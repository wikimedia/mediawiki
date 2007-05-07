-- 
-- patch-indexes.sql
-- 
-- Add data to allow for direct reference to old images
-- They can be included into pages
-- 

ALTER TABLE /*$wgDBprefix*/oldimage
   DROP INDEX oi_name,
   ADD INDEX oi_name_timestamp (oi_name,oi_timestamp),
   ADD oi_metadata mediumblob NOT NULL,
   ADD oi_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
   ADD oi_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") NOT NULL default "unknown",
   ADD oi_minor_mime varchar(32) NOT NULL default "unknown",
   ADD oi_deleted tinyint(1) unsigned NOT NULL default '0',