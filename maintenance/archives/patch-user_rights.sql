-- Split user table into two parts:
--   user
--   user_rights
-- The later contains only the permissions of the user. This way,
-- you can store the accounts for several wikis in one central
-- database but keep user rights local to the wiki.

CREATE TABLE user_rights (
	ur_uid int(5) unsigned NOT NULL,
	ur_rights tinyblob NOT NULL default '',
	UNIQUE KEY user_id (user_id)
) PACK_KEYS=1;

INSERT INTO user_rights SELECT user_id,user_rights FROM user;

ALTER TABLE user DROP COLUMN user_rights;
