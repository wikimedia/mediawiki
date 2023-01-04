-- T161671, T184615, T215466, T326071: Add the replacement actor and comment_id fields.

ALTER TABLE /*_*/revision
	ADD COLUMN rev_comment_id bigint unsigned NOT NULL default 0 AFTER rev_page,
	ADD COLUMN rev_actor bigint unsigned NOT NULL default 0 AFTER rev_comment_id,
	ADD INDEX /*i*/rev_actor_timestamp (rev_actor,rev_timestamp,rev_id),
	ADD INDEX /*i*/rev_page_actor_timestamp (rev_page,rev_actor,rev_timestamp);
