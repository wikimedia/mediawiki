--
-- Optional tables for parserTests recording mode
-- With --record option, success data will be saved to these tables,
-- and comparisons of what's changed from the previous run will be
-- displayed at the end of each run.
--
-- defines must comply with ^define\s*([^\s=]*)\s*=\s?'\{\$([^\}]*)\}';
define mw_prefix='{$wgDBprefix}';

DROP TABLE &mw_prefix.testitem CASCADE CONSTRAINTS;
DROP TABLE &mw_prefix.testrun CASCADE CONSTRAINTS;

CREATE SEQUENCE testrun_tr_id_seq;
CREATE TABLE &mw_prefix.testrun (
  tr_id NUMBER NOT NULL,
  tr_date DATE,
  tr_mw_version BLOB,
  tr_php_version BLOB,
  tr_db_version BLOB,
  tr_uname BLOB,
);
ALTER TABLE &mw_prefix.testrun ADD CONSTRAINT &mw_prefix.testrun_pk PRIMARY KEY (tr_id);
CREATE OR REPLACE TRIGGER &mw_prefix.testrun_bir
BEFORE UPDATE FOR EACH ROW
ON &mw_prefix.testrun
BEGIN
  SELECT testrun_tr_id_seq.NEXTVAL into :NEW.tr_id FROM dual;
END;

CREATE TABLE /*$wgDBprefix*/testitem (
  ti_run NUMBER NOT NULL REFERENCES &mw_prefix.testrun (tr_id) ON DELETE CASCADE,
  ti_name VARCHAR22(255),
  ti_success NUMBER(1)
);
CREATE UNIQUE INDEX &mw_prefix.testitem_u01 ON &mw_prefix.testitem (ti_run, ti_name);
CREATE UNIQUE INDEX &mw_prefix.testitem_u01 ON &mw_prefix.testitem (ti_run, ti_success);

