-- Table defining tag names for IDs. Also stores hit counts to avoid expensive queries on change_tag

CREATE TABLE &mw_prefix.change_tag_def (
    -- Numerical ID of the tag (ct_tag_id refers to this)
    ctd_id NUMBER  NOT NULL,
    -- Symbolic name of the tag (what would previously be put in ct_tag)
    ctd_name VARCHAR2(255) NOT NULL,
    -- Whether this tag was defined manually by a privileged user using Special:Tags
    ctd_user_defined CHAR(1) DEFAULT '0' NOT NULL,
    -- Number of times this tag was used
    cts_count NUMBER NOT NULL DEFAULT 0,
    -- Last time this tag was added to something
    cts_timestamp TIMESTAMP(6) WITH TIME ZONE
);

ALTER TABLE &mw_prefix.change_tag_def ADD CONSTRAINT &mw_prefix.change_tag_def_pk PRIMARY KEY (ctd_id);
CREATE UNIQUE INDEX &mw_prefix.ctd_name ON &mw_prefix.change_tag_def (ctd_name);
CREATE INDEX &mw_prefix.ctd_count ON &mw_prefix.change_tag_def (ctd_count);