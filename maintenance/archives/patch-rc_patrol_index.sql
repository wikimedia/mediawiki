-- Index to speed up locating unpatrolled changes
-- matching specific edit criteria
ALTER TABLE /*$wgDBprefix*/recentchanges
	ADD INDEX `rc_patrolling` ( `rc_this_oldid` , `rc_last_oldid` , `rc_patrolled` );