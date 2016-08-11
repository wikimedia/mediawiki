define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.recentchanges_i08 ON &mw_prefix.recentchanges (rc_namespace, rc_type, rc_patrolled, rc_timestamp);

