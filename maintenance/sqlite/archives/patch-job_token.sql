ALTER TABLE /*_*/job ADD COLUMN job_random integer unsigned NOT NULL default 0;
ALTER TABLE /*_*/job ADD COLUMN job_token varbinary(32) NOT NULL default '';
ALTER TABLE /*_*/job ADD COLUMN job_sha1 varbinary(32) NOT NULL default '';
ALTER TABLE /*_*/job ADD COLUMN job_token_timestamp varbinary(14) NULL default NULL;

CREATE INDEX /*i*/job_sha1 ON /*_*/job (job_sha1);
CREATE INDEX /*i*/job_cmd_token ON /*_*/job (job_cmd,job_token,job_random);

