<?php
/** Kurdish (Latin script) (Kurdî (latînî)‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Asoxor
 * @author Bangin
 * @author Erdal Ronahi
 * @author Ferhengvan
 * @author George Animal
 * @author Ghybu
 * @author Gomada
 * @author Kaganer
 * @author Krinkle
 * @author Liangent
 * @author The Evil IP address
 * @author Welathêja
 */

$namespaceNames = [
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Taybet',
	NS_TALK             => 'Gotûbêj',
	NS_USER             => 'Bikarhêner',
	NS_USER_TALK        => 'Gotûbêja_bikarhêner',
	NS_PROJECT_TALK     => '$1_gotûbêj',
	NS_FILE             => 'Wêne',
	NS_FILE_TALK        => 'Gotûbêja_wêneyî',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Gotûbêja_MediaWiki',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Gotûbêja_şablonê',
	NS_HELP             => 'Alîkarî',
	NS_HELP_TALK        => 'Gotûbêja_alîkariyê',
	NS_CATEGORY         => 'Kategorî',
	NS_CATEGORY_TALK    => 'Gotûbêja_kategoriyê',
];

$namespaceAliases = [
	'Nîqaş'            => NS_TALK,
	'Bikarhêner_nîqaş' => NS_USER_TALK,
	'$1_nîqaş'         => NS_PROJECT_TALK,
	'Wêne_nîqaş'       => NS_FILE_TALK,
	'MediaWiki_nîqaş'  => NS_MEDIAWIKI_TALK,
	'Şablon_nîqaş'     => NS_TEMPLATE_TALK,
	'Alîkarî_nîqaş'    => NS_HELP_TALK,
	'Kategorî_nîqaş'   => NS_CATEGORY_TALK,
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];

$specialPageAliases = [
	'Allmessages'               => [ 'Hemû_Peyam' ],
	'Allpages'                  => [ 'Hemû_Rûpel' ],
	'Categories'                => [ 'Dara_kategoriyan' ],
	'DoubleRedirects'           => [ 'Redirect\'ên_ducarî' ],
	'Export'                    => [ 'Eksport' ],
	'Listadmins'                => [ 'Lîsteya_Rêveberan' ],
	'Listbots'                  => [ 'Lîsteya_Botan' ],
	'Listusers'                 => [ 'Lîsteya_bikarhêneran' ],
	'Longpages'                 => [ 'Rûpelên_dirêj' ],
	'MyLanguage'                => [ 'Zimanê_Min' ],
	'Newpages'                  => [ 'Rûpelên_nû' ],
	'Randompage'                => [ 'Rûpela_tesadufî' ],
	'Randomredirect'            => [ 'Redirecta_tasadufî' ],
	'Recentchanges'             => [ 'Guherandinên_dawî' ],
	'Search'                    => [ 'Lêgerîn' ],
	'Shortpages'                => [ 'Rûpelên_kurt' ],
	'Statistics'                => [ 'Statîstîk' ],
	'Uncategorizedcategories'   => [ 'Kategoriyên_bê_kategorî' ],
	'Uncategorizedpages'        => [ 'Rûpelên_bê_kategorî' ],
	'Upload'                    => [ 'Bar_Bike' ],
	'Version'                   => [ 'Versiyon' ],
	'Wantedcategories'          => [ 'Kategorîyên_tên_xwestin' ],
	'Wantedtemplates'           => [ 'Şablonên_tên_xwestin' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#BERALÎKIRIN', '#REDIRECT' ],
	'notoc'                     => [ '0', '_NAVEROKTUNE_', '__NOTOC__' ],
	'nogallery'                 => [ '0', '_GALERÎTUNE_', '__NOGALLERY__' ],
	'toc'                       => [ '0', '_NAVEROK_', '__TOC__' ],
	'currentmonth1'             => [ '1', 'MEHA_NIHA_1', 'CURRENTMONTH1' ],
	'currentday'                => [ '1', 'ROJA_NIHA', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ROJA_NIHA2', 'CURRENTDAY2' ],
	'currenttime'               => [ '1', 'DEMA_NIHA', 'CURRENTTIME' ],
	'numberofpages'             => [ '1', 'HEJMARA_RÛPELAN', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'HEJMARA_GOTARAN', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'HEJMARA_DOSYEYAN', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'HEJMARA_BIKARHÊNERAN', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'HEJMARA_BIKARHÊNERÊN_ÇALAK', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'HEJMARA_GUHERTINAN', 'NUMBEROFEDITS' ],
	'subpagename'               => [ '1', 'BINRÛPEL', 'SUBPAGENAME' ],
	'img_right'                 => [ '1', 'rast', 'right' ],
	'img_left'                  => [ '1', 'çep', 'left' ],
	'img_link'                  => [ '1', 'girêdan=$1', 'link=$1' ],
	'sitename'                  => [ '1', 'NAVÊ_PROJEYÊ', 'SITENAME' ],
	'grammar'                   => [ '0', 'RÊZIMAN.', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'ZAYEND.', 'GENDER:' ],
	'plural'                    => [ '0', 'PIRRJIMAR:', 'PLURAL:' ],
	'currentversion'            => [ '1', 'VERSIYONA_NIHA', 'CURRENTVERSION' ],
	'language'                  => [ '0', '#ZIMAN', '#LANGUAGE:' ],
	'numberofadmins'            => [ '1', 'HEJMARA_RÊVEBERAN', 'NUMBEROFADMINS' ],
	'special'                   => [ '0', 'taybet', 'special' ],
	'pagesincategory_all'       => [ '0', 'hemû', 'all' ],
	'pagesincategory_pages'     => [ '0', 'rûpel', 'pages' ],
];

$linkTrail = '/^([a-zçêîşûẍḧÇÊÎŞÛẌḦ]+)(.*)$/sDu';
