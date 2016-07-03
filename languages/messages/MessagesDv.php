<?php
/** Divehi (ދިވެހިބަސް)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'މީޑިއާ',
	NS_SPECIAL          => 'ޚާއްސަ',
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
];

$namespaceAliases = [
	'ހާއްޞަ'          => NS_SPECIAL,
	'ފައިލް'           => NS_FILE,
	'ފައިލް_ޚިޔާލު'    => NS_FILE_TALK,
	'މީޑިޔާވިކި_ޚިޔާލު' => NS_MEDIAWIKI_TALK,
	'ފަންވަތް_ޚިޔާލު'  => NS_TEMPLATE_TALK,
	'އެހީ_ޚިޔާލު'      => NS_HELP_TALK,
	'ޤިސްމު_ޚިޔާލު'   => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'ހަރަކާތްތެރި_މެމްބަރުން' ],
	'Allmessages'               => [ 'ހުރިހާ_މެސެޖެއް' ],
	'Allpages'                  => [ 'ހުރިހާ_ޞަފްޙާއެއް' ],
	'Ancientpages'              => [ 'ބާ_ޞަފްޙާތައް' ],
	'Blankpage'                 => [ 'ހުސް_ޞަފްޙާ' ],
	'Contributions'             => [ 'ޙިއްސާ' ],
	'CreateAccount'             => [ 'މެމްބަރުކަން_ހާސިލްކުރައްވާ' ],
	'Emailuser'                 => [ 'މެމްބަރަށް_އީ-މެއިލް_ފޮނުވާ' ],
	'BlockList'                 => [ 'ބްލޮކް_ކުރެވިފައިވާ_ލިސްޓް' ],
	'Listfiles'                 => [ 'ފައިލް_ލިސްޓް' ],
	'Log'                       => [ 'ލޮގު' ],
	'Longpages'                 => [ 'ދިގު_ސަފްޙާތައް' ],
	'Mypage'                    => [ 'މަގޭ_ޞަފްޙާ' ],
	'Mytalk'                    => [ 'މަގޭ_ވާހަކަ' ],
	'Myuploads'                 => [ 'މަގޭ_ފައިލުތައް' ],
	'Newimages'                 => [ 'އާ_ފައިލް' ],
	'Newpages'                  => [ 'އާ_ސަފްހާތައް' ],
	'Preferences'               => [ 'ތަރުޖީހުތައް' ],
	'Protectedpages'            => [ 'ދިފާޢުކުރެވިފައިވާ_ސަފްޙާތައް' ],
	'Protectedtitles'           => [ 'ދިފާޢުކުރެވިފައިވާ_ނަންތައް' ],
	'Randompage'                => [ 'ކޮންމެވެސް_ސަފްޙާއެއް' ],
	'Recentchanges'             => [ 'އެންމެ_ފަހުގެ_ބަދަލުތައް' ],
	'Search'                    => [ 'ހޯއްދަވާ' ],
	'Shortpages'                => [ 'ކުރު_ސަފްޙާތައް' ],
	'Specialpages'              => [ 'ޙާއްސަ_ސަފްޙާތައް' ],
	'Statistics'                => [ 'ތަފާސްހިސާބު' ],
	'Uncategorizedpages'        => [ 'ޤިސްމުކުރެވިފައިނުވާ_ޞަފްޙާތައް' ],
	'Uncategorizedtemplates'    => [ 'ޤިސްމުކުރެވިފައިނުވާ_ފަންވަތް' ],
	'Unusedcategories'          => [ 'ބޭނުން_ނުކުރާ_ޤިސްމުތައް' ],
	'Unusedimages'              => [ 'ބޭނުން_ނުކުރާ_ފައިލް' ],
	'Unusedtemplates'           => [ 'ބޭނުންނުކުރާ_ފަންވަތްތައް' ],
	'Upload'                    => [ 'ފޮނުއްވާ' ],
	'Userlogin'                 => [ 'ވަދެވަޑައިގަންނަވާ' ],
	'Userlogout'                => [ 'ބޭރަށް_ވަޑައިގަންނަވާ' ],
	'Wantedcategories'          => [ 'ބޭނުންވާ_ޤިސްމުތައް' ],
	'Wantedfiles'               => [ 'ބޭނުންވާ_ފައިލުތައް' ],
	'Wantedpages'               => [ 'ބޭނުންވާ_ޞަފްޙާތައް' ],
	'Wantedtemplates'           => [ 'ބޭނުންވާ_ފަންވަތްތައް' ],
	'Watchlist'                 => [ 'މަގޭ_ނަޒަރު' ],
];

