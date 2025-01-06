
CREATE TABLE file (
  file_id BIGSERIAL NOT NULL,
  file_name TEXT NOT NULL,
  file_latest BIGINT NOT NULL,
  file_type SMALLINT NOT NULL,
  file_deleted SMALLINT NOT NULL,
  PRIMARY KEY(file_id)
);

CREATE UNIQUE INDEX file_name ON file (file_name);

CREATE INDEX file_latest ON file (file_latest);


CREATE TABLE filerevision (
  fr_id BIGSERIAL NOT NULL,
  fr_file BIGINT NOT NULL,
  fr_size BIGINT DEFAULT 0 NOT NULL,
  fr_width INT DEFAULT 0 NOT NULL,
  fr_height INT DEFAULT 0 NOT NULL,
  fr_metadata TEXT NOT NULL,
  fr_bits INT DEFAULT 0 NOT NULL,
  fr_description_id BIGINT NOT NULL,
  fr_actor BIGINT NOT NULL,
  fr_timestamp TIMESTAMPTZ NOT NULL,
  fr_sha1 TEXT DEFAULT '' NOT NULL,
  fr_archive_name TEXT DEFAULT '' NOT NULL,
  fr_deleted SMALLINT NOT NULL,
  PRIMARY KEY(fr_id)
);

CREATE INDEX fr_actor_timestamp ON filerevision (fr_actor, fr_timestamp);

CREATE INDEX fr_size ON filerevision (fr_size);

CREATE INDEX fr_timestamp ON filerevision (fr_timestamp);

CREATE INDEX fr_sha1 ON filerevision (fr_sha1);

CREATE INDEX fr_file ON filerevision (fr_file);


CREATE TABLE filetypes (
  ft_id SMALLSERIAL NOT NULL,
  ft_media_type TEXT NOT NULL,
  ft_major_mime TEXT NOT NULL,
  ft_minor_mime TEXT NOT NULL,
  PRIMARY KEY(ft_id)
);

CREATE UNIQUE INDEX ft_media_mime ON filetypes (
  ft_media_type, ft_major_mime, ft_minor_mime
);
