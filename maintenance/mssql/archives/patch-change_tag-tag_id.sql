--
-- Add ctd_tag_id to change_tag table to normalize it
--
ALTER TABLE /*_*/change_tag
  ADD COLUMN ct_tag_id int NULL REFERENCES /*_*/change_tag_def(ctd_id);

CREATE INDEX /*i*/change_tag_rc_tag_id ON /*_*/change_tag (ct_rc_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_log_tag_id ON /*_*/change_tag (ct_log_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_rev_tag_id ON /*_*/change_tag (ct_rev_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_tag_id_id ON /*_*/change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);
