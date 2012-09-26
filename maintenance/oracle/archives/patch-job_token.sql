define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.job ADD (
	job_random NUMBER DEFAULT 0 NOT NULL,
	job_token VARCHAR2(32),
	job_token_timestamp TIMESTAMP(6) WITH TIME ZONE,
	job_sha1 VARCHAR2(32)
);

CREATE INDEX &mw_prefix.job_i03 ON &mw_prefix.job (job_sha1);
CREATE INDEX &mw_prefix.job_i04 ON &mw_prefix.job (job_cmd,job_token,job_random);

