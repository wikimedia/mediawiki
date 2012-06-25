ALTER TABLE /*_*/job
	ADD COLUMN job_token varbinary(32) NOT NULL default '',
	ADD COLUMN job_sha1 varbinary(32) NOT NULL default '';

CREATE INDEX /*i*/job_sha1 ON /*_*/job (job_sha1);
CREATE INDEX /*i*/job_token_cmd ON /*_*/job (job_token,job_cmd);
