--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/filearchive
  DROP COLUMN fa_deleted_reason,
  ALTER COLUMN fa_deleted_reason_id DROP DEFAULT,
  DROP COLUMN fa_description,
  ALTER COLUMN fa_description_id DROP DEFAULT;
