DECLARE @baseSQL nvarchar(max),
	@SQL nvarchar(max),
	@id sysname;--

SET @baseSQL = 'ALTER TABLE /*_*/uploadstash DROP CONSTRAINT ';--

SELECT @id = cc.name
FROM sys.check_constraints cc
JOIN sys.columns c
	ON c.object_id = cc.parent_object_id
	AND c.column_id = cc.parent_column_id
WHERE
	cc.parent_object_id = OBJECT_ID('/*_*/uploadstash')
	AND c.name = 'us_media_type';--

SET @SQL = @baseSQL + @id;--

EXEC sp_executesql @SQL;--

ALTER TABLE /*_*/uploadstash ADD CONSTRAINT us_media_type_ckc check (us_media_type in('UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO', 'VIDEO', 'MULTIMEDIA', 'OFFICE', 'TEXT', 'EXECUTABLE', 'ARCHIVE'));
