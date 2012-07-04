-- Patch to add the sites table.
-- Licence: GNU GPL v2+
-- Author: Jeroen De Dauw < jeroendedauw@gmail.com >

CREATE TABLE IF NOT EXISTS /*_*/sites (
-- Numeric id of the site
  site_id                    INT UNSIGNED        NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Global identifier for the site, ie enwiktionary
  site_global_key            VARCHAR(25)         NOT NULL, -- obtained from repo

  -- Type of the site, ie SITE_TYPE_MW
  site_type                  INT UNSIGNED        NOT NULL, -- obtained from repo

  -- Group of the site, ie SITE_GROUP_WIKIPEDIA
  site_group                 INT UNSIGNED        NOT NULL, -- obtained from repo

  -- Base URL of the site, ie http://en.wikipedia.org
  site_url                   VARCHAR(255)        NOT NULL, -- obtained from repo

  -- Path of pages relative to the base url, ie /wiki/$1
  site_page_path             VARCHAR(255)        NOT NULL, -- obtained from repo

  -- Path of files relative to the base url, ie /w/
  site_file_path             VARCHAR(255)        NOT NULL, -- obtained from repo

  -- Language code of the sites primary language.
  -- We do not have real multilingual handling here by design,
  -- as implementing it would require expensive changes in core
  -- and would overcomplicate things. If you have a multilingual
   -- site, for instance imdb, you can just create multiple rows
   -- for it, ie imdben and imdbbe.
  site_language              VARCHAR(10)         NOT NULL, -- obtained from repo

  -- Type dependent site data.
  site_data                  BLOB                NOT NULL, -- obtained from repo

  -- Local identifier for the site, ie en
  site_local_key             VARCHAR(25)         NOT NULL,

  -- If the site should be linkable inline as an "interwiki link" using
  -- [[site_local_key:pageTitle]].
  site_link_inline           bool                NOT NULL,

  -- If equivalent pages of this site should be listed.
  -- For example in the "language links" section.
  site_link_navigation       bool                NOT NULL,

  -- If site.tld/path/key:pageTitle should forward users to  the page on
  -- the actual site, where "key" is the local identifier.
  site_forward               bool                NOT NULL,

  -- Type dependent site config.
  -- For instance if template transclusion should be allowed if it's a MediaWiki.
  site_config                BLOB                NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/sites_global_key ON /*_*/sites (site_global_key);
CREATE INDEX /*i*/sites_type ON /*_*/sites (site_type);
CREATE INDEX /*i*/sites_group ON /*_*/sites (site_group);
CREATE INDEX /*i*/sites_language ON /*_*/sites (site_language);
CREATE UNIQUE INDEX /*i*/sites_local_key ON /*_*/sites (site_local_key);
CREATE INDEX /*i*/sites_link_inline ON /*_*/sites (site_link_inline);
CREATE INDEX /*i*/sites_link_navigation ON /*_*/sites (site_link_navigation);
CREATE INDEX /*i*/sites_forward ON /*_*/sites (site_forward);



