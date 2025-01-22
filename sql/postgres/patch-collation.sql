CREATE TABLE "collation" (
  collation_id SMALLSERIAL NOT NULL,
  collation_name TEXT NOT NULL,
  PRIMARY KEY(collation_id)
);

CREATE UNIQUE INDEX collation_name ON "collation" (collation_name);
