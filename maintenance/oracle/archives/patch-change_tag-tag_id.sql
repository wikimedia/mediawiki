--
-- Add ctd_tag_id to change_tag table to normalize it
--
ALTER TABLE &mw_prefix.change_tag ADD ( ct_tag_id NUMBER DEFAULT NULL );

CREATE INDEX &mw_prefix.change_tag_i02 ON &mw_prefix.change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);
