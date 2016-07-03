ALTER TABLE /*_*/watchlist ADD wl_id INT IDENTITY;
ALTER TABLE /*_*/watchlist ADD CONSTRAINT pk_watchlist PRIMARY KEY(wl_id)
