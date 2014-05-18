<?php
/** Divehi (ދިވެހިބަސް)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'މީޑިއާ',
	NS_SPECIAL          => 'ޚާއްސަ',
	NS_MAIN             => '',
	NS_TALK             => 'ޚިޔާލު',
	NS_USER             => 'މެމްބަރު',
	NS_USER_TALK        => 'މެމްބަރުގެ_ވާހަކަ',
	NS_PROJECT_TALK     => '$1ގެ_ވާހަކަ',
	NS_FILE             => 'ފައިލު',
	NS_FILE_TALK        => 'ފައިލުގެ_ޚިޔާލު',
	NS_MEDIAWIKI        => 'މީޑިއާވިކީ',
	NS_MEDIAWIKI_TALK   => 'މިޑިއާވިކީ_ޚިޔާލު',
	NS_TEMPLATE         => 'ފަންވަތް',
	NS_TEMPLATE_TALK    => 'ފަންވަތުގެ_ޚިޔާލު',
	NS_HELP             => 'އެހީ',
	NS_HELP_TALK        => 'އެހީގެ_ޚިޔާލު',
	NS_CATEGORY         => 'ޤިސްމު',
	NS_CATEGORY_TALK    => 'ޤިސްމުގެ_ޚިޔާލު',
);

$namespaceAliases = array(
	'ހާއްޞަ'          => NS_SPECIAL,
	'ފައިލް'           => NS_FILE,
	'ފައިލް_ޚިޔާލު'    => NS_FILE_TALK,
	'މީޑިޔާވިކި_ޚިޔާލު' => NS_MEDIAWIKI_TALK,
	'ފަންވަތް_ޚިޔާލު'  => NS_TEMPLATE_TALK,
	'އެހީ_ޚިޔާލު'      => NS_HELP_TALK,
	'ޤިސްމު_ޚިޔާލު'   => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ހަރަކާތްތެރި_މެމްބަރުން' ),
	'Allmessages'               => array( 'ހުރިހާ_މެސެޖެއް' ),
	'Allpages'                  => array( 'ހުރިހާ_ޞަފްޙާއެއް' ),
	'Ancientpages'              => array( 'ބާ_ޞަފްޙާތައް' ),
	'Blankpage'                 => array( 'ހުސް_ޞަފްޙާ' ),
	'Contributions'             => array( 'ޙިއްސާ' ),
	'CreateAccount'             => array( 'މެމްބަރުކަން_ހާސިލްކުރައްވާ' ),
	'Emailuser'                 => array( 'މެމްބަރަށް_އީ-މެއިލް_ފޮނުވާ' ),
	'BlockList'                 => array( 'ބްލޮކް_ކުރެވިފައިވާ_ލިސްޓް' ),
	'Listfiles'                 => array( 'ފައިލް_ލިސްޓް' ),
	'Log'                       => array( 'ލޮގު' ),
	'Longpages'                 => array( 'ދިގު_ސަފްޙާތައް' ),
	'Mypage'                    => array( 'މަގޭ_ޞަފްޙާ' ),
	'Mytalk'                    => array( 'މަގޭ_ވާހަކަ' ),
	'Myuploads'                 => array( 'މަގޭ_ފައިލުތައް' ),
	'Newimages'                 => array( 'އާ_ފައިލް' ),
	'Newpages'                  => array( 'އާ_ސަފްހާތައް' ),
	'Preferences'               => array( 'ތަރުޖީހުތައް' ),
	'Protectedpages'            => array( 'ދިފާޢުކުރެވިފައިވާ_ސަފްޙާތައް' ),
	'Protectedtitles'           => array( 'ދިފާޢުކުރެވިފައިވާ_ނަންތައް' ),
	'Randompage'                => array( 'ކޮންމެވެސް_ސަފްޙާއެއް' ),
	'Recentchanges'             => array( 'އެންމެ_ފަހުގެ_ބަދަލުތައް' ),
	'Search'                    => array( 'ހޯއްދަވާ' ),
	'Shortpages'                => array( 'ކުރު_ސަފްޙާތައް' ),
	'Specialpages'              => array( 'ޙާއްސަ_ސަފްޙާތައް' ),
	'Statistics'                => array( 'ތަފާސްހިސާބު' ),
	'Uncategorizedpages'        => array( 'ޤިސްމުކުރެވިފައިނުވާ_ޞަފްޙާތައް' ),
	'Uncategorizedtemplates'    => array( 'ޤިސްމުކުރެވިފައިނުވާ_ފަންވަތް' ),
	'Unusedcategories'          => array( 'ބޭނުން_ނުކުރާ_ޤިސްމުތައް' ),
	'Unusedimages'              => array( 'ބޭނުން_ނުކުރާ_ފައިލް' ),
	'Unusedtemplates'           => array( 'ބޭނުންނުކުރާ_ފަންވަތްތައް' ),
	'Upload'                    => array( 'ފޮނުއްވާ' ),
	'Userlogin'                 => array( 'ވަދެވަޑައިގަންނަވާ' ),
	'Userlogout'                => array( 'ބޭރަށް_ވަޑައިގަންނަވާ' ),
	'Wantedcategories'          => array( 'ބޭނުންވާ_ޤިސްމުތައް' ),
	'Wantedfiles'               => array( 'ބޭނުންވާ_ފައިލުތައް' ),
	'Wantedpages'               => array( 'ބޭނުންވާ_ޞަފްޙާތައް' ),
	'Wantedtemplates'           => array( 'ބޭނުންވާ_ފަންވަތްތައް' ),
	'Watchlist'                 => array( 'މަގޭ_ނަޒަރު' ),
);

