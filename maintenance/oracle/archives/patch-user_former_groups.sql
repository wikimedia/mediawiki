define mw_prefix='{$wgDBprefix}';

CREATE TABLE &mw_prefix.user_former_groups (
  ufg_user   NUMBER      DEFAULT 0 NOT NULL,
  ufg_group  VARCHAR2(16)     NOT NULL
);
ALTER TABLE &mw_prefix.user_former_groups ADD CONSTRAINT &mw_prefix.user_former_groups_fk1 FOREIGN KEY (ufg_user) REFERENCES &mw_prefix.mwuser(user_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
CREATE UNIQUE INDEX &mw_prefix.user_former_groups_u01 ON &mw_prefix.user_former_groups (ufg_user,ufg_group);

