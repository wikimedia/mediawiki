CREATE TABLE /*_*/objectcache_tmp (
  keyname varbinary(255) NOT NULL default '' PRIMARY KEY,
  value mediumblob,
  exptime datetime
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/objectcache_tmp
	SELECT * FROM /*_*/objectcache;

DROP TABLE /*_*/objectcache;

ALTER TABLE /*_*/objectcache_tmp RENAME TO /*_*/objectcache;

CREATE INDEX /*i*/exptime ON /*_*/objectcache (exptime);