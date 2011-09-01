define mw_prefix='{$wgDBprefix}';

CREATE TABLE &mw_prefix.globalinterwiki (
	giw_wikiid VARCHAR2(64) NOT NULL,
	giw_prefix VARCHAR2(32) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.globalinterwiki_u01 ON &mw_prefix.globalinterwiki (giw_wikiid, giw_prefix);

