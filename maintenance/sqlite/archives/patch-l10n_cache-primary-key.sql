--
-- patch-l10n_cache-primary-key.sql
--
-- Bug T146591. Add l10n_cache primary key
DROP TABLE IF EXISTS /*_*/l10n_cache;

CREATE TABLE /*$wgDBprefix*/l10n_cache (
    lc_lang varbinary(32) NOT NULL,
    lc_key varchar(255) NOT NULL,
    lc_value mediumblob NOT NULL,
    PRIMARY KEY (lc_lang, lc_key)
) /*$wgDBTableOptions*/;
