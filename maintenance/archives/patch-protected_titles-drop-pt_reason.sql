--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/protected_titles
  DROP COLUMN pt_reason,
  ALTER COLUMN pt_reason_id DROP DEFAULT;
