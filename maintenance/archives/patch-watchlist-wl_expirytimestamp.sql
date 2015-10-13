--
-- patch-watchlist-wl_expirytimestamp.sql
--
ALTER TABLE /*$wgDBprefix*/watchlist
  ADD wl_expirytimestamp varbinary(14) NULL default NULL,
  ADD INDEX wl_expirytimestamp(wl_expirytimestamp);
