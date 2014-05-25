-- Add page_lang column

ALTER TABLE /*$wgDBprefix*/page ADD COLUMN page_lang TEXT default NULL;
