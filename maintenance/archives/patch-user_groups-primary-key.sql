-- Convert unique index into a primary key on user_groups

ALTER TABLE /*$wgDBprefix*/user_groups
  DROP INDEX ug_user_group,
  ADD PRIMARY KEY (ug_user, ug_group);
