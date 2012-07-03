ALTER TABLE /*_*/langlinks
	ADD COLUMN ll_local bool NOT NULL DEFAULT true;

CREATE INDEX /*i*/ll_local ON /*_*/langlinks (ll_local);