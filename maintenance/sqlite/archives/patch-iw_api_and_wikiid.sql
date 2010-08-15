--
-- Add iw_api and iw_wikiid to interwiki table
--


CREATE TABLE /*_*/interwiki_tmp (
  iw_prefix TEXT NOT NULL,
  iw_url BLOB NOT NULL,
  iw_api BLOB NOT NULL,
  iw_wikiid TEXT NOT NULL,
  iw_local INTEGER NOT NULL,
  iw_trans INTEGER NOT NULL default 0
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/interwiki_tmp SELECT iw_prefix, iw_url, '', '', iw_local, iw_trans FROM /*_*/interwiki;
DROP TABLE /*_*/interwiki;
ALTER TABLE /*_*/interwiki_tmp RENAME TO /*_*/interwiki;
