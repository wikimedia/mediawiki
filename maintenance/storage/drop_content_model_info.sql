ALTER TABLE /*$wgDBprefix*/archive  DROP COLUMN ar_content_model;
ALTER TABLE /*$wgDBprefix*/archive  DROP COLUMN ar_content_format;

ALTER TABLE /*$wgDBprefix*/revision  DROP COLUMN rev_content_model;
ALTER TABLE /*$wgDBprefix*/revision  DROP COLUMN rev_content_format;

ALTER TABLE /*$wgDBprefix*/page  DROP COLUMN page_content_model;
