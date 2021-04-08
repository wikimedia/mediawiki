ALTER TABLE objectcache
 DROP CONSTRAINT objectcache_keyname_key,
 ADD PRIMARY KEY (keyname),
 ADD DEFAULT '';
