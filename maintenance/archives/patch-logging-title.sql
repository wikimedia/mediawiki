-- 1.4 betas were missing the 'binary' marker from logging.log_title,
-- which causes a collation mismatch error on joins in MySQL 4.1.

ALTER TABLE /*$wgDBprefix*/logging
  CHANGE COLUMN log_title
    log_title varbinary(255) NOT NULL default '';
