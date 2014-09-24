ALTER TABLE /*_*/externallinks
	ADD COLUMN el_from_namespace int NOT NULL default 0;

CREATE INDEX /*i*/el_backlinks_to ON /*_*/externallinks (el_to(60), el_from_namespace, el_from);
