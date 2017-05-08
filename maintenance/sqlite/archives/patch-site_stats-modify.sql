DROP TABLE IF EXISTS /*_*/site_stats_tmp;

-- Create the temporary table. The following part
-- is copied & pasted from the changed tables.sql
-- file besides having an other table name.
CREATE TABLE /*_*/site_stats_tmp (
  ss_row_id int unsigned NOT NULL PRIMARY KEY,
  ss_total_edits bigint unsigned default NULL,
  ss_good_articles bigint unsigned default NULL,
  ss_total_pages bigint unsigned default NULL,
  ss_users bigint unsigned default NULL,
  ss_active_users bigint unsigned default NULL,
  ss_images bigint unsigned default NULL
) /*$wgDBTableOptions*/;

-- Move the data from the old to the new table
INSERT OR IGNORE INTO /*_*/site_stats_tmp (
	ss_row_id,
	ss_total_edits,
	ss_good_articles,
	ss_total_pages,
	ss_active_users,
	ss_images
) SELECT
	ss_row_id,
	ss_total_edits,
	ss_good_articles,
	ss_total_pages,
	ss_active_users,
	ss_images
FROM /*_*/site_stats;

DROP TABLE /*_*/site_stats;

ALTER TABLE /*_*/site_stats_tmp RENAME TO /*_*/site_stats;
