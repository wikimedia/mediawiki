
CREATE TABLE searchindex (
  si_page INT NOT NULL,
  si_title VARCHAR(255) DEFAULT '' NOT NULL,
  si_text TEXT NOT NULL
);

CREATE UNIQUE INDEX si_page ON searchindex (si_page);

CREATE INDEX si_title ON searchindex (si_title);

CREATE INDEX si_text ON searchindex (si_text);
