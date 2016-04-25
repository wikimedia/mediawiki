DECLARE @baseSQL nvarchar(max),
	@SQL nvarchar(max),
	@id sysname;--

SET @baseSQL = 'ALTER TABLE /*_*/categorylinks DROP CONSTRAINT ';--

SELECT @id = cc.name
FROM sys.check_constraints cc
JOIN sys.columns c
	ON c.object_id = cc.parent_object_id
	AND c.column_id = cc.parent_column_id
WHERE
	cc.parent_object_id = OBJECT_ID('/*_*/categorylinks')
	AND c.name = 'cl_type';--

SET @SQL = @baseSQL + @id;--

EXEC sp_executesql @SQL;--

ALTER TABLE /*_*/categorylinks ADD CONSTRAINT cl_type_ckc CHECK (cl_type IN('page', 'subcat', 'file'));
