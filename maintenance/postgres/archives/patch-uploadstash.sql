CREATE SEQUENCE uploadstash_us_id_seq;
CREATE TYPE media_type AS ENUM ('UNKNOWN','BITMAP','DRAWING','AUDIO','VIDEO','MULTIMEDIA','OFFICE','TEXT','EXECUTABLE','ARCHIVE');

CREATE TABLE uploadstash (
  us_id           INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('uploadstash_us_id_seq'),
  us_user         INTEGER,
  us_key		  TEXT,
  us_orig_path    TEXT,
  us_path		  TEXT,
  us_source_type  TEXT,
  us_timestamp	  TIMESTAMPTZ,
  us_status		  TEXT,
  us_size		  INTEGER,
  us_sha1		  TEXT,
  us_mime		  TEXT,
  us_media_type	  media_type DEFAULT NULL,
  us_image_width  INTEGER,
  us_image_height INTEGER,
  us_image_bits   INTEGER
);

CREATE INDEX us_user_idx ON uploadstash (us_user);
CREATE UNIQUE INDEX us_key_idx ON uploadstash (us_key);
CREATE INDEX us_timestamp_idx ON uploadstash (us_timestamp);
