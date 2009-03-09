-- Adding user_hidden field for revisiondelete
ALTER TABLE /*$wgDBprefix*/user
  ADD user_hidden bool NOT NULL default 0;
