-- Primary key and expiry column in user_groups table

ALTER TABLE /*$wgDBprefix*/user_groups
  ADD COLUMN ug_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
  ADD COLUMN ug_expiry varbinary(14) NULL default NULL,
  ADD PRIMARY KEY (ug_id),
  ADD INDEX ug_expiry (ug_expiry);
