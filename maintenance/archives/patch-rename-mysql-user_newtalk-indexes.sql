-- T233240: The indexes on `user_newtalk` may be named `un_user_id`/`un_user_ip`
-- or `user_id`/`user_ip`. At least it won't be both or mixed. Rename them to
-- the former.

-- Do not use the /*i*/ hack here!
ALTER TABLE /*_*/user_newtalk
	DROP INDEX user_id,
	DROP INDEX user_ip,
	ADD INDEX un_user_id (user_id),
	ADD INDEX un_user_ip (user_ip);
