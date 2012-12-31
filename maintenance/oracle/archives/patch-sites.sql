define mw_prefix='{$wgDBprefix}';

CREATE SEQUENCE sites_site_id_seq MINVALUE 0 START WITH 0;
CREATE TABLE &mw_prefix.sites (
  site_id NUMBER NOT NULL,
  site_global_key VARCHAR2(32) NOT NULL,
  site_type VARCHAR2(32) NOT NULL,
  site_group VARCHAR2(32) NOT NULL,
  site_source VARCHAR2(32) NOT NULL,
  site_language VARCHAR2(32) NOT NULL,
  site_protocol VARCHAR2(32) NOT NULL,
  site_domain VARCHAR2(255) NOT NULL,
  site_data BLOB NOT NULL,
  site_forward NUMBER(1) NOT NULL,
  site_config BLOB NOT NULL
);
ALTER TABLE &mw_prefix.sites ADD CONSTRAINT &mw_prefix.sites_pk PRIMARY KEY (site_id);
CREATE UNIQUE INDEX &mw_prefix.sites_u01 ON &mw_prefix.sites (site_global_key);
CREATE INDEX &mw_prefix.sites_i01 ON &mw_prefix.sites (site_type);
CREATE INDEX &mw_prefix.sites_i02 ON &mw_prefix.sites (site_group);
CREATE INDEX &mw_prefix.sites_i03 ON &mw_prefix.sites (site_source);
CREATE INDEX &mw_prefix.sites_i04 ON &mw_prefix.sites (site_language);
CREATE INDEX &mw_prefix.sites_i05 ON &mw_prefix.sites (site_protocol);
CREATE INDEX &mw_prefix.sites_i06 ON &mw_prefix.sites (site_domain);
CREATE INDEX &mw_prefix.sites_i07 ON &mw_prefix.sites (site_forward);

CREATE TABLE &mw_prefix.site_identifiers (
  si_site NUMBER NOT NULL,
  si_type VARCHAR2(32) NOT NULL,
  si_key VARCHAR2(32) NOT NULL
);
CREATE UNIQUE INDEX &mw_prefix.site_identifiers_u01 ON &mw_prefix.site_identifiers (si_type, si_key);
CREATE INDEX &mw_prefix.site_identifiers_i01 ON &mw_prefix.site_identifiers (si_site);
CREATE INDEX &mw_prefix.site_identifiers_i02 ON &mw_prefix.site_identifiers (si_key);
