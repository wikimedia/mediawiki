DECLARE @base nvarchar(max),
	@SQL nvarchar(max),
	@id sysname;--

SET @base = 'ALTER TABLE /*_*/archive DROP CONSTRAINT ';--

SELECT @id = fk.name
FROM sys.foreign_keys fk
JOIN sys.foreign_key_columns fkc
	ON fkc.constraint_object_id = fk.object_id
JOIN sys.columns c
	ON c.column_id = fkc.parent_column_id
	AND c.object_id = fkc.parent_object_id
WHERE
	fk.parent_object_id = OBJECT_ID('/*_*/archive')
	AND fk.referenced_object_id = OBJECT_ID('/*_*/revision')
	AND c.name = 'ar_parent_id';--

SET @SQL = @base + @id;--

EXEC sp_executesql @SQL;--

-- while we're at it, let's fix up the other foreign key constraints on archive
-- as future patches touch constraints on other tables, they'll take the time to update constraint names there as well
SELECT @id = fk.name
FROM sys.foreign_keys fk
JOIN sys.foreign_key_columns fkc
	ON fkc.constraint_object_id = fk.object_id
JOIN sys.columns c
	ON c.column_id = fkc.parent_column_id
	AND c.object_id = fkc.parent_object_id
WHERE
	fk.parent_object_id = OBJECT_ID('/*_*/archive')
	AND fk.referenced_object_id = OBJECT_ID('/*_*/mwuser')
	AND c.name = 'ar_user';--

SET @SQL = @base + @id;--

EXEC sp_executesql @SQL;--

ALTER TABLE /*_*/archive ADD CONSTRAINT ar_user__user_id__fk FOREIGN KEY (ar_user) REFERENCES /*_*/mwuser(user_id);--

SELECT @id = fk.name
FROM sys.foreign_keys fk
JOIN sys.foreign_key_columns fkc
	ON fkc.constraint_object_id = fk.object_id
JOIN sys.columns c
	ON c.column_id = fkc.parent_column_id
	AND c.object_id = fkc.parent_object_id
WHERE
	fk.parent_object_id = OBJECT_ID('/*_*/archive')
	AND fk.referenced_object_id = OBJECT_ID('/*_*/text')
	AND c.name = 'ar_text_id';--

SET @SQL = @base + @id;--

EXEC sp_executesql @SQL;--

ALTER TABLE /*_*/archive ADD CONSTRAINT ar_text_id__old_id__fk FOREIGN KEY (ar_text_id) REFERENCES /*_*/text(old_id) ON DELETE CASCADE;
