ALTER TABLE /*$wgDBprefix*/watchlist ADD wl_group int unsigned NOT NULL default 0;

DROP INDEX /*i*/wl_user ON /*_*/watchlist;
CREATE UNIQUE INDEX /*i*/wl_user ON /*_*/watchlist (wl_user, wl_group, wl_namespace, wl_title);