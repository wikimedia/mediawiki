CREATE TABLE /*_*/watchlist_label (
  wll_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  wll_user INT UNSIGNED NOT NULL,
  wll_name VARBINARY(255) NOT NULL,
  UNIQUE INDEX wll_user_name (wll_user, wll_name),
  PRIMARY KEY(wll_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/watchlist_label_member (
  wlm_label INT UNSIGNED NOT NULL,
  wlm_item INT UNSIGNED NOT NULL,
  INDEX wlm_item (wlm_item),
  PRIMARY KEY(wlm_label, wlm_item)
) /*$wgDBTableOptions*/;
