
CREATE TABLE /*_*/file (
  file_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  file_name VARBINARY(255) NOT NULL,
  file_latest BIGINT UNSIGNED NOT NULL,
  file_type SMALLINT UNSIGNED NOT NULL,
  file_deleted SMALLINT UNSIGNED NOT NULL,
  UNIQUE INDEX file_name (file_name),
  INDEX file_latest (file_latest),
  PRIMARY KEY(file_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/filerevision (
  fr_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  fr_file BIGINT NOT NULL,
  fr_size BIGINT UNSIGNED DEFAULT 0 NOT NULL,
  fr_width INT UNSIGNED DEFAULT 0 NOT NULL,
  fr_height INT UNSIGNED DEFAULT 0 NOT NULL,
  fr_metadata MEDIUMBLOB NOT NULL,
  fr_bits INT UNSIGNED DEFAULT 0 NOT NULL,
  fr_description_id BIGINT UNSIGNED NOT NULL,
  fr_actor BIGINT UNSIGNED NOT NULL,
  fr_timestamp BINARY(14) NOT NULL,
  fr_sha1 VARBINARY(32) DEFAULT '' NOT NULL,
  fr_archive_name VARBINARY(255) DEFAULT '' NOT NULL,
  fr_deleted SMALLINT UNSIGNED NOT NULL,
  INDEX fr_actor_timestamp (fr_actor, fr_timestamp),
  INDEX fr_size (fr_size),
  INDEX fr_timestamp (fr_timestamp),
  INDEX fr_sha1 (
    fr_sha1(10)
  ),
  INDEX fr_file (fr_file),
  PRIMARY KEY(fr_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/filetypes (
  ft_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  ft_media_type VARBINARY(255) NOT NULL,
  ft_major_mime VARBINARY(255) NOT NULL,
  ft_minor_mime VARBINARY(255) NOT NULL,
  UNIQUE INDEX ft_media_mime (
    ft_media_type, ft_major_mime, ft_minor_mime
  ),
  PRIMARY KEY(ft_id)
) /*$wgDBTableOptions*/;
