-- Allows setting an expiry for watchlist items.
CREATE TABLE /*_*/watchlist_expiry (
  -- Key to watchlist.wl_id
  we_item int unsigned NOT NULL PRIMARY KEY,
  -- Expiry time
  we_expiry binary(14) NOT NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/we_expiry ON /*_*/watchlist_expiry (we_expiry);
