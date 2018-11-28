-- T185355

CREATE TABLE /*_*/change_tag_tmp (
  ct_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ct_rc_id int NULL,
  ct_log_id int unsigned NULL,
  ct_rev_id int unsigned NULL,
  ct_params blob NULL,
  ct_tag_id int unsigned NOT NULL
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/change_tag_tmp
	SELECT ct_id, ct_rc_id, ct_log_id, ct_rev_id, ct_params, ct_tag_id
		FROM /*_*/change_tag;

DROP TABLE /*_*/change_tag;

ALTER TABLE /*_*/change_tag_tmp RENAME TO /*_*/change_tag;

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag_id ON /*_*/change_tag (ct_rc_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag_id ON /*_*/change_tag (ct_log_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag_id ON /*_*/change_tag (ct_rev_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_tag_id_id ON /*_*/change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);
