define mw_prefix='{$wgDBprefix}';

CREATE TABLE &mw_prefix.config (
  cf_name VARCHAR2(255) NOT NULL,
  cf_value blob NOT NULL
);
ALTER TABLE &mw_prefix.config ADD CONSTRAINT &mw_prefix.config_pk PRIMARY KEY (cf_name);

