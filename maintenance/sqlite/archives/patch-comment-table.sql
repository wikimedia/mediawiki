--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table and various columns (and temporary tables) to reference it.
-- Sigh, sqlite, such trouble just to change the default value of a column.

CREATE TABLE /*_*/comment (
  comment_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  comment_hash INT NOT NULL,
  comment_text BLOB NOT NULL,
  comment_data BLOB
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/comment_hash ON /*_*/comment (comment_hash);

CREATE TABLE /*_*/revision_comment_temp (
  revcomment_rev int unsigned NOT NULL,
  revcomment_comment_id bigint unsigned NOT NULL,
  PRIMARY KEY (revcomment_rev, revcomment_comment_id)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revcomment_rev ON /*_*/revision_comment_temp (revcomment_rev);

CREATE TABLE /*_*/image_comment_temp (
  imgcomment_name varchar(255) binary NOT NULL,
  imgcomment_description_id bigint unsigned NOT NULL,
  PRIMARY KEY (imgcomment_name, imgcomment_description_id)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/imgcomment_name ON /*_*/image_comment_temp (imgcomment_name);

ALTER TABLE /*_*/recentchanges
  ADD COLUMN rc_comment_id bigint unsigned NOT NULL DEFAULT 0;

ALTER TABLE /*_*/logging
  ADD COLUMN log_comment_id bigint unsigned NOT NULL DEFAULT 0;

BEGIN;

DROP TABLE IF EXISTS /*_*/revision_tmp;
CREATE TABLE /*_*/revision_tmp (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rev_page int unsigned NOT NULL,
  rev_text_id int unsigned NOT NULL,
  rev_comment varbinary(767) NOT NULL default '',
  rev_user int unsigned NOT NULL default 0,
  rev_user_text varchar(255) binary NOT NULL default '',
  rev_timestamp binary(14) NOT NULL default '',
  rev_minor_edit tinyint unsigned NOT NULL default 0,
  rev_deleted tinyint unsigned NOT NULL default 0,
  rev_len int unsigned,
  rev_parent_id int unsigned default NULL,
  rev_sha1 varbinary(32) NOT NULL default '',
  rev_content_model varbinary(32) DEFAULT NULL,
  rev_content_format varbinary(64) DEFAULT NULL
) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;

INSERT OR IGNORE INTO /*_*/revision_tmp (
	rev_id, rev_page, rev_text_id, rev_comment, rev_user, rev_user_text,
	rev_timestamp, rev_minor_edit, rev_deleted, rev_len, rev_parent_id,
	rev_sha1, rev_content_model, rev_content_format)
 SELECT
	rev_id, rev_page, rev_text_id, rev_comment, rev_user, rev_user_text,
	rev_timestamp, rev_minor_edit, rev_deleted, rev_len, rev_parent_id,
	rev_sha1, rev_content_model, rev_content_format
  FROM /*_*/revision;

DROP TABLE /*_*/revision;
ALTER TABLE /*_*/revision_tmp RENAME TO /*_*/revision;
CREATE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision (rev_page,rev_timestamp);
CREATE INDEX /*i*/user_timestamp ON /*_*/revision (rev_user,rev_timestamp);
CREATE INDEX /*i*/usertext_timestamp ON /*_*/revision (rev_user_text,rev_timestamp);
CREATE INDEX /*i*/page_user_timestamp ON /*_*/revision (rev_page,rev_user,rev_timestamp);

COMMIT;

BEGIN;

DROP TABLE IF EXISTS /*_*/archive_tmp;
CREATE TABLE /*_*/archive_tmp (
  ar_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ar_namespace int NOT NULL default 0,
  ar_title varchar(255) binary NOT NULL default '',
  ar_text mediumblob NOT NULL,
  ar_comment varbinary(767) NOT NULL default '',
  ar_comment_id bigint unsigned NOT NULL DEFAULT 0,
  ar_user int unsigned NOT NULL default 0,
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp binary(14) NOT NULL default '',
  ar_minor_edit tinyint NOT NULL default 0,
  ar_flags tinyblob NOT NULL,
  ar_rev_id int unsigned,
  ar_text_id int unsigned,
  ar_deleted tinyint unsigned NOT NULL default 0,
  ar_len int unsigned,
  ar_page_id int unsigned,
  ar_parent_id int unsigned default NULL,
  ar_sha1 varbinary(32) NOT NULL default '',
  ar_content_model varbinary(32) DEFAULT NULL,
  ar_content_format varbinary(64) DEFAULT NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/archive_tmp (
	ar_id, ar_namespace, ar_title, ar_text, ar_comment, ar_user, ar_user_text,
	ar_timestamp, ar_minor_edit, ar_flags, ar_rev_id, ar_text_id, ar_deleted,
	ar_len, ar_page_id, ar_parent_id, ar_sha1, ar_content_model,
	ar_content_format)
  SELECT
	ar_id, ar_namespace, ar_title, ar_text, ar_comment, ar_user, ar_user_text,
	ar_timestamp, ar_minor_edit, ar_flags, ar_rev_id, ar_text_id, ar_deleted,
	ar_len, ar_page_id, ar_parent_id, ar_sha1, ar_content_model,
	ar_content_format
  FROM /*_*/archive;

DROP TABLE /*_*/archive;
ALTER TABLE /*_*/archive_tmp RENAME TO /*_*/archive;
CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_usertext_timestamp ON /*_*/archive (ar_user_text,ar_timestamp);
CREATE INDEX /*i*/ar_revid ON /*_*/archive (ar_rev_id);

COMMIT;

BEGIN;

DROP TABLE IF EXISTS ipblocks_tmp;
CREATE TABLE /*_*/ipblocks_tmp (
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ipb_address tinyblob NOT NULL,
  ipb_user int unsigned NOT NULL default 0,
  ipb_by int unsigned NOT NULL default 0,
  ipb_by_text varchar(255) binary NOT NULL default '',
  ipb_reason varbinary(767) NOT NULL default '',
  ipb_reason_id bigint unsigned NOT NULL DEFAULT 0,
  ipb_timestamp binary(14) NOT NULL default '',
  ipb_auto bool NOT NULL default 0,
  ipb_anon_only bool NOT NULL default 0,
  ipb_create_account bool NOT NULL default 1,
  ipb_enable_autoblock bool NOT NULL default '1',
  ipb_expiry varbinary(14) NOT NULL default '',
  ipb_range_start tinyblob NOT NULL,
  ipb_range_end tinyblob NOT NULL,
  ipb_deleted bool NOT NULL default 0,
  ipb_block_email bool NOT NULL default 0,
  ipb_allow_usertalk bool NOT NULL default 0,
  ipb_parent_block_id int default NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/ipblocks_tmp (
	ipb_id, ipb_address, ipb_user, ipb_by, ipb_by_text, ipb_reason,
	ipb_timestamp, ipb_auto, ipb_anon_only, ipb_create_account,
	ipb_enable_autoblock, ipb_expiry, ipb_range_start, ipb_range_end,
	ipb_deleted, ipb_block_email, ipb_allow_usertalk, ipb_parent_block_id)
  SELECT
	ipb_id, ipb_address, ipb_user, ipb_by, ipb_by_text, ipb_reason,
	ipb_timestamp, ipb_auto, ipb_anon_only, ipb_create_account,
	ipb_enable_autoblock, ipb_expiry, ipb_range_start, ipb_range_end,
	ipb_deleted, ipb_block_email, ipb_allow_usertalk, ipb_parent_block_id
  FROM /*_*/ipblocks;

DROP TABLE /*_*/ipblocks;
ALTER TABLE /*_*/ipblocks_tmp RENAME TO /*_*/ipblocks;
CREATE UNIQUE INDEX /*i*/ipb_address ON /*_*/ipblocks (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only);
CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks (ipb_range_start(8), ipb_range_end(8));
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks (ipb_expiry);
CREATE INDEX /*i*/ipb_parent_block_id ON /*_*/ipblocks (ipb_parent_block_id);

COMMIT;

BEGIN;

DROP TABLE IF EXISTS /*_*/image_tmp;
CREATE TABLE /*_*/image_tmp (
  img_name varchar(255) binary NOT NULL default '' PRIMARY KEY,
  img_size int unsigned NOT NULL default 0,
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,
  img_metadata mediumblob NOT NULL,
  img_bits int NOT NULL default 0,
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
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
  oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
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
  fa_deleted_reason varbinary(767) default '',
  fa_deleted_reason_id bigint unsigned NOT NULL DEFAULT 0,
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description varbinary(767) default '',
  fa_description_id bigint unsigned NOT NULL DEFAULT 0,
  fa_user int unsigned default 0,
  fa_user_text varchar(255) binary,
  fa_timestamp binary(14) default '',
  fa_deleted tinyint unsigned NOT NULL default 0,
  fa_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/filearchive_tmp (
	fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key,
	fa_deleted_user, fa_deleted_timestamp, fa_deleted_reason, fa_size,
	fa_width, fa_height, fa_metadata, fa_bits, fa_media_type, fa_major_mime,
	fa_minor_mime, fa_description, fa_user, fa_user_text, fa_timestamp,
	fa_deleted, fa_sha1)
  SELECT
	fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key,
	fa_deleted_user, fa_deleted_timestamp, fa_deleted_reason, fa_size,
	fa_width, fa_height, fa_metadata, fa_bits, fa_media_type, fa_major_mime,
	fa_minor_mime, fa_description, fa_user, fa_user_text, fa_timestamp,
	fa_deleted, fa_sha1
  FROM /*_*/filearchive;

DROP TABLE /*_*/filearchive;
ALTER TABLE /*_*/filearchive_tmp RENAME TO /*_*/filearchive;
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1(10));

COMMIT;

BEGIN;

DROP TABLE IF EXISTS /*_*/protected_titles_tmp;
CREATE TABLE /*_*/protected_titles_tmp (
  pt_namespace int NOT NULL,
  pt_title varchar(255) binary NOT NULL,
  pt_user int unsigned NOT NULL,
  pt_reason varbinary(767) default '',
  pt_reason_id bigint unsigned NOT NULL DEFAULT 0,
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/protected_titles_tmp (
	pt_namespace, pt_title, pt_user, pt_reason, pt_timestamp, pt_expiry, pt_create_perm)
  SELECT
	pt_namespace, pt_title, pt_user, pt_reason, pt_timestamp, pt_expiry, pt_create_perm
  FROM /*_*/protected_titles;

DROP TABLE /*_*/protected_titles;
ALTER TABLE /*_*/protected_titles_tmp RENAME TO /*_*/protected_titles;
CREATE UNIQUE INDEX /*i*/pt_namespace_title ON /*_*/protected_titles (pt_namespace,pt_title);
CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles (pt_timestamp);

COMMIT;
