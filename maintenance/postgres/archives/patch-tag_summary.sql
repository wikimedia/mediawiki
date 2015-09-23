CREATE TABLE tag_summary (
  ts_rc_id   INTEGER      NULL,
  ts_log_id  INTEGER      NULL,
  ts_rev_id  INTEGER      NULL,
  ts_tags    TEXT     NOT NULL
);
CREATE UNIQUE INDEX tag_summary_rc_id ON tag_summary(ts_rc_id);
CREATE UNIQUE INDEX tag_summary_log_id ON tag_summary(ts_log_id);
CREATE UNIQUE INDEX tag_summary_rev_id ON tag_summary(ts_rev_id);
