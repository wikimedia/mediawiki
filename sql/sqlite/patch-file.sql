
CREATE TABLE /*_*/file (
  file_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  file_name BLOB NOT NULL, file_latest BIGINT UNSIGNED NOT NULL,
  file_type SMALLINT UNSIGNED NOT NULL,
  file_deleted SMALLINT UNSIGNED NOT NULL
);

CREATE UNIQUE INDEX file_name ON /*_*/file (file_name);

CREATE INDEX file_latest ON /*_*/file (file_latest);


CREATE TABLE /*_*/filerevision (
  fr_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  fr_file BIGINT NOT NULL, fr_size BIGINT UNSIGNED DEFAULT 0 NOT NULL,
  fr_width INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  fr_height INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  fr_metadata BLOB NOT NULL, fr_bits INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  fr_description_id BIGINT UNSIGNED NOT NULL,
  fr_actor BIGINT UNSIGNED NOT NULL,
  fr_timestamp BLOB NOT NULL, fr_sha1 BLOB DEFAULT '' NOT NULL,
  fr_archive_name BLOB DEFAULT '' NOT NULL,
  fr_deleted SMALLINT UNSIGNED NOT NULL
);

CREATE INDEX fr_actor_timestamp ON /*_*/filerevision (fr_actor, fr_timestamp);

CREATE INDEX fr_size ON /*_*/filerevision (fr_size);

CREATE INDEX fr_timestamp ON /*_*/filerevision (fr_timestamp);

CREATE INDEX fr_sha1 ON /*_*/filerevision (fr_sha1);

CREATE INDEX fr_file ON /*_*/filerevision (fr_file);


CREATE TABLE /*_*/filetypes (
  ft_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  ft_media_type BLOB NOT NULL, ft_major_mime BLOB NOT NULL,
  ft_minor_mime BLOB NOT NULL
);

CREATE UNIQUE INDEX ft_media_mime ON /*_*/filetypes (
  ft_media_type, ft_major_mime, ft_minor_mime
);
