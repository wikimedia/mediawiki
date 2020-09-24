--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/image
  DROP COLUMN img_description,
  ALTER COLUMN img_description_id DROP DEFAULT;

