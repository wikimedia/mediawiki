CREATE TABLE profiling (
  pf_count   INTEGER         NOT NULL DEFAULT 0,
  pf_time    FLOAT           NOT NULL DEFAULT 0,
  pf_memory  FLOAT           NOT NULL DEFAULT 0,
  pf_name    TEXT            NOT NULL,
  pf_server  TEXT            NULL
);
CREATE UNIQUE INDEX pf_name_server ON profiling (pf_name, pf_server);
