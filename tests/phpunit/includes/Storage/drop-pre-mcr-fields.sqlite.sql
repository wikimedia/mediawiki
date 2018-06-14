DROP TABLE /*_*/revision;

CREATE TABLE /*_*/revision (
  rev_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  rev_page INTEGER NOT NULL,
  rev_comment BLOB NOT NULL,
  rev_user INTEGER NOT NULL default 0,
  rev_user_text varchar(255) NOT NULL default '',
  rev_timestamp blob(14) NOT NULL default '',
  rev_minor_edit INTEGER NOT NULL default 0,
  rev_deleted INTEGER NOT NULL default 0,
  rev_len INTEGER unsigned,
  rev_parent_id INTEGER default NULL,
  rev_sha1 varbinary(32) NOT NULL default ''
) /*$wgDBTableOptions*/;
