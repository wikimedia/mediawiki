define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD ar_log_user NUMBER DEFAULT 0 NOT NULL;