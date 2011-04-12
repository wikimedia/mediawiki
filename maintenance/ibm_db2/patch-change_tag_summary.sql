-- Rollup table to pull a LIST of tags simply
CREATE TABLE tag_summary (
  ts_rc_id INTEGER,
  ts_log_id INTEGER,
  ts_rev_id INTEGER,
  ts_tags CLOB(64K) INLINE LENGTH 4096 NOT NULL
);
