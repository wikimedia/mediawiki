-- Split user table into two parts:
--   user
--   user_rights
-- The later contains only the permissions of the user. This way,
-- you can store the accounts for several wikis in one central
-- database but keep user rights local to the wiki.

CREATE TABLE /*$wgDBprefix*/user_rights (
	ur_user int(5) unsigned NOT NULL,
	ur_rights tinyblob NOT NULL default '',
	UNIQUE KEY ur_user (ur_user)
) PACK_KEYS=1;

INSERT INTO /*$wgDBprefix*/user_rights SELECT user_id,user_rights FROM /*$wgDBprefix*/user;

ALTER TABLE /*$wgDBprefix*/user DROP COLUMN user_rights;
