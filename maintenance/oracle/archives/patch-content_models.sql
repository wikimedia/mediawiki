CREATE SEQUENCE content_models_model_id_seq;
CREATE TABLE &mw_prefix.content_models (
  model_id NUMBER NOT NULL,
  model_name VARCHAR2(64) NOT NULL
);


ALTER TABLE &mw_prefix.content_models ADD CONSTRAINT &mw_prefix.content_models_pk PRIMARY KEY (model_id);

CREATE UNIQUE INDEX &mw_prefix.model_name_u01 ON &mw_prefix.content_models (model_name);

/*$mw$*/
CREATE TRIGGER &mw_prefix.content_models_seq_trg BEFORE INSERT ON &mw_prefix.content_models
	FOR EACH ROW WHEN (new.model_id IS NULL)
BEGIN
	&mw_prefix.lastval_pkg.setLastval(content_models_model_id_seq.nextval, :new.model_id);
END;
/*$mw$*/