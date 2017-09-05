DROP INDEX user_newtalk_id_idx;
ALTER TABLE user_newtalk ADD PRIMARY KEY (user_id);
