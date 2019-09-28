ALTER TABLE /*_*/recentchanges
  ALTER COLUMN rc_user_text SET DEFAULT '',
  ADD COLUMN rc_actor bigint unsigned NOT NULL DEFAULT 0 AFTER rc_user_text;
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);
