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
  revactor_timestamp TIMESTAMPTZ  NOT NULL,
  revactor_page INTEGER          NULL  REFERENCES page (page_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED,
  PRIMARY KEY (revactor_rev, revactor_actor)
);
CREATE UNIQUE INDEX revactor_rev ON revision_actor_temp (revactor_rev);
CREATE INDEX rev_actor_timestamp ON revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX rev_page_actor_timestamp ON revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);
