CREATE TABLE /*_*/watchlist_label (
  wll_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  wll_user INTEGER UNSIGNED NOT NULL,
  wll_name BLOB NOT NULL
);

CREATE UNIQUE INDEX wll_user_name ON /*_*/watchlist_label (wll_user, wll_name);


CREATE TABLE /*_*/watchlist_label_member (
  wlm_label INTEGER UNSIGNED NOT NULL,
  wlm_item INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(wlm_label, wlm_item)
);

CREATE INDEX wlm_item ON /*_*/watchlist_label_member (wlm_item);
