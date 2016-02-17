<?php
/** Bavarian (Boarisch)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'de';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Dischkrian',
	NS_USER             => 'Nutza',
	NS_USER_TALK        => 'Nutza_Dischkrian',
	NS_PROJECT_TALK     => '$1_Dischkrian',
	NS_FILE             => 'Datei',
	NS_FILE_TALK        => 'Datei_Dischkrian',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Dischkrian',
	NS_TEMPLATE         => 'Vorlog',
	NS_TEMPLATE_TALK    => 'Vorlog_Dischkrian',
	NS_HELP             => 'Huif',
	NS_HELP_TALK        => 'Huif_Dischkrian',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Dischkrian',
];

$namespaceAliases = [
	# German namespaces
	'Medium'               => NS_MEDIA,
	'Diskussion'           => NS_TALK,
	'Benutzer'             => NS_USER,
	'Benutzer_Diskussion'  => NS_USER_TALK,
	'$1_Diskussion'        => NS_PROJECT_TALK,
	'Datei_Diskussion'     => NS_FILE_TALK,
	'MediaWiki_Diskussion' => NS_MEDIAWIKI_TALK,
	'Vorlage'              => NS_TEMPLATE,
	'Vorlage_Diskussion'   => NS_TEMPLATE_TALK,
	'Hilfe'                => NS_HELP,
	'Hilfe_Diskussion'     => NS_HELP_TALK,
	'Kategorie_Diskussion' => NS_CATEGORY_TALK,
];

// Remove German aliases
$namespaceGenderAliases = [];

