--- August 2013
--- New field differentiates between types of RC_EXTERNAL changes

ALTER TABLE /*$wgDBprefix*/recentchanges ADD `rc_external_type` VARBINARY(16) NULL default NULL;
