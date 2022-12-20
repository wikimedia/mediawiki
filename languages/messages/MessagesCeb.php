<?php
/** Cebuano (Cebuano)
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Espesyal',
	NS_TALK             => 'Hisgot',
	NS_USER             => 'Gumagamit',
	NS_USER_TALK        => 'Hisgot_sa_Gumagamit',
	NS_PROJECT_TALK     => 'Hisgot_sa_$1',
	NS_FILE             => 'Payl',
	NS_FILE_TALK        => 'Hisgot_sa_Payl',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Hisgot_sa_MediaWiki',
	NS_TEMPLATE         => 'Plantilya',
	NS_TEMPLATE_TALK    => 'Hisgot_sa_Plantilya',
	NS_HELP             => 'Tabang',
	NS_HELP_TALK        => 'Hisgot_sa_Tabang',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Hisgot_sa_Kategoriya',
];

$namespaceAliases = [
	'Hisgot_sa$1' => NS_PROJECT_TALK,
	'Imahen' => NS_FILE,
	'Hisgot_sa_Imahen' => NS_FILE_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allpages'                  => [ 'TanangPanid' ],
	'BrokenRedirects'           => [ 'BuakngaRedirekta' ],
	'Categories'                => [ 'Mga_Kategoriya' ],
	'Contributions'             => [ 'Mga_Tampo' ],
	'CreateAccount'             => [ 'Paghimo\'gAkawnt' ],
	'DoubleRedirects'           => [ 'DoblengRedirekta' ],
	'Listfiles'                 => [ 'Listahan_sa_Imahen' ],
	'Lonelypages'               => [ 'Nag-inusarangPanid', 'Sinagop_nga_Panid' ],
	'Mycontributions'           => [ 'AkongTampo' ],
	'Mypage'                    => [ 'AkongPanid' ],
	'Mytalk'                    => [ 'AkongHisgot' ],
	'Newimages'                 => [ 'Bag-ongImahen' ],
	'Preferences'               => [ 'Mga_Preperensya' ],
	'Randompage'                => [ 'Bisan-unsa', 'Bisan-unsangPanid' ],
	'Recentchanges'             => [ 'Bag-ongGiusab' ],
	'Search'                    => [ 'Pangita' ],
	'Statistics'                => [ 'Estadistika' ],
	'Upload'                    => [ 'Pagsumiter' ],
	'Version'                   => [ 'Bersiyon' ],
	'Watchlist'                 => [ 'Gibantayan' ],
];
