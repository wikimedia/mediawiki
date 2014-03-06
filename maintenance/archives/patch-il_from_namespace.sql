ALTER TABLE /*_*/imagelinks
  ADD COLUMN il_from_namespace int NOT NULL default 0;

CREATE INDEX /*i*/il_backlinks_namespace ON /*_*/imagelinks (il_to,il_from_namespace,il_from);