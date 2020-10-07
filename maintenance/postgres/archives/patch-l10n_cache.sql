CREATE TABLE l10n_cache (
	lc_lang 	TEXT	NOT NULL,
	lc_key		TEXT	NOT NULL,
	lc_value	TEXT	NOT NULL
);
CREATE INDEX l10n_cache_lc_lang_key ON l10n_cache (lc_lang, lc_key);
