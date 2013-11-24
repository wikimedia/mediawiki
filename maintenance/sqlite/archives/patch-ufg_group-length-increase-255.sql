 CREATE TABLE /*_*/user_former_groups_tmp (
  ufg_user int unsigned NOT NULL default 0,
  ufg_group varbinary(255) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_former_groups_tmp
	SELECT ufg_user, ufg_group
		FROM /*_*/user_former_groups;

DROP TABLE /*_*/user_former_groups;

ALTER TABLE /*_*/user_former_groups_tmp RENAME TO /*_*/user_former_groups;

CREATE UNIQUE INDEX /*i*/ufg_user_group ON /*_*/user_former_groups (ufg_user,ufg_group);

