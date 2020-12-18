--
-- patch-drop-logging-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/logging
  DROP INDEX /*i*/user_time,
  DROP INDEX /*i*/log_user_type_time,
  DROP INDEX /*i*/log_user_text_type_time,
  DROP INDEX /*i*/log_user_text_time,
  DROP COLUMN log_user,
  DROP COLUMN log_user_text,
  ALTER COLUMN log_actor DROP DEFAULT;
