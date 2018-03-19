--
-- Adds a default value to the rev_text_id field in the revision table.
-- This is to allow the Multi Content Revisions migration to happen where
-- rows will have to be added to the revision table with no rev_text_id.
--
-- 2018-03-12
--

BEGIN TRANSACTION;

DROP TABLE IF EXISTS /*_*/revision_tmp;

CREATE TABLE /*_*/revision_tmp (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rev_page int unsigned NOT NULL,
  rev_text_id int unsigned NOT NULL default 0,
  rev_comment varbinary(767) NOT NULL default '',
  rev_user int unsigned NOT NULL default 0,
  rev_user_text varchar(255) binary NOT NULL default '',
  rev_timestamp binary(14) NOT NULL default '',
  rev_minor_edit tinyint unsigned NOT NULL default 0,
  rev_deleted tinyint unsigned NOT NULL default 0,
  rev_len int unsigned,
  rev_parent_id int unsigned default NULL,
  rev_sha1 varbinary(32) NOT NULL default '',
  rev_content_model varbinary(32) DEFAULT NULL,
  rev_content_format varbinary(64) DEFAULT NULL

) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;

INSERT OR IGNORE INTO /*_*/revision_tmp (
    rev_id, rev_page, rev_text_id, rev_comment, rev_user, rev_user_text,
    rev_timestamp, rev_minor_edit, rev_deleted, rev_len, rev_parent_id,
    rev_sha1, rev_content_model, rev_content_format
    )
    SELECT
    rev_id, rev_page, rev_text_id, rev_comment, rev_user, rev_user_text,
    rev_timestamp, rev_minor_edit, rev_deleted, rev_len, rev_parent_id,
    rev_sha1, rev_content_model, rev_content_format
    FROM /*_*/revision;

DROP TABLE /*_*/revision;

ALTER TABLE /*_*/revision_tmp RENAME TO /*_*/revision;

CREATE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision (rev_page,rev_timestamp);
CREATE INDEX /*i*/user_timestamp ON /*_*/revision (rev_user,rev_timestamp);
CREATE INDEX /*i*/usertext_timestamp ON /*_*/revision (rev_user_text,rev_timestamp);
CREATE INDEX /*i*/page_user_timestamp ON /*_*/revision (rev_page,rev_user,rev_timestamp);

COMMIT;
