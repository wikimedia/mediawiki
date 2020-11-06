CREATE TABLE /*_*/ip_changes_tmp (
  ipc_rev_id INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ipc_rev_timestamp BLOB NOT NULL,
  ipc_hex BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ipc_rev_id)
);

INSERT INTO /*_*/ip_changes_tmp
  SELECT ipc_rev_id, ipc_rev_timestamp, ipc_hex
  FROM /*_*/ip_changes;
DROP TABLE /*_*/ip_changes;
ALTER TABLE /*_*/ip_changes_tmp RENAME TO /*_*/ip_changes;


CREATE INDEX ipc_rev_timestamp ON /*_*/ip_changes (ipc_rev_timestamp);

CREATE INDEX ipc_hex_time ON /*_*/ip_changes (ipc_hex, ipc_rev_timestamp);
