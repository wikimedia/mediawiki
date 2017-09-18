CREATE SEQUENCE content_content_id_seq;
CREATE TABLE content (
  content_id      INTEGER   NOT NULL PRIMARY KEY nextval('content_content_id_seq'),
  content_size    INTEGER   NOT NULL,
  content_sha1    TEXT      NULL,
  content_model   SMALLINT  NOT NULL,
  content_address TEXT      NOT NULL
);
