ALTER TABLE &mw_prefix.user_newtalk ADD CONSTRAINT &mw_prefix.user_newtalk_pk PRIMARY KEY (user_id);
DROP INDEX &mw_prefix.user_newtalk_i01;
