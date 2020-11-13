CREATE TABLE /*_*/revision_actor_temp_tmp (
  revactor_rev INTEGER UNSIGNED NOT NULL,
  revactor_actor BIGINT UNSIGNED NOT NULL,
  revactor_timestamp BLOB NOT NULL,
  revactor_page INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(revactor_rev, revactor_actor)
);

INSERT INTO /*_*/revision_actor_temp_tmp
  SELECT revactor_rev, revactor_actor, revactor_timestamp, revactor_page
    FROM /*_*/revision_actor_temp;
DROP TABLE /*_*/revision_actor_temp;
ALTER TABLE /*_*/revision_actor_temp_tmp RENAME TO /*_*/revision_actor_temp;

CREATE UNIQUE INDEX revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX actor_timestamp ON /*_*/revision_actor_temp (revactor_actor, revactor_timestamp);
CREATE INDEX page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page, revactor_actor, revactor_timestamp);
