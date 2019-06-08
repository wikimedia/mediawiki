--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table and various columns (and temporary tables) to reference it.
-- Sigh, sqlite, such trouble just to change the default value of a column.

CREATE TABLE /*_*/actor (
  actor_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  actor_user int unsigned,
  actor_name varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX /*i*/actor_name ON /*_*/actor (actor_name);

CREATE TABLE /*_*/revision_actor_temp (
  revactor_rev int unsigned NOT NULL,
  revactor_actor bigint unsigned NOT NULL,
  revactor_timestamp binary(14) NOT NULL default '',
  revactor_page int unsigned NOT NULL,
  PRIMARY KEY (revactor_rev, revactor_actor)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);

BEGIN;

DROP TABLE IF EXISTS /*_*/archive_tmp;
CREATE TABLE /*_*/archive_tmp (
  ar_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ar_namespace int NOT NULL default 0,
  ar_title varchar(255) binary NOT NULL default '',
  ar_comment varbinary(767) NOT NULL default '',
  ar_comment_id bigint unsigned NOT NULL DEFAULT 0,
  ar_user int unsigned NOT NULL default 0,
  ar_user_text varchar(255) binary NOT NULL DEFAULT '',
  ar_actor bigint unsigned NOT NULL DEFAULT 0,
  ar_timestamp binary(14) NOT NULL default '',
  ar_minor_edit tinyint NOT NULL default 0,
  ar_rev_id int unsigned,
  ar_text_id int unsigned NOT NULL default 0,
  ar_deleted tinyint unsigned NOT NULL default 0,
  ar_len int unsigned,
  ar_page_id int unsigned,
  ar_parent_id int unsigned default NULL,
  ar_sha1 varbinary(32) NOT NULL default '',
  ar_content_model varbinary(32) DEFAULT NULL,
  ar_content_format varbinary(64) DEFAULT NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/archive_tmp (
	ar_id, ar_namespace, ar_title, ar_comment, ar_user, ar_user_text,
	ar_timestamp, ar_minor_edit, ar_rev_id, ar_text_id, ar_deleted, ar_len,
	ar_page_id, ar_parent_id, ar_sha1, ar_content_model, ar_content_format)
  SELECT
	ar_id, ar_namespace, ar_title, ar_comment, ar_user, ar_user_text,
	ar_timestamp, ar_minor_edit, ar_rev_id, ar_text_id, ar_deleted, ar_len,
	ar_page_id, ar_parent_id, ar_sha1, ar_content_model, ar_content_format
  FROM /*_*/archive;

DROP TABLE /*_*/archive;
ALTER TABLE /*_*/archive_tmp RENAME TO /*_*/archive;
CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_usertext_timestamp ON /*_*/archive (ar_user_text,ar_timestamp);
CREATE INDEX /*i*/ar_revid ON /*_*/archive (ar_rev_id);
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);

COMMIT;

BEGIN;

DROP TABLE IF EXISTS ipblocks_tmp;
CREATE TABLE /*_*/ipblocks_tmp (
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ipb_address tinyblob NOT NULL,
  ipb_user int unsigned NOT NULL default 0,
  ipb_by int unsigned NOT NULL default 0,
  ipb_by_text varchar(255) binary NOT NULL default '',
  ipb_by_actor bigint unsigned NOT NULL DEFAULT 0,
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
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  img_minor_mime varbinary(100) NOT NULL default "unknown",
  img_description varbinary(767) NOT NULL default '',
  img_description_id bigint unsigned NOT NULL DEFAULT 0,
  img_user int unsigned NOT NULL default 0,
  img_user_text varchar(255) binary NOT NULL DEFAULT '',
  img_actor bigint unsigned NOT NULL DEFAULT 0,
  img_timestamp varbinary(14) NOT NULL default '',
  img_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/image_tmp (
	img_name, img_size, img_width, img_height, img_metadata, img_bits,
	img_media_type, img_major_mime, img_minor_mime, img_description,
	img_description_id, img_user, img_user_text, img_timestamp, img_sha1)
  SELECT
	img_name, img_size, img_width, img_height, img_metadata, img_bits,
	img_media_type, img_major_mime, img_minor_mime, img_description,
	img_description_id, img_user, img_user_text, img_timestamp, img_sha1
  FROM /*_*/image;

DROP TABLE /*_*/image;
ALTER TABLE /*_*/image_tmp RENAME TO /*_*/image;
CREATE INDEX /*i*/img_user_timestamp ON /*_*/image (img_user,img_timestamp);
CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);
CREATE INDEX /*i*/img_actor_timestamp ON /*_*/image (img_actor,img_timestamp);
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
  oi_user_text varchar(255) binary NOT NULL DEFAULT '',
  oi_actor bigint unsigned NOT NULL DEFAULT 0,
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
CREATE INDEX /*i*/oi_actor_timestamp ON /*_*/oldimage (oi_actor,oi_timestamp);

COMMIT;

-- filearchive is done in patch-filearchive-fa_actor.sql

BEGIN;

DROP TABLE IF EXISTS /*_*/logging_tmp;
CREATE TABLE /*_*/logging_tmp (
  log_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  log_type varbinary(32) NOT NULL default '',
  log_action varbinary(32) NOT NULL default '',
  log_timestamp binary(14) NOT NULL default '19700101000000',
  log_user int unsigned NOT NULL default 0,
  log_user_text varchar(255) binary NOT NULL default '',
  log_actor bigint unsigned NOT NULL DEFAULT 0,
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  log_page int unsigned NULL,
  log_comment varbinary(767) NOT NULL default '',
  log_comment_id bigint unsigned NOT NULL DEFAULT 0,
  log_params blob NOT NULL,
  log_deleted tinyint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/logging_tmp (
	log_id, log_type, log_action, log_timestamp, log_user, log_user_text,
	log_namespace, log_title, log_page, log_comment, log_comment_id,
	log_params, log_deleted)
  SELECT
	log_id, log_type, log_action, log_timestamp, log_user, log_user_text,
	log_namespace, log_title, log_page, log_comment, log_comment_id,
	log_params, log_deleted
  FROM /*_*/logging;

DROP TABLE /*_*/logging;
ALTER TABLE /*_*/logging_tmp RENAME TO /*_*/logging;
CREATE INDEX /*i*/type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX /*i*/user_time ON /*_*/logging (log_user, log_timestamp);
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging (log_timestamp);
CREATE INDEX /*i*/log_user_type_time ON /*_*/logging (log_user, log_type, log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
CREATE INDEX /*i*/log_type_action ON /*_*/logging (log_type, log_action, log_timestamp);
CREATE INDEX /*i*/log_user_text_type_time ON /*_*/logging (log_user_text, log_type, log_timestamp);
CREATE INDEX /*i*/log_user_text_time ON /*_*/logging (log_user_text, log_timestamp);

COMMIT;

BEGIN;

DROP TABLE IF EXISTS /*_*/recentchanges_tmp;
CREATE TABLE /*_*/recentchanges_tmp (
  rc_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rc_timestamp varbinary(14) NOT NULL default '',
  rc_user int unsigned NOT NULL default 0,
  rc_user_text varchar(255) binary NOT NULL DEFAULT '',
  rc_actor bigint unsigned NOT NULL DEFAULT 0,
  rc_namespace int NOT NULL default 0,
  rc_title varchar(255) binary NOT NULL default '',
  rc_comment varbinary(767) NOT NULL default '',
  rc_comment_id bigint unsigned NOT NULL DEFAULT 0,
  rc_minor tinyint unsigned NOT NULL default 0,
  rc_bot tinyint unsigned NOT NULL default 0,
  rc_new tinyint unsigned NOT NULL default 0,
  rc_cur_id int unsigned NOT NULL default 0,
  rc_this_oldid int unsigned NOT NULL default 0,
  rc_last_oldid int unsigned NOT NULL default 0,
  rc_type tinyint unsigned NOT NULL default 0,
  rc_source varchar(16) binary not null default '',
  rc_patrolled tinyint unsigned NOT NULL default 0,
  rc_ip varbinary(40) NOT NULL default '',
  rc_old_len int,
  rc_new_len int,
  rc_deleted tinyint unsigned NOT NULL default 0,
  rc_logid int unsigned NOT NULL default 0,
  rc_log_type varbinary(255) NULL default NULL,
  rc_log_action varbinary(255) NULL default NULL,
  rc_params blob NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/recentchanges_tmp (
	rc_id, rc_timestamp, rc_user, rc_user_text, rc_namespace, rc_title,
	rc_comment, rc_comment_id, rc_minor, rc_bot, rc_new, rc_cur_id,
	rc_this_oldid, rc_last_oldid, rc_type, rc_source, rc_patrolled, rc_ip,
	rc_old_len, rc_new_len, rc_deleted, rc_logid, rc_log_type, rc_log_action,
	rc_params)
  SELECT
	rc_id, rc_timestamp, rc_user, rc_user_text, rc_namespace, rc_title,
	rc_comment, rc_comment_id, rc_minor, rc_bot, rc_new, rc_cur_id,
	rc_this_oldid, rc_last_oldid, rc_type, rc_source, rc_patrolled, rc_ip,
	rc_old_len, rc_new_len, rc_deleted, rc_logid, rc_log_type, rc_log_action,
	rc_params
  FROM /*_*/recentchanges;

DROP TABLE /*_*/recentchanges;
ALTER TABLE /*_*/recentchanges_tmp RENAME TO /*_*/recentchanges;
CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title ON /*_*/recentchanges (rc_namespace, rc_title);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX /*i*/rc_ns_usertext ON /*_*/recentchanges (rc_namespace, rc_user_text);
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_user_text ON /*_*/recentchanges (rc_user_text, rc_timestamp);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);

COMMIT;
