-- Table defining tag names for IDs. Also stores hit counts to avoid expensive queries on change_tag
CREATE SEQUENCE change_tag_def_ctd_id_seq;
CREATE TABLE &mw_prefix.change_tag_def (
    -- Numerical ID of the tag (ct_tag_id refers to this)
    ctd_id NUMBER  NOT NULL,
    -- Symbolic name of the tag (what would previously be put in ct_tag)
    ctd_name VARCHAR2(255) NOT NULL,
    -- Whether this tag was defined manually by a privileged user using Special:Tags
    ctd_user_defined CHAR(1) DEFAULT '0' NOT NULL,
    -- Number of times this tag was used
    ctd_count NUMBER NOT NULL DEFAULT 0
);

ALTER TABLE &mw_prefix.change_tag_def ADD CONSTRAINT &mw_prefix.change_tag_def_pk PRIMARY KEY (ctd_id);
CREATE UNIQUE INDEX &mw_prefix.ctd_name ON &mw_prefix.change_tag_def (ctd_name);
CREATE INDEX &mw_prefix.ctd_count ON &mw_prefix.change_tag_def (ctd_count);
CREATE INDEX &mw_prefix.ctd_user_defined ON &mw_prefix.change_tag_def (ctd_user_defined);

/*$mw$*/
CREATE TRIGGER &mw_prefix.change_tag_def_seq_trg BEFORE INSERT ON &mw_prefix.change_tag_def
    FOR EACH ROW WHEN (new.ctd_id IS NULL)
BEGIN
    &mw_prefix.lastval_pkg.setLastval(change_tag_def_ctd_id_seq.nextval, :new.ctd_id);
END;
/*$mw$*/