--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

ALTER TABLE &mw_prefix.archive DROP COLUMN ar_comment;
ALTER TABLE &mw_prefix.archive MODIFY ar_comment_id DEFAULT NULL;

ALTER TABLE &mw_prefix.ipblocks DROP COLUMN ipb_reason;
ALTER TABLE &mw_prefix.ipblocks MODIFY ipb_reason_id DEFAULT NULL;

ALTER TABLE &mw_prefix.image DROP COLUMN img_description;
ALTER TABLE &mw_prefix.image MODIFY img_description_id DEFAULT NULL;

ALTER TABLE &mw_prefix.oldimage DROP COLUMN oi_description;
ALTER TABLE &mw_prefix.oldimage MODIFY oi_description_id DEFAULT NULL;

ALTER TABLE &mw_prefix.filearchive DROP COLUMN fa_deleted_reason;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_deleted_reason_id DEFAULT NULL,
ALTER TABLE &mw_prefix.filearchive DROP COLUMN fa_description;
ALTER TABLE &mw_prefix.filearchive MODIFY fa_description_id DEFAULT NULL;

ALTER TABLE &mw_prefix.recentchanges DROP COLUMN rc_comment;
ALTER TABLE &mw_prefix.recentchanges MODIFY rc_comment_id DEFAULT NULL;

ALTER TABLE &mw_prefix.logging DROP COLUMN log_comment;
ALTER TABLE &mw_prefix.logging MODIFY log_comment_id DEFAULT NULL;

ALTER TABLE &mw_prefix.protected_titles DROP COLUMN pt_reason;
ALTER TABLE &mw_prefix.protected_titles MODIFY pt_reason_id DEFAULT NULL;
