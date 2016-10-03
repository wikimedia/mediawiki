-- Primary key in change_tag table

ALTER TABLE /*$wgDBprefix*/change_tag
	ADD COLUMN ct_id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	ADD PRIMARY KEY (ct_id);
