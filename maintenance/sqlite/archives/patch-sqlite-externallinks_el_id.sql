DROP TABLE IF EXISTS /*_*/externallinks_tmp;

CREATE TABLE /*$wgDBprefix*/externallinks_tmp (
   el_id INT NOT NULL PRIMARY KEY clustered IDENTITY,
   el_from INT NOT NULL DEFAULT '0',
   el_to VARCHAR(2083) NOT NULL,
   el_index VARCHAR(896) NOT NULL,
);
-- Maximum key length ON SQL Server is 900 bytes
CREATE INDEX /*$wgDBprefix*/externallinks_index   ON /*$wgDBprefix*/externallinks_tmp(el_index);

INSERT OR IGNORE INTO /*_*/externallinks_tmp SELECT * FROM /*_*/externallinks;

DROP TABLE /*_*/externallinks;

ALTER TABLE /*_*/externallinks_tmp RENAME TO /*_*/externallinks;