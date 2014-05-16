-- rc_cur_time is no longer used, delete the field
ALTER TABLE /*$wgDBprefix*/recentchanges DROP COLUMN rc_cur_time;