-- T193874: Add new indexes to change_tag table using ct_tag_id instead of ct_tag

define mw_prefix='{$wgDBprefix}';

CREATE UNIQUE INDEX &mw_prefix.change_tag_u04 ON &mw_prefix.change_tag (ct_rc_id,ct_tag_id);
CREATE UNIQUE INDEX &mw_prefix.change_tag_u05 ON &mw_prefix.change_tag (ct_log_id,ct_tag_id);
CREATE UNIQUE INDEX &mw_prefix.change_tag_u06 ON &mw_prefix.change_tag (ct_rev_id,ct_tag_id);

CREATE INDEX &mw_prefix.change_tag_i03 ON &mw_prefix.change_tag (ct_rc_id,ct_tag);
CREATE INDEX &mw_prefix.change_tag_i04 ON &mw_prefix.change_tag (ct_log_id,ct_tag);
CREATE INDEX &mw_prefix.change_tag_i05 ON &mw_prefix.change_tag (ct_rev_id,ct_tag);

DROP INDEX &mw_prefix.change_tag_u01;
DROP INDEX &mw_prefix.change_tag_u02;
DROP INDEX &mw_prefix.change_tag_u03;

ALTER TABLE &mw_prefix.change_tag
	MODIFY ct_tag DEFAULT '///invalid///';