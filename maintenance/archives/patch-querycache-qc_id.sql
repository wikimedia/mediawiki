--
-- patch-querycache-qc_id.sql
--
-- Bug T146571. Add querycache.qc_id.

ALTER TABLE /*$wgDBprefix*/querycache
    ADD COLUMN qc_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (qc_id);
