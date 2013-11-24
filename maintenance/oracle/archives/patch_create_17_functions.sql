define mw_prefix='{$wgDBprefix}';

/*$mw$*/
CREATE OR REPLACE PROCEDURE duplicate_table(p_tabname   IN VARCHAR2,
                                            p_oldprefix IN VARCHAR2,
                                            p_newprefix IN VARCHAR2,
                                            p_temporary IN BOOLEAN) IS
  e_table_not_exist EXCEPTION;
  PRAGMA EXCEPTION_INIT(e_table_not_exist, -00942);
  l_temp_ei_sql VARCHAR2(2000);
BEGIN
  BEGIN
    EXECUTE IMMEDIATE 'DROP TABLE ' || p_newprefix || p_tabname ||
                      ' CASCADE CONSTRAINTS';
  EXCEPTION
    WHEN e_table_not_exist THEN
      NULL;
  END;
  IF (p_temporary) THEN
    EXECUTE IMMEDIATE 'CREATE GLOBAL TEMPORARY TABLE ' || p_newprefix ||
                      p_tabname || ' AS SELECT * FROM ' || p_oldprefix ||
                      p_tabname || ' WHERE ROWNUM = 0';
  ELSE
    EXECUTE IMMEDIATE 'CREATE TABLE ' || p_newprefix || p_tabname ||
                      ' AS SELECT * FROM ' || p_oldprefix || p_tabname ||
                      ' WHERE ROWNUM = 0';
  END IF;
  FOR rc IN (SELECT column_name, data_default
               FROM user_tab_columns
              WHERE table_name = p_oldprefix || p_tabname
                AND data_default IS NOT NULL) LOOP
    EXECUTE IMMEDIATE 'ALTER TABLE ' || p_newprefix || p_tabname ||
                      ' MODIFY ' || rc.column_name || ' DEFAULT ' ||
                      SUBSTR(rc.data_default, 1, 2000);
  END LOOP;
  FOR rc IN (SELECT REPLACE(REPLACE(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('CONSTRAINT',
                                                                          constraint_name),
                                                    32767,
                                                    1),
                                    USER || '"."' || p_oldprefix,
                                    USER || '"."' || p_newprefix),
                            '"' || constraint_name || '"',
                            '"' || p_newprefix || constraint_name || '"') DDLVC2,
                    constraint_name
               FROM user_constraints uc
              WHERE table_name = p_oldprefix || p_tabname
                AND constraint_type = 'P') LOOP
    l_temp_ei_sql := SUBSTR(rc.ddlvc2, 1, INSTR(rc.ddlvc2, 'PCTFREE') - 1);
    l_temp_ei_sql := SUBSTR(l_temp_ei_sql, 1, INSTR(l_temp_ei_sql, ')', INSTR(l_temp_ei_sql, 'PRIMARY KEY')+1)+1);
    EXECUTE IMMEDIATE l_temp_ei_sql;
  END LOOP;
  IF (NOT p_temporary) THEN
  FOR rc IN (SELECT REPLACE(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('REF_CONSTRAINT',
                                                                  constraint_name),
                                            32767,
                                            1),
                            USER || '"."' || p_oldprefix,
                            USER || '"."' || p_newprefix) DDLVC2,
                    constraint_name
               FROM user_constraints uc
              WHERE table_name = p_oldprefix || p_tabname
                AND constraint_type = 'R') LOOP
    EXECUTE IMMEDIATE rc.ddlvc2;
  END LOOP;
  END IF;
  FOR rc IN (SELECT REPLACE(REPLACE(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('INDEX',
                                                                          index_name),
                                                    32767,
                                                    1),
                                    USER || '"."' || p_oldprefix,
                                    USER || '"."' || p_newprefix),
                            '"' || index_name || '"',
                            '"' || p_newprefix || index_name || '"') DDLVC2,
                    index_name,
                    index_type
               FROM user_indexes ui
              WHERE table_name = p_oldprefix || p_tabname
                AND index_type NOT IN ('LOB', 'DOMAIN')
                AND NOT EXISTS
              (SELECT NULL
                       FROM user_constraints
                      WHERE table_name = ui.table_name
                        AND constraint_name = ui.index_name)) LOOP
    l_temp_ei_sql := SUBSTR(rc.ddlvc2, 1, INSTR(rc.ddlvc2, 'PCTFREE') - 1);
    l_temp_ei_sql := SUBSTR(l_temp_ei_sql, 1, INSTR(l_temp_ei_sql, ')', INSTR(l_temp_ei_sql, '"' || USER || '"."' || p_newprefix || '"')+1)+1);
    EXECUTE IMMEDIATE l_temp_ei_sql;
  END LOOP;
  FOR rc IN (SELECT REPLACE(REPLACE(UPPER(DBMS_LOB.SUBSTR(DBMS_METADATA.get_ddl('TRIGGER',
                                                                                trigger_name),
                                                          32767,
                                                          1)),
                                    USER || '"."' || p_oldprefix,
                                    USER || '"."' || p_newprefix),
                            ' ON ' || p_oldprefix || p_tabname,
                            ' ON ' || p_newprefix || p_tabname) DDLVC2,
                    trigger_name
               FROM user_triggers
              WHERE table_name = p_oldprefix || p_tabname) LOOP
    l_temp_ei_sql := SUBSTR(rc.ddlvc2, 1, INSTR(rc.ddlvc2, 'ALTER ') - 1);
    dbms_output.put_line(l_temp_ei_sql);
    EXECUTE IMMEDIATE l_temp_ei_sql;
  END LOOP;
END;
/*$mw$*/

CREATE OR REPLACE TYPE GET_OUTPUT_TYPE IS TABLE OF VARCHAR2(255);

/*$mw$*/
CREATE OR REPLACE FUNCTION GET_OUTPUT_LINES RETURN GET_OUTPUT_TYPE PIPELINED AS
  v_line VARCHAR2(255);
  v_status INTEGER := 0;
BEGIN

  LOOP
    DBMS_OUTPUT.GET_LINE(v_line, v_status);
    IF (v_status = 0) THEN RETURN; END IF;
    PIPE ROW (v_line);
  END LOOP;
  RETURN;
EXCEPTION
  WHEN OTHERS THEN
    RETURN;
END;
/*$mw$*/

