CREATE TABLE config (
  cf_name   TEXT  NOT NULL  PRIMARY KEY,
  cf_value  TEXT  NOT NULL
);
CREATE INDEX cf_name_value ON config (cf_name, cf_value);