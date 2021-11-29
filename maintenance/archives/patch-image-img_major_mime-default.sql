ALTER TABLE  /*_*/image
CHANGE img_major_mime img_major_mime ENUM(
    'unknown', 'application', 'audio',
    'image', 'text', 'video', 'message',
    'model', 'multipart', 'chemical'
  ) DEFAULT 'unknown' NOT NULL;

ALTER TABLE  /*_*/oldimage
CHANGE oi_major_mime oi_major_mime ENUM(
    'unknown', 'application', 'audio',
    'image', 'text', 'video', 'message',
    'model', 'multipart', 'chemical'
  ) DEFAULT 'unknown' NOT NULL;

ALTER TABLE  /*_*/filearchive
CHANGE fa_major_mime fa_major_mime ENUM(
    'unknown', 'application', 'audio',
    'image', 'text', 'video', 'message',
    'model', 'multipart', 'chemical'
  ) DEFAULT 'unknown';
