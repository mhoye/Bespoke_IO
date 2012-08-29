#!/bin/sh

SUPPORTED_FIREFOX='4.0.1 5.0.1 6.0.2 7.0.1 8.0.1 9.0.1 10.0.2 10.0.4esr 11.0 12.0'
SUPPORTED_THUNDERBIRD='10.0.1 10.0.4esr 11.0.1 12.0.1'
POPULAR_LANGUAGES='de en-GB en-US es-AR es-ES es-MX fr ja ru zh-CN zh-TW'

# The general form of the URLs is:
#
# https://ftp.mozilla.org/pub/mozilla.org/firefox/releases/7.0.1/win32/en-US/Firefox%20Setup%207.0.1.exe
#
# We use https because it's not subject to the same redirection as http is, so we can actually get to the
# versions of Firefox older than current without suffering from the redirect.

 
echo 'Prefetching Firefox...'

for PROGRAM in firefox 
do
	for VERSION in $SUPPORTED_FIREFOX
	do
		for I18N in $POPULAR_LANGUAGES
		do
			mkdir -p $VERSION/build1/win32/$I18N
			echo Fetching $PROGRAM $VERSION...
			curl https://ftp.mozilla.org/pub/mozilla.org/$PROGRAM/releases/$VERSION/win32/$I18N/Firefox%20Setup%20$VERSION.exe > $VERSION/build1/win32/$I18N/Firefox\ Setup\ $VERSION.exe
 	
		done	
	done
done

echo 'Prefetching Thunderbird...'

for PROGRAM in thunderbird
do
  for VERSION in $SUPPORTED_THUNDERBIRD
  do
    for I18N in $POPULAR_LANGUAGES
    do
      echo Fetching $PROGRAM $VERSION...
			mkdir -p thunderbird_$VERSION/build1/win32/$I18N/
      curl https://ftp.mozilla.org/pub/mozilla.org/$PROGRAM/releases/$VERSION/win32/$I18N/Thunderbird%20Setup%20$VERSION.exe >  thunderbird_$VERSION/build1/win32/$I18N/Thunderbird\ Setup\ $VERSION.exe
    done
  done
done


