ALTER TABLE /*_*/image
DROP CONSTRAINT CKC__image_img_major_mime;
ALTER TABLE /*_*/image
WITH NOCHECK ADD CONSTRAINT CKC__image_img_major_mime CHECK (img_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical'));