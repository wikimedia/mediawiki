define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.externallinks ADD el_index_60 VARCHAR2(60);
CREATE INDEX &mw_prefix.externallinks_i04 ON &mw_prefix.externallinks (el_index_60, el_id);
CREATE INDEX &mw_prefix.externallinks_i05 ON &mw_prefix.externallinks (el_from, el_index_60, el_id);
