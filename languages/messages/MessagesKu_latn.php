<?php
/** Kurdish (Latin script) (Kurdî (latînî))
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
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

$fallback = 'ku';

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
$minimumGroupingDigits = 2;

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Hemû_Peyam' ],
	'Allpages'                  => [ 'Hemû_Rûpel' ],
	'Categories'                => [ 'Dara_kategoriyan' ],
	'Contributions'             => [ 'Beşdarî' ],
	'DoubleRedirects'           => [ 'Redirect\'ên_ducarî' ],
	'Export'                    => [ 'Eksport' ],
	'Listadmins'                => [ 'Lîsteya_Rêveberan' ],
	'Listbots'                  => [ 'Lîsteya_Botan' ],
	'Listusers'                 => [ 'Lîsteya_bikarhêneran' ],
	'Lonelypages'               => [ 'Rûpelên_sêwî' ],
	'Longpages'                 => [ 'Rûpelên_dirêj' ],
	'MyLanguage'                => [ 'Zimanê_Min' ],
	'Newpages'                  => [ 'Rûpelên_nû' ],
	'Preferences'               => [ 'Tercîh' ],
	'Randompage'                => [ 'Rûpela_tesadufî' ],
	'Randomredirect'            => [ 'Redirecta_tasadufî' ],
	'Recentchanges'             => [ 'Guhertinên_dawî', 'Guherandinên_dawî' ],
	'Search'                    => [ 'Lêgerîn' ],
	'Shortpages'                => [ 'Rûpelên_kurt' ],
	'Statistics'                => [ 'Statîstîk' ],
	'Uncategorizedcategories'   => [ 'Kategoriyên_bê_kategorî' ],
	'Uncategorizedpages'        => [ 'Rûpelên_bê_kategorî' ],
	'Upload'                    => [ 'Bar_Bike' ],
	'Version'                   => [ 'Versiyon' ],
	'Wantedcategories'          => [ 'Kategorîyên_tên_xwestin' ],
	'Wantedpages'               => [ 'Rûpelên_xwestî' ],
	'Wantedtemplates'           => [ 'Şablonên_tên_xwestin' ],
	'Watchlist'                 => [ 'Lîsteya_şopandinê' ],
	'Whatlinkshere'             => [ 'Girêdanên_li_ser_vir' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'ROJA_NIHA', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ROJA_NIHA2', 'CURRENTDAY2' ],
	'currentmonth1'             => [ '1', 'MEHA_NIHA_1', 'CURRENTMONTH1' ],
	'currenttime'               => [ '1', 'DEMA_NIHA', 'CURRENTTIME' ],
	'currentversion'            => [ '1', 'VERSIYONA_NIHA', 'CURRENTVERSION' ],
	'gender'                    => [ '0', 'ZAYEND.', 'GENDER:' ],
	'grammar'                   => [ '0', 'RÊZIMAN.', 'GRAMMAR:' ],
	'img_left'                  => [ '1', 'çep', 'left' ],
	'img_link'                  => [ '1', 'girêdan=$1', 'link=$1' ],
	'img_right'                 => [ '1', 'rast', 'right' ],
	'language'                  => [ '0', '#ZIMAN', '#LANGUAGE:' ],
	'nogallery'                 => [ '0', '_GALERÎTUNE_', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '_NAVEROKTUNE_', '__NOTOC__' ],
	'numberofactiveusers'       => [ '1', 'HEJMARA_BIKARHÊNERÊN_ÇALAK', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'HEJMARA_RÊVEBERAN', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'HEJMARA_GOTARAN', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'HEJMARA_GUHERTINAN', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'HEJMARA_DOSYEYAN', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'HEJMARA_RÛPELAN', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'HEJMARA_BIKARHÊNERAN', 'NUMBEROFUSERS' ],
	'pagesincategory_all'       => [ '0', 'hemû', 'all' ],
	'pagesincategory_pages'     => [ '0', 'rûpel', 'pages' ],
	'plural'                    => [ '0', 'PIRRJIMAR:', 'PLURAL:' ],
	'redirect'                  => [ '0', '#BERALÎKIRIN', '#REDIRECT' ],
	'sitename'                  => [ '1', 'NAVÊ_PROJEYÊ', 'SITENAME' ],
	'special'                   => [ '0', 'taybet', 'special' ],
	'subpagename'               => [ '1', 'BINRÛPEL', 'SUBPAGENAME' ],
	'toc'                       => [ '0', '_NAVEROK_', '__TOC__' ],
];

$linkTrail = '/^([a-zçêîşûẍḧÇÊÎŞÛẌḦ]+)(.*)$/sDu';
