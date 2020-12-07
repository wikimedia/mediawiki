--
-- patch-drop-archive-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/archive
  DROP COLUMN ar_user,
  DROP COLUMN ar_user_text,
  ALTER COLUMN ar_actor DROP DEFAULT;
