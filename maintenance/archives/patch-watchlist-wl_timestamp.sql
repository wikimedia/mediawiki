--
-- patch-watchlist-wl_timestamp.sql
--
ALTER TABLE /*$wgDBprefix*/watchlist
  ADD wl_timestamp varbinary(14);

CREATE INDEX /*i*/wl_timestamp
        ON /*_*/watchlist ( wl_timestamp );
