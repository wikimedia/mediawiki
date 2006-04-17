CREATE TABLE /*$wgDBprefix*/querycache_info (

	-- Special page name
	-- Corresponds to a qc_type value
	qci_type varchar(32) NOT NULL default '',

	-- Timestamp of last update
	qci_timestamp char(14) NOT NULL default '19700101000000',

	UNIQUE KEY ( qci_type )

) TYPE=InnoDB;
