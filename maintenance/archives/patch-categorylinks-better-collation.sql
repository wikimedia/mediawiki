--
-- patch-categorylinks-better-collation.sql
--
-- Bugs 164, 1211, 23682.  This is the second version of this patch; the
-- changes are also incorporated into patch-categorylinks-better-collation2.sql,
-- for the benefit of trunk users who applied the original.
ALTER TABLE /*$wgDBprefix*/categorylinks
	CHANGE COLUMN cl_sortkey cl_sortkey varbinary(230) NOT NULL default '',
	ADD COLUMN cl_sortkey_prefix varchar(255) binary NOT NULL default '',
	ADD COLUMN cl_collation varbinary(32) NOT NULL default '',
	ADD COLUMN cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page',
	ADD INDEX (cl_collation),
	DROP INDEX cl_sortkey,
	ADD INDEX cl_sortkey (cl_to, cl_type, cl_sortkey, cl_from);
INSERT IGNORE INTO /*$wgDBprefix*/updatelog (ul_key) VALUES ('cl_fields_update');
