-- Primary key in watchlist

ALTER TABLE /*$wgDBprefix*/watchlist
  ADD COLUMN ar_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (wl_id);
