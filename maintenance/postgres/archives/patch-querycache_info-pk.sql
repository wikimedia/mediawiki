ALTER TABLE querycache_info
 DROP CONSTRAINT querycache_info_qci_type_key,
 ADD PRIMARY KEY (qci_type);
