--
-- patch-categorylinks-better-collation.sql
--
-- T2164, T3211, T25682.  This is the second version of this patch; the
-- changes are also incorporated into patch-categorylinks-better-collation2.sql,
-- for the benefit of trunk users who applied the original.
--
-- Due to T27254, the length limit of 255 bytes for cl_sortkey_prefix
-- is also enforced in php. If you change the length of that field, make
-- sure to also change the check in LinksUpdate.php.
ALTER TABLE /*$wgDBprefix*/categorylinks
	CHANGE COLUMN cl_sortkey cl_sortkey varbinary(230) NOT NULL default '',
	ADD COLUMN cl_sortkey_prefix varchar(255) binary NOT NULL default '',
	ADD COLUMN cl_collation varbinary(32) NOT NULL default '',
	ADD COLUMN cl_type ENUM('page', 'subcat', 'file') NOT NULL default 'page',
-- rm'd in 1.27	ADD INDEX (cl_collation),
	DROP INDEX cl_sortkey,
	ADD INDEX cl_sortkey (cl_to, cl_type, cl_sortkey, cl_from);
INSERT IGNORE INTO /*$wgDBprefix*/updatelog (ul_key) VALUES ('cl_fields_update');
