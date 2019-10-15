BEGIN;

DROP TABLE IF EXISTS /*_*/oldimage_tmp;
CREATE TABLE /*_*/oldimage_tmp (
  oi_name varchar(255) binary NOT NULL default '',
  oi_archive_name varchar(255) binary NOT NULL default '',
  oi_size int unsigned NOT NULL default 0,
  oi_width int NOT NULL default 0,
  oi_height int NOT NULL default 0,
  oi_bits int NOT NULL default 0,
  oi_description varbinary(767) NOT NULL default '',
  oi_description_id bigint unsigned NOT NULL DEFAULT 0,
  oi_user int unsigned NOT NULL default 0,
  oi_user_text varchar(255) binary NOT NULL,
  oi_timestamp binary(14) NOT NULL default '',
  oi_metadata mediumblob NOT NULL,
  oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  oi_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  oi_minor_mime varbinary(100) NOT NULL default "unknown",
  oi_deleted tinyint unsigned NOT NULL default 0,
  oi_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/oldimage_tmp (
	oi_name, oi_archive_name, oi_size, oi_width, oi_height, oi_bits,
	oi_description, oi_user, oi_user_text, oi_timestamp, oi_metadata,
	oi_media_type, oi_major_mime, oi_minor_mime, oi_deleted, oi_sha1)
  SELECT
	oi_name, oi_archive_name, oi_size, oi_width, oi_height, oi_bits,
	oi_description, oi_user, oi_user_text, oi_timestamp, oi_metadata,
	oi_media_type, oi_major_mime, oi_minor_mime, oi_deleted, oi_sha1
  FROM /*_*/oldimage;

DROP TABLE /*_*/oldimage;
ALTER TABLE /*_*/oldimage_tmp RENAME TO /*_*/oldimage;
CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage (oi_user_text,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name,oi_timestamp);
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name(14));
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1(10));

COMMIT;
