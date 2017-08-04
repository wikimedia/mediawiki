ALTER TABLE /*_*/iwlinks DROP KEY /*i*/iwl_from, ADD PRIMARY KEY (iwl_from,iwl_prefix,iwl_title);
