#!/usr/bin/env python

import os, re, sys
from shutil import copy, copytree, move
import subprocess
from subprocess import Popen
#import argparse # import ArgumentParser
from optparse import OptionParser
import urllib
import firefoxMsi

# Default values
PARTNERS_DIR = '../partners_thunderbird'
BUILD_NUMBER = '1'
STAGING_SERVER = 'stage.mozilla.org'
HGROOT = 'http://hg.mozilla.org'

SEVENZIP_BIN = '7za'

#########################################################################
# Source:
# http://stackoverflow.com/questions/377017/test-if-executable-exists-in-python
def which(program):
    def is_exe(fpath):
        return os.path.exists(fpath) and os.access(fpath, os.X_OK)

    fpath, fname = os.path.split(program)
    if fpath:
        if is_exe(program):
            return program
    else:
        for path in os.environ["PATH"].split(os.pathsep):
            exe_file = os.path.join(path, program)
            if is_exe(exe_file):
                return exe_file

    return None
#########################################################################
def mkdir(dir, mode=0777):
    if not os.path.exists(dir):
        return os.makedirs(dir, mode)
    return True

#########################################################################
def isLinux(platform):
    if (platform.find('linux') != -1):
        return True
    return False

#########################################################################
def isLinux32(platform):
    if (platform.find('linux32') != -1 or platform.find('linux-i686') != -1):
        return True
    return False

#########################################################################
def isLinux64(platform):
    if (platform.find('linux64') != -1 or platform.find('linux-x86_64') != -1):
        return True
    return False

#########################################################################
def isMac(platform):
    if (platform.find('mac') != -1):
        return True
    return False

#########################################################################
def isMac32(platform):
    return isMac(platform)

#########################################################################
def isMac64(platform):
    if (platform.find('mac64') != -1):
        return True
    return False

#########################################################################
def isWin(platform):
    if (platform.find('win') != -1):
        return True
    return False

#########################################################################
def isWin32(platform):
    if (platform.find('win32') != -1):
        return True
    return False

#########################################################################
def isWin64(platform):
    if (platform.find('win64') != -1 or \
        platform.find('win64-x86_64') != -1):
        return True
    return False

#########################################################################

def printSeparator():
    print "##################################################"

#########################################################################
def getFormattedPlatform(platform):
    '''Returns the platform in the format used in building package names.
    '''
    if isLinux64(platform):
        return "linux-x86_64"
    if isLinux(platform):
        return "linux-i686"
    if isMac64(platform):
        return "mac64"
    if isMac(platform):
        return "mac"
    if isWin64(platform):
        return "win64-x86_64"
    if isWin(platform):
        return "win32"
    return None

#########################################################################
def getFileExtension(version, platform):
    if isLinux(platform):
        return "tar.bz2"
    if isMac(platform):
        return "dmg"
    if isWin(platform):
        if version.startswith('3.0'):
            return "installer.exe"
        else:
            return "exe"
    return None

#########################################################################
def getFilename(version, platform, locale, file_ext):
    '''Returns the properly formatted filename based on the version string.
       File location/nomenclature changed starting with 3.5.
    '''
    version_formatted = version

    if isLinux(platform):
        return "thunderbird-%s.%s" % (version,
                                  file_ext)
    if isMac(platform):
        return "Thunderbird %s.%s" % (version_formatted,
                                  file_ext)
    if isWin(platform):
        return "Thunderbird Setup %s.%s" % (version_formatted,
                                        file_ext)

    return None

#########################################################################
def getLocalFilePath(version, base_dir, platform, locale):
    '''Returns the properly formatted filepath based on the version string.
       File location/nomenclature changed starting with 3.5.
    '''
    if version.startswith('3.0'):
        return "%s" % (base_dir)

    return "%s/%s/%s" % (base_dir, platform, locale)

#########################################################################
def retrieveFile(url, file_path):
  failedDownload = False
  try:
    urllib.urlretrieve(url.replace(' ','%20'), file_path)
  except:
    print "exception: n  %s, n  %s, n  %s n  when downloading %s" % \
          (sys.exc_info()[0], sys.exc_info()[1], sys.exc_info()[2], url)
    failedDownload = True

  # remove potentially only partially downloaded file, 
  if failedDownload:
    if os.path.exists(file_path):
      try:
        os.remove(file_path)
      except:
        print "exception: n  %s, n  %s, n  %s n  when trying to remove file %s" %\
              (sys.exc_info()[0], sys.exc_info()[1], sys.exc_info()[2], file_path)
    sys.exit(1)

  return True

#########################################################################
def rmdirRecursive(dir):
    """This is a replacement for shutil.rmtree that works better under
    windows. Thanks to Bear at the OSAF for the code.
    (Borrowed from buildbot.slave.commands)"""
    if not os.path.exists(dir):
        # This handles broken links
        if os.path.islink(dir):
            os.remove(dir)
        return

    if os.path.islink(dir):
        os.remove(dir)
        return

    # Verify the directory is read/write/execute for the current user
    os.chmod(dir, 0700)

    for name in os.listdir(dir):
        full_name = os.path.join(dir, name)
        # on Windows, if we don't have write permission we can't remove
        # the file/directory either, so turn that on
        if os.name == 'nt':
            if not os.access(full_name, os.W_OK):
                # I think this is now redundant, but I don't have an NT
                # machine to test on, so I'm going to leave it in place
                # -warner
                os.chmod(full_name, 0600)

        if os.path.isdir(full_name):
            rmdirRecursive(full_name)
        else:
            # Don't try to chmod links
            if not os.path.islink(full_name):
                os.chmod(full_name, 0700)
            os.remove(full_name)
    os.rmdir(dir)

#########################################################################
def shellCommand(cmd):
    # Shell command output gets dumped immediately to stdout, whereas
    # print statements get buffered unless we flush them explicitly.
    sys.stdout.flush()
    p = Popen(cmd, shell=True)
    (rpid, ret) = os.waitpid(p.pid, 0)
    if ret != 0:
        ret_real = (ret & 0xFF00) >> 8
        print "Error: shellCommand had non-zero exit status: %d" % ret_real
        print "command was: %s" % cmd
        sys.exit(ret_real)
    return True

#########################################################################
class RepackBase(object):
    def __init__(self, build, partner_dir, build_dir, working_dir, final_dir,
                 platform_formatted, repack_info):
        self.base_dir = os.getcwd()
        self.build = build
        self.full_build_path = "%s/%s/%s" % (self.base_dir, build_dir, build)
        self.full_partner_path = "%s/%s" % (self.base_dir, partner_dir)
        self.working_dir = working_dir
        self.final_dir = final_dir
        self.platform_formatted = platform_formatted
        self.repack_info = repack_info
        mkdir(self.working_dir)

    def announceStart(self):
        print "### Repacking %s build \"%s\"" % (self.platform_formatted, 
                                                 self.build)

    def announceSuccess(self):
        print "### Done repacking %s build \"%s\"" % (self.platform_formatted, 
                                                      self.build)
        print

    def unpackBuild(self):
        copy(self.full_build_path, '.')
        print "copying from %s to %s" % (self.full_build_path, self.working_dir)

    def createOverrideIni(self, partner_path):
        ''' Some partners need to override the migration wizard. This is done
            by adding an override.ini file to the base install dir.
        '''
        filename='%s/override.ini' % partner_path
        if self.repack_info.has_key('migrationWizardDisabled'):
            if not os.path.isfile(filename):
                f=open(filename,'w')
                f.write('[XRE]\n')
                f.write('EnableProfileMigrator=0\n')
                f.close()

    def copyFiles(self, platform_dir):
        # Check whether we've already copied files over for this partner.
        if not os.path.exists(platform_dir):
            mkdir(platform_dir)
            print "creating %s" % (platform_dir)
             
    def repackBuild(self):
        pass

    def cleanup(self):
        if self.final_dir == '.':
            move(self.build, '..')
        else:
            move(self.build, "../%s" % self.final_dir)

    def doRepack(self):
        self.announceStart()
        os.chdir(self.working_dir)
        self.unpackBuild()
        self.copyFiles()
        self.repackBuild()
        self.announceSuccess()
        self.cleanup()
        os.chdir(self.base_dir)

#########################################################################
class RepackWin(RepackBase):
    def __init__(self, build, partner_dir, build_dir, working_dir, final_dir,
                 platform_formatted, repack_info):
        super(RepackWin, self).__init__(build, partner_dir, build_dir, 
                                        working_dir, final_dir,
                                        platform_formatted, repack_info)

    def copyFiles(self):
        super(RepackWin, self).copyFiles('core')

    def repackBuild(self):
        if options.quiet:
            zip_redirect = ">/dev/null"
        else:
            zip_redirect = "" 
        zip_cmd = "%s a \"%s\" core %s" % (SEVENZIP_BIN, 
                                                   self.build,
                                                   zip_redirect)
        shellCommand(zip_cmd)
        self.doRepackMsi()

    def doRepackMsi(self):
        basename, extension = os.path.splitext(self.build)
        #basename = re.escape(basename)
        # keeping the workspace file in as small of absolute path as possible because of a bug in WiX
        base_dir = os.getcwd()
        print os.getcwd()
        os.chdir( "../../../../../../" )
        print os.getcwd()
        os.system( "rm -rf m" )

        # create an msi workspace folder
        # using the name m because it is small
        mkdir("m")
        if options.quiet:
            zip_redirect = ">/dev/null"
        else:
            zip_redirect = ""
        zip_cmd = "%s x \"%s\" -om %s -aoa" % (SEVENZIP_BIN, 
                                                   base_dir + "/" + self.build,
                                                   zip_redirect)

        shellCommand(zip_cmd)
        #os.system( "rm m/core/distribution/extensions/exists" )
        print ""
        
        firefoxMsi.build("firefox", "m", { "name": "firefox MSI", "version": "0.1", "vendor": "Bespoke IO Custom Software Deployment Systems", "summary": "Tailored Firefox for the Enterprise" }, partner_dir)

        os.chdir( base_dir )

        success = os.system("mv ../../../../../../firefox.msi ../" + self.final_dir + "/" + re.escape(basename) + ".msi")
        print ""

        # cleaning temporary workspace
        os.system("rm -r ../../../../../../m")
        
        if success == 0:
            print "### Done repacking %s build \"%s\"" % (self.platform_formatted, 
                                                          basename + ".msi")
        else:
            print "error creating " + basename + ".msi"
        print ""

    def doRepack(self):
        super(RepackWin, self).doRepack()
        



if __name__ == '__main__':
    error = False
    partner_builds = {}
    default_platforms = ['linux-i686', 'linux-x86_64', 'mac', 'mac64', 'win32']
    repack_build = {
                    'win32':         RepackWin,
                    'win64-x86_64':  RepackWin
#                    'linux-i686':    RepackLinux,
#                    'linux-x86_64':  RepackLinux,
#                    'mac':           RepackMac,
#                    'mac64':         RepackMac,
    }

    parser = OptionParser(usage="usage: %prog [options]")
    parser.add_option("--platform",
                        action="append",
                        dest="platforms",
                        help="Specify platform (multiples ok, e.g. --platform win32 --platform mac64)." )
    parser.add_option("-d",
                        "--partners-dir",
                        action="store",
                        dest="partners_dir",
                        default=PARTNERS_DIR, 
                        help="Specify the directory where the partner config files are found.")
    parser.add_option("-p",
                        "--partner",
                        action="store",
                        dest="partner",
                        help="Repack for a single partner, specified by name.")
    parser.add_option("--verify-only",
                        action="store_true",
                        dest="verify_only",
                        default=False,
                        help="Check for existing partner repacks.")
    parser.add_option("--nightly-dir",
                        action="store",
                        dest="nightly_dir",
                        default="thunderbird/nightly",
                        help="Specify the subdirectory where candidates live (default firefox/nightly).")
    parser.add_option("-n",
                        "--build-number",
                        action="store",
                        dest="build_number",
                        default=BUILD_NUMBER,
                        help="Set the build number for repacking")
    parser.add_option("-v",
                        "--version",
                        action="store",
                        dest="version",
                        help="Set the version number for repacking")
    parser.add_option("--staging-server",
                        action="store",
                        dest="staging_server",
                        default=STAGING_SERVER,
                        help="Set the staging server to use for downloading/uploading.")
    parser.add_option("-q",
                        "--quiet",
                        action="store_true",
                        dest="quiet",
                        default=False,
                        help="Suppress standard output from the packaging tools.")
    (options, args) = parser.parse_args()

    if not options.version:
        print "Error: no version"

    if not os.path.isdir(options.partners_dir):
        print "Error: partners dir %s is not a directory." % partners_dir
        error = True

    if not options.platforms:
        options.platforms = default_platforms

    if not options.verify_only:
        if "win32" in options.platforms and not which(SEVENZIP_BIN):
            print "Error: couldn't find the %s executable in PATH." % SEVENZIP_BIN
            error = True

    if error:
        sys.exit(1)

    base_workdir = os.getcwd()

    # Remote directory where we can find the Thunderbird builds
    candidates_web_dir = "/pub/mozilla.org/%s/%s-candidates/build%s" %(options.nightly_dir, options.version, options.build_number)
   
    # Local directories where we can store and find builds
    original_builds_dir = "original_builds/thunderbird_%s/build%s" % (options.version, str(options.build_number))  
    repacked_builds_dir = "repacked_builds/thunderbird_%s/build%s" % (options.version, str(options.build_number))  
    if not options.verify_only:
        mkdir(original_builds_dir)
        mkdir(repacked_builds_dir)
        printSeparator()
    

    # Go through all the folders in partners_thunderbird
    for partner_dir in os.listdir(options.partners_dir):
        if options.partner:
            if options.partner != partner_dir:
                continue
        full_partner_dir = "%s/%s" % (options.partners_dir, partner_dir)
        if not os.path.isdir(full_partner_dir):
            continue
        if not options.verify_only:
            print "### Starting repack process for partner: %s " % partner_dir
        else:
            print "### Verifying existing repacks for partner: %s" % partner_dir

        partner_repack_dir = "%s/%s" % (repacked_builds_dir, partner_dir)
        if not options.verify_only:
            if os.path.exists(partner_repack_dir):
                rmdirRecursive(partner_repack_dir)
            mkdir(partner_repack_dir)
            working_dir = "%s/working" % partner_repack_dir
            mkdir(working_dir)

        repack_info = {
            'locales': ["en-US"],
            'platforms': ["win"]
        }

        # Foreach locale in repack_info['locales']
        #   foreach platform in repack_info['platforms']
        for locale in repack_info['locales']:
            for platform in repack_info['platforms']:
                platform_formatted = getFormattedPlatform(platform)
                file_ext = getFileExtension(options.version,
                                            platform_formatted)
                filename = getFilename(options.version,
                                       platform_formatted,
                                       locale,
                                       file_ext)

                local_filepath = getLocalFilePath(options.version,
                                                  original_builds_dir,
                                                  platform_formatted,
                                                  locale)
                if not options.verify_only:
                    mkdir(local_filepath)
                local_filename = "%s/%s" % (local_filepath, filename)
                final_dir = "%s/%s" % (platform_formatted, locale)
                if not options.verify_only:
                        mkdir("%s/%s" % (partner_repack_dir, final_dir))

                # Check to see if build is on disk. If not, download
                if not options.verify_only:
                    if os.path.exists(local_filename):
                        print "### Found %s on disk, not downloading" % local_filename
                    else:
                        # Download original build from stage
                        print "### Downloading %s" % local_filename
                        os.chdir(local_filepath)
                        candidates_dir = candidates_web_dir
                        if options.version.startswith('2.0'):
                            original_build_url = "http://%s%s/%s" % \
                                                 (options.staging_server,
                                                  candidates_dir,
                                                  filename
                                                 )
                        else:
                            original_build_url = "http://%s%s/%s/%s/%s" % \
                                                 (options.staging_server,
                                                  candidates_dir,
                                                  platform_formatted,
                                                  locale,
                                                  filename
                                                 )
                        retrieveFile(original_build_url, filename)
                        os.chdir(base_workdir);

                    # Make sure we have the local file now
                    if not os.path.exists(local_filename):
                        print "Error: Unable to retrieve %s" % filename
                        sys.exit(1)

                    repackObj = repack_build[platform_formatted](filename,
                                                                 full_partner_dir,
                                                                 local_filepath,
                                                                 working_dir,
                                                                 final_dir,
                                                                 platform_formatted,
                                                                 repack_info)
                    repackObj.doRepack() 

                else:
                    repacked_build = "%s/%s/%s" % (partner_repack_dir, final_dir, filename)
                    if not os.path.exists(repacked_build):
                        print "Error: missing expected repack for partner %s (%s/%s): %s" % (partner_dir, platform_formatted, locale, filename)
                        error = True

        if not options.verify_only:
            # Check to see whether we repacked any signed Windows builds. If we
            # did we need to do some scrubbing before we upload them for
            # re-signing.
            if 'win32' in repack_info['platforms'] and options.use_signed:
                repackSignedBuilds(repacked_builds_dir)
            # Remove our working dir so things are all cleaned up and ready for
            # easy upload.
            rmdirRecursive(working_dir)
            printSeparator()

    if error:
        sys.exit(1)
   
