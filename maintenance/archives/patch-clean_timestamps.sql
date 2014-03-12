-- Cleaning of timestamp fields to a standard varbinary(14)  datatype
ALTER TABLE `user` MODIFY COLUMN user_newpass_time varbinary(14),
	MODIFY COLUMN user_touched varbinary(14) NOT NULL default '',
	MODIFY COLUMN user_email_authenticated varbinary(14),
	MODIFY COLUMN user_email_token_expires varbinary(14),
	MODIFY COLUMN user_registration varbinary(14);
ALTER TABLE `user_newtalk` MODIFY COLUMN user_last_timestamp varbinary(14);
ALTER TABLE `page` MODIFY COLUMN page_touched varbinary(14) NOT NULL default '';
ALTER TABLE `revision` MODIFY COLUMN rev_timestamp varbinary(14) NOT NULL default '';
ALTER TABLE `archive` MODIFY COLUMN ar_timestamp varbinary(14) NOT NULL default '';
ALTER TABLE `ipblocks` MODIFY COLUMN ipb_timestamp varbinary(14) NOT NULL default '',
	MODIFY COLUMN ipb_expiry varbinary(14) NOT NULL default '';
ALTER TABLE `image` MODIFY COLUMN img_timestamp varbinary(14) NOT NULL default '';
ALTER TABLE `oldimage` MODIFY COLUMN oi_timestamp varbinary(14) NOT NULL default '';
ALTER TABLE `filearchive` MODIFY COLUMN fa_deleted_timestamp varbinary(14),
	MODIFY COLUMN fa_timestamp varbinary(14) default '';
ALTER TABLE `uploadstash` MODIFY COLUMN us_timestamp varbinary(14) NOT NULL default '';
ALTER TABLE `recentchanges` MODIFY COLUMN rc_timestamp varbinary(14) NOT NULL default '',
	MODIFY COLUMN rc_cur_time varbinary(14) NOT NULL default '';
ALTER TABLE `watchlist` MODIFY COLUMN wl_notificationtimestamp varbinary(14);
ALTER TABLE `transcache` MODIFY COLUMN tc_time varbinary(14) NOT NULL default '';
ALTER TABLE `logging` MODIFY COLUMN log_timestamp varbinary(14) NOT NULL default '19700101000000';
ALTER TABLE `job` MODIFY COLUMN job_timestamp varbinary(14);
ALTER TABLE `querycache_info` MODIFY COLUMN qci_timestamp varbinary(14) NOT NULL default '19700101000000';
ALTER TABLE `page_restrictions` MODIFY COLUMN pr_expiry varbinary(14);
ALTER TABLE `protected_titles` MODIFY COLUMN pt_timestamp varbinary(14) NOT NULL default '',
	MODIFY COLUMN pt_expiry varbinary(14) NOT NULL default '';
ALTER TABLE `msg_resource` MODIFY COLUMN mr_timestamp varbinary(14) NOT NULL default '';

-- Special treatment for exptime because of trucation issue
DROP TABLE IF EXISTS `objectcache_temp`;
CREATE TABLE `objectcache_temp` like objectcache;
INSERT INTO  objectcache_temp select * from objectcache;
TRUNCATE objectcache;
ALTER TABLE `objectcache` MODIFY COLUMN exptime varbinary(14);
INSERT INTO objectcache(keyname, value, exptime)
select
	keyname,
	value,
	replace(replace(replace(exptime,':',''),' ','') ,'-','')
	from objectcache_temp;
drop table objectcache_temp;
