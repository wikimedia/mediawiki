CREATE SEQUENCE content_models_id_seq;
CREATE TABLE content_models (
  model_id    SMALLINT  NOT NULL PRIMARY KEY nextval('content_models_id_seq'),
  model_name  TEXT      NOT NULL
);

CREATE UNIQUE INDEX model_name ON content_models (model_name);