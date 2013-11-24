define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.job ADD job_timestamp		 TIMESTAMP(6) WITH TIME ZONE NULL;

