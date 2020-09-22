CREATE TABLE /*_*/templatelinks_tmp (
  tl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  tl_namespace INTEGER DEFAULT 0 NOT NULL,
  tl_title BLOB DEFAULT '' NOT NULL,
  tl_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(tl_from, tl_namespace, tl_title)
);

INSERT INTO /*_*/templatelinks_tmp
	SELECT tl_from, tl_namespace, tl_title, tl_from_namespace
		FROM /*_*/templatelinks;
DROP TABLE /*_*/templatelinks;
ALTER TABLE /*_*/templatelinks_tmp RENAME TO /*_*/templatelinks;

CREATE INDEX tl_namespace ON /*_*/templatelinks (tl_namespace, tl_title, tl_from);

CREATE INDEX tl_backlinks_namespace ON /*_*/templatelinks (
  tl_from_namespace, tl_namespace,
  tl_title, tl_from
);
