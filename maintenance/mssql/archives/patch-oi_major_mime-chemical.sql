ALTER TABLE /*_*/oldimage
DROP CONSTRAINT oi_major_mime_ckc;
ALTER TABLE /*_*/oldimage
WITH NOCHECK ADD CONSTRAINT oi_major_mime_ckc CHECK (oi_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical'));