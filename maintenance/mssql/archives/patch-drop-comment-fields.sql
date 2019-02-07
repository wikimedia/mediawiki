--
-- patch-drop-comment-fields.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

DECLARE @sql nvarchar(max),
	@id sysname;

ALTER TABLE /*_*/archive DROP CONSTRAINT DF_ar_comment, COLUMN ar_comment;
ALTER TABLE /*_*/archive DROP CONSTRAINT DF_ar_comment_id;

ALTER TABLE /*_*/ipblocks DROP CONSTRAINT DF_ipb_reason, COLUMN ipb_reason;
ALTER TABLE /*_*/ipblocks DROP CONSTRAINT DF_ipb_reason_id;

ALTER TABLE /*_*/image DROP CONSTRAINT DF_img_description, COLUMN img_description;
ALTER TABLE /*_*/image DROP CONSTRAINT DF_img_description_id;

ALTER TABLE /*_*/oldimage DROP CONSTRAINT DF_oi_description, COLUMN oi_description;
ALTER TABLE /*_*/oldimage DROP CONSTRAINT DF_oi_description_id;

ALTER TABLE /*_*/filearchive DROP CONSTRAINT DF_fa_deleted_reason, COLUMN fa_deleted_reason;
ALTER TABLE /*_*/filearchive DROP CONSTRAINT DF_fa_deleted_reason_id;
ALTER TABLE /*_*/filearchive DROP CONSTRAINT DF_fa_description, COLUMN fa_description;
ALTER TABLE /*_*/filearchive DROP CONSTRAINT DF_fa_description_id;

SET @sql = 'ALTER TABLE /*_*/recentchanges DROP CONSTRAINT ';
SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/recentchanges')
	AND c.name = 'rc_comment';
SET @sql = @sql + @id;
EXEC sp_executesql @sql;
ALTER TABLE /*_*/recentchanges DROP COLUMN rc_comment;
ALTER TABLE /*_*/recentchanges DROP CONSTRAINT DF_rc_comment_id;

SET @sql = 'ALTER TABLE /*_*/logging DROP CONSTRAINT ';
SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/logging')
	AND c.name = 'log_comment';
SET @sql = @sql + @id;
EXEC sp_executesql @sql;
ALTER TABLE /*_*/logging DROP COLUMN log_comment;
ALTER TABLE /*_*/logging DROP CONSTRAINT DF_log_comment_id;

ALTER TABLE /*_*/protected_titles DROP CONSTRAINT DF_pt_reason, COLUMN pt_reason;
ALTER TABLE /*_*/protected_titles DROP CONSTRAINT DF_pt_reason_id;
