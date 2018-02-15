CREATE SEQUENCE ip_changes_ipc_rev_id_seq;

CREATE TABLE ip_changes (
  ipc_rev_id        INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('ip_changes_ipc_rev_id_seq'),
  ipc_rev_timestamp TIMESTAMPTZ NOT NULL,
  ipc_hex           BYTEA NOT NULL DEFAULT ''
);

CREATE INDEX ipc_rev_timestamp ON ip_changes (ipc_rev_timestamp);
CREATE INDEX ipc_hex_time ON ip_changes (ipc_hex,ipc_rev_timestamp);
