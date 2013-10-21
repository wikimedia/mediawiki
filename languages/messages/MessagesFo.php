<?php
/** Faroese (føroyskt)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Diupwijk
 * @author EileenSanda
 * @author Geitost
 * @author Krun
 * @author Quackor
 * @author S.Örvarr.S
 * @author Spacebirdy
 * @author Urhixidur
 * @author לערי ריינהארט
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

$messages = array(
# User preference toggles
'tog-underline' => 'Undirstrika leinki:',
'tog-justify' => 'Lat tekstin fáa javnan kant til høgru',
'tog-hideminor' => 'Krógva minni broytingar í seinastu broytingum',
'tog-hidepatrolled' => 'Krógva eftirkannaðar rættingar í seinastu broytingum',
'tog-newpageshidepatrolled' => 'Goym eftirkannaðar síður frá listanum yvir nýggjar síður',
'tog-extendwatchlist' => 'Víðka eftirlitslistan fyri at vísa allar broytingar, ikki bara tær seinastu',
'tog-usenewrc' => 'Bólka broytingar eftir síðu í seinastu broytingum og eftirlitslita',
'tog-numberheadings' => 'Sjálvvirkandi talmerking av yvirskriftum',
'tog-showtoolbar' => 'Vís rættingar-tóllinju',
'tog-editondblclick' => 'Rætta síður við at tvíklikkja',
'tog-editsection' => 'Rætta greinpart við hjálp av [rætta] leinkjum',
'tog-editsectiononrightclick' => 'Rætta reglubrot við at høgraklikkja á reglubrotsyvirskrift',
'tog-showtoc' => 'Vís innihaldsyvirlit (fyri síður við meira enn trimun yvirskriftum)',
'tog-rememberpassword' => 'Minst til mítt loyniorð á hesum kaga (í mesta lagi í $1 {{PLURAL:$1|dag|dagar}})',
'tog-watchcreations' => 'Legg síður, sum eg stovni og fílur sum eg leggi út, afturat mínum eftirlitslista',
'tog-watchdefault' => 'Legg síður sum eg rætti afturat mínum eftirlitslista',
'tog-watchmoves' => 'Legg síður og fílur, sum eg flyti, afturat mínum eftirlitslista',
'tog-watchdeletion' => 'Legg síður og fílur, sum eg striki, afturat mínum eftirlitslista',
'tog-minordefault' => 'Merk sum standard allar broytingar sum smærri',
'tog-previewontop' => 'Vís forhondsvísning áðren rættingarkassan',
'tog-previewonfirst' => 'Vís forskoðan við fyrstu rætting',
'tog-nocache' => 'Deaktivera síðu "caching" í brovsaranum',
'tog-enotifwatchlistpages' => 'Send mær teldupost, tá ein síða ella fíla á mínum eftirlitslista er broytt',
'tog-enotifusertalkpages' => 'Send mær teldubræv, tá mín brúkarasíða er broytt',
'tog-enotifminoredits' => 'Send mær eisini ein teldupost viðvíkjandi smærri broytingum á síðum og fílum',
'tog-enotifrevealaddr' => 'Avdúkað mína teldupost adressu í fráboðanar teldupostum',
'tog-shownumberswatching' => 'Vís tal av brúkarum sum fylgja við',
'tog-oldsig' => 'Verandi undirskrift:',
'tog-fancysig' => 'Viðgerð undirskriftina sum wikitekstur (uttan sjálvvirkandi leinkju)',
'tog-uselivepreview' => 'Nýt "live preview" (á royndarstøði)',
'tog-forceeditsummary' => 'Gev mær boð, um eg ikki havi skrivað ein samandrátt um mína rætting',
'tog-watchlisthideown' => 'Fjal mínar rættingar frá eftirliti',
'tog-watchlisthidebots' => 'Vís ikki rættingar frá botti í eftirlitslistanum',
'tog-watchlisthideminor' => 'Fjal minni rættingar frá eftirlitslita',
'tog-watchlisthideliu' => 'Goym broytingar sum eru gjørdar av brúkarum, sum eru loggaðir á, frá hyggjaralistanum',
'tog-watchlisthideanons' => 'Krógva broytingar sum eru gjørdar av dulnevndum brúkarum frá eftirlitslistanum',
'tog-watchlisthidepatrolled' => 'Fjal eftirhugdar broytingar frá eftirlitslistanum',
'tog-ccmeonemails' => 'Send mær avrit av teldubrøvum, sum eg sendi til aðrar brúkarar',
'tog-diffonly' => 'Vís ikki innihaldið av síðuni undir broytingum',
'tog-showhiddencats' => 'Vís goymdir bólkar',
'tog-norollbackdiff' => 'Vís ikki munin eftir eina afturrulling',
'tog-useeditwarning' => 'Ávara meg, tá ið eg fari frá einari rættingarsíðu, sum hevur broytingar ið ikki eru goymdar.',
'tog-prefershttps' => 'Nýt altíð trygt samband, meðan tú ert innritað/ur',

'underline-always' => 'Altíð',
'underline-never' => 'Ongantíð',
'underline-default' => 'Standard fyri útsjónd ella kaga',

# Font style option in Special:Preferences
'editfont-style' => 'Skriftstílur við rætting:',
'editfont-default' => 'Standard kagi',
'editfont-monospace' => 'Føst breidd (monospaced font)',
'editfont-sansserif' => 'Sans-serif skrift',
'editfont-serif' => 'Serif skrift',

# Dates
'sunday' => 'sunnudagur',
'monday' => 'mánadagur',
'tuesday' => 'týsdagur',
'wednesday' => 'mikudagur',
'thursday' => 'hósdagur',
'friday' => 'fríggjadagur',
'saturday' => 'leygardagur',
'sun' => 'sun',
'mon' => 'mán',
'tue' => 'týs',
'wed' => 'mik',
'thu' => 'hós',
'fri' => 'frí',
'sat' => 'ley',
'january' => 'januar',
'february' => 'februar',
'march' => 'mars',
'april' => 'apríl',
'may_long' => 'mai',
'june' => 'juni',
'july' => 'juli',
'august' => 'august',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'desember',
'january-gen' => 'januar',
'february-gen' => 'februar',
'march-gen' => 'mars',
'april-gen' => 'apríl',
'may-gen' => 'mai',
'june-gen' => 'juni',
'july-gen' => 'juli',
'august-gen' => 'august',
'september-gen' => 'september',
'october-gen' => 'oktober',
'november-gen' => 'november',
'december-gen' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'des',
'january-date' => 'Januar $1',
'february-date' => 'Februar $1',
'march-date' => 'Mars $1',
'april-date' => 'Apríl $1',
'may-date' => 'Maj $1',
'june-date' => 'Juni $1',
'july-date' => 'Juli $1',
'august-date' => 'August $1',
'september-date' => 'September $1',
'october-date' => 'Oktober $1',
'november-date' => 'November $1',
'december-date' => 'Desember $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Bólkur|Bólkar}}',
'category_header' => 'Síður í bólki "$1"',
'subcategories' => 'Undirbólkar',
'category-media-header' => 'Miðlar í bólki "$1"',
'category-empty' => "''Hesin bólkur inniheldur ongar greinir ella miðlar í løtuni.''",
'hidden-categories' => '{{PLURAL:$1|Fjaldur bólkur|Fjaldir bólkar}}',
'hidden-category-category' => 'Fjaldir bólkar',
'category-subcat-count' => '{{PLURAL:$2|Hesin bólkur hevur bert henda undirbólk.|Hesin bólkur hevur fylgjandi {{PLURAL:$1|undirbólk|$1 undirbólkar}}, av $2 í alt.}}',
'category-subcat-count-limited' => 'Hesin bólkur hevur fylgjandi {{PLURAL:$1|undirbólk|$1 undirbólkar}}.',
'category-article-count' => '{{PLURAL:$2|Hesin bólkur inniheldur bert komandi síðu.|Komandi {{PLURAL:$1|síða er|$1 síður eru}} í hesum bólkinum, av í alt $2.}}',
'category-article-count-limited' => 'Fylgjandi {{PLURAL:$1|síða er|$1 síður eru}} í verandi bólki.',
'category-file-count' => '{{PLURAL:$2|Hesin bólkur inniheldur bert fylgjandi fílu.|Henda {{PLURAL:$1|fila er|$1 filur eru}} í hesum bólki, út av $2 í alt.}}',
'category-file-count-limited' => 'Fylgjandi {{PLURAL:$1|fila er|$1 filur eru}} í verandi bólki.',
'listingcontinuesabbrev' => 'frh.',
'index-category' => 'Indekseraðar síður',
'noindex-category' => 'Ikki indekseraðar síður',
'broken-file-category' => 'Síður við brotnum fílu slóðum',

'about' => 'Um',
'article' => 'Innihaldssíða',
'newwindow' => '(kemur í nýggjan glugga)',
'cancel' => 'Ógilda',
'moredotdotdot' => 'Meira...',
'morenotlisted' => 'Hesin listin er ikki liðugur.',
'mypage' => 'Síða',
'mytalk' => 'Kjak',
'anontalk' => 'Kjak til hesa IP-adressuna',
'navigation' => 'Navigatión',
'and' => '&#32;og',

# Cologne Blue skin
'qbfind' => 'Finn',
'qbbrowse' => 'Leita',
'qbedit' => 'Rætta',
'qbpageoptions' => 'Henda síðan',
'qbmyoptions' => 'Mínar síður',
'qbspecialpages' => 'Serstakar síður',
'faq' => 'OSS',
'faqpage' => 'Project:OSS',

# Vector skin
'vector-action-addsection' => 'Nýtt evni',
'vector-action-delete' => 'Strika',
'vector-action-move' => 'Flyt',
'vector-action-protect' => 'Friða',
'vector-action-undelete' => 'Endurstovna',
'vector-action-unprotect' => 'Broyt verju',
'vector-simplesearch-preference' => 'Ger lættari leititeig virknan (bert Vector útsjónd)',
'vector-view-create' => 'Stovna',
'vector-view-edit' => 'Rætta',
'vector-view-history' => 'Vís søgu',
'vector-view-view' => 'Les',
'vector-view-viewsource' => 'Vís keldu',
'actions' => 'Gerningar',
'namespaces' => 'Navnarúm',
'variants' => 'Ymisk sløg',

'navigation-heading' => 'Navigatiónsskrá',
'errorpagetitle' => 'Villa',
'returnto' => 'Vend aftur til $1.',
'tagline' => 'Frá {{SITENAME}}',
'help' => 'Hjálp',
'search' => 'Leita',
'searchbutton' => 'Leita',
'go' => 'Far',
'searcharticle' => 'Far',
'history' => 'Søgan hjá síðuni',
'history_short' => 'Søga',
'updatedmarker' => 'dagført síðan mína seinastu vitjan',
'printableversion' => 'Prentvinarlig útgáva',
'permalink' => 'Støðug slóð',
'print' => 'Prenta',
'view' => 'Vís',
'edit' => 'Rætta',
'create' => 'Stovna',
'editthispage' => 'Rætta hesa síðuna',
'create-this-page' => 'Stovna hesa síðuna',
'delete' => 'Strika',
'deletethispage' => 'Strika hesa síðuna',
'undeletethispage' => 'Endurskapað hesa síðuna',
'undelete_short' => 'Ógilda striking av {{PLURAL:$1|einari rætting|$1 broytingum}}',
'viewdeleted_short' => 'Vís {{PLURAL:$1|eina strikaða broyting|$1 strikaðar broytingar}}',
'protect' => 'Friða',
'protect_change' => 'broyt',
'protectthispage' => 'Friða hesa síðuna',
'unprotect' => 'Broyt friðing',
'unprotectthispage' => 'Broyt verju av hesi síðu',
'newpage' => 'Nýggj síða',
'talkpage' => 'Kjakast um hesa síðuna',
'talkpagelinktext' => 'Kjak',
'specialpage' => 'Serstøk síða',
'personaltools' => 'Persónlig amboð',
'postcomment' => 'Nýtt brot',
'articlepage' => 'Vís síðu við innihaldi',
'talk' => 'Kjak',
'views' => 'Skoðanir',
'toolbox' => 'Amboðskassi',
'userpage' => 'Vís brúkarasíðu',
'projectpage' => 'Vís verkætlanarsíðu',
'imagepage' => 'Vís fílusíðuna',
'mediawikipage' => 'Vís síðu við boðum',
'templatepage' => 'Vís fyrimyndasíðu',
'viewhelppage' => 'Vís hjálparsíðu',
'categorypage' => 'Vís bólkasíðu',
'viewtalkpage' => 'Vís kjak',
'otherlanguages' => 'Á øðrum málum',
'redirectedfrom' => '(Ávíst frá $1)',
'redirectpagesub' => 'Ávísingarsíða',
'lastmodifiedat' => 'Hendan síðan var seinast broytt $2, $1.',
'viewcount' => 'Onkur hevur verið á hesi síðu {{PLURAL:$1|eina ferð|$1 ferðir}}.',
'protectedpage' => 'Friðað síða',
'jumpto' => 'Far til:',
'jumptonavigation' => 'navigatión',
'jumptosearch' => 'leita',
'view-pool-error' => 'Haldið okkum til góðar, servarnir hava ov nógv at gera í løtuni.
Ov nógvir brúkarir royna at síggja hesa síðuna.
Vinarliga bíða eina løtu, áðrenn tú roynir enn einaferð at fáa atgongd til hesa síðuna.

$1',
'pool-timeout' => 'Støðgur, bíða verður eftir lásinum',
'pool-queuefull' => 'Køin til "hylin" er full',
'pool-errorunknown' => 'Ókend villa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Um {{SITENAME}}',
'aboutpage' => 'Project:Um',
'copyright' => 'Innihaldið er tøkt undir $1, um ikki annað er viðmerkt.',
'copyrightpage' => '{{ns:project}}:Upphavsrættur',
'currentevents' => 'Aktuellar hendingar',
'currentevents-url' => 'Project:Aktuellar hendingar',
'disclaimers' => 'Fyrivarni',
'disclaimerpage' => 'Project:Generelt fyrivarni',
'edithelp' => 'Rættingarhjálp',
'helppage' => 'Help:Innihald',
'mainpage' => 'Forsíða',
'mainpage-description' => 'Forsíða',
'policy-url' => 'Project:Handfaring av persónligum upplýsingum',
'portal' => 'Brúkaraportalur',
'portal-url' => 'Project:Brúkaraportalur',
'privacy' => 'Handfaring av persónligum upplýsingum',
'privacypage' => 'Project:Handfaring av persónligum upplýsingum',

'badaccess' => 'Loyvisbrek',
'badaccess-group0' => 'Tú hevur ikki loyvi til at útføra hatta sum tú hevur biðið um.',
'badaccess-groups' => 'Tað sum tú hevur biðið um at sleppa at gera er avmarkað til brúrkarar í {{PLURAL:$2|bólkinum|einum av bólkunum}}: $1.',

'versionrequired' => 'Versjón $1 frá MediaWiki er kravd',
'versionrequiredtext' => 'Versjón $1 av MediaWiki er kravd fyri at brúka hesa síðuna.
Sí [[Special:Version|versjón síða]].',

'ok' => 'Í lagi',
'retrievedfrom' => 'Heintað frá "$1"',
'youhavenewmessages' => 'Tú hevur $1 ($2).',
'newmessageslink' => 'nýggj boð',
'newmessagesdifflink' => 'seinasta broyting',
'youhavenewmessagesfromusers' => 'Tú hevur $1 frá {{PLURAL:$3|øðrum brúkara|$3 brúkarum}} ($2).',
'youhavenewmessagesmanyusers' => 'Tú hevur $1 frá fleiri brúkarum ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|eini nýggj boð|nýggj boð}}',
'newmessagesdifflinkplural' => 'seinasta {{PLURAL:$1|broyting|broytingar}}',
'youhavenewmessagesmulti' => 'Tú hevur nýggj boð á $1',
'editsection' => 'rætta',
'editold' => 'rætta',
'viewsourceold' => 'vís keldu',
'editlink' => 'rætta',
'viewsourcelink' => 'vís keldu',
'editsectionhint' => 'Rætta part: $1',
'toc' => 'Innihaldsyvirlit',
'showtoc' => 'skoða',
'hidetoc' => 'fjal',
'collapsible-collapse' => 'Samanbrot',
'collapsible-expand' => 'Víðka',
'thisisdeleted' => 'Sí ella endurstovna $1?',
'viewdeleted' => 'Vís $1?',
'restorelink' => '{{PLURAL:$1|strikaða rætting|$1 strikaðar rættingar}}',
'feedlinks' => 'Føðing:',
'feed-invalid' => 'Ógyldugt slag av haldi.',
'feed-unavailable' => '↓ Syndikatións fóður (feeds) er ikki atkomuligt',
'site-rss-feed' => '$1 RSS Fóðurið',
'site-atom-feed' => '$1 Atom Fóðurið',
'page-rss-feed' => '"$1" RSS Feed',
'page-atom-feed' => '"$1" Atom-feed',
'red-link-title' => '$1 (síðan er ikki til)',
'sort-descending' => 'Bólkað lækkandi',
'sort-ascending' => 'Bólkað hækkandi',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Síða',
'nstab-user' => 'Brúkarasíða',
'nstab-media' => 'Miðil',
'nstab-special' => 'Serstøk síða',
'nstab-project' => 'Verkætlanarsíða',
'nstab-image' => 'Mynd',
'nstab-mediawiki' => 'Grein',
'nstab-template' => 'Fyrimynd',
'nstab-help' => 'Hjálp',
'nstab-category' => 'Bólkur',

# Main script and global functions
'nosuchaction' => 'Ongin slík gerð',
'nosuchactiontext' => "Gerðin, ið tilskilað er í url, virkar ikki.
Møguliga hevur tú stava urlin skeivt, ella fylgt einari skeivari leinkju.
Hetta kann eisini benda á ein feil í software'ini sum {{SITENAME}} brúkar.",
'nosuchspecialpage' => 'Ongin slík serlig síða',
'nospecialpagetext' => '<strong>Tú hevur biðið um eina serliga síðu, sum wiki ikki kennir aftur.</strong>

<!-- A list of valid special pages can be found at [[Special:SpecialPages]]. -->',

# General errors
'error' => 'Villa',
'databaseerror' => 'Villa í dátagrunni',
'databaseerror-error' => 'Feilur: $1',
'laggedslavemode' => "'''Ávaring:''' Síðan inniheldur møguliga ikki nýggjar dagføringar.",
'readonly' => 'Dátubasan er stongd fyri skriving',
'enterlockreason' => 'Skriva eina orsøk fyri at stongja síðuna fyri skriving, saman við einari meting av, nær ið síðan verður lást upp aftur',
'readonlytext' => '↓ Dátugrunnurin er í løtuni stongdur fyri nýggjum rættingum, óiva orsakað av vanligum viðlíkahaldi av dátugrunninum, eftir hetta verður alt vanligt aftur.

Umboðsstjórin (administratorurin) sum stongdi dátugrunnin gav hesa frágreiðingina: $1',
'missing-article' => 'Dátugrunnurin fann ikki tekstin á eini síðu sum hann átti at havt funnið, við heitinum "$1" $2.

Hetta skyldast oftast at ein fylgir einum gomlum "diff" ella søgu slóð til eina síðu sum er blivin strikað.

Um hetta ikki er støðan, so kann tað vera at tú hevur funnið ein feil í ritbúnaðinum (software).
Vinarliga fortel hetta fyri einum [[Special:ListUsers/sysop|administrator]], og ger vart við URL\'in.',
'missingarticle-rev' => '(versjón#: $1)',
'missingarticle-diff' => '(Munur: $1, $2)',
'readonly_lag' => '↓ Dátugrunnurin er blivin stongdur sjálvvirkandi meðan træla dátugrunna servararnir synkronisera við høvuðs dátugrunnin (master)',
'internalerror' => 'Innvortis brek',
'internalerror_info' => 'Innanhýsis villa: $1',
'fileappenderrorread' => 'Tað bar ikki til at lesa "$1" meðan endingin var sett til.',
'fileappenderror' => 'Kundi ikki seta endingina "$1" á "$2".',
'filecopyerror' => 'Kundi ikki avrita fíluna "$1" til "$2".',
'filerenameerror' => 'Kundi ikki umdoypa fílu "$1" til "$2".',
'filedeleteerror' => 'Kundi ikki strika fíluna "$1".',
'directorycreateerror' => 'Kundi ikki upprætta mappuna "$1".',
'filenotfound' => 'Kundi ikki finna fílu "$1".',
'fileexistserror' => 'Kundi ikki upprætta "$1": fílan er longu til',
'unexpected' => 'Óvæntað virði: "$1"="$2".',
'formerror' => 'Villa: Kundi ikki senda skránna.',
'badarticleerror' => 'Hendan gerðin kann ikki fremjast á hesi síðu.',
'cannotdelete' => 'Síðan ella fílan $1 kundi ikki strikast. 
Møguliga hevur onkur annar longu strikað hana.',
'cannotdelete-title' => 'Kann ikki strika síðu "$1"',
'delete-hook-aborted' => 'Ein húkur (hook) forðaði fyri sletting.
Ongin frágreiðing varð givin.',
'no-null-revision' => 'Tað bar ikki til at upprætta nýggja tóma versjón fyri síðuna "$1"',
'badtitle' => 'Ógyldugt heiti',
'badtitletext' => 'Umbidna síðan er ógyldugt, tómt ella skeivt tilslóðað heiti millum mál ella wikur.',
'perfcached' => 'Fylgjandi upplýsingar eru "fangaðir" (cached) og eru møguliga ikki dagførdir. Í mesta lagi {{PLURAL:$1|eitt úrslit er|$1 úrslit eru}} tøk í cache.',
'perfcachedts' => 'Fylgjandi dáta er "fangað" (cached), og var seinast dagført $1. Í mesta lagi {{PLURAL:$4|eitt úrslit er|$4 úrslit eru}} tøk í cache.',
'querypage-no-updates' => 'Tað ber í løtuni ikki til at dagføra hesa síðuna.
Dáta higani verður í løtuni ikki endurnýggjað.',
'wrong_wfQuery_params' => '↓ Skeiv parametir til wfQuery()<br />
Funktión: $1<br />
Fyrispurningur: $2',
'viewsource' => 'Vís keldu',
'viewsource-title' => 'Sí keldu fyri $1',
'actionthrottled' => 'Hendingin kvaldist',
'actionthrottledtext' => '↓ Fyri at mótvirka spam, er tað ikki møguligt at gera hetta alt ov nógvar ferðir uppá stutta tíð, og tú ert farin yvir tað markið.
Vinarliga royn aftur um fáir minuttir.',
'protectedpagetext' => 'Hendan síða er blivin vard fyri at steðga rættingum ella øðrum handlingum.',
'viewsourcetext' => 'Tú kanst síggja og avrita kelduna til hesa grein:',
'viewyourtext' => "Tú kanst síggja og avrita kelduna fyri '''tínar rættingar''' til hesa síðuna:",
'protectedinterface' => "↓ Henda síðan gevur markamóts tekst til ritbúnaðin (software), og er vard fyri at fyribyrgja misnýtslu.
Fyri at gera rættingar ella broyta týðingar á øllum wiki'um, vinarliga nýt [//translatewiki.net/ translatewiki.net], MediaWiki verkætlanina.",
'editinginterface' => "↓ '''Ávaring:''' Tú rættar eina síðu sum verður brúkt til at geva markamóts tekst til ritbúnaðin (software).
Broytingar á hesi síðu fara at ávirka útsjóndina á brúkara markamótinum (interface) fyri aðrar brúkarar á hesi wiki.
Fyri at gera týðingar ella broyta týðingar á øllum wiki, vinarliga nýt [//translatewiki.net/ translatewiki.net],  sum er ein MediaWiki verkætlan.",
'cascadeprotected' => 'Henda síðan er vard fyri rættingum, tí hon er í fylgjandi {{PLURAL:$1|síðu, sum er|síðum, sum eru}}
vardar við "arvaðari síðuverjing"
$2',
'namespaceprotected' => 'Tú hevur ikki loyvi til at rætta síður í $1 navnateiginum.',
'customcssprotected' => 'Tú hevur ikki loyvi til at rætta hesa CSS síðuna, tí hon inniheldur persónligar innstillingar hjá øðrum brúkara.',
'customjsprotected' => 'Tú hevur ikki loyvir til at rætta hesa JavaScript síðuna, tí hon inniheldur persónligar innstillingar hjá øðrum brúkara.',
'mycustomcssprotected' => 'Tú hevur ikki loyvi til at rætta hesa CSS síðuna.',
'mycustomjsprotected' => 'Tú hevur ikki loyvi til at rætta hesa JavaScript síðuna.',
'myprivateinfoprotected' => 'Tú hevur ikki loyvi til at rætta tína privatu kunning.',
'mypreferencesprotected' => 'Tú hevur ikki loyvi til at rætta tínar preferensur.',
'ns-specialprotected' => 'Serstakar síður kunnu ikki rættast.',
'titleprotected' => '[[User:$1|$1]] hevur vart hetta heitið frá skapan.
Givin orsøk er "\'\'$2\'\'".',
'filereadonlyerror' => 'Tað var ikki møguligt at broyta fíluna "$1" tí at fílugoymslan "$2" er í bara-lesa støðu.

Umboðsstjórin sum stongdi hana, gav hesa frágreiðing: "$3".',
'invalidtitle-knownnamespace' => 'Ógyldugt heiti við navnaøki "$2" og teksti "$3"',
'invalidtitle-unknownnamespace' => 'Ógyldigt heiti við ókendum navnaøkis tali $1 og teksti "$2"',
'exception-nologin' => 'Tú ert ikki loggað/ur inn',
'exception-nologin-text' => 'Henda síða ella tað tú ætlar at gera kremvur at tú ert innritað/ur á hesa wiki.',

# Virus scanner
'virus-badscanner' => "Konfiguratións villa: Ókendur virus skannari: ''$1''",
'virus-scanfailed' => '↓  skanning virkaði ikki (kota $1)',
'virus-unknownscanner' => 'ókent antivirus:',

# Login and logout pages
'logouttext' => "'''Tú hevur nú ritað út.'''
 
Legg til merkis, at summar síður framvegis vera vístar, sum um tú enn vart loggað/ur á, til tú hevur reinsað tín brovsara fyri \"cache\".",
'welcomeuser' => 'Vælkomin, $1!',
'welcomecreation-msg' => 'Tín konta er nú stovnað.
Gloym ikki at broyta tínar [[Special:Preferences|{{SITENAME}}-innstillingar]].',
'yourname' => 'Títt brúkaranavn:',
'userlogin-yourname' => 'Brúkaranavn',
'userlogin-yourname-ph' => 'Skriva títt brúkaranavn',
'createacct-another-username-ph' => 'Skriva brúkaranavnið',
'yourpassword' => 'Títt loyniorð:',
'userlogin-yourpassword' => 'Loyniorð',
'userlogin-yourpassword-ph' => 'Skriva títt loyniorð',
'createacct-yourpassword-ph' => 'Skrivað eitt loyniorð',
'yourpasswordagain' => 'Skriva loyniorð umaftur:',
'createacct-yourpasswordagain' => 'Váttað loyniorðið',
'createacct-yourpasswordagain-ph' => 'Skrivað loyniorðið enn einaferð',
'remembermypassword' => 'Minst til logg inn hjá mær á hesum kaganum (í mesta lagi í $1 {{PLURAL:$1|dag|dagar}})',
'userlogin-remembermypassword' => 'Lat meg vera innritaðan',
'userlogin-signwithsecure' => 'Nýt trygt samband',
'yourdomainname' => 'Títt domene:',
'password-change-forbidden' => 'Tú kanst ikki broyta loyniorð á hesi wiki.',
'externaldberror' => 'Antin var talan um ein atgongd dátubasu feil, ella hevur tú ikki loyvi til at dagføra tína eksternu kontu.',
'login' => 'Rita inn',
'nav-login-createaccount' => 'Stovna kontu ella rita inn',
'loginprompt' => 'Cookies má verða sett til fyri at innrita á {{SITENAME}}.',
'userlogin' => 'Stovna kontu ella rita inn',
'userloginnocreate' => 'Rita inn',
'logout' => 'Útrita',
'userlogout' => 'Rita út',
'notloggedin' => 'Ikki ritað inn',
'userlogin-noaccount' => 'Hevur tú ikki nakra kontu?',
'userlogin-joinproject' => 'Meldað teg til {{SITENAME}}',
'nologin' => 'Hevur tú ikki eina kontu? $1.',
'nologinlink' => 'Stovna eina kontu',
'createaccount' => 'Stovna nýggja kontu',
'gotaccount' => "Hevur tú longu eina kontu? '''$1'''.",
'gotaccountlink' => 'Rita inn',
'userlogin-resetlink' => 'Hevur tú gloymt tínar logg inn upplýsingar',
'userlogin-resetpassword-link' => 'Nullstilla títt loyniorð',
'helplogin-url' => 'Help:Innritan',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Hjálp til innritan]]',
'createacct-join' => 'Skrivað tínar upplýsingar niðanfyri.',
'createacct-another-join' => 'Skriva upplýsingarnar fyri tað nýggju kontuna niðanfyri.',
'createacct-emailrequired' => 'Teldupost adressa',
'createacct-emailoptional' => 'Teldupost adressa (valfrítt)',
'createacct-email-ph' => 'Skrivað tína email adressu',
'createacct-another-email-ph' => 'Skriva tína t-post adressu',
'createaccountmail' => 'Nýt eitt fyribils tilvildarligt loyniorð og send tað til nevndu t-post adressuna',
'createacct-realname' => 'Veruligt navn (valfrítt)',
'createaccountreason' => 'Orsøk:',
'createacct-reason' => 'Orsøk',
'createacct-reason-ph' => 'Hví upprættar tú eina nýggja kontu',
'createacct-captcha' => 'Trygdarkekk',
'createacct-imgcaptcha-ph' => 'Skriva tekstin ið tú sært omanfyri',
'createacct-submit' => 'Upprætta tína kontu',
'createacct-another-submit' => 'Upprætta eina aðra kontu',
'createacct-benefit-heading' => '{{SITENAME}} er gjørd av fólki sum tær.',
'createacct-benefit-body1' => '{{PLURAL:$1|rætting|rættingar}}',
'createacct-benefit-body2' => '{{PLURAL:$1|síða|síður}}',
'createacct-benefit-body3' => 'seinasti/u {{PLURAL:$1|høvundur|høvundar}}',
'badretype' => 'Loyniorðið tú hevur skriva er ikki rætt.',
'userexists' => 'Brúkaranavnið sum tú valdi er longu í nýtslu.
Vinarliga vel eitt annað navn.',
'loginerror' => 'Innritanarbrek',
'createacct-error' => 'Feilur við skapan av konto',
'createaccounterror' => 'Kundi ikki skapa kontu: $1',
'nocookiesnew' => 'Brúkarakontan er nú gjørd, men tú ert ikki loggaður inn. 
{{SITENAME}} brúkar "cookies" fyri at innrita brúkarar.
You hevur gjørt "cookies" óvirkið.
Vinarliga ger "cookies" virkið á tínari teldu, rita síðan inn við tínum nýggja brúkaranavni og loyniorði.',
'nocookieslogin' => '{{SITENAME}} brúkar cookies fyri at innrita brúkarar. 
Tú hevur gjørt cookies óvirkið.
Vinarliga ger tað virkið og royn aftur.',
'nocookiesfornew' => 'Brúkarakontan var ikki upprættað, tí vit kundu ikki staðfesta kelduna.
Tryggja tær, at cookies eru virknar á tínari teldu, dagfør (reload) hesa síðuna og royn aftur.',
'noname' => 'Tú hevur ikki skrivað eitt gyldugt brúkaranavn.',
'loginsuccesstitle' => 'Innritan væleydnað',
'loginsuccess' => "'''Tú hevur nú ritað inn í {{SITENAME}} sum \"\$1\".'''",
'nosuchuser' => 'Eingin brúkari er við navninum "$1". 
Brúkaranøvn eru følsom fyri stórum og lítlum bókstavum.
Eftirkanna um tú hevur stavað rætt, ella [[Special:UserLogin/signup|stovna eina nýggja konto]].',
'nosuchusershort' => 'Eingin brúkari er við navninum "$1". Kanna stavseting.',
'nouserspecified' => 'Tú mást skriva eitt brúkaranavn.',
'login-userblocked' => 'Hesin brúkarin er blokkaður. Tað er ikki loyvt at logga á.',
'wrongpassword' => 'Loyniorðið, sum tú skrivaði, er skeivt. Vinaliga royn aftur.',
'wrongpasswordempty' => 'Loyniorð manglar. Vinarliga royn aftur.',
'passwordtooshort' => 'Loyniorð mugu vera í minsta lagi {{PLURAL:$1|1 bókstav, tal, tekn|$1 bókstavir, tøl og tekn}}.',
'password-name-match' => 'Loyniorðið hjá tær má vera annarleiðis enn títt brúkaranavn.',
'password-login-forbidden' => 'Tað er ikki loyvt at brúka hetta brúkaranavnið og loyniorðið.',
'mailmypassword' => 'Send mær eitt nýtt loyniorð við t-posti',
'passwordremindertitle' => 'Nýtt fyribils loyniorð fyri {{SITENAME}}',
'passwordremindertext' => 'Onkur (óivað tú, frá IP adressu $1) hevur umbiðið eitt nýtt loyniorð fyri {{SITENAME}}  $4. Eitt fyribils loyniorð fyri brúkara "$2" er nú gjørt og er sent til "$3". Um hetta var tað tú vildi, so mást tú rita inn og velja eitt nýtt loyniorð nú. 
Títt fyribils loyniorð gongur út um {{PLURAL:$5|ein dag|$5 dagar}}.


Um onkur annar hevur sent hesa umbønina, ella um tú nú minnist títt loyniorð, 
og tú ikki longur ynskir at broyta tað, so skal tú síggja burtur frá hesum boðunum og halda fram við at brúka títt gamla loyniorð.',
'noemail' => 'Tað er ongin t-post adressa skrásett fyri brúkara "$1".',
'noemailcreate' => 'Tú mást geva eina galdandi t-post adressu',
'passwordsent' => 'Eitt nýtt loyniorð er sent til t-postadressuna,
sum er skrásett fyri "$1".
Vinarliga rita inn eftir at tú hevur fingið hana.',
'blocked-mailpassword' => 'Tín IP adressa er stongd fyri at gera rættingar á síðum, og tí er tað ikki loyvt at brúka funkuna fyri endurskapan av loyniorði, hetta fyri at forða fyri misnýtslu.',
'eauthentsent' => '↓ Ein váttanar t-postur er sendur til givna t-post bústaðin.
Áðrenn aðrir teldupostar verða sendir til kontuna, mást tú fylgja leiðbeiningunum í t-postinum, fyri at vátta at kontoin veruliga er tín.',
'throttled-mailpassword' => 'Ein teldupostur har loyniorðið verður nullstillað er longu sendur fyri bert {{PLURAL:$1|tíma|$1 tímum}} síðan.
Fyri at fyribyrja misnýtslu, verður bert ein teldupostur við nullstillaðum loyniorði sendur fyri pr. {{PLURAL:$1|tíma|$1 tímar}}.',
'mailerror' => 'Villa tá t-postur var sendur: $1',
'acct_creation_throttle_hit' => 'Vitjandi á hesi wiki, sum nýta tína IP addressu, hava stovnað {{PLURAL:$1|1 kontu|$1 kontur}} seinastu dagarnar, sum er mest loyvda hetta tíðarskeið.
Sum eitt úrslit av hesum, kunnu vitjandi sum brúka hesa IP adressuna ikki stovna fleiri kontur í løtuni.',
'emailauthenticated' => 'Tín t-post adressa varð váttað hin $2 kl. $3.',
'emailnotauthenticated' => 'Tín t-post adressa er enn ikki komin í gildi. Ongin t-postur
verður sendur fyri nakað av fylgjandi hentleikum.',
'noemailprefs' => 'Skriva eina t-post adressu, so hesar funktiónir fara at virka.',
'emailconfirmlink' => 'Vátta tína t-post adressu',
'invalidemailaddress' => 'T-post bústaðurin kann ikki verða góðtikin, tí hann sær út til at hava ógyldugt format.
Vinarliga skriva t-post bústað í røttum formati ella lat handa teigin vera tóman.',
'cannotchangeemail' => 'T-post adressur, sum eru knýttar at brúkarakontum, kunnu ikki broytast á hesi wiki.',
'emaildisabled' => 'Henda heimasíðan kann ikki senda teldupostar.',
'accountcreated' => 'Konto upprættað',
'accountcreatedtext' => 'Henda brúkarakontan fyri [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|kjak]]) er nú upprættað.',
'createaccount-title' => 'Upprætta brúkarakonto á {{SITENAME}}',
'createaccount-text' => 'Onkur hevur stovnað eina konto fyri tína teldupost adressu á {{SITENAME}} ($4) nevnd "$2", við loyniorðinum "$3".
Tú eigur at innrita og broyta loyniorðið nú.

Tú kanst síggja burtur frá hesum boðum, um henda kontan varð upprættað av misgáum.',
'usernamehasherror' => 'Brúkaranavn kann ikki innihalda teknið #',
'login-throttled' => 'Tú hevur roynt at rita inn ov nógvar ferðir nýliga.
Vinarliga bíða $1 áðrenn tú roynir aftur.',
'login-abort-generic' => 'Tað miseydnaðist tær at rita inn - avbrotið',
'loginlanguagelabel' => 'Mál: $1',
'suspicious-userlogout' => 'Tín fyrispurningur um at útrita var noktaður, tí tað sær út til at hann varð sendur frá einum oyðiløgdum kaga ella caching proxy.',
'createacct-another-realname-tip' => 'Veruligt navn er valfrítt.
Um tú velur at skriva tað, so verður tað nýtt til at geva brúkaranum æruna fyri hennara/hansara  arbeiði.',

# Email sending
'php-mail-error-unknown' => "Ókend villa í PHP'sa teldupost () funktión.",
'user-mail-no-addy' => 'Royndi at senda t-post uttan eina t-post adressu.',
'user-mail-no-body' => 'Tú royndi at senda ein teldupost við ongum ella órímiliga stuttum innihaldi.',

# Change password dialog
'resetpass' => 'Broyt loyniorð',
'resetpass_announce' => 'Tú ritaði inn við einum fyribils loyniorði, sum tú hevur fingið við telduposti.
Fyri at gera innritanina lidna, mást tú velja tær eitt nýtt loyniorð her:',
'resetpass_header' => 'Broyt loyniorði á kontuni',
'oldpassword' => 'Gamalt loyniorð:',
'newpassword' => 'Nýtt loyniorð:',
'retypenew' => 'Skriva nýtt loyniorð umaftur:',
'resetpass_submit' => 'Vel loyniorð og rita inn',
'changepassword-success' => 'Títt loyniorð er nú broytt!',
'resetpass_forbidden' => 'Loyniorð kunnu ikki broytast',
'resetpass-no-info' => 'Tú mást vera loggaður á fyri at fáa beinleiðis atgongd til hesa síðu.',
'resetpass-submit-loggedin' => 'Broyt loyniorð',
'resetpass-submit-cancel' => 'Ógildað',
'resetpass-wrong-oldpass' => 'Ógyldug fyribils ella verandi loyniorð.
Møguliga hevur tú longu broytt títt loyniorð ella biðið um eitt nýtt fyribils loyniorð.',
'resetpass-temp-password' => 'Fyribils loyniorð',
'resetpass-abort-generic' => 'Broyting av loyniorði bleiv avbrotin av einari víðkan.',

# Special:PasswordReset
'passwordreset' => 'Nullstilla loyniorðið',
'passwordreset-text-one' => 'Útfyll henda teigin fyri at nullstilla títt loyniorð.',
'passwordreset-text-many' => '{{PLURAL:$1|Útfyll ein av teigunum fyri at nullstilla títt loyniorð.}}',
'passwordreset-legend' => 'Nulstilla loyniorðið',
'passwordreset-disabled' => 'Tað ber ikki til at nullstilla loyniorðið á hesi wiki.',
'passwordreset-emaildisabled' => 'Teldupost funksjónir eru óvirknar á hesi wiki.',
'passwordreset-username' => 'Brúkaranavn:',
'passwordreset-domain' => 'Umdømi (domain):',
'passwordreset-capture' => 'Sí tann endaliga t-postin?',
'passwordreset-capture-help' => 'Um tú setir kross við henda teigin, so verður t-posturin (við fyribils loyniorðinum) vístur fyri tær og verður harumframt sendur til brúkaran.',
'passwordreset-email' => 'T-post adressur:',
'passwordreset-emailtitle' => 'konto upplýsingar á {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Onkur (óiva tú, frá IP adressu $1) hevur biðið um nullstillan av tínum loyniorði til {{SITENAME}} ($4). Fylgjandi brúkara {{PLURAL:$3|konta er|kontur eru}}
settar í samband við hesa t-post adressu:

$2

{{PLURAL:$3|Hetta fyribils loyniorðið|Hesi fyribils loyniorðini}} ganga út um {{PLURAL:$5|ein dag|$5 dagar}}.
Tú eigur at rita inn og velja eitt nýtt loyniorð nú. Um onkur annar hevur gjørt hesa umbønina, ella um tú ert komin í tankar um títt uppruna loyniorð, og tú ikki longur ynskir at broyta tað, so kanst tú síggja burtur frá hesum boðum og halda fram at brúka títt gamla loyniorð.',
'passwordreset-emailtext-user' => 'Brúkari $1 á {{SITENAME}} hevur biðið um eina nullstillan av tínum loyniorði til {{SITENAME}} 
($4). Fylgjandi brúkara {{PLURAL:$3|konta er|kontur eru}} settar í samband við hesa t-post adressuna:

$2

{{PLURAL:$3|Hetta fyribils loyniorðið|Hesi fyribils loyniorðini}} ganga út um {{PLURAL:$5|ein dag|$5 dagar}}.
Tú eigur at rita inn og velja eitt nýtt loyniorð nú. Um onkur annar hevur gjørt hesa umbøn, ella um tú ert komin í tankar um títt uppruna loyniorð, og tú ikki longur ynskir at broyta tað, so kanst tú síggja burtur frá hesum boðum og halda fram at brúka títt gamla loyniorð.',
'passwordreset-emailelement' => 'Brúkaranavn: $1
Fyribils loyniorð: $2',
'passwordreset-emailsent' => 'Ein teldupostur har tú kanst nullstillað loyniorðið er blivin sendur.',
'passwordreset-emailsent-capture' => 'Ein teldupostur, har ið tú kanst nullstilla loyniorðið, er blivin sendur, sum víst niðanfyri.',
'passwordreset-emailerror-capture' => 'Ein teldupostur við nullstillaðum loyniorði var gjørdur, sum víst niðanfyri, men tað miseydnaðist at senda til {{GENDER:$2|brúkaran}}: $1',

# Special:ChangeEmail
'changeemail' => 'Broyt teldupost adressu',
'changeemail-header' => 'Broyt t-post adressuna hjá kontuni',
'changeemail-text' => 'Útfyll henda formularin fyri at broyta tína t-post adressu. Tú mást skriva títt loyniorð fyri at vátta hesa broyting.',
'changeemail-no-info' => 'Tú mást vera innritað/ur fyri at fáa beinleiðis atgongd til hesa síðu.',
'changeemail-oldemail' => 'Verandi t-post adressa:',
'changeemail-newemail' => 'Nýggj t-post adressa:',
'changeemail-none' => '(ongin)',
'changeemail-password' => 'Títt {{SITENAME}} loyniorð:',
'changeemail-submit' => 'Broyt t-post',
'changeemail-cancel' => 'Ógilda',

# Special:ResetTokens
'resettokens' => 'Nullstilla lyklar',
'resettokens-text' => 'Tú kanst nullstilla lyklar sum geva atgongd til ávís privat dáta sum eru knýtt at tínari konto her.

Tú eigur at gera tað um tú av óvart hevur deilt lyklarnar við onkran, ella um tín konta hevur verið útsett fyri vandastøðu.',
'resettokens-no-tokens' => 'Tað eru ongir lyklar at nullstilla.',
'resettokens-legend' => 'Nullstilla lyklar',
'resettokens-tokens' => 'Lyklar:',
'resettokens-token-label' => '$1 (dagsins virði: $2)',
'resettokens-done' => 'Nullstilla lyklar.',
'resettokens-resetbutton' => 'Nullstilla útvaldu lyklar (tokens)',

# Edit page toolbar
'bold_sample' => 'Feitir stavir',
'bold_tip' => 'Feitir stavir',
'italic_sample' => 'Skákstavir',
'italic_tip' => 'Skákstavir',
'link_sample' => 'Slóðarheiti',
'link_tip' => 'Innanhýsis slóð',
'extlink_sample' => 'http://www.example.com slóðarheiti',
'extlink_tip' => 'Útvortis slóð (minst til http:// forskoytið)',
'headline_sample' => 'Yvirskriftartekstur',
'headline_tip' => 'Annars stigs yvirskrift',
'nowiki_sample' => 'Skriva ikki-formateraðan tekst her',
'nowiki_tip' => 'Ignorera wiki-forsniðan',
'image_sample' => 'Dømi.jpg',
'image_tip' => 'Innset mynd',
'media_sample' => 'Dømi.ogg',
'media_tip' => 'Fílu slóð',
'sig_tip' => 'Tín undirskrift við tíðarstempli',
'hr_tip' => 'Vatnrøtt linja (vera sparin við)',

# Edit pages
'summary' => 'Samandráttur:',
'subject' => 'Evni/heiti:',
'minoredit' => 'Hetta er smábroyting',
'watchthis' => 'Hav eftirlit við hesi síðuni',
'savearticle' => 'Goym síðu',
'preview' => 'Forskoðan',
'showpreview' => 'Forskoðan',
'showlivepreview' => 'Beinleiðis forskoðan',
'showdiff' => 'Sýn broytingar',
'anoneditwarning' => "'''Ávaring:''' Tú hevur ikki ritað inn.
Tín IP-adressa verður goymd í rættisøguni fyri hesa síðuna.",
'anonpreviewwarning' => "''Tú ert ikki innritað/ur. Um tú goymir nú, so verður tín IP adressa goymd í rættingar søguni hjá hesi síðu. ''",
'missingsummary' => "'''Áminning:''' Tú hevur ikki givið nakran rættingar samandrátt.
Um tú trýstir á \"{{int:savearticle}}\" enn einaferð, so verða tínar rættingar goymdar uttan samandrátt.",
'missingcommenttext' => 'Vinarliga skriva eina viðmerking niðanfyri.',
'missingcommentheader' => "'''Reminder:''' Tú hevur ikki skrivað nakað evni/yvirskrift fyri hesa viðmerking.
Um tú trýstir á \"{{int:savearticle}}\" aftur, so verður tín rætting goymd uttan evni/yvirskrift.",
'summary-preview' => 'Samandráttaforskoðan:',
'subject-preview' => 'Forskoðan av evni/yvirskrift:',
'blockedtitle' => 'Brúkarin er bannaður',
'blockedtext' => "'''Títt brúkaranavn ella IP adressa er sperrað.'''

Sperringin varð gjørd av $1.
Orsøkin segðist vera ''$2''.

* Sperringin byrjaði: $8
* Sperringin endar: $6
* Sperringin er rættað móti: $7

Tú kanst seta teg í samband við $1 ella ein annan [[{{MediaWiki:Grouppage-sysop}}|administrator]] fyri at kjakast um sperringina.
Tú kanst ikki brúka 'send t-post til henda brúkara' funktiónina, uttan so at ein galdandi t-post adressa er givin í tínum [[Special:Preferences|konto innstillingum]] og um tú ikki ert blivin sperraður frá at brúka hana.
Tín verandi IP adressa er $3, og sperrings ID er #$5.
Vinarliga tak allir hesir upplýsingar við í einum hvørjum fyrispurningi ið tí hevur.",
'autoblockedtext' => 'Tín IP bústaður er blivin sjálvvirkandi sperraður, tí hann varð brúktur av einum øðrum brúkara, sum er blivin sperraður av $1.
Viðkomandi gav hesa orsøk:
:\'\'$2\'\'

* Sperring byrjað: $8
* Sperringin útgongur: $6
* Intended blockee: $7

Tú kanst seta teg í samband við $1 ella ein av hinum [[{{MediaWiki:Grouppage-sysop}}|administratorunum]] fyri at kjakast um sperringina.

Legg til merkis, at tú kanst ikki brúka "send ein t-post til henda brúkara" funktiónina, uttan so at tú hevur ein gyldugan t-post  bústað skrásettan í tínum 
[[Special:Preferences|brúkara ynskjum]] og at tú ikki ert blivin sperrað/ur frá at brúka hesa.

IP adressan sum tú brúkar í løtuni er $3, og brúkara ID er #$5.
Vinarliga tak allar hesar upplýsigarnar við í einum og hvørjum fyrispurningi, ið tú kanst hava.',
'blockednoreason' => 'Ongin orsøk er givin',
'whitelistedittext' => 'Tú mást $1 fyri at rætta hesa síðu.',
'confirmedittext' => 'Tú mást vátta tína teldupost adressu áðrenn tú rættar síður.
Vinarliga skriva og vátta tína t-post adressu í tínum [[Special:Preferences|brúkara innstillingum]].',
'nosuchsectiontitle' => 'Kann ikki finna brotið',
'nosuchsectiontext' => 'Tú royndi at rætta eitt brot sum ikki er til. 
Tað kann vera flutt ella blivið strikað meðan tú hevur hugt at síðuni.',
'loginreqtitle' => 'Innritan kravd',
'loginreqlink' => 'rita inn',
'loginreqpagetext' => 'Tú mást $1 fyri at síggja aðrar síður.',
'accmailtitle' => 'Loyniorð sent.',
'accmailtext' => "Eitt tilvildarliga valt loyniorð fyri brúkaran [[User talk:$1|$1]] er blivið  sent til $2.
Tað kann broytast á ''[[Special:ChangePassword|broyt loyniorð]]'' síðuni tá tú ritar inn.",
'newarticle' => '(Nýggj)',
'newarticletext' => "Tú ert komin eftir eini slóð til eina síðu, ið ikki er til enn. Skriva í kassan niðanfyri, um tú vilt byrja uppá hesa síðuna.
(Sí [[{{MediaWiki:Helppage}}|hjálparsíðuna]] um tú ynskir fleiri upplýsingar).
Ert tú komin higar av einum mistaki, kanst tú trýsta á '''aftur'''-knøttin á kagaranum.",
'anontalkpagetext' => "----''Hetta er ein kjaksíða hjá einum dulnevndum brúkara, sum ikki hevur stovnað eina kontu enn, ella ikki brúkar hana. 
Tí noyðast vit at brúka nummerisku IP-adressuna hjá honum ella henni.
Ein slík IP-adressa kann verða brúkt av fleiri brúkarum.
Ert tú ein dulnevndur brúkari, og meinar, at óviðkomandi viðmerkingar eru vendar til tín, so er best fyri teg at [[Special:UserLogin/signup|stovna eina kontu]] ella [[Special:UserLogin|rita inn]] fyri at sleppa undan samanblanding við aðrar dulnevndar brúkarar í framtíðini.''",
'noarticletext' => 'Tað er í løtuni ongin tekstur á hesi síðu.
Tú kanst [[Special:Search/{{PAGENAME}}|leita eftir hesum síðu heitinum]] á øðrum síðum,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} leita í líknandi loggum],
ella [{{fullurl:{{FULLPAGENAME}}|action=edit}} rætta hesa síðu]</span>.',
'noarticletext-nopermission' => 'Tað er í løtuni ongin tekstur á hesi síðu.
Tú kanst [[Special:Search/{{PAGENAME}}|leita eftir hesum síðuheiti]] á øðrum síðum, 
ella <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} leita eftir viðkomandi loggum]</span>, men tú hevur ikki loyvi til at stovna hesa síðu.',
'missing-revision' => 'Endurskoðan #$1 av síðuni við heitinum "{{PAGENAME}}" er ikki til.

Hetta skyldast vanliga tað, at tú fylgir einari gamlari søguslóð til eina síðu, sum er blivin slettað. 
Nærri frágreiðing kanst tú finna í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strikingar logginum].',
'userpage-userdoesnotexist' => 'Brúkarakontan "$1" er ikki skrásett.
Vinarliga umhugsa um tú ynskir at upprætta/rætta hesa síðu.',
'userpage-userdoesnotexist-view' => 'Brúkarakonto "$1" er ikki skrásett.',
'blocked-notice-logextract' => 'Hesin brúkarin er í løtuni sperraður.
Tann seinasti sperringar gerðalistin er her niðanfyri fyri ávísing:',
'clearyourcache' => "'''Legg til merkis:''' Eftir at hava goymt, mást tú fara uttanum minnið (cache) á sneytara (brovsara) tínum fyri at síggja broytingarnar.
* '''Firefox / Safari:''' Halt ''Shift'' meðan tú klikkir á ''Reload'', ella trýst antin ''Ctrl-F5'' ella ''Ctrl-R'' (''Command-R'' á einari Mac)
* '''Google Chrome:''' Trýst ''Ctrl-Shift-R'' (''⌘-Shift-R'' á einari Mac)
* '''Internet Explorer:''' Halt ''Ctrl'' meðan tú trýstir á ''Refresh'', ella trýst á ''Ctrl-F5''
* '''Opera:''' Reinsa cache í ''Tools → Preferences''",
'usercssyoucanpreview' => "'''Gott ráð:''' Brúka \"{{int:showpreview}}\" knappin fyri at royna tína nýggju CSS áðrenn tú goymir.",
'userjsyoucanpreview' => "'''Gott ráð:''' Brúka \"{{int:showpreview}}\" knappin fyri at royna títt nýggja JavaScript áðrenn tú goymir.",
'usercsspreview' => "'''Minst til at hetta bert er ein áðrenn vísing av tínum brúkara CSS.'''
'''Tú hevur ikki goymt tað enn!'''",
'userjspreview' => "'''Minst til at hetta bert er ein royndarvísing av tínum brúkara JavaScript.'''
'''Tú hevur ikki goymt tað enn!'''",
'sitecsspreview' => "'''Minst til at hetta bert er ein royndar vísing av hesum CSS.'''
'''Tú hevur ikki goymt tað enn!'''",
'sitejspreview' => "'''Minst til at hetta bert er ein royndar vísing av hesi JavaScript kotuni.'''
'''Tað er ikki goymt enn!'''",
'userinvalidcssjstitle' => "'''Ávaring:''' Tað er onki skinn \"\$1\".
Tilevnaðar .css og .js síður brúka heiti sum byrja við lítlum bókstavi, t.d.  {{ns:user}}:Foo/vector.css í mun til {{ns:user}}:Foo/Vector.css.",
'updated' => '(Dagført)',
'note' => "'''Viðmerking:'''",
'previewnote' => "'''Minst til at hetta bara er ein forskoðan.'''
Tínar broytingar eru ikki goymdar enn!",
'continue-editing' => 'Far til økið har ið tú kanst gera rættingar',
'previewconflict' => 'Henda forskoðanin vísir tekstin í erva soleiðis sum hann sær út, um tú velur at goyma.',
'session_fail_preview' => "'''Orsakað! Vit kundu ikki fullføra tínar broytingar, tí tínar sessións dáta eru horvin.'''
Vinarliga royn aftur.
Um tað enn ikki virkar, royn so [[Special:UserLogout|rita út]] og rita so inn aftur.",
'session_fail_preview_html' => "'''Orsakað! Vit kundu ikki gjøgnumføra tínar rættingar orsakað av einum missi av sessiónsdáta.'''

''Orsakað av at {{SITENAME}} hevur gjørt rátt HTML virkið, so verður forskoðanin fjald av varsemi móti JavaScript álopum.''

'''Um hetta er ein lóglig roynd at gera rættingar, vinarliga royn so aftur.'''
Um tað enn ikki virkar, royn so at [[Special:UserLogout|rita út]] og rita so inn aftur.",
'edit_form_incomplete' => "'''Nakrir partar av rættingarskjalinum náddu ikki til servaran; eftirkanna tvær ferðir at tínar rættingar eru til staðar og royn so aftur.'''",
'editing' => 'Tú rættar $1',
'creating' => 'Upprætta $1',
'editingsection' => 'Tú rættar $1 (partur)',
'editingcomment' => 'Tú rættar $1 (nýtt brot)',
'editconflict' => 'Rættingar konflikt: $1',
'explainconflict' => "Onkur annar hevur broytt hesa síðuna síðan tú fór í gongd við at rætta hana.
Tað ovara tekst økið inniheldur síðu tekstin sum hann er í løtuni.
Tínar broytingar eru vístar í tí niðara tekst økinum.
Tú mást flætta tínar rættingar inn í verandi tekstin.
'''Bert''' teksturin í ovara økinum verður goymdur, tá tú trýstir á \"{{int:savearticle}}\".",
'yourtext' => 'Tín tekstur',
'storedversion' => 'Goymd útgáva',
'nonunicodebrowser' => "'''Ávaring: Tín internetkagi er ikki í samsvar við Unicode.'''
Ein dagføring er neyðug fyri at tú á tryggan hátt kanst rætta síður: Ikki-ASCII bókstavar fara at koma fram í rættingarteiginum sum hexadecimal kotur.",
'editingold' => "'''Ávaring: Tú rættar ein gamla versjón av hesi síðu.'''
Um tú goymir hana, so fara allar broytingar sum eru gjørdar síðan hesa versjónina mistar.",
'yourdiff' => 'Munir',
'copyrightwarning' => "Alt íkast til {{SITENAME}} er útgivið undir $2 (sí $1 fyri smálutir). Vilt tú ikki hava skriving tína broytta miskunnarleyst og endurspjadda frítt, so send hana ikki inn.<br />
Við at senda arbeiði títt inn, lovar tú, at tú hevur skrivað tað, ella at tú hevur avritað tað frá tilfeingi ið er almenn ogn &mdash; hetta umfatar '''ikki''' flestu vevsíður.
'''SEND IKKI UPPHAVSRÆTTARVART TILFAR UTTAN LOYVI!'''",
'copyrightwarning2' => "Vinarliga legg til merkis at øll íkøst til {{SITENAME}} kunnu rættast í, verða broytt, ella flutt av øðrum skrivarum.
Um tú ikki ynskir at tín skriving verður broytt miskunnarleyst, so skal tú ikki skriva nakað her.<br />
Tú lovar okkum eisini, at tú sjálv/ur hevur skrivað hetta, ella at tú hevur avritað tað frá keldu sum er almenn ogn (public domain) ella frá líkandi fríum keldum (sí $1 fyri nærri upplýsingar). 
'''Tú mást ikki senda tilfar inn, sum er vart av upphavsrætti, uttan so at tú hevur fingið loyvi til tað!'''",
'longpageerror' => "'''Feilur: Teksturin sum tú hevur sent inn er {{PLURAL:$1|eitt kilobyte|$1 kilobytes}} langur, sum er longri enn mest loyvda, sum er  {{PLURAL:$2|eitt kilobyte|$2 kilobytes}}.'''
Teksturin kann tí ikki verða goymdur.",
'readonlywarning' => "'''Ávaring: Dátugrunnurin er blivin stongdur orsakað av viðlíkahaldi, so tú kanst ikki goyma tínar rættingar júst nú.'''
Tað hevði kanska verið eitt gott hugskot, um tú avritar og goymir tín tekst í eina tekstfílu og goymir tað til seinni.

Umboðsstjórin ið stongdi hann gav hesa frágreiðing: $1",
'protectedpagewarning' => "'''Ávaring: Henda síðan er friðað, so at einans brúkarar við umboðsstjóra heimildum kunnu broyta hana.'''
Tann seinasta logg inn er goymt niðanfyri fyri ávísing:",
'semiprotectedpagewarning' => "'''Viðmerking:''' Hendan grein er vard soleiðis at bert skrásetir brúkarar kunnu rætta hana.
Tann seinasta innritanin er víst niðanfyri sum ávísing:",
'cascadeprotectedwarning' => "'''Ávaring:''' Henda síðan er blivin vard, soleiðis at bert brúkarar við administrator rættindum kunnu rætta hana, tí at hon er við í hesum kaskadu-vardu {{PLURAL:$1|síðu|síðum}}:",
'titleprotectedwarning' => "'''Ávaring: Henda síða er blivin vard, soleiðis at [[Special:ListGroupRights|serstøk brúkararættindi]] krevjast fyri at upprætta hana.'''
Tann seinasti posturin í loggfíluni er vístur niðanfyri fyri kelduávísing:",
'templatesused' => '{{PLURAL:$1|Fyrimynd|Fyrimyndir}} brúktar á hesu síðu:',
'templatesusedpreview' => '{{PLURAL:$1|Fyrimynd|Fyrimyndir}} brúktar í hesi forskoðan:',
'templatesusedsection' => '{{PLURAL:$1|Fyrimynd|Fyrimyndir}} brúktar í hesum brotinum:',
'template-protected' => '(friðað)',
'template-semiprotected' => '(lutvíst vardar)',
'hiddencategories' => 'Henda síðan er í {{PLURAL:$1|1 fjaldum bólki|$1 fjaldum bólkum}}:',
'nocreatetext' => '{{SITENAME}} hevur noktað fyri møguleikanum at upprætta nýggjar síður.
Tú kanst fara aftur og rætta eina síðu sum longu er til, ella [[Special:UserLogin|rita teg inn ella få tær eina konto]].',
'nocreate-loggedin' => 'Tú hevur ikki loyvi til at upprætta nýggjar síður.',
'sectioneditnotsupported-title' => 'Tað ber ikki til at rætta brot',
'sectioneditnotsupported-text' => 'Tað ber ikki til at rætta brot á hesi síðu.',
'permissionserrors' => 'Loyvisbrek',
'permissionserrorstext' => 'Tú hevur ikki loyvi til at gera hatta, orsakað av {{PLURAL:$1|hesi orsøk|hesum orsøkum}}:',
'permissionserrorstext-withaction' => 'Tú hevur ikki loyvi til at $2, orsakað av {{PLURAL:$1|hesi orsøk|hesum orsøkum}}:',
'recreate-moveddeleted-warn' => "'''Ávaring: Tú ert í ferð við at upprætta eina síðu, sum áður er blivin strikað.'''

Tú eigur at umhugsa, hvørt tað er passandi at halda fram við at rætta hesa síðuna.
Strikingar og flytingar loggurin (søgan) fyri hesa síðuna eru at finna her fyri at lætta um:",
'moveddeleted-notice' => 'Henda síðan er blivin strikað.
Strikingar og flytingar loggurin (søgan) fyri hesa síðuna eru at finna her niðanfyri.',
'log-fulllog' => 'Vís allan gerðalistan (loggin)',
'edit-hook-aborted' => 'Rættingin bleiv avbrotin av einum programmfeili. 
Ongin frágreiðing finst.',
'edit-gone-missing' => 'Tað var ikki møguligt at dagføra síðuna.
Tað sær út til at hon er blivin strikað.',
'edit-conflict' => 'Rættingar trupulleiki (konflikt).',
'edit-no-change' => 'Tín rætting var sæð burtur frá, tí ongin broyting varð gjørd í tekstinum.',
'postedit-confirmation' => 'Tín rætting varð goymd.',
'edit-already-exists' => 'Tað var ikki møguligt at upprætta nýggja síðu.
Síðan er longu til.',
'defaultmessagetext' => 'Standard boðtekstur',
'content-failed-to-parse' => 'Kláraði ikki at tulka $2 innihaldi fyri $1 modell: $3',
'invalid-content-data' => 'Ógyldug innihalds dáta',
'content-not-allowed-here' => '"$1" innihald er ikki loyvt á síðu [[$2]]',
'editwarning-warning' => 'Um tú fert frá hesi síðuni, so kanst tú missa tær broytingar ið tú hevur gjørt.
Um tú hevur ritað inn, so kanst tú sláa hesa ávaring frá í "Rættingar" partinum í tínum innstillingum.',

# Content models
'content-model-wikitext' => 'wikitekst',
'content-model-text' => 'simpul tekstur',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Ávaring:''' Henda síðan inniheldur ov nógvar útrokningstungar parsara-funksjónskall.

Hon eigur at hava minni enn $2 {{PLURAL:$2|kall}}, tað {{PLURAL:$1|er nú $1|eru nú $1 kall}}.",
'expensive-parserfunction-category' => 'Síður við ov nógvum dýrum parsara funktiónskallum',
'post-expand-template-inclusion-warning' => "'''Ávaring:''' Tað eru ov nógvar skabilónir á hesi síðu. 
Nakrar skabilónir vera ikki vístar.",
'post-expand-template-inclusion-category' => 'Síður sum innihalda ov nógvar skabilónir',
'post-expand-template-argument-warning' => "'''Ávaring:''' Henda síðan inniheldur í minsta lagi eitt skabilón parametur (template argument), sum fyllir meira enn loyvdu støddina. 
Hetta parametur er tí ikki tikið við.",
'post-expand-template-argument-category' => 'Síður har skabilón parametur (template arguments) ikki eru tikin við',
'parser-template-loop-warning' => 'Skapilónssloyfa funnin: [[$1]]',
'language-converter-depth-warning' => 'Markið fyri dýpd á málkonverteraranum er farið út um mark ($1)',
'node-count-exceeded-category' => 'Síður har talið av notum (node) er ov høgt',
'node-count-exceeded-warning' => 'Síðan hevur og høgt tal av notum (node-count)',
'expansion-depth-exceeded-category' => 'Síður ið fara yvir loyvdu víðkanar-dýpdina',
'expansion-depth-exceeded-warning' => 'Síðan fór út um markið fyri víðkanardýpdina',
'parser-unstrip-loop-warning' => 'Unstrip loop varð funnið',

# "Undo" feature
'undo-success' => 'Rættingin kann takast burtur aftur.
Vinarliga kanna eftir samanberingina niðanfyri fyri at vátta, at hetta er tað sum tú ynskir at gera, goym síðan broytingarnar niðanfyri fyri at gjøgnumføra buturtøkuna av rættingini.',
'undo-failure' => 'Rættingin kundi ikki takast burtur orsakað av konfliktum við rættingum sum eru gjørdar eftir at tú fór í gongd at rætta.',
'undo-norev' => 'Rættingin kann ikki takast burtur, tí at hon er ikki til ella var strikað.',
'undo-summary' => 'Tak burtur versjón $1 hjá [[Special:Contributions/$2|$2]] ([[User talk:$2|kjak]])',
'undo-summary-username-hidden' => 'Angra versjón $1 sum ein fjaldur brúkari hevur gjørt',

# Account creation failure
'cantcreateaccounttitle' => 'Tað ber ikki til at upprætta konto',
'cantcreateaccount-text' => "Upprættan frá hesi IP adressuni ('''$1''') er blivin sperrað av [[User:$3|$3]]. Orsøkin til sperringina sigst vera ''$2''

$3 sigur orsøkina vera ''$2''",

# History pages
'viewpagelogs' => 'Sí logg fyri hesa grein',
'nohistory' => 'Eingin broytisøga er til hesa síðuna.',
'currentrev' => 'Núverandi endurskoðan',
'currentrev-asof' => 'Seinasta endurskoðan sum var $1',
'revisionasof' => 'Endurskoðan frá $1',
'revision-info' => 'Versjón frá $1 av $2',
'previousrevision' => '←Eldri endurskoðan',
'nextrevision' => 'Nýggjari endurskoðan→',
'currentrevisionlink' => 'Skoða verandi endurskoðan',
'cur' => 'nú',
'next' => 'næst',
'last' => 'síðst',
'page_first' => 'fyrsta',
'page_last' => 'síðsta',
'histlegend' => 'Frágreiðing:<br />
(nú) = munur til núverandi útgávu,
(síðst) = munur til síðsta útgávu, m = minni rættingar',
'history-fieldset-title' => 'Leita í søguni',
'history-show-deleted' => 'Bert strikaðar',
'histfirst' => 'elsta',
'histlast' => 'nýggjasta',
'historysize' => '({{PLURAL:$1|1 být|$1 být}})',
'historyempty' => '(tóm)',

# Revision feed
'history-feed-title' => 'Versjónssøga',
'history-feed-description' => 'Versjónssøgan fyri hesa síðu á hesum wiki',
'history-feed-item-nocomment' => '$1 hin $2',
'history-feed-empty' => 'Umbidnað síðan er ikki til. 
Møguliga er hon blivin strikað frá wikinum, ella hevur fingið annað navn.
Royn [[Special:Search|leiting á wiki]] fyri at síggja viðkomandi níggjar síður.',

# Revision deletion
'rev-deleted-comment' => '(rættingar frágreiðingin er tikin burtur)',
'rev-deleted-user' => '(brúkaranavn tikið burtur)',
'rev-deleted-event' => '(gerðalista aktivitetur/log action er strikaður)',
'rev-deleted-user-contribs' => '[brúkaranavn ella IP adressa er strikað - rættingar eru fjaldar frá íkøstunum]',
'rev-deleted-text-permission' => "Henda versjónin av síðuni er blivin '''strikað'''.
Tú kanst finna smálutir hesum viðvíkjandi á [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strikingar logginum].",
'rev-deleted-text-unhide' => "Henda endurskoðan av síðuni er '''strikað'''.
Tú kanst finna smálutir hesum viðvíkjandi á [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strikingar logginum].
Tú kanst enn [$1 síggja hesa versjónina] um tú ynskir at halda fram.",
'rev-suppressed-text-unhide' => "Henda versjónin av síðuni er blivin '''samanpressað'''.
Tú kanst fáa fleiri upplýsingar í [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} samanpressingar logginum].
Tú kanst enn [$1 síggja hesa versjónina] um tú ynskir at halda fram.",
'rev-deleted-text-view' => "Henda versjónin av síðuni er blivin '''strikað'''.
Tú kanst síggja hana; smálutir eru at finna í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strikingar logginum].",
'rev-suppressed-text-view' => "Henda versjónin av síðuni er blivin '''samanpressað'''.
Tú kanst síggja hana; smálutir eru at finna í [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} samanpressingar logginum].",
'rev-deleted-no-diff' => "Tú kanst ikki síggja henda munin, tí at onnur av versjónunum er blivin '''strikað'''.
Smálutir hesum viðvíkjandi eru at finna í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strikingar logginum].",
'rev-suppressed-no-diff' => "Tú kanst ikki síggja henda munin, tí at onnur av hesum versjónunum er blivin '''strikað'''.",
'rev-deleted-diff-view' => "Ein av versjónunum av muninum er blivin '''strikað'''.
Tú kanst síggja munin; smálutir eru at finna í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strikingar logginum].",
'rev-suppressed-diff-view' => "Ein av verjsónunum av muninum er blivin '''samanpressað'''.
Tú kanst síggja munin; smálutir eru at finna í [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} samanpressingar logginum].",
'rev-delundel' => 'skoða/fjal',
'rev-showdeleted' => 'vís',
'revisiondelete' => 'Strika/endurnýggja broytingina',
'revdelete-nologtype-title' => 'Onki slag av loggi er upplýst',
'revdelete-nologtype-text' => 'Tú hevur ikki útgreinað nakað slag av loggi, fyri at útføra hesa handling á.',
'revdelete-nologid-title' => 'Ógyldugur loggpostur',
'revdelete-no-file' => 'Nevnda fíla er ikki til.',
'revdelete-show-file-confirm' => 'Ert tú vís/ur í, at tú ynskir at síggja eina strikaða endurskoðan av fíluni "<nowiki>$1</nowiki>" frá $2 kl. $3?',
'revdelete-show-file-submit' => 'Ja',
'revdelete-selected' => "'''{{PLURAL:$2|Valda versjón|Valdar versjónir}} hjá [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Útvald logghending|Útvaldar logghendingar}}:'''",
'revdelete-confirm' => 'Vinarliga vátta, at tú ætlar at gera hetta, at tú skilir avleiðingarnar, og at tú ger hetta í samsvari við [[{{MediaWiki:Policy-url}}|mannagongdirnar]].',
'revdelete-legend' => 'Set avmarkinga fyri sjónligheit',
'revdelete-hide-text' => 'Goym burtur tekstin á hesi versjónini',
'revdelete-hide-image' => 'Fjal fílu innihald',
'revdelete-hide-name' => 'Fjal handling og mál',
'revdelete-hide-comment' => 'Fjal rættingar frágreiðing',
'revdelete-hide-user' => 'Fjal brúkaranavn/IP adressu hjá tí sum rættar',
'revdelete-hide-restricted' => 'Síggj burtur frá data frá administratorum líka væl sum frá øðrum',
'revdelete-radio-same' => '(ikki broyta)',
'revdelete-radio-set' => 'Ja',
'revdelete-radio-unset' => 'Nei',
'revdelete-suppress' => 'Síggj burtur frá data frá administratorum líka væl sum frá øðrum',
'revdelete-unsuppress' => 'Tak burtur avmarkingar á endurskaptum versjónum',
'revdelete-log' => 'Orsøk:',
'revdelete-submit' => 'Fullfør á valdu {{PLURAL:$1|versjón|versjónir}}',
'revdelete-success' => "'''Versjón sjónligheit er dagført við hepni.'''",
'revdelete-failure' => "'''Versjóns sjónligheitin kundi ikki dagførast:'''
$1",
'logdelete-success' => "'''Sjónligheit broytt við hepni.'''",
'logdelete-failure' => "'''Tað bar ikki til at broyta loggsjónligheitina:'''
$1",
'revdel-restore' => 'broyt sjónligheit',
'revdel-restore-deleted' => 'strikaðar rættingar',
'revdel-restore-visible' => 'sjónligar broytingar',
'pagehist' => 'Síðu søgan',
'deletedhist' => 'Strikingar søga',
'revdelete-hide-current' => 'Tað er hendur ein feilur tá luturin skuldi fjalast, luturin er dagfestur $2, kl. $1: Hetta er nýggjast versjónin.
Hon kann ikki fjalast.',
'revdelete-show-no-access' => 'Feilur tá hesin lutur dagfestur $1 klokkan $2 skuldi vísast:Hesin lutur er blivin markeraður sum "avmarkaður".
Tú hevur ikki atgongd til hann.',
'revdelete-modify-no-access' => 'Feilur tá hesin lutur dagfestur $1 klokkan $2 skuldi broytast:Hesin lutur er blivin markeraður sum "avmarkaður".
Tú hevur ikki atgongd til hann.',
'revdelete-modify-missing' => 'Feilur hendi undir broytan av luti ID $1: Hann er ikki at finna í dátabasuni!',
'revdelete-no-change' => "'''Ávaring:''' Pettið ið er dagfest $1, kl. $2 hevði longu tær umbidnu innstillingar fyri sjónligheit.",
'revdelete-concurrent-change' => 'Ein feilur hendi, meðan tú dagførdi tekstin frá $1, kl. $2: Teksturin sær út til at vera blivin broyttur av onkrum øðrum, meðan tú royndi at rætta hann.',
'revdelete-reason-dropdown' => '*Vanligar orsøkir til sletting
** Brot á upphavsrætting
** Ópassandi viðmerking ella persónlig kunning
** Ósømiligt brúkaranavn
** Upplýsingar sum kunnu vera ærumeiðandi',
'revdelete-otherreason' => 'Onnur orsøk',
'revdelete-reasonotherlist' => 'Onnur orsøk',
'revdelete-edit-reasonlist' => 'Rætta strikingar orsøkir',
'revdelete-offender' => 'Høvundurin av hesi endurskoðan:',

# Suppression log
'suppressionlog' => 'Samanpressingarloggur',
'suppressionlogtext' => 'Niðanfyri sæst eitt yvirlit yvir slettingar og sperringar, sum fevnir um innihald, sum er fjalt fyri administratorum.
Hygg at [[Special:BlockList|sperringslistanum]] fyri at síggja listan yvir verandi bann og sperringar.',

# History merging
'mergehistory' => 'Samantvinna søgurnar hjá síðunum',
'mergehistory-header' => 'Henda síðan letur teg samanflætta versjónirnar frá søguni av einari síðu til eina nýggjari síðu.
Tryggja tær, at henda broyting fer at varðveita framhaldssøguna hjá síðuni.',
'mergehistory-box' => 'Samantvinna versjónirnar av tveimum síðum:',
'mergehistory-from' => 'Keldusíða:',
'mergehistory-into' => 'Komusíða:',
'mergehistory-list' => 'Rættingarsøgur, sum kunnu samanflættast',
'mergehistory-go' => 'Vís rættingar ið kunnu samantvinnast',
'mergehistory-submit' => 'Samanflætta versjónirnar',
'mergehistory-empty' => 'Ongar versjónir kunnu samanflættast.',
'mergehistory-success' => '$3 {{PLURAL:$3|versjón|versjónir}} av [[:$1]] er samanflættað við [[:$2]].',
'mergehistory-no-source' => 'Keldu síðan $1 er ikki til.',
'mergehistory-no-destination' => 'Destinatiónssíðan $1 er ikki til.',
'mergehistory-invalid-source' => 'Keldusíðan má hava eitt gyldugt heiti.',
'mergehistory-invalid-destination' => 'Destinatiónssíðan má hava eitt gyldugt heiti.',
'mergehistory-autocomment' => 'Flættaði [[:$1]] inn í [[:$2]]',
'mergehistory-comment' => 'Flættaði [[:$1]] inn í [[:$2]]: $3',
'mergehistory-same-destination' => 'Keldu og málsíðan kunnu ikki vera tann sama',
'mergehistory-reason' => 'Orsøk:',

# Merge log
'mergelog' => 'Samanblandingar gerðalisti (loggur)',
'pagemerge-logentry' => 'flættaði [[$1]] inn í [[$2]] (versjónir fram til $3)',
'revertmerge' => 'Angra samanflætting',
'mergelogpagetext' => 'Niðanfyri er ein listi við teimum nýggjastu samanflættingunum av einari síðu søgu til eina aðra.',

# Diffs
'history-title' => 'Eftirlitssøgan hjá "$1"',
'difference-title' => 'Munurin millum rættingarnar hjá "$1"',
'difference-title-multipage' => 'Munurin millum síðurnar "$1" og "$2"',
'difference-multipage' => '(Munur millum síður)',
'lineno' => 'Linja $1:',
'compareselectedversions' => 'Bera saman valdar útgávur',
'showhideselectedversions' => 'Vís/fjal valdu versjónir',
'editundo' => 'afturstilla',
'diff-empty' => '(Ongin munur)',
'diff-multi' => '({{PLURAL:$1|Ein versjón herímillum|$1 versjónir sum liggja ímillum}} av {{PLURAL:$2|einum brúkara|$2 brúkarar}} ikki víst)',
'diff-multi-manyusers' => '({{PLURAL:$1|Ein versjón sum liggur ímillum|$1 versjónir sum liggja ímillum}} skrivaðar av meira enn $2 {{PLURAL:$2|brúkara|brúkarum}} ikki víst)',

# Search results
'searchresults' => 'Leitúrslit',
'searchresults-title' => 'Leiti úrslit fyri "$1"',
'searchresulttext' => 'Ynskir tú fleiri upplýsingar um leiting á {{SITENAME}}, kanst tú skoða [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Tú leitaði eftur \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|allar síður sum byrja við "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|allar síður sum leinkja til "$1"]])',
'searchsubtitleinvalid' => "Tú leitaði eftur '''$1'''",
'toomanymatches' => 'Alt ov nógvar úrslit vóru funnin, vinarliga royn aftur við nýggjum fyrispurningi',
'titlematches' => 'Síðu heiti samsvarar',
'notitlematches' => 'Onki síðuheiti samsvarar',
'textmatches' => 'Teksturin á síðuni samsvarar',
'notextmatches' => 'Ongin síðutekstur samsvarar',
'prevn' => 'undanfarnu {{PLURAL:$1|$1}}',
'nextn' => 'næstu {{PLURAL:$1|$1}}',
'prevn-title' => 'Gomul $1 {{PLURAL:$1|úrslit|úrslit}}',
'nextn-title' => 'Næstu $1 {{PLURAL:$1|úrslit|úrslit}}',
'shown-title' => 'Vís $1 {{PLURAL:$1|úrslit|úrslit}} á hvørjari síðu',
'viewprevnext' => 'Vís ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Leiti møguleikar',
'searchmenu-exists' => "'''Tað er longu ein síða sum eitur \"[[:\$1]]\" á hesi wiki.'''",
'searchmenu-new' => "'''Stovna síðuna \"[[:\$1]]\" á hesi wiki!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Leita í síðum við hesum prefiksinum (byrjan av orðinum)]]',
'searchprofile-articles' => 'Innihaldssíður',
'searchprofile-project' => 'Hjálpar og verkætlanar síður',
'searchprofile-images' => 'Fjølmiðlar - multimedia',
'searchprofile-everything' => 'Alt',
'searchprofile-advanced' => 'Víðkað',
'searchprofile-articles-tooltip' => 'Leita í $1',
'searchprofile-project-tooltip' => 'Leita í $1',
'searchprofile-images-tooltip' => 'Leita eftir fílum',
'searchprofile-everything-tooltip' => 'Leita í øllum innihaldi (eisini í kjaksíðum)',
'searchprofile-advanced-tooltip' => 'Leita í ávísum navnaøkjum',
'search-result-size' => '$1 ({{PLURAL:$2|1 orð|$2 orð}})',
'search-result-category-size' => '{{PLURAL:$1|1 limur|$1 limir}} ({{PLURAL:$2|1 undirbólkur|$2 undirbólkar}}, {{PLURAL:$3|1 fíla|$3 fílur}})',
'search-result-score' => 'Viðkomandi: $1%',
'search-redirect' => '(umstilling $1)',
'search-section' => '(sektión $1)',
'search-suggest' => 'Meinti tú: $1',
'search-interwiki-caption' => 'Líknandi verkætlanir',
'search-interwiki-default' => '$1 úrslit:',
'search-interwiki-more' => '(meira)',
'search-relatedarticle' => 'Líknandi',
'mwsuggest-disable' => 'Slá leitingaruppskot frá',
'searcheverything-enable' => 'Leita í øllum navnaøkjum',
'searchrelated' => 'líknandi',
'searchall' => 'alt',
'showingresults' => "Niðanfyri standa upp til {{PLURAL:$1|'''$1''' úrslit, sum byrjar|'''$1''' úrslit, sum byrja}} við #<b>$2</b>.",
'showingresultsnum' => "Niðanfyri standa {{PLURAL:$3|'''1''' úrslit, sum byrjar|'''$3''' úrslit, sum byrja}} við #<b>$2</b>.",
'showingresultsheader' => "{{PLURAL:$5|Úrslit '''$1''' av '''$3'''|Úrslit '''$1 - $2''' av '''$3'''}} fyri '''$4'''",
'nonefound' => "'''Legg til merkis''': Sum standard verður bert leita í summum navnaøkum.
Tú kanst royna at brúka ''all:'' sum fyrsta stavilsi fyri at søkja í øllum innihaldi (eisini kjak síður, fyrimyndir, osfr.), ella brúka tað ynskta navnaøkið sum prefiks (forstavilsi).",
'search-nonefound' => 'Leitingin gav onki úrslit.',
'powersearch' => 'Leita',
'powersearch-legend' => 'Víðkað leitan',
'powersearch-ns' => 'Leita í navnaøkinum:',
'powersearch-redir' => 'Vís umvegir',
'powersearch-field' => 'Leita eftir',
'powersearch-togglelabel' => 'Kanna eftir:',
'powersearch-toggleall' => 'Alt',
'powersearch-togglenone' => 'Ongi',
'search-external' => 'Uttanhýsis leitan',
'searchdisabled' => '{{SITENAME}} leitan er sett úr gildi.
Tú kanst leita via Google ímeðan.
Legg til merkis, at teirra innihaldsyvirlit av {{SITENAME}} kann vera gamalt og ikki dagført.',
'search-error' => 'Ein feilur hendi undir leitanini: $1',

# Preferences page
'preferences' => 'Innstillingar',
'mypreferences' => 'Innstillingar',
'prefs-edits' => 'Tal av rættingum:',
'prefsnologin' => 'Tú hevur ikki ritað inn',
'prefsnologintext' => 'Tú mást vera <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} innritað/ur]</span> fyri at broyta brúkarainnstillingar.',
'changepassword' => 'Broyt loyniorð',
'prefs-skin' => 'Hamur',
'skin-preview' => 'Forskoðan',
'datedefault' => 'Ongi serlig ynskir',
'prefs-beta' => 'Betafunktiónir',
'prefs-datetime' => 'Dato og tíð',
'prefs-labs' => 'Testfunktiónir',
'prefs-user-pages' => 'Brúkarasíður',
'prefs-personal' => 'Brúkaradáta',
'prefs-rc' => 'Nýkomnar broytingar og stubbaskoðan',
'prefs-watchlist' => 'Eftirlit',
'prefs-watchlist-days' => 'Tal av døgum, sum skula vísast í eftirliti:',
'prefs-watchlist-days-max' => 'Í mesta lagi $1 {{PLURAL:$1|dagur|dagar}}',
'prefs-watchlist-edits' => 'Tal av rættingum, sum skula vísast í víðkaðum eftirliti:',
'prefs-watchlist-edits-max' => 'Í mesta lagi: 1000',
'prefs-watchlist-token' => 'Lykil til eftirlitslistan:',
'prefs-misc' => 'Ymiskar innstillingar',
'prefs-resetpass' => 'Broyt loyniorð',
'prefs-changeemail' => 'Broyt t-post adressu',
'prefs-setemail' => 'Skriva tína t-post adressu',
'prefs-email' => 'T-post møguleikar',
'prefs-rendering' => 'Útsjónd',
'saveprefs' => 'Goym innstillingar',
'resetprefs' => 'Reinsa ikki goymdar broytingar',
'restoreprefs' => 'Nullstilla alt til standard innstillingar (í øllum teigum)',
'prefs-editing' => 'Broyting av greinum',
'rows' => 'Røð:',
'columns' => 'Teigar:',
'searchresultshead' => 'Leita',
'resultsperpage' => 'Úrslit fyri hvørja síðu:',
'stub-threshold' => 'Avmarkað til <a href="#" class="stub">stubba leinki</a> formatering (bytes):',
'stub-threshold-disabled' => 'Er gjørt óvirki',
'recentchangesdays' => 'Dagar av vísa í seinastu broytingum:',
'recentchangesdays-max' => 'Í mesta lagi $1 {{PLURAL:$1|dagur|dagar}}',
'recentchangescount' => 'Tal av rættingum at vísa í standard:',
'prefs-help-recentchangescount' => 'Íroknað seinastu broytingar, søgur hjá síðum og loggar.',
'savedprefs' => 'Tínar innstillingar eru goymdar.',
'timezonelegend' => 'Tíðar sona:',
'localtime' => 'Lokal tíð:',
'timezoneuseserverdefault' => 'Nýt wiki standard: ($1)',
'timezoneuseoffset' => 'Annað (skrivað munin)',
'timezoneoffset' => 'Offset¹:',
'servertime' => 'Servara tíð:',
'guesstimezone' => 'Fyll út við kagara',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktis',
'timezoneregion-arctic' => 'Arktisk',
'timezoneregion-asia' => 'Asia',
'timezoneregion-atlantic' => 'Atlantarhavið',
'timezoneregion-australia' => 'Avstralia',
'timezoneregion-europe' => 'Evropa',
'timezoneregion-indian' => 'Indiska Havið',
'timezoneregion-pacific' => 'Stillahavið',
'allowemail' => 'Tilset t-post frá øðrum brúkarum',
'prefs-searchoptions' => 'Leita',
'prefs-namespaces' => 'Navnarúm',
'defaultns' => 'Um ikki, leita so í hesum navnateigum:',
'default' => 'standard',
'prefs-files' => 'Fílur',
'prefs-custom-css' => 'Tilpassað CSS',
'prefs-custom-js' => 'Tilpassað JavaScript',
'prefs-common-css-js' => 'Møgulig CSS/JavaScript fyri allar útsjóndir:',
'prefs-reset-intro' => 'Tú kanst brúka hesa síðuna til at nullstilla allar tínar valdu innstillingar, so tað kemur aftur til standard.
Tú kanst ikki angra, tá tað fyrst er gjørt.',
'prefs-emailconfirm-label' => 'Vátta tína t-post adressu:',
'youremail' => 'T-postur (sjálvboðið)*:',
'username' => '{{GENDER:$1|Brúkaranavn}}:',
'uid' => '{{GENDER:$1|Brúkari}} ID:',
'prefs-memberingroups' => '{{GENDER:$2|Limur}} í {{PLURAL:$1|bólki|bólkum}}:',
'prefs-registration' => 'Skrásett tíðspunkt:',
'yourrealname' => 'Títt navn*:',
'yourlanguage' => 'Mál til brúkaraflatu:',
'yourvariant' => 'Málvariantur fyri innihald:',
'yournick' => 'Nýggj undirskrift:',
'prefs-help-signature' => 'Viðmerkingar á kjaksíðum eiga at vera undirskrivaðar við "<nowiki>~~~~</nowiki>", sum verður gjørt um til tína undirskrift og eitt dagfestingarmerki.',
'badsig' => 'Ógyldug ráð undirskrift.
Eftirkannað HTML.',
'badsiglength' => 'Tín undirskrift er ov long. 
Hon má ikki hava meira enn $1 {{PLURAL:$1|tekn|tekn}}',
'yourgender' => 'Hvussu ynskir tú at lýsa teg?',
'gender-unknown' => 'Eg ynski ikki at upplýsa smálutir',
'gender-male' => 'Hann rættar wiki síður',
'gender-female' => 'Hon rættar wiki síður',
'prefs-help-gender' => 'Henda innstillingin er valfríð.
Ritbúnaðurin brúkar upplýsingina til at tiltala teg og tá tú verður nevnd/ur fyri øðrum, soleiðis at rætta kynið verður brúkt. 
Henda kunning verður almenn.',
'email' => 'T-post',
'prefs-help-realname' => 'Veruligt navn er valfrítt.
Um tú velur at skriva tað her, so verður tað nýtt til at geva tær æruna fyri títt arbeiði.',
'prefs-help-email' => 'Tú velur sjálvur, um tú vil skriva tína t-post adressu her, men tað er brúk fyri henni til at nullstilla loyniorðið, um tað skuldi hent, at tú gloymir títt loyniorð.',
'prefs-help-email-others' => 'Tú kanst eisini velja at lata onnur seta seg í samband við teg við telduposti gjøgnum eina leinkju á tínari brúkara ella kjak síðu. 
Tín t-post adressa verður ikki avdúkað, tá aðrir brúkarir seta seg í samband við teg.',
'prefs-help-email-required' => 'T-post adressa er kravd.',
'prefs-info' => 'Grundleggjandi kunning',
'prefs-i18n' => 'Altjóðagerð',
'prefs-signature' => 'Undirskrift',
'prefs-dateformat' => 'Slag av dagfesting',
'prefs-timeoffset' => 'Tíðarmunur',
'prefs-advancedediting' => 'Møguleikar sum heild',
'prefs-editor' => 'Persónur sum rættar',
'prefs-preview' => 'Forskoðan',
'prefs-advancedrc' => 'Víðkaðir møguleikar',
'prefs-advancedrendering' => 'Víðkaðir møguleikar',
'prefs-advancedsearchoptions' => 'Víðkaðir møguleikar',
'prefs-advancedwatchlist' => 'Víðkaðir møguleikar',
'prefs-displayrc' => 'Vís møguleikar',
'prefs-displaysearchoptions' => 'Vís møguleikar',
'prefs-displaywatchlist' => 'Vís møguleikar',
'prefs-tokenwatchlist' => 'Lykil',
'prefs-diffs' => 'Munir',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'T-post adressan sær út til at vera í gildi',
'email-address-validity-invalid' => 'Skriva eina gylduga t-post adressu',

# User rights
'userrights' => 'Handtering av brúkara rættindum',
'userrights-lookup-user' => 'Stýr brúkarabólkum',
'userrights-user-editname' => 'Skriva eitt brúkaranavn:',
'editusergroup' => 'Rætta brúkarabólkar',
'editinguser' => "Broytir rættindini hjá brúkara '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Rætta brúkarabólkar',
'saveusergroups' => 'Goym brúkaraflokk',
'userrights-groupsmember' => 'Limur í:',
'userrights-groupsmember-auto' => 'Óbeinleiðis limur í:',
'userrights-groups-help' => 'Tú kanst broyta bólkalimaskap hjá hesum limi: 
* Ein krossaður kassi merkir, at hesin brúkari er limur í tí bólkinum. 
* Ein kassi sum ikki er krossaður (tjekk merktur) merkir, at brúkarin ikki er limur í tí bólkinum. 
* Ein * merkir, at tú kanst ikki taka bólkin burtur, tá tú fyrst hevur sett hann inn og mótsatt.',
'userrights-reason' => 'Orsøk:',
'userrights-no-interwiki' => 'Tú hevur ikki loyvi til at rætta brúkara rættindi á øðrum wikium.',
'userrights-nodatabase' => 'Dátugrunnurin $1 er ikki til ella er hann ikki lokalur.',
'userrights-nologin' => 'Tú mást [[Special:UserLogin|rita inn]] sum administrator fyri at kunna áseta brúkararættindi.',
'userrights-notallowed' => 'Tú hevur ikki loyvi til at geva ella taka burtur brúkara rættindi.',
'userrights-changeable-col' => 'Bólkar sum tú kanst broyta',
'userrights-unchangeable-col' => 'Bólkar, ið tú ikki kanst broyta',
'userrights-conflict' => 'Ósamsvar viðvíkjandi broytingum í brúkararættindum! Vinarliga endurskoða og vátta tínar broytingar.',
'userrights-removed-self' => 'Tað eydnaðist tær at taka burtur tíni egnu rættindi. Tí kanst tú ikki longur fáa atgongd til hesa síðuna.',

# Groups
'group' => 'Bólkur:',
'group-user' => 'Brúkarar',
'group-autoconfirmed' => 'Sjálvvirkandi váttaðir brúkarar',
'group-bot' => 'Bottar',
'group-sysop' => 'Umboðsstjórar',
'group-bureaucrat' => 'Embætismenn',
'group-suppress' => 'Yvirlit',
'group-all' => '(allir)',

'group-user-member' => '{{GENDER:$1|brúkari}}',
'group-autoconfirmed-member' => '{{GENDER:$1|brúkari er váttaður sjálvvirkandi}}',
'group-bot-member' => '{{GENDER:$1|bottur}}',
'group-sysop-member' => '{{GENDER:$1|umboðsstjóri}}',
'group-bureaucrat-member' => '{{GENDER:$1|embætismaður}}',
'group-suppress-member' => '{{GENDER:$1|eftirlit}}',

'grouppage-user' => '{{ns:project}}:Brúkarar',
'grouppage-autoconfirmed' => '{{ns:project}}:Sjálvvirkandi váttaðir brúkarar',
'grouppage-bot' => '{{ns:project}}:Bottar',
'grouppage-sysop' => '{{ns:project}}:Umboðsstjórar',
'grouppage-bureaucrat' => '{{ns:project}}:Embætismenn',
'grouppage-suppress' => '{{ns:project}}:Eftirlit',

# Rights
'right-read' => 'Les síður',
'right-edit' => 'Rætta síður',
'right-createpage' => 'Stovna síður (sum ikki eru kjaksíður)',
'right-createtalk' => 'Stovna kjaksíðu',
'right-createaccount' => 'Stovna nýggja brúkara kontu',
'right-minoredit' => 'Markera rættingar sum smáar',
'right-move' => 'Flyt síður',
'right-move-subpages' => 'Flyt síður saman við undirsíðum teirra',
'right-move-rootuserpages' => 'Flyta høvuðs brúkarasíður',
'right-movefile' => 'Flyt fílur',
'right-suppressredirect' => 'Flyta síður uttan at upprætta víðaristilling frá tí gomlu síðuni.',
'right-upload' => 'Legg upp fílur',
'right-reupload' => 'Yvirskriva verandi fílur',
'right-reupload-own' => 'Yvirskriva verandi fílur, sum tú hevur lagt upp',
'right-upload_by_url' => 'Legg fílur upp frá einum URL',
'right-autoconfirmed' => 'Skal ikki ávirkast av IP-baseraðum avmarkingum',
'right-writeapi' => 'Nýtsla av skrivi-API',
'right-delete' => 'Strika síður',
'right-bigdelete' => 'Strika síður við nógvum versjónum',
'right-deletelogentry' => 'Strika og endurstovna serstakir loggpostar',
'right-deleterevision' => 'Strika og endurstovna serstakar versjónir av síðum',
'right-deletedhistory' => 'Hygg eftir slettaðum versjónum, uttan tilhoyrandi tekstin',
'right-deletedtext' => 'Sí strikaðan tekst og broytingar ímillum strikaðar endurskoðanir',
'right-browsearchive' => 'Leita í strikaðum síðum',
'right-undelete' => 'Endurstovnað eina síðu',
'right-suppressrevision' => 'Endurskoða og endurstovna versjónir sum eru fjaldar fyri administratorum',
'right-suppressionlog' => 'Vís privatar loggar',
'right-block' => 'Nokta øðrum brúkarum at rætta (blokka)',
'right-blockemail' => 'Nokta einum brúkara at senda teldupost',
'right-hideuser' => 'Sperra eitt brúkaranavn og goyma tað burtur fyri almenninginum',
'right-unblockself' => 'Taka burtur sperring hjá sær sjálvum',
'right-protect' => 'Broyt verjustøður og rætta kaskadu-vardar síður',
'right-editprotected' => 'Rætta síður sum eru vardar sum "{{int:protect-level-sysop}}"',
'right-editsemiprotected' => 'Rætta síður sum er vardar sum "{{int:protect-level-autoconfirmed}}"',
'right-editinterface' => 'Rætta brúkaramarkamótið',
'right-editusercssjs' => 'Rætta CSS og JavaScript fílur hjá øðrum brúkarum',
'right-editusercss' => 'Rætta CSS fílur hjá øðrum brúkarum',
'right-edituserjs' => 'Rætta JavaScript fílur hjá øðrum brúkarum',
'right-editmyusercss' => 'Rætta tínar egnu brúkara CSS fílur',
'right-editmyuserjs' => 'Rætta tínar egnu brúkara JavaScript fílur',
'right-viewmywatchlist' => 'Síggj tín egna eftirlitslista',
'right-editmywatchlist' => 'Rætta tín egna eftirlitslista. Legg til merkis at summar handlingar fara framvegis at leggja síður afturat sjálvt uttan hesi rættindi.',
'right-viewmyprivateinfo' => 'Síggj tíni egnu privatu upplýsingar (t.d. teldupostadressu, veruligt navn)',
'right-editmyprivateinfo' => 'Rætta tíni egnu privatu dáta (t.d. teldupost adresssu, veruligt navn)',
'right-editmyoptions' => 'Rætta tínar egnu innstillingar',
'right-rollback' => 'Rulla skjótt aftur (tak burtur) rættingarnar hjá tí seinasta brúkaranum á einari ávísari síðu',
'right-markbotedits' => 'Markera afturrullaðar rættingar sum rættingar frá einum botti',
'right-noratelimit' => 'Ikki ávirkað av hámarksferð',
'right-import' => 'Innflyt síður frá øðrum wikium',
'right-importupload' => 'Innflyt síður frá frá einari fílu sum er løgd út',
'right-patrol' => 'Marka broytingar hjá øðrum sum eftirkannaðar',
'right-autopatrol' => 'Hava eins egnu rættingar sjálvvirkamdi vístar sum eftirkannaðar',
'right-patrolmarks' => 'Síggj tær seinastu eftirlitsmerktu broytingar',
'right-unwatchedpages' => 'Sí lista við síðum sum ikki eru eftiransaðar',
'right-mergehistory' => 'Samanflætta søguna hjá hesum síðum',
'right-userrights' => 'Rætta øll brúkaraloyvir',
'right-userrights-interwiki' => 'Broyt brúkara rættindi hjá brúkarum á øðrum wikium',
'right-siteadmin' => 'Stong og læs upp dátugrunnin',
'right-override-export-depth' => 'Útflyt síður, eisini slóðaðar síður upp til eina dýpd á 5',
'right-sendemail' => 'Send t-post til aðrir brúkarar',
'right-passwordreset' => 'Sí teldupostar til nullstilling av loyniorði',

# Special:Log/newusers
'newuserlogpage' => 'Brúkara logg',
'newuserlogpagetext' => 'Hetta er ein listi yvir seinast stovnaðu brúkarar.',

# User rights log
'rightslog' => 'Rættindaloggur',
'rightslogtext' => 'Hetta er ein loggur sum vísir broytingar í brúkararættindum.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'les hesa síðu',
'action-edit' => 'rætta hesa síðuna',
'action-createpage' => 'upprætta síður',
'action-createtalk' => 'upprætta kjak síður',
'action-createaccount' => 'upprætta hesa brúkarakontuna',
'action-minoredit' => 'marka hesa rætting sum lítla',
'action-move' => 'flyt hesa síðu',
'action-move-subpages' => 'flyt hesa síðu og undirsíður hennara',
'action-move-rootuserpages' => 'flyt høvuðs brúkarasíður',
'action-movefile' => 'flyt hesa fílu',
'action-upload' => 'send hesa fílu upp',
'action-reupload' => 'yvirskriva hesa verandi fíluna',
'action-reupload-shared' => 'seta hesa fíluna til síðis í eina felags goymslu',
'action-upload_by_url' => 'legg henda fílin upp frá einari URL-adressu',
'action-writeapi' => 'nýt skrivi-API',
'action-delete' => 'Strika hesa síðu',
'action-deleterevision' => 'sletta hesa versjónina',
'action-deletedhistory' => 'hygg at strikingar søguni hjá hesi síðu',
'action-browsearchive' => 'leita eftir strikaðum síðum',
'action-undelete' => 'endurstovnað hesa síðu',
'action-suppressrevision' => 'endurskoða og endurstovna hesa fjaldu versjónina',
'action-suppressionlog' => 'sí henda privata loggin',
'action-block' => 'noktað hesum brúkara at rætta',
'action-protect' => 'broyt verjustøðuna hjá hesi síðu',
'action-rollback' => 'rulla skjótt aftur rættingarnar hjá tí seinasta brúkaranum, sum rættaði eina ávísa síðu',
'action-import' => 'innflyt hesa síðu frá aðrari wiki',
'action-importupload' => 'innflyt hesa síðuna frá einari fílu sum er løgd út',
'action-patrol' => 'markað rætting hjá øðrum sum eftirhugda',
'action-autopatrol' => 'fá tina rætting merkta sum eftirhugda',
'action-unwatchedpages' => 'Síggj listan yvir síður sum ikki eru eftiransaðar',
'action-mergehistory' => 'samanflætta søguna hjá hesi síðu',
'action-userrights' => 'broyt øll brúkaraloyvi',
'action-userrights-interwiki' => 'broyt brúkararættindi hjá brúkarum á øðrum wikium',
'action-siteadmin' => 'stong ella læs upp dátugrunnin',
'action-sendemail' => 'send teldupostar',
'action-editmywatchlist' => 'rætta tín eftirlitslista',
'action-viewmywatchlist' => 'síggja tín eftirlitslista',
'action-viewmyprivateinfo' => 'hygg at tínari privatu kunning',
'action-editmyprivateinfo' => 'rætta tína privatu kunning',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|broyting|broytingar}}',
'recentchanges' => 'Seinastu broytingar',
'recentchanges-legend' => 'Nýligar broytingar møguleikar',
'recentchanges-summary' => 'Á hesi síðu kanst tú fylgja teimum nýggjastu broytingunum á hesi wiki.',
'recentchanges-noresult' => 'Ongar broytingar í nevnda tíðarskeiðnum passa til hesi krøvini.',
'recentchanges-feed-description' => "Við hesum feed'inum kanst tú fylgja teimum seinastu broytingunum á hesi wiki'ini.",
'recentchanges-label-newpage' => 'Henda rætting upprættaði eina nýggja síðu',
'recentchanges-label-minor' => 'Hetta er ein lítil rætting',
'recentchanges-label-bot' => 'Henda rætting varð gjørd av einum botti',
'recentchanges-label-unpatrolled' => 'Henda rætting er ikki blivin eftirkannað enn',
'rcnote' => "Niðanfyri {{PLURAL:$1|stendur '''1''' tann seinasta broytingin|standa '''$1''' tær seinastu broytingarnar}} {{PLURAL:$2|seinasta dagin|seinastu '''$2''' dagarnar}}, frá $5, $4.",
'rcnotefrom' => "Niðanfyri standa broytingarnar síðani '''$2''', (upp til '''$1''' er sýndar).",
'rclistfrom' => 'Sýn nýggjar broytingar byrjandi við $1',
'rcshowhideminor' => '$1 minni rættingar',
'rcshowhidebots' => '$1 bottar',
'rcshowhideliu' => '$1 skrásettar brúkarar',
'rcshowhideanons' => '$1 navnleysar brúkarar',
'rcshowhidepatr' => '$1 eftirhugdar rættingar',
'rcshowhidemine' => '$1 mínar rættingar',
'rclinks' => 'Sýn seinastu $1 broytingarnar seinastu $2 dagarnar<br />$3',
'diff' => 'munur',
'hist' => 'søga',
'hide' => 'Goym',
'show' => 'Vís',
'minoreditletter' => 's',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 ansar eftir {{PLURAL:$1|brúkara|brúkarum}}]',
'rc_categories' => 'Avmarkað til síður frá bólkunum (skil sundur við "|")',
'rc_categories_any' => 'Nakar',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|bytes}} eftir broyting',
'newsectionsummary' => '/* $1 */ nýtt innlegg',
'rc-enhanced-expand' => 'Vís smálutir',
'rc-enhanced-hide' => 'Goym smálutir',
'rc-old-title' => 'upprunaliga stovnað sum "$1"',

# Recent changes linked
'recentchangeslinked' => 'Viðkomandi broytingar',
'recentchangeslinked-feed' => 'Viðkomandi broytingar',
'recentchangeslinked-toolbox' => 'Viðkomandi broytingar',
'recentchangeslinked-title' => 'Broytingar sum vísa til "$1"',
'recentchangeslinked-summary' => "Hetta er ein listi við broytingum sum nýliga eru gjørdir á síðum sum víst verður til frá einari serstakari síðu (ella til limir í einum serstøkum bólki).
Síður á [[Special:Watchlist|tínum eftiransingarlista]] eru skrivaðar við '''feitum'''.",
'recentchangeslinked-page' => 'Síðu heiti:',
'recentchangeslinked-to' => 'Vís broytingar til síður sum slóða til ta vístu síðuna í staðin.',

# Upload
'upload' => 'Legg fílu upp',
'uploadbtn' => 'Legg fílu upp',
'reuploaddesc' => 'Angra uppløðu og far aftur til upload formin',
'upload-tryagain' => 'Goym broytta fílu frágreiðing',
'uploadnologin' => 'Ikki ritað inn',
'uploadnologintext' => 'Tú mást $1 fyri at leggja fílur út.',
'upload-recreate-warning' => "'''Ávaring: Ein fíla við hasum navninum er blivin strikað ella flutt.'''

Strikingar og flytingar loggurin (søgan) fyri ta síðuna verður vístur her niðanfyri fyri at gera tað lættari hjá tær:",
'uploadtext' => "Brúka formularin her niðanfyri tá tú skalt leggja fílur út.
Fyri at síggja ella leita eftir fílur sum longu eru lagdar út, kanst tú fara til [[Special:FileList|lista við upploadaðum fílum]], (endur)uploads eru eisini goymd í [[Special:Log/upload|upload logginum]], strikingar í [[Special:Log/delete|strikingarlogginum]].

Fyri at taka eina fílu við á eina síðu, brúka so eina leinkju í ein av hesum formunum:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' fyri at brúka fulla versjón av fíluni
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' fyri at brúka eina 200 pixel breiða endurgeving í vinstra bredda við 'alt text' sum frágreiðing
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' fyri at leinkja beinleiðis til fíluna uttan at vísa fíluna",
'upload-permitted' => 'Loyvd fílu sløg: $1.',
'upload-preferred' => 'Best umtóktu fílu sløg: $1.',
'upload-prohibited' => 'Ikki loyvd fílu sløg: $1.',
'uploadlog' => 'fílu logg',
'uploadlogpage' => 'Fílugerðabók',
'uploadlogpagetext' => 'Her niðanfyri er ein listi við seinast uppløgdu fílum. 
Sí [[Special:NewFiles|myndasavn av nýggjum fílum]] fyri at fáa eitt meira visuelt yvirlit.',
'filename' => 'Fílunavn',
'filedesc' => 'Samandráttur',
'fileuploadsummary' => 'Samandráttur:',
'filereuploadsummary' => 'Fílu broytingar:',
'filestatus' => 'Upphavsrættar støða:',
'filesource' => 'Kelda:',
'uploadedfiles' => 'Upplagdar fílur',
'ignorewarning' => 'Síggj burtur frá ávaringum og goym fílu álíkavæl',
'ignorewarnings' => 'Ikki vísa ávaringar',
'minlength1' => 'Fílunavnið má hava í minsta lagi ein bókstav.',
'illegalfilename' => 'Fílunavnið "$1" inniheldur bókstavir sum ikki eru loyvdir í síðu nøvnum.
Vinarliga gev fíluni nýtt navn og royn at senda hana upp (uploada) enn einaferð.',
'filename-toolong' => 'Fílunøvn mugu ikki vera longri enn 240 bytes.',
'badfilename' => 'Myndin er umnevnd til "$1".',
'filetype-badmime' => 'Fílur av slagnum MIME "$1" eru ikki loyvd at verða send up (uploada).',
'filetype-unwanted-type' => "'''\".\$1\"''' er eitt óynskt fíluslag.
{{PLURAL:\$3|Ynskta fíluslag er|Ynskt fílusløg eru}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|er ikki eitt loyvt fíluslag|eru ikki loyvd fílusløg}}.
Loyvt/loyvd {{PLURAL:$3|fíluslag er|fílusløg eru}} $2.',
'filetype-missing' => 'Fílan hevur ongan enda (sum t.d. ".jpg").',
'empty-file' => 'Fílan sum tú sendi upp var tóm.',
'file-too-large' => 'Fílan sum tú sendi inn var ov stór.',
'filename-tooshort' => 'Fílunavnið er ov stutt.',
'filetype-banned' => 'Hetta slagi av fílum er bannað.',
'verification-error' => 'Henda fílan varð ikki góðkend av fílugóðkenningini.',
'hookaborted' => 'Broytingin ið tú royndi at gera var tikin burtur av einari leingjan (extension).',
'illegal-filename' => 'Hetta fílunavnið er ikki loyvt.',
'overwrite' => 'Tað er ikki loyvi til at yvirskriva eina verandi fílu.',
'unknown-error' => 'Ein ókend villa kom fyri.',
'tmp-create-error' => 'Kundi ikki upprætta fyribils fílu.',
'tmp-write-error' => 'Villa, meðan fyribils fíla skuldi skrivast.',
'large-file' => 'Tað verður viðmælt, at fílur ikki eru størri enn $1;
henda fílin er $2.',
'largefileserver' => 'Henda fílan er størri enn servarin er innstillaður til at loyva.',
'emptyfile' => 'Fílan ið tú legði út sær út til at vera tóm.
Hetta kann skyldast ein sláfeil í fílunavninum.
Vinarliga eftirkanna um tú veruliga ynskir at leggja hesa fíluna út.',
'windows-nonascii-filename' => "Henda wiki'in stuðlar ikki fílunøvn við serstøkum bókstavum/teknum.",
'fileexists' => 'Ein fíla við hesum navninum er longu til, vinarliga kanna eftir <strong>[[:$1]]</strong> um tú ivast í, um tú ynskir at broyta tað.
[[$1|thumb]]',
'filepageexists' => 'Síðan við frágreingin fyri hesa fíluna er longu til, hon er á <strong>[[:$1]]</strong>, men ongin fíla við hesum navninum er til í løtuni.
Frágreiðingin sum tú hevur skrivað kemur ikki at síggjast á síðuni.
Fyri at tín frágreiðing skal síggjast á síðuni, noyðist tú at skriva tað manuelt.
[[$1|thumb]]',
'fileexists-extension' => 'Ein fíla við líknandi navni finst longu: [[$2|thumb]]
* Heitið á fíluni tú leggur út: <strong>[[:$1]]</strong>
* Heitið á fílu ið longu finst: <strong>[[:$2]]</strong>
Vinarliga vel eitt annað navn.',
'fileexists-thumbnail-yes' => "Fílan sær út til at vera ein minka stødd ''(thumbnail)''.
[[$1|thumb]]
Vinarliga kanna fíluna <strong>[[:$1]]</strong>.
Um tann kannað fílan er tann sama myndin av einari uppruna stødd, so er tað ikki neyðugt at leggja út ein eyka thumbnail.",
'file-thumbnail-no' => "Fílunavnið byrjar við <strong>$1</strong>.
Tað sær út til at vera ein mynd av einari minkaðari stødd ''(thumbnail)''.
Um tú hevur hesa myndina í fullari upploysn, legg so hesa út, um ikki broyt so vinarliga fílunavnið.",
'fileexists-forbidden' => 'Ein fíla við hesum navninum finst longu og kann ikki verða yvirskivað.
Um tú álíkavæl ynskir at leggja út tína fílu, vinarliga far so aftur og vel eitt annað navn.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ein fíla við tí sama navninum finst longu í felags fílusavninum.
Um tú enn ynskir at leggja út tína fílu, vinarliga far so aftur og vel eitt annað navn.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Henda fílan er tann sama sum {{PLURAL:$1|henda fílan|hesar fílurnar}}:',
'file-deleted-duplicate' => 'Ein fíla, sum er líka sum henda ([[:$1]]) er fyrr blivin strikað.
Tú eigur at kanna eftir strikingarsøguna hjá hesi fílu, áðrenn tú heldur áframm við at leggja hana út enn einaferð.',
'uploadwarning' => 'Ávaring',
'uploadwarning-text' => 'Vinarliga broyt frágreiðingina fyri fíluna og royn umaftur.',
'savefile' => 'Goym fílu',
'uploadedimage' => 'sent "[[$1]]" upp',
'overwroteimage' => 'legði út eina nýggja versjón av "[[$1]]"',
'uploaddisabled' => 'Útleggjan av fílum er óvirkin.',
'copyuploaddisabled' => 'Útleggjan frá URL er óvirkið.',
'uploadfromurl-queued' => 'Tín útlegging er komin í bíðirøð.',
'uploaddisabledtext' => 'Útleggjan av fílum er óvirkið.',
'php-uploaddisabledtext' => 'Fíluútleggjan er óvirkið í PHP.
Vinarliga kanna eftir innstillingunum fyri file_uploads.',
'uploadscripted' => 'Henda fílan inniheldur HTML ella skriptkotu, sum í ávísum førum kunnu feillesast av einum internetkaga.',
'uploadvirus' => 'Fílan inniheldur ein virus!
Smálutir: $1',
'upload-source' => 'Keldufíla',
'sourcefilename' => 'Kelda fílunavn:',
'sourceurl' => 'Kelda URL:',
'destfilename' => 'Destinatión fílunavn:',
'upload-maxfilesize' => 'Fílu støddin má í mesta lagi vera: $1',
'upload-description' => 'Frágreiðing av fílu',
'upload-options' => 'Upplótingar møguleikar',
'watchthisupload' => 'Halt eyga við hesi fílu',
'filewasdeleted' => 'Ein fíla við hesum heitinum hevur fyrr verið upplóta og er seinni blivin strikað.
Tú eigur at eftirkanna $1 áðrenn tú heldur á við at upplóta fíluna enn einaferð.',
'filename-bad-prefix' => "Navnið á fíluni ið tú leggur út byrjar við '''\"\$1\"''', sum er eitt ikki-frágreiðandi navn, slík verða ofta givin sjálvvirkandi av talgildm myndatólum.
Vinarliga vel eitt navn ið greiður eitt sindur frá til tína fílu.",
'upload-success-subj' => 'Upplegging væleydnað',
'upload-success-msg' => 'Tín útlegging frá [$2] eydnaðist væl. Hon er tøk her: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Trupulleiki við útlegging',
'upload-failure-msg' => 'Har var ein trupulleiki við tínari útleggin frá [$2]:

$1',
'upload-warning-subj' => 'Ávaring um upplótan',
'upload-warning-msg' => 'Tað var ein trupulleiki við at leggja út frá [$2]. Tú kanst venda aftur til [[Special:Upload/stash/$1|upload formularin]] fyri at rætta henda trupulleikan.',

'upload-proto-error' => 'Skeiv protokol',
'upload-proto-error-text' => 'Fjarútleggjan krevur netadressur sum byrja við <code>http://</code> ella <code>ftp://</code>.',
'upload-file-error' => 'Innvortis brek',
'upload-file-error-text' => 'Ein innanhýsis feilur hendi, tá ein roynd var gjørd at upprætta eina fyribils fílu á ambætaranum.
Vinarliga set teg í samband við ein [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error' => 'Ókend villa tá tú legði út',
'upload-misc-error-text' => "Ein ókend villa fór fram meðan tú legði út.
Vinariga vátta, at URL'urin er gyldugur og atkomuligur og royn aftur.
Um trupulleikin heldur fram, set teg so vinarliga í samband við ein [[Special:ListUsers/sysop|administrator]].",
'upload-too-many-redirects' => "URL'urin innihelt ov nógvar umdirigeringar",
'upload-unknown-size' => 'Ókend stødd',
'upload-http-error' => 'Ein HTTP villa hendi: $1',
'upload-copy-upload-invalid-domain' => 'Upplótan av avritum ber ikki til frá hesum domeninum.',

# File backend
'backend-fail-stream' => 'Tað bar ikki til at stroyma fílu "$1".',
'backend-fail-backup' => 'Tað bar ikki til at taka backup av fílu "$1".',
'backend-fail-notexists' => 'Fílan $1 er ikki til.',
'backend-fail-notsame' => 'Ein ikki-eins fíla finst longu á "$1".',
'backend-fail-invalidpath' => '"$1" er ikki ein loyvd goymsluslóð.',
'backend-fail-delete' => 'Tað bar ikki til at sletta fílu "$1".',
'backend-fail-describe' => 'Tað bar ikki til at broyta metadáta fyri fílu "$1".',
'backend-fail-alreadyexists' => 'Fílan "$1" finst longu.',
'backend-fail-store' => 'Kundi ikki goyma fílu $1 á $2.',
'backend-fail-copy' => 'Kundi ikki avrita fílu $1 til $2.',
'backend-fail-move' => 'Kundi ikki flyta fílu $1 til $2.',
'backend-fail-opentemp' => 'Kundi ikki lata upp fyribils fílu.',
'backend-fail-writetemp' => 'Kundi ikki skriva til fyribils fílu.',
'backend-fail-closetemp' => 'Kundi ikki aftur fyribils fílu.',
'backend-fail-read' => 'Kundi ikki lesa fílu $1.',
'backend-fail-create' => 'Kundi ikki skriva fílu $1.',
'backend-fail-maxsize' => 'Tað bar ikki til at lesa fíluna "$1" tí hon er størri enn {{PLURAL:$2|eitt byte|$2 bytes}}.',
'backend-fail-readonly' => 'Goymslu backend "$1" er í løtuni í "bara-lesa" støðu. Orsøkin til hetta er: "\'\'$2\'\'"',
'backend-fail-connect' => 'Tað bar ikki til at fáa samband við goymslu-backend "$1".',
'backend-fail-internal' => 'Ein ókendur feilur hendi í goymsluskipanini (backend) "$1".',
'backend-fail-contenttype' => 'Tað bar ikki til at avgera slagi av fíluni ið skuldi goymast á "$1".',

# Lock manager
'lockmanager-notlocked' => 'Kundi ikki lata upp "$1"; hon er ikki stongd.',
'lockmanager-fail-closelock' => 'Kundi ikki lata aftur lás fílu fyri "$1".',
'lockmanager-fail-deletelock' => 'Kundi ikki sletta lás fílu fyri "$1".',
'lockmanager-fail-acquirelock' => 'Kundi ikki fáa lás til "$1".',
'lockmanager-fail-openlock' => 'Kundi ikki læsa upp fíluna til: "$1".',
'lockmanager-fail-releaselock' => 'Kundi ikki læsa upp læsingina fyri: "$1".',
'lockmanager-fail-db-release' => 'Kundi ikki loysa lásini í dátagrunninum $1.',
'lockmanager-fail-svr-acquire' => 'Kundi ikki skaffa lás til dátagrunnin $1.',
'lockmanager-fail-svr-release' => 'Kundi ikki loysa lásini í ambætaranum $1.',

# Special:UploadStash
'uploadstash' => 'Legg út stash',
'uploadstash-refresh' => 'Uppfrískað listan við fílum',

# img_auth script messages
'img-auth-accessdenied' => 'Atgongd noktað',
'img-auth-badtitle' => 'Tað bar ikki til at gera eitt heiti útfrá "$1".',
'img-auth-nologinnWL' => 'Tú ert ikki ritað/ur inn, og "$1" er ikki á hvítalista.',
'img-auth-nofile' => 'Fílan "$1" er ikki til',
'img-auth-isdir' => 'Tú roynir at fáa atgongd til mappuna "$1".
Bert fílu atgongd er loyvd.',
'img-auth-streaming' => 'Sendir "$1".',
'img-auth-noread' => 'Brúkarin hevur ikki rættindi til at lesa "$1".',
'img-auth-bad-query-string' => "URL'urin hevur ein ikki galdandi fyrispurning strong.",

# HTTP errors
'http-invalid-url' => 'Ógildug URL (internetadressa): $1',
'http-invalid-scheme' => 'URLar av slagnum "$1" verða ikki stuðlaðir.',
'http-request-error' => 'HTTP fyrispurningurin riggaði ikki av ókendum orsøkum.',
'http-read-error' => 'HTTP lesifeilur.',
'http-timed-out' => 'HTTP fyrispurningurin tók ov langa tíð.',
'http-curl-error' => 'Feilur meðan vit heintaðu URL: $1',
'http-bad-status' => 'Tað hendi ein feilur undir viðgerðini av HTTP fyrispurnininum: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URLurin er ikki atkomuligur',
'upload-curl-error6-text' => "URL'urin sum tú skrivaði er ikki atkomuligur.
Vinarliga dupult-eftirkannað at URL er rættur og at heimasíðan koyrir.",
'upload-curl-error28' => 'Tað gekk ov long tíð við uppload',
'upload-curl-error28-text' => 'Heimasíðan tók ov langa tíð at svara. 
Vinarliga kanna eftir um síðan koyrir (er online), bíða eina lítla løtu og royn so aftur.
Tú kanst eisini royna aftur, tá tað ikki eru so nógv í gongd her í senn.',

'license' => 'Lisensur:',
'license-header' => 'Lisensur',
'nolicense' => 'Onki valt',
'license-nopreview' => '(Fyrr ikki tøkt)',
'upload_source_url' => '(ein galdandi, alment atkomuligan URL)',
'upload_source_file' => '(ein fíla á tínari teldu)',

# Special:ListFiles
'listfiles_search_for' => 'Leita eftir miðla navni:',
'imgfile' => 'fíla',
'listfiles' => 'Myndalisti',
'listfiles_thumb' => 'Lítli mynd',
'listfiles_date' => 'Dagur',
'listfiles_name' => 'Navn',
'listfiles_user' => 'Brúkari',
'listfiles_size' => 'Stødd',
'listfiles_description' => 'Frágreiðing',
'listfiles_count' => 'Versjónir',
'listfiles-latestversion-yes' => 'Ja',
'listfiles-latestversion-no' => 'Nei',

# File description page
'file-anchor-link' => 'Mynd',
'filehist' => 'Søgan hjá fíluni',
'filehist-help' => 'Trýst á dato/tíð fyri at síggja fíluna, sum hon sá út tá.',
'filehist-deleteall' => 'strika alt',
'filehist-deleteone' => 'strika',
'filehist-revert' => 'endurstovna',
'filehist-current' => 'streymur',
'filehist-datetime' => 'Dagur/Tíð',
'filehist-thumb' => 'Lítil mynd',
'filehist-thumbtext' => 'Lítil mynd av versjónini frá $1',
'filehist-nothumb' => 'Ongin lítil mynd (thumbnail)',
'filehist-user' => 'Brúkari',
'filehist-dimensions' => 'Dimensjónir',
'filehist-filesize' => 'Stødd fílu',
'filehist-comment' => 'Viðmerking',
'filehist-missing' => 'Fíla væntar',
'imagelinks' => 'Nýtsla av fílu',
'linkstoimage' => 'Fylgjandi {{PLURAL:$1|síða slóðar|$1 síður slóða}} til hesa fílu:',
'linkstoimage-more' => 'Meira enn $1 {{PLURAL:$1|síða slóðar|síður slóða}} til hesa fílu.
Hesin listin vísir {{PLURAL:$1|fyrstu síðu slóð|firstu $1 síðu slóðir}} bert til hesa fílu.
Ein [[Special:WhatLinksHere/$2|fullur listi]] er tøkur.',
'nolinkstoimage' => 'Ongar síður slóða til hesa myndina.',
'morelinkstoimage' => 'Sí [[Special:WhatLinksHere/$1|fleiri leinkjur]] til hesa fílu.',
'linkstoimage-redirect' => '$1 (fílu víðaristilling) $2',
'duplicatesoffile' => 'Henda {{PLURAL:$1|fílan er eitt avrit|$1 fílur eru avrit}} av hesi fílu ([[Special:FileDuplicateSearch/$2|meira kunning]]):',
'sharedupload' => 'Henda fílan er frá $1 og kann verða brúkt av øðrum verkætlanum.',
'sharedupload-desc-there' => 'Henda fílan er frá $1 og kann verða brúkt av øðrum verkætlanum.
Vinarliga hygg at [$2 fílu frágreiðingarsíðu] fyri nærri kunning.',
'sharedupload-desc-here' => 'Henda fíla er frá $1 og kann verða brúka í øðrum verkætlanum.
Frágreiðingin á [$2 fílu frágreiðingar síðu] er víst her niðanfyri.',
'sharedupload-desc-edit' => 'Henda fílan er frá $1 og kann vera brúkt av øðrum verkætlanum.
Kanska ynskir tú at rætta frágreiðingina hjá fíluni á [$2 fílu frágreiðingarsíðuni] her.',
'sharedupload-desc-create' => 'Henda fílan er frá $1 og kann vera brúkt av øðrum verkætlanum.
Kanska ynskir tú at rætta frágreiðingina til fíluna á [$2 fílu frágreiðingarsíðuni] her.',
'filepage-nofile' => 'Ongin fíla við hesum navninum finst.',
'filepage-nofile-link' => 'Ongin fíla við hesum navninum finst, men tú kanst [$1 leggja hana út].',
'uploadnewversion-linktext' => 'Legg eina nýggja versjón av hesi fílu út',
'shared-repo-from' => 'frá $1',
'shared-repo' => 'eitt felags fíluarkiv',
'upload-disallowed-here' => 'Tú kanst ikki yvirskriva hesa fílu.',

# File reversion
'filerevert' => 'Endurstovna $1',
'filerevert-legend' => 'Endurstovna fílu',
'filerevert-intro' => "Tú ert í ferð við at endurstovna fílu '''[[Media:$1|$1]]''' til [$4 verssjónina sum hon sá út kl. $3, hin $2].",
'filerevert-comment' => 'Orsøk:',
'filerevert-defaultcomment' => 'Endurstovanð til versjón frá kl. $2, hin $1',
'filerevert-submit' => 'Endurstovna',
'filerevert-success' => "'''[[Media:$1|$1]]''' er blivið endurstovna til [$4 versjónina frá $2, kl. $3].",

# File deletion
'filedelete' => 'Strika $1',
'filedelete-legend' => 'Sletta fílu',
'filedelete-intro' => "Tú ert í ferð við at sletta fíluna '''[[Media:$1|$1]]''' saman við allari søguni.",
'filedelete-intro-old' => "Tú slettar versjón '''[[Media:$1|$1]]''' hin [$4 $3, $2].",
'filedelete-comment' => 'Orsøk:',
'filedelete-submit' => 'Strika',
'filedelete-success' => "'''$1''' er blivin strikað.",
'filedelete-success-old' => "Versjónin av '''[[Media:$1|$1]]''' frá kl. $3, hin $2 er blivið strikað.",
'filedelete-nofile' => "'''$1''' er ikki til.",
'filedelete-nofile-old' => "Tað er ongin goymd versjón av '''$1''' við teimum nevndu eginleikunum.",
'filedelete-otherreason' => 'Onnur/aðrar orsøkir:',
'filedelete-reason-otherlist' => 'Onnur orsøk',
'filedelete-reason-dropdown' => '*Vanligar orsøkir til sletting
** Brot á upphavsrættin
** Fílan kemur fyri tvær ferðir',
'filedelete-edit-reasonlist' => 'Rætta orsøkina til striking',
'filedelete-maintenance' => 'Orsakað av viðlíkahaldsarbeiði eru sletting og endurstovnan av fílum fyribils óvirkin.',
'filedelete-maintenance-title' => 'Ógjørligt at sletta fílu',

# MIME search
'mimesearch' => 'MIME-leit',
'mimetype' => 'MIME slag:',
'download' => 'tak niður',

# Unwatched pages
'unwatchedpages' => 'Ikki eftiransaðar síður',

# List redirects
'listredirects' => 'Sýn ávísingar',

# Unused templates
'unusedtemplates' => 'Óbrúktar fyrimyndir',
'unusedtemplatestext' => 'Henda síðan hevur ein lista við øllum síðum í {{ns:template}} navnarúminum, sum ikki eru á aðrari síðu.
Minst til at kanna eftir um aðrar síður slóða til fyrimyndirnar, áðrenn tú slettar tær.',
'unusedtemplateswlh' => 'aðrar slóðir',

# Random page
'randompage' => 'Tilvildarlig síða',
'randompage-nopages' => 'Tað eru ongar síður í hesum {{PLURAL:$2|navnarúminum|navnarúmunum}}: $1.',

# Random page in category
'randomincategory' => 'Tilvildarlig síða í bólkinum',
'randomincategory-invalidcategory' => '"$1" kann ikki brúkast sum bólkaheiti.',
'randomincategory-nopages' => 'Tað eru ongar síður í [[:Category:$1]].',
'randomincategory-selectcategory' => 'Fá tilvildarliga síðu frá bólki: $1 $2.',
'randomincategory-selectcategory-submit' => 'Far',

# Random redirect
'randomredirect' => 'Tilvildarlig ávísingarsíða',
'randomredirect-nopages' => 'Tað eru ongar víðaristillingar til navnarúmið "$1".',

# Statistics
'statistics' => 'Hagtøl',
'statistics-header-pages' => 'Síðu hagtøl',
'statistics-header-edits' => 'Rætti hagtøl',
'statistics-header-views' => 'Vís hagtøl',
'statistics-header-users' => 'Brúkarahagtøl',
'statistics-header-hooks' => 'Onnur hagtøl',
'statistics-articles' => 'Innihaldssíður',
'statistics-pages' => 'Síður',
'statistics-pages-desc' => 'Allar síður í wiki, kjaksíður, ávísingar og so framvegis rokna uppí',
'statistics-files' => 'Fílur lagdar upp',
'statistics-edits' => 'Síðurættingar síðan {{SITENAME}} varð stovnað',
'statistics-edits-average' => 'Miðal rættingar pr. síðu',
'statistics-views-total' => 'Sýningar tilsamans',
'statistics-views-total-desc' => 'Sýningar á síðum ið ikki eru til longur og á serstakar síður eru ikki taldar við',
'statistics-views-peredit' => 'Sýningar fyri hvørja rætting',
'statistics-users' => 'Skrásettir [[Special:ListUsers|brúkarir]]',
'statistics-users-active' => 'Virknir brúkarir',
'statistics-users-active-desc' => 'Brúkarar ið hava framt eina handling seinasta/u {{PLURAL:$1|døgnið|$1 dagarnar}}',
'statistics-mostpopular' => 'Mest sæddu síður',

'pageswithprop' => 'Síður við síðueginleika',
'pageswithprop-legend' => 'Síður við einum síðueginleika',
'pageswithprop-text' => 'Henda síðan vísir síður ein lista yvir síður, sum hava ein serstakan síðueginleika.',
'pageswithprop-prop' => 'Navn á eginleika:',
'pageswithprop-submit' => 'Far',
'pageswithprop-prophidden-long' => 'langur tekstur eginleikavirði er fjalt ($1)',

'doubleredirects' => 'Tvífaldað ávísing',
'doubleredirectstext' => 'Henda síða gevur yvirlit yvir síður, sum vísa víðari til aðrar víðaristillaðar síður.
Hvør linja inniheldur leinkjur til ta fyrstu og næstu víðaristillingina, eins væl og málið fyri tað næstu víðaristillingina, sum vanliga er tann "veruliga" endamáls síðan, sum tann fyrsta víðaristillingin átti at peika móti.
<del>Útkrossaðir</del> postar eru loystir.',
'double-redirect-fixed-move' => '[[$1]] er blivin flutt.
Víðaristilling verður nú gjørd til [[$2]].',
'double-redirect-fixed-maintenance' => 'Rætta dupulta umstýring frá [[$1]] til [[$2]].',
'double-redirect-fixer' => 'Umstýringsrættari',

'brokenredirects' => 'Brotnar ávísingar',
'brokenredirectstext' => 'Hesar víðaristillingar slóða til síður, ið ikki eru til:',
'brokenredirects-edit' => 'rætta',
'brokenredirects-delete' => 'strika',

'withoutinterwiki' => 'Síður uttan málslóðir',
'withoutinterwiki-summary' => 'Fylgjandi síður slóða ikki til útgávur á øðrum málum:',
'withoutinterwiki-legend' => 'Prefiks',
'withoutinterwiki-submit' => 'Skoða',

'fewestrevisions' => 'Greinir við minstum útgávum',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|beit}}',
'ncategories' => '$1 {{PLURAL:$1|bólkur|bólkar}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwiki|interwikiir}}',
'nlinks' => '$1 {{PLURAL:$1|slóð|slóðir}}',
'nmembers' => '$1 {{PLURAL:$1|limur|limir}}',
'nrevisions' => '$1 {{PLURAL:$1|versjón|versjónir}}',
'nviews' => '$1 {{PLURAL:$1|skoðan|skoðanir}}',
'nimagelinks' => 'Brúkt á $1 {{PLURAL:$1|síðu|síðum}}',
'ntransclusions' => 'brúkt á $1 {{PLURAL:$1|síðu|síðum}}',
'specialpage-empty' => 'Tað eru ongi úrslit fyri hesa rapportina.',
'lonelypages' => 'Foreldraleysar síður',
'lonelypagestext' => 'Hesar síður slóða ikki frá ella eru ikki tiknar við á aðrar síður á {{SITENAME}}.',
'uncategorizedpages' => 'Óbólkaðar síður',
'uncategorizedcategories' => 'Óbólkaðir bólkar',
'uncategorizedimages' => 'Fílir sum ikki eru bólkaðar',
'uncategorizedtemplates' => 'Óbólkaðar fyrimyndir',
'unusedcategories' => 'Óbrúktir bólkar',
'unusedimages' => 'Óbrúktar fílur',
'popularpages' => 'Umtóktar síður',
'wantedcategories' => 'Ynsktir bólkar',
'wantedpages' => 'Ynsktar síður',
'wantedpages-badtitle' => 'Ógyldugt heiti í úrslitunum: $1',
'wantedfiles' => 'Ynsktar fílir',
'wantedtemplates' => 'Ynsktar fyrimyndir',
'mostlinked' => 'Síður við flest ávísingum',
'mostlinkedcategories' => 'Bólkar við flestum ávísandi slóðum',
'mostlinkedtemplates' => 'Mest slóðaðar-til fyrimyndir',
'mostcategories' => 'Greinir við flest bólkum',
'mostimages' => 'Mest leinkjaðar-til fílur',
'mostinterwikis' => 'Síður við flestum interwiki-slóðum',
'mostrevisions' => 'Greinir við flestum útgávum',
'prefixindex' => 'Allar síður við forskoyti (prefiks)',
'prefixindex-namespace' => 'Allar síður við prefiksi (navnarúmið $1)',
'prefixindex-strip' => 'Tak burtur prefiks í listanum',
'shortpages' => 'Stuttar síður',
'longpages' => 'Langar síður',
'deadendpages' => 'Gøtubotnssíður',
'deadendpagestext' => 'Hesar síðurnar slóða ikki til aðrar síður í {{SITENAME}}.',
'protectedpages' => 'Friðaðar síður',
'protectedpages-indef' => 'Bert verjur sum vara óendaligt',
'protectedpages-cascade' => 'Bert niðurarvaðar verjur',
'protectedpagestext' => 'Hesar síður eru vardar móti flyting ella rætting',
'protectedpagesempty' => 'Ongar síður eru í løtuni vardar á henda hátt.',
'protectedtitles' => 'Vard heiti',
'protectedtitlestext' => 'Hesi heiti er vard móti upprættan',
'protectedtitlesempty' => 'Ongi heiti eru í løtuni vard á henda hátt.',
'listusers' => 'Brúkaralisti',
'listusers-editsonly' => 'Vís bara brúkarar sum hava gjørt rættingar',
'listusers-creationsort' => 'Bólkað eftir stovningardegnum',
'usereditcount' => '$1 {{PLURAL:$1|rætting|rættingar}}',
'usercreated' => '{{GENDER:$3|Upprættað}} hin $1 kl. $2',
'newpages' => 'Nýggjar síður',
'newpages-username' => 'Brúkaranavn:',
'ancientpages' => 'Elstu síður',
'move' => 'Flyt',
'movethispage' => 'Flyt hesa síðuna',
'unusedimagestext' => 'Fylgjandi fílur eru til, men eru ikki lagdar inn á nakra síðu.
Vinarliga legg merki til, at vevsíður kunnu slóða til eina fílu við beinleiðis URL, og tí kann hon enn síggjast her, hóast at hon er í regluligari nýtslu.',
'unusedcategoriestext' => 'Hesar bólkasíður eru til, sjálvt um ongin onnur síða ella bólkur brúkar tær.',
'notargettitle' => 'Onki mál',
'nopagetext' => 'Síðan ið tú leitar eftir er ikki til.',
'pager-newer-n' => '{{PLURAL:$1|nýggjari 1|nýggjari $1}}',
'pager-older-n' => '{{PLURAL:$1|eldri 1|eldri $1}}',
'suppress' => 'Yvirlit',

# Book sources
'booksources' => 'Bókakeldur',
'booksources-search-legend' => 'Leita eftir bókum',
'booksources-go' => 'Far',

# Special:Log
'specialloguserlabel' => 'Gjørt hevur:',
'speciallogtitlelabel' => 'Mál (heiti ella brúkari):',
'log' => 'Gerðabøkur',
'all-logs-page' => 'Allir almennir loggar',
'alllogstext' => 'Samansett sýning av øllum atkomuligum loggum hjá {{SITENAME}}.
Tú kanst avmarka sýningina við at velja slag av loggi, brúkaranavn (sum er følsamt fyri stórum og lítlum bókstavum) ella ávirkaðu síðuna (sum eisini er følsom fyri stórum og lítlum bókstavum).',
'logempty' => 'Ongir samsvarandi lutir í logginum.',
'log-title-wildcard' => 'Leita í heitum sum byrja við hesum teksti',
'showhideselectedlogentries' => 'Vís/fjal útvaldu loggarnir',

# Special:AllPages
'allpages' => 'Allar síður',
'alphaindexline' => '$1 til $2',
'nextpage' => 'Næsta síða ($1)',
'prevpage' => 'Fyrrverandi síða ($1)',
'allpagesfrom' => 'Vís síður sum byrja við:',
'allpagesto' => 'Vís síður sum enda við:',
'allarticles' => 'Allar greinir',
'allinnamespace' => 'Allar síður ($1 navnarúm)',
'allnotinnamespace' => 'Allar síður (tó ikki í $1 navnarúminum)',
'allpagesprev' => 'Undanfarnu',
'allpagesnext' => 'Næstu',
'allpagessubmit' => 'Far',
'allpagesprefix' => 'Vís síður við prefiksi:',
'allpagesbadtitle' => 'Síðuheitið ið tú skrivaði var skeivt ella hevði eitt inter-málsligt ella inter-wiki prefiks.
Tað inniheldur møguliga ein ella fleiri bókstavir ella tekn sum ikki kunnu nýtast í síðuheitum.',
'allpages-bad-ns' => '{{SITENAME}} hevur ikki navnarúmið "$1".',
'allpages-hide-redirects' => 'Vís ikki umstillingar',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Tú sær eina støðumynd av hesi síðu, sum kann vera upp til $1 gomul.',
'cachedspecial-viewing-cached-ts' => 'Tú hyggur eftir einari støðumynd (cached version) av hesi síðu, sum kann vera ikki heilt dagførd.',
'cachedspecial-refresh-now' => 'Síggj seinastu.',

# Special:Categories
'categories' => 'Bólkar',
'categoriespagetext' => 'Fylgjandi {{PLURAL:$1|bólkur inniheldur|bólkar innihalda}} síður ella miðlar (media).
[[Special:UnusedCategories|Ikki brúktir bólkar]] eru ikki vístar her.
Sí eisini [[Special:WantedCategories|ynsktir bólkar]].',
'categoriesfrom' => 'Vís bólkar, byrja við:',
'special-categories-sort-count' => 'sortera eftir stødd',
'special-categories-sort-abc' => 'uppdeil í bókstavarøð',

# Special:DeletedContributions
'deletedcontributions' => 'Slettaði brúkaraíkøst',
'deletedcontributions-title' => 'Slettaði brúkaraíkøst',
'sp-deletedcontributions-contribs' => 'íkøst',

# Special:LinkSearch
'linksearch' => 'Leitan í uttanhýsis slóðum',
'linksearch-pat' => 'Leita eftir leinkjum til:',
'linksearch-ns' => 'Navnarúm:',
'linksearch-ok' => 'Leita',
'linksearch-line' => '$1 slóðar frá $2',

# Special:ListUsers
'listusersfrom' => 'Vís brúkarar ið byrja við:',
'listusers-submit' => 'Sýna',
'listusers-noresult' => 'Ongin brúkari var funnin.',
'listusers-blocked' => '(sperraður/sperrað)',

# Special:ActiveUsers
'activeusers' => 'Listi yvir aktivar brúkarar',
'activeusers-intro' => 'Hetta er ein listi yvir brúkarar, ið høvdu okkurt slag av aktiviteti tann seinasta/teir seinastu $1 {{PLURAL:$1|dagin|dagarnar}}.',
'activeusers-count' => '$1 {{PLURAL:$1|handling|handlingar}} tann seinasta/teir seinastu {{PLURAL:$3|dagin|$3 dagarnar}}',
'activeusers-from' => 'Vís brúkarar, ið byrja við:',
'activeusers-hidebots' => 'Fjal bottar',
'activeusers-hidesysops' => 'Fjal umboðsstjórar (administratorar)',
'activeusers-noresult' => 'Ongir brúkarar funnir.',

# Special:ListGroupRights
'listgrouprights' => 'Brúkara bólka rættindi',
'listgrouprights-summary' => 'Henda síða vísir ein lista av brúkarabólkum, sum eru útgreinaðir á hesi wiki og rættindini hjá teimum einstøku bólkunum.
Møguliga er [[{{MediaWiki:Listgrouprights-helppage}}|meira kunning]] um einstøk rættindi.',
'listgrouprights-key' => 'Frágreiðing:
* <span class="listgrouprights-granted">Givin rættindi</span>
* <span class="listgrouprights-revoked">Frátikin rættindi</span>',
'listgrouprights-group' => 'Bólkur',
'listgrouprights-rights' => 'Rættindi',
'listgrouprights-helppage' => 'Help:Bólka rættindi',
'listgrouprights-members' => '(limalisti)',
'listgrouprights-addgroup' => 'Legg afturat {{PLURAL:$2|bólk|bólkar}}: $1',
'listgrouprights-removegroup' => 'Tak burtur {{PLURAL:$2|bólk|bólkar}}: $1',
'listgrouprights-addgroup-all' => 'Legg til allir bólkar',
'listgrouprights-removegroup-all' => 'Tak burtur allir bólkar',
'listgrouprights-addgroup-self' => 'Legg {{PLURAL:$2|bólk|bólkar}} til tína egnu konto: $1',
'listgrouprights-removegroup-self' => 'Tak burtur {{PLURAL:$2|bólk|bólkar}} frá egnari konto: $1',
'listgrouprights-addgroup-self-all' => 'Legg allir bólkar til egna konto',
'listgrouprights-removegroup-self-all' => 'Tak burtur allir bólkar frá egnari konto',

# Email user
'mailnologin' => 'Ongin móttakara bústaður',
'mailnologintext' => 'Tú mást hava [[Special:UserLogin|ritað inn]]
og hava virkandi teldupostadressu í [[Special:Preferences|innstillingum]] tínum
fyri at senda teldupost til aðrar brúkarar.',
'emailuser' => 'Send t-post til brúkara',
'emailuser-title-target' => 'Send teldupost til henda {{GENDER:$1|brúkaran}}',
'emailuser-title-notarget' => 'Send t-post til brúkara',
'emailpage' => 'Send t-post til brúkara',
'emailpagetext' => 'Tú kanst brúka skjalið niðanfyri til at senda ein teldupost til henda {{GENDER:$1|brúkara}}.
Teldupost adressan sum tú skrivaði í [[Special:Preferences|tíni brúkara ynskir]] kemur síðan fram sum "Frá" adressan í teldupostinum, soleiðis at móttakarin kann svara beinleiðis til tín.',
'usermailererror' => 'Feilur í handfaranini av meyli:',
'defemailsubject' => '{{SITENAME}} t-postur frá brúkara $1',
'usermaildisabled' => 'Brúkara t-postur er óvirkin',
'usermaildisabledtext' => 'Tú kanst ikki senda teldupost til aðrir brúkarar á hesi wiki',
'noemailtitle' => 'Ongin t-post adressa',
'noemailtext' => 'Hesin brúkarin hevur ikki upplýst eina gylduga t-post-adressu.',
'nowikiemailtitle' => 'Ongin t-postur er loyvdur',
'nowikiemailtext' => 'Hesin brúkarin hevur valt ikki at móttaka teldupost frá øðrum brúkarum.',
'emailnotarget' => 'Ikki-eksisterandi ella ógyldugt brúkaranavn fyri móttakaran.',
'emailtarget' => 'Skriva brúkaranavnið hjá móttakaranum',
'emailusername' => 'Brúkaranavn:',
'emailusernamesubmit' => 'Send',
'email-legend' => 'Send eitt teldubræv til ein annan {{SITENAME}} brúkara',
'emailfrom' => 'Frá:',
'emailto' => 'Til:',
'emailsubject' => 'Evni:',
'emailmessage' => 'Boð:',
'emailsend' => 'Send',
'emailccme' => 'Send mær avrit av mínum boðum.',
'emailccsubject' => 'Avrit av tínum boðum til $1: $2',
'emailsent' => 'T-postur sendur',
'emailsenttext' => 'Títt t-post boð er sent.',
'emailuserfooter' => 'Hesin teldupostur var sendur av $1 til $2 við "Send teldupost" funksjónini á {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Skriva kervisboð.',
'usermessage-editor' => 'Kervisboðberi',

# Watchlist
'watchlist' => 'Eftirlitslisti',
'mywatchlist' => 'Eftirlitslisti',
'watchlistfor2' => 'Fyri $1 $2',
'nowatchlist' => 'Tú hevur ongar lutir í eftirlitinum.',
'watchlistanontext' => 'Vinarliga $1 fyri at síggja ella rætta lutir í tínum eftirlitslista.',
'watchnologin' => 'Tú hevur ikki ritað inn',
'watchnologintext' => 'Tú mást vera [[Special:UserLogin|innritað/ur]] fyri at broyta tín eftirlitslista.',
'addwatch' => 'Legg til eftirlitslista',
'addedwatchtext' => 'Síðan  "[[:$1]]" er løgd undir [[Special:Watchlist|eftirlitslistan]] hjá tær.
Framtíðar broytingar á hesi síðu og tilknýttu kjaksíðuni verða at síggja har.',
'removewatch' => 'Tak burtur frá eftirlistslistanum',
'removedwatchtext' => 'Síðan "[[:$1]]" er strikað úr [[Special:Watchlist|tínum eftirliti]].',
'watch' => 'Eygleið',
'watchthispage' => 'Hav eftirlit við hesi síðuni',
'unwatch' => 'strika eftirlit',
'unwatchthispage' => 'Halt uppat við at hava eftirlit',
'notanarticle' => 'Ongin innihaldssíða',
'notvisiblerev' => 'Seinasta versjón av einum øðrum brúkara er blivin slettað',
'watchlist-details' => '{{PLURAL:$1|$1 síða|$1 síður}} á tínum vaktarlista, kjaksíður ikki íroknaðar.',
'wlheader-enotif' => 'Tað ber nú til at senda teldupost.',
'wlheader-showupdated' => "Síður sum eru broyttar síðan tú seinast vitjaði tær, eru vístar við '''feitum'''.",
'watchmethod-recent' => 'kanna eftir nýligum rættingum á eftirlitssíðum',
'watchmethod-list' => 'kannar síður undir eftirliti fyri feskar broytingar',
'watchlistcontains' => 'Títt eftirlit inniheldur {{PLURAL:$1|eina síðu|$1 síður}}.',
'iteminvalidname' => "Trupulleiki við luti '$1', ógyldugt navn...",
'wlnote' => "Niðanfyri {{PLURAL:$1|stendur seinasta broytingin|standa seinastu '''$1''' broytingarnar}} seinasta/u {{PLURAL:$2| tíman|'''$2''' tímarnar}} hin $3 kl. $4",
'wlshowlast' => 'Vís seinastu $1 tímar $2 dagar $3',
'watchlist-options' => 'Møguleikar í ansingarlistanum',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Eftirlitir...',
'unwatching' => 'Strikar eftirlit...',
'watcherrortext' => 'Ein feilur hendi, meðan tú royndi at broyta innstillingarnar fyri tín eftirlitslista fyri "$1".',

'enotif_mailer' => '{{SITENAME}} fráboðanarmeylur',
'enotif_reset' => 'Markera allar síður sum vitjaðar',
'enotif_impersonal_salutation' => '{{SITENAME}}brúkari',
'enotif_subject_deleted' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|slettað}} av $2',
'enotif_subject_created' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|upprættað}} av $2',
'enotif_subject_moved' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|flutt}} av $2',
'enotif_subject_restored' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|endurupprættað}} av $2',
'enotif_subject_changed' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|broytt}} av $2',
'enotif_body_intro_deleted' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|slettað}} $PAGEEDITDATE av $2, sí $3.',
'enotif_body_intro_created' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|upprættað}} hin $PAGEEDITDATE av $2, sí $3 fyri at síggja tað nýggjastu versjónina.',
'enotif_body_intro_moved' => '{{SITENAME}} síðan $1 er blivin {{GENDER:$2|flutt}} hin $PAGEEDITDATE av $2, sí $3 fyri at síggja nýggjastu versjónina.',
'enotif_body_intro_restored' => '{{SITENAME}} síðan $1 varð {{GENDER:$2|endurstovnað}} hin $PAGEEDITDATE av $2, sí $3 fyri at síggja nýggjastu versjóninina.',
'enotif_body_intro_changed' => '{{SITENAME}} síðan $1 varð {{GENDER:$2|broytt}} hin $PAGEEDITDATE av $2, sí $3 fyri at síggja nýggjastu versjónina.',
'enotif_lastvisited' => 'Sí $1 fyri allar broytingar síðan tína seinastu vitjan.',
'enotif_lastdiff' => 'Sí $1 fyri at síggja hesa broyting.',
'enotif_anon_editor' => 'dulnevndur brúkari $1',
'created' => 'stovnað',

# Delete
'deletepage' => 'Strika síðu',
'confirm' => 'Vátta',
'excontent' => "innihald var: '$1'",
'excontentauthor' => "innihaldið var: '$1' (og einasti rithøvundur var '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => 'innihaldið áðrenn síðan varð tømd var: "$1"',
'exblank' => 'síðan var tóm',
'delete-confirm' => 'Strikað "$1"',
'delete-legend' => 'Strikað',
'historywarning' => "'''Ávaring:''' Síðan, ið tú ert í gongd við at strika, hevur eina søgu við umleið $1 {{PLURAL:$1|broyting|broytingum}}:",
'confirmdeletetext' => 'Tú ert í gongd við endaliga at strika ein a síðu
ella mynd saman við allari søgu úr dátugrunninum.
Vinarliga vátta at tú ætlar at gera hetta, at tú skilur
avleiðingarnar og at tú gert tað í tráð við
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Verkið er fullgjørt',
'actionfailed' => 'Virksemi miseydnaðist',
'deletedtext' => '"$1" er nú strikað.
Sí $2 fyri fulla skráseting av strikingum.',
'dellogpage' => 'Striku logg',
'dellogpagetext' => 'Niðanfyri síggjast tær nýggjastu strikingarnar.',
'deletionlog' => 'striku logg',
'reverted' => 'Aftur til eina eldri verjsón',
'deletecomment' => 'Orsøk:',
'deleteotherreason' => 'Onnur orsøk:',
'deletereasonotherlist' => 'Onnur orsøk',
'deletereason-dropdown' => '*Vanligar orsøkir til striking
** Umbøn frá høvunda
** Brot á upphavsrættin
** Herverk (Vandalisma)',
'delete-edit-reasonlist' => 'Rætta orsøkir til striking',
'delete-toobig' => 'Henda síðan hevur eina langa rættingar søgu, meira enn $1 {{PLURAL:$1|versjón|versjónir}}. 
Striking av slíkum síðum er avmarkað fyri at forða fyri at onkur av óvart kemur til at forstýra {{SITENAME}}.',

# Rollback
'rollback' => 'Rulla broytingar aftur',
'rollback_short' => 'Rulla aftur',
'rollbacklink' => 'afturrulling',
'rollbacklinkcount' => 'rulla aftur $1 {{PLURAL:$1|rætting|rættingar}}',
'rollbacklinkcount-morethan' => 'rulla aftur meira enn $1 {{PLURAL:$1|rætting|rættingar}}',
'rollbackfailed' => 'Afturrulling miseydnað',
'cantrollback' => 'Tað ber ikki til at afturstilla rættingina;
tann seinasti ið skrivaði her er eisini tann einasti høvundurin á hesi síðu.',
'alreadyrolled' => 'Tað ber ikki til at rulla aftur seinastu rætting av [[:$1]] hjá [[User:$2|$2]] ([[User talk:$2|talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
onkur annar hevur longu rættað ella rullað síðuna aftur.

Seinasta broytingin á síðuni var av [[User:$3|$3]] ([[User talk:$3|kjak]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Rættingarfrágreiðingin var: \"''\$1''\".",
'revertpage' => 'Tók burtur rættingar hjá [[Special:Contributions/$2|$2]] ([[User talk:$2|kjak]]) til seinastu versjón hjá [[User:$1|$1]]',
'revertpage-nouser' => 'Tók burtur rættingar hjá einum fjaldum brúkara til seinastu versjón hjá [[User:$1|$1]]',
'rollback-success' => 'Tók burtur rættingar hjá $1;
broytti tað aftur til seinastu versjón hjá $2.',

# Edit tokens
'sessionfailure-title' => 'Sesjónsfeilur',
'sessionfailure' => 'Tað sær út til at vera ein trupulleiki við tínari innritanar sesjón; henda handlingin var ógildað sum fyribirgingar handling móti kapring av sesjónini.
Far aftur til ta fyrru síðuna, uppfríska síðuna og royn so aftur.',

# Protect
'protectlogpage' => 'Friðingarbók',
'protectlogtext' => 'Niðanfyri er ein listi yvir broytingar til verju av síðum.
Sí [[Special:ProtectedPages|listan fyri vardar síður]] fyri at síggja listan yvir síður sum í løtuni er vardar.',
'protectedarticle' => 'friðaði "[[$1]]"',
'modifiedarticleprotection' => 'broytti verjustøðuna fyri "[[$1]]"',
'unprotectedarticle' => 'strikaði friðing á "[[$1]]"',
'movedarticleprotection' => 'flutti verjuinnstillingar frá "[[$2]]" til "[[$1]]"',
'protect-title' => 'Friðar "$1"',
'protect-title-notallowed' => 'Sí verjustøðuna fyri "$1"',
'prot_1movedto2' => '$1 flutt til $2',
'protect-badnamespace-title' => 'Navnarúm ið ikki kann verjast',
'protect-badnamespace-text' => 'Síður í navnarúminum kunnu ikki verjast.',
'protect-norestrictiontypes-text' => 'Henda síðan kann ikki verða vard, tí tað eru ongar avmarkingar tøkar.',
'protect-norestrictiontypes-title' => 'Síða sum ikki kann verjast',
'protect-legend' => 'Vátta friðing',
'protectcomment' => 'Orsøk:',
'protectexpiry' => 'Gongur út:',
'protect-text' => "Her kanst tú síggja og broyta verjustøðuna fyri síðuna '''$1'''.",
'protect-locked-blocked' => "Tú kanst ikki broyta verjustøðu á síðu, meðan tú ert sperrað/ur.
Her er aktuella innstillingin hjá síðuni '''$1''':",
'protect-default' => 'Loyv øllum brúkarum',
'protect-fallback' => 'Loyv bert brúkarum við "$1" loyvi',
'protect-level-autoconfirmed' => 'Loyv bert autováttaðum brúkarum',
'protect-level-sysop' => 'Loyv bert umboðsstjórum',
'protect-summary-cascade' => 'niðurarvað',
'protect-expiring' => 'gongur út $1 (UTC)',
'protect-expiring-local' => 'gongur út $1',
'protect-expiry-indefinite' => 'óavmarkaða tíð',
'protect-cascade' => 'Veit verju til síður sum eru nevndar á hesi síðu (niðurarvað verja)',
'protect-cantedit' => 'Tú kanst ikki broyta verjustøðuna hjá hesi síðu, tí tú hevur ikki rætt til at skriva/rætta hana.',
'protect-othertime' => 'Annað tíðarskeið:',
'protect-othertime-op' => 'annað tíðarskeið',
'protect-existing-expiry' => 'Í løtuni gongur tíðin út: $3, $2',
'protect-otherreason' => 'Onnur orsøk:',
'protect-otherreason-op' => 'Aðrar orsøkir',
'protect-dropdown' => '*Vanligar orsøkir til verju
** Afturvendandi herverk
** Afturvendandi spamming
** Redaktiónskríggj
** Síða sum hevur sera nógv vitjandi',
'protect-edit-reasonlist' => 'Orsøkir til at síðan er vard móti rættingum',
'protect-expiry-options' => '1 tími:1 hour,1 dagur:1 day,1 vika:1 week,2 vikur:2 weeks,1 mánaður:1 month,3 mánaðir:3 months,6 mánaðir:6 months,1 ár:1 year,óendaligt:infinite',
'restriction-type' => 'Verndstøða:',
'restriction-level' => 'Verjustig',
'minimum-size' => 'Minst loyvda stødd',
'maximum-size' => 'Mest loyvda stødd:',
'pagesize' => '(být)',

# Restrictions (nouns)
'restriction-edit' => 'Rætta',
'restriction-move' => 'Flyt',
'restriction-create' => 'Upprætta',
'restriction-upload' => 'Legg upp',

# Restriction levels
'restriction-level-sysop' => 'fult vard',
'restriction-level-autoconfirmed' => 'hálvt vard',
'restriction-level-all' => 'eitt hvørt stig',

# Undelete
'undelete' => 'Endurstovna strikaðar síður',
'undeletepage' => 'Síggj og endurstovna slettaðar síður',
'undeletepagetitle' => "'''Fylgjandi inniheldur slettaðar versjónir av [[:$1|$1]]'''.",
'viewdeletedpage' => 'Síggj slettaðar síður',
'undeletepagetext' => 'Fylgjandi {{PLURAL:$1|síða er blivin slettað men er|$1 síður eru blivnar slettaðar men eru}} enn í goymsluni og kann/kunnu endurstovnast.
Goymslan kann til tíðir verða reinsað út (slettað).',
'undelete-fieldset-title' => 'Endurstovnað versjónir',
'undeleterevisions' => '$1 {{PLURAL:$1|versjón goymd|versjónir goymdar}}',
'undelete-revision' => 'Slettað versjón av $1 (frá $4 kl. $5) av $3:',
'undelete-nodiff' => 'Ongin eldri versjón varð funnin.',
'undeletebtn' => 'Endurstovna',
'undeletelink' => 'síggj/endurstovna',
'undeleteviewlink' => 'Hygg',
'undeletereset' => 'Endurset',
'undeleteinvert' => 'Umvent val',
'undeletecomment' => 'Orsøk:',
'undeletedrevisions' => '{{PLURAL:$1|1 versjón|$1 versjónir}} endurstovnað/ar',
'undeletedrevisions-files' => '{{PLURAL:$1|1 versjón|$1 versjónir}} og {{PLURAL:$2|1 fíla|$2 fílur}} endurstovnað/ar',
'undeletedfiles' => '{{PLURAL:$1|1 fíla endurstovna|$1 fílur endurstovnaðar}}',
'cannotundelete' => 'Endurstovnan miseydnaðist:
$1',
'undeletedpage' => "'''$1 er endurstovnað'''

Sí [[Special:Log/delete|slettingarloggin]] fyri at síggja seinastu strikingar og endurstovningar.",
'undelete-header' => 'Far til [[Special:Log/delete|slettingarloggin]] fyri at síggja nýliga slettaðar síður.',
'undelete-search-title' => 'Leita eftir slettaðum síðum',
'undelete-search-box' => 'Leita eftir slettaðum síðum',
'undelete-search-prefix' => 'Vís síður sum byrja við:',
'undelete-search-submit' => 'Leita',
'undelete-no-results' => 'Ongar síður sum passaðu til vóru funnar í arkivinum yvir slettaðar síður.',
'undelete-show-file-submit' => 'Ja',

# Namespace form on various pages
'namespace' => 'Navnarúm:',
'invert' => 'Umvend val',
'namespace_association' => 'Tilknýtt navnarúm',
'tooltip-namespace_association' => 'Set kross í henda kassan soleiðis at kjak- ella evnisnavnarúm, sum hava samband við tað valda navnarúmið, eisini vera tikin við',
'blanknamespace' => '(Greinir)',

# Contributions
'contributions' => '{{GENDER:$1|Brúkaraíkøst}}',
'contributions-title' => 'Brúkaraíkøst fyri $1',
'mycontris' => 'Íkøst',
'contribsub2' => 'Eftir $1 ($2)',
'nocontribs' => 'Ongar broytingar vóru funnar, sum samsvaraðu hesar treytirnar.',
'uctop' => '(verandi)',
'month' => 'Frá mánaði (og áðrenn):',
'year' => 'Frá ár (og áðrenn):',

'sp-contributions-newbies' => 'Vís bert íkast frá nýggjum kontoum',
'sp-contributions-newbies-sub' => 'Fyri nýggjar kontur',
'sp-contributions-newbies-title' => 'Brúkaraíkøst viðvíkjandi nýggjum kontum',
'sp-contributions-blocklog' => 'bannagerðabók',
'sp-contributions-deleted' => 'slettaði brúkaraíkøst',
'sp-contributions-uploads' => 'uploads',
'sp-contributions-logs' => 'gerðalistar (logglistar)',
'sp-contributions-talk' => 'kjak',
'sp-contributions-userrights' => 'stýring av brúkararættindum',
'sp-contributions-blocked-notice' => 'Hesin brúkarin er í løtuni sperraður.
Tann seinasti sperringarloggurin verður vístur niðanfyri til kunningar:',
'sp-contributions-blocked-notice-anon' => 'Henda IP adressan er í løtuni sperrað.
Tann seinasti sperringarloggurin verður vístur niðanfyri til kunningar:',
'sp-contributions-search' => 'Leita eftir íkøstum',
'sp-contributions-username' => 'IP adressa ella brúkaranavn:',
'sp-contributions-toponly' => 'Vís bara rættingar sum eru tær seinastu versjónirnar',
'sp-contributions-submit' => 'Leita',

# What links here
'whatlinkshere' => 'Hvat slóðar higar',
'whatlinkshere-title' => 'Síður sum slóða til "$1"',
'whatlinkshere-page' => 'Síða:',
'linkshere' => "Hesar síður slóða til '''[[:$1]]''':",
'nolinkshere' => "Ongar síður slóða til '''[[:$1]]'''.",
'nolinkshere-ns' => "Ongar síður slóða til '''[[:$1]]''' í tí valda navnarúminum.",
'isredirect' => 'ávísingarsíða',
'istemplate' => 'leggjast innan í',
'isimage' => 'fílu slóð',
'whatlinkshere-prev' => '{{PLURAL:$1|fyrrverandi|fyrrverandi $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|næst|næstu $1}}',
'whatlinkshere-links' => '← slóðir',
'whatlinkshere-hideredirs' => '$1 umdirigeringar',
'whatlinkshere-hidetrans' => '$1 innkluderingar (transclusions)',
'whatlinkshere-hidelinks' => '$1 slóðir',
'whatlinkshere-hideimages' => '$1 fíluslóðir',
'whatlinkshere-filters' => 'Filtur',

# Block/unblock
'autoblockid' => 'Autosperra #$1',
'block' => 'Sperra brúkara',
'unblock' => 'Tak sperring av brúkara burtur',
'blockip' => 'Banna brúkara',
'blockip-title' => 'Sperra brúkara',
'blockip-legend' => 'Sperra brúkara',
'ipadressorusername' => 'IP-adressa ella brúkaranavn:',
'ipbexpiry' => 'Gongur út:',
'ipbreason' => 'Orsøk:',
'ipbreasonotherlist' => 'Onnur orsøk',
'ipbreason-dropdown' => '*Vanligar orsøkir fyri sperring
** Innsetan av følskum upplýsingum
** Tekur burtur innihald av síðum
** Spammar leinkjur til uttanhýsis síður
** Skrivar tvætl á síður
** Ber seg skeivt at/ger seg inn á brúkarar
** Misnýtir fleiri kontur
** Brúkaranavn ið ikki kann góðtakast',
'ipb-hardblock' => 'Forða innritaðum brúkarum at skriva/rætta frá hesi IP adressuni',
'ipbcreateaccount' => 'Forða fyri upprættan av konto',
'ipbemailban' => 'Forða brúkara at senda teldupost',
'ipbenableautoblock' => 'Sperrað sjálvvirkandi tað seinastu IP adressuna, sum hesin brúkari brúkti og allar fylgjandi IP adressur, sum viðkomandi roynir at rætta/skriva frá',
'ipbsubmit' => 'Banna henda brúkaran',
'ipbother' => 'Annað tíðarskeið:',
'ipboptions' => '2 tímar:2 hours, 1 dagur:1 day, 3 dagar:3 days, 1 vika:1 week, 2 vikur:2 weeks, 1 mánaður:1 month, 3 mánaðir:3 months, 6 mánaðir:6 months, 1 ár:1 year, óendaligt:infinite',
'ipbotheroption' => 'annað',
'ipbotherreason' => 'Onnur orsøk:',
'ipbhidename' => 'Fjal brúkaranavn í rættingum og listum',
'ipbwatchuser' => 'Halt eyga við brúkara og kjaksíðum hjá hesum brúkara',
'ipb-disableusertalk' => 'Forða hesum brúkaranum at rætta sína egnu kjaksíðu, meðan viðkomandi er sperrað/ur',
'ipb-change-block' => 'Endurnýggja sperringina av hesum brúkara við hesum innstillingum',
'ipb-confirm' => 'Vátta sperring',
'badipaddress' => 'Ógyldug IP-adressa',
'blockipsuccesssub' => 'Banning framd',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] er blivin sperraður.<br />
Sí [[Special:BlockList|sperringarlistan]] fyri at síggja allar sperringar.',
'ipb-blockingself' => 'Tú ert í ferð við at sperra teg sjálvan! Ert tú vís/ur í at tú ynskir at gera tað?',
'ipb-confirmhideuser' => 'Tú ert í ferð við at sperra ein brúkara við "goym brúkara" aktiveraðum.  Hetta fer at fjala navnið hjá brúkaranum í øllum listum og loggum. Ert tú sikkur í at tú ynskir at gera tað?',
'ipb-edit-dropdown' => 'Rætta orsøkir til sperring',
'ipb-unblock-addr' => 'Óbanna $1',
'ipb-unblock' => 'Tak burtur sperring frá einum brúkaranavni ella IP adressu',
'ipb-blocklist' => 'Vís allar verandi sperringar',
'ipb-blocklist-contribs' => 'Íkøst hjá $1',
'unblockip' => 'Tak burtur sperring av brúkara',
'unblockiptext' => 'Nýt formularin niðanfyri fyri at endurupprætta skriviatgongdina hjá einari fyrrverandi sperraðari IP adressu ella einum brúkaranavni.',
'ipusubmit' => 'Strika hesa blokaduna',
'unblocked' => '[[User:$1|$1]] er ikki blokkaður longur',
'unblocked-range' => '$1 er ikki longur sperraður.',
'unblocked-id' => 'Sperring $1 er tikin burtur.',
'blocklist' => 'Sperraðir brúkarar',
'ipblocklist' => 'Bannaðir brúkarar',
'ipblocklist-legend' => 'Finn ein sperraðan brúkara',
'blocklist-userblocks' => 'Fjal sperringar av kontum',
'blocklist-tempblocks' => 'Fjal fyribils sperringar',
'blocklist-addressblocks' => 'Fjal einkult IP sperringar',
'blocklist-timestamp' => 'Tíðarstempul',
'blocklist-target' => 'Mál',
'blocklist-expiry' => 'Gongur út',
'blocklist-by' => 'Administrator ið sperraði',
'blocklist-params' => 'Sperringar parametur',
'blocklist-reason' => 'Orsøk',
'ipblocklist-submit' => 'Leita',
'ipblocklist-localblock' => 'Lokal sperring',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Onnur sperring|Aðrar sperringar}}',
'infiniteblock' => 'óendaligt',
'expiringblock' => 'gongur út $1kl. $2',
'anononlyblock' => 'anon. bara',
'noautoblockblock' => 'sjálvvirkandi sperring ikki virkin',
'createaccountblock' => 'upprættan av brúkarakonto er ikki virkin',
'emailblock' => 'tað ber ikki til at senda t-post',
'blocklist-nousertalk' => 'kann ikki skriva á sína egnu kjaksíðu',
'ipblocklist-empty' => 'Sperringslistin er tómur.',
'ipblocklist-no-results' => 'Umbidnað IP adressan ella brúkaranavnið er ikki sperrað.',
'blocklink' => 'banna',
'unblocklink' => 'óbanna',
'change-blocklink' => 'broyt blokkering',
'contribslink' => 'íkøst',
'emaillink' => 'send teldupost',
'autoblocker' => 'Sjálvvirkandi sperring tí at tín IP adressa nýliga er blivin brúkt av "[[User:$1|$1]]".
Orsøkin ið varð nevnd fyri at sperra $1 er "\'\'$2\'\'"',
'blocklogpage' => 'Bannagerðabók',
'blocklog-showlog' => 'Hesin brúkarin hevur fyrr verið sperraður.
Sperringarloggurin er vístur niðanfyri til kunningar:',
'blocklog-showsuppresslog' => 'Hesin brúkarin hevur fyrr verið sperraður og duldur.
Fjalingarloggurin er vístur niðanfyri til kunningar:',
'blocklogentry' => 'sperring [[$1]]  sum varir til $2 $3',
'reblock-logentry' => 'broytti innstillingina fyri sperring av [[$1]] sum varðir fram til $2 $3',
'unblocklogentry' => 'óbannaði $1',
'block-log-flags-anononly' => 'bert dulnevndir brúkarar',
'block-log-flags-nocreate' => 'upprætting av brúkarakonto er sperrað',
'block-log-flags-noautoblock' => 'sjálvvirkandi sperring ikki virkin',
'block-log-flags-noemail' => 'tú kanst ikki senda teldupost',
'block-log-flags-nousertalk' => 'tú kanst ikki skriva á tína egnu kjaksíðu',
'block-log-flags-hiddenname' => 'brúkaranavnið er fjalt',
'ipb_already_blocked' => '"$1" er longu sperrað/ur',
'ipb-needreblock' => '$1 er longu sperraður. Ynskir tú at broyta innstillingarnar?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Onnur sperring|Aðrar sperringar}}',
'unblock-hideuser' => 'Tú kanst ikki taka burtur sperringina hjá hesum brúkara, eftirsum brúkaranavnið hjá viðkomandi er fjalt.',
'ipb_cant_unblock' => 'Feilur: Sperring ID $1 ikki funnin. Tað er møguligt, at sperringin longu er tikin burtur.',
'proxyblocker' => 'Proxy sperring',
'sorbsreason' => 'Tín IP adressa er merkt sum ein open proxy í DNSBL sum {{SITENAME}} brúkar.',
'sorbs_create_account_reason' => 'Tín IP adressa er merkt sum ein open proxy í DNSBL sum {{SITENAME}} brúkar.
Tú kanst ikki upprætta eina konto.',
'cant-block-while-blocked' => 'Tú kanst ikki sperra aðrar brúkarar meðan tú sjálv/ur ert sperrað/ur.',
'ipbblocked' => 'Tú kanst ikki sperra ella taka sperring burtur hjá øðrum brúkarum, tí tú ert sjálv/ur sperrað/ur',
'ipbnounblockself' => 'Tú hevur ikki loyvi til at taka sperringina burtur hjá tær sjálvum',

# Developer tools
'lockdb' => 'Stong dátagrunn',
'unlockdb' => 'Lat dátagrunnin upp',
'lockdbtext' => 'At læsa dátugrunnin steðgar møguleikanum hjá øllum
brúkarum at broyta síður, broyta innstillingar sínar, broyta sínar eftirlitslistar og
onnur ting, ið krevja broytingar í dátugrunninum.
Vinarliga vátta, at hetta er tað, ið tú ætlar at gera, og at tú fert
at læsa dátugrunnin upp aftur tá ið viðgerðin er liðug.',
'lockconfirm' => 'Ja, eg vil veruliga stongja dátagrunnin.',
'unlockconfirm' => 'Ja, eg vil veruliga læsa dátagrunnin upp.',
'lockbtn' => 'Stong dátagrunnin',
'unlockbtn' => 'Læs upp dátagrunnin',
'locknoconfirm' => 'Tú krossaði ikki váttanarkassan.',
'lockdbsuccesssub' => 'Tað eydnaðist at stongja dátagrunnin',
'unlockdbsuccesssub' => 'Dátagrunnurin er opin',
'lockdbsuccesstext' => 'Dátugrunnurin er læstur.
<br />Minst til at [[Special:UnlockDB|læsa upp]] aftur, tá ið viðgerðin er liðug.',
'unlockdbsuccesstext' => 'Dátagrunnurin er latin upp aftur.',
'databasenotlocked' => 'Dátagrunnurin er ikki stongdur.',
'lockedbyandtime' => '(av {{GENDER:$1|$1}} hin $2 kl. $3)',

# Move page
'move-page' => 'Flyt $1',
'move-page-legend' => 'Flyt síðu',
'movepagetext' => "Við frymlinum niðanfyri kanst tú umnevna eina síðu og flyta alla hennara søgu við til nýggja navnið.
Gamla navnið verður ein tilvísingarsíða til ta nýggju.
Tú kanst dagføra tilvísingarsíður sum vísa til uppruna tittulin sjálvvirkandi.
Um tú velur ikki at gera tað, ver so vís/ur í at eftirkanna [[Special:DoubleRedirects|dupultar]]  ella [[Special:BrokenRedirects|brotnar tilvísingarsíður]].
Tú hevur ábyrgdina fyri at ansa eftir at slóðir framvegis peika hagar, tær skulu.

Legg merki til at síðan '''ikki''' verður flutt, um ein síða longu er við nýggja navninum, uttan so at hon er tóm, er ein tilvísingarsíða og onga rættingarsøgu hevur.
Hetta merkir at tú kanst umnevna eina síðu aftur hagani hon kom, um tú gjørdi eitt mistak, og tú kanst ikki yvirskriva eina verandi síðu.

'''ÁVARING!'''
Hetta kann vera ein ógvuslig og óvæntað broyting av einari vældámdari síðu.
Vinarliga tryggja tær, at tú skilur avleiðingarnar av hesum áðrenn tú heldur áfam.",
'movepagetext-noredirectfixer' => "Við frymlinum niðanfyri kanst tú umnevna eina síðu og flyta alla hennara søgu við til nýggja navnið.
Gamla navnið verður ein tilvísingarsíða til ta nýggju.
Ver vís/ur í at eftirkanna [[Special:DoubleRedirects|dupult]] ella [[Special:BrokenRedirects|brotnaðar umstillingar]].
Tú hevur ábyrgdina av at vissa teg um at leinkjur halda fram við at peika á har sum tað er meiningin at tær skulu fara.

Legg merki til at síðan '''ikki''' verður flutt, um ein síða longu er við nýggja navninum, uttan at hon er tóm og onga søgu hevur.
Hetta merkir at tú kanst umnevna eina síðu aftur hagani hon kom, um tú gjørdi eitt mistak. Tú kanst ikki skriva yvir eina verandi síðu.

'''ÁVARING!'''
Hetta kann vera ein ógvuslig og óvæntað flyting av einari vældámdari síðu.
Vinarliga tryggja tær, at tú skilur avleiðingarnar av hesum áðrenn tú heldur áfam.",
'movearticle' => 'Flyt síðu:',
'moveuserpage-warning' => "'''Ávaring:''' Tú ert í ferð við at flyta eina brúkarasíðu. Legg vinarliga til merkis, at bert síðan verður flutt og brúkarin fær ''ikki'' nýtt navn.",
'movenologin' => 'Hevur ikki ritað inn',
'movenologintext' => 'Tú skalt vera ein skrásettur brúkari og [[Special:UserLogin|innritað/ur]] fyri at kunna flyta eina síðu.',
'movenotallowed' => 'Tú hevur ikki loyvi til at flyta síður.',
'movenotallowedfile' => 'Tú hevur ikki loyvi til at flyta fílur.',
'cant-move-user-page' => 'Tú hevur ikki loyvi til at flyta brúkarasíður (uttan undirsíður).',
'cant-move-to-user-page' => 'Tú hevur ikki loyvi til at flyta eina síðu til eina brúkarasíðu (uttan til eina undirsíðu hjá einum brúkara).',
'newtitle' => 'Til nýtt heiti:',
'move-watch' => 'Hav eftirlit við hesi síðuni',
'movepagebtn' => 'Flyt síðu',
'pagemovedsub' => 'Flyting væleydnað',
'movepage-moved' => '\'\'\'"$1" er blivin flutt til "$2"\'\'\'',
'movepage-moved-redirect' => 'Ein víðaristilling er blivin upprættað.',
'articleexists' => 'Ein síða finst longu við hasum navninum,
ella er navnið tú valdi ógyldugt.
Vinarliga vel eitt annað navn.',
'cantmove-titleprotected' => 'Tú kanst ikki flyta eina síðu til hetta heitið, tí tað nýggja heitið er vart móti upprættan',
'talkexists' => "'''Tað eydnaðist at flyta sjálva síðuna, men kjaksíðan kundi ikki flytast, tí ein er longu har við tí nýggja heitinum.
Tú mást samantvinna tær manuelt.'''",
'movedto' => 'flyt til',
'movetalk' => 'Flyt kjaksíðuna eisini, um hon er til.',
'move-subpages' => 'Flyt undirsíður (upp til $1)',
'move-talk-subpages' => 'Flyt undirsíður hjá kjaksíðum (upp til $1)',
'movepage-page-exists' => 'Síðan $1 er longu til og kann ikki yvirskrivast sjálvvirkandi.',
'movepage-page-moved' => 'Síðan $1 er blivin flutt til $2.',
'movepage-page-unmoved' => 'Síðan $1 kundi ikki flytast til $2.',
'movepage-max-pages' => 'Í mesta lagi $1 {{PLURAL:$1|síða varð flutt|síður vóru fluttar}} og ongar aðrar verða fluttar sjálvvirkandi.',
'movelogpage' => 'Flyt gerðabók',
'movelogpagetext' => 'Niðanfyri er ein listi yvir allar fluttar síður.',
'movesubpage' => '{{PLURAL:$1|Undirsíða|Undirsíður}}',
'movesubpagetext' => 'Henda síða hevur $1 {{PLURAL:$1|undirsíðu|undirsíður}} sum vísast niðanfyri.',
'movenosubpage' => 'Henda síðan hevur ongar undirsíður.',
'movereason' => 'Orsøk:',
'revertmove' => 'endurstovna',
'delete_and_move' => 'Strika og flyt',
'delete_and_move_text' => '==Striking krevst==

Grein við navninum "[[:$1]]" finst longu. Ynskir tú at strika hana til tess at skapa pláss til flytingina?',
'delete_and_move_confirm' => 'Ja, strika hesa síðuna',
'delete_and_move_reason' => 'Er strikað fyri at gera pláss til flyting frá "[[$1]]"',
'selfmove' => 'Báðar síður hava sama heiti. Tað ber ikki til at flyta eina síðu til seg sjálva.',
'immobile-source-namespace' => 'Tað ber ikki til at flyta síðu í navnaøkinum "$1"',
'immobile-target-namespace' => 'Tað ber ikki til at flyta síður inn til navnaøkið "$1"',
'immobile-target-namespace-iw' => 'Tú kanst ikki flyta eina síðu til eina interwiki leinkju.',
'immobile-source-page' => 'Henda síðan kann ikki flytast.',
'imagenocrossnamespace' => 'Fílur kunnu ikki flytast til eitt navnarúm sum ikki inniheldur fílur',
'nonfile-cannot-move-to-file' => 'Kann ikki flyta ikki-fílur til fílunavnarúmið',
'imagetypemismatch' => 'Tann nýggja fíluendingin samsvarar ikki við fíluslagið',
'imageinvalidfilename' => 'Ynskta fílunavnið er ikki galdandi',
'fix-double-redirects' => 'Dagfør snarvegir (umdirigeringar) sum føra til tað upprunaliga heitið',
'move-leave-redirect' => 'Lat eina umstilling vera eftir',
'protectedpagemovewarning' => "'''Ávaring:''' Henda síðan er blivin friðað, so at einans brúkarar við umboðsstjóra heimildum kunnu flyta hana.
Tann seinasti loggurin er goymdur niðanfyri til ávísingar:",
'semiprotectedpagemovewarning' => "'''Legg til merkis:''' Henda síðan er blivin friðað, so at einans skrásettir brúkarar kunnu flyta hana.
Tann seinasti loggposturin er vístur niðanfyri til ávísingar:",
'move-over-sharedrepo' => '== Fílan er til ==
[[:$1]] finst í einari felagsgoymslu. At flyta fíluna til hetta heitið fer at seta til síðis tann deilda fílin.',
'file-exists-sharedrepo' => 'Fílunavnið ið tú valdi, verður longu brúkt í einari felags goymslu. 
Vinarliga vel eitt annað navn.',

# Export
'export' => 'Útflyt síður',
'exportall' => 'Útflyt allar síður',
'exportcuronly' => 'Tak bert verandi versjón við, ikki alla søguna',
'exportnohistory' => "----
'''Legg til merkis:''' Tað ber í løtuni ikki til at útflyta alla søguna hjá síðum henda vegin.",
'exportlistauthors' => 'Tak við eitt fult yvirlit yvir skrivarar fyri hvørja síðu',
'export-submit' => 'Útflyt',
'export-addcattext' => 'Legg afturat síður frá bólki:',
'export-addcat' => 'Legg afturat',
'export-addnstext' => 'Legg afturat síður frá navnarúmi:',
'export-addns' => 'Legg afturat',
'export-download' => 'Goym sum fílu',
'export-templates' => 'Tak fyrimyndir við',
'export-pagelinks' => 'Tak við slóðaðar síður til eina dýpd á:',

# Namespace 8 related
'allmessages' => 'Øll kervisboð',
'allmessagesname' => 'Navn',
'allmessagesdefault' => 'Enskur tekstur',
'allmessagescurrent' => 'Verandi tekstur',
'allmessagestext' => 'Hetta er eitt yvirlit av tøkum kervisboðum í MediaWiki-navnarúmi.
Vinarliga vitja [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] og [//translatewiki.net translatewiki.net] um tú ynskir at geva títt íkast til ta generisku MediaWiki lokalisatiónina.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' er ikki stuðlað orsakað av at '''\$wgUseDatabaseMessages''' er sløkt.",
'allmessages-filter-legend' => 'Filtur',
'allmessages-filter-unmodified' => 'Óbroytt',
'allmessages-filter-all' => 'Øll',
'allmessages-filter-modified' => 'Broytt',
'allmessages-prefix' => 'Filtrera eftir forstavilsi:',
'allmessages-language' => 'Mál:',
'allmessages-filter-submit' => 'Far',

# Thumbnails
'thumbnail-more' => 'Víðka',
'filemissing' => 'Fíla vantar',
'thumbnail_error' => 'Feilur við upprættan av thumbnail (lítlari mynd): $1',
'thumbnail_error_remote' => 'Feilboð frá $1:
$2',
'djvu_page_error' => 'DjVu síða uttanfyri økið',
'djvu_no_xml' => 'Tað bar ikki til at heinta XML fyri DjVu fílu',
'thumbnail-temp-create' => 'Tað bar ikki til at upprættað eina fyribils pinkalítla stødd (thumbnail) av fíluni',
'thumbnail-dest-create' => 'Tað bar ikki til at goyma lítla mynd til ætlaða staðið',
'thumbnail_invalid_params' => 'Ógyldug thumbnail parametur',
'thumbnail_dest_directory' => 'Tað bar ikki til at upprætta málmappu',
'thumbnail_image-type' => 'Myndaslagið verður ikki stuðlað',
'thumbnail_image-missing' => 'Fílan sær út til at mangla: $1',

# Special:Import
'import' => 'Innflyt síður',
'importinterwiki' => 'Innflyt frá aðrari wiki',
'import-interwiki-text' => 'Vel eina wiki og síðuheiti at innflyta.
Dato og nøvnini á høvundunum í versjónini verða varveitt.
Allar transwiki innflytingar handlingar verða goymdar í [[Special:Log/import|innflytingarlogginum]].',
'import-interwiki-source' => 'Kelduwiki/síða:',
'import-interwiki-history' => 'Avrita alla versjónssøguna fyri hesa síðu',
'import-interwiki-templates' => 'Tak allar fyrimyndir við',
'import-interwiki-submit' => 'Innflyta',
'import-interwiki-namespace' => 'Innflyt til navnarúm:',
'import-upload-filename' => 'Fílunavn',
'import-comment' => 'Viðmerking:',
'importtext' => "Útflyt fíluna frá kelduwiki'ini við at nýta [[Special:Export|útflutningstólið]].
Goym hana á tínari teldu og legg hana so út her.",
'importstart' => 'Innflytur síður...',
'import-revision-count' => '$1 {{PLURAL:$1|versjón|versjónir}}',
'importnopages' => 'Ongar síður eru at innflyta.',
'imported-log-entries' => 'Innflutti $1 {{PLURAL:$1|loggpost|loggpostar}}.',
'importfailed' => 'Innflutningur miseydnaður: $1',
'importunknownsource' => 'Ókent slag av innflutningskeldu',
'importcantopen' => 'Innflutningsfíla kundi ikki latast upp',
'importbadinterwiki' => 'Skeiv interwiki leinkja',
'importnotext' => 'Tómt ella ongin tekstur',
'importsuccess' => 'Innflutningur er liðugur!',
'importnofile' => 'Ongin fíla at innflyta varð løgd út.',
'importuploaderrorsize' => 'Útleggjan av innflutningsfílu miseydnaðist.
Fílan er størri enn mest loyvda upload-støddin.',
'import-noarticle' => 'Ongin síða at innflyta!',
'import-nonewrevisions' => 'Allar versjónir eru longu innfluttar.',
'xml-error-string' => '$1 á linju $2, rekkju $3 (byte $4): $5',
'import-upload' => 'Legg út XML dáta',
'import-token-mismatch' => 'Misti setunardáta (sesjónsdáta).
Vinarliga royn aftur.',
'import-invalid-interwiki' => 'Tað ber ikki til at innflyta frá nevndu wiki.',
'import-error-edit' => 'Síðan "$1" varð ikki innflutt, tí at tú ikki hevur loyvi til at rætta hana.',
'import-error-create' => 'Síðan "$1" varð ikki innflutt, tí at tú ikki hevur loyvi til at upprætta hana.',
'import-options-wrong' => '{{PLURAL:$2|Ikki loyvd innstilling|Ikki loyvdar innstillingar}}: <nowiki>$1</nowiki>',

# Import log
'importlogpage' => 'Innflutningsloggur.',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|versjón|versjónir}}',
'import-logentry-interwiki' => '$1 varð flutt millum wikiir',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versjón|versjónir}} frá $2',

# JavaScriptTest
'javascripttest' => 'Royndarkoyring av JavaScript',
'javascripttest-title' => 'Koyrir $1 royndir',
'javascripttest-pagetext-noframework' => 'Henda síðan er løgd av til at koyra JavaScript royndir.',
'javascripttest-pagetext-skins' => 'Vel eina útsjónd at koyra royndirnar við:',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Tín brúkarasíða',
'tooltip-pt-anonuserpage' => 'Brúkarasíðan fyri IP adressuna, sum tú rættar frá',
'tooltip-pt-mytalk' => 'Tín kjaksíða',
'tooltip-pt-anontalk' => 'Kjak um rættingar frá hesi IP adressuni',
'tooltip-pt-preferences' => 'Tínar innstillingar',
'tooltip-pt-watchlist' => 'Ein listi við síðum sum tú eftiransar fyri broytingum',
'tooltip-pt-mycontris' => 'Yvirlit yvir títt íkast',
'tooltip-pt-login' => 'Vit mæla til at tú ritar inn, tað er tó ikki eitt krav.',
'tooltip-pt-anonlogin' => 'Vit mæla til at tú ritar inn, tað er tó ikki eitt krav',
'tooltip-pt-logout' => 'Rita út',
'tooltip-ca-talk' => 'Kjak um innihaldssíðuna',
'tooltip-ca-edit' => 'Tú kanst broyta hesa síðuna. Vinarliga nýt forskoðanarknøttin áðrenn tú goymir.',
'tooltip-ca-addsection' => 'Byrja eitt nýtt brot',
'tooltip-ca-viewsource' => 'Henda síðan er friðað. Tú kanst síggja keldukotuna.',
'tooltip-ca-history' => 'Fyrrverandi útgávur av hesi síðu.',
'tooltip-ca-protect' => 'Friða hesa síðuna',
'tooltip-ca-unprotect' => 'Broyt verju av hesi síðu',
'tooltip-ca-delete' => 'Strika hesa síðuna',
'tooltip-ca-undelete' => 'Endurnýggja skrivingina á hesi síðu áðrenn hon varð strikað',
'tooltip-ca-move' => 'Flyt hesa síðuna',
'tooltip-ca-watch' => 'Legg hesa síðuna til tín eftirlitslista',
'tooltip-ca-unwatch' => 'Fá hesa síðuna úr mínum eftirliti',
'tooltip-search' => 'Leita í {{SITENAME}}',
'tooltip-search-go' => 'Far til síðu við júst hesum heiti, um hon er til',
'tooltip-search-fulltext' => 'Leita eftir síðum sum innihalda henda  tekstin',
'tooltip-p-logo' => 'Vitja forsíðuna',
'tooltip-n-mainpage' => 'Vitja forsíðuna',
'tooltip-n-mainpage-description' => 'Vitja forsíðuna',
'tooltip-n-portal' => 'Um verkætlanina, hvat tú kanst gera, hvar tú finnur ymiskt',
'tooltip-n-currentevents' => 'Finn bakgrundsupplýsingar um aktuellar hendingar',
'tooltip-n-recentchanges' => 'Listi við teimum seinastu broytingunum í hesi wiki.',
'tooltip-n-randompage' => 'Far til tilvildarliga síðu',
'tooltip-n-help' => 'Staðið har tú fært hjálp',
'tooltip-t-whatlinkshere' => 'Yvirlit yvir allar wikisíður, ið slóða higar',
'tooltip-t-recentchangeslinked' => 'Broytingar á síðum, ið slóða higar, í seinastuni',
'tooltip-feed-rss' => 'RSS-fóðurið til hesa síðuna',
'tooltip-feed-atom' => 'Atom-fóðurið til hesa síðuna',
'tooltip-t-contributions' => 'Skoða yvirlit yvir íkast hjá hesum brúkara',
'tooltip-t-emailuser' => 'Send teldupost til henda brúkaran',
'tooltip-t-upload' => 'Legg myndir ella miðlafílur upp',
'tooltip-t-specialpages' => 'Yvirlit yvir allar serliga síður',
'tooltip-t-print' => 'Printvinarlig útgáva av hesi síðu',
'tooltip-t-permalink' => 'Varandi ávísing til hesa útgávuna av hesi síðu',
'tooltip-ca-nstab-main' => 'Skoða innihaldssíðuna',
'tooltip-ca-nstab-user' => 'Skoða brúkarasíðuna',
'tooltip-ca-nstab-media' => 'Skoða miðlasíðuna',
'tooltip-ca-nstab-special' => 'Hetta er ein serlig síða. Tú kanst ikki broyta síðuna sjálv/ur.',
'tooltip-ca-nstab-project' => 'Skoða verkætlanarsíðuna',
'tooltip-ca-nstab-image' => 'Skoða myndasíðuna',
'tooltip-ca-nstab-mediawiki' => 'Skoða kervisamboðini',
'tooltip-ca-nstab-template' => 'Brúka formin',
'tooltip-ca-nstab-help' => 'Skoða hjálparsíðuna',
'tooltip-ca-nstab-category' => 'Vís bólkasíðuna',
'tooltip-minoredit' => 'Merk hetta sum eina lítil rætting',
'tooltip-save' => 'Goym broytingar mínar',
'tooltip-preview' => 'Nýt forskoðan fyri at síggja tínar broytingar, vinarliga nýt hetta áðrenn tú goymir!',
'tooltip-diff' => 'Vís hvørjar broytingar tú hevur gjørt í tekstinum',
'tooltip-compareselectedversions' => 'Sí munin millum tær báðar valdu versjónirnar av hesi síðu',
'tooltip-watch' => 'Set hesa síðu á tín vaktarlista',
'tooltip-watchlistedit-normal-submit' => 'Tak burtur heiti',
'tooltip-watchlistedit-raw-submit' => 'Dagfør eftirlitslista',
'tooltip-recreate' => 'Endurstovna síðuna sjálvt um hon er blivin slettað',
'tooltip-upload' => 'Byrja upload',
'tooltip-rollback' => '"Rulla aftur" tekur burtur rætting(ar) hjá tí seinasta íkastgevaranum til hesa síðuna við einum klikki',
'tooltip-undo' => '"Angra" tekur burtur hesa rættingina og letur upp rættingarsíðuna við forskoðan. Tað loyvir at tú skrivar eina orsøk í samandráttin.',
'tooltip-preferences-save' => 'Goym innstillingar',
'tooltip-summary' => 'Skriva stuttan samandrátt',

# Metadata
'notacceptable' => 'Wiki ambætarin kann ikki veita dáta í einum formati, sum tín viðskiftari (klientur) kann lesa.',

# Attribution
'anonymous' => 'Dulnevndir {{PLURAL:$1|brúkari|brúkarar}} í {{SITENAME}}',
'siteuser' => '{{SITENAME}}brúkari $1',
'anonuser' => '{{SITENAME}} dulnevndur brúkari $1',
'lastmodifiedatby' => 'Henda síðan varð seinast broytt kl. $2 hin $1 av $3.',
'othercontribs' => 'Grundað á arbeiði eftir $1.',
'others' => 'onnur',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|brúkari|brúkarar}} $1',
'anonusers' => '{{SITENAME}} dulnevndur/ir {{PLURAL:$2|brúkari|brúkarar}} $1',
'creditspage' => 'Høvundar á síðuni',
'nocredits' => 'Tað eru ongir upplýsingar tøkir um høvundar fyri hesa síðuna.',

# Spam protection
'spamprotectiontitle' => 'Spamm verjufiltur',
'spamprotectiontext' => 'Teksturin ið tú ynskti at goyma varð sperraður av spammfilturinum. Orsøkin til hetta er nokk ein leinkja til eina eksterna heimasíðu, sum er á svartalista.',
'spamprotectionmatch' => 'Hesin teksturin var tann ið útloystið okkara spammfiltur: $1',
'spambot_username' => 'MediaWiki spamm-reinsan',
'spam_blanking' => 'Allar versjónir innihildu leinkjur til $1, tømir síðuna',
'spam_deleting' => 'Allar versjónir innihalda leinkjur til $1, slettar',

# Info page
'pageinfo-title' => 'Kunning um "$1"',
'pageinfo-not-current' => 'Tað er tíverri ómøguligt at veita hesa kunning viðvíkjandi gomlum útgávum.',
'pageinfo-header-basic' => 'Grundleggjandi kunning',
'pageinfo-header-edits' => 'Rættingarsøga',
'pageinfo-header-restrictions' => 'Verja av síðu',
'pageinfo-header-properties' => 'Síðueginleikar',
'pageinfo-display-title' => 'Vís heitið',
'pageinfo-default-sort' => 'Standard sorteringslykil',
'pageinfo-length' => 'Síðulongd (í bytes)',
'pageinfo-article-id' => 'Síðu ID',
'pageinfo-language' => 'Mál á síðuinnihaldinum',
'pageinfo-robot-policy' => 'Indeksering av robottum',
'pageinfo-robot-index' => 'Loyvt',
'pageinfo-robot-noindex' => 'Ikki loyvt',
'pageinfo-views' => 'Tal av skoðanum',
'pageinfo-watchers' => 'Tal av síðu eygleiðarum',
'pageinfo-few-watchers' => 'Færri enn $1 {{PLURAL:$1|eftirlitsbrúkari|eftirlitsbrúkarar}}',
'pageinfo-redirects-name' => 'Tal av víðaristillingum til hesa síðu',
'pageinfo-subpages-name' => 'Undirsíður til hesa síðu',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|snarvegur|snarvegir}}; $3 {{PLURAL:$3|ikki-snarvegur|ikki-snarvegir}})',
'pageinfo-firstuser' => 'Brúkarin, ið upprættaði síðuna',
'pageinfo-firsttime' => 'Dagfesting fyri upprættan av síðuni',
'pageinfo-lastuser' => 'Brúkarin, sum seinast hevur rættað',
'pageinfo-lasttime' => 'Dagfesting fyri seinastu rætting',
'pageinfo-edits' => 'Tal av rættingum í alt',
'pageinfo-authors' => 'Tal av ymiskum høvundum í alt',
'pageinfo-recent-edits' => 'Seinastu rættingar (seinastu $1)',
'pageinfo-recent-authors' => 'Tal av ymiskum høvundum, sum nýliga hava redigerað',
'pageinfo-magic-words' => '{{PLURAL:$1|Magiskt|Magisk}} orð ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Fjaldur bólkur|Fjaldir bólkar}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Innlimað fyrimynd|Innlimaðar fyrimyndir}} ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|Síða innlimað|Síður innlimaðar}} á ($1)',
'pageinfo-toolboxlink' => 'Kunning um síðuna',
'pageinfo-redirectsto' => 'Snarvegir til',
'pageinfo-redirectsto-info' => 'kunning',
'pageinfo-contentpage' => 'Telur við sum ein innihaldssíða',
'pageinfo-contentpage-yes' => 'Ja',
'pageinfo-protect-cascading' => 'Verjur eru niðurarvaðar higani',
'pageinfo-protect-cascading-yes' => 'Ja',
'pageinfo-protect-cascading-from' => 'Verjur eru niðurarvaðar frá',
'pageinfo-category-info' => 'Kunning um bólkin',
'pageinfo-category-pages' => 'Tal av síðum',
'pageinfo-category-subcats' => 'Tal av undirbólkum',
'pageinfo-category-files' => 'Tal av fílum',

# Skin names
'skinname-cologneblue' => 'Cologne-bláur',

# Patrolling
'markaspatrolleddiff' => 'Merk síðuna sum eftirhugda',
'markaspatrolledtext' => 'Merk hesa síðu sum eftirhugda',
'markedaspatrolled' => 'Merk sum eftirkannað',
'markedaspatrolledtext' => 'Valda versjónin frá [[:$1]] er nú markerað sum eftirhugd.',
'rcpatroldisabled' => 'Ansanin eftir nýkomnum broytingum er óvirkin',
'rcpatroldisabledtext' => 'Hentleikin við ansing eftir nýkomnum broytingum er óvirkin í løtuni.',
'markedaspatrollederror' => 'Tað ber ikki til at merkja síðuna sum eftirhugda',
'markedaspatrollederrortext' => 'Tú mást velja eina versjón fyri at merkja hana sum eftirhugda.',
'markedaspatrollederror-noautopatrol' => 'Tú hevur ikki loyvi til at merkja tína egnu broyting sum kannaða.',
'markedaspatrollednotify' => 'Henda broytingin til $1 er blivið merkt sum eftirkannað.',
'markedaspatrollederrornotify' => 'Tað miseydnaðist at merkja sum eftirkannað.',

# Patrol log
'patrol-log-page' => 'Eftirlitsloggur',
'patrol-log-header' => 'Hetta er ein loggur yvir patruljeraðum síðuversjónum.',
'log-show-hide-patrol' => '$1 patrulleringsloggur',

# Image deletion
'deletedrevision' => 'Slettaði gamla síðuversjón $1',
'filedeleteerror-short' => 'Feilur hendi við sletting av fílu: $1',

# Browsing diffs
'previousdiff' => '← Eldri broytingar',
'nextdiff' => 'Nýggjari broytingar →',

# Media information
'imagemaxsize' => "Stødd á mynd er avmarkað:<br />''(fyri frágreiðingar síður hjá fílum)''",
'thumbsize' => 'Smámyndastødd:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|síða|síður}}',
'file-info' => 'fílustødd: $1, MIME slag: $2',
'file-info-size' => '$1 × $2 pixel, stødd fílu: $3, MIME-slag: $4',
'file-info-size-pages' => '$1 × $2 pixels, fílustødd: $3, MIME slag: $4, $5 {{PLURAL:$5|síða|síður}}',
'file-nohires' => 'Ongin hægri upploysn varð funnin.',
'svg-long-desc' => 'SVG fíle, nominelt $1 × $2 pixel, fíle stødd: $3',
'svg-long-desc-animated' => 'Animerað SVG fíla, nominelt $1 × $2 pixels, fílustødd: $3',
'svg-long-error' => 'Ógyldug SVG fíla: $1',
'show-big-image' => 'Full upploysn',
'show-big-image-preview' => 'Stødd av hesi forskoðan: $1.',
'show-big-image-other' => '{{PLURAL:$2|Onnur upploysn|Aðrar upploysnir}}: $1.',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ramma|rammur}}',
'file-info-png-repeat' => 'spælt $1 {{PLURAL:$1|ferð|ferðir}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|ramma|rammur}}',

# Special:NewFiles
'newimages' => 'Nýggjar myndir',
'newimages-legend' => 'Filtur',
'newimages-label' => 'Fílunavn (ella ein partur av tí):',
'showhidebots' => '($1 bottar)',
'noimages' => 'Einki at síggja.',
'ilsubmit' => 'Leita',
'bydate' => 'eftir dato',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekund}}',
'minutes' => '{{PLURAL:$1|$1 minuttur|$1 minuttir}}',
'hours' => '{{PLURAL:$1|$1 tími|$1 tímar}}',
'days' => '{{PLURAL:$1|$1 dagur|$1 dagar}}',
'weeks' => '{{PLURAL:$1|$1 vika|$1 vikur}}',
'months' => '{{PLURAL:$1|$1 mánaður|$1 mánaðir}}',
'years' => '{{PLURAL:$1|$1 ár}}',
'ago' => '$1 síðan',
'just-now' => 'júst nú',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|tími|tímar}} síðan',
'minutes-ago' => '$1 {{PLURAL:$1|minuttur|minuttir}} síðan',
'seconds-ago' => '$1 {{PLURAL:$1|sekund|sekundir}} síðan',
'monday-at' => 'Mánadagin kl. $1',
'tuesday-at' => 'Týsdagin kl. $1',
'wednesday-at' => 'Mikudagin kl. $1',
'thursday-at' => 'Hósdagin kl. $1',
'friday-at' => 'Fríggjadagin kl. $1',
'saturday-at' => 'Leygardagin kl. $1',
'sunday-at' => 'Sunnudagin kl. $1',
'yesterday-at' => 'Í gjár kl. $1',

# Bad image list
'bad_image_list' => 'Støddin er soleiðis: 

Bert innihaldið av listum (linjur sum byrja við *) verða brúkt.
Fyrsta slóðin á linjuni má vera ein leinkja til eina óynskta mynd.
Fylgjandi slóðir á somu linju eru undantøk, tvs. síður har fílan kann fyrikoma innline.',

# Metadata
'metadata' => 'Metadáta',
'metadata-help' => 'Henda fíla inniheldur meiri kunning, sum oftast frá talgilta myndatólinum ella skannaranum, sum tú hevur brúkt til at skapa ella talgilda myndina. 
Um fílan er blivin broytt síðan upprunastøðuna, so kunnu nakrir upplýsingar hvørva.',
'metadata-expand' => 'Vís víðkaðar smálutir',
'metadata-collapse' => 'Fjal víðkaðar smálutir',
'metadata-fields' => 'Mynda metadáta teigar sum eru listaðir í hesum boðunum verða víst á myndasíðuni tá metadáta talvan er er klappað saman.
Onnur metadáta verða fjald sum standard.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Breidd',
'exif-imagelength' => 'Hædd',
'exif-bitspersample' => 'Bits per komponent',
'exif-model' => 'Slag av myndatóli',
'exif-artist' => 'Rithøvundur',
'exif-copyright' => 'Upphavsrætt haldari',
'exif-usercomment' => 'Viðmerkingar frá brúkarum',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-flash' => 'Blits',
'exif-jpegfilecomment' => 'JPEG-fíluviðmerking',
'exif-keywords' => 'Lyklaorð',
'exif-worldregioncreated' => 'Heimsregión har myndin varð tikin',
'exif-countrycreated' => 'Landið har myndin varð tikin',
'exif-countrycodecreated' => 'Kota fyri landið, sum myndin varð tikin í',
'exif-provinceorstatecreated' => 'Landslutur ella lutastatur, sum myndin varð tikin í',
'exif-citycreated' => 'Býurin sum myndin varð tikin í',
'exif-sublocationcreated' => 'Býarpartur av býnum, har myndin varð tikin',
'exif-countrydest' => 'Landið víst',
'exif-countrycodedest' => 'Landakota verður víst',
'exif-citydest' => 'Vísir bý',
'exif-sublocationdest' => 'Býarpartur vístur',
'exif-objectname' => 'Stutt heiti',
'exif-headline' => 'Yvirskrift',
'exif-source' => 'Kelda',
'exif-writer' => 'Høvundur',
'exif-languagecode' => 'Mál',
'exif-iimversion' => 'IIM-versjón',
'exif-iimcategory' => 'Bólkur',
'exif-iimsupplementalcategory' => 'Aðrir bólkar',
'exif-datetimeexpires' => 'Ikki brúka eftir',
'exif-datetimereleased' => 'Útgivið hin',
'exif-lens' => 'Linsa brúkt',
'exif-serialnumber' => 'Seriunummar á myndatóli',
'exif-cameraownername' => 'Eigari av myndatóli',
'exif-copyrighted' => 'Upphavsrættarstøða:',
'exif-copyrightowner' => 'Eigari av upphavsrættinum',
'exif-usageterms' => 'Brúkstreytir',
'exif-personinimage' => 'Avmyndaður persónur',
'exif-originalimageheight' => 'Hæddin á myndini, áðrenn hon varð skorin',
'exif-originalimagewidth' => 'Breiddin á myndini, áðrenn hon varð skorin',

# Exif attributes
'exif-compression-1' => 'Ikki komprimerað',

'exif-copyrighted-true' => 'Vard av upphavrætti',

'exif-unknowndate' => 'Ókendur dagur',

'exif-orientation-1' => 'Normalt',

'exif-subjectdistance-value' => '$1 metrar',

'exif-meteringmode-0' => 'Ókent',
'exif-meteringmode-1' => 'Miðal',

'exif-lightsource-1' => 'Dagsljós',
'exif-lightsource-9' => 'Gott veður',
'exif-lightsource-10' => 'Skýggjað veður',
'exif-lightsource-11' => 'Skuggi',

'exif-scenecapturetype-1' => 'Landsskap',
'exif-scenecapturetype-2' => 'Portrett',

'exif-contrast-0' => 'Vanligt',
'exif-contrast-1' => 'Bleytt',
'exif-contrast-2' => 'Hart',

'exif-saturation-0' => 'Vanligt',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metur|metrar}} yvir havið',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metur|metrar}} undir havinum',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometrar pr. tíma',
'exif-gpsspeed-m' => 'Míl pr. tíma',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometrar',
'exif-gpsdestdistance-m' => 'Míl',
'exif-gpsdestdistance-n' => 'Sjómíl',

'exif-gpsdop-excellent' => 'Einastandandi ($1)',
'exif-gpsdop-good' => 'Gott ($1)',
'exif-gpsdop-fair' => 'Hampuligt ($1)',
'exif-gpsdop-poor' => 'Vánaligt ($1)',

'exif-objectcycle-a' => 'Bert um morgunin',
'exif-objectcycle-p' => 'Bert um kvøldið',
'exif-objectcycle-b' => 'Bæði morgun og kvøld',

'exif-iimcategory-edu' => 'Útbúgving',
'exif-iimcategory-evn' => 'Umhvørvi',
'exif-iimcategory-hth' => 'Heilsa',
'exif-iimcategory-lif' => 'Lívsstílur og frítíð',
'exif-iimcategory-pol' => 'Politikkur',
'exif-iimcategory-rel' => 'Átrúnaður og trúgv',
'exif-iimcategory-sci' => 'Vísund og tøkni',
'exif-iimcategory-soi' => 'Sosialmál',
'exif-iimcategory-spo' => 'Ítróttur',
'exif-iimcategory-wea' => 'Veðrið',

'exif-urgency-normal' => 'Vanligt ($1)',

# External editor support
'edit-externally' => 'Rætta hesa fílu við eksternari applikatión',
'edit-externally-help' => '(Sí [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] fyri meira kunning)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alt',
'namespacesall' => 'alt',
'monthsall' => 'allir',

# Email address confirmation
'confirmemail' => 'Vátta t-post adressu',
'confirmemail_send' => 'Send eina váttanarkotu',
'confirmemail_sent' => 'Játtanar t-postur sendur.',
'confirmemail_oncreate' => 'Ein staðfesingar kota er send til tína T-post adressu.
Tað er ikki neyðugt at hava hesa kodu fyri at rita inn, men tú mást veita hana áðrenn
tú kanst nýta nakran T-post-grundaðan hentleika í hesi wiki.',
'confirmemail_loggedin' => 'Tín t-post adressa er nú váttað.',
'confirmemail_subject' => '{{SITENAME}} váttan av T-post adressu',
'confirmemail_body' => 'Onkur, óiva tú frá IP adressu $1, hevur stovnað eina kontu "$2" við hesi t-post adressuni á {{SITENAME}}.

Fyri at vátta at hendan kontu veruliga hoyrur til tín og fyri at aktivera t-post funktiónir á {{SITENAME}}, so skalt
tú trýsta á fylgjandi slóð í tínum kagara:

$3

Um tað *ikki* var tú sum stovnaði kontuna, fylg so hesi slóðini fyri at avlýsa t-post váttanina: 

$5

Hendan váttanarkoda fer úr gildi tann $4.',

# action=purge
'confirm_purge_button' => 'Í lagi',

# action=watch/unwatch
'confirm-watch-button' => 'Í lagi',
'confirm-watch-top' => 'Legg hesa síðuna til tín eftirlitslista?',
'confirm-unwatch-button' => 'Í lagi',
'confirm-unwatch-top' => 'Taka hesa síðuna burtur frá tínum eftirlitslista?',

# Multipage image navigation
'imgmultipageprev' => '← fyrrverandi síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo' => 'Far!',
'imgmultigoto' => 'Far til síðu $1',

# Table pager
'ascending_abbrev' => 'upp',
'descending_abbrev' => 'nið',
'table_pager_next' => 'Næsta síða',
'table_pager_prev' => 'Fyrrverandi síða',
'table_pager_first' => 'Fyrsta síða',
'table_pager_last' => 'Seinasta síða',
'table_pager_limit' => 'Vís $1 lutir á hvørjari síðu',
'table_pager_limit_label' => 'Lutir á hvørjari síðu:',
'table_pager_limit_submit' => 'Far',
'table_pager_empty' => 'Ongi úrslit',

# Auto-summaries
'autosumm-blank' => 'Slettaði alt innihald á síðuni',
'autosumm-replace' => 'Innihaldið á síðuni bleiv skift út við "$1"',
'autoredircomment' => 'Víðaristillaði síðuna til [[$1]]',
'autosumm-new' => 'Stovnaði síðu við "$1"',

# Live preview
'livepreview-loading' => 'Innlesur...',

# Watchlist editor
'watchlistedit-normal-title' => 'Rætta eftirlit',
'watchlistedit-raw-title' => 'Rætta rátt eftirlit',
'watchlistedit-raw-legend' => 'Rætta rátt eftirlit',
'watchlistedit-raw-titles' => 'Heiti:',
'watchlistedit-raw-submit' => 'Dagfør eftirlitslistan',
'watchlistedit-raw-done' => 'Tín eftirlitslisti varð dagførdur.',

# Watchlist editing tools
'watchlisttools-view' => 'Vís viðkomandi broytingar',
'watchlisttools-edit' => 'Vís og rætta eftirlit',
'watchlisttools-raw' => 'Rætta rátt eftirlit',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Ávaring:\'\'\' Standard sorteringslykilin "$2" yvirtekur fyrrverandi standard sorteringslykilin "$1".',

# Special:Version
'version' => 'Útgáva',
'version-specialpages' => 'Serstakar síður',
'version-skins' => 'Útsjóndir',
'version-other' => 'Annað',
'version-hooks' => 'Krókur',
'version-hook-name' => 'Krókurnavn',
'version-version' => '(Útgáva $1)',
'version-license' => 'Lisensur',
'version-poweredby-credits' => "Henda wiki verður rikin av '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'onnur',
'version-poweredby-translators' => 'translatewiki.net týðarar',
'version-credits-summary' => 'Vit ynskja at takka fylgjandi persónum fyri teirra íkast til [[Special:Version|MediaWiki]].',
'version-software-version' => 'Útgáva',

# Special:Redirect
'redirect-submit' => 'Far',
'redirect-value' => 'Virði:',
'redirect-user' => 'Brúkara ID',
'redirect-revision' => 'Síðuversjón',
'redirect-file' => 'Fílunavn',
'redirect-not-exists' => 'Virði ikki funnið',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Fílunavn:',
'fileduplicatesearch-submit' => 'Leita',
'fileduplicatesearch-info' => '$1 × $2 pixel<br />Fílustødd: $3<br />MIME slag: $4',

# Special:SpecialPages
'specialpages' => 'Serligar síður',
'specialpages-group-other' => 'Aðrar serstakar síður',
'specialpages-group-login' => 'Rita inn / stovna konto',
'specialpages-group-pages' => 'Síðulistar',
'specialpages-group-pagetools' => 'Síðutól',
'specialpages-group-wiki' => 'Dáta og tól',
'specialpages-group-redirects' => 'Víðaristillar serstakar síður',
'specialpages-group-spam' => 'Spamm-tól',

# Special:BlankPage
'blankpage' => 'Tóm síða',
'intentionallyblankpage' => 'Henda síðan er tóm við vilja.',

# External image whitelist
'external_image_whitelist' => "↓  #Lat hesa linjuna vera júst sum hon er<pre>
#Skriva partar av vanligum orðingum (bert partin sum er ímillum //) niðanfyri
#Hesar verða samanbornar við URL'ar á eksternum (hotlinkaðum) myndum
#Tey sum passa saman verða víst sum myndir, í øðrum lagi verður bert ein slóð til myndina víst
#Linjur sum byrja við # verða viðfarin sum viðmerkingar
#Hetta er ikki følsamt fyri stórir og lítlir bókstavir

#Skriva allar vanligar málberingar omanfyri hesa linju. Lat hesa linjuna verða júst sum hon er</pre>",

# Special:Tags
'tag-filter' => '[[Special:Tags|Tag]] filtur:',
'tag-filter-submit' => 'Filtur',
'tags-title' => 'Lyklaorð',
'tags-edit' => 'rætta',
'tags-hitcount' => '$1 {{PLURAL:$1|broyting|broytingar}}',

# Special:ComparePages
'compare-page1' => 'Síða 1',
'compare-page2' => 'Síða 2',
'compare-rev1' => 'Versjón 1',
'compare-rev2' => 'Versjón 2',
'compare-submit' => 'Samanber',

# Database error messages
'dberr-header' => 'Henda wikiin hevur ein trupulleika',

# New logging system
'rightsnone' => '(ongin)',

# Search suggestions
'searchsuggest-search' => 'Leita',

# API errors
'api-error-empty-file' => 'Fílan sum tú sendi inn var tóm.',
'api-error-file-too-large' => 'Fílan sum tú sendi inn var óv stór.',
'api-error-http' => 'Internur feilur: Kann ikki fáa samband við servaran.',
'api-error-mustbeloggedin' => 'Tú mást vera innritað/ur fyri at tú kanst leggja fílur upp.',
'api-error-ok-but-empty' => 'Internur feilur: Onki svar frá servara.',
'api-error-unclassified' => 'Ein ókendur feilur hendi.',
'api-error-unknown-code' => 'Ókendur feilur: "$1"',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekund}}',
'duration-minutes' => '$1 {{PLURAL:$1|minuttur|minuttir}}',
'duration-hours' => '$1 {{PLURAL:$1|tími|tímar}}',
'duration-days' => '$1 {{PLURAL:$1|dagur|dagar}}',
'duration-weeks' => '$1 {{PLURAL:$1|vika|vikur}}',
'duration-years' => '$1 {{PLURAL:$1|ár}}',
'duration-decades' => '$1 {{PLURAL:$1|áratíggju}}',
'duration-centuries' => '$1 {{PLURAL:$1|øld|øldir}}',

);
