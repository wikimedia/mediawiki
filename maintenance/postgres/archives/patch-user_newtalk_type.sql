-- Add the user_msg_type column to user_newtalk and create indices

ALTER TABLE user_newtalk
  ADD user_msg_type int unsigned;

UPDATE user_newtalk
  SET user_msg_type=0;

ALTER TABLE user_newtalk
  MODIFY user_msg_type INTEGER NOT NULL default 0;

DROP INDEX un_user_id ON user_newtalk;
DROP INDEX un_user_ip ON user_newtalk;

CREATE UNIQUE INDEX un_user_id ON user_newtalk (user_id, user_msg_type);
CREATE UNIQUE INDEX un_user_ip ON user_newtalk (user_ip, user_msg_type);