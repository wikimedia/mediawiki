<?php
/** Welsh (Cymraeg)
 *
 * @addtogroup Language
 *
 * @author Lloffiwr
 * @author Nike
 * @author Siebrand
 * @author לערי ריינהארט
 * @author SPQRobin
 */

$namespaceNames = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Arbennig",
	NS_MAIN           => "",
	NS_TALK           => "Sgwrs",
	NS_USER           => "Defnyddiwr",
	NS_USER_TALK      => "Sgwrs_Defnyddiwr",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => "Sgwrs_$1",
	NS_IMAGE          => "Delwedd",
	NS_IMAGE_TALK     => "Sgwrs_Delwedd",
	NS_MEDIAWIKI      => "MediaWici",
	NS_MEDIAWIKI_TALK => "Sgwrs_MediaWici",
	NS_TEMPLATE       => "Nodyn",
	NS_TEMPLATE_TALK  => "Sgwrs_Nodyn",
	NS_CATEGORY		  => "Categori",
	NS_CATEGORY_TALK  => "Sgwrs_Categori",
	NS_HELP			  => "Cymorth",
	NS_HELP_TALK	  => "Sgwrs Cymorth"
);

$skinNames = array(
	'standard' => "Safonol",
	'nostalgia' => "Hiraeth",
	'cologneblue' => "Glas Cwlen",
);

$datePreferences = false;

$bookstoreList = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
	"Amazon.co.uk" => "http://www.amazon.co.uk/exec/obidos/ISBN=$1"
);


$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    "#redirect", "#ail-cyfeirio"                 ),
	'notoc'                  => array( 0,    "__NOTOC__", "__DIMTAFLENCYNNWYS__"          ),
	'noeditsection'          => array( 0,    "__NOEDITSECTION__", "__DIMADRANGOLYGU__"    ),
	'currentmonth'           => array( 1, "CURRENTMONTH", "MISCYFOES"                ),
	'currentmonthname'       => array( 1,    "CURRENTMONTHNAME", "ENWMISCYFOES"           ),
	'currentday'             => array( 1,    "CURRENTDAY", "DYDDIADCYFOES"                ),
	'currentdayname'         => array( 1,    "CURRENTDAYNAME", "ENWDYDDCYFOES"            ),
	'currentyear'            => array( 1,    "CURRENTYEAR", "FLWYDDYNCYFOES"              ),
	'currenttime'            => array( 1,    "CURRENTTIME", "AMSERCYFOES"                 ),
	'numberofarticles'       => array( 1, "NUMBEROFARTICLES","NIFEROERTHYGLAU"       ),
	'currentmonthnamegen'    => array( 1,    "CURRENTMONTHNAMEGEN", "GENENWMISCYFOES"     ),
	'img_thumbnail'          => array( 1, "ewin bawd", "bawd", "thumb", "thumbnail"  ),
	'img_right'              => array( 1,    "de", "right"                                ),
	'img_left'               => array( 1,    "chwith", "left"                             ),
	'img_none'               => array( 1,    "dim", "none"                                ),
	'img_center'             => array( 1, "canol", "centre", "center"                ),

);
$linkTrail = "/^([àáâèéêìíîïòóôûŵŷa-z]+)(.*)\$/sDu";

$messages = array(
# User preference toggles
'tog-underline'               => 'Tanlinellu cysylltiadau:',
'tog-highlightbroken'         => 'Fformatio cysylltiadau wedi\'u torri <a href="" class="new">fel hyn</a> (dewis arall: fel hyn<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Unioni paragraffau',
'tog-hideminor'               => 'Cuddiwch golygiadau bach mewn newidiadau diweddar',
'tog-extendwatchlist'         => 'Ehangu manylion y rhestr gwylio i ddangos pob golygiad i dudalen, nid dim ond y diweddaraf',
'tog-usenewrc'                => "Fersiwn well o 'Newidiadau diweddar' (JavaScript)",
'tog-numberheadings'          => 'Rhifwch teitlau yn awtomatig',
'tog-showtoolbar'             => 'Dangos y bar offer golygu (JavaScript)',
'tog-editondblclick'          => 'Golygu tudalennau gyda clic dwbwl (JavaScript)',
'tog-editsection'             => 'Galluogi golygu adran drwy gyswllt [golygu] wrth ymyl pennawd yr adran',
'tog-editsectiononrightclick' => 'Galluogi golygu adran drwy dde-glicio ar bennawd yr adran (JavaScript)',
'tog-showtoc'                 => 'Dangos y daflen gynnwys (ar gyfer tudalennau sydd â mwy na 3 pennawd)',
'tog-rememberpassword'        => "Y cyfrifiadur hwn i gofio'r cyfrinair",
'tog-editwidth'               => 'Mae lled llawn gan y blwch golygu',
'tog-watchcreations'          => 'Ychwanegu tudalennau at fy rhestr gwylio wrth i mi eu creu',
'tog-watchdefault'            => 'Ychwanegu tudalen at fy rhestr gwylio wrth i mi ei golygu',
'tog-watchmoves'              => 'Ychwanegu tudalen at fy rhestr gwylio wrth i mi ei symud.',
'tog-watchdeletion'           => 'Ychwanegu tudalennau at fy rhestr gwylio wrth i mi eu dileu',
'tog-minordefault'            => 'Marciwch pob golygiad fel un bach',
'tog-previewontop'            => 'Dangos y rhagolwg uwchben yn hytrach nag o dan y bocs golygu.',
'tog-previewonfirst'          => 'Dangos rhagolwg ar y golygiad cyntaf',
'tog-nocache'                 => 'Analluogi storio tudalennau mewn celc',
'tog-enotifwatchlistpages'    => 'Gyrru e-bost ata i pan fo newid i dudalen ar fy rhestr gwylio',
'tog-enotifusertalkpages'     => "Gyrru e-bost ata i pan fo newid i'm tudalen sgwrs",
'tog-enotifminoredits'        => 'Gyrru e-bost ata i ar gyfer golygiadau bychain i dudalennau, hefyd',
'tog-enotifrevealaddr'        => 'Datguddio fy nghyfeiriad e-bost mewn e-byst hysbysu',
'tog-shownumberswatching'     => "Dangos y nifer o ddefnyddwyr sy'n gwylio",
'tog-fancysig'                => 'Llofnod crai (heb gyswllt wici ynghlwm wrtho)',
'tog-externaleditor'          => 'Defnyddio golygydd allanol trwy ragosodiad',
'tog-externaldiff'            => 'Defnyddio "external diff" trwy ragosodiad',
'tog-showjumplinks'           => 'Galluogi cysylltiadau hygyrchedd, e.e. [alt-z]',
'tog-uselivepreview'          => 'Defnyddio rhagolwg byw (JavaScript) (Arbrofol)',
'tog-forceeditsummary'        => 'Tynnu fy sylw pan adawaf flwch crynodeb golygu yn wag',
'tog-watchlisthideown'        => 'Cuddio fy ngolygiadau fy hunan yn fy rhestr gwylio',
'tog-watchlisthidebots'       => 'Cuddio golygiadau bot yn fy rhestr gwylio',
'tog-watchlisthideminor'      => 'Cuddio golygiadau bychain rhag y rhestr gwylio',
'tog-ccmeonemails'            => 'Anfoner copi ataf pan anfonaf e-bost at ddefnyddiwr arall',
'tog-diffonly'                => "Peidio â dangos cynnwys y dudalen islaw'r gymhariaeth ar dudalennau cymharu",
'tog-showhiddencats'          => 'Dangos categorïau cuddiedig',

'underline-always'  => 'Bob amser',
'underline-never'   => 'Byth',
'underline-default' => 'Rhagosodyn y porwr',

'skinpreview' => '(Rhagolwg)',

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
'sep'           => 'Med',
'oct'           => 'Hyd',
'nov'           => 'Tach',
'dec'           => 'Rhag',

# Bits of text used by many pages
'categories'               => 'Categorïau',
'pagecategories'           => '{{PLURAL:$1|Categori|Categori|Categorïau|Categorïau|Categorïau|Categorïau}}',
'category_header'          => 'Erthyglau yn y categori "$1"',
'subcategories'            => 'Is-categorïau',
'category-media-header'    => "Cyfryngau yn y categori '$1'",
'category-empty'           => "''Ar hyn o bryd nid oes unrhyw erthyglau na ffeiliau yn y categori hwn.''",
'hidden-categories'        => '{{PLURAL:$1|Categori cuddiedig|Categori cuddiedig|Categorïau cuddiedig|Categorïau cuddiedig|Categorïau cuddiedig|Categorïau cuddiedig}}',
'hidden-category-category' => 'Categorïau cuddiedig', # Name of the category where hidden categories will be listed

'mainpagetext'      => "<big>'''Wedi llwyddo gosod meddalwedd Mediawiki yma'''</big>",
'mainpagedocfooter' => 'Ceir cymorth (yn Saesneg) ar ddefnyddio meddalwedd wici yn y [http://meta.wikimedia.org/wiki/Help:Contents Canllaw Defnyddwyr] ar wefan Wikimedia.

==Cychwyn arni==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Rhestr gosodiadau wrth gyflunio]
* [http://www.mediawiki.org/wiki/Manual:FAQ Cwestiynau poblogaidd ar MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Rhestr postio datganiadau MediaWiki]',

'about'          => 'Ynglŷn â',
'article'        => 'Erthygl',
'newwindow'      => '(yn agor mewn ffenest newydd)',
'cancel'         => 'Diddymu',
'qbfind'         => 'Cael',
'qbbrowse'       => 'Pori',
'qbedit'         => 'Golygu',
'qbpageoptions'  => 'Dewysiadau tudalen',
'qbpageinfo'     => 'Gwybodaeth tudalen',
'qbmyoptions'    => 'Fy dewysiadau',
'qbspecialpages' => 'Tudalennau arbennig',
'moredotdotdot'  => 'Rhagor...',
'mypage'         => 'Fy nhudalen',
'mytalk'         => 'Fy sgwrs',
'anontalk'       => 'Sgwrs ar gyfer y cyfeiriad IP hwn',
'navigation'     => 'Panel llywio',
'and'            => 'a/ac',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Gwall',
'returnto'          => 'Ewch yn ôl i $1.',
'tagline'           => 'Oddi ar {{SITENAME}}',
'help'              => 'Cymorth',
'search'            => 'Chwilio',
'searchbutton'      => 'Chwilio',
'go'                => 'Eler',
'searcharticle'     => 'Mynd',
'history'           => 'Hanes y dudalen',
'history_short'     => 'Hanes',
'updatedmarker'     => 'diwygiwyd ers i fi ymweld ddiwethaf',
'info_short'        => 'Gwybodaeth',
'printableversion'  => 'Fersiwn argraffu',
'permalink'         => 'Dolen barhaol',
'print'             => 'Argraffu',
'edit'              => 'Golygu',
'editthispage'      => 'Golygwch y dudalen hon',
'delete'            => 'Dileu',
'deletethispage'    => 'Dilëer y dudalen hon',
'undelete_short'    => 'Adfer $1 {{PLURAL:$1|golygiad|golygiad|olygiad|golygiad|golygiad|golygiad}}',
'protect'           => 'Diogelu',
'protectthispage'   => 'Amddiffynwch y tudalen hon',
'unprotect'         => 'Dad-ddiogelu',
'unprotectthispage' => 'Di-amddiffynwch y tudalen hon',
'newpage'           => 'Tudalen newydd',
'talkpage'          => "Sgwrsio amdano'r tudalen hon",
'talkpagelinktext'  => 'Sgwrs',
'specialpage'       => 'Tudalen arbennig',
'personaltools'     => 'Offer personol',
'postcomment'       => 'Postiwch esboniad',
'articlepage'       => 'Dangos tudalen yn y prif barth',
'talk'              => 'Sgwrs',
'views'             => 'Golygon',
'toolbox'           => 'Blwch offer',
'userpage'          => 'Gwyliwch tudalen defnyddiwr',
'projectpage'       => 'Gwyliwch tudalen meta',
'imagepage'         => 'Gweld tudalen y ffeil clyweled',
'viewhelppage'      => 'Dangos y dudalen gymorth',
'categorypage'      => 'Dangos tudalen gategori',
'viewtalkpage'      => 'Gwyliwch sgwrs',
'otherlanguages'    => 'Ieithoedd eraill',
'redirectedfrom'    => '(Ail-cyfeiriad oddiwrth $1)',
'redirectpagesub'   => 'Tudalen ailgyfeirio',
'lastmodifiedat'    => 'Newidiwyd y dudalen hon ddiwethaf $2, $1.', # $1 date, $2 time
'viewcount'         => "{{PLURAL:$1|Ni chafwyd dim|Cafwyd $1|Cafwyd $1|Cafwyd $1|Cafwyd $1|Cafwyd $1}} ymweliad â'r dudalen hon.",
'protectedpage'     => 'Tudalen a ddiogelwyd',
'jumpto'            => 'Neidio i:',
'jumptonavigation'  => 'llywio',
'jumptosearch'      => 'chwilio',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Ynglŷn â {{SITENAME}}',
'aboutpage'         => 'Project:Ynglŷn â {{SITENAME}}',
'bugreports'        => 'Adroddiadau diffygion',
'bugreportspage'    => 'Project:Adroddiadau diffygion',
'copyright'         => "Mae'r cynnwys ar gael o dan $1.",
'copyrightpagename' => 'Hawlfraint {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Hawlfraint',
'currentevents'     => 'Materion cyfoes',
'currentevents-url' => 'Project:Materion cyfoes',
'disclaimers'       => 'Gwadiadau',
'disclaimerpage'    => 'Project:Gwadiad Cyffredinol',
'edithelp'          => 'Help gyda golygu',
'edithelppage'      => 'Help:Golygu',
'faq'               => 'Cwestiynau cyffredin',
'faqpage'           => 'Project:Cwestiynau cyffredin',
'helppage'          => 'Help:Cymorth',
'mainpage'          => 'Hafan',
'policy-url'        => 'Project:Polisi',
'portal'            => 'Porth y Gymuned',
'portal-url'        => 'Project:Porth y Gymuned',
'privacy'           => 'Polisi preifatrwydd',
'privacypage'       => 'Project:Polisi preifatrwydd',
'sitesupport'       => 'Rhoi',
'sitesupport-url'   => 'Project:Codi_arian',

'badaccess'        => 'Gwall caniatâd',
'badaccess-group0' => 'Ni chaniateir i chi wneud y weithred y ceisiasoch amdani.',
'badaccess-group1' => "Dim ond defnyddwyr yng ngrŵp $1 sy'n cael gwneud y weithred y ceisiasoch amdani.",
'badaccess-group2' => "Dim ond defnyddwyr o blith y grwpiau $1 sy'n cael gwneud y weithred y ceisiasoch amdani.",
'badaccess-groups' => "Dim ond defnyddwyr o blith y grwpiau $1 sy'n cael gwneud y weithred y ceisiasoch amdani.",

'versionrequired' => 'Mae angen fersiwn $1 y meddalwedd MediaWiki',

'ok'                      => 'Iawn',
'retrievedfrom'           => 'Wedi dod o "$1"',
'youhavenewmessages'      => 'Mae gennych chi $1 ($2).',
'newmessageslink'         => 'Neges(eueon) newydd',
'newmessagesdifflink'     => 'y newid diweddaraf',
'youhavenewmessagesmulti' => 'Mae negeseuon newydd gennych ar $1',
'editsection'             => 'golygu',
'editold'                 => 'golygu',
'editsectionhint'         => "Golygu'r adran: $1",
'toc'                     => 'Taflen Cynnwys',
'showtoc'                 => 'dangos',
'hidetoc'                 => 'cuddio',
'thisisdeleted'           => 'Edrychwch at, neu atgyweirio $1?',
'viewdeleted'             => 'Gweld $1?',
'restorelink'             => "$1 {{PLURAL:$1|golygiad sydd wedi'i ddileu|golygiad sydd wedi'i ddileu|olygiad sydd wedi'u dileu|golygiad sydd wedi'u dileu|golygiad sydd wedi'u dileu|golygiad sydd wedi'u dileu}}",
'feedlinks'               => 'Porthiant:',
'site-rss-feed'           => 'Porthiant RSS $1',
'site-atom-feed'          => 'Porthiant Atom $1',
'page-rss-feed'           => "Porthiant RSS '$1'",
'red-link-title'          => '$1 (heb ei greu eto)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Erthygl',
'nstab-user'      => 'Tudalen defnyddiwr',
'nstab-media'     => 'Tudalen cyfrwng',
'nstab-special'   => 'Arbennig',
'nstab-project'   => 'Tudalen y wici',
'nstab-image'     => 'Ffeil',
'nstab-mediawiki' => 'Neges',
'nstab-template'  => 'Nodyn',
'nstab-help'      => 'Cymorth',
'nstab-category'  => 'Categori',

# Main script and global functions
'nosuchaction'      => 'Does dim gweithred',
'nosuchactiontext'  => "Dydy'r meddalwedd Mediawiki ddim yn deallt y gweithrediad mae'r URL yn gofyn iddo fe gwneud",
'nosuchspecialpage' => 'Does dim tudalen arbennig',
'nospecialpagetext' => "Yr ydych wedi gofyn am tudalen arbennig dydy'r meddalwedd Mediawiki ddim yn adnabod.",

# General errors
'error'                => 'Gwall',
'databaseerror'        => 'Gwall databas',
'dberrortext'          => 'Mae gwall cystrawen wedi taro\'r databas.
Efallai fod gwall yn y meddalwedd.
Y gofyniad olaf y trïodd y databas oedd:
<blockquote><tt>$1</tt></blockquote>
o\'r ffwythiant "<tt>$2</tt>".
Rhoddwyd y côd gwall "<tt>$3: $4</tt>" gan MySQL.',
'dberrortextcl'        => 'Mae gwall cystrawen wedi taro\'r databas.
Y gofyniad olaf y trïodd y databas oedd:
"$1"
o\'r ffwythiant "$2".
Rhoddwyd y côd gwall "$3: $4<" gan MySQL.',
'noconnect'            => "Mae'n ddrwg gennym ni! Oherwydd anawsterau technegol, nid yw'r wici yn gallu cysylltu â gweinydd y databas. <br />
$1",
'nodb'                 => 'Ddim yn gallu dewis y databas $1',
'cachederror'          => "Codwyd y copi hwn o'r dudalen y gofynasoch amdani o gelc; efallai nad yw hi'n gyfamserol.",
'laggedslavemode'      => "Rhybudd: hwyrach nad yw'r dudalen yn cynnwys diwygiadau diweddar.",
'readonly'             => 'Databas ar gloi',
'enterlockreason'      => "Rhowch eglurhad dros gloi'r databas, ac amcangyfrif hyd at pa bryd y bydd y databas dan glo",
'readonlytext'         => "Mae'r databas {{SITENAME}} wedi eu cloi yn erbyn erthyglau newydd ac adnewidiadau eraill, yn tebygol am gofalaeth trefn y databas -- fydd y databas yn ôl cyn bo hir.
Mae'r gweinyddwr wedi dweud yr achos cloi'r databas oedd:


$1",
'missingarticle'       => 'Dylai\'r databas fod wedi dod o hyd i destun yr erthygl "$1", ond mae wedi methu.

Fel arfer, fe ddigwydd hyn pan yn dilyn trywydd hen gyswllt gwahaniaeth neu hanes sy\'n arwain at dudalen sydd wedi ei dileu.

Os nad hyn sydd ar fai, gallasech fod wedi darganfod nam ar y meddalwedd.
Adroddwch y broblem i weinyddwr os gwelwch yn dda, gan roi\'r cyfeiriad gwe (URL).',
'readonly_lag'         => "Mae'r databas wedi'i gloi'n awtomatig tra bod y gwas-weinyddion yn unionu gyda'r prif weinydd",
'internalerror'        => 'Gwall mewnol',
'internalerror_info'   => 'Gwall mewnol: $1',
'filecopyerror'        => 'Wedi methu copïo\'r ffeil "$1" i "$2".',
'filerenameerror'      => "Wedi methu ail-enwi'r ffeil '$1' yn '$2'.",
'filedeleteerror'      => 'Wedi methu dileu\'r ffeil "$1".',
'directorycreateerror' => 'Wedi methu creu\'r cyfeiriadur "$1".',
'filenotfound'         => "Heb gael hyd i'r ffeil '$1'.",
'fileexistserror'      => 'Nid oes modd ysgrifennu i\'r ffeil "$1": ffeil eisoes ar glawr',
'unexpected'           => 'Gwerth annisgwyl: "$1"="$2".',
'formerror'            => 'Gwall: Wedi methu danfon y ffurflen',
'badarticleerror'      => "Mae'n amhosib cyflawni'r weithred hon ar y dudalen hon.",
'cannotdelete'         => "Mae'n amhosib dileu'r dudalen neu'r ddelwedd hon. (Efallai fod rhywun arall eisoes wedi'i dileu).",
'badtitle'             => 'Teitl gwael',
'badtitletext'         => "Mae'r teitl a ofynnwyd amdano yn annilys, yn wag, neu cysylltu'n anghywir rhwng ieithoedd neu wicïau. Gall fod ynddo un nod neu ragor na ellir eu defnyddio mewn teitlau.",
'perfdisabled'         => "Ymddiheurwn! Mae'r nodwedd hon wedi'i analluogi dros dro gan ei bod yn ormod o dreth ar y databas.",
'perfcached'           => "Mae'r wybodaeth canlynol yn gopi cadw ac yn debygol o fod yn anghyflawn.",
'perfcachedts'         => 'Rhoddwyd y data canlynol ar gadw mewn celc a ddiweddarwyd ddiwethaf am $1.',
'viewsource'           => 'Gwyliwch y ffynhonnell',
'viewsourcefor'        => 'ar gyfer $1',
'protectedpagetext'    => "Mae'r dudalen hon wedi'i diogelu rhag cael ei golygu.",
'viewsourcetext'       => 'Cewch weld a chopïo côd y dudalen:',
'protectedinterface'   => 'Testun ar gyfer rhyngwyneb y wici yw cynnwys y dudalen hon. Clowyd y dudalen er mwyn ei diogeli.',
'editinginterface'     => "'''Dalier sylw:''' Rydych yn golygu tudalen sy'n rhan o destun rhyngwyneb y meddalwedd. Bydd newidiadau i'r dudalen hon yn effeithio ar y rhyngwyneb a ddefnyddir gan eraill. Os am gyfieithu'r neges, ystyriwch ddefnyddio [http://translatewiki.net/wiki/Main_Page?setlang=cy Betawiki], sef y prosiect MediaWiki sy'n hyrwyddo creu wicïau amlieithog.",
'sqlhidden'            => '(cuddiwyd chwiliad SQL)',
'cascadeprotected'     => "Gwarchodwyd y dudalen hon rhag ei newid, oherwydd ei bod wedi ei chynnwys yn y {{PLURAL:$1|dudalen ganlynol|dudalen ganlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol}}, a {{PLURAL:$1|honno yn ei thro wedi ei|honno yn ei thro wedi ei|rheiny yn eu tro wedi eu|rheiny yn eu tro wedi eu|rheiny yn eu tro wedi eu|rheiny yn eu tro wedi eu}} gwarchod, a'r dewisiad 'sgydol' ynghynn:
$2",
'namespaceprotected'   => "Nid oes caniatâd gennych i olygu tudalennau yn y parth '''$1'''.",
'customcssjsprotected' => "Nid oes caniatad ganddoch i olygu'r dudalen hon oherwydd bod gosodiadau personol defnyddiwr arall arno.",

# Login and logout pages
'logouttitle'                => "Allgofnodi'r defnyddiwr",
'logouttext'                 => '<strong>Rydych wedi allgofnodi.</strong><br />
Gallwch ddefnyddio {{SITENAME}} yn anhysbys, neu fe allwch fewngofnodi eto wrth yr un un enw neu wrth enw arall. Sylwer y bydd rhai tudalennau yn parhau i ymddangos fel ag yr oeddent pan oeddech wedi mewngofnodi hyd nes i chi glirio celc eich porwr.',
'welcomecreation'            => '==Croeso, $1!==

Mae eich accownt wedi gael eu creu. Peidiwch ac anghofio i personaleiddio eich ffafraethau defnyddwr {{SITENAME}}.',
'loginpagetitle'             => 'Mewngofnodi',
'yourname'                   => 'Eich enw defnyddwr',
'yourpassword'               => 'Eich allweddair',
'yourpasswordagain'          => 'Ail-teipiwch allweddair',
'remembermypassword'         => 'Cofiwch fy allweddair dros mwy nag un sesiwn.',
'yourdomainname'             => 'Eich parth',
'externaldberror'            => "Naill ai: cafwyd gwall dilysu allanol ar databas neu: ar y llaw arall efallai nad oes hawl gennych chi i ddiwygio'ch cyfrif allanol.",
'loginproblem'               => '<b>Ni lwyddodd y mewngofnodi.</b><br />Ceisiwch eto!',
'login'                      => 'Mewngofnodi',
'loginprompt'                => "Mae'n rhaid galluogi cwcis er mwyn mewngofnodi i {{SITENAME}}.",
'userlogin'                  => 'Mewngofnodi',
'logout'                     => 'Allgofnodi',
'userlogout'                 => 'Allgofnodi',
'notloggedin'                => 'Nid wedi mewngofnodi',
'nologin'                    => 'Dim enw defnyddiwr gennych? $1.',
'nologinlink'                => 'Crëwch gyfrif',
'createaccount'              => 'Creu cyfrif newydd',
'gotaccount'                 => 'Oes cyfrif gennych eisoes? $1.',
'gotaccountlink'             => 'Mewngofnodwch',
'createaccountmail'          => 'trwy e-bost',
'badretype'                  => "Nid yw'r cyfrineiriau'n union yr un fath.",
'userexists'                 => 'Mae rhywun arall wedi dewis yr enw defnyddwr. Dewiswch un arall os gwelwch yn dda.',
'youremail'                  => 'Eich cyfeiriad e-bost',
'username'                   => 'Enw defnyddiwr:',
'uid'                        => 'ID Defnyddiwr:',
'yourrealname'               => 'Eich enw cywir*',
'yourlanguage'               => 'Iaith rhyngwyneb',
'yourvariant'                => 'Amrywiad',
'yournick'                   => 'Eich llysenw (fel llofnod)',
'badsig'                     => 'Llofnod crai annilys; gwiriwch y tagiau HTML.',
'badsiglength'               => "Mae'r llysenw'n rhy hir; rhaid iddo fod yn llai na $1 llythyren o hyd.",
'email'                      => 'E-bost',
'prefs-help-realname'        => '* Enw iawn (dewisol): Os ydych yn dewis ei roi, fe fydd yn cael ei defnyddio er mwyn rhoi cydnabyddiaeth i chi am eich gwaith.',
'loginerror'                 => 'Problem mewngofnodi',
'prefs-help-email'           => "* E-bost (dewisol): Mae'n galluogi eraill i gysylltu â chi trwy eich tudalen defnyddiwr neu dudalen sgwrs, heb ddatguddio eich manylion personol.",
'nocookiesnew'               => "Mae'r cyfrif defnyddiwr wedi cael ei greu, ond nid ydych wedi mewngofnodi. Mae {{SITENAME}} yn defnyddio cwcis wrth i ddefnyddwyr fewngofnodi. Rydych chi wedi analluogi cwcis. Mewngofnodwch eto gyda'ch enw defnyddiwr a'ch cyfrinair newydd os gwelwch yn dda, ar ôl galluogi cwcis.",
'nocookieslogin'             => 'Mae {{SITENAME}} yn defnyddio cwcis wrth i ddefnyddwyr fewngofnodi. Rydych chi wedi analluogi cwcis. Trïwch eto os gwelwch yn dda, ar ôl galluogi cwcis.',
'noname'                     => 'Dydych chi ddim wedi cynnig enw defnyddiwr dilys.',
'loginsuccesstitle'          => 'Llwyddodd y mewngofnodi',
'loginsuccess'               => "'''Yr ydych wedi mewngofnodi i {{SITENAME}} wrth yr enw \"\$1\".'''",
'nosuchuser'                 => 'Does dim defnyddwr gyda\'r enw "$1".
Sicrhau rydych chi wedi sillafu\'n iawn, neu creuwch accownt newydd gyda\'r ffurflen isod.',
'nosuchusershort'            => 'Does dim defnyddiwr o\'r enw "<nowiki>$1</nowiki>". Gwiriwch eich sillafu.',
'nouserspecified'            => "Mae'n rhaid nodi enw defnyddiwr.",
'wrongpassword'              => "Mae'r allweddair rydych wedi teipio ddim yn cywir. Triwch eto, os gwelwch yn dda.",
'wrongpasswordempty'         => 'Roedd y cyfrinair yn wag. Rhowch gynnig arall arni.',
'passwordtooshort'           => "Mae eich cyfrinair yn rhy fyr. Mae'n rhaid cynnwys o leia $1 nôd.",
'mailmypassword'             => 'Anfoner cyfrinair newydd ataf trwy e-bost',
'passwordremindertitle'      => 'Nodyn atgoffa allweddair oddiwrth {{SITENAME}}',
'passwordremindertext'       => "Mae rhywun (chi mwy na thebyg, o'r cyfeiriad IP $1) wedi gofyn i ni anfon cyfrinair newydd ar gyfer {{SITENAME}} atoch ($4).
Mae cyfrinair y defnyddiwr '$2' wedi'i newid i '$3'. Dylid mewngofnodi a'i newid cyn gynted â phosib.

Os mai rhywun arall a holodd am y cyfrinair, ynteu eich bod wedi cofio'r hen gyfrinair, ac nac ydych am newid y cyfrinair, rhydd i chi anwybyddu'r neges hon a pharhau i ddefnyddio'r hen un.",
'noemail'                    => "Does dim cyfeiriad e-bost yng nghofnodion y defnyddiwr '$1'.",
'passwordsent'               => 'Mae allweddair newydd wedi gael eu ddanfon at y cyfeiriad e-bost cofrestredig am "$1". Mewngofnodwch eto, os gwelwch yn dda, ar ol i chi dderbyn yr allweddair.',
'eauthentsent'               => 'Anfonwyd e-bost o gadarnhâd at y cyfeiriad a benwyd. 
Cyn y gellir anfon unrhywbeth arall at y cyfeiriad hwnnw rhaid i chi ddilyn y cyfarwyddiadau yn yr e-bost hwnnw er mwyn cadarnhau bod y cyfeiriad yn un dilys.',
'mailerror'                  => 'Gwall wrth ddanfon e-bost: $1',
'acct_creation_throttle_hit' => 'Rydych chi wedi creu $1 cyfrif yn barod. Ni chewch greu rhagor.',
'emailauthenticated'         => 'Cadarnhawyd eich cyfeiriad e-bost ar $1.',
'emailnotauthenticated'      => "Nid yw eich cyfeiriad e-bost wedi'i ddilysu eto. Ni fydd unrhyw negeseuon e-bost yn cael eu hanfon atoch ar gyfer y nodweddion canlynol.",
'noemailprefs'               => "<strong>Mae'n rhaid i chi gynnig cyfeiriad e-bost er mwyn i'r nodweddion hyn weithio.</strong>",
'emailconfirmlink'           => 'Cadarnhewch eich cyfeiriad e-bost',
'invalidemailaddress'        => 'Ni allwn dderbyn y cyfeiriad e-bost gan fod ganddo fformat annilys. Mewnbynnwch cyfeiriad dilys neu gwagiwch y maes hwnnw, os gwelwch yn dda.',
'accountcreated'             => 'Crëwyd y cyfrif',
'accountcreatedtext'         => 'Crëwyd cyfrif defnyddiwr ar gyfer $1.',
'createaccount-title'        => 'Creu cyfrif ar {{SITENAME}}',
'createaccount-text'         => 'Creodd rhywun gyfrif o\'r enw $2 ar {{SITENAME}} ($4) ar gyfer y cyfeiriad e-bost hwn. "$3" yw\'r cyfrinair ar gyfer "$2". Dylech fewngofnodi a newid eich cyfrinair yn syth.

Rhydd ichi anwybyddu\'r neges hon os mai camgymeriad oedd creu\'r cyfrif.',

# Edit page toolbar
'bold_sample'     => 'Testun cryf',
'bold_tip'        => 'Testun cryf',
'italic_sample'   => 'Testun italig',
'italic_tip'      => 'Testun italig',
'link_sample'     => 'Teitl y cyswllt',
'link_tip'        => 'Cyswllt mewnol',
'extlink_sample'  => 'http://www.example.com cyswllt teitl',
'extlink_tip'     => 'Cyswllt allanol (cofiwch y rhagddodiad http:// )',
'headline_sample' => 'Testun pennawd',
'headline_tip'    => 'Pennawd lefel 2',
'math_sample'     => 'Gosodwch fformwla yma',
'math_tip'        => 'Fformwla mathemategol (LaTeX)',
'nowiki_sample'   => 'Mewnosodwch testun di-fformatedig yma',
'nowiki_tip'      => 'Anwybyddwch fformatiaeth wiki',
'image_sample'    => 'Enghraifft.jpg',
'image_tip'       => 'Ffeil mewnosodol',
'media_sample'    => 'Example.mp3',
'media_tip'       => 'Cyswllt ffeil media',
'sig_tip'         => 'Eich llofnod gyda stamp amser',
'hr_tip'          => "Llinell lorweddol (peidiwch â'i gor-ddefnyddio)",

# Edit pages
'summary'                   => 'Crynodeb',
'subject'                   => 'Testun/pennawd',
'minoredit'                 => 'Golygiad bychan yw hwn',
'watchthis'                 => 'Gwyliwch erthygl hon',
'savearticle'               => 'Cadw tudalen',
'preview'                   => 'Rhagolwg',
'showpreview'               => 'Dangos rhagolwg',
'showlivepreview'           => 'Rhagolwg byw',
'showdiff'                  => 'Dangos newidiadau',
'anoneditwarning'           => "'''Dalier sylw''': Nid ydych wedi mewngofnodi. Fe fydd eich cyfeiriad IP yn ymddangos ar hanes golygu'r dudalen hon. Gallwch ddewis cuddio'ch cyfeiriad IP drwy greu cyfrif (a mewngofnodi) cyn golygu.",
'missingsummary'            => "'''Sylwer:''' Nid ydych wedi gosod nodyn yn y blwch 'Crynodeb'.
Os y pwyswch eto ar 'Cadw'r dudalen' caiff y golygiad ei gadw heb nodyn.",
'missingcommenttext'        => 'Rhowch eich sylwadau isod.',
'missingcommentheader'      => "'''Nodyn:''' Nid ydych wedi cynnig unrhywbeth yn y blwch 'Pwnc/Pennawd:'. Os y cliciwch 'Cadw'r dudalen' eto fe gedwir y golygiad heb bennawd.",
'summary-preview'           => "Rhagolwg o'r crynodeb",
'subject-preview'           => 'Rhagolwg pwnc/pennawd',
'blockedtitle'              => "Mae'r defnyddiwr hwn wedi cael ei flocio",
'blockedtext'               => "<big>'''Mae eich enw defnyddiwr neu gyfeiriad IP wedi cael ei flocio gan $1.'''</big> 

Y rheswm a roddwyd dros y blocio yw: ''$2''.

*Dechreuodd y bloc am: $8
*Bydd y bloc yn dod i ben am: $6
*Bwriadwyd blocio: $7

Gallwch gysylltu â $1 neu un arall o'r [[{{MediaWiki:Grouppage-sysop}}|gweinyddwyr]] i drafod y bloc.

Sylwch mai dim ond y rhai sydd wedi gosod cyfeiriad e-bost yn eu [[Special:Preferences|dewisiadau defnyddiwr]], a hwnnw heb ei flocio, sydd yn gallu 'anfon e-bost at ddefnyddiwr' trwy'r wici.
$3 yw eich cyfeiriad IP. Cyfeirnod y bloc yw #$5. Pan yn ysgrifennu at weinyddwr, cofiwch gynnwys naill ai eich cyfeiriad neu gyfeirnod y bloc, neu'r ddau, os gwelwch yn dda.",
'autoblockedtext'           => "Rhoddwyd bloc yn awtomatig ar eich cyfeiriad IP oherwydd iddo gael ei ddefnyddio gan ddefnyddiwr arall, a bod bloc wedi ei roi ar hwnnw gan $1.
Y rheswm a roddwyd dros y bloc oedd:

:''$2''

*Dechreuodd y bloc am: $8
*Bydd y bloc yn dod i ben am: $6

Gallwch gysylltu â $1 neu un arall o'r [[{{MediaWiki:Grouppage-sysop}}|gweinyddwyr]] i drafod y bloc.

Sylwch mai dim ond y rhai sydd wedi gosod cyfeiriad e-bost yn eu [[Special:Preferences|dewisiadau defnyddiwr]], a hwnnw heb ei flocio, sydd yn gallu 'anfon e-bost at ddefnyddiwr' trwy'r wici.

Cyfeirnod y bloc yw $5. Nodwch hwn wrth drafod y bloc.",
'whitelistedittitle'        => 'Rhaid mewngofnodi i golygu',
'whitelistedittext'         => 'Rhaid $1 i olygu tudalennau.',
'whitelistreadtitle'        => 'Rhaid mewngofnodi i ddarllen',
'whitelistreadtext'         => 'Rhaid i chi [[Special:Userlogin|mewngofnodi]] i ddarllen erthyglau.',
'whitelistacctitle'         => 'Ni chaniateir creu accownt',
'whitelistacctext'          => 'I gael caniatâd i creu accownt yn y wiki hon, rhaid i chi [[Special:Userlogin|mewngofnodi]] a chael y caniatâd priodol.',
'loginreqtitle'             => 'Mae angen mewngofnodi',
'loginreqlink'              => 'mewngofnodi',
'loginreqpagetext'          => "Mae'n rhaid $1 er mwyn gweld tudalennau eraill.",
'accmailtitle'              => 'Wedi danfon cyfrinair.',
'accmailtext'               => 'Anfonwyd cyfrinair "$1" at $2.',
'newarticle'                => '(Newydd)',
'newarticletext'            => "Rydych chi wedi dilyn cysylltiad i dudalen sydd heb gael ei chreu eto.
I greu'r dudalen, dechreuwch deipio yn y blwch isod 
(gweler y [[{{MediaWiki:Helppage}}|dudalen gymorth]] am fwy o wybodaeth).
Os daethoch yma ar ddamwain, cliciwch botwm '''n&ocirc;l''' y porwr.",
'anontalkpagetext'          => "---- ''Dyma dudalen sgwrs defnyddiwr sydd heb greu cyfrif, neu nad yw'n defnyddio'i gyfrif. Mae'n rhaid i ni ddefnyddio'r cyfeiriad IP i'w (h)adnabod. Mae'n bosib fod sawl defnyddiwr yn rhannu'r un cyfeiriad IP. Os ydych chi'n ddefnyddiwr anhysbys ac yn teimlo'ch bod wedi derbyn sylwadau amherthnasol, [[Special:Userlogin|crëwch gyfrif neu mewngofnodwch]] i osgoi dryswch gyda defnyddwyr anhysbys yn y dyfodol.''",
'noarticletext'             => "Mae'r dudalen hon yn wag. Gallwch [[Special:Search/{{PAGENAME}}|chwilio am y teitl hwn]] ar dudalennau eraill neu [{{fullurl:{{FULLPAGENAME}}|action=edit}} golygu'r dudalen].",
'clearyourcache'            => "'''Sylwer:''' Wedi i chi roi'r dudalen ar gadw, efallai y bydd angen mynd heibio celc eich porwr er mwyn gweld y newidiadau. '''Mozilla / Firefox / Safari:''' pwyswch ar ''Shift'' tra'n clicio ''Ail-lwytho/Reload'', neu gwasgwch ''Ctrl-Shift-R'' (''Cmd-Shift-R'' ar Apple Mac); '''IE:''' pwyswch ar ''Ctrl'' tra'n clicio ''Adnewyddu/Refresh'', neu gwasgwch ''Ctrl-F5''; '''Konqueror:''': cliciwch y botwm ''Ail-lwytho/Reload'', neu gwasgwch ''F5''; '''Opera''': efallai y bydd angen gwacau'r celc yn llwyr yn ''Offer→Dewisiadau / Tools→Preferences''.",
'usercssjsyoucanpreview'    => "<strong>Tip:</strong> Defnyddiwch y botwm 'Dangos rhagolwg' er mwyn profi eich CSS/JS newydd cyn ei gadw.",
'usercsspreview'            => "'''Cofiwch -- dim ond rhagolwg o'ch CSS defnyddiwr yw hwn; nid yw wedi'i gadw eto!'''",
'userjspreview'             => "'''Cofiwch -- dim ond rhagolwg o'ch JavaScript yw hwn; nid yw wedi'i gadw eto!'''",
'updated'                   => '(Diweddariad)',
'note'                      => '<strong>Sylwch:</strong>',
'previewnote'               => "'''Cofiwch taw rhagolwg yw hwn; nid yw'r dudalen wedi ei chadw eto.'''",
'previewconflict'           => "Mae'r rhagolwg hwn yn dangos y testun yn yr ardal golygu uchaf, fel ag y byddai'n ymddangos petaech yn rhoi'r dudalen ar gadw.",
'session_fail_preview'      => "<strong>Ymddiheurwn! Methwyd prosesu eich golygiad gan fod rhan o ddata'r sesiwn wedi'i golli. Ceisiwch eto. Os digwydd yr un peth eto, ceisiwch allgofnodi ac yna mewngofnodi eto.</strong>",
'session_fail_preview_html' => "<strong>Ymddiheurwn! Methwyd prosesu eich golygiad gan fod rhan o ddata'r sesiwn wedi'i golli.</strong>

''Oherwydd bod HTML amrwd ar waith ar {{SITENAME}}, cuddir y rhagolwg er mwyn gochel rhag ymosodiad JavaScript.''

<strong>Os ydych am wneud golygiad dilys, ceisiwch eto. Os methwch unwaith eto, ceisiwch allgofnodi ac yna mewngofnodi unwaith eto.</strong>",
'editing'                   => 'Yn golygu $1',
'editingsection'            => 'Yn golygu $1 (adran)',
'editingcomment'            => 'Yn golygu $1 (esboniad)',
'editconflict'              => 'Gwrthdaro golygyddol: $1',
'explainconflict'           => "Mae rhywun arall wedi newid y dudalen hon ers i chi ddechrau ei golygu hi. Mae'r ardal testun uchaf yn cynnwys testun y dudalen fel y mae hi rwan. Mae eich newidiadau chi yn ymddangos yn yr ardal testun isaf.<br />
Bydd yn rhaid i chi gyfuno eich newidiadau chi a'r testun sydd yn bodoli eisioes.
<b>Dim ond</b> y testun yn yr ardal testun <b>uchaf</b> fydd yn cael ei roi ar gadw pan wasgwch y botwm \"Cadw'r dudalen\".<br />",
'yourtext'                  => 'Eich testun',
'storedversion'             => 'Fersiwn wedi cadw',
'nonunicodebrowser'         => '<strong>RHYBUDD: Nid yw eich porwr yn cydymffurfio ag Unicode. Pan fyddwch yn golygu erthyglau, bydd nodau sydd ddim yn ran o ASCII yn ymddangos yn y blwch golygu fel codau hecsadegol.</strong>',
'editingold'                => "<strong>RHYBUDD: Rydych chi'n golygu hen ddiwygiad o'r dudalen hon.<br />Os caiff ei chadw, bydd unrhyw newidiadau diweddarach yn cael eu colli!</strong>",
'yourdiff'                  => 'Gwahaniaethau',
'copyrightwarning'          => "Mae pob cyfraniad i {{SITENAME}} yn cael ei ryddhau o dan termau'r Drwydded Ddogfen Rhydd ($2) (gwelwch $1 am fanylion). Os nad ydych chi'n fodlon i'ch gwaith gael ei olygu heb drugaredd, neu i gopïau ymddangos ar draws y we, peidiwch a'i gyfrannu yma.<br />
Rydych chi'n cadarnhau mai chi yw awdur y cyfraniad, neu eich bod chi wedi'i gopïo o'r parth cyhoeddus (''public domain'') neu rywle rhydd tebyg. '''Nid''' yw'r mwyafrif o wefannau yn y parth cyhoeddus.

<strong>PEIDIWCH Â CHYFRANNU GWAITH O DAN HAWLFRAINT HEB GANIATÂD!</strong>",
'copyrightwarning2'         => "Sylwch fod pob cyfraniad i {{SITENAME}} yn cael ei ryddhau o dan termau'r Drwydded Ddogfen Rhydd ($2) (gwelwch $1 am fanylion).
Os nad ydych chi'n fodlon i'ch gwaith gael ei olygu heb drugaredd, neu i gopïau ymddangos ar draws y we, peidiwch a'i gyfrannu yma.<br />
Rydych chi'n cadarnhau mai chi yw awdur y cyfraniad, neu eich bod chi wedi'i gopïo o'r parth cyhoeddus (''public domain'') neu rywle rhydd tebyg.<br />
<strong>PEIDIWCH Â CHYFRANNU GWAITH O DAN HAWLFRAINT HEB GANIATÂD!</strong>",
'longpagewarning'           => "<strong>RHYBUDD: Mae'r dudalen hon yn $1 cilobeit o hyd; mae rhai porwyr yn cael trafferth wrth lwytho tudalennau sy'n hirach na 32kb.
Byddai'n dda o beth llunio sawl tudalen llai o hyd o ddeunydd y dudalen hon.</strong>",
'readonlywarning'           => "<strong>RHYBUDD: Mae'r databas wedi cloi i gael eu trwsio,
felly fyddwch chi ddim yn medru cadw eich olygiadau rwan. Efalle fyddwch chi'n eisio tori-a-pastio'r
testun i mewn i ffeil testun, a cadw hi tan hwyrach.</strong>",
'protectedpagewarning'      => "<strong>RHYBUDD: Mae'r dudalen hon wedi'i diogelu. Dim ond gweinyddwyr sydd yn gallu ei golygu.</strong>",
'cascadeprotectedwarning'   => "'''Dalier sylw:''' Mae'r dudalen hon wedi ei gwarchod fel nad ond defnyddwyr â galluoedd gweinyddwyr sy'n gallu ei newid, oherwydd ei bod yn rhan o'r {{PLURAL:$1|dudalen ganlynol|dudalen ganlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol|tudalennau canlynol}} sydd wedi {{PLURAL:$1|ei|ei|eu|eu|eu|eu}} sgydol-gwarchod.",
'templatesused'             => 'Nodiadau a ddefnyddir yn y dudalen hon:',
'templatesusedpreview'      => 'Nodiadau a ddefnyddir yn y rhagolwg hwn:',
'template-protected'        => '(wedi ei diogelu)',
'template-semiprotected'    => '(lled-diogelwyd)',
'hiddencategories'          => "Mae'r dudalen hon yn aelod o $1 {{PLURAL:$1|categori|categori|gategori|chategori|chategori|categori}} cuddiedig:",
'nocreatetitle'             => 'Cyfyngwyd ar greu tudalennau',
'nocreatetext'              => "Mae'r safle hwn wedi cyfyngu'r gallu i greu tudalennau newydd. Gallwch olygu tudalen sydd eisoes yn bodoli, neu [[Special:Userlogin|fewngofnodi, neu greu cyfrif]].",
'nocreate-loggedin'         => "Nid yw'r gallu gennych i greu tudalennau ar {{SITENAME}}.",
'permissionserrorstext'     => "Nid yw'r gallu ganddoch i wneud hynna, oherwydd y {{PLURAL:$1|rheswm|rheswm|rhesymau|rhesymau|rhesymau|rhesymau}} canlynol:",
'recreate-deleted-warn'     => "'''Dalier sylw: Rydych yn ail-greu tudalen a ddilewyd rhywdro.'''

Ystyriwch a fyddai'n dda o beth i barhau i olygu'r dudalen hon.
Dyma lòg dileu'r dudalen, er gwybodaeth:",

# "Undo" feature
'undo-failure' => 'Methwyd a dadwneud y golygiad oherwydd gwrthdaro â golygiadau cyfamserol.',

# Account creation failure
'cantcreateaccount-text' => "Rhwystrwyd y gallu i greu cyfrif ar gyfer y cyfeiriad IP hwn, (<b>$1</b>), gan [[User:$3|$3]].

Y rheswm a roddwyd dros y bloc gan $3 yw ''$2''.",

# History pages
'viewpagelogs'        => "Dangos logiau'r dudalen hon",
'nohistory'           => "Does dim hanes golygu i'r dudalen hon.",
'revnotfound'         => 'Cywiriad nid wedi darganfod',
'revnotfoundtext'     => 'Ni ellir darganfod yr hen cywiriad y tudalen rydych wedi gofyn amdano. Gwiriwch yr URL rydych wedi defnyddio i darganfod y tudalen hon.',
'loadhist'            => 'Yn llwytho hanes y dudalen',
'currentrev'          => 'Diwygiad cyfoes',
'revisionasof'        => 'Diwygiad $1',
'revision-info'       => 'Y fersiwn a roddwyd ar gadw am $1 gan $2',
'previousrevision'    => '← at y diwygiad blaenorol',
'nextrevision'        => 'At y diwygiad dilynol →',
'currentrevisionlink' => 'Y diwygiad cyfoes',
'cur'                 => 'cyf',
'next'                => 'nesaf',
'last'                => 'cynt',
'orig'                => 'gwreidd',
'page_first'          => 'cyntaf',
'page_last'           => 'olaf',
'histlegend'          => "Cymharu dau fersiwn: marciwch y cylchoedd ar y ddau fersiwn i'w cymharu, yna pwyswch ar 'return' neu'r botwm 'Cymharer y fersiynau dewisedig'.<br />
Eglurhad: (cyf.) = gwahaniaethau rhyngddo a'r fersiwn cyfredol,
(cynt) = gwahaniaethau rhyngddo a'r fersiwn cynt, B = golygiad bychan",
'deletedrev'          => '[dilëwyd]',
'histfirst'           => 'Cynharaf',
'histlast'            => 'Diweddaraf',
'historysize'         => '({{PLURAL:$1|$1 beit|$1 beit|$1 feit|$1 beit|$1 beit|$1 beit}})',
'historyempty'        => '(gwag)',

# Revision feed
'history-feed-item-nocomment' => '$1 am $2', # user at time

# Revision deletion
'revdelete-selected'  => "Y {{PLURAL:$2|golygiad|golygiad|golygiadau|golygiadau|golygiadau|golygiadau}} dewisedig o '''$1:'''",
'logdelete-selected'  => "{{PLURAL:$2|Digwyddiad|Digwyddiad|Digwyddiadau|Digwyddiadau|Digwyddiadau|Digwyddiadau}} dewisedig y lòg ar gyfer '''$1:'''",
'logdelete-logentry'  => 'newidiwyd y gallu i weld y digwyddiad [[$1]]',
'revdelete-logaction' => "Gosodwyd $1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}} i'r modd $2",
'logdelete-logaction' => "$1 {{PLURAL:$1|digwyddiad|digwyddiad|ddigwyddiad|digwyddiad|digwyddiad|digwyddiad}} i [[$3]] {{PLURAL:$1|wedi'i osod|wedi'i osod|wedi'u gosod|wedi'u gosod|wedi'u gosod|wedi'u gosod}} i'r modd $2",
'logdelete-success'   => "Llwyddwyd i guddio'r digwyddiad.",

# Oversight log
'oversightlog' => 'Lòg arolygiaeth',

# History merging
'mergehistory'                     => 'Cyfuno hanesion y tudalennau',
'mergehistory-header'              => "Pwrpas y dudalen hon yw cyfuno diwygiadau o hanes un dudalen gwreiddiol ar dudalen newydd.
Pan yn gwneud hyn dylid sicrhau nad yw dilyniant hanes tudalennau yn cael ei ddifetha.

'''Rhaid cadw diwygiad presennol y dudalen gwreiddiol o leiaf.'''",
'mergehistory-box'                 => "Cyfuno'r diwygiadau o ddwy dudalen:",
'mergehistory-from'                => 'Y dudalen wreiddiol:',
'mergehistory-into'                => 'Y dudalen cyrchfan:',
'mergehistory-list'                => 'Hanes diwygiadau y gellir eu cyfuno',
'mergehistory-merge'               => "Gellir cyfuno'r diwygiadau canlynol o [[:$1]] i'r dudalen [[:$2]]. Defnyddiwch y botymau radio i gyfuno dim ond y diwygiadau a grewyd hyd at yr amser penodedig. Sylwch y bydd y golofn botwm radio yn cael ei hail-osod pan ddefnyddir y cysylltau llywio.",
'mergehistory-go'                  => 'Dangos y golygiadau y gellir eu cyfuno',
'mergehistory-submit'              => 'Cyfuner y diwygiadau',
'mergehistory-success'             => "Cyfunwyd $3 {{PLURAL:$3|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}} o [[:$1]] yn llwyddiannus i'r dudalen [[:$2]].",
'mergehistory-fail'                => "Methodd y cyfuno hanes; a wnewch wirio paramedrau'r dudalen a'r amser unwaith eto.",
'mergehistory-no-source'           => "Nid yw'r dudalen gwreiddiol $1 yn bod.",
'mergehistory-no-destination'      => "Nid yw'r dudalen cyrchfan $1 yn bod.",
'mergehistory-invalid-source'      => 'Rhaid bod teitl dilys gan y dudalen gwreiddiol.',
'mergehistory-invalid-destination' => 'Rhaid bod teitl dilys gan y dudalen cyrchfan.',

# Merge log
'mergelog'         => 'Lòg cyfuno',
'mergelogpagetext' => "Fe ddilyn rhestr o'r achosion diweddaraf o hanes tudalen yn cael ei gyfuno a hanes tudalen arall.",

# Diffs
'history-title'           => "Hanes golygu '$1'",
'difference'              => '(Gwahaniaethau rhwng diwygiadau)',
'lineno'                  => 'Llinell $1:',
'compareselectedversions' => 'Cymharer y fersiynau dewisedig',
'editundo'                => 'dadwneud',
'diff-multi'              => '(Ni ddangosir {{PLURAL:$1|yr $1 diwygiad|yr $1 diwygiad|y $1 ddiwygiad|y $1 diwygiad|y $1 diwygiad|y $1 diwygiad}} rhyngol.)',

# Search results
'searchresults'         => 'Canlyniadau chwiliad',
'searchresulttext'      => 'Am mwy o hysbys amdano chwilio {{SITENAME}}, gwelwch [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Am gofyniad "[[:$1]]"',
'searchsubtitleinvalid' => "Chwiliwyd am '''$1'''",
'noexactmatch'          => "'''Nid oes tudalen a'r enw '$1' yn bod.''' Gallwch [[:$1|greu'r dudalen]].",
'noexactmatch-nocreate' => "'''Does dim tudalen a'r enw '$1' yn bod.'''",
'titlematches'          => 'Teitlau erthygl yn cyfateb',
'notitlematches'        => 'Does dim teitlau erthygl yn cyfateb',
'textmatches'           => 'Testun erthygl yn cyfateb',
'notextmatches'         => 'Does dim testun erthyglau yn cyfateb',
'prevn'                 => 'y $1 cynt',
'nextn'                 => 'y $1 nesaf',
'viewprevnext'          => 'Dangos ($1) ($2) ($3).',
'showingresults'        => "Yn dangos hyd at $1 {{PLURAL:$1|canlyniad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} isod gan ddechrau gyda rhif '''$2'''.",
'showingresultsnum'     => "Yn dangos $3 {{PLURAL:$3|canlyniad|canlyniad|ganlyniad|chanlyniad|chanlyniad|canlyniad}} isod gan ddechrau gyda rhif '''$2'''.",
'nonefound'             => "'''Nodyn''': Mae'r rhesymau bod chwiliad yn aflwyddiannus yn cynnwys chwilio am fwy nag un gair ar y tro (dim ond tudalennau sy'n cynnwys holl eiriau'r chwiliad a gaiff eu dychwelyd os na fydd OR rhyngddynt).",
'powersearch'           => 'Chwilio',
'powersearchtext'       => '
Edrychwch mewn lle-enw:<br />
$1<br />
$2 Rhestrwch ail-cyfeiriadau &nbsp; Chwiliwch am $3 $9',
'searchdisabled'        => "<p>Mae'r peiriant chwilio'r holl databas wedi cael eu troi i ffwrdd i gwneud pethau'n hawddach ar y gwasanaethwr. Gobeithiwn fydd yn bosibl i troi'r peiriant ymlaen cyn bo hir, ond yn y cyfamser mae'n posibl gofyn Google:</p>",

# Preferences page
'preferences'             => 'ffafraethau',
'mypreferences'           => 'fy newisiadau',
'prefs-edits'             => 'Nifer y golygiadau:',
'prefsnologin'            => 'Nid wedi mewngofnodi',
'prefsnologintext'        => 'Rhaid i chi [[Special:Userlogin|mewngofnodi]]
i setio ffafraethau defnyddwr.',
'prefsreset'              => 'Mae ffafraethau wedi gael eu ail-setio oddiwrth y storfa.',
'qbsettings'              => 'Gosodiadau bar-gyflym',
'qbsettings-none'         => 'Dim',
'qbsettings-fixedleft'    => 'Sefydlog chwith',
'qbsettings-fixedright'   => 'Sefydlog de',
'qbsettings-floatingleft' => 'Arnawf de',
'changepassword'          => 'Newidier y cyfrinair',
'skin'                    => 'Croen',
'math'                    => 'Mathemateg',
'dateformat'              => 'Fformat dyddiad',
'datedefault'             => 'Dim dewisiad',
'datetime'                => 'Dyddiad ac amser',
'math_failure'            => 'Wedi methu dosrannu',
'math_unknown_error'      => 'gwall anhysbys',
'math_unknown_function'   => 'ffwythiant anhysbys',
'math_lexing_error'       => 'gwall lecsio',
'math_syntax_error'       => 'gwall cystrawen',
'math_image_error'        => "Trosiad PNG wedi methu; gwiriwch fod latex, dvips, a gs wedi'u sefydlu'n gywir cyn trosi.",
'math_bad_tmpdir'         => 'Yn methu creu cyfeiriadur mathemateg dros dro, nac ysgrifennu iddo',
'math_bad_output'         => 'Yn methu creu cyfeiriadur allbwn mathemateg nac ysgrifennu iddo',
'math_notexvc'            => 'Rhaglen texvc yn eisiau; gwelwch math/README er mwyn ei chyflunio.',
'prefs-personal'          => 'Data defnyddiwr',
'prefs-rc'                => 'Newidiadau diweddar',
'prefs-watchlist'         => 'Rhestr gwylio',
'prefs-watchlist-days'    => "Nifer y diwrnodau i'w dangos yn y rhestr gwylio:",
'prefs-watchlist-edits'   => "Nifer y golygiadau i'w dangos wrth ehangu'r rhestr gwylio:",
'prefs-misc'              => 'Gosodiadau amrywiol',
'saveprefs'               => 'Cadw ffafraethau',
'resetprefs'              => 'Ail-setio ffafraethau',
'oldpassword'             => 'Hen allweddair',
'newpassword'             => 'Cyfrinair newydd:',
'retypenew'               => 'Ail-teipiwch yr allweddair newydd',
'textboxsize'             => 'Maint y bocs testun',
'rows'                    => 'Rhesi',
'columns'                 => 'Colofnau:',
'searchresultshead'       => 'Sefydliadau canlyniadau chwilio',
'resultsperpage'          => 'Hitiau i ddangos ar pob tudalen',
'contextlines'            => "Nifer y llinellau i'w dangos ar gyfer pob hit:",
'contextchars'            => 'Characters of context per line',
'recentchangesdays'       => "Nifer y diwrnodau i'w dangos yn 'newidiadau diweddar':",
'recentchangescount'      => 'Nifer o teitlau yn newidiadau diweddar',
'savedprefs'              => 'Mae eich ffafraethau wedi cael eu chadw.',
'timezonelegend'          => 'Ardal amser',
'timezonetext'            => "Teipiwch y nifer o oriau mae eich amsel lleol yn wahân o'r amser y gwasanaethwr (UTC/GMT).",
'localtime'               => 'Amser lleol',
'timezoneoffset'          => 'Atred',
'servertime'              => 'Amser y gwasanaethwr yw',
'guesstimezone'           => 'Llenwi oddiwrth y porwr',
'allowemail'              => 'Galluogi e-bost oddi wrth ddefnyddwyr eraill',
'defaultns'               => 'Chwiliwch y parthau rhagosodedig isod:',
'default'                 => 'rhagosodyn',
'files'                   => 'Ffeiliau',

# User rights
'userrights-lookup-user'           => 'Rheoli grwpiau defnyddiwr',
'userrights-user-editname'         => 'Rhowch enw defnyddiwr:',
'editusergroup'                    => 'Golygu Grwpiau Defnyddwyr',
'editinguser'                      => 'Yn golygu $1',
'userrights-editusergroup'         => 'Golygu grwpiau defnyddwyr',
'saveusergroups'                   => 'Cadw Grŵpiau Defnyddwyr',
'userrights-groupsmember'          => 'Yn aelod o:',
'userrights-groupsavailable'       => 'Grwpiau ar gael:',
'userrights-groupshelp'            => "Bydd dewis grwpiau isod yn ychwanegu'r defnyddiwr i'r grwp, neu yn ei dynnu ohoni. Ni newidir y grwpiau eraill. Gallwch ddiddymu dewis gyda CTRL + Clic Chwith",
'userrights-reason'                => 'Y rheswm dros y newid:',
'userrights-available-add'         => 'Gallwch ychwanegu defnyddwyr at y {{PLURAL:$2|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}} canlynol: $1.',
'userrights-available-remove'      => "Gallwch dynnu defnyddwyr o'r {{PLURAL:$2|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}} canlynol: $1.",
'userrights-available-add-self'    => "Gallwch ychwanegu'ch hunan at y {{PLURAL:$2|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}} canlynol: $1.",
'userrights-available-remove-self' => "Gallwch dynnu'ch hunan oddi ar y {{PLURAL:$2|grŵp|grŵp|grwpiau|grwpiau|grwpiau|grwpiau}} canlynol: $1.",

# Groups
'group'               => 'Grŵp:',
'group-autoconfirmed' => "Defnyddwyr wedi eu cadarnhau'n awtomatig",
'group-bot'           => 'Botiau',
'group-sysop'         => 'Gweinyddwyr',
'group-bureaucrat'    => 'Biwrocratiaid',
'group-all'           => '(oll)',

'group-autoconfirmed-member' => "Defnyddiwr wedi ei gadarnhau'n awtomatig",
'group-sysop-member'         => 'Gweinyddwr',
'group-bureaucrat-member'    => 'Biwrocrat',

'grouppage-autoconfirmed' => "{{ns:project}}:Defnyddwyr wedi eu cadarnhau'n awtomatig",
'grouppage-sysop'         => '{{ns:project}}:Gweinyddwyr',

# User rights log
'rightslogtext' => 'Dyma log o newidiadau i hawliau defnyddwyr.',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|newid|newid|newid|newid|newid|o newidiadau}}',
'recentchanges'                     => 'Newidiadau diweddar',
'recentchangestext'                 => "Traciwch y newidiadau mor diweddar i'r {{SITENAME}} ac i'r tudalen hon.",
'recentchanges-feed-description'    => "Dilyn y newidiadau diweddaraf i'r wici gyda'r porthiant hwn.",
'rcnote'                            => "Isod mae'r '''$1''' newidiad diweddaraf yn ystod y '''$2''' {{PLURAL:$2|diwrnod|diwrnod|ddiwrnod|diwrnod|diwrnod|diwrnod}} diwethaf, hyd at $3.",
'rcnotefrom'                        => "Isod yw'r newidiadau ers <b>$2</b> (dangosir i fynu i <b>$1</b>).",
'rclistfrom'                        => 'Dangos newidiadau newydd yn dechrau oddiwrth $1',
'rcshowhideminor'                   => '$1 golygiadau bychain',
'rcshowhidebots'                    => '$1 botiau',
'rcshowhideliu'                     => '$1 defnyddwyr mewngofnodedig',
'rcshowhideanons'                   => '$1 defnyddwyr anhysbys',
'rcshowhidemine'                    => '$1 fy ngolygiadau',
'rclinks'                           => 'Dangos y $1 newidiad diweddaraf yn y $2 diwrnod diwethaf<br />$3',
'diff'                              => 'gwahan',
'hist'                              => 'hanes',
'hide'                              => 'Cuddio',
'show'                              => 'dangos',
'minoreditletter'                   => 'B',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|defnyddwyr|defnyddiwr|ddefnyddiwr|defnyddiwr|defnyddiwr|o ddefnyddwyr}} yn gwylio]',
'rc_categories'                     => 'Cyfyngu i gategorïau (gwahanwch gyda "|")',
'rc_categories_any'                 => 'Unrhyw un',
'newsectionsummary'                 => '/* $1 */ adran newydd',

# Recent changes linked
'recentchangeslinked'         => 'Newidiadau perthnasol',
'recentchangeslinked-title'   => 'Newidiadau cysylltiedig â $1',
'recentchangeslinked-summary' => "Mae'r dudalen arbennig hon yn dangos y newidiadau diweddaraf i'r tudalennau sy'n cysylltu â'r dudalen a enwir. Dangosir tudalennau sydd ar eich rhestr gwylio mewn print '''trwm'''.",

# Upload
'upload'                      => 'Llwytho ffeil i fynu',
'uploadbtn'                   => 'Llwytho ffeil i fynu',
'reupload'                    => 'Ail-llwytho i fynu',
'uploadnologin'               => 'Nid wedi mewngofnodi',
'uploadnologintext'           => 'Rhaid i chi bod wedi [[Special:Userlogin|mewngofnodi]]
i lwytho ffeiliau i fynu.',
'upload_directory_read_only'  => "Ni all y gweinydd ysgrifennu i'r cyfeiriadur uwchlwytho ($1).",
'uploaderror'                 => 'Gwall yn llwytho ffeil i fynu',
'uploadtext'                  => "Defnyddiwch y ffurflen isod i uwchlwytho ffeiliau. 
I weld a chwilio am ffeiliau sydd eisoes wedi eu huwchlwytho ewch at y [[Special:Imagelist|rhestr o'r ffeiliau sydd wedi eu huwchlwytho]]. I weld cofnodion uwchlwytho a dileu ffeiliau ewch at y [[Special:Log/upload|lòg uwchlwytho]]. 

I osod ffeil mewn tudalen defnyddiwch gyswllt wici a fydd yn arwain yn syth at y ffeil, fel a ganlyn:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Ffeil.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Ffeil.png|testun amgen]]</nowiki>''' neu
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Ffeil.ogg]]</nowiki>'''.",
'uploadlog'                   => 'log llwytho i fynu',
'uploadlogpage'               => 'log_llwytho_i_fynu',
'uploadlogpagetext'           => "Isod mae rhestr o'r llwythu ffeil diweddarach.
Pob amser sy'n dangos yw amser y gwasanaethwr (UTC).",
'filename'                    => "Enw'r ffeil",
'filedesc'                    => 'Crynodeb',
'fileuploadsummary'           => 'Crynodeb:',
'filestatus'                  => 'Statws hawlfraint:',
'filesource'                  => 'Ffynhonnell:',
'uploadedfiles'               => 'Ffeiliau wedi llwytho i fynu',
'ignorewarning'               => "Anwybydder y rhybudd, a rhoi'r dudalen ar gadw beth bynnag.",
'ignorewarnings'              => 'Anwybydder pob rhybudd',
'minlength1'                  => 'Rhaid i enwau ffeiliau gynnwys un llythyren neu ragor.',
'illegalfilename'             => 'Mae\'r enw ffeil "$1" yn cynnwys nodau sydd wedi\'u gwahardd mewn teitlau tudalennau. Ail-enwch y ffeil ac uwchlwythwch hi eto os gwelwch yn dda.',
'badfilename'                 => 'Mae enw\'r ffeil wedi\'i newid i "$1".',
'filetype-badmime'            => "Ni chaniateir uwchlwytho ffeiliau o'r math MIME '$1'.",
'filetype-unwanted-type'      => "Mae'r math '''\".\$1\"''' o ffeil yn anghymeradwy.  Mae'n well defnyddio ffeil o'r math \$2.",
'filetype-banned-type'        => "Ni chaniateir ffeiliau o'r math '''\".\$1\"'''.  \$2 yw'r mathau o ffeiliau a ganiateir.",
'filetype-missing'            => "Nid oes gan y ffeil hon estyniad (megis '.jpg').",
'large-file'                  => "Argymhellir na ddylai ffeil fod yn fwy na $1. Mae'r ffeil hwn yn $2 o faint.",
'largefileserver'             => "Mae'r ffeil yn fwy na'r hyn mae'r gweinydd yn ei ganiatau.",
'emptyfile'                   => "Ymddengys fod y ffeil a uwchlwythwyd yn wag. Efallai bod gwall teipio yn enw'r ffeil. Sicrhewch eich bod wir am uwchlwytho'r ffeil.",
'fileexists'                  => "Mae ffeil gyda'r enw hwn eisoes yn bodoli; gwiriwch $1 os nad ydych yn sicr bod angen ei newid.",
'fileexists-extension'        => "Mae ffeil ag enw tebyg eisoes yn bod:<br />
Enw'r ffeil ar fin ei uwchlwytho: <strong><tt>$1</tt></strong><br />
Enw'r ffeil sydd eisoes yn bod: <strong><tt>$2</tt></strong><br />
Dewiswch enw arall os gwelwch yn dda.",
'fileexists-thumb'            => "<center>'''Y ddelwedd eisoes ar glawr'''</center>",
'fileexists-thumbnail-yes'    => "Ymddengys bod delwedd wedi ei leihau <i>(bawd)</i> ar y ffeil. Cymharwch gyda'r ffeil <strong><tt>$1</tt></strong>.<br />
Os mai'r un un llun ar ei lawn faint sydd ar yr ail ffeil yna does dim angen uwchlwytho llun ychwanegol o faint bawd.",
'file-thumbnail-no'           => "Mae <strong><tt>$1</tt></strong> ar ddechrau enw'r ffeil. Mae'n ymddangos bod y ddelwedd wedi ei leihau <i>(maint bawd)</i>.
Os yw'r ddelwedd ar ei lawn faint gallwch barhau i'w uwchlwytho. Os na, newidiwch enw'r ffeil, os gwelwch yn dda.",
'fileexists-forbidden'        => "Mae ffeil gyda'r enw hwn eisoes yn bodoli; ewch nôl ac uwchlwythwch y ffeil o dan enw newydd.
[[Image:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Mae ffeil gyda'r enw hwn eisoes yn bodoli yn y storfa ffeiliau cyfrannol; ewch nôl ac uwchlwythwch y ffeil o dan enw newydd. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'Llwyth i fynu yn llwyddiannus',
'uploadwarning'               => 'Rhybudd llwytho i fynu',
'savefile'                    => 'Cadw ffeil',
'uploadedimage'               => '"[[$1]]" wedi llwytho',
'uploaddisabled'              => 'Mae ddrwg gennym ni, mae uwchllwytho wedi anablo.',
'uploaddisabledtext'          => 'Analluogir uwchlwytho ffeiliau ar y wici yma.',
'uploadscripted'              => "Mae'r ffeil hon yn cynnwys HTML neu sgript a all achosi problemau i borwyr gwe.",
'uploadcorrupt'               => 'Mae nam ar y ffeil neu mae ganddi estyniad anghywir. Gwiriwch y ffeil ac uwchlwythwch eto.',
'uploadvirus'                 => 'Mae firws gan y ffeil hon! Manylion: $1',
'sourcefilename'              => "Enw'r ffeil wreiddiol:",
'destfilename'                => 'Enw ffeil y cyrchfan:',
'watchthisupload'             => 'Gwylier y dudalen hon',
'filename-bad-prefix'         => "Mae'r enw ar y ffeil yr ydych yn ei uwchlwytho yn dechrau gyda <strong>\"\$1\"</strong>. Mae'r math hwn o enw diystyr fel arfer yn cael ei osod yn awtomatig gan gamerâu digidol. Mae'n well gosod enw sy'n disgrifio'r ffeil arno.",

'upload-proto-error' => 'Protocol gwallus',
'upload-file-error'  => 'Gwall mewnol',
'upload-misc-error'  => 'Gwall uwchlwytho anhysbys',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Wedi methu cyrraedd yr URL',

'license'            => 'Trwyddedu:',
'nolicense'          => "Dim un wedi'i ddewis",
'license-nopreview'  => '(Dim rhagolwg ar gael)',
'upload_source_url'  => " (URL dilys, ar gael i'r cyhoedd)",
'upload_source_file' => ' (ffeil ar eich cyfrifiadur)',

# Image list
'imagelist'                 => "Rhestr o'r holl ffeiliau",
'imagelisttext'             => "Isod mae rhestr {{PLURAL:$1|gwag o ffeiliau|o '''$1''' ffeil|o '''$1''' ffeil wedi'u trefnu $2|o '''$1''' ffeil wedi'u trefnu $2|o '''$1''' o ffeiliau wedi'u trefnu $2|o '''$1''' o ffeiliau wedi'u trefnu $2|}}.",
'getimagelist'              => "wrthi'n cywain y rhestr ffeiliau",
'ilsubmit'                  => 'Chwilio',
'showlast'                  => 'Dangos y $1 delweddau olaf wedi trefnu $2.',
'byname'                    => 'yn ôl enw',
'bydate'                    => 'yn ôl dyddiad',
'bysize'                    => 'yn ôl maint',
'imgdelete'                 => 'dileu',
'imgdesc'                   => 'disg.',
'imgfile'                   => 'ffeil',
'filehist'                  => 'Hanes y ffeil',
'filehist-help'             => 'Cliciwch ar ddyddiad/amser i weld y ffeil fel ag yr oedd bryd hynny.',
'filehist-deleteall'        => 'eu dileu i gyd',
'filehist-deleteone'        => 'dileu hwn',
'filehist-revert'           => 'gwrthdroi',
'filehist-current'          => 'cyfredol',
'filehist-datetime'         => 'Dyddiad/Amser',
'filehist-user'             => 'Defnyddiwr',
'filehist-dimensions'       => 'Hyd a lled',
'filehist-filesize'         => 'Maint y ffeil',
'filehist-comment'          => 'Sylw',
'imagelinks'                => "Cysylltiadau'r ffeil",
'linkstoimage'              => "Mae'r tudalennau isod yn cysylltu i'r ddelwedd hon:",
'nolinkstoimage'            => 'Nid oes cyswllt ar unrhyw dudalen yn arwain at y ffeil hon.',
'sharedupload'              => "Mae'r ffeil hon ar gael i'w rannu, felly mae'n bosib ei bod ar waith ar brosiectau eraill.",
'shareduploadwiki'          => 'Gwelwch y [$1 tudalen disgrifiad ffeil] am fwy o fanylion.',
'shareduploadwiki-linktext' => 'tudalen disgrifiad ffeil',
'noimage'                   => "Does dim ffeil a'r enw hwn i gael; gallwch $1.",
'noimage-linktext'          => 'ei uwchlwytho',
'uploadnewversion-linktext' => "Uwchlwytho fersiwn newydd o'r ffeil hon",
'imagelist_date'            => 'Dyddiad',
'imagelist_name'            => 'Enw',
'imagelist_user'            => 'Defnyddiwr',
'imagelist_size'            => 'Maint',
'imagelist_description'     => 'Disgrifiad',
'imagelist_search_for'      => "Chwilio am enw'r ddelwedd:",

# File reversion
'filerevert'                => 'Gwrthdroi $1',
'filerevert-legend'         => "Gwrthdroi'r ffeil",
'filerevert-intro'          => '<span class="plainlinks">Rydych yn gwrthdroi \'\'\'[[Media:$1|$1]]\'\'\' i\'r [fersiwn $4 fel ag yr oedd ar $3, $2].</span>',
'filerevert-comment'        => 'Sylw:',
'filerevert-defaultcomment' => 'Wedi adfer fersiwn $2, $1',
'filerevert-submit'         => 'Gwrthdroi',
'filerevert-success'        => '<span class="plainlinks">Mae \'\'\'[[Media:$1|$1]]\'\'\' wedi cael ei wrthdroi i\'r [fersiwn $4 fel ag yr oedd ar $3, $2].</span>',
'filerevert-badversion'     => "Nid oes fersiwn lleol cynt o'r ffeil hwn gyda'r amsernod a nodwyd.",

# File deletion
'filedelete'           => 'Dileu $1',
'filedelete-legend'    => "Dileu'r ffeil",
'filedelete-intro'     => "Rydych ar fin dileu '''[[Media:$1|$1]]'''.",
'filedelete-comment'   => 'Sylw:',
'filedelete-submit'    => 'Dileer',
'filedelete-success'   => "Mae '''$1''' wedi cael ei dileu.",
'filedelete-nofile'    => "Nid oes '''$1''' ar y wefan {{SITENAME}}.",
'filedelete-iscurrent' => "Rydych yn ceisio dileu'r fersiwn diweddaraf o'r ffeil hwn. Rhaid gwrthdroi i fersiwn gynt yn gyntaf.",

# MIME search
'mimesearch' => 'Chwiliad MIME',
'mimetype'   => 'Ffurf MIME:',
'download'   => 'islwytho',

# Unwatched pages
'unwatchedpages' => 'Tudalennau sydd â neb yn eu gwylio',

# List redirects
'listredirects' => "Rhestru'r ail-gyfeiriadau",

# Unused templates
'unusedtemplates' => 'Nodiadau heb eu defnyddio',

# Random page
'randompage' => 'Erthygl hapgyrch',

# Random redirect
'randomredirect' => 'Tudalen ailgyfeirio ar hap',

# Statistics
'statistics'    => 'Ystadegau',
'sitestats'     => "Ystadegau'r seit",
'userstats'     => 'Ystadegau defnyddwyr',
'sitestatstext' => "Mae '''\$1''' {{PLURAL:\$1|tudalen i gyd|tudalen|dudalen i gyd|tudalen i gyd|thudalen i gyd|o dudalennau i gyd}} ar y databas.
Mae hyn yn cynnwys tudalennau \"sgwrs\", tudalennau ynglŷn â {{SITENAME}}, egin erthyglau cwta, ailgyfeiriadau, a thudalennau eraill nad ydynt yn erthyglau go iawn. Ag eithrio'r rhain, mae'n debyg bod yna '''\$2''' {{PLURAL:\$2|erthygl|erthygl|erthygl|erthygl|erthygl|erthygl}} yn y wici.

{{PLURAL:\$1|Ni chafodd unrhyw ffeil ei|Cafodd '''\$8''' ffeil ei|Cafodd '''\$8''' ffeil eu|Cafodd '''\$8''' ffeil eu|Cafodd '''\$8''' ffeil eu|Cafodd '''\$8''' ffeil eu}} huwchlwytho.

Ers sefydlu'r meddalwedd {{PLURAL:\$3|ni chafwyd unrhyw|cafwyd '''\$3'''|cafwyd '''\$3'''|cafwyd '''\$3'''|cafwyd '''\$3'''|cafwyd '''\$3'''}} ymweliad â'r wefan o wefannau eraill a{{PLURAL:\$4|c ni chafwyd unrhyw olygiad|c '''\$4''' golygiad| '''\$4''' olygiad| '''\$4''' golygiad| '''\$4''' golygiad| '''\$4''' golygiad}} i dudalennau.
Ar gyfartaledd felly, bu '''\$5''' golygiad i bob tudalen, a '''\$6''' ymweliad â thudalen ar gyfer pob golygiad.

Hyd y [http://meta.wikimedia.org/wiki/Help:Job_queue rhes dasgau] yw '''\$7'''.",
'userstatstext' => "Mae '''$1''' {{PLURAL:$1|[[Special:Listusers|defnyddiwr]]|[[Special:Listusers|defnyddiwr]]|[[Special:Listusers|ddefnyddiwr]]|[[Special:Listusers|defnyddiwr]]|[[Special:Listusers|defnyddiwr]]|[[Special:Listusers|defnyddiwr]]}} ar y cofrestr defnyddwyr.
Mae gan '''$2''' (neu '''$4%''') ohonynt alluoedd $5.",

'disambiguations'      => 'Tudalennau gwahaniaethu',
'disambiguations-text' => "Mae'r tudalennau canlynol yn cysylltu â thudalennau gwahaniaethu. Yn hytrach dylent gysylltu'n syth â'r erthygl briodol.<br />Diffinir tudalen yn dudalen gwahaniaethu pan mae'n cynnwys un o'r nodiadau '[[MediaWiki:Disambiguationspage|tudalen gwahaniaethu]]'.",

'doubleredirects'     => 'Ailgyfeiriadau dwbl',
'doubleredirectstext' => "Mae pob rhes yn cynnwys cysylltiad i'r ddau ail-gyfeiriad cyntaf, ynghyd â chyrchfan yr ail ailgyfeiriad. Fel arfer bydd hyn yn rhoi'r gwir dudalen y dylai'r tudalennau cynt gyfeirio ati.",

'brokenredirects'        => "Ailgyfeiriadau wedi'u torri",
'brokenredirectstext'    => "Mae'r ailgyfeiriadau isod yn cysylltu â thudalennau sydd heb eu creu eto.",
'brokenredirects-edit'   => '(golygu)',
'brokenredirects-delete' => '(dileu)',

'withoutinterwiki'        => 'Tudalennau heb gysylltiadau ag ieithoedd eraill',
'withoutinterwiki-header' => 'Nid oes gysylltiad rhwng y tudalennau canlynol a thudalennau mewn ieithoedd eraill:',

'fewestrevisions' => "Erthyglau â'r nifer lleiaf o olygiadau iddynt",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|beit|beit|feit|beit|beit|beit}}',
'ncategories'             => '$1 {{PLURAL:$1|categori|categori|gategori|chategori|chategori|categori}}',
'nlinks'                  => '$1 {{PLURAL:$1|cyswllt|cyswllt|gyswllt|chyswllt|chyswllt|cyswllt}}',
'nmembers'                => '$1 {{PLURAL:$1|aelod|aelod|aelod|aelod|aelod|aelod}}',
'nrevisions'              => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'nviews'                  => '$1 {{PLURAL:$1|ymweliad|ymweliad|ymweliad|ymweliad|ymweliad|ymweliad}}',
'specialpage-empty'       => "Mae'r dudalen hon yn wag.",
'lonelypages'             => 'Erthyglau heb gysylltiadau iddynt',
'lonelypagestext'         => 'Nid oes cysylltiad yn arwain at y tudalennau canlynol oddi wrth unrhyw dudalen arall yn {{SITENAME}}.',
'uncategorizedpages'      => 'Tudalennau heb gategori',
'uncategorizedcategories' => 'Categorïau sydd heb gategori',
'uncategorizedimages'     => 'Ffeiliau heb eu categoreiddio',
'uncategorizedtemplates'  => 'Nodiadau heb eu categoreiddio',
'unusedcategories'        => 'Categorïau gwag',
'unusedimages'            => 'Lluniau di-defnyddio',
'popularpages'            => 'Erthyglau poblogol',
'wantedcategories'        => 'Categorïau sydd eu hangen',
'wantedpages'             => 'Erthyglau mewn eisiau',
'mostlinked'              => 'Tudalennau yn nhrefn nifer y cysylltiadau iddynt',
'mostlinkedcategories'    => 'Categorïau yn nhrefn nifer eu haelodau',
'mostlinkedtemplates'     => 'Nodiadau yn nhrefn nifer y cysylltiadau iddynt',
'mostcategories'          => 'Erthyglau yn nhrefn nifer eu categorïau',
'mostimages'              => 'Ffeiliau yn nhrefn nifer y cysylltiadau iddynt',
'mostrevisions'           => 'Tudalennau yn nhrefn nifer golygiadau',
'allpages'                => 'Pob tudalen',
'prefixindex'             => 'Mynegai rhagddodiad',
'shortpages'              => 'Erthyglau byr',
'longpages'               => 'Tudalennau hirion',
'deadendpages'            => 'Tudalennau heb gysylltiadau ynddynt',
'deadendpagestext'        => "Nid oes cysylltiad yn arwain at dudalen arall oddi wrth yr un o'r tudalennau isod.",
'protectedpages'          => 'Tudalennau wedi eu diogelu',
'listusers'               => 'Rhestr defnyddwyr',
'specialpages'            => 'Erthyglau arbennig',
'spheading'               => 'Erthyglau arbennig',
'restrictedpheading'      => 'Tudalennau arbennig cyfyngedig',
'newpages'                => 'Erthyglau newydd',
'newpages-username'       => 'Enw defnyddiwr:',
'ancientpages'            => 'Erthyglau hynaf',
'move'                    => 'Symud',
'movethispage'            => 'Symud y dudalen hon',
'unusedimagestext'        => "<p>Sylwch y gall gwefannau eraill gysylltu a ffeil drwy URL uniongyrchol. Gan hynny mae'n bosibl fod ffeil wedi ei rhestru yma serch ei bod yn cael defnydd.</p>",
'unusedcategoriestext'    => "Mae'r tudalennau categori isod yn bodoli er nad oes unrhyw dudalen arall yn eu defnyddio.",
'notargettitle'           => 'Dim targed',
'notargettext'            => 'Dydych chi ddim wedi dewis tudalen targed neu defnyddwr.',
'pager-newer-n'           => '{{PLURAL:$1|y $1 mwy diweddar|y 1 mwy diweddar|y $1 mwy diweddar|y $1 mwy diweddar|y $1 mwy diweddar|y $1 mwy diweddar}}.',
'pager-older-n'           => '{{PLURAL:$1|y $1 cynharach|yr $1 cynharach|y $1 cynharach|y $1 cynharach|y $1 cynharach|y $1 cynharach}}',

# Book sources
'booksources'               => 'Ffynonellau llyfrau',
'booksources-search-legend' => 'Chwilier am lyfrau',
'booksources-go'            => 'Mynd',
'booksources-text'          => "Mae'r rhestr isod yn cynnwys cysylltiadau i wefannau sy'n gwerthu llyfrau newydd a rhai ail-law. Mae rhai o'r gwefannau hefyd yn cynnig gwybodaeth pellach am y llyfrau hyn:",

'categoriespagetext' => "Mae'r categorïau isod yn y wici.",
'userrights'         => 'Rheoli hawliau defnyddwyr',
'groups'             => 'Grwpiau defnyddwyr',
'isbn'               => 'Rhif Llyfrau Rhyngwladol (ISBN)',
'alphaindexline'     => '$1 i $2',
'version'            => 'Fersiwn',

# Special:Log
'specialloguserlabel'  => 'Defnyddiwr:',
'speciallogtitlelabel' => 'Teitl:',
'log'                  => 'Logiau',
'all-logs-page'        => 'Pob lòg',
'log-search-legend'    => 'Chwilio am logiau',
'log-search-submit'    => 'Eler',
'alllogstext'          => "Mae pob cofnod yn holl logiau {{SITENAME}} wedi cael eu rhestru yma. 
Gallwch weld chwiliad mwy penodol trwy ddewis y math o lòg, enw'r defnyddiwr, neu'r dudalen benodedig. Sylwer bod prif lythrennau o bwys i'r chwiliad.",
'logempty'             => 'Does dim eitemau yn cyfateb yn y lòg.',
'log-title-wildcard'   => "Chwilio am deitlau'n dechrau gyda'r geiriau hyn",

# Special:Allpages
'nextpage'          => 'Y bloc nesaf gan ddechrau gyda ($1)',
'prevpage'          => 'Y bloc cynt gan ddechrau gyda ($1)',
'allpagesfrom'      => 'Dangos pob tudalen gan ddechrau o:',
'allarticles'       => 'Pob erthygl',
'allinnamespace'    => 'Pob tudalen (parth $1)',
'allnotinnamespace' => 'Pob tudalen (heblaw am y parth $1)',
'allpagesprev'      => 'Gynt',
'allpagesnext'      => 'Nesaf',
'allpagessubmit'    => 'Eler',
'allpagesprefix'    => "Dangos pob tudalen gyda'r rhagddodiad:",
'allpagesbadtitle'  => 'Roedd y darpar deitl yn annilys oherwydd bod ynddo naill ai:<p> - rhagddodiad rhyngwici neu ryngieithol, neu </p>- nod neu nodau na ellir eu defnyddio mewn teitlau.',

# Special:Listusers
'listusersfrom'      => 'Dangos y defnyddwyr gan ddechrau â:',
'listusers-submit'   => 'Dangos',
'listusers-noresult' => "Dim defnyddiwr i'w gael.",

# E-mail user
'mailnologin'     => "Does dim cyfeiriad i'w anfon iddo",
'mailnologintext' => 'Rhaid eich bod wedi [[Special:Userlogin|mewngofnodi]]
a bod cyfeiriad e-bost dilys yn eich [[Special:Preferences|dewisiadau]]
er mwyn medru anfon e-bost at ddefnyddwyr eraill.',
'emailuser'       => 'Anfon e-bost at y defnyddiwr hwn',
'emailpage'       => 'Anfon e-bost at ddefnyddiwr',
'emailpagetext'   => 'Os yw\'r defnyddiwr hwn wedi gosod cyfeiriad e-bost yn ei ddewisiadau, gellir anfon un neges ato ar y ffurflen isod. Bydd y cyfeiriad e-bost a osodoch yn eich dewisiadau chithau yn ymddangos ym maes "Oddi wrth" yr e-bost, fel bod y defnyddiwr arall yn gallu ei ateb.',
'usermailererror' => 'Dychwelwyd gwall gan y rhaglen e-bost:',
'defemailsubject' => 'E-bost {{SITENAME}}',
'noemailtitle'    => 'Dim cyfeiriad e-bost',
'noemailtext'     => "Mae'r defnyddiwr hwn naill ai heb roi cyfeiriad e-bost dilys, neu mae wedi dewis peidio â derbyn e-bost oddi wrth ddefnyddwyr eraill.",
'emailfrom'       => 'Oddi wrth',
'emailto'         => 'At',
'emailsubject'    => 'Pwnc',
'emailmessage'    => 'Neges',
'emailsend'       => 'Anfon',
'emailccme'       => "Anfoner gopi o'r neges e-bost ataf.",
'emailccsubject'  => "Copi o'ch neges at $1: $2",
'emailsent'       => "Neges e-bost wedi'i hanfon",
'emailsenttext'   => 'Mae eich neges e-bost wedi cael ei hanfon.',

# Watchlist
'watchlist'            => 'Fy rhestr gwylio',
'mywatchlist'          => 'Fy rhestr gwylio',
'watchlistfor'         => "(ar gyfer '''$1''')",
'nowatchlist'          => "Mae eich rhestr gwylio'n wag.",
'watchlistanontext'    => "Rhaid $1 er mwyn gweld neu ddiwygio'ch rhestr gwylio.",
'watchnologin'         => 'Dydych chi ddim wedi mewngofnodi',
'watchnologintext'     => 'Rhaid i chi bod wedi [[Special:Userlogin|mewngofnodi]]
i adnewid eich rhestr gwylio.',
'addedwatch'           => 'Rhoddwyd ar eich rhestr gwylio',
'addedwatchtext'       => "Mae'r dudalen \"[[:\$1|\$1]]\" wedi cael ei hychwanegu at eich [[Special:Watchlist|rhestr gwylio]].
Pan fydd y dudalen hon, neu ei thudalen sgwrs, yn newid, fe fyddant yn ymddangos ar eich rhestr gwylio ac hefyd '''yn gryf''' ar restr y [[Special:Recentchanges|newidiadau diweddar]], fel ei bod yn haws eu gweld.

Os ydych am ddiddymu'r dudalen o'r rhestr gwylio, cliciwch ar \"Stopio gwylio\" yn y bar ar frig y dudalen.",
'removedwatch'         => 'Wedi diswyddo oddiwrth y rhestr gwylio',
'removedwatchtext'     => 'Mae tudalen "<nowiki>$1</nowiki>" wedi cael ei diswyddo oddiwrth eich rhestr gwylio.',
'watch'                => 'Gwylio',
'watchthispage'        => 'Gwyliwch y tudalen hon',
'unwatch'              => 'Stopio gwylio',
'unwatchthispage'      => 'Stopiwch gwylio',
'notanarticle'         => 'Nid erthygl',
'watchnochange'        => "Does dim o'r erthyglau rydych chi'n gwylio wedi golygu yn yr amser sy'n dangos.",
'watchlist-details'    => 'Yn gwylio {{PLURAL:$1|$1 tudalen|$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 o dudalennau}}, heb gynnwys tudalennau sgwrs.',
'wlheader-enotif'      => '* Galluogwyd hysbysiadau trwy e-bost.',
'wlheader-showupdated' => "* Mae tudalennau sydd wedi newid ers i chi ymweld ddiwethaf wedi'u '''hamlygu'''.",
'watchmethod-recent'   => 'gwiriwch golygiadau diweddar am tudalennau gwyliad',
'watchmethod-list'     => 'yn gwirio tudalennau gwyliad am olygiadau diweddar',
'watchlistcontains'    => '{{PLURAL:$1|Nid oes $1 tudalen|Mae $1 dudalen|Mae $1 dudalen|Mae $1 tudalen|Mae $1 thudalen|Mae $1 o dudalennau}} ar eich rhestr gwylio.',
'iteminvalidname'      => "Problem gyda'r eitem '$1', enw annilys...",
'wlnote'               => "Gweler isod y '''$1''' {{PLURAL:$1|newidiad|newidiad|newidiad|newidiad|newidiad|newidiad}} diweddaraf yn ystod y <b>$2</b> {{PLURAL:$1|awr|awr|awr|awr|awr|awr}} ddiwethaf.",
'wlshowlast'           => "Dangoser newidiadau'r $1 awr ddiwethaf neu'r $2 diwrnod diwethaf neu'r $3 newidiadau.",
'watchlist-show-bots'  => 'Dangos golygiadau bot',
'watchlist-hide-bots'  => 'Cuddio golygiadau bot',
'watchlist-show-own'   => 'Dangos fy ngolygiadau',
'watchlist-hide-own'   => 'Cuddio fy ngolygiadau',
'watchlist-show-minor' => 'Dangos golygiadau bychain',
'watchlist-hide-minor' => 'Cuddio golygiadau bychain',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => "Wrthi'n ychwanegu...",
'unwatching' => "Wrthi'n tynnu...",

'enotif_mailer'                => 'Sustem hysbysu {{SITENAME}}',
'enotif_reset'                 => 'Ystyried bod pob tudalen wedi cael ymweliad',
'enotif_newpagetext'           => 'Mae hon yn dudalen newydd.',
'enotif_impersonal_salutation' => 'at ddefnyddiwr {{SITENAME}}',
'changed'                      => 'Newidiwyd',
'created'                      => 'Crewyd',
'enotif_subject'               => '$CHANGEDORCREATED y dudalen \'$PAGETITLE\' ar {{SITENAME}} gan $PAGEEDITOR',
'enotif_lastvisited'           => 'Gwelwch $1 am bob newid ers eich ymweliad blaenorol.',
'enotif_lastdiff'              => 'Gallwch weld y newid ar $1.',
'enotif_anon_editor'           => 'defnyddiwr anhysbys $1',
'enotif_body'                  => 'Annwyl $WATCHINGUSERNAME,

$CHANGEDORCREATED y dudalen \'$PAGETITLE\' ar {{SITENAME}} ar $PAGEEDITDATE gan $PAGEEDITOR; gweler $PAGETITLE_URL am y diwygiad presennol.

$NEWPAGE

Crynodeb y golygydd: $PAGESUMMARY $PAGEMINOREDIT

Cysylltu â\'r golygydd:
e-bost: $PAGEEDITOR_EMAIL
wici: $PAGEEDITOR_WIKI

Os digwydd mwy o olygiadau i\'r dudalen cyn i chi ymweld â hi, ni chewch ragor o negeseuon hysbysu. Nodwn bod modd i chi ailosod y fflagiau hysbysu ar eich rhestr gwylio, ar gyfer y tudalennau rydych yn eu gwylio.

             Sustem hysbysu {{SITENAME}}

--
I newid eich gosodiadau gwylio, ymwelwch â
{{fullurl:Special:Watchlist/edit}}

Am fwy o gymorth:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Dilëer y dudalen',
'confirm'                     => 'Cadarnhau',
'excontent'                   => "y cynnwys oedd: '$1'",
'excontentauthor'             => "y cynnwys oedd: '$1' (a'r unig gyfrannwr oedd '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "y cynnwys cyn blancio oedd: '$1'",
'exblank'                     => 'roedd y dudalen yn wag',
'historywarning'              => "Rhybudd: mae hanes i'r dudalen rydych ar fin ei dileu.",
'confirmdeletetext'           => "Rydych chi ar fin dileu tudalen neu ddelwedd, ynghŷd â'i hanes, o'r data-bas, a hynny'n barhaol.
Os gwelwch yn dda, cadarnhewch eich bod chi wir yn bwriadu gwneud hyn, eich bod yn deall y canlyniadau, ac yn ei wneud yn ôl [[{{MediaWiki:Policy-url}}|polisïau {{SITENAME}}]].",
'actioncomplete'              => "Wedi cwblhau'r weithred",
'deletedtext'                 => 'Mae "<nowiki>$1</nowiki>" wedi\'i ddileu. 
Gwelwch y $2 am gofnod o\'r dileuon diweddar.',
'deletedarticle'              => 'wedi dileu "[[$1]]"',
'dellogpage'                  => 'Log dileuon',
'dellogpagetext'              => "Ceir rhestr isod o'r dileadau diweddaraf.",
'deletionlog'                 => 'log dileuon',
'reverted'                    => 'Wedi mynd nôl i fersiwn gynt',
'deletecomment'               => 'Esboniad am y dileu:',
'deleteotherreason'           => 'Rheswm arall:',
'deletereasonotherlist'       => 'Rheswm arall',
'deletereason-dropdown'       => "*Rhesymau arferol dros ddileu
** Ar gais yr awdur
** Torri'r hawlfraint
** Fandaliaeth",
'rollback'                    => 'Roliwch golygon yn ôl',
'rollback_short'              => 'Rolio nôl',
'rollbacklink'                => 'rolio nôl',
'rollbackfailed'              => 'Methwyd rolio nôl',
'cantrollback'                => "Wedi methu gwrthdroi'r golygiad; y cyfrannwr diwethaf oedd unig awdur y dudalen hon.",
'alreadyrolled'               => "Nid yw'n bosib dadwneud y golygiad diwethaf i'r dudalen [[:$1|$1]] gan [[User:$2|$2]] ([[User_talk:$2|Sgwrs]]); mae rhywun arall eisoes wedi dadwneud y golygiad neu wedi golygu'r dudalen. 

[[User:$3|$3]] ([[User_talk:$3|Sgwrs]]) a wnaeth y golygiad diwethaf.",
'editcomment'                 => 'Crynodeb y golygiad oedd: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Wedi gwrthdroi golygiadau gan [[Special:Contributions/$2|$2]] ([[User talk:$2|Sgwrs]]); wedi adfer y golygiad diweddaraf gan [[User:$1|$1]].', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'sessionfailure'              => "Mae'n debyg fod yna broblem gyda'ch sesiwn mewngofnodi; diddymwyd y weithred er mwyn diogelu'r sustem rhag ddefnyddwyr maleisus. Gwasgwch botwm 'nôl' eich porwr ac ail-lwythwch y dudalen honno, yna ceisiwch eto.",
'protectlogpage'              => 'Log_amdiffyno',
'protectlogtext'              => 'Isod mae rhestr o bob gweithred diogelu, a dad-ddiogelu, tudalennau.',
'protectedarticle'            => 'wedi amddiffyno [[$1]]',
'modifiedarticleprotection'   => 'newidiwyd y lefel diogelu ar gyfer "[[$1]]"',
'unprotectedarticle'          => 'wedi di-amddiffyno [[$1]]',
'protectsub'                  => '(Yn diogelu "$1")',
'confirmprotect'              => "Cadarnháu'r diogelu",
'protectcomment'              => 'Rheswm dros ddiogelu',
'protectexpiry'               => 'Yn dod i ben:',
'protect_expiry_invalid'      => 'Amser terfynu annilys.',
'protect_expiry_old'          => "Mae'r amser darfod yn y gorffennol.",
'unprotectsub'                => '(Yn dad-ddiogelu "$1")',
'protect-unchain'             => 'Datgloi hawliau symud',
'protect-text'                => 'Yma, gallwch weld a newid y lefel diogelu ar gyfer y dudalen <strong><nowiki>$1</nowiki></strong>.',
'protect-cascadeon'           => "Mae'r dudalen hon wedi ei diogelu ar hyn o bryd oherwydd ei bod wedi ei chynnwys yn y {{PLURAL:$1|dudalen|dudalen|tudalennau|tudalennau|tudalennau|tudalennau}} canlynol sydd wedi {{PLURAL:$1|ei|ei|eu|eu|eu|eu}} diogelu'n rhaeadrol.  Gallwch newid lefel diogelu'r dudalen hon, ond ni fydd hynny'n effeithio ar y diogelu rhaeadrol.",
'protect-default'             => '(rhagosodedig)',
'protect-level-autoconfirmed' => 'Blocio defnyddwyr heb gyfrif',
'protect-level-sysop'         => 'Gweinyddwyr yn unig',
'protect-summary-cascade'     => 'sgydol',
'protect-expiring'            => 'yn dod i ben am $1 (UTC)',
'protect-cascade'             => 'Diogelu tudalennau sydd wedi eu cynnwys yn y dudalen hon (diogelu sgydol)',
'protect-cantedit'            => "Ni allwch newid lefel diogelu'r dudalen hon, am nad yw'r gallu i olygu'r dudalen ganddoch.",
'restriction-type'            => 'Caniatâd:',
'restriction-level'           => 'Lefel cyfyngu:',
'minimum-size'                => 'Maint lleiaf',
'maximum-size'                => 'Maint mwyaf',

# Restrictions (nouns)
'restriction-edit'   => 'Golygu',
'restriction-move'   => 'Symud',
'restriction-create' => 'Gosod',

# Restriction levels
'restriction-level-sysop' => 'llwyr diogelwyd',
'restriction-level-all'   => 'unrhyw lefel',

# Undelete
'undelete'                 => 'Gwrthdroi tudalen wedi dileuo',
'undeletepage'             => 'Gwyliwch ac adferiwch tudalennau wedi dileuo',
'viewdeletedpage'          => "Gweld tudalennau sydd wedi'u dileu",
'undeletepagetext'         => "Mae'r tudalennau isod wedi cael eu dileuo ond mae nhw'n dal yn yr archif ac maen bosibl adferio nhw. Mae'r archif yn cael eu glanhau o dro i dro.",
'undeleteextrahelp'        => "I adfer y dudalen gyfan, gadewch pob blwch ticio'n wag a phwyswch y botwm '''''Adfer'''''. I adfer rhai diwygiadau'n unig, ticiwch y blychau ar gyfer y diwygiadau dewisedig, a phwyswch ar '''''Adfer'''''. Os y pwyswch ar '''''Ailosod''''' bydd y blwch sylwadau a phob blwch ticio yn gwacáu.",
'undeleterevisions'        => 'Gosodwyd $1 {{PLURAL:$1|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn}} yn yr archif',
'undeletehistory'          => "Os adferiwch y tudalen, fydd holl y fersiwnau yn gael eu adferio yn yr hanes. Os mae tudalen newydd wedi gael eu creu ers i'r tudalen bod yn dileu, fydd y fersiwnau adferol yn dangos yn yr hanes gynt ond ni fydd y fersiwn cyfoes yn gael eu allosodi.",
'undeletehistorynoadmin'   => "Mae'r dudalen hon wedi'i dileu. Dangosir y rheswm am y dileu isod, gyda manylion o'r holl ddefnyddwyr sydd wedi golygu'r dudalen cyn y dileu. Dim ond gweinyddwyr sydd yn gallu gweld testun y diwygiadau i'r dudalen.",
'undelete-revision'        => 'Testun y golygiad o $1 (fel ag yr oedd am $2), a ddilëwyd gan $3:',
'undeletebtn'              => 'Adferiwch!',
'undeletereset'            => 'Ailosod',
'undeletecomment'          => 'Sylwadau:',
'undeletedarticle'         => 'wedi adferio "$1"',
'undeletedrevisions'       => 'wedi adfer $1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'undeletedrevisions-files' => 'Adferwyd $1 {{PLURAL:$1|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn|fersiwn}} a $2 {{PLURAL:$2|ffeil|ffeil|ffeil|ffeil|ffeil|ffeil}}',
'undeletedfiles'           => 'Adferwyd $1 {{PLURAL:$1|ffeil|ffeil|ffeil|ffeil|ffeil|ffeil}}',
'undeletedpage'            => "<big>'''Adferwyd $1'''</big>

Ceir cofnod o'r tudalennau a ddilëwyd neu a adferwyd yn ddiweddar ar y [[Special:Log/delete|lòg dileuon]].",
'undelete-header'          => "Ewch i'r [[Special:Log/delete|lòg dileuon]] i weld tudalennau a ddilëwyd yn ddiweddar.",
'undelete-search-box'      => "Chwilio'r tudalennau a ddilëwyd",
'undelete-search-prefix'   => 'Dangos tudalennau gan ddechrau gyda:',
'undelete-search-submit'   => 'Chwilio',
'undelete-no-results'      => 'Ni chafwyd hyd i dudalennau cyfatebol yn archif y dileuon.',

# Namespace form on various pages
'namespace'      => 'Parth:',
'invert'         => 'Dewiswch pob parth heblaw am hwn',
'blanknamespace' => '(Prif)',

# Contributions
'contributions' => "Cyfraniadau'r defnyddiwr",
'mycontris'     => 'Fy nghyfraniadau',
'contribsub2'   => 'Dros $1 ($2)',
'nocontribs'    => "Heb ddod o hyd i newidiadau gyda'r maen prawf hwn.",
'ucnote'        => 'Isod mae y <b>$1</b> newidiadau yn y <b>$2</b> dyddiau olaf am defnyddwr hwn.',
'uclinks'       => 'Gwelwch y $1 newidiadau olaf; gwelwch y $2 dyddiau olaf.',
'uctop'         => '(cyfredol)',
'month'         => 'Cyfraniadau hyd at fis:',
'year'          => 'Cyfraniadau hyd at y flwyddyn:',

'sp-contributions-newbies'     => 'Dangos cyfraniadau gan gyfrifon newydd yn unig',
'sp-contributions-newbies-sub' => 'Ar gyfer cyfrifon newydd',
'sp-contributions-blocklog'    => 'Lòg blocio',
'sp-contributions-search'      => 'Chwilio am gyfraniadau',
'sp-contributions-username'    => 'Cyfeiriad IP neu enw defnyddiwr:',
'sp-contributions-submit'      => 'Chwilier',

'sp-newimages-showfrom' => "Dangos ffeiliau sy'n newydd ers: $1",

# What links here
'whatlinkshere'       => "Beth sy'n cysylltu yma",
'whatlinkshere-title' => "Tudalennau sy'n cysylltu â $1",
'whatlinkshere-page'  => 'Tudalen:',
'linklistsub'         => '(Rhestr cysylltiadau)',
'linkshere'           => "Mae'r tudalennau isod yn cysylltu â '''[[:$1]]''':",
'nolinkshere'         => "Nid oes cyswllt ar unrhyw dudalen arall yn arwain at '''[[:$1]]'''.",
'nolinkshere-ns'      => "Nid oes cyswllt ar unrhyw dudalen yn y parth dewisedig yn arwain at '''[[:$1]]'''.",
'isredirect'          => 'tudalen ail-gyfeirio',
'istemplate'          => 'cynhwysiad',
'whatlinkshere-prev'  => '{{PLURAL:$1|cynt|cynt|$1 cynt|$1 cynt|$1 cynt|$1 cynt}}',
'whatlinkshere-next'  => '{{PLURAL:$1|nesaf|nesaf|$1 nesaf|$1 nesaf|$1 nesaf|$1 nesaf}}',
'whatlinkshere-links' => '← cysylltiadau',

# Block/unblock
'blockip'                     => "Blocio'r defnyddiwr",
'blockiptext'                 => "Defnyddiwch y ffurflen isod i flocio cyfeiriad IP neu ddefnyddiwr rhag ysgrifennu i'r databas. Dylech chi ddim ond gwneud hyn er mwyn rhwystro fandaliaeth a chan ddilyn polisi'r wici. Llenwch y rheswm am y bloc yn y blwch isod -- dywedwch pa dudalen sydd wedi cael ei fandaleiddio.",
'ipaddress'                   => 'Cyfeiriad IP:',
'ipadressorusername'          => 'Cyfeiriad IP neu enw defnyddiwr:',
'ipbexpiry'                   => 'Am gyfnod o:',
'ipbreason'                   => 'Rheswm:',
'ipbreasonotherlist'          => 'Rheswm arall',
'ipbreason-dropdown'          => '*Rhesymau cyffredin dros flocio
** Gosod gwybodaeth anghywir
** Dileu cynnwys tudalennau
** Gosod cysylltiadau spam i wefannau eraill
** Gosod dwli ar dudalennau
** Ymddwyn yn fygythiol/tarfu
** Camddefnyddio cyfrifon niferus
** Enw defnyddiwr annerbyniol',
'ipbcreateaccount'            => 'Atal y gallu i greu cyfrif',
'ipbemailban'                 => 'Atal y defnyddiwr rhag anfon e-bost',
'ipbenableautoblock'          => "Blocio'n awtomatig y cyfeiriad IP diwethaf y defnyddiodd y defnyddiwr hwn, ac unrhyw gyfeiriad IP arall y bydd yn ceisio defnyddio i olygu ohono.",
'ipbsubmit'                   => 'Blociwch y defnyddiwr hwn',
'ipbother'                    => 'Cyfnod arall:',
'ipboptions'                  => '2 awr:2 hours,ddiwrnod:1 day,3 niwrnod:3 days,wythnos:1 week,bythefnos:2 weeks,fis:1 month,3 mis:3 months,6 mis:6 months,flwyddyn:1 year,5 mlynedd:5 years,amhenodol:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'arall',
'ipbotherreason'              => 'Rheswm arall:',
'ipbhidename'                 => "Cuddio'r enw defnyddiwr/IP o'r lòg blocio, rhestr y blociau cyfredol a'r rhestr defnyddwyr",
'badipaddress'                => 'Cyfeiriad IP annilys.',
'blockipsuccesssub'           => 'Y blocio wedi llwyddo',
'blockipsuccesstext'          => 'Mae cyfeiriad IP [[Special:Contributions/$1|$1]] wedi cael ei flocio.
<br />Gwelwch [[Special:Ipblocklist|restr y blociau IP]] er mwyn arolygu blociau.',
'ipb-edit-dropdown'           => "Golygu'r rhesymau dros flocio",
'ipb-unblock-addr'            => 'Datflocio $1',
'ipb-unblock'                 => 'Datflocio enw defnyddiwr neu cyfeiriad IP',
'ipb-blocklist-addr'          => 'Gweld y blociau cyfredol ar gyfer $1',
'ipb-blocklist'               => 'Dangos y blociau cyfredol',
'unblockip'                   => 'Di-blociwch cyfeiriad IP',
'unblockiptext'               => "Defnyddwch y ffurflen isod i di-blocio mynedfa ysgrifenol i cyfeiriad IP sydd wedi cael eu blocio'n gynt.",
'ipusubmit'                   => 'Datflociwch y cyfeiriad hwn',
'ipblocklist'                 => "Rhestr o'r cyfeiriadau IP ac enwau defnyddwyr sydd wedi'u blocio",
'ipblocklist-legend'          => 'Dod o hyd i ddefnyddiwr sydd wedi ei blocio',
'ipblocklist-username'        => "Enw'r defnyddiwr neu ei gyfeiriad IP:",
'ipblocklist-submit'          => 'Chwilier',
'blocklistline'               => '$1, $2 wedi blocio $3 ($4)',
'infiniteblock'               => 'bloc parhaus',
'expiringblock'               => 'yn dod i ben $1',
'anononlyblock'               => 'ataliwyd dim ond pan nad yw wedi mewngofnodi',
'noautoblockblock'            => 'analluogwyd blocio awtomatig',
'createaccountblock'          => 'ataliwyd y gallu i greu cyfrif',
'emailblock'                  => 'rhwystrwyd e-bostio',
'ipblocklist-empty'           => "Mae'r rhestr blociau'n wag.",
'ipblocklist-no-results'      => 'Nid yw cyfeiriad IP neu enw defnyddiwr yr ymholiad wedi ei flocio.',
'blocklink'                   => 'bloc',
'unblocklink'                 => 'di-bloc',
'contribslink'                => 'cyfraniadau',
'autoblocker'                 => 'Rydych chi wedi cael eich blocio yn awtomatig gan eich bod chi\'n rhannu cyfeiriad IP gyda "[[User:$1|$1]]". Dyma\'r rheswm a roddwyd dros flocio $1: "$2".',
'blocklogpage'                => 'Lòg blociau',
'blocklogentry'               => 'wedi blocio "[[$1]]" am gyfnod o $2 $3',
'blocklogtext'                => "Dyma lòg o'r holl weithredoedd blocio a datflocio. Nid yw'r cyfeiriadau IP sydd wedi cael eu blocio'n awtomatig ar y rhestr. Gweler [[Special:Ipblocklist|rhestr y blociau IP]] am restr y blociau a'r gwaharddiadau sydd yn weithredol ar hyn o bryd.",
'unblocklogentry'             => 'wedi datblocio "$1"',
'block-log-flags-anononly'    => 'defnyddwyr anhysbys yn unig',
'block-log-flags-nocreate'    => 'analluogwyd creu cyfrif',
'block-log-flags-noautoblock' => 'analluogwyd blocio awtomatig',
'block-log-flags-noemail'     => 'analluogwyd e-bostio',
'range_block_disabled'        => 'Mae gallu sysop i creu dewis o blociau wedi anablo.',
'ipb_expiry_invalid'          => 'Amser terfynu yn annilys.',
'ipb_already_blocked'         => 'Mae "$1" eisoes wedi ei flocio',
'ipb_blocked_as_range'        => "Gwall: Nid yw'r IP $1 wedi ei blocio'n uniongyrchol ac felly ni ellir ei datflocio. Wedi dweud hynny, y mae'n rhan o'r amrediad $2 sydd wedi ei blocio; gellir datflocio'r amrediad.",
'ip_range_invalid'            => 'Dewis IP annilys.',
'proxyblocker'                => 'Blociwr dirprwy',
'proxyblockreason'            => "Mae eich cyfeiriad IP wedi'i flocio gan ei fod yn ddirprwy agored (open proxy). Cysylltwch â'ch gweinyddwr rhyngrwyd neu gymorth technegol er mwyn eu hysbysu am y broblem ddifrifol yma.",
'proxyblocksuccess'           => 'Wedi gorffen.',
'sorbsreason'                 => 'Mae eich cyfeiriad IP wedi cael ei osod ar Restr Twll-du DNS fel dirprwy agored.',
'sorbs_create_account_reason' => 'Mae eich cyfeiriad IP wedi cael ei osod ar Restr Twll-du DNS fel dirprwy agored. Ni chaniateir creu cyfrif.',

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
'lockdbsuccesstext'   => "Mae'r databas wedi'i gloi. 
<br />Cofiwch [[Special:Unlockdb|ddatgloi'r]] databas pan fydd y gwaith cynnal ar ben.",
'unlockdbsuccesstext' => "Mae'r databas wedi'i ddatgloi.",

# Move page
'move-page-legend'                => 'Symud tudalen',
'movepagetext'            => "Wrth ddefnyddio'r ffurflen isod byddwch yn ail-enwi tudalen, gan symud ei hanes gyfan i'r enw newydd.
Bydd yr hen deitl yn troi'n dudalen ail-gyfeirio i'r teitl newydd. 
Ni fydd cysylltiadau i'r hen deitl yn newid; rhaid cywiro ail-gyfeiriadau dwbl ac ail-gyfeiriadau tor eich hunan.
Eich cyfrifoldeb chi yw sicrhau bod cysylltiadau wici'n dal i arwain at y man iawn!

Sylwch '''na fydd''' y dudalen yn symud os oes yna dudalen o'r enw newydd yn bodoli'n barod ar y databas (heblaw ei bod hi'n wag neu'n ail-gyfeiriad heb unrhyw hanes golygu). Felly, os y gwnewch gamgymeriad wrth ail-enwi tudalen dylai fod yn bosibl ei hail-enwi eto ar unwaith wrth yr enw gwreiddiol. Hefyd, mae'n amhosibl ysgrifennu dros ben tudalen sydd yn bodoli'n barod.

<b>DALIER SYLW!</b>
Gall hwn fod yn newid sydyn a llym i dudalen boblogaidd; byddwch yn siwr eich bod chi'n deall y canlyniadau cyn mynd ati.",
'movepagetalktext'        => "Bydd y dudalen sgwrs yn symud gyda'r dudalen hon '''onibai:'''
*bod tudalen sgwrs wrth yr enw newydd yn bodoli'n barod
*bod y blwch isod heb ei farcio.

Os felly, gallwch symud y dudalen sgwrs neu ei gyfuno ar ôl symud y dudalen ei hun.",
'movearticle'             => 'Symud y dudalen:',
'movenologin'             => 'Heb fewngofnodi',
'movenologintext'         => "Mae'n rhaid bod yn ddefnyddiwr cofrestredig a'ch bod wedi [[Special:Userlogin|mewngofnodi]] cyn medru symud tudalen.",
'movenotallowed'          => 'Nid oes caniatâd gennych i symud tudalennau ar {{SITENAME}}.',
'newtitle'                => "I'r teitl newydd:",
'move-watch'              => 'Gwylier y dudalen hon',
'movepagebtn'             => 'Symud tudalen',
'pagemovedsub'            => 'Symud yn llwyddiannus',
'movepage-moved'          => '<big>\'\'\'Symudwyd "$1" i "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Mae tudalen gyda'r darpar enw yn bodoli'n barod, neu mae eich darpar enw yn annilys.
Dewiswch enw arall os gwelwch yn dda.",
'talkexists'              => "Mae'r tudalen wedi symud yn llwyddiannus, ond roedd hi'n amhosibl symud y tudalen sgwrs am achos roedd ne un efo'r teitl newydd yn bodoli'n barod. Cysylltwch nhw eich hun, os gwelwch yn dda.",
'movedto'                 => 'symud i',
'movetalk'                => 'Symud y dudalen sgwrs hefyd',
'talkpagemoved'           => "Mae'r tudalen sgwrs hefyd wedi symud.",
'talkpagenotmoved'        => "Dydy'r tudalen sgwrs <strong>ddim</strong> wedi symud.",
'1movedto2'               => 'Wedi symud [[$1]] i [[$2]]',
'1movedto2_redir'         => 'Wedi symud [[$1]] i [[$2]] trwy ailgyfeiriad.',
'movelogpage'             => 'Lòg symud tudalennau',
'movelogpagetext'         => "Isod mae rhestr y tudalennau sydd wedi'u symud",
'movereason'              => 'Rheswm:',
'revertmove'              => 'symud nôl',
'delete_and_move'         => 'Dileu a symud',
'delete_and_move_text'    => "==Angen dileu==

Mae'r erthygl \"[[\$1]]\" yn bodoli'n barod. Ydych chi am ddileu'r erthygl er mwyn cwblhau'r symudiad?",
'delete_and_move_confirm' => "Ie, dileu'r dudalen",
'delete_and_move_reason'  => "Wedi'i dileu er mwyn symud tudalen arall yn ei lle.",
'selfmove'                => "Rydych chi'n ceisio symud tudalen dros ben ei hunan, sy'n amhosib.",
'immobile_namespace'      => "Mae teitl y dudalen gwreiddiol neu'r cyrchfan yn arbennig; ni ellir symud tudalennau i'r parth hwnnw nag oddi wrtho.",

# Export
'export'            => 'Allforio tudalennau',
'exporttext'        => "Gallwch allforio testun a hanes golygu tudalen penodol neu set o dudalennau wedi'u lapio mewn côd XML. Gall hwn wedyn gael ei fewnforio i wici arall sy'n defnyddio meddalwedd MediaWiki, trwy ddefnyddio'r [[Special:Import|dudalen mewnforio]].

I allforio tudalennau, teipiwch y teitlau yn y bocs testun isod, bobi linell i'r teitlau; a dewis p'un ai ydych chi eisiau'r diwygiad presennol a'r holl fersiynnau blaenorol, gyda hanes y dudalen; ynteu a ydych am y diwygiad presennol a'r wybodaeth am y golygiad diweddaraf yn unig.

Yn achos yr ail ddewis, mae modd defnyddio cyswllt, e.e. [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] ar gyfer y dudalen \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Cynnwys y diwygiad diweddaraf yn unig, nid yr hanes llawn',
'exportnohistory'   => "----
'''Sylwer:''' er mwyn peidio â gor-lwytho'r gweinydd, analluogwyd allforio hanes llawn y tudalennau.",
'export-submit'     => 'Allforier',
'export-addcattext' => "Ychwanegu tudalennau i'w hallforio o'r categori:",
'export-addcat'     => 'Ychwaneger',
'export-download'   => 'Cynnig rhoi ar gadw ar ffurf ffeil',

# Namespace 8 related
'allmessages'               => 'Pob neges',
'allmessagesname'           => 'Enw',
'allmessagesdefault'        => 'Testun rhagosodedig',
'allmessagescurrent'        => 'Testun cyfredol',
'allmessagestext'           => "Dyma restr o'r holl negeseuon yn y parth MediaWiki:",
'allmessagesnotsupportedDB' => "Nid yw '''{{ns:special}}:PobNeges''' yn cael ei gynnal gan fod '''\$wgUseDatabaseMessages''' wedi ei ddiffodd.",
'allmessagesfilter'         => 'Hidl enw neges:',
'allmessagesmodified'       => 'Dangos y rhai a ddiwygiwyd yn unig',

# Thumbnails
'thumbnail-more'  => 'Helaethwch',
'filemissing'     => 'Ffeil yn eisiau',
'thumbnail_error' => "Cafwyd gwall wrth greu'r mân-lun: $1",
'djvu_page_error' => 'Y dudalen DjVu allan o amrediad',
'djvu_no_xml'     => 'Ddim yn gallu mofyn XML ar gyfer ffeil DjVu',

# Special:Import
'import'                => 'Mewnforio tudalennau',
'importinterwiki'       => 'Mewnforiad traws-wici',
'importtext'            => "Os gwelwch yn dda, allforiwch y ffeil o'r wici gwreiddiol gan ddefnyddio'r nodwedd <b>Special:Export</b>, cadwch hi i'ch disg, ac uwchlwythwch hi fan hyn.",
'import-revision-count' => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'importfailed'          => 'Mewnforio wedi methu: $1',
'importnotext'          => 'Gwag, neu heb destun',
'importsuccess'         => 'Mewnforio wedi llwyddo!',
'importhistoryconflict' => "Mae gwrthdaro rhwng adolygiadau hanes (efallai eich bod chi wedi mewnforio'r dudalen o'r blaen)",
'importnosources'       => "Ni ddiffiniwyd unrhyw ffynonellau mewnforio traws-wici, ac mae uwchlwytho hanesion yn uniongyrchol wedi'i analluogi.",
'importnofile'          => 'Ni uwchlwythwyd unrhyw ffeil mewnforio.',

# Import log
'importlogpage'                    => 'Lòg mewnforio',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|diwygiad|diwygiad|ddiwygiad|diwygiad|diwygiad|diwygiad}} o $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Fy nhudalen defnyddiwr',
'tooltip-pt-mytalk'               => 'Fy nhudalen sgwrs',
'tooltip-pt-preferences'          => 'Fy newisiadau',
'tooltip-pt-watchlist'            => 'Rydych yn dilyn hynt y tudalennau sydd ar y rhestr hon',
'tooltip-pt-mycontris'            => 'Fy nghyfraniadau yn nhrefn amser',
'tooltip-pt-login'                => "Fe'ch anogir i fewngofnodi, er nad oes rhaid gwneud.",
'tooltip-pt-anonlogin'            => "Fe'ch anogir i fewngofnodi, er nad oes rhaid gwneud.",
'tooltip-pt-logout'               => 'Allgofnodi',
'tooltip-ca-talk'                 => 'Sgwrsio am y dudalen',
'tooltip-ca-edit'                 => "Gallwch olygu'r dudalen hon. Da o beth fyddai defnyddio'r botwm 'Dangos rhagolwg' cyn rhoi ar gadw.",
'tooltip-ca-addsection'           => "Ychwanegu sylw i'r drafodaeth",
'tooltip-ca-viewsource'           => "Mae'r dudalen hon wedi'i diogelu. Gallwch weld y côd yma.",
'tooltip-ca-protect'              => "Diogelu'r dudalen hon",
'tooltip-ca-delete'               => "Dileu'r dudalen hon",
'tooltip-ca-move'                 => 'Symud y dudalen hon',
'tooltip-ca-watch'                => "Ychwanegu'r dudalen hon at eich rhestr gwylio",
'tooltip-ca-unwatch'              => "Tynnu'r dudalen oddi ar eich rhestr gwylio",
'tooltip-search'                  => 'Chwilio {{SITENAME}}',
'tooltip-p-logo'                  => 'Yr Hafan',
'tooltip-n-mainpage'              => "Ymweld â'r Hafan",
'tooltip-n-portal'                => "Pethau i'w gwneud, adnoddau a thudalennau'r gymuned",
'tooltip-n-currentevents'         => 'Gwybodaeth yn gysylltiedig â materion cyfoes',
'tooltip-n-recentchanges'         => 'Rhestr y newidiadau diweddar ar y wici.',
'tooltip-n-randompage'            => 'Llwytho tudalen ar hap',
'tooltip-n-help'                  => 'Tudalennau cymorth',
'tooltip-n-sitesupport'           => "Cefnogi'n ariannol",
'tooltip-t-whatlinkshere'         => "Rhestr o bob tudalen sy'n cysylltu â hon",
'tooltip-t-contributions'         => "Gweld rhestr cyfraniadau'r defnyddiwr hwn",
'tooltip-t-emailuser'             => 'Anfon e-bost at y defnyddiwr hwn',
'tooltip-t-upload'                => 'Uwchlwytho ffeiliau',
'tooltip-t-specialpages'          => "Rhestr o'r holl dudalennau arbennig",
'tooltip-ca-nstab-user'           => 'Gweld tudalen y defnyddiwr',
'tooltip-ca-nstab-project'        => "Ymweld â'r Hafan",
'tooltip-ca-nstab-image'          => 'Gweld tudalen y ffeil',
'tooltip-ca-nstab-template'       => 'Dangos y nodyn',
'tooltip-ca-nstab-help'           => 'Gweld y dudalen gymorth',
'tooltip-ca-nstab-category'       => 'Dangos tudalen y categori',
'tooltip-minoredit'               => 'Marciwch hwn yn olygiad bychan.',
'tooltip-save'                    => 'Cadwch eich newidiadau',
'tooltip-preview'                 => "Dangos rhagolwg o'r newidiadau; defnyddiwch cyn cadw os gwelwch yn dda!",
'tooltip-diff'                    => "Dangos y newidiadau rydych chi wedi gwneud i'r testun.",
'tooltip-compareselectedversions' => 'Cymharwch y fersiynau detholedig.',
'tooltip-watch'                   => "Ychwanegu'r dudalen hon at eich rhestr gwylio",

# Metadata
'nodublincore'      => "Mae metadata RDF 'Dublin Core' wedi cael ei analluogi ar y gwasanaethwr hwn.",
'nocreativecommons' => "Mae metadata RDF 'Creative Commons' wedi'i analluogi ar y gwasanaethwr hwn.",
'notacceptable'     => "Dydi'r gwasanaethwr wici ddim yn medru rhoi'r data mewn fformat darllenadwy i'ch cleient.",

# Attribution
'anonymous'        => 'Defnyddwyr anhysbys {{SITENAME}}',
'lastmodifiedatby' => 'Newidiwyd y dudalen hon ddiwethaf $2, $1 gan $3', # $1 date, $2 time, $3 user
'othercontribs'    => 'Yn seiliedig ar waith gan $1.',
'others'           => 'eraill',
'creditspage'      => "Cydnabyddiaethau'r dudalen",
'nocredits'        => "Does dim cydnabyddiaethau i'r dudalen hon.",

# Spam protection
'spamprotectiontitle'    => 'Hidlydd amddiffyn rhag sbam',
'spamprotectiontext'     => "Mae'r dudalen wedi methu cadw, yn fwy na thebyg oherwydd bod cysylltiad allanol ar y dudalen wedi'i flocio gan yr hidlydd sbam.

Edrychwch drwy'r canlynol am batrymau sy'n cael eu blocio:",
'spamprotectionmatch'    => 'Dyma beth gychwynnodd ein hidlydd amddiffyn rhag sbam: $1',
'subcategorycount'       => "{{PLURAL:$1|Nid oes $1|Mae $1|Mae $1|Mae $1|Mae $1|Mae $1}} is-gategori i'r categori hwn.",
'categoryarticlecount'   => 'Mae $1 {{PLURAL:$1|erthygl|erthygl|erthygl|erthygl|erthygl|erthygl}} yn y categori hwn.',
'category-media-count'   => 'Mae {{PLURAL:$1|ffeil|ffeil|ffeil|ffeil|ffeil|ffeil}} yn y categori hwn.',
'listingcontinuesabbrev' => ' parh.',
'spambot_username'       => 'Teclyn clirio sbam MediaWici',
'spam_reverting'         => "Yn troi nôl i'r diwygiad diweddaraf sydd ddim yn cynnwys cysylltiadau i $1",
'spam_blanking'          => 'Roedd cysylltiadau i $1 gan bob golygiad, yn blancio',

# Info page
'infosubtitle'   => 'Gwybodaeth am y dudalen',
'numedits'       => 'Nifer y golygiadau (tudalen): $1',
'numtalkedits'   => 'Nifer y golygiadau (tudalen sgwrs): $1',
'numwatchers'    => 'Nifer y gwylwyr: $1',
'numauthors'     => 'Nifer o awduron yr erthygl: $1',
'numtalkauthors' => 'Nifer o awduron yr erthygl (tudalen sgwrs): $1',

# Math options
'mw_math_png'    => 'Arddangos symbolau mathemateg fel delwedd PNG bob amser',
'mw_math_simple' => 'HTML os yn syml iawn, PNG fel arall',
'mw_math_html'   => 'HTML os yn bosib, PNG fel arall',
'mw_math_source' => 'Gadewch fel côd TeX (ar gyfer porwyr testun)',
'mw_math_modern' => 'Argymelledig ar gyfer porwyr modern',
'mw_math_mathml' => 'MathML os yn bosib (arbrofol)',

# Patrolling
'rcpatroldisabled'                    => "Patrol Newidiadau Diweddar wedi'i analluogi",
'rcpatroldisabledtext'                => 'Analluogwyd y nodwedd Patrol Newidiadau Diweddar.',
'markedaspatrollederror-noautopatrol' => "Ni chaniateir i chi farcio'ch newidiadau eich hunan fel rhai derbyniol.",

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
'previousdiff' => "← Cymharer â'r fersiwn gynt",
'nextdiff'     => "Cymharer â'r fersiwn dilynol →",

# Media information
'mediawarning'         => "'''Rhybudd''': Gallasai'r ffeil hon gynnwys côd maleisus; os ydyw mae'n bosib y bydd eich cyfrifiadur yn cael ei danseilio wrth lwytho'r ffeil.
<hr />",
'imagemaxsize'         => 'Tocio maint y delweddau ar y tudalennau disgrifiad i:',
'thumbsize'            => 'Maint mân-lun :',
'file-info'            => '(maint y ffeil: $1, ffurf MIME: $2)',
'file-info-size'       => '($1 × $2 picsel, maint y ffeil: $3, ffurf MIME: $4)',
'file-nohires'         => '<small>Wedi ei chwyddo hyd yr eithaf.</small>',
'svg-long-desc'        => '(Ffeil SVG, maint enwol $1 × $2 picsel, maint y ffeil: $3)',
'show-big-image'       => 'Maint llawn',
'show-big-image-thumb' => '<small>Maint y rhagolwg: $1 × $2 picsel</small>',

# Special:Newimages
'newimages'    => 'Oriel y ffeiliau newydd',
'showhidebots' => '($1 botiau)',
'noimages'     => "Does dim byd i'w weld.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Mae'r ffeil hon yn cynnwys gwybodaeth ychwanegol, sydd mwy na thebyg wedi dod o'r camera digidol neu'r sganiwr a ddefnyddiwyd i greu'r ffeil neu ei digido. Os yw'r ffeil wedi ei cael ei newid ers ei chreu efallai nad yw'r manylion hyn yn dal i fod yn gywir.",
'metadata-expand'   => 'Dangos manylion estynedig',
'metadata-collapse' => 'Cuddio manylion estynedig',

# EXIF tags
'exif-artist' => 'Awdur',

# External editor support
'edit-externally'      => 'Golygwch y ffeil gyda rhaglen allanol',
'edit-externally-help' => 'Gwelwch y [http://meta.wikimedia.org/wiki/Help:External_editors cyfarwyddiadau gosod] am fwy o wybodaeth.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'holl',
'imagelistall'     => 'holl',
'watchlistall2'    => 'holl',
'namespacesall'    => 'pob un',
'monthsall'        => 'pob mis',

# E-mail address confirmation
'confirmemail'            => "Cadarnhau'r cyfeiriad e-bost",
'confirmemail_noemail'    => 'Does dim cyfeiriad e-bost dilys wedi ei osod yn eich [[Special:Preferences|dewisiadau defnyddiwr]].',
'confirmemail_text'       => "Cyn i chi allu defnyddio'r nodweddion e-bost, mae'n rhaid i {{SITENAME}} ddilysu'ch cyfeiriad e-bost. Pwyswch y botwm isod er mwyn anfon côd cadarnhau i'ch cyfeiriad e-bost. Bydd yr e-bost yn cynnwys cyswllt gyda chôd ynddi; llwythwch y cyswllt ar eich porwr er mwyn cadarnhau dilysrwydd eich cyfeiriad e-bost.",
'confirmemail_pending'    => '<div class="error">
Mae côd cadarnhau eisoes wedi ei anfon atoch; os ydych newydd greu\'ch cyfrif, hwyrach y gallech ddisgwyl rhai munudau amdano cyn gofyn yr eilwaith am gôd newydd.
</div>',
'confirmemail_send'       => 'Postiwch gôd cadarnhau',
'confirmemail_sent'       => "Wedi anfon e-bost er mwyn cadarnhau'r cyfeiriad.",
'confirmemail_oncreate'   => "Anfonwyd côd cadarnhau at eich cyfeiriad e-bost. 
Nid oes rhaid wrth y côd wrth fewngofnodi, ond rhaid ei ddefnyddio er mwyn galluogi offer ar y wici sy'n defnyddio e-bost.",
'confirmemail_sendfailed' => "Ni fu'n bosibl danfon yr e-bost cadarnháu. Gwiriwch y cyfeiriad am nodau annilys.

Dychwelodd yr ebostydd: $1",
'confirmemail_invalid'    => 'Côd cadarnhau annilys. Efallai fod y côd wedi dod i ben.',
'confirmemail_needlogin'  => 'Rhaid $1 er mwyn cadarnhau eich cyfeiriad e-bost.',
'confirmemail_success'    => "Mae eich cyfeiriad e-bost wedi'i gadarnhau. Cewch fewngofnodi a mwynhau'r Wici.",
'confirmemail_loggedin'   => 'Cadarnhawyd eich cyfeiriad e-bost.',
'confirmemail_error'      => 'Cafwyd gwall wrth ddanfon eich cadarnhad.',
'confirmemail_subject'    => 'Cadarnhâd cyfeiriad e-bost ar {{SITENAME}}',
'confirmemail_body'       => 'Mae rhywun (chi, yn fwy na thebyg, o\'r cyfeiriad IP $1) wedi cofrestru\'r cyfrif "$2" ar {{SITENAME}} gyda\'r cyfeiriad e-bost hwn.

I gadarnhau mai chi yn wir yw perchennog y cyfrif hwn, ac i alluogi nodweddion e-bost ar {{SITENAME}}, agorwch y cyswllt hwn yn eich porwr:

$3

Os *nad* chi sydd berchen ar y cyfrif hwn, peidiwch a dilyn y cyswllt. Bydd y côd cadarnhau yn dod i ben am $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Analluogwyd cynhwysiad rhyng-wici]',
'scarytranscludefailed'   => '[Ymddiheurwn; methwyd nôl y nodyn ar gyfer $1]',
'scarytranscludetoolong'  => "[Ymddiheurwn; mae'r URL yn rhy hir]",

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Cysylltiadau \'Trackback\' ar gyfer yr erthygl hon:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Dileu])',
'trackbacklink'     => "Cyswllt 'trackback'",
'trackbackdeleteok' => "Dilewyd y cyswllt 'trackback' yn lwyddiannus.",

# Delete conflict
'deletedwhileediting' => 'Rhybudd: Dilëwyd y dudalen ers i chi ddechrau golygu!',
'confirmrecreate'     => "Mae'r defnyddiwr [[User:$1|$1]] ([[User_talk:$1|Sgwrs]]) wedi dileu'r erthygl hon ers i chi ddechrau golygu. Y rheswm oedd:
: ''$2''
Cadarnhewch eich bod chi wir am ail-greu'r erthygl.",
'recreate'            => 'Ail-greu',

# HTML dump
'redirectingto' => "Wrthi'n ailgyfeirio i [[:$1|$1]]...",

# action=purge
'confirm_purge'        => "Clirio'r dudalen o'r storfa?

$1",
'confirm_purge_button' => 'Iawn',

# AJAX search
'articletitles' => "Erthyglau'n dechrau gyda: ''$1''",

# Table pager
'ascending_abbrev'  => 'esgynnol',
'descending_abbrev' => 'am lawr',
'table_pager_next'  => 'Tudalen nesaf',
'table_pager_prev'  => 'Tudalen blaenorol',
'table_pager_first' => 'Tudalen gyntaf',
'table_pager_last'  => 'Tudalen olaf',
'table_pager_limit' => 'Dangos $1 eitem y dudalen',
'table_pager_empty' => 'Dim canlyniadau',

# Auto-summaries
'autosumm-blank'   => "Yn gwacau'r dudalen yn llwyr",
'autoredircomment' => 'Yn ailgyfeirio at [[$1]]',
'autosumm-new'     => 'Tudalen newydd: $1',

# Live preview
'livepreview-loading' => "Wrthi'n llwytho…",
'livepreview-ready'   => 'Llwytho… Ar ben!',
'livepreview-failed'  => 'Y rhagolwg byw wedi methu! Rhowch gynnig ar y rhagolwg arferol.',
'livepreview-error'   => 'Wedi methu cysylltu: $1 "$2". Rhowch gynnig ar y rhagolwg arferol.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Hwyrach na ddangosir isod y newidiadau a ddigwyddodd o fewn y $1 eiliad ddiwethaf.',
'lag-warn-high'   => 'Mae gweinydd y data-bas ar ei hôl hi: efallai nad ymddengys newidiadau o fewn y $1 eiliad ddiwethaf ar y rhestr.',

# Watchlist editor
'watchlistedit-numitems'       => 'Mae {{PLURAL:$1|$1 tudalen|$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 o dudalennau}} ar eich rhestr gwylio, heb gynnwys tudalennau sgwrs.',
'watchlistedit-noitems'        => "Mae'ch rhestr gwylio'n wag.",
'watchlistedit-normal-title'   => "Golygu'r rhestr gwylio",
'watchlistedit-normal-legend'  => 'Tynnu tudalennau oddi ar y rhestr gwylio',
'watchlistedit-normal-explain' => "Rhestrir y teitlau ar eich rhestr gwylio isod. I dynnu teitl oddi ar y rhestr, ticiwch y blwch ar ei gyfer, yna cliciwch 'Tynnu'r tudalennau'. Gallwch hefyd ddewis golygu'r rhestr gwylio ar ei [[Special:Watchlist/raw|ffurf syml]].",
'watchlistedit-normal-submit'  => "Tynnu'r tudalennau",
'watchlistedit-normal-done'    => 'Tynnwyd {{PLURAL:$1|$1 tudalen|$1 dudalen|$1 dudalen|$1 tudalen|$1 thudalen|$1 tudalen}} oddi ar eich rhestr gwylio:',
'watchlistedit-raw-title'      => 'Golygu ffeil y rhestr gwylio',
'watchlistedit-raw-legend'     => 'Golygu ffeil y rhestr gwylio',
'watchlistedit-raw-explain'    => "Rhestrir y teitlau ar eich rhestr gwylio isod. Gellir newid y rhestr drwy ychwanegu neu dynnu teitlau; gyda llinell yr un i bob teitl. Pan yn barod, pwyswch ar Diweddaru'r rhestr gwylio.
Gallwch hefyd [[Special:Watchlist/edit|ddefnyddio'r rhestr arferol]].",
'watchlistedit-raw-titles'     => 'Teitlau:',
'watchlistedit-raw-submit'     => "Diweddaru'r rhestr gwylio",
'watchlistedit-raw-done'       => 'Diweddarwyd eich rhestr gwylio.',
'watchlistedit-raw-added'      => 'Ychwanegwyd {{PLURAL:$1|1 teitl|$1 teitl|$1 deitl|$1 theitl|$1 theitl|$1 o deitlau}}:',
'watchlistedit-raw-removed'    => 'Tynnwyd {{PLURAL:$1|1 teitl|$1 teitl|$1 deitl|$1 theitl|$1 theitl|$1 o deitlau}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Gweld newidiadau perthnasol',
'watchlisttools-edit' => "Gweld a golygu'r rhestr gwylio",
'watchlisttools-raw'  => "Golygu'r rhestr gwylio syml",

);
