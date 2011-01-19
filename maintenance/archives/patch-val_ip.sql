-- Column added 2005-05-24

ALTER TABLE /*$wgDBprefix*/validate
  ADD COLUMN val_ip varbinary(20) NOT NULL default '';
