#!/bin/sh

echo 'Making workspace and logging directories, setting permissions...'

mkdir repack_assets && chmod 775 repack_assets
mkdir application/cache && chmod 775 application/cache
mkdir application/logs && chmod 775 application/logs

mv htaccess-dist .htaccess


