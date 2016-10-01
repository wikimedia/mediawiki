DROP TABLE IF EXISTS /*_*/querycachetwo_tmp;

CREATE TABLE /*$wgDBprefix*/querycachetwo_tmp (
  qcc_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  qcc_type varbinary(32) NOT NULL,
  qcc_value int unsigned NOT NULL default 0,
  qcc_namespace int NOT NULL default 0,
  qcc_title varchar(255) binary NOT NULL default '',
  qcc_namespacetwo int NOT NULL default 0,
  qcc_titletwo varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/querycachetwo_tmp (
    qcc_type, qcc_value, qcc_namespace, qcc_title, qcc_namespacetwo, qcc_titletwo )
    SELECT
    qcc_type, qcc_value, qcc_namespace, qcc_title, qcc_namespacetwo, qcc_titletwo
    FROM /*_*/querycachetwo;

DROP TABLE /*_*/querycachetwo;

ALTER TABLE /*_*/querycachetwo_tmp RENAME TO /*_*/querycachetwo;

CREATE INDEX /*i*/qcc_type ON /*_*/querycachetwo (qcc_type,qcc_value);
CREATE INDEX /*i*/qcc_title ON /*_*/querycachetwo (qcc_type,qcc_namespace,qcc_title);
CREATE INDEX /*i*/qcc_titletwo ON /*_*/querycachetwo (qcc_type,qcc_namespacetwo,qcc_titletwo);