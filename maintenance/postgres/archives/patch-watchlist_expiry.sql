CREATE SEQUENCE watchlist_expiry_we_item_seq;

CREATE TABLE watchlist_expiry (
  we_item   INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('watchlist_expiry_we_item_seq'),
  we_expiry TIMESTAMPTZ NOT NULL
);

CREATE INDEX we_expiry ON watchlist_expiry (we_expiry);
