#!/usr/bin/env python

# Builds a .msi file of firefox

import os, glob
from repackMsi.packaging import *

# destination  - string for created firefox.msi file and relative path of where to put it (path must exist)
# source       - string of path to Firefox Setup 3.6.10 source directory
# specs        - metadata about the msi: name, version, vendor, summary (optional) also, any spec (name, version, etc can be optional)

# example: build( "./firefox.msi", "./Firefox Setup 3.6.10", { "name": "firefox", "version": "0.1", "vendor": "feral", "summary": "this is a test" } )

def build( destination, source, specs = {} ):

    # a class to supply the output name for the .wxs file
    
    # path - a string containing the name and path of the .wxs file to be generated
    
    # example: Target( "./firefox.wxs" )
    class Target:
        def __init__( self, path ):
            self.abspath = path
        def get_dir( self ):
            return ""

    # a class defining a single source file
    
    # path - string containing a path to a source file
    # name - string containing the name of the source file
    # installLocation - where the msi will install the file
    
    # example: Source( "./", "firefox.exe", "installDir" )
    class Source:
        def __init__( self, path, name, installLocation ):
            self.path = path
            self.name = name
            self.PACKAGING_INSTALL_LOCATION =  installLocation.replace("core/", "")
        def get_path( self ):
            return self.path.replace("/", "\\") + "\\" + self.name

    # a function to walk through directories and return a tree of objects of the directory structure
    # multiple directories are merged into one root
    
    # sourceArray - an array of strings containing names of directories to be objectified
    
    # example for Firefox 3.6.13
    # sourceArray = [ 'Firefox Setup 3.6.10\\nonlocalized', 'Firefox Setup 3.6.10\\optional', 'Firefox Setup 3.6.10\\localized' ]
    # packageSource( sourceArray )
    def packageSource( sourceArray ):
        returnArray = []

        # recursive function to walk through a single directory and create an object tree
        def scandirs( path ):
            for currentFile in glob.glob( os.path.join( path, '*' ) ):
                if os.path.isdir( currentFile ):
                    scandirs( currentFile )
                else:
                    returnArray.append( Source( os.path.dirname( currentFile ), os.path.basename( currentFile ), currentFile.split( os.path.sep, 2 )[ 2 ] ) )

        # for loop to merge multiple directories into one root
        for sourceDir in sourceArray:            
            scandirs( sourceDir )
            
        return returnArray
        
    # an array of source directories to be merged
    sourceArray = [ source + '/core' ]

    # a dictonary created to supply specs for the msi
    specsDict   = { "NAME": specs[ "name" ] if 'name' in specs else 'not defined', "VERSION": specs[ "version" ] if 'version' in specs else "0.0", "VENDOR": specs[ "vendor" ] if 'vendor' in specs else 'not defined', "SUMMARY": specs[ "summary" ] if 'summary' in specs else 'not defined' }
    
    # builds the .wxs file from repackMsi.packaging import *


    msi.build_wxsfile( ( Target( destination + ".wxs" ), ), packageSource( sourceArray ), specsDict )

    #os.system( "echo \" \" >> ./core/distribution/extensions/config.ini" ) # fails if config.ini is empty
    os.system(" find . -type f -size 0 -exec /bin/sh -c \"echo \\\" \\\" >> {}\" \\; ")

    # creates the .msi file from the .wxs file
    print "running candle on " + destination + ".wxs"
    os.system( "candle " + destination + ".wxs" )  # creating .wixobj file from the .wxs file
    #print "removing " + destination + ".wxs"
    #os.system( "rm " + destination + ".wxs" )      # cleaning up file
    print "running light on " + destination + ".wixobj"
    os.system( "light -sval " + destination + ".wixobj" ) # creating .msi file from .wixobj file
    print "removing " + destination + ".wixobj"
    os.system( "rm " + destination + ".wixobj" )   # cleaning up file
    print "removing " + destination + ".wixpdb"
    os.system( "rm " + destination + ".wixpdb" )   # cleaning up file
