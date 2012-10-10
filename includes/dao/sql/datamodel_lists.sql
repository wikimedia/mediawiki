CREATE TABLE /*_*/datamodel_lists (
  list varbinary(255) NOT NULL DEFAULT '',
  id integer unsigned NOT NULL,
  shard varbinary(255) NOT NULL DEFAULT '',
  sort varbinary(255) DEFAULT '',
  PRIMARY KEY (list, id)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/shard ON /*_*/datamodel_lists (shard);
CREATE INDEX /*i*/sort ON /*_*/datamodel_lists (sort);
