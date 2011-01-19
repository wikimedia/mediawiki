-- A table to track tags for revisions, logs and recent changes.
-- Andrew Garrett, 2009-01
CREATE TABLE /*_*/change_tag (
	ct_rc_id int NULL,
	ct_log_id int NULL,
	ct_rev_id int NULL,
	ct_tag varbinary(255) NOT NULL,
	ct_params BLOB NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag ON /*_*/change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag ON /*_*/change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag ON /*_*/change_tag (ct_rev_id,ct_tag);
-- Covering index, so we can pull all the info only out of the index.
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);

-- Rollup table to pull a LIST of tags simply without ugly GROUP_CONCAT that only works on MySQL 4.1+
CREATE TABLE /*_*/tag_summary (
	ts_rc_id int NULL,
	ts_log_id int NULL,
	ts_rev_id int NULL,
	ts_tags BLOB NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/tag_summary_rc_id ON /*_*/tag_summary (ts_rc_id);
CREATE UNIQUE INDEX /*i*/tag_summary_log_id ON /*_*/tag_summary (ts_log_id);
CREATE UNIQUE INDEX /*i*/tag_summary_rev_id ON /*_*/tag_summary (ts_rev_id);


CREATE TABLE /*_*/valid_tag (
	vt_tag varbinary(255) NOT NULL PRIMARY KEY
) /*$wgDBTableOptions*/;
