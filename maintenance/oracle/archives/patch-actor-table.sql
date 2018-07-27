--
-- patch-actor-table.sql
--
-- T167246. Add an `actor` table and various columns (and temporary tables) to reference it.

define mw_prefix='{$wgDBprefix}';

CREATE SEQUENCE actor_actor_id_seq;
CREATE TABLE &mw_prefix.actor (
  actor_id NUMBER NOT NULL,
  actor_user NUMBER,
  actor_name VARCHAR2(255) NOT NULL
);

ALTER TABLE &mw_prefix.actor ADD CONSTRAINT &mw_prefix.actor_pk PRIMARY KEY (actor_id);

/*$mw$*/
CREATE TRIGGER &mw_prefix.actor_seq_trg BEFORE INSERT ON &mw_prefix.actor
	FOR EACH ROW WHEN (new.actor_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(actor_actor_id_seq.nextval, :new.actor_id);
END;
/*$mw$*/

-- Create a dummy actor to satisfy fk contraints
INSERT INTO &mw_prefix.actor (actor_id, actor_name) VALUES (0,'##Anonymous##');

CREATE TABLE &mw_prefix.revision_actor_temp (
  revactor_rev NUMBER NOT NULL,
  revactor_actor NUMBER NOT NULL,
  revactor_timestamp TIMESTAMP(6) WITH TIME ZONE NOT NULL,
  revactor_page NUMBER NOT NULL
);
ALTER TABLE &mw_prefix.revision_actor_temp ADD CONSTRAINT &mw_prefix.revision_actor_temp_pk PRIMARY KEY (revactor_rev, revactor_actor);
CREATE UNIQUE INDEX &mw_prefix.revactor_rev ON &mw_prefix.revision_actor_temp (revactor_rev);
CREATE INDEX &mw_prefix.actor_timestamp ON &mw_prefix.revision_actor_temp (revactor_actor,revactor_timestamp);
CREATE INDEX &mw_prefix.page_actor_timestamp ON &mw_prefix.revision_actor_temp (revactor_page,revactor_actor,revactor_timestamp);

ALTER TABLE &mw_prefix.archive MODIFY ( ar_user_text NULL );
ALTER TABLE &mw_prefix.archive ADD ( ar_actor NUMBER DEFAULT 0 NOT NULL );
CREATE INDEX &mw_prefix.ar_actor_timestamp ON &mw_prefix.archive (ar_actor,ar_timestamp);

ALTER TABLE &mw_prefix.ipblocks ADD ( ipb_by_actor NUMBER DEFAULT 0 NOT NULL );

ALTER TABLE &mw_prefix.image MODIFY ( img_user_text NULL );
ALTER TABLE &mw_prefix.image ADD ( img_actor NUMBER DEFAULT 0 NOT NULL );
CREATE INDEX &mw_prefix.img_actor_timestamp ON &mw_prefix.image (img_actor, img_timestamp);

ALTER TABLE &mw_prefix.oldimage MODIFY ( oi_user_text NULL );
ALTER TABLE &mw_prefix.oldimage ADD ( oi_actor NUMBER DEFAULT 0 NOT NULL );
CREATE INDEX &mw_prefix.oi_actor_timestamp ON &mw_prefix.oldimage (oi_actor,oi_timestamp);

ALTER TABLE &mw_prefix.filearchive MODIFY ( fa_user_text NULL );
ALTER TABLE &mw_prefix.filearchive ADD ( fa_actor NUMBER DEFAULT 0 NOT NULL );
CREATE INDEX &mw_prefix.fa_actor_timestamp ON &mw_prefix.filearchive (fa_actor,fa_timestamp);

ALTER TABLE &mw_prefix.recentchanges MODIFY ( rc_user_text NULL );
ALTER TABLE &mw_prefix.recentchanges ADD ( rc_actor NUMBER DEFAULT 0 NOT NULL );
CREATE INDEX &mw_prefix.rc_ns_actor ON &mw_prefix.recentchanges (rc_namespace, rc_actor);
CREATE INDEX &mw_prefix.rc_actor ON &mw_prefix.recentchanges (rc_actor, rc_timestamp);

ALTER TABLE &mw_prefix.logging ADD ( log_actor NUMBER DEFAULT 0 NOT NULL );
CREATE INDEX &mw_prefix.actor_time ON &mw_prefix.logging (log_actor, log_timestamp);
CREATE INDEX &mw_prefix.log_actor_type_time ON &mw_prefix.logging (log_actor, log_type, log_timestamp);
