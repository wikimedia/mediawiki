define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.job ADD   job_attempts NUMBER DEFAULT 0 NOT NULL;
CREATE INDEX &mw_prefix.job_i05 ON &mw_prefix.job (job_attempts);
