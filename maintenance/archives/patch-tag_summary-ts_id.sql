-- Primary key in tag_summary table

ALTER TABLE /*$wgDBprefix*/tag_summary
	ADD COLUMN ts_id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	ADD PRIMARY KEY (ts_id);
