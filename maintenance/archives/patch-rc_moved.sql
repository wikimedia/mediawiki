-- rc_moved_to_ns and rc_moved_to_title is no longer used, delete the fields

ALTER TABLE /*$wgDBprefix*/recentchanges DROP COLUMN rc_moved_to_ns,
                                         DROP COLUMN rc_moved_to_title;
