DROP TABLE IF EXISTS /*_*/externallinks_tmp;

CREATE TABLE /*$wgDBprefix*/externallinks_tmp (
   el_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
   el_from int unsigned NOT NULL default 0,
   el_to blob NOT NULL,
   el_index blob NOT NULL
);

INSERT OR IGNORE INTO /*_*/externallinks_tmp (el_from, el_to, el_index) SELECT
    el_from, el_to, el_index FROM /*_*/externallinks;

DROP TABLE /*_*/externallinks;

ALTER TABLE /*_*/externallinks_tmp RENAME TO /*_*/externallinks;

CREATE INDEX /*i*/el_from ON /*_*/externallinks (el_from, el_to(40));
CREATE INDEX /*i*/el_to ON /*_*/externallinks (el_to(60), el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks (el_index(60));