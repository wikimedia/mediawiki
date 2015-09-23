ALTER TABLE /*_*/pagelinks
	ADD COLUMN pl_from_namespace int NOT NULL default 0;

CREATE INDEX /*i*/pl_backlinks_namespace ON /*_*/pagelinks (pl_from_namespace,pl_namespace,pl_title,pl_from);
