-- T161671, T184615, T215466, T326071: Add the replacement actor and comment_id fields.

BEGIN;

ALTER TABLE /*_*/revision ADD COLUMN rev_comment_id bigint unsigned NOT NULL default 0;
ALTER TABLE /*_*/revision ADD COLUMN rev_actor bigint unsigned NOT NULL default 0;

CREATE INDEX /*i*/rev_actor_timestamp ON /*_*/revision (rev_actor,rev_timestamp,rev_id);
CREATE INDEX /*i*/rev_page_actor_timestamp ON /*_*/revision (rev_page,rev_actor,rev_timestamp);

COMMIT;
