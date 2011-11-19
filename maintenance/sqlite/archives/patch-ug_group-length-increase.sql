CREATE TABLE /*_*/user_groups_tmp (
  ug_user int unsigned NOT NULL default 0,
  ug_group varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/user_groups_tmp
	SELECT ug_user, ug_group
		FROM /*_*/user_groups;

DROP TABLE /*_*/user_groups;

ALTER TABLE /*_*/user_groups_tmp RENAME TO /*_*/user_groups;

CREATE UNIQUE INDEX /*i*/ug_user_group ON /*_*/user_groups (ug_user,ug_group);
CREATE INDEX /*i*/ug_group ON /*_*/user_groups (ug_group);
