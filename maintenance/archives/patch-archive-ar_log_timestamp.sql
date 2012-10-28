--
-- patch-archive-ar_log_timestamp.sql
--
-- Bug 39675. Add archive.ar_log_timestamp.

ALTER TABLE /*$wgDBprefix*/archive
	ADD ar_log_timestamp binary(14) NOT NULL default '';