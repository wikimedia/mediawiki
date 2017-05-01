<?php
/** Volapük (Volapük)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Geitost
 * @author Iketsi
 * @author Kaganer
 * @author Malafaya
 * @author Reedy
 * @author Smeira
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'Nünamakanäd',
	NS_SPECIAL          => 'Patikos',
	NS_TALK             => 'Bespik',
	NS_USER             => 'Geban',
	NS_USER_TALK        => 'Gebanibespik',
	NS_PROJECT_TALK     => 'Bespik_dö_$1',
	NS_FILE             => 'Ragiv',
	NS_FILE_TALK        => 'Ragivibespik',
	NS_MEDIAWIKI        => 'Sitanuns',
	NS_MEDIAWIKI_TALK   => 'Bespik_dö_sitanuns',
	NS_TEMPLATE         => 'Samafomot',
	NS_TEMPLATE_TALK    => 'Samafomotibespik',
	NS_HELP             => 'Yuf',
	NS_HELP_TALK        => 'Yufibespik',
	NS_CATEGORY         => 'Klad',
	NS_CATEGORY_TALK    => 'Kladibespik',
];

$namespaceAliases = [
	'Magod' => NS_FILE,
	'Magodibespik' => NS_FILE_TALK,
];

$datePreferences = [
	'default',
	'vo',
	'vo plain',
	'ISO 8601',
];

$defaultDateFormat = 'vo';

$dateFormats = [
	'vo time' => 'H:i',
	'vo date' => 'Y F j"id"',
	'vo both' => 'H:i, Y F j"id"',

	'vo plain time' => 'H:i',
	'vo plain date' => 'Y F j',
	'vo plain both' => 'H:i, Y F j',
];

$specialPageAliases = [
	'Allmessages'               => [ 'Nünsvalik' ],
	'Allpages'                  => [ 'Padsvalik' ],
	'Ancientpages'              => [ 'Padsbäldik' ],
	'Blankpage'                 => [ 'PadVagik' ],
	'BrokenRedirects'           => [ 'Lüodükömsdädik', 'Lüodüköms dädik' ],
	'Categories'                => [ 'Klads' ],
	'Confirmemail'              => [ 'Fümedönladeti' ],
	'Contributions'             => [ 'Keblünots' ],
	'DoubleRedirects'           => [ 'Lüodükömstelik', 'Lüodüköms telik' ],
	'Listfiles'                 => [ 'Ragivalised', 'Magodalised' ],
	'Listusers'                 => [ 'Gebanalised' ],
	'Log'                       => [ 'Jenotalised', 'Jenotaliseds' ],
	'Lonelypages'               => [ 'Padssoelöl', 'Pads soelöl' ],
	'Longpages'                 => [ 'Padslunik' ],
	'Mostimages'                => [ 'Ragivs suvüno peyümöls' ],
	'Mostlinked'                => [ 'Suvüno peyümöls' ],
	'Mostlinkedcategories'      => [ 'Klads suvüno peyümöls' ],
	'Mostlinkedtemplates'       => [ 'Samafomots suvüno peyümöls' ],
	'Movepage'                  => [ 'Topätükön' ],
	'Mycontributions'           => [ 'Keblünotsobik' ],
	'Mypage'                    => [ 'Padobik' ],
	'Mytalk'                    => [ 'Bespikobik' ],
	'Newimages'                 => [ 'Ragivsnulik', 'Magodsnulik', 'Magods nulik' ],
	'Newpages'                  => [ 'Padsnulik' ],
	'Preferences'               => [ 'Buükams' ],
	'Protectedpages'            => [ 'Padspejelöl' ],
	'Protectedtitles'           => [ 'Tiädspejelöl' ],
	'Randompage'                => [ 'Padfädik', 'Pad fädik', 'Fädik' ],
	'Recentchanges'             => [ 'Votükamsnulik' ],
	'Search'                    => [ 'Suk' ],
	'Shortpages'                => [ 'Padsbrefik' ],
	'Specialpages'              => [ 'Padspatik' ],
	'Statistics'                => [ 'Statits' ],
	'Uncategorizedcategories'   => [ 'Kladsnenklads', 'Klads nen klads' ],
	'Uncategorizedimages'       => [ 'Ragivsnenklads', 'Magodsnenklads', 'Magods nen klads' ],
	'Uncategorizedpages'        => [ 'Padsnenklads', 'Pads nen klads' ],
	'Uncategorizedtemplates'    => [ 'Samafomotsnenklads', 'Samafomots nen klads' ],
	'Unusedcategories'          => [ 'Kladsnopageböls', 'Klad no pageböls' ],
	'Unusedimages'              => [ 'Ragivsnopageböls', 'Magodsnopageböls', 'Magods no pageböls' ],
	'Upload'                    => [ 'Löpükön' ],
	'Userlogin'                 => [ 'Gebananunäd' ],
	'Userlogout'                => [ 'Gebanasenunäd' ],
	'Version'                   => [ 'Fomam' ],
	'Wantedcategories'          => [ 'Klads mekabik', 'Kladsmekabik', 'Kladspavilöl', 'Klads pavilöl' ],
	'Wantedfiles'               => [ 'Ragivsmekabik' ],
	'Wantedpages'               => [ 'Pads mekabik', 'Padsmekabik', 'Padspavilöl', 'Yümsdädik', 'Pads pavilöl', 'Yüms dädik' ],
	'Wantedtemplates'           => [ 'Samafomotsmekabik' ],
	'Watchlist'                 => [ 'Galädalised' ],
	'Whatlinkshere'             => [ 'Yümsisio', 'Isio' ],
];

