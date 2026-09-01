
CREATE TABLE device_bound_session (
  dbs_id BIGSERIAL NOT NULL,
  dbs_session TEXT NOT NULL,
  dbs_origin TEXT NOT NULL,
  dbs_subject TEXT NOT NULL,
  dbs_user_token TEXT DEFAULT '' NOT NULL,
  dbs_binding TEXT NOT NULL,
  dbs_created TIMESTAMPTZ NOT NULL,
  dbs_expires TIMESTAMPTZ NOT NULL,
  PRIMARY KEY(dbs_id)
);

CREATE UNIQUE INDEX dbs_origin_session ON device_bound_session (dbs_origin, dbs_session);

CREATE INDEX dbs_session ON device_bound_session (dbs_session);

CREATE INDEX dbs_subject ON device_bound_session (dbs_subject);

CREATE INDEX dbs_expires ON device_bound_session (dbs_expires);
