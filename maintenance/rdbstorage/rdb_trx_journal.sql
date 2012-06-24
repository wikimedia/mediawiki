-- Table to log possible failures for row changes that affect denormalized shards
CREATE TABLE IF NOT EXISTS rdb_trx_journal (
  -- Transaction ID
  rtj_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  -- Transaction commit UNIX timestamp
  rtj_time int unsigned NOT NULL,
  -- Status (0=new, 1=verified)
  rtj_state tinyint unsigned NOT NULL DEFAULT 0,
  -- Serialized blob; stores a list of affected DB table row references.
  -- References are maps of (wiki, table, shard column, column value, ID column, unique ID).
  -- See the ExternalRDBStoreTrxJournal class for the full format.
  rtj_blob blob
);
