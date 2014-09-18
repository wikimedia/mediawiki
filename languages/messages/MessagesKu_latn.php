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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Nîqaş'            => NS_TALK,
	'Bikarhêner_nîqaş' => NS_USER_TALK,
	'$1_nîqaş'         => NS_PROJECT_TALK,
	'Wêne_nîqaş'       => NS_FILE_TALK,
	'MediaWiki_nîqaş'  => NS_MEDIAWIKI_TALK,
	'Şablon_nîqaş'     => NS_TEMPLATE_TALK,
	'Alîkarî_nîqaş'    => NS_HELP_TALK,
	'Kategorî_nîqaş'   => NS_CATEGORY_TALK,
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$specialPageAliases = array(
	'Allmessages'               => array( 'Hemû_Peyam' ),
	'Allpages'                  => array( 'Hemû_Rûpel' ),
	'Categories'                => array( 'Dara_kategoriyan' ),
	'DoubleRedirects'           => array( 'Redirect\'ên_ducarî' ),
	'Export'                    => array( 'Eksport' ),
	'Listadmins'                => array( 'Lîsteya_Rêveberan' ),
	'Listbots'                  => array( 'Lîsteya_Botan' ),
	'Listusers'                 => array( 'Lîsteya_bikarhêneran' ),
	'Longpages'                 => array( 'Rûpelên_dirêj' ),
	'MyLanguage'                => array( 'Zimanê_Min' ),
	'Newpages'                  => array( 'Rûpelên_nû' ),
	'Randompage'                => array( 'Rûpela_tesadufî' ),
	'Randomredirect'            => array( 'Redirecta_tasadufî' ),
	'Recentchanges'             => array( 'Guherandinên_dawî' ),
	'Search'                    => array( 'Lêgerîn' ),
	'Shortpages'                => array( 'Rûpelên_kurt' ),
	'Statistics'                => array( 'Statîstîk' ),
	'Uncategorizedcategories'   => array( 'Kategoriyên_bê_kategorî' ),
	'Uncategorizedpages'        => array( 'Rûpelên_bê_kategorî' ),
	'Upload'                    => array( 'Bar_Bike' ),
	'Version'                   => array( 'Versiyon' ),
	'Wantedcategories'          => array( 'Kategorîyên_tên_xwestin' ),
	'Wantedtemplates'           => array( 'Şablonên_tên_xwestin' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#BERALÎKIRIN', '#REDIRECT' ),
	'notoc'                     => array( '0', '_NAVEROKTUNE_', '__NOTOC__' ),
	'nogallery'                 => array( '0', '_GALERÎTUNE_', '__NOGALLERY__' ),
	'toc'                       => array( '0', '_NAVEROK_', '__TOC__' ),
	'currentmonth1'             => array( '1', 'MEHA_NIHA_1', 'CURRENTMONTH1' ),
	'currentday'                => array( '1', 'ROJA_NIHA', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ROJA_NIHA2', 'CURRENTDAY2' ),
	'currenttime'               => array( '1', 'DEMA_NIHA', 'CURRENTTIME' ),
	'numberofpages'             => array( '1', 'HEJMARA_RÛPELAN', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'HEJMARA_GOTARAN', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'HEJMARA_DOSYEYAN', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'HEJMARA_BIKARHÊNERAN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'HEJMARA_BIKARHÊNERÊN_ÇALAK', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'HEJMARA_GUHERTINAN', 'NUMBEROFEDITS' ),
	'subpagename'               => array( '1', 'BINRÛPEL', 'SUBPAGENAME' ),
	'img_right'                 => array( '1', 'rast', 'right' ),
	'img_left'                  => array( '1', 'çep', 'left' ),
	'img_link'                  => array( '1', 'girêdan=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'NAVÊ_PROJEYÊ', 'SITENAME' ),
	'grammar'                   => array( '0', 'RÊZIMAN.', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'ZAYEND.', 'GENDER:' ),
	'plural'                    => array( '0', 'PIRRJIMAR:', 'PLURAL:' ),
	'currentversion'            => array( '1', 'VERSIYONA_NIHA', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#ZIMAN', '#LANGUAGE:' ),
	'numberofadmins'            => array( '1', 'HEJMARA_RÊVEBERAN', 'NUMBEROFADMINS' ),
	'special'                   => array( '0', 'taybet', 'special' ),
	'pagesincategory_all'       => array( '0', 'hemû', 'all' ),
	'pagesincategory_pages'     => array( '0', 'rûpel', 'pages' ),
);

