-- SQL script to create required database users with proper
-- access rights.  This is run from the installation script
-- which replaces the password variables with their values
-- from local settings.
--

GRANT ALL PRIVILEGES ON `{$wgDBname}`.*
 TO '{$wgDBuser}'@'%' IDENTIFIED BY '{$wgDBpassword}';
GRANT ALL PRIVILEGES ON `{$wgDBname}`.*
 TO '{$wgDBuser}'@localhost IDENTIFIED BY '{$wgDBpassword}';
GRANT ALL PRIVILEGES ON `{$wgDBname}`.*
 TO '{$wgDBuser}'@localhost.localdomain IDENTIFIED BY '{$wgDBpassword}';
