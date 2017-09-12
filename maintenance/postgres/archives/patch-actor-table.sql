--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table and various columns (and temporary tables) to reference it.

CREATE SEQUENCE actor_actor_id_seq;
CREATE TABLE actor (
  actor_id INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('actor_actor_id_seq'),
  actor_user INTEGER,
  actor_name TEXT NOT NULL
);
CREATE UNIQUE INDEX actor_user ON actor (actor_user);
CREATE UNIQUE INDEX actor_name ON actor (actor_name);

CREATE TABLE revision_actor_temp (
  revactor_rev INTEGER NOT NULL,
  revactor_actor INTEGER NOT NULL,
  PRIMARY KEY (revactor_rev, revactor_actor)
);
CREATE UNIQUE INDEX revactor_rev ON revision_actor_temp (revactor_rev);
CREATE INDEX revactor_actor ON revision_actor_temp (revactor_actor);

CREATE TABLE image_actor_temp (
  imgactor_name TEXT NOT NULL,
  imgactor_actor INTEGER NOT NULL,
  PRIMARY KEY (imgactor_name, imgactor_actor)
);
CREATE UNIQUE INDEX imgactor_name ON image_actor_temp (imgactor_name);
CREATE INDEX imgactor_actor ON image_actor_temp (imgactor_actor);
