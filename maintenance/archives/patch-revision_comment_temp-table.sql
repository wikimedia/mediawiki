CREATE TABLE /*_*/revision_comment_temp (
  revcomment_rev int unsigned NOT NULL,
  revcomment_comment_id bigint unsigned NOT NULL,
  PRIMARY KEY (revcomment_rev, revcomment_comment_id)
) /*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revcomment_rev ON /*_*/revision_comment_temp (revcomment_rev);
