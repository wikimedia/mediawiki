--
-- patch-querycache-primary-key.sql
--
-- Bug T146571. Add querycache primary key
DROP TABLE IF EXISTS /*_*/querycache;

CREATE TABLE /*_*/querycache (
  -- A key name, generally the base name of of the special page.
  qc_type varbinary(32) NOT NULL,

  -- Some sort of stored value. Sizes, counts...
  qc_value int unsigned NOT NULL default 0,

  -- Target namespace+title
  qc_namespace int NOT NULL default 0,
  qc_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (qc_type, qc_value)
) /*$wgDBTableOptions*/;
s