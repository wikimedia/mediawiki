DROP INDEX change_tag_rc_tag_nonuniq;
DROP INDEX change_tag_log_tag_nonuniq;
DROP INDEX change_tag_rev_tag_nonuniq;
DROP INDEX change_tag_tag_id;
ALTER TABLE change_tag DROP ct_tag;
