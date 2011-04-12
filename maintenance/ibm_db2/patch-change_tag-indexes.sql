CREATE UNIQUE INDEX change_tag_rc_tag ON change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX change_tag_log_tag ON change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX change_tag_rev_tag ON change_tag (ct_rev_id,ct_tag);
-- Covering index, so we can pull all the info only out of the index.
CREATE INDEX change_tag_tag_id ON change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);
