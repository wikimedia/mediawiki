-- image

CREATE TABLE /*_*/image_tmp (
  -- Filename.
  -- This is also the title of the associated description page,
  -- which will be in namespace 6 (NS_FILE).
  img_name varchar(255) binary NOT NULL default '' PRIMARY KEY,

  -- File size in bytes.
  img_size int unsigned NOT NULL default 0,

  -- For images, size in pixels.
  img_width int NOT NULL default 0,
  img_height int NOT NULL default 0,

  -- Extracted Exif metadata stored as a serialized PHP array.
  img_metadata mediumblob NOT NULL,

  -- For images, bits per pixel if known.
  img_bits int NOT NULL default 0,

  -- Media type as defined by the MEDIATYPE_xxx constants
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,

  -- major part of a MIME media type as defined by IANA
  -- see https://www.iana.org/assignments/media-types/
  -- for "chemical" cf. http://dx.doi.org/10.1021/ci9803233 by the ACS
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",

  -- minor part of a MIME media type as defined by IANA
  -- the minor parts are not required to adher to any standard
  -- but should be consistent throughout the database
  -- see https://www.iana.org/assignments/media-types/
  img_minor_mime varbinary(100) NOT NULL default "unknown",

  -- Description field as entered by the uploader.
  -- This is displayed in image upload history and logs.
  img_description varbinary(767) NOT NULL,

  -- user_id and user_name of uploader.
  img_user int unsigned NOT NULL default 0,
  img_user_text varchar(255) binary NOT NULL,

  -- Time of the upload.
  img_timestamp varbinary(14) NOT NULL default '',

  -- SHA-1 content hash in base-36
  img_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/image_tmp
	SELECT img_name, img_size, img_width, img_height, img_metadata, img_bits,
	img_media_type, img_major_mime, img_minor_mime, img_description,
	img_user, img_user_text, img_timestamp, img_sha1
		FROM /*_*/image;

DROP TABLE /*_*/image;

ALTER TABLE /*_*/image_tmp RENAME TO /*_*/image;

-- Used by Special:Newimages and ApiQueryAllImages
CREATE INDEX /*i*/img_user_timestamp ON /*_*/image (img_user,img_timestamp);
CREATE INDEX /*i*/img_usertext_timestamp ON /*_*/image (img_user_text,img_timestamp);
-- Used by Special:ListFiles for sort-by-size
CREATE INDEX /*i*/img_size ON /*_*/image (img_size);
-- Used by Special:Newimages and Special:ListFiles
CREATE INDEX /*i*/img_timestamp ON /*_*/image (img_timestamp);
-- Used in API and duplicate search
CREATE INDEX /*i*/img_sha1 ON /*_*/image (img_sha1(10));
-- Used to get media of one type
CREATE INDEX /*i*/img_media_mime ON /*_*/image (img_media_type,img_major_mime,img_minor_mime);

-- oldimage

CREATE TABLE /*_*/oldimage_tmp (
  -- Base filename: key to image.img_name
  oi_name varchar(255) binary NOT NULL default '',

  -- Filename of the archived file.
  -- This is generally a timestamp and '!' prepended to the base name.
  oi_archive_name varchar(255) binary NOT NULL default '',

  -- Other fields as in image...
  oi_size int unsigned NOT NULL default 0,
  oi_width int NOT NULL default 0,
  oi_height int NOT NULL default 0,
  oi_bits int NOT NULL default 0,
  oi_description varbinary(767) NOT NULL,
  oi_user int unsigned NOT NULL default 0,
  oi_user_text varchar(255) binary NOT NULL,
  oi_timestamp binary(14) NOT NULL default '',

  oi_metadata mediumblob NOT NULL,
  oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  oi_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") NOT NULL default "unknown",
  oi_minor_mime varbinary(100) NOT NULL default "unknown",
  oi_deleted tinyint unsigned NOT NULL default 0,
  oi_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/oldimage_tmp
	SELECT oi_name, oi_archive_name, oi_size, oi_width, oi_height, oi_bits,
	oi_description, oi_user, oi_user_text, oi_timestamp, oi_metadata,
	oi_media_type, oi_major_mime, oi_minor_mime, oi_deleted, oi_sha1
		FROM /*_*/oldimage;

DROP TABLE /*_*/oldimage;

ALTER TABLE oldimage_tmp RENAME TO /*_*/oldimage;

CREATE INDEX /*i*/oi_usertext_timestamp ON /*_*/oldimage (oi_user_text,oi_timestamp);
CREATE INDEX /*i*/oi_name_timestamp ON /*_*/oldimage (oi_name,oi_timestamp);
-- oi_archive_name truncated to 14 to avoid key length overflow
CREATE INDEX /*i*/oi_name_archive_name ON /*_*/oldimage (oi_name,oi_archive_name(14));
CREATE INDEX /*i*/oi_sha1 ON /*_*/oldimage (oi_sha1(10));

-- filearchive

CREATE TABLE /*_*/filearchive_tmp (
  -- Unique row id
  fa_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Original base filename; key to image.img_name, page.page_title, etc
  fa_name varchar(255) binary NOT NULL default '',

  -- Filename of archived file, if an old revision
  fa_archive_name varchar(255) binary default '',

  -- Which storage bin (directory tree or object store) the file data
  -- is stored in. Should be 'deleted' for files that have been deleted;
  -- any other bin is not yet in use.
  fa_storage_group varbinary(16),

  -- SHA-1 of the file contents plus extension, used as a key for storage.
  -- eg 8f8a562add37052a1848ff7771a2c515db94baa9.jpg
  --
  -- If NULL, the file was missing at deletion time or has been purged
  -- from the archival storage.
  fa_storage_key varbinary(64) default '',

  -- Deletion information, if this file is deleted.
  fa_deleted_user int,
  fa_deleted_timestamp binary(14) default '',
  fa_deleted_reason varbinary(767) default '',

  -- Duped fields from image
  fa_size int unsigned default 0,
  fa_width int default 0,
  fa_height int default 0,
  fa_metadata mediumblob,
  fa_bits int default 0,
  fa_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  fa_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart", "chemical") default "unknown",
  fa_minor_mime varbinary(100) default "unknown",
  fa_description varbinary(767),
  fa_user int unsigned default 0,
  fa_user_text varchar(255) binary,
  fa_timestamp binary(14) default '',

  -- Visibility of deleted revisions, bitfield
  fa_deleted tinyint unsigned NOT NULL default 0,

  -- sha1 hash of file content
  fa_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/filearchive_tmp
	SELECT fa_id, fa_name, fa_archive_name, fa_storage_group, fa_storage_key, fa_deleted_user, fa_deleted_timestamp,
	fa_deleted_reason, fa_size, fa_width, fa_height, fa_metadata, fa_bits, fa_media_type, fa_major_mime,
	fa_minor_mime, fa_description, fa_user, fa_user_text, fa_timestamp, fa_deleted, fa_sha1
		FROM /*_*/filearchive;

DROP TABLE /*_*/filearchive;

ALTER TABLE /*_*/filearchive_tmp RENAME TO /*_*/filearchive;

-- pick out by image name
CREATE INDEX /*i*/fa_name ON /*_*/filearchive (fa_name, fa_timestamp);
-- pick out dupe files
CREATE INDEX /*i*/fa_storage_group ON /*_*/filearchive (fa_storage_group, fa_storage_key);
-- sort by deletion time
CREATE INDEX /*i*/fa_deleted_timestamp ON /*_*/filearchive (fa_deleted_timestamp);
-- sort by uploader
CREATE INDEX /*i*/fa_user_timestamp ON /*_*/filearchive (fa_user_text,fa_timestamp);
-- find file by sha1, 10 bytes will be enough for hashes to be indexed
CREATE INDEX /*i*/fa_sha1 ON /*_*/filearchive (fa_sha1(10));

-- uploadstash

CREATE TABLE /*_*/uploadstash_tmp (
  us_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- the user who uploaded the file.
  us_user int unsigned NOT NULL,

  -- file key. this is how applications actually search for the file.
  -- this might go away, or become the primary key.
  us_key varchar(255) NOT NULL,

  -- the original path
  us_orig_path varchar(255) NOT NULL,

  -- the temporary path at which the file is actually stored
  us_path varchar(255) NOT NULL,

  -- which type of upload the file came from (sometimes)
  us_source_type varchar(50),

  -- the date/time on which the file was added
  us_timestamp varbinary(14) NOT NULL,

  us_status varchar(50) NOT NULL,

  -- chunk counter starts at 0, current offset is stored in us_size
  us_chunk_inx int unsigned NULL,

  -- Serialized file properties from FSFile::getProps()
  us_props blob,

  -- file size in bytes
  us_size int unsigned NOT NULL,
  -- this hash comes from FSFile::getSha1Base36(), and is 31 characters
  us_sha1 varchar(31) NOT NULL,
  us_mime varchar(255),
  -- Media type as defined by the MEDIATYPE_xxx constants, should duplicate definition in the image table
  us_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE", "3D") default NULL,
  -- image-specific properties
  us_image_width int unsigned,
  us_image_height int unsigned,
  us_image_bits smallint unsigned

) /*$wgDBTableOptions*/;

INSERT INTO /*_*/uploadstash_tmp
	SELECT us_id, us_user, us_key, us_orig_path, us_path, us_source_type,
	us_timestamp, us_status, us_chunk_inx, us_props, us_size, us_sha1, us_mime,
	us_media_type, us_image_width, us_image_height, us_image_bits
		FROM /*_*/uploadstash;

DROP TABLE uploadstash;

ALTER TABLE /*_*/uploadstash_tmp RENAME TO /*_*/uploadstash;

-- sometimes there's a delete for all of a user's stuff.
CREATE INDEX /*i*/us_user ON /*_*/uploadstash (us_user);
-- pick out files by key, enforce key uniqueness
CREATE UNIQUE INDEX /*i*/us_key ON /*_*/uploadstash (us_key);
-- the abandoned upload cleanup script needs this
CREATE INDEX /*i*/us_timestamp ON /*_*/uploadstash (us_timestamp);
