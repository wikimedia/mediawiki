DECLARE @sql nvarchar(max),
	@id sysname;--

SET @sql = 'ALTER TABLE /*_*/page DROP CONSTRAINT ';--

SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/page')
	AND c.name = 'page_counter';--

SET @sql = @sql + @id;--

EXEC sp_executesql @sql;--

ALTER TABLE /*_*/page DROP COLUMN page_counter;
