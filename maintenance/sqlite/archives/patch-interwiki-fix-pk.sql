CREATE TABLE /*_*/interwiki_tmp (
  -- The interwiki prefix, (e.g. "Meatball", or the language prefix "de")
  iw_prefix varchar(32) NOT NULL PRIMARY KEY,

  -- The URL of the wiki, with "$1" as a placeholder for an article name.
  -- Any spaces in the name will be transformed to underscores before
  -- insertion.
  iw_url blob NOT NULL,

  -- The URL of the file api.php
  iw_api blob NOT NULL,

  -- The name of the database (for a connection to be established with LBFactory::getMainLB( 'wikiid' ))
  iw_wikiid varchar(64) NOT NULL,

  -- A boolean value indicating whether the wiki is in this project
  -- (used, for example, to detect redirect loops)
  iw_local bool NOT NULL,

  -- Boolean value indicating whether interwiki transclusions are allowed.
  iw_trans tinyint NOT NULL default 0
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/interwiki_tmp(iw_prefix, iw_url, iw_api, iw_wikiid, iw_local, iw_local, iw_trans)
	SELECT iw_prefix, iw_url, iw_api, iw_wikiid, iw_local, iw_local, iw_trans FROM /*_*/interwiki;

DROP TABLE /*_*/interwiki;

ALTER TABLE /*_*/interwiki_tmp RENAME TO /*_*/interwiki;
