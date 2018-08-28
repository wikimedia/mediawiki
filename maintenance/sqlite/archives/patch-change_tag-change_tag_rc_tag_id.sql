-- T193874: Add new indexes to change_tag table using ct_tag_id instead of ct_tag
BEGIN TRANSACTION;

DROP TABLE IF EXISTS /*_*/change_tag_tmp;

CREATE TABLE /*_*/change_tag_tmp (
  ct_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ct_rc_id int NULL,
  ct_log_id int unsigned NULL,
  ct_rev_id int unsigned NULL,
  ct_tag varchar(255) NOT NULL default '',
  ct_params blob NULL,
  ct_tag_id int unsigned NULL
) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=1024;

INSERT OR IGNORE INTO /*_*/change_tag_tmp (ct_id, ct_rc_id, ct_log_id, ct_rev_id, ct_tag, ct_params, ct_tag_id)
    SELECT
    ct_id, ct_rc_id, ct_log_id, ct_rev_id, ct_tag, ct_params, ct_tag_id
    FROM /*_*/change_tag;

DROP TABLE /*_*/change_tag;

ALTER TABLE /*_*/change_tag_tmp RENAME TO /*_*/change_tag;

CREATE INDEX /*i*/change_tag_rc_tag_nonuniq ON /*_*/change_tag (ct_rc_id,ct_tag);
CREATE INDEX /*i*/change_tag_log_tag_nonuniq ON /*_*/change_tag (ct_log_id,ct_tag);
CREATE INDEX /*i*/change_tag_rev_tag_nonuniq ON /*_*/change_tag (ct_rev_id,ct_tag);
CREATE UNIQUE INDEX /*i*/change_tag_rc_tag_id ON /*_*/change_tag (ct_rc_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_log_tag_id ON /*_*/change_tag (ct_log_id,ct_tag_id);
CREATE UNIQUE INDEX /*i*/change_tag_rev_tag_id ON /*_*/change_tag (ct_rev_id,ct_tag_id);
CREATE INDEX /*i*/change_tag_tag_id ON /*_*/change_tag (ct_tag,ct_rc_id,ct_rev_id,ct_log_id);
CREATE INDEX /*i*/change_tag_tag_id_id ON /*_*/change_tag (ct_tag_id,ct_rc_id,ct_rev_id,ct_log_id);

COMMIT;