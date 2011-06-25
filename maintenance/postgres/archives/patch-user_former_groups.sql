CREATE TABLE user_former_groups (
  ufg_user   INTEGER      NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  ufg_group  TEXT     NOT NULL
);
CREATE UNIQUE INDEX user_former_groups_unique ON user_former_groups (ufg_user, ufg_group);
