--
-- patch-drop-recentchanges-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/recentchanges
  DROP INDEX /*i*/rc_ns_usertext,
  DROP INDEX /*i*/rc_user_text,
  DROP COLUMN rc_user,
  DROP COLUMN rc_user_text,
  ALTER COLUMN rc_actor DROP DEFAULT;
