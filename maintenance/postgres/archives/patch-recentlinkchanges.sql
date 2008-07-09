CREATE SEQUENCE recentlinkchanges_rcl_id_seq;
CREATE TABLE recentlinkchanges (
  rlc_id           INTEGER      NOT NULL  PRIMARY KEY DEFAULT nextval('recentlinkchanges_rcl_id_seq'),
  rlc_type         TEXT         NOT NULL,
  rlc_timestamp    TIMESTAMPTZ  NOT NULL,
  rlc_action       SMALLINT     NOT NULL  DEFAULT 0,
  rlc_from         INTEGER      NOT NULL,
  rlc_to_namespace SMALLINT,
  rlc_to_title     TEXT,
  rlc_to_blob      TEXT
);
CREATE INDEX recentlinkchanges_type ON recentlinkchanges(rlc_type);
