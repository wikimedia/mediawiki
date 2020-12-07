--
-- patch-drop-filearchive-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/filearchive
  DROP INDEX /*i*/fa_user_timestamp,
  DROP COLUMN fa_user,
  DROP COLUMN fa_user_text,
  ALTER COLUMN fa_actor DROP DEFAULT;
