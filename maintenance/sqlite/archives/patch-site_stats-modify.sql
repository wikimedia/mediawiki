DROP TABLE IF EXISTS /*_*/site_stats_tmp;

/* Copy & paste from maintenance/tables.sql begins, just table name changed */
CREATE TABLE /*_*/site_stats_tmp (
  -- The single row should contain 1 here.
  ss_row_id int unsigned NOT NULL,

  -- Total number of edits performed.
  ss_total_edits bigint unsigned default NULL,

  -- An approximate count of pages matching the following criteria:
  -- * in namespace 0
  -- * not a redirect
  -- * contains the text '[['
  -- See Article::isCountable() in includes/Article.php
  ss_good_articles bigint unsigned default NULL,

  -- Total pages, theoretically equal to SELECT COUNT(*) FROM page; except faster
  ss_total_pages bigint unsigned default NULL,

  -- Number of users, theoretically equal to SELECT COUNT(*) FROM user;
  ss_users bigint unsigned default NULL,

  -- Number of users that still edit
  ss_active_users bigint unsigned default NULL,

  -- Number of images, equivalent to SELECT COUNT(*) FROM image
  ss_images bigint unsigned default NULL
) /*$wgDBTableOptions*/;
/* Copy & paste from maintenance/tables.sql ends */

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


/* Copy & paste from maintenance/tables.sql begins */
-- Pointless index to assuage developer superstitions
CREATE UNIQUE INDEX /*i*/ss_row_id ON /*_*/site_stats (ss_row_id);
/* Copy & paste from maintenance/tables.sql ends */
