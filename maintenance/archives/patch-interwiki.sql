-- Creates interwiki prefix<->url mapping table
-- used from 2003-08-21 dev version.
-- Import the default mappings from maintenance/interwiki.sql

CREATE TABLE interwiki (
  iw_prefix char(32) NOT NULL,
  iw_url char(127) NOT NULL,
  iw_local BOOL NOT NULL,
  UNIQUE KEY iw_prefix (iw_prefix)
);

