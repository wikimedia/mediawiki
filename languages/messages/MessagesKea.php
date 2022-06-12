<?php
/** Cape Verdean Creole (kabuverdianu)
 *
 * @file
 * @ingroup Languages
 *
 * @author Waldir Pimenta
 * @author Amir E. Aharoni
 */

$fallback = 'pt';

$namespaceNames = [
	NS_MEDIA            => 'Multimédia',
	NS_SPECIAL          => 'Spesial',
	NS_TALK             => 'Diskuson',
	NS_USER             => 'Uzuáriu',
	NS_USER_TALK        => 'Diskuson_di_uzuáriu',
	NS_PROJECT_TALK     => 'Diskuson_di_$1',
	NS_FILE             => 'Fixeru',
	NS_FILE_TALK        => 'Diskuson_di_fixeru',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskuson_di_MediaWiki',
	NS_TEMPLATE         => 'Modelu',
	NS_TEMPLATE_TALK    => 'Diskuson_di_modelu',
	NS_HELP             => 'Ajuda',
	NS_HELP_TALK        => 'Diskuson_di_ajuda',
	NS_CATEGORY         => 'Katiguria',
	NS_CATEGORY_TALK    => 'Diskuson_di_katiguria',
];

$namespaceGenderAliases = [
	NS_USER => [
		'male' => 'Uzuáriu',
		'female' => 'Uzuária'
	],
	NS_USER_TALK => [
		'male' => 'Diskuson_di_uzuáriu',
		'female' => 'Diskuson_di_uzuária'
	],
];

$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j "di" F "di" Y',
	'dmy both' => 'H:i, j "di" F "di" Y',
];

$linkTrail = '/^([áàâãç̈éèêíóòôõúa-z]+)(.*)$/sDu';
