-- T185355
ALTER TABLE /*_*/change_tag ALTER COLUMN ct_tag_id SET NOT NULL;

DROP INDEX /*i*/change_tag_rc_tag_nonuniq;
DROP INDEX /*i*/change_tag_log_tag_nonuniq;
DROP INDEX /*i*/change_tag_rev_tag_nonuniq;
DROP INDEX /*i*/change_tag_tag_id;

ALTER TABLE /*_*/change_tag DROP COLUMN ct_tag;

