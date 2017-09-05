DROP INDEX /*i*/ug_group ON /*_*/user_groups;
ALTER TABLE /*_*/user_newtalk ADD CONSTRAINT PK_user_newtalk PRIMARY KEY(user_id, user_ip);
