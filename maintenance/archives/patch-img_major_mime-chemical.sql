ALTER TABLE /*$wgDBprefix*/image
  CHANGE  img_major_mime img_major_mime ENUM('unknown','application','audio','image','text','video','message','model','multipart','chemical');

