define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD ar_log_user_text VARCHAR2(255) NOT NULL;