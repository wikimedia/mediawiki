-- Table for storing localisation data
CREATE TABLE /*_*/l10n_cache (
  lc_lang varbinary(35) NOT NULL,
  lc_key varchar(255) NOT NULL,
  lc_value mediumblob NOT NULL,

  PRIMARY KEY(lc_lang, lc_key)
) /*$wgDBTableOptions*/;
