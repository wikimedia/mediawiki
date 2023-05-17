CREATE TABLE /*_*/mw_permissions (
  `perm_dbname` VARCHAR(64) NOT NULL,
  `perm_group` VARCHAR(64) NOT NULL,
  `perm_permissions` LONGTEXT NOT NULL,
  `perm_addgroups` LONGTEXT NOT NULL,
  `perm_removegroups` LONGTEXT NOT NULL,
  `perm_addgroupstoself` LONGTEXT NOT NULL,
  `perm_removegroupsfromself` LONGTEXT NOT NULL,
  `perm_autopromote` LONGTEXT,
  UNIQUE KEY `uniqueperm`(perm_dbname,perm_group)
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/perm_dbname ON /*_*/mw_permissions (perm_dbname);
