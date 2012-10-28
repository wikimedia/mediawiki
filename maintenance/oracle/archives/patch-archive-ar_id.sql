define mw_prefix='{$wgDBprefix}';

ALTER TABLE &mw_prefix.archive ADD (
ar_id NUMBER NOT NULL,
ar_log_id NUMBER,
ar_log_timestamp TIMESTAMP(6) WITH TIME ZONE  NOT NULL,
ar_log_user NUMBER DEFAULT 0 NOT NULL,
ar_log_user_text VARCHAR2(255) NOT NULL,
ar_log_comment VARCHAR2(255)
);
ALTER TABLE &mw_prefix.archive ADD CONSTRAINT &mw_prefix.archive_pk PRIMARY KEY (ar_id);