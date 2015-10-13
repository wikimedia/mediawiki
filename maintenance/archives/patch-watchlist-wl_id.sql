-- Primary key in watchlist

ALTER TABLE /*$wgDBprefix*/watchlist
  ADD wl_id int NOT NULL auto_increment,
  ADD PRIMARY KEY wl_id (wl_id);
