ALTER TABLE interwiki
 DROP CONSTRAINT interwiki_iw_prefix_key,
 ADD PRIMARY KEY (iw_prefix);