CREATE TABLE /*_*/content_tmp (
  content_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  content_size INTEGER UNSIGNED NOT NULL,
  content_sha1 BLOB NOT NULL,
  content_model SMALLINT UNSIGNED NOT NULL,
  content_address BLOB NOT NULL
);

INSERT INTO /*_*/content_tmp
	SELECT content_id, content_size, content_sha1, content_model, content_address
		FROM /*_*/content;
DROP TABLE /*_*/content;
ALTER TABLE /*_*/content_tmp RENAME TO /*_*/content;
