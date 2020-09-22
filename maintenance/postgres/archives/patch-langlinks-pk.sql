DROP INDEX langlinks_unique;
ALTER TABLE langlinks
 ADD PRIMARY KEY (ll_from,ll_lang);
