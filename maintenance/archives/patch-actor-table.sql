--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table.

CREATE TABLE /*_*/actor (
  actor_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  actor_user int unsigned,
  actor_name varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX /*i*/actor_name ON /*_*/actor (actor_name);
