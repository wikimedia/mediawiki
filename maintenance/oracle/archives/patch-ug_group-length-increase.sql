define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.user_groups MODIFY ug_group VARCHAR2(32) NOT NULL;
