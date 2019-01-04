--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE /*_*/archive
  DROP COLUMN ar_comment,
  ALTER COLUMN ar_comment_id DROP DEFAULT;

ALTER TABLE /*_*/ipblocks
  DROP COLUMN ipb_reason,
  ALTER COLUMN ipb_reason_id DROP DEFAULT;

ALTER TABLE /*_*/image
  DROP COLUMN img_description,
  ALTER COLUMN img_description_id DROP DEFAULT;

ALTER TABLE /*_*/oldimage
  DROP COLUMN oi_description,
  ALTER COLUMN oi_description_id DROP DEFAULT;

ALTER TABLE /*_*/filearchive
  DROP COLUMN fa_deleted_reason,
  ALTER COLUMN fa_deleted_reason_id DROP DEFAULT,
  DROP COLUMN fa_description,
  ALTER COLUMN fa_description_id DROP DEFAULT;

ALTER TABLE /*_*/recentchanges
  DROP COLUMN rc_comment,
  ALTER COLUMN rc_comment_id DROP DEFAULT;

ALTER TABLE /*_*/logging
  DROP COLUMN log_comment,
  ALTER COLUMN log_comment_id DROP DEFAULT;

ALTER TABLE /*_*/protected_titles
  DROP COLUMN pt_reason,
  ALTER COLUMN pt_reason_id DROP DEFAULT;
