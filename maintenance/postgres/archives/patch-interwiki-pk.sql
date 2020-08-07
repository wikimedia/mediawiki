DROP CONSTRAINT interwiki_iw_prefix_key;
ALTER TABLE interwiki
 ADD PRIMARY KEY (iw_prefix);
