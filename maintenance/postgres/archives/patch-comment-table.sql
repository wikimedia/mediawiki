--
-- patch-comment-table.sql
--
-- T166732. Add a `comment` table, and temporary tables to reference it.

CREATE SEQUENCE comment_comment_id_seq;
CREATE TABLE comment (
	comment_id   INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('comment_comment_id_seq'),
	comment_hash INTEGER NOT NULL,
	comment_text TEXT    NOT NULL,
	comment_data TEXT
);
CREATE INDEX comment_hash ON comment (comment_hash);

CREATE TABLE revision_comment_temp (
	revcomment_rev        INTEGER NOT NULL,
	revcomment_comment_id INTEGER NOT NULL,
	PRIMARY KEY (revcomment_rev, revcomment_comment_id)
);
CREATE UNIQUE INDEX revcomment_rev ON revision_comment_temp (revcomment_rev);

CREATE TABLE image_comment_temp (
	imgcomment_name       TEXT NOT NULL,
	imgcomment_description_id INTEGER NOT NULL,
	PRIMARY KEY (imgcomment_name, imgcomment_description_id)
);
CREATE UNIQUE INDEX imgcomment_name ON image_comment_temp (imgcomment_name);
