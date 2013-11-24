ALTER TABLE /*_*/job ADD COLUMN job_timestamp varbinary(14) NULL default NULL;
CREATE INDEX /*i*/job_timestamp ON /*_*/job(job_timestamp);
