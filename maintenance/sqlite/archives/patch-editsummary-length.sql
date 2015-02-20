CREATE TABLE /*_*/filearchive_tmp (
  -- Unique row id
  fa_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Original base filename; key to image.img_name, page.page_title, etc
  fa_name varchar(255) binary NOT NULL default '',

  -- Filename of archived file, if an old revision
  fa_archive_name varchar(255) binary default '',

  -- Which storage bin (directory tree or object store) the file data
  -- is stored in. Should be 'deleted' for files that have been deleted;
  -- any other bin is not yet in use.
  fa_storage_group varbinary(16),

  -- SHA-1 of the file contents plus extension, used as a key for storage.
  -- eg 8f8a562add37052a1848ff7771a2c515db94baa9.jpg
  --
  -- If NULL, the file was missing at deletion time or has been purged
  -- from the archival storage.
  fa_storage_key varbinary(64) default '',

  -- Deletion information, if this file is deleted.
  fa_deleted_user int,
  fa_deleted_timestamp binary(14) default '',
  fa_deleted_reason varbinary(767) default '',
  -- Duped fields from image
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description varbinary(767),
  fa_user int unsigned default 0,
  fa_user_text varchar(255) binary,
  fa_timestamp binary(14) default '',

  -- Visibility of deleted revisions, bitfield
  fa_deleted tinyint unsigned NOT NULL default 0,

  -- sha1 hash of file content
  fa_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;


INSERT INTO /*_*/filearchive_tmp
	SELECT fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key, fa_deleted_user, fa_deleted_timestamp,
	fa_deleted_reason, fa_size, fa_width, fa_height, fa_metadata, fa_bits, fa_media_type, fa_major_mime,
	fa_minor_mime, fa_description, fa_user, fa_user_text, fa_timestamp, fa_deleted, fa_sha1
		FROM /*_*/filearchive;

DROP TABLE /*_*/filearchive;

ALTER TABLE /*_*/filearchive_tmp RENAME TO /*_*/filearchive;


CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1(10));

