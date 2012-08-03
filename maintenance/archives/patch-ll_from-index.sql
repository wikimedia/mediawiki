-- index ll_from should allow multiy entries per lang,
-- adding ll_title to index
DROP INDEX /*i*/ll_from ON /*_*/langlinks;
CREATE UNIQUE INDEX /*i*/ll_from ON /*_*/langlinks (ll_from, ll_lang, ll_title);