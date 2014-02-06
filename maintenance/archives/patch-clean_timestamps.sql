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

-- Special treatment for cl_timestamp because of trucation issue
CREATE TABLE categorylinks_temp like categorylinks;
INSERT INTO  categorylinks_temp select * from categorylinks;
TRUNCATE categorylinks;
ALTER TABLE `categorylinks` MODIFY COLUMN cl_timestamp varbinary(14) NOT NULL default '';
INSERT INTO categorylinks(cl_from, cl_to, cl_sortkey, cl_sortkey_prefix, cl_timestamp, cl_collation, cl_type)
select
	cl_from,
	cl_to,
	cl_sortkey,
	cl_sortkey_prefix,
	replace(replace(replace(cl_timestamp,':',''),' ','') ,'-',''),
	cl_collation,
	cl_type
	from categorylinks_temp;
drop table categorylinks_temp;
