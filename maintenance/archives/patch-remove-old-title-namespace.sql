-- remove old_title and old_namespace, replace with old_articleid

ALTER TABLE old
	DROP COLUMN old_title,
	DROP COLUMN old_namespace,
	ADD COLUMN old_articleid INT(8) UNSIGNED NOT NULL;

UPDATE old,cur
	SET old_articleid=cur_id
	WHERE old_title=cur_title
	AND old_namespace=cur_namespace;

DROP INDEX name_title_timestamp ON old;
DROP INDEX name_title ON old;
CREATE INDEX articleid_timestamp ON old (old_articleid, old_timestamp);
CREATE INDEX articleid ON old (old_articleid);
