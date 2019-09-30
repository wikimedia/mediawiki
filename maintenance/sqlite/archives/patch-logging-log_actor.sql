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
