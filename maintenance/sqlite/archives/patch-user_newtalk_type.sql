-- Add the user_msg_type column to user_newtalk and create indices

ALTER TABLE /*_*/user_newtalk
  ADD user_msg_type int unsigned NOT NULL default 0;

DROP INDEX /*i*/un_user_id;
DROP INDEX /*i*/un_user_ip;

CREATE UNIQUE INDEX /*i*/un_user_id ON /*_*/user_newtalk (user_id, user_msg_type);
CREATE UNIQUE INDEX /*i*/un_user_ip ON /*_*/user_newtalk (user_ip, user_msg_type);