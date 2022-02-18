CREATE TABLE /*_*/linktarget (
  lt_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  lt_namespace INTEGER NOT NULL, lt_title BLOB NOT NULL
);

CREATE UNIQUE INDEX lt_namespace_title ON /*_*/linktarget (lt_namespace, lt_title);
