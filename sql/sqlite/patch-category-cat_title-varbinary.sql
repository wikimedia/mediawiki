CREATE TABLE /*_*/category_tmp (
  cat_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  cat_title BLOB NOT NULL,
  cat_pages INTEGER DEFAULT 0 NOT NULL,
  cat_subcats INTEGER DEFAULT 0 NOT NULL,
  cat_files INTEGER DEFAULT 0 NOT NULL
);
INSERT INTO /*_*/category_tmp
	SELECT cat_id, cat_title, cat_pages, cat_subcats, cat_files
		FROM /*_*/category;
DROP TABLE /*_*/category;
ALTER TABLE /*_*/category_tmp RENAME TO /*_*/category;

CREATE UNIQUE INDEX cat_title ON /*_*/category (cat_title);
CREATE INDEX cat_pages ON /*_*/category (cat_pages);
