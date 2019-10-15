BEGIN;

DROP TABLE IF EXISTS /*_*/image_tmp;
CREATE TABLE /*_*/image_tmp (
  img_name varchar(255) binary NOT NULL default '' PRIMARY KEY,
  img_size int unsigned NOT NULL default 0,
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,
  img_metadata mediumblob NOT NULL,
  img_bits int NOT NULL default 0,
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  img_minor_mime varbinary(100) NOT NULL default "unknown",
  img_description varbinary(767) NOT NULL default '',
  img_user int unsigned NOT NULL default 0,
  img_user_text varchar(255) binary NOT NULL,
  img_timestamp varbinary(14) NOT NULL default '',
  img_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/image_tmp (
	img_name, img_size, img_width, img_height, img_metadata, img_bits,
	img_media_type, img_major_mime, img_minor_mime, img_description, img_user,
	img_user_text, img_timestamp, img_sha1)
  SELECT
	img_name, img_size, img_width, img_height, img_metadata, img_bits,
	img_media_type, img_major_mime, img_minor_mime, img_description, img_user,
	img_user_text, img_timestamp, img_sha1
  FROM /*_*/image;

DROP TABLE /*_*/image;
ALTER TABLE /*_*/image_tmp RENAME TO /*_*/image;
CREATE INDEX /*i*/img_user_timestamp ON /*_*/image (img_user,img_timestamp);
CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1(10));
CREATE INDEX /*i*/img_media_mime ON /*_*/image (img_media_type,img_major_mime,img_minor_mime);

COMMIT;
