--
-- patch-drop-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/image
  DROP INDEX /*i*/img_user_timestamp,
  DROP INDEX /*i*/img_usertext_timestamp,
  DROP COLUMN img_user,
  DROP COLUMN img_user_text,
  ALTER COLUMN img_actor DROP DEFAULT;
