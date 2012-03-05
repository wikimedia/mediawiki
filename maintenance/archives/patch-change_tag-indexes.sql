--
-- Rename indexes on change_tag from implicit to explicit names
--

DROP INDEX ct_rc_id ON /*_*/change_tag;
DROP INDEX ct_log_id ON /*_*/change_tag;
DROP INDEX ct_rev_id ON /*_*/change_tag;
DROP INDEX ct_tag ON /*_*/change_tag;

DROP INDEX ts_rc_id ON /*_*/tag_summary;
DROP INDEX ts_log_id ON /*_*/tag_summary;
DROP INDEX ts_rev_id ON /*_*/tag_summary;

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag ON /*_*/change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag ON /*_*/change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag ON /*_*/change_tag (ct_rev_id,ct_tag);
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);

CREATE UNIQUE INDEX /*i*/tag_summary_rc_id ON /*_*/tag_summary (ts_rc_id);
CREATE UNIQUE INDEX /*i*/tag_summary_log_id ON /*_*/tag_summary (ts_log_id);
CREATE UNIQUE INDEX /*i*/tag_summary_rev_id ON /*_*/tag_summary (ts_rev_id);
