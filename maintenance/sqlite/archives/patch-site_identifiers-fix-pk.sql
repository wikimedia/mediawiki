CREATE TABLE /*_*/site_identifiers_tmp (
  -- Key on site.site_id
  si_site                    INT UNSIGNED        NOT NULL,

  -- local key type, ie 'interwiki' or 'langlink'
  si_type                    varbinary(32)       NOT NULL,

  -- local key value, ie 'en' or 'wiktionary'
  si_key                     varbinary(32)       NOT NULL,

  PRIMARY KEY (si_type, si_key)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/site_identifiers_tmp(si_site, si_type, si_key)
	SELECT si_site, si_type, si_key FROM /*_*/site_identifiers;

DROP TABLE /*_*/site_identifiers;

ALTER TABLE /*_*/site_identifiers_tmp RENAME TO /*_*/site_identifiers;

CREATE INDEX /*i*/site_ids_site ON /*_*/site_identifiers (si_site);
CREATE INDEX /*i*/site_ids_key ON /*_*/site_identifiers (si_key);
