--
-- patch-archive-el_id.sql
--
-- Bug 15441. Add externallinks.el_id.

ALTER TABLE /*$wgDBprefix*/externallinks
	ADD COLUMN el_id int unsigned NOT NULL AUTO_INCREMENT,
        ADD PRIMARY KEY el_id (el_id);