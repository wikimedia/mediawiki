define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD ar_log_timestamp TIMESTAMP(6) WITH TIME ZONE  NOT NULL;