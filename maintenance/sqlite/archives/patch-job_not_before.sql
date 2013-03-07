ALTER TABLE /*_*/job ADD COLUMN `job_not_before` varbinary(14) NULL DEFAULT NULL;
CREATE INDEX /*i*/job_not_before ON /*_*/job(`job_not_before`);
