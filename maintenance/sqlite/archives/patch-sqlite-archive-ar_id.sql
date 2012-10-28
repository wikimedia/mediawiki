DROP TABLE IF EXISTS /*_*/archive_tmp;

CREATE TABLE /*$wgDBprefix*/archive_tmp (
   ar_id NOT NULL PRIMARY KEY clustered IDENTITY,
   ar_namespace SMALLINT NOT NULL DEFAULT 0,
   ar_title NVARCHAR(255) NOT NULL DEFAULT '',
   ar_text NVARCHAR(MAX) NOT NULL,
   ar_comment NVARCHAR(255) NOT NULL,
   ar_user INT NULL REFERENCES /*$wgDBprefix*/[user](user_id) ON DELETE SET NULL,
   ar_user_text NVARCHAR(255) NOT NULL,
   ar_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   ar_minor_edit BIT NOT NULL DEFAULT 0,
   ar_flags NVARCHAR(255) NOT NULL,
   ar_rev_id INT,
   ar_text_id INT,
   ar_deleted BIT NOT NULL DEFAULT 0,
   ar_len INT DEFAULT NULL,
   ar_page_id INT NULL,
   ar_parent_id INT NULL,
   ar_log_id INT,
   ar_log_timestamp DATETIME NOT NULL DEFAULT GETDATE(),
   ar_log_user INT NULL REFERENCES /*$wgDBprefix*/[user](user_id) ON DELETE SET NULL,
   ar_log_user_text NVARCHAR(255) NOT NULL,
   ar_log_comment NVARCHAR(255) NOT NULL,
);
CREATE INDEX /*$wgDBprefix*/ar_name_title_timestamp ON /*$wgDBprefix*/archive_tmp(ar_namespace,ar_title,ar_timestamp);
CREATE INDEX /*$wgDBprefix*/ar_usertext_timestamp ON /*$wgDBprefix*/archive_tmp(ar_user_text,ar_timestamp);
CREATE INDEX /*$wgDBprefix*/ar_user_text    ON /*$wgDBprefix*/archive_tmp(ar_user_text);

INSERT OR IGNORE INTO /*_*/archive_tmp SELECT * FROM /*_*/archive;

DROP TABLE /*_*/archive;

ALTER TABLE /*_*/archive_tmp RENAME TO /*_*/archive;