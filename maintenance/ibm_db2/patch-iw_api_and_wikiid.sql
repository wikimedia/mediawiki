CREATE TABLE interwiki (
  iw_prefix  VARCHAR(32)      NOT NULL  UNIQUE,
  iw_url     CLOB(64K) INLINE LENGTH 4096      NOT NULL,
  iw_api     CLOB(64K) INLINE LENGTH 4096      NOT NULL,
  iw_wikiid  varchar(64) NOT NULL,
  iw_local   SMALLINT  NOT NULL,
  iw_trans   SMALLINT  NOT NULL  DEFAULT 0
);
