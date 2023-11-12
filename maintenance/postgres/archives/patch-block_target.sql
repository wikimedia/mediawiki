
CREATE TABLE block (
  bl_id SERIAL NOT NULL,
  bl_target INT NOT NULL,
  bl_by_actor BIGINT NOT NULL,
  bl_reason_id BIGINT NOT NULL,
  bl_timestamp TIMESTAMPTZ NOT NULL,
  bl_anon_only SMALLINT DEFAULT 0 NOT NULL,
  bl_create_account SMALLINT DEFAULT 1 NOT NULL,
  bl_enable_autoblock SMALLINT DEFAULT 1 NOT NULL,
  bl_expiry TIMESTAMPTZ NOT NULL,
  bl_deleted SMALLINT DEFAULT 0 NOT NULL,
  bl_block_email SMALLINT DEFAULT 0 NOT NULL,
  bl_allow_usertalk SMALLINT DEFAULT 0 NOT NULL,
  bl_parent_block_id INT DEFAULT NULL,
  bl_sitewide SMALLINT DEFAULT 1 NOT NULL,
  PRIMARY KEY(bl_id)
);

CREATE INDEX bl_timestamp ON block (bl_timestamp);

CREATE INDEX bl_target ON block (bl_target);

CREATE INDEX bl_expiry ON block (bl_expiry);

CREATE INDEX bl_parent_block_id ON block (bl_parent_block_id);


CREATE TABLE block_target (
  bt_id SERIAL NOT NULL,
  bt_address TEXT DEFAULT NULL,
  bt_user INT DEFAULT NULL,
  bt_user_text TEXT DEFAULT NULL,
  bt_auto SMALLINT DEFAULT 0 NOT NULL,
  bt_range_start TEXT DEFAULT NULL,
  bt_range_end TEXT DEFAULT NULL,
  bt_ip_hex TEXT DEFAULT NULL,
  bt_count INT DEFAULT 0 NOT NULL,
  PRIMARY KEY(bt_id)
);

CREATE INDEX bt_address ON block_target (bt_address);

CREATE INDEX bt_ip_user_text ON block_target (bt_ip_hex, bt_user_text);

CREATE INDEX bt_range ON block_target (bt_range_start, bt_range_end);

CREATE INDEX bt_user ON block_target (bt_user);
