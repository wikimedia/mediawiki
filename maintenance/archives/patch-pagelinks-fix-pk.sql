ALTER TABLE /*_*/pagelinks DROP INDEX /*i*/pl_from, ADD PRIMARY KEY (pl_from,pl_namespace,pl_title);
