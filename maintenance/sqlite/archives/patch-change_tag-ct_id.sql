DROP TABLE IF EXISTS /*_*/change_tag_tmp;

CREATE TABLE /*$wgDBprefix*/change_tag_tmp (
  ct_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ct_rc_id int NULL,
  ct_log_id int NULL,
  ct_rev_id int NULL,
  ct_tag varchar(255) NOT NULL,
  ct_params blob NULL
);

INSERT OR IGNORE INTO /*_*/change_tag_tmp (
    ct_rc_id, ct_log_id, ct_rev_id, ct_tag, ct_params )
    SELECT
    ct_rc_id, ct_log_id, ct_rev_id, ct_tag, ct_params
    FROM /*_*/change_tag;

DROP TABLE /*_*/change_tag;

ALTER TABLE /*_*/change_tag_tmp RENAME TO /*_*/change_tag;

CREATE UNIQUE INDEX /*i*/change_tag_rc_tag ON /*_*/change_tag (ct_rc_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag ON /*_*/change_tag (ct_log_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag ON /*_*/change_tag (ct_rev_id,ct_tag);
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);
