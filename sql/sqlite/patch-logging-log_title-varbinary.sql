CREATE TABLE /*_*/logging_temp (
  log_id INTEGER NOT NULL,
  log_type BLOB DEFAULT '' NOT NULL,
  log_action BLOB DEFAULT '' NOT NULL,
  log_timestamp BLOB DEFAULT '19700101000000' NOT NULL,
  log_actor BIGINT UNSIGNED NOT NULL,
  log_namespace INTEGER DEFAULT 0 NOT NULL,
  log_title BLOB DEFAULT '' NOT NULL,
  log_page INTEGER UNSIGNED DEFAULT NULL,
  log_comment_id BIGINT UNSIGNED NOT NULL,
  log_params BLOB NOT NULL,
  log_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  PRIMARY KEY(log_id)
);


INSERT INTO /*_*/logging_temp
	SELECT log_id, log_type, log_action, log_timestamp, log_actor, log_namespace, log_title, log_page, log_comment_id, log_params, log_deleted
		FROM /*_*/logging;
DROP TABLE /*_*/logging;
ALTER TABLE /*_*/logging_temp RENAME TO /*_*/logging;


CREATE INDEX type_time ON /*_*/logging (log_type, log_timestamp);

CREATE INDEX actor_time ON /*_*/logging (log_actor, log_timestamp);

CREATE INDEX page_time ON /*_*/logging (
  log_namespace, log_title, log_timestamp
);

CREATE INDEX times ON /*_*/logging (log_timestamp);

CREATE INDEX log_actor_type_time ON /*_*/logging (
  log_actor, log_type, log_timestamp
);

CREATE INDEX log_page_id_time ON /*_*/logging (log_page, log_timestamp);

CREATE INDEX log_type_action ON /*_*/logging (
  log_type, log_action, log_timestamp
);
