--
-- Provide default groups
-- Should probably be inserted when someone create a new database
--

INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (1,'Anonymous','Anonymous users','read,edit,createaccount');
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (2,'Loggedin','General logged in users','read,edit,move,upload');
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (3,'Sysops','Operators of this site','read,edit,move,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,sysop');
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (4,'Bureaucrat','The bureaucrat group is able to make sysops for example. They have no other rights.','read,edit,move,delete,undelete,protect,block,userrights,createaccount,upload,asksql,rollback,patrol,editinterface,siteadmin,sysop');
