ALTER TABLE /*$wgDBprefix*/mw_permissions
  ADD COLUMN perm_addgroupstoself LONGTEXT NOT NULL AFTER perm_removegroups,
  ADD COLUMN perm_removegroupsfromself LONGTEXT NOT NULL AFTER perm_addgroupstoself;
