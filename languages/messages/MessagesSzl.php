<?php
/** Silesian (ślůnski)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Britscher
 * @author Danny B.
 * @author Djpalar
 * @author Gaj777
 * @author Herr Kriss
 * @author Kaganer
 * @author Krol111
 * @author Lajsikonik
 * @author Leinad
 * @author Lwh
 * @author Ozi64
 * @author Pimke
 * @author Przemub
 * @author Tchoř
 * @author Timpul
 */

$fallback = 'pl';

$namespaceNames = array(
	NS_SPECIAL          => 'Szpecyjalna',
	NS_TALK             => 'Dyskusyjo',
	NS_USER             => 'Używacz',
	NS_USER_TALK        => 'Dyskusyjo_używacza',
	NS_PROJECT_TALK     => 'Dyskusyjo_$1',
	NS_FILE             => 'Plik',
	NS_FILE_TALK        => 'Dyskusyjo_plika',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dyskusyjo_MediaWiki',
	NS_TEMPLATE         => 'Muster',
	NS_TEMPLATE_TALK    => 'Dyskusyjo_mustra',
	NS_HELP             => 'Půmoc',
	NS_HELP_TALK        => 'Dyskusyjo_půmocy',
	NS_CATEGORY         => 'Kategoryjo',
	NS_CATEGORY_TALK    => 'Dyskusyjo_kategoryji',
);

$namespaceAliases = array(
	// Aliases for Polish namespaces (bug 34988).
	'Specjalna'            => NS_SPECIAL,
	'Dyskusja'             => NS_TALK,
	'Użytkownik'           => NS_USER,
	'Dyskusja_użytkownika' => NS_USER_TALK,
	'Dyskusja_$1'          => NS_PROJECT_TALK,
	'Dyskusja_pliku'       => NS_FILE_TALK,
	'Dyskusja_MediaWiki'   => NS_MEDIAWIKI_TALK,
	'Szablon'              => NS_TEMPLATE,
	'Dyskusja_szablonu'    => NS_TEMPLATE_TALK,
	'Pomoc'                => NS_HELP,
	'Dyskusja_pomocy'      => NS_HELP_TALK,
	'Kategoria'            => NS_CATEGORY,
	'Dyskusja_kategorii'   => NS_CATEGORY_TALK,
);

// Remove Polish gender aliases
$namespaceGenderAliases = array();

