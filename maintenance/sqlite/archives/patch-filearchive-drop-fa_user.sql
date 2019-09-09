--
-- patch-filearchive-drop-fa_user.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

BEGIN;

DROP TABLE IF EXISTS /*_*/filearchive_tmp;
CREATE TABLE /*_*/filearchive_tmp (
  fa_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  fa_name varchar(255) binary NOT NULL default '',
  fa_archive_name varchar(255) binary default '',
  fa_storage_group varbinary(16),
  fa_storage_key varbinary(64) default '',
  fa_deleted_user int,
  fa_deleted_timestamp binary(14) default '',
  fa_deleted_reason_id bigint unsigned NOT NULL,
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description_id bigint unsigned NOT NULL,
  fa_actor bigint unsigned NOT NULL DEFAULT 0,
  fa_timestamp binary(14) default '',
  fa_deleted tinyint unsigned NOT NULL default 0,
  fa_sha1 varbinary(32) NOT NULL default ''
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
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1(10));

COMMIT;
