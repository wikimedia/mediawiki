
ALTER TABLE /*$wgDBprefix*/user_groups
	CHANGE user_id ug_user INT(5) UNSIGNED NOT NULL DEFAULT '0',
	CHANGE group_id ug_group INT(5) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE /*$wgDBprefix*/user_rights
	CHANGE user_id ur_user INT(5) UNSIGNED NOT NULL,
	CHANGE user_rights ur_rights TINYBLOB NOT NULL DEFAULT '';

