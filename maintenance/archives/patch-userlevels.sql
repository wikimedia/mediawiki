-- Oct. 1st 2004 - Ashar Voultoiz
-- Implement the new sitelevels
--
-- This is under development to provide a showcase in HEAD :o)

-- Hold group name and description
CREATE TABLE /*$wgDBprefix*/`group` (
  group_id int(5) unsigned NOT NULL auto_increment,
  group_name varchar(50) NOT NULL default '',
  group_description varchar(255) NOT NULL default '',
  group_rights tinyblob,
  PRIMARY KEY  (group_id)
);

-- Relation table between user and groups
CREATE TABLE /*$wgDBprefix*/user_groups (
	ug_user int(5) unsigned NOT NULL default '0',
	ug_group int(5) unsigned NOT NULL default '0',
	PRIMARY KEY  (ug_user,ug_group)
);
