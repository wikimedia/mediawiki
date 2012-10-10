CREATE TABLE /*_*/datamodel_sample (
  id integer unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  shard integer unsigned NOT NULL,
  title varbinary(255) NOT NULL DEFAULT '',
  email varbinary(255) DEFAULT NULL,
  visible boolean NOT NULL,
  timestamp binary(14) DEFAULT NULL
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/shard ON /*_*/datamodel_sample (shard);
