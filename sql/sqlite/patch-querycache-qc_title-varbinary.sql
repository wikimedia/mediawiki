CREATE TABLE /*_*/querycache_tmp (
  qc_type BLOB NOT NULL,
  qc_value INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  qc_namespace INTEGER DEFAULT 0 NOT NULL,
  qc_title BLOB DEFAULT '' NOT NULL
);

INSERT INTO /*_*/querycache_tmp
	SELECT qc_type, qc_value, qc_namespace, qc_title
		FROM /*_*/querycache;
DROP TABLE /*_*/querycache;
ALTER TABLE /*_*/querycache_tmp RENAME TO /*_*/querycache;

CREATE INDEX qc_type ON /*_*/querycache (qc_type, qc_value);
