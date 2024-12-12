
CREATE TABLE /*_*/block (
  bl_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  bl_target INT UNSIGNED NOT NULL,
  bl_by_actor BIGINT UNSIGNED NOT NULL,
  bl_reason_id BIGINT UNSIGNED NOT NULL,
  bl_timestamp BINARY(14) NOT NULL,
  bl_anon_only TINYINT(1) DEFAULT 0 NOT NULL,
  bl_create_account TINYINT(1) DEFAULT 1 NOT NULL,
  bl_enable_autoblock TINYINT(1) DEFAULT 1 NOT NULL,
  bl_expiry VARBINARY(14) NOT NULL,
  bl_deleted TINYINT(1) DEFAULT 0 NOT NULL,
  bl_block_email TINYINT(1) DEFAULT 0 NOT NULL,
  bl_allow_usertalk TINYINT(1) DEFAULT 0 NOT NULL,
  bl_parent_block_id INT UNSIGNED DEFAULT NULL,
  bl_sitewide TINYINT(1) DEFAULT 1 NOT NULL,
  INDEX bl_timestamp (bl_timestamp),
  INDEX bl_target (bl_target),
  INDEX bl_expiry (bl_expiry),
  INDEX bl_parent_block_id (bl_parent_block_id),
  PRIMARY KEY(bl_id)
) /*$wgDBTableOptions*/;


CREATE TABLE /*_*/block_target (
  bt_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
  bt_address TINYBLOB DEFAULT NULL,
  bt_user INT UNSIGNED DEFAULT NULL,
  bt_user_text VARBINARY(255) DEFAULT NULL,
  bt_auto TINYINT(1) DEFAULT 0 NOT NULL,
  bt_range_start TINYBLOB DEFAULT NULL,
  bt_range_end TINYBLOB DEFAULT NULL,
  bt_ip_hex TINYBLOB DEFAULT NULL,
  bt_count INT DEFAULT 0 NOT NULL,
  INDEX bt_address (
    bt_address(42)
  ),
  INDEX bt_ip_user_text (
    bt_ip_hex(35),
    bt_user_text(255)
  ),
  INDEX bt_range (
    bt_range_start(35),
    bt_range_end(35)
  ),
  INDEX bt_user (bt_user),
  PRIMARY KEY(bt_id)
) /*$wgDBTableOptions*/;
