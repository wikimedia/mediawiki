--
-- This table contains change tags hitcounts extracted from the change_tag table.
--
CREATE TABLE /*_*/change_tag_statistics (
  cts_tag nvarchar(255) NOT NULL CONSTRAINT PK_change_tag_statistics PRIMARY KEY,
  cts_count int NOT NULL CONSTRAINT DF_cts_count DEFAULT 0,
  cts_timestamp nvarchar(14) NULL
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/change_tag_statistics_count ON /*_*/change_tag_statistics (cts_count);
