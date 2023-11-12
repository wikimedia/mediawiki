
CREATE TABLE /*_*/block (
  bl_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  bl_target INTEGER UNSIGNED NOT NULL,
  bl_by_actor BIGINT UNSIGNED NOT NULL,
  bl_reason_id BIGINT UNSIGNED NOT NULL,
  bl_timestamp BLOB NOT NULL, bl_anon_only SMALLINT DEFAULT 0 NOT NULL,
  bl_create_account SMALLINT DEFAULT 1 NOT NULL,
  bl_enable_autoblock SMALLINT DEFAULT 1 NOT NULL,
  bl_expiry BLOB NOT NULL, bl_deleted SMALLINT DEFAULT 0 NOT NULL,
  bl_block_email SMALLINT DEFAULT 0 NOT NULL,
  bl_allow_usertalk SMALLINT DEFAULT 0 NOT NULL,
  bl_parent_block_id INTEGER UNSIGNED DEFAULT NULL,
  bl_sitewide SMALLINT DEFAULT 1 NOT NULL
);

CREATE INDEX bl_timestamp ON /*_*/block (bl_timestamp);

CREATE INDEX bl_target ON /*_*/block (bl_target);

CREATE INDEX bl_expiry ON /*_*/block (bl_expiry);

CREATE INDEX bl_parent_block_id ON /*_*/block (bl_parent_block_id);


CREATE TABLE /*_*/block_target (
  bt_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  bt_address BLOB DEFAULT NULL, bt_user INTEGER UNSIGNED DEFAULT NULL,
  bt_user_text BLOB DEFAULT NULL, bt_auto SMALLINT DEFAULT 0 NOT NULL,
  bt_range_start BLOB DEFAULT NULL,
  bt_range_end BLOB DEFAULT NULL, bt_ip_hex BLOB DEFAULT NULL,
  bt_count INTEGER DEFAULT 0 NOT NULL
);

CREATE INDEX bt_address ON /*_*/block_target (bt_address);

CREATE INDEX bt_ip_user_text ON /*_*/block_target (bt_ip_hex, bt_user_text);

CREATE INDEX bt_range ON /*_*/block_target (bt_range_start, bt_range_end);

CREATE INDEX bt_user ON /*_*/block_target (bt_user);
