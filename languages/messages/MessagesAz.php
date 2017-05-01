<?php
/** Azerbaijani (azərbaycanca)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_SPECIAL          => 'Xüsusi',
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
];

$namespaceAliases = [
	'Mediya'                 => NS_MEDIA,
	'MediyaViki'             => NS_MEDIAWIKI,
	'MediyaViki_müzakirəsi'  => NS_MEDIAWIKI_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktivİstifadəçilər' ],
	'Allpages'                  => [ 'BütünSəhifələr' ],
	'Contributions'             => [ 'Fəaliyyətlər' ],
	'CreateAccount'             => [ 'HesabAç' ],
	'Longpages'                 => [ 'UzunSəhifələr' ],
	'Mycontributions'           => [ 'MənimFəaliyyətlərim' ],
	'Mypage'                    => [ 'MənimSəhifəm' ],
	'Mytalk'                    => [ 'MənimDanışıqlarım' ],
	'Newpages'                  => [ 'YeniSəhifələr' ],
	'Preferences'               => [ 'Nizamlamalar' ],
	'Recentchanges'             => [ 'SonDəyişikliklər' ],
	'Search'                    => [ 'Axtar' ],
	'Shortpages'                => [ 'QısaSəhifələr' ],
	'Specialpages'              => [ 'XüsusiSəhifələr' ],
	'Statistics'                => [ 'Statistika' ],
	'Undelete'                  => [ 'Pozma' ],
	'Version'                   => [ 'Versiya' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#İSTİQAMƏTLƏNDİRMƏ', '#İSTİQAMƏTLƏNDİR', '#REDIRECT' ],
	'notoc'                     => [ '0', '__MÜNDƏRİCATYOX__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__QALEREYAYOX__', '__NOGALLERY__' ],
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];

