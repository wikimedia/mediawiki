-- profiling table
-- This is optional

CREATE TABLE /*_*/profiling (
  pf_count int NOT NULL default 0,
  pf_time float NOT NULL default 0,
  pf_memory float NOT NULL default 0,
  pf_name varchar(255) NOT NULL default '',
  pf_server varchar(30) NOT NULL default ''
) ENGINE=MEMORY;

CREATE UNIQUE INDEX /*i*/pf_name_server ON /*_*/profiling (pf_name, pf_server);