<?php
/** Estonian (eesti)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Avjoska
 * @author Cylly1512
 * @author Hendrik
 * @author Hendrix
 * @author Jaan513
 * @author Kaganer
 * @author KaidoKikkas
 * @author KalmerE.
 * @author Ker
 * @author Kyng
 * @author Morel
 * @author Oop
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
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_arutelu',
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

$namespaceAliases = array(
	'$1_arutelu' => NS_PROJECT_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Teguskasutajad' ),
	'Allmessages'               => array( 'Kõik_sõnumid' ),
	'Allpages'                  => array( 'Kõik_leheküljed' ),
	'Ancientpages'              => array( 'Vanimad_leheküljed' ),
	'Blankpage'                 => array( 'Tühi_leht' ),
	'Block'                     => array( 'Blokeerimine' ),
	'Booksources'               => array( 'Raamatuotsimine', 'Otsi_raamatut' ),
	'BrokenRedirects'           => array( 'Vigased_ümbersuunamised' ),
	'Categories'                => array( 'Kategooriad' ),
	'ChangeEmail'               => array( 'E-posti_aadressi_muutmine' ),
	'ChangePassword'            => array( 'Parooli_muutmine', 'Muuda_parool' ),
	'ComparePages'              => array( 'Lehekülgede_kõrvutamine' ),
	'Confirmemail'              => array( 'E-posti_aadressi_kinnitamine' ),
	'Contributions'             => array( 'Kaastöö' ),
	'CreateAccount'             => array( 'Konto_loomine' ),
	'Deadendpages'              => array( 'Edasipääsuta_leheküljed' ),
	'DeletedContributions'      => array( 'Kustutatud_kaastöö' ),
	'Disambiguations'           => array( 'Täpsustusleheküljed' ),
	'DoubleRedirects'           => array( 'Kahekordsed_ümbersuunamised' ),
	'EditWatchlist'             => array( 'Jälgimisloendi_redigeerimine' ),
	'Emailuser'                 => array( 'E-kirja_saatmine' ),
	'Export'                    => array( 'Lehekülgede_eksport' ),
	'Fewestrevisions'           => array( 'Vähim_muudatusi' ),
	'FileDuplicateSearch'       => array( 'Faili_duplikaatide_otsimine', 'Otsi_faili_duplikaate' ),
	'Filepath'                  => array( 'Failitee' ),
	'Import'                    => array( 'Lehekülgede_import' ),
	'Invalidateemail'           => array( 'E-posti_kinnituse_tühistamine', 'Tühista_e-posti_kinnitus' ),
	'JavaScriptTest'            => array( 'JavaScripti_katsetamine' ),
	'BlockList'                 => array( 'Blokeerimisloend' ),
	'LinkSearch'                => array( 'Välislinkide_otsimine', 'Otsi_välislinke' ),
	'Listadmins'                => array( 'Ülemaloend' ),
	'Listbots'                  => array( 'Robotiloend' ),
	'Listfiles'                 => array( 'Failide_loend' ),
	'Listgrouprights'           => array( 'Kasutajarühma_õigused' ),
	'Listredirects'             => array( 'Ümbersuunamised' ),
	'Listusers'                 => array( 'Kasutajate_loend' ),
	'Lockdb'                    => array( 'Andmebaasi_lukustamine', 'Lukusta_andmebaas' ),
	'Log'                       => array( 'Logid' ),
	'Lonelypages'               => array( 'Viitamata_leheküljed' ),
	'Longpages'                 => array( 'Pikad_leheküljed' ),
	'MergeHistory'              => array( 'Liitmisajalugu' ),
	'MIMEsearch'                => array( 'MIME_otsing' ),
	'Mostcategories'            => array( 'Enim_kategoriseeritud' ),
	'Mostimages'                => array( 'Kõige_kasutatumad_failid' ),
	'Mostlinked'                => array( 'Kõige_viidatumad_leheküljed' ),
	'Mostlinkedcategories'      => array( 'Kõige_viidatumad_kategooriad' ),
	'Mostlinkedtemplates'       => array( 'Kõige_viidatumad_mallid' ),
	'Mostrevisions'             => array( 'Enim_muudatusi' ),
	'Movepage'                  => array( 'Teisaldamine', 'Teisalda' ),
	'Mycontributions'           => array( 'Minu_kaastöö' ),
	'Mypage'                    => array( 'Minu_lehekülg' ),
	'Mytalk'                    => array( 'Minu_aruteluleht' ),
	'Myuploads'                 => array( 'Minu_üleslaaditu' ),
	'Newimages'                 => array( 'Uued_failid' ),
	'Newpages'                  => array( 'Uued_leheküljed' ),
	'PasswordReset'             => array( 'Parooli_lähtestamine' ),
	'PermanentLink'             => array( 'Püsilink' ),
	'Popularpages'              => array( 'Loetumad_leheküljed' ),
	'Preferences'               => array( 'Eelistused' ),
	'Prefixindex'               => array( 'Kõik_pealkirjad_eesliitega' ),
	'Protectedpages'            => array( 'Kaitstud_leheküljed' ),
	'Protectedtitles'           => array( 'Kaitstud_pealkirjad' ),
	'Randompage'                => array( 'Juhuslik_artikkel' ),
	'Randomredirect'            => array( 'Juhuslik_ümbersuunamine' ),
	'Recentchanges'             => array( 'Viimased_muudatused' ),
	'Recentchangeslinked'       => array( 'Seotud_muudatused' ),
	'Revisiondelete'            => array( 'Muudatuse_kustutamine', 'Kustuta_muudatus' ),
	'Search'                    => array( 'Otsimine', 'Otsi' ),
	'Shortpages'                => array( 'Lühikesed_leheküljed' ),
	'Specialpages'              => array( 'Erileheküljed' ),
	'Statistics'                => array( 'Arvandmestik' ),
	'Tags'                      => array( 'Märgised' ),
	'Unblock'                   => array( 'Blokeerimise_eemaldamine' ),
	'Uncategorizedcategories'   => array( 'Kategoriseerimata_kategooriad' ),
	'Uncategorizedimages'       => array( 'Kategoriseerimata_failid' ),
	'Uncategorizedpages'        => array( 'Kategoriseerimata_leheküljed' ),
	'Uncategorizedtemplates'    => array( 'Kategoriseerimata_mallid' ),
	'Undelete'                  => array( 'Lehekülje_taastamine', 'Taasta_lehekülg' ),
	'Unlockdb'                  => array( 'Andmebaasi_avamine', 'Ava_andmebaas' ),
	'Unusedcategories'          => array( 'Kasutamata_kategooriad' ),
	'Unusedimages'              => array( 'Kasutamata_failid' ),
	'Unusedtemplates'           => array( 'Kasutamata_mallid' ),
	'Unwatchedpages'            => array( 'Jälgimata_leheküljed' ),
	'Upload'                    => array( 'Faili_üleslaadimine' ),
	'UploadStash'               => array( 'Üleslaaditu_alghoidla' ),
	'Userlogin'                 => array( 'Sisselogimine' ),
	'Userlogout'                => array( 'Väljalogimine' ),
	'Userrights'                => array( 'Kasutaja_õigused' ),
	'Version'                   => array( 'Versioon' ),
	'Wantedcategories'          => array( 'Oodatud_kategooriad' ),
	'Wantedfiles'               => array( 'Oodatud_failid' ),
	'Wantedpages'               => array( 'Oodatud_leheküljed' ),
	'Wantedtemplates'           => array( 'Oodatud_mallid' ),
	'Watchlist'                 => array( 'Jälgimisloend' ),
	'Whatlinkshere'             => array( 'Lingid_siia' ),
	'Withoutinterwiki'          => array( 'Ilma_keelelinkideta' ),
);

# Lisasin eestimaised poed, aga võõramaiseid ei julenud kustutada.
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
	'redirect'                  => array( '0', '#suuna', '#REDIRECT' ),
	'notoc'                     => array( '0', '__SISUKORRATA__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GALERIITA__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__SISUKORDEES__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__SISUKORD__', '__TOC__' ),
	'noeditsection'             => array( '0', '__ALAOSALINGITA__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'HETKEKUU', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'HETKEKUU1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'HETKEKUUNIMETUS', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'HETKEKUUPÄEV', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'HETKEKUUPÄEV2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'HETKENÄDALAPÄEV', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'HETKEAASTA', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'HETKEAEG', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'HETKETUND', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'KOHALIKKUU', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'KOHALIKKUU1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'KOHALIKKUUNIMETUS', 'LOCALMONTHNAME' ),
	'localday'                  => array( '1', 'KOHALIKKUUPÄEV', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'KOHALIKKUUPÄEV2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'KOHALIKNÄDALAPÄEV', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'KOHALIKAASTA', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'KOHALIKAEG', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'KOHALIKTUND', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'LEHEMÄÄR', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ARTIKLIMÄÄR', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FAILIMÄÄR', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'KASUTAJAMÄÄR', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'TEGUSKASUTAJAMÄÄR', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'REDIGEERIMISMÄÄR', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'VAATAMISTEARV', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'LEHEKÜLJENIMI', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'LEHEKÜLJENIMI1', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NIMERUUM', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NIMERUUM1', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ARUTELUNIMERUUM', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ARUTELUNIMERUUM1', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'SISUNIMERUUM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'SISUNIMERUUM1', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'KOGULEHEKÜLJENIMI', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'KOGULEHEKÜLJENIMI1', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ALAMLEHEKÜLJENIMI', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ALAMLEHEKÜLJENIMI1', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'NIMERUUMITANIMI', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'NIMERUUMITANIMI1', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'ARUTELUNIMI', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'ARUTELUNIMI1', 'TALKPAGENAMEE' ),
	'subst'                     => array( '0', 'ASENDA:', 'SUBST:' ),
	'img_thumbnail'             => array( '1', 'pisi', 'pisipilt', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'pisi=$1', 'pisipilt=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'paremal', 'right' ),
	'img_left'                  => array( '1', 'vasakul', 'left' ),
	'img_none'                  => array( '1', 'tühi', 'none' ),
	'img_center'                => array( '1', 'keskel', 'center', 'centre' ),
	'img_framed'                => array( '1', 'raam', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'raamita', 'frameless' ),
	'img_page'                  => array( '1', 'lehekülg=$1', 'lehekülg_$1', 'page=$1', 'page $1' ),
	'img_border'                => array( '1', 'ääris', 'border' ),
	'sitename'                  => array( '1', 'KOHANIMI', 'SITENAME' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'nse'                       => array( '0', 'NR1:', 'NSE:' ),
	'localurl'                  => array( '0', 'KOHALIKURL', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'KOHALIKURL1', 'LOCALURLE:' ),
	'servername'                => array( '0', 'SERVERINIMI', 'SERVERNAME' ),
	'gender'                    => array( '0', 'SUGU:', 'GENDER:' ),
	'currentweek'               => array( '1', 'HETKENÄDAL', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'HETKENÄDALAPÄEV1', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'KOHALIKNÄDAL', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'KOHALIKNÄDALAPÄEV1', 'LOCALDOW' ),
	'fullurl'                   => array( '0', 'KOGUURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'KOGUURL1:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'ESIVT:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ESIST:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'VT:', 'LC:' ),
	'uc'                        => array( '0', 'ST:', 'UC:' ),
	'displaytitle'              => array( '1', 'PEALKIRI', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__UUEALAOSALINK__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__UUEALAOSALINGITA__', '__NONEWSECTIONLINK__' ),
	'currenttimestamp'          => array( '1', 'HETKEAJATEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'KOHALIKAJATEMPEL', 'LOCALTIMESTAMP' ),
	'language'                  => array( '0', '#KEEL:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'VAIKEKEEL', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'LEHEKÜLGINIMERUUMIS', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ÜLEMAMÄÄR', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'ARVUVORMINDUS', 'FORMATNUM' ),
	'defaultsort'               => array( '1', 'JÄRJESTA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'hiddencat'                 => array( '1', '__PEIDETUDKAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'LEHEKÜLGIKATEGOORIAS', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'index'                     => array( '1', 'INDEKSIGA', '__INDEX__' ),
	'noindex'                   => array( '1', 'INDEKSITA', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'KASUTAJAIDRÜHMAS', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'protectionlevel'           => array( '1', 'KAITSETASE', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'kuupäevavormindus', 'formatdate', 'dateformat' ),
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
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
'tog-underline' => 'Linkide allakriipsutus:',
'tog-justify' => 'Lõikude rööpjoondus',
'tog-hideminor' => 'Peida pisiparandused viimastes muudatustes',
'tog-hidepatrolled' => 'Peida kontrollitud redaktsioonid viimastes muudatustes',
'tog-newpageshidepatrolled' => 'Peida uute lehtede loendis kontrollitud leheküljed',
'tog-extendwatchlist' => 'Laienda jälgimisloendit, et näha kõiki muudatusi, mitte vaid kõige värskemaid',
'tog-usenewrc' => 'Rühmita viimased muudatused ja muudatused jälgimisloendis lehekülje järgi (vaja JavaScripti)',
'tog-numberheadings' => 'Pealkirjade automaatnummerdus',
'tog-showtoolbar' => 'Näita redigeerimise tööriistariba (vaja JavaScripti)',
'tog-editondblclick' => 'Redigeeri lehekülgi topeltklõpsu peale (vaja JavaScripti)',
'tog-editsection' => 'Näita alaosade redigeerimise linke',
'tog-editsectiononrightclick' => 'Alusta alaosa redigeerimist paremklõpsuga alaosa pealkirjal (vaja JavaScripti)',
'tog-showtoc' => 'Näita sisukorda (lehtedel, millel on rohkem kui 3 pealkirja)',
'tog-rememberpassword' => 'Parooli meeldejätmine tulevasteks seanssideks (kuni $1 {{PLURAL:$1|päevaks|päevaks}})',
'tog-watchcreations' => 'Lisa jälgimisloendisse minu alustatud leheküljed ja minu üles laaditud failid',
'tog-watchdefault' => 'Lisa jälgimisloendisse minu muudetud leheküljed ja failid',
'tog-watchmoves' => 'Lisa jälgimisloendisse minu teisaldatud leheküljed ja failid',
'tog-watchdeletion' => 'Lisa jälgimisloendisse minu kustutatud leheküljed ja failid',
'tog-minordefault' => 'Märgi kõik parandused vaikimisi pisiparandusteks',
'tog-previewontop' => 'Näita eelvaadet toimetamiskasti ees',
'tog-previewonfirst' => 'Näita eelvaadet esimesel redigeerimisel',
'tog-nocache' => 'Keela võrgulehitsejal lehekülgede puhverdamine',
'tog-enotifwatchlistpages' => 'Teata e-posti teel minu jälgitava lehekülje või faili muutmisest',
'tog-enotifusertalkpages' => 'Teata e-posti teel minu arutelulehekülje muutmisest',
'tog-enotifminoredits' => 'Teata e-posti teel mulle ka pisiparandustest lehekülgedel ja failides',
'tog-enotifrevealaddr' => 'Näita minu e-posti aadressi teavitus-e-kirjades',
'tog-shownumberswatching' => 'Näita jälgivate kasutajate hulka',
'tog-oldsig' => 'Praegune allkiri:',
'tog-fancysig' => 'Kasuta vikiteksti vormingus allkirja (ilma automaatse lingita kasutajalehele)',
'tog-externaleditor' => 'Kasuta vaikimisi välist redaktorit (ainult asjatundjatele, tarvis arvuti eriseadistust – [//www.mediawiki.org/wiki/Manual:External_editors lisateave])',
'tog-externaldiff' => 'Kasuta vaikimisi välist võrdlusvahendit (ainult asjatundjatele, tarvis arvuti eriseadistust – [//www.mediawiki.org/wiki/Manual:External_editors lisateave])',
'tog-showjumplinks' => 'Kuva lehekülje ülaservas "mine"-lingid',
'tog-uselivepreview' => 'Kasuta elavat eelvaadet (vaja JavaScripti) (katseline)',
'tog-forceeditsummary' => 'Nõua redigeerimisel resümee välja täitmist',
'tog-watchlisthideown' => 'Peida minu redaktsioonid jälgimisloendist',
'tog-watchlisthidebots' => 'Peida robotid jälgimisloendist',
'tog-watchlisthideminor' => 'Peida pisiparandused jälgimisloendist',
'tog-watchlisthideliu' => 'Peida sisselogitud kasutajate muudatused jälgimisloendist',
'tog-watchlisthideanons' => 'Peida anonüümsete kasutajate muudatused jälgimisloendist',
'tog-watchlisthidepatrolled' => 'Peida kontrollitud muudatused jälgimisloendist',
'tog-ccmeonemails' => 'Saada mulle koopiad minu läkitatud e-kirjadest',
'tog-diffonly' => 'Ära näita erinevuste vaate all lehe sisu',
'tog-showhiddencats' => 'Näita peidetud kategooriaid',
'tog-norollbackdiff' => 'Ära näita erinevusi pärast tühistamist',

'underline-always' => 'Alati',
'underline-never' => 'Mitte kunagi',
'underline-default' => 'Kujunduse või võrgulehitseja vaikeväärtus',

# Font style option in Special:Preferences
'editfont-style' => 'Redigeerimisala kirjatüüp:',
'editfont-default' => 'Veebilehitseja vaikesäte',
'editfont-monospace' => 'Püsisammuga font',
'editfont-sansserif' => 'Seriifideta kiri',
'editfont-serif' => 'Seriifidega kiri',

# Dates
'sunday' => 'pühapäev',
'monday' => 'esmaspäev',
'tuesday' => 'teisipäev',
'wednesday' => 'kolmapäev',
'thursday' => 'neljapäev',
'friday' => 'reede',
'saturday' => 'laupäev',
'sun' => 'P',
'mon' => 'E',
'tue' => 'T',
'wed' => 'K',
'thu' => 'N',
'fri' => 'R',
'sat' => 'L',
'january' => 'jaanuar',
'february' => 'veebruar',
'march' => 'märts',
'april' => 'aprill',
'may_long' => 'mai',
'june' => 'juuni',
'july' => 'juuli',
'august' => 'august',
'september' => 'september',
'october' => 'oktoober',
'november' => 'november',
'december' => 'detsember',
'january-gen' => 'jaanuari',
'february-gen' => 'veebruari',
'march-gen' => 'märtsi',
'april-gen' => 'aprilli',
'may-gen' => 'mai',
'june-gen' => 'juuni',
'july-gen' => 'juuli',
'august-gen' => 'augusti',
'september-gen' => 'septembri',
'october-gen' => 'oktoobri',
'november-gen' => 'novembri',
'december-gen' => 'detsembri',
'jan' => 'jaan',
'feb' => 'veebr',
'mar' => 'märts',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'juuni',
'jul' => 'juuli',
'aug' => 'aug',
'sep' => 'sept',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dets',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategooria|Kategooriad}}',
'category_header' => 'Leheküljed kategoorias "$1"',
'subcategories' => 'Alamkategooriad',
'category-media-header' => 'Meediafailid kategoorias "$1"',
'category-empty' => "''Selles kategoorias pole ühtegi lehekülge ega meediafaili.''",
'hidden-categories' => '{{PLURAL:$1|Peidetud kategooria|Peidetud kategooriad}}',
'hidden-category-category' => 'Peidetud kategooriad',
'category-subcat-count' => '{{PLURAL:$2|Selles kategoorias on ainult järgmine alamkategooria.|{{PLURAL:$1|Järgmine alamkategooria|Järgmised $1 alamkategooriat}} on selles kategoorias (kokku $2).}}',
'category-subcat-count-limited' => '{{PLURAL:$1|Järgmine alamkategooria|Järgmised $1 alamkategooriat}} on selles kategoorias.',
'category-article-count' => '{{PLURAL:$2|Antud kategoorias on ainult järgmine lehekülg.|{{PLURAL:$1|Järgmine lehekülg|Järgmised $1 lehekülge}} on selles kategoorias (kokku $2).}}',
'category-article-count-limited' => '{{PLURAL:$1|Järgmine lehekülg|Järgmised $1 lehekülge}} on selles kategoorias.',
'category-file-count' => '{{PLURAL:$2|Selles kategoorias on ainult järgmine fail.|{{PLURAL:$1|Järgmine fail |Järgmised $1 faili}} on selles kategoorias (kokku $2).}}',
'category-file-count-limited' => '{{PLURAL:$1|Järgmine fail|Järgmised $1 faili}} on selles kategoorias.',
'listingcontinuesabbrev' => 'jätk',
'index-category' => 'Indeksiga leheküljed',
'noindex-category' => 'Indeksita leheküljed',
'broken-file-category' => 'Katkiste pildilinkidega leheküljed',

'about' => 'Tiitelandmed',
'article' => 'artikkel',
'newwindow' => '(avaneb uues aknas)',
'cancel' => 'Loobu',
'moredotdotdot' => 'Veel...',
'mypage' => 'Minu lehekülg',
'mytalk' => 'Arutelu',
'anontalk' => 'Arutelu selle IP jaoks',
'navigation' => 'Navigeerimine',
'and' => '&#32;ja',

# Cologne Blue skin
'qbfind' => 'Otsi',
'qbbrowse' => 'Sirvi',
'qbedit' => 'Redigeeri',
'qbpageoptions' => 'Lehekülje suvandid',
'qbpageinfo' => 'Lehekülje andmed',
'qbmyoptions' => 'Minu suvandid',
'qbspecialpages' => 'Erileheküljed',
'faq' => 'KKK',
'faqpage' => 'Project:KKK',

# Vector skin
'vector-action-addsection' => 'Lisa teema',
'vector-action-delete' => 'Kustuta',
'vector-action-move' => 'Teisalda',
'vector-action-protect' => 'Kaitse',
'vector-action-undelete' => 'Taasta',
'vector-action-unprotect' => 'Muuda kaitset',
'vector-simplesearch-preference' => 'Kasuta lihtsustatud otsiriba (ainult Vektori-kujunduses)',
'vector-view-create' => 'Loo',
'vector-view-edit' => 'Redigeeri',
'vector-view-history' => 'Näita ajalugu',
'vector-view-view' => 'Vaata',
'vector-view-viewsource' => 'Vaata lähteteksti',
'actions' => 'Toimingud',
'namespaces' => 'Nimeruumid',
'variants' => 'Variandid',

'errorpagetitle' => 'Viga',
'returnto' => 'Naase lehele $1',
'tagline' => 'Allikas: {{SITENAME}}',
'help' => 'Juhend',
'search' => 'Otsimine',
'searchbutton' => 'Otsi',
'go' => 'Mine',
'searcharticle' => 'Mine',
'history' => 'Artikli ajalugu',
'history_short' => 'Ajalugu',
'updatedmarker' => 'uuendatud pärast viimast külastust',
'printableversion' => 'Prinditav versioon',
'permalink' => 'Püsilink',
'print' => 'Prindi',
'view' => 'Vaata',
'edit' => 'Redigeeri',
'create' => 'Loo',
'editthispage' => 'Redigeeri seda lehekülge',
'create-this-page' => 'Loo see lehekülg',
'delete' => 'Kustuta',
'deletethispage' => 'Kustuta see lehekülg',
'undelete_short' => 'Taasta {{PLURAL:$1|üks muudatus|$1 muudatust}}',
'viewdeleted_short' => 'Vaata {{PLURAL:$1|üht|$1}} kustutatud redaktsiooni',
'protect' => 'Kaitse',
'protect_change' => 'muuda',
'protectthispage' => 'Kaitse seda lehekülge',
'unprotect' => 'Muuda kaitset',
'unprotectthispage' => 'Muuda selle lehekülje kaitset',
'newpage' => 'Uus lehekülg',
'talkpage' => 'Selle artikli arutelu',
'talkpagelinktext' => 'arutelu',
'specialpage' => 'Erilehekülg',
'personaltools' => 'Personaalsed tööriistad',
'postcomment' => 'Uus alaosa',
'articlepage' => 'Artiklilehekülg',
'talk' => 'Arutelu',
'views' => 'vaatamisi',
'toolbox' => 'Tööriistad',
'userpage' => 'Vaata kasutajalehekülge',
'projectpage' => 'Vaata projektilehekülge',
'imagepage' => 'Vaata faililehekülge',
'mediawikipage' => 'Vaata sõnumi lehekülge',
'templatepage' => 'Vaata malli lehekülge',
'viewhelppage' => 'Vaata abilehekülge',
'categorypage' => 'Vaata kategooria lehekülge',
'viewtalkpage' => 'Vaata arutelulehekülge',
'otherlanguages' => 'Teistes keeltes',
'redirectedfrom' => '(Ümber suunatud leheküljelt $1)',
'redirectpagesub' => 'Ümbersuunamisleht',
'lastmodifiedat' => 'Viimane muutmine: $2, $1',
'viewcount' => 'Seda lehekülge on külastatud {{PLURAL:$1|üks kord|$1 korda}}.',
'protectedpage' => 'Kaitstud lehekülg',
'jumpto' => 'Mine:',
'jumptonavigation' => 'navigeerimiskast',
'jumptosearch' => 'otsi',
'view-pool-error' => 'Serverid on hetkel üle koormatud.
Liiga palju kasutajaid üritab seda lehte vaadata.
Palun oota hetk, enne kui uuesti proovid.

$1',
'pool-errorunknown' => 'Teadmata tõrge',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{GRAMMAR:genitive|{{SITENAME}}}} tiitelandmed',
'aboutpage' => 'Project:Tiitelandmed',
'copyright' => 'Kogu tekst on kasutatav litsentsi $1 tingimustel.',
'copyrightpage' => '{{ns:project}}:Autoriõigused',
'currentevents' => 'Sündmused',
'currentevents-url' => 'Project:Sündmused',
'disclaimers' => 'Hoiatused',
'disclaimerpage' => 'Project:Hoiatused',
'edithelp' => 'Redigeerimisjuhend',
'edithelppage' => 'Help:Kuidas_lehte_redigeerida',
'helppage' => 'Help:Sisukord',
'mainpage' => 'Esileht',
'mainpage-description' => 'Esileht',
'policy-url' => 'Project:Reeglid',
'portal' => 'Kogukonnavärav',
'portal-url' => 'Project:Kogukonnavärav',
'privacy' => 'Privaatsus',
'privacypage' => 'Project:Privaatsus',

'badaccess' => 'Õigus puudub',
'badaccess-group0' => 'Sul pole õigust sooritada toimingut, mida üritasid.',
'badaccess-groups' => 'Tegevus, mida üritasid, on piiratud kasutajatele {{PLURAL:$2|rühmas|ühes neist rühmadest}}: $1.',

'versionrequired' => 'Nõutav MediaWiki versioon $1',
'versionrequiredtext' => 'Selle lehe kasutamiseks on nõutav MediaWiki versioon $1.
Vaata [[Special:Version|versiooni lehekülge]].',

'ok' => 'Sobib',
'pagetitle' => '$1 – {{SITENAME}}',
'retrievedfrom' => 'Pärit leheküljelt "$1"',
'youhavenewmessages' => 'Sulle on $1 ($2).',
'newmessageslink' => 'uusi sõnumeid',
'newmessagesdifflink' => 'viimane muudatus',
'youhavenewmessagesfromusers' => 'Sulle on $1 {{PLURAL:$3|ühelt|$3}} kasutajalt ($2).',
'youhavenewmessagesmanyusers' => 'Sulle on $1 paljudelt kasutajatelt ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|uus sõnum|uusi sõnumeid}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|viimane muudatus|viimased muudatused}}',
'youhavenewmessagesmulti' => 'Sulle on uusi sõnumeid $1',
'editsection' => 'redigeeri',
'editsection-brackets' => '[$1]',
'editold' => 'redigeeri',
'viewsourceold' => 'vaata lähteteksti',
'editlink' => 'redigeeri',
'viewsourcelink' => 'vaata lähteteksti',
'editsectionhint' => 'Redigeeri alaosa "$1"',
'toc' => 'Sisukord',
'showtoc' => 'näita',
'hidetoc' => 'peida',
'collapsible-collapse' => 'Peida',
'collapsible-expand' => 'Näita',
'thisisdeleted' => 'Vaata $1 või taasta?',
'viewdeleted' => 'Vaata $1?',
'restorelink' => '{{PLURAL:$1|üht|$1}} kustutatud versiooni',
'feedlinks' => 'Sööde:',
'feed-invalid' => 'Vigane vootüüp.',
'feed-unavailable' => 'Uudisvood ei ole saadaval.',
'site-rss-feed' => '$1 RSS-toide',
'site-atom-feed' => '$1 Atom-toide',
'page-rss-feed' => '"$1" RSS-toide',
'page-atom-feed' => '"$1" Atom-toide',
'red-link-title' => '$1 (pole veel kirjutatud)',
'sort-descending' => 'Järjesta kahanevalt',
'sort-ascending' => 'Järjesta kasvavalt',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Artikkel',
'nstab-user' => 'Kasutaja leht',
'nstab-media' => 'Meedia',
'nstab-special' => 'Eri',
'nstab-project' => 'Projektileht',
'nstab-image' => 'Pilt',
'nstab-mediawiki' => 'Sõnum',
'nstab-template' => 'Mall',
'nstab-help' => 'Juhend',
'nstab-category' => 'Kategooria',

# Main script and global functions
'nosuchaction' => 'Sellist toimingut pole.',
'nosuchactiontext' => 'Viki ei tunne internetiaadressile vastavat tegevust.
Võimalik, et sa sisestasid aadressi valesti või kasutasid vigast linki.
Samuti ei ole välistatud, et tarkvaras, mida {{SITENAME}} kasutatab, on viga.',
'nosuchspecialpage' => 'Sellist erilehekülge pole.',
'nospecialpagetext' => '<strong>Viki ei tunne erilehekülge, mille poole pöördusid.</strong>

Käibel olevad erileheküljed on loetletud leheküljel [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Viga',
'databaseerror' => 'Andmebaasi viga',
'dberrortext' => 'Andmebaasipäringus oli süntaksiviga.
Selle võis tingida tarkvaraviga.
Viimane andmebaasipäring oli:
<blockquote><code>$1</code></blockquote>
ja see ilmnes funktsiooniga "<code>$2</code>".
Andmebaas tagastas veateate "<samp>$3: $4</samp>".',
'dberrortextcl' => 'Andmebaasipäringus oli süntaksiviga.
Viimane andmebaasipäring oli:
"$1"
ja see kutsuti funktsioonist "$2".
Andmebaas tagastas veateate "$3: $4".',
'laggedslavemode' => 'Hoiatus: Leheküljel võivad puududa viimased uuendused.',
'readonly' => 'Andmebaas on hetkel kirjutuskaitse all',
'enterlockreason' => 'Sisesta lukustamise põhjus ning juurdepääsu taastamise ligikaudne aeg',
'readonlytext' => 'Andmebaas on praegu kirjutuskaitse all, tõenäoliselt andmebaasi harjumuslikuks hoolduseks, mille lõppedes tavaline olukord taastub.
Ülem, kes selle kaitse alla võttis, andis järgmise selgituse:
<p>$1',
'missing-article' => 'Andmebaas ei leidnud küsitud lehekülje "$1" $2 teksti.

Põhjuseks võib olla võrdlus- või ajaloolink kustutatud leheküljele.

Kui tegemist ei ole nimetatud olukorraga, võib tegu olla ka süsteemi veaga.
Sellisel juhul tuleks teavitada [[Special:ListUsers/sysop|administraatorit]], edastades talle ka käesoleva lehe internetiaadressi.',
'missingarticle-rev' => '(redaktsioon: $1)',
'missingarticle-diff' => '(redaktsioonid: $1, $2)',
'readonly_lag' => "Andmebaas on automaatselt lukustatud, seniks kuni ''slave''-andmebaasid on uuendatud.",
'internalerror' => 'Sisemine viga',
'internalerror_info' => 'Sisemine viga: $1',
'fileappenderrorread' => 'Lisamise käigus ebaõnnestus faili "$1" lugemine.',
'fileappenderror' => 'Faili "$1" ei saanud lisada failile "$2".',
'filecopyerror' => 'Ei saanud faili "$1" kopeerida nimega "$2".',
'filerenameerror' => 'Ei saanud faili "$1" failiks "$2" ümber nimetada.',
'filedeleteerror' => 'Faili nimega "$1" ei ole võimalik kustutada.',
'directorycreateerror' => 'Kataloogi "$1" ei saanud luua.',
'filenotfound' => 'Faili nimega "$1" ei leitud.',
'fileexistserror' => 'Kirjutamine faili "$1" ebaõnnestus: fail on juba olemas',
'unexpected' => 'Ootamatu väärtus: "$1"="$2".',
'formerror' => 'Viga: vormi ei saanud salvestada',
'badarticleerror' => 'Seda toimingut ei saa sellel leheküljel sooritada.',
'cannotdelete' => 'Lehekülge või faili "$1" ei saa kustutada.
Võimalik, et keegi on selle juba kustutanud.',
'cannotdelete-title' => 'Lehekülge "$1" ei saa kustutada',
'badtitle' => 'Vigane pealkiri',
'badtitletext' => 'Soovitud lehekülje pealkiri oli vigane, tühi või teisest keeleversioonist või vikist valesti lingitud.
See võib sisaldada ühte või enamat märki, mida ei saa pealkirjades kasutada.',
'perfcached' => 'Järgnevad andmed on puhverdatud ja ei pruugi olla kõige värskemad. Puhvris on saadaval kuni {{PLURAL:$1|üks tulemus|$1 tulemust}}.',
'perfcachedts' => 'Järgmised andmed on vahemälus. Viimane uuendamisaeg: $2, kell $3. Puhvris on saadaval kuni {{PLURAL:$4|üks tulemus|$4 tulemust}}.',
'querypage-no-updates' => 'Lehekülje uuendamine ei ole hetkel lubatud ning andmeid ei värskendata.',
'wrong_wfQuery_params' => 'Valed parameeterid funktsioonile wfQuery()<br />
Funktsioon: $1<br />
Päring: $2',
'viewsource' => 'Vaata lähteteksti',
'viewsource-title' => 'Lehekülje $1 lähteteksti vaatamine',
'actionthrottled' => 'Toiming nurjus',
'actionthrottledtext' => 'Rämpsmuudatuste vastase meetmena pole sul lühikse aja jooksul seda toimingut liiga palju kordi lubatud sooritada. Sa oled lühikse aja jooskul seda toimingut liiga palju kordi sooritanud.
Palun proovi mõne minuti pärast uuesti.',
'protectedpagetext' => 'See lehekülg on lukustatud, et ei tehtaks muudatusi ega sooritataks muid toiminguid.',
'viewsourcetext' => 'Saad vaadata ja kopeerida lehekülje lähteteksti:',
'viewyourtext' => "Saad vaadata ja kopeerida sellel leheküljel tehtud '''enda muudatuste '''lähteteksti:",
'protectedinterface' => 'Sellel leheküljel on selle viki tarkvara kasutajaliidese tekst. Väärtarvituse vältimiseks on lehekülg kaitstud.
Et lisada ja muuta tõlkeid kõigi vikide jaoks, kasuta palun MediaWiki lokaliseerimisprojekti [//translatewiki.net/ translatewiki.net].',
'editinginterface' => "'''Hoiatus:''' Redigeerid tarkvara kasutajaliidese tekstiga lehekülge.
Muudatused siin mõjutavad kõikide selle viki kasutajate kasutajaliidest.
Et lisada ja muuta tõlkeid kõigi vikide jaoks, kasuta palun MediaWiki lokaliseerimisprojekti [//translatewiki.net/ translatewiki.net].",
'sqlhidden' => '(SQL päring peidetud)',
'cascadeprotected' => 'See lehekülg on muutmise eest kaitstud, sest see on osa {{PLURAL:$1|järgmisest leheküljest|järgmistest lehekülgedest}}, mis on kaskaadkaitse all:
$2',
'namespaceprotected' => "Sul pole õigust redigeerida lehekülgi nimeruumis '''$1'''.",
'customcssprotected' => 'Sul pole õigust seda CSS-lehekülge redigeerida, sest see sisaldab teise kasutaja isiklikke sätteid.',
'customjsprotected' => 'Sul pole õigust seda JavaScripti lehekülge redigeerida, sest see sisaldab teise kasutaja isiklikke sätteid.',
'ns-specialprotected' => 'Erilehekülgi ei saa redigeerida.',
'titleprotected' => "Kasutaja [[User:$1|$1]] on selle pealkirjaga lehe loomise keelanud esitades järgmise põhjenduse: ''$2''.",
'filereadonlyerror' => 'Faili "$1" ei saa muuta, sest hoidla "$2" on kirjutuskaitstud.

Administraator lukustas selle järgmisel põhjusel: "$3".',
'invalidtitle-knownnamespace' => 'Vigane pealkiri nimeruumis "$2" tekstiga "$3"',
'invalidtitle-unknownnamespace' => 'Vigane pealkiri nimeruuminumbriga $1 ja tekstiga "$2"',
'exception-nologin' => 'Sisse logimata',
'exception-nologin-text' => 'Selle lehekülje või toimingu jaoks pead selles vikis olema sisse loginud.',

# Virus scanner
'virus-badscanner' => "Viga konfiguratsioonis: tundmatu viirusetõrje: ''$1''",
'virus-scanfailed' => 'skaneerimine ebaõnnestus (veakood $1)',
'virus-unknownscanner' => 'tundmatu viirusetõrje:',

# Login and logout pages
'logouttext' => "'''Oled nüüd välja loginud.'''

Võid jätkata {{GRAMMAR:genitive|{{SITENAME}}}} kasutamist anonüümselt, aga ka sama või mõne teise kasutajana uuesti [[Special:UserLogin|sisse logida]].
Pane tähele, et seni kuni sa pole oma võrgulehitseja puhvrit tühjendanud, võidakse mõni lehekülg endiselt nii kuvada nagu oleksid ikka sisse logitud.",
'welcomecreation' => '== Tere tulemast, $1! ==

Sinu konto on loodud.
Ära unusta oma {{GRAMMAR:genitive|{{SITENAME}}}} [[Special:Preferences|eelistusi]] seada.',
'yourname' => 'Kasutajanimi:',
'yourpassword' => 'Parool:',
'yourpasswordagain' => 'Sisesta parool uuesti:',
'remembermypassword' => 'Jäta parool meelde (kuni $1 {{PLURAL:$1|päevaks|päevaks}})',
'securelogin-stick-https' => 'Jätka pärast sisselogimist HTTPS-ühenduse kasutamist',
'yourdomainname' => 'Sinu domeen:',
'password-change-forbidden' => 'Selles vikis ei saa paroole muuta.',
'externaldberror' => 'Esines autentimistõrge või sul pole õigust konto andmeid muuta.',
'login' => 'Logi sisse',
'nav-login-createaccount' => 'Logi sisse või registreeru kasutajaks',
'loginprompt' => 'Sisselogimiseks peavad küpsised lubatud olema.',
'userlogin' => 'Sisselogimine või kasutajakonto loomine',
'userloginnocreate' => 'Sisselogimine',
'logout' => 'Logi välja',
'userlogout' => 'Logi välja',
'notloggedin' => 'Sisse logimata',
'nologin' => "Sul pole kontot? '''$1'''.",
'nologinlink' => 'Registreeru siin',
'createaccount' => 'Loo uus konto',
'gotaccount' => "Kui sul on juba konto, '''$1'''.",
'gotaccountlink' => 'logi sisse',
'userlogin-resetlink' => 'Kas oled unustanud oma sisselogimisandmed?',
'createaccountmail' => 'E-posti teel',
'createaccountreason' => 'Põhjus:',
'badretype' => 'Sisestatud paroolid ei lange kokku.',
'userexists' => 'Sisestatud kasutajanimi on juba kasutusel.
Palun valige uus nimi.',
'loginerror' => 'Viga sisselogimisel',
'createaccounterror' => 'Kasutajakonto loomine ebaõnnestus: $1',
'nocookiesnew' => 'Kasutajakonto loodi, aga sa ei ole sisse logitud, sest {{SITENAME}} kasutab kasutajate tuvastamisel küpsiseid. Sinu brauseris on küpsised keelatud. Palun sea küpsised lubatuks ja logi siis oma vastse kasutajanime ning parooliga sisse.',
'nocookieslogin' => '{{SITENAME}} kasutab kasutajate tuvastamisel küpsiseid. Sinu brauseris on küpsised keelatud. Palun sea küpsised lubatuks ja proovi siis uuesti.',
'nocookiesfornew' => 'Kasutajakonto jäi loomata, kuna me ei saanud selle allikat kindlaks teha.
Veendu, et sul on küpsised lubatud, taaslaadi see lehekülg ja proovi uuesti.',
'noname' => 'Sa ei sisestanud kasutajanime lubataval kujul.',
'loginsuccesstitle' => 'Sisselogimine õnnestus',
'loginsuccess' => 'Oled sisse loginud. Sinu kasutajanimi on "$1".',
'nosuchuser' => 'Kasutajat "$1" ei ole olemas.
Kasutajanimed on tõstutundlikud.
Kontrollige kirjapilti või [[Special:UserLogin/signup|looge uus kasutajakonto]].',
'nosuchusershort' => 'Kasutajat nimega "$1" ei ole olemas. Kontrollige kirjapilti.',
'nouserspecified' => 'Kasutajanimi puudub.',
'login-userblocked' => 'See kasutaja on blokeeritud. Sisselogimine pole lubatud.',
'wrongpassword' => 'Vale parool. Proovige uuesti.',
'wrongpasswordempty' => 'Parool jäi sisestamata. Palun proovi uuesti.',
'passwordtooshort' => 'Parool peab koosnema vähemalt {{PLURAL:$1|ühest|$1}} tähemärgist.',
'password-name-match' => 'Parool peab kasutajanimest erinema.',
'password-login-forbidden' => 'Selle kasutajanime ja parooli kasutamine on keelatud.',
'mailmypassword' => 'Saada e-posti teel uus parool',
'passwordremindertitle' => '{{SITENAME}} – ajutine parool',
'passwordremindertext' => 'Keegi IP-aadressiga $1, tõenäoliselt sa ise, palus, et talle saadetaks {{GRAMMAR:elative|{{SITENAME}}}} uus parool ($4). Kasutaja "$2" ajutiseks paroolis seati "$3". Kui soovid tõepoolest uut parooli, pead sisse logima ja uue parooli valima. Ajutine parool aegub {{PLURAL:$5|ühe päeva|$5 päeva}} pärast.

Kui uut parooli palus keegi teine või sulle meenus vana parool ja sa ei soovi seda enam muuta, võid käesolevat teadet eirata ning jätkata endise parooli kasutamist.',
'noemail' => 'Kasutaja $1 e-posti aadressi meil kahjuks pole.',
'noemailcreate' => 'Pead sisestama korrektse e-posti aadressi',
'passwordsent' => 'Uus parool on saadetud kasutaja $1 registreeritud e-postiaadressil.
Pärast parooli saamist logige palun sisse.',
'blocked-mailpassword' => 'Sinu IP-aadressi jaoks on toimetamine blokeeritud, seetõttu ei saa sa kasutada ka parooli meeldetuletamise funktsiooni.',
'eauthentsent' => 'Sisestatud e-posti aadressile on saadetud kinnituse e-kiri.
Enne kui su kontole ükskõik milline muu e-kiri saadetakse, pead sa e-kirjas olevat juhist järgides kinnitama, et konto on tõepoolest sinu.',
'throttled-mailpassword' => 'Parooli meeldetuletus lähetatud viimase {{PLURAL:$1|tunni|$1 tunni}} jooksul.
Väärtarvitamise vältimiseks saadetakse {{PLURAL:$1|tunni|$1 tunni}} jooksul ainult üks meeldetuletus.',
'mailerror' => 'Viga kirja saatmisel: $1',
'acct_creation_throttle_hit' => 'Selle viki külastajad, kes kasutavad sinu IP-aadressi, on viimase ööpäeva jooksul loonud {{PLURAL:$1|ühe konto|$1 kontot}}, mis on selles ajavahemikus ülemmääraks.
Seetõttu ei saa seda IP-aadressi kasutades hetkel rohkem kontosid luua.',
'emailauthenticated' => 'Sinu e-posti aadressi kinnitamisaeg: $2 kell $3.',
'emailnotauthenticated' => 'Sinu e-posti aadress <strong>pole veel kinnitatud</strong>. Järgnevate funktsioonidega seotud e-kirju kinnitamata aadressile ei saadeta.',
'noemailprefs' => 'Järgnevate võimaluste toimimiseks on vaja sisestada e-posti aadress.',
'emailconfirmlink' => 'Kinnita oma e-posti aadress',
'invalidemailaddress' => 'E-posti aadress ei ole aktsepteeritav, sest see on vigaselt kirjutatud.
Ole hea ja sisesta õige e-posti aadress või jäta väli tühjaks.',
'cannotchangeemail' => 'Selles vikis ei saa konto e-posti aadressi muuta.',
'emaildisabled' => 'Selle võrgukoha kaudu ei saa e-kirju saata.',
'accountcreated' => 'Konto loodud',
'accountcreatedtext' => 'Kasutajakonto kasutajatunnusele $1 loodud.',
'createaccount-title' => '{{GRAMMAR:illative|{{SITENAME}}}} konto loomine',
'createaccount-text' => 'Keegi on loonud {{GRAMMAR:illative|{{SITENAME}}}} ($4) sinu e-posti aadressile vastava kasutajatunnuse "$2". Parooliks seati "$3". Logi sisse ja muuda oma parool.

Kui kasutajakonto loomine on eksitus, võid käesolevat sõnumit lihtsalt eirata.',
'usernamehasherror' => 'Kasutajanimi ei või sisaldada trellimärke ("#").',
'login-throttled' => 'Oled lühikese aja jooksul liiga palju äpardunud logimiskatseid sooritanud.
Palun pea nüüd pisut vahet.',
'login-abort-generic' => 'Sisselogimine ebaõnnestus – Katkestatud',
'loginlanguagelabel' => 'Keel: $1',
'suspicious-userlogout' => 'Sinu väljalogimiskatse nurjus, sest see näis olevat katkise veebilehitseja või puhverserveri saadetud.',

# E-mail sending
'php-mail-error-unknown' => 'Tundmatu tõrge PHP funktsioonis mail().',
'user-mail-no-addy' => 'Püüdsid saata e-kirja ilma meiliaadressita.',

# Change password dialog
'resetpass' => 'Parooli muutmine',
'resetpass_announce' => 'Logisid sisse e-posti teel saadud ajutise koodiga.
Sisselogimise lõpetamiseks pead siia uue parooli sisestama:',
'resetpass_text' => '<!-- Lisa tekst siia -->',
'resetpass_header' => 'Konto parooli muutmine',
'oldpassword' => 'Vana parool:',
'newpassword' => 'Uus parool:',
'retypenew' => 'Sisesta uus parool uuesti:',
'resetpass_submit' => 'Sisesta parool ja logi sisse',
'resetpass_success' => 'Sinu parool on edukalt muudetud! Sisselogimine...',
'resetpass_forbidden' => 'Paroole ei saa muuta',
'resetpass-no-info' => 'Pead olema sisselogitud, et sellele lehele pääseda.',
'resetpass-submit-loggedin' => 'Muuda parool',
'resetpass-submit-cancel' => 'Loobu',
'resetpass-wrong-oldpass' => 'Vigane ajutine või praegune salasõna.
Võib-olla oled juba edukalt muudnud oma salasõna või taotlenud uut ajutist salasõna.',
'resetpass-temp-password' => 'Ajutine parool:',

# Special:PasswordReset
'passwordreset' => 'Parooli lähtestamine',
'passwordreset-text' => 'Täida see vorm, et saada e-kiri oma konto andmetega.',
'passwordreset-legend' => 'Parooli lähtestamine',
'passwordreset-disabled' => 'Selles vikis on paroolide lähtestamine keelatud.',
'passwordreset-pretext' => '{{PLURAL:$1||Sisesta üks järgmistest andmeüksustest}}',
'passwordreset-username' => 'Kasutajanimi:',
'passwordreset-domain' => 'Domeen:',
'passwordreset-capture' => 'Näita lähetatavat e-kirja?',
'passwordreset-capture-help' => 'Kui valid selle märkeruudu, näidatakse sulle ajutist parooli sisaldavat e-kirja, mis ühtlasi kasutajale saadetakse.',
'passwordreset-email' => 'E-posti aadress:',
'passwordreset-emailtitle' => '{{GRAMMAR:genitive|{{SITENAME}}}} konto andmed',
'passwordreset-emailtext-ip' => 'Keegi, arvatavasti sina ise, IP-aadressilt $1 palus meelde tuletada sinu {{GRAMMAR:genitive|{{SITENAME}}}} ($4) konto üksikasjad. Selle e-posti aadressiga on seotud {{PLURAL:$3|järgmine konto|järgmised kontod}}:

$2

{{PLURAL:$3|See ajutine parool aegub|Need ajutised paroolid aeguvad}} {{PLURAL:$5|ühe|$5}} päeva pärast.
Peaksid nüüd sisse logima ja uue parooli valima. Kui selle palve esitas keegi teine või kui sulle meenus su parool ja sa ei soovi seda enam muuta, võid teadet eirata ja jätkata vana parooli kasutamist.',
'passwordreset-emailtext-user' => '{{GRAMMAR:genitive|{{SITENAME}}}} kasutaja $1 palus meelde tuletada sinu {{GRAMMAR:genitive|{{SITENAME}}}} ($4) konto üksikasjad. Selle e-posti aadressiga on seotud {{PLURAL:$3|järgmine konto|järgmised kontod}}:

$2

{{PLURAL:$3|See ajutine parool aegub|Need ajutised paroolid aeguvad}} {{PLURAL:$5|ühe|$5}} päeva pärast.
Peaksid nüüd sisse logima ja uue parooli valima. Kui selle palve esitas keegi teine või kui sulle meenus su parool ja sa ei soovi seda enam muuta, võid teadet eirata ja jätkata vana parooli kasutamist.',
'passwordreset-emailelement' => 'Kasutajanimi: $1
Ajutine parool: $2',
'passwordreset-emailsent' => 'Meeldetuletuskiri on saadetud.',
'passwordreset-emailsent-capture' => 'E-kirjatsi on saadetud allpool näidatav meeldetuletus.',
'passwordreset-emailerror-capture' => 'Koostati allpool näidatav meeldetuletus, aga selle e-kirjatsi kasutajale saatmine ebaõnnestus: $1',

# Special:ChangeEmail
'changeemail' => 'E-posti aadressi muutmine',
'changeemail-header' => 'Konto e-posti aadressi muutmine',
'changeemail-text' => 'Täida see vorm, et muuta oma e-posti aadress. Et seda muudatust kinnitada, pead sisestama oma parooli.',
'changeemail-no-info' => 'Otselingi kaudu sellele lehele jõudmiseks pead olema sisse loginud.',
'changeemail-oldemail' => 'Praegune e-posti aadress:',
'changeemail-newemail' => 'Uus e-posti aadress:',
'changeemail-none' => '(puudub)',
'changeemail-submit' => 'Muuda e-posti aadress',
'changeemail-cancel' => 'Loobu',

# Edit page toolbar
'bold_sample' => 'Rasvane kiri',
'bold_tip' => 'Rasvane kiri',
'italic_sample' => 'Kaldkiri',
'italic_tip' => 'Kaldkiri',
'link_sample' => 'Lingitav pealkiri',
'link_tip' => 'Siselink',
'extlink_sample' => 'http://www.example.com Lingi nimi',
'extlink_tip' => 'Välislink (ära unusta eesliidet http://)',
'headline_sample' => 'Pealkiri',
'headline_tip' => '2. taseme pealkiri',
'nowiki_sample' => 'Sisesta vormindamata tekst',
'nowiki_tip' => 'Ignoreeri viki vormindust',
'image_sample' => 'Näidis.jpg',
'image_tip' => 'Pilt',
'media_sample' => 'Näidis.ogg',
'media_tip' => 'Link failile',
'sig_tip' => 'Sinu allkiri ajatempliga',
'hr_tip' => 'Horisontaalkriips (kasuta säästlikult)',

# Edit pages
'summary' => 'Resümee:',
'subject' => 'Pealkiri:',
'minoredit' => 'See on pisiparandus',
'watchthis' => 'Jälgi seda lehekülge',
'savearticle' => 'Salvesta',
'preview' => 'Eelvaade',
'showpreview' => 'Näita eelvaadet',
'showlivepreview' => 'Näita eelvaadet',
'showdiff' => 'Näita muudatusi',
'anoneditwarning' => "'''Hoiatus:''' Sa pole sisse logitud.
Selle lehe redigeerimislogisse salvestatakse su IP-aadress.",
'anonpreviewwarning' => "''Sa pole sisse logitud. Selle lehe redigeerimislogisse salvestatakse su IP-aadress.''",
'missingsummary' => "'''Meeldetuletus:''' Sa ei ole lisanud muudatuse resümeed.
Kui vajutad uuesti salvestamise nupule, salvestatakse muudatus ilma resümeeta.",
'missingcommenttext' => 'Palun sisesta siit allapoole kommentaar.',
'missingcommentheader' => "'''Meeldetuletus:''' Sa pole kirjutanud kommentaarile teemat ega pealkirja.
Kui klõpsad uuesti \"{{int:savearticle}}\", salvestatakse su kommentaar kummatagi.",
'summary-preview' => 'Resümee eelvaade:',
'subject-preview' => 'Alaosa pealkirja eelvaade:',
'blockedtitle' => 'Kasutaja on blokeeritud',
'blockedtext' => "'''Sinu kasutajanimi või IP-aadress on blokeeritud.'''

Blokeeris $1.
Tema põhjendus on järgmine: ''$2''.

* Blokeeringu algus: $8
* Blokeeringu lõpp: $6
* Sooviti blokeerida: $7

Küsimuse arutamiseks võid pöörduda kasutaja $1 või mõne teise [[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pane tähele, et sa ei saa kasutajale teadet saata, kui sa pole kinnitanud oma [[Special:Preferences|eelistuste lehel]] kehtivat e-posti aadressi.

Sinu praegune IP-aadress on $3 ning blokeeringu number on #$5. Lisa need andmed kõigile järelepärimistele, mida kavatsed teha.",
'autoblockedtext' => "Sinu IP-aadress blokeeriti automaatselt, sest seda kasutas teine kasutaja, kelle $1 blokeeris.
Põhjendus on järgmine:

:''$2''

* Blokeeringu algus: $8
* Blokeeringu lõpp: $6
* Sooviti blokeerida: $7

Küsimuse arutamiseks võid pöörduda kasutaja $1 või mõne teise [[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pane tähele, et sa ei saa teisele kasutajale teadet saata, kui sa pole kinnitanud oma [[Special:Preferences|eelistuste lehel]] kehtivat e-posti aadressi ega ole selle kasutamisest blokeeritud.

Sinu praegune IP-aadress on $3 ja blokeeringu number #$5. Lisa need andmed kõigile järelpärimistele, mida kavatsed teha.",
'blockednoreason' => 'põhjendust ei ole kirja pandud',
'whitelistedittext' => 'Lehekülgede toimetamiseks pead $1.',
'confirmedittext' => 'Lehekülgi ei saa toimetada enne e-posti aadressi kinnitamist.
Palun määra ja kinnita e-posti aadress [[Special:Preferences|eelistuste leheküljel]].',
'nosuchsectiontitle' => 'Sellist alaosa pole',
'nosuchsectiontext' => 'Üritasid redigeerida alaosa, mida pole.
Võimalik, et see teisaldati või kustutati, sellal kui lehekülge vaatasid.',
'loginreqtitle' => 'Vajalik on sisselogimine',
'loginreqlink' => 'sisse logima',
'loginreqpagetext' => 'Lehekülgede vaatamiseks pead $1.',
'accmailtitle' => 'Parool saadetud.',
'accmailtext' => "Kasutajale '$1' genereeritud juhuslik parool saadeti aadressile $2.

Seda parooli on võimalik muuta ''[[Special:ChangePassword|parooli muutmise lehel]]'' peale uuele kontole sisse logimist.",
'newarticle' => '(Uus)',
'newarticletext' => "Lehekülge, kuhu link sind suunas, pole veel.
Lehekülje loomiseks alusta allolevasse kasti kirjutamist (lisateave [[{{MediaWiki:Helppage}}|juhendist]]).
Kui sattusid siia kogemata, klõpsa võrgulehitseja ''tagasi''-nupule.",
'anontalkpagetext' => "----''See on anonüümse kasutaja arutelulehekülg. See kasutaja pole kontot loonud või ei kasuta seda. Sellepärast tuleb meil kasutaja tuvastamiseks kasutada tema IP-aadressi. Sellist IP-aadressi võib kasutada mitu kasutajat. Kui oled osutatud IP-aadressi kasutaja ning leiad, et siinsed kommentaarid ei puutu kuidagi sinusse, [[Special:UserLogin/signup|loo palun kasutajakonto]] või [[Special:UserLogin|logi sisse]], et sind edaspidi teiste anonüümsete kasutajatega segi ei aetaks.''",
'noarticletext' => 'Käesoleval leheküljel hetkel teksti ei ole.
Võid [[Special:Search/{{PAGENAME}}|otsida pealkirjaks olevat fraasi]] teistelt lehtedelt,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} uurida asjassepuutuvaid logisid] või [{{fullurl:{{FULLPAGENAME}}|action=edit}} puuduva lehekülje ise luua]</span>.',
'noarticletext-nopermission' => 'Sellel leheküljel pole praegu teksti.
Saad [[Special:Search/{{PAGENAME}}|otsida selle lehekülje pealkirja]] teistelt lehekülgedelt või <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} otsida seonduvatest logidest]</span>, aga sul pole õigust seda lehekülge alustada.',
'missing-revision' => 'Lehekülje "{{PAGENAME}}" redaktsiooni $1 pole.

Harilikult tähendab see seda, et sind siia juhatanud link on vananenud ja siin asunud lehekülg on kustutatud.
Üksikasjad leiad [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamislogist].',
'userpage-userdoesnotexist' => 'Kasutajakontot "<nowiki>$1</nowiki>" pole olemas.
Palun mõtle järele, kas soovid seda lehte luua või muuta.',
'userpage-userdoesnotexist-view' => 'Kasutajakonto "$1" pole registreeritud.',
'blocked-notice-logextract' => 'See kasutaja on praegu blokeeritud.
Allpool on toodud viimane blokeerimislogi sissekanne:',
'clearyourcache' => "'''Märkus:''' Võimalik, et pärast salvestamist tuleb muudatuste nägemiseks veebilehitseja puhver tühjendada.
* '''Firefox / Safari:''' Hoia all ''Shift''-klahvi ja klõpsa ''Laadi uuesti'' või vajuta kas ''Ctrl-F5'' või ''Ctrl-R'' (Macis ''⌘-R'').
* '''Google Chrome:''' Vajuta ''Ctrl-Shift-R'' (Macis ''⌘-Shift-R'').
* '''Internet Explorer:''' Hoia all ''Ctrl''-klahvi ja klõpsa ''Värskenda'' või vajuta ''Ctrl-F5''.
* '''Opera:''' Tühjenda puhver asukohas ''Seaded → Eelistused''.",
'usercssyoucanpreview' => "'''Vihje:''' Enne salvestamist kasuta oma uue CSSi proovimiseks nuppu \"{{int:showpreview}}\".",
'userjsyoucanpreview' => "'''Vihje:''' Enne salvestamist kasuta oma uue JavaScripti proovimiseks nuppu \"{{int:showpreview}}\".",
'usercsspreview' => "'''Ära unusta, et seda versiooni sinu isiklikust stiililehest pole veel salvestatud!'''",
'userjspreview' => "'''Ära unusta, et see versioon sinu isiklikust JavaScriptist on alles salvestamata!'''",
'sitecsspreview' => "'''Pea meeles, et see on vaid selle stiililehe eelvaade.'''
'''Seda pole veel salvestatud!'''",
'sitejspreview' => "'''Pea meeles, et see on vaid selle JavaScripti-koodi eelvaade.'''
'''Seda pole veel salvestatud!'''",
'userinvalidcssjstitle' => "'''Hoiatus:''' Kujundust nimega \"\$1\" ei ole.
Ära unusta, et kasutaja isiklikud .css- ja .js-lehed kasutavad väiketähega algavaid nimesid, näiteks  {{ns:user}}:Juhan Julm/vector.css ja mitte {{ns:user}}:Juhan Julm/Vector.css.",
'updated' => '(Värskendatud)',
'note' => "'''Meeldetuletus:'''",
'previewnote' => "'''Ära unusta, et see on kõigest eelvaade!'''
Sinu muudatused pole veel salvestatud!",
'continue-editing' => 'Mine redigeerimiskasti juurde',
'previewconflict' => 'See eelvaade näitab, kuidas ülemises toimetuskastis olev tekst hakkab välja nägema, kui otsustate salvestada.',
'session_fail_preview' => "'''Vabandust! Meil ei õnnestunud seansiandmete kaotuse tõttu sinu muudatust töödelda.'''
Palun proovi uuesti.
Kui see ikka ei tööta, proovi [[Special:UserLogout|välja]] ja tagasi sisse logida.",
'session_fail_preview_html' => "'''Vabandust! Meil ei õnnestunud seansiandmete kaotuse tõttu sinu muudatust töödelda.'''

''Kuna {{GRAMMAR:inessive|{{SITENAME}}}} on toor-HTML lubatud, on eelvaade JavaScripti-rünnakute vastase ettevaatusabinõuna peidetud.''

'''Kui see on õigustatud redigeerimiskatse, proovi palun uuesti.'''
Kui see ikka ei tööta, proovi [[Special:UserLogout|välja]] ja tagasi sisse logida.",
'token_suffix_mismatch' => "'''Muudatus lükati tagasi, kuna sinu klienttarkvara ei suuda õigesti kirjavahemärke kasutada.'''
Muudatus lükati tagasi, et vältida lehekülje segiminekut.
See juhtub mõnikord siis, kui kasutatakse vigast veebipõhist anonüümsusserverit.",
'edit_form_incomplete' => "'''Redigeerimisvormi mõni osa ei jõudnud serverisse; kontrolli, kas sinu tehtud muudatused on alles, ja proovi uuesti.'''",
'editing' => 'Redigeerimisel on $1',
'creating' => 'Alustamisel on $1',
'editingsection' => 'Redigeerimisel on osa leheküljest $1',
'editingcomment' => 'Muutmisel on $1 (uus alaosa)',
'editconflict' => 'Redigeerimiskonflikt: $1',
'explainconflict' => "Keegi teine on muutnud seda lehekülge pärast seda, kui sina seda redigeerima hakkasid.
Ülemine toimetamiskast sisaldab teksti viimast versiooni.
Sinu muudatused on alumises kastis.
Sul tuleb need viimasesse versiooni üle viia.
Kui klõpsad nupule \"{{int:savearticle}}\", salvestub '''ainult''' ülemises toimetamiskastis olev tekst.",
'yourtext' => 'Sinu tekst',
'storedversion' => 'Salvestatud redaktsioon',
'nonunicodebrowser' => "'''Hoiatus: Sinu brauser ei toeta Unicode'i.'''
Probleemist möödahiilimiseks, selleks et saaksid lehekülgi turvaliselt redigeerida, näidatakse mitte-ASCII märke toimetuskastis kuueteistkümnendsüsteemi koodidena.",
'editingold' => "'''Ettevaatust: Redigeerid praegu selle lehekülje vana redaktsiooni.'''
Kui selle salvestad, lähevad kõik vahepealsed muudatused kaduma.",
'yourdiff' => 'Erinevused',
'copyrightwarning' => "Pea silmas, et kogu kaastöö võrgukohale {{SITENAME}} loetakse avaldatuks litsentsi $2 all (üksikasjad leheküljel $1). Kui sa ei soovi, et sinu kirjutatut halastamatult redigeeritakse ja oma äranägemise järgi kasutatakse, siis ära seda siia salvesta.<br />
Kinnitad ka, et kirjutasid selle ise või võtsid selle allikast, mille materjale ei kaitsta autoriõigusega või muust sarnasest vabast allikast.<br />
'''Ära salvesta autoriõigusega kaitstud materjali ilma loata!'''",
'copyrightwarning2' => "Pea silmas, et teised kaastöölised võivad kogu {{GRAMMAR:inessive|{{SITENAME}}}} tehtud kaastööd muuta või eemaldada. Kui sa ei soovi, et su kirjutatut halastamatult redigeeritakse, siis ära seda siia salvesta.<br />
Sa kinnitad ka, et kirjutasid selle ise või võtsid selle kopeerimiskitsenduseta allikast (vaata ka $1).
'''Ära saada autoriõigusega kaitstud materjali loata!'''",
'longpageerror' => "'''Tõrge: Lehekülge ei saa salvestada, sest sinu esitatud {{PLURAL:$1|ühe|$1}} kilobaidi suurune tekst ületab {{PLURAL:$2|ühekilobaidist|$2-kilobaidist}} ülemmäära.'''",
'readonlywarning' => "'''Hoiatus: Andmebaas on lukustatud hooldustöödeks, nii et praegu ei saa parandusi salvestada.'''
Võid teksti hilisemaks kasutamiseks alles hoida tekstifailina.

Administraator, kes andmebaasi lukustas, andis järgmise selgituse: $1",
'protectedpagewarning' => "'''Hoiatus: See lehekülg on lukustatud, nii et ainult administraatori õigustega kasutajad saavad seda redigeerida.'''
Allpool on toodud uusim logisissekanne:",
'semiprotectedpagewarning' => "'''Märkus:''' See lehekülg on lukustatud, nii et üksnes registreeritud kasutajad saavad seda muuta.
Allpool on toodud uusim logisissekanne:",
'cascadeprotectedwarning' => "'''Hoiatus:''' See lehekülg on nii lukustatud, et ainult administraatori õigustega kasutajad saavad seda redigeerida, sest lehekülg on osa {{PLURAL:$1|järgmisest|järgmisest}} kaskaadkaitsega {{PLURAL:$1|leheküljest|lehekülgedest}}:",
'titleprotectedwarning' => "'''Hoiatus: See lehekülg on nii lukustatud, et selle loomiseks on tarvis [[Special:ListGroupRights|eriõigusi]].'''
Allpool on toodud uusim logisissekanne:",
'templatesused' => 'Sellel leheküljel on kasutusel {{PLURAL:$1|järgnev mall|järgnevad mallid}}:',
'templatesusedpreview' => 'Eelvaates {{PLURAL:$1|kasutatav mall|kasutatavad mallid}}:',
'templatesusedsection' => 'Selles alaosas {{PLURAL:$1|kasutatav mall|kasutatavad mallid}}:',
'template-protected' => '(kaitstud)',
'template-semiprotected' => '(osaliselt kaitstud)',
'hiddencategories' => 'See lehekülg kuulub {{PLURAL:$1|1 peidetud kategooriasse|$1 peidetud kategooriasse}}:',
'nocreatetitle' => 'Lehekülje loomine piiratud',
'nocreatetext' => 'Lehekülje loomise õigus on {{GRAMMAR:inessive|{{SITENAME}}}} piiratud.
Võid pöörduda tagasi ja toimetada olemasolevat lehekülge või [[Special:UserLogin|sisse logida või uue konto luua]].',
'nocreate-loggedin' => 'Sul ei ole luba luua uusi lehekülgi.',
'sectioneditnotsupported-title' => 'Alaosa redigeerimine pole lubatud.',
'sectioneditnotsupported-text' => 'Sellel leheküljel pole alaosa redigeerimine lubatud.',
'permissionserrors' => 'Viga õigustes',
'permissionserrorstext' => 'Sul pole õigust seda teha {{PLURAL:$1|järgmisel põhjusel|järgmistel põhjustel}}:',
'permissionserrorstext-withaction' => 'Sul pole lubatud {{lcfirst:$2}} {{PLURAL:$1|järgneval põhjusel|järgnevatel põhjustel}}:',
'recreate-moveddeleted-warn' => "'''Hoiatus: Lood uuesti lehekülge, mis on varem kustutatud.'''

Kaalu, kas lehekülje uuesti loomine on kohane.
Lehekülje eelnevad kustutamised ja teisaldamised:",
'moveddeleted-notice' => 'See lehekülg on kustutatud.
Allpool on esitatud lehekülje kustutamis- ja teisaldamislogi.',
'log-fulllog' => 'Vaata kogu logi',
'edit-hook-aborted' => 'Laiendusliides katkestas muutmise täpsemat selgitust andmata.',
'edit-gone-missing' => 'Polnud võimalik lehekülge uuendada.
Tundub, et see on kustutatud.',
'edit-conflict' => 'Redigeerimiskonflikt.',
'edit-no-change' => 'Sinu redigeerimist ignoreeriti, sest tekstile ei olnud tehtud muudatusi.',
'edit-already-exists' => 'Ei saanud alustada uut lehekülge.
See on juba olemas.',
'defaultmessagetext' => 'Sõnumi vaiketekst',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Hoiatus:''' See lehekülg kasutab liialt palju aeglustavaid laiendusfunktsioone. Neid võiks kasutada vähem kui {{PLURAL:$2|ühel|$2}} korral, praegu on kasutatud {{PLURAL:$1|ühel|$1}} korral.",
'expensive-parserfunction-category' => 'Liiga palju aeglasi laiendusfunktsioone kasutavad leheküljed',
'post-expand-template-inclusion-warning' => "'''Hoiatus:''' Väljakutsutavate mallide hulk on liiga suur.
Mistõttu osasid malle ei näidata.",
'post-expand-template-inclusion-category' => 'Leheküljed, milledel on mallide väljakutsumise limiit ületatud',
'post-expand-template-argument-warning' => "'''Hoiatus:''' See lehekülg sisaldab argumendina vähemalt üht malli, mille määratud maht on liiga suur.
Need argumendid on välja jäetud.",
'post-expand-template-argument-category' => 'Malli vahele jäetud argumente sisaldavad leheküljed',
'parser-template-loop-warning' => 'Mallid moodustavad tsükli: [[$1]]',
'parser-template-recursion-depth-warning' => 'Malli rekursiivse kasutamise limiit on ületatud ($1)',
'language-converter-depth-warning' => 'Keeleteisendaja sügavuspiir ületatud ($1)',

# "Undo" feature
'undo-success' => 'Selle redaktsiooni käigus tehtud muudatusi saab eemaldada. Palun kontrolli allolevat võrdlust veendumaks, et tahad need muudatused tõepoolest eemaldada. Seejärel saad lehekülje salvestada.',
'undo-failure' => 'Muudatust ei saa vahapeal tehtud redigeerimiste tõttu tühistada.',
'undo-norev' => 'Muudatust ei saanud tühistada, kuna seda ei ole või see kustutati.',
'undo-summary' => 'Tühistati muudatus $1, mille tegi [[Special:Contributions/$2|$2]] ([[User talk:$2|arutelu]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ei saa kontot luua',
'cantcreateaccount-text' => "Kasutaja [[User:$3|$3]] on blokeerinud kasutajanime loomise sellelt IP-aadressilt ('''$1''').
Kasutaja $3 märkis põhjuseks ''$2''",

# History pages
'viewpagelogs' => 'Vaata selle lehe logisid',
'nohistory' => 'Sellel leheküljel ei ole eelmisi redaktsioone.',
'currentrev' => 'Viimane redaktsioon',
'currentrev-asof' => 'Viimane redaktsioon: $1',
'revisionasof' => 'Redaktsioon: $1',
'revision-info' => 'Redaktsioon seisuga $1 kasutajalt $2',
'previousrevision' => '←Vanem redaktsioon',
'nextrevision' => 'Uuem redaktsioon→',
'currentrevisionlink' => 'Viimane redaktsiooni',
'cur' => 'viim',
'next' => 'järg',
'last' => 'eel',
'page_first' => 'esimene',
'page_last' => 'viimane',
'histlegend' => 'Märgi versioonid, mida tahad võrrelda ja vajuta võrdlemisnupule.
Legend: (viim) = erinevused võrreldes viimase redaktsiooniga,
(eel) = erinevused võrreldes eelmise redaktsiooniga, P = pisimuudatus',
'history-fieldset-title' => 'Ajaloo sirvimine',
'history-show-deleted' => 'Üksnes kustutatud',
'histfirst' => 'Esimesed',
'histlast' => 'Viimased',
'historysize' => '({{PLURAL:$1|1 bait|$1 baiti}})',
'historyempty' => '(tühi)',

# Revision feed
'history-feed-title' => 'Redigeerimiste ajalugu',
'history-feed-description' => 'Selle lehekülje redigeerimiste ajalugu',
'history-feed-item-nocomment' => '$1 – $2',
'history-feed-empty' => 'Soovitud lehekülge ei ole olemas.
See võib olla vikist kustutatud või ümber nimetatud.
Ürita [[Special:Search|vikist otsida]] teemakohaseid lehekülgi.',

# Revision deletion
'rev-deleted-comment' => '(muudatuse resümee eemaldatud)',
'rev-deleted-user' => '(kasutajanimi eemaldatud)',
'rev-deleted-event' => '(logitoiming eemaldatud)',
'rev-deleted-user-contribs' => '[kasutajanimi või IP-aadress eemaldatud – muudatust ei näidata]',
'rev-deleted-text-permission' => "See lehekülje redaktsioon on '''kustutatud'''.
Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].",
'rev-deleted-text-unhide' => "See lehekülje redaktsioon on '''kustutatud'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Kustutamislogis] võib leiduda üksikasju.
Soovi korral saad siiski [$1 seda redaktsiooni] näha.",
'rev-suppressed-text-unhide' => "See redaktsioon leheküljest on '''varjatud'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Varjamislogis] võib leiduda üksikasju.
Saad soovi korral siiski [$1 seda redaktsiooni vaadata].",
'rev-deleted-text-view' => "See lehekülje redaktsioon on '''kustutatud'''.
Saad seda näha. [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Kustutamislogis] võib leiduda üksikasju.",
'rev-suppressed-text-view' => "See redaktsioon leheküljest on '''varjatud'''.
Saad seda vaadata. [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Varjamislogis] võib leiduda üksikasju.",
'rev-deleted-no-diff' => "Seda erinevust ei saa vaadata, kuna üks redaktsioonidest on '''kustutatud'''.
Üksikasju võib olla [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamise logis].",
'rev-suppressed-no-diff' => "Erinevusi ei saa vaadata, sest üks redaktsioonidest on '''kustutatud'''.",
'rev-deleted-unhide-diff' => "Üks selle lehekülje muudatustest on '''kustutatud'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Kustutamislogis] võib leiduda üksikasju.
Soovi korral saad siiski [$1 seda muudatust] näha.",
'rev-suppressed-unhide-diff' => "Üks selle lehekülje muudatustest on '''varjatud'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Varjamislogis] võib leiduda üksikasju.
Saad soovi korral siiski [$1 seda muudatust vaadata].",
'rev-deleted-diff-view' => "Üks selle lehekülje muudatustest on '''kustutatud'''.
Saad seda muudatust vaadata. [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Kustutamislogis] võib leiduda üksikasju.",
'rev-suppressed-diff-view' => "Üks selle lehekülje muudatustest on '''varjatud'''.
Saad seda muudatust vaadata. [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Varjamislogis] võib üksikasju olla.",
'rev-delundel' => 'näita/peida',
'rev-showdeleted' => 'näita',
'revisiondelete' => 'Redaktsioonide kustutamine või taastamine',
'revdelete-nooldid-title' => 'Sellist redaktsiooni pole.',
'revdelete-nooldid-text' => 'Sa pole valinud redaktsiooni, valitud redaktsioon puudub või sa püüad peita viimast redaktsiooni.',
'revdelete-nologtype-title' => 'Logi tüüpi ei antud',
'revdelete-nologtype-text' => 'Sa ei ole selle toimingu sooritamiseks logi tüüpi täpsustanud.',
'revdelete-nologid-title' => 'Vigane logikirje',
'revdelete-nologid-text' => 'Selle logisündmuse kirjet pole määratud või seda ei ole olemas.',
'revdelete-no-file' => 'Faili ei ole.',
'revdelete-show-file-confirm' => 'Kas oled kindel, et soovid häha faili "<nowiki>$1</nowiki>" kustutatud redaktsiooni, mis tehti $2 kell $3?',
'revdelete-show-file-submit' => 'Jah',
'revdelete-selected' => "'''{{PLURAL:$2|Valitud versioon|Valitud versioonid}} artiklist [[:$1]]:'''",
'logdelete-selected' => "'''Valitud {{PLURAL:$1|logisissekanne|logisissekanded}}:'''",
'revdelete-text' => "'''Kustutatud redaktsioonid kajastuvad endiselt lehe ajaloos ja logides, kuid osa nende sisust pole tavakasutajatele nähtav.'''
{{GRAMMAR:genitive|{{SITENAME}}}} administraatorid saavad varjatud sisu siiski vaadata ning seda vajadusel taastada, kui see pole just täiendavalt ära keelatud.",
'revdelete-confirm' => 'Kinnita, et soovid tõesti seda teha ning et saad aru tagajärgedest ja tegevus on kooskõlas [[{{MediaWiki:Policy-url}}|siinsete kokkulepetega]].',
'revdelete-suppress-text' => "Andmed tuleks varjata '''ainult''' järgnevatel juhtudel:
* Sobimatu isiklik teave
*: ''kodune aadress ja telefoninumber, sotsiaalhoolekandenumber jne''",
'revdelete-legend' => 'Nähtavuse piirangute seadmine',
'revdelete-hide-text' => 'Peida redigeerimise tekst',
'revdelete-hide-image' => 'Peida faili sisu',
'revdelete-hide-name' => 'Peida toiming ja sihtmärk',
'revdelete-hide-comment' => 'Peida resümee',
'revdelete-hide-user' => 'Peida toimetaja kasutajanimi või IP-aadress',
'revdelete-hide-restricted' => 'Varja andmeid nii administraatorite kui ka teiste eest.',
'revdelete-radio-same' => '(ära muuda)',
'revdelete-radio-set' => 'Jah',
'revdelete-radio-unset' => 'Ei',
'revdelete-suppress' => 'Varja andmed nii administraatorite kui ka teiste eest',
'revdelete-unsuppress' => 'Eemalda taastatud redaktsioonidelt piirangud',
'revdelete-log' => 'Põhjus:',
'revdelete-submit' => 'Rakenda valitud {{PLURAL:$1|redaktsiooni|redaktsioonide}} suhtes',
'revdelete-success' => "Redaktsiooni nähtavus edukalt värskendatud.'''",
'revdelete-failure' => "'''Redaktsiooni nähtavust ei saanud värskendada:'''
$1",
'logdelete-success' => "'''Logi nähtavus edukalt muudetud.'''",
'logdelete-failure' => "'''Logi nähtavust ei saanud paika:'''
$1",
'revdel-restore' => 'muuda nähtavust',
'revdel-restore-deleted' => 'kustutatud redaktsioonid',
'revdel-restore-visible' => 'nähtavad redaktsioonid',
'pagehist' => 'Lehekülje ajalugu',
'deletedhist' => 'Kustutatud ajalugu',
'revdelete-hide-current' => 'Tõrge üksuse kuupäevaga $2, kell $1 peitmisel: see on praegune redaktsioon.
Seda ei saa peita.',
'revdelete-show-no-access' => 'Tõrge ajatempliga $1 kell $2 üksuse näitamisel: selle on märge "piiranguga".
Sul ei ole sellele ligipääsu.',
'revdelete-modify-no-access' => 'Tõrge üksuse kuupäeva $2, kell $1 muutmisel: see on märgitud kui "piiranguga".
Sul ei ole sellele ligipääsu.',
'revdelete-modify-missing' => 'Tõrge üksuse $1 muutmisel: see puudub andmebaasist!',
'revdelete-no-change' => "'''Hoiatus:''' Üksusel kuupäevaga $2, kell $1 olid juba soovitud nähtavussätted.",
'revdelete-concurrent-change' => 'Tõrge üksuse kuupäevaga $2, kell $1 muutmisel: paistab, et keegi teine on selle olekut sel ajal muutnud, kui sina seda muuta üritasid.
Palun vaata logisid.',
'revdelete-only-restricted' => 'Ei õnnestu varjata üksust seisuga $1, kell $2: Seda üksust ei saa administraatorite eest varjata, valimata seejuures ka ühte muudest nähtavussuvanditest.',
'revdelete-reason-dropdown' => '*Peamised kustutamise põhjused
** Autoriõiguste rikkumine
** Kohatu märkus või isiklik info
** Kohatu kasutajanimi
** Potentsiaalne halvustav informatsioon',
'revdelete-otherreason' => 'Muu või täiendav põhjus:',
'revdelete-reasonotherlist' => 'Muu põhjus',
'revdelete-edit-reasonlist' => 'Redigeeri kustutamise põhjuseid',
'revdelete-offender' => 'Redaktsiooni tegija:',

# Suppression log
'suppressionlog' => 'Varjamislogi',
'suppressionlogtext' => 'Allpool on nimekiri kustutamistest ja blokeeringutest, millega kaasneb administraatorite eest sisu varjamine.
Jõus olevad keelud ja blokeeringud leiad [[Special:BlockList|blokeerimisnimekirja]].',

# History merging
'mergehistory' => 'Ühenda lehtede ajalood',
'mergehistory-header' => 'Siin leheküljel saad ühe lehekülje ajaloo redaktsioonid uuema leheküljega liita.
Veendu, et selle muudatusega jääb lehekülje redigeerimislugu ajaliselt katkematuks.',
'mergehistory-box' => 'Ühenda kahe lehekülje muudatuste ajalugu:',
'mergehistory-from' => 'Lehekülje allikas:',
'mergehistory-into' => 'Sihtlehekülg:',
'mergehistory-list' => 'Ühendatav redigeerimise ajalugu',
'mergehistory-merge' => 'Järgmised lehekülje [[:$1]] redaktsioonid saab liita leheküljega [[:$2]].
Kasuta raadionuppe, et valida ainult redaktsioonid, mis on loodud valitud ajal või varem.
Pane tähele, et navigeerimislinkide kasutamine lähtestab redaktsioonide valiku.',
'mergehistory-go' => 'Näita ühendatavaid muudatusi',
'mergehistory-submit' => 'Ühenda redaktsioonid',
'mergehistory-empty' => 'Ühendatavaid redaktsioone ei ole.',
'mergehistory-success' => 'Lehekülje [[:$1]] {{PLURAL:$3|üks redaktsioon|$3 redaktsiooni}} liideti lehega [[:$2]].',
'mergehistory-fail' => 'Muudatuste ajaloo liitmine ebaõnnestus. Palun kontrolli lehekülje ja aja parameetreid.',
'mergehistory-no-source' => 'Lehekülje allikat $1 ei ole.',
'mergehistory-no-destination' => 'Sihtlehekülge $1 pole olemas.',
'mergehistory-invalid-source' => 'Allikaleheküljel peab olema lubatav pealkiri.',
'mergehistory-invalid-destination' => 'Sihtkoha leheküljel peab olema lubatav pealkiri.',
'mergehistory-autocomment' => 'Liitsin lehe [[:$1]] lehele [[:$2]]',
'mergehistory-comment' => 'Lehekülg [[:$1]] liidetud leheküljele [[:$2]]: $3',
'mergehistory-same-destination' => 'Lähte- ja sihtlehekülg ei saa samad olla',
'mergehistory-reason' => 'Põhjus:',

# Merge log
'mergelog' => 'Liitmislogi',
'pagemerge-logentry' => 'liitis lehekülje [[$1]] leheküljelega [[$2]] (muudatusi kuni $3)',
'revertmerge' => 'Tühista ühendamine',
'mergelogpagetext' => 'Allpool on hiljuti üksteisega liidetud leheküljeajalugude logi.',

# Diffs
'history-title' => 'Lehekülje "$1" muudatuste ajalugu',
'difference-title' => 'Erinevus lehekülje "$1" redaktsioonide vahel',
'difference-title-multipage' => 'Erinevus lehekülgede "$1" ja "$2" vahel',
'difference-multipage' => '(Lehekülgede erinevus)',
'lineno' => 'Rida $1:',
'compareselectedversions' => 'Võrdle valitud redaktsioone',
'showhideselectedversions' => 'Näita/peida valitud versioonid',
'editundo' => 'eemalda',
'diff-multi' => '({{PLURAL:$1|Ühte|$1}} vahepealset {{PLURAL:$2|ühe|$2}} kasutaja redaktsiooni ei näidata.)',
'diff-multi-manyusers' => '({{PLURAL:$1|Ühte|$1}} vahepealset rohkem kui {{PLURAL:$2|ühe|$2}} kasutaja redaktsiooni ei näidata.)',
'difference-missing-revision' => 'Selle erinevuste vaate {{PLURAL:$2|üht|$2}} redaktsiooni ($1) ei leitud.

Harilikult tähendab see seda, et sind siia juhatanud link on vananenud ja siin asunud lehekülg on kustutatud.
Üksikasjad leiad [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kustutamislogist].',

# Search results
'searchresults' => 'Otsingu tulemused',
'searchresults-title' => 'Otsingu "$1" tulemused',
'searchresulttext' => 'Lisateavet otsimise kohta vaata [[{{MediaWiki:Helppage}}|juhendist]].',
'searchsubtitle' => 'Otsisid fraasi "[[:$1]]" ([[Special:Prefixindex/$1|kõik sõnega "$1" algavad lehed]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kõik lehed, mis sisaldavad linke artiklile "$1"]])',
'searchsubtitleinvalid' => 'Päring "$1"',
'toomanymatches' => 'Liiga palju tulemusi, ürita teistsugust päringut',
'titlematches' => 'Vasted artikli pealkirjades',
'notitlematches' => 'Artikli pealkirjades otsitavat ei leitud',
'textmatches' => 'Vasted artikli tekstides',
'notextmatches' => 'Artikli tekstides otsitavat ei leitud',
'prevn' => '{{PLURAL:$1|eelmine|eelmised $1}}',
'nextn' => '{{PLURAL:$1|järgmine|järgmised $1}}',
'prevn-title' => '{{PLURAL:$1|Eelmine tulemus|Eelmised $1 tulemust}}',
'nextn-title' => '{{PLURAL:$1|Järgmine tulemus|Järgmised $1 tulemust}}',
'shown-title' => 'Näita lehekülje kohta $1 {{PLURAL:$1|tulemus|tulemust}}',
'viewprevnext' => 'Näita ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Otsingu sätted',
'searchmenu-exists' => "'''Lehekülg pealkirjaga \"[[:\$1]]\" on olemas.'''",
'searchmenu-new' => "'''Loo lehekülg pealkirjaga \"[[:\$1]]\".'''",
'searchhelp-url' => 'Help:Sisukord',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Sirvi selle eesliitega lehekülgi]]',
'searchprofile-articles' => 'Sisuleheküljed',
'searchprofile-project' => 'Abi- ja projektilehed',
'searchprofile-images' => 'Multimeedia',
'searchprofile-everything' => 'Kõik',
'searchprofile-advanced' => 'Täpsem otsing',
'searchprofile-articles-tooltip' => 'Otsi nimeruumist $1',
'searchprofile-project-tooltip' => 'Otsi nimeruumidest $1',
'searchprofile-images-tooltip' => 'Failiotsing',
'searchprofile-everything-tooltip' => 'Otsi kogu sisust (k.a aruteluleheküljed)',
'searchprofile-advanced-tooltip' => 'Otsi kohandatud nimeruumidest',
'search-result-size' => '$1 ({{PLURAL:$2|1 sõna|$2 sõna}})',
'search-result-category-size' => '{{PLURAL:$1|1 lehekülg|$1 lehekülge}} ({{PLURAL:$2|1 alamkategooria|$2 alamkategooriat}}, {{PLURAL:$3|1 fail|$3 faili}})',
'search-result-score' => 'Vastavus: $1%',
'search-redirect' => '(ümbersuunamine $1)',
'search-section' => '(alaosa $1)',
'search-suggest' => 'Kas mõtlesid: $1',
'search-interwiki-caption' => 'Sõsarprojektid',
'search-interwiki-default' => '$1 tulemused:',
'search-interwiki-more' => '(veel)',
'search-relatedarticle' => 'Seotud',
'mwsuggest-disable' => 'Ära näita otsinguvihjeid',
'searcheverything-enable' => 'Otsi kõigist nimeruumidest',
'searchrelated' => 'seotud',
'searchall' => 'kõik',
'showingresults' => "Allpool näidatakse '''{{PLURAL:$1|ühte|$1}}''' tulemust alates '''$2'''. tulemusest.",
'showingresultsnum' => "Allpool näitame {{PLURAL:$3|'''ühte''' tulemit|'''$3''' tulemit}} alates tulemist #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|'''$1''' '''$3'''-st vastest|Vasted '''$1–$2''' '''$3'''-st}} päringule '''$4'''",
'nonefound' => "'''Märkus''': Otsing hõlmab vaikimisi vaid osasid nimeruume.
Kui soovid otsida ühekorraga kõigist nimeruumidest (kaasa arvatud arutelulehed, mallid, jne) kasuta
päringu ees prefiksit ''all:''. Konkreetsest nimeruumist otsimiseks kasuta prefiksina sele nimeruumi nime.",
'search-nonefound' => 'Päringule ei leitud vasteid.',
'powersearch' => 'Otsi',
'powersearch-legend' => 'Täpsem otsing',
'powersearch-ns' => 'Otsing nimeruumidest:',
'powersearch-redir' => 'Loetle ümbersuunamised',
'powersearch-field' => 'Otsi fraasi',
'powersearch-togglelabel' => 'Vali:',
'powersearch-toggleall' => 'Kõik',
'powersearch-togglenone' => 'Ei ühtegi',
'search-external' => 'Välisotsing',
'searchdisabled' => "Otsimine on preagu keelatud.
Vahepeal saad otsimiseks Google'it kasutada.
Pane tähele, et Google'is talletatud {{GRAMMAR:genitive|{{SITENAME}}}} sisu võib olla iganenud.",

# Quickbar
'qbsettings' => 'Kiirriba sätted',
'qbsettings-none' => 'Ei_ole',
'qbsettings-fixedleft' => 'Püsivalt_vasakul',
'qbsettings-fixedright' => 'Püsivalt paremal',
'qbsettings-floatingleft' => 'Ujuvalt vasakul',
'qbsettings-floatingright' => 'Ujuvalt paremal',
'qbsettings-directionality' => 'Fikseeritud, tulenevalt sinu keeles kasutatava kirja suunast',

# Preferences page
'preferences' => 'Eelistused',
'mypreferences' => 'Eelistused',
'prefs-edits' => 'Redigeerimiste arv:',
'prefsnologin' => 'Sisse logimata',
'prefsnologintext' => 'Oma eelistuste määramiseks pead olema <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} sisse logitud]</span>.',
'changepassword' => 'Muuda parool',
'prefs-skin' => 'Kujundus',
'skin-preview' => 'eelvaade',
'datedefault' => 'Eelistus puudub',
'prefs-beta' => 'Beetafunktsioonid',
'prefs-datetime' => 'Kuupäev ja kellaaeg',
'prefs-labs' => 'Katsefunktsioonid',
'prefs-user-pages' => 'Kasutajaleheküljed',
'prefs-personal' => 'Kasutaja andmed',
'prefs-rc' => 'Viimased muudatused',
'prefs-watchlist' => 'Jälgimisloend',
'prefs-watchlist-days' => 'Mitme päeva muudatusi näidata loendis:',
'prefs-watchlist-days-max' => 'Ülemmäär $1 {{PLURAL:$1|päev|päeva}}',
'prefs-watchlist-edits' => 'Mitu muudatust näidatakse laiendatud jälgimisloendis:',
'prefs-watchlist-edits-max' => 'Ülemmäär: 1000',
'prefs-watchlist-token' => 'Jälgimisloendi tunnus:',
'prefs-misc' => 'Muu',
'prefs-resetpass' => 'Muuda parooli',
'prefs-changeemail' => 'Muuda e-posti aadressi',
'prefs-setemail' => 'Määra e-posti aadress',
'prefs-email' => 'E-posti sätted',
'prefs-rendering' => 'Ilme',
'saveprefs' => 'Salvesta eelistused',
'resetprefs' => 'Lähtesta eelistused',
'restoreprefs' => 'Taasta kõikjal vaikesätted',
'prefs-editing' => 'Toimetamine',
'prefs-edit-boxsize' => 'Toimetamise akna suurus.',
'rows' => 'Ridu:',
'columns' => 'Veerge:',
'searchresultshead' => 'Otsingutulemite sätted',
'resultsperpage' => 'Vasteid leheküljel:',
'stub-threshold' => '<a href="#" class="stub">Nii</a> lingitud lehekülje suuruse ülempiir (baitides):',
'stub-threshold-disabled' => 'Välja lülitatud',
'recentchangesdays' => 'Mitu päeva näidata viimastes muudatustes:',
'recentchangesdays-max' => 'Ülemmäär $1 {{PLURAL:$1|päev|päeva}}',
'recentchangescount' => 'Mitut redaktsiooni vaikimisi näidata:',
'prefs-help-recentchangescount' => 'See käib viimaste muudatuste, lehekülgede ajalugude ja logide kohta.',
'prefs-help-watchlist-token' => 'Selle välja täitmine tekitab sinu jälgimisloendile RSS-toite.
Igaüks, kes teab sellel väljal olevat võtit, saab lugeda sinu jälgimisloendit, seega vali turvaline väärtus.
Siin on juhuslik väärtus, mida saad kasutada: $1',
'savedprefs' => 'Sinu eelistused on salvestatud.',
'timezonelegend' => 'Ajavöönd:',
'localtime' => 'Kohalik aeg:',
'timezoneuseserverdefault' => 'Kasuta serveri vaikesätet ($1)',
'timezoneuseoffset' => 'Muu (määra ajavahe)',
'timezoneoffset' => 'Ajavahe¹:',
'servertime' => 'Serveri aeg:',
'guesstimezone' => 'Loe aeg brauserist',
'timezoneregion-africa' => 'Aafrika',
'timezoneregion-america' => 'Ameerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic' => 'Arktika',
'timezoneregion-asia' => 'Aasia',
'timezoneregion-atlantic' => 'Atlandi ookean',
'timezoneregion-australia' => 'Austraalia',
'timezoneregion-europe' => 'Euroopa',
'timezoneregion-indian' => 'India ookean',
'timezoneregion-pacific' => 'Vaikne ookean',
'allowemail' => 'Luba teistel kasutajatel mulle e-kirju saata',
'prefs-searchoptions' => 'Otsimine',
'prefs-namespaces' => 'Nimeruumid',
'defaultns' => 'Muul juhul otsi järgmistest nimeruumidest:',
'default' => 'vaikeväärtus',
'prefs-files' => 'Failid',
'prefs-custom-css' => 'kohandatud CSS',
'prefs-custom-js' => 'kohandatud JS',
'prefs-common-css-js' => 'Kõigi kujunduste ühine CSS/JS:',
'prefs-reset-intro' => 'Sellel leheküljel saad oma eelistused lähtestada võrgukoha vaike-eelistusteks.
Toimingut ei saa hiljem tühistada.',
'prefs-emailconfirm-label' => 'E-posti kinnitus:',
'prefs-textboxsize' => 'Toimetamisakna suurus',
'youremail' => 'E-posti aadress:',
'username' => 'Kasutajanimi:',
'uid' => 'Kasutaja ID:',
'prefs-memberingroups' => 'Kuulub {{PLURAL:$1|rühma|rühmadesse}}:',
'prefs-registration' => 'Registreerumise aeg:',
'yourrealname' => 'Tegelik nimi:',
'yourlanguage' => 'Keel:',
'yourvariant' => 'Kiri:',
'prefs-help-variant' => 'Kiri või kirjaviis, milles eelistad selle viki sisulehekülgi kuvada.',
'yournick' => 'Uus allkiri:',
'prefs-help-signature' => 'Kommentaarile tuleks aruteluleheküljel alla kirjutada märkidega <nowiki>~~~~</nowiki>, mis muutuvad sinu allkirjaks ja ajatempliks.',
'badsig' => 'Sobimatu allkiri.
Palun kontrolli HTML koodi.',
'badsiglength' => 'Sinu allkiri on liiga pikk.
See ei tohi olla pikem kui {{PLURAL:$1|üks märk|$1 märki}}.',
'yourgender' => 'Sugu:',
'gender-unknown' => 'Määramata',
'gender-male' => 'Mees',
'gender-female' => 'Naine',
'prefs-help-gender' => 'Vabatahtlik: kasutatakse mõnedes keeltes sooliselt korrektse väljendumise otstarbel. Info on avalik.',
'email' => 'E-post',
'prefs-help-realname' => 'Vabatahtlik. Kui otsustad päris nime avaldada, kasutatakse seda sinu kaastöö seostamiseks sinuga.',
'prefs-help-email' => 'E-posti aadressi sisestamine pole kohustuslik, kuid võimaldab sul tellida parooli meeldetuletuse, kui peaksid oma parooli unustama.',
'prefs-help-email-others' => 'Võid ka paluda teistel sinu kasutaja- või aruteluleheküljel sinuga ühendust võtta, selle asemel, et peaksid oma isikuandmeid avaldama.',
'prefs-help-email-required' => 'E-posti aadress on vajalik.',
'prefs-info' => 'Põhiteave',
'prefs-i18n' => 'Rahvusvaheline',
'prefs-signature' => 'Allkiri',
'prefs-dateformat' => 'Kuupäeva vorming',
'prefs-timeoffset' => 'Ajavahe',
'prefs-advancedediting' => 'Täpsemad eelistused',
'prefs-advancedrc' => 'Täpsemad eelistused',
'prefs-advancedrendering' => 'Täpsemad eelistused',
'prefs-advancedsearchoptions' => 'Täpsemad eelistused',
'prefs-advancedwatchlist' => 'Täpsemad eelistused',
'prefs-displayrc' => 'Kuvasätted',
'prefs-displaysearchoptions' => 'Kuvasätted',
'prefs-displaywatchlist' => 'Kuvasätted',
'prefs-diffs' => 'Erinevused',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'Sobiv e-posti aadress',
'email-address-validity-invalid' => 'Sisesta sobiv e-posti aadress.',

# User rights
'userrights' => 'Kasutaja õiguste muutmine',
'userrights-lookup-user' => 'Kasutajarühma muutmine',
'userrights-user-editname' => 'Sisesta kasutajatunnus:',
'editusergroup' => 'Muuda kasutajarühma',
'editinguser' => "Kasutaja '''[[User:$1|$1]]''' $2 õiguste muutmine",
'userrights-editusergroup' => 'Kasutajarühma valik',
'saveusergroups' => 'Salvesta rühma muudatused',
'userrights-groupsmember' => 'Kuulub rühmadesse:',
'userrights-groupsmember-auto' => 'Kuulub vaikimisi rühmadesse:',
'userrights-groups-help' => 'Sa võid muuta selle kasutaja kuuluvust eri kasutajarühmadesse.
* Märgitud kast tähendab, et kasutaja kuulub sellesse rühma.
* Märkimata kast tähendab, et kasutaja ei kuulu sellesse rühma.
* Aga * kasutajarühma juures tähistab õigust, mida sa peale lisamist enam eemaldada ei saa, või siis ka vastupidi.',
'userrights-reason' => 'Põhjus:',
'userrights-no-interwiki' => 'Sul ei ole luba muuta kasutajaõigusi teistes vikides.',
'userrights-nodatabase' => 'Andmebaasi $1 ei ole olemas või pole see kohalik.',
'userrights-nologin' => 'Kasutaja õiguste muutmiseks, pead sa administraatori õigustega kontoga [[Special:UserLogin|sisse logima]].',
'userrights-notallowed' => 'Sinu kontole pole antud luba lisada või eemaldada kasutajaõigusi.',
'userrights-changeable-col' => 'Rühmad, mida sa saad muuta',
'userrights-unchangeable-col' => 'Rühmad, mida sa ei saa muuta',

# Groups
'group' => 'Rühm:',
'group-user' => 'Kasutajad',
'group-autoconfirmed' => 'Automaatselt kinnitatud kasutajad',
'group-bot' => 'Robotid',
'group-sysop' => 'Administraatorid',
'group-bureaucrat' => 'Bürokraadid',
'group-suppress' => 'Varjajad',
'group-all' => '(kõik)',

'group-user-member' => 'kasutaja',
'group-autoconfirmed-member' => 'automaatselt kinnitatud kasutaja',
'group-bot-member' => 'robot',
'group-sysop-member' => 'administraator',
'group-bureaucrat-member' => 'bürokraat',
'group-suppress-member' => 'varjaja',

'grouppage-user' => '{{ns:project}}:Kasutajad',
'grouppage-autoconfirmed' => '{{ns:project}}:Automaatselt kinnitatud kasutajad',
'grouppage-bot' => '{{ns:project}}:Robotid',
'grouppage-sysop' => '{{ns:project}}:Administraatorid',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokraadid',
'grouppage-suppress' => '{{ns:project}}:Varjaja',

# Rights
'right-read' => 'Lugeda lehekülgi',
'right-edit' => 'Redigeerida lehekülje sisu',
'right-createpage' => 'Luua lehekülgi (mis pole aruteluleheküljed)',
'right-createtalk' => 'Luua arutelulehekülgi',
'right-createaccount' => 'Luua uusi kasutajakontosid',
'right-minoredit' => 'Märkida muudatusi pisimuudatusteks',
'right-move' => 'Teisaldada lehekülgi',
'right-move-subpages' => 'Teisaldada lehekülgi koos nende alamlehtedega',
'right-move-rootuserpages' => 'Teisaldada kasutajalehekülgi',
'right-movefile' => 'Teisaldada faile',
'right-suppressredirect' => 'Teisaldada lehekülgi ümbersuunamist loomata',
'right-upload' => 'Faile üles laadida',
'right-reupload' => 'Kirjutada olemasolevaid faile üle',
'right-reupload-own' => 'Üle kirjutada enda üles laaditud faile',
'right-reupload-shared' => 'Asendada kohalikus vikis jagatud failivaramu faile',
'right-upload_by_url' => 'Faile internetiaadressilt üles laadida',
'right-purge' => 'Tühjendada lehekülje vahemälu kinnituseta',
'right-autoconfirmed' => 'Redigeerida poolkaitstud lehekülgi',
'right-bot' => 'Olla koheldud kui automaadistatud toimimisviis',
'right-nominornewtalk' => 'Teha arutelulehekülgedel pisimuudatusi, ilma et lehekülg märgitaks uuena',
'right-apihighlimits' => 'Kasutada API-päringutes kõrgemaid limiite',
'right-writeapi' => 'Kasutada kirjutamise rakendusliidest',
'right-delete' => 'Lehekülgi kustutada',
'right-bigdelete' => 'Pikkade ajalugudega lehekülgi kustutada',
'right-deletelogentry' => 'Kustutada ja taastada logisissekandeid',
'right-deleterevision' => 'Kustutada ja taastada lehekülgede teatud redaktsioone',
'right-deletedhistory' => 'Vaadata kustutatud ajalookirjeid ilma seotud tekstita',
'right-deletedtext' => 'Vaadata kustutatud teksti ja võrrelda kustutatud redaktsioone',
'right-browsearchive' => 'Otsida kustutatud lehekülgi',
'right-undelete' => 'Taastada lehekülg',
'right-suppressrevision' => 'Üle vaadata ja taastada administraatorite eest peidetud redaktsioone',
'right-suppressionlog' => 'Vaadata eralogisid',
'right-block' => 'Keelata lehekülgede muutmist mõnel kasutajal',
'right-blockemail' => 'Keelata kasutajal e-kirjade saatmine',
'right-hideuser' => 'Blokeerida kasutajanimi, peites selle avalikkuse eest',
'right-ipblock-exempt' => 'Mööduda automaatsetest blokeeringutest ning aadressivahemiku- ja IP-blokeeringutest',
'right-proxyunbannable' => 'Mööduda automaatsetest puhverserveri blokeeringutest',
'right-unblockself' => 'Enda blokeeringut eemaldada',
'right-protect' => 'Muuta kaitsetasemeid ja redigeerida kaitstud lehekülgi',
'right-editprotected' => 'Muuta kaitstud lehekülgi, millel ei ole kaskaadkaitset',
'right-editinterface' => 'Muuta kasutajaliidest',
'right-editusercssjs' => 'Redigeerida teiste kasutajate CSS ja JS faile',
'right-editusercss' => 'Redigeerida teiste kasutajate CSS faile',
'right-edituserjs' => 'Redigeerida teiste kasutajate JS faile',
'right-rollback' => 'Tühistada otsekohe lehekülje viimase redigeerija muudatused',
'right-markbotedits' => 'Märkida muudatuse tühistamine robotimuudatusena',
'right-noratelimit' => 'Mööduda toimingumäära limiitidest',
'right-import' => 'Importida lehekülgi teistest vikidest',
'right-importupload' => 'Importida XML-dokumendi lehekülgi',
'right-patrol' => 'Märkida teiste redigeerimised kontrollituks',
'right-autopatrol' => 'Teha vaikimisi kontrollituks märgitud muudatusi',
'right-patrolmarks' => 'Vaadata viimaste muudatuste kontrollimise märkeid',
'right-unwatchedpages' => 'Vaadata jälgimata lehekülgede nimekirja',
'right-mergehistory' => 'Ühendada lehekülgede ajalood',
'right-userrights' => 'Muuta kõiki kasutajaõigusi',
'right-userrights-interwiki' => 'Muuta teiste vikide kasutajate õigusi',
'right-siteadmin' => 'Panna lukku ja lukust lahti teha andmebaasi',
'right-override-export-depth' => 'Eksportida lehekülgi, kaasates viidatud leheküljed kuni viienda tasemeni',
'right-sendemail' => 'Saata teistele kasutajatele e-kirju',
'right-passwordreset' => 'Vaadata parooli lähtestamise e-kirju',

# User rights log
'rightslog' => 'Kasutaja õiguste logi',
'rightslogtext' => 'See on logi kasutajate õiguste muutuste kohta.',
'rightslogentry' => 'andis kasutajale $1 järgmised õigused: $3; seni oli ta $2',
'rightslogentry-autopromote' => 'sai automaatselt järgmised õigused: $3; seni oli ta $2',
'rightsnone' => '(puudub)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'seda lehekülge lugeda',
'action-edit' => 'seda lehekülge muuta',
'action-createpage' => 'lehekülgi luua',
'action-createtalk' => 'arutelulehekülgi luua',
'action-createaccount' => 'seda kasutajakontot luua',
'action-minoredit' => 'seda muudatust pisimuudatuseks märkida',
'action-move' => 'seda lehekülge teisaldada',
'action-move-subpages' => 'seda lehekülge koos alamlehekülgedega teisaldada',
'action-move-rootuserpages' => 'teisaldada kasutajalehekülgi',
'action-movefile' => 'seda faili teisaldada',
'action-upload' => 'seda faili üles laadida',
'action-reupload' => 'seda olemasolevat faili üle kirjutada',
'action-reupload-shared' => 'seda jagatud varamus asuvat faili üle kirjutada',
'action-upload_by_url' => 'seda faili internetiaadressilt üles laadida',
'action-writeapi' => 'kirjutamise rakendusliidest kasutada',
'action-delete' => 'seda lehekülge kustutada',
'action-deleterevision' => 'seda redaktsiooni kustutada',
'action-deletedhistory' => 'selle lehekülje kustutatud ajalugu vaadata',
'action-browsearchive' => 'kustutatud lehekülgi otsida',
'action-undelete' => 'lehekülgi taastada',
'action-suppressrevision' => 'seda peidetud redaktsiooni vaadata ja taastada',
'action-suppressionlog' => 'seda eralogi vaadata',
'action-block' => 'selle kasutaja redigeerimisõigust blokeerida',
'action-protect' => 'selle lehekülje kaitsetasemeid muuta',
'action-rollback' => 'tühistada otsekohe lehekülge viimati redigeerinud kasutaja muudatusi',
'action-import' => 'seda lehekülge teisest vikist importida',
'action-importupload' => 'seda lehekülge faili üleslaadimise abil importida',
'action-patrol' => 'teiste muudatusi kontrollituks märkida',
'action-autopatrol' => 'oma muudatusi kontrollituks märkida',
'action-unwatchedpages' => 'jälgimata lehekülgede loendit vaadata',
'action-mergehistory' => 'selle lehekülje ajalugu liita',
'action-userrights' => 'kõiki kasutajaõigusi muuta',
'action-userrights-interwiki' => 'teiste vikide kasutajate õigusi muuta',
'action-siteadmin' => 'andmebaasi lukustada või avada',
'action-sendemail' => 'e-kirju saata',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|muudatus|muudatust}}',
'recentchanges' => 'Viimased muudatused',
'recentchanges-legend' => 'Viimaste muudatuste seaded',
'recentchanges-summary' => 'Jälgi sellel leheküljel viimaseid muudatusi.',
'recentchanges-feed-description' => 'Jälgi vikisse tehtud viimaseid muudatusi.',
'recentchanges-label-newpage' => 'See muudatus lõi uue lehekülje',
'recentchanges-label-minor' => 'See on pisiparandus',
'recentchanges-label-bot' => 'Selle muudatuse sooritas robot',
'recentchanges-label-unpatrolled' => 'Seda muudatust ei ole veel kontrollitud',
'rcnote' => "Allpool on esitatud {{PLURAL:$1|'''1''' muudatus|viimased '''$1''' muudatust}} viimase {{PLURAL:$2|päeva|'''$2''' päeva}} jooksul seisuga $4, kell $5.",
'rcnotefrom' => "Allpool on toodud muudatused alates: '''$2''' (näidatakse kuni '''$1''' muudatust)",
'rclistfrom' => 'Näita muudatusi alates: $1',
'rcshowhideminor' => '$1 pisiparandused',
'rcshowhidebots' => '$1 robotid',
'rcshowhideliu' => '$1 sisseloginud kasutajad',
'rcshowhideanons' => '$1 anonüümsed kasutajad',
'rcshowhidepatr' => '$1 kontrollitud muudatused',
'rcshowhidemine' => '$1 minu parandused',
'rclinks' => 'Näita viimast $1 muudatust viimase $2 päeva jooksul<br />$3',
'diff' => 'erin',
'hist' => 'ajal',
'hide' => 'Peida',
'show' => 'Näita',
'minoreditletter' => 'P',
'newpageletter' => 'U',
'boteditletter' => 'R',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|jälgiv kasutaja|jälgivat kasutajat}}]',
'rc_categories' => 'Ainult kategooriatest (eraldajaks "|")',
'rc_categories_any' => 'Mistahes',
'rc-change-size-new' => '$1 {{PLURAL:$1|bait|baiti}} pärast muudatust',
'newsectionsummary' => '/* $1 */ uus alaosa',
'rc-enhanced-expand' => 'Näita üksikasju (nõuab JavaScripti)',
'rc-enhanced-hide' => 'Peida üksikasjad',
'rc-old-title' => 'alustatud pealkirja "$1" all',

# Recent changes linked
'recentchangeslinked' => 'Seotud muudatused',
'recentchangeslinked-feed' => 'Seotud muudatused',
'recentchangeslinked-toolbox' => 'Seotud muudatused',
'recentchangeslinked-title' => 'Leheküljega "$1" seotud muudatused',
'recentchangeslinked-noresult' => 'Antud ajavahemiku jooksul ei ole lingitud lehekülgedel muudatusi tehtud.',
'recentchangeslinked-summary' => "Siin on loetletud määratud leheküljelt viidatud (või määratud kategooria) lehekülgedel tehtud viimased muudatused.
Sinu [[Special:Watchlist|jälgimisloendi]] leheküljed on  '''rasvaselt''' esile toodud.",
'recentchangeslinked-page' => 'Lehekülje nimi:',
'recentchangeslinked-to' => 'Näita hoopis muudatusi lehekülgedel, mis sellele lehele lingivad',

# Upload
'upload' => 'Faili üleslaadimine',
'uploadbtn' => 'Laadi fail üles',
'reuploaddesc' => 'Tagasi üleslaadimise vormi juurde.',
'upload-tryagain' => 'Salvesta muudetud faili kirjeldus',
'uploadnologin' => 'Sisse logimata',
'uploadnologintext' => 'Kui soovid faile üles laadida, pead [[Special:UserLogin|sisse logima]].',
'upload_directory_missing' => 'Üleslaadimiskaust $1 puudub ja veebiserver ei saa seda luua.',
'upload_directory_read_only' => 'Veebiserveril ei õnnestu üleslaadimiste kataloogi ($1) kirjutada.',
'uploaderror' => 'Faili laadimine ebaõnnestus',
'upload-recreate-warning' => "'''Hoiatus: Sellise nimega fail on kustutatud või teisaldatud.'''

Selle lehe kustutamis- ja teisaldamislogi on kuvatud siin:",
'uploadtext' => "Kasuta allpool olevat vormi failide üleslaadimiseks.
Et näha või leida eelnevalt üles laaditud faile vaata [[Special:FileList|failide loendit]].
Üleslaadimiste ajalugu saab uurida [[Special:Log/upload|üleslaadimislogist]], kustutamiste ajalugu [[Special:Log/delete|kustutamislogist]].

Faili lisamiseks artiklile kasuta linki ühel kujul järgnevatest.
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fail.jpg]]</nowiki></code>''', et lisada täissuuruses pilt.
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fail.png|200px|thumb|left|kirjeldus]]</nowiki></code>''', et lisada lehekülje vasakusse serva raamiga 200 piksli laiune pisipilt koos pildiallkirjaga (''kirjeldus'').
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fail.ogg]]</nowiki></code>''', et linkida otse failile ilma seda kuvamata.",
'upload-permitted' => 'Lubatud failitüübid: $1.',
'upload-preferred' => 'Eelistatud failitüübid: $1.',
'upload-prohibited' => 'Keelatud failitüübid: $1.',
'uploadlog' => 'üleslaadimislogi',
'uploadlogpage' => 'Üleslaadimislogi',
'uploadlogpagetext' => 'Allpool on loend viimastest failide üleslaadimistest. Visuaalsema esituse nägemiseks vaata [[Special:NewFiles|uute failide galeriid]].',
'filename' => 'Faili nimi',
'filedesc' => 'Lühikirjeldus',
'fileuploadsummary' => 'Info faili kohta:',
'filereuploadsummary' => 'Faili muudatused:',
'filestatus' => 'Autoriõiguse staatus:',
'filesource' => 'Allikas:',
'uploadedfiles' => 'Üleslaaditud failid',
'ignorewarning' => 'Ignoreeri hoiatust ja salvesta fail hoiatusest hoolimata',
'ignorewarnings' => 'Ignoreeri hoiatusi',
'minlength1' => 'Faili nimes peab olema vähemalt üks kirjamärk.',
'illegalfilename' => 'Faili "$1" nimi sisaldab sümboleid, mis pole pealkirjades lubatud. Palun nimetage fail ümber ja proovige uuesti.',
'filename-toolong' => 'Failinimed ei või olla pikemad kui 240 baiti.',
'badfilename' => 'Pildi nimi on muudetud. Uus nimi on "$1".',
'filetype-mime-mismatch' => 'Faililaiend ".$1" ei vasta faili ($2) MIME tüübile.',
'filetype-badmime' => 'MIME tüübiga "$1" faile ei ole lubatud üles laadida.',
'filetype-bad-ie-mime' => 'Seda faili ei saa üles laadida, sest Internet Explorer avastaks, et selle MIME tüüp on "$1", mis on keelatud või võimalik ohtlik failitüüp.',
'filetype-unwanted-type' => "'''\".\$1\"''' on soovimatu failitüüp.
Eelistatud {{PLURAL:\$3|failitüüp on|failitüübid on}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' pole lubatud failitüü{{PLURAL:$4|p|bid}}.  Lubatud {{PLURAL:$3|failitüüp|failitüübid}} on  $2.',
'filetype-missing' => 'Failil puudub laiend (nagu näiteks ".jpg").',
'empty-file' => 'Saadetud fail oli tühi.',
'file-too-large' => 'Saadetud fail oli liiga suur.',
'filename-tooshort' => 'Failinimi on liiga lühike.',
'filetype-banned' => 'See failitüüp on keelatud.',
'verification-error' => 'See fail ei läbinud failikontrolli.',
'hookaborted' => 'Ühe laiendusmooduli ühendus takistab sinu soovitud muudatuse tegemist.',
'illegal-filename' => 'Keelatud failinimi.',
'overwrite' => 'Olemasoleva faili ülekirjutamine ei ole lubatud.',
'unknown-error' => 'Tundmatu tõrge.',
'tmp-create-error' => 'Ajutise faili loomine ebaõnnestus.',
'tmp-write-error' => 'Viga ajutise faili kirjutamisel.',
'large-file' => 'On soovitatav, et üleslaaditavad failid ei oleks suuremad kui $1. Selle faili suurus on $2.',
'largefileserver' => 'Antud fail on suurem lubatud failisuurusest.',
'emptyfile' => 'Fail, mille üles laadisid, paistab olevat tühi.
See võib olla tingitud vigasest failinimest.
Palun veendu, et soovid tõesti seda faili üles laadida.',
'windows-nonascii-filename' => 'Sellel vikil puudub erimärkidega failinimede tugi.',
'fileexists' => 'Sellise nimega fail on juba olemas. Palun vaata lehekülge <strong>[[:$1]]</strong>, kui sa pole kindel, kas soovid seda muuta.
[[$1|thumb]]',
'filepageexists' => 'Selle faili kirjelduslehekülg <strong>[[:$1]]</strong> on juba loodud, aga selle nimega faili hetkel pole.
Sinu sisestatud kokkuvõtet ei kuvata kirjeldusleheküljel.
Sinu kokkuvõtte kuvamiseks tuleb kirjelduslehekülge eraldi redigeerida.
[[$1|thumb]]',
'fileexists-extension' => 'Sarnase nimega fail on olemas: [[$2|thumb]]
* Üleslaetava faili nimi: <strong>[[:$1]]</strong>
* Olemasoleva faili nimi: <strong>[[:$2]]</strong>
Palun vali teistsugune nimi.',
'fileexists-thumbnail-yes' => "See paistab olevat vähendatud suurusega pilt (''pisipilt''). [[$1|thumb]]
Palun vaata faili <strong>[[:$1]]</strong>.
Kui vaadatud fail on sama pilt algupärases suuruses, pole vaja täiendavat pisipilti üles laadida.",
'file-thumbnail-no' => "Failinimi algab eesliitega <strong>$1</strong>.
See paistab vähendatud suurusega pilt (''pisipilt'') olevat.
Kui sul on ka selle pildi täislahutusega versioon, laadi palun hoopis see üles, vastasel korral muuda palun faili nime.",
'fileexists-forbidden' => 'Sellise nimega fail on juba olemas, seda ei saa üle kirjutada.
Palun pöörduge tagasi ja laadige fail üles mõne teise nime all. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Samanimeline fail on juba olemas jagatud meediavaramus.
Kui soovid siiski oma faili üles laadida, siis palun mine tagasi ja kasuta teist failinime.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'See fail on {{PLURAL:$1|järgneva faili|järgnevate failide}} duplikaat:',
'file-deleted-duplicate' => 'Selle failiga ([[:$1]]) identne fail on hiljuti kustutatud.
Vaata selle faili kustutamise ajalugu enne jätkamist.',
'uploadwarning' => 'Üleslaadimise hoiatus',
'uploadwarning-text' => 'Muuda allpool olevat faili kirjeldust ning proovi uuesti.',
'savefile' => 'Salvesta fail',
'uploadedimage' => 'laadis üles faili "[[$1]]"',
'overwroteimage' => 'laadis üles faili "[[$1]]" uue versiooni',
'uploaddisabled' => 'Üleslaadimine hetkel keelatud',
'copyuploaddisabled' => 'Internetiaadressilt üleslaadimine on keelatud.',
'uploadfromurl-queued' => 'Üleslaadimine on järjekorras.',
'uploaddisabledtext' => 'Faili üleslaadimine on keelatud.',
'php-uploaddisabledtext' => 'Failide üleslaadmine on PHP seadetes keelatud.
Palun vaata <code>file_uploads</code> sätet.',
'uploadscripted' => 'See fail sisaldab HTML- või skriptikoodi, mida veebilehitseja võib valesti kuvada.',
'uploadvirus' => 'Fail sisaldab viirust! Täpsemalt: $1',
'uploadjava' => 'See fail on ZIP-fail, milles on Java .class-fail.
Java failide üleslaadimine on keelatud, kuna nende kaudu võidaks turvapiiranguist mööda minna.',
'upload-source' => 'Lähtefail',
'sourcefilename' => 'Lähtefail:',
'sourceurl' => 'Allika URL:',
'destfilename' => 'Failinimi vikis:',
'upload-maxfilesize' => 'Maksimaalne failisuurus: $1',
'upload-description' => 'Faili kirjeldus',
'upload-options' => 'Üleslaadimise sätted',
'watchthisupload' => 'Jälgi seda lehekülge',
'filewasdeleted' => 'Selle nimega fail on lisatud ja kustutatud hiljuti.
Kontrolli $1 enne jätkamist.',
'filename-bad-prefix' => "Üleslaaditava faili nimi algab eesliitega '''\"\$1\"''', mis on omane digikaamera antud ebamäärastele nimedele.
Palun vali oma failile kirjeldavam nimi.",
'upload-success-subj' => 'Üleslaadimine õnnestus',
'upload-success-msg' => 'Üleslaadimine allikast [$2] läks edukalt. See on leitav siit: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Üleslaadimisprobleem',
'upload-failure-msg' => 'Üleslaadimisel allikast [$2] ilmnes probleem:

$1',
'upload-warning-subj' => 'Üleslaadimishoiatus',
'upload-warning-msg' => 'Üleslaadimisel allikast [$2] tekkis probleem. Probleemi eemaldamiseks võid naasta [[Special:Upload/stash/$1|üleslaadimisvormi]] juurde.',

'upload-proto-error' => 'Vigane protokoll',
'upload-proto-error-text' => 'Teiselt saidilt üleslaadimiseks peab URL algama <code>http://</code> või <code>ftp://</code>.',
'upload-file-error' => 'Sisemine viga',
'upload-file-error-text' => 'Sisemine viga ilmnes, kui üritati luua ajutist faili serveris.
Palun kontakteeru [[Special:ListUsers/sysop|administraatoriga]].',
'upload-misc-error' => 'Tundmatu viga üleslaadimisel',
'upload-misc-error-text' => 'Üleslaadimisel ilmnes tundmatu tõrge.
Palun veendu, et internetiaadress on õige ja ligipääsetav ning proovi uuesti.
Kui probleem ei kao, võta ühendust [[Special:ListUsers/sysop|administraatoriga]].',
'upload-too-many-redirects' => 'URL sisaldas liiga palju ümbersuunamisi',
'upload-unknown-size' => 'Tundmatu suurus',
'upload-http-error' => 'HTTP-viga: $1',
'upload-copy-upload-invalid-domain' => 'Sellest domeenist pole kopeerimise teel üleslaadimine võimalik.',

# File backend
'backend-fail-stream' => 'Faili $1 ei saanud edastada.',
'backend-fail-backup' => 'Faili $1 ei saanud varundada.',
'backend-fail-notexists' => 'Faili $1 pole olemas.',
'backend-fail-hashes' => 'Võrdluseks ei saanud hankida faili räsiväärtusi.',
'backend-fail-notsame' => 'Asukohas $1 on juba olemas mitteidentne fail.',
'backend-fail-invalidpath' => '$1 pole sobiv talletustee.',
'backend-fail-delete' => 'Faili $1 ei saa kustutada.',
'backend-fail-alreadyexists' => 'Fail $1 on juba olemas.',
'backend-fail-store' => 'Faili $1 ei saa talletada asukohas $2.',
'backend-fail-copy' => 'Faili $1 ei saa kopeerida asukohta $2.',
'backend-fail-move' => 'Faili $1 ei saa teisaldada asukohta $2.',
'backend-fail-opentemp' => 'Ajutist faili ei saa avada.',
'backend-fail-writetemp' => 'Ajutist faili ei saa kirjutada.',
'backend-fail-closetemp' => 'Ajutist faili ei saa sulgeda.',
'backend-fail-read' => 'Faili $1 ei saa lugeda.',
'backend-fail-create' => 'Faili $1 ei saa kirjutada.',
'backend-fail-maxsize' => 'Faili $1 ei saa kirjutada, sest see on {{PLURAL:$2|ühest baidist|$2 baidist}} suurem.',
'backend-fail-contenttype' => 'Faili, mida soovitakse talletada asukohas "$1", sisutüüpi saanud kindlaks teha.',
'backend-fail-usable' => 'Faili $1 ei saa ebapiisavate õiguste või puuduvate kataloogide/konteinerite tõttu lugeda ega kirjutada.',

# Lock manager
'lockmanager-notlocked' => 'Rada "$1" ei saa lukust lahti teha, sest see pole lukus.',
'lockmanager-fail-closelock' => 'Raja "$1" lukustusfaili ei saa sulgeda.',
'lockmanager-fail-deletelock' => 'Raja "$1" lukustusfaili ei saa kustutada.',
'lockmanager-fail-openlock' => 'Raja "$1" lukustusfaili ei saa avada.',

# ZipDirectoryReader
'zip-file-open-error' => 'Faili avamisel ZIP-kontrolliks tekkis tõrge.',
'zip-wrong-format' => 'Valitud fail ei ole ZIP-fail.',
'zip-bad' => 'See ZIP-fail on kas rikutud või muul põhjusel loetamatu.
Selle turvalisust ei saa kontrollida.',
'zip-unsupported' => 'See ZIP-fail kasutab ZIP-funktsioone, mida MediaWiki ei toeta.
Selle turvalisust ei saa kontrollida.',

# Special:UploadStash
'uploadstash' => 'Üleslaaditud failide algne hoidla',
'uploadstash-summary' => 'See lehekülg pakub juurdepääsu failidele, mis on üles laaditud (või mida parasjagu üles laaditakse), kuid mis pole veel vikis avaldatud. Need failid on nähtavad üksnes kasutajale, kes need üles laadis.',
'uploadstash-clear' => 'Kustuta failid algsest hoidlast',
'uploadstash-nofiles' => 'Sul pole algses hoidlas faile.',
'uploadstash-badtoken' => 'Toiming ebaõnnestus; võib-olla redigeerimisloa aegumise tõttu. Proovi uuesti.',
'uploadstash-errclear' => 'Failide kustutamine ebaõnnestus.',
'uploadstash-refresh' => 'Värskenda faililoendit',

# img_auth script messages
'img-auth-accessdenied' => 'Juurdepääs keelatud',
'img-auth-nopathinfo' => 'PATH_INFO puudub.
Sinu server pole seadistatud seda teavet edastama.
See võib olla CGI-põhine ja ei toeta img_auth-i.
Vaata lehekülge https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir' => 'Soovitud salvestuskoht pole üleslaadimiskataloogi all.',
'img-auth-badtitle' => 'Väljendist "$1" ei saa sobivat pealkirja moodustada.',
'img-auth-nologinnWL' => 'Sa pole sisselogitud ja "$1" pole valges nimekirjas.',
'img-auth-nofile' => 'Faili "$1" pole.',
'img-auth-isdir' => 'Sa üritad kausta "$1" juurde pääseda.
Lubatud on ainult juurdepääs failidele.',
'img-auth-streaming' => 'Faili "$1" voogedastus.',
'img-auth-public' => 'img_auth.php on ette nähtud failide väljastamiseks privaatses vikis.
See viki on seadistatud kui avalik viki.
Turvakaalutlustel on img_auth.php kasutus keelatud.',
'img-auth-noread' => 'Faili "$1" lugemiseks vajalik juurdepääs puudub.',
'img-auth-bad-query-string' => 'URL-is on vigane päringusõne.',

# HTTP errors
'http-invalid-url' => 'Vigane internetiaadress: $1',
'http-invalid-scheme' => 'Aadressid, mis algavad eesliitega "$1", ei ole toetatud.',
'http-request-error' => 'HTTP-päring nurjus tundmatu tõrke tõttu.',
'http-read-error' => 'HTTP-lugemistõrge.',
'http-timed-out' => 'HTTP-päring aegus.',
'http-curl-error' => 'Tõrge URL-i $1 lugemisel',
'http-host-unreachable' => 'Internetiaadress pole kättesaadav.',
'http-bad-status' => 'HTTP-päringu ajal ilmnes tõrge: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Internetiaadress pole kättesaadav',
'upload-curl-error6-text' => 'Etteantud internetiaadress ei ole kättesaadav.
Palun kontrolli, kas aadress on õige ja kas võrgukoht on üleval.',
'upload-curl-error28' => 'Üleslaadimise ajalimiit',
'upload-curl-error28-text' => 'Võrgukohal läks vastamiseks liiga kaua.
Palun kontrolli kas võrgukoht on ikka üleval, oota natuke ja proovi uuesti.
Samuti võid proovida siis, kui võrgukoht on vähem hõivatud.',

'license' => 'Litsents:',
'license-header' => 'Litsents',
'nolicense' => 'pole valitud',
'license-nopreview' => '(Eelvaade ei ole saadaval)',
'upload_source_url' => '(avalikult ligipääsetav URL)',
'upload_source_file' => '(fail sinu arvutis)',

# Special:ListFiles
'listfiles-summary' => 'See erileht kuvab kõik üleslaaditud failid.
Kui kasutaja järgi filtrida, kuvatakse ainult need failid, mille viimase versiooni antud kasutaja on üles laadinud.',
'listfiles_search_for' => 'Nimeotsing:',
'imgfile' => 'fail',
'listfiles' => 'Piltide loend',
'listfiles_thumb' => 'Pisipilt',
'listfiles_date' => 'Kuupäev',
'listfiles_name' => 'Nimi',
'listfiles_user' => 'Kasutaja',
'listfiles_size' => 'Suurus',
'listfiles_description' => 'Kirjeldus',
'listfiles_count' => 'Versioonid',

# File description page
'file-anchor-link' => 'Pilt',
'filehist' => 'Faili ajalugu',
'filehist-help' => 'Klõpsa kuupäeva ja kellaaega, et näha sel ajahetkel kasutusel olnud failiversiooni.',
'filehist-deleteall' => 'kustuta kõik',
'filehist-deleteone' => 'kustuta see',
'filehist-revert' => 'taasta',
'filehist-current' => 'viimane',
'filehist-datetime' => 'Kuupäev/kellaaeg',
'filehist-thumb' => 'Pisipilt',
'filehist-thumbtext' => 'Pisipilt $1 versioonile',
'filehist-nothumb' => 'Pisipilti ei ole',
'filehist-user' => 'Kasutaja',
'filehist-dimensions' => 'Mõõtmed',
'filehist-filesize' => 'Faili suurus',
'filehist-comment' => 'Kommentaar',
'filehist-missing' => 'Fail puudub',
'imagelinks' => 'Failikasutus',
'linkstoimage' => 'Sellele pildile {{PLURAL:$1|viitab järgmine lehekülg|viitavad järgmised leheküljed}}:',
'linkstoimage-more' => 'Sellele failile viitab enam kui $1 {{PLURAL:$1|lehekülg|lehekülge}}.
Järgnevas loetelus on kuvatud ainult {{PLURAL:$1|esimene viitav lehekülg|esimesed $1 viitavat lehekülge}}.
[[Special:WhatLinksHere/$2|Kogu loetelu]] on saadaval.',
'nolinkstoimage' => 'Sellele pildile ei viita ükski lehekülg.',
'morelinkstoimage' => 'Vaata [[Special:WhatLinksHere/$1|veel linke]], mis sellele failile viitavad.',
'linkstoimage-redirect' => '$1 (failiümbersuunamine) $2',
'duplicatesoffile' => '{{PLURAL:$1|Järgnev fail|Järgnevad $1 faili}} on selle faili {{PLURAL:$1|duplikaat|duplikaadid}} ([[Special:FileDuplicateSearch/$2|üksikasjad]]):',
'sharedupload' => 'See fail pärineb allikast $1 ning võib olla kasutusel ka teistes projektides.',
'sharedupload-desc-there' => 'See fail pärineb kesksest failivaramust $1. Palun vaata [$2 faili kirjelduse lehekülge], et saada rohkem teavet.',
'sharedupload-desc-here' => 'See on jagatud fail allikast $1 ja seda saab kasutada ka teistes projektides. Faili sealne [$2 kirjeldus] on kuvatud allpool.',
'sharedupload-desc-edit' => 'See fail on pärit allikast $1 ja seda saab kasutada teistes projektides.
Võib-olla tahad redigeerida selle faili [$2 sealset kirjelduslehekülge].',
'sharedupload-desc-create' => 'See fail on pärit allikast $1 ja seda saab kasutada teistes projektides.
Võib-olla tahad redigeerida selle faili [$2 sealset kirjelduslehekülge].',
'filepage-nofile' => 'Sellenimelist faili ei ole.',
'filepage-nofile-link' => 'Sellenimelist faili ei ole, kuid sa saad selle [$1 üles laadida].',
'uploadnewversion-linktext' => 'Laadi üles selle faili uus versioon',
'shared-repo-from' => 'varamust $1',
'shared-repo' => 'jagatud varamu',
'upload-disallowed-here' => 'Sa ei saa seda faili üle kirjutada.',

# File reversion
'filerevert' => 'Taasta $1',
'filerevert-legend' => 'Faili taastamine',
'filerevert-intro' => "Sa taastad faili '''[[Media:$1|$1]]''' seisuga [$4 $3, $2] kasutusel olnud versiooni.",
'filerevert-comment' => 'Põhjus:',
'filerevert-defaultcomment' => 'Ennistati versioon seisuga $1, kell $2',
'filerevert-submit' => 'Taasta',
'filerevert-success' => "Faili '''[[Media:$1|$1]]''' seisuga [$4 $3, $2 kasutusel olnud versioon] on taastatud.",
'filerevert-badversion' => 'Ette antud ajatempliga kohalik versioon sellest failist puudub.',

# File deletion
'filedelete' => 'Kustuta $1',
'filedelete-legend' => 'Faili kustutamine',
'filedelete-intro' => "Oled kustutamas faili '''[[Media:$1|$1]]''' ja kogu selle ajalugu.",
'filedelete-intro-old' => "Sa kustutad faili '''[[Media:$1|$1]]''' seisuga [$4 $3, $2] kasutusel olnud versiooni.",
'filedelete-comment' => 'Põhjus:',
'filedelete-submit' => 'Kustuta',
'filedelete-success' => "'''$1''' on kustutatud.",
'filedelete-success-old' => "Faili '''[[Media:$1|$1]]''' seisuga $3, $2 kasutusel olnud versioon on kustutatud.",
'filedelete-nofile' => "Faili '''$1''' ei ole.",
'filedelete-nofile-old' => "Failist '''$1''' ei ole soovitud versiooni.",
'filedelete-otherreason' => 'Muu või täiendav põhjus:',
'filedelete-reason-otherlist' => 'Muu põhjus',
'filedelete-reason-dropdown' => '*Harilikud kustutamise põhjused
** Autoriõiguse rikkumine
** Duplikaat',
'filedelete-edit-reasonlist' => 'Redigeeri kustutamise põhjuseid',
'filedelete-maintenance' => 'Failide kustutamine ja taastamine on hoolduse ajaks keelatud.',
'filedelete-maintenance-title' => 'Faili ei saa kustutada',

# MIME search
'mimesearch' => 'MIME otsing',
'mimesearch-summary' => 'Selle leheküljega saab faile otsida MIME tüübi järgi.
Sisesta kujul tüüp/alamtüüp, näiteks <code>image/jpeg</code>.',
'mimetype' => 'MIME tüüp:',
'download' => 'laadi alla',

# Unwatched pages
'unwatchedpages' => 'Jälgimata lehed',

# List redirects
'listredirects' => 'Ümbersuunamised',

# Unused templates
'unusedtemplates' => 'Kasutamata mallid',
'unusedtemplatestext' => 'See lehekülg loetleb kõik leheküljed nimeruumis {{ns:template}}, mida teistel lehekülgedel ei kasutata. Enne kustutamist palun kontrollige, kas siia pole muid linke.',
'unusedtemplateswlh' => 'teised lingid',

# Random page
'randompage' => 'Juhuslik artikkel',
'randompage-nopages' => '{{PLURAL:$2|Järgmises nimeruumis|Järgmistes nimeruumides}} ei ole ühtegi lehekülge: $1.',

# Random redirect
'randomredirect' => 'Juhuslik ümbersuunamine',
'randomredirect-nopages' => 'Nimeruumis "$1" ei ole ümbersuunamislehekülgi.',

# Statistics
'statistics' => 'Arvandmestik',
'statistics-header-pages' => 'Lehekülgede arvandmed',
'statistics-header-edits' => 'Redigeerimise arvandmed',
'statistics-header-views' => 'Vaatamise statistika',
'statistics-header-users' => 'Kasutajate arvandmed',
'statistics-header-hooks' => 'Muud arvandmed',
'statistics-articles' => 'Sisulehekülgi',
'statistics-pages' => 'Lehekülgi',
'statistics-pages-desc' => 'Kõik lehed vikis, kaasa arvatud arutelulehed, ümbersuunamised jne',
'statistics-files' => 'Üleslaaditud faile',
'statistics-edits' => 'Redigeerimisi alates {{GRAMMAR:genitive|{{SITENAME}}}} loomisest',
'statistics-edits-average' => 'Keskmiselt redigeerimisi lehekülje kohta',
'statistics-views-total' => 'Lehekülje vaatamisi kokku',
'statistics-views-total-desc' => 'Olematute lehekülgede või erilehekülgede vaatamised on välja arvatud',
'statistics-views-peredit' => 'Vaatamisi redaktsiooni kohta',
'statistics-users' => 'Registreeritud [[Special:ListUsers|kasutajaid]]',
'statistics-users-active' => 'Aktiivseid kasutajaid',
'statistics-users-active-desc' => 'Kasutajad, kes on viimase {{PLURAL:$1|päeva|$1 päeva}} jooksul tegutsenud',
'statistics-mostpopular' => 'Enim vaadatud leheküljed',

'disambiguations' => 'Täpsustuslehekülgedele linkivad leheküljed',
'disambiguationspage' => 'Template:Täpsustuslehekülg',
'disambiguations-text' => "Järgmised leheküljed sisaldavad vähemalt üht linki '''täpsustusleheküljele'''.
Võimalik, et sellised lingid peaks viitama sobivamatele lehekülgedele.
Lehekülg loetakse täpsustusleheküljeks, kui see kasutab malli, millele viitab sõnum [[MediaWiki:Disambiguationspage]].",

'doubleredirects' => 'Kahekordsed ümbersuunamised',
'doubleredirectstext' => 'Käesolev leht esitab loendi lehtedest, mis sisaldavad ümbersuunamisi teistele ümbersuunamislehtedele.
Igal real on ära toodud esimene ja teine ümbersuunamisleht ning samuti teise ümbersuunamislehe sihtmärk, mis tavaliselt on esialgse ümbersuunamise tegelik siht, millele see otse osutama peakski.
<del>Läbikriipsutatud</del> kirjed on kohendatud.',
'double-redirect-fixed-move' => '[[$1]] on teisaldatud, see suunab nüüd leheküljele [[$2]].',
'double-redirect-fixed-maintenance' => 'Parandatakse kahekordne suunamine leheküljelt [[$1]] leheküljele [[$2]].',
'double-redirect-fixer' => 'Ümbersuunamiste parandaja',

'brokenredirects' => 'Vigased ümbersuunamised',
'brokenredirectstext' => 'Järgmised leheküljed on ümber suunatud olematutele lehekülgedele:',
'brokenredirects-edit' => 'redigeeri',
'brokenredirects-delete' => 'kustuta',

'withoutinterwiki' => 'Keelelinkideta leheküljed',
'withoutinterwiki-summary' => 'Loetletud leheküljed ei viita erikeelsetele versioonidele.',
'withoutinterwiki-legend' => 'Eesliide',
'withoutinterwiki-submit' => 'Näita',

'fewestrevisions' => 'Vähima redaktsioonide arvuga leheküljed',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bait|baiti}}',
'ncategories' => '$1 {{PLURAL:$1|kategooria|kategooriat}}',
'ninterwikis' => '{{PLURAL:$1|üks intervikilink|$1 intervikilinki}}',
'nlinks' => '$1 {{PLURAL:$1|link|linki}}',
'nmembers' => '$1 {{PLURAL:$1|liige|liiget}}',
'nrevisions' => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'nviews' => '$1 {{PLURAL:$1|külastus|külastust}}',
'nimagelinks' => 'Kasutuses {{PLURAL:$1|ühel leheküljel|$1 leheküljel}}',
'ntransclusions' => 'kasutuses {{PLURAL:$1|ühel leheküljel|$1 leheküljel}}',
'specialpage-empty' => 'Vasteid ei leidu.',
'lonelypages' => 'Viitamata leheküljed',
'lonelypagestext' => 'Järgmistele lehekülgedele ei lingita ühelgi viki leheküljel, samuti ei ole nad kasutusel teiste lehekülgede osana.',
'uncategorizedpages' => 'Kategoriseerimata leheküljed',
'uncategorizedcategories' => 'Kategoriseerimata kategooriad',
'uncategorizedimages' => 'Kategoriseerimata failid',
'uncategorizedtemplates' => 'Kategoriseerimata mallid',
'unusedcategories' => 'Kasutamata kategooriad',
'unusedimages' => 'Kasutamata failid',
'popularpages' => 'Loetumad leheküljed',
'wantedcategories' => 'Kõige oodatumad kategooriad',
'wantedpages' => 'Kõige oodatumad leheküljed',
'wantedpages-badtitle' => 'Tulemuste seas on vigane pealkiri: $1',
'wantedfiles' => 'Kõige oodatumad failid',
'wantedfiletext-cat' => 'Järgmised failid puuduvad, aga on lehekülgedel kasutuses. Siin võivad olla loetletud ka välistes hoidlates asuvad failid, hoolimata sellest, et nad tegelikult olemas on. Loendi sellised valeliikmed on <del>läbi kriipsutatud</del>. Lisaks on puuduvaid faile sisaldavad leheküljed loetletud asukohas [[:$1]].',
'wantedfiletext-nocat' => 'Järgmised failid puuduvad, aga on lehekülgedel kasutuses. Siin võivad olla loetletud ka välistes hoidlates asuvad failid, hoolimata sellest, et nad tegelikult olemas on. Loendi sellised valeliikmed on <del>läbi kriipsutatud</del>.',
'wantedtemplates' => 'Kõige oodatumad mallid',
'mostlinked' => 'Kõige viidatumad leheküljed',
'mostlinkedcategories' => 'Kõige viidatumad kategooriad',
'mostlinkedtemplates' => 'Kõige viidatumad mallid',
'mostcategories' => 'Enim kategoriseeritud leheküljed',
'mostimages' => 'Kõige kasutatumad failid',
'mostinterwikis' => 'Suurima keelelinkide arvuga leheküljed',
'mostrevisions' => 'Kõige pikema redigeerimislooga leheküljed',
'prefixindex' => 'Kõik pealkirjad eesliitega',
'prefixindex-namespace' => 'Kõik pealkirjad eesliitega (nimeruumis $1)',
'shortpages' => 'Lühikesed leheküljed',
'longpages' => 'Pikad leheküljed',
'deadendpages' => 'Edasipääsuta leheküljed',
'deadendpagestext' => 'Järgmised leheküljed ei viita ühelegi teisele viki leheküljele.',
'protectedpages' => 'Kaitstud leheküljed',
'protectedpages-indef' => 'Ainult määramata ajani kaitstud',
'protectedpages-cascade' => 'Ainult kaskaadkaitsega',
'protectedpagestext' => 'Järgnevad leheküljed on teisaldamise või redigeerimise eest kaitstud',
'protectedpagesempty' => 'Selliste parameetritega ei ole praegu ühtegi lehekülge kaitstud.',
'protectedtitles' => 'Kaitstud pealkirjad',
'protectedtitlestext' => 'Järgnevad pealkirjad on lehekülje loomise eest kaitstud',
'protectedtitlesempty' => 'Hetkel pole ükski pealkiri kaitstud.',
'listusers' => 'Kasutajad',
'listusers-editsonly' => 'Näita vaid kasutajaid, kes on teinud muudatusi',
'listusers-creationsort' => 'Järjesta konto loomise aja järgi',
'usereditcount' => '$1 {{PLURAL:$1|redigeerimine|redigeerimist}}',
'usercreated' => 'Konto {{GENDER:$3|loomise}} aeg: $1 kell $2',
'newpages' => 'Uued leheküljed',
'newpages-username' => 'Kasutajanimi:',
'ancientpages' => 'Vanimad leheküljed',
'move' => 'Teisalda',
'movethispage' => 'Muuda pealkirja',
'unusedimagestext' => 'Järgnevad failid on olemas, aga pole ühelegi leheküljele lisatud.
Pane tähele, et teised võrgukohad võivad viidata failile otselingiga ja seega võivad siin toodud failid olla ikkagi aktiivses kasutuses.',
'unusedcategoriestext' => 'Need kategooriad pole ühelgi leheküljel ega teises kategoorias kasutuses.',
'notargettitle' => 'Puudub sihtlehekülg',
'notargettext' => 'Sa pole määranud selle tegevuse sooritamiseks sihtlehekülge ega kasutajat.',
'nopagetitle' => 'Sihtlehekülg puudub',
'nopagetext' => 'Määratud sihtlehekülge pole olemas.',
'pager-newer-n' => '{{PLURAL:$1|uuem 1|uuemad $1}}',
'pager-older-n' => '{{PLURAL:$1|vanem 1|vanemad $1}}',
'suppress' => 'Varjamine',
'querypage-disabled' => 'See erilehekülg on keelatud, et jõudlust hoida.',

# Book sources
'booksources' => 'Raamatuotsimine',
'booksources-search-legend' => 'Raamatuotsimine',
'booksources-go' => 'Mine',
'booksources-text' => 'Allpool on linke teistele lehekülgedele, kus müüakse uusi ja kasutatud raamatuid. Lehekülgedel võib olla ka lisainfot raamatute kohta:',
'booksources-invalid-isbn' => 'Antud ISBN-number ei ole korrektne; kontrolli algallikast kopeerides vigu.',

# Special:Log
'specialloguserlabel' => 'Täitja:',
'speciallogtitlelabel' => 'Objekt (pealkiri või kasutaja):',
'log' => 'Logid',
'all-logs-page' => 'Kõik avalikud logid',
'alllogstext' => 'See on {{GRAMMAR:genitive|{{SITENAME}}}} kõigi olemasolevate logide ühendkuva.
Valiku kitsendamiseks vali logitüüp, sisesta kasutajanimi (tõstutundlik) või huvipakkuva lehekülje pealkiri (samuti tõstutundlik).',
'logempty' => 'Logis puuduvad vastavad kirjed.',
'log-title-wildcard' => 'Selle tekstiga algavad pealkirjad',
'showhideselectedlogentries' => 'Näita valitud logisissekandeid või peida need',

# Special:AllPages
'allpages' => 'Kõik leheküljed',
'alphaindexline' => '$1 kuni $2',
'nextpage' => 'Järgmine lehekülg ($1)',
'prevpage' => 'Eelmine lehekülg ($1)',
'allpagesfrom' => 'Näita lehti alates pealkirjast:',
'allpagesto' => 'Näita lehti kuni pealkirjani:',
'allarticles' => 'Kõik leheküljed',
'allinnamespace' => 'Kõik leheküljed nimeruumis $1',
'allnotinnamespace' => 'Kõik leheküljed, mis ei kuulu nimeruumi $1',
'allpagesprev' => 'Eelmised',
'allpagesnext' => 'Järgmised',
'allpagessubmit' => 'Näita',
'allpagesprefix' => 'Kuva leheküljed eesliitega:',
'allpagesbadtitle' => 'Lehekülje pealkiri oli vigane või sisaldas teise viki või keele eesliidet.
See võib sisaldada üht või enamat märki, mida ei saa pealkirjades kasutada.',
'allpages-bad-ns' => '{{GRAMMAR:inessive|{{SITENAME}}}} ei ole nimeruumi "$1".',
'allpages-hide-redirects' => 'Peida ümbersuunamised',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Vaata vahemälus olevat lehekülje versiooni, mis võib olla kuni $1 vanune.',
'cachedspecial-viewing-cached-ts' => 'Vaatad vahemälus olevat lehekülje versiooni, mis ei pruugi olla täiesti ajakohane.',
'cachedspecial-refresh-now' => 'Vaata uusimat versiooni.',

# Special:Categories
'categories' => 'Kategooriad',
'categoriespagetext' => 'Vikis on {{PLURAL:$1|järgmine kategooria|järgmised kategooriad}}.
Siin ei näidata [[Special:UnusedCategories|kasutamata kategooriaid]].
Vaata ka [[Special:WantedCategories|puuduvaid kategooriaid]].',
'categoriesfrom' => 'Näita kategooriaid alates:',
'special-categories-sort-count' => 'järjesta hulga järgi',
'special-categories-sort-abc' => 'järjesta tähestikuliselt',

# Special:DeletedContributions
'deletedcontributions' => 'Kustutatud kaastöö',
'deletedcontributions-title' => 'Kasutaja kustutatud kaastöö',
'sp-deletedcontributions-contribs' => 'kaastöö',

# Special:LinkSearch
'linksearch' => 'Välislinkide otsimine',
'linksearch-pat' => 'Otsimisvorm:',
'linksearch-ns' => 'Nimeruum:',
'linksearch-ok' => 'Otsi',
'linksearch-text' => 'Metamärgina võib kasutada tärni, näiteks "*.wikipedia.org".
Otsingus peab olema vähemalt tipptaseme domeen, näiteks "*.org".<br />
Toetatud protokollid: <code>$1</code> (otsingus ära neid kasuta).',
'linksearch-line' => '$1 on lingitud leheküljelt $2',
'linksearch-error' => 'Metamärk võib olla ainult internetiaadressi alguses.',

# Special:ListUsers
'listusersfrom' => 'Näita kasutajaid alates:',
'listusers-submit' => 'Näita',
'listusers-noresult' => 'Kasutajat ei leitud.',
'listusers-blocked' => '(blokeeritud)',

# Special:ActiveUsers
'activeusers' => 'Aktiivsete kasutajate nimekiri',
'activeusers-intro' => 'See on loetelu kasutajatest, kes on viimase $1 {{PLURAL:$1|päev|päeva}} jooksul midagi teinud.',
'activeusers-count' => '$1 {{PLURAL:$1|muudatus|muudatust}} viimase {{PLURAL:$3|päeva|$3 päeva}} jooksul',
'activeusers-from' => 'Näita kasutajaid alates:',
'activeusers-hidebots' => 'Peida robotid',
'activeusers-hidesysops' => 'Peida administraatorid',
'activeusers-noresult' => 'Kasutajaid ei leidunud.',

# Special:Log/newusers
'newuserlogpage' => 'Kasutaja loomise logi',
'newuserlogpagetext' => 'See logi sisaldab infot äsja loodud uute kasutajate kohta.',

# Special:ListGroupRights
'listgrouprights' => 'Kasutajarühma õigused',
'listgrouprights-summary' => 'Siin on loetletud selle viki kasutajarühmad ja rühmaga seotud õigused.
Üksikute õiguste kohta võib olla [[{{MediaWiki:Listgrouprights-helppage}}|täiendavat teavet]].',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Väljaantud õigus</span>
* <span class="listgrouprights-revoked">Äravõetud õigus</span>',
'listgrouprights-group' => 'Rühm',
'listgrouprights-rights' => 'Õigused',
'listgrouprights-helppage' => 'Help:Rühma õigused',
'listgrouprights-members' => '(liikmete loend)',
'listgrouprights-addgroup' => 'Lisada liikmeid {{PLURAL:$2|rühma|rühmadesse}}: $1',
'listgrouprights-removegroup' => 'Eemaldada liikmeid {{PLURAL:$2|rühmast|rühmadest}}: $1',
'listgrouprights-addgroup-all' => 'Kõigisse rühmadesse liikmeid lisada',
'listgrouprights-removegroup-all' => 'Kõigist rühmadest liikmeid eemaldada',
'listgrouprights-addgroup-self' => 'Lisada enda konto {{PLURAL:$2|rühma|rühmadesse}}: $1',
'listgrouprights-removegroup-self' => 'Eemaldada enda konto {{PLURAL:$2|rühmast|rühmadest}}: $1',
'listgrouprights-addgroup-self-all' => 'Oma konto kõigisse rühmadesse lisada',
'listgrouprights-removegroup-self-all' => 'Eemaldada ennast kõigist rühmadest',

# E-mail user
'mailnologin' => 'Saatja aadress puudub',
'mailnologintext' => 'Pead olema [[Special:UserLogin|sisse logitud]] ja sul peab [[Special:Preferences|eelistustes]] olema kehtiv e-posti aadress, et saata teistele kasutajatele e-kirju.',
'emailuser' => 'Saada sellele kasutajale e-kiri',
'emailuser-title-target' => 'Sellele {{GENDER:$1|kasutajale}} e-kirja saatmine',
'emailuser-title-notarget' => 'Kasutajale e-kirja saatmine',
'emailpage' => 'Saada kasutajale e-kiri',
'emailpagetext' => 'Alloleva vormi kaudu saad sellele {{GENDER:$1|kasutajale}} e-kirja saata. Et kasutaja saaks vastata, täidetakse kirja saatja väli e-posti aadressiga, mille oled sisestanud [[Special:Preferences|oma eelistuste leheküljel]].',
'usermailererror' => 'Saatmise viga:',
'defemailsubject' => 'E-kiri {{GRAMMAR:genitive|{{SITENAME}}}} kasutajalt $1',
'usermaildisabled' => 'Kasutajatele e-kirjade saatmine keelatud',
'usermaildisabledtext' => 'Selles vikis ei saa teistele kasutajatele e-kirju saata.',
'noemailtitle' => 'E-posti aadressi pole',
'noemailtext' => 'See kasutaja pole määranud kehtivat e-posti aadressi.',
'nowikiemailtitle' => 'E-kirja saatmine ei ole lubatud',
'nowikiemailtext' => 'See kasutaja ei soovi e-posti teistelt kasutajatelt.',
'emailnotarget' => 'Saaja kasutajanimi on olematu või vigane.',
'emailtarget' => 'Saaja kasutajanime sisestamine',
'emailusername' => 'Kasutajanimi:',
'emailusernamesubmit' => 'Sobib',
'email-legend' => 'Saada e-kiri {{GRAMMAR:genitive|{{SITENAME}}}} kasutajale',
'emailfrom' => 'Kellelt:',
'emailto' => 'Kellele:',
'emailsubject' => 'Pealkiri:',
'emailmessage' => 'Sõnum:',
'emailsend' => 'Saada',
'emailccme' => 'Saada mulle koopia.',
'emailccsubject' => 'Koopia sinu sõnumist kasutajale $1: $2',
'emailsent' => 'E-post saadetud',
'emailsenttext' => 'Sinu teade on e-kirjaga saadetud.',
'emailuserfooter' => 'Selle e-kirja saatis $1 {{GRAMMAR:elative|{{SITENAME}}}} kasutajale $2 toimingu "Saada sellele kasutajale e-kiri" abil.',

# User Messenger
'usermessage-summary' => 'Jätan süsteemiteate.',
'usermessage-editor' => 'Süsteemiteadete edastaja',

# Watchlist
'watchlist' => 'Jälgimisloend',
'mywatchlist' => 'Jälgimisloend',
'watchlistfor2' => 'Kasutaja $1 $2 jaoks',
'nowatchlist' => 'Sinu jälgimisloend on tühi.',
'watchlistanontext' => 'Oma jälgimisloendi nägemiseks ja muutmiseks pead $1.',
'watchnologin' => 'Ei ole sisse logitud',
'watchnologintext' => 'Jälgimisloendi muutmiseks pead [[Special:UserLogin|sisse logima]].',
'addwatch' => 'Lisa jälgimisloendisse',
'addedwatchtext' => 'Lehekülg "[[:$1]]" on lisatud sinu [[Special:Watchlist|jälgimisloendisse]].
Edasised muudatused sellel leheküljel ja seotud aruteluleheküljel tuuakse ära jälgimisloendis.',
'removewatch' => 'Eemalda jälgimisloendist',
'removedwatchtext' => 'Lehekülg "[[:$1]]" on [[Special:Watchlist|jälgimisloendist]] eemaldatud.',
'watch' => 'Jälgi',
'watchthispage' => 'Jälgi seda lehekülge',
'unwatch' => 'Lõpeta jälgimine',
'unwatchthispage' => 'Ära jälgi',
'notanarticle' => 'Pole artikkel',
'notvisiblerev' => 'Redaktsioon on kustutatud',
'watchnochange' => 'Valitud ajavahemiku jooksul pole ühelgi jälgitaval leheküljel muudatusi tehtud.',
'watchlist-details' => 'Jälgimisloendis on {{PLURAL:$1|$1 lehekülg|$1 lehekülge}} (ei arvestata arutelulehekülgi).',
'wlheader-enotif' => '* E-posti teel teavitamine on aktiveeritud.',
'wlheader-showupdated' => "* Leheküljed, mida on muudetud peale sinu viimast külastust, on '''rasvases kirjas'''.",
'watchmethod-recent' => 'jälgitud lehekülgedel tehtud viimaste muudatuste läbivaatamine',
'watchmethod-list' => 'jälgitavate lehekülgede viimased muudatused',
'watchlistcontains' => 'Sinu jälgimisloendis on $1 {{PLURAL:$1|lehekülg|lehekülge}}.',
'iteminvalidname' => "Probleem üksusega '$1'. Selle nimes on viga.",
'wlnote' => "Allpool on {{PLURAL:$1|viimane muudatus|viimased '''$1''' muudatust}} viimase {{PLURAL:$2|tunni|'''$2''' tunni}} jooksul seisuga $3, $4.",
'wlshowlast' => 'Näita viimast $1 tundi $2 päeva. $3',
'watchlist-options' => 'Jälgimisloendi võimalused',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Jälgimine...',
'unwatching' => 'Jälgimise lõpetamine...',
'watcherrortext' => 'Lehekülje "$1" jaoks jälgimisloendi sätete muutmisel ilmnes tõrge.',

'enotif_mailer' => '{{GRAMMAR:genitive|{{SITENAME}}}} lehekülje muutmise teavitaja',
'enotif_reset' => 'Märgi kõik lehed loetuks',
'enotif_newpagetext' => 'See on uus lehekülg.',
'enotif_impersonal_salutation' => '{{GRAMMAR:genitive|{{SITENAME}}}} kasutaja',
'changed' => 'muutnud lehekülge',
'created' => 'loonud lehekülje',
'enotif_subject' => '$PAGEEDITOR on {{GRAMMAR:inessive|{{SITENAME}}}} $CHANGEDORCREATED $PAGETITLE',
'enotif_lastvisited' => 'Kõigi sinu viimase külastuse järel tehtud muudatuste nägemiseks vaata: $1.',
'enotif_lastdiff' => 'Muudatus on leheküljel $1.',
'enotif_anon_editor' => 'anonüümne kasutaja $1',
'enotif_body' => 'Lugupeetud $WATCHINGUSERNAME

{{GRAMMAR:genitive|{{SITENAME}}}} kasutaja $PAGEEDITOR on kuupäeval $PAGEEDITDATE $CHANGEDORCREATED $PAGETITLE. Lehe praegune redaktsioon on asukohas $PAGETITLE_URL.

$NEWPAGE

Redigeerija resümee: $PAGESUMMARY $PAGEMINOREDIT

Redigeerijaga ühenduse võtmine:
e-post: $PAGEEDITOR_EMAIL
viki: $PAGEEDITOR_WIKI

Seni kuni sa seda lehte ei külasta, selle lehe uute muudatuste kohta sulle uusi teavitus-e-kirju ei saadeta.

Abivalmilt
{{GRAMMAR:genitive|{{SITENAME}}}} teavitussüsteem

--
E-kirjatsi saabuvate teavituste sätteid saad muuta leheküljel
{{canonicalurl:{{#special:Preferences}}}}.

Oma jälgimisloendi sätete muutmiseks mine leheküljele
{{canonicalurl:Special:Watchlist/edit}}.

Lehekülje kustutamiseks jälgimisloendist mine leheküljele $UNWATCHURL.

Tagasiside ja abi:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Kustuta lehekülg',
'confirm' => 'Kinnita',
'excontent' => "sisu oli: '$1'",
'excontentauthor' => "sisu oli: '$1' (ja ainuke kirjutaja oli '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "sisu enne lehekülje tühjendamist: '$1'",
'exblank' => 'lehekülg oli tühi',
'delete-confirm' => 'Lehekülje "$1" kustutamine',
'delete-legend' => 'Kustutamine',
'historywarning' => "'''Hoiatus:''' Kustutataval leheküljel on ligikaudu {{PLURAL:$1|ühe redaktsiooniga|$1 redaktsiooniga}} ajalugu:",
'confirmdeletetext' => 'Sa oled andmebaasist kustutamas lehekülge koos kogu tema ajalooga.
Palun kinnita, et tahad seda tõepoolest teha, et sa mõistad tagajärgi ja et sinu tegevus on kooskõlas siinse [[{{MediaWiki:Policy-url}}|sisekorraga]].',
'actioncomplete' => 'Toiming sooritatud',
'actionfailed' => 'Tegevus ebaõnnestus',
'deletedtext' => '"$1" on kustutatud. Kustutatud leheküljed on ära toodud eraldi loendis ($2).',
'dellogpage' => 'Kustutamislogi',
'dellogpagetext' => 'Allpool on esitatud nimekiri viimastest kustutamistest.
Kõik toodud kellaajad järgivad serveriaega.',
'deletionlog' => 'kustutamislogi',
'reverted' => 'Pöörduti tagasi varasemale versioonile',
'deletecomment' => 'Põhjus:',
'deleteotherreason' => 'Muu või täiendav põhjus:',
'deletereasonotherlist' => 'Muu põhjus',
'deletereason-dropdown' => '*Harilikud kustutamise põhjused
** Autori palve
** Autoriõiguste rikkumine
** Vandalism',
'delete-edit-reasonlist' => 'Redigeeri kustutamise põhjuseid',
'delete-toobig' => 'See lehekülg on pika redigeerimisajalooga – üle {{PLURAL:$1|ühe muudatuse|$1 muudatuse}}.
Selle kustutamine on keelatud, et ära hoida ekslikku {{GRAMMAR:genitive|{{SITENAME}}}} töö häirimist.',
'delete-warning-toobig' => 'See lehekülg on pika redigeerimislooga – üle {{PLURAL:$1|ühe muudatuse|$1 muudatuse}}.
Ettevaatust, selle kustutamine võib esile kutsuda häireid {{GRAMMAR:genitive|{{SITENAME}}}} andmebaasi töös.',

# Rollback
'rollback' => 'Tühista muudatused',
'rollback_short' => 'Tühista',
'rollbacklink' => 'tühista',
'rollbacklinkcount' => 'tühista {{PLURAL:$1|üks muudatus|$1 muudatust}}',
'rollbacklinkcount-morethan' => 'tühista üle {{PLURAL:$1|ühe muudatuse|10 muudatuse}}',
'rollbackfailed' => 'Muudatuste tühistamine ebaõnnestus',
'cantrollback' => 'Ei saa muudatusi eemaldada, sest viimane kaastööline on artikli ainus autor.',
'alreadyrolled' => 'Muudatust, mille tegi lehele [[:$1]] kasutaja [[User:$2|$2]] ([[User talk:$2|arutelu]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]), ei saa tühistada, sest keegi teine on seda lehte vahepeal muutnud.

Lehte muutis viimasena [[User:$3|$3]] ([[User talk:$3|arutelu]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Redaktsiooni resümee oli: \"''\$1''\".",
'revertpage' => 'Tühistati kasutaja [[Special:Contributions/$2|$2]] ([[User talk:$2|arutelu]]) tehtud muudatused ja pöörduti tagasi viimasele muudatusele, mille tegi [[User:$1|$1]].',
'revertpage-nouser' => 'Tühistati eemaldatud nimega kasutaja tehtud muudatused ja pöörduti tagasi viimasele muudatusele, mille tegi [[User:$1|$1]].',
'rollback-success' => 'Tühistati $1 muudatus;
pöörduti tagasi viimasele muudatusele, mille tegi $2.',

# Edit tokens
'sessionfailure-title' => 'Seansiviga',
'sessionfailure' => 'Sinu sisselogimisseansiga näib probleem olevat.
See toiming on seansiärandamise vastase ettevaatusabinõuna tühistatud.
Mine tagasi eelmisele leheküljele ja taaslaadi see, seejärel proovi uuesti.',

# Protect
'protectlogpage' => 'Kaitsmislogi',
'protectlogtext' => 'Allpool on loetletud lehekülgede kaitsetasemete muutmised. Praegu kaitstud lehekülgi vaata [[Special:ProtectedPages|kaitstud lehtede loetelust]].',
'protectedarticle' => 'kaitses lehekülje "[[$1]]"',
'modifiedarticleprotection' => 'muutis lehekülje "[[$1]]" kaitsemäära',
'unprotectedarticle' => 'eemaldas lehekülje "[[$1]]" kaitse',
'movedarticleprotection' => 'teisaldas kaitsesätted läheküljelt "[[$2]]" leheküljele "[[$1]]"',
'protect-title' => 'Lehekülje "$1" kaitsemäära muutmine',
'protect-title-notallowed' => 'Lehekülje "$1" kaitsemäära vaatamine',
'prot_1movedto2' => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla',
'protect-badnamespace-title' => 'Kaitstamatu nimeruum',
'protect-badnamespace-text' => 'Selles nimeruumis olevaid lehekülgi ei saa kaitsta.',
'protect-legend' => 'Kaitse kinnitamine',
'protectcomment' => 'Põhjus:',
'protectexpiry' => 'Aegub:',
'protect_expiry_invalid' => 'Sobimatu aegumise tähtaeg.',
'protect_expiry_old' => 'Aegumise tähtaeg on minevikus.',
'protect-unchain-permissions' => 'Ava edasised kaitsmissuvandid',
'protect-text' => "Siin võid vaadata ja muuta lehekülje '''$1''' kaitsetaset.",
'protect-locked-blocked' => "Blokeerituna ei saa muuta kaitstuse taset.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-locked-dblock' => "Kaitstuse taset ei saa muuta, sest andmebaas on lukustatud.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-locked-access' => "Sinu kontol pole õigust muuta lehekülje kaitsetaset.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-cascadeon' => 'See lehekülg on kaitstud, kuna ta on kasutusel {{PLURAL:$1|järgmisel leheküljel|järgmistel lehekülgedel}}, mis on omakorda kaskaadkaitse all.
Sa saad muuta selle lehekülje kaitse staatust, kuid see ei mõjuta kaskaadkaitset.',
'protect-default' => 'Luba kõigile kasutajatele',
'protect-fallback' => 'Nõuab "$1" õiguseid',
'protect-level-autoconfirmed' => 'Blokeeri uued ja registreerimata kasutajad',
'protect-level-sysop' => 'Ainult administraatorid',
'protect-summary-cascade' => 'kaskaad',
'protect-expiring' => 'aegub $1 (UTC)',
'protect-expiring-local' => 'aegub $1',
'protect-expiry-indefinite' => 'määramatu',
'protect-cascade' => 'Kaitse lehekülgi, mis on lülitatud käesoleva lehekülje koosseisu (kaskaadkaitse)',
'protect-cantedit' => 'Sa ei saa lehekülje kaitsetaset muuta, sest sul puudub lehekülje redigeerimise õigus.',
'protect-othertime' => 'Muu aeg:',
'protect-othertime-op' => 'muu aeg',
'protect-existing-expiry' => 'Kehtiv aegumisaeg: $2 kell $3',
'protect-otherreason' => 'Muu või täiendav põhjus:',
'protect-otherreason-op' => 'Muu põhjus',
'protect-dropdown' => '*Tavalised kaitsmise põhjused
** Liigne vandalism
** Liigne rämpspostitamine
** Redigeerimissõja pidamine
** Suure liiklusega lehekülg',
'protect-edit-reasonlist' => 'Muudatuste eest kaitsmise põhjused',
'protect-expiry-options' => '1 tund:1 hour,1 päev:1 day,1 nädal:1 week,2 nädalat: 2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite',
'restriction-type' => 'Lubatud:',
'restriction-level' => 'Kaitsmise tase:',
'minimum-size' => 'Min suurus',
'maximum-size' => 'Max suurus:',
'pagesize' => '(baiti)',

# Restrictions (nouns)
'restriction-edit' => 'Redigeerimine',
'restriction-move' => 'Teisaldamine',
'restriction-create' => 'Loomine',
'restriction-upload' => 'Laadi üles',

# Restriction levels
'restriction-level-sysop' => 'täielikult kaitstud',
'restriction-level-autoconfirmed' => 'poolkaitstud',
'restriction-level-all' => 'kõik tasemed',

# Undelete
'undelete' => 'Kustutatud lehekülgede vaatamine',
'undeletepage' => 'Kustutatud lehekülgede vaatamine ja taastamine',
'undeletepagetitle' => "'''Kustutatud redaktsioonid leheküljest [[:$1|$1]]'''.",
'viewdeletedpage' => 'Kustutatud lehekülgede vaatamine',
'undeletepagetext' => '{{PLURAL:$1|Järgnev lehekülg on kustutatud|Järgnevad leheküljed on kustutatud}}, kuid arhiivis veel olemas ja taastatavad. Arhiivi sisu kustutatakse perioodiliselt.',
'undelete-fieldset-title' => 'Redaktsioonide taastamine',
'undeleteextrahelp' => "Kogu lehe ja selle ajaloo taastamiseks jäta kõik linnukesed tühjaks ja vajuta '''''{{int:undeletebtn}}'''''.
Et taastada valikuliselt, tee linnukesed kastidesse, mida soovid taastada ja vajuta '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 arhiivitud {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'undeletehistory' => 'Kui taastate lehekülje, taastuvad kõik versioonid artikli ajaloona.
Kui vahepeal on loodud uus samanimeline lehekülg, ilmuvad taastatud versioonid varasema ajaloona.',
'undeleterevdel' => 'Lehekülge ei taastata, kui viimane redaktsioon või failiversioon kustub seeläbi osaliselt.
Sellisel juhul tuleb uusima kustutatud redaktsiooni juurest linnuke eemaldada või see peitmata jätta.',
'undeletehistorynoadmin' => 'See lehekülg on kustutatud.
Kustutamise põhjus ning selle lehekülje kustutamiseelne redigeerimislugu on näha allolevas kokkuvõttes.
Lehekülje kustutamiseelsed redaktsioonid on kättesaadavad ainult administraatoritele.',
'undelete-revision' => 'Lehekülje $1 kustutatud redaktsioon, mille autor on $3, seisuga $4, kell $5.',
'undeleterevision-missing' => 'Vigane või puuduv redaktsioon.
Link võib olla kõlbmatu või redaktsioon võib olla taastatud või arhiivist eemaldatud.',
'undelete-nodiff' => 'Varasemat redaktsiooni ei leidunud.',
'undeletebtn' => 'Taasta',
'undeletelink' => 'vaata/taasta',
'undeleteviewlink' => 'vaata',
'undeletereset' => 'Tühjenda',
'undeleteinvert' => 'Pööra valik teistpidi',
'undeletecomment' => 'Põhjus:',
'undeletedrevisions' => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}} taastatud',
'undeletedrevisions-files' => '{{PLURAL:$1|1 redaktsioon|$1 redaktsiooni}} ja {{PLURAL:$2|1 fail|$2 faili}} taastatud',
'undeletedfiles' => '{{PLURAL:$1|1 fail|$1 faili}} taastatud',
'cannotundelete' => 'Taastamine ebaõnnestus; keegi teine võis lehe juba taastada.',
'undeletedpage' => "'''$1 on taastatud'''

[[Special:Log/delete|Kustutamise logist]] võib leida loendi viimastest kustutamistest ja taastamistest.",
'undelete-header' => 'Hiljuti kustutatud leheküljed leiad [[Special:Log/delete|kustutamislogist]].',
'undelete-search-title' => 'Kustutatud lehekülgede otsimine',
'undelete-search-box' => 'Kustutatud lehekülgede otsimine',
'undelete-search-prefix' => 'Näita lehekülgi, mille pealkiri algab nii:',
'undelete-search-submit' => 'Otsi',
'undelete-no-results' => 'Kustutatud lehekülgede arhiivist sellist lehekülge ei leidunud.',
'undelete-filename-mismatch' => 'Failiversiooni ajatempliga $1 ei saa taastada, sest failinimed ei klapi.',
'undelete-bad-store-key' => 'Failiversiooni ajatempliga $1 ei saa taastada, sest faili ei olnud enne kustutamist.',
'undelete-cleanup-error' => 'Kasutamata arhiivifaili "$1" kustutamine ebaõnnestus.',
'undelete-missing-filearchive' => 'Failiarhiivi tunnusega $1 ei saa taastada, sest seda pole andmebaasis.
Võimalik, et see on juba taastatud.',
'undelete-error' => 'Tõrge lehekülje taastamisel',
'undelete-error-short' => 'Faili $1 taastamine ebaõnnestus',
'undelete-error-long' => 'Faili taastamine ebaõnnestus:

$1',
'undelete-show-file-confirm' => 'Kas oled kindel, et soovid näha kustutatud versiooni failist <nowiki>$1</nowiki> seisuga $2, kell $3?',
'undelete-show-file-submit' => 'Jah',

# Namespace form on various pages
'namespace' => 'Nimeruum:',
'invert' => 'Näita kõiki peale valitud nimeruumi',
'tooltip-invert' => 'Tee kasti linnuke, kui soovid peita muudatused valitud nimeruumi lehekülgedel (ja valiku korral ka sellega seotud nimeruumis)',
'namespace_association' => 'Seonduv nimeruum',
'tooltip-namespace_association' => 'Tee kasti linnuke, kui soovid valida ka valitud nimeruumiga seotud arutelu- või sisulehekülgede nimeruumi',
'blanknamespace' => '(Artiklid)',

# Contributions
'contributions' => 'Kasutaja kaastöö',
'contributions-title' => 'Kasutaja $1 kaastöö',
'mycontris' => 'Kaastöö',
'contribsub2' => 'Kasutaja $1 ($2) jaoks',
'nocontribs' => 'Antud kriteeriumitele vastavaid muudatusi ei leitud.',
'uctop' => ' (uusim)',
'month' => 'Alates kuust (ja varasemad):',
'year' => 'Alates aastast (ja varasemad):',

'sp-contributions-newbies' => 'Näita ainult uute kasutajate kaastööd',
'sp-contributions-newbies-sub' => 'Uute kontode kaastöö',
'sp-contributions-newbies-title' => 'Uute kasutajate kaastöö',
'sp-contributions-blocklog' => 'blokeerimised',
'sp-contributions-deleted' => 'kustutatud kaastöö',
'sp-contributions-uploads' => 'üleslaadimised',
'sp-contributions-logs' => 'logid',
'sp-contributions-talk' => 'arutelu',
'sp-contributions-userrights' => 'kasutaja õiguste muutmine',
'sp-contributions-blocked-notice' => 'See kasutaja on parajasti blokeeritud. Allpool on toodud kõige hilisem blokeerimislogi sissekanne:',
'sp-contributions-blocked-notice-anon' => 'See IP-aadress on parajasti blokeeritud.
Allpool on toodud viimane blokeerimislogi sissekanne:',
'sp-contributions-search' => 'Kaastöö otsimine',
'sp-contributions-username' => 'IP-aadress või kasutajanimi:',
'sp-contributions-toponly' => 'Ainult uusimad redaktsioonid',
'sp-contributions-submit' => 'Otsi',

# What links here
'whatlinkshere' => 'Lingid siia',
'whatlinkshere-title' => 'Leheküljed, mis viitavad lehele "$1"',
'whatlinkshere-page' => 'Lehekülg:',
'linkshere' => "Lehele '''[[:$1]]''' viitavad järgmised leheküljed:",
'nolinkshere' => "Lehele '''[[:$1]]''' ei viita ükski lehekülg.",
'nolinkshere-ns' => 'Leheküljele <strong>[[:$1]]</strong> ei ole valitud nimeruumis linke.',
'isredirect' => 'ümbersuunamislehekülg',
'istemplate' => 'kasutamine mallina',
'isimage' => 'faililink',
'whatlinkshere-prev' => '{{PLURAL:$1|eelmine|eelmised $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|järgmine|järgmised $1}}',
'whatlinkshere-links' => '← lingid',
'whatlinkshere-hideredirs' => '$1 ümbersuunamised',
'whatlinkshere-hidetrans' => '$1 mallina kasutamised',
'whatlinkshere-hidelinks' => '$1 lingid',
'whatlinkshere-hideimages' => '$1 faililingid',
'whatlinkshere-filters' => 'Filtrid',

# Block/unblock
'autoblockid' => 'Automaatblokeering #$1',
'block' => 'Kasutaja blokeerimine',
'unblock' => 'Kasutaja blokeeringu eemaldamine',
'blockip' => 'Kasutaja blokeerimine',
'blockip-title' => 'Kasutaja blokeerimine',
'blockip-legend' => 'Kasutaja blokeerimine',
'blockiptext' => 'See vorm on kindla IP-aadressi või kasutajanime kirjutamisõiguste blokeerimiseks.
Seda tohib teha ainult vandalismi vältimiseks ja kooskõlas [[{{MediaWiki:Policy-url}}|{{GRAMMAR:genitive|{{SITENAME}}}} sisekorraga]].
Täida ka põhjuse väli, näiteks viidates lehekülgedele, mis rikuti.',
'ipadressorusername' => 'IP-aadress või kasutajanimi:',
'ipbexpiry' => 'Kehtivus:',
'ipbreason' => 'Põhjus:',
'ipbreasonotherlist' => 'Muul põhjusel',
'ipbreason-dropdown' => '*Tavalised blokeerimise põhjused
** Valeandmete lisamine
** Lehekülgedelt sisu kustutamine
** Välislinkide rämpspostitus
** Sodimine
** Hirmutav käitumine/ahistamine
** Mitme konto väärkasutus
** Lubamatu kasutajanimi',
'ipb-hardblock' => 'Keela sellelt IP-aadressilt sisseloginud kasutajatel redigeerida',
'ipbcreateaccount' => 'Takista konto loomist',
'ipbemailban' => 'Takista kasutajal e-kirjade saatmine',
'ipbenableautoblock' => 'Blokeeri automaatselt viimane IP-aadress, mida see kasutaja kasutas, ja ka järgnevad, mille alt ta võib proovida kaastööd teha',
'ipbsubmit' => 'Blokeeri see kasutaja',
'ipbother' => 'Muu tähtaeg:',
'ipboptions' => '2 tundi:2 hours,1 päev:1 day,3 päeva:3 days,1 nädal:1 week,2 nädalat:2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite',
'ipbotheroption' => 'muu tähtaeg',
'ipbotherreason' => 'Muu või täiendav põhjus:',
'ipbhidename' => 'Peida kasutajatunnus muudatustest ja loenditest',
'ipbwatchuser' => 'Jälgi selle kasutaja lehekülge ja arutelu',
'ipb-disableusertalk' => 'Keela sellel kasutajal blokeeringu ajal oma arutelulehekülge redigeerida',
'ipb-change-block' => 'Blokeeri uuesti samade sätete alusel',
'ipb-confirm' => 'Kinnita blokeering',
'badipaddress' => 'Vigane IP-aadress',
'blockipsuccesssub' => 'Blokeerimine õnnestus',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] on blokeeritud.<br />
Kehtivaid blokeeringuid vaata [[Special:BlockList|blokeerimisnimekirjast]].',
'ipb-blockingself' => 'Sa blokeerid iseenda! Kas tahad tõesti seda teha?',
'ipb-confirmhideuser' => 'Avaldasid soovi kasutaja blokeerida ja peita. Kasutaja nimi peidetakse kõigist loenditest ja logisissekannetest. Kas oled kindel, et soovid seda teha?',
'ipb-edit-dropdown' => 'Muuda blokeeringu põhjuseid',
'ipb-unblock-addr' => 'Tühista kasutaja $1 blokeering',
'ipb-unblock' => 'Kasutaja või IP-aadressi vabastamine blokeerimisest',
'ipb-blocklist' => 'Vaata kehtivaid blokeeringuid',
'ipb-blocklist-contribs' => 'Kasutaja $1 kaastöö',
'unblockip' => 'Blokeerimise eemaldamine',
'unblockiptext' => 'Kasuta allpool olevat vormi varem blokeeritud IP-aadressi või kasutaja redigeerimisõiguse taastamiseks.',
'ipusubmit' => 'Eemalda see blokeering',
'unblocked' => 'Kasutaja [[User:$1|$1]] blokeering on eemaldatud',
'unblocked-range' => 'Vahemiku $1 blokeering on eemaldatud',
'unblocked-id' => 'Blokeerimine $1 on lõpetatud',
'blocklist' => 'Blokeeritud kasutajad',
'ipblocklist' => 'Blokeeritud kasutajad',
'ipblocklist-legend' => 'Leia blokeeritud kasutaja',
'blocklist-userblocks' => 'Peida kontod',
'blocklist-tempblocks' => 'Peida ajutised',
'blocklist-addressblocks' => 'Peida üksikud IP-aadressid',
'blocklist-rangeblocks' => 'Peida vahemikublokeeringud',
'blocklist-timestamp' => 'Ajatempel',
'blocklist-target' => 'Blokeeritav',
'blocklist-expiry' => 'Aegumistähtaeg',
'blocklist-by' => 'Blokeerija',
'blocklist-params' => 'Blokeerimissätted',
'blocklist-reason' => 'Põhjus',
'ipblocklist-submit' => 'Otsi',
'ipblocklist-localblock' => 'Kohalikud blokeeringud',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Muu blokeering|Muud blokeeringud}}',
'infiniteblock' => 'igavene',
'expiringblock' => 'aegub $1 $2',
'anononlyblock' => 'ainult nimetuna',
'noautoblockblock' => 'IP-aadressi ei blokita automaatselt',
'createaccountblock' => 'kontode loomine keelatud',
'emailblock' => 'e-kirjade saatmine keelatud',
'blocklist-nousertalk' => 'ei saa oma arutelulehte muuta',
'ipblocklist-empty' => 'Blokeerimiste loend on tühi.',
'ipblocklist-no-results' => 'Nõutud IP-aadress või kasutajatunnus ei ole blokeeritud.',
'blocklink' => 'blokeeri',
'unblocklink' => 'lõpeta blokeerimine',
'change-blocklink' => 'muuda blokeeringut',
'contribslink' => 'kaastöö',
'emaillink' => 'saada e-kiri',
'autoblocker' => 'Automaatselt blokeeritud, kuna [[User:$1|$1]] on hiljuti sinu IP-aadressi kasutanud. Põhjus: $2',
'blocklogpage' => 'Blokeerimislogi',
'blocklog-showlog' => 'See kasutaja on varem blokeeritud. Allpool on toodud blokeerimislogi sissekanne:',
'blocklog-showsuppresslog' => 'See kasutaja on varem blokeeritud ja peidetud. Allpool on toodud varjamislogi:',
'blocklogentry' => 'blokeeris kasutaja [[$1]]. Blokeeringu aegumistähtaeg on $2 $3',
'reblock-logentry' => 'muutis kasutaja või IP-aadressi [[$1]] blokeeringu sätteid. Blokeering aegumistähtaeg: $2. Põhjus: $3',
'blocklogtext' => 'See on kasutajate blokeerimiste ja blokeeringute eemaldamiste logi.
Automaatselt blokeeritud IP-aadresse siin ei näidata.
Praegu jõus olevad blokeeringud ja redigeerimiskeelud leiad [[Special:BlockList|blokeerimisnimekirjast]].',
'unblocklogentry' => 'eemaldas kasutaja $1 blokeeringu',
'block-log-flags-anononly' => 'ainult anonüümsed kasutajad',
'block-log-flags-nocreate' => 'kontode loomine on blokeeritud',
'block-log-flags-noautoblock' => 'ei blokeerita automaatselt',
'block-log-flags-noemail' => 'e-kirjade saatmine keelatud',
'block-log-flags-nousertalk' => 'ei saa muuta enda arutelulehte',
'block-log-flags-angry-autoblock' => 'Täiustatud automaatblokeerija sisse lülitatud',
'block-log-flags-hiddenname' => 'kasutajanimi peidetud',
'range_block_disabled' => 'Administraatori õigus blokeerida IP-aadresside vahemik on ära võetud.',
'ipb_expiry_invalid' => 'Vigane aegumise tähtaeg.',
'ipb_expiry_temp' => 'Peidetud kasutajanime blokeeringud peavad olema alalised.',
'ipb_hide_invalid' => 'Selle konto varjamine ei õnnestunud. Sellelt võib olla tehtud liiga palju redigeerimisi.',
'ipb_already_blocked' => '"$1" on juba blokeeritud.',
'ipb-needreblock' => '$1 on juba blokeeritud.
Kas soovid muuta blokeeringu sätteid?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Teine blokeering|Teised blokeeringud}}',
'unblock-hideuser' => 'Selle kasutaja blokeeringut ei saa eemaldada, sest tema kasutajanimi on peidetud.',
'ipb_cant_unblock' => 'Tõrge: Blokeerimis-ID $1 pole leitav.
Blokeering võib juba eemaldatud olla.',
'ipb_blocked_as_range' => 'Tõrge: IP-aadressi $1 pole eraldi blokeeritud ja blokeeringut ei saa eemaldada.
See kuulub aga blokeeritud IP-vahemikku $2, mille blokeeringut saab eemaldada.',
'ip_range_invalid' => 'Vigane IP-vahemik.',
'ip_range_toolarge' => 'Suuremad aadressiblokid kui /$1 pole lubatud.',
'blockme' => 'Blokeeri mind',
'proxyblocker' => 'Proksiblokeerija',
'proxyblocker-disabled' => 'See funktsioon ei toimi.',
'proxyblockreason' => 'Sinu IP-aadress on blokeeritud, sest see on avatud proksi. Palun võta ühendust oma internetiteenuse pakkujaga või tehnilise toega ja teata neile sellest probleemist.',
'proxyblocksuccess' => 'Tehtud.',
'sorbsreason' => 'Sinu IP-aadress on {{GRAMMAR:genitive|{{SITENAME}}}} kasutatavas DNS-põhises mustas nimekirjas märgitud kui avatud proksi.',
'sorbs_create_account_reason' => 'Sinu IP-aadress on {{GRAMMAR:genitive|{{SITENAME}}}} kasutatavas DNS-põhises mustas nimekirjas märgitud kui avatud proksi.
Sa ei saa kasutajakontot luua.',
'cant-block-while-blocked' => 'Teisi kasutajaid ei saa blokeerida, kui oled ise blokeeritud.',
'cant-see-hidden-user' => 'Kasutaja, keda blokeerida üritad, on juba blokeeritud ning peidetud. Kuna sul pole õigust blokeerida kasutajanimesid, peites need avalikkuse eest, ei saa sa selle kasutaja blokeeringut vaadata ega muuta.',
'ipbblocked' => 'Sa ei saa teisi blokeerida ega nende blokeeringuid eemaldada, sest oled ise blokeeritud.',
'ipbnounblockself' => 'Sul pole lubatud enda blokeeringut eemaldada.',

# Developer tools
'lockdb' => 'Lukusta andmebaas',
'unlockdb' => 'Tee andmebaas lukust lahti',
'lockdbtext' => 'Andmebaasi lukustamine peatab kõigi kasutajate võimaluse muuta lehtekülgi, oma eelistusi ja jälgimisloendit ja teha teisi toiminguid, mis vajavad muudatusi andmebaasis.
Palun kinnita, et soovid seda teha ja et avad andmebaasi, kui hööldustööd on tehtud.',
'unlockdbtext' => 'Andmebaasi lukust lahti tegemine taastab kõigi kasutajate võimaluse toimetada lehekülgi, muuta oma eelistusi, toimetada oma jälgimisloendeid ja muud, mis nõuab muudatusi andmebaasis.
Palun kinnita, et sa tahad seda teha.',
'lockconfirm' => 'Jah, ma soovin andmebaasi lukustada.',
'unlockconfirm' => 'Jah, ma tõesti soovin andmebaasi lukust avada.',
'lockbtn' => 'Võta andmebaas kirjutuskaitse alla',
'unlockbtn' => 'Taasta andmebaasi kirjutuspääs',
'locknoconfirm' => 'Sa ei märkinud kinnituskastikesse linnukest.',
'lockdbsuccesssub' => 'Andmebaas kirjutuskaitse all',
'unlockdbsuccesssub' => 'Kirjutuspääs taastatud',
'lockdbsuccesstext' => 'Andmebaas on nüüd lukustatud.<br />
Kui sinu hooldustöö on läbi, ära unusta [[Special:UnlockDB|kirjutuspääsu taastada]]!',
'unlockdbsuccesstext' => 'Andmebaasi kirjutuspääs on taastatud.',
'lockfilenotwritable' => 'Andmebaasi lukufail ei ole kirjutatav.
Andmebaasi lukustamiseks ja avamiseks peavad veebiserveril olema sellele kirjutusõigused.',
'databasenotlocked' => 'Andmebaas ei ole lukustatud.',
'lockedbyandtime' => '(lukustas $1; $2, kell $3)',

# Move page
'move-page' => 'Teisalda $1',
'move-page-legend' => 'Lehekülje teisaldamine',
'movepagetext' => "Allolevat vormi kasutades saad lehekülje ümber nimetada. Lehekülje ajalugu tõstetakse uue pealkirja alla automaatselt.
Praeguse pealkirjaga leheküljest saab ümbersuunamislehekülg uuele leheküljele.
Saad senisele pealkirjale viitavad ümbersuunamised automaatselt parandada.
Kui sa seda ei tee, kontrolli, et teisaldamise tõttu ei jää maha [[Special:DoubleRedirects|kahekordseid]] ega [[Special:BrokenRedirects|katkiseid ümbersuunamisi]].
Sinu kohus on hoolitseda selle eest, et kõik jääks toimima, nagu ette nähtud.

Pane tähele, et lehekülge '''ei teisaldata''' juhul, kui uue pealkirjaga lehekülg on juba olemas. Erandiks on juhud, kui olemasolev lehekülg on tühi või redigeerimisajaloota ümbersuunamislehekülg.
See tähendab, et kogemata ei saa üle kirjutada juba olemasolevat lehekülge, kuid saab ebaõnnestunud ümbernimetamise tagasi pöörata.

'''Hoiatus!'''
Tegu võib olla väga loetava lehekülje jaoks tõsise ja ootamatu muudatusega;
enne jätkamist teadvusta palun tagajärgi.",
'movepagetext-noredirectfixer' => "Allolevat vormi kasutades saad lehekülje ümber nimetada. Lehekülje ajalugu tõstetakse uue pealkirja alla automaatselt.
Praeguse pealkirjaga leheküljest saab ümbersuunamislehekülg uuele leheküljele.
Kontrolli, et teisaldamise tõttu ei jää maha [[Special:DoubleRedirects|kahekordseid]] ega [[Special:BrokenRedirects|katkiseid ümbersuunamisi]].
Sinu kohus on hoolitseda selle eest, et kõik jääks toimima, nagu ette nähtud.

Pane tähele, et lehekülge '''ei teisaldata''' juhul, kui uue pealkirjaga lehekülg on juba olemas. Erandiks on juhud, kui olemasolev lehekülg on tühi või redigeerimisajaloota ümbersuunamislehekülg.
See tähendab, et kogemata ei saa üle kirjutada juba olemasolevat lehekülge, kuid saab ebaõnnestunud ümbernimetamise tagasi pöörata.

'''Hoiatus!'''
Tegu võib olla väga loetava lehekülje jaoks tõsise ja ootamatu muudatusega;
enne jätkamist teadvusta palun tagajärgi.",
'movepagetalktext' => "Koos artiklileheküljega teisaldatakse automaatselt ka arutelulehekülg, '''välja arvatud juhtudel, kui:'''
*uue pealkirja all on juba arutelulehekülg, mis pole tühi;
*jätad alloleva märkeruudu valimata.

Neil juhtudel saad lehekülje soovi korral käsitsi teisaldada või liita.",
'movearticle' => 'Teisalda lehekülg',
'moveuserpage-warning' => "'''Hoiatus:''' Oled teisaldamas kasutajalehekülge. Pane tähele, et teisaldatakse ainult lehekülg ja kasutajat '''ei''' nimetata ümber.",
'movenologin' => 'Sisse logimata',
'movenologintext' => 'Lehekülje teisaldamiseks pead registreeruma ja [[Special:UserLogin|sisse logima]].',
'movenotallowed' => 'Sul ei ole lehekülgede teisaldamise õigust.',
'movenotallowedfile' => 'Sul ei ole failide teisaldamise õigust.',
'cant-move-user-page' => 'Sul ei ole õigust teisaldada kasutajalehti (erandiks on kasutajate alamlehed).',
'cant-move-to-user-page' => 'Sul ei ole õigust teisaldada lehekülge kasutajaleheks (ei käi kasutaja alamlehe kohta).',
'newtitle' => 'Uue pealkirja alla',
'move-watch' => 'Jälgi seda lehekülge',
'movepagebtn' => 'Teisalda lehekülg',
'pagemovedsub' => 'Lehekülg on teisaldatud',
'movepage-moved' => '\'\'\'"$1" teisaldatud pealkirja "$2" alla\'\'\'',
'movepage-moved-redirect' => 'Ümbersuunamisleht loodud.',
'movepage-moved-noredirect' => 'Ümbersuunamist ei loodud.',
'articleexists' => 'Selle nimega artikkel on juba olemas või pole valitud nimi lubatav. Palun valige uus nimi.',
'cantmove-titleprotected' => 'Lehte ei saa sinna teisaldada, sest uus pealkiri on artikli loomise eest kaitstud',
'talkexists' => 'Lehekülg on teisaldatud, kuid arutelulehekülge ei saanud teisaldada, sest uue nime all on arutelulehekülg juba olemas. Palun ühendage aruteluleheküljed ise.',
'movedto' => 'Teisaldatud pealkirja alla:',
'movetalk' => 'Teisalda ka "arutelu", kui saab.',
'move-subpages' => 'Teisalda alamleheküljed (kuni $1)',
'move-talk-subpages' => 'Teisalda arutelulehekülje alamleheküljed (kuni $1)',
'movepage-page-exists' => 'Lehekülg $1 on juba olemas ja seda ei saa automaatselt üle kirjutada.',
'movepage-page-moved' => 'Lehekülg $1 on teisaldatud pealkirja $2 alla.',
'movepage-page-unmoved' => 'Lehekülge $1 ei saanud teisaldada pealkirja $2 alla.',
'movepage-max-pages' => 'Teisaldatud on $1 {{PLURAL:$1|lehekülg|lehekülge}}, mis on teisaldatavate lehekülgede ülemmäär. Rohkem lehekülgi automaatselt ei teisaldata.',
'movelogpage' => 'Teisaldamislogi',
'movelogpagetext' => 'See logi sisaldab infot lehekülgede teisaldamistest.',
'movesubpage' => '{{PLURAL:$1|Alamlehekülg|Alamleheküljed}}',
'movesubpagetext' => 'Selle lehekülje $1 {{PLURAL:$1|alamlehekülg|alamlehekülge}} on kuvatud allpool.',
'movenosubpage' => 'Sellel leheküljel pole alamlehekülgi.',
'movereason' => 'Põhjus:',
'revertmove' => 'taasta',
'delete_and_move' => 'Kustuta ja teisalda',
'delete_and_move_text' => '== Vajalik kustutamine ==
Sihtlehekülg "[[:$1]]" on juba olemas.
Kas kustutad selle, et luua võimalus teisaldamiseks?',
'delete_and_move_confirm' => 'Jah, kustuta lehekülg',
'delete_and_move_reason' => 'Kustutatud, et tõsta asemele lehekülg "[[$1]]"',
'selfmove' => 'Algne nimi ja uus nimi on samad.',
'immobile-source-namespace' => 'Lehekülgi ei saa teisaldada nimeruumis $1',
'immobile-target-namespace' => 'Lehekülgi ei saa teisaldada nimeruumi "$1"',
'immobile-target-namespace-iw' => 'Keelelink ei ole sobiv koht lehekülje teisaldamiseks.',
'immobile-source-page' => 'Lehekülg ei ole teisaldatav.',
'immobile-target-page' => 'Soovitud pealkirja alla ei saa teisaldada.',
'imagenocrossnamespace' => 'Faili ei saa teisaldada mõnda muusse nimeruumi',
'nonfile-cannot-move-to-file' => 'Failinimeruumi saab ainult faile teisaldada.',
'imagetypemismatch' => 'Uus faililaiend ei sobi selle tüübiga',
'imageinvalidfilename' => 'Sihtmärgi nimi on vigane',
'fix-double-redirects' => 'Värskenda kõik siia viitavad ümbersuunamislehed uuele pealkirjale',
'move-leave-redirect' => 'Jäta maha ümbersuunamisleht',
'protectedpagemovewarning' => "'''Hoiatus:''' See lehekülg on nii lukustatud, et ainult administraatori õigustega kasutajad saavad seda teisaldada.
Allpool on toodud uusim logisissekanne:",
'semiprotectedpagemovewarning' => "'''Pane tähele:''' See lehekülg on lukustatud, nii et ainult registreeritud kasutajad saavad seda teisaldada.
Allpool on toodud uusim logisissekanne:",
'move-over-sharedrepo' => '== Fail on olemas ==
[[:$1]] on olemas jagatud failivaramus. Faili teisaldamisel selle nime alla varjatakse jagatud failivarmus olev samanimeline fail.',
'file-exists-sharedrepo' => 'Valitud failinimi on juba kasutusel jagatud failivaramus.
Palun kasuta mõnda teist nime.',

# Export
'export' => 'Lehekülgede eksport',
'exporttext' => 'Saad eksportida kindla leheküljel või lehekülgede kogumi teksti ja redigeerimisloo XML-kujule viiduna.
Seda saab teise vikisse importida, kasutades selleks MediaWiki [[Special:Import|impordi lehekülge]].

Et eksportida lehekülgi, sisesta nende pealkirjad allolevasse tekstikasti, iga pealkiri ise reale ja vali, kas soovid viimast redaktsiooni ja kõiki vanemaid redaktsioone ühes redigeerimislooga või viimast redaktsiooni ühes andmetega viimase redigeerimise kohta.

Viimasel juhul saab kasutada ka linki, näiteks lehekülje "[[{{MediaWiki:Mainpage}}]]" jaoks [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportall' => 'Ekspordi kõik leheküljed',
'exportcuronly' => 'Lisa vaid viimane redaktsioon, mitte kogu ajalugu',
'exportnohistory' => "----
'''Märkus:''' Lehekülgede täieliku ajaloo eksportimine on siin leheküljel jõudluse tagamiseks blokeeritud.",
'exportlistauthors' => 'Lisa kõigile lehekülgedele kogu kaastööliste nimekiri',
'export-submit' => 'Ekspordi',
'export-addcattext' => 'Kõik leheküljed kategooriast:',
'export-addcat' => 'Lisa',
'export-addnstext' => 'Lisa lehti nimeruumist:',
'export-addns' => 'Lisa',
'export-download' => 'Salvesta failina',
'export-templates' => 'Kaasa mallid',
'export-pagelinks' => 'Kaasan viidatud lehed kuni tasemeni',

# Namespace 8 related
'allmessages' => 'Kõik süsteemi sõnumid',
'allmessagesname' => 'Nimi',
'allmessagesdefault' => 'Vaikimisi tekst',
'allmessagescurrent' => 'Praegune tekst',
'allmessagestext' => 'See on loend kõikidest olemasolevatest süsteemisõnumitest MediaWiki nimeruumis.
Kui soovid MediaWiki tarkvara tõlkimises osaleda, siis vaata lehti [//www.mediawiki.org/wiki/Localisation MediaWiki lokaliseerimine] ja [//translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB' => "Seda lehekülge ei saa kasutada, sest '''\$wgUseDatabaseMessages''' ei tööta.",
'allmessages-filter-legend' => 'Filter',
'allmessages-filter' => 'Muutmisoleku filter:',
'allmessages-filter-unmodified' => 'Muutmata',
'allmessages-filter-all' => 'Kõik',
'allmessages-filter-modified' => 'Muudetud',
'allmessages-prefix' => 'Eesliitefilter:',
'allmessages-language' => 'Keel:',
'allmessages-filter-submit' => 'Mine',

# Thumbnails
'thumbnail-more' => 'Suurenda',
'filemissing' => 'Fail puudub',
'thumbnail_error' => 'Viga pisipildi loomisel: $1',
'djvu_page_error' => 'DjVu-failis ei ole sellist lehekülge',
'djvu_no_xml' => 'DjVu failist XML-i lugemine ebaõnnestus.',
'thumbnail-temp-create' => 'Ajutist pisipildifaili ei õnnestu luua.',
'thumbnail-dest-create' => 'Pisipilti ei õnnestu sihtkohas salvestada',
'thumbnail_invalid_params' => 'Vigased pisipildi parameetrid',
'thumbnail_dest_directory' => 'Sihtkataloogi loomine ebaõnnestus.',
'thumbnail_image-type' => 'Selline pildi tüüp ei ole toetatav',
'thumbnail_gd-library' => 'GD teegi häälestus on poolik: funktsioon $1 puudub',
'thumbnail_image-missing' => 'Fail näib puuduvat: $1',

# Special:Import
'import' => 'Lehekülgede import',
'importinterwiki' => 'Vikidevaheline import',
'import-interwiki-text' => 'Vali importimiseks viki ja lehekülje pealkiri.
Redigeerimisajad ja kaastööliste nimed säilitatakse.
Kõik vikidevahelised toimingud on [[Special:Log/import|impordilogis]].',
'import-interwiki-source' => 'Lähteviki/lehekülg:',
'import-interwiki-history' => 'Kopeeri selle lehekülje kogu ajalugu',
'import-interwiki-templates' => 'Liida kõik mallid',
'import-interwiki-submit' => 'Impordi',
'import-interwiki-namespace' => 'Sihtkoha nimeruum:',
'import-interwiki-rootpage' => 'Sihtkoha juurlehekülg (valikuline):',
'import-upload-filename' => 'Failinimi:',
'import-comment' => 'Kommentaar:',
'importtext' => 'Palun kasuta faili allikvikist eksportimiseks [[Special:Export|ekspordivahendit]].
Salvesta see oma arvutisse ja laadi siia üles.',
'importstart' => 'Lehekülgede importimine...',
'import-revision-count' => '$1 {{PLURAL:$1|versioon|versiooni}}',
'importnopages' => 'Ei olnud imporditavaid lehekülgi.',
'imported-log-entries' => 'Imporditi $1 {{PLURAL:$1|logisissekanne|logisissekannet}}.',
'importfailed' => 'Importimine ebaõnnestus: <nowiki>$1</nowiki>',
'importunknownsource' => 'Tundmatut tüüpi impordiallikas',
'importcantopen' => 'Ei saa imporditavat faili avada',
'importbadinterwiki' => 'Vigane vikidevaheline link',
'importnotext' => 'Tühi või ilma tekstita',
'importsuccess' => 'Importimine edukalt lõpetatud!',
'importhistoryconflict' => 'Konfliktne muudatuste ajalugu (võimalik, et seda lehekülge juba varem imporditud)',
'importnosources' => 'Ühtegi vikidevahelist impordiallikat pole määratletud ja otsene ajaloo üleslaadimine on keelatud.',
'importnofile' => 'Ühtegi imporditavat faili ei laaditud üles.',
'importuploaderrorsize' => 'Üleslaaditava faili import ebaõnnestus.
Fail on lubatust suurem.',
'importuploaderrorpartial' => 'Imporditava faili üleslaadimine ebaõnnestus.
Fail oli vaid osaliselt üleslaaditud.',
'importuploaderrortemp' => 'Üleslaaditava faili import ebaõnnestus.
Puudub ajutine kataloog.',
'import-parse-failure' => 'Viga XML-i importimisel',
'import-noarticle' => 'Ühtki lehekülge polnud importida!',
'import-nonewrevisions' => 'Kõik versioonid on eelnevalt imporditud.',
'xml-error-string' => '$1 real $2, tulbas $3 (bait $4): $5',
'import-upload' => 'Laadi üles XML-andmed',
'import-token-mismatch' => 'Seansiandmed läksid kaduma.
Palun ürita uuesti.',
'import-invalid-interwiki' => 'Määratud vikist ei saa importida.',
'import-error-edit' => 'Lehekülge "$1" ei imporditud, sest sul pole õigust seda muuta.',
'import-error-create' => 'Lehekülge "$1" ei imporditud, sest sul pole õigust seda luua.',
'import-error-interwiki' => 'Lehekülge "$1" ei impordita, sest selle pealkirja hoitakse välislinkide (interviki) jaoks.',
'import-error-special' => 'Lehekülge "$1" ei impordita, sest see kuulub erinimeruumi, kus pole leheküljed lubatud.',
'import-error-invalid' => 'Lehekülge "$1" ei impordita, sest selle pealkiri on vigane.',
'import-options-wrong' => '{{PLURAL:$2|Vale valik|Valed valikud}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'Etteantud juurlehekülje pealkiri on vigane.',
'import-rootpage-nosubpage' => 'Juurlehekülje nimeruumis "$1" pole alamleheküljed lubatud.',

# Import log
'importlogpage' => 'Impordilogi',
'importlogpagetext' => 'Importimislogi kuvab leheküljed, mille redigeerimisajalugu pärineb teistest vikidest.',
'import-logentry-upload' => 'importis faili üleslaadimisega lehekülje [[$1]]',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'import-logentry-interwiki' => 'importis teisest vikist lehekülje $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}} asukohast $2',

# JavaScriptTest
'javascripttest' => 'JavaScripti katsetamine',
'javascripttest-disabled' => 'See funktsioon pole selles vikis lubatud.',
'javascripttest-title' => '$1-katse käitus',
'javascripttest-pagetext-noframework' => 'Seda lehekülge hoitakse JavaScripti katsete jaoks.',
'javascripttest-pagetext-unknownframework' => 'Tundmatu katseraamistik "$1".',
'javascripttest-pagetext-frameworks' => 'Palun vali üks järgmistest katseraamistikest: $1',
'javascripttest-pagetext-skins' => 'Vali kujundus, millega katsetada:',
'javascripttest-qunit-intro' => 'Vaata [$1 katsetamise dokumentatsiooni] asukohas mediawiki.org.',
'javascripttest-qunit-heading' => 'MediaWiki JavaScripti QUnit-katsekomplekt',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Sinu kasutajaleht',
'tooltip-pt-anonuserpage' => 'Sinu IP-aadressi kasutajalehekülg',
'tooltip-pt-mytalk' => 'Minu aruteluleht',
'tooltip-pt-anontalk' => 'Arutelu sellelt IP-aadressilt tehtud muudatuste kohta',
'tooltip-pt-preferences' => 'Minu eelistused',
'tooltip-pt-watchlist' => 'Lehekülgede loend, mida jälgid muudatuste osas',
'tooltip-pt-mycontris' => 'Sinu kaastööde loend',
'tooltip-pt-login' => 'Me julgustame teid sisse logima, kuid see pole kohustuslik.',
'tooltip-pt-anonlogin' => 'Me julgustame teid sisse logima, kuid see pole kohustuslik.',
'tooltip-pt-logout' => 'Logi välja',
'tooltip-ca-talk' => 'Selle artikli arutelu',
'tooltip-ca-edit' => 'Saad seda lehekülge redigeerida. Palun kasuta enne salvestamist eelvaadet.',
'tooltip-ca-addsection' => 'Lisa uus alaosa',
'tooltip-ca-viewsource' => 'See lehekülg on kaitstud.
Saad vaadata selle lähteteksti.',
'tooltip-ca-history' => 'Selle lehekülje varasemad redaktsioonid',
'tooltip-ca-protect' => 'Kaitse seda lehekülge',
'tooltip-ca-unprotect' => 'Muuda selle lehekülje kaitset',
'tooltip-ca-delete' => 'Kustuta see lehekülg',
'tooltip-ca-undelete' => 'Taasta enne lehekülje kustutamist tehtud muudatused',
'tooltip-ca-move' => 'Teisalda see lehekülg teise nime alla.',
'tooltip-ca-watch' => 'Lisa see lehekülg oma jälgimisloendisse',
'tooltip-ca-unwatch' => 'Eemalda see lehekülg oma jälgimisloendist',
'tooltip-search' => 'Otsi vikist',
'tooltip-search-go' => 'Siirdutakse täpselt sellist pealkirja kandvale lehele (kui selline on olemas)',
'tooltip-search-fulltext' => 'Otsitakse teksti sisaldavaid artikleid',
'tooltip-p-logo' => 'Esileht',
'tooltip-n-mainpage' => 'Mine esilehele',
'tooltip-n-mainpage-description' => 'Mine esilehele',
'tooltip-n-portal' => 'Projekti kohta, mida teha saad, kuidas asju leida',
'tooltip-n-currentevents' => 'Leia teavet toimuvate sündmuste kohta',
'tooltip-n-recentchanges' => 'Vikis tehtud viimaste muudatuste loend',
'tooltip-n-randompage' => 'Mine juhuslikule leheküljele',
'tooltip-n-help' => 'Kuidas redigeerida',
'tooltip-t-whatlinkshere' => 'Kõik viki leheküljed, mis siia viitavad',
'tooltip-t-recentchangeslinked' => 'Viimased muudatused lehekülgedel, milledele on siit viidatud',
'tooltip-feed-rss' => 'Selle lehekülje RSS-toide',
'tooltip-feed-atom' => 'Selle lehekülje Atom-toide',
'tooltip-t-contributions' => 'Kuva selle kasutaja kaastöö',
'tooltip-t-emailuser' => 'Saada sellele kasutajale e-kiri',
'tooltip-t-upload' => 'Laadi faile üles',
'tooltip-t-specialpages' => 'Erilehekülgede loend',
'tooltip-t-print' => 'Selle lehe trükiversioon',
'tooltip-t-permalink' => 'Püsilink lehekülje sellele redaktsioonile',
'tooltip-ca-nstab-main' => 'Näita artiklit',
'tooltip-ca-nstab-user' => 'Näita kasutaja lehte',
'tooltip-ca-nstab-media' => 'Näita pildi lehte',
'tooltip-ca-nstab-special' => 'See on erilehekülg, sa ei saa seda lehekülge ennast redigeerida.',
'tooltip-ca-nstab-project' => 'Näita projekti lehte',
'tooltip-ca-nstab-image' => 'Näita pildi lehte',
'tooltip-ca-nstab-mediawiki' => 'Näita süsteemi sõnumit',
'tooltip-ca-nstab-template' => 'Näita malli',
'tooltip-ca-nstab-help' => 'Näita abilehte',
'tooltip-ca-nstab-category' => 'Näita kategooria lehte',
'tooltip-minoredit' => 'Märgi see pisiparanduseks',
'tooltip-save' => 'Salvesta muudatused',
'tooltip-preview' => 'Näita tehtavaid muudatusi. Palun kasutage seda enne salvestamist!',
'tooltip-diff' => 'Näita tehtavaid muudatusi.',
'tooltip-compareselectedversions' => 'Näita erinevusi kahe selle lehe valitud versiooni vahel.',
'tooltip-watch' => 'Lisa see lehekülg oma jälgimisloendisse',
'tooltip-watchlistedit-normal-submit' => 'Eemalda leheküljed',
'tooltip-watchlistedit-raw-submit' => 'Uuenda jälgimisloendit',
'tooltip-recreate' => 'Taasta kustutatud lehekülg',
'tooltip-upload' => 'Alusta üleslaadimist',
'tooltip-rollback' => 'Tühistab ühe klõpsuga viimase kaastöölise tehtud muudatused.',
'tooltip-undo' => '"Eemalda" tühistab selle muudatuse ja avab teksti eelvaatega redigeerimisakna.
Samuti võimaldab see resümee reale põhjenduse lisamist.',
'tooltip-preferences-save' => 'Salvesta eelistused',
'tooltip-summary' => 'Kirjuta lühike kokkuvõte',

# Stylesheets
'common.css' => '/* Siin olevat CSS-i kasutavad kõik kujundused. */',
'standard.css' => '/* Siin olev CSS puudutab Standard-kujunduse kasutajaid. */',

# Scripts
'common.js' => '/* Siinne JavaScript laaditakse igale kasutajatele igal laaditud leheküljel. */',

# Metadata
'notacceptable' => 'Viki server ei saa esitada andmeid formaadis, mida sinu veebiklient lugeda suudab.',

# Attribution
'anonymous' => '{{GRAMMAR:genitive|{{SITENAME}}}} {{PLURAL:$1|anonüümne kasutaja|anonüümsed kasutajad}}',
'siteuser' => '{{GRAMMAR:genitive|{{SITENAME}}}} kasutaja $1',
'anonuser' => '{{GRAMMAR:genitive|{{SITENAME}}}} anonüümne kasutaja $1',
'lastmodifiedatby' => 'Viimati muutis lehekülge $3 $2 kell $1.',
'othercontribs' => 'Põhineb järgmiste kasutajate kaastööl: $1.',
'others' => 'teiste',
'siteusers' => '{{GRAMMAR:genitive|{{SITENAME}}}} {{PLURAL:$2|kasutaja|kasutajad}} $1',
'anonusers' => '{{GRAMMAR:genitive|{{SITENAME}}}} {{PLURAL:$2|anonüümne kasutaja|anonüümsed kasutajad}} $1',
'creditspage' => 'Lehekülje toimetajate loend',
'nocredits' => 'Selle lehekülje toimetajate loend ei ole kättesaadav.',

# Spam protection
'spamprotectiontitle' => 'Spämmitõrjefilter',
'spamprotectiontext' => 'Rämpspostifilter oli lehekülje, mida sa salvestada tahtsid, blokeerinud.
See on ilmselt põhjustatud linkimisest mustas nimekirjas olevasse välisvõrgukohta.',
'spamprotectionmatch' => 'Järgnev tekst vallandas meie rämpspostifiltri: $1',
'spambot_username' => 'MediaWiki spämmieemaldus',
'spam_reverting' => 'Taastan viimase versiooni, mis ei sisalda linke aadressile $1.',
'spam_blanking' => 'Kõik versioonid sisaldasid linke veebilehele $1. Lehekülg tühjendatud.',
'spam_deleting' => 'Kustutatud kõik redaktsioonid, mis viitasid aadressile $1.',

# Info page
'pageinfo-title' => 'Teave lehekülje "$1" kohta',
'pageinfo-not-current' => 'Kahjuks pole vanade redaktsioonide kohta võimalik seda teavet ära tuua.',
'pageinfo-header-basic' => 'Põhiteave',
'pageinfo-header-edits' => 'Redigeerimislugu',
'pageinfo-header-restrictions' => 'Lehekülje kaitse',
'pageinfo-header-properties' => 'Lehekülje atribuudid',
'pageinfo-display-title' => 'Kuvatav pealkiri',
'pageinfo-default-sort' => 'Vaikejärjestusvõti',
'pageinfo-length' => 'Lehekülje pikkus (baitides)',
'pageinfo-article-id' => 'Lehekülje identifikaator',
'pageinfo-robot-policy' => 'Otsimootori olek',
'pageinfo-robot-index' => 'Indekseeritav',
'pageinfo-robot-noindex' => 'Indekseerimatu',
'pageinfo-views' => 'Vaatamiste arv',
'pageinfo-watchers' => 'Lehekülje jälgijate arv',
'pageinfo-redirects-name' => 'Ümbersuunamisi sellele leheküljele',
'pageinfo-subpages-name' => 'Selle lehekülje alamlehekülgi',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|ümbersuunamine|ümbersuunamist}}; $3 {{PLURAL:$3|mitteümbersuunamine|mitteümbersuunamist}})',
'pageinfo-firstuser' => 'Lehekülje alustaja',
'pageinfo-firsttime' => 'Lehekülje alustusaeg',
'pageinfo-lastuser' => 'Viimane toimetaja',
'pageinfo-lasttime' => 'Viimase redigeerimise aeg',
'pageinfo-edits' => 'Redaktsioonide koguarv',
'pageinfo-authors' => 'Erinevate autorite koguarv',
'pageinfo-recent-edits' => 'Viimaste redigeerimiste arv (viimase $1 jooksul)',
'pageinfo-recent-authors' => 'Erinevate viimaste toimetajate arv',
'pageinfo-magic-words' => '{{PLURAL:$1|Võlusõna|Võlusõnad}} ($1)',
'pageinfo-hidden-categories' => 'Peidetud {{PLURAL:$1|kategooria|kategooriad}} ($1)',
'pageinfo-templates' => 'Kasutatud {{PLURAL:$1|mall|mallid}} ($1)',

# Skin names
'skinname-standard' => 'Algeline',
'skinname-nostalgia' => 'Nostalgia',
'skinname-cologneblue' => 'Kölni sinine',
'skinname-monobook' => 'MonoBook',
'skinname-myskin' => 'Minu kujundus',
'skinname-chick' => 'Tibu',
'skinname-simple' => 'Lihtne',
'skinname-modern' => 'Uudne',
'skinname-vector' => 'Vektor',

# Patrolling
'markaspatrolleddiff' => 'Märgi kui kontrollitud',
'markaspatrolledtext' => 'Märgi see leht kontrollituks',
'markedaspatrolled' => 'Kontrollituks märgitud',
'markedaspatrolledtext' => 'Valitud redaktsioon leheküljel [[:$1]] on kontrollituks märgitud.',
'rcpatroldisabled' => 'Viimaste muudatuste kontroll ei toimi',
'rcpatroldisabledtext' => 'Viimaste muudatuste kontrolli tunnus ei toimi hetkel.',
'markedaspatrollederror' => 'Ei saa kontrollituks märkida',
'markedaspatrollederrortext' => 'Vajalik on määrata, milline versioon märkida kontrollituks.',
'markedaspatrollederror-noautopatrol' => 'Enda muudatusi ei saa kontrollituks märkida.',

# Patrol log
'patrol-log-page' => 'Kontrollimislogi',
'patrol-log-header' => 'See on kontrollitud redaktsioonide logi.',
'log-show-hide-patrol' => '$1 kontrollimislogi',

# Image deletion
'deletedrevision' => 'Kustutatud vanem versioon $1',
'filedeleteerror-short' => 'Faili $1 kustutamine ebaõnnestus',
'filedeleteerror-long' => 'Faili kustutamine ebaõnnestus:

$1',
'filedelete-missing' => 'Faili "$1" ei saa kustutada, sest seda ei ole.',
'filedelete-old-unregistered' => 'Etteantud failiversiooni "$1" pole andmebaasis.',
'filedelete-current-unregistered' => 'Fail "$1" ei ole andmebaasis.',
'filedelete-archive-read-only' => 'Arhiivikataloogi "$1" kirjutamine ebaõnnestus.',

# Browsing diffs
'previousdiff' => '← Eelmised erinevused',
'nextdiff' => 'Järgmised erinevused →',

# Media information
'mediawarning' => "'''Hoiatus''': See failitüüp võib sisaldada pahatahtlikku koodi.
Selle avamine võib su arvutit kahjustada.",
'imagemaxsize' => "Pildi suuruse ülemmäär:<br />''(faili kirjeldusleheküljel)''",
'thumbsize' => 'Pisipildi suurus:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|lehekülg|lehekülge}}',
'file-info' => 'faili suurus: $1, MIME tüüp: $2',
'file-info-size' => '$1 × $2 pikslit, faili suurus: $3, MIME tüüp: $4',
'file-info-size-pages' => '$1 × $2 pikslit, faili suurus: $3 , MIME tüüp: $4, $5 {{PLURAL:$5|lehekülg|lehekülge}}',
'file-nohires' => 'Sellest suuremat pilti pole.',
'svg-long-desc' => 'SVG-fail, algsuurus $1 × $2 pikslit, faili suurus: $3',
'svg-long-desc-animated' => 'Animeeritud SVG-fail,  algsuurus $1 × $2 pikslit, faili suurus: $3',
'show-big-image' => 'Originaalsuurus',
'show-big-image-preview' => 'Selle eelvaate suurus: $1.',
'show-big-image-other' => '{{PLURAL:$2|Teine eraldusvõime|Teised eraldusvõimed}}: $1.',
'show-big-image-size' => '$1 × $2 pikslit',
'file-info-gif-looped' => 'korduv',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kaader|kaadrit}}',
'file-info-png-looped' => 'korduv',
'file-info-png-repeat' => 'mängitud $1 {{PLURAL:$1|korra|korda}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|kaader|kaadrit}}',
'file-no-thumb-animation' => "'''Märkus: Tehniliste piirangute tõttu on selle faili pisipildid animeerimata.'''",
'file-no-thumb-animation-gif' => "'''Märkus: Tehniliste piirangute tõttu on nii suure eraldusega GIF-piltide pisipildid animeerimata.'''",

# Special:NewFiles
'newimages' => 'Uute meediafailide galerii',
'imagelisttext' => "
Järgnevas loendis, mis on sorteeritud $2, on '''$1''' {{PLURAL:$1|fail|faili}}.",
'newimages-summary' => 'Sellel erilehel on viimati üles laaditud failid.',
'newimages-legend' => 'Filter',
'newimages-label' => 'Failinimi (või selle osa):',
'showhidebots' => '($1 robotite kaastööd)',
'noimages' => 'Uusi pilte ei ole.',
'ilsubmit' => 'Otsi',
'bydate' => 'kuupäeva järgi',
'sp-newimages-showfrom' => 'Näita uusi faile alates kuupäevast $2, kell $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|üks sekund|$1 sekundit}}',
'minutes' => '{{PLURAL:$1|üks minut|$1 minutit}}',
'hours' => '{{PLURAL:$1|üks tund|$1 tundi}}',
'days' => '{{PLURAL:$1|üks päev|$1 päeva}}',
'ago' => '$1 tagasi',

# Bad image list
'bad_image_list' => 'Arvesse võetakse ainult nimekirja ühikud (read, mis algavad sümboliga *).
Esimene link real peab olema link kõlbmatule failile.
Samal real olevaid järgmiseid linke vaadeldakse kui erandeid, see tähendab artikleid, mille koosseisu kujutise võib lülitada.',

# Metadata
'metadata' => 'Metaandmed',
'metadata-help' => 'See fail sisaldab lisateavet, mille on tõenäoliselt lisanud digikaamera või skanner.
Kui faili on rakendustarkvaraga töödeldud, võib osa andmeid olla muudetud või täielikult eemaldatud.',
'metadata-expand' => 'Näita veel üksikasju',
'metadata-collapse' => 'Peida laiendatud üksikasjad',
'metadata-fields' => 'Siin loetletud pildi metaandmete välju näidatakse pildi kirjelduslehel vähemdetailse metaandmete vaate korral.
Ülejäänud andmed on vaikimisi peidetud.
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
* gpsaltitude
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth' => 'Laius',
'exif-imagelength' => 'Kõrgus',
'exif-bitspersample' => 'Bitti komponendi kohta',
'exif-compression' => 'Pakkimise skeem',
'exif-photometricinterpretation' => 'Pikslite koosseis',
'exif-orientation' => 'Orientatsioon',
'exif-samplesperpixel' => 'Komponentide arv',
'exif-planarconfiguration' => 'Andmejärjestus',
'exif-ycbcrpositioning' => 'Y- ja C-positsioonimine',
'exif-xresolution' => 'Horisontaalne eraldus',
'exif-yresolution' => 'Vertikaalne eraldus',
'exif-stripoffsets' => 'Pildi andmete asukoht',
'exif-rowsperstrip' => 'Ridade arv riba kohta',
'exif-stripbytecounts' => 'Baitide hulk kokkusurutud riba kohta',
'exif-jpeginterchangeformat' => 'Kaugus JPEG SOI-ni',
'exif-jpeginterchangeformatlength' => 'JPEG-andmete suurus baitides',
'exif-whitepoint' => 'Valgepunkti värvsus',
'exif-primarychromaticities' => 'Põhivärvide värvsus',
'exif-ycbcrcoefficients' => 'Värviruumi ümberkujundamise maatriksi koefitsiendid',
'exif-referenceblackwhite' => 'Musta ja valge kontrollväärtused',
'exif-datetime' => 'Faili muutmise kuupäev ja kellaaeg',
'exif-imagedescription' => 'Pildi pealkiri',
'exif-make' => 'Kaamera tootja',
'exif-model' => 'Kaamera mudel',
'exif-software' => 'Kasutatud tarkvara',
'exif-artist' => 'Autor',
'exif-copyright' => 'Autoriõiguste omanik',
'exif-exifversion' => 'Exif-versioon',
'exif-flashpixversion' => 'Toetatud Flashpixi versioon',
'exif-colorspace' => 'Värviruum',
'exif-componentsconfiguration' => 'Iga komponendi tähendus',
'exif-compressedbitsperpixel' => 'Pildi pakkimise meetod',
'exif-pixelydimension' => 'Pildi laius',
'exif-pixelxdimension' => 'Pildi kõrgus',
'exif-usercomment' => 'Kasutaja kommentaarid',
'exif-relatedsoundfile' => 'Seotud helifail',
'exif-datetimeoriginal' => 'Andmete loomise kuupäev ja kellaaeg',
'exif-datetimedigitized' => 'Digiteerimise kuupäev ja kellaaeg',
'exif-subsectime' => 'Kuupäev/Kellaaeg sekundi murdosad',
'exif-subsectimeoriginal' => 'Loomisaja sekundi murdosad',
'exif-subsectimedigitized' => 'Digiteerimise sekundi murdosad',
'exif-exposuretime' => 'Säriaeg',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-fnumber' => 'F-arv',
'exif-exposureprogram' => 'Säriprogramm',
'exif-spectralsensitivity' => 'Spektraalne tundlikkus',
'exif-isospeedratings' => 'Valgustundlikkus (ISO)',
'exif-shutterspeedvalue' => 'APEX-säriaeg',
'exif-aperturevalue' => 'APEX-avaarv',
'exif-brightnessvalue' => 'APEX-heledus',
'exif-exposurebiasvalue' => 'Särituse mõju',
'exif-subjectdistance' => 'Subjekti kaugus',
'exif-meteringmode' => 'Mõõtmisviis',
'exif-lightsource' => 'Valgusallikas',
'exif-flash' => 'Välk',
'exif-focallength' => 'Fookuskaugus',
'exif-flashenergy' => 'Välgu võimsus',
'exif-subjectlocation' => 'Subjekti asukoht',
'exif-exposureindex' => 'Särituse number',
'exif-sensingmethod' => 'Tundlikustamismeetod',
'exif-filesource' => 'Faili päritolu',
'exif-scenetype' => 'Võtte tüüp',
'exif-customrendered' => 'Kohandatud pilditöötlus',
'exif-exposuremode' => 'Särituse meetod',
'exif-whitebalance' => 'Valge tasakaal',
'exif-digitalzoomratio' => 'Digisuumi tegur',
'exif-focallengthin35mmfilm' => '35 mm-se filmi fookuskaugus',
'exif-scenecapturetype' => 'Ülesvõtte tüüp',
'exif-gaincontrol' => 'Tundlikkus',
'exif-contrast' => 'Kontrastsus',
'exif-saturation' => 'Küllastus',
'exif-sharpness' => 'Teravus',
'exif-devicesettingdescription' => 'Seadme seadistuste kirjeldus',
'exif-imageuniqueid' => 'Üksiku pildi ID',
'exif-gpsversionid' => 'GPS-tähise versioon',
'exif-gpslatituderef' => 'Põhja- või lõunalaius',
'exif-gpslatitude' => 'Laius',
'exif-gpslongituderef' => 'Ida- või läänepikkus',
'exif-gpslongitude' => 'Pikkus',
'exif-gpsaltituderef' => 'Viide kõrgusele merepinnast',
'exif-gpsaltitude' => 'Kõrgus merepinnast',
'exif-gpstimestamp' => 'GPS aeg (aatomikell)',
'exif-gpssatellites' => 'Mõõtmiseks kasutatud satelliidid',
'exif-gpsstatus' => 'Vastuvõtja olek',
'exif-gpsmeasuremode' => 'Mõõtmise meetod',
'exif-gpsdop' => 'Mõõtmise täpsus',
'exif-gpsspeedref' => 'Kiirusühik',
'exif-gpsspeed' => 'GPS-vastuvõtja kiirus',
'exif-gpstrack' => 'Liikumise suund',
'exif-gpsimgdirection' => 'Pildi suund',
'exif-gpsmapdatum' => 'Geodeetiline alus',
'exif-gpsdestlatitude' => 'Kujutatud koha laius',
'exif-gpsdestlongitude' => 'Kujutatud koha pikkus',
'exif-gpsdestdistance' => 'Sihtmärgi kaugus',
'exif-gpsprocessingmethod' => 'GPS-töötlusmeetodi nimi',
'exif-gpsareainformation' => 'GPS-ala nimi',
'exif-gpsdatestamp' => 'GPS kuupäev',
'exif-gpsdifferential' => 'GPS-i diferentsiaalparand',
'exif-jpegfilecomment' => 'JPEG-faili kommentaar',
'exif-keywords' => 'Märksõnad',
'exif-worldregioncreated' => 'Maailma piirkond, kus pilt on üles võetud',
'exif-countrycreated' => 'Maa, kus pilt on üles võetud',
'exif-countrycodecreated' => 'Ülesvõtte maa ISO-kood',
'exif-provinceorstatecreated' => 'Haldusüksus, kus pilt on üles võetud',
'exif-citycreated' => 'Linn, kus pilt on üles võetud',
'exif-sublocationcreated' => 'Linnaosa, kus pilt on üles võetud',
'exif-worldregiondest' => 'Kujutatud maailma piirkond',
'exif-countrydest' => 'Kujutatud maa',
'exif-countrycodedest' => 'Kujutatud maa ISO-kood',
'exif-provinceorstatedest' => 'Kujutatud haldusüksus',
'exif-citydest' => 'Kujutatud linn',
'exif-sublocationdest' => 'Kujutatud linnaosa',
'exif-objectname' => 'Lühipealkiri',
'exif-specialinstructions' => 'Erijuhised',
'exif-headline' => 'Pealkiri',
'exif-source' => 'Allikas',
'exif-urgency' => 'Pakilisus',
'exif-locationdest' => 'Kujutatud asukoht',
'exif-locationdestcode' => 'Kujutatud asukoha kood',
'exif-objectcycle' => 'Päeva osa, milleks meediafail on ette nähtud',
'exif-contact' => 'Kontaktteave',
'exif-writer' => 'Autor',
'exif-languagecode' => 'Keel',
'exif-iimversion' => 'IIM-i versioon',
'exif-iimcategory' => 'Kategooria',
'exif-iimsupplementalcategory' => 'Lisakategooriad',
'exif-datetimeexpires' => 'Mitte kasutada hiljem kui',
'exif-datetimereleased' => 'Avaldamiskuupäev',
'exif-identifier' => 'Identifikaator',
'exif-lens' => 'Kasutatud objektiiv',
'exif-serialnumber' => 'Kaamera seerianumber',
'exif-cameraownername' => 'Kaamera omanik',
'exif-label' => 'Silt',
'exif-datetimemetadata' => 'Metaandmete viimane muutmisaeg',
'exif-nickname' => 'Pildi vabas vormis nimi',
'exif-rating' => 'Hinne (5 palli skaala)',
'exif-rightscertificate' => 'Õiguste haldamise sertifikaat',
'exif-copyrighted' => 'Autoriõiguslik seisund',
'exif-copyrightowner' => 'Autoriõiguse valdaja',
'exif-usageterms' => 'Kasutustingimused',
'exif-webstatement' => 'Autoriõiguse ütlus võrgus',
'exif-originaldocumentid' => 'Algse dokumendi ainuline ID',
'exif-licenseurl' => 'Autoriõiguse litsentsi URL',
'exif-morepermissionsurl' => 'Teave muude võimalike litsentside kohta',
'exif-attributionurl' => 'Kui teost uuesti kasutad, lingi järgmiselt',
'exif-preferredattributionname' => 'Kui teost uuesti kasutad, too ära järgmine autorinimi',
'exif-pngfilecomment' => 'PNG-faili kommentaar',
'exif-disclaimer' => 'Lahtiütlus',
'exif-contentwarning' => 'Sisuhoiatus',
'exif-giffilecomment' => 'GIF-faili kommentaar',
'exif-intellectualgenre' => 'Üksuse tüüp',
'exif-subjectnewscode' => 'Teemakood',
'exif-scenecode' => 'IPTC-võttekood',
'exif-event' => 'Kujutatud sündmus',
'exif-organisationinimage' => 'Kujutatud organisatsioon',
'exif-personinimage' => 'Kujutatud isik',
'exif-originalimageheight' => 'Pildi kõrgus enne kärpimist',
'exif-originalimagewidth' => 'Pildi laius enne kärpimist',

# EXIF attributes
'exif-compression-1' => 'Pakkimata',

'exif-copyrighted-true' => 'Kaitstud',
'exif-copyrighted-false' => 'Avalikus omandis',

'exif-unknowndate' => 'Kuupäev teadmata',

'exif-orientation-1' => 'Normaalne',
'exif-orientation-2' => 'Pööratud pikali',
'exif-orientation-3' => 'Pööratud 180°',
'exif-orientation-4' => 'Pööratud püsti',
'exif-orientation-5' => 'Pööratud 90° vastupäeva ja püstselt ümberpööratud',
'exif-orientation-6' => 'Pööratud 90° vastupäeva',
'exif-orientation-7' => 'Pööratud 90° päripäeva ja püstselt ümberpööratud',
'exif-orientation-8' => 'Pööratud 90° päripäeva',

'exif-planarconfiguration-2' => 'tasapinnaline vorm',

'exif-colorspace-65535' => 'Kalibreerimata',

'exif-componentsconfiguration-0' => 'ei ole',

'exif-exposureprogram-0' => 'Määratlemata',
'exif-exposureprogram-1' => 'Manuaalne',
'exif-exposureprogram-2' => 'Tavaprogramm',
'exif-exposureprogram-3' => 'Ava prioriteet',
'exif-exposureprogram-4' => 'Katiku prioriteet',
'exif-exposureprogram-7' => 'Portree töörežiim (lähifotode jaoks, taust fookusest väljas)',
'exif-exposureprogram-8' => 'Maastiku töörežiim (maastikupiltide jaoks, taust on teravdatud)',

'exif-subjectdistance-value' => '$1 meetrit',

'exif-meteringmode-0' => 'Teadmata',
'exif-meteringmode-1' => 'Keskmine',
'exif-meteringmode-2' => 'Kaalutud keskmine',
'exif-meteringmode-3' => 'Punkt',
'exif-meteringmode-4' => 'Mitmikpunkt',
'exif-meteringmode-5' => 'Muster',
'exif-meteringmode-6' => 'Osaline',
'exif-meteringmode-255' => 'Muu',

'exif-lightsource-0' => 'Teadmata',
'exif-lightsource-1' => 'Päevavalgus',
'exif-lightsource-2' => 'Fluorestsentsvalgus',
'exif-lightsource-3' => 'Hõõglambi valgus',
'exif-lightsource-4' => 'Välk',
'exif-lightsource-9' => 'Hea ilm',
'exif-lightsource-10' => 'Pilvine ilm',
'exif-lightsource-11' => 'Varjus',
'exif-lightsource-12' => 'Päevane fluorestsentsvalgus (D 5700 – 7100K)',
'exif-lightsource-13' => 'Päevavalge fluorestsentsvalgus (N 4600 – 5400K)',
'exif-lightsource-14' => 'Külmvalge fluorestsentsvalgus (W 3900 – 4500K)',
'exif-lightsource-15' => 'Valge fluorestsentsvalgus (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardne valgus A',
'exif-lightsource-18' => 'Standardne valgus B',
'exif-lightsource-19' => 'Standardne valgus C',
'exif-lightsource-24' => 'Stuudio hõõglamp (ISO)',
'exif-lightsource-255' => 'Muu valgusallikas',

# Flash modes
'exif-flash-fired-0' => 'Välk ei töötanud',
'exif-flash-fired-1' => 'Välk töötas',
'exif-flash-return-0' => 'ei ole välgu peegeldumist tuvastavat funktsiooni',
'exif-flash-return-2' => 'välgu peegeldust ei tuvastatud',
'exif-flash-return-3' => 'tuvastati välgu peegeldus',
'exif-flash-mode-1' => 'sundvälk',
'exif-flash-mode-2' => 'välk keelatud',
'exif-flash-mode-3' => 'automaatne töörežiim',
'exif-flash-function-1' => 'Välgu funktsiooni ei ole',
'exif-flash-redeye-1' => 'punasilmsust vähendav režiim',

'exif-focalplaneresolutionunit-2' => 'toll',

'exif-sensingmethod-1' => 'Määramata',
'exif-sensingmethod-2' => 'Ühe-kiibiga värvisensor',
'exif-sensingmethod-3' => 'Kahe-kiibiga värvisensor',
'exif-sensingmethod-4' => 'Kolme-kiibiga värvisensor',
'exif-sensingmethod-7' => 'Kolmerealine sensor',

'exif-filesource-3' => 'Digitaalne fotokaamera',

'exif-customrendered-0' => 'Tavatöötlus',
'exif-customrendered-1' => 'Kohandatud töötlus',

'exif-exposuremode-0' => 'Automaatne säritus',
'exif-exposuremode-1' => 'Manuaalne säritus',
'exif-exposuremode-2' => 'Automaatne särikahvel',

'exif-whitebalance-0' => 'Automaatne valge tasakaal',
'exif-whitebalance-1' => 'Manuaalne valge tasakaal',

'exif-scenecapturetype-0' => 'Standardne',
'exif-scenecapturetype-1' => 'Maastik',
'exif-scenecapturetype-2' => 'Portree',
'exif-scenecapturetype-3' => 'Ööpilt',

'exif-gaincontrol-0' => 'Puudub',
'exif-gaincontrol-1' => 'Natuke tõstetud',
'exif-gaincontrol-2' => 'Suur',
'exif-gaincontrol-3' => 'Natuke vähendatud',
'exif-gaincontrol-4' => 'Vähene',

'exif-contrast-0' => 'Normaalne',
'exif-contrast-1' => 'Nõrk',
'exif-contrast-2' => 'Tugev',

'exif-saturation-0' => 'Normaalne',
'exif-saturation-1' => 'Madal värviküllastus',
'exif-saturation-2' => 'Kõrge värviküllastus',

'exif-sharpness-0' => 'Normaalne',
'exif-sharpness-1' => 'Nõrk',
'exif-sharpness-2' => 'Tugev',

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

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|meeter|meetrit}} üle merepinna',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|meeter|meetrit}} allpool merepinda',

'exif-gpsstatus-a' => 'Mõõtmine pooleli',

'exif-gpsmeasuremode-2' => '2-mõõtmeline ulatus',
'exif-gpsmeasuremode-3' => '3-mõõtmeline ulatus',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilomeetrit tunnis',
'exif-gpsspeed-m' => 'Miili tunnis',
'exif-gpsspeed-n' => 'Sõlme',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilomeetrid',
'exif-gpsdestdistance-m' => 'Miilid',
'exif-gpsdestdistance-n' => 'Meremiilid',

'exif-gpsdop-excellent' => 'Suurepärane ($1)',
'exif-gpsdop-good' => 'Hea ($1)',
'exif-gpsdop-moderate' => 'Keskpärane ($1)',
'exif-gpsdop-fair' => 'Rahuldav ($1)',
'exif-gpsdop-poor' => 'Vilets ($1)',

'exif-objectcycle-a' => 'Ainult hommikuks',
'exif-objectcycle-p' => 'Ainult õhtuks',
'exif-objectcycle-b' => 'Nii hommikuks kui õhtuks',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tegelik suund',
'exif-gpsdirection-m' => 'Magneetiline suund',

'exif-ycbcrpositioning-1' => 'Keskele asetatud',
'exif-ycbcrpositioning-2' => 'Kõrvuti asetatud',

'exif-dc-contributor' => 'Asjaosalised',
'exif-dc-coverage' => 'Teabevahendi ruumiline või ajaline ulatus',
'exif-dc-date' => 'Kuupäevad',
'exif-dc-publisher' => 'Väljaandja',
'exif-dc-relation' => 'Seotud teabevahendid',
'exif-dc-rights' => 'Õigused',
'exif-dc-source' => 'Allikmeediafail',
'exif-dc-type' => 'Meediafaili tüüp',

'exif-rating-rejected' => 'Tagasi lükatud',

'exif-isospeedratings-overflow' => 'Suurem kui 65535',

'exif-iimcategory-ace' => 'Kunstid, kultuur ja meelelahutus',
'exif-iimcategory-clj' => 'Kuritegevus ja õigus',
'exif-iimcategory-dis' => 'Katastroofid ja õnnetused',
'exif-iimcategory-fin' => 'Majandus ja äri',
'exif-iimcategory-edu' => 'Haridus',
'exif-iimcategory-evn' => 'Keskkond',
'exif-iimcategory-hth' => 'Tervis',
'exif-iimcategory-hum' => 'Huviala',
'exif-iimcategory-lab' => 'Töö',
'exif-iimcategory-lif' => 'Eluviis ja vaba aeg',
'exif-iimcategory-pol' => 'Poliitika',
'exif-iimcategory-rel' => 'Religioon ja usk',
'exif-iimcategory-sci' => 'Teadus ja tehnika',
'exif-iimcategory-soi' => 'Sotsiaalküsimused',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Sõjad, konfliktid ja rahutused',
'exif-iimcategory-wea' => 'Ilm',

'exif-urgency-normal' => 'Harilik ($1)',
'exif-urgency-low' => 'Madal ($1)',
'exif-urgency-high' => 'Kõrge ($1)',
'exif-urgency-other' => 'Kasutaja määratud tähtsus ($1)',

# External editor support
'edit-externally' => 'Töötle faili välise programmiga',
'edit-externally-help' => '(Vaata väliste redaktorite [//www.mediawiki.org/wiki/Manual:External_editors kasutusjuhendit])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'Näita kõiki',
'namespacesall' => 'kõik',
'monthsall' => 'kõik',
'limitall' => 'iga',

# E-mail address confirmation
'confirmemail' => 'E-posti aadressi kinnitamine',
'confirmemail_noemail' => 'Sul pole [[Special:Preferences|eelistuste leheküljel]] e-posti aadressi määranud.',
'confirmemail_text' => 'Enne kui saad e-postiga seotud teenuseid kasutada, pead oma e-posti aadressi õigsust kinnitama. Allpool olevat nuppu klõpsates saadetakse sulle e-posti teel kinnituskood. Aadressi kinnitamiseks klõpsa e-kirjas olevat linki.',
'confirmemail_pending' => 'Kinnituskood on juba saadetud. Kui tegid konto hiljuti, oota palun mõni minut selle saabumist, enne kui üritad uuesti.',
'confirmemail_send' => 'Saada kinnituskood',
'confirmemail_sent' => 'Kinnitus-e-kiri saadetud.',
'confirmemail_oncreate' => 'Kinnituskood saadeti e-posti aadressile. See kood ei ole vajalik sisselogimisel, kuid seda on vaja, et kasutada vikis e-postipõhiseid toiminguid.',
'confirmemail_sendfailed' => 'Kinnitus-e-kirja ei õnnestunud saata.
Kontrolli aadressi õigsust.

Veateade e-kirja saatmisel: $1',
'confirmemail_invalid' => 'Vigane kinnituskood, kinnituskood võib olla aegunud.',
'confirmemail_needlogin' => 'Pead oma e-posti aadressi kinnitamiseks $1.',
'confirmemail_success' => 'Sinu e-posti aadress on kinnitatud
Võid nüüd [[Special:UserLogin|sisse logida]].',
'confirmemail_loggedin' => 'Sinu e-posti aadress on nüüd kinnitatud.',
'confirmemail_error' => 'Viga kinnituskoodi salvestamisel.',
'confirmemail_subject' => '{{GRAMMAR:genitive|{{SITENAME}}}} e-posti aadressi kinnitamine',
'confirmemail_body' => 'Keegi IP-aadressilt $1, ilmselt sa ise, registreeris selle e-posti aadressiga {{GRAMMAR:inessive|{{SITENAME}}}} konto "$2".

Kinnitamaks, et see kasutajakonto tõepoolest kuulub sulle ning e-posti teenuste aktiveerimiseks, ava oma võrgulehitsejas järgnev link:

$3

Kui see *pole* sinu loodud konto, ava järgnev link kinnituse tühistamiseks:

$5

Kinnituskood aegub kuupäeval $4.',
'confirmemail_body_changed' => 'Keegi IP-aadressilt $1, ilmselt sa ise,
muutis {{GRAMMAR:inessive|{{SITENAME}}}} konto "$2" e-posti aadressiks selle aadressi.

Kinnitamaks, et see konto tõepoolest kuulub sulle ja e-posti teenuste taasaktiveerimiseks, ava oma veebilehitsejas järgnev link:

$3

Kui see *pole* sinu konto, ava järgnev link
kinnituse tühistamiseks:

$5

Kinnituskood aegub kuupäeval $4.',
'confirmemail_body_set' => 'Keegi, arvatavasti sina ise, IP-aadressilt $1 on {{GRAMMAR:genitive|{{SITENAME}}}} konto "$2" e-posti aadressiks määranud selle aadressi.

Kinnitamaks, et see konto kuulub tõesti sulle ja {{GRAMMAR:genitive|{{SITENAME}}}} e-posti funktsioonide taasaktiveerimiseks ava oma võrgulehitsejas järgmine link:

$3

Kui konto *ei* kuulu sulle, kasuta e-posti aadressi kinnituse tühistamiseks järgmist linki:

$5

Selle kinnituskoodi aegumistähtaeg on $4.',
'confirmemail_invalidated' => 'E-posti aadressi kinnitamine tühistati',
'invalidateemail' => 'E-posti aadressi kinnituse tühistamine',

# Scary transclusion
'scarytranscludetoolong' => '[URL on liiga pikk]',

# Delete conflict
'deletedwhileediting' => "'''Hoiatus''': Sel ajal, kui sina lehekülge redigeerisid, kustutas keegi selle ära!",
'confirmrecreate' => "Kasutaja [[User:$1|$1]] ([[User talk:$1|arutelu]]) kustutas lehekülje sellel ajal, kui sina seda redigeerisid. Põhjus:
: ''$2''
Palun kinnita, et soovid tõesti selle lehekülje taasluua.",
'confirmrecreate-noreason' => 'Kasutaja [[User:$1|$1]] ([[User talk:$1|arutelu]]) kustutas selle lehekülje, pärast seda kui redigeerimist alustasid. Palun kinnita, et tahad tõesti selle lehekülje uuesti luua.',
'recreate' => 'Taasta',

# action=purge
'confirm_purge_button' => 'Sobib',
'confirm-purge-top' => 'Tühjenda selle lehekülje vahemälu?',
'confirm-purge-bottom' => 'Toiming puhastab lehekülje vahemälu ja kuvab uusima versiooni.',

# action=watch/unwatch
'confirm-watch-button' => 'Sobib',
'confirm-watch-top' => 'Kas lisad selle lehekülje oma jälgimisloendisse?',
'confirm-unwatch-button' => 'Sobib',
'confirm-unwatch-top' => 'Kas eemaldad selle lehekülje oma jälgimisloendist?',

# Multipage image navigation
'imgmultipageprev' => '← eelmine lehekülg',
'imgmultipagenext' => 'järgmine lehekülg →',
'imgmultigo' => 'Mine!',
'imgmultigoto' => 'Mine leheküljele $1',

# Table pager
'ascending_abbrev' => 'tõusev',
'descending_abbrev' => 'laskuv',
'table_pager_next' => 'Järgmine lehekülg',
'table_pager_prev' => 'Eelmine lehekülg',
'table_pager_first' => 'Esimene lehekülg',
'table_pager_last' => 'Viimane lehekülg',
'table_pager_limit' => 'Näita leheküljel $1 üksust',
'table_pager_limit_label' => 'Üksusi lehekülje kohta:',
'table_pager_limit_submit' => 'Mine',
'table_pager_empty' => 'Ei ole tulemusi',

# Auto-summaries
'autosumm-blank' => 'Kustutatud kogu lehekülje sisu',
'autosumm-replace' => "Lehekülg asendatud tekstiga '$1'",
'autoredircomment' => 'Ümbersuunamine lehele [[$1]]',
'autosumm-new' => "Uus lehekülg: '$1'",

# Live preview
'livepreview-loading' => 'Laadimine...',
'livepreview-ready' => 'Laadimisel... Valmis!',
'livepreview-failed' => 'Elav eelvaade ebaõnnestus! Proovi normaalset eelvaadet.',
'livepreview-error' => 'Ühendus ebaõnnestus: $1 "$2".
Proovi tavalist eelvaadet.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Viimase {{PLURAL:$1|ühe sekundi|$1 sekundi}} jooksul tehtud muudatused ei pruugi selles loendis näha olla.',
'lag-warn-high' => 'Andmebaasiserveri töö viivituste tõttu ei pruugi viimase {{PLURAL:$1|ühe sekundi|$1 sekundi}} jooksul tehtud muudatused selles loendis näha olla.',

# Watchlist editor
'watchlistedit-numitems' => 'Sinu jälgimisloendis on {{PLURAL:$1|üks lehekülg|$1 lehekülge}}, aruteluleheküljed välja arvatud.',
'watchlistedit-noitems' => 'Sinu jälgimisloend ei sisalda ühtegi lehekülge.',
'watchlistedit-normal-title' => 'Jälgimisloendi redigeerimine',
'watchlistedit-normal-legend' => 'Jälgimisloendist lehtede eemaldamine',
'watchlistedit-normal-explain' => 'Need lehed on sinu jälgimisloendis.
Jälgimisloendist lehtekülgede eemaldamiseks tee vastava lehekülje ees olevasse kastikesse linnuke ja klõpsa nuppu "{{int:Watchlistedit-normal-submit}}". Saad ka jälgimisloendi [[Special:EditWatchlist/raw|algandmeid muuta]].',
'watchlistedit-normal-submit' => 'Eemalda valitud lehed',
'watchlistedit-normal-done' => 'Jälgimisloendist eemaldati {{PLURAL:$1|üks lehekülg|$1 lehekülge}}:',
'watchlistedit-raw-title' => 'Jälgimisloendi algandmed',
'watchlistedit-raw-legend' => 'Redigeeritavad jälgimisloendi algandmed',
'watchlistedit-raw-explain' => 'Sinu jälgimisloendis olevad leheküljed on kuvatud allpool asuvas tekstikastis, kus sa saad neid lisada või eemaldada;
Iga pealkiri asub ise real.
Kui sa oled lõpetanud, kliki nuppu "{{int:Watchlistedit-raw-submit}}".
Sa võid [[Special:EditWatchlist|kasutada ka harilikku tekstiredaktorit]].',
'watchlistedit-raw-titles' => 'Pealkirjad:',
'watchlistedit-raw-submit' => 'Uuenda jälgimisloendit',
'watchlistedit-raw-done' => 'Sinu jälgimisloend on uuendatud.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 lehekülg|$1 lehekülge}} lisatud:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 pealkiri|$1 pealkirja}} eemaldati:',

# Watchlist editing tools
'watchlisttools-view' => 'Näita vastavaid muudatusi',
'watchlisttools-edit' => 'Vaata ja redigeeri jälgimisloendit',
'watchlisttools-raw' => 'Muuda lähteteksti',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|arutelu]])',

# Core parser functions
'unknown_extension_tag' => 'Tundmatu lisa silt "$1".',
'duplicate-defaultsort' => '\'\'\'Hoiatus:\'\'\' Järjestamisvõti "$2" tühistab eespool oleva järjestamisvõtme "$1".',

# Special:Version
'version' => 'Versioon',
'version-extensions' => 'Paigaldatud lisad',
'version-specialpages' => 'Erileheküljed',
'version-parserhooks' => 'Parserihaagid',
'version-variables' => 'Muutujad',
'version-antispam' => 'Rämpsposti tõkestus',
'version-skins' => 'Kujundused',
'version-other' => 'Muu',
'version-mediahandlers' => 'Meediatöötlejad',
'version-hooks' => 'Redaktsioon',
'version-extension-functions' => 'Lisafunktsioonid',
'version-parser-extensiontags' => 'Parseri lisamärgendid',
'version-parser-function-hooks' => 'Parserifunktsioonid',
'version-hook-name' => 'Redaktsiooni nimi',
'version-hook-subscribedby' => 'Tellijad',
'version-version' => '(Versioon $1)',
'version-license' => 'Litsents',
'version-poweredby-credits' => "See viki kasutab '''[//www.mediawiki.org/ MediaWiki]''' tarkvara. Autoriõigus © 2001–$1 $2.",
'version-poweredby-others' => 'teised',
'version-license-info' => "MediaWiki on vaba tarkvara; tohid seda taaslevitada ja/või selle põhjal teisendeid luua vastavalt Vaba Tarkvara Fondi avaldatud GNU Üldise Avaliku Litsentsi versioonis 2 või hilisemas seatud tingimustele.

MediaWiki tarkvara levitatakse lootuses, et see on kasulik, aga '''igasuguse tagatiseta''', ka kaudse tagatiseta teose '''turustatavuse''' või '''müügikõlblikkuse''' kohta. Üksikasjad leiad GNU Üldisest Avalikust Litsentsist.

GNU Üldise Avaliku Litsentsi [{{SERVER}}{{SCRIPTPATH}}/COPYING eksemplar] peaks selle programmiga kaasas olema; kui pole, kirjuta aadressil Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA või [//www.gnu.org/licenses/old-licenses/gpl-2.0.html loe seda võrgust].",
'version-software' => 'Paigaldatud tarkvara',
'version-software-product' => 'Toode',
'version-software-version' => 'Versioon',
'version-entrypoints' => 'Sisendpunktide internetiaadressid',
'version-entrypoints-header-entrypoint' => 'Sisendpunkt',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Failitee',
'filepath-page' => 'Fail:',
'filepath-submit' => 'Mine',
'filepath-summary' => 'See erileht määrab otsitava failini viiva tee.
Pilt kuvatakse algupärases suuruses, muu fail avatakse koheselt seostuva programmiga.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Faili duplikaatide otsimine',
'fileduplicatesearch-summary' => 'Otsi duplikaatfaile nende räsiväärtuse järgi.',
'fileduplicatesearch-legend' => 'Otsi faili duplikaati',
'fileduplicatesearch-filename' => 'Faili nimi:',
'fileduplicatesearch-submit' => 'Otsi',
'fileduplicatesearch-info' => '$1 × $2 pikslit<br />Faili suurus: $3<br />MIME-tüüp: $4',
'fileduplicatesearch-result-1' => 'Failil "$1" ei ole identset duplikaati.',
'fileduplicatesearch-result-n' => 'Failil "$1" on {{PLURAL:$2|1 samane duplikaat|$2 samast duplikaati}}.',
'fileduplicatesearch-noresults' => 'Faili nimega "$1" ei leidu.',

# Special:SpecialPages
'specialpages' => 'Erileheküljed',
'specialpages-note' => '----
* Harilikud erileheküljed
* <span class="mw-specialpagerestricted">Piiranguga erileheküljed</span>
* <span class="mw-specialpagecached">Uuendamata sisuga erileheküljed (ei pruugi enam kasutuses olla)</span>',
'specialpages-group-maintenance' => 'Hooldusaruanded',
'specialpages-group-other' => 'Teised erileheküljed',
'specialpages-group-login' => 'Sisselogimine ja konto loomine',
'specialpages-group-changes' => 'Viimased muudatused ja logid',
'specialpages-group-media' => 'Failidega seonduv',
'specialpages-group-users' => 'Kasutajad ja õigused',
'specialpages-group-highuse' => 'Tihti kasutatud leheküljed',
'specialpages-group-pages' => 'Lehekülgede loendid',
'specialpages-group-pagetools' => 'Töö lehekülgedega',
'specialpages-group-wiki' => 'Viki andmed ja tööriistad',
'specialpages-group-redirects' => 'Ümbersuunavad erilehed',
'specialpages-group-spam' => 'Töö spämmiga',

# Special:BlankPage
'blankpage' => 'Tühi leht',
'intentionallyblankpage' => 'See lehekülg on sihilikult tühjaks jäetud.',

# External image whitelist
'external_image_whitelist' => '  #Jäta see rida muutmata kujule<pre>
#Pane regulaaravaldise osad (vaid //-märkide vahel olev osa) allapoole
#Need on vastavuses vikiväliste piltide internetiaadressidega
#Vastavuses olevad kuvatakse piltidena, muul juhul kuvatakse ainult pildi link
#Märgiga # algavad read on kommentaarid
#See on tõstutundetu

#Pane kõik regulaaravaldise osad selle joone kohale. Jäta see rida muutmata kujule</pre>',

# Special:Tags
'tags' => 'Käibivad muudatusmärgised',
'tag-filter' => '[[Special:Tags|Märgisefilter]]:',
'tag-filter-submit' => 'Filtri',
'tags-title' => 'Märgised',
'tags-intro' => 'See lehekülg loetleb märgised, millega tarkvara võib muudatused märgistada, ja nende kirjeldused.',
'tags-tag' => 'Märgise nimi',
'tags-display-header' => 'Tähistus muudatusloendis',
'tags-description-header' => 'Täiskirjeldus',
'tags-hitcount-header' => 'Märgistatud muudatused',
'tags-edit' => 'muuda',
'tags-hitcount' => '$1 {{PLURAL:$1|muudatus|muudatust}}',

# Special:ComparePages
'comparepages' => 'Lehekülgede kõrvutamine',
'compare-selector' => 'Lehekülje redaktsioonide kõrvutamine',
'compare-page1' => 'Lehekülg 1',
'compare-page2' => 'Lehekülg 2',
'compare-rev1' => 'Redaktsioon&nbsp;1',
'compare-rev2' => 'Redaktsioon&nbsp;2',
'compare-submit' => 'Kõrvuta',
'compare-invalid-title' => 'Valitud pealkiri on vigane.',
'compare-title-not-exists' => 'Valitud pealkirja ei ole.',
'compare-revision-not-exists' => 'Valitud redaktsiooni ei ole.',

# Database error messages
'dberr-header' => 'Selles vikis on probleem',
'dberr-problems' => 'Kahjuks on sellel saidil tehnilisi probleeme',
'dberr-again' => 'Oota mõni hetk ja laadi lehekülg uuesti.',
'dberr-info' => '(Ei saa ühendust andmebaasi serveriga: $1)',
'dberr-usegoogle' => "Proovi vahepeal otsida Google'ist.",
'dberr-outofdate' => "Pane tähele, et Google'is talletatud meie sisu võib olla iganenud.",
'dberr-cachederror' => 'See koopia taotletud leheküljest on vahemälus ja ei pruugi olla ajakohane.',

# HTML forms
'htmlform-invalid-input' => 'Osaga sinu sisestatust on probleeme',
'htmlform-select-badoption' => 'Antud number ei ole kõlbulik.',
'htmlform-int-invalid' => 'Antud väärtus ei ole täisarv.',
'htmlform-float-invalid' => 'Määratud väärtus ei ole arvuline.',
'htmlform-int-toolow' => 'Antud suurus on väiksem kui minimaalne $1',
'htmlform-int-toohigh' => 'Antud suurus on suurem kui maksimaalne $1',
'htmlform-required' => 'See väärtus on nõutav',
'htmlform-submit' => 'Saada',
'htmlform-reset' => 'Tühista muudatused',
'htmlform-selectorother-other' => 'Muu',

# SQLite database support
'sqlite-has-fts' => '$1 koos täistekstiotsingu toega',
'sqlite-no-fts' => '$1 ilma täistekstiotsingu toeta',

# New logging system
'logentry-delete-delete' => '$1 kustutas lehekülje $3',
'logentry-delete-restore' => '$1 taastas lehekülje $3',
'logentry-delete-event' => '$1 muutis leheküljel $3 {{PLURAL:$5|ühe|$5}} logisündmuse nähtavust: $4',
'logentry-delete-revision' => '$1 muutis leheküljel $3 {{PLURAL:$5|ühe|$5}} redaktsiooni nähtavust: $4',
'logentry-delete-event-legacy' => '$1 muutis leheküljel $3 logisündmuste nähtavust',
'logentry-delete-revision-legacy' => '$1 muutis leheküljel $3 redaktsioonide nähtavust',
'logentry-suppress-delete' => '$1 varjas lehekülje $3',
'logentry-suppress-event' => '$1 muutis salaja leheküljel $3 {{PLURAL:$5|ühe|$5}} logisündmuse nähtavust: $4',
'logentry-suppress-revision' => '$1 muutis salaja leheküljel $3 {{PLURAL:$5|ühe|$5}} redaktsiooni nähtavust: $4',
'logentry-suppress-event-legacy' => '$1 muutis salaja leheküljel $3 logisündmuste nähtavust',
'logentry-suppress-revision-legacy' => '$1 muutis salaja leheküljel $3 redaktsioonide nähtavust',
'revdelete-content-hid' => 'peideti sisu',
'revdelete-summary-hid' => 'peideti resümee',
'revdelete-uname-hid' => 'peideti kasutajanimi',
'revdelete-content-unhid' => 'nähtavaks tehti sisu',
'revdelete-summary-unhid' => 'nähtavaks tehti resümee',
'revdelete-uname-unhid' => 'nähtavaks tehti kasutajanimi',
'revdelete-restricted' => 'kehtestati piirangud administraatoritele',
'revdelete-unrestricted' => 'eemaldati administraatoritelt piirangud',
'logentry-move-move' => '$1 teisaldas lehekülje $3 pealkirja $4 alla',
'logentry-move-move-noredirect' => '$1 teisaldas lehekülje $3 pealkirja $4 alla ümbersuunamist maha jätmata',
'logentry-move-move_redir' => '$1 teisaldas lehekülje $3 ümbersuunamise $4 asemele',
'logentry-move-move_redir-noredirect' => '$1 teisaldas lehekülje $3 ümbersuunamise $4 asemele ümbersuunamist maha jätmata',
'logentry-patrol-patrol' => '$1 märkis lehekülje $3 redaktsiooni $4 kontrollituks',
'logentry-patrol-patrol-auto' => '$1 märkis automaatselt lehekülje $3 redaktsiooni $4 kontrollituks',
'logentry-newusers-newusers' => 'Loodud kasutajakonto $1',
'logentry-newusers-create' => 'Loodud kasutajakonto $1',
'logentry-newusers-create2' => '$1 lõi kasutajakonto $3',
'logentry-newusers-autocreate' => 'Konto $1 loodi automaatselt',
'newuserlog-byemail' => 'parool saadetud e-postiga',

# Feedback
'feedback-bugornote' => 'Kui arvad, et oskad tehnilist probleemi üksikasjalikult kirjeldada, [$1 teata veast].
Kui ei, kasuta allolevat lihtsat vormi. Sinu kommentaar lisatakse koos kasutajanime ja teabega kasutatava võrgulehitsejaga kohta leheküljele "[$3 $2]".',
'feedback-subject' => 'Teema:',
'feedback-message' => 'Sõnum:',
'feedback-cancel' => 'Loobu',
'feedback-submit' => 'Saada tagasiside',
'feedback-adding' => 'Tagasiside lisamine leheküljele...',
'feedback-error1' => 'Tõrge: Tundmatu API tulemus',
'feedback-error2' => 'Tõrge: Redigeerimine ebaõnnestus',
'feedback-error3' => 'Tõrge: API ei vasta',
'feedback-thanks' => 'Aitäh! Sinu tagasiside on postitatud leheküljele "[$2 $1]".',
'feedback-close' => 'Valmis',
'feedback-bugcheck' => 'Hästi! Kontrolli vaid, ega tegu pole juba [$1 teada oleva veaga].',
'feedback-bugnew' => 'Kontrollisin. Teata uuest veast',

# Search suggestions
'searchsuggest-search' => 'Otsi',
'searchsuggest-containing' => 'sisalduv...',

# API errors
'api-error-badaccess-groups' => 'Sul pole selles vikis üleslaadimisõigust.',
'api-error-badtoken' => 'Sisemine tõrge: Sobimatu nimi.',
'api-error-copyuploaddisabled' => 'URLi kaudu üleslaadimine on selles serveris keelatud.',
'api-error-duplicate' => 'Siin on {{PLURAL:$1|[$2 teine samasisuline fail]|[$2 mõned teised samasisulised failid]}} juba olemas.',
'api-error-duplicate-archive' => 'Siin {{PLURAL:$1|on [$2 teine samasisuline fail]|olid [$2 mõned teised samasisulised failid]}} juba olemas, aga {{PLURAL:$1|see|need}} kustutati.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Duplikaatfail|Duplikaatfailid}}, mis on juba kustutatud',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Duplikaatfail|Duplikaatfailid}}',
'api-error-empty-file' => 'Üleslaaditav fail on tühi.',
'api-error-emptypage' => 'Uute tühjade lehekülgede loomine pole lubatud.',
'api-error-fetchfileerror' => 'Sisemine tõrge: Midagi läks faili kättesaamisel valesti.',
'api-error-fileexists-forbidden' => 'Fail nimega "$1" on juba olemas ja seda ei saa üle kirjutada.',
'api-error-fileexists-shared-forbidden' => 'Fail nimega "$1" on jagatud failivaramus juba olemas ja seda ei saa üle kirjutada.',
'api-error-file-too-large' => 'Üleslaaditav fail on liiga suur.',
'api-error-filename-tooshort' => 'Failinimi on liiga lühike.',
'api-error-filetype-banned' => 'Antud failitüüp on keelatud.',
'api-error-filetype-banned-type' => '$1 pole lubatud {{PLURAL:$4|failitüüp|failitüübid}}.  Lubatud {{PLURAL:$3|failitüüp|failitüübid}} on  $2.',
'api-error-filetype-missing' => 'Failinime tagant puudub laiend.',
'api-error-hookaborted' => 'Tarkvaralisa katkestas muudatuse tegemise.',
'api-error-http' => 'Sisetõrge: Serveriga pole võimalik ühendust luua.',
'api-error-illegal-filename' => 'Failinimi pole lubatud.',
'api-error-internal-error' => 'Sisetõrge: Sinu faili vikisse üleslaadimise juures läks midagi valesti.',
'api-error-invalid-file-key' => 'Sisetõrge: Faili ei leitud ajutisest mälust.',
'api-error-missingparam' => 'Sisetõrge: Päringust puudub mõni parameeter.',
'api-error-missingresult' => 'Sisetõrge: Ei õnnestu kindlaks teha, kas kopeerimine õnnestus.',
'api-error-mustbeloggedin' => 'Failide üleslaadimiseks pead sisse logima.',
'api-error-mustbeposted' => 'Sisetõrge: Päring tuleb sooritada, kasutades HTTP POST-meetodit.',
'api-error-noimageinfo' => 'Üleslaadimine õnnestus, aga server ei andnud faili kohta mingit teavet.',
'api-error-nomodule' => 'Sisetõrge: Üleslaadimismoodul on määramata.',
'api-error-ok-but-empty' => 'Sisetõrge: Server ei vasta.',
'api-error-overwrite' => 'Olemasolevate failide ülekirjutamine pole lubatud.',
'api-error-stashfailed' => 'Sisetõrge: Serveril ei õnnestunud ajutist faili talletada.',
'api-error-timeout' => 'Server ei vastanud oodatud aja sees.',
'api-error-unclassified' => 'Ilmnes teadmata tõrge.',
'api-error-unknown-code' => 'Teadmata tõrge: "$1"',
'api-error-unknown-error' => 'Sisetõrge: Sellal kui sinu faili üritati üles laadida, läks midagi valesti.',
'api-error-unknown-warning' => 'Teadmata hoiatus: $1',
'api-error-unknownerror' => 'Tundmatu tõrge: "$1".',
'api-error-uploaddisabled' => 'Üleslaadimine on selles vikis keelatud.',
'api-error-verification-error' => 'See fail võib olla rikutud või vale laiendiga.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekundi}}',
'duration-minutes' => '$1 {{PLURAL:$1|minuti}}',
'duration-hours' => '$1 {{PLURAL:$1|tunni}}',
'duration-days' => '$1 {{PLURAL:$1|päeva}}',
'duration-weeks' => '$1 {{PLURAL:$1|nädala}}',
'duration-years' => '$1 {{PLURAL:$1|aasta}}',
'duration-decades' => '$1 {{PLURAL:$1|kümnendi}}',
'duration-centuries' => '$1 {{PLURAL:$1|sajandi}}',
'duration-millennia' => '$1 {{PLURAL:$1|aastatuhande}}',

);
