-- MediaWiki looks for lines ending with semicolons and sends them as separate queries
-- However here we *really* need this all to be sent as a single batch. As such, DO NOT
-- remove the -- from the end of each statement.

DECLARE @temp table (
	fa_id int,
	fa_name nvarchar(255),
	fa_archive_name nvarchar(255),
	fa_storage_group nvarchar(16),
	fa_storage_key nvarchar(64),
	fa_deleted_user int,
	fa_deleted_timestamp varchar(14),
	fa_deleted_reason nvarchar(max),
	fa_size int,
	fa_width int,
	fa_height int,
	fa_metadata nvarchar(max),
	fa_bits int,
	fa_media_type varchar(16),
	fa_major_mime varchar(16),
	fa_minor_mime nvarchar(100),
	fa_description nvarchar(255),
	fa_user int,
	fa_user_text nvarchar(255),
	fa_timestamp varchar(14),
	fa_deleted tinyint,
	fa_sha1 nvarchar(32)
);--

INSERT INTO @temp
SELECT * FROM /*_*/filearchive;--

DROP TABLE /*_*/filearchive;--

CREATE TABLE /*_*/filearchive (
  fa_id int NOT NULL PRIMARY KEY IDENTITY,
  fa_name nvarchar(255) NOT NULL default '',
  fa_archive_name nvarchar(255) default '',
  fa_storage_group nvarchar(16),
  fa_storage_key nvarchar(64) default '',
  fa_deleted_user int,
  fa_deleted_timestamp varchar(14) default '',
  fa_deleted_reason nvarchar(max),
  fa_size int default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata varbinary(max),
  fa_bits int default 0,
  fa_media_type varchar(16) default null,
  fa_major_mime varchar(16) not null default 'unknown',
  fa_minor_mime nvarchar(100) default 'unknown',
  fa_description nvarchar(255),
  fa_user int default 0 REFERENCES /*_*/mwuser(user_id) ON DELETE SET NULL,
  fa_user_text nvarchar(255),
  fa_timestamp varchar(14) default '',
  fa_deleted tinyint NOT NULL default 0,
  fa_sha1 nvarchar(32) NOT NULL default '',
  CONSTRAINT fa_major_mime_ckc check (fa_major_mime in('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical')),
  CONSTRAINT fa_media_type_ckc check (fa_media_type in('UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO', 'VIDEO', 'MULTIMEDIA', 'OFFICE', 'TEXT', 'EXECUTABLE', 'ARCHIVE'))
);--

CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);--
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);--
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);--
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);--
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1);--

SET IDENTITY_INSERT /*_*/filearchive ON;--

INSERT INTO /*_*/filearchive
(
	fa_id,
	fa_name,
	fa_archive_name,
	fa_storage_group,
	fa_storage_key,
	fa_deleted_user,
	fa_deleted_timestamp,
	fa_deleted_reason,
	fa_size,
	fa_width,
	fa_height,
	fa_metadata,
	fa_bits,
	fa_media_type,
	fa_major_mime,
	fa_minor_mime,
	fa_description,
	fa_user,
	fa_user_text,
	fa_timestamp,
	fa_deleted,
	fa_sha1
)
SELECT
	fa_id,
	fa_name,
	fa_archive_name,
	fa_storage_group,
	fa_storage_key,
	fa_deleted_user,
	fa_deleted_timestamp,
	fa_deleted_reason,
	fa_size,
	fa_width,
	fa_height,
	CONVERT(varbinary(max), fa_metadata, 0),
	fa_bits,
	fa_media_type,
	fa_major_mime,
	fa_minor_mime,
	fa_description,
	fa_user,
	fa_user_text,
	fa_timestamp,
	fa_deleted,
	fa_sha1
FROM @temp t;--

SET IDENTITY_INSERT /*_*/filearchive OFF;
