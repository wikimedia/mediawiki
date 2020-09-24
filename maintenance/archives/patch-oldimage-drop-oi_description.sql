--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/oldimage
  DROP COLUMN oi_description,
  ALTER COLUMN oi_description_id DROP DEFAULT;
