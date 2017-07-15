ALTER TABLE /*_*/site_stats
	DROP KEY /*i*/ss_row_id,
	ADD PRIMARY KEY ( `/*i*/ss_row_id` );
