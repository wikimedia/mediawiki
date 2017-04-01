--
-- patch-querycache-primary-key.sql
--
-- Bug T146571. Add querycache primary key

DELETE FROM /*$wgDBprefix*/querycache;

ALTER TABLE /*$wgDBprefix*/querycache DROP KEY /*i*/qc_type, ADD PRIMARY KEY(qc_type, qc_value);
