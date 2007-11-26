<?php
/** Icelandic (Íslenska)
 *
 * @addtogroup Language
 *
 * @author Steinninn
 * @author Jóna Þórunn
 * @author Friðrik Bragi Dýrfjörð
 * @author Cessator
 * @author S.Örvarr.S
 * @author Spacebirdy
 * @author G - ג
 * @author Nike
 * @author Siebrand
 * @author SPQRobin
 */

$skinNames = array(
	'standard'    => 'Sígilt',
	'nostalgia'   => 'Gamaldags',
	'cologneblue' => 'Kölnarblátt',
	'monobook'    => 'EinBók',
	'myskin'      => 'Mitt þema',
	'chick'       => 'Gella',
	'simple'      => 'Einfalt',
);

$datePreferences = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
	'ISO 8601',
);

$datePreferenceMigrationMap = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
);

$defaultDateFormat = 'dmyt';

$dateFormats = array(
	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. F Y "kl." H:i',

	'short dmyt time' => 'H:i',
	'short dmyt date' => 'j. M. Y',
	'short dmyt both' => 'j. M. Y "kl." H:i',

	'tdmy time' => 'H:i',
	'tdmy date' => 'j. F Y',
	'tdmy both' => 'H:i, j. F Y',

	'short tdmy time' => 'H:i',
	'short tdmy date' => 'j. M. Y',
	'short tdmy both' => 'H:i, j. M. Y',
);

$magicWords = array(
	'redirect'            => array( 0, "#tilvísun", "#TILVÍSUN", "#REDIRECT" ),
	'nogallery'           => array( 0, "__EMSAFN__", "__NOGALLERY__" ),
	'currentday'          => array( 1, "NÚDAGUR", "CURRENTDAY" ),
	'currentday2'         => array( 1, "NÚDAGUR2", "CURRENTDAY2" ),
	'currentdayname'      => array( 1, "NÚDAGNAFN", "CURRENTDAYNAME" ),
	'currentyear'         => array( 1, "NÚÁR", "CURRENTYEAR" ),
	'currenttime'         => array( 1, "NÚTÍMI", "CURRENTTIME" ),
	'currenthour'         => array( 1, "NÚKTÍMI", "CURRENTHOUR" ),
	'localmonth'          => array( 1, "STMÁN", "LOCALMONTH" ),
	'localmonthname'      => array( 1, "STMÁNNAFN", "LOCALMONTHNAME" ),
	'localmonthabbrev'    => array( 1, "STMÁNST", "LOCALMONTHABBREV" ),
	'localday'            => array( 1, "STDAGUR", "LOCALDAY" ),
	'localday2'           => array( 1, "STDAGUR2", "LOCALDAY2" ),
	'localdayname'        => array( 1, "STDAGNAFN", "LOCALDAYNAME" ),
	'localyear'           => array( 1, "STÁR", "LOCALYEAR" ),
	'localtime'           => array( 1, "STTÍMI", "LOCALTIME" ),
	'localhour'           => array( 1, "STKTÍMI", "LOCALHOUR" ),
	'numberofpages'       => array( 1, "FJLSÍÐA", "NUMBEROFPAGES" ),
	'numberofarticles'    => array( 1, "FJLGREINA", "NUMBEROFARTICLES" ),
	'numberoffiles'       => array( 1, "FJLSKJALA", "NUMBEROFFILES" ),
	'numberofusers'       => array( 1, "FJLNOT", "NUMBEROFUSERS" ),
	'numberofedits'       => array( 1, "FJLBREYT", "NUMBEROFEDITS" ),
	'pagename'            => array( 1, "SÍÐUNAFN", "PAGENAME" ),
	'namespace'           => array( 1, "NAFNSVÆÐI", "NAMESPACE" ),
	'talkspace'           => array( 1, "SPJALLSVÆÐI", "TALKSPACE" ),
	'fullpagename'        => array( 1, "FULLTSÍÐUNF", "FULLPAGENAME" ),
	'img_manualthumb'     => array( 1, "þumall", "thumbnail=$1", "thumb=$1" ),
	'img_right'           => array( 1, "hægri", "right" ),
	'img_left'            => array( 1, "vinstri", "left" ),
	'img_none'            => array( 1, "engin", "none" ),
	'img_width'           => array( 1, "$1dp", "$1px" ),
	'img_center'          => array( 1, "miðja", "center", "centre" ),
	'img_sub'             => array( 1, "undir", "sub" ),
	'img_super'           => array( 1, "yfir", "super", "sup" ),
	'img_top'             => array( 1, "efst", "top" ),
	'img_middle'          => array( 1, "miðja", "middle" ),
	'img_bottom'          => array( 1, "neðst", "bottom" ),
	'img_text_bottom'     => array( 1, "texti-neðst", "text-bottom" ),
	'ns'                  => array( 0, "NR:", "NS:" ),
	'server'              => array( 0, "VEFÞJ", "SERVER" ),
	'servername'          => array( 0, "VEFÞJNF", "SERVERNAME" ),
	'grammar'             => array( 0, "MÁLFRÆÐI:", "GRAMMAR:" ),
	'currentweek'         => array( 1, "NÚVIKA", "CURRENTWEEK" ),
	'localweek'           => array( 1, "STVIKA", "LOCALWEEK" ),
	'plural'              => array( 0, "FLTALA:", "PLURAL:" ),
	'raw'                 => array( 0, "HRÁ:", "RAW:" ),
	'displaytitle'        => array( 1, "SÝNATITIL", "DISPLAYTITLE" ),
	'language'            => array( 0, "#TUNGUMÁL", "#LANGUAGE:" ),
	'special'             => array( 0, "kerfissíða", "special" ),
);

$namespaceNames = array(
	NS_MEDIA          => 'Miðill',
	NS_SPECIAL        => 'Kerfissíða',
	NS_MAIN	          => '',
	NS_TALK	          => 'Spjall',
	NS_USER           => 'Notandi',
	NS_USER_TALK      => 'Notandaspjall',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1spjall',
	NS_IMAGE          => 'Mynd',
	NS_IMAGE_TALK     => 'Myndaspjall',
	NS_MEDIAWIKI      => 'Melding',
	NS_MEDIAWIKI_TALK => 'Meldingarspjall',
	NS_TEMPLATE       => 'Snið',
	NS_TEMPLATE_TALK  => 'Sniðaspjall',
	NS_HELP           => 'Hjálp',
	NS_HELP_TALK      => 'Hjálparspjall',
	NS_CATEGORY       => 'Flokkur',
	NS_CATEGORY_TALK  => 'Flokkaspjall',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( "Tvöfaldar_tilvísanir" ),
	'BrokenRedirects'           => array( "Brotnar_tilvísanir" ),
	'Disambiguations'           => array( "Tenglar_í_aðgreiningarsíður" ),
	'Userlogin'                 => array( "Innskrá" ),
	'Userlogout'                => array( "Útskrá" ),
	'Preferences'               => array( "Stillingar" ),
	'Watchlist'                 => array( "Vaktlistinn" ),
	'Recentchanges'             => array( "Nýlegar_breytingar" ),
	'Upload'                    => array( "Hlaða_inn_skrá" ),
	'Imagelist'                 => array( "Skráalisti" ),
	'Newimages'                 => array( "Myndasafn_nýlegra_skráa" ),
	'Listusers'                 => array( "Notendalisti" ),
	'Statistics'                => array( "Tölfræði" ),
	'Randompage'                => array( "Handahófsvalin_grein" ),
	'Lonelypages'               => array( "Munaðarlausar_síður" ),
	'Uncategorizedpages'        => array( "Óflokkaðar_síður" ),
	'Uncategorizedcategories'   => array( "Óflokkaðir_flokkar" ),
	'Uncategorizedimages'       => array( "Óflokkaðar_skrár" ),
	'Uncategorizedtemplates'    => array( "Óflokkuð_snið" ),
	'Unusedcategories'          => array( "Ónotaðir_flokkar" ),
	'Unusedimages'              => array( "Munaðarlausar_skrár" ),
	'Wantedpages'               => array( "Eftirsóttar_síður" ),
	'Wantedcategories'          => array( "Eftirsóttir_flokkar" ),
	'Mostlinked'                => array( "Mest_ítengdu_síður" ),
	'Mostlinkedcategories'      => array( "Mest_ítengdu_flokkar" ),
	'Mostlinkedtemplates'       => array( "Mest_ítengdu_snið" ),
	'Mostcategories'            => array( "Mest_flokkaðar_greinar" ),
	'Mostimages'                => array( "Mest_ítengdu_myndir" ),
	'Mostrevisions'             => array( "Greinar_eftir_útgáfum" ),
	'Fewestrevisions'           => array( "Greinar_með_fæstar_breytingar" ),
	'Shortpages'                => array( "Stuttar_síður" ),
	'Longpages'                 => array( "Langar_síður" ),
	'Newpages'                  => array( "Nýjustu_greinar" ),
	'Ancientpages'              => array( "Elstu_síður" ),
	'Deadendpages'              => array( "Botnlangar" ),
	'Protectedpages'            => array( "Verndaðar_síður" ),
	'Allpages'                  => array( "Allar_síður" ),
	'Prefixindex'               => array( "Allar_greinar" ),
	'Ipblocklist'               => array( "Bannaðir_notendur_og_IP-tölur" ),
	'Specialpages'              => array( "Kerfissíður" ),
	'Contributions'             => array( "Framlög_notanda" ),
	'Emailuser'                 => array( "Senda_tölvupóst" ),
	'Whatlinkshere'             => array( "Síður_sem_tengjast" ),
	'Recentchangeslinked'       => array( "Nýlegar_breytingar_tengdar" ),
	'Movepage'                  => array( "Færa_síðu" ),
	'Blockme'                   => array( "Banna_mig" ),
	'Booksources'               => array( "Bókaverslanir" ),
	'Categories'                => array( "Flokkar" ),
	'Export'                    => array( "Flytja_út_síður" ),
	'Version'                   => array( "Útgáfa" ),
	'Allmessages'               => array( "Meldingar" ),
	'Log'                       => array( "Aðgerðaskrár" ),
	'Blockip'                   => array( "Banna_notanda" ),
	'Undelete'                  => array( "Endurvekja_eydda_síðu" ),
	'Import'                    => array( "Flytja_inn_síður" ),
	'Lockdb'                    => array( "Læsa_gagnagrunni" ),
	'Unlockdb'                  => array( "Opna_gagnagrunn" ),
	'Userrights'                => array( "Notandaréttindi" ),
	'MIMEsearch'                => array( "MIME-leit" ),
	'Unwatchedpages'            => array( "Óvaktaðar_síður" ),
	'Listredirects'             => array( "Tilvísanalisti" ),
	'Revisiondelete'            => array( "Eyðingarendurskoðun" ),
	'Unusedtemplates'           => array( "Ónotuð_snið" ),
	'Randomredirect'            => array( "Handahófsvalin_tilvísun" ),
	'Mypage'                    => array( "Notendasíða_mín" ),
	'Mytalk'                    => array( "Spjallasíða_mín" ),
	'Mycontributions'           => array( "Framlög_mín" ),
	'Listadmins'                => array( "Stjórnendalisti" ),
	'Popularpages'              => array( "Vinsælar_síður" ),
	'Search'                    => array( "Leit" ),
	'Resetpass'                 => array( "Endurkalla_aðgangsorðið" ),
	'Withoutinterwiki'          => array( "Síður_án_tungumálatengla" ),
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkPrefixExtension = true;
$linkTrail = '/^([áðéíóúýþæöa-z-–]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Undirstrika tengla:',
'tog-highlightbroken'         => 'Sýna brotna tengla <a href="" class="new">svona</a> (annars: svona<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Jafna málsgreinar',
'tog-hideminor'               => 'Fela minniháttar breytingar',
'tog-extendwatchlist'         => 'Útvíkka vaktlistann þannig að hann sýni allar viðkomandi breytingar',
'tog-usenewrc'                => 'Endurbætt nýjar tengingar (JavaScript)',
'tog-numberheadings'          => 'Númera fyrirsagnir sjálfkrafa',
'tog-showtoolbar'             => 'Sýna verkfærastiku fyrir breytingar (JavaScript)',
'tog-editondblclick'          => 'Breyta síðu ef tvísmellt er á tengilinn (JavaScript)',
'tog-editsection'             => 'Leyfa breytingar á hluta síðna með [breyta] tenglinum',
'tog-editsectiononrightclick' => 'Leyfa breytingar á hluta síðna með því að  hægrismella á fyrirsagnir (JavaScript)',
'tog-showtoc'                 => 'Sýna efnisyfirlit (fyrir síður með meira en 3 fyrirsagnir)',
'tog-rememberpassword'        => 'Muna lykilorðið mitt',
'tog-editwidth'               => 'Innsláttarsvæði hefur fulla breidd',
'tog-watchcreations'          => 'Bæta síðum sem ég bý til á vaktlistann minn',
'tog-watchdefault'            => 'Bæta síðum sem ég breyti á vaktlistann minn',
'tog-watchmoves'              => 'Bæta síðum sem ég færi á vaktlistann minn',
'tog-watchdeletion'           => 'Bæta síðum sem ég eyði á vaktlistann minn',
'tog-minordefault'            => 'Láta breytingar vera sjálfgefnar sem minniháttar',
'tog-previewontop'            => 'Sýna forskoðun á undan breytingarkassanum',
'tog-previewonfirst'          => 'Sýna forskoðun með fyrstu breytingu',
'tog-nocache'                 => 'Slökkva á flýtivistun síðna',
'tog-enotifwatchlistpages'    => 'Senda mér tölvupóst þegar síðu á vaktlistanum mínu er breytt',
'tog-enotifusertalkpages'     => 'Senda mér tölvupóst þegar notandaspjallinu mínu er breytt',
'tog-enotifminoredits'        => 'Senda mér tölvupóst vegna minniháttar breytinga á síðum',
'tog-enotifrevealaddr'        => 'Sýna netfang mitt í tilkynningarpóstum',
'tog-shownumberswatching'     => 'Sýna fjölda notenda sem vakta',
'tog-fancysig'                => 'Nota hráa undirskrift (án sjálfkrafa tengils)',
'tog-externaleditor'          => 'Nota utanaðkomandi ritil að staðaldri',
'tog-externaldiff'            => 'Nota utanaðkomandi breytingarsíðu að staðaldri',
'tog-showjumplinks'           => 'Leyfa „stökkva á“ aðgengitengla',
'tog-forceeditsummary'        => 'Birta áminningu ef útskýring er ekki gefin fyrir breytingu þegar síða er vistuð.',
'tog-watchlisthideown'        => 'Ekki sýna mínar breytingar á vaktlistanum',
'tog-watchlisthidebots'       => 'Ekki sýna breytingar vélmenna á vaktlistanum',
'tog-watchlisthideminor'      => 'Ekki sýna minniháttar breytingar á vaktlistanum',
'tog-ccmeonemails'            => 'Senda mér afrit af tölvupóstum sem ég sendi öðrum notendum',

'underline-always'  => 'Alltaf',
'underline-never'   => 'Aldrei',
'underline-default' => 'skv. vafrastillingu',

'skinpreview' => '(Forskoða)',

# Dates
'sunday'        => 'sunnudagur',
'monday'        => 'mánudagur',
'tuesday'       => 'þriðjudagur',
'wednesday'     => 'miðvikudagur',
'thursday'      => 'fimmtudagur',
'friday'        => 'föstudagur',
'saturday'      => 'laugardagur',
'sun'           => 'sun',
'mon'           => 'mán',
'tue'           => 'þri',
'wed'           => 'mið',
'thu'           => 'fim',
'fri'           => 'fös',
'sat'           => 'lau',
'january'       => 'janúar',
'february'      => 'febrúar',
'march'         => 'mars',
'april'         => 'apríl',
'may_long'      => 'maí',
'june'          => 'júní',
'july'          => 'júlí',
'august'        => 'ágúst',
'september'     => 'september',
'october'       => 'október',
'november'      => 'nóvember',
'december'      => 'desember',
'january-gen'   => 'janúar',
'february-gen'  => 'febrúar',
'march-gen'     => 'mars',
'april-gen'     => 'apríl',
'may-gen'       => 'maí',
'june-gen'      => 'júní',
'july-gen'      => 'júlí',
'august-gen'    => 'ágúst',
'september-gen' => 'september',
'october-gen'   => 'október',
'november-gen'  => 'nóvember',
'december-gen'  => 'desember',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maí',
'jun'           => 'jún',
'jul'           => 'júl',
'aug'           => 'ágú',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nóv',
'dec'           => 'des',

# Bits of text used by many pages
'categories'            => 'Flokkar',
'pagecategories'        => '{{PLURAL:$1|Flokkur|Flokkar}}',
'category_header'       => 'Greinar í flokknum „$1“',
'subcategories'         => 'Undirflokkar',
'category-media-header' => 'Margmiðlunarefni í flokknum „$1“',
'category-empty'        => "''Þessi flokkur inniheldur engar greinar eða margmiðlunarefni.''",

'linkprefix'   => '/^(.*?)([áÁðÐéÉíÍóÓúÚýÝþÞæÆöÖA-Za-z-–]+)$/sDu',
'mainpagetext' => "<big>'''Uppsetning á MediaWiki heppnaðist.'''</big>",

'about'          => 'Um',
'article'        => 'Innihald síðu',
'newwindow'      => '(í nýjum glugga)',
'cancel'         => 'Hætta við',
'qbfind'         => 'Finna',
'qbbrowse'       => 'Flakka',
'qbedit'         => 'Breyta',
'qbpageoptions'  => 'Þessi síða',
'qbpageinfo'     => 'Samhengi',
'qbmyoptions'    => 'Mínar síður',
'qbspecialpages' => 'Kerfissíður',
'moredotdotdot'  => 'Meira...',
'mypage'         => 'Mín síða',
'mytalk'         => 'Spjall',
'anontalk'       => 'Spjallsíða þessa vistfangs.',
'navigation'     => 'Flakk',

# Metadata in edit box
'metadata_help' => 'Lýsigögn:',

'errorpagetitle'    => 'Villa',
'returnto'          => 'Aftur á: $1.',
'tagline'           => 'Úr {{SITENAME}}',
'help'              => 'Hjálp',
'search'            => 'Leit',
'searchbutton'      => 'Leita',
'go'                => 'Áfram',
'searcharticle'     => 'Áfram',
'history'           => 'Breytingaskrá',
'history_short'     => 'Breytingaskrá',
'updatedmarker'     => 'uppfært frá síðustu heimsókn minni',
'info_short'        => 'Upplýsingar',
'printableversion'  => 'Prentvæn útgáfa',
'permalink'         => 'Varanlegur tengill',
'print'             => 'Prenta',
'edit'              => 'Breyta',
'editthispage'      => 'Breyta þessari síðu',
'delete'            => 'Eyða',
'deletethispage'    => 'Eyða þessari síðu',
'undelete_short'    => 'Endurvekja {{PLURAL:$1|eina breytingu|$1 breytingar}}',
'protect'           => 'Vernda',
'protect_change'    => 'Breyta vernd',
'protectthispage'   => 'Vernda þessa síðu',
'unprotect'         => 'Afvernda',
'unprotectthispage' => 'Afvernda þessa síðu',
'newpage'           => 'Ný síða',
'talkpage'          => 'Ræða um þessa síðu',
'talkpagelinktext'  => 'Spjall',
'specialpage'       => 'Kerfissíða',
'personaltools'     => 'Tenglar',
'postcomment'       => 'Komdu með athugasemd',
'articlepage'       => 'Sýna núverandi síðu',
'talk'              => 'Spjall',
'views'             => 'Sýn',
'toolbox'           => 'Verkfæri',
'userpage'          => 'Skoða notandasíðu',
'projectpage'       => 'Sýna verkefnissíðu',
'imagepage'         => 'Skoða myndasíðu',
'mediawikipage'     => 'Skoða skilaboðasíðu',
'templatepage'      => 'Skoða sniðasíðu',
'viewhelppage'      => 'Skoða hjálparsíðu',
'categorypage'      => 'Skoða flokkatré',
'viewtalkpage'      => 'Skoða umræðu',
'otherlanguages'    => 'Á öðrum tungumálum',
'redirectedfrom'    => '(Tilvísun frá $1)',
'redirectpagesub'   => 'Þessi síða er tilvísun',
'lastmodifiedat'    => 'Þessari síðu var síðast breytt $2, $1.', # $1 date, $2 time
'viewcount'         => 'Þessi síða hefur verið skoðuð {{plural:$1|einu sinni|$1 sinnum}}.',
'protectedpage'     => 'Vernduð síða',
'jumpto'            => 'Fara á:',
'jumptonavigation'  => 'flakk',
'jumptosearch'      => 'leita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Um {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Um',
'bugreports'        => 'Villuskýrslur',
'bugreportspage'    => '{{ns:project}}:Villutilkynningar',
'copyright'         => 'Efni síðunnar má nota samkvæmt $1',
'copyrightpagename' => 'Höfundarréttarreglum {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Höfundarréttur',
'currentevents'     => 'Potturinn',
'currentevents-url' => '{{ns:project}}:Potturinn',
'disclaimers'       => 'Fyrirvarar',
'disclaimerpage'    => '{{ns:project}}:Almennur fyrirvari',
'edithelp'          => 'Breytingarhjálp',
'edithelppage'      => 'Hjálp:Breyta',
'faq'               => 'Algengar spurningar',
'faqpage'           => '{{ns:project}}:Algengar spurningar',
'helppage'          => 'Hjálp:Efnisyfirlit',
'mainpage'          => 'Forsíða',
'policy-url'        => '{{ns:project}}:Stjórnarstefnur',
'portal'            => 'Samfélagsgátt',
'portal-url'        => '{{ns:project}}:Samfélagsgátt',
'privacy'           => 'Meðferð persónuupplýsinga',
'privacypage'       => '{{ns:project}}:Stefnumál_um_friðhelgi',
'sitesupport'       => 'Fjárframlög',
'sitesupport-url'   => '{{ns:project}}:Fjárframlög',

'badaccess'        => 'Aðgangsvilla',
'badaccess-group0' => 'Þú hefur ekki leyfi til að framkvæma þá aðgerð sem þú baðst um.',
'badaccess-group1' => 'Aðgerðin sem þú reyndir að framkvæma er takmörkuð notendum fyrir utan $1.',
'badaccess-group2' => 'Aðgerðin sem þú reyndir að framkvæma er takmörkuð einum af hópunum $1.',
'badaccess-groups' => 'Aðgerðin sem þú reyndir að framkvæma er takmörkuð einum af hópunum $1.',

'versionrequired'     => 'Þarfnast úgáfu $1 af MediaWiki',
'versionrequiredtext' => 'Útgáfa $1 af MediaWiki er nauðsynlegt til að geta skoðað þessa síðu. Sjá [[Special:Version|útgáfusíðuna]].',

'ok'                      => 'Í lagi',
'retrievedfrom'           => 'Af „$1“',
'youhavenewmessages'      => 'Þú hefur fengið $1 ($2).',
'newmessageslink'         => 'ný skilaboð',
'newmessagesdifflink'     => 'síðasta breyting',
'youhavenewmessagesmulti' => 'Þín bíða ný skilaboð á $1',
'editsection'             => 'breyta',
'editold'                 => 'breyta',
'editsectionhint'         => 'Breyti hluta: $1',
'toc'                     => 'Efnisyfirlit',
'showtoc'                 => 'sýna',
'hidetoc'                 => 'fela',
'thisisdeleted'           => 'Endurvekja eða skoða $1?',
'viewdeleted'             => 'Skoða $1?',
'restorelink'             => '{{PLURAL:$1|eina eydda breytingu|$1 eyddar breytingar}}',
'feedlinks'               => 'Nippan:',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Grein',
'nstab-user'      => 'Notandi',
'nstab-media'     => 'Margmiðlunarsíða',
'nstab-special'   => 'Kerfissíða',
'nstab-project'   => 'Um',
'nstab-image'     => 'Mynd',
'nstab-mediawiki' => 'Melding',
'nstab-template'  => 'Snið',
'nstab-help'      => 'Hjálp',
'nstab-category'  => 'Flokkur',

# Main script and global functions
'nosuchaction'      => 'Aðgerð ekki til',
'nosuchactiontext'  => 'Aðgerðin sem veffangið tilgreinir þekkir er ekki þekkt af wiki',
'nosuchspecialpage' => 'Kerfissíðan er ekki til',
'nospecialpagetext' => 'Þú hefur beðið um kerfissíðu sem ekki er til. Listi yfir gildar kerfissíður er að finna á [[Special:Specialpages|kerfissíður]].',

# General errors
'error'                => 'Villa',
'databaseerror'        => 'Gagnagrunnsvilla',
'dberrortext'          => 'Spurnarvilla kom upp í gagnagrunninum.
Þetta gæti verið vegna villu í hugbúnaðinum.
Síðasta gagnagrunnsspurnin var:
<blockquote><tt>$1</tt></blockquote>
úr aðgerðinni: „<tt>$2</tt>“.
MySQL skilar villuboðanum „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Spurnarvilla kom upp í gagnagrunninum.
Síðasta gagnagrunnsspurnin var:
"$1"
úr aðgerðinni: "$2".
MySQL skilar villuboðanum "$3: $4"',
'noconnect'            => 'Því miður! Þetta Wiki-kerfi á við tæknilega örðugleika að stríða og nær ekki sambandi við gagnavefþjóninn. <br /> $1',
'nodb'                 => 'Gat ekki valið gagnagrunn $1',
'cachederror'          => 'Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:',
'laggedslavemode'      => 'Viðvörun: Síðan inniheldur ekki nýjustu uppfærslur.',
'readonly'             => 'Gagnagrunnur læstur',
'enterlockreason'      => 'Gefðu fram ástæðu fyrir læsingunni, og einnig áætlun
un hvenær læsingunni verðu aflétt',
'readonlytext'         => 'Læst hefur verið fyrir gerð nýrra síða og breytinga í gagnagrunninum, líklega vegna viðhalds, en eftir það mun hann starfa eðlilega.

Kerfisstjórinn sem læsti honum gaf þessa skýringu: $1',
'missingarticle'       => 'Gagnagrunnurinn finnur ekki texta af síðunni sem þú leitaðir að, „$1“.

Þetta er venjulega vegna þess að þú hefur skoðað breytingu eða breytingaskrá að síðu sem hefur verið eytt. 

Ef þetta á ekki við, þá gæti verið að þú hafir fundið villu í hugbúnaðinum. Vinsamlegast tilkynntu stjórnanda þessa villu og taktu fram slóðina.',
'readonly_lag'         => 'Gagnagrunninum hefur verið læst sjálfkrafa á meðan undirvefþjónarnir reyna að hafa í við aðalvefþjóninn',
'internalerror'        => 'Kerfisvilla',
'internalerror_info'   => 'Innri villa: $1',
'filecopyerror'        => 'Gat ekki afritað skjal "$1" á "$2".',
'filerenameerror'      => 'Gat ekki endurnefnt skrána „$1“ í „$2“.',
'filedeleteerror'      => 'Gat ekki eytt skránni „$1“.',
'directorycreateerror' => 'Gat ekki búið til efnisskrána "$1".',
'filenotfound'         => 'Gat ekki fundið skrána „$1“.',
'fileexistserror'      => 'Ekki var hægt að skrifa í "$1" skjalið: það er nú þegar til',
'unexpected'           => 'Óleyft gildi: "$1"="$2".',
'formerror'            => 'Villa: gat ekki sent eyðublað',
'badarticleerror'      => 'Þetta er ekki hægt að framkvæma á síðunni.',
'cannotdelete'         => 'Ekki var hægt að eyða síðunni eða myndinni sem valin var. (Líklegt er að einhver annar hafi gert það.)',
'badtitle'             => 'Ógildur titill',
'badtitletext'         => 'Umbeðin síðutitill er ógildur.',
'perfdisabled'         => 'Þessi síða hefur verið gerð óvirk þar sem notkun hennar veldur of miklu álagi á gagnagrunninum.',
'perfcached'           => 'Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:',
'perfcachedts'         => 'Eftirfarandi gögn eru úr flýtiminni og voru síðast uppfærð $1.',
'wrong_wfQuery_params' => 'Röng færibreyta fyrir wfQuery()<br />
Virkni: $1<br />
Spurn: $2',
'viewsource'           => 'Skoða wikikóða',
'viewsourcefor'        => 'fyrir $1',
'protectedpagetext'    => 'Þessari síðu hefur verið læst til að koma í veg fyrir breytingar.',
'viewsourcetext'       => 'Þú getur skoðað og afritað kóðann á þessari síðu:',
'protectedinterface'   => 'Þessi síða útvegar textann sem birtist í viðmóti hugbúnaðarins, og er þess vegna læst til að koma í veg fyrir misnotkun.',
'editinginterface'     => "'''Aðvörun:''' Þú ert að breyta síðu sem hefur að geyma útlitsupplýsingar fyrir notendaumhverfi MediaWiki-hugbúnaðarins. Breytingar á þessari síðu hafa áhrif á notendaumhverfi annarra notenda.",
'sqlhidden'            => '(SQL-fyrirspurn falin)',
'namespaceprotected'   => "Þú hefur ekki leyfi til að breyta síðum í '''$1''' nafnrýminu.",
'customcssjsprotected' => 'Þú hefur ekki leyfi til að breyta þessari síð því hún inniheldur notendastillingar fyrir annann notanda.',
'ns-specialprotected'  => 'Ekki er hægt að breyta síðum í {{ns:special}} nafnrýminu.',

# Login and logout pages
'logouttitle'                => 'Útskráning notanda',
'logouttext'                 => 'Þú hefur verið skráð(ur) út.
Þú getur þó haldið áfram að nota {{SITENAME}} nafnlaust og þú getur skráð þig inn sem annar notandi. Athugaðu að sumar síður kunna að birtast líkt og þú sért ennþá innskráður, hægt er að koma í veg fyrir það með því að hreinsa biðminnið í vafranum.',
'welcomecreation'            => '== Velkomin(n) $1 ==

Aðgangurinn þinn hefur verið búinn til. Ekki gleyma að breyta [[Special:Preferences|stillingunum]] eftir þínum vilja.',
'loginpagetitle'             => 'Innskráning notanda',
'yourname'                   => 'Notandanafn',
'yourpassword'               => 'Lykilorð',
'yourpasswordagain'          => 'Lykilorð (aftur)',
'remembermypassword'         => 'Muna.',
'yourdomainname'             => 'Þitt lén:',
'loginproblem'               => '<b>Það kom upp villa í innskráningunni.</b><br />Reyndu aftur!',
'login'                      => 'Innskrá',
'loginprompt'                => 'Þú verður að leyfa HTTP-kökur til þess að geta skráð þig inn á {{SITENAME}}.',
'userlogin'                  => 'Innskrá / Búa til aðgang',
'logout'                     => 'Útskráning',
'userlogout'                 => 'Útskrá',
'notloggedin'                => 'Ekki innskráð(ur)',
'nologin'                    => 'Ekki með notandanafn? $1.',
'nologinlink'                => 'Búðu til aðgang',
'createaccount'              => 'Nýskrá',
'gotaccount'                 => 'Nú þegar með notandanafn? $1.',
'gotaccountlink'             => 'Skráðu þig inn',
'createaccountmail'          => 'með tölvupósti',
'badretype'                  => 'Lykilorðin sem þú skrifaðir eru ekki eins.',
'userexists'                 => 'Þetta notandanafn er þegar í notkun. Gjörðu svo vel að velja þér annað notandanafn.',
'youremail'                  => 'Netfang:',
'username'                   => 'Notandanafn:',
'uid'                        => 'Raðnúmer:',
'yourrealname'               => 'Fullt nafn:',
'yourlanguage'               => 'Viðmótstungumál:',
'yourvariant'                => 'Útgáfa',
'yournick'                   => 'Nafn (fyrir undirskriftir):',
'badsig'                     => 'Ógild hrá undirskrift. Athugaðu HTML-kóða.',
'badsiglength'               => 'Gælunafn of langt; það má ekki innihalda fleiri en $1 stafi.',
'email'                      => 'Tölvupóstur',
'prefs-help-realname'        => 'Alvöru nafn (valfrjálst). Ef þú kýst að gefa upp raunverulegt nafn þitt mun það gefa þér heiður af verkum þínum.',
'loginerror'                 => 'Innskráningarvilla',
'prefs-help-email'           => 'Tölvupóstur (valfrjálst). Gerir öðrum kleift að hafa samband við þig á notandasíðunni þinni eða spjallinu þínu án þess að þú þurfir að gefa upp persónlegar upplýsingar.',
'prefs-help-email-required'  => 'Þörf er á netfangi.',
'nocookieslogin'             => "{{SITENAME}} notar kökur (enska: ''cookies'') til innskráningar. Vafrinn þinn er ekki að taka á móti þeim sem gerir það ókleyft að innskrá þig. Vinsamlegast kveiktu móttöku kakna í vafranum þínum til að geta skráð þig inn.",
'noname'                     => 'Ógilt notandanafn.',
'loginsuccesstitle'          => 'Innskráning tókst',
'loginsuccess'               => 'Þú ert nú innskráð(ur) á {{SITENAME}} sem „[[Notandi:$1|$1]]“.',
'nosuchuser'                 => 'Enginn notandi er til undir nafninu „$1“.',
'nosuchusershort'            => 'Það er enginn notandi með nafnið „$1“. Athugaðu hvort nafnið er ritað rétt.',
'nouserspecified'            => 'Þú verður að taka fram notandanafn.',
'wrongpassword'              => 'Uppgefið lykilorð er rangt. Vinsamlegast reyndu aftur.',
'wrongpasswordempty'         => 'Lykilorðsreiturinn var auður. Vinsamlegast reyndu aftur.',
'passwordtooshort'           => 'Lykilorðið þitt er of stutt eða ógilt. Það verður að hafa að minnsta kosti $1 tákn og má ekki vera notandanafn þitt.',
'mailmypassword'             => 'Senda nýtt lykilorð með tölvupósti',
'passwordremindertitle'      => 'Aðgangsorðaminnir frá {{SITENAME}}',
'passwordremindertext'       => 'Einhver (líklegast þú, á vistfanginu $1)
bað um að fá sent nýtt lykilorð fyrir {{SITENAME}} ($4).
Lykilorðið fyrir notandan „$2“ er núna „$3“.
Þú ættir að skrá þig inn núna og breyta lykilorðinu.

Ef einhver annar hefur sent inn þessa beðni eða þér tókst að muna lykilorðið og
þú hefur ekki áhuga á að fá nýtt þá getur þú hundsað þessi skilaboð og haldið áfram
að nota gamla lykilorðið.',
'noemail'                    => 'Það er ekkert póstfang skráð fyrir notanda "$1".',
'passwordsent'               => 'Nýtt lykilorð var sent á netfangið sem er skráð á „$1“. 
Vinsamlegast skráðu þig inn á ný þegar þú hefur móttekið það.',
'blocked-mailpassword'       => 'Þér er ekki heimilt að gera breytingar frá þessu netfangi og  því getur þú ekki fengið nýtt lykilorð í pósti.  Þetta er gert til þess að koma í veg fyrir skemmdarverk.',
'eauthentsent'               => 'Staðfestingarpóstur hefur verið sendur á uppgefið netfang. Þú verður að fylgja leiðbeiningunum í póstinum til þess að virkja netfangið og staðfesta að það sé örugglega þitt.',
'mailerror'                  => 'Upp kom villa við sendingu tölvupósts: $1',
'acct_creation_throttle_hit' => 'Þú hefur nú þegar búið til $1 notendur. Þú getur ekki búið til fleiri.',
'emailauthenticated'         => 'Netfang þitt var staðfest þann $1.',
'emailnotauthenticated'      => 'Veffang þitt hefur ekki enn verið sannreynt. Enginn póstur verður sendur af neinum af eftirfarandi eiginleikum.',
'noemailprefs'               => 'Gefðu upp netfang svo þessar aðgerðir virki.',
'emailconfirmlink'           => 'Staðfesta netfang þitt',
'invalidemailaddress'        => 'Ekki er hægt að taka við netfangi þínu þar sem að villa er í því.
Gjörðu svo vel að setja inn rétt netfang eða tæmdu reitinn.',
'accountcreated'             => 'Aðgangur búinn til',
'accountcreatedtext'         => 'Notandaaðgangur fyrir $1 er tilbúinn.',
'loginlanguagelabel'         => 'Tungumál: $1',

# Password reset dialog
'resetpass'           => 'Endurkalla aðgangsorðið',
'resetpass_text'      => '<!-- Setja texta hér -->',
'resetpass_header'    => 'Endurstilla lykilorð',
'resetpass_submit'    => 'Skrifðu aðgangsorðið og skráðu þig inn',
'resetpass_success'   => 'Aðgangsorðinu þínu hefur verið breytt! Skráir þig inn...',
'resetpass_forbidden' => 'Ekki er hægt að breyta aðgangsorði á þessum wiki',

# Edit page toolbar
'bold_sample'     => 'Feitletraður texti',
'bold_tip'        => 'Feitletraður texti',
'italic_sample'   => 'Skáletraður texti',
'italic_tip'      => 'Skáletraður texti',
'link_sample'     => 'Titill tengils',
'link_tip'        => 'Innri tengill',
'extlink_sample'  => 'http://www.sýnishorn.is titill tengils',
'extlink_tip'     => 'Ytri tengill (muna að setja http:// á undan)',
'headline_sample' => 'Fyrirsagnartexti',
'headline_tip'    => 'Annars stigs fyrirsögn',
'math_sample'     => 'Formúlan setjist hér',
'math_tip'        => 'LaTeX Stærðfræðiformúla',
'nowiki_sample'   => 'Innsetjið ósniðinn texta hér',
'nowiki_tip'      => 'Hunsa wikisnið',
'image_sample'    => 'Sýnishorn.jpg',
'image_tip'       => 'Setja inn mynd',
'media_sample'    => 'Sýnishorn.ogg',
'media_tip'       => 'Tengill í margmiðlunarskrá',
'sig_tip'         => 'Undirskrift þín auk tímasetningar',
'hr_tip'          => 'Lárétt lína (notist sparlega)',

# Edit pages
'summary'                  => 'Breytingarágrip',
'subject'                  => 'Fyrirsögn',
'minoredit'                => 'Minniháttar breyting',
'watchthis'                => 'Vakta',
'savearticle'              => 'Vista',
'preview'                  => 'Forskoða',
'showpreview'              => 'Forskoða',
'showlivepreview'          => 'Sýning',
'showdiff'                 => 'Sýna breytingar',
'anoneditwarning'          => "'''Viðvörun:''' Þú ert ekki skráður inn. Vistfang þitt mun verða skráð niður í breytingaskrá síðunnar.",
'missingcommenttext'       => 'Gerðu svo vel og skrifaðu athugasemd fyrir neðan.',
'summary-preview'          => 'Forskoða breytingarágrip',
'subject-preview'          => 'Forskoðun viðfangsefnis/fyrirsagnar',
'blockedtitle'             => 'Notandi er bannaður',
'blockedtext'              => "<big>'''Notandanafn þitt eða vistfang hefur verið bannað.'''</big>

Bannið var sett af $1. Ástæðan sem gefin var er eftirfarandi: ''$2''.

* Bannið hófst: $8
* Banninu líkur: $6
* Sá sem átti að vera bannaður: $7

Þú getur reynt að hafa samband við $1 eða einhvern annan [[{{MediaWiki:Grouppage-sysop}}|stjórnanda]] til að ræða bannið.
Athugaðu að „Senda þessum notanda tölvupóst“ möguleikinn er óvirkur nema þú hafir skráð gilt netfang í [[Special:Preferences|notandastillingum þínum]] og að þú hafir ekki verið bannaður frá því að nota það.

Vistfangið þitt er $3 og bönnunarnúmerið er #$5. Vinsamlegast taktu það fram í fyrirspurnum þínum.",
'autoblockedtext'          => "Vistfang þitt hefur verið sjálvirkt bannað því það var notuð af öðrum notanda, sem var bannaður af $1.
Ástæðan sem gefin var er eftirfarandi:

:''$2''

* Bann byrjaði: $8
* Bannið endist til: $6

Þú getur haft samband við $1 eða einn af hinum
[[{{MediaWiki:Grouppage-sysop}}|stjórendunum]] til að ræða bannið.

Athugið að „Senda þessum notanda tölvupóst“ möguleikinn er óvirkur nema þú hafir skráð gilt netfang í [[Special:Preferences|stillingunum]] þínum.

Vistfangið þitt er $5. Vinsamlegast taktu það fram í fyrirspurnum þínum.",
'blockededitsource'        => "Texti '''þinna breytinga''' á '''$1''' eru sýndar að neðan:",
'whitelistedittitle'       => 'Þú verður að skrá þig inn til að geta breytt síðum.',
'whitelistedittext'        => 'Þú þarft að $1 til að breyta síðum.',
'whitelistreadtitle'       => 'Notandi verður að skrá sig inn til að geta lesið.',
'whitelistreadtext'        => 'Þú verður að [[Special:Userlogin|skrá þig inn]] til að lesa síður.',
'whitelistacctitle'        => 'Þér er óheimilt að skrá þig sem notanda.',
'whitelistacctext'         => 'Til að geta búið til aðganga í þessu Wiki, verður þú að [[Special:Userlogin|innskrá]] og hafa viðkomandi réttindi.',
'confirmedittitle'         => 'Netfang þarf að staðfesta til að breyta',
'confirmedittext'          => 'Þú verður að staðfesta netfangið þitt áður en þú getur breytt síðum. Vinsamlegast stilltu og staðfestu netfangið þitt í gegnum [[Special:Preferences|stillingarnar]].',
'nosuchsectiontitle'       => 'Hluti ekki til',
'nosuchsectiontext'        => 'Það hefur komið upp villa. Þú hefur reynt að breyta hluta $1 á síðunni, en hann er ekki til. Vinsamlegast farðu til baka og reyndu að breyta síðunni í heild.',
'loginreqtitle'            => 'Innskráningar krafist',
'loginreqlink'             => 'innskrá',
'loginreqpagetext'         => 'Þú þarft að $1 til að geta séð aðrar síður.',
'accmailtitle'             => 'Lykilorð sent.',
'accmailtext'              => 'Lykilorðið fyrir „$1“ hefur verið sent á $2.',
'newarticle'               => '(Ný)',
'newarticletext'           => "Þú hefur fylgt tengli á síðu sem er ekki til enn þá.
Þú getur búið til síðu með þessu nafni með því að skrifa í formið að neðan 
(meiri upplýsingar á [[{{MediaWiki:Helppage}}|hjálp]]).
Ef þú hefur óvart vilst hingtað, veldu einfaldlega '''til baka''' á vafranum þínum.",
'anontalkpagetext'         => "----Þetta er spjallsíða fyrir óskráðan notanda sem hefur ekki búið til aðgang enn þá eða notar hann ekki, slíkir notendur þekkjast á vistfangi sínu. Það getur gerst að margir notendur deili sama vistfangi þannig að athugasemdum sem beint er til eins notanda geta birst á spjallsíðu annars. [[Special:Userlogin|Skráðu þig sem notanda]] til að koma í veg fyrir svona misskilning.''",
'noarticletext'            => 'Hér er engin texti enn sem komið er, þú getur [[Special:Search/{{PAGENAME}}|leitað í öðrum síðum]] eða [{{fullurl:{{FULLPAGENAMEE}}|action=edit}} breytt henni sjálfur].',
'clearyourcache'           => "'''Ath:''' Eftir að þú hefur vistað breytingar getur þurft að hreinsa flýtiskrár vafrans til að sjá þær. Í '''Mozilla / Konqueror''' er það gert með ''ctrl-shift-R'', '''IE / Opera:''' ''ctrl-F5'', '''Safari:''' ''slaufa-val-E'' (''command-option-E'' / ''command-alt-E'').",
'usercssjsyoucanpreview'   => '<strong>Ath:</strong> Hægt er að nota „Forskoða“ hnappinn til að prófa CSS og JavaScript kóða áður en hann er vistaður.',
'usercsspreview'           => "'''Hafðu í huga að þú ert aðeins að forskoða CSS-kóðann þinn, hann hefur ekki enn verið vistaður!'''",
'updated'                  => '(Uppfært)',
'note'                     => '<strong>Athugið:</strong>',
'previewnote'              => '<strong>Það sem sést hér er aðeins forskoðun og hefur ekki enn verið vistað!</strong>',
'session_fail_preview'     => '<strong>Því miður! Gat ekki unnið úr breytingum þínum vegna týndra lotugagna. 
Vinsamlegast reyndu aftur síðar. Ef það virkar ekki heldur skaltu reyna að skrá þig út og inn á ný.</strong>',
'editing'                  => 'Breyti $1',
'editinguser'              => 'Breyti $1',
'editingsection'           => 'Breyti $1 (hluta)',
'editingcomment'           => 'Breyti $1 (bæti við athugasemd)',
'editconflict'             => 'Breytingaárekstur: $1',
'explainconflict'          => 'Síðunni hefur verið breytt síðan þú byrjaðir að gera breytingar á henni, textinn í efri reitnum inniheldur núverandi útgáfu úr gagnagrunni og sá neðri inniheldur þína útgáfu, þú þarft hér að færa breytingar sem þú vilt halda úr neðri reitnum í þann efri og vista síðuna. <strong>Aðeins</strong> texti úr efri reitnum mun vera vistaður þegar þú vistar.',
'yourtext'                 => 'Þinn texti',
'storedversion'            => 'Geymd útgáfa',
'editingold'               => '<strong>ATH: Þú ert að breyta gamalli útgáfu þessarar síðu og munu allar breytingar sem gerðar hafa verið á henni frá þeirri útgáfu vera fjarlægðar ef þú vistar.</strong>',
'yourdiff'                 => 'Mismunur',
'copyrightwarning'         => 'Vinsamlegast athugaðu að öll framlög á {{SITENAME}} eru álitin leyfisbundin samkvæmt $2 (sjá $1 fyrir frekari upplýsingar).  Ef þú vilt ekki að skrif þín falli undir þetta leyfi og öllum verði frjálst að breyta og endurútgefa efnið samkvæmt því skaltu ekki leggja þau fram hér.<br />
Þú berð ábyrgð á framlögum þínum, þau verða að vera þín skrif eða afrit texta í almannaeigu eða sambærilegs frjáls texta.
<strong>AFRITIÐ EKKI HÖFUNDARRÉTTARVARIN VERK Á ÞESSA SÍÐU ÁN LEYFIS</strong>',
'copyrightwarning2'        => 'Vinsamlegast athugið að aðrir notendur geta breytt eða fjarlægt öll framlög til {{SITENAME}}.
Ef þú vilt ekki að textanum verði breytt skaltu ekki senda hann inn hér.<br />
Þú lofar okkur einnig að þú hafir skrifað þetta sjálfur, að efnið sé í almannaeigu eða að það heyri undir frjálst leyfi. (sjá $1).
<strong>EKKI SENDA INN HÖFUNDARRÉTTARVARIÐ EFNI ÁN LEYFIS RÉTTHAFA!</strong>',
'longpagewarning'          => '<strong>VIÐVÖRUN: Þessi síða er $1 kílóbæta löng; sumir
vafrar gætu átt erfitt með að gera breytingar á síðum sem nálgast eða eru lengri en 32kb.
Vinsamlegast íhugaðu að skipta síðunni niður í smærri einingar.</strong>',
'longpageerror'            => '<strong>VILLA: Textinn sem þú sendir inn er $1 kílóbæti að lengd, en hámarkið er $2 kílóbæti. Ekki er hægt að vista textann.</strong>',
'readonlywarning'          => '<strong>VIÐVÖRUN: Gagnagrunninum hefur verið læst til að unnt sé að framkvæma viðhaldsaðgerðir, svo að þú getur ekki vistað breytingar þínar núna. Þú gætir viljað afrita breyttan texta síðunnar yfir í textaskjal og geyma hann þar til síðar.</strong>',
'protectedpagewarning'     => '<!-- -->',
'semiprotectedpagewarning' => "'''Athugið''': Þessari síðu hefur verið læst þannig að aðeins innskráðir notendur geti breytt henni.",
'templatesused'            => 'Snið notuð á síðunni:',
'templatesusedpreview'     => 'Snið notuð á forskoðuninni:',
'templatesusedsection'     => 'Snið notuð á hlutanum:',
'template-protected'       => '(vernduð)',
'template-semiprotected'   => '(hálfsverndað)',
'nocreatetitle'            => 'Síðugerð takmörkuð',
'permissionserrors'        => 'Leyfisvillur',
'recreate-deleted-warn'    => "'''Viðvörun: Þú ert að búa til síðu sem að hefur áður verið eytt.'''

Athuga skal hvort viðunandi sé að gera þessa síðu.
Eyðingarskrá fyrir þessa síðu er útveguð hér til þæginda:",

# "Undo" feature
'undo-success' => 'Breytingin hefur verið tekin tilbaka. Vinsamlegast staðfestu og vistaðu svo.',
'undo-failure' => 'Breytinguna var ekki hægt að taka tilbaka vegna breytinga í millitíðinni.',
'undo-summary' => 'Tek aftur breytingu $1 frá [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|Spjall]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ekki hægt að búa til aðgang',
'cantcreateaccount-text' => "Aðgangsgerð fyrir þetta vistfang (<b>$1</b>) hefur verið bannað af [[User:$3|$3]].

Ástæðan sem $3 gaf fyrir því er ''$2''",

# History pages
'revhistory'          => 'Útgáfusaga',
'viewpagelogs'        => 'Sýna aðgerðir varðandi þessa síðu',
'nohistory'           => 'Þessi síða hefur enga breytingaskrá.',
'revnotfound'         => 'Breyting ekki fundin',
'loadhist'            => 'Hleð breytingaskránni',
'currentrev'          => 'Núverandi útgáfa',
'revisionasof'        => 'Útgáfa síðunnar $1',
'previousrevision'    => '←Fyrri útgáfa',
'nextrevision'        => 'Næsta útgáfa→',
'currentrevisionlink' => 'núverandi útgáfa',
'cur'                 => 'nú',
'next'                => 'næst',
'last'                => 'breyting',
'orig'                => 'uppru',
'page_first'          => 'fyrsta',
'page_last'           => 'síðasta',
'histlegend'          => 'Skýringar: (nú) = bera saman við núverandi útgáfu, 
(breyting) = bera saman við útgáfuna á undan, M = minniháttar breyting.',
'deletedrev'          => '[eytt]',
'histfirst'           => 'elstu',
'histlast'            => 'yngstu',
'historysize'         => '({{PLURAL:$1|1 bæti|$1 bæti}})',
'historyempty'        => '(tóm)',

# Revision feed
'history-feed-title'          => 'Breytingaskrá',
'history-feed-item-nocomment' => '$1 á $2', # user at time
'history-feed-empty'          => 'Síðan sem þú leitaðir að er ekki til.
Möglegt er að henni hafi verið eytt út af þessari wiki síðu, eða endurnefnd.
Prófaðu [[Special:Search|að leita á þessari wiki síðu]] að svipuðum síðum.',

# Revision deletion
'rev-deleted-comment'    => '(athugasemd fjarlægð)',
'rev-deleted-user'       => '(notandanafn fjarlægt)',
'rev-deleted-event'      => '(breyting fjarlægð)',
'rev-delundel'           => 'sýna/fela',
'logdelete-selected'     => "{{PLURAL:$2|Valin aðgerð|Valdar aðgerðir}} fyrir '''$1:'''",
'revdelete-legend'       => 'Skráðu hamlanir:',
'revdelete-hide-text'    => 'Fela breytingatexta',
'revdelete-hide-comment' => 'Fela breytingaathugasemdir',
'revdelete-hide-user'    => 'Fela notandanafn/vistfang',
'revdelete-hide-image'   => 'Fela efni skráar',
'revdelete-log'          => 'Athugasemd atburðaskráar:',

# Diffs
'history-title'             => 'Breytingaskrá fyrir "$1"',
'difference'                => '(Munur milli útgáfa)',
'lineno'                    => 'Lína $1:',
'editcurrent'               => 'Breyta núverandi útgáfu þessarar síðu',
'selectnewerversionfordiff' => 'Velja nýrri útgáfu til samanburðar',
'selectolderversionfordiff' => 'Velja eldri útgáfu til samanburðar',
'compareselectedversions'   => 'Bera saman valdar útgáfur',
'editundo'                  => 'Taka aftur þessa breytingu',

# Search results
'searchresults'         => 'Leitarniðurstöður',
'searchresulttext'      => 'Fyrir frekari upplýsingar um leit á {{SITENAME}} farið á [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Þú leitaðir að '''[[:$1]]'''",
'searchsubtitleinvalid' => "Þú leitaðir að '''$1'''",
'noexactmatch'          => "'''Engin síða ber nafnið „$1“.''' Þú getur [[:$1|búið hana til]].",
'titlematches'          => 'Titlar greina sem pössuðu við fyrirspurnina',
'notitlematches'        => 'Engir greinartitlar pössuðu við fyrirspurnina',
'textmatches'           => 'Leitarorð fannst/fundust í innihaldi eftirfarandi greina',
'notextmatches'         => 'Leitarorð fannst/fundust ekki í innihaldi greina',
'prevn'                 => 'síðustu $1',
'nextn'                 => 'næstu $1',
'viewprevnext'          => 'Skoða ($1) ($2) ($3).',
'showingresults'        => "Sýni {{PLURAL:$1|'''1''' niðurstöðu|'''$1''' niðurstöður}} frá og með #'''$2'''.",
'showingresultsnum'     => "Sýni {{PLURAL:$3|'''$3''' niðurstöðu|'''$3''' niðurstöður}} frá og með #<b>$2</b>.",
'powersearch'           => 'Leita',
'powersearchtext'       => 'Leita í eftirfarandi nafnrýmum :<br />$1<br />$2 Sýna tilvísarnir<br />Leita að $3 $9',

# Preferences page
'preferences'             => 'Stillingar',
'mypreferences'           => 'Stillingar',
'prefs-edits'             => 'Fjöldi breytinga:',
'prefsnologin'            => 'Ekki innskráður',
'prefsnologintext'        => 'Þú þarft að vera [[Special:Userlogin|innskráð(ur)]] til að breyta notendastillingum.',
'prefsreset'              => 'Stillingum hefur verið breytt yfir í þær stillingar sem eru í minni.',
'qbsettings'              => 'Valblað',
'qbsettings-none'         => 'Sleppa',
'qbsettings-fixedleft'    => 'Fast vinstra megin',
'qbsettings-fixedright'   => 'Fast hægra megin',
'qbsettings-floatingleft' => 'Fljótandi til vinstri',
'changepassword'          => 'Breyta lykilorði',
'skin'                    => 'Þema',
'math'                    => 'Stærðfræðiformúlur',
'dateformat'              => 'Tímasnið',
'datedefault'             => 'Sjálfgefið',
'datetime'                => 'Tímasnið og tímabelti',
'math_failure'            => 'Þáttun mistókst',
'math_unknown_error'      => 'óþekkt villa',
'math_unknown_function'   => 'óþekkt virkni',
'math_lexing_error'       => 'lestrarvilla',
'math_syntax_error'       => 'málfræðivilla',
'prefs-personal'          => 'Notendaupplýsingar',
'prefs-rc'                => 'Nýlegar breytingar',
'prefs-watchlist'         => 'Vaktalistinn',
'prefs-watchlist-days'    => 'Fjöldi daga sem vaktlistinn nær yfir:',
'prefs-watchlist-edits'   => 'Fjöldi breytinga sem vaktalistinn nær yfir:',
'prefs-misc'              => 'Aðrar stillingar',
'saveprefs'               => 'Vista stillingar',
'resetprefs'              => 'Endurstilla valmöguleika',
'oldpassword'             => 'Gamla lykilorðið',
'newpassword'             => 'Nýja lykilorðið',
'retypenew'               => 'Endurtaktu nýja lykilorðið',
'textboxsize'             => 'Breytingarflipinn',
'rows'                    => 'Raðir',
'columns'                 => 'Dálkar',
'searchresultshead'       => 'Leit',
'resultsperpage'          => 'Niðurstöður á síðu',
'contextlines'            => 'Línur á hverja niðurstöðu',
'contextchars'            => 'Stafir í samhengi á hverja línu',
'recentchangesdays'       => 'Hve marga daga á að sýna í nýlegum breytingum:',
'recentchangescount'      => 'Fjöldi síðna á „nýlegum breytingum“',
'savedprefs'              => 'Stillingarnar þínar hafa verið vistaðar.',
'timezonelegend'          => 'Tímabelti',
'timezonetext'            => 'Hliðrun staðartíma frá UTC+0.',
'localtime'               => 'Staðartími',
'timezoneoffset'          => 'Hliðrun',
'servertime'              => 'Tími netþjóns',
'guesstimezone'           => 'Fylla inn',
'allowemail'              => 'Virkja tölvupóst frá öðrum notendum',
'defaultns'               => 'Leita í þessum nafnrýmum:',
'default'                 => 'sjálfgefið',
'files'                   => 'Skrár',

# User rights
'userrights-lookup-user'      => 'Yfirlit notandaflokka',
'userrights-user-editname'    => 'Skráðu notandanafn:',
'editusergroup'               => 'Breyta notendahópum',
'userrights-editusergroup'    => 'Breyta notandaflokkum',
'saveusergroups'              => 'Vista notandaflokka',
'userrights-groupsmember'     => 'Meðlimur:',
'userrights-groupsavailable'  => 'Tiltækir flokkar:',
'userrights-groupshelp'       => 'Veldu þá flokka sem að þú vilt fjarlægja eða bæta notandanum við.
Óvaldir flokkar verða ekki vistaðir. Þú getur afvalið flokk með CTRL + hægri smella',
'userrights-reason'           => 'Ástæða fyrir breytingunni:',
'userrights-available-none'   => 'Þú getur ekki breytt notendaréttindum.',
'userrights-available-add'    => 'Þú getur bætt notendum við $1.',
'userrights-available-remove' => 'Þú getur fjarlægt notendur af $1.',

# Groups
'group'               => 'Flokkur:',
'group-autoconfirmed' => 'Sjálfkrafa staðfesting notenda',
'group-bot'           => 'Vélmenni',
'group-sysop'         => 'Stjórnendur',
'group-bureaucrat'    => 'Möppudýr',
'group-all'           => '(allir)',

'group-autoconfirmed-member' => 'Sjálfkrafa staðfesting notanda',
'group-bot-member'           => 'Vélmenni',
'group-sysop-member'         => 'Stjórnandi',
'group-bureaucrat-member'    => 'Möppudýr',

'grouppage-autoconfirmed' => '{{ns:project}}:Sjálfkrafa staðfesting notenda',
'grouppage-bot'           => '{{ns:project}}:Vélmenni',
'grouppage-sysop'         => '{{ns:project}}:Stjórnendur',
'grouppage-bureaucrat'    => '{{ns:project}}:Möppudýr',

# User rights log
'rightslog'      => 'Réttindaskrá notenda',
'rightslogtext'  => 'Þetta er skrá yfir breytingar á réttindum notenda.',
'rightslogentry' => 'breytti réttindum $1 frá $2 í $3',
'rightsnone'     => '(engin)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|breyting|breytingar}}',
'recentchanges'                     => 'Nýlegar breytingar',
'recentchangestext'                 => 'Hér geturðu fylgst með nýjustu breytingunum.',
'recentchanges-feed-description'    => 'Hér er hægt að fylgjast með nýlegum breytingum á {{SITENAME}}.',
'rcnote'                            => "Að neðan {{PLURAL:$1|er síðasta '''1''' breyting|eru síðustu '''$1''' breytingar}} {{PLURAL:$2|síðastliðinn dag|síðastliðna '''$2''' daga}}, frá $3.",
'rcnotefrom'                        => 'Að neðan eru breytingar síðan <b>$2</b> (allt að <b>$1</b> sýndar).',
'rclistfrom'                        => 'Sýna breytingar frá og með $1',
'rcshowhideminor'                   => '$1 minniháttar breytingar',
'rcshowhidebots'                    => '$1 vélmenni',
'rcshowhideliu'                     => '$1 innskráða notendur',
'rcshowhideanons'                   => '$1 óinnskráða notendur',
'rcshowhidepatr'                    => '$1 vaktaðar breytingar',
'rcshowhidemine'                    => '$1 mínar breytingar',
'rclinks'                           => 'Sýna síðustu $1 breytingar síðustu $2 daga<br />$3',
'diff'                              => 'breyting',
'hist'                              => 'breytingaskrá',
'hide'                              => 'Fela',
'show'                              => 'Sýna',
'boteditletter'                     => 'v',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|notandi skoðandi|$1 notendur skoðandi}}]',
'rc_categories'                     => 'Takmark á flokkum (aðskilja með "|")',
'rc_categories_any'                 => 'Alla',
'newsectionsummary'                 => 'Nýr hluti: /* $1 */',

# Recent changes linked
'recentchangeslinked'       => 'Skyldar breytingar',
'recentchangeslinked-title' => 'Breytingar tengdar $1',

# Upload
'upload'            => 'Hlaða inn skrá',
'uploadbtn'         => 'Hlaða inn skrá',
'reupload'          => 'Hlaða aftur inn',
'reuploaddesc'      => 'Aftur á innhlaðningarformið.',
'uploadnologin'     => 'Óinnskráð(ur)',
'uploadnologintext' => 'Þú verður að vera [[Special:Userlogin|skráð(ur) inn]]
til að hlaða inn skrám.',
'uploaderror'       => 'Villa í innhlaðningu',
'uploadtext'        => "Fyllið út í eyðurnar hér að neðan til að hala upp skrám, til að skoða eða leita í skrám sem þegar eru til skoðið [[{{ns:special}}:Imagelist|skráalistan]].  Uppflutningar og eyðingar eru skráðar í [[{{ns:special}}:Log/upload|innhlaðingarskrá]].

Til að bæta skrá inn á síðu, notið eina af eftirfarandi aðferðum
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Skráarheiti.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Skráarheiti.png|alt text]]</nowiki>''' eða
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Skráarheiti.ogg]]</nowiki>''' fyrir beina tengla á skránna.",
'uploadlog'         => 'innhlaðningarskrá',
'uploadlogpage'     => 'Innhlaðningarskrá',
'uploadlogpagetext' => 'Þetta er listi yfir skrár sem nýlega hefur verið hlaðið inn.',
'filename'          => 'Skráarnafn',
'filedesc'          => 'Lýsing',
'fileuploadsummary' => 'Ágrip:',
'ignorewarning'     => 'Hunsa viðvaranir og vista þessa skrá.',
'ignorewarnings'    => 'Hunsa allar viðvaranir',
'badfilename'       => 'Skáarnafninu hefur verið breytt í „$1“.',
'filetype-missing'  => 'Skráin hefur engan viðauka (dæmi ".jpg").',
'large-file'        => 'Það er mælt með að skrár séu ekki stærri en $1; þessi skrá er $2.',
'fileexists'        => 'Skrá með þessu nafni er þegar til, skoðaðu <strong><tt>$1</tt></strong> ef þú ert óviss um hvort þú viljir breyta henni, ekki verður skrifað yfir gömlu skránna hlaðiru inn nýrri með sama nafni heldur verður núverandi útgáfa geymd í útgáfusögu.',
'fileexists-thumb'  => "<center>'''Núverandi mynd'''</center>",
'successfulupload'  => 'Innhlaðning tókst',
'uploadwarning'     => 'Aðvörun',
'savefile'          => 'Vista',
'uploadedimage'     => 'hlóð inn „[[$1]]“',
'overwroteimage'    => 'hlóð inn nýrri útgáfu af "[[$1]]"',
'uploadscripted'    => 'Þetta skjal inniheldur (X)HTML eða forskriftu sem gæti valdið villum í vöfrum.',
'uploadcorrupt'     => 'Skráin er skemmd eða hefur ranga skráarendingu. Vinsamlegast athugaðu skrána og reyndu svo aftur.',
'uploadvirus'       => 'Skráin inniheldur veiru! Nánari upplýsingar: $1',
'sourcefilename'    => 'Upprunalegt skráarnafn',
'destfilename'      => 'Skráarnafn eftir innhleðslu',
'watchthisupload'   => 'Vakta þessa síðu',
'filewasdeleted'    => 'Skrá af sama nafni hefur áður verið hlaðið inn og síðan eytt. Þú ættir að athuga $1 áður en þú hleður skránni inn.',

'upload-proto-error' => 'Vitlaus samskiptaregla',
'upload-file-error'  => 'Innri villa',
'upload-misc-error'  => 'Óþekkt innhleðsluvilla',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'Gat ekki náð í slóðina',
'upload-curl-error28' => 'Innhleðslutími útrunninn',

'license'            => 'Leyfisupplýsingar',
'nolicense'          => 'Ekkert valið',
'upload_source_file' => '(skrá á tölvunni þinni)',

# Image list
'imagelist'                 => 'Skráalisti',
'imagelisttext'             => 'Hér fyrir neðan er {{PLURAL:$1|einni skrá|$1 skrám}} raðað $2.',
'getimagelist'              => 'sæki skráarlista',
'ilsubmit'                  => 'Leita',
'showlast'                  => 'Sýna síðustu $1 skrár raðaðar $2.',
'byname'                    => 'eftir nafni',
'bydate'                    => 'eftir dagsetningu',
'bysize'                    => 'eftir stærð',
'imgdelete'                 => 'eyða',
'imgdesc'                   => 'lýsing',
'imgfile'                   => 'skrá',
'filehist'                  => 'Breytingaskrá skjals',
'filehist-deleteall'        => 'eyða öllu',
'filehist-deleteone'        => 'eyða þessu',
'filehist-current'          => 'núverandi',
'filehist-datetime'         => 'Dagsetning/Tími',
'filehist-user'             => 'Notandi',
'filehist-dimensions'       => 'Víddir',
'filehist-filesize'         => 'Stærð skráar',
'filehist-comment'          => 'Athugasemd',
'imagelinks'                => 'Myndatenglar',
'linkstoimage'              => 'Eftirfarandi síður tengjast í mynd þessa:',
'nolinkstoimage'            => 'Engar síður tengja í þessa skrá.',
'sharedupload'              => 'Þessari skrá er deilt meðal annarra verkefna og er því notanleg þar.',
'noimage'                   => 'Engin skrá með þessu nafni er til. Þú getur $1.',
'noimage-linktext'          => 'hlaðið henni inn',
'uploadnewversion-linktext' => 'Hlaða inn nýrri útgáfu af þessari skrá',
'imagelist_date'            => 'Dagsetning',
'imagelist_name'            => 'Nafn',
'imagelist_user'            => 'Notandi',
'imagelist_size'            => 'Stærð (bæti)',
'imagelist_description'     => 'Lýsing',

# File reversion
'filerevert-comment' => 'Athugasemdir:',

# File deletion
'filedelete'         => 'Eyði „$1“',
'filedelete-legend'  => 'Eyða skrá',
'filedelete-intro'   => "Þú ert að eyða '''[[Media:$1|$1]]'''.",
'filedelete-comment' => 'Ástæða:',
'filedelete-submit'  => 'Eyða',

# MIME search
'mimesearch' => 'MIME leit',
'mimetype'   => 'MIME tegund:',
'download'   => 'Hlaða niður',

# Unwatched pages
'unwatchedpages' => 'Óvaktaðar síður',

# List redirects
'listredirects' => 'Tilvísanir',

# Unused templates
'unusedtemplates'     => 'Ónotuð snið',
'unusedtemplatestext' => 'Þetta er listi yfir allar síður í sniðanafnrýminu sem ekki eru notaðar í neinum öðrum síðum. Munið að gá að öðrum tenglum í sniðin áður en þeim er eytt.',
'unusedtemplateswlh'  => 'aðrir tenglar',

# Random page
'randompage'         => 'Handahófsvalin grein',
'randompage-nopages' => 'Það eru engar síður í þessu nafnrými.',

# Random redirect
'randomredirect' => 'Handahófsvalin tilvísun',

# Statistics
'statistics'             => 'Tölfræði',
'sitestats'              => 'Tölfræði fyrir {{SITENAME}}',
'userstats'              => 'Notendatölfræði',
'sitestatstext'          => "Það {{PLURAL:$1|er '''1''' síða|eru '''$1''' síður}} í gagnagrunninum.
Meðtaldar eru „spjallsíður“, síður varðandi {{SITENAME}}, smávægilegir „stubbar“, 
tilvísanir og aðrar síður sem mundu líklega ekki teljast sem efnislegar síður.
Fyrir utan þær þá {{PLURAL:$2|er '''1''' síða sem líklega getur|eru '''$2''' síður sem líklega geta}} talist
{{PLURAL:$2|efnisleg grein|efnislegar greinar}}.

'''$8''' {{PLURAL:$8|skrá|skrám}} hefur verið hlaðið inn.

Það hafa alls '''$3''' {{PLURAL:$3|síða verið skoðuð|síður verið skoðaðar}} og '''$4''' {{PLURAL:$4|síðubreyting|síðubreytingar}}
síðan {{SITENAME}} hóf göngu sína.
Sem gerir að meðaltali '''$5''' breytingar á hverja síðu og '''$6''' skoðanir á hverja breytingu.

Lengdin á [http://meta.wikimedia.org/wiki/Help:Job_queue vinnsluröðinni] er '''$7'''.",
'userstatstext'          => "Hér {{PLURAL:$1|er '''1''' skráður [[{{ns:special}}:Listusers|notandi]]|eru '''$1''' skráðir [[{{ns:special}}:Listusers|notendur]]}}, þar af 
'''$2''' (eða '''$4%''') {{PLURAL:$2|hefur|hafa}} $5 stjórnendaréttindi (sjá $3).",
'statistics-mostpopular' => 'Mest skoðuðu síður',

'disambiguations'      => 'Tenglar í aðgreiningarsíður',
'disambiguationspage'  => '{{ns:template}}:Aðgreining',
'disambiguations-text' => "Þessar síður innihalda tengla á svokallaðar „'''aðgreiningarsíður'''“. Laga ætti tenglanna og láta þá vísa á rétta síðu.<br />Farið er með síðu sem aðgreiningarsíðu ef að hún inniheldur snið sem vísað er í frá [[MediaWiki:disambiguationspage]]",

'doubleredirects' => 'Tvöfaldar tilvísanir',

'brokenredirects'        => 'Brotnar tilvísanir',
'brokenredirectstext'    => 'Eftirfarandi tilvísanir vísa á síður sem ekki eru til:',
'brokenredirects-edit'   => '(breyta)',
'brokenredirects-delete' => '(eyða)',

'withoutinterwiki'        => 'Síður án tungumálatengla',
'withoutinterwiki-header' => 'Eftirfarandi síður tengja ekki í önnur tungumál:',

'fewestrevisions' => 'Greinar með fæstar breytingar',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bæt|bæt}}',
'ncategories'             => '$1 {{PLURAL:$1|flokkur|flokkar}}',
'nlinks'                  => '$1 {{PLURAL:$1|tengill|tenglar}}',
'nmembers'                => '$1 {{PLURAL:$1|meðlimur|meðlimir}}',
'nrevisions'              => '$1 {{PLURAL:$1|breyting|breytingar}}',
'nviews'                  => '$1 {{PLURAL:$1|fletting|flettingar}}',
'specialpage-empty'       => 'Þessi síða er tóm.',
'lonelypages'             => 'Munaðarlausar síður',
'lonelypagestext'         => 'Eftirfarandi síður eru munaðarlausar á þessu Wiki-kerfi.',
'uncategorizedpages'      => 'Óflokkaðar síður',
'uncategorizedcategories' => 'Óflokkaðir flokkar',
'uncategorizedimages'     => 'Óflokkaðar skrár',
'uncategorizedtemplates'  => 'Óflokkuð snið',
'unusedcategories'        => 'Ónotaðir flokkar',
'unusedimages'            => 'Munaðarlausar skrár',
'popularpages'            => 'Vinsælar síður',
'wantedcategories'        => 'Eftirsóttir flokkar',
'wantedpages'             => 'Eftirsóttar síður',
'mostlinked'              => 'Mest ítengdu síður',
'mostlinkedcategories'    => 'Mest ítengdu flokkar',
'mostlinkedtemplates'     => 'Mest ítengdu snið',
'mostcategories'          => 'Mest flokkaðar greinar',
'mostimages'              => 'Mest ítengdu myndir',
'mostrevisions'           => 'Greinar eftir fjölda breytinga',
'allpages'                => 'Allar síður',
'prefixindex'             => 'Forskeytisleit',
'shortpages'              => 'Stuttar síður',
'longpages'               => 'Langar síður',
'deadendpages'            => 'Botnlangar',
'deadendpagestext'        => 'Eftirfarandi síður tengjast ekki við aðrar síður í þessu wiki.',
'protectedpages'          => 'Verndaðar síður',
'protectedpagestext'      => 'Eftirfarandi síður hafa verið verndaðar svo ekki sé hægt að breyta þeim eða færa þær',
'listusers'               => 'Notendalisti',
'specialpages'            => 'Kerfissíður',
'spheading'               => 'Almennar',
'rclsub'                  => '(á síðum sem tengd er í frá „$1“)',
'newpages'                => 'Nýjustu greinar',
'newpages-username'       => 'Notandanafn:',
'ancientpages'            => 'Elstu síður',
'intl'                    => 'Tungumálatenglar',
'move'                    => 'Færa',
'movethispage'            => 'Færa þessa síðu',
'unusedimagestext'        => 'Vinsamlegast athugið að aðrar vefsíður gætu tengt beint í 
skrár héðan, svo að þær gætu komið fram á þessum lista þrátt 
fyrir að vera í notkun.',
'unusedcategoriestext'    => 'Þessir flokkar eru til en engar síður eða flokkar eru í þeim.',

# Book sources
'booksources'               => 'Bókaverslanir',
'booksources-search-legend' => 'Leita að bókaverslunum',
'booksources-go'            => 'Áfram',
'booksources-text'          => 'Fyrir neðan er listi af tenglum í aðrar síður sem selja nýjar og notaðar bækur og gætu einnig haft nánari upplýsingar í sambandi við bókina sem þú varst að leita að:',

'categoriespagetext' => 'Eftirfarandi flokkar fyrirfinnast í þessu wiki-kerfi.',
'data'               => 'Gögn',
'userrights'         => 'Breyta notendaréttindum',
'groups'             => 'Notendahópar',
'alphaindexline'     => '$1 til $2',
'version'            => 'Útgáfa',

# Special:Log
'specialloguserlabel'  => 'Notandi:',
'speciallogtitlelabel' => 'Titill:',
'log'                  => 'Aðgerðaskrár',
'all-logs-page'        => 'Allar aðgerðir',
'log-search-legend'    => 'Leita að aðgerð',
'log-search-submit'    => 'Áfram',
'alllogstext'          => 'Safn af öllum aðgerðum {{SITENAME}}.
Þú getur takmarkað listann við tegund aðgerðarinnar, notendarnafn eða síðu.',
'logempty'             => 'Engin slík aðgerð fannst.',
'log-title-wildcard'   => 'Leita að titlum sem byrja á þessum texta',

# Special:Allpages
'nextpage'          => 'Næsta síða ($1)',
'prevpage'          => 'Fyrri síða ($1)',
'allpagesfrom'      => 'Sýna síður frá og með:',
'allarticles'       => 'Allar greinar',
'allinnamespace'    => 'Allar síður ($1 nafnrými)',
'allnotinnamespace' => 'Allar síður (ekki í $1 nafnrýminu)',
'allpagesprev'      => 'Síðast',
'allpagesnext'      => 'Næst',
'allpagessubmit'    => 'Áfram',
'allpagesprefix'    => 'Sýna síður með forskeytinu:',
'allpagesbadtitle'  => 'Ekki var hægt að búa til grein með þessum titli því hann innihélt einn eða fleiri stafi sem ekki er hægt að nota í titlum.',
'allpages-bad-ns'   => '{{SITENAME}} hefur ekki nafnrými „$1“.',

# Special:Listusers
'listusersfrom'      => 'Sýna notendur sem byrja á:',
'listusers-submit'   => 'Sýna',
'listusers-noresult' => 'Enginn notandi fannst.',

# E-mail user
'mailnologin'     => 'Ekkert netfang til að senda á',
'mailnologintext' => 'Þú verður að vera [[{{ns:special}}:Userlogin|innskráð(ur)]] auk þess að hafa gilt netfang í [[{{ns:special}}:Preferences|stillingunum]] þínum til að senda tölvupóst til annara notenda.',
'emailuser'       => 'Senda þessum notanda tölvupóst',
'emailpage'       => 'Senda tölvupóst',
'emailpagetext'   => 'Hafi notandi þessi fyllt út gild tölvupóstfang í stillingum sínum er hægt að senda póst til hans hér. Póstfangið sem þú fylltir út í stillingum þínum mun birtast í „From:“ hlutanum svo viðtakandinn geti svarað.',
'defemailsubject' => 'Varðandi {{SITENAME}}',
'noemailtitle'    => 'Ekkert póstfang',
'noemailtext'     => 'Notandi þessi hefur kosið að fá ekki tölvupóst frá öðrum notendum eða hefur ekki fyllt út netfang sitt í stillingum.',
'emailfrom'       => 'Frá',
'emailto'         => 'Til',
'emailsubject'    => 'Fyrirsögn',
'emailmessage'    => 'Skilaboð',
'emailsend'       => 'Senda',
'emailccme'       => 'Senda mér tölvupóst með afriti af mínum skeytum.',
'emailsent'       => 'Sending tókst',
'emailsenttext'   => 'Skilaboðin þín hafa verið send.',

# Watchlist
'watchlist'            => 'Vaktlistinn',
'mywatchlist'          => 'Vaktlistinn',
'watchlistfor'         => "(fyrir '''$1''')",
'nowatchlist'          => 'Vaktlistinn er tómur.',
'watchlistanontext'    => 'Vinsamlegast $1 til að skoða eða breyta vaktlistanum þínum.',
'watchnologin'         => 'Óinnskráð(ur)',
'watchnologintext'     => 'Þú verður að vera [[{{ns:special}}:Userlogin|innskáð(ur)]] til að geta breytt vaktlistanum.',
'addedwatch'           => 'Bætt á vaktlistann',
'addedwatchtext'       => "Síðunni „[[:$1]]“ hefur verið bætt á [[{{ns:special}}:Watchlist|Vaktlistann]] þinn.
Frekari breytingar á henni eða spallsíðu hennar munu verða sýndar þar.
Þar að auki verður síða þessi '''feitletruð''' á [[{{ns:special}}:Recentchanges|Nýlegum breytingum]]
svo auðveldara sé að sjá hana þar meðal fjöldans.

Til að fjarlægja síðu þessa af vaktlistanum þarft þú að ýta á tengilinn er merktur er „afvakta“.",
'removedwatch'         => 'Fjarlægt af vaktlistanum',
'removedwatchtext'     => 'Síðan „[[:$1]]“ hefur verið fjarlægð af vaktlistanum.',
'watch'                => 'Vakta',
'watchthispage'        => 'Vakta þessa síðu',
'unwatch'              => 'Afvakta',
'unwatchthispage'      => 'Hætta vöktun',
'watchnochange'        => 'Engri síðu á vaktlistanum þínum hefur verið breytt á tilgreindu tímabili.',
'watchlist-details'    => 'Fyrir utan spjallsíður {{PLURAL:$1|er $1 síða|eru $1 síður}} á vaktlistanum þínum.',
'wlheader-enotif'      => '* Tilkynning með tölvupósti er virk.',
'wlheader-showupdated' => "* Síðum sem hefur verið breytt síðan þú skoðaðir þær síðast eru '''feitletraðar'''",
'watchmethod-recent'   => 'kanna hvort nýlegar breytingar innihalda vaktaðar síður',
'watchmethod-list'     => 'leita að breytingum í vöktuðum síðum',
'watchlistcontains'    => 'Vaktlistinn þinn inniheldur {{PLURAL:$1|$1 síðu|$1 síður}}.',
'iteminvalidname'      => 'Vandamál með „$1“, rangt nafn...',
'wlnote'               => "Að neðan {{PLURAL:$1|er síðasta breyting|eru síðustu '''$1''' breytingar}} {{PLURAL:$2|síðastliðinn klukkutímann|síðastliðna '''$2''' klukkutímana}}.",
'wlshowlast'           => 'Sýna síðustu $1 klukkutíma, $2 daga, $3',
'watchlist-show-bots'  => 'Sýna vélmennabreytingar',
'watchlist-hide-bots'  => 'Fela vélmennabreytingar',
'watchlist-show-own'   => 'Sýna mínar breytingar',
'watchlist-hide-own'   => 'Fela mínar breytingar',
'watchlist-show-minor' => 'Sýna minniháttar breytingar',
'watchlist-hide-minor' => 'Fela minniháttar breytingar',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Vaktar...',
'unwatching' => 'Afvakta...',

'enotif_reset'                 => 'Merkja allar síður sem skoðaðar',
'enotif_newpagetext'           => 'Þetta er ný síða.',
'enotif_impersonal_salutation' => '{{SITENAME}}notandi',
'changed'                      => 'breytt',
'created'                      => 'búið til',
'enotif_anon_editor'           => 'ónefndur notandi $1',

# Delete/protect/revert
'deletepage'                  => 'Eyða',
'confirm'                     => 'Staðfesta',
'excontent'                   => 'innihaldið var: „$1“',
'excontentauthor'             => "innihaldið var: '$1' (og öll framlög voru frá '[[{{ns:special}}:Contributions/$2|$2]]')",
'exbeforeblank'               => "innihald fyrir tæmingu var: '$1'",
'exblank'                     => 'síðan var tóm',
'confirmdelete'               => 'Staðfesting á eyðingu',
'deletesub'                   => '(Eyði: „$1“)',
'historywarning'              => 'Athugið: Síðan sem þú ert um það bil að eyða á sér:',
'confirmdeletetext'           => 'Þú ert um það bil að eyða síðu eða mynd ásamt
breytingaskrá hennar úr gagnagrunninum.
Vinsamlegast staðfestu hér bæði að þetta sé vilji þinn
og að þú skiljir afleiðingarnar. Þar að auki að þetta
sé í samræmi við [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'              => 'Aðgerð lokið',
'deletedtext'                 => '„[[$1]]“ hefur verið eytt. Sjá lista yfir nýlegar eyðingar í $2.',
'deletedarticle'              => 'eyddi „[[$1]]“',
'dellogpage'                  => 'Eyðingaskrá',
'dellogpagetext'              => 'Að neðan gefur að líta lista yfir síður sem nýlega hefur verið eytt.',
'deletionlog'                 => 'eyðingaskrá',
'reverted'                    => 'Breytt aftur til fyrri útgáfu',
'deletecomment'               => 'Ástæða',
'rollback'                    => 'Taka aftur breytingar',
'rollback_short'              => 'Taka aftur',
'rollbacklink'                => 'taka aftur',
'rollbackfailed'              => 'Mistókst að taka aftur',
'cantrollback'                => 'Ekki hægt að taka aftur breytingu, síðasti höfundur er eini höfundur þessarar síðu.',
'alreadyrolled'               => 'Ekki var hægt að taka síðustu breytingu [[:$1]] 
eftir [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|spjall]]) til baka; eitthver annar hefur breytt síðunni eða nú þegar tekið breytinguna til baka.

Síðasta breyting er frá [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|Spjall]]).',
'revertpage'                  => 'Tók aftur breytingar [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user talk}}:$2|spjall]]), breytt til síðustu útgáfu [[{{ns:user}}:$1|$1]]',
'rollback-success'            => 'Tók til baka breytingar eftir $1; núverandi $2.',
'protectlogpage'              => 'Verndunarskrá',
'protectlogtext'              => 'Þetta er skrá yfir verndunar- og afverndunaraðgerðir. Sjáðu [[Special:Protectedpages|listann yfir verndaðar síður]] fyrir núverandi lista yfir verndaðar síður.',
'protectedarticle'            => 'verndaði „[[$1]]“',
'unprotectedarticle'          => 'afverndaði „[[$1]]“',
'protectsub'                  => '(Vernda „$1“)',
'confirmprotect'              => 'Verndunarstaðfesting',
'protectcomment'              => 'Ástæða',
'protectexpiry'               => 'Rennur út:',
'unprotectsub'                => '(Afvernda „$1“)',
'protect-unchain'             => 'Opna fyrir færslur',
'protect-text'                => 'Hér getur þú skoðað og breytt verndunarstigi síðunnar <strong>$1</strong>.',
'protect-default'             => '(sjálfgefið)',
'protect-fallback'            => '„$1“ réttindi nauðsynleg',
'protect-level-autoconfirmed' => 'Banna óinnskráða notendur',
'protect-level-sysop'         => 'Leyfa aðeins stjórnendur',
'protect-summary-cascade'     => 'keðjuvörn',
'protect-expiring'            => 'rennur út $1 (UTC)',
'restriction-type'            => 'Réttindi:',
'restriction-level'           => 'Takmarkaði við:',
'minimum-size'                => 'Lágmarksstærð',
'maximum-size'                => 'Hámarksstærð',
'pagesize'                    => '(bæt)',

# Restrictions (nouns)
'restriction-edit' => 'Breyta',
'restriction-move' => 'Færa',

# Restriction levels
'restriction-level-sysop'         => 'alvernduð',
'restriction-level-autoconfirmed' => 'hálfvernduð',
'restriction-level-all'           => 'öll stig',

# Undelete
'undelete'                 => 'Endurvekja eydda síðu',
'undeletepage'             => 'Skoða og endurvekja síður',
'viewdeletedpage'          => 'Skoða eyddar síður',
'undeletepagetext'         => 'Eftirfarandi síðum hefur verið eitt en eru þó enn í gagnagrunninum og geta verið endurvaknar. Athugið að síður þessar eru reglulega fjarlægðar endanlega úr gagnagrunninum.',
'undeleterevisions'        => '$1 {{PLURAL:$1|breyting|breytingar}}',
'undeletebtn'              => 'Endurvekja!',
'undeletereset'            => 'Endurstilla',
'undeletecomment'          => 'Athugasemd:',
'undeletedarticle'         => 'endurvakti „[[$1]]“',
'undeletedrevisions'       => '$1 {{PLURAL:$1|breyting endurvakin|breytingar endurvaktar}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|breyting|breytingar}} og $2 {{PLURAL:$2|skrá|skrár}} endurvaktar',
'undeletedfiles'           => '{{PLURAL:$1|Ein skrá endurvakin|$1 skrár endurvaktar}}',
'cannotundelete'           => 'Ekki var hægt að afturkalla síðuna. (Líklega hefur einhver gert það á undan þér.)',
'undeletedpage'            => "<big>'''$1 var endurvakin'''</big>

Skoðaðu [[{{ns:special}}:Log/delete|eyðingaskrána]] til að skoða eyðingar og endurvakningar.",
'undelete-search-box'      => 'Leita að eyddum síðum',
'undelete-search-prefix'   => 'Sýna síður sem byrja á:',
'undelete-search-submit'   => 'Leita',
'undelete-no-results'      => 'Engar samsvarandi síður fundust í eyðingarskjalasafninu.',

# Namespace form on various pages
'namespace'      => 'Nafnrými:',
'invert'         => 'allt nema valið',
'blanknamespace' => '(Aðalnafnrýmið)',

# Contributions
'contributions' => 'Framlög notanda',
'mycontris'     => 'Framlög',
'contribsub2'   => 'Eftir $1 ($2)',
'nocontribs'    => 'Engar breytingar fundnar sem passa við þessa viðmiðun.',
'uctop'         => '(nýjast)',
'month'         => 'Frá mánuðinum (og fyrr):',
'year'          => 'Frá árinu (og fyrr):',

'sp-contributions-newest'      => 'Nýjast',
'sp-contributions-oldest'      => 'Elst',
'sp-contributions-newer'       => 'Nýrri $1',
'sp-contributions-older'       => 'Eldri $1',
'sp-contributions-newbies'     => 'Sýna aðeins breytingar frá nýjum notendum',
'sp-contributions-newbies-sub' => 'Fyrir nýliða',
'sp-contributions-blocklog'    => 'Fyrri bönn',
'sp-contributions-search'      => 'Leita að framlögum',
'sp-contributions-username'    => 'Vistfang eða notandanafn:',
'sp-contributions-submit'      => 'Leita að breytingum',

# What links here
'whatlinkshere'       => 'Hvað tengist hingað',
'whatlinkshere-title' => 'Síður sem tengjast $1',
'whatlinkshere-page'  => 'Síða:',
'linklistsub'         => '(Listi yfir ítengdar síður)',
'linkshere'           => "Eftirfarandi síður tengjast á '''[[:$1]]''':",
'nolinkshere'         => "Engar síður tengjast á '''[[:$1]]'''.",
'nolinkshere-ns'      => "Engar síður tengjast '''[[:$1]]''' í þessu nafnrými.",
'isredirect'          => 'tilvísun',
'istemplate'          => 'innifalið',
'whatlinkshere-prev'  => '{{PLURAL:$1|fyrra|fyrri $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|næst|næstu $1}}',
'whatlinkshere-links' => '← tenglar',

# Block/unblock
'blockip'                     => 'Banna notanda',
'blockiptext'                 => 'Hægt er koma í veg fyrir breytingar á {{SITENAME}} frá einstökum notendum eða vistföngum.  Aðeins ætti að banna notendur fyrir skemmdarverk og í samræmi við [[{{MediaWiki:Policy-url}}|reglur]] {{SITENAME}}.

Gefðu ástæðu fyrir banninu (meðal annars að nefna síðu sem var skemmd).',
'ipaddress'                   => 'Vistfang:',
'ipadressorusername'          => 'Vistfang eða notandanafn:',
'ipbexpiry'                   => 'Bannið rennur út:',
'ipbreason'                   => 'Ástæða:',
'ipbreasonotherlist'          => 'Aðrar ástæður',
'ipbreason-dropdown'          => '*Algengar bannástæður
** Skjalafals
** Síðutæmingar
** Rusltenglar á aðrar vefsíður
** Setur inn bull á síður
** Slæm framkoma við aðra notendur
** Fjöldi notendanafna
** Óásættanlegt notandanafn',
'ipbanononly'                 => 'Banna einungis ónafngreinda notendur',
'ipbcreateaccount'            => 'Banna nýskráningu notanda',
'ipbemailban'                 => 'Banna notanda að senda tölvupóst',
'ipbenableautoblock'          => 'Banna síðasta vistfang notanda sjálfkrafa; og þau vistföng sem viðkomandi notar til að breyta síðum',
'ipbsubmit'                   => 'Banna notanda',
'ipbother'                    => 'Annar tími:',
'ipboptions'                  => '2 tíma:2 hours,1 dag:1 day,3 daga:3 days,1 viku:1 week,2 vikur:2 weeks,1 mánuð:1 month,3 mánuði:3 months,6 mánuði:6 months,1 ár:1 year,aldrei:infinite',
'ipbotheroption'              => 'annar',
'ipbotherreason'              => 'Önnur/auka ástæða:',
'ipbhidename'                 => 'Fela notandanafn/vistfang úr bannskrá og notandaskrá',
'badipaddress'                => 'Ógilt vistfang',
'blockipsuccesssub'           => 'Bann tókst',
'blockipsuccesstext'          => '[[{{ns:special}}:Contributions/$1|$1]] hefur verið bannaður/bönnuð.
<br />Sjá [[{{ns:special}}:Ipblocklist|bannaðar notendur og vistföng]] fyrir yfirlit yfir núverandi bönn.',
'ipb-edit-dropdown'           => 'Breyta ástæðu fyrir banni',
'ipb-unblock-addr'            => 'Afbanna $1',
'ipb-unblock'                 => 'Afbanna notanda eða vistfang',
'ipb-blocklist-addr'          => 'Sjá núverandi bönn fyrir $1',
'ipb-blocklist'               => 'Sjá núverandi bönn',
'unblockip'                   => 'Afbanna notanda',
'unblockiptext'               => 'Endurvekja skrifréttindi bannaðra notenda eða vistfanga.',
'ipusubmit'                   => 'Afbanna',
'unblocked'                   => '[[User:$1|$1]] hefur verið afbannaður',
'unblocked-id'                => 'Bann $1 hefur verið fjarlægt',
'ipblocklist'                 => 'Bannaðir notendur og vistföng',
'ipblocklist-legend'          => 'Finna bannaðann notanda',
'ipblocklist-username'        => 'Notandanafn eða vistfang:',
'ipblocklist-submit'          => 'Leita',
'blocklistline'               => '$1, $2 bannaði $3 (rennur út $4)',
'infiniteblock'               => 'aldrei',
'expiringblock'               => 'rennur út  $1',
'createaccountblock'          => 'bann við stofnun nýrra notenda',
'emailblock'                  => 'tölvupóstur bannaður',
'ipblocklist-empty'           => 'Bannlistinn er tómur.',
'ipblocklist-no-results'      => 'Umbeðið vistfang eða notandanafn er ekki í banni.',
'blocklink'                   => 'banna',
'unblocklink'                 => 'afbanna',
'contribslink'                => 'framlög',
'autoblocker'                 => 'Vistfang þitt er bannað vegna þess að það hefur nýlega verið notað af „[[{{ns:user}}:$1|$1]]“. Ástæðan fyrir því að $1 var bannaður er: „$2“',
'blocklogpage'                => 'Bönnunarskrá',
'blocklogentry'               => 'bannaði „[[$1]]“; rennur út eftir: $2 $3',
'blocklogtext'                => 'Þetta er skrá yfir bönn sem lögð hafa verið á notendur eða bönn sem hafa verið numin úr gildi. Vistföng sem sett hafa verið í bann sjálfvirkt birtast ekki hér. Sjá [[{{ns:special}}:Ipblocklist|ítarlegri lista]] fyrir öll núgildandi bönn.',
'unblocklogentry'             => 'afbannaði $1',
'block-log-flags-anononly'    => 'bara ónefndir notendur',
'block-log-flags-nocreate'    => 'gerð aðganga bönnuð',
'block-log-flags-noautoblock' => 'sjálfkrafa bann óvirkt',
'block-log-flags-noemail'     => 'netfang bannað',
'ipb_expiry_invalid'          => 'Tími ógildur.',
'ipb_already_blocked'         => '„$1“ er nú þegar í banni',
'ipb_cant_unblock'            => 'Villa: Bann-tala $1 fannst ekki. Hún gæti nú þegar hafa verið afbönnuð.',
'ip_range_invalid'            => 'Ógilt vistfangasvið.',
'proxyblocksuccess'           => 'Búinn.',

# Developer tools
'lockdb'              => 'Læsa gagnagrunninum',
'unlockdb'            => 'Opna gagnagrunninn',
'lockconfirm'         => 'Já, ég er viss um að ég vilji læsa gagnagrunninum.',
'unlockconfirm'       => 'Já, ég vil aflæsa gagnagrunninum.',
'lockbtn'             => 'Læsa gagnagrunni',
'unlockbtn'           => 'Aflæsa gagnagrunninum',
'lockdbsuccesssub'    => 'Læsing á gagnagrunninum tóks',
'unlockdbsuccesssub'  => 'Læsing á gagnagrunninum hefur verið fjarlægð',
'lockdbsuccesstext'   => 'Gagnagrunninum hefur verið læst.
<br />Mundu að [[{{ns:special}}:Unlockdb|opna hann aftur]] þegar þú hefur lokið viðgerðum.',
'unlockdbsuccesstext' => 'Gagnagrunnurinn hefur verið opnaður.',
'databasenotlocked'   => 'Gagnagrunnurinn er ekki læstur.',

# Move page
'movepage'                => 'Færa síðu',
'movepagetext'            => "Hér er hægt að endurnefna síðu, hún mun ásamt breytingarskrá hennar
verða færð á nýja nafnið og núverandi staðsetning mun
breytast í tilvísun sem vísa mun á nýju staðsetninguna,
tenglar í núverandi nafn munu hinsvegar ekki breytast,
athugaðu að þetta búi ekki til margfaldar
tilvísanir, það er á þína ábyrgð að tryggja það að tenglar haldi áfram
að vísa á rétta síðu.

Athugaðu að síðan mun '''ekki''' verða færð ef það er þegar síða á nafninu
sem þú hyggst færa hana á, nema síða sú sé tóm eða tilvísun sem á sér enga
breytingarsögu. Þú getur þar með fært síðuna aftur til baka án þess að
missa breytingarsöguna, en ekki fært hana venjulega síðu.

'''Varúð:'''
Vertu viss um að skilja afleiðingarnar af þessari aðgerð vel. Þetta gæti þýtt
mjög rótækar breytingar á vinsælum síðum og valdið titringi hjá öðrum notendum.",
'movepagetalktext'        => 'Spallsíða síðunnar verður sjálfkrafa færð með ef hún er til nema:
* Þú sért að færa síðuna á milli nafnrýma
* Spallsíða sé þegar til undir nýja nafninu
* Þú veljir að færa hana ekki
Í þeim tilfellum verður að færa hana handvirkt.',
'movearticle'             => 'Færa',
'movenologin'             => 'Óinnskráð(ur)',
'movenologintext'         => 'Þú verður að vera [[{{ns:special}}:Userlogin|innskráð(ur)]] til að geta fært síður.',
'movenotallowed'          => 'Þú hefur ekki leyfi til að færa síður á þessari wiki síðu.',
'newtitle'                => 'Yfir á',
'move-watch'              => 'Vakta þessa síðu',
'movepagebtn'             => 'Færa síðuna',
'pagemovedsub'            => 'Færsla tókst',
'movepage-moved'          => "<big>'''„$1“ hefur verið færð á „$2“'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Annaðhvort er þegar til síða undir þessum titli,
eða sá titill sem þú hefur valið er ekki gildur.
Vinsamlegast veldu annan titil.',
'movedto'                 => 'fært á',
'movetalk'                => 'Færa „Spjall“ síðuna líka ef við á.',
'talkpagemoved'           => 'Spjallsíðan var einnig færð.',
'talkpagenotmoved'        => 'Samsvarandi spjallsíða var <strong>ekki</strong> færð.',
'1movedto2'               => '[[$1]] færð á [[$2]]',
'1movedto2_redir'         => '[[$1]] færð á [[$2]] yfir tilvísun',
'movelogpage'             => 'Flutningaskrá',
'movelogpagetext'         => 'Þetta er listi yfir síður sem nýlega hafa verið færðar.',
'movereason'              => 'Ástæða:',
'revertmove'              => 'taka til baka',
'delete_and_move'         => 'Eyða og flytja',
'delete_and_move_text'    => '==Beiðni um eyðingu==

Síðan „[[$1]]“ er þegar til. Viltu eyða henni til þess að rýma til fyrir flutningi?',
'delete_and_move_confirm' => 'Já, eyða síðunni',
'delete_and_move_reason'  => 'Eytt til að rýma til fyrir flutning',
'selfmove'                => 'Nýja nafnið er það sama og gamla, þú verður að velja annað nafn.',

# Export
'export'            => 'XML útgáfa síðu',
'exportcuronly'     => 'Aðeins núverandi útgáfu án breytingarskrá',
'export-submit'     => 'Flytja',
'export-addcattext' => 'Bæta við síðum frá flokkinum:',
'export-addcat'     => 'Bæta við',
'export-download'   => 'Vista sem skjal',

# Namespace 8 related
'allmessages'               => 'Meldingar',
'allmessagesname'           => 'Titill',
'allmessagesdefault'        => 'Sjálfgefinn texti',
'allmessagescurrent'        => 'Núverandi texti',
'allmessagestext'           => 'Listi yfir meldingar í „Melding“ nafnrýminu.',
'allmessagesnotsupportedDB' => "Það er ekki hægt að nota '''{{ns:special}}:Allmessages''' því '''\$wgUseDatabaseMessages''' hefur verið gerð óvirk.",
'allmessagesmodified'       => 'Sýna aðeins breyttar',

# Thumbnails
'thumbnail-more' => 'Stækka',
'missingimage'   => '<b>Mynd vantar</b><br /><i>$1</i>',
'filemissing'    => 'Skrá vantar',

# Special:Import
'import'                     => 'Flytja inn síður',
'importinterwiki'            => 'Milli-Wiki innflutningur',
'import-interwiki-text'      => 'Veldu Wiki-kerfi og síðutitil til að flytja inn.
Breytingaupplýsingar s.s. dagsetningar og höfundanöfn eru geymd.
Allir innflutningar eru skráð í [[{{ns:special}}:Log/import|innflutningsskránna]].',
'import-interwiki-history'   => 'Afrita allar breytingar þessarar síðu',
'import-interwiki-submit'    => 'Flytja inn',
'import-interwiki-namespace' => 'Færa síður í nafnrými:',
'importstart'                => 'Flyt inn síður...',
'import-revision-count'      => '$1 {{PLURAL:$1|breyting|breytingar}}',
'importnopages'              => 'Engar síður til innflutnings.',
'importfailed'               => 'Innhlaðning mistókst: $1',
'importcantopen'             => 'Get ekki opnað innflutt skjal',
'importbadinterwiki'         => 'Villa í tungumálatengli',
'importnotext'               => 'Tómt eða enginn texti',
'importsuccess'              => 'Innflutningur tókst!',

# Import log
'importlogpage'                    => 'Innflutningsskrá',
'import-logentry-upload'           => 'flutti inn [[$1]] með innflutningi',
'import-logentry-upload-detail'    => '$1 breyting(ar)',
'import-logentry-interwiki'        => 'flutti inn $1',
'import-logentry-interwiki-detail' => '$1 breyting(ar) frá $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Notendasíðan mín',
'tooltip-pt-anonuserpage'         => 'Notandasíðan fyrir vistfangið þitt',
'tooltip-pt-mytalk'               => 'Spallsíðan mín',
'tooltip-pt-anontalk'             => 'Spjallsíðan fyrir þetta vistfang',
'tooltip-pt-preferences'          => 'Almennar stillingar',
'tooltip-pt-watchlist'            => 'Vaktlistinn.',
'tooltip-pt-mycontris'            => 'Listi yfir framlög þín',
'tooltip-pt-login'                => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki skylda.',
'tooltip-pt-anonlogin'            => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.',
'tooltip-pt-logout'               => 'Útskráning',
'tooltip-ca-talk'                 => 'Spallsíða þessarar síðu',
'tooltip-ca-edit'                 => 'Þú getur breytt síðu þessari, vinsamlegast notaðu „forskoða“ hnappinn áður en þú vistar',
'tooltip-ca-addsection'           => 'Viðbótarumræða.',
'tooltip-ca-viewsource'           => 'Síða þessi er vernduð, þú getur þó skoðað frumkóða hennar.',
'tooltip-ca-history'              => 'Eldri útgáfur af síðunni.',
'tooltip-ca-protect'              => 'Vernda þessa síðu',
'tooltip-ca-delete'               => 'Eyða þessari síðu',
'tooltip-ca-undelete'             => 'Endurvekja breytingar á síðu þessari fyrir en henni var tortímt',
'tooltip-ca-move'                 => 'Færa þessa síðu',
'tooltip-ca-watch'                => 'Bæta þessari síðu við á vaktlistann',
'tooltip-ca-unwatch'              => 'Fjarlægja þessa síðu af vaktlistanum',
'tooltip-search'                  => 'Leit á þessari Wiki',
'tooltip-p-logo'                  => 'Forsíða',
'tooltip-n-mainpage'              => 'Forsíða {{SITENAME}}',
'tooltip-n-portal'                => 'Um verkefnið, hvernig er hægt að hjálpa og hvar á að byrja',
'tooltip-n-currentevents'         => 'Líðandi stund',
'tooltip-n-recentchanges'         => 'Listi yfir nýlegar breytingar.',
'tooltip-n-randompage'            => 'Handahófsvalin síða',
'tooltip-n-help'                  => 'Efnisyfirlit yfir hjálparsíður.',
'tooltip-n-sitesupport'           => 'Fjárframlagssíða',
'tooltip-t-whatlinkshere'         => 'Listi yfir síður sem tengjast í þessa',
'tooltip-t-recentchangeslinked'   => 'Nýlegar breitingar á ítengdum síðum',
'tooltip-feed-rss'                => 'RSS fyrir þessa síðu',
'tooltip-feed-atom'               => 'Atom fyrir þessa síðu',
'tooltip-t-contributions'         => 'Sýna framlagslista þessa notanda',
'tooltip-t-emailuser'             => 'Senda notanda þessum póst',
'tooltip-t-upload'                => 'Hlaða inn myndum eða margmiðlunarskrám',
'tooltip-t-specialpages'          => 'Listi yfir kerfissíður',
'tooltip-t-permalink'             => 'Varanlegur tengill',
'tooltip-ca-nstab-main'           => 'Sýna síðuna',
'tooltip-ca-nstab-user'           => 'Sýna notendasíðuna',
'tooltip-ca-nstab-media'          => 'Sýna margmiðlunarsíðuna',
'tooltip-ca-nstab-special'        => 'Þetta er kerfissíða, þér er óhæft að breyta henni.',
'tooltip-ca-nstab-project'        => 'Sýna verkefnasíðuna',
'tooltip-ca-nstab-image'          => 'Sýna myndasíðuna',
'tooltip-ca-nstab-mediawiki'      => 'Sýna kerfisskilaboðin',
'tooltip-ca-nstab-template'       => 'Sýna sniðið',
'tooltip-ca-nstab-help'           => 'Sýna hjálparsíðuna',
'tooltip-ca-nstab-category'       => 'Sýna efnisflokkasíðuna',
'tooltip-minoredit'               => 'Merkja þessa breytingu sem minniháttar',
'tooltip-save'                    => 'Vista breytingarnar',
'tooltip-preview'                 => 'Forskoða breytingarnar, vinsamlegast gerðu þetta áður en þú vistar!',
'tooltip-compareselectedversions' => 'Sjá breytingarnar á þessari grein á milli útgáfanna sem þú valdir.',
'tooltip-watch'                   => 'Bæta þessari síðu á vaktlistann þinn',
'tooltip-recreate'                => 'Endurvekja síðuna þó henni hafi verið eytt',

# Stylesheets
'common.css'   => '/* Allt CSS sem sett er hér mun virka á öllum þemum. */',
'monobook.css' => '/* Það sem sett er hingað er bætt við Monobook stilsniðið fyrir allan vefinn */',

# Scripts
'common.js' => '/* Allt JavaScript sem sett er hér mun virka í hvert skipti sem að síða hleðst. */',

# Attribution
'anonymous'        => 'Ónefndir notendur {{SITENAME}}',
'siteuser'         => '{{SITENAME}} notandi $1',
'lastmodifiedatby' => 'Þessari síðu var síðast breytt $2, $1 af $3.', # $1 date, $2 time, $3 user
'and'              => 'og',
'othercontribs'    => 'Byggt á verkum $1.',

# Spam protection
'subcategorycount'       => 'Það {{PLURAL:$1|er einn undirflokkur|eru $1 undirflokkar}} í þessum flokki.',
'categoryarticlecount'   => 'Það {{PLURAL:$1|er 1 grein|eru $1 greinar}} í þessum flokki.',
'category-media-count'   => 'Það {{PLURAL:$1|er ein skrá|eru $1 skrár}} í þessum flokki.',
'listingcontinuesabbrev' => 'frh.',

# Info page
'infosubtitle'   => 'Upplýsingar um síðu',
'numedits'       => 'Fjöldi breytinga (síða): $1',
'numtalkedits'   => 'Fjöldi breytinga (spjall síða): $1',
'numwatchers'    => 'Fjöldi vaktara: $1',
'numauthors'     => 'Fjöldi frábrugðinna höfunda (grein): $1',
'numtalkauthors' => 'Fjöldi frábrugðinna höfunda (spjall síða): $1',

# Math options
'mw_math_png'    => 'Alltaf birta PNG mynd',
'mw_math_simple' => 'HTML fyrir einfaldar jöfnur annars PNG',
'mw_math_html'   => 'HTML ef hægt er, annars PNG',
'mw_math_source' => 'Sýna TeX jöfnu (fyrir textavafra)',
'mw_math_modern' => 'Mælt með fyrir nýja vafra',
'mw_math_mathml' => 'MathML',

# Patrol log
'patrol-log-auto' => '(sjálfkrafa)',

# Image deletion
'deletedrevision'       => 'Eydd gömul útgáfu $1',
'filedeleteerror-short' => 'Villa við eyðingu: $1',
'filedeleteerror-long'  => 'Það kom upp villa við eyðingu skráarinnar: $1',

# Browsing diffs
'previousdiff' => '← Fyrri breyting',
'nextdiff'     => 'Næsta breyting →',

# Media information
'imagemaxsize'         => 'Takmarka stærðir mynda á myndasíðum við:',
'thumbsize'            => 'Stærð smámynda:',
'widthheightpage'      => '$1×$2, $3 síður',
'file-info'            => '(stærð skráar: $1, MIME tegund: $2)',
'file-info-size'       => '($1 × $2 depplar, stærð skráar: $3, MIME gerð: $4)',
'file-nohires'         => '<small>Það er engin hærri upplausn til.</small>',
'show-big-image'       => 'Mesta upplausn',
'show-big-image-thumb' => '<small>Myndin er í upplausninni $1 × $2 </small>',

# Special:Newimages
'newimages'    => 'Myndasafn nýlegra skráa',
'showhidebots' => '($1 vélmenni)',
'noimages'     => 'Ekkert að sjá.',

# Metadata
'metadata'          => 'Lýsigögn',
'metadata-collapse' => 'Fela auka upplýsingar',

# EXIF tags
'exif-imagewidth'       => 'Breidd',
'exif-imagelength'      => 'Hæð',
'exif-xresolution'      => 'Lárétt upplausn',
'exif-yresolution'      => 'Lóðrétt upplausn',
'exif-imagedescription' => 'Titill myndar',
'exif-make'             => 'Framleiðandi myndavélar',
'exif-model'            => 'Tegund',
'exif-software'         => 'Hugbúnaður notaður',
'exif-artist'           => 'Höfundur',
'exif-pixelydimension'  => 'Leyfð myndalengd',
'exif-pixelxdimension'  => 'Leyfð myndahæð',
'exif-usercomment'      => 'Athugunarsemdir notanda',

# EXIF attributes
'exif-compression-1' => 'Ósamþjappað',

'exif-componentsconfiguration-0' => 'er ekki til',

'exif-exposureprogram-0' => 'Ekki skilgreint',

'exif-subjectdistance-value' => '$1 metrar',

'exif-lightsource-9'  => 'Gott veður',
'exif-lightsource-10' => 'Skýjað',
'exif-lightsource-11' => 'Skuggi',

'exif-focalplaneresolutionunit-2' => 'tommur',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kílómetra á klukkustund',
'exif-gpsspeed-m' => 'Mílur á klukkustund',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'allt',
'imagelistall'     => 'allar',
'watchlistall2'    => 'allt',
'namespacesall'    => 'öll',
'monthsall'        => 'allir',

# E-mail address confirmation
'confirmemail'            => 'Staðfesta netfang',
'confirmemail_noemail'    => 'Þú hefur ekki gefið upp gilt netfang í [[{{ns:special}}:Preferences|notandastillingum]] þínum.',
'confirmemail_send'       => 'Senda staðfestingarkóða með tölvupósti',
'confirmemail_sent'       => 'Staðfestingartölvupóstur sendur.',
'confirmemail_sendfailed' => 'Gat ekki sent staðfestingarkóða. Athugaðu hvort netfangið sé rétt.

Póstþjónninn gaf eftirfarandi skilaboð: $1',
'confirmemail_invalid'    => 'Ógildur staðfestingarkóði. Hann gæti verið útrunninn.',
'confirmemail_needlogin'  => 'Þú verður að $1 til að staðfesta netfangið þitt.',
'confirmemail_success'    => 'Netfang þitt hefur verið staðfest. Þú getur nú skráð þig inn og vafrað um wiki-kerfið.',
'confirmemail_loggedin'   => 'Netfang þitt hefur verið staðfest.',
'confirmemail_error'      => 'Eitthvað fór úrskeiðis við vistun staðfestingarinnar.',
'confirmemail_subject'    => '{{SITENAME}} netfangs-staðfesting',
'confirmemail_body'       => 'Einhver, sennilega þú, með vistfangið $1 hefur skráð sig á íslensku {{SITENAME}} undir notandanafninu „$2“ og gefið upp þetta netfang.

Til að staðfesta að það hafi verið þú sem skráðir þig undir þessu nafni, og til þess að virkja póstsendingar í gegnum {{SITENAME}}, skaltu opna þennan tengil:

$3

Ef þú ert *ekki* sá sem skráði þetta notandanafn skaltu alls ekki fylgja tenglinum. Þessi staðfestingarkóði rennur út $4.',

# Scary transclusion
'scarytranscludefailed'  => '[Gat ekki sótt snið fyrir $1; því miður]',
'scarytranscludetoolong' => '[URL-ið er of langt; því miður]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Varanlegir tenglar fyrir þessa grein:<br />
$1
</div>',
'trackbackremove'   => '([$1 {{plural:$1|eydd|eyddar}}])',
'trackbacklink'     => 'Varanlegur tengill',
'trackbackdeleteok' => 'Varanlega tenglinum var eytt.',

# Delete conflict
'deletedwhileediting' => 'Viðvörun: Þessari síðu var eytt á meðan þú varst að breyta henni!',
'confirmrecreate'     => "Notandi [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|spjall]]) eyddi þessari síðu eftir að þú fórst að breyta henni út af: ''$2''
Vinsamlegast staðfestu að þú viljir endurvekja hana.",
'recreate'            => 'Endurvekja',

# HTML dump
'redirectingto' => 'Tilvísun á [[$1]]...',

# action=purge
'confirm_purge'        => 'Hreinsa skyndiminni þessarar síðu?

$1',
'confirm_purge_button' => 'Í lagi',

# AJAX search
'searchcontaining' => "Leita að greinum sem innihalda ''$1''.",
'searchnamed'      => "Leita að greinum sem heita ''$1''.",
'articletitles'    => "Greinar sem hefjast á ''$1''",
'hideresults'      => 'Fela niðurstöður',

# Multipage image navigation
'imgmultipageprev' => '← fyrri síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo'       => 'Áfram!',
'imgmultigotopre'  => 'Fara á síðuna',

# Table pager
'table_pager_next'         => 'Næsta síða',
'table_pager_prev'         => 'Fyrri síða',
'table_pager_first'        => 'Fyrsta síðan',
'table_pager_last'         => 'Síðasta síðan',
'table_pager_limit'        => 'Sýna $1 hluti á hverri síðu',
'table_pager_limit_submit' => 'Áfram',
'table_pager_empty'        => 'Engar niðurstöður',

# Auto-summaries
'autosumm-blank'   => 'Tæmdi síðuna',
'autosumm-replace' => 'Skipti út innihaldi með „$1“',
'autoredircomment' => 'Tilvísun á [[$1]]',
'autosumm-new'     => 'Ný síða: $1',

# Live preview
'livepreview-loading' => 'Framkalla…',
'livepreview-ready'   => '… framköllun lokið!',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Breytingar nýrri en $1 sekúndur gætu ekki verið sýndar á þessum lista.',
'lag-warn-high'   => 'Vegna mikils álags á vefþjónanna munu breytingar yngry en $1 sekúndur ekki vera sýndar á þessum lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'Á vaktlista þínum {{PLURAL:$1|er 1 síða|eru $1 síður}}, að undanskildum spjallsíðum.',
'watchlistedit-noitems'        => 'Vaktalistinn þinn inniheldur enga titla.',
'watchlistedit-normal-title'   => 'Breyta vaktalistanum',
'watchlistedit-normal-legend'  => 'Fjarlægja titla af vaktalistanum',
'watchlistedit-normal-explain' => 'Titlarnir á vaktlistanum þínum er sýndir fyrir neðan. Til að fjarlægja titil hakaðu í kassan við hliðina á honum og smelltu á „Fjarlægja titla“. Þú getur einnig [[Special:Watchlist/raw|breytt honum opnum]] eða [[Special:Watchlist/clear|fjarlægt alla titla]].',
'watchlistedit-normal-submit'  => 'Fjarlægja titla',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Ein síða var fjarlægð|$1 síður voru fjarlægðar}} af vaktlistanum þínum:',
'watchlistedit-raw-title'      => 'Breyta opnum vaktlistanum',
'watchlistedit-raw-legend'     => 'Breyta opnum vaktlistanum',
'watchlistedit-raw-explain'    => 'Titlarnir á vaktlistanum þínum er sýndir fyrir neðan, þar sem mögulegt er að breyta þeim með því að bæta við hann og taka af honum; einn tiltil í hverri línu. Þegar þú er búinn, smelltu þá á „Uppfæra vaktlistann“. Þú getur einnig notað [[Special:Watchlist/edit|staðlaða breytinn]].',
'watchlistedit-raw-titles'     => 'Titlar:',
'watchlistedit-raw-submit'     => 'Uppfæra vaktalistann',
'watchlistedit-raw-done'       => 'Vaktalistinn þinn hefur verið uppfærður.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Einum titli|$1 titlum}} var bætt við:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titill var fjarlægður|$1 titlar voru fjarlægðir}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Sýna viðeigandi breytingar',
'watchlisttools-edit' => 'Skoða og breyta vaktalistanum',
'watchlisttools-raw'  => 'Breyta opnum vaktlistanum',

);
