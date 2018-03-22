DECLARE @cname sysname;--

SELECT @cname = dc.name
FROM sys.default_constraints dc
JOIN sys.columns c
	ON c.object_id = dc.parent_object_id
	AND c.column_id = dc.parent_column_id
WHERE
	c.name = 'rc_patrolled'
	AND c.object_id = OBJECT_ID('/*_*/recentchanges', 'U');--

IF @cname IS NOT NULL
BEGIN;--
	DECLARE @sql nvarchar(max);--
	SET @sql = N'ALTER TABLE /*_*/recentchanges DROP CONSTRAINT ' + @cname;--
	EXEC sp_executesql @sql;--
END;--

DROP INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges;--
ALTER TABLE /*_*/recentchanges ALTER COLUMN rc_patrolled tinyint NOT NULL;--
ALTER TABLE /*_*/recentchanges ADD CONSTRAINT DF_rc_patrolled DEFAULT 0 FOR rc_patrolled;--
CREATE INDEX /*i*/rc_name_type_patrolled_timestamp ON /*_*/recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);