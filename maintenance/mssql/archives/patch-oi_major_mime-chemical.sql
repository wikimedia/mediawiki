ALTER TABLE /*_*/oldimage
DROP CONSTRAINT CKC__oldimage_oi_major_mime;
ALTER TABLE /*_*/oldimage
WITH NOCHECK ADD CONSTRAINT CKC__oldimage_oi_major_mime CHECK (oi_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical'));