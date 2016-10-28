--
-- patch-oldimage-oi_id.sql
--
-- Bug T146568. Add oldimage.oi_id.

ALTER TABLE /*$wgDBprefix*/oldimage
    ADD COLUMN oi_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (oi_id);
