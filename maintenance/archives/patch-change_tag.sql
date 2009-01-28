-- A table to track tags for revisions, logs and recent changes.
-- Andrew Garrett, 2009-01
CREATE TABLE /*_*/change_tag (
	ct_rc_id int NULL,
	ct_log_id int NULL,
	ct_rev_id int NULL,
	ct_tag varchar(255) NOT NULL,
	ct_params BLOB NULL,

	UNIQUE KEY (ct_rc_id,ct_tag),
	UNIQUE KEY (ct_log_id,ct_tag),
	UNIQUE KEY (ct_rev_id,ct_tag),
	KEY (ct_tag,ct_rc_id,ct_rev_id,ct_log_id) -- Covering index, so we can pull all the info only out of the index.
) /*$wgDBTableOptions*/;

-- Rollup table to pull a LIST of tags simply without ugly GROUP_CONCAT that only works on MySQL 4.1+
CREATE TABLE /*_*/tag_summary (
	ts_rc_id int NULL,
	ts_log_id int NULL,
	ts_rev_id int NULL,
	ts_tags BLOB NOT NULL,

	UNIQUE KEY (ts_rc_id),
	UNIQUE KEY (ts_log_id),
	UNIQUE KEY (ts_rev_id)
) /*$wgDBTableOptions*/;

CREATE TABLE /*_*/valid_tag (
	vt_tag varchar(255) NOT NULL,
	PRIMARY KEY (vt_tag)
) /*$wgDBTableOptions*/;