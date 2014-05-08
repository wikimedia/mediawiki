CREATE TABLE &paused_job (
  paused_job_cmd   VARCHAR2(60) NOT NULL
);
ALTER TABLE &mw_prefix.paused_job ADD CONSTRAINT &mw_prefix.paused_job_pk PRIMARY KEY (paused_job_cmd);
