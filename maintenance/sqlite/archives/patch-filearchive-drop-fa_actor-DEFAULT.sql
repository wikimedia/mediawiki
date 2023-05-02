--
-- patch-filearchive-drop-fa_actor-DEFAULT.sql
--
-- T246077. Drop DEFAULT from fa_actor (forgotten in patch-filearchive-drop-fa_user.sql).

BEGIN;

DROP TABLE IF EXISTS /*_*/filearchive_tmp;
CREATE TABLE /*_*/filearchive_tmp (
  fa_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  fa_name TEXT  NOT NULL default '',
  fa_archive_name TEXT  default '',
  fa_storage_group BLOB,
  fa_storage_key BLOB default '',
  fa_deleted_user INTEGER,
  fa_deleted_timestamp BLOB default '',
  fa_deleted_reason_id INTEGER  NOT NULL,
  fa_size INTEGER  default 0,
  fa_width INTEGER default 0,
  fa_height INTEGER default 0,
  fa_metadata BLOB,
  fa_bits INTEGER default 0,
  fa_media_type TEXT default NULL,
  fa_major_mime TEXT default "unknown",
  fa_minor_mime BLOB default "unknown",
  fa_description_id INTEGER  NOT NULL,
  fa_actor INTEGER  NOT NULL,
  fa_timestamp BLOB default '',
  fa_deleted INTEGER  NOT NULL default 0,
  fa_sha1 BLOB NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/filearchive_tmp (
	fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key,
	fa_deleted_user, fa_deleted_timestamp, fa_deleted_reason_id,
	fa_size, fa_width, fa_height, fa_metadata, fa_bits,
	fa_media_type, fa_major_mime, fa_minor_mime, fa_description_id,
	fa_actor, fa_timestamp, fa_deleted, fa_sha1
  ) SELECT
	fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key,
	fa_deleted_user, fa_deleted_timestamp, fa_deleted_reason_id,
	fa_size, fa_width, fa_height, fa_metadata, fa_bits,
	fa_media_type, fa_major_mime, fa_minor_mime, fa_description_id,
	fa_actor, fa_timestamp, fa_deleted, fa_sha1
  FROM /*_*/filearchive;

DROP TABLE /*_*/filearchive;
ALTER TABLE /*_*/filearchive_tmp RENAME TO /*_*/filearchive;
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_actor_timestamp ON /*_*/filearchive (fa_actor,fa_timestamp);
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1);

COMMIT;
