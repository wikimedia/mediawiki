CREATE TABLE revision_comment_temp (
	revcomment_rev        INTEGER NOT NULL,
	revcomment_comment_id INTEGER NOT NULL,
	PRIMARY KEY (revcomment_rev, revcomment_comment_id)
);
CREATE UNIQUE INDEX revcomment_rev ON revision_comment_temp (revcomment_rev);
