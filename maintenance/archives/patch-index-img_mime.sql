-- Indexing MIME types
--
-- Allows MIME search to work on large databases like Wikimedia one

ALTER TABLE /*$wgDBprefix*/image
   ADD INDEX img_mime (img_major_mime, img_minor_mime);
