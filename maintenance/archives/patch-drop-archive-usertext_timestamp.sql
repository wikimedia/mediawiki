-- T233221: The index on `archive` variously known as `ar_usertext_timestamp`
-- and `usertext_timestamp` has a long and sordid history. We're dropping the
-- `ar_user_text` column entirely now (see patch-drop-user-fields.sql), but
-- this index needs special care thanks to said history.

-- Do not use the /*i*/ thing here!
DROP INDEX usertext_timestamp ON /*_*/archive;
