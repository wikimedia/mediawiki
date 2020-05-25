CREATE TABLE /*$wgDBprefix*/langlinks (
  -- page_id of the referring page
  ll_from int unsigned NOT NULL default '0',

  -- Language code of the target
  ll_lang varbinary(35) NOT NULL default '',

  -- Title of the target, including namespace
  ll_title varchar(255) binary NOT NULL default '',

  PRIMARY KEY (ll_from, ll_lang),
  KEY (ll_lang, ll_title)
) /*$wgDBTableOptions*/;
