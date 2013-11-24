ALTER TABLE /*_*/job
    ADD COLUMN job_attempts integer unsigned NOT NULL default 0;

CREATE INDEX /*i*/job_cmd_token_id ON /*_*/job (job_cmd,job_token,job_id);
