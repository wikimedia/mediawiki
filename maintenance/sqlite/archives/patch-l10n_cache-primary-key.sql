--
-- patch-l10n_cache-primary-key.sql
--
-- Bug T146591. Add l10n_cache primary key

DROP INDEX /*i*/lc_lang_key ON /*$wgDBprefix*/l10n_cache;

ALTER TABLE /*$wgDBprefix*/l10n_cache ADD PRIMARY KEY(lc_lang, lc_key);
