CREATE TABLE /*_*/collation (
  collation_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL,
  collation_name VARBINARY(64) NOT NULL,
  UNIQUE INDEX collation_name (collation_name),
  PRIMARY KEY(collation_id)
) /*$wgDBTableOptions*/;
