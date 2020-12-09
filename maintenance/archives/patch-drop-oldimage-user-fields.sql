--
-- patch-drop-oldimage-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/oldimage
  DROP INDEX /*i*/oi_usertext_timestamp,
  DROP COLUMN oi_user,
  DROP COLUMN oi_user_text,
  ALTER COLUMN oi_actor DROP DEFAULT;
