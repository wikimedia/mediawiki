<?php
/** Estonian (Eesti)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Avjoska
 * @author Hendrix
 * @author Jaan513
 * @author KalmerE.
 * @author Ker
 * @author Pikne
 * @author Silvar
 * @author Võrok
 * @author WikedKentaur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Meedia',
	NS_SPECIAL          => 'Eri',
	NS_TALK             => 'Arutelu',
	NS_USER             => 'Kasutaja',
	NS_USER_TALK        => 'Kasutaja_arutelu',
	NS_PROJECT_TALK     => '$1_arutelu',
	NS_FILE             => 'Pilt',
	NS_FILE_TALK        => 'Pildi_arutelu',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_arutelu',
	NS_TEMPLATE         => 'Mall',
	NS_TEMPLATE_TALK    => 'Malli_arutelu',
	NS_HELP             => 'Juhend',
	NS_HELP_TALK        => 'Juhendi_arutelu',
	NS_CATEGORY         => 'Kategooria',
	NS_CATEGORY_TALK    => 'Kategooria_arutelu',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Kahekordsed_ümbersuunamised' ),
	'BrokenRedirects'           => array( 'Vigased_ümbersuunamised' ),
	'Disambiguations'           => array( 'Täpsustusleheküljed' ),
	'Userlogin'                 => array( 'Sisselogimine' ),
	'Userlogout'                => array( 'Väljalogimine' ),
	'CreateAccount'             => array( 'Konto_loomine' ),
	'Preferences'               => array( 'Eelistused' ),
	'Watchlist'                 => array( 'Jälgimisloend' ),
	'Recentchanges'             => array( 'Viimased_muudatused' ),
	'Upload'                    => array( 'Faili_üleslaadimine' ),
	'Listfiles'                 => array( 'Failide_loend' ),
	'Newimages'                 => array( 'Uued_failid' ),
	'Listusers'                 => array( 'Kasutajate_loend' ),
	'Listgrouprights'           => array( 'Kasutajarühma_õigused' ),
	'Statistics'                => array( 'Arvandmestik' ),
	'Randompage'                => array( 'Juhuslik_artikkel' ),
	'Lonelypages'               => array( 'Viitamata_leheküljed' ),
	'Uncategorizedpages'        => array( 'Kategoriseerimata_leheküljed' ),
	'Uncategorizedcategories'   => array( 'Kategoriseerimata_kategooriad' ),
	'Uncategorizedimages'       => array( 'Kategoriseerimata_failid' ),
	'Uncategorizedtemplates'    => array( 'Kategoriseerimata_mallid' ),
	'Unusedcategories'          => array( 'Kasutamata_kategooriad' ),
	'Unusedimages'              => array( 'Kasutamata_failid' ),
	'Wantedpages'               => array( 'Oodatud_leheküljed' ),
	'Wantedcategories'          => array( 'Oodatud_kategooriad' ),
	'Wantedfiles'               => array( 'Oodatud_failid' ),
	'Wantedtemplates'           => array( 'Oodatud_mallid' ),
	'Mostlinked'                => array( 'Kõige_viidatumad_leheküljed' ),
	'Mostlinkedcategories'      => array( 'Kõige_viidatumad_kategooriad' ),
	'Mostlinkedtemplates'       => array( 'Kõige_viidatumad_mallid' ),
	'Mostimages'                => array( 'Kõige_kasutatumad_failid' ),
	'Mostcategories'            => array( 'Enim_kategoriseeritud' ),
	'Mostrevisions'             => array( 'Enim_muudatusi' ),
	'Fewestrevisions'           => array( 'Vähim_muudatusi' ),
	'Shortpages'                => array( 'Lühikesed_leheküljed' ),
	'Longpages'                 => array( 'Pikad_leheküljed' ),
	'Newpages'                  => array( 'Uued_leheküljed' ),
	'Ancientpages'              => array( 'Vanimad_leheküljed' ),
	'Deadendpages'              => array( 'Edasipääsuta_leheküljed' ),
	'Protectedpages'            => array( 'Kaitstud_leheküljed' ),
	'Protectedtitles'           => array( 'Kaitstud_pealkirjad' ),
	'Allpages'                  => array( 'Kõik_leheküljed' ),
	'Prefixindex'               => array( 'Kõik_pealkirjad_eesliitega' ),
	'Ipblocklist'               => array( 'Blokiloend' ),
	'Specialpages'              => array( 'Erileheküljed' ),
	'Contributions'             => array( 'Kaastöö' ),
	'Emailuser'                 => array( 'E-kirja_saatmine' ),
	'Confirmemail'              => array( 'E-posti_aadressi_kinnitamine' ),
	'Whatlinkshere'             => array( 'Lingid_siia' ),
	'Recentchangeslinked'       => array( 'Seotud_muudatused' ),
	'Movepage'                  => array( 'Teisalda' ),
	'Booksources'               => array( 'Otsi_raamatut' ),
	'Categories'                => array( 'Kategooriad' ),
	'Export'                    => array( 'Lehekülgede_eksport' ),
	'Version'                   => array( 'Versioon' ),
	'Allmessages'               => array( 'Kõik_sõnumid' ),
	'Log'                       => array( 'Logid' ),
	'Blockip'                   => array( 'Blokeeri' ),
	'Undelete'                  => array( 'Taasta_lehekülg' ),
	'Import'                    => array( 'Lehekülgede_import' ),
	'Lockdb'                    => array( 'Lukusta_andmebaas' ),
	'Unlockdb'                  => array( 'Ava_andmebaas' ),
	'Userrights'                => array( 'Kasutaja_õigused' ),
	'MIMEsearch'                => array( 'MIME_otsing' ),
	'FileDuplicateSearch'       => array( 'Otsi_faili_duplikaate' ),
	'Unwatchedpages'            => array( 'Jälgimata_leheküljed' ),
	'Listredirects'             => array( 'Ümbersuunamised' ),
	'Revisiondelete'            => array( 'Kustuta_muudatus' ),
	'Unusedtemplates'           => array( 'Kasutamata_mallid' ),
	'Randomredirect'            => array( 'Juhuslik_ümbersuunamine' ),
	'Mypage'                    => array( 'Minu_lehekülg' ),
	'Mytalk'                    => array( 'Minu_aruteluleht' ),
	'Mycontributions'           => array( 'Minu_kaastöö' ),
	'Listadmins'                => array( 'Ülemaloend' ),
	'Listbots'                  => array( 'Robotiloend' ),
	'Popularpages'              => array( 'Loetumad_leheküljed' ),
	'Search'                    => array( 'Otsi' ),
	'Resetpass'                 => array( 'Muuda_parool' ),
	'Withoutinterwiki'          => array( 'Ilma_keelelinkideta' ),
	'MergeHistory'              => array( 'Liitmisajalugu' ),
	'Filepath'                  => array( 'Failitee' ),
	'Invalidateemail'           => array( 'Tühista_e-posti_kinnitus' ),
	'Blankpage'                 => array( 'Tühi_leht' ),
	'LinkSearch'                => array( 'Otsi_välislinke' ),
	'DeletedContributions'      => array( 'Kustutatud_kaastöö' ),
	'Tags'                      => array( 'Tähistused' ),
	'Activeusers'               => array( 'Teguskasutajad' ),
);

#Lisasin eestimaised poed, aga võõramaiseid ei julenud kustutada.
$bookstoreList = array(
	'Apollo' => 'http://www.apollo.ee/search.php?keyword=$1&search=OTSI',
	'minu Raamat' => 'http://www.raamat.ee/advanced_search_result.php?keywords=$1',
	'Raamatukoi' => 'http://www.raamatukoi.ee/cgi-bin/index?valik=otsing&paring=$1',
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#suuna', '#REDIRECT' ),
	'notoc'                 => array( '0', '__SISUKORRATA__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__GALERIITA__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__SISUKORDEES__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__SISUKORD__', '__TOC__' ),
	'noeditsection'         => array( '0', '__ALAOSALINGITA__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'HETKEKUU', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'HETKEKUU1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'HETKEKUUNIMETUS', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'HETKEKUUPÄEV', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'HETKEKUUPÄEV2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'HETKENÄDALAPÄEV', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'HETKEAASTA', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'HETKEAEG', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'HETKETUND', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'KOHALIKKUU', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'KOHALIKKUU1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'KOHALIKKUUNIMETUS', 'LOCALMONTHNAME' ),
	'localday'              => array( '1', 'KOHALIKKUUPÄEV', 'LOCALDAY' ),
	'localday2'             => array( '1', 'KOHALIKKUUPÄEV2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'KOHALIKNÄDALAPÄEV', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'KOHALIKAASTA', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'KOHALIKAEG', 'LOCALTIME' ),
	'localhour'             => array( '1', 'KOHALIKTUND', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'LEHEMÄÄR', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ARTIKLIMÄÄR', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'FAILIMÄÄR', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'KASUTAJAMÄÄR', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'TEGUSKASUTAJAMÄÄR', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'REDIGEERIMISMÄÄR', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'LEHEKÜLJENIMI', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'LEHEKÜLJENIMI1', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NIMERUUM', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NIMERUUM1', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'ARUTELUNIMERUUM', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'ARUTELUNIMERUUM1', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'SISUNIMERUUM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'SISUNIMERUUM1', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'KOGULEHEKÜLJENIMI', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'KOGULEHEKÜLJENIMI1', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ALAMLEHEKÜLJENIMI', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ALAMLEHEKÜLJENIMI1', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'NIMERUUMITANIMI', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'NIMERUUMITANIMI1', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'ARUTELUNIMI', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'ARUTELUNIMI1', 'TALKPAGENAMEE' ),
	'img_thumbnail'         => array( '1', 'pisi', 'pisipilt', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'pisi=$1', 'pisipilt=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'paremal', 'right' ),
	'img_left'              => array( '1', 'vasakul', 'left' ),
	'img_none'              => array( '1', 'tühi', 'none' ),
	'img_center'            => array( '1', 'keskel', 'center', 'centre' ),
	'img_framed'            => array( '1', 'raam', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'raamita', 'frameless' ),
	'img_border'            => array( '1', 'ääris', 'border' ),
	'sitename'              => array( '1', 'KOHANIMI', 'SITENAME' ),
	'ns'                    => array( '0', 'NR:', 'NS:' ),
	'nse'                   => array( '0', 'NR1:', 'NSE:' ),
	'localurl'              => array( '0', 'KOHALIKURL', 'LOCALURL:' ),
	'localurle'             => array( '0', 'KOHALIKURL1', 'LOCALURLE:' ),
	'servername'            => array( '0', 'SERVERINIMI', 'SERVERNAME' ),
	'currentweek'           => array( '1', 'HETKENÄDAL', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'HETKENÄDALAPÄEV1', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'KOHALIKNÄDAL', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'KOHALIKNÄDALAPÄEV1', 'LOCALDOW' ),
	'fullurl'               => array( '0', 'KOGUURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'KOGUURL1:', 'FULLURLE:' ),
	'newsectionlink'        => array( '1', '__UUEALAOSALINK__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__UUEALAOSALINGITA__', '__NONEWSECTIONLINK__' ),
	'currenttimestamp'      => array( '1', 'HETKEAJATEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'KOHALIKAJATEMPEL', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#KEEL:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'VAIKEKEEL', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'LEHEKÜLGINIMERUUMIS', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'ÜLEMAMÄÄR', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ARVUVORMINDUS', 'FORMATNUM' ),
	'defaultsort'           => array( '1', 'JÄRJESTA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'hiddencat'             => array( '1', '__PEIDETUDKAT__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'LEHEKÜLGIKATEGOORIAS', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'index'                 => array( '1', 'INDEKSIGA', '__INDEX__' ),
	'noindex'               => array( '1', 'INDEKSITA', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'KASUTAJAIDRÜHMAS', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'protectionlevel'       => array( '1', 'KAITSETASE', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'kuupäevavormindus', 'formatdate', 'dateformat' ),
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([äöõšüža-z]+)(.*)$/sDu';

$datePreferences = array(
	'default',
	'et numeric',
	'dmy',
	'et roman',
	'ISO 8601'
);

$datePreferenceMigrationMap = array(
	'default',
	'et numeric',
	'dmy',
	'et roman',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'et numeric time' => 'H:i',
	'et numeric date' => 'd.m.Y',
	'et numeric both' => 'd.m.Y, "kell" H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'j. F Y, "kell" H:i',

	'et roman time' => 'H:i',
	'et roman date' => 'j. xrm Y',
	'et roman both' => 'j. xrm Y, "kell" H:i',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Lingid alla kriipsutada',
'tog-highlightbroken'         => 'Vorminda lingirikked <a href="" class="new">nii</a> (alternatiiv: nii<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Lõikude rööpjoondus',
'tog-hideminor'               => 'Peida pisiparandused viimastes muudatustes',
'tog-hidepatrolled'           => 'Peida viimaste muudatuste loetelus jälgimisloendis esitatavad muudatused',
'tog-newpageshidepatrolled'   => 'Peida uute lehtede loendis kontrollitud leheküljed',
'tog-extendwatchlist'         => 'Laienda jälgimisloendit, et näha kõiki muudatusi, mitte vaid kõige värskemaid',
'tog-usenewrc'                => 'Laiendatud viimased muudatused (nõutav JavaScripti olemasolu)',
'tog-numberheadings'          => 'Pealkirjade automaatnummerdus',
'tog-showtoolbar'             => 'Redigeerimise tööriistariba näitamine',
'tog-editondblclick'          => 'Artiklite redigeerimine topeltklõpsu peale (JavaScript)',
'tog-editsection'             => '[redigeeri] lingid peatükkide muutmiseks',
'tog-editsectiononrightclick' => 'Peatükkide redigeerimine paremklõpsuga alampealkirjadel (JavaScript)',
'tog-showtoc'                 => 'Näita sisukorda (lehtedel, millel on rohkem kui 3 pealkirja)',
'tog-rememberpassword'        => 'Parooli meeldejätmine tulevasteks seanssideks',
'tog-editwidth'               => 'Laienda toimetamisaken ekraani laiuseks',
'tog-watchcreations'          => 'Lisa minu loodud lehed jälgimisloendisse',
'tog-watchdefault'            => 'Jälgi uusi ja muudetud artikleid',
'tog-watchmoves'              => 'Lisa minu teisaldatud artiklid jälgimisloendisse',
'tog-watchdeletion'           => 'Lisa minu kustutatud leheküljed jälgimisloendisse',
'tog-minordefault'            => 'Märgi kõik parandused vaikimisi pisiparandusteks',
'tog-previewontop'            => 'Näita eelvaadet toimetamisakna ees, mitte järel',
'tog-previewonfirst'          => 'Näita eelvaadet esimesel redigeerimisel',
'tog-nocache'                 => 'Keela lehekülgede puhverdamine',
'tog-enotifwatchlistpages'    => 'Teata meili teel, kui minu jälgitavat artiklit muudetakse',
'tog-enotifusertalkpages'     => 'Teata meili teel, kui minu arutelu lehte muudetakse',
'tog-enotifminoredits'        => 'Teata meili teel ka pisiparandustest',
'tog-enotifrevealaddr'        => 'Näita minu e-posti aadressi teatavakstegemiste e-kirjades.',
'tog-shownumberswatching'     => 'Näita jälgivate kasutajate hulka',
'tog-oldsig'                  => 'Praeguse allkirja eelvaade:',
'tog-fancysig'                => 'Kasuta vikiteksti vormingus allkirja (ilma automaatse lingita kasutajalehele)',
'tog-externaleditor'          => 'Kasuta vaikimisi välist redaktorit',
'tog-externaldiff'            => 'Kasuta vaikimisi välist võrdlusvahendit (ainult ekspertidele, tarvilikud on kasutaja arvuti eriseadistused)',
'tog-showjumplinks'           => 'Kuva lehekülje ülaservas "mine"-lingid.',
'tog-uselivepreview'          => 'Kasuta elavat eelvaadet (nõutav JavaScript) (testimisel)',
'tog-forceeditsummary'        => 'Nõua redigeerimisel resümee välja täitmist',
'tog-watchlisthideown'        => 'Peida minu redaktsioonid jälgimisloendist',
'tog-watchlisthidebots'       => 'Peida robotid jälgimisloendist',
'tog-watchlisthideminor'      => 'Peida pisiparandused jälgimisloendist',
'tog-watchlisthideliu'        => 'Peida sisselogitud kasutajate muudatused jälgimisloendist',
'tog-watchlisthideanons'      => 'Peida anonüümsete kasutajate muudatused jälgimisloendist',
'tog-watchlisthidepatrolled'  => 'Peida kontrollitud muudatused jälgimisloendist',
'tog-ccmeonemails'            => 'Saada mulle koopiad e-mailidest, mida ma teistele kasutajatele saadan',
'tog-diffonly'                => 'Ära näita erinevuste vaate all lehe sisu',
'tog-showhiddencats'          => 'Näita peidetud kategooriaid',
'tog-norollbackdiff'          => 'Ära näita erinevusi pärast tühistamist',

'underline-always'  => 'Alati',
'underline-never'   => 'Mitte kunagi',
'underline-default' => 'Brauseri vaikeväärtus',

# Font style option in Special:Preferences
'editfont-style'     => 'Redigeerimisala kirjatüüp:',
'editfont-default'   => 'Veebilehitseja vaikesäte',
'editfont-monospace' => 'Püsisammuga font',
'editfont-sansserif' => 'Seriifideta kiri',
'editfont-serif'     => 'Seriifidega kiri',

# Dates
'sunday'        => 'pühapäev',
'monday'        => 'esmaspäev',
'tuesday'       => 'teisipäev',
'wednesday'     => 'kolmapäev',
'thursday'      => 'neljapäev',
'friday'        => 'reede',
'saturday'      => 'laupäev',
'sun'           => 'P',
'mon'           => 'E',
'tue'           => 'T',
'wed'           => 'K',
'thu'           => 'N',
'fri'           => 'R',
'sat'           => 'L',
'january'       => 'jaanuar',
'february'      => 'veebruar',
'march'         => 'märts',
'april'         => 'aprill',
'may_long'      => 'mai',
'june'          => 'juuni',
'july'          => 'juuli',
'august'        => 'august',
'september'     => 'september',
'october'       => 'oktoober',
'november'      => 'november',
'december'      => 'detsember',
'january-gen'   => 'jaanuari',
'february-gen'  => 'veebruari',
'march-gen'     => 'märtsi',
'april-gen'     => 'aprilli',
'may-gen'       => 'mai',
'june-gen'      => 'juuni',
'july-gen'      => 'juuli',
'august-gen'    => 'augusti',
'september-gen' => 'septembri',
'october-gen'   => 'oktoobri',
'november-gen'  => 'novembri',
'december-gen'  => 'detsembri',
'jan'           => 'jaan',
'feb'           => 'veebr',
'mar'           => 'märts',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'juuni',
'jul'           => 'juuli',
'aug'           => 'aug',
'sep'           => 'sept',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dets',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategooria|Kategooriad}}',
'category_header'                => 'Leheküljed kategoorias "$1"',
'subcategories'                  => 'Allkategooriad',
'category-media-header'          => 'Meediafailid kategooriast "$1"',
'category-empty'                 => "''Selles kategoorias pole ühtegi artiklit ega meediafaili.''",
'hidden-categories'              => '{{PLURAL:$1|Peidetud kategooria|Peidetud kategooriad}}',
'hidden-category-category'       => 'Peidetud kategooriad',
'category-subcat-count'          => '{{PLURAL:$2|Selles kategoorias on ainult järgmine allkategooria.|{{PLURAL:$1|Järgmine allkategooria|Järgmised $1 allkategooriat}} on selles kategoorias (kokku $2).}}',
'category-subcat-count-limited'  => '{{PLURAL:$1|Järgmine allkategooria|Järgmised $1 allkategooriat}} on selles kategoorias.',
'category-article-count'         => '{{PLURAL:$2|Antud kategoorias on ainult järgmine lehekülg.|{{PLURAL:$1|Järgmine lehekülg|Järgmised $1 lehekülge}} on selles kategoorias (kokku $2).}}',
'category-article-count-limited' => '{{PLURAL:$1|Järgmine lehekülg|Järgmised $1 lehekülge}} on selles kategoorias.',
'category-file-count'            => '{{PLURAL:$2|Selles kategoorias on ainult järgmine fail.|{{PLURAL:$1|Järgmine fail |Järgmised $1 faili}} on selles kategoorias (kokku $2).}}',
'category-file-count-limited'    => '{{PLURAL:$1|Järgmine fail|Järgmised $1 faili}} on selles kategoorias.',
'listingcontinuesabbrev'         => 'jätk',

'mainpagetext'      => "<big>'''MediaWiki tarkvara on edukalt paigaldatud.'''</big>",
'mainpagedocfooter' => 'Juhiste saamiseks kasutamise ning konfigureerimise kohta vaata palun inglisekeelset [http://meta.wikimedia.org/wiki/MediaWiki_localisation dokumentatsiooni liidese kohaldamisest]
ning [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide kasutusjuhendit].',

'about'         => 'Tiitelandmed',
'article'       => 'artikkel',
'newwindow'     => '(avaneb uues aknas)',
'cancel'        => 'Tühista',
'moredotdotdot' => 'Veel...',
'mypage'        => 'Minu lehekülg',
'mytalk'        => 'Arutelu',
'anontalk'      => 'Arutelu selle IP jaoks',
'navigation'    => 'Navigeerimine',
'and'           => '&#32;ja',

# Cologne Blue skin
'qbfind'         => 'Otsi',
'qbbrowse'       => 'Sirvi',
'qbedit'         => 'Redigeeri',
'qbpageoptions'  => 'Lehekülje suvandid',
'qbpageinfo'     => 'Lehekülje andmed',
'qbmyoptions'    => 'Minu suvandid',
'qbspecialpages' => 'Erileheküljed',
'faq'            => 'KKK',
'faqpage'        => 'Project:KKK',

# Vector skin
'vector-action-addsection'   => 'Lisa teema',
'vector-action-delete'       => 'Kustuta',
'vector-action-move'         => 'Teisalda',
'vector-action-protect'      => 'Kaitse',
'vector-action-undelete'     => 'Taasta',
'vector-action-unprotect'    => 'Tühista kaitse',
'vector-namespace-category'  => 'Kategooria',
'vector-namespace-help'      => 'Abilehekülg',
'vector-namespace-image'     => 'Fail',
'vector-namespace-main'      => 'Artikkel',
'vector-namespace-media'     => 'Meedialeht',
'vector-namespace-mediawiki' => 'Sõnum',
'vector-namespace-project'   => 'Projektileht',
'vector-namespace-special'   => 'Erileht',
'vector-namespace-talk'      => 'Arutelu',
'vector-namespace-template'  => 'Mall',
'vector-namespace-user'      => 'Kasutaja leht',
'vector-view-create'         => 'Loo',
'vector-view-edit'           => 'Toimeta',
'vector-view-history'        => 'Näita ajalugu',
'vector-view-view'           => 'Loe',
'vector-view-viewsource'     => 'Vaata lähteteksti',
'actions'                    => 'Toimingud',
'namespaces'                 => 'Nimeruumid',
'variants'                   => 'Variandid',

# Metadata in edit box
'metadata_help' => 'Metaandmed:',

'errorpagetitle'    => 'Viga',
'returnto'          => 'Naase lehele $1',
'tagline'           => 'Allikas: {{SITENAME}}',
'help'              => 'Juhend',
'search'            => 'Otsi',
'searchbutton'      => 'Otsi',
'go'                => 'Mine',
'searcharticle'     => 'Mine',
'history'           => 'Artikli ajalugu',
'history_short'     => 'Ajalugu',
'updatedmarker'     => 'uuendatud pärast viimast külastust',
'info_short'        => 'Info',
'printableversion'  => 'Prinditav versioon',
'permalink'         => 'Püsilink',
'print'             => 'Prindi',
'edit'              => 'redigeeri',
'create'            => 'Loo',
'editthispage'      => 'Redigeeri seda artiklit',
'create-this-page'  => 'Loo see lehekülg',
'delete'            => 'kustuta',
'deletethispage'    => 'Kustuta see artikkel',
'undelete_short'    => 'Taasta {{PLURAL:$1|üks muudatus|$1 muudatust}}',
'protect'           => 'Kaitse',
'protect_change'    => 'muuda',
'protectthispage'   => 'Kaitse seda artiklit',
'unprotect'         => 'Ära kaitse',
'unprotectthispage' => 'Ära kaitse seda artiklit',
'newpage'           => 'Uus artikkel',
'talkpage'          => 'Selle artikli arutelu',
'talkpagelinktext'  => 'arutelu',
'specialpage'       => 'Erilehekülg',
'personaltools'     => 'Personaalsed tööriistad',
'postcomment'       => 'Uus alalõik',
'articlepage'       => 'Artiklilehekülg',
'talk'              => 'Arutelu',
'views'             => 'vaatamisi',
'toolbox'           => 'Tööriistad',
'userpage'          => 'Kasutajalehekülg',
'projectpage'       => 'Vaata projektilehekülge',
'imagepage'         => 'Vaata faililehekülge',
'mediawikipage'     => 'Vaata sõnumite lehekülge',
'templatepage'      => 'Mallilehekülg',
'viewhelppage'      => 'Vaata abilehekülge',
'categorypage'      => 'Kategoorialehekülg',
'viewtalkpage'      => 'Arutelulehekülg',
'otherlanguages'    => 'Teistes keeltes',
'redirectedfrom'    => '(Ümber suunatud artiklist $1)',
'redirectpagesub'   => 'Ümbersuunamisleht',
'lastmodifiedat'    => 'Viimane muutmine: $2, $1',
'viewcount'         => 'Seda lehekülge on külastatud {{PLURAL:$1|üks kord|$1 korda}}.',
'protectedpage'     => 'Kaitstud lehekülg',
'jumpto'            => 'Mine:',
'jumptonavigation'  => 'navigeerimiskast',
'jumptosearch'      => 'otsi',
'view-pool-error'   => 'Serverid on hetkel üle koormatud.
Liiga palju kasutajaid üritab korraga seda lehte vaadata.
Palun oota hetk enne kui uuesti proovid.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} tiitelandmed',
'aboutpage'            => 'Project:Tiitelandmed',
'copyright'            => 'Kogu tekst on kasutatav litsentsi $1 tingimustel.',
'copyrightpagename'    => '{{SITENAME}} ja autoriõigused',
'copyrightpage'        => '{{ns:project}}:Autoriõigused',
'currentevents'        => 'Sündmused maailmas',
'currentevents-url'    => 'Project:Sündmused maailmas',
'disclaimers'          => 'Hoiatused',
'disclaimerpage'       => 'Project:Hoiatused',
'edithelp'             => 'Redigeerimisjuhend',
'edithelppage'         => 'Help:Kuidas_lehte_redigeerida',
'helppage'             => 'Help:Juhend',
'mainpage'             => 'Esileht',
'mainpage-description' => 'Esileht',
'policy-url'           => 'Project:Reeglid',
'portal'               => 'Kogukonnavärav',
'portal-url'           => 'Project:Kogukonnavärav',
'privacy'              => 'Privaatsus',
'privacypage'          => 'Project:Privaatsus',

'badaccess'        => 'Õigus puudub',
'badaccess-group0' => 'Sul ei ole õigust läbi viia toimingut, mida üritasid.',
'badaccess-groups' => 'Tegevus, mida üritasid, on piiratud kasutajatele {{PLURAL:$2|rühmas|ühes neist rühmadest}}: $1.',

'versionrequired'     => 'Nõutav MediaWiki versioon $1',
'versionrequiredtext' => 'Selle lehe kasutamiseks on nõutav MediaWiki versioon $1.
Vaata [[Special:Version|versiooni lehekülge]].',

'ok'                      => 'Sobib',
'retrievedfrom'           => 'Välja otsitud andmebaasist "$1"',
'youhavenewmessages'      => 'Teile on $1 ($2).',
'newmessageslink'         => 'uusi sõnumeid',
'newmessagesdifflink'     => 'viimane muudatus',
'youhavenewmessagesmulti' => 'Sulle on uusi sõnumeid $1',
'editsection'             => 'redigeeri',
'editsection-brackets'    => '[$1]',
'editold'                 => 'redigeeri',
'viewsourceold'           => 'vaata lähteteksti',
'editlink'                => 'redigeeri',
'viewsourcelink'          => 'vaata lähteteksti',
'editsectionhint'         => 'Redigeeri alaosa $1',
'toc'                     => 'Sisukord',
'showtoc'                 => 'näita',
'hidetoc'                 => 'peida',
'thisisdeleted'           => 'Vaata või taasta $1?',
'viewdeleted'             => 'Vaata lehekülge $1?',
'restorelink'             => '{{PLURAL:$1|üks kustutatud versioon|$1 kustutatud versiooni}}',
'feedlinks'               => 'Sööde:',
'site-rss-feed'           => '$1 RSS-toide',
'site-atom-feed'          => '$1 Atom-toide',
'page-rss-feed'           => '"$1" RSS-toide',
'page-atom-feed'          => '"$1" Atom-toide',
'red-link-title'          => '$1 (pole veel kirjutatud)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikkel',
'nstab-user'      => 'Kasutaja leht',
'nstab-media'     => 'Meedia',
'nstab-special'   => 'Eri',
'nstab-project'   => 'Abileht',
'nstab-image'     => 'Pilt',
'nstab-mediawiki' => 'Sõnum',
'nstab-template'  => 'Mall',
'nstab-help'      => 'Juhend',
'nstab-category'  => 'Kategooria',

# Main script and global functions
'nosuchaction'      => 'Sellist toimingut pole.',
'nosuchactiontext'  => 'Viki ei tunne internetiaadressile vastavat tegevust.
Võimalik, et sa sisestasid aadressi valesti või kasutasid vigast linki.
Samuti ei ole välistatud, et tarkvaras, mida {{SITENAME}} kasutatab, on viga.',
'nosuchspecialpage' => 'Sellist erilehekülge pole.',
'nospecialpagetext' => 'Viki ei tunne sellist erilehekülge.',

# General errors
'error'                => 'Viga',
'databaseerror'        => 'Andmebaasi viga',
'dberrortext'          => 'Andmebaasipäringus oli süntaksiviga.
Selle võis tingida tarkvaraviga.
Viimane andmebaasipäring oli:
<blockquote><tt>$1</tt></blockquote>
ja see kutsuti funktsioonist "<tt>$2</tt>".
$5 tagastas veateate "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Andmebaasipäringus oli süntakiviga.
Viimane andmebaasipäring oli:
"$1"
ja see kutsuti funktsioonist "$2".
$5 tagastas veateate "$3: $4".',
'laggedslavemode'      => 'Hoiatus: Leheküljel võivad puududa viimased uuendused.',
'readonly'             => 'Andmebaas on hetkel kirjutuskaitse all',
'enterlockreason'      => 'Sisesta lukustamise põhjus ning juurdepääsu taastamise ligikaudne aeg',
'readonlytext'         => 'Andmebaas on praegu kirjutuskaitse all, tõenäoliselt andmebaasi harjumuslikuks hoolduseks, mille lõppedes tavaline olukord taastub.
Ülem, kes selle kaitse alla võttis, andis järgmise selgituse:
<p>$1',
'missing-article'      => 'Andmebaas ei leidnud küsitud lehekülje "$1" $2 teksti.

Põhjuseks võib olla võrdlus- või ajaloolink kustutatud leheküljele.

Kui tegemist ei ole nimetatud olukorraga, võib tegu olla ka süsteemi veaga.
Sellisel juhul tuleks teavitada [[Special:ListUsers/sysop|ülemat]], edastades talle ka käesoleva lehe internetiaadressi.',
'missingarticle-rev'   => '(redaktsioon: $1)',
'missingarticle-diff'  => '(redaktsioonid: $1, $2)',
'internalerror'        => 'Sisemine viga',
'internalerror_info'   => 'Sisemine viga: $1',
'fileappenderror'      => 'Faili "$1" ei saanud lisada failile "$2".',
'filecopyerror'        => 'Ei saanud faili "$1" kopeerida nimega "$2".',
'filerenameerror'      => 'Ei saanud faili "$1" failiks "$2" ümber nimetada.',
'filedeleteerror'      => 'Faili nimega "$1" ei ole võimalik kustutada.',
'directorycreateerror' => 'Ei suuda luua kausta "$1".',
'filenotfound'         => 'Faili nimega "$1" ei leitud.',
'fileexistserror'      => 'Kirjutamine faili "$1" ebaõnnestus: fail on juba olemas',
'unexpected'           => 'Ootamatu väärtus: "$1"="$2".',
'formerror'            => 'Viga: vormi ei saanud salvestada',
'badarticleerror'      => 'Seda toimingut ei saa sellel leheküljel sooritada.',
'cannotdelete'         => 'Seda lehekülge või pilti ei ole võimalik kustutada. (Võib-olla keegi teine juba kustutas selle.)',
'badtitle'             => 'Vigane pealkiri',
'badtitletext'         => 'Soovitud leheküljepealkiri oli vigane, tühi või teisest keeleversioonist või vikist valesti lingitud.
See võib sisaldada ühte või enamat märki, mida ei saa pealkirjades kasutada.',
'perfcached'           => 'Järgnevad andmed on puhverdatud ja ei pruugi olla kõige värskemad:',
'perfcachedts'         => 'Järgmised andmed on vahemälus. Viimase uuendamise daatum on $1.',
'querypage-no-updates' => 'Lehekülje uuendamine ei ole hetkel lubatud ning andmeid ei värskendata.',
'wrong_wfQuery_params' => 'Valed parameeterid funktsioonile wfQuery()<br />
Funktsioon: $1<br />
Päring: $2',
'viewsource'           => 'Vaata lähteteksti',
'viewsourcefor'        => '$1',
'actionthrottled'      => 'Toiming nurjus',
'protectedpagetext'    => 'See lehekülg on lukustatud, et muudatusi ei tehtaks.',
'viewsourcetext'       => 'Võite vaadata ja kopeerida lehekülje algteksti:',
'protectedinterface'   => 'Sellel leheküljel on tarkvara kasutajaliidese tekst. Kuritahtliku muutmise vältimiseks on lehekülg lukustatud.',
'editinginterface'     => "'''Hoiatus:''' Te redigeerite tarkvara kasutajaliidese tekstiga lehekülge. Muudatused siin mõjutavad kõikide kasutajate kasutajaliidest. Tõlkijad, palun kaaluge MediaWiki tõlkimisprojekti – [http://translatewiki.net/wiki/Main_Page?setlang=et translatewiki.net] kasutamist.",
'sqlhidden'            => '(SQL päring peidetud)',
'cascadeprotected'     => 'See lehekülg on muutmise eest kaitstud, sest see on osa {{PLURAL:$1|järgmisest leheküljest|järgmistest lehekülgedest}}, mis on kaskaadkaitse all:
$2',
'namespaceprotected'   => "Teil ei ole õigusi redigeerida lehekülgi '''$1''' nimeruumis.",
'customcssjsprotected' => 'Sul pole õigust antud lehte muuta, kuna see sisaldab teise kasutaja isiklikke seadeid.',
'ns-specialprotected'  => 'Erilehekülgi ei saa redigeerida.',
'titleprotected'       => "Kasutaja [[User:$1|$1]] on selle pealkirjaga lehe loomise keelanud esitades järgmise põhjenduse: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Viga konfiguratsioonis: tundmatu viirusetõrje: ''$1''",
'virus-scanfailed'     => 'skaneerimine ebaõnnestus (veakood $1)',
'virus-unknownscanner' => 'tundmatu viirusetõrje:',

# Login and logout pages
'logouttext'                 => "'''Te olete nüüd välja loginud.'''

Te võite jätkata {{SITENAME}} kasutamist anonüümselt, aga ka sama või mõne teise kasutajana uuesti [[Special:UserLogin|sisse logida]].",
'welcomecreation'            => '<h2>Tere tulemast, $1!</h2><p>Teie konto on loodud. Ärge unustage seada oma eelistusi.',
'yourname'                   => 'Teie kasutajanimi',
'yourpassword'               => 'Teie parool',
'yourpasswordagain'          => 'Sisestage parool uuesti',
'remembermypassword'         => 'Parooli meeldejätmine tulevasteks seanssideks.',
'yourdomainname'             => 'Teie domeen:',
'login'                      => 'Logi sisse',
'nav-login-createaccount'    => 'Logi sisse / registreeru kasutajaks',
'loginprompt'                => 'Teie brauser peab nõustuma küpsistega, et saaksite {{SITENAME}} lehele sisse logida.',
'userlogin'                  => 'Logi sisse / registreeru kasutajaks',
'logout'                     => 'Logi välja',
'userlogout'                 => 'Logi välja',
'notloggedin'                => 'Te pole sisse loginud',
'nologin'                    => 'Sul pole kontot? $1.',
'nologinlink'                => 'Registreeru siin',
'createaccount'              => 'Loo uus konto',
'gotaccount'                 => 'Kui sul on juba konto olemas, siis $1.',
'gotaccountlink'             => 'logi sisse',
'createaccountmail'          => 'meili teel',
'badretype'                  => 'Sisestatud paroolid ei lange kokku.',
'userexists'                 => 'Sisestatud kasutajanimi on juba kasutusel.
Palun valige uus nimi.',
'loginerror'                 => 'Viga sisselogimisel',
'nocookiesnew'               => 'Kasutajakonto loodi, aga sa ei ole sisse logitud, sest {{SITENAME}} kasutab kasutajate tuvastamisel küpsiseid. Sinu brauseris on küpsised keelatud. Palun sea küpsised lubatuks ja logi siis oma vastse kasutajanime ning parooliga sisse.',
'nocookieslogin'             => '{{SITENAME}} kasutab kasutajate tuvastamisel küpsiseid. Sinu brauseris on küpsised keelatud. Palun sea küpsised lubatuks ja proovi siis uuesti.',
'noname'                     => 'Sa ei sisestanud kasutajanime lubataval kujul.',
'loginsuccesstitle'          => 'Sisselogimine õnnestus',
'loginsuccess'               => 'Te olete sisse loginud. Teie kasutajanimi on "$1".',
'nosuchuser'                 => 'Kasutajat "$1" ei ole olemas.
Kasutajanimed on tõstutundlikud.
Kontrollige kirjapilti või [[Special:UserLogin/signup|looge uus kasutajakonto]].',
'nosuchusershort'            => 'Kasutajat nimega "<nowiki>$1</nowiki>" ei ole olemas. Kontrollige kirjapilti.',
'nouserspecified'            => 'Kasutajanimi puudub.',
'wrongpassword'              => 'Vale parool. Proovige uuesti.',
'wrongpasswordempty'         => 'Parool jäi sisestamata. Palun proovi uuesti.',
'passwordtooshort'           => 'Parool on liiga lühike.
See peab koosnema vähemalt {{PLURAL:$1|ühest|$1}} tähemärgist.',
'password-name-match'        => 'Parool peab kasutajanimest erinema.',
'mailmypassword'             => 'Saada mulle meili teel uus parool',
'passwordremindertitle'      => '{{SITENAME}} - unustatud salasõna',
'passwordremindertext'       => 'Keegi (tõenäoliselt Teie ise, IP-aadressilt $1), palus, et me saadaksime Teile uue parooli
portaali {{SITENAME}} sisselogimiseks ($4). Kasutaja "$2" ajutiseks paroolis seati "$3".
Kui see oligi Teie soov, peaksite sisse logima ja uue parooli valima. Ajutine parool aegub {{PLURAL:$5|ühe päeva|$5 päeva}} pärast.

Kui parooli vahetamise palve lähetas Teie nimel keegi teine või kui Teile meenus vana parool ja Te ei soovi seda enam muuta, võite käesolevat teadet lihtsalt ignoreerida ning jätkata endise parooli kasutamist.',
'noemail'                    => 'Kasutaja "$1" meiliaadressi meil kahjuks pole.',
'passwordsent'               => 'Uus parool on saadetud kasutaja "$1" registreeritud meiliaadressil.
Pärast parooli saamist logige palun sisse.',
'blocked-mailpassword'       => 'Sinu IP-aadressi jaoks on toimetamine blokeeritud, seetõttu ei saa sa kasutada ka parooli meeldetuletamise funktsiooni.',
'eauthentsent'               => 'Sisestatud e-posti aadressile on saadetud kinnituse e-kiri.
Enne kui su kontole ükskõik milline muu e-kiri saadetakse, pead sa e-kirjas olevat juhist järgides kinnitama, et konto on tõepoolest sinu.',
'throttled-mailpassword'     => 'Parooli meeldetuletus lähetatud viimase {{PLURAL:$1|tunni|$1 tunni}} jooksul.
Väärtarvitamise vältimiseks saadetakse {{PLURAL:$1|tunni|$1 tunni}} jooksul ainult üks meeldetuletus.',
'mailerror'                  => 'Viga kirja saatmisel: $1',
'acct_creation_throttle_hit' => 'Selle viki külastajad, kes kasutavad sinu IP-aadressi, on viimase ööpäeva jooksul loonud {{PLURAL:$1|ühe konto|$1 kontot}}, mis on selles ajavahemikus ülemmääraks.
Seetõttu ei saa seda IP-aadressi kasutades hetkel rohkem kontosid luua.',
'emailauthenticated'         => 'Sinu e-posti aadress kinnitati: $2 kell $3.',
'emailnotauthenticated'      => 'Sinu e-posti aadress <strong>pole veel kinnitatud</strong>. E-posti kinnitamata aadressile ei saadeta.',
'noemailprefs'               => 'Järgnevate võimaluste toimimiseks on vaja sisestada e-posti aadress.',
'emailconfirmlink'           => 'Kinnita oma e-posti aadress',
'invalidemailaddress'        => 'E-aadress ei ole aktsepteeritav, sest see on vigaselt kirjutatud.
Ole hea ja anna õige e-aadress või jäta lahter tühjaks.',
'accountcreated'             => 'Konto loodud',
'accountcreatedtext'         => 'Kasutajakonto kasutajatunnusele $1 loodud.',
'createaccount-title'        => 'Konto loomine portaali {{SITENAME}}',
'createaccount-text'         => 'Keegi on loonud {{GRAMMAR:illative|{{SITENAME}}}} ($4) sinu meiliaadressile vastava kasutajatunnuse "$2". Parooliks seati "$3". Logi sisse ja muuda oma parool.

Kui kasutajakonto loomine on eksitus, võid käesolevat sõnumit lihtsalt ignoreerida.',
'login-throttled'            => 'Sa oled lühikese aja jooksul teinud liiga palju äpardunud katseid selle konto parooli sisestada.
Palun pea nüüd pisut vahet.',
'loginlanguagelabel'         => 'Keel: $1',

# Password reset dialog
'resetpass'                 => 'Muuda parooli',
'resetpass_announce'        => 'Sa logisid sisse ajutise e-maili koodiga. 
Et sisselogimine lõpetada, pead uue parooli siia trükkima:',
'resetpass_text'            => '<!-- Lisa tekst siia -->',
'resetpass_header'          => 'Muuda konto parooli',
'oldpassword'               => 'Vana parool',
'newpassword'               => 'Uus parool',
'retypenew'                 => 'Sisestage uus parool uuesti',
'resetpass_submit'          => 'Sisesta parool ja logi sisse',
'resetpass_success'         => 'Sinu parool on edukalt muudetud! Sisselogimine...',
'resetpass_forbidden'       => 'Paroole ei saa muuta',
'resetpass-no-info'         => 'Pead olema sisselogitud, et sellele lehele pääseda.',
'resetpass-submit-loggedin' => 'Muuda parool',
'resetpass-wrong-oldpass'   => 'Vigane ajutine või praegune salasõna.
Võib-olla oled juba edukalt muudnud oma salasõna või taotlenud uut ajutist salasõna.',
'resetpass-temp-password'   => 'Ajutine parool:',

# Edit page toolbar
'bold_sample'     => 'Rasvane kiri',
'bold_tip'        => 'Rasvane kiri',
'italic_sample'   => 'Kaldkiri',
'italic_tip'      => 'Kaldkiri',
'link_sample'     => 'Lingitav pealkiri',
'link_tip'        => 'Siselink',
'extlink_sample'  => 'http://www.example.com Lingi nimi',
'extlink_tip'     => 'Välislink (ärge unustage kasutada http:// eesliidet)',
'headline_sample' => 'Pealkiri',
'headline_tip'    => '2. taseme pealkiri',
'math_sample'     => 'Sisesta valem siia',
'math_tip'        => 'Matemaatiline valem (LaTeX)',
'nowiki_sample'   => 'Sisesta vormindamata tekst',
'nowiki_tip'      => 'Ignoreeri viki vormindust',
'image_sample'    => 'Näidis.jpg',
'image_tip'       => 'Pilt',
'media_sample'    => 'Näidis.ogg',
'media_tip'       => 'Link failile',
'sig_tip'         => 'Sinu allkiri ajatempliga',
'hr_tip'          => 'Horisontaalkriips (kasuta säästlikult)',

# Edit pages
'summary'                          => 'Resümee:',
'subject'                          => 'Pealkiri:',
'minoredit'                        => 'See on pisiparandus',
'watchthis'                        => 'Jälgi seda artiklit',
'savearticle'                      => 'Salvesta',
'preview'                          => 'Eelvaade',
'showpreview'                      => 'Näita eelvaadet',
'showlivepreview'                  => 'Näita eelvaadet',
'showdiff'                         => 'Näita muudatusi',
'anoneditwarning'                  => 'Te ei ole sisse logitud. Selle lehe redigeerimislogisse salvestatakse Teie IP-aadress.',
'missingsummary'                   => "'''Meeldetuletus:''' Sa ei ole lisanud muudatuse resümeed.
Kui vajutad uuesti salvestamise nupule, salvestatakse muudatus ilma resümeeta.",
'missingcommenttext'               => 'Palun sisesta siit allapoole kommentaar.',
'missingcommentheader'             => "'''Meeldetuletus:''' Sa ei ole kirjutanud kommentaarile teemat/pealkirja.
Kui vajutad uuesti <em>Salvesta</em>, siis salvestatakse kommentaar ilma teema/pealkirjata.",
'summary-preview'                  => 'Resümee eelvaade:',
'subject-preview'                  => 'Alaosa pealkirja eelvaade:',
'blockedtitle'                     => 'Kasutaja on blokeeritud',
'blockedtext'                      => "<big>'''Teie kasutajanimi või IP-aadress on blokeeritud.'''</big>

Blokeeris $1.
Tema põhjendus on järgmine: ''$2''.

* Blokeeringu algus: $8
* Blokeeringu lõpp: $6
* Sooviti blokeerida: $7

Küsimuse arutamiseks võite pöörduda kasutaja $1 või mõne teise [[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pange tähele, et Te ei saa kasutajale teadet saata, kui Te pole kinnitanud oma [[Special:Preferences|eelistuste lehel]] kehtivat e-posti aadressi.

Teie praegune IP-aadress on $3 ning blokeeringu number on #$5. Lisage need andmed kõigile järelepärimistele, mida kavatsete teha.",
'autoblockedtext'                  => "Teie IP-aadress blokeeriti automaatselt, sest seda kasutas teine kasutaja, kelle $1 blokeeris.
Põhjendus on järgmine:

:''$2''

* Blokeeringu algus: $8
* Blokeeringu lõpp: $6
* Sooviti blokeerida: $7

Küsimuse arutamiseks võite pöörduda kasutaja $1 või mõne teise [[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pange tähele, et Te ei saa teisele kasutajale teadet saata, kui Te pole kinnitanud oma [[Special:Preferences|eelistuste lehel]] kehtivat e-posti aadressi ega ole selle kasutamisest blokeeritud.

Teie praegune IP on $3 ning blokeeringu number on #$5. Lisage need andmed kõigile järelpärimistele, mida kavatsete teha.",
'blockednoreason'                  => 'põhjendust ei ole kirja pandud',
'blockedoriginalsource'            => "'''$1''' allikas on näidatud allpool:",
'blockededitsource'                => "Sinu muudatused leheküljele '''$1''':",
'whitelistedittitle'               => 'Redigeerimiseks tuleb sisse logida',
'whitelistedittext'                => 'Lehekülgede toimetamiseks peate $1.',
'confirmedittext'                  => 'Lehekülgi ei saa toimetada enne e-aadressi kinnitamist. Võid teha kinnitamise [[Special:Preferences|eelistuste lehel]].',
'nosuchsectiontitle'               => 'Sellist alaosa pole',
'nosuchsectiontext'                => 'Sa üritasid redigeerida alaosa, mida ei ole. Kuna alaosa $1 pole, ei saa redaktsiooni salvestada.',
'loginreqtitle'                    => 'Vajalik on sisselogimine',
'loginreqlink'                     => 'sisse logima',
'loginreqpagetext'                 => 'Lehekülgede vaatamiseks peate $1.',
'accmailtitle'                     => 'Parool saadetud.',
'accmailtext'                      => "Kasutajale '$1' genereeritud juhuslik parool saadeti aadressile $2.

Seda parooli on võimalik muuta ''[[Special:ChangePassword|parooli muutmise lehel]]'' peale uuele kontole sisse logimist.",
'newarticle'                       => '(Uus)',
'newarticletext'                   => "Sellise pealkirjaga lehekülge ei ole veel loodud. Lehekülje loomiseks sisestage lehe tekst alljärgnevasse tekstikasti ja salvestage (lisainfo saamiseks vaadake [[{{MediaWiki:Helppage}}|juhendit]]).

Kui sattusite siia kogemata, klõpsake lihtsalt brauseri ''tagasi''-nupule.",
'anontalkpagetext'                 => "---- ''See on arutelulehekülg anonüümse kasutaja jaoks, kes ei ole loonud kontot või ei kasuta seda. Sellepärast tuleb meil kasutaja identifitseerimiseks kasutada tema IP-aadressi.
Sellisel IP-aadressilt võib portaali kasutada mitu inimest.
Kui oled osutatud IP kasutaja ning leiad, et siinsed kommentaarid ei puutu kuidagi sinusse, siis palun [[Special:UserLogin|loo konto või logi sisse]], et sind edaspidi teiste anonüümsete kasutajatega segi ei aetaks.''",
'noarticletext'                    => 'Käesoleval leheküljel hetkel teksti ei ole.
Võid [[Special:Search/{{PAGENAME}}|otsida pealkirjaks olevat fraasi]] teistelt lehtedelt,
<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} uurida asjassepuutuvaid logisid] või [{{fullurl:{{FULLPAGENAME}}|action=edit}} puuduva lehekülje ise luua]</span>.',
'userpage-userdoesnotexist'        => 'Kasutajakontot "$1" pole olemas.
Palun mõtle järele, kas soovid seda lehte luua või muuta.',
'clearyourcache'                   => "'''Märkus:''' Pärast salvestamist pead sa muudatuste nägemiseks oma brauseri puhvri tühjendama: '''Mozilla:''' ''ctrl-shift-r'', '''IE:''' ''ctrl-f5'', '''Safari:''' ''cmd-shift-r'', '''Konqueror''' ''f5''.",
'usercssyoucanpreview'             => "'''Vihje:''' Kasuta nuppu 'Näita eelvaadet' oma uue css/js testimiseks enne salvestamist.",
'userjsyoucanpreview'              => "'''Vihje:''' Kasuta nuppu 'Näita eelvaadet' oma uue css/js testimiseks enne salvestamist.",
'usercsspreview'                   => "'''Ärge unustage, et seda versiooni teie isiklikust stiililehest pole veel salvestatud!'''",
'userjspreview'                    => "'''Ärge unustage, et see versioon teie isiklikust javascriptist on alles salvestamata!'''",
'userinvalidcssjstitle'            => "'''Hoiatus:''' Kujundust nimega \"\$1\" ei ole.
Ära unusta, et kasutaja isiklikud .css- ja .js-lehed kasutavad väiketähega algavaid nimesid, näiteks  {{ns:user}}:Juhan Julm/monobook.css ja mitte {{ns:user}}:Juhan Julm/Monobook.css.",
'updated'                          => '(Värskendatud)',
'note'                             => "'''Meeldetuletus:'''",
'previewnote'                      => "'''Ärge unustage, et see versioon ei ole veel salvestatud!'''",
'previewconflict'                  => 'See eelvaade näitab, kuidas ülemises toimetuskastis olev tekst hakkab välja nägema, kui otsustate salvestada.',
'session_fail_preview'             => "'''Vabandust! Meil ei õnnestunud seansiandmete kaotuse tõttu sinu muudatust töödelda.'''
Palun proovi uuesti.
Kui see ikka ei tööta, proovi [[Special:UserLogout|välja]] ja tagasi sisse logida.",
'session_fail_preview_html'        => "'''Vabandust! Meil ei õnnestunud seansiandmete kaotuse tõttu sinu muudatust töödelda.'''

''Kuna võrgukohas {{SITENAME}} on toor-HTML lubatud, on eelvaade JavaScripti rünnakute vastase ettevaatusabinõuna peidetud.''

'''Kui see on õigustatud redigeerimiskatse, proovi palun uuesti.'''
Kui see ikka ei tööta, proovi [[Special:UserLogout|välja]] ja tagasi sisse logida.",
'editing'                          => 'Redigeerimisel on $1',
'editingsection'                   => 'Redigeerimisel on osa leheküljest $1',
'editingcomment'                   => 'Muutmisel on $1 (uus alaosa)',
'editconflict'                     => 'Redigeerimiskonflikt: $1',
'explainconflict'                  => 'Keegi teine on muutnud seda lehekülge pärast seda, kui Teie seda redigeerima hakkasite.
Ülemine toimetuskast sisaldab teksti viimast versiooni.
Teie muudatused on alumises kastis.
Teil tuleb need viimasesse versiooni üle viia.
Kui Te klõpsate nupule
 "Salvesta", siis salvestub <b>ainult</b> ülemises toimetuskastis olev tekst.<br />',
'yourtext'                         => 'Teie tekst',
'storedversion'                    => 'Salvestatud redaktsioon',
'nonunicodebrowser'                => "'''HOIATUS: Sinu brauser ei toeta unikoodi.'''
Probleemist möödahiilimiseks, selleks et saaksid lehekülgi turvaliselt redigeerida, näidatakse mitte-ASCII sümboleid toimetuskastis kuueteistkümnendsüsteemi koodidena.",
'editingold'                       => "'''ETTEVAATUST! Te redigeerite praegu selle lehekülje vana redaktsiooni.
Kui Te selle salvestate, siis lähevad kõik vahepealsed muudatused kaduma.'''",
'yourdiff'                         => 'Erinevused',
'copyrightwarning'                 => "Pidage silmas, et kogu teie kaastöö võrgukohale {{SITENAME}} loetakse avaldatuks litsentsi $2 all (vaata ka $1). Kui te ei soovi, et teie kirjutatut halastamatult redigeeritakse ja oma äranägemise järgi kasutatakse, siis ärge seda siia salvestage.<br />
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast.<br />
'''ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!'''",
'copyrightwarning2'                => "Pidage silmas teised kaastöölised võivad kogu võrgukohale {{SITENAME}} tehtud kaastööd muuta või eemaldada. Kui te ei soovi, et teie kirjutatut halastamatult redigeeritakse, siis ärge seda siia salvestage.<br />
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast (vaata ka $1).<br />
'''ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!'''",
'longpagewarning'                  => "'''HOIATUS: Selle lehekülje pikkus ületab $1 kilobaiti. Mõne brauseri puhul valmistab raskusi juba 32-le kilobaidile läheneva pikkusega lehekülgede redigeerimine. Palun kaaluge selle lehekülje sisu jaotamist lühemate lehekülgede vahel.'''",
'longpageerror'                    => "'''Viga: Lehekülje suurus on $1 kilobaiti. Lehekülge ei saa salvestada, kuna see on pikem kui maksimaalsed $2 kilobaiti.'''",
'readonlywarning'                  => "'''HOIATUS: Andmebaas on lukustatud hooldustöödeks, nii et praegu ei saa parandusi salvestada. Võite teksti hilisemaks kasutamiseks alles hoida tekstifailina.'''

Ülem, kes andmebaasi lukustas, andis järgmise selgituse: $1",
'protectedpagewarning'             => "'''HOIATUS: See lehekülg on lukustatud, nii et seda saavad redigeerida ainult ülema õigustega kasutajad.'''",
'semiprotectedpagewarning'         => "'''Märkus:''' See lehekülg on lukustatud nii, et üksnes registreeritud kasutajad saavad seda muuta.",
'titleprotectedwarning'            => "'''Hoiatus: See lehekülg on nii lukustatud, et selle loomiseks on tarvis [[Special:ListGroupRights|eriõigusi]].'''",
'templatesused'                    => 'Sellel lehel on kasutusel järgnevad mallid:',
'templatesusedpreview'             => 'Selles eelvaates kasutatakse järgmisi malle:',
'templatesusedsection'             => 'Siin rubriigis kasutatud mallid:',
'template-protected'               => '(kaitstud)',
'template-semiprotected'           => '(osaliselt kaitstud)',
'hiddencategories'                 => 'See lehekülg kuulub {{PLURAL:$1|1 peidetud kategooriasse|$1 peidetud kategooriasse}}:',
'nocreatetitle'                    => 'Lehekülje loomine piiratud',
'nocreatetext'                     => 'Võrgukohas {{SITENAME}} on piirangud uue lehekülje loomisel.
Te võite pöörduda tagasi ja toimetada olemasolevat lehekülge või [[Special:UserLogin|sisse logida või uue konto luua]].',
'nocreate-loggedin'                => 'Sul ei ole luba luua uusi lehekülgi.',
'permissionserrors'                => 'Viga õigustes',
'permissionserrorstext'            => 'Teil ei ole õigust seda teha {{PLURAL:$1|järgmisel põhjusel|järgmistel põhjustel}}:',
'permissionserrorstext-withaction' => 'Sul pole lubatud {{lcfirst:$2}} {{PLURAL:$1|järgneval põhjusel|järgnevatel põhjustel}}:',
'recreate-moveddeleted-warn'       => "'''Hoiatus: Te loote uuesti lehte, mis on varem kustutatud.'''

Kaaluge, kas lehe uuesti loomine on kohane.
Lehe eelnevad kustutamised ja teisaldamised:",
'moveddeleted-notice'              => 'See lehekülg on kustutatud.
Allpool on esitatud lehekülje kustutamis- ja teisaldamislogi.',
'log-fulllog'                      => 'Vaata kogu logi',
'edit-gone-missing'                => 'Polnud võimalik lehekülge uuendada.
Tundub, et see on kustutatud.',
'edit-conflict'                    => 'Redigeerimiskonflikt.',
'edit-no-change'                   => 'Sinu redigeerimist ignoreeriti, sest tekstile ei olnud tehtud muudatusi.',
'edit-already-exists'              => 'Ei saanud alustada uut lehekülge.
See on juba olemas.',

# Parser/template warnings
'post-expand-template-argument-category' => 'Malli vahele jäetud argumente sisaldavad leheküljed',
'parser-template-loop-warning'           => 'Mallid moodustavad tsükli: [[$1]]',

# "Undo" feature
'undo-success' => 'Selle redaktsiooni käigus tehtud muudatusi saab eemaldada. Palun kontrolli allolevat võrdlust veendumaks, et tahad need muudatused tõepoolest eemaldada. Seejärel saad lehekülje salvestada.',
'undo-norev'   => 'Muudatust ei saanud tühistada, kuna seda ei ole või see kustutati.',
'undo-summary' => 'Tühistati muudatus $1, mille tegi [[Special:Contributions/$2|$2]] ([[User talk:$2|arutelu]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ei saa kontot luua',
'cantcreateaccount-text' => "Kasutaja [[User:$3|$3]] on blokeerinud kasutajanime loomise sellelt IP-aadressilt ('''$1'''). 
Kasutaja $3 märkis põhjuseks ''$2''",

# History pages
'viewpagelogs'           => 'Vaata selle lehe logisid',
'nohistory'              => 'Sellel leheküljel ei ole eelmisi redaktsioone.',
'currentrev'             => 'Viimane redaktsioon',
'currentrev-asof'        => 'Viimane redaktsioon ($1)',
'revisionasof'           => 'Redaktsioon: $1',
'revision-info'          => 'Redaktsioon seisuga $1 kasutajalt $2',
'previousrevision'       => '←Vanem redaktsioon',
'nextrevision'           => 'Uuem redaktsioon→',
'currentrevisionlink'    => 'vaata viimast redaktsiooni',
'cur'                    => 'viim',
'next'                   => 'järg',
'last'                   => 'eel',
'page_first'             => 'esimene',
'page_last'              => 'viimane',
'histlegend'             => 'Märgi versioonid, mida tahad võrrelda ja vajuta võrdlemisnupule.
Legend: (viim) = erinevused võrreldes viimase redaktsiooniga,
(eel) = erinevused võrreldes eelmise redaktsiooniga, P = pisimuudatus',
'history-fieldset-title' => 'Lehitse ajalugu.',
'histfirst'              => 'Esimesed',
'histlast'               => 'Viimased',
'historysize'            => '({{PLURAL:$1|1 bait|$1 baiti}})',
'historyempty'           => '(tühi)',

# Revision feed
'history-feed-title'          => 'Redigeerimiste ajalugu',
'history-feed-description'    => 'Selle lehekülje redigeerimiste ajalugu',
'history-feed-item-nocomment' => '$1 - $2',
'history-feed-empty'          => 'Soovitud lehekülge ei ole olemas.
See võib olla vikist kustutatud või ümber nimetatud.
Ürita [[Special:Search|vikist otsida]] teemakohaseid lehekülgi.',

# Revision deletion
'rev-deleted-comment'         => '(kommentaar eemaldatud)',
'rev-deleted-user'            => '(kasutajanimi eemaldatud)',
'rev-deleted-event'           => '(logitoiming eemaldatud)',
'rev-deleted-text-permission' => "See lehekülje redaktsioon on '''kustutatud'''.
Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].",
'rev-deleted-text-unhide'     => "See lehekülje redaktsioon on '''kustutatud'''.
Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].
Administraatorina võid [$1 seda redaktsiooni] näha, kui soovid jätkata.",
'rev-deleted-text-view'       => "See lehekülje redaktsioon on '''kustutatud'''.
Administraatorina võid seda näha. Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].",
'rev-deleted-no-diff'         => "Seda erinevust ei saa vaadata, kuna üks redaktsioonidest on '''kustutatud'''.
Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].",
'rev-deleted-unhide-diff'     => "Üks selle lehekülje muudatustest on '''kustutatud'''.
Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].
Administraatorina võid [$1 seda muudatust] näha, kui soovid jätkata.",
'rev-delundel'                => 'näita/peida',
'revisiondelete'              => 'Kustuta/taasta redaktsioone',
'revdelete-nologtype-title'   => 'Logi tüüpi ei antud',
'revdelete-nologtype-text'    => 'Sa ei ole selle toimingu sooritamiseks logi tüüpi täpsustanud.',
'revdelete-nologid-title'     => 'Vigane logikirje',
'revdelete-no-file'           => 'Faili ei ole.',
'revdelete-show-file-confirm' => 'Kas oled kindel, et soovid häha faili "<nowiki>$1</nowiki>" kustutatud redaktsiooni, mis tehti $2 kell $3?',
'revdelete-show-file-submit'  => 'Jah',
'revdelete-selected'          => "'''{{PLURAL:$2|Valitud versioon|Valitud versioonid}} artiklist [[:$1]]:'''",
'revdelete-suppress-text'     => "Andmed tuleks varjata '''ainult''' järgnevatel juhtudel:
* Sobimatu isiklik teave
*: ''kodune aadress ja telefoninumber, sotsiaalhoolekandenumber jne''",
'revdelete-legend'            => 'Sea nähtavusele piirangud',
'revdelete-hide-text'         => 'Peida redigeerimise tekst',
'revdelete-hide-name'         => 'Peida toiming ja sihtmärk',
'revdelete-hide-comment'      => 'Peida muudatuse kommentaar',
'revdelete-hide-user'         => 'Peida toimetaja kasutajanimi/IP',
'revdelete-hide-restricted'   => 'Varja andmeid nii ülemate kui ka teiste eest.',
'revdelete-suppress'          => 'Varja andmed nii ülemate kui ka teiste eest.',
'revdelete-hide-image'        => 'Peida faili sisu',
'revdelete-unsuppress'        => 'Eemalda taastatud redaktsioonidelt piirangud',
'revdelete-log'               => 'Logi kommentaar:',
'revdelete-submit'            => 'Pöördu valitud redigeerimise juurde',
'revdelete-logentry'          => 'muutis lehekülje [[$1]] redaktsiooni nähtavust',
'logdelete-logentry'          => 'muutis lehekülje [[$1]] nähtavust',
'revdelete-success'           => "'''Redaktsiooni nähtavus edukalt paigas.'''",
'revdelete-failure'           => "'''Redaktsiooni nähtavust ei saanud paika:'''
$1",
'logdelete-success'           => "'''Logi nähtavus edukalt paigas.'''",
'logdelete-failure'           => "'''Logi nähtavust ei saanud paika:'''
$1",
'revdel-restore'              => 'Muuda nähtavust',
'pagehist'                    => 'Lehekülje ajalugu',
'deletedhist'                 => 'Kustutatud ajalugu',
'revdelete-content'           => 'sisu',
'revdelete-summary'           => 'toimeta kokkuvõtet',
'revdelete-uname'             => 'kasutajanimi',
'revdelete-restricted'        => 'kehtestas ülematele piirangud',
'revdelete-unrestricted'      => 'eemaldas ülematelt piirangud',
'revdelete-hid'               => 'peitsin: $1',
'revdelete-unhid'             => 'tegin nähtavaks: $1',
'revdelete-show-no-access'    => 'Tõrge ajatempliga $1 kell $2 üksuse näitamisel: selle on märge "piiranguga".
Sul ei ole sellele ligipääsu.',
'revdelete-only-restricted'   => 'Sa ei saa ilma veel ühte varjamissätet valimata üksuski ülemate eest varjata.',

# Suppression log
'suppressionlog'     => 'Varjamislogi',
'suppressionlogtext' => 'Allpool on nimekiri kustutamistest ja blokeeringutes, millega kaasneb ülemate eest sisu varjamine.
Jõus olevad keelud ja blokeeringud leiad [[Special:IPBlockList|blokeeritud IP-aadressie loendist]].',

# History merging
'mergehistory'                     => 'Ühenda lehtede ajalood',
'mergehistory-box'                 => 'Ühenda kahe lehekülje muudatuste ajalugu:',
'mergehistory-from'                => 'Lehekülje allikas:',
'mergehistory-into'                => 'Lehekülje sihtpunkt:',
'mergehistory-list'                => 'Ühendatav redigeerimise ajalugu',
'mergehistory-go'                  => 'Näita ühendatavaid muudatusi',
'mergehistory-submit'              => 'Ühenda redaktsioonid',
'mergehistory-empty'               => 'Ühendatavaid redaktsioone ei ole.',
'mergehistory-success'             => 'Lehekülje [[:$1]] {{PLURAL:$3|üks redaktsioon|$3 redaktsiooni}} liideti lehega [[:$2]].',
'mergehistory-fail'                => 'Muudatuste ajaloo liitmine ebaõnnestus. Palun kontrolli lehekülje ja aja parameetreid.',
'mergehistory-no-source'           => 'Lehekülje allikat $1 ei ole.',
'mergehistory-no-destination'      => 'Lehekülje sihtpunkti $1 ei ole.',
'mergehistory-invalid-source'      => 'Allikaleheküljel peab olema lubatav pealkiri.',
'mergehistory-invalid-destination' => 'Sihtkoha leheküljel peab olema lubatav pealkiri.',
'mergehistory-autocomment'         => 'Liitsin lehe [[:$1]] lehele [[:$2]]',
'mergehistory-comment'             => 'Lehekülg [[:$1]] liidetud leheküljele [[:$2]]: $3',
'mergehistory-same-destination'    => 'Lähte- ja sihtpunkti lehekülg ei või olla sama',
'mergehistory-reason'              => 'Põhjus:',

# Merge log
'mergelog'           => 'Liitmise logi',
'pagemerge-logentry' => 'liitis lehekülje [[$1]] leheküljelega [[$2]] (muudatusi kuni $3)',
'revertmerge'        => 'Tühista ühendamine',
'mergelogpagetext'   => 'Allpool on hiljuti üksteisega liidetud leheküljeajalugude logi.',

# Diffs
'history-title'            => '"$1" muudatuste ajalugu',
'difference'               => '(Erinevused redaktsioonide vahel)',
'lineno'                   => 'Rida $1:',
'compareselectedversions'  => 'Võrdle valitud redaktsioone',
'showhideselectedversions' => 'Näita/peida valitud versioonid',
'visualcomparison'         => 'Vaateline võrdlus',
'wikicodecomparison'       => 'Lähtetekstide võrdlus',
'editundo'                 => 'eemalda',
'diff-multi'               => '({{PLURAL:$1|Ühte vahepealset muudatust|$1 vahepealset muudatust}} ei näidata.)',
'diff-movedto'             => 'teisaldatud leheküljele $1',
'diff-styleadded'          => '$1 stiil lisatud',
'diff-added'               => '$1 lisatud',
'diff-changedto'           => 'muudetud selliseks: $1',
'diff-movedoutof'          => 'teisaldatud leheküljelt $1',
'diff-styleremoved'        => '$1 stiil eemaldatud',
'diff-removed'             => '$1 eemaldatud',
'diff-changedfrom'         => 'muudetud siit: $1',
'diff-src'                 => 'allikas',
'diff-withdestination'     => 'sihtpunkt: $1',
'diff-width'               => 'laius',
'diff-height'              => 'kõrgus',
'diff-p'                   => "'''paragrahv'''",
'diff-blockquote'          => "'''tsitaat'''",
'diff-h1'                  => "'''pealkiri (tase 1)'''",
'diff-h2'                  => "'''pealkiri (tase 2)'''",
'diff-h3'                  => "'''pealkiri (tase 3)'''",
'diff-h4'                  => "'''pealkiri (tase 4)'''",
'diff-h5'                  => "'''pealkiri (tase 5)'''",
'diff-ul'                  => "'''järjestamata loend'''",
'diff-ol'                  => "'''järjestatud loend'''",
'diff-table'               => "'''tabel'''",
'diff-tbody'               => "'''tabeli sisu'''",
'diff-tr'                  => "'''rida'''",
'diff-td'                  => "'''element'''",
'diff-th'                  => "'''päis'''",
'diff-br'                  => "'''tühik'''",
'diff-dl'                  => "'''definitsioonide loend'''",
'diff-dd'                  => "'''definitsioon'''",
'diff-img'                 => "'''pilt'''",
'diff-span'                => "'''ulatus'''",
'diff-a'                   => "'''link'''",
'diff-i'                   => "'''kaldkiri'''",
'diff-b'                   => "'''paks kiri'''",
'diff-strong'              => "'''tugev'''",
'diff-em'                  => "'''rõhk'''",
'diff-font'                => "'''kirjatüüp'''",
'diff-big'                 => "'''suur'''",
'diff-del'                 => "'''kustutatud'''",
'diff-tt'                  => "'''fikseeritud laius'''",
'diff-sub'                 => "'''alaindeks'''",
'diff-sup'                 => "'''ülaindeks'''",
'diff-strike'              => "'''läbi joonitud'''",

# Search results
'searchresults'                    => 'Otsingu tulemused',
'searchresults-title'              => 'Otsingu "$1" tulemused',
'searchresulttext'                 => 'Lisainfot otsimise kohta vaata [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Otsisid fraasi "[[:$1]]" ([[Special:Prefixindex/$1|kõik sõnega "$1" algavad lehed]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kõik lehed, mis sisaldavad linke artiklile "$1"]])',
'searchsubtitleinvalid'            => 'Päring "$1"',
'noexactmatch'                     => "'''Artiklit pealkirjaga \"\$1\" ei leitud.''' Võite [[:\$1|selle artikli luua]].",
'noexactmatch-nocreate'            => "'''Lehekülge pealkirjaga \"\$1\" ei eksisteeri.'''",
'toomanymatches'                   => 'Liiga palju tulemusi, ürita teistsugust päringut',
'titlematches'                     => 'Vasted artikli pealkirjades',
'notitlematches'                   => 'Artikli pealkirjades otsitavat ei leitud',
'textmatches'                      => 'Vasted artikli tekstides',
'notextmatches'                    => 'Artikli tekstides otsitavat ei leitud',
'prevn'                            => 'eelmised {{PLURAL:$1|$1}}',
'nextn'                            => 'järgmised {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Eelmine tulemus|Eelmised $1 tulemust}}',
'nextn-title'                      => '{{PLURAL:$1|Järgmine tulemus|Järgmised $1 tulemust}}',
'viewprevnext'                     => 'Näita ($1) ($2) ($3).',
'searchmenu-legend'                => 'Otsingu sätted',
'searchmenu-exists'                => "'''Lehekülg pealkirjaga \"[[:\$1]]\" on selles vikis olemas.'''",
'searchmenu-new'                   => "'''Loo lehekülg pealkirjaga \"[[:\$1]]\" siia vikisse!'''",
'searchhelp-url'                   => 'Help:Juhend',
'searchprofile-articles'           => 'Sisuleheküljed',
'searchprofile-project'            => 'Abi- ja projektilehed',
'searchprofile-images'             => 'Multimeedia',
'searchprofile-everything'         => 'Kõik',
'searchprofile-advanced'           => 'Detailne otsing',
'searchprofile-articles-tooltip'   => 'Otsi nimeruumist $1',
'searchprofile-project-tooltip'    => 'Otsi nimeruumidest $1',
'searchprofile-images-tooltip'     => 'Failiotsing',
'searchprofile-everything-tooltip' => 'Otsi kogu sisust (k.a aruteluleheküljed)',
'searchprofile-advanced-tooltip'   => 'Otsi kohandatud nimeruumidest',
'search-result-size'               => '$1 ({{PLURAL:$2|1 sõna|$2 sõna}})',
'search-result-score'              => 'Vastavus: $1%',
'search-redirect'                  => '(ümbersuunamine $1)',
'search-section'                   => '(alaosa $1)',
'search-suggest'                   => 'Kas Sa mõtlesid: $1',
'search-interwiki-caption'         => 'Sõsarprojektid',
'search-interwiki-default'         => '$1 tulemused:',
'search-interwiki-more'            => '(veel)',
'search-mwsuggest-enabled'         => 'ettepanekutega',
'search-mwsuggest-disabled'        => 'ettepanekuid ei ole',
'search-relatedarticle'            => 'Seotud',
'mwsuggest-disable'                => 'Ära näita otsinguvihjeid',
'searcheverything-enable'          => 'Otsi kõigist nimeruumidest',
'searchrelated'                    => 'seotud',
'searchall'                        => 'kõik',
'showingresults'                   => "Allpool näitame {{PLURAL:$1|'''ühte''' tulemit|'''$1''' tulemit}} alates tulemist #'''$2'''.",
'showingresultsnum'                => "Allpool näitame {{PLURAL:$3|'''ühte''' tulemit|'''$3''' tulemit}} alates tulemist #'''$2'''.",
'showingresultstotal'              => "Allpool näidatakse {{PLURAL:$4|'''$1'''. tulemust (otsingutulemuste koguarv '''$3''')|'''$1. - $2.''' tulemust (otsingutulemuste koguarv '''$3''')}}",
'showingresultsheader'             => "{{PLURAL:$5|'''$1''' '''$3'''-st vastest|Vasted '''$1–$2''' '''$3'''-st}} päringule '''$4'''",
'nonefound'                        => "'''Märkus''': Otsing hõlmab vaikimisi vaid osasid nimeruume.
Kui soovid otsida ühekorraga kõigist nimeruumidest (kaasa arvatud arutelulehed, mallid, jne) kasuta
päringu ees prefiksit ''all:''. Konkreetsest nimeruumist otsimiseks kasuta prefiksina sele nimeruumi nime.",
'search-nonefound'                 => 'Päringule ei leitud vasteid.',
'powersearch'                      => 'Otsi',
'powersearch-legend'               => 'Detailne otsing',
'powersearch-ns'                   => 'Otsing nimeruumidest:',
'powersearch-redir'                => 'Loetle ümbersuunamised',
'powersearch-field'                => 'Otsi fraasi',
'powersearch-togglelabel'          => 'Vali:',
'powersearch-toggleall'            => 'Kõik',
'powersearch-togglenone'           => 'Ei ühtegi',
'search-external'                  => 'Välisotsing',
'searchdisabled'                   => "<p>Vabandage! Otsing vikist on ajutiselt peatatud, et säilitada muude teenuste normaalne töökiirus. Otsimiseks võite kasutada allpool olevat Google'i otsinguvormi, kuid sellelt saadavad tulemused võivad olla vananenud.</p>",

# Quickbar
'qbsettings'               => 'Kiirriba sätted',
'qbsettings-none'          => 'Ei_ole',
'qbsettings-fixedleft'     => 'Püsivalt_vasakul',
'qbsettings-fixedright'    => 'Püsivalt paremal',
'qbsettings-floatingleft'  => 'Ujuvalt vasakul',
'qbsettings-floatingright' => 'Ujuvalt paremal',

# Preferences page
'preferences'                   => 'Eelistused',
'mypreferences'                 => 'Eelistused',
'prefs-edits'                   => 'Redigeerimiste arv:',
'prefsnologin'                  => 'Te ei ole sisse loginud',
'prefsnologintext'              => 'Et oma eelistusi seada, peate olema <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} sisse logitud]</span>.',
'changepassword'                => 'Muuda parool',
'prefs-skin'                    => 'Kujundus',
'skin-preview'                  => 'eelvaade',
'prefs-math'                    => 'Valemite näitamine',
'datedefault'                   => 'Eelistus puudub',
'prefs-datetime'                => 'Kuupäev ja kellaaeg',
'prefs-personal'                => 'Kasutaja andmed',
'prefs-rc'                      => 'Viimased muudatused',
'prefs-watchlist'               => 'Jälgimisloend',
'prefs-watchlist-days'          => 'Mitme päeva muudatusi näidata loendis:',
'prefs-watchlist-days-max'      => '(maksimaalne päevade arv on 7)',
'prefs-watchlist-edits'         => 'Mitu muudatust näidatakse laiendatud jälgimisloendis:',
'prefs-watchlist-edits-max'     => '(maksimaalne väärtus: 1000)',
'prefs-watchlist-token'         => 'Jälgimisloendi tunnus',
'prefs-misc'                    => 'Muu',
'prefs-resetpass'               => 'Muuda parooli',
'prefs-email'                   => 'E-posti sätted',
'prefs-rendering'               => 'Ilme',
'saveprefs'                     => 'Salvesta eelistused',
'resetprefs'                    => 'Lähtesta eelistused',
'restoreprefs'                  => 'Taasta kõikjal vaikesätted',
'prefs-editing'                 => 'Toimetamine',
'prefs-edit-boxsize'            => 'Toimetamise akna suurus.',
'rows'                          => 'Ridu:',
'columns'                       => 'Veerge:',
'searchresultshead'             => 'Otsingutulemite sätted',
'resultsperpage'                => 'Vasteid leheküljel:',
'contextlines'                  => 'Ridu vastes:',
'contextchars'                  => 'Kaasteksti rea kohta:',
'stub-threshold'                => '<a href="#" class="stub">Nii</a> lingitud lehekülje suuruse ülempiir (baitides):',
'recentchangesdays'             => 'Mitu päeva näidata viimastes muudatustes:',
'recentchangesdays-max'         => 'Ülemmäär $1 {{PLURAL:$1|päev|päeva}}',
'recentchangescount'            => 'Mitut redaktsiooni vaikimisi näidata:',
'prefs-help-recentchangescount' => 'See käib viimaste muudatuste, lehekülgede ajalugude ja logide kohta.',
'prefs-help-watchlist-token'    => 'Selle välja täitmine tekitab sinu jälgimisloendile RSS-toite.
Igaüks, kes teab sellel väljal olevat võtit, saab lugeda sinu jälgimisloendit, seega vali turvaline väärtus.
Siin on juhuslik väärtus, mida saad kasutada: $1',
'savedprefs'                    => 'Teie eelistused on salvestatud.',
'timezonelegend'                => 'Ajavöönd:',
'localtime'                     => 'Kohalik aeg:',
'timezoneuseserverdefault'      => 'Kasuta serveri vaikesätet',
'timezoneuseoffset'             => 'Muu (määra ajavahe)',
'timezoneoffset'                => 'Ajavahe¹:',
'servertime'                    => 'Serveri aeg:',
'guesstimezone'                 => 'Loe aeg brauserist',
'timezoneregion-africa'         => 'Aafrika',
'timezoneregion-america'        => 'Ameerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktika',
'timezoneregion-asia'           => 'Aasia',
'timezoneregion-atlantic'       => 'Atlandi ookean',
'timezoneregion-australia'      => 'Austraalia',
'timezoneregion-europe'         => 'Euroopa',
'timezoneregion-indian'         => 'India ookean',
'timezoneregion-pacific'        => 'Vaikne ookean',
'allowemail'                    => 'Luba teistel kasutajatel mulle e-posti saata',
'prefs-searchoptions'           => 'Otsimine',
'prefs-namespaces'              => 'Nimeruumid',
'defaultns'                     => 'Muul juhul otsi järgmistest nimeruumidest:',
'default'                       => 'vaikeväärtus',
'prefs-files'                   => 'Failid',
'prefs-custom-css'              => 'kohandatud CSS',
'prefs-custom-js'               => 'kohandatud JS',
'prefs-reset-intro'             => 'Sellel leheküljel saad oma eelistused lähtestada võrgukoha vaike-eelistusteks.
Toimingut ei saa hiljem tühistada.',
'prefs-emailconfirm-label'      => 'E-posti kinnitus:',
'prefs-textboxsize'             => 'Toimetamisakna suurus',
'youremail'                     => 'Teie e-posti aadress*',
'username'                      => 'Kasutajanimi:',
'uid'                           => 'Kasutaja ID:',
'prefs-memberingroups'          => 'Kuulub {{PLURAL:$1|rühma|rühmadesse}}:',
'prefs-registration'            => 'Registreerumise aeg:',
'yourrealname'                  => 'Tegelik nimi:',
'yourlanguage'                  => 'Keel:',
'yournick'                      => 'Teie hüüdnimi (allakirjutamiseks)',
'prefs-help-signature'          => 'Kommentaarile tuleks aruteluleheküljel alla kirjutada märkidega <nowiki>~~~~</nowiki>, mis muutuvad sinu allkirjaks ja ajatempliks.',
'badsig'                        => 'Sobimatu allkiri.
Palun kontrolli HTML koodi.',
'badsiglength'                  => 'Sinu signatuur on liiga pikk.
See ei tohi olla pikem kui $1 {{PLURAL:$1|sümbol|sümbolit}}.',
'yourgender'                    => 'Sugu:',
'gender-unknown'                => 'Määramata',
'gender-male'                   => 'Mees',
'gender-female'                 => 'Naine',
'prefs-help-gender'             => 'Vabatahtlik: kasutatakse mõnedes keeltes sooliselt korrektse väljendumise otstarbel. Info on avalik.',
'email'                         => 'E-post',
'prefs-help-realname'           => 'Vabatahtlik: Kui otsustate selle avaldada, kasutatakse seda teie kaastöö seostamiseks teiega.',
'prefs-help-email'              => 'Elektronpostiaadressi sisestamine ei ole kohustuslik, kuid võimaldab sul tellida parooli meeldetuletuse, kui peaksid oma parooli unustama. Samuti saad aadressi märkides anda oma identiteeti avaldamata teistele kasutajatele võimaluse enesele sõnumeid saata.',
'prefs-help-email-required'     => 'E-posti aadress on vajalik.',
'prefs-info'                    => 'Põhiteave',
'prefs-i18n'                    => 'Rahvusvaheline',
'prefs-signature'               => 'Allkiri',
'prefs-dateformat'              => 'Kuupäeva vorming',
'prefs-timeoffset'              => 'Ajavahe',
'prefs-advancedediting'         => 'Täpsemad eelistused',
'prefs-advancedrc'              => 'Täpsemad eelistused',
'prefs-advancedrendering'       => 'Täpsemad eelistused',
'prefs-advancedsearchoptions'   => 'Täpsemad eelistused',
'prefs-advancedwatchlist'       => 'Täpsemad eelistused',
'prefs-display'                 => 'Kuvasätted',
'prefs-diffs'                   => 'Erinevused',

# User rights
'userrights'                  => 'Kasutaja õiguste muutmine',
'userrights-lookup-user'      => 'Muuda kasutajarühma',
'userrights-user-editname'    => 'Sisesta kasutajatunnus:',
'editusergroup'               => 'Muuda kasutajarühma',
'editinguser'                 => "Muudan kasutaja '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) õigusi",
'userrights-editusergroup'    => 'Kasutajarühma valik',
'saveusergroups'              => 'Salvesta rühma muudatused',
'userrights-groupsmember'     => 'Kuulub rühma:',
'userrights-groups-help'      => 'Sa võid muuta selle kasutaja kuuluvust eri kasutajarühmadesse.
* Märgitud kast tähendab, et kasutaja kuulub sellesse rühma.
* Märkimata kast tähendab, et kasutaja ei kuulu sellesse rühma.
* Aga * kasutajarühma juures tähistab õigust, mida sa peale lisamist enam eemaldada ei saa, või siis ka vastupidi.',
'userrights-reason'           => 'Muutmise põhjus:',
'userrights-no-interwiki'     => 'Sul ei ole luba muuta kasutajaõigusi teistes vikides.',
'userrights-nodatabase'       => 'Andmebaasi $1 ei ole olemas või pole see kohalik.',
'userrights-nologin'          => 'Kasutaja õiguste muutmiseks, pead sa ülema õigustega kontoga [[Special:UserLogin|sisse logima]].',
'userrights-notallowed'       => 'Sulle pole antud luba jagada kasutajatele õigusi.',
'userrights-changeable-col'   => 'Rühmad, mida sa saad muuta',
'userrights-unchangeable-col' => 'Rühmad, mida sa ei saa muuta',

# Groups
'group'               => 'Rühm:',
'group-user'          => 'Kasutajad',
'group-autoconfirmed' => 'Automaatselt kinnitatud kasutajad',
'group-bot'           => 'Robotid',
'group-sysop'         => 'Ülemad',
'group-bureaucrat'    => 'Bürokraadid',
'group-suppress'      => 'Varjajad',
'group-all'           => '(kõik)',

'group-user-member'          => 'Kasutaja',
'group-autoconfirmed-member' => 'Automaatselt kinnitatud kasutaja',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Ülem',
'group-bureaucrat-member'    => 'Bürokraat',
'group-suppress-member'      => 'Varjaja',

'grouppage-user'          => '{{ns:project}}:Kasutajad',
'grouppage-autoconfirmed' => '{{ns:project}}:Automaatselt kinnitatud kasutajad',
'grouppage-bot'           => '{{ns:project}}:Robotid',
'grouppage-sysop'         => '{{ns:project}}:Administraatorid',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraadid',
'grouppage-suppress'      => '{{ns:project}}:Varjaja',

# Rights
'right-read'                 => 'Lugeda lehekülgi',
'right-edit'                 => 'Redigeerida lehekülje sisu',
'right-createpage'           => 'Luua lehekülgi (mis pole arutelu leheküljed)',
'right-createtalk'           => 'Luua arutelu lehekülgi',
'right-createaccount'        => 'Luua uusi kasutajakontosid',
'right-minoredit'            => 'Märkida muudatusi pisimuudatustena',
'right-move'                 => 'Teisaldada lehekülgi',
'right-move-subpages'        => 'Teisaldada lehekülgi koos nende alamlehtedega',
'right-move-rootuserpages'   => 'Teisaldada kasutajalehekülgi',
'right-movefile'             => 'Teisaldada faile',
'right-suppressredirect'     => 'Teisaldada lehekülgi ümbersuunamist loomata',
'right-upload'               => 'Faile üles laadida',
'right-reupload'             => 'Kirjutada olemasolevaid faile üle',
'right-reupload-own'         => 'Üle kirjutada enda üles laaditud faile',
'right-reupload-shared'      => 'Asendada kohalikus vikis jagatud failivaramu faile',
'right-upload_by_url'        => 'Faile internetiaadressilt üles laadida',
'right-purge'                => 'Tühjendada lehekülje vahemälu kinnituseta',
'right-autoconfirmed'        => 'Redigeerida poolkaitstud lehekülgi',
'right-bot'                  => 'Olla koheldud kui automaadistatud toimimisviis',
'right-nominornewtalk'       => 'Teha arutelulehekülgedel pisimuudatusi, ilma et lehekülg märgitaks uuena',
'right-apihighlimits'        => 'Kasutada API-päringutes kõrgemaid limiite',
'right-writeapi'             => 'Kasutada {{SITENAME}} kirjutamise liidest',
'right-delete'               => 'Lehekülgi kustutada',
'right-bigdelete'            => 'Pikkade ajalugudega lehekülgi kustutada',
'right-deleterevision'       => 'Kustutada ja taastada lehekülgede teatud redaktsioone',
'right-deletedhistory'       => 'Vaadata kustutatud ajalookirjeid ilma seotud tekstita',
'right-browsearchive'        => 'Otsida kustutatud lehekülgi',
'right-undelete'             => 'Taastada lehekülg',
'right-suppressrevision'     => 'Üle vaadata ja taastada ülemate eest peidetud redaktsioone',
'right-suppressionlog'       => 'Vaadata eralogisid',
'right-block'                => 'Keelata lehekülgede muutmist mõnel kasutajal',
'right-blockemail'           => 'Keelata kasutajal e-kirjade saatmine',
'right-hideuser'             => 'Blokeerida kasutajanimi, peites selle avalikkuse eest',
'right-ipblock-exempt'       => 'Mööduda automaatsetest blokeeringutest ning aadressivahemiku- ja IP-blokeeringutest',
'right-proxyunbannable'      => 'Mööduda automaatsetest puhverserveri blokeeringutest',
'right-protect'              => 'Muuta kaitsetasemeid ja redigeerida kaitstud lehekülgi',
'right-editprotected'        => 'Muuta kaitstud lehekülgi, millel ei ole kaskaadkaitset',
'right-editinterface'        => 'Muuta kasutajaliidest',
'right-editusercssjs'        => 'Redigeerida teiste kasutajate CSS ja JS faile',
'right-editusercss'          => 'Redigeerida teiste kasutajate CSS faile',
'right-edituserjs'           => 'Redigeerida teiste kasutajate JS faile',
'right-rollback'             => 'Tühistada otsekohe muudatused, mille tegi kasutaja, kes lehekülge viimati redigeeris.',
'right-markbotedits'         => 'Märkida muudatuse tühistamine robotimuudatusena',
'right-noratelimit'          => 'Mööduda toimingumäära limiitidest',
'right-import'               => 'Importida lehekülgi teistest vikidest',
'right-importupload'         => 'Importida XML-dokumendi lehekülgi',
'right-patrol'               => 'Märkida teiste redigeerimised kontrollituks',
'right-autopatrol'           => 'Teha vaikimisi kontrollituks märgitud muudatusi',
'right-patrolmarks'          => 'Vaadata viimaste muudatuste kontrollimise märkeid',
'right-unwatchedpages'       => 'Vaadata jälgimata lehekülgede nimekirja',
'right-trackback'            => "Lähetada ''trackback''",
'right-mergehistory'         => 'Ühendada lehekülgede ajalood',
'right-userrights'           => 'Muuta kõiki kasutajaõigusi',
'right-userrights-interwiki' => 'Muuta teiste vikide kasutajate õigusi',
'right-siteadmin'            => 'Panna lukku ja lukust lahti teha andmebaasi',
'right-reset-passwords'      => 'Määrata teistele kasutajatele paroole',
'right-versiondetail'        => 'Näha laiendatud tarkvaraversiooni teavet.',

# User rights log
'rightslog'      => 'Kasutaja õiguste logi',
'rightslogtext'  => 'See on logi kasutajate õiguste muutuste kohta.',
'rightslogentry' => 'muutis kasutaja $1 rühmast $2 rühma $3 liikmeks',
'rightsnone'     => '(puudub)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'seda lehekülge lugeda',
'action-edit'                 => 'seda lehekülge muuta',
'action-createpage'           => 'lehekülgi luua',
'action-createtalk'           => 'arutelulehekülgi luua',
'action-createaccount'        => 'seda kasutajakontot luua',
'action-minoredit'            => 'seda muudatust pisimuudatuseks märkida',
'action-move'                 => 'seda lehekülge teisaldada',
'action-move-subpages'        => 'seda lehekülge koos alamlehekülgedega teisaldada',
'action-move-rootuserpages'   => 'teisaldada kasutajalehekülgi',
'action-movefile'             => 'seda faili teisaldada',
'action-upload'               => 'seda faili üles laadida',
'action-reupload'             => 'seda olemasolevat faili üle kirjutada',
'action-reupload-shared'      => 'seda jagatud varamus asuvat faili üle kirjutada',
'action-upload_by_url'        => 'seda faili internetiaadressilt üles laadida',
'action-writeapi'             => 'kirjutamise rakendusliidest kasutada',
'action-delete'               => 'seda lehekülge kustutada',
'action-deleterevision'       => 'seda redaktsiooni kustutada',
'action-deletedhistory'       => 'selle lehekülje kustutatud ajalugu vaadata',
'action-browsearchive'        => 'kustutatud lehekülgi otsida',
'action-undelete'             => 'lehekülgi taastada',
'action-suppressrevision'     => 'seda peidetud redaktsiooni vaadata ja taastada',
'action-suppressionlog'       => 'seda eralogi vaadata',
'action-block'                => 'selle kasutaja redigeerimisõigust blokeerida',
'action-protect'              => 'selle lehekülje kaitsetasemeid muuta',
'action-import'               => 'seda lehekülge teisest vikist importida',
'action-importupload'         => 'seda lehekülge faili üleslaadimise abil importida',
'action-patrol'               => 'teiste muudatusi kontrollituks märkida',
'action-autopatrol'           => 'oma muudatusi kontrollituks märkida',
'action-unwatchedpages'       => 'jälgimata lehekülgede loendit vaadata',
'action-trackback'            => "''trackbacki'' lähetada",
'action-mergehistory'         => 'selle lehekülje ajalugu liita',
'action-userrights'           => 'kõiki kasutajaõigusi muuta',
'action-userrights-interwiki' => 'teiste vikide kasutajate õigusi muuta',
'action-siteadmin'            => 'andmebaasi lukustada või avada',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|muudatus|muudatust}}',
'recentchanges'                     => 'Viimased muudatused',
'recentchanges-legend'              => 'Viimaste muudatuste seaded',
'recentchangestext'                 => 'Jälgige sellel leheküljel viimaseid muudatusi.',
'recentchanges-feed-description'    => 'Jälgi vikisse tehtud viimaseid muudatusi.',
'recentchanges-label-legend'        => 'Märkide selgitused: $1.',
'recentchanges-legend-newpage'      => '$1 - uus lehekülg',
'recentchanges-label-newpage'       => 'See muudatus lõi uue lehekülje',
'recentchanges-legend-minor'        => '$1 – pisimuudatus',
'recentchanges-label-minor'         => 'See on pisiparandus',
'recentchanges-legend-bot'          => '$1 - roboti muudatus',
'recentchanges-label-bot'           => 'Selle muudatuse sooritas robot',
'recentchanges-legend-unpatrolled'  => '$1 – kontrollimata muudatus',
'recentchanges-label-unpatrolled'   => 'Seda muudatust ei ole veel kontrollitud',
'rcnote'                            => "Allpool on esitatud {{PLURAL:$1|'''1''' muudatus|viimased '''$1''' muudatust}} viimase {{PLURAL:$2|päeva|'''$2''' päeva}} jooksul, seisuga $4, kell $5.",
'rcnotefrom'                        => 'Allpool on esitatud muudatused alates <b>$2</b> (näidatakse kuni <b>$1</b> muudatust).',
'rclistfrom'                        => 'Näita muudatusi alates $1',
'rcshowhideminor'                   => '$1 pisiparandused',
'rcshowhidebots'                    => '$1 robotid',
'rcshowhideliu'                     => '$1 sisseloginud kasutajad',
'rcshowhideanons'                   => '$1 anonüümsed kasutajad',
'rcshowhidepatr'                    => '$1 kontrollitud muudatused',
'rcshowhidemine'                    => '$1 minu parandused',
'rclinks'                           => 'Näita viimast $1 muudatust viimase $2 päeva jooksul<br />$3',
'diff'                              => 'erin',
'hist'                              => 'ajal',
'hide'                              => 'peida',
'show'                              => 'näita',
'minoreditletter'                   => 'P',
'newpageletter'                     => 'U',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|jälgiv kasutaja|jälgivat kasutajat}}]',
'rc_categories'                     => 'Ainult kategooriatest (eraldajaks "|")',
'rc_categories_any'                 => 'Mistahes',
'newsectionsummary'                 => '/* $1 */ uus alajaotus',
'rc-enhanced-expand'                => 'Näita üksikasju (nõuab JavaScripti)',
'rc-enhanced-hide'                  => 'Peida detailid',

# Recent changes linked
'recentchangeslinked'          => 'Seotud muudatused',
'recentchangeslinked-feed'     => 'Seotud muudatused',
'recentchangeslinked-toolbox'  => 'Seotud muudatused',
'recentchangeslinked-title'    => 'Muudatused, mis on seotud "$1"-ga.',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Antud ajavahemiku jooksul ei ole lingitud lehekülgedel muudatusi tehtud.',
'recentchangeslinked-summary'  => "See on viimaste muudatuste nimekiri lehekülgedel, kuhu lähevad lingid antud leheküljelt (või antud kategooria liikmetele).
Leheküljed, mis lähevad [[Special:Watchlist|Jälgimisloendi]] koosseisu, on esiletoodud '''rasvasena'''.",
'recentchangeslinked-page'     => 'Lehekülje nimi:',
'recentchangeslinked-to'       => 'Näita hoopis muudatusi lehekülgedel, mis sellele lehele lingivad',

# Upload
'upload'                      => 'Faili üleslaadimine',
'uploadbtn'                   => 'Laadi fail üles',
'reupload'                    => 'Uuesti üleslaadimine',
'reuploaddesc'                => 'Tagasi üleslaadimise vormi juurde.',
'uploadnologin'               => 'Sisse logimata',
'uploadnologintext'           => 'Kui Te soovite faile üles laadida, peate [[Special:UserLogin|sisse logima]].',
'upload_directory_missing'    => 'Üleslaadimiskaust $1 puudub ja veebiserver ei saa seda luua.',
'uploaderror'                 => 'Faili laadimine ebaõnnestus',
'uploadtext'                  => "Järgnevat vormi võid kasutada failide üleslaadimiseks.

Et näha või leida eelnevalt üles laaditud faile vaata [[Special:FileList|failide nimekirja]].
Üleslaadimiste ajalugu saab uurida [[Special:Log/upload|üleslaadimislogist]], kustutamiste ajalugu [[Special:Log/delete|kustutamislogist]].

Faili lisamiseks artiklile kasuta linki ühel kujul järgnevatest:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:fail.jpg]]</nowiki></tt>''' algupäraste mõõtmetega pildi lisamiseks
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:fail.png|200px|thumb|left|kirjeldus]]</nowiki></tt>''' raamiga pisipildi lisamiseks lehekülje vasakusse serva; ''kirjeldus'' kuvatakse pildiallkirjana
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:fail.ogg]]</nowiki></tt>''' helifaililingi lisamiseks",
'upload-permitted'            => 'Lubatud failitüübid: $1.',
'upload-preferred'            => 'Eelistatud failitüübid: $1.',
'upload-prohibited'           => 'Keelatud failitüübid: $1.',
'uploadlog'                   => 'üleslaadimise logi',
'uploadlogpage'               => 'Üleslaadimise logi',
'uploadlogpagetext'           => 'Allpool on loend viimastest failide üleslaadimistest. Visuaalsema esituse nägemiseks vaata [[Special:NewFiles|uute failide galeriid]].',
'filename'                    => 'Faili nimi',
'filedesc'                    => 'Lühikirjeldus',
'fileuploadsummary'           => 'Info faili kohta:',
'filereuploadsummary'         => 'Faili muudatused:',
'filestatus'                  => 'Autoriõiguse staatus:',
'filesource'                  => 'Allikas:',
'uploadedfiles'               => 'Üleslaaditud failid',
'ignorewarning'               => 'Ignoreeri hoiatust ja salvesta fail hoiatusest hoolimata',
'ignorewarnings'              => 'Ignoreeri hoiatusi',
'minlength1'                  => 'Faili nimes peab olema vähemalt üks kirjamärk.',
'illegalfilename'             => 'Faili "$1" nimi sisaldab sümboleid, mis pole pealkirjades lubatud. Palun nimetage fail ümber ja proovige uuesti.',
'badfilename'                 => 'Pildi nimi on muudetud. Uus nimi on "$1".',
'filetype-unwanted-type'      => "'''\".\$1\"''' on soovimatu failitüüp.
Eelistatud {{PLURAL:\$3|failitüüp on|failitüübid on}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' ei ole lubatud failitüüp.  Lubatud {{PLURAL:\$3|failitüüp|failitüübid}} on  \$2.",
'filetype-missing'            => 'Failil puudub laiend (nagu näiteks ".jpg").',
'large-file'                  => 'On soovitatav, et üleslaaditavad failid ei oleks suuremad kui $1. Selle faili suurus on $2.',
'largefileserver'             => 'Antud fail on suurem lubatud failisuurusest.',
'emptyfile'                   => 'Fail, mille Te üles laadisite, paistab olevat tühi.
See võib olla tingitud vigasest failinimest.
Palun kaalutlege, kas Te tõesti soovite seda faili üles laadida.',
'fileexists'                  => "Sellise nimega fail on juba olemas. Palun kontrollige '''<tt>$1</tt>''', kui te ei ole kindel, kas tahate seda muuta.",
'fileexists-extension'        => "Sarnase nimega fail on olemas:<br />
Üleslaetava faili nimi: '''<tt>$1</tt>'''<br />
Olemasoleva faili nimi: '''<tt>$2</tt>'''<br />
Palun vali teistsugune nimi.",
'fileexists-thumb'            => "<center>'''Fail on olemas'''</center>",
'fileexists-thumbnail-yes'    => "See paistab olevat vähendatud suurusega pilt (''pisipilt'').
Palun vaata faili '''<tt>$1</tt>'''.<br />
Kui vaadatud fail on sama pilt algupärases suuruses, pole vaja täiendavat pisipilti üles laadida.",
'file-thumbnail-no'           => "Failinimi algab eesliitega '''<tt>$1</tt>'''.
See paistab vähendatud suurusega pilt (''pisipilt'') olevat.
Kui sul on ka selle pildi täislahutusega versioon, laadi palun hoopis see üles, vastasel korral muuda palun faili nime.",
'fileexists-forbidden'        => 'Sellise nimega fail on juba olemas, seda ei saa üle kirjutada.
Palun pöörduge tagasi ja laadige fail üles mõne teise nime all. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Samanimeline fail on juba olemas jagatud meediavaramus.
Kui soovid siiski oma faili üles laadida, siis palun mine tagasi ja kasuta teist failinime.
[[File:$1|thumb|center|$1]]',
'file-deleted-duplicate'      => 'Selle failiga ([[$1]]) identne fail on hiljuti kustutatud.
Vaata selle faili kustutamise ajalugu enne jätkamist.',
'successfulupload'            => 'Üleslaadimine õnnestus',
'uploadwarning'               => 'Üleslaadimise hoiatus',
'savefile'                    => 'Salvesta fail',
'uploadedimage'               => 'laadis üles faili "[[$1]]"',
'overwroteimage'              => 'laadis üles faili "[[$1]]" uue versiooni',
'uploaddisabled'              => 'Üleslaadimine hetkel keelatud',
'uploaddisabledtext'          => 'Faili üleslaadimine on keelatud.',
'uploadcorrupt'               => 'Fail on vigane või vale laiendiga. Palun kontrolli faili ja proovi seda uuesti üles laadida.',
'uploadvirus'                 => 'Fail sisaldab viirust! Täpsemalt: $1',
'sourcefilename'              => 'Lähtefail:',
'destfilename'                => 'Failinimi vikis:',
'upload-maxfilesize'          => 'Maksimaalne failisuurus: $1',
'watchthisupload'             => 'Jälgi seda lehekülge',
'filewasdeleted'              => 'Selle nimega fail on lisatud ja kustutatud hiljuti.
Kontrolli $1 enne jätkamist.',
'upload-wasdeleted'           => "'''Hoiatus: Sa laadid üles faili, mis on eelnevalt kustutatud.'''

Peaksid kaaluma, kas selle faili üleslaadimise jätkamine on sobilik.
Selle faili kustutamislogi on toodud siinsamas:",

'upload-proto-error'        => 'Vigane protokoll',
'upload-file-error'         => 'Sisemine viga',
'upload-file-error-text'    => 'Sisemine viga ilmnes, kui üritati luua ajutist faili serveris. 
Palun kontakteeru [[Special:ListUsers/sysop|administraatoriga]].',
'upload-misc-error'         => 'Tundmatu viga üleslaadimisel',
'upload-too-many-redirects' => 'URL sisaldas liiga palju ümbersuunamisi',
'upload-unknown-size'       => 'Tundmatu suurus',
'upload-http-error'         => 'HTTP-viga: $1',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Internetiaadress pole kättesaadav',
'upload-curl-error6-text'  => 'Etteantud internetiaadress ei ole kättesaadav.
Palun kontrolli, kas aadress on õige ja kas võrgukoht on üleval.',
'upload-curl-error28'      => 'Üleslaadimise ajalimiit',
'upload-curl-error28-text' => 'Võrgukohal läks vastamiseks liiga kaua.
Palun kontrolli kas võrgukoht on ikka üleval, oota natuke ja proovi uuesti.
Samuti võid proovida siis, kui võrgukoht on vähem hõivatud.',

'license'            => 'Litsents:',
'nolicense'          => 'pole valitud',
'license-nopreview'  => '(Eelvaade ei ole saadaval)',
'upload_source_url'  => '(avalikult ligipääsetav URL)',
'upload_source_file' => '(fail sinu arvutis)',

# Special:ListFiles
'listfiles-summary'     => 'See erileht kuvab kõik üleslaaditud failid.
Vaikimisi on kõige ees viimati üleslaaditud failid.
Tulba päisel klõpsamine muudab sortimist.',
'listfiles_search_for'  => 'Nimeotsing:',
'imgfile'               => 'fail',
'listfiles'             => 'Piltide loend',
'listfiles_date'        => 'Kuupäev',
'listfiles_name'        => 'Nimi',
'listfiles_user'        => 'Kasutaja',
'listfiles_size'        => 'Suurus',
'listfiles_description' => 'Kirjeldus',
'listfiles_count'       => 'Versioonid',

# File description page
'file-anchor-link'          => 'Pilt',
'filehist'                  => 'Faili ajalugu',
'filehist-help'             => 'Klõpsa Kuupäev/kellaaeg, et näha faili sel ajahetkel.',
'filehist-deleteall'        => 'kustuta kõik',
'filehist-deleteone'        => 'kustuta see',
'filehist-revert'           => 'taasta',
'filehist-current'          => 'viimane',
'filehist-datetime'         => 'Kuupäev/kellaaeg',
'filehist-thumb'            => 'Pisipilt',
'filehist-thumbtext'        => 'Pisipilt $1 versioonile',
'filehist-nothumb'          => 'Pisipilti ei ole',
'filehist-user'             => 'Kasutaja',
'filehist-dimensions'       => 'Mõõtmed',
'filehist-filesize'         => 'Faili suurus',
'filehist-comment'          => 'Kommentaar',
'filehist-missing'          => 'Fail puudub',
'imagelinks'                => 'Viited failile',
'linkstoimage'              => 'Sellele pildile {{PLURAL:$1|viitab järgmine lehekülg|viitavad järgmised leheküljed}}:',
'nolinkstoimage'            => 'Sellele pildile ei viita ükski lehekülg.',
'sharedupload'              => 'See fail pärineb allikast $1 ning võib olla kasutusel ka teistes projektides.',
'sharedupload-desc-there'   => 'See fail pärineb kesksest failivaramust $1. Palun vaata [$2 faili kirjelduse lehekülge], et saada rohkem teavet.',
'sharedupload-desc-here'    => 'See on jagutud fail allikast $1 ja seda saab kasutada ka teistes projektides. Faili sealne [$2 kirjeldus] on kuvatud allpool.',
'filepage-nofile'           => 'Sellenimelist faili ei ole.',
'filepage-nofile-link'      => 'Sellenimelist faili ei ole, kuid sa saad selle [$1 üles laadida].',
'uploadnewversion-linktext' => 'Laadi üles selle faili uus versioon',
'shared-repo-from'          => 'kohast $1',
'shared-repo'               => 'jagatud varamu',

# File reversion
'filerevert'                => 'Taasta $1',
'filerevert-legend'         => 'Taasta fail',
'filerevert-comment'        => 'Põhjus:',
'filerevert-defaultcomment' => 'Naaseti redaktsiooni juurde, mis loodi $1 kell $2',
'filerevert-submit'         => 'Taasta',
'filerevert-badversion'     => 'Failist ei ole kohalikku versiooni tagatud ajamarkeeringuga.',

# File deletion
'filedelete'                  => 'Kustuta $1',
'filedelete-legend'           => 'Kustuta fail',
'filedelete-intro'            => "Oled kustutamas faili '''[[Media:$1|$1]]''' ja kogu selle ajalugu.",
'filedelete-comment'          => 'Kustutamise põhjus:',
'filedelete-submit'           => 'Kustuta',
'filedelete-success'          => "'''$1''' on kustutatud.",
'filedelete-nofile'           => "Faili '''$1''' ei ole.",
'filedelete-otherreason'      => 'Muu/täiendav põhjus',
'filedelete-reason-otherlist' => 'Muu põhjus',
'filedelete-reason-dropdown'  => '*Harilikud kustutamise põhjused
** Autoriõiguste rikkumine
** Duplikaat',
'filedelete-edit-reasonlist'  => 'Redigeeri kustutamise põhjuseid',

# MIME search
'mimesearch'         => 'MIME otsing',
'mimesearch-summary' => 'Selle leheküljega saab faile otsida MIME tüübi järgi.
Sisesta kujul tüüp/alamtüüp, näiteks <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME tüüp:',
'download'           => 'laadi alla',

# Unwatched pages
'unwatchedpages' => 'Jälgimata lehed',

# List redirects
'listredirects' => 'Ümbersuunamised',

# Unused templates
'unusedtemplates'     => 'Kasutamata mallid',
'unusedtemplatestext' => 'See lehekülg loetleb kõik leheküljed nimeruumis {{ns:template}}, mida teistel lehekülgedel ei kasutata. Enne kustutamist palun kontrollige, kas siia pole muid linke.',
'unusedtemplateswlh'  => 'teised lingid',

# Random page
'randompage'         => 'Juhuslik artikkel',
'randompage-nopages' => 'Nimeruumis "$1" ei ole lehekülgi.',

# Random redirect
'randomredirect'         => 'Juhuslik ümbersuunamine',
'randomredirect-nopages' => 'Nimeruumis "$1" ei ole ümbersuunamislehekülgi.',

# Statistics
'statistics'                   => 'Arvandmestik',
'statistics-header-pages'      => 'Lehekülgede arvandmed',
'statistics-header-edits'      => 'Redigeerimise arvandmed',
'statistics-header-views'      => 'Vaatamise statistika',
'statistics-header-users'      => 'Kasutajate arvandmed',
'statistics-header-hooks'      => 'Muu statistika',
'statistics-articles'          => 'Sisulehekülgi',
'statistics-pages'             => 'Lehekülgi',
'statistics-pages-desc'        => 'Kõik lehed vikis, kaasa arvatud arutelulehed, ümbersuunamised jne',
'statistics-files'             => 'Üleslaaditud faile',
'statistics-edits'             => 'Redigeerimisi alates {{SITENAME}} loomisest',
'statistics-edits-average'     => 'Keskmiselt redigeerimisi lehekülje kohta',
'statistics-views-total'       => 'Lehekülje vaatamisi kokku',
'statistics-views-peredit'     => 'Vaatamisi redaktsiooni kohta',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue Tööjärje] pikkus',
'statistics-users'             => 'Registreeritud [[Special:ListUsers|kasutajaid]]',
'statistics-users-active'      => 'Aktiivseid kasutajaid',
'statistics-users-active-desc' => 'Kasutajad, kes on viimase {{PLURAL:$1|päeva|$1 päeva}} jooksul tegutsenud',
'statistics-mostpopular'       => 'Enim vaadatud leheküljed',

'disambiguations'      => 'Täpsustusleheküljed',
'disambiguationspage'  => 'Template:Täpsustuslehekülg',
'disambiguations-text' => "Loetletud leheküljed viitavad '''täpsustusleheküljele'''.
Selle asemel peaks nad olema lingitud sobivasse artiklisse.
Lehekülg loetakse täpsustusleheküljeks, kui see kasutab malli, millele viitab sõnum [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Kahekordsed ümbersuunamised',
'doubleredirectstext'        => 'Käesolev leht esitab loendi lehtedest, mis sisaldavad ümbersuunamisi teistele ümbersuunamislehtedele.
Igal real on ära toodud esimene ja teine ümbersuunamisleht ning samuti teise ümbersuunamislehe sihtmärk, mis tavaliselt on esialgse ümbersuunamise tegelik siht, millele see otse osutama peakski.
<s>Läbikriipsutatud</s> kirjed on kohendatud.',
'double-redirect-fixed-move' => '[[$1]] on teisaldatud, see suunab nüüd leheküljele [[$2]].',
'double-redirect-fixer'      => 'Ümbersuunamiste parandaja',

'brokenredirects'        => 'Vigased ümbersuunamised',
'brokenredirectstext'    => 'Järgmised leheküljed on ümber suunatud olematutele lehekülgedele:',
'brokenredirects-edit'   => 'redigeeri',
'brokenredirects-delete' => 'kustuta',

'withoutinterwiki'         => 'Keelelinkideta leheküljed',
'withoutinterwiki-summary' => 'Loetletud leheküljed ei viita erikeelsetele versioonidele.',
'withoutinterwiki-legend'  => 'Eesliide',
'withoutinterwiki-submit'  => 'Näita',

'fewestrevisions' => 'Leheküljed, kus on kõige vähem muudatusi tehtud',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bait|baiti}}',
'ncategories'             => '$1 {{PLURAL:$1|kategooria|kategooriat}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linki}}',
'nmembers'                => '$1 {{PLURAL:$1|liige|liiget}}',
'nrevisions'              => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'nviews'                  => '$1 {{PLURAL:$1|külastus|külastust}}',
'specialpage-empty'       => 'Vasteid ei leidu.',
'lonelypages'             => 'Viitamata artiklid',
'lonelypagestext'         => 'Järgmistele lehekülgedele ei ole linki ühelgi Viki leheküljel, samuti ei ole nad kasutusel teiste lehekülgede osana.',
'uncategorizedpages'      => 'Kategoriseerimata leheküljed',
'uncategorizedcategories' => 'Kategoriseerimata kategooriad',
'uncategorizedimages'     => 'Kategoriseerimata failid',
'uncategorizedtemplates'  => 'Kategoriseerimata mallid',
'unusedcategories'        => 'Kasutamata kategooriad',
'unusedimages'            => 'Kasutamata pildid',
'popularpages'            => 'Loetumad artiklid',
'wantedcategories'        => 'Kõige oodatumad kategooriad',
'wantedpages'             => 'Kõige oodatumad artiklid',
'wantedpages-badtitle'    => 'Tulemuste seas on vigane pealkiri: $1',
'wantedfiles'             => 'Kõige oodatumad failid',
'wantedtemplates'         => 'Kõige oodatumad mallid',
'mostlinked'              => 'Kõige viidatumad leheküljed',
'mostlinkedcategories'    => 'Kõige viidatumad kategooriad',
'mostlinkedtemplates'     => 'Kõige viidatumad mallid',
'mostcategories'          => 'Enim kategoriseeritud artiklid',
'mostimages'              => 'Kõige kasutatumad failid',
'mostrevisions'           => 'Kõige pikema redigeerimislooga artiklid',
'prefixindex'             => 'Kõik pealkirjad eesliitega',
'shortpages'              => 'Lühikesed artiklid',
'longpages'               => 'Pikad artiklid',
'deadendpages'            => 'Edasipääsuta artiklid',
'deadendpagestext'        => 'Järgmised leheküljed ei viita ühelegi teisele Viki leheküljele.',
'protectedpages'          => 'Kaitstud leheküljed',
'protectedpages-indef'    => 'Ainult määramata ajani kaitstud',
'protectedpages-cascade'  => 'Ainult kaskaadkaitsega',
'protectedpagestext'      => 'Järgnevad leheküljed on teisaldamise või redigeerimise eest kaitstud',
'protectedpagesempty'     => 'Selliste parameetritega ei ole praegu ühtegi lehekülge kaitstud.',
'protectedtitles'         => 'Kaitstud pealkirjad',
'protectedtitlestext'     => 'Järgnevad pealkirjad on lehekülje loomise eest kaitstud',
'protectedtitlesempty'    => 'Hetkel pole ükski pealkiri kaitstud.',
'listusers'               => 'Kasutajad',
'listusers-editsonly'     => 'Näita vaid kasutajaid, kes on teinud muudatusi',
'listusers-creationsort'  => 'Sorteeri konto loomise aja järgi',
'usereditcount'           => '$1 {{PLURAL:$1|redigeerimine|redigeerimist}}',
'usercreated'             => 'Konto loomise aeg: $1 kell $2',
'newpages'                => 'Uued leheküljed',
'newpages-username'       => 'Kasutajanimi:',
'ancientpages'            => 'Kõige vanemad artiklid',
'move'                    => 'Teisalda',
'movethispage'            => 'Muuda pealkirja',
'unusedimagestext'        => 'Pange palun tähele, et teised veebisaidid võivad linkida failile otselingiga ja seega võivad siin toodud failid olla ikkagi aktiivses kasutuses.',
'unusedcategoriestext'    => 'Need kategooriad pole ühesgi artiklis või teises kategoorias kasutuses.',
'notargettitle'           => 'Puudub sihtlehekülg',
'notargettext'            => 'Sa ei ole esitanud sihtlehekülge ega kasutajat, kelle kallal seda operatsiooni toime panna.',
'nopagetitle'             => 'Sihtpunktiks määratud lehekülge ei ole',
'nopagetext'              => 'Lehekülg, mille sa sihtpunktiks määrasid, ei eksisteeri.',
'pager-newer-n'           => '{{PLURAL:$1|uuem 1|uuemad $1}}',
'pager-older-n'           => '{{PLURAL:$1|vanem 1|vanemad $1}}',
'suppress'                => 'Varjamine',

# Book sources
'booksources'               => 'Otsi raamatut',
'booksources-search-legend' => 'Otsi raamatut',
'booksources-go'            => 'Mine',
'booksources-text'          => 'Allpool on linke teistele lehekülgedele, kus müüakse uusi ja kasutatud raamatuid. Lehekülgedel võib olla ka lisainfot raamatute kohta:',
'booksources-invalid-isbn'  => 'Antud ISBN-number ei ole korrektne; kontrolli algallikast kopeerides vigu.',

# Special:Log
'specialloguserlabel'  => 'Kasutaja:',
'speciallogtitlelabel' => 'Pealkiri:',
'log'                  => 'Logid',
'all-logs-page'        => 'Kõik avalikud logid',
'alllogstext'          => 'See on võrgukoha {{SITENAME}} kõigi olemasolevate logide ühendkuva.
Valiku kitsendamiseks vali logitüüp, sisesta kasutajanimi (tõstutundlik) või huvipakkuva lehekülje pealkiri (samuti tõstutundlik).',
'logempty'             => 'Logis puuduvad vastavad kirjed.',
'log-title-wildcard'   => 'Selle tekstiga algavad pealkirjad',

# Special:AllPages
'allpages'          => 'Kõik leheküljed',
'alphaindexline'    => '$1 kuni $2',
'nextpage'          => 'Järgmine lehekülg ($1)',
'prevpage'          => 'Eelmine lehekülg ($1)',
'allpagesfrom'      => 'Näita lehti alates pealkirjast:',
'allpagesto'        => 'Näita lehti kuni pealkirjani:',
'allarticles'       => 'Kõik artiklid',
'allinnamespace'    => 'Kõik artiklid ($1 nimeruum)',
'allnotinnamespace' => 'Kõik artiklid (mis ei kuulu $1 nimeruumi)',
'allpagesprev'      => 'Eelmised',
'allpagesnext'      => 'Järgmised',
'allpagessubmit'    => 'Näita',
'allpagesprefix'    => 'Kuva leheküljed eesliitega:',

# Special:Categories
'categories'                    => 'Kategooriad',
'categoriespagetext'            => 'Vikis on {{PLURAL:$1|järgmine kategooria|järgmised kategooriad}}.
Siin ei näidata [[Special:UnusedCategories|kasutamata kategooriaid]].
Vaata ka [[Special:WantedCategories|puuduvaid kategooriaid]].',
'categoriesfrom'                => 'Näita kategooriaid alates:',
'special-categories-sort-count' => 'sorteeri hulga järgi',
'special-categories-sort-abc'   => 'sorteeri tähestikuliselt',

# Special:DeletedContributions
'deletedcontributions'             => 'Kasutaja kustutatud kaastööd',
'deletedcontributions-title'       => 'Kustutatud muudatused',
'sp-deletedcontributions-contribs' => 'kaastöö',

# Special:LinkSearch
'linksearch'      => 'Välislingid',
'linksearch-pat'  => 'Otsimisvorm:',
'linksearch-ns'   => 'Nimeruum:',
'linksearch-ok'   => 'Otsi',
'linksearch-text' => 'Metamärgina võib kasutada tärni, näiteks "*.wikipedia.org".

Toetatud protokollid: <tt>$1</tt>',
'linksearch-line' => '$1 on lingitud leheküljelt $2',

# Special:ListUsers
'listusersfrom'      => 'Näita kasutajaid alustades:',
'listusers-submit'   => 'Näita',
'listusers-noresult' => 'Kasutajat ei leitud.',
'listusers-blocked'  => '(blokeeritud)',

# Special:ActiveUsers
'activeusers'          => 'Aktiivsete kasutajate nimekiri',
'activeusers-count'    => '$1 {{PLURAL:$1|hiljutine muudatus|hiljutist muudatust}}',
'activeusers-from'     => 'Näita kasutajaid alates:',
'activeusers-noresult' => 'Kasutajaid ei leidunud.',

# Special:Log/newusers
'newuserlogpage'              => 'Kasutaja loomise logi',
'newuserlogpagetext'          => 'See logi sisaldab infot äsja loodud uute kasutajate kohta.',
'newuserlog-byemail'          => 'parool saadetud e-postiga',
'newuserlog-create-entry'     => 'Uus kasutaja',
'newuserlog-create2-entry'    => 'lõi uue konto $1',
'newuserlog-autocreate-entry' => 'Konto loodud automaatselt',

# Special:ListGroupRights
'listgrouprights'                      => 'Kasutajarühma õigused',
'listgrouprights-summary'              => 'Siin on loetletud selle viki kasutajarühmad ja rühmaga seotud õigused.
Üksikute õiguste kohta võib olla [[{{MediaWiki:Listgrouprights-helppage}}|täiendavat teavet]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Väljaantud õigus</span>
* <span class="listgrouprights-revoked">Äravõetud õigus</span>',
'listgrouprights-group'                => 'Rühm',
'listgrouprights-rights'               => 'Õigused',
'listgrouprights-helppage'             => 'Help:Rühma õigused',
'listgrouprights-members'              => '(liikmete loend)',
'listgrouprights-addgroup'             => 'Lisada liikmeid {{PLURAL:$2|rühma|rühmadesse}}: $1',
'listgrouprights-removegroup'          => 'Eemaldada liikmeid {{PLURAL:$2|rühmast|rühmadest}}: $1',
'listgrouprights-addgroup-all'         => 'Kõigisse rühmadesse liikmeid lisada',
'listgrouprights-removegroup-all'      => 'Kõigist rühmadest liikmeid eemaldada',
'listgrouprights-addgroup-self'        => 'Lisada enda konto {{PLURAL:$2|rühma|rühmadesse}}: $1',
'listgrouprights-removegroup-self'     => 'Eemaldada enda konto {{PLURAL:$2|rühmast|rühmadest}}: $1',
'listgrouprights-addgroup-self-all'    => 'Oma konto kõigisse rühmadesse lisada',
'listgrouprights-removegroup-self-all' => 'Eemaldada ennast kõigist rühmadest',

# E-mail user
'mailnologin'      => 'Saatja aadress puudub',
'mailnologintext'  => 'Te peate olema [[Special:UserLogin|sisse logitud]] ja teil peab [[Special:Preferences|eelistustes]] olema kehtiv e-posti aadress, et saata teistele kasutajatele e-kirju.',
'emailuser'        => 'Saada sellele kasutajale e-kiri',
'emailpage'        => 'Saada kasutajale e-kiri',
'emailpagetext'    => 'Kui see kasutaja on oma eelistuste lehel sisestanud e-posti aadressi, siis saate alloleva vormi kaudu talle kirja saata. Et kasutaja saaks vastata, täidetakse kirja saatja väli "kellelt" e-posti aadressiga, mille olete sisestanud [[Special:Preferences|oma eelistuste lehel]].',
'defemailsubject'  => 'E-kiri lehelt {{SITENAME}}',
'noemailtitle'     => 'E-posti aadressi ei ole',
'noemailtext'      => 'See kasutaja ei ole määranud kehtivat e-posti aadressi.',
'nowikiemailtitle' => 'E-kirja saatmine ei ole lubatud',
'nowikiemailtext'  => 'See kasutaja ei soovi e-posti teistelt kasutajatelt.',
'email-legend'     => 'Saada e-kiri {{GRAMMAR:genitive|{{SITENAME}}}} kasutajale',
'emailfrom'        => 'Kellelt:',
'emailto'          => 'Kellele:',
'emailsubject'     => 'Pealkiri:',
'emailmessage'     => 'Sõnum:',
'emailsend'        => 'Saada',
'emailccme'        => 'Saada mulle koopia.',
'emailccsubject'   => 'Koopia sinu sõnumist kasutajale $1: $2',
'emailsent'        => 'E-post saadetud',
'emailsenttext'    => 'Teie sõnum on saadetud.',

# Watchlist
'watchlist'            => 'Jälgimisloend',
'mywatchlist'          => 'Jälgimisloend',
'watchlistfor'         => "('''$1''' jaoks)",
'nowatchlist'          => 'Teie jälgimisloend on tühi.',
'watchlistanontext'    => 'Et näha ja muuta oma jälgimisloendit, peate $1.',
'watchnologin'         => 'Ei ole sisse logitud',
'watchnologintext'     => 'Jälgimisloendi muutmiseks peate [[Special:UserLogin|sisse logima]].',
'addedwatch'           => 'Lisatud jälgimisloendile',
'addedwatchtext'       => 'Lehekülg "<nowiki>$1</nowiki>" on lisatud Teie [[Special:Watchlist|jälgimisloendile]].

Edasised muudatused käesoleval lehel ja sellega seotud aruteluküljel reastatakse jälgimisloendis ning [[Special:RecentChanges|viimaste muudatuste lehel]] tuuakse jälgitava lehe pealkiri esile <b>rasvase</b> kirja abil.

Kui tahad seda lehte hiljem jälgimisloendist eemaldada, klõpsa päisenupule "Lõpeta jälgimine".',
'removedwatch'         => 'Jälgimisloendist kustutatud',
'removedwatchtext'     => 'Artikkel "[[:$1]]" on jälgimisloendist kustutatud.',
'watch'                => 'Jälgi',
'watchthispage'        => 'Jälgi seda artiklit',
'unwatch'              => 'Lõpeta jälgimine',
'unwatchthispage'      => 'Ära jälgi',
'notanarticle'         => 'Pole artikkel',
'notvisiblerev'        => 'Redaktsioon on kustutatud',
'watchnochange'        => 'Valitud perioodi jooksul ei ole üheski jälgitavas artiklis muudatusi tehtud.',
'watchlist-details'    => 'Jälgimisloendis on {{PLURAL:$1|$1 lehekülg|$1 lehekülge}} (ei arvestata arutelulehekülgi).',
'wlheader-enotif'      => '* E-posti teel teavitamine on aktiveeritud.',
'wlheader-showupdated' => "* Leheküljed, mida on muudetud peale sinu viimast külastust, on '''rasvases kirjas'''",
'watchmethod-recent'   => 'jälgitud lehekülgedel tehtud viimaste muudatuste läbivaatamine',
'watchmethod-list'     => 'jälgitavate lehekülgede viimased muudatused',
'watchlistcontains'    => 'Sinu jälgimisloendis on $1 {{PLURAL:$1|artikkel|artiklit}}.',
'wlnote'               => "Allpool on {{PLURAL:$1|viimane muudatus|viimased '''$1''' muudatust}} viimase {{PLURAL:$2|tunni|'''$2''' tunni}} jooksul.",
'wlshowlast'           => 'Näita viimast $1 tundi $2 päeva. $3',
'watchlist-options'    => 'Jälgimisloendi võimalused',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'jälgin...',
'unwatching' => 'Jälgimise lõpetamine...',

'enotif_reset'                 => 'Märgi kõik lehed loetuks',
'enotif_newpagetext'           => 'See on uus lehekülg.',
'enotif_impersonal_salutation' => '{{SITENAME}} kasutaja',
'changed'                      => 'muudetud',
'created'                      => 'lehekülg loodud',
'enotif_lastvisited'           => 'Aadressil $1 on kõik muudatused su viimase külastuse järel.',
'enotif_lastdiff'              => 'Muudatus on leheküljel $1.',
'enotif_anon_editor'           => 'anonüümne kasutaja $1',

# Delete
'deletepage'             => 'Kustuta lehekülg',
'confirm'                => 'Kinnita',
'excontent'              => "sisu oli: '$1'",
'excontentauthor'        => "sisu oli: '$1' (ja ainuke kirjutaja oli '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "sisu enne lehekülje tühjendamist: '$1'",
'exblank'                => 'lehekülg oli tühi',
'delete-confirm'         => 'Kustuta "$1"',
'delete-legend'          => 'Kustuta',
'historywarning'         => 'Hoiatus: leheküljel, mida tahate kustutada, on ajalugu:&nbsp;',
'confirmdeletetext'      => 'Sa oled andmebaasist jäädavalt kustutamas lehte või pilti koos kogu tema ajalooga. Palun kinnita, et sa tahad seda tõepoolest teha, et sa mõistad tagajärgi ja et sinu tegevus on kooskõlas siinse [[{{MediaWiki:Policy-url}}|sisekorraga]].',
'actioncomplete'         => 'Toiming sooritatud',
'actionfailed'           => 'Tegevus ebaõnnestus',
'deletedtext'            => '"<nowiki>$1</nowiki>" on kustutatud. $2 lehel on nimekiri viimastest kustutatud lehekülgedest.',
'deletedarticle'         => 'kustutas lehekülje "[[$1]]"',
'suppressedarticle'      => 'varjas lehekülje "[[$1]]"',
'dellogpage'             => 'Kustutatud_leheküljed',
'dellogpagetext'         => 'Allpool on esitatud nimekiri viimastest kustutamistest.
Kõik toodud kellaajad järgivad serveriaega.',
'deletionlog'            => 'Kustutatud leheküljed',
'reverted'               => 'Pöörduti tagasi varasemale versioonile',
'deletecomment'          => 'Kustutamise põhjus',
'deleteotherreason'      => 'Muu/täiendav põhjus:',
'deletereasonotherlist'  => 'Muu põhjus',
'deletereason-dropdown'  => '*Harilikud kustutamise põhjused
** Autori palve
** Autoriõiguste rikkumine
** Vandalism',
'delete-edit-reasonlist' => 'Redigeeri kustutamise põhjuseid',

# Rollback
'rollback'         => 'Tühista muudatused',
'rollback_short'   => 'Tühista',
'rollbacklink'     => 'tühista',
'rollbackfailed'   => 'Muudatuste tühistamine ebaõnnestus',
'cantrollback'     => 'Ei saa muudatusi eemaldada, sest viimane kaastööline on artikli ainus autor.',
'editcomment'      => "Redaktsiooni kokkuvõte: \"''\$1''\".",
'revertpage'       => 'Tühistati kasutaja [[Special:Contributions/$2|$2]] ([[User talk:$2|arutelu]]) tehtud muudatused ning pöörduti tagasi viimasele muudatusele, mille tegi [[User:$1|$1]]',
'rollback-success' => 'Tühistati $1 muudatus; 
pöörduti tagasi viimasele muudatusele, mille tegi $2.',
'sessionfailure'   => 'Sinu sisselogimisseansiga näib probleem olevat.
See toiming on seansiärandamise vastase ettevaatusabinõuna tühistatud.
Mine tagasi eelmisele leheküljele ja taaslaadi see, seejärel proovi uuesti.',

# Protect
'protectlogpage'              => 'Kaitsmise logi',
'protectlogtext'              => 'Allpool on loetletud lehekülgede kaitsmised ja kaitsete eemaldamised. Praegu kaitstud lehekülgi vaata [[Special:ProtectedPages|kaitstud lehtede loetelust]].',
'protectedarticle'            => 'kaitses lehekülje "[[$1]]"',
'modifiedarticleprotection'   => 'muutis lehekülje "[[$1]]" kaitsemäära',
'unprotectedarticle'          => 'eemaldas lehekülje "[[$1]]" kaitse',
'movedarticleprotection'      => 'teisaldas kaitsesätted läheküljelt "[[$2]]" leheküljele "[[$1]]"',
'protect-title'               => 'Muuda lehekülje "$1" kaitsemäära',
'prot_1movedto2'              => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla',
'protect-legend'              => 'Kinnita kaitsmine',
'protectcomment'              => 'Põhjus',
'protectexpiry'               => 'Aegub:',
'protect_expiry_invalid'      => 'Sobimatu aegumise tähtaeg.',
'protect_expiry_old'          => 'Aegumise tähtaeg on minevikus.',
'protect-unchain'             => 'Võimalda lehekülje teisaldamist.',
'protect-text'                => "Siin võite vaadata ja muuta lehekülje '''<nowiki>$1</nowiki>''' kaitsesätteid.",
'protect-locked-blocked'      => "Blokeerituna ei saa muuta kaitstuse taset.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-locked-dblock'       => "Kaitstuse taset ei saa muuta, sest andmebaas on lukustatud.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-locked-access'       => "Teie konto ei oma õiguseid muuta lehekülje kaitstuse taset.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-cascadeon'           => 'See lehekülg on kaitstud, kuna ta on kasutusel {{PLURAL:$1|järgmisel leheküljel|järgmistel lehekülgedel}}, mis on omakorda kaskaadkaitse all.
Sa saad muuta selle lehekülje kaitse staatust, kuid see ei mõjuta kaskaadkaitset.',
'protect-default'             => 'Luba kõigile kasutajatele',
'protect-fallback'            => 'Nõuab "$1" õiguseid',
'protect-level-autoconfirmed' => 'Blokeeri uued ja registreerimata kasutajad',
'protect-level-sysop'         => 'Ainult ülemad',
'protect-summary-cascade'     => 'kaskaad',
'protect-expiring'            => 'aegub $1 (UTC)',
'protect-expiry-indefinite'   => 'määramatu',
'protect-cascade'             => 'Kaitse lehekülgi, mis on lülitatud käesoleva lehekülje koosseisu (kaskaadkaitse)',
'protect-cantedit'            => 'Te ei saa muuta selle lehekülje kaitstuse taset, sest Teile pole selleks luba antud.',
'protect-othertime'           => 'Muu aeg:',
'protect-othertime-op'        => 'muu aeg',
'protect-existing-expiry'     => 'Kehtiv aegumisaeg: $2 kell $3',
'protect-otherreason'         => 'Muu/täiendav põhjus:',
'protect-otherreason-op'      => 'muu/täiendav põhjus',
'protect-dropdown'            => '*Tavalised kaitsmise põhjused
** Liigne vandalism
** Liigne spämmimine
** Counter-productive edit warring
** Kõrge liiklusega lehekülg',
'protect-edit-reasonlist'     => 'Muudatuste eest kaitsmise põhjused',
'protect-expiry-options'      => '1 tund:1 hour,1 päev:1 day,1 nädal:1 week,2 nädalat: 2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite',
'restriction-type'            => 'Lubatud:',
'restriction-level'           => 'Kaitsmise tase:',
'minimum-size'                => 'Min suurus',
'maximum-size'                => 'Max suurus:',
'pagesize'                    => '(baiti)',

# Restrictions (nouns)
'restriction-edit'   => 'Redigeerimine',
'restriction-move'   => 'Teisaldamine',
'restriction-create' => 'Loomine',
'restriction-upload' => 'Laadi üles',

# Restriction levels
'restriction-level-sysop'         => 'täielikult kaitstud',
'restriction-level-autoconfirmed' => 'poolkaitstud',
'restriction-level-all'           => 'kõik tasemed',

# Undelete
'undelete'                     => 'Taasta kustutatud lehekülg',
'undeletepage'                 => 'Kuva ja taasta kustutatud lehekülgi',
'undeletepagetitle'            => "'''Kustutatud redaktsioonid leheküljest [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Vaata kustutatud lehekülgi',
'undeletepagetext'             => '{{PLURAL:$1|Järgnev lehekülg on kustutatud|Järgnevad leheküljed on kustutatud}}, kuid arhiivis veel olemas ja taastatavad. Arhiivi sisu kustutatakse perioodiliselt.',
'undelete-fieldset-title'      => 'Taasta redigeerimised',
'undeleteextrahelp'            => "Kogu lehe ja selle ajaloo taastamiseks jätke kõik linnukesed tühjaks ja vajutage '''''Taasta'''''.
Et taastada valikuliselt, tehke linnukesed kastidesse, mida soovite taastada ja vajutage '''''Taasta'''''.
Nupu '''''Tühjenda''''' vajutamine tühjendab põhjusevälja ja eemaldab kõik linnukesed.",
'undeleterevisions'            => '$1 arhiveeritud {{PLURAL:$1|redaktsioon|redaktsiooni}}.',
'undeletehistory'              => 'Kui taastate lehekülje, taastuvad kõik versioonid artikli ajaloona. 
Kui vahepeal on loodud uus samanimeline lehekülg, ilmuvad taastatud versioonid varasema ajaloona.',
'undeletehistorynoadmin'       => 'See lehekülg on kustutatud.
Kustutamise põhjus ning selle lehekülje kustutamiseelne redigeerimislugu on näha allolevas kokkuvõttes.
Lehekülje kustutamiseelsed redaktsioonid on kättesaadavad ainult ülematele.',
'undelete-revision'            => 'Lehekülje $1 kustutatud redaktsioonid, mille autor on $3, seisuga $4 kell $5.',
'undeleterevision-missing'     => 'Vigane või puuduv redaktsioon.
Link võib olla kõlbmatu või redaktsioon võib olla taastatud või arhiivist eemaldatud.',
'undelete-nodiff'              => 'Varasemat redaktsiooni ei leidunud.',
'undeletebtn'                  => 'Taasta',
'undeletelink'                 => 'vaata/taasta',
'undeleteviewlink'             => 'vaata',
'undeletereset'                => 'Tühjenda',
'undeleteinvert'               => 'Pööra valim teistpidi',
'undeletecomment'              => 'Põhjus:',
'undeletedarticle'             => '"$1" taastatud',
'undeletedrevisions'           => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}} taastatud',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 redaktsioon|$1 redaktsiooni}} ja {{PLURAL:$2|1 fail|$2 faili}} taastatud',
'undeletedfiles'               => '{{PLURAL:$1|1 fail|$1 faili}} taastatud',
'cannotundelete'               => 'Taastamine ebaõnnestus; keegi teine võis lehe juba taastada.',
'undeletedpage'                => "<big>'''$1 on taastatud'''</big>

[[Special:Log/delete|Kustutamise logist]] võib leida loendi viimastest kustutamistest ja taastamistest.",
'undelete-header'              => 'Hiljuti kustutatud leheküljed leiad [[Special:Log/delete|kustutamislogist]].',
'undelete-search-box'          => 'Otsi kustutatud lehekülgi',
'undelete-search-prefix'       => 'Näita lehekülgi, mille pealkiri algab nii:',
'undelete-search-submit'       => 'Otsi',
'undelete-no-results'          => 'Kustutatud lehekülgede arhiivist sellist lehekülge ei leidunud.',
'undelete-filename-mismatch'   => 'Failiversiooni ajatempliga $1 ei saa taastada, sest failinimed ei klapi.',
'undelete-bad-store-key'       => 'Failiversiooni ajatempliga $1 ei saa taastada, sest faili ei olnud enne kustutamist.',
'undelete-cleanup-error'       => 'Kasutamata arhiivifaili "$1" kustutamine ebaõnnestus.',
'undelete-missing-filearchive' => 'Failiarhiivi tunnusega $1 ei saa taastada, sest seda pole andmebaasis.
Võimalik, et see on juba taastatud.',
'undelete-error-short'         => 'Faili $1 taastamine ebaõnnestus',
'undelete-error-long'          => 'Faili taastamine ebaõnnestus:

$1',
'undelete-show-file-confirm'   => 'Kas oled kindel, et soovid näha kustutatud versiooni failist <nowiki>$1</nowiki>, mis salvestati $2 kell $3?',
'undelete-show-file-submit'    => 'Jah',

# Namespace form on various pages
'namespace'      => 'Nimeruum:',
'invert'         => 'Näita kõiki peale valitud nimeruumi',
'blanknamespace' => '(Artiklid)',

# Contributions
'contributions'       => 'Kasutaja kaastööd',
'contributions-title' => 'Kasutaja $1 kaastööd',
'mycontris'           => 'Kaastöö',
'contribsub2'         => 'Kasutaja "$1 ($2)" jaoks',
'nocontribs'          => 'Antud kriteeriumile vastavaid muudatusi ei leidnud.',
'uctop'               => ' (üles)',
'month'               => 'Alates kuust (ja varasemad):',
'year'                => 'Alates aastast (ja varasemad):',

'sp-contributions-newbies'       => 'Näita ainult uute kasutajate kaastööd.',
'sp-contributions-newbies-sub'   => 'Uued kasutajad',
'sp-contributions-newbies-title' => 'Uute kasutajate kaastööd',
'sp-contributions-blocklog'      => 'Blokeerimise logi',
'sp-contributions-deleted'       => 'kustutatud kasutaja kaastööd',
'sp-contributions-logs'          => 'logid',
'sp-contributions-talk'          => 'arutelu',
'sp-contributions-userrights'    => 'kasutaja õiguste muutmine',
'sp-contributions-search'        => 'Otsi kaastöid',
'sp-contributions-username'      => 'IP-aadress või kasutajanimi:',
'sp-contributions-submit'        => 'Otsi',

# What links here
'whatlinkshere'            => 'Lingid siia',
'whatlinkshere-title'      => 'Leheküljed, mis viitavad lehele "$1"',
'whatlinkshere-page'       => 'Lehekülg:',
'linkshere'                => "Lehele '''[[:$1]]''' viitavad järgmised leheküljed:",
'nolinkshere'              => "Lehele '''[[:$1]]''' ei viita ükski lehekülg.",
'nolinkshere-ns'           => 'Leheküljele <strong>[[:$1]]</strong> ei ole valitud nimeruumis linke.',
'isredirect'               => 'ümbersuunamislehekülg',
'istemplate'               => 'kasutamine mallina',
'isimage'                  => 'link pildile',
'whatlinkshere-prev'       => '{{PLURAL:$1|eelmised|eelmised $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|järgmised|järgmised $1}}',
'whatlinkshere-links'      => '← lingid',
'whatlinkshere-hideredirs' => '$1 ümbersuunamised',
'whatlinkshere-hidetrans'  => '$1 mallina kasutamised',
'whatlinkshere-hidelinks'  => '$1 lingid',
'whatlinkshere-hideimages' => '$1 pildilingid',
'whatlinkshere-filters'    => 'Filtrid',

# Block/unblock
'blockip'                      => 'Blokeeri IP-aadress',
'blockip-legend'               => 'Blokeeri kasutaja',
'blockiptext'                  => "See vorm on kirjutamisõiguste blokeerimiseks konkreetselt IP-aadressilt.
'''Seda tohib teha ainult vandalismi vältimiseks ning kooskõlas [[{{MediaWiki:Policy-url}}|{{SITENAME}} sisekorraga]]'''.
Kindlasti tuleb täita ka väli \"põhjus\", paigutades sinna näiteks viited konkreetsetele lehekülgedele, mida rikuti.",
'ipaddress'                    => 'IP-aadress',
'ipadressorusername'           => 'IP-aadress või kasutajanimi',
'ipbexpiry'                    => 'Kehtivus',
'ipbreason'                    => 'Põhjus',
'ipbreasonotherlist'           => 'Muul põhjusel',
'ipbreason-dropdown'           => '*Tavalised blokeerimise põhjused
** Lehtedelt sisu kustutamine
** Sodimine
** Taunitav käitumine, isiklikud rünnakud
** Mittesobiv kasutajanimi
** Spämmi levitamine
** Vale info levitamine',
'ipbanononly'                  => 'Blokeeri ainult anonüümsed kasutajad',
'ipbcreateaccount'             => 'Takista konto loomist',
'ipbemailban'                  => 'Takista kasutajal e-kirjade saatmine',
'ipbenableautoblock'           => 'Blokeeri automaatselt viimane IP-aadress, mida see kasutaja kasutas, ja ka järgnevad, mille alt ta võib proovida kaastööd teha.',
'ipbsubmit'                    => 'Blokeeri see aadress',
'ipbother'                     => 'Muu tähtaeg',
'ipboptions'                   => '2 tundi:2 hours,1 päev:1 day,3 päeva:3 days,1 nädal:1 week,2 nädalat:2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite',
'ipbotheroption'               => 'muu tähtaeg',
'ipbotherreason'               => 'Muu/täiendav põhjus:',
'ipbhidename'                  => 'Peida kasutajatunnus muudatustest ja loenditest',
'ipbwatchuser'                 => 'Jälgi selle kasutaja lehekülge ja arutelu',
'ipballowusertalk'             => 'Luba kasutajal vaatamata blokeeringule, siiski muuta enese arutelu lehekülge',
'ipb-change-block'             => 'Blokeeri uuesti samade sätete alusel',
'badipaddress'                 => 'Vigane IP-aadress',
'blockipsuccesssub'            => 'Blokeerimine õnnestus',
'blockipsuccesstext'           => '[[Special:Contributions/$1|$1]] on blokeeritud.<br />
Kehtivaid blokeeringuid vaata [[Special:IPBlockList|blokeeringute loendist]].',
'ipb-edit-dropdown'            => 'Muuda blokeeringu põhjuseid',
'ipb-unblock-addr'             => 'Kustuta $1 blokeering',
'ipb-unblock'                  => 'Vabasta kasutaja või IP-aadress blokeeringust',
'ipb-blocklist-addr'           => 'Kasutaja $1 blokeeringud',
'ipb-blocklist'                => 'Vaata kehtivaid keelde',
'ipb-blocklist-contribs'       => '$1 kaastööd',
'unblockip'                    => 'Lõpeta IP-aadressi blokeerimine',
'unblockiptext'                => 'Kasutage allpool olevat vormi redigeerimisõiguste taastamiseks varem blokeeritud IP aadressile.',
'ipusubmit'                    => 'Eemalda see blokeering',
'unblocked'                    => '[[User:$1|$1]] blokeering võeti maha.',
'unblocked-id'                 => 'Blokeerimine $1 on lõpetatud',
'ipblocklist'                  => 'Blokeeritud IP-aadresside ja kasutajakontode loend',
'ipblocklist-legend'           => 'Leia blokeeritud kasutaja',
'ipblocklist-username'         => 'Kasutajanimi või IP-aadress:',
'ipblocklist-sh-userblocks'    => '$1 kasutajanimed',
'ipblocklist-sh-tempblocks'    => '$1 ajutised blokeeringud',
'ipblocklist-sh-addressblocks' => '$1 IP-aadressid',
'ipblocklist-submit'           => 'Otsi',
'blocklistline'                => '$1, $2 blokeeris kasutaja $3 ($4)',
'infiniteblock'                => 'igavene',
'expiringblock'                => 'aegub $1 $2',
'anononlyblock'                => 'ainult nimetuna',
'noautoblockblock'             => 'IP-aadressi ei blokita automaatselt',
'createaccountblock'           => 'kontode loomine keelatud',
'emailblock'                   => 'e-kirjade saatmine keelatud',
'blocklist-nousertalk'         => 'ei saa oma arutelulehte muuta',
'ipblocklist-empty'            => 'Blokeerimiste loend on tühi.',
'ipblocklist-no-results'       => 'Nõutud IP-aadress või kasutajatunnus ei ole blokeeritud.',
'blocklink'                    => 'blokeeri',
'unblocklink'                  => 'lõpeta blokeerimine',
'change-blocklink'             => 'muuda blokeeringut',
'contribslink'                 => 'kaastöö',
'autoblocker'                  => 'Automaatselt blokeeritud, kuna [[User:$1|$1]] on hiljuti teie IP-aadressi kasutanud. Põhjus: $2',
'blocklogpage'                 => 'Blokeerimise logi',
'blocklog-fulllog'             => 'Täielik blokeerimise logi',
'blocklogentry'                => 'blokeeris kasutaja [[$1]]. Blokeeringu aegumistähtaeg on $2 $3',
'reblock-logentry'             => 'muutis kasutaja või IP-aadressi [[$1]] blokeeringu sätteid. Blokeering aegumistähtaeg: $2. Põhjus: $3',
'blocklogtext'                 => 'See on kasutajate blokeerimiste ja blokeeringute eemaldamiste nimekiri. Automaatselt blokeeritud IP aadresse siin ei näidata. Hetkel aktiivsete blokeeringute ja redigeerimiskeeldude nimekirja vaata [[Special:IPBlockList|IP blokeeringute nimekirja]] leheküljelt.',
'unblocklogentry'              => 'eemaldas kasutaja $1 blokeeringu',
'block-log-flags-anononly'     => 'ainult anonüümsed kasutajad',
'block-log-flags-nocreate'     => 'kontode loomine on blokeeritud',
'block-log-flags-noautoblock'  => 'ei blokeerita automaatselt',
'block-log-flags-noemail'      => 'e-mail blokeeritud',
'block-log-flags-nousertalk'   => 'ei saa muuta enda arutelulehte',
'block-log-flags-hiddenname'   => 'kasutajanimi peidetud',
'ipb_expiry_invalid'           => 'Vigane aegumise tähtaeg.',
'ipb_expiry_temp'              => 'Peidetud kasutajanime blokeeringud peavad olema alalised.',
'ipb_already_blocked'          => '"$1" on juba blokeeritud.',
'ipb-needreblock'              => '==Juba blokeeritud==
$1 on juba blokeeritud.
Kas soovid muuta blokeeringu sätteid?',
'blockme'                      => 'Blokeeri mind',
'proxyblocker-disabled'        => 'See funktsioon ei toimi.',
'proxyblockreason'             => 'Teie IP aadress on blokeeritud, sest see on anonüümne proxy server. Palun kontakteeruga oma internetiteenuse pakkujaga või tehnilise toega ning informeerige neid sellest probleemist.',
'proxyblocksuccess'            => 'Tehtud.',
'cant-block-while-blocked'     => 'Teisi kasutajaid ei saa blokeerida, kui oled ise blokeeritud.',

# Developer tools
'lockdb'              => 'Lukusta andmebaas',
'unlockdb'            => 'Tee andmebaas lukust lahti',
'unlockdbtext'        => 'Andmebaasi lukust lahti tegemine taastab kõigi kasutajate võimaluse toimetada lehekülgi, muuta oma eelistusi, toimetada oma jälgimisloendeid ja muud, mis nõuab muudatusi andmebaasis.
Palun kinnita, et sa tahad seda teha.',
'lockconfirm'         => 'Jah, ma soovin andmebaasi lukustada.',
'unlockconfirm'       => 'Jah, ma tõesti soovin andmebaasi lukust avada.',
'lockbtn'             => 'Võta andmebaas kirjutuskaitse alla',
'unlockbtn'           => 'Taasta andmebaasi kirjutuspääs',
'locknoconfirm'       => 'Sa ei märkinud kinnituskastikesse linnukest.',
'lockdbsuccesssub'    => 'Andmebaas kirjutuskaitse all',
'unlockdbsuccesssub'  => 'Kirjutuspääs taastatud',
'lockdbsuccesstext'   => 'Andmebaas on nüüd kirjutuskaitse all.
<br />Kui Teie hooldustöö on läbi, ärge unustage kirjutuspääsu taastada!',
'unlockdbsuccesstext' => 'Andmebaasi kirjutuspääs on taastatud.',
'databasenotlocked'   => 'Andmebaas ei ole lukustatud.',

# Move page
'move-page'                    => 'Teisalda $1',
'move-page-legend'             => 'Teisalda artikkel',
'movepagetext'                 => "Allolevat vormi kasutades saate lehekülje ümber nimetada.
Lehekülje ajalugu tõstetakse uue pealkirja alla automaatselt.
Praeguse pealkirjaga leheküljest saab ümbersuunamisleht uuele leheküljele.
Teistes artiklites olevaid linke praeguse nimega leheküljele automaatselt ei muudeta.
Teie kohuseks on hoolitseda, et ei tekiks topeltümbersuunamisi ning et kõik jääks toimima nagu enne ümbernimetamist.

Lehekülge '''ei nimetata ümber''' juhul, kui uue nimega lehekülg on juba olemas. Erandiks on juhud, kui olemasolev lehekülg on tühi või ümbersuunamislehekülg ja sellel pole redigeerimisajalugu.
See tähendab, et te ei saa kogemata üle kirjutada juba olemasolevat lehekülge, kuid saate ebaõnnestunud ümbernimetamise tagasi pöörata.

'''ETTEVAATUST!'''
Võimalik, et kavatsete teha ootamatut ning drastilist muudatust väga loetavasse artiklisse;
enne muudatuse tegemist mõelge palun järele, mis võib olla selle tagajärjeks.",
'movepagetalktext'             => "Koos artiklileheküljega teisaldatakse automaatselt ka arutelulehekülg, '''välja arvatud juhtudel, kui:'''
*liigutate lehekülge ühest nimeruumist teise,
*uue nime all on juba olemas mittetühi arutelulehekülg või
*jätate alumise kastikese märgistamata.

Neil juhtudel teisaldage arutelulehekülg soovi korral eraldi või ühendage ta omal käel uue aruteluleheküljega.",
'movearticle'                  => 'Teisalda artiklilehekülg',
'movenologin'                  => 'Te ei ole sisse loginud',
'movenologintext'              => 'Et lehekülge teisaldada, peate registreeruma
kasutajaks ja [[Special:UserLogin|sisse logima]]',
'movenotallowed'               => 'Sul ei ole lehekülgede teisaldamise õigust.',
'movenotallowedfile'           => 'Sul ei ole failide teisaldamise õigust.',
'cant-move-user-page'          => 'Sul ei ole õigust teisaldada kasutajalehti (erandiks on kasutajate alamlehed).',
'cant-move-to-user-page'       => 'Sul ei ole õigust teisaldada lehekülge kasutajaleheks (ei käi kasutaja alamlehe kohta).',
'newtitle'                     => 'Uue pealkirja alla',
'move-watch'                   => 'Jälgi seda lehekülge',
'movepagebtn'                  => 'Teisalda artikkel',
'pagemovedsub'                 => 'Artikkel on teisaldatud',
'movepage-moved'               => '<big>\'\'\'"$1" teisaldatud pealkirja "$2" alla\'\'\'</big>',
'movepage-moved-redirect'      => 'Ümbersuunamisleht loodud.',
'movepage-moved-noredirect'    => 'Ümbersuunamist ei loodud.',
'articleexists'                => 'Selle nimega artikkel on juba olemas või pole valitud nimi lubatav. Palun valige uus nimi.',
'cantmove-titleprotected'      => 'Lehte ei saa sinna teisaldada, sest uus pealkiri on artikli loomise eest kaitstud',
'talkexists'                   => 'Artikkel on teisaldatud, kuid arutelulehekülge ei saanud teisaldada, sest uue nime all on arutelulehekülg juba olemas. Palun ühendage aruteluleheküljed ise.',
'movedto'                      => 'Teisaldatud pealkirja alla:',
'movetalk'                     => 'Teisalda ka "arutelu", kui saab.',
'move-subpages'                => 'Teisalda alalehed (kuni $1)',
'move-talk-subpages'           => 'Teisalda arutelulehe alalehed (kuni $1)',
'movepage-page-exists'         => 'Lehekülg $1 on juba olemas ja seda ei saa automaatselt üle kirjutada.',
'movepage-page-moved'          => 'Lehekülg $1 on teisaldatud pealkirja $2 alla.',
'movepage-page-unmoved'        => 'Lehekülge $1 ei saanud teisaldada pealkirja $2 alla.',
'1movedto2'                    => 'teisaldas lehekülje [[$1]] pealkirja [[$2]] alla',
'1movedto2_redir'              => 'teisaldas lehekülje [[$1]] ümbersuunamisega pealkirja [[$2]] alla',
'move-redirect-suppressed'     => 'ümbersuunamiseta',
'movelogpage'                  => 'Teisaldamise logi',
'movelogpagetext'              => 'See logi sisaldab infot lehekülgede teisaldamistest.',
'movesubpage'                  => '{{PLURAL:$1|Alamleht|Alamlehte}}',
'movenosubpage'                => 'Sellel leheküljel ei ole alalehekülgi.',
'movereason'                   => 'Põhjus',
'revertmove'                   => 'taasta',
'delete_and_move'              => 'Kustuta ja teisalda',
'delete_and_move_confirm'      => 'Jah, kustuta lehekülg',
'delete_and_move_reason'       => 'Kustutatud, et asemele tõsta teine lehekülg',
'immobile-source-namespace'    => 'Lehekülgi ei saa teisaldada nimeruumis $1',
'immobile-target-namespace'    => 'Lehekülgi ei saa teisaldada nimeruumi "$1"',
'immobile-target-namespace-iw' => 'Keelelink ei ole sobiv koht lehekülje teisaldamiseks.',
'immobile-source-page'         => 'Lehekülg ei ole teisaldatav.',
'immobile-target-page'         => 'Soovitud pealkirja alla ei saa teisaldada.',
'imagenocrossnamespace'        => 'Faili ei saa teisaldada mõnda muusse nimeruumi',
'imagetypemismatch'            => 'Uus faililaiend ei sobi selle tüübiga',
'imageinvalidfilename'         => 'Sihtmärgi nimi on vigane',
'fix-double-redirects'         => 'Värskenda kõik siia viitavad ümbersuunamislehed uuele pealkirjale',
'move-leave-redirect'          => 'Jäta maha ümbersuunamisleht',
'protectedpagemovewarning'     => "'''Hoiatus:''' See lehekülg on nii lukustatud, et ainult ülemaeelistega kasutajad saavad seda teisaldada.",
'semiprotectedpagemovewarning' => "'''Pane tähele:''' See lehekülg on lukustatud sel viisil, et ainult registreeritud kasutajad saavad seda teisaldada.",

# Export
'export'            => 'Lehekülgede eksport',
'exporttext'        => 'Sa saad siin eksportida kindla lehekülje või nende kogumi, tekstid, koos kogu nende muudatuste ajalooga, XML kujule viiduna. Seda saad sa vajadusel kasutada teksti ülekandmiseks teise vikisse, kasutades selleks MediaWiki [[Special:Import|impordi lehekülge]].

Et eksportida lehekülgi, sisesta nende pealkirjad all olevasse teksti kasti, iga pealkiri ise reale, ning vali kas sa soovid saada leheküljest kõiki selle vanemaid versioone (muudatusi) või soovid sa saada leheküljest vaid hetke versiooni.

Viimasel juhul võid sa näiteks "[[{{MediaWiki:Mainpage}}]]" lehekülje, jaoks kasutada samuti linki kujul:  [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Lisa vaid viimane versioon lehest, ning mitte kogu ajalugu',
'export-submit'     => 'Ekspordi',
'export-addcattext' => 'Kõik leheküljed kategooriast:',
'export-addcat'     => 'Lisa',
'export-addnstext'  => 'Lisa lehti nimeruumist:',
'export-addns'      => 'Lisa',
'export-download'   => 'Salvesta failina',
'export-templates'  => 'Kaasa mallid',

# Namespace 8 related
'allmessages'                   => 'Kõik süsteemi sõnumid',
'allmessagesname'               => 'Nimi',
'allmessagesdefault'            => 'Vaikimisi tekst',
'allmessagescurrent'            => 'Praegune tekst',
'allmessagestext'               => 'See on loend kõikidest kättesaadavatest süsteemi sõnumitest MediaWiki: nimeruumis.
Kui soovid MediaWiki tarkvara tõlkimises osaleda siis vaata lehti [http://www.mediawiki.org/wiki/Localisation MediaWiki Lokaliseerimine] ja [http://translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB'     => "Seda lehekülge ei saa kasutada, sest '''\$wgUseDatabaseMessages''' ei tööta.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter-unmodified' => 'Muutmata',
'allmessages-filter-all'        => 'Kõik',
'allmessages-filter-modified'   => 'Muudetud',
'allmessages-language'          => 'Keel:',
'allmessages-filter-submit'     => 'Mine',

# Thumbnails
'thumbnail-more'           => 'Suurenda',
'filemissing'              => 'Fail puudub',
'thumbnail_error'          => 'Viga pisipildi loomisel: $1',
'thumbnail_invalid_params' => 'Vigased pisipildi parameetrid',
'thumbnail_image-type'     => 'Selline pildi tüüp ei ole toetatav',
'thumbnail_image-missing'  => 'Fail näib puuduvat: $1',

# Special:Import
'import'                     => 'Lehekülgede import',
'importinterwiki'            => 'Vikidevaheline import',
'import-interwiki-source'    => 'Lähteviki/lehekülg:',
'import-interwiki-history'   => 'Kopeeri selle lehekülje kogu ajalugu',
'import-interwiki-templates' => 'Liida kõik mallid',
'import-interwiki-submit'    => 'Impordi',
'import-interwiki-namespace' => 'Sihtpunkti nimeruum:',
'import-upload-filename'     => 'Failinimi:',
'import-comment'             => 'Kommentaar:',
'importtext'                 => 'Palun kasuta faili eksportimiseks allikaks olevast vikist [[Special:Export|ekspordi vahendit]]. Salvesta see oma arvutisse laadi siia üles.',
'importstart'                => 'Impordin lehekülgi...',
'import-revision-count'      => '$1 {{PLURAL:$1|versioon|versiooni}}',
'importnopages'              => 'Ei olnud imporditavaid lehekülgi.',
'importfailed'               => 'Importimine ebaõnnestus: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Unknown import source type
Tundmatu tüüpi algallikas',
'importcantopen'             => 'Ei saa imporditavat faili avada',
'importbadinterwiki'         => 'Vigane interwiki link',
'importnotext'               => 'Tühi või ilma tekstita',
'importsuccess'              => 'Importimine edukalt lõpetatud!',
'importhistoryconflict'      => 'Konfliktne muudatuste ajalugu (võimalik, et seda lehekülge juba varem imporditud)',
'importnosources'            => 'Ühtegi transwiki impordiallikat ei ole defineeritud ning ajaloo otseimpordi funktsioon on välja lülitatud.',
'importnofile'               => 'Ühtegi imporditavat faili ei laaditud üles.',
'importuploaderrorsize'      => 'Üleslaaditava faili import ebaõnnestus.
Fail on lubatust suurem.',
'importuploaderrorpartial'   => 'Imporditava faili üleslaadimine ebaõnnestus.
Fail oli vaid osaliselt üleslaaditud.',
'importuploaderrortemp'      => 'Üleslaaditava faili import ebaõnnestus.
Puudub ajutine kataloog.',
'import-noarticle'           => 'Ühtki lehekülge polnud importida!',
'import-nonewrevisions'      => 'Kõik versioonid on eelnevalt imporditud.',
'import-token-mismatch'      => 'Seansiandmed läksid kaduma.
Palun ürita uuesti.',

# Import log
'importlogpage'                    => 'Impordi logi',
'importlogpagetext'                => 'Importimislogi kuvab leheküljed, mille redigeerimisajalugu pärineb teistest vikidest.',
'import-logentry-upload'           => 'importis faili üleslaadimisega lehekülje [[$1]]',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}} vikist $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Sinu kasutajaleht',
'tooltip-pt-anonuserpage'         => 'Selle IP aadressi kasutajaleht',
'tooltip-pt-mytalk'               => 'Minu aruteluleht',
'tooltip-pt-anontalk'             => 'Arutelu sellelt IP aadressilt tehtud muudatuste kohta',
'tooltip-pt-preferences'          => 'Minu eelistused',
'tooltip-pt-watchlist'            => 'Lehekülgede loend, mida jälgid muudatuste osas',
'tooltip-pt-mycontris'            => 'Sinu kaastööde loend',
'tooltip-pt-login'                => 'Me julgustame teid sisse logima, kuid see pole kohustuslik.',
'tooltip-pt-anonlogin'            => 'Me julgustame teid sisse logima, kuid see pole kohustuslik.',
'tooltip-pt-logout'               => 'Logi välja',
'tooltip-ca-talk'                 => 'Selle artikli arutelu',
'tooltip-ca-edit'                 => 'Te võite seda lehekülge redigeerida. Palun kasutage enne salvestamist eelvaadet.',
'tooltip-ca-addsection'           => 'Lisa uus alaosa',
'tooltip-ca-viewsource'           => 'See lehekülg on kaitstud. Te võite kuvada selle koodi.',
'tooltip-ca-history'              => 'Selle lehekülje varasemad versioonid.',
'tooltip-ca-protect'              => 'Kaitse seda lehekülge',
'tooltip-ca-delete'               => 'Kustuta see lehekülg',
'tooltip-ca-undelete'             => 'Taasta tehtud muudatused enne kui see lehekülg kustutati',
'tooltip-ca-move'                 => 'Teisalda see lehekülg teise nime alla.',
'tooltip-ca-watch'                => 'Lisa see lehekülg oma jälgimisloendile',
'tooltip-ca-unwatch'              => 'Eemalda see lehekülg oma jälgimisloendist',
'tooltip-search'                  => 'Otsi vikist',
'tooltip-search-go'               => 'Siirdutakse täpselt sellist pealkirja kandvale lehele (kui selline on olemas)',
'tooltip-search-fulltext'         => 'Otsitakse teksti sisaldavaid artikleid',
'tooltip-p-logo'                  => 'Esileht',
'tooltip-n-mainpage'              => 'Mine esilehele',
'tooltip-n-portal'                => 'Projekti kohta, mida te saate teha, kuidas leida informatsiooni jne',
'tooltip-n-currentevents'         => 'Leia informatsiooni sündmuste kohta maailmas',
'tooltip-n-recentchanges'         => 'Vikis tehtud viimaste muudatuste loend.',
'tooltip-n-randompage'            => 'Mine juhuslikule leheküljele',
'tooltip-n-help'                  => 'Kuidas redigeerida.',
'tooltip-t-whatlinkshere'         => 'Kõik viki leheküljed, mis siia viitavad',
'tooltip-t-recentchangeslinked'   => 'Viimased muudatused lehekülgedel, milledele on siit viidatud',
'tooltip-feed-rss'                => 'Selle lehekülje RSS-toide',
'tooltip-feed-atom'               => 'Selle lehekülje Atom-toide',
'tooltip-t-contributions'         => 'Kuva selle kasutaja kaastööd',
'tooltip-t-emailuser'             => 'Saada sellele kasutajale e-kiri',
'tooltip-t-upload'                => 'Laadi faile üles',
'tooltip-t-specialpages'          => 'Erilehekülgede loend',
'tooltip-t-print'                 => 'Selle lehe trükiversioon',
'tooltip-t-permalink'             => 'Püsilink lehe sellele versioonile',
'tooltip-ca-nstab-main'           => 'Näita artiklit',
'tooltip-ca-nstab-user'           => 'Näita kasutaja lehte',
'tooltip-ca-nstab-media'          => 'Näita pildi lehte',
'tooltip-ca-nstab-special'        => 'See on erilehekülg, te ei saa seda redigeerida',
'tooltip-ca-nstab-project'        => 'Näita projekti lehte',
'tooltip-ca-nstab-image'          => 'Näita pildi lehte',
'tooltip-ca-nstab-mediawiki'      => 'Näita süsteemi sõnumit',
'tooltip-ca-nstab-template'       => 'Näita malli',
'tooltip-ca-nstab-help'           => 'Näita abilehte',
'tooltip-ca-nstab-category'       => 'Näita kategooria lehte',
'tooltip-minoredit'               => 'Märgista see pisiparandusena',
'tooltip-save'                    => 'Salvesta muudatused',
'tooltip-preview'                 => 'Näita tehtavaid muudatusi. Palun kasutage seda enne salvestamist!',
'tooltip-diff'                    => 'Näita tehtavaid muudatusi.',
'tooltip-compareselectedversions' => 'Näita erinevusi kahe selle lehe valitud versiooni vahel.',
'tooltip-watch'                   => 'Lisa see lehekülg oma jälgimisloendile',
'tooltip-recreate'                => 'Taasta kustutatud lehekülg',
'tooltip-upload'                  => 'Alusta üleslaadimist',
'tooltip-rollback'                => 'Tühistab ühe klõpsuga viimase kaastöölise tehtud muudatused.',
'tooltip-undo'                    => '"Eemalda" tühistab selle muudatuse ja avab teksti eelvaatega redigeerimisakna. 
Samuti võimaldab see resümee reale põhjenduse lisamist.',

# Metadata
'nodublincore'      => "Dublin Core'i RDF-meta-andmed ei ole selles serveris lubatud.",
'nocreativecommons' => 'Creative Commonsi RDF-meta-andmed ei ole selles serveris lubatud.',
'notacceptable'     => 'Viki server ei saa esitada andmeid selles formaadis, mida su veebilugeja suudab lugeda.',

# Attribution
'anonymous'        => '{{SITENAME}} {{PLURAL:$1|anonüümne kasutaja|anonüümsed kasutajad}}',
'siteuser'         => 'viki kasutaja $1',
'lastmodifiedatby' => 'Viimati muutis lehekülge $3 $2 kell $1.',
'othercontribs'    => 'Põhineb $1 tööl.',
'others'           => 'teised',
'siteusers'        => 'Võrgukoha {{SITENAME}} {{PLURAL:$2|kasutaja|kasutajad}} $1',
'creditspage'      => 'Lehekülje toimetajate loend',
'nocredits'        => 'Selle lehekülje toimetajate loend ei ole kättesaadav.',

# Spam protection
'spamprotectiontitle' => 'Spämmitõrjefilter',
'spamprotectiontext'  => 'Rämpspostifilter oli lehekülje, mida sa salvestada tahtsid, blokeerinud.
See on ilmselt põhjustatud linkimisest mustas nimekirjas olevasse välisvõrgukohta.',
'spamprotectionmatch' => 'Järgnev tekst vallandas meie rämpspostifiltri: $1',
'spam_blanking'       => 'Kõik versioonid sisaldasid linke veebilehele $1. Lehekülg tühjendatud.',

# Info page
'infosubtitle'   => 'Lehekülje informatsioon',
'numedits'       => 'Lehekülje redigeerimiste arv: $1',
'numtalkedits'   => 'Arutelulehe redigeerimiste arv: $1',
'numwatchers'    => 'Jälgijate arv: $1',
'numauthors'     => 'Lehekülje erinevate toimetajate arv: $1',
'numtalkauthors' => 'Arutelulehe erinevate toimetajate arv: $1',

# Skin names
'skinname-standard'    => 'Algeline',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Kölni sinine',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Minu kujundus',
'skinname-chick'       => 'Tibu',
'skinname-simple'      => 'Lihtne',
'skinname-modern'      => 'Uudne',
'skinname-vector'      => 'Vektor',

# Math options
'mw_math_png'    => 'Alati PNG',
'mw_math_simple' => 'Kui väga lihtne, siis HTML, muidu PNG',
'mw_math_html'   => 'Võimaluse korral HTML, muidu PNG',
'mw_math_source' => 'Säilitada TeX (tekstibrauserite puhul)',
'mw_math_modern' => 'Soovitatav moodsate brauserite puhul',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Arusaamatu süntaks',
'math_unknown_error'    => 'Tundmatu viga',
'math_unknown_function' => 'Tundmatu funktsioon',
'math_lexing_error'     => 'Väljalugemisviga',
'math_syntax_error'     => 'Süntaksiviga',

# Patrolling
'markaspatrolleddiff'                 => 'Märgi kui kontrollitud',
'markaspatrolledtext'                 => 'Märgi see leht kontrollituks',
'markedaspatrolled'                   => 'Kontrollituks märgitud',
'markedaspatrolledtext'               => 'Valitud redaktsioon on märgitud kontrollituks.',
'rcpatroldisabled'                    => 'Viimaste muudatuste kontroll ei toimi',
'rcpatroldisabledtext'                => 'Viimaste muudatuste kontrolli tunnus ei toimi hetkel.',
'markedaspatrollederror'              => 'Ei saa kontrollituks märkida',
'markedaspatrollederrortext'          => 'Vajalik on määrata, milline versioon märkida kontrollituks.',
'markedaspatrollederror-noautopatrol' => 'Enda muudatusi ei saa kontrollituks märkida.',

# Patrol log
'patrol-log-page'      => 'Kontrollimise logi',
'patrol-log-header'    => 'See on kontrollitud redaktsioonide logi.',
'patrol-log-line'      => 'märkis $1 leheküljel $2 kontrollituks $3',
'patrol-log-auto'      => '(automaatne)',
'patrol-log-diff'      => 'versiooni $1',
'log-show-hide-patrol' => '$1 kontrollimislogi',

# Image deletion
'deletedrevision'                 => 'Kustutatud vanem variant $1',
'filedeleteerror-short'           => 'Faili $1 kustutamine ebaõnnestus',
'filedeleteerror-long'            => 'Faili kustutamine ebaõnnestus:

$1',
'filedelete-missing'              => 'Faili "$1" ei saa kustutada, sest seda ei ole.',
'filedelete-current-unregistered' => 'Fail "$1" ei ole andmebaasis.',

# Browsing diffs
'previousdiff' => '← Eelmised erinevused',
'nextdiff'     => 'Järgmised erinevused →',

# Visual comparison
'visual-comparison' => 'Vaateline võrdlus',

# Media information
'mediawarning'         => "'''Hoiatus''': See fail võib sisaldada pahatahtlikku koodi, mille käivitamime võib kahjustada teie arvutisüsteemi.<hr />",
'imagemaxsize'         => "Maksimaalne pildi suurus:<br />''(faili kirjeldusleheküljel)''",
'thumbsize'            => 'Pisipildi suurus:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|lehekülg|lehekülge}}',
'file-info-size'       => '($1 × $2 pikslit, faili suurus: $3, MIME tüüp: $4)',
'file-nohires'         => '<small>Sellest suuremat pilti pole.</small>',
'svg-long-desc'        => '(SVG fail, algsuurus $1 × $2 pikslit, faili suurus: $3)',
'show-big-image'       => 'Originaalsuurus',
'show-big-image-thumb' => '<small>Selle eelvaate suurus on: $1 × $2 pikselit</small>',

# Special:NewFiles
'newimages'             => 'Uute meediafailide galerii',
'imagelisttext'         => "
Järgnevas loendis, mis on sorteeritud $2, on '''$1''' {{PLURAL:$1|fail|faili}}.",
'newimages-summary'     => 'Sellel erilehel on viimati üles laaditud failid.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Failinimi (või selle osa):',
'showhidebots'          => '($1 robotite kaastööd)',
'noimages'              => 'Uusi pilte ei ole.',
'ilsubmit'              => 'Otsi',
'bydate'                => 'kuupäeva järgi',
'sp-newimages-showfrom' => 'Näita uusi faile alates $2 $1',

# Bad image list
'bad_image_list' => 'Arvesse võetakse ainult nimekirja ühikud (read, mis algavad sümboliga *).
Esimene link real peab olema link kõlbmatule failile.
Samal real olevaid järgmiseid linke vaadeldakse kui erandeid, see tähendab artikleid, mille koosseisu kujutise võib lülitada.',

# Metadata
'metadata'          => 'Metaandmed',
'metadata-help'     => 'See fail sisaldab lisateavet, mille on tõenäoliselt lisanud digikaamera või skanner.
Kui faili on rakendustarkvaraga töödeldud, võib osa andmeid olla muudetud või täielikult eemaldatud.',
'metadata-expand'   => 'Näita täpsemaid detaile',
'metadata-collapse' => 'Peida täpsemad detailid',
'metadata-fields'   => 'Siin loetletud EXIF metaandmete välju näidatakse pildi kirjelduslehel vähemdetailse metaandmete vaate korral.
Ülejäänud andmed on vaikimisi peidetud.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                => 'Laius',
'exif-imagelength'               => 'Kõrgus',
'exif-bitspersample'             => 'Bitti komponendi kohta',
'exif-compression'               => 'Pakkimise skeem',
'exif-photometricinterpretation' => 'Pikslite koosseis',
'exif-orientation'               => 'Orientatsioon',
'exif-samplesperpixel'           => 'Komponentide arv',
'exif-planarconfiguration'       => 'Andmejärjestus',
'exif-xresolution'               => 'Horisontaalne eraldus',
'exif-yresolution'               => 'Vertikaalne eraldus',
'exif-stripoffsets'              => 'Pildi andmete asukoht',
'exif-transferfunction'          => 'Siirdefunktsioon',
'exif-datetime'                  => 'Faili muutmise kuupäev ja kellaaeg',
'exif-imagedescription'          => 'Pildi pealkiri',
'exif-make'                      => 'Kaamera tootja',
'exif-model'                     => 'Kaamera mudel',
'exif-software'                  => 'Kasutatud tarkvara',
'exif-artist'                    => 'Autor',
'exif-copyright'                 => 'Autoriõiguste omanik',
'exif-exifversion'               => 'Exif versioon',
'exif-componentsconfiguration'   => 'Iga komponendi tähendus',
'exif-pixelydimension'           => 'Kehtiv pildi laius',
'exif-pixelxdimension'           => 'Kehtiv pildi kõrgus',
'exif-makernote'                 => 'Tootja märkmed',
'exif-usercomment'               => 'Kasutaja kommentaarid',
'exif-relatedsoundfile'          => 'Seotud helifail',
'exif-datetimedigitized'         => 'Digitaliseerimise kuupäev ja kellaaeg',
'exif-subsectime'                => 'Kuupäev/Kellaaeg sekundi murdosad',
'exif-subsectimeoriginal'        => 'Loomisaja sekundi murdosad',
'exif-subsectimedigitized'       => 'Digiteerimise sekundi murdosad',
'exif-exposuretime'              => 'Säriaeg',
'exif-exposuretime-format'       => '$1 sek ($2)',
'exif-exposureprogram'           => 'Säriprogramm',
'exif-spectralsensitivity'       => 'Spektraalne tundlikkus',
'exif-aperturevalue'             => 'Avaarv',
'exif-brightnessvalue'           => 'Heledus',
'exif-subjectdistance'           => 'Subjekti kaugus',
'exif-lightsource'               => 'Valgusallikas',
'exif-flash'                     => 'Välk',
'exif-focallength'               => 'Fookuskaugus',
'exif-subjectlocation'           => 'Subjekti asukoht',
'exif-filesource'                => 'Faili päritolu',
'exif-whitebalance'              => 'Valge tasakaal',
'exif-contrast'                  => 'Kontrastsus',
'exif-saturation'                => 'Küllastus',
'exif-sharpness'                 => 'Teravus',
'exif-devicesettingdescription'  => 'Seadme seadistuste kirjeldus',
'exif-imageuniqueid'             => 'Üksiku pildi ID',
'exif-gpslatituderef'            => 'Põhja- või lõunapikkus',
'exif-gpslatitude'               => 'Laius',
'exif-gpslongituderef'           => 'Ida- või läänepikkus',
'exif-gpslongitude'              => 'Laiuskraad',
'exif-gpsaltituderef'            => 'Viide kõrgusele merepinnast',
'exif-gpsaltitude'               => 'Kõrgus merepinnast',
'exif-gpstimestamp'              => 'GPS aeg (aatomikell)',
'exif-gpsstatus'                 => 'Vastuvõtja olek',
'exif-gpsspeedref'               => 'Kiirusühik',
'exif-gpsspeed'                  => 'GPS-vastuvõtja kiirus',
'exif-gpsimgdirection'           => 'Pildi suund',
'exif-gpsdestdistance'           => 'Sihtmärgi kaugus',
'exif-gpsdatestamp'              => 'GPS kuupäev',

# EXIF attributes
'exif-compression-1' => 'Pakkimata',

'exif-unknowndate' => 'Kuupäev teadmata',

'exif-orientation-1' => 'Normaalne',
'exif-orientation-2' => 'Pööratud pikali',
'exif-orientation-3' => 'Pööratud 180°',
'exif-orientation-4' => 'Pööratud püsti',

'exif-componentsconfiguration-0' => 'ei ole',

'exif-exposureprogram-0' => 'Määratlemata',
'exif-exposureprogram-1' => 'Manuaalne',
'exif-exposureprogram-7' => 'Portree töörežiim (lähifotode jaoks, taust fookusest väljas)',
'exif-exposureprogram-8' => 'Maastiku töörežiim (maastikupiltide jaoks, taust on fokuseeritud)',

'exif-subjectdistance-value' => '$1 meetrit',

'exif-meteringmode-0'   => 'Teadmata',
'exif-meteringmode-1'   => 'Keskmine',
'exif-meteringmode-2'   => 'Kaalutud keskmine',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Osaline',
'exif-meteringmode-255' => 'Muu',

'exif-lightsource-0'   => 'Teadmata',
'exif-lightsource-1'   => 'Päevavalgus',
'exif-lightsource-2'   => 'Helendav',
'exif-lightsource-4'   => 'Välk',
'exif-lightsource-9'   => 'Hea ilm',
'exif-lightsource-10'  => 'Pilvine ilm',
'exif-lightsource-11'  => 'Varjus',
'exif-lightsource-17'  => 'Standardne valgus A',
'exif-lightsource-18'  => 'Standardne valgus B',
'exif-lightsource-19'  => 'Standardne valgus C',
'exif-lightsource-255' => 'Muu valgusallikas',

# Flash modes
'exif-flash-fired-0'    => 'Välk ei töötanud',
'exif-flash-fired-1'    => 'Välk töötas',
'exif-flash-mode-3'     => 'automaatne töörežiim',
'exif-flash-function-1' => 'Välgu funktsiooni ei ole',

'exif-focalplaneresolutionunit-2' => 'tolli',

'exif-sensingmethod-1' => 'Määramata',

'exif-exposuremode-0' => 'Automaatne säritus',
'exif-exposuremode-1' => 'Manuaalne säritus',

'exif-whitebalance-1' => 'Manuaalne valgusbalanss',

'exif-scenecapturetype-1' => 'Maastik',
'exif-scenecapturetype-2' => 'Portree',
'exif-scenecapturetype-3' => 'Ööpilt',

'exif-gaincontrol-0' => 'Ei ole',
'exif-gaincontrol-1' => 'Aeglane tõus',
'exif-gaincontrol-2' => 'Kiire tõus',

'exif-contrast-0' => 'Normaalne',
'exif-contrast-1' => 'Pehme',
'exif-contrast-2' => 'Kõva',

'exif-saturation-0' => 'Normaalne',
'exif-saturation-1' => 'Madal värviküllastus',
'exif-saturation-2' => 'Kõrge värviküllastus',

'exif-sharpness-0' => 'Normaalne',
'exif-sharpness-1' => 'Pehme',
'exif-sharpness-2' => 'Kõva',

'exif-subjectdistancerange-0' => 'Teadmata',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Lähivõte',
'exif-subjectdistancerange-3' => 'Kaugvõte',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Põhjalaius',
'exif-gpslatitude-s' => 'Lõunalaius',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Idapikkus',
'exif-gpslongitude-w' => 'Läänepikkus',

'exif-gpsstatus-a' => 'Mõõtmine pooleli',

'exif-gpsmeasuremode-2' => '2-mõõtmeline ulatus',
'exif-gpsmeasuremode-3' => '3-mõõtmeline ulatus',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilomeetrit tunnis',
'exif-gpsspeed-m' => 'Miili tunnis',
'exif-gpsspeed-n' => 'Sõlme',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tegelik suund',

# External editor support
'edit-externally'      => 'Töötle faili välise programmiga',
'edit-externally-help' => 'Lisainfot loe leheküljelt [http://www.mediawiki.org/wiki/Manual:External_editors meta:väliste redaktorite kasutamine]',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'kõik',
'imagelistall'     => 'kõik pildid',
'watchlistall2'    => 'Näita kõiki',
'namespacesall'    => 'kõik',
'monthsall'        => 'kõik',

# E-mail address confirmation
'confirmemail'             => 'Kinnita e-posti aadress',
'confirmemail_noemail'     => 'Sul ei ole e-aadress määratud [[Special:Preferences|eelistustes]].',
'confirmemail_text'        => 'Enne kui saad e-postiga seotud teenuseid kasutada, pead sa oma e-posti aadressi õigsust kinnitama. Allpool olevale nupule klikkides meilitakse sulle kinnituskood, koodi kinnitamiseks kliki meilis oleval lingil.',
'confirmemail_pending'     => 'Kinnituskood on juba su e-aadressile saadetud. Kui tegid konto hiljuti, oota palun mõni minut selle saabumist, enne kui üritad uuesti.',
'confirmemail_send'        => 'Meili kinnituskood',
'confirmemail_sent'        => 'Kinnitusmeil saadetud.',
'confirmemail_oncreate'    => 'Kinnituskood saadeti su e-aadressile. See kood ei ole vajalik sisselogimisel, kuid seda on vaja, et kasutada vikis e-posti-põhiseid toiminguid.',
'confirmemail_sendfailed'  => 'Kinnitusmeili ei õnnestunud saata. 
Kontrolli aadressi õigsust.

Veateade meili saatmisel: $1',
'confirmemail_invalid'     => 'Vigane kinnituskood, kinnituskood võib olla aegunud.',
'confirmemail_needlogin'   => 'Oma e-posti aadressi kinnitamiseks pead sa $1.',
'confirmemail_success'     => 'Sinu e-posti aadress on nüüd kinnitatud. Sa võid sisse logida ning viki imelisest maailma nautida.',
'confirmemail_loggedin'    => 'Sinu e-posti aadress on nüüd kinnitatud.',
'confirmemail_error'       => 'Viga kinnituskoodi salvestamisel.',
'confirmemail_subject'     => '{{SITENAME}}: e-posti aadressi kinnitamine',
'confirmemail_body'        => 'Keegi, ilmselt sa ise, registreeris IP aadressilt $1 saidil {{SITENAME}} kasutajakonto "$2".

Kinnitamaks, et see kasutajakonto tõepoolest kuulub sulle ning aktiveerimaks e-posti teenuseid, ava oma brauseris järgnev link:

$3

Kui see *ei* ole sinu loodud konto, siis ava järgnev link $5 kinnituse tühistamiseks. 

Kinnituskood aegub $4.',
'confirmemail_invalidated' => 'E-aadressi kinnitamine tühistati',
'invalidateemail'          => 'Tühista e-posti kinnitus',

# Scary transclusion
'scarytranscludetoolong' => '[URL on liiga pikk]',

# Trackbacks
'trackbackremove' => '([$1 Kustuta])',

# Delete conflict
'deletedwhileediting' => 'Hoiatus: Sel ajal, kui sina artiklit redigeerisid, kustutas keegi selle ära!',
'recreate'            => 'Taasta',

# action=purge
'confirm_purge_button' => 'Sobib',
'confirm-purge-top'    => 'Tühjenda selle lehekülje vahemälu?',
'confirm-purge-bottom' => 'Toiming puhastab lehekülje vahemälu ja kuvab uusima versiooni.',

# Multipage image navigation
'imgmultipageprev' => '← eelmine lehekülg',
'imgmultipagenext' => 'järgmine lehekülg →',
'imgmultigo'       => 'Mine!',
'imgmultigoto'     => 'Mine leheküljele $1',

# Table pager
'ascending_abbrev'         => 'tõusev',
'descending_abbrev'        => 'laskuv',
'table_pager_next'         => 'Järgmine lehekülg',
'table_pager_prev'         => 'Eelmine lehekülg',
'table_pager_first'        => 'Esimene lehekülg',
'table_pager_last'         => 'Viimane lehekülg',
'table_pager_limit'        => 'Näita lehekülje kohta $1 üksust',
'table_pager_limit_submit' => 'Mine',
'table_pager_empty'        => 'Ei ole tulemusi',

# Auto-summaries
'autosumm-blank'   => 'Kustutatud kogu lehekülje sisu',
'autosumm-replace' => "Lehekülg asendatud tekstiga '$1'",
'autoredircomment' => 'Ümbersuunamine lehele [[$1]]',
'autosumm-new'     => "Uus lehekülg: '$1'",

# Live preview
'livepreview-loading' => 'Laen...',
'livepreview-ready'   => 'Laadimisel... Valmis!',
'livepreview-failed'  => 'Elav eelvaade ebaõnnestus! Proovi normaalset eelvaadet.',
'livepreview-error'   => 'Ühendus ebaõnnestus: $1 "$2".
Proovi normaalset eelvaadet.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Hilisemad muudatused kui $1 {{PLURAL:$1|sekund|sekundit}} ei pruugi selles loendis näha olla.',

# Watchlist editor
'watchlistedit-numitems'       => 'Teie jälgimisloendis on ilma arutelulehtedeta {{PLURAL:$1|1 leht|$1 lehte}}.',
'watchlistedit-noitems'        => 'Teie jälgimisloend ei sisalda ühtegi lehekülge.',
'watchlistedit-normal-title'   => 'Jälgimisloendi redigeerimine',
'watchlistedit-normal-legend'  => 'Jälgimisloendist lehtede eemaldamine',
'watchlistedit-normal-explain' => "Need lehed on teie jälgimisloendis.
Et lehti jälgimisloendist eemaldada, tehke vastava lehe ees olevasse kastikesse linnuke ja vajutage siis nuppu '''Eemalda valitud lehed'''. Kuid teil on võimalus muuta siit ka [[Special:Watchlist/raw|jälgimisloendi algandmeid]].",
'watchlistedit-normal-submit'  => 'Eemalda valitud lehed',
'watchlistedit-normal-done'    => 'Teie jälgimisloendist eemaldati {{PLURAL:$1|1 leht|$1 lehte}}:',
'watchlistedit-raw-title'      => 'Jälgimisloendi algandmed',
'watchlistedit-raw-legend'     => 'Redigeeritavad jälgimisloendi algandmed',
'watchlistedit-raw-explain'    => 'Sinu jälgimisloendi pealkirjad on kuvatud all asuvas tekstikastis, kus sa saad neid lisada ja/või eemaldada;
Iga pealkiri asub ise real.
Kui sa oled lõpetanud, vajuta all nuppu Uuenda jälgimisloendit.
Aga samuti võid sa [[Special:Watchlist/edit|kasutada harilikku redaktorit]].',
'watchlistedit-raw-titles'     => 'Pealkirjad:',
'watchlistedit-raw-submit'     => 'Uuenda jälgimisloendit',
'watchlistedit-raw-done'       => 'Teie jälgimisloend on uuendatud.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 lehekülg|$1 lehekülge}} lisatud:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 pealkiri|$1 pealkirja}} eemaldati:',

# Watchlist editing tools
'watchlisttools-view' => 'Näita vastavaid muudatusi',
'watchlisttools-edit' => 'Vaata ja redigeeri jälgimisloendit',
'watchlisttools-raw'  => 'Muuda lähteteksti',

# Special:Version
'version'                       => 'Versioon',
'version-extensions'            => 'Paigaldatud lisad',
'version-specialpages'          => 'Erileheküljed',
'version-parserhooks'           => 'Süntaksianalüsaatori lisad (Parser hooks)',
'version-variables'             => 'Muutujad',
'version-other'                 => 'Muu',
'version-mediahandlers'         => 'Meediatöötlejad',
'version-extension-functions'   => 'Lisafunktsioonid',
'version-parser-extensiontags'  => 'Parseri lisamärgendid',
'version-parser-function-hooks' => 'Parserifunktsioonid',
'version-hook-subscribedby'     => 'Tellijad',
'version-version'               => '(Versioon $1)',
'version-license'               => 'Litsents',
'version-software'              => 'Paigaldatud tarkvara',
'version-software-product'      => 'Toode',
'version-software-version'      => 'Versioon',

# Special:FilePath
'filepath'         => 'Failitee',
'filepath-page'    => 'Fail:',
'filepath-submit'  => 'Tee',
'filepath-summary' => 'See erileht määrab otsitava failini viiva tee.
Pilt kuvatakse algupärases suuruses, muu fail avatakse koheselt seostuva programmiga.

Sisesta faili nimi eesliiteta "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Otsi faili duplikaate',
'fileduplicatesearch-summary'  => 'Otsi duplikaatfaile nende räsiväärtuse järgi.

Sisesta faili nimi eesliiteta "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Otsi faili duplikaati',
'fileduplicatesearch-filename' => 'Faili nimi:',
'fileduplicatesearch-submit'   => 'Otsi',
'fileduplicatesearch-info'     => '$1 × $2 pikslit<br />Faili suurus: $3<br />MIME-tüüp: $4',
'fileduplicatesearch-result-1' => 'Failil "$1" ei ole identset duplikaati.',

# Special:SpecialPages
'specialpages'                   => 'Erileheküljed',
'specialpages-note'              => '----
* Harilikud erileheküljed
* <strong class="mw-specialpagerestricted">Piiranguga erileheküljed.</strong>',
'specialpages-group-maintenance' => 'Hooldusraportid',
'specialpages-group-other'       => 'Teised erileheküljed',
'specialpages-group-login'       => 'Sisselogimine / registreerumine',
'specialpages-group-changes'     => 'Viimased muudatused ja logid',
'specialpages-group-media'       => 'Failidega seonduv',
'specialpages-group-users'       => 'Kasutajad ja õigused',
'specialpages-group-highuse'     => 'Tihti kasutatud leheküljed',
'specialpages-group-pages'       => 'Lehekülgede loendid',
'specialpages-group-pagetools'   => 'Töö lehekülgedega',
'specialpages-group-wiki'        => 'Viki andmed ja tööriistad',
'specialpages-group-redirects'   => 'Ümbersuunavad erilehed',
'specialpages-group-spam'        => 'Töö spämmiga',

# Special:BlankPage
'blankpage'              => 'Tühi leht',
'intentionallyblankpage' => 'See lehekülg on sihilikult tühjaks jäetud.',

# Special:Tags
'tags'                    => 'Käibivad muudatusmärgised',
'tags-title'              => 'Märgised',
'tags-intro'              => 'See lehekülg loetleb märgised, millega tarkvara võib muudatused märgistada, ja nende kirjeldused.',
'tags-tag'                => 'Sisene märgisenimi',
'tags-display-header'     => 'Tähistus muudatusloendis',
'tags-description-header' => 'Täiskirjeldus',
'tags-hitcount-header'    => 'Märgistatud muudatused',
'tags-edit'               => 'muuda',
'tags-hitcount'           => '$1 {{PLURAL:$1|muudatus|muudatust}}',

# Database error messages
'dberr-header'      => 'Selles wikis on probleem',
'dberr-problems'    => 'Kahjuks on sellel saidil tehnilisi probleeme',
'dberr-again'       => 'Oota mõni hetk ja lae lehekülg uuesti.',
'dberr-info'        => '(Ei saa ühendust andmebaasi serveriga: $1)',
'dberr-usegoogle'   => "Proovi vahepeal otsida Google'ist.",
'dberr-outofdate'   => "Google'i indeksid võivad olla ajas maha jäänud.",
'dberr-cachederror' => 'See koopia taotletud leheküljest on vahemälus ja ei pruugi olla ajakohane.',

# HTML forms
'htmlform-select-badoption'    => 'Antud number ei ole kõlbulik.',
'htmlform-int-invalid'         => 'Antud väärtus ei ole täisarv.',
'htmlform-float-invalid'       => 'Määratud väärtus ei ole number.',
'htmlform-int-toolow'          => 'Antud suurus on väiksem kui minimaalne $1',
'htmlform-int-toohigh'         => 'Antud suurus on suurem kui maksimaalne $1',
'htmlform-submit'              => 'Saada',
'htmlform-reset'               => 'Tühista muudatused',
'htmlform-selectorother-other' => 'Muu',

);
