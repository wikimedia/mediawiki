-- A table to track tags for revisions, logs and recent changes.
CREATE TABLE change_tag (
  ct_rc_id INTEGER,
  ct_log_id INTEGER,
  ct_rev_id INTEGER,
  ct_tag varchar(255) NOT NULL,
  ct_params CLOB(64K) INLINE LENGTH 4096
);
