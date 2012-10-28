--
-- patch-archive-ar_log_comment.sql
--
-- Bug 39675. Add archive.ar_log_comment.

ALTER TABLE /*$wgDBprefix*/archive
	ADD ar_log_comment varchar(255) NOT NULL default '';