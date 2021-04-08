-- Modify fa_name and fa_storage_group to be BLOB and drop default from fa_deleted_timestamp and fa_timestamp
CREATE TABLE /*_*/filearchive_tmp (
  fa_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  fa_name BLOB DEFAULT '' NOT NULL, fa_archive_name BLOB DEFAULT '',
  fa_storage_group BLOB DEFAULT NULL,
  fa_storage_key BLOB DEFAULT '', fa_deleted_user INTEGER DEFAULT NULL,
  fa_deleted_timestamp BLOB DEFAULT NULL,
  fa_deleted_reason_id BIGINT UNSIGNED NOT NULL,
  fa_size INTEGER UNSIGNED DEFAULT 0,
  fa_width INTEGER DEFAULT 0, fa_height INTEGER DEFAULT 0,
  fa_metadata BLOB DEFAULT NULL, fa_bits INTEGER DEFAULT 0,
  fa_media_type TEXT DEFAULT NULL, fa_major_mime TEXT DEFAULT 'unknown',
  fa_minor_mime BLOB DEFAULT 'unknown',
  fa_description_id BIGINT UNSIGNED NOT NULL,
  fa_actor BIGINT UNSIGNED NOT NULL,
  fa_timestamp BLOB DEFAULT NULL, fa_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  fa_sha1 BLOB DEFAULT '' NOT NULL
);
INSERT INTO /*_*/filearchive_tmp (
    fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key, fa_deleted_user, fa_deleted_timestamp,
    fa_deleted_reason_id, fa_size, fa_width, fa_height, fa_metadata, fa_bits, fa_media_type, fa_major_mime,
    fa_minor_mime, fa_description_id, fa_actor, fa_timestamp, fa_deleted, fa_sha1
)
SELECT fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key, fa_deleted_user, fa_deleted_timestamp,
       fa_deleted_reason_id, fa_size, fa_width, fa_height, fa_metadata, fa_bits, fa_media_type, fa_major_mime,
       fa_minor_mime, fa_description_id, fa_actor, fa_timestamp, fa_deleted, fa_sha1
FROM /*_*/filearchive;
DROP TABLE /*_*/filearchive;
ALTER TABLE /*_*/filearchive_tmp RENAME TO /*_*/filearchive;

CREATE INDEX fa_name ON /*_*/filearchive (fa_name, fa_timestamp);

CREATE INDEX fa_storage_group ON /*_*/filearchive (
  fa_storage_group, fa_storage_key
);

CREATE INDEX fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);

CREATE INDEX fa_actor_timestamp ON /*_*/filearchive (fa_actor, fa_timestamp);

CREATE INDEX fa_sha1 ON /*_*/filearchive (fa_sha1);
