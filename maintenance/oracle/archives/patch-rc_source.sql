define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.recentchanges ADD rc_source VARCHAR2(16);
