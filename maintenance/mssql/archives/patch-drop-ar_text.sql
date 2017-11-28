DECLARE @sql nvarchar(max),
	@id sysname;--

SET @sql = 'ALTER TABLE /*_*/archive DROP CONSTRAINT ';--

SELECT @id = df.name
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id = OBJECT_ID('/*_*/archive')
	AND ( c.name = 'ar_text' OR c.name = 'ar_flags' );--

SET @sql = @sql + @id;--

EXEC sp_executesql @sql;--

ALTER TABLE /*_*/archive DROP COLUMN ar_text;
ALTER TABLE /*_*/archive DROP COLUMN ar_flags;
ALTER TABLE /*_*/archive ALTER COLUMN ar_text_id INT NOT NULL CONSTRAINT DF_ar_text_id DEFAULT 0;
