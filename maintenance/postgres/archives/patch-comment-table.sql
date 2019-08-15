--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table

CREATE SEQUENCE comment_comment_id_seq;
CREATE TABLE comment (
	comment_id   INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('comment_comment_id_seq'),
	comment_hash INTEGER NOT NULL,
	comment_text TEXT    NOT NULL,
	comment_data TEXT
);
CREATE INDEX comment_hash ON comment (comment_hash);
