CREATE TABLE /*_*/text_tmp (
  -- Unique text storage key number.
  -- Note that the 'oldid' parameter used in URLs does *not*
  -- refer to this number anymore, but to rev_id.
  --
  -- revision.rev_text_id is a key to this column
  old_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Depending on the contents of the old_flags field, the text
  -- may be convenient plain text, or it may be funkily encoded.
  old_text mediumblob NOT NULL,

  -- Comma-separated list of flags:
  -- gzip: text is compressed with PHP's gzdeflate() function.
  -- utf-8: text was stored as UTF-8.
  --        If $wgLegacyEncoding option is on, rows *without* this flag
  --        will be converted to UTF-8 transparently at load time. Note
  --        that due to a bug in a maintenance script, this flag may
  --        have been stored as 'utf8' in some cases (T18841).
  -- object: text field contained a serialized PHP object.
  --         The object either contains multiple versions compressed
  --         together to achieve a better compression ratio, or it refers
  --         to another row where the text can be found.
  -- external: text was stored in an external location specified by old_text.
  --           Any additional flags apply to the data stored at that URL, not
  --           the URL itself. The 'object' flag is *not* set for URLs of the
  --           form 'DB://cluster/id/itemid', because the external storage
  --           system itself decompresses these.
  old_flags tinyblob NOT NULL
) /*$wgDBTableOptions*/ MAX_ROWS=10000000 AVG_ROW_LENGTH=10240;

INSERT INTO /*_*/text_tmp
	SELECT * FROM /*_*/text;

DROP TABLE /*_*/text;

ALTER TABLE /*_*/text_tmp RENAME TO /*_*/text;