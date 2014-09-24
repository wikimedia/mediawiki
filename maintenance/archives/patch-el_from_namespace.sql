ALTER TABLE /*_*/externallinks
	ADD COLUMN el_from_namespace int NOT NULL default 0;

CREATE INDEX /*i*/el_backlinks_namespace ON /*_*/externallinks (el_from_namespace, el_to(60), el_from);
