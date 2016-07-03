DECLARE @base nvarchar(max),
	@SQL nvarchar(max),
	@id sysname;--

SET @base = 'ALTER TABLE /*_*/logging DROP CONSTRAINT ';--

SELECT @id = fk.name
FROM sys.foreign_keys fk
JOIN sys.foreign_key_columns fkc
	ON fkc.constraint_object_id = fk.object_id
JOIN sys.columns c
	ON c.column_id = fkc.parent_column_id
	AND c.object_id = fkc.parent_object_id
WHERE
	fk.parent_object_id = OBJECT_ID('/*_*/logging')
	AND fk.referenced_object_id = OBJECT_ID('/*_*/mwuser')
	AND c.name = 'log_user';--

SET @SQL = @base + @id;--

EXEC sp_executesql @SQL;--

SELECT @id = fk.name
FROM sys.foreign_keys fk
JOIN sys.foreign_key_columns fkc
	ON fkc.constraint_object_id = fk.object_id
JOIN sys.columns c
	ON c.column_id = fkc.parent_column_id
	AND c.object_id = fkc.parent_object_id
WHERE
	fk.parent_object_id = OBJECT_ID('/*_*/logging')
	AND fk.referenced_object_id = OBJECT_ID('/*_*/page')
	AND c.name = 'log_page';--

SET @SQL = @base + @id;--

EXEC sp_executesql @SQL;
