--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/archive
  DROP COLUMN ar_comment,
  ALTER COLUMN ar_comment_id DROP DEFAULT;
