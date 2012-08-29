-- 
-- Set up BeSDS database and user.
--

CREATE DATABASE IF NOT EXISTS besds;

CREATE USER 'besds_admin'@'localhost' IDENTIFIED BY 'besds_admin_password';

GRANT SELECT, INSERT, UPDATE, DELETE, DROP, ALTER ON besds.* TO 'besds_admin'@'localhost' WITH GRANT OPTION;

