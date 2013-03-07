--
-- patch-delayed-queue.sql
-- Adds a column to job to allow jobs to be deliberately delayed on a job queue
-- 2013-2-27
--

ALTER TABLE /*$wgDBprefix*/job
	ADD COLUMN `job_not_before` varbinary(14) DEFAULT NULL;

ALTER TABLE /*$wgDBprefix*/job
	ADD INDEX `job_not_before` (`job_not_before`);
