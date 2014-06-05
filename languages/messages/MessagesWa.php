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
$datePreferences = array(
	'default',
	'dmy',
	'walloon short',
	'ISO 8601'
);

$datePreferenceMigrationMap = array(
	0 => 'default',
	2 => 'dmy',
	4 => 'walloon short',
);
$defaultDateFormat = 'dmy';

$dateFormats = array(
	'walloon short time' => 'H:i'
);

$namespaceNames = array(
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
);

// Remove French aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Allpages'                  => array( 'Totes_les_pådjes' ),
	'Block'                     => array( 'Bloker', 'Blocaedje' ),
	'Categories'                => array( 'Categoreyes' ),
	'Listusers'                 => array( 'Djivêye_des_uzeus' ),
	'Log'                       => array( 'Djournå', 'Djournås' ),
	'Preferences'               => array( 'Preferinces' ),
	'Prefixindex'               => array( 'Indecse_pa_betchete' ),
	'Search'                    => array( 'Cweri' ),
	'Specialpages'              => array( 'Pådjes_sipeciåles' ),
	'Statistics'                => array( 'Sitatistikes' ),
	'Undelete'                  => array( 'Rapexhî' ),
	'Upload'                    => array( 'Eberweter', 'Eberwetaedje' ),
	'Userlogin'                 => array( 'Elodjaedje' ),
	'Userlogout'                => array( 'Dislodjaedje' ),
	'Version'                   => array( 'Modêye' ),
	'Watchlist'                 => array( 'Pådjes_shuvowes' ),
);

# definixha del cogne po les limeros
# (number format definition)
# en: 12,345.67 -> wa: 12 345,67
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

# $linkTrail = '/^([a-zåâêîôûçéèA-ZÅÂÊÎÔÛÇÉÈ]+)(.*)$/sDu';
$linkTrail = '/^([a-zåâêîôûçéè]+)(.*)$/sDu';

