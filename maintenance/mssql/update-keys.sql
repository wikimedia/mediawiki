-- Update keys for Microsoft SQL Server
-- SQL to insert update keys into the initial tables after a
-- fresh installation of MediaWiki's database.
-- This is read and executed by the install script; you should
-- not have to run it by itself unless doing a manual install.
-- Insert keys here if either the unnecessary would cause heavy
-- processing or could potentially cause trouble by lowering field
-- sizes, adding constraints, etc.
-- When adjusting field sizes, it is recommended removing old
-- patches but to play safe, update keys should also inserted here.

--
-- The /*_*/ comments in this and other files are
-- replaced with the defined table prefix by the installer
-- and updater scripts. If you are installing or running
-- updates manually, you will need to manually insert the
-- table prefix if any when running these scripts.
--

INSERT INTO /*_*/updatelog
	SELECT 'filearchive-fa_major_mime-patch-fa_major_mime-chemical.sql' AS ul_key, null as ul_value
	UNION SELECT 'image-img_major_mime-patch-img_major_mime-chemical.sql', null
	UNION SELECT 'oldimage-oi_major_mime-patch-oi_major_mime-chemical.sql', null
	UNION SELECT 'cl_type-category_types-ck', null
	UNION SELECT 'fa_major_mime-major_mime-ck', null
	UNION SELECT 'fa_media_type-media_type-ck', null
	UNION SELECT 'img_major_mime-major_mime-ck', null
	UNION SELECT 'img_media_type-media_type-ck', null
	UNION SELECT 'oi_major_mime-major_mime-ck', null
	UNION SELECT 'oi_media_type-media_type-ck', null
	UNION SELECT 'us_media_type-media_type-ck', null;