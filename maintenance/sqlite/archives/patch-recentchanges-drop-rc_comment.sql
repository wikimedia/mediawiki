--
-- patch-recentchanges-drop-rc_comment.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

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
  rc_comment_id bigint unsigned NOT NULL,
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
	rc_id, rc_timestamp, rc_user, rc_user_text, rc_actor, rc_namespace, rc_title,
	rc_comment_id, rc_minor, rc_bot, rc_new, rc_cur_id, rc_this_oldid, rc_last_oldid,
	rc_type, rc_source, rc_patrolled, rc_ip, rc_old_len, rc_new_len, rc_deleted,
	rc_logid, rc_log_type, rc_log_action, rc_params
  ) SELECT
	rc_id, rc_timestamp, rc_user, rc_user_text, rc_actor, rc_namespace, rc_title,
	rc_comment_id, rc_minor, rc_bot, rc_new, rc_cur_id, rc_this_oldid, rc_last_oldid,
	rc_type, rc_source, rc_patrolled, rc_ip, rc_old_len, rc_new_len, rc_deleted,
	rc_logid, rc_log_type, rc_log_action, rc_params
  FROM /*_*/recentchanges;

DROP TABLE /*_*/recentchanges;
ALTER TABLE /*_*/recentchanges_tmp RENAME TO /*_*/recentchanges;
CREATE INDEX /*i*/rc_timestamp ON /*_*/recentchanges (rc_timestamp);
CREATE INDEX /*i*/rc_namespace_title_timestamp ON /*_*/recentchanges (rc_namespace, rc_title, rc_timestamp);
CREATE INDEX /*i*/rc_cur_id ON /*_*/recentchanges (rc_cur_id);
CREATE INDEX /*i*/new_name_timestamp ON /*_*/recentchanges (rc_new,rc_namespace,rc_timestamp);
CREATE INDEX /*i*/rc_ip ON /*_*/recentchanges (rc_ip);
CREATE INDEX /*i*/rc_ns_usertext ON /*_*/recentchanges (rc_namespace, rc_user_text);
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_user_text ON /*_*/recentchanges (rc_user_text, rc_timestamp);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
CREATE INDEX /*i*/rc_this_oldid ON /*_*/recentchanges (rc_this_oldid);

COMMIT;
