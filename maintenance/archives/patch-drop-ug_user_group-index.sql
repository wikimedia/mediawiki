-- Drop redundant unique index, to be replaced by a primary key in the next
-- sequential update (patch-user_groups-ug_expiry.sql) 

ALTER TABLE /*$wgDBprefix*/user_groups
  DROP INDEX ug_user_group;
