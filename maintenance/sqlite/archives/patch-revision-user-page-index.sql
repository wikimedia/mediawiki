-- New index on revision table to allow searches for all edits by a given user
-- to a given page. Added 2007-08-28

CREATE INDEX /*i*/page_user_timestamp ON /*_*/revision  (rev_page,rev_user,rev_timestamp);
