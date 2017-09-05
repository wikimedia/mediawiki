ALTER TABLE /*_*/user_newtalk ADD CONSTRAINT pk_user_newtalk PRIMARY KEY(user_ud);
DROP INDEX /*i*/un_user_id ON /*_*/user_newtalk;

