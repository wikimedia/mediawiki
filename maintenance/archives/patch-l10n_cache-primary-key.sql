--
-- patch-l10n_cache-primary-key.sql
--
-- Bug T146591. Add l10n_cache primary key
DROP INDEX IF EXISTS lc_lang_key;

ALTER TABLE /*$wgDBprefix*/l10n_cache ADD PRIMARY KEY (lc_lang, lc_key);
