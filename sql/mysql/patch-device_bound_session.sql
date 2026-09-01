
CREATE TABLE /*_*/device_bound_session (
  dbs_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  dbs_session VARBINARY(64) NOT NULL,
  dbs_origin VARBINARY(255) NOT NULL,
  dbs_subject VARBINARY(64) NOT NULL,
  dbs_user_token VARBINARY(43) DEFAULT '' NOT NULL,
  dbs_binding BLOB NOT NULL,
  dbs_created BINARY(14) NOT NULL,
  dbs_expires BINARY(14) NOT NULL,
  UNIQUE INDEX dbs_origin_session (dbs_origin, dbs_session),
  INDEX dbs_session (dbs_session),
  INDEX dbs_subject (dbs_subject),
  INDEX dbs_expires (dbs_expires),
  PRIMARY KEY(dbs_id)
) /*$wgDBTableOptions*/;
