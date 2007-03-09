-- Adding ar_deleted field for revisiondelete
ALTER TABLE /*$wgDBprefix*/logging
  ADD log_deleted tinyint(1) unsigned NOT NULL default '0';