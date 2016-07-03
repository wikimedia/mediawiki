DROP TABLE IF EXISTS /*_*/watchlist_tmp;

CREATE TABLE /*$wgDBprefix*/watchlist_tmp (
  wl_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  wl_user INTEGER  NOT NULL,
  wl_namespace INTEGER NOT NULL default 0,
  wl_title TEXT  NOT NULL default '',
  wl_notificationtimestamp BLOB
);

INSERT OR IGNORE INTO /*_*/watchlist_tmp (
    wl_user, wl_namespace, wl_title, wl_notificationtimestamp )
    SELECT
    wl_user, wl_namespace, wl_title, wl_notificationtimestamp
    FROM /*_*/watchlist;

DROP TABLE /*_*/watchlist;

ALTER TABLE /*_*/watchlist_tmp RENAME TO /*_*/watchlist;

CREATE UNIQUE INDEX /*i*/wl_user ON /*_*/watchlist (wl_user, wl_namespace, wl_title);
CREATE INDEX /*i*/namespace_title ON /*_*/watchlist (wl_namespace, wl_title);
CREATE INDEX /*i*/wl_user_notificationtimestamp ON /*_*/watchlist (wl_user, wl_notificationtimestamp);
