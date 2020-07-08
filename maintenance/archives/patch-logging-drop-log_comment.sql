--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/logging
  DROP COLUMN log_comment,
  ALTER COLUMN log_comment_id DROP DEFAULT;
