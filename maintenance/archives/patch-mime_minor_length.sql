ALTER TABLE /*_*/filearchive
  MODIFY COLUMN fa_minor_mime varbinary(100) default "unknown";

ALTER TABLE /*_*/image
  MODIFY COLUMN img_minor_mime varbinary(100) NOT NULL default "unknown";

ALTER TABLE /*_*/oldimage
  MODIFY COLUMN oi_minor_mime varbinary(100) NOT NULL default "unknown";

INSERT INTO /*_*/updatelog(ul_key) VALUES ('mime_minor_length');
