-- T185355
ALTER TABLE /*_*/change_tag MODIFY ct_tag_id int unsigned NOT NULL;

DROP INDEX /*i*/change_tag_rc_tag_nonuniq ON /*_*/change_tag;
DROP INDEX /*i*/change_tag_log_tag_nonuniq ON /*_*/change_tag;
DROP INDEX /*i*/change_tag_rev_tag_nonuniq ON /*_*/change_tag;
DROP INDEX /*i*/change_tag_tag_id ON /*_*/change_tag;

ALTER TABLE /*_*/change_tag DROP COLUMN ct_tag;
