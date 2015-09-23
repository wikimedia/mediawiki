-- New index on image table to allow searches for types i.e. video webm
-- Added 2013-01-08

CREATE INDEX /*i*/img_media_mime ON /*_*/image (img_media_type,img_major_mime,img_minor_mime);
