-- More statistics, for version 1.14

ALTER TABLE /*$wgDBprefix*/site_stats ADD ss_active_users bigint default '-1';
SELECT @activeusers := COUNT( DISTINCT rc_user_text ) FROM /*$wgDBprefix*/recentchanges 
WHERE rc_user != 0 AND rc_bot = 0 AND rc_log_type != 'newusers';
UPDATE /*$wgDBprefix*/site_stats SET ss_active_users=@activeusers;
