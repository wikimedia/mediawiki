CREATE TABLE /*_*/pagelinks_tmp (
  pl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  pl_namespace INTEGER DEFAULT 0 NOT NULL,
  pl_title BLOB DEFAULT '' NOT NULL,
  pl_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(pl_from, pl_namespace, pl_title)
);

INSERT INTO /*_*/pagelinks_tmp
	SELECT pl_from, pl_namespace, pl_title, pl_from_namespace
		FROM /*_*/pagelinks;
DROP TABLE /*_*/pagelinks;
ALTER TABLE /*_*/pagelinks_tmp RENAME TO /*_*/pagelinks;

CREATE INDEX pl_namespace ON /*_*/pagelinks (pl_namespace, pl_title, pl_from);
CREATE INDEX pl_backlinks_namespace ON /*_*/pagelinks (
  pl_from_namespace, pl_namespace,
  pl_title, pl_from
);
