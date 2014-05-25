ALTER TABLE /*_*/filearchive
DROP CONSTRAINT CKC__filearchive_fa_major_mime;
ALTER TABLE /*_*/filearchive
WITH NOCHECK ADD CONSTRAINT CKC__filearchive_fa_major_mime CHECK (fa_major_mime IN('unknown', 'application', 'audio', 'image', 'text', 'video', 'message', 'model', 'multipart', 'chemical'));