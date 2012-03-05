ALTER TABLE /*_*/transcache MODIFY tc_time binary(14);
UPDATE /*_*/transcache SET tc_time = DATE_FORMAT(FROM_UNIXTIME(tc_time), "%Y%c%d%H%i%s");

INSERT INTO /*_*/updatelog(ul_key) VALUES ('convert transcache field');
