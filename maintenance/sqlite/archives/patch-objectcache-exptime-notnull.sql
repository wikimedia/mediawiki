CREATE TABLE /*_*/objectcache_tmp (
  keyname BLOB DEFAULT '' NOT NULL,
  value BLOB DEFAULT NULL,
  exptime BLOB NOT NULL,
  PRIMARY KEY(keyname)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/objectcache_tmp(keyname, value, exptime)
  SELECT keyname, value, exptime FROM /*_*/objectcache;

DROP TABLE /*_*/objectcache;
ALTER TABLE /*_*/objectcache_tmp RENAME TO /*_*/objectcache;

CREATE INDEX exptime ON /*_*/objectcache (exptime);
