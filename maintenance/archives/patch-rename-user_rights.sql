
ALTER TABLE user_rights
	CHANGE user_id ur_user INT(5) UNSIGNED NOT NULL,
	CHANGE user_rights ur_rights TINYBLOB NOT NULL DEFAULT '';

