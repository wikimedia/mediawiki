-- T33223: Remove obsolete ar_text and ar_flags columns
-- (and make ar_text_id not nullable and default 0)

define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive DROP (ar_text, ar_flags);
ALTER TABLE &mw_prefix.archive MODIFY ar_text_id NUMBER DEFAULT 0 NOT NULL;
