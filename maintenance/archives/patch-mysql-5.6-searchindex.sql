-- This only supports mysql 5.6+
--
-- When using the default MySQL search backend, page titles
-- and text are munged to strip markup, do Unicode case folding,
-- and prepare the result for MySQL's fulltext index.
--
-- This table must be InnoDB;
--
CREATE TABLE /*_*/searchindex (
  -- Key to page_id
  si_page int unsigned NOT NULL,

  -- Munged version of title
  si_title varchar(255) NOT NULL default '',

  -- Munged version of body text
  si_text mediumtext NOT NULL,
  FULLTEXT KEY si_title (si_title, si_text)
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/si_page ON /*_*/searchindex (si_page);
