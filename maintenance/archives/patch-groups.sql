CREATE TABLE IF NOT EXISTS /*_*/groups (
  group_internal_name varbinary(32) NOT NULL,
  group_permissions BLOB
) /*$wgDBTableOptions*/;
