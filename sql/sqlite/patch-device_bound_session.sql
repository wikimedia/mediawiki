
CREATE TABLE /*_*/device_bound_session (
  dbs_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  dbs_session BLOB NOT NULL,
  dbs_origin BLOB NOT NULL,
  dbs_subject BLOB NOT NULL,
  dbs_user_token BLOB DEFAULT '' NOT NULL,
  dbs_binding BLOB NOT NULL,
  dbs_created BLOB NOT NULL,
  dbs_expires BLOB NOT NULL
);

CREATE UNIQUE INDEX dbs_origin_session ON /*_*/device_bound_session (dbs_origin, dbs_session);

CREATE INDEX dbs_session ON /*_*/device_bound_session (dbs_session);

CREATE INDEX dbs_subject ON /*_*/device_bound_session (dbs_subject);

CREATE INDEX dbs_expires ON /*_*/device_bound_session (dbs_expires);
