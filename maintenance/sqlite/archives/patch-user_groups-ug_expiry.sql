DROP TABLE IF EXISTS /*_*/user_groups_tmp;

CREATE TABLE /*$wgDBprefix*/user_groups_tmp (
  ug_user int unsigned NOT NULL default 0,
  ug_group varbinary(255) NOT NULL default '',
  ug_expiry varbinary(14) NULL default NULL,
  PRIMARY KEY (ug_user, ug_group)
);

INSERT OR IGNORE INTO /*_*/user_groups_tmp (
    ug_user, ug_group )
    SELECT
    ug_user, ug_group
    FROM /*_*/user_groups;

DROP TABLE /*_*/user_groups;

ALTER TABLE /*_*/user_groups_tmp RENAME TO /*_*/user_groups;

CREATE INDEX /*i*/ug_group ON /*_*/user_groups (ug_group);
CREATE INDEX /*i*/ug_expiry ON /*_*/user_groups (ug_expiry);
