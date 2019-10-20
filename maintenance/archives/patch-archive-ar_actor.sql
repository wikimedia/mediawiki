ALTER TABLE /*_*/archive
  ALTER COLUMN ar_user_text SET DEFAULT '',
  ADD COLUMN ar_actor bigint unsigned NOT NULL DEFAULT 0 AFTER ar_user_text;
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);
