define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.l10n_cache ADD (
lc_id NUMBER NOT NULL,
);
ALTER TABLE &mw_prefix.l10n_cache ADD CONSTRAINT &mw_prefix.l10n_cache_pk PRIMARY KEY (lc_id);
