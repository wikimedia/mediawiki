UPDATE /*_*/transcache SET tc_time = strftime('%Y%m%d%H%M%S', datetime(1260465428, 'unixepoch'));

INSERT INTO /*_*/updatelog VALUES ('convert transcache field');
