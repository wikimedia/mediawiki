--
-- Add ctd_tag_id to change_tag table to normalize it
--
ALTER TABLE /*_*/change_tag
  ADD COLUMN ct_tag_id int unsigned NULL;

CREATE INDEX /*i*/change_tag_tag_id_id ON /*_*/change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);
