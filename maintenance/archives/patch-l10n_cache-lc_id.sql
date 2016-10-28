--
-- patch-l10n_cache-lc_id.sql
--
-- Bug T146591. Add l10n_cache.lc_id.

ALTER TABLE /*$wgDBprefix*/l10n_cache ADD PRIMARY KEY(lc_lang, lc_key);
