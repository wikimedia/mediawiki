CREATE TABLE /*_*/transcache_tmp (
  tc_url varbinary(255) NOT NULL PRIMARY KEY,
  tc_contents text,
  tc_time binary(14) NOT NULL
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/transcache_tmp
	SELECT * FROM /*_*/transcache;

DROP TABLE /*_*/transcache;

ALTER TABLE /*_*/transcache_tmp RENAME TO /*_*/transcache;