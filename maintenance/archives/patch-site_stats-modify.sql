ALTER TABLE /*_*/site_stats
	ALTER ss_total_edits SET DEFAULT NULL,
	ALTER ss_good_articles SET DEFAULT NULL,
	MODIFY COLUMN ss_total_pages bigint unsigned DEFAULT NULL,
	MODIFY COLUMN ss_users bigint unsigned DEFAULT NULL,
	MODIFY COLUMN ss_active_users bigint unsigned DEFAULT NULL,
	MODIFY COLUMN ss_images bigint unsigned DEFAULT NULL;
