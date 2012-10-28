--
-- patch-archive-el_id.sql
--
-- Bug 15441. Add externallinks.el_id.

ALTER TABLE /*$wgDBprefix*/externallinks
	ADD COLUMN el_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT;