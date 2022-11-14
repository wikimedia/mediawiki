<?php
/** Volapük (Volapük)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Nünsvalik' ],
	'Allpages'                  => [ 'Padsvalik' ],
	'Ancientpages'              => [ 'Padsbäldik' ],
	'Blankpage'                 => [ 'PadVagik' ],
	'BrokenRedirects'           => [ 'Lüodükömsdädik', 'Lüodüköms_dädik' ],
	'Categories'                => [ 'Klads' ],
	'Confirmemail'              => [ 'Fümedönladeti' ],
	'Contributions'             => [ 'Keblünots' ],
	'DoubleRedirects'           => [ 'Lüodükömstelik', 'Lüodüköms_telik' ],
	'Listfiles'                 => [ 'Ragivalised', 'Magodalised' ],
	'Listusers'                 => [ 'Gebanalised' ],
	'Log'                       => [ 'Jenotalised', 'Jenotaliseds' ],
	'Lonelypages'               => [ 'Padssoelöl', 'Pads_soelöl' ],
	'Longpages'                 => [ 'Padslunik' ],
	'Mostimages'                => [ 'Ragivs_suvüno_peyümöls' ],
	'Mostlinked'                => [ 'Suvüno_peyümöls' ],
	'Mostlinkedcategories'      => [ 'Klads_suvüno_peyümöls' ],
	'Mostlinkedtemplates'       => [ 'Samafomots_suvüno_peyümöls' ],
	'Movepage'                  => [ 'Topätükön' ],
	'Mycontributions'           => [ 'Keblünotsobik' ],
	'Mypage'                    => [ 'Padobik' ],
	'Mytalk'                    => [ 'Bespikobik' ],
	'Newimages'                 => [ 'Ragivsnulik', 'Magodsnulik', 'Magods_nulik' ],
	'Newpages'                  => [ 'Padsnulik' ],
	'Preferences'               => [ 'Buükams' ],
	'Protectedpages'            => [ 'Padspejelöl' ],
	'Protectedtitles'           => [ 'Tiädspejelöl' ],
	'Randompage'                => [ 'Padfädik', 'Pad_fädik', 'Fädik' ],
	'Recentchanges'             => [ 'Votükamsnulik' ],
	'Search'                    => [ 'Suk' ],
	'Shortpages'                => [ 'Padsbrefik' ],
	'Specialpages'              => [ 'Padspatik' ],
	'Statistics'                => [ 'Statits' ],
	'Uncategorizedcategories'   => [ 'Kladsnenklads', 'Klads_nen_klads' ],
	'Uncategorizedimages'       => [ 'Ragivsnenklads', 'Magodsnenklads', 'Magods_nen_klads' ],
	'Uncategorizedpages'        => [ 'Padsnenklads', 'Pads_nen_klads' ],
	'Uncategorizedtemplates'    => [ 'Samafomotsnenklads', 'Samafomots_nen_klads' ],
	'Unusedcategories'          => [ 'Kladsnopageböls', 'Klad_no_pageböls' ],
	'Unusedimages'              => [ 'Ragivsnopageböls', 'Magodsnopageböls', 'Magods_no_pageböls' ],
	'Upload'                    => [ 'Löpükön' ],
	'Userlogin'                 => [ 'Gebananunäd' ],
	'Userlogout'                => [ 'Gebanasenunäd' ],
	'Version'                   => [ 'Fomam' ],
	'Wantedcategories'          => [ 'Klads_mekabik', 'Kladsmekabik', 'Kladspavilöl', 'Klads_pavilöl' ],
	'Wantedfiles'               => [ 'Ragivsmekabik' ],
	'Wantedpages'               => [ 'Pads_mekabik', 'Padsmekabik', 'Padspavilöl', 'Yümsdädik', 'Pads_pavilöl', 'Yüms_dädik' ],
	'Wantedtemplates'           => [ 'Samafomotsmekabik' ],
	'Watchlist'                 => [ 'Galädalised' ],
	'Whatlinkshere'             => [ 'Yümsisio', 'Isio' ],
];
