ALTER TABLE /*_*/site_stats
	ALTER ss_total_edits SET DEFAULT NULL,
	ALTER ss_good_articles SET DEFAULT NULL,
	ALTER ss_total_pages SET DEFAULT NULL,
	ALTER ss_users SET DEFAULT NULL,
	ALTER ss_active_users SET DEFAULT NULL,
	ALTER ss_images SET DEFAULT NULL;
