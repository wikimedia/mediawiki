ALTER TABLE /*_*/logging
  ADD COLUMN log_actor bigint unsigned NOT NULL DEFAULT 0 AFTER log_user_text;
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
