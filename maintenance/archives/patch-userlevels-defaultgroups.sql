--
-- Provide default groups
-- Should probably be inserted when someone create a new database
--

INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		1,':group-anon-name',':group-anon-desc',
		'read,edit,createaccount'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		2,':group-loggedin-name',':group-loggedin-desc',
		'read,edit,move,upload,validate,createaccount'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		3,':group-admin-name',':group-loggedin-desc',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,sysop'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		4,':group-bureaucrat-name',':group-bureaucrat-desc',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,sysop,makesysop'
	);
INSERT INTO /*$wgDBprefix*/`group` (group_id,group_name,group_description,group_rights)
	VALUES (
		5,':group-steward-name',':group-steward-desc',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,sysop,makesysop,userrights,grouprights,siteadmin'
	);
