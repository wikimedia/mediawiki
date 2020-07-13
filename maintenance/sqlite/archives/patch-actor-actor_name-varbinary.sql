CREATE TABLE /*_*/actor_tmp (
  actor_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  actor_user INTEGER UNSIGNED DEFAULT NULL,
  actor_name BLOB NOT NULL
);
INSERT INTO /*_*/actor_tmp
	SELECT actor_id, actor_user, actor_name
		FROM /*_*/actor;
DROP TABLE /*_*/actor;
ALTER TABLE /*_*/actor_tmp RENAME TO /*_*/actor;

CREATE UNIQUE INDEX actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX actor_name ON /*_*/actor (actor_name);
