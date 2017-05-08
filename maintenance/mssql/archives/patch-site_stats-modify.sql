/* Delete old default constraints */
DECLARE @sql nvarchar(max)
SET @sql=''

/* IMHO: A DBMS where you have to do THIS to change a default value sucks. */
SELECT @sql= @sql + 'ALTER TABLE site_stats DROP CONSTRAINT ' + df.name + '; '
FROM sys.default_constraints df
JOIN sys.columns c
	ON c.object_id = df.parent_object_id
	AND c.column_id = df.parent_column_id
WHERE
	df.parent_object_id =  OBJECT_ID('site_stats');--

EXEC sp_executesql @sql;

/* Change data type of ss_images from int to bigint.
 * All other fields (except ss_row_id) already are bigint.
 * This MUST happen before adding new constraints. */
ALTER TABLE site_stats ALTER COLUMN ss_images bigint;

/* Add new default constraints.
 * Don't ask me why I have to repeat ALTER TABLE site_stats
 * instead of using commas, for some reason SQL Server 2016
 * didn't accept it in any other way. Maybe I just don't know
 * enough about mssql, but this works.
 */
ALTER TABLE site_stats ADD CONSTRAINT col_ss_total_edits DEFAULT NULL FOR ss_total_edits;
ALTER TABLE site_stats ADD CONSTRAINT col_ss_good_article DEFAULT NULL FOR ss_good_articles;
ALTER TABLE site_stats ADD CONSTRAINT col_ss_total_pages DEFAULT NULL FOR ss_total_pages;
ALTER TABLE site_stats ADD CONSTRAINT col_ss_users DEFAULT NULL FOR ss_users;
ALTER TABLE site_stats ADD CONSTRAINT col_ss_active_users DEFAULT NULL FOR ss_active_users;
ALTER TABLE site_stats ADD CONSTRAINT col_ss_images DEFAULT NULL FOR ss_images;
