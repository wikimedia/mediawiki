-- Adding ar_deleted field for revisiondelete
ALTER TABLE /*$wgDBprefix*/archive
  ADD ar_deleted tinyint(1) unsigned NOT NULL default '0';