-- T161671, T184615, T215466: Drop old revision user, comment, and content fields.

ALTER TABLE /*_*/revision
	DROP INDEX /*i*/user_timestamp,
	DROP INDEX /*i*/page_user_timestamp,
	DROP INDEX /*i*/usertext_timestamp,
	DROP COLUMN rev_text_id,
	DROP COLUMN rev_comment,
	DROP COLUMN rev_user,
	DROP COLUMN rev_user_text,
	DROP COLUMN rev_content_model,
	DROP COLUMN rev_content_format;
