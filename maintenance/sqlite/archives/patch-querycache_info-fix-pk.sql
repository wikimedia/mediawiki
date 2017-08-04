CREATE TABLE /*_*/querycache_info_tmp (
  -- Special page name
  -- Corresponds to a qc_type value
  qci_type varbinary(32) NOT NULL default '' PRIMARY KEY,

  -- Timestamp of last update
  qci_timestamp binary(14) NOT NULL default '19700101000000'
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/querycache_info_tmp
	SELECT * FROM /*_*/querycache_info;

DROP TABLE /*_*/querycache_info;

ALTER TABLE /*_*/querycache_info_tmp RENAME TO /*_*/querycache_info;