CREATE TABLE /*_*/content_models_tmp (
  model_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  model_name BLOB NOT NULL
);

INSERT INTO /*_*/content_models_tmp
	SELECT model_id, model_name
		FROM /*_*/content_models;
DROP TABLE /*_*/content_models;
ALTER TABLE /*_*/content_models_tmp RENAME TO /*_*/content_models;
CREATE UNIQUE INDEX model_name ON /*_*/content_models (model_name);
