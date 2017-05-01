-- Primary key and expiry column in user_groups table

DROP INDEX IF EXISTS /*i*/ug_user_group ON /*_*/user_groups;
ALTER TABLE /*_*/tag_summary ADD CONSTRAINT pk_user_groups PRIMARY KEY(ug_user, ug_group);
ALTER TABLE /*_*/tag_summary ADD ug_expiry varchar(14) DEFAULT NULL;
CREATE INDEX /*i*/ug_expiry ON /*_*/user_groups(ug_expiry);
