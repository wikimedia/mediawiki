ALTER TABLE /*_*/templatelinks DROP INDEX /*i*/tl_from, ADD PRIMARY KEY (tl_from,tl_namespace,tl_title);
