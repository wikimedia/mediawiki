define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.user_groups ADD (
ug_expiry TIMESTAMP(6) WITH TIME ZONE  NULL
);
DROP INDEX IF EXISTS &mw_prefix.user_groups_u01;
ALTER TABLE &mw_prefix.user_groups ADD CONSTRAINT &mw_prefix.user_groups_pk PRIMARY KEY (ug_user,ug_group);
CREATE INDEX &mw_prefix.user_groups_i02 ON &mw_prefix.user_groups (ug_expiry);
