<?php
/** Icelandic (íslenska)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bjarki S
 * @author Cessator
 * @author Friðrik Bragi Dýrfjörð
 * @author Gott wisst
 * @author Jóna Þórunn
 * @author Kaganer
 * @author Krun
 * @author Maxí
 * @author S.Örvarr.S
 * @author Snævar
 * @author Spacebirdy
 * @author Steinninn
 * @author Urhixidur
 * @author Ævar Arnfjörð Bjarmason
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Miðill',
	NS_SPECIAL          => 'Kerfissíða',
	NS_TALK             => 'Spjall',
	NS_USER             => 'Notandi',
	NS_USER_TALK        => 'Notandaspjall',
	NS_PROJECT_TALK     => '$1spjall',
	NS_FILE             => 'Mynd',
	NS_FILE_TALK        => 'Myndaspjall',
	NS_MEDIAWIKI        => 'Melding',
	NS_MEDIAWIKI_TALK   => 'Meldingarspjall',
	NS_TEMPLATE         => 'Snið',
	NS_TEMPLATE_TALK    => 'Sniðaspjall',
	NS_HELP             => 'Hjálp',
	NS_HELP_TALK        => 'Hjálparspjall',
	NS_CATEGORY         => 'Flokkur',
	NS_CATEGORY_TALK    => 'Flokkaspjall',
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
	'redirect'                  => array( '0', '#tilvísun', '#TILVÍSUN', '#REDIRECT' ),
	'nogallery'                 => array( '0', '__EMSAFN__', '__NOGALLERY__' ),
	'currentday'                => array( '1', 'NÚDAGUR', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'NÚDAGUR2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NÚDAGNAFN', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'NÚÁR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'NÚTÍMI', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'NÚKTÍMI', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'STMÁN', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'STMÁNNAFN', 'LOCALMONTHNAME' ),
	'localmonthabbrev'          => array( '1', 'STMÁNST', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'STDAGUR', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'STDAGUR2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'STDAGNAFN', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'STÁR', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'STTÍMI', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'STKTÍMI', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'FJLSÍÐA', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'FJLGREINA', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FJLSKJALA', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'FJLNOT', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'FJLBREYT', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'SÍÐUNAFN', 'PAGENAME' ),
	'namespace'                 => array( '1', 'NAFNSVÆÐI', 'NAMESPACE' ),
	'talkspace'                 => array( '1', 'SPJALLSVÆÐI', 'TALKSPACE' ),
	'fullpagename'              => array( '1', 'FULLTSÍÐUNF', 'FULLPAGENAME' ),
	'img_manualthumb'           => array( '1', 'þumall', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'hægri', 'right' ),
	'img_left'                  => array( '1', 'vinstri', 'left' ),
	'img_none'                  => array( '1', 'engin', 'none' ),
	'img_width'                 => array( '1', '$1dp', '$1px' ),
	'img_center'                => array( '1', 'miðja', 'center', 'centre' ),
	'img_sub'                   => array( '1', 'undir', 'sub' ),
	'img_super'                 => array( '1', 'yfir', 'super', 'sup' ),
	'img_top'                   => array( '1', 'efst', 'top' ),
	'img_bottom'                => array( '1', 'neðst', 'bottom' ),
	'img_text_bottom'           => array( '1', 'texti-neðst', 'text-bottom' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'server'                    => array( '0', 'VEFÞJ', 'SERVER' ),
	'servername'                => array( '0', 'VEFÞJNF', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'MÁLFRÆÐI:', 'GRAMMAR:' ),
	'currentweek'               => array( '1', 'NÚVIKA', 'CURRENTWEEK' ),
	'localweek'                 => array( '1', 'STVIKA', 'LOCALWEEK' ),
	'plural'                    => array( '0', 'FLTALA:', 'PLURAL:' ),
	'raw'                       => array( '0', 'HRÁ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'SÝNATITIL', 'DISPLAYTITLE' ),
	'language'                  => array( '0', '#TUNGUMÁL', '#LANGUAGE:' ),
	'special'                   => array( '0', 'kerfissíða', 'special' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Virkir_notendur' ),
	'Allmessages'               => array( 'Meldingar' ),
	'Allpages'                  => array( 'Allar_síður' ),
	'Ancientpages'              => array( 'Elstu_síður' ),
	'Blankpage'                 => array( 'Auð_síða' ),
	'Block'                     => array( 'Banna_vistföng' ),
	'Blockme'                   => array( 'Banna_mig' ),
	'Booksources'               => array( 'Bókaheimildir' ),
	'BrokenRedirects'           => array( 'Brotnar_tilvísanir' ),
	'Categories'                => array( 'Flokkar' ),
	'ChangeEmail'               => array( 'Breyta_netfangi' ),
	'ChangePassword'            => array( 'Endurkalla_aðgangsorðið' ),
	'ComparePages'              => array( 'Bera_saman_síður' ),
	'Confirmemail'              => array( 'Staðfesta_netfang' ),
	'Contributions'             => array( 'Framlög' ),
	'CreateAccount'             => array( 'Búa_til_aðgang' ),
	'Deadendpages'              => array( 'Botnlangar' ),
	'DeletedContributions'      => array( 'Eydd_framlög' ),
	'Disambiguations'           => array( 'Tenglar_í_aðgreiningarsíður' ),
	'DoubleRedirects'           => array( 'Tvöfaldar_tilvísanir' ),
	'EditWatchlist'             => array( 'Breyta_vaktlista' ),
	'Emailuser'                 => array( 'Senda_tölvupóst' ),
	'Export'                    => array( 'Flytja_út' ),
	'Fewestrevisions'           => array( 'Fæstar_útgáfur' ),
	'FileDuplicateSearch'       => array( 'Afritunarskráarleit' ),
	'Filepath'                  => array( 'Skráarslóð' ),
	'Import'                    => array( 'Flytja_inn' ),
	'Invalidateemail'           => array( 'Rangt_netfang' ),
	'BlockList'                 => array( 'Bönnuð_vistföng' ),
	'Listadmins'                => array( 'Stjórnendalisti' ),
	'Listbots'                  => array( 'Vélmennalisti' ),
	'Listfiles'                 => array( 'Myndalisti' ),
	'Listgrouprights'           => array( 'Réttindalisti' ),
	'Listredirects'             => array( 'Tilvísanalisti' ),
	'Listusers'                 => array( 'Notendalisti' ),
	'Lockdb'                    => array( 'Læsa_gagnagrunni' ),
	'Log'                       => array( 'Aðgerðaskrár' ),
	'Lonelypages'               => array( 'Munaðarlausar_síður' ),
	'Longpages'                 => array( 'Langar_síður' ),
	'MergeHistory'              => array( 'Sameina_breytingaskrá' ),
	'MIMEsearch'                => array( 'MIME-leit' ),
	'Mostcategories'            => array( 'Flestir_flokkar' ),
	'Mostimages'                => array( 'Flestar_myndir' ),
	'Mostlinked'                => array( 'Mest_ítengt' ),
	'Mostlinkedcategories'      => array( 'Mest_ítengdu_flokkar' ),
	'Mostlinkedtemplates'       => array( 'Mest_ítengdu_snið' ),
	'Mostrevisions'             => array( 'Flestar_útgáfur' ),
	'Movepage'                  => array( 'Færa_síðu' ),
	'Mycontributions'           => array( 'Framlög_mín' ),
	'Mypage'                    => array( 'Notandasíða_mín' ),
	'Mytalk'                    => array( 'Spjallasíða_mín' ),
	'Myuploads'                 => array( 'Upplöðin_mín' ),
	'Newimages'                 => array( 'Nýjar_myndir' ),
	'Newpages'                  => array( 'Nýjustu_greinar' ),
	'PasswordReset'             => array( 'Endursetja_lykilorð' ),
	'Popularpages'              => array( 'Vinsælar_síður' ),
	'Preferences'               => array( 'Stillingar' ),
	'Prefixindex'               => array( 'Forskeyti' ),
	'Protectedpages'            => array( 'Verndaðar_síður' ),
	'Protectedtitles'           => array( 'Verndaðir_titlar' ),
	'Randompage'                => array( 'Handahófsvalin_síða' ),
	'Randomredirect'            => array( 'Handahófsvalin_tilvísun' ),
	'Recentchanges'             => array( 'Nýlegar_breytingar' ),
	'Recentchangeslinked'       => array( 'Nýlegar_breytingar_tengdar' ),
	'Revisiondelete'            => array( 'Eyðingarendurskoðun' ),
	'Search'                    => array( 'Leit' ),
	'Shortpages'                => array( 'Stuttar_síður' ),
	'Specialpages'              => array( 'Kerfissíður' ),
	'Statistics'                => array( 'Tölfræði' ),
	'Tags'                      => array( 'Tög' ),
	'Unblock'                   => array( 'Afbönnun' ),
	'Uncategorizedcategories'   => array( 'Óflokkaðir_flokkar' ),
	'Uncategorizedimages'       => array( 'Óflokkaðar_myndir' ),
	'Uncategorizedpages'        => array( 'Óflokkaðar_síður' ),
	'Uncategorizedtemplates'    => array( 'Óflokkuð_snið' ),
	'Undelete'                  => array( 'Endurvekja_eydda_síðu' ),
	'Unlockdb'                  => array( 'Opna_gagnagrunn' ),
	'Unusedcategories'          => array( 'Ónotaðir_flokkar' ),
	'Unusedimages'              => array( 'Munaðarlausar_myndir' ),
	'Unusedtemplates'           => array( 'Ónotuð_snið' ),
	'Unwatchedpages'            => array( 'Óvaktaðar_síður' ),
	'Upload'                    => array( 'Hlaða_inn' ),
	'Userlogin'                 => array( 'Innskrá' ),
	'Userlogout'                => array( 'Útskrá' ),
	'Userrights'                => array( 'Notandaréttindi' ),
	'Version'                   => array( 'Útgáfa' ),
	'Wantedcategories'          => array( 'Eftirsóttir_flokkar' ),
	'Wantedfiles'               => array( 'Eftirsóttar_skrár' ),
	'Wantedpages'               => array( 'Eftirsóttar_síður' ),
	'Wantedtemplates'           => array( 'Eftirsótt_snið' ),
	'Watchlist'                 => array( 'Vaktlistinn' ),
	'Whatlinkshere'             => array( 'Síður_sem_tengjast_hingað' ),
	'Withoutinterwiki'          => array( 'Síður_án_tungumálatengla' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkPrefixExtension = true;
$linkTrail = '/^([áðéíóúýþæöa-z-–]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline' => 'Undirstrika tengla:',
'tog-justify' => 'Jafna málsgreinar',
'tog-hideminor' => 'Fela minniháttar breytingar í nýlegum breytingum',
'tog-hidepatrolled' => 'Fela yfirfarnar breytingar í nýlegum breytingum',
'tog-newpageshidepatrolled' => 'Fela yfirfarnar breytingar í listanum yfir nýjar síður',
'tog-extendwatchlist' => 'Sýna allar breytingar á vaktlistanum, ekki einungis þær nýjustu',
'tog-usenewrc' => 'Flokka breytingar eftir síðu í nýlegum breytingum og vaktlista (þarfnast JavaScript)',
'tog-numberheadings' => 'Númera fyrirsagnir sjálfkrafa',
'tog-showtoolbar' => 'Sýna breytingarverkfærastiku (JavaScript)',
'tog-editondblclick' => 'Breyta síðum þegar tvísmellt er (JavaScript)',
'tog-editsection' => 'Virkja hlutabreytingu með [breyta] tenglum',
'tog-editsectiononrightclick' => 'Virkja hlutabreytingu með því að hægrismella á hlutafyrirsagnir (JavaScript)',
'tog-showtoc' => 'Sýna efnisyfirlit (fyrir síður með meira en 3 fyrirsagnir)',
'tog-rememberpassword' => 'Muna innskráninguna mína í þessum vafra (í allt að $1 {{PLURAL:$1|dag|daga}})',
'tog-watchcreations' => 'Bæta síðum sem ég bý til og skrám sem ég hleð inn á vaktlistann minn',
'tog-watchdefault' => 'Bæta síðum og skrám sem ég breyti á vaktlistann minn',
'tog-watchmoves' => 'Bæta síðum og skrám sem ég færi á vaktlistann minn',
'tog-watchdeletion' => 'Bæta síðum og skrám sem ég eyði á vaktlistann minn',
'tog-minordefault' => 'Merkja allar breytingar sem minniháttar sjálfgefið',
'tog-previewontop' => 'Sýna forskoðun á undan breytingarkassanum',
'tog-previewonfirst' => 'Sýna forskoðun með fyrstu breytingu',
'tog-nocache' => 'Slökkva á flýtiminni vafrans',
'tog-enotifwatchlistpages' => 'Senda mér tölvupóst þegar síðu eða skrá á vaktlistanum mínu er breytt',
'tog-enotifusertalkpages' => 'Senda mér tölvupóst þegar notandaspjallinu mínu er breytt',
'tog-enotifminoredits' => 'Senda mér einnig tölvupóst vegna minniháttar breytinga á síðum og skrám',
'tog-enotifrevealaddr' => 'Gefa upp netfang mitt í tilkynningarpóstum',
'tog-shownumberswatching' => 'Sýna fjölda vaktandi notenda',
'tog-oldsig' => 'Núverandi undirskrift:',
'tog-fancysig' => 'Meðhöndla undirskrift sem wikitexti (án sjálfvirks tengils)',
'tog-externaleditor' => 'Nota utanaðkomandi ritil sjálfgefið (eingöngu fyrir reynda, þarfnast sérstakra stillinga á tölvunni þinni)',
'tog-externaldiff' => 'Nota utanaðkomandi mismun sjálfgefið (eingöngu fyrir reynda, þarfnast sérstakra stillinga á tölvunni þinni)',
'tog-showjumplinks' => 'Virkja „stökkva á“ aðgengitengla',
'tog-uselivepreview' => 'Nota beina forskoðun (JavaScript) (Á tilraunastigi)',
'tog-forceeditsummary' => 'Birta áminningu þegar breytingarágripið er tómt',
'tog-watchlisthideown' => 'Ekki sýna mínar breytingar á vaktlistanum',
'tog-watchlisthidebots' => 'Ekki sýna breytingar vélmenna á vaktlistanum',
'tog-watchlisthideminor' => 'Ekki sýna minniháttar breytingar á vaktlistanum',
'tog-watchlisthideliu' => 'Ekki sýna breytingar innskráðra notenda á vaktlistanum',
'tog-watchlisthideanons' => 'Ekki sýna breytingar óþekktra notenda á vaktlistanum',
'tog-watchlisthidepatrolled' => 'Fela yfirfarnar breytingar í vaktlistanum',
'tog-ccmeonemails' => 'Senda mér afrit af tölvupóstum sem ég sendi öðrum notendum',
'tog-diffonly' => 'Ekki sýna síðuefni undir mismunum',
'tog-showhiddencats' => 'Sýna falda flokka',
'tog-norollbackdiff' => 'Sleppa breytingu eftir að endurvakning síðu hefur verið gerð.',

'underline-always' => 'Alltaf',
'underline-never' => 'Aldrei',
'underline-default' => 'Fletta eða vafra sjálfkrafa',

# Font style option in Special:Preferences
'editfont-style' => 'Breyta leturgerð í textareitum',
'editfont-default' => 'skv. vafrastillingu',
'editfont-monospace' => 'Monospaced letur',
'editfont-sansserif' => 'Sans-serif font',
'editfont-serif' => 'Serif letur',

# Dates
'sunday' => 'sunnudagur',
'monday' => 'mánudagur',
'tuesday' => 'þriðjudagur',
'wednesday' => 'miðvikudagur',
'thursday' => 'fimmtudagur',
'friday' => 'föstudagur',
'saturday' => 'laugardagur',
'sun' => 'sun',
'mon' => 'mán',
'tue' => 'þri',
'wed' => 'mið',
'thu' => 'fim',
'fri' => 'fös',
'sat' => 'lau',
'january' => 'janúar',
'february' => 'febrúar',
'march' => 'mars',
'april' => 'apríl',
'may_long' => 'maí',
'june' => 'júní',
'july' => 'júlí',
'august' => 'ágúst',
'september' => 'september',
'october' => 'október',
'november' => 'nóvember',
'december' => 'desember',
'january-gen' => 'janúar',
'february-gen' => 'febrúar',
'march-gen' => 'mars',
'april-gen' => 'apríl',
'may-gen' => 'maí',
'june-gen' => 'júní',
'july-gen' => 'júlí',
'august-gen' => 'ágúst',
'september-gen' => 'september',
'october-gen' => 'október',
'november-gen' => 'nóvember',
'december-gen' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'maí',
'jun' => 'jún',
'jul' => 'júl',
'aug' => 'ágú',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nóv',
'dec' => 'des',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Flokkur|Flokkar}}',
'category_header' => 'Síður í flokknum „$1“',
'subcategories' => 'Undirflokkar',
'category-media-header' => 'Margmiðlunarefni í flokknum „$1“',
'category-empty' => "''Þessi flokkur inniheldur engar síður eða margmiðlunarefni.''",
'hidden-categories' => '{{PLURAL:$1|Falinn flokkur|Faldir flokkar}}',
'hidden-category-category' => 'Faldir flokkar',
'category-subcat-count' => '{{PLURAL:$2|Þessi flokkur hefur einungis eftirfarandi undirflokk.|Þessi flokkur hefur eftirfarandi {{PLURAL:$1|undirflokk|$1 undirflokka}}, af alls $2.}}',
'category-subcat-count-limited' => 'Þessi flokkur hefur eftirfarandi {{PLURAL:$1|undirflokk|$1 undirflokka}}.',
'category-article-count' => '{{PLURAL:$2|Þessi flokkur inniheldur aðeins eftirfarandi síðu.|Eftirfarandi {{PLURAL:$1|síða er|síður eru}} í þessum flokki, af alls $1.}}',
'category-article-count-limited' => 'Eftirfarndi {{PLURAL:$1|síða er|$1 síður eru}} í þessum flokki.',
'category-file-count' => '{{PLURAL:$2|Þessi flokkur inniheldur einungis eftirfarandi skrá.|Eftirfarandi {{PLURAL:$1|skrá er|$1 skrár eru}} í þessum flokki, af alls $2.}}',
'category-file-count-limited' => 'Eftirfarandi {{PLURAL:$1|skrá er|$1 skrár eru}} í þessum flokki.',
'listingcontinuesabbrev' => 'frh.',
'index-category' => 'Raðaðar skrár',
'noindex-category' => 'Óraðaðar skrár',
'broken-file-category' => 'Síður með brotna myndatengla',

'linkprefix' => '/^(.*?)([áÁðÐéÉíÍóÓúÚýÝþÞæÆöÖA-Za-z-–]+)$/sDu',

'about' => 'Um',
'article' => 'Efnissíða',
'newwindow' => '(opnast í nýjum glugga)',
'cancel' => 'Hætta við',
'moredotdotdot' => 'Meira...',
'mypage' => 'Síða',
'mytalk' => 'Spjall',
'anontalk' => 'Spjallsíða þessa vistfangs.',
'navigation' => 'Flakk',
'and' => '&#32;og',

# Cologne Blue skin
'qbfind' => 'Finna',
'qbbrowse' => 'Flakka',
'qbedit' => 'Breyta',
'qbpageoptions' => 'Þessi síða',
'qbpageinfo' => 'Samhengi',
'qbmyoptions' => 'Mínar síður',
'qbspecialpages' => 'Kerfissíður',
'faq' => 'Algengar spurningar',
'faqpage' => 'Project:Algengar spurningar',

# Vector skin
'vector-action-addsection' => 'Bæta við umræðu',
'vector-action-delete' => 'Eyða',
'vector-action-move' => 'Færa',
'vector-action-protect' => 'Vernda',
'vector-action-undelete' => 'Hætta við eyðingu',
'vector-action-unprotect' => 'Breyta verndunarstigi',
'vector-simplesearch-preference' => 'Virkja einfaldaða leitarstiku (Vector-þemað eingöngu)',
'vector-view-create' => 'Skapa',
'vector-view-edit' => 'Breyta',
'vector-view-history' => 'Breytingaskrá',
'vector-view-view' => 'Lesa',
'vector-view-viewsource' => 'Sýna frumkóða',
'actions' => 'Aðgerðir',
'namespaces' => 'Nafnrými',
'variants' => 'Útgáfur',

'errorpagetitle' => 'Villa',
'returnto' => 'Aftur á: $1.',
'tagline' => 'Úr {{SITENAME}}',
'help' => 'Hjálp',
'search' => 'Leit',
'searchbutton' => 'Leita',
'go' => 'Áfram',
'searcharticle' => 'Áfram',
'history' => 'Breytingaskrá',
'history_short' => 'Breytingaskrá',
'updatedmarker' => 'uppfært frá síðustu heimsókn minni',
'printableversion' => 'Prentvæn útgáfa',
'permalink' => 'Varanlegur tengill',
'print' => 'Prenta',
'view' => 'Skoða',
'edit' => 'Breyta',
'create' => 'Skapa',
'editthispage' => 'Breyta þessari síðu',
'create-this-page' => 'Skapa þessari síðu',
'delete' => 'Eyða',
'deletethispage' => 'Eyða þessari síðu',
'undelete_short' => 'Endurvekja {{PLURAL:$1|eina breytingu|$1 breytingar}}',
'viewdeleted_short' => 'Skoða {{PLURAL:$1|eina eydda breytingu|$1 eyddar breytingar}}',
'protect' => 'Vernda',
'protect_change' => 'breyta',
'protectthispage' => 'Vernda þessa síðu',
'unprotect' => 'Afvernda',
'unprotectthispage' => 'Afvernda þessa síðu',
'newpage' => 'Ný síða',
'talkpage' => 'Ræða um þessa síðu',
'talkpagelinktext' => 'Spjall',
'specialpage' => 'Kerfissíða',
'personaltools' => 'Tenglar',
'postcomment' => 'Nýr hluti',
'articlepage' => 'Sýna núverandi síðu',
'talk' => 'Spjall',
'views' => 'Sýn',
'toolbox' => 'Verkfæri',
'userpage' => 'Skoða notandasíðu',
'projectpage' => 'Skoða verkefnissíðu',
'imagepage' => 'Skoða skráarsíðu',
'mediawikipage' => 'Skoða skilaboðasíðu',
'templatepage' => 'Skoða sniðasíðu',
'viewhelppage' => 'Skoða hjálparsíðu',
'categorypage' => 'Skoða flokkatré',
'viewtalkpage' => 'Skoða umræðu',
'otherlanguages' => 'Á öðrum tungumálum',
'redirectedfrom' => '(Tilvísað frá $1)',
'redirectpagesub' => 'Tilvísunarsíða',
'lastmodifiedat' => 'Þessari síðu var síðast breytt $1 klukkan $2.',
'viewcount' => 'Þessi síða hefur verið skoðuð {{PLURAL:$1|einu sinni|$1 sinnum}}.',
'protectedpage' => 'Vernduð síða',
'jumpto' => 'Stökkva á:',
'jumptonavigation' => 'flakk',
'jumptosearch' => 'leita',
'view-pool-error' => 'Því miður eru vefþjónarnir yfirhlaðnir í augnablikinu.
Of margir notendur eru að reyna að skoða þessa síðu. 
Vinsamlegast bíddu í smástund áður en þú reynir að sækja þessa síðu aftur.

$1',
'pool-timeout' => 'Of löng bið efttir lás',
'pool-queuefull' => 'Vefþjónninn er yfirhlaðinn í augnablikinu.',
'pool-errorunknown' => 'Óþekkt villa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Um {{SITENAME}}',
'aboutpage' => 'Project:Um verkefnið',
'copyright' => 'Efni má nota samkvæmt $1.',
'copyrightpage' => '{{ns:project}}:Höfundarréttur',
'currentevents' => 'Potturinn',
'currentevents-url' => 'Project:Potturinn',
'disclaimers' => 'Fyrirvarar',
'disclaimerpage' => 'Project:Almennur fyrirvari',
'edithelp' => 'Breytingarhjálp',
'edithelppage' => 'Help:Breyta',
'helppage' => 'Help:Efnisyfirlit',
'mainpage' => 'Forsíða',
'mainpage-description' => 'Forsíða',
'policy-url' => 'Project:Samþykktir',
'portal' => 'Samfélagsgátt',
'portal-url' => 'Project:Samfélagsgátt',
'privacy' => 'Meðferð persónuupplýsinga',
'privacypage' => 'Project:Meðferð persónuupplýsinga',

'badaccess' => 'Aðgangsvilla',
'badaccess-group0' => 'Þú hefur ekki leyfi til að framkvæma þá aðgerð sem þú baðst um.',
'badaccess-groups' => 'Aðgerðin sem þú reyndir að framkvæma er takmörkuð notendum í {{PLURAL:$2|hópnum|einum af hópunum}}: $1.',

'versionrequired' => 'Þarfnast úgáfu $1 af MediaWiki',
'versionrequiredtext' => 'Útgáfa $1 af MediaWiki er þörf til að geta skoðað þessa síðu.
Sjá [[Special:Version|útgáfusíðuna]].',

'ok' => 'Í lagi',
'retrievedfrom' => 'Sótt frá „$1“',
'youhavenewmessages' => 'Þú hefur fengið $1 ($2).',
'newmessageslink' => 'ný skilaboð',
'newmessagesdifflink' => 'síðasta breyting',
'youhavenewmessagesfromusers' => 'Þú hefur $1 frá {{PLURAL:$3|öðrum notanda|$3 notendum}} ($2)',
'youhavenewmessagesmanyusers' => 'Þú hefur $1 frá mörgum notendum ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|ein|}} ný skilaboð',
'newmessagesdifflinkplural' => '{{PLURAL:$1|síðasta breyting|síðustu breytingar}} spjallsíðunnar',
'youhavenewmessagesmulti' => 'Þín bíða ný skilaboð á $1',
'editsection' => 'breyta',
'editold' => 'breyta',
'viewsourceold' => 'skoða efni',
'editlink' => 'breyta',
'viewsourcelink' => 'skoða efni',
'editsectionhint' => 'Breyti hluta: $1',
'toc' => 'Efnisyfirlit',
'showtoc' => 'sýna',
'hidetoc' => 'fela',
'collapsible-collapse' => 'Fela',
'collapsible-expand' => 'Sýna',
'thisisdeleted' => 'Endurvekja eða skoða $1?',
'viewdeleted' => 'Skoða $1?',
'restorelink' => '{{PLURAL:$1|eina eydda breytingu|$1 eyddar breytingar}}',
'feedlinks' => 'Streymi:',
'feed-invalid' => 'Röng tegund áskriftarstreymis.',
'feed-unavailable' => 'Samræmisstreymi eru ekki fáanlegt',
'site-rss-feed' => '$1 RSS-streymi',
'site-atom-feed' => '$1 Atom-streymi',
'page-rss-feed' => '„$1“ RSS-streymi',
'page-atom-feed' => '„$1“ Atom-streymi',
'red-link-title' => '$1 (síða er ekki enn til)',
'sort-descending' => 'Raða í lækkandi röð',
'sort-ascending' => 'Raða í hækkandi röð',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Síða',
'nstab-user' => 'Notandi',
'nstab-media' => 'Margmiðlunarsíða',
'nstab-special' => 'Kerfissíða',
'nstab-project' => 'Um',
'nstab-image' => 'Skrá',
'nstab-mediawiki' => 'Melding',
'nstab-template' => 'Snið',
'nstab-help' => 'Hjálp',
'nstab-category' => 'Flokkur',

# Main script and global functions
'nosuchaction' => 'Aðgerð ekki til',
'nosuchactiontext' => 'Aðgerðin sem veffangið tilgreinir þekkir er ekki þekkt af wiki
Þú gætir haft slegið inn vefslóðina vitlaust eða fylgt eftir röngum tengli.
Þetta gæti einnig verið villa í hugbúnaðinum sem er notuð á {{SITENAME}}.',
'nosuchspecialpage' => 'Kerfissíðan er ekki til',
'nospecialpagetext' => 'Þú hefur beðið um kerfissíðu sem ekki er til. Listi yfir gildar kerfissíður er að finna á [[Special:SpecialPages|kerfissíður]].',

# General errors
'error' => 'Villa',
'databaseerror' => 'Gagnagrunnsvilla',
'dberrortext' => 'Málfræðivilla kom upp í gangagrnunsfyrirspurninni.
Þetta gæti verið vegna villu í hugbúnaðinum.
Síðasta gagnagrunnsfyrirspurnin var:
<blockquote><code>$1</code></blockquote>
úr aðgerðinni: „<code>$2</code>".
MySQL skilar villuboðunum „<samp>$3: $4</samp>".',
'dberrortextcl' => 'Málfræðivilla kom upp í gangagrnunsfyrirspurninni.
Síðasta gagnagrunnsfyrirspurnin var:
„$1“
úr aðgerðinni: „$2“.
MySQL skilar villuboðanum „$3: $4“',
'laggedslavemode' => 'Viðvörun: Síðan inniheldur ekki nýjustu uppfærslur.',
'readonly' => 'Gagnagrunnur læstur',
'enterlockreason' => 'Gefðu fram ástæðu fyrir læsingunni, og einnig áætlun
un hvenær læsingunni verðu aflétt',
'readonlytext' => 'Læst hefur verið fyrir gerð nýrra síða og breytinga í gagnagrunninum, líklega vegna viðhalds, en eftir það mun hann starfa eðlilega.

Kerfisstjórinn sem læsti honum gaf þessa skýringu: $1',
'missing-article' => 'Gagnagrunnurinn fann ekki texta síðu sem að hann hefði átt að finna, undir nafninu „$1“ $2.

Þetta orsakast oftast þegar úreltum mismunar- eða breytingaskráartengli er fylgt að síðu sem hefur verið eytt.

Ef þetta er ekki raunin, kann að vera að þú hafir rekist á villu í hugbúnaðinum.
Gjörðu svo vel og tilkynntu atvikið til [[Special:ListUsers/sysop|stjórnanda]], og gerðu grein fyrir vefslóðinni.',
'missingarticle-rev' => '(breyting#: $1)',
'missingarticle-diff' => '(Munur: $1, $2)',
'readonly_lag' => 'Gagnagrunninum hefur verið læst sjálfkrafa á meðan undirvefþjónarnir reyna að hafa í við aðalvefþjóninn',
'internalerror' => 'Kerfisvilla',
'internalerror_info' => 'Innri villa: $1',
'fileappenderrorread' => 'Mistókst að lesa "$1" á meðan skeytt var við síðuna.',
'fileappenderror' => 'Gat ekki bætt „$1“ við „$2“.',
'filecopyerror' => 'Mistókst að afrita skjal "$1" á "$2".',
'filerenameerror' => 'Gat ekki endurnefnt skrána „$1“ í „$2“.',
'filedeleteerror' => 'Gat ekki eytt skránni „$1“.',
'directorycreateerror' => 'Gat ekki búið til efnisskrána "$1".',
'filenotfound' => 'Gat ekki fundið skrána „$1“.',
'fileexistserror' => 'Ekki var hægt að skrifa í "$1" skjalið: það er nú þegar til',
'unexpected' => 'Óvænt gildi: „$1“=„$2“.',
'formerror' => 'Villa: gat ekki sent eyðublað',
'badarticleerror' => 'Þetta er ekki hægt að framkvæma á síðunni.',
'cannotdelete' => 'Ekki var hægt að eyða síðunni "$1".
Líklegt er að einhver annar hafi gert það.',
'cannotdelete-title' => 'Gat ekki eytt síðunni $1',
'delete-hook-aborted' => 'Eyðing síðu stöðvuð af viðbótarkrók (extension hook).
Engin skýring gefin.',
'badtitle' => 'Slæmur titill',
'badtitletext' => 'Umbeðin síðutitill er ógildur.',
'perfcached' => 'Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar. Allt að {{PLURAL:$1|ein niðurstaða er aðgengileg|$1 niðurstöður eru aðgengilegar}} í skyndiminninu.',
'perfcachedts' => 'Eftirfarandi gögn eru í skyndiminninu, og voru síðast uppfærð $1. Allt að {{PLURAL:$4|ein niðurstaða er aðgengileg|$4 niðurstöður eru aðgengilegar}} í skyndiminninu.',
'querypage-no-updates' => 'Lokað er fyrir uppfærslur af þessari síðu. Gögn sett hér munu ekki vistast.',
'wrong_wfQuery_params' => 'Röng færibreyta fyrir wfQuery()<br />
Virkni: $1<br />
Spurn: $2',
'viewsource' => 'Skoða efni',
'viewsource-title' => 'Skoða efni $1',
'actionthrottled' => 'Aðgerðin kafnaði',
'actionthrottledtext' => 'Til þess að verjast ruslpósti, er ekki hægt að framkvæma þessa aðgerð of oft, og þú hefur farið fram yfir þau takmörk. Gjörðu svo vel og reyndu aftur eftir nokkrar mínútur.',
'protectedpagetext' => 'Þessari síðu hefur verið læst til að koma í veg fyrir breytingar eða aðrar aðgerðir.',
'viewsourcetext' => 'Þú getur skoðað og afritað kóða þessarar síðu:',
'viewyourtext' => "Þú getur skoðað og afritað kóða '''breytinganna þinna''' yfir á þessa síðu:",
'protectedinterface' => 'Þessi síða útvegar textann sem birtist í viðmóti hugbúnaðarins sem keyrir þessa síðu, og er læst til að koma í veg fyrir misnotkun.
Til þess að bæta við eða breyta þýðingum fyrir öll wiki verkefni, vinsamlegast notaðu [//translatewiki.net/ translatewiki.net], staðfæringaverkefni MediaWiki',
'editinginterface' => "'''Aðvörun:''' Þú ert að breyta síðu sem hefur að geyma texta fyrir notendaumhverfi hugbúnaðarins.
Breytingar á þessari síðu munu hafa áhrif á notendaumhverfi annarra notenda á þessu vefsvæði.
Til þess að bæta við eða breyta þýðingum fyrir öll wiki verkefni, vinsamlegast notaðu [//translatewiki.net/wiki/Main_Page?setlang=is translatewiki.net], staðfæringaverkefni MediaWiki.",
'sqlhidden' => '(SQL-fyrirspurn falin)',
'cascadeprotected' => 'Þessi síða hefur verið vernduð fyrir breytingum, vegna þess að hún er innifalin í eftirfarandi {{PLURAL:$1|síðu, sem er vernduð|síðum, sem eru verndaðar}} með „keðjuverndun“:
$2',
'namespaceprotected' => "Þú hefur ekki leyfi til að breyta síðum í '''$1''' nafnrýminu.",
'customcssprotected' => 'Þú hefur ekki leyfi að breyta þessari CSS-umbrotsíðu, því hún hefur notendastillingar annars notanda.',
'customjsprotected' => 'Þú hefur ekki leyfi til að breyta þessari JavaScript síðu, því hún hefur notendastillingar annars notanda.',
'ns-specialprotected' => 'Kerfissíðum er ekki hægt að breyta.',
'titleprotected' => "Þessi titill hefur verið verndaður fyrir sköpun af [[User:$1|$1]].
Ástæðan sem gefin var ''$2''.",
'filereadonlyerror' => 'Ekki var hægt að breyta skránni "$1" því skráin í skráarsafninu "$2" er engöngu hægt að lesa.

Möppudýrið sem læsti skránni gaf þessa ástæðu: "\'\'$3\'\'".',
'invalidtitle-knownnamespace' => 'Ógildur titill í nafnrými "$2" og með textann "$3"',
'invalidtitle-unknownnamespace' => 'Ógildur titill með óþekkt nafnrými númer $1 og texta "$2"',
'exception-nologin' => 'Óinnskráð(ur)',
'exception-nologin-text' => 'Þessi síða eða aðgerð krefst þess að þú sért skráður inn á þessum wiki.',

# Virus scanner
'virus-badscanner' => "Slæm stilling: óþekktur veiruskannari: ''$1''",
'virus-scanfailed' => 'skönnun mistókst (kóði $1)',
'virus-unknownscanner' => 'óþekkt mótveira:',

# Login and logout pages
'logouttext' => "'''Þú hefur verið skráð(ur) út.'''

Þú getur haldið áfram að nota {{SITENAME}} óþekkt(ur), eða þú getur [[Special:UserLogin|skráð þig inn aftur]] sem sami eða annar notandi.
Athugaðu að sumar síður kunna að birtast líkt og þú sért ennþá skráð(ur) inn, þangað til að þú hreinsar skyndiminnið í vafranum þínum.",
'welcomecreation' => '== Velkomin(n), $1! ==
Aðgangurinn þinn hefur verið búinn til.
Ekki gleyma að breyta [[Special:Preferences|{{SITENAME}}-stillingunum]] þínum.',
'yourname' => 'Notandanafn:',
'yourpassword' => 'Lykilorð:',
'yourpasswordagain' => 'Endurrita lykilorð:',
'remembermypassword' => 'Muna innskráninguna mína í þessum vafra (í allt að $1 {{PLURAL:$1|dag|daga}})',
'securelogin-stick-https' => 'Halda öllum samskiptum áfram yfir HTTPS eftir að þú skráir þig inn',
'yourdomainname' => 'Þitt lén:',
'password-change-forbidden' => 'Þú getur ekki breytt lykilorðum á þessum wiki.',
'externaldberror' => 'Uppfærsla mistókst. Annaðhvort varð villa í gagnasafninu eða að þér sé óheimilt að uppfæra aðra aðganga.',
'login' => 'Innskrá',
'nav-login-createaccount' => 'Innskrá / Búa til aðgang',
'loginprompt' => 'Þú verður að leyfa vefkökur til þess að geta skráð þig inn á {{SITENAME}}.',
'userlogin' => 'Innskrá / Búa til aðgang',
'userloginnocreate' => 'Innskrá',
'logout' => 'Útskráning',
'userlogout' => 'Útskrá',
'notloggedin' => 'Ekki innskráð(ur)',
'nologin' => "Ekki með aðgang? '''$1'''.",
'nologinlink' => 'Stofnaðu aðgang',
'createaccount' => 'Nýskrá',
'gotaccount' => "Nú þegar með notandanafn? '''$1'''.",
'gotaccountlink' => 'Skráðu þig inn',
'userlogin-resetlink' => 'Gleymdir þú notendaupplýsingunum þínum?',
'createaccountmail' => 'með tölvupósti',
'createaccountreason' => 'Ástæða:',
'badretype' => 'Lykilorðin sem þú skrifaðir eru ekki eins.',
'userexists' => 'Þetta notandanafn er þegar í notkun.
Vinsamlegast veldu þér annað.',
'loginerror' => 'Innskráningarvilla',
'createaccounterror' => 'Gat ekki búið til notanda: $1',
'nocookiesnew' => 'Innskráningin var búin til, en þú ert ekki skráð(ur) inn.
{{SITENAME}} notar vefkökur til að skrá inn notendur.
Þú hefur lokað fyrir vefkökur.
Gjörðu svo vel og opnaðu fyrir þær, skráðu þig svo inn með notandanafni og lykilorði.',
'nocookieslogin' => '{{SITENAME}} notar vefkökur til innskráningar. Vafrinn þinn er ekki að taka á móti þeim sem gerir það ókleyft að innskrá þig. Vinsamlegast virkjaðu móttöku kakna í vafranum þínum til að geta skráð þig inn.',
'nocookiesfornew' => 'Notenda aðgangurinn var ekki stofnaður, því ekki fannst uppruni beiðnarinnar.
Gakktu úr skugga um að vefkökur séu virkar, endurhladdu þessari síðu og reyndu aftur.',
'noname' => 'Þú hefur ekki tilgreint gilt notandanafn.',
'loginsuccesstitle' => 'Innskráning tókst',
'loginsuccess' => "'''Þú ert nú innskráð(ur) á {{SITENAME}} sem „$1“.'''",
'nosuchuser' => 'Það er enginn notandi með þetta nafn: "$1".
Gerður er greinarmunur á há- og lágstöfum.
Athugaðu hvort um innsláttavillu er að ræða eða [[Special:UserLogin/signup|búðu til nýtt notendanafn]].',
'nosuchusershort' => 'Það er enginn notandi með nafnið „$1“. Athugaðu hvort nafnið sé ritað rétt.',
'nouserspecified' => 'Þú verður að taka fram notandanafn.',
'login-userblocked' => 'Þessi notandi hefur verið settur í bann.  Innskráning ekki leyfð.',
'wrongpassword' => 'Uppgefið lykilorð er rangt. Vinsamlegast reyndu aftur.',
'wrongpasswordempty' => 'Lykilorðsreiturinn var auður. Vinsamlegast reyndu aftur.',
'passwordtooshort' => 'Lykilorð skal vera að minnsta kosti {{plural: $1 |einn stafur|$1 stafir}}.',
'password-name-match' => 'Þarf að lykilorð þitt sé öðruvísi notandanafni þínu',
'password-login-forbidden' => 'Notkun þessa notendanafns og lykilorðs er ekki leyfileg.',
'mailmypassword' => 'Senda nýtt lykilorð með tölvupósti',
'passwordremindertitle' => 'Nýtt tímabundið aðgangsorð fyrir {{SITENAME}}',
'passwordremindertext' => 'Einhver (líklegast þú, á vistfanginu $1) hefur beðið um að fá nýtt
lykilorð fyrir {{SITENAME}} ($4). Tímabundið lykilorð fyrir notandann „$2“
hefur verið búið til og er núna „$3“. Ef þetta er það sem þú vildir, þarfu að skrá
þig inn og velja nýtt lykilorð.  Þetta tímabundna lykilorð rennur út eftir {{PLURAL:$5|einn dag|$5 daga}}.

Ef það var ekki þú sem fórst fram á þetta, eða ef þú mannst lykilorðið þitt,
og vilt ekki lengur breyta því, skaltu hunsa þetta skilaboð og
halda áfram að nota gamla lykilorðið.',
'noemail' => 'Það er ekkert netfang skráð fyrir notandan "$1".',
'noemailcreate' => 'Þú verður að skrá gilt netfang',
'passwordsent' => 'Nýtt lykilorð var sent á netfangið sem er skráð á „$1“.
Vinsamlegast skráðu þig inn á ný þegar þú hefur móttekið það.',
'blocked-mailpassword' => 'Þér er ekki heimilt að gera breytingar frá þessu netfangi og  því getur þú ekki fengið nýtt lykilorð í pósti.  Þetta er gert til þess að koma í veg fyrir skemmdarverk.',
'eauthentsent' => 'Staðfestingarpóstur hefur verið sendur á uppgefið netfang. Þú verður að fylgja leiðbeiningunum í póstinum til þess að virkja netfangið og staðfesta að það sé örugglega þitt.',
'throttled-mailpassword' => 'Áminning fyrir lykilorð hefur nú þegar verið send, innan við {{PLURAL:$1|síðasta klukkutímans|$1 síðustu klukkutímanna}}.
Til að koma í veg fyrir misnotkun, er aðeins ein áminning send {{PLURAL:$1|hvern klukkutíma|hverja $1 klukkutíma}}.',
'mailerror' => 'Upp kom villa við sendingu tölvupósts: $1',
'acct_creation_throttle_hit' => 'Því miður, hafa verið búnir til {{PLURAL:$1|1 aðgang|$1 aðganga}} nýr aðgangar í dag sem er hámarksfjöldi nýskráninga á einum degi.
Þú getur því miður ekki búið til nýjan aðgang frá þessari IP-tölu að svo stöddu.',
'emailauthenticated' => 'Netfang þitt var staðfest þann $2 klukkan $3.',
'emailnotauthenticated' => 'Veffang þitt hefur ekki enn verið sannreynt. Enginn póstur verður sendur af neinum af eftirfarandi eiginleikum.',
'noemailprefs' => 'Tilgreindu netfang svo þessar aðgerðir virki.',
'emailconfirmlink' => 'Staðfesta netfang þitt',
'invalidemailaddress' => 'Ekki er hægt að taka við netfangi þínu þar sem að það er á ógildu formi.
Gjörðu svo vel og settu inn netfang á gildu formi eða tæmdu reitinn.',
'cannotchangeemail' => 'Ekki er hægt að breyta netföngum notenda á þessum wiki',
'emaildisabled' => 'Þessi síða getur ekki sent tölvupóst.',
'accountcreated' => 'Aðgangur búinn til',
'accountcreatedtext' => 'Notandaaðgangur fyrir $1 er tilbúinn.',
'createaccount-title' => 'Innskráningagerð á {{SITENAME}}',
'createaccount-text' => 'Einhver bjó til aðgang fyrir netfangið þitt á {{SITENAME}} ($4) undir nafninu „$2“, með lykilorðið „$3“.
Þú ættir að skrá þig inn og breyta lykilorðinu núna.

Þú getur hunsað þessi skilaboð, ef villa hefur átt sér stað.',
'usernamehasherror' => 'Notendanöfn mega ekki innihalda kassa (#)',
'login-throttled' => 'Þér hefur mistekist að skrá þig inn undir þessu notendanafni of oft.
Vinsamlegast reynið aftur síðar.',
'login-abort-generic' => 'Innskráningin misheppnaðist - hætt var við hana.',
'loginlanguagelabel' => 'Tungumál: $1',
'suspicious-userlogout' => 'Beiðni um útskráningu hafnað því hún var líklegast send frá biluðum vafra eða vefseli sem hefur vistað vefsíðuna í flýtiminni.',

# E-mail sending
'php-mail-error-unknown' => 'Óþekkt villa í PHP mail() aðgerð.',
'user-mail-no-addy' => 'Gat ekki sent tölvupóst því ekkert tölvupóstfang fannst.',

# Change password dialog
'resetpass' => 'Breyta lykilorði',
'resetpass_announce' => 'Þú skráðir þig inn með tímabundnum netfangskóða.
Til að klára að skrá þig inn, verður þú að endurstilla lykilorðið hér:',
'resetpass_text' => '<!-- Setja texta hér -->',
'resetpass_header' => 'Breyta lykilorði',
'oldpassword' => 'Gamla lykilorðið',
'newpassword' => 'Nýja lykilorðið',
'retypenew' => 'Endurtaktu nýja lykilorðið:',
'resetpass_submit' => 'Skrifaðu aðgangsorðið og skráðu þig inn',
'resetpass_success' => 'Aðgangsorðinu þínu hefur verið breytt! Skráir þig inn...',
'resetpass_forbidden' => 'Ekki er hægt að breyta lykilorðum',
'resetpass-no-info' => 'Þú verður að vera skráð(ur) inn til að hafa aðgang að þessari síðu.',
'resetpass-submit-loggedin' => 'Breyta lykilorði',
'resetpass-submit-cancel' => 'Hætta við',
'resetpass-wrong-oldpass' => 'Vitlaust tímabundið eða núverandi lykilorð.
Þú gætir þegar verið búin/n að breyta lykilorðinu eða sótt um nýtt tímabundið lykilorð',
'resetpass-temp-password' => 'Tímabundið lykilorð:',

# Special:PasswordReset
'passwordreset' => 'Endurstilla lykilorð',
'passwordreset-text' => 'Fylltu út þennan reit til að fá tölvupóst um áminningu um notendauplýsingarnar þínar.',
'passwordreset-legend' => 'Endurstilla lykilorð',
'passwordreset-disabled' => 'Lokað hefur verið fyrir að endurstilla lykilorð á þessum wiki.',
'passwordreset-pretext' => '{{PLURAL:$1||Sláðu inn einn hluta gagnanna hér fyrir neðan}}',
'passwordreset-username' => 'Notandanafn:',
'passwordreset-domain' => 'Lén:',
'passwordreset-capture' => 'Sjá áminninguna sem var send í tölvupósti?',
'passwordreset-capture-help' => 'Ef þú hakar við þennan reit verður tölvupósturinn (með tímabundna lykilorðinu) sýndur þér og einnig sendur notandanum.',
'passwordreset-email' => 'Netfang:',
'passwordreset-emailtitle' => 'Notendaupplýsingar á {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Einhver (líklegast þú, á vistfanginu $1) hefur beðið um notendaupplýsingar þínar fyrir {{SITENAME}} ($4). Aðgangur eftirfarandi {{PLURAL:$3|notanda er|notendum eru}} tengd þessu netfangi:

$2

Ef þetta er það sem þú vildir, þarftu að skrá þig inn og velja nýtt lykilorð. {{PLURAL:$3|Tímabundna lykilorð|Tímabundnu lykilorðin}} renna út eftir {{PLURAL:$5|einn dag|$5 daga}}.

Ef það varst ekki þú sem fórst fram á þetta, eða ef þú mannst lykilorðið þitt, og villt ekki lengur breyta því, skaltu hunsa þessi skilaboð og halda áfram að nota gamla lykilorðið.',
'passwordreset-emailtext-user' => 'Notandinn $1 á {{SITENAME}} hefur beðið um notendaupplýsingar þínar fyrir {{SITENAME}} ($4). Aðgangur eftirfarandi {{PLURAL:$3|notanda er|notendum eru}} tengd þessu netfangi:

$2

Ef þetta er það sem þú vildir, þarftu að skrá þig inn og velja nýtt lykilorð. {{PLURAL:$3|Tímabundna lykilorð|Tímabundnu lykilorðin}} renna út eftir {{PLURAL:$5|einn dag|$5 daga}}.

Ef það varst ekki þú sem fórst fram á þetta, eða ef þú mannst lykilorðið þitt, og villt ekki lengur breyta því, skaltu hunsa þessi skilaboð og halda áfram að nota gamla lykilorðið.',
'passwordreset-emailelement' => 'Notendanafn: $1
Tímabundið lykilorð: $2',
'passwordreset-emailsent' => 'Áminning hefur verið send í tölvupósti.',
'passwordreset-emailsent-capture' => 'Áminning hefur verið send í tölvupósti, sem er sýnd hér fyrir neðan.',
'passwordreset-emailerror-capture' => 'Áminning var búin til, sem er sýnd hér fyrir neðan, en ekki tókst að senda hana til notandans: $1',

# Special:ChangeEmail
'changeemail' => 'Breyting netfangs',
'changeemail-header' => 'Breyta skráðu netfangi',
'changeemail-text' => 'Fylltu út þetta eyðublað til að breyta netfanginu þínu. Þú þarft að slá inn lykilorðið þitt til að staðfesta breytinguna.',
'changeemail-no-info' => 'Þú verður að vera skráð(ur) inn til að hafa aðgang að þessari síðu.',
'changeemail-oldemail' => 'Núverandi netfang:',
'changeemail-newemail' => 'Nýtt netfang:',
'changeemail-none' => '(ekkert)',
'changeemail-submit' => 'Breyta netfangi',
'changeemail-cancel' => 'Hætta við',

# Edit page toolbar
'bold_sample' => 'Feitletraður texti',
'bold_tip' => 'Feitletraður texti',
'italic_sample' => 'Skáletraður texti',
'italic_tip' => 'Skáletraður texti',
'link_sample' => 'Titill tengils',
'link_tip' => 'Innri tengill',
'extlink_sample' => 'http://www.example.com titill tengils',
'extlink_tip' => 'Ytri tengill (munið að setja http:// á undan)',
'headline_sample' => 'Fyrirsagnartexti',
'headline_tip' => 'Annars stigs fyrirsögn',
'nowiki_sample' => 'Innsetjið ósniðinn texta hér',
'nowiki_tip' => 'Hunsa wiki-snið',
'image_sample' => 'Sýnishorn.jpg',
'image_tip' => 'Innfellt skjal',
'media_sample' => 'Sýnishorn.ogg',
'media_tip' => 'Tengill skjals',
'sig_tip' => 'Undirskrift þín auk tímasetningar',
'hr_tip' => 'Lárétt lína (notist sparlega)',

# Edit pages
'summary' => 'Breytingarágrip:',
'subject' => 'Fyrirsögn:',
'minoredit' => 'Þetta er minniháttar breyting',
'watchthis' => 'Vakta þessa síðu',
'savearticle' => 'Vista síðu',
'preview' => 'Forskoða',
'showpreview' => 'Forskoða',
'showlivepreview' => 'Forskoða',
'showdiff' => 'Sýna breytingar',
'anoneditwarning' => "'''Viðvörun:''' Þú ert ekki innskráð(ur). Vistfang þitt skráist í breytingaskrá síðunnar.",
'anonpreviewwarning' => 'Þú ert ekki innskráð(ur). Vistfang þitt skráist í breytingaskrá síðunnar.',
'missingsummary' => "'''Áminning:''' Þú hefur ekki skrifað breytingarágrip.
Ef þú smellir á Vista aftur, verður breyting þín vistuð án þess.",
'missingcommenttext' => 'Gerðu svo vel og skrifaðu athugasemd fyrir neðan.',
'missingcommentheader' => "'''Áminning:''' Þú hefur ekki gefið upp umræðuefni/fyrirsögn.
Ef þú smellir á \"{{int:savearticle}}\" aftur, verður breyting þín vistuð án þess.",
'summary-preview' => 'Forskoða breytingarágrip:',
'subject-preview' => 'Forskoðun umræðuefnis/fyrirsagnar:',
'blockedtitle' => 'Notandi er bannaður',
'blockedtext' => "'''Notandanafn þitt eða vistfang hefur verið bannað.'''

Bannið var sett af $1.
Ástæðan er eftirfarandi: ''$2''.

* Bannið hófst: $8
* Banninu lýkur: $6
* Sá sem banna átti: $7

Þú getur haft samband við $1 eða annan [[{{MediaWiki:Grouppage-sysop}}|stjórnanda]] til að ræða bannið.
Þú getur ekki notað „Senda þessum notanda tölvupóst“ aðgerðina nema gilt netfang sé skráð í [[Special:Preferences|notandastillingum þínum]] og að þér hafi ekki verið óheimilað það.
Núverandi vistfang þitt er $3, og bönnunarnúmerið er #$5.
Vinsamlegast tilgreindu allt að ofanverðu í fyrirspurnum þínum.",
'autoblockedtext' => "Vistfang þitt hefur verið sjálfvirkt bannað því það var notað af öðrum notanda, sem var bannaður af $1.
Ástæðan er eftirfarandi:

:''$2''

* Bannið hófst: $8
* Banninu lýkur: $6
* Sá sem banna átti: $7

Þú getur haft samband við $1 eða annan [[{{MediaWiki:Grouppage-sysop}}|stjórnanda]] til að ræða bannið.

Athugaðu að þú getur ekki notað „Senda þessum notanda tölvupóst“ aðgerðina nema gilt netfang sé skráð í [[Special:Preferences|notandastillingum þínum]] og að þér hafi ekki verið óheimilað það.

Núverandi vistfang þitt er $3, og bönnunarnúmerið er #$5.
Vinsamlegast tilgreindu allt að ofanverðu í fyrirspurnum þínum.",
'blockednoreason' => 'engin ástæða gefin',
'whitelistedittext' => 'Þú þarft að $1 til að breyta síðum.',
'confirmedittext' => 'Þú verður að staðfesta netfangið þitt áður en þú getur breytt síðum. Vinsamlegast stilltu og staðfestu netfangið þitt í gegnum [[Special:Preferences|stillingarnar]].',
'nosuchsectiontitle' => 'Hluti ekki til',
'nosuchsectiontext' => 'Þú reyndir að breyta hluta sem er ekki til.
Hlutinn gæti hafa verið fluttur til eða hent á meðan þú varst að skoða síðuna.',
'loginreqtitle' => 'Innskráningar krafist',
'loginreqlink' => 'innskrá',
'loginreqpagetext' => 'Þú þarft að $1 til að geta séð aðrar síður.',
'accmailtitle' => 'Lykilorð sent.',
'accmailtext' => "Lykilorðið fyrir [[User talk:$1|$1]] hefur verið sent á $2.

Hægt er að breyta lykilorðinu fyrir aðganginn á ''[[Special:ChangePassword|change password]]'' þegar notandinn hefur skráð sig inn.",
'newarticle' => '(Ný)',
'newarticletext' => "Þú hefur fylgt tengli á síðu sem ekki er til ennþá.
Þú getur búið til síðu með þessu nafni með því að skrifa í formið fyrir neðan
(meiri upplýsingar í [[{{MediaWiki:Helppage}}|hjálpinni]]).
Ef þú hefur óvart villst hingað geturðu notað '''til baka'''-hnappinn í vafranum þínum.",
'anontalkpagetext' => "----''Þetta er spjallsíða fyrir óþekktan notanda sem hefur ekki búið til aðgang ennþá, eða notar hann ekki.
Þar af leiðandi þurfum við að nota vistfang til að bera kennsli á hann/hana.
Nokkrir notendur geta deilt sama vistfangi.
Ef þú ert óþekktur notandi og finnst að óviðkomandi athugasemdum hafa verið beint að þér, gjörðu svo vel og [[Special:UserLogin/signup|búðu til aðgang]] eða [[Special:UserLogin|skráðu þig inn]] til þess að koma í veg fyrir þennan rugling við aðra óþekkta notendur í framtíðinni.''",
'noarticletext' => 'Enginn texti er á þessari síðu enn sem komið er.
Þú getur [[Special:Search/{{PAGENAME}}|leitað í öðrum síðum]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} leitað í tengdum skrám], eða [{{fullurl:{{FULLPAGENAME}}|action=edit}} breytt henni sjálfur]</span>.',
'noarticletext-nopermission' => 'Það er enginn texti á þessari síðu eins og er.
Þú getur [[Special:Search/{{PAGENAME}}|leitað að þessum titli]] í öðrum síðum, eða <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} leitað í tengdum skrám]</span>, en þú hefur ekki réttindi til þess að stofna þessa síðu.',
'missing-revision' => 'Útgáfa #$1 síðunnar „{{PAGENAME}}" er ekki til.

Þetta gerist oftast þegar úreld breytingarskrá tengir á síðu sem hefur verið eytt. Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingarskránni].',
'userpage-userdoesnotexist' => 'Notandaaðgangurinn „<nowiki>$1</nowiki>“ er ekki skráður.
Gjörðu svo vel og athugaðu hvort að þú viljir skapa/breyta þessari síðu.',
'userpage-userdoesnotexist-view' => 'Notandinn "$1" er ekki skráður.',
'blocked-notice-logextract' => 'Þessi notandi er í banni.
Síðasta færsla notandans úr bönnunarskrá er sýnd hér fyrir neðan til skýringar:',
'clearyourcache' => "'''Athugaðu:''' Eftir vistun kann að vera að þú þurfir að komast hjá skyndiminni vafrans þíns til að sjá breytingarnar.
* '''Firefox / Safari:''' Haltu ''Shift'' samtímis og þú smellir á ''Endurhlaða (Reload)'', eða ýttu á annaðhvort ''Ctrl-F5'' eða ''Ctrl-R'' (''⌘-R'' á Mac)
* '''Google Chrome:''' Ýttu á ''Ctrl-Shift-R'' (''⌘-Shift-R'' á Mac)
* '''Internet Explorer:''' Haltu ''Ctrl'' samtímis og þú smellir á ''Endurnýja (Refresh)'', eða ýttu á ''Ctrl-F5''
* '''Opera:''' Hreinsaðu skyndiminnið í ''Verkfæri (Tools) → Stillingar (Preferences)''",
'usercssyoucanpreview' => "'''Ath:''' Hægt er að nota „{{int:showpreview}}“ hnappinn til að prófa CSS-kóða áður en hann er vistaður.",
'userjsyoucanpreview' => "'''Ath:''' Hægt er að nota \"{{int:showpreview}}\" hnappinn til að prófa JavaScript-kóða áður en hann er vistaður.",
'usercsspreview' => "'''Mundu að þú ert aðeins að forskoða CSS-kóðann þinn.'''
'''Hann hefur ekki enn verið vistaður!'''",
'userjspreview' => "'''Mundu að þú ert aðeins að prófa/forskoða JavaScript-kóðann þinn.'''
'''Hann hefur ekki enn verið vistaður!'''",
'sitecsspreview' => "'''Mundu að þú ert aðeins að forskoða CSS-kóðann þinn.'''
'''Hann hefur ekki enn verið vistaður!'''",
'sitejspreview' => "'''Mundu að þú ert aðeins að prófa/forskoða JavaScript-kóðann.'''
'''Hann hefur ekki enn verið vistaður!'''",
'userinvalidcssjstitle' => "'''Viðvörun:''' Þemað $1 er ekki til. Sérsniðin CSS og JavaScript útlit nota lágstafi, t.d.  {{ns:user}}:Foo/vector.css en alls ekki {{ns:user}}:Foo/Vector.css.",
'updated' => '(Uppfært)',
'note' => "'''Athugið:'''",
'previewnote' => "'''Það sem sést hér er aðeins forskoðun og hefur ekki enn verið vistað!'''",
'continue-editing' => 'Fara á breytingasvæði',
'previewconflict' => 'Þessi forskoðun endurspeglar textann í efra breytingarsvæði eins og hann myndi líta út ef þú vistar.',
'session_fail_preview' => "'''Því miður! Gat ekki unnið úr breytingum þínum vegna týndra lotugagna.
Vinsamlegast reyndu aftur síðar. Ef það virkar ekki heldur skaltu reyna að skrá þig út og inn á ný.'''",
'session_fail_preview_html' => "'''Því miður gátum við ekki unnið úr breytingu þinni vegna týndra lotugagna.'''

''Því {{SITENAME}} styður hráan HTML-kóða er forskoðunin falin sem vörn gegn JavaScript árásum..''

'''Ef þetta er vingjarnleg breyting, reyndu þá aftur.'''
Ef þetta leysir ekki vandamálið, reyndu að [[Special:UserLogout|skrá þig út]] og skrá þig aftur inn.",
'token_suffix_mismatch' => "'''Breytingu þinni hefur verið hafnað því að biðlarinn þinn ruglaði greinarmerkingum í breytingar tókanum.\"
Þetta er gert til að hindra spillingu texta síðunnar.
Þetta getur gerst þegar þú notar bilaðan vafra eða ónafngreinda vefsels þjónustu.",
'edit_form_incomplete' => "'''Sumir hlutar breytingarinnar bárust ekki til vefþjónsins; athugaðu hvort breytingin þín er óbreytt og reyndu aftur.'''",
'editing' => 'Breyti $1',
'creating' => 'Skapa $1',
'editingsection' => 'Breyti $1 (hluta)',
'editingcomment' => 'Breyti $1 (nýr hluti)',
'editconflict' => 'Breytingaárekstur: $1',
'explainconflict' => "Síðunni hefur verið breytt síðan þú byrjaðir að gera breytingar á henni, textinn í efri reitnum inniheldur núverandi útgáfu úr gagnagrunni og sá neðri inniheldur þína útgáfu, þú þarft hér að færa breytingar sem þú vilt halda úr neðri reitnum í þann efri og vista síðuna. 
'''Aðeins''' texti úr efri reitnum mun vera vistaður þegar þú vistar.",
'yourtext' => 'Þinn texti',
'storedversion' => 'Geymd útgáfa',
'nonunicodebrowser' => "'''Viðvörun: Vafrarinn þinn styður ekki unicode.'''
Lausn er í gildi sem leyfir þér að breyta síðum: Stafatákn sem eru ekki í ASCII kerfinu birtast í breytingarglugganum eins og sextándakóðar.",
'editingold' => "'''ATH: Þú ert að breyta gamalli útgáfu þessarar síðu og munu allar breytingar sem gerðar hafa verið á henni frá þeirri útgáfu vera fjarlægðar ef þú vistar.'''",
'yourdiff' => 'Mismunur',
'copyrightwarning' => "Vinsamlegast athugaðu að öll framlög á {{SITENAME}} eru álitin leyfisbundin samkvæmt $2 (sjá $1 fyrir frekari upplýsingar).  Ef þú vilt ekki að skrif þín falli undir þetta leyfi og öllum verði frjálst að breyta og endurútgefa efnið samkvæmt því skaltu ekki leggja þau fram hér.<br />
Þú berð ábyrgð á framlögum þínum, þau verða að vera þín skrif eða afrit texta í almannaeigu eða sambærilegs frjáls texta.
'''AFRITIÐ EKKI HÖFUNDARRÉTTARVARIN VERK Á ÞESSA SÍÐU ÁN LEYFIS'''",
'copyrightwarning2' => "Vinsamlegast athugið að aðrir notendur geta breytt eða fjarlægt öll framlög til {{SITENAME}}.
Ef þú vilt ekki að textanum verði breytt skaltu ekki senda hann inn hér.<br />
Þú lofar okkur einnig að þú hafir skrifað þetta sjálfur, að efnið sé í almannaeigu eða að það heyri undir frjálst leyfi. (sjá $1).
'''EKKI SENDA INN HÖFUNDARRÉTTARVARIÐ EFNI ÁN LEYFIS RÉTTHAFA!'''",
'longpageerror' => "'''VILLA: Textinn sem þú sendir inn er {{PLURAL:$1|eitt kílóbæti|$1 kílóbæti}} að lengd, en hámarkið er {{PLURAL:$2|eitt kílóbæti|$2 kílóbæti}}. Ekki er hægt að vista textann.'''",
'readonlywarning' => "'''AÐVÖRUN: Gagnagrunninum hefur verið læst til að unnt sé að framkvæma viðhaldsaðgerðir, svo þú getur ekki vistað breytingar þínar núna.
Þú kannt að vilja að klippa og líma textann í textaskjal og vista hann fyrir síðar.'''

Stjórnandinn sem læsti honum gaf þessa skýringu: $1",
'protectedpagewarning' => "'''Viðvörun: Þessari síðu hefur verið læst svo aðeins notendur með möppudýraréttindi geti breytt henni.'''
Síðasta færsla síðunnar úr verndunarskrá er sýnd til skýringar:",
'semiprotectedpagewarning' => "'''Athugið''': Þessari síðu hefur verið læst þannig að aðeins innskráðir notendur geti breytt henni.
Síðasta færsla síðunnar úr verndunarskrá er sýnd til skýringar:",
'cascadeprotectedwarning' => "'''Viðvörun:''' Þessari síðu hefur verið læst svo aðeins möppudýr geta breytt henni, því hún er innifalin í keðjuvörn eftirfarandi {{PLURAL:$1|síðu|síðna}}:",
'titleprotectedwarning' => "''VIÐVÖRUN: Þessari síðu hefur verið læst svo aðeins [[Special:ListGroupRights|sérstakir notendur]] geta breytt henni.'''
Verndunarskrá síðunnar er gefin fyrir neðan til tilvísunar.",
'templatesused' => 'Snið {{PLURAL:$1|notað|notuð}} á þessari síðu:',
'templatesusedpreview' => 'Snið {{PLURAL:$1|notað|notuð}} í forskoðuninni:',
'templatesusedsection' => 'Snið {{PLURAL:$1|notað|notuð}} í þessum hluta:',
'template-protected' => '(vernduð)',
'template-semiprotected' => '(hálfvernduð)',
'hiddencategories' => 'Þessi síða er meðlimur í {{PLURAL:$1|1 földum flokki|$1 földum flokkum}}:',
'nocreatetitle' => 'Síðugerð takmörkuð',
'nocreatetext' => '{{SITENAME}} hefur takmarkað eiginleikann að gera nýjar síður.
Þú getur farið til baka og breytt núverandi síðum, eða [[Special:UserLogin|skráð þið inn eða búið til aðgang]].',
'nocreate-loggedin' => 'Þú hefur ekki leyfi til að skapa nýjar síður.',
'sectioneditnotsupported-title' => 'Hlutabreyting er ekki virk',
'sectioneditnotsupported-text' => 'Hlutabreyting er ekki virk á þessari síðu.',
'permissionserrors' => 'Leyfisvillur',
'permissionserrorstext' => 'Þú hefur ekki leyfi til að gera þetta, af eftirfarandi {{PLURAL:$1|ástæðu|ástæðum}}:',
'permissionserrorstext-withaction' => 'Þú hefur ekki réttindi til að $2, af eftirfarandi {{PLURAL:$1|ástæðu|ástæðum}}:',
'recreate-moveddeleted-warn' => "'''Viðvörun: Þú ert að endurskapa síðu sem áður hefur verið eytt.'''

Athuga skal hvort viðeigandi sé að gera þessa síðu.
Eyðingarskrá og flutningaskrá fyrir þessa síðu eru útvegaðar hér til þæginda:",
'moveddeleted-notice' => 'Þessari síðu hefur verið eytt.
Eyðingaskrá og flutningaskrá síðunnar eru gefnar fyrir neðan til tilvísunar.',
'log-fulllog' => 'Skoða alla aðgerðarskránna',
'edit-hook-aborted' => 'Breyting síðu stöðvuð af viðbótarkrók (extension hook).
Engin skýring gefin.',
'edit-gone-missing' => 'Gat ekki uppfært síðu.
Svo virðist sem henni hafi verið eytt.',
'edit-conflict' => 'Breytingaárekstur.',
'edit-no-change' => 'Breyting þín var hunsuð, því engin breyting var á textanum.',
'edit-already-exists' => 'Gat ekki skapað nýja síðu.
Hún er nú þegar til.',
'defaultmessagetext' => 'Sjálfgefinn skilaboða texti',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Viðvörun:''' Þessi síða inniheldur of mörg vinnslufrek aðgerðar þáttunar köll.

Hún ætti að innihalda minna en $2 {{PLURAL:$2|kall|köll}}, en {{PLURAL:$1|er nú eitt kall|eru nú $1 köll}}.",
'expensive-parserfunction-category' => 'Síður með of mörg vinnslufrek aðgerðar þáttunar köll',
'post-expand-template-inclusion-warning' => "'''Viðvörun:''' Sniðið tekur of mikið pláss.
Hluti sniðsins verður ekki með.",
'post-expand-template-inclusion-category' => 'Síður þar sem eru stærri en stærðartakmörkun sniða segir til um',
'post-expand-template-argument-warning' => "'''Viðvörun:''' Þessi síða inniheldur í minnsta lagi eitt vinnslufrekt frumgildi.
Þeim hefur verið sleppt.",
'post-expand-template-argument-category' => 'Síður sem innihalda frumbreytur sniða sem hefur verið sleppt',
'parser-template-loop-warning' => 'Lykkja í sniði fundin: [[$1]]',

# "Undo" feature
'undo-success' => 'Breytingin hefur verið tekin tilbaka. Vinsamlegast staðfestu og vistaðu svo.',
'undo-failure' => 'Breytinguna var ekki hægt að taka tilbaka vegna breytinga í millitíðinni.',
'undo-norev' => 'Ekki var hægt að taka breytinguna aftr því að hún er ekki til eða henni var eytt.',
'undo-summary' => 'Tek aftur breytingu $1 frá [[Special:Contributions/$2|$2]] ([[User talk:$2|spjall]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ekki hægt að búa til aðgang',
'cantcreateaccount-text' => "Aðgangsgerð fyrir þetta vistfang ('''$1''') hefur verið bannað af [[User:$3|$3]].

Ástæðan sem $3 gaf fyrir því er ''$2''",

# History pages
'viewpagelogs' => 'Sýna aðgerðir varðandi þessa síðu',
'nohistory' => 'Þessi síða hefur enga breytingaskrá.',
'currentrev' => 'Núverandi útgáfa',
'currentrev-asof' => 'Núverandi breyting frá og með $1',
'revisionasof' => 'Útgáfa síðunnar $1',
'revision-info' => 'Útgáfa frá $1 eftir $2',
'previousrevision' => '←Fyrri útgáfa',
'nextrevision' => 'Næsta útgáfa→',
'currentrevisionlink' => 'Núverandi útgáfa',
'cur' => 'fyrri',
'next' => 'næst',
'last' => 'þessa',
'page_first' => 'fyrsta',
'page_last' => 'síðasta',
'histlegend' => 'Mismunarval: merktu við einvalshnappanna fyrir þær útgáfur sem á að bera saman og styddu svo á færsluhnappinn.<br />
Skýringartexti: (nú) = skoðanamunur á núverandi útgáfu,
(síðast) = skoðanamunur á undanfarandi útgáfu, M = minniháttar breyting.',
'history-fieldset-title' => 'Skoða breytingaskrá',
'history-show-deleted' => 'Eingöngu eyddar breytingar',
'histfirst' => 'elstu',
'histlast' => 'yngstu',
'historysize' => '({{PLURAL:$1|1 bæti|$1 bæti}})',
'historyempty' => '(tóm)',

# Revision feed
'history-feed-title' => 'Breytingaskrá',
'history-feed-description' => 'Breytingaskrá fyrir þessa síðu á wiki-síðunni',
'history-feed-item-nocomment' => '$1 á $2',
'history-feed-empty' => 'Síðan sem þú leitaðir að er ekki til.
Möglegt er að henni hafi verið eytt út af þessari wiki síðu, eða endurnefnd.
Prófaðu [[Special:Search|að leita á þessari wiki síðu]] að svipuðum síðum.',

# Revision deletion
'rev-deleted-comment' => '(breytingarágrip fjarlægt)',
'rev-deleted-user' => '(notandanafn fjarlægt)',
'rev-deleted-event' => '(skráarbreyting fjarlægð)',
'rev-deleted-user-contribs' => '[notandanafn eða vistfang falið - breyting falin í framlögum]',
'rev-deleted-text-permission' => "Þessari útgáfu síðunnar hefur verið '''eytt'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingaskrá].",
'rev-deleted-text-unhide' => "Þessari útgáfu síðunnar hefur verið '''eytt'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingaskrá].
Þú getur enn skoðað [$1 þessa útgáfu] ef þú vilt halda áfram.",
'rev-suppressed-text-unhide' => "Þessari útgáfu síðunnar hefur verið '''bæld niður'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} bælingarskrá].
Þú getur enn skoðað [$1 þessa útgáfu] ef þú vilt halda áfram.",
'rev-deleted-text-view' => "Þessari útgáfu síðunnar hefur verið '''eytt'''.
Þú getur enn skoðað hana; frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingaskrá].",
'rev-suppressed-text-view' => "Þessari útgáfu síðunnar hefur verið '''bæld niður'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} bælingarskrá].",
'rev-deleted-no-diff' => "Þú getur ekki skoðað þessa breytingu því ein af breytingunum hefur verið '''eytt'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingaskrá]",
'rev-suppressed-no-diff' => "Þú getur ekki skoðað þessa breytingu því einni af útgáfunum var '''eytt'''.",
'rev-deleted-unhide-diff' => "Einni af útgáfum þessarar breytingar hefur verið '''eytt'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingaskrá].
Þú getur enn skoðað [$1 þessa breytingu] ef þú vilt halda áfram.",
'rev-suppressed-unhide-diff' => "Einni af útgáfum þessarar breytingar hefur verið '''bæld niður'''.
Frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} bælingarskrá].
Þú getur enn skoðað [$1 þessa breytingu] ef þú vilt halda áfram.",
'rev-deleted-diff-view' => "Einni af útgáfum þessarar breytingar hefur verið '''eytt'''.
Þú getur enn skoðað þessa breytingu; frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} eyðingaskrá].",
'rev-suppressed-diff-view' => "Einni af útgáfum þessarar breytingar hefur verið '''bæld niður'''.
Þú getur enn skoðað þessa breytingu; frekari upplýsingar eru í [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} bælingaskrá].",
'rev-delundel' => 'sýna/fela',
'rev-showdeleted' => 'sýna',
'revisiondelete' => 'Eyða/endurvekja breytingar',
'revdelete-nooldid-title' => 'Ógild markbreyting',
'revdelete-nooldid-text' => 'Annaðhvort hefur útgáfan sem á að fela ekki verið tilgreind, þessi útgáfa ekki verið til, eða að þú sért að reyna að fela núverandi útgáfu.',
'revdelete-no-file' => 'Umbeðin skrá er ekki til.',
'revdelete-show-file-confirm' => 'Ertu viss um að þú viljir sjá eydda breytingu af síðunni "<nowiki>$1</nowiki>" frá $2 $3?',
'revdelete-show-file-submit' => 'Já',
'revdelete-selected' => "'''{{PLURAL:$2|Valin breyting|Valdar breytingar}} fyrir [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Valin aðgerð|Valdar aðgerðir}}:'''",
'revdelete-text' => "'''Eyddar útgáfur og breytingar munu birtast áfram í breytingarskrá síðunnar og í aðgerðarskrám, en hluti upplýsingana verða falin almenningi.'''
Önnur möppudýr á {{SITENAME}} hafa aðgang að földu upplýsingunum og geta endurvakið upplýsingarnar í gegnum sama viðmót, nema sérstakar takmarkanir séu virkar.",
'revdelete-confirm' => 'Vinsamlegast staðfestu að þú viljir gera þetta, að þú skiljir afleiðingarnar og að þú sért að gera þetta í samræmi við  [[{{MediaWiki:Policy-url}}]].',
'revdelete-suppress-text' => "Bælingu á '''eingöngu''' að nota í eftirfarandi tilfellum:
* Mögulegar ærumleiðandi upplýsingar
* Óviðeigandi persónulegar upplýsingar
*: ''heimilisfang, símanúmer, kennitala, osfrv.''",
'revdelete-legend' => 'Setja sjáanlegar hamlanir',
'revdelete-hide-text' => 'Fela breytingatexta',
'revdelete-hide-image' => 'Fela efni skráar',
'revdelete-hide-name' => 'Fela aðgerð og mark',
'revdelete-hide-comment' => 'Fela breytingarágrip',
'revdelete-hide-user' => 'Fela notandanafn/vistfang',
'revdelete-hide-restricted' => 'Dylja gögn frá stjórnendum og öðrum',
'revdelete-radio-same' => '(ekki breyta)',
'revdelete-radio-set' => 'Já',
'revdelete-radio-unset' => 'Nei',
'revdelete-suppress' => 'Dylja gögn frá stjórnendum og öðrum',
'revdelete-unsuppress' => 'Fjarlægja takmarkanir á endurvöktum breytingum',
'revdelete-log' => 'Ástæða:',
'revdelete-submit' => 'Setja á {{PLURAL:$1|valda breytingu|valdar breytingar}}',
'revdel-restore' => 'Breyta sýn',
'revdel-restore-deleted' => 'eyddar breytingar',
'revdel-restore-visible' => 'sýnilegar breytingar',
'pagehist' => 'Breytingaskrá',
'deletedhist' => 'Eyðingaskrá',
'revdelete-hide-current' => 'Mistókst að fela breytingu frá $1 $2: Þetta er núverandi útgáfa síðunnar.
Ekki er hægt að fela hana.',
'revdelete-show-no-access' => 'Mistókst að sýna breytingu frá $1 $2: Þessi breyting hefur verið merkt sem "takmörkuð".
Þú hefur ekki aðgang að henni.',
'revdelete-no-change' => "'''Viðvörun:''' Breytingin frá $1 $2 hefur þegar umbeðnar sýnileika stillingar.",
'revdelete-only-restricted' => 'Mistókst að fela breytingu frá $1 $2: Þú getur ekki falið breytingu fyrir möppudýrum án þess að velja eina af hinum sýnileika stillingunum.',
'revdelete-reason-dropdown' => '*Algengar eyðingarástæður
**Höfundarréttarbrot
**Óviðeigandi athugasemdir eða persónuuplýsingar
**Óviðeigandi notandanafn
**Mögulega ærumleiðandi upplýsingar',
'revdelete-otherreason' => 'Aðrar/fleiri ástæður:',
'revdelete-reasonotherlist' => 'Önnur ástæða',
'revdelete-edit-reasonlist' => 'Eyðingarástæður',

# Suppression log
'suppressionlog' => 'Bælingarskrá',

# History merging
'mergehistory' => 'Sameina breytingaskrár',
'mergehistory-header' => 'Þessi síða gerir þér kleift að sameina breytingarskrá tveggja síðna.
Sjáðu til þess að þessi breyting sameini breytingarskrárnar samfellt.',
'mergehistory-box' => 'Sameina breytingarskrá tveggja síðna:',
'mergehistory-from' => 'Heimildsíða:',
'mergehistory-into' => 'Áætlunarsíða:',
'mergehistory-list' => 'Breytingarskrá sem hægt er að sameina',
'mergehistory-reason' => 'Ástæða:',

# Merge log
'mergelog' => 'Sameiningar skrá',
'pagemerge-logentry' => 'sameinaði [[$1]] við [[$2]] (útgáfur frá $3)',
'revertmerge' => 'Taka aftur sameiningu',
'mergelogpagetext' => 'Þetta er skrá yfir síðustu sameiningar einnar síðu við aðra.',

# Diffs
'history-title' => '$1: Breytingaskrá',
'difference-title' => 'Munur á milli breytinga „$1“',
'difference-title-multipage' => 'Munur á milli síðna „$1“ og „$2“',
'difference-multipage' => '(Munur á milli síðna)',
'lineno' => 'Lína $1:',
'compareselectedversions' => 'Bera saman valdar útgáfur',
'showhideselectedversions' => 'Sýna/fela valdar breytingar',
'editundo' => 'Taka aftur þessa breytingu',
'diff-multi' => '({{PLURAL:$1|Ein millibreyting ekki sýnd|$1 millibreytingar ekki sýndar}} frá {{PLURAL:$2|notanda|$2 notendum}}.)',
'diff-multi-manyusers' => '({{PLURAL:$1|Ein millibreyting ekki sýnd|$1 millibreytingar ekki sýndar}} frá fleiri en {{PLURAL:$2|einum notanda|$2 notendum}}.)',

# Search results
'searchresults' => 'Leitarniðurstöður',
'searchresults-title' => 'Leitarniðurstöður fyrir „$1“',
'searchresulttext' => 'Fyrir frekari upplýsingar um leit á {{SITENAME}} farið á [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => "Þú leitaðir að '''[[:$1]]''' ([[Special:Prefixindex/$1|öllum síðum sem hefjast á „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|öllum síðum sem tengja í „$1“]])",
'searchsubtitleinvalid' => "Þú leitaðir að '''$1'''",
'toomanymatches' => 'Of mörgum niðurstöðum var skilað, gjörðu svo vel og reyndu aðra fyrirspurn',
'titlematches' => 'Titlar greina sem pössuðu við fyrirspurnina',
'notitlematches' => 'Engir greinartitlar pössuðu við fyrirspurnina',
'textmatches' => 'Leitarorð fannst/fundust í innihaldi eftirfarandi greina',
'notextmatches' => 'Engar samsvaranir á texta í síðum',
'prevn' => 'síðustu {{PLURAL:$1|$1}}',
'nextn' => 'næstu {{PLURAL:$1|$1}}',
'prevn-title' => 'Fyrri $1 {{PLURAL:$1|niðurstaða|niðurstöður}}',
'nextn-title' => '{{PLURAL:$1|Næsta|Næstu}} $1 {{PLURAL:$1|niðurstaða|niðurstöður}}',
'shown-title' => 'Sýna $1 {{PLURAL:$1|niðurstöðu|niðurstöður}} á hverri síðu',
'viewprevnext' => 'Skoða ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Leitarvalmöguleikar',
'searchmenu-exists' => "'''Það er síða að nafni „[[:$1]]“ á þessum wiki'''",
'searchmenu-new' => "'''Skapaðu síðuna \"[[:\$1]]\" á þessum wiki!'''",
'searchhelp-url' => 'Help:Efnisyfirlit',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Leita að síðum með þessu forskeyti]]',
'searchprofile-articles' => 'Efnissíður',
'searchprofile-project' => 'Hjálpar- og verkefnasíður',
'searchprofile-images' => 'Margmiðlanir',
'searchprofile-everything' => 'Allt',
'searchprofile-advanced' => 'Nánar',
'searchprofile-articles-tooltip' => 'Leita í $1',
'searchprofile-project-tooltip' => 'Leita í $1',
'searchprofile-images-tooltip' => 'Leita að skrám',
'searchprofile-everything-tooltip' => 'Leita í öllu efni (þar á meðal spjallsíðum)',
'searchprofile-advanced-tooltip' => 'Leita í ákveðnum nafnrýmum',
'search-result-size' => '$1 ({{PLURAL:$2|1 orð|$2 orð}})',
'search-result-category-size' => '{{PLURAL:$1|1 meðlimur|$1 meðlimir}} ({{PLURAL:$2|1 undirflokks|$2 undirflokka}}, {{PLURAL:$3|1 skrá|$3 skrár}})',
'search-result-score' => 'Gildi: $1%',
'search-redirect' => '(tilvísun $1)',
'search-section' => '(hluti $1)',
'search-suggest' => 'Varstu að leita að: $1',
'search-interwiki-caption' => 'Systurverkefni',
'search-interwiki-default' => '$1 útkomur:',
'search-interwiki-more' => '(fleiri)',
'search-relatedarticle' => 'Tengt',
'mwsuggest-disable' => 'Gera AJAX-uppástungur óvirkar',
'searcheverything-enable' => 'Leita í öllum nafnrýmum',
'searchrelated' => 'tengt',
'searchall' => 'öllum',
'showingresults' => "Sýni {{PLURAL:$1|'''1''' niðurstöðu|'''$1''' niðurstöður}} frá og með #'''$2'''.",
'showingresultsnum' => "Sýni {{PLURAL:$3|'''$3''' niðurstöðu|'''$3''' niðurstöður}} frá og með #<b>$2</b>.",
'showingresultsheader' => "{{PLURAL:$5|Niðurstaða '''$1''' af '''$3'''|Niðurstöður'''$1 - $2''' af '''$3'''}} fyrir '''$4'''",
'nonefound' => "'''Athugaðu''': Það er aðeins leitað í sumum nafnrýmum sjálfkrafa. Prófaðu að setja forskeytið ''all:'' í fyrirspurnina til að leita í öllu efni (þar á meðal notandaspjallsíðum, sniðum, o.s.frv.), eða notaðu tileigandi nafnrými sem forskeyti.",
'search-nonefound' => 'Engar niðurstöður pössuðu við fyrirspurnina.',
'powersearch' => 'Ítarleg leit',
'powersearch-legend' => 'Ítarlegri leit',
'powersearch-ns' => 'Leita í nafnrýmum:',
'powersearch-redir' => 'Lista tilvísanir',
'powersearch-field' => 'Leita að',
'powersearch-togglelabel' => 'Athuga:',
'powersearch-toggleall' => 'Allt',
'powersearch-togglenone' => 'Ekkert',
'search-external' => 'Ytri leit',
'searchdisabled' => '{{SITENAME}}-leit er óvirk.
Þú getur leitað í genum Google á meðan.
Athugaðu að skrár þeirra yfir {{SITENAME}}-efni kunna að vera úreltar.',

# Quickbar
'qbsettings' => 'Valblað',
'qbsettings-none' => 'Sleppa',
'qbsettings-fixedleft' => 'Fast vinstra megin',
'qbsettings-fixedright' => 'Fast hægra megin',
'qbsettings-floatingleft' => 'Fljótandi til vinstri',
'qbsettings-floatingright' => 'Fljótandi til hægri',
'qbsettings-directionality' => 'Lagað, fer eftir því í hvaða átt er skrifað á þínu tungumáli.',

# Preferences page
'preferences' => 'Stillingar',
'mypreferences' => 'Mínar stillingar',
'prefs-edits' => 'Fjöldi breytinga:',
'prefsnologin' => 'Ekki innskráður',
'prefsnologintext' => 'Þú verður að vera <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} skráð(ur) inn]</span> til að breyta notandastillingum.',
'changepassword' => 'Breyta lykilorði',
'prefs-skin' => 'Þema',
'skin-preview' => 'Forskoða',
'datedefault' => 'Sjálfgefið',
'prefs-beta' => 'Stillingar á prufustigi',
'prefs-datetime' => 'Tímasnið og tímabelti',
'prefs-labs' => 'Stillingar á tilraunastigi',
'prefs-user-pages' => 'Notendasíður',
'prefs-personal' => 'Notandaupplýsingar',
'prefs-rc' => 'Nýlegar breytingar',
'prefs-watchlist' => 'Vaktlistinn',
'prefs-watchlist-days' => 'Fjöldi daga sem vaktlistinn nær yfir:',
'prefs-watchlist-days-max' => 'Hámark $1 {{PLURAL:$1|dagur|dagar}}',
'prefs-watchlist-edits' => 'Fjöldi breytinga sem vaktlistinn nær yfir:',
'prefs-watchlist-edits-max' => 'Hámarkstala: 1000',
'prefs-watchlist-token' => 'Tóki vaktlistans:',
'prefs-misc' => 'Aðrar stillingar',
'prefs-resetpass' => 'Breyta lykilorði',
'prefs-changeemail' => 'Breyta netfangi',
'prefs-setemail' => 'Skrá netfang',
'prefs-email' => 'Tölvupóststillingar',
'prefs-rendering' => 'Útlit',
'saveprefs' => 'Vista',
'resetprefs' => 'Endurstilla valmöguleika',
'restoreprefs' => 'Endurheimta allar stillingar',
'prefs-editing' => 'Breytingarflipinn',
'prefs-edit-boxsize' => 'Stærð breytingagluggans.',
'rows' => 'Raðir',
'columns' => 'Dálkar',
'searchresultshead' => 'Leit',
'resultsperpage' => 'Niðurstöður á síðu',
'stub-threshold' => 'Þröskuldur fyrir <a href="#" class="stub">stubbatengla</a> (bæt):',
'stub-threshold-disabled' => 'Óvirkt',
'recentchangesdays' => 'Fjöldi daga sem nýlegar breytingar ná yfir:',
'recentchangesdays-max' => '(hámark $1 {{PLURAL:$1|dag|daga}})',
'recentchangescount' => 'Fjöldi síðna:',
'prefs-help-recentchangescount' => 'Taldar eru með nýlegar breytingar, breytingarskrár og aðgerðarskrár.',
'prefs-help-watchlist-token' => 'Með því að fylla út þennan reit með leynilegum lykli býr til RSS-efnistraum fyrir vaktlistann þinn. Allir sem vita hver lykillinn er geta lesið vaktlistann þinn, svo veldu öruggt eigindargildi.
Hér er gildi sem var valið af handahófi sem þú getur notað: $1',
'savedprefs' => 'Stillingarnar þínar hafa verið vistaðar.',
'timezonelegend' => 'Tímabelti:',
'localtime' => 'Staðartími:',
'timezoneuseserverdefault' => 'Nota sjálfgefið tímabelti ($1)',
'timezoneuseoffset' => 'Annað (tilgreinið tímamismun)',
'timezoneoffset' => 'Hliðrun¹:',
'servertime' => 'Tími netþjóns:',
'guesstimezone' => 'Fylla inn frá vafranum',
'timezoneregion-africa' => 'Afríka',
'timezoneregion-america' => 'Ameríka',
'timezoneregion-antarctica' => 'Suðurskautslandið',
'timezoneregion-arctic' => 'Norðurheimskautið',
'timezoneregion-asia' => 'Asía',
'timezoneregion-atlantic' => 'Atlantshaf',
'timezoneregion-australia' => 'Ástralía',
'timezoneregion-europe' => 'Evrópa',
'timezoneregion-indian' => 'Indlandshaf',
'timezoneregion-pacific' => 'Kyrrahaf',
'allowemail' => 'Virkja tölvupóst frá öðrum notendum',
'prefs-searchoptions' => 'Leit',
'prefs-namespaces' => 'Nafnrými',
'defaultns' => 'Leita í þessum nafnrýmum sjálfgefið:',
'default' => 'sjálfgefið',
'prefs-files' => 'Skrár',
'prefs-custom-css' => 'Sérsniðið CSS-útlit',
'prefs-custom-js' => 'Sérsniðin JavaScript',
'prefs-common-css-js' => 'Sérsniðin útlit fyrir öll þemu:',
'prefs-reset-intro' => 'Þessi síða er til að endurstilla stillingarnar til sjálfgefnum gildum.
Ekki er hægt að taka þessa breytingu til baka.',
'prefs-emailconfirm-label' => 'Staðfesting netfangs:',
'prefs-textboxsize' => 'Stærð breytingarglugga',
'youremail' => 'Netfang:',
'username' => 'Notandanafn:',
'uid' => 'Raðnúmer:',
'prefs-memberingroups' => 'Meðlimur {{PLURAL:$1|hóps|hópa}}:',
'prefs-registration' => 'Nýskráningartími:',
'yourrealname' => 'Fullt nafn:',
'yourlanguage' => 'Viðmótstungumál:',
'yourvariant' => 'Afbrigði efnismáls:',
'prefs-help-variant' => 'Þín sérvalda útgáfa eða réttritun til að birta innihald síðna í.',
'yournick' => 'Undirskrift:',
'prefs-help-signature' => 'Ummæli á spjallsíðum eiga að vera skrifuð undir með "<nowiki>~~~~</nowiki>" sem verður breytt í undirskrift þína og dagsetningu.',
'badsig' => 'Ógild hrá undirskrift. Athugaðu HTML-kóða.',
'badsiglength' => 'Undirskriftin er of löng.
Hún þarf að vera færri en $1 {{PLURAL:$1|stafur|stafir}}.',
'yourgender' => 'Kyn:',
'gender-unknown' => 'Óskilgreint',
'gender-male' => 'Karl',
'gender-female' => 'Kona',
'prefs-help-gender' => 'Valfrjálst: notað til að aðgreina kynin í meldingum hugbúnaðarins. Þessar upplýsingar verða aðgengilegar öllum.',
'email' => 'Tölvupóstur',
'prefs-help-realname' => 'Alvöru nafn er valfrjálst.
Ef þú kýst að gefa það upp, verður það notað til að gefa þér heiður af verkum þínum.',
'prefs-help-email' => 'Tölvupóstfang er valfrjálst, en gerir þér kleift að fá nýtt lykilorð ef þú gleymir lykilorðinu þínu.',
'prefs-help-email-others' => 'Þú getur einnig valið að láta aðra hafa samband við þig með tölvupósti í gegnum tengil á notendasíðu eða notendaspjallsíðu þinni.
Tölvupóstfang þitt er ekki gefið upp þegar aðrir notendur hafa samband við þig.',
'prefs-help-email-required' => 'Þörf er á netfangi.',
'prefs-info' => 'Undirstöðuupplýsingar',
'prefs-i18n' => 'Alþjóðavæðing',
'prefs-signature' => 'Undirskrift',
'prefs-dateformat' => 'Dagasnið',
'prefs-timeoffset' => 'Tímamismunur',
'prefs-advancedediting' => 'Háþróaðir möguleikar',
'prefs-advancedrc' => 'Háþróaðir möguleikar',
'prefs-advancedrendering' => 'Háþróaðir möguleikar',
'prefs-advancedsearchoptions' => 'Háþróaðir möguleikar',
'prefs-advancedwatchlist' => 'Háþróaðir möguleikar',
'prefs-displayrc' => 'Útlitsmöguleikar',
'prefs-displaysearchoptions' => 'Útlitsmöguleikar',
'prefs-displaywatchlist' => 'Útlitsmöguleikar',
'prefs-diffs' => 'Breytingar',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'Netfang virðist vera virkt.',
'email-address-validity-invalid' => 'Settu inn rétt netfang',

# User rights
'userrights' => 'Breyta notandaréttindum',
'userrights-lookup-user' => 'Yfirlit notandahópa',
'userrights-user-editname' => 'Skráðu notandanafn:',
'editusergroup' => 'Breyta notandahópum',
'editinguser' => "Breyti réttindum '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Breyta notandahópum',
'saveusergroups' => 'Vista notandahóp',
'userrights-groupsmember' => 'Meðlimur:',
'userrights-groupsmember-auto' => 'Sjálfvirkt bætt við sem meðlimur í:',
'userrights-groups-help' => 'Þú getur breytt hópunum sem að þessi notandi er í.
* Valinn reitur þýðir að notandinn er í hópnum.
* Óvalinn reitur þýðir að notandinn er ekki í hópnum.
* Stjarnan (*) þýðir að þú getur ekki fært hópinn eftir að þú hefur breytt honum, eða öfugt.',
'userrights-reason' => 'Ástæða:',
'userrights-no-interwiki' => 'Þú hefur ekki leyfi til að breyta notandaréttindum á öðrum wiki-síðum.',
'userrights-nodatabase' => 'Gagnagrunnurinn $1 er ekki til eða ekki staðbundinn.',
'userrights-nologin' => 'Þú verður að [[Special:UserLogin|innskrá]] þig á möppudýraaðgang til að geta útdeilt notandaréttindum.',
'userrights-notallowed' => 'Þinn aðgangur hefur ekki réttindi til að útdeila notandaréttindum.',
'userrights-changeable-col' => 'Hópar sem þú getur breytt',
'userrights-unchangeable-col' => 'Hópar sem þú getur ekki breytt',

# Groups
'group' => 'Hópur:',
'group-user' => 'Notendur',
'group-autoconfirmed' => 'Sjálfkrafa staðfestir notendur',
'group-bot' => 'Vélmenni',
'group-sysop' => 'Stjórnendur',
'group-bureaucrat' => 'Möppudýr',
'group-suppress' => 'Yfirsýn',
'group-all' => '(allir)',

'group-user-member' => '{{GENDER:$1|Notandi}}',
'group-autoconfirmed-member' => '{{GENDER:$1|Sjálfkrafa staðfesting notanda}}',
'group-bot-member' => '{{GENDER:$1|Vélmenni}}',
'group-sysop-member' => '{{GENDER:$1|Stjórnandi}}',
'group-bureaucrat-member' => '{{GENDER:$1|Möppudýr}}',
'group-suppress-member' => '{{GENDER:$1|Umsjón}}',

'grouppage-user' => '{{ns:project}}:Notendur',
'grouppage-autoconfirmed' => '{{ns:project}}:Sjálfkrafa staðfestir notendur',
'grouppage-bot' => '{{ns:project}}:Vélmenni',
'grouppage-sysop' => '{{ns:project}}:Stjórnendur',
'grouppage-bureaucrat' => '{{ns:project}}:Möppudýr',
'grouppage-suppress' => '{{ns:project}}:Umsjón',

# Rights
'right-read' => 'Lesa síður',
'right-edit' => 'Breyta síðum',
'right-createpage' => 'Skapa síður (sem eru ekki spjallsíður)',
'right-createtalk' => 'Skapa spjallsíður',
'right-createaccount' => 'Stofna nýja notandaaðganga',
'right-minoredit' => 'Merkja sem minniháttarbreytingar',
'right-move' => 'Færa síður',
'right-move-subpages' => 'Færa síður með undirsíðum þeirra',
'right-move-rootuserpages' => 'Færa notandasíður',
'right-movefile' => 'Færa skrár',
'right-suppressredirect' => 'Ekki búa til tilvísun frá gamla nafninu þegar síða er færð',
'right-upload' => 'Hlaða inn skrám',
'right-reupload' => 'Yfirrita núverandi skrá',
'right-reupload-own' => 'Yfirrita núverandi skrá sem að ég hlóð inn sjálf(ur)',
'right-reupload-shared' => 'Hunsa skrár á sameiginlegu myndasafni staðbundið',
'right-upload_by_url' => 'Hlaða inn skrám frá vefslóð',
'right-purge' => 'Hreinsa skyndiminni síðu án staðfestingar',
'right-autoconfirmed' => 'Breyta hálfvernduðum síðum',
'right-bot' => 'Eru meðhöndlaðir eins og sjálfvirk aðgerð',
'right-nominornewtalk' => 'Ekki láta minniháttar breytingar á spjallsíðum kveða upp áminningu um ný skilaboð',
'right-apihighlimits' => 'Setja hærri mörk á fjölda API fyrirspurna',
'right-writeapi' => 'Nota API skrifun',
'right-delete' => 'Eyða síðum',
'right-bigdelete' => 'Eyða síðum með stórum breytingaskrám',
'right-deletelogentry' => 'Eyða og endurvekja sérstakar aðgerða færslur',
'right-deleterevision' => 'Eyða og endurvekja sérstaka breytignar á síðum',
'right-deletedhistory' => 'Skoða eyddar færslur úr breytingarskrá, án efnis þeirra',
'right-deletedtext' => 'Sjá eyddan texta og breytingar á milli eyddra útgáfna',
'right-browsearchive' => 'Leita í eyddum síðum',
'right-undelete' => 'Endurvekja eydda síðu',
'right-suppressrevision' => 'Skoða og endurvekja breytingar faldar fyrir stjórnendum',
'right-suppressionlog' => 'Skoða einrænar aðgerðaskrár',
'right-block' => 'Banna öðrum notendum að gera breytingar',
'right-blockemail' => 'Banna notanda að senda tölvupóst',
'right-hideuser' => 'Banna notandanafn, og þannig fela það frá almenningi',
'right-ipblock-exempt' => 'Hunsa bönn vistfanga, sjálfvirk bönn og fjöldabönn',
'right-proxyunbannable' => 'Sneiða hjá sjálfvirkum proxy-bönnum',
'right-unblockself' => 'Afbanna sjálfan sig',
'right-protect' => 'Breyta verndunarstigi og breyta vernduðum síðum',
'right-editprotected' => 'Breyta verndaðar síður (án keðjuverndunar)',
'right-editinterface' => 'Breyta notandaviðmótinu',
'right-editusercssjs' => 'Breyta CSS- og JS-skrám annarra',
'right-editusercss' => 'Breyta CSS-skrám annarra',
'right-edituserjs' => 'Breyta JS-skrám annarra',
'right-rollback' => 'Taka snögglega aftur breytingar síðasta notanda sem breytti síðunni',
'right-markbotedits' => 'Merkja endurtektar breytingar sem vélmennabreytingar',
'right-noratelimit' => 'Sneiða hjá takmörkunum',
'right-import' => 'Flytja inn síður frá öðrum wiki',
'right-importupload' => 'Flytja inn síður frá skráar upphali',
'right-patrol' => 'Merkja breytingar annara sem yfirfarnar',
'right-autopatrol' => 'Egin breytingar merktar sem yfirfarnar',
'right-patrolmarks' => 'Skoða yfirferðir nýlegra breytinga',
'right-unwatchedpages' => 'Skoða lista yfir óvaktaðar síður',
'right-mergehistory' => 'Sameina breytingarskrá síðna',
'right-userrights' => 'Breyta öllum notandaréttindum',
'right-userrights-interwiki' => 'Breyta notandaréttindum annarra notenda á öðrum wiki-verkefnum',
'right-siteadmin' => 'Læsa og aflæsa gagnagrunninum',
'right-override-export-depth' => 'Flytja út síður með greinum þar sem allt að 5 greinar tengja þær saman.',
'right-sendemail' => 'Senda tölvupóst til annara notenda',
'right-passwordreset' => 'Skoða tölvupósta um endurstillingu lykilorðs',

# User rights log
'rightslog' => 'Réttindaskrá notenda',
'rightslogtext' => 'Þetta er skrá yfir breytingar á réttindum notenda.',
'rightslogentry' => 'breytti réttindum $1 frá $2 í $3',
'rightslogentry-autopromote' => 'fékk sjálfvirkt aukin réttindi frá $2 til $3',
'rightsnone' => '(engin)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lesa þessa síðu',
'action-edit' => 'breyta þessari síðu',
'action-createpage' => 'skapa síður',
'action-createtalk' => 'skapa spjallsíður',
'action-createaccount' => 'skapa þennan notandaaðgang',
'action-minoredit' => 'merkja þessa breytingu sem minniháttar',
'action-move' => 'færa þessa síðu',
'action-move-subpages' => 'færa þessa síðu, og undirsíður hennar',
'action-move-rootuserpages' => 'Færa notandasíður',
'action-movefile' => 'færa þessa skrá',
'action-upload' => 'hlaða inn þessari skrá',
'action-reupload' => 'yfirrita þessa skrá',
'action-reupload-shared' => 'Hunsa þessa skrá á sameiginlega myndasafninu',
'action-upload_by_url' => 'hlaða inn þessari skrá frá vefslóð',
'action-writeapi' => 'Nota API skrifun',
'action-delete' => 'eyða þessari síðu',
'action-deleterevision' => 'eyða þessari breytingu',
'action-deletedhistory' => 'skoða breytingaskrá þessarar síðu',
'action-browsearchive' => 'leita í eyddum síðum',
'action-undelete' => 'endurvekja þessa síðu',
'action-suppressrevision' => 'Skoða og endurvekja þessa falda breytingu',
'action-suppressionlog' => 'Skoða þessa einrænu aðgerðarskrá',
'action-block' => 'Banna notandanum að gera breytingar',
'action-protect' => 'breyta verndunarstigum fyrir þessa síðu',
'action-rollback' => 'Taka snögglega aftur breytingar síðasta notanda sem breytti ákveðinni síðu',
'action-import' => 'Flytja inn þessa skrá frá öðrum wiki',
'action-importupload' => 'Flytja inn þessa síðu frá skráar upphali',
'action-patrol' => 'Merkja breytingar annara sem yfirfarnar',
'action-autopatrol' => 'Merkja eigin breytingu sem yfirfarna',
'action-unwatchedpages' => 'Skoða lista yfir óvaktaðar síður',
'action-mergehistory' => 'Sameina breytingarskrá þessarar síðu',
'action-userrights' => 'breyta öllum notandaréttindum',
'action-userrights-interwiki' => 'breyta notandaréttindum annarra notenda á öðrum wiki-verkefnum',
'action-siteadmin' => 'læsa eða opna gagnagrunninn',
'action-sendemail' => 'senda tölvupósta',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|breyting|breytingar}}',
'recentchanges' => 'Nýlegar breytingar',
'recentchanges-legend' => 'Stillingar nýlegra breytinga',
'recentchanges-summary' => 'Hér geturðu fylgst með nýjustu breytingunum.',
'recentchanges-feed-description' => 'Hér er hægt að fylgjast með nýlegum breytingum á {{SITENAME}}.',
'recentchanges-label-newpage' => 'Þessi breyting skapaði nýja síðu',
'recentchanges-label-minor' => 'Þetta er minniháttar breyting',
'recentchanges-label-bot' => 'Þessi breytingar var gerð af vélmenni',
'recentchanges-label-unpatrolled' => 'Þessi breyting hefur ekki verið yfirfarin',
'rcnote' => "Að neðan {{PLURAL:$1|er '''1''' breyting|eru síðustu '''$1''' breytingar}} síðast {{PLURAL:$2|liðinn dag|liðna '''$2''' daga}}, frá $5, $4.",
'rcnotefrom' => "Að neðan eru breytingar síðan '''$2''' (allt að '''$1''' sýndar).",
'rclistfrom' => 'Sýna breytingar frá og með $1',
'rcshowhideminor' => '$1 minniháttar breytingar',
'rcshowhidebots' => '$1 vélmenni',
'rcshowhideliu' => '$1 innskráða notendur',
'rcshowhideanons' => '$1 óinnskráða notendur',
'rcshowhidepatr' => '$1 vaktaðar breytingar',
'rcshowhidemine' => '$1 mínar breytingar',
'rclinks' => 'Sýna síðustu $1 breytingar síðustu $2 daga<br />$3',
'diff' => 'breyting',
'hist' => 'breytingaskrá',
'hide' => 'Fela',
'show' => 'Sýna',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'v',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|notandi skoðandi|$1 notendur skoðandi}}]',
'rc_categories' => 'Takmark á flokkum (aðskilja með "|")',
'rc_categories_any' => 'Alla',
'rc-change-size-new' => '$1 {{PLURAL:$1|bæt|bæti}} eftir breytingu',
'newsectionsummary' => 'Nýr hluti: /* $1 */',
'rc-enhanced-expand' => 'Sýna upplýsingar (þarfnast JavaScript)',
'rc-enhanced-hide' => 'Fela ítarefni',
'rc-old-title' => 'Upphaflega búin til undir nafninu "$1"',

# Recent changes linked
'recentchangeslinked' => 'Skyldar breytingar',
'recentchangeslinked-feed' => 'Skyldar breytingar',
'recentchangeslinked-toolbox' => 'Skyldar breytingar',
'recentchangeslinked-title' => 'Breytingar tengdar "$1"',
'recentchangeslinked-noresult' => 'Engar breytingar á tengdum síðum á þessu tímabili.',
'recentchangeslinked-summary' => "Þetta er listi yfir nýlega gerðar breytingar á síðum sem tengt er í frá tilgreindri síðu (eða á meðlimum úr tilgreindum flokki).
Síður á [[Special:Watchlist|vaktlistanum þínum]] eru '''feitletraðar'''.",
'recentchangeslinked-page' => 'Nafn á síða:',
'recentchangeslinked-to' => 'Sýna breytingar á síðum sem tengjast uppgefinni síðu í staðinn',

# Upload
'upload' => 'Hlaða inn skrá',
'uploadbtn' => 'Hlaða inn skrá',
'reuploaddesc' => 'Aftur á innhlaðningarformið.',
'upload-tryagain' => 'Sendu breytta myndlýsingu',
'uploadnologin' => 'Óinnskráð(ur)',
'uploadnologintext' => 'Þú verður að vera [[Special:UserLogin|skráð(ur) inn]]
til að hlaða inn skrám.',
'upload_directory_missing' => 'Mappa upphlaða ($1) er týnd og vefþjónninn gat ekki búið hana til.',
'upload_directory_read_only' => 'Mistókst að skrifa í möppu upphlaða ($1) á vefþjóni.',
'uploaderror' => 'Villa í innhlaðningu',
'upload-recreate-warning' => "'''Viðvörun: Skrá með þessu nafni hefur verið eytt eða færð.'''

Síðasta færsla skráarinnar úr bönnunarskrá og flutningskrá er sýnd hér fyrir neðan til skýringar:",
'uploadtext' => "Notaðu eyðublaðið hér fyrir neðan til að hlaða inn skrám.
Til að skoða eða leita í áður innhlöðnum skrám ferðu á [[Special:FileList|skráarlistann]], (endur)innhlaðnar skrár eru skráðar í [[Special:Log/upload|innhlaðningarskránni]], eyðingar í [[Special:Log/delete|eyðingaskránni]].

* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skrá.jpg]]</nowiki></code>''' til að sýna skránna í fullri upplausn.
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skrá.png|200px|thumb|left|alt-texti]]</nowiki></code>''' til að nota 200 díla upplausn í kassa, sett til vinstri með 'alt text' sem myndlýsingu.
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Skrá.ogg]]</nowiki></code>''' til að tengja í myndina án þess að sýna hana.",
'upload-permitted' => 'Heimilaðar skráargerðir: $1.',
'upload-preferred' => 'Ákjósanlegustu skrárgerðirnar: $1.',
'upload-prohibited' => 'Óheimiluð skrárgerð: $1.',
'uploadlog' => 'innhlaðningarskrá',
'uploadlogpage' => 'Innhlaðningarskrá',
'uploadlogpagetext' => 'Fyrir neðan er listi yfir nýlegustu innhlöðnu skrárnar.
Sjá [[Special:NewFiles|myndasafn nýrra mynda]] fyrir myndrænna yfirlit.',
'filename' => 'Skráarnafn',
'filedesc' => 'Lýsing',
'fileuploadsummary' => 'Ágrip:',
'filereuploadsummary' => 'Skráarbreytingar:',
'filestatus' => 'Staða höfundaréttar:',
'filesource' => 'Heimild:',
'uploadedfiles' => 'Hlóð inn skráunum',
'ignorewarning' => 'Hunsa viðvaranir og vista þessa skrá',
'ignorewarnings' => 'Hunsa allar viðvaranir',
'minlength1' => 'Skráarnöfn þurfa að vera að minnsta kosti einn stafur að lengd',
'illegalfilename' => 'Skráarnafnið „$1“ inniheldur stafi sem eru ekki leyfðir í síðutitlum.
Gjörðu svo vel og endurnefndu skrána og hladdu henni inn aftur.',
'filename-toolong' => 'Skráarnöfn mega ekki vera lengri en 240 bæt.',
'badfilename' => 'Skáarnafninu hefur verið breytt í „$1“.',
'filetype-mime-mismatch' => 'Skráarendingin ".$1" samræmist ekki MIME gerð skráarinnar ($2).',
'filetype-badmime' => 'Skrárir af MIME-gerðinni „$1“ er ekki leyfilegt að hlaða inn.',
'filetype-bad-ie-mime' => 'Mistókst að hlaða inn skrá því Internet Explorer myndi uppgvötva hana sem "$1" sem er óheimil og mögulega hættulegt skráarsnið.',
'filetype-unwanted-type' => "'''„.$1“''' er óæskileg skráargerð.
{{PLURAL:$3|Ákjósanleg skráargerð er|Ákjósanlegar skráargerðir eru}} $2.",
'filetype-banned-type' => "'''„.$1“''' {{PLURAL:$4|er ekki leifileg skráargerð|eru ekki leifilegar skráargerðir}}.
{{PLURAL:$3|Leyfileg skráargerð er|Leyfilegar skráargerðir eru}} $2.",
'filetype-missing' => 'Skráin hefur engan viðauka (dæmi ".jpg").',
'empty-file' => 'Skráin sem þú valdir var tóm.',
'file-too-large' => 'Skráin sem þú valdir er of stór.',
'filename-tooshort' => 'Skráarnafnið er of stutt',
'filetype-banned' => 'Þessi skráarending er bönnuð.',
'verification-error' => 'Þessi skrá stóðst ekki sannprófun.',
'hookaborted' => 'Viðbót hætti við breytingu þína.',
'illegal-filename' => 'Þetta skráarnafn er ekki leyft.',
'overwrite' => 'Óheimilt er að skrifa yfir skrá sem er þegar til.',
'unknown-error' => 'Óþekkt villa kom upp.',
'tmp-create-error' => 'Gat ekki búið til tímabundna skrá.',
'tmp-write-error' => 'Villa við skrifun tímabundinnar skrár.',
'large-file' => 'Það er mælt með að skrár séu ekki stærri en $1; þessi skrá er $2.',
'largefileserver' => 'Þessi skrá er of stór. Vefþjónninn getur ekki tekið við skránni.',
'emptyfile' => 'Skráin sem þú hlóðst inn virðist vera tóm.
Þetta gæti verið vegna ásláttarvillu í skráarnafninu.
Vinsamlegast athugaðu hvort þú viljir hlaða skránni inn.',
'windows-nonascii-filename' => 'Þessi wiki styður ekki skráarnöfn með sérstökum stöfum',
'fileexists' => 'Skrá með þessu nafni er þegar til, skoðaðu <strong>[[:$1]]</strong> ef þú ert óviss um hvort þú viljir breyta henni, ekki verður skrifað yfir gömlu skránna hlaðiru inn nýrri með sama nafni heldur verður núverandi útgáfa geymd í útgáfusögu.
[[$1|thumb]]',
'filepageexists' => 'Myndasíðan fyrir þessa síðu hefur þegar verið búin til <strong>[[:$1]]</strong>, en engin skrá er til með þessu nafni.
Lýsingin sem þú skrifaðir verður ekki birt á myndasíðunni.
Til þess að lýsingin geti birst á síðunni, þá þarft þú að breyta síðunni sérstaklega.
[[$1|thumb]]',
'fileexists-extension' => 'Skrá með svipuðu nafni er til: [[$2|thumb]]
*Nafn skráarinnar sem hlaða á inn: <strong>[[:$1]]</strong>
*Nafn skráarinnar sem er þegar til: <strong>[[:$2]]</strong>
Vinsamlegast veldu annað skráarnafn.',
'fileexists-thumbnail-yes' => 'Skráin virðist vera smámynd [[$1|thumb]]
Vinsamlegast athugaðu skránna <strong>[[:$1]]</strong>.
Ef skráin er sama myndin í upprunalegri stærð er ekki þörf á annari smámynd.',
'file-thumbnail-no' => 'Skráin er líklega smámynd, því skráarnafnið byrjar á <strong>$1</strong>.
Ef skráin er í fullri upplausn haltu þá áfram að hlaða henni inn, en ef ekki breyttu þá skráarnafninu.',
'fileexists-forbidden' => 'Skrá með þessu nafni er þegar til og ekki er hægt að skrifa yfir skránna.
Ef þú villt hlaða inn skránni þinni engu að síður, farðu þá til baka og veldu annað skráarnafn.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Skrá með þessu nafni er þegar til í sameiginlega myndasafninu.
Ef þú villt hlaða inn skránni þinni engu að síður, farðu þá til baka og veldu annað skráarnafn.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Þessi skrá er afrit eftirfarandi {{PLURAL:$1|skráar|skráa}}:',
'file-deleted-duplicate' => 'Afriti þessarar skráar ([[:$1]]) hefur verið eytt.
Þú ættir að fara yfir eyðingarsögu skráarinnar áður en þú velur að hlaða skránni aftur inn.',
'uploadwarning' => 'Aðvörun',
'uploadwarning-text' => 'Vinsamlegast breyttu myndalýsingunni hér fyrir neðan og reyndu aftur.',
'savefile' => 'Vista',
'uploadedimage' => 'hlóð inn „[[$1]]“',
'overwroteimage' => 'hlóð inn nýrri útgáfu af "[[$1]]"',
'uploaddisabled' => 'Lokað er fyrir að hlaða inn myndum.',
'copyuploaddisabled' => 'Lokað er fyrir að hlaða inn myndum frá vefslóð.',
'uploadfromurl-queued' => 'Upphlaðið þitt hefur verið sett í biðröð.',
'uploaddisabledtext' => 'Lokað er fyrir að hlaða inn skrám.',
'php-uploaddisabledtext' => 'Skráar upphlöð eru óvirk í PHP.
Vinsamlegast athugaðu stillinguna í file_uploads.',
'uploadscripted' => 'Þetta skjal inniheldur (X)HTML eða forskriftu sem gæti valdið villum í vöfrum.',
'uploadvirus' => 'Skráin inniheldur veiru! Nánari upplýsingar: $1',
'uploadjava' => 'Þessi skrá er ZIP skrá sem inniheldur Java .class skráarsnið.
Upphlöðun Java skráa er óheimil, því þær hunsa öryggis hömlur.',
'upload-source' => 'Upprunaleg skrá',
'sourcefilename' => 'Upprunalegt skráarnafn:',
'sourceurl' => 'Uppruni:',
'destfilename' => 'Móttökuskráarnafn:',
'upload-maxfilesize' => 'Hámarks skráarstærð: $1',
'upload-description' => 'Myndlýsing',
'upload-options' => 'Valmöguleikar fyrir upphöl',
'watchthisupload' => 'Vakta þessa skrá',
'filewasdeleted' => 'Skrá af sama nafni hefur áður verið hlaðið inn og síðan eytt. Þú ættir að athuga $1 áður en þú hleður skránni inn.',
'filename-bad-prefix' => "Sráarnafnið lýsir ekki skránni, heldur var það búið til af myndavélinni, því það byrjar á '''\"\$1\"'''.
Veldu lýsandi nafn fyrir skránna og reyndu aftur.",
'upload-success-subj' => 'Innhlaðning tókst',
'upload-success-msg' => 'Upphlöðun frá [$2] tókst. Það er aðgengilegt hér: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Vandamál við upphleðslu skráarinnar',
'upload-failure-msg' => 'Upphlaðið frá [$2] mistókst:

$1',
'upload-warning-subj' => 'Aðvörun',
'upload-warning-msg' => 'Upphal þitt [$2] mistókst. Þú getur farið aftur á [[Special:Upload/stash/$1|upphlaðsviðmótið]] og leiðrétt villuna.',

'upload-proto-error' => 'Vitlaus samskiptaregla',
'upload-proto-error-text' => 'Upphlöðun frá öðrum vefþjón þarfnast vefslóðar sem byrjar á <code>http://</code> eða <code>ftp://</code>.',
'upload-file-error' => 'Innri villa',
'upload-file-error-text' => 'Innri villa: Gat ekki búið til tímabundna skrá á vefþjóni.
Vinsamlegast hafðu samband við [[Special:ListUsers/sysop|möppudýr]].',
'upload-misc-error' => 'Óþekkt innhleðsluvilla',
'upload-misc-error-text' => 'Upphal þitt mistókst vegna óþekktrar villu.
Athugaðu hvort vefslóðin sé rétt og aðgengileg og að því loknu reyndu aftur.
Ef vandamálið lagast ekki, hafðu samband við [[Special:ListUsers/sysop|stjórnanda]].',
'upload-too-many-redirects' => 'Vefslóðin inniheldur of margar tilvísanir.',
'upload-unknown-size' => 'Óþekkt stærð',
'upload-http-error' => 'HTTP villa kom upp við upphal skráarinnar: $1',
'upload-copy-upload-invalid-domain' => 'Lokað er fyrir afritun skráa frá öðrum vefþjón á þessu vefsvæði.',

# File backend
'backend-fail-backup' => 'Öryggisafritun skráarinnar $1 mistókst.',
'backend-fail-notexists' => 'Skráin $1 er ekki til.',
'backend-fail-notsame' => 'Ólík skrá er þegar til á $1.',
'backend-fail-invalidpath' => '$1 er ekki gildur geymslustaður.',
'backend-fail-delete' => 'Mistókst að eyða skránni $1.',
'backend-fail-alreadyexists' => 'Skráin $1 er þegar til.',
'backend-fail-store' => 'Mistókst að vista skrá $1 á $2.',
'backend-fail-copy' => 'Mistókst að afrita skjal $1 á $2.',
'backend-fail-move' => 'Mistókst að færa skrá $1 á $2.',
'backend-fail-opentemp' => 'Mistókst að opna tímabundna skrá.',
'backend-fail-writetemp' => 'Gat ekki skrifað í tímabundna skrá.',
'backend-fail-closetemp' => 'Mistókst að loka tímabundinni skrá.',
'backend-fail-read' => 'Mistókst að lesa skrá $1.',
'backend-fail-create' => 'Mistókst að skrifa skrá $1.',
'backend-fail-maxsize' => 'Mistókst að skrifa skránna $1 því hún er stærri en {{PLURAL:$2|eitt bæti|$2 bæti}}.',
'backend-fail-readonly' => 'Gagnabankann "$1" er engöngu hægt að lesa í augnablikinu. Ástæðan sem var gefin er: "\'\'$2\'\'"',
'backend-fail-connect' => 'Mistókst að tengjast gagnabankanum "$1".',
'backend-fail-internal' => 'Óþekkt villa átti sér stað í gagnabankanum "$1".',

# ZipDirectoryReader
'zip-file-open-error' => 'Mistök við opnun skráarinnar fyrir ZIP athuganir.',
'zip-wrong-format' => 'Skráin var ekki ZIP skrá.',
'zip-bad' => 'Þessi ZIP skrá er skemmd eða ólesanleg.
Ekki var hægt að athuga öryggi skráarinnar almennilega.',
'zip-unsupported' => 'Þessi skrá er ZIP skrá sem notar möguleika sem eru ekki studdir af MediaWiki.
Ekki er hægt að athuga öryggi skráarinnar almennilega.',

# Special:UploadStash
'uploadstash' => 'Óútgefnar skrár',
'uploadstash-summary' => 'Þessi síða gefur aðgang að þeim skrám sem hafa verið hlaðið inn (eða eru í biðröð eftir því að vera hlaðið inn) en hafa ekki verið útgefnar. Þessar skrár eru eingöngu sýnilegar þeim notanda sem hlóð þeim inn.',
'uploadstash-clear' => 'Tæma listann',
'uploadstash-nofiles' => 'Þú hefur engar skrár sem eru í bið eftir því að verða útgefnar.',
'uploadstash-badtoken' => 'Þessi aðgerð misheppnaðist, kannski hafa réttindi þín til breytinga runnið út.
Reyndu aftur.',
'uploadstash-errclear' => 'Tæming listans mistókst.',
'uploadstash-refresh' => 'Endurhlaða listann',

# img_auth script messages
'img-auth-accessdenied' => 'Aðgangur óheimill',
'img-auth-nopathinfo' => 'PATH_INFO vantar.
Biðlarinn þínn er ekki stilltur til að gefa upp þessar upplýsingar.
Þær mega vera CGI-byggðar og mega ekki styðja img_auth.
https://www.mediawiki.org/wiki/Manual:Image_Authorization',
'img-auth-nofile' => 'Skráin "$1" er ekki til.',
'img-auth-streaming' => 'Streymi "$1".',
'img-auth-noread' => 'Notandinn hefur ekki rétt til að lesa "$1"',
'img-auth-bad-query-string' => 'Vefslóðin hefur ógildan fyrirspurnar streng.',

# HTTP errors
'http-invalid-url' => 'Vitlaust veffang: $1',
'http-invalid-scheme' => 'Vefslóðir með "$1" forskeyti eru óstuddar.',
'http-request-error' => 'HTTP beiðni mistókst vegna óþekktrar villu.',
'http-read-error' => 'HTTP lesturs villa.',
'http-timed-out' => 'Tímamörk HTTP beiðni rann út.',
'http-curl-error' => 'Villa við að sækja vefslóð: $1',
'http-host-unreachable' => 'Gat ekki náð í vefslóðina',
'http-bad-status' => 'Mistök við HTTP beiðnina: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Gat ekki náð í slóðina',
'upload-curl-error6-text' => 'Mistókst að sækja tilgreinda vefslóð.
Athugaðu hvort vefslóðin sé rétt og vefsíðan sé aðgengileg.',
'upload-curl-error28' => 'Innhleðslutími útrunninn',
'upload-curl-error28-text' => 'Vefsvæðið tók of langan tíma til að svara.
Athugaðu hvort síðan sé aðgengileg, bíddu í smástund og reyndu aftur.
Þú gætir viljað reyna aftur þegar minna álag er á vefþjóninn.',

'license' => 'Leyfisupplýsingar:',
'license-header' => 'Leyfisupplýsingar:',
'nolicense' => 'Ekkert valið',
'license-nopreview' => '(Forskoðun ekki fáanleg)',
'upload_source_url' => '(gild, aðgengileg vefslóð)',
'upload_source_file' => '(skrá á tölvunni þinni)',

# Special:ListFiles
'listfiles-summary' => 'Þessi kerfissíða sýnir allar upphlaðnar skrár.
Þegar hún er síuð ákveðnu notendanafni birtast eingöngu myndir frá honum.',
'listfiles_search_for' => 'Leita að miðilsnafni:',
'imgfile' => 'skrá',
'listfiles' => 'Skráalisti',
'listfiles_thumb' => 'Smámynd',
'listfiles_date' => 'Dagsetning',
'listfiles_name' => 'Nafn',
'listfiles_user' => 'Notandi',
'listfiles_size' => 'Stærð (bæti)',
'listfiles_description' => 'Lýsing',
'listfiles_count' => 'Útgáfur',

# File description page
'file-anchor-link' => 'Skrá',
'filehist' => 'Breytingaskrá skjals',
'filehist-help' => 'Smelltu á dagsetningu eða tímasetningu til að sjá hvernig hún leit þá út.',
'filehist-deleteall' => 'eyða öllu',
'filehist-deleteone' => 'eyða',
'filehist-revert' => 'taka aftur',
'filehist-current' => 'núverandi',
'filehist-datetime' => 'Dagsetning/Tími',
'filehist-thumb' => 'Smámynd',
'filehist-thumbtext' => 'Smámynd útgáfunnar frá $2, kl. $3',
'filehist-nothumb' => 'Engin smámynd',
'filehist-user' => 'Notandi',
'filehist-dimensions' => 'Víddir',
'filehist-filesize' => 'Stærð skráar',
'filehist-comment' => 'Athugasemd',
'filehist-missing' => 'Skrá vantar',
'imagelinks' => 'Skráartenglar',
'linkstoimage' => 'Eftirfarandi {{PLURAL:$1|síða tengist|$1 síður tengjast}} í þessa skrá:',
'linkstoimage-more' => 'Fleiri en $1 {{PLURAL:$1|síða tengist|síður tengjast}} þessari skrá.
Eftirfarandi listi sýnir {{PLURAL:$1|fyrsta myndatengilinn|fyrstu $1 myndatenglana}}.
[[Special:WhatLinksHere/$2|Tæmandi listi]] er til staðar.',
'nolinkstoimage' => 'Engar síður tengja í þessa skrá.',
'morelinkstoimage' => 'Skoða [[Special:WhatLinksHere/$1|fleiri myndatengla]] á þessa skrá.',
'linkstoimage-redirect' => '$1 (tilvísun) $2',
'duplicatesoffile' => 'Eftirfarandi {{PLURAL:$1|skrá er afrit|$1 skrár eru afrit}} af þessari skrá ([[Special:FileDuplicateSearch/$2|Frekari upplýsingar]]):',
'sharedupload' => 'Skrá þessi er af $1, og deilt meðal annarra verkefna og nýtist því þar.',
'sharedupload-desc-there' => 'Skrá þessi er af $1, og deilt meðal annarra verkefna og nýtist því þar.
Gjörðu svo vel og sjáðu [$2 skráarsíðuna þar] fyrir fleiri upplýsingar.',
'sharedupload-desc-here' => 'Skrá þessi er af $1, og deilt meðal annarra verkefna og nýtist því þar.
Hér fyrir neðan er afrit af [$2 skráarsíðunni þar].',
'sharedupload-desc-edit' => 'Þessi skrá er af $1 og gæti verið í notkun á öðrum vefkefnum.
Hentugra væri ef þú gætir breytt lýsingu skráarinnar á [$2 myndasíðu] hennar þar.',
'sharedupload-desc-create' => 'Þessi skrá er af $1 og gæti verið í notkun á öðrum vefkefnum.
Hentugra væri ef þú gætir breytt lýsingu skráarinnar á [$2 myndasíðu] hennar þar.',
'filepage-nofile' => 'Engin skrá er til með þessu nafni.',
'filepage-nofile-link' => 'Engin skrá er til með þessu nafni, en þú getur [$1 hlaðið henni inn].',
'uploadnewversion-linktext' => 'Hlaða inn nýrri útgáfu af þessari skrá',
'shared-repo-from' => 'frá $1',
'shared-repo' => 'sameiginlegu myndasafni',

# File reversion
'filerevert' => 'Taka aftur $1',
'filerevert-legend' => 'Taka aftur skrá',
'filerevert-intro' => "Þú ert í þann mund að breyta skránni '''[[Media:$1|$1]]''' aftur til [$4 útgáfu frá $3, $2].",
'filerevert-comment' => 'Ástæða:',
'filerevert-defaultcomment' => 'Breytt til útgáfu $2 $1',
'filerevert-submit' => 'Taka aftur',
'filerevert-success' => "'''[[Media:$1|$1]]''' var breytt aftur til [$4 útgáfu frá $3, $2].",
'filerevert-badversion' => 'Það er ekki til nein fyrri staðbundin útgáfa af þessari skrá með þessum tímastimpli.',

# File deletion
'filedelete' => 'Eyði „$1“',
'filedelete-legend' => 'Eyða skrá',
'filedelete-intro' => "Þú ert að eyða '''[[Media:$1|$1]]''' ásamt breytingarskrá hennar.",
'filedelete-intro-old' => "Þú ert í þann mund að eyða útgáfu '''[[Media:$1|$1]]''' frá [$4 $3, kl. $2].",
'filedelete-comment' => 'Ástæða:',
'filedelete-submit' => 'Eyða',
'filedelete-success' => "'''$1''' hefur verið eytt.",
'filedelete-success-old' => "Útgáfu '''[[Media:$1|$1]]''' frá $3, kl. $2 hefur verið eytt.",
'filedelete-nofile' => "'''$1''' er ekki til.",
'filedelete-nofile-old' => 'Ekkert skjalasafn af $1 er til með tilgreindum táknum.',
'filedelete-otherreason' => 'Aðrar/fleiri ástæður:',
'filedelete-reason-otherlist' => 'Önnur ástæða',
'filedelete-reason-dropdown' => '* Algengar eyðingarástæður
** Höfundarréttarbrot
** Endurtekin skrá',
'filedelete-edit-reasonlist' => 'Eyðingarástæður',
'filedelete-maintenance' => 'Á meðan viðhaldi stendur er lokað fyrir eyðingu og endurvakningu skráa.',
'filedelete-maintenance-title' => 'Mistókst að eyða skrá',

# MIME search
'mimesearch' => 'MIME-leit',
'mimesearch-summary' => 'Þessi síða gerir þér kleift að leita eftir skrám eftir MIME-gerð þeirra.

Leitarstrengurinn á að vera á þessu formi: efnistag/myndasnið, t.d. <code>image/jpeg</code>.',
'mimetype' => 'MIME-tegund:',
'download' => 'Hlaða niður',

# Unwatched pages
'unwatchedpages' => 'Óvaktaðar síður',

# List redirects
'listredirects' => 'Tilvísanir',

# Unused templates
'unusedtemplates' => 'Ónotuð snið',
'unusedtemplatestext' => 'Þetta er listi yfir allar síður í {{ns:snið}} nafnrýminu sem ekki eru notaðar í neinum öðrum síðum. Munið að gá að öðrum tenglum í sniðin áður en þeim er eytt.',
'unusedtemplateswlh' => 'aðrir tenglar',

# Random page
'randompage' => 'Handahófsvalin grein',
'randompage-nopages' => 'Það eru engar síður í {{PLURAL:$2|nafnrýminu|nafnrýmunum}}: $1.',

# Random redirect
'randomredirect' => 'Handahófsvalin tilvísun',
'randomredirect-nopages' => 'Það eru engar tilvísanir í nafnrýminu „$1“.',

# Statistics
'statistics' => 'Tölfræði',
'statistics-header-pages' => 'Síðutölfræði',
'statistics-header-edits' => 'Breytingatölfræði',
'statistics-header-views' => 'Uppflettitölfræði',
'statistics-header-users' => 'Notandatölfræði',
'statistics-header-hooks' => 'Önnur tölfræði',
'statistics-articles' => 'Greinar alls',
'statistics-pages' => 'Síður',
'statistics-pages-desc' => 'Allar síður wiki-verkefnisins, þar á meðal spjallsíður, tilvísanir o.fl.',
'statistics-files' => 'Skráafjöldi',
'statistics-edits' => 'Síðubreytingar frá því {{SITENAME}} byrjaði',
'statistics-edits-average' => 'Meðal breytingafjöldi á síðu',
'statistics-views-total' => 'Uppflettingar alls',
'statistics-views-total-desc' => 'Flettingar á síður sem eru ekki til eða kerfisíður eru ekki innifaldar.',
'statistics-views-peredit' => 'Uppflettingar á hverja breytingu (meðaltal)',
'statistics-users' => 'Skráðir  [[Special:ListUsers|notendur]]',
'statistics-users-active' => 'Virkir notendur',
'statistics-users-active-desc' => 'Notendur sem hafa framkvæmt aðgerð {{PLURAL:$1|síðastliðin dag|síðastliðna $1 daga}}',
'statistics-mostpopular' => 'Mest skoðuðu síður',

'disambiguations' => 'Síður sem tengja á aðgreiningarsíður',
'disambiguationspage' => 'Template:Aðgreining',
'disambiguations-text' => "Þessar síður innihalda tengla á svokallaðar „'''aðgreiningarsíður'''“.
Laga ætti tenglanna og láta þá vísa á rétta síðu.<br />
Farið er með síðu sem aðgreiningarsíðu ef að hún inniheldur snið sem vísað er í frá [[MediaWiki:Disambiguationspage]]",

'doubleredirects' => 'Tvöfaldar tilvísanir',
'doubleredirectstext' => 'Þessi síða er listi yfir skrár sem eru tilvísanir á aðrar tilvísanir.
Hver lína inniheldur tengla á fyrstu og aðra tilvísun auk þeirrar síðu sem seinni tilvísunin beinist að, sem er oftast sú síða sem allar tilvísanirnar eiga að benda á.
<del>Yfirstrikaðar</del> færslur hafa verið leiðréttar.',
'double-redirect-fixed-move' => '[[$1]] hefur verið færð.
Hún er tilvísun á [[$2]].',
'double-redirect-fixed-maintenance' => 'Laga tvöfalda tilvísun frá [[$1]] til [[$2]].',
'double-redirect-fixer' => 'Laga tilvísun',

'brokenredirects' => 'Brotnar tilvísanir',
'brokenredirectstext' => 'Eftirfarandi tilvísanir vísa á síður sem ekki eru til:',
'brokenredirects-edit' => 'breyta',
'brokenredirects-delete' => 'eyða',

'withoutinterwiki' => 'Síður án tungumálatengla',
'withoutinterwiki-summary' => 'Eftirfarandi síður tengja ekki í önnur tungumál:',
'withoutinterwiki-legend' => 'Forskeyti',
'withoutinterwiki-submit' => 'Sýna',

'fewestrevisions' => 'Greinar með fæstar breytingar',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bæt|bæti}}',
'ncategories' => '$1 {{PLURAL:$1|flokkur|flokkar}}',
'ninterwikis' => '$1 {{PLURAL:$1|tungumálatengill|tungumálatenglar}}',
'nlinks' => '$1 {{PLURAL:$1|tengill|tenglar}}',
'nmembers' => '$1 {{PLURAL:$1|meðlimur|meðlimir}}',
'nrevisions' => '$1 {{PLURAL:$1|breyting|breytingar}}',
'nviews' => '$1 {{PLURAL:$1|fletting|flettingar}}',
'nimagelinks' => 'Ítengd á $1 {{PLURAL:$1|síðu|síðum}}',
'ntransclusions' => 'Ítengd á $1 {{PLURAL:$1|síðu|síðum}}',
'specialpage-empty' => 'Þessi síða er tóm.',
'lonelypages' => 'Munaðarlausar síður',
'lonelypagestext' => 'Eftirfarandi síður eru munaðarlausar á {{SITENAME}}.',
'uncategorizedpages' => 'Óflokkaðar síður',
'uncategorizedcategories' => 'Óflokkaðir flokkar',
'uncategorizedimages' => 'Óflokkaðar skrár',
'uncategorizedtemplates' => 'Óflokkuð snið',
'unusedcategories' => 'Ónotaðir flokkar',
'unusedimages' => 'Munaðarlausar skrár',
'popularpages' => 'Vinsælar síður',
'wantedcategories' => 'Eftirsóttir flokkar',
'wantedpages' => 'Eftirsóttar síður',
'wantedpages-badtitle' => 'Ógildur titill í listanum: $1',
'wantedfiles' => 'Eftirsóttar skrár',
'wantedfiletext-cat' => 'Eftirfarandi skrár eru í notkun en eru ekki til. Skrár frá skráarsöfnum gætu verið á listanum þrátt fyrir að þær séu til. Allar ástæðulausar færslur verða <del>yfirstrikaðar</del>. Þar að auki, eru síður sem innifala skrár sem eru ekki til á lista [[:$1]].',
'wantedfiletext-nocat' => 'Eftirfarandi skrár eru í notkun en eru ekki til. Skrár frá srkáarsöfnum gætu verið á listanum þrátt fyrir að þær séu til. Allar ástæðulausar færslur verða <del>yfirstrikaðar</del>.',
'wantedtemplates' => 'Eftirsótt snið',
'mostlinked' => 'Mest ítengdu síður',
'mostlinkedcategories' => 'Mest ítengdu flokkar',
'mostlinkedtemplates' => 'Mest ítengdu snið',
'mostcategories' => 'Mest flokkaðar greinar',
'mostimages' => 'Mest ítengdu skrárnar',
'mostinterwikis' => 'Síður með flestm tungumálatenglum',
'mostrevisions' => 'Síður eftir fjölda breytinga',
'prefixindex' => 'Allar síður með forskeyti',
'prefixindex-namespace' => 'Allar síður með forskeyti ($1 nafnrými)',
'shortpages' => 'Stuttar síður',
'longpages' => 'Langar síður',
'deadendpages' => 'Botnlangar',
'deadendpagestext' => 'Eftirfarandi síður tengjast ekki við aðrar síður á {{SITENAME}}.',
'protectedpages' => 'Verndaðar síður',
'protectedpages-indef' => 'Aðeins óendanlegar verndanir',
'protectedpages-cascade' => 'Keðjuverndun eingöngu',
'protectedpagestext' => 'Eftirfarandi síður hafa verið verndaðar svo ekki sé hægt að breyta þeim eða færa þær',
'protectedpagesempty' => 'Engar síður eru verndaðar með þessum stikum.',
'protectedtitles' => 'Verndaðir titlar',
'protectedtitlestext' => 'Eftirfarandi titlar eru verndaðir gegn því að vera skapaðir',
'protectedtitlesempty' => 'Engir titlar eru verndaðir með þessum stikum.',
'listusers' => 'Notendalisti',
'listusers-editsonly' => 'Sýna eingöngu notendur með breytingar',
'listusers-creationsort' => 'Raða eftir stofndegi',
'usereditcount' => '$1 {{PLURAL:$1|breyting|breytingar}}',
'usercreated' => '{{GENDER:$3|Stofnað|}} $1 $2',
'newpages' => 'Nýjustu greinar',
'newpages-username' => 'Notandanafn:',
'ancientpages' => 'Elstu síður',
'move' => 'Færa',
'movethispage' => 'Færa þessa síðu',
'unusedimagestext' => 'Eftirfarandi skrár eru til, en eru ekki notaðar í greinum.
Vinsamlegast athugið að aðrar vefsíður gætu tengt beint í skrár héðan, svo að þær gætu komið fram á þessum lista þrátt fyrir að vera í notkun.',
'unusedcategoriestext' => 'Þessir flokkar eru til en engar síður eða flokkar eru í þeim.',
'notargettitle' => 'Ekkert skotmark',
'notargettext' => 'Villa: Engin síða eða notandi tilgreind til að nota þennan möguleika á.',
'nopagetitle' => 'Síðan er ekki til',
'nopagetext' => 'Síðan sem á að færa frá er ekki til.',
'pager-newer-n' => '{{PLURAL:$1|nýrri 1|nýrri $1}}',
'pager-older-n' => '{{PLURAL:$1|1 eldri|$1 eldri}}',
'suppress' => 'Yfirsýn',
'querypage-disabled' => 'Þessi kerfisíða er óvirk til að minnka ekki afköst vefþjónsins.',

# Book sources
'booksources' => 'Bókaleit',
'booksources-search-legend' => 'Leita að bókaverslunum',
'booksources-go' => 'Áfram',
'booksources-text' => 'Fyrir neðan er listi af tenglum í aðrar síður sem selja nýjar og notaðar bækur og gætu einnig haft nánari upplýsingar í sambandi við bókina sem þú varst að leita að:',
'booksources-invalid-isbn' => 'ISBN gildið virðist ekki vera gilt; leitaðu eftir villum við innslátt eða afritun gildisins frá upsprettu þess.',

# Special:Log
'specialloguserlabel' => 'Gerandi:',
'speciallogtitlelabel' => 'Beinist að (titill eða notandi):',
'log' => 'Aðgerðaskrár',
'all-logs-page' => 'Allar aðgerðir',
'alllogstext' => 'Safn allra aðgerðaskráa {{SITENAME}}.
Þú getur takmarkað listann með því að velja tegund aðgerðaskráar, notandanafn, eða síðu.',
'logempty' => 'Engin slík aðgerð fannst.',
'log-title-wildcard' => 'Leita að titlum sem byrja á þessum texta',
'showhideselectedlogentries' => 'Sýna/fela valdar aðgerða færslur',

# Special:AllPages
'allpages' => 'Allar síður',
'alphaindexline' => '$1 til $2',
'nextpage' => 'Næsta síða ($1)',
'prevpage' => 'Fyrri síða ($1)',
'allpagesfrom' => 'Sýna síður frá og með:',
'allpagesto' => 'Sýna síður sem enda á:',
'allarticles' => 'Allar greinar',
'allinnamespace' => 'Allar síður ($1 nafnrými)',
'allnotinnamespace' => 'Allar síður (ekki í $1 nafnrýminu)',
'allpagesprev' => 'Síðast',
'allpagesnext' => 'Næst',
'allpagessubmit' => 'Áfram',
'allpagesprefix' => 'Sýna síður með forskeytinu:',
'allpagesbadtitle' => 'Ekki var hægt að búa til grein með þessum titli því hann innihélt einn eða fleiri stafi sem ekki er hægt að nota í titlum.',
'allpages-bad-ns' => '{{SITENAME}} hefur ekki nafnrými „$1“.',
'allpages-hide-redirects' => 'Fela tilvísanir',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Þú ert að skoða útgáfu síðunnar úr skyndiminni, sem getur verið allt að $1 gömul.',
'cachedspecial-viewing-cached-ts' => 'Þetta er útgáfa þessarar síðu úr skyndiminni og sem endurspeglar ekki endilega núverandi ástand.',
'cachedspecial-refresh-now' => 'Skoða síðustu',

# Special:Categories
'categories' => 'Flokkar',
'categoriespagetext' => 'Eftirfarandi {{PLURAL:$1|flokkur inniheldur|flokkar innihalda}} síður eða skrár.
[[Special:UnusedCategories|Ónotaðir flokkar]] birtast ekki hér.
Sjá einnig [[Special:WantedCategories|eftirsótta flokka]].',
'categoriesfrom' => 'Sýna flokka frá:',
'special-categories-sort-count' => 'raða eftir fjölda',
'special-categories-sort-abc' => 'raða í stafrófsröð',

# Special:DeletedContributions
'deletedcontributions' => 'Eyddar breytingar notanda',
'deletedcontributions-title' => 'Eyddar breytingar notanda',
'sp-deletedcontributions-contribs' => 'Framlög',

# Special:LinkSearch
'linksearch' => 'Leita að útværum tenglum',
'linksearch-pat' => 'Leitarmynstur:',
'linksearch-ns' => 'Nafnrými:',
'linksearch-ok' => 'Leita',
'linksearch-text' => 'Algildistafir eins og "*.wikipedia.org" eru leyfðir.<br />
Stafurinn þarf í minnsta kosti að innihalda rótarlén, eins og "*.org"
Studdar samskiptareglur: <code>$1</code> (ekki bæta neinum af þessum í leitina)',
'linksearch-line' => 'Tengt er í $1 á síðunni $2',
'linksearch-error' => 'Algildistafir mega engöngu birtast í upphafi vefslóðarinnar.',

# Special:ListUsers
'listusersfrom' => 'Sýna notendur sem byrja á:',
'listusers-submit' => 'Sýna',
'listusers-noresult' => 'Enginn notandi fannst.',
'listusers-blocked' => '(bannaður)',

# Special:ActiveUsers
'activeusers' => 'Virkir notendur',
'activeusers-intro' => 'Þetta er listi yfir notendur sem hafa verið virkir {{PLURAL:$1|síðasta|síðustu}} $1 {{PLURAL:$1|dag|daga}}.',
'activeusers-count' => '$1 {{PLURAL:$1|breyting|breytingar}} á {{PLURAL:$3|síðastliðnum degi|síðustu $3 dögum}}',
'activeusers-from' => 'Sýna notendur sem byrja á:',
'activeusers-hidebots' => 'Fela vélmenni',
'activeusers-hidesysops' => 'Fela möppudýr',
'activeusers-noresult' => 'Enginn notandi fannst.',

# Special:Log/newusers
'newuserlogpage' => 'Skrá yfir nýja notendur',
'newuserlogpagetext' => 'Þetta er skrá yfir nýskráða notendur.',

# Special:ListGroupRights
'listgrouprights' => 'Notandahópréttindi',
'listgrouprights-summary' => 'Hér er listi yfir notendahópa á þessum wiki, með þeirra réttindum. 
Það gæti verið til síða með [[{{MediaWiki:Listgrouprights-helppage}}|frekari upplýsingar]] um einstök réttindi.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Veitt réttindi</span>
* <span class="listgrouprights-revoked">Afturkölluð réttindi</span>',
'listgrouprights-group' => 'Hópur',
'listgrouprights-rights' => 'Réttindi',
'listgrouprights-helppage' => 'Help:Hópréttindi',
'listgrouprights-members' => '(listi yfir meðlimi)',
'listgrouprights-addgroup' => 'Bæta við meðlimum í {{PLURAL:$2|hópinn|hópana}}: $1',
'listgrouprights-removegroup' => 'Fjarlægja meðlimi úr {{PLURAL:$2|hópinum|hópunum}}: $1',
'listgrouprights-addgroup-all' => 'Bæta meðlimum við alla hópa',
'listgrouprights-removegroup-all' => 'Fjarlægja meðlimi úr öllum hópum',
'listgrouprights-addgroup-self' => 'Bæta sjálfum sér í {{PLURAL:$2|hópinn|hópana}}: $1',
'listgrouprights-removegroup-self' => 'Fjarlægja sjálfan sig úr {{PLURAL:$2|hópinum|hópunum}}: $1',
'listgrouprights-addgroup-self-all' => 'Bæta sjálfum sér í alla hópa',
'listgrouprights-removegroup-self-all' => 'Fjarlægja sjálfan sig úr öllum hópum',

# E-mail user
'mailnologin' => 'Ekkert netfang til að senda á',
'mailnologintext' => 'Þú verður að vera [[Special:UserLogin|innskráð(ur)]] auk þess að hafa gilt netfang í [[Special:Preferences|stillingunum]] þínum til að senda tölvupóst til annara notenda.',
'emailuser' => 'Senda þessum notanda tölvupóst',
'emailuser-title-target' => 'Sendu þessum {{GENDER:$1|notanda}} tölvupóst',
'emailuser-title-notarget' => 'Senda tölvupóst',
'emailpage' => 'Senda tölvupóst',
'emailpagetext' => 'Hafi notandinn tilgreint netfang í stillingunum sínum er hægt að senda póst til {{GENDER:$1|hans|hennar|hans}} hér.
Póstfangið sem þú tilgreindir í [[Special:Preferences|stillingunum þínum]] birtist í "Frá:" hluta tölvupóstsins, svo að viðtakandi hans geti svarað beint til þín.',
'usermailererror' => 'Póst hlutur skilaði villu:',
'defemailsubject' => '{{SITENAME}} skilaboð frá notandanum "$1"',
'usermaildisabled' => 'Netfang notenda er óvirkt',
'usermaildisabledtext' => 'Þú getur ekki sent tölvupóst til annara notenda á þessum wiki',
'noemailtitle' => 'Ekkert póstfang',
'noemailtext' => 'Þessi notandi hefur ekki tilgreint gilt netfang.',
'nowikiemailtitle' => 'Tölvupóstur óheimill',
'nowikiemailtext' => 'Þessi notandi hefur valið að fá engan tölvupóst frá öðrum notendum.',
'emailnotarget' => 'Notendanafn er ekki til eða ógilt fyrir þennan viðtakanda.',
'emailtarget' => 'Tilgreindu notendanafn viðtakanda',
'emailusername' => 'Notandanafn:',
'emailusernamesubmit' => 'Senda',
'email-legend' => 'Senda tölvupóst á annan {{SITENAME}}-notanda',
'emailfrom' => 'Frá:',
'emailto' => 'Til:',
'emailsubject' => 'Fyrirsögn:',
'emailmessage' => 'Skilaboð:',
'emailsend' => 'Senda',
'emailccme' => 'Senda mér tölvupóst með afriti af mínum skeytum.',
'emailccsubject' => 'Afrit af skilaboðinu þínu til $1: $2',
'emailsent' => 'Sending tókst',
'emailsenttext' => 'Skilaboðin þín hafa verið send.',
'emailuserfooter' => 'Þessi tölvupóstur var sendur af $1 til $2 með möguleikanum "Senda notanda tölvupóst" á {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Skil eftir meldingu.',
'usermessage-editor' => 'Meldinga sendiboði',

# Watchlist
'watchlist' => 'Vaktlistinn',
'mywatchlist' => 'Vaktlisti',
'watchlistfor2' => 'Eftir $1 $2',
'nowatchlist' => 'Vaktlistinn er tómur.',
'watchlistanontext' => 'Vinsamlegast $1 til að skoða eða breyta vaktlistanum þínum.',
'watchnologin' => 'Óinnskráð(ur)',
'watchnologintext' => 'Þú verður að vera [[Special:UserLogin|innskáð(ur)]] til að geta breytt vaktlistanum.',
'addwatch' => 'Bæta á vaktlistann',
'addedwatchtext' => 'Síðunni „[[:$1]]“ hefur verið bætt á [[Special:Watchlist|vaktlistann]] þinn.
Frekari breytingar á henni eða spallsíðu hennar munu verða sýndar þar.',
'removewatch' => 'Fjarlægja af vaktlistanum',
'removedwatchtext' => 'Síðan „[[:$1]]“ hefur verið fjarlægð af [[Special:Watchlist|vaktlistanum þínum]].',
'watch' => 'Vakta',
'watchthispage' => 'Vakta þessa síðu',
'unwatch' => 'Afvakta',
'unwatchthispage' => 'Hætta vöktun',
'notanarticle' => 'Ekki efnisleg síða',
'notvisiblerev' => 'Síðasta breyting eftir annan notanda hefur verið eytt.',
'watchnochange' => 'Engri síðu á vaktlistanum þínum hefur verið breytt á tilgreindu tímabili.',
'watchlist-details' => '{{PLURAL:$1|$1 síða|$1 síður}} á vaktlistanum þínum, fyrir utan spjallsíður.',
'wlheader-enotif' => '* Tilkynning með tölvupósti er virk.',
'wlheader-showupdated' => "* Síðum sem hefur verið breytt síðan þú skoðaðir þær síðast eru '''feitletraðar'''",
'watchmethod-recent' => 'kanna hvort nýlegar breytingar innihalda vaktaðar síður',
'watchmethod-list' => 'leita að breytingum í vöktuðum síðum',
'watchlistcontains' => 'Vaktlistinn þinn inniheldur {{PLURAL:$1|$1 síðu|$1 síður}}.',
'iteminvalidname' => 'Vandamál með „$1“, rangt nafn...',
'wlnote' => "Hér fyrir neðan {{PLURAL:$1|er síðasta breyting|eru síðustu '''$1''' breytingar}} {{PLURAL:$2|síðastliðinn klukkutímann|síðastliðna '''$2''' klukkutímana}}, frá $3, $4.",
'wlshowlast' => 'Sýna síðustu $1 klukkutíma, $2 daga, $3',
'watchlist-options' => 'Vaktlistastillingar',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Vakta...',
'unwatching' => 'Afvakta...',
'watcherrortext' => 'Villa kom upp við breytingu á stillingum vaktlistans fyrir "$1".',

'enotif_mailer' => '{{SITENAME}} tilkynningasendill',
'enotif_reset' => 'Merkja allar síður sem skoðaðar',
'enotif_newpagetext' => 'Þetta er ný síða.',
'enotif_impersonal_salutation' => '{{SITENAME}}notandi',
'changed' => 'breytt',
'created' => 'búin til',
'enotif_subject' => '$PAGETITLE á {{SITENAME}} hefur verið $CHANGEDORCREATED af $PAGEEDITOR',
'enotif_lastvisited' => 'Heimsóttu eftirfarandi tengil til að sjá allar breytingar síðan 
þú heimsóttir síðuna síðast:
  $1',
'enotif_lastdiff' => 'Einnig getur þú heimsótt eftirfarandi tengil til að skoða þessa breytingu:
  $1',
'enotif_anon_editor' => 'ónefndum notanda $1',
'enotif_body' => 'Kæri $WATCHINGUSERNAME,

Síðan „$PAGETITLE” sem þú hefur beðið um að fylgjast með á {{SITENAME}} hefur verið $CHANGEDORCREATED $PAGEEDITDATE af 
$PAGEEDITOR. Breytingarágripið var:

   $PAGESUMMARY

Þetta er tengill á síðuna:

   $PAGETITLE_URL

$NEWPAGE

Til þess að hafa samband við $PAGEEDITOR, smelltu á:

   $PAGEEDITOR_WIKI

Athugaðu að frekari breytingar á $PAGETITLE leiða
ekki af sér fleiri tilkynningar fyrr en þú hefur heimsótt síðuna.

Kveðja,
{{SITENAME}}

--

Til þess að breyta stillingum um hvenær þú færð sendar tilkynningar, smelltu á:

{{canonicalurl:{{#special:Preferences}}}}


Til þess að hætta að fylgjast með „$PAGETITLE”, smelltu á:

$UNWATCHURL',

# Delete
'deletepage' => 'Eyða',
'confirm' => 'Staðfesta',
'excontent' => 'innihaldið var: „$1“',
'excontentauthor' => "innihaldið var: '$1' (og öll framlög voru frá '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "innihald fyrir tæmingu var: '$1'",
'exblank' => 'síðan var tóm',
'delete-confirm' => 'Eyða „$1“',
'delete-legend' => 'Eyða',
'historywarning' => "'''Viðvörun:''' Síðan sem þú ert um það bil að eyða hefur breytingarskrá með $1 {{PLURAL:$1|breytingu|breytingum}}:",
'confirmdeletetext' => 'Þú ert um það bil að eyða síðu ásamt breytingaskrá hennar.
Vinsamlegast staðfestu það að þú ætlir að gera svo, það að þú skiljir afleiðingarnar, og að þú sért að gera þetta í samræmi við [[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Aðgerð lokið',
'actionfailed' => 'Aðgerð mistókst',
'deletedtext' => '„$1“ hefur verið eytt.
Sjá lista yfir nýlegar eyðingar í $2.',
'dellogpage' => 'Eyðingaskrá',
'dellogpagetext' => 'Að neðan gefur að líta lista yfir síður sem nýlega hefur verið eytt.',
'deletionlog' => 'eyðingaskrá',
'reverted' => 'Breytt aftur til fyrri útgáfu',
'deletecomment' => 'Ástæða:',
'deleteotherreason' => 'Aðrar/fleiri ástæður:',
'deletereasonotherlist' => 'Önnur ástæða',
'deletereason-dropdown' => '* Algengar ástæður
** Að beiðni höfundar
** Höfundaréttarbrot
** Skemmdarverk',
'delete-edit-reasonlist' => 'Breyta eyðingarástæðum',
'delete-toobig' => 'Þessi síða hefur stóra breytingarskrá, yfir $1 {{PLURAL:$1|breyting|breytingar}}.
Óheimilt er að eyða slíkum síðum til að valda ekki óæskilegum truflunum á {{SITENAME}}.',
'delete-warning-toobig' => 'Þessi síða hefur stóra breytingarskrá, yfir $1 {{PLURAL:$1|breyting|breytingar}}.
Eyðing síðunnar gæti truflað vinnslu gangnasafns {{SITENAME}}; haltu áfram með varúð.',

# Rollback
'rollback' => 'Taka aftur breytingar',
'rollback_short' => 'Taka aftur',
'rollbacklink' => 'taka aftur',
'rollbacklinkcount' => 'taka aftur $1 {{PLURAL:$1|breytingu|breytingar}}',
'rollbacklinkcount-morethan' => 'taka aftur fleiri en $1 {{PLURAL:$1|breytingu|breytingar}}',
'rollbackfailed' => 'Mistókst að taka aftur',
'cantrollback' => 'Ekki hægt að taka aftur breytingu, síðasti höfundur er eini höfundur þessarar síðu.',
'alreadyrolled' => 'Ekki var hægt að taka síðustu breytingu [[:$1]] eftir [[User:$2|$2]] ([[User talk:$2|talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) til baka;
einhver annar hefur breytt síðunni eða tekið breytinguna til baka.

Síðasta breyting síðunnar er frá [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Beytingarágripið var: \"''\$1''\".",
'revertpage' => 'Tók aftur breytingar [[Special:Contributions/$2|$2]] ([[User talk:$2|spjall]]), breytt til síðustu útgáfu [[User:$1|$1]]',
'revertpage-nouser' => 'Tók aftur breytingar (notendanafn fjarlægt) til síðustu útgáfu [[User:$1|$1]]',
'rollback-success' => 'Tók til baka breytingar eftir $1; núverandi $2.',

# Edit tokens
'sessionfailure-title' => 'Mistök í setu',
'sessionfailure' => 'Líklega er vandamál með innskráningar setuna þína;
hætt hefur verið við þessa aðgerð sem vörn gegn mögulegu samskiptaráni setunar.
Farðu aftur á fyrri síðu, endurhladdu hana og reyndu aftur.',

# Protect
'protectlogpage' => 'Verndunarskrá',
'protectlogtext' => 'Fyrir neðan er listi yfir síðuverndanir og -afverndanir.
Sjáðu [[Special:ProtectedPages|Verndunarskrá]] fyrir núverandi lista yfir verndaðar síður.',
'protectedarticle' => 'verndaði „[[$1]]“',
'modifiedarticleprotection' => 'breytti verndunarstigi fyrir "[[$1]]"',
'unprotectedarticle' => 'afverndaði „[[$1]]“',
'movedarticleprotection' => 'verndunarstilling hefur verið færð frá „[[$2]]“ á „[[$1]]“',
'protect-title' => 'Vernda „$1“',
'protect-title-notallowed' => 'Skoða verndunarstig $1',
'prot_1movedto2' => '[[$1]] færð á [[$2]]',
'protect-badnamespace-title' => 'Óverndanlegt nafnrými',
'protect-badnamespace-text' => 'Síður í þessu nafnrými geta ekki verið verndaðar.',
'protect-legend' => 'Verndunarstaðfesting',
'protectcomment' => 'Ástæða:',
'protectexpiry' => 'Rennur út:',
'protect_expiry_invalid' => 'Ógildur tími.',
'protect_expiry_old' => 'Tíminn er þegar runninn út.',
'protect-unchain-permissions' => 'Aflæsa frekari verndunarmöguleika',
'protect-text' => "Hér getur þú skoðað og breytt verndunarstigi síðunnar '''$1'''.",
'protect-locked-blocked' => "Þú getur ekki breytt verndunarstigi á meðan þú ert bannaður.
Hérna er núverandi verndunarstig fyrir síðuna '''$1''':",
'protect-locked-dblock' => "Á meðan gangnabankinn er læstur er ekki hægt að breyta verndunarstigi.
Hér eru núverandi verndunarstig fyrir síðuna '''$1''':",
'protect-locked-access' => "Þú hefur ekki heimild til þess að vernda eða afvernda síður.
Núverandi staða síðunnar er '''$1''':",
'protect-cascadeon' => 'Þessi síða er vernduð vegna þess að hún er innifalin í eftirfarandi {{PLURAL:$1|síðu, sem er keðjuvernduð|síðum, sem eru keðjuverndaðar}}.
Þú getur breytt verndunarstigi þessarar síðu, en það mun ekki hafa áhrif á keðjuverndunina.',
'protect-default' => 'Leyfa öllum notendum',
'protect-fallback' => '„$1“ réttindi nauðsynleg',
'protect-level-autoconfirmed' => 'Banna nýja og óinnskráða notendur',
'protect-level-sysop' => 'Leyfa aðeins stjórnendur',
'protect-summary-cascade' => 'keðjuvörn',
'protect-expiring' => 'rennur út $1 (UTC)',
'protect-expiring-local' => 'rennur út $1',
'protect-expiry-indefinite' => 'ótiltekinn',
'protect-cascade' => 'Vernda innifaldar síður í þessari síðu (keðjuvörn)',
'protect-cantedit' => 'Þú getur ekki breytt verndunarstigi þessarar síðu, vegna þess að þú hefur ekki réttindin til að breyta því.',
'protect-othertime' => 'Annar tími:',
'protect-othertime-op' => 'annar tími',
'protect-existing-expiry' => 'Fyrri gildislok: $3, $2',
'protect-otherreason' => 'Aðrar/fleiri ástæður:',
'protect-otherreason-op' => 'Önnur ástæða',
'protect-dropdown' => '*Algengar ástæður fyrir verndun
** Gengdarlaus skemmdarverk
** Gengdarlausar amasendingar
** Breytingarstríð
** Síða með margar heimsóknir',
'protect-edit-reasonlist' => 'Breyta verndarástæðum',
'protect-expiry-options' => '1 tími:1 hour,1 dag:1 day,1 viku:1 week,2 vikur:2 weeks,1 mánuð:1 month,3 mánuði:3 months,6 mánuði:6 months,1 ár:1 year,aldrei:infinite',
'restriction-type' => 'Réttindi:',
'restriction-level' => 'Takmarkaði við:',
'minimum-size' => 'Lágmarksstærð',
'maximum-size' => 'Hámarksstærð:',
'pagesize' => '(bæt)',

# Restrictions (nouns)
'restriction-edit' => 'Breyta',
'restriction-move' => 'Færa',
'restriction-create' => 'Skapa',
'restriction-upload' => 'Hlaða inn',

# Restriction levels
'restriction-level-sysop' => 'alvernduð',
'restriction-level-autoconfirmed' => 'hálfvernduð',
'restriction-level-all' => 'öll stig',

# Undelete
'undelete' => 'Endurvekja eydda síðu',
'undeletepage' => 'Skoða og endurvekja eyddar síður',
'undeletepagetitle' => "'''Eftirfarandi er samansafn af eyddum breytingum á [[:$1|$1]]'''.",
'viewdeletedpage' => 'Skoða eyddar síður',
'undeletepagetext' => 'Eftirfarandi {{PLURAL:$1|síðu hefur verið eytt en hún er þó enn í gagnagrunninum og getur verið endurvakin|$1 síðum hefur verið eytt en eru þó enn í gagnagrunninum og geta verið endurvaknar}}.
Gagnagrunnurinn kann að vera tæmdur reglulega.',
'undelete-fieldset-title' => 'Endurvekja breytingar',
'undeleteextrahelp' => "Til þess að endurvekja alla breytingarskrá síðunnar, skildu öll box eftir óhökuð og ýttu á '''''{{int:undeletebtn}}'''''.
Til þess að framkvæma ákveðna endurvakningu, ýttu á þau box sem standa hliðiná þeim útgáfum sem á að endurvekja og ýttu á '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|breyting|breytingar}}',
'undeletehistory' => 'Ef þú endurvekur síðuna verða allar útgáfur færðar í breytingarsögu.
Ef ný síða með sama nafni hefur verið stofnuð síðan henni var eytt, verða breytingar síðunnar færðar síðast í breytingarskránna.',
'undeleterevdel' => 'Endurvakning síðu verður ekki framkvæmd ef það leiðir til þess að haus síðunnar eða breytingarsaga hennar verði að hluta til eydd.
Í slíkum málum, þarft þú að afhaka við eða affela nýjustu eyddu breytinguna.',
'undeletehistorynoadmin' => 'Þessari síðu hefur verið eytt. Ástæðan sést í ágripinu fyrir neðan, ásamt upplýsingum um hvaða notendur breyttu síðunni fyrir eyðingu.
Innihald greinarinnar er einungis aðgengilegt möppudýrum.',
'undelete-revision' => 'Eydd breyting $1 (frá $4, kl. $5) eftir $3:',
'undeleterevision-missing' => 'Ógild eða týnd útgáfa.
Þú gætir verið með vitlausan tengil, eða útgáfan gæti hafa verið tekin til baka eða fjarlægð úr breytingarskránni.',
'undelete-nodiff' => 'Engin fyrri útgáfa fannst.',
'undeletebtn' => 'Endurvekja',
'undeletelink' => 'skoða/endurvekja',
'undeleteviewlink' => 'skoða',
'undeletereset' => 'Endurstilla',
'undeleteinvert' => 'Snúa vali við',
'undeletecomment' => 'Ástæða:',
'undeletedrevisions' => '$1 {{PLURAL:$1|breyting endurvakin|breytingar endurvaktar}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|breyting|breytingar}} og $2 {{PLURAL:$2|skrá|skrár}} endurvaktar',
'undeletedfiles' => '{{PLURAL:$1|Ein skrá endurvakin|$1 skrár endurvaktar}}',
'cannotundelete' => 'Ekki var hægt að afturkalla síðuna. (Líklega hefur einhver gert það á undan þér.)',
'undeletedpage' => "'''$1 var endurvakin'''

Skoðaðu [[Special:Log/delete|eyðingaskrána]] til að skoða eyðingar og endurvakningar.",
'undelete-header' => 'Sjá [[Special:Log/delete|eyðingarskrá]] fyrir síður sem nýlega hefur verið eytt.',
'undelete-search-title' => 'Leita í eyddum síðum',
'undelete-search-box' => 'Leita að eyddum síðum',
'undelete-search-prefix' => 'Sýna síður sem byrja á:',
'undelete-search-submit' => 'Leita',
'undelete-no-results' => 'Engar samsvarandi síður fundust í eyðingarskjalasafninu.',
'undelete-filename-mismatch' => 'Endurvakningu skráar mistókst með tímastipilinn $1: Skráarnafnið stenst ekki.',
'undelete-bad-store-key' => 'Endurvakningu útgáfu skráar mistókst með tímastipilinn $1: Skráin fannst ekki fyrir eyðingu.',
'undelete-cleanup-error' => 'Villa við eyðingu ónotaðs skjalasafns $1',
'undelete-missing-filearchive' => 'Mistókst að endurvekja skjalasafn með auðkenninu $1 því það er ekki til í gagnabankanum.
Mögulega er þegar búið að endurvekja það.',
'undelete-error' => 'Mistókst að endurvekja síðu.',
'undelete-error-short' => 'Villa við endurvakningu skráar: $1',
'undelete-error-long' => 'Það kom upp villa við endurvakningu skráarinnar:

$1',
'undelete-show-file-confirm' => 'Ertu viss um að þú viljir sjá eydda breytingu af skránni "<nowiki>$1</nowiki>" frá $2 $3?',
'undelete-show-file-submit' => 'Já',

# Namespace form on various pages
'namespace' => 'Nafnrými:',
'invert' => 'allt nema valið',
'tooltip-invert' => 'Hakaðu við þennan kassa til að fela breytingar á síðum innan ákveðins nafnrýmis',
'namespace_association' => 'Tengd nafnrými',
'tooltip-namespace_association' => 'Hakaðu við þennan kassa til að hafa með spjallsíður eða tengd nafnrými.',
'blanknamespace' => '(Aðalnafnrýmið)',

# Contributions
'contributions' => 'Framlög notanda',
'contributions-title' => 'Framlög notanda $1',
'mycontris' => 'Framlög',
'contribsub2' => 'Eftir $1 ($2)',
'nocontribs' => 'Engar breytingar fundnar sem passa við þessa viðmiðun.',
'uctop' => '(nýjast)',
'month' => 'Frá mánuðinum (og fyrr):',
'year' => 'Frá árinu (og fyrr):',

'sp-contributions-newbies' => 'Sýna aðeins breytingar frá nýjum notendum',
'sp-contributions-newbies-sub' => 'Fyrir nýliða',
'sp-contributions-newbies-title' => 'Breytingar nýrra notenda',
'sp-contributions-blocklog' => 'Fyrri bönn',
'sp-contributions-deleted' => 'Eyddar breytingar notanda',
'sp-contributions-uploads' => 'upphlöð',
'sp-contributions-logs' => 'Aðgerðaskrá',
'sp-contributions-talk' => 'spjall',
'sp-contributions-userrights' => 'Breyta notandaréttindum',
'sp-contributions-blocked-notice' => 'Þessi notandi er í banni.
Síðasta færsla notandans úr bönnunarskrá er sýnd hér fyrir neðan til skýringar:',
'sp-contributions-blocked-notice-anon' => 'Þetta vistfang er í banni.
Síðasta færsla vistfangsins úr bönnunarskrá er sýnd hér fyrir neðan til skýringar:',
'sp-contributions-search' => 'Leita að framlögum',
'sp-contributions-username' => 'Vistfang eða notandanafn:',
'sp-contributions-toponly' => 'Aðeins sýna síðustu breytingar',
'sp-contributions-submit' => 'Leita að breytingum',

# What links here
'whatlinkshere' => 'Hvað tengist hingað',
'whatlinkshere-title' => 'Síður sem tengjast „$1“',
'whatlinkshere-page' => 'Síða:',
'linkshere' => "Eftirfarandi síður tengjast á '''[[:$1]]''':",
'nolinkshere' => "Engar síður tengjast á '''[[:$1]]'''.",
'nolinkshere-ns' => "Engar síður tengjast '''[[:$1]]''' í þessu nafnrými.",
'isredirect' => 'tilvísun',
'istemplate' => 'innifalið',
'isimage' => 'Skráartengill',
'whatlinkshere-prev' => '{{PLURAL:$1|fyrra|fyrri $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|næst|næstu $1}}',
'whatlinkshere-links' => '← tenglar',
'whatlinkshere-hideredirs' => '$1 tilvísanir',
'whatlinkshere-hidetrans' => '$1 ítengingar',
'whatlinkshere-hidelinks' => '$1 tengla',
'whatlinkshere-hideimages' => '$1 skrátenglar',
'whatlinkshere-filters' => 'Síur',

# Block/unblock
'autoblockid' => 'Sjálfvirkt bann $1',
'block' => 'Banna notanda',
'unblock' => 'Afbanna notanda',
'blockip' => 'Banna notanda',
'blockip-title' => 'Banna notanda',
'blockip-legend' => 'Banna notanda',
'blockiptext' => 'Notaðu eyðublaðið hér að neðan til þess að banna ákveðið vistfang eða notandanafn.
Þetta ætti einungis að gera til þess að koma í veg fyrir skemmdarverk, og í samræmi við [[{{MediaWiki:Policy-url}}|samþykktir]].
Gefðu nákvæma skýringu að neðan (til dæmis, með því að vísa í þær síður sem skemmdar voru).',
'ipadressorusername' => 'Vistfang eða notandanafn:',
'ipbexpiry' => 'Bannið rennur út:',
'ipbreason' => 'Ástæða:',
'ipbreasonotherlist' => 'Aðrar ástæður',
'ipbreason-dropdown' => '* Algengar bannástæður
** Setur inn rangar upplýsingar
** Fjarlægir efni af síðum
** Setur inn rusltengla á utanaðkomandi síður
** Setur inn vitleysu/þvaður á síður
** Yfirþyrmandi framkoma/áreitni
** Misnotkun á fjölda notandanafna
** Óásættanlegt notandanafn',
'ipb-hardblock' => 'Hindra innskráðum notendum frá því að breyta frá þessu vistfangi.',
'ipbcreateaccount' => 'Banna nýskráningu notanda',
'ipbemailban' => 'Banna notanda að senda tölvupóst',
'ipbenableautoblock' => 'Banna síðasta vistfang notanda sjálfkrafa; og þau vistföng sem viðkomandi notar til að breyta síðum',
'ipbsubmit' => 'Banna notanda',
'ipbother' => 'Annar tími:',
'ipboptions' => '2 tíma:2 hours,1 dag:1 day,3 daga:3 days,1 viku:1 week,2 vikur:2 weeks,1 mánuð:1 month,3 mánuði:3 months,6 mánuði:6 months,1 ár:1 year,aldrei:infinite',
'ipbotheroption' => 'annar',
'ipbotherreason' => 'Önnur/auka ástæða:',
'ipbhidename' => 'Fela notandanafn úr breytingarskrá og listum',
'ipbwatchuser' => 'Vakta notanda- og spjallsíður þessa notanda',
'ipb-disableusertalk' => 'Banna þessum notenda að breyta egin spjallsíðu',
'ipb-change-block' => 'Endurbanna notanda með þessum stillingum',
'ipb-confirm' => 'Staðfesta bann',
'badipaddress' => 'Ógilt vistfang',
'blockipsuccesssub' => 'Bann tókst',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] hefur verið bannaður/bönnuð.<br />
Sjá [[Special:BlockList|bannaðir notendur og vistföng]] fyrir yfirlit yfir núverandi bönn.',
'ipb-blockingself' => 'Þú ert í þann mund að banna sjálfan þig! Ertu viss um að þú viljir gera það?',
'ipb-confirmhideuser' => 'Þú ert í þann mund að banna notenda sem er falinn. Notendanafn hans mun ekki birtast í listum og aðgerðarskrám. Ertu viss um að þú viljir gera það?',
'ipb-edit-dropdown' => 'Breyta ástæðu fyrir banni',
'ipb-unblock-addr' => 'Afbanna $1',
'ipb-unblock' => 'Afbanna notanda eða vistfang',
'ipb-blocklist' => 'Sjá núverandi bönn',
'ipb-blocklist-contribs' => 'Framlög fyrir $1',
'unblockip' => 'Afbanna notanda',
'unblockiptext' => 'Endurvekja skrifréttindi bannaðra notenda eða vistfanga.',
'ipusubmit' => 'Afbanna',
'unblocked' => '[[User:$1|$1]] hefur verið afbannaður',
'unblocked-range' => '$1 hefur verið afbannaður',
'unblocked-id' => 'Bann $1 hefur verið fjarlægt',
'blocklist' => 'Bannaðir notendur og vistföng',
'ipblocklist' => 'Bannaðir notendur og vistföng',
'ipblocklist-legend' => 'Finna bannaðan notanda',
'blocklist-userblocks' => 'Fela notendabönn',
'blocklist-tempblocks' => 'Fela tímabundin bönn',
'blocklist-addressblocks' => 'Fela einstök bönn vistfanga',
'blocklist-rangeblocks' => 'Fela fjöldabönn',
'blocklist-timestamp' => 'Tímastimpill',
'blocklist-target' => 'Beinist að',
'blocklist-expiry' => 'Rennur út',
'blocklist-by' => 'Bannaður af',
'blocklist-params' => 'Bönnunar stikar',
'blocklist-reason' => 'Ástæða',
'ipblocklist-submit' => 'Leita',
'ipblocklist-localblock' => 'Svæðisbundið bann',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Annað bann|Önnur bönn}}',
'infiniteblock' => 'aldrei',
'expiringblock' => 'rennur út  $1 $2',
'anononlyblock' => 'bara ónafngreindir',
'noautoblockblock' => 'sjálfbönnun óvirk',
'createaccountblock' => 'bann við stofnun nýrra aðganga',
'emailblock' => 'tölvupóstur bannaður',
'blocklist-nousertalk' => 'getur ekki breytt eigin spjallsíðu',
'ipblocklist-empty' => 'Bannlistinn er tómur.',
'ipblocklist-no-results' => 'Umbeðið vistfang eða notandanafn er ekki í banni.',
'blocklink' => 'banna',
'unblocklink' => 'afbanna',
'change-blocklink' => 'breyta bönnun',
'contribslink' => 'framlög',
'emaillink' => 'senda tölvupóst',
'autoblocker' => 'Vistfang þitt er bannað vegna þess að það hefur nýlega verið notað af „[[User:$1|$1]]“.
Ástæðan fyrir því að $1 var bannaður er: „$2“',
'blocklogpage' => 'Bönnunarskrá',
'blocklog-showlog' => 'Notandinn hefur verið bannaður áður.
Síðasta færsla notandans úr bönnunarskrá er sýnd hér fyrir neðan til skýringar:',
'blocklog-showsuppresslog' => 'Notandinn hefur verið bældur niður áður.
Síðasta færsla notandans úr bælingarskrá er sýnd hér fyrir neðan til skýringar:',
'blocklogentry' => 'bannaði „[[$1]]“; rennur út eftir: $2 $3',
'reblock-logentry' => 'breytti banni [[$1]] rennur út $2 $3',
'blocklogtext' => 'Þetta er skrá yfir bönn sem lögð hafa verið á notendur eða bönn sem hafa verið numin úr gildi.
Vistföng sem sett hafa verið í bann sjálfvirkt birtast ekki hér.
Sjá [[Special:BlockList|ítarlegri lista]] fyrir öll núgildandi bönn.',
'unblocklogentry' => 'afbannaði $1',
'block-log-flags-anononly' => 'bara ónefndir notendur',
'block-log-flags-nocreate' => 'gerð aðganga bönnuð',
'block-log-flags-noautoblock' => 'sjálfkrafa bann óvirkt',
'block-log-flags-noemail' => 'netfang bannað',
'block-log-flags-nousertalk' => 'getur ekki breytt eigin spjallsíðu',
'block-log-flags-angry-autoblock' => 'sjálfkrafa bann virkt',
'block-log-flags-hiddenname' => 'notandanafn falið',
'range_block_disabled' => 'Möppudýr geta ekki fjöldabannað vistföng á þessum wiki.',
'ipb_expiry_invalid' => 'Tími ógildur.',
'ipb_expiry_temp' => 'Bönn faldra notenda verða að vera varanleg.',
'ipb_hide_invalid' => 'Mistókst að bæla niður þennan aðgang; mögulega hefur hann of margar breytingar.',
'ipb_already_blocked' => '„$1“ er nú þegar í banni',
'ipb-needreblock' => '$1 er þegar bannaður. Vilt þú breyta banninu?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Annað bann|Önnur bönn}}',
'unblock-hideuser' => 'Þú getur ekki afbannað þennan notanda, því notendanafn hans hefur verið falið.',
'ipb_cant_unblock' => 'Villa: Bann-tala $1 fannst ekki. Bannið gæti verið útrunnið eða hún afbönnuð.',
'ipb_blocked_as_range' => 'Villa: Ekki er hægt að afbanna vistfangið $1, því það er hluti af fjöldabanni.
Vistfangið var bannað sem hluti af fjöldabanninu $2, sem er hægt að afbanna.',
'ip_range_invalid' => 'Ógilt vistfangasvið.',
'ip_range_toolarge' => 'Fjöldabönn stærri en /$1 eru óheimil.',
'blockme' => 'Banna mig',
'proxyblocker' => 'Vefsels bann',
'proxyblocker-disabled' => 'Þessi virkni er óvirk.',
'proxyblockreason' => 'Vistfangið þitt hefur verið bannað því það er opið vefsel.
Vinsamlegast hafðu samband við internetþjónustuaðilann þinn eða netstjóra félagsins og láttu þá vita af þessu alvarlegu öryggisvandamáli.',
'proxyblocksuccess' => 'Búinn.',
'sorbsreason' => 'Vistfangið þitt er á lista yfir opin vefsel í DNSBL sem er í notkun á {{SITENAME}}.',
'sorbs_create_account_reason' => 'Vistfangið þitt er á lista yfir opin vefsel í DNSBL sem er notað af {{SITENAME}}.
Þú getur ekki stofnað aðgang.',
'cant-block-while-blocked' => 'Þú getur ekki bannað aðra notendur á meðan þú ert í banni.',
'cant-see-hidden-user' => 'Notandinn sem þú ert að reyna að banna hefur þegar verið bannaður og falinn.
Þar sem þú hefur ekki þau réttindi að fela notendur, þá getur þú ekki séð eða breytt banni notandans.',
'ipbblocked' => 'Þú getur ekki bannað eða afbannað aðra notendur, því þú ert sjálfur í banni.',
'ipbnounblockself' => 'Þér er óheimilt að afbanna sjálfan þig',

# Developer tools
'lockdb' => 'Læsa gagnagrunninum',
'unlockdb' => 'Opna gagnagrunninn',
'lockconfirm' => 'Já, ég vil læsa gagnagrunninum.',
'unlockconfirm' => 'Já, ég vil aflæsa gagnagrunninum.',
'lockbtn' => 'Læsa gagnagrunni',
'unlockbtn' => 'Aflæsa gagnagrunninum',
'locknoconfirm' => 'Þú hakaðir ekki í staðfestingarrammann.',
'lockdbsuccesssub' => 'Læsing á gagnagrunninum tókst',
'unlockdbsuccesssub' => 'Læsing á gagnagrunninum hefur verið fjarlægð',
'lockdbsuccesstext' => 'Gagnagrunninum hefur verið læst.<br />
Mundu að [[Special:UnlockDB|opna hann aftur]] þegar þú hefur lokið viðgerðum.',
'unlockdbsuccesstext' => 'Gagnagrunnurinn hefur verið opnaður.',
'databasenotlocked' => 'Gagnagrunnurinn er ekki læstur.',
'lockedbyandtime' => '(af {{GENDER:$1|$1}} kl. $3, $2)',

# Move page
'move-page' => 'Færa $1',
'move-page-legend' => 'Færa síðu',
'movepagetext' => "Hér er hægt að endurnefna síðu. Hún færist, ásamt breytingaskránni, yfir á nýtt heiti og eldra heitið myndar tilvísun á það. Þú getur sjálfkrafa uppfært tilvísanir á nýja heitið. Ef þú vilt það síður, athugaðu þá hvort nokkuð myndist [[Special:DoubleRedirects|tvöfaldar]] eða [[Special:BrokenRedirects|brotnar tilvísanir]].
Þú berð ábyrgð á því að tenglar vísi á rétta staði.

Athugaðu að síðan mun '''ekki''' færast ef þegar er síða á nafninu sem þú hyggst færa hana á, nema sú síða sé tóm eða tilvísun sem vísar á síðuna sem þú ætlar að færa. Þú getur þar með fært síðuna aftur til baka án þess að missa breytingarsöguna, en ekki fært hana yfir venjulega síðu.

'''Varúð:'''
Athugaðu að þessi aðgerð getur kallað fram viðbrögð annarra notenda og getur þýtt mjög rótækar breytingar á vinsælum síðum.",
'movepagetext-noredirectfixer' => "Með þessu eyðublaði er hægt að endurnefna síðu og færa alla breytingarskrá hennar á nýja nafnið. Gamli titillinn verður að tilvísun á nýja titilinn. 
Athugaðu hvort síðan tengist [[Special:DoubleRedirects|tvöfaldri]]- eða [[Special:BrokenRedirects|brotinni]] tilvísun.
Þú berð ábyrgð á því að tenglarnir haldi áfram að tengjast á réttan stað.

Athugaðu að síðan verður '''ekki''' færð ef síða er þegar til á nýja titlinum, nema hann sé annaðhvort tómur, tilvísun eða hafi enga breytingarskrá.
Þetta merkir að þú getur fært síðu aftur til baka á þann stað sem hún var færð frá ef þú gerir mistök og þú getur ekki skrifað yfir síðu sem er þegar til.

'''Varúð:'''
Ef síðan er vinsæl þá getur þessi aðgerð kallað fram viðbrögð annara notenda og getur þýtt mjög rótækar breytingar á öðrum síðum. Vertu viss um að þú skiljir hættuna áður en þú heldur áfram.",
'movepagetalktext' => 'Spallsíða síðunnar verður sjálfkrafa færð með ef hún er til nema:
* Þú sért að færa síðuna á milli nafnrýma
* Spallsíða sé þegar til undir nýja nafninu
* Þú veljir að færa hana ekki
Í þeim tilfellum verður að færa hana handvirkt.',
'movearticle' => 'Færa síðu:',
'moveuserpage-warning' => "'''Viðvörun:''' Þú ert í þann mund að færa notendasíðu. Athugaðu aðeins síðan verður færð og notendanafni hans verður '''ekki''' breytt.",
'movenologin' => 'Óinnskráð(ur)',
'movenologintext' => 'Þú verður að vera [[Special:UserLogin|innskráð(ur)]] til að geta fært síður.',
'movenotallowed' => 'Þú hefur ekki leyfi til að færa síður.',
'movenotallowedfile' => 'Þú hefur ekki leyfi til að færa skrár.',
'cant-move-user-page' => 'Þú hefur ekki leyfi til að færa notandasíðu (fyrir utan undirsíður).',
'cant-move-to-user-page' => 'Þú hefur ekki leyfi til að færa síðu á notandasíðu (að frátöldum undirsíðum notanda).',
'newtitle' => 'Á nýja titilinn:',
'move-watch' => 'Vakta þessa síðu',
'movepagebtn' => 'Færa síðu',
'pagemovedsub' => 'Færsla tókst',
'movepage-moved' => "'''„$1“ hefur verið færð á „$2“'''",
'movepage-moved-redirect' => 'Tilvísun hefur verið búin til.',
'movepage-moved-noredirect' => 'Tilvísun var ekki búin til.',
'articleexists' => 'Annaðhvort er þegar til síða undir þessum titli, eða sá titill sem þú hefur valið er ekki gildur.
Vinsamlegast veldu annan titil.',
'cantmove-titleprotected' => 'Þú getur ekki fært síðu á þessa staðsetningu, því nýi titillinn hefur verið verndaður gegn sköpun',
'talkexists' => "'''Færsla á síðunni sjálfri heppnaðist, en ekki var hægt að færa spjallsíðuna því hún er nú þegar til á nýja titlinum.
Gjörðu svo vel og færðu hana handvirkt.'''",
'movedto' => 'fært á',
'movetalk' => 'Færa meðfylgjandi spjallsíðu',
'move-subpages' => 'Færa undirstíður (upp að $1)',
'move-talk-subpages' => 'Færa undirstíður spjallsíðunnar (upp að $1)',
'movepage-page-exists' => 'Síðan $1 er nú þegar til og er ekki hægt að yfirskrifa sjálfkrafa.',
'movepage-page-moved' => 'Síðan $1 hefur verið færð á $2.',
'movepage-page-unmoved' => 'Ekki var hægt að færa síðuna $1 á $2.',
'movepage-max-pages' => 'Hámarkinu, $1 {{PLURAL:$1|síða|síður}}, hefur verið náð og verða engar fleiri færðar sjálfvirkt.',
'movelogpage' => 'Flutningaskrá',
'movelogpagetext' => 'Þetta er listi yfir síður sem nýlega hafa verið færðar.',
'movesubpage' => '{{Plural:$1|Undirsíða|Undirsíður}}',
'movesubpagetext' => 'Þessi síða hefur {{PLURAL:$1|eina undirsíðu|$1 undirsíður}} sem {{PLURAL:$1|er sýnd|eru sýndar}} hér fyrir neðan.',
'movenosubpage' => 'Þessi síða hefur engar undirsíður.',
'movereason' => 'Ástæða:',
'revertmove' => 'taka til baka',
'delete_and_move' => 'Eyða og flytja',
'delete_and_move_text' => '==Beiðni um eyðingu==

Síðan „[[:$1]]“ er þegar til. Viltu eyða henni til þess að rýma til fyrir flutningi?',
'delete_and_move_confirm' => 'Já, eyða síðunni',
'delete_and_move_reason' => 'Eytt til að rýma til fyrir flutning frá "[[$1]]"',
'selfmove' => 'Nýja nafnið er það sama og gamla, þú verður að velja annað nafn.',
'immobile-source-namespace' => 'Get ekki fært síður í nafnrýminu „$1“',
'immobile-target-namespace' => 'Get ekki fært síður inn í nafnrýmið „$1“',
'immobile-target-namespace-iw' => 'Óheimilt er að færa síðu með tungumálatengli.',
'immobile-source-page' => 'Þessi síða er ekki færanleg.',
'immobile-target-page' => 'Get ekki fært á áætlaðan titil.',
'imagenocrossnamespace' => 'Get ekki fært skrá í skrálaust nafnrými',
'nonfile-cannot-move-to-file' => 'Get ekki fært annað en skrár í nafnrými skráa.',
'imagetypemismatch' => 'Nýi nafnaukinn passar ekki við tegund hennar',
'imageinvalidfilename' => 'Markskráarnafnið er ógilt',
'fix-double-redirects' => 'Uppfæra tilvísanir sem vísa á upphaflegan titil',
'move-leave-redirect' => 'Skilja tilvísun eftir',
'protectedpagemovewarning' => "'''Viðvörun:''' Þessari síðu hefur verið læst svo aðeins notendur með möppudýraréttindi geta fært hana.
Síðasta færsla síðunnar úr verndunarskrá er sýnd til skýringar:",
'semiprotectedpagemovewarning' => "'''Athugið''': Þessari síðu hefur verið læst þannig að aðeins innskráðir notendur geti fært hana.
Síðasta færsla síðunnar úr verndunarskrá er sýnd til skýringar:",
'move-over-sharedrepo' => '== Skráin er þegar til ==
[[:$1]] er þegar til í sameiginlega myndasafninu. Ef skráin yrði færð á þennan titil myndi hún sjást í stað þeirrar skráar sem er til fyrir á sameiginlega myndasafninu.',
'file-exists-sharedrepo' => 'Skráarnafnið er þegar í notkun á sameiginlega myndasafninu.
Vinsamlegast veldu annað nafn.',

# Export
'export' => 'Flytja út síður',
'exporttext' => 'Þú getur flutt texta og breytingarsögu síðu eða fjölda síðna sem eru tilgreindar í XML skjali.
Þessi gögn er hægt að flytja inn á annan wiki með möguleikanum að [[Special:Import|flytja inn síðu]].

Til þess að flytja út síður, skrifaðu titla þeirra í reitina hér fyrir neðan, einn titil í hvern reit og veldu hvort þú viljir núverandi útgáfu með eldri útgáfum hennar, eða núverandi breytingu með upplýsingum um síðustu breytingu.

Ef síðari möguleikinn á við getur þú einnig notað tengil, til dæmis
[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] fyrir síðuna "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly' => 'Aðeins núverandi útgáfu án breytingaskrár',
'exportnohistory' => "----
'''Athugaðu:''' Að flytja út alla breytingasögu síðna á þennan hátt hefur verið óvirkjað vegna ástæðna afkasta.",
'exportlistauthors' => 'Innifela tæmandi lista af breytingum fyrir allar síður',
'export-submit' => 'Flytja',
'export-addcattext' => 'Bæta við síðum frá flokkinum:',
'export-addcat' => 'Bæta við',
'export-addnstext' => 'Bæta við síðum frá nafnrýminu:',
'export-addns' => 'Bæta við',
'export-download' => 'Vista sem skjal',
'export-templates' => 'Innifala sniðin með',
'export-pagelinks' => 'Innifela tengdar síður með dýptinni:',

# Namespace 8 related
'allmessages' => 'Meldingar',
'allmessagesname' => 'Titill',
'allmessagesdefault' => 'Sjálfgefinn skilaboða texti',
'allmessagescurrent' => 'Núverandi texti',
'allmessagestext' => 'Þetta er listi yfir kerfismeldingar í Melding-nafnrýminu.
Vinsamlegast heimsæktu [//www.mediawiki.org/wiki/Localisation MediaWiki-staðfæringuna] og [//translatewiki.net translatewiki.net] ef þú vilt taka þátt í almennri MediaWiki-staðfæringu.',
'allmessagesnotsupportedDB' => "Það er ekki hægt að nota '''{{ns:special}}:Allmessages''' því '''\$wgUseDatabaseMessages''' hefur verið gerð óvirk.",
'allmessages-filter-legend' => 'Sía',
'allmessages-filter' => 'Sía með breytingarstöðu:',
'allmessages-filter-unmodified' => 'Óbreytt',
'allmessages-filter-all' => 'Allt',
'allmessages-filter-modified' => 'Breyttar',
'allmessages-prefix' => 'Sía með forskeyti:',
'allmessages-language' => 'Tungumál:',
'allmessages-filter-submit' => 'Áfram',

# Thumbnails
'thumbnail-more' => 'Stækka',
'filemissing' => 'Skrá vantar',
'thumbnail_error' => 'Villa við gerð smámyndar: $1',
'thumbnail-temp-create' => 'Mistókst að búa til tímabundna smámynd.',
'thumbnail_invalid_params' => 'Breytur smámyndarinnar eru rangar',
'thumbnail_dest_directory' => 'Mistókst að búa til niðurhals möppu',
'thumbnail_image-type' => 'Enginn stuðningur er við þetta skráarsnið',
'thumbnail_image-missing' => 'Skránna vantar: $1',

# Special:Import
'import' => 'Flytja inn síður',
'importinterwiki' => 'Milli-Wiki innflutningur',
'import-interwiki-text' => 'Veldu Wiki-kerfi og síðutitil til að flytja inn.
Breytingaupplýsingar s.s. dagsetningar og höfundanöfn eru geymd.
Allir innflutningar eru skráð í [[Special:Log/import|innflutningsskránna]].',
'import-interwiki-source' => 'Uppruni wiki síðunnar:',
'import-interwiki-history' => 'Afrita allar breytingar þessarar síðu',
'import-interwiki-templates' => 'Innifala öll snið með',
'import-interwiki-submit' => 'Flytja inn',
'import-interwiki-namespace' => 'Ákvörðunarnafnrými:',
'import-upload-filename' => 'Skráarnafn:',
'import-comment' => 'Athugasemdir:',
'importtext' => 'Vinsamlegast fluttu út skránna frá upprunalegum wiki með því að nota [[Special:Export|Flytja út síður]].
Vistaðu skránna á tölvunni þinni og hladdu henni inn hér.',
'importstart' => 'Flyt inn síður...',
'import-revision-count' => '$1 {{PLURAL:$1|breyting|breytingar}}',
'importnopages' => 'Engar síður til innflutnings.',
'imported-log-entries' => '$1 {{PLURAL:$1|breytingar færsla|breytingar færslur}} hafa verið fluttar inn',
'importfailed' => 'Innhlaðning mistókst: $1',
'importcantopen' => 'Get ekki opnað innflutt skjal',
'importbadinterwiki' => 'Villa í tungumálatengli',
'importnotext' => 'Tómt eða enginn texti',
'importsuccess' => 'Innflutningi lokið!',
'importhistoryconflict' => 'Breytingarskrá þessarar síðu er þegar til (gæti hafa verið flutt inn áður)',
'importnosources' => 'Engin uppspretta hefur verið valin og bein upphlöð breytingarskráa eru óvirk.',
'importnofile' => 'Engri skrá var hlaðið inn.',
'importuploaderrorsize' => 'Upphlöðun skráarinnar mistókst.
Skráin er stærri en hámarsstærð síðna segir til um.',
'importuploaderrorpartial' => 'Upphlöðun skráarinnar mistókst.
Skráinni var eingöngu að hluta til hlaðið inn.',
'importuploaderrortemp' => 'Upphlöðun skráarinnar mistókst.
Tímabundin mappa fannst ekki.',
'import-parse-failure' => 'Þáttunarvilla við innflutning XML skjals',
'import-noarticle' => 'Engin síða til innflutnings!',
'import-nonewrevisions' => 'Allar breytingar voru fluttar inn.',
'xml-error-string' => '$1 í línu $2, dálki $3 ($4 bæt): $5',
'import-upload' => 'Hlaða inn XML-gögnum',
'import-token-mismatch' => 'Týnd setu gögn.
Vinsamlegast reyndu aftur.',
'import-invalid-interwiki' => 'Get ekki flutt inn frá þessum wiki.',
'import-error-edit' => 'Síðan "$1" var ekki flutt inn því þú hefur ekki réttindi til að breyta henni.',
'import-error-create' => 'Síðan "$1" var ekki flutt inn því þú hefur ekki réttindi til að stofna hana.',
'import-error-interwiki' => 'Síðan "$1" var ekki flutt inn því nafn hennar er frátekið fyrir ytri tengla (tungumálatengla).',
'import-error-special' => 'Síðan "$1" var ekki flutt inn því hún tilheyrir ákveðnu nafnrými sem leyfir ekki síður.',
'import-error-invalid' => 'Síðan "$1" var ekki flutt inn því nafn hennar er ógilt.',

# Import log
'importlogpage' => 'Innflutningsskrá',
'importlogpagetext' => 'Hér er listi yfir innflutninga möppdýra á síðum ásamt breytingarskránni frá öðrum wiki.',
'import-logentry-upload' => 'flutti inn [[$1]] frá skrá',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|breyting|breytingar}}',
'import-logentry-interwiki' => 'flutti inn $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|breyting|breytingar}} frá $2',

# JavaScriptTest
'javascripttest' => 'JavaScript prófun',
'javascripttest-disabled' => 'Þessi möguleiki hefur ekki verið virkjaður á þessum wiki.',
'javascripttest-pagetext-skins' => 'Veldu þema sem á að keyra prófanirnar á:',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Notandasíðan þín',
'tooltip-pt-anonuserpage' => 'Notandasíðan fyrir vistfangið þitt',
'tooltip-pt-mytalk' => 'Spjallsíðan þín',
'tooltip-pt-anontalk' => 'Spjallsíðan fyrir þetta vistfang',
'tooltip-pt-preferences' => 'Almennar stillingar',
'tooltip-pt-watchlist' => 'Listi yfir síður sem þú fylgist með breytingum á',
'tooltip-pt-mycontris' => 'Listi yfir framlög þín',
'tooltip-pt-login' => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki skylda.',
'tooltip-pt-anonlogin' => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.',
'tooltip-pt-logout' => 'Útskráning',
'tooltip-ca-talk' => 'Spallsíða þessarar síðu',
'tooltip-ca-edit' => 'Þú getur breytt síðu þessari, vinsamlegast notaðu „forskoða“ hnappinn áður en þú vistar',
'tooltip-ca-addsection' => 'Bæta nýjum hluta við',
'tooltip-ca-viewsource' => 'Síða þessi er vernduð. Þú getur þó skoðað frumkóða hennar.',
'tooltip-ca-history' => 'Eldri útgáfur af síðunni.',
'tooltip-ca-protect' => 'Vernda þessa síðu',
'tooltip-ca-unprotect' => 'Afvernda þessa síðu',
'tooltip-ca-delete' => 'Eyða þessari síðu',
'tooltip-ca-undelete' => 'Endurvekja breytingar á þessari síðu áður en að henni var eytt',
'tooltip-ca-move' => 'Færa þessa síðu',
'tooltip-ca-watch' => 'Bæta þessari síðu við á vaktlistann',
'tooltip-ca-unwatch' => 'Fjarlægja þessa síðu af vaktlistanum',
'tooltip-search' => 'Leit á þessari Wiki',
'tooltip-search-go' => 'Fara á síðu með þessu nafni ef hún er til',
'tooltip-search-fulltext' => 'Leita á síðunum eftir þessum texta',
'tooltip-p-logo' => 'Forsíða',
'tooltip-n-mainpage' => 'Forsíða {{SITENAME}}',
'tooltip-n-mainpage-description' => 'Heimsækja forsíðuna',
'tooltip-n-portal' => 'Um verkefnið, hvernig er hægt að hjálpa og hvar á að byrja',
'tooltip-n-currentevents' => 'Finna upplýsingar um líðandi stund',
'tooltip-n-recentchanges' => 'Listi yfir nýlegar breytingar.',
'tooltip-n-randompage' => 'Handahófsvalin síða',
'tooltip-n-help' => 'Efnisyfirlit yfir hjálparsíður.',
'tooltip-t-whatlinkshere' => 'Listi yfir síður sem tengjast í þessa',
'tooltip-t-recentchangeslinked' => 'Nýlegar breytingar á ítengdum síðum',
'tooltip-feed-rss' => 'RSS fyrir þessa síðu',
'tooltip-feed-atom' => 'Atom fyrir þessa síðu',
'tooltip-t-contributions' => 'Sýna framlagslista þessa notanda',
'tooltip-t-emailuser' => 'Senda þessum notanda tölvupóst',
'tooltip-t-upload' => 'Hlaða inn skrám',
'tooltip-t-specialpages' => 'Listi yfir kerfissíður',
'tooltip-t-print' => 'Prentanleg útgáfa af þessari síðu',
'tooltip-t-permalink' => 'Varanlegur tengill',
'tooltip-ca-nstab-main' => 'Sýna síðuna',
'tooltip-ca-nstab-user' => 'Sýna notandasíðuna',
'tooltip-ca-nstab-media' => 'Sýna margmiðlunarsíðuna',
'tooltip-ca-nstab-special' => 'Þetta er kerfissíða, þér er óhæft að breyta henni.',
'tooltip-ca-nstab-project' => 'Sýna verkefnasíðuna',
'tooltip-ca-nstab-image' => 'Sýna skráarsíðu',
'tooltip-ca-nstab-mediawiki' => 'Sýna kerfisskilaboðin',
'tooltip-ca-nstab-template' => 'Sýna sniðið',
'tooltip-ca-nstab-help' => 'Sýna hjálparsíðuna',
'tooltip-ca-nstab-category' => 'Sýna efnisflokkasíðuna',
'tooltip-minoredit' => 'Merkja þessa breytingu sem minniháttar',
'tooltip-save' => 'Vista breytingarnar',
'tooltip-preview' => 'Forskoða breytingarnar, vinsamlegast gerðu þetta áður en þú vistar!',
'tooltip-diff' => 'Sýna hvaða breytingar þú gerðir á textanum.',
'tooltip-compareselectedversions' => 'Sjá breytingarnar á þessari grein á milli útgáfanna sem þú valdir.',
'tooltip-watch' => 'Bæta þessari síðu á vaktlistann þinn',
'tooltip-watchlistedit-normal-submit' => 'Fjarlægja titla',
'tooltip-watchlistedit-raw-submit' => 'Uppfæra vaktlistann',
'tooltip-recreate' => 'Endurvekja síðuna þó henni hafi verið eytt',
'tooltip-upload' => 'Hefja innhleðslu',
'tooltip-rollback' => '"taka aftur" breytir greininni til síðasta höfundar með einum smelli',
'tooltip-undo' => '"Tek aftur þessa breytingu" breytir aftur til síðustu breytingu og opnar breytinguna í forskoðun. Hægt er að bæta við ástæðu í breytingarávarpinu.',
'tooltip-preferences-save' => 'Vista stillingar',
'tooltip-summary' => 'Bættu við stuttu ágripi',

# Stylesheets
'common.css' => '/* Allt CSS sem sett er hér mun virka á öllum þemum. */',
'monobook.css' => '/* Það sem sett er hingað er bætt við Monobook stilsniðið fyrir allan vefinn */',

# Scripts
'common.js' => '/* Allt JavaScript sem sett er hér mun virka í hvert skipti sem að síða hleðst. */',

# Metadata
'notacceptable' => 'Wiki vefþjónninn getur ekki útvegað gögn á því formi sem biðlarinn þinn getur lesið.',

# Attribution
'anonymous' => '{{PLURAL:$1|Óþekktur notandi|Óþekktir notendur}} á {{SITENAME}}',
'siteuser' => '{{SITENAME}} notandi $1',
'anonuser' => '{{SITENAME}} nafnlaus notandi $1',
'lastmodifiedatby' => 'Þessari síðu var síðast breytt $1 klukkan $2 af $3.',
'othercontribs' => 'Byggt á verkum $1.',
'others' => 'aðrir',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|notandi|notendur}} $1',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|nafnlaus notandi|nafnlausir notendur}} $1',

# Spam protection
'spamprotectiontitle' => 'Amapósts sía',
'spamprotectiontext' => 'Textinn sem þú vildir vista var hafnað af amapósts síunni.
Þetta er líklega vegna tengils á síðu sem er á svörtum lista.',
'spamprotectionmatch' => 'Eftirfarandi texti hrinti amapósts síunni af stað: $1',
'spambot_username' => 'MediaWiki amapósts hreinsun',
'spam_reverting' => 'Tek aftur síðustu breytingu sem inniheldur ekki tengil á $1',
'spam_blanking' => 'Allar útgáfur innihéldu tengla á $1, tæmi síðuna',
'spam_deleting' => 'Allar útgáfur innihéldu tengla á $1, eyði síðunni',

# Info page
'pageinfo-title' => 'Upplýsingar um $1',
'pageinfo-header-basic' => 'Grunnupplýsingar',
'pageinfo-header-edits' => 'Breytingarskrá',
'pageinfo-header-restrictions' => 'Verndunarstig síðunnar',
'pageinfo-header-properties' => 'Eiginleikar síðunnar',
'pageinfo-display-title' => 'Sýnilegur titill',
'pageinfo-default-sort' => 'Sjálfgefinn röðunarlykill',
'pageinfo-length' => 'Lengd síðunnar (í bætum)',
'pageinfo-article-id' => 'Einkennisnúmer síðunnar',
'pageinfo-robot-policy' => 'Leitarvélastaða',
'pageinfo-robot-index' => 'Skráanleg',
'pageinfo-robot-noindex' => 'Óskráanleg',
'pageinfo-views' => 'Fjöldi innlita',
'pageinfo-watchers' => 'Fjöldi notenda, sem vakta síðuna',
'pageinfo-redirects-name' => 'Tilvísanir til þessarar síðu',
'pageinfo-subpages-name' => 'Undirsíður þessarar síðu',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|tilvísun|tilvísanir}}; $3 {{PLURAL:$3|ekki tilvísun|ekki tilvísanir}})',
'pageinfo-firstuser' => 'Stofnandi síðunnar',
'pageinfo-firsttime' => 'Dagsetning stofnunar síðunnar',
'pageinfo-lastuser' => 'Síðasti notandinn til þess að breyta',
'pageinfo-lasttime' => 'Dagsetning síðustu breytingar',
'pageinfo-edits' => 'Heildarfjöldi breytinga',
'pageinfo-authors' => 'Heildarfjöldi einstakra höfunda',
'pageinfo-recent-edits' => 'Fjöldi nýlegra breytinga á síðunni (síðustu $1)',
'pageinfo-recent-authors' => 'Fjöldi notenda sem breytt hafa síðunni nýlega',
'pageinfo-magic-words' => 'Töfra {{PLURAL:$1|orð}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Falinn flokkur|Faldir flokkar}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Innifalið snið|Innifalin snið}} ($1)',

# Skin names
'skinname-standard' => 'Sígilt',
'skinname-nostalgia' => 'Gamaldags',
'skinname-cologneblue' => 'Kölnarblátt',
'skinname-monobook' => 'EinBók',
'skinname-myskin' => 'Mitt þema',
'skinname-chick' => 'Gella',
'skinname-simple' => 'Einfalt',
'skinname-modern' => 'Nútímalegt',

# Patrolling
'markaspatrolleddiff' => 'Merkja sem yfirfarið',
'markaspatrolledtext' => 'Merkja þessa síðu sem yfirfarna',
'markedaspatrolled' => 'Merkja sem yfirfarið',
'markedaspatrolledtext' => 'Valda breytingin [[:$1]] hefur verið merkt sem yfirfarin.',
'rcpatroldisabled' => 'Slökkt á yfirferð nýlegra breytinga',
'rcpatroldisabledtext' => 'Yfirferð nýlegra breytinga er ekki virk.',
'markedaspatrollederror' => 'Get ekki merkt sem yfirfarið',
'markedaspatrollederrortext' => 'Þú verður að velja breytingu til að merkja sem yfirfarið.',
'markedaspatrollederror-noautopatrol' => 'Þú hefur ekki réttindi til að merkja eigin breytingar sem yfirfarnar.',

# Patrol log
'patrol-log-page' => 'Yfirferðarskrá',
'patrol-log-header' => 'Þetta er skrá yfir yfirfarnar breytingar.',
'log-show-hide-patrol' => '$1 Listi yfir vaktaðar síður',

# Image deletion
'deletedrevision' => 'Eyddi gamla útgáfu $1',
'filedeleteerror-short' => 'Villa við eyðingu: $1',
'filedeleteerror-long' => 'Það kom upp villa við eyðingu skráarinnar: $1',
'filedelete-missing' => 'Skránni „$1“ er ekki hægt að eyða vegna þess að hún er ekki til.',
'filedelete-old-unregistered' => 'Tilgreind útgáfa "$1" er ekki til í gagnabankanum.',
'filedelete-current-unregistered' => 'Tilgreind skrá "$1" er ekki til í gagnabankanum.',
'filedelete-archive-read-only' => 'Mistókst að skrifa möppu skjalasafna ($1) á vefþjón.',

# Browsing diffs
'previousdiff' => '← Eldri breyting',
'nextdiff' => 'Nýrri breyting →',

# Media information
'mediawarning' => "'''AÐVÖRUN''': Þessi skrá kann að hafa meinfýsinn kóða, ef keyrður kann hann að stofna kerfinu þínu í hættu.",
'imagemaxsize' => "Takmarka myndastærð:<br />''(fyrir skráarsíður)''",
'thumbsize' => 'Stærð smámynda:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|síða|síður}}',
'file-info' => 'stærð skráar: $1, MIME-tegund: $2',
'file-info-size' => '$1 × $2 dílar, stærð skráar: $3, MIME-gerð: $4',
'file-info-size-pages' => '$1 x $2 dílar, skráarstærð: $3, MIME-gerð: $4, $5 {{PLURAL:$5|síða|síður}} tengja í skránna.',
'file-nohires' => 'Það er engin hærri upplausn til.',
'svg-long-desc' => 'SVG-skrá, að nafni til $1 × $2 dílar, skráarstærð: $3',
'show-big-image' => 'Mesta upplausn',
'show-big-image-preview' => 'Stærð þessarar forskoðunar: $1',
'show-big-image-other' => '{{PLURAL:$2|Önnur upplausn|Aðrar upplausnir}}: $1.',
'show-big-image-size' => '$1 x $2 dílar',
'file-info-gif-looped' => 'síendurtekin hreyfimynd',
'file-info-gif-frames' => '$1 {{PLURAL:$1|rammi|rammar}}',
'file-info-png-looped' => 'síendurtekin hreyfimynd',
'file-info-png-repeat' => 'spilað {{PLURAL:$1|einu sinni|$1 sinnum}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|rammi|rammar}}',

# Special:NewFiles
'newimages' => 'Myndasafn nýlegra skráa',
'imagelisttext' => 'Hér fyrir neðan er {{PLURAL:$1|einni skrá|$1 skrám}} raðað $2.',
'newimages-summary' => 'Þessi kerfissíða sýnir nýlega innhlaðnar skrár.',
'newimages-legend' => 'Sía',
'newimages-label' => 'Skráarnafn (eða hluti þess):',
'showhidebots' => '($1 vélmenni)',
'noimages' => 'Ekkert að sjá.',
'ilsubmit' => 'Leita',
'bydate' => 'eftir dagsetningu',
'sp-newimages-showfrom' => 'Leita af nýjum skráum frá $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|ein sekúnda|$1 sekúndur}}',
'minutes' => '{{PLURAL:$1|ein mínúta|$1 mínútur}}',
'hours' => '{{PLURAL:$1|einn klukkutími|$1 klukkutímar}}',
'days' => '{{PLURAL:$1|einn dagur|$1 dagar}}',
'ago' => '$1 síðan',

# Bad image list
'bad_image_list' => 'Sniðið er eftirfarandi:

Aðeins listaeigindi (línur sem byrja á *) eru meðtalin.
Fyrsti tengillinn í hverri línu verður að tengja í slæma skrá.
Allir síðari tenglar á sömu línu eru taldir vera undantekningar, þ.e. síður þar sem að skráin kann að koma fyrir innfelld.',

# Metadata
'metadata' => 'Lýsigögn',
'metadata-help' => 'Þessi skrá inniheldur viðbótarupplýsingar, líklega frá stafrænu myndavélinni eða skannanum sem notaður var til að gera eða stafræna hana.
Ef skránni hefur verið breytt, kann að vera að einhverjar upplýsingar eigi ekki við um hana.',
'metadata-expand' => 'Sýna frekari upplýsingar',
'metadata-collapse' => 'Fela auka upplýsingar',
'metadata-fields' => 'EXIF-lýsigögn í þessum skilaboðum verða innifalin á síðu myndarinnar þegar tafla lýsisgangnana er samfallin.
Önnur verða sjálfkrafa falin.
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
'exif-imagewidth' => 'Breidd',
'exif-imagelength' => 'Hæð',
'exif-bitspersample' => 'Bæti á einingu',
'exif-compression' => 'Þjöppunar aðferð',
'exif-photometricinterpretation' => 'Díla myndbygging',
'exif-orientation' => 'Lega',
'exif-samplesperpixel' => 'Fjöldi eininga',
'exif-planarconfiguration' => 'Tilhögun gagna',
'exif-ycbcrpositioning' => 'Staðsetning Y og C',
'exif-xresolution' => 'Lárétt upplausn',
'exif-yresolution' => 'Lóðrétt upplausn',
'exif-stripoffsets' => 'Staðsetning gagna',
'exif-rowsperstrip' => 'Fjöldi raða á ræmu',
'exif-stripbytecounts' => 'Bæti á hverri þjappaðri ræmu',
'exif-jpeginterchangeformatlength' => 'bæti af JPEG gögnum',
'exif-whitepoint' => 'Krómatísmi hvíta punkts',
'exif-primarychromaticities' => 'Krómatísmi grunnlita',
'exif-referenceblackwhite' => 'Pör svartra og hvítra tilvísana gilda',
'exif-datetime' => 'Dagsetning og tími breytingar',
'exif-imagedescription' => 'Titill myndar',
'exif-make' => 'Framleiðandi myndavélar',
'exif-model' => 'Tegund',
'exif-software' => 'Hugbúnaður',
'exif-artist' => 'Höfundur',
'exif-copyright' => 'höfundur',
'exif-exifversion' => 'Exif-útgáfa',
'exif-flashpixversion' => 'Studd Flashpix útgáfa',
'exif-colorspace' => 'Litróf',
'exif-componentsconfiguration' => 'Merking hverrar einingar',
'exif-compressedbitsperpixel' => 'Þjöppunar aðferð',
'exif-pixelydimension' => 'Breidd myndar',
'exif-pixelxdimension' => 'Hæð myndar',
'exif-usercomment' => 'Athugunarsemdir notanda',
'exif-relatedsoundfile' => 'Tengd hljóðskrá',
'exif-datetimeoriginal' => 'Upprunaleg dagsetning',
'exif-datetimedigitized' => 'Dagsetning stafrænnar myndar',
'exif-subsectime' => 'DagsetningTími sekúndubrot',
'exif-exposuretime' => 'Lýsingartími',
'exif-exposuretime-format' => '$1 sekúnda ($2)',
'exif-exposureprogram' => 'Ljósastilling',
'exif-spectralsensitivity' => 'Litrófsnæmni',
'exif-isospeedratings' => 'ISO filmuhraði',
'exif-shutterspeedvalue' => 'APEX lokunarhraði',
'exif-aperturevalue' => 'APEX ljósop',
'exif-brightnessvalue' => 'APEX birtustig',
'exif-subjectdistance' => 'Lengd að viðfangsefni',
'exif-lightsource' => 'Uppspretta ljóssins',
'exif-flash' => 'Leifturljós',
'exif-focallength' => 'Brennivídd',
'exif-subjectarea' => 'Svæði viðfangsefnis',
'exif-flashenergy' => 'orka leifturljóss',
'exif-focalplanexresolution' => 'Upplausn brennidepils flatar X',
'exif-focalplaneyresolution' => 'Upplausn brennidepils flatar Y',
'exif-focalplaneresolutionunit' => 'Eining upplausnar brennidepils flatar',
'exif-subjectlocation' => 'Staðsetning viðfangsefnis',
'exif-exposureindex' => 'Vísistala lýsingar',
'exif-sensingmethod' => 'Skynjun',
'exif-filesource' => 'Uppruni skráar',
'exif-scenetype' => 'Myndefni',
'exif-customrendered' => 'Sérstök myndvinnsla',
'exif-exposuremode' => 'Stilling lýsingar',
'exif-whitebalance' => 'Ljóshiti',
'exif-digitalzoomratio' => 'Aðdráttar hlutfall',
'exif-focallengthin35mmfilm' => 'Brennivídd 35 mm filmu',
'exif-scenecapturetype' => 'Gerð myndefnis',
'exif-contrast' => 'Andstæður',
'exif-saturation' => 'Litstyrkur',
'exif-sharpness' => 'Skerpa',
'exif-devicesettingdescription' => 'Lýsing stillinga tækisins',
'exif-subjectdistancerange' => 'Svið lengdar á viðfangsefni',
'exif-imageuniqueid' => 'Einstakt einkenni myndar',
'exif-gpsversionid' => 'GPS tag útgáfa',
'exif-gpslatituderef' => 'Norður- eða suður breiddargráða',
'exif-gpslatitude' => 'Breiddargráða',
'exif-gpslongituderef' => 'Austur- eða vestur lengdargráða',
'exif-gpslongitude' => 'Lengdargráða',
'exif-gpsaltitude' => 'Stjörnuhæð',
'exif-gpstimestamp' => 'GPS tími (atómklukka)',
'exif-gpssatellites' => 'Gervihnettir sem voru notaðir við mælingu',
'exif-gpsstatus' => 'Staða móttakara',
'exif-gpsmeasuremode' => 'Mælingarmáti',
'exif-gpsdop' => 'Nákvæmni mælinga',
'exif-gpsspeedref' => 'Hraða eining',
'exif-gpsspeed' => 'Móttökuhraði GPS',
'exif-gpstrack' => 'Átt hreyfingar',
'exif-gpsimgdirection' => 'Stefna myndarinnar',
'exif-gpsmapdatum' => 'Landmælinga gögn',
'exif-gpsdestlatitude' => 'Breiddargráða',
'exif-gpsdestlongitude' => 'Lengdargráða',
'exif-gpsdestdistance' => 'Fjarlægð á áfangastað',
'exif-gpsprocessingmethod' => 'GPS vinnsluaðferð',
'exif-gpsareainformation' => 'Nafn GPS svæðis',
'exif-gpsdatestamp' => 'GPS dagsetning',
'exif-gpsdifferential' => 'GPS mismuns leiðrétting',
'exif-jpegfilecomment' => 'JPEG athugasemd',
'exif-keywords' => 'Lykilorð',
'exif-worldregioncreated' => 'Heimsálfa sem myndin var tekin í',
'exif-countrycreated' => 'Land sem myndin var tekin í',
'exif-countrycodecreated' => 'Kóði fyrir landið sem myndin var tekin í',
'exif-provinceorstatecreated' => 'Fylki sem myndin var tekin í',
'exif-citycreated' => 'Borg sem myndin var tekin í',
'exif-sublocationcreated' => 'Hverfi borgarinnar sem myndin var tekin í',
'exif-worldregiondest' => 'Heimsálfa sýnd',
'exif-countrydest' => 'Land sýnt',
'exif-countrycodedest' => 'Kóði fyrir landið sýndur',
'exif-provinceorstatedest' => 'Fylki sýnt',
'exif-citydest' => 'Borg sýnd',
'exif-sublocationdest' => 'Hverfi borgar sýnt',
'exif-objectname' => 'Stuttur titill',
'exif-specialinstructions' => 'Sérstakar leiðbeiningar',
'exif-headline' => 'Fyrirsögn',
'exif-source' => 'Uppruni',
'exif-urgency' => 'Nauðsyn',
'exif-objectcycle' => 'Tími dags sem efnið er ætlað fyrir',
'exif-contact' => 'Samskipta upplýsingar',
'exif-writer' => 'Ritari myndlýsingar',
'exif-languagecode' => 'Tungumál',
'exif-iimcategory' => 'Flokkur',
'exif-iimsupplementalcategory' => 'Undirflokkar',
'exif-datetimeexpires' => 'Ekki nota eftir',
'exif-datetimereleased' => 'Útgefið klukkan',
'exif-originaltransmissionref' => 'Upphaflegur sendingarkóði staðsetningar',
'exif-identifier' => 'Auðkenni',
'exif-lens' => 'Linsa notuð',
'exif-serialnumber' => 'Raðnúmer myndavélarinnar',
'exif-cameraownername' => 'Eigandi myndavélarinnar',
'exif-label' => 'Merki',
'exif-datetimemetadata' => 'Lýsigögnum síðast breytt',
'exif-nickname' => 'Látlaust nafn myndar',
'exif-rating' => 'Einkunn (af 5 mögulegum)',
'exif-copyrighted' => 'Staða höfundaréttar',
'exif-copyrightowner' => 'Eigandi höfundarétts',
'exif-usageterms' => 'Notkunar skilmálar',
'exif-webstatement' => 'Höfundaréttarleyfi á netinu',
'exif-originaldocumentid' => 'Einstakt auðkenni upphafslegs skjals',
'exif-licenseurl' => 'Vefslóð höfundarleyfis',
'exif-morepermissionsurl' => 'Aðrar leyfisupplýsingar',
'exif-attributionurl' => 'Þegar þetta verk er endurnotað, tengdu á',
'exif-pngfilecomment' => 'PNG athugasemd',
'exif-disclaimer' => 'Fyrirvari',
'exif-contentwarning' => 'Viðvörun innihalds myndar',
'exif-giffilecomment' => 'GIF athugasemd',
'exif-scenecode' => 'IPTC kóði myndefnis',
'exif-event' => 'Lýsir viðburðinum',
'exif-organisationinimage' => 'Lýsir félaginu',
'exif-originalimageheight' => 'Hæð myndarinnar fyrir skerðingu',
'exif-originalimagewidth' => 'Breidd myndar fyrir skerðingu',

# EXIF attributes
'exif-compression-1' => 'Ósamþjappað',

'exif-copyrighted-true' => 'Höfundaréttarvarið',
'exif-copyrighted-false' => 'Í Almenningi',

'exif-unknowndate' => 'Óþekkt dagsetning',

'exif-orientation-1' => 'Venjuleg',
'exif-orientation-2' => 'Speglað lárétt',
'exif-orientation-3' => 'Snýr 180°',
'exif-orientation-4' => 'Speglað lóðrétt',
'exif-orientation-5' => 'Snúið 90° rangsælis og speglað lóðrétt',
'exif-orientation-6' => 'Snýr 90° rangsælis',
'exif-orientation-7' => 'Snúið 90° réttsælis og speglað lóðrétt',
'exif-orientation-8' => 'Snýr 90° réttsælis',

'exif-planarconfiguration-2' => 'planar snið',

'exif-componentsconfiguration-0' => 'er ekki til',

'exif-exposureprogram-0' => 'Ekki skilgreind',
'exif-exposureprogram-1' => 'Handvirk',
'exif-exposureprogram-2' => 'Hefðbundin stilling',
'exif-exposureprogram-3' => 'Forgangur ljósops',
'exif-exposureprogram-4' => 'Forgangur lokara',
'exif-exposureprogram-7' => 'Andlitsmynda stilling (fyrir nærmyndir með bakrunninn í þoku)',
'exif-exposureprogram-8' => 'Landslags stilling (fyrir landslagsmyndir með skarpan bakrunn)',

'exif-subjectdistance-value' => '$1 metrar',

'exif-meteringmode-0' => 'Óþekkt',
'exif-meteringmode-1' => 'Miðlungs',
'exif-meteringmode-2' => 'Miðjuvegið meðaltal',
'exif-meteringmode-3' => 'Blettur',
'exif-meteringmode-4' => 'Margir-blettir',
'exif-meteringmode-5' => 'Mynstur',
'exif-meteringmode-255' => 'Annað',

'exif-lightsource-0' => 'Óþekkt',
'exif-lightsource-1' => 'Dagsbirta',
'exif-lightsource-2' => 'Flúrljós',
'exif-lightsource-3' => 'Wolfram ljós (hvítglóandi ljós)',
'exif-lightsource-4' => 'Leiftur',
'exif-lightsource-9' => 'Gott veður',
'exif-lightsource-10' => 'Skýjað',
'exif-lightsource-11' => 'Skuggi',
'exif-lightsource-17' => 'Staðaljós A',
'exif-lightsource-18' => 'Staðaljós B',
'exif-lightsource-19' => 'Staðaljós C',
'exif-lightsource-255' => 'Önnur ljósuppspretta',

# Flash modes
'exif-flash-fired-0' => 'Leifturljósið var slökkt',
'exif-flash-fired-1' => 'Leifturljósið kviknaði',
'exif-flash-mode-1' => 'skyldubundið leifturljós',
'exif-flash-mode-2' => 'skyldubundin bæling leifturljóss',
'exif-flash-mode-3' => 'sjálfvirkt',
'exif-flash-function-1' => 'Ekkert leifturljós',
'exif-flash-redeye-1' => 'lagfæring rauðra-augna',

'exif-focalplaneresolutionunit-2' => 'tommur',

'exif-sensingmethod-1' => 'Óskilgreint',
'exif-sensingmethod-2' => 'Einnar-kísilflögu litsviðs skynjari',
'exif-sensingmethod-3' => 'Tveggja-kísilflögu litsviðs skynjari',
'exif-sensingmethod-4' => 'Þriggja-kísilflögu litsviðs skynjari',
'exif-sensingmethod-5' => 'Raðbundinn litsviðs skynjari',

'exif-customrendered-0' => 'Venjuleg vinnsla',
'exif-customrendered-1' => 'Sérstök vinnsla',

'exif-exposuremode-0' => 'Sjálfvirk lýsing',
'exif-exposuremode-1' => 'Handstillt lýsing',

'exif-whitebalance-0' => 'Sjálfgefinn ljóshiti',
'exif-whitebalance-1' => 'Handstilltur ljóshiti',

'exif-scenecapturetype-0' => 'Staðlað',
'exif-scenecapturetype-1' => 'Landslag',
'exif-scenecapturetype-2' => 'Skammsnið',
'exif-scenecapturetype-3' => 'Næturvettvangur',

'exif-gaincontrol-0' => 'Ekkert',
'exif-gaincontrol-1' => 'Lægðar hækkun',
'exif-gaincontrol-2' => 'Hæðar hækkun',
'exif-gaincontrol-3' => 'Lægðar lækkun',
'exif-gaincontrol-4' => 'Hæðar lækkun',

'exif-contrast-0' => 'Venjuleg',
'exif-contrast-1' => 'Mjúk',
'exif-contrast-2' => 'Hörð',

'exif-saturation-0' => 'Venjulegur',
'exif-saturation-1' => 'Lítill litstyrkur',
'exif-saturation-2' => 'Mikill litstyrkur',

'exif-sharpness-0' => 'Venjulegur',
'exif-sharpness-1' => 'Mjúkur',
'exif-sharpness-2' => 'Harður',

'exif-subjectdistancerange-0' => 'Óþekkt',
'exif-subjectdistancerange-1' => 'Nærmyndar fókus',
'exif-subjectdistancerange-2' => 'Nærmynd',
'exif-subjectdistancerange-3' => 'Fjarlægt sjónarhorn',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norður breiddargráða',
'exif-gpslatitude-s' => 'Suður breiddargráða',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Austur lengdargráða',
'exif-gpslongitude-w' => 'Vestur lengdargráða',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metri|metra}} fyrir ofan sjávarmál',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metri|metra}} fyrir neðan sjávarmál',

'exif-gpsstatus-a' => 'Mæling í vinnslu',
'exif-gpsstatus-v' => 'Samvirkni mælinga',

'exif-gpsmeasuremode-2' => '2-víddar mæling',
'exif-gpsmeasuremode-3' => '3-víddar mæling',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kílómetrar á klukkustund',
'exif-gpsspeed-m' => 'Mílur á klukkustund',
'exif-gpsspeed-n' => 'Hnútar',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kílómetrar',
'exif-gpsdestdistance-m' => 'Mílur',
'exif-gpsdestdistance-n' => 'Sjómílur',

'exif-gpsdop-excellent' => 'Frábært ($1)',
'exif-gpsdop-good' => 'Gott ($1)',
'exif-gpsdop-moderate' => 'Miðlungs ($1)',
'exif-gpsdop-fair' => 'Sæmilegt ($1)',
'exif-gpsdop-poor' => 'Lélegt ($1)',

'exif-objectcycle-a' => 'að morgni',
'exif-objectcycle-p' => 'að kvöldi',
'exif-objectcycle-b' => 'að morgni og kvöldi',

'exif-ycbcrpositioning-1' => 'Miðjuð',

'exif-dc-contributor' => 'Framleggjendur',
'exif-dc-date' => 'Dagsetning(ar)',
'exif-dc-publisher' => 'Útgefandi',
'exif-dc-relation' => 'Tengd margmiðlunargögn',
'exif-dc-rights' => 'Réttindi',
'exif-dc-source' => 'Uppruni margmiðlunarskrár',

'exif-rating-rejected' => 'Hafnað',

'exif-isospeedratings-overflow' => 'Stærri en 65535',

'exif-iimcategory-ace' => 'Listir, menning og skemmtun',
'exif-iimcategory-clj' => 'Gæpir og lög',
'exif-iimcategory-dis' => 'Hamfarir og slys',
'exif-iimcategory-fin' => 'Hagkerfi og viðskipti',
'exif-iimcategory-edu' => 'Menntun',
'exif-iimcategory-evn' => 'Umhverfi',
'exif-iimcategory-hth' => 'Heilsa',
'exif-iimcategory-lab' => 'Verkamennska',
'exif-iimcategory-lif' => 'Lífstíll og tómstundagaman',
'exif-iimcategory-pol' => 'Pólitík',
'exif-iimcategory-rel' => 'Trúarbrögð og trú',
'exif-iimcategory-sci' => 'Vísindi og tækni',
'exif-iimcategory-soi' => 'Félagsleg mál',
'exif-iimcategory-spo' => 'Íþróttir',
'exif-iimcategory-war' => 'Stríð, átök og ókyrrð',
'exif-iimcategory-wea' => 'Veður',

'exif-urgency-normal' => 'Venjulegt ($1)',
'exif-urgency-low' => 'Lítið ($1)',
'exif-urgency-high' => 'Hátt ($1)',
'exif-urgency-other' => 'Mikilvægi ákveðið af notanda ($1)',

# External editor support
'edit-externally' => 'Breyta þessari skrá með utanaðkomandi hugbúnaði',
'edit-externally-help' => '(Sjá [//www.mediawiki.org/wiki/Manual:External_editors leiðbeiningar] fyrir meiri upplýsingar)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'allt',
'namespacesall' => 'öll',
'monthsall' => 'allir',
'limitall' => 'alla',

# E-mail address confirmation
'confirmemail' => 'Staðfesta netfang',
'confirmemail_noemail' => 'Þú hefur ekki gefið upp gilt netfang í [[Special:Preferences|notandastillingum]] þínum.',
'confirmemail_text' => '{{SITENAME}} krefst þess að þú staðfestir netfangið þitt áður en að þú getur notað eiginleika tengt því. Smelltu á hnappinn að neðan til að fá staðfestingarpóst sendan á netfangið. Pósturinn mun innihalda tengil með kóða í sér; opnaðu tengilinn í vafranum til að staðfesta að netfangið sé rétt.',
'confirmemail_pending' => 'Þér hefur þegar verið sendur staðfestingarkóði á netfang þitt;
ef þú varst að enda við að búa til nýtt notendanafn skaltu bíða í nokkrar mínútur og sjá hvort staðfestingarkóðinn berist þér ekki í pósti á næstunni áður en þú reynir aftur að fá nýjan staðfestingarkóða.',
'confirmemail_send' => 'Senda staðfestingarkóða með tölvupósti',
'confirmemail_sent' => 'Staðfestingartölvupóstur sendur.',
'confirmemail_oncreate' => 'Staðfestingarkóði hefur verði sendur á netfangið.
Þennan kóða þarf ekki að staðfesta til að skrá sig inn, en þú þarft að gefa hann upp áður
en opnað verður fyrir valmöguleika tengdum netfangi á þessu wiki-verkefni.',
'confirmemail_sendfailed' => '{{SITENAME}} gat ekki sent staðfestingarpóst.
Athugaðu hvort ógild tákn séu í netfanginu þínu.

Póstþjónninn skilaði: $1',
'confirmemail_invalid' => 'Ógildur staðfestingarkóði. Hann gæti verið útrunninn.',
'confirmemail_needlogin' => 'Þú verður að $1 til að staðfesta netfangið þitt.',
'confirmemail_success' => 'Netfang þitt hefur verið staðfest. Þú getur nú [[Special:UserLogin|skráð þig inn]] og vafrað um wiki-kerfið.',
'confirmemail_loggedin' => 'Netfang þitt hefur verið staðfest.',
'confirmemail_error' => 'Eitthvað fór úrskeiðis við vistun staðfestingarinnar.',
'confirmemail_subject' => 'Staðfesting netfangs á {{SITENAME}}',
'confirmemail_body' => 'Einhver, sennilega þú, með vistfangið $1 hefur skráð sig á {{SITENAME}} undir notandanafninu „$2“ og gefið upp þetta netfang.

Til að staðfesta að það hafi verið þú sem skráðir þig undir þessu nafni, og til þess að virkja póstsendingar í gegnum {{SITENAME}}, skaltu opna þennan tengil í vafranum þínum:

$3

Ef þú ert *ekki* sá/sú sem skráði þetta notandanafn, skaltu opna þennan tengil til að ógilda staðfestinguna:

$5

Þessi staðfestingarkóði rennur út $4.',
'confirmemail_body_changed' => 'Einhver, sennilega þú, með vistfangið $1 hefur breytt netfangi aðgangsins "$2" yfir á þetta netfang á {{SITENAME}}.

Til að staðfesta að það hafi verið þú sem skráðir þig undir þessu nafni, og til þess að virkja póstsendingar í gegnum {{SITENAME}}, skaltu opna þennan tengil í vafranum þínum:

$3

Ef þú ert *ekki* sá/sú sem skráði þetta notandanafn, skaltu opna þennan tengil til að ógilda staðfestinguna:

$5

Þessi staðfestingarkóði rennur út $4.',
'confirmemail_body_set' => 'Einhver, sennilega þú, með vistfangið $1 hefur gefið upp þetta netfang fyrir aðganginn "$2" á {{SITENAME}}.

Til að staðfesta að það hafi verið þú sem skráðir þig undir þessu nafni, og til þess að virkja póstsendingar í gegnum {{SITENAME}}, skaltu opna þennan tengil í vafranum þínum:

$3

Ef þú ert *ekki* sá/sú sem skráði þetta notandanafn, skaltu opna þennan tengil til að ógilda staðfestinguna:

$5

Þessi staðfestingarkóði rennur út $4.',
'confirmemail_invalidated' => 'Hætt við staðfestingu netfangs',
'invalidateemail' => 'Hætta við staðfestingu netfangs',

# Scary transclusion
'scarytranscludefailed' => '[Gat ekki sótt snið fyrir $1]',
'scarytranscludetoolong' => '[vefslóðin er of löng]',

# Delete conflict
'deletedwhileediting' => "'''Viðvörun''': Þessari síðu var eytt eftir að þú fórst að breyta henni!",
'confirmrecreate' => "Notandi [[User:$1|$1]] ([[User talk:$1|spjall]]) eyddi þessari síðu eftir að þú fórst að breyta henni út af:
: ''$2''
Vinsamlegast staðfestu að þú viljir endurvekja hana.",
'confirmrecreate-noreason' => 'Notandinn [[User:$1|$1]] ([[User talk:$1|spjall]]) eyddi þessari síðu eftir að þú fórst að breyta henni.
Vinsamlegast staðfestu að þú viljir endurvekja hana.',
'recreate' => 'Endurvekja',

# action=purge
'confirm_purge_button' => 'Í lagi',
'confirm-purge-top' => 'Hreinsa skyndiminni þessarar síðu?',
'confirm-purge-bottom' => 'Förgun síðu tæmir skyndimynnið og lætur nýjustu útgáfu síðunnar birtast.',

# action=watch/unwatch
'confirm-watch-button' => 'Í lagi',
'confirm-watch-top' => 'Bæta þessari síðu á vaktlistann þinn?',
'confirm-unwatch-button' => 'Í lagi',
'confirm-unwatch-top' => 'Fjarlægja þessa síðu af vaktlistanum þínum?',

# Multipage image navigation
'imgmultipageprev' => '← fyrri síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo' => 'Áfram!',
'imgmultigoto' => 'Fara á síðu $1',

# Table pager
'ascending_abbrev' => 'hækkandi',
'descending_abbrev' => 'lækkandi',
'table_pager_next' => 'Næsta síða',
'table_pager_prev' => 'Fyrri síða',
'table_pager_first' => 'Fyrsta síðan',
'table_pager_last' => 'Síðasta síðan',
'table_pager_limit' => 'Sýna $1 hluta á hverri síðu',
'table_pager_limit_label' => 'Færslur á síðu:',
'table_pager_limit_submit' => 'Áfram',
'table_pager_empty' => 'Engar niðurstöður',

# Auto-summaries
'autosumm-blank' => 'Tæmdi síðuna',
'autosumm-replace' => 'Skipti út innihaldi með „$1“',
'autoredircomment' => 'Tilvísun á [[$1]]',
'autosumm-new' => 'Ný síða: $1',

# Live preview
'livepreview-loading' => 'Framkalla…',
'livepreview-ready' => '… framköllun lokið!',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Breytingar nýrri en $1 {{PLURAL:$1|sekúnda|sekúndur}} kunna að vera ekki á þessm lista.',
'lag-warn-high' => 'Vegna mikils álags á vefþjónanna, kunna breytingar yngri en $1 {{PLURAL:$1|sekúnda|sekúndur}} ekki að vera á þessum lista.',

# Watchlist editor
'watchlistedit-numitems' => 'Á vaktlista þínum {{PLURAL:$1|er 1 síða|eru $1 síður}}, að undanskildum spjallsíðum.',
'watchlistedit-noitems' => 'Vaktlistinn þinn inniheldur enga titla.',
'watchlistedit-normal-title' => 'Breyta vaktlistanum',
'watchlistedit-normal-legend' => 'Fjarlægja titla af vaktlistanum',
'watchlistedit-normal-explain' => 'Titlarnir á vaktlistanum þínum er sýndir fyrir neðan.
Til að fjarlægja titil hakaðu í kassann við hliðina á honum og smelltu á „{{int:Watchlistedit-normal-submit}}“. Þú getur einnig [[Special:EditWatchlist/raw|breytt honum opnum]].',
'watchlistedit-normal-submit' => 'Fjarlægja titla',
'watchlistedit-normal-done' => '{{PLURAL:$1|Ein síða var fjarlægð|$1 síður voru fjarlægðar}} af vaktlistanum þínum:',
'watchlistedit-raw-title' => 'Breyta opnum vaktlistanum',
'watchlistedit-raw-legend' => 'Breyta opnum vaktlistanum',
'watchlistedit-raw-explain' => 'Titlarnir á vaktlistanum þínum eru sýndir hér fyrir neðan og þeim er hægt að breyta með því að bæta við og taka út af honum;
einn titil í hverri línu.
Þegar þú ert búinn, smelltu á "{{int:Watchlistedit-raw-submit}}". 
Þú getur einnig notað [[Special:EditWatchlist|hefðbundna ritilinn]].',
'watchlistedit-raw-titles' => 'Titlar:',
'watchlistedit-raw-submit' => 'Uppfæra vaktlistann',
'watchlistedit-raw-done' => 'Vaktlistinn þinn hefur verið uppfærður.',
'watchlistedit-raw-added' => '{{PLURAL:$1|Einum titli|$1 titlum}} var bætt við:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 titill var fjarlægður|$1 titlar voru fjarlægðir}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Sýna viðeigandi breytingar',
'watchlisttools-edit' => 'Skoða og breyta vaktlistanum',
'watchlisttools-raw' => 'Breyta opnum vaktlistanum',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|spjall]])',

# Core parser functions
'unknown_extension_tag' => 'Óþekkt tákn "$1"',
'duplicate-defaultsort' => '\'\'\'Viðvörun:\'\'\' Sjálfgildur flýtihnappur "$2" tekur yfir fyrri flýtihnapp "$1".',

# Special:Version
'version' => 'Útgáfa',
'version-extensions' => 'Uppsettar viðbætur',
'version-specialpages' => 'Kerfissíður',
'version-variables' => 'Breytur',
'version-antispam' => 'Amapósts sía',
'version-skins' => 'Þemu',
'version-other' => 'Aðrar',
'version-mediahandlers' => 'Rekill margmiðlunarskráa',
'version-extension-functions' => 'Aðgerðir smáforrita',
'version-parser-extensiontags' => 'Þáttuð smáforrita tög',
'version-hook-subscribedby' => 'Í áskrift af',
'version-version' => '(Útgáfa $1)',
'version-license' => 'Leyfi',
'version-poweredby-credits' => "Þessi wiki er knúin af '''[//www.mediawiki.org/ MediaWiki]''', höfundaréttur © 2001-$1 $2.",
'version-poweredby-others' => 'aðrir',
'version-license-info' => 'MediaWiki er frjáls hugbúnaður; þú mátt endurútgefa hann og/eða breyta honum undir GNU General Public leyfi eins og það er gefið út af Free Software stofnuninni, annaðhvort útgáfu 2 eða (að þínu mati) hvaða nýrri útgáfa sem er.

MediaWiki er útgefin í þeirri von að hann sé gagnlegur, en ÁN ALLRAR ÁBYRGÐAR; þar meðtalið er undanskilin ábyrgð við MARKAÐSETNINGU og að hugbúnaðurinn VIRKI Í ÁKVEÐNUM TILGANGI. Sjá GNU General Public leyfið fyrir frekari upplýsingar.

Þú ættir að hafa fengið [{{SERVER}}{{SCRIPTPATH}}/COPYING afrit af  GNU General Public leyfinu] með þessum hugbúnaði, en ef ekki, skrifaðu til Free Software stofnunarinnar, 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, Bandaríkjunum eða [//www.gnu.org/licenses/old-licenses/gpl-2.0.html lestu það á netinu]',
'version-software' => 'Uppsettur hugbúnaður',
'version-software-product' => 'Vara',
'version-software-version' => 'Útgáfa',
'version-entrypoints-header-url' => 'vefslóð',

# Special:FilePath
'filepath' => 'Slóð skráar',
'filepath-page' => 'Skrá:',
'filepath-submit' => 'Áfram',
'filepath-summary' => 'Þessi kerfisíða birtir fulla vefslóð skráar. 
Myndir eru sýndar í fullri upplausn og önnur skráarsnið eru ræst í sjálfvöldu forriti til þess að opna skránna.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Leita að afriti',
'fileduplicatesearch-summary' => 'Leita að afritum sem hafa sama hakk gildi.',
'fileduplicatesearch-legend' => 'Leita að afriti',
'fileduplicatesearch-filename' => 'Skráarnafn:',
'fileduplicatesearch-submit' => 'Leita',
'fileduplicatesearch-info' => '$1 × $2 myndeining<br />Skráarstærð: $3<br />MIME-gerð: $4',
'fileduplicatesearch-result-1' => 'Skráin „$1“ hefur engin nákvæmlega eins afrit.',
'fileduplicatesearch-result-n' => 'Skráin „$1“ hefur {{PLURAL:$2|1 nákvæmlega eins afrit|$2 nákvæmlega eins afrit}}.',
'fileduplicatesearch-noresults' => 'Mistókst að finna skránna "$1"',

# Special:SpecialPages
'specialpages' => 'Kerfissíður',
'specialpages-note' => '----
* Venjulegar kerfisíður.
* <span class="mw-specialpagerestricted">Kerfisíður með takmörkuðum aðgangi.</span>',
'specialpages-group-maintenance' => 'Viðhaldsskýrslur',
'specialpages-group-other' => 'Aðrar kerfissíður',
'specialpages-group-login' => 'Innskrá / Búa til aðgang',
'specialpages-group-changes' => 'Nýlegar breytingar og skrár',
'specialpages-group-media' => 'Miðilsskrár og innhleðslur',
'specialpages-group-users' => 'Notendur og réttindi',
'specialpages-group-highuse' => 'Mest notuðu síðurnar',
'specialpages-group-pages' => 'Listar yfir síður',
'specialpages-group-pagetools' => 'Síðuverkfæri',
'specialpages-group-wiki' => 'Wiki gögn og tól',
'specialpages-group-redirects' => 'Tilvísaðar kerfisíður',
'specialpages-group-spam' => 'Amapósts sía',

# Special:BlankPage
'blankpage' => 'Tóm síða',
'intentionallyblankpage' => 'Þessi síða er viljandi höfð tóm.',

# External image whitelist
'external_image_whitelist' => '#Ekki breyta þessari línu<pre>
#Settu brot úr reglulegum segðum (bara þann hluta sem er á milli //) hér fyrir neðan
#Þær verða bornar saman við vefslóðir ytri mynda
#Þær sem passa saman verða sýndar sem myndir, en hinar eingöngu sem tengill á myndina
#Línur sem byrja á # verða sýndar sem athugasemdir
#Þetta er hástafafrjálst

#Settu allar reglulegar segðir fyrir ofan þessa línu. Ekki breyta þessari línu.</pre>',

# Special:Tags
'tags' => 'Breyta virkum tögum',
'tag-filter' => '[[Special:Tags|Tag]] sía:',
'tag-filter-submit' => 'Sía',
'tags-title' => 'Tög',
'tags-intro' => 'Á þessari síðu er listi yfir þau tög sem hugbúnaðurinn gæti merkt breytingar með og merkingu þeirra.',
'tags-tag' => 'Nafn tags',
'tags-display-header' => 'Útlit í breytingarskrá',
'tags-description-header' => 'Tæmandi merkingarlýsing',
'tags-hitcount-header' => 'Merktar breytingar',
'tags-edit' => 'breyta',
'tags-hitcount' => '$1 {{PLURAL:$1|breyting|breytingar}}',

# Special:ComparePages
'comparepages' => 'Bera saman síður',
'compare-selector' => 'Bera saman útgáfur síðna',
'compare-page1' => 'Síða 1',
'compare-page2' => 'Síða 2',
'compare-rev1' => 'Útgáfa 1',
'compare-rev2' => 'Útgáfa 2',
'compare-submit' => 'Bera saman',
'compare-invalid-title' => 'Titillinn sem þú gafst upp er ógildur.',
'compare-title-not-exists' => 'Umbeðinn titill er ekki til.',
'compare-revision-not-exists' => 'Umbeðin útgáfa er ekki til.',

# Database error messages
'dberr-header' => 'Vandamál við þennan wiki',
'dberr-problems' => 'Því miður!
Tæknilegir örðugleikar eru á þessari síðu.',
'dberr-again' => 'Reyndu að bíða í nokkrar mínútur og endurhladdu síðan síðuna.',
'dberr-info' => '(Mistókst að hafa samband við gagnaþjón: $1)',
'dberr-usegoogle' => 'Þú getur notað Google til að leita á meðan.',
'dberr-outofdate' => 'Athugaðu að afrit þeirra gætu verið úreld.',
'dberr-cachederror' => 'Þetta er afritað eintak af umbeðinni síðu og gæti verið úreld.',

# HTML forms
'htmlform-invalid-input' => 'Vandamál við hluta af innleggi þínu',
'htmlform-select-badoption' => 'Gildið sem þú tilgreindir er ekki gildur möguleiki.',
'htmlform-int-invalid' => 'Gildið sem þú tilgreindir er ekki heil tala.',
'htmlform-float-invalid' => 'Gildið sem þú tilgreindir er ekki tala.',
'htmlform-int-toolow' => 'Gildið sem þú tilgreindir er minna en lágmarkið $1',
'htmlform-int-toohigh' => 'Gildið sem þú tilgreindir er stærra en hámarkið $1',
'htmlform-required' => 'Þú þarft að fylla út þetta gildi.',
'htmlform-submit' => 'Senda',
'htmlform-reset' => 'Taka aftur breytingu',
'htmlform-selectorother-other' => 'Annað',

# SQLite database support
'sqlite-has-fts' => '$1 með fullum texta leitar stuðningi',
'sqlite-no-fts' => '$1 án fullum texta leitar stuðningi',

# New logging system
'logentry-delete-delete' => '$1 eyddi síðunni $3',
'logentry-delete-restore' => '$1 endurvakti $3',
'logentry-delete-event' => '$1 breytti sýnileika {{PLURAL:$5|færslu|$5 færslna}} á $3: $4',
'logentry-delete-revision' => '$1 breytti sýnileika {{PLURAL:$5|útgáfu|$5 útgáfna}} á $3: $4',
'logentry-delete-event-legacy' => '$1 breytti sýnileika færslna á $3',
'logentry-delete-revision-legacy' => '$1 breytti sýnileika útgáfna á $3',
'logentry-suppress-delete' => '$1 bældi niður síðuna $3',
'logentry-suppress-event' => '$1 breytti leynilega sýnileika {{PLURAL:$5|færslu|$5 færslna}} á $3: $4',
'logentry-suppress-revision' => '$1 breytti leynilega sýnileika {{PLURAL:$5|útgáfu|$5 útgáfna}} á $3: $4',
'logentry-suppress-event-legacy' => '$1 breytti leynilega sýnileika færslna á $3',
'logentry-suppress-revision-legacy' => '$1 breytti leynilega sýnileika útgáfna á $3',
'revdelete-content-hid' => 'efni falið',
'revdelete-summary-hid' => 'breytingarágrip falið',
'revdelete-uname-hid' => 'notandanafn falið',
'revdelete-content-unhid' => 'efni birt',
'revdelete-summary-unhid' => 'breytingarágrip birt',
'revdelete-uname-unhid' => 'notandanafn birt',
'revdelete-restricted' => 'hömlur settar á stjórnendur',
'revdelete-unrestricted' => 'fjarlægja hömlur á stjórnendur',
'logentry-move-move' => '$1 færði $3 á $4',
'logentry-move-move-noredirect' => '$1 færði $3 á $4 án þess að skilja eftir tilvísun',
'logentry-move-move_redir' => '$1 færði $3 á $4 yfir tilvísun',
'logentry-move-move_redir-noredirect' => '$1 færði $3 á $4 yfir tilvísun, án þess að skilja eftir tilvísun',
'logentry-patrol-patrol' => '$1 merkti útgáfu $3 frá $4 sem yfirfarna',
'logentry-patrol-patrol-auto' => '$1 merkti sjálfvirkt útgáfu $3 frá $4 sem yfirfarna',
'logentry-newusers-newusers' => 'Notandaaðgangurinn $1 var stofnaður',
'logentry-newusers-create' => 'Notandaaðgangurinn $1 var stofnaður',
'logentry-newusers-create2' => '$1 stofnaði notandaaðganginn $3',
'logentry-newusers-autocreate' => 'Aðgangurinn $1 var stofnaður sjálfvirkt',
'newuserlog-byemail' => 'lykilorð sent með tölvupósti',

# Feedback
'feedback-bugornote' => 'Ef þú ert reiðubúinn að lýsa tæknilegri villu í smáatriðum, vinsamlegast [$1 tilkynntu villu].
Ef ekki, þá getur þú notað einfalt eyðublað hér fyrir neðan. Athugasemdin þín verður bætt við síðuna "[$3 $2]" ásamt notendanafni og nafni vafrarans sem þú ert að nota.',
'feedback-subject' => 'Fyrirsögn:',
'feedback-message' => 'Skilaboð:',
'feedback-cancel' => 'Hætta við',
'feedback-submit' => 'Senda svörun',
'feedback-adding' => 'Bæti við svörun á síðuna...',
'feedback-error1' => 'Villa: Óþekkt útkoma frá API',
'feedback-error2' => 'Villa: Breytingin mistókst',
'feedback-error3' => 'Villa: Ekkert svar frá API',
'feedback-thanks' => 'Takk! Ábendingu þinni hefur verið bætt við á síðuna "[$2 $1]".',
'feedback-close' => 'Búið',
'feedback-bugcheck' => 'Frábært! Athugaðu hvort þessi villa hafi verið [$1 tilkynnt áður].',
'feedback-bugnew' => 'Ég athugaði það. Tilkynna nýja villu.',

# Search suggestions
'searchsuggest-search' => 'Leita',
'searchsuggest-containing' => 'sem innihalda ...',

# API errors
'api-error-badaccess-groups' => 'Þú hefur ekki leyfi til að hlaða inn skrám.',
'api-error-badtoken' => 'Innri villa: Skemmdur tóki.',
'api-error-copyuploaddisabled' => 'Ekki er hægt að hlaða upp með vefslóð á þessum vefþjón.',
'api-error-duplicate' => 'Það {{PLURAL:$1|er [$2 önnur skrá]|eru[$2 aðrar skrár]}} þegar til á vefsvæðinu sem hafa sama innihald.',
'api-error-duplicate-archive' => 'Það {{PLURAL:$1|var [$2 önnur skrá]|voru [$2 aðrar skrár]}} þegar á síðunni með sama innihald, en {{PLURAL:$1|henni|þeim}} var eytt.',
'api-error-duplicate-archive-popup-title' => 'Eins {{PLURAL:$1|skrá|skrár}} sem {{PLURAL:$1|hefur|hafa}} þegar verið eytt.',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Afrituð skrá|Afritaðar skrár}}',
'api-error-empty-file' => 'Skráin sem þú valdir er tóm.',
'api-error-emptypage' => 'Stofnun nýrra, tómra síðna er óheimil.',
'api-error-fetchfileerror' => 'Innri villa: Mistókst að sækja skránna.',
'api-error-fileexists-forbidden' => 'Skrá með nafninu "$1" er þegar til og ekki er hægt að yfirskrifa hana.',
'api-error-fileexists-shared-forbidden' => 'Skrá með nafninu "$1" er þegar til á miðlæga gagnaþjóninum og ekki er hægt að yfirskrifa hana.',
'api-error-file-too-large' => 'Skráin sem þú valdir er of stór.',
'api-error-filename-tooshort' => 'Skráarnafnið er of stutt',
'api-error-filetype-banned' => 'Þessi gerð skráar er bönnuð.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|er óleyfileg skráargerð|eru óleyfilegar skráargerðir}}. {{PLURAL:$3|Leyfð skráargerð er|Leyfðar skráargerðir eru}} $2.',
'api-error-filetype-missing' => 'Skráin hefur enga skráarendingu.',
'api-error-hookaborted' => 'Hætt var við breytinguna sem þú reyndir að gera með viðbót.',
'api-error-http' => 'Innri villa: Get ekki tengst vefþjón.',
'api-error-illegal-filename' => 'Þetta skráarnafn er ekki leyft.',
'api-error-internal-error' => 'Innri villa: Mistókst að vinna úr upphali þínu.',
'api-error-invalid-file-key' => 'Innri villa: Skrá fannst ekki í tímabundinni geymslu.',
'api-error-missingparam' => 'Innri villa: Breytur vantar í beiðni.',
'api-error-missingresult' => 'Innri villa: Gat ekki ákvarðað hvort tókst að afrita.',
'api-error-mustbeloggedin' => 'Þú verður að vera skráður inn til að hlaða inn skrám.',
'api-error-mustbeposted' => 'Innri villa: Beiðnin þarfnast HTTP POST.',
'api-error-noimageinfo' => 'Upphleðsla skráarinnar tókst, en vefþjónninn gaf okkur engar upplýsingar um skránna.',
'api-error-nomodule' => 'Innri villa: Engin upphlaðs eining valin.',
'api-error-ok-but-empty' => 'Innri villa: ekkert svar frá vefþjón.',
'api-error-overwrite' => 'Óheimilt er að skrifa yfir skrá sem er þegar til.',
'api-error-stashfailed' => 'Innri villa: Vefþjónninn gat ekki geymt tímabundna skrá.',
'api-error-timeout' => 'Vefþjónninn svaraði ekki á tilætluðum tíma.',
'api-error-unclassified' => 'Óþekkt villa kom upp.',
'api-error-unknown-code' => 'Óþekkt villa: "$1"',
'api-error-unknown-error' => 'Innri villa: Eitthvað fór úrskeiðis þegar að skráinni þinni var hlaðið inn.',
'api-error-unknown-warning' => 'Óþekkt viðvörun: $1',
'api-error-unknownerror' => 'Óþekkt villa: "$1".',
'api-error-uploaddisabled' => 'Ekki er leyft að hlaða inn á þessum wiki.',
'api-error-verification-error' => 'Þessi skrá gæti verið skemmd, eða með vitlausa skráarendingu.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekúnda|sekúndur}}',
'duration-minutes' => '$1 {{PLURAL:$1|mínúta|mínútur}}',
'duration-hours' => '$1 {{PLURAL:$1|klukkustund|klukkustundir}}',
'duration-days' => '$1 {{PLURAL:$1|dagur|dagar}}',
'duration-weeks' => '$1 {{PLURAL:$1|vika|vikur}}',
'duration-years' => '$1 {{PLURAL:$1|ár|ár}}',
'duration-decades' => '$1 {{PLURAL:$1|áratugur|áratugir}}',
'duration-centuries' => '$1 {{PLURAL:$1|öld|aldir}}',
'duration-millennia' => '$1 {{PLURAL:$1|árþúsund}}',

);
