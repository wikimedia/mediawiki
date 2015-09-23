CREATE SEQUENCE page_page_id_seq;
CREATE TABLE page (
  page_id            INTEGER        NOT NULL  PRIMARY KEY DEFAULT nextval('page_page_id_seq'),
  page_namespace     SMALLINT       NOT NULL,
  page_title         TEXT           NOT NULL,
  page_restrictions  TEXT,
  page_counter       BIGINT         NOT NULL  DEFAULT 0,
  page_is_redirect   SMALLINT       NOT NULL  DEFAULT 0,
  page_is_new        SMALLINT       NOT NULL  DEFAULT 0,
  page_random        NUMERIC(15,14) NOT NULL  DEFAULT RANDOM(),
  page_touched       TIMESTAMPTZ,
  page_latest        INTEGER        NOT NULL,
  page_len           INTEGER        NOT NULL
);
CREATE UNIQUE INDEX page_unique_name ON page (page_namespace, page_title);
CREATE INDEX page_main_title         ON page (page_title) WHERE page_namespace = 0;
CREATE INDEX page_talk_title         ON page (page_title) WHERE page_namespace = 1;
CREATE INDEX page_user_title         ON page (page_title) WHERE page_namespace = 2;
CREATE INDEX page_utalk_title        ON page (page_title) WHERE page_namespace = 3;
CREATE INDEX page_project_title      ON page (page_title) WHERE page_namespace = 4;
CREATE INDEX page_mediawiki_title    ON page (page_title) WHERE page_namespace = 8;
CREATE INDEX page_random_idx         ON page (page_random);
CREATE INDEX page_len_idx            ON page (page_len);

