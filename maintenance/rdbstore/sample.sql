
-- Example table using sharding annotations
CREATE TABLE IF NOT EXISTS /*_*/mytable/*__#SHARD#__myt_id*/ (
  myt_id integer unsigned NOT NULL,
  myt_name varchar(255) binary NOT NULL default '',
  myt_timestamp varbinary(14) NULL,
  myt_value varbinary(32) NOT NULL default '',

  PRIMARY KEY (myt_id)
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/myt_value ON /*_*/mytable/*__#SHARD#__myt_id*/ (myt_value);
