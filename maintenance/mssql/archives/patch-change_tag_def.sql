-- Table defining tag names for IDs. Also stores hit counts to avoid expensive queries on change_tag

CREATE TABLE /*_*/change_tag_def (
    -- Numerical ID of the tag (ct_tag_id refers to this)
    ctd_id int NOT NULL PRIMARY KEY IDENTITY,
    -- Symbolic name of the tag (what would previously be put in ct_tag)
    ctd_name nvarchar(255) NOT NULL,
    -- Whether this tag was defined manually by a privileged user using Special:Tags
    ctd_user_defined tinyint NOT NULL default 0,
    -- Number of times this tag was used
    cts_count int NOT NULL CONSTRAINT DF_cts_count DEFAULT 0,
    -- Last time this tag was added to something
    ctd_timestamp nvarchar(14) NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ctd_name ON /*_*/change_tag_def (ctd_name);
CREATE INDEX /*i*/ctd_count ON /*_*/change_tag_def (ctd_count);