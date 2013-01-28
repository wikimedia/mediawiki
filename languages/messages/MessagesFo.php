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
'tog-underline' => 'Undurstrika ávísingar',
'tog-justify' => 'Stilla greinpart',
'tog-hideminor' => 'Goym minni broytingar í seinast broytt listanum',
'tog-hidepatrolled' => 'Goym eftirkannaðar rættingar í seinastu broytingum',
'tog-newpageshidepatrolled' => 'Goym eftirkannaðar síður frá listanum yvir nýggjar síður',
'tog-extendwatchlist' => 'Víðka eftirlitslistan fyri at vísa allar broytingar, ikki bara tær seinastu',
'tog-usenewrc' => 'Bólkað broytingar eftir síðu í seinastu rættingar og eftirlitslita (krevur JavaScript)',
'tog-numberheadings' => 'Sjálvtalmerking av yvirskrift',
'tog-showtoolbar' => 'Vís amboðslinju í rætting',
'tog-editondblclick' => 'Rætta síðu við at tvíklikkja (JavaScript)',
'tog-editsection' => 'Rætta greinpart við hjálp av [rætta]-ávísing',
'tog-editsectiononrightclick' => 'Rætta greinpart við at høgraklikkja á yvirskrift av greinparti (JavaScript)',
'tog-showtoc' => 'Vís innihaldsyvurlit (Til greinir við meira enn trimun greinpartum)',
'tog-rememberpassword' => 'Minst til loyniorð á hesum kaga (í mesta lagi $1 {{PLURAL:$1|dag|dagar}})',
'tog-watchcreations' => 'Legg síður, sum eg stovni og fílur sum eg leggi út, afturat mínum eftirlitslista',
'tog-watchdefault' => 'Legg síður sum eg rætti afturat mínum eftirlitslista',
'tog-watchmoves' => 'Legg síður og fílur, sum eg flyti, afturat mínum eftirlitslista',
'tog-watchdeletion' => 'Legg síður og fílur, sum eg striki, afturat mínum eftirlitslista',
'tog-minordefault' => 'Merk sum standard allar broytingar sum smærri',
'tog-previewontop' => 'Vís forhondsvísning áðren rættingarkassan',
'tog-previewonfirst' => 'Sýn forskoðan við fyrstu broyting',
'tog-nocache' => 'Deaktivera síðu "caching" í brovsaranum',
'tog-enotifwatchlistpages' => 'Send mær teldupost, tá ein síða ella fíla á mínum eftirlitslista er broytt',
'tog-enotifusertalkpages' => 'Send mær teldubræv, tá mín brúarasíða er broytt',
'tog-enotifminoredits' => 'Send mær eisini ein teldupost viðvíkjandi smærri broytingum á síðum og fílum',
'tog-enotifrevealaddr' => 'Avdúkað mína teldupost adressu í fráboðanar teldupostum',
'tog-shownumberswatching' => 'Vís tal av brúkarum sum fylgja við',
'tog-oldsig' => 'Verandi undirskrift:',
'tog-fancysig' => 'Viðgerð undirskriftina sum wikitekstur (uttan sjálvvirkandi leinkju)',
'tog-externaleditor' => 'Nýt útvortis ritil sum fyrimynd (bert fyri fólk við serkunnleika, tað er tørvur á serligum innstillingum á tínari teldu. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff' => 'Nýt útvortis diff sum fyrimynd (bert fyri serfrøðingar, tín telda tørvar serligar innstillingar. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-showjumplinks' => 'Ger "far til"-tilgongd virkna',
'tog-uselivepreview' => 'Nýt "live preview" (tørvar JavaScript) (á royndarstøði)',
'tog-forceeditsummary' => 'Gev mær boð, um eg ikki havi skrivað ein samandrátt um mína rætting',
'tog-watchlisthideown' => 'Fjal mínar rættingar frá eftirliti',
'tog-watchlisthidebots' => 'Fjal bot rættingar frá eftirliti',
'tog-watchlisthideminor' => 'Fjal minni rættingar frá eftirliti',
'tog-watchlisthideliu' => 'Goym broytingar sum eru gjørdar av brúkarum, sum eru loggaðir á, frá hyggjaralistanum',
'tog-watchlisthideanons' => 'Goym broytingar sum eru gjørdar av dulnevndum brúkarum frá hyggjaralistanum',
'tog-watchlisthidepatrolled' => 'Fjal eftirhugdar broytingar frá eftirlitslistanum',
'tog-ccmeonemails' => 'Send mær avrit av teldubrøvum, sum eg sendi til aðrir brúkarar',
'tog-diffonly' => 'Vís ikki innihaldið av síðuni undir diffs',
'tog-showhiddencats' => 'Vís goymdir bólkar',
'tog-norollbackdiff' => 'Síggj burtur frá diff eftir eina afturrulling',

'underline-always' => 'Altíð',
'underline-never' => 'Ongantíð',
'underline-default' => 'Kagarastandard',

# Font style option in Special:Preferences
'editfont-style' => 'Rættað økið typografi:',
'editfont-default' => 'Kagi (brovsari) standard',
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

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Bólkur|Bólkar}}',
'category_header' => 'Greinir í bólki "$1"',
'subcategories' => 'Undirbólkur',
'category-media-header' => 'Media í bólkur "$1"',
'category-empty' => "''Hesin bólkur inniheldur ongar greinir ella miðlar í løtuni.''",
'hidden-categories' => '{{PLURAL:$1|Hidden category|Fjaldir bólkar}}',
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
'mypage' => 'Mín síða',
'mytalk' => 'Kjak',
'anontalk' => 'Kjak til hesa ip-adressuna',
'navigation' => 'Navigatión',
'and' => '&#32;og',

# Cologne Blue skin
'qbfind' => 'Finn',
'qbbrowse' => 'Kaga',
'qbedit' => 'Rætta',
'qbpageoptions' => 'Henda síðan',
'qbpageinfo' => 'Samanhangur',
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
'vector-action-unprotect' => 'Broyt friðing',
'vector-simplesearch-preference' => 'Ger virkið betraði leiti uppskot (bert Vector útsjónd)',
'vector-view-create' => 'Stovna',
'vector-view-edit' => 'Rætta',
'vector-view-history' => 'Søga',
'vector-view-view' => 'Les',
'vector-view-viewsource' => 'Vís keldu',
'actions' => 'Gerningar',
'namespaces' => 'Navnarúm',
'variants' => 'Ymisk sløg',

'errorpagetitle' => 'Villa',
'returnto' => 'Vend aftur til $1.',
'tagline' => 'Frá {{SITENAME}}',
'help' => 'Hjálp',
'search' => 'Leita',
'searchbutton' => 'Leita',
'go' => 'Far til',
'searcharticle' => 'Far',
'history' => 'Síðusøga',
'history_short' => 'Søga',
'updatedmarker' => 'dagført síðan mína seinastu vitjan',
'printableversion' => 'Prentvinarlig útgáva',
'permalink' => 'Støðug slóð',
'print' => 'Prenta',
'view' => 'Les',
'edit' => 'Rætta',
'create' => 'Stovna',
'editthispage' => 'Rætta hesa síðuna',
'create-this-page' => 'Stovna hesa síðuna',
'delete' => 'Strika',
'deletethispage' => 'Strika hesa síðuna',
'undelete_short' => 'Ógilda striking av {{PLURAL:$1|broyting|$1 broytingar}}',
'viewdeleted_short' => 'Vís {{PLURAL:$1|eina strikaða broyting|$1 strikaðar broytingar}}',
'protect' => 'Friða',
'protect_change' => 'broyt',
'protectthispage' => 'Friða hesa síðuna',
'unprotect' => 'Broyt friðing',
'unprotectthispage' => 'Broyt verju av hesi síðu',
'newpage' => 'Nýggj síða',
'talkpage' => 'Kjakast um hesa síðuna',
'talkpagelinktext' => 'Kjak',
'specialpage' => 'Serlig síða',
'personaltools' => 'Persónlig amboð',
'postcomment' => 'Nýtt brot',
'articlepage' => 'Skoða innihaldssíðuna',
'talk' => 'Kjak',
'views' => 'Skoðanir',
'toolbox' => 'Amboð',
'userpage' => 'Vís brúkarisíðu',
'projectpage' => 'Vís verkætlanarsíðu',
'imagepage' => 'Vís síðu við fílum',
'mediawikipage' => 'Vís kervisboðsíðu',
'templatepage' => 'Vís fyrimyndsíðu',
'viewhelppage' => 'Vís hjálpsíðu',
'categorypage' => 'Vís bólkursíðu',
'viewtalkpage' => 'Vís kjak',
'otherlanguages' => 'Onnur mál',
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
'pool-timeout' => 'Støðgur bíða verður eftir lásinum',
'pool-queuefull' => 'Køin til "hylin" er full',
'pool-errorunknown' => 'Ókend villa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Um {{SITENAME}}',
'aboutpage' => 'Project:Um',
'copyright' => 'Innihald er tøkt undir $1.',
'copyrightpage' => '{{ns:project}}:Útgávurættur',
'currentevents' => 'Núverandi hendingar',
'currentevents-url' => 'Project:Núverandi hendingar',
'disclaimers' => 'Fyrivarni',
'disclaimerpage' => 'Project:Fyrivarni',
'edithelp' => 'Rættihjálp',
'edithelppage' => 'Help:Rættihjálp',
'helppage' => 'Help:Innihald',
'mainpage' => 'Forsíða',
'mainpage-description' => 'Forsíða',
'policy-url' => 'Project:Handfaring av persónligum upplýsingum',
'portal' => 'Forsíða fyri høvundar',
'portal-url' => 'Project:Forsíða fyri høvundar',
'privacy' => 'Handfaring av persónligum upplýsingum',
'privacypage' => 'Project:Handfaring av persónligum upplýsingum',

'badaccess' => 'Loyvisbrek',
'badaccess-group0' => 'Tú hevur ikki loyvi til at útføra hatta sum tú hevur biðið um.',
'badaccess-groups' => 'Tað sum tú hevur biðið um at sleppa at gera er avmarkað til brúrkarar í {{PLURAL:$2|bólkinum|einum av bólkunum}}: $1.',

'versionrequired' => 'Versjón $1 frá MediaWiki er kravd',
'versionrequiredtext' => 'Versjón $1 av MediaWiki er kravd fyri at brúka hesa síðuna.
Sí [[Special:Version|versjón síða]].',

'ok' => 'Í lagi',
'retrievedfrom' => 'Heinta frá "$1"',
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
'nstab-main' => 'Grein',
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
'dberrortext' => '↓ Tað er hend ein syntaks villa í fyrispurninginum til dátugrunnin.
Hetta kann merkja, at tað er feilur í ritbúnaðinum (software).
Seinasta royndin at spyrja dátugrunnin var:
<blockquote><tt>$1</tt></blockquote>
frá funktiónini "<tt>$2</tt>".
Dátugrunnurin sendi feilin aftur "<tt>$3: $4</tt>".',
'dberrortextcl' => '↓ Ein syntaks feilur hendi í fyrispurningi til dátugrunnin.
Seinasta royndin at leita í dátugrunninum var:
 "$1"
frá funktiónini "$2".
Dátugrunnurin sendi aftur feilmeldingina: "$3: $4"',
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
'protectedpagetext' => 'Hendan síða er læst fyri at steðga rættingum.',
'viewsourcetext' => 'Tú kanst síggja og avrita kelduna til hesa grein:',
'viewyourtext' => "Tú kanst síggja og avrita kelduna fyri '''tínar rættingar''' til hesa síðuna:",
'protectedinterface' => '↓ Henda síðan gevur markamóts tekst til ritbúnaðin (software), og er vard fyri at fyribyrgja misnýtslu.',
'editinginterface' => "↓ '''Ávaring:''' Tú rættar eina síðu sum verður brúkt til at geva markamóts tekst til ritbúnaðin (software).
Broytingar á hesi síðu fara at ávirka útsjóndina á brúkara markamótinum (interface) fyri aðrir brúkarar.
Fyri at gera týðingar verður tú vinarliga biðin um at umhugsa at brúka [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], sum er verkætlan fyri týðingum av MediaWiki.",
'sqlhidden' => '(SQL fyrispurningur fjaldur)',
'cascadeprotected' => 'Henda síðan er vard fyri rættingum, tí hon er í fylgjandi {{PLURAL:$1|síðu, sum er|síðum, sum eru}}
vardar við "arvaðari síðuverjing"
$2',
'namespaceprotected' => 'Tú hevur ikki loyvi til at rætta síður í $1 navnateiginum.',
'customcssprotected' => 'Tú hevur ikki loyvi til at rætta hesa CSS síðuna, tí hon inniheldur persónligar innstillingar hjá øðrum brúkara.',
'customjsprotected' => 'Tú hevur ikki loyvir til at rætta hesa JavaScript síðuna, tí hon inniheldur persónligar innstillingar hjá øðrum brúkara.',
'ns-specialprotected' => 'Serstakar síður kunnu ikki rættast.',
'titleprotected' => '[[User:$1|$1]] hevur vart hetta heitið frá skapan.
Givin orsøk er "\'\'$2\'\'".',
'exception-nologin' => 'Tú ert ikki loggað/ur inn',
'exception-nologin-text' => 'Henda síða ella tað tú ætlar at gera kremvur at tú ert innritað/ur á hesa wiki.',

# Virus scanner
'virus-badscanner' => "Konfiguratións villa: Ókendur virus skannari: ''$1''",
'virus-scanfailed' => '↓  skanning virkaði ikki (kota $1)',
'virus-unknownscanner' => 'ókent antivirus:',

# Login and logout pages
'logouttext' => "'''Tú hevur nú ritað út.'''
Tú kanst halda fram at brúka {{SITENAME}} sum dulnevndur, ella kanst tú [[Special:UserLogin|logga á aftur]] sum sami ella sum annar brúkari. 
Legg til merkis, at summar síður framvegis vera vístar, sum um tú enn vart loggaður á, til tú hevur reinsa tín brovsara fyri \"cache\".",
'welcomecreation' => '== Vælkomin, $1! ==

Tín konta er nú stovnað.
Gloym ikki at broyta tínar [[Special:Preferences|{{SITENAME}} innstillingar]].',
'yourname' => 'Títt brúkaranavn:',
'yourpassword' => 'Títt loyniorð:',
'yourpasswordagain' => 'Skriva loyniorð umaftur:',
'remembermypassword' => 'Minst til logg inn hjá mær á hesum kaganum (í mesta lagi í $1 {{PLURAL:$1|dag|dagar}})',
'securelogin-stick-https' => 'Varðveit sambandið við HTTPS eftir logg inn',
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
'nologin' => "Hevur tú ikki eina kontu? '''$1'''.",
'nologinlink' => 'Stovna eina kontu',
'createaccount' => 'Stovna nýggja kontu',
'gotaccount' => "Hevur tú longu eina kontu? '''$1'''.",
'gotaccountlink' => 'Rita inn',
'userlogin-resetlink' => 'Hevur tú gloymt tínar logg inn upplýsingar',
'createaccountmail' => 'eftur t-posti',
'createaccountreason' => 'Orsøk:',
'badretype' => 'Loyniorðið tú hevur skriva er ikki rætt.',
'userexists' => 'Brúkaranavnið sum tú valdi er longu í nýtslu.
Vinarliga vel eitt annað navn.',
'loginerror' => 'Innritanarbrek',
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
'throttled-mailpassword' => 'Ein teldupost við áminning um loyniorði er longu sendur fyri bert {{PLURAL:$1|tíma|$1 tímum}}.
Fyri at fyribyrja misnýtslu, verður bert ein teldupostur við áminning um loyniorði sendur fyri hvønn/hvørjir {{PLURAL:$1|tíma|$1 tímar}}.',
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
'accountcreatedtext' => 'Brúkarakontan hjá $1 er nú upprættað.',
'createaccount-title' => 'Upprætta brúkarakonto á {{SITENAME}}',
'createaccount-text' => 'Onkur hevur stovnað eina konto fyri tína teldupost adressu á {{SITENAME}} ($4) nevnd "$2", við loyniorðinum "$3".
Tú eigur at innrita og broyta loyniorðið nú.

Tú kanst síggja burtur frá hesum boðum, um henda kontan varð upprættað av misgáum.',
'usernamehasherror' => 'Brúkaranavn kann ikki innihalda teknið #',
'login-throttled' => 'Tú hevur roynt at rita inn ov nógvar ferðir nýliga.
Vinarliga bíða áðrenn tú roynir aftur.',
'login-abort-generic' => 'Tað miseydnaðist tær at rita inn - avbrotið',
'loginlanguagelabel' => 'Mál: $1',
'suspicious-userlogout' => 'Tín fyrispurningur um at útrita var noktaður, tí tað sær út til at hann varð sendur frá einum oyðiløgdum kaga ella caching proxy.',

# E-mail sending
'php-mail-error-unknown' => "Ókend villa í PHP'sa teldupost () funktión.",
'user-mail-no-addy' => 'Royndi at senda t-post uttan eina t-post adressu.',

# Change password dialog
'resetpass' => 'Broyt loyniorð',
'resetpass_announce' => 'Tú ritaði inn við einum fyribils loyniorði, sum tú hevur fingið við telduposti.
Fyri at gera innritanina lidna, mást tú velja tær eitt nýtt loyniorð her:',
'resetpass_header' => 'Broyt loyniorði á kontuni',
'oldpassword' => 'Gamalt loyniorð:',
'newpassword' => 'Nýtt loyniorð:',
'retypenew' => 'Skriva nýtt loyniorð umaftur:',
'resetpass_submit' => 'Vel loyniorð og rita inn',
'resetpass_success' => 'Tað hevur eydnast tær at broyta títt loyniorð!
Nú verður tú ritaður inn...',
'resetpass_forbidden' => 'Loyniorð kunnu ikki broytast',
'resetpass-no-info' => 'Tú mást vera loggaður á fyri at fáa beinleiðis atgongd til hesa síðu.',
'resetpass-submit-loggedin' => 'Broyt loyniorð',
'resetpass-submit-cancel' => 'Ógildað',
'resetpass-wrong-oldpass' => 'Ógyldug fyribils ella verandi loyniorð.
Møguliga hevur tú longu broytt títt loyniorð ella biðið um eitt nýtt fyribils loyniorð.',
'resetpass-temp-password' => 'Fyribils loyniorð',

# Special:PasswordReset
'passwordreset' => 'Nullstilla loyniorðið',
'passwordreset-text' => 'Útfyll hetta skjalið fyri at fáa eina áminning við t-posti við tínum konto upplýsingum.',
'passwordreset-legend' => 'Nulstilla loyniorðið',
'passwordreset-disabled' => 'Tað ber ikki til at nullstilla loyniorðið á hesi wiki.',
'passwordreset-pretext' => '{{PLURAL:$1||Skriva ein av upplýsingunum niðanfyri}}',
'passwordreset-username' => 'Brúkaranavn:',
'passwordreset-domain' => 'Umdømi (domain):',
'passwordreset-capture' => 'Sí tann endaliga t-postin?',
'passwordreset-capture-help' => 'Um tú setir kross við henda teigin, so verður t-posturin (við fyribils loyniorðinum) vístur fyri tær og verður harumframt sendur til brúkaran.',
'passwordreset-email' => 'T-post adressur:',
'passwordreset-emailtitle' => 'konto upplýsingar á {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Onkur (óiva tú, frá IP adressu $1) hevur biðið um eina áminning av tínum konto upplýsingum fyri {{SITENAME}} ($4). Fylgjandi brúkara {{PLURAL:$3|konta er|kontur eru}}
sett í samband við hesa t-post adressu:

$2

{{PLURAL:$3|Hetta fyribils loyniorðið|Hesi fyribils loyniorðini}} ganga út um {{PLURAL:$5|ein dag|$5 dagar}}.
Tú eigur at rita inn og velja eitt nýtt loyniorð nú. Um onkur annar hevur gjørt hesa 
umbønina, ella um tú ert komin í tankar um títt uppruna loyniorð, og tú ikki longur 
ynskir at broyta tað, so kanst tú síggja burtur frá hesum boðum og halda fram at brúka títt gamla loyniorð.',
'passwordreset-emailtext-user' => 'Brúkari $1 á {{SITENAME}} hevur biðið um eina áminning av tínum konto upplýsingum fyri {{SITENAME}}
($4). Fylgjandi brúkara {{PLURAL:$3|konta er|kontur eru}} settar í samband við hesa t-post adressuna:

$2

{{PLURAL:$3|Hetta fyribils loyniorðið|Hesi fyribils loyniorðini}} ganga út um {{PLURAL:$5|ein dag|$5 dagar}}.
Tú eigur at rita inn og velja eitt nýtt loyniorð nú. Um onkur annar hevur gjørt hesa 
umbønina, ella um tú ert komin í tankar um títt uppruna loyniorð, og tú ikki longur 
ynskir at broyta tað, so kanst tú síggja burtur frá hesum boðum og halda fram at brúka títt gamla loyniorð.',
'passwordreset-emailelement' => 'Brúkaranavn: $1
Fyribils loyniorð: $2',
'passwordreset-emailsent' => 'Ein áminningar teldupostur er blivin sendur.',
'passwordreset-emailsent-capture' => 'Ein áminningar teldupostur er blivin sendur, sum víst niðanfyri.',
'passwordreset-emailerror-capture' => 'Ein áminningar teldupostur var gjørdur, sum víst niðanfyri, men tað miseydnaðist at senda til brúkaran: $1',

# Special:ChangeEmail
'changeemail' => 'Broyt teldupost adressu',
'changeemail-header' => 'Broyt t-post adressuna hjá kontuni',
'changeemail-text' => 'Útfyll henda formularin fyri at broyta tína t-post adressu. Tú mást skriva títt loyniorð fyri at vátta hesa broyting.',
'changeemail-no-info' => 'Tú mást vera innritað/ur fyri at fáa beinleiðis atgongd til hesa síðu.',
'changeemail-oldemail' => 'Verandi t-post adressa:',
'changeemail-newemail' => 'Nýggj t-post adressa:',
'changeemail-none' => '(ongin)',
'changeemail-submit' => 'Broyt t-post',
'changeemail-cancel' => 'Ógilda',

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
Loyniorðið fyri hesa nýggju kontuna kann verða broytt á ''[[Special:ChangePassword|broyt loyniorð]]'' síðuni tá tú ritar inn.",
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
Tú kanst [[Special:Search/{{PAGENAME}}|leita eftir hesum síðu heiti]] á øðrum siðum, 
ella <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} síggja viðkomandi logglistar]</span>.',
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
'continue-editing' => 'Halt fram við at rætta',
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
'nocreatetitle' => 'Upprættan av síðu er avmarkað',
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
'edit-already-exists' => 'Tað var ikki møguligt at upprætta nýggja síðu.
Síðan er longu til.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Ávaring:''' Tað eru ov nógvar skabilónir á hesi síðu. 
Nakrar skabilónir vera ikki vístar.",
'post-expand-template-inclusion-category' => 'Síður sum innihalda ov nógvar skabilónir',
'post-expand-template-argument-warning' => "'''Ávaring:''' Henda síðan inniheldur í minsta lagi eitt skabilón parametur (template argument), sum fyllir meira enn loyvdu støddina. 
Hetta parametur er tí ikki tikið við.",
'post-expand-template-argument-category' => 'Síður har skabilón parametur (template arguments) ikki eru tikin við',

# "Undo" feature
'undo-success' => 'Rættingin kann takast burtur aftur.
Vinarliga kanna eftir samanberingina niðanfyri fyri at vátta, at hetta er tað sum tú ynskir at gera, goym síðan broytingarnar niðanfyri fyri at gjøgnumføra buturtøkuna av rættingini.',
'undo-failure' => 'Rættingin kundi ikki takast burtur orsakað av konfliktum við rættingum sum eru gjørdar eftir at tú fór í gongd at rætta.',
'undo-norev' => 'Rættingin kann ikki takast burtur, tí at hon er ikki til ella var strikað.',
'undo-summary' => 'Tak burtur versjón $1 hjá [[Special:Contributions/$2|$2]] ([[User talk:$2|kjak]])',

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
'histfirst' => 'Elsta',
'histlast' => 'Nýggjasta',
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
'revdelete-show-file-submit' => 'Ja',
'revdelete-legend' => 'Set avmarkinga fyri sjónligheit',
'revdelete-hide-text' => 'Goym burtur tekstin á hesi versjónini',
'revdelete-hide-image' => 'Fjal fílu innihald',
'revdelete-hide-name' => 'Fjal handling og mál',
'revdelete-hide-comment' => 'Fjal rættingar frágreiðing',
'revdelete-hide-user' => 'Fjal brúkaranavn/IP adressu hjá tí sum rættar',
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
'revdel-restore' => 'broyt sjónligheit',
'revdel-restore-deleted' => 'strikaðar rættingar',
'revdel-restore-visible' => 'sjónligar broytingar',
'pagehist' => 'Síðu søgan',
'deletedhist' => 'Strikingar søga',
'revdelete-hide-current' => 'Tað er hendur ein feilur tá luturin skuldi fjalast, luturin er dagfestur $2, kl. $1: Hetta er nýggjast versjónin.
Hon kann ikki fjalast.',
'revdelete-show-no-access' => 'Feilur tá hesin lutur dagfestur $1 klokkan $2 skuldi vísast:Hesin lutur er blivin markeraður sum "avmarkaður".
Tú hevur ikki atgongd til hann.',
'revdelete-otherreason' => 'Onnur orsøk',
'revdelete-reasonotherlist' => 'Onnur orsøk',
'revdelete-edit-reasonlist' => 'Rætta strikingar orsøkir',
'revdelete-offender' => 'Høvundurin av hesi endurskoðan:',

# History merging
'mergehistory-from' => 'Keldusíða:',
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
'searchhelp-url' => 'Help:Innihald',
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
'mwsuggest-disable' => 'Slá AJAX uppskot frá',
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

# Quickbar
'qbsettings' => 'Skundfjøl innstillingar',
'qbsettings-none' => 'Eingin',
'qbsettings-fixedleft' => 'Fast vinstru',
'qbsettings-fixedright' => 'Fast høgru',
'qbsettings-floatingleft' => 'Flótandi vinstru',
'qbsettings-floatingright' => 'Flótandi høgra',

# Preferences page
'preferences' => 'Innstillingar',
'mypreferences' => 'Innstillingar',
'prefs-edits' => 'Tal av rættingum:',
'prefsnologin' => 'Tú hevur ikki ritað inn',
'changepassword' => 'Broyt loyniorð',
'prefs-skin' => 'Hamur',
'skin-preview' => 'Forskoðan',
'prefs-datetime' => 'Dato og tíð',
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
'restoreprefs' => 'Endurset alt til standard innstillingar',
'prefs-editing' => 'Broyting av greinum',
'prefs-edit-boxsize' => 'Støddin á rættingar vindeyganum.',
'rows' => 'Røð:',
'columns' => 'Teigar:',
'searchresultshead' => 'Leita',
'resultsperpage' => 'Úrslit fyri hvørja síðu:',
'stub-threshold-disabled' => 'Er gjørt óvirki',
'recentchangesdays' => 'Dagar av vísa í seinastu broytingum:',
'recentchangesdays-max' => 'Í mesta lagi $1 {{PLURAL:$1|dagur|dagar}}',
'recentchangescount' => 'Tal av rættingum at vísa í standard:',
'savedprefs' => 'Tínar innstillingar eru goymdar.',
'timezonelegend' => 'Tíðar sona:',
'localtime' => 'Lokal tíð:',
'timezoneuseserverdefault' => 'Nýt wiki standard: ($1)',
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
'prefs-searchoptions' => 'Leiti møguleikar',
'prefs-namespaces' => 'Navnarúm',
'defaultns' => 'Um ikki, leita so í hesum navnateigum:',
'default' => 'standard',
'prefs-files' => 'Fílur',
'prefs-custom-css' => 'Tilpassað CSS',
'prefs-custom-js' => 'Tilpassað JavaScript',
'prefs-emailconfirm-label' => 'Vátta tína t-post adressu:',
'prefs-textboxsize' => 'Støddin á rættingar vindeyganum',
'youremail' => 'T-postur (sjálvboðið)*:',
'username' => 'Brúkaranavn:',
'uid' => 'Brúkara ID:',
'prefs-memberingroups' => 'Limir í {{PLURAL:$1|bólki|bólkum}}:',
'prefs-registration' => 'Skrásett tíðspunkt:',
'yourrealname' => 'Títt navn*:',
'yourlanguage' => 'Mál til brúkaraflatu:',
'yournick' => 'Nýggj undirskrift:',
'badsiglength' => 'Tín undirskrift er ov long. 
Hon má ikki hava meira enn $1 {{PLURAL:$1|tekn|tekn}}',
'yourgender' => 'Kyn:',
'gender-unknown' => 'Ikki upplýst',
'gender-male' => 'Maður',
'gender-female' => 'Kvinna',
'prefs-help-gender' => 'Tú avgerð sjálv/ur: Tað verður brúkt til at fáa kynsrættan tekst á nøkrum málum. Henda kunning verður almenn.',
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
'prefs-timeoffset' => 'Tíðarmunur',
'prefs-advancedediting' => 'Víðkaðir møguleikar',
'prefs-advancedrc' => 'Víðkaðir møguleikar',
'prefs-advancedrendering' => 'Víðkaðir møguleikar',
'prefs-advancedsearchoptions' => 'Víðkaðir møguleikar',
'prefs-advancedwatchlist' => 'Víðkaðir møguleikar',
'prefs-displayrc' => 'Vís møguleikar',
'prefs-displaysearchoptions' => 'Vís møguleikar',
'prefs-displaywatchlist' => 'Vís møguleikar',
'prefs-diffs' => 'Munir',

# User preference: e-mail validation using jQuery
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
'userrights-notallowed' => 'Tín konto hevur ikki loyvi til at seta ella taka burtur brúkara rættindi.',
'userrights-changeable-col' => 'Bólkar sum tú kanst broyta',
'userrights-unchangeable-col' => 'Bólkar, ið tú ikki kanst broyta',

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
'right-upload' => 'Legg upp fílur',
'right-reupload' => 'Yvirskriva verandi fílur',
'right-reupload-own' => 'Yvirskriva verandi fílur, sum tú hevur lagt upp',
'right-upload_by_url' => 'Legg fílur upp frá einum URL',
'right-autoconfirmed' => 'Rætta hálvt-vardar síður (semi-protected)',
'right-delete' => 'Strika síður',
'right-bigdelete' => 'Strika síður við nógvum versjónum',
'right-browsearchive' => 'Leita í strikaðum síðum',
'right-undelete' => 'Endurstovnað eina síðu',
'right-block' => 'Nokta øðrum brúkarum at rætta (blokka)',
'right-blockemail' => 'Nokta einum brúkara at senda teldupost',
'right-hideuser' => 'Sperra eitt brúkaranavn og goyma tað burtur fyri almenninginum',
'right-unblockself' => 'Taka burtur sperring av sær sjálvum',
'right-protect' => 'Broyt verjustøður og rætta vardar síður',
'right-editprotected' => 'Rætta vardar síður (uttan niðurarvaða verju)',
'right-editusercssjs' => 'Rætta CSS og JavaScript fílur hjá øðrum brúkarum',
'right-editusercss' => 'Rætta CSS fílur hjá øðrum brúkarum',
'right-edituserjs' => 'Rætta JavaScript fílur hjá øðrum brúkarum',
'right-import' => 'Innflyt síður frá øðrum wikium',
'right-patrol' => 'Marka broytingar hjá øðrum sum eftirkannaðar',
'right-unwatchedpages' => 'Sí lista við síðum sum ikki eru eftiransaðar',
'right-mergehistory' => 'Samanflætta søguna hjá hesum síðum',
'right-userrights' => 'Rætta øll brúkaraloyvir',
'right-userrights-interwiki' => 'Broyt brúkara rættindi hjá brúkarum á øðrum wikium',
'right-sendemail' => 'Send t-post til aðrir brúkarar',
'right-passwordreset' => 'Sí teldupostar til nullstilling av loyniorði',

# User rights log
'rightslog' => 'Rættindaloggur',
'rightslogtext' => 'Hetta er ein loggur sum vísir broytingar í brúkararættindum.',
'rightslogentry' => 'broyttar bólka limaskap fyri $1 frá $2 til $3',
'rightslogentry-autopromote' => 'varð sjálvvirkandi fluttur upp frá $2 til $3',
'rightsnone' => '(ongin)',

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
'action-upload_by_url' => 'legg henda fílin upp frá einari URL-adressu',
'action-delete' => 'Strika hesa síðu',
'action-deletedhistory' => 'hygg at strikingar søguni hjá hesi síðu',
'action-browsearchive' => 'leita eftir strikaðum síðum',
'action-undelete' => 'endurstovnað hesa síðu',
'action-block' => 'noktað hesum brúkara at rætta',
'action-protect' => 'broyt verjustøðuna hjá hesi síðu',
'action-unwatchedpages' => 'Síggj listan yvir síður sum ikki eru eftiransaðar',
'action-mergehistory' => 'samanflætta søguna hjá hesi síðu',
'action-userrights' => 'broyt øll brúkaraloyvi',
'action-userrights-interwiki' => 'broyt brúkararættindi hjá brúkarum á øðrum wikium',
'action-siteadmin' => 'stong ella læs upp dátugrunnin',
'action-sendemail' => 'send teldupostar',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|broyting|broytingar}}',
'recentchanges' => 'Seinastu broytingar',
'recentchanges-legend' => 'Nýligar broytingar møguleikar',
'recentchanges-summary' => 'Á hesi síðu kanst tú fylgja teimum nýggjastu broytingunum á hesi wiki.',
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
'hide' => 'Fjal',
'show' => 'Skoða',
'minoreditletter' => 's',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 ansar eftir {{PLURAL:$1|brúkara|brúkarum}}]',
'rc_categories_any' => 'Nakar',
'newsectionsummary' => '/* $1 */ nýtt innlegg',
'rc-enhanced-expand' => 'Vís smálutir (krevur JavaScript)',
'rc-enhanced-hide' => 'Goym smálutir',
'rc-old-title' => 'upprunaliga stovnað sum "$1"',

# Recent changes linked
'recentchangeslinked' => 'Viðkomandi broytingar',
'recentchangeslinked-feed' => 'Viðkomandi broytingar',
'recentchangeslinked-toolbox' => 'Viðkomandi broytingar',
'recentchangeslinked-title' => 'Broytingar sum vísa til "$1"',
'recentchangeslinked-noresult' => 'Ongar broytingar á slóðaðar síður í valda tíðarskeiði.',
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
'uploadnologintext' => 'Tú mást hava [[Special:UserLogin|ritað inn]]
fyri at leggja fílur upp.',
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
'filetype-missing' => 'Fílan hevur ongan enda (sum t.d. ".jpg").',
'empty-file' => 'Fílan sum tú sendi upp var tóm.',
'file-too-large' => 'Fílan sum tú sendi inn var ov stór.',
'filename-tooshort' => 'Fílunavnið er ov stutt.',
'filetype-banned' => 'Hetta slagi av fílum er bannað.',
'illegal-filename' => 'Hetta fílunavnið er ikki loyvt.',
'overwrite' => 'Tað er ikki loyvi til at yvirskriva eina verandi fílu.',
'unknown-error' => 'Ein ókend villa kom fyri.',
'tmp-create-error' => 'Kundi ikki upprætta fyribils fílu.',
'tmp-write-error' => 'Villa, meðan fyribils fíla skuldi skrivast.',
'large-file' => 'Tað verður viðmælt, at fílur ikki eru størri enn $1;
henda fílin er $2.',
'largefileserver' => 'Henda fílan er størri enn servarin er innstillaður til at loyva.',
'file-deleted-duplicate' => 'Ein fíla, sum er líka sum henda ([[:$1]]) er fyrr blivin strikað.
Tú eigur at kanna eftir strikingarsøguna hjá hesi fílu, áðrenn tú heldur áframm við at leggja hana út enn einaferð.',
'uploadwarning' => 'Ávaring',
'savefile' => 'Goym fílu',
'uploadedimage' => 'sent "[[$1]]" upp',
'sourcefilename' => 'Kelda fílunavn:',
'sourceurl' => 'Kelda URL:',
'destfilename' => 'Destinatión fílunavn:',
'upload-maxfilesize' => 'Fílu støddin má í mesta lagi vera: $1',
'upload-description' => 'Frágreiðing av fílu',
'upload-options' => 'Upplótingar møguleikar',
'watchthisupload' => 'Halt eyga við hesi fílu',
'filewasdeleted' => 'Ein fíla við hesum heitinum hevur fyrr verið upplóta og er seinni blivin strikað.
Tú eigur at eftirkanna $1 áðrenn tú heldur á við at upplóta fíluna enn einaferð.',
'upload-success-subj' => 'Upplegging væleydnað',

'upload-file-error' => 'Innvortis brek',
'upload-unknown-size' => 'Ókend stødd',

# File backend
'backend-fail-notexists' => 'Fílan $1 er ikki til.',
'backend-fail-store' => 'Kundi ikki goyma fílu $1 á $2.',
'backend-fail-copy' => 'Kundi ikki avrita fílu $1 til $2.',
'backend-fail-move' => 'Kundi ikki flyta fílu $1 til $2.',
'backend-fail-opentemp' => 'Kundi ikki lata upp fyribils fílu.',
'backend-fail-writetemp' => 'Kundi ikki skriva til fyribils fílu.',
'backend-fail-closetemp' => 'Kundi ikki aftur fyribils fílu.',
'backend-fail-read' => 'Kundi ikki lesa fílu $1.',
'backend-fail-create' => 'Kundi ikki skriva fílu $1.',

# Lock manager
'lockmanager-notlocked' => 'Kundi ikki lata upp "$1"; hon er ikki stongd.',
'lockmanager-fail-closelock' => 'Kundi ikki lata aftur lás fílu fyri "$1".',
'lockmanager-fail-deletelock' => 'Kundi ikki sletta lás fílu fyri "$1".',
'lockmanager-fail-acquirelock' => 'Kundi ikki fáa lás til "$1".',
'lockmanager-fail-openlock' => 'Kundi ikki læsa upp fíluna til: "$1".',
'lockmanager-fail-releaselock' => 'Kundi ikki læsa upp læsingina fyri: "$1".',

# img_auth script messages
'img-auth-accessdenied' => 'Atgongd noktað',
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
'http-host-unreachable' => 'Internetadressan er ikki atkomulig.',
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

# File description page
'file-anchor-link' => 'Mynd',
'filehist' => 'Søga fílu',
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
'sharedupload-desc-here' => 'Henda fíla er frá $1 og kann verða brúka í øðrum verkætlanum.
Frágreiðingin á [$2 fílu frágreiðingar síðu] er víst her niðanfyri.',
'shared-repo-from' => 'frá $1',

# File deletion
'filedelete' => 'Strika $1',
'filedelete-comment' => 'Orsøk:',
'filedelete-submit' => 'Strika',
'filedelete-success' => "'''$1''' er blivin strikað.",
'filedelete-success-old' => "Versjónin av '''[[Media:$1|$1]]''' frá kl. $3, hin $2 er blivið strikað.",
'filedelete-nofile' => "'''$1''' er ikki til.",

# MIME search
'mimesearch' => 'MIME-leit',

# List redirects
'listredirects' => 'Sýn ávísingar',

# Unused templates
'unusedtemplates' => 'Óbrúktar fyrimyndir',
'unusedtemplateswlh' => 'aðrar slóðir',

# Random page
'randompage' => 'Tilvildarlig síða',

# Random redirect
'randomredirect' => 'Tilvildarlig ávísingarsíða',

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
'statistics-edits-average' => 'Miðal rættingar pr. síðu',
'statistics-users' => 'Skrásettir [[Special:ListUsers|brúkarir]]',
'statistics-users-active' => 'Virknir brúkarir',
'statistics-mostpopular' => 'Mest sæddu síður',

'disambiguations' => 'Síður sum vísa til síður við fleirfaldum týdningi',
'disambiguationspage' => 'Template:fleiri týdningar',

'doubleredirects' => 'Tvífaldað ávísing',
'doubleredirectstext' => 'Henda síða gevur yvirlit yvir síður, sum vísa víðari til aðrar víðaristillaðar síður.
Hvør linja inniheldur leinkjur til ta fyrstu og næstu víðaristillingina, eins væl og málið fyri tað næstu víðaristillingina, sum vanliga er tann "veruliga" endamáls síðan, sum tann fyrsta víðaristillingin átti at peika móti.
<del>Útkrossaðir</del> postar eru loystir.',
'double-redirect-fixed-move' => '[[$1]] er blivin flutt.
Víðaristilling verður nú gjørd til [[$2]].',

'brokenredirects' => 'Brotnar ávísingar',
'brokenredirectstext' => 'Hesar víðaristillingar slóða til síður, ið ikki eru til:',
'brokenredirects-edit' => 'rætta',
'brokenredirects-delete' => 'strika',

'withoutinterwiki' => 'Síður uttan málslóðir',
'withoutinterwiki-summary' => 'Fylgjandi síður slóða ikki til útgávur á øðrum málum:',
'withoutinterwiki-submit' => 'Skoða',

'fewestrevisions' => 'Greinir við minstum útgávum',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|být|být}}',
'ncategories' => '$1 {{PLURAL:$1|bólkur|bólkar}}',
'nlinks' => '$1 {{PLURAL:$1|slóð|slóðir}}',
'nmembers' => '$1 {{PLURAL:$1|limur|limir}}',
'nviews' => '$1 {{PLURAL:$1|skoðan|skoðanir}}',
'lonelypages' => 'Foreldraleysar síður',
'uncategorizedpages' => 'Óbólkaðar síður',
'uncategorizedcategories' => 'Óbólkaðir bólkar',
'uncategorizedimages' => 'Fílir sum ikki eru bólkaðar',
'uncategorizedtemplates' => 'Óbólkaðar fyrimyndir',
'unusedcategories' => 'Óbrúktir bólkar',
'unusedimages' => 'Óbrúktar fílur',
'popularpages' => 'Umtóktar síður',
'wantedcategories' => 'Ynsktir bólkar',
'wantedpages' => 'Ynsktar síður',
'wantedfiles' => 'Ynsktar fílir',
'wantedtemplates' => 'Ynsktar fyrimyndir',
'mostlinked' => 'Síður við flest ávísingum',
'mostlinkedcategories' => 'Bólkar við flestum ávísandi slóðum',
'mostcategories' => 'Greinir við flest bólkum',
'mostrevisions' => 'Greinir við flestum útgávum',
'prefixindex' => 'Allar síður við forskoyti (prefiks)',
'shortpages' => 'Stuttar síður',
'longpages' => 'Langar síður',
'deadendpages' => 'Gøtubotnssíður',
'protectedpages' => 'Friðaðar síður',
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
'notargettitle' => 'Onki mál',
'pager-newer-n' => '{{PLURAL:$1|nýggjari 1|nýggjari $1}}',
'pager-older-n' => '{{PLURAL:$1|eldri 1|eldri $1}}',

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

# Special:AllPages
'allpages' => 'Allar síður',
'alphaindexline' => '$1 til $2',
'nextpage' => 'Næsta síða ($1)',
'prevpage' => 'Fyrrverandi síða ($1)',
'allarticles' => 'Allar greinir',
'allinnamespace' => 'Allar síður ($1 navnarúm)',
'allpagesprev' => 'Undanfarnu',
'allpagesnext' => 'Næstu',
'allpagessubmit' => 'Far',

# Special:Categories
'categories' => 'Bólkar',
'categoriespagetext' => 'Fylgjandi {{PLURAL:$1|bólkur inniheldur|bólkar innihalda}} síður ella miðlar (media).
[[Special:UnusedCategories|Ikki brúktir bólkar]] eru ikki vístar her.
Sí eisini [[Special:WantedCategories|ynsktir bólkar]].',

# Special:DeletedContributions
'deletedcontributions' => 'Slettaði brúkaraíkøst',
'deletedcontributions-title' => 'Slettaði brúkaraíkøst',
'sp-deletedcontributions-contribs' => 'íkøst',

# Special:LinkSearch
'linksearch-ns' => 'Navnarúm:',
'linksearch-ok' => 'Leita',
'linksearch-line' => '$1 slóðar frá $2',

# Special:ListUsers
'listusersfrom' => 'Vís brúkarar ið byrja við:',
'listusers-submit' => 'Sýna',
'listusers-noresult' => 'Ongin brúkari var funnin.',

# Special:ActiveUsers
'activeusers-hidebots' => 'Fjal bottar',
'activeusers-hidesysops' => 'Fjal umboðsstjórar (administratorar)',
'activeusers-noresult' => 'Ongir brúkarar funnir.',

# Special:Log/newusers
'newuserlogpage' => 'Brúkara logg',
'newuserlogpagetext' => 'Hetta er ein listi yvir seinast stovnaðu brúkarar.',

# Special:ListGroupRights
'listgrouprights' => 'Brúkara bólka rættindi',
'listgrouprights-summary' => 'Henda síða vísir ein lista av brúkarabólkum, sum eru útgreinaðir á hesi wiki og rættindini hjá teimum einstøku bólkunum.
Møguliga er [[{{MediaWiki:Listgrouprights-helppage}}|meira kunning]] um einstøk rættindi.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Givin rættindi</span>
* <span class="listgrouprights-revoked">Frátikin rættindi</span>',
'listgrouprights-group' => 'Bólkur',
'listgrouprights-rights' => 'Rættindi',
'listgrouprights-helppage' => 'Help:Bólka rættindi',
'listgrouprights-members' => '(limalisti)',
'listgrouprights-removegroup' => 'Tak burtur {{PLURAL:$2|bólk|bólkar}}: $1',
'listgrouprights-addgroup-all' => 'Legg til allir bólkar',
'listgrouprights-removegroup-all' => 'Tak burtur allir bólkar',
'listgrouprights-addgroup-self' => 'Legg {{PLURAL:$2|bólk|bólkar}} til tína egnu konto: $1',
'listgrouprights-removegroup-self' => 'Tak burtur {{PLURAL:$2|bólk|bólkar}} frá egnari konto: $1',
'listgrouprights-addgroup-self-all' => 'Legg allir bólkar til egna konto',
'listgrouprights-removegroup-self-all' => 'Tak burtur allir bólkar frá egnari konto',

# E-mail user
'mailnologin' => 'Ongin móttakara bústaður',
'mailnologintext' => 'Tú mást hava [[Special:UserLogin|ritað inn]]
og hava virkandi teldupostadressu í [[Special:Preferences|innstillingum]] tínum
fyri at senda teldupost til aðrar brúkarar.',
'emailuser' => 'Send t-post til brúkara',
'emailpage' => 'Send t-post til brúkara',
'emailpagetext' => 'Tú kanst brúka skjalið niðanfyri til at senda ein teldupost til henda brúkaran.
Teldupost adressan sum tú skrivaði í [[Special:Preferences|tíni brúkara ynskir]] kemur síðan fram sum "Frá" adressan í teldupostinum, soleiðis at móttakarin kann svara beinleiðis til tín.',
'defemailsubject' => '{{SITENAME}} t-postur frá brúkara $1',
'usermaildisabled' => 'Brúkara t-postur er óvirkin',
'usermaildisabledtext' => 'Tú kanst ikki senda teldupost til aðrir brúkarar á hesi wiki',
'noemailtitle' => 'Ongin t-post adressa',
'noemailtext' => 'Hesin brúkarin hevur ikki upplýst eina gylduga t-post-adressu.',
'nowikiemailtitle' => 'Ongin t-postur er loyvdur',
'emailusername' => 'Brúkaranavn:',
'emailfrom' => 'Frá:',
'emailto' => 'Til:',
'emailsubject' => 'Evni:',
'emailmessage' => 'Boð:',
'emailsend' => 'Send',
'emailccme' => 'Send mær avrit av mínum boðum.',
'emailccsubject' => 'Avrit av tínum boðum til $1: $2',
'emailsent' => 'T-postur sendur',
'emailsenttext' => 'Títt t-post boð er sent.',

# Watchlist
'watchlist' => 'Mítt eftirlit',
'mywatchlist' => 'Mítt eftirlit',
'watchlistfor2' => 'Fyri $1 $2',
'nowatchlist' => 'Tú hevur ongar lutir í eftirlitinum.',
'watchnologin' => 'Tú hevur ikki ritað inn',
'addedwatchtext' => "Síðan \"<nowiki>\$1</nowiki>\" er løgd undir [[Special:Watchlist|eftirlit]] hjá tær.
Framtíðar broytingar á hesi síðu og tilknýttu kjaksíðuni verða at síggja her.
Tá sæst síðan sum '''feit skrift''' í [[Special:RecentChanges|broytingaryvirlitinum]] fyri at gera hana lættari at síggja.

Vilt tú flyta síðuna undan tínum eftirliti, kanst tú trýsta á \"Strika eftirlit\" á síðuni.",
'removedwatchtext' => 'Síðan "[[:$1]]" er strikað úr [[Special:Watchlist|tínum eftirliti]].',
'watch' => 'Eftirlit',
'watchthispage' => 'Hav eftirlit við hesi síðuni',
'unwatch' => 'strika eftirlit',
'notanarticle' => 'Ongin innihaldssíða',
'watchnochange' => 'Ongin grein í tínum eftirliti er rætta innanfyri hetta tíðarskeiði.',
'watchlist-details' => '{{PLURAL:$1|$1 síða|$1 síður}} á tínum vaktarlista, kjaksíður ikki íroknaðar.',
'watchmethod-list' => 'kannar síður undir eftirliti fyri feskar broytingar',
'watchlistcontains' => 'Títt eftirlit inniheldur {{PLURAL:$1|eina síðu|$1 síður}}.',
'wlnote' => "Niðanfyri {{PLURAL:$1|stendur seinasta broytingin|standa seinastu '''$1''' broytingarnar}} seinasta/u {{PLURAL:$2| tíman|'''$2''' tímarnar}} hin $3 kl. $4",
'wlshowlast' => 'Vís seinastu $1 tímar $2 dagar $3',
'watchlist-options' => 'Møguleikar í ansingarlistanum',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Eftirlitir...',
'unwatching' => 'Strikar eftirlit...',

'enotif_newpagetext' => 'Hetta er ein nýggj síða.',
'enotif_impersonal_salutation' => '{{SITENAME}}brúkari',
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
'rollbackfailed' => 'Afturrulling miseydnað',
'revertpage' => 'Tók burtur rættingar hjá [[Special:Contributions/$2|$2]] ([[User talk:$2|kjak]]) til seinastu versjón hjá [[User:$1|$1]]',
'revertpage-nouser' => 'Tók burtur rættingar hjá (brúkaranavn tikið vekk) til seinastu versjón hjá [[User:$1|$1]]',
'rollback-success' => 'Tók burtur rættingar hjá $1;
broytti tað aftur til seinastu versjón hjá $2.',

# Protect
'protectlogpage' => 'Friðingarbók',
'protectedarticle' => 'friðaði "[[$1]]"',
'unprotectedarticle' => 'strikaði friðing á "[[$1]]"',
'protect-title' => 'Friðar "$1"',
'prot_1movedto2' => '$1 flutt til $2',
'protect-legend' => 'Vátta friðing',
'protectcomment' => 'Orsøk:',
'protectexpiry' => 'Gongur út:',
'protect-default' => 'Loyv øllum brúkarum',
'protect-fallback' => 'Krevur "$1" loyvi',
'protect-level-autoconfirmed' => 'Sperra fyri nýggjum og ikki skrásettum brúkarum',
'protect-level-sysop' => 'Bert umboðsstjórar',
'protect-expiring' => 'gongur út $1 (UTC)',
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

# Undelete
'undelete' => 'Endurstovna strikaðar síður',
'undeletebtn' => 'Endurstovna',
'undeletelink' => 'síggj/endurstovna',
'undeleteviewlink' => 'Hygg',
'undeletereset' => 'Endurset',
'undeletecomment' => 'Orsøk:',
'undeletedfiles' => '{{PLURAL:$1|1 fíla endurstovna|$1 fílur endurstovnaðar}}',
'undelete-search-submit' => 'Leita',
'undelete-show-file-submit' => 'Ja',

# Namespace form on various pages
'namespace' => 'Navnarúm:',
'invert' => 'Umvend val',
'blanknamespace' => '(Greinir)',

# Contributions
'contributions' => 'Brúkaraíkast',
'contributions-title' => 'Brúkaraíkøst fyri $1',
'mycontris' => 'Íkøst',
'contribsub2' => 'Eftir $1 ($2)',
'uctop' => '(ovast)',
'month' => 'Frá mánaði (og áðrenn):',
'year' => 'Frá ár (og áðrenn):',

'sp-contributions-newbies' => 'Vís bert íkast frá nýggjum kontoum',
'sp-contributions-blocklog' => 'bannagerðabók',
'sp-contributions-uploads' => 'uploads',
'sp-contributions-logs' => 'gerðalistar (logglistar)',
'sp-contributions-talk' => 'kjak',
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
'block' => 'Sperra brúkara',
'unblock' => 'Tak sperring av brúkara burtur',
'blockip' => 'Banna brúkara',
'blockip-title' => 'Sperra brúkara',
'blockip-legend' => 'Sperra brúkara',
'ipadressorusername' => 'IP-adressa ella brúkaranavn:',
'ipbreason' => 'Orsøk:',
'ipbreasonotherlist' => 'Onnur orsøk',
'ipbsubmit' => 'Banna henda brúkaran',
'ipboptions' => '2 tímar:2 hours, 1 dagur:1 day, 3 dagar:3 days, 1 vika:1 week, 2 vikur:2 weeks, 1 mánaður:1 month, 3 mánaðir:3 months, 6 mánaðir:6 months, 1 ár:1 year, óendaligt:infinite',
'badipaddress' => 'Ógyldug IP-adressa',
'blockipsuccesssub' => 'Banning framd',
'ipb-unblock-addr' => 'Óbanna $1',
'ipusubmit' => 'Strika hesa blokaduna',
'unblocked' => '[[User:$1|$1]] er ikki blokkaður longur',
'ipblocklist' => 'Bannaðir brúkarar',
'ipblocklist-legend' => 'Finn ein sperraðan brúkara',
'blocklist-userblocks' => 'Fjal sperringar av kontum',
'blocklist-tempblocks' => 'Fjal fyribils sperringar',
'blocklist-addressblocks' => 'Fjal einkult IP sperringar',
'ipblocklist-submit' => 'Leita',
'expiringblock' => 'gongur út $1kl. $2',
'anononlyblock' => 'anon. bara',
'blocklink' => 'banna',
'unblocklink' => 'óbanna',
'change-blocklink' => 'broyt blokkering',
'contribslink' => 'íkøst',
'blocklogpage' => 'Bannagerðabók',
'blocklogentry' => 'sperring [[$1]]  sum varir til $2 $3',
'unblocklogentry' => 'óbannaði $1',
'block-log-flags-nocreate' => 'upprætting av brúkarakonto er sperrað',
'proxyblocksuccess' => 'Liðugt.',

# Developer tools
'lockdbtext' => 'At læsa dátugrunnin steðgar møguleikanum hjá øllum
brúkarum at broyta síður, broyta innstillingar sínar, broyta sínar eftirlitslistar og
onnur ting, ið krevja broytingar í dátugrunninum.
Vinarliga vátta, at hetta er tað, ið tú ætlar at gera, og at tú fert
at læsa dátugrunnin upp aftur tá ið viðgerðin er liðug.',
'locknoconfirm' => 'Tú krossaði ikki váttanarkassan.',
'lockdbsuccesstext' => 'Dátugrunnurin er læstur.
<br />Minst til at [[Special:UnlockDB|læsa upp]] aftur, tá ið viðgerðin er liðug.',

# Move page
'move-page' => 'Flyt $1',
'move-page-legend' => 'Flyt síðu',
'movepagetext' => "Við frymlinum niðanfyri kanst tú umnevna eina síðu og flyta alla hennara søgu við til nýggja navnið.
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
Ver vís/ur í at eftirkanna [[Special:DoubleRedirects|dupult]] ella [[Special:BrokenRedirects|brotnaðar umstillingar]].
Tú hevur ábyrgdina av at vissa teg um at leinkjur halda fram við at peika á har sum tað er meiningin at tær skulu fara.

Legg merki til at síðan '''ikki''' verður flutt, um ein síða longu er við nýggja navninum, uttan at hon er tóm og onga søgu hevur.
Hetta merkir at tú kanst umnevna eina síðu aftur hagani hon kom, um tú gjørdi eitt mistak. Tú kanst ikki skriva yvir eina verandi síðu.

'''ÁVARING!'''
Hetta kann vera ein ógvuslig og óvæntað flyting av einari vældámdari síðu.
Vinarliga tryggja tær, at tú skilur avleiðingarnar av hesum áðrenn tú heldur áfam.",
'movearticle' => 'Flyt síðu:',
'movenologin' => 'Hevur ikki ritað inn',
'newtitle' => 'Til nýtt heiti:',
'move-watch' => 'Hav eftirlit við hesi síðuni',
'movepagebtn' => 'Flyt síðu',
'pagemovedsub' => 'Flyting væleydnað',
'articleexists' => 'Ein síða finst longu við hasum navninum,
ella er navnið tú valdi ógyldugt.
Vinarliga vel eitt annað navn.',
'movedto' => 'flyt til',
'movetalk' => 'Flyt kjaksíðuna eisini, um hon er til.',
'movelogpage' => 'Flyt gerðabók',
'movereason' => 'Orsøk:',
'revertmove' => 'endurstovna',
'delete_and_move' => 'Strika og flyt',
'delete_and_move_text' => '==Striking krevst==

Grein við navninum "[[:$1]]" finst longu. Ynskir tú at strika hana til tess at skapa pláss til flytingina?',
'delete_and_move_confirm' => 'Ja, strika hesa síðuna',
'delete_and_move_reason' => 'Er strikað fyri at gera pláss til flyting frá "[[$1]]"',
'immobile-source-namespace' => 'Tað ber ikki til at flyta síðu í navnaøkinum "$1"',
'immobile-target-namespace' => 'Tað ber ikki til at flyta síður inn til navnaøkið "$1"',

# Export
'export' => 'Útflutningssíður',

# Namespace 8 related
'allmessages' => 'Øll kervisboð',
'allmessagesname' => 'Navn',
'allmessagesdefault' => 'Enskur tekstur',
'allmessagescurrent' => 'Verandi tekstur',
'allmessagestext' => 'Hetta er eitt yvirlit av tøkum kervisboðum í MediaWiki-navnarúmi.
Vinarliga vitja [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] og [//translatewiki.net translatewiki.net] um tú ynskir at geva títt íkast til ta generisku MediaWiki lokalisatiónina.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' er ikki stuðlað orsakað av at '''\$wgUseDatabaseMessages''' er sløkt.",
'allmessages-filter-legend' => 'Filtur',
'allmessages-language' => 'Mál:',

# Thumbnails
'thumbnail-more' => 'Víðka',
'filemissing' => 'Fíla vantar',
'thumbnail_error' => 'Feilur við upprættan av thumbnail (lítlari mynd): $1',

# Special:Import
'import' => 'Innflyt síður',
'import-interwiki-submit' => 'Innflyta',
'importfailed' => 'Innflutningur miseydnaður: $1',
'importsuccess' => 'Innflutningur er liðugur!',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Tín brúkarasíða',
'tooltip-pt-mytalk' => 'Tín kjaksíða',
'tooltip-pt-preferences' => 'Tínar innstillingar',
'tooltip-pt-watchlist' => 'Ein listi við síðum sum tú eftiransar fyri broytingum',
'tooltip-pt-mycontris' => 'Yvirlit yvir títt íkast',
'tooltip-pt-login' => 'Vit mæla til at tú ritar inn, men tað er ikki neyðugt.',
'tooltip-pt-anonlogin' => 'Vit mæla til at tú ritar inn, tað er tó ikki eitt krav',
'tooltip-pt-logout' => 'Rita út',
'tooltip-ca-talk' => 'Umrøða av innihaldssíðuni',
'tooltip-ca-edit' => 'Tú kanst broyta hesa síðuna. Vinarliga nýt forskoðanarknøttin áðrenn tú goymir.',
'tooltip-ca-addsection' => 'Byrja eitt nýtt brot',
'tooltip-ca-viewsource' => 'Henda síðan er friðað. Tú kanst síggja keldukotuna.',
'tooltip-ca-history' => 'Fyrrverandi útgávur av hesi síðu.',
'tooltip-ca-protect' => 'Friða hesa síðuna',
'tooltip-ca-delete' => 'Strika hesa síðuna',
'tooltip-ca-undelete' => 'Endurnýggja skrivingina á hesi síðu áðrenn hon varð strikað',
'tooltip-ca-move' => 'Flyt hesa síðuna',
'tooltip-ca-watch' => 'Legg hesa síðuna undir mítt eftirlit',
'tooltip-ca-unwatch' => 'Fá hesa síðuna úr mínum eftirliti',
'tooltip-search' => 'Leita í {{SITENAME}}',
'tooltip-search-go' => 'Far til síðu við júst hesum heiti, um hon er til',
'tooltip-search-fulltext' => 'Leita eftir síðum sum innihalda henda  tekstin',
'tooltip-p-logo' => 'Forsíða',
'tooltip-n-mainpage' => 'Vitja forsíðuna',
'tooltip-n-mainpage-description' => 'Vitja forsíðuna',
'tooltip-n-portal' => 'Um verkætlanina, hvat tú kanst gera, hvar tú finnur ymiskt',
'tooltip-n-currentevents' => 'Finn bakgrundsupplýsingar um aktuellar hendingar',
'tooltip-n-recentchanges' => 'Listi av teimum seinastu broytingunum í wikinum.',
'tooltip-n-randompage' => 'Far til tilvildarliga síðu',
'tooltip-n-help' => 'Staðurin at finna út.',
'tooltip-t-whatlinkshere' => 'Yvirlit yvir allar wikisíður, ið slóða higar',
'tooltip-t-recentchangeslinked' => 'Broytingar á síðum, ið slóða higar, í seinastuni',
'tooltip-feed-rss' => 'RSS-fóðurið til hesa síðuna',
'tooltip-feed-atom' => 'Atom-fóðurið til hesa síðuna',
'tooltip-t-contributions' => 'Skoða yvirlit yvir íkast hjá hesum brúkara',
'tooltip-t-emailuser' => 'Send teldupost til henda brúkaran',
'tooltip-t-upload' => 'Legg myndir ella miðlafílur upp',
'tooltip-t-specialpages' => 'Yvirlit yvir serliga síður',
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
'tooltip-ca-nstab-category' => 'Skoða bólkasíðuna',
'tooltip-minoredit' => 'Merk hetta sum eina lítil rætting',
'tooltip-save' => 'Goym broytingar mínar',
'tooltip-preview' => 'Nýt forskoðan fyri at síggja tínar broytingar, vinarliga nýt hetta áðrenn tú goymir!',
'tooltip-diff' => 'Vís hvørjar broytingar tú hevur gjørt í tekstinum',
'tooltip-compareselectedversions' => 'Sí munin millum tær báðar valdu versjónirnar av hesi síðu',
'tooltip-watch' => 'Set hesa síðu á tín vaktarlista',
'tooltip-rollback' => '"Rulla aftur" tekur burtur rætting(ar) hjá tí seinasta íkastgevaranum til hesa síðuna við einum klikki',
'tooltip-undo' => '"Angra" tekur burtur hesa rættingina og letur upp rættingarsíðuna við forskoðan. Tað loyvir at tú skrivar eina orsøk í samandráttin.',
'tooltip-summary' => 'Skriva stuttan samandrátt',

# Attribution
'anonymous' => 'Dulnevndir {{PLURAL:$1|brúkari|brúkarar}} í {{SITENAME}}',
'siteuser' => '{{SITENAME}}brúkari $1',
'anonuser' => '{{SITENAME}} dulnevndur brúkari $1',
'othercontribs' => 'Grundað á arbeiði eftir $1.',
'others' => 'onnur',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|brúkari|brúkarar}} $1',
'anonusers' => '{{SITENAME}} dulnevndur/ir {{PLURAL:$2|brúkari|brúkarar}} $1',

# Info page
'pageinfo-title' => 'Kunning um "$1"',
'pageinfo-header-edits' => 'Rættingar',
'pageinfo-views' => 'Tal av skoðanum',
'pageinfo-watchers' => 'Tal av fólkum sum hava eftirlit',
'pageinfo-edits' => 'Tal av rættingum',
'pageinfo-authors' => 'Tal av ymiskum høvundum',

# Skin names
'skinname-standard' => 'Standardur',
'skinname-nostalgia' => 'Nostalgiskur',
'skinname-cologneblue' => 'Cologne-bláur',

# Patrolling
'markaspatrolleddiff' => 'Merk síðuna sum eftirhugda',
'markaspatrolledtext' => 'Merk hesa síðu sum eftirhugda',
'markedaspatrolled' => 'Merk sum eftirkannað',
'markedaspatrolledtext' => 'Valda versjónin frá [[:$1]] er nú markerað sum eftirhugd.',
'rcpatroldisabled' => 'Ansanin eftir nýkomnum broytingum er óvirkin',
'rcpatroldisabledtext' => 'Hentleikin við ansing eftir nýkomnum broytingum er óvirkin í løtuni.',
'markedaspatrollederror' => 'Tað ber ikki til at merkja síðuna sum eftirhugda',

# Browsing diffs
'previousdiff' => '← Eldri broytingar',
'nextdiff' => 'Nýggjari broytingar →',

# Media information
'imagemaxsize' => "Stødd á mynd er avmarkað:<br />''(fyri frágreiðingar síður hjá fílum)''",
'thumbsize' => 'Smámyndastødd:',
'file-info-size' => '$1 × $2 pixel, stødd fílu: $3, MIME-slag: $4',
'file-nohires' => 'Ongin hægri upploysn varð funnin.',
'svg-long-desc' => 'SVG fíle, nominelt $1 × $2 pixel, fíle stødd: $3',
'show-big-image' => 'Full upploysn',

# Special:NewFiles
'newimages' => 'Nýggjar myndir',
'noimages' => 'Einki at síggja.',
'ilsubmit' => 'Leita',
'bydate' => 'eftir dato',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekund}}',
'minutes' => '{{PLURAL:$1|$1 minuttur|$1 minuttir}}',
'hours' => '{{PLURAL:$1|$1 tími|$1 tímar}}',
'days' => '{{PLURAL:$1|$1 dagur|$1 dagar}}',

# Bad image list
'bad_image_list' => 'Støddin er soleiðis: 

Bert innihaldið av listum (linjur sum byrja við *) verða brúkt.
Fyrsta slóðin á linjuni má vera ein leinkja til eina óynskta mynd.
Fylgjandi slóðir á somu linju eru undantøk, tvs. síður har fílan kann fyrikoma innline.',

# Metadata
'metadata' => 'Metadáta',
'metadata-help' => 'Henda fíla inniheldur meiri kunning, sum oftast frá talgilta myndatólinum ella skannaranum, sum tú hevur brúkt til at skapa ella talgilda myndina. 
Um fílan er blivin broytt síðan upprunastøðuna, so kunnu nakrir upplýsingar hvørva.',
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

# EXIF tags
'exif-artist' => 'Rithøvundur',
'exif-copyright' => 'Upphavsrætt haldari',
'exif-headline' => 'Yvirskrift',
'exif-iimcategory' => 'Bólkur',

# External editor support
'edit-externally' => 'Rætta hesa fílu við eksternari applikatión',
'edit-externally-help' => '(Sí [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] fyri meira kunning)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alt',
'namespacesall' => 'alt',
'monthsall' => 'allir',

# E-mail address confirmation
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

# Multipage image navigation
'imgmultipageprev' => '← fyrrverandi síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo' => 'Far!',

# Table pager
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
'autosumm-new' => 'Stovnaði síðu við "$1"',

# Watchlist editor
'watchlistedit-normal-title' => 'Rætta eftirlit',
'watchlistedit-raw-title' => 'Rætta rátt eftirlit',
'watchlistedit-raw-legend' => 'Rætta rátt eftirlit',

# Watchlist editing tools
'watchlisttools-view' => 'Vís viðkomandi broytingar',
'watchlisttools-edit' => 'Vís og rætta eftirlit',
'watchlisttools-raw' => 'Rætta rátt eftirlit',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Ávaring:\'\'\' Standard sorteringslykilin "$2" yvirtekur fyrrverandi standard sorteringslykilin "$1".',

# Special:Version
'version' => 'Útgáva',
'version-hooks' => 'Krókur',
'version-hook-name' => 'Krókurnavn',
'version-version' => '(Útgáva $1)',
'version-software-version' => 'Útgáva',

# Special:FilePath
'filepath-page' => 'Fíla:',

# Special:SpecialPages
'specialpages' => 'Serligar síður',

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
'tags-edit' => 'rætta',
'tags-hitcount' => '$1 {{PLURAL:$1|broyting|broytingar}}',

# Special:ComparePages
'compare-page1' => 'Síða 1',
'compare-page2' => 'Síða 2',

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
