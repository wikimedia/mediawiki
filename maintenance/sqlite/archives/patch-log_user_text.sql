ALTER TABLE /*$wgDBprefix*/logging ADD COLUMN log_user_text TEXT NOT NULL default '';
ALTER TABLE /*$wgDBprefix*/logging ADD COLUMN log_page INTEGER NULL;

CREATE INDEX /*i*/log_user_type_time ON /*_*/logging (log_user, log_type, log_timestamp);
CREATE INDEX /*i*/log_page_id_time ON /*_*/logging (log_page,log_timestamp);
