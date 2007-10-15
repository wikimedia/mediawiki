<?php
/** Faroese (Føroyskt)
 *
 * @addtogroup Language
 * Translators:
 * @author Spacebirdy
 */

$skinNames = array(
	'standard'    => 'Standardur', 
	'nostalgia'   => 'Nostalgiskur', 
	'cologneblue' => 'Cologne-bláur'
);

$bookstoreList = array(
	'Bokasolan.fo' => 'http://www.bokasolan.fo/vleitari.asp?haattur=bok.alfa&Heiti=&Hovindur=&Forlag=&innbinding=Oell&bolkur=Allir&prisur=Allir&Aarstal=Oell&mal=Oell&status=Oell&ISBN=$1',
	'inherit' => true,
);

$namespaceNames = array(
	NS_MEDIA            => 'Miðil',
	NS_SPECIAL          => 'Serstakur',
	NS_MAIN             => '',
	NS_TALK             => 'Kjak',
	NS_USER             => 'Brúkari',
	NS_USER_TALK        => 'Brúkari_kjak',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_kjak',
	NS_IMAGE            => 'Mynd',
	NS_IMAGE_TALK       => 'Mynd_kjak',
	NS_MEDIAWIKI        => 'MidiaWiki',
	NS_MEDIAWIKI_TALK   => 'MidiaWiki_kjak',
	NS_TEMPLATE         => 'Fyrimynd',
	NS_TEMPLATE_TALK    => 'Fyrimynd_kjak',
	NS_HELP             => 'Hjálp',
	NS_HELP_TALK        => 'Hjálp_kjak',
	NS_CATEGORY         => 'Bólkur',
	NS_CATEGORY_TALK    => 'Bólkur_kjak'
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',
);

$linkTrail = '/^([áðíóúýæøa-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Undurstrika ávísingar',
'tog-highlightbroken'         => 'Brúka reyða ávísing til tómar síður',
'tog-justify'                 => 'Stilla greinpart',
'tog-hideminor'               => 'Goym minni broytingar í seinast broytt listanum',
'tog-extendwatchlist'         => 'Víðkað eftirlit',
'tog-usenewrc'                => 'víðka seinastu broytingar lista (ikki til alla kagarar)',
'tog-numberheadings'          => 'Sjálvtalmerking av yvirskrift',
'tog-showtoolbar'             => 'Vís amboðslinju í rætting',
'tog-editondblclick'          => 'Rætta síðu við at tvíklikkja (JavaScript)',
'tog-editsection'             => 'Rætta greinpart við hjálp av [rætta]-ávísing',
'tog-editsectiononrightclick' => 'Rætta greinpart við at høgraklikkja á yvirskrift av greinparti (JavaScript)',
'tog-showtoc'                 => 'Vís innihaldsyvurlit (Til greinir við meira enn trimun greinpartum)',
'tog-rememberpassword'        => 'Minst til loyniorð næstu ferð',
'tog-editwidth'               => 'Rættingarkassin hevur fulla breid',
'tog-watchcreations'          => 'Legg síður, sum eg stovni, í mítt eftirlit',
'tog-watchdefault'            => 'Vaka yvur nýggjum og broyttum greinum',
'tog-minordefault'            => 'Merk sum standard allar broytingar sum smærri',
'tog-previewontop'            => 'Vís forhondsvísning áðren rættingarkassan',
'tog-previewonfirst'          => 'Sýn forskoðan við fyrstu broyting',
'tog-nocache'                 => 'Minst ikki til síðurnar til næstu ferð',
'tog-fancysig'                => 'Rá undirskrift (uttan sjálvvirkandi slóð)',
'tog-externaleditor'          => 'Nýt útvortis ritil sum fyrimynd',
'tog-externaldiff'            => 'Nýt útvortis diff sum fyrimynd',

'underline-default' => 'Kagarastandard',

'skinpreview' => '(Forskoðan)',

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

# Bits of text used by many pages
'categories'     => 'Bólkar',
'pagecategories' => '{{PLURAL:$1|Bólkur|Bólkar}}',
'subcategories'  => 'Undirbólkur',

'about'          => 'Um',
'article'        => 'Innihaldssíða',
'newwindow'      => '(kemur í nýggjan glugga)',
'cancel'         => 'Ógilda',
'qbspecialpages' => 'Serstakar síður',
'moredotdotdot'  => 'Meira...',
'mypage'         => 'Mín síða',
'mytalk'         => 'Mítt kjak',
'anontalk'       => 'Kjak til hesa ip-adressuna',
'navigation'     => 'Navigatión',

'tagline'          => 'Frá {{SITENAME}}',
'help'             => 'Hjálp',
'search'           => 'Leita',
'searchbutton'     => 'Leita',
'go'               => 'Far til',
'searcharticle'    => 'Far',
'history'          => 'Síðusøga',
'history_short'    => 'Søga',
'printableversion' => 'Prentvinarlig útgáva',
'permalink'        => 'Støðug slóð',
'edit'             => 'Rætta',
'editthispage'     => 'Rætta hesa síðuna',
'delete'           => 'Strika',
'deletethispage'   => 'Strika hesa síðuna',
'protect'          => 'Friða',
'protectthispage'  => 'Friða hesa síðuna',
'unprotect'        => 'Strika friðing',
'newpage'          => 'Nýggj síða',
'talkpage'         => 'Kjakast um hesa síðuna',
'talkpagelinktext' => 'Kjak',
'specialpage'      => 'Serlig síða',
'personaltools'    => 'Persónlig amboð',
'articlepage'      => 'Skoða innihaldssíðuna',
'talk'             => 'Kjak',
'views'            => 'Skoðanir',
'toolbox'          => 'Amboð',
'viewtalkpage'     => 'Vís kjak',
'redirectpagesub'  => 'Ávísingarsíða',
'jumptonavigation' => 'navigatión',
'jumptosearch'     => 'leita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Um {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Um',
'copyright'         => 'Innihald er tøkt undir $1.',
'currentevents'     => 'Núverandi hendingar',
'currentevents-url' => 'Project:Núverandi hendingar',
'disclaimers'       => 'Fyrivarni',
'disclaimerpage'    => 'Project:Fyrivarni',
'edithelp'          => 'Rættihjálp',
'helppage'          => '{{ns:help}}:Innihald',
'mainpage'          => 'Forsíða',
'portal'            => 'Forsíða fyri høvundar',
'portal-url'        => 'Project:Forsíða fyri høvundar',
'privacy'           => 'Handfaring av persónligum upplýsingum',

'ok'                      => 'Í lagi',
'youhavenewmessages'      => 'Tú hevur $1 ($2).',
'youhavenewmessagesmulti' => 'Tú hevur nýggj boð á $1',
'editsection'             => 'rætta',
'editold'                 => 'rætta',
'toc'                     => 'Innihaldsyvirlit',
'showtoc'                 => 'skoða',
'hidetoc'                 => 'fjal',
'thisisdeleted'           => 'Sí ella endurstovna $1?',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Grein',
'nstab-user'      => 'Brúkarasíða',
'nstab-media'     => 'Miðil',
'nstab-special'   => 'Serstøk',
'nstab-project'   => 'Verkætlanar síða',
'nstab-image'     => 'Mynd',
'nstab-mediawiki' => 'Grein',
'nstab-template'  => 'Formur',
'nstab-help'      => 'Hjálp',
'nstab-category'  => 'Flokkur',

# General errors
'badtitle'   => 'Ógyldugt heiti',
'viewsource' => 'Vís keldu',

# Login and logout pages
'welcomecreation'            => '== Vælkomin, $1! ==

Tín konto er nú stovnað. Gloym ikki at broyta tínar {{SITENAME}} innstillingar.',
'yourname'                   => 'Títt brúkaranavn:',
'yourpassword'               => 'Títt loyniorð:',
'yourpasswordagain'          => 'Skriva loyniorð umaftur:',
'userlogin'                  => 'Stovna kontu ella rita inn',
'userlogout'                 => 'Rita út',
'nologin'                    => 'Hevur tú ikki ein brúkara? $1.',
'nologinlink'                => 'Stovna eina kontu',
'gotaccountlink'             => 'Rita inn',
'youremail'                  => 'T-postur (sjálvboðið)*:',
'username'                   => 'Brúkaranavn:',
'yourrealname'               => 'Títt navn*:',
'yourlanguage'               => 'Mál til brúkaraflatu:',
'yournick'                   => 'Títt eyknevni (til undirskriftir):',
'email'                      => 'T-post',
'loginsuccesstitle'          => 'Tú hevur nú ritað inn í {{SITENAME}} sum „[[Brúkari:$1|$1]]“.',
'wrongpassword'              => 'Loyniorðið, sum tú skrivaði, er skeivt. Vinaliga royn aftur.',
'wrongpasswordempty'         => 'Loyniorð manglar. Vinarliga royn aftur.',
'acct_creation_throttle_hit' => 'Tíverri hevur tú longu stovnað $1 kontur. Tú kanst ikki stovna fleiri.',
'emailconfirmlink'           => 'Vátta tína t-post adressu',
'accountcreated'             => 'Konto upprættað',

# Edit page toolbar
'italic_sample' => 'Skákstavir',
'image_sample'  => 'Dømi.jpg',
'media_sample'  => 'Dømi.ogg',
'sig_tip'       => 'Tín undurskrift við tíðarstempli',
'hr_tip'        => 'Vatnrøtt linja (vera sparin við)',

# Edit pages
'summary'          => 'Samandráttur',
'minoredit'        => 'Hetta er smábroyting',
'watchthis'        => 'Hav eftirlit við hesi síðuni',
'savearticle'      => 'Goym síðu',
'showpreview'      => 'Forskoðan',
'showlivepreview'  => 'Beinleiðis forskoðan',
'showdiff'         => 'Sýn broytingar',
'anoneditwarning'  => "'''Ávaring:''' Tú hevur ikki [[Special:Userlogin|ritað inn]]. Tín IP-adressa verður goymd í rættisøguni fyri hesa síðuna.",
'summary-preview'  => 'Samandráttaforskoðan',
'blockedtitle'     => 'Brúkarin er bannaður',
'loginreqlink'     => 'rita inn',
'accmailtitle'     => 'Loyniorð sent.',
'accmailtext'      => 'Loyniorð fyri "$1" er sent til $2.',
'newarticle'       => '(Nýggj)',
'newarticletext'   => "Tú ert komin eftir eini slóð til eina síðu, ið ikki er til enn. Skriva í kassan niðanfyri, um tú vilt byrja uppá hesa síðuna.
(Sí [[{{MediaWiki:helppage}}|hjálparsíðuna]] um tú ynskir fleiri upplýsingar).
Ert tú komin higar av einum mistaki, kanst tú trýsta á '''aftur'''-knøttin á kagaranum.",
'anontalkpagetext' => "----''Hetta er ein kjaksíða hjá einum dulnevndum brúkara, sum ikki hevur stovnað eina kontu enn, ella ikki brúkar hana. Tí noyðast vit at brúka nummerisku [[IP-adressa|IP-adressuna]] hjá honum ella henni. Ein slík IP-adressa kann verða brúkt av fleiri brúkarum. Ert tú ein dulnevndur brúkari, og kennir, at óvikomandi viðmerkingar eru vendar til tín, so vinarliga [[Serstakur:Userlogin|stovna eina kontu]] fyri at sleppa undan samanblanding við aðrar dulnevndar brúkarar í framtíðini.''",
'editing'          => 'Tú rættar $1',
'yourtext'         => 'Tín tekstur',
'yourdiff'         => 'Munir',
'copyrightwarning' => "Alt íkast til {{SITENAME}} er útgivið undir $2 (sí $1 fyri smálutir). Vilt tú ikki hava skriving tína broytta miskunnarleyst og endurspjadda frítt, so send hana ikki inn.<br />
Við at senda arbeiði títt inn, lovar tú, at tú hevur skrivað tað, ella at tú hevur avritað tað frá tilfeingi ið er almenn ogn &#8212; hetta umfatar '''ikki''' flestu vevsíður. 
<strong>IKKI SENDA UPPHAVSRÆTTARVART TILFAR UTTAN LOYVI!</strong>",

# History pages
'revhistory'       => 'Endurskoðanar søga',
'loadhist'         => 'Lesur síðusøgu',
'previousrevision' => '←Eldri endurskoðan',
'nextrevision'     => 'Nýggjari endurskoðan→',
'cur'              => 'nú',
'next'             => 'næst',
'last'             => 'síðst',
'page_last'        => 'síðsta',
'histlegend'       => 'Frágreiðing:<br />
(nú) = munur til núverandi útgávu,
(síðst) = munur til síðsta útgávu, m = minni rættingar',
'histfirst'        => 'Elsta',
'histlast'         => 'Nýggjasta',

# Search results
'searchresults'    => 'Leitúrslit',
'searchresulttext' => 'Ynskir tú fleiri upplýsingar um leiting á {{SITENAME}}, kanst tú skoða [[{{MediaWiki:helppage}}|{{int:help}}]].',
'noexactmatch'     => "'''Ongin síða við heitinum \"\$1\" er til''' Tú kanst [[:\$1|býrja uppá eina grein við hesum heitinum]].",
'prevn'            => 'undanfarnu $1',
'nextn'            => 'næstu $1',
'powersearchtext'  => 'Leita í navnaøki:<br />$1<br />$2 Sýn ávísingar<br />Leita eftur $3 $9',

# Preferences page
'preferences'             => 'Innstillingar',
'mypreferences'           => 'Mínar innstillingar',
'qbsettings-none'         => 'Eingin',
'qbsettings-fixedleft'    => 'Fast vinstru',
'qbsettings-fixedright'   => 'Fast høgru',
'qbsettings-floatingleft' => 'Flótandi vinstru',
'changepassword'          => 'Broyt loyniorð',
'saveprefs'               => 'Goym innstillingar',
'resetprefs'              => 'Endurset innstillingar',
'oldpassword'             => 'Gamalt loyniorð:',
'newpassword'             => 'Nýtt loyniorð:',
'retypenew'               => 'Skriva nýtt loyniorð umaftur:',
'textboxsize'             => 'Broyting av greinum',
'searchresultshead'       => 'Leita',
'localtime'               => 'Lokal klokka',
'allowemail'              => 'Tilset t-post frá øðrum brúkarum',
'defaultns'               => 'Leita í hesum navnarúminum sum fyrisett mál:',
'files'                   => 'Fílur',

# User rights
'saveusergroups' => 'Goym brúkaraflokk',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|broyting|broytingar}}',
'recentchanges'   => 'Seinastu broytingar',
'rcnotefrom'      => 'Niðanfyri standa broytingarnar síðani <b>$2</b>, (upp til <b>$1</b> er sýndar).',
'rclistfrom'      => 'Sýn nýggjar broytingar byrjandi við $1',
'rcshowhideminor' => '$1 minni rættingar',
'rcshowhideliu'   => '$1 skrásettar brúkarar',
'rcshowhideanons' => '$1 navnleysar brúkarar',
'rcshowhidemine'  => '$1 mínar rættingar',
'rclinks'         => 'Sýn seinastu $1 broytingarnar seinastu $2 dagarnar<br />$3',
'diff'            => 'munur',
'hist'            => 'søga',
'hide'            => 'fjal',

# Recent changes linked
'recentchangeslinked' => 'Viðkomandi broytingar',

# Upload
'upload'            => 'Legg fílu upp',
'uploadbtn'         => 'Legg fílu upp',
'uploadnologin'     => 'Ikki ritað inn',
'fileuploadsummary' => 'Samandráttur:',
'ignorewarnings'    => 'Ikki vísa ávaringar',
'savefile'          => 'Goym fílu',
'watchthisupload'   => 'Hav eftirlit við hesi síðuni',

# Image list
'imagelist'      => 'Myndalisti',
'ilsubmit'       => 'Leita',
'imagelinks'     => 'Myndarslóðir',
'imagelist_name' => 'Navn',
'imagelist_user' => 'Brúkari',

# List redirects
'listredirects' => 'Sýn ávísingar',

# Statistics
'statistics' => 'Hagtøl',

'brokenredirects' => 'Brotnar ávísingar',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|být|být}}',
'ncategories'             => '$1 {{PLURAL:$1|bólkur|bólkar}}',
'nviews'                  => '$1 {{PLURAL:$1|skoðan|skoðanir}}',
'uncategorizedpages'      => 'Óbólkaðar síður',
'uncategorizedcategories' => 'Óbólkaðir bólkar',
'unusedimages'            => 'Óbrúktar myndir',
'wantedpages'             => 'Ynsktar síður',
'mostcategories'          => 'Greinir við flest bólkum',
'mostrevisions'           => 'Greinir við flest útgávum',
'allpages'                => 'Allar síður',
'randompage'              => 'Tilvildarlig síða',
'listusers'               => 'Brúkaralisti',
'specialpages'            => 'Serligar síður',
'spheading'               => 'Serligar síður til allar brúkarar',
'newpages-username'       => 'Brúkaranavn:',
'ancientpages'            => 'Elstu síður',
'move'                    => 'Flyt',
'movethispage'            => 'Flyt hesa síðuna',

# Book sources
'booksources-go' => 'Far',

'categoriespagetext' => 'Eftirfylgjandi bólkar eru í hesu wiki.',
'alphaindexline'     => '$1 til $2',
'version'            => 'Útgáva',

# Special:Log
'specialloguserlabel'  => 'Brúkari:',
'speciallogtitlelabel' => 'Heitið:',
'alllogstext'          => 'Samansett sýning av upplegging, striking, friðing, forðing og sysop-gerðabókum.
Tú kanst avmarka sýningina við at velja gerðabókaslag, brúkaranavn ella ávirkaðu síðuna.',

# Special:Allpages
'nextpage'       => 'Næsta síða ($1)',
'prevpage'       => 'Fyrrverandi síða ($1)',
'allarticles'    => 'Allar greinir',
'allinnamespace' => 'Allar síður ($1 navnarúm)',
'allpagesprev'   => 'Undanfarnu',
'allpagesnext'   => 'Næstu',
'allpagessubmit' => 'Far',

# Special:Listusers
'listusers-noresult' => 'Ongin brúkari var funnin.',

# E-mail user
'emailuser'       => 'Send t-post til brúkara',
'emailpage'       => 'Send t-post til brúkara',
'defemailsubject' => '{{SITENAME}} t-postur',
'noemailtitle'    => 'Ongin t-post adressa',
'noemailtext'     => 'Hesin brúkarin hevur ikki upplýst eina gylduga t-post-adressu,
ella hevur hann valt ikki at taka ímóti t-posti frá øðrum brúkarum.',
'emailfrom'       => 'Frá',
'emailto'         => 'Til',
'emailsubject'    => 'Evni',
'emailmessage'    => 'Boð',
'emailsent'       => 'T-postur sendur',
'emailsenttext'   => 'Títt t-post boð er sent.',

# Watchlist
'watchlist'            => 'Mítt eftirlit',
'mywatchlist'          => 'Mítt eftirlit',
'watchlistfor'         => "(fyri '''$1''')",
'nowatchlist'          => 'Tú hevur ongar lutir í eftirlitinum.',
'addedwatch'           => 'Lagt undir eftirlit',
'addedwatchtext'       => "Síðan \"\$1\" er løgd undir [[Special:Watchlist|eftirlit]] hjá tær.
Framtíðar broytingar á hesi síðu og tilknýttu kjaksíðuni verða at síggja her.
Tá sæst síðan sum '''feit skrift''' í [[Special:Recentchanges|broytingaryvirlitinum]] fyri at gera hana lættari at síggja.

Vilt tú flyta síðuna undan tínum eftirliti, kanst tú trýsta á \"Strika eftirlit\" á síðuni.",
'watch'                => 'Eftirlit',
'watchthispage'        => 'Hav eftirlit við hesi síðuni',
'unwatch'              => 'strika eftirlit',
'watchnochange'        => 'Ongin grein í tínum eftirliti er rætta innanfyri hetta tíðarskeiði.',
'watchmethod-list'     => 'kannar síður undir eftirliti fyri feskar broytingar',
'watchlistcontains'    => 'Títt eftirlit inniheldur {{PLURAL:$1|eina síðu|$1 síður}}.',
'wlnote'               => "Niðanfyri {{PLURAL:$1|stendur seinastu broytingina|standa seinastu '''$1''' broytingarnar}} {{PLURAL:$2|seinasta tíman|seinastu '''$2''' tímarnar}}.",
'wlshowlast'           => 'Vís seinastu $1 tímar $2 dagar $3',
'watchlist-show-bots'  => 'Vís bot rættingar',
'watchlist-hide-bots'  => 'Fjal bot rættingar',
'watchlist-show-own'   => 'Vís mínar rættingar',
'watchlist-hide-own'   => 'Fjal mínar rættingar',
'watchlist-show-minor' => 'Vís minni rættingar',
'watchlist-hide-minor' => 'Fjal minni rættingar',

'enotif_newpagetext' => 'Hetta er ein nýggj síða.',

# Delete/protect/revert
'deletepage'     => 'Strika síðu',
'confirm'        => 'Vátta',
'exblank'        => 'síðan var tóm',
'confirmdelete'  => 'Vátta striking',
'historywarning' => 'Ávaring: Síðan, ið tú ert í gongd við at strika, hevur eina søgu:',
'actioncomplete' => 'Verkið er fullgjørt',
'deletedarticle' => 'strikaði "[[$1]]"',
'dellogpage'     => 'Striku logg',
'deletionlog'    => 'striku logg',
'rollback_short' => 'Rulla aftur',
'rollbacklink'   => 'afturrulling',
'rollbackfailed' => 'Afturrulling miseydnað',
'pagesize'       => '(být)',

# Undelete
'undelete'         => 'Endurstovna strikaðar síður',
'undeletereset'    => 'Endurset',
'undeletedarticle' => 'endurstovnaði "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Navnarúm:',
'invert'         => 'Umvend val',
'blanknamespace' => '(Greinir)',

# Contributions
'contributions' => 'Brúkaraíkast',
'mycontris'     => 'Mítt íkast',
'year'          => 'Frá ár (og áðrenn):',

'sp-contributions-newest'   => 'Nýggjasta',
'sp-contributions-oldest'   => 'Elsta',
'sp-contributions-newer'    => 'Nýggjari $1',
'sp-contributions-older'    => 'Eldri $1',
'sp-contributions-newbies'  => 'Vís bert íkast frá nýggjum kontoum',
'sp-contributions-search'   => 'Leita eftir íkøstum',
'sp-contributions-username' => 'IP adressa ella brúkaranavn:',
'sp-contributions-submit'   => 'Leita',

# What links here
'whatlinkshere' => 'Hvat slóðar higar',
'isredirect'    => 'ávísingarsíða',

# Block/unblock
'blockip'            => 'Banna brúkara',
'ipaddress'          => 'IP-adressa:',
'ipadressorusername' => 'IP-adressa ella brúkaranavn:',
'ipbreason'          => 'Orsøk:',
'ipbsubmit'          => 'Banna henda brúkaran',
'blockipsuccesssub'  => 'Banning framd',
'ipb-unblock-addr'   => 'Óbanna $1',
'ipusubmit'          => 'Óbanna hesa adressuna',
'ipblocklist-submit' => 'Leita',
'blocklink'          => 'banna',
'unblocklink'        => 'óbanna',
'contribslink'       => 'íkøst',

# Move page
'movepage'        => 'Flyt síðu',
'movenologin'     => 'Hevur ikki ritað inn',
'newtitle'        => 'Til nýtt heiti:',
'move-watch'      => 'Hav eftirlit við hesi síðuni',
'articleexists'   => 'Ein síða finst longu við hasum navninum,
ella er navnið tú valdi ógyldugt.
Vinarliga vel eitt annað navn.',
'1movedto2'       => '$1 flutt til $2',
'1movedto2_redir' => '$1 flutt til $2 um ávísing',
'movelogpage'     => 'Flyti logg',
'movereason'      => 'Orsøk:',
'delete_and_move' => 'Strika og flyt',

# Namespace 8 related
'allmessages'               => 'Øll kervisboð',
'allmessagesname'           => 'Navn',
'allmessagescurrent'        => 'Verandi tekstur',
'allmessagestext'           => 'Hetta er eitt yvirlit av tøkum kervisboðum í MediaWiki-navnarúmi.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' er ikki stuðlað orsakað av at '''\$wgUseDatabaseMessages''' er sløkt.",
'allmessagesfilter'         => 'Boð navn filtur:',
'allmessagesmodified'       => 'Vís bert broytt',

# Tooltip help for the actions
'tooltip-search' => 'Leita {{SITENAME}}',
'tooltip-p-logo' => 'Forsíða',

# Attribution
'anonymous' => 'Dulnevndir brúkarar í {{SITENAME}}',
'siteuser'  => '{{SITENAME}}brúkari $1',
'and'       => 'og',
'siteusers' => '{{SITENAME}}brúkari(ar) $1',

# Spam protection
'subcategorycount' => 'Tað {{PLURAL:$1|er ein undirbólkur|eru $1 undirbólkar}} í hesum bólki.',

# Math options
'mw_math_png'    => 'Vís altíð sum PNG',
'mw_math_simple' => 'HTML um sera einfalt annars PNG',
'mw_math_html'   => 'HTML um møguligt annars PNG',
'mw_math_source' => 'Lat verða sum TeX (til tekstkagara)',
'mw_math_modern' => 'Tilmælt nýtíðarkagara',

# Browsing diffs
'previousdiff' => '← Far til fyrra mun',
'nextdiff'     => 'Far til næsta mun →',

# Special:Newimages
'newimages' => 'Nýggjar myndir',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alt',
'monthsall'     => 'allir',

# action=purge
'confirm_purge_button' => 'Í lagi',

# Multipage image navigation
'imgmultipageprev' => '← fyrrverandi síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo'       => 'Far!',

# Table pager
'table_pager_next'         => 'Næsta síða',
'table_pager_prev'         => 'Fyrrverandi síða',
'table_pager_limit_submit' => 'Far',

# Auto-summaries
'autosumm-new' => 'Nýggj síða: $1',

# Watchlist editor
'watchlistedit-clear-title'  => 'Strika alt eftirlit',
'watchlistedit-clear-legend' => 'Strika alt eftirlit',
'watchlistedit-normal-title' => 'Rætta eftirlit',
'watchlistedit-raw-title'    => 'Rætta rátt eftirlit',
'watchlistedit-raw-legend'   => 'Rætta rátt eftirlit',

# Watchlist editing tools
'watchlisttools-view'  => 'Vís viðkomandi broytingar',
'watchlisttools-edit'  => 'Vís og rætta eftirlit',
'watchlisttools-raw'   => 'Rætta rátt eftirlit',
'watchlisttools-clear' => 'Strika alt eftirlit',

);
