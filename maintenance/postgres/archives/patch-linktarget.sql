CREATE TABLE linktarget (
  lt_id BIGSERIAL NOT NULL,
  lt_namespace INT NOT NULL,
  lt_title TEXT NOT NULL,
  PRIMARY KEY(lt_id)
);

CREATE UNIQUE INDEX lt_namespace_title ON linktarget (lt_namespace, lt_title);
