-- Clean the table first otherwise setting datatype of exptime would fail.
DELETE FROM /*_*/objectcache;
ALTER TABLE /*_*/objectcache
  MODIFY exptime BINARY(14) NOT NULL;
