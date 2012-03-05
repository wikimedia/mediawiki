-- defines must comply with ^define\s*([^\s=]*)\s*=\s?'\{\$([^\}]*)\}';
define wiki_user='{$wgDBuser}';
define wiki_pass='{$wgDBpassword}';
define def_ts='{$wgDBOracleDefTS}';
define temp_ts='{$wgDBOracleTempTS}';

create user &wiki_user. identified by &wiki_pass. default tablespace &def_ts. temporary tablespace &temp_ts. quota unlimited on &def_ts.;
grant connect, resource to &wiki_user.;
grant alter session to &wiki_user.;
grant ctxapp to &wiki_user.;
grant execute on ctx_ddl to &wiki_user.;
grant create view to &wiki_user.;
grant create synonym to &wiki_user.;
grant create table to &wiki_user.;
grant create sequence to &wiki_user.;
grant create trigger to &wiki_user.;
