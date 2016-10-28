DROP TABLE IF EXISTS /*_*/l10n_cache_tmp;

CREATE TABLE /*$wgDBprefix*/l10n_cache_tmp (
    lc_lang varbinary(32) NOT NULL,
    lc_key varchar(255) NOT NULL,
    lc_value mediumblob NOT NULL,
    PRIMARY KEY (lc_lang, lc_key)
) /*$wgDBTableOptions*/;

INSERT OR IGNORE INTO /*_*/l10n_cache_tmp (
    lc_lang, lc_key, lc_value )
    SELECT
    lc_lang, lc_key, lc_value
    FROM /*_*/l10n_cache;

DROP TABLE /*_*/l10n_cache;

ALTER TABLE /*_*/l10n_cache_tmp RENAME TO /*_*/l10n_cache;

CREATE INDEX /*i*/lc_lang_key ON /*_*/l10n_cache (lc_lang, lc_key);
