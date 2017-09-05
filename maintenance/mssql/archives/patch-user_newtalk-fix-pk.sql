DROP INDEX &mw_prefix.user_newtalk_i01;
ALTER TABLE /*_*/user_newtalk ADD CONSTRAINT PK_user_newtalk PRIMARY KEY(user_id, user_ip);
