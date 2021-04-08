CREATE TABLE /*_*/watchlist_tmp (
  wl_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  wl_user INTEGER UNSIGNED NOT NULL,
  wl_namespace INTEGER DEFAULT 0 NOT NULL,
  wl_title BLOB DEFAULT '' NOT NULL,
  wl_notificationtimestamp BLOB DEFAULT NULL
);

INSERT INTO /*_*/watchlist_tmp
  SELECT wl_id, wl_user, wl_namespace, wl_title, wl_notificationtimestamp
    FROM /*_*/watchlist;
DROP TABLE /*_*/watchlist;
ALTER TABLE /*_*/watchlist_tmp RENAME TO /*_*/watchlist;

CREATE UNIQUE INDEX wl_user ON /*_*/watchlist (wl_user, wl_namespace, wl_title);

CREATE INDEX namespace_title ON /*_*/watchlist (wl_namespace, wl_title);

CREATE INDEX wl_user_notificationtimestamp ON /*_*/watchlist (
  wl_user, wl_notificationtimestamp
);