-- (T218446) Make page.page_restrictions nullable in preparation for dropping it

BEGIN;
DROP TABLE IF EXISTS /*_*/page_tmp;

CREATE TABLE /*_*/page_tmp (
  page_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
  page_namespace INTEGER NOT NULL,
  page_title TEXT  NOT NULL,
  page_restrictions BLOB NULL,
  page_is_redirect INTEGER  NOT NULL default 0,
  page_is_new INTEGER  NOT NULL default 0,
  page_random real  NOT NULL,
  page_touched BLOB NOT NULL default '',
  page_links_updated BLOB NULL default NULL,
  page_latest INTEGER  NOT NULL,
  page_len INTEGER  NOT NULL,
  page_content_model BLOB DEFAULT NULL,
  page_lang BLOB DEFAULT NULL
) /*$wgDBTableOptions*/;

INSERT INTO /*_*/page_tmp
SELECT page_id, page_namespace, page_title, page_restrictions, page_is_redirect,
       page_is_new, page_random, page_touched, page_links_updated, page_latest, page_len,
       page_content_model, page_lang
FROM /*_*/page;

DROP TABLE /*_*/page;

ALTER TABLE /*_*/page_tmp RENAME TO /*_*/page;

CREATE UNIQUE INDEX /*i*/name_title ON /*_*/page (page_namespace,page_title);
CREATE INDEX /*i*/page_random ON /*_*/page (page_random);
CREATE INDEX /*i*/page_len ON /*_*/page (page_len);
CREATE INDEX /*i*/page_redirect_namespace_len ON /*_*/page (page_is_redirect, page_namespace, page_len);

COMMIT;
