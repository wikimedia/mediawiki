-- Rollup table to pull a LIST of tags simply without ugly GROUP_CONCAT that only works on MySQL 4.1+
-- Andrew Garrett, 2009-01
CREATE TABLE /*_*/tag_summary (
	ts_rc_id int NULL,
	ts_log_id int NULL,
	ts_rev_id int NULL,
	ts_tags BLOB NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tag_summary_rc_id ON /*_*/tag_summary (ts_rc_id);
CREATE UNIQUE INDEX /*i*/tag_summary_log_id ON /*_*/tag_summary (ts_log_id);
CREATE UNIQUE INDEX /*i*/tag_summary_rev_id ON /*_*/tag_summary (ts_rev_id);
