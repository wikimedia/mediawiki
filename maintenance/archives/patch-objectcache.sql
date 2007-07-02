-- For a few generic cache operations if not using Memcached
CREATE TABLE /*$wgDBprefix*/objectcache (
  keyname varbinary(255) binary not null default '',
  value mediumblob,
  exptime datetime,
  unique key (keyname),
  key (exptime)

) /*$wgDBTableOptions*/;
