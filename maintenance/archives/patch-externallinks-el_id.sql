--
-- patch-extenallinks-el_id.sql
--
-- T17441. Add externallinks.el_id.

ALTER TABLE /*$wgDBprefix*/externallinks
    ADD COLUMN el_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (el_id);
