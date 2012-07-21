ALTER TABLE /*$wgDBprefix*/watchlist ADD wl_group int unsigned NOT NULL default 0;

DROP INDEX wl_group;
CREATE UNIQUE INDEX wl_group ON /*_*/watchlist (wl_user, wl_group, wl_namespace, wl_title);