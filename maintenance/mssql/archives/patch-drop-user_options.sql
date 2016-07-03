DECLARE @sql nvarchar(max),
	@id sysname;--

SET @sql = 'ALTER TABLE /*_*/mwuser DROP CONSTRAINT ';--

SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/mwuser')
	AND c.name = 'user_options';--

SET @sql = @sql + @id;--

EXEC sp_executesql @sql;--

ALTER TABLE /*_*/mwuser DROP COLUMN user_options;
