define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.recentchanges DROP ( rc_moved_to_ns, rc_moved_to_title );

