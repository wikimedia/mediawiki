--
-- This table contains change tags hitcounts extracted from the change_tag table.
--
CREATE TABLE &mw_prefix.change_tag_statistics (
  cts_tag VARCHAR2(255) NOT NULL,
  cts_count NUMBER DEFAULT 0,
  cts_timestamp TIMESTAMP(6) WITH TIME ZONE
) /*$wgDBTableOptions*/;

ALTER TABLE &mw_prefix.change_tag_statistics ADD CONSTRAINT &mw_prefix.change_tag_statistics_pk PRIMARY KEY (cts_tag);
CREATE INDEX &mw_prefix.change_tag_statistics_i01 ON &mw_prefix.change_tag_statistics (cts_count);
