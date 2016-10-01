-- Primary key in l10n_cache table

ALTER TABLE /*_*/l10n_cache ADD lc_id INT IDENTITY;
ALTER TABLE /*_*/l10n_cache ADD CONSTRAINT pk_l10n_cache PRIMARY KEY(lc_id)
