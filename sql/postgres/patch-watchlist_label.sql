CREATE TABLE watchlist_label (
  wll_id SERIAL NOT NULL,
  wll_user INT NOT NULL,
  wll_name TEXT NOT NULL,
  PRIMARY KEY(wll_id)
);

CREATE UNIQUE INDEX wll_user_name ON watchlist_label (wll_user, wll_name);


CREATE TABLE watchlist_label_member (
  wlm_label INT NOT NULL,
  wlm_item INT NOT NULL,
  PRIMARY KEY(wlm_label, wlm_item)
);

CREATE INDEX wlm_item ON watchlist_label_member (wlm_item);
