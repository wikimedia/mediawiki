CREATE TABLE /*_*/existencelinks (
  exl_from INT UNSIGNED DEFAULT 0 NOT NULL,
  exl_target_id BIGINT UNSIGNED NOT NULL,
  INDEX exl_target_id (exl_target_id, exl_from),
  PRIMARY KEY(exl_from, exl_target_id)
) /*$wgDBTableOptions*/;
