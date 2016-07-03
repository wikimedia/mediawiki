<?php
/** Walloon (walon)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Lucyin
 * @author Srtxg
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'fr';

# lists "no preferences", normall (long) walloon date,
# short walloon date, and ISO format
# MW_DATE_DMY is alias for long format, as it is dd mmmmm yyyy.
$datePreferences = [
	'default',
	'dmy',
	'walloon short',
	'ISO 8601'
];

$datePreferenceMigrationMap = [
	0 => 'default',
	2 => 'dmy',
	4 => 'walloon short',
];
$defaultDateFormat = 'dmy';

$dateFormats = [
	'walloon short time' => 'H:i'
];

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Sipeciås',
	NS_TALK             => 'Copene',
	NS_USER             => 'Uzeu',
	NS_USER_TALK        => 'Uzeu_copene',
	NS_PROJECT_TALK     => '$1_copene',
	NS_FILE             => 'Imådje',
	NS_FILE_TALK        => 'Imådje_copene',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_copene',
	NS_TEMPLATE         => 'Modele',
	NS_TEMPLATE_TALK    => 'Modele_copene',
	NS_HELP             => 'Aidance',
	NS_HELP_TALK        => 'Aidance_copene',
	NS_CATEGORY         => 'Categoreye',
	NS_CATEGORY_TALK    => 'Categoreye_copene',
];

// Remove French aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Allpages'                  => [ 'Totes_les_pådjes' ],
	'Block'                     => [ 'Bloker', 'Blocaedje' ],
	'Categories'                => [ 'Categoreyes' ],
	'Listusers'                 => [ 'Djivêye_des_uzeus' ],
	'Log'                       => [ 'Djournå', 'Djournås' ],
	'Preferences'               => [ 'Preferinces' ],
	'Prefixindex'               => [ 'Indecse_pa_betchete' ],
	'Search'                    => [ 'Cweri' ],
	'Specialpages'              => [ 'Pådjes_sipeciåles' ],
	'Statistics'                => [ 'Sitatistikes' ],
	'Undelete'                  => [ 'Rapexhî' ],
	'Upload'                    => [ 'Eberweter', 'Eberwetaedje' ],
	'Userlogin'                 => [ 'Elodjaedje' ],
	'Userlogout'                => [ 'Dislodjaedje' ],
	'Version'                   => [ 'Modêye' ],
	'Watchlist'                 => [ 'Pådjes_shuvowes' ],
];

# definixha del cogne po les limeros
# (number format definition)
# en: 12,345.67 -> wa: 12 345,67
$separatorTransformTable = [ ',' => "\xc2\xa0", '.' => ',' ];

# $linkTrail = '/^([a-zåâêîôûçéèA-ZÅÂÊÎÔÛÇÉÈ]+)(.*)$/sDu';
$linkTrail = '/^([a-zåâêîôûçéè]+)(.*)$/sDu';

