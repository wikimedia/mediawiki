<?php
/** Faroese (føroyskt)
 *
 * @file
 * @ingroup Languages
 */

$namespaceNames = [
	NS_MEDIA            => 'Miðil',
	NS_SPECIAL          => 'Serstakt',
	NS_TALK             => 'Kjak',
	NS_USER             => 'Brúkari',
	NS_USER_TALK        => 'Brúkarakjak',
	NS_PROJECT_TALK     => '$1-kjak',
	NS_FILE             => 'Mynd',
	NS_FILE_TALK        => 'Myndakjak',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-kjak',
	NS_TEMPLATE         => 'Fyrimynd',
	NS_TEMPLATE_TALK    => 'Fyrimyndakjak',
	NS_HELP             => 'Hjálp',
	NS_HELP_TALK        => 'Hjálparkjak',
	NS_CATEGORY         => 'Bólkur',
	NS_CATEGORY_TALK    => 'Bólkakjak',
];

$namespaceAliases = [
	'Serstakur' => NS_SPECIAL,
	'Brúkari_kjak' => NS_USER_TALK,
	'$1_kjak' => NS_PROJECT_TALK,
	'Mynd_kjak' => NS_FILE_TALK,
	'MidiaWiki' => NS_MEDIAWIKI,
	'MidiaWiki_kjak' => NS_MEDIAWIKI_TALK,
	'Fyrimynd_kjak' => NS_TEMPLATE_TALK,
	'Hjálp_kjak' => NS_HELP_TALK,
	'Bólkur_kjak' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Øll_kervisboð' ],
	'Allpages'                  => [ 'Allar_síður' ],
	'Ancientpages'              => [ 'Elstu_síður' ],
	'Block'                     => [ 'Banna_brúkara' ],
	'BlockList'                 => [ 'Bannað_brúkaranøvn_og_IP-adressur' ],
	'Booksources'               => [ 'Bóka_keldur' ],
	'BrokenRedirects'           => [ 'Brotnar_ávísingar' ],
	'Categories'                => [ 'Bólkar' ],
	'Contributions'             => [ 'Brúkaraíkast' ],
	'Deadendpages'              => [ 'Gøtubotns_síður' ],
	'DoubleRedirects'           => [ 'Tvífaldað_ávísing' ],
	'Emailuser'                 => [ 'Send_t-post_til_brúkara' ],
	'Export'                    => [ 'Útflutningssíður' ],
	'Fewestrevisions'           => [ 'Greinir_við_minst_útgávum' ],
	'Listfiles'                 => [ 'Myndalisti' ],
	'Listusers'                 => [ 'Brúkaralisti' ],
	'Lonelypages'               => [ 'Foreldraleysar_síður' ],
	'Longpages'                 => [ 'Langar_síður' ],
	'Mostcategories'            => [ 'Greinir_við_flest_bólkum' ],
	'Mostrevisions'             => [ 'Greinir_við_flest_útgávum' ],
	'Movepage'                  => [ 'Flyt_síðu' ],
	'Newimages'                 => [ 'Nýggjar_myndir' ],
	'Newpages'                  => [ 'Nýggjar_síður' ],
	'Preferences'               => [ 'Innstillingar' ],
	'Randompage'                => [ 'Tilvildarlig_síða' ],
	'Recentchanges'             => [ 'Seinastu_broytingar' ],
	'Search'                    => [ 'Leita' ],
	'Shortpages'                => [ 'Stuttar_síður' ],
	'Specialpages'              => [ 'Serligar_síður' ],
	'Statistics'                => [ 'Hagtøl' ],
	'Uncategorizedcategories'   => [ 'Óbólkaðir_bólkar' ],
	'Uncategorizedimages'       => [ 'Óbólkaðar_myndir' ],
	'Uncategorizedpages'        => [ 'Óbólkaðar_síður' ],
	'Uncategorizedtemplates'    => [ 'Óbólkaðar_fyrimyndir' ],
	'Undelete'                  => [ 'Endurstovna_strikaðar_síður' ],
	'Unusedcategories'          => [ 'Óbrúktir_bólkar' ],
	'Unusedimages'              => [ 'Óbrúktar_myndir' ],
	'Upload'                    => [ 'Legg_fílu_upp' ],
	'Userlogin'                 => [ 'Stovna_kontu_ella_rita_inn' ],
	'Userlogout'                => [ 'Rita_út' ],
	'Version'                   => [ 'Útgáva' ],
	'Wantedpages'               => [ 'Ynsktar_síður' ],
	'Watchlist'                 => [ 'Mítt_eftirlit' ],
];

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',
];

$bookstoreList = [
	'Bokasolan.fo' => 'http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1',
	'inherit' => true,
];

$linkTrail = '/^([áðíóúýæøa-z]+)(.*)$/sDu';
