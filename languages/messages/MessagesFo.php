<?php
/** Faroese (føroyskt)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
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

$specialPageAliases = [
	'Allmessages'               => [ 'Øll kervisboð' ],
	'Allpages'                  => [ 'Allar síður' ],
	'Ancientpages'              => [ 'Elstu síður' ],
	'Block'                     => [ 'Banna brúkara' ],
	'Booksources'               => [ 'Bóka keldur' ],
	'BrokenRedirects'           => [ 'Brotnar ávísingar' ],
	'Categories'                => [ 'Bólkar' ],
	'Contributions'             => [ 'Brúkaraíkast' ],
	'Deadendpages'              => [ 'Gøtubotns síður' ],
	'DoubleRedirects'           => [ 'Tvífaldað ávísing' ],
	'Emailuser'                 => [ 'Send t-post til brúkara' ],
	'Export'                    => [ 'Útflutningssíður' ],
	'Fewestrevisions'           => [ 'Greinir við minst útgávum' ],
	'BlockList'                 => [ 'Bannað brúkaranøvn og IP-adressur' ],
	'Listfiles'                 => [ 'Myndalisti' ],
	'Listusers'                 => [ 'Brúkaralisti' ],
	'Lonelypages'               => [ 'Foreldraleysar síður' ],
	'Longpages'                 => [ 'Langar síður' ],
	'Mostcategories'            => [ 'Greinir við flest bólkum' ],
	'Mostrevisions'             => [ 'Greinir við flest útgávum' ],
	'Movepage'                  => [ 'Flyt síðu' ],
	'Newimages'                 => [ 'Nýggjar myndir' ],
	'Newpages'                  => [ 'Nýggjar síður' ],
	'Preferences'               => [ 'Innstillingar' ],
	'Randompage'                => [ 'Tilvildarlig síða' ],
	'Recentchanges'             => [ 'Seinastu broytingar' ],
	'Search'                    => [ 'Leita' ],
	'Shortpages'                => [ 'Stuttar síður' ],
	'Specialpages'              => [ 'Serligar síður' ],
	'Statistics'                => [ 'Hagtøl' ],
	'Uncategorizedcategories'   => [ 'Óbólkaðir bólkar' ],
	'Uncategorizedimages'       => [ 'Óbólkaðar myndir' ],
	'Uncategorizedpages'        => [ 'Óbólkaðar síður' ],
	'Uncategorizedtemplates'    => [ 'Óbólkaðar fyrimyndir' ],
	'Undelete'                  => [ 'Endurstovna strikaðar síður' ],
	'Unusedcategories'          => [ 'Óbrúktir bólkar' ],
	'Unusedimages'              => [ 'Óbrúktar myndir' ],
	'Upload'                    => [ 'Legg fílu upp' ],
	'Userlogin'                 => [ 'Stovna kontu ella rita inn' ],
	'Userlogout'                => [ 'Rita út' ],
	'Version'                   => [ 'Útgáva' ],
	'Wantedpages'               => [ 'Ynsktar síður' ],
	'Watchlist'                 => [ 'Mítt eftirlit' ],
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

