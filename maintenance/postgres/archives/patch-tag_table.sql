CREATE SEQUENCE tag_tag_id_seq;
CREATE TABLE tag (
  tag_id        INTEGER  NOT NULL PRIMARY KEY DEFAULT nextval('tag_tag_id_seq'),
  tag_name      TEXT     NOT NULL,
  tag_count     INTEGER  NOT NULL DEFAULT 0,
  tag_timestamp TIMESTAMPTZ  NULL
);
CREATE UNIQUE INDEX tag_name_idx ON tag(tag_name);
CREATE INDEX tag_count_idx ON tag(tag_count);
