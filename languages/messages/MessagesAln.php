<?php
/** Gheg Albanian (Gegë)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'sq';

$namespaceNames = [
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
];

$namespaceAliases = [
	'Perdoruesi'          => NS_USER,
	'Perdoruesi_diskutim' => NS_USER_TALK,
	'Përdoruesi'          => NS_USER,
	'Përdoruesi_diskutim' => NS_USER_TALK,
	'Figura'              => NS_FILE,
	'Figura_diskutim'     => NS_FILE_TALK,
	'Kategori'            => NS_CATEGORY,
	'Kategori_Diskutim'   => NS_CATEGORY_TALK
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Përdoruesi', 'female' => 'Përdoruesja' ],
	NS_USER_TALK => [ 'male' => 'Përdoruesi_diskutim', 'female' => 'Përdoruesja_diskutim' ],
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Search'                    => [ 'Kërko' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currenthour'               => [ '1', 'ORATASH', 'ORATANI', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'MUEJIAKTUAL', 'MUEJIAKTUAL2', 'MUAJIMOMENTAL', 'MUAJIMOMENTAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MUEJIAKTUAL1', 'MUAJIMOMENTAL1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'EMNIMUEJITAKTUAL', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'KOHATASH', 'KOHATANI', 'CURRENTTIME' ],
	'currentweek'               => [ '1', 'JAVAAKTUALE', 'JAVAMOMENTALE', 'CURRENTWEEK' ],
	'img_baseline'              => [ '1', 'vijabazë', 'linjabazë', 'baseline' ],
	'img_center'                => [ '1', 'qendër', 'qendrore', 'qëndër', 'qëndrore', 'center', 'centre' ],
	'localmonth'                => [ '1', 'MUEJILOKAL', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ],
	'servername'                => [ '0', 'EMNISERVERIT', 'EMRIISERVERIT', 'SERVERNAME' ],
];
