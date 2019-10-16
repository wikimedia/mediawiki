ALTER TABLE /*_*/filearchive
  ALTER COLUMN fa_user_text SET DEFAULT '',
  ADD COLUMN fa_actor bigint unsigned NOT NULL DEFAULT 0 AFTER fa_user_text;
CREATE INDEX /*i*/fa_actor_timestamp ON /*_*/filearchive (fa_actor,fa_timestamp);
