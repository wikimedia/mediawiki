define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.page_restrictions DROP CONSTRAINT &mw_prefix.page_restrictions_pk;

ALTER TABLE &mw_prefix.page_restrictions ADD CONSTRAINT &mw_prefix.page_restrictions_pk PRIMARY KEY (pr_id);

CREATE UNIQUE INDEX &mw_prefix.page_restrictions_u01 ON &mw_prefix.page_restrictions (pr_page,pr_type);
