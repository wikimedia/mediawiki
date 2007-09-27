<?php
/** Faroese (Føroyskt)
  *
  * @addtogroup Language
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

'about'      => 'Um',
'mytalk'     => 'Mítt kjak',
'navigation' => 'Navigatión',

'help'             => 'Hjálp',
'search'           => 'Leita',
'searchbutton'     => 'Leita',
'go'               => 'Far til',
'searcharticle'    => 'Far',
'history_short'    => 'Søga',
'printableversion' => 'Prentvinarlig útgáva',
'permalink'        => 'Støðug slóð',
'edit'             => 'Rætta',
'delete'           => 'Strika',
'protect'          => 'Friða',
'unprotect'        => 'Strika friðing',
'personaltools'    => 'Persónlig amboð',
'talk'             => 'Kjak',
'toolbox'          => 'Amboð',
'redirectpagesub'  => 'Ávísingarsíða',
'jumptosearch'     => 'leita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Um {{SITENAME}}',
'copyright'         => 'Innihald er tøkt undir $1.',
'currentevents'     => 'Núverandi hendingar',
'currentevents-url' => 'Project:Núverandi hendingar',
'disclaimers'       => 'Fyrivarni',
'disclaimerpage'    => 'Project:Fyrivarni',
'mainpage'          => 'Forsíða',
'portal'            => 'Forsíða fyri høvundar',
'portal-url'        => 'Project:Forsíða fyri høvundar',
'privacy'           => 'Handfaring av persónligum upplýsingum',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Grein',
'nstab-user'      => 'Brúkarasíða',
'nstab-media'     => 'Miðil',
'nstab-special'   => 'Serstøk',
'nstab-image'     => 'Mynd',
'nstab-mediawiki' => 'Grein',
'nstab-template'  => 'Formur',
'nstab-help'      => 'Hjálp',
'nstab-category'  => 'Flokkur',

# General errors
'viewsource' => 'Vís keldu',

# Login and logout pages
'userlogin'                  => 'Stovna kontu ella rita inn',
'userlogout'                 => 'Rita út',
'acct_creation_throttle_hit' => 'Tíverri hevur tú longu stovnað $1 kontur. Tú kanst ikki stovna fleiri.',
'accountcreated'             => 'Konto upprættað',

# Edit pages
'accmailtitle' => 'Loyniorð sent.',
'accmailtext'  => 'Loyniorð fyri "$1" er sent til $2.',

# Preferences page
'preferences'             => 'Innstillingar',
'qbsettings-none'         => 'Eingin',
'qbsettings-fixedleft'    => 'Fast vinstru',
'qbsettings-fixedright'   => 'Fast høgru',
'qbsettings-floatingleft' => 'Flótandi vinstru',
'newpassword'             => 'Nýtt loyniorð:',
'allowemail'              => 'Tilset t-post frá øðrum brúkarum',

# Recent changes
'recentchanges' => 'Seinastu broytingar',

# Recent changes linked
'recentchangeslinked' => 'Viðkomandi broytingar',

# Upload
'upload' => 'Legg fílu upp',

# Miscellaneous special pages
'allpages'     => 'Allar síður',
'randompage'   => 'Tilvildarlig síða',
'specialpages' => 'Serligar síður',
'ancientpages' => 'Elstu síður',
'move'         => 'Flyt',

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

# E-mail user
'emailuser' => 'Send t-post til brúkara',

# Watchlist
'watchlist'  => 'Mítt eftirlit',
'addedwatch' => 'Lagt undir eftirlit',
'watch'      => 'Eftirlit',
'unwatch'    => 'strika eftirlit',

# Delete/protect/revert
'deletepage'     => 'Strika síðu',
'exblank'        => 'síðan var tóm',
'historywarning' => 'Ávaring: Síðan, ið tú ert í gongd við at strika, hevur eina søgu:',
'actioncomplete' => 'Verkið er fullgjørt',
'deletedarticle' => 'strikaði "[[$1]]"',
'dellogpage'     => 'Striku logg',
'deletionlog'    => 'striku logg',

# Undelete
'undelete' => 'Endurstovna strikaðar síður',

# Contributions
'contributions' => 'Brúkaraíkast',
'mycontris'     => 'Mítt íkast',

# What links here
'whatlinkshere' => 'Hvat slóðar higar',

# Move page
'movepage'        => 'Flyt síðu',
'1movedto2'       => '$1 flutt til $2',
'1movedto2_redir' => '$1 flutt til $2 um ávísing',
'movelogpage'     => 'Flyti logg',

# Namespace 8 related
'allmessages'               => 'Øll kervisboð',
'allmessagesname'           => 'Navn',
'allmessagescurrent'        => 'Verandi tekstur',
'allmessagestext'           => 'Hetta er eitt yvirlit av tøkum kervisboðum í MediaWiki-navnarúmi.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:AllMessages''' er ikki stuðlað orsakað av at '''\$wgUseDatabaseMessages''' er sløkt.",

# Attribution
'anonymous' => 'Dulnevndir brúkarar í {{SITENAME}}',
'and'       => 'og',

# Math options
'mw_math_png'    => 'Vís altíð sum PNG',
'mw_math_simple' => 'HTML um sera einfalt annars PNG',
'mw_math_html'   => 'HTML um møguligt annars PNG',
'mw_math_source' => 'Lat verða sum TeX (til tekstkagara)',
'mw_math_modern' => 'Tilmælt nýtíðarkagara',

# Auto-summaries
'autosumm-new' => 'Nýggj síða: $1',

);
