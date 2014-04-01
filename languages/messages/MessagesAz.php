<?php
/** Azerbaijani (azərbaycanca)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Xüsusi',
	NS_MAIN             => '',
	NS_TALK             => 'Müzakirə',
	NS_USER             => 'İstifadəçi',
	NS_USER_TALK        => 'İstifadəçi_müzakirəsi',
	NS_PROJECT_TALK     => '$1_müzakirəsi',
	NS_FILE             => 'Şəkil',
	NS_FILE_TALK        => 'Şəkil_müzakirəsi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_müzakirəsi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_müzakirəsi',
	NS_HELP             => 'Kömək',
	NS_HELP_TALK        => 'Kömək_müzakirəsi',
	NS_CATEGORY         => 'Kateqoriya',
	NS_CATEGORY_TALK    => 'Kateqoriya_müzakirəsi',
);

$namespaceAliases = array(
	'Mediya'                 => NS_MEDIA,
	'MediyaViki'             => NS_MEDIAWIKI,
	'MediyaViki_müzakirəsi'  => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktivİstifadəçilər' ),
	'Allpages'                  => array( 'BütünSəhifələr' ),
	'Contributions'             => array( 'Fəaliyyətlər' ),
	'CreateAccount'             => array( 'HesabAç' ),
	'Longpages'                 => array( 'UzunSəhifələr' ),
	'Mycontributions'           => array( 'MənimFəaliyyətlərim' ),
	'Mypage'                    => array( 'MənimSəhifəm' ),
	'Mytalk'                    => array( 'MənimDanışıqlarım' ),
	'Newpages'                  => array( 'YeniSəhifələr' ),
	'Preferences'               => array( 'Nizamlamalar' ),
	'Recentchanges'             => array( 'SonDəyişikliklər' ),
	'Search'                    => array( 'Axtar' ),
	'Shortpages'                => array( 'QısaSəhifələr' ),
	'Specialpages'              => array( 'XüsusiSəhifələr' ),
	'Statistics'                => array( 'Statistika' ),
	'Undelete'                  => array( 'Pozma' ),
	'Version'                   => array( 'Versiya' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#İSTİQAMƏTLƏNDİRMƏ', '#İSTİQAMƏTLƏNDİR', '#REDIRECT' ),
	'notoc'                     => array( '0', '__MÜNDƏRİCATYOX__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__QALEREYAYOX__', '__NOGALLERY__' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

