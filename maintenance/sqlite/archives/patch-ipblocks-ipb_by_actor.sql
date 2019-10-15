BEGIN;

DROP TABLE IF EXISTS ipblocks_tmp;
CREATE TABLE /*_*/ipblocks_tmp (
  ipb_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  ipb_address tinyblob NOT NULL,
  ipb_user int unsigned NOT NULL default 0,
  ipb_by int unsigned NOT NULL default 0,
  ipb_by_text varchar(255) binary NOT NULL default '',
  ipb_by_actor bigint unsigned NOT NULL DEFAULT 0,
  ipb_reason varbinary(767) NOT NULL default '',
  ipb_reason_id bigint unsigned NOT NULL DEFAULT 0,
  ipb_timestamp binary(14) NOT NULL default '',
  ipb_auto bool NOT NULL default 0,
  ipb_anon_only bool NOT NULL default 0,
  ipb_create_account bool NOT NULL default 1,
  ipb_enable_autoblock bool NOT NULL default '1',
  ipb_expiry varbinary(14) NOT NULL default '',
  ipb_range_start tinyblob NOT NULL,
  ipb_range_end tinyblob NOT NULL,
  ipb_deleted bool NOT NULL default 0,
  ipb_block_email bool NOT NULL default 0,
  ipb_allow_usertalk bool NOT NULL default 0,
  ipb_parent_block_id int default NULL
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/ipblocks_tmp (
	ipb_id, ipb_address, ipb_user, ipb_by, ipb_by_text, ipb_reason,
	ipb_timestamp, ipb_auto, ipb_anon_only, ipb_create_account,
	ipb_enable_autoblock, ipb_expiry, ipb_range_start, ipb_range_end,
	ipb_deleted, ipb_block_email, ipb_allow_usertalk, ipb_parent_block_id)
  SELECT
	ipb_id, ipb_address, ipb_user, ipb_by, ipb_by_text, ipb_reason,
	ipb_timestamp, ipb_auto, ipb_anon_only, ipb_create_account,
	ipb_enable_autoblock, ipb_expiry, ipb_range_start, ipb_range_end,
	ipb_deleted, ipb_block_email, ipb_allow_usertalk, ipb_parent_block_id
  FROM /*_*/ipblocks;

DROP TABLE /*_*/ipblocks;
ALTER TABLE /*_*/ipblocks_tmp RENAME TO /*_*/ipblocks;
CREATE UNIQUE INDEX /*i*/ipb_address ON /*_*/ipblocks (ipb_address(255), ipb_user, ipb_auto, ipb_anon_only);
CREATE INDEX /*i*/ipb_user ON /*_*/ipblocks (ipb_user);
CREATE INDEX /*i*/ipb_range ON /*_*/ipblocks (ipb_range_start(8), ipb_range_end(8));
CREATE INDEX /*i*/ipb_timestamp ON /*_*/ipblocks (ipb_timestamp);
CREATE INDEX /*i*/ipb_expiry ON /*_*/ipblocks (ipb_expiry);
CREATE INDEX /*i*/ipb_parent_block_id ON /*_*/ipblocks (ipb_parent_block_id);

COMMIT;
