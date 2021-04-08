CREATE TABLE /*_*/langlinks_tmp (
  ll_from INTEGER UNSIGNED DEFAULT 0 NOT NULL,
  ll_lang BLOB DEFAULT '' NOT NULL,
  ll_title BLOB DEFAULT '' NOT NULL,
  PRIMARY KEY(ll_from, ll_lang)
);

INSERT INTO /*_*/langlinks_tmp
	SELECT ll_from, ll_lang, ll_title
		FROM /*_*/langlinks;
DROP TABLE /*_*/langlinks;
ALTER TABLE /*_*/langlinks_tmp RENAME TO /*_*/langlinks;

CREATE INDEX ll_lang ON /*_*/langlinks (ll_lang, ll_title);
