ALTER TABLE /*_*/templatelinks
	ADD COLUMN tl_from_namespace int NOT NULL default 0;

CREATE INDEX /*i*/tl_backlinks_namespace ON /*_*/templatelinks (tl_from_namespace,tl_namespace,tl_title,tl_from);
