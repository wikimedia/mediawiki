
CREATE TABLE log_search (
  ls_field TEXT NOT NULL,
  ls_value TEXT NOT NULL,
  ls_log_id INTEGER NOT NULL DEFAULT 0
);

ALTER TABLE log_search ADD CONSTRAINT log_search_pkey PRIMARY KEY(ls_field, ls_value, ls_log_id);
CREATE INDEX ls_log_id ON log_search (ls_log_id);
