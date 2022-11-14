<?php
/** Azerbaijani (azərbaycanca)
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_SPECIAL          => 'Xüsusi',
	NS_TALK             => 'Müzakirə',
	NS_USER             => 'İstifadəçi',
	NS_USER_TALK        => 'İstifadəçi_müzakirəsi',
	NS_PROJECT_TALK     => '$1_müzakirəsi',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_müzakirəsi',
	NS_MEDIAWIKI        => 'MediaViki',
	NS_MEDIAWIKI_TALK   => 'MediaViki_müzakirəsi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_müzakirəsi',
	NS_HELP             => 'Kömək',
	NS_HELP_TALK        => 'Kömək_müzakirəsi',
	NS_CATEGORY         => 'Kateqoriya',
	NS_CATEGORY_TALK    => 'Kateqoriya_müzakirəsi',
];

$namespaceAliases = [ // Kept former namespaces for backwards compatibility - T280577
	'Şəkil'                  => NS_FILE,
	'Şəkil_müzakirəsi'       => NS_FILE_TALK,
	'MediaWiki'              => NS_MEDIAWIKI,
	'MediaWiki_müzakirəsi'   => NS_MEDIAWIKI_TALK,
	'Mediya'                 => NS_MEDIA,
	'MediyaViki'             => NS_MEDIAWIKI,
	'MediyaViki_müzakirəsi'  => NS_MEDIAWIKI_TALK,
];

/** @phpcs-require-sorted-array */
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

/** @phpcs-require-sorted-array */
$magicWords = [
	'nogallery'                 => [ '0', '__QALEREYAYOX__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__MÜNDƏRİCATYOX__', '__NOTOC__' ],
	'redirect'                  => [ '0', '#İSTİQAMƏTLƏNDİRMƏ', '#İSTİQAMƏTLƏNDİR', '#REDIRECT' ],
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
$linkTrail = '/^([a-zçəğıöşü]+)(.*)$/sDu';
