<?php
/** Wolof (Wolof)
 *
 * @file
 * @ingroup Languages
 *
 * @author Ahloubadar
 * @author Ibou
 * @author Maax
 * @author Reedy
 * @author SF-Language
 * @author Urhixidur
 */

$fallback = 'fr';

$namespaceNames = [
	NS_MEDIA            => 'Xibaarukaay',
	NS_SPECIAL          => 'Jagleel',
	NS_TALK             => 'Waxtaan',
	NS_USER             => 'Jëfandikukat',
	NS_USER_TALK        => 'Waxtaani_jëfandikukat',
	NS_PROJECT_TALK     => '$1_waxtaan',
	NS_FILE             => 'Dencukaay',
	NS_FILE_TALK        => 'Waxtaani_dencukaay',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Waxtaani_MediaWiki',
	NS_TEMPLATE         => 'Royuwaay',
	NS_TEMPLATE_TALK    => 'Waxtaani_royuwaay',
	NS_HELP             => 'Ndimbal',
	NS_HELP_TALK        => 'Waxtaani_ndimbal',
	NS_CATEGORY         => 'Wàll',
	NS_CATEGORY_TALK    => 'Waxtaani_wàll',
];

$namespaceAliases = [
	'Discuter' => NS_TALK,
	'Utilisateur' => NS_USER,
	'Discussion_Utilisateur' => NS_USER_TALK,
	'Discussion_$1' => NS_PROJECT_TALK,
	'Discussion_Image' => NS_FILE_TALK,
	'Discussion_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Modèle' => NS_TEMPLATE,
	'Discussion_Modèle' => NS_TEMPLATE_TALK,
	'Aide' => NS_HELP,
	'Discussion_Aide' => NS_HELP_TALK,
	'Catégorie' => NS_CATEGORY,
	'Discussion_Catégorie' => NS_CATEGORY_TALK,
];

// Remove French aliases
$namespaceGenderAliases = [];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'CreateAccount'             => [ 'Sos_am_sàq' ],
	'Listfiles'                 => [ 'Limu_nataal_yi' ],
	'Listgrouprights'           => [ 'Limu_mboolooy_jëfandikukat' ],
	'Listusers'                 => [ 'Limu_jëfandikukat_yi' ],
	'Lonelypages'               => [ 'Xëtu_jirim' ],
	'Mycontributions'           => [ 'Samay_cëru' ],
	'Mypage'                    => [ 'Sama_xët' ],
	'Mytalk'                    => [ 'Samay_waxtaan' ],
	'Newimages'                 => [ 'Nataal_bu_bees' ],
	'Preferences'               => [ 'Tànneef' ],
	'Randompage'                => [ 'Xët_cig_mbetteel' ],
	'Recentchanges'             => [ 'Coppite_yu_mujj' ],
	'Search'                    => [ 'Ceet' ],
	'Uncategorizedcategories'   => [ 'Wàll_yi_amul_wàll' ],
	'Uncategorizedimages'       => [ 'Nataal_yi_amul_wàll' ],
	'Uncategorizedpages'        => [ 'Xët_yi_amul_wàll' ],
	'Uncategorizedtemplates'    => [ 'Royuwaay_yi_amul_wàll' ],
	'Unusedcategories'          => [ 'Royuwaay_yiñ_jëfandikuwul' ],
	'Unusedimages'              => [ 'Nataal_yiñ_jëfandikuwul' ],
	'Upload'                    => [ 'Yeb' ],
	'Userlogin'                 => [ 'Lonku' ],
	'Userlogout'                => [ 'Lonkiku' ],
	'Wantedcategories'          => [ 'Wàll_yiñ_laaj' ],
	'Wantedpages'               => [ 'Xët_yiñ_laaj' ],
	'Watchlist'                 => [ 'Limu_toppte' ],
];
