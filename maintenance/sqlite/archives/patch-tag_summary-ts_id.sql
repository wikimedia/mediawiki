DROP TABLE IF EXISTS /*_*/tag_summary_tmp;

CREATE TABLE /*$wgDBprefix*/tag_summary_tmp (
  ts_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ts_rc_id int NULL,
  ts_log_id int NULL,
  ts_rev_id int NULL,
  ts_tags blob NOT NULL
);

INSERT OR IGNORE INTO /*_*/tag_summary_tmp (
    ts_rc_id, ts_log_id, ts_rev_id, ts_tags )
    SELECT
    ts_rc_id, ts_log_id, ts_rev_id, ts_tags
    FROM /*_*/tag_summary;

DROP TABLE /*_*/tag_summary;

ALTER TABLE /*_*/tag_summary_tmp RENAME TO /*_*/tag_summary;

CREATE UNIQUE INDEX /*i*/tag_summary_rc_id ON /*_*/tag_summary (ts_rc_id);
CREATE UNIQUE INDEX /*i*/tag_summary_log_id ON /*_*/tag_summary (ts_log_id);
CREATE UNIQUE INDEX /*i*/tag_summary_rev_id ON /*_*/tag_summary (ts_rev_id);
