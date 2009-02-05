
CREATE TABLE change_tag (
	ct_rc_id INTEGER NULL,
	ct_log_id INTEGER NULL,
	ct_rev_id INTEGER NULL,
	ct_tag TEXT NOT NULL,
	ct_params TEXT NULL
);
CREATE UNIQUE INDEX change_tag_rc_tag ON change_tag(ct_rc_id,ct_tag);
CREATE UNIQUE INDEX change_tag_log_tag ON change_tag(ct_log_id,ct_tag);
CREATE UNIQUE INDEX change_tag_rev_tag ON change_tag(ct_rev_id,ct_tag);
CREATE INDEX change_tag_tag_id ON change_tag(ct_tag,ct_rc_id,ct_rev_id,ct_log_id);


CREATE TABLE tag_summary (
	ts_rc_id INTEGER NULL,
	ts_log_id INTEGER NULL,
	ts_rev_id INTEGER NULL,
	ts_tags TEXT NOT NULL
);
CREATE UNIQUE INDEX tag_summary_rc_id ON tag_summary(ts_rc_id);
CREATE UNIQUE INDEX tag_summary_log_id ON tag_summary(ts_log_id);
CREATE UNIQUE INDEX tag_summary_rev_id ON tag_summary(ts_rev_id);


CREATE TABLE valid_tag (
	vt_tag TEXT NOT NULL PRIMARY KEY
);
