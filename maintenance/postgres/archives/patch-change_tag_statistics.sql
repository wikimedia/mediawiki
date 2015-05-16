--
-- This table contains change tags hitcounts extracted from the change_tag table.
--
CREATE TABLE change_tag_statistics (
  cts_tag TEXT NOT NULL PRIMARY KEY,
  cts_count INTEGER NOT NULL DEFAULT 0,
  cts_timestamp TIMESTAMPTZ
);

CREATE INDEX change_tag_statistics_count ON change_tag_statistics(cts_count);
