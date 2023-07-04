DROP INDEX IF EXISTS langlinks_unique;
ALTER TABLE langlinks
 ADD PRIMARY KEY (ll_from,ll_lang);
