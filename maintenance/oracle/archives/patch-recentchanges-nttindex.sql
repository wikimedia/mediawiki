define mw_prefix='{$wgDBprefix}';

DROP INDEX IF EXISTS &mw_prefix.recentchanges_i02;
CREATE INDEX &mw_prefix.recentchanges_i09 ON &mw_prefix.recentchanges (rc_namespace, rc_title, rc_timestamp);
