"""SCons.Tool.Packaging

SCons Packaging Tool.
"""

#
# Copyright (c) 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010 The SCons Foundation
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be included
# in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
# KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
# WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

__revision__ = "src/engine/SCons/Tool/packaging/__init__.py 5134 2010/08/16 23:02:40 bdeegan"

__all__ = [ 'msi' ]

#
# Utility and Builder function
#

def stripinstallbuilder(target, source, env):
    """ strips the install builder action from the source list and stores
    the final installation location as the "PACKAGING_INSTALL_LOCATION" of
    the source of the source file. This effectively removes the final installed
    files from the source list while remembering the installation location.

    It also warns about files which have no install builder attached.
    """
    def has_no_install_location(file):
        return not (file.has_builder() and\
            hasattr(file.builder, 'name') and\
            (file.builder.name=="InstallBuilder" or\
             file.builder.name=="InstallAsBuilder"))

    if len(list(filter(has_no_install_location, source))):
        warn(Warning, "there are files to package which have no\
        InstallBuilder attached, this might lead to irreproducible packages")

    n_source=[]
    for s in source:
        if has_no_install_location(s):
            n_source.append(s)
        else:
            for ss in s.sources:
                n_source.append(ss)
                copy_attr(s, ss)
                setattr(ss, 'PACKAGING_INSTALL_LOCATION', s.get_path())

    return (target, n_source)

# Local Variables:
# tab-width:4
# indent-tabs-mode:nil
# End:
# vim: set expandtab tabstop=4 shiftwidth=4:
