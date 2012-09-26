--
-- Store information about newly uploaded files before they're
-- moved into the actual filestore
--
CREATE TABLE /*_*/uploadstash (
	us_id int unsigned NOT NULL PRIMARY KEY auto_increment,

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
	us_timestamp varbinary(14) not null,

	us_status varchar(50) not null,

	-- file properties from File::getPropsFromPath.  these may prove unnecessary.
	--
	us_size int unsigned NOT NULL,
	-- this hash comes from File::sha1Base36(), and is 31 characters
	us_sha1 varchar(31) NOT NULL,
	us_mime varchar(255),
	-- Media type as defined by the MEDIATYPE_xxx constants, should duplicate definition in the image table
	us_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
	-- image-specific properties
	us_image_width int unsigned,
	us_image_height int unsigned,
	us_image_bits smallint unsigned
) /*$wgDBTableOptions*/;

-- sometimes there's a delete for all of a user's stuff.
CREATE INDEX /*i*/us_user ON /*_*/uploadstash (us_user);
-- pick out files by key, enforce key uniqueness
CREATE UNIQUE INDEX /*i*/us_key ON /*_*/uploadstash (us_key);
-- the abandoned upload cleanup script needs this
CREATE INDEX /*i*/us_timestamp ON /*_*/uploadstash (us_timestamp);
