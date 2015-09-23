-- field is deprecated and no longer updated as of 1.5
CREATE TABLE /*_*/site_stats_tmp (
  ss_row_id int unsigned NOT NULL,
  ss_total_edits bigint unsigned default 0,
  ss_good_articles bigint unsigned default 0,
  ss_total_pages bigint default '-1',
  ss_users bigint default '-1',
  ss_active_users bigint default '-1',
  ss_images int default 0
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/site_stats_tmp
	SELECT ss_row_id, ss_total_edits, ss_good_articles,
		ss_total_pages, ss_users, ss_active_users, ss_images
		FROM /*_*/site_stats;

DROP TABLE /*_*/site_stats;

ALTER TABLE /*_*/site_stats_tmp RENAME TO /*_*/site_stats;

CREATE UNIQUE INDEX /*i*/ss_row_id ON /*_*/site_stats (ss_row_id);