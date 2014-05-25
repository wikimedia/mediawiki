ALTER TABLE /*$wgDBprefix*/filearchive
  CHANGE  fa_major_mime fa_major_mime ENUM('unknown','application','audio','image','text','video','message','model','multipart','chemical');

