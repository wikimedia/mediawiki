-- T161671, T184615, T215466: Drop old revision user, comment, and content fields, and
-- add the replacement actor and comment_id fields.

ALTER TABLE /*_*/revision
	DROP INDEX /*i*/user_timestamp,
	DROP INDEX /*i*/page_user_timestamp,
	DROP INDEX /*i*/usertext_timestamp,
	DROP COLUMN rev_text_id,
	DROP COLUMN rev_comment,
	DROP COLUMN rev_user,
	DROP COLUMN rev_user_text,
	DROP COLUMN rev_content_model,
	DROP COLUMN rev_content_format,
	ADD COLUMN rev_comment_id bigint unsigned NOT NULL default 0 AFTER rev_page,
	ADD COLUMN rev_actor bigint unsigned NOT NULL default 0 AFTER rev_comment_id,
	ADD INDEX /*i*/rev_actor_timestamp (rev_actor,rev_timestamp,rev_id),
	ADD INDEX /*i*/rev_page_actor_timestamp (rev_page,rev_actor,rev_timestamp);
