--
-- patch-l10n_cache-primary-key.sql
--
-- Bug T146591. Add l10n_cache primary key

DELETE FROM /*$wgDBprefix*/l10n_cache;

ALTER TABLE /*$wgDBprefix*/l10n_cache DROP KEY /*i*/lc_lang_key, ADD PRIMARY KEY(lc_lang, lc_key);
