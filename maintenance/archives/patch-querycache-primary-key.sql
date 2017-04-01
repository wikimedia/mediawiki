--
-- patch-querycache-primary-key.sql
--
-- Bug T146571. Add querycache primary key

DELETE FROM /*$wgDBprefix*/querycache;

DROP INDEX /*i*/qc_type ON /*$wgDBprefix*/querycache;

ALTER TABLE /*$wgDBprefix*/querycache ADD PRIMARY KEY(qc_type, qc_value);
