CREATE SEQUENCE 'watchlist_groups_wg_id_seq';
CREATE TABLE watchlist_groups (
  wg_id     INTEGER  NOT NULL  PRIMARY KEY DEFAULT nextval('watchlist_groups_wg_id_seq'),
  wg_name   TEXT     NOT NULL,
  wg_user   INTEGER  NOT NULL  REFERENCES mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  wg_perm   SMALLINT NOT NULL  DEFAULT 0,
);

CREATE UNIQUE INDEX wg_id_name_user_perm ON watchlist (wg_id, wg_name, wg_user, wg_perm);