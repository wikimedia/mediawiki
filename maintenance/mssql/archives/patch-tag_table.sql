-- Table defining tag names for IDs. Also stores hit counts to avoid expensive queries on change_tag
CREATE TABLE /*_*/tag (
  tag_id int NOT NULL PRIMARY KEY IDENTITY,
  tag_name nvarchar(255) NOT NULL,
  tag_count int NOT NULL CONSTRAINT DF_tag_count DEFAULT 0,
  tag_timestamp nvarchar(14) NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tag_name ON /*_*/tag (tag_name);
CREATE INDEX /*i*/tag_count ON /*_*/tag (tag_count);
