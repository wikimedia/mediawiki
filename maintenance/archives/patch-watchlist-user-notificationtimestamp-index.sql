--
-- Creates the wl_user_notificationtimestamp index for the watchlist table
--
CREATE INDEX /*i*/wl_user_notificationtimestamp ON /*_*/watchlist (wl_user, wl_notificationtimestamp);
