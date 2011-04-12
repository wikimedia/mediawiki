CREATE TABLE l10n_cache (
  -- Language code
  lc_lang			VARCHAR(32) NOT NULL,
  -- Cache key
  lc_key			VARCHAR(255) NOT NULL,
  -- Value
  lc_value			CLOB(16M) INLINE LENGTH 4096 NOT NULL
);
