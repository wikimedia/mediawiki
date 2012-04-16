CREATE TABLE /*_*/user_former_groups_tmp (
  ug_user int unsigned NOT NULL default 0,
  ug_group varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_former_groups_tmp
	SELECT ug_user, ug_group
		FROM /*_*/user_groups;

DROP TABLE /*_*/user_former_groups;

ALTER TABLE /*_*/user_former_groups_tmp RENAME TO /*_*/user_former_groups;

CREATE UNIQUE INDEX /*i*/ufg_user_group ON /*_*/user_former_groups (ufg_user,ufg_group);

