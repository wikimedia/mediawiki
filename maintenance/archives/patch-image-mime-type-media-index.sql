-- New index on image table to allow searches for types i.e. video webm
-- Added 2013-01-08

CREATE INDEX /*i*/img_mime_type_media ON /*_*/image (img_media_type,img_major_mime,img_minor_mime,img_name);
