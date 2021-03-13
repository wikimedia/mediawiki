CREATE TABLE archive_tmp (
  ar_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ar_namespace INTEGER DEFAULT 0 NOT NULL,
  ar_title BLOB DEFAULT '' NOT NULL, ar_comment_id BIGINT UNSIGNED NOT NULL,
  ar_actor BIGINT UNSIGNED NOT NULL,
  ar_timestamp BLOB NOT NULL, ar_minor_edit SMALLINT DEFAULT 0 NOT NULL,
  ar_rev_id INTEGER UNSIGNED NOT NULL,
  ar_deleted SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
  ar_len INTEGER UNSIGNED DEFAULT NULL,
  ar_page_id INTEGER UNSIGNED DEFAULT NULL,
  ar_parent_id INTEGER UNSIGNED DEFAULT NULL,
  ar_sha1 BLOB DEFAULT '' NOT NULL
);
INSERT INTO /*_*/archive_tmp (
  ar_id, ar_namespace, ar_title, ar_comment_id, ar_actor, ar_timestamp, ar_minor_edit, ar_rev_id,
  ar_deleted, ar_len, ar_page_id, ar_parent_id, ar_sha1)
SELECT ar_id, ar_namespace, ar_title, ar_comment_id, ar_actor, ar_timestamp, ar_minor_edit, ar_rev_id, ar_deleted,
       ar_len, ar_page_id, ar_parent_id, ar_sha1
FROM /*_*/archive;
DROP TABLE /*_*/archive;
ALTER TABLE /*_*/archive_tmp RENAME TO /*_*/archive;

CREATE INDEX ar_name_title_timestamp ON /*_*/archive (
  ar_namespace, ar_title, ar_timestamp
);

CREATE INDEX ar_actor_timestamp ON /*_*/archive (ar_actor, ar_timestamp);

CREATE UNIQUE INDEX ar_revid_uniq ON /*_*/archive (ar_rev_id);
