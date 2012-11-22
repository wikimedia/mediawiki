-- New index on image table to allow searches for types i.e. video webm
-- Added 2012-11-22

CREATE INDEX /*i*/img_media_type ON /*_*/image (img_media_type,img_minor_mime);
