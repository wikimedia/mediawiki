ALTER TABLE /*$wgDBprefix*/watchlist ADD wl_group int unsigned NOT NULL default 0;

DROP INDEX /*i*/wl_group ON /*_*/watchlist;
CREATE UNIQUE INDEX /*i*/wl_group ON /*_*/watchlist (wl_user, wl_group, wl_namespace, wl_title);