--
-- patch-archive-ar_log_user.sql
--
-- Bug 39675. Add archive.ar_log_user.

ALTER TABLE /*$wgDBprefix*/archive
	ADD ar_log_user int unsigned NOT NULL default 0;