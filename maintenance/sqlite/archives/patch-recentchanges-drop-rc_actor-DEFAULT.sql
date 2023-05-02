--
-- patch-recentchanges-drop-rc_actor-DEFAULT.sql
--
-- T246077. Drop DEFAULT from rc_actor (forgotten in patch-filearchive-drop-rc_user.sql).

BEGIN;

DROP TABLE IF EXISTS /*_*/recentchanges_tmp;
CREATE TABLE /*_*/recentchanges_tmp (
  rc_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  rc_timestamp BLOB NOT NULL default '',
  rc_actor INTEGER  NOT NULL,
  rc_namespace INTEGER NOT NULL default 0,
  rc_title TEXT  NOT NULL default '',
  rc_comment_id INTEGER  NOT NULL,
  rc_minor INTEGER  NOT NULL default 0,
  rc_bot INTEGER  NOT NULL default 0,
  rc_new INTEGER  NOT NULL default 0,
  rc_cur_id INTEGER  NOT NULL default 0,
  rc_this_oldid INTEGER  NOT NULL default 0,
  rc_last_oldid INTEGER  NOT NULL default 0,
  rc_type INTEGER  NOT NULL default 0,
  rc_source TEXT  not null default '',
  rc_patrolled INTEGER  NOT NULL default 0,
  rc_ip BLOB NOT NULL default '',
  rc_old_len INTEGER,
  rc_new_len INTEGER,
  rc_deleted INTEGER  NOT NULL default 0,
  rc_logid INTEGER  NOT NULL default 0,
  rc_log_type BLOB NULL default NULL,
  rc_log_action BLOB NULL default NULL,
  rc_params BLOB NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/recentchanges_tmp (
	rc_id, rc_timestamp, rc_actor, rc_namespace, rc_title,
	rc_comment_id, rc_minor, rc_bot, rc_new, rc_cur_id, rc_this_oldid, rc_last_oldid,
	rc_type, rc_source, rc_patrolled, rc_ip, rc_old_len, rc_new_len, rc_deleted,
	rc_logid, rc_log_type, rc_log_action, rc_params
  ) SELECT
	rc_id, rc_timestamp, rc_actor, rc_namespace, rc_title,
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
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);
CREATE INDEX /*i*/rc_this_oldid ON /*_*/recentchanges (rc_this_oldid);

COMMIT;
