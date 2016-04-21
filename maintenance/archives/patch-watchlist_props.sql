-- Name/value pairs indexed by wl_id
CREATE TABLE /*_*/watchlist_props (
  wlp_item int NOT NULL,
  wlp_propname varbinary(60) NOT NULL,
  wlp_value blob NOT NULL,
  wlp_sortkey float DEFAULT NULL,
  PRIMARY KEY (wlp_item,wlp_propname)
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/wlp_propname_item ON /*_*/watchlist_props (wlp_propname,wlp_item);
CREATE UNIQUE INDEX /*i*/wlp_propname_sortkey_item ON /*_*/watchlist_props (wlp_propname,wlp_sortkey,wlp_item);