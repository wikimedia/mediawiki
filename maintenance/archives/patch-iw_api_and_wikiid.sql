--
-- Add iw_api and iw_wikiid to interwiki table
--

ALTER TABLE /*_*/interwiki
	ADD iw_api BLOB NOT NULL;
ALTER TABLE /*_*/interwiki
	ADD iw_wikiid varchar(64) NOT NULL;

