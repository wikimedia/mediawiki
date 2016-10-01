--
-- patch-querycachetwo-qcc_id.sql
--
-- Bug T146586. Add querycachetwo.qcc_id.

ALTER TABLE /*$wgDBprefix*/querycachetwo
    ADD COLUMN qcc_id int unsigned NOT NULL AUTO_INCREMENT FIRST,
    ADD PRIMARY KEY (qcc_id);
