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

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

// Remove French aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'CreateAccount'             => array( 'Sos am sàq' ),
	'Listfiles'                 => array( 'Limu nataal yi' ),
	'Listgrouprights'           => array( 'Limu mboolooy jëfandikukat' ),
	'Listusers'                 => array( 'Limu jëfandikukat yi' ),
	'Lonelypages'               => array( 'Xëtu jirim' ),
	'Mycontributions'           => array( 'Samay cëru' ),
	'Mypage'                    => array( 'Sama xët' ),
	'Mytalk'                    => array( 'Samay waxtaan' ),
	'Newimages'                 => array( 'Nataal bu bees' ),
	'Preferences'               => array( 'Tànneef' ),
	'Randompage'                => array( 'Xët cig mbetteel' ),
	'Recentchanges'             => array( 'Coppite yu mujj' ),
	'Search'                    => array( 'Ceet' ),
	'Uncategorizedcategories'   => array( 'Wàll yi amul wàll' ),
	'Uncategorizedimages'       => array( 'Nataal yi amul wàll' ),
	'Uncategorizedpages'        => array( 'Xët yi amul wàll' ),
	'Uncategorizedtemplates'    => array( 'Royuwaay yi amul wàll' ),
	'Unusedcategories'          => array( 'Royuwaay yiñ jëfandikuwul' ),
	'Unusedimages'              => array( 'Nataal yiñ jëfandikuwul' ),
	'Upload'                    => array( 'Yeb' ),
	'Userlogin'                 => array( 'Lonku' ),
	'Userlogout'                => array( 'Lonkiku' ),
	'Wantedcategories'          => array( 'Wàll yiñ laaj' ),
	'Wantedpages'               => array( 'Xët yiñ laaj' ),
	'Watchlist'                 => array( 'Limu toppte' ),
);

