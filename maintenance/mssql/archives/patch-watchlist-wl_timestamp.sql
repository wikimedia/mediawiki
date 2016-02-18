ALTER TABLE /*_*/watchlist ADD wl_timestamp VARCHAR(14) DEFAULT '';
CREATE INDEX /*i*/wl_timestamp ON /*_*/watchlist (wl_timestamp)
