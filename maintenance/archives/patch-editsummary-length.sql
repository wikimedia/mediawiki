ALTER TABLE /*_*/revision MODIFY rev_comment varbinary(/*$wgEditSummaryLength*/) NOT NULL default '';
ALTER TABLE /*_*/archive MODIFY ar_comment varbinary(/*$wgEditSummaryLength*/) NOT NULL default '';
ALTER TABLE /*_*/image MODIFY img_description varbinary(/*$wgEditSummaryLength*/) NOT NULL default '';
ALTER TABLE /*_*/oldimage MODIFY oi_description varbinary(/*$wgEditSummaryLength*/) NOT NULL default '';
ALTER TABLE /*_*/filearchive MODIFY fa_description varbinary(/*$wgEditSummaryLength*/);
ALTER TABLE /*_*/recentchanges MODIFY rc_comment varbinary(/*$wgEditSummaryLength*/) NOT NULL default '';
ALTER TABLE /*_*/logging MODIFY log_comment varbinary(/*$wgEditSummaryLength*/) NOT NULL default '';

