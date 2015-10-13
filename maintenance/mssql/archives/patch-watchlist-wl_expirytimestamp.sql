ALTER TABLE /*_*/watchlist ADD wl_expirytimestamp VARCHAR(14) DEFAULT '';
CREATE INDEX /*i*/wl_expirytimestamp ON /*_*/watchlist (wl_expirytimestamp)