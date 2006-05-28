-- profiling table
-- This is optional

CREATE TABLE /*$wgDBprefix*/profiling (
	pf_count integer not null default 0,
	pf_time float not null default 0,
	pf_name varchar(255) not null default '',
	pf_server varchar(30) not null default '',
	UNIQUE KEY pf_name_server (pf_name, pf_server)
) TYPE=HEAP;
