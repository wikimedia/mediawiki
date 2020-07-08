--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/recentchanges
  DROP COLUMN rc_comment,
  ALTER COLUMN rc_comment_id DROP DEFAULT;
