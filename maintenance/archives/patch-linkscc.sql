--
-- linkscc table used to cache link lists in easier to digest form
-- November 2003
--
-- Update March 2004, changed lcc_title to binary to prevent data
-- corruption problem
--

DROP TABLE IF EXISTS linkscc;
CREATE TABLE linkscc (
  lcc_pageid INT UNSIGNED NOT NULL UNIQUE KEY,
  lcc_title VARCHAR(255) BINARY NOT NULL UNIQUE KEY,
  lcc_cacheobj MEDIUMBLOB NOT NULL
);
