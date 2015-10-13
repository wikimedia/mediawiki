--
-- patch-watchlist-wl_expirytimestamp.sql
--
ALTER TABLE /*$wgDBprefix*/watchlist
  ADD wl_expirytimestamp varbinary(14) NOT NULL default '';

CREATE INDEX /*i*/wl_expirytimestamp
        ON /*_*/watchlist ( wl_expirytimestamp );