--
-- This table contains change tags hitcounts extracted from the change_tag table.
--
CREATE TABLE /*_*/change_tag_statistics (
  cts_tag NVARCHAR(255) NOT NULL PRIMARY KEY,
  cts_count INT DEFAULT 0,
  cts_timestamp VARCHAR(14) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/change_tag_statistics_count ON /*_*/change_tag_statistics (cts_count);
