define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.watchlist ADD wl_timestamp TIMESTAMP(6) WITH TIME ZONE;
CREATE INDEX &mw_prefix.watchlist_i02 ON &mw_prefix.watchlist (wl_timestamp);
