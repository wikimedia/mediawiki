-- Table for storing localisation data
CREATE TABLE /*_*/l10n_cache (
  lc_lang varbinary(32) NOT NULL,
  lc_key varchar(255) NOT NULL,
  lc_value mediumblob NOT NULL
);
CREATE INDEX /*i*/lc_lang_key ON /*_*/l10n_cache (lc_lang, lc_key);

