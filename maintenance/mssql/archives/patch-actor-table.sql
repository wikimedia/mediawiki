--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table and various columns (and temporary tables) to reference it.

CREATE TABLE /*_*/actor (
  actor_id bigint unsigned NOT NULL CONSTRAINT PK_actor PRIMARY KEY IDENTITY(0,1),
  actor_user int unsigned,
  actor_name nvarchar(255) NOT NULL
);
CREATE UNIQUE INDEX /*i*/actor_user ON /*_*/actor (actor_user);
CREATE UNIQUE INDEX /*i*/actor_name ON /*_*/actor (actor_name);

-- Dummy
INSERT INTO /*_*/actor (actor_name) VALUES ('##Anonymous##');

CREATE TABLE /*_*/revision_actor_temp (
  revactor_rev int unsigned NOT NULL CONSTRAINT FK_revactor_rev FOREIGN KEY REFERENCES /*_*/revision(rev_id) ON DELETE CASCADE,
  revactor_actor bigint unsigned NOT NULL,
  revactor_timestamp varchar(14) NOT NULL CONSTRAINT DF_revactor_timestamp DEFAULT '',
  revactor_page int unsigned NOT NULL,
  CONSTRAINT PK_revision_actor_temp PRIMARY KEY (revactor_rev, revactor_actor)
);
CREATE UNIQUE INDEX /*i*/revactor_rev ON /*_*/revision_actor_temp (revactor_rev);
CREATE INDEX /*i*/actor_timestamp ON /*_*/revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX /*i*/page_actor_timestamp ON /*_*/revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);

ALTER TABLE /*_*/archive ADD CONSTRAINT DF_ar_user_text DEFAULT '' FOR ar_user_text;
ALTER TABLE /*_*/archive ADD ar_actor bigint unsigned NOT NULL CONSTRAINT DF_ar_actor DEFAULT 0;
CREATE INDEX /*i*/ar_actor_timestamp ON /*_*/archive (ar_actor,ar_timestamp);

ALTER TABLE /*_*/ipblocks ADD ipb_by_actor bigint unsigned NOT NULL CONSTRAINT DF_ipb_by_actor DEFAULT 0;

ALTER TABLE /*_*/image ADD CONSTRAINT DF_img_user_text DEFAULT '' FOR img_user_text;
ALTER TABLE /*_*/image ADD img_actor bigint unsigned NOT NULL CONSTRAINT DF_img_actor DEFAULT 0;
CREATE INDEX /*i*/img_actor_timestamp ON /*_*/image (img_actor, img_timestamp);

ALTER TABLE /*_*/oldimage ADD CONSTRAINT DF_oi_user_text DEFAULT '' FOR oi_user_text;
ALTER TABLE /*_*/oldimage ADD oi_actor bigint unsigned NOT NULL CONSTRAINT DF_oi_actor DEFAULT 0;
CREATE INDEX /*i*/oi_actor_timestamp ON /*_*/oldimage (oi_actor,oi_timestamp);

ALTER TABLE /*_*/filearchive ADD CONSTRAINT DF_fa_user_text DEFAULT '' FOR fa_user_text;
ALTER TABLE /*_*/filearchive ADD fa_actor bigint unsigned NOT NULL CONSTRAINT DF_fa_actor DEFAULT 0;
CREATE INDEX /*i*/fa_actor_timestamp ON /*_*/filearchive (fa_actor,fa_timestamp);

ALTER TABLE /*_*/recentchanges ADD CONSTRAINT DF_rc_user_text DEFAULT '' FOR rc_user_text;
ALTER TABLE /*_*/recentchanges ADD rc_actor bigint unsigned NOT NULL CONSTRAINT DF_rc_actor DEFAULT 0;
CREATE INDEX /*i*/rc_ns_actor ON /*_*/recentchanges (rc_namespace, rc_actor);
CREATE INDEX /*i*/rc_actor ON /*_*/recentchanges (rc_actor, rc_timestamp);

ALTER TABLE /*_*/logging ADD log_actor bigint unsigned NOT NULL CONSTRAINT DF_log_actor DEFAULT 0;
CREATE INDEX /*i*/actor_time ON /*_*/logging (log_actor, log_timestamp);
CREATE INDEX /*i*/log_actor_type_time ON /*_*/logging (log_actor, log_type, log_timestamp);
