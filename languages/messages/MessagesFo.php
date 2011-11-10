<?php
/** Faroese (Føroyskt)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Diupwijk
 * @author EileenSanda
 * @author Krun
 * @author Quackor
 * @author S.Örvarr.S
 * @author Spacebirdy
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$bookstoreList = array(
	'Bokasolan.fo' => 'http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1',
	'inherit' => true,
);

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


$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',
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
	'Disambiguations'           => array( 'Síður við fleirfaldum týdningi' ),
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

$linkTrail = '/^([áðíóúýæøa-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Undurstrika ávísingar',
'tog-highlightbroken'         => 'Brúka reyða ávísing til tómar síður',
'tog-justify'                 => 'Stilla greinpart',
'tog-hideminor'               => 'Goym minni broytingar í seinast broytt listanum',
'tog-extendwatchlist'         => 'Víðka eftirlitslistan fyri at vísa allar broytingar, ikki bara tær seinastu',
'tog-usenewrc'                => 'Nýt betraðar seinastu broytingar (krevur JavaScript)',
'tog-numberheadings'          => 'Sjálvtalmerking av yvirskrift',
'tog-showtoolbar'             => 'Vís amboðslinju í rætting',
'tog-editondblclick'          => 'Rætta síðu við at tvíklikkja (JavaScript)',
'tog-editsection'             => 'Rætta greinpart við hjálp av [rætta]-ávísing',
'tog-editsectiononrightclick' => 'Rætta greinpart við at høgraklikkja á yvirskrift av greinparti (JavaScript)',
'tog-showtoc'                 => 'Vís innihaldsyvurlit (Til greinir við meira enn trimun greinpartum)',
'tog-rememberpassword'        => 'Minst til loyniorð á hesum kaga (í mesta lagi $1 {{PLURAL:$1|dag|dagar}})',
'tog-watchcreations'          => 'Legg síður, sum eg stovni, í mítt eftirlit',
'tog-watchdefault'            => 'Vaka yvur nýggjum og broyttum greinum',
'tog-watchmoves'              => 'Legg síður afturat, sum eg havi valt afturat mínum eftirkanningarlista.',
'tog-minordefault'            => 'Merk sum standard allar broytingar sum smærri',
'tog-previewontop'            => 'Vís forhondsvísning áðren rættingarkassan',
'tog-previewonfirst'          => 'Sýn forskoðan við fyrstu broyting',
'tog-nocache'                 => 'Deaktivera síðu "caching" í brovsaranum',
'tog-enotifwatchlistpages'    => 'Send mær teldupost, tá ein síða á mínum eftirlitslista er broytt',
'tog-enotifusertalkpages'     => 'Send mær teldubræv, tá mín brúarasíða er broytt',
'tog-enotifminoredits'        => 'Send mær eisini teldupost viðvíkjandi smærri broytingum á síðunum',
'tog-enotifrevealaddr'        => 'Avdúkað mína teldupost adressu í fráboðanar teldupostum',
'tog-shownumberswatching'     => 'Vís tal av brúkarum sum fylgja við',
'tog-oldsig'                  => 'Verandi undirskrift:',
'tog-fancysig'                => 'Viðgerð undirskriftina sum wikitekstur (uttan sjálvvirkandi leinkju)',
'tog-externaleditor'          => 'Nýt útvortis ritil sum fyrimynd (bert fyri fólk við serkunnleika, tað er tørvur á serligum innstillingum á tínari teldu. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff'            => 'Nýt útvortis diff sum fyrimynd (bert fyri serfrøðingar, tín telda tørvar serligar innstillingar. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-showjumplinks'           => 'Ger "far til"-tilgongd virkna',
'tog-uselivepreview'          => 'Nýt "live preview" (tørvar JavaScript) (á royndarstøði)',
'tog-forceeditsummary'        => 'Gev mær boð, um eg ikki havi skrivað ein samandrátt um mína rætting',
'tog-watchlisthideown'        => 'Fjal mínar rættingar frá eftirliti',
'tog-watchlisthidebots'       => 'Fjal bot rættingar frá eftirliti',
'tog-watchlisthideminor'      => 'Fjal minni rættingar frá eftirliti',
'tog-watchlisthideliu'        => 'Goym broytingar sum eru gjørdar av brúkarum, sum eru loggaðir á, frá hyggjaralistanum',
'tog-watchlisthideanons'      => 'Goym broytingar sum eru gjørdar av dulnevndum brúkarum frá hyggjaralistanum',
'tog-watchlisthidepatrolled'  => 'Fjal eftirhugdar broytingar frá eftirlitslistanum',
'tog-ccmeonemails'            => 'Send mær avrit av teldubrøvum, sum eg sendi til aðrir brúkarar',
'tog-diffonly'                => 'Vís ikki innihaldið av síðuni undir diffs',
'tog-showhiddencats'          => 'Vís goymdir bólkar',
'tog-norollbackdiff'          => 'Síggj burtur frá diff eftir eina afturrulling',

'underline-always'  => 'Altíð',
'underline-never'   => 'Ongantíð',
'underline-default' => 'Kagarastandard',

# Font style option in Special:Preferences
'editfont-style'   => 'Rættað økið typografi:',
'editfont-default' => 'Kagi (brovsari) standard',

# Dates
'sunday'        => 'sunnudagur',
'monday'        => 'mánadagur',
'tuesday'       => 'týsdagur',
'wednesday'     => 'mikudagur',
'thursday'      => 'hósdagur',
'friday'        => 'fríggjadagur',
'saturday'      => 'leygardagur',
'sun'           => 'sun',
'mon'           => 'mán',
'tue'           => 'týs',
'wed'           => 'mik',
'thu'           => 'hós',
'fri'           => 'frí',
'sat'           => 'ley',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'mars',
'april'         => 'apríl',
'may_long'      => 'mai',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'august',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'desember',
'january-gen'   => 'januar',
'february-gen'  => 'februar',
'march-gen'     => 'mars',
'april-gen'     => 'apríl',
'may-gen'       => 'mai',
'june-gen'      => 'juni',
'july-gen'      => 'juli',
'august-gen'    => 'august',
'september-gen' => 'september',
'october-gen'   => 'oktober',
'november-gen'  => 'november',
'december-gen'  => 'desember',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

# Categories related messages
'pagecategories'                => '{{PLURAL:$1|Bólkur|Bólkar}}',
'category_header'               => 'Greinir í bólki "$1"',
'subcategories'                 => 'Undirbólkur',
'category-media-header'         => 'Media í bólkur "$1"',
'category-empty'                => "''Hesin bólkur inniheldur ongar greinir ella miðlar í løtuni.''",
'hidden-categories'             => '{{PLURAL:$1|Hidden category|Fjaldir bólkar}}',
'hidden-category-category'      => 'Fjaldir bólkar',
'category-subcat-count'         => '{{PLURAL:$2|Hesin bólkur hevur bert henda undirbólk.|Hesin bólkur hevur fylgjandi {{PLURAL:$1|undirbólk|$1 undirbólkar}}, av $2 í alt.}}',
'category-subcat-count-limited' => 'Hesin bólkur hevur fylgjandi {{PLURAL:$1|undirbólk|$1 undirbólkar}}.',
'listingcontinuesabbrev'        => 'frh.',
'broken-file-category'          => 'Síður við brotnum fílu slóðum',

'about'         => 'Um',
'article'       => 'Innihaldssíða',
'newwindow'     => '(kemur í nýggjan glugga)',
'cancel'        => 'Ógilda',
'moredotdotdot' => 'Meira...',
'mypage'        => 'Mín síða',
'mytalk'        => 'Mítt kjak',
'anontalk'      => 'Kjak til hesa ip-adressuna',
'navigation'    => 'Navigatión',
'and'           => '&#32;og',

# Cologne Blue skin
'qbfind'         => 'Finn',
'qbbrowse'       => 'Kaga',
'qbedit'         => 'Rætta',
'qbpageoptions'  => 'Henda síðan',
'qbpageinfo'     => 'Samanhangur',
'qbmyoptions'    => 'Mínar síður',
'qbspecialpages' => 'Serstakar síður',
'faq'            => 'OSS',
'faqpage'        => 'Project:OSS',

# Vector skin
'vector-action-addsection' => 'Nýtt evni',
'vector-action-delete'     => 'Strika',
'vector-action-move'       => 'Flyt',
'vector-action-protect'    => 'Friða',
'vector-action-undelete'   => 'Endurstovna',
'vector-action-unprotect'  => 'Broyt friðing',
'vector-view-create'       => 'Stovna',
'vector-view-edit'         => 'Rætta',
'vector-view-history'      => 'Søga',
'vector-view-view'         => 'Les',
'vector-view-viewsource'   => 'Vís keldu',
'namespaces'               => 'Navnarúm',
'variants'                 => 'Ymisk sløg',

'errorpagetitle'    => 'Villa',
'returnto'          => 'Vend aftur til $1.',
'tagline'           => 'Frá {{SITENAME}}',
'help'              => 'Hjálp',
'search'            => 'Leita',
'searchbutton'      => 'Leita',
'go'                => 'Far til',
'searcharticle'     => 'Far',
'history'           => 'Síðusøga',
'history_short'     => 'Søga',
'updatedmarker'     => 'dagført síðan mína seinastu vitjan',
'printableversion'  => 'Prentvinarlig útgáva',
'permalink'         => 'Støðug slóð',
'print'             => 'Prenta',
'view'              => 'Les',
'edit'              => 'Rætta',
'create'            => 'Stovna',
'editthispage'      => 'Rætta hesa síðuna',
'create-this-page'  => 'Stovna hesa síðuna',
'delete'            => 'Strika',
'deletethispage'    => 'Strika hesa síðuna',
'undelete_short'    => 'Ógilda striking av {{PLURAL:$1|broyting|$1 broytingar}}',
'viewdeleted_short' => 'Vís {{PLURAL:$1|eina strikaða broyting|$1 strikaðar broytingar}}',
'protect'           => 'Friða',
'protect_change'    => 'broyt',
'protectthispage'   => 'Friða hesa síðuna',
'unprotect'         => 'Broyt friðing',
'unprotectthispage' => 'Broyt verju av hesi síðu',
'newpage'           => 'Nýggj síða',
'talkpage'          => 'Kjakast um hesa síðuna',
'talkpagelinktext'  => 'Kjak',
'specialpage'       => 'Serlig síða',
'personaltools'     => 'Persónlig amboð',
'postcomment'       => 'Nýtt brot',
'articlepage'       => 'Skoða innihaldssíðuna',
'talk'              => 'Kjak',
'views'             => 'Skoðanir',
'toolbox'           => 'Amboð',
'userpage'          => 'Vís brúkarisíðu',
'projectpage'       => 'Vís verkætlanarsíðu',
'imagepage'         => 'Vís síðu við fílum',
'mediawikipage'     => 'Vís kervisboðsíðu',
'templatepage'      => 'Vís fyrimyndsíðu',
'viewhelppage'      => 'Vís hjálpsíðu',
'categorypage'      => 'Vís bólkursíðu',
'viewtalkpage'      => 'Vís kjak',
'otherlanguages'    => 'Onnur mál',
'redirectedfrom'    => '(Ávíst frá $1)',
'redirectpagesub'   => 'Ávísingarsíða',
'lastmodifiedat'    => 'Hendan síðan var seinast broytt $2, $1.',
'viewcount'         => 'Onkur hevur verið á hesi síðu {{PLURAL:$1|eina ferð|$1 ferðir}}.',
'protectedpage'     => 'Friðað síða',
'jumpto'            => 'Far til:',
'jumptonavigation'  => 'navigatión',
'jumptosearch'      => 'leita',
'view-pool-error'   => 'Haldið okkum til góðar, servarnir hava ov nógv at gera í løtuni.
Ov nógvir brúkarir royna at síggja hesa síðuna.
Vinarliga bíða eina løtu, áðrenn tú roynir enn einaferð at fáa atgongd til hesa síðuna.

$1',
'pool-timeout'      => 'Støðgur bíða verður eftir lásinum',
'pool-queuefull'    => 'Køin til "hylin" er full',
'pool-errorunknown' => 'Ókend villa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Um {{SITENAME}}',
'aboutpage'            => 'Project:Um',
'copyright'            => 'Innihald er tøkt undir $1.',
'copyrightpage'        => '{{ns:project}}:Útgávurættur',
'currentevents'        => 'Núverandi hendingar',
'currentevents-url'    => 'Project:Núverandi hendingar',
'disclaimers'          => 'Fyrivarni',
'disclaimerpage'       => 'Project:Fyrivarni',
'edithelp'             => 'Rættihjálp',
'edithelppage'         => 'Help:Rættihjálp',
'helppage'             => 'Help:Innihald',
'mainpage'             => 'Forsíða',
'mainpage-description' => 'Forsíða',
'policy-url'           => 'Project:Handfaring av persónligum upplýsingum',
'portal'               => 'Forsíða fyri høvundar',
'portal-url'           => 'Project:Forsíða fyri høvundar',
'privacy'              => 'Handfaring av persónligum upplýsingum',
'privacypage'          => 'Project:Handfaring av persónligum upplýsingum',

'badaccess'        => 'Loyvisbrek',
'badaccess-group0' => 'Tú hevur ikki loyvi til at útføra hatta sum tú hevur biðið um.',
'badaccess-groups' => 'Tað sum tú hevur biðið um at sleppa at gera er avmarkað til brúrkarar í {{PLURAL:$2|bólkinum|einum av bólkunum}}: $1.',

'ok'                      => 'Í lagi',
'retrievedfrom'           => 'Heinta frá "$1"',
'youhavenewmessages'      => 'Tú hevur $1 ($2).',
'newmessageslink'         => 'nýggj boð',
'newmessagesdifflink'     => 'seinasta broyting',
'youhavenewmessagesmulti' => 'Tú hevur nýggj boð á $1',
'editsection'             => 'rætta',
'editold'                 => 'rætta',
'viewsourceold'           => 'vís keldu',
'editlink'                => 'rætta',
'viewsourcelink'          => 'vís keldu',
'editsectionhint'         => 'Rætta part: $1',
'toc'                     => 'Innihaldsyvirlit',
'showtoc'                 => 'skoða',
'hidetoc'                 => 'fjal',
'collapsible-collapse'    => 'Samanbrot',
'collapsible-expand'      => 'Víðka',
'thisisdeleted'           => 'Sí ella endurstovna $1?',
'viewdeleted'             => 'Vís $1?',
'restorelink'             => '{{PLURAL:$1|strikaða rætting|$1 strikaðar rættingar}}',
'feedlinks'               => 'Føðing:',
'site-rss-feed'           => '$1 RSS Fóðurið',
'site-atom-feed'          => '$1 Atom Fóðurið',
'page-rss-feed'           => '"$1" RSS Feed',
'red-link-title'          => '$1 (síðan er ikki til)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Grein',
'nstab-user'      => 'Brúkarasíða',
'nstab-media'     => 'Miðil',
'nstab-special'   => 'Serstøk síða',
'nstab-project'   => 'Verkætlanarsíða',
'nstab-image'     => 'Mynd',
'nstab-mediawiki' => 'Grein',
'nstab-template'  => 'Fyrimynd',
'nstab-help'      => 'Hjálp',
'nstab-category'  => 'Bólkur',

# Main script and global functions
'nosuchaction'      => 'Ongin slík gerð',
'nosuchactiontext'  => "Gerðin, ið tilskilað er í url, virkar ikki.
Møguliga hevur tú stava urlin skeivt, ella fylgt einari skeivari leinkju.
Hetta kann eisini benda á ein feil í software'ini sum {{SITENAME}} brúkar.",
'nosuchspecialpage' => 'Ongin slík serlig síða',
'nospecialpagetext' => '<strong>Tú hevur biðið um eina serliga síðu, sum wiki ikki kennir aftur.</strong>

<!-- A list of valid special pages can be found at [[Special:SpecialPages]]. -->',

# General errors
'error'               => 'Villa',
'databaseerror'       => 'Villa í dátagrunni',
'internalerror'       => 'Innvortis brek',
'filecopyerror'       => 'Kundi ikki avrita fíluna "$1" til "$2".',
'filerenameerror'     => 'Kundi ikki umdoypa fílu "$1" til "$2".',
'filedeleteerror'     => 'Kundi ikki strika fíluna "$1".',
'filenotfound'        => 'Kundi ikki finna fílu "$1".',
'badarticleerror'     => 'Hendan gerðin kann ikki fremjast á hesi síðu.',
'cannotdelete'        => 'Síðan ella fílan $1 kundi ikki strikast. 
Møguliga hevur onkur annar longu strikað hana.',
'cannotdelete-title'  => 'Kann ikki strika síðu "$1"',
'badtitle'            => 'Ógyldugt heiti',
'badtitletext'        => 'Umbidna síðan er ógyldugt, tómt ella skeivt tilslóðað heiti millum mál ella wikur.',
'perfcached'          => 'Fylgjandi upplýsingar eru "cached" og eru møguliga ikki dagførdir.',
'perfcachedts'        => 'Fylgjandi dáta er goymt, og var seinast goymt $1.',
'viewsource'          => 'Vís keldu',
'protectedpagetext'   => 'Hendan síða er læst fyri at steðga rættingum.',
'viewsourcetext'      => 'Tú kanst síggja og avrita kelduna til hesa grein:',
'namespaceprotected'  => 'Tú hevur ikki loyvi til at rætta síður í $1 navnateiginum.',
'customcssprotected'  => 'Tú hevur ikki loyvi til at rætta hesa CSS síðuna, tí hon inniheldur persónligar innstillingar hjá øðrum brúkara.',
'customjsprotected'   => 'Tú hevur ikki loyvir til at rætta hesa JavaScript síðuna, tí hon inniheldur persónligar innstillingar hjá øðrum brúkara.',
'ns-specialprotected' => 'Serstakar síður kunnu ikki rættast.',
'titleprotected'      => '[[User:$1|$1]] hevur vart hetta heitið frá skapan.
Givin orsøk er "\'\'$2\'\'".',

# Login and logout pages
'logouttext'                 => "'''Tú hevur nú ritað út.'''
Tú kanst halda fram at brúka {{SITENAME}} sum dulnevndur, ella kanst tú [[Special:UserLogin|logga á aftur]] sum sami ella sum annar brúkari. 
Legg til merkis, at summar síður framvegis vera vístar, sum um tú enn vart loggaður á, til tú hevur reinsa tín brovsara fyri \"cache\".",
'welcomecreation'            => '== Vælkomin, $1! ==

Tín konta er nú stovnað.
Gloym ikki at broyta tínar [[Special:Preferences|{{SITENAME}} innstillingar]].',
'yourname'                   => 'Títt brúkaranavn:',
'yourpassword'               => 'Títt loyniorð:',
'yourpasswordagain'          => 'Skriva loyniorð umaftur:',
'remembermypassword'         => 'Minst til logg inn hjá mær á hesum kaganum (í mesta lagi í $1 {{PLURAL:$1|dag|dagar}})',
'securelogin-stick-https'    => 'Varðveit sambandið við HTTPS eftir logg inn',
'yourdomainname'             => 'Títt domene:',
'login'                      => 'Rita inn',
'nav-login-createaccount'    => 'Stovna kontu ella rita inn',
'loginprompt'                => 'Cookies má verða sett til fyri at innrita á {{SITENAME}}.',
'userlogin'                  => 'Stovna kontu ella rita inn',
'userloginnocreate'          => 'Rita inn',
'logout'                     => 'Útrita',
'userlogout'                 => 'Rita út',
'notloggedin'                => 'Ikki ritað inn',
'nologin'                    => "Hevur tú ikki eina kontu? '''$1'''.",
'nologinlink'                => 'Stovna eina kontu',
'createaccount'              => 'Stovna nýggja kontu',
'gotaccount'                 => "Hevur tú longu eina kontu? '''$1'''.",
'gotaccountlink'             => 'Rita inn',
'userlogin-resetlink'        => 'Hevur tú gloymt tínar logg inn upplýsingar',
'createaccountmail'          => 'eftur t-posti',
'createaccountreason'        => 'Orsøk:',
'badretype'                  => 'Loyniorðið tú hevur skriva er ikki rætt.',
'loginerror'                 => 'Innritanarbrek',
'noname'                     => 'Tú hevur ikki skrivað eitt gyldugt brúkaranavn.',
'loginsuccesstitle'          => 'Innritan væleydnað',
'loginsuccess'               => "'''Tú hevur nú ritað inn í {{SITENAME}} sum \"\$1\".'''",
'nosuchuser'                 => 'Eingin brúkari er við navninum "$1". 
Brúkaranøvn eru følsom fyri stórum og lítlum bókstavum.
Eftirkanna um tú hevur stavað rætt, ella [[Special:UserLogin/signup|stovna eina nýggja konto]].',
'nosuchusershort'            => 'Eingin brúkari er við navninum "$1". Kanna stavseting.',
'nouserspecified'            => 'Tú mást skriva eitt brúkaranavn.',
'login-userblocked'          => 'Hesin brúkarin er blokkaður. Tað er ikki loyvt at logga á.',
'wrongpassword'              => 'Loyniorðið, sum tú skrivaði, er skeivt. Vinaliga royn aftur.',
'wrongpasswordempty'         => 'Loyniorð manglar. Vinarliga royn aftur.',
'passwordtooshort'           => 'Loyniorð mugu vera í minsta lagi {{PLURAL:$1|1 bókstav, tal, tekn|$1 bókstavir, tøl og tekn}}.',
'password-name-match'        => 'Loyniorðið hjá tær má vera annarleiðis enn títt brúkaranavn.',
'password-login-forbidden'   => 'Tað er ikki loyvt at brúka hetta brúkaranavnið og loyniorðið.',
'mailmypassword'             => 'Send mær eitt nýtt loyniorð við t-posti',
'passwordremindertitle'      => 'Nýtt fyribils loyniorð fyri {{SITENAME}}',
'passwordsent'               => 'Eitt nýtt loyniorð er sent til t-postadressuna,
sum er skrásett fyri "$1".
Vinarliga rita inn eftir at tú hevur fingið hana.',
'acct_creation_throttle_hit' => 'Vitjandi á hesi wiki, sum nýta tína IP addressu, hava stovnað {{PLURAL:$1|1 kontu|$1 kontur}} seinastu dagarnar, sum er mest loyvda hetta tíðarskeið.
Sum eitt úrslit av hesum, kunnu vitjandi sum brúka hesa IP adressuna ikki stovna fleiri kontur í løtuni.',
'emailauthenticated'         => 'Tín t-post adressa varð váttað hin $2 kl. $3.',
'emailnotauthenticated'      => 'Tín t-post adressa er enn ikki komin í gildi. Ongin t-postur
verður sendur fyri nakað av fylgjandi hentleikum.',
'emailconfirmlink'           => 'Vátta tína t-post adressu',
'accountcreated'             => 'Konto upprættað',
'loginlanguagelabel'         => 'Mál: $1',

# Change password dialog
'resetpass'                 => 'Broyt loyniorð',
'resetpass_announce'        => 'Tú ritaði inn við einum fyribils loyniorði, sum tú hevur fingið við telduposti.
Fyri at gera innritanina lidna, mást tú velja tær eitt nýtt loyniorð her:',
'resetpass_header'          => 'Broyt loyniorði á kontuni',
'oldpassword'               => 'Gamalt loyniorð:',
'newpassword'               => 'Nýtt loyniorð:',
'retypenew'                 => 'Skriva nýtt loyniorð umaftur:',
'resetpass_submit'          => 'Vel loyniorð og rita inn',
'resetpass_success'         => 'Tað hevur eydnast tær at broyta títt loyniorð!
Nú verður tú ritaður inn...',
'resetpass_forbidden'       => 'Loyniorð kunnu ikki broytast',
'resetpass-no-info'         => 'Tú mást vera loggaður á fyri at fáa beinleiðis atgongd til hesa síðu.',
'resetpass-submit-loggedin' => 'Broyt loyniorð',
'resetpass-submit-cancel'   => 'Ógildað',

# Special:PasswordReset
'passwordreset-emailelement'       => 'Brúkaranavn: $1
Fyribils loyniorð: $2',
'passwordreset-emailsent'          => 'Ein áminningar teldupostur er blivin sendur.',
'passwordreset-emailsent-capture'  => 'Ein áminningar teldupostur er blivin sendur, sum víst niðanfyri.',
'passwordreset-emailerror-capture' => 'Ein áminningar teldupostur var gjørdur, sum víst niðanfyri, men tað miseydnaðist at senda til brúkaran: $1',

# Special:ChangeEmail
'changeemail'        => 'Broyt teldupost adressu',
'changeemail-submit' => 'Broyt t-post',
'changeemail-cancel' => 'Ógilda',

# Edit page toolbar
'bold_sample'     => 'Feitir stavir',
'bold_tip'        => 'Feitir stavir',
'italic_sample'   => 'Skákstavir',
'italic_tip'      => 'Skákstavir',
'link_sample'     => 'Slóðarheiti',
'link_tip'        => 'Innanhýsis slóð',
'extlink_sample'  => 'http://www.example.com slóðarheiti',
'extlink_tip'     => 'Útvortis slóð (minst til http:// forskoytið)',
'headline_sample' => 'Yvirskriftartekstur',
'headline_tip'    => 'Annars stigs yvirskrift',
'nowiki_sample'   => 'Skriva ikki-formateraðan tekst her',
'nowiki_tip'      => 'Ignorera wiki-forsniðan',
'image_sample'    => 'Dømi.jpg',
'image_tip'       => 'Innset mynd',
'media_sample'    => 'Dømi.ogg',
'media_tip'       => 'Fílu slóð',
'sig_tip'         => 'Tín undirskrift við tíðarstempli',
'hr_tip'          => 'Vatnrøtt linja (vera sparin við)',

# Edit pages
'summary'                  => 'Samandráttur:',
'subject'                  => 'Evni/heiti:',
'minoredit'                => 'Hetta er smábroyting',
'watchthis'                => 'Hav eftirlit við hesi síðuni',
'savearticle'              => 'Goym síðu',
'preview'                  => 'Forskoðan',
'showpreview'              => 'Forskoðan',
'showlivepreview'          => 'Beinleiðis forskoðan',
'showdiff'                 => 'Sýn broytingar',
'anoneditwarning'          => "'''Ávaring:''' Tú hevur ikki ritað inn.
Tín IP-adressa verður goymd í rættisøguni fyri hesa síðuna.",
'anonpreviewwarning'       => "''Tú ert ikki innritað/ur. Um tú goymir nú, so verður tín IP adressa goymd í rættingar søguni hjá hesi síðu. ''",
'summary-preview'          => 'Samandráttaforskoðan:',
'blockedtitle'             => 'Brúkarin er bannaður',
'loginreqtitle'            => 'Innritan kravd',
'loginreqlink'             => 'rita inn',
'accmailtitle'             => 'Loyniorð sent.',
'accmailtext'              => "Eitt tilvildarliga valt loyniorð fyri brúkaran [[User talk:$1|$1]] er blivið  sent til $2.
Loyniorðið fyri hesa nýggju kontuna kann verða broytt á ''[[Special:ChangePassword|broyt loyniorð]]'' síðuni tá tú ritar inn.",
'newarticle'               => '(Nýggj)',
'newarticletext'           => "Tú ert komin eftir eini slóð til eina síðu, ið ikki er til enn. Skriva í kassan niðanfyri, um tú vilt byrja uppá hesa síðuna.
(Sí [[{{MediaWiki:Helppage}}|hjálparsíðuna]] um tú ynskir fleiri upplýsingar).
Ert tú komin higar av einum mistaki, kanst tú trýsta á '''aftur'''-knøttin á kagaranum.",
'anontalkpagetext'         => "----''Hetta er ein kjaksíða hjá einum dulnevndum brúkara, sum ikki hevur stovnað eina kontu enn, ella ikki brúkar hana. 
Tí noyðast vit at brúka nummerisku IP-adressuna hjá honum ella henni.
Ein slík IP-adressa kann verða brúkt av fleiri brúkarum.
Ert tú ein dulnevndur brúkari, og meinar, at óviðkomandi viðmerkingar eru vendar til tín, so er best fyri teg at [[Special:UserLogin/signup|stovna eina kontu]] ella [[Special:UserLogin|rita inn]] fyri at sleppa undan samanblanding við aðrar dulnevndar brúkarar í framtíðini.''",
'clearyourcache'           => "'''Viðmerking:''' Eftir at hava goymt mást tú fara uttanum minnið á sneytara tínum fyri at síggja broytingarnar.
* '''Firefox / Safari:''' Halt ''Shift'' meðan tú klikkir á ''Reload'', ella trýst antin ''Ctrl-F5'' ella ''Ctrl-R'' (''Command-R'' á einari Mac)
* '''Google Chrome:''' Trýst ''Ctrl-Shift-R'' (''Command-Shift-R'' á einari Mac)
* '''Internet Explorer:''' Halt ''Ctrl'' meðan tú trýstir á ''Refresh'', ella trýst á ''Ctrl-F5''
* '''Konqueror:''' Trýst ''Reload'' ella trýst ''F5''
* '''Opera:''' Reinsa cache í ''Tools → Preferences''",
'note'                     => "'''Viðmerking:'''",
'previewnote'              => "'''Minst til at hetta bara er ein forskoðan, sum enn ikki er goymd!'''",
'previewconflict'          => 'Henda forskoðanin vísir tekstin í erva soleiðis sum hann sær út, um tú velur at goyma.',
'editing'                  => 'Tú rættar $1',
'editingsection'           => 'Tú rættar $1 (partur)',
'editingcomment'           => 'Tú rættar $1 (nýtt brot)',
'editconflict'             => 'Rættingar konflikt: $1',
'yourtext'                 => 'Tín tekstur',
'storedversion'            => 'Goymd útgáva',
'yourdiff'                 => 'Munir',
'copyrightwarning'         => "Alt íkast til {{SITENAME}} er útgivið undir $2 (sí $1 fyri smálutir). Vilt tú ikki hava skriving tína broytta miskunnarleyst og endurspjadda frítt, so send hana ikki inn.<br />
Við at senda arbeiði títt inn, lovar tú, at tú hevur skrivað tað, ella at tú hevur avritað tað frá tilfeingi ið er almenn ogn &mdash; hetta umfatar '''ikki''' flestu vevsíður.
'''SEND IKKI UPPHAVSRÆTTARVART TILFAR UTTAN LOYVI!'''",
'protectedpagewarning'     => "'''Ávaring: Henda síðan er friðað, so at einans brúkarar við umboðsstjóra heimildum kunnu broyta hana.'''
Tann seinasta logg inn er goymt niðanfyri fyri ávísing:",
'semiprotectedpagewarning' => "'''Viðmerking:''' Hendan grein er vard soleiðis at bert skrásetir brúkarar kunnu rætta hana.
Tann seinasta innritanin er víst niðanfyri sum ávísing:",
'templatesused'            => '{{PLURAL:$1|Fyrimynd|Fyrimyndir}} brúktar á hesu síðu:',
'templatesusedpreview'     => '{{PLURAL:$1|Fyrimynd|Fyrimyndir}} brúktar í hesi forskoðan:',
'template-protected'       => '(friðað)',

# History pages
'viewpagelogs'        => 'Sí logg fyri hesa grein',
'nohistory'           => 'Eingin broytisøga er til hesa síðuna.',
'currentrev'          => 'Núverandi endurskoðan',
'revisionasof'        => 'Endurskoðan frá $1',
'previousrevision'    => '←Eldri endurskoðan',
'nextrevision'        => 'Nýggjari endurskoðan→',
'currentrevisionlink' => 'Skoða verandi endurskoðan',
'cur'                 => 'nú',
'next'                => 'næst',
'last'                => 'síðst',
'page_first'          => 'fyrsta',
'page_last'           => 'síðsta',
'histlegend'          => 'Frágreiðing:<br />
(nú) = munur til núverandi útgávu,
(síðst) = munur til síðsta útgávu, m = minni rættingar',
'histfirst'           => 'Elsta',
'histlast'            => 'Nýggjasta',
'historysize'         => '({{PLURAL:$1|1 být|$1 být}})',
'historyempty'        => '(tóm)',

# Revision deletion
'rev-delundel'          => 'skoða/fjal',
'revdelete-radio-set'   => 'Ja',
'revdelete-radio-unset' => 'Nei',
'revdelete-suppress'    => 'Síggj burtur frá data frá administratorum líka væl sum frá øðrum',

# History merging
'mergehistory-from' => 'Keldusíða:',

# Diffs
'difference'              => '(Munur millum endurskoðanir)',
'lineno'                  => 'Linja $1:',
'compareselectedversions' => 'Bera saman valdar útgávur',
'editundo'                => 'afturstilla',

# Search results
'searchresults'         => 'Leitúrslit',
'searchresulttext'      => 'Ynskir tú fleiri upplýsingar um leiting á {{SITENAME}}, kanst tú skoða [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Tú leitaði eftur \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|allar síður sum byrja við "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|allar síður sum leinkja til "$1"]])',
'searchsubtitleinvalid' => "Tú leitaði eftur '''$1'''",
'toomanymatches'        => 'Alt ov nógvar úrslit vóru funnin, vinarliga royn aftur við nýggjum fyrispurningi',
'titlematches'          => 'Síðu heiti samsvarar',
'notitlematches'        => 'Onki síðuheiti samsvarar',
'textmatches'           => 'Teksturin á síðuni samsvarar',
'notextmatches'         => 'Ongin síðutekstur samsvarar',
'prevn'                 => 'undanfarnu {{PLURAL:$1|$1}}',
'nextn'                 => 'næstu {{PLURAL:$1|$1}}',
'prevn-title'           => 'Gomul $1 {{PLURAL:$1|úrslit|úrslit}}',
'nextn-title'           => 'Næstu $1 {{PLURAL:$1|úrslit|úrslit}}',
'shown-title'           => 'Vís $1 {{PLURAL:$1|úrslit|úrslit}} á hvørjari síðu',
'viewprevnext'          => 'Vís ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'     => 'Leiti møguleikar',
'searchmenu-exists'     => "'''Tað er longu ein síða sum eitur \"[[:\$1]]\" á hesi wiki.'''",
'searchmenu-new'        => "'''Stovna síðuna \"[[:\$1]]\" á hesi wiki!'''",
'searchhelp-url'        => 'Help:Innihald',
'search-result-size'    => '$1 ({{PLURAL:$2|1 orð|$2 orð}})',
'showingresults'        => "Niðanfyri standa upp til {{PLURAL:$1|'''$1''' úrslit, sum byrjar|'''$1''' úrslit, sum byrja}} við #<b>$2</b>.",
'showingresultsnum'     => "Niðanfyri standa {{PLURAL:$3|'''1''' úrslit, sum byrjar|'''$3''' úrslit, sum byrja}} við #<b>$2</b>.",
'powersearch'           => 'Leita',

# Quickbar
'qbsettings'              => 'Skundfjøl innstillingar',
'qbsettings-none'         => 'Eingin',
'qbsettings-fixedleft'    => 'Fast vinstru',
'qbsettings-fixedright'   => 'Fast høgru',
'qbsettings-floatingleft' => 'Flótandi vinstru',

# Preferences page
'preferences'                 => 'Innstillingar',
'mypreferences'               => 'Mínar innstillingar',
'prefsnologin'                => 'Tú hevur ikki ritað inn',
'changepassword'              => 'Broyt loyniorð',
'prefs-skin'                  => 'Hamur',
'skin-preview'                => 'Forskoðan',
'prefs-datetime'              => 'Dato og tíð',
'prefs-personal'              => 'Brúkaradáta',
'prefs-rc'                    => 'Nýkomnar broytingar og stubbaskoðan',
'prefs-watchlist'             => 'Eftirlit',
'prefs-watchlist-days'        => 'Tal av døgum, sum skula vísast í eftirliti:',
'prefs-watchlist-days-max'    => 'Í mesta lagi 7 dagar',
'prefs-watchlist-edits'       => 'Tal av rættingum, sum skula vísast í víðkaðum eftirliti:',
'prefs-watchlist-edits-max'   => 'Í mesta lagi: 1000',
'prefs-misc'                  => 'Ymiskar innstillingar',
'prefs-resetpass'             => 'Broyt loyniorð',
'prefs-changeemail'           => 'Broyt t-post adressu',
'prefs-email'                 => 'T-post møguleikar',
'saveprefs'                   => 'Goym innstillingar',
'resetprefs'                  => 'Reinsa ikki goymdar broytingar',
'restoreprefs'                => 'Endurset alt til standard innstillingar',
'prefs-editing'               => 'Broyting av greinum',
'prefs-edit-boxsize'          => 'Støddin á rættingar vindeyganum.',
'rows'                        => 'Røð:',
'columns'                     => 'Teigar:',
'searchresultshead'           => 'Leita',
'resultsperpage'              => 'Úrslit fyri hvørja síðu:',
'stub-threshold-disabled'     => 'Er gjørt óvirki',
'recentchangesdays'           => 'Dagar av vísa í seinastu broytingum:',
'recentchangesdays-max'       => 'Í mesta lagi $1 {{PLURAL:$1|dagur|dagar}}',
'recentchangescount'          => 'Tal av rættingum at vísa í standard:',
'savedprefs'                  => 'Tínar innstillingar eru goymdar.',
'timezonelegend'              => 'Tíðar sona:',
'localtime'                   => 'Lokal tíð:',
'timezoneuseserverdefault'    => 'Nýt wiki standard: ($1)',
'timezoneoffset'              => 'Offset¹:',
'servertime'                  => 'Servara tíð:',
'guesstimezone'               => 'Fyll út við kagara',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Amerika',
'timezoneregion-antarctica'   => 'Antarktis',
'timezoneregion-arctic'       => 'Arktisk',
'timezoneregion-asia'         => 'Asia',
'timezoneregion-atlantic'     => 'Atlantarhavið',
'timezoneregion-australia'    => 'Avstralia',
'timezoneregion-europe'       => 'Evropa',
'timezoneregion-indian'       => 'Indiska Havið',
'timezoneregion-pacific'      => 'Stillahavið',
'allowemail'                  => 'Tilset t-post frá øðrum brúkarum',
'prefs-searchoptions'         => 'Leiti møguleikar',
'prefs-namespaces'            => 'Navnarúm',
'defaultns'                   => 'Um ikki, leita so í hesum navnateigum:',
'default'                     => 'standard',
'prefs-files'                 => 'Fílur',
'prefs-custom-css'            => 'Tilpassað CSS',
'prefs-custom-js'             => 'Tilpassað JavaScript',
'youremail'                   => 'T-postur (sjálvboðið)*:',
'username'                    => 'Brúkaranavn:',
'uid'                         => 'Brúkara ID:',
'yourrealname'                => 'Títt navn*:',
'yourlanguage'                => 'Mál til brúkaraflatu:',
'yournick'                    => 'Nýggj undirskrift:',
'yourgender'                  => 'Kyn:',
'gender-male'                 => 'Maður',
'gender-female'               => 'Kvinna',
'email'                       => 'T-post',
'prefs-help-realname'         => 'Veruligt navn er valfrítt.
Um tú velur at skriva tað her, so verður tað nýtt til at geva tær æruna fyri títt arbeiði.',
'prefs-help-email'            => 'Tú velur sjálvur, um tú vil skriva tína t-post adressu her, men tað er brúk fyri henni til at nullstilla loyniorðið, um tað skuldi hent, at tú gloymir títt loyniorð.',
'prefs-help-email-others'     => 'Tú kanst eisini velja at lata onnur seta seg í samband við teg við telduposti gjøgnum eina leinkju á tínari brúkara ella kjak síðu. 
Tín t-post adressa verður ikki avdúkað, tá aðrir brúkarir seta seg í samband við teg.',
'prefs-help-email-required'   => 'T-post adressa er kravd.',
'prefs-info'                  => 'Grundleggjandi kunning',
'prefs-advancedediting'       => 'Víðkaðir møguleikar',
'prefs-advancedrc'            => 'Víðkaðir møguleikar',
'prefs-advancedrendering'     => 'Víðkaðir møguleikar',
'prefs-advancedsearchoptions' => 'Víðkaðir møguleikar',
'prefs-advancedwatchlist'     => 'Víðkaðir møguleikar',
'prefs-displayrc'             => 'Vís møguleikar',
'prefs-displaysearchoptions'  => 'Vís møguleikar',
'prefs-displaywatchlist'      => 'Vís møguleikar',

# User rights
'saveusergroups' => 'Goym brúkaraflokk',

# Groups
'group'            => 'Bólkur:',
'group-bot'        => 'Bottar',
'group-sysop'      => 'Umboðsstjórar',
'group-bureaucrat' => 'Embætismenn',
'group-all'        => '(allir)',

'group-user-member'          => '{{GENDER:$1|brúkari}}',
'group-autoconfirmed-member' => '{{GENDER:$1|brúkari er váttaður sjálvvirkandi}}',
'group-bot-member'           => '{{GENDER:$1|bottur}}',
'group-sysop-member'         => '{{GENDER:$1|umboðsstjóri}}',
'group-bureaucrat-member'    => '{{GENDER:$1|embætismaður}}',
'group-suppress-member'      => '{{GENDER:$1|eftirlit}}',

'grouppage-user'          => '{{ns:project}}:Brúkarar',
'grouppage-autoconfirmed' => '{{ns:project}}:Sjálvvirkandi váttaðir brúkarar',
'grouppage-bot'           => '{{ns:project}}:Bottar',
'grouppage-sysop'         => '{{ns:project}}:Umboðsstjórar',
'grouppage-bureaucrat'    => '{{ns:project}}:Embætismenn',
'grouppage-suppress'      => '{{ns:project}}:Eftirlit',

# Rights
'right-read'          => 'Les síður',
'right-edit'          => 'Rætta síður',
'right-createpage'    => 'Stovna síður (sum ikki eru kjaksíður)',
'right-createtalk'    => 'Stovna kjaksíðu',
'right-createaccount' => 'Stovna nýggja brúkara kontu',
'right-minoredit'     => 'Markera rættingar sum smáar',
'right-move'          => 'Flyt síður',
'right-move-subpages' => 'Flyt síður saman við undirsíðum teirra',
'right-movefile'      => 'Flyt fílur',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'rætta hesa síðuna',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|broyting|broytingar}}',
'recentchanges'     => 'Seinastu broytingar',
'rcnote'            => "Niðanfyri {{PLURAL:$1|stendur '''1''' tann seinasta broytingin|standa '''$1''' tær seinastu broytingarnar}} {{PLURAL:$2|seinasta dagin|seinastu '''$2''' dagarnar}}, frá $5, $4.",
'rcnotefrom'        => "Niðanfyri standa broytingarnar síðani '''$2''', (upp til '''$1''' er sýndar).",
'rclistfrom'        => 'Sýn nýggjar broytingar byrjandi við $1',
'rcshowhideminor'   => '$1 minni rættingar',
'rcshowhidebots'    => '$1 bottar',
'rcshowhideliu'     => '$1 skrásettar brúkarar',
'rcshowhideanons'   => '$1 navnleysar brúkarar',
'rcshowhidemine'    => '$1 mínar rættingar',
'rclinks'           => 'Sýn seinastu $1 broytingarnar seinastu $2 dagarnar<br />$3',
'diff'              => 'munur',
'hist'              => 'søga',
'hide'              => 'Fjal',
'show'              => 'Skoða',
'minoreditletter'   => 's',
'newpageletter'     => 'N',
'boteditletter'     => 'b',
'rc_categories_any' => 'Nakar',
'newsectionsummary' => '/* $1 */ nýtt innlegg',

# Recent changes linked
'recentchangeslinked'         => 'Viðkomandi broytingar',
'recentchangeslinked-feed'    => 'Viðkomandi broytingar',
'recentchangeslinked-toolbox' => 'Viðkomandi broytingar',

# Upload
'upload'              => 'Legg fílu upp',
'uploadbtn'           => 'Legg fílu upp',
'uploadnologin'       => 'Ikki ritað inn',
'uploadnologintext'   => 'Tú mást hava [[Special:UserLogin|ritað inn]]
fyri at leggja fílur upp.',
'uploadlog'           => 'fílu logg',
'uploadlogpage'       => 'Fílugerðabók',
'filename'            => 'Fílunavn',
'filedesc'            => 'Samandráttur',
'fileuploadsummary'   => 'Samandráttur:',
'filestatus'          => 'Upphavsrættar støða:',
'filesource'          => 'Kelda:',
'uploadedfiles'       => 'Upplagdar fílur',
'ignorewarnings'      => 'Ikki vísa ávaringar',
'badfilename'         => 'Myndin er umnevnd til "$1".',
'savefile'            => 'Goym fílu',
'uploadedimage'       => 'sent "[[$1]]" upp',
'sourcefilename'      => 'Kelda fílunavn:',
'sourceurl'           => 'Kelda URL:',
'destfilename'        => 'Destinatión fílunavn:',
'upload-maxfilesize'  => 'Fílu støddin má í mesta lagi vera: $1',
'upload-description'  => 'Frágreiðing av fílu',
'upload-options'      => 'Upplótingar møguleikar',
'watchthisupload'     => 'Halt eyga við hesi fílu',
'filewasdeleted'      => 'Ein fíla við hesum heitinum hevur fyrr verið upplóta og er seinni blivin strikað.
Tú eigur at eftirkanna $1 áðrenn tú heldur á við at upplóta fíluna enn einaferð.',
'upload-success-subj' => 'Upplegging væleydnað',

'upload-file-error' => 'Innvortis brek',

'license'           => 'Lisensur:',
'license-header'    => 'Lisensur',
'nolicense'         => 'Onki valt',
'license-nopreview' => '(Fyrr ikki tøkt)',

# Special:ListFiles
'listfiles'      => 'Myndalisti',
'listfiles_name' => 'Navn',
'listfiles_user' => 'Brúkari',

# File description page
'file-anchor-link'  => 'Mynd',
'filehist'          => 'Søga fílu',
'filehist-current'  => 'streymur',
'filehist-datetime' => 'Dagur/Tíð',
'filehist-user'     => 'Brúkari',
'filehist-filesize' => 'Stødd fílu',
'filehist-comment'  => 'Viðmerking',
'imagelinks'        => 'Slóðir',
'linkstoimage'      => 'Hesar síður slóða til hesa mynd:',
'nolinkstoimage'    => 'Ongar síður slóða til hesa myndina.',

# File deletion
'filedelete'        => 'Strika $1',
'filedelete-submit' => 'Strika',

# MIME search
'mimesearch' => 'MIME-leit',

# List redirects
'listredirects' => 'Sýn ávísingar',

# Unused templates
'unusedtemplates'    => 'Óbrúktar fyrimyndir',
'unusedtemplateswlh' => 'aðrar slóðir',

# Random page
'randompage' => 'Tilvildarlig síða',

# Random redirect
'randomredirect' => 'Tilvildarlig ávísingarsíða',

# Statistics
'statistics'               => 'Hagtøl',
'statistics-header-edits'  => 'Rætti hagtøl',
'statistics-header-views'  => 'Vís hagtøl',
'statistics-header-users'  => 'Brúkarahagtøl',
'statistics-header-hooks'  => 'Onnur hagtøl',
'statistics-pages'         => 'Síður',
'statistics-pages-desc'    => 'Allar síður í wiki, kjaksíður, ávísingar og so framvegis rokna uppí',
'statistics-files'         => 'Fílur lagdar upp',
'statistics-edits-average' => 'Miðal rættingar pr. síðu',
'statistics-users'         => 'Skrásettir [[Special:ListUsers|brúkarir]]',
'statistics-users-active'  => 'Virknir brúkarir',
'statistics-mostpopular'   => 'Mest sæddu síður',

'disambiguations'     => 'Síður við fleirfaldum týdningi',
'disambiguationspage' => 'Template:fleiri týdningar',

'doubleredirects'            => 'Tvífaldað ávísing',
'doubleredirectstext'        => 'Henda síða gevur yvirlit yvir síður, sum vísa víðari til aðrar víðaristillaðar síður.
Hvør linja inniheldur leinkjur til ta fyrstu og næstu víðaristillingina, eins væl og málið fyri tað næstu víðaristillingina, sum vanliga er tann "veruliga" endamáls síðan, sum tann fyrsta víðaristillingin átti at peika móti.
<del>Útkrossaðir</del> postar eru loystir.',
'double-redirect-fixed-move' => '[[$1]] er blivin flutt.
Víðaristilling verður nú gjørd til [[$2]].',

'brokenredirects'        => 'Brotnar ávísingar',
'brokenredirectstext'    => 'Hesar víðaristillingar slóða til síður, ið ikki eru til:',
'brokenredirects-edit'   => 'rætta',
'brokenredirects-delete' => 'strika',

'withoutinterwiki'         => 'Síður uttan málslóðir',
'withoutinterwiki-summary' => 'Fylgjandi síður slóða ikki til útgávur á øðrum málum:',
'withoutinterwiki-submit'  => 'Skoða',

'fewestrevisions' => 'Greinir við minstum útgávum',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|být|být}}',
'ncategories'             => '$1 {{PLURAL:$1|bólkur|bólkar}}',
'nlinks'                  => '$1 {{PLURAL:$1|slóð|slóðir}}',
'nmembers'                => '$1 {{PLURAL:$1|limur|limir}}',
'nviews'                  => '$1 {{PLURAL:$1|skoðan|skoðanir}}',
'lonelypages'             => 'Foreldraleysar síður',
'uncategorizedpages'      => 'Óbólkaðar síður',
'uncategorizedcategories' => 'Óbólkaðir bólkar',
'uncategorizedtemplates'  => 'Óbólkaðar fyrimyndir',
'unusedimages'            => 'Óbrúktar fílur',
'popularpages'            => 'Umtóktar síður',
'wantedcategories'        => 'Ynsktir bólkar',
'wantedpages'             => 'Ynsktar síður',
'wantedtemplates'         => 'Ynsktar fyrimyndir',
'mostcategories'          => 'Greinir við flest bólkum',
'mostrevisions'           => 'Greinir við flestum útgávum',
'shortpages'              => 'Stuttar síður',
'longpages'               => 'Langar síður',
'deadendpages'            => 'Gøtubotnssíður',
'protectedpages'          => 'Friðaðar síður',
'listusers'               => 'Brúkaralisti',
'newpages'                => 'Nýggjar síður',
'newpages-username'       => 'Brúkaranavn:',
'ancientpages'            => 'Elstu síður',
'move'                    => 'Flyt',
'movethispage'            => 'Flyt hesa síðuna',
'unusedimagestext'        => 'Fylgjandi fílur eru til, men eru ikki lagdar inn á nakra síðu.
Vinarliga legg merki til, at vevsíður kunnu slóða til eina fílu við beinleiðis URL, og tí kann hon enn síggjast her, hóast at hon er í regluligari nýtslu.',
'notargettitle'           => 'Onki mál',

# Book sources
'booksources'    => 'Bókakeldur',
'booksources-go' => 'Far',

# Special:Log
'specialloguserlabel'  => 'Brúkari:',
'speciallogtitlelabel' => 'Heitið:',
'log'                  => 'Gerðabøkur',
'all-logs-page'        => 'Allir almennir loggar',
'alllogstext'          => 'Samansett sýning av øllum atkomuligum loggum hjá {{SITENAME}}.
Tú kanst avmarka sýningina við at velja slag av loggi, brúkaranavn (sum er følsamt fyri stórum og lítlum bókstavum) ella ávirkaðu síðuna (sum eisini er følsom fyri stórum og lítlum bókstavum).',

# Special:AllPages
'allpages'       => 'Allar síður',
'alphaindexline' => '$1 til $2',
'nextpage'       => 'Næsta síða ($1)',
'prevpage'       => 'Fyrrverandi síða ($1)',
'allarticles'    => 'Allar greinir',
'allinnamespace' => 'Allar síður ($1 navnarúm)',
'allpagesprev'   => 'Undanfarnu',
'allpagesnext'   => 'Næstu',
'allpagessubmit' => 'Far',

# Special:Categories
'categories'         => 'Bólkar',
'categoriespagetext' => 'Eftirfylgjandi bólkar eru í hesu wiki.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:LinkSearch
'linksearch-ns' => 'Navnarúm:',
'linksearch-ok' => 'Leita',

# Special:ListUsers
'listusersfrom'      => 'Vís brúkarar ið byrja við:',
'listusers-submit'   => 'Sýna',
'listusers-noresult' => 'Ongin brúkari var funnin.',

# Special:Log/newusers
'newuserlogpage' => 'Brúkara logg',

# E-mail user
'mailnologintext'      => 'Tú mást hava [[Special:UserLogin|ritað inn]]
og hava virkandi teldupostadressu í [[Special:Preferences|innstillingum]] tínum
fyri at senda teldupost til aðrar brúkarar.',
'emailuser'            => 'Send t-post til brúkara',
'emailpage'            => 'Send t-post til brúkara',
'defemailsubject'      => '{{SITENAME}} t-postur frá brúkara $1',
'usermaildisabled'     => 'Brúkara t-postur er óvirkin',
'usermaildisabledtext' => 'Tú kanst ikki senda teldupost til aðrir brúkarar á hesi wiki',
'noemailtitle'         => 'Ongin t-post adressa',
'noemailtext'          => 'Hesin brúkarin hevur ikki upplýst eina gylduga t-post-adressu.',
'nowikiemailtitle'     => 'Ongin t-postur er loyvdur',
'emailfrom'            => 'Frá:',
'emailto'              => 'Til:',
'emailsubject'         => 'Evni:',
'emailmessage'         => 'Boð:',
'emailsend'            => 'Send',
'emailccme'            => 'Send mær avrit av mínum boðum.',
'emailccsubject'       => 'Avrit av tínum boðum til $1: $2',
'emailsent'            => 'T-postur sendur',
'emailsenttext'        => 'Títt t-post boð er sent.',

# Watchlist
'watchlist'         => 'Mítt eftirlit',
'mywatchlist'       => 'Mítt eftirlit',
'watchlistfor2'     => 'Fyri $1 $2',
'nowatchlist'       => 'Tú hevur ongar lutir í eftirlitinum.',
'watchnologin'      => 'Tú hevur ikki ritað inn',
'addedwatchtext'    => "Síðan \"<nowiki>\$1</nowiki>\" er løgd undir [[Special:Watchlist|eftirlit]] hjá tær.
Framtíðar broytingar á hesi síðu og tilknýttu kjaksíðuni verða at síggja her.
Tá sæst síðan sum '''feit skrift''' í [[Special:RecentChanges|broytingaryvirlitinum]] fyri at gera hana lættari at síggja.

Vilt tú flyta síðuna undan tínum eftirliti, kanst tú trýsta á \"Strika eftirlit\" á síðuni.",
'removedwatchtext'  => 'Síðan "[[:$1]]" er strikað úr [[Special:Watchlist|tínum eftirliti]].',
'watch'             => 'Eftirlit',
'watchthispage'     => 'Hav eftirlit við hesi síðuni',
'unwatch'           => 'strika eftirlit',
'notanarticle'      => 'Ongin innihaldssíða',
'watchnochange'     => 'Ongin grein í tínum eftirliti er rætta innanfyri hetta tíðarskeiði.',
'watchmethod-list'  => 'kannar síður undir eftirliti fyri feskar broytingar',
'watchlistcontains' => 'Títt eftirlit inniheldur {{PLURAL:$1|eina síðu|$1 síður}}.',
'wlnote'            => "Niðanfyri {{PLURAL:$1|stendur seinastu broytingina|standa seinastu '''$1''' broytingarnar}} {{PLURAL:$2|seinasta tíman|seinastu '''$2''' tímarnar}}.",
'wlshowlast'        => 'Vís seinastu $1 tímar $2 dagar $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Eftirlitir...',
'unwatching' => 'Strikar eftirlit...',

'enotif_newpagetext'           => 'Hetta er ein nýggj síða.',
'enotif_impersonal_salutation' => '{{SITENAME}}brúkari',
'created'                      => 'stovnað',

# Delete
'deletepage'        => 'Strika síðu',
'confirm'           => 'Vátta',
'excontent'         => "innihald var: '$1'",
'excontentauthor'   => "innihaldið var: '$1' (og einasti rithøvundur var '[[Special:Contributions/$2|$2]]')",
'exblank'           => 'síðan var tóm',
'historywarning'    => "'''Ávaring:''' Síðan, ið tú ert í gongd við at strika, hevur eina søgu við umleið $1 {{PLURAL:$1|broyting|broytingum}}:",
'confirmdeletetext' => 'Tú ert í gongd við endaliga at strika ein a síðu
ella mynd saman við allari søgu úr dátugrunninum.
Vinarliga vátta at tú ætlar at gera hetta, at tú skilur
avleiðingarnar og at tú gert tað í tráð við
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete'    => 'Verkið er fullgjørt',
'actionfailed'      => 'Virksemi miseydnaðist',
'deletedtext'       => '"$1" er nú strikað.
Sí $2 fyri fulla skráseting av strikingum.',
'dellogpage'        => 'Striku logg',
'deletionlog'       => 'striku logg',
'deletecomment'     => 'Orsøk:',

# Rollback
'rollback'       => 'Rulla broytingar aftur',
'rollback_short' => 'Rulla aftur',
'rollbacklink'   => 'afturrulling',
'rollbackfailed' => 'Afturrulling miseydnað',

# Protect
'protectlogpage'      => 'Friðingarbók',
'protectedarticle'    => 'friðaði "[[$1]]"',
'unprotectedarticle'  => 'strikaði friðing á "[[$1]]"',
'protect-title'       => 'Friðar "$1"',
'prot_1movedto2'      => '$1 flutt til $2',
'protect-legend'      => 'Vátta friðing',
'protectcomment'      => 'Orsøk:',
'protectexpiry'       => 'Gongur út:',
'protect-default'     => '(fyridømi)',
'protect-level-sysop' => 'Bert umboðsstjórar',
'protect-expiring'    => 'gongur út $1 (UTC)',
'restriction-type'    => 'Verndstøða:',
'pagesize'            => '(být)',

# Undelete
'undelete'               => 'Endurstovna strikaðar síður',
'undeletebtn'            => 'Endurstovna',
'undeletereset'          => 'Endurset',
'undeletedfiles'         => '{{PLURAL:$1|1 fíla endurstovna|$1 fílur endurstovnaðar}}',
'undelete-search-submit' => 'Leita',

# Namespace form on various pages
'namespace'      => 'Navnarúm:',
'invert'         => 'Umvend val',
'blanknamespace' => '(Greinir)',

# Contributions
'contributions' => 'Brúkaraíkast',
'mycontris'     => 'Mítt íkast',
'contribsub2'   => 'Eftir $1 ($2)',
'uctop'         => '(ovast)',
'month'         => 'Frá mánaði (og áðrenn):',
'year'          => 'Frá ár (og áðrenn):',

'sp-contributions-newbies'  => 'Vís bert íkast frá nýggjum kontoum',
'sp-contributions-blocklog' => 'Bannagerðabók',
'sp-contributions-talk'     => 'kjak',
'sp-contributions-search'   => 'Leita eftir íkøstum',
'sp-contributions-username' => 'IP adressa ella brúkaranavn:',
'sp-contributions-submit'   => 'Leita',

# What links here
'whatlinkshere'       => 'Hvat slóðar higar',
'linkshere'           => "Hesar síður slóða til '''[[:$1]]''':",
'nolinkshere'         => "Ongar síður slóða til '''[[:$1]]'''.",
'isredirect'          => 'ávísingarsíða',
'whatlinkshere-prev'  => '{{PLURAL:$1|fyrrverandi|fyrrverandi $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|næst|næstu $1}}',
'whatlinkshere-links' => '← slóðir',

# Block/unblock
'blockip'            => 'Banna brúkara',
'ipadressorusername' => 'IP-adressa ella brúkaranavn:',
'ipbreason'          => 'Orsøk:',
'ipbsubmit'          => 'Banna henda brúkaran',
'badipaddress'       => 'Ógyldug IP-adressa',
'blockipsuccesssub'  => 'Banning framd',
'ipb-unblock-addr'   => 'Óbanna $1',
'ipusubmit'          => 'Strika hesa blokaduna',
'unblocked'          => '[[User:$1|$1]] er ikki blokkaður longur',
'ipblocklist'        => 'Bannaðir brúkarar',
'ipblocklist-submit' => 'Leita',
'expiringblock'      => 'gongur út $1kl. $2',
'anononlyblock'      => 'anon. bara',
'blocklink'          => 'banna',
'unblocklink'        => 'óbanna',
'contribslink'       => 'íkøst',
'blocklogpage'       => 'Bannagerðabók',
'unblocklogentry'    => 'óbannaði $1',
'proxyblocksuccess'  => 'Liðugt.',

# Developer tools
'lockdbtext'        => 'At læsa dátugrunnin steðgar møguleikanum hjá øllum
brúkarum at broyta síður, broyta innstillingar sínar, broyta sínar eftirlitslistar og
onnur ting, ið krevja broytingar í dátugrunninum.
Vinarliga vátta, at hetta er tað, ið tú ætlar at gera, og at tú fert
at læsa dátugrunnin upp aftur tá ið viðgerðin er liðug.',
'locknoconfirm'     => 'Tú krossaði ikki váttanarkassan.',
'lockdbsuccesstext' => 'Dátugrunnurin er læstur.
<br />Minst til at [[Special:UnlockDB|læsa upp]] aftur, tá ið viðgerðin er liðug.',

# Move page
'move-page'                    => 'Flyt $1',
'move-page-legend'             => 'Flyt síðu',
'movepagetext'                 => "Við frymlinum niðanfyri kanst tú umnevna eina síðu og flyta alla hennara søgu við til nýggja navnið.
Gamla navnið verður ein tilvísingarsíða til ta nýggju.
Tú kanst dagføra tilvísingarsíður sum vísa til uppruna tittulin sjálvvirkandi.
Um tú velur ikki at gera tað, ver so vís/ur í at eftirkanna [[Special:DoubleRedirects|dupultar]]  ella [[Special:BrokenRedirects|brotnar tilvísingarsíður]].
Tú hevur ábyrgdina fyri at ansa eftir at slóðir framvegis fara hagar, tær skulu.

Legg merki til at síðan '''ikki''' verður flutt, um ein síða longu er við nýggja navninum, uttan so at hon er tóm, er ein tilvísingarsíða og onga rættingarsøgu hevur.
Hetta merkir at tú kanst umnevna eina síðu aftur hagani hon kom, um tú gjørdi eitt mistak og tú kanst ikki yvirskriva eina verandi síðu.

'''ÁVARING!'''
Hetta kann vera ein ógvuslig og óvæntað broyting av einari vældámdari síðu.
Vinarliga tryggja tær, at tú skilur avleiðingarnar av hesum áðrenn tú heldur áfam.",
'movepagetext-noredirectfixer' => "Við frymlinum niðanfyri kanst tú umnevna eina síðu og flyta alla hennara søgu við til nýggja navnið.
Gamla navnið verður ein tilvísingarsíða til ta nýggju.
Slóðirnar til gomlu síðuna verða ikki broyttar.
Ansa eftir at kanna um [[Special:DoubleRedirects|tvífaldar]] ella [[Special:BrokenRedirects|brotnar]] tilvísingar eru.
Tú hevur ábyrgdina fyri at ansa eftir at slóðir framvegis fara hagar, tær skulu.

Legg merki til at síðan '''ikki''' verður flutt, um ein síða longu er við nýggja navninum, uttan at hon er tóm og onga søgu hevur.
Hetta merkir at tú kanst umnevna eina síðu aftur hagani hon kom, um tú gjørdi eitt mistak. Tú kanst ikki skriva yvir eina verandi síðu.

'''ÁVARING!'''
Hetta kann vera ein ógvuslig og óvæntað flyting av einari vældámdari síðu.
Vinarliga tryggja tær, at tú skilur avleiðingarnar av hesum áðrenn tú heldur áfam.",
'movearticle'                  => 'Flyt síðu:',
'movenologin'                  => 'Hevur ikki ritað inn',
'newtitle'                     => 'Til nýtt heiti:',
'move-watch'                   => 'Hav eftirlit við hesi síðuni',
'movepagebtn'                  => 'Flyt síðu',
'pagemovedsub'                 => 'Flyting væleydnað',
'articleexists'                => 'Ein síða finst longu við hasum navninum,
ella er navnið tú valdi ógyldugt.
Vinarliga vel eitt annað navn.',
'movedto'                      => 'flyt til',
'movetalk'                     => 'Flyt kjaksíðuna eisini, um hon er til.',
'movelogpage'                  => 'Flyt gerðabók',
'movereason'                   => 'Orsøk:',
'delete_and_move'              => 'Strika og flyt',
'delete_and_move_text'         => '==Striking krevst==

Grein við navninum "[[:$1]]" finst longu. Ynskir tú at strika hana til tess at skapa pláss til flytingina?',
'delete_and_move_confirm'      => 'Ja, strika hesa síðuna',
'delete_and_move_reason'       => 'Er strikað fyri at gera pláss til flyting frá "[[$1]]"',

# Export
'export' => 'Útflutningssíður',

# Namespace 8 related
'allmessages'               => 'Øll kervisboð',
'allmessagesname'           => 'Navn',
'allmessagesdefault'        => 'Enskur tekstur',
'allmessagescurrent'        => 'Verandi tekstur',
'allmessagestext'           => 'Hetta er eitt yvirlit av tøkum kervisboðum í MediaWiki-navnarúmi.
Vinarliga vitja [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] og [//translatewiki.net translatewiki.net] um tú ynskir at geva títt íkast til ta generisku MediaWiki lokalisatiónina.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' er ikki stuðlað orsakað av at '''\$wgUseDatabaseMessages''' er sløkt.",
'allmessages-filter-legend' => 'Filtur',
'allmessages-language'      => 'Mál:',

# Thumbnails
'thumbnail-more' => 'Víðka',
'filemissing'    => 'Fíla vantar',

# Special:Import
'import'                  => 'Innflyt síður',
'import-interwiki-submit' => 'Innflyta',
'importfailed'            => 'Innflutningur miseydnaður: $1',
'importsuccess'           => 'Innflutningur er liðugur!',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Tín brúkarasíða',
'tooltip-pt-mytalk'              => 'Tín kjaksíða',
'tooltip-pt-preferences'         => 'Tínir innstillingar',
'tooltip-pt-mycontris'           => 'Yvirlit yvir títt íkast',
'tooltip-pt-login'               => 'Vit mæla til at tú ritar inn, men tað er ikki neyðugt.',
'tooltip-pt-anonlogin'           => 'Vit mæla til at tú ritar inn, tað er tó ikki eitt krav',
'tooltip-pt-logout'              => 'Rita út',
'tooltip-ca-talk'                => 'Umrøða av innihaldssíðuni',
'tooltip-ca-edit'                => 'Tú kanst broyta hesa síðuna. Vinarliga nýt forskoðanarknøttin áðrenn tú goymir.',
'tooltip-ca-addsection'          => 'Byrja eitt nýtt brot',
'tooltip-ca-viewsource'          => 'Henda síðan er friðað. Tú kanst síggja keldukotuna.',
'tooltip-ca-history'             => 'Fyrrverandi útgávur av hesi síðu.',
'tooltip-ca-protect'             => 'Friða hesa síðuna',
'tooltip-ca-delete'              => 'Strika hesa síðuna',
'tooltip-ca-undelete'            => 'Endurnýggja skrivingina á hesi síðu áðrenn hon varð strikað',
'tooltip-ca-move'                => 'Flyt hesa síðuna',
'tooltip-ca-watch'               => 'Legg hesa síðuna undir mítt eftirlit',
'tooltip-ca-unwatch'             => 'Fá hesa síðuna úr mínum eftirliti',
'tooltip-search'                 => 'Leita í {{SITENAME}}',
'tooltip-p-logo'                 => 'Forsíða',
'tooltip-n-mainpage'             => 'Vitja forsíðuna',
'tooltip-n-mainpage-description' => 'Vitja forsíðuna',
'tooltip-n-portal'               => 'Um verkætlanina, hvat tú kanst gera, hvar tú finnur ymiskt',
'tooltip-n-currentevents'        => 'Finn bakgrundsupplýsingar um aktuellar hendingar',
'tooltip-n-recentchanges'        => 'Listi av teimum seinastu broytingunum í wikinum.',
'tooltip-n-randompage'           => 'Far til tilvildarliga síðu',
'tooltip-n-help'                 => 'Staðurin at finna út.',
'tooltip-t-whatlinkshere'        => 'Yvirlit yvir allar wikisíður, ið slóða higar',
'tooltip-t-recentchangeslinked'  => 'Broytingar á síðum, ið slóða higar, í seinastuni',
'tooltip-feed-rss'               => 'RSS-fóðurið til hesa síðuna',
'tooltip-feed-atom'              => 'Atom-fóðurið til hesa síðuna',
'tooltip-t-contributions'        => 'Skoða yvirlit yvir íkast hjá hesum brúkara',
'tooltip-t-emailuser'            => 'Send teldupost til henda brúkaran',
'tooltip-t-upload'               => 'Legg myndir ella miðlafílur upp',
'tooltip-t-specialpages'         => 'Yvirlit yvir serliga síður',
'tooltip-ca-nstab-main'          => 'Skoða innihaldssíðuna',
'tooltip-ca-nstab-user'          => 'Skoða brúkarasíðuna',
'tooltip-ca-nstab-media'         => 'Skoða miðlasíðuna',
'tooltip-ca-nstab-special'       => 'Hetta er ein serlig síða. Tú kanst ikki broyta síðuna sjálv/ur.',
'tooltip-ca-nstab-project'       => 'Skoða verkætlanarsíðuna',
'tooltip-ca-nstab-image'         => 'Skoða myndasíðuna',
'tooltip-ca-nstab-mediawiki'     => 'Skoða kervisamboðini',
'tooltip-ca-nstab-template'      => 'Brúka formin',
'tooltip-ca-nstab-help'          => 'Skoða hjálparsíðuna',
'tooltip-ca-nstab-category'      => 'Skoða bólkasíðuna',
'tooltip-save'                   => 'Goym broytingar mínar',

# Attribution
'anonymous'     => 'Dulnevndir {{PLURAL:$1|brúkari|brúkarar}} í {{SITENAME}}',
'siteuser'      => '{{SITENAME}}brúkari $1',
'anonuser'      => '{{SITENAME}} dulnevndur brúkari $1',
'othercontribs' => 'Grundað á arbeiði eftir $1.',
'others'        => 'onnur',
'siteusers'     => '{{SITENAME}} {{PLURAL:$2|brúkari|brúkarar}} $1',
'anonusers'     => '{{SITENAME}} dulnevndur/ir {{PLURAL:$2|brúkari|brúkarar}} $1',

# Skin names
'skinname-standard'    => 'Standardur',
'skinname-nostalgia'   => 'Nostalgiskur',
'skinname-cologneblue' => 'Cologne-bláur',

# Patrolling
'rcpatroldisabled'     => 'Ansanin eftir nýkomnum broytingum er óvirkin',
'rcpatroldisabledtext' => 'Hentleikin við ansing eftir nýkomnum broytingum er óvirkin í løtuni.',

# Browsing diffs
'previousdiff' => '← Eldri broytingar',
'nextdiff'     => 'Nýggjari broytingar →',

# Media information
'imagemaxsize'   => "Stødd á mynd er avmarkað:<br />''(fyri frágreiðingar síður hjá fílum)''",
'thumbsize'      => 'Smámyndastødd:',
'file-info-size' => '$1 × $2 pixel, stødd fílu: $3, MIME-slag: $4',
'svg-long-desc'  => 'SVG fíle, nominelt $1 × $2 pixel, fíle stødd: $3',

# Special:NewFiles
'newimages' => 'Nýggjar myndir',
'noimages'  => 'Einki at síggja.',
'ilsubmit'  => 'Leita',
'bydate'    => 'eftir dato',

# Metadata
'metadata' => 'Metadáta',

# EXIF tags
'exif-artist'      => 'Rithøvundur',
'exif-copyright'   => 'Upphavsrætt haldari',
'exif-headline'    => 'Yvirskrift',
'exif-iimcategory' => 'Bólkur',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alt',
'namespacesall' => 'alt',
'monthsall'     => 'allir',

# E-mail address confirmation
'confirmemail'          => 'Vátta t-post adressu',
'confirmemail_send'     => 'Send eina váttanarkotu',
'confirmemail_sent'     => 'Játtanar t-postur sendur.',
'confirmemail_oncreate' => 'Ein staðfesingar kota er send til tína T-post adressu.
Tað er ikki neyðugt at hava hesa kodu fyri at rita inn, men tú mást veita hana áðrenn
tú kanst nýta nakran T-post-grundaðan hentleika í hesi wiki.',
'confirmemail_loggedin' => 'Tín t-post adressa er nú váttað.',
'confirmemail_subject'  => '{{SITENAME}} váttan av T-post adressu',
'confirmemail_body'     => 'Onkur, óiva tú frá IP adressu $1, hevur stovnað eina kontu "$2" við hesi t-post adressuni á {{SITENAME}}.

Fyri at vátta at hendan kontu veruliga hoyrur til tín og fyri at aktivera t-post funktiónir á {{SITENAME}}, so skalt
tú trýsta á fylgjandi slóð í tínum kagara:

$3

Um tað *ikki* var tú sum stovnaði kontuna, fylg so hesi slóðini fyri at avlýsa t-post váttanina: 

$5

Hendan váttanarkoda fer úr gildi tann $4.',

# action=purge
'confirm_purge_button' => 'Í lagi',

# Multipage image navigation
'imgmultipageprev' => '← fyrrverandi síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo'       => 'Far!',

# Table pager
'table_pager_next'         => 'Næsta síða',
'table_pager_prev'         => 'Fyrrverandi síða',
'table_pager_first'        => 'Fyrsta síða',
'table_pager_last'         => 'Seinasta síða',
'table_pager_limit'        => 'Vís $1 lutir á hvørjari síðu',
'table_pager_limit_label'  => 'Lutir á hvørjari síðu:',
'table_pager_limit_submit' => 'Far',
'table_pager_empty'        => 'Ongi úrslit',

# Auto-summaries
'autosumm-blank' => 'Slettaði alt innihald á síðuni',
'autosumm-new'   => 'Stovnaði síðu við "$1"',

# Watchlist editor
'watchlistedit-normal-title' => 'Rætta eftirlit',
'watchlistedit-raw-title'    => 'Rætta rátt eftirlit',
'watchlistedit-raw-legend'   => 'Rætta rátt eftirlit',

# Watchlist editing tools
'watchlisttools-view' => 'Vís viðkomandi broytingar',
'watchlisttools-edit' => 'Vís og rætta eftirlit',
'watchlisttools-raw'  => 'Rætta rátt eftirlit',

# Special:Version
'version'                  => 'Útgáva',
'version-hooks'            => 'Krókur',
'version-hook-name'        => 'Krókurnavn',
'version-version'          => '(Útgáva $1)',
'version-software-version' => 'Útgáva',

# Special:FilePath
'filepath-page' => 'Fíla:',

# Special:SpecialPages
'specialpages' => 'Serligar síður',

# Special:ComparePages
'compare-page1' => 'Síða 1',
'compare-page2' => 'Síða 2',

);
