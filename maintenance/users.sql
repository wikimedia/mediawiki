-- SQL script to create required database users with proper
-- access rights.  This is run from the installation script
-- which replaces the password variables with their values
-- from local settings.
--

GRANT ALL ON `{$wgDBname}`.*
 TO {$wgDBadminuser}@'%' IDENTIFIED BY '{$wgDBadminpassword}';
GRANT ALL ON `{$wgDBname}`.*
 TO {$wgDBadminuser}@localhost IDENTIFIED BY '{$wgDBadminpassword}';
GRANT ALL ON `{$wgDBname}`.*
 TO {$wgDBadminuser}@localhost.localdomain IDENTIFIED BY '{$wgDBadminpassword}';

GRANT DELETE,INSERT,SELECT,UPDATE ON `{$wgDBname}`.*
 TO {$wgDBuser}@'%' IDENTIFIED BY '{$wgDBpassword}';
GRANT DELETE,INSERT,SELECT,UPDATE ON `{$wgDBname}`.*
 TO {$wgDBuser}@localhost IDENTIFIED BY '{$wgDBpassword}';
GRANT DELETE,INSERT,SELECT,UPDATE ON `{$wgDBname}`.*
 TO {$wgDBuser}@localhost.localdomain IDENTIFIED BY '{$wgDBpassword}';

GRANT SELECT (user_id,user_name,user_rights,user_options) ON `{$wgDBname}`.user
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.cur
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.old
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.archive
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.links
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.brokenlinks
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.imagelinks
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.site_stats
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.ipblocks
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.image
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.oldimage
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.recentchanges
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.watchlist
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.math
 TO {$wgDBsqluser}@'%' IDENTIFIED BY '{$wgDBsqlpassword}';

GRANT SELECT (user_id,user_name,user_rights,user_options)
 ON `{$wgDBname}`.user
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.cur
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.old
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.archive
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.links
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.brokenlinks
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.imagelinks
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.site_stats
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.ipblocks
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.image
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.oldimage
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.recentchanges
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.watchlist
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.math
 TO {$wgDBsqluser}@localhost IDENTIFIED BY '{$wgDBsqlpassword}';

GRANT SELECT (user_id,user_name,user_rights,user_options)
 ON `{$wgDBname}`.user
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.cur
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.old
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.archive
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.links
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.brokenlinks
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.imagelinks
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.site_stats
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.ipblocks
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.image
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.oldimage
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.recentchanges
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.watchlist
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';
GRANT SELECT ON `{$wgDBname}`.math
 TO {$wgDBsqluser}@localhost.localdomain IDENTIFIED BY '{$wgDBsqlpassword}';

