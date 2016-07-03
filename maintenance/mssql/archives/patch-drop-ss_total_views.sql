DECLARE @sql nvarchar(max),
	@id sysname;--

SET @sql = 'ALTER TABLE /*_*/site_stats DROP CONSTRAINT ';--

SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/site_stats')
	AND c.name = 'ss_total_views';--

SET @sql = @sql + @id;--

EXEC sp_executesql @sql;--

ALTER TABLE /*_*/site_stats DROP COLUMN ss_total_views;
