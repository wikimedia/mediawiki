CREATE TABLE /*_*/imagelinks_tmp (
  il_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  il_to BLOB DEFAULT '' NOT NULL,
  il_from_namespace INTEGER DEFAULT 0 NOT NULL,
  PRIMARY KEY(il_from, il_to)
);

INSERT INTO /*_*/imagelinks_tmp
	SELECT il_from, il_to, il_from_namespace
		FROM /*_*/imagelinks;
DROP TABLE /*_*/imagelinks;
ALTER TABLE /*_*/imagelinks_tmp RENAME TO /*_*/imagelinks;

CREATE INDEX il_to ON /*_*/imagelinks (il_to, il_from);

CREATE INDEX il_backlinks_namespace ON /*_*/imagelinks (
  il_from_namespace, il_to, il_from
);
