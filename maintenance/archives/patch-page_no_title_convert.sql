-- Used to prevent title conversion (only for languagues with variants)
-- Set to 1 if the page contains __NOTITLECONVERT__ magic word.
--
-- Added 2006-07-21

ALTER TABLE /*$wgDBprefix*/page
  ADD page_no_title_convert bool NOT NULL default 0;
