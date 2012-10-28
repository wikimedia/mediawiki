--
-- patch-archive-ar_log_id.sql
--
-- Bug 39675. Add archive.ar_log_id.

ALTER TABLE /*$wgDBprefix*/archive
	ADD COLUMN ar_log_id int unsigned NOT NULL default 0;