<?php
/** Bikol Central (Bikol Central)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Filipinayzd
 * @author Geopoet
 * @author Kaganer
 * @author Steven*fung
 * @author Urhixidur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medio',
	NS_SPECIAL          => 'Espesyal',
	NS_TALK             => 'Olay',
	NS_USER             => 'Paragamit',
	NS_USER_TALK        => 'Olay_kan_paragamit',
	NS_PROJECT_TALK     => 'Olay_sa_$1',
	NS_FILE             => 'Ladawan',
	NS_FILE_TALK        => 'Olay_sa_ladawan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Olay_sa_MediaWiki',
	NS_TEMPLATE         => 'Plantilya',
	NS_TEMPLATE_TALK    => 'Olay_sa_plantilya',
	NS_HELP             => 'Tabang',
	NS_HELP_TALK        => 'Olay_sa_tabang',
	NS_CATEGORY         => 'Kategorya',
	NS_CATEGORY_TALK    => 'Olay_sa_kategorya',
);

$magicWords = array(
	'currentmonth'            => array( '1', 'BULANNGONYAN', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'        => array( '1', 'NGARANBULANNGONYAN', 'CURRENTMONTHNAME' ),
	'currentday'              => array( '1', 'ALDAWNGONYAN', 'CURRENTDAY' ),
	'currentyear'             => array( '1', 'TAONNGONYAN', 'CURRENTYEAR' ),
	'currenttime'             => array( '1', 'PANAHONNGONYAN', 'CURRENTTIME' ),
	'currenthour'             => array( '1', 'ORASNGONYAN', 'CURRENTHOUR' ),
	'localmonth'              => array( '1', 'LOKALBULAN', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'          => array( '1', 'NGARANLOKALBULAN', 'LOCALMONTHNAME' ),
	'localday'                => array( '1', 'LOKALALDAW', 'LOCALDAY' ),
	'localday2'               => array( '1', 'LOKALALDAW2', 'LOCALDAY2' ),
	'localdayname'            => array( '1', 'NGARANLOKALALDAW', 'LOCALDAYNAME' ),
	'localyear'               => array( '1', 'LOKALTAON', 'LOCALYEAR' ),
	'localtime'               => array( '1', 'LOKALPANAHON', 'LOCALTIME' ),
	'localhour'               => array( '1', 'LOKALORAS', 'LOCALHOUR' ),
	'numberofpages'           => array( '1', 'NUMEROKANPAHINA', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', 'NUMEROKANARTIKULO', 'NUMBEROFARTICLES' ),
	'numberoffiles'           => array( '1', 'NUMEROKANDOKUMENTO', 'NUMBEROFFILES' ),
	'numberofusers'           => array( '1', 'NUMEROKANPARAGAMIT', 'NUMBEROFUSERS' ),
	'numberofedits'           => array( '1', 'NUMEROKANLIGWAT', 'NUMBEROFEDITS' ),
	'pagename'                => array( '1', 'NGARANKANPAHINA', 'PAGENAME' ),
	'pagenamee'               => array( '1', 'KAGNGARANKANPAHINA', 'PAGENAMEE' ),
	'namespace'               => array( '1', 'NGARANESPASYO', 'NAMESPACE' ),
	'namespacee'              => array( '1', 'KAGNGARANESPASYO', 'NAMESPACEE' ),
	'talkspace'               => array( '1', 'OLAYESPASYO', 'TALKSPACE' ),
	'talkspacee'              => array( '1', 'KAGOLAYESPASYO', 'TALKSPACEE' ),
	'fullpagename'            => array( '1', 'TODONGNGARANKANPAHINA', 'FULLPAGENAME' ),
	'fullpagenamee'           => array( '1', 'KAGNGARANKANTODONGNGARANKANPAHINA', 'FULLPAGENAMEE' ),
	'talkpagename'            => array( '1', 'NGARANKANPAHINANINOLAY', 'TALKPAGENAME' ),
	'talkpagenamee'           => array( '1', 'KAGNGARANKANPAHINANINOLAY', 'TALKPAGENAMEE' ),
	'msg'                     => array( '0', 'MSH', 'MSG:' ),
	'img_right'               => array( '1', 'too', 'right' ),
	'img_left'                => array( '1', 'wala', 'left' ),
	'img_none'                => array( '1', 'mayò', 'none' ),
	'img_center'              => array( '1', 'sentro', 'tangâ', 'center', 'centre' ),
	'img_framed'              => array( '1', 'nakakawadro', 'kwadro', 'framed', 'enframed', 'frame' ),
	'img_frameless'           => array( '1', 'daing kwadro', 'frameless' ),
	'img_page'                => array( '1', 'pahina=$1', 'pahina $1', 'page=$1', 'page $1' ),
	'localurl'                => array( '0', 'LOKALURL', 'LOCALURL:' ),
	'localurle'               => array( '0', 'LOKALURLE', 'LOCALURLE:' ),
	'currentweek'             => array( '1', 'SEMANANGONYAN', 'CURRENTWEEK' ),
	'localweek'               => array( '1', 'LOKALSEMANA', 'LOCALWEEK' ),
	'plural'                  => array( '0', 'DAKOL:', 'PLURAL:' ),
	'fullurl'                 => array( '0', 'TODONGURL:', 'FULLURL:' ),
	'fullurle'                => array( '0', 'TODONGURLE:', 'FULLURLE:' ),
	'language'                => array( '0', '#TATARAMON', '#LANGUAGE:' ),
	'contentlanguage'         => array( '1', 'TATARAMONKANLAOG', 'TATARAMONLAOG', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'          => array( '1', 'NUMEROKANTAGAMATO', 'NUMBEROFADMINS' ),
	'padleft'                 => array( '0', 'PADWALA', 'PADLEFT' ),
	'padright'                => array( '0', 'PADTOO', 'PADRIGHT' ),
	'filepath'                => array( '0', 'FILEDALAN', 'FILEPATH:' ),
	'hiddencat'               => array( '1', '__NAKATAGONGKAT__', '__HIDDENCAT__' ),
	'pagesincategory'         => array( '1', 'PAHINASAKATEGORYA', 'PAHINASAKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                => array( '1', 'PAHINASOKOL', 'PAGESIZE' ),
);

$specialPageAliases = array(
	'Search'                    => array( 'Hanapon' ),
	'Upload'                    => array( 'Ikarga' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Linyahan an kilyawan:',
'tog-justify' => 'Pantayón an mga talodtód',
'tog-hideminor' => 'Tagóon an mga saradít na paghirá sa nakakaági pa sanáng pagbabàgo',
'tog-hidepatrolled' => 'Tagóa an patrolyadong mga paghirá sa nakakaági pa sanáng pagbabàgo',
'tog-newpageshidepatrolled' => 'Tagóa an patrolyadong mga pahina gikan sa listahan kan bàgong pahina',
'tog-extendwatchlist' => 'Palakbanga an bantay-listahan (watchlist) na maipahiling an gabos na pinagbago, bako sana an pinakahurihang binago',
'tog-usenewrc' => 'Grupong mga pagbabago sa kada pahina kan pinakahuring mga binago asin bantay-listahan (minakaipo nin JavaScript)',
'tog-numberheadings' => 'Tolos-bilang na mga pamayohán',
'tog-showtoolbar' => 'Ihayag an toolbar nin paghirá (minakaipo nin JavaScript)',
'tog-editondblclick' => 'Liwaton an mga pahina sa dobleng pagpindot (minakaipo nin JavaScript)',
'tog-editsection' => 'Paganaha an paghihirá kan seksyon sa paági kan [liwaton] na kilyawan',
'tog-editsectiononrightclick' => 'Paganaha an paghihirá kan seksyon sa paagi kan patoong pagpindot sa mga titulo kan seksyon (minakaipo nin JavaScript)',
'tog-showtoc' => 'Ihayag an taytayan nin mga laog (para sa mga pahinang igwang sobra sa 3 pamayohan)',
'tog-rememberpassword' => 'Giromdoma an sakong paglaóg sa kilyaw (browser) na ini (para sa maximum na $1 {{PLURAL:$1|aldaw|mga aldaw}})',
'tog-watchcreations' => 'Idagdag sa mga pahina na ako an nagmukna sa sakong bantay-listahan',
'tog-watchdefault' => 'Idagdag an mga pahina na ako an nagliwat sa sakong bantay-listahan',
'tog-watchmoves' => 'Idagdag an mga pahina na ako an nagbalyo sa sakong bantay-listahan',
'tog-watchdeletion' => 'Idagdag an mga pahina na ako an nagpura sa sakong bantay-listahan',
'tog-minordefault' => 'Markahán gabos na saradit na pagliwat sa paaging panugmad',
'tog-previewontop' => 'Ipahilíng an patànaw bàgo an kahon nin paghirá',
'tog-previewonfirst' => 'Ipahilíng an patànaw sa enot na paghirá',
'tog-nocache' => 'Pundoha an pagsaray nin mga pahina sa kilyaw (browser)',
'tog-enotifwatchlistpages' => 'E-koreohan ako kunsoarin an sarong pahina sa sakong bantay-listahan (watchlist) pinagribayan',
'tog-enotifusertalkpages' => 'E-koreohan ako pag pigribáyan an pahina kan sakóng olay',
'tog-enotifminoredits' => 'E-koreohan man giraray ako para sa saradit na paghirá kan mga pahina',
'tog-enotifrevealaddr' => 'Ibuyágyag an sakong e-koreong address sa pan-abisong mga e-koreo',
'tog-shownumberswatching' => 'Ihayag an numero kan nagbabantay na mga parágamit',
'tog-oldsig' => 'Tugmadong pirma',
'tog-fancysig' => 'Trataron an pirma na wiki-teksto (mayo nin awtomatikong kilyaw)',
'tog-externaleditor' => 'Gamíta nguna an panluwas na editor (para sa mga eksperto sana, minakaipo nin espesyal na mga panuytoy (settings) sa saimong kompyuter.',
'tog-externaldiff' => 'Gamíta an panluwas na diff nguna (para sa mga eksperto sana, minakaipo nin espesyal na mga panuytoy (settings) sa saimong kompyuter.
[//www.mediawiki.org/wiki/Manual:External_editors Kadagdagang impormasyon.])',
'tog-showjumplinks' => 'Paganaha an "luksó sa" kilyaw nin kalangkayan',
'tog-uselivepreview' => 'Gamíta an buhay na patànaw (minakaipo nin JavaScript) (eksperimental)',
'tog-forceeditsummary' => 'Ibunyaw sako kun maglalaog sa blangkong kalanyang nin paghirá',
'tog-watchlisthideown' => 'Tagóa an sakong mga pagliwat gikan sa bantay-listahan',
'tog-watchlisthidebots' => 'Tagóa an bot na mga pagliwat gikan sa bantay-listahan',
'tog-watchlisthideminor' => 'Tagóa an saradít na mga pagliwat gikan sa bantay-listahan',
'tog-watchlisthideliu' => 'Tagoon an mga pagbabagong nahimo kan mga nakalaog na paragamit gikan sa bantayang listahan',
'tog-watchlisthideanons' => 'Tagoon an mga pagbabagong nahimo kan mga bakong bistadong paragamit gikan sa bantayang listahan',
'tog-watchlisthidepatrolled' => 'Tagoon an mga patrolyadong pagbabago gikan sa bantayang listahan',
'tog-nolangconversion' => 'Pundoha an mga palaen na pagbabago',
'tog-ccmeonemails' => 'Ipadara sako an mga kopya kan e-koreo na pinadara ko sa ibang mga paragamit',
'tog-diffonly' => 'Dai tabi ihayag an laog kan pahina sa ibaba nin mga diffs',
'tog-showhiddencats' => 'Ihayag an nakatagong mga kategorya',
'tog-norollbackdiff' => 'Omidohon an diff matapos himoon an pagbalikot',

'underline-always' => 'Pirmi',
'underline-never' => 'Nungka',
'underline-default' => 'Kilyaw na panugmad',

# Font style option in Special:Preferences
'editfont-style' => 'Baguhon an estilo nin kalwig sa sinasakupan',
'editfont-default' => 'Kilyawang tugmad',
'editfont-monospace' => 'Manarong espasyo nin kalwig',
'editfont-sansserif' => 'Kalwig na Sans-serif',
'editfont-serif' => 'Kalwig na Serif',

# Dates
'sunday' => 'Domingo',
'monday' => 'Lunes',
'tuesday' => 'Martes',
'wednesday' => 'Miyerkoles',
'thursday' => 'Huwebes',
'friday' => 'Biyernes',
'saturday' => 'Sabado',
'sun' => 'Dom',
'mon' => 'Lun',
'tue' => 'Mar',
'wed' => 'Miy',
'thu' => 'Huw',
'fri' => 'Biy',
'sat' => 'Sab',
'january' => 'Enero',
'february' => 'Pebrero',
'march' => 'Marso',
'april' => 'Abril',
'may_long' => 'Mayo',
'june' => 'Hunyo',
'july' => 'Hulyo',
'august' => 'Agosto',
'september' => 'Setyembre',
'october' => 'Oktobre',
'november' => 'Nobyembre',
'december' => 'Desyembre',
'january-gen' => 'Enero',
'february-gen' => 'Pebrero',
'march-gen' => 'Marso',
'april-gen' => 'Abril',
'may-gen' => 'Mayo',
'june-gen' => 'Hunyo',
'july-gen' => 'Hulyo',
'august-gen' => 'Agosto',
'september-gen' => 'Setyembre',
'october-gen' => 'Oktobre',
'november-gen' => 'Nobyembre',
'december-gen' => 'Desyembre',
'jan' => 'Ene',
'feb' => 'Peb',
'mar' => 'Mar',
'apr' => 'Abr',
'may' => 'May',
'jun' => 'Hun',
'jul' => 'Hul',
'aug' => 'Ago',
'sep' => 'Set',
'oct' => 'Okt',
'nov' => 'Nob',
'dec' => 'Des',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategorya|Mga kategorya}}',
'category_header' => 'Mga pahina sa kategoryang "$1"',
'subcategories' => 'Mga sub-kategorya',
'category-media-header' => 'Media sa kategoryang "$1"',
'category-empty' => "''Ining kategorya sa presente mayong laog na mga pahina o media.\"",
'hidden-categories' => '{{PLURAL:$1|Nakatagong kategorya|Mga nakatagong kategorya}}',
'hidden-category-category' => 'Mga nakatagong kategorya',
'category-subcat-count' => '{{PLURAL:$2|Ining kategorya igwa sana kan minasunod na sub-kategorya.|Ining kategorya igwa kan minasunod {{PLURAL:$1|subcategory|$1 subcategories}}, out of $2 total.}}',
'category-subcat-count-limited' => 'Ining kategorya igwa kan minasunod na {{PLURAL:$1|sub-kategorya|$1 mga sub-kategorya}}.',
'category-article-count' => '{{PLURAL:$2|An mga minasunod na pahina sana an laog kan kategoryang ini|An mga minasunod na {{PLURAL:$1|pahina|$1 mga pahina}} an yaon sa kategoryang ini, gikan sa $2 kagabsan.}}',
'category-article-count-limited' => 'An minasunod na {{PLURAL:$1|pahina|$1 mga pahina}} yaon sa presenteng kategorya.',
'category-file-count' => '{{PLURAL:$2|Ining kategorya naglalaman sana kan minasunod na sagunson.|An minasunod {{PLURAL:$1|sagunson iyo|$1 na mga sagunson iyo}} sa kategoryang ini, na ginahi sa $2 sa kabilogan.}}',
'category-file-count-limited' => 'An minasunod {{PLURAL:$1|na sagunson|$1 na mga sagunson}} yaon sa presenteng kategorya.',
'listingcontinuesabbrev' => 'sunód',
'index-category' => 'Hinukdoang mga pahina',
'noindex-category' => 'Bakong hinukdoang mga pahina',
'broken-file-category' => 'Mga pahina na igwang nagkaparasa na sagunsong kilyawan',

'about' => 'Manonongod',
'article' => 'Laog na pahina',
'newwindow' => '(minabukas sa bàgong bintanà)',
'cancel' => 'Kanselaron',
'moredotdotdot' => 'Kadagdagan...',
'mypage' => 'An sakóng pahina',
'mytalk' => 'An sakóng olay',
'anontalk' => 'Olay para kaining IP address',
'navigation' => 'Nabigasyon',
'and' => '&#32;asin',

# Cologne Blue skin
'qbfind' => 'Maghanap',
'qbbrowse' => 'Halungkáta',
'qbedit' => 'Liwata',
'qbpageoptions' => 'Ining pahina',
'qbpageinfo' => 'Konteksto',
'qbmyoptions' => 'Sakong mga pahina',
'qbspecialpages' => 'Espesyal na mga pahina',
'faq' => 'PPK (Pirmihang Pighahapot na mga kahaputan)',
'faqpage' => 'Project:PPK (Pirmihang Pighahapot na mga Kahaputan)',

# Vector skin
'vector-action-addsection' => 'Idagdag an topic',
'vector-action-delete' => 'puráon',
'vector-action-move' => 'Ibalyó',
'vector-action-protect' => 'Protektaran',
'vector-action-undelete' => 'Bawion sa pagkapara',
'vector-action-unprotect' => 'Ribayan an proteksyon',
'vector-simplesearch-preference' => 'Paganahon an pinapusog na suhestiyon sa paghahanap (Yanong panhanap sana)',
'vector-view-create' => 'Magmukna',
'vector-view-edit' => 'Liwatón',
'vector-view-history' => 'Tanawon sa historiya',
'vector-view-view' => 'Basáha',
'vector-view-viewsource' => 'Hilingón an ginikánan',
'actions' => 'Mga aksyon',
'namespaces' => 'Mga espasyong ngaran',
'variants' => 'Mga pinalaen',

'errorpagetitle' => 'Salâ',
'returnto' => 'Magbalik sa $1.',
'tagline' => 'Gikan sa {{SITENAME}}',
'help' => 'Katabangan',
'search' => 'Maghanap',
'searchbutton' => 'Maghanap',
'go' => 'Dumani',
'searcharticle' => 'Lakaw',
'history' => 'Historiya kan pahina',
'history_short' => 'Historiya',
'updatedmarker' => 'dinagdagan poon kan sakong huring pagbisita',
'printableversion' => 'Puwede maimprintang bersyon',
'permalink' => 'Permanenteng kilyawan',
'print' => 'Imprintaron',
'view' => 'Tanawon',
'edit' => 'Liwatón',
'create' => 'Muknaon',
'editthispage' => 'Liwata ining pahina',
'create-this-page' => 'Muknaon ining pahina',
'delete' => 'Puraon',
'deletethispage' => 'Puraon ining pahina',
'undelete_short' => 'Bawia an pagpurà kan {{PLURAL:$1|sarong pagliwat|$1 mga pagliwat}}',
'viewdeleted_short' => 'Hilngon {{PLURAL:$1|sarong pinara na pagliwat|$1 mga pinara na pagliwat}}',
'protect' => 'Protektari',
'protect_change' => 'Ribayan',
'protectthispage' => 'Protektaran ining pahina',
'unprotect' => 'Ribayi an proteksyon',
'unprotectthispage' => 'Ribayi an proteksyon kaining pahina',
'newpage' => 'Bàguhong pahina',
'talkpage' => 'Orolayan ining pahina',
'talkpagelinktext' => 'Olay',
'specialpage' => 'Espesyal na Pahina',
'personaltools' => 'Personal na mga kagamitan',
'postcomment' => 'Baguhong seksyon',
'articlepage' => 'Tanawon an laog kan pahina',
'talk' => 'Orolayan',
'views' => 'Mga Tanawon',
'toolbox' => 'Kagamitang kahon',
'userpage' => 'Tanawon an pahina kan parágamit',
'projectpage' => 'Tanawon an pahina kan proyekto',
'imagepage' => 'Hilngón an pahina nin sagunson (file)',
'mediawikipage' => 'Tanawon an pahina kan mensahe',
'templatepage' => 'Tanawon an pahina kan panguyog',
'viewhelppage' => 'Tanawon an pahina nin katabangan',
'categorypage' => 'Tanawon an pahina nin kategorya',
'viewtalkpage' => 'Tanawon an orolayan',
'otherlanguages' => 'Sa ibang mga lengguwahe',
'redirectedfrom' => '(Piglikay halì sa $1)',
'redirectpagesub' => 'Ilikáy an pahina',
'lastmodifiedat' => 'Huring pigbàgo an pahinang iní $2 kan $1.',
'viewcount' => 'Binukasán ining pahina nin {{PLURAL:$1|sarong beses|nin $1 beses}}.',
'protectedpage' => 'Protektadong pahina',
'jumpto' => 'Magluksó sa:',
'jumptonavigation' => 'paglibotlibot',
'jumptosearch' => 'hanapon',
'view-pool-error' => 'Sori tabi, an mga server kargado sa oras na ini.
Grabe kadakol an mga paragamit na pinagprubaran mahiling an pahinang ini.
Makihalat tabi nin kadikit na panahon bago ka magprubara na makapaglaog sa pahinang ini.

$1',
'pool-timeout' => 'Timeout naghahalat para makapanugpon',
'pool-queuefull' => 'An grupong panproseso panoon',
'pool-errorunknown' => 'Bakong bistadong sala',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Dapít sa {{SITENAME}}',
'aboutpage' => 'Project:Manonongód',
'copyright' => 'Makukua an laog sa $1.',
'copyrightpage' => '{{ns:project}}:Mga derechos nin parásurat',
'currentevents' => 'Mga pangyayari sa ngunyán',
'currentevents-url' => 'Project:Mga pangyayari sa ngunyán',
'disclaimers' => 'Mga pagpabayà',
'disclaimerpage' => 'Project:Pangkagabsán na pagpabayà',
'edithelp' => 'Paghirá kan pagtabang',
'edithelppage' => 'Help:Pagliwát',
'helppage' => 'Help:Mga laóg',
'mainpage' => 'Panginot na Pahina',
'mainpage-description' => 'Panginot na Pahina',
'policy-url' => 'Project:Palakáw',
'portal' => 'Portal kan komunidad',
'portal-url' => 'Project:Portal kan Komunidad',
'privacy' => 'Palakáw nin pribasidad',
'privacypage' => 'Project:Palakaw nin pribasidad',

'badaccess' => 'Salang permiso',
'badaccess-group0' => 'Dai ka tinotogotan na gibohon an aksyon na saimong hinahagad.',
'badaccess-groups' => 'An aksyon na saimong pinaghahagad limitado sa mga parágamit na {{PLURAL:$2|an grupo|saro sa mga grupo}}: $1.',

'versionrequired' => 'Kaipuhan an bersyon $1 kan MediaWiki',
'versionrequiredtext' => 'Kaipuhan an bersyon $1 kan MediaWiki sa paggamit kan pahinang ini. Hilíngón an [[Special:Version|Bersyon kan pahina]].',

'ok' => 'Sige',
'retrievedfrom' => 'Pigkua sa "$1"',
'youhavenewmessages' => 'Igwa ka nin $1 ($2).',
'newmessageslink' => 'mga bàgong mensahe',
'newmessagesdifflink' => 'huring pagbàgo',
'youhavenewmessagesmulti' => 'Igwa ka nin mga bàgong mensahe sa $1',
'editsection' => 'liwatón',
'editold' => 'Liwatón',
'viewsourceold' => 'hilingón an ginikánan',
'editlink' => 'liwatón',
'viewsourcelink' => 'hilingón an toltólan',
'editsectionhint' => 'Liwatón an seksyon: $1',
'toc' => 'Mga laóg',
'showtoc' => 'ipahilíng',
'hidetoc' => 'tagóon',
'collapsible-collapse' => 'Pinahalipot',
'collapsible-expand' => 'Pinahalawig',
'thisisdeleted' => 'Hilingón o isulít an $1?',
'viewdeleted' => 'Hilingón an $1?',
'restorelink' => '{{PLURAL:$1|sarong pinarang paghirá|$1 na pinarang paghirá}}',
'feedlinks' => 'Hungit:',
'feed-invalid' => 'Bawal na tipo nin hungit na subkripsyon.',
'feed-unavailable' => 'Mayò an mga sindikasyon na hungit',
'site-rss-feed' => '$1 Hungit nin RSS',
'site-atom-feed' => '$1 Hungit nin Atomo',
'page-rss-feed' => '"$1" Hungit na RSS',
'page-atom-feed' => '"$1" Hungit na Atomo',
'feed-atom' => 'Atomo',
'red-link-title' => '$1 (an pahina bako pang eksistido)',
'sort-descending' => 'Suysoy paibaba',
'sort-ascending' => 'Suysoy paitaas',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pahina',
'nstab-user' => 'Pahina nin paragamít',
'nstab-media' => 'Pahina kan media',
'nstab-special' => 'Espesyal na pahina',
'nstab-project' => 'Pahina kan proyekto',
'nstab-image' => 'File',
'nstab-mediawiki' => 'Mensahe',
'nstab-template' => 'Templato',
'nstab-help' => 'Pahina kan tabang',
'nstab-category' => 'Kategorya',

# Main script and global functions
'nosuchaction' => 'Mayong siring na aksyon',
'nosuchactiontext' => 'An aksyon na pinanungdan kan kilyawan sarong imbalido.
Baka napasala ka sa pagsurat kan kilyawan, o nagsunod nin salang kilyawan.
Ini minapanungod man nin sarong kubol (bug) sa ginagamit na software kan {{SITENAME}}.',
'nosuchspecialpage' => 'Mayong siring na espesyal na páhina',
'nospecialpagetext' => '<strong>Dai pwede an pahinang espesyal na pinilî mo.</strong>

Pwede mong mahiling an lista nin mga marhay na pahina sa [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Salâ',
'databaseerror' => 'Salâ sa base nin datos',
'dberrortext' => 'Sarong datos-sarayan na may napasalang sintaks an nangyari.
Ini puwedeng minapanungod nin sarong kubol (bug) sa software.
An pinakahuring pagprubar sa datos-sarayan naghahapot nin:
<blockquote><tt>$1</tt></blockquote>
hale sa laog kan punksyon na "<tt>$2</tt>".
An datos-sarayan nagbalik nin sala na "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Sarong datos-sarayan na may napasalang sintaks an nangyari.
An pinakahuring pagprubar sa datos-sarayan naghahapot nin:
"$1"
na hale sa laog kan punksyon na "$2".
An datos-sarayan nagbalik nin sala na "<tt>$3: $4</tt>".',
'laggedslavemode' => 'Patanid: An pahina pwedeng dai nin pagbabâgo sa ngonyan.',
'readonly' => 'Kandado an base nin datos',
'enterlockreason' => 'Magkaag tabì nin rason sa pagkandado, asin ikalkulo kun nuarin bubukasón an kandado',
'readonlytext' => 'Sarado mùna an base nin datos sa mga bàgong entrada asin iba pang mga pagribay, pwede gayod sa rutinang pagmantenir kan base nin datos, despues, mabalik na ini sa normal.

Ini an eksplikasyon kan tagamató na nagkandado kaini: $1',
'missing-article' => 'An datos-sarayan dae nakanagbo kan teksto nin sarong pahina na dapat kuta nang managboan, pinagngaran na "$1" S2.

Ini pirmeng pinagkakausa sa paagi nin pagsusunod nin sarong lumang diff o historiyang kilyawan na yaon sa sarong pahinang pinagpura na.

Kun iyo ini an kaso, ika nakanagbo nin sarong kubol (bug) sa software.
Pakireport tabi ini sa [[Special:ListUsers/sysop|administrador]], na naka-antabay sa kilyawan.',
'missingarticle-rev' => '(pagbàgo#: $1)',
'missingarticle-diff' => '(Kaibhán: $1, $2)',
'readonly_lag' => 'Tulostulos na pagkandado an base nin datos mantang makaabot an base nin datos na esklabo saiyang amo.',
'internalerror' => 'Panlaog na salâ',
'internalerror_info' => 'Panlaog na salâ: $1',
'fileappenderrorread' => 'Dae nakakabasa nin "$1" habang pinagdadagdag.',
'fileappenderror' => 'Dae nakakapagdagdag nin "$1" sagkod "$2".',
'filecopyerror' => 'Dai naarog an mga file na "$1" hasta "$2".',
'filerenameerror' => 'Dai natàwan nin bàgong ngaran an file na "$1" sa "$2".',
'filedeleteerror' => 'Dai naparà an file na "$1".',
'directorycreateerror' => 'Dai nagibo an direktorya na "$1".',
'filenotfound' => 'Dai nahanap an file na "$1".',
'fileexistserror' => 'Dai maisurat sa file na "$1": igwa nang file na arog kaini',
'unexpected' => 'Dai pighuhunà na balór: "$1"="$2".',
'formerror' => 'Salâ: dai pwedeng isumitir an porma',
'badarticleerror' => 'Dai pwedeng gibohon ini sa ining páhina.',
'cannotdelete' => 'An pahina o an sagunson (file) na "$1" dae tabi napupura.
Ini puwede nang napura kan iba.',
'cannotdelete-title' => 'Dae mapura an pahina na "$1"',
'delete-hook-aborted' => 'An pagpura pinundo kan pangawit.
Ini dae nagtao nin kapaliwanagan.',
'badtitle' => 'Salâ an titulo',
'badtitletext' => 'Dai pwede an hinagad na titulo nin pahina, o mayong laog, o sarong titulong pan-ibang tatarámon o pan-ibang wiki na sala an pagkatakód. Pwedengigwa ining sarô o iba pang mga karakter na dai pwedeng gamiton sa mga titulo.',
'perfcached' => 'An minasunod na datos pinagtago asin bakong gayo napapanahon. An maximum na {{PLURAL:$1|sarong resulta na|$1 mga resulta na}} yaon sana sa pinagtago.',
'perfcachedts' => 'An minasunod na datos pinagtago, asin huring pinagdagdagan kan $1. An maximum na {{PLURAL:$4|sarong result na |$4 mga resulta na }} yaon sana sa pinagtago.',
'querypage-no-updates' => 'Pigpopogol mùna an mga pagbabàgo sa pahinang ini. Dai mùna mababàgo an mga datos digdi.',
'wrong_wfQuery_params' => 'Salâ na parámetro sa wfQuery()<br />
Acción: $1<br />
Hapót: $2',
'viewsource' => 'Hilingón an ginikanan',
'viewsource-title' => 'Hilnga an piggikanan para sa $1',
'actionthrottled' => 'An aksyon pinagpugulan',
'actionthrottledtext' => 'Bilang sarong pangontra sa spam, ika limitadong sanang himoon ining aksyon sa kadakulon na beses sa halipot sanang panahon, asin ika nakasobra na sa limitasyong ini.
Paki-otroha giraray sa nagkapirang minuto sana.',
'protectedpagetext' => 'An pahinang ini pigsará tangarig pogolon an paghirá.',
'viewsourcetext' => 'Pwede mong hilingón asin arógon an ginikanan kan pahinang ini:',
'viewyourtext' => "Saimong mahihiling asin makokopya an gikanan kan '''saimong mga pinagriliwat''' sa pahinang ini:",
'protectedinterface' => 'An pahinang ini nagtatao nin interface para sa software, asin sarado tangarig mapondo an pag-abuso.',
'editinginterface' => "'''Warning:''' Ika nagliliwat kan pahina na ginagamit sa pagtao nin pantahaw-olay na teksto para sa software.
An mga pagbabago kaining pahina makaka-apekto sa hitsura kan pantahaw-olay nin paragamit para sa iba man na paragamit.
Para sa mga pagdadakit-taramon, pakikonsidera man tabi an paggagamit kan [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], an MediaWiking lokalisasyon kan proyekto.",
'sqlhidden' => '(nakatagô an hapót nin SQL)',
'cascadeprotected' => 'Pinoprotehirán ining páhina sa mga paghirá, ta sarô ini sa mga minasunod na {{PLURAL:$1|páhina|mga páhina}} na pinoprotehiran kan opsyón na "katarata" na nakabuká:
$2',
'namespaceprotected' => "Mayô kang permisong maghirá kan mga páhina sa '''$1''' ngaran-espacio.",
'customcssprotected' => 'Ika mayong permiso sa pagliwat kaining pahinang CSS, nin huli ta ini naglalaman kan personal na panuytoy (settings) kan ibang paragamit.',
'customjsprotected' => 'Ika mayong permiso sa pagliwat kaining pahinang JavaScript, nin huli ta ini naglalaman kan personal na panuytoy (settings) kan ibang paragamit.',
'ns-specialprotected' => 'An mga pahinang nasa {{ns:special}} na liang-liang dai pwedeng hirahón.',
'titleprotected' => 'Ining titulo pinagprotektaran poon pagkamukna ni [[User:$1|$1]].
An rason na pinagtao iyo na "\'\'$2\'\'".',
'filereadonlyerror' => 'Dae kinayang baguhon an sagunson (file) "$1$ nin huli ta an repositoryo kan sagunson "$2" yaon sa kamugtakan na basahon sana.

An administrador na iyo an nagkandado kaini nagpahayag kaining kapaliwanagan: "$3".',
'invalidtitle-knownnamespace' => 'Imbalidong titulo na igwang espasyadong ngaran na "$2" asin teksto na "$3"',
'invalidtitle-unknownnamespace' => 'Imbalidong titulo na igwang nin bakong bistado na bilang kan espasyadong ngaran na $1 asin teksto na "$2"',
'exception-nologin' => 'Dae ka nakalaog',
'exception-nologin-text' => 'Ining pahina o aksyon minakaipo saimo na maglaog kaining wiki.',

# Virus scanner
'virus-badscanner' => "Raot na kasalansanan: Bakong bistadong virus scanner: ''$1''",
'virus-scanfailed' => 'An paghingipid (scan) nagpalya (Koda $1)',
'virus-unknownscanner' => 'bakong bistadong antivirus:',

# Login and logout pages
'logouttext' => "'''Ika po sa ngunyan nakaluwas na.'''

Ika makakadagos pa sa paggamit kan {{SITENAME}} na dai nagpapabisto, o ika [[Special:UserLogin|Maglaog giraray]] bilang pareho o bilang ibang paragamit.
Giromdoma na an ibang mga pahina mapuwedeng padagos na magpapahiling siring baga na kun ika garo yaon man sana sa laog, sagkod na saimong malinigan mo an sarayan sa kilyawan.",
'welcomecreation' => '== Maogmang Pag-abot, $1! ==
An saimong panindog (account) naimukna na tabi.
Dae ka man tabi malingaw na ribayan an saimong [[Special:Preferences|{{SITENAME}} mga kabôtan]].',
'yourname' => 'Pangaran kan paragamit:',
'yourpassword' => 'Pasa-taramon:',
'yourpasswordagain' => 'Pakilaog giraray kan sekretong panlaog:',
'remembermypassword' => 'Giromdoma an sakong paglaog sa kilyaw (browser) na ini (para sa maksimum na $1 {{PLURAL:$1|aldaw|mga aldaw}})',
'securelogin-stick-https' => 'Magpirmeng konektado sa HTTPS matapos kang maglaog',
'yourdomainname' => 'An saimong kasakupan:',
'externaldberror' => 'Igwa gayod sala sa arinman kan patunay sa datos-sarayan o ika dae pinagtugutan na bâgohon an saimong panluwas na panindog.',
'login' => 'Maglaog',
'nav-login-createaccount' => 'Maglaog / magmukna nin panindog',
'loginprompt' => 'Ika kaipong paganahon an mga cookies tanganing makalaog sa {{SITENAME}}.',
'userlogin' => 'Maglaog / magmukna nin panindog',
'userloginnocreate' => 'Maglaog ka',
'logout' => 'Magluwas',
'userlogout' => 'Magluwás',
'notloggedin' => 'Dae ka nakalaog',
'nologin' => 'Mayò ka pa nin panindog (account)? $1.',
'nologinlink' => 'Magmukna nin panindog',
'createaccount' => 'Magmukna nin panindog',
'gotaccount' => 'Igwa ka na tabi nin panindog? $1.',
'gotaccountlink' => 'Maglaog',
'userlogin-resetlink' => 'Nakalingaw ka sa panlaog mong detalye?',
'createaccountmail' => 'Sa paagi nin e-koreo',
'createaccountreason' => 'Rason:',
'badretype' => 'An mga sekretong panlaog mong pinagtatak bakong pareho.',
'userexists' => 'Paragamit na ngarang piglaog may naggagamit na.
Pakipili nin ibang ngaran tabi.',
'loginerror' => 'An paglaog napasalâ',
'createaccounterror' => 'Dae tabi maimukna an panindog: $1.',
'nocookiesnew' => 'An panindog kan paragamit namukna na, pero ika dae pa tabi nakalaog.
{{SITENAME}} naggagamit nin cookies tanganing makalaog an mga paragamit.
Ika igwang mga cookies na dae pinagana.
Tabi paganaha sinda, dangan maglaog ka sa saimong baguhon na pangaran kan paragamit asin sekretong panlaog.',
'nocookieslogin' => '{{SITENAME}} naggagamit nin mga cookies para sa maglaog na mga paragamit.
Ika igwang mga cookies na dae pinagana.
Tabi paganaha sinda asin otroha giraray.',
'nocookiesfornew' => 'An panindog kan paragamit dae pinagmukna, nin huli ta dae nyamo kumpirmado an pinaggikanan kaini.
Pakipaseguro na saimong pinagana an cookies, ikarga giraray ining pahina asin probaran mo otro.',
'noname' => 'Ika dae tabi nakapagkaag nin sarong balidong pangaran nin paragamit.',
'loginsuccesstitle' => 'Matrayumpo an saimong paglaog',
'loginsuccess' => "'''Ika ngunyan nakalaog na sa {{SITENAME}} bilang si \"\$1\".'''",
'nosuchuser' => 'Dae pang paragamit na ginagamit an pangaran na "$1".
An mga ngaran nin paragamit sensitibo gayo sa tipahan.
Pakireparo kan saimong espeling, o [[Special:UserLogin/signup|Magmukna nin bagong panindog]].',
'nosuchusershort' => 'Mayo po tabing paragamit na an pangaran "$1".
Paki-tsek an saimong espeling.',
'nouserspecified' => 'Kaipuhan mong magkaag nin sarong pangaran nin paragamit.',
'login-userblocked' => 'An paragamit na ini pinagkubkob. An paglaog dae pinagtutugutan.',
'wrongpassword' => 'Salâ an pigtaták na sekretong panlaog.
Tabi probaran giraray.',
'wrongpasswordempty' => 'An sekretong panlaog pinagtatak na blangko.
Tabi probaran giraray.',
'passwordtooshort' => 'Mga sekretong panlaog dapat igwa nin {{PLURAL:$1|1 karakter|$1 mga karakter}}.',
'password-name-match' => 'An saimong sekretong panlaog dapat laen sa saimong paragamit na ngaran.',
'password-login-forbidden' => 'An paggamit kaining pangaran nin paragamit asin sekretong panlaog pinagbabawal.',
'mailmypassword' => 'Paki-koreo an bagong sekretong panlaog',
'passwordremindertitle' => 'Bagong temporaryo na sekretong panlaog para sa {{SITENAME}}',
'passwordremindertext' => 'May sarong tawo (pwedeng ika gayod, gikan sa IP address na $1) naghagad nin sarong bagong sekretong panlaog para sa {{SITENAME}} ($4). Sarong temporaryong sekretong panlaog para sa paragamit "$2" an pinagmukna asin pinagtuytoy na magin "$3". Kun iyo ini an saimong katuyuhan, kaipuhan mong maglaog asin magpili nin sarong bagong sekretong panlaog ngunyan.
An saimong temporaryong sekretong panlaog mapapaso sa laog nin {{PLURAL:$5|sarong aldaw|$5 aldaw}}. 

Kun ibang tawo an naghimo kaining kahagadan, o kun saimo nang nagiromdoman an saimong sekretong panlaog, asin habo mo nang ribayan ini, ipasapara mo na sana an mensaheng ini asin ipadagos mo nang gamiton an saimong lumang sekretong panlaog.',
'noemail' => 'Mayo tabing e-koreong address na nakarehistro para sa paragamit na "$1".',
'noemailcreate' => 'Kaipuhan kang magtao nin sarong balidong address sa e-surat',
'passwordsent' => 'Sarong baguhon na sekretong panlaog an ipinadara sa e-koreong address na nakarehistro para ki "$1".
Tabi maglaog giraray matapos mong maresibe ini.',
'blocked-mailpassword' => 'An saimong IP address pinagkubkob na magliwat, asin kaya dae tinutugutan na gumamit kan pambawi nin sekretong panlaog na punksyon tanganing makalikay sa abuso.',
'eauthentsent' => 'Sarong e-koreong pankumpirmasyon an ipinadara sa nominadong e-koreong address.
Bago an ibang e-koreo ipinadara sa panindog, ika igwang pagsunudong na mga instruksyon na yaon sa e-koreo, tanganing kumpirmaron na an panindog tunay talagang saimo.',
'throttled-mailpassword' => 'Sarong pagiromdom kan sekretong panlaog an ipinadara na, sa laog nin {{PLURAL:$1|hour|$1 hours}}.
Tangarig malikayan an abuso, saro sanang pagiromdom kan sekretong panlaog an ipinapadara sa lambang {{PLURAL:$1|hour|$1 hours}}.',
'mailerror' => 'Salâ an pagpadará kan koreo: $1',
'acct_creation_throttle_hit' => 'Mga bisita kaining wiki na ginagamit an saimong IP address nagmukna nin {{PLURAL:$1|1 panindog|$1 mga panindog}} sa nakaaging aldaw, na iyo ngani an maximum na pinagtutugot sa laog kan peryodong panahon.
Bilang resulta, an mga bisita na naggagamit kaining IP address dae nguna makakamukna nin mga panindog.',
'emailauthenticated' => 'An saimong e-koreo awtentikado kan $2 sa $3.',
'emailnotauthenticated' => 'An saimong e-surat dae pa tabi pinagpatunayan. 
Mayong e-surat an ipapadara para sa arinman kan minasunod na estima.',
'noemailprefs' => 'Magkaag nin sarong e-koreong address sa saimong mga kabotan para gumana ining mga estima.',
'emailconfirmlink' => 'Kompirmaron tabî an saimong e-koreong address',
'invalidemailaddress' => 'An e-koreo dae akseptado habang ini minapahiling na igwa nin imbalidong panugmad.
Pakilaog sana tabi nin sarong tugmadong koreo o pabayae na mayong laman an suratan.',
'cannotchangeemail' => 'An panindog na address sa e-surat dati mariribayan sa Wiki na ini.',
'emaildisabled' => 'Ining sayt dae makakapagpadara nin mga e-surat.',
'accountcreated' => 'Panindog pinagmukna na',
'accountcreatedtext' => 'An paragamit na panindog para sa $1 pinagmukna na.',
'createaccount-title' => 'Panindog na pagmukna para sa {{SITENAME}}',
'createaccount-text' => 'May tawong nagmukna nin sarong panindog na gamit an saimong address na e-surat sa {{SITENAME}} ($4) na may ngaran na "$2, na may sikretong panlaog na "$3".',
'usernamehasherror' => 'Paragamit na ngaran dae puwede na igwang simbolikong mga kabtang',
'login-throttled' => 'Nakapaghimo ka na nin kadakulon na pagprubar sa paglaog dae pa sana nahaloy. Mapuwede lang po na maghalat bago magprubar giraray.',
'login-abort-generic' => 'An saimong paglaog dae nakadagos - Pinundo',
'loginlanguagelabel' => 'Lengguwahe: $1',
'suspicious-userlogout' => 'An hinahagad mong magluwas pinagpundo nin huli ta ini gayod pinagpadara sa paagi nin sarong pasang kilyaw o proksing hilom.',

# E-mail sending
'php-mail-error-unknown' => 'Bakong bantog na kasalaan sa PHP mail() function.',
'user-mail-no-addy' => 'Nagprubar na magpadara nin e-koreo na mayo nin e-koreong address.',

# Change password dialog
'resetpass' => 'Ribayan an sekretong panlaog',
'resetpass_announce' => 'Ika nakalaog na na igwang sarong temporaryong koda sa e-koreo.
Tanganing tapuson an paglalaog, ika kaipong magkaag nin sarong baguhon na sekretong panlaog digdi:',
'resetpass_text' => '<!-- Magdagdag nin teksto digdi -->',
'resetpass_header' => 'Ribayan an panindog na sekretong panlaog',
'oldpassword' => 'Dating sekretong panlaog:',
'newpassword' => 'Bàguhon na sekretong panlaog:',
'retypenew' => 'Itaták giraray an bàgong panlaog:',
'resetpass_submit' => 'Ipwesto an sekretong panlaog dangan maglaog',
'resetpass_success' => 'Naribayan na an saimong sekretong panlaog! Pigpapadagos ka na...',
'resetpass_forbidden' => 'An mga sekretong panlaog dae puwedeng maribayan',
'resetpass-no-info' => 'Ika dapat nakalaog na tanganing direktang makagamit kaining pahina.',
'resetpass-submit-loggedin' => 'Ribayan an sekretong panlaog',
'resetpass-submit-cancel' => 'I-kansela',
'resetpass-wrong-oldpass' => 'Saláng temporaryo o presenteng sekretong panlaog.
Matriumpo mo nang nailaog an sekretong panlaog o nakua an bàgong temporaryong sekretong panlaog.',
'resetpass-temp-password' => 'Temporaryong sekretong panlaog:',

# Special:PasswordReset
'passwordreset' => 'Pakibago kan sekretong panlaog',
'passwordreset-text' => 'Kumpletoha ining porma tanganing makaresibe nin pampagiromdom na e-koreo kan detalye nin saimong panindog.',
'passwordreset-legend' => 'Pakibago kan sekretong panlaog',
'passwordreset-disabled' => 'An pagbago kan sekretong panlaog pinagpundo sa wiking ini.',
'passwordreset-pretext' => '{{PLURAL:$1||Pakilaog kan saro sa mga pedaso nin datos sa ibaba}}',
'passwordreset-username' => 'Paragamit-ngaran:',
'passwordreset-domain' => 'Kasakupan:',
'passwordreset-capture' => 'Hilngon an kinaluwasang e-koreo?',
'passwordreset-capture-help' => 'Kun saimong i-tsek ini box, an e-koreo (na igwang temporaryong sekretong panlaog) ipapahiling saimo siring na ini ipagpapadara sa paragamit.',
'passwordreset-email' => 'E-koreong address:',
'passwordreset-emailtitle' => 'Mga detalye kan panindog sa {{SITENAME}}',
'passwordreset-emailtext-ip' => 'May sarong tawo (pwedeng ika gayod, gikan sa IP address na $1) naghagad nin sarong pagiromdom kan detalye kan saimong panindog para sa {{SITENAME}} ($4). An minasunod na paragamit {{PLURAL:$3|panindog iyo an|mga panindog iyo an}} na asosyado kaining e-koreong address:

$2

{{PLURAL:$3|Ining temporaryong sekretong panlaog|Ining mga temporaryong panlaog}} mapapaso sa {{PLURAL:$5|sarong aldaw|$5 mga aldaw}}.
Ika dapat na maglaog asin magpili nin sarong bagong sekretong panlaog ngunyan. Kun ibang tawo an naghimo kaining kahagadan, o kun saimo nang nagiromdoman an saimong orihinal na sekretong panlaog, asin habo mo nang ribayan ini, ipasapara mo na sana an mensaheng ini asin ipadagos mo nang gamiton an saimong lumang sekretong panlaog.',
'passwordreset-emailtext-user' => 'Paragamit $1 sa {{SITENAME}} naghahagad nin sarong pagiromdom kan detalye nin saimong panindog para sa {{SITENAME}}
($4). An minasunod na paragamit {{PLURAL:$3|panindog iyo an|mga panindog iyo an}} na asosyado kaining e-koreong address:

$2


{{PLURAL:$3|Ining temporaryong sekretong panlaog|Ining mga temporaryong panlaog}} mapapaso sa {{PLURAL:$5|sarong aldaw|$5 mga aldaw}}.
Ika dapat na maglaog asin magpili nin sarong bagong sekretong panlaog ngunyan. Kun ibang tawo an naghimo kaining kahagadan, o kun saimo nang nagiromdoman an saimong orihinal na sekretong panlaog, asin habo mo nang ribayan ini, ipasapara mo na sana an mensaheng ini asin ipadagos mo nang gamiton an saimong lumang sekretong panlaog.',
'passwordreset-emailelement' => 'Paragamit-ngaran: $1
Temporaryong sekretong panlaog: $2',
'passwordreset-emailsent' => 'Sarong pampagiromdom na e-koreo ipinadara na.',
'passwordreset-emailsent-capture' => 'Sarong pampagiromdom na e-koreo ipinadara na, ipinapahiling sa may ibaba.',
'passwordreset-emailerror-capture' => 'Sarong pampagiromdom na e-koreo pinaghimo na, ipinapahiling sa may ibaba, alagad an pagpapadara kaini sa paragamit nagpalya: $1',

# Special:ChangeEmail
'changeemail' => 'Ribayan an e-koreong address',
'changeemail-header' => 'Ribayan an panindog na e-koreong address',
'changeemail-text' => 'Kumpletoha ining porma tanganing ribayan an saimong e-koreong address. Kinakaipo mong ilaog an saimong sekretong panlaog tanganing kumpirmaron ining pagribay.',
'changeemail-no-info' => 'Ika dapat nakalaog na tanganing direktang makagamit kaining pahina.',
'changeemail-oldemail' => 'Presenteng e-koreong address:',
'changeemail-newemail' => 'Bagong e-koreong address:',
'changeemail-none' => 'mayo tabi.',
'changeemail-submit' => 'Ribayan an e-koreo',
'changeemail-cancel' => 'Kanselaha',

# Edit page toolbar
'bold_sample' => 'Tekstong mahìbog',
'bold_tip' => 'Mahìbog na teksto',
'italic_sample' => 'Tekstong Itáliko',
'italic_tip' => 'Tekstong patagilíd',
'link_sample' => 'Titulo nin takod',
'link_tip' => 'Panlaog na takod',
'extlink_sample' => 'http://www.example.com títulong nakatakod',
'extlink_tip' => 'Panluwas na takod (giromdomon an http:// na prefiho)',
'headline_sample' => 'Tekstong pamayohan',
'headline_tip' => 'Tangga ika-2 na pamayohan',
'nowiki_sample' => "Isaliot digdi an tekstong dai na-''format''",
'nowiki_tip' => "Dai pagindiendehon pag-''format'' kan wiki",
'image_sample' => 'Halimbawa.jpg',
'image_tip' => 'Nakalubog na sagunson',
'media_sample' => 'Halimbawa.ogg',
'media_tip' => 'Kilyaw nin sagunson (file)',
'sig_tip' => 'Pirma mo na may taták nin oras',
'hr_tip' => 'Pabalagbag na linya (use sparingly)',

# Edit pages
'summary' => 'Sumada:',
'subject' => 'Tema/pamayohan:',
'minoredit' => 'Sadit na paghirá ini',
'watchthis' => 'Bantayan an pahinang ini',
'savearticle' => 'Itagáma an pahina',
'preview' => 'Tànawón',
'showpreview' => 'Hilingón an patànaw',
'showlivepreview' => 'Patànaw na direkto',
'showdiff' => 'Hilingón an mga pagbabàgo',
'anoneditwarning' => "'''Patanid:''' Dai ka nakalaog. Masusurat an saimong IP sa uusipón kan pagbabàgo kan pahinang ini.",
'anonpreviewwarning' => 'Dae ka tabi nakalaog. An pagtatagama matala kan saimong IP address sa historya nin pagliwat sa pahinang ini.',
'missingsummary' => "'''Paisi:''' Dai ka nagkaag nin sumád kan paghirâ. Kun pindotón mo giraray an Itagama, maitatagama an hirá mo na mayô kaini.",
'missingcommenttext' => 'Paki lâgan nin komento sa ibabâ.',
'missingcommentheader' => "'''Pagiromdom:''' Ika dae tabi nagtao nin sarong panultol (subject)/Pamayong linya (headline) para kaining sinambit mo.
Kun saimong pinduton an \"{{int:savearticle}}\" giraray, an saimong pigliwat matatagama na mayo kaiyan.",
'summary-preview' => 'Patànaw nin sumada:',
'subject-preview' => 'Patânaw nin tema/pamayohan:',
'blockedtitle' => 'Pigbágat an parágamit',
'blockedtext' => "'''An saimong paragamit na ngaran o IP address pinagkubkob.'''

An pagkubkob hinimo ni $1.
An rason na ipinagtao iyo na ''$2''.

* Pagpoon kan pagkubkob: $8
* Pagpasó kan pagkubkob: $6
* Katuyuhan kan parakubkob: $7

Ika puwedeng magkontak sa $1 or ibang [[{{MediaWiki:Grouppage-sysop}}|administrador]] tanganing pag-orolayan an pagkubkob.
Ika dae makakagamit kan 'e-koreo kaining paragamit' na panuytuyan laen lang na may sarong balidong e-koreo address na ipinahayag sa saimong [[Special:Preferences|panindog na mga kabotan]] asin ika dae pinagkubkob para sa paggamit kaini.
An saimong presenteng IP address iyo $3, asin an kubkob ID iyo #$5.
Pakibale na lang tabi an gabos na mga detalye sa itaas sa anuman na mga kahaputan na saimong himoon.",
'autoblockedtext' => 'An saimong IP address awtomatikong pinagkubkob nin huli ta ini pinaggamit kan ibang paragamit, na pinagkubkob ni $1.
An rason na ipinagtao iyo:

:\'\'$2\'\'

* Pagpoon kan pagkubkob: $8
* Pagpasó kan pagkubkob: $6
* Katuyuhan kan parakubkob: $7

Puwede mong kontakon si $1 o saro sa [[{{MediaWiki:Grouppage-sysop}}|mga administrador] tanganing pag-orolayan an kubkob.

Patanid tabi dae mo puwedeng gamiton an "e-koreo kaining paragamit" estima laen lang kun ika igwa nin sarong balidong e-koreo address na rehistrado sa saimong [[Special:Preferences|paragamit na mga kabotan]] asin ika dae pinagkubkob para sa paggamit kaini.

An saimong presenteng IP address iyo an $3, asin and Kubkob ID iyo an #$5.
Pakibale tabi an gabos na mga detalye sa itaas sa anuman na mga kahaputan na saimong himoon.',
'blockednoreason' => 'mayong itinaong rason',
'whitelistedittext' => 'Kaipuhan mong $1 tangarig makahirá nin mga páhina.',
'confirmedittext' => "Kaipuhan mong kompirmaron an saimong ''e''-surat. Ipwesto tabî asin patunayan an saimong ''e''-surat sa [[Special:Preferences|mga kabôtan kan parágamit]].",
'nosuchsectiontitle' => 'Dae managboan an seksyon',
'nosuchsectiontext' => 'Ika nagprubar na liwaton an sarong seksyon na bakong eksistido.
Ini puwedeng pinagbalyo o pinagpara na habang saimong pinaghihiling an pahina.',
'loginreqtitle' => 'Kaipuhan Maglaog',
'loginreqlink' => 'maglaog',
'loginreqpagetext' => 'Kaipuhan kang $1 tangarig makahilíng nin ibang pahina.',
'accmailtitle' => 'Napadará na an sekretong panlaog.',
'accmailtext' => "An patsambang pagpuyos kan sekretong panlaog para ki [[User talk:$1|$1]] ipinagpadara na ki $2.

An sekretong panlaog para sa bagong panindog mapuwede tabing maribayan ''[[Special:ChangePassword|Ribayan an sekretong panlaog]]'' na pahina matapos na makalaog.",
'newarticle' => '(Bàgo)',
'newarticletext' => 'Sinunod mo an takod sa pahinang mayò pa man.
Tangarig magibo an pahina, magpoon pagsurat sa kahon sa babâ
(hilingón an [[{{MediaWiki:Helppage}}|pahina nin tabang]] para sa iba pang impormasyon).
Kun dai tinuyong nakaabot ka digdi, pindoton sana an back sa browser mo.',
'anontalkpagetext' => "----''Ini iyo an pahina kan orolayan para an sarong dae bistadong paragamit na dae pa nakapagmukna nin panindog, o dae pa nakapaggamit kaini.
Kaya kami kaipong gumamit nin numerikal na IP address sa pagbisto saiya.
An arog kaining IP address puwedeng maikapagheras sa nagkapirang mga paragamit.
Kun ika sarong dae pa bistadong paragamit asin mati mo na igwang irelebanteng sambit na pinanungod saimo, tabi paki [[Special:UserLogin/signup|mukna nin panindog]] or [[Special:UserLogin|maglaog ka]] tanganing malikayan an pagkaribong sa pag-iriba kan iba pang mga paragamit.''",
'noarticletext' => 'Mayo tabi sa presente nin teksto sa pahinang ini.
Ika mapuwedeng [[Special:Search/{{PAGENAME}}|maghanap para sa titulo kan pahinang ini]] sa iba pang mga pahina,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} maghanap sa magkasurundong mga talaan],
o [{{fullurl:{{FULLPAGENAME}}|action=edit}} liwaton ining pahina]</span>.',
'noarticletext-nopermission' => 'Mayo tabi sa presente nin teksto sa pahinang ini.
Ika mapuwedeng [[Special:Search/{{PAGENAME}}|maghanap para sa titulo kan pahinang ini]] sa iba pang mga pahina,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} maghanap sa magkasurundong mga talaan]</span>.',
'userpage-userdoesnotexist' => 'Paragamit na panindog "$1" bako tabing rehistrado.
Paki-tsek kun ika magustong magmukna/magliwat kaining pahina.',
'userpage-userdoesnotexist-view' => 'Paragamit na panindog "$1" bako tabing rehistrado.',
'blocked-notice-logextract' => 'Ining paragamit sa presente nakakubkob.
An pinakahuring entrada kan pagkubkob nakahaya sa ibaba bilang reperensiya:',
'clearyourcache' => "'''Note:''' Matapos maitagama, ika mapuwedeng makaluktos sa tagoan kan saimong kilyaw tanganing mahiling an mga naribayan.
* '''Firefox / Safari:''' Pauntok na duon sa ''Shift'' habang pinipindot an ''Ikarga otro'', o pinduton as maski arin sa ''Ctrl-F5'' o ''Ctrl-R'' (''⌘-R'' para sa Mac)
* '''Google Chrome:''' Pinduton ''Ctrl-Shift-R'' (''⌘-Shift-R'' para sa Mac)
* '''Internet Explorer:''' Pauntok na duon sa ''Ctrl'' habang pinipindot an ''Ipresko otro'', o pinduton an ''Ctrl-F5''
* '''Konqueror:''' Ipindot an ''Ikarga otro'' o pinduton an ''F5''
* '''Opera:''' Linigan an tagoan sa ''Tools → Mga Kabotan''",
'usercssyoucanpreview' => "'''Tip:''' Gamita an \"{{int:showpreview}}\" na pindutan tanganing prubaran an saimong baguhong CSS bago ipagtagama.",
'userjsyoucanpreview' => "'''Tip:''' Gamita an \"{{int:showpreview}}\" na pindutan tanganing prubaran an saimong baguhong JavaScript bago ipagtagama.",
'usercsspreview' => "'''Giromdoma baya na ika nagtatanaw pa sana kan saimong paragamit sa CSS.'''
'''Ini dae pa tabi naitatagama!'''",
'userjspreview' => "'''Giromdomon tabi na pigtetest/pighihiling mo sana an patanaw kan saimong JavaScript nin paragamit, dai pa ini naitagama!'''",
'sitecsspreview' => "'''Giromdoma baya na ika nagtatanaw pa sana kaining CSS.'''
'''Ini dae pa tabi naitatagama!'''",
'sitejspreview' => "'''Giromdoma baya na ika nagtatatanaw pa sana kaining koda sa JavaScript.'''
'''Ini dae pa tabi naitatagama!'''",
'userinvalidcssjstitle' => "'''Patanid:''' Mayong ''skin'' na \"\$1\". Giromdomon tabî na an .css asin .js na mga páhina naggagamit nin titulong nakasurat sa sadit na letras, halimbawa {{ns:user}}:Foo/vector.css bakong {{ns:user}}:Foo/Vector.css.",
'updated' => '(Binàgo)',
'note' => "'''Paisi:'''",
'previewnote' => "'''Giromdoma na ini sarong patanaw pa sana.'''
An saimong mga pinagriliwat dae pa tabi naitatagama!",
'continue-editing' => 'Ipagpadagos an pagliliwat',
'previewconflict' => 'Mahihilíng sa patànaw na ini an tekstong nasa itaas na lugar nin paghirá arog sa maipapahiling kun ini an itatagama mo.',
'session_fail_preview' => "'''Despensa! Dai mi naipadagos an paghirá mo huli sa pagkawara nin datos kan sesyon.
Probaran tabì giraray. Kun dai man giraray magibo, probaran na magluwas dangan maglaog giraray.'''",
'session_fail_preview_html' => "'''Sori po! Dae tabi nyamo maiproseso an saimong pagliwat nin huli sa kawaraan kan datos sa sesyon.'''

''Nin huli ta {{SITENAME}} igwa nin bakong pang naprosesong HTML pinagpagana, an patanaw ipinagtago bilang pag-ingat kontra sa atake kan JavaScript.''

'''Kun ini sarong lehitimong pagprubar nin pagliwat, paki-otro tabi giraray.'''
Kun ini dae man giraray guminana, magprubar na [[Special:UserLogout|magluwas]] asin maglaog giraray.",
'token_suffix_mismatch' => "'''Dai pigtogotan an paghirá mo ta sinabrit kan client mo an punctuation characters.
Dai pigtogotan ining paghirá tangarig maibitaran na maraot an teksto kan pahina.
Nanyayari nanggad ini kun naggagamit ka nin bakong maraháy asin dai bistong web-based proxy service.'''",
'edit_form_incomplete' => "'''An ibang mga parte kan porma nin pagliwat dae nakaabot sa serbidor; paki-dobleng mansay na an saimong mga pinagliwat bilog na yaon pa asin paki-otro giraray.'''",
'editing' => 'Pigliliwat an $1',
'creating' => 'Pinagmumukna an $1',
'editingsection' => 'Pighihira an $1 (seksyon)',
'editingcomment' => 'Pigliliwat an $1 (bagong seksyon)',
'editconflict' => 'Komplikto sa paghihira: $1',
'explainconflict' => "May ibang parágamit na nagbàgo kaining pahina kan pagpoon mong paghirá kaini.
Nahihilíng ang pahina kan teksto sa parteng itaas kan teksto.
An mga pagbabàgo mo nahihilíng sa parteng ibabâ kan teksto.
Kaipuhan mong isalak an mga pagbabàgo mo sa presenteng teksto.
An teksto na nasa parteng itaas '''sana''' an maitatagama sa pagpindot mo kan \"{{int:savearticle}}\".",
'yourtext' => 'Saimong teksto',
'storedversion' => 'Itinagamang bersyon',
'nonunicodebrowser' => "'''PATANID: An browser mo bakong unicode complaint. Igwang temporariong sistema na nakaandar para makahirá ka kan mga pahina: mahihiling an mga karakter na non-ASCII sa kahon nin paghirá bilang mga kodang hexadecimal.'''",
'editingold' => "'''PATANID: Pighihirá mo an pasó nang pagpakaraháy kaining pahina.
Kun itatagama mo ini, mawawarà an mga pagbabàgong nagibo poon kan pagpakaraháy kaini.'''",
'yourdiff' => 'Mga kaibahán',
'copyrightwarning' => "Giromdomon tabì na an gabos na kontribusyon sa {{SITENAME}} pigkokonsiderar na $2 (hilingón an $1 para sa mga detalye). Kun habò mong mahirá an saimomg sinurat na mayong pakimàno, dai tabì iyan isumiter digdi.<br />
Pigpropromesa mo man samuyà na ika an kagsurat kaini, o kinopya mo ini sa dominiong panpubliko o sarong parehong libreng rekurso (hilingón an $1 para sa mga detalye).
'''DAI TABÌ MAGSUMITIR NIN MGA GIBONG IPINAPANGALAD NA KOPYAHON NIN MAYONG PERMISO!'''",
'copyrightwarning2' => "Giromdomon tabì na an gabos na kontribusyon sa {{SITENAME}} pwedeng hirahón, bàgohon o halion kan ibang mga parágamit. Kun habô mong mahirá an saimomg sinurat na mayong pakimàno, pues, dai tabì isumitir iyan digdi.<br />
Pigpapangakò mo man samuyà na ika an nagsurat kaini, o pigkopya mo ini sa dominiong panpubliko o sarong parehong libreng rekurso (hilingon an $1 para sa mga detalye). '''DAI TABÌ MAGSUMITIR NIN MGA GIBONG IPINAPANGALAD NA KOPYAHON NIN MAYONG PERMISO!'''",
'longpageerror' => "'''Ay Sala: An teksto na saimong pinagsumite {{PLURAL:$1|sarong kilobyte|$1 kilobytes}} an laba, mas halaba kesa maksimum na {{PLURAL:$2|sarong kilobyte|$2 kilobytes}}.'''
Ini dae tabi maitatagama.",
'readonlywarning' => "'''Patanid tabi: An datos-sarayan nakakandado para sa maintenance, kay ika dae makakapagtagama kan saimong mga pinagriliwat sa ngunyan.'''
Ika mapuwedeng maikopya an teksto pasiring sa sarong sagunson kan teksto asin para itagama ini sa huri.

An administrador na iyo an nagkandado kaini naghayag kaining kapaliwanagan: $1",
'protectedpagewarning' => "'''Patanid tabi: Ining pahina pinagprotektaran tanganing an mga paragamit sana na igwang pribilihiyo bilang administrador an makakapagliwat kaini.'''
An pinakahuring entrada sa talaan pinaghaya sa ibaba bilang reperensiya:",
'semiprotectedpagewarning' => "'''Note:''' Ining pahina pinagprotektaran na tanganing an mga rehistradong mga paragamit sana an mapuwedeng makapagliwat kaini.
An pinakahuring entrada sa talaan pinaghaya sa ibaba bilang reperensiya:",
'cascadeprotectedwarning' => "'''Patanid:''' Nakakandado an pahinang ini tangarig an mga parágamit na igwang pribilehyo nin sysop sana an pwedeng maghirá kaini, huli ta kabali ini sa mga kataratang protektado na {{PLURAL:$1|pahina|mga pahina}}:",
'titleprotectedwarning' => "'''Patanid tabi: Ining pahina pinagprotektaran na tanganing [[Special:ListGroupRights|espesipikong karapatan]] minakaipo tanganing magmukna kaini.'''
An pinakahuring entrada sa talaan pinaghaya sa ibaba bilang reperensiya:",
'templatesused' => '{{PLURAL:$1|Template|Mga Panguyog}} na pinaggamit kaining pahina:',
'templatesusedpreview' => '{{PLURAL:$1|Template|Mga Panguyog}} na pinaggamit kaining patanaw:',
'templatesusedsection' => '{{PLURAL:$1|Template|Mga Panguyog}} na pinaggamit kaining seksyon:',
'template-protected' => '(protektado)',
'template-semiprotected' => '(semi-protektado)',
'hiddencategories' => 'Ining pahina sarong miyembro kan {{PLURAL:$1|1 pinagtagong kategorya|$1 pinagtagong mga kategorya}}:',
'edittools' => '<!-- An teksto digdi mahihiling sa babâ kan mga pormang pighihirá asin pigkakarga. -->',
'nocreatetitle' => 'Limitado an paggibo nin pahina',
'nocreatetext' => '{{SITENAME}} pinagpupugol an kakayanan na magmukna nin baguhong mga pahina.
Ika makakabalik asin magliwat kan eksistidong nang pahina, o [[Special:UserLogin|maglaog ka o magmukna nin sarong panindog]].',
'nocreate-loggedin' => 'Ika mayo tabi nin permiso tanganing magmukna nin baguhong mga pahina.',
'sectioneditnotsupported-title' => 'An pagliliwat tabi sa seksyon bakong suportado',
'sectioneditnotsupported-text' => 'An pagliliwat tabi sa seksyon bakong suportado sa pahinang ini.',
'permissionserrors' => 'Mga saláng Permiso',
'permissionserrorstext' => 'Mayò ka nin permiso na gibohon yan, sa minasunod na {{PLURAL:$1|rason|mga rason}}:',
'permissionserrorstext-withaction' => 'Ika mayo tabi nin permiso sa $2, para sa minasunod na {{PLURAL:$1|rason|mga rason}}:',
'recreate-moveddeleted-warn' => "'''Patanid tabi: Saimong pinagmumukna giraray an sarong pahina na dati nang pinagpura.'''

Saimo tabing ikonsidera kun ini maninigo na ipagpadagos pa an pagliliwat kaining pahina.
An pagpura asin pagbalyong talaan para sa pahinang ini pinaghaya digde para sa kombenyinsiya:",
'moveddeleted-notice' => 'Ining pahina pinagpura na.
An pagpura asin pagbalyong talaan para sa pahina pinaghaya sa ibaba bilang reperensiya.',
'log-fulllog' => 'Tanawon an bilog na talaan',
'edit-hook-aborted' => 'An pagliwat pinagpundo sa paagi kan pangawil.
Ini dae tabi nagtao nin kapaliwanagan.',
'edit-gone-missing' => 'Dae makakapagdagdag sa pahina.
Ini minapahiwatig tabi na pinagpura na.',
'edit-conflict' => 'Igwang iregularidad sa pagliwat.',
'edit-no-change' => 'An saimong pagliwat pinagbalewala, nin huli ta mayong pagbabago an pinaghimo sa teksto.',
'edit-already-exists' => 'Dai maggibo an bàgong pahina.
Igwa na kaini.',
'defaultmessagetext' => 'Tugmadong mensahe sa teksto',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Patanid tabi:''' Ining pahina naglalaman nin grabe kadakulon na ekspensibong programang pambaranga sa punksyon nin mga pag-aapod.

Ini dapat magkaigwa nin menos sanang $2 {{PLURAL:$2|apod|mga apod}}, igwa na {{PLURAL:$1|ngunyan nin $1 apod|ngunyan nin $1 mga apod}}.",
'expensive-parserfunction-category' => 'Mga pahina na igwa nin grabe kadakulon na mga ekspensibong programang pambaranga sa punksyon nin mga pag-aapod',
'post-expand-template-inclusion-warning' => "'''Patanid tabi:''' An panguyog (template) igwang sukol na grabe kadakula.
An ibang mga panguyog dae tabi maipagdadagdag.",
'post-expand-template-inclusion-category' => 'Mga pahina kun saen an panguyog igwang sukol na sobrado',
'post-expand-template-argument-warning' => "'''Patanid tabi:''' Ining pahina naglalaman baya nin sarong panguyog na igwang grabe kadakulang kalakbangan sa sukol.
Ining mga argumento tabi pinagharali na.",
'post-expand-template-argument-category' => 'Mga pahina na naglalaman kan pinagharaleng mga argumento sa panguyog',
'parser-template-loop-warning' => 'An kaluktosan kan panguyog namansayan: [[$1]]',
'parser-template-recursion-depth-warning' => 'An panguyog nin rekursyong panrarom na kasagkodan nagsobra nin ($1)',
'language-converter-depth-warning' => 'Tagapagbago kan Lengguwaheng panrarom na kasagkodan nagsobra ($1)',
'node-count-exceeded-category' => 'Mga pahina kun saen an kabilangan nin tagapagsumpay nagsobra',
'node-count-exceeded-warning' => 'An pahina nagsobra an kabilangan nin tagapagsumpay',
'expansion-depth-exceeded-category' => 'Mga pahina kun saen an panrarom na kalakbangan nagsobra',
'expansion-depth-exceeded-warning' => 'An pahina nagsobra sa panrarom na kalakbangan',
'parser-unstrip-loop-warning' => 'Panul-ot na kaluktusan namansayan',
'parser-unstrip-recursion-limit' => 'Panul-ot na rekusyong kasagkodan nagsobra ($1)',

# "Undo" feature
'undo-success' => 'Pwedeng bawion an paghirá. Sosogon tabì an pagkakaiba sa babâ tangarig maberipikár kun ini an boot mong gibohon, dangan itagama an mga pagbabàgo sa babâ tangarig tapuson an pagbawì sa paghirá.',
'undo-failure' => 'Dai napogol an paghirá ta igwa pang ibang paghirá sa tahaw na nagkokomplikto.',
'undo-norev' => 'An pagliwat dae tabi magigibo nin huli ta ini bakong eksistido o pinagpura na.',
'undo-summary' => 'Ibalik tabi an pinagbabago $1 sa paagi [[Special:Contributions/$2|$2]] ([[Paragamit na Olay:$2|olay]])',

# Account creation failure
'cantcreateaccounttitle' => 'Dai makagibo nin account',
'cantcreateaccount-text' => "An pagbukas nin account halì sa IP na ('''$1''') binágat ni [[User:$3|$3]].

''$2'' an rason na pigtao ni $3",

# History pages
'viewpagelogs' => 'Hilingón an mga usip para sa pahinang ini',
'nohistory' => 'Mayong paghirá nin uusipón sa pahinang ini.',
'currentrev' => 'Sa ngonyan na pagpakarháy',
'currentrev-asof' => 'Pinakahuring pagbabago kan $1',
'revisionasof' => 'Pagpakarháy sa $1',
'revision-info' => 'An pagpakarháy sa $1 ni $2',
'previousrevision' => '←Lumà pang pagpakarhay',
'nextrevision' => 'Mas bàgong pagpakarháy→',
'currentrevisionlink' => 'Sa ngonyan na pagpakarháy',
'cur' => 'ngonyán',
'next' => 'sunod',
'last' => 'huri',
'page_first' => 'enot',
'page_last' => 'huri',
'histlegend' => 'Kaib na pinili: markahán an mga kahon kan mga bersyon tangarig makomparar asin pindoton an enter o butones babâ.<br />
Legend: (ngonyan) = kaibhán sa ngonyan na bersyon,
(huri) = kaibhán sa huring bersyon, S = saradít na paghirá.',
'history-fieldset-title' => 'Rinsayon an uusipon',
'history-show-deleted' => 'Pinagpura sana',
'histfirst' => 'Pinakaenot',
'histlast' => 'Pinakahúri',
'historysize' => '({{PLURAL:$1|sarong byte|$1 mga bytes}})',
'historyempty' => '(mayong laog)',

# Revision feed
'history-feed-title' => 'Uusipón kan pagpakaraháy',
'history-feed-description' => 'Uusipón kan pagpakaraháy para sa pahinang ini sa wiki',
'history-feed-item-nocomment' => '$1 sa $2',
'history-feed-empty' => 'Mayò man an hinágad na pahina.
Pwedeng pigparà na ini sa wiki, o tinàwan nin bàgong pangaran.
Probaran tabì an [[Special:Search|pighahanap sa wiki]] para sa mga pahinang dapít.',

# Revision deletion
'rev-deleted-comment' => '(pagliwat na sumaryo pinaghale)',
'rev-deleted-user' => '(hinalì an parágamit)',
'rev-deleted-event' => '(talaan kan aksyon pinaghale)',
'rev-deleted-user-contribs' => 'Paragamit na ngaran o IP address pinaghale - an pigliwat pinagtago gikan sa mga kontribusyon]',
'rev-deleted-text-permission' => "An pinagbago tabi kaining pahina '''pinagpura'''.
An mga detalye mananagboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan kan pinagpura].",
'rev-deleted-text-unhide' => "An pagbabago tabi kaining pahina '''pinagpura'''.
Mga detaylye puwedeng managboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan nin pagpura].
Ika mapuwedeng [$1 hilingon ining pagbabago] kun ika nagmawot na magpadagos.",
'rev-suppressed-text-unhide' => "An pagbabago kaining pahina '''pinaglubog'''.
Mga detalye mapuwedeng managboan sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} talaan kan paglubog].
Ika mapuwede pa man [$1 matanaw ining pagbabago] kun mawot na magdagos",
'rev-deleted-text-view' => "An pagbabago tabi kaining pahina '''pinagpura'''.
Ika mapuwedeng magtanaw kaini; mga detalye puwedeng managboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan kan pinagpura].",
'rev-suppressed-text-view' => "An pagbabago kaining pahina '''pinaglubog'''.
Ika mapuwedeng matanaw ini; mga detalye puwedeng managboan sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} talaan kan pinaglubog].",
'rev-deleted-no-diff' => "Ika dae makapagtanaw kaining diff nin huli ta saro kan mga pagbabago '''pinagpura'''.
Mga detalye puwedeng managboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan kan pinuraan].",
'rev-suppressed-no-diff' => "Ika dae makapagtanaw kaining diff nin huli ta saro sa mga pagbabago '''pinagpura'''.",
'rev-deleted-unhide-diff' => "Saro sa mga pagbabago kaining diff '''pinagpura'''.
Mga detalye mapuwedeng managboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan kan pinuraan].
Ika mapuwede pa [$1 magtanaw kaining diff] kun ika nagmawot na magpadagos.",
'rev-suppressed-unhide-diff' => "Saro sa mga pagbabago kaining diff '''pinaglubog'''.
Mga detalye mapuwedeng managboan sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} talaan kan pinaglubog].
Ika mapuwede pa man na [$1 matanaw ining diff] kun ika mawot na magdagos.",
'rev-deleted-diff-view' => "Saro sa mga pinagbago kaining diff '''pinagpura'''.
Ika makakapagtanaw kaining diff; mga detalye puwedeng mananagboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan kan pinuraan].",
'rev-suppressed-diff-view' => "Saro sa mga pinagbago kaining diff '''pinaglubog'''.
Ika puwedeng makakatanaw kaining diff; mga detalye puwedeng mananagboan sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} talaan kan pinaglubog].",
'rev-delundel' => 'ipahilíng/itagò',
'rev-showdeleted' => 'ibuyagyag',
'revisiondelete' => 'Paraon/bawion an mga pagpakaraháy',
'revdelete-nooldid-title' => 'Imbalidong target nin pagbabago',
'revdelete-nooldid-text' => 'Ika dae baya naghayag nin sarong target sa pagbabago tanganing gibohon ining punksyon, an ipinaghayag na pagbabago bako tabing eksistido, o ika nagpuprubar tanganing itago an presentend pagbabago.',
'revdelete-nologtype-title' => 'Mayong tipo nin talaan na ipinagtao',
'revdelete-nologtype-text' => 'Ika dae tabi nagpapahayag nin sarong tipo nin talaan tanganing gumibo kaining aksyon dagos.',
'revdelete-nologid-title' => 'Imbalidong entrada sa talaan',
'revdelete-nologid-text' => 'Ika dae tabi naghayag nin sarong target talaan nin pangyayari tanganing gumibo kaining punksyon o an pinaghayag na entrada dae tabi eksistido.',
'revdelete-no-file' => 'An sagunson na pinaghayag dae tabi eksistido.',
'revdelete-show-file-confirm' => 'Segurado ka tabi na gusto mo matanaw sarong pinagpurang pagbabago kan sagunson "<nowiki>$1</nowiki>" poon $2 sa $3?',
'revdelete-show-file-submit' => 'Iyo tabi',
'revdelete-selected' => "'''{{PLURAL:$2|Selected revision|Mga piniling pagbabago}} kan [[:$1]]'''",
'logdelete-selected' => "'''{{PLURAL:$1|Selected log event|Mga piniling talaan kan mga pangyayari}}:'''",
'revdelete-text' => "'''Pinagpurang mga pagbabago asin mga pangyayari mahihiling pa man sa historiyang pahina asin mga talaan, pero an mga parte kan saindang laman dae puwedeng magamit kan publiko.'''
An ibang administrador sa {{SITENAME}} puwede pa man makagamit sa pinagtagong laman asin balewalaon an pagpura kaini giraray sa paagi nin kaparehong panlaog-olay, laen lang kun may kadagdagang pangilin an inilapat.",
'revdelete-confirm' => 'Pakikumpirma tabi na ika tuyong gumibo kaini, na saimong naintindihan an mga konsekuwensiya, asin ta ika pinaghihimo ini na uyon sa [[{{MediaWiki:Policy-url}}|an palisiya]].',
'revdelete-suppress-text' => "An paglulubog dapat '''sana''' makakagamit sana para sa minasunod na mga kaso:
*Potensiyal na libeloso an impormasyon
*Bakong angay an personal na impormasyon
*: ''mga address kan harong asin mga numero kan telepono, sosyal na seguridad, iba pa.''",
'revdelete-legend' => 'Ilapat an mga restriksyon sa bisibilidad',
'revdelete-hide-text' => 'Tagoon an teksto kan pagpakaraháy',
'revdelete-hide-image' => 'Tagoon an laog kan file',
'revdelete-hide-name' => 'Tagoon an aksyon asin target',
'revdelete-hide-comment' => 'Tagoon an komento sa paghirá',
'revdelete-hide-user' => 'Tagoon an pangaran kan editor/IP',
'revdelete-hide-restricted' => 'Ilubog an mga datos gikan sa mga administrador asin man kan iba',
'revdelete-radio-same' => '(dae pagribayan)',
'revdelete-radio-set' => 'Iyo tabi',
'revdelete-radio-unset' => 'Bako tabi',
'revdelete-suppress' => 'Dai ipahilíng an mga datos sa mga sysops asin sa mga iba pa',
'revdelete-unsuppress' => 'Halîon an mga restriksyón sa mga ibinalík na pagpakarhay',
'revdelete-log' => 'Rason:',
'revdelete-submit' => 'Ipag-aplay sa mga piniling {{PLURAL:$1|pagbabago|mga pagbabago}}',
'revdelete-success' => "'''Pagbabago sa bisibilidad matrayumpong pinagdagdagan.'''",
'revdelete-failure' => "'''Pagbabago sa bisibilidad dae tabi nadagdagan:'''
$1",
'logdelete-success' => "'''Nakapuesto na an katalâan kan nangyari.'''",
'logdelete-failure' => "'''Talaan sa bisibilidad dae tabi nailapat:'''
$1",
'revdel-restore' => 'ribayan an bisibilidad',
'revdel-restore-deleted' => 'pinagpurang mga pagbabago',
'revdel-restore-visible' => 'lantad na mga pagbabago',
'pagehist' => 'Pahinang historiya',
'deletedhist' => 'Pinagpurang historiya',
'revdelete-hide-current' => 'Napasalang pagtatago kan item petsado $2, $1: Iyo ini an presenteng pagbabago.
Ini dae tabi naitatago.',
'revdelete-show-no-access' => 'Napasalang paghahayag kan item petsado $2, $1: Ining item markadong "pinagpangilin".
Ika mayo tabing pangaputan kaini.',
'revdelete-modify-no-access' => 'Napasalang pagsasangli kan item petsado $2, $1: Ining item markadong "pinagpangilin".
Ika mayo tabing pangaputan kaini.',
'revdelete-modify-missing' => 'Napasalang pagsasangli kan item ID $1: Ini nawawara gikan sa datos-sarayan!',
'revdelete-no-change' => "'''Patanid tabi:''' An item petsado $2, $1 igwa na tabi kan pinaghahagad na mga panuytoy sa bisibilidad.",
'revdelete-concurrent-change' => 'Napasalang pagsasangli kan item petsado $2, $1: An status nagpapahiling na pinagribayan kan ibang tawo habang ikan nagprubar na sanglian ini.
Paki-tsek tabi sa mga talaan.',
'revdelete-only-restricted' => 'Napasalang pagtatago kan item petsado $2, $1: Ika dae tabi makakapaglubog kan mga item na mahiling kan mga administrador na mayo kang piniling saro sa iba pang bisibilidad na mga pagpipilian.',
'revdelete-reason-dropdown' => '*Pirmihang mga rason sa pagpura
**Paglapas kan Copyright
**Bakong angay na personal na impormasyon
**Potensiyal na libelosong impormasyon',
'revdelete-otherreason' => 'Iba pa/kadagdagang rason:',
'revdelete-reasonotherlist' => 'Ibang rason',
'revdelete-edit-reasonlist' => 'Liwaton an mga rason sa pagpura',
'revdelete-offender' => 'Awtor kan pagbabago:',

# Suppression log
'suppressionlog' => 'Talaan kan paglulubog',
'suppressionlogtext' => 'Sa ibaba yaon an sarong listahan kan mga pinuraan asin mga kinubkob na imbuwelto sa laman na pinagtatago sa mga administrador.
Hilnga baya an [[Special:BlockList|listahan kan kinubkob]] para sa listahan kan presenteng operasyonal na mga pinagbabawal asin mga pinagkukubkob.',

# History merging
'mergehistory' => 'Tiriponon an pahina kan mga historiya',
'mergehistory-header' => 'Ining pahina minatugot saimo na tiriponon an mga pagbabago kan historiya nin sarong pinaggikanang pahina na magin sarong baguhong pahina.
Himoon mong segurado na ining pagbabago makapagtala nin historikal na kapadagusang pahina.',
'mergehistory-box' => 'Tiriponon an mga pagbabago sa duwang mga pahina:',
'mergehistory-from' => 'Gikanang pahina:',
'mergehistory-into' => 'Destinasyong pahina:',
'mergehistory-list' => 'Puwedeng maitiripon na historiya kan pagliwat',
'mergehistory-merge' => 'An mga minasunod na mga pagbabago sa [[:$1]] mapuwedeng pagtiriponon na magin [[:$2]].
Gamita an radyong pindutan sa kolum tanganing tiriponon sana an mga pagbabagong pinagmukna asin bago pa man an pinaghahayag na oras.
Tandaan na an paggagamit kan nabigasyong kilyawan makakapaglapat giraray kaining kolum.',
'mergehistory-go' => 'Ipahayag an mapuwedeng matiripon na mga pagliwat',
'mergehistory-submit' => 'Tiripona an mga pagbabago',
'mergehistory-empty' => 'Mayong mga pagbabago na puwedeng mapagtiripon.',
'mergehistory-success' => '$3 {{PLURAL:$3|pagbabago|mga pagbabago}} sa [[:$1]] matrayumpong napagtiripon na magin [[:$2]].',
'mergehistory-fail' => 'Dae tabi makayanan na makapaghimo nin historiyang pagtiripon, tabi pakihiling giraray an pahina asin parametro kan oras.',
'mergehistory-no-source' => 'Gikanang pahina $1 bakong eksistido.',
'mergehistory-no-destination' => 'Destinasyong pahina $1 bakong eksistido.',
'mergehistory-invalid-source' => 'Gikanang pahina kaipuhan magin saro na balidong titulo.',
'mergehistory-invalid-destination' => 'Destinasyong pahina kaipuhan magin saro na balidong titulo.',
'mergehistory-autocomment' => 'Pinagtiripon [[:$1]] na magin [[:$2]]',
'mergehistory-comment' => 'Pinagtiripon [[:$1]] na magin [[:$2]]: $3',
'mergehistory-same-destination' => 'Gikanan asin destinasyong mga pahina dae puwedeng magkapareho',
'mergehistory-reason' => 'Rason:',

# Merge log
'mergelog' => 'Talaan kan pagtiripon',
'pagemerge-logentry' => 'pinagtiripon [[$1]] na magin [[$2]] (mga pagbabago sagkod sa $3)',
'revertmerge' => 'Suwayón',
'mergelogpagetext' => 'Sa ibaba yaon an sarong listahan kan pinakahuring mga pagtitiripon kan sarong pahinang historiya sagkod sa iba pa.',

# Diffs
'history-title' => 'Pagbabagong historiya kan "$1"',
'difference-title' => 'Pagkalaen sa tahaw nin mga pagbabago kan "$1"',
'difference-title-multipage' => 'Pagkalaen sa tahaw nin mga pahina sa "$1" asin "$2"',
'difference-multipage' => '(Pagkalaen sa tahaw kan mga pahina)',
'lineno' => 'Taytáy $1:',
'compareselectedversions' => 'Ikomparar an mga piniling bersyon',
'showhideselectedversions' => 'Ihayag/itago mga piniling pagbabago',
'editundo' => 'isulít',
'diff-multi' => '({{PLURAL:$1|Saro intermediate na pagbabago|$1 mga intermediate na mga pagbabago}} by {{PLURAL:$2|sarong paragamit|$2 mga paragamit}} dae pinaghahayag)',
'diff-multi-manyusers' => '({{PLURAL:$1|Sarong intermediate na pagbabago|$1 mga intermediate na mga pagbabago}} na sobra sa $2 {{PLURAL:$2|paragamit|mga paragamit}} dae pinaghahayag)',

# Search results
'searchresults' => 'Resulta kan paghánap',
'searchresults-title' => 'Hanápon an resulta para sa "$1"',
'searchresulttext' => 'Para sa iba pang impormasyon manonongod sa paghanap sa {{SITENAME}}, hilingon tabî an [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Ika naghanap para sa \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|gabos na mga pahina na nagpopoon sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|gabos na mga pahina na nakatakod sa "$1"]])',
'searchsubtitleinvalid' => "Hinanap mo an '''$1'''",
'toomanymatches' => 'Kadakol-dakol na angay an ipigbalik, probaran an ibang kahaputan',
'titlematches' => 'Angay an título kan artíkulo',
'notitlematches' => 'Mayong angay na título nin pahina',
'textmatches' => 'Angay an teksto nin páhina',
'notextmatches' => 'Mayong ángay na teksto nin páhina',
'prevn' => 'dating {{PLURAL:$1|$1}}',
'nextn' => 'sunód na {{PLURAL:$1|$1}}',
'viewprevnext' => 'Hilingón ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url' => 'Help:Mga laog',
'searchprofile-everything' => 'Gabós',
'searchprofile-articles-tooltip' => 'Hanapon sa $1',
'search-result-size' => '$1 ({{PLURAL:$2|1 tatarámon|$2 mga tatarámon}})',
'search-suggest' => 'Boót mo iyó: $1',
'search-interwiki-more' => '(dakol pa)',
'search-mwsuggest-enabled' => 'igwang mga suhestyon',
'search-mwsuggest-disabled' => 'mayong suhestyon',
'searchall' => 'gabós',
'showingresults' => "Pigpapahiling sa babâ sagkod sa {{PLURAL:$1|'''1''' resulta|'''$1''' mga resulta}} poon sa #'''$2'''.",
'showingresultsnum' => "Pigpapahiling sa babâ {{PLURAL:$3|'''1''' resulta|'''$3''' mga resulta}} poon sa #'''$2'''.",
'nonefound' => "'''Pagiromdom''': An mga prakasong paghanap pirmeng kawsa kan paghanap kan mga tataramon na komún arog kan \"may\" asin \"sa\", huli ta an mga ini dai nakaíndise, o sa pagpili kan sobra sa sarong tataramon (an mga páhina sana na igwá kan gabos na pighahanap na tataramon an maipapahiling sa resulta).",
'powersearch' => 'Pinaoróg na paghánap',
'powersearch-field' => 'Hanápon an',
'searchdisabled' => 'Pigpopogolan mûna an paghanap sa {{SITENAME}}. Mientras tanto, pwede ka man maghanap sa Google. Giromdomon tabî na an mga indise kan laog ninda sa {{SITENAME}} pwede ser na lumâ na.',

# Quickbar
'qbsettings' => 'Quickbar',
'qbsettings-none' => 'Mayô',
'qbsettings-fixedleft' => 'Nakatakód sa walá',
'qbsettings-fixedright' => 'Nakatakód sa tûo',
'qbsettings-floatingleft' => 'Naglálatáw sa walá',
'qbsettings-floatingright' => 'Naglálatáw sa tûo',

# Preferences page
'preferences' => 'Mga kabòtan',
'mypreferences' => 'Mga kabòtan ko',
'prefs-edits' => 'Bilang kan mga hirá:',
'prefsnologin' => 'Dai nakalaog',
'prefsnologintext' => 'Ika dapat si [[Special:UserLogin|nakalaog]] para makapwesto nin mga kabôtan nin parágamit.',
'changepassword' => 'Ribayan an sekretong panlaog',
'prefs-skin' => "''Skin''",
'skin-preview' => 'Tânawon',
'datedefault' => 'Mayong kabôtan',
'prefs-datetime' => 'Petsa asin oras',
'prefs-personal' => 'Pambisto nin parágamit',
'prefs-rc' => 'Mga kaaagi pa sanang pagribay',
'prefs-watchlist' => 'Pigbabantayan',
'prefs-watchlist-days' => 'Máximong número nin mga aldaw na ipapahiling sa lista nin mga pigbabantayan:',
'prefs-watchlist-edits' => 'Máximong número nin pagbabâgo na ipapahiling sa pinadakulang lista nin pigbabantayan:',
'prefs-misc' => 'Lain',
'saveprefs' => 'Itagama',
'resetprefs' => 'Ipwesto giraray',
'prefs-editing' => 'Pighihira',
'rows' => 'Mga hilera:',
'columns' => 'Mga taytay:',
'searchresultshead' => 'Hanápon',
'resultsperpage' => 'Mga tamà kada pahina:',
'stub-threshold' => 'Kasagkoran kan <a href="#" class="stub">takod kan tambô</a> pigpopormato:',
'recentchangesdays' => 'Mga aldáw na ipapahilíng sa mga nakakaági pa sanáng pagbabàgó:',
'recentchangescount' => 'Bilang nin mga paghirá na ipapahilíng sa mga nakakaági pa sanáng pagbabàgó:',
'savedprefs' => 'Itinagama na an mga kabôtan mo.',
'timezonelegend' => 'Zona nin oras',
'localtime' => 'Lokal na oras',
'timezoneuseserverdefault' => 'Gamita an panugmad sa wiki ($1)',
'timezoneuseoffset' => 'Iba pa (ihayag an pambawi)',
'timezoneoffset' => 'Bawia¹:',
'servertime' => 'Oras kan serbidor:',
'guesstimezone' => "Bugtakan an ''browser''",
'timezoneregion-africa' => 'Aprika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antartika',
'timezoneregion-arctic' => 'Arktik',
'timezoneregion-asia' => 'Asya',
'timezoneregion-atlantic' => 'Atlantikong Kadagatan',
'timezoneregion-australia' => 'Australya',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Indiyang Kadagatan',
'timezoneregion-pacific' => 'Pasipikong Kadagatan',
'allowemail' => "Togotan an mga ''e''-surat halî sa ibang mga parágamit",
'prefs-searchoptions' => 'Pagpipilian sa Paghahanap',
'prefs-namespaces' => 'Pangarang mga espasyo',
'defaultns' => 'Kun laen maghanap sa laog kaining pangarang mga espasyo:',
'default' => 'pwestong normal',
'prefs-files' => 'Mga dokumento',
'prefs-custom-css' => 'Kustombreng CSS',
'prefs-custom-js' => 'Kustombreng JavaScript',
'prefs-common-css-js' => 'Pinagheras na CSS/JavaScript para sa gabos na mga kalapatan:',
'prefs-reset-intro' => 'Ika makakagamit kaining pahina tanganing ilapat giraray an saimong mga kabotan sa panugmad kan sayt.
Ini dae tabi matitingkog.',
'prefs-emailconfirm-label' => 'Kumpirmasyon sa E-koreo',
'youremail' => 'E-koreo:',
'username' => 'Pangaran kan parágamit:',
'uid' => 'ID kan parágamit:',
'prefs-memberingroups' => 'Miembro kan {{PLURAL:$1|grupo|grupos}}:',
'yourrealname' => 'Totoong pangaran:',
'yourlanguage' => 'Tataramon:',
'yourvariant' => 'Bariante:',
'yournick' => 'Gahâ:',
'badsig' => 'Dai pwede an bâgong pirmang ini; isúsog an mga HTML na takód.',
'badsiglength' => 'Halabâon an gahâ; kaipuhan dai mababà sa $1 na mga karakter.',
'gender-male' => 'Lalaki',
'gender-female' => 'Babai',
'email' => 'E-koreo',
'prefs-help-realname' => 'Opsyonal an totoong pangaran asin kun itatao mo ini, gagamiton ini yangarig an mga sinurat mo maatribuir saimo.',
'prefs-help-email' => 'Opsyonal an e-koreo, alagad pwede ka na masosog kan iba sa paagi kan saimong pahina o pahina nin olay na dai kinakaipuhan na ipabisto an identidad mo.',
'prefs-help-email-required' => 'Kaipuhan an e-koreo.',

# User rights
'userrights' => 'Pagmaneho kan mga derecho nin paragamit',
'userrights-lookup-user' => 'Magmaného kan mga grupo nin parágamit',
'userrights-user-editname' => 'Ilaog an pangaran kan parágamit:',
'editusergroup' => 'Hirahón an mga Grupo kan Parágamit',
'editinguser' => "Pighihira an parágamit na '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Hirahón an mga grupo kan parágamit',
'saveusergroups' => 'Itagama an mga Grupo nin Páragamit',
'userrights-groupsmember' => 'Myembro kan:',
'userrights-reason' => 'Rason:',
'userrights-nologin' => 'Ika kaipuhan na [[Special:UserLogin|maglaog ka]] na igwa nin panindog na administrador bago ka makapagtao nin karapatan sa paragamit.',
'userrights-notallowed' => 'An saimong panindog mayo tabi nin permiso na magdagdag o maghale nin karapatan kan mga paragamit.',
'userrights-changeable-col' => 'Mga grupo na mapuwede mong baguhon',
'userrights-unchangeable-col' => 'Mga grupo na dae mo mapuwedeng baguhon',

# Groups
'group' => 'Grupo:',
'group-user' => 'Mga Paragamit',
'group-autoconfirmed' => 'Paragamit na sadiring nagkonpirma',
'group-bot' => 'Mga bots',
'group-sysop' => 'Mga sysop',
'group-bureaucrat' => 'Mga bureaucrat',
'group-suppress' => 'Mga Tagapagmato',
'group-all' => '(gabos)',

'group-user-member' => '{{GENDER:$1|paragamit}}',
'group-autoconfirmed-member' => '{{GENDER:$1|auto-kumpirmadong paragamit}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|administrador}}',
'group-bureaucrat-member' => '{{GENDER:$1|burokrata}}',
'group-suppress-member' => '{{GENDER:$1|tagapagmato}}',

'grouppage-user' => '{{ns:project}}:Mga Paragamit',
'grouppage-autoconfirmed' => '{{ns:project}}:Mga enseguidang nakonpirmar na parágamit',
'grouppage-bot' => '{{ns:project}}:Mga bot',
'grouppage-sysop' => '{{ns:project}}:Mga tagamató',
'grouppage-bureaucrat' => '{{ns:project}}:Mga bureaucrat',
'grouppage-suppress' => '{{ns:project}}:Tagapagmato',

# Rights
'right-read' => 'Magbasa kan mga pahina',
'right-edit' => 'Liwaton an mga pahina',
'right-createpage' => 'Muknaon an mga pahina (na bakong mga pahina nin orolayan)',
'right-createtalk' => 'Muknaon an mga pahinang orolayan',
'right-createaccount' => 'Muknaon an baguhong mga panindog nin paragamit',
'right-minoredit' => 'Markahan an mga pinagliwat bilang menor',
'right-move' => 'Ibalyo an mga pahina',
'right-move-subpages' => 'Ibalyo an mga pahina kaiba an saindang mga sub-pahina',
'right-move-rootuserpages' => 'Ibalyo an ugat nin mga pahina kan paragamit',
'right-movefile' => 'Ibalyo an mga sagunson',
'right-suppressredirect' => 'Dae tabi magmukna nin paotrong direksyon gikan sa ginikanang mga pahina kunsoarin magbabalyo nin mga pahina',
'right-upload' => 'Ipagkarga an mga sagunson (file)',
'right-reupload' => 'Patungan an mga eksistidong mga sagunson',
'right-reupload-own' => 'Patungan an eksistido nang mga pahina na ipinagkarga sa paagi mo',
'right-reupload-shared' => 'Salambawan an mga sagunson sa lokal na pinagheras nin repositoryo kan media',
'right-upload_by_url' => 'Ipagkara an mga sagunson sa sarong URL',
'right-purge' => 'Purgaha an sarayan kan sayt para sa sarong pahina na daeng kaipo an kumpirmasyon',
'right-autoconfirmed' => 'Liwaton an semi-protektadong mga pahina',
'right-bot' => 'Pagtrataron bilang awtomatikong proseso',
'right-nominornewtalk' => 'Dae gayod nagkaigwa nin menor na pagliwat sa mga pahina nin orolayan minasulpang nin bunyaw kan bagong mga mensahe',
'right-apihighlimits' => 'Gumamit nin harahalangkaw na sagkodan sa mga kahaputan kan API',
'right-writeapi' => 'Gamit kan pagsurat sa API',
'right-delete' => 'Puraon an mga pahina',
'right-bigdelete' => 'Puraon an mga pahina na igwang darakulang mga historiya',
'right-deleterevision' => 'Puraon asin dae puraon an espisipikong pagbabago kan mga pahina',
'right-deletedhistory' => 'Tanawon an pinagpurang mga entradang historiya, na dae kan saindang asosyadong teksto',
'right-deletedtext' => 'Tanawon an pinagpurang teksto asin mga karibay sa tahaw kan mga pagbabagong pinagpura na',
'right-browsearchive' => 'Hanapon an pinagpurang mga pahina',
'right-undelete' => 'Dae puraon an pahina',
'right-suppressrevision' => 'Hilngon otro asin balikon an mga pagbabagong itinago gikan sa mga administrador',
'right-suppressionlog' => 'Tanawon an pribadong mga talaan',
'right-block' => 'Kubkubon an ibang mga paragamit sa pagliliwat',
'right-blockemail' => 'Kubkubon an paragamit na makapagpadara nin e-koreo',
'right-hideuser' => 'Kubkubon an pangaran nin paragamit, itago ini sa publiko',
'right-ipblock-exempt' => 'Sampawan an pangubkob kan IP, awtomatikong-kubkob asin panhalawig na kubkob',
'right-proxyunbannable' => 'Sampawan an awtomatikong mga kubkob kan mga proksi',
'right-unblockself' => 'Dae pagkubkubon sinda',
'right-protect' => 'Ribayan an kurit kan proteksyon asin liwaton an protektadong mga pahina',
'right-editprotected' => 'Liwaton an protektadong mga pahina (na bakong pinagsasalansan an proteksyon)',
'right-editinterface' => 'Liwaton an paragamit na olay-panlaog',
'right-editusercssjs' => 'Liwaton an CSS asin JavaScript na mga sagunson kan ibang mga paragamit',
'right-editusercss' => 'Liwaton an CSS na mga sagunson kan ibang mga paragamit',
'right-edituserjs' => 'Liwaton an JavaScript na mga sagunson kan ibang mga paragamit',
'right-rollback' => 'Hidaling ibalik an mga niliwat kan huring paragamit na nagliwat nin sarong partikular na pahina',
'right-markbotedits' => 'Markahan an pinagbalik na mga niliwat bilang bot na panliwat',
'right-noratelimit' => 'Dae magin apektado sa paagi kan rata nin mga sagkodan',
'right-import' => 'Importaron an mga pahina gikan sa ibang mga wikis',
'right-importupload' => 'Importaron an mga pahina gikan sa sarong pangarga nin sagunson',
'right-patrol' => 'Markahan an mga pagliwat kan iba bilang patrolyado',
'right-autopatrol' => 'Giboha na an saimong sadiring mga pagliwat awtomatikong markado bilang patrolyado',
'right-patrolmarks' => 'Tanawon an pinakahuring mga pagbabago na markadong patrol',
'right-unwatchedpages' => 'Tanawon an listahan kan mayong bantay na mga pahina',
'right-mergehistory' => 'Pagkasararoon an historiya kan mga pahina',
'right-userrights' => 'Liwaton gabos an karapatan kan mga paragamit',
'right-userrights-interwiki' => 'Liwaton an karapatan kan mga paragamit kan ibang mga wikis',
'right-siteadmin' => 'Kandaduhan asin dae pagkandaduhan an datos-sarayan',
'right-override-export-depth' => 'Eksportaron an mga pahina kabali na an pinagkilyawan na mga pahina sagkod sa rarom na 5',
'right-sendemail' => 'Magpadara nin e-koreo sa ibang mga paragamit',
'right-passwordreset' => 'Tanawon an e-koreo kan pagbabago nin sekretong panlaog',

# User rights log
'rightslog' => 'Usip nin derechos nin paragamit',
'rightslogtext' => 'Ini an historial kan mga pagbabâgo sa mga derecho nin parágamit.',
'rightslogentry' => 'Rinibayab an pagkamyembro ni $1 sa $2 sagkod sa $3',
'rightslogentry-autopromote' => 'dati na awtomatikong pinagpalangkaw gikan sa $2 sagkod S3',
'rightsnone' => '(mayô)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'basaha ining pahina',
'action-edit' => 'liwatón ining pahina',
'action-createpage' => 'magmukna nin mga pahina',
'action-createtalk' => 'Magmukna nin mga pahina sa orolayan',
'action-createaccount' => 'Muknaon ining panindog kan paragamit',
'action-minoredit' => 'marakahan ining pagliwat bilang menor',
'action-move' => 'ibalyo ining pahina',
'action-move-subpages' => 'ibalyo ining pahina, asin kaiba an mga sub-pahina',
'action-move-rootuserpages' => 'ibalyo an ugat kan mga pahina nin mga paragamit',
'action-movefile' => 'ibalyo ining sagunson',
'action-upload' => 'ikarga ining mga sagunson',
'action-reupload' => 'sampawan ining eksistidong sagunson',
'action-reupload-shared' => 'salambawan ining sagunson sa pinagheras na repositoryo',
'action-upload_by_url' => 'ikarga ining sagunson gikan sa URL',
'action-writeapi' => 'gamita an panurat na API',
'action-delete' => 'puraon ining pahina',
'action-deleterevision' => 'puraon ining pagbabago',
'action-deletedhistory' => 'tanawon an pinagpurang historiya kaining pahina',
'action-browsearchive' => 'hanapon an pinagpurang mga pahina',
'action-undelete' => 'dae pagpuraon ining pahina',
'action-suppressrevision' => 'hilngon otro asin ibalik ining pinagtagong pagbabago',
'action-suppressionlog' => 'tanawon ining pribadong talaan',
'action-block' => 'kubkubon ining paragamit gikan sa pagliliwat',
'action-protect' => 'ribayan an kurit nin proteksyon para sa pahinang ini',
'action-rollback' => 'hidaling ipagbalik an mga pagliwat kan huring paragamit na pinagliwat an sarong partikular na pahina',
'action-import' => 'importaron ining pahina gikan sa ibang wiki',
'action-importupload' => 'importaron ining pahina gikan sa sarong ikinargang sagunson',
'action-patrol' => 'markahan an pagliwat kan iba bilang patrolyado',
'action-autopatrol' => 'Giboha na an saimong pagliwat markado bilang patrolyado',
'action-unwatchedpages' => 'tanawon an listahan kan mayong bantay na mga pahina',
'action-mergehistory' => 'Pagkasararoon an historiya kaining pahina',
'action-userrights' => 'liwaton gabos na mga karapatan nin paragamit',
'action-userrights-interwiki' => 'liwaton an paragamit na mga karapatan kan mga paragamit nin ibang wikis',
'action-siteadmin' => 'ikandado o dae ikandado an datos-sarayan',
'action-sendemail' => 'magpadara nin mga e-koreo',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|pagbabâgo|mga pagbabâgo}}',
'recentchanges' => 'Mga nakakaági pa sanáng pagbabàgo',
'recentchanges-legend' => 'Pinakahuring mga option kan mga pagbabago',
'recentchanges-summary' => 'Hanapon an mga pinahuring pagbabâgo sa wiki digdi sa páhinang ini.',
'recentchanges-feed-description' => 'Hanápon an mga pinakahuring pagbabàgo sa wiki sa hungit na ini.',
'recentchanges-label-newpage' => 'Ining pagliwat nakapagmukna nin sarong baguhon na pahina',
'recentchanges-label-minor' => 'Ini saro sanang menor na pagliwat',
'recentchanges-label-bot' => 'Ining pagliwat pinaghimo bilang sarong bot',
'recentchanges-label-unpatrolled' => 'Ining pagliwat dae pa tabi pinagpatrolyahan',
'rcnote' => "Mahihiling sa babâ an {{PLURAL:$1| '''1''' pagbabàgo|'''$1''' pagbabàgo}} sa huring {{PLURAL:$2|na aldaw|'''$2''' na aldaw}}, sa $3.",
'rcnotefrom' => "Mahihiling sa babâ an mga pagbabàgo poon kan '''$2''' (hasta '''$1''' ipinapahiling).",
'rclistfrom' => 'Ipahilíng an mga pagbabàgo poon sa $1',
'rcshowhideminor' => '$1 saradit na pagligwat',
'rcshowhidebots' => '$1 mga bot',
'rcshowhideliu' => '$1 mga nakadágos na paragamit',
'rcshowhideanons' => '$1 mga dai bistong paragamit',
'rcshowhidepatr' => '$1 pigbabantayan na mga pagliwat',
'rcshowhidemine' => '$1 mga pagliwat ko',
'rclinks' => 'Ipahilíng an $1 huring pagbabàgo sa ultimong $2 aldaw<br />$3',
'diff' => 'ibá',
'hist' => 'usip',
'hide' => 'Tagóon',
'show' => 'Ipahilíng',
'minoreditletter' => 's',
'newpageletter' => 'B',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 nagbabantay na parágamit]',
'rc_categories' => 'Limitado sa mga kategorya (suhayon nin "|")',
'rc_categories_any' => 'Dawà arín',
'newsectionsummary' => '/* $1 */ bàgong seksyon',

# Recent changes linked
'recentchangeslinked' => 'Mga angay na pagbabàgo',
'recentchangeslinked-feed' => 'Mga angay na pagbabàgo',
'recentchangeslinked-toolbox' => 'Mga angay na pagbabàgo',
'recentchangeslinked-title' => 'Mga pagbabàgong angay sa "$1"',
'recentchangeslinked-noresult' => 'Warang mga pagbabago sa mga pahinang nakatakod sa itinaong pagkalawig.',
'recentchangeslinked-summary' => "Ini an lista nin mga pagsangli na ginibo pa sana sa mga pahinang nakatakod halì sa sarong espesyal na pahina (o sa mga myembro nin sarong espesyal na kategorya).
'''Maitom''' an mga pahinang [[Special:Pigbabantayan|pigbabantayan mo]].",

# Upload
'upload' => 'Isàngat an file',
'uploadbtn' => 'Ikargá an file',
'reuploaddesc' => 'Magbalik sa pormulario kan pagkarga.',
'uploadnologin' => 'Dai nakalaog',
'uploadnologintext' => "Kaipuhan ika si [[Special:UserLogin|nakadagos]]
para makakarga nin mga ''file''.",
'upload_directory_read_only' => 'An directoriong pagkarga na ($1) dai puedeng suratan kan serbidor nin web.',
'uploaderror' => 'Salâ an pagkarga',
'uploadtext' => "Gamiton tabî an pormulario sa babâ para magkarga nin mga ''file'', para maghiling o maghanap kan mga ladawan na dating kinarga magduman tabi sa [[Special:FileList|lista nin mga pigkargang ''file'']], an mga kinarga asin mga pinarâ nakalista man sa [[Special:Log/upload|historial nin pagkarga]].

Kun boot mong ikaag an ladawan sa páhina, gamiton tabî an takod arog kan
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki>''' o
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' para sa direktong pagtakod sa ''file''.",
'uploadlog' => 'historial nin pagkarga',
'uploadlogpage' => 'Ikarga an usip',
'uploadlogpagetext' => "Mahihiling sa babâ an lista kan mga pinakahuring ''file'' na kinarga.",
'filename' => 'Pangaran kan dokumento',
'filedesc' => 'Kagabsan',
'fileuploadsummary' => 'Kagabsan:',
'filestatus' => 'Estatutong derechos nin paragamit:',
'filesource' => 'Ginikanan',
'uploadedfiles' => "Mga ''file'' na ikinargá",
'ignorewarning' => 'Dai pagintiendehon an mga patanid asin itagama pa man an file',
'ignorewarnings' => 'Paliman-limanon an mga tanid',
'minlength1' => "An pangaran kan mga ''file'' dapat na dai mababâ sa sarong letra.",
'illegalfilename' => "An ''filename'' na \"\$1\" igwang mga ''character'' na dai pwede sa mga titulo nin páhina. Tâwan tabî nin bâgong pangaran an ''file'' asin probaran na ikarga giraray.",
'badfilename' => "Rinibayan an ''filename'' nin \"\$1\".",
'filetype-badmime' => "Dai pigtotogotan na ikarga an mga ''file'' na MIME na \"\$1\" tipo.",
'filetype-missing' => "Mayong ekstensyón an ''file'' (arog kan \".jpg\").",
'large-file' => "Pigrerekomendár na dapat an mga ''file'' bakong mas dakula sa $1; $2 an sokol kaining ''file''.",
'largefileserver' => "Mas dakula an ''file'' sa pigtotogotan na sokol kan ''server''.",
'emptyfile' => "Garo mayong laog an ''file'' na kinarga mo. Pwede ser na salâ ining tipo nin ''filename''. Isegurado tabî kun talagang boot mong ikarga ining ''file''.",
'fileexists' => "Igwa nang ''file'' na may parehong pangaran sa ini, sosogon tabî an '''<tt>[[:$1]]</tt>''' kun dai ka seguradong ribayan ini.
[[$1|thumb]]",
'fileexists-extension' => "May ''file'' na may parehong pangaran: [[$2|thumb]]
* Pangaran kan pigkakargang ''file'': '''<tt>[[:$1]]</tt>'''
* Pangaran kan yaon nang ''file'': '''<tt>[[:$2]]</tt>'''
Magpili tabî nin ibang pangaran.",
'fileexists-thumbnail-yes' => "An ''file'' garo ladawan kan pinasadit ''(thumbnail)''. [[$1|thumb]]
Sosogon tabî an ''file'' '''<tt>[[:$1]]</tt>'''.
Kun an sinosog na ''file'' iyo an parehong ladawan na nasa dating sokol, dai na kaipuhan magkarga nin iba pang retratito.",
'file-thumbnail-no' => "An ''filename'' nagpopoon sa '''<tt>$1</tt>'''. Garo ladawan na pinasadit ini ''(thumbnail)''.
Kun igwa ka nin ladawan na may resolusyón na maximo ikarga tabî ini, kun dai, bâgohon tabî an pangaran nin ''file''.",
'fileexists-forbidden' => "Igwa nang ''file'' na may parehong pangaran; bumalik tabi asin ikarga an ''file'' sa bâgong pangaran [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Igwa nang ''file'' na may parehong pangaran sa repositoryo nin mga bakas na ''file''; bumalik tabî asin ikarga an ''file'' sa bâgong pangaran. [[File:$1|thumb|center|$1]]",
'uploadwarning' => 'Patanid sa pagkarga',
'savefile' => "Itagama an ''file''",
'uploadedimage' => 'Ikinarga "[[$1]]"',
'overwroteimage' => 'kinarga an bagong bersión kan "[[$1]]"',
'uploaddisabled' => 'Pigpopondó an mga pagkargá',
'uploaddisabledtext' => "Pigpopogolan an pagkarga nin mga ''file'' o sa ining wiki.",
'uploadscripted' => "Ining ''file'' igwang HTML o kodang eskritura na pwede ser na salang mainterpretar kan ''browser''.",
'uploadvirus' => "May virus an ''file''! Mga detalye: $1",
'sourcefilename' => 'Ginikanan kan pangaran kan dokumento',
'destfilename' => "''Filename'' kan destinasyón",
'watchthisupload' => 'Bantayan ining pahina',
'filewasdeleted' => "May sarong ''file'' na kapangaran kaini na dating pigkarga tapos pigparâ man sana. Sosogon muna tabî an $1 bago ikarga giraray ini.",
'filename-bad-prefix' => "An pangaran nin ''file'' na pigkakarga mo nagpopoon sa '''\"\$1\"''', sarong pangaran na dai makapaladawan na normalmente enseguidang pigtatao kan mga kamerang digital. Magpili tabî nin pangaran nin ''file'' na mas makapaladawan.",
'upload-success-subj' => 'Nakarga na',

'upload-proto-error' => 'Salang protocolo',
'upload-proto-error-text' => 'An pagkargang panharayo kaipuhan nin mga URLs na nagpopoon sa  <code>http://</code> o <code>ftp://</code>.',
'upload-file-error' => 'Panlaog na salâ',
'upload-file-error-text' => "May panlaog na salâ kan pagprobar na maggibo nin temporaryong ''file'' sa ''server''.  Apodon tabî an administrador nin sistema.",
'upload-misc-error' => 'Dai naaaram na error sa pagkarga',
'upload-misc-error-text' => 'May salang panyayari na dai aram kan pagkarga.  Sosogon tabî kun tamâ an URL asin probaran giraray.  Kun an problema nagpeperseguir, apodon tabî an sarong administrador nin sistema.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Dai naabot an URL',
'upload-curl-error6-text' => 'Dai nabukas an URL na tinao.  Susugon tabi giraray na an  URL tama asin an sitio bakong raot.',
'upload-curl-error28' => 'sobra na an pagkalawig kan pagkarga',
'upload-curl-error28-text' => 'Sobrang haloy an pagsimbag kan sitio. Susugon tabi na nagaandar an sitio, maghalat nin muna asin iprobar giraray. Tibaad moot mong magprobar sa panahon na bako masiadong okupado.',

'license' => 'Paglilisensya',
'license-header' => 'Paglilisensya',
'nolicense' => 'Mayong pigpilî',
'license-nopreview' => '(Mayong patânaw)',
'upload_source_url' => ' (sarong tama, na bukas sa publikong URL)',
'upload_source_file' => " (sarong ''file'' sa kompyuter mo)",

# Special:ListFiles
'listfiles_search_for' => 'Hanápon an pangaran kan retrato:',
'imgfile' => 'dokumento',
'listfiles' => 'Lista kan dokumento',
'listfiles_date' => 'Petsa',
'listfiles_name' => 'Pangaran',
'listfiles_user' => 'Parágamit',
'listfiles_size' => 'Sukol',
'listfiles_description' => 'Deskripsión',

# File description page
'file-anchor-link' => 'File',
'filehist' => 'Uusipón nin file',
'filehist-help' => 'Magpindot kan petsa/oras para mahiling an hitsura kan file sa piniling oras.',
'filehist-deleteall' => 'parâon gabos',
'filehist-deleteone' => 'parâon ini',
'filehist-revert' => 'ibalik',
'filehist-current' => 'ngonyan',
'filehist-datetime' => 'Petsa/Oras',
'filehist-user' => 'Paragamít',
'filehist-dimensions' => 'Mga dimensyón',
'filehist-filesize' => 'Sokol nin file',
'filehist-comment' => 'Komento',
'imagelinks' => 'Mga takód',
'linkstoimage' => 'An mga minasunod na pahina nakatakod sa dokumentong ini:',
'nolinkstoimage' => 'Mayong mga pahinang nakatakod sa dokumentong ini.',
'sharedupload' => "Ining ''file'' sarong bakas na pagkarga asin pwede ser na gamiton kan ibang mga proyekto.",
'uploadnewversion-linktext' => 'Magkarga nin bàgong bersyon kaining file',

# File reversion
'filerevert' => 'Ibalik an $1',
'filerevert-legend' => 'Ibalik an dokumento',
'filerevert-intro' => "Pigbabalik mo an '''[[Media:$1|$1]]''' sa [$4 version as of $3, $2].",
'filerevert-comment' => 'Komento:',
'filerevert-defaultcomment' => 'Pigbalik sa bersyon sa ngonyan $2, $1',
'filerevert-submit' => 'Ibalik',
'filerevert-success' => "'''[[Media:$1|$1]]''' binalik sa [$4 version as of $3, $2].",
'filerevert-badversion' => "Mayong dating bersyón na lokal kaining ''file'' na may taták nin oras na arog sa tinao.",

# File deletion
'filedelete' => 'Parâon an $1',
'filedelete-legend' => 'Parâon an dokumento',
'filedelete-intro' => "Pigpaparâ mo an '''[[Media:$1|$1]]'''.",
'filedelete-intro-old' => "Pigpaparâ mo an bersyon kan '''[[Media:$1|$1]]''' sa ngonyan [$4 $3, $2].",
'filedelete-comment' => 'Rason:',
'filedelete-submit' => 'Parâon',
'filedelete-success' => "An '''$1''' pinarâ na.",
'filedelete-success-old' => '<span class="plainlinks">An bersyón kan \'\'\'[[Media:$1|$1]]\'\'\' na ngonyan na $3, pigparâ na an $2.</span>',
'filedelete-nofile' => "Mayo man an '''$1''' sa ining sitio.",
'filedelete-nofile-old' => "Mayong bersyón na nakaarchibo kan '''$1''' na igwang kan mga piniling ''character''.",

# MIME search
'mimesearch' => 'Paghanap kan MIME',
'mimesearch-summary' => "An gamit kaining páhina sa pagsasarâ kan mga ''file'' segun sa mga tipo nin MIME. Input: contenttype/subtype, e.g. <tt>image/jpeg</tt>.",
'mimetype' => 'tipo nin MIME:',
'download' => 'ideskarga',

# Unwatched pages
'unwatchedpages' => 'Dai pigbabantayan na mga pahina',

# List redirects
'listredirects' => 'Lista nin mga paglikay',

# Unused templates
'unusedtemplates' => 'Mga templatong dai ginamit',
'unusedtemplatestext' => 'Piglilista kaining páhina an gabos na mga páhina sa templatong ngaran-espacio na dai nakakaag sa ibang páhina. Giromdomon tabî na sosogon an ibang mga takod sa mga templato bâgo parâon iyan.',
'unusedtemplateswlh' => 'ibang mga takod',

# Random page
'randompage' => 'Arín man na pahina',
'randompage-nopages' => 'Mayong páhina an ngaran-espacio.',

# Random redirect
'randomredirect' => 'Random na pagredirekta',
'randomredirect-nopages' => 'Mayong paglikay (redirects) didgi sa ngaran-espacio.',

# Statistics
'statistics' => 'Mga Estadistiko',
'statistics-header-users' => 'Mga estadistiko nin parágamit',
'statistics-mostpopular' => 'mga pinaka pighiling na pahina',

'disambiguations' => 'Mga pahinang klaripikasyon',
'disambiguationspage' => 'Template:clarip',
'disambiguations-text' => "An mga nasunod na páhina nakatakod sa sarong '''páhina nin klaripikasyon'''.
Imbis, kaipuhan na nakatakod sinda sa maninigong tema.<br />
An páhina pigkokonsiderar na páhina nin klaripikasyon kun naggagamit ini nin templatong nakatakod sa [[MediaWiki:Disambiguationspage]]",

'doubleredirects' => 'Dobleng mga redirekta',
'doubleredirectstext' => 'Piglilista kaining pahina an mga pahinang minalikay sa ibang pahinang paralikay. Kada raya may mga takod sa primero asin segundang likay, buda an destino kan segundong likay, na puro-pirme sarong "tunay " na pahinang destino, na dapat duman nakaturo an primerong likay.',

'brokenredirects' => 'Putol na mga paglikay',
'brokenredirectstext' => 'An nagsusunod naglilikay kan takod sa mga pahinang mayo man:',
'brokenredirects-edit' => 'hirahón',
'brokenredirects-delete' => 'parâon',

'withoutinterwiki' => 'Mga pahinang dai nin mga takod sa ibang tataramon',
'withoutinterwiki-summary' => 'An mga nagsusunod na páhina dai nakatakód sa mga bersión na ibang tataramón:',
'withoutinterwiki-submit' => 'Ipahiling',

'fewestrevisions' => 'Mga artikulong may pinakadikit na pagpakarháy',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|mga byte}}',
'ncategories' => '$1 {{PLURAL:$1|kategorya|mga kategorya}}',
'nlinks' => '$1 {{PLURAL:$1|takod|mga takod}}',
'nmembers' => '$1 {{PLURAL:$1|myembro|mga myembro}}',
'nrevisions' => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'nviews' => '$1 {{PLURAL:$1|hiling|mga hiling}}',
'specialpage-empty' => 'Mayong mga resulta para sa report na ini.',
'lonelypages' => 'Mga solong pahina',
'lonelypagestext' => 'An mga minasunod na mga páhina dai nakatakod sa ibang mga páhina sa wiki na ini.',
'uncategorizedpages' => 'Mga dai nakakategoryang páhina',
'uncategorizedcategories' => 'Mga dai nakakategoryang kategorya',
'uncategorizedimages' => 'Mga dai nakakategoryang retrato',
'uncategorizedtemplates' => 'Mga templatong mayong kategorya',
'unusedcategories' => 'Dai gamit na mga kategorya',
'unusedimages' => 'Mga dokumentong dai nagamit',
'popularpages' => 'Mga popular na páhina',
'wantedcategories' => 'Mga hinahanap na kategorya',
'wantedpages' => 'Mga hinahanap na pahina',
'mostlinked' => 'Pinakapigtatakodan na mga pahina',
'mostlinkedcategories' => 'Pinakapigtatakodan na mga kategorya',
'mostlinkedtemplates' => 'An mga pinakanatakodan na templato',
'mostcategories' => 'Mga artikulong may pinaka dakol na kategorya',
'mostimages' => 'Pinakapigtatakodan na files',
'mostrevisions' => 'Mga artikulong may pinakadakol na pagpakarháy',
'prefixindex' => 'Gabós na pahinang igwáng katakód',
'shortpages' => 'Haralìpot na pahina',
'longpages' => 'Mga halabang pahina',
'deadendpages' => 'Mga pahinang mayong luwasan',
'deadendpagestext' => 'An mga nagsusunod na pahina dai nakatakod sa mga ibang pahina sa ining wiki.',
'protectedpages' => 'Mga protektadong pahina',
'protectedpagestext' => 'An mga minasunod na pahina protektado na ibalyó o hirahón',
'protectedpagesempty' => 'Mayong pang páhina an napoprotehiran kaining mga parametros.',
'listusers' => 'Lista nin paragamit',
'newpages' => 'Mga bàgong pahina',
'newpages-username' => 'Pangaran kan parágamit:',
'ancientpages' => 'Mga pinakalumang pahina',
'move' => 'Ibalyó',
'movethispage' => 'Ibalyó ining pahina',
'unusedimagestext' => "Giromdomon tabî na an mga ibang ''site'' pwedeng nakatakod sa ladawan na may direktong URL, pues pwede ser na nakalista pa digdi a pesar na ini piggagamit pa.",
'unusedcategoriestext' => 'Igwa ining mga pahinang kategoria maski mayo man na iba pang pahina o kategoria an naggagamit kaiyan.',
'notargettitle' => 'Mayong target',
'notargettext' => 'Dai ka pa nagpili nin pahina o paragamit na muya mong gibohon an accion na ini.',

# Book sources
'booksources' => 'Ginikanang libro',
'booksources-search-legend' => 'Maghanap nin mga ginikanang libro',
'booksources-go' => 'Dumanán',
'booksources-text' => "Mahihiling sa babâ an lista kan mga takod sa ibang ''site'' na nagbenbenta nin mga bâgo asin nagamit nang libro, asin pwede ser na igwa pang mga ibang impormasyon manonongod sa mga librong pighahanap mo:",

# Special:Log
'specialloguserlabel' => 'Paragamit:',
'speciallogtitlelabel' => 'Titulo:',
'log' => 'Mga usip',
'all-logs-page' => 'Gabos na usip',
'alllogstext' => 'Sinalak na hihilngon kan gabos na historial na igwa sa {{SITENAME}}. Kun boot mong pasaditon an seleksyon magpili tabî nin klase kan historial, ngaran nin parágamit, o páhinang naapektaran.',
'logempty' => 'Mayong angay na bagay sa historial.',
'log-title-wildcard' => 'Hanapon an mga titulong napopoon sa tekstong ini',

# Special:AllPages
'allpages' => 'Gabos na pahina',
'alphaindexline' => '$1 sagkod sa $2',
'nextpage' => 'Sunod na pahina ($1)',
'prevpage' => 'Nakaaging pahina ($1)',
'allpagesfrom' => 'Ipahiling an mga páhina poon sa:',
'allarticles' => 'Gabos na mga artikulo',
'allinnamespace' => 'Gabos na mga páhina ($1 ngaran-espacio)',
'allnotinnamespace' => 'Gabos na mga páhina (na wara sa $1 ngaran-espacio)',
'allpagesprev' => 'Nakaagi',
'allpagesnext' => 'Sunod',
'allpagessubmit' => 'Dumanán',
'allpagesprefix' => 'Ipahiling an mga pahinang may prefiho:',
'allpagesbadtitle' => "Dai pwede an tinaong titulo kan páhina o may prefihong para sa ibang tataramon o ibang wiki. Pwede ser na igwa ining sarô o iba pang mga ''character'' na dai pwedeng gamiton sa mga titulo.",
'allpages-bad-ns' => 'An {{SITENAME}} mayo man na ngaran-espacio na "$1".',

# Special:Categories
'categories' => 'Mga Kategorya',
'categoriespagetext' => 'Igwa nin laog ang mga minasunod na kategorya.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:DeletedContributions
'deletedcontributions' => 'Parâon an mga kontribusyon kan parágamit',
'deletedcontributions-title' => 'Parâon an mga kontribusyon kan parágamit',

# Special:LinkSearch
'linksearch' => 'Mga panluwas na takod',
'linksearch-ok' => 'Hanápon',
'linksearch-line' => '$1 an nakatakod sa $2',

# Special:ListUsers
'listusersfrom' => 'Ipahiling an mga paragamit poon sa:',
'listusers-submit' => 'Ipahiling',
'listusers-noresult' => 'Mayong nakuang parágamit.',

# Special:ListGroupRights
'listgrouprights-group' => 'Grupo',
'listgrouprights-rights' => 'Derechos',
'listgrouprights-members' => '(lista kan mga kaapíl)',

# E-mail user
'mailnologin' => 'Mayong direksyón nin destino',
'mailnologintext' => "Kaipuhan ika si [[Special:UserLogin|nakalaog]]
asin may marhay na ''e''-surat sa saimong [[Special:Preferences|Mga kabôtan]]
para makapadara nin ''e''-surat sa ibang parágamit.",
'emailuser' => 'E-koreohan ining paragamit',
'emailpage' => 'E-suratan an parágamit',
'emailpagetext' => "Kun ining páragamit nagkaag nin marhay ''e''-surat sa saiyang mga kabôtan, an pormulario sa babâ mapadara nin sarong mensahe.
An kinaag mong ''e''-surat sa saimong mga kabôtan nin paragamit mahihiling bilang na \"Hali ki\" kan ''e''-surat, para an recipiente pwedeng makasimbag.",
'usermailererror' => 'Error manonongod sa korreong binalik:',
'defemailsubject' => '{{SITENAME}} e-surat',
'noemailtitle' => "Mayô nin ''e''-surat",
'noemailtext' => 'Dai nagpili nin tama na direccion nin e-surat an paragamit,
o habo magresibo nin e-surat sa ibang paragamit.',
'emailfrom' => 'Poon',
'emailto' => 'Hasta:',
'emailsubject' => 'Tema',
'emailmessage' => 'Mensahe',
'emailsend' => 'Ipadara',
'emailccme' => 'E-suratan ako nin kopya kan mga mensahe ko.',
'emailccsubject' => 'Kopya kan saimong mensahe sa $1: $2',
'emailsent' => 'Naipadará na an e-surat',
'emailsenttext' => 'Naipadará na su e-surat mo.',

# Watchlist
'watchlist' => 'Pigbabantayan ko',
'mywatchlist' => 'Babantáyan ko',
'nowatchlist' => 'Mayo ka man na mga bagay saimong lista nin pigbabantayan.',
'watchlistanontext' => 'Mag $1 tabi para mahiling o maghira nin mga bagay saimong lista nin mga pigbabantayan.',
'watchnologin' => 'Mayô sa laog',
'watchnologintext' => 'Dapat ika si [[Special:UserLogin|nakalaog]] para puede kang magribay kan saimong lista nin mga pigbabantayán.',
'addedwatchtext' => "Ining pahina \"[[:\$1]]\" dinugang sa saimong mga [[Special:Watchlist|Pigbabantayan]].
An mga pagbabâgo sa páhinang ini asin sa mga páhinang olay na kapadis kaini ililista digdi,
asin an páhina isusurat nin '''mahîbog''' sa [[Special:RecentChanges|lista nin mga kaaagi pa sanang pagbabâgo]] para madalî ining mahiling.

Kun boot mong halîon an páhina sa pigbabantayan mo sa maabot na panahon, pindoton an \"Pabayaan\" ''side bar''.",
'removedwatchtext' => 'An pahinang "[[:$1]]" pigtanggal sa saimong pigbabantayan.',
'watch' => 'Bantayán',
'watchthispage' => 'Bantayan ining pahina',
'unwatch' => 'Dai pagbantayan',
'unwatchthispage' => 'Pondohon an pagbantay',
'notanarticle' => 'Bakong páhina nin laog',
'watchnochange' => 'Mayo sa saimong mga pigbabantayan an nahira sa laog nin pinahiling na pagkalawig.',
'watchlist-details' => '{{PLURAL:$1|$1 pahina|$1 mga pahina}} sa babantáyan mo an daí kabáli an mga olay na pahina.',
'wlheader-enotif' => "* Nakaandar an paising ''e''-surat.",
'wlheader-showupdated' => "* An mga páhinang pigbâgo poon kan huri mong bisita nakasurat nin '''mahîbog'''",
'watchmethod-recent' => 'Pigsososog an mga kaaagi pa sanang hirá sa mga pigbabantayan na páhina',
'watchmethod-list' => 'Pigsososog an mga pigbabantayan na páhina para mahiling an mga kaaagi pa sanan paghirá',
'watchlistcontains' => 'An saimong lista nin pigbabantayan igwang $1 na {{PLURAL:$1|páhina|mga páhina}}.',
'iteminvalidname' => "May problema sa bagay na '$1', salâ an pangaran...",
'wlnote' => "Mahihiling sa babâ an {{PLURAL:$1|huring pagriribay|mga huring'''$1''' pagriribay}} sa ultimong {{PLURAL:$2|oras|'''$2''' mga oras}}.",
'wlshowlast' => 'Ipahilíng an ultimong $1 na oras $2 na aldaw $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Pigbabantayan...',
'unwatching' => 'Dai pigbabantayan...',

'enotif_mailer' => '{{SITENAME}} Kartero nin isi',
'enotif_reset' => 'Markahan an gabos na mga binisitang pahina',
'enotif_newpagetext' => 'Bâgo ining pahina.',
'enotif_impersonal_salutation' => '{{SITENAME}} parágamit',
'changed' => 'pigbâgo',
'created' => 'piggibo',
'enotif_subject' => 'An pahinang {{SITENAME}} na $PAGETITLE binago $CHANGEDORCREATED ni $PAGEEDITOR',
'enotif_lastvisited' => 'Hilingón an $1 para sa gabos na mga pagbâgo poon kan huring bisita.',
'enotif_lastdiff' => 'Hilingón an $1 tangarig mahiling an pagbâgong ini.',
'enotif_anon_editor' => 'dai bistong parágamit $1',
'enotif_body' => 'Namómòtan na $WATCHINGUSERNAME,


An páhinang {{SITENAME}} na $PAGETITLE binâgo $CHANGEDORCREATED sa $PAGEEDITDATE ni $PAGEEDITOR, hilingón an $PAGETITLE_URL para sa presenteng bersyón.

$NEWPAGE

Sumáda kan editor: $PAGESUMMARY $PAGEMINOREDIT

Apodon an editor:
\'\'e\'\'-surat: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Mayô nang iba pang paisi na ipapadara dapit sa iba pang mga pagbabâgo kun dai mo bibisitahon giraray ining páhina. Pwede mo man na ipwesto giraray an mga patanid para sa saimong mga páhinang pigbabantayan duman sa saimong lista nin pigbabantayan.

             An maboot na sistema nin paisi kan {{SITENAME}}

--
Para bâgohon an pagpwesto kan saimong mga pigbabantayan, bisitahon an
{{canonicalurl:{{#special:EditWatchlist}}}}

Komentaryo asin iba pang tabang:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Paraon an pahina',
'confirm' => 'Kompermaron',
'excontent' => "Ini an dating laog: '$1'",
'excontentauthor' => "ini an dating laog: '$1' (asin an unikong kontribuidor si '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "Ini an dating laog bagô blinankohán: '$1'",
'exblank' => 'mayong laog an páhina',
'delete-legend' => 'Paraon',
'historywarning' => 'Patanid: An pahinang paparaon mo igwa nin uusipón:',
'confirmdeletetext' => 'Paparaon mo sa base nin datos ining pahina kasabay an gabos na mga uusipón kaini.
Konpirmaron tabì na talagang boot mong gibohon ini, nasasabotan mo an mga resulta, asin an piggigibo mo ini konporme sa
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Nagibo na',
'deletedtext' => 'Pigparà na an "$1" .
Hilingón tabì an $2 para mahiling an lista nin mga kaaagi pa sanang pagparà.',
'dellogpage' => 'Usip nin pagparà',
'dellogpagetext' => 'Mahihiling sa babâ an lista kan mga pinakahuring pagparâ.',
'deletionlog' => 'Historial nin pagparâ',
'reverted' => 'Ibinalik sa mas naenot na pagpakarhay',
'deletecomment' => 'Rason:',
'deleteotherreason' => 'Iba/dugang na rason:',
'deletereasonotherlist' => 'Ibang rason',

# Rollback
'rollback' => 'Mga paghihira na pabalík',
'rollback_short' => 'pabalík',
'rollbacklink' => 'pabalikón',
'rollbackfailed' => 'Prakaso an pagbalík',
'cantrollback' => 'Dai pwedeng bawîon an hirá; an huring kontribuidor iyo an unikong parásurat kan páhina.',
'alreadyrolled' => 'Dai pwedeng ibalik an huring hirá kan [[:$1]]
ni [[User:$2|$2]] ([[User talk:$2|Olay]]); may ibang parágamit na naghirá na o nagbalik na kaini.

Huring hirá ni [[User:$3|$3]] ([[User talk:$3|Olay]]).',
'editcomment' => "Ini an nakakaag na komentaryo sa paghirá: \"''\$1''\".",
'revertpage' => 'Binawî na mga paghirá kan [[Special:Contributions/$2|$2]] ([[User talk:$2|Magtaram]]); pigbalik sa dating bersyón ni [[User:$1|$1]]',
'rollback-success' => 'Binawî na mga paghirá ni $1; pigbalik sa dating bersyón ni $2.',

# Edit tokens
'sessionfailure' => "Garo may problema sa paglaog mo;
kinanselár ining aksyón bilang sarong paglikay kontra sa ''session hijacking''.
Pindotón tabî an \"back\" asin ikarga giraray an páhinang ginikanan mo, dangan probarán giraray.",

# Protect
'protectlogpage' => 'Usip nin pagsagáng',
'protectlogtext' => 'May lista sa baba nin mga kandado asin panbawi kan kandado kan mga páhina. Hilingon an [[Special:ProtectedPages|lista kan mga pigproprotektarán na mga páhina]] para mahiling an lista kan mga proteksión nin mga páhina sa ngunyan na nakabuká.',
'protectedarticle' => 'protektado "[[$1]]"',
'modifiedarticleprotection' => 'binago an nibel nin proteksión para sa "[[$1]]"',
'unprotectedarticle' => 'Warang proteksión an "[[$1]]"',
'protect-title' => 'Pigpupuesta an nibel nin proteksión sa "$1"',
'prot_1movedto2' => '[[$1]] piglipat sa [[$2]]',
'protect-legend' => 'Kompermaron an proteksyon',
'protectcomment' => 'Rason:',
'protectexpiry' => 'Mápasó:',
'protect_expiry_invalid' => 'Dai pwede ining pahanon nin pagpasó.',
'protect_expiry_old' => 'Nakalihis na an panahon nin pagpasó.',
'protect-text' => "Pwede mong hilingón asin bàgohon an tangga nin proteksyon digdi para sa pahina '''$1'''.",
'protect-locked-blocked' => "Dai mo pwedeng bâgohon an mga tangga kan proteksyon mientras na ika nababágat. Ini an mga presenteng pwesto kan páhina '''$1''':",
'protect-locked-dblock' => "Dai puedeng ibalyo an mga nibel kan proteksión ta may actibong kandado sa base nin datos.
Ini an mga puesta sa ngunyan kaining páhina '''$1''':",
'protect-locked-access' => "Mayong permiso an account mo na magbàgo kan tangga nin proteksyon.
Uya an ngonyan na mga pwesto kan pahinang '''$1''':",
'protect-cascadeon' => 'Pigproprotektaran ining pahina sa ngonyan ta sabay ini sa mga nasunod na {{PLURAL:$1|pahina, na may|mga pahina, na may}} proteksyong katarata na nakaandar. Pwede mong bàgohon an tangga nin proteksyon kaining pahina, pero mayò ning epekto sa proteksyong katarata.',
'protect-default' => '(normal)',
'protect-fallback' => 'Mangipo kan "$1" na permiso',
'protect-level-autoconfirmed' => 'Bagáton an mga paragamit na dai nakarehistro',
'protect-level-sysop' => 'Para sa mga sysop sana',
'protect-summary-cascade' => 'katarata',
'protect-expiring' => 'mápasó sa $1 (UTC)',
'protect-cascade' => 'Protektarán an mga pahinang nakaiba sa pahinang ini (proteksyon katarata)',
'protect-cantedit' => 'Dai mo mariribayan an mga tanggá kan proteksyon kaining pahina huli ta mayò ka nin permiso na ligwatón ini.',
'protect-expiry-options' => '1ng ora:1 hour,1ng aldaw:1 day,1ng semana:1 week,2ng semana:2 weeks,1ng bulan:1 month,3ng bulan:3 months,6 na bulan:6 months,1ng taon:1 year,daing kasagkoran:infinite',
'restriction-type' => 'Permiso:',
'restriction-level' => 'Tanggá nin restriksyon:',
'minimum-size' => 'Pinaka sadit na sukol',
'maximum-size' => 'Pinaka dakula na sukol:',
'pagesize' => '(oktetos)',

# Restrictions (nouns)
'restriction-edit' => 'Hirahón',
'restriction-move' => 'Ibalyó',
'restriction-create' => 'Maggibo',
'restriction-upload' => 'Magkarga',

# Restriction levels
'restriction-level-sysop' => 'protektado',
'restriction-level-autoconfirmed' => 'semi-protektado',
'restriction-level-all' => 'maski anong nibel',

# Undelete
'undelete' => 'Hilingón ang mga pinarang pahina',
'undeletepage' => 'Hilingón asin ibalik an mga pinarang pahina',
'viewdeletedpage' => 'Hilingón an mga pinarang pahina',
'undeletepagetext' => 'An mga minasunod na páhina pigparâ na alagad yaon pa sa archibo asin pwedeng ibalik. Dapat limpiahan an archibo kada periodo.',
'undeleteextrahelp' => "Kun boot mong ibalik an enterong páhina, dai markahan an gabos na mga kahon asin pindoton an '''''Restore'''''. Para magpili nin ibábalik, markahan an mga kahon na boot mong ibalik, asin pindoton an '''''Restore'''''. An pagpindot kan '''''Reset''''' makakalimpya nin kampo kan mga kommento
asin an gabos na mga kahon-marka.",
'undeleterevisions' => '$1 {{PLURAL:$1|na pagriribay|na mga pagriribay}} na nakaarchibo',
'undeletehistory' => "Kun ibabalik mo an páhinang ini, an gabos na mga pagribay mabalik sa historial.
Kun igwang piggibong sarong bâgong páhinang may parehong pangaran antes ka pagparâ, an presenteng pagribay maluwas sa historial, asin an presenteng pagribay kan tunay na páhina dai enseguidang mariribayan. Giromdomon man tabî na an mga restriksyon sa mga pagriribay nin ''file'' mawawarâ sa pagbalik.",
'undeleterevdel' => "Dai madadagos an pagbalik kan pagparâ kun an resulta kaini mapaparâ kan pagribay an nasa páhinang pinaka itaas.
Sa mga kasong ini, dapat halîon an mga marka o dai itâgo an mga pinaka bâgong pigparâ na mga pagribay. Dai ibabalik an mga pagribay kan mga ''file'' na mayo kan permisong hilingon.",
'undeletehistorynoadmin' => 'Pigparâ na ining péhina. Mahihiling an rason sa epitome sa babâ, kasabay sa mga detalye kan mga parágamit na naghira kaining páhina bago pigparâ. Sa mga administrador sana maipapahiling an mga pagribay sa mismong tekstong ini.',
'undelete-revision' => 'Pigparâng pagribay ni $3 kan $1 (sa $2):',
'undeleterevision-missing' => 'Dai pwede o nawawarang pagribay. Pwede ser na salâ an takod, o
binalik an na pagribay o hinalî sa archibo.',
'undeletebtn' => 'Ibalik',
'undeletereset' => 'Ipwesto giraray',
'undeletecomment' => 'Komento:',
'undeletedrevisions' => '$1 na (mga) pagriribay an binalík',
'undeletedrevisions-files' => "$1 na (mga) pagribay asin $2 na (mga) binalik na ''file''",
'undeletedfiles' => "$1 (mga) ''file'' an binalik",
'cannotundelete' => 'Naprakaso an pagbalik kan pigparâ; pwede ser an binawi an pagparâ kan páhina kan ibang parágamit.',
'undeletedpage' => "'''binalik na an $1 '''

Ikonsultar an [[Special:Log/delete|historial nin pagparâ]] para mahiling an lista nin mga kaaaging pagparâ asin pagbalik.",
'undelete-header' => 'Hilingon an [[Special:Log/delete|historial kan pagparâ]] kan mga kaaagi pa sanang pinarang páhina.',
'undelete-search-box' => 'Hanapón an mga pinarang pahina',
'undelete-search-prefix' => 'Hilingón an mga pahinang nagpopoon sa:',
'undelete-search-submit' => 'Hanápon',
'undelete-no-results' => 'Mayong nahanap na páhinang angay sa archibo kan mga pigparâ.',
'undelete-filename-mismatch' => "Dai pwedeng bawîon an pagparâ sa ''file'' sa pagkarhay na may tatâk nin oras na $1: dai kapadis an ''filename''",
'undelete-bad-store-key' => "Dai pwedeng bawîon an pagparâ nin ''file'' na pagpakarhay na may taták nin oras na $1: nawara an ''file'' bago an pagparâ.",
'undelete-cleanup-error' => "May salâ pagparâ kan ''file'' na archibong \"\$1\".",
'undelete-missing-filearchive' => "Dai maibalik an archibo kan ''file'' may na  ID $1 ta mayô ini sa base nin datos. Pwede ser na pigparâ na ini.",
'undelete-error-short' => "May salâ sa pagbalik kan pigparang ''file'': $1",
'undelete-error-long' => "May mga salâ na nasabat mientras sa pigbabalik an pigparang ''file'':

$1",

# Namespace form on various pages
'namespace' => 'Liang-liang:',
'invert' => 'Pabaliktadón an pinili',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Mga kontribusyon kan parágamit',
'mycontris' => 'Mga ambág ko',
'contribsub2' => 'Para sa $1 ($2)',
'nocontribs' => 'Mayong mga pagbabago na nahanap na kapadis sa ining mga criteria.',
'uctop' => '(alituktok)',
'month' => 'Poon bulan (asin mas amay):',
'year' => 'Poon taon (asin mas amay):',

'sp-contributions-newbies' => 'Ipahiling an mga kontribusión kan mga bagong kuenta sana',
'sp-contributions-newbies-sub' => 'Para sa mga bàgong account',
'sp-contributions-blocklog' => 'Bagáton an usip',
'sp-contributions-deleted' => 'Paráon an mga ambág kan paragamít',
'sp-contributions-talk' => 'Pag-oláyan',
'sp-contributions-userrights' => 'Pagmaneho kan mga derecho nin paragamit',
'sp-contributions-search' => 'Maghanap nin mga kontribusyon',
'sp-contributions-username' => 'IP o ngaran kan parágamit:',
'sp-contributions-submit' => 'Hanápon',

# What links here
'whatlinkshere' => 'An nakatakód digdí',
'whatlinkshere-title' => 'Mga pahinang nakatakód sa $1',
'whatlinkshere-page' => 'Pahina:',
'linkshere' => "An mga minasunod na pahina nakatakod sa '''[[:$1]]''':",
'nolinkshere' => "Mayong pahinang nakatakod sa '''[[:$1]]'''.",
'nolinkshere-ns' => "Mayong pahina na nakatakod sa '''[[:$1]]''' sa piniling ngaran-espacio.",
'isredirect' => 'ilikay an pahina',
'istemplate' => 'kabali',
'whatlinkshere-prev' => '{{PLURAL:$1|nakaagi|nakaaging $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|sunod|sunod na $1}}',
'whatlinkshere-links' => '← mga takod',
'whatlinkshere-filters' => 'Mga pansarà',

# Block/unblock
'blockip' => 'Bagáton an paragamit',
'blockiptext' => 'Gamiton an pormularyo sa babâ para bagaton an pagsurat kan sarong espesipikong IP o ngaran nin parágamit.
Dapat gibohon sana ini para maibitaran vandalismo, asin kompirmi sa [[{{MediaWiki:Policy-url}}|palakaw]].
Magkaag nin espisipikong rason (halimbawa, magtao nin ehemplo kan mga páhinang rinaot).',
'ipadressorusername' => 'direksyon nin IP o gahâ:',
'ipbexpiry' => 'Pasó:',
'ipbreason' => 'Rason:',
'ipbreasonotherlist' => 'Ibang rason',
'ipbreason-dropdown' => "*Mga komon na rason sa pagbagat
** Nagkakaag nin salang impormasyon
** Naghahalî nin mga laog kan páhina
** Nagkakaag nin mga takod na ''spam'' kan mga panluwas na ''site''
** Nagkakaag nin kalokohan/ringaw sa mga pahina
** Gawî-gawing makatakót/makauyám
** Nag-aabuso nin mga lain-lain na ''account''
** Dai akong ngaran nin parágamit",
'ipbcreateaccount' => 'Pugulon an pagibo nin kuenta.',
'ipbemailban' => 'Pugolan ining paragamit na magpadara nin e-surat',
'ipbenableautoblock' => 'Enseguidang bagaton an huring direccion nin  IP na ginamit kaining paragamit, asin kon ano pang ibang IP na proprobaran nindang gamiton',
'ipbsubmit' => 'Bagáton ining parágamit',
'ipbother' => 'Ibang oras:',
'ipboptions' => '2ng oras:2 hours,1ng aldaw:1 day,3ng aldaw:3 days,1ng semana:1 week,2ng semana:2 weeks,1ng bulan:1 month,3ng bulan:3 months,6 na bulan:6 months,1ng taon:1 year,daing kasagkoran:infinite',
'ipbotheroption' => 'iba',
'ipbotherreason' => 'Iba/dugang na rasón:',
'ipbhidename' => 'Itago an ngaran in paragamit para dai mahiling sa historial nin pagbagat, nakaandar na lista nin binagat asin lista nin paragamit',
'badipaddress' => 'Dai pwede ining IP',
'blockipsuccesssub' => 'Nagibo na an pagbagát',
'blockipsuccesstext' => 'Binagat si [[Special:Contributions/$1|$1]].
<br />Hilingon an [[Special:BlockList|lista nin mga binagat na IP]] para marepaso an mga binagat.',
'ipb-edit-dropdown' => 'Hirahón an mga rasón sa pagbabagát',
'ipb-unblock-addr' => 'Paagihon $1',
'ipb-unblock' => 'Bawion an pagbagat nin ngaran nin paragamit o direccion nin IP',
'ipb-blocklist' => 'Hilingon an mga presenteng binagat',
'unblockip' => 'Paagihon an parâgamit',
'unblockiptext' => 'Gamiton an pormulario sa baba para puede giraray suratan an dating binagat na direccion nin IP address o ngaran nin paragamit.',
'ipusubmit' => 'Bawion an pagbagat kaining direccíón',
'unblocked' => 'Binawi na an pagbagat ki [[User:$1|$1]]',
'unblocked-id' => 'Hinali na an bagat na $1',
'ipblocklist' => 'Lista nin mga direksyon nin IP asin ngaran nin paragamit na binagat',
'ipblocklist-legend' => 'Hanapon an sarong binagát na paragamit',
'ipblocklist-submit' => 'Hanápon',
'infiniteblock' => 'daing siring',
'expiringblock' => 'minapasó $1 $2',
'anononlyblock' => 'anon. sana',
'noautoblockblock' => 'pigpopondo an enseguidang pagbagat',
'createaccountblock' => 'binagat an paggibo nin kuenta',
'emailblock' => 'binagát an e-surat',
'ipblocklist-empty' => 'Mayong laog an lista nin mga binagat.',
'ipblocklist-no-results' => 'Dai nabagat an hinagad na direccion nin IP o ngaran nin paragamit.',
'blocklink' => 'bagáton',
'unblocklink' => 'paagihon',
'change-blocklink' => 'sanglián an pagbagat',
'contribslink' => 'mga ambág',
'autoblocker' => 'Enseguidang binagat an saimong direccion nin IP ta kaaaging ginamit ini ni "[[User:$1|$1]]". An rason nin pagbagat ni $1: "$2"',
'blocklogpage' => 'Usip nin pagbagat',
'blocklogentry' => 'binagat na [[$1]] na may oras nin pagpaso na $2 $3',
'blocklogtext' => 'Ini an historial kan pagbagat asin pagbawi sa pagbagat nin mga paragamit. An mga enseguidang binagat na direccion nin
IP dai nakalista digdi. Hilingon an [[Special:BlockList|IP lista nin mga binagat]] para sa lista nin mga nakaandar na mga pagpangalad buda mga pagbagat.',
'unblocklogentry' => 'binawi an pagbagat $1',
'block-log-flags-anononly' => 'Mga paragamit na anónimo sana',
'block-log-flags-nocreate' => "pigpopondohán an paggibo nin ''account'",
'block-log-flags-noautoblock' => 'pigpopondo an enseguidang pagbagat',
'block-log-flags-noemail' => 'binagát an e-surat',
'range_block_disabled' => 'Pigpopondo an abilidad kan sysop na maggibo nin bagat na hilera.',
'ipb_expiry_invalid' => 'Dai pwede ini bilang oras kan pagpasó.',
'ipb_already_blocked' => 'Dating binagat na si "$1"',
'ipb_cant_unblock' => 'Error: Dai nahanap an ID nin binagat na $1. Puede ser na dati nang binawi an pagbagat kaini.',
'ip_range_invalid' => 'Dai pwede ining serye nin IP.',
'proxyblocker' => 'Parabagát na karibay',
'proxyblockreason' => 'Binagat an saimong direccion nin IP ta ini sarong bukas na proxy. Apodon tabi an saimong Internet service provider o tech support asin ipaaram sainda ining seriosong problema nin seguridad.',
'proxyblocksuccess' => 'Tapos.',
'sorbsreason' => 'An saimong direccion in IP nakalista na bukas na proxy sa DNSBL na piggagamit kaining sitio.',
'sorbs_create_account_reason' => "An IP mo nakalista bilang bukás ''proxy'' sa DNSBL na piggagamit kaining ''site''. Dai ka pwedeng maggibo ''account''",

# Developer tools
'lockdb' => 'Ikandado an base nin datos',
'unlockdb' => 'Ibukás an base nin datos',
'lockconfirm' => 'Iyo, boot kong ikandado an base kan datos.',
'unlockconfirm' => 'Iyo, boot kong bukasan an base kan datos.',
'lockbtn' => 'Isará an base nin datos',
'unlockbtn' => 'Ibukás an base nin datos',
'locknoconfirm' => 'Dai mo pigtsekan an kahon para sa kompirmasyon.',
'lockdbsuccesssub' => 'Kinandado na an base nin datos',
'unlockdbsuccesssub' => 'Hinalî an kandado nin base nin datos',
'lockdbsuccesstext' => 'Pigkandado na an base kan datos.
<br />Giromdomon na [[Special:UnlockDB|halîon an kandado]] pagkatapos kan pagmantenir.',
'unlockdbsuccesstext' => 'Pigbukasan na an base nin datos.',
'lockfilenotwritable' => "An ''file'' na kandado kan base nin datos dai nasusuratan. Para makandado o mabukasan an bse nin datos, kaipuhan na nasusuratan ini kan web server.",
'databasenotlocked' => 'Dai nakakandado an base nin datos.',

# Move page
'move-page-legend' => 'Ibalyó an páhina',
'movepagetext' => "Matatàwan nin bàgong pangaran an sarong pahina na pigbabalyo an gabos na uusipón kaini gamit an pormularyo sa babâ.
An dating titulo magigin redirektang pahina sa bàgong titulo.
Dai babàgohon an mga takod sa dating titulo kan pahina;
seguradohon tabì na mayong doble o raot na mga redirekta.
Ika an responsable sa pagpaseguro na an mga takod nakatokdô kun sain dapat.

Giromdomon tabì na an pahina '''dai''' ibabalyó kun igwa nang pahina sa bàgong titulo, apwera kun mayò ining laog o sarong redirekta asin uusipón nin mga dating pagliwat. An boot sabihon kaini, pwede mong ibalik an dating pangaran kan pahina kun sain ini pigribayan nin pangaran kun napasalà ka, asin dai mo man sosoknongan an presenteng pahina.

'''PATANID!'''
Pwede na dakulà asin dai seguradong pagbàgo ini kan sarong popular na pahina; seguradohon tabì na aram mo an konsekwensya kaini bago magdagos.",
'movepagetalktext' => "An kapadis na olay na páhina enseguidang ibabalyo kasabay kaini '''kun:'''
*Igwa nang may laog na olay na páhina na may parehong pangaran, o
*Halîon mo an marka sa kahon sa babâ.

Sa mga kasong iyan, kaipuhan mong ibalyo o isalak an páhina nin mano-mano kun boot mo.",
'movearticle' => 'Ibalyó an pahina:',
'movenologin' => 'Mayô sa laog',
'movenologintext' => 'Kaipuhan na rehistradong parágamit ka asin si [[Special:UserLogin|nakalaog]] tangarig makabalyó ka nin páhina.',
'movenotallowed' => 'Mayô kang permiso na ibalyó an mga pahina sa wiki na ini.',
'newtitle' => 'Sa bàgong titulong:',
'move-watch' => 'Bantayán ining pahina',
'movepagebtn' => 'Ibalyó an pahina',
'pagemovedsub' => 'Naibalyó na',
'movepage-moved' => '\'\'\'Naihubò na an "$1" sa "$2"\'\'\'',
'articleexists' => 'Igwa nang pahina sa parehong pangaran, o dai pwede an pangaran na pigpilì mo.
Magpilì tabì nin ibang pangaran.',
'talkexists' => "'''Ibinalyo na an mismong pahina, alagad dai naibalyo an pahina nin orolay ta igwa na kaini sa bàgong titulo. Pagsaroon tabì ining duwa nin mano-mano.'''",
'movedto' => 'piglipat sa',
'movetalk' => 'Ibalyo an pahinang orolayan na nakaasociar',
'movelogpage' => 'Ibalyó an usip',
'movelogpagetext' => 'Nasa ibaba an lista kan pahinang pigbalyó.',
'movereason' => 'Rason:',
'revertmove' => 'ibalík',
'delete_and_move' => 'Parâon asin ibalyó',
'delete_and_move_text' => '==Kaipuhan na parâon==

Igwa nang páhina na "[[:$1]]". Gusto mong parâon ini tangarig maibalyó?',
'delete_and_move_confirm' => 'Iyo, parâon an pahina',
'delete_and_move_reason' => 'Pinarâ tangarig maibalyó',
'selfmove' => 'Pareho an páhinang ginikanan asin destinasyon; dai pwedeng ibalyó an sarong páhina sa sadiri.',

# Export
'export' => 'Iluwas an mga pahina',
'exporttext' => 'Pwede mong ipadara an teksto asin historya nin paghirá kan sarong partikular na páhina o grupo nin mga páhina na nakapatos sa ibang XML. Pwede ining ipadara sa ibang wiki gamit an MediaWiki sa paagi kan [[Special:Import|pagpadara nin páhina]].

Para makapadara nin mga páhina, ilaag an mga titulo sa kahon para sa teksto sa babâ, sarong titulo kada linya, dangan pilîon kun boot mo presenteng bersyón asin dating bersyón, na may mga linya kan historya, o an presenteng bersyón sana na may impormasyon manonongod sa huring hirá.

Sa kaso kan huri, pwede ka man na maggamit nin takod, arog kan [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para sa páhinang "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly' => 'Mga presenteng pagpakarhay sana an ibali, bakong an enterong historya',
'exportnohistory' => "----
'''Paisi:''' Dai pigpatogotan an pagpadara kan enterong historya kan mga páhina sa paagi kaining forma huli sa mga rasón dapit sa pagsagibo kaini.",
'export-submit' => 'Ipaluwás',
'export-addcattext' => 'Magdugang nin mga pahina sa kategoryang ini:',
'export-addcat' => 'Magdugang',
'export-download' => "Hapotón ku gustong itagama bilang sarong ''file''",

# Namespace 8 related
'allmessages' => 'Mga mensahe sa sistema',
'allmessagesname' => 'Pangaran',
'allmessagesdefault' => 'Tekstong normal',
'allmessagescurrent' => 'Presenteng teksto',
'allmessagestext' => 'Ini an lista kan mga mensahe sa sistema sa ngaran-espacio na MediaWiki.
Please visit [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [//translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "Dai pwedeng gamiton an '''{{ns:special}}:Allmessages''' ta sarado an '''\$wgUseDatabaseMessages'''.",

# Thumbnails
'thumbnail-more' => 'Padakuláon',
'filemissing' => "Nawawarâ an ''file''",
'thumbnail_error' => 'Error sa paggigibo kan retratito: $1',
'djvu_page_error' => 'luwas sa serye an páhina kan DjVu',
'djvu_no_xml' => 'Dai makua an XML para sa DjVu file',
'thumbnail_invalid_params' => 'Dai pwede an mga parámetro kaining retratito',
'thumbnail_dest_directory' => 'Dai makagibo kan destinasyon kan direktoryo',

# Special:Import
'import' => 'Ilaog an mga páhina',
'importinterwiki' => 'Ipadara an Transwiki',
'import-interwiki-history' => 'Kopyahon an gabos na mga bersyón para sa páhinang ini',
'import-interwiki-submit' => 'Ipalaog',
'import-interwiki-namespace' => 'Ibalyó an mga pahina sa ngaran-espacio:',
'import-comment' => 'Komento:',
'importtext' => "Ipadara tabì an ''file'' hali sa ginikanan na wiki gamit an Special:Export utility, itagama ini sa saimong disk dangan ikarga iyan digdi.",
'importstart' => 'Piglalaog an mga páhina...',
'import-revision-count' => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'importnopages' => 'Mayong mga páhinang ipapadara.',
'importfailed' => 'Bakong matriumpo an pagpadara: $1',
'importunknownsource' => 'Dai aram an tipo kan gigikanan kan ipapadara',
'importcantopen' => "Dai mabukasan an pigpadarang ''file''",
'importbadinterwiki' => 'Salâ an takod na interwiki',
'importnotext' => 'Mayong laog o mayong teksto',
'importsuccess' => 'Matriumpo an pagpadara!',
'importnofile' => "Mayong ipinadarang ''file'' an naikarga.",

# Import log
'importlogpage' => 'Usip nin pagpalaog',
'import-logentry-upload' => "pigpadara an [[$1]] kan pagkarga nin ''file''",
'import-logentry-upload-detail' => '$1 mga pagpakarháy',
'import-logentry-interwiki' => 'na-transwiki an $1',
'import-logentry-interwiki-detail' => '$1 mga pagpakarháy halì sa $2',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'An sakóng pahina',
'tooltip-pt-anonuserpage' => 'An páhina nin páragamit para sa ip na pighihira mo bilang',
'tooltip-pt-mytalk' => 'Pahina nin sakóng olay',
'tooltip-pt-anontalk' => 'Mga olay manonongod sa mga hira halî sa ip na ini',
'tooltip-pt-preferences' => 'Mga kabòtan ko',
'tooltip-pt-watchlist' => 'Lista nin mga pahina na pigbabantayan an mga pagbabàgo',
'tooltip-pt-mycontris' => 'Taytáy kan mga kabòtan ko',
'tooltip-pt-login' => 'Pigaagda kang maglaog, alagad, bako man ining piriritan.',
'tooltip-pt-anonlogin' => 'Pig-aagda kang maglaog, alagad, bakô man ining piriritan.',
'tooltip-pt-logout' => 'Magluwas',
'tooltip-ca-talk' => 'Olay sa pahina nin laog',
'tooltip-ca-edit' => 'Pwede mong hirahón ining pahina. Gamiton tabi an patànaw na butones bago an pagtagama.',
'tooltip-ca-addsection' => 'Magdugang nin komento sa urulay na iní.',
'tooltip-ca-viewsource' => 'Sinagangán ining pahina. Mahihilíng mo an ginikanan.',
'tooltip-ca-history' => 'Mga nakaaging bersyon kaining pahina',
'tooltip-ca-protect' => 'Protektahán ining pahina',
'tooltip-ca-delete' => 'Paraon an pahinang ini',
'tooltip-ca-undelete' => 'Bawîon an mga hirá na piggibo sa páhinang ini bâgo ini pigparâ',
'tooltip-ca-move' => 'Ibalyó an pahinang iní',
'tooltip-ca-watch' => 'Idugang ining páhina sa pigbabantayan mo',
'tooltip-ca-unwatch' => 'Halion ining pahina sa lista nin pigbabantayan mo',
'tooltip-search' => 'Hanápon an {{SITENAME}}',
'tooltip-search-go' => 'Magduman sa pahina na igwa kaining eksaktong pangaran',
'tooltip-search-fulltext' => 'Hanápon an mga pahina para sa tekstong ini',
'tooltip-p-logo' => 'Pangenot na Pahina',
'tooltip-n-mainpage' => 'Bisitahon an Pangenot na Pahina',
'tooltip-n-mainpage-description' => 'Daláwon an pangenot na pahina',
'tooltip-n-portal' => 'Manonongod sa proyekto, an pwede mong gibohon, kun sain mo pwedeng hanapon an mga bagay',
'tooltip-n-currentevents' => 'Hanapon an mga impormasyon na ginikanan sa mga presenteng panyayari',
'tooltip-n-recentchanges' => 'An lista nin mga bàgong pagbabàgo sa wiki.',
'tooltip-n-randompage' => 'Magsàngat nin bàgong pahina',
'tooltip-n-help' => 'An lugar para makatalastás',
'tooltip-t-whatlinkshere' => 'Lista nin gabos na pahinang wiki na nakatakód digdi',
'tooltip-t-recentchangeslinked' => 'Mga kaaaging pagbabàgo sa mga pahinang nakatakod digdi',
'tooltip-feed-rss' => 'Hungit na RSS sa pahinang ini',
'tooltip-feed-atom' => 'Hungit na atomo sa pahinang iní',
'tooltip-t-contributions' => 'Hilingón an lista kan mga kontribusyon kaining paragamit',
'tooltip-t-emailuser' => 'Padarahan nin e-koreo an paragamit na ini',
'tooltip-t-upload' => 'Ikargá an mga ladawan o media files',
'tooltip-t-specialpages' => 'Lista kan gabos na mga espesyal na pahina',
'tooltip-t-print' => 'Naipiprint na bersyon kaining pahina',
'tooltip-t-permalink' => 'Permanenteng takod sa bersyon kaining páhina',
'tooltip-ca-nstab-main' => 'Hilingón an pahina nin laog',
'tooltip-ca-nstab-user' => 'Hilingón an pahina nin paragamit',
'tooltip-ca-nstab-media' => "Hilingón an pahina kan ''media''",
'tooltip-ca-nstab-special' => 'Pahinang espesyal ini, dai mo ini pwedeng hirahón',
'tooltip-ca-nstab-project' => 'Hilingón an pahina kan proyekto',
'tooltip-ca-nstab-image' => 'Hilingón an pahina kan retrato',
'tooltip-ca-nstab-mediawiki' => "Hilingón an ''system message''",
'tooltip-ca-nstab-template' => 'Hilingón an templato',
'tooltip-ca-nstab-help' => 'Hilingón an pahina nin tabang',
'tooltip-ca-nstab-category' => 'Hilingón an pahina kan kategorya',
'tooltip-minoredit' => 'Kurítan iní bilang sadít na paglíwat',
'tooltip-save' => 'Itagáma an saímong mga pagbabàgo',
'tooltip-preview' => 'Tànawon an saimong mga pagbabàgo, gamitón tabì ini bàgo magtagáma!',
'tooltip-diff' => 'Ipahilíng an mga pagbabàgong ginibo mo sa teksto.',
'tooltip-compareselectedversions' => 'Hilingón an mga kaibhán sa duwáng piníling bersyon kainíng pahina.',
'tooltip-watch' => 'Idugang ining pahina sa pigbabantayan mo',
'tooltip-recreate' => 'Gibohon giraray an páhina maski na naparâ na ini',
'tooltip-upload' => 'Pônan an pagkarga',

# Stylesheets
'common.css' => '/** an CSS na pigbugtak digdi maiaaplikar sa gabos na mga skin */',
'monobook.css' => '/* an CSS na pigbugtak digdi makakaapektar sa mga parágamit kan Monobook skin */',

# Scripts
'common.js' => '/* Arin man na JavaScript digdi maikakarga para sa gabos na mga parágamit sa kada karga kan páhina. */',
'monobook.js' => '/* Deprecado; gamiton an [[MediaWiki:common.js]] */',

# Metadata
'notacceptable' => "Dai pwedeng magtao nin datos an ''wiki server'' sa ''format'' na pwedeng basahon kan kompyuter mo.",

# Attribution
'anonymous' => '(Mga)paragamit na anónimo kan {{SITENAME}}',
'siteuser' => 'Paragamit kan {{SITENAME}} na si $1',
'lastmodifiedatby' => 'Ining páhina huring binago sa $2, $1 ni $3.',
'othercontribs' => 'Binase ini sa trabaho ni $1.',
'others' => 'iba pa',
'siteusers' => '(Mga)paragamit kan {{SITENAME}} na si $1',
'creditspage' => 'Mga krédito nin páhina',
'nocredits' => 'Mayong talastas kan kredito para sa ining pahina.',

# Spam protection
'spamprotectiontitle' => "Proteksyon kan ''spam filter''",
'spamprotectiontext' => "An páhinang gusto mong itagama pigbagat kan ''spam filter''. Kawsa gayod ini kan sarong takod sa sarong panluwas na 'site'.",
'spamprotectionmatch' => "An minasunod na teksto iyo an nagbukas kan ''spam filter'' mi: $1",
'spambot_username' => 'paglimpya nin spam sa MediaWiki',
'spam_reverting' => 'Mabalik sa huring bersion na mayong takod sa $1',
'spam_blanking' => 'An gabos na mga pahirá na may takod sa $1, pigblablanko',

# Skin names
'skinname-standard' => 'Klasiko',
'skinname-simple' => 'Simple',
'skinname-modern' => 'Bago',

# Patrolling
'markaspatrolleddiff' => 'Markahan bilang pigpapatrolya',
'markaspatrolledtext' => 'Markahan iníng pahina na pigpapatrolya',
'markedaspatrolled' => 'Minarkahan na pigpapatrolya',
'markedaspatrolledtext' => 'Minarkahan bilang pigpapatrolya an piníling pagpakarháy.',
'rcpatroldisabled' => 'Pigpopogólan an mga Pagpatrolya kan mga Nakakaági pa sanáng Pagbabàgo',
'rcpatroldisabledtext' => 'Pigpopogólan ngùna an Pagpatrolya kan mga Nakakaági pa sanáng Pagbabàgo.',
'markedaspatrollederror' => 'Dai mamamarkahan bilang pigpapatrolya',
'markedaspatrollederrortext' => 'Kaipúhan mong magpilì nin pagpakarháy na mamarkahan bilang pigpapatrolya.',
'markedaspatrollederror-noautopatrol' => 'Daí ka pigtotogótan na markahan an sadíri mong pababàgo bilang pigpapatrolya.',

# Patrol log
'patrol-log-page' => 'Laóg kan Pigpapatrolya',

# Image deletion
'deletedrevision' => 'Pigparâ an lumang pagribay na $1.',
'filedeleteerror-short' => 'Salâ sa pagparà kan dokumento: $1',
'filedeleteerror-long' => "May mga nasabat na salâ mientras na pigpaparâ an ''file'':

$1",
'filedelete-missing' => "An ''file'' na \"\$1\" dai pwedeng paraon, ta mayô man ini.",
'filedelete-old-unregistered' => 'An piniling pagpakaray na "$1" wara man sa base nin datos.',
'filedelete-current-unregistered' => "Mayô sa base nin datos an piniling ''file'' na \"\$1\".",
'filedelete-archive-read-only' => 'An direktoryong archibo na "$1" dai nasusuratan kan webserver.',

# Browsing diffs
'previousdiff' => '← Nakaaging kaibhán',
'nextdiff' => 'Kaibhán pa→',

# Media information
'mediawarning' => "'''Patanid''': May ''malicious code'' sa ''file'' na ini, kun gigibohon ini pwede ser na maraot an saimong ''system''.",
'imagemaxsize' => 'Limitaran an mga ladawan sa mga páhinang deskripsyon kan ladawan sa:',
'thumbsize' => 'Sokol nin retratito:',
'widthheightpage' => '$1 × $2, $3 mga pahina',
'file-info' => "sokol kan ''file'': $1, tipo nin MIME: $2",
'file-info-size' => "$1 × $2 na pixel, sokol kan ''file'': $3, tipo nin MIME: $4",
'file-nohires' => 'Mayong mas halangkáw na resolusyon.',
'svg-long-desc' => 'file na SVG, haros $1 × $2 pixels, sokol kan file: $3',
'show-big-image' => 'Todong resolusyon',

# Special:NewFiles
'newimages' => 'Galeria nin mga bàgong file',
'imagelisttext' => "Mahihiling sa baba an lista nin mga  '''$1''' {{PLURAL:$1|file|files}} na linain $2.",
'showhidebots' => '($1 na bots)',
'noimages' => 'Mayong mahihilíng.',
'ilsubmit' => 'Hanápon',
'bydate' => 'sa petsa',
'sp-newimages-showfrom' => 'Hilingón an mga retratong nagpopoon sa $1',

# Bad image list
'bad_image_list' => 'An husay iyó an minasunód:

An mga nakataytáy saná (mga taytáy na nagpopoón sa *) iyó an kaayon.
An inot na takód sa taytáy kaipohan na saróng takód sa saróng saláng file.
Anó man na minasunód na takód sa ginikanan na taytáy iyó an kaayon sa mga paglain, i.e. mga pahina na may file na maluwás sa laog kan taytáy.',

# Metadata
'metadata' => 'Metadatos',
'metadata-help' => 'Igwang dugang na impormasyon ining file na pwedeng idinugang hali sa digital camera o scanner na piggamit tangarig magibo ini. Kun namodipikar na file hali sa orihinal nyang kamogtakan, an ibang mga detalye pwedeng dai mahiling sa minodipikar na ladawan.',
'metadata-expand' => 'Ipahilíng an gabós na detalye',
'metadata-collapse' => 'Itagò an gabós na detalye',

# EXIF tags
'exif-imagewidth' => 'Lakbáng',
'exif-imagelength' => 'Langkáw',
'exif-imagedescription' => 'Titulo kan retrato',
'exif-make' => 'Tagagibo nin kamera',
'exif-model' => 'Modelo nin kamera',
'exif-artist' => 'Kagsúrat',
'exif-usercomment' => 'Mga komento kan paragamít',
'exif-aperturevalue' => 'Pagkabukás',
'exif-brightnessvalue' => 'Kaliwanagan',
'exif-lightsource' => 'Ginikánan nin liwánag',
'exif-flash' => 'Kikilát',
'exif-flashenergy' => 'Kakusogan nin kikilát',
'exif-filesource' => 'Ginikánan nin dokumento',
'exif-contrast' => 'Kontraste',
'exif-imageuniqueid' => 'Unikong ID kan ladawan',
'exif-gpstrack' => 'Direksyon kan paghirô',
'exif-gpsimgdirection' => 'Direksyon kan ladáwan',
'exif-gpsdestdistance' => 'Distansya sa destinasyon',

'exif-unknowndate' => 'Daí aram an petsa',

'exif-componentsconfiguration-0' => 'mayô man ini',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0' => 'Dai aram',
'exif-meteringmode-255' => 'Iba pa',

'exif-lightsource-4' => 'Kitkilát',
'exif-lightsource-9' => 'Magayón na panahón',
'exif-lightsource-255' => 'Mga ibang ginikanan nin ilaw',

'exif-focalplaneresolutionunit-2' => 'pulgada',

'exif-scenetype-1' => 'Direktong naretratong ladawan',

'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Eksenang banggi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometros kada oras',
'exif-gpsspeed-m' => 'Milya kada oras',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tunay na direksyon',
'exif-gpsdirection-m' => 'Direksyón nin batobalani',

# External editor support
'edit-externally' => 'Hirahón an file gamit an panluwas na aplikasyon',
'edit-externally-help' => 'Hilingón an  [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] para sa iba pang mga impormasyon.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'gabos',
'namespacesall' => 'gabós',
'monthsall' => 'gabos',

# E-mail address confirmation
'confirmemail' => "Kompirmaron an ''e''-surat",
'confirmemail_noemail' => "Mayô kang pigkaag na marhay na ''e''-surat sa saimong [[Special:Preferences|mga kabôtan nin parágamit]].",
'confirmemail_text' => "Kaipuhan an pag-''validate'' kan saimong e-koreo bago ka makagamit nin ''features'' na e-koreo. Pindoton an butones sa babâ tangarig magpadara nin kompirmasyon sa saimong e-koreo. An surat igwang takod na may koda; ikarga an takod sa browser para makompirmar na valido an saimong e-koreo.",
'confirmemail_pending' => "May pigpadara nang kompirmasyon sa ''e''-surat mo; kun kagigibo mo pa sana kan saimong ''account'', maghalat ka nin mga dikit na minutos bago ka maghagad giraray nin bâgong ''code''.",
'confirmemail_send' => 'Magpadará nin kompirmasyon',
'confirmemail_sent' => "Napadará na an kompirmasyon sa ''e''-surat.",
'confirmemail_oncreate' => "May pigpadara nang kompirmasyon sa saimong ''e''-surat.
Dai man kaipuhan ini para makalaog, pero kaipuhan mong itao ini bago
ka makagamit nin ''features'' na basado sa ''e''-surat sa wiki.",
'confirmemail_sendfailed' => "Dai napadará an kompirmasyon kan ''e''-surat. Seguradohon tabî kun igwang sala.

Pigbalik: $1",
'confirmemail_invalid' => 'Salâ an kódigo nin konpirmasyon. Puede ser napasó na an kódigo.',
'confirmemail_needlogin' => "Kaipuhan tabi $1 ikompirmar an saimong ''e''-surat.",
'confirmemail_success' => "Nakompirmar na an saimong ''e''-surat. Pwede ka nang maglaog asin mag-ogma sa wiki.",
'confirmemail_loggedin' => "Nakompirmar na an saimong ''e''-surat.",
'confirmemail_error' => 'May nasalâ sa pagtagama kan saimong kompirmasyon.',
'confirmemail_subject' => "kompirmasyón {{SITENAME}} kan direksyón nin ''e''-surat",
'confirmemail_body' => 'May paragamit, pwedeng ika, halì sa IP na $1, na nagrehistro nin account na
"$2" na igwang e-koreo sa {{SITENAME}}.

Tangarig makompirmar na talagang saimo ining account asin makagamit nin e-koreo sa {{SITENAME}}, bukasán ining takod sa saimong browser:

$3

Kun *bakô* ka ini, dai sunodón an takod. Mapaso sa $4 inning koda nin kompirmasyon.',

# Scary transclusion
'scarytranscludedisabled' => '[Pigpopogolan an transcluding na Interwiki]',
'scarytranscludefailed' => '[Nabigô an pagkua kan templato para sa $1; despensa]',
'scarytranscludetoolong' => '[halabaon an URL; despensa]',

# Delete conflict
'deletedwhileediting' => 'Patanid: Pigparâ na an pahinang ini antes na nagpoon kan maghirá!',
'confirmrecreate' => "Si [[User:$1|$1]] ([[User talk:$1|olay]]) pigparâ ining páhina pagkatapos mong magpoon kan paghira ta:
: ''$2''
Ikonpirmar tabi na talagang gusto mong gibohon giraray ining pahina.",
'recreate' => 'Gibohón giraray',

# action=purge
'confirm_purge_button' => 'Sige',
'confirm-purge-top' => 'Halîon an an aliho kaining páhina?',

# Multipage image navigation
'imgmultipageprev' => '← nakaaging pahina',
'imgmultipagenext' => 'sunod na pahina →',
'imgmultigo' => 'Dumanán!',

# Table pager
'ascending_abbrev' => 'skt',
'descending_abbrev' => 'ba',
'table_pager_next' => 'Sunod na páhina',
'table_pager_prev' => 'Nakaaging páhina',
'table_pager_first' => 'Enot na páhina',
'table_pager_last' => 'Huring páhina',
'table_pager_limit' => 'Ipahiling an $1 na aytem kada páhina',
'table_pager_limit_submit' => 'Dumanán',
'table_pager_empty' => 'Mayong resulta',

# Auto-summaries
'autosumm-blank' => 'Pighahalî an gabos na laog sa páhina',
'autosumm-replace' => "Pigriribayan an páhina nin '$1'",
'autoredircomment' => 'Piglilikay sa [[$1]]',
'autosumm-new' => 'Bâgong páhina: $1',

# Live preview
'livepreview-loading' => 'Pigkakarga…',
'livepreview-ready' => 'Pigkakarga… Magpreparar!',
'livepreview-failed' => 'Dae nakapoon an direktong patânaw! Probaran tabî an patânaw na normal.',
'livepreview-error' => 'Dai nakakabit: $1 "$2". Hilingón tabî an normal na patânaw.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'An mga pagbalyó na mas bâgo sa $1 na segundo pwedeng dai pa mahiling sa listang ini.',
'lag-warn-high' => "Nin huli sa ''high database server lag'', an mga pagbabâgo na mas bâgo sa $1 na segundo pwedeng dai pa ipahiling sa listang ini.",

# Watchlist editor
'watchlistedit-numitems' => 'An saimong pigbabantayan igwang {{PLURAL:$1|1 titulo|$1 mga titulo}}, apwera kan mga páhina kan olay.',
'watchlistedit-noitems' => 'Mayong mga titulo an pigbabantayan mo.',
'watchlistedit-normal-title' => 'Hirahón an pigbabantayan',
'watchlistedit-normal-legend' => 'Halion an mga titulo sa pigbabantayan',
'watchlistedit-normal-explain' => 'Mahihiling sa babâ an mga titulo na nasa pigbabantayan mo.
Tangarig maghalì nin titulo, markahan an kahon sa gilid kaini, dangan pindotón an Tangkasón an mga Titulo. Pwede mo man na [[Special:EditWatchlist/raw|hirahón an bàgong lista]].',
'watchlistedit-normal-submit' => 'Tangkasón an mga Titulo',
'watchlistedit-normal-done' => 'Pigtangkas an {{PLURAL:$1|1 an titulo|$1 mga titulo}} sa saimong pigbabantayan:',
'watchlistedit-raw-title' => 'Hirahón an bàgong pigbabantayan',
'watchlistedit-raw-legend' => 'Hirahón an bàgong pigbabantayan',
'watchlistedit-raw-explain' => 'Mahihiling sa babâ an mga titulo na nasa pigbabantayan mo, asin pwede ining hirahón sa paagi nin pagdugang sagkod paghalì sa lista;
sarong titulo kada linya.
Pag tapos na, lagatikón an Bàgohón an Pigbabantayan.
Pwede mo man [[Special:EditWatchlist|gamiton an standard editor]].',
'watchlistedit-raw-titles' => 'Mga titulo:',
'watchlistedit-raw-submit' => 'Bàgohón an Pigbabantayan',
'watchlistedit-raw-done' => 'Binàgo na an saimong pigbabantayan.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 an titulong|$1 mga titulong}} idinugang:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 an titulong|$1 mga titulong}} hinalì:',

# Watchlist editing tools
'watchlisttools-view' => 'Hilingón an mga katakód na pagbabàgo',
'watchlisttools-edit' => 'Hilingón asin ligwatón an pigbabantayan',
'watchlisttools-raw' => 'Hirahón an bàgong pigbabantayan',

# Special:Version
'version' => 'Bersyon',

# Special:SpecialPages
'specialpages' => 'Mga espesyal na pahina',
'specialpages-group-other' => 'Iba pang mga espesyal na pahina',
'specialpages-group-login' => 'Maglaóg/ maggíbo',
'specialpages-group-changes' => 'Nakakaági pa sanáng mga pagbàgo asín laóg',

# Special:BlankPage
'blankpage' => 'Blangkong pahina',
'intentionallyblankpage' => 'Pigtuyong blangko an pahinang ini',

# Special:Tags
'tags-edit' => 'liwatón',

);
