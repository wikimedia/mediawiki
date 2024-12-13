DROP INDEX name_title_timestamp;
CREATE INDEX ar_name_title_timestamp ON  /*_*/archive (ar_namespace,ar_title,ar_timestamp);
