CREATE TABLE /*_*/datamodel_sample (
  ds_id varbinary(32) NOT NULL PRIMARY KEY,
  ds_shard integer unsigned NOT NULL,
  ds_title varbinary(255) NOT NULL DEFAULT '',
  ds_email varbinary(255) DEFAULT NULL,
  ds_visible boolean NOT NULL,
  ds_timestamp binary(14) DEFAULT NULL
) /*$wgDBTableOptions*/;
