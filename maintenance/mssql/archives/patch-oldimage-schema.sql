-- MediaWiki looks for lines ending with semicolons and sends them as separate queries
-- However here we *really* need this all to be sent as a single batch. As such, DO NOT
-- remove the -- from the end of each statement.

DECLARE @temp table (
	oi_name varbinary(255),
	oi_archive_name varbinary(255),
	oi_size int,
	oi_width int,
	oi_height int,
	oi_bits int,
	oi_description nvarchar(255),
	oi_user int,
	oi_user_text nvarchar(255),
	oi_timestamp varchar(14),
	oi_metadata nvarchar(max),
	oi_media_type varchar(16),
	oi_major_mime varchar(16),
	oi_minor_mime nvarchar(100),
	oi_deleted tinyint,
	oi_sha1 nvarchar(32)
);--

INSERT INTO @temp
SELECT * FROM /*_*/oldimage;--

DROP TABLE /*_*/oldimage;--

CREATE TABLE /*_*/oldimage (
  oi_name nvarchar(255) NOT NULL default '',
  oi_archive_name nvarchar(255) NOT NULL default '',
  oi_size int NOT NULL default 0,
  oi_width int NOT NULL default 0,
  oi_height int NOT NULL default 0,
  oi_bits int NOT NULL default 0,
  oi_description nvarchar(255) NOT NULL,
  oi_user int REFERENCES /*_*/mwuser(user_id),
  oi_user_text nvarchar(255) NOT NULL,
  oi_timestamp varchar(14) NOT NULL default '',
  oi_metadata varbinary(max) NOT NULL,
  oi_media_type varchar(16) default null,
  oi_major_mime varchar(16) not null default 'unknown',
  oi_minor_mime nvarchar(100) NOT NULL default 'unknown',
  oi_deleted tinyint NOT NULL default 0,
  oi_sha1 nvarchar(32) NOT NULL default '',
  CONSTRAINT oi_major_mime_ckc check (oi_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical')),
  CONSTRAINT oi_media_type_ckc check (oi_media_type IN('UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO', 'VIDEO', 'MULTIMEDIA', 'OFFICE', 'TEXT', 'EXECUTABLE', 'ARCHIVE'))
);--

CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage (oi_user_text, oi_timestamp);--
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name, oi_timestamp);--
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name, oi_archive_name);--
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1);--

INSERT INTO /*_*/oldimage
(
	oi_name,
	oi_archive_name,
	oi_size,
	oi_width,
	oi_height,
	oi_bits,
	oi_description,
	oi_user,
	oi_user_text,
	oi_timestamp,
	oi_metadata,
	oi_media_type,
	oi_major_mime,
	oi_minor_mime,
	oi_deleted,
	oi_sha1
)
SELECT
	oi_name,
	oi_archive_name,
	oi_size,
	oi_width,
	oi_height,
	oi_bits,
	oi_description,
	oi_user,
	oi_user_text,
	oi_timestamp,
	CONVERT(varbinary(max), oi_metadata, 0),
	oi_media_type,
	oi_major_mime,
	oi_minor_mime,
	oi_deleted,
	oi_sha1
FROM @temp t;
