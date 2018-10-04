--
-- patch-categorylinks-better-collation2.sql
--
-- Bugs T2164, T3211, T25682.  This patch exists for trunk users who already
-- applied the first patch in its original version.  The first patch was
-- updated to incorporate the changes as well, so as not to do two alters on a
-- large table unnecessarily for people upgrading from 1.16, so this will be
-- skipped if unneeded.
ALTER TABLE /*$wgDBprefix*/categorylinks
	CHANGE COLUMN cl_sortkey cl_sortkey varbinary(230) NOT NULL default '',
	CHANGE COLUMN cl_collation cl_collation varbinary(32) NOT NULL default '';
INSERT IGNORE INTO /*$wgDBprefix*/updatelog (ul_key) VALUES ('cl_fields_update');
