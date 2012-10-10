CREATE TABLE /*_*/datamodel_conditions (
  entry_table varbinary(255) NOT NULL,
  entry_id varbinary(32) NOT NULL,
  entry_shard varbinary(255) NOT NULL,
  conditionmatch varbinary(255) NOT NULL,
  PRIMARY KEY (entry_table, entry_id, entry_shard, conditionmatch)
) /*$wgDBTableOptions*/;

CREATE TABLE /*_*/datamodel_sorts (
  entry_table varbinary(255) NOT NULL,
  entry_id varbinary(32) NOT NULL,
  entry_shard varbinary(255) NOT NULL,
  sort varbinary(255) NOT NULL,
  sortvalue_string varbinary(255) DEFAULT NULL,
  sortvalue_number double DEFAULT NULL,
  PRIMARY KEY (entry_table, entry_id, entry_shard, sort)
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/sortvalue_string ON /*_*/datamodel_sorts (sortvalue_string);
CREATE INDEX /*i*/sortvalue_number ON /*_*/datamodel_sorts (sortvalue_number);
