-- Rename groups table to groups, which is not a keyword
-- It was called group in a few alpha versions

RENAME TABLE /*$wgDBprefix*/`group` TO /*$wgDBprefix*/groups;
ALTER TABLE /*$wgDBprefix*/groups 
	CHANGE group_id gr_id int(5) unsigned NOT NULL auto_increment,
	CHANGE group_name gr_name varchar(50) NOT NULL default '',
	CHANGE group_description gr_description varchar(255) NOT NULL default '',
	CHANGE group_rights gr_rights tinyblob;

