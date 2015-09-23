--
-- Add the page_redirect_namespace_len index
--

CREATE INDEX /*i*/page_redirect_namespace_len ON /*_*/page (page_is_redirect, page_namespace, page_len);


