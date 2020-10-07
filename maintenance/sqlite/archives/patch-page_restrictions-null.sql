-- (T218446) Make page.page_restrictions nullable in preparation for dropping it

BEGIN;
DROP TABLE IF EXISTS /*_*/page_tmp;

CREATE TABLE /*_*/page_tmp (
  page_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  page_namespace int NOT NULL,
  page_title varchar(255) binary NOT NULL,
  page_restrictions tinyblob NULL,
  page_is_redirect tinyint unsigned NOT NULL default 0,
  page_is_new tinyint unsigned NOT NULL default 0,
  page_random real unsigned NOT NULL,
  page_touched binary(14) NOT NULL default '',
  page_links_updated varbinary(14) NULL default NULL,
  page_latest int unsigned NOT NULL,
  page_len int unsigned NOT NULL,
  page_content_model varbinary(32) DEFAULT NULL,
  page_lang varbinary(35) DEFAULT NULL
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
