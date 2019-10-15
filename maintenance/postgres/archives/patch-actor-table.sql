--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table.

CREATE SEQUENCE actor_actor_id_seq;
CREATE TABLE actor (
  actor_id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('actor_actor_id_seq'),
  actor_user INTEGER,
  actor_name TEXT NOT NULL
);
CREATE UNIQUE INDEX actor_user ON actor (actor_user);
CREATE UNIQUE INDEX actor_name ON actor (actor_name);
