define mw_prefix='{$wgDBprefix}';

CREATE TABLE &mw_prefix.tag (
  tag_id NUMBER NOT NULL,
  tag_name VARCHAR2(255) NOT NULL,
  tag_count NUMBER NOT NULL DEFAULT 0,
  tag_timestamp TIMESTAMP(6) WITH TIME ZONE
);

ALTER TABLE &mw_prefix.tag ADD CONSTRAINT &mw_prefix.tag_pk PRIMARY KEY (tag_id);
CREATE INDEX &mw_prefix.tag_i01 ON &mw_prefix.tag (tag_count);
