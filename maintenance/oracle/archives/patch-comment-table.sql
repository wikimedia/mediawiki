--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table and various columns (and temporary tables) to reference it.

CREATE SEQUENCE comment_comment_id_seq;
CREATE TABLE &mw_prefix."COMMENT" (
  comment_id NUMBER NOT NULL,
  comment_hash NUMBER NOT NULL,
  comment_text CLOB,
  comment_data CLOB
);
CREATE INDEX &mw_prefix.comment_hash ON &mw_prefix."COMMENT" (comment_hash);
/*$mw$*/
CREATE TRIGGER &mw_prefix.comment_seq_trg BEFORE INSERT ON &mw_prefix."COMMENT"
	FOR EACH ROW WHEN (new.comment_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(comment_comment_id_seq.nextval, :new.comment_id);
END;
/*$mw$*/

-- dummy row for FKs. Hash is intentionally wrong so CommentStore won't match it.
INSERT INTO &mw_prefix."COMMENT" (comment_hash, comment_text) VALUES (-1, '** dummy **');


CREATE TABLE &mw_prefix.revision_comment_temp (
  revcomment_rev NUMBER NOT NULL,
  revcomment_comment_id NUMBER NOT NULL
);
ALTER TABLE &mw_prefix.revision_comment_temp ADD CONSTRAINT &mw_prefix.revision_comment_temp_pk PRIMARY KEY (revcomment_rev, revcomment_comment_id);
ALTER TABLE &mw_prefix.revision_comment_temp ADD CONSTRAINT &mw_prefix.revision_comment_temp_fk1 FOREIGN KEY (revcomment_rev) REFERENCES &mw_prefix.revision(rev_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.revision_comment_temp ADD CONSTRAINT &mw_prefix.revision_comment_temp_fk2 FOREIGN KEY (revcomment_comment_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
CREATE UNIQUE INDEX &mw_prefix.revcomment_rev ON &mw_prefix.revision_comment_temp (revcomment_rev);


CREATE TABLE &mw_prefix.image_comment_temp (
  imgcomment_name VARCHAR2(255) NOT NULL,
  imgcomment_description_id NUMBER NOT NULL
);
ALTER TABLE &mw_prefix.image_comment_temp ADD CONSTRAINT &mw_prefix.image_comment_temp_pk PRIMARY KEY (imgcomment_name, imgcomment_description_id);
ALTER TABLE &mw_prefix.image_comment_temp ADD CONSTRAINT &mw_prefix.image_comment_temp_fk1 FOREIGN KEY (imgcomment_name) REFERENCES &mw_prefix.image(img_name) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.image_comment_temp ADD CONSTRAINT &mw_prefix.image_comment_temp_fk2 FOREIGN KEY (imgcomment_description_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
CREATE UNIQUE INDEX &mw_prefix.imgcomment_name ON &mw_prefix.image_comment_temp (imgcomment_name);


ALTER TABLE &mw_prefix.archive ADD COLUMN ar_comment_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.archive ADD CONSTRAINT &mw_prefix.archive_fk2 FOREIGN KEY (ar_comment_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE &mw_prefix.ipblocks ALTER COLUMN ipb_reason VARCHAR2(255) NULL;
ALTER TABLE &mw_prefix.ipblocks ADD COLUMN ipb_reason_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.ipblocks ADD CONSTRAINT &mw_prefix.ipblocks_fk3 FOREIGN KEY (ipb_reason_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE &mw_prefix.oldimage ADD COLUMN oi_description_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.oldimage ADD CONSTRAINT &mw_prefix.oldimage_fk3 FOREIGN KEY (oi_description_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE &mw_prefix.filearchive ADD COLUMN fa_deleted_reason_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.filearchive ADD COLUMN fa_description_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.filearchive ADD CONSTRAINT &mw_prefix.filearchive_fk3 FOREIGN KEY (fa_deleted_reason_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
ALTER TABLE &mw_prefix.filearchive ADD CONSTRAINT &mw_prefix.filearchive_fk4 FOREIGN KEY (fa_description_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE &mw_prefix.recentchanges ADD COLUMN rc_comment_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.recentchanges ADD CONSTRAINT &mw_prefix.recentchanges_fk3 FOREIGN KEY (rc_comment_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE &mw_prefix.logging ADD COLUMN log_comment_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.logging ADD CONSTRAINT &mw_prefix.logging_fk2 FOREIGN KEY (log_comment_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE &mw_prefix.protected_titles ADD COLUMN pt_reason_id NUMBER DEFAULT 0 NOT NULL;
ALTER TABLE &mw_prefix.protected_titles ADD CONSTRAINT &mw_prefix.protected_titles_fk1 FOREIGN KEY (pt_reason_id) REFERENCES &mw_prefix."COMMENT"(comment_id) ON DELETE CASCADE DEFERRABLE INITIALLY DEFERRED;
