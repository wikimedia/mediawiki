CREATE TABLE /*_*/user_autocreate_serial (
  uas_shard INT UNSIGNED NOT NULL,
  uas_value INT UNSIGNED NOT NULL,
  PRIMARY KEY(uas_shard)
) /*$wgDBTableOptions*/;
