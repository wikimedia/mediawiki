-- Reference to page_id. Useful for sysadmin fixing of large
-- pages merged together in the archives
-- Added 2007-07-21

ALTER TABLE /*$wgDBprefix*/archive
  ADD ar_page int unsigned NOT NULL;
