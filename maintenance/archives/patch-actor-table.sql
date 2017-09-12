--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table and various columns (and temporary tables) to reference it.

CREATE TABLE /*_*/actor (
  actor_id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  actor_user int unsigned,
  actor_name varchar(255) binary NOT NULL
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX /*i*/actor_name ON /*_*/actor (actor_name);

CREATE TABLE /*_*/revision_actor_temp (
  revactor_rev int unsigned NOT NULL,
  revactor_actor bigint unsigned NOT NULL,
  revactor_timestamp binary(14) NOT NULL default '',
  revactor_page int unsigned NOT NULL,
  PRIMARY KEY (revactor_rev, revactor_actor)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);

ALTER TABLE /*_*/archive
  ALTER COLUMN ar_user_text SET DEFAULT '',
  ADD COLUMN ar_actor bigint unsigned NOT NULL DEFAULT 0 AFTER ar_user_text;
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);

ALTER TABLE /*_*/ipblocks
  ADD COLUMN ipb_by_actor bigint unsigned NOT NULL DEFAULT 0 AFTER ipb_by_text;

ALTER TABLE /*_*/image
  ALTER COLUMN img_user_text SET DEFAULT '',
  ADD COLUMN img_actor bigint unsigned NOT NULL DEFAULT 0 AFTER img_user_text;
CREATE INDEX /*i*/img_actor_timestamp ON /*_*/image (img_actor, img_timestamp);

ALTER TABLE /*_*/oldimage
  ALTER COLUMN oi_user_text SET DEFAULT '',
  ADD COLUMN oi_actor bigint unsigned NOT NULL DEFAULT 0 AFTER oi_user_text;
CREATE INDEX /*i*/oi_actor_timestamp ON /*_*/oldimage (oi_actor,oi_timestamp);

ALTER TABLE /*_*/filearchive
  ALTER COLUMN fa_user_text SET DEFAULT '',
  ADD COLUMN fa_actor bigint unsigned NOT NULL DEFAULT 0 AFTER fa_user_text;
CREATE INDEX /*i*/fa_actor_timestamp ON /*_*/filearchive (fa_actor,fa_timestamp);

ALTER TABLE /*_*/recentchanges
  ALTER COLUMN rc_user_text SET DEFAULT '',
  ADD COLUMN rc_actor bigint unsigned NOT NULL DEFAULT 0 AFTER rc_user_text;
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);

ALTER TABLE /*_*/logging
  ADD COLUMN log_actor bigint unsigned NOT NULL DEFAULT 0 AFTER log_user_text;
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
