CREATE TABLE /*_*/tag (
    tag_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tag_name varchar(255) NOT NULL,
    tag_count bigint unsigned NOT NULL default 0,
    tag_timestamp varbinary(14) NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tag_name ON /*_*/tag (tag_name);
CREATE INDEX /*i*/tag_count ON /*_*/tag (tag_count);
