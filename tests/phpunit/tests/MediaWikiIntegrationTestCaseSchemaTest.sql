CREATE TABLE /*_*/MediaWikiIntegrationTestCaseTestTable (
  id INT NOT NULL,
  name VARCHAR(20) NOT NULL,
  PRIMARY KEY (id)
) /*$wgDBTableOptions*/;

CREATE TABLE /*_*/imagelinks (
  il_from int NOT NULL DEFAULT 0,
  il_from_namespace int NOT NULL DEFAULT 0,
  il_to varchar(127) NOT NULL DEFAULT '',
  il_frobnitz varchar(127) NOT NULL DEFAULT 'FROB',
  PRIMARY KEY (il_from,il_to)
) /*$wgDBTableOptions*/;

ALTER TABLE /*_*/pagelinks
ADD pl_frobnitz varchar(127) NOT NULL DEFAULT 'FROB';

DROP TABLE /*_*/oldimage;
