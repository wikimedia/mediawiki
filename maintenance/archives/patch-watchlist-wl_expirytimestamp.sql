-- TODO index?
ALTER TABLE /*$wgDBprefix*/watchlist
  ADD wl_expirytimestamp varbinary(14) NULL default NULL;
