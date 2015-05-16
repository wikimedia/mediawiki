--
-- This table contains change tags hitcounts extracted from the change_tag table;
-- as well as the timestamp of last addition.
--
CREATE TABLE /*_*/change_tag_statistics (
  cts_tag varchar(255) NOT NULL PRIMARY KEY,
  cts_count bigint unsigned NOT NULL default 0,
  cts_timestamp varbinary(14) NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/change_tag_statistics_count ON /*_*/change_tag_statistics (cts_count);
