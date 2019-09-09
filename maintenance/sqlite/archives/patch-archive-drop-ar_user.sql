--
-- patch-archive-drop-ar_user.sql
--
-- T188327. Drop old xx_user and xx_user_text fields, and defaults from xx_actor fields.

BEGIN;

DROP TABLE IF EXISTS /*_*/archive_tmp;
CREATE TABLE /*_*/archive_tmp (
  ar_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ar_namespace int NOT NULL default 0,
  ar_title varchar(255) binary NOT NULL default '',
  ar_comment_id bigint unsigned NOT NULL,
  ar_actor bigint unsigned NOT NULL,
  ar_timestamp binary(14) NOT NULL default '',
  ar_minor_edit tinyint NOT NULL default 0,
  ar_rev_id int unsigned NOT NULL,
  ar_text_id int unsigned NOT NULL DEFAULT 0,
  ar_deleted tinyint unsigned NOT NULL default 0,
  ar_len int unsigned,
  ar_page_id int unsigned,
  ar_parent_id int unsigned default NULL,
  ar_sha1 varbinary(32) NOT NULL default '',
  ar_content_model varbinary(32) DEFAULT NULL,
  ar_content_format varbinary(64) DEFAULT NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/archive_tmp (
	ar_id, ar_namespace, ar_title, ar_comment_id, ar_actor,
	ar_timestamp, ar_minor_edit, ar_rev_id, ar_text_id, ar_deleted,
	ar_len, ar_page_id, ar_parent_id, ar_sha1, ar_content_model, ar_content_format
  ) SELECT
	ar_id, ar_namespace, ar_title, ar_comment_id, ar_actor,
	ar_timestamp, ar_minor_edit, ar_rev_id, ar_text_id, ar_deleted,
	ar_len, ar_page_id, ar_parent_id, ar_sha1, ar_content_model, ar_content_format
  FROM /*_*/archive;

DROP TABLE /*_*/archive;
ALTER TABLE /*_*/archive_tmp RENAME TO /*_*/archive;
CREATE INDEX /*i*/name_title_timestamp ON /*_*/archive (ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);
CREATE UNIQUE INDEX /*i*/ar_revid_uniq ON /*_*/archive (ar_rev_id);

COMMIT;
