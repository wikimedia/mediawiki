--
-- patch-archive-ar_id.sql
--
-- Bug 39675. Add archive.ar_id.

ALTER TABLE /*$wgDBprefix*/archive
	ADD COLUMN ar_id int unsigned NOT NULL AUTO_INCREMENT,
        ADD COLUMN ar_log_id int unsigned NOT NULL default 0,
        ADD COLUMN ar_log_timestamp binary(14) NOT NULL default '',
        ADD COLUMN ar_log_user int unsigned NOT NULL default 0,
        ADD COLUMN ar_log_user_text varchar(255) binary NOT NULL,
        ADD COLUMN ar_log_comment varchar(255) NOT NULL default '',
        ADD PRIMARY KEY ar_id (ar_id);