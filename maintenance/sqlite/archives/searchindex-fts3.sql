-- Patch that introduces fulltext search capabilities to SQLite schema
-- Requires that SQLite must be compiled with FTS3 module (comes with core amalgamation).
-- See http://sqlite.org/fts3.html for details of syntax.
-- Will fail if FTS3 is not present, 
DROP TABLE IF EXISTS /*_*/searchindex;
CREATE VIRTUAL TABLE /*_*/searchindex USING FTS3(
  -- Key to page_id
  -- Disabled, instead we use the built-in rowid column
  -- si_page INTEGER NOT NULL,

  -- Munged version of title
  si_title,
  
  -- Munged version of body text
  si_text
);

INSERT INTO /*_*/updatelog VALUES ('fts3');