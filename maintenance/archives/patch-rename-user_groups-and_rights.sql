
ALTER TABLE user_groups
	CHANGE user_id ug_uid INT(5) UNSIGNED NOT NULL DEFAULT '0',
	CHANGE group_id ug_gid INT(5) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE user_rights
	CHANGE user_id ur_uid INT(5) UNSIGNED NOT NULL,
	CHANGE user_rights ur_rights TINYBLOB NOT NULL DEFAULT '';

