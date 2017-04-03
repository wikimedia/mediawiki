-- Rename the archive.ar_usertext_timestamp index to usertext_timestamp.
-- This is for MySQL only and is only necessary on wikis freshly installed on
-- 1.28.0 when bug T154872 was present. The patch will probably be removed in
-- 1.29 since we plan on renaming the index properly to ar_usertext_timestamp.
ALTER TABLE /*$wgDBprefix*/archive
	RENAME INDEX ar_usertext_timestamp TO usertext_timestamp;
