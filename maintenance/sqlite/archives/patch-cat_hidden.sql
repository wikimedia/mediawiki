-- cat_hidden is no longer used, delete it

CREATE TABLE /*_*/category_tmp (
  cat_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  cat_title varchar(255) binary NOT NULL,
  cat_pages int signed NOT NULL default 0,
  cat_subcats int signed NOT NULL default 0,
  cat_files int signed NOT NULL default 0
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/category_tmp
	SELECT cat_id, cat_title, cat_pages, cat_subcats, cat_files
		FROM /*_*/category;

DROP TABLE /*_*/category;

ALTER TABLE /*_*/category_tmp RENAME TO /*_*/category;

CREATE UNIQUE INDEX /*i*/cat_title ON /*_*/category (cat_title);
CREATE INDEX /*i*/cat_pages ON /*_*/category (cat_pages);
