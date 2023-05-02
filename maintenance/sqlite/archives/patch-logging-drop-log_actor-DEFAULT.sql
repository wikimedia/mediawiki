--
-- patch-logging-drop-log_actor-DEFAULT.sql
--
-- T246077. Drop DEFAULT from log_actor (forgotten in patch-filearchive-drop-log_user.sql).

BEGIN;

DROP TABLE IF EXISTS /*_*/logging_tmp;
CREATE TABLE /*_*/logging_tmp (
  log_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  log_type BLOB NOT NULL default '',
  log_action BLOB NOT NULL default '',
  log_timestamp BLOB NOT NULL default '19700101000000',
  log_actor INTEGER  NOT NULL,
  log_namespace INTEGER NOT NULL default 0,
  log_title TEXT  NOT NULL default '',
  log_page INTEGER  NULL,
  log_comment_id INTEGER  NOT NULL,
  log_params BLOB NOT NULL,
  log_deleted INTEGER  NOT NULL default 0
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/logging_tmp (
	log_id, log_type, log_action, log_timestamp, log_actor,
	log_namespace, log_title, log_page, log_comment_id, log_params, log_deleted
  ) SELECT
	log_id, log_type, log_action, log_timestamp, log_actor,
	log_namespace, log_title, log_page, log_comment_id, log_params, log_deleted
  FROM /*_*/logging;

DROP TABLE /*_*/logging;
ALTER TABLE /*_*/logging_tmp RENAME TO /*_*/logging;
CREATE INDEX /*i*/type_time ON /*_*/logging (log_type, log_timestamp);
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/page_time ON /*_*/logging (log_namespace, log_title, log_timestamp);
CREATE INDEX /*i*/times ON /*_*/logging (log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
CREATE INDEX /*i*/log_type_action ON /*_*/logging (log_type, log_action, log_timestamp);

COMMIT;
