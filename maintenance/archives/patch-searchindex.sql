-- Break fulltext search index out to separate table from cur
-- This is being done mainly to allow us to use InnoDB tables
-- for the main db while keeping the MyISAM fulltext index for
-- search.

-- 2002-12-16, 2003-01-25 Brion VIBBER <brion@pobox.com>

-- Creating searchindex table...
DROP TABLE IF EXISTS searchindex;
CREATE TABLE searchindex (
  si_page int(8) unsigned NOT NULL,
  si_title varchar(255) NOT NULL default '',
  si_text mediumtext NOT NULL default '',
  UNIQUE KEY (si_page)
) TYPE=MyISAM PACK_KEYS=1;

-- Copying data into new table...
INSERT INTO searchindex
  (si_page,si_title,si_text)
  SELECT
    cur_id,cur_ind_title,cur_ind_text
    FROM cur;


-- Creating fulltext index...
ALTER TABLE searchindex
  ADD FULLTEXT si_title (si_title),
  ADD FULLTEXT si_text (si_text);

-- Dropping index columns from cur table.
ALTER TABLE cur
  DROP COLUMN cur_ind_title,
  DROP COLUMN cur_ind_text;
