--
-- patch-delayed-queue.sql
-- Adds an index on the column to job to allow jobs to be deliberately delayed on a job queue
--
-- Plit into separate file from the column add as it seemed to make gerrit's Sqlite tests fail
-- 2013-03-07
--

CREATE INDEX job_not_before ON /*$wgDBprefix*/job(`job_not_before`);
