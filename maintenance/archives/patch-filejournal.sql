-- File backend operation journal
CREATE TABLE /*_*/filejournal (
  -- Unique ID for each file operation
  fj_id bigint unsigned NOT NULL PRIMARY KEY auto_increment,
  -- UUID of the batch this operation belongs to
  fj_batch_uuid varbinary(32) NOT NULL,
  -- The registered file backend name
  fj_backend varchar(255) NOT NULL,
  -- The storage path that was affected (may be internal paths)
  fj_path blob NOT NULL,
  -- Primitive operation description (create/update/delete)
  fj_op varchar(16) NOT NULL default '',
  -- SHA-1 file content hash in base-36
  fj_new_sha1 varbinary(32) NOT NULL default '',
  -- Timestamp of the batch operation
  fj_timestamp varbinary(14) NOT NULL default ''
) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/fj_batch_id ON /*_*/filejournal (fj_batch_uuid);
CREATE INDEX /*i*/fj_timestamp ON /*_*/filejournal (fj_timestamp);
