ALTER TABLE /*$wgDBprefix*/oldimage
  CHANGE  oi_major_mime oi_major_mime ENUM('unknown','application','audio','image','text','video','message','model','multipart','chemical');
