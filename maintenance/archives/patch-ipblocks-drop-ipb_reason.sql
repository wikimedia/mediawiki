--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/ipblocks
  DROP COLUMN ipb_reason,
  ALTER COLUMN ipb_reason_id DROP DEFAULT;
