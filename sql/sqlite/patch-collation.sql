
CREATE TABLE /*_*/collation (
  collation_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  collation_name BLOB NOT NULL
);

CREATE UNIQUE INDEX collation_name ON /*_*/collation (collation_name);
