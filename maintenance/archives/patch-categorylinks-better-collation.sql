--
-- patch-categorylinks-better-collation.sql
--
-- Bugs 164, 1211, 23682.  This is currently experimental and subject to
-- change.  You need to set $wgExperimentalCategorySort = true; to use this.
-- You also need to manually apply any changes that are made to this file,
-- since they will not be automatically applied.  This patch is only intended
-- to work for MySQL for now, without table prefixes, possibly other random
-- limitations.
ALTER TABLE categorylinks
	ADD COLUMN cl_raw_sortkey varchar(255) binary NULL default NULL,
	ADD COLUMN cl_collation tinyint NOT NULL default 0,
	ADD COLUMN cl_type ENUM('page', 'subcat', 'file') NOT NULL,
	ADD INDEX (cl_collation),
	DROP INDEX cl_sortkey,
	ADD INDEX cl_sortkey (cl_to, cl_type, cl_sortkey, cl_from);
