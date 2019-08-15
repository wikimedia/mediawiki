--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table.

CREATE TABLE /*_*/comment (
  comment_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  comment_hash INT NOT NULL,
  comment_text BLOB NOT NULL,
  comment_data BLOB
) /*$wgDBTableOptions*/;
CREATE INDEX /*i*/comment_hash ON /*_*/comment (comment_hash);
