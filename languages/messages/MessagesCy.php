<?php
/** Welsh (Cymraeg)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Arwel Parry
 * @author Lloffiwr
 * @author Malafaya
 * @author Reedy
 * @author Thaf
 * @author Urhixidur
 * @author Xxglennxx
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arbennig',
	NS_TALK             => 'Sgwrs',
	NS_USER             => 'Defnyddiwr',
	NS_USER_TALK        => 'Sgwrs_Defnyddiwr',
	NS_PROJECT_TALK     => 'Sgwrs_$1',
	NS_FILE             => 'Delwedd',
	NS_FILE_TALK        => 'Sgwrs_Delwedd',
	NS_MEDIAWIKI        => 'MediaWici',
	NS_MEDIAWIKI_TALK   => 'Sgwrs_MediaWici',
	NS_TEMPLATE         => 'Nodyn',
	NS_TEMPLATE_TALK    => 'Sgwrs_Nodyn',
	NS_HELP             => 'Cymorth',
	NS_HELP_TALK        => 'Sgwrs Cymorth',
	NS_CATEGORY         => 'Categori',
	NS_CATEGORY_TALK    => 'Sgwrs_Categori',
);


$defaultDateFormat = 'dmy';

$bookstoreList = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
	"Amazon.co.uk" => "http://www.amazon.co.uk/exec/obidos/ISBN=$1"
);

$magicWords = array(
	'redirect'              => array( '0', '#ail-cyfeirio', '#ailgyfeirio', '#REDIRECT' ),
	'notoc'                 => array( '0', '__DIMTAFLENCYNNWYS__', '__DIMRHESTRGYNNWYS__', '__DIMRHG__', '__NOTOC__' ),
	'noeditsection'         => array( '0', '__DIMADRANGOLYGU__', '__DIMGOLYGUADRAN__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'MISCYFOES', 'MISCYFREDOL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ENWMISCYFOES', 'ENWMISCYFREDOL', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'GENENWMISCYFOES', 'CURRENTMONTHNAMEGEN' ),
	'currentday'            => array( '1', 'DYDDIADCYFOES', 'DYDDCYFREDOL', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'ENWDYDDCYFOES', 'ENWDYDDCYFREDOL', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'FLWYDDYNCYFOES', 'BLWYDDYNGYFREDOL', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'AMSERCYFOES', 'AMSERCYFREDOL', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'AWRGYFREDOL', 'CURRENTHOUR' ),
	'numberofarticles'      => array( '1', 'NIFEROERTHYGLAU', 'NIFERYRERTHYGLAU', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NIFERYFFEILIAU', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NIFERYDEFNYDDWYR', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'NIFERYGOLYGIADAU', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'ENWTUDALEN', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'ENWTUDALENE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'PARTH', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NAMESPACE', 'PARTHE', 'NAMESPACEE' ),
	'fullpagename'          => array( '1', 'ENWLLAWNTUDALEN', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ENWLLAWNTUDALENE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ENWISDUDALEN', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ENWISDUDALENE', 'SUBPAGENAMEE' ),
	'talkpagename'          => array( '1', 'ENWTUDALENSGWRS', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'ENWTUDALENSGWRSE', 'TALKPAGENAMEE' ),
	'img_thumbnail'         => array( '1', 'ewin bawd', 'bawd', 'mân-lun', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'mân-lun=$1', 'bawd=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'de', 'right' ),
	'img_left'              => array( '1', 'chwith', 'left' ),
	'img_none'              => array( '1', 'dim', 'none' ),
	'img_center'            => array( '1', 'canol', 'center', 'centre' ),
	'img_page'              => array( '1', 'tudalen=$1', 'tudalen $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'unionsyth', 'unionsyth=$1', 'unionsyth $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_sub'               => array( '1', 'is', 'sub' ),
	'img_super'             => array( '1', 'uwch', 'super', 'sup' ),
	'img_top'               => array( '1', 'brig', 'top' ),
	'img_bottom'            => array( '1', 'gwaelod', 'godre', 'bottom' ),
	'server'                => array( '0', 'GWEINYDD', 'SERVER' ),
	'servername'            => array( '0', 'ENW\'RGWEINYDD', 'SERVERNAME' ),
	'grammar'               => array( '0', 'GRAMMAR', 'GRAMADEG', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'WYTHNOSGYFREDOL', 'CURRENTWEEK' ),
	'revisionid'            => array( '1', 'IDYGOLYGIAD', 'REVISIONID' ),
	'revisionday'           => array( '1', 'DIWRNODYGOLYGIAD', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'DIWRNODYGOLYGIAD2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'MISYGOLYGIAD', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'BLWYDDYNYGOLYGIAD', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'STAMPAMSERYGOLYGIAD', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'LLUOSOG:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'URLLLAWN:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLLLAWNE:', 'FULLURLE:' ),
	'newsectionlink'        => array( '1', '_NEWSECTIONLINK_', '_CYSWLLTADRANNEWYDD_', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'GOLYGIADCYFREDOL', 'CURRENTVERSION' ),
	'currenttimestamp'      => array( '1', 'STAMPAMSERCYFREDOL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'STAMPAMSERLLEOL', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#IAITH:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'IAITHYCYNNWYS', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'TUDALENNAUYNYPARTH:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'NIFERYGWEINYDDWYR', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'FFORMATIORHIF', 'FORMATNUM' ),
	'special'               => array( '0', 'arbennig', 'special' ),
	'hiddencat'             => array( '1', '_HIDDENCAT_', '_CATCUDD_', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'TUDALENNAUYNYCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'MAINTTUD', 'PAGESIZE' ),
);

$linkTrail = "/^([àáâèéêìíîïòóôûŵŷa-z]+)(.*)\$/sDu";

$messages = array(
# User preference toggles
'tog-underline'               => 'Tanlinellu cysylltiadau:',
'tog-highlightbroken'         => 'Fformatio cysylltiadau wedi\'u torri <a href="" class="new">fel hyn</a> (dewis arall: fel hyn<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Unioni paragraffau',
'tog-hideminor'               => 'Cuddio golygiadau bychain yn rhestr y newidiadau diweddar',
'tog-hidepatrolled'           => 'Cuddio golygiadau sydd wedi derbyn ymweliad patrôl rhag y rhestr newidiadau diweddar',
'tog-newpageshidepatrolled'   => 'Cuddio tudalennau sydd wedi derbyn ymweliad patrôl rhag y rhestr dudalennau newydd',
'tog-extendwatchlist'         => "Ehangu'r rhestr wylio i ddangos pob golygiad yn hytrach na'r diweddaraf yn unig",
'tog-usenewrc'                => 'Defnyddio newidiadau diweddar gwell (angen JavaScript)',
'tog-numberheadings'          => "Rhifo penawdau'n awtomatig",
'tog-showtoolbar'             => 'Dangos y bar offer golygu (angen JavaScript)',
'tog-editondblclick'          => 'Golygu tudalennau wrth glicio ddwywaith (angen JavaScript)',
'tog-editsection'             => 'Galluogi golygu adran trwy ddolennau [golygu] uwchben yr adran',
'tog-editsectiononrightclick' => 'Galluogi golygu adran drwy dde-glicio ar bennawd yr adran (JavaScript)',
'tog-showtoc'                 => 'Dangos y daflen gynnwys (ar gyfer tudalennau sydd â mwy na 3 pennawd)',
'tog-rememberpassword'        => "Y porwr hwn i gofio'r manylion mewngofnodi (hyd at $1 {{PLURAL:$1||diwrnod|ddiwrnod|diwrnod|diwrnod|diwrnod}})",
'tog-watchcreations'          => 'Ychwanegu tudalennau at fy rhestr wylio wrth i mi eu creu',
'tog-watchdefault'            => 'Ychwanegu tudalennau at fy rhestr wylio wrth i mi eu golygu',
'tog-watchmoves'              => 'Ychwanegu tudalennau at fy rhestr wylio wrth i mi eu symud',
'tog-watchdeletion'           => 'Ychwanegu tudalennau at fy rhestr wylio wrth i mi eu dileu',
'tog-minordefault'            => 'Marcio pob golygiad fel un bach yn ddiofyn',
'tog-previewontop'            => 'Dangos y rhagolwg cyn y blwch golygu',
'tog-previewonfirst'          => 'Dangos rhagolwg ar y golygiad cyntaf',
'tog-nocache'                 => 'Analluogi storio tudalennau yng nghelc y porydd',
'tog-enotifwatchlistpages'    => 'Gyrru e-bost ataf pan fo newid i dudalen ar fy rhestr wylio',
'tog-enotifusertalkpages'     => "Gyrru e-bost ataf fy hunan pan fo newid i'm tudalen sgwrs",
'tog-enotifminoredits'        => 'Gyrru e-bost ataf hefyd ar gyfer golygiadau bychain i dudalennau',
'tog-enotifrevealaddr'        => 'Datguddio fy nghyfeiriad e-bost mewn e-byst hysbysu',
'tog-shownumberswatching'     => "Dangos y nifer o ddefnyddwyr sy'n gwylio",
'tog-oldsig'                  => "Rhagolwg o'r llofnod presennol:",
'tog-fancysig'                => 'Trin y llofnod fel testun wici (heb gyswllt wici awtomatig)',
'tog-externaleditor'          => 'Defnyddio golygydd allanol trwy ragosodiad (ar gyfer arbenigwyr yn unig; mae angen gosodiadau arbennig ar eich cyfrifiadur)',
'tog-externaldiff'            => 'Defnyddio "external diff" trwy ragosodiad (ar gyfer arbenigwyr yn unig; mae angen gosodiadau arbennig ar eich cyfrifiadur)',
'tog-showjumplinks'           => 'Galluogi dolenni hygyrchedd "neidio i"',
'tog-uselivepreview'          => 'Defnyddio rhagolwg byw (JavaScript) (Arbrofol)',
'tog-forceeditsummary'        => 'Tynnu fy sylw pan adawaf flwch crynodeb golygu yn wag',
'tog-watchlisthideown'        => 'Cuddio fy ngolygiadau fy hunan yn fy rhestr wylio',
'tog-watchlisthidebots'       => 'Cuddio golygiadau bot yn fy rhestr wylio',
'tog-watchlisthideminor'      => 'Cuddio golygiadau bychain rhag y rhestr wylio',
'tog-watchlisthideliu'        => 'Cuddio golygiadau gan ddefnyddwyr mewngofnodedig rhag y rhestr wylio',
'tog-watchlisthideanons'      => 'Cuddio golygiadau gan ddefnyddwyr anhysbys rhag y rhestr wylio',
'tog-watchlisthidepatrolled'  => 'Cuddio golygiadau sydd wedi derbyn ymweliad patrôl rhag y rhestr wylio',
'tog-ccmeonemails'            => 'Anfoner copi ataf pan anfonaf e-bost at ddefnyddiwr arall',
'tog-diffonly'                => "Peidio â dangos cynnwys y dudalen islaw'r gymhariaeth ar dudalennau cymharu",
'tog-showhiddencats'          => 'Dangos categorïau cuddiedig',
'tog-norollbackdiff'          => 'Hepgor dangos cymhariaeth ar ôl gwrthdroi golygiad',

'underline-always'  => 'Bob amser',
'underline-never'   => 'Byth',
'underline-default' => 'Rhagosodyn y porwr',

# Font style option in Special:Preferences
'editfont-style'     => 'Arddull y ffont yn y blwch golygu:',
'editfont-default'   => 'Rhagosodyn y porwr',
'editfont-monospace' => 'Ffont unlled',
'editfont-sansserif' => 'Sans-seriff',
'editfont-serif'     => 'Seriff',

# Dates
'sunday'        => 'Dydd Sul',
'monday'        => 'Dydd Llun',
'tuesday'       => 'Dydd Mawrth',
'wednesday'     => 'Dydd Mercher',
'thursday'      => 'Dydd Iau',
'friday'        => 'Dydd Gwener',
'saturday'      => 'Dydd Sadwrn',
'sun'           => 'Sul',
'mon'           => 'Llun',
'tue'           => 'Maw',
'wed'           => 'Mer',
'thu'           => 'Iau',
'fri'           => 'Gwe',
'sat'           => 'Sad',
'january'       => 'Ionawr',
'february'      => 'Chwefror',
'march'         => 'Mawrth',
'april'         => 'Ebrill',
'may_long'      => 'Mai',
'june'          => 'Mehefin',
'july'          => 'Gorffennaf',
'august'        => 'Awst',
'september'     => 'Medi',
'october'       => 'Hydref',
'november'      => 'Tachwedd',
'december'      => 'Rhagfyr',
'january-gen'   => 'Ionawr',
'february-gen'  => 'Chwefror',
'march-gen'     => 'Mawrth',
'april-gen'     => 'Ebrill',
'may-gen'       => 'Mai',
'june-gen'      => 'Mehefin',
'july-gen'      => 'Gorffennaf',
'august-gen'    => 'Awst',
'september-gen' => 'Medi',
'october-gen'   => 'Hydref',
'november-gen'  => 'Tachwedd',
'december-gen'  => 'Rhagfyr',
'jan'           => 'Ion',
'feb'           => 'Chwe',
'mar'           => 'Maw',
'apr'           => 'Ebr',
'may'           => 'Mai',
'jun'           => 'Meh',
'jul'           => 'Gor',
'aug'           => 'Awst',
'sep'           => 'Medi',
'oct'           => 'Hyd',
'nov'           => 'Tach',
'dec'           => 'Rhag',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categori|Categori|Categorïau|Categorïau|Categorïau|Categorïau}}',
'category_header'                => 'Erthyglau yn y categori "$1"',
'subcategories'                  => 'Is-gategorïau',
'category-media-header'          => "Cyfryngau yn y categori '$1'",
'category-empty'                 => "''Ar hyn o bryd nid oes unrhyw erthyglau na ffeiliau yn y categori hwn.''",
'hidden-categories'              => '{{PLURAL:$1|Categori cuddiedig|Categori cuddiedig|Categorïau cuddiedig|Categorïau cuddiedig|Categorïau cuddiedig|Categorïau cuddiedig}}',
'hidden-category-category'       => 'Categorïau cuddiedig',
'category-subcat-count'          => "{{PLURAL:$1|Nid oes dim is-gategorïau|Dim ond yr is-gategori sy'n dilyn sydd|Mae'r $1 is-gategori sy'n dilyn ymhlith cyfanswm o $2|Mae'r $1 is-gategori sy'n dilyn ymhlith cyfanswm o $2|Mae'r $1 is-gategori sy'n dilyn ymhlith cyfanswm o $2|Mae'r $1 is-gategori sy'n dilyn ymhlith cyfanswm o $2}} yn y categori hwn.",
'category-subcat-count-limited'  => 'Mae gan y categori hwn $1 {{PLURAL:$1|is-gategori}} fel a ganlyn.',
'category-article-count'         => "{{PLURAL:$2|Nid oes dim tudalennau|Dim ond y dudalen sy'n dilyn sydd|Dangosir isod y $1 dudalen sydd|Dangosir isod y $1 tudalen sydd|Dangosir isod y $1 thudalen sydd|Dangosir isod $1 {{PLURAL:$1|Dim|dudalen|dudalen|tudalen|thudalen|tudalen}} ymhlith cyfanswm o $2 sydd}} yn y categori hwn.",
'category-article-count-limited' => "Mae'r {{PLURAL:$1|tudalen|dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 tudalen}} sy'n dilyn yn y categori hwn.",
'category-file-count'            => "{{PLURAL:$2|Nid oes dim ffeiliau|Dim ond y ffeil sy'n dilyn sydd|Mae'r $1 ffeil sy'n dilyn ymhlith cyfanswm o $2|Mae'r $1 ffeil sy'n dilyn ymhlith cyfanswm o $2|Mae'r $1 ffeil sy'n dilyn ymhlith cyfanswm o $2|Mae'r $1 ffeil sy'n dilyn ymhlith cyfanswm o $2}} yn y categori hwn.",
'category-file-count-limited'    => "Mae'r {{PLURAL:$1|dim ffeil|un ffeil|$1 ffeil|$1 ffeil|$1 ffeil|$1 ffeil}} canlynol yn y categori hwn.",
'listingcontinuesabbrev'         => 'parh.',
'index-category'                 => 'Tudalennau wedi eu mynegeio',
'noindex-category'               => 'Tudalennau heb eu mynegeio',

'mainpagetext'      => "'''Wedi llwyddo gosod meddalwedd MediaWiki yma'''",
'mainpagedocfooter' => 'Ceir cymorth (yn Saesneg) ar ddefnyddio meddalwedd wici yn y [http://meta.wikimedia.org/wiki/Help:Contents Canllaw Defnyddwyr] ar wefan Wikimedia.

==Cychwyn arni==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Rhestr gosodiadau wrth gyflunio]
* [http://www.mediawiki.org/wiki/Manual:FAQ Cwestiynau poblogaidd ar MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Rhestr postio datganiadau MediaWiki]',

'about'         => 'Ynglŷn â',
'article'       => 'Tudalen bwnc (erthygl/ffeil)',
'newwindow'     => '(yn agor mewn ffenest newydd)',
'cancel'        => 'Diddymu',
'moredotdotdot' => 'Rhagor...',
'mypage'        => 'Fy nhudalen',
'mytalk'        => 'Fy sgwrs',
'anontalk'      => 'Sgwrs ar gyfer y cyfeiriad IP hwn',
'navigation'    => 'Panel llywio',
'and'           => '&#32;a/ac',

# Cologne Blue skin
'qbfind'         => 'Canfod',
'qbbrowse'       => 'Pori',
'qbedit'         => 'Golygu',
'qbpageoptions'  => 'Y dudalen hon',
'qbpageinfo'     => 'Cyd-destun',
'qbmyoptions'    => 'Fy nhudalennau',
'qbspecialpages' => 'Tudalennau arbennig',
'faq'            => 'Cwestiynau cyffredin',
'faqpage'        => 'Project:Cwestiynau cyffredin',

# Vector skin
'vector-action-addsection'       => 'Ychwanegu adran',
'vector-action-delete'           => 'Dileu',
'vector-action-move'             => 'Symud',
'vector-action-protect'          => 'Diogelu',
'vector-action-undelete'         => 'Adfer',
'vector-action-unprotect'        => 'Dad-ddiogelu',
'vector-simplesearch-preference' => 'Galluogi awgrymiadau chwilio uwch (gwedd Vector yn unig)',
'vector-view-create'             => 'Dechrau',
'vector-view-edit'               => 'Golygu',
'vector-view-history'            => 'Gweld yr hanes',
'vector-view-view'               => 'Darllen',
'vector-view-viewsource'         => 'Dangos côd y dudalen',
'actions'                        => 'Gweithrediadau',
'namespaces'                     => 'Parthau',
'variants'                       => 'Amrywiolion',

'errorpagetitle'    => 'Gwall',
'returnto'          => 'Dychwelyd at $1.',
'tagline'           => 'Oddi ar {{SITENAME}}',
'help'              => 'Cymorth',
'search'            => 'Chwilio',
'searchbutton'      => 'Chwilio',
'go'                => 'Eler',
'searcharticle'     => 'Mynd',
'history'           => 'Hanes y dudalen',
'history_short'     => 'Hanes',
'updatedmarker'     => 'diwygiwyd ers i mi ymweld ddiwethaf',
'info_short'        => 'Gwybodaeth',
'printableversion'  => 'Fersiwn argraffu',
'permalink'         => 'Dolen barhaol',
'print'             => 'Argraffu',
'view'              => 'Darllen',
'edit'              => 'Golygu',
'create'            => 'Creu',
'editthispage'      => 'Golygwch y dudalen hon',
'create-this-page'  => "Creu'r dudalen",
'delete'            => 'Dileu',
'deletethispage'    => 'Dilëer y dudalen hon',
'undelete_short'    => 'Adfer $1 {{PLURAL:$1|golygiad|golygiad|olygiad|golygiad|golygiad|golygiad}}',
'viewdeleted_short' => "Edrych ar y {{PLURAL:$1|golygiad sydd wedi'i ddileu|golygiad sydd wedi'i ddileu|$1 olygiad sydd wedi'u dileu|$1 golygiad sydd wedi'u dileu|$1 golygiad sydd wedi'u dileu|$1 golygiad sydd wedi'u dileu}}",
'protect'           => 'Diogelu',
'protect_change'    => 'newid',
'protectthispage'   => "Diogelu'r dudalen hon",
'unprotect'         => 'Dad-ddiogelu',
'unprotectthispage' => "Dad-ddiogelu'r dudalen hon",
'newpage'           => 'Tudalen newydd',
'talkpage'          => 'Sgwrsiwch am y dudalen hon',
'talkpagelinktext'  => 'Sgwrs',
'specialpage'       => 'Tudalen Arbennig',
'personaltools'     => 'Offer personol',
'postcomment'       => 'Adran newydd',
'articlepage'       => 'Dangos tudalen bwnc',
'talk'              => 'Sgwrs',
'views'             => 'Golygon',
'toolbox'           => 'Blwch offer',
'userpage'          => 'Gweld tudalen y defnyddiwr',
'projectpage'       => 'Gweld tudalen y wici',
'imagepage'         => 'Gweld tudalen y ffeil',
'mediawikipage'     => 'Gweld tudalen y neges',
'templatepage'      => 'Dangos y dudalen nodyn',
'viewhelppage'      => 'Dangos y dudalen gymorth',
'categorypage'      => 'Dangos tudalen y categori',
'viewtalkpage'      => 'Gweld y sgwrs',
'otherlanguages'    => 'Ieithoedd eraill',
'redirectedfrom'    => '(Ailgyfeiriad oddi wrth $1)',
'redirectpagesub'   => 'Tudalen ailgyfeirio',
'lastmodifiedat'    => 'Newidiwyd y dudalen hon ddiwethaf $2, $1.',
'viewcount'         => "{{PLURAL:$1|Ni chafwyd dim|Cafwyd $1|Cafwyd $1|Cafwyd $1|Cafwyd $1|Cafwyd $1}} ymweliad â'r dudalen hon.",
'protectedpage'     => 'Tudalen a ddiogelwyd',
'jumpto'            => 'Neidio i:',
'jumptonavigation'  => 'llywio',
'jumptosearch'      => 'chwilio',
'view-pool-error'   => 'Ymddiheurwn, mae gormod o waith gan y gweinyddion ar hyn o bryd.
Mae gormod o ddefnyddwyr am weld y dudalen hon ar unwaith.
Arhoswch ychydig cyn ceisio mynd at y dudalen hon eto.

$1',
'pool-timeout'      => 'Cafwyd goroedi wrth aros am y clo',
'pool-queuefull'    => 'Mae cwt y gronfa brosesu yn llawn',
'pool-errorunknown' => 'Gwall anhysbys',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ynglŷn â {{SITENAME}}',
'aboutpage'            => 'Project:Amdanom',
'copyright'            => "Mae'r cynnwys ar gael o dan $1.",
'copyrightpage'        => '{{ns:project}}:Hawlfraint',
'currentevents'        => 'Materion cyfoes',
'currentevents-url'    => 'Project:Materion cyfoes',
'disclaimers'          => 'Gwadiadau',
'disclaimerpage'       => 'Project:Gwadiad Cyffredinol',
'edithelp'             => 'Help gyda golygu',
'edithelppage'         => 'Help:Golygu',
'helppage'             => 'Help:Cymorth',
'mainpage'             => 'Hafan',
'mainpage-description' => 'Hafan',
'policy-url'           => 'Project:Policy',
'portal'               => 'Porth y Gymuned',
'portal-url'           => 'Project:Porth y Gymuned',
'privacy'              => 'Polisi preifatrwydd',
'privacypage'          => 'Project:Polisi preifatrwydd',

'badaccess'        => 'Gwall caniatâd',
'badaccess-group0' => 'Ni chaniateir i chi wneud y weithred y gwnaethoch gais amdani.',
'badaccess-groups' => "Dim ond defnyddwyr o blith y {{PLURAL:$2|grwp|grwp|grwpiau|grwpiau|grwpiau|grwpiau}} $1 sy'n cael gwneud y weithred y gwnaethoch gais amdani.",

'versionrequired'     => 'Mae angen fersiwn $1 y meddalwedd MediaWiki',
'versionrequiredtext' => "Mae angen fersiwn $1 y meddalwedd MediaWiki er mwyn gwneud defnydd o'r dudalen hon. Gweler y dudalen am y [[Special:Version|fersiwn]].",

'ok'                      => 'Iawn',
'retrievedfrom'           => 'Wedi dod o "$1"',
'youhavenewmessages'      => 'Mae gennych chi $1 ($2).',
'newmessageslink'         => 'Neges(eueon) newydd',
'newmessagesdifflink'     => 'y newid diweddaraf',
'youhavenewmessagesmulti' => 'Mae negeseuon newydd gennych ar $1',
'editsection'             => 'golygu',
'editold'                 => 'golygu',
'viewsourceold'           => 'dangos y tarddiad',
'editlink'                => 'golygu',
'viewsourcelink'          => 'dangos côd y dudalen',
'editsectionhint'         => "Golygu'r adran: $1",
'toc'                     => 'Cynnwys',
'showtoc'                 => 'dangos',
'hidetoc'                 => 'cuddio',
'collapsible-collapse'    => 'Crebachu',
'collapsible-expand'      => 'Ehangu',
'thisisdeleted'           => 'Ydych chi am ddangos, neu ddad-ddileu $1?',
'viewdeleted'             => 'Gweld $1?',
'restorelink'             => "$1 {{PLURAL:$1|golygiad sydd wedi'i ddileu|golygiad sydd wedi'i ddileu|olygiad sydd wedi'u dileu|golygiad sydd wedi'u dileu|golygiad sydd wedi'u dileu|golygiad sydd wedi'u dileu}}",
'feedlinks'               => 'Porthiant:',
'feed-invalid'            => 'Math annilys o borthiant ar danysgrifiad.',
'feed-unavailable'        => 'Nid oes porthiant wedi ei gynghreirio ar gael',
'site-rss-feed'           => 'Porthiant RSS $1',
'site-atom-feed'          => 'Porthiant Atom $1',
'page-rss-feed'           => "Porthiant RSS '$1'",
'page-atom-feed'          => "Porthiant Atom '$1'",
'red-link-title'          => "$1 (does dim tudalen o'r enw hwn i gael)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tudalen',
'nstab-user'      => 'Tudalen defnyddiwr',
'nstab-media'     => 'Tudalen cyfrwng',
'nstab-special'   => 'Tudalen arbennig',
'nstab-project'   => 'Tudalen y wici',
'nstab-image'     => 'Ffeil',
'nstab-mediawiki' => 'Neges',
'nstab-template'  => 'Nodyn',
'nstab-help'      => 'Cymorth',
'nstab-category'  => 'Categori',

# Main script and global functions
'nosuchaction'      => "Dim gweithred o'r fath",
'nosuchactiontext'  => "Nid yw'r weithred sydd ynghlwm wrth y cyfeiriad URL yn un dilys.
Efallai eich bod wedi camdeipio'r URL, neu eich bod wedi dilyn cyswllt gwallus.
Neu efallai fod byg ar {{SITENAME}}.",
'nosuchspecialpage' => "Does dim tudalen arbennig o'r fath",
'nospecialpagetext' => "<strong>Dyw'r wici ddim yn adnabod y dudalen arbennig y gofynnwyd amdani.</strong>

Mae rhestr o'r tudalennau arbennig dilys i'w gael [[Special:SpecialPages|yma]].",

# General errors
'error'                => 'Gwall',
'databaseerror'        => 'Gwall databas',
'dberrortext'          => 'Mae gwall cystrawen wedi taro\'r databas.
Efallai fod gwall yn y meddalwedd.
Y gofyniad olaf y trïodd y databas oedd:
<blockquote><tt>$1</tt></blockquote>
o\'r ffwythiant "<tt>$2</tt>".
Rhoddwyd y côd gwall "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Mae gwall cystrawen wedi taro\'r databas.
Y gofyniad olaf y trïodd y databas oedd:
"$1"
o\'r ffwythiant "$2".
Rhoddwyd y côd gwall "$3: $4<".',
'laggedslavemode'      => "Rhybudd: hwyrach nad yw'r dudalen yn cynnwys diwygiadau diweddar.",
'readonly'             => 'Databas ar glo',
'enterlockreason'      => "Rhowch eglurhad dros gloi'r databas, ac amcangyfrif hyd at pa bryd y bydd y databas dan glo",
'readonlytext'         => "Mae'r databas ar glo; nid yw'n bosib cadw erthyglau newydd na gwneud unrhyw newid arall. Mae'n debygol fod hyn er mwyn cynnal a chadw'r databas -- fe fydd ar gael eto cyn bo hir.

Rhoddwyd y rheswm canlynol gan y gweinyddwr a'i glodd: $1",
'missing-article'      => 'Ni lwyddodd y databas i ddod o hyd i destun tudalen yr oedd disgwyl iddo ei gael, sef "$1" $2.

Fe ddigwydd gan amlaf wrth ddilyn hen gyswllt "gwahan" (gwahaniaeth rhwng tudalennau) neu hanes at dudalen sydd eisoes wedi ei dileu.

Onid dyma\'r achos, gall fod i chi ddod o hyd i fyg yn y meddalwedd.
A fyddech gystal â gwneud adroddiad ar hwn at [[Special:ListUsers/sysop|weinyddwr]], gan nodi\'r URL dan sylw.',
'missingarticle-rev'   => '(#golygiad: $1)',
'missingarticle-diff'  => '(Gwahaniaeth: $1, $2)',
'readonly_lag'         => "Mae'r databas wedi'i gloi'n awtomatig tra bod y gwas-weinyddion yn asio gyda'r prif weinydd",
'internalerror'        => 'Gwall mewnol',
'internalerror_info'   => 'Gwall mewnol: $1',
'fileappenderrorread'  => 'Wedi methu darllen "$1" yn ystod yr atodi.',
'fileappenderror'      => 'Ni ellid atodi "$1" wrth "$2".',
'filecopyerror'        => 'Wedi methu copïo\'r ffeil "$1" i "$2".',
'filerenameerror'      => "Wedi methu ail-enwi'r ffeil '$1' yn '$2'.",
'filedeleteerror'      => 'Wedi methu dileu\'r ffeil "$1".',
'directorycreateerror' => 'Wedi methu creu\'r cyfeiriadur "$1".',
'filenotfound'         => "Heb gael hyd i'r ffeil '$1'.",
'fileexistserror'      => 'Nid oes modd ysgrifennu i\'r ffeil "$1": ffeil eisoes ar glawr',
'unexpected'           => 'Gwerth annisgwyl: "$1"="$2".',
'formerror'            => 'Gwall: Wedi methu danfon y ffurflen',
'badarticleerror'      => "Mae'n amhosib cyflawni'r weithred hon ar y dudalen hon.",
'cannotdelete'         => "Mae'n amhosib dileu'r dudalen neu'r ddelwedd \"\$1\".
Efallai fod rhywun arall eisoes wedi'i dileu.",
'badtitle'             => 'Teitl gwael',
'badtitletext'         => "Mae'r teitl a ofynnwyd amdano yn annilys, yn wag, neu cysylltu'n anghywir rhwng ieithoedd neu wicïau. Gall fod ynddo un nod neu ragor na ellir eu defnyddio mewn teitlau.",
'perfcached'           => "Mae'r wybodaeth ganlynol yn gopi cadw; mae'n bosib nad y fersiwn diweddaraf ydyw.",
'perfcachedts'         => 'Rhoddwyd y data canlynol ar gadw mewn celc a ddiweddarwyd ddiwethaf am $1.',
'querypage-no-updates' => "Ar hyn o bryd, nid yw'r meddalwedd wedi ei osod i ddiweddaru data'r dudalen hon.",
'wrong_wfQuery_params' => 'Paramedrau anghywir i wfQuery()<br />
Ffwythiant: $1<br />
Gofyniad: $2',
'viewsource'           => 'Dangos côd y dudalen',
'viewsourcefor'        => 'ar gyfer $1',
'actionthrottled'      => 'Tagwyd y weithred',
'actionthrottledtext'  => "Mae camau gwrth-sbam y wici yn cyfyngu ar ba mor aml y gall defnyddwyr ailwneud y weithred hon mewn byr amser, ac rydych chi wedi croesi'r terfyn.
Ceisiwch eto ymhen rhai munudau.",
'protectedpagetext'    => "Mae'r dudalen hon wedi'i diogelu rhag cael ei golygu.",
'viewsourcetext'       => 'Cewch weld a chopïo côd y dudalen:',
'protectedinterface'   => 'Testun ar gyfer rhyngwyneb y wici yw cynnwys y dudalen hon. Clowyd y dudalen er mwyn ei diogeli.',
'editinginterface'     => "'''Dalier sylw:''' Rydych yn golygu tudalen sy'n rhan o destun rhyngwyneb y meddalwedd. Bydd newidiadau i'r dudalen hon yn effeithio ar y rhyngwyneb a ddefnyddir gan eraill. Os am gyfieithu'r neges, ystyriwch ddefnyddio [http://translatewiki.net/wiki/Main_Page?setlang=cy translatewiki.net], sef y prosiect MediaWiki sy'n hyrwyddo creu wicïau amlieithog.",
'sqlhidden'            => '(cuddiwyd chwiliad SQL)',
'cascadeprotected'     => "Diogelwyd y dudalen hon rhag ei newid, oherwydd ei bod wedi ei chynnwys yn y {{PLURAL:$1|dudalen ganlynol|dudalen ganlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol}}, a {{PLURAL:$1|honno yn ei thro wedi ei|honno yn ei thro wedi ei|rheiny yn eu tro wedi eu|rheiny yn eu tro wedi eu|rheiny yn eu tro wedi eu|rheiny yn eu tro wedi eu}} diogelu, a'r dewisiad 'sgydol' ynghynn:
$2",
'namespaceprotected'   => "Nid oes caniatâd gennych i olygu tudalennau yn y parth '''$1'''.",
'customcssjsprotected' => "Nid oes caniatad ganddoch i olygu'r dudalen hon oherwydd bod gosodiadau personol defnyddiwr arall arno.",
'ns-specialprotected'  => 'Ni ellir golygu tudalennau arbennig.',
'titleprotected'       => "Diogelwyd y teitl hwn rhag ei greu gan [[User:$1|$1]].
Rhoddwyd y rheswm hwn - ''$2''.",

# Virus scanner
'virus-badscanner'     => "Cyfluniad gwael: sganiwr firysau anhysbys: ''$1''",
'virus-scanfailed'     => 'methodd y sgan (côd $1)',
'virus-unknownscanner' => 'gwrthfirysydd anhysbys:',

# Login and logout pages
'logouttext'                 => "'''Rydych wedi allgofnodi.'''

Gallwch ddefnyddio {{SITENAME}} yn anhysbys, neu fe allwch [[Special:UserLogin|fewngofnodi eto]] wrth yr un un enw neu wrth enw arall.
Sylwer y bydd rhai tudalennau yn parhau i ymddangos fel ag yr oeddent pan oeddech wedi mewngofnodi hyd nes i chi glirio celc eich porwr.",
'welcomecreation'            => "==Croeso, $1!==
Mae eich cyfrif wedi'i greu.
Cofiwch osod y [[Special:Preferences|dewisiadau]] sydd fwyaf hwylus i chi ar {{SITENAME}}.",
'yourname'                   => 'Eich enw defnyddiwr:',
'yourpassword'               => 'Eich cyfrinair:',
'yourpasswordagain'          => 'Ail-deipiwch y cyfrinair:',
'remembermypassword'         => "Y porwr hwn i gofio'r manylion mewngofnodi (am hyd at $1 {{PLURAL:$1||diwrnod|ddiwrnod|diwrnod|diwrnod|diwrnod}})",
'securelogin-stick-https'    => "Cadw'r cyswllt â HTTPS ar ôl mewngofnodi",
'yourdomainname'             => 'Eich parth',
'externaldberror'            => "Naill ai: cafwyd gwall dilysu allanol ar databas neu: ar y llaw arall efallai nad oes hawl gennych chi i ddiwygio'ch cyfrif allanol.",
'login'                      => 'Mewngofnodi',
'nav-login-createaccount'    => 'Mewngofnodi',
'loginprompt'                => "Mae'n rhaid galluogi cwcis er mwyn mewngofnodi i {{SITENAME}}.",
'userlogin'                  => 'Mewngofnodi / creu cyfrif',
'userloginnocreate'          => 'Mewngofnodi',
'logout'                     => 'Allgofnodi',
'userlogout'                 => 'Allgofnodi',
'notloggedin'                => 'Nid ydych wedi mewngofnodi',
'nologin'                    => "Dim cyfrif gennych? '''$1'''.",
'nologinlink'                => 'Crëwch gyfrif',
'createaccount'              => 'Creu cyfrif newydd',
'gotaccount'                 => "Oes cyfrif gennych eisoes? '''$1'''.",
'gotaccountlink'             => 'Mewngofnodwch',
'createaccountmail'          => 'trwy e-bost',
'createaccountreason'        => 'Rheswm:',
'badretype'                  => "Nid yw'r cyfrineiriau'n union yr un fath.",
'userexists'                 => 'Mae rhywun arall wedi dewis yr enw defnyddiwr hwn. Dewiswch un arall os gwelwch yn dda.',
'loginerror'                 => 'Problem mewngofnodi',
'createaccounterror'         => "Ni lwyddwyd i greu'r cyfrif: $1",
'nocookiesnew'               => "Mae'r cyfrif defnyddiwr wedi cael ei greu, ond nid ydych wedi mewngofnodi. Mae {{SITENAME}} yn defnyddio cwcis wrth i ddefnyddwyr fewngofnodi. Rydych chi wedi analluogi cwcis. Mewngofnodwch eto gyda'ch enw defnyddiwr a'ch cyfrinair newydd os gwelwch yn dda, ar ôl galluogi cwcis.",
'nocookieslogin'             => 'Mae {{SITENAME}} yn defnyddio cwcis wrth i ddefnyddwyr fewngofnodi. Rydych chi wedi analluogi cwcis. Trïwch eto os gwelwch yn dda, ar ôl galluogi cwcis.',
'nocookiesfornew'            => 'Ni chrëwyd cyfrif defnyddiwr newydd, oherwydd na allem gadarnhau ei ffynhonnell.
Sicrhewch eich bod wedi galluogi cwcis, yna ail-lwythwch y dudalen hon a cheisiwch eto.',
'noname'                     => 'Dydych chi ddim wedi cynnig enw defnyddiwr dilys.',
'loginsuccesstitle'          => 'Llwyddodd y mewngofnodi',
'loginsuccess'               => "'''Yr ydych wedi mewngofnodi i {{SITENAME}} fel \"\$1\".'''",
'nosuchuser'                 => "Does yna'r un defnyddiwr â'r enw \"\$1\".
Mae'r rhaglen yn gwahaniaethu rhwng llythrennau bach a mawr.
Sicrhewch eich bod chi wedi sillafu'r enw'n gywir, neu [[Special:UserLogin/signup|crëwch gyfrif newydd]].",
'nosuchusershort'            => 'Does dim defnyddiwr o\'r enw "<nowiki>$1</nowiki>". Gwiriwch eich sillafu.',
'nouserspecified'            => "Mae'n rhaid nodi enw defnyddiwr.",
'login-userblocked'          => "Mae'r defnyddiwr hwn wedi ei flocio. Ni ellir mewngofnodi.",
'wrongpassword'              => "Nid yw'r cyfrinair a deipiwyd yn gywir. Rhowch gynnig arall arni, os gwelwch yn dda.",
'wrongpasswordempty'         => 'Roedd y cyfrinair yn wag. Rhowch gynnig arall arni.',
'passwordtooshort'           => "Mae'n rhaid fod gan gyfrinair o leia $1 {{PLURAL:$1|nod}}.",
'password-name-match'        => "Rhaid i'ch cyfrinair a'ch enw defnyddiwr fod yn wahanol i'w gilydd.",
'password-login-forbidden'   => "Gwaharddwyd defnyddio'r enw defnyddiwr a'r cyfrinair hwn.",
'mailmypassword'             => 'Anfoner cyfrinair newydd ataf trwy e-bost',
'passwordremindertitle'      => 'Hysbysu cyfrinair dros dro newydd ar gyfer {{SITENAME}}',
'passwordremindertext'       => 'Mae rhywun (chi mwy na thebyg, o\'r cyfeiriad IP $1) wedi gofyn i ni anfon cyfrinair newydd atoch ar gyfer {{SITENAME}} ($4).
Mae cyfrinair dros dro, sef "$3", wedi ei greu ar gyfer y defnyddiwr "$2". Os mai dyma oedd y bwriad, yna dylech fewngofnodi a\'i newid cyn gynted â phosib. Daw\'ch cyfrinair dros dro i ben ymhen {{PLURAL:$5||diwrnod|deuddydd|tridiau|$5 diwrnod|$5 diwrnod}}.

Os mai rhywun arall a holodd am y cyfrinair, ynteu eich bod wedi cofio\'r hen gyfrinair, ac nac ydych am newid y cyfrinair, rhydd i chi anwybyddu\'r neges hon a pharhau i ddefnyddio\'r cyfrinair gwreiddiol.',
'noemail'                    => "Does dim cyfeiriad e-bost yng nghofnodion y defnyddiwr '$1'.",
'noemailcreate'              => "Mae'n rhaid i chi gynnig cyfeiriad e-bost dilys",
'passwordsent'               => 'Mae cyfrinair newydd wedi\'i ddanfon at gyfeiriad e-bost cofrestredig "$1". Mewngofnodwch eto ar ôl i chi dderbyn y cyfrinair, os gwelwch yn dda.',
'blocked-mailpassword'       => 'Gan fod eich cyfeiriad IP wedi ei atal rhag golygu, ni ellir adfer y cyfrinair.',
'eauthentsent'               => 'Anfonwyd e-bost o gadarnhâd at y cyfeiriad a benwyd.
Cyn y gellir anfon unrhywbeth arall at y cyfeiriad hwnnw rhaid i chi ddilyn y cyfarwyddiadau yn yr e-bost hwnnw er mwyn cadarnhau bod y cyfeiriad yn un dilys.',
'throttled-mailpassword'     => "Anfonwyd e-bost atoch i'ch atgoffa o'ch cyfrinair eisoes, yn ystod y $1 {{PLURAL:$1|awr|awr|awr|awr|awr|awr}} diwethaf.
Er mwyn rhwystro camddefnydd, dim ond un e-bost i'ch atgoffa o'ch cyfrinair gaiff ei anfon bob yn $1 {{PLURAL:$1|awr|awr|awr|awr|awr|awr}}.",
'mailerror'                  => 'Gwall wrth ddanfon yr e-bost: $1',
'acct_creation_throttle_hit' => "Mae ymwelwyr sy'n defnyddio'ch cyfeiriad IP wedi creu $1 {{PLURAL:$1|cyfrif|cyfrif|gyfrif|chyfrif|chyfrif|cyfrif}} yn ystod y diwrnod diwethaf, sef y mwyafswm a ganiateir mewn diwrnod.
Felly ni chaiff defnyddwyr sy'n defnyddio'r cyfeiriad IP hwn greu rhagor o gyfrifon ar hyn o bryd.",
'emailauthenticated'         => 'Cadarnhawyd eich cyfeiriad e-bost am $3 ar $2.',
'emailnotauthenticated'      => "Nid yw eich cyfeiriad e-bost wedi'i ddilysu eto. Ni fydd unrhyw negeseuon e-bost yn cael eu hanfon atoch ar gyfer y nodweddion canlynol.",
'noemailprefs'               => "Mae'n rhaid i chi gynnig cyfeiriad e-bost er mwyn i'r nodweddion hyn weithio.",
'emailconfirmlink'           => 'Cadarnhewch eich cyfeiriad e-bost',
'invalidemailaddress'        => 'Ni allwn dderbyn y cyfeiriad e-bost gan fod ganddo fformat annilys. Mewnbynnwch cyfeiriad dilys neu gwagiwch y maes hwnnw, os gwelwch yn dda.',
'accountcreated'             => 'Crëwyd y cyfrif',
'accountcreatedtext'         => 'Crëwyd cyfrif defnyddiwr ar gyfer $1.',
'createaccount-title'        => 'Creu cyfrif ar {{SITENAME}}',
'createaccount-text'         => 'Creodd rhywun gyfrif o\'r enw $2 ar {{SITENAME}} ($4) ar gyfer y cyfeiriad e-bost hwn. "$3" yw\'r cyfrinair ar gyfer "$2". Dylech fewngofnodi a newid eich cyfrinair yn syth.

Rhydd ichi anwybyddu\'r neges hon os mai camgymeriad oedd creu\'r cyfrif.',
'usernamehasherror'          => 'Ni all enw defnyddiwr gynnwys symbolau stwnsh',
'login-throttled'            => 'Rydych wedi ceisio mewngofnodi gormod o weithiau ar unwaith.
Oedwch ychydig cyn mentro eto.',
'loginlanguagelabel'         => 'Iaith: $1',
'suspicious-userlogout'      => 'Gwrthodwyd eich cais i allgofnodi oherwydd ei fod yn ymddangos mai gweinydd wedi torri neu ddirprwy gelc a anfonodd y cais.',

# E-mail sending
'php-mail-error-unknown' => 'Gwall anhysbys yng ngweithrediad post() PHP',

# JavaScript password checks
'password-strength'            => 'Amcangyfrif o gryfder y cyfrinair: $1',
'password-strength-bad'        => 'GWAEL',
'password-strength-mediocre'   => 'tila',
'password-strength-acceptable' => 'derbyniol',
'password-strength-good'       => 'da',
'password-retype'              => 'Ail-deipiwch y cyfrinair fan hyn',
'password-retype-mismatch'     => 'Y cyfrineiriau yn wahanol',

# Password reset dialog
'resetpass'                 => 'Newid cyfrinair y cyfrif',
'resetpass_announce'        => "Fe wnaethoch fewngofnodi gyda chôd dros dro oddi ar e-bost.
Er mwyn cwblhau'r mewngofnodi, rhaid i chi osod cyfrinair newydd fel hyn:",
'resetpass_header'          => 'Newid cyfrinair y cyfrif',
'oldpassword'               => 'Hen gyfrinair:',
'newpassword'               => 'Cyfrinair newydd:',
'retypenew'                 => 'Ail-deipiwch y cyfrinair newydd:',
'resetpass_submit'          => 'Gosod y cyfrinair a mewngofnodi',
'resetpass_success'         => "Llwyddodd y newid i'ch cyfrinair! Wrthi'n mewngofnodi...",
'resetpass_forbidden'       => 'Ni ellir newid cyfrineiriau',
'resetpass-no-info'         => 'Ni allwch fynd at y dudalen hon yn uniongyrchol heblaw eich bod wedi mewngofnodi.',
'resetpass-submit-loggedin' => 'Newidier y cyfrinair',
'resetpass-submit-cancel'   => 'Diddymu',
'resetpass-wrong-oldpass'   => "Mae'r cyfrinair dros dro neu gyfredol yn annilys.
Gall fod eich bod wedi llwyddo newid eich cyfrinair eisoes neu eich bod wedi gofyn am gyfrinair dros dro newydd.",
'resetpass-temp-password'   => 'Cyfrinair dros dro:',

# Edit page toolbar
'bold_sample'     => 'Testun cryf',
'bold_tip'        => 'Testun cryf',
'italic_sample'   => 'Testun italig',
'italic_tip'      => 'Testun italig',
'link_sample'     => 'Teitl y cyswllt',
'link_tip'        => 'Cyswllt mewnol',
'extlink_sample'  => 'http://www.example.com teitl y cyswllt',
'extlink_tip'     => 'Cyswllt allanol (cofiwch y rhagddodiad http:// )',
'headline_sample' => 'Testun pennawd',
'headline_tip'    => 'Pennawd lefel 2',
'math_sample'     => 'Gosodwch fformwla yma',
'math_tip'        => 'Fformwla mathemategol (LaTeX)',
'nowiki_sample'   => 'Rhowch destun di-fformatedig yma',
'nowiki_tip'      => "Anwybyddu'r gystrawen wici",
'image_sample'    => 'Enghraifft.jpg',
'image_tip'       => 'Ffeil mewnosodol',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Cyswllt ffeil media',
'sig_tip'         => 'Eich llofnod gyda stamp amser',
'hr_tip'          => "Llinell lorweddol (peidiwch â'i gor-ddefnyddio)",

# Edit pages
'summary'                          => 'Crynodeb:',
'subject'                          => 'Pwnc/pennawd:',
'minoredit'                        => 'Golygiad bychan yw hwn',
'watchthis'                        => 'Gwylier y dudalen hon',
'savearticle'                      => "Cadw'r dudalen",
'preview'                          => 'Rhagolwg',
'showpreview'                      => 'Dangos rhagolwg',
'showlivepreview'                  => 'Rhagolwg byw',
'showdiff'                         => 'Dangos newidiadau',
'anoneditwarning'                  => "'''Dalier sylw''': Nid ydych wedi mewngofnodi. Fe fydd eich cyfeiriad IP yn ymddangos ar hanes golygu'r dudalen hon. Gallwch ddewis cuddio'ch cyfeiriad IP drwy greu cyfrif (a mewngofnodi) cyn golygu.",
'anonpreviewwarning'               => "''Nid ydych wedi mewngofnodi. Os y cadwch eich newidiadau caiff eich cyfeiriad IP ei gofnodi yn hanes golygu'r dudalen hon.''",
'missingsummary'                   => "'''Sylwer:''' Nid ydych wedi gosod nodyn yn y blwch 'Crynodeb'.
Os y pwyswch eto ar 'Cadw'r dudalen' caiff y golygiad ei gadw heb nodyn.",
'missingcommenttext'               => 'Rhowch eich sylwadau isod.',
'missingcommentheader'             => "'''Nodyn:''' Nid ydych wedi cynnig unrhywbeth yn y blwch 'Pwnc/Pennawd:'. Os y cliciwch \"{{int:savearticle}}\" eto fe gedwir y golygiad heb bennawd.",
'summary-preview'                  => "Rhagolwg o'r crynodeb:",
'subject-preview'                  => 'Rhagolwg pwnc/pennawd:',
'blockedtitle'                     => "Mae'r defnyddiwr hwn wedi cael ei flocio",
'blockedtext'                      => "'''Mae eich enw defnyddiwr neu gyfeiriad IP wedi cael ei flocio.'''

$1 a osododd y bloc.
Y rheswm a roddwyd dros y blocio yw: ''$2''.

*Dechreuodd y bloc am: $8
*Bydd y bloc yn dod i ben am: $6
*Bwriadwyd blocio: $7

Gallwch gysylltu â $1 neu un arall o'r [[{{MediaWiki:Grouppage-sysop}}|gweinyddwyr]] i drafod y bloc.
Sylwch mai dim ond y rhai sydd wedi gosod cyfeiriad e-bost yn eu [[Special:Preferences|dewisiadau defnyddiwr]], a hwnnw heb ei flocio, sydd yn gallu 'anfon e-bost at ddefnyddiwr' trwy'r wici.
$3 yw eich cyfeiriad IP presennol. Cyfeirnod y bloc yw #$5.
Pan yn ysgrifennu at weinyddwr, cofiwch gynnwys yr holl fanylion uchod, os gwelwch yn dda.",
'autoblockedtext'                  => "Rhoddwyd bloc yn awtomatig ar eich cyfeiriad IP oherwydd iddo gael ei ddefnyddio gan ddefnyddiwr arall, a bod bloc wedi ei roi ar hwnnw gan $1.
Y rheswm a roddwyd dros y bloc oedd:

:''$2''

*Dechreuodd y bloc am: $8
*Daw'r bloc i ben am: $6
*Bwriadwyd blocio: $7

Gallwch gysylltu â $1 neu un arall o'r [[{{MediaWiki:Grouppage-sysop}}|gweinyddwyr]] i drafod y bloc.

Sylwch mai dim ond y rhai sydd wedi gosod cyfeiriad e-bost yn eu [[Special:Preferences|dewisiadau defnyddiwr]], a hwnnw heb ei flocio, sydd yn gallu 'anfon e-bost at ddefnyddiwr' trwy'r wici.

Eich cyfeiriad IP presennol yw $3. Cyfeirnod y bloc yw $5. Nodwch y manylion hyn wrth drafod y bloc.",
'blockednoreason'                  => 'dim rheswm wedi ei roi',
'blockedoriginalsource'            => "Dangosir côd '''$1''' isod:",
'blockededitsource'                => "Dangosir testun '''eich golygiadau''' ar '''$1''' isod:",
'whitelistedittitle'               => 'Rhaid mewngofnodi cyn golygu',
'whitelistedittext'                => 'Rhaid $1 i olygu tudalennau.',
'confirmedittext'                  => "Mae'n rhaid i chi gadarnhau eich cyfeiriad e-bost cyn y gallwch ddechrau golygu tudalennau.
Gosodwch eich cyfeiriad e-bost drwy eich [[Special:Preferences|dewisiadau defnyddiwr]] ac yna'i gadarnhau, os gwelwch yn dda.",
'nosuchsectiontitle'               => "Heb ddod o hyd i'r adran",
'nosuchsectiontext'                => "Rydych wedi ceisio golygu adran nad ydy'n bod.
Efallai bod yr adran wedi cael ei symud neu ei dileu ers i chi agor y dudalen.",
'loginreqtitle'                    => 'Mae angen mewngofnodi',
'loginreqlink'                     => 'mewngofnodi',
'loginreqpagetext'                 => "Mae'n rhaid $1 er mwyn gweld tudalennau eraill.",
'accmailtitle'                     => 'Wedi danfon y cyfrinair.',
'accmailtext'                      => "Anfonwyd cyfrinair a grewyd ar hap ar gyfer [[User talk:$1|$1]] at $2.

Gellir newid y cyfrinair ar gyfer y cyfrif newydd hwn ar y dudalen ''[[Special:ChangePassword|newid cyfrinair]]'', wedi i chi fewngofnodi.",
'newarticle'                       => '(Newydd)',
'newarticletext'                   => "Rydych chi wedi dilyn cysylltiad i dudalen sydd heb gael ei chreu eto.
I greu'r dudalen, dechreuwch deipio yn y blwch isod (gweler y [[{{MediaWiki:Helppage}}|dudalen gymorth]] am fwy o wybodaeth).
Os daethoch yma ar ddamwain, cliciwch botwm '''n&ocirc;l''' y porwr.",
'anontalkpagetext'                 => "----''Dyma dudalen sgwrs ar gyfer defnyddiwr anhysbys sydd heb greu cyfrif eto, neu nad yw'n ei ddefnyddio. Felly mae'n rhaid inni ddefnyddio'r cyfeiriad IP i'w (h)adnabod. Mae cyfeiriadau IP yn gallu cael eu rhannu rhwng nifer o ddefnyddwyr. Os ydych chi'n ddefnyddiwr anhysbys ac yn teimlo'ch bod wedi derbyn sylwadau amherthnasol, [[Special:UserLogin/signup|crëwch gyfrif]] neu [[Special:UserLogin|mewngofnodwch]] i osgoi cael eich drysu gyda defnyddwyr anhysbys eraill o hyn ymlaen.''",
'noarticletext'                    => "Mae'r dudalen hon yn wag ar hyn o bryd.
Gallwch [[Special:Search/{{PAGENAME}}|chwilio am y teitl hwn]] ar dudalennau eraill, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} chwilio drwy'r logiau perthnasol], neu [{{fullurl:{{FULLPAGENAME}}|action=edit}} golygu'r dudalen]</span>.",
'noarticletext-nopermission'       => 'Mae\'r dudalen hon yn wag ar hyn o bryd.
Gallwch [[Special:Search/{{PAGENAME}}|chwilio am y teitl hwn]] ar dudalennau eraill, neu gallwch <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} chwilio drwy\'r logiau perthnasol]</span>.',
'userpage-userdoesnotexist'        => 'Nid oes defnyddiwr a\'r enw "$1" yn bod. Gwnewch yn siwr eich bod am greu/golygu\'r dudalen hon.',
'userpage-userdoesnotexist-view'   => 'Nid yw\'r cyfrif defnyddiwr "$1" wedi ei gofrestri.',
'blocked-notice-logextract'        => "Mae'r defnyddiwr hwn wedi ei flocio ar hyn o bryd.
Dyma'r cofnod lòg diweddaraf, er gwybodaeth:",
'clearyourcache'                   => "'''Sylwer - Wedi i chi roi'r dudalen ar gadw, efallai y bydd angen mynd heibio celc eich porwr er mwyn gweld y newidiadau.'''
'''Mozilla / Firefox / Safari:''' pwyswch ar ''Shift'' tra'n clicio ''Ail-lwytho/Reload'', neu gwasgwch ''Ctrl-F5'' neu ''Ctrl-R'' (''Command-R'' ar Macintosh); '''Konqueror:''' cliciwch y botwm ''Ail-lwytho/Reload'', neu gwasgwch ''F5''; '''Opera:''' gwacewch y celc yn llwyr trwy ''Offer → Dewisiadau / Tools→Preferences''; '''Internet Explorer:''' pwyswch ar ''Ctrl'' tra'n clicio ''Adnewyddu/Refresh'', neu gwasgwch ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Tip:''' Defnyddiwch y botwm \"{{int:showpreview}}\" er mwyn profi eich CSS newydd cyn ei gadw.",
'userjsyoucanpreview'              => "'''Tip:''' Defnyddiwch y botwm \"{{int:showpreview}}\" er mwyn profi eich JS newydd cyn ei gadw.",
'usercsspreview'                   => "'''Cofiwch - dim ond rhagolwg o'ch CSS defnyddiwr yw hwn.'''
'''Nid yw wedi'i gadw eto!'''",
'userjspreview'                    => "'''Cofiwch -- dim ond rhagolwg o'ch JavaScript yw hwn; nid yw wedi'i gadw eto!'''",
'sitecsspreview'                   => "'''Cofiwch - dim ond rhagolwg o'ch CSS yw hwn.'''
'''Nid yw wedi'i gadw eto!'''",
'sitejspreview'                    => "'''Cofiwch - dim ond rhagolwg o'ch côd JavaScript yw hwn.'''
'''Nid yw wedi'i rhoi ar gadw eto!'''",
'userinvalidcssjstitle'            => "'''Rhybudd:''' Nid oes gwedd o'r enw \"\$1\".
Cofiwch bod y tudalennau .css a .js yn defnyddio llythrennau bach, e.e. {{ns:user}}:Foo/vector.css yn hytrach na {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Diweddariad)',
'note'                             => "'''Dalier sylw:'''",
'previewnote'                      => "'''Cofiwch taw rhagolwg yw hwn; nid yw'r dudalen wedi ei chadw eto.'''",
'previewconflict'                  => "Mae'r rhagolwg hwn yn dangos y testun yn yr ardal golygu uchaf, fel ag y byddai'n ymddangos petaech yn rhoi'r dudalen ar gadw.",
'session_fail_preview'             => "'''Ymddiheurwn! Methwyd prosesu eich golygiad gan fod rhan o ddata'r sesiwn wedi'i golli. Ceisiwch eto.
Os digwydd yr un peth eto, ceisiwch [[Special:UserLogout|allgofnodi]] ac yna mewngofnodi eto.'''",
'session_fail_preview_html'        => "'''Ymddiheurwn! Methwyd prosesu eich golygiad gan fod rhan o ddata'r sesiwn wedi'i golli.'''

''Oherwydd bod HTML amrwd ar waith ar {{SITENAME}}, cuddir y rhagolwg er mwyn gochel rhag ymosodiad JavaScript.''

'''Os ydych am wneud golygiad dilys, ceisiwch eto.
Os methwch unwaith eto, ceisiwch [[Special:UserLogout|allgofnodi]] ac yna mewngofnodi unwaith eto.'''",
'token_suffix_mismatch'            => "'''Gwrthodwyd eich golygiad oherwydd bod eich gweinydd cleient wedi gwneud cawl o'r atalnodau yn y tocyn golygu.
Gwrthodwyd y golygiad rhag i destun y dudalen gael ei lygru.
Weithiau fe ddigwydd hyn wrth ddefnyddio dirprwy-wasanaeth anhysbys gwallus yn seiliedig ar y we.'''",
'editing'                          => 'Yn golygu $1',
'editingsection'                   => 'Yn golygu $1 (adran)',
'editingcomment'                   => 'Yn golygu $1 (adran newydd)',
'editconflict'                     => 'Cyd-ddigwyddiad golygu: $1',
'explainconflict'                  => "Mae rhywun arall wedi newid y dudalen hon ers i chi ddechrau ei golygu hi.
Mae'r blwch testun uchaf yn cynnwys testun y dudalen fel y mae hi rwan.
Mae eich newidiadau chi yn ymddangos yn y blwch testun isaf.
Bydd yn rhaid i chi gyfuno eich newidiadau chi a'r testun sydd yn bodoli eisoes.
'''Dim ond''' y testun yn y blwch testun '''uchaf''' fydd yn cael ei roi ar gadw pan wasgwch y botwm \"{{int:savearticle}}\".",
'yourtext'                         => 'Eich testun',
'storedversion'                    => 'Y golygiad diweddaraf yn y storfa',
'nonunicodebrowser'                => "'''RHYBUDD: Nid yw eich porwr yn cydymffurfio ag Unicode. Serch hyn, mae modd i chi olygu tudalennau: bydd nodau sydd ddim yn rhan o ASCII yn ymddangos yn y blwch golygu fel codau hecsadegol.'''",
'editingold'                       => "'''RHYBUDD: Rydych chi'n golygu hen ddiwygiad o'r dudalen hon. Os caiff ei chadw, bydd unrhyw newidiadau diweddarach yn cael eu colli.'''",
'yourdiff'                         => 'Gwahaniaethau',
'copyrightwarning'                 => "Mae pob cyfraniad i {{SITENAME}} yn cael ei ryddhau o dan termau'r Drwydded Ddogfen Rhydd ($2) (gwelwch $1 am fanylion). Os nad ydych chi'n fodlon i'ch gwaith gael ei olygu heb drugaredd, neu i gopïau ymddangos ar draws y we, peidiwch a'i gyfrannu yma.<br />
Rydych chi'n cadarnhau mai chi yw awdur y cyfraniad, neu eich bod chi wedi'i gopïo o'r parth cyhoeddus (''public domain'') neu rywle rhydd tebyg. '''Nid''' yw'r mwyafrif o wefannau yn y parth cyhoeddus.

'''PEIDIWCH Â CHYFRANNU GWAITH O DAN HAWLFRAINT HEB GANIATÂD!'''",
'copyrightwarning2'                => "Sylwch fod pob cyfraniad i {{SITENAME}} yn cael ei ryddhau o dan termau'r Drwydded Ddogfen Rhydd (gwelwch $1 am fanylion).
Os nad ydych chi'n fodlon i'ch gwaith gael ei olygu heb drugaredd, neu i gopïau ymddangos ar draws y we, peidiwch a'i gyfrannu yma.<br />
Rydych chi'n cadarnhau mai chi yw awdur y cyfraniad, neu eich bod chi wedi'i gopïo o'r parth cyhoeddus (''public domain'') neu rywle rhydd tebyg.<br />
'''PEIDIWCH Â CHYFRANNU GWAITH O DAN HAWLFRAINT HEB GANIATÂD!'''",
'longpageerror'                    => "'''GWALL: Mae'r testun yr ydych wedi ei osod yma yn $1 cilobeit o hyd, ac yn hwy na'r hyd eithaf o $2 cilobeit.
Ni ellir ei roi ar gadw.'''",
'readonlywarning'                  => "'''RHYBUDD: Mae'r databas wedi'i gloi am gyfnod er mwyn cynnal a chadw, felly fyddwch chi ddim yn gallu cadw'ch golygiadau ar hyn o bryd. Rydyn ni'n argymell eich bod chi'n copïo a gludo'r testun i ffeil a'i gadw ar eich disg tan bod y sustem yn weithredol eto.'''

Cynigiodd y gweinyddwr a glodd y databas y rheswm hwn dros ei gloi: $1",
'protectedpagewarning'             => "'''RHYBUDD: Mae'r dudalen hon wedi'i diogelu. Dim ond gweinyddwyr sydd yn gallu ei golygu.'''
Dyma'r cofnod lòg diweddaraf, er gwybodaeth:",
'semiprotectedpagewarning'         => "'''Sylwer:''' Mae'r dudalen hon wedi ei chloi; dim ond defnyddwyr cofrestredig a allant ei golygu.
Dyma'r cofnod lòg diweddaraf, er gwybodaeth:",
'cascadeprotectedwarning'          => "'''Dalier sylw:''' Mae'r dudalen hon wedi ei diogelu fel nad ond defnyddwyr â galluoedd gweinyddwyr sy'n gallu ei newid, oherwydd ei bod yn rhan o'r {{PLURAL:$1|dudalen ganlynol|dudalen ganlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol}} sydd wedi {{PLURAL:$1|ei|ei|eu|eu|eu|eu}} sgydol-ddiogelu.",
'titleprotectedwarning'            => "'''RHYBUDD:  Mae'r dudalen hon wedi ei chloi; dim ond rhai defnyddwyr sydd â'r [[Special:ListGroupRights|gallu]] i'w chreu.'''
Dyma'r cofnod lòg diweddaraf, er gwybodaeth:",
'templatesused'                    => 'Defnyddir y {{PLURAL:$1|nodyn hwn|nodyn hwn|nodiadau hyn|nodiadau hyn|nodiadau hyn|nodiadau hyn}} yn y dudalen hon:',
'templatesusedpreview'             => 'Defnyddir y {{PLURAL:$1|nodyn hwn|nodyn hwn|nodiadau hyn|nodiadau hyn|nodiadau hyn|nodiadau hyn}} yn y rhagolwg hwn:',
'templatesusedsection'             => 'Defnyddir y {{PLURAL:$1|nodyn hwn|nodyn hwn|nodiadau hyn|nodiadau hyn|nodiadau hyn|nodiadau hyn}} yn yr adran hon:',
'template-protected'               => '(wedi ei diogelu)',
'template-semiprotected'           => '(lled-diogelwyd)',
'hiddencategories'                 => "Mae'r dudalen hon yn aelod o $1 {{PLURAL:$1|categori|categori|gategori|chategori|chategori|categori}} cuddiedig:",
'nocreatetitle'                    => 'Cyfyngwyd ar greu tudalennau',
'nocreatetext'                     => "Mae'r safle hwn wedi cyfyngu'r gallu i greu tudalennau newydd. Gallwch olygu tudalen sydd eisoes yn bodoli, neu [[Special:UserLogin|fewngofnodi, neu greu cyfrif]].",
'nocreate-loggedin'                => "Nid yw'r gallu gennych i greu tudalennau.",
'sectioneditnotsupported-title'    => 'Dim modd golygu fesul adran',
'sectioneditnotsupported-text'     => "Nid oes modd golygu'r dudalen hon fesul adran",
'permissionserrors'                => 'Gwallau Caniatâd',
'permissionserrorstext'            => "Nid yw'r gallu ganddoch i weithredu yn yr achos yma, am y {{PLURAL:$1|rheswm|rheswm|rhesymau|rhesymau|rhesymau|rhesymau}} canlynol:",
'permissionserrorstext-withaction' => "Nid yw'r gallu hwn ($2) ganddoch, am y {{PLURAL:$1|rheswm|rheswm|rhesymau|rhesymau|rhesymau|rhesymau}} canlynol:",
'recreate-moveddeleted-warn'       => "'''Dalier sylw: Rydych yn ail-greu tudalen a ddilewyd rhywdro.'''

Ystyriwch a fyddai'n dda o beth i barhau i olygu'r dudalen hon.
Dyma'r logiau dileu a symud ar gyfer y dudalen, er gwybodaeth:",
'moveddeleted-notice'              => 'Dilëwyd y dudalen hon.
Dangosir y logiau dileu a symud ar gyfer y dudalen isod.',
'log-fulllog'                      => 'Gweld y lòg cyflawn',
'edit-hook-aborted'                => 'Terfynwyd y golygiad cyn pryd gan fachyn.
Ni roddodd eglurhad.',
'edit-gone-missing'                => "Ni ellid diweddaru'r dudalen.
Ymddengys iddi gael ei dileu.",
'edit-conflict'                    => 'Cyd-ddigwyddiad golygu.',
'edit-no-change'                   => 'Anwybyddwyd eich golygiad, gan na newidiwyd y testun.',
'edit-already-exists'              => 'Ni ellid creu tudalen newydd.
Mae ar gael yn barod.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Rhybudd:''' Mae gormod o alwadau ar ffwythiannau dosrannu sy'n dreth ar adnoddau yn y dudalen hon.

Dylai fod llai na $2 {{PLURAL:$2|galwad|alwad|alwad|galwad|galwad|galwad}} yn y dudalen, ond ar hyn o bryd mae $1 {{PLURAL:$1|galwad|alwad|alwad|galwad|galwad|galwad}} ynddi.",
'expensive-parserfunction-category'       => "Tudalennau a gormod o alwadau ar ffwythiannau dosrannu sy'n dreth ar adnoddau",
'post-expand-template-inclusion-warning'  => "Rhybudd: Mae'r maint cynnwys nodyn yn rhy fawr.
Ni chaiff rhai nodiadau eu cynnwys.",
'post-expand-template-inclusion-category' => "Tudalennau a phatrymlun ynddynt sy'n fwy na chyfyngiad y meddalwedd",
'post-expand-template-argument-warning'   => "'''Rhybudd:''' Mae gan y dudalen hon o leiaf un arg nodyn sydd a maint ehangu rhy fawr.
Cafodd yr argiau hyn eu hepgor.",
'post-expand-template-argument-category'  => 'Tudalennau lle ceir argiau nodiadau coll',
'parser-template-loop-warning'            => 'Daethpwyd o hyd i ddolen yn y nodyn: [[$1]]',
'parser-template-recursion-depth-warning' => 'Wedi mynd dros ben y terfyn ar ddyfnder dychweliad nodiadau ($1)',
'language-converter-depth-warning'        => "Wedi mynd tu hwnt i'r terfyn dyfnder ($1) ar y cyfnewidydd iaith.",

# "Undo" feature
'undo-success' => "Gellir dadwneud y golygiad. Byddwch gystal â gwirio'r gymhariaeth isod i sicrhau mai dyma sydd arnoch eisiau gwneud, ac yna rhowch y newidiadau ar gadw i gwblhau'r gwaith o ddadwneud y golygiad.",
'undo-failure' => 'Methwyd a dadwneud y golygiad oherwydd gwrthdaro â golygiadau cyfamserol.',
'undo-norev'   => "Ni ellid dadwneud y golygiad oherwydd nad yw'n bod neu iddo gael ei ddileu.",
'undo-summary' => 'Dadwneud y golygiad $1 gan [[Special:Contributions/$2|$2]] ([[User talk:$2|Sgwrs]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]])',

# Account creation failure
'cantcreateaccounttitle' => 'Yn methu creu cyfrif',
'cantcreateaccount-text' => "Rhwystrwyd y gallu i greu cyfrif ar gyfer y cyfeiriad IP hwn, ('''$1'''), gan [[User:$3|$3]].

Y rheswm a roddwyd dros y bloc gan $3 yw ''$2''.",

# History pages
'viewpagelogs'           => "Dangos logiau'r dudalen hon",
'nohistory'              => "Does dim hanes golygu i'r dudalen hon.",
'currentrev'             => 'Diwygiad cyfoes',
'currentrev-asof'        => 'Y diwygiad cyfredol, am $1',
'revisionasof'           => 'Diwygiad $1',
'revision-info'          => 'Y fersiwn a roddwyd ar gadw am $1 gan $2',
'previousrevision'       => '← At y diwygiad blaenorol',
'nextrevision'           => 'At y diwygiad dilynol →',
'currentrevisionlink'    => 'Y diwygiad cyfoes',
'cur'                    => 'cyf',
'next'                   => 'nesaf',
'last'                   => 'cynt',
'page_first'             => 'cyntaf',
'page_last'              => 'olaf',
'histlegend'             => "Cymharu dau fersiwn: marciwch y cylchoedd ar y ddau fersiwn i'w cymharu, yna pwyswch ar 'return' neu'r botwm 'Cymharer y fersiynau dewisedig'.<br />
Eglurhad: '''({{int:cur}})''' = gwahaniaethau rhyngddo a'r fersiwn cyfredol,
'''({{int:last}})''' = gwahaniaethau rhyngddo a'r fersiwn cynt, '''({{int:minoreditletter}})''' = golygiad bychan",
'history-fieldset-title' => "Chwilio drwy'r hanes",
'history-show-deleted'   => 'Y rhai a ddilëwyd yn unig',
'histfirst'              => 'Cynharaf',
'histlast'               => 'Diweddaraf',
'historysize'            => '({{PLURAL:$1|$1 beit|$1 beit|$1 feit|$1 beit|$1 beit|$1 beit}})',
'historyempty'           => '(gwag)',

# Revision feed
'history-feed-title'          => 'Hanes diwygio',
'history-feed-description'    => "Hanes diwygio'r dudalen hon ar y wici",
'history-feed-item-nocomment' => '$1 am $2',
'history-feed-empty'          => "Nid yw'r dudalen a ofynwyd amdani'n bod.
Gall fod iddi gael ei dileu neu ei hailenwi.
Gallwch [[Special:Search|chwilio'r]] wici am dudalennau eraill perthnasol.",

# Revision deletion
'rev-deleted-comment'         => '(sylw wedi ei ddileu)',
'rev-deleted-user'            => '(enw defnyddiwr wedi ei ddiddymu)',
'rev-deleted-event'           => '(tynnwyd gweithred y lòg)',
'rev-deleted-user-contribs'   => '[tynnwyd enw defnyddiwr neu gyfeiriad IP i ffwrdd - ni ddangosir y golygiad ar y rhestr cyfraniadau]',
'rev-deleted-text-permission' => "'''Dilewyd''' y diwygiad hwn o'r dudalen.
Hwyrach bod manylion pellach ar y [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} lòg dileu].",
'rev-deleted-text-unhide'     => "Cafodd y diwygiad hwn o'r dudalen ei '''ddileu'''.
Gweler cofnod y dileu ar y [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} lòg dileu].
Gan eich bod yn weinyddwr gallwch [$1 weld y diwygiad] os y mynnwch.",
'rev-suppressed-text-unhide'  => "Mae’r diwygiad hwn o’r dudalen wedi cael ei '''guddio'''.
Cewch weld y manylion ar y [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} lòg cuddio].
Gan eich bod yn weinyddwr cewch [$1 weld y golygiad] o hyd os y dymunwch.",
'rev-deleted-text-view'       => "'''Dilewyd''' y diwygiad hwn o'r dudalen.
Gan eich bod yn weinyddwr gallwch ei weld; gall fod manylion yn y [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} lòg dileu].",
'rev-suppressed-text-view'    => "Mae’r diwygiad hwn o’r dudalen wedi cael ei '''guddio'''.
Gan eich bod yn weinyddwr cewch weld y golygiad;  mae'r manylion ar gael ar y [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} lòg cuddio].",
'rev-deleted-no-diff'         => "Ni allwch gymharu'r diwygiadau oherwydd bod un ohonynt wedi cael ei '''ddileu'''.
Cewch weld y manylion ar y [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} lòg dileu].",
'rev-suppressed-no-diff'      => "Ni allwch gymharu'r diwygiadau oherwydd bod un ohonynt wedi cael ei '''ddileu'''.",
'rev-deleted-unhide-diff'     => "Cafodd un o'r diwygiadau yr ydych am eu cymharu ei '''ddileu'''.
Cewch weld y manylion ar y [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} lòg dileu].
Gan eich bod yn weinyddwr gallwch [$1 weld y gwahaniaeth] rhyngddynt o hyd, os y dymunwch.",
'rev-suppressed-unhide-diff'  => "Cafodd un o'r diwygiadau yr ydych am eu cymharu ei '''guddio'''.
Cewch weld y manylion ar y [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} lòg cuddio].
Gan eich bod yn weinyddwr gallwch [$1 weld y gwahaniaeth] rhyngddynt o hyd, os y dymunwch.",
'rev-deleted-diff-view'       => "Cafodd un o'r diwygiadau yr ydych am eu cymharu ei '''ddileu'''.
Gan eich bod yn weinyddwr gallwch eu cymharu o hyd; cewch weld y manylion ar y [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} lòg dileu].",
'rev-suppressed-diff-view'    => "Cafodd un o'r diwygiadau yr ydych am eu cymharu ei '''guddio'''.
Gan eich bod yn weinyddwr gallwch eu cymharu o hyd; cewch weld y manylion ar y [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} lòg cuddio].",
'rev-delundel'                => 'dangos/cuddio',
'rev-showdeleted'             => 'dangos',
'revisiondelete'              => 'Dileu/dad-ddileu diwygiadau',
'revdelete-nooldid-title'     => 'Anelwyd at olygiad annilys',
'revdelete-nooldid-text'      => "Naill ai; nid ydych wedi dynodi diwygiad yn darged y weithred, neu nid yw'r diwygiad penodedig yn bod, neu rydych wedi ceisio cuddio'r diwygiad presennol.",
'revdelete-nologtype-title'   => 'Ni nodwyd y math o lòg',
'revdelete-nologtype-text'    => "Nid ydych wedi enwi'r math o lòg yr ydych am weithredu arno.",
'revdelete-nologid-title'     => 'Cofnod lòg annilys',
'revdelete-nologid-text'      => "Ni enwyd y lòg yr ydych am weithio arno ynteu nid yw'r lòg a enwyd yn bod.",
'revdelete-no-file'           => "Nid yw'r ffeil a nodwyd yn bod.",
'revdelete-show-file-confirm' => 'Ydych chi\'n sicr eich bod am weld y diwygiad dilëedig o\'r ffeil "<nowiki>$1</nowiki>" a roddwyd ar gadw am $3 ar $2?',
'revdelete-show-file-submit'  => 'Ydw',
'revdelete-selected'          => "'''Y {{PLURAL:$2|golygiad|golygiad|golygiadau|golygiadau|golygiadau|golygiadau}} dewisedig o [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Digwyddiad|Digwyddiad|Digwyddiadau|Digwyddiadau|Digwyddiadau|Digwyddiadau}} a ddewiswyd o'r lòg:'''",
'revdelete-text'              => "'''Fe fydd y golygiadau a'r digwyddiadau sydd wedi eu diddymu i'w gweld o hyd yn hanes y dudalen ac yn y logiau, ond ni fydd y cyhoedd yn gallu gweld y cynnwys i gyd.'''
Fe fydd gweinyddwyr eraill {{SITENAME}} o hyd yn gallu gweld yr hyn a guddiwyd. Fe allant ei ddatguddio trwy ddefnyddio'r dudalen arbennig hon, cyhyd ag nad oes cyfyngiadau ychwanegol wedi eu gosod.",
'revdelete-confirm'           => "Byddwch gystal â chadarnhau eich bod yn bwriadu gwneud hyn, eich bod yn deall yr effaith a gaiff, a'ch bod yn ei wneud yn ôl y [[{{MediaWiki:Policy-url}}|y polisi]].",
'revdelete-suppress-text'     => "'''Dim ond''' yn yr achosion sy'n dilyn y dylech fentro cuddio gwybodaeth:
* Gwybodaeth bersonol anaddas
*: ''cyfeiriad cartref, rhif ffôn, rhif yswiriant cenedlaethol, ayb.''",
'revdelete-legend'            => 'Gosod cyfyngiadau ar y gallu i weld',
'revdelete-hide-text'         => 'Cuddio testun y diwygiad',
'revdelete-hide-image'        => 'Cuddio cynnwys y ffeil',
'revdelete-hide-name'         => "Cuddio'r weithred a'r targed",
'revdelete-hide-comment'      => 'Cuddio sylwad golygu',
'revdelete-hide-user'         => 'Cuddio enw defnyddiwr/IP y golygydd',
'revdelete-hide-restricted'   => 'Gosod y cyfyngiadau gweld data ar weinyddwyr yn ogystal ag eraill',
'revdelete-radio-same'        => '(peidier â newid)',
'revdelete-radio-set'         => 'Cuddier',
'revdelete-radio-unset'       => 'Na chuddier',
'revdelete-suppress'          => 'Atal data oddi wrth Weinyddwyr yn ogystal ag eraill',
'revdelete-unsuppress'        => "Tynnu'r cyfyngiadau ar y golygiadau a adferwyd",
'revdelete-log'               => 'Rheswm:',
'revdelete-submit'            => 'Rhoi ar waith ar y {{PLURAL:$1|golygiad|golygiad|golygiadau|golygiadau|golygiadau|golygiadau}} dewisedig',
'revdelete-logentry'          => 'wedi newid y gallu i weld golygiadau ar [[$1]]',
'logdelete-logentry'          => 'wedi newid y gallu i weld y digwyddiad ar [[$1]]',
'revdelete-success'           => "'''Diweddarwyd y gallu i weld golygiadau.'''",
'revdelete-failure'           => "'''Ni ellid newid y cyfyngiadau ar y gallu i weld y golygiad:'''
$1",
'logdelete-success'           => "'''Llwyddwyd i guddio neu i ddatguddio'r digwyddiad rhag y lòg.'''",
'logdelete-failure'           => "'''Ni ellid gosod cyfyngiadau ar y gallu i weld y cofnod lòg:'''
$1",
'revdel-restore'              => 'Newid gwelededd',
'revdel-restore-deleted'      => 'diwygiadau dilëedig',
'revdel-restore-visible'      => 'diwygiadau gweladwy',
'pagehist'                    => 'Hanes y dudalen',
'deletedhist'                 => 'Hanes dilëedig',
'revdelete-content'           => 'cynnwys',
'revdelete-summary'           => 'crynodeb golygu',
'revdelete-uname'             => 'yr enw defnyddiwr ar gyfer',
'revdelete-restricted'        => 'cyfyngwyd ar allu gweinyddwyr i weld',
'revdelete-unrestricted'      => 'tynnwyd y cyfyngiadau ar allu gweinyddwyr i weld',
'revdelete-hid'               => 'cuddiwyd $1',
'revdelete-unhid'             => 'datguddiwyd $1',
'revdelete-log-message'       => '$1 $2 {{PLURAL:$2|golygiad|golygiad|olygiad|golygiad|golygiad|golygiad|}}',
'logdelete-log-message'       => '$1 $2 {{PLURAL:$2||digywddiad|ddigwyddiad|digwyddiad|digwyddiad|digwyddiad}}',
'revdelete-hide-current'      => "Cafwyd gwall wrth geisio cuddio'r eitem a'r dyddiad $2, $1 arno: hwn yw'r diwygiad presennol.
Ni ellir ei guddio.",
'revdelete-show-no-access'    => 'Cafwyd gwall wrth geisio newid yr eitem gyda\'r dyddiad $2, $1: mae marc "cyfyngedig" arno.
Ni allwch ei weld.',
'revdelete-modify-no-access'  => 'Cafwyd gwall wrth geisio newid yr eitem gyda\'r dyddiad $2, $1: mae marc "cyfyngedig" arno.
Ni allwch fynd ato.',
'revdelete-modify-missing'    => "Cafwyd gwall wrth geisio newid yr eitem gyda'r cyfeirnod $1: mae'n eisiau o'r bas data!",
'revdelete-no-change'         => "'''Dalier sylw:''' roedd eisoes gan yr eitem dyddiedig $2, $1 y cyfyngiadau ar y gallu i weld y gwneuthpwyd cais amdanynt.",
'revdelete-concurrent-change' => "Cafwyd gwall wrth geisio newid yr eitem dyddiedig $2, $1: mae'n ymddangos bod ei statws wedi ei newid gan rywun arall wrth i chi geisio ei addasu eich hunan.
Edrychwch ar y logiau er mwyn cael rhagor o wybodaeth.",
'revdelete-only-restricted'   => "Cafwyd gwall wrth guddio'r eitem dyddiedig $2, $1: ni allwch guddio eitemau o olwg gweinyddwyr heb ar yr un pryd ddewis un o'r opsiynau eraill i gyfyngu ar y gallu i weld.",
'revdelete-reason-dropdown'   => '*Rhesymau cyffredin dros ddileu
** Torri hawlfraint
** Gwybodaeth bersonol anaddas',
'revdelete-otherreason'       => 'Rheswm arall:',
'revdelete-reasonotherlist'   => 'Rheswm arall',
'revdelete-edit-reasonlist'   => 'Golygu rhestr y rhesymau dros ddileu',
'revdelete-offender'          => 'Awdur y golygiad:',

# Suppression log
'suppressionlog'     => 'Lòg cuddio',
'suppressionlogtext' => "Dyma restr y dileuon a'r blociau lle y cuddiwyd cynnwys rhag y gweinyddwyr.
Gallwch weld rhestr y gwaharddiadau a'r blociau gweithredol ar y [[Special:IPBlockList|rhestr blociau IP]].",

# Revision move
'moverevlogentry'              => 'wedi symud {{PLURAL:$3||un diwygiad|$3 ddiwygiad|$3 diwygiad|$3 diwygiad|$3 diwygiad}} o $1 i $2',
'revisionmove'                 => 'Symud diwygiadau oddi wrth "$1"',
'revmove-explain'              => "Caiff y diwygiadau hyn eu symud o $1 i dudalen y cyrchfan a benwyd. Os nad yw'r cyrchfan yn bodoli, fe gaiff ei greu. Fel arall, caiff y diwygiadau eu cyfuno gyda hanes y dudalen.",
'revmove-legend'               => 'Gosod crynodeb a thudalen y cyrchfan',
'revmove-submit'               => "Symud y diwygiadau i'r dudalen dewisedig",
'revisionmoveselectedversions' => 'Symud y diwygiadau dewisedig',
'revmove-reasonfield'          => 'Rheswm:',
'revmove-titlefield'           => 'Tudalen y cyrchfan:',
'revmove-badparam-title'       => 'Paramedrau gwallus',
'revmove-badparam'             => 'Mae eich cais yn cynnwys paramedrau annigonol neu anghyfreithlon. Pwyswch y botwm "Nôl" a rhowch gynnig arall arni.',
'revmove-norevisions-title'    => 'Penwyd diwygiad annilys',
'revmove-norevisions'          => "Nid ydych wedi nodi un neu ragor o ddiwygiadau i'w symud, ynteu nid yw'r diwygiad a nodwyd ar gael.",
'revmove-nullmove-title'       => 'Teitl gwallus',
'revmove-nullmove'             => 'Mae\'r un enw ar dudalennau\'r ffynhonnell a\'r cyrchfan. Pwyswch y botwm "Nôl" a phennwch enw tudalen heblaw "$1".',
'revmove-success-existing'     => "{{PLURAL:$1|Cafodd un diwygiad o [[$2]] ei|$1 diwygiad o [[$2]] eu}} symud i'r dudalen [[$3]].",
'revmove-success-created'      => "{{PLURAL:$1|Cafodd un diwygiad o [[$2]] ei|$1 diwygiad o [[$2]] eu}} symud i'r dudalen sydd newydd gael ei chreu [[$3]].",

# History merging
'mergehistory'                     => 'Cyfuno hanesion y tudalennau',
'mergehistory-header'              => 'Pwrpas y dudalen hon yw cyfuno diwygiadau o hanes un dudalen gwreiddiol ar dudalen newydd.
Pan yn gwneud hyn dylid sicrhau nad yw dilyniant hanes tudalennau yn cael ei ddifetha.',
'mergehistory-box'                 => "Cyfuno'r diwygiadau o ddwy dudalen:",
'mergehistory-from'                => 'Y dudalen wreiddiol:',
'mergehistory-into'                => 'Y dudalen cyrchfan:',
'mergehistory-list'                => 'Hanes diwygiadau y gellir eu cyfuno',
'mergehistory-merge'               => "Gellir cyfuno'r diwygiadau canlynol o [[:$1]] i'r dudalen [[:$2]]. Defnyddiwch y botymau radio i gyfuno dim ond y diwygiadau a grewyd hyd at yr amser penodedig. Sylwch y bydd y golofn botwm radio yn cael ei hail-osod pan ddefnyddir y cysylltau llywio.",
'mergehistory-go'                  => 'Dangos y golygiadau y gellir eu cyfuno',
'mergehistory-submit'              => 'Cyfuner y diwygiadau',
'mergehistory-empty'               => 'Ni ellir cyfuno unrhyw ddiwygiadau.',
'mergehistory-success'             => "Cyfunwyd $3 {{PLURAL:$3|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}} o [[:$1]] yn llwyddiannus i'r dudalen [[:$2]].",
'mergehistory-fail'                => "Methodd y cyfuno hanes; a wnewch wirio paramedrau'r dudalen a'r amser unwaith eto.",
'mergehistory-no-source'           => "Nid yw'r dudalen gwreiddiol $1 yn bod.",
'mergehistory-no-destination'      => "Nid yw'r dudalen cyrchfan $1 yn bod.",
'mergehistory-invalid-source'      => 'Rhaid bod teitl dilys gan y dudalen gwreiddiol.',
'mergehistory-invalid-destination' => 'Rhaid bod teitl dilys gan y dudalen cyrchfan.',
'mergehistory-autocomment'         => 'Cyfunwyd [[:$1]] tu mewn i [[:$2]]',
'mergehistory-comment'             => 'Cyfunwyd [[:$1]] tu mewn i [[:$2]]: $3',
'mergehistory-same-destination'    => "Ni all y tudalen gwreiddiol a'r cyrchfan fod yr un enw",
'mergehistory-reason'              => 'Rheswm:',

# Merge log
'mergelog'           => 'Lòg cyfuno',
'pagemerge-logentry' => 'llyncwyd [[$1]] gan [[$2]] (golygiadau hyd at $3)',
'revertmerge'        => 'Daduno',
'mergelogpagetext'   => "Fe ddilyn rhestr o'r achosion diweddaraf o hanes tudalen yn cael ei gyfuno a hanes tudalen arall.",

# Diffs
'history-title'            => "Hanes golygu '$1'",
'difference'               => '(Gwahaniaethau rhwng diwygiadau)',
'difference-multipage'     => '(Y gwahaniaeth rhwng y tudalennau)',
'lineno'                   => 'Llinell $1:',
'compareselectedversions'  => 'Cymharer y fersiynau dewisedig',
'showhideselectedversions' => 'Dangos/cuddio y diwygiadau dewisedig',
'editundo'                 => 'dadwneud',
'diff-multi'               => '(Ni ddangosir {{PLURAL:$1|yr $1 diwygiad|yr $1 diwygiad|y $1 ddiwygiad|y $1 diwygiad|y $1 diwygiad|y $1 diwygiad}} rhyngol gan {{PLURAL:$2||un defnyddiwr|$2 ddefnyddiwr|$2 defnyddiwr|$2 o ddefnyddwyr|$2 o ddefnyddwyr}}.)',
'diff-multi-manyusers'     => '(Ni ddangosir {{PLURAL:$1|yr $1 diwygiad|yr $1 diwygiad|y $1 ddiwygiad|y $1 diwygiad|y $1 diwygiad|y $1 diwygiad}} rhyngol gan mwy na $2 {{PLURAL:$2|o ddefnyddwyr}}.)',

# Search results
'searchresults'                    => "Canlyniadau'r chwiliad",
'searchresults-title'              => 'Canlyniadau chwilio am "$1"',
'searchresulttext'                 => 'Am fwy o wybodaeth am chwilio {{SITENAME}}, gwelwch [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Chwiliwyd am \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|pob tudalen yn dechrau gyda "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|pob tudalen sy\'n cysylltu â "$1"]])',
'searchsubtitleinvalid'            => "Chwiliwyd am '''$1'''",
'toomanymatches'                   => "Cafwyd hyd i ormod o enghreifftiau o'r term chwilio; ceisiwch chwilio am derm arall",
'titlematches'                     => 'Teitlau erthygl yn cyfateb',
'notitlematches'                   => 'Does dim teitl yn cyfateb',
'textmatches'                      => 'Testun erthygl yn cyfateb',
'notextmatches'                    => 'Does dim testun yn cyfateb',
'prevn'                            => '{{PLURAL:$1||yr $1 cynt|y $1 gynt|y $1 chynt|y $1 chynt|y $1 cynt}}',
'nextn'                            => 'y {{PLURAL:$1|$1}} nesaf',
'prevn-title'                      => 'Y $1 {{PLURAL:$1|canlyiad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} cynt',
'nextn-title'                      => 'Y $1 {{PLURAL:$1|canlyiad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} nesaf',
'shown-title'                      => 'Dangos $1 {{PLURAL:$1|canlyiad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} y dudalen',
'viewprevnext'                     => 'Dangos ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Dewisiadau chwilio',
'searchmenu-exists'                => "'''Mae tudalen o'r enw \"[[\$1]]\" ar y wici hwn'''",
'searchmenu-new'                   => "'''Creu'r dudalen \"[[:\$1]]\" ar y wici hwn!'''",
'searchhelp-url'                   => 'Help:Cymorth',
'searchmenu-prefix'                => "[[Special:PrefixIndex/$1|Chwilio drwy tudalennau gyda'r rhagddodiad hwn]]",
'searchprofile-articles'           => 'Tudalennau pwnc (erthyglau/ffeiliau)',
'searchprofile-project'            => 'Tudalennau Cymorth a Phrosiect',
'searchprofile-images'             => 'Ffeiliau amlgyfrwng',
'searchprofile-everything'         => 'Popeth',
'searchprofile-advanced'           => 'Uwch',
'searchprofile-articles-tooltip'   => 'Chwilio drwy $1',
'searchprofile-project-tooltip'    => 'Chwilio drwy $1',
'searchprofile-images-tooltip'     => 'Chwilio am ffeiliau',
'searchprofile-everything-tooltip' => "Chwilio'r cynnwys gyfan (gan gynnwys tudalennau sgwrs)",
'searchprofile-advanced-tooltip'   => 'Chwilio drwy parthau dewisol',
'search-result-size'               => '$1 ({{PLURAL:$2|dim geiriau|$2 gair|$2 air|$2 gair|$2 gair|$2 gair|}})',
'search-result-category-size'      => '{{PLURAL:$1|$1 aelod}} ({{PLURAL:$2|$2 is-gategori}}, {{PLURAL:$3|$3 ffeil}})',
'search-result-score'              => 'Perthnasedd: $1%',
'search-redirect'                  => '(ailgyfeiriad $1)',
'search-section'                   => '(adran $1)',
'search-suggest'                   => 'Ai am hyn y chwiliwch: $1',
'search-interwiki-caption'         => 'Chwaer-brosiectau',
'search-interwiki-default'         => 'Y canlyniadau o $1:',
'search-interwiki-more'            => '(rhagor)',
'search-mwsuggest-enabled'         => 'gydag awgrymiadau',
'search-mwsuggest-disabled'        => 'dim awgrymiadau',
'search-relatedarticle'            => 'Erthyglau eraill tebyg',
'mwsuggest-disable'                => 'Analluogi awgrymiadau AJAX',
'searcheverything-enable'          => 'Chwilio pob parth',
'searchrelated'                    => 'erthyglau eraill tebyg',
'searchall'                        => 'oll',
'showingresults'                   => "Yn dangos $1 {{PLURAL:$1|canlyniad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} isod gan ddechrau gyda rhif '''$2'''.",
'showingresultsnum'                => "Yn dangos $3 {{PLURAL:$3|canlyniad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} isod gan ddechrau gyda rhif '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5||Canlyniad '''$1''' o blith '''$3'''|Canlyniadau '''$1 - $2''' o blith '''$3'''|Canlyniadau '''$1 - $2''' o blith '''$3'''|Canlyniadau '''$1 - $2''' o blith '''$3'''|Canlyniadau '''$1 - $2''' o blith '''$3'''}} ar gyfer '''$4'''",
'nonefound'                        => "'''Sylwer''': Dim ond rhai parthau sy'n cael eu chwilio'n ddiofyn. Os ydych am chwilio'r holl barthau (gan gynnwys tudalennau sgwrs, nodiadau, ayb) teipiwch ''all:'' o flaen yr enw. Os am chwilio parth arbennig teipiwch ''enw'r parth:'' o flaen yr enw.",
'search-nonefound'                 => "Ni chafwyd dim canlyniadau i'r ymholiad.",
'powersearch'                      => 'Chwilio',
'powersearch-legend'               => 'Chwiliad uwch',
'powersearch-ns'                   => 'Chwilio yn y parthau:',
'powersearch-redir'                => 'Rhestru ailgyfeiriadau',
'powersearch-field'                => 'Chwilier am',
'powersearch-togglelabel'          => 'Dewis:',
'powersearch-toggleall'            => 'Oll',
'powersearch-togglenone'           => 'Dim un',
'search-external'                  => 'Chwiliad allanol',
'searchdisabled'                   => "Mae'r teclyn chwilio ar {{SITENAME}} wedi'i analluogi dros dro.
Yn y cyfamser gallwch chwilio drwy Google.
Cofiwch y gall mynegeion Google o gynnwys {{SITENAME}} fod ar ei hôl hi.",

# Quickbar
'qbsettings'               => 'Panel llywio',
'qbsettings-none'          => 'Dim',
'qbsettings-fixedleft'     => 'Sefydlog ar y chwith',
'qbsettings-fixedright'    => 'Sefydlog ar y dde',
'qbsettings-floatingleft'  => 'Yn arnofio ar y chwith',
'qbsettings-floatingright' => 'Yn arnofio ar y dde',

# Preferences page
'preferences'                   => 'Dewisiadau',
'mypreferences'                 => 'Fy newisiadau',
'prefs-edits'                   => 'Nifer y golygiadau:',
'prefsnologin'                  => 'Nid ydych wedi mewngofnodi',
'prefsnologintext'              => 'Rhaid i chi <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} fewngofnodi]</span> er mwyn gosod eich dewisiadau defnyddiwr.',
'changepassword'                => 'Newid y cyfrinair',
'prefs-skin'                    => 'Gwedd',
'skin-preview'                  => 'Rhagolwg',
'prefs-math'                    => 'Mathemateg',
'datedefault'                   => 'Dim dewisiad',
'prefs-datetime'                => 'Dyddiad ac amser',
'prefs-personal'                => 'Data defnyddiwr',
'prefs-rc'                      => 'Newidiadau diweddar',
'prefs-watchlist'               => 'Rhestr wylio',
'prefs-watchlist-days'          => "Nifer y diwrnodau i'w dangos yn y rhestr wylio:",
'prefs-watchlist-days-max'      => '(hyd at 7 diwrnod)',
'prefs-watchlist-edits'         => "Nifer y golygiadau i'w dangos wrth ehangu'r rhestr wylio:",
'prefs-watchlist-edits-max'     => '(hyd at uchafswm o 1000)',
'prefs-watchlist-token'         => 'Tocyn y rhestr wylio:',
'prefs-misc'                    => 'Amrywiol',
'prefs-resetpass'               => 'Newid y cyfrinair',
'prefs-email'                   => 'E-bostio',
'prefs-rendering'               => 'Ymddangosiad',
'saveprefs'                     => "Cadw'r dewisiadau",
'resetprefs'                    => "Clirio'r darpar newidiadau",
'restoreprefs'                  => 'Adfer yr holl osodiadau diofyn',
'prefs-editing'                 => 'Golygu',
'prefs-edit-boxsize'            => 'Maint y blwch testun.',
'rows'                          => 'Rhesi:',
'columns'                       => 'Colofnau:',
'searchresultshead'             => 'Chwilio',
'resultsperpage'                => 'Cyfradd taro fesul tudalen:',
'contextlines'                  => "Nifer y llinellau i'w dangos ar gyfer pob hit:",
'contextchars'                  => 'Nifer y llythrennau a nodau eraill i bob llinell:',
'stub-threshold'                => 'Trothwy ar gyfer fformatio <a href="#" class="stub">cyswllt eginyn</a> (beitiau):',
'stub-threshold-disabled'       => 'Analluogwyd',
'recentchangesdays'             => "Nifer y diwrnodau i'w dangos yn 'newidiadau diweddar':",
'recentchangesdays-max'         => '(hyd at $1 {{PLURAL:$1||diwrnod|ddiwrnod|diwrnod|diwrnod|diwrnod}})',
'recentchangescount'            => "Nifer y golygiadau i'w dangos yn ddiofyn:",
'prefs-help-recentchangescount' => 'Mae hwn yn cynnwys newidiadau diweddar, hanesion tudalennau, a logiau.',
'prefs-help-watchlist-token'    => "Gallwch gynhyrchu porthiant RSS ar gyfer eich rhestr wylio drwy osod allwedd gudd yn y blwch hwn.
Gall unrhywun sy'n gwybod yr allwedd ddarllen eich rhestr wylio, felly gofalwch ddewis allwedd ddiogel.
Dyma allwedd wedi ei chreu ar hap y gallwch ei defnyddio: $1",
'savedprefs'                    => 'Mae eich dewisiadau wedi cael eu cadw.',
'timezonelegend'                => 'Ardal amser:',
'localtime'                     => 'Amser lleol:',
'timezoneuseserverdefault'      => 'Amser y gweinydd',
'timezoneuseoffset'             => 'Arall (nodwch yr atred)',
'timezoneoffset'                => 'Atred¹:',
'servertime'                    => 'Amser y gweinydd:',
'guesstimezone'                 => 'Llenwi oddi wrth y porwr',
'timezoneregion-africa'         => 'Affrica',
'timezoneregion-america'        => 'America',
'timezoneregion-antarctica'     => 'Yr Antarctig',
'timezoneregion-arctic'         => 'Yr Arctig',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Môr Iwerydd',
'timezoneregion-australia'      => 'Awstralia',
'timezoneregion-europe'         => 'Ewrop',
'timezoneregion-indian'         => 'Cefnfor yr India',
'timezoneregion-pacific'        => 'Y Môr Tawel',
'allowemail'                    => 'Galluogi e-bost oddi wrth ddefnyddwyr eraill',
'prefs-searchoptions'           => 'Chwilio',
'prefs-namespaces'              => 'Parthau',
'defaultns'                     => "Neu chwilio'r parthau isod:",
'default'                       => 'rhagosodyn',
'prefs-files'                   => 'Ffeiliau',
'prefs-custom-css'              => 'CSS o hunan-ddewis',
'prefs-custom-js'               => 'JS o hunan-ddewis',
'prefs-common-css-js'           => 'CSS/JS ar y cyd ar gyfer pob gwedd:',
'prefs-reset-intro'             => "Gallwch ddefnyddio'r dudalen hon i ailosod eich dewisiadau i'r rhai diofyn.
Ni allwch ddadwneud y weithred hon.",
'prefs-emailconfirm-label'      => "Cadarnhau'r e-bost:",
'prefs-textboxsize'             => 'Maint y ffenestr olygu',
'youremail'                     => 'Eich cyfeiriad e-bost',
'username'                      => 'Enw defnyddiwr:',
'uid'                           => 'ID Defnyddiwr:',
'prefs-memberingroups'          => "Yn aelod o'r {{PLURAL:$1|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}} canlynol:",
'prefs-registration'            => "Amser dechrau'r cyfrif:",
'yourrealname'                  => 'Eich enw cywir*',
'yourlanguage'                  => 'Iaith y rhyngwyneb',
'yourvariant'                   => 'Amrywiad',
'yournick'                      => 'Eich llysenw (fel llofnod):',
'prefs-help-signature'          => 'Dylid arwyddo sylwadau ar dudalennau sgwrs gyda "<nowiki>~~~~</nowiki>". Fe ymddengys hwn fel eich enw ac amser y sylw.',
'badsig'                        => 'Llofnod crai annilys; gwiriwch y tagiau HTML.',
'badsiglength'                  => "Mae'ch llysenw'n rhy hir.
Gall fod hyd at $1 {{PLURAL:$1|llythyren|lythyren|lythyren|llythyren|llythyren|llythyren}} o hyd.",
'yourgender'                    => 'Rhyw:',
'gender-unknown'                => 'Heb ei nodi',
'gender-male'                   => 'Gwryw',
'gender-female'                 => 'Benyw',
'prefs-help-gender'             => "Heb rheidrwydd: mae'r meddalwedd yn defnyddio hwn i gyfeirio atoch ac i'ch cyfarch yn ôl eich rhyw.
Mae'r wybodaeth hon ar gael i'r cyhoedd.",
'email'                         => 'E-bost',
'prefs-help-realname'           => '* Enw iawn (dewisol): Os ydych yn dewis ei roi, fe fydd yn cael ei ddefnyddio er mwyn rhoi cydnabyddiaeth i chi am eich gwaith.',
'prefs-help-email'              => 'Os ydych yn dewis gosod eich cyfeiriad e-bost yna gallwn anfon cyfrinair newydd atoch os aiff yr un gwreiddiol yn angof gennych.',
'prefs-help-email-others'       => "Gallwch hefyd adael i eraill anfon e-bost atoch trwy'r cyswllt ar eich tudalen defnyddiwr neu eich tudalen sgwrs, heb ddatguddio'ch manylion personol.",
'prefs-help-email-required'     => 'Cyfeiriad e-bost yn angenrheidiol.',
'prefs-info'                    => 'Gwybodaeth sylfaenol',
'prefs-i18n'                    => 'Iaith',
'prefs-signature'               => 'Llofnod',
'prefs-dateformat'              => 'Fformat dyddiad',
'prefs-timeoffset'              => 'Atred amser',
'prefs-advancedediting'         => 'Dewisiadau uwch',
'prefs-advancedrc'              => 'Dewisiadau uwch',
'prefs-advancedrendering'       => 'Dewisiadau uwch',
'prefs-advancedsearchoptions'   => 'Dewisiadau uwch',
'prefs-advancedwatchlist'       => 'Dewisiadau uwch',
'prefs-displayrc'               => 'Dewisiadau arddangos',
'prefs-displaysearchoptions'    => 'Dewisiadau arddangos',
'prefs-displaywatchlist'        => 'Dewisiadau arddangos',
'prefs-diffs'                   => "Cymharu golygiadau ('gwahan')",

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Y cyfeiriad e-bost yn ymddangos yn un dilys',
'email-address-validity-invalid' => 'Rhowch gyfeiriad e-bost dilys',

# User rights
'userrights'                   => 'Rheoli galluoedd defnyddwyr',
'userrights-lookup-user'       => 'Rheoli grwpiau defnyddiwr',
'userrights-user-editname'     => 'Rhowch enw defnyddiwr:',
'editusergroup'                => 'Golygu Grwpiau Defnyddwyr',
'editinguser'                  => "Newid galluoedd y defnyddiwr '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Golygu grwpiau defnyddwyr',
'saveusergroups'               => "Cadw'r Grwpiau Defnyddwyr",
'userrights-groupsmember'      => 'Yn aelod o:',
'userrights-groupsmember-auto' => 'Ac ynghlwm wrth aelodaeth y grwpiau uchod, yn aelod o:',
'userrights-groups-help'       => 'Gallwch newid y grwpiau y perthyn y defnyddiwr hwn iddynt:
* Mae defnyddiwr yn perthyn i grŵp pan mae tic yn y bocs.
* Nid yw defnyddiwr yn perthyn i grŵp pan nad oes tic yn y bocs.
* Mae * yn golygu na fyddwch yn gallu dad-wneud unrhyw newid yn y grŵp hwnnw.',
'userrights-reason'            => 'Rheswm:',
'userrights-no-interwiki'      => "Nid yw'r gallu ganddoch i newid galluoedd defnyddwyr ar wicïau eraill.",
'userrights-nodatabase'        => "Nid yw'r bas data $1 yn bod neu nid yw'n un lleol.",
'userrights-nologin'           => 'Rhaid i chi [[Special:UserLogin|fewngofnodi]] ar gyfrif gweinyddwr er mwyn pennu galluoedd defnyddwyr.',
'userrights-notallowed'        => "Nid yw'r gallu i bennu galluoedd defnyddwyr ynghlwm wrth eich cyfrif defnyddiwr.",
'userrights-changeable-col'    => 'Grwpiau y gallwch eu newid',
'userrights-unchangeable-col'  => 'Grwpiau na allwch eu newid',

# Groups
'group'               => 'Grŵp:',
'group-user'          => 'Defnyddwyr',
'group-autoconfirmed' => "Defnyddwyr wedi eu cadarnhau'n awtomatig",
'group-bot'           => 'Botiau',
'group-sysop'         => 'Gweinyddwyr',
'group-bureaucrat'    => 'Biwrocratiaid',
'group-suppress'      => 'Goruchwylwyr',
'group-all'           => '(pawb)',

'group-user-member'          => 'Defnyddiwr',
'group-autoconfirmed-member' => "Defnyddiwr wedi ei gadarnhau'n awtomatig",
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Gweinyddwr',
'group-bureaucrat-member'    => 'Biwrocrat',
'group-suppress-member'      => 'Goruchwyliwr',

'grouppage-user'          => '{{ns:project}}:Defnyddwyr',
'grouppage-autoconfirmed' => "{{ns:project}}:Defnyddwyr wedi eu cadarnhau'n awtomatig",
'grouppage-bot'           => '{{ns:project}}:Botiau',
'grouppage-sysop'         => '{{ns:project}}:Gweinyddwyr',
'grouppage-bureaucrat'    => '{{ns:project}}:Biwrocratiaid',
'grouppage-suppress'      => '{{ns:project}}:Goruchwylio',

# Rights
'right-read'                  => 'Darllen tudalennau',
'right-edit'                  => 'Golygu tudalennau',
'right-createpage'            => 'Creu tudalennau (nad ydynt yn dudalennau sgwrs)',
'right-createtalk'            => 'Creu tudalennau sgwrs',
'right-createaccount'         => 'Creu cyfrifon defnyddwyr newydd',
'right-minoredit'             => "Marcio golygiadau'n rhai bychain",
'right-move'                  => 'Symud tudalennau',
'right-move-subpages'         => "Symud tudalennau gyda'u his-dudalennau",
'right-move-rootuserpages'    => 'Symud prif dudalennau defnyddwyr',
'right-movefile'              => 'Symud ffeiliau',
'right-suppressredirect'      => "Peidio â chreu ailgyfeiriad o'r hen enw wrth symud tudalen",
'right-upload'                => 'Uwchlwytho ffeiliau',
'right-reupload'              => 'Trosysgrifo ffeil sydd eisoes yn bod',
'right-reupload-own'          => "Trosysgrifo ffeil sydd eisoes yn bod ac wedi ei uwchlwytho gennych chi'ch hunan",
'right-reupload-shared'       => "Uwchlwytho ffeil ar wici lleol, gyda'r un teitl â ffeil ar y storfa cyfrannol",
'right-upload_by_url'         => 'Uwchlwytho ffeil oddi ar gyfeiriad URL',
'right-purge'                 => 'Carthu celc y safle o ryw dudalen heb gadarnhau',
'right-autoconfirmed'         => 'Golygu tudalennau sydd wedi eu lled-ddiogelu',
'right-bot'                   => 'Cael ei drin fel proses awtomataidd',
'right-nominornewtalk'        => "Gallu dewis peidio â derbyn hysbysiad bod gennych neges newydd pan ddigwydd mân newidiadau i'ch tudalen sgwrs",
'right-apihighlimits'         => 'Defnyddio terfynau uwch mewn ymholiadau API',
'right-writeapi'              => "Defnyddio'r API i ysgrifennu a thrin y tudalennau",
'right-delete'                => 'Dileu tudalennau',
'right-bigdelete'             => 'Dileu tudalennau a hanes llwythog iddynt',
'right-deleterevision'        => 'Dileu a dad-ddileu golygiadau arbennig o dudalennau',
'right-deletedhistory'        => 'Gweld cofnodion fersiynau sydd wedi eu dileu, heb y testun ynddynt',
'right-deletedtext'           => 'Gweld ysgrifen sydd wedi ei ddileu a newidiadau rhwng fersiynau ar ôl eu dileu',
'right-browsearchive'         => 'Chwilio drwy tudalennau dilëedig',
'right-undelete'              => 'Adfer tudalen dilëedig',
'right-suppressrevision'      => 'Adolygu ac adfer diwygiadau sydd wedi eu cuddio rhag gweinyddwyr',
'right-suppressionlog'        => 'Gweld logiau preifat',
'right-block'                 => 'Atal defnyddwyr eraill rhag golygu',
'right-blockemail'            => 'Atal defnyddiwr rhag anfon e-bost',
'right-hideuser'              => "Atal enw defnyddiwr rhag i'r cyhoedd ei weld",
'right-ipblock-exempt'        => 'Mynd heibio i flociau IP, blociau awtomatig a blociau amrediad',
'right-proxyunbannable'       => 'Mynd heibio i flociau awtomatig gan weinyddion dirprwyol',
'right-unblockself'           => 'Dad-flocio eu hunain',
'right-protect'               => 'Newid lefelau diogelu a golygu tudalennau wedi eu diogelu',
'right-editprotected'         => 'Golygu tudalennau sydd wedi eu diogelu (ond bod hebddynt ddiogelu sgydol)',
'right-editinterface'         => "Golygu'r rhyngwyneb",
'right-editusercssjs'         => 'Golygu ffeiliau CSS a JS yn perthyn i ddefnyddwyr eraill',
'right-editusercss'           => 'Golygu ffeiliau CSS yn perthyn i ddefnyddwyr eraill',
'right-edituserjs'            => 'Golygu ffeiliau JS yn perthyn i ddefnyddwyr eraill',
'right-rollback'              => 'Gwrthdroi golygiadau defnyddiwr diwethaf rhyw dudalen yn sydyn',
'right-markbotedits'          => 'Marcio golygiadau wedi eu gwrthdroi yn olygiadau bot',
'right-noratelimit'           => 'Bod heb gyfyngiad ar gyflymder eich gweithredoedd',
'right-import'                => 'Mewnforio tudalennau o wicïau eraill',
'right-importupload'          => 'Mewnforio tudalennau drwy uwchlwytho ffeil XML',
'right-patrol'                => 'Gallu marcio golygiadau pobl eraill yn rhai sydd wedi derbyn ymweliad patrôl',
'right-autopatrol'            => 'Gallu derbyn marc ymweliad patrôl yn awtomatig ar eich golygiadau eich hunan',
'right-patrolmarks'           => 'Gweld marciau patrôl ar newidiadau diweddar',
'right-unwatchedpages'        => 'Gweld rhestr y tudalennau heb neb yn eu gwylio',
'right-trackback'             => "Gallu cael y wici i dderbyn 'trackback'",
'right-mergehistory'          => 'Cyfuno hanes y tudalennau',
'right-userrights'            => 'Golygu holl alluoedd defnyddwyr',
'right-userrights-interwiki'  => "Newid galluoedd defnyddwyr sy'n perthyn i ddefnyddwyr ar wicïau eraill",
'right-siteadmin'             => "Cloi a datgloi'r databas",
'right-reset-passwords'       => 'Ailosod cyfrinair defnyddwyr eraill',
'right-override-export-depth' => 'Allforio tudalennau gan gynnwys tudalennau cysylltiedig hyd at ddyfnder o 5',
'right-sendemail'             => 'Anfon e-bost at ddefnyddwyr eraill',
'right-revisionmove'          => 'Symud diwygiadau',
'right-disableaccount'        => 'Analluogi cyfrifon',

# User rights log
'rightslog'      => 'Lòg galluoedd defnyddiwr',
'rightslogtext'  => 'Lòg y newidiadau i alluoedd defnyddwyr yw hwn.',
'rightslogentry' => "wedi gosod $1 yn aelod o'r grŵp $3 (grŵp cynt $2)",
'rightsnone'     => '(dim)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'darllen y dudalen',
'action-edit'                 => "golygu'r dudalen",
'action-createpage'           => 'creu tudalennau',
'action-createtalk'           => 'creu tudalennau sgwrs',
'action-createaccount'        => "creu'r cyfrif defnyddiwr hwn",
'action-minoredit'            => "marcio'r golygiad yn un bach",
'action-move'                 => 'symud y dudalen',
'action-move-subpages'        => "symud y dudalen a'i is-dudalennau",
'action-move-rootuserpages'   => 'symud prif dudalennau defnyddwyr',
'action-movefile'             => 'symud y ffeil hon',
'action-upload'               => "uwchlwytho'r ffeil",
'action-reupload'             => 'trosysgrifo ffeil sydd eisoes ar gael',
'action-reupload-shared'      => "Uwchlwytho ffeil ar wici lleol, gyda'r un teitl â ffeil yn y storfa gyfrannol",
'action-upload_by_url'        => "uuchlwytho'r ffeil o gyfeiriad URL",
'action-writeapi'             => "defnyddio'r API i ysgrifennu a thrin y tudalennau",
'action-delete'               => "dileu'r dudalen",
'action-deleterevision'       => "dileu'r golygiad",
'action-deletedhistory'       => 'gweld hanes dilëedig y dudalen hon',
'action-browsearchive'        => 'chwilio drwy tudalennau dilëedig',
'action-undelete'             => "dad-ddileu'r dudalen",
'action-suppressrevision'     => 'gweld ac adfer y golygiad cudd hwn',
'action-suppressionlog'       => 'gweld y lòg preifat hwn',
'action-block'                => 'atal y defnyddiwr hwn rhag golygu',
'action-protect'              => 'newid lefelau gwarchod y dudalen hon',
'action-import'               => "mewnforio'r dudalen hon o wici arall",
'action-importupload'         => "mewnforio'r dudalen hon drwy uwchlwytho ffeil XML",
'action-patrol'               => 'marcio bod golygiad defnyddiwr arall wedi derbyn ymweliad patrôl',
'action-autopatrol'           => 'cael derbyn marc ymweliad patrôl ar eich golygiad',
'action-unwatchedpages'       => 'gweld rhestr y tudalennau heb neb yn eu gwylio',
'action-trackback'            => "cael y wici i dderbyn 'trackback'",
'action-mergehistory'         => 'cyfuno hanes y dudalen hon',
'action-userrights'           => 'golygu holl alluoedd y defnyddwyr',
'action-userrights-interwiki' => 'golygu galluoedd y defnyddwyr ar wicïau eraill',
'action-siteadmin'            => "cloi neu ddatgloi'r databas",
'action-revisionmove'         => 'symud diwygiadau',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|newid|newid|newid|newid|newid|o newidiadau}}',
'recentchanges'                     => 'Newidiadau diweddar',
'recentchanges-legend'              => "Dewisiadau'r newidiadau diweddar",
'recentchangestext'                 => "Dilynwch y newidiadau diweddaraf i'r wici ar y dudalen hon.",
'recentchanges-feed-description'    => "Dilynwch y newidiadau diweddaraf i'r wici gyda'r porthiant hwn.",
'recentchanges-label-newpage'       => 'Dechreuwyd tudalen newydd wrth olygu',
'recentchanges-label-minor'         => 'Mân olygiad',
'recentchanges-label-bot'           => 'Golygwyd gan fot',
'recentchanges-label-unpatrolled'   => "Nid yw'r golygiad hwn wedi derbyn ymweliad patrôl eto",
'rcnote'                            => "Isod mae'r '''$1''' newid diweddaraf yn ystod y {{PLURAL:$2|diwrnod|diwrnod|deuddydd|tridiau|'''$2''' diwrnod|'''$2''' diwrnod}} diwethaf, hyd at $5, $4.",
'rcnotefrom'                        => "Isod rhestrir pob newid ers '''$2''' (hyd at '''$1''' ohonynt).",
'rclistfrom'                        => 'Dangos newidiadau newydd gan ddechrau o $1',
'rcshowhideminor'                   => '$1 golygiadau bychain',
'rcshowhidebots'                    => '$1 botiau',
'rcshowhideliu'                     => '$1 defnyddwyr mewngofnodedig',
'rcshowhideanons'                   => '$1 defnyddwyr anhysbys',
'rcshowhidepatr'                    => '$1 golygiadau wedi derbyn ymweliad patrôl',
'rcshowhidemine'                    => '$1 fy ngolygiadau',
'rclinks'                           => 'Dangos y $1 newid diweddaraf yn ystod y(r) $2 diwrnod diwethaf<br />$3',
'diff'                              => 'gwahan',
'hist'                              => 'hanes',
'hide'                              => 'Cuddio',
'show'                              => 'Dangos',
'minoreditletter'                   => 'B',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|defnyddwyr|defnyddiwr|ddefnyddiwr|defnyddiwr|defnyddiwr|o ddefnyddwyr}} yn gwylio]',
'rc_categories'                     => 'Cyfyngu i gategorïau (gwahanwch gyda "|")',
'rc_categories_any'                 => 'Unrhyw un',
'newsectionsummary'                 => '/* $1 */ adran newydd',
'rc-enhanced-expand'                => 'Dangos y manylion (angen JavaScript)',
'rc-enhanced-hide'                  => "Cuddio'r manylion",

# Recent changes linked
'recentchangeslinked'          => 'Newidiadau perthnasol',
'recentchangeslinked-feed'     => 'Newidiadau perthnasol',
'recentchangeslinked-toolbox'  => 'Newidiadau perthnasol',
'recentchangeslinked-title'    => 'Newidiadau cysylltiedig â "$1"',
'recentchangeslinked-noresult' => 'Ni chafwyd unrhyw newidiadau i dudalennau cysylltiedig yn ystod cyfnod yr ymholiad.',
'recentchangeslinked-summary'  => "Mae'r dudalen arbennig hon yn dangos y newidiadau diweddaraf i'r tudalennau hynny y mae cyswllt yn arwain atynt ar y dudalen a enwir (neu newidiadau i dudalennau sy'n aelodau o'r categori a enwir). Dangosir tudalennau sydd ar [[Special:Watchlist|eich rhestr wylio]] mewn print '''trwm'''.",
'recentchangeslinked-page'     => 'Tudalen:',
'recentchangeslinked-to'       => "Dangos newidiadau i'r tudalennau â chyswllt arnynt sy'n arwain at y dudalen a enwir",

# Upload
'upload'                      => 'Uwchlwytho ffeil',
'uploadbtn'                   => 'Uwchlwytho ffeil',
'reuploaddesc'                => "Dileu'r uwchlwytho a dychwelyd i'r ffurflen uwchlwytho",
'upload-tryagain'             => "Uwchlwyther disgrifiad newydd o'r ffeil",
'uploadnologin'               => 'Nid ydych wedi mewngofnodi',
'uploadnologintext'           => "Mae'n rhaid i chi [[Special:UserLogin|fewngofnodi]] er mwyn uwchlwytho ffeiliau.",
'upload_directory_missing'    => "Mae'r cyfeiriadur uwchlwytho ($1) yn eisiau, ac ni allai'r gweinydd gwe ei greu.",
'upload_directory_read_only'  => "Ni all y gweinydd ysgrifennu i'r cyfeiriadur uwchlwytho ($1).",
'uploaderror'                 => "Gwall tra'n uwchlwytho ffeil",
'upload-recreate-warning'     => "'''Rhybudd: Cafodd ffeil o'r enw hwn ei dileu neu ei symud.'''

Dyma'r lòg dileu a symud ar gyfer y dudalen hon, er gwybodaeth:",
'uploadtext'                  => "Defnyddiwch y ffurflen isod i uwchlwytho ffeiliau.
I weld a chwilio am ffeiliau sydd eisoes wedi eu huwchlwytho, ewch at y [[Special:FileList|rhestr o'r ffeiliau sydd wedi eu huwchlwytho]]. I weld cofnodion uwchlwytho a dileu ffeiliau, ewch at y [[Special:Log/upload|lòg uwchlwytho]] neu'r [[Special:Log/delete|lòg dileu]].

I osod ffeil mewn tudalen, defnyddiwch gyswllt wici ar un o'r ffurfiau canlynol:
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Ffeil.jpg]]</nowiki></tt>''', er mwyn defnyddio fersiwn llawn y ffeil
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Ffeil.png|200px|bawd|chwith|testun amgen]]</nowiki></tt>''' a wnaiff dangos llun 200 picsel o led mewn blwch ar yr ochr chwith, a'r testun 'testun amgen' wrth ei odre
*'''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Ffeil.ogg]]</nowiki></tt>''' a fydd yn arwain yn syth at y ffeil heb arddangos y ffeil.",
'upload-permitted'            => 'Mathau o ffeiliau a ganiateir: $1',
'upload-preferred'            => 'Mathau ffeil dewisol: $1.',
'upload-prohibited'           => 'Mathau o ffeiliau a waherddir: $1.',
'uploadlog'                   => 'lòg uwchlwytho',
'uploadlogpage'               => 'Lòg uwchlwytho',
'uploadlogpagetext'           => "Isod mae rhestr o'r uwchlwythiadau ffeiliau <nowiki>diweddaraf</nowiki>.
Gweler [[Special:NewFiles|oriel y ffeiliau newydd]] i fwrw golwg drostynt.",
'filename'                    => "Enw'r ffeil",
'filedesc'                    => 'Crynodeb',
'fileuploadsummary'           => 'Crynodeb:',
'filereuploadsummary'         => "Newidiadau i'r ffeil:",
'filestatus'                  => 'Statws hawlfraint:',
'filesource'                  => 'Ffynhonnell:',
'uploadedfiles'               => 'Ffeiliau a uwchlwythwyd',
'ignorewarning'               => "Anwybydder y rhybudd, a rhoi'r dudalen ar gadw beth bynnag",
'ignorewarnings'              => 'Anwybydder pob rhybudd',
'minlength1'                  => 'Rhaid i enwau ffeiliau gynnwys un llythyren neu ragor.',
'illegalfilename'             => 'Mae\'r enw ffeil "$1" yn cynnwys nodau sydd wedi\'u gwahardd mewn teitlau tudalennau. Ail-enwch y ffeil ac uwchlwythwch hi eto os gwelwch yn dda.',
'badfilename'                 => 'Newidiwyd enw\'r ffeil i "$1".',
'filetype-mime-mismatch'      => "Nid yw estyniad y ffeil yn cysefeillio â'r math MIME.",
'filetype-badmime'            => "Ni chaniateir uwchlwytho ffeiliau o'r math MIME '$1'.",
'filetype-bad-ie-mime'        => 'Ni ellir uwchlwytho\'r ffeil hon oherwydd y byddai Internet Explorer yn ei adnabod fel "$1", sef math annilys o ffeil sydd efallai hefyd yn beryglus.',
'filetype-unwanted-type'      => "Mae'r math '''\".\$1\"''' o ffeil yn anghymeradwy.  Mae'n well defnyddio ffeil {{PLURAL:\$3|o'r math|o'r math|o'r mathau|o'r mathau|o'r mathau|o'r mathau}} \$2.",
'filetype-banned-type'        => "Ni chaniateir ffeiliau o'r math '''\".\$1\"'''.  \$2 yw'r {{PLURAL:\$3|math|math|mathau|mathau|mathau|mathau}} o ffeil a ganiateir.",
'filetype-missing'            => "Nid oes gan y ffeil hon estyniad (megis '.jpg').",
'empty-file'                  => "Mae'r ffeil a gyflwynwyd gennych yn wag.",
'file-too-large'              => "Mae'r ffeil a gyflwynwyd gennych yn rhy fawr.",
'filename-tooshort'           => "Mae enw'r ffeil yn rhy fyr.",
'filetype-banned'             => "Mae'r math hwn o ffeil wedi ei wahardd.",
'verification-error'          => "Nid yw'r ffeil hon wedi ei derbyn wrth ei gwirio.",
'hookaborted'                 => 'Cafodd y darpar newid ei derfynu gan fachyn estyniad.',
'illegal-filename'            => "Nid yw'r enw ffeil hwn yn cael ei ganiatáu.",
'overwrite'                   => 'Ni chaniateir trosysgrifo ffeil sydd eisoes yn bod.',
'unknown-error'               => 'Cafwyd gwall anhysbys.',
'tmp-create-error'            => 'Wedi methu llunio ffeil dros dro.',
'tmp-write-error'             => "Cafwyd gwall wrth ysgrifennu'r ffeil dros dro.",
'large-file'                  => "Argymhellir na ddylai ffeil fod yn fwy na $1. Mae'r ffeil hwn yn $2 o faint.",
'largefileserver'             => "Mae'r ffeil yn fwy na'r hyn mae'r gweinydd yn ei ganiatau.",
'emptyfile'                   => "Ymddengys fod y ffeil a uwchlwythwyd yn wag. Efallai bod gwall teipio yn enw'r ffeil. Sicrhewch eich bod wir am uwchlwytho'r ffeil.",
'fileexists'                  => "Mae ffeil gyda'r enw hwn eisoes yn bodoli; gwiriwch '''<tt>[[:$1]]</tt>''' os nad ydych yn sicr bod angen ei newid.
[[$1|thumb]]",
'filepageexists'              => "Mae tudalen ddisgrifiad ar gyfer y ffeil hon eisoes ar gael ar '''<tt>[[:$1]]</tt>''', ond nid oes ffeil o'r enw hwn ar gael ar hyn o bryd.
Ni fydd crynodeb a osodir wrth uwchlwytho yn ymddangos ar y dudalen ddisgrifiad.
Er mwyn gwneud i'r crynodeb ymddangos yno, bydd raid i chi olygu'r dudalen ddisgrifiad yn unswydd.
[[$1|thumb]]",
'fileexists-extension'        => "Mae ffeil ag enw tebyg eisoes yn bod: [[$2|thumb]]
* Enw'r ffeil ar fin ei uwchlwytho: '''<tt>[[:$1]]</tt>'''
* Enw'r ffeil sydd eisoes yn bod: '''<tt>[[:$2]]</tt>'''
Dewiswch enw arall os gwelwch yn dda.",
'fileexists-thumbnail-yes'    => "Ymddengys bod delwedd wedi ei leihau ''(bawd)'' ar y ffeil. [[$1|thumb]]
Cymharwch gyda'r ffeil '''<tt>[[:$1]]</tt>'''.
Os mai'r un un llun ar ei lawn faint sydd ar yr ail ffeil yna does dim angen uwchlwytho llun ychwanegol o faint bawd.",
'file-thumbnail-no'           => "Mae '''<tt>$1</tt>''' ar ddechrau enw'r ffeil.
Mae'n ymddangos felly bod y ddelwedd wedi ei leihau ''(maint bawd)''.
Os yw'r ddelwedd ar ei lawn faint gallwch barhau i'w uwchlwytho. Os na, newidiwch enw'r ffeil, os gwelwch yn dda.",
'fileexists-forbidden'        => "Mae ffeil gyda'r enw hwn eisoes ar gael, ac ni ellir ei throsysgrifo.
Os ydych am uwchlwytho'ch ffeil, ewch nôl ac uwchlwythwch hi ac enw newydd arni.
[[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Mae ffeil gyda'r enw hwn eisoes yn bodoli yn y storfa ffeiliau cyfrannol.
Ewch nôl ac uwchlwythwch y ffeil gydag enw gwahanol iddo.
[[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => "Dyblgeb yw'r ffeil hwn o'r {{PLURAL:$1|ffeil|ffeil|ffeiliau|ffeiliau|ffeiliau|ffeiliau}} sy'n dilyn:",
'file-deleted-duplicate'      => "Mae ffeil union debyg i hon ([[$1]]) eisoes wedi cael ei dileu.
Dylech edrych ar hanes dileu'r ffeil honno cyn bwrw ati i'w llwytho unwaith eto.",
'uploadwarning'               => 'Rhybudd uwchlwytho',
'uploadwarning-text'          => 'Newidiwch ddisgrifiad y ffeil isod ac yna ceisiwch ei huwchlwytho eto, os gwelwch yn dda.',
'savefile'                    => "Cadw'r ffeil",
'uploadedimage'               => 'wedi llwytho "[[$1]]"',
'overwroteimage'              => 'wedi uwchlwytho fersiwn newydd o "[[$1]]"',
'uploaddisabled'              => "Ymddiheurwn; mae uwchlwytho wedi'i analluogi.",
'copyuploaddisabled'          => 'Analluogwyd uwchlwytho gan URL.',
'uploadfromurl-queued'        => "Mae'r ffeil mewn ciw, yn disgwyl cael ei huwchlwytho.",
'uploaddisabledtext'          => 'Analluogwyd uwchlwytho ffeiliau ar y wefan hon.',
'php-uploaddisabledtext'      => 'Anablwyd uwchlwytho ffeiliau yn PHP.
Gwiriwch y gosodiad ar file_uploads.',
'uploadscripted'              => "Mae'r ffeil hon yn cynnwys HTML neu sgript a all achosi problemau i borwyr gwe.",
'uploadvirus'                 => 'Mae firws gan y ffeil hon! Manylion: $1',
'upload-source'               => 'Y ffeil gwreiddiol',
'sourcefilename'              => "Enw'r ffeil wreiddiol:",
'sourceurl'                   => 'URL y gwreiddiol:',
'destfilename'                => 'Enw ffeil y cyrchfan:',
'upload-maxfilesize'          => 'Uchafswm maint y ffeil: $1',
'upload-description'          => 'Disgrifiad y ffeil',
'upload-options'              => 'Dewisiadau uwchlwytho',
'watchthisupload'             => 'Gwylier y ffeil hon',
'filewasdeleted'              => "Cafodd ffeil o'r enw hwn eisoes ei uwchlwytho ac yna ei dileu.
Dylech ddarllen y $1 cyn bwrw ati i'w uwchlwytho unwaith eto.",
'upload-wasdeleted'           => "'''Rhybudd: Rydych yn uwchlwytho ffeil sydd eisoes wedi ei dileu.'''

Ail-feddyliwch a ddylech barhau i uwchlwytho'r ffel hon.
Dyma'r lòg dileu ar gyfer y ffeil i chi gael gweld:",
'filename-bad-prefix'         => "Mae'r enw ar y ffeil yr ydych yn ei uwchlwytho yn dechrau gyda '''\"\$1\"'''. Mae'r math hwn o enw diystyr fel arfer yn cael ei osod yn awtomatig gan gamerâu digidol. Mae'n well gosod enw sy'n disgrifio'r ffeil arno.",
'upload-success-subj'         => 'Wedi llwyddo uwchlwytho',
'upload-success-msg'          => "Llwyddwyd i uwchlwytho'r ffeil o [$2]. Mae ar gael yma: [[:{{ns:file}}:$1]]",
'upload-failure-subj'         => 'Cafwyd problem wrth uwchlwytho',
'upload-failure-msg'          => 'Cafwyd problem wrth uwchlwytho o [$2]:

$1',
'upload-warning-subj'         => 'Rhybudd uwchlwytho',
'upload-warning-msg'          => 'Cafwyd problem wrth uwchlwytho o [$2]. Gallwch ddychwelyd at y [[Special:Upload/stash/$1|ffurflen uwchlwytho]] i ddatrys y broblem.',

'upload-proto-error'        => 'Protocol gwallus',
'upload-proto-error-text'   => "Rhaid cael URLs yn dechrau gyda <code>http://</code> neu <code>ftp://</code> wrth uwchlwytho'n bell.",
'upload-file-error'         => 'Gwall mewnol',
'upload-file-error-text'    => 'Cafwyd gwall mewnol wrth geisio creu ffeil dros dro ar y gweinydd.
Byddwch gystal â chysylltu â [[Special:ListUsers/sysop|gweinyddwr]].',
'upload-misc-error'         => 'Gwall uwchlwytho anhysbys',
'upload-misc-error-text'    => "Cafwyd gwall anghyfarwydd yn ystod yr uwchlwytho.
Sicrhewch bod yr URL yn ddilys ac yn hygyrch a cheisiwch eto.
Os yw'r broblem yn parhau, cysylltwch â [[Special:ListUsers/sysop|gweinyddwr]].",
'upload-too-many-redirects' => 'Roedd gormod o ailgyfeiriadau yn yr URL',
'upload-unknown-size'       => 'Maint anhysbys',
'upload-http-error'         => 'Digwyddodd gwall HTTP: $1',

# Special:UploadStash
'uploadstash'          => "Uwchlwytho i'r celc",
'uploadstash-summary'  => "O'r dudalen hon gallwch gyrchu'r ffeiliau sydd wedi cael eu huwchlwytho (neu wrthi'n cael eu huwchlwytho) ond nad ydynt wedi eu cyhoeddi ar y wici eto. Nid oes neb yn gallu gweld y ffeiliau heblaw am y defnyddiwr a'u huwchlwythodd.",
'uploadstash-clear'    => "Clirio'r celc ffeiliau",
'uploadstash-nofiles'  => 'Nid oes unrhyw ffeiliau mewn celc gennych.',
'uploadstash-badtoken' => 'Ni lwyddodd y weithred, efallai oherwydd bod eich cymwysterau golygu wedi dod i ben. Ceisiwch eto.',
'uploadstash-errclear' => "Ni lwyddwyd i glirio'r ffeiliau.",
'uploadstash-refresh'  => 'Adnewyddu rhestr y ffeiliau',

# img_auth script messages
'img-auth-accessdenied' => 'Ni chaniatawyd mynediad',
'img-auth-nopathinfo'   => "PATH_INFO yn eisiau.
Nid yw'ch gweinydd wedi ei osod i fedru pasio'r wybodaeth hon.
Efallai ei fod wedi ei seilio ar CGI, ac heb fod yn gallu cynnal img_auth.
Gweler http://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-notindir'     => "Nid yw'r llwybr y gwneuthpwyd cais amdano yn y cyfeiriadur uwchlwytho ffurfweddedig.",
'img-auth-badtitle'     => 'Ddim yn gallu gwneud teitl dilys o "$1".',
'img-auth-nologinnWL'   => 'Nid ydych wedi mewngofnodi ac nid yw "$1" ar y rhestr wen.',
'img-auth-nofile'       => 'Nid oes ffeil a\'r enw "$1" ar gael.',
'img-auth-isdir'        => 'Rydych yn ceisio cyrchu cyfeiriadur o\'r enw "$1".
Dim ond ffeiliau y cewch eu cyrchu.',
'img-auth-streaming'    => 'Wrthi\'n llifo "$1".',
'img-auth-public'       => "Gwaith img_auth.php yw allbynnu ffeiliau o wici preifat.
Mae'r wici hwn wedi ei osod yn wici gyhoeddus.
Er mwyn sicrhau'r diogelwch gorau posib, analluogwyd img_auth.php.",
'img-auth-noread'       => 'Nid yw\'r gallu gan y defnyddiwr hwn i gyrchu\'r ffeil "$1" i\'w ddarllen.',

# HTTP errors
'http-invalid-url'      => 'URL annilys: $1',
'http-invalid-scheme'   => 'Nid ydym yn gallu cynnal URL gyda\'r rhagddodiad "$1".',
'http-request-error'    => 'Methodd y cais HTTP oherwydd gwall anhysbys.',
'http-read-error'       => 'Cafwyd gwall wrth ddarllen yr HTTP.',
'http-timed-out'        => 'Goroedi wedi digwydd ar y cais HTTP.',
'http-curl-error'       => 'Cafwyd gwall wrth nôl yr URL: $1',
'http-host-unreachable' => 'Wedi methu cyrraedd yr URL.',
'http-bad-status'       => 'Cafwyd trafferth yn ystod y cais HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Wedi methu cyrraedd yr URL',
'upload-curl-error6-text'  => 'Ni chyrhaeddwyd yr URL a roddwyd.
Gwiriwch yr URL a sicrhau bod y wefan ar waith.',
'upload-curl-error28'      => 'Goroedi wrth uwchlwytho',
'upload-curl-error28-text' => 'Oedodd y wefan yn rhy hir cyn ymateb.
Sicrhewch bod y wefan ar waith, arhoswch ennyd, yna ceisiwch eto.
Efallai yr hoffech rhoi cynnig arni ar adeg llai prysur.',

'license'            => 'Trwyddedu:',
'license-header'     => 'Trwyddedu',
'nolicense'          => 'Heb ddewis dim un',
'license-nopreview'  => '(Dim rhagolwg ar gael)',
'upload_source_url'  => " (URL dilys, ar gael i'r cyhoedd)",
'upload_source_file' => ' (ffeil ar eich cyfrifiadur)',

# Special:ListFiles
'listfiles-summary'     => "Rhestr yr holl ffeiliau sydd wedi eu huwchlwytho sydd ar y dudalen hon.
Trefnir y rhestr yn ôl amser uwchlwytho, gyda'r diweddaraf ar flaen y rhestr.
Gallwch newid trefn y rhestr trwy bwyso ar bennawd colofn.",
'listfiles_search_for'  => "Chwilio am enw'r ddelwedd:",
'imgfile'               => 'ffeil',
'listfiles'             => "Rhestr o'r holl ffeiliau",
'listfiles_thumb'       => 'Mân-lun',
'listfiles_date'        => 'Dyddiad',
'listfiles_name'        => 'Enw',
'listfiles_user'        => 'Defnyddiwr',
'listfiles_size'        => 'Maint',
'listfiles_description' => 'Disgrifiad',
'listfiles_count'       => 'Fersiynau',

# File description page
'file-anchor-link'                  => 'Ffeil',
'filehist'                          => 'Hanes y ffeil',
'filehist-help'                     => 'Cliciwch ar ddyddiad/amser i weld y ffeil fel ag yr oedd bryd hynny.',
'filehist-deleteall'                => 'eu dileu i gyd',
'filehist-deleteone'                => 'dileu',
'filehist-revert'                   => 'gwrthdroi',
'filehist-current'                  => 'cyfredol',
'filehist-datetime'                 => 'Dyddiad/Amser',
'filehist-thumb'                    => 'Mân-lun',
'filehist-thumbtext'                => 'Mân-lun y fersiwn am $1',
'filehist-nothumb'                  => 'Dim mân-lun',
'filehist-user'                     => 'Defnyddiwr',
'filehist-dimensions'               => 'Hyd a lled',
'filehist-filesize'                 => 'Maint y ffeil',
'filehist-comment'                  => 'Sylw',
'filehist-missing'                  => 'Y ffeil yn eisiau',
'imagelinks'                        => "Cysylltiadau'r ffeil",
'linkstoimage'                      => "Mae'r {{PLURAL:$1|tudalen|dudalen|tudalennau|tudalennau|tudalennau|tudalennau}} isod yn cysylltu i'r ddelwedd hon:",
'linkstoimage-more'                 => "Mae rhagor na $1 {{PLURAL:$1|tudalen yn|dudalen yn|dudalen yn|o dudalennau'n|o dudalennau'n|o dudalennau'n}} cysylltu at y ffeil hon.
Mae'r rhestr canlynol yn dangos y {{PLURAL:$1|$1 cysylltiad cyntaf}} at y ffeil hon yn unig. Mae [[Special:WhatLinksHere/$2|rhestr lawn]] ar gael.",
'nolinkstoimage'                    => 'Nid oes cyswllt ar unrhyw dudalen yn arwain at y ffeil hon.',
'morelinkstoimage'                  => 'Gweld [[Special:WhatLinksHere/$1|rhagor o gysylltiadau]] at y ffeil hon.',
'redirectstofile'                   => "Mae'r {{PLURAL:$1||ffeil|$1 ffeil|$1 ffeil|$1 ffeil|$1 ffeil}} canlynol yn ailgyfeirio at y ffeil hon:",
'duplicatesoffile'                  => "Mae'r {{PLURAL:$1||ffeil|$1 ffeil|$1 ffeil|$1 ffeil|$1 ffeil}} canlynol yn union debyg i'r ffeil hon ([[Special:FileDuplicateSearch/$2|rhagor o fanylion]]):",
'sharedupload'                      => 'Daw y ffeil hon o $1, felly gall fod ar waith ar brosiectau eraill.',
'sharedupload-desc-there'           => "Daw'r ffeil hon o $1 a gellir ei defnyddio gan brosiectau eraill.
Am wybodaeth pellach gwelwch y disgrifiad ohoni sydd ar [$2 dudalen ddisgrifio'r ffeil] yno.",
'sharedupload-desc-here'            => "Daw'r ffeil hon o $1 a gellir ei defnyddio gan brosiectau eraill.
Dangosir isod y disgrifiad sydd ar [$2 dudalen ddisgrifio'r ffeil] yno.",
'filepage-nofile'                   => "Does dim ffeil o'r enw hwn ar gael.",
'filepage-nofile-link'              => "Does dim ffeil o'r enw hwn ar gael, ond gallwch [$1 ei huwchlwytho].",
'uploadnewversion-linktext'         => "Uwchlwytho fersiwn newydd o'r ffeil hon",
'shared-repo-from'                  => 'oddi ar $1',
'shared-repo'                       => 'storfa cyfrannol',
'shared-repo-name-wikimediacommons' => 'Comin Wikimedia',

# File reversion
'filerevert'                => 'Gwrthdroi $1',
'filerevert-legend'         => "Gwrthdroi'r ffeil",
'filerevert-intro'          => "Rydych yn gwrthdroi '''[[Media:$1|$1]]''' i'r fersiwn [$4 fel ag yr oedd ar $3, $2].",
'filerevert-comment'        => 'Rheswm:',
'filerevert-defaultcomment' => 'Wedi adfer fersiwn $2, $1',
'filerevert-submit'         => 'Gwrthdroi',
'filerevert-success'        => "Mae '''[[Media:$1|$1]]''' wedi cael ei wrthdroi i'r fersiwn [$4 fel ag yr oedd ar $3, $2].",
'filerevert-badversion'     => "Nid oes fersiwn lleol cynt o'r ffeil hwn gyda'r amsernod a nodwyd.",

# File deletion
'filedelete'                  => 'Dileu $1',
'filedelete-legend'           => "Dileu'r ffeil",
'filedelete-intro'            => "Rydych ar fin dileu'r ffeil '''[[Media:$1|$1]]''' ynghyd â'i holl hanes.",
'filedelete-intro-old'        => "You are deleting the version of '''[[Media:$1|$1]]''' as of [$4 $3, $2].",
'filedelete-comment'          => 'Rheswm:',
'filedelete-submit'           => 'Dileer',
'filedelete-success'          => "Mae '''$1''' wedi cael ei dileu.",
'filedelete-success-old'      => "The version of '''[[Media:$1|$1]]''' as of $3, $2 has been deleted.",
'filedelete-nofile'           => "Ni chafwyd '''$1'''.",
'filedelete-nofile-old'       => "Nid oes fersiwn o '''$1''' gyda'r priodoleddau a enwir yn yr archif.",
'filedelete-otherreason'      => 'Rheswm arall/ychwanegol:',
'filedelete-reason-otherlist' => 'Rheswm arall',
'filedelete-reason-dropdown'  => '*Rhesymau cyffredin dros ddileu
** Yn torri hawlfraint
** Dwy ffeil yn union debyg',
'filedelete-edit-reasonlist'  => 'Golygu rhestr y rhesymau dros ddileu',
'filedelete-maintenance'      => "Mae'r gallu i ddileu ffeiliau a'u hadfer wedi ei anallogi tra bod gwaith cynnal wrthi.",

# MIME search
'mimesearch'         => 'Chwiliad MIME',
'mimesearch-summary' => "Fe allwch ddefnyddio'r dudalen hon i hidlo'r ffeiliau yn ôl math MIME.
Mewnbwn: contenttype/subtype, e.e. <tt>image/jpeg</tt>.",
'mimetype'           => 'Ffurf MIME:',
'download'           => 'islwytho',

# Unwatched pages
'unwatchedpages' => 'Tudalennau sydd â neb yn eu gwylio',

# List redirects
'listredirects' => "Rhestru'r ail-gyfeiriadau",

# Unused templates
'unusedtemplates'     => 'Nodiadau heb eu defnyddio',
'unusedtemplatestext' => "Dyma restr o'r holl dudalennau yn y parth {{ns:template}} nad ydynt wedi eu cynnwys yn unrhyw dudalen arall.
Cofiwch chwilio am gysylltiadau eraill at nodyn a'u hystyried cyn ei ddileu.",
'unusedtemplateswlh'  => 'cysylltiadau eraill',

# Random page
'randompage'         => 'Tudalen ar hap',
'randompage-nopages' => 'Does dim tudalennau yn y {{PLURAL:$2|parth hwn|parth hwn|parthau hyn|parthau hyn|parthau hyn|parthau hyn}}: $1.',

# Random redirect
'randomredirect'         => 'Tudalen ailgyfeirio ar hap',
'randomredirect-nopages' => 'Does dim tudalennau ailgyfeirio yn y parth "$1".',

# Statistics
'statistics'                   => 'Ystadegau',
'statistics-header-pages'      => 'Ystadegau tudalennau',
'statistics-header-edits'      => 'Ystadegau golygiadau',
'statistics-header-views'      => 'Ystadegau ymweliadau',
'statistics-header-users'      => 'Ystadegau defnyddwyr',
'statistics-header-hooks'      => 'Ystadegau eraill',
'statistics-articles'          => 'Tudalennau pwnc',
'statistics-pages'             => 'Tudalennau',
'statistics-pages-desc'        => 'Pob tudalen yn y wici, gan gynnwys tudalennau sgwrs, ailgyfeiriadau, ayb.',
'statistics-files'             => 'Ffeiliau wedi eu huwchlwytho',
'statistics-edits'             => 'Golygiadau ers y dechrau ar {{SITENAME}}',
'statistics-edits-average'     => "Golygiadau'r dudalen, ar gyfartaledd",
'statistics-views-total'       => 'Cyfanswm yr ymweliadau',
'statistics-views-total-desc'  => 'Ni chynhwysir ymweliadau â thudalennau nad ydynt yn bod na thudalennau arbennig',
'statistics-views-peredit'     => 'Ymweliadau i bob golygiad:',
'statistics-users'             => '[[Special:ListUsers|Defnyddwyr]] cofrestredig',
'statistics-users-active'      => 'Defnyddwyr gweithgar',
'statistics-users-active-desc' => 'Defnyddwyr sydd wedi gweithredu unwaith neu ragor yn ystod y {{PLURAL:$1||diwrnod|deuddydd|tridiau|$1 diwrnod|$1 diwrnod}} diwethaf',
'statistics-mostpopular'       => "Tudalennau sy'n derbyn ymweliad amlaf",

'disambiguations'      => 'Tudalennau gwahaniaethu',
'disambiguationspage'  => 'Template:Gwahaniaethu',
'disambiguations-text' => "Mae'r tudalennau canlynol yn cysylltu â thudalennau gwahaniaethu. Yn hytrach dylent gysylltu'n syth â'r erthygl briodol.<br />Diffinir tudalen yn dudalen gwahaniaethu pan mae'n cynnwys un o'r nodiadau '[[MediaWiki:Disambiguationspage|tudalen gwahaniaethu]]'.",

'doubleredirects'            => 'Ailgyfeiriadau dwbl',
'doubleredirectstext'        => "Mae pob rhes yn cynnwys cysylltiad i'r ddau ail-gyfeiriad cyntaf, ynghyd â chyrchfan yr ail ailgyfeiriad. Fel arfer bydd hyn yn rhoi'r gwir dudalen y dylai'r tudalennau cynt gyfeirio ati.
Gosodwyd <del>llinell</del> drwy'r eitemau sydd eisoes wedi eu datrys.",
'double-redirect-fixed-move' => "Symudwyd [[$1]], a'i droi'n ailgyfeiriad at [[$2]]",
'double-redirect-fixer'      => 'Redirect fixer',

'brokenredirects'        => "Ailgyfeiriadau wedi'u torri",
'brokenredirectstext'    => "Mae'r ailgyfeiriadau isod yn cysylltu â thudalennau nad ydynt ar gael:",
'brokenredirects-edit'   => 'golygu',
'brokenredirects-delete' => 'dileu',

'withoutinterwiki'         => 'Tudalennau heb gysylltiadau ag ieithoedd eraill',
'withoutinterwiki-summary' => 'Nid oes gysylltiad rhwng y tudalennau canlynol a thudalennau mewn ieithoedd eraill:',
'withoutinterwiki-legend'  => 'Rhagddodiad',
'withoutinterwiki-submit'  => 'Dangos',

'fewestrevisions' => "Erthyglau â'r nifer lleiaf o olygiadau iddynt",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|beit|beit|feit|beit|beit|beit}}',
'ncategories'             => '$1 {{PLURAL:$1|categori|categori|gategori|chategori|chategori|categori}}',
'nlinks'                  => '$1 {{PLURAL:$1|cyswllt|cyswllt|gyswllt|chyswllt|chyswllt|cyswllt}}',
'nmembers'                => '$1 {{PLURAL:$1|aelod|aelod|aelod|aelod|aelod|aelod}}',
'nrevisions'              => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'nviews'                  => '$1 {{PLURAL:$1|ymweliad|ymweliad|ymweliad|ymweliad|ymweliad|ymweliad}}',
'nimagelinks'             => 'Defnyddir ar {{PLURAL:$1||$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 tudalen}}',
'ntransclusions'          => 'defnyddir ar {{PLURAL:$1||$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 tudalen}}',
'specialpage-empty'       => "Ni chafwyd canlyniadau i'w hadrodd.",
'lonelypages'             => 'Erthyglau heb gysylltiadau iddynt',
'lonelypagestext'         => 'Nid oes cysylltiad yn arwain at y tudalennau canlynol oddi wrth unrhyw dudalen arall yn {{SITENAME}}. Nid ydynt wedi eu trawsgynnwys ar unrhyw dudalen yn {{SITENAME}}, chwaith.',
'uncategorizedpages'      => 'Tudalennau heb gategori',
'uncategorizedcategories' => 'Categorïau sydd heb gategori',
'uncategorizedimages'     => 'Ffeiliau heb eu categoreiddio',
'uncategorizedtemplates'  => 'Nodiadau heb eu categoreiddio',
'unusedcategories'        => 'Categorïau gwag',
'unusedimages'            => 'Lluniau na ddefnyddiwyd eto',
'popularpages'            => 'Erthyglau poblogaidd',
'wantedcategories'        => 'Categorïau sydd eu hangen',
'wantedpages'             => 'Erthyglau sydd eu hangen',
'wantedpages-badtitle'    => 'Mae teitl annilys ymhlith y canlyniadau, sef: $1',
'wantedfiles'             => 'Ffeiliau sydd eu hangen',
'wantedtemplates'         => 'Nodiadau sydd eu hangen',
'mostlinked'              => 'Tudalennau yn nhrefn nifer y cysylltiadau iddynt',
'mostlinkedcategories'    => 'Categorïau yn nhrefn nifer eu haelodau',
'mostlinkedtemplates'     => 'Nodiadau yn nhrefn nifer y cysylltiadau iddynt',
'mostcategories'          => 'Erthyglau yn nhrefn nifer eu categorïau',
'mostimages'              => 'Ffeiliau yn nhrefn nifer y cysylltiadau iddynt',
'mostrevisions'           => 'Tudalennau yn nhrefn nifer y newidiadau iddynt',
'prefixindex'             => 'Pob tudalen yn ôl parth',
'shortpages'              => 'Erthyglau byr',
'longpages'               => 'Tudalennau hirion',
'deadendpages'            => 'Tudalennau heb gysylltiadau ynddynt',
'deadendpagestext'        => "Nid oes cysylltiad yn arwain at dudalen arall oddi wrth yr un o'r tudalennau isod.",
'protectedpages'          => 'Tudalennau wedi eu diogelu',
'protectedpages-indef'    => 'A ddiogelwyd yn ddi-derfyn yn unig',
'protectedpages-cascade'  => 'A sgydol-ddiogelwyd yn unig',
'protectedpagestext'      => "Mae'r tudalennau hyn wedi eu diogelu rhag cael eu symud na'u golygu",
'protectedpagesempty'     => "Does dim tudalennau wedi eu diogelu gyda'r paramedrau hyn.",
'protectedtitles'         => 'Teitlau wedi eu diogelu',
'protectedtitlestext'     => "Diogelwyd rhag creu tudalennau gyda'r teitlau hyn",
'protectedtitlesempty'    => "Ar hyn o bryd nid oes unrhyw deitlau wedi eu diogelu a'r paramedrau hyn.",
'listusers'               => 'Rhestr defnyddwyr',
'listusers-editsonly'     => 'Dangos y defnyddwyr hynny sydd wedi golygu rhywbeth yn unig',
'listusers-creationsort'  => 'Trefnwch yn ôl dyddiad creu',
'usereditcount'           => '$1 {{PLURAL:$1|golygiad|golygiad|olygiad|golygiad|golygiad|o olygiadau}}',
'usercreated'             => 'Crëwyd ar $1 am $2',
'newpages'                => 'Erthyglau newydd',
'newpages-username'       => 'Enw defnyddiwr:',
'ancientpages'            => 'Erthyglau hynaf',
'move'                    => 'Symud',
'movethispage'            => 'Symud y dudalen hon',
'unusedimagestext'        => "Mae'r ffeiliau canlynol ar gael, ond nid ydynt wedi eu mewnosod ar unrhyw dudalen.
Sylwch y gall gwefannau eraill gysylltu â ffeil drwy URL uniongyrchol. Gan hynny mae'n bosib fod ffeil wedi ei rhestru yma serch bod defnydd arni.",
'unusedcategoriestext'    => "Mae'r tudalennau categori isod yn bodoli er nad oes unrhyw dudalen arall yn eu defnyddio.",
'notargettitle'           => 'Dim targed',
'notargettext'            => 'Dydych chi ddim wedi dewis defnyddiwr neu dudalen i weithredu arno.',
'nopagetitle'             => "Nid yw'r dudalen a ddynodwyd ar gael",
'nopagetext'              => "Nid yw'r dudalen a ddynodwyd ar gael.",
'pager-newer-n'           => '{{PLURAL:$1|y $1 mwy diweddar|yr 1 mwy diweddar|y $1 mwy diweddar|y $1 mwy diweddar|y $1 mwy diweddar|y $1 mwy diweddar}}.',
'pager-older-n'           => '{{PLURAL:$1|y $1 cynharach|yr $1 cynharach|y $1 cynharach|y $1 cynharach|y $1 cynharach|y $1 cynharach}}',
'suppress'                => 'Goruchwylio',
'querypage-disabled'      => 'Analluogwyd y dudalen arbennig hon er mwyn osgoi iddi andwyo perfformiad y wefan.',

# Book sources
'booksources'               => 'Ffynonellau llyfrau',
'booksources-search-legend' => 'Chwilier am lyfrau',
'booksources-go'            => 'Mynd',
'booksources-text'          => "Mae'r rhestr isod yn cynnwys cysylltiadau i wefannau sy'n gwerthu llyfrau newydd a rhai ail-law. Mae rhai o'r gwefannau hefyd yn cynnig gwybodaeth pellach am y llyfrau hyn:",
'booksources-invalid-isbn'  => "Ymddengys nad yw'r rhif ISBN hwn yn ddilys; efallai y cafwyd gwall wrth drosglwyddo'r rhif.",

# Special:Log
'specialloguserlabel'  => 'Defnyddiwr:',
'speciallogtitlelabel' => 'Teitl:',
'log'                  => 'Logiau',
'all-logs-page'        => 'Pob lòg cyhoeddus',
'alllogstext'          => "Mae pob cofnod yn holl logiau {{SITENAME}} wedi cael eu rhestru yma.
Gallwch weld chwiliad mwy penodol trwy ddewis y math o lòg, enw'r defnyddiwr, neu'r dudalen benodedig.
Sylwer bod llythrennau mawr neu fach o bwys i'r chwiliad.",
'logempty'             => 'Does dim eitemau yn cyfateb yn y lòg.',
'log-title-wildcard'   => "Chwilio am deitlau'n dechrau gyda'r geiriau hyn",

# Special:AllPages
'allpages'          => 'Pob tudalen',
'alphaindexline'    => '$1 i $2',
'nextpage'          => 'Y bloc nesaf gan ddechrau gyda ($1)',
'prevpage'          => 'Y bloc cynt gan ddechrau gyda ($1)',
'allpagesfrom'      => 'Dangos pob tudalen gan ddechrau o:',
'allpagesto'        => 'Dangos pob tudalen hyd at:',
'allarticles'       => 'Pob erthygl',
'allinnamespace'    => 'Pob tudalen (parth $1)',
'allnotinnamespace' => 'Pob tudalen (heblaw am y parth $1)',
'allpagesprev'      => 'Gynt',
'allpagesnext'      => 'Nesaf',
'allpagessubmit'    => 'Eler',
'allpagesprefix'    => 'Dangos pob tudalen mewn parth gan ddechrau o:',
'allpagesbadtitle'  => 'Roedd y darpar deitl yn annilys oherwydd bod ynddo naill ai:<p> - rhagddodiad rhyngwici neu ryngieithol, neu </p>- nod neu nodau na ellir eu defnyddio mewn teitlau.',
'allpages-bad-ns'   => 'Nid oes gan {{SITENAME}} barth o\'r enw "$1".',

# Special:Categories
'categories'                    => 'Categorïau',
'categoriespagetext'            => "Mae'r {{PLURAL:$1|categori|categori|categorïau|categorïau|categorïau|categorïau}} isod yn cynnwys tudalennau neu ffeiliau amlgyfrwng.
Ni ddangosir [[Special:UnusedCategories|categorïau gwag]] yma.
Gweler hefyd [[Special:WantedCategories|categorïau sydd eu hangen]].",
'categoriesfrom'                => 'Dangos categorïau gan ddechrau gyda:',
'special-categories-sort-count' => 'trefnu yn ôl nifer',
'special-categories-sort-abc'   => 'trefnu yn ôl yr wyddor',

# Special:DeletedContributions
'deletedcontributions'             => 'Cyfraniadau defnyddiwr i dudalennau dilëedig',
'deletedcontributions-title'       => 'Cyfraniadau defnyddiwr i dudalennau dilëedig',
'sp-deletedcontributions-contribs' => 'cyfraniadau',

# Special:LinkSearch
'linksearch'       => 'Cysylltiadau allanol',
'linksearch-pat'   => 'Patrwm chwilio:',
'linksearch-ns'    => 'Parth:',
'linksearch-ok'    => 'Chwilio',
'linksearch-text'  => 'Gellir defnyddio cardiau gwyllt megis "*.wikipedia.org".<br />
Protocoliau sy\'n cael eu cynnal: <tt>$1</tt>',
'linksearch-line'  => 'Mae cysylltiad i gael i $1 oddi wrth $2',
'linksearch-error' => "Dim ond ar ddechrau enw'r gwesteiwr y gallwch osod cardiau gwyllt.",

# Special:ListUsers
'listusersfrom'      => 'Dangos y defnyddwyr gan ddechrau â:',
'listusers-submit'   => 'Dangos',
'listusers-noresult' => "Dim defnyddiwr i'w gael.",
'listusers-blocked'  => '(wedi ei flocio)',

# Special:ActiveUsers
'activeusers'            => 'Rhestr defnyddwyr gweithgar',
'activeusers-intro'      => 'Dyma restr y defnyddwyr a fuont yn weithgar o fewn y {{PLURAL:$1|diwrnod|diwrnod|deuddydd|tridiau|$1 diwrnod|$1 diwrnod}} diwethaf.',
'activeusers-count'      => '$1 {{PLURAL:$1|golygiad|golygiad|olygiad|golygiad|golygiad|golygiad}} yn ystod y {{PLURAL:$3|diwrnod|diwrnod|deuddydd|tridiau|$3 diwrnod|$3 diwrnod}} diwethaf',
'activeusers-from'       => "Rhestru'r defnyddwyr gan ddechrau gyda:",
'activeusers-hidebots'   => 'Cuddio botiau',
'activeusers-hidesysops' => 'Cuddio gweinyddwyr',
'activeusers-noresult'   => "Dim defnyddwyr i'w cael.",

# Special:Log/newusers
'newuserlogpage'              => 'Lòg creu cyfrifon defnyddwyr newydd',
'newuserlogpagetext'          => "Dyma restr o'r defnyddwyr newydd sydd wedi ymuno â'r wici.",
'newuserlog-byemail'          => 'anfonwyd y cyfrinair trwy e-bost',
'newuserlog-create-entry'     => 'Defnyddiwr newydd',
'newuserlog-create2-entry'    => 'wedi creu cyfrif newydd ar gyfer $1',
'newuserlog-autocreate-entry' => "Cyfrif wedi ei greu'n awtomatig",

# Special:ListGroupRights
'listgrouprights'                      => 'Galluoedd grwpiau defnyddwyr',
'listgrouprights-summary'              => "Dyma restr o'r grwpiau defnyddwyr sydd i'w cael ar y wici hon, ynghyd â galluoedd aelodau'r gwahanol grwpiau. Cewch wybodaeth pellach am y gwahanol alluoedd ar y [[{{MediaWiki:Listgrouprights-helppage}}|dudalen gymorth]].",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Gallu sydd wedi ei roi</span>
* <span class="listgrouprights-revoked">Gallu sydd wedi ei dynnu yn ei ôl</span>',
'listgrouprights-group'                => 'Grŵp',
'listgrouprights-rights'               => 'Galluoedd',
'listgrouprights-helppage'             => 'Help:Galluoedd yn ôl grŵp',
'listgrouprights-members'              => '(rhestr aelodau)',
'listgrouprights-addgroup'             => "Yn gallu ychwanegu'r {{PLURAL:$2|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}}: $1",
'listgrouprights-removegroup'          => "Yn gallu tynnu'r {{PLURAL:$2|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}}: $1",
'listgrouprights-addgroup-all'         => "Yn gallu ychwanegu'r holl grwpiau",
'listgrouprights-removegroup-all'      => "Yn gallu tynnu'r holl grwpiau",
'listgrouprights-addgroup-self'        => 'Yn gallu ychwanegu {{PLURAL:$2|grŵp}} at eich cyfrif eich hunan: $1',
'listgrouprights-removegroup-self'     => 'Yn gallu tynnu {{PLURAL:$2|grŵp}} oddi ar eich cyfrif eich hunan: $1',
'listgrouprights-addgroup-self-all'    => "Yn gallu ychwanegu'r holl grwpiau at eich cyfrif eich hunan",
'listgrouprights-removegroup-self-all' => "Yn gallu tynnu'r holl grwpiau oddi ar eich cyfrif eich hunan",

# E-mail user
'mailnologin'          => "Does dim cyfeiriad i'w anfon iddo",
'mailnologintext'      => 'Rhaid eich bod wedi [[Special:UserLogin|mewngofnodi]]
a bod cyfeiriad e-bost dilys yn eich [[Special:Preferences|dewisiadau]]
er mwyn medru anfon e-bost at ddefnyddwyr eraill.',
'emailuser'            => 'Anfon e-bost at y defnyddiwr hwn',
'emailpage'            => 'Anfon e-bost at ddefnyddiwr',
'emailpagetext'        => "Os yw'r cyfeiriad e-bost sydd yn newisiadau'r defnyddiwr hwn yn un dilys, gellir anfon neges ato o'i ysgrifennu ar y ffurflen isod.
Bydd y cyfeiriad e-bost a osodoch yn eich [[Special:Preferences|dewisiadau chithau]] yn ymddangos ym maes \"Oddi wrth\" yr e-bost, fel bod y defnyddiwr arall yn gallu anfon ateb atoch.",
'usermailererror'      => 'Dychwelwyd gwall gan y rhaglen e-bost:',
'defemailsubject'      => 'E-bost {{SITENAME}}',
'usermaildisabled'     => 'Dim modd anfon e-bost at ddefnyddwyr',
'usermaildisabledtext' => 'Ni allwch anfon e-bost at ddefnyddwyr eraill y wici hwn',
'noemailtitle'         => 'Dim cyfeiriad e-bost',
'noemailtext'          => "Nid yw'r defnyddiwr hwn wedi gosod cyfeiriad e-bost dilys.",
'nowikiemailtitle'     => 'Ni chaniateir e-bostio',
'nowikiemailtext'      => "Mae'r defnyddiwr hwn wedi dewis peidio derbyn e-byst oddi wrth ddefnyddwyr eraill.",
'email-legend'         => 'Anfon e-bost at ddefnyddiwr {{SITENAME}} arall',
'emailfrom'            => 'Oddi wrth:',
'emailto'              => 'At:',
'emailsubject'         => 'Pwnc:',
'emailmessage'         => 'Neges:',
'emailsend'            => 'Anfon',
'emailccme'            => "Anfoner gopi o'r neges e-bost ataf.",
'emailccsubject'       => "Copi o'ch neges at $1: $2",
'emailsent'            => "Neges e-bost wedi'i hanfon",
'emailsenttext'        => 'Mae eich neges e-bost wedi cael ei hanfon.',
'emailuserfooter'      => 'Anfonwyd yr e-bost hwn oddi wrth $1 at $2 trwy ddefnyddio\'r teclyn "Anfon e-bost at ddefnyddiwr" ar {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Yn gadael neges am ddigwyddiad yn y sustem.',
'usermessage-editor'  => 'Golygydd neges y system',

# Watchlist
'watchlist'            => 'Fy rhestr wylio',
'mywatchlist'          => 'Fy rhestr wylio',
'watchlistfor2'        => 'Yn ôl gofyn $1 $2',
'nowatchlist'          => "Mae eich rhestr wylio'n wag.",
'watchlistanontext'    => "Rhaid $1 er mwyn gweld neu ddiwygio'ch rhestr wylio.",
'watchnologin'         => 'Nid ydych wedi mewngofnodi',
'watchnologintext'     => "Mae'n rhaid i chi [[Special:UserLogin|fewngofnodi]] er mwyn newid eich rhestr wylio.",
'addedwatch'           => 'Rhoddwyd ar eich rhestr wylio',
'addedwatchtext'       => "Mae'r dudalen \"[[:\$1|\$1]]\" wedi cael ei hychwanegu at eich [[Special:Watchlist|rhestr wylio]].
Pan fydd y dudalen hon, neu ei thudalen sgwrs, yn newid, fe fyddant yn ymddangos ar eich rhestr wylio ac hefyd '''yn gryf''' ar restr y [[Special:RecentChanges|newidiadau diweddar]], fel ei bod yn haws eu gweld.

Os ydych am ddiddymu'r dudalen o'r rhestr wylio, cliciwch ar \"Stopio gwylio\" yn y bar ar frig y dudalen.",
'removedwatch'         => 'Tynnwyd oddi ar eich rhestr wylio',
'removedwatchtext'     => 'Mae\'r dudalen "[[:$1]]" wedi\'i thynnu oddi ar [[Special:Watchlist|eich rhestr wylio]].',
'watch'                => 'Gwylio',
'watchthispage'        => 'Gwylier y dudalen hon',
'unwatch'              => 'Stopio gwylio',
'unwatchthispage'      => 'Stopio gwylio',
'notanarticle'         => 'Ddim yn erthygl/ffeil',
'notvisiblerev'        => 'Y diwygiad wedi cael ei ddileu',
'watchnochange'        => "Ni olygwyd dim o'r erthyglau yr ydych yn cadw golwg arnynt yn ystod y cyfnod uchod.",
'watchlist-details'    => '{{PLURAL:$1|Nid oes dim tudalennau|Mae $1 dudalen|Mae $1 dudalen|Mae $1 tudalen|Mae $1 thudalen|Mae $1 o dudalennau}} ar eich rhestr wylio, heb gynnwys tudalennau sgwrs.',
'wlheader-enotif'      => '* Galluogwyd hysbysiadau trwy e-bost.',
'wlheader-showupdated' => "* Mae tudalennau sydd wedi newid ers i chi ymweld ddiwethaf wedi'u '''hamlygu'''.",
'watchmethod-recent'   => "yn chwilio'r diwygiadau diweddar am dudalennau ar y rhestr wylio",
'watchmethod-list'     => "yn chwilio'r tudalennau ar y rhestr wylio am ddiwygiadau diweddar",
'watchlistcontains'    => '{{PLURAL:$1|Nid oes dim tudalennau|Mae $1 dudalen|Mae $1 dudalen|Mae $1 tudalen|Mae $1 thudalen|Mae $1 o dudalennau}} ar eich rhestr wylio.',
'iteminvalidname'      => "Problem gyda'r eitem '$1', enw annilys...",
'wlnote'               => "{{PLURAL:$1|Ni fu unrhyw newid|Isod mae'r '''$1''' newid diweddaraf|Isod mae'r '''$1''' newid diweddaraf|Isod mae'r '''$1''' newid diweddaraf|Isod mae'r '''$1''' newid diweddaraf|Isod mae'r '''$1''' newid diweddaraf}} yn ystod {{PLURAL:$2||yr awr|y ddwyawr|y teirawr|y <b>$2</b> awr|y(r) <b>$2</b> awr}} ddiwethaf.",
'wlshowlast'           => "Dangoser newidiadau'r $1 awr ddiwethaf neu'r $2 {{PLURAL:$2|diwrnod|diwrnod|ddiwrnod|diwrnod|diwrnod|diwrnod}} diwethaf neu'r $3 newidiadau.",
'watchlist-options'    => 'Dewisiadau ar gyfer y rhestr wylio',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => "Wrthi'n ychwanegu...",
'unwatching' => "Wrthi'n tynnu...",

'enotif_mailer'                => 'Sustem hysbysu {{SITENAME}}',
'enotif_reset'                 => 'Ystyried bod pob tudalen wedi cael ymweliad',
'enotif_newpagetext'           => 'Mae hon yn dudalen newydd.',
'enotif_impersonal_salutation' => 'at ddefnyddiwr {{SITENAME}}',
'changed'                      => 'Newidiwyd',
'created'                      => 'crëwyd',
'enotif_subject'               => '$CHANGEDORCREATED y dudalen \'$PAGETITLE\' ar {{SITENAME}} gan $PAGEEDITOR',
'enotif_lastvisited'           => 'Gwelwch $1 am bob newid ers eich ymweliad blaenorol.',
'enotif_lastdiff'              => 'Gallwch weld y newid ar $1.',
'enotif_anon_editor'           => 'defnyddiwr anhysbys $1',
'enotif_body'                  => 'Annwyl $WATCHINGUSERNAME,

$CHANGEDORCREATED y dudalen \'$PAGETITLE\' ar {{SITENAME}} ar $PAGEEDITDATE gan $PAGEEDITOR; gwelir y diwygiad presennol ar $PAGETITLE_URL.

$NEWPAGE

Crynodeb y golygydd: $PAGESUMMARY $PAGEMINOREDIT

Cysylltu â\'r golygydd:
e-bost: $PAGEEDITOR_EMAIL
wici: $PAGEEDITOR_WIKI

Os digwydd mwy o olygiadau i\'r dudalen cyn i chi ymweld â hi, ni chewch ragor o negeseuon hysbysu. Nodwn bod modd i chi ailosod y fflagiau hysbysu ar eich rhestr wylio, ar gyfer y tudalennau rydych yn eu gwylio.

             Sustem hysbysu {{SITENAME}}

--
I newid eich gosodiadau gwylio, ymwelwch â
{{fullurl:{{#special:Watchlist}}/edit}}

I dynnu\'r dudalen oddi ar eich rhestr wylio, ewch at
$UNWATCHURL

Am fwy o gymorth ac adborth:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Dilëer y dudalen',
'confirm'                => 'Cadarnhau',
'excontent'              => "y cynnwys oedd: '$1'",
'excontentauthor'        => "y cynnwys oedd: '$1' (a'r unig gyfrannwr oedd '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "y cynnwys cyn blancio oedd: '$1'",
'exblank'                => 'roedd y dudalen yn wag',
'delete-confirm'         => 'Dileu "$1"',
'delete-legend'          => 'Dileu',
'historywarning'         => "'''Rhybudd:''' bu tua $1 {{PLURAL:$1|golygiad|golygiad|olygiad|golygiad|golygiad|o olygiadau}} yn hanes y dudalen rydych ar fin ei dileu:",
'confirmdeletetext'      => "Rydych chi ar fin dileu tudalen neu ddelwedd, ynghŷd â'i hanes, o'r data-bas, a hynny'n barhaol.
Os gwelwch yn dda, cadarnhewch eich bod chi wir yn bwriadu gwneud hyn, eich bod yn deall y canlyniadau, ac yn ei wneud yn ôl [[{{MediaWiki:Policy-url}}|polisïau {{SITENAME}}]].",
'actioncomplete'         => "Wedi cwblhau'r weithred",
'actionfailed'           => 'Methodd y weithred',
'deletedtext'            => 'Mae "<nowiki>$1</nowiki>" wedi\'i ddileu.
Gwelwch y $2 am gofnod o\'r dileuon diweddar.',
'deletedarticle'         => 'wedi dileu "[[$1]]"',
'suppressedarticle'      => 'cuddiwyd "[[$1]]"',
'dellogpage'             => 'Log dileuon',
'dellogpagetext'         => "Ceir rhestr isod o'r dileadau diweddaraf.",
'deletionlog'            => 'lòg dileuon',
'reverted'               => "Wedi gwrthdroi i'r golygiad cynt",
'deletecomment'          => 'Rheswm:',
'deleteotherreason'      => 'Rheswm arall:',
'deletereasonotherlist'  => 'Rheswm arall',
'deletereason-dropdown'  => "*Rhesymau arferol dros ddileu
** Ar gais yr awdur
** Torri'r hawlfraint
** Fandaliaeth",
'delete-edit-reasonlist' => 'Golygu rhestr y rhesymau dros ddileu',
'delete-toobig'          => "Cafwyd dros $1 {{PLURAL:$1|o olygiadau}} i'r dudalen hon.
Cyfyngwyd ar y gallu i ddileu tudalennau sydd wedi eu golygu cymaint â hyn, er mwyn osgoi amharu ar weithrediad databas {{SITENAME}} yn ddamweiniol.",
'delete-warning-toobig'  => "Cafwyd dros $1 {{PLURAL:$1|o olygiadau}} i'r dudalen hon.
Gallai dileu tudalen, gyda hanes golygu cymaint â hyn iddi, beri dryswch i weithrediadau'r databas ar {{SITENAME}}; ewch ati'n ofalus.",

# Rollback
'rollback'          => 'Gwrthdroi golygiadau',
'rollback_short'    => 'Gwrthdroi',
'rollbacklink'      => 'gwrthdroi',
'rollbackfailed'    => 'Methodd y gwrthdroi',
'cantrollback'      => "Wedi methu gwrthdroi'r golygiad; y cyfrannwr diwethaf oedd unig awdur y dudalen hon.",
'alreadyrolled'     => "Nid yw'n bosib dadwneud y golygiad diwethaf i'r dudalen [[:$1|$1]] gan [[User:$2|$2]] ([[User talk:$2|Sgwrs]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
mae rhywun arall eisoes wedi dadwneud y golygiad neu wedi golygu'r dudalen.

[[User:$3|$3]] ([[User talk:$3|Sgwrs]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) a wnaeth y golygiad diwethaf.",
'editcomment'       => "Crynodeb y golygiad oedd: \"''\$1''\".",
'revertpage'        => 'Wedi gwrthdroi golygiadau gan [[Special:Contributions/$2|$2]] ([[User talk:$2|Sgwrs]]); wedi adfer y golygiad diweddaraf gan [[User:$1|$1]]',
'revertpage-nouser' => 'Wedi gwrthdroi golygiadau gan (enw wedi ei guddio); wedi adfer y golygiad diweddaraf gan [[User:$1|$1]]',
'rollback-success'  => "Gwrthdrowyd y golygiadau gan $1;
wedi gwrthdroi i'r golygiad olaf gan $2.",

# Edit tokens
'sessionfailure-title' => 'Sesiwn wedi methu',
'sessionfailure'       => "Mae'n debyg fod yna broblem gyda'ch sesiwn mewngofnodi; diddymwyd y weithred er mwyn diogelu'r sustem rhag ddefnyddwyr maleisus. Gwasgwch botwm 'nôl' eich porwr ac ail-lwythwch y dudalen honno, yna ceisiwch eto.",

# Protect
'protectlogpage'              => 'Lòg diogelu',
'protectlogtext'              => "Isod mae rhestr o bob gweithred diogelu (a dad-ddiogelu) tudalen.
Mae'r tudalennau sydd wedi eu diogelu ar hyn o bryd wedi eu rhestri ar y [[Special:ProtectedPages|rhestr tudalennau wedi eu diogelu]].",
'protectedarticle'            => "wedi diogelu '[[$1]]'",
'modifiedarticleprotection'   => 'wedi newid y lefel diogelu ar gyfer "[[$1]]"',
'unprotectedarticle'          => 'wedi dad-ddiogelu "[[$1]]"',
'movedarticleprotection'      => 'wedi symud y gosodiadau gwarchod o "[[$2]]" i "[[$1]]"',
'protect-title'               => "Newid y lefel diogelu ar gyfer '$1'",
'prot_1movedto2'              => 'wedi symud [[$1]] i [[$2]]',
'protect-legend'              => "Cadarnháu'r diogelu",
'protectcomment'              => 'Rheswm:',
'protectexpiry'               => 'Yn dod i ben:',
'protect_expiry_invalid'      => 'Amser terfynu annilys.',
'protect_expiry_old'          => "Mae'r amser darfod yn y gorffennol.",
'protect-unchain-permissions' => 'Datgloi rhagor o opsiynau diogelu',
'protect-text'                => "Yma, gallwch weld a newid y lefel diogelu ar gyfer y dudalen '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Ni allwch newid y lefel diogelu tra eich bod wedi eich blocio.
Dyma'r gosodiadau diogelu cyfredol ar gyfer y dudalen '''$1''':",
'protect-locked-dblock'       => "Ni ellir newid y lefel diogelu gan fod y databas dan glo.
Dyma'r gosodiadau diogelu cyfredol ar gyfer y dudalen '''$1''':",
'protect-locked-access'       => "Nid yw'r gallu i newid lefel diogelu ar dudalen ynghlwm wrth eich cyfrif defnyddiwr.
Dyma'r gosodiadau diogelu cyfredol ar gyfer y dudalen '''$1''':",
'protect-cascadeon'           => "Mae'r dudalen hon wedi ei diogelu ar hyn o bryd oherwydd ei bod wedi ei chynnwys yn y {{PLURAL:$1|dudalen|dudalen|tudalennau|tudalennau|tudalennau|tudalennau}} canlynol sydd wedi {{PLURAL:$1|ei|ei|eu|eu|eu|eu}} sgydol-diogelu.  Gallwch newid lefel diogelu'r dudalen hon, ond ni fydd hynny'n effeithio ar y sgydol-ddiogelu.",
'protect-default'             => "Caniatáu'r gallu i bob defnyddiwr",
'protect-fallback'            => 'Mynnu\'r gallu "$1"',
'protect-level-autoconfirmed' => "Blocio defnyddwyr newydd a'r rhai heb gyfrif",
'protect-level-sysop'         => 'Gweinyddwyr yn unig',
'protect-summary-cascade'     => 'sgydol',
'protect-expiring'            => 'yn dod i ben am $1 (UTC)',
'protect-expiry-indefinite'   => 'amhenodol',
'protect-cascade'             => 'Diogelwch dudalennau sydd wedi eu cynnwys yn y dudalen hon (diogelu sgydol)',
'protect-cantedit'            => "Ni allwch newid lefel diogelu'r dudalen hon, am nad yw'r gallu i olygu'r dudalen ganddoch.",
'protect-othertime'           => 'Cyfnod arall:',
'protect-othertime-op'        => 'cyfnod arall',
'protect-existing-expiry'     => "Ar hyn o bryd daw'r gwarchod i ben am: $3, $2",
'protect-otherreason'         => 'Rheswm arall:',
'protect-otherreason-op'      => 'Rheswm arall',
'protect-dropdown'            => '*Rhesymau cyffredin dros ddiogelu
** Fandaliaeth yn rhemp
** Sbam yn rhemp
** Ymrafael golygu gwrthgynhyrchiol
** Tudalen aml ei defnydd',
'protect-edit-reasonlist'     => "Golygu'r rhesymau dros ddiogelu",
'protect-expiry-options'      => 'awr:1 hour,ddiwrnod:1 day,wythnos:1 week,bythefnos:2 weeks,fis:1 month,3 mis:3 months,6 mis:6 months,flwyddyn:1 year,gyfnod amhenodol:infinite',
'restriction-type'            => 'Cyfyngiad:',
'restriction-level'           => 'Lefel cyfyngu:',
'minimum-size'                => 'Maint lleiaf',
'maximum-size'                => 'Maint mwyaf:',
'pagesize'                    => '(beit)',

# Restrictions (nouns)
'restriction-edit'   => 'Golygu',
'restriction-move'   => 'Symud',
'restriction-create' => 'Gosod',
'restriction-upload' => 'Uwchlwytho',

# Restriction levels
'restriction-level-sysop'         => 'diogelwyd yn llwyr',
'restriction-level-autoconfirmed' => 'lled-ddiogelwyd',
'restriction-level-all'           => 'pob lefel',

# Undelete
'undelete'                     => 'Gweld tudalennau dilëedig',
'undeletepage'                 => 'Gweld ac adfer tudalennau dilëedig',
'undeletepagetitle'            => "'''Dyma'r diwygiadau dilëedig o [[:$1|$1]]'''.",
'viewdeletedpage'              => "Gweld tudalennau sydd wedi'u dileu",
'undeletepagetext'             => "Mae'r {{PLURAL:$1|tudalennau|dudalen|tudalennau|tudalennau|tudalennau|tudalennau}} isod wedi cael eu dileu ond mae cofnod ohonynt o hyd yn yr archif, felly mae'n bosibl eu hadfer.
Gall yr archif gael ei glanhau o dro i dro.",
'undelete-fieldset-title'      => 'Dewis ac adfer diwygiadau',
'undeleteextrahelp'            => "I adfer hanes gyfan y dudalen, gadewch pob blwch ticio'n wag a phwyswch y botwm '''''Adfer'''''. I adfer rhai diwygiadau'n unig, ticiwch y blychau ar gyfer y diwygiadau yr ydych am eu hadfer, yna pwyswch ar '''''Adfer'''''. Os y pwyswch ar '''''Ailosod''''' bydd y blwch sylwadau a phob blwch ticio yn gwacáu.",
'undeleterevisions'            => 'Gosodwyd $1 {{PLURAL:$1|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn}} yn yr archif',
'undeletehistory'              => "Os adferwch y dudalen, fe fydd yr holl ddiwygiadau yn cael eu hadfer hefyd yn hanes y dudalen.
Os oes tudalen newydd o'r un enw wedi cael ei chreu ers y dilëad, fe ddangosir y diwygiadau cynt yn yr hanes, heb ddisodli'r dudalen bresennol.",
'undeleterevdel'               => "Ni fydd yr adfer yn cael ei chyflawni pe byddai peth o'r diwygiad blaen i'r dudalen neu'r ffeil yn cael ei dileu oherwydd yr adfer.
Os hynny, rhaid i chi dad-ticio'r diwygiad dilëedig diweddaraf neu ei ddatguddio.",
'undeletehistorynoadmin'       => "Mae'r dudalen hon wedi'i dileu. Dangosir y rheswm am y dileu isod, gyda manylion o'r holl ddefnyddwyr sydd wedi golygu'r dudalen cyn y dileu. Dim ond gweinyddwyr sydd yn gallu gweld testun y diwygiadau i'r dudalen.",
'undelete-revision'            => 'Testun y golygiad gan $3 o $1 (fel ag yr oedd am $5 ar $4), a ddilëwyd:',
'undeleterevision-missing'     => "Y diwygiad yn annilys neu yn eisiau.
Mae'n bosib bod nam ar y cyswllt, neu fod y diwygiad eisoes wedi ei adfer neu wedi ei ddileu o'r archif.",
'undelete-nodiff'              => 'Ni chafwyd hyd i olygiad cynharach.',
'undeletebtn'                  => 'Adfer!',
'undeletelink'                 => 'gweld/adfer',
'undeleteviewlink'             => 'gweld',
'undeletereset'                => 'Ailosoder',
'undeleteinvert'               => "Troi'r dewis tu chwith",
'undeletecomment'              => 'Rheswm:',
'undeletedarticle'             => 'wedi adfer "[[$1]]"',
'undeletedrevisions'           => 'wedi adfer $1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'undeletedrevisions-files'     => 'Adferwyd $1 {{PLURAL:$1|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn}} a $2 {{PLURAL:$2|ffeil|ffeil|ffeil|ffeil|ffeil|ffeil}}',
'undeletedfiles'               => 'Adferwyd $1 {{PLURAL:$1|ffeil|ffeil|ffeil|ffeil|ffeil|ffeil}}',
'cannotundelete'               => "Wedi methu dad-ddileu;
gall rhywyn arall fod wedi dad-ddileu'r dudalen yn barod.",
'undeletedpage'                => "'''Adferwyd $1'''

Ceir cofnod o'r tudalennau a ddilëwyd neu a adferwyd yn ddiweddar ar y [[Special:Log/delete|lòg dileuon]].",
'undelete-header'              => "Ewch i'r [[Special:Log/delete|lòg dileuon]] i weld tudalennau a ddilëwyd yn ddiweddar.",
'undelete-search-box'          => "Chwilio'r tudalennau a ddilëwyd",
'undelete-search-prefix'       => 'Dangos tudalennau gan ddechrau gyda:',
'undelete-search-submit'       => 'Chwilio',
'undelete-no-results'          => 'Ni chafwyd hyd i dudalennau cyfatebol yn archif y dileuon.',
'undelete-filename-mismatch'   => "Ni ellir adfer y golgiad ffeil â'r stamp amser $1: nid oedd enw'r ffeil yn cysefeillio",
'undelete-bad-store-key'       => "Ni ellir adfer y golgiad ffeil â'r stamp amser $1: roedd y ffeil yn eisiau cyn y dileuad.",
'undelete-cleanup-error'       => 'Cafwyd gwall wrth ddileu\'r ffeil archif na ddefnyddiwyd "$1".',
'undelete-missing-filearchive' => "Ni ellid adfer archif y ffeil â'r ID $1 oherwydd nad yw yn y databas.
Efallai ei bod eisoes wedi ei hadfer.",
'undelete-error-short'         => 'Gwall wrth adfer y ffeil: $1',
'undelete-error-long'          => 'Cafwyd gwallau wrth adfer y ffeil:

$1',
'undelete-show-file-confirm'   => 'Ydych chi\'n sicr eich bod am weld golygiad dilëedig o\'r ffeil "<nowiki>$1</nowiki>" a roddwyd ar gadw ar $2 am $3?',
'undelete-show-file-submit'    => 'Ydw',

# Namespace form on various pages
'namespace'      => 'Parth:',
'invert'         => 'Dewiswch bob parth heblaw am hwn',
'blanknamespace' => '(Prif)',

# Contributions
'contributions'       => "Cyfraniadau'r defnyddiwr",
'contributions-title' => "Cyfraniadau'r defnyddiwr $1",
'mycontris'           => 'Fy nghyfraniadau',
'contribsub2'         => 'Dros $1 ($2)',
'nocontribs'          => "Heb ddod o hyd i newidiadau gyda'r maen prawf hwn.",
'uctop'               => '(cyfredol)',
'month'               => 'Cyfraniadau hyd at fis:',
'year'                => 'Cyfraniadau hyd at y flwyddyn:',

'sp-contributions-newbies'             => 'Dangos cyfraniadau gan gyfrifon newydd yn unig',
'sp-contributions-newbies-sub'         => 'Ar gyfer cyfrifon newydd',
'sp-contributions-newbies-title'       => 'Cyfraniadau defnyddwyr newydd',
'sp-contributions-blocklog'            => 'Lòg blocio',
'sp-contributions-deleted'             => 'cyfraniadau defnyddiwr i dudalennau dilëedig',
'sp-contributions-uploads'             => 'Llwythiadau',
'sp-contributions-logs'                => 'logiau',
'sp-contributions-talk'                => 'sgwrs',
'sp-contributions-userrights'          => 'rheoli galluoedd defnyddwyr',
'sp-contributions-blocked-notice'      => "Mae'r defnyddiwr hwn wedi ei atal rhag golygu ar hyn o bryd. Mae'r cofnod diweddaraf yn y lòg blocio i'w weld isod:",
'sp-contributions-blocked-notice-anon' => "Mae'r gallu i olygu o'r cyfeiriad IP hwn wedi ei atal ar hyn o bryd.
Mae'r cofnod diweddaraf yn y lòg blocio i'w weld isod:",
'sp-contributions-search'              => 'Chwilio am gyfraniadau',
'sp-contributions-username'            => 'Cyfeiriad IP neu enw defnyddiwr:',
'sp-contributions-toponly'             => 'Dangos y diwygiadau diweddaraf yn unig',
'sp-contributions-submit'              => 'Chwilier',

# What links here
'whatlinkshere'            => "Beth sy'n cysylltu yma",
'whatlinkshere-title'      => 'Tudalennau sy\'n cysylltu â "$1"',
'whatlinkshere-page'       => 'Tudalen:',
'linkshere'                => "Mae'r tudalennau isod yn cysylltu â '''[[:$1]]''':",
'nolinkshere'              => "Nid oes cyswllt ar unrhyw dudalen arall yn arwain at '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nid oes cyswllt ar unrhyw dudalen yn y parth dewisedig yn arwain at '''[[:$1]]'''.",
'isredirect'               => 'tudalen ail-gyfeirio',
'istemplate'               => 'cynhwysiad',
'isimage'                  => 'cyswllt at y ddelwedd',
'whatlinkshere-prev'       => '{{PLURAL:$1|cynt|cynt|$1 cynt|$1 cynt|$1 cynt|$1 cynt}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nesaf|nesaf|$1 nesaf|$1 nesaf|$1 nesaf|$1 nesaf}}',
'whatlinkshere-links'      => '← cysylltiadau',
'whatlinkshere-hideredirs' => '$1 ail-gyfeiriadau',
'whatlinkshere-hidetrans'  => '$1 trawsgynhwysion',
'whatlinkshere-hidelinks'  => '$1 cysylltau',
'whatlinkshere-hideimages' => '$1 cysylltau delweddau',
'whatlinkshere-filters'    => 'Hidlau',

# Block/unblock
'blockip'                         => "Blocio'r defnyddiwr",
'blockip-title'                   => "Blocio'r defnyddiwr",
'blockip-legend'                  => "Blocio'r defnyddiwr",
'blockiptext'                     => "Defnyddiwch y ffurflen isod i flocio cyfeiriad IP neu ddefnyddiwr rhag ysgrifennu i'r databas. Dylech chi ddim ond gwneud hyn er mwyn rhwystro fandaliaeth a chan ddilyn [[{{MediaWiki:Policy-url}}|polisi'r wici]]. Llenwch y rheswm am y bloc yn y blwch isod -- dywedwch pa dudalen sydd wedi cael ei fandaleiddio.",
'ipaddress'                       => 'Cyfeiriad IP:',
'ipadressorusername'              => 'Cyfeiriad IP neu enw defnyddiwr:',
'ipbexpiry'                       => 'Am gyfnod:',
'ipbreason'                       => 'Rheswm:',
'ipbreasonotherlist'              => 'Rheswm arall',
'ipbreason-dropdown'              => '*Rhesymau cyffredin dros flocio
** Gosod gwybodaeth anghywir
** Dileu cynnwys tudalennau
** Gosod cysylltiadau spam i wefannau eraill
** Gosod dwli ar dudalennau
** Ymddwyn yn fygythiol/tarfu
** Camddefnyddio cyfrifon niferus
** Enw defnyddiwr annerbyniol',
'ipbanononly'                     => 'Blocio defnyddwyr anhysbys yn unig',
'ipbcreateaccount'                => 'Atal y gallu i greu cyfrif',
'ipbemailban'                     => 'Atal y defnyddiwr rhag anfon e-bost',
'ipbenableautoblock'              => "Blocio'n awtomatig y cyfeiriad IP diwethaf y defnyddiodd y defnyddiwr hwn, ac unrhyw gyfeiriad IP arall y bydd yn ceisio defnyddio i olygu ohono.",
'ipbsubmit'                       => 'Blocier y defnyddiwr hwn',
'ipbother'                        => 'Cyfnod arall:',
'ipboptions'                      => 'o 2 awr:2 hours,o ddiwrnod:1 day,o 3 niwrnod:3 days,o wythnos:1 week,o bythefnos:2 weeks,o fis:1 month,o 3 mis:3 months,o 6 mis:6 months,o flwyddyn:1 year,amhenodol:infinite',
'ipbotheroption'                  => 'arall',
'ipbotherreason'                  => 'Rheswm arall:',
'ipbhidename'                     => "Cuddio'r enw defnyddiwr rhag ymddangos ar restri a golygiadau",
'ipbwatchuser'                    => 'Gwylio tudalen defnyddiwr a thudalen sgwrs y defnyddiwr hwn',
'ipballowusertalk'                => "Galluogi'r defnyddiwr hwn i olygu ei dudalen sgwrs ei hun tra bod bloc arno",
'ipb-change-block'                => "Ailflocio'r defnyddiwr hwn gyda'r gosodiadau hyn",
'badipaddress'                    => 'Cyfeiriad IP annilys.',
'blockipsuccesssub'               => 'Llwyddodd y rhwystr',
'blockipsuccesstext'              => 'Mae cyfeiriad IP [[Special:Contributions/$1|$1]] wedi cael ei flocio.
<br />Gwelwch [[Special:IPBlockList|restr y blociau IP]] er mwyn arolygu blociau.',
'ipb-edit-dropdown'               => "Golygu'r rhesymau dros flocio",
'ipb-unblock-addr'                => 'Datflocio $1',
'ipb-unblock'                     => 'Datflocio enw defnyddiwr neu cyfeiriad IP',
'ipb-blocklist'                   => 'Dangos y blociau cyfredol',
'ipb-blocklist-contribs'          => 'Cyfraniadau $1',
'unblockip'                       => 'Dadflocio defnyddiwr',
'unblockiptext'                   => "Defnyddiwch y ffurflen isod i ail-alluogi golygiadau gan ddefnyddiwr neu o gyfeiriad IP a fu gynt wedi'i flocio.",
'ipusubmit'                       => 'Dadflocier',
'unblocked'                       => 'Mae [[User:$1|$1]] wedi cael ei ddad-flocio',
'unblocked-id'                    => 'Tynnwyd y bloc $1',
'ipblocklist'                     => "Cyfeiriadau IP ac enwau defnyddwyr sydd wedi'u blocio",
'ipblocklist-legend'              => 'Dod o hyd i ddefnyddiwr sydd wedi ei flocio',
'ipblocklist-username'            => "Enw'r defnyddiwr neu ei gyfeiriad IP:",
'ipblocklist-sh-userblocks'       => '$1 blociau parhaus ar gyfrifon',
'ipblocklist-sh-tempblocks'       => '$1 blociau dros dro',
'ipblocklist-sh-addressblocks'    => '$1 blociau IP unigol',
'ipblocklist-submit'              => 'Chwilier',
'ipblocklist-localblock'          => 'Bloc lleol',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Bloc arall|Bloc arall|Blociau eraill|Blociau eraill|Blociau eraill|Blociau eraill}}',
'blocklistline'                   => '$1, $2 wedi blocio $3 ($4)',
'infiniteblock'                   => 'bloc parhaus',
'expiringblock'                   => 'yn dod i ben ar $1 am $2',
'anononlyblock'                   => 'ataliwyd dim ond pan nad yw wedi mewngofnodi',
'noautoblockblock'                => 'analluogwyd blocio awtomatig',
'createaccountblock'              => 'ataliwyd y gallu i greu cyfrif',
'emailblock'                      => 'rhwystrwyd e-bostio',
'blocklist-nousertalk'            => 'ni all olygu ei dudalen sgwrs ei hun',
'ipblocklist-empty'               => "Mae'r rhestr blociau'n wag.",
'ipblocklist-no-results'          => 'Nid yw cyfeiriad IP neu enw defnyddiwr yr ymholiad wedi ei flocio.',
'blocklink'                       => 'blocio',
'unblocklink'                     => 'dadflocio',
'change-blocklink'                => 'newid y bloc',
'contribslink'                    => 'cyfraniadau',
'autoblocker'                     => 'Rydych wedi cael eich rhwystro\'n awtomatig oherwydd i\'ch cyfeiriad IP gael ei ddefnyddio gan "[[User:$1|$1]]" yn ddiweddar. Dyma\'r rheswm a roddwyd dros rwystro $1: "$2".',
'blocklogpage'                    => 'Lòg blociau',
'blocklog-showlog'                => "Cafodd y defnyddiwr hwn ei flocio o'r blaen.
Dyma'r lòg blocio perthnasol:",
'blocklog-showsuppresslog'        => "Cafodd y defnyddiwr hwn ei flocio a'i guddio o'r blaen.
Dyma'r lòg cuddio perthnasol:",
'blocklogentry'                   => 'wedi rhwystro "[[$1]]" am gyfnod o $2 $3',
'reblock-logentry'                => 'wedi newid y gosodiadau blocio ar [[$1]], gan ddod i ben am $2 $3',
'blocklogtext'                    => "Dyma lòg o'r holl weithredoedd blocio a datflocio. Nid yw'r cyfeiriadau IP sydd wedi cael eu blocio'n awtomatig ar y rhestr. Gweler [[Special:IPBlockList|rhestr y blociau IP]] am restr y blociau a'r gwaharddiadau sydd yn weithredol ar hyn o bryd.",
'unblocklogentry'                 => 'wedi dadflocio $1',
'block-log-flags-anononly'        => 'defnyddwyr anhysbys yn unig',
'block-log-flags-nocreate'        => 'analluogwyd creu cyfrif',
'block-log-flags-noautoblock'     => 'analluogwyd blocio awtomatig',
'block-log-flags-noemail'         => 'analluogwyd e-bostio',
'block-log-flags-nousertalk'      => 'ni all olygu ei dudalen sgwrs ei hun',
'block-log-flags-angry-autoblock' => 'galluogwyd blocio awtomatig uwch',
'block-log-flags-hiddenname'      => 'cuddiwyd yr enw defnyddiwr',
'range_block_disabled'            => 'Ar hyn o bryd nid yw gweinyddwyr yn gallu blocio ystod o gyfeiriadau IP.',
'ipb_expiry_invalid'              => 'Amser terfynu yn annilys.',
'ipb_expiry_temp'                 => "Rhaid i floc ar ddefnyddiwr fod yn barhaus os am guddio'r enw.",
'ipb_hide_invalid'                => "Ddim yn gallu cuddio'r cyfrif hwn; efallai bod ganddo ormod o olygiadau.",
'ipb_already_blocked'             => 'Mae "$1" eisoes wedi ei flocio',
'ipb-needreblock'                 => "== Wedi blocio'n barod ==
Mae $1 wedi ei flocio'n barod. Ydych chi am newid y gosodiadau?",
'ipb-otherblocks-header'          => '{{PLURAL:$1|Bloc|Bloc|Blociau|Blociau|Blociau|Blociau}} eraill',
'ipb_cant_unblock'                => "Gwall: Ni chafwyd hyd i'r bloc a'r ID $1.
Hwyrach ei fod wedi ei ddad-flocio'n barod.",
'ipb_blocked_as_range'            => "Gwall: Nid yw'r IP $1 wedi ei blocio'n uniongyrchol ac felly ni ellir ei datflocio. Wedi dweud hynny, y mae'n rhan o'r amrediad $2 sydd wedi ei blocio; gellir datflocio'r amrediad.",
'ip_range_invalid'                => 'Dewis IP annilys.',
'ip_range_toolarge'               => 'Ni chaniateir blociau amrediad mwy na /$1.',
'blockme'                         => 'Blocier fi',
'proxyblocker'                    => 'Dirprwy-flociwr',
'proxyblocker-disabled'           => 'Analluogwyd y swyddogaeth hon.',
'proxyblockreason'                => "Mae eich cyfeiriad IP wedi'i flocio gan ei fod yn ddirprwy agored (open proxy). Cysylltwch â'ch gweinyddwr rhyngrwyd neu gymorth technegol er mwyn eu hysbysu am y broblem ddifrifol yma.",
'proxyblocksuccess'               => 'Wedi llwyddo.',
'sorbsreason'                     => 'Mae eich cyfeiriad IP wedi cael ei osod ymhlith y dirprwyon agored ar y Rhestr DNS Gwaharddedig a ddefnyddir gan {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Mae eich cyfeiriad IP wedi cael ei osod ymhlith y dirprwyon agored ar y Rhestr DNS Gwaharddedig a ddefnyddir gan {{SITENAME}}.
Ni allwch greu cyfrif.',
'cant-block-while-blocked'        => 'Ni allwch flocio defnyddwyr eraill tra bod bloc arnoch chithau.',
'cant-see-hidden-user'            => "Mae'r defnyddiwr yr ydych am ei flocio eisoes wedi ei flocio a'i guddio.
Gan nad yw'r gallu \"hideuser\" gennych, ni allwch weld y bloc ar y defnyddiwr na'i olygu.",
'ipbblocked'                      => 'Ni allwch flocio na dad-flocio defnyddwyr eraill, oherwydd eich bod chi eich hunan wedi eich blocio',
'ipbnounblockself'                => "Nid yw'r gallu gennych i ddad-flocio'ch hunan",

# Developer tools
'lockdb'              => "Cloi'r databas",
'unlockdb'            => "Datgloi'r databas",
'lockdbtext'          => "Bydd cloi'r databas yn golygu na all unrhyw ddefnyddiwr olygu tudalennau, newid eu dewisiadau, golygu eu rhestrau gwylio, na gwneud unrhywbeth arall sydd angen newid yn y databas. Cadarnhewch eich bod chi wir am wneud hyn, ac y byddwch yn ei ddatgloi unwaith mae'r gwaith cynnal a chadw ar ben.",
'unlockdbtext'        => "Bydd datgloi'r databas yn ail-alluogi unrhyw ddefnyddiwr i olygu tudalennau, newid eu dewisiadau, golygu eu rhestrau gwylio, neu i wneud unrhywbeth arall sydd angen newid yn y databas. Cadarnhewch eich bod chi wir am wneud hyn.",
'lockconfirm'         => "Ydw, rydw i wir am gloi'r databas.",
'unlockconfirm'       => "Ydw, rydw i wir am ddatgloi'r databas.",
'lockbtn'             => "Cloi'r databas",
'unlockbtn'           => "Datgloi'r databas",
'locknoconfirm'       => "Nid ydych wedi ticio'r blwch cadarnhad.",
'lockdbsuccesssub'    => "Wedi llwyddo cloi'r databas",
'unlockdbsuccesssub'  => "Databas wedi'i ddatgloi",
'lockdbsuccesstext'   => "Mae'r databas wedi'i gloi.<br />
Cofiwch [[Special:UnlockDB|ddatgloi'r]] databas pan fydd y gwaith cynnal ar ben.",
'unlockdbsuccesstext' => "Mae'r databas wedi'i ddatgloi.",
'lockfilenotwritable' => "Ni ellir ysgrifennu'r ffeil cloi'r databas.
Er mwyn cloi'r databas neu ei ddatgloi, mae'n rhaid i'r gweinydd gwe allu ysgrifennu'r ffeil.",
'databasenotlocked'   => "Nid yw'r databas ar glo.",

# Move page
'move-page'                    => 'Symud $1',
'move-page-legend'             => 'Symud tudalen',
'movepagetext'                 => "Wrth ddefnyddio'r ffurflen isod byddwch yn ail-enwi tudalen, gan symud ei hanes gyfan i'r enw newydd.
Bydd yr hen deitl yn troi'n dudalen ailgyfeirio i'r teitl newydd.
Gallwch ddewis bod y meddalwedd yn cywiro tudalennau ailgyfeirio oedd yn arwain at yr hen deitl yn awtomatig.
Os nad ydych yn dewis hyn, yna byddwch gystal â thrwsio [[Special:DoubleRedirects|ailgyfeiriadau dwbl]] ac [[Special:BrokenRedirects|ailgyfeiriadau tor]] eich hunan.
Eich cyfrifoldeb chi yw sicrhau bod cysylltiadau wici'n dal i arwain at y man iawn!

Sylwch '''na fydd''' y dudalen yn symud os oes yna dudalen o'r enw newydd ar gael yn barod ar y databas (heblaw ei bod hi'n wag neu'n ailgyfeiriad heb unrhyw hanes golygu).
Felly, os y gwnewch gamgymeriad wrth ail-enwi tudalen dylai fod yn bosibl ei hail-enwi eto ar unwaith wrth yr enw gwreiddiol.
Hefyd, mae'n amhosibl ysgrifennu dros ben tudalen sydd ar gael yn barod.

'''Dalier Sylw!'''
Gall hwn fod yn newid sydyn a llym i dudalen boblogaidd;
gwnewch yn siwr eich bod chi'n deall y canlyniadau cyn mynd ati.",
'movepagetext-noredirectfixer' => "Wrth ddefnyddio'r ffurflen isod byddwch yn ail-enwi tudalen, gan symud ei hanes gyfan i'r enw newydd.
Bydd yr hen deitl yn troi'n dudalen ailgyfeirio i'r teitl newydd.
Byddwch gystal â thrwsio [[Special:DoubleRedirects|ailgyfeiriadau dwbl]] ac [[Special:BrokenRedirects|ailgyfeiriadau tor]].
Eich cyfrifoldeb chi yw sicrhau bod cysylltiadau wici'n dal i arwain at y man iawn.

Sylwch '''na fydd''' y dudalen yn symud os oes yna dudalen o'r enw newydd ar gael yn barod (heblaw ei bod hi'n wag neu'n ailgyfeiriad heb unrhyw hanes golygu).
Felly, os y gwnewch gamgymeriad wrth ail-enwi tudalen dylai fod yn bosibl ei hail-enwi eto ar unwaith wrth yr enw gwreiddiol. 
Hefyd, mae'n amhosibl ysgrifennu dros ben tudalen sydd yn bodoli'n barod.

'''Dalier Sylw!'''
Gall hwn fod yn newid sydyn a llym i dudalen boblogaidd;
gwnewch yn siwr eich bod chi'n deall y canlyniadau cyn mynd ati.",
'movepagetalktext'             => "Bydd y dudalen sgwrs yn symud gyda'r dudalen hon '''onibai:'''
*bod tudalen sgwrs wrth yr enw newydd yn bodoli'n barod
*bod y blwch isod heb ei farcio.

Os felly, gallwch symud y dudalen sgwrs neu ei gyfuno ar ôl symud y dudalen ei hun.",
'movearticle'                  => 'Symud y dudalen:',
'moveuserpage-warning'         => "'''Sylwer:''' Yr ydych ar fin symud tudalen defnyddiwr. Sylwch mai'r dudalen yn unig a gaiff ei symud ac ''na fydd'' y defnyddiwr yn cael ei ail-enwi.",
'movenologin'                  => 'Nid ydych wedi mewngofnodi',
'movenologintext'              => "Mae'n rhaid bod yn ddefnyddiwr cofrestredig a'ch bod wedi [[Special:UserLogin|mewngofnodi]] cyn medru symud tudalen.",
'movenotallowed'               => 'Nid oes caniatâd gennych i symud tudalennau.',
'movenotallowedfile'           => "Nid yw'r gallu ganddoch i symud ffeiliau.",
'cant-move-user-page'          => "Nid yw'r gallu ganddoch i symud tudalennau defnyddwyr (heblaw am isdudalennau).",
'cant-move-to-user-page'       => "Nid yw'r gallu ganddoch i symud tudalen i dudalen defnyddiwr (heblaw am i isdudalen defnyddiwr).",
'newtitle'                     => "I'r teitl newydd:",
'move-watch'                   => 'Gwylier y dudalen hon',
'movepagebtn'                  => 'Symud y dudalen',
'pagemovedsub'                 => 'Y symud wedi llwyddo',
'movepage-moved'               => '\'\'\'Symudwyd y dudalen "$1" i "$2"\'\'\'',
'movepage-moved-redirect'      => 'Gosodwyd ail-gyfeiriad.',
'movepage-moved-noredirect'    => 'Ni osodwyd tudalen ailgyfeirio.',
'articleexists'                => "Mae tudalen gyda'r darpar enw yn bodoli'n barod, neu mae eich darpar enw yn annilys.
Dewiswch enw arall os gwelwch yn dda.",
'cantmove-titleprotected'      => "Ni allwch symud tudalen i'r lleoliad hwn, oherwydd bod y teitl arfaethedig wedi ei ddiogelu rhag cael ei ddefnyddio.",
'talkexists'                   => "'''Mae'r dudalen wedi'i symud yn llwyddiannus, ond nid oedd hi'n bosibl symud y dudalen sgwrs oherwydd bod yna dudalen sgwrs gyda'r enw newydd yn bodoli'n barod. Cyfunwch y ddwy dudalen, os gwelwch yn dda.'''",
'movedto'                      => 'symud i',
'movetalk'                     => 'Symud y dudalen sgwrs hefyd',
'move-subpages'                => 'Symud unrhyw is-dudalennau (hyd at $1)',
'move-talk-subpages'           => "Symud is-dudalennau'r dudalen sgwrs (hyd at $1)",
'movepage-page-exists'         => "Mae'r dudalen $1 eisoes ar gael ac ni ellir ysgrifennu drosto yn awtomatig.",
'movepage-page-moved'          => 'Symudwyd y dudalen $1 i $2.',
'movepage-page-unmoved'        => 'Ni ellid symud y dudalen $1 i $2.',
'movepage-max-pages'           => 'Symudwyd yr uchafswm o $1 {{PLURAL:$1|tudalen|dudalen|dudalen|tudalen|thudalen|tudalen}} y gellir eu symud yn awtomatig.',
'1movedto2'                    => 'wedi symud [[$1]] i [[$2]]',
'1movedto2_redir'              => 'Wedi symud [[$1]] i [[$2]] trwy ailgyfeiriad.',
'move-redirect-suppressed'     => 'ataliwyd ailgyfeirio',
'movelogpage'                  => 'Lòg symud tudalennau',
'movelogpagetext'              => "Isod mae rhestr y tudalennau sydd wedi'u symud",
'movesubpage'                  => '{{PLURAL:$1|Isdudalen|Isdudalen|Isdudalennau|Isdudalennau|Isdudalennau|Isdudalennau}}',
'movesubpagetext'              => 'Mae gan y dudalen hon $1 {{PLURAL:$1|isdudalen|isdudalen|isdudalen|isdudalen|o isdudalennau}} a ddangosir isod.',
'movenosubpage'                => "Nid oes isdudalennau i gael i'r dudalen hon.",
'movereason'                   => 'Rheswm:',
'revertmove'                   => 'symud nôl',
'delete_and_move'              => 'Dileu a symud',
'delete_and_move_text'         => "==Angen dileu==

Mae'r erthygl \"[[:\$1]]\" yn bodoli'n barod. Ydych chi am ddileu'r erthygl er mwyn cwblhau'r symudiad?",
'delete_and_move_confirm'      => "Ie, dileu'r dudalen",
'delete_and_move_reason'       => "Wedi'i dileu er mwyn symud tudalen arall yn ei lle.",
'selfmove'                     => "Mae'r teitlau hen a newydd yn union yr un peth;
nid yw'n bosib cyflawnu'r symud.",
'immobile-source-namespace'    => 'Ni ellir symud tudalennau yn y parth "$1".',
'immobile-target-namespace'    => 'Ni ellir symud tudalennau i\'r parth "$1".',
'immobile-target-namespace-iw' => 'Nid yw cyswllt rhyngwici yn nod dilys wrth symud tudalen.',
'immobile-source-page'         => 'Ni ellir symud y dudalen hon.',
'immobile-target-page'         => "Ddim yn gallu symud i'r teitl newydd hwn.",
'imagenocrossnamespace'        => 'Ni ellir symud ffeil i barth arall',
'nonfile-cannot-move-to-file'  => 'Ni ellir symud unrhywbeth heblaw ffeil i barth y ffeiliau',
'imagetypemismatch'            => "Nid yw'r estyniad ffeil newydd yn cyfateb i'r math o ffeil",
'imageinvalidfilename'         => "Mae enw'r ffeil darged yn annilys",
'fix-double-redirects'         => "Yn diwygio unrhyw ailgyfeiriadau sy'n cysylltu i'r teitl gwreiddiol",
'move-leave-redirect'          => "Creu tudalen ail-gyfeirio â'r teitl gwreiddiol",
'protectedpagemovewarning'     => "'''Sylwer:''' Clowyd y dudalen ac felly dim ond defnyddwyr a galluoedd gweinyddu ganddynt sy'n gallu ei symud.
Dyma'r cofnod lòg diweddaraf, er gwybodaeth:",
'semiprotectedpagemovewarning' => "'''Sylwer:''' Clowyd y dudalen ac felly dim ond defnyddwyr mewngofnodedig sy'n gallu ei symud.
Dyma'r cofnod lòg diweddaraf, er gwybodaeth:",
'move-over-sharedrepo'         => "== Y ffeil ar gael ==
Mae'r ffeil [[:$1]] ar gael mewn storfa gyfrannol. Pe byddech yn symud y ffeil i'r teitl hwn, yna byddai'r ffeil o'r storfa gyfrannol yn cael ei disodli.",
'file-exists-sharedrepo'       => "Mae'r enw y dewisoch ar y ffeil yn cael ei ddefnyddio'n barod ar storfa gyfrannol.
Dewiswch enw arall os gwelwch yn dda.",

# Export
'export'            => 'Allforio tudalennau',
'exporttext'        => "Gallwch allforio testun a hanes golygu tudalen penodol neu set o dudalennau wedi'u lapio mewn côd XML. Gall hwn wedyn gael ei fewnforio i wici arall sy'n defnyddio meddalwedd MediaWiki, trwy ddefnyddio'r [[Special:Import|dudalen mewnforio]].

I allforio tudalennau, teipiwch y teitlau yn y bocs testun isod, bobi linell i'r teitlau; a dewis p'un ai ydych chi eisiau'r diwygiad presennol a'r holl fersiynnau blaenorol, gyda hanes y dudalen; ynteu a ydych am y diwygiad presennol a'r wybodaeth am y golygiad diweddaraf yn unig.

Yn achos yr ail ddewis, mae modd defnyddio cyswllt, e.e. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] ar gyfer y dudalen \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Cynnwys y diwygiad diweddaraf yn unig, nid yr hanes llawn',
'exportnohistory'   => "----
'''Sylwer:''' er mwyn peidio â gor-lwytho'r gweinydd, analluogwyd allforio hanes llawn y tudalennau.",
'export-submit'     => 'Allforier',
'export-addcattext' => "Ychwanegu tudalennau i'w hallforio o'r categori:",
'export-addcat'     => 'Ychwaneger',
'export-addnstext'  => "Ychwanegu tudalennau o'r parth:",
'export-addns'      => 'Ychwaneger',
'export-download'   => 'Cynnig rhoi ar gadw ar ffurf ffeil',
'export-templates'  => 'Cynnwys nodiadau',
'export-pagelinks'  => 'Cynhwyser tudalennau cysylltiedig hyd at ddyfnder o:',

# Namespace 8 related
'allmessages'                   => 'Pob neges',
'allmessagesname'               => 'Enw',
'allmessagesdefault'            => 'Testun rhagosodedig',
'allmessagescurrent'            => 'Testun cyfredol',
'allmessagestext'               => "Dyma restr o'r holl negeseuon yn y parth MediaWici.
Os ydych am gyfrannu at y gwaith o gyfieithu ar gyfer holl prosiectau MediaWiki ar y cyd, mae croeso i chi ymweld â [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] a [http://translatewiki.net translatewiki.net].",
'allmessagesnotsupportedDB'     => "Nid yw '''{{ns:special}}:PobNeges''' yn cael ei gynnal gan fod '''\$wgUseDatabaseMessages''' wedi ei ddiffodd.",
'allmessages-filter-legend'     => 'Hidl',
'allmessages-filter'            => 'Hidlo yn ôl eu cyflwr addasu:',
'allmessages-filter-unmodified' => 'Heb eu haddasu',
'allmessages-filter-all'        => 'Oll',
'allmessages-filter-modified'   => 'Wedi eu haddasu',
'allmessages-prefix'            => 'Hidlo yn ôl rhagddodiad:',
'allmessages-language'          => 'Iaith:',
'allmessages-filter-submit'     => 'Eler',

# Thumbnails
'thumbnail-more'           => 'Chwyddo',
'filemissing'              => 'Ffeil yn eisiau',
'thumbnail_error'          => "Cafwyd gwall wrth greu'r mân-lun: $1",
'djvu_page_error'          => 'Y dudalen DjVu allan o amrediad',
'djvu_no_xml'              => 'Ddim yn gallu mofyn XML ar gyfer ffeil DjVu',
'thumbnail_invalid_params' => 'Paramedrau maint mân-lun annilys',
'thumbnail_dest_directory' => "Methwyd â chreu'r cyfeiriadur cyrchfan",
'thumbnail_image-type'     => "Nid yw'r math hwn o ddelwedd yn cael ei gynnal",
'thumbnail_gd-library'     => 'Mae ffurfwedd y llyfrgell GD yn anghyflawn: y ffwythiant $1 yn eisiau',
'thumbnail_image-missing'  => "Mae'n debyg bod y ffeil yn eisiau: $1",

# Special:Import
'import'                     => 'Mewnforio tudalennau',
'importinterwiki'            => 'Mewnforiad traws-wici',
'import-interwiki-text'      => "Dewiswch wici a thudalen i'w mewnforio.
Fe gedwir dyddiadau ac enwau'r golygwyr ar gyfer y diwygiadau i'r dudalen.
Mae cofnod o bob weithred o fewnforio i'w gweld ar y [[Special:Log/import|lòg mewnforio]].",
'import-interwiki-source'    => 'Wici/tudalen y gwreiddiol:',
'import-interwiki-history'   => 'Copïer yr holl fersiynau yn hanes y dudalen hon',
'import-interwiki-templates' => 'Cynhwyser pob nodyn',
'import-interwiki-submit'    => 'Mewnforio',
'import-interwiki-namespace' => 'Parth y cyrchir ato:',
'import-upload-filename'     => "Enw'r ffeil:",
'import-comment'             => 'Sylw:',
'importtext'                 => "Os gwelwch yn dda, allforiwch y ffeil o'r wici gwreiddiol gan ddefnyddio'r nodwedd <b>Special:Export</b>, cadwch hi i'ch disg, ac uwchlwythwch hi fan hyn.",
'importstart'                => "Wrthi'n mewnforio...",
'import-revision-count'      => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'importnopages'              => "Dim tudalennau i gael i'w mewnforio.",
'imported-log-entries'       => 'Mewnforiwyd $1 {{PLURAL:$1|cofnod|cofnod|gofnod|cofnod|cofnod|o gofnodion}} lòg.',
'importfailed'               => 'Y mewnforio wedi methu: <nowiki>$1</nowiki>',
'importunknownsource'        => "Y gwreiddiol i'w fewnforio o fath anhysbys",
'importcantopen'             => "Ni ellid agor y ffeil i'w fewnforio",
'importbadinterwiki'         => 'Cyswllt rhyngwici gwallus',
'importnotext'               => 'Gwag, neu heb destun',
'importsuccess'              => 'Y mewnforio wedi llwyddo!',
'importhistoryconflict'      => "Mae adolygiadau yn yr hanes yn croesgyffwrdd (efallai eich bod chi wedi mewnforio'r dudalen o'r blaen)",
'importnosources'            => "Ni ddiffiniwyd unrhyw ffynonellau mewnforio traws-wici, ac mae uwchlwytho hanesion yn uniongyrchol wedi'i analluogi.",
'importnofile'               => 'Ni uwchlwythwyd unrhyw ffeil mewnforio.',
'importuploaderrorsize'      => "Methodd yr uwchlwytho.
Mae'r ffeil yn fwy na'r maint y gellir ei uwchlwytho.",
'importuploaderrorpartial'   => "Methodd yr uwchlwytho.
Dim ond rhan o'r ffeil sydd wedi ei huwchlwytho.",
'importuploaderrortemp'      => 'Methodd yr uwchlwytho.
Mae ffolder dros dro yn eisiau.',
'import-parse-failure'       => "Wedi methu dosrannu'r mewnforiad XML",
'import-noarticle'           => "Ni chafwyd tudalen i'w mewnforio!",
'import-nonewrevisions'      => "Mae'r holl ddiwygiadau eisoes wedi eu mewnforio.",
'xml-error-string'           => '$1 ar linell $2, col $3 (beit $4): $5',
'import-upload'              => 'Uwchlwytho data XML',
'import-token-mismatch'      => "Collwyd data'r sesiwn. Ceisiwch eto.",
'import-invalid-interwiki'   => "Ni ellir uwchlwytho o'r wici dewisedig.",

# Import log
'importlogpage'                    => 'Lòg mewnforio',
'importlogpagetext'                => "Cofnodion mewnforio tudalennau ynghyd â'u hanes golygu oddi ar wicïau eraill, gan weinyddwyr.",
'import-logentry-upload'           => 'wedi mewnforio [[$1]] trwy uwchlwytho ffeil',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'import-logentry-interwiki'        => 'wedi symud $1 (traws-wici)',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}} o $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Eich tudalen defnyddiwr',
'tooltip-pt-anonuserpage'         => 'Y tudalen defnyddiwr ar gyfer y cyfeiriad IP yr ydych yn ei ddefnyddio wrth olygu',
'tooltip-pt-mytalk'               => 'Eich tudalen sgwrs',
'tooltip-pt-anontalk'             => "Sgwrs ynglŷn â golygiadau o'r cyfeiriad IP hwn",
'tooltip-pt-preferences'          => 'Eich dewisiadau',
'tooltip-pt-watchlist'            => 'Rydych yn dilyn hynt y tudalennau sydd ar y rhestr hon',
'tooltip-pt-mycontris'            => 'Rhestr eich cyfraniadau yn nhrefn amser',
'tooltip-pt-login'                => "Fe'ch anogir i fewngofnodi, er nad oes rhaid gwneud.",
'tooltip-pt-anonlogin'            => "Fe'ch anogir i fewngofnodi, er nad oes rhaid gwneud.",
'tooltip-pt-logout'               => 'Allgofnodi',
'tooltip-ca-talk'                 => 'Sgwrsio am y dudalen',
'tooltip-ca-edit'                 => "Gallwch olygu'r dudalen hon. Da o beth fyddai defnyddio'r botwm 'Dangos rhagolwg' cyn rhoi ar gadw.",
'tooltip-ca-addsection'           => 'Ychwanegu adran newydd',
'tooltip-ca-viewsource'           => "Mae'r dudalen hon wedi'i diogelu. Gallwch weld y côd yma.",
'tooltip-ca-history'              => "Fersiynau cynt o'r dudalen hon.",
'tooltip-ca-protect'              => "Diogelu'r dudalen hon",
'tooltip-ca-unprotect'            => "Dad-ddiogelu'r dudalen hon",
'tooltip-ca-delete'               => "Dileu'r dudalen hon",
'tooltip-ca-undelete'             => "Adfer y golygiadau i'r dudalen hon a wnaethpwyd cyn ei dileu",
'tooltip-ca-move'                 => 'Symud y dudalen hon',
'tooltip-ca-watch'                => "Ychwanegu'r dudalen hon at eich rhestr wylio",
'tooltip-ca-unwatch'              => "Tynnu'r dudalen oddi ar eich rhestr wylio",
'tooltip-search'                  => 'Chwilio {{SITENAME}}',
'tooltip-search-go'               => "Mynd i'r dudalen â'r union deitl hwn, os oes un",
'tooltip-search-fulltext'         => 'Chwilio am y testun hwn',
'tooltip-p-logo'                  => 'Yr Hafan',
'tooltip-n-mainpage'              => "Ymweld â'r Hafan",
'tooltip-n-mainpage-description'  => 'At yr Hafan',
'tooltip-n-portal'                => "Pethau i'w gwneud, adnoddau a thudalennau'r gymuned",
'tooltip-n-currentevents'         => 'Gwybodaeth yn gysylltiedig â materion cyfoes',
'tooltip-n-recentchanges'         => 'Rhestr y newidiadau diweddar ar y wici.',
'tooltip-n-randompage'            => 'Dewiswch dudalen ar hap',
'tooltip-n-help'                  => 'Tudalennau cymorth',
'tooltip-t-whatlinkshere'         => "Rhestr o bob tudalen sy'n cysylltu â hon",
'tooltip-t-recentchangeslinked'   => 'Newidiadau diweddar i dudalennau sydd yn cysylltu â hon',
'tooltip-feed-rss'                => 'Porthiant RSS ar gyfer y dudalen hon',
'tooltip-feed-atom'               => 'Porthiant atom ar gyfer y dudalen hon',
'tooltip-t-contributions'         => "Gwelwch restr o gyfraniadau'r defnyddiwr hwn",
'tooltip-t-emailuser'             => 'Anfonwch e-bost at y defnyddiwr hwn',
'tooltip-t-upload'                => 'Uwchlwythwch ffeil delwedd, sain, fideo, ayb',
'tooltip-t-specialpages'          => "Rhestr o'r holl dudalennau arbennig",
'tooltip-t-print'                 => "Cynhyrchwch fersiwn o'r dudalen yn barod at ei hargraffu",
'tooltip-t-permalink'             => "Ail-lwytho'r dudalen fel bod modd gweld y cyfeiriad URL llawn a chreu cyswllt parhaol i'r fersiwn hwn o'r dudalen",
'tooltip-ca-nstab-main'           => 'Gweld y dudalen bwnc',
'tooltip-ca-nstab-user'           => 'Gweld tudalen y defnyddiwr',
'tooltip-ca-nstab-media'          => 'Gweld y dudalen gyfrwng',
'tooltip-ca-nstab-special'        => "Mae hwn yn dudalen arbennig; ni allwch olygu'r dudalen ei hun",
'tooltip-ca-nstab-project'        => 'Gweld tudalen y wici',
'tooltip-ca-nstab-image'          => 'Gweld tudalen y ffeil',
'tooltip-ca-nstab-mediawiki'      => 'Gweld neges y system',
'tooltip-ca-nstab-template'       => 'Dangos y nodyn',
'tooltip-ca-nstab-help'           => 'Gweld y dudalen gymorth',
'tooltip-ca-nstab-category'       => 'Dangos tudalen y categori',
'tooltip-minoredit'               => 'Marciwch hwn yn olygiad bychan.',
'tooltip-save'                    => 'Cadwch eich newidiadau',
'tooltip-preview'                 => "Dangos rhagolwg o'r newidiadau; defnyddiwch cyn cadw.",
'tooltip-diff'                    => "Dangos y newidiadau rydych chi wedi gwneud i'r testun.",
'tooltip-compareselectedversions' => 'Cymharwch y fersiynau detholedig.',
'tooltip-watch'                   => "Ychwanegu'r dudalen hon at eich rhestr wylio",
'tooltip-recreate'                => "Ail-greu'r dudalen serch iddi gael ei dileu",
'tooltip-upload'                  => 'Dechrau uwchlwytho',
'tooltip-rollback'                => "Yn troi golygiad(au) y defnyddiwr diwethaf i'r dudalen hon yn ôl gydag un clic.",
'tooltip-undo'                    => 'Mae "dadwneud" yn troi\'r golygiad hwn yn ôl ac yn dangos rhagolwg o\'r golygiad adferedig.
Gellir ychwanegu rheswm dros y dadwneud yn y crynodeb.',
'tooltip-preferences-save'        => "Rhoi'r dewisiadau ar gadw",
'tooltip-summary'                 => 'Rhowch grynodeb byr',

# Metadata
'nodublincore'      => "Mae metadata RDF 'Dublin Core' wedi cael ei analluogi ar y gwasanaethwr hwn.",
'nocreativecommons' => "Mae metadata RDF 'Creative Commons' wedi'i analluogi ar y gwasanaethwr hwn.",
'notacceptable'     => "Dydy gweinydd y wici ddim yn medru rhoi'r data mewn fformat darllenadwy i'ch cleient.",

# Attribution
'anonymous'        => 'chan {{PLURAL:$1|defnyddiwr|ddefnyddiwr|ddefnyddwyr|ddefnyddwyr|ddefnyddwyr|ddefnyddwyr}} anhysbys {{SITENAME}}',
'siteuser'         => 'y defnyddiwr {{SITENAME}} $1',
'anonuser'         => 'Defnyddiwr {{SITENAME}} anhysbys $1',
'lastmodifiedatby' => 'Newidiwyd y dudalen hon ddiwethaf am $2, $1 gan $3.',
'othercontribs'    => 'Yn seiliedig ar waith gan $1.',
'others'           => 'eraill',
'siteusers'        => 'y {{PLURAL:$2|defnyddiwr|defnyddiwr|defnyddwyr|defnyddwyr|defnyddwyr|defnyddwyr}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|defnyddiwr|defnyddiwr|defnyddwyr|defnyddwyr|defnyddwyr|defnyddwyr}} {{SITENAME}} anhysbys $1',
'creditspage'      => "Cydnabyddiaethau'r dudalen",
'nocredits'        => "Does dim cydnabyddiaethau i'r dudalen hon.",

# Spam protection
'spamprotectiontitle' => 'Hidlydd amddiffyn rhag sbam',
'spamprotectiontext'  => 'Ataliwyd y dudalen rhag ei rhoi ar gadw gan yr hidlydd sbam.
Achos hyn yn fwy na thebyg yw presenoldeb cysylltiad i wefan ar y rhestr gwaharddedig.',
'spamprotectionmatch' => "Dyma'r testun gyneuodd ein hidlydd amddiffyn rhag sbam: $1",
'spambot_username'    => 'Teclyn clirio sbam MediaWiki',
'spam_reverting'      => "Yn troi nôl i'r diwygiad diweddaraf sydd ddim yn cynnwys cysylltiadau i $1",
'spam_blanking'       => 'Roedd cysylltiadau i $1 gan bob golygiad, yn blancio',

# Info page
'infosubtitle'   => 'Gwybodaeth am y dudalen',
'numedits'       => "Nifer y golygiadau (i'r dudalen): $1",
'numtalkedits'   => "Nifer y golygiadau (i'r dudalen sgwrs): $1",
'numwatchers'    => 'Nifer y gwylwyr: $1',
'numauthors'     => 'Nifer y gwahanol awduron a gyfrannodd at y dudalen: $1',
'numtalkauthors' => "Nifer yr awduron (o'r dudalen sgwrs): $1",

# Skin names
'skinname-standard'    => 'Safonol',
'skinname-nostalgia'   => 'Hiraeth',
'skinname-cologneblue' => 'Glas Cwlen',

# Math options
'mw_math_png'    => 'Arddangos symbolau mathemateg fel delwedd PNG bob amser',
'mw_math_simple' => 'HTML os yn syml iawn, PNG fel arall',
'mw_math_html'   => 'HTML os yn bosib, PNG fel arall',
'mw_math_source' => 'Gadewch fel côd TeX (ar gyfer porwyr testun)',
'mw_math_modern' => 'Argymelledig ar gyfer porwyr modern',
'mw_math_mathml' => 'MathML os yn bosib (arbrofol)',

# Math errors
'math_failure'          => 'Wedi methu dosrannu',
'math_unknown_error'    => 'gwall anhysbys',
'math_unknown_function' => 'ffwythiant anhysbys',
'math_lexing_error'     => 'gwall lecsio',
'math_syntax_error'     => 'gwall cystrawen',
'math_image_error'      => "Trosiad PNG wedi methu; gwiriwch fod latex a dvips (neu dvips + gs + convert) wedi'u gosod yn gywir cyn trosi.",
'math_bad_tmpdir'       => 'Yn methu creu cyfeiriadur mathemateg dros dro, nac ysgrifennu iddo',
'math_bad_output'       => 'Yn methu creu cyfeiriadur allbwn mathemateg nac ysgrifennu iddo',
'math_notexvc'          => 'Rhaglen texvc yn eisiau; gwelwch math/README er mwyn ei chyflunio.',

# Patrolling
'markaspatrolleddiff'                 => 'Marcio ei bod wedi derbyn ymweliad patrôl',
'markaspatrolledtext'                 => 'Marcio bod y dudalen wedi derbyn ymweliad patrôl',
'markedaspatrolled'                   => 'Gosodwyd marc ei bod wedi derbyn ymweliad patrôl',
'markedaspatrolledtext'               => 'Wedi gosod marc bod y golygiad dewisedig o [[:$1]] wedi derbyn ymweliad patrôl.',
'rcpatroldisabled'                    => "Patrol y Newidiadau Diweddar wedi'i analluogi",
'rcpatroldisabledtext'                => 'Analluogwyd y nodwedd Patrol y Newidiadau Diweddar.',
'markedaspatrollederror'              => 'Ni ellir gosod marc ymweliad patrôl',
'markedaspatrollederrortext'          => "Rhaid nodi'r union olygiad sydd angen marc ymweliad patrôl.",
'markedaspatrollederror-noautopatrol' => "Ni chaniateir i chi farcio'ch newidiadau eich hunan fel rhai derbyniol.",

# Patrol log
'patrol-log-page'      => 'Lòg patrolio',
'patrol-log-header'    => "Mae'r lòg hwn yn dangos y golygiadau sydd wedi derbyn ymweliad patrôl.",
'patrol-log-line'      => 'wedi marcio bod $1 o $2 wedi derbyn ymweliad patrôl $3',
'patrol-log-auto'      => '(awtomatig)',
'patrol-log-diff'      => 'golygiad $1',
'log-show-hide-patrol' => '$1 lòg patrolio',

# Image deletion
'deletedrevision'                 => 'Wedi dileu hen ddiwygiad $1.',
'filedeleteerror-short'           => "Gwall wrth ddileu'r ffeil: $1",
'filedeleteerror-long'            => "Cafwyd gwallau wrth ddileu'r ffeil:

$1",
'filedelete-missing'              => 'Ni ellir dileu\'r ffeil "$1" gan nad yw\'n bodoli.',
'filedelete-old-unregistered'     => 'Nid yw\'r diwygiad "$1" o\'r ffeil yn y databas.',
'filedelete-current-unregistered' => 'Nid yw\'r ffeil "$1" yn y databas.',
'filedelete-archive-read-only'    => 'Nid oes modd i\'r gweweinydd ysgrifennu ar y cyfeiriadur archif "$1".',

# Browsing diffs
'previousdiff' => '← Y fersiwn gynt',
'nextdiff'     => 'Y fersiwn dilynol →',

# Media information
'mediawarning'         => "'''Rhybudd''': Gallasai'r math hwn o ffeil gynnwys côd maleisus.
Mae'n bosib y bydd eich cyfrifiadur yn cael ei danseilio wrth ddefnyddio'r ffeil.",
'imagemaxsize'         => "Maint mwyaf y delweddau:<br />''(ar y tudalennau disgrifiad)''",
'thumbsize'            => 'Maint mân-lun :',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|tudalen|dudalen|dudalen|tudalen|thudalen|tudalen}}',
'file-info'            => 'maint y ffeil: $1, ffurf MIME: $2',
'file-info-size'       => '$1 × $2 picsel, maint y ffeil: $3, ffurf MIME: $4',
'file-nohires'         => '<small>Wedi ei chwyddo hyd yr eithaf.</small>',
'svg-long-desc'        => 'Ffeil SVG, maint mewn enw $1 × $2 picsel, maint y ffeil: $3',
'show-big-image'       => 'Maint llawn',
'show-big-image-thumb' => '<small>Maint y rhagolwg: $1 × $2 picsel</small>',
'file-info-gif-looped' => 'dolennog',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ffrâm}}',
'file-info-png-looped' => 'dolennog',
'file-info-png-repeat' => "wedi'i chwarae {{PLURAL:$1||unwaith|ddwywaith|deirgwaith|$1 gwaith|$1 gwaith}}",
'file-info-png-frames' => '$1 {{PLURAL:$1|ffrâm}}',

# Special:NewFiles
'newimages'             => 'Oriel y ffeiliau newydd',
'imagelisttext'         => "Isod mae rhestr {{PLURAL:$1|gwag o ffeiliau|o '''$1''' ffeil|o '''$1''' ffeil wedi'u trefnu $2|o '''$1''' ffeil wedi'u trefnu $2|o '''$1''' o ffeiliau wedi'u trefnu $2|o '''$1''' o ffeiliau wedi'u trefnu $2|}}.",
'newimages-summary'     => "Mae'r dudalen arbennig hon yn dangos y ffeiliau a uwchlwythwyd yn ddiweddar.",
'newimages-legend'      => 'Hidlo',
'newimages-label'       => "Enw'r ffeil (neu ran ohono):",
'showhidebots'          => '($1 botiau)',
'noimages'              => "Does dim byd i'w weld.",
'ilsubmit'              => 'Chwilio',
'bydate'                => 'yn ôl dyddiad',
'sp-newimages-showfrom' => "Dangos ffeiliau sy'n newydd ers: $2, $1",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'e',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'a',

# Bad image list
'bad_image_list' => "Dyma'r fformat:

Dim ond eitemau mewn rhestr (llinellau'n dechrau a *) gaiff eu hystyried.
Rhaid i'r cyswllt cyntaf ar linell fod yn gyswllt at ffeil wallus.
Mae unrhyw gysylltau eraill ar yr un llinell yn cael eu trin fel eithriadau, h.y. tudalennau lle mae'r ffeil yn gallu ymddangos.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Mae'r ffeil hon yn cynnwys gwybodaeth ychwanegol, sydd mwy na thebyg wedi dod o'r camera digidol neu'r sganiwr a ddefnyddiwyd i greu'r ffeil neu ei digido. Os yw'r ffeil wedi ei cael ei newid ers ei chreu efallai nad yw'r manylion hyn yn dal i fod yn gywir.",
'metadata-expand'   => 'Dangos manylion estynedig',
'metadata-collapse' => 'Cuddio manylion estynedig',
'metadata-fields'   => "Pan fod tabl y metadata wedi'i grebachu fe ddangosir y meysydd metadata EXIF a restrir yn y neges hwn ar dudalen wybodaeth y ddelwedd.
Cuddir y meysydd eraill trwy ragosodiad.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Lled',
'exif-imagelength'                 => 'Uchder',
'exif-bitspersample'               => 'Nifer y didau i bob cydran',
'exif-compression'                 => 'Cynllun cywasgu',
'exif-photometricinterpretation'   => 'Cyfansoddiad picseli',
'exif-orientation'                 => 'Gogwydd',
'exif-samplesperpixel'             => 'Nifer y cydrannau',
'exif-planarconfiguration'         => 'Trefn y data',
'exif-ycbcrsubsampling'            => 'Cymhareb is-samplo Y i C',
'exif-ycbcrpositioning'            => 'Lleoli Y a C',
'exif-xresolution'                 => 'Datrysiad llorweddol',
'exif-yresolution'                 => 'Datrysiad fertigol',
'exif-resolutionunit'              => 'Datrysiad uned X a Y',
'exif-stripoffsets'                => "Lleoliad data'r ddelwedd",
'exif-rowsperstrip'                => 'Nifer y rhesi i bob stribed',
'exif-stripbytecounts'             => 'Nifer y beitiau i bob stribed cywasgedig',
'exif-jpeginterchangeformat'       => 'Yr atred i JPEG SOI',
'exif-jpeginterchangeformatlength' => "Nifer beitiau'r data JPEG",
'exif-transferfunction'            => 'Swyddogaeth trosglwyddo',
'exif-whitepoint'                  => 'Cromatigedd y cyfeirbwynt gwyn',
'exif-primarychromaticities'       => 'Cromatigedd y lliwiau cysefin',
'exif-ycbcrcoefficients'           => 'Cyfernodau matrics trawsffurfio gofod lliw',
'exif-referenceblackwhite'         => 'Pâr o gyfeirnodau du a gwyn',
'exif-datetime'                    => 'Dyddiad ac amser y newidiwyd y ffeil ddiwethaf',
'exif-imagedescription'            => 'Teitl y ddelwedd',
'exif-make'                        => 'Gwneuthurwr y camera',
'exif-model'                       => 'Model y camera',
'exif-software'                    => 'Meddalwedd a ddefnyddir',
'exif-artist'                      => 'Awdur',
'exif-copyright'                   => 'Deiliad yr hawlfraint',
'exif-exifversion'                 => 'Fersiwn Exif',
'exif-flashpixversion'             => 'Fersiwn Flashpix a gynhelir',
'exif-colorspace'                  => 'Gofod lliw',
'exif-componentsconfiguration'     => 'Ystyr pob cydran',
'exif-compressedbitsperpixel'      => 'Modd cywasgu delwedd',
'exif-pixelydimension'             => 'Lled delwedd dilys',
'exif-pixelxdimension'             => 'Uchder delwedd dilys',
'exif-makernote'                   => "Nodiadau'r gwneuthurwr",
'exif-usercomment'                 => "Sylwadau'r defnyddiwr",
'exif-relatedsoundfile'            => 'Ffeil sain cysylltiedig',
'exif-datetimeoriginal'            => 'Dyddiad ac amser y cynhyrchwyd y data',
'exif-datetimedigitized'           => 'Dyddiad ac amser y digiteiddiwyd',
'exif-subsectime'                  => 'Manylyn iseiliad amser newid y ffeil',
'exif-subsectimeoriginal'          => "Manylyn iseiliad amser cynhyrchu'r llun",
'exif-subsectimedigitized'         => "Manylyn iseiliad amser digiteiddio'r llun",
'exif-exposuretime'                => 'Amser dinoethi',
'exif-exposuretime-format'         => '$1 eiliad ($2)',
'exif-fnumber'                     => 'Cymhareb yr agorfa (rhif F)',
'exif-exposureprogram'             => 'Rhaglen Dinoethi',
'exif-spectralsensitivity'         => 'Sensitifedd sbectrol',
'exif-isospeedratings'             => 'Cyfraddiad cyflymder ISO',
'exif-oecf'                        => 'Ffactor trawsnewid optoelectronig',
'exif-shutterspeedvalue'           => 'Cyflymder y caead',
'exif-aperturevalue'               => 'Agorfa',
'exif-brightnessvalue'             => 'Disgleirdeb',
'exif-exposurebiasvalue'           => 'Bias dinoethi',
'exif-maxaperturevalue'            => "Maint mwyaf agorfa'r glan",
'exif-subjectdistance'             => 'Pellter y goddrych',
'exif-meteringmode'                => 'Modd mesur goleuni',
'exif-lightsource'                 => 'Tarddiad goleuni',
'exif-flash'                       => 'Fflach',
'exif-focallength'                 => 'Hyd ffocal y lens',
'exif-subjectarea'                 => 'Maint a lleoliad y goddrych',
'exif-flashenergy'                 => "Ynni'r fflach",
'exif-spatialfrequencyresponse'    => 'Spatial frequency response
Ymateb yr amledd gofodol',
'exif-focalplanexresolution'       => 'Datrysiad y plân ffocysu X',
'exif-focalplaneyresolution'       => 'Datrysiad y plân ffocysu Y',
'exif-focalplaneresolutionunit'    => 'Uned mesur datrysiad y plân ffocysu',
'exif-subjectlocation'             => 'Lleoliad y goddrych',
'exif-exposureindex'               => 'Indecs dinoethiad',
'exif-sensingmethod'               => 'Dull synhwyro',
'exif-filesource'                  => 'Ffynhonnell y ffeil',
'exif-scenetype'                   => 'Math o olygfa',
'exif-cfapattern'                  => 'Patrwm CFA',
'exif-customrendered'              => "Hunan-ddewis gosodiadau prosesu'r ddelwedd",
'exif-exposuremode'                => 'Modd dinoethi',
'exif-whitebalance'                => 'Cydbwysedd Gwyn',
'exif-digitalzoomratio'            => 'Cymhareb closio digidol',
'exif-focallengthin35mmfilm'       => 'Hyd ffocal ar ffilm 35mm',
'exif-scenecapturetype'            => 'Modd cipio yn ôl y math o olygfa',
'exif-gaincontrol'                 => 'Rheolydd golygfa',
'exif-contrast'                    => 'Cyferbyniad',
'exif-saturation'                  => 'Dirlawnder',
'exif-sharpness'                   => 'Eglurder',
'exif-devicesettingdescription'    => "Disgrifiad o osodiadau'r ddyfais",
'exif-subjectdistancerange'        => 'Amrediad pellter y goddrych',
'exif-imageuniqueid'               => 'ID unigryw y ddelwedd',
'exif-gpsversionid'                => 'Fersiwn y tag GPS',
'exif-gpslatituderef'              => "Lledred i'r Gogledd neu i'r De",
'exif-gpslatitude'                 => 'Lledred',
'exif-gpslongituderef'             => "Hydred i'r Dwyrain neu i'r Gorllewin",
'exif-gpslongitude'                => 'Hydred',
'exif-gpsaltituderef'              => 'Cyfeirnod uchder',
'exif-gpsaltitude'                 => 'Uchder',
'exif-gpstimestamp'                => 'Amser GPS (cloc atomig)',
'exif-gpssatellites'               => 'Defnyddir lloerennau i fesur',
'exif-gpsstatus'                   => 'Statws y derbynnydd',
'exif-gpsmeasuremode'              => 'Modd mesur',
'exif-gpsdop'                      => 'Manylder mesur',
'exif-gpsspeedref'                 => 'Uned cyflymder',
'exif-gpsspeed'                    => 'Cyflymder y derbynnydd GPS',
'exif-gpstrackref'                 => 'Cyfeirbwynt ar gyfer cyfeiriad y symud',
'exif-gpstrack'                    => 'Cyfeiriad symud',
'exif-gpsimgdirectionref'          => 'Cyfeirbwynt ar gyfer cyfeiriad y ddelwedd',
'exif-gpsimgdirection'             => 'Cyfeiriad y ddelwedd',
'exif-gpsmapdatum'                 => 'Defnyddir data o arolwg geodetig',
'exif-gpsdestlatituderef'          => 'Cyfeirbwynt lledred y cyrchnod',
'exif-gpsdestlatitude'             => 'Lledred y cyrchfan',
'exif-gpsdestlongituderef'         => 'Cyfeirbwynt hydred y cyrchfan',
'exif-gpsdestlongitude'            => 'Hydred y cyrchfan',
'exif-gpsdestbearingref'           => 'Cyfeirnod ar gyfer cyfeiriant y cyrchfan',
'exif-gpsdestbearing'              => 'Cyfeiriant y cyrchfan',
'exif-gpsdestdistanceref'          => 'Cyfeirnod ar gyfer pellter y cyrchfan',
'exif-gpsdestdistance'             => 'Pellter i ben y daith',
'exif-gpsprocessingmethod'         => "Enw'r dull prosesu GPS",
'exif-gpsareainformation'          => "Enw'r parth GPS",
'exif-gpsdatestamp'                => 'Dyddiad GPS',
'exif-gpsdifferential'             => 'cywiriad differol y GPS',

# EXIF attributes
'exif-compression-1' => 'Heb ei gywasgu',

'exif-unknowndate' => 'Dyddiad anhysbys',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Wedi troi tu chwith ar lorwedd',
'exif-orientation-3' => 'Wedi ei droi 180°',
'exif-orientation-4' => 'Wedi troi wyneb i waered',
'exif-orientation-5' => 'Wedi troi 90° yn erbyn y cloc a thu chwith yn fertigol',
'exif-orientation-6' => "Wedi troi 90° gyda'r cloc",
'exif-orientation-7' => "Wedi troi 90° gyda'r cloc a thu chwith yn fertigol",
'exif-orientation-8' => "Wedi troi 90° yn groes i'r cloc",

'exif-planarconfiguration-1' => 'fformat talpiog',
'exif-planarconfiguration-2' => 'fformat planar',

'exif-componentsconfiguration-0' => "ddim i'w gael",

'exif-exposureprogram-0' => 'Heb ei gosod',
'exif-exposureprogram-1' => 'Hunan-ddewis',
'exif-exposureprogram-2' => 'Rhaglen normal',
'exif-exposureprogram-3' => 'Hunan-ddewis yr agorfa',
'exif-exposureprogram-4' => 'Hunan-ddewis cyflymder y caead',
'exif-exposureprogram-5' => 'Rhaglen creadigol (blaenoriaeth i ddyfnder ffocws)',
'exif-exposureprogram-6' => 'Rhaglen digwyddiad (yn tueddu at gyflymder caead uchel)',
'exif-exposureprogram-7' => "Modd portread (ar gyfer lluniau agos a'r cefndir yn aneglur)",
'exif-exposureprogram-8' => 'Modd tirlun (ar gyfer tirluniau wedi ffocysu ar y cefndir)',

'exif-subjectdistance-value' => '$1 medr',

'exif-meteringmode-0'   => 'Anhysbys',
'exif-meteringmode-1'   => 'Cyfartaleddu',
'exif-meteringmode-2'   => 'Cyfartaleddu canol-bwysedig',
'exif-meteringmode-3'   => 'Smotyn',
'exif-meteringmode-4'   => 'Smotiau',
'exif-meteringmode-5'   => 'Patrymu',
'exif-meteringmode-6'   => 'Rhannol',
'exif-meteringmode-255' => 'Arall',

'exif-lightsource-0'   => 'Anhysbys',
'exif-lightsource-1'   => 'Golau dydd',
'exif-lightsource-2'   => 'Fflworolau',
'exif-lightsource-3'   => 'Twngsten (golau gwynias)',
'exif-lightsource-4'   => 'Fflach',
'exif-lightsource-9'   => 'Tywydd braf',
'exif-lightsource-10'  => 'Tywydd cymylog',
'exif-lightsource-11'  => 'Cysgod',
'exif-lightsource-12'  => 'Fflworolau golau dydd (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fflworolau gwyn golau dydd (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fflworolau gwyn oeraidd (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fflworolau gwyn (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lamp hirgoes A',
'exif-lightsource-18'  => 'Lamp hirgoes B',
'exif-lightsource-19'  => 'Lamp hirgoes C',
'exif-lightsource-24'  => 'Twngsten stiwdio ISO',
'exif-lightsource-255' => "Tarddiad arall i'r goleuni",

# Flash modes
'exif-flash-fired-0'    => 'Ni daniodd y fflach',
'exif-flash-fired-1'    => 'Taniodd y fflach',
'exif-flash-return-0'   => "hepgor synhwyro golau'r fflach wedi ei daflu yn ôl",
'exif-flash-return-2'   => "ni synhwyrwyd golau'r fflach wedi ei daflu yn ôl",
'exif-flash-return-3'   => "synhwyrwyd golau'r fflach wedi ei daflu yn ôl",
'exif-flash-mode-1'     => 'gosod y fflach i danio',
'exif-flash-mode-2'     => 'hepgorwyd y fflach',
'exif-flash-mode-3'     => 'modd awtomatig',
'exif-flash-function-1' => 'Dim fflach',
'exif-flash-redeye-1'   => 'modd lleddfu llygaid cochion',

'exif-focalplaneresolutionunit-2' => 'modfeddi',

'exif-sensingmethod-1' => 'Heb ei ddiffinio',
'exif-sensingmethod-2' => 'Synhwyrydd lliw ardal un-naddyn',
'exif-sensingmethod-3' => 'Synhwyrydd lliw ardal dau-naddyn',
'exif-sensingmethod-4' => 'Synhwyrydd lliw ardal tri-naddyn',
'exif-sensingmethod-5' => 'Synhwyrydd lliw ardal dilyniannol',
'exif-sensingmethod-7' => 'Synhwyrydd trillinol',
'exif-sensingmethod-8' => 'Synhwyrydd lliw llinellol dilyniannol',

'exif-scenetype-1' => "Delwedd wedi ei dynnu'n uniongyrchol",

'exif-customrendered-0' => 'Proses normal',
'exif-customrendered-1' => "Proses wedi'i addasu",

'exif-exposuremode-0' => 'Dinoethi awtomatig',
'exif-exposuremode-1' => 'Hunan-ddewis hyd y dinoethiad',
'exif-exposuremode-2' => 'Cyfres dinoethi awtomatig',

'exif-whitebalance-0' => 'Cydwysedd gwyn awtomatig',
'exif-whitebalance-1' => 'Cydbwysedd gwyn hunan-ddewisedig',

'exif-scenecapturetype-0' => 'Safonol',
'exif-scenecapturetype-1' => 'Tirlun',
'exif-scenecapturetype-2' => 'Portread',
'exif-scenecapturetype-3' => 'Golygfa nos',

'exif-gaincontrol-0' => 'Dim',
'exif-gaincontrol-1' => 'Lled-gynyddu disgleirdeb - cynyddu',
'exif-gaincontrol-2' => 'Tra-chynyddu disgleirdeb - cynyddu',
'exif-gaincontrol-3' => 'Lled-gynyddu disgleirdeb - lleihau',
'exif-gaincontrol-4' => 'Tra-chynyddu disgleirdeb - lleihau',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Meddal',
'exif-contrast-2' => 'Caled',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Dirlawnder isel',
'exif-saturation-2' => 'Dirlawnder uchel',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Meddal',
'exif-sharpness-2' => 'Caled',

'exif-subjectdistancerange-0' => 'Anhysbys',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Golygfa agos',
'exif-subjectdistancerange-3' => 'Golygfa pell',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => "Lledred i'r Gogledd",
'exif-gpslatitude-s' => "Lledred i'r De",

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => "Hydred i'r Dwyrain",
'exif-gpslongitude-w' => "Hydred i'r Gorllewin",

'exif-gpsstatus-a' => "Wrthi'n mesur",
'exif-gpsstatus-v' => 'Y gallu i ryngweithredu o ran mesur',

'exif-gpsmeasuremode-2' => 'mesuriad 2 ddimensiwn',
'exif-gpsmeasuremode-3' => 'mesuriad 3 dimensiwn',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Cilomedr yr awr',
'exif-gpsspeed-m' => 'Milltir yr awr',
'exif-gpsspeed-n' => 'Notiau',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Gwir gyfeiriad',
'exif-gpsdirection-m' => 'Cyfeiriad magnetig',

# External editor support
'edit-externally'      => 'Golygwch y ffeil gyda rhaglen allanol',
'edit-externally-help' => '(Gwelwch y [http://www.mediawiki.org/wiki/Manual:External_editors cyfarwyddiadau gosod] am fwy o wybodaeth)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'holl',
'imagelistall'     => 'holl',
'watchlistall2'    => 'holl',
'namespacesall'    => 'pob un',
'monthsall'        => 'pob mis',
'limitall'         => 'oll',

# E-mail address confirmation
'confirmemail'              => "Cadarnhau'r cyfeiriad e-bost",
'confirmemail_noemail'      => 'Does dim cyfeiriad e-bost dilys wedi ei osod yn eich [[Special:Preferences|dewisiadau defnyddiwr]].',
'confirmemail_text'         => "Cyn i chi allu defnyddio'r nodweddion e-bost, mae'n rhaid i {{SITENAME}} ddilysu'ch cyfeiriad e-bost. Pwyswch y botwm isod er mwyn anfon côd cadarnhau i'ch cyfeiriad e-bost. Bydd yr e-bost yn cynnwys cyswllt gyda chôd ynddi; llwythwch y cyswllt ar eich porwr er mwyn cadarnhau dilysrwydd eich cyfeiriad e-bost.",
'confirmemail_pending'      => "Mae côd cadarnhau eisoes wedi ei anfon atoch; os ydych newydd greu'ch cyfrif, hwyrach y gallech ddisgwyl rhai munudau amdano cyn gofyn yr eilwaith am gôd newydd.",
'confirmemail_send'         => 'Postiwch gôd cadarnhau',
'confirmemail_sent'         => "Wedi anfon e-bost er mwyn cadarnhau'r cyfeiriad.",
'confirmemail_oncreate'     => "Anfonwyd côd cadarnhau at eich cyfeiriad e-bost.
Nid oes rhaid wrth y côd wrth fewngofnodi, ond rhaid ei ddefnyddio er mwyn galluogi offer ar y wici sy'n defnyddio e-bost.",
'confirmemail_sendfailed'   => "Ni fu'n bosibl danfon yr e-bost cadarnháu oddi wrth {{SITENAME}}. Gwiriwch eich cyfeiriad e-bost am nodau annilys.

Dychwelodd yr ebostydd: $1",
'confirmemail_invalid'      => 'Côd cadarnhau annilys. Efallai fod y côd wedi dod i ben.',
'confirmemail_needlogin'    => 'Rhaid $1 er mwyn cadarnhau eich cyfeiriad e-bost.',
'confirmemail_success'      => "Mae eich cyfeiriad e-bost wedi'i gadarnhau. 
Cewch [[Special:UserLogin|fewngofnodi]] a mwynhau'r wici.",
'confirmemail_loggedin'     => 'Cadarnhawyd eich cyfeiriad e-bost.',
'confirmemail_error'        => 'Cafwyd gwall wrth ddanfon eich cadarnhad.',
'confirmemail_subject'      => 'Cadarnhâd cyfeiriad e-bost ar {{SITENAME}}',
'confirmemail_body'         => 'Mae rhywun (chi, yn fwy na thebyg, o\'r cyfeiriad IP $1) wedi cofrestru\'r cyfrif "$2" ar {{SITENAME}} gyda\'r cyfeiriad e-bost hwn.

I gadarnhau mai chi yn wir yw perchennog y cyfrif hwn, ac i alluogi nodweddion e-bost ar {{SITENAME}}, agorwch y cyswllt hwn yn eich porwr:

$3

Os *nad* chi sydd berchen y cyfrif hwn, dilynwch y cyswllt hwn er mwyn diddymu cadarnhad y cyfeiriad e-bost:

$5

Bydd y côd cadarnhau yn dod i ben am $4.',
'confirmemail_body_changed' => 'Mae rhywun (chi, yn fwy na thebyg, o\'r cyfeiriad IP $1) wedi newid cyfeiriad e-bost y cyfrif "$2" ar {{SITENAME}} i\'r cyfeiriad e-bost hwn.

I gadarnhau mai chi yn wir yw perchennog y cyfrif hwn, ac i ail-alluogi nodweddion e-bost ar {{SITENAME}}, agorwch y cyswllt hwn yn eich porwr:

$3

Os *nad* chi sydd berchen y cyfrif hwn, dilynwch y cyswllt hwn er mwyn diddymu cadarnhad y cyfeiriad e-bost:

$5

Bydd y côd cadarnhau yn dod i ben am $4.',
'confirmemail_invalidated'  => "Diddymwyd y weithred o gadarnhau'r cyfeiriad e-bost",
'invalidateemail'           => 'Diddymu cadarnhad y cyfeiriad e-bost.',

# Scary transclusion
'scarytranscludedisabled' => '[Analluogwyd cynhwysiad rhyng-wici]',
'scarytranscludefailed'   => '[Methwyd â nôl y nodyn ar gyfer $1]',
'scarytranscludetoolong'  => "[Mae'r URL yn rhy hir]",

# Trackbacks
'trackbackbox'      => "Cysylltiadau 'Trackback' ar gyfer yr erthygl hon:<br />
$1",
'trackbackremove'   => '([$1 Dileu])',
'trackbacklink'     => "Cyswllt 'trackback'",
'trackbackdeleteok' => "Dilewyd y cyswllt 'trackback' yn llwyddiannus.",

# Delete conflict
'deletedwhileediting' => "'''Rhybudd''': Dilëwyd y dudalen wedi i chi ddechrau ei golygu!",
'confirmrecreate'     => "Mae'r defnyddiwr [[User:$1|$1]] ([[User talk:$1|Sgwrs]]) wedi dileu'r erthygl hon ers i chi ddechrau golygu. Y rheswm oedd:
: ''$2''
Cadarnhewch eich bod chi wir am ail-greu'r erthygl.",
'recreate'            => 'Ail-greu',

# action=purge
'confirm_purge_button' => 'Iawn',
'confirm-purge-top'    => "Clirio'r dudalen o'r storfa?",
'confirm-purge-bottom' => "Mae carthu tudalen yn clirio'r celc ac yn gorfodi'r fersiwn diweddaraf i ymddangos.",

# Multipage image navigation
'imgmultipageprev' => "← i'r dudalen gynt",
'imgmultipagenext' => "i'r dudalen nesaf →",
'imgmultigo'       => 'Eler!',
'imgmultigoto'     => "Mynd i'r dudalen $1",

# Table pager
'ascending_abbrev'         => 'esgynnol',
'descending_abbrev'        => 'am lawr',
'table_pager_next'         => 'Tudalen nesaf',
'table_pager_prev'         => 'Tudalen gynt',
'table_pager_first'        => 'Tudalen gyntaf',
'table_pager_last'         => 'Tudalen olaf',
'table_pager_limit'        => 'Dangos $1 eitem y dudalen',
'table_pager_limit_label'  => 'Eitemau ar bob tudalen:',
'table_pager_limit_submit' => 'Eler',
'table_pager_empty'        => 'Dim canlyniadau',

# Auto-summaries
'autosumm-blank'   => "Wedi gwacáu'r dudalen yn llwyr",
'autosumm-replace' => "Gwacawyd y dudalen a gosod y canlynol yn ei le: '$1'",
'autoredircomment' => 'Yn ailgyfeirio at [[$1]]',
'autosumm-new'     => "Crëwyd tudalen newydd yn dechrau gyda '$1'",

# Live preview
'livepreview-loading' => "Wrthi'n llwytho…",
'livepreview-ready'   => 'Llwytho… Ar ben!',
'livepreview-failed'  => 'Y rhagolwg byw wedi methu! Rhowch gynnig ar y rhagolwg arferol.',
'livepreview-error'   => 'Wedi methu cysylltu: $1 "$2". Rhowch gynnig ar y rhagolwg arferol.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Hwyrach na ddangosir isod y newidiadau a ddigwyddodd o fewn y $1 {{PLURAL:$1|eiliad|eiliad|eiliad|eiliad|eiliad|eiliad}} ddiwethaf.',
'lag-warn-high'   => 'Mae gweinydd y data-bas ar ei hôl hi: efallai nad ymddengys newidiadau o fewn y $1 {{PLURAL:$1|eiliad|eiliad|eiliad|eiliad|eiliad|eiliad}} ddiwethaf ar y rhestr.',

# Watchlist editor
'watchlistedit-numitems'       => 'Mae {{PLURAL:$1|$1 tudalen|$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 o dudalennau}} ar eich rhestr wylio, heb gynnwys tudalennau sgwrs.',
'watchlistedit-noitems'        => "Mae'ch rhestr wylio'n wag.",
'watchlistedit-normal-title'   => "Golygu'r rhestr wylio",
'watchlistedit-normal-legend'  => 'Tynnu tudalennau oddi ar y rhestr wylio',
'watchlistedit-normal-explain' => 'Rhestrir y teitlau ar eich rhestr wylio isod.
I dynnu teitl oddi ar y rhestr, ticiwch y blwch ar ei gyfer, yna cliciwch "{{int:Watchlistedit-normal-submit}}".
Gallwch hefyd ddewis golygu\'r rhestr wylio ar ei [[Special:Watchlist/raw|ffurf syml]].',
'watchlistedit-normal-submit'  => "Tynnu'r tudalennau",
'watchlistedit-normal-done'    => 'Tynnwyd {{PLURAL:$1|$1 tudalen|$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 tudalen}} oddi ar eich rhestr wylio:',
'watchlistedit-raw-title'      => 'Golygu ffeil y rhestr wylio',
'watchlistedit-raw-legend'     => 'Golygu ffeil y rhestr wylio',
'watchlistedit-raw-explain'    => 'Rhestrir y teitlau ar eich rhestr wylio isod. Gellir newid y rhestr drwy ychwanegu neu dynnu teitlau; gyda llinell yr un i bob teitl.
Pan yn barod, pwyswch ar "{{int:Watchlistedit-raw-submit}}".
Gallwch hefyd [[Special:Watchlist/edit|ddefnyddio\'r rhestr arferol]].',
'watchlistedit-raw-titles'     => 'Teitlau:',
'watchlistedit-raw-submit'     => "Diweddaru'r rhestr wylio",
'watchlistedit-raw-done'       => 'Diweddarwyd eich rhestr wylio.',
'watchlistedit-raw-added'      => 'Ychwanegwyd {{PLURAL:$1|1 teitl|$1 teitl|$1 deitl|$1 theitl|$1 theitl|$1 o deitlau}}:',
'watchlistedit-raw-removed'    => 'Tynnwyd {{PLURAL:$1|1 teitl|$1 teitl|$1 deitl|$1 theitl|$1 theitl|$1 o deitlau}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Gweld newidiadau perthnasol',
'watchlisttools-edit' => "Gweld a golygu'r rhestr wylio",
'watchlisttools-raw'  => "Golygu'r rhestr wylio syml",

# Core parser functions
'unknown_extension_tag' => 'Tag estyniad anhysbys "$1"',
'duplicate-defaultsort' => 'Rhybudd: Mae\'r allwedd trefnu diofyn "$2" yn gwrthwneud yr allwedd trefnu diofyn blaenorol "$1".',

# Special:Version
'version'                          => 'Fersiwn',
'version-extensions'               => 'Estyniadau gosodedig',
'version-specialpages'             => 'Tudalennau arbennig',
'version-parserhooks'              => 'Bachau dosrannydd',
'version-variables'                => 'Newidynnau',
'version-antispam'                 => 'Atal sbam',
'version-skins'                    => 'Gweddau',
'version-other'                    => 'Arall',
'version-mediahandlers'            => 'Trinyddion cyfryngau',
'version-hooks'                    => 'Bachau',
'version-extension-functions'      => 'Ffwythiannau estyn',
'version-parser-extensiontags'     => 'Tagiau estyn dosrannydd',
'version-parser-function-hooks'    => 'Bachau ffwythiant dosrannu',
'version-skin-extension-functions' => 'Ffwythiannau estyn y wedd',
'version-hook-name'                => "Enw'r bachyn",
'version-hook-subscribedby'        => 'Tanysgrifwyd gan',
'version-version'                  => '(Fersiwn $1)',
'version-license'                  => 'Trwydded',
'version-poweredby-credits'        => "Mae'r wici hwn wedi'i nerthu gan '''[http://www.mediawiki.org/ MediaWiki]''', hawlfraint © 2001 - $1 $2.",
'version-poweredby-others'         => 'eraill',
'version-license-info'             => "Meddalwedd rhydd yw MediaWiki; gallwch ei ddefnyddio a'i addasu yn ôl termau'r GNU General Public License a gyhoeddir gan Free Software Foundation; naill ai fersiwn 2 o'r Drwydded, neu unrhyw fersiwn diweddarach o'ch dewis.

Cyhoeddir MediaWiki yn y gobaith y bydd o ddefnydd, ond HEB UNRHYW WARANT; heb hyd yn oed gwarant ymhlyg o FARCHNADWYEDD nag o FOD YN ADDAS AT RYW BWRPAS ARBENNIG. Gweler y GNU General Public License am fanylion pellach.

Dylech fod wedi derbyn [{{SERVER}}{{SCRIPTPATH}}/COPYING gopi o GNU General Public License] gyda'r rhaglen hon; os nad ydych, ysgrifennwch at Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, neu [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html gallwch ei ddarllen ar y we].",
'version-software'                 => 'Meddalwedd gosodedig',
'version-software-product'         => 'Cynnyrch',
'version-software-version'         => 'Fersiwn',

# Special:FilePath
'filepath'         => 'Llwybr y ffeil',
'filepath-page'    => 'Ffeil:',
'filepath-submit'  => 'Eler',
'filepath-summary' => 'Mae\'r dudalen arbennig hon yn adrodd llwybr ffeil yn gyfan.
Dangosir delweddau ar eu maint llawn, dechreuir ffeiliau o fathau eraill yn uniongyrchol gan y rhaglen gysylltiedig.

Rhowch enw\'r ffeil heb y rhagddodiad "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Chwilio am ffeiliau dyblyg',
'fileduplicatesearch-summary'  => 'Chwilier am ffeiliau dyblyg ar sail ei werth stwnsh.

Rhowch enw\'r ffeil heb y rhagddodiad "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Chwilio am ddyblygeb',
'fileduplicatesearch-filename' => "Enw'r ffeil:",
'fileduplicatesearch-submit'   => 'Chwilier',
'fileduplicatesearch-info'     => '$1 × $2 picsel<br />Maint y ffeil: $3<br />math MIME: $4',
'fileduplicatesearch-result-1' => 'Nid oes yr un ffeil i gael sydd yn union yr un fath â\'r ffeil "$1".',
'fileduplicatesearch-result-n' => '{{PLURAL:$2|Nid oes yr un ffeil|Mae $2 ffeil|Mae $2 ffeil|Mae $2 ffeil|Mae $2 ffeil|Mae $2 ffeil|}} i gael sydd yn union yr un fath â\'r ffeil "$1".',

# Special:SpecialPages
'specialpages'                   => 'Tudalennau arbennig',
'specialpages-note'              => '----
* Tudalennau arbennig ar gael i bawb.
* <strong class="mw-specialpagerestricted">Tudalennau arbennig cyfyngedig.</strong>',
'specialpages-group-maintenance' => 'Adroddiadau cynnal a chadw',
'specialpages-group-other'       => 'Eraill',
'specialpages-group-login'       => 'Mewngofnodi / creu cyfrif',
'specialpages-group-changes'     => 'Newidiadau diweddar a logiau',
'specialpages-group-media'       => 'Ffeiliau - adroddiadau ac uwchlwytho',
'specialpages-group-users'       => "Defnyddwyr a'u galluoedd",
'specialpages-group-highuse'     => 'Tudalennau aml eu defnydd',
'specialpages-group-pages'       => 'Rhestrau tudalennau',
'specialpages-group-pagetools'   => 'Offer trin tudalennau',
'specialpages-group-wiki'        => 'Data ac offer y wici',
'specialpages-group-redirects'   => 'Tudalennau arbennig ailgyfeirio',
'specialpages-group-spam'        => 'Offer sbam',

# Special:BlankPage
'blankpage'              => 'Tudalen wag',
'intentionallyblankpage' => 'Gadawyd y dudalen hon yn wag o fwriad',

# External image whitelist
'external_image_whitelist' => " #Leave this line exactly as it is<pre>
#Gosodwch darnau o ymadroddion rheolaidd (y rhan sy'n cael ei osod rhwng y //) isod
#Caiff y rhain eu cysefeillio gyda URL y delweddau allanol (a chyswllt poeth atynt)
#Dangosir y rhai sy'n cysefeillio fel delweddau; dangosir cyswllt at y ddelwedd yn unig ar gyfer y lleill
#Caiff y llinellau sy'n dechrau gyda # eu trin fel sylwadau
#Nid yw'n gwahaniaethu rhwng llythrennau mawr a bach

#Put all regex fragments above this line. Leave this line exactly as it is</pre>",

# Special:Tags
'tags'                    => 'Tagiau newidiadau',
'tag-filter'              => 'Hidl [[Special:Tags|tagiau]]:',
'tag-filter-submit'       => 'Hidlo',
'tags-title'              => 'Tagiau',
'tags-intro'              => "Dyma restr o'r tagiau y mae'r meddalwedd yn defnyddio i farcio golygiad, ynghyd â'r rhesymau dros eu defnyddio.",
'tags-tag'                => "Enw'r tag",
'tags-display-header'     => 'Y nodyn a welir ar logiau',
'tags-description-header' => 'Disgrifiad llawn y tag',
'tags-hitcount-header'    => 'Nifer wedi tagio',
'tags-edit'               => 'golygu',
'tags-hitcount'           => '$1 {{PLURAL:$1|newid}}',

# Special:ComparePages
'comparepages'     => 'Cymharu tudalennau',
'compare-selector' => "Cymharu diwygiadau gwahanol o'r dudalen",
'compare-page1'    => 'Tudalen 1',
'compare-page2'    => 'Tudalen 2',
'compare-rev1'     => 'Diwygiad 1',
'compare-rev2'     => 'Diwygiad 2',
'compare-submit'   => 'Cymharer',

# Database error messages
'dberr-header'      => 'Mae problem gan y wici hwn',
'dberr-problems'    => "Mae'n ddrwg gennym! Mae'r wefan hon yn dioddef anawsterau technegol.",
'dberr-again'       => 'Oedwch am ychydig funudau cyn ceisio ail-lwytho.',
'dberr-info'        => '(Ni ellir cysylltu â gweinydd y bas data: $1)',
'dberr-usegoogle'   => 'Yn y cyfamser gallwch geisio chwilio gyda Google.',
'dberr-outofdate'   => "Sylwch y gall eu mynegeion o'n cynnwys fod ar ei hôl hi.",
'dberr-cachederror' => "Dyma gopi o'r dudalen a ofynnwyd amdani, a dynnwyd o'r celc. Mae'n bosib nad y fersiwn diweddaraf yw'r copi hwn.",

# HTML forms
'htmlform-invalid-input'       => "Mae problemau gyda pheth o'ch mewnbwn",
'htmlform-select-badoption'    => "Nid yw'r gwerth a bennwyd gennych yn ddewis dilys.",
'htmlform-int-invalid'         => "Nid yw'r gwerth a bennwyd gennych yn gyfanrif.",
'htmlform-float-invalid'       => "Nid yw'r gwerth a bennwyd gennych yn rif.",
'htmlform-int-toolow'          => "Mae'r gwerth a bennwyd gennych yn llai na'r isafswm $1",
'htmlform-int-toohigh'         => "Mae'r gwerth a bennwyd gennych yn fwy na'r uchafswm $1",
'htmlform-required'            => "Rhaid llanw'r blwch hwn",
'htmlform-submit'              => 'Gosoder',
'htmlform-reset'               => 'Datod y newidiadau',
'htmlform-selectorother-other' => 'Arall',

# SQLite database support
'sqlite-has-fts' => '$1 gyda chymorth chwilio yr holl destun',
'sqlite-no-fts'  => '$1 heb gymorth chwiliad yr holl destun',

# Special:DisableAccount
'disableaccount'             => 'Analluogi cyfrif defnyddiwr',
'disableaccount-user'        => 'Enw defnyddiwr:',
'disableaccount-reason'      => 'Rheswm:',
'disableaccount-confirm'     => "Analluogu cyfrif y defnyddiwr hwn. 
Ni fydd y defnyddiwr yn gallu mewngofnodi, ailosod ei gyfrinair, na derbyn hysbysiadau e-bost. 
Os yw'r defnyddiwr wedi mewngofnodi rhywle ar hyn o bryd, bydd yn cael ei allgofnodi'n syth. 
''Noder nad oes modd gwrthdroi'r weithred o anablu cyfrif heb ymyrraeth gweinyddwr y system.''",
'disableaccount-mustconfirm' => "Mae'n rhaid i chi gadarnhau eich bod am analluogi'r cyfrif hwn.",
'disableaccount-nosuchuser'  => 'Nid oes cyfrif defnyddiwr o\'r enw "$1" ar gael.',
'disableaccount-success'     => 'Analluogwyd cyfrif y defnyddiwr "$1" yn barhaol.',
'disableaccount-logentry'    => 'wedi analluogi cyfrif y defnyddiwr [[$1]] yn barhaol',

);
