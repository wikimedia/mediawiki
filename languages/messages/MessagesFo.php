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
'tog-usenewrc'                => 'víðka seinastu broytingar lista<br />(ikki til alla kagarar)',
'tog-numberheadings'          => 'Sjálvtalmerking av yvirskrift',
'tog-showtoolbar'             => 'Vís amboðslinju í rætting',
'tog-editondblclick'          => 'Rætta síðu við at tvíklikkja (JavaScript)',
'tog-editsection'             => 'Rætta greinpart við hjálp av [rætta]-ávísing',
'tog-editsectiononrightclick' => 'Rætta greinpart við at høgraklikkja<br /> á yvirskrift av greinparti (JavaScript)',
'tog-showtoc'                 => 'Vís innihaldsyvurlit<br />(Til greinir við meira enn trimun greinpartum)',
'tog-rememberpassword'        => 'Minst til loyniorð næstu ferð',
'tog-editwidth'               => 'Rættingarkassin hevur fulla breid',
'tog-watchdefault'            => 'Vaka yvur nýggjum og broyttum greinum',
'tog-minordefault'            => 'Merk sum standard allar broytingar sum smærri',
'tog-previewontop'            => 'Vís forhondsvísning áðren rættingarkassan',
'tog-previewonfirst'          => 'Sýn forskoðan við fyrstu broyting',
'tog-nocache'                 => 'Minst ikki til síðurnar til næstu ferð',

# Dates
'sunday'    => 'sunnudagur',
'monday'    => 'mánadagur',
'tuesday'   => 'týsdagur',
'wednesday' => 'mikudagur',
'thursday'  => 'hósdagur',
'friday'    => 'fríggjadagur',
'saturday'  => 'leygardagur',
'january'   => 'januar',
'february'  => 'februar',
'march'     => 'mars',
'april'     => 'apríl',
'may_long'  => 'mai',
'june'      => 'juni',
'july'      => 'juli',
'august'    => 'august',
'september' => 'september',
'october'   => 'oktober',
'november'  => 'november',
'december'  => 'desember',
'jan'       => 'jan',
'feb'       => 'feb',
'mar'       => 'mar',
'apr'       => 'apr',
'may'       => 'mai',
'jun'       => 'jun',
'jul'       => 'jul',
'aug'       => 'aug',
'sep'       => 'sep',
'oct'       => 'okt',
'nov'       => 'nov',
'dec'       => 'des',

# Bits of text used by many pages
'subcategories' => 'Undirbólkur',

'about'         => 'Um',
'article'       => 'Innihaldssíða',
'newwindow'     => '(kemur í nýggjan glugga)',
'cancel'        => 'Ógilda',
'moredotdotdot' => 'Meira...',
'mypage'        => 'Mín síða',
'mytalk'        => 'Mítt kjak',
'navigation'    => 'Navigatión',

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
'delete'           => 'Strika',
'protect'          => 'Friða',
'unprotect'        => 'Strika friðing',
'talkpagelinktext' => 'Kjak',
'personaltools'    => 'Persónlig amboð',
'talk'             => 'Kjak',
'toolbox'          => 'Amboð',
'viewtalkpage'     => 'Vís kjak',
'redirectpagesub'  => 'Ávísingarsíða',
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
'showtoc'                 => 'skoða',
'hidetoc'                 => 'fjal',

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
'viewsource' => 'Vís keldu',

# Login and logout pages
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
'acct_creation_throttle_hit' => 'Tíverri hevur tú longu stovnað $1 kontur. Tú kanst ikki stovna fleiri.',
'emailconfirmlink'           => 'Vátta tína t-post adressu',
'accountcreated'             => 'Konto upprættað',

# Edit page toolbar
'italic_sample' => 'Skákstavir',
'image_sample'  => 'Dømi.jpg',
'media_sample'  => 'Dømi.ogg',

# Edit pages
'summary'          => 'Samandráttur',
'minoredit'        => 'Hetta er smábroyting',
'watchthis'        => 'Hav eftirlit við hesi síðuni',
'savearticle'      => 'Goym síðu',
'showpreview'      => 'Forskoðan',
'showlivepreview'  => 'Beinleiðis forskoðan',
'showdiff'         => 'Sýn broytingar',
'summary-preview'  => 'Samandráttaforskoðan',
'accmailtitle'     => 'Loyniorð sent.',
'accmailtext'      => 'Loyniorð fyri "$1" er sent til $2.',
'newarticle'       => '(Nýggj)',
'newarticletext'   => "Tú ert komin eftir eini slóð til eina síðu, ið ikki er til enn. Skriva í kassan niðanfyri, um tú vilt byrja uppá hesa síðuna.
(Sí [[{{MediaWiki:helppage}}|hjálparsíðuna]] um tú ynskir fleiri upplýsingar).
Ert tú komin higar av einum mistaki, kanst tú trýsta á '''aftur'''-knøttin á kagaranum.",
'editing'          => 'Tú rættar $1',
'yourtext'         => 'Tín tekstur',
'copyrightwarning' => "Alt íkast til {{SITENAME}} er útgivið undir $2 (sí $1 fyri smálutir). Vilt tú ikki hava skriving tína broytta miskunnarleyst og endurspjadda frítt, so send hana ikki inn.<br />
Við at senda arbeiði títt inn, lovar tú, at tú hevur skrivað tað, ella at tú hevur avritað tað frá tilfeingi ið er almenn ogn &#8212; hetta umfatar '''ikki''' flestu vevsíður. 
<strong>IKKI SENDA UPPHAVSRÆTTARVART TILFAR UTTAN LOYVI!</strong>",

# History pages
'histlegend' => 'Frágreiðing:<br />
(nú) = munur til núverandi útgávu,
(síðst) = munur til síðsta útgávu, m = minni rættingar',
'histfirst'  => 'Elsta',
'histlast'   => 'Nýggjasta',

# Search results
'searchresults'    => 'Leitúrslit',
'searchresulttext' => 'Ynskir tú fleiri upplýsingar um leiting á {{SITENAME}}, kanst tú skoða [[{{MediaWiki:helppage}}|{{int:help}}]].',
'noexactmatch'     => "'''Ongin síða við heitinum \"\$1\" er til''' Tú kanst [[:\$1|býrja uppá eina grein við hesum heitinum]].",
'powersearchtext'  => 'Leita í navnaøki:<br />$1<br />$2 Sýn ávísingar<br />Leita eftur $3 $9',

# Preferences page
'preferences'             => 'Innstillingar',
'mypreferences'           => 'Mínar innstillingar',
'qbsettings-none'         => 'Eingin',
'qbsettings-fixedleft'    => 'Fast vinstru',
'qbsettings-fixedright'   => 'Fast høgru',
'qbsettings-floatingleft' => 'Flótandi vinstru',
'changepassword'          => 'Broyt loyniorð',
'oldpassword'             => 'Gamalt loyniorð:',
'newpassword'             => 'Nýtt loyniorð:',
'retypenew'               => 'Skriva nýtt loyniorð umaftur:',
'searchresultshead'       => 'Leita',
'allowemail'              => 'Tilset t-post frá øðrum brúkarum',
'files'                   => 'Fílur',

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
'upload'         => 'Legg fílu upp',
'uploadbtn'      => 'Legg fílu upp',
'uploadnologin'  => 'Ikki ritað inn',
'ignorewarnings' => 'Ikki vísa ávaringar',

# Image list
'imagelist'  => 'Myndalisti',
'ilsubmit'   => 'Leita',
'imagelinks' => 'Myndarslóðir',

# List redirects
'listredirects' => 'Sýn ávísingar',

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
'newpages-username'       => 'Brúkaranavn:',
'ancientpages'            => 'Elstu síður',
'move'                    => 'Flyt',
'movethispage'            => 'Flyt hesa síðuna',

# Book sources
'booksources-go' => 'Far',

'alphaindexline' => '$1 til $2',
'version'        => 'Útgáva',

# Special:Allpages
'allarticles'    => 'Allar greinir',
'allinnamespace' => 'Allar síður ($1 navnarúm)',
'allpagesprev'   => 'Undanfarnu',
'allpagesnext'   => 'Næstu',
'allpagessubmit' => 'Far',

# Special:Listusers
'listusers-noresult' => 'Ongin brúkari var funnin.',

# E-mail user
'emailuser'     => 'Send t-post til brúkara',
'emailpage'     => 'Send t-post til brúkara',
'emailfrom'     => 'Frá',
'emailto'       => 'Til',
'emailsubject'  => 'Evni',
'emailmessage'  => 'Boð',
'emailsent'     => 'T-postur sendur',
'emailsenttext' => 'Títt t-post boð er sent.',

# Watchlist
'watchlist'            => 'Mítt eftirlit',
'mywatchlist'          => 'Mítt eftirlit',
'nowatchlist'          => 'Tú hevur ongar lutir í eftirlitinum.',
'addedwatch'           => 'Lagt undir eftirlit',
'watch'                => 'Eftirlit',
'watchthispage'        => 'Hav eftirlit við hesi síðuni',
'unwatch'              => 'strika eftirlit',
'watchlistcontains'    => 'Títt eftirlit inniheldur {{PLURAL:$1|eina síðu|$1 síður}}.',
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
'pagesize'       => '(být)',

# Undelete
'undelete'         => 'Endurstovna strikaðar síður',
'undeletedarticle' => 'endurstovnaði "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Navnarúm:',
'blanknamespace' => '(Greinir)',

# Contributions
'contributions' => 'Brúkaraíkast',
'mycontris'     => 'Mítt íkast',

'sp-contributions-username' => 'IP adressa ella brúkaranavn:',

# What links here
'whatlinkshere' => 'Hvat slóðar higar',

# Block/unblock
'ipbsubmit'    => 'Banna henda brúkaran',
'blocklink'    => 'banna',
'unblocklink'  => 'óbanna',
'contribslink' => 'íkøst',

# Move page
'movepage'        => 'Flyt síðu',
'movenologin'     => 'Hevur ikki ritað inn',
'newtitle'        => 'Til nýtt heiti:',
'move-watch'      => 'Hav eftirlit við hesi síðuni',
'1movedto2'       => '$1 flutt til $2',
'1movedto2_redir' => '$1 flutt til $2 um ávísing',
'movelogpage'     => 'Flyti logg',
'movereason'      => 'Orsøk:',

# Namespace 8 related
'allmessages'               => 'Øll kervisboð',
'allmessagesname'           => 'Navn',
'allmessagescurrent'        => 'Verandi tekstur',
'allmessagestext'           => 'Hetta er eitt yvirlit av tøkum kervisboðum í MediaWiki-navnarúmi.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' er ikki stuðlað orsakað av at '''\$wgUseDatabaseMessages''' er sløkt.",

# Tooltip help for the actions
'tooltip-search' => 'Leita {{SITENAME}}',

# Attribution
'anonymous' => 'Dulnevndir brúkarar í {{SITENAME}}',
'and'       => 'og',

# Math options
'mw_math_png'    => 'Vís altíð sum PNG',
'mw_math_simple' => 'HTML um sera einfalt annars PNG',
'mw_math_html'   => 'HTML um møguligt annars PNG',
'mw_math_source' => 'Lat verða sum TeX (til tekstkagara)',
'mw_math_modern' => 'Tilmælt nýtíðarkagara',

# Special:Newimages
'newimages' => 'Nýggjar myndir',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alt',
'monthsall'     => 'allir',

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
