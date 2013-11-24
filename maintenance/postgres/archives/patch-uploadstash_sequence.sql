ALTER TABLE uploadstash RENAME us_id_seq TO uploadstash_us_id_seq;
ALTER TABLE uploadstash ALTER COLUMN us_id SET DEFAULT NEXTVAL('uploadstash_us_id_seq');
