UPDATE /*_*/transcache SET tc_time = strftime('%Y%m%d%H%M%S', datetime(tc_time, 'unixepoch'));

INSERT INTO /*_*/updatelog VALUES ('convert transcache field');
