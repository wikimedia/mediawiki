-- Set up wl_notificationtimestamp with NULL support.
-- 2005-08-17

ALTER TABLE /*$wgDBprefix*/watchlist
  CHANGE wl_notificationtimestamp wl_notificationtimestamp varchar(14) binary;

UPDATE /*$wgDBprefix*/watchlist
  SET wl_notificationtimestamp=NULL
  WHERE wl_notificationtimestamp='0';
