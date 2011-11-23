<?php
/** Serbian (Latin script) (‪Srpski (latinica)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author FriedrickMILBarbarossa
 * @author Liangent
 * @author Meno25
 * @author Michaello
 * @author Rancher
 * @author Red Baron
 * @author Reedy
 * @author Slaven Kosanovic
 * @author Жељко Тодоровић
 * @author Михајло Анђелковић
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_sa_korisnikom',
	NS_PROJECT_TALK     => 'Razgovor_o_$1',
	NS_FILE             => 'Slika',
	NS_FILE_TALK        => 'Razgovor_o_slici',
	NS_MEDIAWIKI        => 'MedijaViki',
	NS_MEDIAWIKI_TALK   => 'Razgovor_o_MedijaVikiju',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

# Aliases to cyrillic namespaces
$namespaceAliases = array(
	"Медија"                  => NS_MEDIA,
	"Посебно"                 => NS_SPECIAL,
	"Разговор"                => NS_TALK,
	"Корисник"                => NS_USER,
	"Разговор_са_корисником"  => NS_USER_TALK,
	"Разговор_о_$1"           => NS_PROJECT_TALK,
	"Слика"                   => NS_FILE,
	"Разговор_о_слици"        => NS_FILE_TALK,
	"МедијаВики"              => NS_MEDIAWIKI,
	"Разговор_о_МедијаВикију" => NS_MEDIAWIKI_TALK,
	'Шаблон'                  => NS_TEMPLATE,
	'Разговор_о_шаблону'      => NS_TEMPLATE_TALK,
	'Помоћ'                   => NS_HELP,
	'Разговор_о_помоћи'       => NS_HELP_TALK,
	'Категорија'              => NS_CATEGORY,
	'Разговор_о_категорији'   => NS_CATEGORY_TALK,
);

$extraUserToggles = array(
	'nolangconversion',
);

$datePreferenceMigrationMap = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$datePreferences = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$defaultDateFormat = 'hh:mm d. month y.';

$dateFormats = array(
	/*
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
	 */

	'hh:mm d. month y. time'    => 'H:i',
	'hh:mm d month y time'      => 'H:i',
	'hh:mm dd.mm.yyyy time'     => 'H:i',
	'hh:mm d.m.yyyy time'       => 'H:i',
	'hh:mm d. mon y. time'      => 'H:i',
	'hh:mm d mon y time'        => 'H:i',
	'h:mm d. month y. time'     => 'G:i',
	'h:mm d month y time'       => 'G:i',
	'h:mm dd.mm.yyyy time'      => 'G:i',
	'h:mm d.m.yyyy time'        => 'G:i',
	'h:mm d. mon y. time'       => 'G:i',
	'h:mm d mon y time'         => 'G:i',

	'hh:mm d. month y. date'    => 'j. F Y.',
	'hh:mm d month y date'      => 'j F Y',
	'hh:mm dd.mm.yyyy date'     => 'd.m.Y',
	'hh:mm d.m.yyyy date'       => 'j.n.Y',
	'hh:mm d. mon y. date'      => 'j. M Y.',
	'hh:mm d mon y date'        => 'j M Y',
	'h:mm d. month y. date'     => 'j. F Y.',
	'h:mm d month y date'       => 'j F Y',
	'h:mm dd.mm.yyyy date'      => 'd.m.Y',
	'h:mm d.m.yyyy date'        => 'j.n.Y',
	'h:mm d. mon y. date'       => 'j. M Y.',
	'h:mm d mon y date'         => 'j M Y',

	'hh:mm d. month y. both'    => 'H:i, j. F Y.',
	'hh:mm d month y both'      => 'H:i, j F Y',
	'hh:mm dd.mm.yyyy both'     => 'H:i, d.m.Y',
	'hh:mm d.m.yyyy both'       => 'H:i, j.n.Y',
	'hh:mm d. mon y. both'      => 'H:i, j. M Y.',
	'hh:mm d mon y both'        => 'H:i, j M Y',
	'h:mm d. month y. both'     => 'G:i, j. F Y.',
	'h:mm d month y both'       => 'G:i, j F Y',
	'h:mm dd.mm.yyyy both'      => 'G:i, d.m.Y',
	'h:mm d.m.yyyy both'        => 'G:i, j.n.Y',
	'h:mm d. mon y. both'       => 'G:i, j. M Y.',
	'h:mm d mon y both'         => 'G:i, j M Y',
);


/* NOT USED IN STABLE VERSION */
$magicWords = array(
	'redirect'              => array( '0', '#Preusmeri', '#preusmeri', '#PREUSMERI', '#REDIRECT' ),
	'notoc'                 => array( '0', '__BEZSADRŽAJA__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__FORSIRANISADRŽAJ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__SADRŽAJ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BEZ_IZMENA__', '__BEZIZMENA__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'TRENUTNIMESEC', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'TRENUTNIMESECIME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'TRENUTNIMESECGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'TRENUTNIMESECSKR', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'TRENUTNIDAN', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'TRENUTNIDANIME', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'TRENUTNAGODINA', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'TRENUTNOVREME', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'BROJČLANAKA', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'BROJDATOTEKA', 'BROJFAJLOVA', 'NUMBEROFFILES' ),
	'pagename'              => array( '1', 'STRANICA', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'STRANICE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'IMENSKIPROSTOR', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'IMENSKIPROSTORI', 'NAMESPACEE' ),
	'fullpagename'          => array( '1', 'PUNOIMESTRANE', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'PUNOIMESTRANEE', 'FULLPAGENAMEE' ),
	'msg'                   => array( '0', 'POR:', 'MSG:' ),
	'subst'                 => array( '0', 'ZAMENI:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'NVPOR:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'desno', 'd', 'right' ),
	'img_left'              => array( '1', 'levo', 'l', 'left' ),
	'img_none'              => array( '1', 'n', 'bez', 'none' ),
	'img_width'             => array( '1', '$1piskel', '$1p', '$1px' ),
	'img_center'            => array( '1', 'centar', 'c', 'center', 'centre' ),
	'img_framed'            => array( '1', 'okvir', 'ram', 'framed', 'enframed', 'frame' ),
	'sitename'              => array( '1', 'IMESAJTA', 'SITENAME' ),
	'ns'                    => array( '0', 'IP:', 'NS:' ),
	'localurl'              => array( '0', 'LOKALNAADRESA:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'LOKALNEADRESE:', 'LOCALURLE:' ),
	'servername'            => array( '0', 'IMESERVERA', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'SKRIPTA', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMATIKA:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__БЕЗКН__', '__BEZKN__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__BEZCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'TRENUTNANEDELJA', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'TRENUTNIDOV', 'CURRENTDOW' ),
	'revisionid'            => array( '1', 'IDREVIZIJE', 'REVISIONID' ),
	'plural'                => array( '0', 'MNOŽINA:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'PUNURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'PUNURLE:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'LCPRVI:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'UCPRVI:', 'UCFIRST:' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Podvuci veze',
'tog-highlightbroken'         => 'Formatiraj pokvarene veze <a href="" class="new">ovako</a> (alternativa: ovako<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Uravnaj pasuse',
'tog-hideminor'               => 'Sakrij male izmene u spisku skorašnjih izmena',
'tog-hidepatrolled'           => 'Sakrij patrolirane izmene u skorašnjim izmenama',
'tog-newpageshidepatrolled'   => 'Sakrij patrolirane stranice sa spiska novih stranica',
'tog-extendwatchlist'         => 'Proširuje spisak nadgledanja tako da pokazuje sve izmene, ne samo najsvežije',
'tog-usenewrc'                => 'Koristi poboljšan spisak skorašnjih izmena (zahteva JavaScript)',
'tog-numberheadings'          => 'Automatski numeriši podnaslove',
'tog-showtoolbar'             => 'Prikaži dugmiće za izmene (zahteva JavaScript)',
'tog-editondblclick'          => 'Menjaj stranice dvostrukim klikom (zahteva JavaScript)',
'tog-editsection'             => 'Omogući izmenu delova [uredi] vezama',
'tog-editsectiononrightclick' => 'Omogući izmenu delova desnim klikom<br />na njihove naslove (zahteva JavaScript)',
'tog-showtoc'                 => 'Prikaži sadržaj (u člancima sa više od 3 podnaslova)',
'tog-rememberpassword'        => 'Zapamti me na ovom pregledaču (najduže $1 {{PLURAL:$1|dan|dana|dana}})',
'tog-watchcreations'          => 'Dodaj stranice koje pravim u moj spisak nadgledanja',
'tog-watchdefault'            => 'Dodaj stranice koje menjam u moj spisak nadgledanja',
'tog-watchmoves'              => 'Dodaj stranice koje premeštam u moj spisak nadgledanja',
'tog-watchdeletion'           => 'Dodaj stranice koje brišem u moj spisak nadgledanja',
'tog-minordefault'            => 'Označi sve izmene malim isprva',
'tog-previewontop'            => 'Prikaži pretpregled pre polja za izmenu',
'tog-previewonfirst'          => 'Prikaži pretpregled pri prvoj izmeni',
'tog-nocache'                 => 'Onemogući privremeno memorisanje stranica',
'tog-enotifwatchlistpages'    => 'Pošalji mi imejl kada se promeni stranica koju nadgledam',
'tog-enotifusertalkpages'     => 'Pošalji mi imejl kada se promeni moja stranica za razgovor',
'tog-enotifminoredits'        => 'Pošalji mi imejl i za manje izmene',
'tog-enotifrevealaddr'        => 'Otkrij adresu moje e-pošte u pošti obaveštenja',
'tog-shownumberswatching'     => 'Prikaži broj korisnika koji nadgledaju',
'tog-oldsig'                  => 'Postojeći potpis:',
'tog-fancysig'                => 'Čist potpis (bez automatskih veza)',
'tog-externaleditor'          => 'Uvek koristi spoljni uređivač (samo za naprednije korisnike, potrebne su posebne postavke na računaru).',
'tog-externaldiff'            => 'Uvek koristi spoljni program za upoređivanje (samo za naprednije korisnike, potrebne su posebne postavke na računaru).',
'tog-showjumplinks'           => 'Omogući "skoči na" veze',
'tog-uselivepreview'          => 'Koristi živi pretpregled (zahteva JavaScript) (eksperimentalno)',
'tog-forceeditsummary'        => 'Upozori me kad ne unesem opis izmene',
'tog-watchlisthideown'        => 'Sakrij moje izmene sa spiska nadgledanja',
'tog-watchlisthidebots'       => 'Sakrij izmene botova sa spiska nadgledanja',
'tog-watchlisthideminor'      => 'Sakrij male izmene sa spiska nadgledanja',
'tog-watchlisthideliu'        => 'Sakrij izmene prijavljenih korisnika sa spiska nadgledanja',
'tog-watchlisthideanons'      => 'Sakrij izmene anonimnih korisnika sa spiska nadgledanja',
'tog-watchlisthidepatrolled'  => 'Sakrij patrolirane izmene sa spiska nadgledanja',
'tog-nolangconversion'        => 'Isključi konverziju varijanti',
'tog-ccmeonemails'            => 'Pošalji mi kopije imejlova koje šaljem drugim korisnicima',
'tog-diffonly'                => 'Ne prikazuj sadržaj stranice ispod razlike stranice',
'tog-showhiddencats'          => 'Prikaži skrivene kategorije',
'tog-norollbackdiff'          => 'Sakrij razlike verzija nakon vraćanja',

'underline-always'  => 'Uvek',
'underline-never'   => 'Nikad',
'underline-default' => 'Po podešavanjima brauzera',

# Font style option in Special:Preferences
'editfont-style'     => 'Izmeni stil fonta za ovaj dio:',
'editfont-default'   => 'Podrazumevan iz brauzera',
'editfont-monospace' => 'Font sa jednakim razmacima',
'editfont-sansserif' => 'Sans-serif font',
'editfont-serif'     => 'Serif font',

# Dates
'sunday'        => 'nedelja',
'monday'        => 'ponedeljak',
'tuesday'       => 'utorak',
'wednesday'     => 'sreda',
'thursday'      => 'četvrtak',
'friday'        => 'petak',
'saturday'      => 'subota',
'sun'           => 'ned',
'mon'           => 'pon',
'tue'           => 'uto',
'wed'           => 'sre',
'thu'           => 'čet',
'fri'           => 'pet',
'sat'           => 'sub',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'mart',
'april'         => 'april',
'may_long'      => 'maj',
'june'          => 'jun',
'july'          => 'jul',
'august'        => 'avgust',
'september'     => 'septembar',
'october'       => 'oktobar',
'november'      => 'novembar',
'december'      => 'decembar',
'january-gen'   => 'januara',
'february-gen'  => 'februara',
'march-gen'     => 'marta',
'april-gen'     => 'aprila',
'may-gen'       => 'maja',
'june-gen'      => 'juna',
'july-gen'      => 'jula',
'august-gen'    => 'avgusta',
'september-gen' => 'septembra',
'october-gen'   => 'oktobra',
'november-gen'  => 'novembra',
'december-gen'  => 'decembra',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maj',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'avg',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategorije|Kategorije}} stranica',
'category_header'                => 'Članaka u kategoriji "$1"',
'subcategories'                  => 'Potkategorije',
'category-media-header'          => 'Medija u kategoriji "$1"',
'category-empty'                 => "''Ova kategorija trenutno nema stranica ili medija.''",
'hidden-categories'              => '{{PLURAL:$1|Skrivena kategorija|Skrivene kategorije}}',
'hidden-category-category'       => 'Skrivene kategorije',
'category-subcat-count'          => '{{PLURAL:$2|Ova kategorija sadrži samo sledeću kategoriju.|Ova kategorija sadrži {{PLURAL:$1|potkategoriju|$1 potkategorije}}, od $2 ukupno.}}',
'category-subcat-count-limited'  => 'Ova kategorija sadrži {{PLURAL:$1|sledeću potkategoriju|$1 sledeće potkategorije}}.',
'category-article-count'         => '{{PLURAL:$2|Ova kategorija sadrži samo sledeću stranicu.|{{PLURAL:$1|stranica je|$1 stranice su|$1 stranica je}} u ovoj kategoriji od ukupno $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Sledeća stranica je|$1 sledeće stranice su|$1 sledećih stranica je}} u ovoj kategoriji.',
'category-file-count'            => '{{PLURAL:$2|Ova kategorija sadrži samo sledeći fajl.|{{PLURAL:$1|Sledeći fajl je|$1 sledeća fajla su|$1 sledećih fajlova su}} u ovoj kategoriji, od ukupno $2.}}',
'category-file-count-limited'    => 'Sledeći {{PLURAL:$1|fajl je|$1 fajlovi su}} u ovoj kategoriji.',
'listingcontinuesabbrev'         => 'nast.',
'index-category'                 => 'Indeksirane stranice',
'noindex-category'               => 'Neindeksirane stranice',
'broken-file-category'           => 'Stranice s neispravnim vezama do datoteka',

'about'         => 'O...',
'article'       => 'Članak',
'newwindow'     => '(novi prozor)',
'cancel'        => 'Otkaži',
'moredotdotdot' => 'Još...',
'mypage'        => 'Moja stranica',
'mytalk'        => 'Moj razgovor',
'anontalk'      => 'Razgovor za ovu IP adresu',
'navigation'    => 'Navigacija',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Pronađi',
'qbbrowse'       => 'Prelistavaj',
'qbedit'         => 'Izmeni',
'qbpageoptions'  => 'Opcije stranice',
'qbpageinfo'     => 'Sadržaj stranice',
'qbmyoptions'    => 'Moje opcije',
'qbspecialpages' => 'Posebne stranice',
'faq'            => 'NPP',
'faqpage'        => 'Project:NPP',

# Vector skin
'vector-action-addsection'       => '+',
'vector-action-delete'           => 'Obriši',
'vector-action-move'             => 'Premesti',
'vector-action-protect'          => 'Zaštiti',
'vector-action-undelete'         => 'Vrati',
'vector-action-unprotect'        => 'Promeni zaštitu',
'vector-simplesearch-preference' => 'Prošireni predlozi za pretragu (samo za temu Vektor)',
'vector-view-create'             => 'Napravi',
'vector-view-edit'               => 'Uredi',
'vector-view-history'            => 'Istorija',
'vector-view-view'               => 'Čitaj',
'vector-view-viewsource'         => 'Izvornik',
'actions'                        => 'Akcije',
'namespaces'                     => 'Imenski prostori',
'variants'                       => 'Varijante',

'errorpagetitle'    => 'Greška',
'returnto'          => 'Povratak na $1.',
'tagline'           => 'Iz projekta {{SITENAME}}',
'help'              => 'Pomoć',
'search'            => 'pretraga',
'searchbutton'      => 'Traži',
'go'                => 'Idi',
'searcharticle'     => 'Idi',
'history'           => 'Istorija stranice',
'history_short'     => 'istorija',
'updatedmarker'     => 'ažurirano od moje poslednje posete',
'printableversion'  => 'Verzija za štampu',
'permalink'         => 'Permalink',
'print'             => 'Štampa',
'view'              => 'Pogledaj',
'edit'              => 'Uredi',
'create'            => 'Napravi',
'editthispage'      => 'Uredi ovu stranicu',
'create-this-page'  => 'Napravi ovu stranicu',
'delete'            => 'obriši',
'deletethispage'    => 'Obriši ovu stranicu',
'undelete_short'    => 'vrati {{PLURAL:$1|jednu obrisanu izmenu|$1 obrisane izmene|$1 obrisanih izmena}}',
'viewdeleted_short' => 'Pogledaj {{PLURAL:$1|obrisanu izmenu|$1 obrisane izmene|$1 obrisanih izmena}}',
'protect'           => 'zaštiti',
'protect_change'    => 'izmeni',
'protectthispage'   => 'Zaštiti ovu stranicu',
'unprotect'         => 'Promeni zaštitu',
'unprotectthispage' => 'Promeni zaštitu ove stranice',
'newpage'           => 'Nova stranica',
'talkpage'          => 'Razgovor o ovoj stranici',
'talkpagelinktext'  => 'razgovor',
'specialpage'       => 'Posebna stranica',
'personaltools'     => 'Lični alati',
'postcomment'       => 'Novi odeljak',
'articlepage'       => 'Pogledaj članak',
'talk'              => 'Razgovor',
'views'             => 'Pregledi',
'toolbox'           => 'alati',
'userpage'          => 'Pogledaj korisničku stranu',
'projectpage'       => 'Pogledaj stranu projekta',
'imagepage'         => 'Pogledaj stranicu fajla',
'mediawikipage'     => 'Pogledaj stranicu poruke',
'templatepage'      => 'Vidi stranicu šablona',
'viewhelppage'      => 'Vidi stranicu pomoći',
'categorypage'      => 'Vidi stranicu kategorije',
'viewtalkpage'      => 'Pogledaj razgovor',
'otherlanguages'    => 'Ostali jezici',
'redirectedfrom'    => '(Preusmereno sa $1)',
'redirectpagesub'   => 'Preusmerenje',
'lastmodifiedat'    => 'Ova stranica je poslednji put izmenjena $2, $1.',
'viewcount'         => 'Ovoj stranici je pristupljeno {{PLURAL:$1|jednom|$1 puta|$1 puta}}.',
'protectedpage'     => 'Zaštićena stranica',
'jumpto'            => 'Idi na:',
'jumptonavigation'  => 'navigacija',
'jumptosearch'      => 'pretraga',
'view-pool-error'   => 'Žao nam je, serveri su trenutno prezauzeti.
Previše korisnika pokušava da pristupi ovoj stranici.
Molimo vas da sačekate neko vrijeme prije nego pokušate opet da joj pristupite.

$1',
'pool-timeout'      => 'Istek vremena čeka na zaključavanje',
'pool-queuefull'    => 'Skup procesa je pun',
'pool-errorunknown' => 'Nepoznata greška',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O projektu {{SITENAME}}',
'aboutpage'            => 'Project:O',
'copyright'            => 'Sadržaj je objavljen pod $1.',
'copyrightpage'        => '{{ns:project}}:Autorska prava',
'currentevents'        => 'Aktuelnosti',
'currentevents-url'    => 'Project:Trenutni događaji',
'disclaimers'          => 'Odricanje odgovornosti',
'disclaimerpage'       => 'Project:Odricanje odgovornosti',
'edithelp'             => 'Pomoć oko uređivanja',
'edithelppage'         => 'Help:Uređivanje',
'helppage'             => 'Help:Sadržaj',
'mainpage'             => 'Glavna strana',
'mainpage-description' => 'Glavna strana',
'policy-url'           => 'Project:Politika privatnosti',
'portal'               => 'Radionica',
'portal-url'           => 'Project:Radionica',
'privacy'              => 'Politika privatnosti',
'privacypage'          => 'Project:Politika privatnosti',

'badaccess'        => 'Greška u dozvolama',
'badaccess-group0' => 'Nije vam dozvoljeno da izvršite akciju koju ste pokrenuli.',
'badaccess-groups' => 'Akcija koju ste pokrenuli je rezervisana za korisnike iz {{PLURAL:$2|grupe|iz jedne od grupa}}: $1.',

'versionrequired'     => 'Verzija $1 MedijaVikija je potrebna',
'versionrequiredtext' => 'Potrebno je izdanje $1 Medijavikija da biste koristili ovu stranicu.
Pogledajte stranicu za [[Special:Version|izdanje]].',

'ok'                      => 'U redu',
'retrievedfrom'           => 'Dobavljeno iz "$1"',
'youhavenewmessages'      => 'Imate $1 ($2).',
'newmessageslink'         => 'novih poruka',
'newmessagesdifflink'     => 'najsvežije izmene',
'youhavenewmessagesmulti' => 'Imate novih poruka na $1',
'editsection'             => 'uredi',
'editold'                 => 'uredi',
'viewsourceold'           => 'izvornik',
'editlink'                => 'uredi',
'viewsourcelink'          => 'Izvornik',
'editsectionhint'         => 'Uredi deo: $1',
'toc'                     => 'Sadržaj',
'showtoc'                 => 'prikaži',
'hidetoc'                 => 'sakrij',
'collapsible-collapse'    => 'Skupi',
'collapsible-expand'      => 'Proširi',
'thisisdeleted'           => 'Pogledaj ili vrati $1?',
'viewdeleted'             => 'Pogledaj $1?',
'restorelink'             => '{{PLURAL:$1|jedna obrisana izmena|$1 obrisane izmene|$1 obrisanih izmena}}',
'feedlinks'               => 'Fid:',
'feed-invalid'            => 'Loš tip fida prijave.',
'feed-unavailable'        => 'Fidovi nisu dostupni',
'site-rss-feed'           => '$1 RSS fid',
'site-atom-feed'          => '$1 Atom fid',
'page-rss-feed'           => '"$1" RSS fid',
'page-atom-feed'          => '"$1" Atom fid',
'feed-atom'               => 'Atom',
'red-link-title'          => '$1 (stranica ne postoji)',
'sort-descending'         => 'Poređaj opadajuće',
'sort-ascending'          => 'Poređaj rastuće',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Članak',
'nstab-user'      => '{{GENDER:{{BASEPAGENAME}}|Korisnik|Korisnica}}',
'nstab-media'     => 'Medij',
'nstab-special'   => 'Posebno',
'nstab-project'   => 'Projekat',
'nstab-image'     => 'Datoteka',
'nstab-mediawiki' => 'Poruka',
'nstab-template'  => 'Šablon',
'nstab-help'      => 'Pomoć',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Nema takve akcije',
'nosuchactiontext'  => 'Radnja navedena u adresi nije ispravna.
Možda ste pogrešno napisali adresu ili ste pratili zastarelu vezu.
Moguće je i da se radi o grešci u softveru vikija.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nospecialpagetext' => '<strong>Tražili ste nepostojeću posebnu stranicu.</strong>

Spisak svih posebnih stranica se može naći na [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Greška',
'databaseerror'        => 'Greška u bazi',
'dberrortext'          => 'Došlo je do sintaksne greške u bazi.
Ovo može da označi bag u softveru.
Poslednji poslati upit bazi bio je:
<blockquote><tt>$1</tt></blockquote>
unutar funkcije "<tt>$2</tt>".
Baza podataka je vratila grešku "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Došlo je do sintaksne greške u bazi.
Poslednji poslati upit bazi bio je:
"$1"
unutar funkcije "$2".
Baza podataka je vratila grešku "$3: $4"',
'laggedslavemode'      => "'''Upozorenje:''' Moguće je da stranica nije skoro ažurirana.",
'readonly'             => 'Baza je zaključana',
'enterlockreason'      => 'Unesite razlog za zaključavanje, uključujući procenu
vremena otključavanja',
'readonlytext'         => 'Baza podataka je trenutno zaključana za nove
unose i ostale izmene, verovatno zbog rutinskog održavanja,
posle čega će biti vraćena u uobičajeno stanje.
Administrator koji ju je zaključao dao je ovo objašnjenje: $1',
'missing-article'      => 'Tekst strane pod imenom "$1" ($2) nije pronađen.

Uzrok za ovu grešku je obično zastareli diff ili veza ka obrisanoj verziji članka.

Ako to nije slučaj, možda ste pronašli bag u softveru.
U tom slučaju, prijavite grešku [[Special:ListUsers/sysop|administratoru]] uz odgovarajući link.',
'missingarticle-rev'   => '(revizija#: $1)',
'missingarticle-diff'  => '(Raz: $1, $2)',
'readonly_lag'         => 'Baza podataka je automatski zaključana dok slejv serveri ne sustignu master',
'internalerror'        => 'Interna greška',
'internalerror_info'   => 'Interna greška: $1',
'fileappenderrorread'  => 'Nije bilo moguće pročitati "$1" za vreme ažuriranja.',
'fileappenderror'      => 'Nije bilo moguće ažurirati "$1" na "$2".',
'filecopyerror'        => 'Ne mogu da iskopiram fajl "$1" na "$2".',
'filerenameerror'      => 'Ne mogu da preimenujem fajl "$1" u "$2".',
'filedeleteerror'      => 'Ne mogu da obrišem fajl "$1".',
'directorycreateerror' => 'Ne mogu da napravim direktorijum "$1".',
'filenotfound'         => 'Ne mogu da nađem fajl "$1".',
'fileexistserror'      => 'Ne mogu da pišem po fajlu &quot;$1&quot;: fajl postoji',
'unexpected'           => 'Neočekivana vrednost: "$1"="$2".',
'formerror'            => 'Greška: ne mogu da pošaljem upitnik',
'badarticleerror'      => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'cannotdelete'         => 'Ova strana ili fajl nije mogla biti obrisana: "$1".
Moguće je da ju je neko već obrisao.',
'cannotdelete-title'   => 'Ne mogu da obrišem stranicu „$1“',
'badtitle'             => 'Loš naslov',
'badtitletext'         => 'Zahtevani naslov stranice je bio neispravan, prazan ili
neispravno povezan međujezički ili interviki naslov. Možda sadrži jedan ili više karaktera koji ne mogu da se upotrebljavaju u naslovima.',
'perfcached'           => 'Sledeći podaci su keširani i ne moraju biti u potpunosti ažurirani.',
'perfcachedts'         => 'Sledeći podaci su keširani i poslednji put su ažurirani: $1',
'querypage-no-updates' => 'Ažuriranje ove stranice je trenutno onemogućeno. Podaci ovde neće biti osveženi odmah.',
'wrong_wfQuery_params' => 'Netačni parametri za wfQuery()<br />
Funkcija: $1<br />
Pretraga: $2',
'viewsource'           => 'pogledaj kod',
'viewsource-title'     => 'Prikaz izvora stranice $1',
'actionthrottled'      => 'Akcija je usporena',
'actionthrottledtext'  => 'U cilju borbe protiv spama, niste u mogućnosti da učinite to više puta u kratkom vremenu, a upravo ste prešli to ograničenje. Pokušajte ponovo za par minuta.',
'protectedpagetext'    => 'Ova stranica je zaključana kako se ne bi vršile izmene na njoj.',
'viewsourcetext'       => 'Možete da pregledate i kopirate sadržaj ove stranice:',
'viewyourtext'         => "Možete da pogledate i umnožite izvor '''vaših izmena''' na ovoj stranici:",
'protectedinterface'   => 'Ova stranica pruža tekst interfejsa za softver i zaključana je kako bi se sprečila zloupotreba.',
'editinginterface'     => "'''Upozorenje:''' Uređujete stranu koja se koristi da pruži tekst za interfejs ovog softvera.
Izmene na ovoj strani će uticati na prikaz izgleda korisničkog interfejsa za sve korisnike.
Za prevode, posetite [//translatewiki.net/wiki/Main_Page?setlang=sr_ec translatewiki.net], projekat lokalizacije MedijaViki softvera.",
'sqlhidden'            => '(SQL pretraga sakrivena)',
'cascadeprotected'     => 'Ova stranica je zaključana i njeno uređivanje je onemogućeno jer je uključena u sadržaj {{PLURAL:$1|sledeće stranice|sledećih stranica}}, koji je zaštićen sa opcijom „prenosive“ zaštite:
$2',
'namespaceprotected'   => "Nemate ovlašćenja da uređujete stranice u '''$1''' imenskom prostoru.",
'customcssprotected'   => 'Nemate dozvolu da menjate ovu CSS stranicu jer sadrži lične postavke drugog korisnika.',
'customjsprotected'    => 'Nemate dozvolu da menjate ovu stranicu javaskripta jer sadrži lične postavke drugog korisnika.',
'ns-specialprotected'  => 'Stranice u {{ns:special}} imenskom prostoru ne mogu se uređivati.',
'titleprotected'       => "Ovaj naslov je blokiran za pravljenje.
Blokirao ga je [[User:$1|$1]] a dati razlog je ''$2''.",

# Virus scanner
'virus-badscanner'     => "Loša konfiguracija zbog neodgovarajućeg skenera za virus: ''$1''",
'virus-scanfailed'     => 'skeniranje propalo (kod $1)',
'virus-unknownscanner' => 'nepoznati antivirus:',

# Login and logout pages
'logouttext'                 => "'''Odjavljeni ste.'''

Možete da nastavite s korišćenjem ovog vikija kao gost, ili se [[Special:UserLogin|ponovo prijavite]] kao drugi korisnik.
Obratite pažnju da neke stranice mogu nastaviti da se prikazuju kao da ste još uvek prijavljeni, sve dok ne očistite privremenu memoriju svog pregledača.",
'welcomecreation'            => '== Dobro došli, $1! ==

Vaš nalog je otvoren.
Ne zaboravite da prilagodite svoja [[Special:Preferences|podešavanja]].',
'yourname'                   => 'Korisničko ime',
'yourpassword'               => 'Lozinka:',
'yourpasswordagain'          => 'Ponovite lozinku',
'remembermypassword'         => 'Zapamti moju lozinku na ovom računaru (najviše $1 {{PLURAL:$1|dan|dana}})',
'securelogin-stick-https'    => 'Ostanite povezani na HTTPS nakon prijave',
'yourdomainname'             => 'Domen:',
'externaldberror'            => 'Došlo je ili do greške pri spoljašnjoj autentifikaciji baze podataka ili vam nije dozvoljeno da ažurirate svoj spoljašnji nalog.',
'login'                      => 'Prijavi me',
'nav-login-createaccount'    => 'Prijavi se/registruj se',
'loginprompt'                => 'Omogućite kolačiće da biste se prijavili na ovaj viki.',
'userlogin'                  => 'Prijavi se/registruj se',
'userloginnocreate'          => 'Prijava',
'logout'                     => 'Odjava',
'userlogout'                 => 'Odjavi me',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => "Nemate nalog? Idite na stranicu ''$1''.",
'nologinlink'                => 'Otvaranje naloga',
'createaccount'              => 'Napravi nalog',
'gotaccount'                 => "Imate nalog? Idite na stranicu ''$1''.",
'gotaccountlink'             => 'Prijavi me',
'userlogin-resetlink'        => 'Zaboravili ste podatke za prijavu?',
'createaccountmail'          => 'E-poštom',
'createaccountreason'        => 'Razlog:',
'badretype'                  => 'Lozinke koje ste uneli se ne poklapaju.',
'userexists'                 => 'Korisničko ime je zauzeto. Izaberite drugo.',
'loginerror'                 => 'Greška pri prijavljivanju',
'createaccounterror'         => 'Nije moguće napraviti nalog: $1',
'nocookiesnew'               => "Korisnički nalog je napravljen, ali niste prijavljeni. {{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili. Vi ste onemogućili kolačiće na svom računaru. Molimo omogućite ih, a onda se prijavite sa svojim novim korisničkim imenom i lozinkom.",
'nocookieslogin'             => "{{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili. Vi ste onemogućili kolačiće na svom računaru. Molimo omogućite ih i pokušajte ponovo sa prijavom.",
'nocookiesfornew'            => 'Korisnički nalog nije otvoren jer njegov izvor nije potvrđen.
Omogućite kolačiće na pregledaču i ponovo učitajte stranicu.',
'noname'                     => 'Niste izabrali ispravno korisničko ime.',
'loginsuccesstitle'          => 'Uspešno prijavljivanje',
'loginsuccess'               => "'''Sada ste prijavljeni na {{SITENAME}} kao \"\$1\".'''",
'nosuchuser'                 => 'Ne postoji korisnik pod imenom "$1".
Kod korisničkih imena se pravi razlika između malog i velikog slova.
Proverite da li ste ga dobro ukucali, ili [[Special:UserLogin/signup|napravite novi korisnički nalog]].',
'nosuchusershort'            => 'Ne postoji korisnik sa imenom "$1". Proverite da li ste dobro napisali.',
'nouserspecified'            => 'Morate da naznačite korisničko ime.',
'login-userblocked'          => 'Ovaj korisnik je blokiran. Logovanje nije dozvoljeno.',
'wrongpassword'              => 'Lozinka koju ste uneli je neispravna. Molimo pokušajte ponovo.',
'wrongpasswordempty'         => 'Lozinka koju ste uneli je prazna. Molimo pokušajte ponovo.',
'passwordtooshort'           => 'Lozinka mora imati najmanje {{PLURAL:$1|1 znak|$1 znaka/znakova}}.',
'password-name-match'        => 'Vaša lozinka mora biti drugačija od vašeg korisničkog imena.',
'password-login-forbidden'   => 'Korišćenje ovog naloga je zabranjeno.',
'mailmypassword'             => 'Pošalji mi novu lozinku',
'passwordremindertitle'      => '{{SITENAME}} podsetnik za šifru',
'passwordremindertext'       => 'Neko (verovatno vi, sa IP adrese $1) je zahtevao da vam pošaljemo novu
šifru za prijavljivanje na {{SITENAME}} ($4). Privremena šifra za korisnika
„$2“ je generisana i sada je „$3“. Ukoliko je ovo
Vaš zahtev, sada se prijavite i izaberite novu šifu.
Vaša privremena šifra ističe za {{PLURAL:$5|jedna dan|$5 dana}}.

Ukoliko je neko drugi zahtevao promenu šifre, ili ste vi zaboravili vašu
šifru i više ne želite da je menjate, možete ignorisati ovu poruku i
nastaviti koristiti vašu staru.',
'noemail'                    => 'Ne postoji adresa e-pošte za korisnika "$1".',
'noemailcreate'              => 'Morate uneti ispravnu adresu e-pošte',
'passwordsent'               => 'Nova šifra je poslata na adresu e-pošte korisnika "$1".
Molimo prijavite se pošto je primite.',
'blocked-mailpassword'       => 'Vašoj IP adresi je blokiran pristup uređivanju, iz kog razloga nije moguće koristiti funkciju podsećanja lozinke, radi prevencije izvršenja nedozvoljene akcije.',
'eauthentsent'               => 'E-pošta za potvrdu je poslata na naznačenu adresu e-pošte. Pre nego što se bilo koja druga e-pošta pošalje na nalog, moraćete da pratite uputstva u e-pošti, da biste potvrdili da je nalog zaista vaš.',
'throttled-mailpassword'     => 'Podsetnik lozinke vam je već poslao jednu poruku u poslednjih {{PLURAL:$1|sat|$1 sati}}i.
Radi prevencije izvršenja nedozvoljene akcije, podsetnik šalje samo jednu poruku u roku od {{PLURAL:$1|sata|$1 sati}}.',
'mailerror'                  => 'Greška pri slanju e-pošte: $1',
'acct_creation_throttle_hit' => 'Posetioci ovog vikija su, koristeći Vašu IP adresu, već napravili {{PLURAL:$1|jedan nalog|$1 naloga}} tokom zadnjeg dana, što je dozvoljeni maksimum za ovaj vremenski period.
Za posledicu, posetioci koji koriste ovu IP adresu trenutno ne mogu da otvore još naloga.',
'emailauthenticated'         => 'Vaša adresa e-pošte na $2 je potvrđena u $3.',
'emailnotauthenticated'      => 'Vaša adresa e-pošte još uvek nije potvrđena. E-pošta neće biti poslata ni za jednu od sledećih mogućnosti.',
'noemailprefs'               => 'Naznačite adresu e-pošte kako bi ove mogućnosti radile.',
'emailconfirmlink'           => 'Potvrdite vašu adresu e-pošte',
'invalidemailaddress'        => 'Adresa e-pošte ne može biti primljena jer izgleda nije pravilnog formata.
Molimo unesite dobro-formatiranu adresu ili ispraznite to polje.',
'cannotchangeemail'          => 'Na ovom vikiju ne možete promeniti e-adresu naloga.',
'accountcreated'             => 'Nalog je napravljen',
'accountcreatedtext'         => 'Korisnički nalog za $1 je napravljen.',
'createaccount-title'        => 'Pravljenje korisničkog naloga za {{SITENAME}}',
'createaccount-text'         => 'Neko je napravio nalog sa vašom adresom e-pošte na {{SITENAME}} ($4) pod imenom „$2”, sa lozinkom „$3”.
Prijavite se i promenite vašu lozinku.

Možete igronisati ovu poruku, ukoliko je nalog napravljen greškom.',
'usernamehasherror'          => 'Korisničko ime ne može sadržati znake tarabe (#).',
'login-throttled'            => 'Uradili ste previše skorih pokušaja da se ulogujete.
Molimo vas da sačekate par minuta i pokušate opet.',
'login-abort-generic'        => 'Prijavljivanje nije uspelo.',
'loginlanguagelabel'         => 'Jezik: $1',
'suspicious-userlogout'      => 'Vaš zahtev za izlogovanje nije izvršen zato što izgleda da je poslat iz neispravnog brauzera ili preko keširanog proksija.',

# E-mail sending
'php-mail-error-unknown' => 'Nepoznata greška u funkciji PHP mail()',
'user-mail-no-addy'      => 'Pokušali ste da pošaljete poruku bez e-adrese.',

# Change password dialog
'resetpass'                 => 'Promeni lozinku',
'resetpass_announce'        => 'Prijavili ste se sa temporalnim kodom koji Vam je poslat preko e-pošte.
Kako biste dovršili prijavljivanje, morate uneti novu lozinku:',
'resetpass_text'            => '<!-- Ovde dodaj tekst -->',
'resetpass_header'          => 'Izmeni lozinku naloga',
'oldpassword'               => 'Stara lozinka:',
'newpassword'               => 'Nova lozinka:',
'retypenew'                 => 'Ponovo otkucajte novu lozinku:',
'resetpass_submit'          => 'Unesi lozinku i prijavi se',
'resetpass_success'         => 'Vaša lozinka je uspešno promenjena! Sada vas prijavljujem u...',
'resetpass_forbidden'       => 'Lozinke se ne mogu menjati',
'resetpass-no-info'         => 'Morate biti prijavljeni da biste pristupili ovoj stranici.',
'resetpass-submit-loggedin' => 'Promeni lozinku',
'resetpass-submit-cancel'   => 'Otkaži',
'resetpass-wrong-oldpass'   => 'Neispravna privremena ili aktuelna lozinka.
Možda ste već uspešno promenili lozinku ili zatražili novu privremenu.',
'resetpass-temp-password'   => 'Privremena šifra:',

# Special:PasswordReset
'passwordreset'                    => 'Obnavljanje lozinke',
'passwordreset-text'               => 'Popunite ovaj obrazac da biste primili e-poruku sa svojim podacima za prijavu.',
'passwordreset-legend'             => 'Poništi lozinku',
'passwordreset-disabled'           => 'Poništavanje lozinke je onemogućeno na ovom vikiju.',
'passwordreset-pretext'            => '{{PLURAL:$1||Unesite jedan od delova podataka ispod}}',
'passwordreset-username'           => 'Korisničko ime:',
'passwordreset-domain'             => 'Domen:',
'passwordreset-capture'            => 'Pogledati krajnju poruku?',
'passwordreset-capture-help'       => 'Ako označite ovu kućicu, e-poruka s privremenom lozinkom će biti prikazana i poslata korisniku.',
'passwordreset-email'              => 'E-adresa:',
'passwordreset-emailtitle'         => 'Detalji naloga na vikiju {{SITENAME}}',
'passwordreset-emailtext-ip'       => 'Neko, verovatno vi, sa IP adrese $1 je zatražio novu lozinku na vikiju {{SITENAME}} ($4).
Sledeći {{PLURAL:$3|korisnički nalog je povezan|korisnički nalozi su povezani}} s ovom e-adresom:

$2

{{PLURAL:$3|Privremena lozinka ističe|Privremene lozinke ističu}} za {{PLURAL:$5|jedan dan|$5 dana|$5 dana}}.
Prijavite se i izaberite novu lozinku. Ako je neko drugi zahtevao ovu radnju ili ste se setili lozinke i ne želite da je menjate, zanemarite ovu poruku.',
'passwordreset-emailtext-user'     => '{{GENDER:$1|Korisnik|Korisnica|Korisnik}} $1 je zatražio podsetnik o podacima za prijavu na vikiju {{SITENAME}} ($4).
Sledeći {{PLURAL:$3|korisnički nalog je povezan|korisnički nalozi su povezani}} s ovom e-adresom:

$2

{{PLURAL:$3|Privremena lozinka ističe|Privremene lozinke ističu}} za {{PLURAL:$5|jedan dan|$5 dana|$5 dana}}.
Prijavite se i izaberite novu lozinku. Ako je neko drugi zahtevao ovu radnju ili ste se setili lozinke i ne želite da je menjate, zanemarite ovu poruku.',
'passwordreset-emailelement'       => 'Korisničko ime: $1
Privremena lozinka: $2',
'passwordreset-emailsent'          => 'Podsetnik o lozinci je poslat na vašu adresu.',
'passwordreset-emailsent-capture'  => 'Poslat je podsetnik preko e-pošte (prikazan dole).',
'passwordreset-emailerror-capture' => 'Napravljen je podsetnik za slanje preko e-pošte (prikazan dole), ali ga ne mogu poslati korisniku: $1',

# Special:ChangeEmail
'changeemail'          => 'Promena e-adrese',
'changeemail-header'   => 'Promenite e-adresu naloga',
'changeemail-text'     => 'Popunite ovaj obrazac da biste promenili svoju e-adresu. Moraćete da unesete lozinku da potvrdite izmenu.',
'changeemail-no-info'  => 'Morate biti prijavljeni da biste pristupili ovoj stranici.',
'changeemail-oldemail' => 'Trenutna e-adresa:',
'changeemail-newemail' => 'Nova e-adresa:',
'changeemail-none'     => '(ništa)',
'changeemail-submit'   => 'Promeni',
'changeemail-cancel'   => 'Otkaži',

# Edit page toolbar
'bold_sample'     => 'podebljan tekst',
'bold_tip'        => 'podebljan tekst',
'italic_sample'   => 'kurzivan tekst',
'italic_tip'      => 'kurzivan tekst',
'link_sample'     => 'naslov veze',
'link_tip'        => 'unutrašnja veza',
'extlink_sample'  => 'http://www.example.com opis adrese',
'extlink_tip'     => 'spoljašnja veza (ne zaboravite prefiks http://)',
'headline_sample' => 'Naslov',
'headline_tip'    => 'Naslov drugog nivoa',
'nowiki_sample'   => 'Dodaj neformatirani tekst ovde',
'nowiki_tip'      => 'Ignoriši viki formatiranje',
'image_sample'    => 'ime_slike.jpg',
'image_tip'       => 'Uklopljena slika',
'media_sample'    => 'ime_medija_fajla.ogg',
'media_tip'       => 'Putanja ka multimedijalnom fajlu',
'sig_tip'         => 'Vaš potpis sa trenutnim vremenom',
'hr_tip'          => 'Horizontalna linija',

# Edit pages
'summary'                          => 'Opis izmene:',
'subject'                          => 'Tema/naslov:',
'minoredit'                        => 'Ovo je mala izmena',
'watchthis'                        => 'nadgledaj ovu stranicu',
'savearticle'                      => 'Sačuvaj stranicu',
'preview'                          => 'Pretpregled',
'showpreview'                      => 'Prikaži pretpregled',
'showlivepreview'                  => 'Živi pretpregled',
'showdiff'                         => 'Prikaži promene',
'anoneditwarning'                  => "'''Upozorenje:''' Niste prijavljeni.
Vaša IP adresa će biti zabeležena u istoriji ove stranice.",
'anonpreviewwarning'               => "''Niste prijavljeni. Čuvanje će postaviti Vašu IP adresu u stranici za uređivanje.''",
'missingsummary'                   => "'''Podsetnik:''' Niste uneli opis izmene. Ukoliko kliknete Snimi stranicu ponovo, vaše izmene će biti snimljene bez opisa.",
'missingcommenttext'               => 'Molimo unestite komentar ispod.',
'missingcommentheader'             => "'''Podsetnik:''' Niste naveli naslov za ovaj komentar.
Ako opet kliknete \"{{int:savearticle}}\", Vaš komentar će biti snimljen bez njega.",
'summary-preview'                  => 'Pretpregled opisa izmene:',
'subject-preview'                  => 'Pretpregled predmeta/odeljka:',
'blockedtitle'                     => 'Korisnik je blokiran',
'blockedtext'                      => '\'\'\'Vaše korisničko ime ili IP adresa je blokirano.\'\'\'

Blokirao vas je korisnik $1.
Razlog za blokiranje je \'\'$2\'\'.

* Početak bloka: $8
* Ističe: $6
* Namenjen: $7

Možete kontaktirati korisnika $1 ili nekog drugog [[{{MediaWiki:Grouppage-sysop}}|administratora]] kako biste razgovarali o blokadi. Ne možete da koristite opciju "Pošalji e-poštu ovom korisniku" ukoliko nemate valjanu adresu e-pošte navedenu u vašim [[Special:Preferences|podešavanjima]]. Vaša trenutna IP adresa je $3 i ID bloka je #$5.
Molimo uključite gornje detalje u svaki vaš zahtev.',
'autoblockedtext'                  => 'Vaša IP adresa je automatski blokirana jer ju je upotrebljavao drugi korisnik, koga je blokirao $1.
Dat razlog je:

:\'\'$2\'\'

* Početak blokade: $8
* Blokada ističe: $6
* Blokirani: $7

Možete kontaktirati $1 ili nekog drugog
[[{{MediaWiki:Grouppage-sysop}}|administratora]] da biste razjasnili ovu blokadu.

Imajte u vidu da ne možete da koristite opciju "pošalji e-poštu ovom korisniku" ukoliko niste priložili ispravnu adresu elektronske pošte
u vašim [[Special:Preferences|korisničkim podešavanjima]] i ukoliko vam blokadom nije onemogućena upotreba ove opcije.

IP adresa koja je blokirana je $3, a ID vaše blokade je $5.
Molimo vas navedite ovaj ID broj prilikom pravljenja bilo kakvih upita.',
'blockednoreason'                  => 'nije dat razlog',
'whitelistedittext'                => 'Za uređivanje stranice je potrebno da budete $1.',
'confirmedittext'                  => 'Morate potvrditi svoju e-adresu pre uređivanja stranica.
Postavite i potvrdite je putem [[Special:Preferences|podešavanja]].',
'nosuchsectiontitle'               => 'Ne postoji takav odeljak',
'nosuchsectiontext'                => 'Pokušali ste da uredite odeljak koji ne postoji.
Možda je bio premešten ili obrisan dok ste pregledali stranu.',
'loginreqtitle'                    => 'Potrebna je prijava',
'loginreqlink'                     => 'prijava',
'loginreqpagetext'                 => 'Morate $1 da biste videli ostale strane.',
'accmailtitle'                     => 'Lozinka je poslata.',
'accmailtext'                      => "Slučajno generisana lozinka za [[User talk:$1|$1]] je poslata na $2.

Lozinka za ovaj novi nalog može biti promenjena na ''[[Special:ChangePassword|change password]]'', nakon prijavljivanja.",
'newarticle'                       => '(Novi)',
'newarticletext'                   => 'Došli ste na stranicu koja još uvek ne postoji.
Da biste je napravili, počnite kucati u prozor ispod ovog teksta (pogledajte [[{{MediaWiki:Helppage}}|stranicu za pomoć]]).
Ako ste ovde došli greškom, vratite se na prethodnu stranicu.',
'anontalkpagetext'                 => '---- Ovo je stranica za razgovor za anonimnog korisnika koji još nije napravio nalog, ili ga ne koristi.
Zbog toga moramo da koristimo brojčanu IP adresu kako bismo identifikovali njega ili nju.
Takvu adresu može deliti više korisnika.
Ako ste anonimni korisnik i mislite da su vam upućene nebitne primedbe, molimo vas da [[Special:UserLogin/signup|napravite nalog]] ili [[Special:UserLogin|se prijavite]] da biste izbegli buduću zabunu sa ostalim anonimnim korisnicima.',
'noarticletext'                    => 'Trenutno ne postoji članak pod tim imenom.
Možete [[Special:Search/{{PAGENAME}}|tražiti ovu stranicu]] u drugim člancima,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti srodne istorije zapisa], ili je [{{fullurl:{{FULLPAGENAME}}|action=edit}} urediti]</span>.',
'noarticletext-nopermission'       => 'Na ovoj stranici trenutno nema sadržaja.
Možete [[Special:Search/{{PAGENAME}}|potražiti ovaj naslov]] na drugim stranicama ili <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti srodne izveštaje]</span>.',
'userpage-userdoesnotexist'        => 'Nalog "<nowiki>$1</nowiki>" nije registrovan. Proverite da li želite da pravite/uređujete ovu stranicu.',
'userpage-userdoesnotexist-view'   => 'Korisnički nalog "$1" nije registrovan.',
'blocked-notice-logextract'        => 'Ovaj korisnik je trenutno blokiran.
Podaci o poslednjem blokiranju su priloženi ispod kao dodatna informacija:',
'clearyourcache'                   => "'''Napomena:''' nakon čuvanja, možda ćete morati da očistite privremenu memoriju pregledača.
*'''Fajerfoks i Safari:''' držite ''šift'' i kliknite na ''Osveži'', ili pritisnite ''šift+ktrl+R'' (''komand-R'' na Makintošu);
*'''K-osvajač: '''kliknite na ''Osveži'' ili pritisnite ''F5'';
*'''Opera:''' očistite privremenu memoriju preko menija ''Alatke → Postavke'';
*'''Gugl kroum:''' pritisnite ''ktrl-šift-R'' (''komand-šift-R'' na Makintošu)
*'''Internet eksplorer: '''držite ''ktrl'' i kliknite na ''Osveži'', ili pritisnite ''ktrl-F5'';",
'usercssyoucanpreview'             => "'''Savet:''' Korisitite dugme \"{{int:showpreview}}\" dugme da biste testirali svoj novi CSS pre snimanja.",
'userjsyoucanpreview'              => "'''Savet:''' Korisitite dugme \"{{int:showpreview}}\" da biste testirali svoj novi JavaScript pre snimanja.",
'usercsspreview'                   => "'''Zapamtite ovo je samo pretpregled vašeg CSS, još uvek nije snimljen!'''",
'userjspreview'                    => "'''Zapamtite ovo je samo pretpregled vaše JavaScript-e i da još uvek nije snimljen!'''",
'sitecsspreview'                   => "'''Ovo je samo pregled CSS-a.'''
'''Stranica još uvek nije sačuvana!'''",
'sitejspreview'                    => "'''Ovo je samo pregled javaskripta.'''
'''Stranica još uvek nije sačuvana!'''",
'userinvalidcssjstitle'            => "'''Pažnja:''' Ne postoji koža \"\$1\". Zapamtite da lične .css i .js koriste mala početna slova, npr. {{ns:user}}:Petar/vector.css a ne {{ns:user}}:Petar/Vector.css.",
'updated'                          => '(Ažurirano)',
'note'                             => "'''Napomena:'''",
'previewnote'                      => "'''Ovo samo pretpregled; izmene još nisu sačuvane!'''",
'previewconflict'                  => 'Ovaj pretpregled oslikava kako će tekst u
tekstualnom polju izgledati ako se odlučite da ga snimite.',
'session_fail_preview'             => "'''Žao nam je! Nismo mogli da obradimo vašu izmenu zbog gubitka podataka seanse. Molimo pokušajte kasnije. Ako i dalje ne radi, pokušajte da se odjavite i ponovo prijavite.'''",
'session_fail_preview_html'        => "'''Žao nam je! Nismo mogli da obradimo vašu izmenu zbog gubitka podataka seanse.'''

''Zbog toga što {{SITENAME}} ima omogućen sirov HTML, pretpregled je sakriven kao predostrožnost protiv JavaScript napada.''

'''Ako ste pokušali da napravite legitimnu izmenu, molimo pokušajte ponovo. Ako i dalje ne radi, pokušajte da se [[Special:UserLogout|odjavite]] i ponovo prijavite.'''",
'token_suffix_mismatch'            => "'''Vaša izmena je odbijena zato što je vaš klijent okrnjio interpunkcijske znake na kraju tokena. Ova izmena je odbijena zbog zaštite konzistentnosti teksta strane. Ponekad se ovo događa kad se koristi bagovit proksi servis.'''",
'edit_form_incomplete'             => "'''Neki delovi obrasca za uređivanje nisu dostigli do servera. Proverite da li su izmene promenjene i pokušajte ponovo.'''",
'editing'                          => 'Uređujete $1',
'editingsection'                   => 'Uređujete $1 (deo)',
'editingcomment'                   => 'Uređujete $1 (novi odeljak)',
'editconflict'                     => 'Sukobljene izmene: $1',
'explainconflict'                  => "Neko drugi je promenio ovu stranicu otkad ste vi počeli da je menjate.
Gornje tekstualno polje sadrži tekst stranice kakav trenutno postoji.
Vaše izmene su prikazane u donjem tekstu.
Moraćete da unesete svoje promene u postojeći tekst.
'''Samo''' tekst u gornjem tekstualnom polju će biti snimljen kada pritisnete \"{{int:savearticle}}\".",
'yourtext'                         => 'Vaš tekst',
'storedversion'                    => 'Uskladištena verzija',
'nonunicodebrowser'                => "'''UPOZORENJE: Vaš brauzer ne podržava unikod. Molimo promenite ga pre nego što počnete sa uređivanjem članka.'''",
'editingold'                       => "'''PAŽNJA: Vi menjate stariju reviziju ove stranice.
Ako je snimite, sve promene učinjene od ove revizije biće izgubljene.'''",
'yourdiff'                         => 'Razlike',
'copyrightwarning'                 => "Imajte na umu da se svi prilozi na ovom vikiju smatraju da su objavljeni pod licencom $2 (pogledajte $1 za detalje).
Ako ne želite da se vaš rad menja i raspodeljuje bez ograničenja, onda ga ne šaljite ovde.<br />
Takođe nam obećavate da ste ga sami napisali ili umnožili s izvora koji je u javnom vlasništvu.
'''Ne šaljite radove zaštićene autorskim pravima bez dozvole!'''",
'copyrightwarning2'                => "Svi prilozi na ovom vikiju mogu da se menjaju, vraćaju ili brišu od strane drugih korisnika.
Ako ne želite da se vaši prilozi nemilosrdno menjaju, ne šaljite ih ovde.<br />
Takođe nam obećavate da ste ovo sami napisali ili umnožili s izvora u javnom vlasništvu (pogledajte $1 za detalje).
'''Ne šaljite radove zaštićene autorskim pravima bez dozvole!'''",
'longpageerror'                    => "'''GREŠKA: Tekst koji snimate je velik $1 kilobajta, što je veće od maksimalno dozvoljene veličine koja iznosi $2 kilobajta. Nemoguće je snimiti stranicu.'''",
'readonlywarning'                  => "'''PAŽNJA: Baza je upravo zaključana zbog održavanja, tako da sada nećete moći da snimite svoje izmene.
Možda bi bilo dobro da iskopirate tekst u neki editor teksta i sačuvate za kasnije.'''

Administartor koji je zaključao bazu je dao ovo objašnjenje: $1",
'protectedpagewarning'             => "'''Napomena: Ova stranica je zaključana tako da samo korisnici sa administratorskim pravima mogu da je menjaju.'''
Istorija najskorijih izmena je prikazana ispod:",
'semiprotectedpagewarning'         => "'''Napomena:''' Ova stranica je zaključana tako da je samo registrovani korisnici mogu uređivati.",
'cascadeprotectedwarning'          => "'''Upozorenje:''' Ova stranica je zaštićena tako da je mogu uređivati samo korisnici sa administratorskim privilegijama jer je uključena u prenosivu zaštitu {{PLURAL:$1|sledeće stranice|sledećih stranica}}:",
'titleprotectedwarning'            => "'''Napomena: Ova stranica je zaključana tako da samo korisnici sa [[Special:ListGroupRights|određenim pravima]] mogu da je naprave.'''",
'templatesused'                    => '{{PLURAL:$1|Šablon korišćen|Šabloni korišćeni}} na ovoj strani:',
'templatesusedpreview'             => '{{PLURAL:$1|Šablon korišćen|Šabloni korišćeni}} u ovom pretpregledu:',
'templatesusedsection'             => '{{PLURAL:$1|Šablon korišćen|Šabloni korišćeni}} u ovom odeljku:',
'template-protected'               => '(zaštićeno)',
'template-semiprotected'           => '(poluzaštićeno)',
'hiddencategories'                 => 'Ova stranica je član {{PLURAL:$1|1 skrivene kategorije|$1 skrivene kategorije|$1 skrivenih kategorija}}:',
'edittools'                        => '<!-- Tekst odavde će biti pokazan ispod formulara za uređivanje i slanje slika. -->',
'nocreatetitle'                    => 'Pravljenje stranice je ograničeno',
'nocreatetext'                     => 'Na ovom sajtu je ograničeno pravljenje novih stranica.
Možete se vratiti i urediti već postojeću stranu ili [[Special:UserLogin|se prijaviti ili napraviti nalog]].',
'nocreate-loggedin'                => 'Nemate ovlašćenje da pravite nove stranice.',
'sectioneditnotsupported-title'    => 'Menjanje delova stranice nije podržano.',
'sectioneditnotsupported-text'     => 'Menjanje delova stranice nije podržano na ovoj stranici.',
'permissionserrors'                => 'Greške u ovlašćenjima',
'permissionserrorstext'            => 'Nemate ovlašćenje da uradite to iz {{PLURAL:$1|sledećeg|sledećih}} razloga:',
'permissionserrorstext-withaction' => 'Nemate dozvolu da $2, zbog sledećeg: {{PLURAL:$1|razloga|razloga}}:',
'recreate-moveddeleted-warn'       => "'''Upozorenje: Ponovo pravite stranicu koja je prethodno obrisana.'''

Trebalo bi da razmotrite da li je prikladno da nastavite sa uređivanjem ove stranice.
Istorije brisanja i premeštanja ove stranice su priloženi ispod:",
'moveddeleted-notice'              => 'Ova stranica je obrisana.
Istorija njenog brisanja i premeštanja nalazi se ispod:',
'log-fulllog'                      => 'Pogledaj celu istoriju',
'edit-hook-aborted'                => 'Izmena je sprečena zakačenom funkcijom.
Nije dato nikakvo obrazloženje.',
'edit-gone-missing'                => 'Stranica nije mogla biti izmenjena.
Izgleda da je u međuvremenu bila obrisana.',
'edit-conflict'                    => 'Sukob izmena',
'edit-no-change'                   => 'Vaša izmena je ignorisana jer nije bilo nikakvih izmena u tekstu.',
'edit-already-exists'              => 'Nemože se napraviti nova stranica.
Ona već postoji.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Upozorenje:''' Ova stranica sadrži previše poziva funkcije parsiranja.

Trebalo bi da ima manje od $2 {{PLURAL:$2|poziv|poziva}}, a sada {{PLURAL:$1|postoji $1 poziv|postoje $1 poziva}}.",
'expensive-parserfunction-category'       => 'Strane sa previše skupih poziva funkcija parsiranja.',
'post-expand-template-inclusion-warning'  => 'Upozorenje: Veličina uključenog šablona je prevelika. Neki šabloni neće biti uključeni.',
'post-expand-template-inclusion-category' => 'Strane na kojima je prekoračena veličina uključivanja šablona.',
'post-expand-template-argument-warning'   => "'''Upozorenje:''' Ova stranica sadrži bar jedan preveliki argument šablona, koji će biti izostavljeni.",
'post-expand-template-argument-category'  => 'Strane sa izostavljenim argumentima šablona.',
'parser-template-loop-warning'            => 'Otkriveno je samouključivanje šablona: [[$1]]',
'parser-template-recursion-depth-warning' => 'Premašena je dozvoljena dubina rekurzije za šablone ($1)',
'language-converter-depth-warning'        => 'Prekoračena je granica dubine jezičkog pretvarača ($1)',

# "Undo" feature
'undo-success' => 'Ova izmena može da se vrati. Proverite razlike ispod kako bi proverili da je ovo to što želite da uradite, tada snimite izmene kako bi završili vraćanje izmene.',
'undo-failure' => 'Izmena ne može biti oporavljena usled sukobljenih međuizmena.',
'undo-norev'   => 'Izmena ne može biti oporavljena zato što ne postoji ili je obrisana.',
'undo-summary' => 'Izmena $1 je vraćena od {{GENDER:$2|korisnika|korisnice|korisnika}} [[Special:Contributions/$2|$2]] ([[User talk:$2|razgovor]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ne može da se napravi nalog',
'cantcreateaccount-text' => "Pravljenje naloga sa ove IP adrese ('''$1''') je blokirao [[User:$3|$3]].

Razlog koji je dao $3 je ''$2''",

# History pages
'viewpagelogs'           => 'Istorija ove stranice',
'nohistory'              => 'Ne postoji istorija izmena ove stranice.',
'currentrev'             => 'Trenutna revizija',
'currentrev-asof'        => 'Tekuća izmena od $2 u $3',
'revisionasof'           => 'Izmena od $2 u $3',
'revision-info'          => 'Revizija od $1; $2',
'previousrevision'       => '← Prethodna revizija',
'nextrevision'           => 'Sledeća revizija →',
'currentrevisionlink'    => 'Trenutna revizija',
'cur'                    => 'tren',
'next'                   => 'sled',
'last'                   => 'preth',
'page_first'             => 'prvo',
'page_last'              => 'poslednje',
'histlegend'             => 'Odabiranje razlika: odaberite kutijice revizija za upoređivanje i pritisnite enter ili dugme na dnu.<br />
Objašnjenje: (tren) = razlika sa trenutnom verzijom,
(posl) = razlika sa prethodnom verzijom, M = mala izmena',
'history-fieldset-title' => 'Pregled istorije',
'history-show-deleted'   => 'Samo obrisane',
'histfirst'              => 'Najranije',
'histlast'               => 'Poslednje',
'historysize'            => '({{PLURAL:$1|1 bajt|$1 bajta|$1 bajtova}})',
'historyempty'           => '(prazno)',

# Revision feed
'history-feed-title'          => 'Istorija izmena',
'history-feed-description'    => 'Istorija revizija za ovu stranu na vikiju',
'history-feed-item-nocomment' => '$1, $2',
'history-feed-empty'          => 'Tražena stranica ne postoji.
Moguće da je obrisana iz vikija ili preimenovana.
Pokušajte [[Special:Search|da pretražite viki]] za relevantne nove stranice.',

# Revision deletion
'rev-deleted-comment'         => '(sažetak izmene je uklonjen)',
'rev-deleted-user'            => '(korisničko ime uklonjeno)',
'rev-deleted-event'           => '(istorija uklonjena)',
'rev-deleted-user-contribs'   => '[korisničko ime ili IP adresa su obrisani - izmena je sakrivena iz spiska doprinosa]',
'rev-deleted-text-permission' => "Ova revizija stranice je '''obrisana'''.
Detalji vezani za ovo brisanje bi se mogli nalaziti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-deleted-text-unhide'     => "Izmena ove stranice je '''obrisana'''.
Detalje možete videti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].
Ipak možete da [$1 vidite ovu izmenu] ako želite da nastavite.",
'rev-suppressed-text-unhide'  => "Izmena ove stranice je '''sakrivena'''.
Detalje možete videti u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].
Ipak možete da [$1 vidite ovu izmenu] ako želite da nastavite.",
'rev-deleted-text-view'       => "Izmena ove stranice je '''obrisana'''.
Možete je pogledati; više detalja možete naći u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-suppressed-text-view'    => "Izmena ove stranice je '''sakrivena'''.
Možete je pogledati; više detalja možete naći u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].",
'rev-deleted-no-diff'         => "Ne možete videti ovu razliku izmena zato što je jedna od revizija '''obrisana'''.
Detalji vezani za ovo brisanje bi se mogli nalaziti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-suppressed-no-diff'      => "Ne možete da vidite ovaj dif zato što je jedna od revizija '''obrisana'''.",
'rev-deleted-unhide-diff'     => "Jedna od izmena u ovom pregledu razlika je '''obrisana'''.
Detalji se nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].
Ipak možete da [$1 vidite ovu razliku] ako želite da nastavite.",
'rev-suppressed-unhide-diff'  => "Jedna od izmena ove razlike je '''sakrivena'''.
Detalji se nalaze u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].
Ipak možete da [$1 vidite ovu razliku] ako želite da nastavite.",
'rev-deleted-diff-view'       => "Jedna od izmena ove razlike je '''obrisana'''.
Ipak možete da vidite ovu razliku; više detalja možete naći u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-suppressed-diff-view'    => "Jedna od izmena ove razlike je '''sakrivena'''.
Ipak možete da vidite ovu razliku; više detalja možete naći u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].",
'rev-delundel'                => 'pokaži/sakrij',
'rev-showdeleted'             => 'pokaži',
'revisiondelete'              => 'Obriši/vrati reviziju',
'revdelete-nooldid-title'     => 'Nema odabrane revizije',
'revdelete-nooldid-text'      => 'Niste odabrali željenu reviziju ili revizije kako biste uključili ove funkcije.',
'revdelete-nologtype-title'   => 'Nije navedena vrsta istorije',
'revdelete-nologtype-text'    => 'Niste naveli tip istorije nad kojim želite da izvedete ovu akciju.',
'revdelete-nologid-title'     => 'Neispravan unos u istoriju',
'revdelete-nologid-text'      => 'Ili niste naznačili ciljani unos istorije, zarad izvođenja ove funkcije, ili unos koji ste naveli ne postoji.',
'revdelete-no-file'           => 'Traženi fajl ne postoji.',
'revdelete-show-file-confirm' => 'Da li ste sigurni da želite da vidite obrisanu reviziju fajla "<nowiki>$1</nowiki>" od $2 u $3?',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Odabrana revizija|Odabrane revizije}} za '''[[:$1]]''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Izabrani događaj iz istorije|Izabrani događaji iz istorije}}:'''",
'revdelete-text'              => "'''Obrisane revizije i događaji će još uvek biti prikazani u istoriji stranica i protokola, ali delovi njihovog sadržaja neće biti javno dostupni.'''
Drugi administratori na {{SITENAME}} će još uvek imati pristup ovom skrivenom sadržaju i moći će da ga vrate preko istog ovog interfejsa, osim ako se postave dodatna ograničenja.",
'revdelete-confirm'           => 'Potvrdite da nameravate ovo uraditi, da razumete posledice i da to činite u skladu s [[{{MediaWiki:Policy-url}}|pravilima]].',
'revdelete-suppress-text'     => "Sakrivanje naloga bi trebalo da se koristi '''samo''' u sledećim slučajevima:
* Verovatno zlonamernu informaciju
* Neodgovarajuće lične podatke
*: ''kućne adrese i telefonske brojeve, brojeve socijalnih usluga, itd.''",
'revdelete-legend'            => 'Postavi restrikcije vidljivosti',
'revdelete-hide-text'         => 'Sakrij tekst revizije',
'revdelete-hide-image'        => 'Sakrij sadržaj fajla',
'revdelete-hide-name'         => 'Sakrij akciju i cilj.',
'revdelete-hide-comment'      => 'Sakrij opis izmene',
'revdelete-hide-user'         => 'Sakrij korisničko ime/IP adresu korisnika koji je uređivao stranicu',
'revdelete-hide-restricted'   => 'Skloni podatke kako od administratora, tako i od svih ostalih',
'revdelete-radio-same'        => '(ne menjaj)',
'revdelete-radio-set'         => 'Da',
'revdelete-radio-unset'       => 'Ne',
'revdelete-suppress'          => 'Sakrij podatke od administratora i drugih korisnika',
'revdelete-unsuppress'        => 'Ukloni zabrane nad oporavljenim verzijama.',
'revdelete-log'               => 'Razlog:',
'revdelete-submit'            => 'Primeni na {{PLURAL:$1|izabranu reviziju|izabrane revizije}}',
'revdelete-success'           => "'''Vidljivost izmene je uspešno podešena.'''",
'revdelete-failure'           => "'''Vidljivost revizije nije mogla biti ažurirana:'''
$1",
'logdelete-success'           => "'''Vidnost loga je uspešno podešena.'''",
'logdelete-failure'           => "'''Vidljivost istorije nije postavljena:'''
$1",
'revdel-restore'              => 'Promena vidnosti',
'revdel-restore-deleted'      => 'izbrisane revizije',
'revdel-restore-visible'      => 'vidljive revizije',
'pagehist'                    => 'Istorija stranice',
'deletedhist'                 => 'Obrisana istorija',
'revdelete-hide-current'      => 'Greška pri sakrivanju stavke od $1, $2: ovo je trenutna izmena.
Ne može biti sakrivena.',
'revdelete-show-no-access'    => 'Greška pri prikazivanju stavke od $1, $2: označena je kao „ograničena“.
Nemate pristup do nje.',
'revdelete-modify-no-access'  => 'Greška pri menjanju stavke od $1, $2: označena je kao „ograničena“.
Nemate pristup do nje.',
'revdelete-modify-missing'    => 'Greška pri menjanju IB stavke $1: ona ne postoji u bazi podataka.',
'revdelete-no-change'         => "'''Upozorenje:''' stavka od $1, $2 već poseduje zatražene postavke vidljivosti.",
'revdelete-concurrent-change' => 'Greška pri menjanju stavke od $1, $2: njeno stanje je u međuvremenu promenjeno od strane drugog korisnika.
Pogledajte istoriju.',
'revdelete-only-restricted'   => 'Greška pri sakrivanju stavke od $1, $2: ne možete sakriti stavke od administratora bez izbora drugih mogućnosti vidljivosti.',
'revdelete-reason-dropdown'   => '*Uobičajeni razlozi za brisanje
** Kršenje autorskog prava
** Neodgovarajuće lične informacije
** Potencijalno uvredljive informacije',
'revdelete-otherreason'       => 'Drugi/dodatni razlog:',
'revdelete-reasonotherlist'   => 'Drugi razlog',
'revdelete-edit-reasonlist'   => 'Uredi razloge za brisanje',
'revdelete-offender'          => 'Autor revizije:',

# Suppression log
'suppressionlog'     => 'Log sakrivanja',
'suppressionlogtext' => 'Ispod se nalazi spisak brisanja i blokiranja koji uključuje sadržaj sakriven od administratora. Pogledajte [[Special:BlockList|spisak blokiranih IP adresa]] za pregled važećih zabrana i blokiranja.',

# History merging
'mergehistory'                     => 'Spoji istorije stranica',
'mergehistory-header'              => 'Ova stranica omogućava spajanje verzija jedne stranice u drugu. Uverite se prethodno da će ova izmena održati kontinuitet istorije stranice.',
'mergehistory-box'                 => 'Spoj verzije dve strane:',
'mergehistory-from'                => 'Izvorna stranica:',
'mergehistory-into'                => 'Željena stranica:',
'mergehistory-list'                => 'Istorija izmena koje se mogu spojiti',
'mergehistory-merge'               => 'Sledeće verzije strane [[:$1]] mogu se spojiti sa [[:$2]]. Koristi kolonu s "radio dugmićima" za spajanje samo onih verzija koje su napravljene pre datog vremena. Korišćenje navigacionih linkova će poništiti ovu kolonu.',
'mergehistory-go'                  => 'Prikaži izmene koje se mogu spojiti.',
'mergehistory-submit'              => 'Spoj izmene.',
'mergehistory-empty'               => 'Nema izmena koje se mogu spojiti.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revizija|revizije|revizija}} strane [[:$1]] uspešno spojeno u [[:$2]].',
'mergehistory-fail'                => 'Nije moguće spojiti verzije; proveri parametre strane i vremena.',
'mergehistory-no-source'           => 'Izvorna stranica $1 ne postoji.',
'mergehistory-no-destination'      => 'Željena stranica $1 ne postoji.',
'mergehistory-invalid-source'      => 'Ime izvorne stranice mora biti ispravno.',
'mergehistory-invalid-destination' => 'Ime željene stranice mora biti ispravno.',
'mergehistory-autocomment'         => 'Spojena stranica [[:$1]] u stranicu [[:$2]].',
'mergehistory-comment'             => 'Spojena stranica [[:$1]] u stranicu [[:$2]]: $3',
'mergehistory-same-destination'    => 'Izvorna i ciljana strana ne mogu biti iste',
'mergehistory-reason'              => 'Razlog:',

# Merge log
'mergelog'           => 'Istorija spajanja',
'pagemerge-logentry' => 'spojena strana [[$1]] u stranu [[$2]] (broj verzija do: $3)',
'revertmerge'        => 'rastavljanje',
'mergelogpagetext'   => 'Ispod se nalazi spisak skorašnjih spajanja verzija jedne strane u drugu.',

# Diffs
'history-title'            => 'Istorija izmena za „$1”',
'difference'               => '(Razlika između revizija)',
'difference-multipage'     => '(razlike između stranica)',
'lineno'                   => 'Linija $1:',
'compareselectedversions'  => 'Uporedi označene verzije',
'showhideselectedversions' => 'Prikaži/sakrij odabrane revizije',
'editundo'                 => 'poništi',
'diff-multi'               => '({{PLURAL:$1|nije prikazana međuizmena|nisu prikazane $1 međuizmene|nije prikazano $1 međuizmena}} {{PLURAL:$2|jednog|$2|$2}} korisnika)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Nije prikazana međuizmena|Nisu prikazane $1 međuizmene|Nije prikazano $1 međuizmena}} od više od $2 korisnika)',

# Search results
'searchresults'                    => 'Rezultati pretrage',
'searchresults-title'              => 'Rezultati pretrage za „$1”',
'searchresulttext'                 => 'Za više informacija o pretraživanju {{SITENAME}}, pogledajte [[{{MediaWiki:Helppage}}|Pretraživanje {{SITENAME}}]].',
'searchsubtitle'                   => 'Tražili ste \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|sve stranice koje počinju sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sve stranice koje povezuju na "$1"]])',
'searchsubtitleinvalid'            => "Tražili ste '''$1'''",
'toomanymatches'                   => 'Previše pogodaka je vraćeno. Izmenite upit.',
'titlematches'                     => 'Naslov stranice odgovara',
'notitlematches'                   => 'Nijedan naslov stranice ne odgovara',
'textmatches'                      => 'Tekst stranice odgovara',
'notextmatches'                    => 'Nijedan tekst stranice ne odgovara',
'prevn'                            => 'prethodnih {{PLURAL:$1|$1}}',
'nextn'                            => 'sledećih {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Prethodni $1 rezultat|Prethodnih $1 rezultata}}',
'nextn-title'                      => '{{PLURAL:$1|Sledeći $1 rezultat|Sledećih $1 rezultata}}',
'shown-title'                      => 'Prikaži $1 {{PLURAL:$1|rezultat|rezultata}} po strani',
'viewprevnext'                     => 'Pogledaj ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opcije pretrage',
'searchmenu-exists'                => "'''Već postoji članak pod imenom \"[[:\$1]]\" na ovom Vikiju'''",
'searchmenu-new'                   => "'''Napravi članak \"[[:\$1]]\" na ovom Vikiju!'''",
'searchhelp-url'                   => 'Help:Sadržaj',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Potraži stranice sa ovim prefiksom]]',
'searchprofile-articles'           => 'Stranice sa sadržajem',
'searchprofile-project'            => 'Strane pomoći i projekta',
'searchprofile-images'             => 'Multimedija',
'searchprofile-everything'         => 'Sve',
'searchprofile-advanced'           => 'Napredna',
'searchprofile-articles-tooltip'   => 'Traži u $1',
'searchprofile-project-tooltip'    => 'Traži u $1',
'searchprofile-images-tooltip'     => 'Pretražuj fajlove',
'searchprofile-everything-tooltip' => 'Pretraži sav sadržaj (uključujući stranice za razgovor)',
'searchprofile-advanced-tooltip'   => 'Pretraži u sopstvenim imenskim prostorima',
'search-result-size'               => '$1 ({{PLURAL:$2|1 reč|$2 reči}})',
'search-result-category-size'      => '{{PLURAL:$1|1 član|$1 člana|$1 članova}}, ({{PLURAL:$2|1 potkategorija|$2 potkategorije|$2 potkategorija}}, {{PLURAL:$3|1 datoteka|$3 datoteke|$3 datoteka}})',
'search-result-score'              => 'Relevantnost: $1%',
'search-redirect'                  => '(preusmerenje $1)',
'search-section'                   => '(naslov $1)',
'search-suggest'                   => 'Da li ste mislili: $1',
'search-interwiki-caption'         => 'Bratski projekti',
'search-interwiki-default'         => '$1 rezultati:',
'search-interwiki-more'            => '(više)',
'search-mwsuggest-enabled'         => 'sa sugestijama',
'search-mwsuggest-disabled'        => 'bez sugestija',
'search-relatedarticle'            => 'Srodno',
'mwsuggest-disable'                => 'Isključi AJAKS sugestije',
'searcheverything-enable'          => 'Traži u svim imenskim prostorima',
'searchrelated'                    => 'srodno',
'searchall'                        => 'sve',
'showingresults'                   => "Prikazujem ispod do {{PLURAL:$1|'''1''' rezultat|'''$1''' rezultata}} počev od #'''$2'''.",
'showingresultsnum'                => "Prikazujem ispod do {{PLURAL:$3|'''1''' rezultat|'''$3''' rezultata}} počev od #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultat '''$1''' od '''$3'''|Rezultata '''$1 – $2''' od '''$3'''}} za '''$4'''",
'nonefound'                        => "'''Napomena''': Samo nekoliko imenskih prostora se pretražuju po osnovnom podešavanju.
Pokušajte sa prefiksom '''sve:''' da pretražite ceo sadržaj (uključujući stranice za razgovor, šablone itd.), ili izaberite željeni imenski prostor kao prefiks.",
'search-nonefound'                 => 'Nije bilo rezultata koji odgovaraju upitu.',
'powersearch'                      => 'Traži',
'powersearch-legend'               => 'Napredna pretraga',
'powersearch-ns'                   => 'Traži u imenskim prostorima:',
'powersearch-redir'                => 'Spisak preusmerenja',
'powersearch-field'                => 'Pretraži za',
'powersearch-togglelabel'          => 'Odaberi:',
'powersearch-toggleall'            => 'Sve',
'powersearch-togglenone'           => 'Ništa',
'search-external'                  => 'Spoljašnja pretraga',
'searchdisabled'                   => 'Pretraga za sajt {{SITENAME}} je onemogućena. U međuvremenu, možete koristiti Gugl pretragu. Imajte na umu da indeksi Gugla za sajt {{SITENAME}} mogu biti zastareli.',

# Quickbar
'qbsettings'                => 'Brza paleta',
'qbsettings-none'           => 'Nikakva',
'qbsettings-fixedleft'      => 'Pričvršćena levo',
'qbsettings-fixedright'     => 'Pričvršćena desno',
'qbsettings-floatingleft'   => 'Plutajuća levo',
'qbsettings-floatingright'  => 'Plutajuća desno',
'qbsettings-directionality' => 'Fiksno, u zavisnosti od smera pisanja vašeg jezika',

# Preferences page
'preferences'                   => 'Podešavanja',
'mypreferences'                 => 'Podešavanja',
'prefs-edits'                   => 'Broj izmena:',
'prefsnologin'                  => 'Niste prijavljeni',
'prefsnologintext'              => 'Morate biti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prijavljeni]</span> da biste podešavali korisnička podešavanja.',
'changepassword'                => 'Promeni lozinku',
'prefs-skin'                    => 'Koža',
'skin-preview'                  => 'Pregled',
'datedefault'                   => 'Nije bitno',
'prefs-beta'                    => 'Beta funkcije',
'prefs-datetime'                => 'Datum i vreme',
'prefs-labs'                    => 'Probne mogućnosti',
'prefs-personal'                => 'Korisnička podešavanja',
'prefs-rc'                      => 'Skorašnje izmene',
'prefs-watchlist'               => 'Spisak nadgledanja',
'prefs-watchlist-days'          => 'Broj dana koji treba da se vidi na spisku nadgledanja:',
'prefs-watchlist-days-max'      => 'Maksimum 7 dana',
'prefs-watchlist-edits'         => 'Broj izmena koji treba da se vidi na proširenom spisku nadgledanja:',
'prefs-watchlist-edits-max'     => 'Maksimalan broj: 1000',
'prefs-watchlist-token'         => 'Pečat spiska nadgledanja:',
'prefs-misc'                    => 'Razno',
'prefs-resetpass'               => 'Promeni lozinku',
'prefs-changeemail'             => 'Promeni e-adresu',
'prefs-setemail'                => 'Postavi e-adresu',
'prefs-email'                   => 'Opcije elektronske pošte',
'prefs-rendering'               => 'Izgled',
'saveprefs'                     => 'Sačuvaj',
'resetprefs'                    => 'Vrati',
'restoreprefs'                  => 'Vrati podrazumevana podešavanja',
'prefs-editing'                 => 'Uređivanje',
'prefs-edit-boxsize'            => 'Veličina prozora za pisanje izmene.',
'rows'                          => 'Redova',
'columns'                       => 'Kolona',
'searchresultshead'             => 'Pretraga',
'resultsperpage'                => 'Pogodaka po stranici:',
'stub-threshold'                => 'Prag za formatiranje <a href="#" class="stub">linka kao klice</a> (u bajtovima):',
'stub-threshold-disabled'       => 'Onemogućeno',
'recentchangesdays'             => 'Broj dana u skorašnjim izmenama:',
'recentchangesdays-max'         => '(mmaksimum $1 {{PLURAL:$1|dan|dana}})',
'recentchangescount'            => 'Podrazumevani broj izmena, koji će biti prikazan:',
'prefs-help-recentchangescount' => 'Ovo uključuje skorašnje izmene, istorije i izveštaje.',
'prefs-help-watchlist-token'    => 'Popunjavanjem ovog polja s tajnom šifrom napraviće RSS dovod vašeg spiska nadgledanja.
Svako ko zna tu šifru biće u mogućnosti da vidi vaša nadgledanja, zato izaberite bezbednu.
Na primer: $1',
'savedprefs'                    => 'Vaša podešavanja su sačuvana.',
'timezonelegend'                => 'Vremenska zona:',
'localtime'                     => 'Lokalno vreme:',
'timezoneuseserverdefault'      => 'podrazumevane vrednosti ($1)',
'timezoneuseoffset'             => 'Drugo (odredi odstupanje)',
'timezoneoffset'                => 'Odstupanje¹:',
'servertime'                    => 'Vreme na serveru:',
'guesstimezone'                 => 'Popuni iz brauzera',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktik',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlantski okean',
'timezoneregion-australia'      => 'Australija',
'timezoneregion-europe'         => 'Evropa',
'timezoneregion-indian'         => 'Indijski okean',
'timezoneregion-pacific'        => 'Pacifički okean',
'allowemail'                    => 'Omogući e-poštu od drugih korisnika',
'prefs-searchoptions'           => 'Opcije pretrage',
'prefs-namespaces'              => 'Imenski prostori',
'defaultns'                     => 'U suprotnom, traži u ovim imenskim prostorima:',
'default'                       => 'standard',
'prefs-files'                   => 'Datoteke',
'prefs-custom-css'              => 'Korisnički CSS',
'prefs-custom-js'               => 'Korisnički JS',
'prefs-common-css-js'           => 'Deljeni CSS/javaskript za sve teme:',
'prefs-reset-intro'             => 'Možete koristiti ovu stranicu da poništite svoje postavke na podrazumevane vrednosti.
Ova radnja se ne može vratiti.',
'prefs-emailconfirm-label'      => 'Potvrda e-pošte:',
'prefs-textboxsize'             => 'Veličina prozora za pisanje izmene',
'youremail'                     => 'E-pošta:',
'username'                      => 'Korisničko ime:',
'uid'                           => 'Korisnički ID:',
'prefs-memberingroups'          => 'Član {{PLURAL:$1|grupe|grupa}}:',
'prefs-registration'            => 'Vreme registracije:',
'yourrealname'                  => 'Pravo ime:',
'yourlanguage'                  => 'Jezik:',
'yourvariant'                   => 'Varijanta jezika:',
'prefs-help-variant'            => 'Željena varijanta ili pravopis za prikaz stranica sa sadržajem ovog vikija.',
'yournick'                      => 'Nadimak:',
'prefs-help-signature'          => "Komentare na stranicama za razgovor potpišite sa ''<nowiki>~~~~</nowiki>''. Ovi znakovi će biti pretvoreni u vaš potpis i trenutno vreme.",
'badsig'                        => 'Greška u potpisu; proverite HTML tagove.',
'badsiglength'                  => 'Vaš potpis je predugačak.
Mora biti ispod $1 {{PLURAL:$1|karakter|karaktera}}.',
'yourgender'                    => 'Pol:',
'gender-unknown'                => 'Nenaznačen',
'gender-male'                   => 'Muški',
'gender-female'                 => 'Ženski',
'prefs-help-gender'             => 'Neobavezno: koristi se za ispravno obraćanje softvera korisnicima, zavisno od njihovog pola.
Ova informacija će biti javna.',
'email'                         => 'E-pošta',
'prefs-help-realname'           => '* Pravo ime (opciono): ako izaberete da date ime, ovo će biti korišćeno za pripisivanje za vaš rad.',
'prefs-help-email'              => 'E-adresa nije obavezna, ali je potrebna u slučaju da zaboravite lozinku.',
'prefs-help-email-others'       => 'Možete je koristiti i da omogućite drugima da vas kontaktiraju preko korisničke stranice ili stranice za razgovor, bez otkrivanja svog identiteta.',
'prefs-help-email-required'     => 'Neophodna je adresa e-pošte.',
'prefs-info'                    => 'Osnovne informacije',
'prefs-i18n'                    => 'Internacionalizacija',
'prefs-signature'               => 'Potpis',
'prefs-dateformat'              => 'Format datuma',
'prefs-timeoffset'              => 'Vremenska razlika',
'prefs-advancedediting'         => 'Napredne opcije',
'prefs-advancedrc'              => 'Napredne opcije',
'prefs-advancedrendering'       => 'Napredne opcije',
'prefs-advancedsearchoptions'   => 'Napredne opcije',
'prefs-advancedwatchlist'       => 'Napredne opcije',
'prefs-displayrc'               => 'Opcije prikaza',
'prefs-displaysearchoptions'    => 'Postavke prikaza',
'prefs-displaywatchlist'        => 'Postavke prikaza',
'prefs-diffs'                   => 'Revizije',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-adresa je ispravna',
'email-address-validity-invalid' => 'Unesite ispravnu e-adresu',

# User rights
'userrights'                   => 'Upravljanje korisničkim pravima',
'userrights-lookup-user'       => 'Upravljaj korisničkim grupama',
'userrights-user-editname'     => 'Unesite korisničko ime:',
'editusergroup'                => 'Menjaj grupe korisnika',
'editinguser'                  => "Menjate korisnička prava {{GENDER:$1|korisnika|korisnice|korisnika}} '''[[User:$1|$1]]''' $2",
'userrights-editusergroup'     => 'Promeni korisničke grupe',
'saveusergroups'               => 'Sačuvaj korisničke grupe',
'userrights-groupsmember'      => 'Član:',
'userrights-groupsmember-auto' => 'Implicitni član od:',
'userrights-groups-help'       => 'Možete kontrolisati grupe u kojima se ovaj korisnik nalazi.
* Štiklirani kvadratić označava da se korisnik nalazi u grupi.
* Kvadratić koji nije štikliran označava da se korisnik ne nalazi u grupi.
* Zvezdica (*) označava da vi ne možete ukloniti grupu ukoliko ste je dodali, ili obratno.',
'userrights-reason'            => 'Razlog:',
'userrights-no-interwiki'      => 'Nemate ovlašćenja da menjate korisnička prava na ostalim vikijima.',
'userrights-nodatabase'        => 'Baza podataka $1 ne postoji ili nije lokalna.',
'userrights-nologin'           => 'Morate se [[Special:UserLogin|prijaviti]] sa administratorskim nalogom da dodate korisnička prava.',
'userrights-notallowed'        => 'Nemate ovlašćenja da dodajete ili uklanjate korisnička prava.',
'userrights-changeable-col'    => 'Grupe koje možete menjati',
'userrights-unchangeable-col'  => 'Grupe koje ne možete menjati',

# Groups
'group'               => 'Grupa:',
'group-user'          => 'Korisnici',
'group-autoconfirmed' => 'Automatski potvrđeni korisnici',
'group-bot'           => 'botovi',
'group-sysop'         => 'administratori',
'group-bureaucrat'    => 'birokrate',
'group-suppress'      => 'Revizori',
'group-all'           => '(svi)',

'group-user-member'          => '{{GENDER:$1|korisnik|korisnica|korisnik}}',
'group-autoconfirmed-member' => '{{GENDER:$1|automatski potvrđen korisnik|automatski potvrđena korisnica|automatski potvrđen korisnik}}',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|administrator|administratorka|administrator}}',
'group-bureaucrat-member'    => '{{GENDER:$1|birokrata|birokratica|birokrata}}',
'group-suppress-member'      => '{{GENDER:$1|revizor|revizorka|revizor}}',

'grouppage-user'          => '{{ns:project}}:Korisnici',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatski potvrđeni korisnici',
'grouppage-bot'           => '{{ns:project}}:Botovi',
'grouppage-sysop'         => '{{ns:project}}:Spisak administratora',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrate',
'grouppage-suppress'      => '{{ns:project}}:Revizori',

# Rights
'right-read'                  => 'Pregledanje stranica',
'right-edit'                  => 'Uređivanje stranica',
'right-createpage'            => 'Pravljenje stranica (koje nisu stranice za razgovor)',
'right-createtalk'            => 'Pravljenje stranica za razgovor',
'right-createaccount'         => 'Pravljenje novih korisničkih naloga',
'right-minoredit'             => 'Označavanje izmena malom',
'right-move'                  => 'Premeštanje stranica',
'right-move-subpages'         => 'Premeštanje stranica sa njihovim podstranicama',
'right-move-rootuserpages'    => 'Premeštanje baznih korisničkih strana',
'right-movefile'              => 'Premesti fajlove',
'right-suppressredirect'      => 'Nestvaranje preusmerenja od starog imena po preimenovanju strane.',
'right-upload'                => 'slanje datoteka',
'right-reupload'              => 'Presnimavanje postojećeg fajla',
'right-reupload-own'          => 'Presnimavanje sopstvenog postojećeg fajla',
'right-reupload-shared'       => 'lokalno prepisivanje fajlova na deljenom skladištu medija',
'right-upload_by_url'         => 'slanje datoteka preko URL adrese',
'right-purge'                 => 'čišćenje keša stranice bez potvrde',
'right-autoconfirmed'         => 'uređivanje poluzaštićenih stranica',
'right-bot'                   => 'korisnik je zapravo automatski proces (bot)',
'right-nominornewtalk'        => 'neposedovanje malih izmena na stranama za razgovor okida prompt za novu poruku',
'right-apihighlimits'         => 'korišćenje viših limita za upite iz API-ja',
'right-writeapi'              => 'pisanje API-ja',
'right-delete'                => 'brisanje stranica',
'right-bigdelete'             => 'brisanje stranica sa velikom istorijom',
'right-deleterevision'        => 'brisanje i vraćanje određenih izmena stranica',
'right-deletedhistory'        => 'gledanje obrisanih verzija stranica bez teksta koji je vezan za njih',
'right-deletedtext'           => 'pregledanje obrisanog teksta i izmena između obrisanih izmena',
'right-browsearchive'         => 'Pretraživanje obrisanih stranica',
'right-undelete'              => 'Vraćanje obrisane stranice',
'right-suppressrevision'      => 'pregledanje i vraćanje izmena koje su sakrivene od strane administratora',
'right-suppressionlog'        => 'pregledanje lične istorije',
'right-block'                 => 'zabrana menjenja strana drugim saradnicima',
'right-blockemail'            => 'zabrana slanja imejla saradnicima',
'right-hideuser'              => 'zabrana korisničkog imena skrivanjem od javnosti',
'right-ipblock-exempt'        => 'prolazak IP blokova, automatskih blokova i blokova opsega',
'right-proxyunbannable'       => 'prolazak automatskih blokova proksija',
'right-unblockself'           => 'deblokiranje samog sebe',
'right-protect'               => 'promena stepena zaštite i izmena zaštićenih strana',
'right-editprotected'         => 'izmena zaštićenih strana (bez mogućnosti izmene stepena zaštite)',
'right-editinterface'         => 'Uredi korisnički interfejs',
'right-editusercssjs'         => 'menjanje tuđih CSS i JS fajlova',
'right-editusercss'           => 'menjanje tuđih CSS fajlova',
'right-edituserjs'            => 'menjanje tuđih JS fajlova',
'right-rollback'              => 'brzo vraćanje izmena poslednjeg korisnika koji je menjao određenu stranicu',
'right-markbotedits'          => 'označavanje vraćenih strana kao izmena koje je napravio bot',
'right-noratelimit'           => 'ne biti pogođen limitima',
'right-import'                => 'uvoženje strana s drugih vikija',
'right-importupload'          => 'uvoženje stranica iz poslatog fajla',
'right-patrol'                => 'markiranje tuđih izmena kao patroliranih',
'right-autopatrol'            => 'automatsko markiranje svojih izmena kao patroliranih',
'right-patrolmarks'           => 'viđenje oznaka za patroliranje unutar skorašnjih izmena',
'right-unwatchedpages'        => 'viđenje spiska nenadgledanih strana',
'right-mergehistory'          => 'spajanje istorija stranica',
'right-userrights'            => 'Uređivanje svih korisničkih prava',
'right-userrights-interwiki'  => 'izmena prava korisnika na drugim vikijima',
'right-siteadmin'             => 'zaključavanje i otključavanje baze podataka',
'right-override-export-depth' => 'Izvezi strane, uključujući povezane strane, do dubine 5',
'right-sendemail'             => 'Pošalji e-poštu ostalim korisnicima',
'right-passwordreset'         => 'pregledanje poruka za obnavljanje lozinke',

# User rights log
'rightslog'                  => 'Istorija korisničkih prava',
'rightslogtext'              => 'Ovo je istorija izmena korisničkih prava.',
'rightslogentry'             => 'je promenio prava za $1 sa $2 na $3',
'rightslogentry-autopromote' => 'je unapređen iz $2 u $3',
'rightsnone'                 => '(nema)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'čitanje ove strane',
'action-edit'                 => 'uredi ovu stranicu',
'action-createpage'           => 'pravljenje stranica',
'action-createtalk'           => 'pravljenje stranica za razgovor',
'action-createaccount'        => 'napravi nalog za ovog korisnika',
'action-minoredit'            => 'označi ovu izmenu malom',
'action-move'                 => 'premesti ovu stranicu',
'action-move-subpages'        => 'premesti ovu stranu i njene podstrane',
'action-move-rootuserpages'   => 'premesti bazne korisničke strane',
'action-movefile'             => 'premesti ovaj fajl',
'action-upload'               => 'pošalji ovu datoteku',
'action-reupload'             => 'poništi ovaj postojeći fajl',
'action-reupload-shared'      => 'piši preko verzije ovog fajla na deljenom skladištu',
'action-upload_by_url'        => 'pošalji ovu datoteku preko URL adrese',
'action-writeapi'             => 'koristi API za pisanje',
'action-delete'               => 'obriši ovu stranicu',
'action-deleterevision'       => 'obriši ovu reviziju',
'action-deletedhistory'       => 'pregledaj obrisanu istoriju ove stranice',
'action-browsearchive'        => 'pretraga obrisanih stranica',
'action-undelete'             => 'vrati ovu stranu',
'action-suppressrevision'     => 'pregledaj i vrati ovu skrivenu reviziju',
'action-suppressionlog'       => 'pregledaj ovu privatnu istoriju',
'action-block'                => 'blokiraj dalje izmene ovog korisnika',
'action-protect'              => 'menjanje nivoa zaštite za ovu stranu',
'action-rollback'             => 'brzo vraćanje izmena poslednjeg korisnika koji je menjao određenu stranicu',
'action-import'               => 'uvezi ovu stranu sa druge Viki',
'action-importupload'         => 'uvezi ovu stranu preko poslatog fajla',
'action-patrol'               => 'označavanje tuđih izmena kao patroliranih',
'action-autopatrol'           => 'automatsko patroliranje sopstvenih izmena',
'action-unwatchedpages'       => 'pregled spiska nenadgledanih stranica',
'action-mergehistory'         => 'spoji istoriju ove stranice',
'action-userrights'           => 'izmeni sva korisnička prava',
'action-userrights-interwiki' => 'izmeni prava korisnika sa drugih Vikija',
'action-siteadmin'            => 'zaključavanje ili otključavanje baze podataka',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'recentchanges'                     => 'Skorašnje izmene',
'recentchanges-legend'              => 'Podešavanja skorašnjih izmena',
'recentchangestext'                 => 'Ovde pratite najskorije izmene na vikiju.',
'recentchanges-feed-description'    => 'Pratite skorašnje izmene uz pomoć ovog fida.',
'recentchanges-label-newpage'       => 'Ovom izmenom je napravljena nova strana.',
'recentchanges-label-minor'         => 'Ovo je mala izmena',
'recentchanges-label-bot'           => 'Ovu izmenu je napravio bot',
'recentchanges-label-unpatrolled'   => 'Ova izmena još uvek nije patrolirana',
'rcnote'                            => "Ispod {{PLURAL:$1|je '''1''' promena|su poslednje '''$1''' promene|su poslednjih '''$1''' promena}} u {{PLURAL:$2|poslednjem danu|poslednja '''$2''' dana|poslednjih '''$2''' dana}}, od $5, $4.",
'rcnotefrom'                        => 'Ispod su promene od <b>$2</b> (do <b>$1</b> prikazano).',
'rclistfrom'                        => 'Pokaži nove promene počev od $1',
'rcshowhideminor'                   => '$1 male izmene',
'rcshowhidebots'                    => '$1 botove',
'rcshowhideliu'                     => '$1 prijavljene korisnike',
'rcshowhideanons'                   => '$1 anonimne korisnike',
'rcshowhidepatr'                    => '$1 patrolirane izmene',
'rcshowhidemine'                    => '$1 sopstvene izmene',
'rclinks'                           => 'Pokaži poslednjih $1 promena u poslednjih $2 dana<br />$3',
'diff'                              => 'razl',
'hist'                              => 'ist',
'hide'                              => 'sakrij',
'show'                              => 'pokaži',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 nadgleda {{PLURAL:$1|korisnik|korisnika}}]',
'rc_categories'                     => 'Ograniči na kategorije (razdvoji sa "|")',
'rc_categories_any'                 => 'Bilo koji',
'newsectionsummary'                 => '/* $1 */ novi odeljak',
'rc-enhanced-expand'                => 'Prikaži detalje (zahteva JavaScript)',
'rc-enhanced-hide'                  => 'Sakrij detalje',

# Recent changes linked
'recentchangeslinked'          => 'Srodne promene',
'recentchangeslinked-feed'     => 'Srodne promene',
'recentchangeslinked-toolbox'  => 'Srodne promene',
'recentchangeslinked-title'    => 'Srodne promene za "$1"',
'recentchangeslinked-noresult' => 'Nema izmena na povezanim stranicama za odabrani period.',
'recentchangeslinked-summary'  => "Ova posebna stranica pokazuje spisak poslednjih promena na stranicama koje su povezane (ili članovi određene kategorije).
Stranice sa [[Special:Watchlist|vašeg spiska nadgledanja]] su '''podebljane'''.",
'recentchangeslinked-page'     => 'Ime stranice:',
'recentchangeslinked-to'       => 'prikazivanje izmena prema stranama povezanih sa datom stranom',

# Upload
'upload'                      => 'Pošalji datoteku',
'uploadbtn'                   => 'Pošalji datoteku',
'reuploaddesc'                => 'Vrati me na obrazac za slanje',
'upload-tryagain'             => 'Pošalji izmenjeni opis fajla',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Morate biti [[Special:UserLogin|prijavljeni]] da biste slali datoteke.',
'upload_directory_missing'    => 'Direktorijum za prihvat fajlova ($1) nedostaje, a veb server ga ne može napraviti.',
'upload_directory_read_only'  => 'Na direktorijum za slanje ($1) server ne može da piše.',
'uploaderror'                 => 'Greška pri slanju',
'upload-recreate-warning'     => "'''Upozorenje: datoteka s tim nazivom je obrisana ili premeštena.'''

Istorija brisanja i premeštanja se nalazi ispod:",
'uploadtext'                  => "Koristite obrazac ispod da biste poslali datoteke.
Postojeće datoteke možete pronaći u [[Special:FileList|spisku poslatih datoteka]], ponovna slanja su zapisana u [[Special:Log/upload|istoriji slanja]], a brisanja u [[Special:Log/delete|istoriji brisanja]].

Datoteku dodajete na željenu stranicu koristeći sledeće obrasce:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Slika.jpg]]</nowiki></tt>''' za korišćenje celog izdanja datoteke
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Slika.png|200p|mini|levo|opis]]</nowiki></tt>''' za korišćenje široke uokvirene slike na levoj strani, veličine 200 piksela, zajedno s opisom
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fajl.ogg]]</nowiki></tt>''' za direktno povezivanje do datoteke bez prikazivanja",
'upload-permitted'            => 'Dozvoljeni tipovi fajlova su: $1.',
'upload-preferred'            => 'Poželjni tipovi fajlova su: $1.',
'upload-prohibited'           => 'Zabranjeni tipovi fajlova su: $1.',
'uploadlog'                   => 'istorija slanja',
'uploadlogpage'               => 'Istorija slanja',
'uploadlogpagetext'           => 'Ispod je spisak najskorijih slanja.',
'filename'                    => 'Naziv datoteke',
'filedesc'                    => 'Opis',
'fileuploadsummary'           => 'Opis:',
'filereuploadsummary'         => 'Izmene fajla:',
'filestatus'                  => 'Status autorskog prava:',
'filesource'                  => 'Izvor:',
'uploadedfiles'               => 'Poslate datoteke',
'ignorewarning'               => 'Ignoriši upozorenja i snimi datoteku.',
'ignorewarnings'              => 'Ignoriši sva upozorenja',
'minlength1'                  => 'Imena fajlova moraju imati najmanje jedan karakter.',
'illegalfilename'             => 'Fajl "$1" sadrži karaktere koji nisu dozvoljeni u nazivima stranica. Molimo Vas promenite ime fajla i ponovo ga pošaljite.',
'filename-toolong'            => 'Nazivi datoteka mogu imati najviše 240 bajtova.',
'badfilename'                 => 'Ime slike je promenjeno u "$1".',
'filetype-mime-mismatch'      => 'Ekstenzija „.$1“ ne odgovara prepoznatoj vrsti MIME datoteke ($2).',
'filetype-badmime'            => 'Nije dozvoljeno slati datoteke MIME tipa „$1“.',
'filetype-bad-ie-mime'        => 'Ovaj fajl ne može biti poslat zato što bi Internet Eksplorer mogao da ga detektuje "$1", što je onemogućen i potencijalno opasan tip fajla.',
'filetype-unwanted-type'      => "'''\".\$1\"''' nije poželjan tip fajla.
Poželjni {{PLURAL:\$3|tip fajla je|tipovi fajlova su}} \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|je zabranjena vrsta datoteke|su zabranjene vrste datoteka}}.
{{PLURAL:$3|Dozvoljena vrsta datoteke je|Dozvoljene vrste datoteka su}} $2.',
'filetype-missing'            => 'Ovaj fajl nema ekstenziju (npr ".jpg").',
'empty-file'                  => 'Poslata datoteka je prazna.',
'file-too-large'              => 'Poslata datoteka je prevelika.',
'filename-tooshort'           => 'Ime fajla je prekratko.',
'filetype-banned'             => 'Vrsta datoteke je zabranjena.',
'verification-error'          => 'Ova datoteka nije prošla proveru.',
'hookaborted'                 => 'Izmena je odbačena od strane kuke proširenja.',
'illegal-filename'            => 'Naziv datoteke je zabranjen.',
'overwrite'                   => 'Zamenjivanje postojeće datoteke je zabranjeno.',
'unknown-error'               => 'Došlo je do nepoznate greške.',
'tmp-create-error'            => 'Privremena datoteka nije napravljena.',
'tmp-write-error'             => 'Greška pri pisanju privremene datoteke.',
'large-file'                  => 'Preporučljivo je da fajlovi ne budu veći od $1; ovaj fajl je $2.',
'largefileserver'             => 'Ovaj fajl je veći nego što je podešeno da server dozvoli.',
'emptyfile'                   => 'Fajl koji ste poslali deluje da je prazan. Ovo je moguće zbog greške u imenu fajla. Molimo proverite da li stvarno želite da pošaljete ovaj fajl.',
'windows-nonascii-filename'   => 'Ovaj viki ne podržava nazive datoteka s posebnim znacima.',
'fileexists'                  => "Fajl sa ovim imenom već postoji.
Molimo proverite '''<tt>[[:$1]]</tt>''' ako niste sigurni da li želite da ga promenite.
[[$1|thumb]]",
'filepageexists'              => "Strana opisa ovog fajla je napravljena kao '''<tt>[[:$1]]</tt>''', iako sam fajl ne postoji.
Opis koga unosite se dakle neće pojaviti na strani opisa.
Da biste učinili da se Vaš opis ipak pojavi, trebalo bi da ga izmenite ručno.
[[$1|pregled]]",
'fileexists-extension'        => "Datoteka sa sličnim imenom već postoji: [[$2|thumb]]
* Naziv datoteke koju šaljete: '''<tt>[[:$1]]</tt>'''
* Naziv postojeće datoteke: '''<tt>[[:$2]]</tt>'''
Izaberite drugo ime.",
'fileexists-thumbnail-yes'    => "Ovaj fajl je najverovatnije umanjena verzija slike. [[$1|thumb]]
Molimo vas proverite fajl '''<tt>[[:$1]]</tt>'''.
Ukoliko je dati fajl ista slika ili originalna slika, nije potrebno da šaljete dodatno umanjenu verziju iste.",
'file-thumbnail-no'           => "Fajl počinje sa '''<tt>$1</tt>'''.
Pretpostavlja se da je ovo umanjena verzija slike.
Ukoliko imate ovu sliku u punoj rezolicuji, pošaljite je, a ukoliko nemate, promenite ime fajla.",
'fileexists-forbidden'        => 'Fajl sa ovim imenom već postoji, i preko njega se ne može pisati.
Ako ipak želite da pošaljete Vaš fajl, molimo Vas da se vratite nazad i upotrebite drugo ime. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fajl sa ovim imenom već postoji u zajedničkoj ostavi.
Molimo vratite se i pošaljite ovaj fajl pod novim imenom. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ovaj fajl je duplikat {{PLURAL:$1|sledećeg fajla|sledeđih fajlova}}:',
'file-deleted-duplicate'      => 'Fajl identičan ovom ([[:$1]]) je već bio obrisan.
Trebalo bi da proverite istoriju brisanja fajla pre ponovnog slanja.',
'uploadwarning'               => 'Upozorenje pri slanju',
'uploadwarning-text'          => 'Molimo Vas da izmenite opis fajla ispod i pokušate ponovo.',
'savefile'                    => 'Snimi fajl',
'uploadedimage'               => '{{GENDER:|je poslao|je poslala|je poslao}} „[[$1]]“',
'overwroteimage'              => 'poslata nova verzija "[[$1]]"',
'uploaddisabled'              => 'Slanje je onemogućeno.',
'copyuploaddisabled'          => 'Slanje putem URL adrese je onemogućeno.',
'uploadfromurl-queued'        => 'Slanje je stavljeno na spisak čekanja.',
'uploaddisabledtext'          => 'Slanje je onemogućeno.',
'php-uploaddisabledtext'      => 'Slanje fajlova je onemogućeno u samom PHP-u.
Molimo, proverite podešavanja file_uploads.',
'uploadscripted'              => 'Ovaj fajl sadrži HTML ili kod skripte koje internet brauzer može pogrešno da interpretira.',
'uploadvirus'                 => 'Fajl sadrži virus! Detalji: $1',
'uploadjava'                  => 'Datoteka je formata ZIP koji sadrži java .class element.
Slanje java datoteka nije dozvoljeno jer one mogu izazvati zaobilaženje sigurnosnih ograničenja.',
'upload-source'               => 'Izvorna datoteka',
'sourcefilename'              => 'Ime fajla izvora:',
'sourceurl'                   => 'Izvorna adresa:',
'destfilename'                => 'Ciljano ime fajla:',
'upload-maxfilesize'          => 'Najveća veličina datoteke: $1',
'upload-description'          => 'Opis fajla',
'upload-options'              => 'Opcije slanja',
'watchthisupload'             => 'Nadgledaj ovaj fajl',
'filewasdeleted'              => 'Fajl sa ovim imenom je ranije poslat, a kasnije obrisan. Trebalo bi da proverite $1 pre nego što nastavite sa ponovnim slanjem.',
'filename-bad-prefix'         => "Ime ovog fajla počinje sa '''\"\$1\"''', što nije opisno ime, najčešće je nazvan automatski sa digitalnim fotoaparatom. Molimo izaberite opisnije ime za vaš fajl.",
'upload-success-subj'         => 'Uspešno slanje',
'upload-success-msg'          => 'Datoteka iz [$2] je poslata. Dostupna je ovde: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Greška pri slanju',
'upload-failure-msg'          => 'Došlo je do problema pri slanju iz [$2]:

$1',
'upload-warning-subj'         => 'Upozorenje pri slanju',
'upload-warning-msg'          => 'Došlo je do greške pri slanju iz [$2]. Vratite se na [[Special:Upload/stash/$1|stranicu za slanje datoteka]] da biste rešili problem.',

'upload-proto-error'        => 'Nekorektni protokol',
'upload-proto-error-text'   => 'Slanje eksternih fajlova zahteva URLove koji počinju sa <code>http://</code> ili <code>ftp://</code>.',
'upload-file-error'         => 'Interna greška',
'upload-file-error-text'    => 'Došlo je do unutrašnje greške pri otvaranju privremene datoteke na serveru.
Kontaktirajte [[Special:ListUsers/sysop|administratora]].',
'upload-misc-error'         => 'Nepoznata greška pri slanju fajla',
'upload-misc-error-text'    => 'Nepoznata greška pri slanju fajla. Proverite da li je URL ispravan i pokušajte ponovo. Ako problem ostane, kontaktirajte sistem administratora.',
'upload-too-many-redirects' => 'URL je sadržao previše preusmerenja',
'upload-unknown-size'       => 'Nepoznata veličina',
'upload-http-error'         => 'Došlo je do HTTP greške: $1',

# ZipDirectoryReader
'zip-file-open-error' => 'Došlo je do greške pri otvaranju datoteke za proveru ZIP arhive.',
'zip-wrong-format'    => 'Navedena datoteka nije formata ZIP.',
'zip-bad'             => 'Datoteka je oštećena ili je nečitljiva ZIP datoteka.
Ne može se ispravno proveriti u vezi sa sigurnošću.',
'zip-unsupported'     => 'Datoteka je formata ZIP koji koristi mogućnosti koje ne podržava Medijaviki.
Ona se ne može ispravno proveriti u vezi sa sigurnošću.',

# Special:UploadStash
'uploadstash'          => 'Tajno skladište',
'uploadstash-summary'  => 'Ova stranica pruža pristup datotekama koje su poslate (ili se šalju), ali još uvek nisu objavljene. Ove datoteke su vidljive samo korisniku koji ga je poslao.',
'uploadstash-clear'    => 'Očisti sakrivene datoteke',
'uploadstash-nofiles'  => 'Nemate sakrivene datoteke.',
'uploadstash-badtoken' => 'Izvršavanje date radnje nije uspelo. Razlog tome može biti istek vremena za uređivanje. Pokušajte ponovo.',
'uploadstash-errclear' => 'Čišćenje datoteka nije uspelo.',
'uploadstash-refresh'  => 'Osveži spisak datoteka',

# img_auth script messages
'img-auth-accessdenied'     => 'Pristup onemogućen',
'img-auth-nopathinfo'       => 'Nedostaje PATH_INFO.
Vaš server nije podešen da prosleđuje ovakve podatke.
Možda je zasnovan na CGI-ju koji ne podržava img_auth.
Pogledajte [//www.mediawiki.org/wiki/Manual:Image_Authorization?uselang=sr-el odobravanje slika.]',
'img-auth-notindir'         => 'Zahtevana putanja nije u podešenoj fascikli za slanje.',
'img-auth-badtitle'         => 'Stvaranje ispravnog naslova za „$1“ nije uspelo.',
'img-auth-nologinnWL'       => 'Niste prijavljeni i „$1“ nije na spisku dozvoljenih.',
'img-auth-nofile'           => 'Fajl "$1" ne postoji.',
'img-auth-isdir'            => 'Pokušavate da pristupite fascikli „$1“.
Dozvoljen je samo pristup datotekama.',
'img-auth-streaming'        => 'Učitavanje „$1“.',
'img-auth-public'           => 'Svrha img_auth.php je da prosleđuje datoteke iz privatnih vikija.
Ovaj viki je postavljen kao javni.
Radi sigurnosti, img_auth.php je onemogućen.',
'img-auth-noread'           => 'Korisnik nema pristup za čitanje „$1“.',
'img-auth-bad-query-string' => 'Adresa ima neispravnu nisku upita.',

# HTTP errors
'http-invalid-url'      => 'Neispravan URL: $1',
'http-invalid-scheme'   => 'Adrese sa šemom „$1“ nisu podržane.',
'http-request-error'    => 'HTTP zahtev nije prošao zbog nepoznate greške.',
'http-read-error'       => 'HTTP greška pri čitanju.',
'http-timed-out'        => 'HTTP zahtev je prekoračio vreme za ispunjenje.',
'http-curl-error'       => 'Greška pri otvaranju URL: $1',
'http-host-unreachable' => 'URL je bio nedostupan.',
'http-bad-status'       => 'Došlo je do problema tokom zahteva HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL nije dostupan',
'upload-curl-error6-text'  => 'URL koji ste uneli nije dostupan. Uradite dupli klik na URL da proverite da li je adresa dostupna.',
'upload-curl-error28'      => 'Tajmaut greška',
'upload-curl-error28-text' => 'Sajtu je trebalo previše vremena da odgovori. Proverite da li sajt radi, ili sačekajte malo i pokušajte ponovo.',

'license'            => 'Licenca:',
'license-header'     => 'Licenca:',
'nolicense'          => 'Nema',
'license-nopreview'  => '(prikaz nije dostupan)',
'upload_source_url'  => ' (validan, javno dostupan URL)',
'upload_source_file' => ' (fajl na vašem računaru)',

# Special:ListFiles
'listfiles-summary'     => 'Ova posebna stranica prikazuje sve poslate datoteke.
Kad je poređano po korisniku, popis prikazuje samo one datoteke čije je poslednje izdanje postavio taj korisnik.',
'listfiles_search_for'  => 'Traži ime slike:',
'imgfile'               => 'fajl',
'listfiles'             => 'Spisak slika',
'listfiles_thumb'       => 'Umanjeni prikaz',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Ime',
'listfiles_user'        => 'Korisnik',
'listfiles_size'        => 'Veličina',
'listfiles_description' => 'Opis slike',
'listfiles_count'       => 'Verzije',

# File description page
'file-anchor-link'          => 'Slika',
'filehist'                  => 'Istorija datoteke',
'filehist-help'             => 'Kliknite na datum/vreme da vidite verziju fajla iz tog vremena.',
'filehist-deleteall'        => 'obriši sve',
'filehist-deleteone'        => 'obriši',
'filehist-revert'           => 'vrati',
'filehist-current'          => 'trenutno',
'filehist-datetime'         => 'Datum/Vreme',
'filehist-thumb'            => 'Umanjeni prikaz',
'filehist-thumbtext'        => 'Umanjeni prikaz za verziju od $1',
'filehist-nothumb'          => 'Bez pregleda slika',
'filehist-user'             => 'Korisnik',
'filehist-dimensions'       => 'Dimenzije',
'filehist-filesize'         => 'Veličina fajla',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Nema fajla',
'imagelinks'                => 'Upotreba datoteke',
'linkstoimage'              => '{{PLURAL:$1|Sledeća stranica koristi|$1 Sledeće stranice koriste}} ovaj fajl:',
'linkstoimage-more'         => 'Više od $1 {{PLURAL:$1|stranice se veše|stranica se vežu}} za ovaj fajl.
Sledeći spisak pokazuje stranice koje se vežu za ovaj fajl
[[Special:WhatLinksHere/$2|Potpuni spisak]] je dostupan takođe.',
'nolinkstoimage'            => 'Nema stranica koje koriste ovaj fajl.',
'morelinkstoimage'          => 'Vidi [[Special:WhatLinksHere/$1|više veza]] prema ovom fajlu.',
'linkstoimage-redirect'     => '$1 (preusmerenje datoteke) $2',
'duplicatesoffile'          => 'Sledeći {{PLURAL:$1|fajl je duplikat|$1 fajla su duplikati|$1 fajlova su duplikati}} ovog fajla ([[Special:FileDuplicateSearch/$2|više detalja]]):',
'sharedupload'              => 'Ovaj fajl je sa $1, i može se koristiti na drugim projektima.',
'sharedupload-desc-there'   => 'Ova datoteka se nalazi na $1 i može se koristiti i na drugim projektima.
Pogledajte [$2 stranicu za opis datoteke] za više detalja o njoj.',
'sharedupload-desc-here'    => 'Ova datoteka se nalazi na $1 i može se koristiti i na drugim projektima.
Opis na [$2 stranici datoteke] je prikazan ispod.',
'filepage-nofile'           => 'Ne postoji fajl pod tim imenom.',
'filepage-nofile-link'      => 'Ne postoji fajl sa ovim imenom, ali ga možete [$1 poslati].',
'uploadnewversion-linktext' => 'Pošaljite noviju verziju ovog fajla',
'shared-repo-from'          => 'od $1',
'shared-repo'               => 'deljeno skladište',

# File reversion
'filerevert'                => 'Vrati $1',
'filerevert-legend'         => 'Vrati fajl',
'filerevert-intro'          => "Vraćate '''[[Media:$1|$1]]''' na [$4 verziju od $3, $2].",
'filerevert-comment'        => 'Razlog:',
'filerevert-defaultcomment' => 'Vraćeno na verziju od $2, $1',
'filerevert-submit'         => 'Vrati',
'filerevert-success'        => "'''[[Media:$1|$1]]''' je vraćen na [$4 verziju od $3, $2].",
'filerevert-badversion'     => 'Ne postoji prethodna lokalna verzija fajla sa unesenim vremenom.',

# File deletion
'filedelete'                   => 'Obriši $1',
'filedelete-legend'            => 'Obriši fajl',
'filedelete-intro'             => "Na putu ste da obrišete fajl '''[[Media:$1|$1]]''' zajedno sa njegovom istorijom.",
'filedelete-intro-old'         => "Brišete verziju fajla '''[[Media:$1|$1]]''' od [$4 $3, $2].",
'filedelete-comment'           => 'Razlog:',
'filedelete-submit'            => 'Obriši',
'filedelete-success'           => "'''$1''' je obrisan.",
'filedelete-success-old'       => "Verzija fajla '''[[Media:$1|$1]]''' od $3, $2 je obrisana.",
'filedelete-nofile'            => "'''$1''' ne postoji.",
'filedelete-nofile-old'        => "Ne postoji skladištena verzija fajla '''$1''' sa datim osobinama.",
'filedelete-otherreason'       => 'Drugi/dodatni razlog:',
'filedelete-reason-otherlist'  => 'Drugi razlog',
'filedelete-reason-dropdown'   => '*Najčešći razlozi brisanja
** Kršenje autorskih prava
** Duplikat',
'filedelete-edit-reasonlist'   => 'Uredi razloge za brisanje',
'filedelete-maintenance'       => 'Brisanje i vraćanje fajlova je temporalno onemogućeno zbog održavanja.',
'filedelete-maintenance-title' => 'Ne mogu da obrišem datoteku',

# MIME search
'mimesearch'         => 'MIME pretraga',
'mimesearch-summary' => 'Ova stranica omogućava filtriranje datoteka prema njihovim vrstama MIME. Ulazni podaci: contenttype/subtype, npr. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME tip:',
'download'           => 'Preuzmi',

# Unwatched pages
'unwatchedpages' => 'Nenadgledane stranice',

# List redirects
'listredirects' => 'Spisak preusmerenja',

# Unused templates
'unusedtemplates'     => 'Neiskorišćeni šabloni',
'unusedtemplatestext' => 'Ova stranica navodi sve stranice u {{ns:template}} imenskom prostoru koje nisu uključene ni na jednoj drugoj stranici.
Ne zaboravite da proverite ostale poveznice ka šablonima pre nego što ih obrišete.',
'unusedtemplateswlh'  => 'ostale veze',

# Random page
'randompage'         => 'Slučajna stranica',
'randompage-nopages' => 'Nema stranica u {{PLURAL:$2|sledećem imenskom prostoru|sledećim imenskim prostorima}}: $1.',

# Random redirect
'randomredirect'         => 'Slučajno preusmerenje',
'randomredirect-nopages' => 'Nema preusmerenja u imenskom prostoru „$1”.',

# Statistics
'statistics'                   => 'Statistike',
'statistics-header-pages'      => 'Statistike stranice',
'statistics-header-edits'      => 'Statistike izmena',
'statistics-header-views'      => 'Vidi statistike',
'statistics-header-users'      => 'Statistike korisnika',
'statistics-header-hooks'      => 'Druge statistike',
'statistics-articles'          => 'Strane sa sadržajem',
'statistics-pages'             => 'Stranice',
'statistics-pages-desc'        => 'Sve strane na ovoj Viki, uključujući strane za razgovor, preusmerenja, itd.',
'statistics-files'             => 'Poslato datoteka',
'statistics-edits'             => 'Broj izmena stranica otkad {{SITENAME}} postoji',
'statistics-edits-average'     => 'Prosečan broj izmena po stranici',
'statistics-views-total'       => 'Ukupan broj pregleda',
'statistics-views-total-desc'  => 'Pregledi nepostojećih i posebnih stranica nisu uključeni',
'statistics-views-peredit'     => 'Pregledi po izmeni',
'statistics-users'             => 'Registrovani [[Special:ListUsers|korisnici]]',
'statistics-users-active'      => 'Aktivni korisnici',
'statistics-users-active-desc' => 'Korisnici koji su izvršili makar jednu akciju tokom {{PLURAL:$1|zadnjeg dana|$1 zadnjih dana}}',
'statistics-mostpopular'       => 'Najposećenije stranice',

'disambiguations'      => 'Stranice do višeznačnih odrednica',
'disambiguationspage'  => '{{ns:template}}:Višeznačna odrednica',
'disambiguations-text' => "Sledeće stranice imaju veze ka '''višeznačnim odrednicama'''. Potrebno je da upućuju na odgovarajući članak.

Stranica se smatra višeznačnom odrednicom ako koristi šablon koji je upućen sa stranice [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'Dvostruka preusmerenja',
'doubleredirectstext'               => 'Ova stranica pokazuje spisak stranica koje preusmeravaju na druge stranice preusmerenja.
Svaki red sadrži veze prema prvom i drugom preusmerenju, kao i ciljanu stranicu drugog preusmerenja, koja je obično „pravi“ članak, na koga prvo preusmerenje treba da pokazuje.
<del>Precrtani unosi</del> su već rešeni.',
'double-redirect-fixed-move'        => '[[$1]] je premešten, sada je preusmerenje na [[$2]]',
'double-redirect-fixed-maintenance' => 'Ispravljanje dvostrukih preusmerenja iz [[$1]] u [[$2]].',
'double-redirect-fixer'             => 'Popravljač preusmerenja',

'brokenredirects'        => 'Pokvarena preusmerenja',
'brokenredirectstext'    => 'Sledeća preusmerenja povezuju na nepostojeće strane:',
'brokenredirects-edit'   => 'uredi',
'brokenredirects-delete' => 'obriši',

'withoutinterwiki'         => 'Stranice bez jezičkih veza',
'withoutinterwiki-summary' => 'Sledeće stranice ne vežu ka drugim jezicima (međuviki):',
'withoutinterwiki-legend'  => 'prefiks',
'withoutinterwiki-submit'  => 'Prikaži',

'fewestrevisions' => 'Stranice sa najmanje revizija',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajta|bajtova}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategorije|kategorija}}',
'nlinks'                  => '$1 {{PLURAL:$1|veza|veze|veza}}',
'nmembers'                => '$1 {{PLURAL:$1|članak|članka|članaka}}',
'nrevisions'              => '$1 {{PLURAL:$1|revizija|revizije|revizija}}',
'nviews'                  => '$1 {{PLURAL:$1|puta|puta}} pogledano',
'nimagelinks'             => 'Koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'ntransclusions'          => 'koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'specialpage-empty'       => 'Nema rezultata za ovaj izveštaj.',
'lonelypages'             => 'Siročići',
'lonelypagestext'         => 'Sledeće stranice nisu povezane sa drugih stranica na ovom vikiju.',
'uncategorizedpages'      => 'Stranice bez kategorije',
'uncategorizedcategories' => 'Kategorije bez kategorija',
'uncategorizedimages'     => 'Slike bez kategorija',
'uncategorizedtemplates'  => 'Šabloni bez kategorija',
'unusedcategories'        => 'Neiskorišćene kategorije',
'unusedimages'            => 'Neiskorišćeni fajlovi',
'popularpages'            => 'Popularne stranice',
'wantedcategories'        => 'Tražene kategorije',
'wantedpages'             => 'Tražene stranice',
'wantedpages-badtitle'    => 'Neispavan naslov u nizu rezultata: $1',
'wantedfiles'             => 'Traženi fajlovi',
'wantedtemplates'         => 'Traženi šabloni',
'mostlinked'              => 'Najviše povezane strane',
'mostlinkedcategories'    => 'Najviše povezane kategorije',
'mostlinkedtemplates'     => 'Najpovezaniji šabloni',
'mostcategories'          => 'Članci sa najviše kategorija',
'mostimages'              => 'Najviše povezani fajlovi',
'mostrevisions'           => 'Članci sa najviše revizija',
'prefixindex'             => 'Sve stranice sa prefiksima',
'shortpages'              => 'Kratke stranice',
'longpages'               => 'Dugačke stranice',
'deadendpages'            => 'Stranice bez unutrašnjih veza',
'deadendpagestext'        => 'Sledeće stranice ne vežu na druge stranice na ovom vikiju.',
'protectedpages'          => 'Zaštićene stranice',
'protectedpages-indef'    => 'samo neograničene zaštite',
'protectedpages-cascade'  => 'Samo prenosive zaštite',
'protectedpagestext'      => 'Sledeće stranice su zaštićene od premeštanja ili uređivanja',
'protectedpagesempty'     => 'Nema zaštićenih stranica sa ovim parametrima.',
'protectedtitles'         => 'Zaštićeni naslovi',
'protectedtitlestext'     => 'Sledeći naslovi su zaštićeni od stvaranja:',
'protectedtitlesempty'    => 'Nema naslova koji su trenutno zaštićeni pomoću ovih parametara.',
'listusers'               => 'Spisak korisnika',
'listusers-editsonly'     => 'Prikaži korisnike koji imaju izmene',
'listusers-creationsort'  => 'Sortiraj po datumu pravljenja',
'usereditcount'           => '$1 {{PLURAL:$1|izmena|izmena}}',
'usercreated'             => '{{GENDER:$3|je napravio|je napravila|je napravio}} dana $1 u $2',
'newpages'                => 'Nove stranice',
'newpages-username'       => 'Korisničko ime:',
'ancientpages'            => 'Najstariji članci',
'move'                    => 'premesti',
'movethispage'            => 'premesti ovu stranicu',
'unusedimagestext'        => 'Sledeći fajlovi postoje, ali nisu ugneždeni ni u jednu stranicu.
Obratite pažnju da se drugi veb sajtovi mogu povezivati na sliku direktnim URL-om, i tako mogu još uvek biti prikazani ovde uprkos činjenici da više nisu u aktivnoj upotrebi.',
'unusedcategoriestext'    => 'Naredne strane kategorija postoje iako ih ni jedan drugi članak ili kategorija ne koriste.',
'notargettitle'           => 'Nema cilja',
'notargettext'            => 'Niste naveli ciljnu stranicu ili korisnika
na kome bi se izvela ova funkcija.',
'nopagetitle'             => 'Ne postoji takva stranica',
'nopagetext'              => 'Tražena stranica ne postoji.',
'pager-newer-n'           => '{{PLURAL:$1|noviji 1|novija $1|novijih $1}}',
'pager-older-n'           => '{{PLURAL:$1|stariji 1|starija $1|starijih $1}}',
'suppress'                => 'Revizor',
'querypage-disabled'      => 'Ova posebna stranica je onemogućena radi poboljšanja performansi.',

# Book sources
'booksources'               => 'Štampani izvori',
'booksources-search-legend' => 'Pretražite izvore knjiga',
'booksources-go'            => 'Idi',
'booksources-text'          => 'Ispod se nalazi spisak linkova ka sajtovima koji se bave prodajom novih i korišćenih knjiga, a koji bi mogli sadržati dodatne informacije o knjigama za koje se interesujete:',
'booksources-invalid-isbn'  => 'Naveden ISBN ne izgleda ispravno; proverite da nije došlo do greške prilikom kopiranja iz originalnog izvora.',

# Special:Log
'specialloguserlabel'  => 'Izvršilac:',
'speciallogtitlelabel' => 'Cilj (naslov ili korisnik):',
'log'                  => 'Protokoli',
'all-logs-page'        => 'Sve javne istorije',
'alllogstext'          => 'Kombinovani prikaz svih dostupnih istorija za {{SITENAME}}.
Možete suziti pregled odabirom tipa istorije, korisničkog imena ili tražene stranice.',
'logempty'             => 'Istorija je prazna.',
'log-title-wildcard'   => 'Traži naslove koji počinju sa ovim tekstom',

# Special:AllPages
'allpages'          => 'Sve stranice',
'alphaindexline'    => '$1 u $2',
'nextpage'          => 'Sledeća stranica ($1)',
'prevpage'          => 'Prethodna stranica ($1)',
'allpagesfrom'      => 'Prikaži stranice početno sa:',
'allpagesto'        => 'Prikazuje stranice koje se završavaju sa:',
'allarticles'       => 'Svi članci',
'allinnamespace'    => 'Sve stranice ($1 imenski prostor)',
'allnotinnamespace' => 'Sve stranice (koje nisu u $1 imenskom prostoru)',
'allpagesprev'      => 'Prethodna',
'allpagesnext'      => 'Sledeća',
'allpagessubmit'    => 'Idi',
'allpagesprefix'    => 'Prikaži strane sa prefiksom:',
'allpagesbadtitle'  => 'Dati naziv stranice nije dobar ili sadrži međujezički ili interviki prefiks. Moguće je da sadrži karaktere koji ne mogu da se koriste u nazivima.',
'allpages-bad-ns'   => '{{SITENAME}} nema imenski prostor "$1".',

# Special:Categories
'categories'                    => 'Kategorije stranica',
'categoriespagetext'            => '{{PLURAL:$1|Sledeća kategorija sadrži|Sledeće kategorije sadrže}} strane ili fajlove.
[[Special:UnusedCategories|Nekorišćene kategorije]] nisu prikazane ovde.
Takođe pogledajte [[Special:WantedCategories|tražene kategorije]].',
'categoriesfrom'                => 'Prikaži kategorije na:',
'special-categories-sort-count' => 'sortiraj po broju',
'special-categories-sort-abc'   => 'sortiraj azbučno',

# Special:DeletedContributions
'deletedcontributions'             => 'Obrisani prilozi',
'deletedcontributions-title'       => 'Obrisani prilozi',
'sp-deletedcontributions-contribs' => 'doprinosi',

# Special:LinkSearch
'linksearch'       => 'Pretraga spoljnih veza',
'linksearch-pat'   => 'Obrazac pretrage:',
'linksearch-ns'    => 'Imenski prostor:',
'linksearch-ok'    => 'Pretraga',
'linksearch-text'  => 'Mogu se koristiti džokeri poput „*.wikipedia.org“.<br />
Potreban je najviši domen, kao „*.org“.<br />
Podržani protokoli: <tt>$1</tt> (ne stavljajte u pretragu)',
'linksearch-line'  => 'stranica $1 je povezana sa stranice $2',
'linksearch-error' => 'Džokeri se mogu pojaviti samo na početku imena hosta.',

# Special:ListUsers
'listusersfrom'      => 'Prikaži korisnike počevši od:',
'listusers-submit'   => 'Prikaži',
'listusers-noresult' => 'Nije pronađen korisnik.',
'listusers-blocked'  => '(blokiran)',

# Special:ActiveUsers
'activeusers'            => 'Spisak aktivnih korisnika',
'activeusers-intro'      => 'Ovo je spisak korisnika koji su bili aktivni {{PLURAL:$1|prethodni dan|u poslednja $1 dana|u poslednjih $1 dana}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|izmena|izmene|izmena}} {{PLURAL:$3|prethodni dan|u poslednja $3 dana|u poslednjih $3 dana}}',
'activeusers-from'       => 'Prikaži korisnike počevši od:',
'activeusers-hidebots'   => 'Sakrij botove',
'activeusers-hidesysops' => 'Sakrij administratore',
'activeusers-noresult'   => 'Korisnik nije pronađen.',

# Special:Log/newusers
'newuserlogpage'     => 'Istorija otvaranja naloga',
'newuserlogpagetext' => 'Ovo je istorija stvaranja korisnika.',

# Special:ListGroupRights
'listgrouprights'                      => 'Prava korisničkih grupa',
'listgrouprights-summary'              => 'Sledi spisak korisničkih grupa definisanih na ovom Vikiju, sa njihovim pridruženim pravima pristupa.
Mogle bi Vas interesovati [[{{MediaWiki:Listgrouprights-helppage}}|dodatne informacije]] o pojedinačnim pravima pristupa.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dodeljeno pravo</span>
* <span class="listgrouprights-revoked">Ukinuto pravo</span>',
'listgrouprights-group'                => 'Grupa',
'listgrouprights-rights'               => 'Prava',
'listgrouprights-helppage'             => 'Help:Prava',
'listgrouprights-members'              => '(spisak članova)',
'listgrouprights-addgroup'             => 'može da doda {{PLURAL:$2|sledeću grupu|sledeće grupe}}: $1',
'listgrouprights-removegroup'          => 'može da obriše {{PLURAL:$2|sledeću grupu|sledeće grupe}}: $1',
'listgrouprights-addgroup-all'         => 'Može da doda sve grupe',
'listgrouprights-removegroup-all'      => 'Može da obriše sve grupe',
'listgrouprights-addgroup-self'        => 'Dodaj {{PLURAL:$2|grupa|grupe}} na svoj nalog: $1',
'listgrouprights-removegroup-self'     => 'Ukloni {{PLURAL:$2|grupa|grupe}} iz naloga: $1',
'listgrouprights-addgroup-self-all'    => 'Dodaj sve grupe u svoj nalog',
'listgrouprights-removegroup-self-all' => 'Ukloni sve grupe sa svog naloga',

# E-mail user
'mailnologin'          => 'Nema adrese za slanje',
'mailnologintext'      => 'Morate biti [[Special:UserLogin|prijavljeni]] i imati ispravnu adresu e-pošte u vašim [[Special:Preferences|podešavanjima]]
da biste slali e-poštu drugim korisnicima.',
'emailuser'            => 'Pošalji e-poštu ovom korisniku',
'emailpage'            => 'Pošalji e-pismo korisniku',
'emailpagetext'        => 'Možete koristiti ovaj formular da pošaljete e-poštu ovom korisniku.
Adresa e-pošte koju ste vi uneli u svojim [[Special:Preferences|korisničkim podešavanjima]] će se pojaviti kao "From" adresa poruke, tako da će primalac moći direktno da Vam odgovori.',
'usermailererror'      => 'Objekat pošte je vratio grešku:',
'defemailsubject'      => '{{SITENAME}} e-adresa {{GENDER:$1|korisnika|korisnice|korisnika}} $1',
'usermaildisabled'     => 'Korisnikova e-pošta je onemogućena',
'usermaildisabledtext' => 'Ne možete da šaljete e-poruke drugim korisnicima ovog vikija',
'noemailtitle'         => 'Nema adrese e-pošte',
'noemailtext'          => 'Ovaj korisnik nije naveo ispravnu adresu e-pošte.',
'nowikiemailtitle'     => 'Nije omogućeno slanje mejlova',
'nowikiemailtext'      => 'Ovaj korisnik je onemogućio slanje imejlova od drugih korisnika.',
'emailnotarget'        => 'Nepostojeće ili neispravno korisničko ime primaoca.',
'emailtarget'          => 'Unos korisničkog imena primaoca',
'emailusername'        => 'Korisničko ime:',
'emailusernamesubmit'  => 'Pošalji',
'email-legend'         => 'Pošaljite mejl drugom korisniku na {{SITENAME}}',
'emailfrom'            => 'Od:',
'emailto'              => 'Za:',
'emailsubject'         => 'Naslov:',
'emailmessage'         => 'Poruka:',
'emailsend'            => 'Pošalji',
'emailccme'            => 'Pošalji mi kopiju moje poruke u moje sanduče e-pošte.',
'emailccsubject'       => 'Kopija vaše poruke na $1: $2',
'emailsent'            => 'Poruka poslata',
'emailsenttext'        => 'Vaša poruka je poslata elektronskom poštom.',
'emailuserfooter'      => 'Ovaj imejl posla $1 saradniku $2 pomoću "Pošalji imejl" funkcije na sajtu "{{SITENAME}}".',

# User Messenger
'usermessage-summary' => 'Slanje sistemske poruke.',
'usermessage-editor'  => 'Uređivač sistemskih poruka',

# Watchlist
'watchlist'            => 'Spisak nadgledanja',
'mywatchlist'          => 'Spisak nadgledanja',
'watchlistfor2'        => 'Za $1 $2',
'nowatchlist'          => 'Nemate ništa na svom spisku nadgledanja.',
'watchlistanontext'    => 'Morate biti $1 da biste gledali ili menjali stavke na vašem spisku nadgledanja.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Morate biti [[Special:UserLogin|prijavljeni]] da biste menjali spisak nadgledanja.',
'addwatch'             => 'Dodaj na spisak nadgledanja',
'addedwatchtext'       => 'Stranica „[[:$1]]“ je dodata na vaš [[Special:Watchlist|spisak nadgledanja]].
Buduće izmene ove stranice i njene stranice za razgovor biće navedene ovde, a stranica će biti <b>podebljana</b> u [[Special:RecentChanges|spisku skorašnjih izmena]] da bi se lakše uočila.

Ukoliko budete želeli da uklonite stranicu sa spiska nadgledanja, kliknite opet na zvezdicu u gornjoj paleti.',
'removewatch'          => 'Ukloni sa spiska nadgledanja',
'removedwatchtext'     => 'Stranica „[[:$1]]“ je obrisana sa [[Special:Watchlist|Vašeg spiska nadgledanja]].',
'watch'                => 'nadgledaj',
'watchthispage'        => 'Nadgledaj ovu stranicu',
'unwatch'              => 'Prekini nadgledanje',
'unwatchthispage'      => 'Prekini nadgledanje',
'notanarticle'         => 'Nije članak',
'notvisiblerev'        => 'Revizija je obrisana',
'watchnochange'        => 'Ništa što nadgledate nije promenjeno u prikazanom vremenu.',
'watchlist-details'    => '{{PLURAL:$1|$1 stranica|$1 stranice|$1 stranica}} na vašem spisku nadgledanja, ne računajući stranice za razgovor.',
'wlheader-enotif'      => '* Obaveštavanje e-poštom je omogućeno.',
'wlheader-showupdated' => "* Stranice koje su izmenjene od kada ste ih poslednji put posetili su prikazane '''podebljano'''",
'watchmethod-recent'   => 'proveravam ima li nadgledanih stranica u skorašnjim izmenama',
'watchmethod-list'     => 'proveravam ima li skorašnjih izmena u nadgledanim stranicama',
'watchlistcontains'    => 'Vaš spisak nadgledanja sadrži $1 {{PLURAL:$1|stranicu|stranice|stranica}}.',
'iteminvalidname'      => "Problem sa stavkom '$1', neispravno ime...",
'wlnote'               => "Ispod {{PLURAL:$1|je poslednja izmena|su poslednje '''$1''' izmene|je poslednjih '''$1''' izmena}} u {{PLURAL:$2|prethodnom satu|prethodna '''$2''' sata|prethodnih '''$2''' sati}}, zaključno sa $3, $4.",
'wlshowlast'           => 'Prikaži poslednjih $1 sati $2 dana $3',
'watchlist-options'    => 'Podešavanja spiska nadgledanja',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Nadgledam...',
'unwatching'     => 'Uklanjanje nadgledanja...',
'watcherrortext' => 'Došlo je do greške pri promeni postavki vašeg spiska nadgledanja za „$1“.',

'enotif_mailer'                => '{{SITENAME}} pošta obaveštenja',
'enotif_reset'                 => 'Označi sve stranice kao posećene',
'enotif_newpagetext'           => 'Ovo je novi članak.',
'enotif_impersonal_salutation' => '{{SITENAME}} korisnik',
'changed'                      => 'promenjena',
'created'                      => 'napravljena',
'enotif_subject'               => '{{SITENAME}} stranica $PAGETITLE je bila $CHANGEDORCREATED od strane $PAGEEDITOR',
'enotif_lastvisited'           => 'Pogledajte $1 za sve promene od vaše poslednje posete.',
'enotif_lastdiff'              => 'Pogledajte $1 da vidite ovu izmenu.',
'enotif_anon_editor'           => 'anonimni korisnik $1',
'enotif_body'                  => 'Poštovani $WATCHINGUSERNAME,


Stranica $PAGETITLE na vikiju {{SITENAME}} je $CHANGEDORCREATED dana $PAGEEDITDATE od strane {{GENDER:$PAGEEDITOR|korisnika|korisnice|korisnika}} $PAGEEDITOR. Pogledajte $PAGETITLE_URL za tekuću izmenu.

$NEWPAGE

Sažetak: $PAGESUMMARY $PAGEMINOREDIT

Kontakt:
e-adresa: $PAGEEDITOR_EMAIL
viki: $PAGEEDITOR_WIKI

Neće biti drugih obaveštenja u slučaju daljih izmena ukoliko ne posetite ovu stranicu.
Možete i da poništite postavke obaveštenja za sve stranice u vašem spisku nadgledanja.

Srdačan pozdrav, {{SITENAME}}

--
Da biste promenili postavke u vezi s e-obaveštenjima, posetite
{{canonicalurl:{{#special:Preferences}}}}

Da biste promenili postavke u vezi sa spiskom nadgledanja, posetite
{{canonicalurl:{{#special:EditWatchlist}}}}

Da biste uklonili ovu stranicu sa spiska nadgledanja, posetite
$UNWATCHURL

Podrška i dalja pomoć:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Obriši stranicu',
'confirm'                => 'Potvrdi',
'excontent'              => "sadržaj je bio: '$1'",
'excontentauthor'        => "sadržaj je bio: '$1' (a jedinu izmenu je napravio '$2')",
'exbeforeblank'          => "sadržaj pre brisanja je bio: '$1'",
'exblank'                => 'stranica je bila prazna',
'delete-confirm'         => 'Obriši „$1“',
'delete-legend'          => 'Obriši',
'historywarning'         => "'''Upozorenje:''' Stranica koju želite da obrišete ima istoriju sa približno $1 {{PLURAL:$1|revizijom|revizija}}:",
'confirmdeletetext'      => 'Na putu ste da trajno obrišete stranicu
ili sliku zajedno sa njenom istorijom iz baze podataka.
Molimo vas potvrdite da nameravate da uradite ovo, da razumete
posledice, i da ovo radite u skladu sa
[[{{MediaWiki:Policy-url}}|pravilima]] {{SITENAME}}.',
'actioncomplete'         => 'Akcija je završena',
'actionfailed'           => 'Akcija nije uspela',
'deletedtext'            => 'Članak "$1" je obrisan.
Pogledajte $2 za zapis o skorašnjim brisanjima.',
'dellogpage'             => 'istorija brisanja',
'dellogpagetext'         => 'Ispod je spisak najskorijih brisanja.',
'deletionlog'            => 'istorija brisanja',
'reverted'               => 'Vraćeno na raniju reviziju',
'deletecomment'          => 'Razlog:',
'deleteotherreason'      => 'Drugi/dodatni razlog:',
'deletereasonotherlist'  => 'Drugi razlog',
'deletereason-dropdown'  => '*Najčešći razlozi brisanja
** Zahtev autora
** Kršenje autorskih prava
** Vandalizam',
'delete-edit-reasonlist' => 'Uredi razloge za brisanje',
'delete-toobig'          => 'Ova stranica ima veliku istoriju stranice, preko $1 {{PLURAL:$1|revizije|revizije|revizija}}.
Brisanje takvih stranica je zabranjeno radi preventive od slučajnog oštećenja sajta.',
'delete-warning-toobig'  => 'Ova strana ima veliku istoriju izmena, preko $1 {{PLURAL:$1|izmene|izmena}}.
Njeno brisanje bi moglo da omete operacije and bazom {{SITENAME}};
produžite oprezno.',

# Rollback
'rollback'          => 'Vrati izmene',
'rollback_short'    => 'Vrati',
'rollbacklink'      => 'vrati',
'rollbackfailed'    => 'Vraćanje nije uspelo',
'cantrollback'      => 'Ne mogu da vratim izmenu; poslednji autor je ujedno i jedini.',
'alreadyrolled'     => 'Ne mogu da vratim poslednju izmenu [[:$1]] od korisnika [[User:$2|$2]] ([[User talk:$2|razgovor]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); neko drugi je već izmenio ili vratio članak.

Poslednja izmena od korisnika [[User:$3|$3]] ([[User talk:$3|razgovor]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Komentar izmene je: \"''\$1''\".",
'revertpage'        => 'Vraćene su izmene {{GENDER:$2|korisnika|korisnice|korisnika}} [[Special:Contributions/$2|$2]] ([[User talk:$2|razgovor]]) na poslednju izmenu člana [[User:$1|$1]]',
'revertpage-nouser' => 'Vraćene su izmene korisnika (korisničko ime je uklonjeno) na poslednju izmenu člana [[User:$1|$1]]',
'rollback-success'  => 'Vraćene izmene od strane $1; na poslednju izmenu od strane $2.',

# Edit tokens
'sessionfailure-title' => 'Sesija je okončana',
'sessionfailure'       => 'Izgleda da postoji problem sa vašom seansom prijave;
ova akcija je prekinuta kao predostrožnost protiv preotimanja seansi.
Molimo kliknite "back" i ponovo učitajte stranu odakle ste došli, a onda pokušajte ponovo.',

# Protect
'protectlogpage'              => 'Istorija zaključavanja',
'protectlogtext'              => 'Ispod je spisak izmena u vidu zaštite stranica.
Pogledajte [[Special:ProtectedPages|spisak zaštićenih stranica]] za više detalja.',
'protectedarticle'            => '{{GENDER:|je zaštitio|je zaštitila|je zaštitio}} „[[$1]]“',
'modifiedarticleprotection'   => '{{GENDER:|je promenio|je promenila|je promenio}} nivo zaštite za „[[$1]]“',
'unprotectedarticle'          => '{{GENDER:|je uklonio|je uklonila|je uklonio}} zaštitu sa stranice „[[$1]]“',
'movedarticleprotection'      => 'premestio podešavanja zaštite sa "[[$2]]" na "[[$1]]"',
'protect-title'               => 'Nivo zaštite za „$1”',
'protect-title-notallowed'    => 'Pregled stepena zaštite za „$1“',
'prot_1movedto2'              => 'je promenio ime članku [[$1]] u [[$2]]',
'protect-badnamespace-title'  => 'Nezaštitljiv imenski prostor',
'protect-badnamespace-text'   => 'Stranice u ovom imenskom prostoru se ne mogu zaštititi.',
'protect-legend'              => 'Potvrdite zaštitu',
'protectcomment'              => 'Razlog:',
'protectexpiry'               => 'Ističe:',
'protect_expiry_invalid'      => 'Vreme isteka nije odgovarajuće.',
'protect_expiry_old'          => 'Vreme isteka je u prošlosti.',
'protect-unchain-permissions' => 'Otključaj daljnje postavke zaštite',
'protect-text'                => "Ovde možete pogledati i menjati nivo zaštite za stranicu '''$1'''.",
'protect-locked-blocked'      => "Ne možete menjati nivoe zaštite dok ste blokirani.
Ovo su trenutna podešavanja za stranicu '''$1''':",
'protect-locked-dblock'       => "Nivoi zaštite ne mogu biti promenjeni zbog aktivnog zaključavanja baze.
Ovo su trenutna podešavanja za stranu '''$1''':",
'protect-locked-access'       => "Vaš nalog nema dozvole za izmenu nivoa zaštite stranice.
Ovo su trenutna podešavanja za stranicu '''$1''':",
'protect-cascadeon'           => 'Ova stranica je trenutno zaštićena jer se nalazi na {{PLURAL:$1|stranici koja ima|stranicama koje imaju}} prenosivu zaštitu.
Možete promeniti stepen zaštite ove stranice, ali on neće uticati na prenosivu zaštitu.',
'protect-default'             => 'Dozvoli sve korisnike',
'protect-fallback'            => 'Zahteva "$1" ovlašćenja',
'protect-level-autoconfirmed' => 'Blokiraj nove i neregistrovane korisnike',
'protect-level-sysop'         => 'Samo za administratore',
'protect-summary-cascade'     => 'prenosiva zaštita',
'protect-expiring'            => 'ističe $1 (UTC)',
'protect-expiring-local'      => 'ističe $1',
'protect-expiry-indefinite'   => 'beskonačno',
'protect-cascade'             => 'Zaštićene stranice uključene u ovu stranicu (prenosiva zaštita)',
'protect-cantedit'            => 'Ne možete menjati nivoe zaštite za ovu stranicu, zbog toga što nemate ovlašćenja da je uređujete.',
'protect-othertime'           => 'Drugo vreme:',
'protect-othertime-op'        => 'drugo vreme',
'protect-existing-expiry'     => 'Trenutno vreme isteka: $3, $2',
'protect-otherreason'         => 'Drugi/dodatni razlog:',
'protect-otherreason-op'      => 'Drugi razlog',
'protect-dropdown'            => '* Razlozi zaštite
** Vandalizam
** Neželjene poruke
** Kontra-produktivne izmene
** Stranica sa velikim brojem poseta',
'protect-edit-reasonlist'     => 'Izmenite razloge zaštite',
'protect-expiry-options'      => '1 sat:1 hour,1 dan:1 day,1 nedelja:1 week,2 nedelje:2 weeks,1 mesec:1 month,3 meseca:3 months,6 meseci:6 months,1 godina:1 year,beskonačno:infinite',
'restriction-type'            => 'Ovlašćenje:',
'restriction-level'           => 'Nivo zaštite:',
'minimum-size'                => 'Min veličina',
'maximum-size'                => 'Maks veličina:',
'pagesize'                    => '(bajta)',

# Restrictions (nouns)
'restriction-edit'   => 'Uređivanje',
'restriction-move'   => 'Premeštanje',
'restriction-create' => 'Napravi',
'restriction-upload' => 'Pošalji',

# Restriction levels
'restriction-level-sysop'         => 'puna zaštita',
'restriction-level-autoconfirmed' => 'polu-zaštita',
'restriction-level-all'           => 'bilo koji nivo',

# Undelete
'undelete'                     => 'Pogledaj obrisane stranice',
'undeletepage'                 => 'Pogledaj i vrati obrisane stranice',
'undeletepagetitle'            => "'''Sledeće sadrži obrisane izmene članka: [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Pogledaj obrisane strane',
'undeletepagetext'             => '{{PLURAL:$1|Sledeća stranica je obrisana, ali je još uvek u arhivi i može biti vraćena|Sledeće $1 stranice su obrisane, ali su još uvek u arhivi i mogu biti vraćene|Sledećih $1 stranica je obrisano, ali su još uvek u arhivi i mogu biti vraćene}}.
Arhiva se povremeno čisti od ovakvih stranica.',
'undelete-fieldset-title'      => 'vraćanje verzija',
'undeleteextrahelp'            => "Da biste vratili celu istoriju stranice, ostavite sve kućice neoznačene i kliknite na dugme '''''{{int:undeletebtn}}'''''.
Ako želite da vratite određene izmene, označite ih i kliknite na '''''{{int:undeletebtn}}'''''.",
'undeleterevisions'            => '$1 {{PLURAL:$1|izmena je arhivirana|izmene su arhivirane|izmena je arhivirano}}',
'undeletehistory'              => 'Ako vratite stranicu, sve revizije će biti vraćene njenoj istoriji.
Ako je nova stranica istog imena napravljena od brisanja, vraćene revizije će se pojaviti u ranijoj istoriji.',
'undeleterevdel'               => 'Vraćanje neće biti izvedenu ukoliko bi rezultovalo delimičnim brisanjem revizije fajla ili vrha stranice.
U ovakvim slučajevima morate skinuti oznaku sa ili ponovo prikazati najnoviju obrisanu reviziju.',
'undeletehistorynoadmin'       => 'Ova stranica je obrisana. Razlog za brisanje se nalazi u opisu ispod, zajedno sa detaljima o korisniku koji je menjao ovu stranicu pre brisanja. Stvarni tekst ovih obrisanih revizija je dostupan samo administratorima.',
'undelete-revision'            => 'Obrisana revizija od $1 (u vreme $4, na $5) od strane korisnika $3:',
'undeleterevision-missing'     => 'Nekorektna ili nepostojeća revizija. Možda je vaš link pogrešan, ili je revizija restaurirana, ili obrisana iz arhive.',
'undelete-nodiff'              => 'Nema prethodnih izmena.',
'undeletebtn'                  => 'Vrati',
'undeletelink'                 => 'pogledaj/vrati',
'undeleteviewlink'             => 'pogledaj',
'undeletereset'                => 'Poništi',
'undeleteinvert'               => 'Obrni izbor',
'undeletecomment'              => 'Razlog:',
'undeletedrevisions'           => '{{PLURAL:$1|Izmena je vraćena|$1 izmene su vraćene|$1 izmena je vraćeno}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|revizija|revizije|revizija}} i $2 {{PLURAL:$2|fajl|fajla|fajlova}} vraćeno',
'undeletedfiles'               => '$1 {{PLURAL:$1|fajl vraćen|fajla vraćena|fajlova vraćeno}}',
'cannotundelete'               => 'Vraćanje obrisane verzije nije uspelo; neko drugi je vratio stranicu pre vas.',
'undeletedpage'                => "'''Stranica $1 je vraćena'''

Pogledajte [[Special:Log/delete|istoriju brisanja]] za zapise o skorašnjim brisanjima i vraćanjima.",
'undelete-header'              => 'Vidi [[Special:Log/delete|istoriju brisanja]] za nedavno obrisane stranice.',
'undelete-search-title'        => 'Pretraga obrisanih stranica',
'undelete-search-box'          => 'Pretraži obrisane stranice',
'undelete-search-prefix'       => 'Prikaži stranice koje počinju sa:',
'undelete-search-submit'       => 'Pretraga',
'undelete-no-results'          => 'Nema takvih strana u skladištu obrisanih.',
'undelete-filename-mismatch'   => 'Nije moguće obrisati verziju fajla od vremena $1: ime fajla se ne poklapa.',
'undelete-bad-store-key'       => 'Nije moguće vratiti izmenu verzije fajla vremena $1: fajl je nedeostajao pre brisanja.',
'undelete-cleanup-error'       => 'Greška prilikom brisanja nekorišćenog fajla iz arhive "$1".',
'undelete-missing-filearchive' => 'Nije moguće vratiti arhivu fajlova ID $1 zato što nije u bazi.
Možda je već bila vraćena.',
'undelete-error'               => 'Došlo je do greške pri vraćanju obrisane stranice',
'undelete-error-short'         => 'Greška pri vraćanju fajla: $1',
'undelete-error-long'          => 'Desila se greška pri vraćanju fajla:

$1',
'undelete-show-file-confirm'   => 'Da li ste sigurni da želite da vidite obrisanu reviziju fajla "<nowiki>$1</nowiki>" od $2 na $3?',
'undelete-show-file-submit'    => 'Da',

# Namespace form on various pages
'namespace'                     => 'Imenski prostor:',
'invert'                        => 'Obrni izbor',
'tooltip-invert'                => 'Označite ovu kućicu da biste sakrili izmene na stranicama u odabranom imenskom prostoru (i povezanim imenskim prostorima, ako je označeno)',
'namespace_association'         => 'Povezani imenski prostor',
'tooltip-namespace_association' => 'Označite ovu kućicu da biste uključili i razgovor ili imenski prostor teme koja je povezana s odabranim imenskim prostorom',
'blanknamespace'                => '(Glavno)',

# Contributions
'contributions'       => 'Prilozi korisnika',
'contributions-title' => 'Prilozi {{GENDER:$1|korisnika|korisnice|korisnika}} $1',
'mycontris'           => 'Prilozi',
'contribsub2'         => 'Za $1 ($2)',
'nocontribs'          => 'Nisu nađene promene koje zadovoljavaju ove uslove.',
'uctop'               => ' (vrh)',
'month'               => 'Za mesec (i ranije):',
'year'                => 'Od godine (i ranije):',

'sp-contributions-newbies'             => 'Prikaži samo priloge novih naloga',
'sp-contributions-newbies-sub'         => 'Za novajlije',
'sp-contributions-newbies-title'       => 'Prilozi novih korisnika',
'sp-contributions-blocklog'            => 'istorija blokiranja',
'sp-contributions-deleted'             => 'obrisani prilozi',
'sp-contributions-uploads'             => 'slanja',
'sp-contributions-logs'                => 'istorije',
'sp-contributions-talk'                => 'razgovor',
'sp-contributions-userrights'          => 'podešavanje korisničkih prava',
'sp-contributions-blocked-notice'      => 'Ovaj korisnik je trenutno blokiran.
Poslednji unos u dnevnik blokiranja je ponuđen ispod kao referenca:',
'sp-contributions-blocked-notice-anon' => 'Ovoj IP adresi je trenutno zabranjen pristup.
Izveštaj o blokiranim korisnicima se nalazi ispod:',
'sp-contributions-search'              => 'Pretraga priloga',
'sp-contributions-username'            => 'IP adresa ili korisničko ime:',
'sp-contributions-toponly'             => 'Prikaži samo najnovije izmene',
'sp-contributions-submit'              => 'Pretraga',

# What links here
'whatlinkshere'            => 'Šta je povezano ovde',
'whatlinkshere-title'      => 'Stranice koje su povezane na „$1“',
'whatlinkshere-page'       => 'Strana:',
'linkshere'                => "Sledeće stranice su povezane na '''[[:$1]]''':",
'nolinkshere'              => "Nijedna stranica nije povezana sa: '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nijedna stranica u odabranom imenskom prostoru se ne veže za '''[[:$1]]'''",
'isredirect'               => 'preusmerivač',
'istemplate'               => 'uključivanje',
'isimage'                  => 'veza ka datoteci',
'whatlinkshere-prev'       => '{{PLURAL:$1|prethodni|prethodnih $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sledeći|sledećih $1}}',
'whatlinkshere-links'      => '← veze',
'whatlinkshere-hideredirs' => '$1 preusmerenja',
'whatlinkshere-hidetrans'  => '$1 uključenja',
'whatlinkshere-hidelinks'  => '$1 veze',
'whatlinkshere-hideimages' => 'broj veza prema slikama: $1',
'whatlinkshere-filters'    => 'Filteri',

# Block/unblock
'autoblockid'                     => 'Samoblokiranje #$1',
'block'                           => 'Blokiraj korisnika',
'unblock'                         => 'Deblokiraj korisnika',
'blockip'                         => 'Blokiraj korisnika',
'blockip-title'                   => 'Blokiraj korisnika',
'blockip-legend'                  => 'Blokiraj korisnika',
'blockiptext'                     => 'Upotrebite donji upitnik da biste uklonili pravo pisanja
sa određene IP adrese ili korisničkog imena.
Ovo bi trebalo da bude urađeno samo da bi se sprečio vandalizam, i u skladu
sa [[{{MediaWiki:Policy-url}}|politikom]].
Unesite konkretan razlog ispod (na primer, navodeći koje
stranice su vandalizovane).',
'ipadressorusername'              => 'IP adresa ili korisničko ime',
'ipbexpiry'                       => 'Trajanje',
'ipbreason'                       => 'Razlog:',
'ipbreasonotherlist'              => 'Drugi razlog',
'ipbreason-dropdown'              => '*Najčešći razlozi blokiranja
** Unošenje lažnih informacija
** Uklanjanje sadržaja sa stranica
** Postavljanje veza ka spoljašnjim sajtovima
** Unos besmislica u stranice
** Nepoželjno ponašanje
** Upotreba više naloga
** Nepoželjno korisničko ime',
'ipb-hardblock'                   => 'Zabrani prijavljenim korisnicima da uređuju s ove IP adrese',
'ipbcreateaccount'                => 'Spreči pravljenje naloga',
'ipbemailban'                     => 'Zabranite korisniku da šalje e-poštu',
'ipbenableautoblock'              => 'Automatski blokiraj poslednju IP adresu ovog korisnika, i svaku sledeću adresu sa koje se pokuša uređivanje.',
'ipbsubmit'                       => 'Blokiraj ovog korisnika',
'ipbother'                        => 'Ostalo vreme',
'ipboptions'                      => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 nedelja:1 week,2 nedelje:2 weeks,1 mesec:1 month,3 meseca:3 months,6 meseci:6 months,1 godina:1 year,beskonačno:infinite',
'ipbotheroption'                  => 'ostalo',
'ipbotherreason'                  => 'Drugi/dodatni razlog:',
'ipbhidename'                     => 'Sakrij korisničko ime sa izmena i spiskova',
'ipbwatchuser'                    => 'nadgledanje korisničke stranice i stranice za razgovor ovog korisnika',
'ipb-disableusertalk'             => 'Zabrani ovom korisniku da uređuje svoju stranicu za razgovor dok je blokiran',
'ipb-change-block'                => 'Blokirajte korisnika ponovo sa ovim podešavanjima',
'ipb-confirm'                     => 'Potvrdi blokiranje',
'badipaddress'                    => 'Loša IP adresa',
'blockipsuccesssub'               => 'Blokiranje je uspelo',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] je {{GENDER:$1|blokiran|blokirana|blokiran}}.<br />
Pogledajte [[Special:BlockList|spisak blokiranih IP adresa]] za pregled blokiranja.',
'ipb-blockingself'                => 'Ovom radnjom ćete blokirati sebe! Jeste li sigurni da to želite?',
'ipb-confirmhideuser'             => 'Upravo ćete blokirati korisnika s uključenom mogućnošću „sakrij korisnika“. Ovim će korisničko ime biti sakriveno u svim spiskovima i izveštajima. Želite li to da uradite?',
'ipb-edit-dropdown'               => 'Menjajte razloge bloka',
'ipb-unblock-addr'                => 'Deblokiraj $1',
'ipb-unblock'                     => 'Deblokiraj korisničko ime ili IP adresu',
'ipb-blocklist'                   => 'Pogledajte postojeće blokove',
'ipb-blocklist-contribs'          => 'Prilozi za $1',
'unblockip'                       => 'Deblokiraj korisnika',
'unblockiptext'                   => 'Upotrebite donji upitnik da biste vratili pravo pisanja
ranije blokiranoj IP adresi ili korisničkom imenu.',
'ipusubmit'                       => 'Deblokiraj',
'unblocked'                       => '[[User:$1|$1]] je deblokiran',
'unblocked-range'                 => '$1 je {{GENDER:$1|deblokiran|deblokirana|deblokiran}}',
'unblocked-id'                    => 'Blok $1 je uklonjen',
'blocklist'                       => 'Blokirani korisnici',
'ipblocklist'                     => 'Blokirani korisnici',
'ipblocklist-legend'              => 'Pronađi blokiranog korisnika',
'blocklist-userblocks'            => 'Sakrij blokiranja naloga',
'blocklist-tempblocks'            => 'Sakrij privremena blokiranja',
'blocklist-addressblocks'         => 'Sakrij pojedinačna blokiranja IP adrese',
'blocklist-rangeblocks'           => 'Sakrij blokiranja opsega',
'blocklist-timestamp'             => 'Vreme',
'blocklist-target'                => 'Korisnik',
'blocklist-expiry'                => 'Ističe',
'blocklist-by'                    => 'Blokirao',
'blocklist-params'                => 'Zabranjene radnje',
'blocklist-reason'                => 'Razlog',
'ipblocklist-submit'              => 'Pretraga',
'ipblocklist-localblock'          => 'Lokalni blok',
'ipblocklist-otherblocks'         => 'Drugi {{PLURAL:$1|blok|blokovi}}',
'infiniteblock'                   => 'beskonačan',
'expiringblock'                   => 'Ističe na $1 u $2',
'anononlyblock'                   => 'samo anonimni',
'noautoblockblock'                => 'isključeno automatsko blokiranje',
'createaccountblock'              => 'zabranjeno otvaranje naloga',
'emailblock'                      => 'zabranjena e-pošta',
'blocklist-nousertalk'            => 'ne može da izmeni sopstvenu stranu za razgovor',
'ipblocklist-empty'               => 'Spisak blokova je prazan.',
'ipblocklist-no-results'          => 'Unesena IP adresa ili korisničko ime nije blokirano.',
'blocklink'                       => 'blokiraj',
'unblocklink'                     => 'deblokiraj',
'change-blocklink'                => 'promeni blok',
'contribslink'                    => 'prilozi',
'emaillink'                       => 'pošalji e-poruku',
'autoblocker'                     => 'Automatski ste blokirani jer je vašu IP adresu skoro koristio "[[User:$1|$1]]". Razlog za blokiranje korisnika $1 je: "\'\'\'$2\'\'\'".',
'blocklogpage'                    => 'Istorija blokiranja',
'blocklog-showlog'                => 'Ovaj korisnik je već bio blokiran.
Dnevnik blokiranja je ponuđen ispod  kao referenca:',
'blocklog-showsuppresslog'        => '{{GENDER:|Ovaj korisnik je ranije blokiran i sakriven|Ova korisnica je ranije blokirana i sakrivena|Ovaj korisnik je ranije blokiran i sakriven}}.
Istorija sakrivanja se nalazi ispod:',
'blocklogentry'                   => 'je blokirao „[[$1]]” sa vremenom isticanja blokade od $2 $3',
'reblock-logentry'                => 'je promenio podešavanja bloka za [[$1]] sa vremenom isteka $2 ($3)',
'blocklogtext'                    => 'Ovo je istorija blokiranja korisnika.
Automatski zabranjene IP adrese nisu ispisane ovde.
Pogledajte [[Special:BlockList|zabranjene IP adrese]] za spisak trenutnih blokova.',
'unblocklogentry'                 => '{{GENDER:|je deblokirao|je deblokirala|je deblokirao}} „$1“',
'block-log-flags-anononly'        => 'samo anonimni korisnici',
'block-log-flags-nocreate'        => 'onemogućeno otvaranje naloga',
'block-log-flags-noautoblock'     => 'isključeno automatsko blokiranje',
'block-log-flags-noemail'         => 'zabranjena e-pošta',
'block-log-flags-nousertalk'      => 'zabranjeno uređivanje sopstvene stranice za razgovor',
'block-log-flags-angry-autoblock' => 'omogućen je poboljšani autoblok',
'block-log-flags-hiddenname'      => 'korisničko ime sakriveno',
'range_block_disabled'            => 'Administratorska mogućnost da blokira blokove IP adresa je isključena.',
'ipb_expiry_invalid'              => 'Pogrešno vreme trajanja.',
'ipb_expiry_temp'                 => 'Sakriveni blokovi korisničkih imena moraju biti stalni.',
'ipb_hide_invalid'                => 'Nije bilo moguće sakriti ovaj nalog; Mora da ima previše izmena.',
'ipb_already_blocked'             => '"$1" je već blokiran',
'ipb-needreblock'                 => '$1 je već blokiran. Da li želite da promenite podešavanja?',
'ipb-otherblocks-header'          => 'Drugi {{PLURAL:$1|blok|blokovi}}',
'unblock-hideuser'                => 'Ne možete deblokirati ovog korisnika jer je njegovo korisničko ime sakriveno.',
'ipb_cant_unblock'                => 'Greška: ID bloka $1 nije nađen.
Moguće je da je već deblokiran.',
'ipb_blocked_as_range'            => 'Greška: IP $1 nije direktno blokiran i ne može biti deblokiran.
Međutim, blokiran je kao deo opsega $2, koji može biti deblokiran.',
'ip_range_invalid'                => 'Netačan blok IP adresa.',
'ip_range_toolarge'               => 'Opsezi blokiranja širi od /$1 nisu dozvoljeni.',
'blockme'                         => 'Blokiraj me',
'proxyblocker'                    => 'Bloker proksija',
'proxyblocker-disabled'           => 'Ova fukcija je isključena.',
'proxyblockreason'                => 'Vaša IP adresa je blokirana jer je ona otvoreni proksi. Molimo kontaktirajte vašeg Internet servis provajdera ili tehničku podršku i obavestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess'               => 'Urađeno.',
'sorbsreason'                     => 'Vaša IP adresa je na spisku kao otvoren proksi na DNSBL.',
'sorbs_create_account_reason'     => 'Vaša IP adresa se nalazi na spisku kao otvoreni proksi na DNSBL. Ne možete da napravite nalog',
'cant-block-while-blocked'        => 'Ne možete da blokirate druge korisnike dok ste blokirani.',
'cant-see-hidden-user'            => 'Član kome želite da zabranite pristup je već blokiran i sakriven.
S obzirom na to da nemate prava za sakrivanje korisnika, ne možete da vidite niti izmenite zabranu.',
'ipbblocked'                      => 'Ne možete zabraniti ili vratiti pristup drugim korisnicima jer ste i sami blokirani',
'ipbnounblockself'                => 'Nije vam dozvoljeno da deblokirate sebe',

# Developer tools
'lockdb'              => 'Zaključaj bazu',
'unlockdb'            => 'Otključaj bazu',
'lockdbtext'          => 'Zaključavanje baze će svim korisnicima ukinuti mogućnost izmene stranica,
promene korisničkih podešavanja, izmene spiska nadgledanja, i svega ostalog
što zahteva promene u bazi.
Molimo potvrdite da je ovo zaista ono što nameravate da uradite i da ćete
otključati bazu kada završite posao oko njenog održavanja.',
'unlockdbtext'        => 'Otključavanje baze će svim korisnicima vratiti mogućnost izmene stranica,
promene korisničkih podešavanja, izmene spiska nadgledanja, i svega ostalog
što zahteva promene u bazi.
Molimo potvrdite da je ovo zaista ono što nameravate da uradite.',
'lockconfirm'         => 'Da, zaista želim da zaključam bazu.',
'unlockconfirm'       => 'Da, zaista želim da otključam bazu.',
'lockbtn'             => 'Zaključaj bazu',
'unlockbtn'           => 'Otključaj bazu',
'locknoconfirm'       => 'Niste potvrdili svoju nameru.',
'lockdbsuccesssub'    => 'Baza je zaključana',
'unlockdbsuccesssub'  => 'Baza je otključana',
'lockdbsuccesstext'   => 'Baza podataka je zaključana.<br />
Setite se da je [[Special:UnlockDB|otključate]] kada završite sa održavanjem.',
'unlockdbsuccesstext' => 'Baza podataka je otključana.',
'lockfilenotwritable' => 'Po fajlu za zaključavanje baze podataka ne može da se piše. Da biste zaključali ili otključali bazu, po ovom fajlu mora da bude omogućeno pisanje od strane veb servera.',
'databasenotlocked'   => 'Baza podataka nije zaključana.',
'lockedbyandtime'     => '(od $1 dana $2 u $3)',

# Move page
'move-page'                    => 'Premesti $1',
'move-page-legend'             => 'Premeštanje stranice',
'movepagetext'                 => "Donji obrazac će preimenovati stranicu, premeštajući celu istoriju na novo ime.
Stari naslov postaće preusmerenje na novi naslov.
Možete automatski izmeniti preusmerenje do izvornog naslova.
Pogledajte [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|neispravna]] preusmerenja.
Na vama je odgovornost da veze i dalje idu tamo gde bi trebalo da idu.

Stranica '''neće''' biti premeštena ako već postoji stranica s tim imenom, osim ako je ona prazna, sadrži preusmerenje ili nema istoriju izmena.
To znači da možete vratiti stranicu na prethodno mesto ako pogrešite, ali ne možete zameniti postojeću stranicu.

'''Pažnja!'''
Ovo može predstavljati drastičnu i neočekivanu izmenu za popularnu stranicu;
dobro razmislite o posledicama pre nego što nastavite.",
'movepagetext-noredirectfixer' => "Donji obrazac će preimenovati stranicu, premeštajući celu istoriju na novo ime.
Stari naslov postaće preusmerenje na novi naslov.
Pogledajte [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|neispravna]] preusmerenja.
Na vama je odgovornost da veze i dalje idu tamo gde bi trebalo da idu.

Stranica '''neće''' biti premeštena ako već postoji stranica s tim imenom, osim ako je ona prazna, sadrži preusmerenje ili nema istoriju izmena.
To znači da možete vratiti stranicu na prethodno mesto ako pogrešite, ali ne možete zameniti postojeću stranicu.

'''Pažnja!'''
Ovo može predstavljati drastičnu i neočekivanu izmenu za popularnu stranicu;
dobro razmislite o posledicama pre nego što nastavite.",
'movepagetalktext'             => "Odgovarajuća stranica za razgovor, ako postoji, biće automatski premeštena istovremeno '''osim ako:'''
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odbeležite donju kućicu.

U tim slučajevima, moraćete ručno da premestite ili spojite stranicu ukoliko to želite.",
'movearticle'                  => 'Premesti stranicu',
'moveuserpage-warning'         => "'''Upozorenje:'''Spremate se da premestite korisničku stranicu. Upamtite da će se samo stranica premestiti, dok korisnik ''neće'' biti preimenovan.",
'movenologin'                  => 'Niste prijavljeni',
'movenologintext'              => 'Morate biti registrovani korisnik i [[Special:UserLogin|prijavljeni]]
da biste premestili stranicu.',
'movenotallowed'               => 'Nemate ovlašćenje za premeštanje stranica.',
'movenotallowedfile'           => 'Nemate potrebna prava, da biste premeštali fajlove.',
'cant-move-user-page'          => 'Nemate prava potrebna za premeštanje korisničkih strana (isključujući podstrane).',
'cant-move-to-user-page'       => 'Nemate prava potrebna za premeštanje neke strane na mesto korisničke strane (izuzevši korisničke podstrane).',
'newtitle'                     => 'Novi naslov',
'move-watch'                   => 'Nadgledaj ovu stranicu',
'movepagebtn'                  => 'premesti stranicu',
'pagemovedsub'                 => 'Premeštanje uspelo',
'movepage-moved'               => "'''Stranica „$1“ je preimenovana u „$2“!'''",
'movepage-moved-redirect'      => 'Preusmerenje je napravljeno.',
'movepage-moved-noredirect'    => 'Preusmerenje nije napravljeno.',
'articleexists'                => 'Stranica pod tim imenom već postoji, ili je
ime koje ste izabrali neispravno.
Molimo izaberite drugo ime.',
'cantmove-titleprotected'      => 'Ne možete premestiti stranicu na ovu lokaciju, zato što je novi naslov zaštićen za pravljenje',
'talkexists'                   => "'''Sama stranica je uspešno premeštena, ali
stranica za razgovor nije mogla biti premeštena jer takva već postoji na novom naslovu. Molimo vas da ih spojite ručno.'''",
'movedto'                      => 'premeštena na',
'movetalk'                     => 'Premesti "stranicu za razgovor" takođe, ako je moguće.',
'move-subpages'                => 'Premesti podstrane (do $1)',
'move-talk-subpages'           => 'Premesti podstranice stranice za razgovor (do $1)',
'movepage-page-exists'         => 'Stranica $1 već postoji ne može se automatski prepisati.',
'movepage-page-moved'          => 'Stranica $1 je preimenovana u $2.',
'movepage-page-unmoved'        => 'Stranica $1 ne može biti preimenovana u $2.',
'movepage-max-pages'           => 'Maksimum od $1 {{PLURAL:$1|strane|strana}} je bio premešten, i više od toga neće biti automatski premešteno.',
'movelogpage'                  => 'Istorija premeštanja',
'movelogpagetext'              => 'Ispod je spisak premeštanja članaka.',
'movesubpage'                  => '{{PLURAL:$1|Podstrana|Podstrana}}',
'movesubpagetext'              => 'Ova stranica ima $1 {{PLURAL:$1|podstranicu prikazanu|podstranica prikazanih}} ispod.',
'movenosubpage'                => 'Ova strana nema podstrana.',
'movereason'                   => 'Razlog:',
'revertmove'                   => 'vrati',
'delete_and_move'              => 'Obriši i premesti',
'delete_and_move_text'         => '==Potrebno brisanje==

Ciljani članak "[[:$1]]" već postoji. Da li želite da ga obrišete da biste napravili mesto za premeštanje?',
'delete_and_move_confirm'      => 'Da, obriši stranicu',
'delete_and_move_reason'       => 'Obrisano da se oslobodi mesto za premeštanje iz „[[$1]]“',
'selfmove'                     => 'Izvorni i ciljani naziv su isti; stranica ne može da se premesti preko same sebe.',
'immobile-source-namespace'    => 'Strane iz imenskog prostora "$1" nisu mogle biti premeštene',
'immobile-target-namespace'    => 'Ne može da premesti stranice u imenski prostor „$1”',
'immobile-target-namespace-iw' => 'Međuviki veza nije ispravna meta pri premeštanju strane.',
'immobile-source-page'         => 'Ova strana se ne može premestiti.',
'immobile-target-page'         => 'Ne može da se premesti na ciljani naslov.',
'imagenocrossnamespace'        => 'Fajl se ne može preimenovati u imenski prostor koji ne pripada fajlovima.',
'nonfile-cannot-move-to-file'  => 'Ne-datoteke ne možete premestiti u imenski prostor za datoteke',
'imagetypemismatch'            => 'Novi nastavak za fajlove se ne poklapa sa svojim tipom.',
'imageinvalidfilename'         => 'Ciljano ime fajla je pogrešno.',
'fix-double-redirects'         => 'Osvežava bilo koje preusmerenje koje veže na originalni naslov',
'move-leave-redirect'          => 'Ostavi preusmerenje nakon premeštanja',
'protectedpagemovewarning'     => "'''Napomena:''' Ova stranica je zaključana tako da samo korisnici sa administratorskim privilegijama mogu da je premeste.
Najskorija zabeleška istorije je priložena ispod kao dodatna informacija:",
'semiprotectedpagemovewarning' => "'''Napomena:''' Ova stranica je zaključana tako da samo registrovani korisnici mogu da je premeste.
Najnoviji izveštaj nalazi se ispod:",
'move-over-sharedrepo'         => '== Datoteka postoji ==
[[:$1]] se nalazi na deljenom skladištu. Ako premestite datoteku na ovaj naslov, to će zameniti deljenu datoteku.',
'file-exists-sharedrepo'       => 'Navedeni naziv datoteke se već koristi u deljenom skladištu.
Izaberite drugi naziv.',

# Export
'export'            => 'Izvezi stranice',
'exporttext'        => 'Možete izvesti tekst i istoriju izmena određene stranice ili grupe stranica u formatu XML.
Ovo onda može biti uvezeno u drugi viki koji koristi Medijaviki softver preko [[Special:Import|stranice za uvoz]].

Da biste izvezli stranice, unesite nazive u okviru ispod, s jednim naslovom po redu, i izaberite da li želite tekuću izmenu i sve ostale, ili samo tekuću izmenu s podacima o poslednjoj izmeni.

U drugom slučaju, možete koristiti i vezu, na primer [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za stranicu [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Uključi samo trenutnu reviziju, ne celu istoriju',
'exportnohistory'   => "----
'''Napomena:''' izvoženje pune istorije strana preko ovog formulara je onemogućeno zbog serverskih razloga.",
'exportlistauthors' => 'Uključi celokupan spisak doprinosilaca za svaku stranicu',
'export-submit'     => 'Izvoz',
'export-addcattext' => 'Dodaj stranice iz kategorije:',
'export-addcat'     => 'Dodaj',
'export-addnstext'  => 'Dodaj strane iz imenskog prostora:',
'export-addns'      => 'Dodaj',
'export-download'   => 'Sačuvaj kao fajl',
'export-templates'  => 'Uključuje šablone',
'export-pagelinks'  => 'Uključi povezane stranice do dubine od:',

# Namespace 8 related
'allmessages'                   => 'Sistemske poruke',
'allmessagesname'               => 'Ime',
'allmessagesdefault'            => 'Standardni tekst',
'allmessagescurrent'            => 'Trenutni tekst',
'allmessagestext'               => 'Ovo je spisak sistemskih poruka koje su u MedijaViki imenskom prostoru.
Posetite [//translatewiki.net translatewiki.net] ukoliko želite da pomognete u lokalizaciji.',
'allmessagesnotsupportedDB'     => "Ova stranica ne može biti upotrebljena zato što je '''\$wgUseDatabaseMessages''' isključen.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Filtriraj po stanju prilagođenosti:',
'allmessages-filter-unmodified' => 'Neizmenjene',
'allmessages-filter-all'        => 'Sve',
'allmessages-filter-modified'   => 'Izmenjene',
'allmessages-prefix'            => 'Filtriraj po prefiksu:',
'allmessages-language'          => 'Jezik:',
'allmessages-filter-submit'     => 'Idi',

# Thumbnails
'thumbnail-more'           => 'uvećaj',
'filemissing'              => 'Nedostaje fajl',
'thumbnail_error'          => 'Greška pri pravljenju umanjene slike: $1',
'djvu_page_error'          => 'DjVu strana je van opsega.',
'djvu_no_xml'              => 'Ne mogu preuzeti XML za DjVu fajl.',
'thumbnail_invalid_params' => 'Pogrešni parametri za malu sliku.',
'thumbnail_dest_directory' => 'Ne mogu napraviti odredišni direktorijum.',
'thumbnail_image-type'     => 'Tip slike nije podržan',
'thumbnail_gd-library'     => 'Nedovršene postavke GD biblioteke: nedostaje $1 funkcija',
'thumbnail_image-missing'  => 'Izgleda da fajl nedostaje: $1',

# Special:Import
'import'                     => 'Uvoz stranica',
'importinterwiki'            => 'Transviki uvoženje',
'import-interwiki-text'      => 'Odaberite viki i naziv stranice za uvoz. Datumi izmene i imena urednika će biti sačuvani. Svi transviki uvozi su zabeleženi u [[Special:Log/import|istoriji uvoza]].',
'import-interwiki-source'    => 'Izvorni viki/strana:',
'import-interwiki-history'   => 'Kopiraj sve izmene ove stranice',
'import-interwiki-templates' => 'Uključi sve šablone',
'import-interwiki-submit'    => 'Uvezi',
'import-interwiki-namespace' => 'Imenski prostor:',
'import-upload-filename'     => 'Naziv datoteke:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Izvezite datoteku s izvornog vikija koristeći [[Special:Export|izvoz]].
Sačuvajte je na računar i pošaljite ovde.',
'importstart'                => 'Uvoženje stranica u toku...',
'import-revision-count'      => '$1 {{PLURAL:$1|revizija|revizije|revizija}}',
'importnopages'              => 'Nema stranica za uvoz.',
'imported-log-entries'       => '{{PLURAL:$1|Uvezena je $1 stavka izveštaja|Uvezene su $1 stavke izveštaja|Uvezeno je $1 stavki izveštaja}}.',
'importfailed'               => 'Uvoz nije uspeo: $1',
'importunknownsource'        => 'Nepoznati tip izvora unosa',
'importcantopen'             => 'Neuspešno otvaranje fajla za uvoz',
'importbadinterwiki'         => 'Loša interviki veza',
'importnotext'               => 'Stranica je prazna ili bez teksta.',
'importsuccess'              => 'Uspešan uvoz!',
'importhistoryconflict'      => 'Postoji konfliktna istorija revizija (možda je ova stranica već uvezena ranije)',
'importnosources'            => 'Nije definisan nijedan izvor transviki uvoženja i direktna slanja istorija su onemogućena.',
'importnofile'               => 'Nije poslat nijedan uvozni fajl.',
'importuploaderrorsize'      => 'Slanje i unos fajla nisu uspeli. Fajl je veći nego što je dozvoljeno.',
'importuploaderrorpartial'   => 'Slanje fajla za unos podataka nije uspelo. Fajl je delimično stigao.',
'importuploaderrortemp'      => 'Slanje fajla za unos nije uspelo. Privremeni direktorijum nedostaje.',
'import-parse-failure'       => 'Neuspešno parsiranje unesenog XML-a.',
'import-noarticle'           => 'Nema stranica za uvoz!',
'import-nonewrevisions'      => 'Sve verzije su prethodno unesene.',
'xml-error-string'           => '$1 na liniji $2, kolona $3 (bajt $4): $5',
'import-upload'              => 'Slanje XML podataka',
'import-token-mismatch'      => 'Gubitak podataka o sesiji.
Molimo Vas da opet pokušate.',
'import-invalid-interwiki'   => 'Uvoz sa naznačenog Vikija ne može biti obavljen.',
'import-error-edit'          => 'Stranica „$1“ nije uvezena jer vam nije dozvoljeno da je uređujete.',
'import-error-create'        => 'Stranica „$1“ nije uvezena jer vam nije dozvoljeno da je napravite.',

# Import log
'importlogpage'                    => 'Istorija uvoza',
'importlogpagetext'                => 'Administrativni uvozi stranica sa istorijama izmena sa drugih vikija.',
'import-logentry-upload'           => 'uvezao [[$1]] putem slanja fajla',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'import-logentry-interwiki'        => 'premestio sa drugog vikija: $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|izmena|izmene|izmena}} od $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Vaša korisnička stranica',
'tooltip-pt-anonuserpage'         => 'Korisnička stranica IP adrese sa koje uređujete',
'tooltip-pt-mytalk'               => 'Vaša stranica za razgovor',
'tooltip-pt-anontalk'             => 'Razgovor o prilozima sa ove IP adrese',
'tooltip-pt-preferences'          => 'Moja korisnička podešavanja',
'tooltip-pt-watchlist'            => 'Spisak članaka koje nadgledate',
'tooltip-pt-mycontris'            => 'Spisak vaših priloga',
'tooltip-pt-login'                => 'Preporučuje se da se prijavite, ali nije obavezno',
'tooltip-pt-anonlogin'            => 'Preporučuje se da se prijavite, ali nije obavezno',
'tooltip-pt-logout'               => 'Odjavi me',
'tooltip-ca-talk'                 => 'Razgovor o članku',
'tooltip-ca-edit'                 => 'Možete urediti ovu stranicu. Molimo koristite pretpregled pre sačuvavanja.',
'tooltip-ca-addsection'           => 'Počnite novi odeljak',
'tooltip-ca-viewsource'           => 'Ova stranica je zaključana. Možete videti njen izvor',
'tooltip-ca-history'              => 'Prethodne verzije ove stranice',
'tooltip-ca-protect'              => 'Zaštiti ovu stranicu',
'tooltip-ca-unprotect'            => 'Promeni zaštitu ove stranice',
'tooltip-ca-delete'               => 'Obriši ovu stranicu',
'tooltip-ca-undelete'             => 'Vraćati izmene koje su načinjene pre brisanja stranice',
'tooltip-ca-move'                 => 'Premesti ovu stranicu',
'tooltip-ca-watch'                => 'Dodajte ovu stranicu na Vaš spisak nadgledanja',
'tooltip-ca-unwatch'              => 'Uklonite ovu stranicu sa Vašeg spiska nadgledanja',
'tooltip-search'                  => 'Pretražite ovaj viki',
'tooltip-search-go'               => 'Idi na stranu s tačnim imenom ako postoji.',
'tooltip-search-fulltext'         => 'Pretražite stranice sa ovim tekstom',
'tooltip-p-logo'                  => 'Glavna strana',
'tooltip-n-mainpage'              => 'Posetite glavnu stranu',
'tooltip-n-mainpage-description'  => 'Posetite glavnu stranu',
'tooltip-n-portal'                => 'O projektu, šta možete da radite i gde da pronađete stvari',
'tooltip-n-currentevents'         => 'Saznajte više o aktuelnostima',
'tooltip-n-recentchanges'         => 'Spisak skorašnjih izmena na vikiju',
'tooltip-n-randompage'            => 'Učitavaj slučajnu stranicu',
'tooltip-n-help'                  => 'Mesto gde možete da naučite nešto',
'tooltip-t-whatlinkshere'         => 'Spisak svih stranica koje vezuju na ovu',
'tooltip-t-recentchangeslinked'   => 'Skorašnje izmene na člancima povezanim sa ove stranice',
'tooltip-feed-rss'                => 'RSS fid za ovu stranicu',
'tooltip-feed-atom'               => 'Atom fid za ovu stranicu',
'tooltip-t-contributions'         => 'Pogledaj spisak priloga ovog korisnika',
'tooltip-t-emailuser'             => 'Pošalji elektronsku poštu ovom korisniku',
'tooltip-t-upload'                => 'Pošalji slike i medija fajlove',
'tooltip-t-specialpages'          => 'Spisak svih posebnih stranica',
'tooltip-t-print'                 => 'Izdanje za štampanje ove stranice',
'tooltip-t-permalink'             => 'stalna veza ka ovoj verziji stranice',
'tooltip-ca-nstab-main'           => 'Pogledajte članak',
'tooltip-ca-nstab-user'           => 'Pogledajte korisničku stranicu',
'tooltip-ca-nstab-media'          => 'Pogledajte medija stranicu',
'tooltip-ca-nstab-special'        => 'Ovo je posebna stranica, ne možete je menjati',
'tooltip-ca-nstab-project'        => 'Pregled stranice projekta',
'tooltip-ca-nstab-image'          => 'Pogledajte stranicu slike',
'tooltip-ca-nstab-mediawiki'      => 'Pogledajte sistemsku poruku',
'tooltip-ca-nstab-template'       => 'Pogledajte šablon',
'tooltip-ca-nstab-help'           => 'Pogledajte stranicu za pomoć',
'tooltip-ca-nstab-category'       => 'Pogledajte stranicu kategorije',
'tooltip-minoredit'               => 'Naznačite da se radi o maloj izmeni',
'tooltip-save'                    => 'Snimite Vaše izmene',
'tooltip-preview'                 => 'Pretpregled Vaših izmena, molimo koristite ovo pre snimanja!',
'tooltip-diff'                    => 'Prikaži koje promene ste napravili na tekstu.',
'tooltip-compareselectedversions' => 'Pogledaj razlike između dve odabrane verzije ove stranice.',
'tooltip-watch'                   => 'Dodajte ovu stranicu na Vaš spisak nadgledanja',
'tooltip-recreate'                => 'Ponovo napravite ovu stranu uprkos tome što je obrisana',
'tooltip-upload'                  => 'Počni slanje',
'tooltip-rollback'                => '"Vrati" vraća poslednje izmene kornisika u jednom koraku (kliku)',
'tooltip-undo'                    => '"Vrati" vraća izmenu i otvara formu za izmene za pregled. Dozvoljava dodavanje razloga u opis izmene.',
'tooltip-preferences-save'        => 'Sačuvaj postavke',
'tooltip-summary'                 => 'Unesite kratak sažetak',

# Stylesheets
'common.css'   => '/** CSS stavljen ovde će se odnositi na sve kože */',
'monobook.css' => '/* CSS stavljen ovde će se odnositi na korisnike Monobuk kože */',

# Metadata
'notacceptable' => 'Viki server ne može da pruži podatke u onom formatu koji vaš klijent može da pročita.',

# Attribution
'anonymous'        => 'Anonimni {{PLURAL:$1|korisnik|korisnici}} na {{SITENAME}}',
'siteuser'         => '{{SITENAME}} korisnik $1',
'anonuser'         => '{{SITENAME}} anonimni korisnik $1',
'lastmodifiedatby' => 'Ovu stranicu je poslednji put promenio $3 u $2, $1.',
'othercontribs'    => 'Bazirano na radu korisnika $1.',
'others'           => 'ostali',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|korisnik|korisnici}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anonimni korisnik|anonimnih korisnika}} $1',
'creditspage'      => 'Zasluge za stranicu',
'nocredits'        => 'Nisu dostupne informacije o zaslugama za ovu stranicu.',

# Spam protection
'spamprotectiontitle' => 'Filter za zaštitu od neželjenih poruka',
'spamprotectiontext'  => 'Stranica koju želite da sačuvate je blokirana od strane filtera za neželjene poruke.
Ovo je verovatno izazvano blokiranom vezom ka spoljašnjem sajtu.',
'spamprotectionmatch' => 'Sledeći tekst je izazvao naš filter za neželjene poruke: $1',
'spambot_username'    => 'Čišćenje neželjenih poruka u MedijaVikiju',
'spam_reverting'      => 'Vraćanje na staru reviziju koja ne sadrži veze ka $1',
'spam_blanking'       => 'Sve revizije su sadržale veze ka $1, pražnjenje',

# Info page
'pageinfo-title'            => 'Podaci o „$1“',
'pageinfo-header-edits'     => 'Izmena',
'pageinfo-header-watchlist' => 'Spisak nadgledanja',
'pageinfo-header-views'     => 'Pregleda',
'pageinfo-subjectpage'      => 'Stranica',
'pageinfo-talkpage'         => 'Stranica za razgovor',
'pageinfo-watchers'         => 'Broj pregledača',
'pageinfo-edits'            => 'Broj izmena',
'pageinfo-authors'          => 'Broj različitih autora',
'pageinfo-views'            => 'Broj pregleda',
'pageinfo-viewsperedit'     => 'Pregleda po izmeni',

# Patrolling
'markaspatrolleddiff'                 => 'Označi kao patroliran',
'markaspatrolledtext'                 => 'Označi ovaj članak kao patroliran',
'markedaspatrolled'                   => 'Označen kao patroliran',
'markedaspatrolledtext'               => 'Izabrana revizija od [[:$1]] je označena kao patrolirana.',
'rcpatroldisabled'                    => 'Patrola skorašnjih izmena onemogućena',
'rcpatroldisabledtext'                => 'Patrola skorašnjih izmena je trenutno onemogućena.',
'markedaspatrollederror'              => 'Nemoguće označiti kao patrolirano',
'markedaspatrollederrortext'          => 'Morate izabrati reviziju da biste označili kao patrolirano.',
'markedaspatrollederror-noautopatrol' => 'Nije ti dozvoljeno da obeležiš svoje izmene patroliranim.',

# Patrol log
'patrol-log-page'      => 'Istorija patroliranja',
'patrol-log-header'    => 'Ovo je istorija patroliranih revizija.',
'log-show-hide-patrol' => '$1 istorija patroliranja',

# Image deletion
'deletedrevision'                 => 'Obrisana stara revizija $1',
'filedeleteerror-short'           => 'Greška pri brisanju fajla: $1',
'filedeleteerror-long'            => 'Pojavile su se greške prilikom brisanja fajla:

$1',
'filedelete-missing'              => 'Fajl „$1” se ne može obrisati, zato što ne postoji.',
'filedelete-old-unregistered'     => 'Data verzija fajla "$1" ne postoji u bazi.',
'filedelete-current-unregistered' => 'Dati fajl "$1" ne postoji u bazi.',
'filedelete-archive-read-only'    => 'Veb server ne može pisati po kladišnom direktorijumu &quot;$1&quot;.',

# Browsing diffs
'previousdiff' => '← Starija izmena',
'nextdiff'     => 'Novija izmena →',

# Media information
'mediawarning'           => "'''Upozorenje''': Ovaj tip fajla bi mogao da sadrži štetan kod.
Njegovim izvršavanjem biste mogli da oštetite Vaš sistem.",
'imagemaxsize'           => "Ograničenje veličine slike:<br />''(za strane opisa fajlova)''",
'thumbsize'              => 'Veličina umanjenog prikaza :',
'widthheightpage'        => '$1×$2, $3 {{PLURAL:$3|stranica|stranice|stranica}}',
'file-info'              => 'veličina fajla: $1, MIME tip: $2',
'file-info-size'         => '$1 × $2 piksela, veličina fajla: $3, MIME tip: $4',
'file-info-size-pages'   => '$1 × $2 piksela, veličina: $3, MIME vrsta: $4, $5 {{PLURAL:$5|stranica|stranice|stranica}}',
'file-nohires'           => 'Nije dostupna veća rezolucija',
'svg-long-desc'          => 'SVG fajl, nominalno $1 × $2 piksela, veličina fajla: $3',
'show-big-image'         => 'Puna rezolucija',
'show-big-image-preview' => 'Veličina ovog prikaza: $1..',
'show-big-image-other'   => '{{PLURAL:$2|Druga rezolucija|Druge rezolucije}}: $1.',
'show-big-image-size'    => '$1×$2 piksela',
'file-info-gif-looped'   => 'petlja',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|frejm|frejmova}}',
'file-info-png-looped'   => 'petlja',
'file-info-png-repeat'   => 'ponovljeno $1 {{PLURAL:$1|put|puta|puta}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|kadar|kadra|kadrova}}',

# Special:NewFiles
'newimages'             => 'Galerija novih slika',
'imagelisttext'         => "Ispod je spisak od '''$1''' {{PLURAL:$1|fajla|fajla|fajlova}} poređanih $2.",
'newimages-summary'     => 'Ova posebna stranica prikazuje poslednje poslate datoteke.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Ime fajla (ili njegov deo):',
'showhidebots'          => '($1 botove)',
'noimages'              => 'Nema ništa da se vidi',
'ilsubmit'              => 'Traži',
'bydate'                => 'po datumu',
'sp-newimages-showfrom' => 'Prikaži nove fajlove počevši od $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekunda|$1 sekunde|$1 sekundi}}',
'minutes' => '{{PLURAL:$1|$1 minut|$1 minuta|$1 minuta}}',
'hours'   => '{{PLURAL:$1|$1 sat|$1 sata|$1 sati}}',
'days'    => '{{PLURAL:$1|$1 dan|$1 dana|$1 dana}}',
'ago'     => 'pre $1',

# Bad image list
'bad_image_list' => 'Format je sledeći:

Razmatraju se samo stavke u spisku (linije koje počinju sa *).
Prva veza u liniji mora biti veza na visoko rizičnu sliku.
Sve druge veze u istoj liniji se smatraju izuzecima tj. članci u kojima se slika može prikazati.',

# Variants for Serbian language
'variantname-sr-ec' => 'Ћирилица',
'variantname-sr-el' => 'Latinica',
'variantname-sr'    => 'disable',

# Metadata
'metadata'          => 'Metapodaci',
'metadata-help'     => 'Ovaj fajl sadrži dodatne informacije, koje su verovatno dodali digitalni fotoaparat ili skener koji su korišćeni da bi se napravila ili digitalizovala slika. Ako je prvobitno stanje fajla promenjeno, moguće je da neki detalji ne opisuju u potpunosti izmenjenu sliku.',
'metadata-expand'   => 'Pokaži detalje',
'metadata-collapse' => 'Sakrij detalje',
'metadata-fields'   => 'Polja za metapodatke slike navedena u ovoj poruci će biti uključena na stranici za slike kada se skupi tabela metapodataka. Ostala polja će biti sakrivena po podrazumevanim postavkama.
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
'exif-imagewidth'                  => 'Širina',
'exif-imagelength'                 => 'Visina',
'exif-bitspersample'               => 'Bitova po komponenti',
'exif-compression'                 => 'Šema kompresije',
'exif-photometricinterpretation'   => 'Kompozicija piksela',
'exif-orientation'                 => 'Orijentacija',
'exif-samplesperpixel'             => 'Broj komponenti',
'exif-planarconfiguration'         => 'Princip rasporeda podataka',
'exif-ycbcrsubsampling'            => 'Odnos komponente Y prema C',
'exif-ycbcrpositioning'            => 'Razmeštaj komponenata Y i C',
'exif-xresolution'                 => 'Horizonatalna rezolucija',
'exif-yresolution'                 => 'Vertikalna rezolucija',
'exif-stripoffsets'                => 'Položaj bloka podataka',
'exif-rowsperstrip'                => 'Broj redova u bloku',
'exif-stripbytecounts'             => 'Veličina kompresovanog bloka',
'exif-jpeginterchangeformat'       => 'Udaljenost JPEG pregleda od početka fajla',
'exif-jpeginterchangeformatlength' => 'Količina bajtova JPEG pregleda',
'exif-whitepoint'                  => 'Hromacitet bele tačke',
'exif-primarychromaticities'       => 'Hromacitet primarnih boja',
'exif-ycbcrcoefficients'           => 'Matrični koeficijenti transformacije kolor prostora',
'exif-referenceblackwhite'         => 'Mesto bele i crne tačke',
'exif-datetime'                    => 'Datum poslednje promene fajla',
'exif-imagedescription'            => 'Ime slike',
'exif-make'                        => 'Proizvođač kamere',
'exif-model'                       => 'Model kamere',
'exif-software'                    => 'Korišćen softver',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Nosilac prava',
'exif-exifversion'                 => 'Exif verzija',
'exif-flashpixversion'             => 'Podržana verzija Flešpiksa',
'exif-colorspace'                  => 'Prostor boje',
'exif-componentsconfiguration'     => 'Značenje svake od komponenti',
'exif-compressedbitsperpixel'      => 'Mod kompresije slike',
'exif-pixelydimension'             => 'Širina slike',
'exif-pixelxdimension'             => 'Visina slike',
'exif-usercomment'                 => 'Korisnički komentar',
'exif-relatedsoundfile'            => 'Povezani zvučni zapis',
'exif-datetimeoriginal'            => 'Datum i vreme slikanja',
'exif-datetimedigitized'           => 'Datum i vreme digitalizacije',
'exif-subsectime'                  => 'Deo sekunde u kojem je slikano',
'exif-subsectimeoriginal'          => 'Deo sekunde u kojem je fotografisano',
'exif-subsectimedigitized'         => 'Deo sekunde u kojem je digitalizovano',
'exif-exposuretime'                => 'Ekspozicija',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'F broj otvora blende',
'exif-exposureprogram'             => 'Program ekspozicije',
'exif-spectralsensitivity'         => 'Spektralna osetljivost',
'exif-isospeedratings'             => 'ISO vrednost',
'exif-shutterspeedvalue'           => 'Brzina zatvarača',
'exif-aperturevalue'               => 'Otvor blende',
'exif-brightnessvalue'             => 'Osvetljenost',
'exif-exposurebiasvalue'           => 'Kompenzacija ekspozicije',
'exif-maxaperturevalue'            => 'Minimalni broj otvora blende',
'exif-subjectdistance'             => 'Udaljenost do objekta',
'exif-meteringmode'                => 'Režim merača vremena',
'exif-lightsource'                 => 'Izvor svetlosti',
'exif-flash'                       => 'Blic',
'exif-focallength'                 => 'Fokusna daljina sočiva',
'exif-subjectarea'                 => 'Položaj i površina objekta snimka',
'exif-flashenergy'                 => 'Energija blica',
'exif-focalplanexresolution'       => 'Vodoravna rezolucija fokusne ravni',
'exif-focalplaneyresolution'       => 'Horizonatlna rezolucija fokusne ravni',
'exif-focalplaneresolutionunit'    => 'Jedinica rezolucije fokusne ravni',
'exif-subjectlocation'             => 'Položaj subjekta',
'exif-exposureindex'               => 'Indeks ekspozicije',
'exif-sensingmethod'               => 'Tip senzora',
'exif-filesource'                  => 'Izvorni fajl',
'exif-scenetype'                   => 'Tip scene',
'exif-customrendered'              => 'Dodatna obrada slike',
'exif-exposuremode'                => 'Režim izbora ekspozicije',
'exif-whitebalance'                => 'Balans bele boje',
'exif-digitalzoomratio'            => 'Odnos digitalnog zuma',
'exif-focallengthin35mmfilm'       => 'Ekvivalent fokusne daljine za 35 mm film',
'exif-scenecapturetype'            => 'Tip scene na snimku',
'exif-gaincontrol'                 => 'Kontrola osvetljenosti',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Saturacija',
'exif-sharpness'                   => 'Oštrina',
'exif-devicesettingdescription'    => 'Opis podešavanja uređaja',
'exif-subjectdistancerange'        => 'Raspon udaljenosti subjekata',
'exif-imageuniqueid'               => 'Jedinstveni identifikator slike',
'exif-gpsversionid'                => 'Verzija bloka GPS-informacije',
'exif-gpslatituderef'              => 'Severna ili južna širina',
'exif-gpslatitude'                 => 'Širina',
'exif-gpslongituderef'             => 'Istočna ili zapadna dužina',
'exif-gpslongitude'                => 'Dužina',
'exif-gpsaltituderef'              => 'Visina ispod ili iznad mora',
'exif-gpsaltitude'                 => 'Visina',
'exif-gpstimestamp'                => 'Vreme po GPS-u (atomski sat)',
'exif-gpssatellites'               => 'Upotrebljeni sateliti',
'exif-gpsstatus'                   => 'Status prijemnika',
'exif-gpsmeasuremode'              => 'Režim merenja',
'exif-gpsdop'                      => 'Preciznost merenja',
'exif-gpsspeedref'                 => 'Jedinica brzine',
'exif-gpsspeed'                    => 'Brzina GPS prijemnika',
'exif-gpstrackref'                 => 'Tip azimuta prijemnika (pravi ili magnetni)',
'exif-gpstrack'                    => 'Azimut prijemnika',
'exif-gpsimgdirectionref'          => 'Tip azimuta slike (pravi ili magnetni)',
'exif-gpsimgdirection'             => 'Azimut slike',
'exif-gpsmapdatum'                 => 'Korišćeni geodetski koordinatni sistem',
'exif-gpsdestlatituderef'          => 'Indeks geografske širine objekta',
'exif-gpsdestlatitude'             => 'Geografska širina objekta',
'exif-gpsdestlongituderef'         => 'Indeks geografske dužine objekta',
'exif-gpsdestlongitude'            => 'Geografska dužina objekta',
'exif-gpsdestbearingref'           => 'Indeks azimuta objekta',
'exif-gpsdestbearing'              => 'Azimut objekta',
'exif-gpsdestdistanceref'          => 'Merne jedinice udaljenosti objekta',
'exif-gpsdestdistance'             => 'Udaljenost objekta',
'exif-gpsprocessingmethod'         => 'Ime metode obrade GPS podataka',
'exif-gpsareainformation'          => 'Ime GPS područja',
'exif-gpsdatestamp'                => 'GPS datum',
'exif-gpsdifferential'             => 'GPS diferencijalna korekcija',
'exif-jpegfilecomment'             => 'Komentar na datoteku JPEG',
'exif-keywords'                    => 'Ključne reči',
'exif-worldregioncreated'          => 'Oblast sveta gde je slikana fotografija',
'exif-countrycreated'              => 'Zemlja gde je slikana fotografija',
'exif-countrycodecreated'          => 'Kôd zemlje gde je slikana fotografija',
'exif-provinceorstatecreated'      => 'Pokrajina ili država gde je slikana fotografija',
'exif-citycreated'                 => 'Grad gde je slikana fotografija',
'exif-sublocationcreated'          => 'Oblast grada gde je slikana fotografija',
'exif-worldregiondest'             => 'Prikazana oblast sveta',
'exif-countrydest'                 => 'Prikazana zemlja',
'exif-countrycodedest'             => 'Prikazani kôd zemlje',
'exif-provinceorstatedest'         => 'Prikazana pokrajina ili država',
'exif-citydest'                    => 'Prikazani grad',
'exif-sublocationdest'             => 'Prikazana oblast grada',
'exif-objectname'                  => 'Kratak naslov',
'exif-specialinstructions'         => 'Posebna uputstva',
'exif-headline'                    => 'Naslov',
'exif-credit'                      => 'Zasluge/pružalac usluga',
'exif-source'                      => 'Izvor',
'exif-editstatus'                  => 'Urednički status slike',
'exif-urgency'                     => 'Hitnost',
'exif-fixtureidentifier'           => 'Naziv rubrike',
'exif-locationdest'                => 'Prikazana lokacija',
'exif-locationdestcode'            => 'Kôd prikazane lokacije',
'exif-objectcycle'                 => 'Doba dana za koji je medij namenjen',
'exif-contact'                     => 'Podaci za kontakt',
'exif-writer'                      => 'Pisac',
'exif-languagecode'                => 'Jezik',
'exif-iimversion'                  => 'IIM izdanje',
'exif-iimcategory'                 => 'Kategorija',
'exif-iimsupplementalcategory'     => 'Dopunske kategorije',
'exif-datetimeexpires'             => 'Ne koristi nakon',
'exif-datetimereleased'            => 'Objavljeno',
'exif-originaltransmissionref'     => 'Izvorni prenos kôda lokacije',
'exif-identifier'                  => 'Oznaka',
'exif-lens'                        => 'Korišćeni objektiv',
'exif-serialnumber'                => 'Serijski broj kamere',
'exif-cameraownername'             => 'Vlasnik kamere',
'exif-label'                       => 'Naziv',
'exif-datetimemetadata'            => 'Datum poslednje izmene metapodataka',
'exif-nickname'                    => 'Neformalni naziv slike',
'exif-rating'                      => 'Ocena (od 1 do 5)',
'exif-rightscertificate'           => 'Potvrda za upravljanje pravima',
'exif-copyrighted'                 => 'Status autorskog prava',
'exif-copyrightowner'              => 'Nosilac autorskog prava',
'exif-usageterms'                  => 'Pravila korišćenja',
'exif-webstatement'                => 'Izjava o autorskom pravu',
'exif-originaldocumentid'          => 'Jedinstveni IB izvornog dokumenta',
'exif-licenseurl'                  => 'Adresa licence za autorska prava',
'exif-morepermissionsurl'          => 'Rezervni podaci o licenciranju',
'exif-attributionurl'              => 'Pri ponovnom korišćenju ovog rada, koristite vezu do',
'exif-preferredattributionname'    => 'Pri ponovnom korišćenju ovog rada, postavite zasluge',
'exif-pngfilecomment'              => 'Komentar na datoteku PNG',
'exif-disclaimer'                  => 'Odricanje odgovornosti',
'exif-contentwarning'              => 'Upozorenje o sadržaju',
'exif-giffilecomment'              => 'Komentar na datoteku GIF',
'exif-intellectualgenre'           => 'Vrsta stavke',
'exif-subjectnewscode'             => 'Kôd predmeta',
'exif-scenecode'                   => 'IPTC kôd scene',
'exif-event'                       => 'Prikazani događaj',
'exif-organisationinimage'         => 'Prikazana organizacija',
'exif-personinimage'               => 'Prikazana osoba',
'exif-originalimageheight'         => 'Visina slike pre isecanja',
'exif-originalimagewidth'          => 'Širina slike pre isecanja',

# EXIF attributes
'exif-compression-1' => 'Nekompresovan',
'exif-compression-2' => 'CCITT Group 3 1 – Dimenzionalno izmenjeno Hafmanovo kodiranje po dužini',
'exif-compression-3' => 'CCITT Group 3 faks kodiranje',
'exif-compression-4' => 'CCITT Group 4 faks kodiranje',
'exif-compression-6' => 'JPEG',

'exif-copyrighted-true'  => 'Zaštićeno autorskim pravom',
'exif-copyrighted-false' => 'Javno vlasništvo',

'exif-unknowndate' => 'Nepoznat datum',

'exif-orientation-1' => 'Normalno',
'exif-orientation-2' => 'Obrnuto po horizontali',
'exif-orientation-3' => 'Zaokrenuto 180°',
'exif-orientation-4' => 'Obrnuto po vertikali',
'exif-orientation-5' => 'Zaokrenuto 90° suprotno od smera kazaljke na satu i obrnuto po vertikali',
'exif-orientation-6' => 'Zaokrenuto 90° suprotno od smera kazaljke',
'exif-orientation-7' => 'Zaokrenuto 90° u smeru kazaljke na satu i obrnuto po vertikali',
'exif-orientation-8' => 'Zaokrenuto 90° u smeru kazaljke',

'exif-planarconfiguration-1' => 'delimični format',
'exif-planarconfiguration-2' => 'planarni format',

'exif-colorspace-65535' => 'Deštelovano',

'exif-componentsconfiguration-0' => 'ne postoji',

'exif-exposureprogram-0' => 'Nepoznato',
'exif-exposureprogram-1' => 'Ručno',
'exif-exposureprogram-2' => 'Normalni program',
'exif-exposureprogram-3' => 'Prioritet otvora blende',
'exif-exposureprogram-4' => 'Prioritet zatvarača',
'exif-exposureprogram-5' => 'Umetnički program (na bazi nužne dubine polja)',
'exif-exposureprogram-6' => 'Sportski program (na bazi što bržeg zatvarača)',
'exif-exposureprogram-7' => 'Portretni režim (za krupne kadrove sa neoštrom pozadinom)',
'exif-exposureprogram-8' => 'Režim pejzaža (za slike pejzaža sa oštrom pozadinom)',

'exif-subjectdistance-value' => '$1 metara',

'exif-meteringmode-0'   => 'Nepoznato',
'exif-meteringmode-1'   => 'Prosek',
'exif-meteringmode-2'   => 'Prosek sa težištem na sredini',
'exif-meteringmode-3'   => 'Tačka',
'exif-meteringmode-4'   => 'Više tačaka',
'exif-meteringmode-5'   => 'Matrični',
'exif-meteringmode-6'   => 'Delimični',
'exif-meteringmode-255' => 'Drugo',

'exif-lightsource-0'   => 'Nepoznato',
'exif-lightsource-1'   => 'Dnevna svetlost',
'exif-lightsource-2'   => 'Fluorescentno',
'exif-lightsource-3'   => 'Volfram (svetlo)',
'exif-lightsource-4'   => 'Blic',
'exif-lightsource-9'   => 'Lepo vreme',
'exif-lightsource-10'  => 'Oblačno vreme',
'exif-lightsource-11'  => 'Senka',
'exif-lightsource-12'  => 'Fluorescentna svetlost (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescentna svetlost (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescentna svetlost (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Bela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardno svetlo A',
'exif-lightsource-18'  => 'Standardno svetlo B',
'exif-lightsource-19'  => 'Standardno svetlo C',
'exif-lightsource-24'  => 'ISO studijski volfram',
'exif-lightsource-255' => 'Drugi izvor svetla',

# Flash modes
'exif-flash-fired-0'    => 'Blic nije korišćen',
'exif-flash-fired-1'    => 'Blic je korišćen',
'exif-flash-return-0'   => 'bez funkcije povratnog svetla',
'exif-flash-return-2'   => 'povratno svetlo nije uočeno',
'exif-flash-return-3'   => 'uočeno je povratno svetlo',
'exif-flash-mode-1'     => 'obavezno fleš ispaljivanje',
'exif-flash-mode-2'     => 'obavezno fleš suzbijanje',
'exif-flash-mode-3'     => 'auto mod',
'exif-flash-function-1' => 'Bez blica',
'exif-flash-redeye-1'   => 'mod za redukciju crvenih očiju',

'exif-focalplaneresolutionunit-2' => 'inči',

'exif-sensingmethod-1' => 'Nedefinisano',
'exif-sensingmethod-2' => 'Jednokristalni matrični senzor',
'exif-sensingmethod-3' => 'Dvokristalni matrični senzor',
'exif-sensingmethod-4' => 'Trokristalni matrični senzor',
'exif-sensingmethod-5' => 'Sekvencijalni matrični senzor',
'exif-sensingmethod-7' => 'Trobojni linearni senzor',
'exif-sensingmethod-8' => 'Sekvencijalni linearni senzor',

'exif-filesource-3' => 'Digitalni fotoaparat',

'exif-scenetype-1' => 'Direktno fotografisana slika',

'exif-customrendered-0' => 'Normalni proces',
'exif-customrendered-1' => 'Nestadardni proces',

'exif-exposuremode-0' => 'Automatski',
'exif-exposuremode-1' => 'Ručno',
'exif-exposuremode-2' => 'Automatski sa zadatim rasponom',

'exif-whitebalance-0' => 'Automatski',
'exif-whitebalance-1' => 'Ručno',

'exif-scenecapturetype-0' => 'Standardno',
'exif-scenecapturetype-1' => 'Pejzaž',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Noćno',

'exif-gaincontrol-0' => 'Nema',
'exif-gaincontrol-1' => 'Malo povećanje',
'exif-gaincontrol-2' => 'Veliko povećanje',
'exif-gaincontrol-3' => 'Malo smanjenje',
'exif-gaincontrol-4' => 'Veliko smanjenje',

'exif-contrast-0' => 'Normalno',
'exif-contrast-1' => 'Meko',
'exif-contrast-2' => 'Tvrdo',

'exif-saturation-0' => 'Normalno',
'exif-saturation-1' => 'Niska saturacija',
'exif-saturation-2' => 'Visoka saturacija',

'exif-sharpness-0' => 'Normalno',
'exif-sharpness-1' => 'Meko',
'exif-sharpness-2' => 'Tvrdo',

'exif-subjectdistancerange-0' => 'Nepoznato',
'exif-subjectdistancerange-1' => 'Krupni kadar',
'exif-subjectdistancerange-2' => 'Bliski kadar',
'exif-subjectdistancerange-3' => 'Daleki kadar',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Sever',
'exif-gpslatitude-s' => 'Jug',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Istok',
'exif-gpslongitude-w' => 'Zapad',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metar|metra|metara}} nadmorske visine',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metar|metra|metara}} ispod nivoa mora',

'exif-gpsstatus-a' => 'Merenje u toku',
'exif-gpsstatus-v' => 'Spreman za prenos',

'exif-gpsmeasuremode-2' => 'Dvodimenzionalno merenje',
'exif-gpsmeasuremode-3' => 'Trodimenzionalno merenje',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometri na čas',
'exif-gpsspeed-m' => 'Milje na čas',
'exif-gpsspeed-n' => 'Čvorovi',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometara',
'exif-gpsdestdistance-m' => 'Milja',
'exif-gpsdestdistance-n' => 'Nautičkih milja',

'exif-gpsdop-excellent' => 'Odlično ($1)',
'exif-gpsdop-good'      => 'Dobro ($1)',
'exif-gpsdop-moderate'  => 'Umereno ($1)',
'exif-gpsdop-fair'      => 'Zadovoljavajuće ($1)',
'exif-gpsdop-poor'      => 'Loše ($1)',

'exif-objectcycle-a' => 'Samo ujutru',
'exif-objectcycle-p' => 'Samo uveče',
'exif-objectcycle-b' => 'I ujutru i uveče',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravi pravac',
'exif-gpsdirection-m' => 'Magnetni pravac',

'exif-ycbcrpositioning-1' => 'Centrirano',
'exif-ycbcrpositioning-2' => 'Uporedo',

'exif-dc-contributor' => 'Doprinosioci',
'exif-dc-coverage'    => 'Prostorni ili vremenski opseg medija',
'exif-dc-date'        => 'Datum',
'exif-dc-publisher'   => 'Izdavač',
'exif-dc-relation'    => 'Srodni mediji',
'exif-dc-rights'      => 'Prava',
'exif-dc-source'      => 'Izvor medija',
'exif-dc-type'        => 'Vrsta medija',

'exif-rating-rejected' => 'Odbijeno',

'exif-isospeedratings-overflow' => 'Veće od 65535',

'exif-iimcategory-ace' => 'Umetnost, kultura i zabava',
'exif-iimcategory-clj' => 'Kriminal i zakon',
'exif-iimcategory-dis' => 'Katastrofe i nesreće',
'exif-iimcategory-fin' => 'Ekonomija i posao',
'exif-iimcategory-edu' => 'Obrazovanje',
'exif-iimcategory-evn' => 'Okolina',
'exif-iimcategory-hth' => 'Zdravlje',
'exif-iimcategory-hum' => 'Zanimanje',
'exif-iimcategory-lab' => 'Rad',
'exif-iimcategory-lif' => 'Način života i slobodno vreme',
'exif-iimcategory-pol' => 'Politika',
'exif-iimcategory-rel' => 'Religija i verovanja',
'exif-iimcategory-sci' => 'Nauka i tehnologija',
'exif-iimcategory-soi' => 'Društvena pitanja',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Rat, sukobi i nemiri',
'exif-iimcategory-wea' => 'Vreme',

'exif-urgency-normal' => 'Normalno ($1)',
'exif-urgency-low'    => 'Nisko ($1)',
'exif-urgency-high'   => 'Visoko ($1)',
'exif-urgency-other'  => 'Prilagođeni prioritet ($1)',

# External editor support
'edit-externally'      => 'Izmenite ovaj fajl koristeći spoljašnju aplikaciju',
'edit-externally-help' => '(Pogledajte [//www.mediawiki.org/wiki/Manual:External_editors uputstvo za podešavanje] za više informacija)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'sve',
'namespacesall' => 'svi',
'monthsall'     => 'sve',
'limitall'      => 'sve',

# E-mail address confirmation
'confirmemail'              => 'Potvrdite adresu e-pošte',
'confirmemail_noemail'      => 'Nemate potvrđenu adresu vaše e-pošte u vašim [[Special:Preferences|korisničkim podešavanjima interfejsa]].',
'confirmemail_text'         => 'Ova viki zahteva da potvrdite adresu vaše e-pošte pre nego što koristite mogućnosti e-pošte. Aktivirajte dugme ispod kako biste poslali poštu za potvrdu na vašu adresu. Pošta uključuje vezu koja sadrži kod; učitajte tu vezu u vaš brauzer da biste potvrdili da je adresa vaše e-pošte validna.',
'confirmemail_pending'      => 'Kod potvrde je već poslat na Vašu e-pošru;
Ako ste skoro napravili Vaš nalog, verovatno bi trebalo da odčekate nekoliko minuta, kako bi kod stigao, pre nego što zatražite novi.',
'confirmemail_send'         => 'Pošalji kod za potvrdu',
'confirmemail_sent'         => 'E-pošta za potvrđivanje poslata.',
'confirmemail_oncreate'     => 'Kod za potvrdu je poslat na vašu imejl adresu.
Ovaj kod nije potreban da biste se ulogovali, ali će od Vas biti traženo da ga priložite da bi omogućili pogodnosti Vikija vezane za korišćenje mejlova.',
'confirmemail_sendfailed'   => '{{SITENAME}} nije uspela da pošanje e-poštu.
Proverita adresu zbog nepravilnih karaktera.

Vraćeno: $1',
'confirmemail_invalid'      => 'Netačan kod za potvrdu. Moguće je da je kod istekao.',
'confirmemail_needlogin'    => 'Morate da se $1 da biste potvrdili adresu vaše e-pošte.',
'confirmemail_success'      => 'Adresa vaše e-pošte je potvrđena. Možete sada da se prijavite i uživate u vikiju.',
'confirmemail_loggedin'     => 'Adresa vaše e-pošte je sada potvrđena.',
'confirmemail_error'        => 'Nešto je pošlo po zlu prilikom snimanja vaše potvrde.',
'confirmemail_subject'      => '{{SITENAME}} adresa e-pošte za potvrđivanje',
'confirmemail_body'         => 'Neko, verovatno vi, sa IP adrese $1
je registrovao nalog „$2” sa ovom adresom e-pošte na sajtu {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada vama i da aktivirate
mogućnost e-pošte na sajtu {{SITENAME}}, otvorite ovu vezu u vašem brauzeru:

$3

Ako ovo *niste* vi, pratite ovu vezu kako biste prekinuli registraciju:

$5

Ovaj kod za potvrdu će isteći u $4.',
'confirmemail_body_changed' => 'Neko, verovatno vi, sa IP adrese $1 je promenio e-adresu naloga „$2“ u ovu adresu na vikiju {{SITENAME}}.

Da biste potvrdili da ovaj nalog stvarno pripada vama i ponovo aktivirali mogućnosti korišćenja e-pošte na vikiju {{SITENAME}}, otvorite sledeću vezu u pregledaču:

$3

Ako nalog *ne* pripada vama, pratite sledeću vezu da biste otkazali potvrdu e-adrese:

$5

Ovaj potvrdni kôd ističe $6 u $7.',
'confirmemail_body_set'     => 'Neko, verovatno vi, sa IP adrese $1 je promenio e-adresu naloga „$2“ u ovu adresu na vikiju {{SITENAME}}.

Da biste potvrdili da ovaj nalog stvarno pripada vama i ponovo aktivirali korišćenja e-pošte na vikiju {{SITENAME}}, otvorite sledeću vezu u pregledaču:

$3

Ako nalog *ne* pripada vama, pratite sledeću vezu da biste otkazali potvrdu e-adrese:

$5

Ovaj potvrdni kôd ističe $6 u $7.',
'confirmemail_invalidated'  => 'Overa elektronske adrese je poništena.',
'invalidateemail'           => 'poništavanje potvrde putem imejla',

# Scary transclusion
'scarytranscludedisabled' => '[Interviki uključivanje je onemogućeno]',
'scarytranscludefailed'   => '[Donošenje šablona za $1 neuspešno]',
'scarytranscludetoolong'  => '[URL je predugačak]',

# Delete conflict
'deletedwhileediting'      => "'''Upozorenje''': Ova stranica je obrisana nakon što ste počeli uređivanje!",
'confirmrecreate'          => "[[User:$1|$1]] ([[User talk:$1|razgovor]]) {{GENDER:$1|je obrisao|je obrisala|obrisa}} ovu stranicu nakon što ste počeli da je uređujete, sa sledećim razlogom:
: ''$2''
Potvrdite da stvarno želite da napravite stranicu.",
'confirmrecreate-noreason' => 'Korisnik [[User:$1|$1]] ([[User talk:$1|razgovor]]) je obrisao ovu stranicu nakon što ste počeli da ga uređujete. Potvrdite da stvarno želite da ponovo napravite ovu stranicu.',
'recreate'                 => 'Ponovo napravi',

# action=purge
'confirm_purge_button' => 'Da',
'confirm-purge-top'    => 'Da li želite očistiti keš ove stranice?',
'confirm-purge-bottom' => 'Čišćenje keša strane primorava softver da prikaže njenu najnoviju verziju.',

# action=watch/unwatch
'confirm-watch-button'   => 'U redu',
'confirm-watch-top'      => 'Dodati ovu stranicu u spisak nadgledanja?',
'confirm-unwatch-button' => 'U redu',
'confirm-unwatch-top'    => 'Ukloniti ovu stranicu sa spiska nadgledanja?',

# Multipage image navigation
'imgmultipageprev' => '&larr; prethodna stranica',
'imgmultipagenext' => 'sledeća stranica &rarr;',
'imgmultigo'       => 'Idi!',
'imgmultigoto'     => 'Idi na stranu $1',

# Table pager
'ascending_abbrev'         => 'rast',
'descending_abbrev'        => 'opad',
'table_pager_next'         => 'Sledeća stranica',
'table_pager_prev'         => 'Prethodna stranica',
'table_pager_first'        => 'Prva stranica',
'table_pager_last'         => 'Poslednja stranica',
'table_pager_limit'        => 'Prikaži $1 delova informacije po stranici',
'table_pager_limit_label'  => 'Stavki po stranici:',
'table_pager_limit_submit' => 'Idi',
'table_pager_empty'        => 'Nema rezultata',

# Auto-summaries
'autosumm-blank'   => 'Obrisan je sadržaj stranice',
'autosumm-replace' => "Zamena stranice sa '$1'",
'autoredircomment' => 'Preusmerenje na [[$1]]',
'autosumm-new'     => 'Napravljena je stranica sa „$1”',

# Live preview
'livepreview-loading' => 'Učitavanje…',
'livepreview-ready'   => 'Učitavanje… Gotovo!',
'livepreview-failed'  => 'Brzi prikaz neuspešan! Pokušajte normalni prikaz.',
'livepreview-error'   => 'Neuspešna konekcija: $1 "$2". Probajte normalni prikaz.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Izmene novije od $1 {{PLURAL:$1|sekunde|sekunde|sekundi}} se neće prikazati u spisku.',
'lag-warn-high'   => 'Zbog velikog laga baze podataka, izmene novije od $1 {{PLURAL:$1|sekunde|sekunde|sekundi}} se neće prikazati na spisku.',

# Watchlist editor
'watchlistedit-numitems'       => 'Vaš spisak nadgledanja sadrži {{PLURAL:$1|jednu stranicu|$1 stranice|$1 stranica}}, ne računajući stranice za razgovor.',
'watchlistedit-noitems'        => 'Nema naslova u vašem spisku nadgledanja.',
'watchlistedit-normal-title'   => 'Uredi spisak nadgledanja',
'watchlistedit-normal-legend'  => 'Ukloni naslove sa spiska nadgledanja',
'watchlistedit-normal-explain' => 'Naslovi na vašem spisku nadgledanja su prikazani ispod.
Da biste uklonili naslov, označite kućicu do njega i kliknite na „{{int:Watchlistedit-normal-submit}}“.
Možete i da [[Special:EditWatchlist/raw|izmenite sirov spisak]].',
'watchlistedit-normal-submit'  => 'Ukloni naslove',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 članak je uklonjen|$1 članka su uklonjena|$1 članaka je uklonjeno}} sa vašeg spiska nadgledanja:',
'watchlistedit-raw-title'      => 'Izmeni sirov spisak nadgledanja',
'watchlistedit-raw-legend'     => 'Izmeni sirov spisak nadgledanja',
'watchlistedit-raw-explain'    => 'Naslovi sa Vašeg spiska nadgledanja su prikazani ispod i mogu se menjati dodavanjem ili oduzimanjem;
Pišite jedan naslov po liniji.
Kada završite, kliknite "{{int:Watchlistedit-raw-submit}}".
Takođe, možete [[Special:EditWatchlist|koristiti standardan uređivač spiska]].',
'watchlistedit-raw-titles'     => 'Naslovi:',
'watchlistedit-raw-submit'     => 'Osvežite spisak nadgledanja',
'watchlistedit-raw-done'       => 'Vaš spisak nadgledanja je osvežen.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 naslov je dodat|$1 naslova su dodata|$1 naslova je dodato}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 naslov je uklonjen|$1 naslova su uklonjena|$1 naslova je uklonjeno}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Pogledaj srodne promene',
'watchlisttools-edit' => 'Pogledaj i izmeni spisak nadgledanja',
'watchlisttools-raw'  => 'Izmeni sirov spisak nadgledanja',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|razgovor]])',

# Core parser functions
'unknown_extension_tag' => 'Nepoznati tag za ekstenziju: "$1".',
'duplicate-defaultsort' => "'''Upozorenje:''' Podrazumevani ključ sortiranja „$2“ prepisuje ranije podrazumevani ključ sortiranja „$1“.",

# Special:Version
'version'                       => 'Verzija',
'version-extensions'            => 'Instalisane ekstenzije',
'version-specialpages'          => 'Posebne stranice',
'version-parserhooks'           => 'zakačke parsera',
'version-variables'             => 'Varijable',
'version-antispam'              => 'Sprečavanje nepoželjnih poruka',
'version-skins'                 => 'Teme',
'version-other'                 => 'Ostalo',
'version-mediahandlers'         => 'rukovaoci medijima',
'version-hooks'                 => 'zakačke',
'version-extension-functions'   => 'Funkcije dodatka',
'version-parser-extensiontags'  => 'tagovi ekstenzije Parser',
'version-parser-function-hooks' => 'zakačke parserove funkcije',
'version-hook-name'             => 'ime zakačke',
'version-hook-subscribedby'     => 'prijavljeni',
'version-version'               => '(Verzija $1)',
'version-license'               => 'Licenca',
'version-poweredby-credits'     => "Ovaj viki pokreće '''[//www.mediawiki.org/ Medijaviki]''', autorska prava © 2001-$1 $2.",
'version-poweredby-others'      => 'ostali',
'version-license-info'          => 'Medijaviki je slobodan softver; možete ga raspodeljivati i menjati pod uslovima GNU-ove opšte javne licence (OJL) koju je objavila Zadužbina za slobodan softver, bilo da je u pitanju drugo ili novije izdanje licence.

Medijaviki se raspodeljuje u nadi da će biti koristan, ali BEZ IKAKVE GARANCIJE; čak i bez implicitne garancije o PRODAJI ili POGODNOSTI ZA ODREĐENE NAMENE. Pogledajte GNU-ovu opštu javnu licencu za više detalja.

Trebalo bi da ste primili [{{SERVER}}{{SCRIPTPATH}}/COPYING primerak GNU-ove opšte javne licence] zajedno s ovim programom. Ako niste, pišite Zadužbini za slobodan softver, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA ili [//www.gnu.org/licenses/old-licenses/gpl-2.0.html pročitajte na mreži].',
'version-software'              => 'Instaliran softver',
'version-software-product'      => 'Proizvod',
'version-software-version'      => 'Verzija',

# Special:FilePath
'filepath'         => 'Putanja fajla',
'filepath-page'    => 'Fajl:',
'filepath-submit'  => 'Pošalji',
'filepath-summary' => 'Ova posebna stranica prikazuje potpunu putanju datoteke.
Slike su prikazane u punoj veličini, a druge vrste datoteka se pokreću pomoću njima pridruženim programima.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Pretražite duplikate fajlova',
'fileduplicatesearch-summary'   => 'Pretraga za duplikatima fajlova na bazi njihovih heš vrednosti.',
'fileduplicatesearch-legend'    => 'Pretražite duplikate',
'fileduplicatesearch-filename'  => 'Naziv datoteke:',
'fileduplicatesearch-submit'    => 'Pretraga',
'fileduplicatesearch-info'      => '$1 × $2 poksel<br />Veličina fajla: $3<br />MIME tip: $4',
'fileduplicatesearch-result-1'  => 'Datoteka „$1“ nema identičnih duplikata.',
'fileduplicatesearch-result-n'  => 'Datoteka "$1" ima {{PLURAL:$2|1 identičan duplikat|$2 identična duplikata|$2 identičnih duplikata}}.',
'fileduplicatesearch-noresults' => 'Datoteka pod nazivom „$1“ nije pronađena.',

# Special:SpecialPages
'specialpages'                   => 'Posebne stranice',
'specialpages-note'              => '----
* obične posebne stranice
* <span class="mw-specialpagerestricted">ograničene posebne stranice</span>
* <span class="mw-specialpagecached">privremeno memorisane posebne stranice</span>',
'specialpages-group-maintenance' => 'Izveštaji',
'specialpages-group-other'       => 'Ostale posebne stranice',
'specialpages-group-login'       => 'Prijavi se / registruj se',
'specialpages-group-changes'     => 'Skorašnje izmene i istorije',
'specialpages-group-media'       => 'Multimedijalni izveštaji i slanja',
'specialpages-group-users'       => 'Korisnici i korisnička prava',
'specialpages-group-highuse'     => 'Najviše korišćene stranice',
'specialpages-group-pages'       => 'Spisak stranica',
'specialpages-group-pagetools'   => 'Alatke sa stranice',
'specialpages-group-wiki'        => 'podaci i oruđa vikija',
'specialpages-group-redirects'   => 'Preusmeravanje posebnih stranica',
'specialpages-group-spam'        => 'oruđa protiv spama',

# Special:BlankPage
'blankpage'              => 'prazna stranica',
'intentionallyblankpage' => 'Ova stranica je namerno ostavljena praznom.',

# External image whitelist
'external_image_whitelist' => ' #Ostavite ovu liniju tačno onakvom kakva jeste<pre>
#Dodajte fragmente regularnih izraza (samo deo koji se nalazi između //) ispod
#Oni će biti upoređeno sa URL-ovima spoljašnjih (hot-linkovanih) slika
#One koje odgovaraju će biti prikazane kao slike, a preostale kao veze ka slikama
#Linije koje počinju sa # se tretiraju kao komentari
#Svi unosi su osetljivi na veličinu slova

#Dodajte sve fragmente regularnih izraza ispod ove linije. Ostavite ovu liniju tačno onakvom kakva jeste</pre>',

# Special:Tags
'tags'                    => 'Dozvoljeni tagovi izmene',
'tag-filter'              => 'Filter za [[Special:Tags|tagove]]:',
'tag-filter-submit'       => 'Filtriraj',
'tags-title'              => 'Oznake',
'tags-intro'              => 'Ova stranica daje spisak i značenje tagova kojima softver može da označi neku izmenu.',
'tags-tag'                => 'Naziv oznake',
'tags-display-header'     => 'Izgled na spiskovima izmena',
'tags-description-header' => 'Opis značenja',
'tags-hitcount-header'    => 'Označene izmene',
'tags-edit'               => 'uredi',
'tags-hitcount'           => '$1 {{PLURAL:$1|izmena|izmena}}',

# Special:ComparePages
'comparepages'                => 'Uporedi strane',
'compare-selector'            => 'Upoređivanje izmena stranice',
'compare-page1'               => 'Stranica 1',
'compare-page2'               => 'Stranica 2',
'compare-rev1'                => 'Izmena 1',
'compare-rev2'                => 'Izmena 2',
'compare-submit'              => 'Uporedi',
'compare-invalid-title'       => 'Navedeni naslov je neispravan.',
'compare-title-not-exists'    => 'Navedeni naslov ne postoji.',
'compare-revision-not-exists' => 'Navedena izmena ne postoji.',

# Database error messages
'dberr-header'      => 'Ovaj viki ima problem',
'dberr-problems'    => 'Žao nam je! Ovaj sajt ima tehničkih poteškoća.',
'dberr-again'       => 'Sačekajte nekoliko minuta pre nego što ponovo učitate stranicu.',
'dberr-info'        => '(Server baze podataka ne može da se kontaktira: $1)',
'dberr-usegoogle'   => 'U međuvremenu, pokušajte da pretražite pomoću Gugla.',
'dberr-outofdate'   => 'Primetite da Guglov keš našeg sadržaja može biti neažuran.',
'dberr-cachederror' => 'Ovo je keširana kopija zahtevane strane, i možda nije ažurna.',

# HTML forms
'htmlform-invalid-input'       => 'Ima problema sa delom Vašeg unosa',
'htmlform-select-badoption'    => 'Vrednost koju ste naveli nije ispravna opcija.',
'htmlform-int-invalid'         => 'Vrednost koji ste naveli nije celi broj.',
'htmlform-float-invalid'       => 'Vrednost koju ste zadali nije broj.',
'htmlform-int-toolow'          => 'Vrednosti koju ste naveli je ispod minimuma od $1',
'htmlform-int-toohigh'         => 'Vrednost koju ste naveli je iznad maksimuma od $1',
'htmlform-required'            => 'Ova vrednost se mora navesti',
'htmlform-submit'              => 'Pošalji',
'htmlform-reset'               => 'Vrati izmene',
'htmlform-selectorother-other' => 'Drugo',

# SQLite database support
'sqlite-has-fts' => '$1 s podrškom pretrage punog teksta',
'sqlite-no-fts'  => '$1 bez podrške pretrage punog teksta',

# New logging system
'logentry-delete-delete'              => '$1 {{GENDER:$2|je obrisao|je obrisala|obrisa}} stranicu $3',
'logentry-delete-restore'             => '$1 {{GENDER:$2|je vratio|je vratila|povrati}} stranicu $3',
'logentry-delete-event'               => '$1 {{GENDER:$2|je promenio|je promenila|promeni}} vidljivost {{PLURAL:$5|događaja u istoriji|$5 događaja u istoriji|$5 događaja u istoriji}} na $3: $4',
'logentry-delete-revision'            => '$1 {{GENDER:$2|je promenio|je promenila|promeni}} vidljivost {{PLURAL:$5|izmene|$5 izmene|$5 izmena}} na stranici $3: $4',
'logentry-delete-event-legacy'        => '$1 {{GENDER:$2|je promenio|je promenila|promeni}} vidljivost događajâ u istoriji na $3',
'logentry-delete-revision-legacy'     => '$1 {{GENDER:$2|je promenio|je promenila|promeni}} vidljivost izmenâ na stranici $3',
'logentry-suppress-delete'            => '$1 {{GENDER:$2|je potisnuo|je potisnula|potisnu}} stranicu $3',
'logentry-suppress-event'             => '$1 {{GENDER:$2|je potajno promenio|je potajno promenila|potajno promeni}} vidljivost {{PLURAL:$5|događaja u istoriji|$5 događaja u istoriji|$5 događaja u istoriji}} na $3: $4',
'logentry-suppress-revision'          => '$1 {{GENDER:$2|je potajno promenio|je potajno promenila|potajno promeni}} vidljivost {{PLURAL:$5|izmene|$5 izmene|$5 izmena}} na stranici $3: $4',
'logentry-suppress-event-legacy'      => '$1 {{GENDER:$2|je potajno promenio|je potajno promenila|potajno promeni}} vidljivost događajâ u istoriji na $3',
'logentry-suppress-revision-legacy'   => '$1 {{GENDER:$2|je potajno promenio|je potajno promenila|potajno promeni}} vidljivost izmenâ na stranici $3',
'revdelete-content-hid'               => 'sadržaj je sakriven',
'revdelete-summary-hid'               => 'sažetak izmene je sakriven',
'revdelete-uname-hid'                 => 'korisničko ime je sakriveno',
'revdelete-content-unhid'             => 'sadržaj je otkriven',
'revdelete-summary-unhid'             => 'sažetak izmene je otkriven',
'revdelete-uname-unhid'               => 'korisničko ime je otkriveno',
'revdelete-restricted'                => 'ograničenja za administratore su primenjena',
'revdelete-unrestricted'              => 'ograničenja za administratore su uklonjena',
'logentry-move-move'                  => '$1 {{GENDER:$2|je premestio|je premestila|premesti}} stranicu $3 na $4',
'logentry-move-move-noredirect'       => '$1 {{GENDER:$2|je premestio|je premestila|premesti}} stranicu $3 na $4 bez ostavljanja preusmerenja',
'logentry-move-move_redir'            => '$1 {{GENDER:$2|je premestio|je premestila|je premestio}} stranicu $3 na $4 preko preusmerenja',
'logentry-move-move_redir-noredirect' => '$1 {{GENDER:$2|je premestio|je premestila|premesti}} stranicu $3 na $4 preko preusmerenja bez ostavljanja preusmerenja',
'logentry-patrol-patrol'              => '$1 {{GENDER:$2|je označio|je označila|označi}} izmenu $4 pregledane stranice $3',
'logentry-patrol-patrol-auto'         => '$1 {{GENDER:$2|je samostalno označio|je samostalno označila|samostalno označi}} izmenu $4 pregledane stranice $3',
'logentry-newusers-newusers'          => '$1 {{GENDER:$2|je otvorio|je otvorila|otvori}} korisnički nalog',
'logentry-newusers-create'            => '$1 {{GENDER:$2|je otvorio|je otvorila|otvori}} korisnički nalog',
'logentry-newusers-create2'           => '$1 {{GENDER:$2|je otvorio|je otvorila|otvori}} {{GENDER:$4|korisnički nalog}} $3',
'logentry-newusers-autocreate'        => 'Nalog $1 je {{GENDER:$2|samostalno otvoren}}',
'newuserlog-byemail'                  => 'lozinka poslata imejlom',

);
