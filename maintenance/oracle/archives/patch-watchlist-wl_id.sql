define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.watchlist ADD (
wl_id NUMBER NOT NULL,
);
ALTER TABLE &mw_prefix.watchlist ADD CONSTRAINT &mw_prefix.watchlist_pk PRIMARY KEY (wl_id);
