-- T161671, T184615, T215466: Drop old revision user, comment, and content fields, and
-- add the replacement actor and comment_id fields.

BEGIN;

DROP TABLE IF EXISTS /*_*/revision_tmp;
CREATE TABLE /*_*/revision_tmp (
  rev_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rev_page int unsigned NOT NULL,
  rev_comment_id bigint unsigned NOT NULL default 0,
  rev_actor bigint unsigned NOT NULL default 0,
  rev_timestamp binary(14) NOT NULL default '',
  rev_minor_edit tinyint unsigned NOT NULL default 0,
  rev_deleted tinyint unsigned NOT NULL default 0,
  rev_len int unsigned,
  rev_parent_id int unsigned default NULL,
  rev_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;

INSERT OR IGNORE INTO revision_tmp (
	rev_id, rev_page, rev_timestamp, rev_minor_edit, rev_deleted, rev_len,
	rev_parent_id, rev_sha1)
 SELECT
	rev_id, rev_page, rev_timestamp, rev_minor_edit, rev_deleted, rev_len,
	rev_parent_id, rev_sha1
  FROM /*_*/revision;

DROP TABLE /*_*/revision;
ALTER TABLE /*_*/revision_tmp RENAME TO /*_*/revision;
CREATE INDEX /*i*/rev_page_id ON /*_*/revision (rev_page, rev_id);
CREATE INDEX /*i*/rev_timestamp ON /*_*/revision (rev_timestamp);
CREATE INDEX /*i*/page_timestamp ON /*_*/revision (rev_page,rev_timestamp);
CREATE INDEX /*i*/rev_actor_timestamp ON /*_*/revision (rev_actor,rev_timestamp,rev_id);
CREATE INDEX /*i*/rev_page_actor_timestamp ON /*_*/revision (rev_page,rev_actor,rev_timestamp);

COMMIT;
