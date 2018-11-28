-- T185355
ALTER TABLE &mw_prefix.change_tag MODIFY &mw_prefix.ct_tag_id NUMBER NOT NULL;

DROP INDEX &mw_prefix.change_tag_i03;
DROP INDEX &mw_prefix.change_tag_i04;
DROP INDEX &mw_prefix.change_tag_i05;
DROP INDEX &mw_prefix.change_tag_i01;

ALTER TABLE &mw_prefix.change_tag DROP COLUMN &mw_prefix.ct_tag;
