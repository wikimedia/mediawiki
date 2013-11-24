-- Patch to add the sites and site_identifiers tables.
-- Licence: GNU GPL v2+
-- Author: Jeroen De Dauw < jeroendedauw@gmail.com >


-- Holds all the sites known to the wiki.
CREATE TABLE IF NOT EXISTS /*_*/sites (
-- Numeric id of the site
  site_id                    INT UNSIGNED        NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Global identifier for the site, ie 'enwiktionary'
  site_global_key            varbinary(32)       NOT NULL,

  -- Type of the site, ie 'mediawiki'
  site_type                  varbinary(32)       NOT NULL,

  -- Group of the site, ie 'wikipedia'
  site_group                 varbinary(32)       NOT NULL,

  -- Source of the site data, ie 'local', 'wikidata', 'my-magical-repo'
  site_source                varbinary(32)       NOT NULL,

  -- Language code of the sites primary language.
  site_language              varbinary(32)       NOT NULL,

  -- Protocol of the site, ie 'http://', 'irc://', '//'
  -- This field is an index for lookups and is build from type specific data in site_data.
  site_protocol              varbinary(32)       NOT NULL,

  -- Domain of the site in reverse order, ie 'org.mediawiki.www.'
  -- This field is an index for lookups and is build from type specific data in site_data.
  site_domain                VARCHAR(255)        NOT NULL,

  -- Type dependent site data.
  site_data                  BLOB                NOT NULL,

  -- If site.tld/path/key:pageTitle should forward users to  the page on
  -- the actual site, where "key" is the local identifier.
  site_forward              bool                NOT NULL,

  -- Type dependent site config.
  -- For instance if template transclusion should be allowed if it's a MediaWiki.
  site_config               BLOB                NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/sites_global_key ON /*_*/sites (site_global_key);
CREATE INDEX /*i*/sites_type ON /*_*/sites (site_type);
CREATE INDEX /*i*/sites_group ON /*_*/sites (site_group);
CREATE INDEX /*i*/sites_source ON /*_*/sites (site_source);
CREATE INDEX /*i*/sites_language ON /*_*/sites (site_language);
CREATE INDEX /*i*/sites_protocol ON /*_*/sites (site_protocol);
CREATE INDEX /*i*/sites_domain ON /*_*/sites (site_domain);
CREATE INDEX /*i*/sites_forward ON /*_*/sites (site_forward);



-- Links local site identifiers to their corresponding site.
CREATE TABLE IF NOT EXISTS /*_*/site_identifiers (
  -- Key on site.site_id
  si_site                    INT UNSIGNED        NOT NULL,

  -- local key type, ie 'interwiki' or 'langlink'
  si_type                    varbinary(32)       NOT NULL,

  -- local key value, ie 'en' or 'wiktionary'
  si_key                     varbinary(32)       NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/site_ids_type ON /*_*/site_identifiers (si_type, si_key);
CREATE INDEX /*i*/site_ids_site ON /*_*/site_identifiers (si_site);
CREATE INDEX /*i*/site_ids_key ON /*_*/site_identifiers (si_key);