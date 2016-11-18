-- To change the default on one column, sqlite requires we copy the whole table

CREATE TABLE /*_*/externallinks_tmp (
  el_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  el_from int unsigned NOT NULL default 0,
  el_to blob NOT NULL,
  el_index blob NOT NULL,
  el_index_60 varbinary(60) NOT NULL
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/externallinks_tmp
	SELECT el_id, el_from, el_to, el_index, el_index_60 FROM /*_*/externallinks;

DROP TABLE /*_*/externallinks;
ALTER TABLE /*_*/externallinks_tmp RENAME TO /*_*/externallinks;

CREATE INDEX /*i*/el_from ON /*_*/externallinks (el_from, el_to(40));
CREATE INDEX /*i*/el_to ON /*_*/externallinks (el_to(60), el_from);
CREATE INDEX /*i*/el_index ON /*_*/externallinks (el_index(60));
CREATE INDEX /*i*/el_index_60 ON /*_*/externallinks (el_index_60, el_id);
CREATE INDEX /*i*/el_from_index_60 ON /*_*/externallinks (el_from, el_index_60, el_id);
