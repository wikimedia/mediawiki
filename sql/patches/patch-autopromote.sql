ALTER TABLE /*$wgDBprefix*/mw_permissions
  ADD COLUMN perm_autopromote LONGTEXT AFTER perm_removegroupsfromself;
