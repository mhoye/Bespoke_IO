#!/usr/bin/perl

# Byteshifting program for mozilla's netscape.cfg files

# Old netscape 4.x uses a bytechift of 7
#   To decode: moz-byteshift.pl -s -7 <netscape.cfg >netscape.cfg.txt
#   To encode: moz-byteshift.pl -s  7 <netscape.cfg.txt >netscape.cfg

# Mozilla uses a byteshift of 13
#   To decode: moz-byteshift.pl -s -13 <netscape.cfg >netscape.cfg.txt
#   To encode: moz-byteshift.pl -s  13 <netscape.cfg.txt >netscape.cfg

# To activate the netscape.cfg file, place the encoded netscape.cfg file
# into your C:\Program Files\mozilla.org\Mozilla directory.
# Then add the following line to your
# C:\Program Files\mozilla.org\Mozilla\defaults\pref\all.js file :
# pref("general.config.filename", "mozilla.cfg");

use encoding 'latin1';
use strict;
use Getopt::Std;

use vars qw/$opt_s/;

getopts("s:");
if(!defined $opt_s) {
  die "Missing shift\n";
}

my $buffer;
while(1) {
  binmode(STDIN, ":raw");
  my $n=sysread STDIN, $buffer, 1;
  if($n == 0) {
    last;
  }
  my $byte = unpack("c", $buffer);
  $byte += 512 + $opt_s;
  $buffer = pack("c", $byte);
  binmode(STDOUT, ":raw");
  syswrite STDOUT, $buffer, 1;
}
