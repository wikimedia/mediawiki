define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD ar_log_comment VARCHAR2(255);