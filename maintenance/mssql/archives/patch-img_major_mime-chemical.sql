ALTER TABLE /*_*/image
DROP CONSTRAINT img_major_mime_ckc;
ALTER TABLE /*_*/image
WITH NOCHECK ADD CONSTRAINT img_major_mime_ckc CHECK (img_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical'));