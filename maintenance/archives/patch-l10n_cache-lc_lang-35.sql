ALTER TABLE /*$wgDBprefix*/l10n_cache
	MODIFY `lc_lang`
	VARBINARY(35) NOT NULL;
