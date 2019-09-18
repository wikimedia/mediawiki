--
-- patch-drop-user-fields.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

ALTER TABLE /*_*/archive
  DROP COLUMN ar_user,
  DROP COLUMN ar_user_text,
  ALTER COLUMN ar_actor DROP DEFAULT;

ALTER TABLE /*_*/ipblocks
  DROP COLUMN ipb_by,
  DROP COLUMN ipb_by_text,
  ALTER COLUMN ipb_by_actor DROP DEFAULT;

ALTER TABLE /*_*/image
  DROP INDEX /*i*/img_user_timestamp,
  DROP INDEX /*i*/img_usertext_timestamp,
  DROP COLUMN img_user,
  DROP COLUMN img_user_text,
  ALTER COLUMN img_actor DROP DEFAULT;

ALTER TABLE /*_*/oldimage
  DROP INDEX /*i*/oi_usertext_timestamp,
  DROP COLUMN oi_user,
  DROP COLUMN oi_user_text,
  ALTER COLUMN oi_actor DROP DEFAULT;

ALTER TABLE /*_*/filearchive
  DROP INDEX /*i*/fa_user_timestamp,
  DROP COLUMN fa_user,
  DROP COLUMN fa_user_text,
  ALTER COLUMN fa_actor DROP DEFAULT;

ALTER TABLE /*_*/recentchanges
  DROP INDEX /*i*/rc_ns_usertext,
  DROP INDEX /*i*/rc_user_text,
  DROP COLUMN rc_user,
  DROP COLUMN rc_user_text,
  ALTER COLUMN rc_actor DROP DEFAULT;

ALTER TABLE /*_*/logging
  DROP INDEX /*i*/user_time,
  DROP INDEX /*i*/log_user_type_time,
  DROP INDEX /*i*/log_user_text_type_time,
  DROP INDEX /*i*/log_user_text_time,
  DROP COLUMN log_user,
  DROP COLUMN log_user_text,
  ALTER COLUMN log_actor DROP DEFAULT;
