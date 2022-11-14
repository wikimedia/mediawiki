<?php
/** Campidanese Sardinian (sardu campidanesu)
 *
 * @file
 * @ingroup Languages
 * @author Jaime Sulas
 * @author Amir E. Aharoni
 */

$fallback = 'it';

$namespaceNames = [
	NS_MEDIA            => 'Mèdius',
	NS_SPECIAL          => 'Spetziali',
	NS_TALK             => 'Arraxonus',
	NS_USER             => 'Impreadori',
	NS_USER_TALK        => 'Arraxonus_de_s’impreadori',
	NS_PROJECT_TALK     => 'Arraxonus_de_$1',
	NS_FILE             => 'Documentu',
	NS_FILE_TALK        => 'Arraxonus_de_su_documentu',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Arraxonus_de_MediaWiki',
	NS_TEMPLATE         => 'Mòlliu',
	NS_TEMPLATE_TALK    => 'Arraxonus_de_su_mòlliu',
	NS_HELP             => 'Agiudu',
	NS_HELP_TALK        => 'Arraxonus_de_s’agiudu',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Arraxonus_de_sa_categoria',
];

$namespaceGenderAliases = [
	NS_USER => [
		'male' => 'Impreadori',
		'female' => 'Impreadora'
	],
	NS_USER_TALK => [
		'male' => 'Arraxonus_de_s’impreadori',
		'female' => 'Arraxonus_de_s’impreadora'
	],
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i, j M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
];

$linkTrail = '/^([a-zàéèíîìóòúù]+)(.*)$/sDu';
