-- Oct. 24 2004
-- Adds the group_rights field missing from early dev work

-- Hold group name and description
ALTER TABLE `group` ADD group_rights tinyblob;
