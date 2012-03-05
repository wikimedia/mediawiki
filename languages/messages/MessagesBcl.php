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
	'currentmonth'          => array( '1', 'BULANNGONYAN', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'NGARANBULANNGONYAN', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'ALDAWNGONYAN', 'CURRENTDAY' ),
	'currentyear'           => array( '1', 'TAONNGONYAN', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'PANAHONNGONYAN', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ORASNGONYAN', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'LOKALBULAN', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'NGARANLOKALBULAN', 'LOCALMONTHNAME' ),
	'localday'              => array( '1', 'LOKALALDAW', 'LOCALDAY' ),
	'localday2'             => array( '1', 'LOKALALDAW2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NGARANLOKALALDAW', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'LOKALTAON', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'LOKALPANAHON', 'LOCALTIME' ),
	'localhour'             => array( '1', 'LOKALORAS', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NUMEROKANPAHINA', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NUMEROKANARTIKULO', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NUMEROKANDOKUMENTO', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NUMEROKANPARAGAMIT', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'NUMEROKANLIGWAT', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'NGARANKANPAHINA', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'KAGNGARANKANPAHINA', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NGARANESPASYO', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'KAGNGARANESPASYO', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'OLAYESPASYO', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'KAGOLAYESPASYO', 'TALKSPACEE' ),
	'fullpagename'          => array( '1', 'TODONGNGARANKANPAHINA', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'KAGNGARANKANTODONGNGARANKANPAHINA', 'FULLPAGENAMEE' ),
	'talkpagename'          => array( '1', 'NGARANKANPAHINANINOLAY', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'KAGNGARANKANPAHINANINOLAY', 'TALKPAGENAMEE' ),
	'msg'                   => array( '0', 'MSH', 'MSG:' ),
	'img_right'             => array( '1', 'too', 'right' ),
	'img_left'              => array( '1', 'wala', 'left' ),
	'img_none'              => array( '1', 'mayò', 'none' ),
	'img_center'            => array( '1', 'sentro', 'tangâ', 'center', 'centre' ),
	'img_framed'            => array( '1', 'nakakawadro', 'kwadro', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'daing kwadro', 'frameless' ),
	'img_page'              => array( '1', 'pahina=$1', 'pahina $1', 'page=$1', 'page $1' ),
	'localurl'              => array( '0', 'LOKALURL', 'LOCALURL:' ),
	'localurle'             => array( '0', 'LOKALURLE', 'LOCALURLE:' ),
	'currentweek'           => array( '1', 'SEMANANGONYAN', 'CURRENTWEEK' ),
	'localweek'             => array( '1', 'LOKALSEMANA', 'LOCALWEEK' ),
	'plural'                => array( '0', 'DAKOL:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'TODONGURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'TODONGURLE:', 'FULLURLE:' ),
	'language'              => array( '0', '#TATARAMON', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'TATARAMONKANLAOG', 'TATARAMONLAOG', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'        => array( '1', 'NUMEROKANTAGAMATO', 'NUMBEROFADMINS' ),
	'padleft'               => array( '0', 'PADWALA', 'PADLEFT' ),
	'padright'              => array( '0', 'PADTOO', 'PADRIGHT' ),
	'filepath'              => array( '0', 'FILEDALAN', 'FILEPATH:' ),
	'hiddencat'             => array( '1', '__NAKATAGONGKAT__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PAHINASAKATEGORYA', 'PAHINASAKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'PAHINASOKOL', 'PAGESIZE' ),
);

$specialPageAliases = array(
	'Upload'                    => array( 'Ikarga' ),
	'Search'                    => array( 'Hanapon' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Kurítan an mga takód:',
'tog-highlightbroken'         => 'Pakarahayón an mga raot na takód <a href="" klase="bàgo">arog kaini</a> (panribay: arog kaini<a href="" klase="panlaog">?</a>).',
'tog-justify'                 => 'Pantayón an mga talodtód',
'tog-hideminor'               => 'Tagóon an mga saradít na paghirá sa nakakaági pa sanáng pagbabàgó',
'tog-extendwatchlist'         => 'Palakbangón an lista kan pigbabantayan tangarig mahiling an gabos na angay na pagbabàgo',
'tog-usenewrc'                => 'Paorogón an kaaging pagbabàgo (JavaScript)',
'tog-numberheadings'          => 'Tolos na pagbílang sa mga pamayohán',
'tog-showtoolbar'             => 'Ipahilíng an toolbar nin paghirá (JavaScript)',
'tog-editondblclick'          => 'Hirahón sa dobleng paglagatík an mga pahina (JavaScript)',
'tog-editsection'             => 'Togótan an paghirá kan seksyon sa paági kan mga takód na [hirá]',
'tog-editsectiononrightclick' => 'Togotan an paghirá kan seksyon sa pag-lagatik sa walá sa mga titulo nin seksyon (JavaScript)',
'tog-showtoc'                 => 'Ipahilíng an indise kan mga laog (para sa mga pahinang igwang sobra sa 3 pamayohan)',
'tog-rememberpassword'        => 'Giromdomón an mga paglaóg ko sa kompyuter na iní (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Idúgang an mga pahinang ginigíbo ko sa pigbabantayan ko',
'tog-watchdefault'            => 'Idúgang an mga pahinang pighíhirá ko sa pigbabantayan ko',
'tog-watchmoves'              => 'Idúgang an mga pahinang piglilípat ko sa pigbabantayan ko',
'tog-watchdeletion'           => 'Idúgang an mga pahinang pigpapárà ko sa pigbabantayan ko',
'tog-previewontop'            => 'Ipahilíng an patànaw bàgo an kahon nin paghirá',
'tog-previewonfirst'          => 'Ipahilíng an patànaw sa enot na paghirá',
'tog-nocache'                 => 'Pogólon an pag-abang nin mga pahina',
'tog-enotifwatchlistpages'    => 'E-koreohan ako pag pigribayan an pahinang pigbabantayan ko',
'tog-enotifusertalkpages'     => 'E-koreohan ako pag pigribáyan an pahina kan sakóng olay',
'tog-enotifminoredits'        => 'E-koreohan man giraray ako para sa saradit na paghirá kan mga pahina',
'tog-enotifrevealaddr'        => 'Ibunyág an adres kan sakuyang e-koreo sa mga surat na pag-abiso',
'tog-shownumberswatching'     => 'Ipahilíng an bilang kan nagbabantay na mga parágamit',
'tog-fancysig'                => 'Mga bàgong pirma (mayò nin tolos na pantakod)',
'tog-externaleditor'          => 'Gamíton mùna an panluwas na editor',
'tog-externaldiff'            => 'Gamíton mùna an diff na panluwás',
'tog-showjumplinks'           => 'Maka-"luksó sa" mga takód na pangabót',
'tog-uselivepreview'          => 'Gamíton an patànaw na direkto (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Ipaarám sakuyà kun malaog sa sumáriong blanko nin paghirá',
'tog-watchlisthideown'        => 'Tagoon an mga saradit na paghirá sa pigbabantayan',
'tog-watchlisthidebots'       => 'Tagoon an mga paghirá kan bot sa pigbabantayan',
'tog-watchlisthideminor'      => 'Tagoon an mga saradít na paghirá sa pigbabantayan',
'tog-nolangconversion'        => 'Pogólon an pagríbay nin mga lain-lain',
'tog-ccmeonemails'            => 'Padarahán ako nin mga kopya kan e-koreo na pigpadara ko sa ibang mga parágamit',
'tog-diffonly'                => 'Dai ipahilíng an mga laog nin pahina sa babâ kan kaib',
'tog-showhiddencats'          => 'Ipahiling an mga nakatagong kategorya',

'underline-always'  => 'Pirmi',
'underline-never'   => 'Nungka',
'underline-default' => 'Browser na normal',

# Dates
'sunday'        => 'Domingo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miyerkoles',
'thursday'      => 'Huwebes',
'friday'        => 'Biyernes',
'saturday'      => 'Sabado',
'sun'           => 'Dom',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Miy',
'thu'           => 'Huw',
'fri'           => 'Biy',
'sat'           => 'Sab',
'january'       => 'Enero',
'february'      => 'Pebrero',
'march'         => 'Marso',
'april'         => 'Abril',
'may_long'      => 'Mayo',
'june'          => 'Hunyo',
'july'          => 'Hulyo',
'august'        => 'Agosto',
'september'     => 'Setiembre',
'october'       => 'Oktobre',
'november'      => 'Nobiembre',
'december'      => 'Deciembre',
'january-gen'   => 'Enero',
'february-gen'  => 'Pebrero',
'march-gen'     => 'Marso',
'april-gen'     => 'Abril',
'may-gen'       => 'Mayo',
'june-gen'      => 'Hunyo',
'july-gen'      => 'Hulyo',
'august-gen'    => 'Agosto',
'september-gen' => 'Setiembre',
'october-gen'   => 'Oktobre',
'november-gen'  => 'Nobiembre',
'december-gen'  => 'Deciembre',
'jan'           => 'Ene',
'feb'           => 'Peb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'May',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorya|Mga kategorya}}',
'category_header'                => 'Mga artikulo sa kategoryang "$1"',
'subcategories'                  => 'Mga sub-kategorya',
'category-media-header'          => 'Media sa kategoryang "$1"',
'category-empty'                 => "''Mayò nin laog an kategoryang ini sa ngonyan.''",
'hidden-categories'              => '{{PLURAL:$1|Nakatagong kategorya|Mga nakatagong kategorya}}',
'hidden-category-category'       => 'Mga nakatagong kategorya',
'category-subcat-count-limited'  => 'Igwa nin {{PLURAL:$1|sub-kategorya|$1 mga sub-kategorya}} an artikulong ini.',
'category-article-count'         => '{{PLURAL:$2|An mga minasunod na pahina sana an laog kan kategoryang ini|An mga minasunod na {{PLURAL:$1|pahina|$1 pahina}} an yaon sa kategoryang ini, sa $2 gabos.}}',
'category-article-count-limited' => 'Yaon sa presenteng kategorya an mga minasunod na {{PLURAL:$1|pahina|$1 pahina}}.',
'listingcontinuesabbrev'         => 'sunód',

'mainpagetext'      => "'''Instalado na an MediaWiki.'''",
'mainpagedocfooter' => "Konsultarón tabì an [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] para sa impormasyon sa paggamit nin progama kaining wiki.

== Pagpopoon ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Manonongod',
'article'       => 'Laog na pahina',
'newwindow'     => '(minabukas sa bàgong bintanà)',
'cancel'        => 'Pondohón',
'moredotdotdot' => 'Dakol pa...',
'mypage'        => 'An sakóng pahina',
'mytalk'        => 'An sakóng olay',
'anontalk'      => 'Olay para sa IP na ini',
'navigation'    => 'Nabigasyon',
'and'           => '&#32;asin',

# Cologne Blue skin
'qbfind'         => 'Hanápon',
'qbbrowse'       => 'Maghalungkát',
'qbedit'         => 'Hirahón',
'qbpageoptions'  => 'Ining pahina',
'qbpageinfo'     => 'Konteksto',
'qbmyoptions'    => 'Mga pahina ko',
'qbspecialpages' => 'Espesyal na mga pahina',
'faq'            => 'HD',
'faqpage'        => 'Project:HD',

# Vector skin
'vector-action-delete' => 'paráon',
'vector-action-move'   => 'Ibalyó',
'vector-view-create'   => 'Maggíbo',
'vector-view-edit'     => 'Liwatón',
'vector-view-view'     => 'Basáhon',

'errorpagetitle'    => 'Salâ',
'returnto'          => 'Magbwelta sa $1.',
'tagline'           => 'Halì sa {{SITENAME}}',
'help'              => 'Tabang',
'search'            => 'Hanápon',
'searchbutton'      => 'Hanápon',
'go'                => 'Dumanán',
'searcharticle'     => 'Dumanán',
'history'           => 'Uusipón nin pahina',
'history_short'     => 'Uusipón',
'updatedmarker'     => 'nabàgo poon kan huri kong pagdalaw',
'info_short'        => 'Impormasyon',
'printableversion'  => 'Naipiprentang bersyon',
'permalink'         => 'Permanenteng takod',
'print'             => 'Iprenta',
'edit'              => 'Liwatón',
'create'            => 'Maggibo',
'editthispage'      => 'Hirahón ining pahina',
'create-this-page'  => 'Gibohon ining pahina',
'delete'            => 'Paraon',
'deletethispage'    => 'Paraon ining pahina',
'undelete_short'    => 'Bawion an pagparà {{PLURAL:$1|paghirá|$1 mga paghirá}}',
'protect'           => 'Protehiran',
'protect_change'    => 'ribáyan an proteksyon',
'protectthispage'   => 'Protehiran ining pahina',
'unprotect'         => 'bawion an pagprotehir',
'unprotectthispage' => 'Bawion an proteksyon kaining pahina',
'newpage'           => 'Bàgong pahina',
'talkpage'          => 'Pag-olayan ining pahina',
'talkpagelinktext'  => 'Pag-olayán',
'specialpage'       => 'Espesyal na Pahina',
'personaltools'     => 'Mga gamit na personal',
'postcomment'       => 'Magkomento',
'articlepage'       => 'Hilingón an pahina kan laog',
'talk'              => 'Orólay',
'views'             => 'Mga hilíng',
'toolbox'           => 'Kagamitan',
'userpage'          => 'Hilingón an pahina kan parágamit',
'projectpage'       => 'Hilingón an pahina kan proyekto',
'imagepage'         => 'Hilingón an pahina kan ladawan',
'mediawikipage'     => 'Hilingón an pahina kan mensahe',
'templatepage'      => 'Hilingón an pahina kan templato',
'viewhelppage'      => 'Hilingón an pahina kan tabang',
'categorypage'      => 'Hilingón an pahina kan kategorya',
'viewtalkpage'      => 'Hilingón an orólay',
'otherlanguages'    => 'Sa ibang mga tataramon',
'redirectedfrom'    => '(Piglikay halì sa $1)',
'redirectpagesub'   => 'Ilikáy an pahina',
'lastmodifiedat'    => 'Huring pigmodipikar an pahinang ini $2 kan $1.',
'viewcount'         => 'Binukasán ining pahina nin {{PLURAL:$1|sarong beses|nin $1 beses}}.',
'protectedpage'     => 'Protektadong pahina',
'jumpto'            => 'Maglukso sa:',
'jumptonavigation'  => 'nabigasyon',
'jumptosearch'      => 'hanápon',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Manonongod sa {{SITENAME}}',
'aboutpage'            => 'Project:Manonongod',
'copyright'            => 'Makukua an laog sa $1.',
'copyrightpage'        => '{{ns:project}}:Mga derechos nin parásurat',
'currentevents'        => 'Mga panyayari sa ngonyan',
'currentevents-url'    => 'Project:Mga panyayari sa ngonyan',
'disclaimers'          => 'Mga renunsya',
'disclaimerpage'       => 'Project:Heneral na renunsya',
'edithelp'             => 'Paghirá kan pagtabang',
'edithelppage'         => 'Help:Pagliwat',
'helppage'             => 'Help:Mga laog',
'mainpage'             => 'Pangenot na Pahina',
'mainpage-description' => 'Pangenot na Pahina',
'policy-url'           => 'Project:Palakáw',
'portal'               => 'Portal kan komunidad',
'portal-url'           => 'Project:Portal kan Komunidad',
'privacy'              => 'Palakaw nin pribasidad',
'privacypage'          => 'Project:Palakaw nin pribasidad',

'badaccess'        => 'Salang permiso',
'badaccess-group0' => 'Dai ka tinotogotan na gibohon an aksyon na saimong hinahagad.',
'badaccess-groups' => 'An aksyon na saimong hinahagad limitado sa mga parágamit sa sarô sa mga grupong $1.',

'versionrequired'     => 'Kaipuhan an bersyon $1 kan MediaWiki',
'versionrequiredtext' => 'Kaipuhan an bersyon $1 kan MediaWiki sa paggamit kan pahinang ini. Hilíngón an [[Special:Version|Bersyon kan pahina]].',

'ok'                      => 'Sige',
'retrievedfrom'           => 'Pigkua sa "$1"',
'youhavenewmessages'      => 'Igwa ka nin $1 ($2).',
'newmessageslink'         => 'mga bàgong mensahe',
'newmessagesdifflink'     => 'huring pagbàgo',
'youhavenewmessagesmulti' => 'Igwa ka nin mga bàgong mensahe sa $1',
'editsection'             => 'liwatón',
'editold'                 => 'Liwatón',
'viewsourceold'           => 'hilingón an ginikánan',
'editlink'                => 'liwatón',
'viewsourcelink'          => 'hilingón an toltolan',
'editsectionhint'         => 'Liwatón an seksyon: $1',
'toc'                     => 'Mga laog',
'showtoc'                 => 'ipahilíng',
'hidetoc'                 => 'tagóon',
'thisisdeleted'           => 'Hilingón o isulít an $1?',
'viewdeleted'             => 'Hilingón an $1?',
'restorelink'             => '{{PLURAL:$1|sarong pinarang paghirá|$1 na pinarang paghirá}}',
'feedlinks'               => 'Hungit:',
'feed-invalid'            => 'Bawal na tipo nin hungit na subkripsyon.',
'feed-unavailable'        => 'Mayò an mga sindikasyon na hungit',
'site-rss-feed'           => '$1 Hungit nin RSS',
'site-atom-feed'          => '$1 Hungit nin Atomo',
'page-rss-feed'           => '"$1" Hungit na RSS',
'page-atom-feed'          => '"$1" Hungit na Atomo',
'feed-atom'               => 'Atomo',
'red-link-title'          => '$1 (dai pa naisusurat)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pahina',
'nstab-user'      => 'Pahina nin paragamit',
'nstab-media'     => 'Pahina kan media',
'nstab-special'   => 'Espesyal',
'nstab-project'   => 'Pahina kan proyekto',
'nstab-image'     => 'File',
'nstab-mediawiki' => 'Mensahe',
'nstab-template'  => 'Templato',
'nstab-help'      => 'Pahina kan tabang',
'nstab-category'  => 'Kategorya',

# Main script and global functions
'nosuchaction'      => 'Mayong siring na aksyon',
'nosuchactiontext'  => 'An gibo na pinílì nin URL dai bisto kan wiki',
'nosuchspecialpage' => 'Mayong siring na espesyal na páhina',
'nospecialpagetext' => '<strong>Dai pwede an pahinang espesyal na pinilî mo.</strong>

Pwede mong mahiling an lista nin mga marhay na pahina sa [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Salâ',
'databaseerror'        => 'Salâ sa base nin datos',
'dberrortext'          => 'May salâ sa hapot na sintaksis kan base nin datos.
Pwedeng may salâ digdi.
An huring probar na hapót iyo:
<blockquote><tt>$1</tt></blockquote>
hale sa aksyón "<tt>$2</tt>".
AnSQL ko nagbalik nin salâ na"<tt>$3: $4</tt>".',
'dberrortextcl'        => 'May salâ sa hapót nin sintaksis kan base nin datos.
Ini an huring probar na hapót:
"$1"
sa aksyón na "$2".
AnSQL ko nagbalik nin salâ na"$3: $4"',
'laggedslavemode'      => 'Patanid: An pahina pwedeng dai nin pagbabâgo sa ngonyan.',
'readonly'             => 'Kandado an base nin datos',
'enterlockreason'      => 'Magkaag tabì nin rason sa pagkandado, asin ikalkulo kun nuarin bubukasón an kandado',
'readonlytext'         => 'Sarado mùna an base nin datos sa mga bàgong entrada asin iba pang mga pagribay, pwede gayod sa rutinang pagmantenir kan base nin datos, despues, mabalik na ini sa normal.

Ini an eksplikasyon kan tagamató na nagkandado kaini: $1',
'missingarticle-rev'   => '(pagbàgo#: $1)',
'missingarticle-diff'  => '(Kaibhán: $1, $2)',
'readonly_lag'         => 'Enseguidang nakandado an base nin datos mientras makaabot an base nin datos na esklabo saiyang amo.',
'internalerror'        => 'Panlaog na salâ',
'internalerror_info'   => 'Panlaog na salâ: $1',
'filecopyerror'        => 'Dai naarog an mga file na "$1" hasta "$2".',
'filerenameerror'      => 'Dai natàwan nin bàgong ngaran an file na "$1" sa "$2".',
'filedeleteerror'      => 'Dai naparà an file na "$1".',
'directorycreateerror' => 'Dai nagibo an direktorya na "$1".',
'filenotfound'         => 'Dai nahanap an file na "$1".',
'fileexistserror'      => 'Dai maisurat sa file na "$1": igwa nang file na arog kaini',
'unexpected'           => 'Dai pighuhunà na balór: "$1"="$2".',
'formerror'            => 'Salâ: dai pwedeng isumitir an porma',
'badarticleerror'      => 'Dai pwedeng gibohon ini sa ining páhina.',
'cannotdelete'         => 'Dai naparà an pahina o file na napilî. (Pwede na naparà na ini kan ibang paragamit.)',
'badtitle'             => 'Salâ an titulo',
'badtitletext'         => 'Dai pwede an hinagad na titulo nin pahina, o mayong laog, o sarong titulong pan-ibang tatarámon o pan-ibang wiki na sala an pagkatakód. Pwedengigwa ining sarô o iba pang mga karakter na dai pwedeng gamiton sa mga titulo.',
'perfcached'           => 'Nakaabang an minasunod na mga datos, asin pwede ser na mga lumâ na.',
'perfcachedts'         => 'Nakaabang an nagsusunod na mga datos, asin huring nabâgo sa $1.',
'querypage-no-updates' => 'Pigpopogol mùna an mga pagbabàgo sa pahinang ini. Dai mùna mababàgo an mga datos digdi.',
'wrong_wfQuery_params' => 'Salâ na parámetro sa wfQuery()<br />
Acción: $1<br />
Hapót: $2',
'viewsource'           => 'Hilingón an ginikanan',
'viewsourcefor'        => 'para sa $1',
'protectedpagetext'    => 'An pahinang ini pigsará tangarig pogolon an paghirá.',
'viewsourcetext'       => 'Pwede mong hilingón asin arógon an ginikanan kan pahinang ini:',
'protectedinterface'   => 'An pahinang ini nagtatao nin interface para sa software, asin sarado tangarig mapondo an pag-abuso.',
'editinginterface'     => "'''Patanid:''' An pighihira mong pahina piggagamit para sa tekstong interface kan software. An mga pagbabàgo sa pahinang ini makakaapektar sa hitsura kan interface nin parágamit kan mga ibang parágamit.",
'sqlhidden'            => '(nakatagô an hapót nin SQL)',
'cascadeprotected'     => 'Pinoprotehirán ining páhina sa mga paghirá, ta sarô ini sa mga minasunod na {{PLURAL:$1|páhina|mga páhina}} na pinoprotehiran kan opsyón na "katarata" na nakabuká:
$2',
'namespaceprotected'   => "Mayô kang permisong maghirá kan mga páhina sa '''$1''' ngaran-espacio.",
'customcssjsprotected' => 'Mayô kang permiso na maghirâ kaining páhina, hulî ta igwa ining mga puesta na personal kan ibang paragamit.',
'ns-specialprotected'  => 'An mga páhinang nasa {{ns:special}} na ngaran-espacio dai pwedeng hirahón.',

# Virus scanner
'virus-badscanner'     => "Saláng konfigurasyon: dai aram an virus scanner: ''$1''",
'virus-unknownscanner' => 'dai aram an antivirus:',

# Login and logout pages
'logouttext'                 => "'''Nakaluwas ka na.'''

Pwede mo pang gamiton an {{SITENAME}} na dai nagpapabisto, o pwede ka giraray lumaog
bilang pareho o ibang parágamit. Giromdomon tabî na an ibang mga páhina pwedeng mahiling pa na garo nakalaog ka pa, hasta limpyarón mo an abang kan ''browser'' mo.",
'welcomecreation'            => "== Maogmang Pagdagos, $1! ==

Nagibo na an ''account'' mo. Giromdomon tabi na ribayán an saimong mga kabôtan sa {{SITENAME}}.",
'yourname'                   => 'Pangaran kan paragamit:',
'yourpassword'               => 'Sekretong panlaog:',
'yourpasswordagain'          => 'Itaták giraray an sekretong panlaog:',
'remembermypassword'         => 'Giromdomon an paglaog ko sa kompyuter na ini(for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'             => "An saimong ''domain'':",
'externaldberror'            => "Igwang nin salang panluwas pantunay kan base nin datos o dai ka pigtotogotan na bâgohon an saimong panluwas na ''account''.",
'login'                      => 'Maglaog',
'nav-login-createaccount'    => 'Maglaog / maggibo nin account',
'loginprompt'                => 'Kaipuhan may cookies ka para makalaog sa {{SITENAME}}.',
'userlogin'                  => 'Maglaog / maggibo nin account',
'logout'                     => 'Magluwas',
'userlogout'                 => 'Magluwás',
'notloggedin'                => 'Mayò sa laog',
'nologin'                    => "Igwa ka nin entrada? '''$1'''.",
'nologinlink'                => 'Maggibo nin account',
'createaccount'              => 'Maggibo nin account',
'gotaccount'                 => "Igwa ka na nin account? '''$1'''.",
'gotaccountlink'             => 'Maglaog',
'createaccountmail'          => 'sa e-koreo',
'badretype'                  => 'Dai parehas an pigtaták mong mga sekretong panlaog.',
'userexists'                 => 'Piggagamit na kan iba an pangaran. Magpili tabî nin iba.',
'loginerror'                 => 'Salâ an paglaog',
'createaccounterror'         => 'Daí maggíbo an account: $1.',
'nocookiesnew'               => 'Nagibo na an account kan parágamit, alagad dai ka pa nakalaog. Naggagamit nin cookies an {{SITENAME}} para magpalaog sa mga parágamit. Nakapondo an cookies mo. Paandaron tabì ini, dangan, maglaog gamit an bàgo mong pangaran asin sekretong panlaog.',
'nocookieslogin'             => 'Naggagamit nin mga cookies an {{SITENAME}} para magpalaog nin mga paragamit. Nakapondo an mga cookies mo. Paandaron tabi ini asin probaran giraray.',
'noname'                     => 'Dai ka pa nagkaag nin pwedeng gamiton na pangaran.',
'loginsuccesstitle'          => 'Matriumpo an paglaog',
'loginsuccess'               => "'''Nakalaog ka na sa {{SITENAME}} \"\$1\".'''",
'nosuchuser'                 => 'Mayong paragamit sa pangaran na "$1". Reparohon an pigsurat mo, o maggibo nin bàgong account.',
'nosuchusershort'            => 'Mayong paragamit sa nagngangaran na "<nowiki>$1</nowiki>". Reparohon an pigsurat mo.',
'nouserspecified'            => 'Kaipuhan mong kaagan nin pangaran.',
'wrongpassword'              => 'Salâ an pigtaták na sekretong panlaog. Probaran giraray tabì.',
'wrongpasswordempty'         => 'Mayong pigkaag na sekretong panlaog. Probaran giraray tabì.',
'passwordtooshort'           => 'Salâ o halìpoton an saimong sekretong panlaog. Igwa dapat ining dai mababà sa {{PLURAL:$1|1 karakter|$1 karakter}} asin iba man sa pinilì mong pangaran.',
'mailmypassword'             => 'Ipadara sa e-koreo an sekretong panlaog',
'passwordremindertitle'      => 'Panpaísi nin sekretong panlaog halì sa {{SITENAME}}',
'passwordremindertext'       => 'Sarong paragamit (pwedeng ika, halì sa IP na $1)
an naghagad nin bàgong sekretong panlaog para sa {{SITENAME}} ($4).
"$3" na an bàgong sekretong panlaog ni "$2".
Kaipuhan maglaog ka asin ibalyó an saimong sekretong panlaog.

Kun ibang tawo an naghagad kaini o kun nagiromdóman mo na an saimong sekretong panlaog asin habô mo nang ribayan ini, dai mo na pagintiendehon ining mensahe. Ipadagos na an paggamit kan dating sekretong panlaog.',
'noemail'                    => 'Mayong e-koreo na nakarehistro sa parágamit na "$1".',
'passwordsent'               => 'Igwang bàgong sekretong panlaog na pigpadará sa e-koreong nakarehistro ki "$1".
Maglaog tabì giraray pagnakua mo na ini.',
'blocked-mailpassword'       => 'Pigbagatan sa paghirá an saimong IP, asin pigpopogolan na magamit an pagbawi kan sekretong panlaog tangarig maibitaran an pagabuso.',
'eauthentsent'               => 'May ipinadarang e-koreong kompirmasyon sa piniling adres nin e-koreo.
Bàgo pa magpadara nin iba pang e-koreo sa account na ini, kaipuhan tabing sunodon an mga instruksyon na nasa e-koreo, tangarig makompirmar na talagang saimo ining account.',
'throttled-mailpassword'     => 'May pinadara nang paisi nin sekretong panlaog, sa huring
$1 na oras. Para pogolan an mga pagabuso, sarong paisi sana an ipapadara sa laog nin
$1 na oras.',
'mailerror'                  => 'Salâ an pagpadará nin e-koreo: $1',
'acct_creation_throttle_hit' => 'Pasensya, nakagibo ka na nin $1 accounts. Dai ka na makakagibo pa.',
'emailauthenticated'         => "An saimong ''e''-surat pinatunayan sa $1.",
'emailnotauthenticated'      => "Dai pa napatunayan an saimong ''e''-surat. Mayong ipapadarang ''e''-surat para sa ano man na minasunod na gamit.",
'noemailprefs'               => "Magpilî tabî nin ''e''-surat tangarig magandar ining mga gamit.",
'emailconfirmlink'           => "Kompirmaron tabî an saimong ''e''-surat",
'invalidemailaddress'        => "Dai matogotan ining ''e''-surat ta garo salâ an ''format'' kaini. Magkaag tabî nin tamâ o dai pagkaagan.",
'accountcreated'             => "Nagibo na an ''account''.",
'accountcreatedtext'         => "Ginibo na an ''account'' para ki $1.",
'loginlanguagelabel'         => 'Tataramon: $1',

# Password reset dialog
'resetpass'                 => "Ipwesto giraray an sekretong panlaog kan ''account''",
'resetpass_announce'        => "Nakalaog ka na may kodang temporaryong ''e''-sinurat. Para matapos an paglaog, kaipuhan mong magpwesto nin bâgong sekretong panlaog digdi:",
'resetpass_text'            => '<!-- Magdugang nin teksto digdi -->',
'resetpass_header'          => 'Ibalyó an sekretong panlaog',
'oldpassword'               => 'Lumang sekretong panlaog:',
'newpassword'               => 'Bàgong sekretong panlaog:',
'retypenew'                 => 'Itaták giraray an bàgong panlaog:',
'resetpass_submit'          => 'Ipwesto an sekretong panlaog dangan maglaog',
'resetpass_success'         => 'Naribayan na an saimong sekretong panlaog! Pigpapadagos ka na...',
'resetpass_forbidden'       => 'Dai pwedeng ribayan an mga sekretong panlaog sa ining wiki',
'resetpass-submit-loggedin' => 'Ribayan an sekretong panlaog',
'resetpass-wrong-oldpass'   => 'Saláng temporaryo o presenteng sekretong panlaog.
Matriumpo mo nang nailaog an sekretong panlaog o nakua an bàgong temporaryong sekretong panlaog.',
'resetpass-temp-password'   => 'Temporaryong sekretong panlaog:',

# Edit page toolbar
'bold_sample'     => 'Tekstong mahìbog',
'bold_tip'        => 'Mahîbog na teksto',
'italic_sample'   => 'Tekstong Itáliko',
'italic_tip'      => 'Tekstong patagilíd',
'link_sample'     => 'Titulo nin takod',
'link_tip'        => 'Panlaog na takod',
'extlink_sample'  => 'http://www.example.com títulong nakatakod',
'extlink_tip'     => 'Panluwas na takod (giromdomon an http:// na prefiho)',
'headline_sample' => 'Tekstong pamayohan',
'headline_tip'    => 'Tangga ika-2 na pamayohan',
'math_sample'     => 'Isaliôt an pormula digdi',
'math_tip'        => 'Pórmulang matemátika (LaTeX)',
'nowiki_sample'   => "Isaliot digdi an tekstong dai na-''format''",
'nowiki_tip'      => "Dai pagindiendehon pag-''format'' kan wiki",
'image_sample'    => 'Halimbawa.jpg',
'image_tip'       => 'Nakaturay na file',
'media_sample'    => 'Halimbawa.ogg',
'media_tip'       => 'Takod nin file',
'sig_tip'         => 'Pirma mo na may taták nin oras',
'hr_tip'          => 'Pabalagbag na linya (use sparingly)',

# Edit pages
'summary'                    => 'Sumada:',
'subject'                    => 'Tema/pamayohan:',
'minoredit'                  => 'Sadit na paghirá ini',
'watchthis'                  => 'Bantayan an pahinang ini',
'savearticle'                => 'Itagáma an pahina',
'preview'                    => 'Tànawón',
'showpreview'                => 'Hilingón an patànaw',
'showlivepreview'            => 'Patànaw na direkto',
'showdiff'                   => 'Hilingón an mga pagbabàgo',
'anoneditwarning'            => "'''Patanid:''' Dai ka nakalaog. Masusurat an saimong IP sa uusipón kan pagbabàgo kan pahinang ini.",
'missingsummary'             => "'''Paisi:''' Dai ka nagkaag nin sumád kan paghirâ. Kun pindotón mo giraray an Itagama, maitatagama an hirá mo na mayô kaini.",
'missingcommenttext'         => 'Paki lâgan nin komento sa ibabâ.',
'missingcommentheader'       => "'''Paisi:''' Dai ka nagkaag nin tema/pamayohan para sa ining komentaryo. Kun pindoton mo giraray an Itagama, maitatagama an hira mo na mayô ini.",
'summary-preview'            => 'Patànaw nin sumada:',
'subject-preview'            => 'Patânaw nin tema/pamayohan:',
'blockedtitle'               => 'Pigbágat an parágamit',
'blockedtext'                => "'''Pigbagat an pangaran o IP mo.'''

Si $1 an nagbagat. Ini an itinaong rasón, ''$2''.

* Pagpoon kan pagbagat: $8
* Pagpasó kan pagbagat: $6
* Piniling bagaton: $7

Pwede mong suratan si $1 o an iba pang [[{{MediaWiki:Grouppage-sysop}}|administrador]] para pagoralayan an manonongod sa pagbagat.
Dai mo pwedeng gamiton an ' e-koreohan an paragamit ' kun mayong tamang e-surat sa  [[Special:Preferences|mga kabòtan kan account]] mo asin dai ka pigbagat sa paggamit kaini.
$3 an presente mong IP, asin #$5 an ID nin pigbagat. Ikaag tabì an arin man o pareho sain man na hapót.",
'autoblockedtext'            => "Enseguidang pigbagat an IP mo ta ginamit ini kan ibang parágamit, na binagat ni \$1.
Ini an rasón:

:''\$2''

* Pagpoon kan pagbagat: \$8
* Pagpasó kan pagbagat: \$6

Pwedeng mong suratan si \$1 o an iba pang mga
[[{{MediaWiki:Grouppage-sysop}}|administrador]] para pagolayan an manonongod sa pagbagat.

Giromdomon tabî na pwede mo sanang gamiton an \"''e''-suratan ining parágamit\" na gamit kun igwa kang tamang ''e''-surat na nakarehistro saimong [[Special:Preferences|mga kabôtan nin parágamit]] asin dai ka pigbabagat sa paggamit kaini.

\$5 an pigbagat na ID. Ikaag tabî ining ID sa gabos na paghapot mo.",
'blockednoreason'            => 'mayong itinaong rason',
'blockedoriginalsource'      => "An ginikanan kan '''$1''' mahihiling sa ibabâ:",
'blockededitsource'          => "An teksto kan '''mga hira mo''' sa '''$1''' mahihiling sa babâ:",
'whitelistedittitle'         => 'Kaipuhan an paglaog tangarig makahirá',
'whitelistedittext'          => 'Kaipuhan mong $1 tangarig makahirá nin mga páhina.',
'confirmedittext'            => "Kaipuhan mong kompirmaron an saimong ''e''-surat. Ipwesto tabî asin patunayan an saimong ''e''-surat sa [[Special:Preferences|mga kabôtan kan parágamit]].",
'nosuchsectiontitle'         => 'Mayong siring na seksyón',
'nosuchsectiontext'          => 'Mayo man an seksyón an pighihira mo.',
'loginreqtitle'              => 'Kaipuhan Maglaog',
'loginreqlink'               => 'maglaog',
'loginreqpagetext'           => 'Kaipuhan kang $1 tangarig makahilíng nin ibang pahina.',
'accmailtitle'               => 'Napadará na an sekretong panlaog.',
'accmailtext'                => 'An sekretong panlaog ni "$1" naipadará na sa $2.',
'newarticle'                 => '(Bàgo)',
'newarticletext'             => 'Sinunod mo an takod sa pahinang mayò pa man.
Tangarig magibo an pahina, magpoon pagsurat sa kahon sa babâ
(hilingón an [[{{MediaWiki:Helppage}}|pahina nin tabang]] para sa iba pang impormasyon).
Kun dai tinuyong nakaabot ka digdi, pindoton sana an back sa browser mo.',
'anontalkpagetext'           => "----''Ini an pahina kan olay kan sarong parágamit na dai bisto na dai pa naggibo nin account o dai naggagamit kaini. Entonces, piggagamit mi an numero nin IP tangarig mabisto siya. Ining IP pwede gamiton kan manlain-lain na mga parágamit. Kun ika sarong paraágamit na dai bisto asin konbensido ka sa pigsasabi ka ining mga komento bakô man dapit saimo,  [[Special:UserLogin|maggibo nin'' account ''o maglaog]] tabì tangarig maibitaran an pagkaribong saimo asin sa ibang mga parágamit na dai bisto.''",
'noarticletext'              => 'Mayo man na teksto sa páhinang ini, pwede mong [[Special:Search/{{PAGENAME}}|hanápon ining titulo nin páhina]] sa ibang mga páhina o [{{fullurl:{{FULLPAGENAME}}|action=edit}} hirahon ining páhina].',
'clearyourcache'             => "'''Pagiromdom:''' Pagkatapos kan pagtagama, pwede ser na kaipuhan mong lawigawan an abang kan ''browser'' para mahiling mo an mga pagbabâgo. '''Mozilla / Firefox / Safari:''' doonan an ''shift'' an ''Shift'' sabay an pagpindot sa ''Reload'', o pindoton an ''Ctrl-Shift-R'' (''Cmd-Shift-R'' sa Apple Mac); '''IE:''' doonan (dai halion an muro) an ''Ctrl'' mientras sabay an pagpindot sa  ''Refresh'', o pindoton an ''Ctrl-F5''; '''Konqueror:''': pindoton sana ''Reload'', o pindoton  an ''F5''; '''Opera''' pwede ser na kaipuhan na halîon an gabos na laog kan abang sa ''Tools→Preferences''.",
'usercssyoucanpreview'       => "'''Tip:''' Gamiton an 'Show preview' para testingon an bâgong CSS bago magtagama.",
'userjsyoucanpreview'        => "'''Tip:''' Gamiton an 'Show preview' para testingon an bâgong JS bago magtagama.",
'usercsspreview'             => "'''Giromdomon tabî na pigpapatânaw sana saimo an CSS nin parágamit, dai pa ini nakatagama!'''",
'userjspreview'              => "'''Giromdomon tabi na pigtetest/pighihiling mo sana an patanaw kan saimong JavaScript nin paragamit, dai pa ini naitagama!'''",
'userinvalidcssjstitle'      => "'''Patanid:''' Mayong ''skin'' na \"\$1\". Giromdomon tabî na an .css asin .js na mga páhina naggagamit nin titulong nakasurat sa sadit na letras, halimbawa {{ns:user}}:Foo/monobook.css bakong {{ns:user}}:Foo/Monobook.css.",
'updated'                    => '(Binàgo)',
'note'                       => "'''Paisi:'''",
'previewnote'                => "'''Patànaw sana ini; dai pa naitagama an mga pagbabàgo!'''",
'previewconflict'            => 'Mahihilíng sa patànaw na ini an tekstong nasa itaas na lugar nin paghirá arog sa maipapahiling kun ini an itatagama mo.',
'session_fail_preview'       => "'''Despensa! Dai mi naipadagos an paghirá mo huli sa pagkawara nin datos kan sesyon.
Probaran tabì giraray. Kun dai man giraray magibo, probaran na magluwas dangan maglaog giraray.'''",
'session_fail_preview_html'  => "'''Despensa! Dai mi naipadagos an paghirá mo nin huli sa kawàran kan datos kan sesyon.'''

''Huli ta ining wiki may HTML na nakaandar, pigtago an patànaw bilang paglikay kontra sa mga atake sa JavaScript.''

'''Kun talagang boot mong hirahón ini, probaran giraray. Kun dai pa giraray magibo, magluwas dangan maglaog giraray. '''",
'token_suffix_mismatch'      => "'''Dai pigtogotan an paghirá mo ta sinabrit kan client mo an punctuation characters.
Dai pigtogotan ining paghirá tangarig maibitaran na maraot an teksto kan pahina.
Nanyayari nanggad ini kun naggagamit ka nin bakong maraháy asin dai bistong web-based proxy service.'''",
'editing'                    => 'Pigliliwat an $1',
'editingsection'             => 'Pighihira an $1 (seksyon)',
'editingcomment'             => 'Pighihira an $1 (komento)',
'editconflict'               => 'Komplikto sa paghihira: $1',
'explainconflict'            => "May ibang parágamit na nagbàgo kaining pahina kan pagpoon mong paghirá kaini.
Nahihilíng ang pahina kan teksto sa parteng itaas kan teksto.
An mga pagbabàgo mo nahihilíng sa parteng ibabâ kan teksto.
Kaipuhan mong isalak an mga pagbabàgo mo sa presenteng teksto.
An teksto na nasa parteng itaas '''sana''' an maitatagama sa pagpindot mo kan \"Itagama an pahina\".",
'yourtext'                   => 'Saimong teksto',
'storedversion'              => 'Itinagamang bersyon',
'nonunicodebrowser'          => "'''PATANID: An browser mo bakong unicode complaint. Igwang temporariong sistema na nakaandar para makahirá ka kan mga pahina: mahihiling an mga karakter na non-ASCII sa kahon nin paghirá bilang mga kodang hexadecimal.'''",
'editingold'                 => "'''PATANID: Pighihirá mo an pasó nang pagpakaraháy kaining pahina.
Kun itatagama mo ini, mawawarà an mga pagbabàgong nagibo poon kan pagpakaraháy kaini.'''",
'yourdiff'                   => 'Mga kaibahán',
'copyrightwarning'           => "Giromdomon tabì na an gabos na kontribusyon sa {{SITENAME}} pigkokonsiderar na $2 (hilingón an $1 para sa mga detalye). Kun habò mong mahirá an saimomg sinurat na mayong pakimàno, dai tabì iyan isumiter digdi.<br />
Pigpropromesa mo man samuyà na ika an kagsurat kaini, o kinopya mo ini sa dominiong panpubliko o sarong parehong libreng rekurso (hilingón an $1 para sa mga detalye).
'''DAI TABÌ MAGSUMITIR NIN MGA GIBONG IPINAPANGALAD NA KOPYAHON NIN MAYONG PERMISO!'''",
'copyrightwarning2'          => "Giromdomon tabì na an gabos na kontribusyon sa {{SITENAME}} pwedeng hirahón, bàgohon o halion kan ibang mga parágamit. Kun habô mong mahirá an saimomg sinurat na mayong pakimàno, pues, dai tabì isumitir iyan digdi.<br />
Pigpapangakò mo man samuyà na ika an nagsurat kaini, o pigkopya mo ini sa dominiong panpubliko o sarong parehong libreng rekurso (hilingon an $1 para sa mga detalye). '''DAI TABÌ MAGSUMITIR NIN MGA GIBONG IPINAPANGALAD NA KOPYAHON NIN MAYONG PERMISO!'''",
'longpagewarning'            => "'''PATANID: $1 na kilobytes na kalabà an pahinang ini; an ibang mga browser pwedeng magkaproblema sa paghirá nin mga pahinang haros o sobra sa 32 kb.
Paki bangâ ini sa saradit na seksyon.'''",
'longpageerror'              => "'''SALÀ: $1 na kilobytes na kalabà an pahinang isinumitir mo, na mas halabà sa hanggan nin $2 na kilobytes. Dai pwede ining itagama.'''",
'readonlywarning'            => "'''PATANID: Nakakandado an base nin datos para sa pagmantinir, pues, dai mo mûna pwede na itagama an mga paghirá mo. Pwede mo pa man na arogon dangan ipaskil ang teksto sa sarong dokumento arog kan MS Word asbp. asin itagama ini para sa atyan.'''",
'protectedpagewarning'       => "'''PATANID:  Nakakandado ining pahina tangarig an mga parágamit na may priblehiyo nin ''sysop'' sana an pwedeng maghira kaini.'''",
'semiprotectedpagewarning'   => "'''Paisi:''' An pahinang ini isinara tangarig mga rehistradong parágamit sana an makahira kaini.",
'cascadeprotectedwarning'    => "'''Patanid:''' Nakakandado an pahinang ini tangarig an mga parágamit na igwang pribilehyo nin sysop sana an pwedeng maghirá kaini, huli ta kabali ini sa mga kataratang protektado na {{PLURAL:$1|pahina|mga pahina}}:",
'templatesused'              => 'Mga templato na piggamit sa pahinang ini:',
'templatesusedpreview'       => 'Mga templato na piggamit sa patànaw na ini:',
'templatesusedsection'       => 'Mga templato na piggamit sa seksyon na ini:',
'template-protected'         => '(protektado)',
'template-semiprotected'     => '(semi-protektado)',
'edittools'                  => '<!-- An teksto digdi mahihiling sa babâ kan mga pormang pighihirá asin pigkakarga. -->',
'nocreatetitle'              => 'Limitado an paggibo nin pahina',
'nocreatetext'               => 'Igwang pagpogol sa paggibo nin bàgong pahina sa site na ini.
Pwede kang bumalik dangan maghirá nin presenteng pahina, o [[Special:UserLogin|maglaog o magbukas nin account]].',
'nocreate-loggedin'          => 'Mayò ka nin permiso na maggibo nin mga bàgong pahina sa wiki na ini.',
'permissionserrors'          => 'Mga saláng Permiso',
'permissionserrorstext'      => 'Mayò ka nin permiso na gibohon yan, sa minasunod na {{PLURAL:$1|rason|mga rason}}:',
'recreate-moveddeleted-warn' => "'''Patanid: Piggigíbo mo giraray an pahina na pigparà na dati pa.'''

Dapat mong isipon kun kaipuhan na ipadagos an paghirá kaining pahina.
An paghalì kan historial para sa pahinang ini yaon digdi para sa saimong kombenyensya:",
'edit-conflict'              => 'Igwang iregularidad sa pagliwat.',
'edit-already-exists'        => 'Dai maggibo an bàgong pahina.
Igwa na kaini.',

# "Undo" feature
'undo-success' => 'Pwedeng bawion an paghirá. Sosogon tabì an pagkakaiba sa babâ tangarig maberipikár kun ini an boot mong gibohon, dangan itagama an mga pagbabàgo sa babâ tangarig tapuson an pagbawì sa paghirá.',
'undo-failure' => 'Dai napogol an paghirá ta igwa pang ibang paghirá sa tahaw na nagkokomplikto.',
'undo-summary' => 'Bawion an pagpakaraháy na $1 ni [[Special:Contributions/$2|$2]] ([[User talk:$2|Pag-oláyan]])',

# Account creation failure
'cantcreateaccounttitle' => 'Dai makagibo nin account',
'cantcreateaccount-text' => "An pagbukas nin account halì sa IP na ('''$1''') binágat ni [[User:$3|$3]].

''$2'' an rason na pigtao ni $3",

# History pages
'viewpagelogs'           => 'Hilingón an mga usip para sa pahinang ini',
'nohistory'              => 'Mayong paghirá nin uusipón sa pahinang ini.',
'currentrev'             => 'Sa ngonyan na pagpakarháy',
'revisionasof'           => 'Pagpakarháy sa $1',
'revision-info'          => 'An pagpakarháy sa $1 ni $2',
'previousrevision'       => '←Orog na lumang pagpakarhay',
'nextrevision'           => 'Mas bàgong pagpakarháy→',
'currentrevisionlink'    => 'Sa ngonyan na pagpakarháy',
'cur'                    => 'ngonyan',
'next'                   => 'sunod',
'last'                   => 'huri',
'page_first'             => 'enot',
'page_last'              => 'huri',
'histlegend'             => 'Kaib na pinili: markahán an mga kahon kan mga bersyon tangarig makomparar asin pindoton an enter o butones babâ.<br />
Legend: (ngonyan) = kaibhán sa ngonyan na bersyon,
(huri) = kaibhán sa huring bersyon, S = saradít na paghirá.',
'history-fieldset-title' => 'Rinsayon an uusipon',
'histfirst'              => 'Pinakaenot',
'histlast'               => 'Pinakahúri',
'historysize'            => '($1 bytes)',
'historyempty'           => '(mayong laog)',

# Revision feed
'history-feed-title'          => 'Uusipón kan pagpakaraháy',
'history-feed-description'    => 'Uusipón kan pagpakaraháy para sa pahinang ini sa wiki',
'history-feed-item-nocomment' => '$1 sa $2',
'history-feed-empty'          => 'Mayò man an hinágad na pahina.
Pwedeng pigparà na ini sa wiki, o tinàwan nin bàgong pangaran.
Probaran tabì an [[Special:Search|pighahanap sa wiki]] para sa mga pahinang dapít.',

# Revision deletion
'rev-deleted-comment'         => '(hinalì an komento)',
'rev-deleted-user'            => '(hinalì an parágamit)',
'rev-deleted-event'           => '(hinalì an ingreso)',
'rev-deleted-text-permission' => 'Ining pagpakaraháy nin pahina pighalì na sa mga archibong panpubliko.
Pwedeng igwang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} historial kan pagparà].',
'rev-deleted-text-view'       => 'Ining pagpakaraháy nin pahina pighalì na sa mga archibong panpubliko.
Pwede mo ining hilingón bilang sarong tagamató kaining site;
Pwedeng igwang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} historial kan pagparà].',
'rev-delundel'                => 'ipahilíng/tagoon',
'revisiondelete'              => 'Paraon/bawion an mga pagpakaraháy',
'revdelete-nooldid-title'     => 'Mayong tunggit pagpakaraháy',
'revdelete-nooldid-text'      => 'Dai ka nagpili nin target na pagpakarhay o mga pagpakarhay tangarig magamit ini.',
'revdelete-selected'          => "'''{{PLURAL:$2|Selected revision|Selected revisions}} kan [[:$1]]'''",
'revdelete-text'              => "'''An mga pagpakarhay asin mga panyayari na pigparâ mahihiling pa sa historya asin mga historial kan páhina, pero an ibang parte kan mga laog kaini dai na ipapahiling sa publiko.'''

An ibang mga administrador sa ining wiki pwede pang maghiling kan mga nakatagong laog asin pwede pa nindang bawîon an pagparâ kaini sa paggamit kan parehong ''interface'', kun mayô pang mga ibang restriksyón.",
'revdelete-legend'            => 'Ipwesto an mga restriksyón',
'revdelete-hide-text'         => 'Tagoon an teksto kan pagpakaraháy',
'revdelete-hide-image'        => 'Tagoon an laog kan file',
'revdelete-hide-name'         => 'Tagoon an aksyon asin target',
'revdelete-hide-comment'      => 'Tagoon an komento sa paghirá',
'revdelete-hide-user'         => 'Tagoon an pangaran kan editor/IP',
'revdelete-hide-restricted'   => 'Ibali sa mga restriksyón na ini an mga sysops asin iba pa',
'revdelete-suppress'          => 'Dai ipahilíng an mga datos sa mga sysops asin sa mga iba pa',
'revdelete-unsuppress'        => 'Halîon an mga restriksyón sa mga ibinalík na pagpakarhay',
'revdelete-log'               => 'Rason:',
'revdelete-submit'            => 'Ibílang sa piniling pagpakarhay',
'revdelete-logentry'          => 'pigribayan an bisibilidad nin panyayari kan $1 [[$1]]',
'logdelete-logentry'          => 'Pigribayan an bisibilidad nin panyayari kan [[$1]]',
'revdelete-success'           => "'''Nakapwesto na an bisibilidad kan pagpakarhay.'''",
'logdelete-success'           => "'''Nakapuesto na an katalâan kan nangyari.'''",
'revdelete-uname'             => 'paragamit',

# Merge log
'revertmerge' => 'Suwayón',

# Diffs
'history-title'           => 'Uusipón nin pagpakarháy kan "$1"',
'difference'              => '(Kaibhan kan mga pagpakarhay)',
'lineno'                  => 'Linya $1:',
'compareselectedversions' => 'Ikomparar an mga piniling bersyon',
'editundo'                => 'ibalik',
'diff-multi'              => '({{PLURAL:$1|One intermediate revision|$1 intermediate revisions}} dai ipinahihiling.)',

# Search results
'searchresults'             => 'Hanapon an mga resulta',
'searchresults-title'       => 'Hanápon an resulta para sa "$1"',
'searchresulttext'          => 'Para sa iba pang impormasyon manonongod sa paghanap sa {{SITENAME}}, hilingon tabî an [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => "Hinanap mo an '''[[:$1]]'''",
'searchsubtitleinvalid'     => "Hinanap mo an '''$1'''",
'toomanymatches'            => 'Kadakol-dakol na angay an ipigbalik, probaran an ibang kahaputan',
'titlematches'              => 'Angay an título kan artíkulo',
'notitlematches'            => 'Mayong angay na título nin pahina',
'textmatches'               => 'Angay an teksto nin páhina',
'notextmatches'             => 'Mayong ángay na teksto nin páhina',
'prevn'                     => 'dating {{PLURAL:$1|$1}}',
'nextn'                     => 'sunod na {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Hilingón ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:Mga laog',
'search-result-size'        => '$1 ({{PLURAL:$2|1 tatarámon|$2 mga tatarámon}})',
'search-suggest'            => 'An boot mo: $1',
'search-interwiki-more'     => '(dakol pa)',
'search-mwsuggest-enabled'  => 'igwang mga suhestyon',
'search-mwsuggest-disabled' => 'mayong suhestyon',
'searchall'                 => 'gabos',
'showingresults'            => "Pigpapahiling sa babâ sagkod sa {{PLURAL:$1|'''1''' resulta|'''$1''' mga resulta}} poon sa #'''$2'''.",
'showingresultsnum'         => "Pigpapahiling sa babâ {{PLURAL:$3|'''1''' resulta|'''$3''' mga resulta}} poon sa #'''$2'''.",
'nonefound'                 => "'''Pagiromdom''': An mga prakasong paghanap pirmeng kawsa kan paghanap kan mga tataramon na komún arog kan \"may\" asin \"sa\", huli ta an mga ini dai nakaíndise, o sa pagpili kan sobra sa sarong tataramon (an mga páhina sana na igwá kan gabos na pighahanap na tataramon an maipapahiling sa resulta).",
'powersearch'               => 'Pinaoróg na paghánap',
'powersearch-field'         => 'Hanápon an',
'searchdisabled'            => 'Pigpopogolan mûna an paghanap sa {{SITENAME}}. Mientras tanto, pwede ka man maghanap sa Google. Giromdomon tabî na an mga indise kan laog ninda sa {{SITENAME}} pwede ser na lumâ na.',

# Quickbar
'qbsettings'               => 'Quickbar',
'qbsettings-none'          => 'Mayô',
'qbsettings-fixedleft'     => 'Nakatakód sa walá',
'qbsettings-fixedright'    => 'Nakatakód sa tûo',
'qbsettings-floatingleft'  => 'Naglálatáw sa walá',
'qbsettings-floatingright' => 'Naglálatáw sa tûo',

# Preferences page
'preferences'               => 'Mga kabòtan',
'mypreferences'             => 'Mga kabòtan ko',
'prefs-edits'               => 'Bilang kan mga hirá:',
'prefsnologin'              => 'Dai nakalaog',
'prefsnologintext'          => 'Ika dapat si [[Special:UserLogin|nakalaog]] para makapwesto nin mga kabôtan nin parágamit.',
'changepassword'            => 'Ribayan an sekretong panlaog',
'prefs-skin'                => "''Skin''",
'skin-preview'              => 'Tânawon',
'prefs-math'                => 'Mat',
'datedefault'               => 'Mayong kabôtan',
'prefs-datetime'            => 'Petsa asin oras',
'prefs-personal'            => 'Pambisto nin parágamit',
'prefs-rc'                  => 'Mga kaaagi pa sanang pagribay',
'prefs-watchlist'           => 'Pigbabantayan',
'prefs-watchlist-days'      => 'Máximong número nin mga aldaw na ipapahiling sa lista nin mga pigbabantayan:',
'prefs-watchlist-edits'     => 'Máximong número nin pagbabâgo na ipapahiling sa pinadakulang lista nin pigbabantayan:',
'prefs-misc'                => 'Lain',
'saveprefs'                 => 'Itagama',
'resetprefs'                => 'Ipwesto giraray',
'prefs-editing'             => 'Pighihira',
'rows'                      => 'Mga hilera:',
'columns'                   => 'Mga taytay:',
'searchresultshead'         => 'Hanápon',
'resultsperpage'            => 'Mga tamà kada pahina:',
'contextlines'              => 'Mga linya kada tamà:',
'contextchars'              => 'Konteksto kada linya:',
'stub-threshold'            => 'Kasagkoran kan <a href="#" class="stub">takod kan tambô</a> pigpopormato:',
'recentchangesdays'         => 'Mga aldáw na ipapahilíng sa mga nakakaági pa sanáng pagbabàgó:',
'recentchangescount'        => 'Bilang nin mga paghirá na ipapahilíng sa mga nakakaági pa sanáng pagbabàgó:',
'savedprefs'                => 'Itinagama na an mga kabôtan mo.',
'timezonelegend'            => 'Zona nin oras',
'localtime'                 => 'Lokal na oras',
'servertime'                => "Oras kan ''server''",
'guesstimezone'             => "Bugtakan an ''browser''",
'allowemail'                => "Togotan an mga ''e''-surat halî sa ibang mga parágamit",
'defaultns'                 => 'Maghilíng mûna sa ining mga ngaran-espacio:',
'default'                   => 'pwestong normal',
'prefs-files'               => 'Mga dokumento',
'youremail'                 => 'E-koreo:',
'username'                  => 'Pangaran kan parágamit:',
'uid'                       => 'ID kan parágamit:',
'prefs-memberingroups'      => 'Miembro kan {{PLURAL:$1|grupo|grupos}}:',
'yourrealname'              => 'Totoong pangaran:',
'yourlanguage'              => 'Tataramon:',
'yourvariant'               => 'Bariante:',
'yournick'                  => 'Gahâ:',
'badsig'                    => 'Dai pwede an bâgong pirmang ini; isúsog an mga HTML na takód.',
'badsiglength'              => 'Halabâon an gahâ; kaipuhan dai mababà sa $1 na mga karakter.',
'gender-male'               => 'Lalaki',
'gender-female'             => 'Babai',
'email'                     => 'E-koreo',
'prefs-help-realname'       => 'Opsyonal an totoong pangaran asin kun itatao mo ini, gagamiton ini yangarig an mga sinurat mo maatribuir saimo.',
'prefs-help-email'          => 'Opsyonal an e-koreo, alagad pwede ka na masosog kan iba sa paagi kan saimong pahina o pahina nin olay na dai kinakaipuhan na ipabisto an identidad mo.',
'prefs-help-email-required' => 'Kaipuhan an e-koreo.',

# User rights
'userrights'               => 'Pagmaneho kan mga derecho nin paragamit',
'userrights-lookup-user'   => 'Magmaného kan mga grupo nin parágamit',
'userrights-user-editname' => 'Ilaog an pangaran kan parágamit:',
'editusergroup'            => 'Hirahón an mga Grupo kan Parágamit',
'editinguser'              => "Pighihira an parágamit na '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Hirahón an mga grupo kan parágamit',
'saveusergroups'           => 'Itagama an mga Grupo nin Páragamit',
'userrights-groupsmember'  => 'Myembro kan:',
'userrights-reason'        => 'Rason:',

# Groups
'group'               => 'Grupo:',
'group-autoconfirmed' => 'Paragamit na sadiring nagkonpirma',
'group-bot'           => 'Mga bots',
'group-sysop'         => 'Mga sysop',
'group-bureaucrat'    => 'Mga bureaucrat',
'group-all'           => '(gabos)',

'group-autoconfirmed-member' => 'Enseguidang nakonpirmar na parágamit',
'group-sysop-member'         => 'Opsys',
'group-bureaucrat-member'    => 'Bureaucrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Mga enseguidang nakonpirmar na parágamit',
'grouppage-bot'           => '{{ns:project}}:Mga bot',
'grouppage-sysop'         => '{{ns:project}}:Mga tagamató',
'grouppage-bureaucrat'    => '{{ns:project}}:Mga bureaucrat',

# User rights log
'rightslog'      => 'Usip nin derechos nin paragamit',
'rightslogtext'  => 'Ini an historial kan mga pagbabâgo sa mga derecho nin parágamit.',
'rightslogentry' => 'Rinibayab an pagkamyembro ni $1 sa $2 sagkod sa $3',
'rightsnone'     => '(mayô)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'liwatón ining pahina',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pagbabâgo|mga pagbabâgo}}',
'recentchanges'                     => 'Mga nakakaági pa sanáng pagbabàgó',
'recentchangestext'                 => 'Hanapon an mga pinahuring pagbabâgo sa wiki digdi sa páhinang ini.',
'recentchanges-feed-description'    => 'Hanápon an mga pinakahuring pagbabàgo sa wiki sa hungit na ini.',
'rcnote'                            => "Mahihiling sa babâ an {{PLURAL:$1| '''1''' pagbabàgo|'''$1''' pagbabàgo}} sa huring {{PLURAL:$2|na aldaw|'''$2''' na aldaw}}, sa $3.",
'rcnotefrom'                        => "Mahihiling sa babâ an mga pagbabàgo poon kan '''$2''' (hasta '''$1''' ipinapahiling).",
'rclistfrom'                        => 'Ipahilíng an mga pagbabàgo poon sa $1',
'rcshowhideminor'                   => '$1 saradit na pagligwat',
'rcshowhidebots'                    => '$1 mga bot',
'rcshowhideliu'                     => '$1 mga nakadágos na paragamit',
'rcshowhideanons'                   => '$1 mga dai bistong paragamit',
'rcshowhidepatr'                    => '$1 pigbabantayan na mga pagliwat',
'rcshowhidemine'                    => '$1 mga pagliwat ko',
'rclinks'                           => 'Ipahilíng an $1 huring pagbabàgo sa ultimong $2 aldaw<br />$3',
'diff'                              => 'ibá',
'hist'                              => 'usip',
'hide'                              => 'Tagóon',
'show'                              => 'Ipahilíng',
'minoreditletter'                   => 's',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 nagbabantay na parágamit]',
'rc_categories'                     => 'Limitado sa mga kategorya (suhayon nin "|")',
'rc_categories_any'                 => 'Dawà arín',
'newsectionsummary'                 => '/* $1 */ bàgong seksyon',

# Recent changes linked
'recentchangeslinked'          => 'Mga angay na pagbabàgo',
'recentchangeslinked-feed'     => 'Mga angay na pagbabàgo',
'recentchangeslinked-toolbox'  => 'Mga angay na pagbabàgo',
'recentchangeslinked-title'    => 'Mga pagbabàgong angay sa "$1"',
'recentchangeslinked-noresult' => 'Warang mga pagbabago sa mga pahinang nakatakod sa itinaong pagkalawig.',
'recentchangeslinked-summary'  => "Ini an lista nin mga pagsangli na ginibo pa sana sa mga pahinang nakatakod halì sa sarong espesyal na pahina (o sa mga myembro nin sarong espesyal na kategorya).
'''Maitom''' an mga pahinang [[Special:Pigbabantayan|pigbabantayan mo]].",

# Upload
'upload'                      => 'Ikargá an file',
'uploadbtn'                   => 'Ikargá an file',
'reuploaddesc'                => 'Magbalik sa pormulario kan pagkarga.',
'uploadnologin'               => 'Dai nakalaog',
'uploadnologintext'           => "Kaipuhan ika si [[Special:UserLogin|nakadagos]]
para makakarga nin mga ''file''.",
'upload_directory_read_only'  => 'An directoriong pagkarga na ($1) dai puedeng suratan kan serbidor nin web.',
'uploaderror'                 => 'Salâ an pagkarga',
'uploadtext'                  => "Gamiton tabî an pormulario sa babâ para magkarga nin mga ''file'', para maghiling o maghanap kan mga ladawan na dating kinarga magduman tabi sa [[Special:FileList|lista nin mga pigkargang ''file'']], an mga kinarga asin mga pinarâ nakalista man sa [[Special:Log/upload|historial nin pagkarga]].

Kun boot mong ikaag an ladawan sa páhina, gamiton tabî an takod arog kan
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki>''' o
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' para sa direktong pagtakod sa ''file''.",
'uploadlog'                   => 'historial nin pagkarga',
'uploadlogpage'               => 'Ikarga an usip',
'uploadlogpagetext'           => "Mahihiling sa babâ an lista kan mga pinakahuring ''file'' na kinarga.",
'filename'                    => 'Pangaran kan dokumento',
'filedesc'                    => 'Kagabsan',
'fileuploadsummary'           => 'Kagabsan:',
'filestatus'                  => 'Estatutong derechos nin paragamit:',
'filesource'                  => 'Ginikanan',
'uploadedfiles'               => "Mga ''file'' na ikinargá",
'ignorewarning'               => 'Dai pagintiendehon an mga patanid asin itagama pa man an file',
'ignorewarnings'              => 'Paliman-limanon an mga tanid',
'minlength1'                  => "An pangaran kan mga ''file'' dapat na dai mababâ sa sarong letra.",
'illegalfilename'             => "An ''filename'' na \"\$1\" igwang mga ''character'' na dai pwede sa mga titulo nin páhina. Tâwan tabî nin bâgong pangaran an ''file'' asin probaran na ikarga giraray.",
'badfilename'                 => "Rinibayan an ''filename'' nin \"\$1\".",
'filetype-badmime'            => "Dai pigtotogotan na ikarga an mga ''file'' na MIME na \"\$1\" tipo.",
'filetype-missing'            => "Mayong ekstensyón an ''file'' (arog kan \".jpg\").",
'large-file'                  => "Pigrerekomendár na dapat an mga ''file'' bakong mas dakula sa $1; $2 an sokol kaining ''file''.",
'largefileserver'             => "Mas dakula an ''file'' sa pigtotogotan na sokol kan ''server''.",
'emptyfile'                   => "Garo mayong laog an ''file'' na kinarga mo. Pwede ser na salâ ining tipo nin ''filename''. Isegurado tabî kun talagang boot mong ikarga ining ''file''.",
'fileexists'                  => "Igwa nang ''file'' na may parehong pangaran sa ini, sosogon tabî an '''<tt>[[:$1]]</tt>''' kun dai ka seguradong ribayan ini.
[[$1|thumb]]",
'fileexists-extension'        => "May ''file'' na may parehong pangaran: [[$2|thumb]]
* Pangaran kan pigkakargang ''file'': '''<tt>[[:$1]]</tt>'''
* Pangaran kan yaon nang ''file'': '''<tt>[[:$2]]</tt>'''
Magpili tabî nin ibang pangaran.",
'fileexists-thumbnail-yes'    => "An ''file'' garo ladawan kan pinasadit ''(thumbnail)''. [[$1|thumb]]
Sosogon tabî an ''file'' '''<tt>[[:$1]]</tt>'''.
Kun an sinosog na ''file'' iyo an parehong ladawan na nasa dating sokol, dai na kaipuhan magkarga nin iba pang retratito.",
'file-thumbnail-no'           => "An ''filename'' nagpopoon sa '''<tt>$1</tt>'''. Garo ladawan na pinasadit ini ''(thumbnail)''.
Kun igwa ka nin ladawan na may resolusyón na maximo ikarga tabî ini, kun dai, bâgohon tabî an pangaran nin ''file''.",
'fileexists-forbidden'        => "Igwa nang ''file'' na may parehong pangaran; bumalik tabi asin ikarga an ''file'' sa bâgong pangaran [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Igwa nang ''file'' na may parehong pangaran sa repositoryo nin mga bakas na ''file''; bumalik tabî asin ikarga an ''file'' sa bâgong pangaran. [[File:$1|thumb|center|$1]]",
'uploadwarning'               => 'Patanid sa pagkarga',
'savefile'                    => "Itagama an ''file''",
'uploadedimage'               => 'Ikinarga "[[$1]]"',
'overwroteimage'              => 'kinarga an bagong bersión kan "[[$1]]"',
'uploaddisabled'              => 'Pigpopondó an mga pagkargá',
'uploaddisabledtext'          => "Pigpopogolan an pagkarga nin mga ''file'' o sa ining wiki.",
'uploadscripted'              => "Ining ''file'' igwang HTML o kodang eskritura na pwede ser na salang mainterpretar kan ''browser''.",
'uploadvirus'                 => "May virus an ''file''! Mga detalye: $1",
'sourcefilename'              => 'Ginikanan kan pangaran kan dokumento',
'destfilename'                => "''Filename'' kan destinasyón",
'watchthisupload'             => 'Bantayan ining pahina',
'filewasdeleted'              => "May sarong ''file'' na kapangaran kaini na dating pigkarga tapos pigparâ man sana. Sosogon muna tabî an $1 bago ikarga giraray ini.",
'upload-wasdeleted'           => "'''Patanid: Pigkakarga mo an ''file'' na dati nang pigparâ.'''

Isipon tabi kun maninigo an pagkarga giraray kaini.
An historial nin pagparâ kan ''file'' nakakaag digdi para sa konbenyensya:",
'filename-bad-prefix'         => "An pangaran nin ''file'' na pigkakarga mo nagpopoon sa '''\"\$1\"''', sarong pangaran na dai makapaladawan na normalmente enseguidang pigtatao kan mga kamerang digital. Magpili tabî nin pangaran nin ''file'' na mas makapaladawan.",
'upload-success-subj'         => 'Nakarga na',

'upload-proto-error'      => 'Salang protocolo',
'upload-proto-error-text' => 'An pagkargang panharayo kaipuhan nin mga URLs na nagpopoon sa  <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Panlaog na salâ',
'upload-file-error-text'  => "May panlaog na salâ kan pagprobar na maggibo nin temporaryong ''file'' sa ''server''.  Apodon tabî an administrador nin sistema.",
'upload-misc-error'       => 'Dai naaaram na error sa pagkarga',
'upload-misc-error-text'  => 'May salang panyayari na dai aram kan pagkarga.  Sosogon tabî kun tamâ an URL asin probaran giraray.  Kun an problema nagpeperseguir, apodon tabî an sarong administrador nin sistema.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Dai naabot an URL',
'upload-curl-error6-text'  => 'Dai nabukas an URL na tinao.  Susugon tabi giraray na an  URL tama asin an sitio bakong raot.',
'upload-curl-error28'      => 'sobra na an pagkalawig kan pagkarga',
'upload-curl-error28-text' => 'Sobrang haloy an pagsimbag kan sitio. Susugon tabi na nagaandar an sitio, maghalat nin muna asin iprobar giraray. Tibaad moot mong magprobar sa panahon na bako masiadong okupado.',

'license'            => 'Paglilisensia',
'license-header'     => 'Paglilisensia',
'nolicense'          => 'Mayong pigpilî',
'license-nopreview'  => '(Mayong patânaw)',
'upload_source_url'  => ' (sarong tama, na bukas sa publikong URL)',
'upload_source_file' => " (sarong ''file'' sa kompyuter mo)",

# Special:ListFiles
'listfiles_search_for'  => 'Hanápon an pangaran kan retrato:',
'imgfile'               => 'dokumento',
'listfiles'             => 'Lista kan dokumento',
'listfiles_date'        => 'Petsa',
'listfiles_name'        => 'Pangaran',
'listfiles_user'        => 'Parágamit',
'listfiles_size'        => 'Sukol',
'listfiles_description' => 'Deskripsión',

# File description page
'file-anchor-link'          => 'File',
'filehist'                  => 'Uusipón nin file',
'filehist-help'             => 'Magpindot kan petsa/oras para mahiling an hitsura kan file sa piniling oras.',
'filehist-deleteall'        => 'parâon gabos',
'filehist-deleteone'        => 'parâon ini',
'filehist-revert'           => 'ibalik',
'filehist-current'          => 'ngonyan',
'filehist-datetime'         => 'Petsa/Oras',
'filehist-user'             => 'Paragamit',
'filehist-dimensions'       => 'Mga dimensyón',
'filehist-filesize'         => 'Sokol nin file',
'filehist-comment'          => 'Komento',
'imagelinks'                => 'Mga takód',
'linkstoimage'              => 'An mga minasunod na pahina nakatakod sa dokumentong ini:',
'nolinkstoimage'            => 'Mayong mga pahinang nakatakod sa dokumentong ini.',
'sharedupload'              => "Ining ''file'' sarong bakas na pagkarga asin pwede ser na gamiton kan ibang mga proyekto.",
'uploadnewversion-linktext' => 'Magkarga nin bàgong bersyon kaining file',

# File reversion
'filerevert'                => 'Ibalik an $1',
'filerevert-legend'         => 'Ibalik an dokumento',
'filerevert-intro'          => "Pigbabalik mo an '''[[Media:$1|$1]]''' sa [$4 version as of $3, $2].",
'filerevert-comment'        => 'Komento:',
'filerevert-defaultcomment' => 'Pigbalik sa bersyon sa ngonyan $2, $1',
'filerevert-submit'         => 'Ibalik',
'filerevert-success'        => "'''[[Media:$1|$1]]''' binalik sa [$4 version as of $3, $2].",
'filerevert-badversion'     => "Mayong dating bersyón na lokal kaining ''file'' na may taták nin oras na arog sa tinao.",

# File deletion
'filedelete'             => 'Parâon an $1',
'filedelete-legend'      => 'Parâon an dokumento',
'filedelete-intro'       => "Pigpaparâ mo an '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => "Pigpaparâ mo an bersyon kan '''[[Media:$1|$1]]''' sa ngonyan [$4 $3, $2].",
'filedelete-comment'     => 'Rason:',
'filedelete-submit'      => 'Parâon',
'filedelete-success'     => "An '''$1''' pinarâ na.",
'filedelete-success-old' => '<span class="plainlinks">An bersyón kan \'\'\'[[Media:$1|$1]]\'\'\' na ngonyan na $3, pigparâ na an $2.</span>',
'filedelete-nofile'      => "Mayo man an '''$1''' sa ining sitio.",
'filedelete-nofile-old'  => "Mayong bersyón na nakaarchibo kan '''$1''' na igwang kan mga piniling ''character''.",

# MIME search
'mimesearch'         => 'Paghanap kan MIME',
'mimesearch-summary' => "An gamit kaining páhina sa pagsasarâ kan mga ''file'' segun sa mga tipo nin MIME. Input: contenttype/subtype, e.g. <tt>image/jpeg</tt>.",
'mimetype'           => 'tipo nin MIME:',
'download'           => 'ideskarga',

# Unwatched pages
'unwatchedpages' => 'Dai pigbabantayan na mga pahina',

# List redirects
'listredirects' => 'Lista nin mga paglikay',

# Unused templates
'unusedtemplates'     => 'Mga templatong dai ginamit',
'unusedtemplatestext' => 'Piglilista kaining páhina an gabos na mga páhina sa templatong ngaran-espacio na dai nakakaag sa ibang páhina. Giromdomon tabî na sosogon an ibang mga takod sa mga templato bâgo parâon iyan.',
'unusedtemplateswlh'  => 'ibang mga takod',

# Random page
'randompage'         => 'Dawà arin na pahina',
'randompage-nopages' => 'Mayong páhina an ngaran-espacio.',

# Random redirect
'randomredirect'         => 'Random na pagredirekta',
'randomredirect-nopages' => 'Mayong paglikay (redirects) didgi sa ngaran-espacio.',

# Statistics
'statistics'              => 'Mga Estadistiko',
'statistics-header-users' => 'Mga estadistiko nin parágamit',
'statistics-mostpopular'  => 'mga pinaka pighiling na pahina',

'disambiguations'      => 'Mga pahinang klaripikasyon',
'disambiguationspage'  => 'Template:clarip',
'disambiguations-text' => "An mga nasunod na páhina nakatakod sa sarong '''páhina nin klaripikasyon'''.
Imbis, kaipuhan na nakatakod sinda sa maninigong tema.<br />
An páhina pigkokonsiderar na páhina nin klaripikasyon kun naggagamit ini nin templatong nakatakod sa [[MediaWiki:Disambiguationspage]]",

'doubleredirects'     => 'Dobleng mga redirekta',
'doubleredirectstext' => 'Piglilista kaining pahina an mga pahinang minalikay sa ibang pahinang paralikay. Kada raya may mga takod sa primero asin segundang likay, buda an destino kan segundong likay, na puro-pirme sarong "tunay " na pahinang destino, na dapat duman nakaturo an primerong likay.',

'brokenredirects'        => 'Putol na mga paglikay',
'brokenredirectstext'    => 'An nagsusunod naglilikay kan takod sa mga pahinang mayo man:',
'brokenredirects-edit'   => 'hirahón',
'brokenredirects-delete' => 'parâon',

'withoutinterwiki'         => 'Mga pahinang dai nin mga takod sa ibang tataramon',
'withoutinterwiki-summary' => 'An mga nagsusunod na páhina dai nakatakód sa mga bersión na ibang tataramón:',
'withoutinterwiki-submit'  => 'Ipahiling',

'fewestrevisions' => 'Mga artikulong may pinakadikit na pagpakarháy',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|mga byte}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorya|mga kategorya}}',
'nlinks'                  => '$1 {{PLURAL:$1|takod|mga takod}}',
'nmembers'                => '$1 {{PLURAL:$1|myembro|mga myembro}}',
'nrevisions'              => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'nviews'                  => '$1 {{PLURAL:$1|hiling|mga hiling}}',
'specialpage-empty'       => 'Mayong mga resulta para sa report na ini.',
'lonelypages'             => 'Mga solong pahina',
'lonelypagestext'         => 'An mga minasunod na mga páhina dai nakatakod sa ibang mga páhina sa wiki na ini.',
'uncategorizedpages'      => 'Mga dai nakakategoryang páhina',
'uncategorizedcategories' => 'Mga dai nakakategoryang kategorya',
'uncategorizedimages'     => 'Mga dai nakakategoryang retrato',
'uncategorizedtemplates'  => 'Mga templatong mayong kategorya',
'unusedcategories'        => 'Dai gamit na mga kategorya',
'unusedimages'            => 'Mga dokumentong dai nagamit',
'popularpages'            => 'Mga popular na páhina',
'wantedcategories'        => 'Mga hinahanap na kategorya',
'wantedpages'             => 'Mga hinahanap na pahina',
'mostlinked'              => 'Pinakapigtatakodan na mga pahina',
'mostlinkedcategories'    => 'Pinakapigtatakodan na mga kategorya',
'mostlinkedtemplates'     => 'An mga pinakanatakodan na templato',
'mostcategories'          => 'Mga artikulong may pinaka dakol na kategorya',
'mostimages'              => 'Pinakapigtatakodan na files',
'mostrevisions'           => 'Mga artikulong may pinakadakol na pagpakarháy',
'prefixindex'             => 'Murô nin prefiho',
'shortpages'              => 'Haralìpot na pahina',
'longpages'               => 'Mga halabang pahina',
'deadendpages'            => 'Mga pahinang mayong luwasan',
'deadendpagestext'        => 'An mga nagsusunod na pahina dai nakatakod sa mga ibang pahina sa ining wiki.',
'protectedpages'          => 'Mga protektadong pahina',
'protectedpagestext'      => 'An mga minasunod na pahina protektado na ibalyó o hirahón',
'protectedpagesempty'     => 'Mayong pang páhina an napoprotehiran kaining mga parametros.',
'listusers'               => 'Lista nin paragamit',
'newpages'                => 'Mga bàgong pahina',
'newpages-username'       => 'Pangaran kan parágamit:',
'ancientpages'            => 'Mga pinakalumang pahina',
'move'                    => 'Ibalyó',
'movethispage'            => 'Ibalyó ining pahina',
'unusedimagestext'        => "Giromdomon tabî na an mga ibang ''site'' pwedeng nakatakod sa ladawan na may direktong URL, pues pwede ser na nakalista pa digdi a pesar na ini piggagamit pa.",
'unusedcategoriestext'    => 'Igwa ining mga pahinang kategoria maski mayo man na iba pang pahina o kategoria an naggagamit kaiyan.',
'notargettitle'           => 'Mayong target',
'notargettext'            => 'Dai ka pa nagpili nin pahina o paragamit na muya mong gibohon an accion na ini.',

# Book sources
'booksources'               => 'Ginikanang libro',
'booksources-search-legend' => 'Maghanap nin mga ginikanang libro',
'booksources-go'            => 'Dumanán',
'booksources-text'          => "Mahihiling sa babâ an lista kan mga takod sa ibang ''site'' na nagbenbenta nin mga bâgo asin nagamit nang libro, asin pwede ser na igwa pang mga ibang impormasyon manonongod sa mga librong pighahanap mo:",

# Special:Log
'specialloguserlabel'  => 'Paragamit:',
'speciallogtitlelabel' => 'Titulo:',
'log'                  => 'Mga usip',
'all-logs-page'        => 'Gabos na usip',
'alllogstext'          => 'Sinalak na hihilngon kan gabos na historial na igwa sa {{SITENAME}}. Kun boot mong pasaditon an seleksyon magpili tabî nin klase kan historial, ngaran nin parágamit, o páhinang naapektaran.',
'logempty'             => 'Mayong angay na bagay sa historial.',
'log-title-wildcard'   => 'Hanapon an mga titulong napopoon sa tekstong ini',

# Special:AllPages
'allpages'          => 'Gabos na pahina',
'alphaindexline'    => '$1 sagkod sa $2',
'nextpage'          => 'Sunod na pahina ($1)',
'prevpage'          => 'Nakaaging pahina ($1)',
'allpagesfrom'      => 'Ipahiling an mga páhina poon sa:',
'allarticles'       => 'Gabos na mga artikulo',
'allinnamespace'    => 'Gabos na mga páhina ($1 ngaran-espacio)',
'allnotinnamespace' => 'Gabos na mga páhina (na wara sa $1 ngaran-espacio)',
'allpagesprev'      => 'Nakaagi',
'allpagesnext'      => 'Sunod',
'allpagessubmit'    => 'Dumanán',
'allpagesprefix'    => 'Ipahiling an mga pahinang may prefiho:',
'allpagesbadtitle'  => "Dai pwede an tinaong titulo kan páhina o may prefihong para sa ibang tataramon o ibang wiki. Pwede ser na igwa ining sarô o iba pang mga ''character'' na dai pwedeng gamiton sa mga titulo.",
'allpages-bad-ns'   => 'An {{SITENAME}} mayo man na ngaran-espacio na "$1".',

# Special:Categories
'categories'         => 'Mga Kategorya',
'categoriespagetext' => 'Igwa nin laog ang mga minasunod na kategorya.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:DeletedContributions
'deletedcontributions'       => 'Parâon an mga kontribusyon kan parágamit',
'deletedcontributions-title' => 'Parâon an mga kontribusyon kan parágamit',

# Special:LinkSearch
'linksearch'      => 'Mga panluwas na takod',
'linksearch-ok'   => 'Hanápon',
'linksearch-line' => '$1 an nakatakod sa $2',

# Special:ListUsers
'listusersfrom'      => 'Ipahiling an mga paragamit poon sa:',
'listusers-submit'   => 'Ipahiling',
'listusers-noresult' => 'Mayong nakuang parágamit.',

# Special:Log/newusers
'newuserlog-create-entry' => 'Bâgong parágamit',

# Special:ListGroupRights
'listgrouprights-group'   => 'Grupo',
'listgrouprights-rights'  => 'Derechos',
'listgrouprights-members' => '(lista kan mga kaapíl)',

# E-mail user
'mailnologin'     => 'Mayong direksyón nin destino',
'mailnologintext' => "Kaipuhan ika si [[Special:UserLogin|nakalaog]]
asin may marhay na ''e''-surat sa saimong [[Special:Preferences|Mga kabôtan]]
para makapadara nin ''e''-surat sa ibang parágamit.",
'emailuser'       => 'E-koreohan ining paragamit',
'emailpage'       => 'E-suratan an parágamit',
'emailpagetext'   => "Kun ining páragamit nagkaag nin marhay ''e''-surat sa saiyang mga kabôtan, an pormulario sa babâ mapadara nin sarong mensahe.
An kinaag mong ''e''-surat sa saimong mga kabôtan nin paragamit mahihiling bilang na \"Hali ki\" kan ''e''-surat, para an recipiente pwedeng makasimbag.",
'usermailererror' => 'Error manonongod sa korreong binalik:',
'defemailsubject' => '{{SITENAME}} e-surat',
'noemailtitle'    => "Mayô nin ''e''-surat",
'noemailtext'     => 'Dai nagpili nin tama na direccion nin e-surat an paragamit,
o habo magresibo nin e-surat sa ibang paragamit.',
'emailfrom'       => 'Poon',
'emailto'         => 'Hasta:',
'emailsubject'    => 'Tema',
'emailmessage'    => 'Mensahe',
'emailsend'       => 'Ipadara',
'emailccme'       => 'E-suratan ako nin kopya kan mga mensahe ko.',
'emailccsubject'  => 'Kopya kan saimong mensahe sa $1: $2',
'emailsent'       => 'Naipadará na an e-surat',
'emailsenttext'   => 'Naipadará na su e-surat mo.',

# Watchlist
'watchlist'            => 'Pigbabantayan ko',
'mywatchlist'          => 'Pigbabantayan ko',
'nowatchlist'          => 'Mayo ka man na mga bagay saimong lista nin pigbabantayan.',
'watchlistanontext'    => 'Mag $1 tabi para mahiling o maghira nin mga bagay saimong lista nin mga pigbabantayan.',
'watchnologin'         => 'Mayô sa laog',
'watchnologintext'     => 'Dapat ika si [[Special:UserLogin|nakalaog]] para puede kang magribay kan saimong lista nin mga pigbabantayán.',
'addedwatch'           => 'Idinugang sa pigbabantayan',
'addedwatchtext'       => "Ining pahina \"[[:\$1]]\" dinugang sa saimong mga [[Special:Watchlist|Pigbabantayan]].
An mga pagbabâgo sa páhinang ini asin sa mga páhinang olay na kapadis kaini ililista digdi,
asin an páhina isusurat nin '''mahîbog''' sa [[Special:RecentChanges|lista nin mga kaaagi pa sanang pagbabâgo]] para madalî ining mahiling.

Kun boot mong halîon an páhina sa pigbabantayan mo sa maabot na panahon, pindoton an \"Pabayaan\" ''side bar''.",
'removedwatch'         => 'Pigtanggal sa pigbabantayan',
'removedwatchtext'     => 'An pahinang "[[:$1]]" pigtanggal sa saimong pigbabantayan.',
'watch'                => 'Bantayán',
'watchthispage'        => 'Bantayan ining pahina',
'unwatch'              => 'Dai pagbantayan',
'unwatchthispage'      => 'Pondohon an pagbantay',
'notanarticle'         => 'Bakong páhina nin laog',
'watchnochange'        => 'Mayo sa saimong mga pigbabantayan an nahira sa laog nin pinahiling na pagkalawig.',
'watchlist-details'    => '{{PLURAL:$1|$1 páhina|$1 mga páhina}} an pigbabantayan dai kabali an mga olay na páhina.',
'wlheader-enotif'      => "* Nakaandar an paising ''e''-surat.",
'wlheader-showupdated' => "* An mga páhinang pigbâgo poon kan huri mong bisita nakasurat nin '''mahîbog'''",
'watchmethod-recent'   => 'Pigsososog an mga kaaagi pa sanang hirá sa mga pigbabantayan na páhina',
'watchmethod-list'     => 'Pigsososog an mga pigbabantayan na páhina para mahiling an mga kaaagi pa sanan paghirá',
'watchlistcontains'    => 'An saimong lista nin pigbabantayan igwang $1 na {{PLURAL:$1|páhina|mga páhina}}.',
'iteminvalidname'      => "May problema sa bagay na '$1', salâ an pangaran...",
'wlnote'               => "Mahihiling sa babâ an {{PLURAL:$1|huring pagriribay|mga huring'''$1''' pagriribay}} sa ultimong {{PLURAL:$2|oras|'''$2''' mga oras}}.",
'wlshowlast'           => 'Ipahilíng an ultimong $1 na oras $2 na aldaw $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Pigbabantayan...',
'unwatching' => 'Dai pigbabantayan...',

'enotif_mailer'                => '{{SITENAME}} Kartero nin isi',
'enotif_reset'                 => 'Markahan an gabos na mga binisitang pahina',
'enotif_newpagetext'           => 'Bâgo ining pahina.',
'enotif_impersonal_salutation' => '{{SITENAME}} parágamit',
'changed'                      => 'pigbâgo',
'created'                      => 'piggibo',
'enotif_subject'               => 'An pahinang {{SITENAME}} na $PAGETITLE binago $CHANGEDORCREATED ni $PAGEEDITOR',
'enotif_lastvisited'           => 'Hilingón an $1 para sa gabos na mga pagbâgo poon kan huring bisita.',
'enotif_lastdiff'              => 'Hilingón an $1 tangarig mahiling an pagbâgong ini.',
'enotif_anon_editor'           => 'dai bistong parágamit $1',
'enotif_body'                  => 'Namómòtan na $WATCHINGUSERNAME,


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
{{fullurl:{{#special:Watchlist}}/edit}}

Komentaryo asin iba pang tabang:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Paraon an pahina',
'confirm'               => 'Kompermaron',
'excontent'             => "Ini an dating laog: '$1'",
'excontentauthor'       => "ini an dating laog: '$1' (asin an unikong kontribuidor si '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'         => "Ini an dating laog bagô blinankohán: '$1'",
'exblank'               => 'mayong laog an páhina',
'delete-legend'         => 'Paraon',
'historywarning'        => 'Patanid: An pahinang paparaon mo igwa nin uusipón:',
'confirmdeletetext'     => 'Paparaon mo sa base nin datos ining pahina kasabay an gabos na mga uusipón kaini.
Konpirmaron tabì na talagang boot mong gibohon ini, nasasabotan mo an mga resulta, asin an piggigibo mo ini konporme sa
[[{{MediaWiki:Policy-url}}]].',
'actioncomplete'        => 'Nagibo na',
'deletedtext'           => 'Pigparà na an "<nowiki>$1</nowiki>" .
Hilingón tabì an $2 para mahiling an lista nin mga kaaagi pa sanang pagparà.',
'deletedarticle'        => 'pigparà an "[[$1]]"',
'dellogpage'            => 'Usip nin pagparà',
'dellogpagetext'        => 'Mahihiling sa babâ an lista kan mga pinakahuring pagparâ.',
'deletionlog'           => 'Historial nin pagparâ',
'reverted'              => 'Ibinalik sa mas naenot na pagpakarhay',
'deletecomment'         => 'Rason:',
'deleteotherreason'     => 'Iba/dugang na rason:',
'deletereasonotherlist' => 'Ibang rason',

# Rollback
'rollback'         => 'Mga paghihira na pabalík',
'rollback_short'   => 'pabalík',
'rollbacklink'     => 'pabalikón',
'rollbackfailed'   => 'Prakaso an pagbalík',
'cantrollback'     => 'Dai pwedeng bawîon an hirá; an huring kontribuidor iyo an unikong parásurat kan páhina.',
'alreadyrolled'    => 'Dai pwedeng ibalik an huring hirá kan [[:$1]]
ni [[User:$2|$2]] ([[User talk:$2|Olay]]); may ibang parágamit na naghirá na o nagbalik na kaini.

Huring hirá ni [[User:$3|$3]] ([[User talk:$3|Olay]]).',
'editcomment'      => "Ini an nakakaag na komentaryo sa paghirá: \"''\$1''\".",
'revertpage'       => 'Binawî na mga paghirá kan [[Special:Contributions/$2|$2]] ([[User talk:$2|Magtaram]]); pigbalik sa dating bersyón ni [[User:$1|$1]]',
'rollback-success' => 'Binawî na mga paghirá ni $1; pigbalik sa dating bersyón ni $2.',

# Edit tokens
'sessionfailure' => "Garo may problema sa paglaog mo;
kinanselár ining aksyón bilang sarong paglikay kontra sa ''session hijacking''.
Pindotón tabî an \"back\" asin ikarga giraray an páhinang ginikanan mo, dangan probarán giraray.",

# Protect
'protectlogpage'              => 'Usip nin proteksyon',
'protectlogtext'              => 'May lista sa baba nin mga kandado asin panbawi kan kandado kan mga páhina. Hilingon an [[Special:ProtectedPages|lista kan mga pigproprotektarán na mga páhina]] para mahiling an lista kan mga proteksión nin mga páhina sa ngunyan na nakabuká.',
'protectedarticle'            => 'protektado "[[$1]]"',
'modifiedarticleprotection'   => 'binago an nibel nin proteksión para sa "[[$1]]"',
'unprotectedarticle'          => 'Warang proteksión an "[[$1]]"',
'protect-title'               => 'Pigpupuesta an nibel nin proteksión sa "$1"',
'prot_1movedto2'              => '[[$1]] piglipat sa [[$2]]',
'protect-legend'              => 'Kompermaron an proteksyon',
'protectcomment'              => 'Rason:',
'protectexpiry'               => 'Mápasó:',
'protect_expiry_invalid'      => 'Dai pwede ining pahanon nin pagpasó.',
'protect_expiry_old'          => 'Nakalihis na an panahon nin pagpasó.',
'protect-text'                => "Pwede mong hilingón asin bàgohon an tangga nin proteksyon digdi para sa pahina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Dai mo pwedeng bâgohon an mga tangga kan proteksyon mientras na ika nababágat. Ini an mga presenteng pwesto kan páhina '''$1''':",
'protect-locked-dblock'       => "Dai puedeng ibalyo an mga nibel kan proteksión ta may actibong kandado sa base nin datos.
Ini an mga puesta sa ngunyan kaining páhina '''$1''':",
'protect-locked-access'       => "Mayong permiso an account mo na magbàgo kan tangga nin proteksyon.
Uya an ngonyan na mga pwesto kan pahinang '''$1''':",
'protect-cascadeon'           => 'Pigproprotektaran ining pahina sa ngonyan ta sabay ini sa mga nasunod na {{PLURAL:$1|pahina, na may|mga pahina, na may}} proteksyong katarata na nakaandar. Pwede mong bàgohon an tangga nin proteksyon kaining pahina, pero mayò ning epekto sa proteksyong katarata.',
'protect-default'             => '(normal)',
'protect-fallback'            => 'Mangipo kan "$1" na permiso',
'protect-level-autoconfirmed' => 'Bagáton an mga paragamit na dai nakarehistro',
'protect-level-sysop'         => 'Para sa mga sysop sana',
'protect-summary-cascade'     => 'katarata',
'protect-expiring'            => 'mápasó sa $1 (UTC)',
'protect-cascade'             => 'Protektarán an mga pahinang nakaiba sa pahinang ini (proteksyon katarata)',
'protect-cantedit'            => 'Dai mo mariribayan an mga tanggá kan proteksyon kaining pahina huli ta mayò ka nin permiso na ligwatón ini.',
'protect-expiry-options'      => '1ng ora:1 hour,1ng aldaw:1 day,1ng semana:1 week,2ng semana:2 weeks,1ng bulan:1 month,3ng bulan:3 months,6 na bulan:6 months,1ng taon:1 year,daing kasagkoran:infinite',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Tanggá nin restriksyon:',
'minimum-size'                => 'Pinaka sadit na sukol',
'maximum-size'                => 'Pinaka dakula na sukol:',
'pagesize'                    => '(oktetos)',

# Restrictions (nouns)
'restriction-edit'   => 'Hirahón',
'restriction-move'   => 'Ibalyó',
'restriction-create' => 'Maggibo',
'restriction-upload' => 'Magkarga',

# Restriction levels
'restriction-level-sysop'         => 'protektado',
'restriction-level-autoconfirmed' => 'semi-protektado',
'restriction-level-all'           => 'maski anong nibel',

# Undelete
'undelete'                     => 'Hilingón ang mga pinarang pahina',
'undeletepage'                 => 'Hilingón asin ibalik an mga pinarang pahina',
'viewdeletedpage'              => 'Hilingón an mga pinarang pahina',
'undeletepagetext'             => 'An mga minasunod na páhina pigparâ na alagad yaon pa sa archibo asin pwedeng ibalik. Dapat limpiahan an archibo kada periodo.',
'undeleteextrahelp'            => "Kun boot mong ibalik an enterong páhina, dai markahan an gabos na mga kahon asin pindoton an '''''Restore'''''. Para magpili nin ibábalik, markahan an mga kahon na boot mong ibalik, asin pindoton an '''''Restore'''''. An pagpindot kan '''''Reset''''' makakalimpya nin kampo kan mga kommento
asin an gabos na mga kahon-marka.",
'undeleterevisions'            => '$1 {{PLURAL:$1|na pagriribay|na mga pagriribay}} na nakaarchibo',
'undeletehistory'              => "Kun ibabalik mo an páhinang ini, an gabos na mga pagribay mabalik sa historial.
Kun igwang piggibong sarong bâgong páhinang may parehong pangaran antes ka pagparâ, an presenteng pagribay maluwas sa historial, asin an presenteng pagribay kan tunay na páhina dai enseguidang mariribayan. Giromdomon man tabî na an mga restriksyon sa mga pagriribay nin ''file'' mawawarâ sa pagbalik.",
'undeleterevdel'               => "Dai madadagos an pagbalik kan pagparâ kun an resulta kaini mapaparâ kan pagribay an nasa páhinang pinaka itaas.
Sa mga kasong ini, dapat halîon an mga marka o dai itâgo an mga pinaka bâgong pigparâ na mga pagribay. Dai ibabalik an mga pagribay kan mga ''file'' na mayo kan permisong hilingon.",
'undeletehistorynoadmin'       => 'Pigparâ na ining péhina. Mahihiling an rason sa epitome sa babâ, kasabay sa mga detalye kan mga parágamit na naghira kaining páhina bago pigparâ. Sa mga administrador sana maipapahiling an mga pagribay sa mismong tekstong ini.',
'undelete-revision'            => 'Pigparâng pagribay ni $3 kan $1 (sa $2):',
'undeleterevision-missing'     => 'Dai pwede o nawawarang pagribay. Pwede ser na salâ an takod, o
binalik an na pagribay o hinalî sa archibo.',
'undeletebtn'                  => 'Ibalik',
'undeletereset'                => 'Ipwesto giraray',
'undeletecomment'              => 'Komento:',
'undeletedarticle'             => 'Ibinalik "[[$1]]"',
'undeletedrevisions'           => '$1 na (mga) pagriribay an binalík',
'undeletedrevisions-files'     => "$1 na (mga) pagribay asin $2 na (mga) binalik na ''file''",
'undeletedfiles'               => "$1 (mga) ''file'' an binalik",
'cannotundelete'               => 'Naprakaso an pagbalik kan pigparâ; pwede ser an binawi an pagparâ kan páhina kan ibang parágamit.',
'undeletedpage'                => "'''binalik na an $1 '''

Ikonsultar an [[Special:Log/delete|historial nin pagparâ]] para mahiling an lista nin mga kaaaging pagparâ asin pagbalik.",
'undelete-header'              => 'Hilingon an [[Special:Log/delete|historial kan pagparâ]] kan mga kaaagi pa sanang pinarang páhina.',
'undelete-search-box'          => 'Hanapón an mga pinarang pahina',
'undelete-search-prefix'       => 'Hilingón an mga pahinang nagpopoon sa:',
'undelete-search-submit'       => 'Hanápon',
'undelete-no-results'          => 'Mayong nahanap na páhinang angay sa archibo kan mga pigparâ.',
'undelete-filename-mismatch'   => "Dai pwedeng bawîon an pagparâ sa ''file'' sa pagkarhay na may tatâk nin oras na $1: dai kapadis an ''filename''",
'undelete-bad-store-key'       => "Dai pwedeng bawîon an pagparâ nin ''file'' na pagpakarhay na may taták nin oras na $1: nawara an ''file'' bago an pagparâ.",
'undelete-cleanup-error'       => "May salâ pagparâ kan ''file'' na archibong \"\$1\".",
'undelete-missing-filearchive' => "Dai maibalik an archibo kan ''file'' may na  ID $1 ta mayô ini sa base nin datos. Pwede ser na pigparâ na ini.",
'undelete-error-short'         => "May salâ sa pagbalik kan pigparang ''file'': $1",
'undelete-error-long'          => "May mga salâ na nasabat mientras sa pigbabalik an pigparang ''file'':

$1",

# Namespace form on various pages
'namespace'      => 'Liang-liang:',
'invert'         => 'Pabaliktadón an pinili',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Mga kontribusyon kan parágamit',
'mycontris'     => 'Mga kontribusyon ko',
'contribsub2'   => 'Para sa $1 ($2)',
'nocontribs'    => 'Mayong mga pagbabago na nahanap na kapadis sa ining mga criteria.',
'uctop'         => '(alituktok)',
'month'         => 'Poon bulan (asin mas amay):',
'year'          => 'Poon taon (asin mas amay):',

'sp-contributions-newbies'     => 'Ipahiling an mga kontribusión kan mga bagong kuenta sana',
'sp-contributions-newbies-sub' => 'Para sa mga bàgong account',
'sp-contributions-blocklog'    => 'Bagáton an usip',
'sp-contributions-deleted'     => 'Parâon an mga kontribusyon kan parágamit',
'sp-contributions-talk'        => 'Pag-olayán',
'sp-contributions-userrights'  => 'Pagmaneho kan mga derecho nin paragamit',
'sp-contributions-search'      => 'Maghanap nin mga kontribusyon',
'sp-contributions-username'    => 'IP o ngaran kan parágamit:',
'sp-contributions-submit'      => 'Hanápon',

# What links here
'whatlinkshere'         => 'An nakatakod digdi',
'whatlinkshere-title'   => 'Mga pahinang nakatakod sa $1',
'whatlinkshere-page'    => 'Pahina:',
'linkshere'             => "An mga minasunod na pahina nakatakod sa '''[[:$1]]''':",
'nolinkshere'           => "Mayong pahinang nakatakod sa '''[[:$1]]'''.",
'nolinkshere-ns'        => "Mayong pahina na nakatakod sa '''[[:$1]]''' sa piniling ngaran-espacio.",
'isredirect'            => 'ilikay an pahina',
'istemplate'            => 'kabali',
'whatlinkshere-prev'    => '{{PLURAL:$1|nakaagi|nakaaging $1}}',
'whatlinkshere-next'    => '{{PLURAL:$1|sunod|sunod na $1}}',
'whatlinkshere-links'   => '← mga takod',
'whatlinkshere-filters' => 'Mga pansárà',

# Block/unblock
'blockip'                     => 'Bagáton an paragamit',
'blockiptext'                 => 'Gamiton an pormularyo sa babâ para bagaton an pagsurat kan sarong espesipikong IP o ngaran nin parágamit.
Dapat gibohon sana ini para maibitaran vandalismo, asin kompirmi sa [[{{MediaWiki:Policy-url}}|palakaw]].
Magkaag nin espisipikong rason (halimbawa, magtao nin ehemplo kan mga páhinang rinaot).',
'ipaddress'                   => 'Direksyón nin IP:',
'ipadressorusername'          => 'direksyon nin IP o gahâ:',
'ipbexpiry'                   => 'Pasó:',
'ipbreason'                   => 'Rason:',
'ipbreasonotherlist'          => 'Ibang rason',
'ipbreason-dropdown'          => "*Mga komon na rason sa pagbagat
** Nagkakaag nin salang impormasyon
** Naghahalî nin mga laog kan páhina
** Nagkakaag nin mga takod na ''spam'' kan mga panluwas na ''site''
** Nagkakaag nin kalokohan/ringaw sa mga pahina
** Gawî-gawing makatakót/makauyám
** Nag-aabuso nin mga lain-lain na ''account''
** Dai akong ngaran nin parágamit",
'ipbanononly'                 => 'Bagaton an mga paragamit na anonimo sana',
'ipbcreateaccount'            => 'Pugulon an pagibo nin kuenta.',
'ipbemailban'                 => 'Pugolan ining paragamit na magpadara nin e-surat',
'ipbenableautoblock'          => 'Enseguidang bagaton an huring direccion nin  IP na ginamit kaining paragamit, asin kon ano pang ibang IP na proprobaran nindang gamiton',
'ipbsubmit'                   => 'Bagáton ining parágamit',
'ipbother'                    => 'Ibang oras:',
'ipboptions'                  => '2ng oras:2 hours,1ng aldaw:1 day,3ng aldaw:3 days,1ng semana:1 week,2ng semana:2 weeks,1ng bulan:1 month,3ng bulan:3 months,6 na bulan:6 months,1ng taon:1 year,daing kasagkoran:infinite',
'ipbotheroption'              => 'iba',
'ipbotherreason'              => 'Iba/dugang na rasón:',
'ipbhidename'                 => 'Itago an ngaran in paragamit para dai mahiling sa historial nin pagbagat, nakaandar na lista nin binagat asin lista nin paragamit',
'badipaddress'                => 'Dai pwede ining IP',
'blockipsuccesssub'           => 'Nagibo na an pagbagát',
'blockipsuccesstext'          => 'Binagat si [[Special:Contributions/$1|$1]].
<br />Hilingon an [[Special:IPBlockList|lista nin mga binagat na IP]] para marepaso an mga binagat.',
'ipb-edit-dropdown'           => 'Hirahón an mga rasón sa pagbabagát',
'ipb-unblock-addr'            => 'Paagihon $1',
'ipb-unblock'                 => 'Bawion an pagbagat nin ngaran nin paragamit o direccion nin IP',
'ipb-blocklist-addr'          => 'Hilingón an mga presenteng pagbagat ki $1',
'ipb-blocklist'               => 'Hilingon an mga presenteng binagat',
'unblockip'                   => 'Paagihon an parâgamit',
'unblockiptext'               => 'Gamiton an pormulario sa baba para puede giraray suratan an dating binagat na direccion nin IP address o ngaran nin paragamit.',
'ipusubmit'                   => 'Bawion an pagbagat kaining direccíón',
'unblocked'                   => 'Binawi na an pagbagat ki [[User:$1|$1]]',
'unblocked-id'                => 'Hinali na an bagat na $1',
'ipblocklist'                 => 'Lista nin mga direksyon nin IP asin ngaran nin paragamit na binagat',
'ipblocklist-legend'          => 'Hanapon an sarong binagát na paragamit',
'ipblocklist-username'        => 'Gahâ o dirección nin IP:',
'ipblocklist-submit'          => 'Hanápon',
'blocklistline'               => '$1, $2 binagat $3 ($4)',
'infiniteblock'               => 'daing siring',
'expiringblock'               => 'minapasó $1 $2',
'anononlyblock'               => 'anon. sana',
'noautoblockblock'            => 'pigpopondo an enseguidang pagbagat',
'createaccountblock'          => 'binagat an paggibo nin kuenta',
'emailblock'                  => 'binagát an e-surat',
'ipblocklist-empty'           => 'Mayong laog an lista nin mga binagat.',
'ipblocklist-no-results'      => 'Dai nabagat an hinagad na direccion nin IP o ngaran nin paragamit.',
'blocklink'                   => 'bagáton',
'unblocklink'                 => 'paagihon',
'contribslink'                => 'mga kontrib',
'autoblocker'                 => 'Enseguidang binagat an saimong direccion nin IP ta kaaaging ginamit ini ni "[[User:$1|$1]]". An rason nin pagbagat ni $1: "$2"',
'blocklogpage'                => 'Usip nin pagbagat',
'blocklogentry'               => 'binagat na [[$1]] na may oras nin pagpaso na $2 $3',
'blocklogtext'                => 'Ini an historial kan pagbagat asin pagbawi sa pagbagat nin mga paragamit. An mga enseguidang binagat na direccion nin
IP dai nakalista digdi. Hilingon an [[Special:IPBlockList|IP lista nin mga binagat]] para sa lista nin mga nakaandar na mga pagpangalad buda mga pagbagat.',
'unblocklogentry'             => 'binawi an pagbagat $1',
'block-log-flags-anononly'    => 'Mga paragamit na anónimo sana',
'block-log-flags-nocreate'    => "pigpopondohán an paggibo nin ''account'",
'block-log-flags-noautoblock' => 'pigpopondo an enseguidang pagbagat',
'block-log-flags-noemail'     => 'binagát an e-surat',
'range_block_disabled'        => 'Pigpopondo an abilidad kan sysop na maggibo nin bagat na hilera.',
'ipb_expiry_invalid'          => 'Dai pwede ini bilang oras kan pagpasó.',
'ipb_already_blocked'         => 'Dating binagat na si "$1"',
'ipb_cant_unblock'            => 'Error: Dai nahanap an ID nin binagat na $1. Puede ser na dati nang binawi an pagbagat kaini.',
'ip_range_invalid'            => 'Dai pwede ining serye nin IP.',
'proxyblocker'                => 'Parabagát na karibay',
'proxyblockreason'            => 'Binagat an saimong direccion nin IP ta ini sarong bukas na proxy. Apodon tabi an saimong Internet service provider o tech support asin ipaaram sainda ining seriosong problema nin seguridad.',
'proxyblocksuccess'           => 'Tapos.',
'sorbsreason'                 => 'An saimong direccion in IP nakalista na bukas na proxy sa DNSBL na piggagamit kaining sitio.',
'sorbs_create_account_reason' => "An IP mo nakalista bilang bukás ''proxy'' sa DNSBL na piggagamit kaining ''site''. Dai ka pwedeng maggibo ''account''",

# Developer tools
'lockdb'              => 'Ikandado an base nin datos',
'unlockdb'            => 'Ibukás an base nin datos',
'lockconfirm'         => 'Iyo, boot kong ikandado an base kan datos.',
'unlockconfirm'       => 'Iyo, boot kong bukasan an base kan datos.',
'lockbtn'             => 'Isará an base nin datos',
'unlockbtn'           => 'Ibukás an base nin datos',
'locknoconfirm'       => 'Dai mo pigtsekan an kahon para sa kompirmasyon.',
'lockdbsuccesssub'    => 'Kinandado na an base nin datos',
'unlockdbsuccesssub'  => 'Hinalî an kandado nin base nin datos',
'lockdbsuccesstext'   => 'Pigkandado na an base kan datos.
<br />Giromdomon na [[Special:UnlockDB|halîon an kandado]] pagkatapos kan pagmantenir.',
'unlockdbsuccesstext' => 'Pigbukasan na an base nin datos.',
'lockfilenotwritable' => "An ''file'' na kandado kan base nin datos dai nasusuratan. Para makandado o mabukasan an bse nin datos, kaipuhan na nasusuratan ini kan web server.",
'databasenotlocked'   => 'Dai nakakandado an base nin datos.',

# Move page
'move-page-legend'        => 'Ibalyó an páhina',
'movepagetext'            => "Matatàwan nin bàgong pangaran an sarong pahina na pigbabalyo an gabos na uusipón kaini gamit an pormularyo sa babâ.
An dating titulo magigin redirektang pahina sa bàgong titulo.
Dai babàgohon an mga takod sa dating titulo kan pahina;
seguradohon tabì na mayong doble o raot na mga redirekta.
Ika an responsable sa pagpaseguro na an mga takod nakatokdô kun sain dapat.

Giromdomon tabì na an pahina '''dai''' ibabalyó kun igwa nang pahina sa bàgong titulo, apwera kun mayò ining laog o sarong redirekta asin uusipón nin mga dating pagliwat. An boot sabihon kaini, pwede mong ibalik an dating pangaran kan pahina kun sain ini pigribayan nin pangaran kun napasalà ka, asin dai mo man sosoknongan an presenteng pahina.

'''PATANID!'''
Pwede na dakulà asin dai seguradong pagbàgo ini kan sarong popular na pahina; seguradohon tabì na aram mo an konsekwensya kaini bago magdagos.",
'movepagetalktext'        => "An kapadis na olay na páhina enseguidang ibabalyo kasabay kaini '''kun:'''
*Igwa nang may laog na olay na páhina na may parehong pangaran, o
*Halîon mo an marka sa kahon sa babâ.

Sa mga kasong iyan, kaipuhan mong ibalyo o isalak an páhina nin mano-mano kun boot mo.",
'movearticle'             => 'Ibalyó an pahina:',
'movenologin'             => 'Mayô sa laog',
'movenologintext'         => 'Kaipuhan na rehistradong parágamit ka asin si [[Special:UserLogin|nakalaog]] tangarig makabalyó ka nin páhina.',
'movenotallowed'          => 'Mayô kang permiso na ibalyó an mga pahina sa wiki na ini.',
'newtitle'                => 'Sa bàgong titulong:',
'move-watch'              => 'Bantayán ining pahina',
'movepagebtn'             => 'Ibalyó an pahina',
'pagemovedsub'            => 'Naibalyó na',
'movepage-moved'          => '\'\'\'Naihubò na an "$1" sa "$2"\'\'\'',
'articleexists'           => 'Igwa nang pahina sa parehong pangaran, o dai pwede an pangaran na pigpilì mo.
Magpilì tabì nin ibang pangaran.',
'talkexists'              => "'''Ibinalyo na an mismong pahina, alagad dai naibalyo an pahina nin orolay ta igwa na kaini sa bàgong titulo. Pagsaroon tabì ining duwa nin mano-mano.'''",
'movedto'                 => 'piglipat sa',
'movetalk'                => 'Ibalyo an pahinang orolayan na nakaasociar',
'1movedto2'               => '[[$1]] piglipat sa [[$2]]',
'1movedto2_redir'         => '[[$1]] pigbalyó sa [[$2]] sa paagi kan pagredirekta',
'movelogpage'             => 'Ibalyó an usip',
'movelogpagetext'         => 'Nasa ibaba an lista kan pahinang pigbalyó.',
'movereason'              => 'Rason:',
'revertmove'              => 'ibalík',
'delete_and_move'         => 'Parâon asin ibalyó',
'delete_and_move_text'    => '==Kaipuhan na parâon==

Igwa nang páhina na "[[:$1]]". Gusto mong parâon ini tangarig maibalyó?',
'delete_and_move_confirm' => 'Iyo, parâon an pahina',
'delete_and_move_reason'  => 'Pinarâ tangarig maibalyó',
'selfmove'                => 'Pareho an páhinang ginikanan asin destinasyon; dai pwedeng ibalyó an sarong páhina sa sadiri.',

# Export
'export'            => 'Iluwas an mga pahina',
'exporttext'        => 'Pwede mong ipadara an teksto asin historya nin paghirá kan sarong partikular na páhina o grupo nin mga páhina na nakapatos sa ibang XML. Pwede ining ipadara sa ibang wiki gamit an MediaWiki sa paagi kan [[Special:Import|pagpadara nin páhina]].

Para makapadara nin mga páhina, ilaag an mga titulo sa kahon para sa teksto sa babâ, sarong titulo kada linya, dangan pilîon kun boot mo presenteng bersyón asin dating bersyón, na may mga linya kan historya, o an presenteng bersyón sana na may impormasyon manonongod sa huring hirá.

Sa kaso kan huri, pwede ka man na maggamit nin takod, arog kan [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para sa páhinang "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Mga presenteng pagpakarhay sana an ibali, bakong an enterong historya',
'exportnohistory'   => "----
'''Paisi:''' Dai pigpatogotan an pagpadara kan enterong historya kan mga páhina sa paagi kaining forma huli sa mga rasón dapit sa pagsagibo kaini.",
'export-submit'     => 'Ipaluwás',
'export-addcattext' => 'Magdugang nin mga pahina sa kategoryang ini:',
'export-addcat'     => 'Magdugang',
'export-download'   => "Hapotón ku gustong itagama bilang sarong ''file''",

# Namespace 8 related
'allmessages'               => 'Mga mensahe sa sistema',
'allmessagesname'           => 'Pangaran',
'allmessagesdefault'        => 'Tekstong normal',
'allmessagescurrent'        => 'Presenteng teksto',
'allmessagestext'           => 'Ini an lista kan mga mensahe sa sistema sa ngaran-espacio na MediaWiki.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "Dai pwedeng gamiton an '''{{ns:special}}:Allmessages''' ta sarado an '''\$wgUseDatabaseMessages'''.",

# Thumbnails
'thumbnail-more'           => 'Padakulaon',
'filemissing'              => "Nawawarâ an ''file''",
'thumbnail_error'          => 'Error sa paggigibo kan retratito: $1',
'djvu_page_error'          => 'luwas sa serye an páhina kan DjVu',
'djvu_no_xml'              => 'Dai makua an XML para sa DjVu file',
'thumbnail_invalid_params' => 'Dai pwede an mga parámetro kaining retratito',
'thumbnail_dest_directory' => 'Dai makagibo kan destinasyon kan direktoryo',

# Special:Import
'import'                     => 'Ilaog an mga páhina',
'importinterwiki'            => 'Ipadara an Transwiki',
'import-interwiki-history'   => 'Kopyahon an gabos na mga bersyón para sa páhinang ini',
'import-interwiki-submit'    => 'Ipalaog',
'import-interwiki-namespace' => 'Ibalyó an mga páhina sa ngaran-espacio:',
'import-comment'             => 'Komento:',
'importtext'                 => "Ipadara tabì an ''file'' hali sa ginikanan na wiki gamit an Special:Export utility, itagama ini sa saimong disk dangan ikarga iyan digdi.",
'importstart'                => 'Piglalaog an mga páhina...',
'import-revision-count'      => '$1 {{PLURAL:$1|pagpakarhay|mga pagpakarhay}}',
'importnopages'              => 'Mayong mga páhinang ipapadara.',
'importfailed'               => 'Bakong matriumpo an pagpadara: $1',
'importunknownsource'        => 'Dai aram an tipo kan gigikanan kan ipapadara',
'importcantopen'             => "Dai mabukasan an pigpadarang ''file''",
'importbadinterwiki'         => 'Salâ an takod na interwiki',
'importnotext'               => 'Mayong laog o mayong teksto',
'importsuccess'              => 'Matriumpo an pagpadara!',
'importnofile'               => "Mayong ipinadarang ''file'' an naikarga.",

# Import log
'importlogpage'                    => 'Usip nin pagpalaog',
'import-logentry-upload'           => "pigpadara an [[$1]] kan pagkarga nin ''file''",
'import-logentry-upload-detail'    => '$1 mga pagpakarháy',
'import-logentry-interwiki'        => 'na-transwiki an $1',
'import-logentry-interwiki-detail' => '$1 mga pagpakarháy halì sa $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'An sakóng pahina',
'tooltip-pt-anonuserpage'         => 'An páhina nin páragamit para sa ip na pighihira mo bilang',
'tooltip-pt-mytalk'               => 'Pahina nin sakóng olay',
'tooltip-pt-anontalk'             => 'Mga olay manonongod sa mga hira halî sa ip na ini',
'tooltip-pt-preferences'          => 'Mga kabòtan ko',
'tooltip-pt-watchlist'            => 'Lista nin mga pahina na pigbabantayan an mga pagbabàgo',
'tooltip-pt-mycontris'            => 'Lista kan mga kabòtan ko',
'tooltip-pt-login'                => 'Pigaagda kang maglaog, alagad, bako man ining piriritan.',
'tooltip-pt-anonlogin'            => 'Pig-aagda kang maglaog, alagad, bakô man ining piriritan.',
'tooltip-pt-logout'               => 'Magluwas',
'tooltip-ca-talk'                 => 'Olay sa pahina nin laog',
'tooltip-ca-edit'                 => 'Pwede mong hirahón ining pahina. Gamiton tabi an patànaw na butones bago an pagtagama.',
'tooltip-ca-addsection'           => 'Magdugang nin komento sa orólay na ini.',
'tooltip-ca-viewsource'           => 'Protektado ining pahina. Pwede mong hilingón an ginikanan.',
'tooltip-ca-history'              => 'Mga nakaaging bersyon kaining pahina',
'tooltip-ca-protect'              => 'Protektahán ining pahina',
'tooltip-ca-delete'               => 'Paraon an pahinang ini',
'tooltip-ca-undelete'             => 'Bawîon an mga hirá na piggibo sa páhinang ini bâgo ini pigparâ',
'tooltip-ca-move'                 => 'Ibalyo an pahinang ini',
'tooltip-ca-watch'                => 'Idugang ining páhina sa pigbabantayan mo',
'tooltip-ca-unwatch'              => 'Halion ining pahina sa lista nin pigbabantayan mo',
'tooltip-search'                  => 'Hanápon an {{SITENAME}}',
'tooltip-search-go'               => 'Magduman sa pahina na igwa kaining eksaktong pangaran',
'tooltip-search-fulltext'         => 'Hanápon an mga pahina para sa tekstong ini',
'tooltip-p-logo'                  => 'Pangenot na Pahina',
'tooltip-n-mainpage'              => 'Bisitahon an Pangenot na Pahina',
'tooltip-n-mainpage-description'  => 'Daláwon an pangenot na pahina',
'tooltip-n-portal'                => 'Manonongod sa proyekto, an pwede mong gibohon, kun sain mo pwedeng hanapon an mga bagay',
'tooltip-n-currentevents'         => 'Hanapon an mga impormasyon na ginikanan sa mga presenteng panyayari',
'tooltip-n-recentchanges'         => 'An lista nin mga bàgong pagbabàgo sa wiki.',
'tooltip-n-randompage'            => 'Magkarga nin bàgong pahina',
'tooltip-n-help'                  => 'An lugar para makatalastas',
'tooltip-t-whatlinkshere'         => 'Lista nin gabos na pahinang wiki na nakatakód digdi',
'tooltip-t-recentchangeslinked'   => 'Mga kaaaging pagbabàgo sa mga pahinang nakatakod digdi',
'tooltip-feed-rss'                => 'Hungit na RSS sa pahinang ini',
'tooltip-feed-atom'               => 'Hungit na atomo sa pahinang ini',
'tooltip-t-contributions'         => 'Hilingón an lista kan mga kontribusyon kaining paragamit',
'tooltip-t-emailuser'             => 'Padarahan nin e-koreo an paragamit na ini',
'tooltip-t-upload'                => 'Ikargá an mga ladawan o media files',
'tooltip-t-specialpages'          => 'Lista kan gabos na mga espesyal na pahina',
'tooltip-t-print'                 => 'Naipiprint na bersyon kaining pahina',
'tooltip-t-permalink'             => 'Permanenteng takod sa bersyon kaining páhina',
'tooltip-ca-nstab-main'           => 'Hilingón an pahina nin laog',
'tooltip-ca-nstab-user'           => 'Hilingón an pahina nin paragamit',
'tooltip-ca-nstab-media'          => "Hilingón an pahina kan ''media''",
'tooltip-ca-nstab-special'        => 'Pahinang espesyal ini, dai mo ini pwedeng hirahón',
'tooltip-ca-nstab-project'        => 'Hilingón an pahina kan proyekto',
'tooltip-ca-nstab-image'          => 'Hilingón an pahina kan retrato',
'tooltip-ca-nstab-mediawiki'      => "Hilingón an ''system message''",
'tooltip-ca-nstab-template'       => 'Hilingón an templato',
'tooltip-ca-nstab-help'           => 'Hilingón an pahina nin tabang',
'tooltip-ca-nstab-category'       => 'Hilingón an pahina kan kategorya',
'tooltip-minoredit'               => 'Markahán ini bilang sadit na pagligwat',
'tooltip-save'                    => 'Itagáma an saimong mga pagbabàgo',
'tooltip-preview'                 => 'Tànawon an saimong mga pagbabàgo, gamitón tabì ini bàgo magtagáma!',
'tooltip-diff'                    => 'Ipahilíng an mga pagbabàgong ginibo mo sa teksto.',
'tooltip-compareselectedversions' => 'Hilingon an mga kaibhán sa duwang piniling bersyón kaining páhina.',
'tooltip-watch'                   => 'Idugang ining pahina sa pigbabantayan mo',
'tooltip-recreate'                => 'Gibohon giraray an páhina maski na naparâ na ini',
'tooltip-upload'                  => 'Pônan an pagkarga',

# Stylesheets
'common.css'   => '/** an CSS na pigbugtak digdi maiaaplikar sa gabos na mga skin */',
'monobook.css' => '/* an CSS na pigbugtak digdi makakaapektar sa mga parágamit kan Monobook skin */',

# Scripts
'common.js'   => '/* Arin man na JavaScript digdi maikakarga para sa gabos na mga parágamit sa kada karga kan páhina. */',
'monobook.js' => '/* Deprecado; gamiton an [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => "Pigpopogolan an Dublin Core RDF na metadata para sa ''server'' na ini.",
'nocreativecommons' => "Pigpopogolan an Creative Commons RDF na metadata para sa ''server'' na ini.",
'notacceptable'     => "Dai pwedeng magtao nin datos an ''wiki server'' sa ''format'' na pwedeng basahon kan kompyuter mo.",

# Attribution
'anonymous'        => '(Mga)paragamit na anónimo kan {{SITENAME}}',
'siteuser'         => 'Paragamit kan {{SITENAME}} na si $1',
'lastmodifiedatby' => 'Ining páhina huring binago sa $2, $1 ni $3.',
'othercontribs'    => 'Binase ini sa trabaho ni $1.',
'others'           => 'iba pa',
'siteusers'        => '(Mga)paragamit kan {{SITENAME}} na si $1',
'creditspage'      => 'Mga krédito nin páhina',
'nocredits'        => 'Mayong talastas kan kredito para sa ining pahina.',

# Spam protection
'spamprotectiontitle' => "Proteksyon kan ''spam filter''",
'spamprotectiontext'  => "An páhinang gusto mong itagama pigbagat kan ''spam filter''. Kawsa gayod ini kan sarong takod sa sarong panluwas na 'site'.",
'spamprotectionmatch' => "An minasunod na teksto iyo an nagbukas kan ''spam filter'' mi: $1",
'spambot_username'    => 'paglimpya nin spam sa MediaWiki',
'spam_reverting'      => 'Mabalik sa huring bersion na mayong takod sa $1',
'spam_blanking'       => 'An gabos na mga pahirá na may takod sa $1, pigblablanko',

# Info page
'infosubtitle'   => 'Impormasyón kan páhina',
'numedits'       => 'Bilang kan mga hira (artikulo): $1',
'numtalkedits'   => 'Bilang kan mga hirá (páhina kan orólay): $1',
'numwatchers'    => 'Bilang kan mga parábantay: $1',
'numauthors'     => 'Bilang kan mga parásurat na ibá (páhina): $1',
'numtalkauthors' => 'Bilang kan mga parásurat na ibá (páhina kan orólay): $1',

# Skin names
'skinname-standard' => 'Klasiko',
'skinname-simple'   => 'Simple',
'skinname-modern'   => 'Bago',

# Math options
'mw_math_png'    => 'Itaô pirmi an PNG',
'mw_math_simple' => 'HTML kun simple sana o PNG kun bakô',
'mw_math_html'   => 'HTML kun posible o PNG kun bakô',
'mw_math_source' => "Pabayaan na bilang TeX (para sa mga ''browser'' na teksto)",
'mw_math_modern' => "Pigrerekomendár para sa mga modernong ''browser''",
'mw_math_mathml' => 'MathML kun posible (experimental)',

# Math errors
'math_failure'          => 'Nagprakaso an pagatíd-atíd',
'math_unknown_error'    => 'dai aram an salâ',
'math_unknown_function' => 'Dai aram an gamit',
'math_lexing_error'     => 'may salâ sa analisador léxico',
'math_syntax_error'     => 'may salâ sa analisador nin sintaksis',
'math_image_error'      => 'Nagprakaso an konbersyon kan PNG; sosogon tabî an pagkaag nin latex, dvips, gs, asin ikonbertir',
'math_bad_tmpdir'       => 'Dai masuratan o magibo an direktoryo nin mat temp',
'math_bad_output'       => 'Dai masuratan o magibo an direktoryo kan salida nin math',
'math_notexvc'          => 'May nawawarang texvc na ehekutable; hilingón tabî an mat/README para makonpigurar.',

# Patrolling
'markaspatrolleddiff'                 => 'Markahán na pigpapatrulya',
'markaspatrolledtext'                 => 'Markahan ining pahina na pigpapatrulya',
'markedaspatrolled'                   => 'Minarkahán na pigpapatrulyá',
'markedaspatrolledtext'               => 'Minarkahan bilang pigpapatrulya an piniling pagpakaray.',
'rcpatroldisabled'                    => 'Pigpopogolan an mga Pagpatrulya kan mga Kaaaging Pagbabâgo',
'rcpatroldisabledtext'                => 'Pigpopogulan muna an Pagpatrulya kan mga Kaaaging Pagbabago.',
'markedaspatrollederror'              => 'Dai pwedeng markahán na pigpapatrulya',
'markedaspatrollederrortext'          => 'Kaipuhan mong magpili nin pagpakaray na mamarkahon na pigpapatrulya.',
'markedaspatrollederror-noautopatrol' => 'Dai ka pigtotogotan na markahán an sadiri mong pababâgo na pigpapatrulya.',

# Patrol log
'patrol-log-page' => 'Bantayán an historial',
'patrol-log-line' => 'minarkahan an $1 kan $2 na pigpapatrulya $3',
'patrol-log-auto' => '(enseguida)',

# Image deletion
'deletedrevision'                 => 'Pigparâ an lumang pagribay na $1.',
'filedeleteerror-short'           => "Salâ sa pagparâ kan ''file'': $1",
'filedeleteerror-long'            => "May mga nasabat na salâ mientras na pigpaparâ an ''file'':

$1",
'filedelete-missing'              => "An ''file'' na \"\$1\" dai pwedeng paraon, ta mayô man ini.",
'filedelete-old-unregistered'     => 'An piniling pagpakaray na "$1" wara man sa base nin datos.',
'filedelete-current-unregistered' => "Mayô sa base nin datos an piniling ''file'' na \"\$1\".",
'filedelete-archive-read-only'    => 'An direktoryong archibo na "$1" dai nasusuratan kan webserver.',

# Browsing diffs
'previousdiff' => '← Nakaáging kaibhán',
'nextdiff'     => 'Sunod na kaibhán →',

# Media information
'mediawarning'         => "'''Patanid''': May ''malicious code'' sa ''file'' na ini, kun gigibohon ini pwede ser na maraot an saimong ''system''.",
'imagemaxsize'         => 'Limitaran an mga ladawan sa mga páhinang deskripsyon kan ladawan sa:',
'thumbsize'            => 'Sokol nin retratito:',
'widthheightpage'      => '$1×$2, $3 mga pahina',
'file-info'            => "(sokol kan ''file'': $1, tipo nin MIME: $2)",
'file-info-size'       => "($1 × $2 na pixel, sokol kan ''file'': $3, tipo nin MIME: $4)",
'file-nohires'         => '<small>Mayong mas halangkaw na resolusyón.</small>',
'svg-long-desc'        => '(file na SVG, haros $1 × $2 pixels, sokol kan file: $3)',
'show-big-image'       => 'Todong resolusyon',
'show-big-image-thumb' => '<small>Sokol kan patânaw: $1 × $2  na pixel</small>',

# Special:NewFiles
'newimages'             => 'Galeria nin mga bàgong file',
'imagelisttext'         => "Mahihiling sa baba an lista nin mga  '''$1''' {{PLURAL:$1|file|files}} na linain $2.",
'showhidebots'          => '($1 na bots)',
'noimages'              => 'Mayong mahihiling.',
'ilsubmit'              => 'Hanápon',
'bydate'                => 'sa petsa',
'sp-newimages-showfrom' => 'Hilingón an mga retratong nagpopoon sa $1',

# Bad image list
'bad_image_list' => 'An pormato iyo an minasunod:

An mga nakalista sana (mga linyang nagpopoon sa *) an pigkokonsiderar.
An enot na takod sa linya seguradong sarong takod sa sarong salang file.
Ano man na takod sa parehong linyang ini pigkokonsiderar na eksepsyon, i.e. mga pahina na may file sa laog nin linya.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Igwang dugang na impormasyon ining file na pwedeng idinugang hali sa digital camera o scanner na piggamit tangarig magibo ini. Kun namodipikar na file hali sa orihinal nyang kamogtakan, an ibang mga detalye pwedeng dai mahiling sa minodipikar na ladawan.',
'metadata-expand'   => 'Ipahiling an mga pinahalaba na detalye',
'metadata-collapse' => 'Itago an mga pinahalaba na detalye',

# EXIF tags
'exif-imagewidth'       => 'Lakbang',
'exif-imagelength'      => 'Langkaw',
'exif-imagedescription' => 'Titulo kan retrato',
'exif-make'             => 'Tagagibo nin kamera',
'exif-model'            => 'Modelo nin kamera',
'exif-artist'           => 'Parásurat',
'exif-usercomment'      => 'Mga komento kan parágamit',
'exif-aperturevalue'    => 'Pagkabukás',
'exif-brightnessvalue'  => 'Kaliwanagan',
'exif-lightsource'      => 'Ginikanan nin liwanag',
'exif-flash'            => 'Kikilát',
'exif-flashenergy'      => 'Kakusogan nin kikilát',
'exif-filesource'       => "Ginikanan nin ''file''",
'exif-contrast'         => 'Kontraste',
'exif-imageuniqueid'    => 'Unikong ID kan ladawan',
'exif-gpstrack'         => 'Direksyón nin paghirô',
'exif-gpsimgdirection'  => 'Direksyón nin ladawan',
'exif-gpsdestdistance'  => 'Distansya sa destinasyon',

'exif-unknowndate' => 'Dai aram an petsa',

'exif-componentsconfiguration-0' => 'mayô man ini',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Dai aram',
'exif-meteringmode-255' => 'Iba pa',

'exif-lightsource-4'   => 'Kitkilát',
'exif-lightsource-9'   => 'Magayón na panahón',
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
'edit-externally'      => 'Hirahón an file gamit an panluwas na aplikasyon',
'edit-externally-help' => 'Hilingón an  [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] para sa iba pang mga impormasyon.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'gabos',
'imagelistall'     => 'gabos',
'watchlistall2'    => 'gabos',
'namespacesall'    => 'gabos',
'monthsall'        => 'gabos',

# E-mail address confirmation
'confirmemail'            => "Kompirmaron an ''e''-surat",
'confirmemail_noemail'    => "Mayô kang pigkaag na marhay na ''e''-surat sa saimong [[Special:Preferences|mga kabôtan nin parágamit]].",
'confirmemail_text'       => "Kaipuhan an pag-''validate'' kan saimong e-koreo bago ka makagamit nin ''features'' na e-koreo. Pindoton an butones sa babâ tangarig magpadara nin kompirmasyon sa saimong e-koreo. An surat igwang takod na may koda; ikarga an takod sa browser para makompirmar na valido an saimong e-koreo.",
'confirmemail_pending'    => "May pigpadara nang kompirmasyon sa ''e''-surat mo; kun kagigibo mo pa sana kan saimong ''account'', maghalat ka nin mga dikit na minutos bago ka maghagad giraray nin bâgong ''code''.",
'confirmemail_send'       => 'Magpadará nin kompirmasyon',
'confirmemail_sent'       => "Napadará na an kompirmasyon sa ''e''-surat.",
'confirmemail_oncreate'   => "May pigpadara nang kompirmasyon sa saimong ''e''-surat.
Dai man kaipuhan ini para makalaog, pero kaipuhan mong itao ini bago
ka makagamit nin ''features'' na basado sa ''e''-surat sa wiki.",
'confirmemail_sendfailed' => "Dai napadará an kompirmasyon kan ''e''-surat. Seguradohon tabî kun igwang sala.

Pigbalik: $1",
'confirmemail_invalid'    => 'Salâ an kódigo nin konpirmasyon. Puede ser napasó na an kódigo.',
'confirmemail_needlogin'  => "Kaipuhan tabi $1 ikompirmar an saimong ''e''-surat.",
'confirmemail_success'    => "Nakompirmar na an saimong ''e''-surat. Pwede ka nang maglaog asin mag-ogma sa wiki.",
'confirmemail_loggedin'   => "Nakompirmar na an saimong ''e''-surat.",
'confirmemail_error'      => 'May nasalâ sa pagtagama kan saimong kompirmasyon.',
'confirmemail_subject'    => "kompirmasyón {{SITENAME}} kan direksyón nin ''e''-surat",
'confirmemail_body'       => 'May paragamit, pwedeng ika, halì sa IP na $1, na nagrehistro nin account na
"$2" na igwang e-koreo sa {{SITENAME}}.

Tangarig makompirmar na talagang saimo ining account asin makagamit nin e-koreo sa {{SITENAME}}, bukasán ining takod sa saimong browser:

$3

Kun *bakô* ka ini, dai sunodón an takod. Mapaso sa $4 inning koda nin kompirmasyon.',

# Scary transclusion
'scarytranscludedisabled' => '[Pigpopogolan an transcluding na Interwiki]',
'scarytranscludefailed'   => '[Nabigô an pagkua kan templato para sa $1; despensa]',
'scarytranscludetoolong'  => '[halabaon an URL; despensa]',

# Trackbacks
'trackbackbox'      => 'Mga trackback sa pahinang ini:<br />
$1',
'trackbackremove'   => '([$1 Parâon])',
'trackbacklink'     => 'Solsogan',
'trackbackdeleteok' => 'Pigparâ na an solsogan.',

# Delete conflict
'deletedwhileediting' => 'Patanid: Pigparâ na an pahinang ini antes na nagpoon kan maghirá!',
'confirmrecreate'     => "Si [[User:$1|$1]] ([[User talk:$1|olay]]) pigparâ ining páhina pagkatapos mong magpoon kan paghira ta:
: ''$2''
Ikonpirmar tabi na talagang gusto mong gibohon giraray ining pahina.",
'recreate'            => 'Gibohón giraray',

# action=purge
'confirm_purge_button' => 'Sige',
'confirm-purge-top'    => 'Halîon an an aliho kaining páhina?',

# Multipage image navigation
'imgmultipageprev' => '← nakaaging pahina',
'imgmultipagenext' => 'sunod na pahina →',
'imgmultigo'       => 'Dumanán!',

# Table pager
'ascending_abbrev'         => 'skt',
'descending_abbrev'        => 'ba',
'table_pager_next'         => 'Sunod na páhina',
'table_pager_prev'         => 'Nakaaging páhina',
'table_pager_first'        => 'Enot na páhina',
'table_pager_last'         => 'Huring páhina',
'table_pager_limit'        => 'Ipahiling an $1 na aytem kada páhina',
'table_pager_limit_submit' => 'Dumanán',
'table_pager_empty'        => 'Mayong resulta',

# Auto-summaries
'autosumm-blank'   => 'Pighahalî an gabos na laog sa páhina',
'autosumm-replace' => "Pigriribayan an páhina nin '$1'",
'autoredircomment' => 'Piglilikay sa [[$1]]',
'autosumm-new'     => 'Bâgong páhina: $1',

# Live preview
'livepreview-loading' => 'Pigkakarga…',
'livepreview-ready'   => 'Pigkakarga… Magpreparar!',
'livepreview-failed'  => 'Dae nakapoon an direktong patânaw! Probaran tabî an patânaw na normal.',
'livepreview-error'   => 'Dai nakakabit: $1 "$2". Hilingón tabî an normal na patânaw.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'An mga pagbalyó na mas bâgo sa $1 na segundo pwedeng dai pa mahiling sa listang ini.',
'lag-warn-high'   => "Nin huli sa ''high database server lag'', an mga pagbabâgo na mas bâgo sa $1 na segundo pwedeng dai pa ipahiling sa listang ini.",

# Watchlist editor
'watchlistedit-numitems'       => 'An saimong pigbabantayan igwang {{PLURAL:$1|1 titulo|$1 mga titulo}}, apwera kan mga páhina kan olay.',
'watchlistedit-noitems'        => 'Mayong mga titulo an pigbabantayan mo.',
'watchlistedit-normal-title'   => 'Hirahón an pigbabantayan',
'watchlistedit-normal-legend'  => 'Halion an mga titulo sa pigbabantayan',
'watchlistedit-normal-explain' => 'Mahihiling sa babâ an mga titulo na nasa pigbabantayan mo.
Tangarig maghalì nin titulo, markahan an kahon sa gilid kaini, dangan pindotón an Tangkasón an mga Titulo. Pwede mo man na [[Special:Watchlist/raw|hirahón an bàgong lista]].',
'watchlistedit-normal-submit'  => 'Tangkasón an mga Titulo',
'watchlistedit-normal-done'    => 'Pigtangkas an {{PLURAL:$1|1 an titulo|$1 mga titulo}} sa saimong pigbabantayan:',
'watchlistedit-raw-title'      => 'Hirahón an bàgong pigbabantayan',
'watchlistedit-raw-legend'     => 'Hirahón an bàgong pigbabantayan',
'watchlistedit-raw-explain'    => 'Mahihiling sa babâ an mga titulo na nasa pigbabantayan mo, asin pwede ining hirahón sa paagi nin pagdugang sagkod paghalì sa lista;
sarong titulo kada linya.
Pag tapos na, lagatikón an Bàgohón an Pigbabantayan.
Pwede mo man [[Special:Watchlist/edit|gamiton an standard editor]].',
'watchlistedit-raw-titles'     => 'Mga titulo:',
'watchlistedit-raw-submit'     => 'Bàgohón an Pigbabantayan',
'watchlistedit-raw-done'       => 'Binàgo na an saimong pigbabantayan.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 an titulong|$1 mga titulong}} idinugang:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 an titulong|$1 mga titulong}} hinalì:',

# Watchlist editing tools
'watchlisttools-view' => 'Hilingón an mga katakód na pagbabàgo',
'watchlisttools-edit' => 'Hilingón asin ligwatón an pigbabantayan',
'watchlisttools-raw'  => 'Hirahón an bàgong pigbabantayan',

# Special:Version
'version' => 'Bersyon',

# Special:SpecialPages
'specialpages'               => 'Mga espesyal na pahina',
'specialpages-group-other'   => 'Iba pang mga espesyal na pahina',
'specialpages-group-login'   => 'Maglaóg/ maggíbo',
'specialpages-group-changes' => 'Nakakaági pa sanáng mga pagbàgo asín laóg',

# Special:BlankPage
'blankpage'              => 'Blangkong pahina',
'intentionallyblankpage' => 'Pigtuyong blangko an pahinang ini',

# Special:Tags
'tags-edit' => 'liwatón',

);
