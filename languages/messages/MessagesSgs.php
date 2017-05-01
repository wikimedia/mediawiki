<?php
/** Samogitian (žemaitėška)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hugo.arg
 * @author Kaganer
 * @author Reedy
 * @author Urhixidur
 * @author Zordsdavini
 * @author לערי ריינהארט
 */

$fallback = 'lt';

$namespaceNames = [
	NS_MEDIA            => 'Medėjė',
	NS_SPECIAL          => 'Specēlos',
	NS_TALK             => 'Aptarėms',
	NS_USER             => 'Nauduotuos',
	NS_USER_TALK        => 'Nauduotuojė_aptarėms',
	NS_PROJECT_TALK     => '$1_aptarėms',
	NS_FILE             => 'Abruozdielis',
	NS_FILE_TALK        => 'Abruozdielė_aptarėms',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarėms',
	NS_TEMPLATE         => 'Šabluons',
	NS_TEMPLATE_TALK    => 'Šabluona_aptarėms',
	NS_HELP             => 'Pagelba',
	NS_HELP_TALK        => 'Pagelbas_aptarėms',
	NS_CATEGORY         => 'Kateguorėjė',
	NS_CATEGORY_TALK    => 'Kateguorėjės_aptarėms',
];

/**
 * Aliases from the fallback language 'lt' to avoid breakage of links
 */
$namespaceAliases = [
	'Specialus'             => NS_SPECIAL,
	'Aptarimas'             => NS_TALK,
	'Naudotojas'            => NS_USER,
	'Naudotojo_aptarimas'   => NS_USER_TALK,
	'$1_aptarimas'          => NS_PROJECT_TALK,
	'Vaizdas'               => NS_FILE,
	'Vaizdo_aptarimas'      => NS_FILE_TALK,
	'MediaWiki_aptarimas'   => NS_MEDIAWIKI_TALK,
	'Šablonas'              => NS_TEMPLATE,
	'Šablono_aptarimas'     => NS_TEMPLATE_TALK,
	'Pagalba'               => NS_HELP,
	'Pagalbos_aptarimas'    => NS_HELP_TALK,
	'Kategorija'            => NS_CATEGORY,
	'Kategorijos_aptarimas' => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [];

