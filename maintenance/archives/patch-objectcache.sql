-- For a few generic cache operations if not using Memcached
CREATE TABLE objectcache (
  keyname char(255) binary not null default '',
  value mediumblob,
  exptime datetime,
  unique key (keyname),
  key (exptime)
);
