-- MediaWiki looks for lines ending with semicolons and sends them as separate queries
-- However here we *really* need this all to be sent as a single batch. As such, DO NOT
-- remove the -- from the end of each statement.

DECLARE @temp table (
	img_name varbinary(255),
	img_size int,
	img_width int,
	img_height int,
	img_metadata varbinary(max),
	img_bits int,
	img_media_type varchar(16),
	img_major_mime varchar(16),
	img_minor_mime nvarchar(100),
	img_description nvarchar(255),
	img_user int,
	img_user_text nvarchar(255),
	img_timestamp nvarchar(14),
	img_sha1 nvarchar(32)
);--

INSERT INTO @temp
SELECT * FROM /*_*/image;--

DROP TABLE /*_*/image;--

CREATE TABLE /*_*/image (
  img_name nvarchar(255) NOT NULL default '' PRIMARY KEY,
  img_size int NOT NULL default 0,
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,
  img_metadata varbinary(max) NOT NULL,
  img_bits int NOT NULL default 0,
  img_media_type varchar(16) default null,
  img_major_mime varchar(16) not null default 'unknown',
  img_minor_mime nvarchar(100) NOT NULL default 'unknown',
  img_description nvarchar(255) NOT NULL,
  img_user int REFERENCES /*_*/mwuser(user_id) ON DELETE SET NULL,
  img_user_text nvarchar(255) NOT NULL,
  img_timestamp nvarchar(14) NOT NULL default '',
  img_sha1 nvarchar(32) NOT NULL default '',
  CONSTRAINT img_major_mime_ckc check (img_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical')),
  CONSTRAINT img_media_type_ckc check (img_media_type in('UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO', 'VIDEO', 'MULTIMEDIA', 'OFFICE', 'TEXT', 'EXECUTABLE', 'ARCHIVE'))
);--

CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);--
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);--
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);--
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1);--
CREATE INDEX /*i*/img_media_mime ON /*_*/image (img_media_type,img_major_mime,img_minor_mime);--

INSERT INTO /*_*/image
(
	img_name,
	img_size,
	img_width,
	img_height,
	img_metadata,
	img_bits,
	img_media_type,
	img_major_mime,
	img_minor_mime,
	img_description,
	img_user,
	img_user_text,
	img_timestamp,
	img_sha1
)
SELECT
	img_name,
	img_size,
	img_width,
	img_height,
	img_metadata,
	img_bits,
	img_media_type,
	img_major_mime,
	img_minor_mime,
	img_description,
	img_user,
	img_user_text,
	img_timestamp,
	img_sha1
FROM @temp t;
