-- Table defining tag names for IDs. Also stores hit counts to avoid expensive queries on change_tag

CREATE TABLE /*_*/change_tag_def (
    -- Numerical ID of the tag (ct_tag_id refers to this)
    ctd_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    -- Symbolic name of the tag (what would previously be put in ct_tag)
    ctd_name varbinary(255) NOT NULL,
    -- Whether this tag was defined manually by a privileged user using Special:Tags
    ctd_user_defined tinyint(1) NOT NULL,
    -- Number of times this tag was used
    ctd_count bigint unsigned NOT NULL default 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/ctd_name ON /*_*/change_tag_def (ctd_name);
CREATE INDEX /*i*/ctd_count ON /*_*/change_tag_def (ctd_count);
CREATE INDEX /*i*/ctd_user_defined ON /*_*/change_tag_def (ctd_user_defined);