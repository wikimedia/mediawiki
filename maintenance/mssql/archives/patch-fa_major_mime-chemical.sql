ALTER TABLE /*_*/filearchive
DROP CONSTRAINT fa_major_mime_ckc;
ALTER TABLE /*_*/filearchive
WITH NOCHECK ADD CONSTRAINT fa_major_mime_ckc CHECK (fa_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical'));