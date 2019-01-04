--
-- patch-protected_titles-drop-pt_reason.sql
--
-- T166732. Drop old xx_comment fields, and defaults from xx_comment_id fields.

BEGIN;

DROP TABLE IF EXISTS /*_*/protected_titles_tmp;
CREATE TABLE /*_*/protected_titles_tmp (
  pt_namespace int NOT NULL,
  pt_title varchar(255) binary NOT NULL,
  pt_user int unsigned NOT NULL,
  pt_reason_id bigint unsigned NOT NULL,
  pt_timestamp binary(14) NOT NULL,
  pt_expiry varbinary(14) NOT NULL default '',
  pt_create_perm varbinary(60) NOT NULL,
  PRIMARY KEY (pt_namespace,pt_title)
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/protected_titles_tmp (
	pt_namespace, pt_title, pt_user, pt_reason_id, pt_timestamp, pt_expiry, pt_create_perm
  ) SELECT
	pt_namespace, pt_title, pt_user, pt_reason_id, pt_timestamp, pt_expiry, pt_create_perm
  FROM /*_*/protected_titles;

DROP TABLE /*_*/protected_titles;
ALTER TABLE /*_*/protected_titles_tmp RENAME TO /*_*/protected_titles;
CREATE INDEX /*i*/pt_timestamp ON /*_*/protected_titles (pt_timestamp);

COMMIT;
