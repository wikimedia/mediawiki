--
-- patch-categorylinks-better-collation.sql
--
-- Bugs 164, 1211, 23682.
ALTER TABLE /*$wgDBprefix*/categorylinks
	ADD COLUMN cl_sortkey_prefix varchar(255) binary NOT NULL default '',
	ADD COLUMN cl_collation tinyint NOT NULL default 0,
	ADD COLUMN cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page',
	ADD INDEX (cl_collation),
	DROP INDEX cl_sortkey,
	ADD INDEX cl_sortkey (cl_to, cl_type, cl_sortkey, cl_from);
