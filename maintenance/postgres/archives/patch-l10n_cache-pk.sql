DROP INDEX l10n_cache_lc_lang_key;
ALTER TABLE l10n_cache
 ADD PRIMARY KEY (lc_lang, lc_key);
