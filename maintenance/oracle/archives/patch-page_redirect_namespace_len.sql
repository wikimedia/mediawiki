define mw_prefix='{$wgDBprefix}';

CREATE INDEX &mw_prefix.page_i03 ON &mw_prefix.page (page_is_redirect, page_namespace, page_len);

