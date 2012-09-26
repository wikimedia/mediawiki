CREATE SEQUENCE sites_site_id_seq;
CREATE TABLE sites (
  site_id           INTEGER     NOT NULL    PRIMARY KEY DEFAULT nextval('sites_site_id_seq'),
  site_global_key   TEXT        NOT NULL,
  site_type         TEXT        NOT NULL,
  site_group        TEXT        NOT NULL,
  site_source       TEXT        NOT NULL,
  site_language     TEXT        NOT NULL,
  site_protocol     TEXT        NOT NULL,
  site_domain       TEXT        NOT NULL,
  site_data         TEXT        NOT NULL,
  site_forward      SMALLINT    NOT NULL,
  site_config       TEXT        NOT NULL
);
CREATE UNIQUE INDEX site_global_key ON sites (site_global_key);
CREATE INDEX site_type ON sites (site_type);
CREATE INDEX site_group ON sites (site_group);
CREATE INDEX site_source ON sites (site_source);
CREATE INDEX site_language ON sites (site_language);
CREATE INDEX site_protocol ON sites (site_protocol);
CREATE INDEX site_domain ON sites (site_domain);
CREATE INDEX site_forward ON sites (site_forward);

CREATE TABLE site_identifiers (
  si_site   INTEGER NOT NULL,
  si_type   TEXT    NOT NULL,
  si_key    TEXT    NOT NULL
);
CREATE UNIQUE INDEX si_type_key ON site_identifiers (si_type, si_key);
CREATE INDEX si_site ON site_identifiers (si_site);
CREATE INDEX si_key ON site_identifiers (si_key);
