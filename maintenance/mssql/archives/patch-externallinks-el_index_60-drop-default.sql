DECLARE @sql nvarchar(max)
SET @sql=''

SELECT @sql= @sql + 'ALTER TABLE /*_*/externallinks DROP CONSTRAINT ' + df.name + '; '
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id =  OBJECT_ID('/*_*/externallinks')
	AND c.name = 'el_index_60';--

EXEC sp_executesql @sql;
