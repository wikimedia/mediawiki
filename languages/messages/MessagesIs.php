<?php
/** Icelandic (Íslenska)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Cessator
 * @author Friðrik Bragi Dýrfjörð
 * @author Gott wisst
 * @author Jóna Þórunn
 * @author Krun
 * @author Maxí
 * @author S.Örvarr.S
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
	'redirect'              => array( '0', '#tilvísun', '#TILVÍSUN', '#REDIRECT' ),
	'nogallery'             => array( '0', '__EMSAFN__', '__NOGALLERY__' ),
	'currentday'            => array( '1', 'NÚDAGUR', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'NÚDAGUR2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NÚDAGNAFN', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'NÚÁR', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'NÚTÍMI', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'NÚKTÍMI', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'STMÁN', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'STMÁNNAFN', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'STMÁNST', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'STDAGUR', 'LOCALDAY' ),
	'localday2'             => array( '1', 'STDAGUR2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'STDAGNAFN', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'STÁR', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'STTÍMI', 'LOCALTIME' ),
	'localhour'             => array( '1', 'STKTÍMI', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'FJLSÍÐA', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'FJLGREINA', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'FJLSKJALA', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'FJLNOT', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'FJLBREYT', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'SÍÐUNAFN', 'PAGENAME' ),
	'namespace'             => array( '1', 'NAFNSVÆÐI', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'SPJALLSVÆÐI', 'TALKSPACE' ),
	'fullpagename'          => array( '1', 'FULLTSÍÐUNF', 'FULLPAGENAME' ),
	'img_manualthumb'       => array( '1', 'þumall', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'hægri', 'right' ),
	'img_left'              => array( '1', 'vinstri', 'left' ),
	'img_none'              => array( '1', 'engin', 'none' ),
	'img_width'             => array( '1', '$1dp', '$1px' ),
	'img_center'            => array( '1', 'miðja', 'center', 'centre' ),
	'img_sub'               => array( '1', 'undir', 'sub' ),
	'img_super'             => array( '1', 'yfir', 'super', 'sup' ),
	'img_top'               => array( '1', 'efst', 'top' ),
	'img_bottom'            => array( '1', 'neðst', 'bottom' ),
	'img_text_bottom'       => array( '1', 'texti-neðst', 'text-bottom' ),
	'ns'                    => array( '0', 'NR:', 'NS:' ),
	'server'                => array( '0', 'VEFÞJ', 'SERVER' ),
	'servername'            => array( '0', 'VEFÞJNF', 'SERVERNAME' ),
	'grammar'               => array( '0', 'MÁLFRÆÐI:', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'NÚVIKA', 'CURRENTWEEK' ),
	'localweek'             => array( '1', 'STVIKA', 'LOCALWEEK' ),
	'plural'                => array( '0', 'FLTALA:', 'PLURAL:' ),
	'raw'                   => array( '0', 'HRÁ:', 'RAW:' ),
	'displaytitle'          => array( '1', 'SÝNATITIL', 'DISPLAYTITLE' ),
	'language'              => array( '0', '#TUNGUMÁL', '#LANGUAGE:' ),
	'special'               => array( '0', 'kerfissíða', 'special' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Tvöfaldar tilvísanir' ),
	'BrokenRedirects'           => array( 'Brotnar tilvísanir' ),
	'Disambiguations'           => array( 'Tenglar í aðgreiningarsíður' ),
	'Userlogin'                 => array( 'Innskrá' ),
	'Userlogout'                => array( 'Útskrá' ),
	'CreateAccount'             => array( 'Búa til aðgang' ),
	'Preferences'               => array( 'Stillingar' ),
	'Watchlist'                 => array( 'Vaktlistinn' ),
	'Recentchanges'             => array( 'Nýlegar breytingar' ),
	'Upload'                    => array( 'Hlaða inn' ),
	'Listfiles'                 => array( 'Myndalisti' ),
	'Newimages'                 => array( 'Nýjar myndir' ),
	'Listusers'                 => array( 'Notendalisti' ),
	'Listgrouprights'           => array( 'Réttindalisti' ),
	'Statistics'                => array( 'Tölfræði' ),
	'Randompage'                => array( 'Handahófsvalin síða' ),
	'Lonelypages'               => array( 'Munaðarlausar síður' ),
	'Uncategorizedpages'        => array( 'Óflokkaðar síður' ),
	'Uncategorizedcategories'   => array( 'Óflokkaðir flokkar' ),
	'Uncategorizedimages'       => array( 'Óflokkaðar myndir' ),
	'Uncategorizedtemplates'    => array( 'Óflokkuð snið' ),
	'Unusedcategories'          => array( 'Ónotaðir flokkar' ),
	'Unusedimages'              => array( 'Munaðarlausar myndir' ),
	'Wantedpages'               => array( 'Eftirsóttar síður' ),
	'Wantedcategories'          => array( 'Eftirsóttir flokkar' ),
	'Mostlinked'                => array( 'Mest ítengt' ),
	'Mostlinkedcategories'      => array( 'Mest ítengdu flokkar' ),
	'Mostlinkedtemplates'       => array( 'Mest ítengdu snið' ),
	'Mostimages'                => array( 'Flestar myndir' ),
	'Mostcategories'            => array( 'Flestir flokkar' ),
	'Mostrevisions'             => array( 'Flestar útgáfur' ),
	'Fewestrevisions'           => array( 'Fæstar útgáfur' ),
	'Shortpages'                => array( 'Stuttar síður' ),
	'Longpages'                 => array( 'Langar síður' ),
	'Newpages'                  => array( 'Nýjustu greinar' ),
	'Ancientpages'              => array( 'Elstu síður' ),
	'Deadendpages'              => array( 'Botnlangar' ),
	'Protectedpages'            => array( 'Verndaðar síður' ),
	'Protectedtitles'           => array( 'Verndaðir titlar' ),
	'Allpages'                  => array( 'Allar síður' ),
	'Prefixindex'               => array( 'Forskeyti' ),
	'Ipblocklist'               => array( 'Bönnuð vistföng' ),
	'Specialpages'              => array( 'Kerfissíður' ),
	'Contributions'             => array( 'Framlög' ),
	'Emailuser'                 => array( 'Senda tölvupóst' ),
	'Confirmemail'              => array( 'Staðfesta netfang' ),
	'Whatlinkshere'             => array( 'Síður sem tengjast hingað' ),
	'Recentchangeslinked'       => array( 'Nýlegar breytingar tengdar' ),
	'Movepage'                  => array( 'Færa síðu' ),
	'Blockme'                   => array( 'Banna mig' ),
	'Booksources'               => array( 'Bókaheimildir' ),
	'Categories'                => array( 'Flokkar' ),
	'Export'                    => array( 'Flytja út' ),
	'Version'                   => array( 'Útgáfa' ),
	'Allmessages'               => array( 'Meldingar' ),
	'Log'                       => array( 'Aðgerðaskrár' ),
	'Blockip'                   => array( 'Banna vistföng' ),
	'Undelete'                  => array( 'Endurvekja eydda síðu' ),
	'Import'                    => array( 'Flytja inn' ),
	'Lockdb'                    => array( 'Læsa gagnagrunni' ),
	'Unlockdb'                  => array( 'Opna gagnagrunn' ),
	'Userrights'                => array( 'Notandaréttindi' ),
	'MIMEsearch'                => array( 'MIME-leit' ),
	'FileDuplicateSearch'       => array( 'Afritunarskráarleit' ),
	'Unwatchedpages'            => array( 'Óvaktaðar síður' ),
	'Listredirects'             => array( 'Tilvísanalisti' ),
	'Revisiondelete'            => array( 'Eyðingarendurskoðun' ),
	'Unusedtemplates'           => array( 'Ónotuð snið' ),
	'Randomredirect'            => array( 'Handahófsvalin tilvísun' ),
	'Mypage'                    => array( 'Notandasíða mín' ),
	'Mytalk'                    => array( 'Spjallasíða mín' ),
	'Mycontributions'           => array( 'Framlög mín' ),
	'Listadmins'                => array( 'Stjórnendalisti' ),
	'Listbots'                  => array( 'Vélmennalisti' ),
	'Popularpages'              => array( 'Vinsælar síður' ),
	'Search'                    => array( 'Leit' ),
	'Resetpass'                 => array( 'Endurkalla aðgangsorðið' ),
	'Withoutinterwiki'          => array( 'Síður án tungumálatengla' ),
	'MergeHistory'              => array( 'Sameina breytingaskrá' ),
	'Filepath'                  => array( 'Skráarslóð' ),
	'Invalidateemail'           => array( 'Rangt netfang' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkPrefixExtension = true;
$linkTrail = '/^([áðéíóúýþæöa-z-–]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Undirstrika tengla:',
'tog-highlightbroken'         => 'Sýna brotna tengla <a href="" class="new">svona</a> (annars: svona<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Jafna málsgreinar',
'tog-hideminor'               => 'Fela minniháttar breytingar í nýlegum breytingum',
'tog-hidepatrolled'           => 'Fela yfirfarnar breytingar í nýlegum breytingum',
'tog-newpageshidepatrolled'   => 'Fela yfirfarnar breytingar í listanum yfir nýjar síður',
'tog-extendwatchlist'         => 'Útvíkka vaktlistann svo hann sýni allar viðeigandi breytingar',
'tog-usenewrc'                => 'Endurbættar auknar nýlegar breytingar (þarfnast JavaScript)',
'tog-numberheadings'          => 'Númera fyrirsagnir sjálfkrafa',
'tog-showtoolbar'             => 'Sýna breytingarverkfærastiku (JavaScript)',
'tog-editondblclick'          => 'Breyta síðum þegar tvísmellt er (JavaScript)',
'tog-editsection'             => 'Virkja hlutabreytingu með [breyta] tenglum',
'tog-editsectiononrightclick' => 'Virkja hlutabreytingu með því að hægrismella á hlutafyrirsagnir (JavaScript)',
'tog-showtoc'                 => 'Sýna efnisyfirlit (fyrir síður með meira en 3 fyrirsagnir)',
'tog-rememberpassword'        => 'Muna innskráninguna mína í þessum vafra (í allt að $1 {{PLURAL:$1|dag|daga}})',
'tog-watchcreations'          => 'Bæta síðum sem ég bý til á vaktlistann minn',
'tog-watchdefault'            => 'Bæta síðum sem ég breyti á vaktlistann minn',
'tog-watchmoves'              => 'Bæta síðum sem ég færi á vaktlistann minn',
'tog-watchdeletion'           => 'Bæta síðum sem ég eyði á vaktlistann minn',
'tog-minordefault'            => 'Merkja allar breytingar sem minniháttar sjálfgefið',
'tog-previewontop'            => 'Sýna forskoðun á undan breytingarkassanum',
'tog-previewonfirst'          => 'Sýna forskoðun með fyrstu breytingu',
'tog-nocache'                 => 'Óvirkja skyndiminni síðna',
'tog-enotifwatchlistpages'    => 'Senda mér tölvupóst þegar síðu á vaktlistanum mínu er breytt',
'tog-enotifusertalkpages'     => 'Senda mér tölvupóst þegar notandaspjallinu mínu er breytt',
'tog-enotifminoredits'        => 'Senda mér einnig tölvupóst vegna minniháttar breytinga á síðum',
'tog-enotifrevealaddr'        => 'Gefa upp netfang mitt í tilkynningarpóstum',
'tog-shownumberswatching'     => 'Sýna fjölda vaktandi notenda',
'tog-oldsig'                  => 'Undirskrift þín eins og hún er núna:',
'tog-fancysig'                => 'Taka undirskrift sem wikitexti (án sjálfkrafa tengils)',
'tog-externaleditor'          => 'Nota utanaðkomandi ritil sjálfgefið (eingöngu fyrir reynda, þarfnast sérstakra stillinga á tölvunni þinni)',
'tog-externaldiff'            => 'Nota utanaðkomandi mismun sjálfgefið (eingöngu fyrir reynda, þarfnast sérstakra stillinga á tölvunni þinni)',
'tog-showjumplinks'           => 'Virkja „stökkva á“ aðgengitengla',
'tog-uselivepreview'          => 'Nota beina forskoðun (JavaScript) (Á tilraunastigi)',
'tog-forceeditsummary'        => 'Birta áminningu þegar breytingarágripið er tómt',
'tog-watchlisthideown'        => 'Ekki sýna mínar breytingar á vaktlistanum',
'tog-watchlisthidebots'       => 'Ekki sýna breytingar vélmenna á vaktlistanum',
'tog-watchlisthideminor'      => 'Ekki sýna minniháttar breytingar á vaktlistanum',
'tog-watchlisthideliu'        => 'Ekki sýna breytingar innskráðra notenda á vaktlistanum',
'tog-watchlisthideanons'      => 'Ekki sýna breytingar óþekktra notenda á vaktlistanum',
'tog-watchlisthidepatrolled'  => 'Fela yfirfarnar breytingar í vaktlistanum',
'tog-ccmeonemails'            => 'Senda mér afrit af tölvupóstum sem ég sendi öðrum notendum',
'tog-diffonly'                => 'Ekki sýna síðuefni undir mismunum',
'tog-showhiddencats'          => 'Sýna falda flokka',

'underline-always'  => 'Alltaf',
'underline-never'   => 'Aldrei',
'underline-default' => 'skv. vafrastillingu',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Flokkur|Flokkar}}',
'category_header'                => 'Síður í flokknum „$1“',
'subcategories'                  => 'Undirflokkar',
'category-media-header'          => 'Margmiðlunarefni í flokknum „$1“',
'category-empty'                 => "''Þessi flokkur inniheldur engar síður eða margmiðlunarefni.''",
'hidden-categories'              => '{{PLURAL:$1|Falinn flokkur|Faldir flokkar}}',
'hidden-category-category'       => 'Faldir flokkar',
'category-subcat-count'          => '{{PLURAL:$2|Þessi flokkur hefur einungis eftirfarandi undirflokk.|Þessi flokkur hefur eftirfarandi {{PLURAL:$1|undirflokk|$1 undirflokka}}, af alls $2.}}',
'category-subcat-count-limited'  => 'Þessi flokkur hefur eftirfarandi {{PLURAL:$1|undirflokk|$1 undirflokka}}.',
'category-article-count'         => '{{PLURAL:$2|Þessi flokkur inniheldur aðeins eftirfarandi síðu.|Eftirfarandi {{PLURAL:$1|síða er|síður eru}} í þessum flokki, af alls $1.}}',
'category-article-count-limited' => 'Eftirfarndi {{PLURAL:$1|síða er|$1 síður eru}} í þessum flokki.',
'category-file-count'            => '{{PLURAL:$2|Þessi flokkur inniheldur einungis eftirfarandi skrá.|Eftirfarandi {{PLURAL:$1|skrá er|$1 skrár eru}} í þessum flokki, af alls $2.}}',
'category-file-count-limited'    => 'Eftirfarandi {{PLURAL:$1|skrá er|$1 skrár eru}} í þessum flokki.',
'listingcontinuesabbrev'         => 'frh.',

'linkprefix'        => '/^(.*?)([áÁðÐéÉíÍóÓúÚýÝþÞæÆöÖA-Za-z-–]+)$/sDu',
'mainpagetext'      => "'''Uppsetning á MediaWiki heppnaðist.'''",
'mainpagedocfooter' => 'Ráðfærðu þig við [http://meta.wikimedia.org/wiki/Help:Contents Notandahandbókina] fyrir frekari upplýsingar um notkun wiki-hugbúnaðarins.

== Fyrir byrjendur ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Listi yfir uppsetningarstillingar]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki Algengar spurningar MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Póstlisti MediaWiki-útgáfa]',

'about'         => 'Um',
'article'       => 'Efnissíða',
'newwindow'     => '(opnast í nýjum glugga)',
'cancel'        => 'Hætta við',
'moredotdotdot' => 'Meira...',
'mypage'        => 'Mín síða',
'mytalk'        => 'Spjall',
'anontalk'      => 'Spjallsíða þessa vistfangs.',
'navigation'    => 'Flakk',
'and'           => '&#32;og',

# Cologne Blue skin
'qbfind'         => 'Finna',
'qbbrowse'       => 'Flakka',
'qbedit'         => 'Breyta',
'qbpageoptions'  => 'Þessi síða',
'qbpageinfo'     => 'Samhengi',
'qbmyoptions'    => 'Mínar síður',
'qbspecialpages' => 'Kerfissíður',
'faq'            => 'Algengar spurningar',
'faqpage'        => 'Project:Algengar spurningar',

# Vector skin
'vector-action-addsection' => 'Bæta við umræðu',
'vector-action-delete'     => 'Eyða',
'vector-action-move'       => 'Færa',
'vector-action-protect'    => 'Vernda',
'vector-action-unprotect'  => 'Afvernda',
'vector-view-create'       => 'Skapa',
'vector-view-edit'         => 'Breyta',
'vector-view-history'      => 'Breytingaskrá',
'vector-view-view'         => 'Lesa',
'vector-view-viewsource'   => 'Sýna frumkóða',
'namespaces'               => 'Nafnrými',

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
'create'            => 'Skapa',
'editthispage'      => 'Breyta þessari síðu',
'create-this-page'  => 'Skapa þessari síðu',
'delete'            => 'Eyða',
'deletethispage'    => 'Eyða þessari síðu',
'undelete_short'    => 'Endurvekja {{PLURAL:$1|eina breytingu|$1 breytingar}}',
'protect'           => 'Vernda',
'protect_change'    => 'breyta',
'protectthispage'   => 'Vernda þessa síðu',
'unprotect'         => 'Afvernda',
'unprotectthispage' => 'Afvernda þessa síðu',
'newpage'           => 'Ný síða',
'talkpage'          => 'Ræða um þessa síðu',
'talkpagelinktext'  => 'Spjall',
'specialpage'       => 'Kerfissíða',
'personaltools'     => 'Tenglar',
'postcomment'       => 'Nýr hluti',
'articlepage'       => 'Sýna núverandi síðu',
'talk'              => 'Spjall',
'views'             => 'Sýn',
'toolbox'           => 'Verkfæri',
'userpage'          => 'Skoða notandasíðu',
'projectpage'       => 'Skoða verkefnissíðu',
'imagepage'         => 'Skoða skráarsíðu',
'mediawikipage'     => 'Skoða skilaboðasíðu',
'templatepage'      => 'Skoða sniðasíðu',
'viewhelppage'      => 'Skoða hjálparsíðu',
'categorypage'      => 'Skoða flokkatré',
'viewtalkpage'      => 'Skoða umræðu',
'otherlanguages'    => 'Á öðrum tungumálum',
'redirectedfrom'    => '(Tilvísað frá $1)',
'redirectpagesub'   => 'Tilvísunarsíða',
'lastmodifiedat'    => 'Þessari síðu var síðast breytt $1 klukkan $2.',
'viewcount'         => 'Þessi síða hefur verið skoðuð {{PLURAL:$1|einu sinni|$1 sinnum}}.',
'protectedpage'     => 'Vernduð síða',
'jumpto'            => 'Stökkva á:',
'jumptonavigation'  => 'flakk',
'jumptosearch'      => 'leita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Um {{SITENAME}}',
'aboutpage'            => 'Project:Um',
'copyright'            => 'Efni má nota samkvæmt $1.',
'copyrightpage'        => '{{ns:project}}:Höfundarréttur',
'currentevents'        => 'Potturinn',
'currentevents-url'    => 'Project:Potturinn',
'disclaimers'          => 'Fyrirvarar',
'disclaimerpage'       => 'Project:Almennur fyrirvari',
'edithelp'             => 'Breytingarhjálp',
'edithelppage'         => 'Help:Breyta',
'helppage'             => 'Help:Efnisyfirlit',
'mainpage'             => 'Forsíða',
'mainpage-description' => 'Forsíða',
'policy-url'           => 'Project:Stjórnarstefnur',
'portal'               => 'Samfélagsgátt',
'portal-url'           => 'Project:Samfélagsgátt',
'privacy'              => 'Meðferð persónuupplýsinga',
'privacypage'          => 'Project:Stefnumál um friðhelgi',

'badaccess'        => 'Aðgangsvilla',
'badaccess-group0' => 'Þú hefur ekki leyfi til að framkvæma þá aðgerð sem þú baðst um.',
'badaccess-groups' => 'Aðgerðin sem þú reyndir að framkvæma er takmörkuð notendum í {{PLURAL:$2|hópnum|einum af hópunum}}: $1.',

'versionrequired'     => 'Þarfnast úgáfu $1 af MediaWiki',
'versionrequiredtext' => 'Útgáfa $1 af MediaWiki er þörf til að geta skoðað þessa síðu.
Sjá [[Special:Version|útgáfusíðuna]].',

'ok'                      => 'Í lagi',
'retrievedfrom'           => 'Sótt frá „$1“',
'youhavenewmessages'      => 'Þú hefur fengið $1 ($2).',
'newmessageslink'         => 'ný skilaboð',
'newmessagesdifflink'     => 'síðasta breyting',
'youhavenewmessagesmulti' => 'Þín bíða ný skilaboð á $1',
'editsection'             => 'breyta',
'editold'                 => 'breyta',
'viewsourceold'           => 'skoða efni',
'editlink'                => 'breyta',
'viewsourcelink'          => 'skoða efni',
'editsectionhint'         => 'Breyti hluta: $1',
'toc'                     => 'Efnisyfirlit',
'showtoc'                 => 'sýna',
'hidetoc'                 => 'fela',
'thisisdeleted'           => 'Endurvekja eða skoða $1?',
'viewdeleted'             => 'Skoða $1?',
'restorelink'             => '{{PLURAL:$1|eina eydda breytingu|$1 eyddar breytingar}}',
'feedlinks'               => 'Streymi:',
'feed-invalid'            => 'Röng tegund áskriftarstreymis.',
'feed-unavailable'        => 'Samræmisstreymi eru ekki fáanlegt',
'site-rss-feed'           => '$1 RSS-streymi',
'site-atom-feed'          => '$1 Atom-streymi',
'page-rss-feed'           => '„$1“ RSS-streymi',
'page-atom-feed'          => '„$1“ Atom-streymi',
'red-link-title'          => '$1 (síða er ekki enn til)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Síða',
'nstab-user'      => 'Notandi',
'nstab-media'     => 'Margmiðlunarsíða',
'nstab-special'   => 'Kerfissíða',
'nstab-project'   => 'Um',
'nstab-image'     => 'Skrá',
'nstab-mediawiki' => 'Melding',
'nstab-template'  => 'Snið',
'nstab-help'      => 'Hjálp',
'nstab-category'  => 'Flokkur',

# Main script and global functions
'nosuchaction'      => 'Aðgerð ekki til',
'nosuchactiontext'  => 'Aðgerðin sem veffangið tilgreinir þekkir er ekki þekkt af wiki',
'nosuchspecialpage' => 'Kerfissíðan er ekki til',
'nospecialpagetext' => 'Þú hefur beðið um kerfissíðu sem ekki er til. Listi yfir gildar kerfissíður er að finna á [[Special:SpecialPages|kerfissíður]].',

# General errors
'error'                => 'Villa',
'databaseerror'        => 'Gagnagrunnsvilla',
'dberrortext'          => 'Málfræðivilla kom upp í gangagrnunsfyrirspurninni.
Þetta gæti verið vegna villu í hugbúnaðinum.
Síðasta gagnagrunnsfyrirspurnin var:
<blockquote><tt>$1</tt></blockquote>
úr aðgerðinni: „<tt>$2</tt>“.
MySQL skilar villuboðanum „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Málfræðivilla kom upp í gangagrnunsfyrirspurninni.
Síðasta gagnagrunnsfyrirspurnin var:
„$1“
úr aðgerðinni: „$2“.
MySQL skilar villuboðanum „$3: $4“',
'laggedslavemode'      => 'Viðvörun: Síðan inniheldur ekki nýjustu uppfærslur.',
'readonly'             => 'Gagnagrunnur læstur',
'enterlockreason'      => 'Gefðu fram ástæðu fyrir læsingunni, og einnig áætlun
un hvenær læsingunni verðu aflétt',
'readonlytext'         => 'Læst hefur verið fyrir gerð nýrra síða og breytinga í gagnagrunninum, líklega vegna viðhalds, en eftir það mun hann starfa eðlilega.

Kerfisstjórinn sem læsti honum gaf þessa skýringu: $1',
'missing-article'      => 'Gagnagrunnurinn fann ekki texta síðu sem að hann hefði átt að finna, undir nafninu „$1“ $2.

Þetta orsakast oftast þegar úreltum mismunar- eða breytingaskráartengli er fylgt að síðu sem hefur verið eytt.

Ef þetta er ekki raunin, kann að vera að þú hafir rekist á villu í hugbúnaðinum.
Gjörðu svo vel og tilkynntu atvikið til [[Special:ListUsers/sysop|stjórnanda]], og gerðu grein fyrir vefslóðinni.',
'missingarticle-rev'   => '(breyting#: $1)',
'missingarticle-diff'  => '(Munur: $1, $2)',
'readonly_lag'         => 'Gagnagrunninum hefur verið læst sjálfkrafa á meðan undirvefþjónarnir reyna að hafa í við aðalvefþjóninn',
'internalerror'        => 'Kerfisvilla',
'internalerror_info'   => 'Innri villa: $1',
'fileappenderror'      => 'Gat ekki bætt „$1“ við „$2“.',
'filecopyerror'        => 'Gat ekki afritað skjal "$1" á "$2".',
'filerenameerror'      => 'Gat ekki endurnefnt skrána „$1“ í „$2“.',
'filedeleteerror'      => 'Gat ekki eytt skránni „$1“.',
'directorycreateerror' => 'Gat ekki búið til efnisskrána "$1".',
'filenotfound'         => 'Gat ekki fundið skrána „$1“.',
'fileexistserror'      => 'Ekki var hægt að skrifa í "$1" skjalið: það er nú þegar til',
'unexpected'           => 'Óvænt gildi: „$1“=„$2“.',
'formerror'            => 'Villa: gat ekki sent eyðublað',
'badarticleerror'      => 'Þetta er ekki hægt að framkvæma á síðunni.',
'cannotdelete'         => 'Ekki var hægt að eyða síðunni eða myndinni sem valin var. (Líklegt er að einhver annar hafi gert það.)',
'badtitle'             => 'Slæmur titill',
'badtitletext'         => 'Umbeðin síðutitill er ógildur.',
'perfcached'           => 'Eftirfarandi er afrit af umbeðinni síðu og gæti því ekki verið nýjasta útgáfa hennar:',
'perfcachedts'         => 'Eftirfarandi gögn eru í skyndiminninu, og voru síðast uppfærð $1.',
'querypage-no-updates' => 'Lokað er fyrir uppfærslur af þessari síðu. Gögn sett hér munu ekki vistast.',
'wrong_wfQuery_params' => 'Röng færibreyta fyrir wfQuery()<br />
Virkni: $1<br />
Spurn: $2',
'viewsource'           => 'Skoða efni',
'viewsourcefor'        => 'fyrir $1',
'actionthrottled'      => 'Aðgerðin kafnaði',
'actionthrottledtext'  => 'Til þess að verjast ruslpósti, er ekki hægt að framkvæma þessa aðgerð of oft, og þú hefur farið fram yfir þau takmörk. Gjörðu svo vel og reyndu aftur eftir nokkrar mínútur.',
'protectedpagetext'    => 'Þessari síðu hefur verið læst til að koma í veg fyrir breytingar.',
'viewsourcetext'       => 'Þú getur skoðað og afritað kóða þessarar síðu:',
'protectedinterface'   => 'Þessi síða útvegar textann sem birtist í viðmóti hugbúnaðarins, og er læst til að koma í veg fyrir misnotkun.',
'editinginterface'     => "'''Aðvörun:''' Þú ert að breyta síðu sem hefur að geyma texta fyrir notendaumhverfi hugbúnaðarins.
Breytingar á þessari síðu munu hafa áhrif á notendaumhverfi annarra notenda.
Fyrir þýðingar, gjörðu svo vel að nota [http://translatewiki.net/wiki/Main_Page?setlang=is translatewiki.net], staðfæringverkefni MediaWiki.",
'sqlhidden'            => '(SQL-fyrirspurn falin)',
'cascadeprotected'     => 'Þessi síða hefur verið vernduð fyrir breytingum, vegna þess að hún er innifalin í eftirfarandi {{PLURAL:$1|síðu, sem er vernduð|síðum, sem eru verndaðar}} með „keðjuverndun“:
$2',
'namespaceprotected'   => "Þú hefur ekki leyfi til að breyta síðum í '''$1''' nafnrýminu.",
'customcssjsprotected' => 'Þú hefur ekki leyfi til að breyta þessari síðu, því hún hefur notandastillingar annars notanda.',
'ns-specialprotected'  => 'Kerfissíðum er ekki hægt að breyta.',
'titleprotected'       => "Þessi titill hefur verið verndaður fyrir sköpun af [[User:$1|$1]].
Ástæðan sem gefin var ''$2''.",

# Virus scanner
'virus-badscanner'     => "Slæm stilling: óþekktur veiruskannari: ''$1''",
'virus-scanfailed'     => 'skönnun mistókst (kóði $1)',
'virus-unknownscanner' => 'óþekkt mótveira:',

# Login and logout pages
'logouttext'                 => "'''Þú hefur verið skráð(ur) út.'''

Þú getur haldið áfram að nota {{SITENAME}} óþekkt(ur), eða þú getur [[Special:UserLogin|skráð þig inn aftur]] sem sami eða annar notandi.
Athugaðu að sumar síður kunna að birtast líkt og þú sért ennþá skráð(ur) inn, þangað til að þú hreinsar skyndiminnið í vafranum þínum.",
'welcomecreation'            => '== Velkomin(n), $1! ==
Aðgangurinn þinn hefur verið búinn til.
Ekki gleyma að breyta [[Special:Preferences|{{SITENAME}}-stillingunum]] þínum.',
'yourname'                   => 'Notandanafn:',
'yourpassword'               => 'Lykilorð:',
'yourpasswordagain'          => 'Endurrita lykilorð:',
'remembermypassword'         => 'Muna innskráninguna mína í þessum vafra (í allt að $1 {{PLURAL:$1|dag|daga}})',
'yourdomainname'             => 'Þitt lén:',
'login'                      => 'Innskrá',
'nav-login-createaccount'    => 'Innskrá / Búa til aðgang',
'loginprompt'                => 'Þú verður að leyfa vefkökur til þess að geta skráð þig inn á {{SITENAME}}.',
'userlogin'                  => 'Innskrá / Búa til aðgang',
'userloginnocreate'          => 'Innskrá',
'logout'                     => 'Útskráning',
'userlogout'                 => 'Útskrá',
'notloggedin'                => 'Ekki innskráð(ur)',
'nologin'                    => "Ekki með aðgang? '''$1'''.",
'nologinlink'                => 'Stofnaðu til aðgangs',
'createaccount'              => 'Nýskrá',
'gotaccount'                 => "Nú þegar með notandanafn? '''$1'''.",
'gotaccountlink'             => 'Skráðu þig inn',
'createaccountmail'          => 'með tölvupósti',
'badretype'                  => 'Lykilorðin sem þú skrifaðir eru ekki eins.',
'userexists'                 => 'Þetta notandanafn er þegar í notkun. Vinsamlegast veldu þér annað.',
'loginerror'                 => 'Innskráningarvilla',
'createaccounterror'         => 'Gat ekki búið til notanda: $1',
'nocookiesnew'               => 'Innskráningin var búin til, en þú ert ekki skráð(ur) inn.
{{SITENAME}} notar vefkökur til að skrá inn notendur.
Þú hefur lokað fyrir vefkökur.
Gjörðu svo vel og opnaðu fyrir þær, skráðu þig svo inn með notandanafni og lykilorði.',
'nocookieslogin'             => '{{SITENAME}} notar vefkökur til innskráningar. Vafrinn þinn er ekki að taka á móti þeim sem gerir það ókleyft að innskrá þig. Vinsamlegast virkjaðu móttöku kakna í vafranum þínum til að geta skráð þig inn.',
'noname'                     => 'Þú hefur ekki tilgreint gilt notandanafn.',
'loginsuccesstitle'          => 'Innskráning tókst',
'loginsuccess'               => "'''Þú ert nú innskráð(ur) á {{SITENAME}} sem „$1“.'''",
'nosuchuser'                 => 'Það er enginn notandi með nafnið „$1“.
Athugaðu stafsetning, eða [[Special:UserLogin/signup|búðu til aðgang]].',
'nosuchusershort'            => 'Það er enginn notandi með nafnið „<nowiki>$1</nowiki>“. Athugaðu hvort nafnið sé ritað rétt.',
'nouserspecified'            => 'Þú verður að taka fram notandanafn.',
'wrongpassword'              => 'Uppgefið lykilorð er rangt. Vinsamlegast reyndu aftur.',
'wrongpasswordempty'         => 'Lykilorðsreiturinn var auður. Vinsamlegast reyndu aftur.',
'passwordtooshort'           => 'Lykilorðið þitt er ógilt eða of stutt.
Það verður að hafa að minnsta kosti {{PLURAL:$1|1 rittákn|$1 rittákn}} og einnig frábrugðið notandanafninu þínu.',
'password-name-match'        => 'Þarf að lykilorð þitt sé öðruvísi notandanafni þínu',
'mailmypassword'             => 'Senda nýtt lykilorð með tölvupósti',
'passwordremindertitle'      => 'Nýtt tímabundið aðgangsorð fyrir {{SITENAME}}',
'passwordremindertext'       => 'Einhver (líklegast þú, á vistfanginu $1) hefur beðið um að fá nýtt
lykilorð fyrir {{SITENAME}} ($4). Tímabundið lykilorð fyrir notandan „$2“
hefur verið búið til og er núna „$3“. Ef þetta var vilji þinn, þarfu að skrá
þig inn og velja nýtt lykilorð.

Ef einhver annar fór fram á þessa beiðni, eða ef þú mannst lykilorðið þitt,
og vilt ekki lengur breyta því, skaltu hunsa þetta skilaboð og
halda áfram að nota gamla lykilorðið.',
'noemail'                    => 'Það er ekkert netfang skráð fyrir notandan "$1".',
'noemailcreate'              => 'Þú verður að skrá gilt netfang',
'passwordsent'               => 'Nýtt lykilorð var sent á netfangið sem er skráð á „$1“.
Vinsamlegast skráðu þig inn á ný þegar þú hefur móttekið það.',
'blocked-mailpassword'       => 'Þér er ekki heimilt að gera breytingar frá þessu netfangi og  því getur þú ekki fengið nýtt lykilorð í pósti.  Þetta er gert til þess að koma í veg fyrir skemmdarverk.',
'eauthentsent'               => 'Staðfestingarpóstur hefur verið sendur á uppgefið netfang. Þú verður að fylgja leiðbeiningunum í póstinum til þess að virkja netfangið og staðfesta að það sé örugglega þitt.',
'throttled-mailpassword'     => 'Áminning fyrir lykilorð hefur nú þegar verið send, innan við {{PLURAL:$1|síðasta klukkutímans|$1 síðustu klukkutímanna}}.
Til að koma í veg fyrir misnotkun, er aðeins ein áminning send {{PLURAL:$1|hvern klukkutíma|hverja $1 klukkutíma}}.',
'mailerror'                  => 'Upp kom villa við sendingu tölvupósts: $1',
'acct_creation_throttle_hit' => 'Því miður, þú hefur nú þegar búið til {{PLURAL:$1|1 aðgang|$1 aðganga}}.
Þú getur ekki búið til fleiri.',
'emailauthenticated'         => 'Netfang þitt var staðfest þann $2 klukkan $3.',
'emailnotauthenticated'      => 'Veffang þitt hefur ekki enn verið sannreynt. Enginn póstur verður sendur af neinum af eftirfarandi eiginleikum.',
'noemailprefs'               => 'Tilgreindu netfang svo þessar aðgerðir virki.',
'emailconfirmlink'           => 'Staðfesta netfang þitt',
'invalidemailaddress'        => 'Ekki er hægt að taka við netfangi þínu þar sem að það er á ógildu formi.
Gjörðu svo vel og settu inn netfang á gildu formi eða tæmdu reitinn.',
'accountcreated'             => 'Aðgangur búinn til',
'accountcreatedtext'         => 'Notandaaðgangur fyrir $1 er tilbúinn.',
'createaccount-title'        => 'Innskráningagerð á {{SITENAME}}',
'createaccount-text'         => 'Einhver bjó til aðgang fyrir netfangið þitt á {{SITENAME}} ($4) undir nafninu „$2“, með lykilorðið „$3“.
Þú ættir að skrá þig inn og breyta lykilorðinu núna.

Þú getur hunsað þetta skilaboð, ef villa hefur átt sér stað.',
'login-throttled'            => 'Þú hefur gert of margar tilraunir nýlega á lykilorð þessa aðgangs.
Gjörðu svo vel og bíddu áður en að þú reynir aftur.',
'loginlanguagelabel'         => 'Tungumál: $1',

# Password reset dialog
'resetpass'                 => 'Breyta lykilorði',
'resetpass_announce'        => 'Þú skráðir þig inn með tímabundnum netfangskóða.
Til að klára að skrá þig inn, verður þú að endurstilla lykilorðið hér:',
'resetpass_text'            => '<!-- Setja texta hér -->',
'resetpass_header'          => 'Breyta lykilorði',
'oldpassword'               => 'Gamla lykilorðið',
'newpassword'               => 'Nýja lykilorðið',
'retypenew'                 => 'Endurtaktu nýja lykilorðið:',
'resetpass_submit'          => 'Skrifaðu aðgangsorðið og skráðu þig inn',
'resetpass_success'         => 'Aðgangsorðinu þínu hefur verið breytt! Skráir þig inn...',
'resetpass_forbidden'       => 'Ekki er hægt að breyta lykilorðum',
'resetpass-submit-loggedin' => 'Breyta lykilorði',
'resetpass-temp-password'   => 'Tímabundið lykilorð:',

# Edit page toolbar
'bold_sample'     => 'Feitletraður texti',
'bold_tip'        => 'Feitletraður texti',
'italic_sample'   => 'Skáletraður texti',
'italic_tip'      => 'Skáletraður texti',
'link_sample'     => 'Titill tengils',
'link_tip'        => 'Innri tengill',
'extlink_sample'  => 'http://www.example.com titill tengils',
'extlink_tip'     => 'Ytri tengill (munið að setja http:// á undan)',
'headline_sample' => 'Fyrirsagnartexti',
'headline_tip'    => 'Annars stigs fyrirsögn',
'math_sample'     => 'Sláið inn formúlu hér',
'math_tip'        => 'Stærðfræðiformúla (LaTeX)',
'nowiki_sample'   => 'Innsetjið ósniðinn texta hér',
'nowiki_tip'      => 'Hunsa wiki-snið',
'image_sample'    => 'Sýnishorn.jpg',
'image_tip'       => 'Innfellt skjal',
'media_sample'    => 'Sýnishorn.ogg',
'media_tip'       => 'Tengill skjals',
'sig_tip'         => 'Undirskrift þín auk tímasetningar',
'hr_tip'          => 'Lárétt lína (notist sparlega)',

# Edit pages
'summary'                          => 'Breytingarágrip:',
'subject'                          => 'Fyrirsögn:',
'minoredit'                        => 'Þetta er minniháttar breyting',
'watchthis'                        => 'Vakta þessa síðu',
'savearticle'                      => 'Vista síðu',
'preview'                          => 'Forskoða',
'showpreview'                      => 'Forskoða',
'showlivepreview'                  => 'Forskoða',
'showdiff'                         => 'Sýna breytingar',
'anoneditwarning'                  => "'''Viðvörun:''' Þú ert ekki innskráð(ur). Vistfang þitt skráist í breytingaskrá síðunnar.",
'missingsummary'                   => "'''Áminning:''' Þú hefur ekki skrifað breytingarágrip.
Ef þú smellir á Vista aftur, verður breyting þín vistuð án þess.",
'missingcommenttext'               => 'Gerðu svo vel og skrifaðu athugasemd fyrir neðan.',
'missingcommentheader'             => "'''Áminning:''' Þú hefur ekki gefið upp umræðuefni/fyrirsögn.
Ef þú smellir á Vista aftur, verður breyting þín vistuð án þess.",
'summary-preview'                  => 'Forskoða breytingarágrip:',
'subject-preview'                  => 'Forskoðun umræðuefnis/fyrirsagnar:',
'blockedtitle'                     => 'Notandi er bannaður',
'blockedtext'                      => "'''Notandanafn þitt eða vistfang hefur verið bannað.'''

Bannið var sett af $1.
Ástæðan er eftirfarandi: ''$2''.

* Bannið hófst: $8
* Banninu lýkur: $6
* Sá sem banna átti: $7

Þú getur haft samband við $1 eða annan [[{{MediaWiki:Grouppage-sysop}}|stjórnanda]] til að ræða bannið.
Þú getur ekki notað „Senda þessum notanda tölvupóst“ aðgerðina nema gilt netfang sé skráð í [[Special:Preferences|notandastillingum þínum]] og að þér hafi ekki verið óheimilað það.
Núverandi vistfang þitt er $3, og bönnunarnúmerið er #$5.
Vinsamlegast tilgreindu allt að ofanverðu í fyrirspurnum þínum.",
'autoblockedtext'                  => "Vistfang þitt hefur verið sjálfvirkt bannað því það var notað af öðrum notanda, sem var bannaður af $1.
Ástæðan er eftirfarandi:

:''$2''

* Bannið hófst: $8
* Banninu lýkur: $6
* Sá sem banna átti: $7

Þú getur haft samband við $1 eða annan [[{{MediaWiki:Grouppage-sysop}}|stjórnanda]] til að ræða bannið.

Athugaðu að þú getur ekki notað „Senda þessum notanda tölvupóst“ aðgerðina nema gilt netfang sé skráð í [[Special:Preferences|notandastillingum þínum]] og að þér hafi ekki verið óheimilað það.

Núverandi vistfang þitt er $3, og bönnunarnúmerið er #$5.
Vinsamlegast tilgreindu allt að ofanverðu í fyrirspurnum þínum.",
'blockednoreason'                  => 'engin ástæða gefin',
'blockedoriginalsource'            => "Efni '''$1''' er sýnt fyrir neðan:",
'blockededitsource'                => "Texti '''þinna breytinga''' á '''$1''' eru sýndar að neðan:",
'whitelistedittitle'               => 'Innskráningar er þörf til að breyta',
'whitelistedittext'                => 'Þú þarft að $1 til að breyta síðum.',
'confirmedittext'                  => 'Þú verður að staðfesta netfangið þitt áður en þú getur breytt síðum. Vinsamlegast stilltu og staðfestu netfangið þitt í gegnum [[Special:Preferences|stillingarnar]].',
'nosuchsectiontitle'               => 'Hluti ekki til',
'nosuchsectiontext'                => 'Það hefur komið upp villa.',
'loginreqtitle'                    => 'Innskráningar krafist',
'loginreqlink'                     => 'innskrá',
'loginreqpagetext'                 => 'Þú þarft að $1 til að geta séð aðrar síður.',
'accmailtitle'                     => 'Lykilorð sent.',
'accmailtext'                      => 'Lykilorðið fyrir „$1“ hefur verið sent á $2.',
'newarticle'                       => '(Ný)',
'newarticletext'                   => "Þú hefur fylgt tengli á síðu sem ekki er til.
Þú getur búið til síðu með þessu nafni með því að skrifa í formið fyrir neðan
(meiri upplýsingar í [[{{MediaWiki:Helppage}}|hjálpinni]]).
Ef þú hefur óvart villst hingað geturðu notað '''til baka'''-hnappinn í vafranum þínum.",
'anontalkpagetext'                 => "----''Þetta er spjallsíða fyrir óþekktan notanda sem hefur ekki búið til aðgang ennþá, eða notar hann ekki.
Þar af leiðandi þurfum við að nota vistfang til að bera kennsli á hann/hana.
Nokkrir notendur geta deilt sama vistfangi.
Ef þú ert óþekktur notandi og finnst að óviðkomandi athugasemdum hafa verið beint að þér, gjörðu svo vel og [[Special:UserLogin/signup|búðu til aðgang]] eða [[Special:UserLogin|skráðu þig inn]] til þess að koma í veg fyrir þennan rugling við aðra óþekkta notendur í framtíðinni.''",
'noarticletext'                    => 'Enginn texti er á þessari síðu enn sem komið er.
Þú getur [[Special:Search/{{PAGENAME}}|leitað í öðrum síðum]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} leitað í tengdum skrám], eða [{{fullurl:{{FULLPAGENAME}}|action=edit}} breytt henni sjálfur]</span>.',
'userpage-userdoesnotexist'        => 'Notandaaðgangurinn „$1“ er ekki skráður.
Gjörðu svo vel og athugaðu hvort að þú viljir skapa/breyta þessari síðu.',
'clearyourcache'                   => "'''Athugaðu - Eftir vistun, má vera að þú þurfir að komast hjá skyndiminni vafrans þíns til að sjá breytingarnar.'''
'''Mozilla / Firefox / Safari:''' haltu ''Shift'' og smelltu á ''Reload'', eða ýttu á annaðhvort ''Ctrl-F5'' eða ''Ctrl-R'' (''Command-R'' á Macintosh);
'''Konqueror: '''smelltu á ''Reload'' eða ýttu á ''F5'';
'''Opera:''' hreinsaðu skyndiminnið í ''Tools → Prefernces'';
'''Internet Explorer:''' haltu ''Ctrl'' og smelltu á ''Refresh'', eða ýttu á ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Ath:''' Hægt er að nota „Forskoða“ hnappinn til að prófa CSS og JavaScript-kóða áður en hann er vistaður.",
'userjsyoucanpreview'              => "'''Ath:''' Hægt er að nota „Forskoða“ hnappinn til að prófa CSS og JavaScript-kóða áður en hann er vistaður.",
'usercsspreview'                   => "'''Mundu að þú ert aðeins að forskoða CSS-kóðann þinn.'''
'''Hann hefur ekki enn verið vistaður!'''",
'userjspreview'                    => "'''Mundu að þú ert aðeins að prófa/forskoða JavaScript-kóðann þinn.'''
'''Hann hefur ekki enn verið vistaður!'''",
'updated'                          => '(Uppfært)',
'note'                             => "'''Athugið:'''",
'previewnote'                      => "'''Það sem sést hér er aðeins forskoðun og hefur ekki enn verið vistað!'''",
'session_fail_preview'             => "'''Því miður! Gat ekki unnið úr breytingum þínum vegna týndra lotugagna.
Vinsamlegast reyndu aftur síðar. Ef það virkar ekki heldur skaltu reyna að skrá þig út og inn á ný.'''",
'editing'                          => 'Breyti $1',
'editingsection'                   => 'Breyti $1 (hluta)',
'editingcomment'                   => 'Breyti $1 (nýr hluti)',
'editconflict'                     => 'Breytingaárekstur: $1',
'explainconflict'                  => "Síðunni hefur verið breytt síðan þú byrjaðir að gera breytingar á henni, textinn í efri reitnum inniheldur núverandi útgáfu úr gagnagrunni og sá neðri inniheldur þína útgáfu, þú þarft hér að færa breytingar sem þú vilt halda úr neðri reitnum í þann efri og vista síðuna. 
'''Aðeins''' texti úr efri reitnum mun vera vistaður þegar þú vistar.",
'yourtext'                         => 'Þinn texti',
'storedversion'                    => 'Geymd útgáfa',
'editingold'                       => "'''ATH: Þú ert að breyta gamalli útgáfu þessarar síðu og munu allar breytingar sem gerðar hafa verið á henni frá þeirri útgáfu vera fjarlægðar ef þú vistar.'''",
'yourdiff'                         => 'Mismunur',
'copyrightwarning'                 => "Vinsamlegast athugaðu að öll framlög á {{SITENAME}} eru álitin leyfisbundin samkvæmt $2 (sjá $1 fyrir frekari upplýsingar).  Ef þú vilt ekki að skrif þín falli undir þetta leyfi og öllum verði frjálst að breyta og endurútgefa efnið samkvæmt því skaltu ekki leggja þau fram hér.<br />
Þú berð ábyrgð á framlögum þínum, þau verða að vera þín skrif eða afrit texta í almannaeigu eða sambærilegs frjáls texta.
'''AFRITIÐ EKKI HÖFUNDARRÉTTARVARIN VERK Á ÞESSA SÍÐU ÁN LEYFIS'''",
'copyrightwarning2'                => "Vinsamlegast athugið að aðrir notendur geta breytt eða fjarlægt öll framlög til {{SITENAME}}.
Ef þú vilt ekki að textanum verði breytt skaltu ekki senda hann inn hér.<br />
Þú lofar okkur einnig að þú hafir skrifað þetta sjálfur, að efnið sé í almannaeigu eða að það heyri undir frjálst leyfi. (sjá $1).
'''EKKI SENDA INN HÖFUNDARRÉTTARVARIÐ EFNI ÁN LEYFIS RÉTTHAFA!'''",
'longpagewarning'                  => "'''VIÐVÖRUN: Þessi síða er $1 kílóbæta löng; sumir
vafrar gætu átt erfitt með að gera breytingar á síðum sem nálgast eða eru lengri en 32 kb.
Vinsamlegast íhugaðu að skipta síðunni niður í smærri einingar.'''",
'longpageerror'                    => "'''VILLA: Textinn sem þú sendir inn er $1 kílóbæti að lengd, en hámarkið er $2 kílóbæti. Ekki er hægt að vista textann.'''",
'readonlywarning'                  => "'''AÐVÖRUN: Gagnagrunninum hefur verið læst til að unnt sé að framkvæma viðhaldsaðgerðir, svo þú getur ekki vistað breytingar þínar núna.
Þú kannt að vilja að klippa og líma textann í textaskjal og vista hann fyrir síðar.'''

Stjórnandinn sem læsti honum gaf þessa skýringu: $1",
'protectedpagewarning'             => "'''Viðvörun: Þessari síðu hefur verið læst svo aðeins notendur með möppudýraréttindi geti breytt henni.'''",
'semiprotectedpagewarning'         => "'''Athugið''': Þessari síðu hefur verið læst þannig að aðeins innskráðir notendur geti breytt henni.",
'titleprotectedwarning'            => "'''VIÐVÖRUN: Þessari síðu hefur verið læst svo aðeins [[Special:ListGroupRights|sérstakir notendur]] geta breytt henni.'''",
'templatesused'                    => 'Snið {{PLURAL:$1|notað|notuð}} á þessari síðu:',
'templatesusedpreview'             => 'Snið {{PLURAL:$1|notað|notuð}} í forskoðuninni:',
'templatesusedsection'             => 'Snið notuð á hlutanum:',
'template-protected'               => '(vernduð)',
'template-semiprotected'           => '(hálfvernduð)',
'hiddencategories'                 => 'Þessi síða er meðlimur í {{PLURAL:$1|1 földum flokki|$1 földum flokkum}}:',
'nocreatetitle'                    => 'Síðugerð takmörkuð',
'nocreatetext'                     => '{{SITENAME}} hefur takmarkað eiginleikann að gera nýjar síður.
Þú getur farið til baka og breytt núverandi síðum, eða [[Special:UserLogin|skráð þið inn eða búið til aðgang]].',
'nocreate-loggedin'                => 'Þú hefur ekki leyfi til að skapa nýjar síður.',
'permissionserrors'                => 'Leyfisvillur',
'permissionserrorstext'            => 'Þú hefur ekki leyfi til að gera þetta, af eftirfarandi {{PLURAL:$1|ástæðu|ástæðum}}:',
'permissionserrorstext-withaction' => 'Þú hefur ekki réttindi til að $2, af eftirfarandi {{PLURAL:$1|ástæðu|ástæðum}}:',
'recreate-moveddeleted-warn'       => "'''Viðvörun: Þú ert að endurskapa síðu sem áður hefur verið eytt.'''

Athuga skal hvort viðeigandi sé að gera þessa síðu.
Eyðingarskrá og flutningaskrá fyrir þessa síðu eru útvegaðar hér til þæginda:",
'moveddeleted-notice'              => 'Þessari síðu hefur verið eytt.
Eyðingaskrá og flutningaskrá síðunnar eru gefnar fyrir neðan til tilvísunar.',
'edit-gone-missing'                => 'Gat ekki uppfært síðu.
Svo virðist sem henni hafi verið eytt.',
'edit-conflict'                    => 'Breytingaárekstur.',
'edit-no-change'                   => 'Breyting þín var hunsuð, því engin breyting var á textanum.',
'edit-already-exists'              => 'Gat ekki skapað nýja síðu.
Hún er nú þegar til.',

# Parser/template warnings
'parser-template-loop-warning' => 'Lykkja í sniði fundin: [[$1]]',

# "Undo" feature
'undo-success' => 'Breytingin hefur verið tekin tilbaka. Vinsamlegast staðfestu og vistaðu svo.',
'undo-failure' => 'Breytinguna var ekki hægt að taka tilbaka vegna breytinga í millitíðinni.',
'undo-norev'   => 'Ekki var hægt að taka breytinguna aftr því að hún er ekki til eða henni var eytt.',
'undo-summary' => 'Tek aftur breytingu $1 frá [[Special:Contributions/$2|$2]] ([[User talk:$2|spjall]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ekki hægt að búa til aðgang',
'cantcreateaccount-text' => "Aðgangsgerð fyrir þetta vistfang ('''$1''') hefur verið bannað af [[User:$3|$3]].

Ástæðan sem $3 gaf fyrir því er ''$2''",

# History pages
'viewpagelogs'           => 'Sýna aðgerðir varðandi þessa síðu',
'nohistory'              => 'Þessi síða hefur enga breytingaskrá.',
'currentrev'             => 'Núverandi útgáfa',
'currentrev-asof'        => 'Núverandi breyting frá og með $1',
'revisionasof'           => 'Útgáfa síðunnar $1',
'revision-info'          => 'Útgáfa frá $1 eftir $2',
'previousrevision'       => '←Fyrri útgáfa',
'nextrevision'           => 'Næsta útgáfa→',
'currentrevisionlink'    => 'Núverandi útgáfa',
'cur'                    => 'fyrri',
'next'                   => 'næst',
'last'                   => 'þessa',
'page_first'             => 'fyrsta',
'page_last'              => 'síðasta',
'histlegend'             => 'Mismunarval: merktu við einvalshnappanna fyrir þær útgáfur sem á að bera saman og styddu svo á færsluhnappinn.<br />
Skýringartexti: (nú) = skoðanamunur á núverandi útgáfu,
(síðast) = skoðanamunur á undanfarandi útgáfu, M = minniháttar breyting.',
'history-fieldset-title' => 'Skoða breytingaskrá',
'histfirst'              => 'elstu',
'histlast'               => 'yngstu',
'historysize'            => '({{PLURAL:$1|1 bæti|$1 bæti}})',
'historyempty'           => '(tóm)',

# Revision feed
'history-feed-title'          => 'Breytingaskrá',
'history-feed-description'    => 'Breytingaskrá fyrir þessa síðu á wiki-síðunni',
'history-feed-item-nocomment' => '$1 á $2',
'history-feed-empty'          => 'Síðan sem þú leitaðir að er ekki til.
Möglegt er að henni hafi verið eytt út af þessari wiki síðu, eða endurnefnd.
Prófaðu [[Special:Search|að leita á þessari wiki síðu]] að svipuðum síðum.',

# Revision deletion
'rev-deleted-comment'        => '(athugasemd fjarlægð)',
'rev-deleted-user'           => '(notandanafn fjarlægt)',
'rev-deleted-event'          => '(skráarbreyting fjarlægð)',
'rev-delundel'               => 'sýna/fela',
'rev-showdeleted'            => 'sýna',
'revisiondelete'             => 'Eyða/endurvekja breytingar',
'revdelete-nooldid-title'    => 'Ógild markbreyting',
'revdelete-show-file-submit' => 'Já',
'revdelete-selected'         => "'''{{PLURAL:$2|Valin breyting|Valdar breytingar}} fyrir [[:$1]]:'''",
'logdelete-selected'         => "'''{{PLURAL:$1|Valin aðgerð|Valdar aðgerðir}}:'''",
'revdelete-legend'           => 'Setja sjáanlegar hamlanir',
'revdelete-hide-text'        => 'Fela breytingatexta',
'revdelete-hide-image'       => 'Fela efni skráar',
'revdelete-hide-name'        => 'Fela aðgerð og mark',
'revdelete-hide-comment'     => 'Fela breytingaathugasemdir',
'revdelete-hide-user'        => 'Fela notandanafn/vistfang',
'revdelete-hide-restricted'  => 'Setja þessar hömlur á fyrir stjórnendur og læsa viðmótinu',
'revdelete-radio-same'       => '(ekki breyta)',
'revdelete-radio-set'        => 'Já',
'revdelete-radio-unset'      => 'Nei',
'revdelete-suppress'         => 'Dylja gögn frá stjórnendum og öðrum',
'revdelete-log'              => 'Ástæða:',
'revdelete-submit'           => 'Setja á valda breytingu',
'revdel-restore'             => 'Breyta sýn',
'pagehist'                   => 'Breytingaskrá',
'deletedhist'                => 'Eyðingaskrá',
'revdelete-content'          => 'efni',
'revdelete-summary'          => 'breytingarágrip',
'revdelete-uname'            => 'notandanafn',
'revdelete-restricted'       => 'hömlur settar á stjórnendur',
'revdelete-unrestricted'     => 'fjarlægja hömlur á stjórnendur',
'revdelete-log-message'      => '$1 fyrir $2 {{PLURAL:$2|breytingu|breytingar}}',
'revdelete-edit-reasonlist'  => 'Eyðingarástæður',

# History merging
'mergehistory'      => 'Sameina breytingaskrár',
'mergehistory-from' => 'Heimildsíða:',
'mergehistory-into' => 'Áætlunarsíða:',

# Merge log
'mergelog'    => 'Sameina skrá',
'revertmerge' => 'Taka aftur sameiningu',

# Diffs
'history-title'            => 'Breytingaskrá fyrir "$1"',
'difference'               => '(Munur milli útgáfa)',
'lineno'                   => 'Lína $1:',
'compareselectedversions'  => 'Bera saman valdar útgáfur',
'showhideselectedversions' => 'Sýna/fela valdar breytingar',
'editundo'                 => 'Taka aftur þessa breytingu',
'diff-multi'               => '({{PLURAL:$1|Ein millibreyting ekki sýnd|$1 millibreytingar ekki sýndar}}.)',

# Search results
'searchresults'                    => 'Leitarniðurstöður',
'searchresults-title'              => 'Leitarniðurstöður fyrir „$1“',
'searchresulttext'                 => 'Fyrir frekari upplýsingar um leit á {{SITENAME}} farið á [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Þú leitaðir að '''[[:$1]]''' ([[Special:Prefixindex/$1|öllum síðum sem hefjast á „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|öllum síðum sem tengja í „$1“]])",
'searchsubtitleinvalid'            => "Þú leitaðir að '''$1'''",
'toomanymatches'                   => 'Of mörgum niðurstöðum var skilað, gjörðu svo vel og reyndu aðra fyrirspurn',
'titlematches'                     => 'Titlar greina sem pössuðu við fyrirspurnina',
'notitlematches'                   => 'Engir greinartitlar pössuðu við fyrirspurnina',
'textmatches'                      => 'Leitarorð fannst/fundust í innihaldi eftirfarandi greina',
'notextmatches'                    => 'Engar samsvaranir á texta í síðum',
'prevn'                            => 'síðustu {{PLURAL:$1|$1}}',
'nextn'                            => 'næstu {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Skoða ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Leitarvalmöguleikar',
'searchmenu-exists'                => "'''Það er síða að nafni „[[$1]]“ á þessum wiki'''",
'searchmenu-new'                   => "'''Skapaðu síðuna \"[[:\$1]]\" á þessum wiki!'''",
'searchhelp-url'                   => 'Help:Efnisyfirlit',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Leita að síðum með þessu forskeyti]]',
'searchprofile-articles'           => 'Efnissíður',
'searchprofile-project'            => 'Hjálpar- og verkefnasíður',
'searchprofile-images'             => 'Margmiðlanir',
'searchprofile-everything'         => 'Allt',
'searchprofile-advanced'           => 'Nánar',
'searchprofile-articles-tooltip'   => 'Leita í $1',
'searchprofile-project-tooltip'    => 'Leita í $1',
'searchprofile-images-tooltip'     => 'Leita að skrám',
'searchprofile-everything-tooltip' => 'Leita í öllu efni (þar á meðal spjallsíðum)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 orð|$2 orð}})',
'search-result-score'              => 'Gildi: $1%',
'search-redirect'                  => '(tilvísun $1)',
'search-section'                   => '(hluti $1)',
'search-suggest'                   => 'Varstu að leita að: $1',
'search-interwiki-caption'         => 'Systurverkefni',
'search-interwiki-default'         => '$1 útkomur:',
'search-interwiki-more'            => '(fleiri)',
'search-mwsuggest-enabled'         => 'með uppástungum',
'search-mwsuggest-disabled'        => 'engar uppástungur',
'search-relatedarticle'            => 'Tengt',
'mwsuggest-disable'                => 'Gera AJAX-uppástungur óvirkar',
'searcheverything-enable'          => 'Leita í öllum nafnrýmum',
'searchrelated'                    => 'tengt',
'searchall'                        => 'öllum',
'showingresults'                   => "Sýni {{PLURAL:$1|'''1''' niðurstöðu|'''$1''' niðurstöður}} frá og með #'''$2'''.",
'showingresultsnum'                => "Sýni {{PLURAL:$3|'''$3''' niðurstöðu|'''$3''' niðurstöður}} frá og með #<b>$2</b>.",
'nonefound'                        => "'''Athugaðu''': Það er aðeins leitað í sumum nafnrýmum sjálfkrafa. Prófaðu að setja forskeytið ''all:'' í fyrirspurnina til að leita í öllu efni (þar á meðal notandaspjallsíðum, sniðum, o.s.frv.), eða notaðu tileigandi nafnrými sem forskeyti.",
'search-nonefound'                 => 'Engar niðurstöður pössuðu við fyrirspurnina.',
'powersearch'                      => 'Ítarleg leit',
'powersearch-legend'               => 'Ítarlegri leit',
'powersearch-ns'                   => 'Leita í nafnrýmum:',
'powersearch-redir'                => 'Lista tilvísanir',
'powersearch-field'                => 'Leita að',
'powersearch-toggleall'            => 'Allt',
'powersearch-togglenone'           => 'Ekkert',
'search-external'                  => 'Ytri leit',
'searchdisabled'                   => '{{SITENAME}}-leit er óvirk.
Þú getur leitað í genum Google á meðan.
Athugaðu að skrár þeirra yfir {{SITENAME}}-efni kunna að vera úreltar.',

# Quickbar
'qbsettings'               => 'Valblað',
'qbsettings-none'          => 'Sleppa',
'qbsettings-fixedleft'     => 'Fast vinstra megin',
'qbsettings-fixedright'    => 'Fast hægra megin',
'qbsettings-floatingleft'  => 'Fljótandi til vinstri',
'qbsettings-floatingright' => 'Fljótandi til hægri',

# Preferences page
'preferences'               => 'Stillingar',
'mypreferences'             => 'Stillingar',
'prefs-edits'               => 'Fjöldi breytinga:',
'prefsnologin'              => 'Ekki innskráður',
'prefsnologintext'          => 'Þú verður að vera <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} skráð(ur) inn]</span> til að breyta notandastillingum.',
'changepassword'            => 'Breyta lykilorði',
'prefs-skin'                => 'Þema',
'skin-preview'              => 'Forskoða',
'prefs-math'                => 'Stærðfræðiformúlur',
'datedefault'               => 'Sjálfgefið',
'prefs-datetime'            => 'Tímasnið og tímabelti',
'prefs-personal'            => 'Notandaupplýsingar',
'prefs-rc'                  => 'Nýlegar breytingar',
'prefs-watchlist'           => 'Vaktlistinn',
'prefs-watchlist-days'      => 'Fjöldi daga sem vaktlistinn nær yfir:',
'prefs-watchlist-days-max'  => '(hámark 7 dagar)',
'prefs-watchlist-edits'     => 'Fjöldi breytinga sem vaktlistinn nær yfir:',
'prefs-watchlist-edits-max' => '(hámarkstala: 1000)',
'prefs-misc'                => 'Aðrar stillingar',
'prefs-resetpass'           => 'Breyta lykilorði',
'prefs-email'               => 'Tölvupóststillingar',
'prefs-rendering'           => 'Útlit',
'saveprefs'                 => 'Vista',
'resetprefs'                => 'Endurstilla valmöguleika',
'restoreprefs'              => 'Endurheimta allar stillingar',
'prefs-editing'             => 'Breytingarflipinn',
'prefs-edit-boxsize'        => 'Stærð breytingagluggans.',
'rows'                      => 'Raðir',
'columns'                   => 'Dálkar',
'searchresultshead'         => 'Leit',
'resultsperpage'            => 'Niðurstöður á síðu',
'contextlines'              => 'Línur á hverja niðurstöðu',
'contextchars'              => 'Stafir í samhengi á hverja línu',
'stub-threshold'            => 'Þröskuldur fyrir sniði <a href="#" class="stub">stubbatengla</a> (bæt):',
'recentchangesdays'         => 'Hve marga daga á að sýna í nýlegum breytingum:',
'recentchangesdays-max'     => '(hámark $1 {{PLURAL:$1|dag|daga}})',
'recentchangescount'        => 'Fjöldi síðna á „nýlegum breytingum“',
'savedprefs'                => 'Stillingarnar þínar hafa verið vistaðar.',
'timezonelegend'            => 'Tímabelti:',
'localtime'                 => 'Staðartími:',
'timezoneoffset'            => 'Hliðrun¹:',
'servertime'                => 'Tími netþjóns:',
'guesstimezone'             => 'Fylla inn frá vafranum',
'timezoneregion-africa'     => 'Afríka',
'timezoneregion-america'    => 'Ameríka',
'timezoneregion-antarctica' => 'Suðurskautslandið',
'timezoneregion-arctic'     => 'Norðurheimskautið',
'timezoneregion-asia'       => 'Asía',
'timezoneregion-atlantic'   => 'Atlantshaf',
'timezoneregion-australia'  => 'Ástralía',
'timezoneregion-europe'     => 'Evrópa',
'timezoneregion-indian'     => 'Indlandshaf',
'timezoneregion-pacific'    => 'Kyrrahaf',
'allowemail'                => 'Virkja tölvupóst frá öðrum notendum',
'prefs-searchoptions'       => 'Leitarvalmöguleikar',
'prefs-namespaces'          => 'Nafnrými',
'defaultns'                 => 'Leita í þessum nafnrýmum sjálfgefið:',
'default'                   => 'sjálfgefið',
'prefs-files'               => 'Skrár',
'prefs-emailconfirm-label'  => 'Staðfesting netfangs:',
'youremail'                 => 'Netfang:',
'username'                  => 'Notandanafn:',
'uid'                       => 'Raðnúmer:',
'prefs-memberingroups'      => 'Meðlimur {{PLURAL:$1|hóps|hópa}}:',
'prefs-registration'        => 'Nýskráningartími:',
'yourrealname'              => 'Fullt nafn:',
'yourlanguage'              => 'Viðmótstungumál:',
'yourvariant'               => 'Útgáfa:',
'yournick'                  => 'Undirskrift:',
'badsig'                    => 'Ógild hrá undirskrift. Athugaðu HTML-kóða.',
'badsiglength'              => 'Undirskriftin er of löng.
Hún þarf að vera færri en $1 {{PLURAL:$1|rittákn|rittákn}}.',
'yourgender'                => 'Kyn:',
'gender-unknown'            => 'Óskilgreint',
'gender-male'               => 'Karl',
'gender-female'             => 'Kona',
'prefs-help-gender'         => 'Valfrjálst: notað til að aðgreina kynin í meldingum hugbúnaðarins. Þessar upplýsingar verða aðgengilegar öllum.',
'email'                     => 'Tölvupóstur',
'prefs-help-realname'       => 'Alvöru nafn er valfrjálst.
Ef þú kýst að gefa það upp, verður það notað til að gefa þér heiður af verkum þínum.',
'prefs-help-email'          => 'Tölvupóstfang er valfrjálst, en gerir það kleift að fá nýtt lykilorð sent ef þú gleymir lykilorðinu þínu.
Þú getur einnig leyft öðrum að hafa samband við þig á notanda- eða spjallsíðunni þinni án þess að opinbera þig.',
'prefs-help-email-required' => 'Þörf er á netfangi.',
'prefs-info'                => 'Undirstöðuupplýsingar',
'prefs-signature'           => 'Undirskrift',

# User rights
'userrights'                  => 'Breyta notandaréttindum',
'userrights-lookup-user'      => 'Yfirlit notandahópa',
'userrights-user-editname'    => 'Skráðu notandanafn:',
'editusergroup'               => 'Breyta notandahópum',
'editinguser'                 => "Breyti réttindum '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Breyta notandahópum',
'saveusergroups'              => 'Vista notandahóp',
'userrights-groupsmember'     => 'Meðlimur:',
'userrights-groups-help'      => 'Þú getur breytt hópunum sem að þessi notandi er í.
* Valinn reitur þýðir að notandinn er í hópnum.
* Óvalinn reitur þýðir að notandinn er ekki í hópnum.
* Stjarnan (*) þýðir að þú getur ekki fært hópinn eftir að þú hefur breytt honum, eða öfugt.',
'userrights-reason'           => 'Ástæða:',
'userrights-no-interwiki'     => 'Þú hefur ekki leyfi til að breyta notandaréttindum á öðrum wiki-síðum.',
'userrights-nodatabase'       => 'Gagnagrunnurinn $1 er ekki til eða ekki staðbundinn.',
'userrights-nologin'          => 'Þú verður að [[Special:UserLogin|innskrá]] þig á möppudýraaðgang til að geta útdeilt notandaréttindum.',
'userrights-notallowed'       => 'Þinn aðgangur hefur ekki réttindi til að útdeila notandaréttindum.',
'userrights-changeable-col'   => 'Hópar sem þú getur breytt',
'userrights-unchangeable-col' => 'Hópar sem þú getur ekki breytt',

# Groups
'group'               => 'Hópur:',
'group-user'          => 'Notendur',
'group-autoconfirmed' => 'Sjálfkrafa staðfesting notenda',
'group-bot'           => 'Vélmenni',
'group-sysop'         => 'Stjórnendur',
'group-bureaucrat'    => 'Möppudýr',
'group-suppress'      => 'Yfirsýn',
'group-all'           => '(allir)',

'group-user-member'          => 'Notandi',
'group-autoconfirmed-member' => 'Sjálfkrafa staðfesting notanda',
'group-bot-member'           => 'Vélmenni',
'group-sysop-member'         => 'Stjórnandi',
'group-bureaucrat-member'    => 'Möppudýr',
'group-suppress-member'      => 'Umsjón',

'grouppage-user'          => '{{ns:project}}:Notendur',
'grouppage-autoconfirmed' => '{{ns:project}}:Sjálfkrafa staðfesting notenda',
'grouppage-bot'           => '{{ns:project}}:Vélmenni',
'grouppage-sysop'         => '{{ns:project}}:Stjórnendur',
'grouppage-bureaucrat'    => '{{ns:project}}:Möppudýr',
'grouppage-suppress'      => '{{ns:project}}:Umsjón',

# Rights
'right-read'                 => 'Lesa síður',
'right-edit'                 => 'Breyta síðum',
'right-createpage'           => 'Gera síður (sem eru ekki spjallsíður)',
'right-createtalk'           => 'Gera spjallsíður',
'right-createaccount'        => 'Gera nýja notandaaðganga',
'right-minoredit'            => 'Merkja sem minniháttarbreytingar',
'right-move'                 => 'Færa síður',
'right-move-subpages'        => 'Færa síður með undirsíðum þeirra',
'right-movefile'             => 'Færa skrár',
'right-suppressredirect'     => 'Ekki búa til tilvísun frá gamla nafninu þegar síða er færð',
'right-upload'               => 'Hlaða inn skrám',
'right-reupload'             => 'Yfirrita núverandi skrá',
'right-reupload-own'         => 'Yfirrita núverandi skrá sem að ég hlóð inn sjálf(ur)',
'right-purge'                => 'Hreinsa skyndiminni síðu án staðfestingar',
'right-autoconfirmed'        => 'Breyta hálfvernduðum síðum',
'right-nominornewtalk'       => 'Ekki láta minniháttar breytingar á spjallsíðum kveða upp áminningu um ný skilaboð',
'right-delete'               => 'Eyða síðum',
'right-bigdelete'            => 'Eyða síðum með stórum breytingaskrám',
'right-deleterevision'       => 'Eyða og endurvekja sérstaka breytignar á síðum',
'right-browsearchive'        => 'Leita í eyddum síðum',
'right-undelete'             => 'Endurvekja eydda síðu',
'right-suppressrevision'     => 'Skoða og endurvekja breytingar faldar fyrir stjórnendum',
'right-suppressionlog'       => 'Skoða einrænar aðgerðaskrár',
'right-block'                => 'Banna öðrum notendum að gera breytingar',
'right-blockemail'           => 'Banna notanda að senda tölvupóst',
'right-hideuser'             => 'Banna notandanafn, og þannig fela það frá almenningi',
'right-editprotected'        => 'Breyta verndaðar síður (án keðjuverndunar)',
'right-editinterface'        => 'Breyta notandaviðmótinu',
'right-editusercssjs'        => 'Breyta CSS- og JS-skrám annarra',
'right-editusercss'          => 'Breyta CSS-skrám annarra',
'right-edituserjs'           => 'Breyta JS-skrám annarra',
'right-unwatchedpages'       => 'Skoða lista yfir óvaktaðar síður',
'right-userrights'           => 'Breyta öllum notandaréttindum',
'right-userrights-interwiki' => 'Breyta notandaréttindum annarra notenda á öðrum wiki-verkefnum',
'right-siteadmin'            => 'Læsa og aflæsa gagnagrunninum',

# User rights log
'rightslog'      => 'Réttindaskrá notenda',
'rightslogtext'  => 'Þetta er skrá yfir breytingar á réttindum notenda.',
'rightslogentry' => 'breytti réttindum $1 frá $2 í $3',
'rightsnone'     => '(engin)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lesa þessa síðu',
'action-edit'                 => 'breyta þessari síðu',
'action-createpage'           => 'skapa síður',
'action-createtalk'           => 'skapa spjallsíður',
'action-createaccount'        => 'skapa þennan notandaaðgang',
'action-minoredit'            => 'merkja þessa breytingu sem minniháttar',
'action-move'                 => 'færa þessa síðu',
'action-move-subpages'        => 'færa þessa síðu, og undirsíður hennar',
'action-upload'               => 'hlaða inn þessari skrá',
'action-reupload'             => 'yfirrita þessa skrá',
'action-delete'               => 'eyða þessari síðu',
'action-deleterevision'       => 'eyða þessari breytingu',
'action-deletedhistory'       => 'skoða breytingaskrá þessarar síðu',
'action-browsearchive'        => 'leita í eyddum síðum',
'action-undelete'             => 'endurvekja þessa síðu',
'action-protect'              => 'breyta verndunarstigum fyrir þessa síðu',
'action-userrights'           => 'breyta öllum notandaréttindum',
'action-userrights-interwiki' => 'breyta notandaréttindum annarra notenda á öðrum wiki-verkefnum',
'action-siteadmin'            => 'læsa eða opna gagnagrunninn',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|breyting|breytingar}}',
'recentchanges'                     => 'Nýlegar breytingar',
'recentchanges-legend'              => 'Stillingar nýlegra breytinga',
'recentchangestext'                 => 'Hér geturðu fylgst með nýjustu breytingunum.',
'recentchanges-feed-description'    => 'Hér er hægt að fylgjast með nýlegum breytingum á {{SITENAME}}.',
'recentchanges-label-newpage'       => 'Þessi breyting skapaði nýja síðu',
'recentchanges-label-minor'         => 'Þetta er minniháttar breyting',
'recentchanges-label-bot'           => 'Þessi breytingar var gerð af vélmenni',
'recentchanges-label-unpatrolled'   => 'Þessi breyting hefur ekki verið yfirfarin',
'rcnote'                            => "Að neðan {{PLURAL:$1|er '''1''' breyting|eru síðustu '''$1''' breytingar}} síðast {{PLURAL:$2|liðinn dag|liðna '''$2''' daga}}, frá $5, $4.",
'rcnotefrom'                        => "Að neðan eru breytingar síðan '''$2''' (allt að '''$1''' sýndar).",
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
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'v',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|notandi skoðandi|$1 notendur skoðandi}}]',
'rc_categories'                     => 'Takmark á flokkum (aðskilja með "|")',
'rc_categories_any'                 => 'Alla',
'newsectionsummary'                 => 'Nýr hluti: /* $1 */',
'rc-enhanced-expand'                => 'Sýna upplýsingar (þarfnast JavaScript)',
'rc-enhanced-hide'                  => 'Fela ítarefni',

# Recent changes linked
'recentchangeslinked'          => 'Skyldar breytingar',
'recentchangeslinked-feed'     => 'Skyldar breytingar',
'recentchangeslinked-toolbox'  => 'Skyldar breytingar',
'recentchangeslinked-title'    => 'Breytingar tengdar "$1"',
'recentchangeslinked-noresult' => 'Engar breytingar á tengdum síðum á þessu tímabili.',
'recentchangeslinked-summary'  => "Þetta er listi yfir nýlega gerðar breytingar á síðum sem tengt er í frá tilgreindri síðu (eða á meðlimum úr tilgreindum flokki).
Síður á [[Special:Watchlist|vaktlistanum þínum]] eru '''feitletraðar'''.",
'recentchangeslinked-page'     => 'Nafn á síða:',
'recentchangeslinked-to'       => 'Sýna breytingar á síðum sem tengjast uppgefinni síðu í staðinn',

# Upload
'upload'                 => 'Hlaða inn skrá',
'uploadbtn'              => 'Hlaða inn skrá',
'reuploaddesc'           => 'Aftur á innhlaðningarformið.',
'uploadnologin'          => 'Óinnskráð(ur)',
'uploadnologintext'      => 'Þú verður að vera [[Special:UserLogin|skráð(ur) inn]]
til að hlaða inn skrám.',
'uploaderror'            => 'Villa í innhlaðningu',
'uploadtext'             => "Notaðu eyðublaðið hér fyrir neðan til að hlaða inn skrám.
Til að skoða eða leita í áður innhlöðnum skrám ferðu á [[Special:FileList|skráarlistann]], (endur)innhlaðnar skrár eru skráðar í [[Special:Log/upload|innhlaðningarskránni]], eyðingar í [[Special:Log/delete|eyðingaskránni]].

* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skrá.jpg]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skrá.png|200px|thumb|left|alt-texti]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Skrá.ogg]]</nowiki></tt>'''",
'upload-permitted'       => 'Heimilaðar skráargerðir: $1.',
'upload-preferred'       => 'Ákjósanlegustu skrárgerðirnar: $1.',
'upload-prohibited'      => 'Óheimiluð skrárgerð: $1.',
'uploadlog'              => 'innhlaðningarskrá',
'uploadlogpage'          => 'Innhlaðningarskrá',
'uploadlogpagetext'      => 'Fyrir neðan er listi yfir nýlegustu innhlöðnu skrárnar.
Sjá [[Special:NewFiles|myndasafn nýrra mynda]] fyrir myndrænna yfirlit.',
'filename'               => 'Skráarnafn',
'filedesc'               => 'Lýsing',
'fileuploadsummary'      => 'Ágrip:',
'filereuploadsummary'    => 'Skráarbreytingar:',
'filestatus'             => 'Staða höfundaréttar:',
'filesource'             => 'Heimild:',
'uploadedfiles'          => 'Hlóð inn skráunum',
'ignorewarning'          => 'Hunsa viðvaranir og vista þessa skrá',
'ignorewarnings'         => 'Hunsa allar viðvaranir',
'minlength1'             => 'Skráarnöfn þurfa að vera að minnsta kosti einn stafur að lengd',
'illegalfilename'        => 'Skráarnafnið „$1“ inniheldur stafi sem eru ekki leyfðir í síðutitlum.
Gjörðu svo vel og endurnefndu skrána og hladdu henni inn aftur.',
'badfilename'            => 'Skáarnafninu hefur verið breytt í „$1“.',
'filetype-badmime'       => 'Skrárir af MIME-gerðinni „$1“ er ekki leyfilegt að hlaða inn.',
'filetype-unwanted-type' => "'''„.$1“''' er óæskileg skráargerð.
{{PLURAL:$3|Ákjósanleg skráargerð er|Ákjósanlegar skráargerðir eru}} $2.",
'filetype-banned-type'   => "'''„.$1“''' er ekki leyfileg skráargerð.
{{PLURAL:$3|Leyfileg skráargerð er|Leyfilegar skráargerðir eru}} $2.",
'filetype-missing'       => 'Skráin hefur engan viðauka (dæmi ".jpg").',
'large-file'             => 'Það er mælt með að skrár séu ekki stærri en $1; þessi skrá er $2.',
'fileexists'             => "Skrá með þessu nafni er þegar til, skoðaðu '''<tt>[[:$1]]</tt>''' ef þú ert óviss um hvort þú viljir breyta henni, ekki verður skrifað yfir gömlu skránna hlaðiru inn nýrri með sama nafni heldur verður núverandi útgáfa geymd í útgáfusögu.
[[$1|thumb]]",
'uploadwarning'          => 'Aðvörun',
'savefile'               => 'Vista',
'uploadedimage'          => 'hlóð inn „[[$1]]“',
'overwroteimage'         => 'hlóð inn nýrri útgáfu af "[[$1]]"',
'uploadscripted'         => 'Þetta skjal inniheldur (X)HTML eða forskriftu sem gæti valdið villum í vöfrum.',
'uploadvirus'            => 'Skráin inniheldur veiru! Nánari upplýsingar: $1',
'sourcefilename'         => 'Upprunalegt skráarnafn:',
'destfilename'           => 'Móttökuskráarnafn:',
'upload-maxfilesize'     => 'Hámarks skráarstærð: $1',
'watchthisupload'        => 'Vakta þessa skrá',
'filewasdeleted'         => 'Skrá af sama nafni hefur áður verið hlaðið inn og síðan eytt. Þú ættir að athuga $1 áður en þú hleður skránni inn.',
'upload-success-subj'    => 'Innhlaðning tókst',

'upload-proto-error'  => 'Vitlaus samskiptaregla',
'upload-file-error'   => 'Innri villa',
'upload-misc-error'   => 'Óþekkt innhleðsluvilla',
'upload-unknown-size' => 'Óþekkt stærð',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'Gat ekki náð í slóðina',
'upload-curl-error28' => 'Innhleðslutími útrunninn',

'license'            => 'Leyfisupplýsingar:',
'license-header'     => 'Leyfisupplýsingar:',
'nolicense'          => 'Ekkert valið',
'license-nopreview'  => '(Forskoðun ekki fáanleg)',
'upload_source_file' => '(skrá á tölvunni þinni)',

# Special:ListFiles
'listfiles_search_for'  => 'Leita að miðilsnafni:',
'imgfile'               => 'skrá',
'listfiles'             => 'Skráalisti',
'listfiles_date'        => 'Dagsetning',
'listfiles_name'        => 'Nafn',
'listfiles_user'        => 'Notandi',
'listfiles_size'        => 'Stærð (bæti)',
'listfiles_description' => 'Lýsing',
'listfiles_count'       => 'Útgáfur',

# File description page
'file-anchor-link'          => 'Skrá',
'filehist'                  => 'Breytingaskrá skjals',
'filehist-help'             => 'Smelltu á dagsetningu eða tímasetningu til að sjá hvernig hún leit þá út.',
'filehist-deleteall'        => 'eyða öllu',
'filehist-deleteone'        => 'eyða',
'filehist-revert'           => 'taka aftur',
'filehist-current'          => 'núverandi',
'filehist-datetime'         => 'Dagsetning/Tími',
'filehist-thumb'            => 'Smámynd',
'filehist-thumbtext'        => 'Smámynd útgáfunnar frá $2, kl. $3',
'filehist-nothumb'          => 'Engin smámynd',
'filehist-user'             => 'Notandi',
'filehist-dimensions'       => 'Víddir',
'filehist-filesize'         => 'Stærð skráar',
'filehist-comment'          => 'Athugasemd',
'imagelinks'                => 'Skráatenglar',
'linkstoimage'              => 'Eftirfarandi {{PLURAL:$1|síða tengist|$1 síður tengjast}} í þessa skrá:',
'nolinkstoimage'            => 'Engar síður tengja í þessa skrá.',
'sharedupload'              => 'Skrá þessi er af $1, og deilt meðal annarra verkefna og nýtist því þar.',
'sharedupload-desc-there'   => 'Skrá þessi er af $1, og deilt meðal annarra verkefna og nýtist því þar.
Gjörðu svo vel og sjáðu [$2 skráarsíðuna þar] fyrir fleiri upplýsingar.',
'sharedupload-desc-here'    => 'Skrá þessi er af $1, og deilt meðal annarra verkefna og nýtist því þar.
Hér fyrir neðan er afrit af [$2 skráarsíðunni þar].',
'uploadnewversion-linktext' => 'Hlaða inn nýrri útgáfu af þessari skrá',

# File reversion
'filerevert'         => 'Taka aftur $1',
'filerevert-legend'  => 'Taka aftur skrá',
'filerevert-comment' => 'Athugasemdir:',
'filerevert-submit'  => 'Taka aftur',

# File deletion
'filedelete'                  => 'Eyði „$1“',
'filedelete-legend'           => 'Eyða skrá',
'filedelete-intro'            => "Þú ert að eyða '''[[Media:$1|$1]]'''.",
'filedelete-comment'          => 'Ástæða:',
'filedelete-submit'           => 'Eyða',
'filedelete-success'          => "'''$1''' hefur verið eytt.",
'filedelete-nofile'           => "'''$1''' er ekki til.",
'filedelete-otherreason'      => 'Aðrar/fleiri ástæður:',
'filedelete-reason-otherlist' => 'Önnur ástæða',
'filedelete-reason-dropdown'  => '* Algengar eyðingarástæður
** Höfundarréttarbrot
** Endurtekin skrá',
'filedelete-edit-reasonlist'  => 'Eyðingarástæður',

# MIME search
'mimesearch' => 'MIME-leit',
'mimetype'   => 'MIME-tegund:',
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
'randompage-nopages' => 'Það eru engar síður í {{PLURAL:$2|nafnrýminu|nafnrýmunum}}: $1.',

# Random redirect
'randomredirect'         => 'Handahófsvalin tilvísun',
'randomredirect-nopages' => 'Það eru engar tilvísanir í nafnrýminu „$1“.',

# Statistics
'statistics'                   => 'Tölfræði',
'statistics-header-pages'      => 'Síðutölfræði',
'statistics-header-edits'      => 'Breytingatölfræði',
'statistics-header-views'      => 'Uppflettitölfræði',
'statistics-header-users'      => 'Notandatölfræði',
'statistics-header-hooks'      => 'Önnur tölfræði',
'statistics-articles'          => 'Greinar alls',
'statistics-pages'             => 'Síður',
'statistics-pages-desc'        => 'Allar síður wiki-verkefnisins, þar á meðal spjallsíður, tilvísanir o.fl.',
'statistics-files'             => 'Skráafjöldi',
'statistics-edits'             => 'Síðubreytingar frá því {{SITENAME}} byrjaði',
'statistics-edits-average'     => 'Meðal breytingafjöldi á síðu',
'statistics-views-total'       => 'Uppflettingar alls',
'statistics-views-peredit'     => 'Uppflettingar á hverja breytingu (meðaltal)',
'statistics-users'             => 'Skráðir  [[Special:ListUsers|notendur]]',
'statistics-users-active'      => 'Virkir notendur',
'statistics-users-active-desc' => 'Notendur sem hafa framkvæmt aðgerð {{PLURAL:$1|síðastliðin dag|síðastliðna $1 daga}}',
'statistics-mostpopular'       => 'Mest skoðuðu síður',

'disambiguations'      => 'Tenglar í aðgreiningarsíður',
'disambiguationspage'  => 'Template:Aðgreining',
'disambiguations-text' => "Þessar síður innihalda tengla á svokallaðar „'''aðgreiningarsíður'''“.
Laga ætti tenglanna og láta þá vísa á rétta síðu.<br />
Farið er með síðu sem aðgreiningarsíðu ef að hún inniheldur snið sem vísað er í frá [[MediaWiki:Disambiguationspage]]",

'doubleredirects' => 'Tvöfaldar tilvísanir',

'brokenredirects'        => 'Brotnar tilvísanir',
'brokenredirectstext'    => 'Eftirfarandi tilvísanir vísa á síður sem ekki eru til:',
'brokenredirects-edit'   => 'breyta',
'brokenredirects-delete' => 'eyða',

'withoutinterwiki'         => 'Síður án tungumálatengla',
'withoutinterwiki-summary' => 'Eftirfarandi síður tengja ekki í önnur tungumál:',
'withoutinterwiki-legend'  => 'Forskeyti',
'withoutinterwiki-submit'  => 'Sýna',

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
'lonelypagestext'         => 'Eftirfarandi síður eru munaðarlausar á {{SITENAME}}.',
'uncategorizedpages'      => 'Óflokkaðar síður',
'uncategorizedcategories' => 'Óflokkaðir flokkar',
'uncategorizedimages'     => 'Óflokkaðar skrár',
'uncategorizedtemplates'  => 'Óflokkuð snið',
'unusedcategories'        => 'Ónotaðir flokkar',
'unusedimages'            => 'Munaðarlausar skrár',
'popularpages'            => 'Vinsælar síður',
'wantedcategories'        => 'Eftirsóttir flokkar',
'wantedpages'             => 'Eftirsóttar síður',
'wantedfiles'             => 'Eftirsóttar skrár',
'wantedtemplates'         => 'Eftirsótt snið',
'mostlinked'              => 'Mest ítengdu síður',
'mostlinkedcategories'    => 'Mest ítengdu flokkar',
'mostlinkedtemplates'     => 'Mest ítengdu snið',
'mostcategories'          => 'Mest flokkaðar greinar',
'mostimages'              => 'Mest ítengdu skrárnar',
'mostrevisions'           => 'Síður eftir fjölda breytinga',
'prefixindex'             => 'Allar síður með forskeyti',
'shortpages'              => 'Stuttar síður',
'longpages'               => 'Langar síður',
'deadendpages'            => 'Botnlangar',
'deadendpagestext'        => 'Eftirfarandi síður tengjast ekki við aðrar síður á {{SITENAME}}.',
'protectedpages'          => 'Verndaðar síður',
'protectedpages-indef'    => 'Aðeins óendanlegar verndanir',
'protectedpages-cascade'  => 'Keðjuverndun eingöngu',
'protectedpagestext'      => 'Eftirfarandi síður hafa verið verndaðar svo ekki sé hægt að breyta þeim eða færa þær',
'protectedtitles'         => 'Verndaðir titlar',
'listusers'               => 'Notendalisti',
'usereditcount'           => '$1 {{PLURAL:$1|breyting|breytingar}}',
'newpages'                => 'Nýjustu greinar',
'newpages-username'       => 'Notandanafn:',
'ancientpages'            => 'Elstu síður',
'move'                    => 'Færa',
'movethispage'            => 'Færa þessa síðu',
'unusedimagestext'        => 'Vinsamlegast athugið að aðrar vefsíður gætu tengt beint í skrár héðan, svo að þær gætu komið fram á þessum lista þrátt fyrir að vera í notkun.',
'unusedcategoriestext'    => 'Þessir flokkar eru til en engar síður eða flokkar eru í þeim.',
'pager-newer-n'           => '{{PLURAL:$1|nýrri 1|nýrri $1}}',
'pager-older-n'           => '{{PLURAL:$1|1 eldri|$1 eldri}}',
'suppress'                => 'Yfirsýn',

# Book sources
'booksources'               => 'Bókaleit',
'booksources-search-legend' => 'Leita að bókaverslunum',
'booksources-go'            => 'Áfram',
'booksources-text'          => 'Fyrir neðan er listi af tenglum í aðrar síður sem selja nýjar og notaðar bækur og gætu einnig haft nánari upplýsingar í sambandi við bókina sem þú varst að leita að:',

# Special:Log
'specialloguserlabel'  => 'Notandi:',
'speciallogtitlelabel' => 'Titill:',
'log'                  => 'Aðgerðaskrár',
'all-logs-page'        => 'Allar aðgerðir',
'alllogstext'          => 'Safn allra aðgerðaskráa {{SITENAME}}.
Þú getur takmarkað listann með því að velja tegund aðgerðaskráar, notandanafn, eða síðu.',
'logempty'             => 'Engin slík aðgerð fannst.',
'log-title-wildcard'   => 'Leita að titlum sem byrja á þessum texta',

# Special:AllPages
'allpages'          => 'Allar síður',
'alphaindexline'    => '$1 til $2',
'nextpage'          => 'Næsta síða ($1)',
'prevpage'          => 'Fyrri síða ($1)',
'allpagesfrom'      => 'Sýna síður frá og með:',
'allpagesto'        => 'Sýna síður sem enda á:',
'allarticles'       => 'Allar greinar',
'allinnamespace'    => 'Allar síður ($1 nafnrými)',
'allnotinnamespace' => 'Allar síður (ekki í $1 nafnrýminu)',
'allpagesprev'      => 'Síðast',
'allpagesnext'      => 'Næst',
'allpagessubmit'    => 'Áfram',
'allpagesprefix'    => 'Sýna síður með forskeytinu:',
'allpagesbadtitle'  => 'Ekki var hægt að búa til grein með þessum titli því hann innihélt einn eða fleiri stafi sem ekki er hægt að nota í titlum.',
'allpages-bad-ns'   => '{{SITENAME}} hefur ekki nafnrými „$1“.',

# Special:Categories
'categories'                    => 'Flokkar',
'categoriespagetext'            => 'Eftirfarandi flokkar innihalda síður eða skrár.
[[Special:UnusedCategories|Ónotaðir flokkar]] birtast ekki hér.
Sjá einnig [[Special:WantedCategories|eftirsótta flokka]].',
'categoriesfrom'                => 'Sýna flokka frá:',
'special-categories-sort-count' => 'raða eftir fjölda',
'special-categories-sort-abc'   => 'raða eftir stafrófinu',

# Special:DeletedContributions
'deletedcontributions'       => 'Eyddar breytingar notanda',
'deletedcontributions-title' => 'Eyddar breytingar notanda',

# Special:LinkSearch
'linksearch'    => 'Útværir tenglar',
'linksearch-ns' => 'Nafnrými:',
'linksearch-ok' => 'Leita',

# Special:ListUsers
'listusersfrom'      => 'Sýna notendur sem byrja á:',
'listusers-submit'   => 'Sýna',
'listusers-noresult' => 'Enginn notandi fannst.',

# Special:ActiveUsers
'activeusers-hidebots'   => 'Fela vélmenni',
'activeusers-hidesysops' => 'Fela möppudýr',

# Special:Log/newusers
'newuserlogpage'              => 'Skrá yfir nýja notendur',
'newuserlogpagetext'          => 'Þetta er skrá yfir nýskráða notendur.',
'newuserlog-byemail'          => 'lykilorð sent með tölvupósti',
'newuserlog-create-entry'     => 'Nýr notandi',
'newuserlog-create2-entry'    => 'bjó til notanda fyrir $1',
'newuserlog-autocreate-entry' => 'Aðgangur búinn til sjálfkrafa',

# Special:ListGroupRights
'listgrouprights'          => 'Notandahópréttindi',
'listgrouprights-group'    => 'Hópur',
'listgrouprights-rights'   => 'Réttindi',
'listgrouprights-helppage' => 'Help:Hópréttindi',
'listgrouprights-members'  => '(listi yfir meðlimi)',

# E-mail user
'mailnologin'     => 'Ekkert netfang til að senda á',
'mailnologintext' => 'Þú verður að vera [[Special:UserLogin|innskráð(ur)]] auk þess að hafa gilt netfang í [[Special:Preferences|stillingunum]] þínum til að senda tölvupóst til annara notenda.',
'emailuser'       => 'Senda þessum notanda tölvupóst',
'emailpage'       => 'Senda tölvupóst',
'emailpagetext'   => 'Hafi notandi þessi fyllt út gild tölvupóstfang í stillingum sínum er hægt að senda póst til hans hér. Póstfangið sem þú fylltir út í stillingum þínum mun birtast í „From:“ hlutanum svo viðtakandinn geti svarað.',
'defemailsubject' => 'Varðandi {{SITENAME}}',
'noemailtitle'    => 'Ekkert póstfang',
'noemailtext'     => 'Notandi þessi hefur kosið að fá ekki tölvupóst frá öðrum notendum eða hefur ekki fyllt út netfang sitt í stillingum.',
'email-legend'    => 'Senda tölvupóst á annan {{SITENAME}}-notanda',
'emailfrom'       => 'Frá:',
'emailto'         => 'Til:',
'emailsubject'    => 'Fyrirsögn:',
'emailmessage'    => 'Skilaboð:',
'emailsend'       => 'Senda',
'emailccme'       => 'Senda mér tölvupóst með afriti af mínum skeytum.',
'emailccsubject'  => 'Afrit af skilaboðinu þínu til $1: $2',
'emailsent'       => 'Sending tókst',
'emailsenttext'   => 'Skilaboðin þín hafa verið send.',

# Watchlist
'watchlist'            => 'Vaktlistinn',
'mywatchlist'          => 'Vaktlistinn',
'nowatchlist'          => 'Vaktlistinn er tómur.',
'watchlistanontext'    => 'Vinsamlegast $1 til að skoða eða breyta vaktlistanum þínum.',
'watchnologin'         => 'Óinnskráð(ur)',
'watchnologintext'     => 'Þú verður að vera [[Special:UserLogin|innskáð(ur)]] til að geta breytt vaktlistanum.',
'addedwatch'           => 'Bætt á vaktlistann',
'addedwatchtext'       => "Síðunni „[[:$1]]“ hefur verið bætt á [[Special:Watchlist|Vaktlistann]] þinn.
Frekari breytingar á henni eða spallsíðu hennar munu verða sýndar þar, og síðan mun vera '''feitletruð''' í [[Special:RecentChanges|Nýlegum breytingum]] svo auðveldara sé að finna hana.",
'removedwatch'         => 'Fjarlægt af vaktlistanum',
'removedwatchtext'     => 'Síðan „[[:$1]]“ hefur verið fjarlægð af [[Special:Watchlist|vaktlistanum þínum]].',
'watch'                => 'Vakta',
'watchthispage'        => 'Vakta þessa síðu',
'unwatch'              => 'Afvakta',
'unwatchthispage'      => 'Hætta vöktun',
'notanarticle'         => 'Ekki efnisleg síða',
'watchnochange'        => 'Engri síðu á vaktlistanum þínum hefur verið breytt á tilgreindu tímabili.',
'watchlist-details'    => '{{PLURAL:$1|$1 síða|$1 síður}} á vaktlistanum þínum, fyrir utan spjallsíður.',
'wlheader-enotif'      => '* Tilkynning með tölvupósti er virk.',
'wlheader-showupdated' => "* Síðum sem hefur verið breytt síðan þú skoðaðir þær síðast eru '''feitletraðar'''",
'watchmethod-recent'   => 'kanna hvort nýlegar breytingar innihalda vaktaðar síður',
'watchmethod-list'     => 'leita að breytingum í vöktuðum síðum',
'watchlistcontains'    => 'Vaktlistinn þinn inniheldur {{PLURAL:$1|$1 síðu|$1 síður}}.',
'iteminvalidname'      => 'Vandamál með „$1“, rangt nafn...',
'wlnote'               => "Að neðan {{PLURAL:$1|er síðasta breyting|eru síðustu '''$1''' breytingar}} {{PLURAL:$2|síðastliðinn klukkutímann|síðastliðna '''$2''' klukkutímana}}.",
'wlshowlast'           => 'Sýna síðustu $1 klukkutíma, $2 daga, $3',
'watchlist-options'    => 'Vaktlistastillingar',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vakta...',
'unwatching' => 'Afvakta...',

'enotif_reset'                 => 'Merkja allar síður sem skoðaðar',
'enotif_newpagetext'           => 'Þetta er ný síða.',
'enotif_impersonal_salutation' => '{{SITENAME}}notandi',
'changed'                      => 'breytt',
'created'                      => 'búið til',
'enotif_lastdiff'              => 'Sjá $1 til að skoða þessa breytingu.',
'enotif_anon_editor'           => 'ónefndur notandi $1',

# Delete
'deletepage'             => 'Eyða',
'confirm'                => 'Staðfesta',
'excontent'              => 'innihaldið var: „$1“',
'excontentauthor'        => "innihaldið var: '$1' (og öll framlög voru frá '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "innihald fyrir tæmingu var: '$1'",
'exblank'                => 'síðan var tóm',
'delete-confirm'         => 'Eyða „$1“',
'delete-legend'          => 'Eyða',
'historywarning'         => 'Athugið: Síðan sem þú ert um það bil að eyða á sér',
'confirmdeletetext'      => 'Þú ert um það bil að eyða síðu ásamt breytingaskrá hennar.
Vinsamlegast staðfestu það að þú ætlir að gera svo, það að þú skiljir afleiðingarnar, og að þú sért að gera þetta í samræmi við [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Aðgerð lokið',
'actionfailed'           => 'Aðgerð mistókst',
'deletedtext'            => '„<nowiki>$1</nowiki>“ hefur verið eytt.
Sjá lista yfir nýlegar eyðingar í $2.',
'deletedarticle'         => 'eyddi „[[$1]]“',
'dellogpage'             => 'Eyðingaskrá',
'dellogpagetext'         => 'Að neðan gefur að líta lista yfir síður sem nýlega hefur verið eytt.',
'deletionlog'            => 'eyðingaskrá',
'reverted'               => 'Breytt aftur til fyrri útgáfu',
'deletecomment'          => 'Ástæða:',
'deleteotherreason'      => 'Aðrar/fleiri ástæður:',
'deletereasonotherlist'  => 'Önnur ástæða',
'deletereason-dropdown'  => '* Algengar ástæður
** Að beiðni höfundar
** Höfundaréttarbrot
** Skemmdarverk',
'delete-edit-reasonlist' => 'Breyta eyðingarástæðum',

# Rollback
'rollback'         => 'Taka aftur breytingar',
'rollback_short'   => 'Taka aftur',
'rollbacklink'     => 'taka aftur',
'rollbackfailed'   => 'Mistókst að taka aftur',
'cantrollback'     => 'Ekki hægt að taka aftur breytingu, síðasti höfundur er eini höfundur þessarar síðu.',
'alreadyrolled'    => 'Ekki var hægt að taka síðustu breytingu [[:$1]] eftir [[User:$2|$2]] ([[User talk:$2|spjall]]) til baka;
eitthver annar hefur breytt síðunni eða nú þegar tekið breytinguna til baka.

Síðasta breyting er frá [[User:$3|$3]] ([[User talk:$3|Spjall]]).',
'editcomment'      => "Beytingarágripið var: \"''\$1''\".",
'revertpage'       => 'Tók aftur breytingar [[Special:Contributions/$2|$2]] ([[User talk:$2|spjall]]), breytt til síðustu útgáfu [[User:$1|$1]]',
'rollback-success' => 'Tók til baka breytingar eftir $1; núverandi $2.',

# Protect
'protectlogpage'              => 'Verndunarskrá',
'protectlogtext'              => 'Fyrir neðan er listi yfir síðuverndanir og -afverndanir.
Sjáðu [[Special:ProtectedPages|listann yfir verndaðar síður]] fyrir núverandi lista yfir verndaðar síður.',
'protectedarticle'            => 'verndaði „[[$1]]“',
'modifiedarticleprotection'   => 'breytti verndunarstigi fyrir "[[$1]]"',
'unprotectedarticle'          => 'afverndaði „[[$1]]“',
'movedarticleprotection'      => 'verndunarstilling hefur verið færð frá „[[$2]]“ á „[[$1]]“',
'protect-title'               => 'Vernda „$1“',
'prot_1movedto2'              => '[[$1]] færð á [[$2]]',
'protect-legend'              => 'Verndunarstaðfesting',
'protectcomment'              => 'Ástæða:',
'protectexpiry'               => 'Rennur út:',
'protect_expiry_invalid'      => 'Ógildur tími.',
'protect_expiry_old'          => 'Tíminn er þegar runninn út.',
'protect-text'                => "Hér getur þú skoðað og breytt verndunarstigi síðunnar '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Þú hefur ekki heimild til þess að vernda eða afvernda síður.
Núverandi staða síðunnar er '''$1''':",
'protect-cascadeon'           => 'Þessi síða er vernduð vegna þess að hún er innifalin í eftirfarandi {{PLURAL:$1|síðu, sem er keðjuvernduð|síðum, sem eru keðjuverndaðar}}.
Þú getur breytt verndunarstigi þessarar síðu, en það mun ekki hafa áhrif á keðjuverndunina.',
'protect-default'             => 'Leyfa öllum notendum',
'protect-fallback'            => '„$1“ réttindi nauðsynleg',
'protect-level-autoconfirmed' => 'Banna nýja og óinnskráða notendur',
'protect-level-sysop'         => 'Leyfa aðeins stjórnendur',
'protect-summary-cascade'     => 'keðjuvörn',
'protect-expiring'            => 'rennur út $1 (UTC)',
'protect-expiry-indefinite'   => 'ótiltekinn',
'protect-cascade'             => 'Vernda innifaldar síður í þessari síðu (keðjuvörn)',
'protect-cantedit'            => 'Þú getur ekki breytt verndunarstigi þessarar síðu, vegna þess að þú hefur ekki réttindin til að breyta því.',
'protect-othertime'           => 'Annar tími:',
'protect-othertime-op'        => 'annar tími',
'protect-expiry-options'      => '1 tím:1 hour,1 dag:1 day,1 viku:1 week,2 vikur:2 weeks,1 mánuð:1 month,3 mánuði:3 months,6 mánuði:6 months,1 ár:1 year,aldrei:infinite',
'restriction-type'            => 'Réttindi:',
'restriction-level'           => 'Takmarkaði við:',
'minimum-size'                => 'Lágmarksstærð',
'maximum-size'                => 'Hámarksstærð:',
'pagesize'                    => '(bæt)',

# Restrictions (nouns)
'restriction-edit'   => 'Breyta',
'restriction-move'   => 'Færa',
'restriction-create' => 'Skapa',
'restriction-upload' => 'Hlaða inn',

# Restriction levels
'restriction-level-sysop'         => 'alvernduð',
'restriction-level-autoconfirmed' => 'hálfvernduð',
'restriction-level-all'           => 'öll stig',

# Undelete
'undelete'                  => 'Endurvekja eydda síðu',
'undeletepage'              => 'Skoða og endurvekja eyddar síður',
'undeletepagetitle'         => "'''Eftirfarandi er samansafn af eyddum breytingum á [[:$1|$1]]'''.",
'viewdeletedpage'           => 'Skoða eyddar síður',
'undeletepagetext'          => 'Eftirfarandi {{PLURAL:$1|síðu hefur verið eytt en hún er þó enn í gagnagrunninum og getur verið endurvakin|$1 síðum hefur verið eytt en eru þó enn í gagnagrunninum og geta verið endurvaknar}}.
Gagnagrunnurinn kann að vera tæmdur reglulega.',
'undeleterevisions'         => '$1 {{PLURAL:$1|breyting|breytingar}}',
'undeletehistorynoadmin'    => 'Þessari síðu hefur verið eytt. Ástæðan sést í ágripinu fyrir neðan, ásamt upplýsingum um hvaða notendur breyttu síðunni fyrir eyðingu.
Innihald greinarinnar er einungis aðgengilegt möppudýrum.',
'undeletebtn'               => 'Endurvekja',
'undeletelink'              => 'skoða/endurvekja',
'undeleteviewlink'          => 'skoða',
'undeletereset'             => 'Endurstilla',
'undeleteinvert'            => 'Snúa vali við',
'undeletecomment'           => 'Athugasemd:',
'undeletedarticle'          => 'endurvakti „[[$1]]“',
'undeletedrevisions'        => '$1 {{PLURAL:$1|breyting endurvakin|breytingar endurvaktar}}',
'undeletedrevisions-files'  => '$1 {{PLURAL:$1|breyting|breytingar}} og $2 {{PLURAL:$2|skrá|skrár}} endurvaktar',
'undeletedfiles'            => '{{PLURAL:$1|Ein skrá endurvakin|$1 skrár endurvaktar}}',
'cannotundelete'            => 'Ekki var hægt að afturkalla síðuna. (Líklega hefur einhver gert það á undan þér.)',
'undeletedpage'             => "'''$1 var endurvakin'''

Skoðaðu [[Special:Log/delete|eyðingaskrána]] til að skoða eyðingar og endurvakningar.",
'undelete-search-box'       => 'Leita að eyddum síðum',
'undelete-search-prefix'    => 'Sýna síður sem byrja á:',
'undelete-search-submit'    => 'Leita',
'undelete-no-results'       => 'Engar samsvarandi síður fundust í eyðingarskjalasafninu.',
'undelete-error-short'      => 'Villa við endurvakningu skráar: $1',
'undelete-show-file-submit' => 'Já',

# Namespace form on various pages
'namespace'      => 'Nafnrými:',
'invert'         => 'allt nema valið',
'blanknamespace' => '(Aðalnafnrýmið)',

# Contributions
'contributions'       => 'Framlög notanda',
'contributions-title' => 'Framlög notanda $1',
'mycontris'           => 'Framlög',
'contribsub2'         => 'Eftir $1 ($2)',
'nocontribs'          => 'Engar breytingar fundnar sem passa við þessa viðmiðun.',
'uctop'               => '(nýjast)',
'month'               => 'Frá mánuðinum (og fyrr):',
'year'                => 'Frá árinu (og fyrr):',

'sp-contributions-newbies'     => 'Sýna aðeins breytingar frá nýjum notendum',
'sp-contributions-newbies-sub' => 'Fyrir nýliða',
'sp-contributions-blocklog'    => 'Fyrri bönn',
'sp-contributions-deleted'     => 'Eyddar breytingar notanda',
'sp-contributions-talk'        => 'spjall',
'sp-contributions-userrights'  => 'Breyta notandaréttindum',
'sp-contributions-search'      => 'Leita að framlögum',
'sp-contributions-username'    => 'Vistfang eða notandanafn:',
'sp-contributions-submit'      => 'Leita að breytingum',

# What links here
'whatlinkshere'            => 'Hvað tengist hingað',
'whatlinkshere-title'      => 'Síður sem tengjast „$1“',
'whatlinkshere-page'       => 'Síða:',
'linkshere'                => "Eftirfarandi síður tengjast á '''[[:$1]]''':",
'nolinkshere'              => "Engar síður tengjast á '''[[:$1]]'''.",
'nolinkshere-ns'           => "Engar síður tengjast '''[[:$1]]''' í þessu nafnrými.",
'isredirect'               => 'tilvísun',
'istemplate'               => 'innifalið',
'isimage'                  => 'myndatengill',
'whatlinkshere-prev'       => '{{PLURAL:$1|fyrra|fyrri $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|næst|næstu $1}}',
'whatlinkshere-links'      => '← tenglar',
'whatlinkshere-hideredirs' => '$1 tilvísanir',
'whatlinkshere-hidetrans'  => '$1 ítengingar',
'whatlinkshere-hidelinks'  => '$1 tenglar',
'whatlinkshere-hideimages' => '$1 myndatenglar',
'whatlinkshere-filters'    => 'Síur',

# Block/unblock
'blockip'                     => 'Banna notanda',
'blockip-title'               => 'Banna notanda',
'blockip-legend'              => 'Banna notanda',
'blockiptext'                 => 'Notaðu eyðublaðið hér að neðan til þess að banna ákveðið vistfang eða notandanafn.
Þetta ætti einungis að gera til þess að koma í veg fyrir skemmdarverk, og í samræmi við [[{{MediaWiki:Policy-url}}|samþykktir]].
Gefðu nákvæma skýringu að neðan (til dæmis, með því að vísa í þær síður sem skemmdar voru).',
'ipaddress'                   => 'Vistfang:',
'ipadressorusername'          => 'Vistfang eða notandanafn:',
'ipbexpiry'                   => 'Bannið rennur út:',
'ipbreason'                   => 'Ástæða:',
'ipbreasonotherlist'          => 'Aðrar ástæður',
'ipbreason-dropdown'          => '* Algengar bannástæður
** Setur inn rangar upplýsingar
** Fjarlægir efni af síðum
** Setur inn rusltengla á utanaðkomandi síður
** Setur inn vitleysu/þvaður á síður
** Yfirþyrmandi framkoma/áreitni
** Misnotkun á fjölda notandanafna
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
'ipbwatchuser'                => 'Vakta notanda- og spjallsíður þessa notanda',
'ipballowusertalk'            => 'Leyfa notanda að breyta eigin spjallsíðu á meðan hann er í banni',
'ipb-change-block'            => 'Endurbanna notanda með þessum stillingum',
'badipaddress'                => 'Ógilt vistfang',
'blockipsuccesssub'           => 'Bann tókst',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] hefur verið bannaður/bönnuð.<br />
Sjá [[Special:IPBlockList|bannaðar notendur og vistföng]] fyrir yfirlit yfir núverandi bönn.',
'ipb-edit-dropdown'           => 'Breyta ástæðu fyrir banni',
'ipb-unblock-addr'            => 'Afbanna $1',
'ipb-unblock'                 => 'Afbanna notanda eða vistfang',
'ipb-blocklist-addr'          => 'Núverandi bönn fyrir $1',
'ipb-blocklist'               => 'Sjá núverandi bönn',
'ipb-blocklist-contribs'      => 'Framlög fyrir $1',
'unblockip'                   => 'Afbanna notanda',
'unblockiptext'               => 'Endurvekja skrifréttindi bannaðra notenda eða vistfanga.',
'ipusubmit'                   => 'Afbanna',
'unblocked'                   => '[[User:$1|$1]] hefur verið afbannaður',
'unblocked-id'                => 'Bann $1 hefur verið fjarlægt',
'ipblocklist'                 => 'Bannaðir notendur og vistföng',
'ipblocklist-legend'          => 'Finna bannaðan notanda',
'ipblocklist-username'        => 'Notandanafn eða vistfang:',
'ipblocklist-submit'          => 'Leita',
'blocklistline'               => '$1, $2 bannaði $3 (rennur út $4)',
'infiniteblock'               => 'aldrei',
'expiringblock'               => 'rennur út  $1 $2',
'anononlyblock'               => 'bara ónafngreindir',
'noautoblockblock'            => 'sjálfbönnun óvirk',
'createaccountblock'          => 'bann við stofnun nýrra aðganga',
'emailblock'                  => 'tölvupóstur bannaður',
'blocklist-nousertalk'        => 'getur ekki breytt eigin spjallsíðu',
'ipblocklist-empty'           => 'Bannlistinn er tómur.',
'ipblocklist-no-results'      => 'Umbeðið vistfang eða notandanafn er ekki í banni.',
'blocklink'                   => 'banna',
'unblocklink'                 => 'afbanna',
'change-blocklink'            => 'breyta bönnun',
'contribslink'                => 'framlög',
'autoblocker'                 => 'Vistfang þitt er bannað vegna þess að það hefur nýlega verið notað af „[[User:$1|$1]]“.
Ástæðan fyrir því að $1 var bannaður er: „$2“',
'blocklogpage'                => 'Bönnunarskrá',
'blocklogentry'               => 'bannaði „[[$1]]“; rennur út eftir: $2 $3',
'blocklogtext'                => 'Þetta er skrá yfir bönn sem lögð hafa verið á notendur eða bönn sem hafa verið numin úr gildi.
Vistföng sem sett hafa verið í bann sjálfvirkt birtast ekki hér.
Sjá [[Special:IPBlockList|ítarlegri lista]] fyrir öll núgildandi bönn.',
'unblocklogentry'             => 'afbannaði $1',
'block-log-flags-anononly'    => 'bara ónefndir notendur',
'block-log-flags-nocreate'    => 'gerð aðganga bönnuð',
'block-log-flags-noautoblock' => 'sjálfkrafa bann óvirkt',
'block-log-flags-noemail'     => 'netfang bannað',
'block-log-flags-hiddenname'  => 'notandanafn falið',
'ipb_expiry_invalid'          => 'Tími ógildur.',
'ipb_already_blocked'         => '„$1“ er nú þegar í banni',
'ipb_cant_unblock'            => 'Villa: Bann-tala $1 fannst ekki. Bannið gæti verið útrunnið eða hún afbönnuð.',
'ip_range_invalid'            => 'Ógilt vistfangasvið.',
'blockme'                     => 'Banna mig',
'proxyblocker-disabled'       => 'Þessi virkni er óvirk.',
'proxyblocksuccess'           => 'Búinn.',
'cant-block-while-blocked'    => 'Þú getur ekki bannað aðra notendur á meðan þú ert í banni.',

# Developer tools
'lockdb'              => 'Læsa gagnagrunninum',
'unlockdb'            => 'Opna gagnagrunninn',
'lockconfirm'         => 'Já, ég vil læsa gagnagrunninum.',
'unlockconfirm'       => 'Já, ég vil aflæsa gagnagrunninum.',
'lockbtn'             => 'Læsa gagnagrunni',
'unlockbtn'           => 'Aflæsa gagnagrunninum',
'locknoconfirm'       => 'Þú hakaðir ekki í staðfestingarrammann.',
'lockdbsuccesssub'    => 'Læsing á gagnagrunninum tókst',
'unlockdbsuccesssub'  => 'Læsing á gagnagrunninum hefur verið fjarlægð',
'lockdbsuccesstext'   => 'Gagnagrunninum hefur verið læst.<br />
Mundu að [[Special:UnlockDB|opna hann aftur]] þegar þú hefur lokið viðgerðum.',
'unlockdbsuccesstext' => 'Gagnagrunnurinn hefur verið opnaður.',
'databasenotlocked'   => 'Gagnagrunnurinn er ekki læstur.',

# Move page
'move-page'                 => 'Færa $1',
'move-page-legend'          => 'Færa síðu',
'movepagetext'              => "Hér er hægt að endurnefna síðu. Hún færist, ásamt breytingaskránni, yfir á nýtt heiti og eldra heitið myndar tilvísun á það. Þú getur sjálfkrafa uppfært tilvísanir á nýja heitið. Ef þú vilt það síður, athugaðu þá hvort nokkuð myndist [[Special:DoubleRedirects|tvöfaldar]] eða [[Special:BrokenRedirects|brotnar tilvísanir]].
Þú berð ábyrgð á því að tenglar vísi á rétta staði.

Athugaðu að síðan mun '''ekki''' færast ef þegar er síða á nafninu sem þú hyggst færa hana á, nema sú síða sé tóm eða tilvísun sem vísar á síðuna sem þú ætlar að færa. Þú getur þar með fært síðuna aftur til baka án þess að missa breytingarsöguna, en ekki fært hana yfir venjulega síðu.

'''Varúð:'''
Athugaðu að þessi aðgerð getur kallað fram viðbrögð annarra notenda og getur þýtt mjög rótækar breytingar á vinsælum síðum.",
'movepagetalktext'          => 'Spallsíða síðunnar verður sjálfkrafa færð með ef hún er til nema:
* Þú sért að færa síðuna á milli nafnrýma
* Spallsíða sé þegar til undir nýja nafninu
* Þú veljir að færa hana ekki
Í þeim tilfellum verður að færa hana handvirkt.',
'movearticle'               => 'Færa síðu:',
'movenologin'               => 'Óinnskráð(ur)',
'movenologintext'           => 'Þú verður að vera [[Special:UserLogin|innskráð(ur)]] til að geta fært síður.',
'movenotallowed'            => 'Þú hefur ekki leyfi til að færa síður.',
'movenotallowedfile'        => 'Þú hefur ekki leyfi til að færa skrár.',
'cant-move-user-page'       => 'Þú hefur ekki leyfi til að færa notandasíðu (fyrir utan undirsíður).',
'cant-move-to-user-page'    => 'Þú hefur ekki leyfi til að færa síðu á notandasíðu (að frátöldum undirsíðum notanda).',
'newtitle'                  => 'Á nýja titilinn:',
'move-watch'                => 'Vakta þessa síðu',
'movepagebtn'               => 'Færa síðu',
'pagemovedsub'              => 'Færsla tókst',
'movepage-moved'            => "'''„$1“ hefur verið færð á „$2“'''",
'movepage-moved-redirect'   => 'Tilvísun hefur verið búin til.',
'movepage-moved-noredirect' => 'Tilvísun var ekki búin til.',
'articleexists'             => 'Annaðhvort er þegar til síða undir þessum titli, eða sá titill sem þú hefur valið er ekki gildur.
Vinsamlegast veldu annan titil.',
'cantmove-titleprotected'   => 'Þú getur ekki fært síðu á þessa staðsetningu, því nýi titillinn hefur verið verndaður gegn sköpun',
'talkexists'                => "'''Færsla á síðunni sjálfri heppnaðist, en ekki var hægt að færa spjallsíðuna því hún er nú þegar til á nýja titlinum.
Gjörðu svo vel og færðu hana handvirkt.'''",
'movedto'                   => 'fært á',
'movetalk'                  => 'Færa meðfylgjandi spjallsíðu',
'move-subpages'             => 'Færa undirstíður (upp að $1)',
'move-talk-subpages'        => 'Færa undirstíður spjallsíðunnar (upp að $1)',
'movepage-page-exists'      => 'Síðan $1 er nú þegar til og er ekki hægt að yfirskrifa sjálfkrafa.',
'movepage-page-moved'       => 'Síðan $1 hefur verið færð á $2.',
'movepage-page-unmoved'     => 'Ekki var hægt að færa síðuna $1 á $2.',
'movepage-max-pages'        => 'Hámarkinu, $1 {{PLURAL:$1|síða|síður}}, hefur verið náð og verða engar fleiri færðar sjálfvirkt.',
'1movedto2'                 => '[[$1]] færð á [[$2]]',
'1movedto2_redir'           => '[[$1]] færð á [[$2]] yfir tilvísun',
'move-redirect-suppressed'  => 'tilvísun leynd',
'movelogpage'               => 'Flutningaskrá',
'movelogpagetext'           => 'Þetta er listi yfir síður sem nýlega hafa verið færðar.',
'movesubpage'               => '{{Plural:$1|Undirsíða|Undirsíður}}',
'movereason'                => 'Ástæða:',
'revertmove'                => 'taka til baka',
'delete_and_move'           => 'Eyða og flytja',
'delete_and_move_text'      => '==Beiðni um eyðingu==

Síðan „[[:$1]]“ er þegar til. Viltu eyða henni til þess að rýma til fyrir flutningi?',
'delete_and_move_confirm'   => 'Já, eyða síðunni',
'delete_and_move_reason'    => 'Eytt til að rýma til fyrir flutning',
'selfmove'                  => 'Nýja nafnið er það sama og gamla, þú verður að velja annað nafn.',
'immobile-source-namespace' => 'Get ekki fært síður í nafnrýminu „$1“',
'immobile-target-namespace' => 'Get ekki fært síður inn í nafnrýmið „$1“',
'immobile-source-page'      => 'Þessi síða er ekki færanleg.',
'immobile-target-page'      => 'Get ekki fært á áætlaðan titil.',
'imagenocrossnamespace'     => 'Get ekki fært skrá í skrálaust nafnrými',
'imagetypemismatch'         => 'Nýi nafnaukinn passar ekki við tegund hennar',
'imageinvalidfilename'      => 'Markskráarnafnið er ógilt',
'fix-double-redirects'      => 'Uppfæra tilvísanir sem vísa á upphaflegan titil',
'move-leave-redirect'       => 'Skilja tilvísun eftir',

# Export
'export'            => 'Flytja út síður',
'exportcuronly'     => 'Aðeins núverandi útgáfu án breytingaskrár',
'exportnohistory'   => "----
'''Athugaðu:''' Að flytja út alla breytingasögu síðna á þennan hátt hefur verið óvirkjað vegna ástæðna afkasta.",
'export-submit'     => 'Flytja',
'export-addcattext' => 'Bæta við síðum frá flokkinum:',
'export-addcat'     => 'Bæta við',
'export-download'   => 'Vista sem skjal',

# Namespace 8 related
'allmessages'               => 'Meldingar',
'allmessagesname'           => 'Titill',
'allmessagesdefault'        => 'Sjálfgefinn texti',
'allmessagescurrent'        => 'Núverandi texti',
'allmessagestext'           => 'Þetta er listi yfir kerfismeldingar í Melding-nafnrýminu.
Gjörðu svo vel og heimsæktu [http://www.mediawiki.org/wiki/Localisation MediaWiki-staðfæringuna] og [http://translatewiki.net translatewiki.net] ef þú vilt taka þátt í almennri MediaWiki-staðfæringu.',
'allmessagesnotsupportedDB' => "Það er ekki hægt að nota '''{{ns:special}}:Allmessages''' því '''\$wgUseDatabaseMessages''' hefur verið gerð óvirk.",
'allmessages-language'      => 'Tungumál:',
'allmessages-filter-submit' => 'Áfram',

# Thumbnails
'thumbnail-more'  => 'Stækka',
'filemissing'     => 'Skrá vantar',
'thumbnail_error' => 'Villa við gerð smámyndar: $1',

# Special:Import
'import'                     => 'Flytja inn síður',
'importinterwiki'            => 'Milli-Wiki innflutningur',
'import-interwiki-text'      => 'Veldu Wiki-kerfi og síðutitil til að flytja inn.
Breytingaupplýsingar s.s. dagsetningar og höfundanöfn eru geymd.
Allir innflutningar eru skráð í [[Special:Log/import|innflutningsskránna]].',
'import-interwiki-history'   => 'Afrita allar breytingar þessarar síðu',
'import-interwiki-submit'    => 'Flytja inn',
'import-interwiki-namespace' => 'Ákvörðunarnafnrými:',
'import-upload-filename'     => 'Skráarnafn:',
'import-comment'             => 'Athugasemdir:',
'importstart'                => 'Flyt inn síður...',
'import-revision-count'      => '$1 {{PLURAL:$1|breyting|breytingar}}',
'importnopages'              => 'Engar síður til innflutnings.',
'importfailed'               => 'Innhlaðning mistókst: $1',
'importcantopen'             => 'Get ekki opnað innflutt skjal',
'importbadinterwiki'         => 'Villa í tungumálatengli',
'importnotext'               => 'Tómt eða enginn texti',
'importsuccess'              => 'Innflutningi lokið!',
'import-noarticle'           => 'Engin síða til innflutnings!',
'import-upload'              => 'Hlaða inn XML-gögnum',

# Import log
'importlogpage'                    => 'Innflutningsskrá',
'import-logentry-upload'           => 'flutti inn [[$1]] með innflutningi',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|breyting|breytingar}}',
'import-logentry-interwiki'        => 'flutti inn $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|breyting|breytingar}} frá $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Notandasíðan þín',
'tooltip-pt-anonuserpage'         => 'Notandasíðan fyrir vistfangið þitt',
'tooltip-pt-mytalk'               => 'Spjallsíðan þín',
'tooltip-pt-anontalk'             => 'Spjallsíðan fyrir þetta vistfang',
'tooltip-pt-preferences'          => 'Almennar stillingar',
'tooltip-pt-watchlist'            => 'Listi yfir síður sem þú fylgist með breytingum á',
'tooltip-pt-mycontris'            => 'Listi yfir framlög þín',
'tooltip-pt-login'                => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki skylda.',
'tooltip-pt-anonlogin'            => 'Þú ert hvattur/hvött til að innskrá þig, það er hinsvegar ekki nauðsynlegt.',
'tooltip-pt-logout'               => 'Útskráning',
'tooltip-ca-talk'                 => 'Spallsíða þessarar síðu',
'tooltip-ca-edit'                 => 'Þú getur breytt síðu þessari, vinsamlegast notaðu „forskoða“ hnappinn áður en þú vistar',
'tooltip-ca-addsection'           => 'Bæta nýjum hluta við',
'tooltip-ca-viewsource'           => 'Síða þessi er vernduð. Þú getur þó skoðað frumkóða hennar.',
'tooltip-ca-history'              => 'Eldri útgáfur af síðunni.',
'tooltip-ca-protect'              => 'Vernda þessa síðu',
'tooltip-ca-unprotect'            => 'Afvernda þessa síðu',
'tooltip-ca-delete'               => 'Eyða þessari síðu',
'tooltip-ca-undelete'             => 'Endurvekja breytingar á þessari síðu áður en að henni var eytt',
'tooltip-ca-move'                 => 'Færa þessa síðu',
'tooltip-ca-watch'                => 'Bæta þessari síðu við á vaktlistann',
'tooltip-ca-unwatch'              => 'Fjarlægja þessa síðu af vaktlistanum',
'tooltip-search'                  => 'Leit á þessari Wiki',
'tooltip-search-go'               => 'Fara á síðu með þessu nafni ef hún er til',
'tooltip-search-fulltext'         => 'Leita á síðunum eftir þessum texta',
'tooltip-p-logo'                  => 'Forsíða',
'tooltip-n-mainpage'              => 'Forsíða {{SITENAME}}',
'tooltip-n-mainpage-description'  => 'Heimsækja forsíðuna',
'tooltip-n-portal'                => 'Um verkefnið, hvernig er hægt að hjálpa og hvar á að byrja',
'tooltip-n-currentevents'         => 'Finna upplýsingar um líðandi stund',
'tooltip-n-recentchanges'         => 'Listi yfir nýlegar breytingar.',
'tooltip-n-randompage'            => 'Handahófsvalin síða',
'tooltip-n-help'                  => 'Efnisyfirlit yfir hjálparsíður.',
'tooltip-t-whatlinkshere'         => 'Listi yfir síður sem tengjast í þessa',
'tooltip-t-recentchangeslinked'   => 'Nýlegar breytingar á ítengdum síðum',
'tooltip-feed-rss'                => 'RSS fyrir þessa síðu',
'tooltip-feed-atom'               => 'Atom fyrir þessa síðu',
'tooltip-t-contributions'         => 'Sýna framlagslista þessa notanda',
'tooltip-t-emailuser'             => 'Senda þessum notanda tölvupóst',
'tooltip-t-upload'                => 'Hlaða inn skrám',
'tooltip-t-specialpages'          => 'Listi yfir kerfissíður',
'tooltip-t-print'                 => 'Prentanleg útgáfa af þessari síðu',
'tooltip-t-permalink'             => 'Varanlegur tengill',
'tooltip-ca-nstab-main'           => 'Sýna síðuna',
'tooltip-ca-nstab-user'           => 'Sýna notandasíðuna',
'tooltip-ca-nstab-media'          => 'Sýna margmiðlunarsíðuna',
'tooltip-ca-nstab-special'        => 'Þetta er kerfissíða, þér er óhæft að breyta henni.',
'tooltip-ca-nstab-project'        => 'Sýna verkefnasíðuna',
'tooltip-ca-nstab-image'          => 'Sýna skráarsíðu',
'tooltip-ca-nstab-mediawiki'      => 'Sýna kerfisskilaboðin',
'tooltip-ca-nstab-template'       => 'Sýna sniðið',
'tooltip-ca-nstab-help'           => 'Sýna hjálparsíðuna',
'tooltip-ca-nstab-category'       => 'Sýna efnisflokkasíðuna',
'tooltip-minoredit'               => 'Merkja þessa breytingu sem minniháttar',
'tooltip-save'                    => 'Vista breytingarnar',
'tooltip-preview'                 => 'Forskoða breytingarnar, vinsamlegast gerðu þetta áður en þú vistar!',
'tooltip-diff'                    => 'Sýna hvaða breytingar þú gerðir á textanum.',
'tooltip-compareselectedversions' => 'Sjá breytingarnar á þessari grein á milli útgáfanna sem þú valdir.',
'tooltip-watch'                   => 'Bæta þessari síðu á vaktlistann þinn',
'tooltip-recreate'                => 'Endurvekja síðuna þó henni hafi verið eytt',
'tooltip-upload'                  => 'Hefja innhleðslu',

# Stylesheets
'common.css'   => '/* Allt CSS sem sett er hér mun virka á öllum þemum. */',
'monobook.css' => '/* Það sem sett er hingað er bætt við Monobook stilsniðið fyrir allan vefinn */',

# Scripts
'common.js' => '/* Allt JavaScript sem sett er hér mun virka í hvert skipti sem að síða hleðst. */',

# Attribution
'anonymous'        => '{{PLURAL:$1|Óþekktur notandi|Óþekktir notendur}} á {{SITENAME}}',
'siteuser'         => '{{SITENAME}} notandi $1',
'lastmodifiedatby' => 'Þessari síðu var síðast breytt $1 klukkan $2 af $3.',
'othercontribs'    => 'Byggt á verkum $1.',
'others'           => 'aðrir',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|notandi|notendur}} $1',

# Info page
'infosubtitle'   => 'Upplýsingar um síðu',
'numedits'       => 'Fjöldi breytinga (síða): $1',
'numtalkedits'   => 'Fjöldi breytinga (spjall síða): $1',
'numwatchers'    => 'Fjöldi vaktara: $1',
'numauthors'     => 'Fjöldi frábrugðinna höfunda (grein): $1',
'numtalkauthors' => 'Fjöldi frábrugðinna höfunda (spjall síða): $1',

# Skin names
'skinname-standard'    => 'Sígilt',
'skinname-nostalgia'   => 'Gamaldags',
'skinname-cologneblue' => 'Kölnarblátt',
'skinname-monobook'    => 'EinBók',
'skinname-myskin'      => 'Mitt þema',
'skinname-chick'       => 'Gella',
'skinname-simple'      => 'Einfalt',
'skinname-modern'      => 'Nútímalegt',

# Math options
'mw_math_png'    => 'Alltaf birta PNG mynd',
'mw_math_simple' => 'HTML fyrir einfaldar jöfnur annars PNG',
'mw_math_html'   => 'HTML ef hægt er, annars PNG',
'mw_math_source' => 'Sýna TeX jöfnu (fyrir textavafra)',
'mw_math_modern' => 'Mælt með fyrir nýja vafra',
'mw_math_mathml' => 'MathML ef mögulegt (tilraun)',

# Math errors
'math_failure'          => 'Þáttun mistókst',
'math_unknown_error'    => 'óþekkt villa',
'math_unknown_function' => 'óþekkt virkni',
'math_lexing_error'     => 'lestrarvilla',
'math_syntax_error'     => 'málfræðivilla',

# Patrolling
'markaspatrolleddiff'                 => 'Merkja sem yfirfarið',
'markaspatrolledtext'                 => 'Merkja þessa síðu sem yfirfarna',
'markedaspatrolled'                   => 'Merkja sem yfirfarið',
'markedaspatrolledtext'               => 'Valin breyting hefur verið merkt sem yfirfarin.',
'rcpatroldisabled'                    => 'Slökkt á yfirferð nýlegra breytinga',
'rcpatroldisabledtext'                => 'Yfirferð nýlegra breytinga er ekki virk.',
'markedaspatrollederror'              => 'Get ekki merkt sem yfirfarið',
'markedaspatrollederrortext'          => 'Þú verður að velja breytingu til að merkja sem yfirfarið.',
'markedaspatrollederror-noautopatrol' => 'Þú hefur ekki réttindi til að merkja eigin breytingar sem yfirfarnar.',

# Patrol log
'patrol-log-page'   => 'Yfirferðarskrá',
'patrol-log-header' => 'Þetta er skrá yfir yfirfarna breytingar.',
'patrol-log-line'   => 'merkti $1 eftir $2 sem yfirfarið $3',
'patrol-log-auto'   => '(sjálfkrafa)',
'patrol-log-diff'   => 'útgáfa $1',

# Image deletion
'deletedrevision'       => 'Eydd gömul útgáfu $1',
'filedeleteerror-short' => 'Villa við eyðingu: $1',
'filedeleteerror-long'  => 'Það kom upp villa við eyðingu skráarinnar: $1',
'filedelete-missing'    => 'Skránni „$1“ er ekki hægt að eyða vegna þess að hún er ekki til.',

# Browsing diffs
'previousdiff' => '← Eldri breyting',
'nextdiff'     => 'Nýrri breyting →',

# Media information
'mediawarning'         => "'''AÐVÖRUN''': Þessi skrá kann að hafa meinfýsinn kóða, ef keyrður kann hann að stofna kerfinu þínu í hættu.",
'imagemaxsize'         => 'Takmarka myndir á skráarlýsingasíðum við:',
'thumbsize'            => 'Stærð smámynda:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|síða|síður}}',
'file-info'            => '(stærð skráar: $1, MIME-tegund: $2)',
'file-info-size'       => '($1 × $2 dílar, stærð skráar: $3, MIME-gerð: $4)',
'file-nohires'         => '<small>Það er engin hærri upplausn til.</small>',
'svg-long-desc'        => '(SVG-skrá, að nafni til $1 × $2 dílar, skráarstærð: $3)',
'show-big-image'       => 'Mesta upplausn',
'show-big-image-thumb' => '<small>Myndin er í upplausninni $1 × $2 </small>',

# Special:NewFiles
'newimages'             => 'Myndasafn nýlegra skráa',
'imagelisttext'         => 'Hér fyrir neðan er {{PLURAL:$1|einni skrá|$1 skrám}} raðað $2.',
'newimages-summary'     => 'Þessi kerfissíða sýnir nýlega innhlaðnar skrár.',
'newimages-legend'      => 'Sía',
'newimages-label'       => 'Skráarnafn (eða hluti þess):',
'showhidebots'          => '($1 vélmenni)',
'noimages'              => 'Ekkert að sjá.',
'ilsubmit'              => 'Leita',
'bydate'                => 'eftir dagsetningu',
'sp-newimages-showfrom' => 'Leita af nýjum skráum frá $2, $1',

# Bad image list
'bad_image_list' => 'Sniðið er eftirfarandi:

Aðeins listaeigindi (línur sem byrja á *) eru meðtalin.
Fyrsti tengillinn í hverri línu verður að tengja í slæma skrá.
Allir síðari tenglar á sömu línu eru taldir vera undantekningar, þ.e. síður þar sem að skráin kann að koma fyrir innfelld.',

# Metadata
'metadata'          => 'Lýsigögn',
'metadata-help'     => 'Þessi skrá inniheldur viðbótarupplýsingar, líklega frá stafrænu myndavélinni eða skannanum sem notaður var til að gera eða stafræna hana.
Ef skránni hefur verið breytt, kann að vera að einhverjar upplýsingar eigi ekki við um hana.',
'metadata-expand'   => 'Sýna frekari upplýsingar',
'metadata-collapse' => 'Fela auka upplýsingar',
'metadata-fields'   => 'EXIF-lýsigögn listuð í þessu skilaboði munu vera innifalin á myndasíðusýningu þegar lýsigagnataflan er samfallin.
Önnur verða sjálfkrafa falin.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

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
'exif-exifversion'      => 'Exif-útgáfa',
'exif-pixelydimension'  => 'Leyfð myndalengd',
'exif-pixelxdimension'  => 'Leyfð myndahæð',
'exif-usercomment'      => 'Athugunarsemdir notanda',
'exif-flash'            => 'Leiftur',
'exif-gpslatitude'      => 'Breiddargráða',
'exif-gpslongitude'     => 'Lengdargráða',
'exif-gpsaltitude'      => 'Stjörnuhæð',

# EXIF attributes
'exif-compression-1' => 'Ósamþjappað',

'exif-componentsconfiguration-0' => 'er ekki til',

'exif-exposureprogram-0' => 'Ekki skilgreint',

'exif-subjectdistance-value' => '$1 metrar',

'exif-lightsource-1'  => 'Dagsbirta',
'exif-lightsource-4'  => 'Leiftur',
'exif-lightsource-9'  => 'Gott veður',
'exif-lightsource-10' => 'Skýjað',
'exif-lightsource-11' => 'Skuggi',

'exif-focalplaneresolutionunit-2' => 'tommur',

'exif-scenecapturetype-0' => 'Staðlað',
'exif-scenecapturetype-1' => 'Landslag',
'exif-scenecapturetype-2' => 'Skammsnið',
'exif-scenecapturetype-3' => 'Næturvettvangur',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kílómetrar á klukkustund',
'exif-gpsspeed-m' => 'Mílur á klukkustund',
'exif-gpsspeed-n' => 'Hnútar',

# External editor support
'edit-externally'      => 'Breyta þessari skrá með utanaðkomandi hugbúnaði',
'edit-externally-help' => '(Sjá [http://www.mediawiki.org/wiki/Manual:External_editors leiðbeiningar] fyrir meiri upplýsingar)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'allt',
'imagelistall'     => 'allar',
'watchlistall2'    => 'allt',
'namespacesall'    => 'öll',
'monthsall'        => 'allir',
'limitall'         => 'alla',

# E-mail address confirmation
'confirmemail'             => 'Staðfesta netfang',
'confirmemail_noemail'     => 'Þú hefur ekki gefið upp gilt netfang í [[Special:Preferences|notandastillingum]] þínum.',
'confirmemail_text'        => '{{SITENAME}} krefst þess að þú staðfestir netfangið þitt áður en að þú getur notað eiginleika tengt því. Smelltu á hnappinn að neðan til að fá staðfestingarpóst sendan á netfangið. Pósturinn mun innihalda tengil með kóða í sér; opnaðu tengilinn í vafranum til að staðfesta að netfangið sé rétt.',
'confirmemail_pending'     => 'Þú hefur nú þegar fengið staðfestingarpóst sendann; ef það er stutt síðan
þú bjóst til aðganginn þinn, væri ráð að býða í nokkrar mínútur eftir póstinum
áður en að þú byður um að fá nýjan kóða sendann.',
'confirmemail_send'        => 'Senda staðfestingarkóða með tölvupósti',
'confirmemail_sent'        => 'Staðfestingartölvupóstur sendur.',
'confirmemail_oncreate'    => 'Staðfestingarkóði hefur verði sendur á netfangið.
Þennan kóða þarf ekki að staðfesta til að skrá sig inn, en þú þarft að gefa hann upp áður
en opnað verður fyrir valmöguleika tengdum netfangi á þessu wiki-verkefni.',
'confirmemail_sendfailed'  => '{{SITENAME}} gat ekki sent staðfestingarpóst.
Athugaðu hvort ógild tákn séu í netfanginu þínu.

Póstþjónninn skilaði: $1',
'confirmemail_invalid'     => 'Ógildur staðfestingarkóði. Hann gæti verið útrunninn.',
'confirmemail_needlogin'   => 'Þú verður að $1 til að staðfesta netfangið þitt.',
'confirmemail_success'     => 'Netfang þitt hefur verið staðfest. Þú getur nú skráð þig inn og vafrað um wiki-kerfið.',
'confirmemail_loggedin'    => 'Netfang þitt hefur verið staðfest.',
'confirmemail_error'       => 'Eitthvað fór úrskeiðis við vistun staðfestingarinnar.',
'confirmemail_subject'     => '{{SITENAME}} netfangs-staðfesting',
'confirmemail_body'        => 'Einhver, sennilega þú, með vistfangið $1 hefur skráð sig á {{SITENAME}} undir notandanafninu „$2“ og gefið upp þetta netfang.

Til að staðfesta að það hafi verið þú sem skráðir þig undir þessu nafni, og til þess að virkja póstsendingar í gegnum {{SITENAME}}, skaltu opna þennan tengil í vafranum þínum:

$3

Ef þú ert *ekki* sá/sú sem skráði þetta notandanafn, skaltu opna þennan tengil til að ógilda staðfestinguna:

$5

Þessi staðfestingarkóði rennur út $4.',
'confirmemail_invalidated' => 'Hætt við staðfestingu netfangs',
'invalidateemail'          => 'Hætta við staðfestingu netfangs',

# Scary transclusion
'scarytranscludefailed'  => '[Gat ekki sótt snið fyrir $1]',
'scarytranscludetoolong' => '[vefslóðin er of löng]',

# Trackbacks
'trackbackbox'      => 'Varanlegir tenglar fyrir þessa grein:<br />
$1',
'trackbackremove'   => '([$1 eydd])',
'trackbacklink'     => 'Varanlegur tengill',
'trackbackdeleteok' => 'Varanlega tenglinum var eytt.',

# Delete conflict
'deletedwhileediting' => "'''Viðvörun''': Þessari síðu var eytt eftir að þú fórst að breyta henni!",
'confirmrecreate'     => "Notandi [[User:$1|$1]] ([[User talk:$1|spjall]]) eyddi þessari síðu eftir að þú fórst að breyta henni út af:
: ''$2''
Vinsamlegast staðfestu að þú viljir endurvekja hana.",
'recreate'            => 'Endurvekja',

# action=purge
'confirm_purge_button' => 'Í lagi',
'confirm-purge-top'    => 'Hreinsa skyndiminni þessarar síðu?',

# Multipage image navigation
'imgmultipageprev' => '← fyrri síða',
'imgmultipagenext' => 'næsta síða →',
'imgmultigo'       => 'Áfram!',
'imgmultigoto'     => 'Fara á síðu $1',

# Table pager
'ascending_abbrev'         => 'hækkandi',
'descending_abbrev'        => 'lækkandi',
'table_pager_next'         => 'Næsta síða',
'table_pager_prev'         => 'Fyrri síða',
'table_pager_first'        => 'Fyrsta síðan',
'table_pager_last'         => 'Síðasta síðan',
'table_pager_limit'        => 'Sýna $1 hluta á hverri síðu',
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
'lag-warn-normal' => 'Breytingar nýrri en $1 {{PLURAL:$1|sekúnda|sekúndur}} kunna að vera ekki á þessm lista.',
'lag-warn-high'   => 'Vegna mikils álags á vefþjónanna, kunna breytingar yngri en $1 {{PLURAL:$1|sekúnda|sekúndur}} ekki að vera á þessum lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'Á vaktlista þínum {{PLURAL:$1|er 1 síða|eru $1 síður}}, að undanskildum spjallsíðum.',
'watchlistedit-noitems'        => 'Vaktlistinn þinn inniheldur enga titla.',
'watchlistedit-normal-title'   => 'Breyta vaktlistanum',
'watchlistedit-normal-legend'  => 'Fjarlægja titla af vaktlistanum',
'watchlistedit-normal-explain' => 'Titlarnir á vaktlistanum þínum er sýndir fyrir neðan. Til að fjarlægja titil hakaðu í kassan við hliðina á honum og smelltu á „Fjarlægja titla“. Þú getur einnig [[Special:Watchlist/raw|breytt honum opnum]].',
'watchlistedit-normal-submit'  => 'Fjarlægja titla',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Ein síða var fjarlægð|$1 síður voru fjarlægðar}} af vaktlistanum þínum:',
'watchlistedit-raw-title'      => 'Breyta opnum vaktlistanum',
'watchlistedit-raw-legend'     => 'Breyta opnum vaktlistanum',
'watchlistedit-raw-explain'    => 'Titlarnir á vaktlistanum þínum er sýndir fyrir neðan, þar sem mögulegt er að breyta þeim með því að bæta við hann og taka af honum; einn tiltil í hverri línu. Þegar þú er búinn, smelltu þá á „Uppfæra vaktlistann“. Þú getur einnig notað [[Special:Watchlist/edit|staðlaða breytinn]].',
'watchlistedit-raw-titles'     => 'Titlar:',
'watchlistedit-raw-submit'     => 'Uppfæra vaktlistann',
'watchlistedit-raw-done'       => 'Vaktlistinn þinn hefur verið uppfærður.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Einum titli|$1 titlum}} var bætt við:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titill var fjarlægður|$1 titlar voru fjarlægðir}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Sýna viðeigandi breytingar',
'watchlisttools-edit' => 'Skoða og breyta vaktlistanum',
'watchlisttools-raw'  => 'Breyta opnum vaktlistanum',

# Special:Version
'version'                  => 'Útgáfa',
'version-extensions'       => 'Uppsettar viðbætur',
'version-specialpages'     => 'Kerfissíður',
'version-variables'        => 'Breytur',
'version-other'            => 'Aðrar',
'version-version'          => '(Útgáfa $1)',
'version-license'          => 'Leyfi',
'version-software'         => 'Uppsettur hugbúnaður',
'version-software-product' => 'Vara',
'version-software-version' => 'Útgáfa',

# Special:FilePath
'filepath'        => 'Slóð skráar',
'filepath-page'   => 'Skrá:',
'filepath-submit' => 'Slóð',

# Special:FileDuplicateSearch
'fileduplicatesearch-legend'   => 'Leita að afriti',
'fileduplicatesearch-filename' => 'Skráarnafn:',
'fileduplicatesearch-submit'   => 'Leita',
'fileduplicatesearch-info'     => '$1 × $2 myndeining<br />Skráarstærð: $3<br />MIME-gerð: $4',
'fileduplicatesearch-result-1' => 'Skráin „$1“ hefur engin nákvæmlega eins afrit.',
'fileduplicatesearch-result-n' => 'Skráin „$1“ hefur {{PLURAL:$2|1 nákvæmlega eins afrit|$2 nákvæmlega eins afrit}}.',

# Special:SpecialPages
'specialpages'                   => 'Kerfissíður',
'specialpages-group-maintenance' => 'Viðhaldsskýrslur',
'specialpages-group-other'       => 'Aðrar kerfissíður',
'specialpages-group-login'       => 'Innskrá / Búa til aðgang',
'specialpages-group-changes'     => 'Nýlegar breytingar og skrár',
'specialpages-group-media'       => 'Miðilsskrár og innhleðslur',
'specialpages-group-users'       => 'Notendur og réttindi',
'specialpages-group-highuse'     => 'Mest notaðar síður',
'specialpages-group-pages'       => 'Listar yfir síður',
'specialpages-group-pagetools'   => 'Síðuverkfæri',

# Special:BlankPage
'blankpage' => 'Tóm síða',

# Special:Tags
'tags-edit' => 'breyta',

# Database error messages
'dberr-usegoogle' => 'Þú getur notað Google til að leita á meðan.',

);
