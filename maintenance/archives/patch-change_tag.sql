-- A table to track tags for revisions, logs and recent changes.
-- Andrew Garrett, 2009-01
CREATE TABLE /*_*/change_tag (
	ct_rc_id int NULL,
	ct_log_id int NULL,
	ct_rev_id int NULL,
	ct_tag varchar(255) NOT NULL,
	ct_params BLOB NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag ON /*_*/change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag ON /*_*/change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag ON /*_*/change_tag (ct_rev_id,ct_tag);
-- Covering index, so we can pull all the info only out of the index.
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);
