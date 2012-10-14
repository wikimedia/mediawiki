CREATE SEQUENCE sites_site_id_seq MINVALUE 0 START WITH 0;

CREATE TABLE IF NOT EXISTS sites (
  site_id          INTEGER   NOT NULL PRIMARY KEY DEFAULT nextval('sites_site_id_seq'),
  site_global_key  TEXT      NOT NULL,
  site_type        TEXT      NOT NULL,
  site_group       TEXT      NOT NULL,
  site_source      TEXT      NOT NULL,
  site_language    TEXT      NOT NULL,
  site_protocol    TEXT	     NOT NULL,
  site_domain      TEXT      NOT NULL,
  site_data        TEXT      NOT NULL,
  site_forward     SMALLINT  NOT NULL DEFAULT 0,
  site_config      TEXT      NOT NULL
);

CREATE UNIQUE INDEX sites_global_key ON sites (site_global_key);
CREATE INDEX sites_type ON sites (site_type);
CREATE INDEX sites_group ON sites (site_group);
CREATE INDEX sites_source ON sites (site_source);
CREATE INDEX sites_language ON sites (site_language);
CREATE INDEX sites_protocol ON sites (site_protocol);
CREATE INDEX sites_domain ON sites (site_domain);
CREATE INDEX sites_forward ON sites (site_forward);

CREATE TABLE IF NOT EXISTS /*_*/site_identifiers (
  si_site  INTEGER  NOT NULL,
  si_type  TEXT	    NOT NULL,
  si_key   TEXT	    NOT NULL
);

CREATE UNIQUE INDEX site_ids_type ON site_identifiers (si_type, si_key);
CREATE INDEX site_ids_site ON site_identifiers (si_site);
CREATE INDEX site_ids_key ON site_identifiers (si_key);
