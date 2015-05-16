--
-- This table contains change tags hitcounts extracted from the change_tag table.
--
CREATE TABLE /*_*/change_tag_statistics (
  cts_tag VARCHAR2(255) NOT NULL PRIMARY KEY,
  cts_count NUMBER DEFAULT 0,
  cts_timestamp TIMESTAMP(6) WITH TIME ZONE
) /*$wgDBTableOptions*/;
