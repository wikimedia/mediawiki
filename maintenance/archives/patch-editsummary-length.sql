ALTER TABLE /*_*/revision MODIFY rev_comment varbinary(767) NOT NULL;
ALTER TABLE /*_*/archive MODIFY ar_comment varbinary(767) NOT NULL;
ALTER TABLE /*_*/image MODIFY img_description varbinary(767) NOT NULL;
ALTER TABLE /*_*/oldimage MODIFY oi_description varbinary(767) NOT NULL;
ALTER TABLE /*_*/filearchive MODIFY fa_description varbinary(767);
ALTER TABLE /*_*/filearchive MODIFY fa_deleted_reason varbinary(767) default '';
ALTER TABLE /*_*/recentchanges MODIFY rc_comment varbinary(767) NOT NULL default '';
ALTER TABLE /*_*/logging MODIFY log_comment varbinary(767) NOT NULL default '';
ALTER TABLE /*_*/ipblocks MODIFY ipb_reason varbinary(767) NOT NULL;
ALTER TABLE /*_*/protected_titles MODIFY pt_reason varbinary(767);

