CREATE TABLE protected_titles (
  pt_namespace   SMALLINT    NOT NULL,
  pt_title       TEXT        NOT NULL,
  pt_by          INTEGER     NOT NULL,
  pt_reason      TEXT,
  pt_timestamp   TIMESTAMPTZ NOT NULL,
  pt_expiry      TIMESTAMPTZ     NULL,
  pt_create_perm TEXT        NOT NULL DEFAULT ''
);
CREATE UNIQUE INDEX protected_titles_unique ON protected_titles(pt_namespace, pt_title);
CREATE INDEX protected_titles_index ON protected_titles(pt_by, pt_timestamp);
