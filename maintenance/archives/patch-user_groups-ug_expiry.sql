-- Primary key and expiry column in user_groups table

ALTER TABLE /*$wgDBprefix*/user_groups
  DROP INDEX ug_user_group,
  ADD PRIMARY KEY (ug_user, ug_group),
  ADD COLUMN ug_expiry varbinary(14) NULL default NULL,
  ADD INDEX ug_expiry (ug_expiry);
