CREATE TABLE /*_*/user_former_groups_tmp (
  -- Key to user_id
  ufg_user int unsigned NOT NULL default 0,
  ufg_group varbinary(255) NOT NULL default '',
  PRIMARY KEY (ufg_user,ufg_group)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_former_groups_tmp(ufg_user, ufg_group)
	SELECT ufg_user, ufg_group FROM /*_*/user_former_groups;

DROP TABLE /*_*/user_former_groups;

ALTER TABLE /*_*/user_former_groups_tmp RENAME TO /*_*/user_former_groups;
