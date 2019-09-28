ALTER TABLE /*_*/oldimage
  ALTER COLUMN oi_user_text SET DEFAULT '',
  ADD COLUMN oi_actor bigint unsigned NOT NULL DEFAULT 0 AFTER oi_user_text;
CREATE INDEX /*i*/oi_actor_timestamp ON /*_*/oldimage (oi_actor,oi_timestamp);
