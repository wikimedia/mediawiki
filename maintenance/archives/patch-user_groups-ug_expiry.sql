-- Add expiry column in user_groups table

ALTER TABLE /*$wgDBprefix*/user_groups
  ADD COLUMN ug_expiry varbinary(14) NULL default NULL,
  ADD INDEX ug_expiry (ug_expiry);
