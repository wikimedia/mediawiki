--
-- Provide default groups
-- Should probably be inserted when someone create a new database
--

INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		1,'Anonymous','Anonymous users',
		'read,edit,createaccount'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		2,'Logged in','General logged in users',
		'read,edit,move,upload,validate,createaccount'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		3,'Administrator','Trusted users able to block users and delete articles',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,sysop'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		4,'Bureaucrat','The bureaucrat group is able to make sysops. They have no other rights.',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,sysop,makesysop'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		5,'Steward','Full access',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,sysop,makesysop,userrights,grouprights,siteadmin'
	);
