CREATE TABLE /*_*/langlinks_tmp (
  -- page_id of the referring page
  ll_from int unsigned NOT NULL default 0,

  -- Language code of the target
  ll_lang varbinary(20) NOT NULL default '',

  -- Title of the target, including namespace
  ll_title varchar(255) binary NOT NULL default '',
  PRIMARY KEY (ll_from,ll_lang)
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/langlinks_tmp(ll_from, ll_lang, ll_title)
	SELECT ll_from, ll_lang, ll_title FROM /*_*/langlinks;

DROP TABLE /*_*/langlinks;

ALTER TABLE /*_*/langlinks_tmp RENAME TO /*_*/langlinks;

-- Index for ApiQueryLangbacklinks
CREATE INDEX /*i*/ll_lang ON /*_*/langlinks (ll_lang, ll_title);
