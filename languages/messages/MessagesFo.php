<?php
/** Faroese (føroyskt)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Serstakur' => NS_SPECIAL,
	'Brúkari_kjak' => NS_USER_TALK,
	'$1_kjak' => NS_PROJECT_TALK,
	'Mynd_kjak' => NS_FILE_TALK,
	'MidiaWiki' => NS_MEDIAWIKI,
	'MidiaWiki_kjak' => NS_MEDIAWIKI_TALK,
	'Fyrimynd_kjak' => NS_TEMPLATE_TALK,
	'Hjálp_kjak' => NS_HELP_TALK,
	'Bólkur_kjak' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Øll kervisboð' ),
	'Allpages'                  => array( 'Allar síður' ),
	'Ancientpages'              => array( 'Elstu síður' ),
	'Block'                     => array( 'Banna brúkara' ),
	'Booksources'               => array( 'Bóka keldur' ),
	'BrokenRedirects'           => array( 'Brotnar ávísingar' ),
	'Categories'                => array( 'Bólkar' ),
	'Contributions'             => array( 'Brúkaraíkast' ),
	'Deadendpages'              => array( 'Gøtubotns síður' ),
	'DoubleRedirects'           => array( 'Tvífaldað ávísing' ),
	'Emailuser'                 => array( 'Send t-post til brúkara' ),
	'Export'                    => array( 'Útflutningssíður' ),
	'Fewestrevisions'           => array( 'Greinir við minst útgávum' ),
	'BlockList'                 => array( 'Bannað brúkaranøvn og IP-adressur' ),
	'Listfiles'                 => array( 'Myndalisti' ),
	'Listusers'                 => array( 'Brúkaralisti' ),
	'Lonelypages'               => array( 'Foreldraleysar síður' ),
	'Longpages'                 => array( 'Langar síður' ),
	'Mostcategories'            => array( 'Greinir við flest bólkum' ),
	'Mostrevisions'             => array( 'Greinir við flest útgávum' ),
	'Movepage'                  => array( 'Flyt síðu' ),
	'Newimages'                 => array( 'Nýggjar myndir' ),
	'Newpages'                  => array( 'Nýggjar síður' ),
	'Preferences'               => array( 'Innstillingar' ),
	'Randompage'                => array( 'Tilvildarlig síða' ),
	'Recentchanges'             => array( 'Seinastu broytingar' ),
	'Search'                    => array( 'Leita' ),
	'Shortpages'                => array( 'Stuttar síður' ),
	'Specialpages'              => array( 'Serligar síður' ),
	'Statistics'                => array( 'Hagtøl' ),
	'Uncategorizedcategories'   => array( 'Óbólkaðir bólkar' ),
	'Uncategorizedimages'       => array( 'Óbólkaðar myndir' ),
	'Uncategorizedpages'        => array( 'Óbólkaðar síður' ),
	'Uncategorizedtemplates'    => array( 'Óbólkaðar fyrimyndir' ),
	'Undelete'                  => array( 'Endurstovna strikaðar síður' ),
	'Unusedcategories'          => array( 'Óbrúktir bólkar' ),
	'Unusedimages'              => array( 'Óbrúktar myndir' ),
	'Upload'                    => array( 'Legg fílu upp' ),
	'Userlogin'                 => array( 'Stovna kontu ella rita inn' ),
	'Userlogout'                => array( 'Rita út' ),
	'Version'                   => array( 'Útgáva' ),
	'Wantedpages'               => array( 'Ynsktar síður' ),
	'Watchlist'                 => array( 'Mítt eftirlit' ),
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',
);

$bookstoreList = array(
	'Bokasolan.fo' => 'http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1',
	'inherit' => true,
);

$linkTrail = '/^([áðíóúýæøa-z]+)(.*)$/sDu';

