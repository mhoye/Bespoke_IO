#!/bin/sh

echo
echo This script naively sends setup.sql and current.sql into mysql, 
echo setting up database \(besds\) a user, \(besds_admin\) and populating
echo the DB with all the necessary data.
echo
echo If you have any qualms about this, hit control-C now to exit.
echo Press any other key to continue.
echo
read x

mysql < setup.sql 
mysql < current.sql 

