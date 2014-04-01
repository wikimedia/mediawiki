<?php
/** Gheg Albanian (Gegë)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'sq';

$namespaceNames = array(
	NS_SPECIAL          => 'Speciale',
	NS_TALK             => 'Diskutim',
	NS_USER             => 'Përdorues',
	NS_USER_TALK        => 'Përdoruesi_diskutim',
	NS_PROJECT_TALK     => '$1_diskutim',
	NS_FILE             => 'Skeda',
	NS_FILE_TALK        => 'Skeda_diskutim',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskutim',
	NS_TEMPLATE         => 'Stampa',
	NS_TEMPLATE_TALK    => 'Stampa_diskutim',
	NS_HELP             => 'Ndihmë',
	NS_HELP_TALK        => 'Ndihmë_diskutim',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Kategoria_diskutim',
);

$namespaceAliases = array(
	'Perdoruesi'          => NS_USER,
	'Perdoruesi_diskutim' => NS_USER_TALK,
	'Përdoruesi'          => NS_USER,
	'Përdoruesi_diskutim' => NS_USER_TALK,
	'Figura'              => NS_FILE,
	'Figura_diskutim'     => NS_FILE_TALK,
	'Kategori'            => NS_CATEGORY,
	'Kategori_Diskutim'   => NS_CATEGORY_TALK
);

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Përdoruesi', 'female' => 'Përdoruesja' ),
	NS_USER_TALK => array( 'male' => 'Përdoruesi_diskutim', 'female' => 'Përdoruesja_diskutim' ),
);

$specialPageAliases = array(
	'Popularpages'              => array( 'Faqe të famshme' ),
	'Search'                    => array( 'Kërko' ),
);

$magicWords = array(
	'currentmonth'              => array( '1', 'MUEJIAKTUAL', 'MUEJIAKTUAL2', 'MUAJIMOMENTAL', 'MUAJIMOMENTAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'MUEJIAKTUAL1', 'MUAJIMOMENTAL1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'EMNIMUEJITAKTUAL', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ),
	'currenttime'               => array( '1', 'KOHATASH', 'KOHATANI', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ORATASH', 'ORATANI', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MUEJILOKAL', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'img_center'                => array( '1', 'qendër', 'qendrore', 'qëndër', 'qëndrore', 'center', 'centre' ),
	'img_baseline'              => array( '1', 'vijabazë', 'linjabazë', 'baseline' ),
	'servername'                => array( '0', 'EMNISERVERIT', 'EMRIISERVERIT', 'SERVERNAME' ),
	'currentweek'               => array( '1', 'JAVAAKTUALE', 'JAVAMOMENTALE', 'CURRENTWEEK' ),
);

