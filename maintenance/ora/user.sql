define WIKI_USER=&1
define WIKI_PASS=&2
define DEF_TS=&3
define TEMP_TS=&4
create user &&wiki_user identified by &&wiki_pass default tablespace &&def_ts temporary tablespace &&temp_ts quota unlimited on &&def_ts;
grant connect, resource to &&wiki_user;
grant alter session to &&wiki_user;
grant ctxapp to &&wiki_user;
grant execute on ctx_ddl to &&wiki_user;
grant create view to &&wiki_user;
grant create synonym to &&wiki_user;
grant create table to &&wiki_user;
grant create sequence to &&wiki_user;
