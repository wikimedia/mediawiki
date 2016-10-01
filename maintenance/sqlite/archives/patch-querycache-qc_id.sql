DROP TABLE IF EXISTS /*_*/querycache_tmp;

CREATE TABLE /*$wgDBprefix*/querycache_tmp (
  qc_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  qc_type varbinary(32) NOT NULL,
  qc_value int unsigned NOT NULL default 0,
  qc_namespace int NOT NULL default 0,
  qc_title varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;
);

INSERT OR IGNORE INTO /*_*/querycache_tmp (
    qc_type, qc_value, qc_namespace, qc_title )
    SELECT
    qc_type, qc_value, qc_namespace, qc_title
    FROM /*_*/querycache;

DROP TABLE /*_*/querycache;

ALTER TABLE /*_*/querycache_tmp RENAME TO /*_*/querycache;

CREATE INDEX /*i*/qc_type ON /*_*/querycache (qc_type,qc_value);