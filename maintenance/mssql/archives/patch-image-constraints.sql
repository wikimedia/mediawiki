DECLARE @baseSQL nvarchar(max),
	@SQL nvarchar(max),
	@id sysname;--

SET @baseSQL = 'ALTER TABLE /*_*/image DROP CONSTRAINT ';--

SELECT @id = cc.name
FROM sys.check_constraints cc
JOIN sys.columns c
	ON c.object_id = cc.parent_object_id
	AND c.column_id = cc.parent_column_id
WHERE
	cc.parent_object_id = OBJECT_ID('/*_*/image')
	AND c.name = 'img_major_mime';--

SET @SQL = @baseSQL + @id;--

EXEC sp_executesql @SQL;--

SELECT @id = cc.name
FROM sys.check_constraints cc
JOIN sys.columns c
	ON c.object_id = cc.parent_object_id
	AND c.column_id = cc.parent_column_id
WHERE
	cc.parent_object_id = OBJECT_ID('/*_*/image')
	AND c.name = 'img_media_type';--

SET @SQL = @baseSQL + @id;--

EXEC sp_executesql @SQL;--

ALTER TABLE /*_*/image ADD CONSTRAINT img_major_mime_ckc check (img_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart'));--
ALTER TABLE /*_*/image ADD CONSTRAINT img_media_type_ckc check (img_media_type in('UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO', 'VIDEO', 'MULTIMEDIA', 'OFFICE', 'TEXT', 'EXECUTABLE', 'ARCHIVE'));
