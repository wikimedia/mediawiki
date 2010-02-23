ALTER TABLE /*$wgDBprefix*/filearchive
  MODIFY COLUMN fa_minor_mime varbinary(100) default "unknown";

ALTER TABLE /*$wgDBprefix*/image
  MODIFY COLUMN img_minor_mime varbinary(100) NOT NULL default "unknown";
  
ALTER TABLE /*$wgDBprefix*/oldimage
  MODIFY COLUMN oi_minor_mime varbinary(100) NOT NULL default "unknown";
  
