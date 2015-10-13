define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.watchlist ADD wl_expirytimestamp TIMESTAMP(6) WITH TIME ZONE;
