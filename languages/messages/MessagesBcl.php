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
 * @author Hoo
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
	'currentmonth'              => array( '1', 'BULANNGONYAN', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'NGARANBULANNGONYAN', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'ALDAWNGONYAN', 'CURRENTDAY' ),
	'currentyear'               => array( '1', 'TAONNGONYAN', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'PANAHONNGONYAN', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ORASNGONYAN', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'LOKALBULAN', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'NGARANLOKALBULAN', 'LOCALMONTHNAME' ),
	'localday'                  => array( '1', 'LOKALALDAW', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'LOKALALDAW2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'NGARANLOKALALDAW', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'LOKALTAON', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'LOKALPANAHON', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'LOKALORAS', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'NUMEROKANPAHINA', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NUMEROKANARTIKULO', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NUMEROKANDOKUMENTO', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NUMEROKANPARAGAMIT', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'NUMEROKANLIGWAT', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'NGARANKANPAHINA', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'KAGNGARANKANPAHINA', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NGARANESPASYO', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'KAGNGARANESPASYO', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'OLAYESPASYO', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'KAGOLAYESPASYO', 'TALKSPACEE' ),
	'fullpagename'              => array( '1', 'TODONGNGARANKANPAHINA', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'KAGNGARANKANTODONGNGARANKANPAHINA', 'FULLPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NGARANKANPAHINANINOLAY', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'KAGNGARANKANPAHINANINOLAY', 'TALKPAGENAMEE' ),
	'msg'                       => array( '0', 'MSH', 'MSG:' ),
	'img_right'                 => array( '1', 'too', 'right' ),
	'img_left'                  => array( '1', 'wala', 'left' ),
	'img_none'                  => array( '1', 'mayò', 'none' ),
	'img_center'                => array( '1', 'sentro', 'tangâ', 'center', 'centre' ),
	'img_framed'                => array( '1', 'nakakawadro', 'kwadro', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'daing kwadro', 'frameless' ),
	'img_page'                  => array( '1', 'pahina=$1', 'pahina $1', 'page=$1', 'page $1' ),
	'localurl'                  => array( '0', 'LOKALURL', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALURLE', 'LOCALURLE:' ),
	'currentweek'               => array( '1', 'SEMANANGONYAN', 'CURRENTWEEK' ),
	'localweek'                 => array( '1', 'LOKALSEMANA', 'LOCALWEEK' ),
	'plural'                    => array( '0', 'DAKOL:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'TODONGURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'TODONGURLE:', 'FULLURLE:' ),
	'language'                  => array( '0', '#TATARAMON', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'TATARAMONKANLAOG', 'TATARAMONLAOG', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'            => array( '1', 'NUMEROKANTAGAMATO', 'NUMBEROFADMINS' ),
	'padleft'                   => array( '0', 'PADWALA', 'PADLEFT' ),
	'padright'                  => array( '0', 'PADTOO', 'PADRIGHT' ),
	'filepath'                  => array( '0', 'FILEDALAN', 'FILEPATH:' ),
	'hiddencat'                 => array( '1', '__NAKATAGONGKAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PAHINASAKATEGORYA', 'PAHINASAKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'PAHINASOKOL', 'PAGESIZE' ),
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
'tog-watchcreations' => 'Idagdag an mga pahina na ako an nagmukna asin an mga sagunson na ako an nagkarga sa sakong bantay-listahan',
'tog-watchdefault' => 'Idagdag an mga pahina asin mga sagunson na ako an nagliwat sa sakong bantay-listahan',
'tog-watchmoves' => 'Idagdag an mga pahina asin mga sagunson na ako an nagbalyo sa sakong bantay-listahan',
'tog-watchdeletion' => 'Idagdag an mga pahina asin mga sagunson na ako an nagpura sa sakong bantay-listahan',
'tog-minordefault' => 'Markahán gabos na saradit na pagliwat sa paaging panugmad',
'tog-previewontop' => 'Ipahilíng an patànaw bàgo an kahon nin paghirá',
'tog-previewonfirst' => 'Ipahilíng an patànaw sa enot na paghirá',
'tog-nocache' => 'Pundoha an pagsaray nin mga pahina sa kilyaw (browser)',
'tog-enotifwatchlistpages' => 'E-suratan mo ako kunsoarin an sarong pahina o sagunson na yaon sa sakong bantay-listahan pinagliwat',
'tog-enotifusertalkpages' => 'E-koreohan ako pag pigribáyan an pahina kan sakóng olay',
'tog-enotifminoredits' => 'E-suratan man ako para sa saraditon na mga pagliwat kan mga pahina asin mga sagunson',
'tog-enotifrevealaddr' => 'Ibuyágyag an sakong e-koreong address sa pan-abisong mga e-koreo',
'tog-shownumberswatching' => 'Ihayag an numero kan nagbabantay na mga parágamit',
'tog-oldsig' => 'Tugmadong pirma',
'tog-fancysig' => 'Trataron an pirma na wiki-teksto (mayo nin awtomatikong kilyaw)',
'tog-externaleditor' => 'Gamiton an pirmihan na panluwas na editor (para sa mga eksperto sana, minakaipo nin espesyal na mga panuytoy sa saimong kompyuter.
[//www.mediawiki.org/wiki/Manual:External_editors Mga dagdag na impormasyon.])',
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
'tog-ccmeonemails' => 'Ipadara sako an mga kopya kan e-koreo na pinadara ko sa ibang mga paragamit',
'tog-diffonly' => 'Dai tabi ihayag an laog kan pahina sa ibaba nin mga diffs',
'tog-showhiddencats' => 'Ihayag an nakatagong mga kategorya',
'tog-norollbackdiff' => 'Omidohon an diff matapos himoon an pagbalikot',

'underline-always' => 'Pirmi',
'underline-never' => 'Nungka',
'underline-default' => 'Kublit o kilyaw na panugmad',

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
'mypage' => 'An Pahina',
'mytalk' => 'Orolayan',
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
'vector-simplesearch-preference' => 'Paganahon an pinagyanong panukod sa paghahanap (Pansolong kublit sana)',
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
'youhavenewmessagesfromusers' => 'Ika igwa nin $1 gikan sa {{PLURAL:$3|ibang paragamit|$3 mga paragamit}} ($2).',
'youhavenewmessagesmanyusers' => 'Ika igwa nin $1 gikan sa kadakol na mga paragamit ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|sarong bagong mensahe|bagong mga mensahe}}',
'newmessagesdifflinkplural' => 'huring {{PLURAL:$1|kaliwatan|mga kaliwatan}}',
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
'dberrortext' => 'May kahaputan sa datos-sarayan sa napasalang sintaks an nangyari.
Ini puwedeng minapanungod nin sarong kubol na yaon sa kasungatan .
An pinakahuring pagprubar sa datos-sarayan naghahapot nin:
<blockquote><tt>$1</tt></blockquote>
gikan sa laog kan punksyon na "<tt>$2</tt>".
An pinagbalik na kasalaan sa datos-sarayan "<tt>$3: $4</tt>".',
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
'missing-article' => 'An datos-sarayan dae nakanagbo nin teksto kan sarong pahina na dapat kuta nang managboan, pinagngaran na "$1" $2.

Ini pirmeng pinagkakausa sa paagi nin pagsusunod nin sarong lumaon na diff o historiyang kasugpunan na yaon sa sarong pahinang pinagpura na.

Kun bako ini an kaso, ika nakanagbo nin sarong kubol sa kasungatan.
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
'protectedpagetext' => 'Ining pahina protektado tanganing malikayan an pagliliwat o ibang aksyon.',
'viewsourcetext' => 'Pwede mong hilingón asin arógon an ginikanan kan pahinang ini:',
'viewyourtext' => "Saimong mahihiling asin makokopya an gikanan kan '''saimong mga pinagriliwat''' sa pahinang ini:",
'protectedinterface' => 'An pahinang ini nagtatao nin panlaog-olay para sa software, asin protektado tangaring malikayan an abuso.
Sa pagdagdag or pagliwat nin mga dakit-taramon para sa bilog na wiki, gamita tabi an [//translatewiki.net/translatewiki.net], an MediaWiki sa proyektong lokalisasyon.',
'editinginterface' => "'''Patanid:''' Ika nagliliwat kan pahina na ginagamit sa pagtao nin pantahaw-olay na teksto para sa software.
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
'password-change-forbidden' => 'Ika dae makapagliwat nin sekretong panlaog sa wiking ini.',
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
An rason na ipinagtao iyo na:

:\'\'$2\'\'

* Pagpoon kan pagkubkob: $8
* Pagpasó kan pagkubkob: $6
* Katuyuhan kan parakubkob: $7

Puwede mong kontakon si $1 o saro sa [[{{MediaWiki:Grouppage-sysop}}|mga administrador]] tanganing pag-orolayan an kubkob.

Patanid tabi dae mo puwedeng gamiton an "e-koreo kaining paragamit" estima laen lang kun ika igwa nin sarong balidong e-koreo address na rehistrado sa saimong [[Special:Preferences|paragamit na mga kabotan]] asin ika dae pinagkubkob para sa paggamit kaini.

An saimong presenteng IP address iyo an $3, asin and Kubkob ID iyo an #$5.
Pakibale tabi an gabos na mga detalye sa itaas sa arinman na mga kahaputan na saimong himoon.',
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
'noarticletext-nopermission' => 'Mayong sa presente nin teksto an pahinang ini.
Ika mapuwedeng [[Special:Search/{{PAGENAME}}|hanapa para kaining titulo kan pahina]] sa iba pang mga pahina,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} maghanap sa magkasurundong mga talaan]</span>.',
'missing-revision' => 'An rebisyon #$1 kan pahina pinagngaranan na "{{PAGENAME}}" bakong eksistido.

Ini pirmihan na pinagkakausa sa paagi nin pagsusunod nin luwas na petsang historiya nin kasugpunan pasiring sa sarong pahinang pinagpura na.
An mga detalye matatagboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} pinagpura na talaan].',
'userpage-userdoesnotexist' => 'Paragamit na panindog "$1" bako tabing rehistrado.
Paki-tsek kun ika magustong magmukna/magliwat kaining pahina.',
'userpage-userdoesnotexist-view' => 'Paragamit na panindog "$1" bako tabing rehistrado.',
'blocked-notice-logextract' => 'Ining paragamit sa presente nakakubkob.
An pinakahuring entrada kan pagkubkob nakahaya sa ibaba bilang reperensiya:',
'clearyourcache' => "'''Antabay:''' Matapos maitagama, ika mapuwedeng magsalimbaw sa sarayan kan saimong kilyaw tanganing hilingon an mga naribayan.
* '''Firefox / Safari:''' Pauntok na duon ''Shift'' habang pig-klik an ''Ikarga otro'', o pinduton an maski arin ''Ctrl-F5'' o ''Ctrl-R'' (''⌘-R'' para sa Mac)
* '''Google Chrome:''' Pinduton ''Ctrl-Shift-R'' (''⌘-Shift-R'' para sa Mac)
* '''Internet Explorer:''' Pauntok na duon ''Ctrl'' habang pig-klik an ''Ipresko otro'', o pinduton ''Ctrl-F5''
* '''Opera:''' Linigan an sarayan sa ''Mga Kagamitan → Mga Kabotan''",
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
'continue-editing' => 'Magduman sa lugar nin pagliliwat',
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
'converter-manual-rule-error' => 'Kasalaan detektado sa manwal na konbersyon kan pinapasunod sa lengguwahe',

# "Undo" feature
'undo-success' => 'Pwedeng bawion an paghirá. Sosogon tabì an pagkakaiba sa babâ tangarig maberipikár kun ini an boot mong gibohon, dangan itagama an mga pagbabàgo sa babâ tangarig tapuson an pagbawì sa paghirá.',
'undo-failure' => 'Dai napogol an paghirá ta igwa pang ibang paghirá sa tahaw na nagkokomplikto.',
'undo-norev' => 'An pagliwat dae tabi magigibo nin huli ta ini bakong eksistido o pinagpura na.',
'undo-summary' => 'Dae idagos an rebisyon $1 sa [[Special:Contributions/$2|$2]] ([[User talk:$2|olay]])',

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
'rev-deleted-user-contribs' => '[Ngaran nin paragamit o IP address pinaghale - an pigliwat pinagtago gikan sa mga kontribusyon]',
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
'rev-suppressed-unhide-diff' => "Saro sa mga rebisyon kaining diff '''pinaglubog'''.
Mga detalye mapuwedeng managboan sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} pinaglubog na talaan].
Ika mapuwede man na [$1 tanawon ining diff] kun ika mawot na magpadagos.",
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
'revdelete-reason-dropdown' => '*Komon na mga rason sa pagpura
**Paglapas kan Copyright
**Bakong angay na personal na impormasyon
**Bakong angay na pangaran nin paragamit
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
'mergehistory-merge' => 'An mga minasunod na mga rebisyon kan [[:$1]] mapuwedeng pagkasararoon na magin [[:$2]].
Gamita an radyong pindutan sa kolum tanganing sararoon sana an mga rebisyon na pinagmukna sa asin bago pa man an pinagsambit na oras.
Tandaan na an paggagamit kan nabigasyong nin mga kasurugponan makakapagliwat kaining kolum.',
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
'difference-missing-revision' => '{{PLURAL:$2|sarong rebisyon|$2 mga rebisyon}} kaining diperensiya ($1) {{PLURAL:$2|na iyo an|kaidto na iyo an}} dae nanagboan.

Ini pirmihan na pinagkakausa sa paagi nin pagsusunod nin luwas sa petsang diff na kasugponan pasiring sa sarong pahina na pinagpura na.
An mga detalye mapuwedeng matatagboan sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan kan pinagpuraan].',

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
'prevn-title' => 'Dati $1 {{PLURAL:$1|resulta|mga resulta}}',
'nextn-title' => 'Sunod $1  {{PLURAL:$1|resulta|mga resulta}}',
'shown-title' => 'Ipahiling $1  {{PLURAL:$1|resulta|mga resulta}} sa kada pahina',
'viewprevnext' => 'Hilingón ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Opsyon sa paghahanap',
'searchmenu-exists' => "'''Igwa nin sarong pahina na pinagngaranan na \"[[:\$1]]\" sa wiking ini.'''",
'searchmenu-new' => "'''Muknaon an pahina \"[[:\$1]]\" sa wiking ini!'''",
'searchhelp-url' => 'Help:Mga laog',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Kilyawon an mga pahina sa paagi kainin enotang panigmit]]',
'searchprofile-articles' => 'Mga pahina nin laog',
'searchprofile-project' => 'Mga pahina nin Tabang asin Proyekto',
'searchprofile-images' => 'Multimidya',
'searchprofile-everything' => 'Gabós',
'searchprofile-advanced' => 'Adbansiyado',
'searchprofile-articles-tooltip' => 'Hanapon sa $1',
'searchprofile-project-tooltip' => 'Maghanap sa $1',
'searchprofile-images-tooltip' => 'Maghanap nin mga sagunson',
'searchprofile-everything-tooltip' => 'Maghanap nin gabos na laog (kabali an mga pahina nin olay)',
'searchprofile-advanced-tooltip' => 'Maghanap nin pankustombreng espasyong-ngaran',
'search-result-size' => '$1 ({{PLURAL:$2|1 tatarámon|$2 mga tatarámon}})',
'search-result-category-size' => '{{PLURAL:$1|1 miyembro|$1 mga miyembro}} ({{PLURAL:$2|1 subkategorya|$2 mga subkategorya}}, {{PLURAL:$3|1 sagunson|$3 mga sagunson}})',
'search-result-score' => 'Relebansiya: $1%',
'search-redirect' => '(Panukdong otro $1)',
'search-section' => '(Seksyon $1)',
'search-suggest' => 'Boót mo iyó: $1',
'search-interwiki-caption' => 'Tugang na mga proyekto',
'search-interwiki-default' => '$1 na mga resulta:',
'search-interwiki-more' => '(dakol pa)',
'search-relatedarticle' => 'Kauyon',
'mwsuggest-disable' => 'Pundohon an AJAX na mga suhestiyon',
'searcheverything-enable' => 'Maghanap sa gabos na mga espasyong-ngaran',
'searchrelated' => 'kauyon',
'searchall' => 'gabós',
'showingresults' => "Pigpapahiling sa babâ sagkod sa {{PLURAL:$1|'''1''' resulta|'''$1''' mga resulta}} poon sa #'''$2'''.",
'showingresultsnum' => "Pigpapahiling sa babâ {{PLURAL:$3|'''1''' resulta|'''$3''' mga resulta}} poon sa #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Resulta '''$1''' kan '''$3'''|Mga Resulta '''$1 - $2''' kan '''$3'''}} para sa '''$4'''",
'nonefound' => "'''Notang Antabay''': An ibang espasyong-ngaran sana an pirmihang pinaghahanap.
Prubaran na panigmitan an saimong kahaputan nin ''all:'' sa paghanap kan gabos na laog (kabali an mga pahina nin olay, mga templato, etc), o gamiton an pinagmawot na espasyong ngaran bilang enotang panigmit.",
'search-nonefound' => 'Mayo nin mga resulta na panampok sa kahaputan.',
'powersearch' => 'Adbansiyadong paghahanap',
'powersearch-legend' => 'Adbansiyadong paghahanap',
'powersearch-ns' => 'Maghanap sa mga espasyong-ngaran:',
'powersearch-redir' => 'Listahan kan mga panukdong otro',
'powersearch-field' => 'Hanápon an',
'powersearch-togglelabel' => 'Pamili:',
'powersearch-toggleall' => 'Gabos',
'powersearch-togglenone' => 'Wara',
'search-external' => 'Panluwas na paghahanap',
'searchdisabled' => 'Pigpopogolan mûna an paghanap sa {{SITENAME}}. Mientras tanto, pwede ka man maghanap sa Google. Giromdomon tabî na an mga indise kan laog ninda sa {{SITENAME}} pwede ser na lumâ na.',

# Quickbar
'qbsettings' => 'Quickbar',
'qbsettings-none' => 'Mayô',
'qbsettings-fixedleft' => 'Nakatakód sa walá',
'qbsettings-fixedright' => 'Nakatakód sa tûo',
'qbsettings-floatingleft' => 'Naglálatáw sa walá',
'qbsettings-floatingright' => 'Naglálatáw sa tûo',
'qbsettings-directionality' => 'Nakadukot, minadepende sa skrip panukdoan kan saimong lengguwahe',

# Preferences page
'preferences' => 'Mga kabòtan',
'mypreferences' => 'Mga Kamuyahan ko',
'prefs-edits' => 'Bilang kan mga hirá:',
'prefsnologin' => 'Dai nakalaog',
'prefsnologintext' => 'Ika dapat na magin <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} nakalaog na]</span> tanganing tuytuyon an mga kabotan nin paragamit.',
'changepassword' => 'Ribayan an sekretong panlaog',
'prefs-skin' => "''Skin''",
'skin-preview' => 'Tânawon',
'datedefault' => 'Mayong kabôtan',
'prefs-beta' => 'Mga posturang yaon sa beta',
'prefs-datetime' => 'Petsa asin oras',
'prefs-labs' => 'Mga posturang yaon sa Labs',
'prefs-user-pages' => 'Paragamit na mga pahina',
'prefs-personal' => 'Pambisto nin parágamit',
'prefs-rc' => 'Mga kaaagi pa sanang pagribay',
'prefs-watchlist' => 'Pigbabantayan',
'prefs-watchlist-days' => 'Mga aldaw na ipahiling sa batay-listahan:',
'prefs-watchlist-days-max' => 'Maksimum $1 {{PLURAL:$1|aldaw|mga aldaw}}',
'prefs-watchlist-edits' => 'Máximong número nin pagbabâgo na ipapahiling sa pinadakulang lista nin pigbabantayan:',
'prefs-watchlist-edits-max' => 'Maksimum na numero: 1000',
'prefs-watchlist-token' => 'Token sa Bantay-listahan:',
'prefs-misc' => 'Lain',
'prefs-resetpass' => 'Liwaton an sekretong panlaog',
'prefs-changeemail' => 'Liwaton an e-surat na adres',
'prefs-setemail' => 'Tuytuyon an e-surat na adres',
'prefs-email' => 'E-surat na mga pagpipilian',
'prefs-rendering' => 'Hitsurahon',
'saveprefs' => 'Itagama',
'resetprefs' => 'Linigan an dae naitagamang mga kaliwatan',
'restoreprefs' => 'Ibalik an gabos na pirmihang mga panuytoy',
'prefs-editing' => 'Pighihira',
'prefs-edit-boxsize' => 'Sukol kan bintana sa pagliwat.',
'rows' => 'Mga hilera:',
'columns' => 'Mga taytay:',
'searchresultshead' => 'Hanápon',
'resultsperpage' => 'Mga tamà kada pahina:',
'stub-threshold' => 'Kasagkoran kan <a href="#" class="stub">takod kan tambô</a> pigpopormato:',
'stub-threshold-disabled' => 'Pinagpundo',
'recentchangesdays' => 'Mga aldáw na ipapahilíng sa mga nakakaági pa sanáng pagbabàgó:',
'recentchangesdays-max' => 'Maksimum $1 {{PLURAL:$1|aldaw|mga aldaw}}',
'recentchangescount' => 'Numero kan mga pagliliwat na ipapahiling na pirmihan:',
'prefs-help-recentchangescount' => 'Kabali kaini an dae pa nahaloy na mga kaliwatan, mga historiyang pahina, asin mga talaan.',
'prefs-help-watchlist-token' => 'An pagpapano sa parteng ini nin sarong sekretong susi magbubuswang nin RSS feed para sa saimong bantay-listahan.
Siisay man na nakakaaram kan suri sa parteng ini makakabasa kan saimong bantay-listahan, kaya magpili nin sarong seguradong halaga.
Uya an halaga sa random na pagbuswang na puwede mong magamit: $1',
'savedprefs' => 'Itinagama na an mga kabôtan mo.',
'timezonelegend' => 'Pan-oras na sona:',
'localtime' => 'Panlokal na oras:',
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
'prefs-searchoptions' => 'Hanapa',
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
'prefs-textboxsize' => 'Sukol kan bintana sa pagliliwat',
'youremail' => 'E-koreo:',
'username' => 'Pangaran kan parágamit:',
'uid' => 'ID kan parágamit:',
'prefs-memberingroups' => 'Miembro kan {{PLURAL:$1|grupo|grupos}}:',
'prefs-registration' => 'Rehistrasyong oras:',
'yourrealname' => 'Totoong pangaran:',
'yourlanguage' => 'Tataramon:',
'yourvariant' => 'Panlaog na lengguwaheng kairibanhan:',
'prefs-help-variant' => 'Saimong pinagpiling kairibanhan o ortograpiya tanganing ipahiling an laog kaining mga pahina sa wiking ini.',
'yournick' => 'Panibagong pirma:',
'prefs-help-signature' => 'Mga komentaryo sa mga pahina nin olay dapat pirmado nin "<nowiki>~~~~</nowiki>" na pagriribayon na magin saimong pirma asin sarong panimbreng oras.',
'badsig' => 'Dai pwede an bâgong pirmang ini; isúsog an mga HTML na takód.',
'badsiglength' => 'An saimong pirma grabe kahalabaon.
Ini dapat dae magsobra sa $1 {{PLURAL:$1|karakter|mga karakter}} an laba.',
'yourgender' => 'Pagkatawo:',
'gender-unknown' => 'Dae nakasambit',
'gender-male' => 'Lalaki',
'gender-female' => 'Babai',
'prefs-help-gender' => 'Opsyonal: Ginagamit para sa pagkatawong pag-apod sa paagi nin kasungatan.
Ining impormasyon magigin pampubliko.',
'email' => 'E-koreo',
'prefs-help-realname' => 'Opsyonal an totoong pangaran asin kun itatao mo ini, gagamiton ini yangarig an mga sinurat mo maatribuir saimo.',
'prefs-help-email' => 'An e-surat na adres sarong opsyonal, alagad ini kinakaipohan para sa pagtuytoy otro kan sekretong panlaog, kun ika malingaw kan saimong sekretong panlaog.',
'prefs-help-email-others' => 'Ika kan man pumili na magtugot sa iba na makontak ka sa e-surat sa paagi nin sarong kasugponan na yaon sa saimong pahina nin paragamit o olay.
An saimong e-surat na adres dae ipagbuyagyag kunsoarin na an ibang paragamit makontak saimo.',
'prefs-help-email-required' => 'Kaipuhan an e-koreo.',
'prefs-info' => 'Panuntong na impormasyon',
'prefs-i18n' => 'Internasyonalisasyon',
'prefs-signature' => 'Pirma',
'prefs-dateformat' => 'Pampetsang pormat',
'prefs-timeoffset' => 'Pan-oras na tapal',
'prefs-advancedediting' => 'Pinag-abanteng mga Pagpipilian',
'prefs-advancedrc' => 'Pangenot na mga pagpipilian',
'prefs-advancedrendering' => 'Abantidong mga pagpipilian',
'prefs-advancedsearchoptions' => 'Abantidong mga pagpipilian',
'prefs-advancedwatchlist' => 'Abantidong mga pagpipilian',
'prefs-displayrc' => 'Ihayag an mga pagpipilian',
'prefs-displaysearchoptions' => 'Ipahiling ang mga pagpipilian',
'prefs-displaywatchlist' => 'Ipahiling ang mga pagpipilian',
'prefs-diffs' => 'Diffs',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'An e-koreo nagpapahiling na balido',
'email-address-validity-invalid' => 'Magkaag nin sarong balidong e-koreong address',

# User rights
'userrights' => 'Pagmaneho kan mga derecho nin paragamit',
'userrights-lookup-user' => 'Magmaného kan mga grupo nin parágamit',
'userrights-user-editname' => 'Ilaog an pangaran kan parágamit:',
'editusergroup' => 'Hirahón an mga Grupo kan Parágamit',
'editinguser' => "Sinasanglian an paragamit na karapatan kan paragamit '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Hirahón an mga grupo kan parágamit',
'saveusergroups' => 'Itagama an mga Grupo nin Páragamit',
'userrights-groupsmember' => 'Myembro kan:',
'userrights-groupsmember-auto' => 'Implisitong miyembro kan:',
'userrights-groups-help' => 'Ika puwedeng magbago kan mga grupo na kinabalihan kaining paragamit:
*An natsekan na kahon minapasabot na an paragamit kabali sa grupong yan.
*An mayong tsek na kahon minapasabot na an paragamit bakong kabali sa grupong yan.
* A * minapahiwatig na ika dae puwedeng makapaghale kan grupo kun naidagdag mo na ini, or vice versa.',
'userrights-reason' => 'Rason:',
'userrights-no-interwiki' => 'Ika mayo tabing permkso na magliwat sa paragamit na karapatan sa ibang wikis.',
'userrights-nodatabase' => 'An datos-sarayan $1 bakong eksistido o bakong lokal.',
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
'right-deletelogentry' => 'Puraon asin dae pagpuran an espesipikong mga entrada sa log',
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
'rightslogentry-autopromote' => 'dati na awtomatikong pinagpalangkaw gikan sa $2 sagkod $3',
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
'rcnote' => "Yaon sa ibaba iyo {{PLURAL:$1|an '''1''' pagbabago|an mga huring '''$1''' mga pagbabago}} kan nakaaging huring {{PLURAL:$2|aldaw|'''$2''' mga aldaw}}, poon pa kan $5, $4.",
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
'number_of_watching_users_pageview' => '[$1 naka-antabay sa {{PLURAL:$1|paragamit|mga paragamit}}]',
'rc_categories' => 'Limitado sa mga kategorya (suhayon nin "|")',
'rc_categories_any' => 'Dawà arín',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|bytes}} pagtatapos kan pagbabago',
'newsectionsummary' => '/* $1 */ bàgong seksyon',
'rc-enhanced-expand' => 'Magpahiling kan mga detalye (minakaipo nin JavaScript)',
'rc-enhanced-hide' => 'Itago an mga detalye',
'rc-old-title' => 'orihinal na pinagmukna bilang "$1"',

# Recent changes linked
'recentchangeslinked' => 'Mga angay na pagbabàgo',
'recentchangeslinked-feed' => 'Mga angay na pagbabàgo',
'recentchangeslinked-toolbox' => 'Mga angay na pagbabàgo',
'recentchangeslinked-title' => 'Mga pagbabàgong angay sa "$1"',
'recentchangeslinked-noresult' => 'Warang mga pagbabago sa mga pahinang nakatakod sa itinaong pagkalawig.',
'recentchangeslinked-summary' => "Ini an listahan kan mga pagbabagong ginibo kan dae pa sana nahaloy sa mga pahina na nakatakod gikan sa sarong pinagsambit na pahina (o sa mga miyembro kan sarong pinagsambit na kategorya).
An mga pahina na yaon sa [[Special:Watchlist|saimong Bantay-listahan]] na '''tekstong mahibog'''.",
'recentchangeslinked-page' => 'Pahinang ngaran:',
'recentchangeslinked-to' => 'Ipahiling an mga pagbabago sa mga pahina na nakatakod sa pinagtaong pahina lugod',

# Upload
'upload' => 'Isàngat an file',
'uploadbtn' => 'Ikargá an file',
'reuploaddesc' => 'Ikansela an pagkarga asin magbalik sa porma kan pagkakarga',
'upload-tryagain' => 'Isumite an modipikadong deskripsyon kan sagunson',
'uploadnologin' => 'Dai nakalaog',
'uploadnologintext' => "Kaipuhan ika si [[Special:UserLogin|nakadagos]]
para makakarga nin mga ''file''.",
'upload_directory_missing' => 'An direktoriyo nin pagkarga ($1) nawawara tabi asin dae maikapagmukna sa paagi kan webserver.',
'upload_directory_read_only' => 'An directoriong pagkarga na ($1) dai puedeng suratan kan serbidor nin web.',
'uploaderror' => 'Salâ an pagkarga',
'upload-recreate-warning' => "'''Patanid tabi: An sagunson sa pangaran kaini pinagpura o pinagbalyo na tabi.'''

An talaan kan pagkapura asin pagkabalyo para sa pahinang ini yaon digde para sa saimong konbenyensiya:",
'uploadtext' => "Gamita an porma sa ibaba tanganing makapagkarga nin mga sagunson.
Para hilngon o hanapon an dati nang pinagkargang mga sagunson, magduman tabi sa [[Special:FileList|listahan kan pinagkargang mga sagunson]], mga pagkarga asin pagkarga otro pinagtala man sa [[Special:Log/upload|talaan nin pagkakarga]], mga pinagpura na yaon sa [[Special:Log/delete|talaan nin pagkapura]].

Sa pagbali nin sarong sagunson sa sarong pahina, gamita tabi an takod kan saro sa mga minasunod na mga porma:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' sa paggamit kan bilog na bersyon kan sagunson
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' sa paggamit kan 200 pixel na lawig kan pagkakua sa sarong kahon na yaon sa parteng wala nin gaygayan na yaon an 'alt text' bilang deskripsyon
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' para sa direktang nakakatakod sa sagunson na dae pinagpapahiling na sarong sagunson",
'upload-permitted' => 'Pinagtutugutang mga tipo nin sagunson: $1',
'upload-preferred' => 'Pinagpapaurog na mga tipo nin sagunson: $1',
'upload-prohibited' => 'Pinagbabawal na mga tipo nin sagunson: $1.',
'uploadlog' => 'historial nin pagkarga',
'uploadlogpage' => 'Ikarga an usip',
'uploadlogpagetext' => 'Yaon sa ibaba an sarong listahan kan dae pa sanang nahahaloy na pinagkargang mga sagunson.
Hilngon tabi an [[Special:NewFiles|galleriya kan mga bagong sagunson]] para sa mas biswal na lantawon.',
'filename' => 'Pangaran kan dokumento',
'filedesc' => 'Kagabsan',
'fileuploadsummary' => 'Kagabsan:',
'filereuploadsummary' => 'Mga pagbabago kan sagunson:',
'filestatus' => 'Kamugtakan sa karapatan nin panurat:',
'filesource' => 'Gikanan:',
'uploadedfiles' => "Mga ''file'' na ikinargá",
'ignorewarning' => 'Dai pagintiendehon an mga patanid asin itagama pa man an file',
'ignorewarnings' => 'Paliman-limanon an mga tanid',
'minlength1' => "An pangaran kan mga ''file'' dapat na dai mababâ sa sarong letra.",
'illegalfilename' => "An ''filename'' na \"\$1\" igwang mga ''character'' na dai pwede sa mga titulo nin páhina. Tâwan tabî nin bâgong pangaran an ''file'' asin probaran na ikarga giraray.",
'filename-toolong' => 'Mga pangaran nin sagunson dae dapat maglawig na sobra sa 240 bytes.',
'badfilename' => "Rinibayan an ''filename'' nin \"\$1\".",
'filetype-mime-mismatch' => 'An ekstensyon kan sagunson na ".$1" bakong langkap sa detektadong tipo kan MIME nin sagunson ($2).',
'filetype-badmime' => "Dai pigtotogotan na ikarga an mga ''file'' na MIME na \"\$1\" tipo.",
'filetype-bad-ie-mime' => 'Dae makakapagkarga kaining sagunson nin huli ta an Internet Explorer minamansay kaini bilang "$1", na bakong pinagtutugutan asin potensyal na delikadong tipo nin sagunson.',
'filetype-unwanted-type' => "'''\".\$1\"''' bakong aprubadong tipo nin sagunson.
Pinapaurog an {{PLURAL:\$3|tipo nin sagunson na|tipo nin sagunson an}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|bakong tinutugutan na tipo nin sagunson|bakong tinutugutan na mga tipo nin mga sagunson}}.
An pinagtutugutan na {{PLURAL:$3|tipo nin sagunson|mga tipo nin mga sagunson}} $2.',
'filetype-missing' => "Mayong ekstensyón an ''file'' (arog kan \".jpg\").",
'empty-file' => 'An sagunson na saimong pinagsumite blangko.',
'file-too-large' => 'An sagunson na saimong pinagsumite grabe kadakula.',
'filename-tooshort' => 'An ngaran kan sagunson grabe kahalipot.',
'filetype-banned' => 'Ining tipo nin sagunson pinagbabawal.',
'verification-error' => 'Ining sagunson dae nag-agi sa beripikasyon.',
'hookaborted' => 'An modipikasyon na saimong pinagprubaran na gibohon pinag-untok bilang sarong ekstensyon.',
'illegal-filename' => 'An ngaran kan sagunson dae pinagtutugot.',
'overwrite' => 'An pagpatungan an sarong eksistidong sagunson dae pinagtutugot.',
'unknown-error' => 'May dae aram na kasalaan an nangyari.',
'tmp-create-error' => 'Dae makapagmukna nin temporaryong sagunson.',
'tmp-write-error' => 'An kasalaan nagsusurat nin temporaryong sagunson.',
'large-file' => "Pigrerekomendár na dapat an mga ''file'' bakong mas dakula sa $1; $2 an sokol kaining ''file''.",
'largefileserver' => "Mas dakula an ''file'' sa pigtotogotan na sokol kan ''server''.",
'emptyfile' => "Garo mayong laog an ''file'' na kinarga mo. Pwede ser na salâ ining tipo nin ''filename''. Isegurado tabî kun talagang boot mong ikarga ining ''file''.",
'windows-nonascii-filename' => 'Ining wiki dae tabi nagsusuporta kan mga pangaran kan sagunson na igwang espesyal na mga karakter.',
'fileexists' => "Igwa nang ''file'' na may parehong pangaran sa ini, sosogon tabî an <strong>[[:$1]]</strong> kun dai ka seguradong ribayan ini.
[[$1|thumb]]",
'filepageexists' => 'An pahinang pandeskripsyon kaining sagunson pinagmukna na tabi sa <strong>[[:$1]]</strong>, alagad mayong sagunson na igwa kaining pangaran sa ngunyan nag-eeksister.
An sumaryong na saimong ipinaglaog dae minaluwas sa pahina kan deskription.
Tanganing gibohon na an saimong sumaryo magluwas duman, kaipohan mong manwal na pagliliwat kaini.
[[$1|thumb]]',
'fileexists-extension' => "May ''file'' na may parehong pangaran: [[$2|thumb]]
* Pangaran kan pigkakargang ''file'': <strong>[[:$1]]</strong>
* Pangaran kan yaon nang ''file'': <strong>[[:$2]]</strong>
Magpili tabî nin ibang pangaran.",
'fileexists-thumbnail-yes' => "An ''file'' garo ladawan kan pinasadit ''(thumbnail)''. [[$1|thumb]]
Sosogon tabî an ''file'' <strong>[[:$1]]</strong>.
Kun an sinosog na ''file'' iyo an parehong ladawan na nasa dating sokol, dai na kaipuhan magkarga nin iba pang retratito.",
'file-thumbnail-no' => "An sagunson minapoon sa <strong>$1</strong>.
Garo baga ini sarong imaheng pinasadit an sukol ''(thumbnail)''.
Kun igwa ka kaining imahe sa kabilogang resolusyon ikarga ini, kun laen pakiribayi an ngaran kan sagunson.",
'fileexists-forbidden' => 'May sagunson na sa arog kaining ngaran, asin dae puwedeng mapapatungan.
Kun gusto mo pang ipagkarga an saimong sagunson, pakibalik lang asin gumamit nin bagong ngaran.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'May sagunson na sa arog kaining ngaran sa repositoryo kan pinagheras na sagunson.
Kun gusto mo pang ipagkarga an saimong sagunson, pakibalik lang asin gumamit nin bagong ngaran.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'An sagunson na ini sarong duplikado kan minasunod na {{PLURAL:$1|file|files}}:',
'file-deleted-duplicate' => 'Sarong sagunson na kapareho kaini ([[:$1]]) na dati nang pinagpura.
Kaipuhan mong aramon an historiya kan pagpura bago ka man magpadagos sa pagkarga kaini giraray.',
'uploadwarning' => 'Patanid sa pagkarga',
'uploadwarning-text' => 'Pakibaguha tabi an deskripsyon kan sagunson sa ibaba asin paki-otroha giraray.',
'savefile' => "Itagama an ''file''",
'uploadedimage' => 'Ikinarga "[[$1]]"',
'overwroteimage' => 'kinarga an bagong bersión kan "[[$1]]"',
'uploaddisabled' => 'Pigpopondó an mga pagkargá',
'copyuploaddisabled' => 'An pagkarga sa paagi kan kilyawan pinagpondo.',
'uploadfromurl-queued' => 'An saimong pagkarga pinagpahalat.',
'uploaddisabledtext' => 'An pagkarga kan mga sagunson pinagpondo tabi.',
'php-uploaddisabledtext' => 'An pagkarga kan mga sagunson pinagpundo nguna sa PHP.
Pakihilnga man tabi an panuytuyan kan pagkarga nin mga sagunson.',
'uploadscripted' => "Ining ''file'' igwang HTML o kodang eskritura na pwede ser na salang mainterpretar kan ''browser''.",
'uploadvirus' => "May virus an ''file''! Mga detalye: $1",
'uploadjava' => 'An sagunson yaon sa ZIP an porma na igwang Java .class na sagunson.
Pagkakarga na mga Java an mga sagunson dae pinagtutugutan, nin huli ta sinda minakausa nin mga restriksyon sa seguridad na lagpason.',
'upload-source' => 'Gikanang sagunson',
'sourcefilename' => 'Ginikanan kan pangaran nin sagunson:',
'sourceurl' => 'Ginikanan kan kilyawan:',
'destfilename' => 'Destinasyon kan pangaran nin sagunson:',
'upload-maxfilesize' => 'Pinakahalangkaw na kadakulaan nin sagunson: $1',
'upload-description' => 'Deskripsyon kan Sagunson',
'upload-options' => 'Pagpipilian kan pagkukupkop',
'watchthisupload' => 'Bantayi ining sagunson',
'filewasdeleted' => "May sarong ''file'' na kapangaran kaini na dating pigkarga tapos pigparâ man sana. Sosogon muna tabî an $1 bago ikarga giraray ini.",
'filename-bad-prefix' => "An pangaran nin ''file'' na pigkakarga mo nagpopoon sa '''\"\$1\"''', sarong pangaran na dai makapaladawan na normalmente enseguidang pigtatao kan mga kamerang digital. Magpili tabî nin pangaran nin ''file'' na mas makapaladawan.",
'upload-success-subj' => 'Nakarga na',
'upload-success-msg' => 'An saimong pagkukupkop na gikan sa [$2] matrayumpo. Ini makukua digde: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problema sa pangupkop',
'upload-failure-msg' => 'Nagkaigwa nin problema sa saimong pagkukupkop gikan sa [$2]:

$1',
'upload-warning-subj' => 'Patanid tabi sa pagkukupkop',
'upload-warning-msg' => 'Nagkaigwa nin problema sa saimong pagkukupkop gikan sa [$2]. Ika puwedeng magbalik sa [[Special:Upload/stash/$1|upload form]] tanganing korehiran ining problema.',

'upload-proto-error' => 'Salang protocolo',
'upload-proto-error-text' => 'An pagkargang panharayo kaipuhan nin mga URLs na nagpopoon sa  <code>http://</code> o <code>ftp://</code>.',
'upload-file-error' => 'Panlaog na salâ',
'upload-file-error-text' => 'Sarong internal na kasalaan an nangyari kunsoarin na pinagprubaran na magmukna in sarong temporaryong sagunson sa tagapagsirbe. Pakikontak lang tabi nin sarong [[Special:ListUsers/sysop|administrador]].',
'upload-misc-error' => 'Dai naaaram na error sa pagkarga',
'upload-misc-error-text' => 'Sarong dae maiwasan na kasalaan an nangyari kan ika nagkukupkop.
Paki arama tabi na an kilyawan balido asin nagagamit asin pakiotro giraray.
Kun an problema yaon pa, pakikontak tabi nin sarong [[Special:ListUsers/sysop|administrador]].',
'upload-too-many-redirects' => 'An kilyawan nagkaigwa nin kadakol na mga kaliwatan',
'upload-unknown-size' => 'Dae aram an kadakulaan',
'upload-http-error' => 'Sarong HTTP na kasalaan an nangyari: $1',
'upload-copy-upload-invalid-domain' => 'Pangungupkop nin kopya bakong puwede gikan sa kinasakupan kaini.',

# File backend
'backend-fail-stream' => 'Dae maipakupsit an sagunson $1.',
'backend-fail-backup' => 'Dae makapagtago nin saro pang kopya an sagunson $1.',
'backend-fail-notexists' => 'An sagunson na $1 bakong eksistido.',
'backend-fail-hashes' => 'Dae nakakakua nin kaputol kan sagunson para ipagkumpara.',
'backend-fail-notsame' => 'Bakong magkakaparehong sagunson yaon na po sa $1.',
'backend-fail-invalidpath' => '$1 bakong balidong agihan sa pagsasaray.',
'backend-fail-delete' => 'Dae makakapura kan sagunson $1.',
'backend-fail-alreadyexists' => 'An sagunson $1 eksistido na po.',
'backend-fail-store' => 'Dae makakapagsaray nin sagunson an $1 sa $2.',
'backend-fail-copy' => 'Dae makakakopya nin sagunson $1 pasiring sa $2.',
'backend-fail-move' => 'Dae makakabalyo nin sagunson $1 pasiring sa $2.',
'backend-fail-opentemp' => 'Dae makakapagbukas nin temporaryong sagunson.',
'backend-fail-writetemp' => 'Dae makakapagsurat sa temporaryong sagunson.',
'backend-fail-closetemp' => 'Dae makakapagsarado nin temporaryong sagunson.',
'backend-fail-read' => 'Dae makakabasa nin sagunson $1.',
'backend-fail-create' => 'Dae makakapagsurat nin sagunson $1.',
'backend-fail-maxsize' => 'Dae makakapagsuat nin sagunson $1 nin huli ta ini grabe kadakula nin {{PLURAL:$2|sarong byte|$2 bytes}}.',
'backend-fail-readonly' => 'An sarayan na panampad "$1" yaon sa estado na basahon-sana. An rason na pinagtao iyo na: "\'\'$2\'\'"',
'backend-fail-synced' => 'An sagunson "$1" yaon sa estado na bakong konsistido sa laog kan mga panampad na sarayan',
'backend-fail-connect' => 'Dae nakakapagsugpon sa panampad na sarayan "$1".',
'backend-fail-internal' => 'Sarong bakong bistadong kasalaan an nangyari sa panampad na sarayan "$1".',
'backend-fail-contenttype' => 'Dae makapagdeterminar sa tipo kan laog kan sagunson na magsaray sa "$1".',
'backend-fail-batchsize' => 'An panampad na sarayan pinagtao nin sarong batch kan sagunson sa $1 {{PLURAL:$1|operasyon|mga operasyon}}; an limit $2 {{PLURAL:$2|operasyon|mga operasyon}}.',
'backend-fail-usable' => 'Dae makakapagbasa o makakapagsurat nin sagunson sa "$1" nin hulita ta kulang an mga permiso o nawawara an mga direktoryo/kaaganan.',

# File journal errors
'filejournal-fail-dbconnect' => 'Dae makakasugpon sa datos-sarayan kan dyornal para sa panampad na sarayan "$1".',
'filejournal-fail-dbquery' => 'Dae makakasumpay sa datos-sarayan kan dyornal para sa panampad na sarayan "$1".',

# Lock manager
'lockmanager-notlocked' => 'Dae makakabukas kan "$1"; dae po ini nakakandado.',
'lockmanager-fail-closelock' => 'Dae makakasara sa nakakandadong sagunson para sa "$1".',
'lockmanager-fail-deletelock' => 'Dae makakapura sa nakakandadong sagunson para sa "$1".',
'lockmanager-fail-acquirelock' => 'Dae makakakua nin kandado para sa "$1".',
'lockmanager-fail-openlock' => 'Dae makakabukas nin nakakandadong sagunson para sa "$1".',
'lockmanager-fail-releaselock' => 'Dae makakabuhi sa kandado para sa "$1".',
'lockmanager-fail-db-bucket' => 'Dae makakakontak awad-awad na kandado kan mga datos-sarayan na yaon sa tipunan na $1.',
'lockmanager-fail-db-release' => 'Dae makakabuhi nin mga kandado sa datos-sarayan na $1.',
'lockmanager-fail-svr-acquire' => 'Dae makakakua nin mga kandado sa serbidor na $1.',
'lockmanager-fail-svr-release' => 'Dae makakabuhi nin mga kandado sa serbidor na $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Sarong kasalaan an nanagboan kunsoarin binubuksan an sagunson para sa ZIP na binansayan.',
'zip-wrong-format' => 'An sinambit na sagunson bakong yaon sa ZIP an porma.',
'zip-bad' => 'An sagunson sarong korapto o baya dae nababasang ZIP na sagunson.
Ini dae nababansayang gayo para sa seguridad.',
'zip-unsupported' => 'An sagunson yaon sa porma nin ZIP na minagamit kan itsura nin ZIP na bakong suportado kan MediaWiki.
Ini dae nababansayang gayo para sa seguridad.',

# Special:UploadStash
'uploadstash' => 'Ikarga an makantidad na tago',
'uploadstash-summary' => 'An pahinang ini minatao nin agihan pasiring sa mga sagunson na ikinarga na (o baya yaon pa sa proseso nin pagkakarga) alagad dae pa naipublisa sa wiki. An mga sagunson na ini bakong hiling sa kiisay man kundi sa paragamit na nagkarga kan mga ini.',
'uploadstash-clear' => 'Pinaglinigan na makantidad na mga sagunson',
'uploadstash-nofiles' => 'Ika mayo nin mahalagang mga sagunson.',
'uploadstash-badtoken' => 'An paggibo kan aksyon na yan bakong matrayumpo, baka nin huli ta an saimong kredensiyal sa pagliliwat nagpaso na.',
'uploadstash-errclear' => 'An paglilinig kan mga sagunson bakong matrayumpo.',
'uploadstash-refresh' => 'Papreskoha otro an listahan kan mga sagunson',
'invalid-chunk-offset' => 'Imbalidong tagpas na pampahale',

# img_auth script messages
'img-auth-accessdenied' => 'Paggamit dae pinagtugot',
'img-auth-nopathinfo' => 'Nawawara an PATH_INFO.
An saimong serbidor dae naipamugtak tanganing makapasa kaining impormasyon.
Ini mapuwedeng yaon nakabase sa CGI asin dae makakasuporta sa img_auth.
Hilnga an https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir' => 'An hinahagad na agihan bakong naka-akma sa pankargang direktoryo.',
'img-auth-badtitle' => 'Dae nakakapaggibo in sarong balidong titulog gikan sa "$1".',
'img-auth-nologinnWL' => 'Dae ka nakalaog asin "$1" mayo sa aprubadong listahan.',
'img-auth-nofile' => 'An sagunson "$1" bakong eksistido.',
'img-auth-isdir' => 'Ika naghingohang makalaog sa direktoryo "$1".
Makakalaog ka sana sa sagunson na tinugot.',
'img-auth-streaming' => 'Nagsusughay na "$1".',
'img-auth-public' => 'An punksyon kan img_autho.php iyo an magpaluwas nin mga sagunson gikan sa pribadong wiki.
Ining wiki pinagbago bilang sarong pampublikong wiki.
Para sa pinakamakusog na seguridad, img_auth.php nganay pinagpundo.',
'img-auth-noread' => 'An paragamit mayo nin kakusgan na magbasa sa "$1".',
'img-auth-bad-query-string' => 'An kilyawan igwa nin bakong imbalidong pasurunod na kahaputan.',

# HTTP errors
'http-invalid-url' => 'Imbalidong kilwayan: $1',
'http-invalid-scheme' => 'Mga kilyawan na igwang "$1" eskima bako tabing suportado.',
'http-request-error' => 'HTTP kahagadan nagpalya nin huli sa dae pa aram na kasalaan.',
'http-read-error' => 'HTTP na pagbabasa nasasala.',
'http-timed-out' => 'HTTP na kahagadan naubos na an oras.',
'http-curl-error' => 'An kasalaan nagsusungko sa kilyawan: $1',
'http-host-unreachable' => 'Dae nakakaabot sa kilyawan.',
'http-bad-status' => 'Igwa nin sarong problema habang yaon sa HTTP na kahagadan: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Dai naabot an URL',
'upload-curl-error6-text' => 'Dai nabukas an URL na tinao.  Susugon tabi giraray na an  URL tama asin an sitio bakong raot.',
'upload-curl-error28' => 'sobra na an pagkalawig kan pagkarga',
'upload-curl-error28-text' => 'Sobrang haloy an pagsimbag kan sitio. Susugon tabi na nagaandar an sitio, maghalat nin muna asin iprobar giraray. Tibaad moot mong magprobar sa panahon na bako masiadong okupado.',

'license' => 'An Paglilisensya:',
'license-header' => 'Paglisensiya',
'nolicense' => 'Mayong pigpilî',
'license-nopreview' => '(Mayong patânaw)',
'upload_source_url' => ' (sarong tama, na bukas sa publikong URL)',
'upload_source_file' => " (sarong ''file'' sa kompyuter mo)",

# Special:ListFiles
'listfiles-summary' => 'Ining espesyal na pahina nagpapahiling kan gabos na pinagkargang mga sagunson.
Kunsoarin na pinagsara nin paragamit, an mga sagunson sana kun saen an pinagkarga nin paragamit na iyo an pinakahuring bersyon an ipagpapahiling.',
'listfiles_search_for' => 'Hanápon an pangaran kan retrato:',
'imgfile' => 'dokumento',
'listfiles' => 'Lista kan dokumento',
'listfiles_thumb' => 'Imaheng sadit',
'listfiles_date' => 'Petsa',
'listfiles_name' => 'Pangaran',
'listfiles_user' => 'Parágamit',
'listfiles_size' => 'Sukol',
'listfiles_description' => 'Deskripsión',
'listfiles_count' => 'Mga Bersyon',

# File description page
'file-anchor-link' => 'File',
'filehist' => 'Uusipón nin file',
'filehist-help' => 'Magpindot kan petsa/oras para mahiling an hitsura kan file sa piniling oras.',
'filehist-deleteall' => 'parâon gabos',
'filehist-deleteone' => 'puraon',
'filehist-revert' => 'ibalik',
'filehist-current' => 'ngonyan',
'filehist-datetime' => 'Petsa/Oras',
'filehist-thumb' => 'Imaheng sadit',
'filehist-thumbtext' => 'Imaheng sadit para sa bersyon kan nakaaging $1',
'filehist-nothumb' => 'Mayo nin imaheng sadit',
'filehist-user' => 'Paragamít',
'filehist-dimensions' => 'Mga dimensyón',
'filehist-filesize' => 'Sokol nin file',
'filehist-comment' => 'Komento',
'filehist-missing' => 'Nawawarang sagunson',
'imagelinks' => 'Sagunsong naggagamit',
'linkstoimage' => 'An minasunod na {{PLURAL:$1|mga takod nin pahina|$1 mga pahinang nakatakod}} kaining sagunson:',
'linkstoimage-more' => 'Sobra sa $1 {{PLURAL:$1|mga takod nin pahina|$1 mga pahinang nakatakod}} kaining sagunson.
An minasunod na lista nagpapahiling kan {{PLURAL:$1|enot na pahinang takod|enot na $1 pahinang nakatakod}} kaining sagunson sana.
Sarong [[Special:WhatLinksHere/$2|bilog na lista]] an maantabayan.',
'nolinkstoimage' => 'Mayong mga pahinang nakatakod sa dokumentong ini.',
'morelinkstoimage' => 'Hilngon an [[Special:WhatLinksHere/$1|kadagdagang mga takod]] kaining sagunson.',
'linkstoimage-redirect' => '$1 (sagunson na panukdong otro) $2',
'duplicatesoffile' => 'An minasunod na {{PLURAL:$1|sagunson sarong duplikado|$1 mga sagunsong duplikado}} kaining sagunson ([[Special:FileDuplicateSearch/$2|kadagdagang mga detalye]]):',
'sharedupload' => 'Ining sagunson naggikan sa $1 asin mapuwedeng gamiton kan ibang mga proyekto.',
'sharedupload-desc-there' => 'Ining sagunson naggikan sa $1 asin mapuwedeng gamiton kan ibang mga proyekto.
Pakihiling tabi sa [$2 sagunsong deskripsyon kan pahina] para sa mga kadagdagang impormasyon.',
'sharedupload-desc-here' => 'Ining sagunson naggikan sa $1 asin mapuwedeng gamiton kan ibang mga proyekto.
An deskripsyon na yaon sa [$2 sagunsong deskripsyon kan pahina] ipinapahiling tabi sa ibaba.',
'sharedupload-desc-edit' => 'Ining sagunson naggikan sa $1 asin mapuwedeng gamiton kan ibang mga proyekto.
Mapuwede gayod na magusto kang liwaton an deskripsyon na yaon sa [$2 sagunsong deskripsyon kan pahina] kaini.',
'sharedupload-desc-create' => 'Ining sagunson naggikan sa $1 asin mapuwedeng gamiton kan ibang mga proyekto.
Mapuwede gayod na ika magustong liwatong an deskripsyon na yaon sa [$2 sagunsong deskripsyon kan pahina] kaini.',
'filepage-nofile' => 'Mayong sagunson sa arog kaining ngaran an yaon.',
'filepage-nofile-link' => 'Mayong sagunson sa arog kaining ngaran an yaon, alagad ika puwedeng [$1 magkarga kaini].',
'uploadnewversion-linktext' => 'Magkarga nin bàgong bersyon kaining file',
'shared-repo-from' => 'gikan sa $1',
'shared-repo' => 'sarong pinagheras na repositoryo',
'upload-disallowed-here' => 'Ika dae makakapagpatong sa pagsurat kaining sagunson.',

# File reversion
'filerevert' => 'Ibalik an $1',
'filerevert-legend' => 'Ibalik an dokumento',
'filerevert-intro' => "Pigbabalik mo an '''[[Media:$1|$1]]''' sa [$4 version as of $3, $2].",
'filerevert-comment' => 'Rason:',
'filerevert-defaultcomment' => 'Pigbalik sa bersyon sa ngonyan $2, $1',
'filerevert-submit' => 'Ibalik',
'filerevert-success' => "'''[[Media:$1|$1]]''' binalik sa [$4 version as of $3, $2].",
'filerevert-badversion' => "Mayong dating bersyón na lokal kaining ''file'' na may taták nin oras na arog sa tinao.",

# File deletion
'filedelete' => 'Parâon an $1',
'filedelete-legend' => 'Parâon an dokumento',
'filedelete-intro' => "Saimong pagpupuraon an sagunson '''[[Media:$1|$1]]''' kaiba an gabos kaining historiya.",
'filedelete-intro-old' => "Pigpaparâ mo an bersyon kan '''[[Media:$1|$1]]''' sa ngonyan [$4 $3, $2].",
'filedelete-comment' => 'Rason:',
'filedelete-submit' => 'Parâon',
'filedelete-success' => "An '''$1''' pinarâ na.",
'filedelete-success-old' => "An bersyon kan '''[[Media:$1|$1]]''' magpoon kan $3, $2 pinagpura na tabi.",
'filedelete-nofile' => "'''$1''' bakong eksistido.",
'filedelete-nofile-old' => "Mayong bersyón na nakaarchibo kan '''$1''' na igwang kan mga piniling ''character''.",
'filedelete-otherreason' => 'An iba pa/kadugangang rason:',
'filedelete-reason-otherlist' => 'Ibang dahilan',
'filedelete-reason-dropdown' => '*Kumon na mga rason nin pagpura
** Copyright na paglapas
** Duplikadong sagunson',
'filedelete-edit-reasonlist' => 'Liwaton an mga rason nin pagpura',
'filedelete-maintenance' => 'Pagpupura asin restorasyon nin mga sagunson temporaryong pinagpupundo sa panahon nin pagpapakarhay.',
'filedelete-maintenance-title' => 'Dae makapagpura nin sagunson',

# MIME search
'mimesearch' => 'Paghanap kan MIME',
'mimesearch-summary' => "An gamit kaining páhina sa pagsasarâ kan mga ''file'' segun sa mga tipo nin MIME. Input: contenttype/subtype, e.g. <code>image/jpeg</code>.",
'mimetype' => 'tipo nin MIME:',
'download' => 'ideskarga',

# Unwatched pages
'unwatchedpages' => 'Dai pigbabantayan na mga pahina',

# List redirects
'listredirects' => 'Lista nin mga paglikay',

# Unused templates
'unusedtemplates' => 'Mga templatong dai ginamit',
'unusedtemplatestext' => 'Ining pahina minalista kan gabos na mga pahina sa {{ns:template}} ngarang-espasyo na bakong kabali sa ibang pahina.
Giromdoma baya na mag-tsek para sa iba pang kasugpon sa mga templato bago mo pagpuraon sinda.',
'unusedtemplateswlh' => 'ibang mga takod',

# Random page
'randompage' => 'Arín man na pahina',
'randompage-nopages' => 'Dae tabi nin mga pahina sa minasunod na {{PLURAL:$2|espasyong-ngaran|mga espasyong-ngaran}}: $1.',

# Random redirect
'randomredirect' => 'Random na pagredirekta',
'randomredirect-nopages' => 'Mayo nin panukdo-liwat sa espasyong-ngaran na "$1".',

# Statistics
'statistics' => 'Mga Estadistiko',
'statistics-header-pages' => 'Estadistikong pahina',
'statistics-header-edits' => 'Estadistiko nin pagliwat',
'statistics-header-views' => 'Estadistiko nin pagmansay',
'statistics-header-users' => 'Mga estadistiko nin parágamit',
'statistics-header-hooks' => 'Iba pang estadistiko',
'statistics-articles' => 'Laman na mga pahina',
'statistics-pages' => 'Mga Pahina',
'statistics-pages-desc' => 'Gabos na mga pahina sa laog kan wiki, kabali an pahina nin orolay, mga panukdo-liwat, ibp.',
'statistics-files' => 'Pinagkargang mga sagunson',
'statistics-edits' => 'Mga pagliwat sa pahina magpoon pa na an {{SITENAME}} pinagmukna.',
'statistics-edits-average' => 'Katahaw kan mga pagliliwat sa kada pahina',
'statistics-views-total' => 'Mga Kamansayan sa kabilogan',
'statistics-views-total-desc' => 'Mga kamansayan sa dae pa eksistidong mga pahina asin espesyal na mga pahina bakong kabali',
'statistics-views-peredit' => 'Mga kamansayan kada pagliwat',
'statistics-users' => 'Rehistrado [[Special:ListUsers|users]]',
'statistics-users-active' => 'Mga Aktibong Paragamit',
'statistics-users-active-desc' => 'Mga paragamit na may ginibong aksyon sa nakaaging {{PLURAL:$1|aldaw|$1 mga aldaw}}',
'statistics-mostpopular' => 'mga pinaka pighiling na pahina',

'disambiguations' => 'Mga pahinang minatulay pasiring sa pampalinaw na mga pahina',
'disambiguationspage' => 'Template:clarip',
'disambiguations-text' => "An mga minasunod na mga pahina igwang laog nin kisera sarong tulay pasiring sa '''pampalinaw na pahina'''.
Sinda mapuwedeng makipagsugpon pasiring sa sarong mas manigong pahina nanggad.<br />
An sarong pahina tratado bilang pampalinaw na pahina kun ini minagamit nin sarong templato na nakasugpon gikan sa [[MediaWiki:Disambiguationspage]].",

'doubleredirects' => 'Dobleng mga redirekta',
'doubleredirectstext' => 'Ining pahina minalista nin mga pahina na minatukdo liwat pasiring sa pinagtukdong-liwat na mga pahina.
Kada palunpon igwang laog na minasugpon pasiring sa enot asin ikaduwang pagtukdo-liwat, siring man sa target kan ikaduwang pagtukdo-liwat, na pirme nanggad an "tunay" na pahinang target, na an enot na pagtukdong-liwat dapat na iyo an pagtutukdoon.
<del>Pinagpura</del> na mga entrada naresolberan na.',
'double-redirect-fixed-move' => '[[$1]] pinagbalyo tabi.
Ini ngunyan minatukdo-liwat pasiring sa [[$2]].',
'double-redirect-fixed-maintenance' => 'Pinapakarhay na dobleng panukdo-liwat magpoon sa [[$1]] pasiring sa [[$2]].',
'double-redirect-fixer' => 'Parapakarhay kan panukdo-liwat',

'brokenredirects' => 'Putol na mga paglikay',
'brokenredirectstext' => 'An mga minasunod na panukdo-liwat nakasugpon pasiring sa busyaw na mga pahina:',
'brokenredirects-edit' => 'hirahón',
'brokenredirects-delete' => 'parâon',

'withoutinterwiki' => 'Mga pahinang dai nin mga takod sa ibang tataramon',
'withoutinterwiki-summary' => 'An mga nagsusunod na páhina dai nakatakód sa mga bersión na ibang tataramón:',
'withoutinterwiki-legend' => 'Enotang panigmit',
'withoutinterwiki-submit' => 'Ipahiling',

'fewestrevisions' => 'Mga artikulong may pinakadikit na pagpakarháy',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|mga byte}}',
'ncategories' => '$1 {{PLURAL:$1|kategorya|mga kategorya}}',
'ninterwikis' => '$1 {{PLURAL:$1|an interwiki|mga interwiki}}',
'nlinks' => '$1 {{PLURAL:$1|takod|mga takod}}',
'nmembers' => '$1 {{PLURAL:$1|myembro|mga myembro}}',
'nrevisions' => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'nviews' => '$1 {{PLURAL:$1|hiling|mga hiling}}',
'nimagelinks' => 'Ginamit sa $1 {{PLURAL:$1|pahina|mga pahina}}',
'ntransclusions' => 'ginamit sa $1 {{PLURAL:$1|pahina|mga pahina}}',
'specialpage-empty' => 'Mayong mga resulta para sa report na ini.',
'lonelypages' => 'Mga solong pahina',
'lonelypagestext' => 'An minasunod na mga pahina dae nakatakod gikan o balyong-kabali sa laog kan ibang mga phina nin {{SITENAME}}.',
'uncategorizedpages' => 'Mga dai nakakategoryang páhina',
'uncategorizedcategories' => 'Mga dai nakakategoryang kategorya',
'uncategorizedimages' => 'Mayong kategoryang mga sagunson',
'uncategorizedtemplates' => 'Mga templatong mayong kategorya',
'unusedcategories' => 'Dai gamit na mga kategorya',
'unusedimages' => 'Mga dokumentong dai nagamit',
'popularpages' => 'Mga popular na páhina',
'wantedcategories' => 'Mga hinahanap na kategorya',
'wantedpages' => 'Mga hinahanap na pahina',
'wantedpages-badtitle' => 'Imbalidong titulo sa resultang kinaag: $1',
'wantedfiles' => 'Kinakaipong mga sagunson',
'wantedfiletext-cat' => 'An minasunod na mga sagunson ginagamit alagad bakong eksistido. Mga sagunson na gikan sa luwas kan mga repositoryo mapuwedeng listahon dawa bakong eksistido. An arinman na mga palsong positibo <del>paghahaleon sa agi nin linya</del>. Bilang kadagdagan, an mga pahina na minapadukot nin mga sagunson na bako man eksistido yaon nakalista sa [[:$1]].',
'wantedfiletext-nocat' => 'An minasunod na mga sagunson ginagamit alagad bakong eksistido. Mga sagunson na gikan sa luwas kan mga repositoryo mapuwedeng listahon dawa eksistido na. An arinman na mga palsong positibo <del>paghahaleon sa agi nin linya</del>.',
'wantedtemplates' => 'Kinakaipong mga templato',
'mostlinked' => 'Pinakapigtatakodan na mga pahina',
'mostlinkedcategories' => 'Pinakapigtatakodan na mga kategorya',
'mostlinkedtemplates' => 'An mga pinakanatakodan na templato',
'mostcategories' => 'Mga artikulong may pinaka dakol na kategorya',
'mostimages' => 'An pinakapakisugpunan na mga sagunson',
'mostinterwikis' => 'Mga pahina na igwang mas kadakol na interwiki',
'mostrevisions' => 'Mga artikulong may pinakadakol na pagpakarháy',
'prefixindex' => 'Gabos na mga pahina na igwa nin enotang panigmit',
'prefixindex-namespace' => 'Gabos na mga pahina na igwa nin enotang panigmit ($1 espasyong ngaran)',
'shortpages' => 'Haralìpot na pahina',
'longpages' => 'Mga halabang pahina',
'deadendpages' => 'Mga pahinang mayong luwasan',
'deadendpagestext' => 'An mga minasunod na mga phina dae nakatakod sa ibang mga pahina sa {{SITENAME}}.',
'protectedpages' => 'Mga protektadong pahina',
'protectedpages-indef' => 'Daeng sagkod na proteksyon sana',
'protectedpages-cascade' => 'Mga pasurunod na proteksyon sana',
'protectedpagestext' => 'An mga minasunod na pahina protektado na ibalyó o hirahón',
'protectedpagesempty' => 'Mayong pang páhina an napoprotehiran kaining mga parametros.',
'protectedtitles' => 'Protektadong mga titulo',
'protectedtitlestext' => 'An minasunod na mga titulo pinagprotektaran magpoon na muknaon',
'protectedtitlesempty' => 'Mayong mga titulo sa presente an protektado kaining mga parametro.',
'listusers' => 'Lista nin paragamit',
'listusers-editsonly' => 'Ipahiling sana an mga paragamit na igwang mga pinagliwat',
'listusers-creationsort' => 'Salansanon sa paagi kan petsa nin pagmukna',
'usereditcount' => '$1 {{PLURAL:$1|pigliwat|mga pigliwat}}',
'usercreated' => '{{GENDER:$3|Minukna}} kan $1 sa $2',
'newpages' => 'Mga bàgong pahina',
'newpages-username' => 'Pangaran kan parágamit:',
'ancientpages' => 'Mga pinakalumang pahina',
'move' => 'Ibalyó',
'movethispage' => 'Ibalyó ining pahina',
'unusedimagestext' => 'An minasunod na mga sagunson eksistido alagad dae nakadukot sa arinman na pahina.
Pakigiromdom tabi na sa ibang websityo mapuwedeng nakatakod sa sarong sagunson na igwang direktang kilyawan, asin kaya mapuwedeng nakalista digde dawa ngani ini aktibong ginagamit.',
'unusedcategoriestext' => 'Igwa ining mga pahinang kategoria maski mayo man na iba pang pahina o kategoria an naggagamit kaiyan.',
'notargettitle' => 'Mayong target',
'notargettext' => 'Dai ka pa nagpili nin pahina o paragamit na muya mong gibohon an accion na ini.',
'nopagetitle' => 'Mayo kaiyang target na pahina',
'nopagetext' => 'An target na pahina na saimong pinagsambit bako tabing eksistido.',
'pager-newer-n' => '{{PLURAL:$1|baguhon 1|baguhon na $1}}',
'pager-older-n' => '{{PLURAL:$1|luma na nin 1|luma na nin $1}}',
'suppress' => 'Tagapagmansay',
'querypage-disabled' => 'Ining espesyal na pahina pinagpundo nin huli sa kaggibohang mga rason.',

# Book sources
'booksources' => 'Ginikanang libro',
'booksources-search-legend' => 'Maghanap nin mga ginikanang libro',
'booksources-go' => 'Dumanán',
'booksources-text' => "Mahihiling sa babâ an lista kan mga takod sa ibang ''site'' na nagbenbenta nin mga bâgo asin nagamit nang libro, asin pwede ser na igwa pang mga ibang impormasyon manonongod sa mga librong pighahanap mo:",
'booksources-invalid-isbn' => 'An pinagtaong ISBN dae minaluwas na balido; paki-tsek tabi nin mga sala sa pagkopya gikan sa orihinal na piggikanan.',

# Special:Log
'specialloguserlabel' => 'Paragibo:',
'speciallogtitlelabel' => 'Target (titulo o paragamit):',
'log' => 'Mga usip',
'all-logs-page' => 'Gabos na pampublikong mga talaan',
'alllogstext' => 'Kumbinadong pagpapahiling kan gabos na yaong mga talaan sa {{SITENAME}}.
Saimong mapasadit an patanaw sa paagi nin pagpipili nin sarong tipo nin talaan, an ngaran nin paragamit (sensitibo sa pindutan), o an apektadong pahina (sensitibo sa pindutan man).',
'logempty' => 'Mayong angay na bagay sa historial.',
'log-title-wildcard' => 'Hanapon an mga titulong napopoon sa tekstong ini',
'showhideselectedlogentries' => 'Ipahiling/itago an pinagpiling mga entrada sa talaan',

# Special:AllPages
'allpages' => 'Gabos na pahina',
'alphaindexline' => '$1 sagkod sa $2',
'nextpage' => 'Sunod na pahina ($1)',
'prevpage' => 'Nakaaging pahina ($1)',
'allpagesfrom' => 'Ipahiling an mga páhina poon sa:',
'allpagesto' => 'Ipahiling an mga pahina na may tapos na:',
'allarticles' => 'Gabos na mga artikulo',
'allinnamespace' => 'Gabos na mga páhina ($1 ngaran-espacio)',
'allnotinnamespace' => 'Gabos na mga páhina (na wara sa $1 ngaran-espacio)',
'allpagesprev' => 'Nakaagi',
'allpagesnext' => 'Sunod',
'allpagessubmit' => 'Dumanán',
'allpagesprefix' => 'Ipahiling an mga pahinang may prefiho:',
'allpagesbadtitle' => "Dai pwede an tinaong titulo kan páhina o may prefihong para sa ibang tataramon o ibang wiki. Pwede ser na igwa ining sarô o iba pang mga ''character'' na dai pwedeng gamiton sa mga titulo.",
'allpages-bad-ns' => 'An {{SITENAME}} mayo man na ngaran-espacio na "$1".',
'allpages-hide-redirects' => 'Itago an mga panukdong otro',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Ika nakahiling sa sarong pinagsaray na bersyon kaining pahina, na puwedeng minaabot na sa $1 nang haloy.',
'cachedspecial-viewing-cached-ts' => 'Ika nakahiling sa sarong pinagsaray na bersyon kaining pahina, na mapuwedeng bakong aktuwal na kumpleto talaga.',
'cachedspecial-refresh-now' => 'Hilngon an pinakahuri.',

# Special:Categories
'categories' => 'Mga Kategorya',
'categoriespagetext' => 'An minasunod {{PLURAL:$1|kategorya na may laog na|mga kategorya na may laog na}} mga pahina o midya.
[[Special:UnusedCategories|Dae ginamit na mga kategorya]] dae ipinapahiling digde.
Asin man hilnga an [[Special:WantedCategories|kinakaipong mga kategorya]].',
'categoriesfrom' => 'Pahilnga an mga kategorya magpoon sa:',
'special-categories-sort-count' => 'salansana sa paagi nin bilang',
'special-categories-sort-abc' => 'salansana sa paagi nin alpabetiko',

# Special:DeletedContributions
'deletedcontributions' => 'Parâon an mga kontribusyon kan parágamit',
'deletedcontributions-title' => 'Parâon an mga kontribusyon kan parágamit',
'sp-deletedcontributions-contribs' => 'mga kontribusyon',

# Special:LinkSearch
'linksearch' => 'Panluwas na mga takod sa paghahanap',
'linksearch-pat' => 'Pangarugan sa paghahanap:',
'linksearch-ns' => 'Espasyong-ngaran:',
'linksearch-ok' => 'Hanápon',
'linksearch-text' => 'Mga pantsambang baraha arog baka kan "*.wikipedia.org" mapuwedeng gamiton.
Minakaipo kisera sarong halangkaw na mugtak nin kinasakupan, halimbawa "*.org".<br />
Suportadong mga panundan: <code>$1</code> (dae magdagdag arinman kaini sa saimong paghahanap).',
'linksearch-line' => '$1 an nakatakod sa $2',
'linksearch-error' => 'Mga pantsambang baraha mapuwedeng magluwas sana sa poon kan hostname.',

# Special:ListUsers
'listusersfrom' => 'Ipahiling an mga paragamit poon sa:',
'listusers-submit' => 'Ipahiling',
'listusers-noresult' => 'Mayong nakuang parágamit.',
'listusers-blocked' => '(pinagbarado)',

# Special:ActiveUsers
'activeusers' => 'Listahan kan aktibong paragamit',
'activeusers-intro' => 'Iyo in an listahan kan mga paragamit na nagkaigwa nin mga ginibo sa laog kan nakaaging $1 {{PLURAL:$1|aldaw|mga aldaw}}.',
'activeusers-count' => '$1 {{PLURAL:$1|pigliwat|mga pigliwat}} sa nakaaging {{PLURAL:$3|aldaw|$3 mga aldaw}}',
'activeusers-from' => 'Ipahiling an mga paragamit magpoon sa:',
'activeusers-hidebots' => 'Itago an mga panalnga',
'activeusers-hidesysops' => 'Itago an mga administrador',
'activeusers-noresult' => 'Mayong mga paragamit na nanagboan.',

# Special:Log/newusers
'newuserlogpage' => 'Paragamit na talaan nin pagmukna',
'newuserlogpagetext' => 'Ini an talaan kan mga pagmukna nin paragamit.',

# Special:ListGroupRights
'listgrouprights' => 'Mga karapatan kan grupo nin paragamit',
'listgrouprights-summary' => 'An minasunod iyo an listahan kan mga grupo nin paragamit na pinaghunsay kaining wiki, kaiba an saindang asosyadong mga karapatan nin paggamit.
Puwedeng magkakaigwa nin [[{{MediaWiki:Listgrouprights-helppage}}|kadagdagang impormasyon]] mapanungod sa indibidwal na mga karapatan.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Pinagbanhag na Karapatan</span>
* <span class="listgrouprights-revoked">Pinagbawi na Karapatan</span>',
'listgrouprights-group' => 'Grupo',
'listgrouprights-rights' => 'Derechos',
'listgrouprights-helppage' => 'Help:Pangrupong mga karapatan',
'listgrouprights-members' => '(lista kan mga kaapíl)',
'listgrouprights-addgroup' => 'Dagdag {{PLURAL:$2|grupo|mga grupo}}: $1',
'listgrouprights-removegroup' => 'Halia an {{PLURAL:$2|grupo|mga grupo}}: $1',
'listgrouprights-addgroup-all' => 'Idagdag an gabos na mga grupo',
'listgrouprights-removegroup-all' => 'Haleon an gabos na mga grupo',
'listgrouprights-addgroup-self' => 'Dagdag {{PLURAL:$2|grupo|mga grupo}} tanganing magkaigwa nin panindog: $1',
'listgrouprights-removegroup-self' => 'Halia {{PLURAL:$2|grupo|mga grupo}} gikan sa sadireng panindog: $1',
'listgrouprights-addgroup-self-all' => 'Idagdag an gabos na mga grupo tanganing magkaigwa nin sadireng panindog',
'listgrouprights-removegroup-self-all' => 'Halion an gabos na mga grupo gikan sa sadireng panindog',

# E-mail user
'mailnologin' => 'Mayong direksyón nin destino',
'mailnologintext' => "Kaipuhan ika si [[Special:UserLogin|nakalaog]]
asin may marhay na ''e''-surat sa saimong [[Special:Preferences|Mga kabôtan]]
para makapadara nin ''e''-surat sa ibang parágamit.",
'emailuser' => 'E-koreohan ining paragamit',
'emailuser-title-target' => 'E-surat kaining {{GENDER:$1|paragamit}}',
'emailuser-title-notarget' => 'E-surat na paragamit',
'emailpage' => 'E-suratan an parágamit',
'emailpagetext' => 'Ika makakagamit kan porma na yaon sa ibaba sa pagpadara nin mensahe na e-surat sa {{GENDER:$1|paragamit}}.
An e-surat na estada sa saimong pinaglaog sa [[Special:Preferences|saimong paragamit na mga kamuyahan]] ipapahiling bilang iyo an "Gikan sa" estada kan e-surat, kaya an resipiyente makakapagsimbag direkta mismo saimo.',
'usermailererror' => 'Error manonongod sa korreong binalik:',
'defemailsubject' => '{{SITENAME}} e-surat gikan sa paragamit "$1"',
'usermaildisabled' => 'Paragamit na e-surat pinagpundo',
'usermaildisabledtext' => 'Ika dae makakapagpadara nin e-surat sa ibang mga paragamit kaining wiki',
'noemailtitle' => "Mayô nin ''e''-surat",
'noemailtext' => 'Ining paragamit dae nagkaag nin sarong balidong e-surat na adres.',
'nowikiemailtitle' => 'Mayong e-surat na pinagtutugutan',
'nowikiemailtext' => 'Ining paragamit nagpili na mayong mareresibeng e-surat gikan sa ibang mga paragamit.',
'emailnotarget' => 'Bakong eksistido o imbalido an ngaran nin paragamit para sa sinuratan.',
'emailtarget' => 'Paki-entra an ngaran kan paragamit na sinuratan',
'emailusername' => 'Ngaran nin Paragamit:',
'emailusernamesubmit' => 'Isumite',
'email-legend' => 'Magpadara nin sarong e-surat sa ibang {{SITENAME}} na paragamit',
'emailfrom' => 'Gikan ki:',
'emailto' => 'Hasta:',
'emailsubject' => 'Subheto:',
'emailmessage' => 'Mensahe:',
'emailsend' => 'Ipadara',
'emailccme' => 'E-suratan ako nin kopya kan mga mensahe ko.',
'emailccsubject' => 'Kopya kan saimong mensahe sa $1: $2',
'emailsent' => 'Naipadará na an e-surat',
'emailsenttext' => 'Naipadará na su e-surat mo.',
'emailuserfooter' => 'Ining e-surat ipinadara sa paagi nin $1 pasiring ki $2 kan "E-surat na paragamit" na punksyon kan {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Magwawalat nin pansistemang mensahe.',
'usermessage-editor' => 'Pansistemang mensahero',

# Watchlist
'watchlist' => 'Pigbabantayan ko',
'mywatchlist' => 'Bantay-listahan',
'watchlistfor2' => 'Para ki $1 $2',
'nowatchlist' => 'Mayo ka man na mga bagay saimong lista nin pigbabantayan.',
'watchlistanontext' => 'Mag $1 tabi para mahiling o maghira nin mga bagay saimong lista nin mga pigbabantayan.',
'watchnologin' => 'Mayô sa laog',
'watchnologintext' => 'Dapat ika si [[Special:UserLogin|nakalaog]] para puede kang magribay kan saimong lista nin mga pigbabantayán.',
'addwatch' => 'Idagdag sa bantay-listahan',
'addedwatchtext' => 'Ining pahina "[[:$1]]" dinadagdag sa saimong mga [[Special:Watchlist|Bantay-listahan]].
An maabot na mga pagbabâgo sa páhinang ini asin sa asosyadong páhina nin olay paglilistahon duman.',
'removewatch' => 'Halion gikan sa bantay-listahan',
'removedwatchtext' => 'An pahina "[[:$1]]" pinaghale gikan sa [[Special:Watchlist|saimong bantay-listahan]].',
'watch' => 'Bantayán',
'watchthispage' => 'Bantayan ining pahina',
'unwatch' => 'Dai pagbantayan',
'unwatchthispage' => 'Pondohon an pagbantay',
'notanarticle' => 'Bakong páhina nin laog',
'notvisiblerev' => 'An huring rebisyon kan ibang paragamit pinagpura na',
'watchnochange' => 'Mayo sa saimong mga pigbabantayan an nahira sa laog nin pinahiling na pagkalawig.',
'watchlist-details' => '{{PLURAL:$1|$1 pahina|$1 mga pahina}} sa saimong bantay-listahan, dae binibilang an mga pahina nin orolayan.',
'wlheader-enotif' => "* Nakaandar an paising ''e''-surat.",
'wlheader-showupdated' => "* An mga páhinang pigbâgo poon kan huri mong bisita nakasurat nin '''mahîbog'''",
'watchmethod-recent' => 'Pigsososog an mga kaaagi pa sanang hirá sa mga pigbabantayan na páhina',
'watchmethod-list' => 'Pigsososog an mga pigbabantayan na páhina para mahiling an mga kaaagi pa sanan paghirá',
'watchlistcontains' => 'An saimong lista nin pigbabantayan igwang $1 na {{PLURAL:$1|páhina|mga páhina}}.',
'iteminvalidname' => "May problema sa bagay na '$1', salâ an pangaran...",
'wlnote' => "Sa ibaba an {{PLURAL:$1|huring pagbabago|mga huring '''$1''' pagbabago}} sa nakaaging {{PLURAL:$2|oras|'''$2''' mga oras}}, magpoon pa kan $3, $4.",
'wlshowlast' => 'Ipahilíng an ultimong $1 na oras $2 na aldaw $3',
'watchlist-options' => 'Bantay-listahan na mga pagpipilian',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Pigbabantayan...',
'unwatching' => 'Dai pigbabantayan...',
'watcherrortext' => 'Sarong kasalaan an nangyari habang binabago an saimong bantay-listahan na panuytoy para sa "$1".',

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
'enotif_body' => 'Namomotang $WATCHINGUSERNAME,

An {{SITENAME}} pahina $PAGETITLE pinagmukna $CHANGEDORCREATED kan $PAGEEDITDATE ni $PAGEEDITOR, hilngon sa $PAGETITLE_URL para sa presenteng rebisyon.

$NEWPAGE

Sumaryo kan paraliwat: $PAGESUMMARY $PAGEMINOREDIT

Kontaka an paraliwat:
e-surat: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Dae na magkakaigwa pa nin ibang pagpapaisi sa kaso na may kadagdagan pang mga pagbabago laen lang kun ika magbisita sa pahinang ini.
Ika mapuwede man na makapagbago kan bandera nin pagpapaisi para sa gabos mong pinagbabantayan na mga pahina na yaon sa saimong bantay-listahan.

An saimong mainamigong {{SITENAME}} sistema nin pagpapaisi

--
Sa pagbabago kan saimong e-surat na pagpapaising panuytoy, magbisita sa
{{canonicalurl:{{#special:Mga Kagustuhan}}}}

Sa pagbabago kan saimong bantay-listahang panuytoy, magbisita sa
{{canonicalurl:{{#special:EditWatchlist}}}}

Sa pagpura ka pahina gikan sa saimong bantay-listahan, magbisita sa
$UNWATCHURL

Balik-simbag asin kadagdagang asistensiya:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Paraon an pahina',
'confirm' => 'Kompermaron',
'excontent' => "Ini an dating laog: '$1'",
'excontentauthor' => "ini an dating laog: '$1' (asin an unikong kontribuidor si '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "Ini an dating laog bagô blinankohán: '$1'",
'exblank' => 'mayong laog an páhina',
'delete-confirm' => 'Puraon "$1"',
'delete-legend' => 'Paraon',
'historywarning' => "'''Patanid tabi:''' An pahina na saimong pagpupuraon may historiya na igwa nin haros $1 {{PLURAL:$1|rebisyon|mga rebisyon}}:",
'confirmdeletetext' => 'Paparaon mo sa base nin datos ining pahina kasabay an gabos na mga uusipón kaini.
Konpirmaron tabì na talagang boot mong gibohon ini, nasasabotan mo an mga resulta, asin an piggigibo mo ini konporme sa
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Nagibo na',
'actionfailed' => 'An aksyon nagpalya',
'deletedtext' => 'Pigparà na an "$1" .
Hilingón tabì an $2 para mahiling an lista nin mga kaaagi pa sanang pagparà.',
'dellogpage' => 'Usip nin pagparà',
'dellogpagetext' => 'Mahihiling sa babâ an lista kan mga pinakahuring pagparâ.',
'deletionlog' => 'Historial nin pagparâ',
'reverted' => 'Ibinalik sa mas naenot na pagpakarhay',
'deletecomment' => 'Rason:',
'deleteotherreason' => 'Iba/dugang na rason:',
'deletereasonotherlist' => 'Ibang rason',
'deletereason-dropdown' => '*Pirmehang rason nin pagpupura
** Kahagadan nin Awtor/Parasurat
** Kalapasan sa Copyright
** Bandalismo',
'delete-edit-reasonlist' => 'Pagliwat kan mga rason nin pagpupura',
'delete-toobig' => 'Ining pahina igwa nin dakulaong historiya sa pagliwat, minasobrang $1 {{PLURAL:$1|rebisyon|mga rebisyon}}.
An pagpupura kan nasambit na mga pahina dae pinagtutugot tanganing maiwasan an aksidenteng pagka-antala kan {{SITENAME}}.',
'delete-warning-toobig' => 'Ining pahina igwa nin dakulaong historiya sa pagliwat, minasobrang $1 {{PLURAL:$1|rebisyon|mga rebisyon}}.
An pagpupura kaini mapuwedeng makapag-antala sa mga operasyon kan datos-sarayan kan {{SITENAME}}; magpadagos tabi na igwang pag-iingat.',

# Rollback
'rollback' => 'Mga paghihira na pabalík',
'rollback_short' => 'pabalík',
'rollbacklink' => 'pabalikón',
'rollbacklinkcount' => 'ibalik $1 {{PLURAL:$1|pagliwat|mga pagliwat}}',
'rollbacklinkcount-morethan' => 'ibalik an sobrang $1  {{PLURAL:$1|pagliwat|mga pagliwat}}',
'rollbackfailed' => 'Prakaso an pagbalík',
'cantrollback' => 'Dai pwedeng bawîon an hirá; an huring kontribuidor iyo an unikong parásurat kan páhina.',
'alreadyrolled' => 'Dae maibalik an huring pagliwat kan [[:$1]] ni [[User:$2|$2]] ([[User talk:$2|olay]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
may iba na tabing nagliwat o nagbalik kan pahina.

An huring pagliwat sa pahina ginibo ni [[User:$3|$3]] ([[User talk:$3|olay]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "An sumaryo kan pagliwat: \"''\$1''\".",
'revertpage' => 'Ibinalik na mga pagliwat ni [[Special:Contributions/$2|$2]] ([[User talk:$2|talk]]) sagkod sa huring rebisyon ni [[User:$1|$1]]',
'revertpage-nouser' => 'Ibinalik na mga pagliwat ni (ngaran nin paragamit pinaghali) sagkod sa huring rebisyon ni [[User:$1|$1]]',
'rollback-success' => 'Binawî na mga paghirá ni $1; pigbalik sa dating bersyón ni $2.',

# Edit tokens
'sessionfailure-title' => 'Nagpalyang sesyon',
'sessionfailure' => "Garo may problema sa paglaog mo;
kinanselár ining aksyón bilang sarong paglikay kontra sa ''session hijacking''.
Pindotón tabî an \"back\" asin ikarga giraray an páhinang ginikanan mo, dangan probarán giraray.",

# Protect
'protectlogpage' => 'Usip nin pagsagáng',
'protectlogtext' => 'Sa ibaba iyo an sarong listahan kan mga pagbabago sa mga proteksyon kan pahina.
Hilnga tabi an [[Special:ProtectedPages|listahan kan protektadong mga pahina]] para sa listahan kan presenteng naggaganang mga proteksyon nin pahina.',
'protectedarticle' => 'protektado "[[$1]]"',
'modifiedarticleprotection' => 'binago an nibel nin proteksión para sa "[[$1]]"',
'unprotectedarticle' => 'pinaghaleng proteksyon gikan sa "[[$1]]"',
'movedarticleprotection' => 'pinaglipat an panuytoy kan proteksyon gikan sa "[[$2]]" sagkod "[[$1]]"',
'protect-title' => 'Pigpupuesta an nibel nin proteksión sa "$1"',
'protect-title-notallowed' => 'Hilnga na lebel nin proteksyon kan "$1"',
'prot_1movedto2' => '[[$1]] piglipat sa [[$2]]',
'protect-badnamespace-title' => 'Dae maprotektaran na espasyong-ngaran',
'protect-badnamespace-text' => 'Mga pahina kaining espasyong-ngaran dae tabi protektado.',
'protect-legend' => 'Kompermaron an proteksyon',
'protectcomment' => 'Rason:',
'protectexpiry' => 'Mápasó:',
'protect_expiry_invalid' => 'Dai pwede ining pahanon nin pagpasó.',
'protect_expiry_old' => 'Nakalihis na an panahon nin pagpasó.',
'protect-unchain-permissions' => 'Bukasi an kadagdagang pagpipilian kan proteksyon',
'protect-text' => "Pwede mong hilingón asin bàgohon an tangga nin proteksyon digdi para sa pahina '''$1'''.",
'protect-locked-blocked' => "Dai mo pwedeng bâgohon an mga tangga kan proteksyon mientras na ika nababágat. Ini an mga presenteng pwesto kan páhina '''$1''':",
'protect-locked-dblock' => "Dai puedeng ibalyo an mga nibel kan proteksión ta may actibong kandado sa base nin datos.
Ini an mga puesta sa ngunyan kaining páhina '''$1''':",
'protect-locked-access' => "Mayong permiso an account mo na magbàgo kan tangga nin proteksyon.
Uya an ngonyan na mga pwesto kan pahinang '''$1''':",
'protect-cascadeon' => 'Pigproprotektaran ining pahina sa ngonyan ta sabay ini sa mga nasunod na {{PLURAL:$1|pahina, na may|mga pahina, na may}} proteksyong katarata na nakaandar. Pwede mong bàgohon an tangga nin proteksyon kaining pahina, pero mayò ning epekto sa proteksyong katarata.',
'protect-default' => 'Tuguti an gabos na mga paragamit',
'protect-fallback' => 'Minatugot sana sa mga paragamit na igwang "$1" na permiso',
'protect-level-autoconfirmed' => 'Minatugot sana sa awtokumpirmadong mga paragamit',
'protect-level-sysop' => 'Minatugot sana sa mga administrador',
'protect-summary-cascade' => 'katarata',
'protect-expiring' => 'mápasó sa $1 (UTC)',
'protect-expiring-local' => 'mapalso sa $1',
'protect-expiry-indefinite' => 'daeng sagkodan',
'protect-cascade' => 'Protektarán an mga pahinang nakaiba sa pahinang ini (proteksyon katarata)',
'protect-cantedit' => 'Dai mo mariribayan an mga tanggá kan proteksyon kaining pahina huli ta mayò ka nin permiso na ligwatón ini.',
'protect-othertime' => 'Ibang panahon:',
'protect-othertime-op' => 'laeng oras',
'protect-existing-expiry' => 'Eksistidong oras nin pagpalso: $3, $2',
'protect-otherreason' => 'Laen/kadagdagang rason:',
'protect-otherreason-op' => 'Laeng rason',
'protect-dropdown' => '*Pirmehang mga rason nin proteksyon
** Sobrahon na bandalismo
** Sobrahon na pag-espam
** Kontra-produktibong iwalan sa pagliwat
** Halangkaw na trapiko kan pahina',
'protect-edit-reasonlist' => 'Liwaton an mga rason nin proteksyon',
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
'undeletepagetitle' => "'''An minasunod konsistido nin pinagpurang mga rebisyon kan [[:$1|$1]]'''.",
'viewdeletedpage' => 'Hilingón an mga pinarang pahina',
'undeletepagetext' => 'An minasunod na {{PLURAL:$1|pahina pinagpura na alagad yaon|$1 mga pahina pinagpura na alagad yaraon }} pa man sa arkibo asin puwedeng maipagbalik.
An arkibo mapupuwedeng peryodikal na paglilinigan.',
'undelete-fieldset-title' => 'Ibalik an mga rebisyon',
'undeleteextrahelp' => "Tanganing maibalik an enterong historiya kan pahina, pabayae na an gabos na mga kahon nin tsek dae pagkaagan asin i-klik mo an '''''{{int:undeletebtn}}'''''.
Tanganing gibohon an piniling restorasyon, i-tsek mo an mga kahon na kinatangudan kan mga rebisyon na ipagbabalik, asin i-klik an '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|na pagriribay|na mga pagriribay}} na nakaarchibo',
'undeletehistory' => 'Kun saimong ipagbalik an pahina, an gabos nga mga rebisyon ipagbabalik sa historiya.
Kun an baguhon na pahina na igwang kaparehas na ngaran naimukna na poon kan puraon, an ipinagbalik na mga rebisyon minaluwas sa nakaagi nang historiya.',
'undeleterevdel' => 'An dae pagpupura dae paggigibohon kun ini magreresulta sa kaibabawan kan pahina o rebisyon kan sagunson bilang parsiyal na pinagpura.
Sa arog na mga kaso, kaipuhan mong haleon an tsek o tagoon an pinakabaguhon na pinagpurang rebisyon.',
'undeletehistorynoadmin' => 'Pigparâ na ining péhina. Mahihiling an rason sa epitome sa babâ, kasabay sa mga detalye kan mga parágamit na naghira kaining páhina bago pigparâ. Sa mga administrador sana maipapahiling an mga pagribay sa mismong tekstong ini.',
'undelete-revision' => 'Pinagpurang rebisyon kan $1 (poon kan $4, sa oras na $5) ni $3:',
'undeleterevision-missing' => 'Dai pwede o nawawarang pagribay. Pwede ser na salâ an takod, o
binalik an na pagribay o hinalî sa archibo.',
'undelete-nodiff' => 'Mayo nin dating rebisyon an nanagboan.',
'undeletebtn' => 'Ibalik',
'undeletelink' => 'hilngon/ibalik',
'undeleteviewlink' => 'hilngon',
'undeletereset' => 'Ipwesto giraray',
'undeleteinvert' => 'Baliktada an pinilian',
'undeletecomment' => 'An rason:',
'undeletedrevisions' => '{{PLURAL:$1|1 rebisyon|$1 mga rebisyon}} ipinagbalik',
'undeletedrevisions-files' => '{{PLURAL:$1|1 rebisyon|$1 mga rebisyon}} asin {{PLURAL:$2|1 sagunson|$2 mga sagunson}} ipinagbalik',
'undeletedfiles' => '{{PLURAL:$1|1 sagunson|$1 mga sagunson}} ipinagbalik',
'cannotundelete' => 'Naprakaso an pagbalik kan pigparâ; pwede ser an binawi an pagparâ kan páhina kan ibang parágamit.',
'undeletedpage' => "'''binalik na an $1 '''

Ikonsultar an [[Special:Log/delete|historial nin pagparâ]] para mahiling an lista nin mga kaaaging pagparâ asin pagbalik.",
'undelete-header' => 'Hilingon an [[Special:Log/delete|historial kan pagparâ]] kan mga kaaagi pa sanang pinarang páhina.',
'undelete-search-title' => 'Hanapa an pinagpurang mga pahina',
'undelete-search-box' => 'Hanapón an mga pinarang pahina',
'undelete-search-prefix' => 'Hilingón an mga pahinang nagpopoon sa:',
'undelete-search-submit' => 'Hanápon',
'undelete-no-results' => 'Mayong nahanap na páhinang angay sa archibo kan mga pigparâ.',
'undelete-filename-mismatch' => "Dai pwedeng bawîon an pagparâ sa ''file'' sa pagkarhay na may tatâk nin oras na $1: dai kapadis an ''filename''",
'undelete-bad-store-key' => "Dai pwedeng bawîon an pagparâ nin ''file'' na pagpakarhay na may taták nin oras na $1: nawara an ''file'' bago an pagparâ.",
'undelete-cleanup-error' => "May salâ pagparâ kan ''file'' na archibong \"\$1\".",
'undelete-missing-filearchive' => "Dai maibalik an archibo kan ''file'' may na  ID $1 ta mayô ini sa base nin datos. Pwede ser na pigparâ na ini.",
'undelete-error' => 'Kasalaan sa dae pinagpupurang pahina',
'undelete-error-short' => "May salâ sa pagbalik kan pigparang ''file'': $1",
'undelete-error-long' => "May mga salâ na nasabat mientras sa pigbabalik an pigparang ''file'':

$1",
'undelete-show-file-confirm' => 'Segurado ka na gusto mong hilngon an pinagpurang rebisyon kan sagunson "<nowiki>$1</nowiki>" poon kan $2 oras na $3?',
'undelete-show-file-submit' => 'Iyo po',

# Namespace form on various pages
'namespace' => 'Liang-liang:',
'invert' => 'Pabaliktadón an pinili',
'tooltip-invert' => 'I-tsek ining kahon tanganing tagoon an mga pagbabago sa mga pahina na yaon sa laog kan pinagpiling espasyong-ngaran (asin an asosyado na espasyong-ngaran kun may tsek)',
'namespace_association' => 'Asosyado na espasyong-ngaran',
'tooltip-namespace_association' => 'I-tsek ining kahon tangani man ibali an olay o subheto na espasyong-ngaran na asosyado sa pinagpili na espasyong-ngaran',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Mga kontribusyon kan parágamit',
'contributions-title' => 'Mga kontribusyon kan paragamit para sa $1',
'mycontris' => 'Mga Kaarambagan',
'contribsub2' => 'Para sa $1 ($2)',
'nocontribs' => 'Mayong mga pagbabago na nahanap na kapadis sa ining mga criteria.',
'uctop' => '(alituktok)',
'month' => 'Poon bulan (asin mas amay):',
'year' => 'Poon taon (asin mas amay):',

'sp-contributions-newbies' => 'Ipahiling an mga kontribusión kan mga bagong kuenta sana',
'sp-contributions-newbies-sub' => 'Para sa mga bàgong account',
'sp-contributions-newbies-title' => 'Mga kontribusyon kan paragamit para sa baguhon an mga panindog',
'sp-contributions-blocklog' => 'Bagáton an usip',
'sp-contributions-deleted' => 'pinagpurang mga kontribusyon kan paragamit',
'sp-contributions-uploads' => 'mga ikinarga',
'sp-contributions-logs' => 'mga tinalaan',
'sp-contributions-talk' => 'olayan',
'sp-contributions-userrights' => 'manihamento sa mga karapatan kan paragamit',
'sp-contributions-blocked-notice' => 'Ining paragamit sa presente pinagbarahan.
An pinakahuring entrada sa talaan nin pagbara nakahaya sa ibaba bilang reperensiya:',
'sp-contributions-blocked-notice-anon' => 'Ining IP adres sa presente pinagbarahan.
An pinakahuring entrada sa talaan nin pagbara nakahaya sa ibaba bilang reperensiya:',
'sp-contributions-search' => 'Maghanap nin mga kontribusyon',
'sp-contributions-username' => 'IP o ngaran kan parágamit:',
'sp-contributions-toponly' => 'Minapahiling sana nin mga pagliwat na pinakahurihang mga rebisyon',
'sp-contributions-submit' => 'Hanápon',

# What links here
'whatlinkshere' => 'An nakatakód digdí',
'whatlinkshere-title' => 'Mga pahina na nakasugpon sa "$1"',
'whatlinkshere-page' => 'Pahina:',
'linkshere' => "An mga minasunod na pahina nakatakod sa '''[[:$1]]''':",
'nolinkshere' => "Mayong pahinang nakatakod sa '''[[:$1]]'''.",
'nolinkshere-ns' => "Mayong pahina na nakatakod sa '''[[:$1]]''' sa piniling ngaran-espacio.",
'isredirect' => 'ilikay an pahina',
'istemplate' => 'kabali',
'isimage' => 'kasugpon nin sagunson',
'whatlinkshere-prev' => '{{PLURAL:$1|nakaagi|nakaaging $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|sunod|sunod na $1}}',
'whatlinkshere-links' => '← mga takod',
'whatlinkshere-hideredirs' => '$1 mga panukdong otro',
'whatlinkshere-hidetrans' => '$1 kabaling-binalyuhan',
'whatlinkshere-hidelinks' => '$1 mga kasugpon',
'whatlinkshere-hideimages' => '$1 mga kasugpon nin mga sagunson',
'whatlinkshere-filters' => 'Mga pansarà',

# Block/unblock
'autoblockid' => 'Awtomatikong-kabarahan #$1',
'block' => 'Barahon an paragamit',
'unblock' => 'Haleon an bara kan paragamit',
'blockip' => 'Bagáton an paragamit',
'blockip-title' => 'Barahon an paragamit',
'blockip-legend' => 'Kubkuba an paragamit',
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
'ipb-hardblock' => 'Pugulan an yaon sa laog na mga paragamit na magliliwat gikan kaining IP adres',
'ipbcreateaccount' => 'Pugulon an pagibo nin kuenta.',
'ipbemailban' => 'Pugolan ining paragamit na magpadara nin e-surat',
'ipbenableautoblock' => 'Enseguidang bagaton an huring direccion nin  IP na ginamit kaining paragamit, asin kon ano pang ibang IP na proprobaran nindang gamiton',
'ipbsubmit' => 'Bagáton ining parágamit',
'ipbother' => 'Ibang oras:',
'ipboptions' => '2ng oras:2 hours,1ng aldaw:1 day,3ng aldaw:3 days,1ng semana:1 week,2ng semana:2 weeks,1ng bulan:1 month,3ng bulan:3 months,6 na bulan:6 months,1ng taon:1 year,daing kasagkoran:infinite',
'ipbotheroption' => 'iba',
'ipbotherreason' => 'Iba/dugang na rasón:',
'ipbhidename' => 'Tagoon an ngaran nin paragamit gikan sa mga pagliliwat asin mga listahan',
'ipbwatchuser' => 'Bantayi ining gamit kan paragamit asin mga pahina nin olayan',
'ipb-disableusertalk' => 'Pugulan ining paragamit na magliliwat kan saiyang sadireng pahina nin olayan habang ini barado',
'ipb-change-block' => 'Barahan-otro an paragamit na igwa kaining mga panuytoy',
'ipb-confirm' => 'Kumpirmaron an pagbara',
'badipaddress' => 'Dai pwede ining IP',
'blockipsuccesssub' => 'Nagibo na an pagbagát',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] pinagbarahan.<br />
Hilngon an [[Special:BlockList|listahan kan binarahan]] tanganing repasohon an mga binara.',
'ipb-blockingself' => 'Pagbabarahon mo an sadire mo! Segurado ka daw na saimong kagustuhan an gibohon ini?',
'ipb-confirmhideuser' => 'Ika magbabara nin sarong paragamit na igwang "tagoon an paragamit" na nakagana. Ini magtatago kan ngaran nin paragamit sa gabos na mga listahan asin mga entrada sa talaan. Segurado ka daw na saimong kagustuhan an gibohon ini?',
'ipb-edit-dropdown' => 'Hirahón an mga rasón sa pagbabagát',
'ipb-unblock-addr' => 'Paagihon $1',
'ipb-unblock' => 'Bawion an pagbagat nin ngaran nin paragamit o direccion nin IP',
'ipb-blocklist' => 'Hilingon an mga presenteng binagat',
'ipb-blocklist-contribs' => 'Mga kontribusyon para sa $1',
'unblockip' => 'Paagihon an parâgamit',
'unblockiptext' => 'Gamiton an pormulario sa baba para puede giraray suratan an dating binagat na direccion nin IP address o ngaran nin paragamit.',
'ipusubmit' => 'Haleon ining bara',
'unblocked' => 'Binawi na an pagbagat ki [[User:$1|$1]]',
'unblocked-range' => '$1 pinaghale na an bara',
'unblocked-id' => 'Hinali na an bagat na $1',
'blocklist' => 'Pinagbarang na mga paragamit',
'ipblocklist' => 'Baradong mga paragamit',
'ipblocklist-legend' => 'Hanapon an sarong binagát na paragamit',
'blocklist-userblocks' => 'Tagoon an mga bara sa panindog',
'blocklist-tempblocks' => 'Tagoon an temporaryong mga pambara',
'blocklist-addressblocks' => 'Tagoon an solong pambara kan IP',
'blocklist-rangeblocks' => 'Tagoon an mahiwason na mga pambara',
'blocklist-timestamp' => 'pandukot-oras',
'blocklist-target' => 'Target',
'blocklist-expiry' => 'Mapasohon',
'blocklist-by' => 'Admin sa pagbabara',
'blocklist-params' => 'Mga parametro nin pagbabara',
'blocklist-reason' => 'Kausa',
'ipblocklist-submit' => 'Hanápon',
'ipblocklist-localblock' => 'Pambara sa lokal',
'ipblocklist-otherblocks' => 'Ibang {{PLURAL:$1|pambara|mga pambara}}',
'infiniteblock' => 'daing siring',
'expiringblock' => 'mapaso sa $1 sa oras na $2',
'anononlyblock' => 'anon. sana',
'noautoblockblock' => 'pigpopondo an enseguidang pagbagat',
'createaccountblock' => 'binagat an paggibo nin kuenta',
'emailblock' => 'binagát an e-surat',
'blocklist-nousertalk' => 'dae makakaliwat kan sadireng pahina nin olayan',
'ipblocklist-empty' => 'Mayong laog an lista nin mga binagat.',
'ipblocklist-no-results' => 'Dai nabagat an hinagad na direccion nin IP o ngaran nin paragamit.',
'blocklink' => 'bagáton',
'unblocklink' => 'paagihon',
'change-blocklink' => 'sanglián an pagbagat',
'contribslink' => 'mga ambág',
'emaillink' => 'ipadara an e-surat',
'autoblocker' => 'Enseguidang binagat an saimong direccion nin IP ta kaaaging ginamit ini ni "[[User:$1|$1]]". An rason nin pagbagat ni $1: "$2"',
'blocklogpage' => 'Usip nin pagbagat',
'blocklog-showlog' => 'Ining paragamit dati nang pinagbarahan.
An talaan nin pagbara nakahaya sa ibaba bilang reperensiya:',
'blocklog-showsuppresslog' => 'Ining paragamit pinagkubkob asin dati nang ipinagtago.
An talaan nin pagpaunlok ipinagtao sa ibaba para hilingan.',
'blocklogentry' => 'binagat na [[$1]] na may oras nin pagpaso na $2 $3',
'reblock-logentry' => 'pinagliwat an mga panuytoy nin pagkubkob para sa [[$1]] na igwang oras nin pagpaso kan $2 $3',
'blocklogtext' => 'Ini sarong talaan kan paragamit na nagkukubkob asin dae nagkukubkob na mga aksyon.
An awtomatikong pinagkubkob na IP na mga estada dae pinaglista.
Hilngon sa [[Special:BlockList|listahan nin kubkob]] para sa listahan kan presenteng operasyonal na mga pagbabara asin mga pagkukubkob.',
'unblocklogentry' => 'binawi an pagbagat $1',
'block-log-flags-anononly' => 'Mga paragamit na anónimo sana',
'block-log-flags-nocreate' => "pigpopondohán an paggibo nin ''account'",
'block-log-flags-noautoblock' => 'pigpopondo an enseguidang pagbagat',
'block-log-flags-noemail' => 'binagát an e-surat',
'block-log-flags-nousertalk' => 'dae makakaliwat nin sadireng pahina nin olay',
'block-log-flags-angry-autoblock' => 'pinakusog na awto-kubkob pinaandar',
'block-log-flags-hiddenname' => 'pangaran nin paragamit itinago',
'range_block_disabled' => 'Pigpopondo an abilidad kan sysop na maggibo nin bagat na hilera.',
'ipb_expiry_invalid' => 'Dai pwede ini bilang oras kan pagpasó.',
'ipb_expiry_temp' => 'Itinagong pangaran nin paragamit na nagkukubkob dapat na magin permanente.',
'ipb_hide_invalid' => 'Dae nakayanan na untukon ining panindog; ini gayod nagkaigwa nin kadakulon na mga pagliliwat.',
'ipb_already_blocked' => 'An "$1" pinagkubkob na',
'ipb-needreblock' => 'An $1 pinagkubkob na. Gusto mong liwaton an mga panuytoy?',
'ipb-otherblocks-header' => 'An ibang {{PLURAL:$1|kubkob|mga kubkob}}',
'unblock-hideuser' => 'Ika dae makakakubkog kaining paragamit, siring na an saindang paragamit na ngaran itinatago.',
'ipb_cant_unblock' => 'Error: Dai nahanap an ID nin binagat na $1. Puede ser na dati nang binawi an pagbagat kaini.',
'ipb_blocked_as_range' => 'Kasalaan: An IP na estada $1 dae direktang pinagkubkob asin dae puwedeng dae makukubkob.
Ini, baya, pinagkubkob bilang parte kan hidwas $2, na mapuwedeng daemakukubkob.',
'ip_range_invalid' => 'Dai pwede ining serye nin IP.',
'ip_range_toolarge' => 'An hidwas kan mga kubkob dakulaon kesa /$1 dae pinagtutugutan.',
'blockme' => 'Kubkuba ako',
'proxyblocker' => 'Parabagát na karibay',
'proxyblocker-disabled' => 'Ining punksyon pinag-untok.',
'proxyblockreason' => 'Binagat an saimong direccion nin IP ta ini sarong bukas na proxy. Apodon tabi an saimong Internet service provider o tech support asin ipaaram sainda ining seriosong problema nin seguridad.',
'proxyblocksuccess' => 'Tapos.',
'sorbsreason' => 'An saimong IP na estada pinaglista bilang sarong bukas na proksi sa lang kan DNSBL na ginagamit kan {{SITENAME}}.',
'sorbs_create_account_reason' => 'An saimong IP na estada pinaglista bilang sarong bukas na proksi sa laog kan DNSBL na ginagamit kan {{SITENAME}}.
Ika dae makakamukna nin sarong panindog.',
'cant-block-while-blocked' => 'Ika dae makakakubkob kan ibang mga paragamit mantang ika nakukubkob pa.',
'cant-see-hidden-user' => 'An paragamit na pinagpubaran mong kubkubon pinagkubkob asin pinagtago na. Mala ta ika mayo nin karapatan na magtago nin paragamit, ika dae makakahiling or makakaliwat kan kinubkob na paragamit.',
'ipbblocked' => 'Ika da makakakubkob or maghale nin kubkob sa ibang mga paragamit, nin huli ta ika mismo sa sadiri mo pinagkubkob na',
'ipbnounblockself' => 'Ika dae pinagtutugutan na magkubkob kan sadiri mo',

# Developer tools
'lockdb' => 'Ikandado an base nin datos',
'unlockdb' => 'Ibukás an base nin datos',
'lockdbtext' => 'An pagkakandado kan datos-sarayan mag-uuntok sa abilidad kan gabos na mga paragamit na pagliwat nin mga pahina, pagsasangli kan saindang mga kamuyahan, pagliliwat kan saindang mga bantay-listahan, asin iba pang mga bagay na nagkakaipo nin mga pagsasangli sa laog kan datos-sarayan. Pakikumpirma lang tabi kun iyo ini an boot mong gibohon, asin na saimong bukasan an datos-saray kun an saimong pagpapakarhay tapos na.',
'unlockdbtext' => 'An pagbubukas kan datos-sarayan magbabalik-liwat kan abilidad nin gabos na mga paragamit na makapagliwat nin mga pahina, pagsasangli kan saindang mga kamuyahan, pagliliwat kan saindang mga bantay-listahan, asin iba pang mga bagay na nagkakaipo nin mga pagsasangli sa laog kan datos-sarayan. Pakikumpirma lang tabi kun iyo ini an boot mong gibohon.',
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
'lockedbyandtime' => '(sa paagi ni {{GENDER:$1|$1}} kan $2 sa ika-$3)',

# Move page
'move-page' => 'Ibalyo an $1',
'move-page-legend' => 'Ibalyó an páhina',
'movepagetext' => "Sa paggagamit kan porma na yaon sa ibaba mariribayan nin pangaran an sarong pahina, maibabalyo an gabos kaining historiya pasiring sa baguhon na titulo.
Ika makakapagsumpay kan mga panlikwat na magtutukdo awtomatiko pasiring sa orihinal na titulo.
Kun saimong pinili na dae, seguraduhon na ma-tsek para sa [[Special:DoubleRedirects|doble]] o [[Special:BrokenRedirects|nabaak namga panlikwat]].
Ika an responsable para himoong segurado na an mga kilyaw padagos na minatukdo kun saen sinda dapat na magduman.

Giromdoma na an pahina '''dae''' maibabalyo kun igwa na nin sarong pahina sa baguhon na titulo, laen lang kun ini daeng laman o sarong panlikwat asin mayo nin nakaaging historiya nin pagliwat.
Ini minapasabot na ika makakapagliwat nin pangaran nin sarong pahina pabalik sa kun saen ini pinagliwatan nin pangaran kun ika nakahimo nin kasalaan, asin ika dae makakasalambaw nin sarong eksistido nang pahina.

'''Patanid!'''
Ini magigin sarong biglaan asin dae inaasahan na kaliwatan para sa sarong bantugan na pahina; pakiseguro sana na saimong nasabutan an mga konsekuwensiya kaini bago ipagpadagos.",
'movepagetext-noredirectfixer' => "An paggamit kan porma na yaon sa ibaba magliliwat sa pangaran kan pahina, magbabalyo kan gabos kaining historiya paduman sa baguhon na pangaran.
An lumang titulo magigin sarong panlikwat na pahina paduman sa baguhon na titulo.
Magin paseguro na magmansay nin [[Special:DoubleRedirects|doble]] o [[Special:BrokenRedirects|baraak na panlikwat]].
Ika an responsable para himoon na segurado na an mga kilyaw padagos na magtutukdo kun saen sinda dapat na magduman.

Tandaan na an pahina '''dae''' maibabalyo kun igwa na nin sarong pahina sa baguhon na titulo, lean lang kun ini mayong laog o sarong panlikwat asin mayo nin nakaaging historiya nin pagliwat.
Ini minapasabot na ika makakapagliwat nin pangaran kan saron gpahina pabalik sa kun saen ini pinagliwat an pangaran sa piggikanan kun ika makahimo nin sarong kasalaan, asin ika dae makakasalambaw kan sarong eksistido nang pahina.

'''Patanid!'''
Ini mapuwedeng sarong hidalion asin dae inaasahan na kaliwatan para sa sarong bantugan na pahina;
pakipaseguro baya na ika nakakasabot sa mga konsekuwensiya kaini bago magpapadagos.",
'movepagetalktext' => "An kapadis na olay na páhina enseguidang ibabalyo kasabay kaini '''kun:'''
*Igwa nang may laog na olay na páhina na may parehong pangaran, o
*Halîon mo an marka sa kahon sa babâ.

Sa mga kasong iyan, kaipuhan mong ibalyo o isalak an páhina nin mano-mano kun boot mo.",
'movearticle' => 'Ibalyó an pahina:',
'moveuserpage-warning' => "'''Patanid:''' Ika magpopoon na magbalyo in sarong pahina nin paragamit. Pakitandaan tabi na an pahina sana na ipagbabalyo asin an paragamit '''dae''' maipagliliwat an pangaran.",
'movenologin' => 'Mayô sa laog',
'movenologintext' => 'Kaipuhan na rehistradong parágamit ka asin si [[Special:UserLogin|nakalaog]] tangarig makabalyó ka nin páhina.',
'movenotallowed' => 'Mayô kang permiso na ibalyó an mga pahina sa wiki na ini.',
'movenotallowedfile' => 'Ika mayo nin permiso na magbabalyo nin mga sagunson.',
'cant-move-user-page' => 'Ika mayo nin permiso na magbabalyo nin mga pahina nin paragamit (laen pa sa mga sub-pahina).',
'cant-move-to-user-page' => 'Ika mayo nin permiso na magbabalyo nin pahina paduman sa sa sarong pahina nin paragamit (laen pa sa sub-pahina nin paragamit).',
'newtitle' => 'Sa bàgong titulong:',
'move-watch' => 'Bantayán ining pahina',
'movepagebtn' => 'Ibalyó an pahina',
'pagemovedsub' => 'Naibalyó na',
'movepage-moved' => '\'\'\'Naihubò na an "$1" sa "$2"\'\'\'',
'movepage-moved-redirect' => 'An panlikwat pinagmukna na.',
'movepage-moved-noredirect' => 'An pagmumukna kan sarong panlikwat pinagtago na.',
'articleexists' => 'Igwa nang pahina sa parehong pangaran, o dai pwede an pangaran na pigpilì mo.
Magpilì tabì nin ibang pangaran.',
'cantmove-titleprotected' => 'Ika dae makakapagbalyo nin pahina sa lokasyon na ini, nin huli ta an baguhon na titulo protektado na gikan sa pagmumukna',
'talkexists' => "'''Ibinalyo na an mismong pahina, alagad dai naibalyo an pahina nin orolay ta igwa na kaini sa bàgong titulo. Pagsaroon tabì ining duwa nin mano-mano.'''",
'movedto' => 'piglipat sa',
'movetalk' => 'Ibalyo an pahinang orolayan na nakaasociar',
'move-subpages' => 'Ibalyo an mga sub-pahina (sagkod sa $1)',
'move-talk-subpages' => 'Ibalyo an mga sub-pahina kan pahina nin olay (sagkod sa $1)',
'movepage-page-exists' => 'An pahina sa $1 eksistido na asin bako tabi awtomatikong masasalambawan.',
'movepage-page-moved' => 'An pahina $1 pinagbalyo na paduman sa $2.',
'movepage-page-unmoved' => 'An pahina $1 dae maipagbabalyo paduman sa $2.',
'movepage-max-pages' => 'An pinakahalangkawon na $1 {{PLURAL:S1|pahina|mga pahina}} pinagbalyo na asin mayo tabi na awtomatikong maipagbabalyo.',
'movelogpage' => 'Ibalyó an usip',
'movelogpagetext' => 'Nasa ibaba an lista kan pahinang pigbalyó.',
'movesubpage' => '{{PLURAL:$1|Sub-pahina|Mga Sub-pahina}}',
'movesubpagetext' => 'Ining pahina igwa nin $1 {{PLURAL:$1|sub-pahina|mga sub-pahina}} na ipinapahiling sa ibaba.',
'movenosubpage' => 'Ining pahina mayo nin mga sub-pahina.',
'movereason' => 'Rason:',
'revertmove' => 'ibalík',
'delete_and_move' => 'Parâon asin ibalyó',
'delete_and_move_text' => '==Kaipuhan na parâon==

Igwa nang páhina na "[[:$1]]". Gusto mong parâon ini tangarig maibalyó?',
'delete_and_move_confirm' => 'Iyo, parâon an pahina',
'delete_and_move_reason' => 'Pinagpura sa paghimo nin dalan para maibalyo gikan sa "[[$1]]"',
'selfmove' => 'Pareho an páhinang ginikanan asin destinasyon; dai pwedeng ibalyó an sarong páhina sa sadiri.',
'immobile-source-namespace' => 'Dae makakapagbalyo nin mga pahina sa espasyong-pangaran na "$1"',
'immobile-target-namespace' => 'Dae makakapagbalyo nin mga pahina pasiring sa espasyong-pangaran na "$1"',
'immobile-target-namespace-iw' => 'An Interwiki na kilyaw bakong balido puntirya para sa pagbalyo nin pahina.',
'immobile-source-page' => 'Ining pahina bakong mabalyuhon.',
'immobile-target-page' => 'Dae makakabalyo paduman sa titulong destinasyon.',
'imagenocrossnamespace' => 'Dae makakapagbalyo nin sagunson paduman sa bakong sagunson na espasyong pangaran.',
'nonfile-cannot-move-to-file' => 'Dae makakapagbalyo nin bakong-sagunson pasiring sa sagunson kan espasyong-pangaran',
'imagetypemismatch' => 'An baguhon na ekstensyon nin sagunson dae mai-aampad sa tipong ini',
'imageinvalidfilename' => 'An puntiryang nin pangaran-sagunson imbalido',
'fix-double-redirects' => 'An panumpay sa arinman na mga panlikwat na nagtutukdo paduman sa orihinal na titulo',
'move-leave-redirect' => 'Walaton an sarong panlikwat sa likod',
'protectedpagemovewarning' => "'''Patanid:''' Ining pahina protektado tangani na an mga paragamit sana na igwang administrador na mga pribilihiyo an makakapagbalyo kaini.
An pinakahuring entrada sa talaan pinagtao sa ibaba para sa reperensiya:",
'semiprotectedpagemovewarning' => "'''Giromdomon:''' Ining pahina protektado tanganing an mga rehistradong paragamit sana an makakabalyo kaini. 
An pinakahuring entrada sa talaan pinagtao sa ibaba para sa reperensiya:",
'move-over-sharedrepo' => '== Yaon nang Sagunson ==
[[:$1]] yaon na sa pinagheras na repositoryo. An pagbabalyo nin sagunson paduman kaining titulo masalambaw sa pinagheras na sagunson.',
'file-exists-sharedrepo' => 'An pangaran nin saguson na pinili ginagamit na sa pinagheras na repositoryo.
Pakipili kan ibang pangaran.',

# Export
'export' => 'Iluwas an mga pahina',
'exporttext' => 'Pwede mong ipadara an teksto asin historya nin paghirá kan sarong partikular na páhina o grupo nin mga páhina na nakapatos sa ibang XML. Pwede ining ipadara sa ibang wiki gamit an MediaWiki sa paagi kan [[Special:Import|pagpadara nin páhina]].

Para makapadara nin mga páhina, ilaag an mga titulo sa kahon para sa teksto sa babâ, sarong titulo kada linya, dangan pilîon kun boot mo presenteng bersyón asin dating bersyón, na may mga linya kan historya, o an presenteng bersyón sana na may impormasyon manonongod sa huring hirá.

Sa kaso kan huri, pwede ka man na maggamit nin takod, arog kan [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para sa páhinang "[[{{MediaWiki:Mainpage}}]]".',
'exportall' => 'Isalimbago an gabos na mga pahina',
'exportcuronly' => 'Mga presenteng pagpakarhay sana an ibali, bakong an enterong historya',
'exportnohistory' => "----
'''Paisi:''' Dai pigpatogotan an pagpadara kan enterong historya kan mga páhina sa paagi kaining forma huli sa mga rasón dapit sa pagsagibo kaini.",
'exportlistauthors' => 'Ibali an sarong bilog na listahan kan mga paraambag para sa lambang pahina',
'export-submit' => 'Ipaluwás',
'export-addcattext' => 'Magdugang nin mga pahina sa kategoryang ini:',
'export-addcat' => 'Magdugang',
'export-addnstext' => 'Idugang an mga pahina gikan sa espasyong-pangaran:',
'export-addns' => 'Magdagdag',
'export-download' => "Hapotón ku gustong itagama bilang sarong ''file''",
'export-templates' => 'Ibali an mga templato',
'export-pagelinks' => 'Inbai an pinagkilyaw na mga pahina pasiring sa sarong kairaroman na:',

# Namespace 8 related
'allmessages' => 'Mga mensahe sa sistema',
'allmessagesname' => 'Pangaran',
'allmessagesdefault' => 'Tekstong normal',
'allmessagescurrent' => 'Presenteng teksto',
'allmessagestext' => 'Ini sarong listahan nin pansistemang mga mensahe na mananagboan sa espasyong-pangaran kan MediaWiki.
Pakibisita an [//www.mediawiki.org/wiki/Localisation Mediawiki Lokalisasyon] asin [//translatewiki.net translatewiki.net] kun boot mong mag-ambag sa henerikong lokalisasyon kan MediaWiki.',
'allmessagesnotsupportedDB' => "Dai pwedeng gamiton an '''{{ns:special}}:Allmessages''' ta sarado an '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-legend' => 'An Pansara',
'allmessages-filter' => 'Pansara sa paagi kan estado nin kustomisasyon:',
'allmessages-filter-unmodified' => 'Bakong modipikado',
'allmessages-filter-all' => 'Gabos',
'allmessages-filter-modified' => 'Modipikado',
'allmessages-prefix' => 'Pansara sa paagi kan enot-panigmit:',
'allmessages-language' => 'Lengguwahe:',
'allmessages-filter-submit' => 'Dumuman',

# Thumbnails
'thumbnail-more' => 'Padakuláon',
'filemissing' => "Nawawarâ an ''file''",
'thumbnail_error' => 'Error sa paggigibo kan retratito: $1',
'djvu_page_error' => 'luwas sa serye an páhina kan DjVu',
'djvu_no_xml' => 'Dai makua an XML para sa DjVu file',
'thumbnail-temp-create' => 'Dae nakamukna nin temporaryong sagunson kan retrato',
'thumbnail-dest-create' => 'Dae nakatagama kan retrato sa destinasyon',
'thumbnail_invalid_params' => 'Dai pwede an mga parámetro kaining retratito',
'thumbnail_dest_directory' => 'Dai makagibo kan destinasyon kan direktoryo',
'thumbnail_image-type' => 'An tipo kan imahe bakong suportado',
'thumbnail_gd-library' => 'Bakong kumpleto an kasalansanan kan kalibrohang GD: Nawawara an trabaho kan $1',
'thumbnail_image-missing' => 'An sagunson garo baga nawawara: $1',

# Special:Import
'import' => 'Ilaog an mga páhina',
'importinterwiki' => 'Ipadara an Transwiki',
'import-interwiki-text' => 'Pumili nin sarong wiki asin titulo kan pahina na importaron.
Mga petsa nin kaliwatan asin pangaran kan mga paraliwat pagpepreserbaron.
Gabos na aksyon nin importa sa transwiki nakatala sa [[Special:Log/import|talaan nin importa]].',
'import-interwiki-source' => 'Ginikanang wiki/pahina:',
'import-interwiki-history' => 'Kopyahon an gabos na mga bersyón para sa páhinang ini',
'import-interwiki-templates' => 'Ibali an gabos na mga panguyog',
'import-interwiki-submit' => 'Ipalaog',
'import-interwiki-namespace' => 'Destinasyon kan espasyong-pangaran:',
'import-interwiki-rootpage' => 'Destinasyon kan ugat pahina (opsyonal):',
'import-upload-filename' => 'Sagunsong Pangaran:',
'import-comment' => 'Komento:',
'importtext' => 'Paki-eksporta an sagunson gikan sa ginikanang wiki na gamit an [[Special:Export|gamiton pan-eksporta]].
Itagama ini sa saimong kompyuter asin ikarga ini digde.',
'importstart' => 'Piglalaog an mga páhina...',
'import-revision-count' => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'importnopages' => 'Mayong mga páhinang ipapadara.',
'imported-log-entries' => 'Importado $1 {{PLURAL:$1|talaan na entrada|talaan na mga entrada}}.',
'importfailed' => 'Bakong matriumpo an pagpadara: $1',
'importunknownsource' => 'Dai aram an tipo kan gigikanan kan ipapadara',
'importcantopen' => "Dai mabukasan an pigpadarang ''file''",
'importbadinterwiki' => 'Salâ an takod na interwiki',
'importnotext' => 'Mayong laog o mayong teksto',
'importsuccess' => 'Tapos na an importa!',
'importhistoryconflict' => 'Igwang nagsalimbayan sa historiya kan rebisyon (puwedeng importado na ining pahina kaidto)',
'importnosources' => 'Mayong transwiki na mga ginikanan sa importa an pinagpasabot asin direktang historiya nin mga pagkakarga pinag-untok.',
'importnofile' => "Mayong ipinadarang ''file'' an naikarga.",
'importuploaderrorsize' => 'Pagkarga kan ini-importang sagunson nagpalya.
An sagunson dakulaon kesa sa itinutugot na kadakulaan nin pagkarga.',
'importuploaderrorpartial' => 'Pagkarga kan ini-importang sagunson nagpalya.
An sagunson igwang parte sana an naikarga.',
'importuploaderrortemp' => 'Pagkarga kan ini-importang sagunson nagpalya.
An temporaryong polder nawawara.',
'import-parse-failure' => 'XML importang panabot puminalya',
'import-noarticle' => 'Mayong pahina na maiimporta!',
'import-nonewrevisions' => 'An gabos na mga rebisyon dati nang importado.',
'xml-error-string' => '$1 sa linya $2, kol $3 (bayta $4): $5',
'import-upload' => 'Ikarga an XML na datos',
'import-token-mismatch' => 'Nawara an datos kan sesyon.
Paki-otro giraray.',
'import-invalid-interwiki' => 'Dae makakapag-importa gikan sa pinagsambit na wiki.',
'import-error-edit' => 'An pahina "$1" bakong importado nin huli ta ika dae tinutugutan na magliliwat kaini.',
'import-error-create' => 'An pahina "$1" bakong importado nin huli ta ika dae tinutugutan na magmumukna kaini.',
'import-error-interwiki' => 'An pahina "$1" bakong importado nin huli ta an pangaran kaini reserbado na para sa panluwas na kasugpunan (interwiki).',
'import-error-special' => 'An pahina "$1" bakong importado nin huli ta ini kabali sa espesyal an espasyong-ngaran na dae nagtutugot nin mga pahina.',
'import-error-invalid' => 'An pahina "$1" bakong importado nin huli ta an ngaran kaini imbalido.',
'import-options-wrong' => 'Salang {{PLURAL:$2|pagpipilian|mga pagpipilian}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'An pinagtaong ugat na pahina sarong imbalidong titulo.',
'import-rootpage-nosubpage' => 'Espasyong-ngaran "$1" kan ugat na pahina dae minatugot nin pan-irarom na mga pahina.',

# Import log
'importlogpage' => 'Usip nin pagpalaog',
'importlogpagetext' => 'Administratibong mga importadong pahina na igwang historiya nin pagliliwat gikan sa ibang wikis.',
'import-logentry-upload' => "pigpadara an [[$1]] kan pagkarga nin ''file''",
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|rebisyon|mga rebisyon}}',
'import-logentry-interwiki' => 'na-transwiki an $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|rebisyon|mga rebisyon}} gikan sa $2',

# JavaScriptTest
'javascripttest' => 'Testing sa JavaScript',
'javascripttest-disabled' => 'Ining punksyon dae pinagpagana sa wiki na ini.',
'javascripttest-title' => 'Pinapadalagan na $1 na mga pagtesting',
'javascripttest-pagetext-noframework' => 'An pahinang ini reserbado para sa pagpapadalagan kan mga pagtesting sa JavaScript.',
'javascripttest-pagetext-unknownframework' => 'Bakong bistadong modelo para sa pagtesting kan "$1".',
'javascripttest-pagetext-frameworks' => 'Pakipili tabi nin saro sa minasunod na mga modelo sa pagtesting: $1',
'javascripttest-pagetext-skins' => 'Magpili nin sarong kublit tanganing padalaganon an mga pagtesting sa:',
'javascripttest-qunit-intro' => 'Hilngon [$1 dokumentasyon sa pagtesting] sa mediawiki.org.',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit kuwarto nin pagtesting',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'An saimong paragamit na pahina',
'tooltip-pt-anonuserpage' => 'An páhina nin páragamit para sa ip na pighihira mo bilang',
'tooltip-pt-mytalk' => 'An saimong pahina sa olayan',
'tooltip-pt-anontalk' => 'Mga olay manonongod sa mga hira halî sa ip na ini',
'tooltip-pt-preferences' => 'Mga kabòtan ko',
'tooltip-pt-watchlist' => 'Lista nin mga pahina na pigbabantayan an mga pagbabàgo',
'tooltip-pt-mycontris' => 'Sarong listahan kan saimong mga kontribusyon',
'tooltip-pt-login' => 'Pigaagda kang maglaog, alagad, bako man ining piriritan.',
'tooltip-pt-anonlogin' => 'Pig-aagda kang maglaog, alagad, bakô man ining piriritan.',
'tooltip-pt-logout' => 'Magluwas',
'tooltip-ca-talk' => 'Olay sa pahina nin laog',
'tooltip-ca-edit' => 'Pwede mong hirahón ining pahina. Gamiton tabi an patànaw na butones bago an pagtagama.',
'tooltip-ca-addsection' => 'Magpoon nin sarong baguhon na seksyon',
'tooltip-ca-viewsource' => 'Sinagangán ining pahina. Mahihilíng mo an ginikanan.',
'tooltip-ca-history' => 'Mga nakaaging bersyon kaining pahina',
'tooltip-ca-protect' => 'Protektahán ining pahina',
'tooltip-ca-unprotect' => 'Magribay nin proteksyon kaining pahina',
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
'tooltip-t-upload' => 'Ikarga an mga sagunson',
'tooltip-t-specialpages' => 'Lista kan gabos na mga espesyal na pahina',
'tooltip-t-print' => 'Naipiprint na bersyon kaining pahina',
'tooltip-t-permalink' => 'Permanenteng takod sa bersyon kaining páhina',
'tooltip-ca-nstab-main' => 'Hilingón an pahina nin laog',
'tooltip-ca-nstab-user' => 'Hilingón an pahina nin paragamit',
'tooltip-ca-nstab-media' => "Hilingón an pahina kan ''media''",
'tooltip-ca-nstab-special' => 'Pahinang espesyal ini, dai mo ini pwedeng hirahón',
'tooltip-ca-nstab-project' => 'Hilingón an pahina kan proyekto',
'tooltip-ca-nstab-image' => 'Hilnga an pahina kan sagunson',
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
'tooltip-watchlistedit-normal-submit' => 'Haleon an mga titulo',
'tooltip-watchlistedit-raw-submit' => 'Magdugang kan bantay-listahan',
'tooltip-recreate' => 'Gibohon giraray an páhina maski na naparâ na ini',
'tooltip-upload' => 'Pônan an pagkarga',
'tooltip-rollback' => '"Balikon" an mga pinagbagong pagliliwat sa pahinang ini kan pinakahuring kontributor sa paagi nin sarong klik',
'tooltip-undo' => '"Gibohang ibalik" an mga pinagbagong pagliliwat asin bukasi an porma nin pagliliwat sa modong patanaw. Ini minatugot na magdadagdag nin rason sa sumaryo.',
'tooltip-preferences-save' => 'Itagama an mga kagustuhan',
'tooltip-summary' => 'Magkaag nin sarong halipot na sumaryo',

# Stylesheets
'common.css' => '/** an CSS na pigbugtak digdi maiaaplikar sa gabos na mga skin */',
'monobook.css' => '/* an CSS na pigbugtak digdi makakaapektar sa mga parágamit kan Monobook skin */',

# Scripts
'common.js' => '/* Arin man na JavaScript digdi maikakarga para sa gabos na mga parágamit sa kada karga kan páhina. */',
'monobook.js' => '/* Deprecado; gamiton an [[MediaWiki:common.js]] */',

# Metadata
'notacceptable' => "Dai pwedeng magtao nin datos an ''wiki server'' sa ''format'' na pwedeng basahon kan kompyuter mo.",

# Attribution
'anonymous' => 'Bako-bistadong {{PLURAL:$1|paragamit|mga paragamit}} kan {{SITENAME}}',
'siteuser' => 'Paragamit kan {{SITENAME}} na si $1',
'anonuser' => '{{SITENAME}} bako-bistadong paragamit $1',
'lastmodifiedatby' => 'Ining páhina huring binago sa $2, $1 ni $3.',
'othercontribs' => 'Binase ini sa trabaho ni $1.',
'others' => 'iba pa',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|paragamit|mga paragamit}} $1',
'anonusers' => '{{SITENAME}} bako-bistadong {{PLURAL:$2|paragamit|mga paragamit}} $1',
'creditspage' => 'Mga krédito nin páhina',
'nocredits' => 'Mayong talastas kan kredito para sa ining pahina.',

# Spam protection
'spamprotectiontitle' => "Proteksyon kan ''spam filter''",
'spamprotectiontext' => 'An teksto na saimong kinakaipong ipagtagama pinagbarahan kan saraan nin spam.
Ini hurot na pinagkausa nin sarong sugpunan pasiring sa sarong pinagbawal na panluwas na sityo.',
'spamprotectionmatch' => "An minasunod na teksto iyo an nagbukas kan ''spam filter'' mi: $1",
'spambot_username' => 'paglimpya nin spam sa MediaWiki',
'spam_reverting' => 'Mabalik sa huring bersion na mayong takod sa $1',
'spam_blanking' => 'An gabos na mga pahirá na may takod sa $1, pigblablanko',
'spam_deleting' => 'An gabos na mga rebisyon na igwang mga kasugpunan sa $1, pinupura',

# Info page
'pageinfo-title' => 'Impormasyon para sa "$1"',
'pageinfo-not-current' => 'Sori, imposible baya na maitao ining impormasyon para sa lumaon nang mga rebisyon.',
'pageinfo-header-basic' => 'Panuntungang impormasyon',
'pageinfo-header-edits' => 'Pagliwat na historiya',
'pageinfo-header-restrictions' => 'Pampahinang proteksyon',
'pageinfo-header-properties' => 'Pampahinang propriyedades',
'pageinfo-display-title' => 'Titulo nin patanaw',
'pageinfo-default-sort' => 'Panugmad na susi nin salansan',
'pageinfo-length' => 'Kalabaan kan pahina (yaon sa mga bayta)',
'pageinfo-article-id' => 'ID kan pahina',
'pageinfo-robot-policy' => 'Estado kan makinang parahanap',
'pageinfo-robot-index' => 'Maihuhukdo',
'pageinfo-robot-noindex' => 'Dae maihuhukdo',
'pageinfo-views' => 'Numero kan mga patanaw',
'pageinfo-watchers' => 'Numero kan parabantay nin pahina',
'pageinfo-redirects-name' => 'Maipalikwat pasiring sa pahina ini',
'pageinfo-subpages-name' => 'Mga sub-pahina kaining pahina',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|panlikwat|mga panlikwat}}; $3 {{PLURAL:$3|bakong panlikwat|bakong mga panlikwat}})',
'pageinfo-firstuser' => 'Paramukna nin pahina',
'pageinfo-firsttime' => 'Petsa kan pagmukna nin pahina',
'pageinfo-lastuser' => 'Pinakahuring paraliwat',
'pageinfo-lasttime' => 'Petsa kan pinakahuring pagliwat',
'pageinfo-edits' => 'Kabilogan na bilang kan mga pagliwat',
'pageinfo-authors' => 'Kabilogan na bilang kan pinagpalaen na mga awtor',
'pageinfo-recent-edits' => 'Bilang kan dae pa sana nahahaloy na mga pagliliwat (sa laog kan nakaaging $1)',
'pageinfo-recent-authors' => 'Bilang kan dae pa sana nahahaloy na pinagpalaen na mga awtor',
'pageinfo-magic-words' => 'Mahiko {{PLURAL:$1|taramon|mga taramon}} ($1)',
'pageinfo-hidden-categories' => 'Itinago na {{PLURAL:$1|kategorya|mga kategorya}} ($1)',
'pageinfo-templates' => 'Kabaling pinagbalyo na {{PLURAL:$1|panguyog|mga panguyog}} ($1)',

# Skin names
'skinname-standard' => 'Klasiko',
'skinname-simple' => 'Simple',
'skinname-modern' => 'Bago',

# Patrolling
'markaspatrolleddiff' => 'Markahan bilang pigpapatrolya',
'markaspatrolledtext' => 'Markahan iníng pahina na pigpapatrolya',
'markedaspatrolled' => 'Minarkahan na pigpapatrolya',
'markedaspatrolledtext' => 'An pinagpiling rebisyon kan [[:$1]] pinagmarkahan bilang patrolyado.',
'rcpatroldisabled' => 'Pigpopogólan an mga Pagpatrolya kan mga Nakakaági pa sanáng Pagbabàgo',
'rcpatroldisabledtext' => 'Pigpopogólan ngùna an Pagpatrolya kan mga Nakakaági pa sanáng Pagbabàgo.',
'markedaspatrollederror' => 'Dai mamamarkahan bilang pigpapatrolya',
'markedaspatrollederrortext' => 'Kaipúhan mong magpilì nin pagpakarháy na mamarkahan bilang pigpapatrolya.',
'markedaspatrollederror-noautopatrol' => 'Daí ka pigtotogótan na markahan an sadíri mong pababàgo bilang pigpapatrolya.',

# Patrol log
'patrol-log-page' => 'Laóg kan Pigpapatrolya',
'patrol-log-header' => 'Ini an sarong talaan kan patrolyadong mga rebisyon.',
'log-show-hide-patrol' => '$1 talaan sa patrolya',

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
'previousdiff' => '← Lumaong pagliliwat',
'nextdiff' => 'Baguhong pagliliwat →',

# Media information
'mediawarning' => "'''Patanid tabi''': Ining tipo nin sagunson mapuwedeng may laog nin malisyosong koda.
Sa pagpapa-andar kaini, an saimong sistema mapupuwedeng makompromiso.",
'imagemaxsize' => "Limit sa sukol kan imahe:<br />''(para sa deskripsyon kan mga pahina nin sagunson)''",
'thumbsize' => 'Sokol nin retratito:',
'widthheightpage' => '$1 x $2, $3 {{PLURAL:$3|pahina|mga pahina}}',
'file-info' => "sokol kan ''file'': $1, tipo nin MIME: $2",
'file-info-size' => "$1 × $2 na pixel, sokol kan ''file'': $3, tipo nin MIME: $4",
'file-info-size-pages' => '$1 × $2 piksel, sukol kan sagunson: $3, MIME na tipo: $4, $5 {{PLURAL:$5|pahina|mga pahina}}',
'file-nohires' => 'Mayong mas halangkáw na resolusyon.',
'svg-long-desc' => 'file na SVG, haros $1 × $2 pixels, sokol kan file: $3',
'svg-long-desc-animated' => 'Animatadong SVG na sagunson, nangangaranang $1 x $2 piksel, kadakulaan nin sagunson: $3',
'show-big-image' => 'Todong resolusyon',
'show-big-image-preview' => 'Sukol kaining patanaw: $1.',
'show-big-image-other' => 'Ibang {{PLURAL:$2|resolusyon|mga resoluyon}}: $1.',
'show-big-image-size' => '$1 × $2 piksel',
'file-info-gif-looped' => 'pinag-otro',
'file-info-gif-frames' => '$1 {{PLURAL:$1|prema|mga prema}}',
'file-info-png-looped' => 'inotrohan',
'file-info-png-repeat' => 'pinagkawat $1 {{PLURAL:$1|bes|beses}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|prema|mga prema}}',
'file-no-thumb-animation' => "'''Giromdomon: Nin huli sa teknikal na mga limitasyon, an mga retrato kaining sagunson bakong animatado.'''",
'file-no-thumb-animation-gif' => "'''Giromdomon: Nin huli sa teknikal na mga limitasyon, an mga retrato na GIF na mga imahe na igwang halangkawon na resolusyon na siring kaini dae animatado.'''",

# Special:NewFiles
'newimages' => 'Galeria nin mga bàgong file',
'imagelisttext' => "Mahihiling sa baba an lista nin mga  '''$1''' {{PLURAL:$1|file|files}} na linain $2.",
'newimages-summary' => 'Ining espesyal na pahina minaphiling kan huring pinagkargang mga sagunson.',
'newimages-legend' => 'An saraan',
'newimages-label' => 'Ngaran nin sagunson (o sarong parte kaini):',
'showhidebots' => '($1 na bots)',
'noimages' => 'Mayong mahihilíng.',
'ilsubmit' => 'Hanápon',
'bydate' => 'sa petsa',
'sp-newimages-showfrom' => 'Ipahiling an baguhon na mga sagunson na nagpopoon gikan sa oras na $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 segundo|$1 segundos}}',
'minutes' => '{{PLURAL:$1|$1 minuto|$1 minutos}}',
'hours' => '{{PLURAL:$1|$1 oras|$1 oras}}',
'days' => '{{PLURAL:$1|$1 aldaw|$1 mga aldaw}}',
'ago' => '$1 nakaagi',

# Bad image list
'bad_image_list' => 'An pormat iyo ining minasunod:

Listahan kan mga aytem sana (mga linyang nagpopoon sa *) an mga pinagkokonsidera.
An enot na kasugponan sa sarong linya kaipuhan na sarong sugpon pasiringon sa maraot na sagunson.
An arinman na nagsurunod na mga kasugponan sa kaparehong linya an mga pinagkokonsidera na magin mga palaen, i.e. mga pahina na kun saen an sagunson mapuwedeng mangyari sa laog kan linya.',

# Metadata
'metadata' => 'Metadatos',
'metadata-help' => 'Ining sagunson may laog na kadagdagang impormasyon, puwedeng pinagdagdag gikan sa kamerang digital o tagakopyang ginamit sa pagmukna o pagpasadit kaini.
Kun an sagunson pinagbago gikan sa orihinal kaining estado, an ibang mga detalye mapuwedeng dae bilog na minapahiling kan pinagbagong sagunson.',
'metadata-expand' => 'Ipahilíng an gabós na detalye',
'metadata-collapse' => 'Itagò an gabós na detalye',
'metadata-fields' => 'Mga kinaagan kan imaheng metadata na nakalista sa mensaheng ipinagdadagdag sa pahina kan patanaw nin imahe kunsoaring na an lamesa kan metadata pinagpasadit.
An mga iba pagtatagoon sa paagi nin pirmehan.
* gibo
* modelo
* petsaorasorihinal
* kinaluwasangoras
* fnumero
* isobilismarka
* pokalkalawigan
* artista
* copyright
* imahedeskripsyon
* gpspabalagbag
* gpspalaba
* gpspalangkaw',

# EXIF tags
'exif-imagewidth' => 'Lakbáng',
'exif-imagelength' => 'Langkáw',
'exif-bitspersample' => 'Panaradit kada komponente',
'exif-compression' => 'Eskima sa kompresyon',
'exif-photometricinterpretation' => 'Komposisyon sa piksel',
'exif-orientation' => 'Oryentasyon',
'exif-samplesperpixel' => 'Numero kan mga komponente',
'exif-planarconfiguration' => 'Kahusayan kan datos',
'exif-ycbcrsubsampling' => 'Pan-irarom na sampol na rasyo kan Y sagkod C',
'exif-ycbcrpositioning' => 'Y asin C na pagpoposisyon',
'exif-xresolution' => 'Pahigdang resolusyon',
'exif-yresolution' => 'Patindog na resolusyon',
'exif-stripoffsets' => 'Lokasyon kan datos nin imahe',
'exif-rowsperstrip' => 'Numero kan mga row sa kada ginupit',
'exif-stripbytecounts' => 'Panadakol sa kada kompresadong ginupit',
'exif-jpeginterchangeformat' => 'Ipagpantay sa JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Panadakol kan datos sa JPEG',
'exif-whitepoint' => 'Puti na puntong kromatiko',
'exif-primarychromaticities' => 'Kromatisidad kan mga primarisidad',
'exif-ycbcrcoefficients' => 'Kulay kan espasyong transpormasyon sa matrisang mga koepisiyente',
'exif-referenceblackwhite' => 'Padis kan itom asin puting bilang sa reperensiya',
'exif-datetime' => 'Pansagunsong pagbabago sa petsa asin oras',
'exif-imagedescription' => 'Titulo kan retrato',
'exif-make' => 'Tagagibo nin kamera',
'exif-model' => 'Modelo nin kamera',
'exif-software' => 'Panuklob na ginamit',
'exif-artist' => 'Kagsúrat',
'exif-copyright' => 'Kagkapot nin karapatan sa panurat',
'exif-exifversion' => 'Exif bersyon',
'exif-flashpixversion' => 'Suportadong Flashpix na bersyon',
'exif-colorspace' => 'Espasyong kulay',
'exif-componentsconfiguration' => 'Panabot kan lambang komponente',
'exif-compressedbitsperpixel' => 'Moda nin kompresyon sa imahe',
'exif-pixelydimension' => 'Lakbang kan imahe',
'exif-pixelxdimension' => 'Langkaw kan imahe',
'exif-usercomment' => 'Mga komento kan paragamít',
'exif-relatedsoundfile' => 'Kaakibay kan sagunson na pandangog',
'exif-datetimeoriginal' => 'Petsa asin oras kan henerasyon nin datos',
'exif-datetimedigitized' => 'Petsa asin oras kan paghimong dihital',
'exif-subsectime' => 'PetsaOras na mga subsegundo',
'exif-subsectimeoriginal' => 'PetsaOrasOrihinal na mga subsegundo',
'exif-subsectimedigitized' => 'PetaOrasDihitalisadong mga subsegundo',
'exif-exposuretime' => 'Kahuyangang oras',
'exif-exposuretime-format' => '$1 seg ($2)',
'exif-fnumber' => 'F numero',
'exif-exposureprogram' => 'Kahuyangang Programa',
'exif-spectralsensitivity' => 'Espektral na sensitibidad',
'exif-isospeedratings' => 'ISO grado nin rikas',
'exif-shutterspeedvalue' => 'APEX rikas kan kimlat',
'exif-aperturevalue' => 'APEX na Kamuklatan',
'exif-brightnessvalue' => 'APEX na kaliwanagan',
'exif-exposurebiasvalue' => 'APEX na kahuyangan nin kalaenan',
'exif-maxaperturevalue' => 'Pinakahalangkaw na kamuklatang pandaga',
'exif-subjectdistance' => 'Subhetong distansiya',
'exif-meteringmode' => 'Moda nin pagmemetro',
'exif-lightsource' => 'Ginikánan nin liwánag',
'exif-flash' => 'Kikilát',
'exif-focallength' => 'Laba sa turuhok kan lente',
'exif-subjectarea' => 'Lugar kan subheto',
'exif-flashenergy' => 'Kakusogan nin kikilát',
'exif-focalplanexresolution' => 'Sayon nin turuhok kan X na resolusyon',
'exif-focalplaneyresolution' => 'Sayon nin turuhok kan Y na resolusyon',
'exif-focalplaneresolutionunit' => 'Sayon nin turuhok sa resolusyon kan yunit',
'exif-subjectlocation' => 'Lokasyon kan subheto',
'exif-exposureindex' => 'Hukdo nin kahuyangan',
'exif-sensingmethod' => 'Metodo nin paghihimate',
'exif-filesource' => 'Ginikánan nin dokumento',
'exif-scenetype' => 'Tipo nin eksena',
'exif-customrendered' => 'Kapakarahayang proseso kan imahe',
'exif-exposuremode' => 'Moda nin Kahuyangan',
'exif-whitebalance' => 'Kapantayan nin kaputian',
'exif-digitalzoomratio' => 'Dihital na rata nin karanihan',
'exif-focallengthin35mmfilm' => 'Sayon nin kalabaan sa 35 mm na pelikula',
'exif-scenecapturetype' => 'Tipo kan pagdakop nin eksena',
'exif-gaincontrol' => 'Kontrol na pan-eksena',
'exif-contrast' => 'Kontraste',
'exif-saturation' => 'Kababadan',
'exif-sharpness' => 'Kahaisan',
'exif-devicesettingdescription' => 'Mga deskripsyon kan panuytoy nin aparato',
'exif-subjectdistancerange' => 'Hikwas kan distansiya nin subheto',
'exif-imageuniqueid' => 'Unikong ID kan ladawan',
'exif-gpsversionid' => 'Bersyon kan GPS tag',
'exif-gpslatituderef' => 'Hiraga o Timog na kahalaghagan',
'exif-gpslatitude' => 'Halaghag',
'exif-gpslongituderef' => 'Sirangan o Sulnupan na kalabaghan',
'exif-gpslongitude' => 'Kalabaghan',
'exif-gpsaltituderef' => 'Reperensiya nin kalangkawan',
'exif-gpsaltitude' => 'Kahalaghagan',
'exif-gpstimestamp' => 'GPS na oras (atomikong orasan)',
'exif-gpssatellites' => 'Mga satelayt na pinaggagamit para sa kasukolan',
'exif-gpsstatus' => 'Estado kan resibidor',
'exif-gpsmeasuremode' => 'Moda nin kasukolan',
'exif-gpsdop' => 'Katusayang kasukolan',
'exif-gpsspeedref' => 'Yunit nin karikasan',
'exif-gpsspeed' => 'Karikasan kan GPS na resibidor',
'exif-gpstrackref' => 'Reperensiya para sa direksyon nin kahiroan',
'exif-gpstrack' => 'Direksyon kan paghirô',
'exif-gpsimgdirectionref' => 'Reperensiya para sa direksyon kan imahe',
'exif-gpsimgdirection' => 'Direksyon kan ladáwan',
'exif-gpsmapdatum' => 'Heodetikong surbey an datos na pinaggamit',
'exif-gpsdestlatituderef' => 'Reperensiya para sa panlatitud na destinasyon',
'exif-gpsdestlatitude' => 'Panlatitud na destinasyon',
'exif-gpsdestlongituderef' => 'Reperensiya para sa panlongitud na destination',
'exif-gpsdestlongitude' => 'Panlongitud na destinasyon',
'exif-gpsdestbearingref' => 'Reperensiya para sa pandireksyon na destinasyon',
'exif-gpsdestbearing' => 'Pandireksyon na destinasyon',
'exif-gpsdestdistanceref' => 'Reperensiya para sa pandistansiya na destinasyon',
'exif-gpsdestdistance' => 'Distansya sa destinasyon',
'exif-gpsprocessingmethod' => 'Ngaran kan GPS na pamprosesong kapaagihan',
'exif-gpsareainformation' => 'Ngaran nin lugar kan GPS',
'exif-gpsdatestamp' => 'Petsa kan GPS',
'exif-gpsdifferential' => 'Diperensiyal na koreksyon kan GPS',
'exif-jpegfilecomment' => 'Komentaryo sa JPEG na sagunson',
'exif-keywords' => 'Mga Susing taramon',
'exif-worldregioncreated' => 'Rehiyon kan kinaban na pinagkuanan kan litrato',
'exif-countrycreated' => 'Nasyon na pinagkuanan kan litrato',
'exif-countrycodecreated' => 'Koda para sa nasyon na pinagkuanan kan litrato',
'exif-provinceorstatecreated' => 'Probinsiya o estado na pinagkuanan kan litratro',
'exif-citycreated' => 'Siyudad na pinagkuanan kan litrato',
'exif-sublocationcreated' => 'Sublokasyon kan siyudad na pinagkuanan kan litrato',
'exif-worldregiondest' => 'Rehiyon kan kinaban pinapahiling',
'exif-countrydest' => 'Nasyon ipinapahiling',
'exif-countrycodedest' => 'Koda para sa nasyon na ipinahiling',
'exif-provinceorstatedest' => 'Probinsiya o estadong ipinapahiling',
'exif-citydest' => 'Siyudad ipinahiling',
'exif-sublocationdest' => '
Sublokas kan siyudad na ipinahiling',
'exif-objectname' => 'Halipot na titulo',
'exif-specialinstructions' => 'Espesyal na mga instruksyon',
'exif-headline' => 'Pamayuhang-linya',
'exif-credit' => 'Pautang/Tagapagtao',
'exif-source' => 'Pinaggikanan',
'exif-editstatus' => 'Editoryal na kamugtakan kan imahe',
'exif-urgency' => 'Kahidalian',
'exif-fixtureidentifier' => 'Ngaran kan agwerto',
'exif-locationdest' => 'Lokasyon pinagbiklad',
'exif-locationdestcode' => 'Koda kan lokasyon pinagbiklad',
'exif-objectcycle' => 'Oras kan aldaw na an midya pinagtuyuhan',
'exif-contact' => 'Impormasyon kan kontak',
'exif-writer' => 'Parasurat',
'exif-languagecode' => 'Lengguwahe',
'exif-iimversion' => 'IIM bersyon',
'exif-iimcategory' => 'Kategoriya',
'exif-iimsupplementalcategory' => 'Pansuplementong mga kategoriya',
'exif-datetimeexpires' => 'Dae gamiton pagkatapos',
'exif-datetimereleased' => 'Pinaluwas kan',
'exif-originaltransmissionref' => 'Orihinal na transmisyon sa koda nin lokasyon',
'exif-identifier' => 'Tagapagpamidbid',
'exif-lens' => 'Lenteng pinaggamit',
'exif-serialnumber' => 'Seryal na numero kan kamera',
'exif-cameraownername' => 'Kagsadire kan kamera',
'exif-label' => 'Tatak',
'exif-datetimemetadata' => 'Petsa kan metadata na huring pinagbago',
'exif-nickname' => 'Impormal na ngaran kan imahe',
'exif-rating' => 'Kamarkahan (luwas sa lima)',
'exif-rightscertificate' => 'Sertipiko kan manihamento nin mga karapatan',
'exif-copyrighted' => 'Estado sa karapatan nin panurat',
'exif-copyrightowner' => 'Kagsadire sa karapatan nin panurat',
'exif-usageterms' => 'Mga Terminong Ginagamit',
'exif-webstatement' => 'Online na testamento sa karapatan nin panurat',
'exif-originaldocumentid' => 'Unikong ID kan orihinal na dokumento',
'exif-licenseurl' => 'Kilyawan para sa lisensiya nin karapatan sa panurat',
'exif-morepermissionsurl' => 'Alternatibong impormasyon sa paglilisensiya',
'exif-attributionurl' => 'Kunsoarin gagamiton otro ining gibo, pakisugpon sa',
'exif-preferredattributionname' => 'Kunsoarin gagamiton otro ining gibo, sabihon tabi an kredito',
'exif-pngfilecomment' => 'Komentaryo sa PNG na sagunson',
'exif-disclaimer' => 'Pagpapasimuya',
'exif-contentwarning' => 'Patanid kan laog',
'exif-giffilecomment' => 'Komentary sa GIF na sagunson',
'exif-intellectualgenre' => 'Tipo kan Aytem',
'exif-subjectnewscode' => 'Koda kan subheto',
'exif-scenecode' => 'IPTC pan-eksenang koda',
'exif-event' => 'Panyayaring pinagbiklad',
'exif-organisationinimage' => 'Organisasyon pinagbiklad',
'exif-personinimage' => 'Persona pinagbiklad',
'exif-originalimageheight' => 'Langkaw kan imahe bago ini pinagkrap',
'exif-originalimagewidth' => 'Lakbang kan imahe bago ini pinagkrap',

# EXIF attributes
'exif-compression-1' => 'Pinaghalugaan',
'exif-compression-2' => 'CCITT Grupong 3 1-Dimensyonal na pagbabago ni Huffman nagdadalagan nin halawig na pag-enkod',
'exif-compression-3' => 'CCITT Grupong 3 pinag-enkod sa fax',
'exif-compression-4' => 'CCITT Grupong 3 pinag-enkod sa fax',

'exif-copyrighted-true' => 'Nakatagamang karapatan sa panurat',
'exif-copyrighted-false' => 'Pampublikong Kinasakupan',

'exif-unknowndate' => 'Daí aram an petsa',

'exif-orientation-1' => 'Normalon',
'exif-orientation-2' => 'Pahigdang pinagbuklat',
'exif-orientation-3' => 'Pinag-ikot nin 180 grado',
'exif-orientation-4' => 'Patindog na pinagbuklot',
'exif-orientation-5' => 'Pinag-ikot nin 90 grade asin patindog na pinagbuklat',
'exif-orientation-6' => 'Pinag-ikot nin 90 grado sa CCW',
'exif-orientation-7' => 'Pinag-ikot nin 90 grade CW asin patindog na pinagbuklat',
'exif-orientation-8' => 'Pinag-ikot nin 90 grado sa CW',

'exif-planarconfiguration-1' => 'Patingi na pormat',
'exif-planarconfiguration-2' => 'Planar na pormat',

'exif-colorspace-65535' => 'Bakong kalibrado',

'exif-componentsconfiguration-0' => 'mayô man ini',

'exif-exposureprogram-0' => 'Mayong pinagkahulugan',
'exif-exposureprogram-1' => 'Manwal',
'exif-exposureprogram-2' => 'Normal na programa',
'exif-exposureprogram-3' => 'Apertoryong Prayoridad',
'exif-exposureprogram-4' => 'Panseradong Prayoridad',
'exif-exposureprogram-5' => 'Pangmuknaon na programa (minapabor sa hararomon na kinasakupan)',
'exif-exposureprogram-6' => 'Pamprogramang Aksyon (minauyon sa mabilison pampundong buklos)',
'exif-exposureprogram-7' => 'Modong patindog (para haranihang mga litrato na igwang kalikudan na luwas sa pokus)',
'exif-exposureprogram-8' => 'Modong pahigda ( para sa pahigdang mga litrato na igwang kalikudan na nakapokus)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0' => 'Dai aram',
'exif-meteringmode-1' => 'Kagtahawan',
'exif-meteringmode-2' => 'Sentrong pinaggabatan na kagtahawan',
'exif-meteringmode-3' => 'Kaghilngan',
'exif-meteringmode-4' => 'Pandakol na kaghilngan',
'exif-meteringmode-5' => 'Pangarugan',
'exif-meteringmode-6' => 'Parsyal',
'exif-meteringmode-255' => 'Iba pa',

'exif-lightsource-0' => 'Bakong bistado',
'exif-lightsource-1' => 'Maliwanagong aldaw',
'exif-lightsource-2' => 'Kalaadan',
'exif-lightsource-3' => 'Tungsten (mainitong liwanag)',
'exif-lightsource-4' => 'Kitkilát',
'exif-lightsource-9' => 'Magayón na panahón',
'exif-lightsource-10' => 'Mapanginurong panahon',
'exif-lightsource-11' => 'Lindong',
'exif-lightsource-12' => 'Pan-agang kalaadan (D 5700 - 7100K)',
'exif-lightsource-13' => 'Pan-agang mapution na kalaadan (N 4600 - 5400K)',
'exif-lightsource-14' => 'Malipotong mapution na kalaadan (W 3900 - 4500K)',
'exif-lightsource-15' => 'Maputiong kalaadan (WW 3200 - 3700K)',
'exif-lightsource-17' => 'Estandarteng Laad A',
'exif-lightsource-18' => 'Estandarteng Laad B',
'exif-lightsource-19' => 'Estandarteng Laad C',
'exif-lightsource-24' => 'ISO estudyong tungsten',
'exif-lightsource-255' => 'Mga ibang ginikanan nin ilaw',

# Flash modes
'exif-flash-fired-0' => 'An flash dae nagsindi',
'exif-flash-fired-1' => 'An flash nagsindi',
'exif-flash-return-0' => 'mayong estrobo sa pambalik na punksyon sa deteksyon',
'exif-flash-return-2' => 'estrobong pambalik liwanag bakong detektado',
'exif-flash-return-3' => 'estrobong pambalik na liwanag detektado',
'exif-flash-mode-1' => 'kompulsaryong flash nagsindi',
'exif-flash-mode-2' => 'kompulsaryong flash pinupugulan',
'exif-flash-mode-3' => 'automatikong modo',
'exif-flash-function-1' => 'Mayong naggaganang flash',
'exif-flash-redeye-1' => 'mapulang-mata modong pambawas',

'exif-focalplaneresolutionunit-2' => 'pulgada',

'exif-sensingmethod-1' => 'Mayong pakahulugan',
'exif-sensingmethod-2' => 'Tagahimate kan solong pinyero nin pankolor sa erya',
'exif-sensingmethod-3' => 'Tagahimate kan panduwahang pinyero nin pankolor sa erya',
'exif-sensingmethod-4' => 'Tagahimate kan pantolohang pinyero nin pankolor sa erya',
'exif-sensingmethod-5' => 'Tagahimate kan pasurunod na pankolor sa erya',
'exif-sensingmethod-7' => 'Pantolohang linya na tagahimate',
'exif-sensingmethod-8' => 'Pankolor na pasurunod kan panlinyang tagahimate',

'exif-filesource-3' => 'Nakauntok na kamerang digital',

'exif-scenetype-1' => 'Direktong naretratong ladawan',

'exif-customrendered-0' => 'Normal na proseso',
'exif-customrendered-1' => 'Pambagong proseso',

'exif-exposuremode-0' => 'Awto na pamburiyas',
'exif-exposuremode-1' => 'Manwal na pamburiyas',
'exif-exposuremode-2' => 'Awto na pankorda',

'exif-whitebalance-0' => 'Awto pambalanse kan puti',
'exif-whitebalance-1' => 'Manwal na pambalanse kan puti',

'exif-scenecapturetype-0' => 'Estandarte',
'exif-scenecapturetype-1' => 'Pahigda',
'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Eksenang banggi',

'exif-gaincontrol-0' => 'Mayo',
'exif-gaincontrol-1' => 'Hababaong pampalangkaw',
'exif-gaincontrol-2' => 'Paitaas na pampalangkaw',
'exif-gaincontrol-3' => 'Hababaong pampababa',
'exif-gaincontrol-4' => 'Paitaas na pampababa',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Malumoy',
'exif-contrast-2' => 'Matagas',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Hababaon na satyurasyon',
'exif-saturation-2' => 'Halangkawon na satyurasyon',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Malumoy',
'exif-sharpness-2' => 'Matagas',

'exif-subjectdistancerange-0' => 'Bakong bisto',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Haranihon pagtanaw',
'exif-subjectdistancerange-3' => 'Harayoong pagtanaw',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norteng kasalungaan',
'exif-gpslatitude-s' => 'Sur na kasalungaan',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Esteng kahalungaan',
'exif-gpslongitude-w' => 'Westeng kahalungaan',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metro|metros}} ibabaw sa kaabtangan nin dagat',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metro|metros}} ibaba sa kaabtangan nin dagat',

'exif-gpsstatus-a' => 'Kasukolan yaon sa progreso',
'exif-gpsstatus-v' => 'Kasukolan yaon sa panlaog na operabilidad',

'exif-gpsmeasuremode-2' => 'Duwahang dimensyon na kasukolan',
'exif-gpsmeasuremode-3' => 'Pantolohang dimensyon na kasukolan',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometros kada oras',
'exif-gpsspeed-m' => 'Milya kada oras',
'exif-gpsspeed-n' => 'kanukso',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'kilometros',
'exif-gpsdestdistance-m' => 'Milyas',
'exif-gpsdestdistance-n' => 'Milya nautikal',

'exif-gpsdop-excellent' => 'Ekselente ($1)',
'exif-gpsdop-good' => 'Marahayon ($1)',
'exif-gpsdop-moderate' => 'Moderato ($1)',
'exif-gpsdop-fair' => 'Marahay-rahay ($1)',
'exif-gpsdop-poor' => 'Maluyahon ($1)',

'exif-objectcycle-a' => 'Pan-aga sana',
'exif-objectcycle-p' => 'Panbanggi sana',
'exif-objectcycle-b' => 'Pareho sa pagka-aga asin pagkabanggi',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tunay na direksyon',
'exif-gpsdirection-m' => 'Direksyón nin batobalani',

'exif-ycbcrpositioning-1' => 'Pinagpatahaw',
'exif-ycbcrpositioning-2' => 'Katampad-sityo',

'exif-dc-contributor' => 'Mga Tagapag-ambag',
'exif-dc-coverage' => 'Espasyal o temporal tungkos kan midya',
'exif-dc-date' => 'Petsa (s)',
'exif-dc-publisher' => 'Publikador',
'exif-dc-relation' => 'Kaampad na midya',
'exif-dc-rights' => 'Mga karapatan',
'exif-dc-source' => 'Ginikanang midya',
'exif-dc-type' => 'Tipo kan midya',

'exif-rating-rejected' => 'Dinihado',

'exif-isospeedratings-overflow' => 'Halangkawon kesa 65535',

'exif-iimcategory-ace' => 'Mga arte, kultura asin kasalingayan',
'exif-iimcategory-clj' => 'Krimen asin ley',
'exif-iimcategory-dis' => 'Mga destroso asin aksidente',
'exif-iimcategory-fin' => 'Ekonomiya asin negosyo',
'exif-iimcategory-edu' => 'Edukasyon',
'exif-iimcategory-evn' => 'Kapalibutan',
'exif-iimcategory-hth' => 'Salud',
'exif-iimcategory-hum' => 'Pantawong interes',
'exif-iimcategory-lab' => 'Trabaho',
'exif-iimcategory-lif' => 'Estilo nin buhay asin libangan',
'exif-iimcategory-pol' => 'Mga Pulitika',
'exif-iimcategory-rel' => 'Relihiyon asin paniniwala',
'exif-iimcategory-sci' => 'Siyensiya asin teknolohiya',
'exif-iimcategory-soi' => 'Mga pansosyal na mga isyu',
'exif-iimcategory-spo' => 'Mga Pakawat',
'exif-iimcategory-war' => 'Giyera, iriwal asin daeng-kahingaloan',
'exif-iimcategory-wea' => 'Panahon',

'exif-urgency-normal' => 'Normalon ($1)',
'exif-urgency-low' => 'Hababaon ($1)',
'exif-urgency-high' => 'Halangkawon ($1)',
'exif-urgency-other' => 'Prayoridad na pakahulugan nin paragamit ($1)',

# External editor support
'edit-externally' => 'Hirahón an file gamit an panluwas na aplikasyon',
'edit-externally-help' => '(Hilngon an [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] para sa kadagdagang impormasyon)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'gabos',
'namespacesall' => 'gabós',
'monthsall' => 'gabos',
'limitall' => 'gabos',

# E-mail address confirmation
'confirmemail' => "Kompirmaron an ''e''-surat",
'confirmemail_noemail' => "Mayô kang pigkaag na marhay na ''e''-surat sa saimong [[Special:Preferences|mga kabôtan nin parágamit]].",
'confirmemail_text' => '{{SITENAME}} minakaipo na balidaron an saimong e-surat na adres bago mo gagamiton an mga estima kan e-surat.
Aktibaron an tipahan sa ibaba tanganing ipadara an pankumpirmang surat sa saimong adres.
An surat igwa nin sarong kasugpunan na kinaagan nin sarong koda;
ikarga an kasugpunan sa saimong kilyaw tanganing makumpirma na an saimong e-surat na adres balido.',
'confirmemail_pending' => "May pigpadara nang kompirmasyon sa ''e''-surat mo; kun kagigibo mo pa sana kan saimong ''account'', maghalat ka nin mga dikit na minutos bago ka maghagad giraray nin bâgong ''code''.",
'confirmemail_send' => 'Magpadará nin kompirmasyon',
'confirmemail_sent' => "Napadará na an kompirmasyon sa ''e''-surat.",
'confirmemail_oncreate' => "May pigpadara nang kompirmasyon sa saimong ''e''-surat.
Dai man kaipuhan ini para makalaog, pero kaipuhan mong itao ini bago
ka makagamit nin ''features'' na basado sa ''e''-surat sa wiki.",
'confirmemail_sendfailed' => '{{SITENAME}} dae nakapadara kan saimong pankumpirmang surat.
Pakihiling tabi sa saimong e-surat na adress para sa imbalidong mga karakter.

Paradarang surat pinagbalik: $1',
'confirmemail_invalid' => 'Salâ an kódigo nin konpirmasyon. Puede ser napasó na an kódigo.',
'confirmemail_needlogin' => "Kaipuhan tabi $1 ikompirmar an saimong ''e''-surat.",
'confirmemail_success' => 'An saimong e-surat na adres kumpirmado na.
Puwede ka na ngunyan [[Special:UserLogin|maglaog]] asin maogmang maggamit kan wiki.',
'confirmemail_loggedin' => "Nakompirmar na an saimong ''e''-surat.",
'confirmemail_error' => 'May nasalâ sa pagtagama kan saimong kompirmasyon.',
'confirmemail_subject' => "kompirmasyón {{SITENAME}} kan direksyón nin ''e''-surat",
'confirmemail_body' => 'Sarong tawo, mapuwedeng ika, gikan sa IP adres na $1,
nagrehistro nin sarong panindog "$2" na igwa kaining e-surat na adres sa {{SITENAME}}.

Tanganing kumpirmaron na ining panindog talagang pagsadire mo asin aktibaron an e-surat na mga estima sa {{SITENAME}}, bukasi tabi ining kasugpunan sa saimong kilyaw:

$3

Kun ika *dae* nagrehistro kan panindog, sunuda ining sugpon
tanganing kanselaron an e-surat na adres na pankumpirma:

$5

Ining pankumpirmang koda mapalso sa $4.',
'confirmemail_body_changed' => 'Sarong tawo, mapuwedeng ika, gikan sa IP adres na $1,
nagrehistro nin sarong panindog "$2" na igwa kaining e-surat na adres sa {{SITENAME}}.

Tanganing kumpirmaron na ining panindog talagang pagsadire mo asin aktibaron an e-surat na mga estima sa {{SITENAME}}, bukasi tabi ining kasugpunan sa saimong kilyaw:

$3

Kun an panindog *bakong* saimo, sunuda ining sugpon
tanganing kanselaron an e-surat na adres na pankumpirma:

$5

Ining pankumpirmang koda mapalso sa $4.',
'confirmemail_body_set' => 'Sarong tawo, mapuwedeng ika, gikan sa IP adres na $1,
nagrehistro nin sarong panindog "$2" na igwa kaining e-surat na adres sa {{SITENAME}}.

Tanganing kumpirmaron na ining panindog talagang pagsadire mo asin re-aktibaron an e-surat na mga estima sa {{SITENAME}}, bukasi tabi ining kasugpunan sa saimong kilyaw:

$3

Kun an panindog *bakong* saimo, sunuda ining sugpon
tanganing kanselaron an e-surat na adres na pankumpirma:

$5

Ining pankumpirmang koda mapalso sa $4.',
'confirmemail_invalidated' => 'An e-surat na adres na pankumpirma kanselado na',
'invalidateemail' => 'Kanselaron an e-surat na pankumpirmasyon',

# Scary transclusion
'scarytranscludedisabled' => '[Pigpopogolan an transcluding na Interwiki]',
'scarytranscludefailed' => '[Templatong panakdo nagpalya para sa $1]',
'scarytranscludetoolong' => '[An kilyawan grabe kahalaba]',

# Delete conflict
'deletedwhileediting' => "'''Patanid tabi''': Ining pahina pinagpura matapos na ika nagpoon na magliliwat!",
'confirmrecreate' => "Si [[User:$1|$1]] ([[User talk:$1|olay]]) pigparâ ining páhina pagkatapos mong magpoon kan paghira ta:
: ''$2''
Ikonpirmar tabi na talagang gusto mong gibohon giraray ining pahina.",
'confirmrecreate-noreason' => 'Paragamit [[User:$1|$1]] ([[User talk:$1|Olay]]) an nagpura kaining pahina matapos na ika nagpoon na magliliwat. Pakikumpirma tabi na ika boot na muknaon otro ining pahina.',
'recreate' => 'Gibohón giraray',

# action=purge
'confirm_purge_button' => 'Sige',
'confirm-purge-top' => 'Halîon an an aliho kaining páhina?',
'confirm-purge-bottom' => 'Pagpupurga nin sarong pahina minalinig kan sarayan asin minapuwersa sa pinakahuring rebisyon na magtunga.',

# action=watch/unwatch
'confirm-watch-button' => 'OK tabi',
'confirm-watch-top' => 'Idadagdag ining pahina sa saimong bantay-listahan?',
'confirm-unwatch-button' => 'OK tabi',
'confirm-unwatch-top' => 'Haleon ining pahina gikan sa saimong bantay-listahan?',

# Multipage image navigation
'imgmultipageprev' => '← nakaaging pahina',
'imgmultipagenext' => 'sunod na pahina →',
'imgmultigo' => 'Dumanán!',
'imgmultigoto' => 'Magpasiring sa pahina $1',

# Table pager
'ascending_abbrev' => 'skt',
'descending_abbrev' => 'ba',
'table_pager_next' => 'Sunod na páhina',
'table_pager_prev' => 'Nakaaging páhina',
'table_pager_first' => 'Enot na páhina',
'table_pager_last' => 'Huring páhina',
'table_pager_limit' => 'Ipahiling an $1 na aytem kada páhina',
'table_pager_limit_label' => 'Mga aytem kada pahina:',
'table_pager_limit_submit' => 'Dumanán',
'table_pager_empty' => 'Mayong resulta',

# Auto-summaries
'autosumm-blank' => 'Pinagblangko an pahina',
'autosumm-replace' => "Pigriribayan an páhina nin '$1'",
'autoredircomment' => 'Piglilikay sa [[$1]]',
'autosumm-new' => 'Pinagmukna an pahina kaining "$1"',

# Live preview
'livepreview-loading' => 'Pigkakarga…',
'livepreview-ready' => 'Pigkakarga… Magpreparar!',
'livepreview-failed' => 'Dae nakapoon an direktong patânaw! Probaran tabî an patânaw na normal.',
'livepreview-error' => 'Dai nakakabit: $1 "$2". Hilingón tabî an normal na patânaw.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Mga pagliliwat na baguhon pa sana nin $1 {{PLURAL:$1|segundo|segundos}} puwedeng dae maipapahiling sa listahang ini.',
'lag-warn-high' => 'Nin huli sa halangkawon na kaabalahan sa serbidor kan datos-sarayan, mga pagliliwat na baguhon pa sana nin $1 {{PLURAL:$1|segundo|segundos}} puwedeng dae maipapahiling sa listahang ini.',

# Watchlist editor
'watchlistedit-numitems' => 'An saimong pigbabantayan igwang {{PLURAL:$1|1 titulo|$1 mga titulo}}, apwera kan mga páhina kan olay.',
'watchlistedit-noitems' => 'Mayong mga titulo an pigbabantayan mo.',
'watchlistedit-normal-title' => 'Hirahón an pigbabantayan',
'watchlistedit-normal-legend' => 'Halion an mga titulo sa pigbabantayan',
'watchlistedit-normal-explain' => 'Mga sa saimong bantay-listahan ipinapahiling sa ibaba.
Sa paghali nin sarong titutlo, -tsek an kahon kasunod kaini, asin i-klik an "{{int:Watchlistedit-normal-submit}}".
Puwede ka man na [[Special:EditWatchlist/raw|magliwat kan temporaryong listahan]].',
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

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|olay]])',

# Core parser functions
'unknown_extension_tag' => 'Bakong bistadong ekstensyon nin pagmarka "$1"',
'duplicate-defaultsort' => '\'\'\'Patanid tabi:\'\'\' An susing panugmad kan salansan na "$2" minasalimbaw sa dating susing panugmad kan salansan na "$1".',

# Special:Version
'version' => 'Bersyon',
'version-extensions' => 'Instaladong mga ekstensyon',
'version-specialpages' => 'Espesyal na mga pahina',
'version-parserhooks' => 'Mga pangawil kan parser',
'version-variables' => 'Mga kabalanggayahan',
'version-antispam' => 'Pan-spam na pangataman',
'version-skins' => 'Mga kublit',
'version-other' => 'An iba pa',
'version-mediahandlers' => 'Mga Midyang Tagakapot',
'version-hooks' => 'Mga pangawil',
'version-extension-functions' => 'Mga punksyon kan ekstensyon',
'version-parser-extensiontags' => 'Mga ekstensyong panmarka kan Parser',
'version-parser-function-hooks' => 'Mga panpunksyong pangawil kan Parser',
'version-hook-name' => 'Ngaran kan pangawil',
'version-hook-subscribedby' => 'Pinaghaguhot ni',
'version-version' => '(Bersyon na $1)',
'version-license' => 'Lisensiya',
'version-poweredby-credits' => "An wiking ini pinagpagana kan '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'mga iba pa',
'version-license-info' => 'An MediaWiki sarong libreng kasungatan; puwede mong ipanao ini asin/o baguhon ini sa irarom kan termino nin HNU (Heneral na Pampublikong Lisensiya) bilang publisado kan Free Software Foundation; maski sa arin na bersyon 2 kan lisensiya, o (saimong pansadireng pagpipilian) arinman na huring bersyon.

An MediaWiki ipinagpanao sa paglaom na ini magigin kapakinabangan, pero MAYO NIN ANUMAN NA WARANTIYA; mayo dawa ngani nin pinaghuhurot na warantiya kan MERKANTIBILIDAD o KAUYUGAN PARA SA SARONG PARTIKULAR NA KATUYUHAN. Hilngon an HNU (Heneral na Pampublikong Lisensiya) para sa kadagdagang mga detalye.

Ika dapat na nakapagresibe na kan [{{SERVER}}{{SCRIPTPATH}}/COPYING sarong kopya nin HNU Heneral na Pampublikong Lisensiya] na kaiba kaining programa; kun dae, surati an Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA o baya [//www.gnu.org/licenses/old-licenses/gpl-2.0.html online ining basahon].',
'version-software' => 'Instalyadong kasungatan',
'version-software-product' => 'Produkto',
'version-software-version' => 'Bersyon',
'version-entrypoints' => 'Puntong pan-entrada sa mga kilyawan',
'version-entrypoints-header-entrypoint' => 'Puntong pan-entrada',
'version-entrypoints-header-url' => 'Kilyawan',

# Special:FilePath
'filepath' => 'Pansagunsong agihan',
'filepath-page' => 'Sagunson:',
'filepath-submit' => 'Magduman',
'filepath-summary' => 'Ining espesyal na pahina minapabalik kan kumpletong agihan para sa sarong sagunson.
Mga imahe ipinapahiling sa bilog na resolusyon, an iba pang tipo nin mga sagunson pinagpapoon nin direkta kan saindang asosyadong programa.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Maghanap kan duplikadong mga sagunson',
'fileduplicatesearch-summary' => 'Maghanap kan duplikadong mga sagunson na pinagbasihan an mga kahalagahan nin hash.',
'fileduplicatesearch-legend' => 'Maghanap kan sarong duplikado',
'fileduplicatesearch-filename' => 'Ngaran nin sagunson:',
'fileduplicatesearch-submit' => 'Maghanap',
'fileduplicatesearch-info' => '$1 × $2 piksel<br />Sukol nin sagunson: $3<br />MIME tipo: $4',
'fileduplicatesearch-result-1' => 'An sagunson "$1" mayo nin kaparehong duplikasyon.',
'fileduplicatesearch-result-n' => 'An sagunson "$1" igwa nin {{PLURAL:$2|1 kaparehong duplikasyon|$2 mga kaparehong duplikasyon}}.',
'fileduplicatesearch-noresults' => 'Mayong sagunson na pinagngaranan na "$1" an nanagboan.',

# Special:SpecialPages
'specialpages' => 'Mga espesyal na pahina',
'specialpages-note' => '----
* Normal espesyal na mga pahina.
* <span class="mw-specialpagerestricted">Restriktadong espesyal na mga pahina.</span>',
'specialpages-group-maintenance' => 'Mga talaan nin pagpangataman',
'specialpages-group-other' => 'Iba pang mga espesyal na pahina',
'specialpages-group-login' => 'Maglaog / magmukna nin panindog',
'specialpages-group-changes' => 'Nakakaági pa sanáng mga pagbàgo asín laóg',
'specialpages-group-media' => 'Mga talaan sa midya asin mga ikinarga',
'specialpages-group-users' => 'Mga paragamit asin mga karapatan',
'specialpages-group-highuse' => 'Halangkawong gamit na mga pahina',
'specialpages-group-pages' => 'Mga listahan kan mga pahina',
'specialpages-group-pagetools' => 'Mga kagamitan sa pahina',
'specialpages-group-wiki' => 'Wiking datos asin mga kagamitan',
'specialpages-group-redirects' => 'Panukdo sa espesyal na mga pahina',
'specialpages-group-spam' => 'Pan-spam na mga kagamitan',

# Special:BlankPage
'blankpage' => 'Blangkong pahina',
'intentionallyblankpage' => 'Pigtuyong blangko an pahinang ini',

# External image whitelist
'external_image_whitelist' => '#Bayai ining linya eksaktong siring man sana kaini<pre>
#Magkaag nin regular na mga panambitong parasa (idto sanang parte na minauyon sa tahaw kan //) na yaon sa ibaba
#An mga ini paglalangkapon kaiba an mga kilyawan kan panluwas (hotlinked) na mga imahe
#Idtong nagkaralangkap ipagpapahiling bilang mga imahe, o baya an sarong sugpon sana pasiring sa imahe an ipagpapahiling
#Mga linya na nagpopoon sa # pagtatrataron bilang mga komento
#Ini baya bakong sensitibo sa tipahan

#Ikaag an gabos na parasang regex sa ibabaw kaining linya. Bayai ining linya eksaktong siring man sana kaini.</pre>',

# Special:Tags
'tags' => 'Balidong mga marka nin kaliwatan',
'tag-filter' => '[[Special:Tags|Tag]] saraon:',
'tag-filter-submit' => 'Saraan',
'tags-title' => 'Mga marka',
'tags-intro' => 'Ining pahina minalista kan mga marka na an kasungatan mapuwedeng maimarka an pagliwat kaini, asin an saindang mga kahulugan.',
'tags-tag' => 'Ngarang panmarka',
'tags-display-header' => 'Kinaluwasan sa listahan nin kaliwatan',
'tags-description-header' => 'Bilog na deskripsyon nin kahulugan',
'tags-hitcount-header' => 'Pinagmarkahan na mga kaliwatan',
'tags-edit' => 'liwatón',
'tags-hitcount' => '$1 {{PLURAL:$1|kaliwatan|mga kaliwatan}}',

# Special:ComparePages
'comparepages' => 'Ikumpara an mga pahina',
'compare-selector' => 'Ikumpara an mga rebisyon nin pahina',
'compare-page1' => 'Pahina 1',
'compare-page2' => 'Pahina 2',
'compare-rev1' => 'Rebisyon 1',
'compare-rev2' => 'Rebisyon 2',
'compare-submit' => 'Ikumpara',
'compare-invalid-title' => 'An titulo na saimong pinagsambit sarong imbalido.',
'compare-title-not-exists' => 'An titulo na saimong pinagsambit bakong eksistido.',
'compare-revision-not-exists' => 'An rebisyon na saimong pinagsambit bakong eksistido.',

# Database error messages
'dberr-header' => 'Ining wiki igwa nin sarong problema',
'dberr-problems' => 'Sori!
Ining sityo igwang naeksperiyensiyahan na mga kakundian sa teknikal.',
'dberr-again' => 'Prubaring maghalat tabi nin nagkapirang minutos asin otrohon ikarga.',
'dberr-info' => '(Dae makakontak sa serbidor kan datos-sarayan: $1)',
'dberr-usegoogle' => 'Ika puwedeng magprubar na maghanap sa Google nguna.',
'dberr-outofdate' => 'Pasinon mo tabi na an saindang mga indekso kan satuyang laog puwedeng luwas na sa petsa.',
'dberr-cachederror' => 'Ini sarong nakasaray na kopya kan pinaghahagad na pahina, asin puwedeng bakong angat sa petsa.',

# HTML forms
'htmlform-invalid-input' => 'Igwa nin mga problema an iba sa saimong pinaglaog',
'htmlform-select-badoption' => 'An halaga na saimong pinagsambit bakong saro sa balidong pagpipilian.',
'htmlform-int-invalid' => 'An halaga na saimong pinagsambit bako na sarong integer.',
'htmlform-float-invalid' => 'An halaga na saimong pinagsamit bako na sarong numero.',
'htmlform-int-toolow' => 'An halaga na saimong pinagsambit hababaon sa minimum na $1',
'htmlform-int-toohigh' => 'An halaga na saimong pinagsambit halangkawon sa maksimum na $1',
'htmlform-required' => 'Ining halaga pinaghahagad',
'htmlform-submit' => 'Sumitiron',
'htmlform-reset' => 'Dae idagos an mga kaliwatan',
'htmlform-selectorother-other' => 'An iba',

# SQLite database support
'sqlite-has-fts' => '$1 na igwang suporta sa kabilogang-teksto nin paghahanap',
'sqlite-no-fts' => '$1 na mayong suporta sa kabilogang-teksto nin paghahanap',

# New logging system
'logentry-delete-delete' => '$1 pinagpurang pahina $3',
'logentry-delete-restore' => '$1 pinagbalik na pahina $3',
'logentry-delete-event' => '$1 pinagliwat an bisibilidad kan {{PLURAL:$5|sarong talaan nin pangyayari|%5 talaan nin mga pangyayari}} kan $3: $4',
'logentry-delete-revision' => '$1 pinagliwat an bisibilidad kan {{PLURAL:$5|sarong rebisyon|$5 na mga rebisyon}} na yaon sa pahina $3: $4',
'logentry-delete-event-legacy' => '$1 pinagliwat an bisibilidad kan talaan nin mga pangyayari sa $3',
'logentry-delete-revision-legacy' => '$1 pinagliwat an bisibilidad kan mga rebisyon sa pahina $3',
'logentry-suppress-delete' => '$1 pinaglubog na pahina $3',
'logentry-suppress-event' => '$1 pasikretong pinagliwat an bisibilidad kan {{PLURAL:$5|talaan nin pangyayari|$5 mga talaan nin pangyayari}} sa $3: $4',
'logentry-suppress-revision' => '$1 pasikretong pinagliwat an bisibilidad kan {{PLURAL:$5|rebisyon|$5 mga rebisyon}} sa pahina $3: $4',
'logentry-suppress-event-legacy' => '$1 pasikretong pinagliwat an bisibilidad kan talaan nin mga pangyayari sa $3',
'logentry-suppress-revision-legacy' => '$1 pasikretong pinagliwat an bisibilidad kan mga rebisyon sa pahina $3',
'revdelete-content-hid' => 'an laog pinagtago',
'revdelete-summary-hid' => 'Sumaryo nin pagliwat itinago',
'revdelete-uname-hid' => 'pangaran nin paragamit itinago',
'revdelete-content-unhid' => 'an laog pinaghaya',
'revdelete-summary-unhid' => 'Sumaryo nin pagliwat ipinaghaya',
'revdelete-uname-unhid' => 'pangaran nin paragamit ipinaghaya',
'revdelete-restricted' => 'Pinag-aplikar an mga restriksyon sa mga administrador',
'revdelete-unrestricted' => 'Pinaghale an mga restriksyon para sa mga administrador',
'logentry-move-move' => '$1 pinagbalyo an pahina $3 paduman sa $4',
'logentry-move-move-noredirect' => 'S1 pinagbalyo an pahina $3 paduman sa $4 na mayong iwinalat na panlikwat',
'logentry-move-move_redir' => '$1 pinagbalyo an pahina $3 paduman sa $4 sa paagi kan panlikwat',
'logentry-move-move_redir-noredirect' => '$1 pinagbalyo an pahina $3 paduman sa $4 sa paagi kan panlikwat na mayong iwinawalat na sarong panlikwat',
'logentry-patrol-patrol' => '$1 pinagmarkahan an rebisyon $4 kan pahina $3 na patrolyado',
'logentry-patrol-patrol-auto' => '$1 awtomatikong pinagmarkahan an rebisyonn $4 kan pahina $3 na patrolyado',
'logentry-newusers-newusers' => 'An paragamit na panindog $1 pinagmukna na',
'logentry-newusers-create' => 'An paragamit na panindog $1 pinagmukna na',
'logentry-newusers-create2' => 'An paragamit na panindog $3 pinagmukna na ni $1',
'logentry-newusers-autocreate' => 'An paragamit na panindog $1 awtomatikong pinagmukna na',
'newuserlog-byemail' => 'an pasa-taramon ipinadara na sa paagi kan e-surat',

# Feedback
'feedback-bugornote' => 'Kun ika andam na iladawan an sarong teknikal na problema na igwang detalye tabi [$1 ipaaram an kuto].
Kun bako man, ika makakagamit nin sayon na porma sa ibaba. An saimong komento idudugang sa pahina "[$3 $2]", kaiba an saimong paragamit na ngaran.',
'feedback-subject' => 'Subheto',
'feedback-message' => 'An Mensahe:',
'feedback-cancel' => 'Kanselaron',
'feedback-submit' => 'Isumite an balik-simbag',
'feedback-adding' => 'Idugang an balik-simbag sa pahina...',
'feedback-error1' => 'Kasalaan: Bakong bistadong resulta gikan sa API',
'feedback-error2' => 'Kasalaan: An pagliwat nagpalya',
'feedback-error3' => 'Kasalaan: Mayong kasimbagan gikan sa API',
'feedback-thanks' => 'Salamat! An saimong balik-simbag pinagposte sa pahina "[$2 $1]".',
'feedback-close' => 'Nagibo na',
'feedback-bugcheck' => 'Marhay! I-tsek sana baya na ini bakong saro sa mga [$1 bistadong kuto].',
'feedback-bugnew' => 'Pig-tsek ko. Pakireport kan sarong baguhong kuto',

# Search suggestions
'searchsuggest-search' => 'Hanapa baya',
'searchsuggest-containing' => 'may laog na...',

# API errors
'api-error-badaccess-groups' => 'Ika daeng permiso na magkarga nin mga sagunson sa wiking ini.',
'api-error-badtoken' => 'Panlaog na kasalaan: Raot na pangilip',
'api-error-copyuploaddisabled' => 'An pagkakarga sa paagi kan URL pinag-untok sa serbidor na ini.',
'api-error-duplicate' => 'Igwa {{PLURAL:$1|nin [$2 ibang sagunson]|mga [$2 iba pang mga sagunson]}} na yaon sa sityo na igwa nin kaparehong laog.',
'api-error-duplicate-archive' => 'Igwa {{PLURAL:$1|kaidto nin [$2 ibang sagunson]|kaidto nin [$2 ibang mga sagunson]}} na yaon sa sityo na igwa nin kaparehong laog, alagad {{PLURAL:$1|ini kaidto|sinda kaidto}} pinagpura na.',
'api-error-duplicate-archive-popup-title' => 'Kambal na {{PLURAL:$1|sagunson na|mga sagunson na}} pinagpura na.',
'api-error-duplicate-popup-title' => 'Kambal na {{PLURAL:$1|sagunson|mga sagunson}}.',
'api-error-empty-file' => 'An sagunson na saimong pinagsumite daeng laog.',
'api-error-emptypage' => 'Nagmumukna nin bago, mayong laog na mga pahina dae pinagtutugutan.',
'api-error-fetchfileerror' => 'Panlaog na kasalaan: May bagay na naging sala habang hinahakot an sagunson.',
'api-error-fileexists-forbidden' => 'Sarong sagunson na igwang ngaran na "$1" an yaon na, asin dae puwedeng masalambawan.',
'api-error-fileexists-shared-forbidden' => 'Sarong sagunson na igwang ngaran na "$1" an yaon na sa pinagheras na repositoryo nin sagunson, asin dae puwedeng masalambawan.',
'api-error-file-too-large' => 'An sagunson na saimong pinagsumite dakulaon na maray.',
'api-error-filename-tooshort' => 'An pangaran nin sagunson halipoton na maray.',
'api-error-filetype-banned' => 'An tipong ini nin sagunson pinagpangalad na.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|dae itinutugot na tipo nin sagunson|dae itinutugot na mga tipo nin mga sagunson}}. An pinagtutugutan na {{PLURAL:$3|sagunson iyo an tipo na|mga sagunson iyo an mga tipo na}} $2.',
'api-error-filetype-missing' => 'An pangaran nin sagunson nawawaraan nin ekstensyon.',
'api-error-hookaborted' => 'An modipikasyon na saimong pinagprubaran na hihimoon ipinag-untok nin sarong ekstensyon.',
'api-error-http' => 'Panlaog na kasalaan: Dae nakakakonekta sa serbidor.',
'api-error-illegal-filename' => 'An pangaran nin sagunson dae pinagtutugutan.',
'api-error-internal-error' => 'Panlaog na kasalaan: May bagay na napasala sa pagproseso kan saimong pagkakarga sa wiki.',
'api-error-invalid-file-key' => 'Panlaog na kasalaan: An sagunson dae natagboan sa temporaryong sarayan.',
'api-error-missingparam' => 'Panlaog na kasalaan: Nawawara an mga parametro sa kahagadan.',
'api-error-missingresult' => 'Panlaog na kasalaan: Dae madeterminaran kun an kopya naipadagos.',
'api-error-mustbeloggedin' => 'Ika dapat na nakalaog tanganing makapagkarga nin mga sagunson.',
'api-error-mustbeposted' => 'Panlaog na kasalaan: An kahagadan minakaipo nin HTTP POST.',
'api-error-noimageinfo' => 'An pagkarga nagdagos, alagad an serbidor dae nakapagtao samuya nin anuman na impormasyon manunungod sa sagunson.',
'api-error-nomodule' => 'Panlaog na kasalaan: Mayong pankargang modyul an naikaag.',
'api-error-ok-but-empty' => 'Panlaog na kasalaan: Mayong simbag gikan sa serbidor.',
'api-error-overwrite' => 'An salambawan na sarong eksistido nang sagunson dae pinagtutugutan.',
'api-error-stashfailed' => 'Panlaog na kasalaan: An serbidor nagpalya sa pagsaray kan temporaryong sagunson.',
'api-error-timeout' => 'An serbidor dae nakapagsimbag sa laog kan pinaghunang panahon.',
'api-error-unclassified' => 'May dae midbid na kasalaan an nangyari.',
'api-error-unknown-code' => 'Dae midbid na kasalaan: "$1".',
'api-error-unknown-error' => 'Panlaog na kasalaan: May sarong bagay na napasala kan prubaran na ikarga an saimong sagunson.',
'api-error-unknown-warning' => 'Dae midbid na patanid: "$1".',
'api-error-unknownerror' => 'Dae midbidon na kasalaan: "$1".',
'api-error-uploaddisabled' => 'An pagkakarga pinag-untok nguna kaining wiki.',
'api-error-verification-error' => 'Ining sagunson baka koraptu, o igwa nin salang ekstensyon.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|segundo|mga segundo}}',
'duration-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
'duration-hours' => '$1 {{PLURAL:$1|oras|mga oras}}',
'duration-days' => '$1 {{PLURAL:$1|aldaw|mga aldaw}}',
'duration-weeks' => '$1 {{PLURAL:$1|semana|mga semana}}',
'duration-years' => '$1 {{PLURAL:$1|taon|mga taon}}',
'duration-decades' => '$1 {{PLURAL:$1|dekada|mga dekada}}',
'duration-centuries' => '$1 {{PLURAL:$1|siglo|mga siglo}}',
'duration-millennia' => '$1 {{PLURAL:$1|milenyo|mga millenyo}}',

);
