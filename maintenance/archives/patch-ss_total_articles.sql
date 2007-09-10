-- Faster statistics, as of 1.4.3

ALTER TABLE /*$wgDBprefix*/site_stats
  ADD ss_total_pages bigint(20) default -1,
  ADD ss_users bigint(20) default -1,
  ADD ss_admins int(10) default -1;
