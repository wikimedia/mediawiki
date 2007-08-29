-- New index on revision table to allow searches for all edits by a given user
-- to a given page. Added 2007-08-28

ALTER TABLE /*$wgDBprefix*/revision
  ADD INDEX page_user_timestamp (rev_page,rev_user,rev_timestamp);
