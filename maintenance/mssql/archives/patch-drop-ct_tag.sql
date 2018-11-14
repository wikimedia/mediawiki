-- T185355
ALTER TABLE /*_*/change_tag ALTER COLUMN ct_tag INTEGER NOT NULL

DECLARE @sql nvarchar(max),
	@id sysname;--

SET @sql = 'ALTER TABLE /*_*/change_tag DROP CONSTRAINT ';--

SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/change_tag')
	AND c.name = 'ct_tag';--

SET @sql = @sql + @id;--

EXEC sp_executesql @sql;--

ALTER TABLE /*_*/change_tag DROP COLUMN ct_tag;
