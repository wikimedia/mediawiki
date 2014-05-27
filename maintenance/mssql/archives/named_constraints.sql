DECLARE @fullyQualifiedTableName nvarchar(max),
@tableName sysname,
@fieldName sysname,
@constr sysname,
@constrNew sysname,
@sqlcmd nvarchar(max),
@sqlcreate nvarchar(max)

SET @fullyQualifiedTableName = '/*_*//*$tableName*/'
SET @tableName = '/*$tableName*/'
SET @fieldName = '/*$fieldName*/'

SELECT @constr = CONSTRAINT_NAME
FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
WHERE TABLE_NAME = @tableName
AND CONSTRAINT_CATALOG = '/*$wgDBname*/'
AND CONSTRAINT_SCHEMA = '/*$wgDBmwschema*/'
AND CONSTRAINT_TYPE = 'CHECK'
AND CONSTRAINT_NAME LIKE ('CK__' + left(@tableName,9) + '__' + left(@fieldName,5) + '%')

SELECT @constrNew = CONSTRAINT_NAME
FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
WHERE TABLE_NAME = @tableName
AND CONSTRAINT_CATALOG = '/*$wgDBname*/'
AND CONSTRAINT_SCHEMA = '/*$wgDBmwschema*/'
AND CONSTRAINT_TYPE = 'CHECK'
AND CONSTRAINT_NAME = (@fieldName + '_ckc')

IF @constr IS NOT NULL
BEGIN
  SET @sqlcmd =  'ALTER TABLE ' + @fullyQualifiedTableName + ' DROP CONSTRAINT [' + @constr + ']'
  EXECUTE sp_executesql @sqlcmd
END
IF @constrNew IS NULL
BEGIN
  SET @sqlcreate =  'ALTER TABLE ' + @fullyQualifiedTableName + ' WITH NOCHECK ADD CONSTRAINT ' + @fieldName + '_ckc CHECK /*$checkConstraint*/;'
  EXECUTE sp_executesql @sqlcreate
END