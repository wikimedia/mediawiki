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
