--
-- Track links that do exist
-- l_from and l_to key to cur_id
--
DROP TABLE IF EXISTS links;
CREATE TABLE links (
  l_from int(8) unsigned NOT NULL default '0',
  l_to int(8) unsigned NOT NULL default '0',
  UNIQUE KEY l_from(l_from,l_to),
  KEY (l_to)
);

--
-- Track links to pages that don't yet exist.
-- bl_from keys to cur_id
-- bl_to is a text link (namespace:title)
--
DROP TABLE IF EXISTS brokenlinks;
CREATE TABLE brokenlinks (
  bl_from int(8) unsigned NOT NULL default '0',
  bl_to varchar(255) binary NOT NULL default '',
  UNIQUE KEY bl_from(bl_from,bl_to),
  KEY (bl_to)
);

--
-- Track links to images *used inline*
-- il_from keys to cur_id, il_to keys to image_name.
-- We don't distinguish live from broken links.
--
DROP TABLE IF EXISTS imagelinks;
CREATE TABLE imagelinks (
  il_from int(8) unsigned NOT NULL default '0',
  il_to varchar(255) binary NOT NULL default '',
  UNIQUE KEY il_from(il_from,il_to),
  KEY (il_to)
);

--
-- Stores (possibly gzipped) serialized objects with
-- cache arrays to reduce database load slurping up
-- from links and brokenlinks.
--
DROP TABLE IF EXISTS linkscc;
CREATE TABLE linkscc (
  lcc_pageid INT UNSIGNED NOT NULL UNIQUE KEY,
  lcc_cacheobj MEDIUMBLOB NOT NULL
);
