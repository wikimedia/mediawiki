<?php
/** Wolof (Wolof)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
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

$specialPageAliases = [
	'CreateAccount'             => [ 'Sos am sàq' ],
	'Listfiles'                 => [ 'Limu nataal yi' ],
	'Listgrouprights'           => [ 'Limu mboolooy jëfandikukat' ],
	'Listusers'                 => [ 'Limu jëfandikukat yi' ],
	'Lonelypages'               => [ 'Xëtu jirim' ],
	'Mycontributions'           => [ 'Samay cëru' ],
	'Mypage'                    => [ 'Sama xët' ],
	'Mytalk'                    => [ 'Samay waxtaan' ],
	'Newimages'                 => [ 'Nataal bu bees' ],
	'Preferences'               => [ 'Tànneef' ],
	'Randompage'                => [ 'Xët cig mbetteel' ],
	'Recentchanges'             => [ 'Coppite yu mujj' ],
	'Search'                    => [ 'Ceet' ],
	'Uncategorizedcategories'   => [ 'Wàll yi amul wàll' ],
	'Uncategorizedimages'       => [ 'Nataal yi amul wàll' ],
	'Uncategorizedpages'        => [ 'Xët yi amul wàll' ],
	'Uncategorizedtemplates'    => [ 'Royuwaay yi amul wàll' ],
	'Unusedcategories'          => [ 'Royuwaay yiñ jëfandikuwul' ],
	'Unusedimages'              => [ 'Nataal yiñ jëfandikuwul' ],
	'Upload'                    => [ 'Yeb' ],
	'Userlogin'                 => [ 'Lonku' ],
	'Userlogout'                => [ 'Lonkiku' ],
	'Wantedcategories'          => [ 'Wàll yiñ laaj' ],
	'Wantedpages'               => [ 'Xët yiñ laaj' ],
	'Watchlist'                 => [ 'Limu toppte' ],
];

