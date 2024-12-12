CREATE TABLE /*_*/protected_titles_tmp (
  pt_namespace INTEGER NOT NULL,
  pt_title BLOB NOT NULL,
  pt_user INTEGER UNSIGNED NOT NULL,
  pt_reason_id BIGINT UNSIGNED NOT NULL,
  pt_timestamp BLOB NOT NULL,
  pt_expiry BLOB NOT NULL,
  pt_create_perm BLOB NOT NULL,
  PRIMARY KEY(pt_namespace, pt_title)
);


INSERT INTO /*_*/protected_titles_tmp
	SELECT pt_namespace, pt_title, pt_user, pt_reason_id, pt_timestamp, pt_expiry, pt_create_perm
		FROM /*_*/protected_titles;
DROP TABLE /*_*/protected_titles;
ALTER TABLE /*_*/protected_titles_tmp RENAME TO /*_*/protected_titles;

CREATE INDEX pt_timestamp ON /*_*/protected_titles (pt_timestamp);
