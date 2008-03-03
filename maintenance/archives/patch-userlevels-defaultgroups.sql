--
-- Provide default groups
-- Should probably be inserted when someone create a new database
--

INSERT INTO /*$wgDBprefix*/groups (gr_id,gr_name,gr_description,gr_rights)
	VALUES (
		1,':group-anon-name',':group-anon-desc',
		'read,edit,createaccount'
	);
INSERT INTO /*$wgDBprefix*/groups (gr_id,gr_name,gr_description,gr_rights)
	VALUES (
		2,':group-loggedin-name',':group-loggedin-desc',
		'read,edit,move,upload,validate,createaccount'
	);
INSERT INTO /*$wgDBprefix*/groups (gr_id,gr_name,gr_description,gr_rights)
	VALUES (
		3,':group-admin-name',':group-admin-desc',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import'
	);
INSERT INTO /*$wgDBprefix*/groups (gr_id,gr_name,gr_description,gr_rights)
	VALUES (
		4,':group-bureaucrat-name',':group-bureaucrat-desc',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,makesysop'
	);
INSERT INTO /*$wgDBprefix*/groups (gr_id,gr_name,gr_description,gr_rights)
	VALUES (
		5,':group-steward-name',':group-steward-desc',
		'read,edit,move,upload,validate,createaccount,delete,undelete,protect,block,upload,asksql,rollback,patrol,editinterface,import,makesysop,userrights,grouprights,siteadmin'
	);
