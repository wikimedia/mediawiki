-- For auto-expiring blocks --

ALTER TABLE ipblocks
	ADD ipb_auto tinyint(1) NOT NULL default '0',
	ADD ipb_id int(8) NOT NULL auto_increment,
	ADD PRIMARY KEY (ipb_id);
