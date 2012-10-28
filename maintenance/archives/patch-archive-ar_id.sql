--
-- patch-archive-ar_id.sql
--
-- Bug 39675. Add archive.ar_id.

ALTER TABLE /*$wgDBprefix*/archive
    ADD COLUMN ar_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (ar_id);
