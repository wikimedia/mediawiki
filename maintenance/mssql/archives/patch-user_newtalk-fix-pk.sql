DROP INDEX /*i*/un_user_id ON /*_*/user_newtalk;
ALTER TABLE /*_*/user_newtalk ADD CONSTRAINT PK_user_newtalk PRIMARY KEY(user_id, user_ip);
