CREATE TABLE /*_*/site_stats_tmp (
  -- The single row should contain 1 here.
  ss_row_id int unsigned NOT NULL PRIMARY KEY,

  -- Total number of edits performed.
  ss_total_edits bigint unsigned default 0,

  -- An approximate count of pages matching the following criteria:
  -- * in namespace 0
  -- * not a redirect
  -- * contains the text '[['
  -- See Article::isCountable() in includes/Article.php
  ss_good_articles bigint unsigned default 0,

  -- Total pages, theoretically equal to SELECT COUNT(*) FROM page; except faster
  ss_total_pages bigint default '-1',

  -- Number of users, theoretically equal to SELECT COUNT(*) FROM user;
  ss_users bigint default '-1',

  -- Number of users that still edit
  ss_active_users bigint default '-1',

  -- Number of images, equivalent to SELECT COUNT(*) FROM image
  ss_images int default 0
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/site_stats_tmp(ss_row_id, ss_total_edits, ss_good_articles, ss_total_pages, ss_users, ss_active_users, ss_images)
	SELECT ss_row_id, ss_total_edits, ss_good_articles, ss_total_pages, ss_users, ss_active_users, ss_images FROM /*_*/site_stats;

DROP TABLE /*_*/site_stats;

ALTER TABLE /*_*/site_stats_tmp RENAME TO /*_*/site_stats;
