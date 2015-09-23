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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Magod' => NS_FILE,
	'Magodibespik' => NS_FILE_TALK,
);

$datePreferences = array(
	'default',
	'vo',
	'vo plain',
	'ISO 8601',
);

$defaultDateFormat = 'vo';

$dateFormats = array(
	'vo time' => 'H:i',
	'vo date' => 'Y F j"id"',
	'vo both' => 'H:i, Y F j"id"',

	'vo plain time' => 'H:i',
	'vo plain date' => 'Y F j',
	'vo plain both' => 'H:i, Y F j',
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Nünsvalik' ),
	'Allpages'                  => array( 'Padsvalik' ),
	'Ancientpages'              => array( 'Padsbäldik' ),
	'Blankpage'                 => array( 'PadVagik' ),
	'BrokenRedirects'           => array( 'Lüodükömsdädik', 'Lüodüköms dädik' ),
	'Categories'                => array( 'Klads' ),
	'Confirmemail'              => array( 'Fümedönladeti' ),
	'Contributions'             => array( 'Keblünots' ),
	'DoubleRedirects'           => array( 'Lüodükömstelik', 'Lüodüköms telik' ),
	'Listfiles'                 => array( 'Ragivalised', 'Magodalised' ),
	'Listusers'                 => array( 'Gebanalised' ),
	'Log'                       => array( 'Jenotalised', 'Jenotaliseds' ),
	'Lonelypages'               => array( 'Padssoelöl', 'Pads soelöl' ),
	'Longpages'                 => array( 'Padslunik' ),
	'Mostimages'                => array( 'Ragivs suvüno peyümöls' ),
	'Mostlinked'                => array( 'Suvüno peyümöls' ),
	'Mostlinkedcategories'      => array( 'Klads suvüno peyümöls' ),
	'Mostlinkedtemplates'       => array( 'Samafomots suvüno peyümöls' ),
	'Movepage'                  => array( 'Topätükön' ),
	'Mycontributions'           => array( 'Keblünotsobik' ),
	'Mypage'                    => array( 'Padobik' ),
	'Mytalk'                    => array( 'Bespikobik' ),
	'Newimages'                 => array( 'Ragivsnulik', 'Magodsnulik', 'Magods nulik' ),
	'Newpages'                  => array( 'Padsnulik' ),
	'Preferences'               => array( 'Buükams' ),
	'Protectedpages'            => array( 'Padspejelöl' ),
	'Protectedtitles'           => array( 'Tiädspejelöl' ),
	'Randompage'                => array( 'Padfädik', 'Pad fädik', 'Fädik' ),
	'Recentchanges'             => array( 'Votükamsnulik' ),
	'Search'                    => array( 'Suk' ),
	'Shortpages'                => array( 'Padsbrefik' ),
	'Specialpages'              => array( 'Padspatik' ),
	'Statistics'                => array( 'Statits' ),
	'Uncategorizedcategories'   => array( 'Kladsnenklads', 'Klads nen klads' ),
	'Uncategorizedimages'       => array( 'Ragivsnenklads', 'Magodsnenklads', 'Magods nen klads' ),
	'Uncategorizedpages'        => array( 'Padsnenklads', 'Pads nen klads' ),
	'Uncategorizedtemplates'    => array( 'Samafomotsnenklads', 'Samafomots nen klads' ),
	'Unusedcategories'          => array( 'Kladsnopageböls', 'Klad no pageböls' ),
	'Unusedimages'              => array( 'Ragivsnopageböls', 'Magodsnopageböls', 'Magods no pageböls' ),
	'Upload'                    => array( 'Löpükön' ),
	'Userlogin'                 => array( 'Gebananunäd' ),
	'Userlogout'                => array( 'Gebanasenunäd' ),
	'Version'                   => array( 'Fomam' ),
	'Wantedcategories'          => array( 'Klads mekabik', 'Kladsmekabik', 'Kladspavilöl', 'Klads pavilöl' ),
	'Wantedfiles'               => array( 'Ragivsmekabik' ),
	'Wantedpages'               => array( 'Pads mekabik', 'Padsmekabik', 'Padspavilöl', 'Yümsdädik', 'Pads pavilöl', 'Yüms dädik' ),
	'Wantedtemplates'           => array( 'Samafomotsmekabik' ),
	'Watchlist'                 => array( 'Galädalised' ),
	'Whatlinkshere'             => array( 'Yümsisio', 'Isio' ),
);

