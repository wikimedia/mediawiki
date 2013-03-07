--
-- patch-job-not_before.sql
-- Adds a column to job to allow jobs to be deliberately delayed on a job queue
-- 2013-2-27
--

ALTER TABLE /*$wgDBprefix*/job
	ADD COLUMN job_not_before varbinary(14) NULL DEFAULT NULL;

CREATE INDEX job_not_before ON /*$wgDBprefix*/job(`job_not_before`);
