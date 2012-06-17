-- Add the user_msg_type column to user_newtalk and create indices

ALTER TABLE &mw_prefix.user_newtalk ADD user_msg_type int unsigned;
UPDATE &mw_prefix.user_newtalk SET user_msg_type=0;
ALTER TABLE &mw_prefix.user_newtalk MODIFY user_msg_type INTEGER NOT NULL default 0;

DROP INDEX &mw_prefix.un_user_id ON user_newtalk;
DROP INDEX &mw_prefix.un_user_ip ON user_newtalk;