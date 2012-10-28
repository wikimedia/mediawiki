--
-- patch-archive-ar_id.sql
--
-- Bug 39675. Add archive.ar_id.

ALTER TABLE /*$wgDBprefix*/archive
    ADD COLUMN FIRST ar_id int unsigned NOT NULL AUTO_INCREMENT,
    ADD PRIMARY KEY ar_id (ar_id);
