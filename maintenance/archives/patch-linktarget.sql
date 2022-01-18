CREATE TABLE /*_*/linktarget (
  lt_id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
  lt_namespace INT NOT NULL,
  lt_title VARBINARY(255) NOT NULL,
  UNIQUE INDEX lt_namespace_title (lt_namespace, lt_title),
  PRIMARY KEY(lt_id)
) /*$wgDBTableOptions*/;
