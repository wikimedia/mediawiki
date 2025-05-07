CREATE TABLE /*_*/existencelinks (
  exl_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  exl_target_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY(exl_from, exl_target_id)
);

CREATE INDEX exl_target_id ON /*_*/existencelinks (exl_target_id, exl_from);
