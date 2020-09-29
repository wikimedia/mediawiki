CREATE TABLE /*_*/querycachetwo_tmp (
  qcc_type BLOB NOT NULL,
  qcc_value INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  qcc_namespace INTEGER DEFAULT 0 NOT NULL,
  qcc_title BLOB DEFAULT '' NOT NULL,
  qcc_namespacetwo INTEGER DEFAULT 0 NOT NULL,
  qcc_titletwo BLOB DEFAULT '' NOT NULL
);

INSERT INTO /*_*/querycachetwo_tmp
	SELECT qcc_type, qcc_value, qcc_namespace, qcc_title, qcc_namespacetwo, qcc_titletwo
		FROM /*_*/querycachetwo;
DROP TABLE /*_*/querycachetwo;
ALTER TABLE /*_*/querycachetwo_tmp RENAME TO /*_*/querycachetwo;

CREATE INDEX qcc_type ON /*_*/querycachetwo (qcc_type, qcc_value);

CREATE INDEX qcc_title ON /*_*/querycachetwo (
  qcc_type, qcc_namespace, qcc_title
);

CREATE INDEX qcc_titletwo ON /*_*/querycachetwo (
  qcc_type, qcc_namespacetwo, qcc_titletwo
);
