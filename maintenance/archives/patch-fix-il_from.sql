-- Fix a bug from the 1.2 -> 1.3 upgrader by moving away the imagelinks table
-- and recreating it.
RENAME TABLE /*_*/imagelinks TO /*_*/imagelinks_old;
CREATE TABLE /*_*/imagelinks (
  il_from int unsigned NOT NULL default 0,
  il_to varchar(255) binary NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/il_from ON /*_*/imagelinks (il_from,il_to);
CREATE UNIQUE INDEX /*i*/il_to ON /*_*/imagelinks (il_to,il_from);

