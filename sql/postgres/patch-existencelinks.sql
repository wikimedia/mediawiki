CREATE TABLE existencelinks (
  exl_from INT DEFAULT 0 NOT NULL,
  exl_target_id BIGINT NOT NULL,
  PRIMARY KEY(exl_from, exl_target_id)
);

CREATE INDEX exl_target_id ON existencelinks (exl_target_id, exl_from);
