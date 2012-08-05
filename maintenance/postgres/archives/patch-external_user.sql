CREATE TABLE external_user (
  eu_local_id     INTEGER  NOT NULL  PRIMARY KEY,
  eu_external_id TEXT
);

CREATE UNIQUE INDEX eu_external_id ON external_user (eu_external_id);
