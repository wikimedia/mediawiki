-- Table associating distant wiki IDs with their interwiki prefixes.
CREATE TABLE /*_*/globalinterwiki (
  -- The wiki ID of the wiki
  giw_wikiid varchar(64) NOT NULL,

  -- The interwiki prefix of that wiki
  giw_prefix varchar(32) NOT NULL

) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/giw_index ON /*_*/globalinterwiki (giw_wikiid, giw_prefix);
