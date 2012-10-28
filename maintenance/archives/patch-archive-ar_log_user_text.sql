--
-- patch-archive-ar_log_user_text.sql
--
-- Bug 39675. Add archive.ar_log_user_text.

ALTER TABLE /*$wgDBprefix*/archive
	ADD ar_log_user_text varchar(255) binary NOT NULL;