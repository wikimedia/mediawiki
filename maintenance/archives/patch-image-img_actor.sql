ALTER TABLE /*_*/image
  ALTER COLUMN img_user_text SET DEFAULT '',
  ADD COLUMN img_actor bigint unsigned NOT NULL DEFAULT 0 AFTER img_user_text;
CREATE INDEX /*i*/img_actor_timestamp ON /*_*/image (img_actor, img_timestamp);
