-- Searchindex table definition for cases when no full-text search SQLite module is present
-- (currently, only FTS3 is supported).
-- Use it if you are moving your database from environment with FTS support
-- to environment without it.

DROP TABLE IF EXISTS /*_*/searchindex;

-- These are pieces of FTS3-enabled searchindex
DROP TABLE IF EXISTS /*_*/searchindex_content;
DROP TABLE IF EXISTS /*_*/searchindex_segdir;
DROP TABLE IF EXISTS /*_*/searchindex_segments;

CREATE TABLE /*_*/searchindex (
  si_page INTEGER UNSIGNED NOT NULL,
  si_title CLOB NOT NULL,
  si_text CLOB NOT NULL,
  PRIMARY KEY(si_page)
);

CREATE INDEX si_title ON /*_*/searchindex (si_title);
CREATE INDEX si_text ON /*_*/searchindex (si_text);

DELETE FROM /*_*/updatelog WHERE ul_key='fts3';
