<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

$namespaceNames = array(
	NS_MEDIA            => "Medija",
	NS_SPECIAL          => "Posebno",
	NS_MAIN             => "",
	NS_TALK             => "Razgovor",
	NS_USER             => "Korisnik",
	NS_USER_TALK        => "Razgovor_sa_korisnikom",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => "Razgovor_o_$1",
	NS_IMAGE            => "Slika",
	NS_IMAGE_TALK       => "Razgovor_o_slici",
	NS_MEDIAWIKI        => "MedijaViki",
	NS_MEDIAWIKI_TALK   => "Razgovor_o_MedijaVikiju",
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
	"Слика"                   => NS_IMAGE,
	"Разговор_о_слици"        => NS_IMAGE_TALK,
	"МедијаВики"              => NS_MEDIAWIKI,
	"Разговор_о_МедијаВикију" => NS_MEDIAWIKI_TALK,
	'Шаблон'                  => NS_TEMPLATE,
	'Разговор_о_шаблону'      => NS_TEMPLATE_TALK,
	'Помоћ'                   => NS_HELP,
	'Разговор_о_помоћи'       => NS_HELP_TALK,
	'Категорија'              => NS_CATEGORY,
	'Разговор_о_категорији'   => NS_CATEGORY_TALK,
);


$quickbarSettings = array(
 "Nikakva", "Pričvršćena levo", "Pričvršćena desno", "Plutajuća levo"
);

$skinNames = array(
 "Obična", "Nostalgija", "Kelnsko plavo", "Pedington", "Monparnas"
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

	'hh:mm d. month y. both'    =>'H:i, j. F Y.', 
	'hh:mm d month y both'      =>'H:i, j F Y',   
	'hh:mm dd.mm.yyyy both'     =>'H:i, d.m.Y',   
	'hh:mm d.m.yyyy both'       =>'H:i, j.n.Y',   
	'hh:mm d. mon y. both'      =>'H:i, j. M Y.', 
	'hh:mm d mon y both'        =>'H:i, j M Y',   
	'h:mm d. month y. both'     =>'G:i, j. F Y.', 
	'h:mm d month y both'       =>'G:i, j F Y',   
	'h:mm dd.mm.yyyy both'      =>'G:i, d.m.Y',   
	'h:mm d.m.yyyy both'        =>'G:i, j.n.Y',   
	'h:mm d. mon y. both'       =>'G:i, j. M Y.', 
	'h:mm d mon y both'         =>'G:i, j M Y',   
);


/* NOT USED IN STABLE VERSION */
$magicWords = array(
#	ID                                CASE SYNONYMS
	'redirect'               => array( 0, '#Preusmeri', '#redirect', '#preusmeri', '#PREUSMERI' ),
	'notoc'                  => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	'toc'                    => array( 0, '__TOC__', '__SADRŽAJ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
	'start'                  => array( 0, '__START__', '__POČETAK__' ),
	'end'                    => array( 0, '__END__', '__KRAJ__' ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'TRENUTNIMESEC' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMESECIME' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMESECGEN' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMESECSKR' ),
	'currentday'             => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'TRENUTNOVREME' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
	'pagename'               => array( 1, 'PAGENAME', 'STRANICA' ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'STRANICE' ),
	'namespace'              => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
	'namespacee'             => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
	'msg'                    => array( 0, 'MSG:', 'POR:' ),
	'subst'                  => array( 0, 'SUBST:', 'ZAMENI:' ),
	'msgnw'                  => array( 0, 'MSGNW:', 'NVPOR:' ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'mini' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
	'img_right'              => array( 1, 'right', 'desno', 'd' ),
	'img_left'               => array( 1, 'left', 'levo', 'l' ),
	'img_none'               => array( 1, 'none', 'n', 'bez' ),
	'img_width'              => array( 1, '$1px', '$1piskel' , '$1p' ),
	'img_center'             => array( 1, 'center', 'centre', 'centar', 'c' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'okvir', 'ram' ),
	'int'                    => array( 0, 'INT:', 'INT:' ),
	'sitename'               => array( 1, 'SITENAME', 'IMESAJTA' ),
	'ns'                     => array( 0, 'NS:', 'IP:' ),
	'localurl'               => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
	'localurle'              => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
	'server'                 => array( 0, 'SERVER', 'SERVER' ),
	'servername'             => array( 0, 'SERVERNAME', 'IMESERVERA' ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'SKRIPTA' ),
	'grammar'                => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__БЕЗКН__', '__BEZKN__' ),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'TRENUTNANEDELJA' ),
	'currentdow'             => array( 1, 'CURRENTDOW', 'TRENUTNIDOV' ),
	'revisionid'             => array( 1, 'REVISIONID', 'IDREVIZIJE' ),
	'plural'                 => array( 0, 'PLURAL:', 'MNOŽINA:' ),
	'fullurl'                => array( 0, 'FULLURL:', 'PUNURL:' ),
	'fullurle'               => array( 0, 'FULLURLE:', 'PUNURLE:' ),
	'lcfirst'                => array( 0, 'LCFIRST:', 'LCPRVI:' ),
	'ucfirst'                => array( 0, 'UCFIRST:', 'UCPRVI:' ),
	'lc'                     => array( 0, 'LC:', 'LC:' ),
	'uc'                     => array( 0, 'UC:', 'UC:' ),
);

$separatorTransformTable = array(',' => '.', '.' => ',' );


$messages = array(
# stylesheets
'Common.css'  => '/** CSS koji važi za sve skinove */',
'Monobook.css'  => '/** Samo za MonoBook skin */',

# User preference toggles
'tog-underline' => 'Podvuci veze',
'tog-highlightbroken' => 'Formatiraj pokvarene veze <a href="" class="new">ovako</a> (alternativa: ovako<a href="" class="internal">?</a>).',
'tog-justify' => 'Uravnaj pasuse',
'tog-hideminor' => 'Sakrij male izmene u spisku skorašnjih izmena',
'tog-extendwatchlist' => 'Poboljšan spisak nadgledanja',
'tog-usenewrc' => 'Poboljšan spisak skorašnjih izmena (zahteva JavaScript)',
'tog-numberheadings' => 'Automatski numeriši podnaslove',
'tog-showtoolbar' => 'Prikaži dugmiće za izmene (zahteva JavaScript)',
'tog-editondblclick' => 'Menjaj stranice dvostrukim klikom (zahteva JavaScript)',
'tog-editsection' => 'Omogući izmenu delova [uredi] vezama',
'tog-editsectiononrightclick' => 'Omogući izmenu delova desnim klikom<br />na njihove naslove (zahteva JavaScript)',
'tog-showtoc' => 'Prikaži sadržaj (u člancima sa više od 3 podnaslova)',
'tog-rememberpassword' => 'Pamti lozinku kroz više seansi',
'tog-editwidth' => 'Polje za izmene ima punu širinu',
'tog-watchcreations' => 'Dodaj stranice koje pravim u moj spisak nadgledanja',
'tog-watchdefault' => 'Dodaj stranice koje menjam u moj spisak nadgledanja',
'tog-minordefault' => 'Označi sve izmene malim isprva',
'tog-previewontop' => 'Prikaži pretpregled pre polja za izmenu',
'tog-previewonfirst' => 'Prikaži pretpregled pri prvoj izmeni',
'tog-nocache' => 'Onemogući keširanje stranica',
'tog-enotifwatchlistpages' => 'Pošalji mi e-poštu kada se promeni strana koju nadgledam',
'tog-enotifusertalkpages' => 'Pošalji mi e-poštu kada se promeni moja korisnička strana za razgovor',
'tog-enotifminoredits' => 'Pošalji mi e-poštu takođe za male izmene strana',
'tog-enotifrevealaddr' => 'Otkrij adresu moje e-pošte u pošti obaveštenja',
'tog-shownumberswatching' => 'Prikaži broj korisnika koji nadgledaju',
'tog-fancysig' => 'Čist potpis (bez automatskih veza)',
'tog-externaleditor' => 'Koristi spoljašnji uređivač po podrazumevanim podešavanjima',
'tog-externaldiff' => 'Koristi spoljašnji program za prikaz razlika po podrazumevanim podešavanjima',
'tog-showjumplinks' => 'Omogući "skoči na" veze',
'tog-uselivepreview' => 'Koristi živi pretpregled (zahteva JavaScript) (eksperimentalno)',
'tog-autopatrol' => 'Označi izmene koje pravim patroliranim',
'tog-forceeditsummary' => 'Upozori me kad ne unesem opis izmene',
'tog-watchlisthideown' => 'Sakrij moje izmene sa spiska nadgledanja',
'tog-watchlisthidebots' => 'Sakrij izmene botova sa spiska nadgledanja',
'tog-nolangconversion' => 'Isključi konverziju varijanti',

'underline-always' => 'Uvek',
'underline-never' => 'Nikad',
'underline-default' => 'Po podešavanjima brauzera',

'skinpreview' => '(Pregled)',

# dates
'sunday' => 'nedelja',
'monday' => 'ponedeljak',
'tuesday' => 'utorak',
'wednesday' => 'sreda',
'thursday' => 'četvrtak',
'friday' => 'petak',
'saturday' => 'subota',
'sun' => 'ned',
'mon' => 'pon',
'tue' => 'uto',
'wed' => 'sre',
'thu' => 'čet',
'fri' => 'pet',
'sat' => 'sub',
'january' => 'januar',
'february' => 'februar',
'march' => 'mart',
'april' => 'april',
'may_long' => 'maj',
'june' => 'jun',
'july' => 'jul',
'august' => 'avgust',
'september' => 'septembar',
'october' => 'oktobar',
'november' => 'novembar',
'december' => 'decembar',
'january-gen' => 'januara',
'february-gen' => 'februara',
'march-gen' => 'marta',
'april-gen' => 'aprila',
'may-gen' => 'maja',
'june-gen' => 'juna',
'july-gen' => 'jula',
'august-gen' => 'avgusta',
'september-gen' => 'septembra',
'october-gen' => 'oktobra',
'november-gen' => 'novembra',
'december-gen' => 'decembra',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'maj',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'avg',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',
# Bits of text used by many pages:
#
'categories' => 'Kategorije stranica',
'pagecategories' => '{{PLURAL:$1|Kategorija|Kategorije|Kategorije}} stranica',
'category_header' => 'Članaka u kategoriji "$1"',
'subcategories' => 'Potkategorije',

'mainpage' => 'Glavna strana',
'mainpagetext' => '<big>\'\'\'MedijaViki je uspešno instaliran.\'\'\'</big>',
'mainpagedocfooter' => "Molimo vidite [http://meta.wikimedia.org/wiki/Help:Contents korisnički vodič] za informacije o upotrebi viki softvera.

== Za početak ==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Pomoć u vezi sa podešavanjima]
* [http://www.mediawiki.org/wiki/Help:FAQ Najčešće postavljena pitanja]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce Mejling lista o izdanjima MedijaVikija]",

'portal' => 'Radionica',
'portal-url' => 'Project:Radionica',
'about' => 'O...',
'aboutsite' => 'O projektu {{SITENAME}}',
'aboutpage' => 'Project:O',
'article' => 'Članak',
'help' => 'Pomoć',
'helppage' => '{{ns:help}}:Sadržaj',
'bugreports' => 'Prijave grešaka',
'bugreportspage' => 'Project:Prijave_grešaka',
'sitesupport' => 'Donacije',
'sitesupport-url' => 'Project:Donacije',
'faq' => 'NPP',
'faqpage' => 'Project:NPP',
'edithelp' => 'Pomoć oko uređivanja',
'newwindow' => '(novi prozor)',
'edithelppage' => '{{ns:help}}:Uređivanje',
'cancel' => 'Poništi',
'qbfind' => 'Pronađi',
'qbbrowse' => 'Prelistavaj',
'qbedit' => 'Izmeni',
'qbpageoptions' => 'Opcije stranice',
'qbpageinfo' => 'Informacije o stranici',
'qbmyoptions' => 'Moje opcije',
'qbspecialpages' => 'Posebne stranice',
'moredotdotdot' => 'Još...',
'mypage' => 'Moja stranica',
'mytalk' => 'Moj razgovor',
'anontalk' => 'Razgovor za ovu IP adresu',
'navigation' => 'Navigacija',

# Metadata in edit box
'metadata_help' => 'Metapodaci (vidi [[Project:Metapodaci]] za objašnjenje):',

'currentevents' => 'Trenutni događaji',
'currentevents-url' => 'Trenutni događaji',
'disclaimers' => 'Odricanje odgovornosti',
'disclaimerpage' => 'Project:Odricanje odgovornosti',
'privacy' => 'Politika privatnosti',
'privacypage' => 'Project:Politika privatnosti',
'errorpagetitle' => 'Greška',
'returnto' => 'Povratak na $1.',
'tagline' => 'Iz projekta {{SITENAME}}',
'help' => 'Pomoć',
'search' => 'pretraga',
'searchbutton' => 'Traži',
'go' => 'Idi',
'searcharticle' => 'Idi',
'history' => 'Istorija stranice',
'history_short' => 'istorija',
'updatedmarker' => 'ažurirano od moje poslednje posete',
'info_short' => 'Informacije',
'printableversion' => 'Verzija za štampu',
'permalink' => 'Permalink',
'print' => 'Štampa',
'edit' => 'Uredi',
'editthispage' => 'Uredi ovu stranicu',
'delete' => 'obriši',
'deletethispage' => 'Obriši ovu stranicu',
'undelete_short' => 'vrati {{PLURAL:$1|jednu obrisanu izmenu|$1 obrisane izmene|$1 obrisanih izmena}}',
'protect' => 'zaštiti',
'protectthispage' => 'Zaštiti ovu stranicu',
'unprotect' => 'Skloni zaštitu',
'unprotectthispage' => 'Skloni zaštitu sa ove stranice',
'newpage' => 'Nova stranica',
'talkpage' => 'Razgovor o ovoj stranici',
'specialpage' => 'Posebna stranica',
'personaltools' => 'Lični alati',
'postcomment' => 'Pošalji komentar',
'articlepage' => 'Pogledaj članak',
'talk' => 'Razgovor',
'views' => 'Pregledi',
'toolbox' => 'alati',
'userpage' => 'Pogledaj korisničku stranu',
'projectpage' => 'Pogledaj stranu projekta',
'imagepage' => 'Pogledaj stranu slike',
'mediawikipage' => 'Vidi stranicu poruke',
'templatepage' => 'Vidi stranicu šablona',
'viewhelppage' => 'Vidi stranicu pomoći',
'categorypage' => 'Vidi stranicu kategorije',
'viewtalkpage' => 'Pogledaj razgovor',
'otherlanguages' => 'Ostali jezici',
'redirectedfrom' => '(Preusmereno sa $1)',
'autoredircomment' => 'Preusmerenje na [[$1]]',
'redirectpagesub' => 'Strana preusmerenja',
'lastmodifiedat' => 'Ova stranica je poslednji put izmenjena $2, $1.',
'viewcount' => 'Ovoj stranici je pristupljeno {{PLURAL:$1|jednom|$1 puta|$1 puta}}.',
'copyright' => 'Sadržaj je objavljen pod $1.',
'protectedpage' => 'Zaštićena stranica',
'jumpto' => 'Skoči na:',
'jumptonavigation' => 'navigacija',
'jumptosearch' => 'pretraga',

'badaccess' => 'Greška u dozvolama',
'badaccess-group0' => 'Nije vam dozvoljeno da izvršite akciju koju ste pokrenuli.',
'badaccess-group1' => 'Akcija koju ste pokrenuli je rezerevisana za korisnike u grupi $1.',
'badaccess-group2' => 'Akcija koju ste pokrenuli je rezervisana za korisnike iz jedne od grupa $1.',
'badaccess-groups' => 'Akcija koju ste pokrenuli je rezervisana za korisnike iz jedne od grupa $1.',

'versionrequired' => 'Verzija $1 MedijaVikija je potrebna',
'versionrequiredtext' => 'Verzija $1 MedijaVikija je potrebna da bi se koristila ova strana. Pogledajte [[{{ns:special}}:Version|verziju]]',

'ok' => 'da',
'pagetitle' => '$1 - {{SITENAME}}',
'retrievedfrom' => 'Dobavljeno iz "$1"',
'youhavenewmessages' => 'Imate $1 ($2).',
'newmessageslink' => 'novih poruka',
'newmessagesdifflink' => 'najsvežije izmene',
'editsection' => 'uredi',
'editold' => 'uredi',
'editsectionhint' => 'Uredi deo: $1',
'toc' => 'Sadržaj',
'showtoc' => 'prikaži',
'hidetoc' => 'sakrij',
'thisisdeleted' => 'Pogledaj ili vrati $1?',
'viewdeleted' => 'Pogledaj $1?',
'restorelink' => '{{PLURAL:$1|jedna obrisana izmena|$1 obrisane izmene|$1 obrisanih izmena}}',
'feedlinks' => 'Fid:',
'feed-invalid' => 'Loš tip fida prijave.',
'feed-atom' => 'Atom',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Članak',
'nstab-user' => 'Korisnička strana',
'nstab-media' => 'Medij',
'nstab-special' => 'Posebna',
'nstab-project' => 'Strana projekta',
'nstab-image' => 'Slika',
'nstab-mediawiki' => 'Poruka',
'nstab-template' => 'Šablon',
'nstab-help' => 'Pomoć',
'nstab-category' => 'Kategorija',

# Main script and global functions
#
'nosuchaction' => 'Nema takve akcije',
'nosuchactiontext' => 'Akciju navedenu u URL-u viki softver
nije prepoznao.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nospecialpagetext' => 'Tražili ste nepostojeću posebnu stranicu. Spisak svih posebnih stranica se može naći na [[{{ns:special}}:Specialpages]].',

# General errors
#
'error' => 'Greška',
'databaseerror' => 'Greška u bazi',
'dberrortext' => 'Desila se sintaksna greška upita baze.
Ovo možda ukazuje na greške u softveru.
Poslednji pokušani upit je bio:
<blockquote><tt>$1</tt></blockquote>
iz funkcije "<tt>$2</tt>".
MySQL je vratio grešku "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Desila se sintaksna greška upita baze.
Poslednji pokušani upit je bio:
"$1"
iz funkcije "$2".
MySQL je vratio grešku "$3: $4".',
'noconnect' => 'Žao nam je! Viki ima tehničke poteškoće, i ne može da se poveže se serverom baze.',
'nodb' => 'Ne mogu da izaberem bazu $1',
'cachederror' => 'Ovo je keširana kopija zahtevane stranice, i možda nije ažurirana.',
'laggedslavemode' => 'Upozorenje: moguće je da strana nije skoro ažurirana.',
'readonly' => 'Baza je zaključana',
'enterlockreason' => 'Unesite razlog za zaključavanje, uključujući procenu
vremena otključavanja',
'readonlytext' => 'Baza podataka je trenutno zaključana za nove
unose i ostale izmene, verovatno zbog rutinskog održavanja,
posle čega će biti vraćena u uobičajeno stanje.
Administrator koji ju je zaključao dao je ovo objašnjenje: $1',
'missingarticle' => 'Baza nije našla tekst stranice
koji je trebalo, nazvan "$1".

Ovo je obično izazvano praćenjem zastarele "razl" veze ili veze ka istoriji
stranice koja je obrisana.

Ako ovo nije slučaj, možda ste pronašli grešku u softveru.
Molimo vas prijavite ovo jednom od [[Project:Administratori|administratora]], zajedno sa URL-om.',
'readonly_lag' => 'Baza podataka je automatski zaključana dok slejv serveri ne sustignu master',
'internalerror' => 'Interna greška',
'filecopyerror' => 'Ne mogu da iskopiram fajl "$1" na "$2".',
'filerenameerror' => 'Ne mogu da preimenujem fajl "$1" u "$2".',
'filedeleteerror' => 'Ne mogu da obrišem fajl "$1".',
'filenotfound' => 'Ne mogu da nađem fajl "$1".',
'unexpected' => 'Neočekivana vrednost: "$1"="$2".',
'formerror' => 'Greška: ne mogu da pošaljem upitnik',
'badarticleerror' => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'cannotdelete' => 'Ne mogu da obrišem navedenu stranicu ili fajl. (Moguće je da je neko drugi već obrisao.)',
'badtitle' => 'Loš naslov',
'badtitletext' => 'Zahtevani naslov stranice je bio neispravan, prazan ili
neispravno povezan međujezički ili interviki naslov. Možda sadrži jedan ili više karaktera koji ne mogu da se upotrebljavaju u naslovima.',
'perfdisabled' => 'Žao nam je! Ova mogućnost je privremeno onemogućena jer usporava bazu do te mere da više niko ne može da koristi viki.',
'perfdisabledsub' => 'Ovde je snimljena kopija $1:', # obsolete?
'perfcached' => 'Sledeći podaci su keširani i ne moraju biti u potpunosti ažurirani.',
'perfcachedts' => 'Sledeći podaci su keširani i poslednji put su ažurirani: $1',
'wrong_wfQuery_params' => 'Netačni parametri za wfQuery()<br />
Funkcija: $1<br />
Pretraga: $2',
'viewsource' => 'pogledaj kod',
'viewsourcefor' => 'za $1',
'protectedtext' => 'Ova stranica je zaključana za izmene.

Možete gledati i kopirati sadržaj ove strane:',
'protectedinterface' => '\'\'\'Upozorenje:\'\'\' Menjate stranu koja se koristi da pruži tekst interfejsa za softver. Izmene na ovoj strani će uticati na izgled korisničkog interfejsa za ostale korisnike.',
'editinginterface' => "'''Upozorenje:''' Uređujete stranicu čija je namena upisivanje teksta za interfejs softvera. Izmene u ovoj stranici će promeniti izgled korisničkog intefejsa svih korisnika.",
'sqlhidden' => '(SQL pretraga sakrivena)',

# Login and logout pages
#
'logouttitle' => 'Odjavi se',
'logouttext' => '<strong>Sada ste odjavljeni.</strong><br />
Možete da nastavite da koristite projekat {{SITENAME}} anonimno, ili se ponovo prijaviti kao drugi korisnik. Obratite pažnju da neke stranice mogu nastaviti da se prikazuju kao da ste još uvek prijavljeni, dok ne očistite keš svog brauzera.',

'welcomecreation' => '== Dobrodošli, $1! ==

Vaš nalog je napravljen.
Ne zaboravite da prilagodite sebi svoja {{SITENAME}} podešavanja.',

'loginpagetitle' => 'Prijavljivanje',
'yourname' => 'Korisničko ime',
'yourpassword' => 'Vaša lozinka',
'yourpasswordagain' => 'Ponovite lozinku',
'remembermypassword' => 'Zapamti me',
'yourdomainname' => 'Vaš domen',
'externaldberror' => 'Došlo je ili do greške pri spoljašnjoj autentifikaciji baze podataka ili vam nije dozvoljeno da ažurirate svoj spoljašnji nalog.',
'loginproblem' => '<b>Bilo je problema sa vašim prijavljivanjem.</b><br />Pokušajte ponovo!',
'alreadyloggedin' => '<strong>Korisniče $1, već ste prijavljeni!</strong><br />',

'login' => 'Prijavi se',
'loginprompt' => 'Morate da imate omogućene kolačiće (\'\'cookies\'\') da biste se prijavili na {{SITENAME}}.',
'userlogin' => 'Registruj se / Prijavi se',
'logout' => 'Odjavi se',
'userlogout' => 'Odjavi se',
'notloggedin' => 'Niste prijavljeni',
'nologin' => 'Nemate nalog? $1.',
'nologinlink' => 'Napravite nalog',
'createaccount' => 'Napravi nalog',
'gotaccount' => 'Već imate nalog? $1.',
'gotaccountlink' => 'Prijavi se',
'createaccountmail' => 'e-poštom',
'badretype' => 'Lozinke koje ste uneli se ne poklapaju.',
'userexists' => 'Korisničko ime koje ste uneli već je u upotrebi. Molimo izaberite drugo ime.',
'youremail' => 'Adresa vaše e-pošte *',
'username' => 'Korisničko ime:',
'uid' => 'Korisnički ID:',
'yourrealname' => 'Vaše pravo ime *',
'yourlanguage' => 'Jezik:',
'yourvariant' => 'Varijanta:',
'yournick' => 'Nadimak:',
'badsig' => 'Greška u potpisu; proverite HTML tagove.',
'email' => 'E-pošta',
'prefs-help-email-enotif' => 'Ova adresa se takođe koristi da vam se šalju obaveštenja preko e-pošte ako ste omogućili tu opciju.',
'prefs-help-realname' => '* Pravo ime (opciono): ako izaberete da date ime, ovo će biti korišćeno za pripisivanje za vaš rad.',
'loginerror' => 'Greška pri prijavljivanju',
'prefs-help-email' => '* E-pošta (opciono): Omogućuje ostalima da vas kontaktiraju preko vaše korisničke strane ili strane za razgovora bez potrebe da odajete svoj identitet.',
'nocookiesnew' => 'Korisnički nalog je napravljen, ali niste prijavljeni. {{SITENAME}} koristi kolačiće (\'\'cookies\'\') da bi se korisnici prijavili. Vi ste onemogućili kolačiće na svom računaru. Molimo omogućite ih, a onda se prijavite sa svojim novim korisničkim imenom i lozinkom.',
'nocookieslogin' => '{{SITENAME}} koristi kolačiće (\'\'cookies\'\') da bi se korisnici prijavili. Vi ste onemogućili kolačiće na svom računaru. Molimo omogućite ih i pokušajte ponovo sa prijavom.',
'noname' => 'Niste izabrali ispravno korisničko ime.',
'loginsuccesstitle' => 'Prijavljivanje uspešno',
'loginsuccess' => '\'\'\'Sada ste prijavljeni na {{SITENAME}} kao "$1".\'\'\'',
'nosuchuser' => 'Ne postoji korisnik sa imenom "$1". Proverite da li ste dobro napisali ili napravite novi korisnički nalog.',
'nosuchusershort' => 'Ne postoji korisnik sa imenom "$1". Proverite da li ste dobro napisali.',
'nouserspecified' => 'Morate da naznačite korisničko ime.',
'wrongpassword' => 'Lozinka koju ste uneli je neispravna. Molimo pokušajte ponovo.',
'wrongpasswordempty' => 'Lozinka koju ste uneli je prazna. Molimo pokušajte ponovo.',
'mailmypassword' => 'Pošalji mi lozinku',
'passwordremindertitle' => '{{SITENAME}} podsetnik za šifru',
'passwordremindertext' => 'Neko (verovatno vi, sa IP adrese $1)
je zahtevao da vam pošaljemo novu lozinku za {{SITENAME}} ($4).
Lozinka za korisnika "$2" je sada "$3".
Sada treba da se prijavite i promenite svoju lozinku.

Ako je neko drugi podneo ovaj zahtev ili ukoliko ste se setili svoje lozinke i više ne želite da je menjate, možete da ignorišete ovu poruku i nastavite da koristite svoju staru šifru.',
'noemail' => 'Ne postoji adresa e-pošte za korisnika "$1".',
'passwordsent' => 'Nova šifra je poslata na adresu e-pošte korisnika "$1".
Molimo prijavite se pošto je primite.',
'blocked-mailpassword' => 'Vašoj IP adresi je blokiran pristup uređivanju, iz kog razloga nije moguće koristiti funkciju podsećanja lozinke, radi prevencije izvršenja nedozvoljene akcije.',
'eauthentsent' => 'E-pošta za potvrdu je poslata na naznačenu adresu e-pošte. Pre nego što se bilo koja druga e-pošta pošalje na nalog, moraćete da pratite uputstva u e-pošti, da biste potvrdili da je nalog zaista vaš.',
'throttled-mailpassword' => 'Podsetnik lozinke vam je već poslao jednu poruku u zadnjih $1 sati. Radi prevencije izvršenja nedozvoljene akcije, podsetnik šalje samo jednu poruku u roku od $1 sati.',
'mailerror' => 'Greška pri slanju e-pošte: $1',
'acct_creation_throttle_hit' => 'Žao nam je, već ste napravili $1 korisnička imena. Više nije dozvoljeno.',
'emailauthenticated' => 'Vaša adresa e-pošte je potvrđena: $1.',
'emailnotauthenticated' => 'Vaša adresa e-pošte još uvek nije potvrđena. E-pošta neće biti poslata ni za jednu od sledećih mogućnosti.',
'noemailprefs' => 'Naznačite adresu e-pošte kako bi ove mogućnosti radile.',
'emailconfirmlink' => 'Potvrdite vašu adresu e-pošte',
'invalidemailaddress' => 'Adresa e-pošte ne može biti primljena jer izgleda nije pravilnog formata. Molimo unesite dobro-formatiranu adresu ili ispraznite to polje.',
'accountcreated' => 'Nalog je napravljen',
'accountcreatedtext' => 'Korisnički nalog za $1 je napravljen.',

# Edit page toolbar
'bold_sample' => 'podebljan tekst',
'bold_tip' => 'podebljan tekst',
'italic_sample' => 'kurzivan tekst',
'italic_tip' => 'kurzivan tekst',
'link_sample' => 'naslov veze',
'link_tip' => 'unutrašnja veza',
'extlink_sample' => 'http://www.adresa.com opis adrese',
'extlink_tip' => 'spoljašnja veza (ne zaboravite prefiks http://)',
'headline_sample' => 'Naslov',
'headline_tip' => 'Naslov drugog nivoa',
'math_sample' => 'Ovde unesite formulu',
'math_tip' => 'Matematička formula (LaTeX)',
'nowiki_sample' => 'Dodaj neformatirani tekst ovde',
'nowiki_tip' => 'Ignoriši viki formatiranje',
'image_sample' => 'ime_slike.jpg',
'image_tip' => 'Uklopljena slika',
'media_sample' => 'ime_medija_fajla.mp3',
'media_tip' => 'Putanja ka multimedijalnom fajlu',
'sig_tip' => 'Vaš potpis sa trenutnim vremenom',
'hr_tip' => 'Horizontalna linija',

# Edit pages
#
'summary' => 'Opis izmene',
'subject' => 'Tema/naslov',
'minoredit' => 'Ovo je mala izmena',
'watchthis' => 'Nadgledaj ovaj članak',
'savearticle' => 'Snimi stranicu',
'preview' => 'Pretpregled',
'showpreview' => 'Prikaži pretpregled',
'showlivepreview' => 'Živi pretpregled',
'showdiff' => 'Prikaži promene',
'anoneditwarning' => 'Niste prijavljeni. Vaša IP adresa će biti zabeležena u istoriji izmena ove strane.',
'missingsummary' => '\'\'\'Podsetnik:\'\'\' Niste uneli opis izmene. Ukoliko kliknete Snimi stranicu ponovo, vaše izmene će biti snimljene bez opisa.',
'missingcommenttext' => 'Molimo unestite komentar ispod.',
'missingcommentheader' => "'''Podsetnik:''' Niste naveli naslov ovog komentara. Ukoliko kliknete ''Snimi ponovo'', vaš komentar će biti snimljen bez naslova.",
'blockedtitle' => 'Korisnik je blokiran',
'blockedtext' => '<big>\'\'\'Vaše korisničko ime ili IP adresa je blokirano.\'\'\'</big>

Blokirao vas je korisnik $1. Razlog za blokiranje je \'\'$2\'\'.

Možete kontaktirati korisnika $1 ili nekog drugog [[{{ns:project}}:Administratori|administratora]] kako biste razgovarali o blokadi. Ne možete da koristite opciju "Pošalji e-poštu ovom korisniku" ukoliko nemate valjanu adresu e-pošte navedenu u vašim [[{ns:special}}:Preferences|podešavanjima]]. Vaša trenutna IP adresa je $3. Molimo uključite ovo u svaki vaš zahtev.',
'blockedoriginalsource' => 'Izvor \'\'\'$1\'\'\' je prikazan ispod:',
'blockededitsource' => 'Tekst \'\'\'vaših izmena\'\'\' za \'\'\'$1\'\'\' je prikazan ispod:',
'whitelistedittitle' => 'Obavezno je prijavljivanje za uređivanje',
'whitelistedittext' => 'Morate da se [[{{ns:special}}:Userlogin|prijavite]] da biste menjali članke.',
'whitelistreadtitle' => 'Obavezno je prijavljivanje za čitanje',
'whitelistreadtext' => 'Morate da se [[{{ns:special}}:Userlogin|prijavite]] da biste čitali članke.',
'whitelistacctitle' => 'Nije vam dozvoljeno da napravite nalog',
'whitelistacctext' => 'Da bi vam bilo dozvoljeno da pravite naloge na ovom vikiju morate da se [[{{ns:special}}:Userlogin|prijavite]] i imate odgovarajuća ovlašćenja.',
'confirmedittitle' => 'Potrebna je potvrda adrese e-pošte za uređivanje',
'confirmedittext' => 'Morate potvrditi vašu adresu e-pošte pre uređivanja strana. Molimo postavite i potvrdite adresu vaše e-pošte preko vaših [[{{ns:special}}:Preferences|korisničkih podešavanja]].',
'loginreqtitle' => 'Potrebno [[{{ns:special}}:Userlogin|prijavljivanje]]',
'loginreqlink' => 'prijava',
'loginreqpagetext' => 'Morate $1 da biste videli ostale strane.',
'accmailtitle' => 'Lozinka je poslata.',
'accmailtext' => 'Lozinka za nalog "$1" je poslata na adresu $2.',
'newarticle' => '(Novi)',
'newarticletext' => 'Pratili ste vezu ka stranici koja još ne postoji.
Da biste je napravili, počnite da kucate u polju ispod
(pogledajte [[{{ns:help}}:Sadržaj|pomoć]] za više informacija).
Ako ste došli ovde greškom, samo kliknite dugme \'\'\'back\'\'\' dugme vašeg brauzera.',
'anontalkpagetext' => '---- \'\'Ovo je stranica za razgovor za anonimnog korisnika koji još nije napravio nalog ili ga ne koristi. Zbog toga moramo da koristimo brojčanu IP adresu kako bismo identifikovali njega ili nju. Takvu adresu može deliti više korisnika. Ako ste anonimni korisnik i mislite da su vam upućene nebitne primedbe, molimo vas da [[{{ns:special}}:Userlogin|napravite nalog ili se prijavite]] da biste izbegli buduću zabunu sa ostalim anonimnim korisnicima.\'\'',
'noarticletext' => 'Trenutno nema teksta na ovoj stranici. Možete [[{{ns:special}}:Search/{{PAGENAME}}|pretražiti ovaj naziv]] u ostalim stranicama ili [{{fullurl:{{FULLPAGENAME}}|action=edit}} urediti ovu stranicu].',
'clearyourcache' => '\'\'\'Zapamtite:\'\'\' Nakon snimanja, možda morate očistiti keš vašeg brauzera da biste videli promene. \'\'\'Mozilla / Firefox / Safari:\'\'\' držite \'\'Shift\'\' dok klikćete \'\'Reload\'\' ili pritisnite  \'\'Shift+Ctrl+R\'\' (\'\'Cmd-Shift-R\'\' na \'\'Apple Mac\'\' mašini); \'\'\'IE:\'\'\' držite \'\'Ctrl\'\' dok klikćete \'\'Refresh\'\' ili pritisnite \'\'Ctrl-F5\'\'; \'\'\'Konqueror:\'\'\': samo kliknite \'\'Reload\'\' dugme ili pritisnite \'\'F5\'\'; korisnici \'\'\'Opera\'\'\' brauzera možda moraju da u potpunosti očiste svoj keš preko \'\'Tools→Preferences\'\'.',
'usercssjsyoucanpreview' => '<strong>Savet:</strong> Korisitite \'Prikaži pretpregled\' dugme da testirate svoj novi CSS/JS pre snimanja.',
'usercsspreview' => '\'\'\'Zapamtite ovo je samo pretpregled vašeg CSS i da još uvek nije snimljen!\'\'\'',
'userjspreview' => '\'\'\'Zapamtite ovo je samo pretpregled vaše JavaScript-e i da još uvek nije snimljen!\'\'\'',
'userinvalidcssjstitle' => '\'\'\'Pažnja:\'\'\' Ne postoji koža "$1". Zapamtite da lične .css i .js koriste mala početna slova, npr. Korisnik:Petar/monobook.css a ne Korisnik:Petar/Monobook.css.',
'updated' => '(Ažurirano)',
'note' => '<strong>Napomena:</strong>',
'previewnote' => '<strong>Ovo samo pretpregled; izmene još nisu sačuvane!</strong>',
'session_fail_preview' => '<strong>Žao nam je! Nismo mogli da obradimo vašu izmenu zbog gubitka podataka seanse. Molimo pokušajte kasnije. Ako i dalje ne radi, pokušajte da se odjavite i ponovo prijavite.</strong>',
'previewconflict' => 'Ovaj pretpregled oslikava kako će tekst u
tekstualnom polju izgledati ako se odlučite da ga snimite.',
'session_fail_preview_html' => '<strong>Žao nam je! Nismo mogli da obradimo vašu izmenu zbog gubitka podataka seanse.</strong>

\'\'Zbog toga što ova viki ima omogućen sirov HTML, pretpregled je sakriven kao predostrožnost protiv JavaScript napada.\'\'

<strong>Ako ste pokušali da napravite pravu izmenu, molimo pokušajte ponovo. Ako i dalje ne radi, pokušajte da se odjavite i ponovo prijavite.</strong>',
'importing' => 'Uvoz u toku: $1',
'editing' => 'Uređujete $1',
'editinguser' => 'Uređujete $1',
'editingsection' => 'Uređujete $1 (deo)',
'editingcomment' => 'Uređujete $1 (komentar)',
'editconflict' => 'Sukobljene izmene: $1',
'explainconflict' => 'Neko drugi je promenio ovu stranicu otkad ste vi počeli da je menjate.
Gornje tekstualno polje sadrži tekst stranice kakav trenutno postoji.
Vaše izmene su prikazane u donjem tekstu.
Moraćete da unesete svoje promene u postojeći tekst.
<b>Samo</b> tekst u gornjem tekstualnom polju će biti snimljen kada
pritisnete "Snimi stranicu".<br />',
'yourtext' => 'Vaš tekst',
'storedversion' => 'Uskladištena verzija',
'nonunicodebrowser' => '<strong>UPOZORENJE: Vaš brauzer ne podržava unikod. Molimo promenite ga pre nego što počnete sa uređivanjem članka.</strong>',
'editingold' => '<strong>PAŽNJA: Vi menjate stariju reviziju ove stranice.
Ako je snimite, sve promene učinjene od ove revizije biće izgubljene.</strong>',
'yourdiff' => 'Razlike',
'copyrightwarning' => 'Napomena: Za sve vaše doprinose se smatra da su izdati pod $2 (vidite $1 za detalje). Ako ne želite da se vaši doprinosi nemilosrdno menjaju, ne šaljite ih ovde.<br />
Takođe nam obećavate da ste ovo sami napisali ili prekopirali iz izvora u javnom vlasništvu ili sličnog slobodnog izvora.
<strong>NE ŠALJITE RADOVE ZAŠTIĆENE AUTORSKIM PRAVIMA BEZ DOZVOLE!</strong>',
'copyrightwarning2' => 'Napomena: Sve vaše doprinose ostali korisnici mogu da menjaju ili uklone. Ako ne želite da se vaši doprinosi nemilosrdno menjaju, ne šaljite ih ovde.<br />
Takođe nam obećavate da ste ovo sami napisali ili prekopirali iz izvora u javnom vlasništvu ili sličnog slobodnog izvora (vidite $1 za detalje).
<strong>NE ŠALJITE RADOVE ZAŠTIĆENE AUTORSKIM PRAVIMA BEZ DOZVOLE!</strong>',
'longpagewarning' => '<strong>PAŽNJA: Ova stranica ima $1 kilobajta; neki brauzeri imaju problema sa uređivanjem strana koje imaju blizu ili više od 32 kilobajta. Molimo vas da razmotrite razbijanje stranice na manje delove.</strong>',
'longpageerror' => '<strong>GREŠKA: Tekst koji snimate je velik $1 kilobajta, što je veće od maksimalno dozvoljene veličine koja iznosi $2 kilobajta. Nemoguće je snimiti stranicu.</strong>',
'readonlywarning' => '<strong>PAŽNJA: Baza je upravo zaključana zbog održavanja,
tako da sada nećete moći da snimite svoje izmene. Možda bi bilo dobro da iskopirate tekst u neki editor teksta i snimite ga za kasnije.</strong>',
'protectedpagewarning' => '<strong>PAŽNJA: Ova stranica je zaključana tako da samo korisnici sa
administratorskim privilegijama mogu da je menjaju. Uverite se
da pratite [[{{ns:project}}:Pravila o zaštiti stranica|pravila o zaštiti stranica]].',
'semiprotectedpagewarning' => '\'\'\'Napomena:\'\'\' Ova stranica je zaključana tako da je samo registrovani korisnici mogu uređivati.',
'templatesused' => 'Šabloni koji se koriste na ovoj stranici:',
'edittools' => '<!-- Tekst odavde će biti pokazan ispod formulara za uređivanje i slanje slika. -->',
'nocreatetitle' => 'Pravljenje stranice ograničeno',
'nocreatetext' => 'Na ovom sajtu je ograničeno pravljenje novih stranica. Možete se vratiti i urediti već postojeću stranu ili [[Posebno:Userlogin|se prijaviti ili napraviti nalog]].',
'cantcreateaccounttitle' => 'Ne može da se napravi nalog',
'cantcreateaccounttext' => 'Pravljenje naloga sa ove IP adrese (<b>$1</b>) je blokirano.
Ovo je verovatno zbog učestalog vandalizma iz vaše škole ili Internet servis provajdera.',

# History pages
#
'revhistory' => 'Istorija izmena',
'viewpagelogs' => 'Pogledaj protokole za ovu stranu',
'nohistory' => 'Ne postoji istorija izmena za ovu stranicu.',
'revnotfound' => 'Revizija nije pronađena',
'revnotfoundtext' => 'Starija revizija ove stranice koju ste zatražili nije nađena.
Molimo vas da proverite URL koji ste upotrebili da biste pristupili ovoj stranici.',
'loadhist' => 'Učitavam istoriju stranice',
'currentrev' => 'Trenutna revizija',
'revisionasof' => 'Revizija od $1',
'revision-info' => 'Revizija od $1; $2',
'previousrevision' => '← Prethodna revizija',
'nextrevision' => 'Sledeća revizija →',
'currentrevisionlink' => 'Trenutna revizija',
'cur' => 'tren',
'next' => 'sled',
'last' => 'posl',
'orig' => 'orig',
'histlegend' => 'Odabiranje razlika: odaberite kutijice revizija za upoređivanje i pritisnite enter ili dugme na dnu.<br />
Objašnjenje: (tren) = razlika sa trenutnom verzijom,
(posl) = razlika sa prethodnom verzijom, M = mala izmena',
'deletedrev' => '[obrisan]',
'histfirst' => 'Najranije',
'histlast' => 'Poslednje',
'rev-deleted-comment' => '(komentar uklonjen)',
'rev-deleted-user' => '(korisničko ime uklonjeno)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Revizija ove stranice je uklonjena iz javnih arhiva.
Moguće da ima više detalja u [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} istoriji brisanja].
</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">
Revizija ove stranice je uklonjena iz javnih arhiva.
Kao administrator, možete da je pogledate;
Moguće da ima više detalja u [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} istoriji brisanja].
</div>',
'rev-delundel' => 'pokaži/sakrij',

'history-feed-title' => 'Istorija revizija',
'history-feed-description' => 'Istorija revizija za ovu stranu na vikiju',
'history-feed-item-nocomment' => '$1, $2', # user at time
'history-feed-empty' => 'Tražena stranica ne postoji.
Moguće da je obrisana iz vikija ili preimenovana.
Pokušajte [[Posebno:Search|da pretražite viki]] za relevantne nove strane.',

# Revision deletion
#
'revisiondelete' => 'Obriši/vrati reviziju',
'revdelete-nooldid-title' => 'Nema odabrane revizije',
'revdelete-nooldid-text' => 'Niste odabrali željenu reviziju ili revizije kako biste uključili ove funkcije.',
'revdelete-selected' => 'Izabrano revizija od [[:$1]]:',
'revdelete-text' => 'Obrisane revizije će se i dalje pojavljivati na istoriji stranice,
ali će njihov sadržaj biti skriven javnosti.

Ostali administratori na ovoj Vikipediji će i dalje imati mogućnost da vide skriveni sadržaj i moći će da ga vrate ponovo putem ove iste komande, sve ukoliko nisu primenjene dodatne restrikcije operatora sajta.',
'revdelete-legend' => 'Postavi restrikcije revizija:',
'revdelete-hide-text' => 'Sakrij tekst revizije',
'revdelete-hide-comment' => 'Sakrij opis izmene',
'revdelete-hide-user' => 'Sakrij korisničko ime/IP adresu korisnika koji je uređivao stranicu',
'revdelete-hide-restricted' => 'Primeni ove restrikcije za administratore isto kao i za ostale',
'revdelete-log' => 'Komentar protokola:',
'revdelete-submit' => 'Primeni na izabrane revizije',
'revdelete-logentry' => 'promenjen prikaz revizije za [[$1]]',

# Diffs
#
'difference' => '(Razlika između revizija)',
'loadingrev' => 'učitavam reviziju za razliku',
'lineno' => 'Linija $1:',
'editcurrent' => 'Izmeni trenutnu verziju ove stranice',
'selectnewerversionfordiff' => 'Izaberi noviju verziju za upoređivanje',
'selectolderversionfordiff' => 'Izaberi stariju verziju za upoređivanje',
'compareselectedversions' => 'Uporedi označene verzije',

# Search results
#
'searchresults' => 'Rezultati pretrage',
'searchresulttext' => 'Za više informacija o pretraživanju sajta {{SITENAME}}, pogledajte [[{{ns:project}}:Pretraživanje|Pretraživanje sajta {{SITENAME}}]].',
'searchsubtitle' => 'Tražili ste \'\'\'[[:$1]]\'\'\'',
'searchsubtitleinvalid' => 'Tražili ste \'\'\'$1\'\'\'',
'badquery' => 'Loše oblikovan upit za pretragu',
'badquerytext' => 'Nismo mogli da obradimo vaš upit.
Ovo je verovatno zbog toga što ste pokušali da tražite
reč kraću od tri slova, što trenutno nije podržano.
Takođe je moguće da ste pogrešno ukucali izraz, na
primer "riba ima ima krljušti".
Molimo vas pokušajte nekim drugim upitom.',
'matchtotals' => 'Upit "$1" je nađen u $2 naslova članaka
i tekst $3 članaka.',
'noexactmatch' => 'Ne postoji stranica sa naslovom "$1". Možete [[$1|napraviti ovu stranicu]].',
'titlematches' => 'Naslov stranice odgovara',
'notitlematches' => 'Nijedan naslov stranice ne odgovara',
'textmatches' => 'Tekst stranice odgovara',
'notextmatches' => 'Nijedan tekst stranice ne odgovara',
'prevn' => 'prethodnih $1',
'nextn' => 'sledećih $1',
'viewprevnext' => 'Pogledaj ($1) ($2) ($3).',
'showingresults' => 'Prikazujem ispod <b>$1</b> rezultata počev od #<b>$2</b>.',
'showingresultsnum' => 'Prikazujem ispod <b>$3</b> rezultate počev od #<b>$2</b>.',
'nonefound' => '\'\'\'Napomena\'\'\': neuspešne pretrage su
često izazvane traženjem čestih reči kao "je" ili "od",
koje nisu indeksirane, ili navođenjem više od jednog izraza za traženje (samo stranice
koje sadrže sve izraze koji se traže će se pojaviti u rezultatu).',
'powersearch' => 'Traži',
'powersearchtext' => 'Pretraga u imenskim prostorima:<br />$1<br />$2 Izlistaj preusmerenja<br />Traži $3 $9',
'searchdisabled' => 'Pretraga za sajt {{SITENAME}} je onemogućena. U međuvremenu, možete koristiti Gugl pretragu. Imajte na umu da indeksi Gugla za sajt {{SITENAME}} mogu biti zastareli.',

'blanknamespace' => '(Glavno)',

# Preferences page
#
'preferences' => 'Podešavanja',
'mypreferences' => 'Moja podešavanja',
'prefsnologin' => 'Niste prijavljeni',
'prefsnologintext' => 'Morate biti [[{{ns:special}}:Userlogin|prijavljeni]]
da biste podešavali korisnička podešavanja.',
'prefsreset' => 'Vraćena su uskladištena podešavanja.',
'qbsettings' => 'Brza paleta',
'changepassword' => 'Promeni lozinku',
'skin' => 'Koža',
'math' => 'Matematike',
'dateformat' => 'Format datuma',
'datedefault' => 'Nije bitno',
'datetime' => 'Datum i vreme',
'math_failure' => 'Neuspeh pri parsiranju',
'math_unknown_error' => 'nepoznata greška',
'math_unknown_function' => 'nepoznata funkcija',
'math_lexing_error' => 'rečnička greška',
'math_syntax_error' => 'sintaksna greška',
'math_image_error' => 'PNG konverzija neuspešna; proverite tačnu instalaciju latex-a, dvips-a, gs-a i convert-a',
'math_bad_tmpdir' => 'Ne mogu da napišem ili napravim privremeni math direktorijum',
'math_bad_output' => 'Ne mogu da napišem ili napravim direktorijum za math izlaz.',
'math_notexvc' => 'Nedostaje izvršno texvc; molimo pogledajte math/README da biste podesili.',
'prefs-personal' => 'Korisnička podešavanja',
'prefs-rc' => 'Skorašnje izmene',
'prefs-watchlist' => 'Spisak nadgledanja',
'prefs-watchlist-days' => 'Broj dana koji treba da se vidi na spisku nadgledanja:',
'prefs-watchlist-edits' => 'Broj izmena koji treba da se vidi na proširenom spisku nadgledanja:',
'prefs-misc' => 'Razno',
'saveprefs' => 'Sačuvaj',
'resetprefs' => 'Vrati',
'oldpassword' => 'Stara lozinka:',
'newpassword' => 'Nova lozinka:',
'retypenew' => 'Ponovo otkucajte novu lozinku:',
'textboxsize' => 'Veličine tekstualnog polja',
'rows' => 'Redova',
'columns' => 'Kolona',
'searchresultshead' => 'Pretraga',
'resultsperpage' => 'Pogodaka po stranici:',
'contextlines' => 'Linija po pogotku:',
'contextchars' => 'Karaktera konteksta po liniji:',
'stubthreshold' => 'Granica za prikazivanje klica:',
'recentchangescount' => 'Broj naslova u skorašnjim izmenama:',
'savedprefs' => 'Vaša podešavanja su sačuvana.',
'timezonelegend' => 'Vremenska zona',
'timezonetext' => 'Broj sati za koji se vaše lokalno vreme razlikuje od serverskog vremena (UTC).',
'localtime' => 'Lokalno vreme',
'timezoneoffset' => 'Odstupanje¹',
'servertime' => 'Vreme na serveru',
'guesstimezone' => 'Popuni iz brauzera',
'allowemail' => 'Omogući e-poštu od drugih korisnika',
'defaultns' => 'Po standardu traži u ovim imenskim prostorima:',
'default' => 'standard',
'files' => 'Fajlovi',

# User rights
'userrights-lookup-user' => 'Upravljaj korisničkim grupama',
'userrights-user-editname' => 'Unesite korisničko ime:',
'editusergroup' => 'Menjaj grupe korisnika',

'userrights-editusergroup' => 'Promeni korisničke grupe',
'saveusergroups' => 'Sačuvaj korisničke grupe',
'userrights-groupsmember' => 'Član:',
'userrights-groupsavailable' => 'Dostupne grupe:',
'userrights-groupshelp' => 'Odabrane grupe od kojih želite da se ukloni korisnik ili da se doda.
Neodabrane grupe neće biti promenjene. Možete da deselektujete grupu koristeći CTRL + levi klik',

# Groups
'group' => 'Grupa:',
'group-bot' => 'botovi',
'group-sysop' => 'administratori',
'group-bureaucrat' => 'birokrate',
'group-all' => '(svi)',

'group-bot-member' => 'bot',
'group-sysop-member' => 'administrator',
'group-bureaucrat-member' => 'birokrata',

'grouppage-bot' => 'Project:Botovi',
'grouppage-sysop' => 'Project:Spisak administratora',
'grouppage-bureaucrat' => 'Project:Birokrate',

# Recent changes
#
'changes' => 'izmene',
'recentchanges' => 'Skorašnje izmene',
'recentchangestext' => 'Ovde pratite najskorije izmene na vikiju.',

'rcnote' => 'Ispod je poslednjih <strong>$1</strong> promena u poslednjih <strong>$2</strong> dana.',
'rcnotefrom' => 'Ispod su promene od <b>$2</b> (do <b>$1</b> prikazano).',
'rclistfrom' => 'Pokaži nove promene počev od $1',
'rcshowhideminor' => '$1 male izmene',
'rcshowhidebots' => '$1 botove',
'rcshowhideliu' => '$1 prijavljene korisnike',
'rcshowhideanons' => '$1 anonimne korisnike',
'rcshowhidepatr' => '$1 patrolirane izmene',
'rcshowhidemine' => '$1 sopstvene izmene',
'rclinks' => 'Pokaži poslednjih $1 promena u poslednjih $2 dana<br />$3',
'diff' => 'razl',
'hist' => 'ist',
'hide' => 'sakrij',
'show' => 'pokaži',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'b',
'sectionlink' => '→',
'number_of_watching_users_pageview' => '[$1 korisnik/a koji nadgleda/ju]',
'rc_categories' => 'Ograniči na kategorije (razdvoji sa "|")',
'rc_categories_any' => 'Bilo koji',

# Upload
#
'upload' => 'Pošalji fajl',
'uploadbtn' => 'Pošalji fajl',
'reupload' => 'Ponovo pošalji',
'reuploaddesc' => 'Vrati se na upitnik za slanje.',
'uploadnologin' => 'Niste prijavljeni',
'uploadnologintext' => 'Morate biti [[{{ns:special}}:Userlogin|prijavljeni]]
da biste slali fajlove.',
'upload_directory_read_only' => 'Na direktorijum za slanje ($1) server ne može da piše.',
'uploaderror' => 'Greška pri slanju',
'uploadtext' => 'Koristite donji obrazac da pošaljete fajlove. Za gledanje ili pretraživanje već poslatih slika, idite na [[{{ns:special}}:Imagelist|spisak poslatih fajlova]]. Slanja i brisanja se beleže u [[{{ns:special}}:Log/upload|istoriji slanja]]

Da biste ubacili sliku na stranu, koristite vezu u obliku
\'\'\'<nowiki>[[{{ns:image}}:Fajl.jpg]]</nowiki>\'\'\',
\'\'\'<nowiki>[[{{ns:image}}:Fajl.png|opis slike]]</nowiki>\'\'\' ili
\'\'\'<nowiki>[[{{ns:media}}:Fajl.ogg]]</nowiki>\'\'\' za direktno povezivanje na fajl.',
'uploadlog' => 'istorija slanja',
'uploadlogpage' => 'istorija slanja',
'uploadlogpagetext' => 'Ispod je spisak najskorijih slanja.',
'filename' => 'Ime fajla',
'filedesc' => 'Opis',
'fileuploadsummary' => 'Opis:',
'filestatus' => 'Status autorskog prava',
'filesource' => 'Izvor',
'copyrightpage' => 'Project:Autorska prava',
'copyrightpagename' => 'Autorska prava projekta {{SITENAME}}',
'uploadedfiles' => 'Poslati fajlovi',
'ignorewarning' => 'Ignoriši upozorenja i snimi datoteku.',
'ignorewarnings' => 'Ignoriši sva upozorenja',
'minlength' => 'Imena slika moraju imati bar tri slova.',
'illegalfilename' => 'Fajl "$1" sadrži karaktere koji nisu dozvoljeni u nazivima stranica. Molimo Vas promenite ime fajla i ponovo ga pošaljite.',
'badfilename' => 'Ime slike je promenjeno u "$1".',
'badfiletype' => '".$1" nije preporučeni format slike.',
'largefile' => 'Preporučuje se da slike ne pređu veličinu od $1 bajtova; ova slika ima $2 bajtova.',
'largefileserver' => 'Ovaj fajl je veći nego što je podešeno da server dozvoli.',
'emptyfile' => 'Fajl koji ste poslali deluje da je prazan. Ovo je moguće zbog greške u imenu fajla. Molimo proverite da li stvarno želite da pošaljete ovaj fajl.',
'fileexists' => 'Fajl sa ovim imenom već postoji. Molimo proverite $1 ako niste sigurni da li želite da ga promenite.',
'fileexists-forbidden' => 'Fajl sa ovim imenom već postoji; molimo vratite se i pošaljite ovaj fajl pod novim imenom. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fajl sa ovim imenom već postoji u zajedničkoj ostavi; molimo vratite se i pošaljite ovaj fajl pod novim imenom. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload' => 'Uspešno slanje',
'fileuploaded' => 'Fajl "$1" je uspešno poslat.
Molim pratite ovu vezu: $2 do stranice za opis i unesite
informacije o fajlu, npr. odakle je, kada i
ko ga je napravio, i bilo šta drugo što znate o njemu.
Ako je ovo slika, možete je uneti ovako: <tt><nowiki>[[Image:$1|thumb|Opis]]</nowiki></tt>',
'uploadwarning' => 'Upozorenje pri slanju',
'savefile' => 'Snimi fajl',
'uploadedimage' => 'poslao "[[$1]]"',
'uploaddisabled' => 'Slanje fajlova je isključeno.',
'uploaddisabledtext' => 'Slanja fajlova su onemogućena na ovom vikiju.',
'uploadscripted' => 'Ovaj fajl sadrži HTML ili kod skripte koje internet brauzer može pogrešno da interpretira.',
'uploadcorrupt' => 'Fajl je neispravan ili ima netačnu ekstenziju. Molimo proverite fajl i pošaljite ga ponovo.',
'uploadvirus' => 'Fajl sadrži virus! Detalji: $1',
'sourcefilename' => 'Ime fajla izvora',
'destfilename' => 'Ciljano ime fajla',
'watchthisupload' => 'Nadgledaj stranicu',
'filewasdeleted' => 'Fajl sa ovim imenom je ranije poslat, a kasnije obrisan. Trebalo bi da proverite $1 pre nego što nastavite sa ponovnim slanjem.',

'upload-proto-error' => 'Nekorektni protokol',
'upload-proto-error-text' => 'Slanje eksternih fajlova zahteva URLove koji počinju sa <code>http://</code> ili <code>ftp://</code>.',
'upload-file-error' => 'Interna greška',
'upload-file-error-text' => 'Desila se interna greška pri pokušaju pravljenja privremenog fajla na serveru. Kontaktirajte sistem administratora.',
'upload-misc-error' => 'Nepoznata greška pri slanju fajla',
'upload-misc-error-text' => 'Nepoznata greška pri slanju fajla. Proverite da li je URL ispravan i pokušajte ponovo. Ako problem ostane, kontaktirajte sistem administratora.',
# Some likely curl errors.  More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => "URL nije dostupan",
'upload-curl-error6-text' => 'URL koji ste uneli nije dostupan. Uradite dupli klik na URL da proverite da li je adresa dostupna.',
'upload-curl-error28' => 'Tajmaut greška',
'upload-curl-error28-text' => 'Sajtu je trebalo previše vremena da odgovori. Proverite da li sajt radi, ili sačekajte malo i pokušajte ponovo.',

'license' => 'Licenca',
'nolicense' => 'Nema',
'upload_source_url' => ' (validan, javno dostupan URL)',
'upload_source_file' => ' (fajl na vašem računaru)',

# Image list
#
'imagelist' => 'Spisak slika',
'imagelisttext' => 'Ispod je spisak od \'\'\'$1\'\'\' {{plural:$1|fajla|fajla|fajlova}} poređanih $2.',
'imagelistforuser' => 'Ovo je spisak slika koje je poslao korisnik $1.',
'getimagelist' => 'pribavljam spisak slika',
'ilsubmit' => 'Traži',
'showlast' => 'Prikaži poslednjih $1 slika poređanih $2.',
'bydate' => 'po datumu',
'byname' => 'po imenu',
'bysize' => 'po veličini',
'imgdelete' => 'obr',
'imgdesc' => 'opis',
'imgfile' => 'fajl',
'imghistory' => 'istorija slike',
'imglegend' => 'Objašnjenje: (opis) = prikaži/izmeni opis slike.',
'revertimg' => 'vrt',
'deleteimg' => 'obr',
'deleteimgcompletely' => 'Obriši sve revizije ovog fajla',
'imghistlegend' => 'Objašnjenje: (tren) = ovo je trenutna slika, (obr) = obriši
ovu staru verziju, (vrt) = vrati na ovu staru verziju.
<br /><i>Kliknite na datum da vidite sliku poslatu tog datuma</i>.',
'imagelinks' => 'Upotreba slike',
'linkstoimage' => 'Sledeće stranice koriste ovaj fajl:',
'nolinkstoimage' => 'Nema stranica koje koriste ovaj fajl.',
'sharedupload' => 'Ova slika je sa zajedničke ostave i možda je koriste ostali projekti.',
'shareduploadwiki' => 'Molimo pogledajte $1 za dalje informacije.',
'shareduploadwiki-linktext' => 'strana za opis fajla',
'noimage' => 'Ne postoji fajl sa ovim imenom, možete ga $1',
'noimage-linktext' => 'poslati',
'uploadnewversion-linktext' => 'Pošaljite noviju verziju ovog fajla',
'imagelist_date' => 'Datum',
'imagelist_name' => 'Ime',
'imagelist_user' => 'Korisnik',
'imagelist_size' => 'Veličina (bajtovi)',
'imagelist_description' => 'Opis slike',
'imagelist_search_for' => 'Traži ime slike:',

# Mime search
#
'mimesearch' => 'MIME pretraga',
'mimetype' => 'MIME tip:',
'download' => 'Preuzmi',

# Unwatchedpages
#
'unwatchedpages' => 'Nenadgledane stranice',

# List redirects
'listredirects' => 'Spisak preusmerenja',

# Unused templates
'unusedtemplates' => 'Neiskorišćeni šabloni',
'unusedtemplatestext' => 'Ova strana navodi sve stranice u imenskom prostoru šablona koje nisu uključene ni na jednoj drugoj strani. Ne zaboravite da proverite ostale veze ka šablonima pre nego što ih obrišete.',
'unusedtemplateswlh' => 'ostale veze',

# Random redirect
'randomredirect' => 'Slučajno preusmerenje',

# Statistics
#
'statistics' => 'Statistike',
'sitestats' => 'Statistike sajta',
'userstats' => 'Statistike korisnika',
'sitestatstext' => 'Postoji ukupno \'\'\'$1\'\'\' stranica u bazi podataka. Ovaj broj uključuje strane za razgovor, stranice o sajtu, preusmerenja, članke bez ijedne poveznice i ostale stranice koje se ne mogu računati kao članci. Ne računajući njih, postoji \'\'\'$2\'\'\' stranica koje su verovatno legitimni članci.

Na ovaj sajt je poslato \'\'\'$8\'\'\' slika.

Stranice su ukupno pogledane \'\'\'$3\'\'\' puta i \'\'\'$4\'\'\' izmena od postavljanja vikija. Ovo znači da je bilo u proseku \'\'\'$5\'\'\' izmena po stranici i \'\'\'$6\'\'\' pogleda po stranici.

Dužina reda za poslove iznosi \'\'\'$7\'\'\'',
'userstatstext' => 'Postoji \'\'\'$1\'\'\' registrovanih korisnika, od kojih su \'\'\'$2\'\'\' (ili $4%) administratori (pogledajte $3).',
'statistics-mostpopular' => 'Najposećenije stranice',

'disambiguations' => 'Stranice za višeznačne odrednice',
'disambiguationspage' => '{{ns:template}}:Višeznačna odrednica',
'disambiguationstext' => 'Sledeći članci se povezuju sa <i>višeznačnom odrednicom</i>. Umesto toga, oni bi trebalo da se povezuju sa odgovarajućom temom.<br />Stranica se tretira da je višeznačna odrednica ako je povezana sa $1.<br />Poveznice iz ostalih imenskih prostora <i>nisu</i> navedene ovde.',

'doubleredirects' => 'Dvostruka preusmerenja',
'doubleredirectstext' => 'Svaki red sadrži veze na prvo i drugo preusmerenje, kao i na prvu liniju teksta drugog preusmerenja, što obično daje "pravi" ciljni članak, na koji bi prvo preusmerenje i trebalo da pokazuje.',

'brokenredirects' => 'Pokvarena preusmerenja',
'brokenredirectstext' => 'Sledeća preusmerenja su povezana na nepostojeći članak.',

# Miscellaneous special pages
#
'nbytes' => '$1 {{PLURAL:$1|bajt|bajta|bajtova}}',
'ncategories' => '$1 {{PLURAL:$1|kategorija|kategorije|kategorija}}',
'nlinks' => '$1 {{PLURAL:$1|veza|veze|veza}}',
'nmembers' => '$1 {{PLURAL:$1|članak|članka|članaka}}',
'nrevisions' => '$1 {{PLURAL:$1|revizija|revizije|revizija}}',
'nviews' => '$1 puta pogledano',

'lonelypages' => 'Siročići',
'lonelypagestext' => 'Sledeće stranice nisu povezane sa drugih stranica na ovom vikiju.',
'uncategorizedpages' => 'Stranice bez kategorije',
'uncategorizedcategories' => 'Kategorije bez kategorija',
'uncategorizedimages' => 'Slike bez kategorija',
'unusedcategories' => 'Neiskorišćene kategorije',
'unusedimages' => 'Neiskorišćeni fajlovi',
'popularpages' => 'Popularne stranice',
'wantedcategories' => 'Tražene kategorije',
'wantedpages' => 'Tražene stranice',
'mostlinked' => 'Najviše povezane strane',
'mostlinkedcategories' => 'Najviše povezane kategorije',
'mostcategories' => 'Članci sa najviše kategorija',
'mostimages' => 'Najviše povezane slike',
'mostrevisions' => 'Članci sa najviše revizija',
'allpages' => 'Sve stranice',
'prefixindex' => 'Spisak prefiksa',
'randompage' => 'Slučajna stranica',
'shortpages' => 'Kratke stranice',
'longpages' => 'Dugačke stranice',
'deadendpages' => 'Stranice bez internih veza',
'deadendpagestext' => 'Sledeće stranice ne vežu na druge stranice na ovom vikiju.',
'listusers' => 'Spisak korisnika',
'specialpages' => 'Posebne stranice',
'spheading' => 'Posebne stranice za sve korisnike',
'restrictedpheading' => 'Zaštićene posebne stranice',
'recentchangeslinked' => 'Srodne promene',
'rclsub' => '(na stranice povezane od "$1")',
'newpages' => 'Nove stranice',
'newpages-username' => 'Korisničko ime:',
'ancientpages' => 'Najstariji članci',
'intl' => 'Međujezičke veze',
'move' => 'premesti',
'movethispage' => 'premesti ovu stranicu',
'unusedimagestext' => '<p>Obratite pažnju da se drugi veb sajtovi
mogu povezivati na sliku direktnim URL-om, i tako mogu još uvek biti prikazani ovde uprkos
aktivnoj upotrebi.',
'unusedcategoriestext' => 'Naredne strane kategorija postoje iako ih ni jedan drugi članak ili kategorija ne koriste.',

'booksources' => 'Štampani izvori',
'categoriespagetext' => 'Sledeće kategorije već postoje na vikiju',
'data' => 'Podaci',
'userrights' => 'Upravljanje korisničkim pravima',
'groups' => 'Korisničke grupe',

'booksourcetext' => 'Ispod je spisak veza ka drugim sajtovima koji
prodaju nove i korišćene knjige, i takođe mogu imati daljnje informacije
o knjigama koje tražite.',
'isbn' => 'ISBN',
'alphaindexline' => '$1 u $2',
'version' => 'Verzija',
'log' => 'Protokoli',
'alllogstext' => 'Kombinovani prikaz istorija slanja, brisanja, zaštite, blokiranja i administratorskih prava.
Možete suziti pregled odabirom tipa istorije, korisničkog imena ili tražene stranice.',
'logempty' => 'Protokol je prazan.',

# Special:Allpages
'nextpage' => 'Sledeća stranica ($1)',
'allpagesfrom' => 'Prikaži stranice početno sa:',
'allarticles' => 'Svi članci',
'allinnamespace' => 'Sve stranice ($1 imenski prostor)',
'allnotinnamespace' => 'Sve stranice (koje nisu u $1 imenskom prostoru)',
'allpagesprev' => 'Prethodna',
'allpagesnext' => 'Sledeća',
'allpagessubmit' => 'Idi',
'allpagesprefix' => 'Prikaži strane sa prefiksom:',
'allpagesbadtitle' => 'Dati naziv stranice nije dobar ili sadrži međujezički ili interviki prefiks. Moguće je da sadrži karaktere koji ne mogu da se koriste u nazivima.',

# Special:Listusers
'listusersfrom' => 'Prikaži korisnike počevši od:',

# Email this user
#
'mailnologin' => 'Nema adrese za slanje',
'mailnologintext' => 'Morate biti [[{{ns:special}}:Userlogin|prijavljeni]]
i imati ispravnu adresu e-pošte u vašim [[Special:Preferences|podešavanjima]]
da biste slali e-poštu drugim korisnicima.',
'emailuser' => 'Pošalji e-poštu ovom korisniku',
'emailpage' => 'Pošalji e-pismo korisniku',
'emailpagetext' => 'Ako je ovaj korisnik uneo ispravnu adresu e-pošte u
svoja korisnička podešavanja, upitnik ispod će poslati jednu poruku.
Adresa e-pošte koju ste vi uneli u svojim korisničkim podešavanjima će se pojaviti
kao "From" adresa poruke, tako da će primalac moći da odgovori.',
'usermailererror' => 'Objekat pošte je vratio grešku:',
'defemailsubject' => '{{SITENAME}} e-pošta',
'noemailtitle' => 'Nema adrese e-pošte',
'noemailtext' => 'Ovaj korisnik nije naveo ispravnu adresu e-pošte,
ili je izabrao da ne prima e-poštu od drugih korisnika.',
'emailfrom' => 'Od',
'emailto' => 'Za',
'emailsubject' => 'Tema',
'emailmessage' => 'Poruka',
'emailsend' => 'Pošalji',
'emailccme' => 'Pošalji mi kopiju moje poruke u moje sanduče e-pošte.',
'emailccsubject' => 'Kopija vaše poruke na $1: $2',
'emailsent' => 'Poruka poslata',
'emailsenttext' => 'Vaša poruka je poslata elektronskom poštom.',

# Watchlist
'watchlist' => 'Moj spisak nadgledanja',
'watchlistfor' => '(za \'\'\'$1\'\'\')',
'nowatchlist' => 'Nemate ništa na svom spisku nadgledanja.',
'watchlistanontext' => 'Molimo $1 da biste gledali ili menjali stavke na vašem spisku nadgledanja.',
'watchlistcount' => '\'\'\'Imate $1 {{plural:$1|stavku|stavke|stavki}} na vašem spisku nadgledanja, uključujući strane za razgovor.\'\'\'',
'clearwatchlist' => 'Obriši spisak nadgledanja',
'watchlistcleartext' => 'Da li ste sigurni da želite da ih uklonite?',
'watchlistclearbutton' => 'Obriši spisak nadgledanja',
'watchlistcleardone' => 'Vaš spisak nadgledanja je obrisan. $1 {{plural:$1|stavka|stavke|stavki}} je uklonjeno.',
'watchnologin' => 'Niste prijavljeni',
'watchnologintext' => 'Morate biti [[{{ns:special}}:Userlogin|prijavljeni]] da biste menjali spisak nadgledanja.',
'addedwatch' => 'Dodato spisku nadgledanja',
'addedwatchtext' => 'Stranica "[[:$1]]" je dodata vašem [[{{ns:special}}:Watchlist|spisku nadgledanja]] .
Buduće promene na ovoj stranici i njoj pridruženoj stranici za razgovor biće navedene ovde, i stranica će biti \'\'\'podebljana\'\'\' u [[{{ns:special}}:Recentchanges|spisku skorašnjih izmena]] da bi se lakše uočila.

Ako kasnije želite da uklonite stranicu sa vašeg spiska nadgledanja, kliknite na "ne nadgledaj" na bočnoj paleti.',
'removedwatch' => 'Uklonjeno sa spiska nadgledanja',
'removedwatchtext' => 'Stranica "[[:$1]]" je uklonjena sa vašeg spiska nadgledanja.',
'watch' => 'nadgledaj',
'watchthispage' => 'Nadgledaj ovu stranicu',
'unwatch' => 'Prekini nadgledanje',
'unwatchthispage' => 'Prekini nadgledanje',
'notanarticle' => 'Nije članak',
'watchnochange' => 'Ništa što nadgledate nije promenjeno u prikazanom vremenu.',
'watchdetails' => '* $1 stranica nadgledano ne računajući stranice za razgovor;
* [[{{ns:special}}:Watchlist/edit|prikaži i menjaj potpuni spisak]]
* [[{{ns:special}}:Watchlist/clear|ukloni sve stranice]]',
'wlheader-enotif' => '* Obaveštavanje e-poštom je omogućeno.',
'wlheader-showupdated' => '* Stranice koje su izmenjene od kada ste ih poslednji put posetili su prikazane \'\'\'podebljano\'\'\'',
'watchmethod-list' => 'proveravam ima li skorašnjih izmena u nadgledanim stranicama',
'watchmethod-recent' => 'proveravam ima li nadgledanih stranica u skorašnjim izmenama',
'removechecked' => 'Ukloni obeležene unose sa spiska nadgledanja',
'watchlistcontains' => 'Vaš spisak nadgledanja sadrži $1 stranica.',
'watcheditlist' => 'Ovde je azbučni spisak stranica
koje nadgledate. Obeležite kućice stranica koje želite da uklonite
sa svog spiska nadgledanja i kliknite na dugme \'ukloni obeležene\'
na dnu ekrana (uklanjanje stranice takođe uklanja i prateću stranu za razgovor i obrnuto).',
'removingchecked' => 'Uklanjam obeležene stvari sa spiska nadgledanja...',
'couldntremove' => 'Ne mogu da uklonim stavku \'$1\'...',
'iteminvalidname' => 'Problem sa stavkom \'$1\', neispravno ime...',
'wlnote' => 'Ispod je poslednjih $1 izmena u poslednjih <b>$2</b> sati.',
'wlshowlast' => 'Prikaži poslednjih $1 sati $2 dana $3',
'wlsaved' => 'Ovo je sačuvana verzija vašeg spiska nadgledanja.',
'wlhideshowown' => '$1 moje izmene.',
'wlhideshowbots' => '$1 izmena botova.',
'wldone' => 'Gotovo.',

'enotif_mailer' => '{{SITENAME}} pošta obaveštenja',
'enotif_reset' => 'Označi sve strane kao posećene',
'enotif_newpagetext' => 'Ovo je novi članak.',
'changed' => 'promenjena',
'created' => 'napravljena',
'enotif_subject' => '{{SITENAME}} stranica $PAGETITLE je bila $CHANGEDORCREATED od strane $PAGEEDITOR',
'enotif_lastvisited' => 'Pogledajte $1 za sve promene od vaše poslednje posete.',
'enotif_body' => 'Dragi $WATCHINGUSERNAME,

{{SITENAME}} stranicaa $PAGETITLE je bila $CHANGEDORCREATED ($PAGEEDITDATE) od strane $PAGEEDITOR,
pogledajte $PAGETITLE_URL za trenutnu verziju.

$NEWPAGE

Opis izmene urednika: $PAGESUMMARY $PAGEMINOREDIT

Kontaktirajte urednika:
pošta: $PAGEEDITOR_EMAIL
viki: $PAGEEDITOR_WIKI

Neće biti drugih obaveštenja u slučaju daljih promena ukoliko ne posetite ovu stranu.
Takođe možete da resetujete zastavice za obaveštenja za sve vaše nadgledane strane na vašem spisku nadgledanja.

             Vaš prijateljski {{SITENAME}} sistem obaveštavanja

--
Da promenite podešavanja vezana za spisak nadgledanja posetite
{{fullurl:{{ns:special}}:Watchlist/edit}}

Fidbek i dalja pomoć:
{{fullurl:{{ns:help}}:Sadržaj}}',

# Delete/protect/revert
#
'deletepage' => 'Obriši stranicu',
'confirm' => 'Potvrdi',
'excontent' => 'sadržaj je bio: \'$1\'',
'excontentauthor' => 'sadržaj je bio: \'$1\' (a jedinu izmenu je napravio \'$2\')',
'exbeforeblank' => 'sadržaj pre brisanja je bio: \'$1\'',
'exblank' => 'stranica je bila prazna',
'confirmdelete' => 'Potvrdi brisanje',
'deletesub' => '(Brišem "$1")',
'historywarning' => 'Pažnja: stranica koju želite da obrišete ima istoriju:',
'confirmdeletetext' => 'Na putu ste da trajno obrišete stranicu
ili sliku zajedno sa njenom istorijom iz baze podataka.
Molimo vas potvrdite da nameravate da uradite ovo, da razumete
posledice, i da ovo radite u skladu sa
[[{{ns:project}}:Pravila i smernice|pravilima]].',
'actioncomplete' => 'Akcija završena',
'deletedtext' => 'Članak "$1" je obrisan.
Pogledajte $2 za zapis o skorašnjim brisanjima.',
'deletedarticle' => 'obrisan "[[$1]]"',
'dellogpage' => 'istorija brisanja',
'dellogpagetext' => 'Ispod je spisak najskorijih brisanja.',
'deletionlog' => 'istorija brisanja',
'reverted' => 'Vraćeno na raniju reviziju',
'deletecomment' => 'Razlog za brisanje',
'imagereverted' => 'Vraćanje na raniju verziju je uspešno.',
'rollback' => 'Vrati izmene',
'rollback_short' => 'Vrati',
'rollbacklink' => 'vrati',
'rollbackfailed' => 'Vraćanje nije uspelo',
'cantrollback' => 'Ne mogu da vratim izmenu; poslednji autor je ujedno i jedini.',
'alreadyrolled' => 'Ne mogu da vratim poslednju izmenu [[:$1]]
od korisnika [[{{ns:user}}:$2|$2]] ([[{{ns:user_talk}}:$2|razgovor]]); neko drugi je već izmenio ili vratio članak.

Poslednju izmenu je napravio korisnik [[{{ns:user}}:$3|$3]] ([[{{ns:user_talk}}:$3|razgovor]]).',
#   only shown if there is an edit comment
'editcomment' => 'Komentar izmene je: "<i>$1</i>".',
'revertpage' => 'Vraćene izmene od [[{{ns:special}}:Contributions/$2|$2]] ([[{{ns:user_talk}}:$2|razgovor]]) na poslednju izmenu od korisnika [[{{ns:user}}:$1|$1]]',
'sessionfailure' => 'Izgleda da postoji problem sa vašom seansom prijave;
ova akcija je prekinuta kao predostrožnost protiv preotimanja seansi.
Molimo kliknite "back" i ponovo učitajte stranu odakle ste došli, a onda pokušajte ponovo.',
'protectlogpage' => 'istorija zaključavanja',
'protectlogtext' => 'Ispod je spisak zaključavanja i otključavanja stranica.',
'protectedarticle' => 'zaštitio $1',
'unprotectedarticle' => 'skinuo zaštitu sa $1',
'protectsub' => '(stavljanje zaštite "$1")',
'confirmprotecttext' => 'Da li zaista želite da zaštitite ovu stranicu?',
'confirmprotect' => 'Potvrdite zaštitu',
'protectmoveonly' => 'Zaštićeno samo od pomeranja',
'protectcomment' => 'Razlog zaštite',
'unprotectsub' => '(skidanje zaštite "$1")',
'confirmunprotecttext' => 'Da li zaista želite da skinete zaštitu sa ove stranice?',
'confirmunprotect' => 'Potvrdite skidanje zaštite',
'unprotectcomment' => 'Razlog za skidanje zaštite',
'protect-unchain' => 'Otključaj dozvole premeštanja',
'protect-text' => 'Ovde možete pogledati i menjati nivo zaštite za stranicu <strong>$1</strong>.',
'protect-viewtext' => 'Vaš nalog nema pristup izmenama
nivoa zaštite. Ovo su trenutna podešavanja za stranicu <strong>$1</strong>:',
'protect-default' => '(standard)',
'protect-level-autoconfirmed' => 'Blokiraj neregistrovane korisnike',
'protect-level-sysop' => 'Samo za administratore',

# restrictions (nouns)
'restriction-edit' => 'Uređivanje',
'restriction-move' => 'Premeštanje',

# Undelete
'undelete' => 'Pogledaj obrisane stranice',
'undeletepage' => 'Pogledaj i vrati obrisane stranice',
'viewdeletedpage' => 'Pogledaj obrisane strane',
'undeletepagetext' => 'Sledeće stranice su obrisane ali su još uvek u arhivi i
mogu biti vraćene. Arhiva može biti periodično čišćena.',
'undeleteextrahelp' => 'Da vratite celu stranu, ostavite sve kućice neotkačenim i kliknite na \'\'\'\'\'Vrati\'\'\'\'\'. Da izvršite selektivno vraćanje, otkačite kućice koje odgovaraju reviziji koja treba da se vrati i kliknite na \'\'\'\'\'Vrati\'\'\'\'\'. Klikom na \'\'\'\'\'Poništi\'\'\'\'\' ćete obrisati polje za komentar i sve kućice.',
'undeletearticle' => 'Vrati obrisani članak',
'undeleterevisions' => '$1 revizija arhivirano',
'undeletehistory' => 'Ako vratite stranicu, sve revizije će biti vraćene njenoj istoriji.
Ako je nova stranica istog imena napravljena od brisanja, vraćene
revizije će se pojaviti u ranijoj istoriji, a trenutna revizija sadašnje stranice
neće biti automatski zamenjena.',
'undeletehistorynoadmin' => 'Ova strana je obrisana. Razlog za brisanje se nalazi u opisu ispod, zajedno sa detaljima o korisniku koji je menjao ovu stranu pre brisanja. Stvarni tekst ovih obrisanih revizija je dostupan samo administratorima.',
'undeleterevision' => 'Obrisana revizija od $1',
'undeleterevision-missing' => 'Nekorektna ili nepostojeća revizija. Možda je vaš link pogrešan, ili je revizija restaurirana, ili obrisana iz arhive.',
'undeletebtn' => 'Vrati!',
'undeletereset' => 'Poništi',
'undeletecomment' => 'Komentar:',
'undeletedarticle' => 'vratio "[[$1]]"',
'undeletedrevisions' => '$1 revizija vraćeno',
'undeletedrevisions-files' => '$1 {{plural:$1|revizija|revizije|revizija}} i $2 {{plural:$2|fajl|fajla|fajlova}} vraćeno',
'undeletedfiles' => '$1 {{plural:$1|fajl vraćen|fajla vraćena|fajlova vraćeno}}',
'cannotundelete' => 'Vraćanje obrisane verzije nije uspelo; neko drugi je vratio stranicu pre vas.',
'undeletedpage' => '<big>\'\'\'Strana $1 je vraćena\'\'\'</big>

Pogledajte [[{{ns:special}}:Log/delete|istoriju brisanja]] za spisak skorašnjih brisanja i vraćanja.',

# Namespace form on various pages
'namespace' => 'Imenski prostor:',
'invert' => 'Obrni selekciju',

# Contributions
#
'contributions' => 'Prilozi korisnika',
'mycontris' => 'Moji prilozi',
'contribsub' => 'Za $1',
'nocontribs' => 'Nisu nađene promene koje zadovoljavaju ove uslove.',
'ucnote' => 'Ispod je poslednjih <b>$1</b> promena u poslednjih <b>$2</b> dana.',
'uclinks' => 'Gledaj poslednjih $1 promena; gledaj poslednjih $2 dana.',
'uctop' => ' (vrh)',
'newbies' => 'novajlije',

'sp-newimages-showfrom' => 'Prikaži nove slike počevši od $1',

'sp-contributions-newest' => 'Najnovijih',
'sp-contributions-oldest' => 'Najstarijih',
'sp-contributions-newer' => 'Novijih $1',
'sp-contributions-older' => 'Starijih $1',
'sp-contributions-newbies-sub' => 'Za novajlije',

# What links here
#
'whatlinkshere' => 'Šta je povezano ovde',
'notargettitle' => 'Nema cilja',
'notargettext' => 'Niste naveli ciljnu stranicu ili korisnika
na kome bi se izvela ova funkcija.',
'linklistsub' => '(spisak veza)',
'linkshere' => 'Sledeće stranice su povezane ovde:',
'nolinkshere' => 'Ni jedna stranica nije povezana ovde.',
'isredirect' => 'preusmerivač',
'istemplate' => 'uključivanje',

# Block/unblock IP
#
'blockip' => 'Blokiraj korisnika',
'blockiptext' => 'Upotrebite donji upitnik da biste uklonili pravo pisanja
sa određene IP adrese ili korisničkog imena.
Ovo bi trebalo da bude urađeno samo da bi se sprečio vandalizam, i u skladu
sa [[{{ns:project}}:Politika|politikom]].
Unesite konkretan razlog ispod (na primer, navodeći koje
stranice su vandalizovane).',
'ipaddress' => 'IP adresa',
'ipadressorusername' => 'IP adresa ili korisničko ime',
'ipbexpiry' => 'Trajanje',
'ipbreason' => 'Razlog',
'ipbanononly' => 'Blokiraj samo anonimne korisnike',
'ipbcreateaccount' => 'Spreči pravljenje naloga',
'ipbenableautoblock' => 'Automatski blokiraj poslednju IP adresu ovog korisnika, i svaku sledeću adresu sa koje se pokuša uređivanje.',
'ipbsubmit' => 'Blokiraj ovog korisnika',
'ipbother' => 'Ostalo vreme',
'ipboptions' => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 nedelja:1 week,2 nedelje:2 weeks,1 mesec:1 month,3 meseca:3 months,6 meseci:6 months,1 godina:1 year,beskonačno:infinite',
'ipbotheroption' => 'ostalo',
'badipaddress' => 'Loša IP adresa',
'blockipsuccesssub' => 'Blokiranje je uspelo',
'blockipsuccesstext' => '[[{{ns:special}}:Contributions/$1|$1]] je blokiran.
<br />Vidite [[{{ns:special}}:Ipblocklist|spisak blokiranja]] da biste pregledali blokiranja.',
'unblockip' => 'Odblokiraj korisnika',
'unblockiptext' => 'Upotrebite donji upitnik da biste vratili pravo pisanja
ranije blokiranoj IP adresi ili korisničkom imenu.',
'ipusubmit' => 'Odblokiraj ovu adresu',
'unblocked' => '[[{{ns:user}}:$1|$1]] je odblokiran',
'ipblocklist' => 'Spisak blokiranih IP adresa i korisnika',
'blocklistline' => '$1, $2 blokirao korisnika [[{{ns:user}}:$3|$3]], (ističe $4)',
'infiniteblock' => 'beskonačan',
'expiringblock' => 'ističe $1',
'anononlyblock' => 'samo anonimni',
'noautoblockblock' => 'Autoblokiranje je onemogućeno',
'createaccountblock' => 'blokirano pravljenje naloga',
'ipblocklistempty' => 'Spisak blokiranja je prazan.',
'blocklink' => 'blokiraj',
'unblocklink' => 'odblokiraj',
'contribslink' => 'prilozi',
'autoblocker' => 'Automatski ste blokirani jer je vašu IP adresu skoro koristio "[[{{ns:user}}:$1|$1]]". Razlog za blokiranje korisnika $1 je: "\'\'\'$2\'\'\'".',
'blocklogpage' => 'istorija blokiranja',
'blocklogentry' => 'je blokirao "[[$1]]" sa vremenom isticanja blokade od $2',
'blocklogtext' => 'Ovo je istorija blokiranja i odblokiranja korisnika. Automatski
blokirane IP adrese nisu navedene. Pogledajte [[{{ns:special}}:Ipblocklist|spisak blokiranih IP adresa]] za spisak trenutnih zabrana i blokiranja.',
'unblocklogentry' => 'odblokirao "$1"',
'range_block_disabled' => 'Administratorska mogućnost da blokira blokove IP adresa je isključena.',
'ipb_expiry_invalid' => 'Pogrešno vreme trajanja.',
'ipb_already_blocked' => '"$1" je već blokiran',
'ip_range_invalid' => 'Netačan blok IP adresa.',
'proxyblocker' => 'Bloker proksija',
'ipb_cant_unblock' => 'Greška: ID bloka $1 nije nađen. Moguće je da je već odblokiran.',
'proxyblockreason' => 'Vaša IP adresa je blokirana jer je ona otvoreni proksi. Molimo kontaktirajte vašeg Internet servis provajdera ili tehničku podršku i obavestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess' => 'Urađeno.',
'sorbs'         => 'SORBS DNSBL',
'sorbsreason' => 'Vaša IP adresa je na spisku kao otvoren proksi na [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Vaša IP adresa se nalazi na spisku kao otvoreni proksi na [http://www.sorbs.net SORBS] DNSBL. Ne možete da napravite nalog',

# Developer tools
#
'lockdb' => 'Zaključaj bazu',
'unlockdb' => 'Otključaj bazu',
'lockdbtext' => 'Zaključavanje baze će svim korisnicima ukinuti mogućnost izmene stranica,
promene korisničkih podešavanja, izmene spiska nadgledanja, i svega ostalog
što zahteva promene u bazi.
Molimo potvrdite da je ovo zaista ono što nameravate da uradite i da ćete
otključati bazu kada završite posao oko njenog održavanja.',
'unlockdbtext' => 'Otključavanje baze će svim korisnicima vratiti mogućnost izmene stranica,
promene korisničkih podešavanja, izmene spiska nadgledanja, i svega ostalog
što zahteva promene u bazi.
Molimo potvrdite da je ovo zaista ono što nameravate da uradite.',
'lockconfirm' => 'Da, zaista želim da zaključam bazu.',
'unlockconfirm' => 'Da, zaista želim da otključam bazu.',
'lockbtn' => 'Zaključaj bazu',
'unlockbtn' => 'Otključaj bazu',
'locknoconfirm' => 'Niste potvrdili svoju nameru.',
'lockdbsuccesssub' => 'Baza je zaključana',
'unlockdbsuccesssub' => 'Baza je otključana',
'lockdbsuccesstext' => 'Baza podataka je zaključana.
<br />Ne zaboravite da je [[{{ns:special}}:Unlockdb|otključate]] kada završite sa održavanjem.',
'unlockdbsuccesstext' => 'Baza podataka je otključana.',
'lockfilenotwritable' => 'Po fajlu za zaključavanje baze podataka ne može da se piše. Da biste zaključali ili otključali bazu, po ovom fajlu mora da bude omogućeno pisanje od strane veb servera.',
'databasenotlocked' => 'Baza podataka nije zaključana.',

# Make sysop
'makesysoptitle' => 'Pretvorite korisnika u administratora',
'makesysoptext' => 'Ovaj upitnik koriste birokrate da se obični korisnici pretvore u administratore. Unesite ime korisnika u polje i pritisnite dugme da bi korisnik postao administrator',
'makesysopname' => 'Ime korisnika:',
'makesysopsubmit' => 'Dodajte ovom korisniku administratorska ovlašćenja',
'makesysopok' => '<b>Korisnik "$1" je sada administrator</b>',
'makesysopfail' => '<b>Korisnik "$1" ne može da postane administrator. (Da li ste pravilno uneli ime?)</b>',
'setbureaucratflag' => 'Postavi prava birokrate',
'rightslog' => 'istorija korisničkih prava',
'rightslogtext' => 'Ovo je istorija izmena korisničkih prava.',
'rightslogentry' => 'je promenio prava za $1 sa $2 na $3',
'rights' => 'Prava:',
'set_user_rights' => 'Postavi prava korisnika',
'user_rights_set' => '<b>Prava za korisnika "$1" promenjena</b>',
'set_rights_fail' => '<b>Korisnička prava za "$1" nisu mogla da se podese. (Da li ste pravilno uneli ime?)</b>',
'makesysop' => 'Davanje administratorskih ovlašćenja korisniku',
'already_sysop' => 'Ovaj korisnik je već administrator',
'already_bureaucrat' => 'Ovaj korisnik je već birokrata',
'rightsnone' => '(nema)',

# Move page
#
'movepage' => 'Premeštanje stranice',
'movepagetext' => 'Donji upitnik će preimenovati stranicu, premeštajući svu
njenu istoriju na novo ime.
Stari naslov će postati preusmerenje na novi naslov.
Veze ka starom naslovu neće biti promenjene; obavezno
potražite [[{{ns:special}}:DoubleRedirects|dvostruka]] ili [[{{ns:special}}:BrokenRedirects|pokvarena preusmerenja]].
Na vama je odgovornost da veze i dalje idu tamo gde bi i trebalo da idu.

Obratite pažnju da stranica \'\'\'neće\'\'\' biti pomerena ako već postoji
stranica sa novim naslovom, osim ako je ona prazna ili preusmerenje i nema
istoriju promena. Ovo znači da ne možete preimenovati stranicu na ono ime
sa koga ste je preimenovali ako pogrešite, i ne možete prepisati
postojeću stranicu.

<b>PAŽNJA!</b>
Ovo može biti drastična i neočekivana promena za popularnu stranicu;
molimo da budete sigurni da razumete posledice ovoga pre nego što
nastavite.',
'movepagetalktext' => 'Odgovarajuća stranica za razgovor, ako postoji, biće automatski premeštena istovremeno \'\'\'osim ako:\'\'\'
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odbeležite donju kućicu.

U tim slučajevima, moraćete ručno da premestite ili spojite stranicu ukoliko to želite.',
'movearticle' => 'Premesti stranicu',
'movenologin' => 'Niste prijavljeni',
'movenologintext' => 'Morate biti registrovani korisnik i [[Special:Userlogin|prijavljeni]]
da biste premestili stranicu.',
'newtitle' => 'Novi naslov',
'movepagebtn' => 'premesti stranicu',
'pagemovedsub' => 'Premeštanje uspelo',
'pagemovedtext' => 'Stranica "[[$1]]" premeštena je na "[[$2]]".',
'articleexists' => 'Stranica pod tim imenom već postoji, ili je
ime koje ste izabrali neispravno.
Molimo izaberite drugo ime.',
'talkexists' => '\'\'\'Sama stranica je uspešno premeštena, ali
stranica za razgovor nije mogla biti premeštena jer takva već postoji na novom naslovu. Molimo vas da ih spojite ručno.\'\'\'',
'movedto' => 'premeštena na',
'movetalk' => 'Premesti "stranicu za razgovor" takođe, ako je moguće.',
'talkpagemoved' => 'Odgovarajuća stranica za razgovor je takođe premeštena.',
'talkpagenotmoved' => 'Odgovarajuća stranica za razgovor <strong>nije</strong> premeštena.',
'1movedto2' => 'je promenio ime članku [[$1]] u [[$2]]',
'1movedto2_redir' => 'je promenio ime članku [[$1]] u [[$2]] putem preusmerenja',
'movelogpage' => 'istorija premeštanja',
'movelogpagetext' => 'Ispod je spisak premeštanja članaka.',
'movereason' => 'Razlog',
'revertmove' => 'vrati',
'delete_and_move' => 'Obriši i premesti',
'delete_and_move_text' => '==Potrebno brisanje==

Ciljani članak "[[$1]]" već postoji. Da li želite da ga obrišete da biste napravili mesto za premeštanje?',
'delete_and_move_confirm' => 'Da, obriši stranicu',
'delete_and_move_reason' => 'Obrisano kako bi se napravilo mesto za premeštanje',
'selfmove' => 'Izvorni i ciljani naziv su isti; strana ne može da se premesti preko same sebe.',
'immobile_namespace' => 'Ciljani naziv je posebnog tipa; ne mogu da premeste strane u taj imenski prostor.',

# Export

'export' => 'Izvezi stranice',
'exporttext' => 'Možete izvoziti tekst i istoriju promena određene
stranice ili grupe stranica u XML formatu. Ovo onda može biti uvezeno u drugi
viki koji koristi MedijaViki softver preko {{ns:special}}:Import stranice.

Da biste izvozili stranice, unesite nazive u tekstualnom polju ispod, sa jednim naslovom po redu, i odaberite da li želite trenutnu verziju sa svim starim verzijama ili samo trenutnu verziju sa informacijama o poslednjoj izmeni.

U drugom slučaju, možete takođe koristiti vezu, npr. [[{{ns:special}}:Export/{{int:mainpage}}]] za stranicu {{int:mainpage}}.',
'exportcuronly' => 'Uključi samo trenutnu reviziju, ne celu istoriju',
'exportnohistory' => '----
\'\'\'Napomena:\'\'\' izvoženje pune istorije strana preko ovog formulara je onemogućeno zbog serverskih razloga.',
'export-submit' => 'Izvoz',

# Namespace 8 related

'allmessages' => 'Sistemske poruke',
'allmessagesname' => 'Ime',
'allmessagesdefault' => 'Standardni tekst',
'allmessagescurrent' => 'Trenutni tekst',
'allmessagestext' => 'Ovo je spisak svih poruka koje su u {{ns:MediaWiki}} imenskom prostoru',
'allmessagesnotsupportedUI' => 'Stranica {{ns:special}}:Allmessages ne podržava vVaš trenutni jezik interfejsa <b>$1</b> na ovoj viki.',
'allmessagesnotsupportedDB' => 'Stranica {{ns:special}}:Allmessages ne može da se koristi zato što je \'\'\'$wgUseDatabaseMessages\'\'\' isključen.',
'allmessagesfilter' => 'Filter za imena poruka:',
'allmessagesmodified' => 'Prikaži samo izmenjene',

# Thumbnails
'thumbnail-more' => 'uvećaj',
'missingimage' => '<b>Ovde nedostaje slika</b><br /><i>$1</i>',
'filemissing' => 'Nedostaje fajl',
'thumbnail_error' => 'Greška pri pravljenju umanjene slike: $1',

# Special:Import
'import' => 'Uvoz stranica',
'importinterwiki' => 'Transviki uvoženje',
'import-interwiki-text' => 'Odaberite viki i naziv strane za uvoz.
Datumi revizije i imena urednika će biti sačuvani.
Svi transviki uvozi su zabeleženi u [[Posebno:Log/import|istoriji uvoza]].',
'import-interwiki-history' => 'Kopiraj sve revizije ove strane',
'import-interwiki-submit' => 'Uvezi',
'import-interwiki-namespace' => 'Prebaci stranice u imenski prostor:',
'importtext' => 'Molimo izvezite fajl iz izvornog vikija koristeći {{ns:special}}:Export, sačuvajte ga kod sebe i pošaljite ovde.',
'importstart' => 'Uvoženje strana u toku...',
'import-revision-count' => '$1 {{plural:$1|revizija|revizije|revizija}}',
'importnopages' => 'Nema strana za uvoz.',
'importfailed' => 'Uvoz nije uspeo: $1',
'importunknownsource' => 'Nepoznati tip izvora unosa',
'importcantopen' => 'Neuspešno otvaranje fajla za uvoz',
'importbadinterwiki' => 'Loša interviki veza',
'importnotext' => 'Stranica je prazna ili bez teksta.',
'importsuccess' => 'Uspešan uvoz!',
'importhistoryconflict' => 'Postoji konfliktna istorija revizija (možda je ova stranica već uvezena ranije)',
'importnosources' => 'Nije definisan nijedan izvor transviki uvoženja i direktna slanja istorija su onemogućena.',
'importnofile' => 'Nije poslat nijedan uvozni fajl.',
'importuploaderror' => 'Slanje uvoznog fajla nije bilo uspešno; moguće je da je fajl veći od dozvoljene veličine za slanje.',

# import log
'importlogpage' => 'istorija uvoza',
'importlogpagetext' => 'Administrativni uvozi stranica sa istorijama izmena sa drugih vikija.',
'import-logentry-upload' => 'uvezao [[$1]] putem slanja fajla',
'import-logentry-upload-detail' => '$1 revizija/e',
'import-logentry-interwiki' => 'premestio sa drugog vikija: $1',
'import-logentry-interwiki-detail' => '$1 revizija/e od $2',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',
'accesskey-watch' => 'w',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Pretražite viki [alt-f]',
'tooltip-minoredit' => 'Naznačite da se radi o maloj izmeni [alt-i]',
'tooltip-save' => 'Snimite Vaše izmene [alt-s]',
'tooltip-preview' => 'Pretpregled Vaših izmena, molimo koristite ovo pre snimanja! [alt-p]',
'tooltip-diff' => 'Prikaži koje promene ste napravili na tekstu. [alt-v]',
'tooltip-compareselectedversions' => 'Pogledaj razlike između dve odabrane verzije ove stranice. [alt-v]',
'tooltip-watch' => 'Dodajte ovu stranicu na Vaš spisak nadgledanja [alt-w]',

# stylesheets
'Common.css' => '/** CSS stavljen ovde će se odnositi na sve kože */',
'Monobook.css' => '/* CSS stavljen ovde će se odnositi na korisnike Monobuk kože */',

# Metadata
'nodublincore' => 'Dublin Core RDF metapodaci onemogućeni za ovaj server.',
'nocreativecommons' => 'Creative Commons RDF metapodaci onemogućeni za ovaj server.',
'notacceptable' => 'Viki server ne može da pruži podatke u onom formatu koji vaš klijent može da pročita.',

# Attribution

'anonymous' => 'Anonimni korisnik sajta {{SITENAME}}',
'siteuser' => '{{SITENAME}} korisnik $1',
'lastmodifiedatby' => 'Ovu stranicu je poslednji put promenio $3 u $2, $1.',
'and' => 'i',
'othercontribs' => 'Bazirano na radu korisnika $1.',
'others' => 'ostali',
'siteusers' => '{{SITENAME}} korisnik (korisnici) $1',
'creditspage' => 'Zasluge za stranicu',
'nocredits' => 'Nisu dostupne informacije o zaslugama za ovu stranicu.',

# Spam protection
'spamprotectiontitle' => 'Filter za zaštitu od neželjenih poruka',
'spamprotectiontext' => 'Strana koju želite da sačuvate je blokirana od strane filtera za neželjene poruke. Ovo je verovatno izazvano vezom ka spoljašnjem sajtu.',
'spamprotectionmatch' => 'Sledeći tekst je izazvao naš filter za neželjene poruke: $1',
'subcategorycount' => 'U ovoj kategoriji se nalazi {{plural:$1|jedna potkategorija|$1 potkategorije|$1 potkategorija}}.',
'categoryarticlecount' => 'U ovoj kategoriji se nalazi {{plural:$1|Jedan članak|$1 članka|$1 članaka}}.',
'listingcontinuesabbrev' => ' nast.',
'spambot_username' => 'Čišćenje neželjenih poruka u MedijaVikiju',
'spam_reverting' => 'Vraćanje na staru reviziju koja ne sadrži veze ka $1',
'spam_blanking' => 'Sve revizije su sadržale veze ka $1, pražnjenje',

# Info page
'infosubtitle' => 'Informacije za stranicu',
'numedits' => 'Broj promena (članak): $1',
'numtalkedits' => 'Broj promena (stranica za razgovor): $1',
'numwatchers' => 'Broj korisnika koji nadgledaju: $1',
'numauthors' => 'Broj različitih autora (članak): $1',
'numtalkauthors' => 'Broj različitih autora (stranica za razgovor): $1',

# Math options
'mw_math_png' => 'Uvek prikaži PNG',
'mw_math_simple' => 'HTML ako je vrlo jednostavno, inače PNG',
'mw_math_html' => 'HTML ako je moguće, inače PNG',
'mw_math_source' => 'Ostavi kao TeH (za tekstualne brauzere)',
'mw_math_modern' => 'Preporučeno za savremene brauzere',
'mw_math_mathml' => 'MathML ako je moguće (eksperimentalno)',

# Patrolling
'markaspatrolleddiff' => 'Označi kao patroliran',
'markaspatrolledtext' => 'Označi ovaj članak kao patroliran',
'markedaspatrolled' => 'Označen kao patroliran',
'markedaspatrolledtext' => 'Izabrana revizija je označena kao patrolirana.',
'rcpatroldisabled' => 'Patrola skorašnjih izmena onemogućena',
'rcpatroldisabledtext' => 'Patrola skorašnjih izmena je trenutno onemogućena.',
'markedaspatrollederror' => 'Nemoguće označiti kao patrolirano',
'markedaspatrollederrortext' => 'Morate izabrati reviziju da biste označili kao patrolirano.',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Moja korisnička stranica\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Korisnička stranica IP adrese sa koje uređujete\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Moja stranica za razgovor\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Razgovor o prilozima sa ove IP adrese\');
ta[\'pt-preferences\'] = new Array(\'\',\'Moja korisnička podešavanja\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Spisak članaka koje nadgledate\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Spisak mojih priloga\');
ta[\'pt-login\'] = new Array(\'o\',\'Preporučuje se da se prijavite, ali nije obavezno\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Preporučuje se da se prijavite, ali nije obavezno\');
ta[\'pt-logout\'] = new Array(\'o\',\'Odjavi se\');
ta[\'ca-talk\'] = new Array(\'t\',\'Razgovor o članku\');
ta[\'ca-edit\'] = new Array(\'e\',\'Možete urediti ovu stranicu. Molimo koristite pretpregled pre sačuvavanja.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Dodajte komentar na ovu diskusiju\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Ova stranica je zaključana. Možete videti njen izvor\');
ta[\'ca-history\'] = new Array(\'h\',\'Prethodne verzije ove stranice\');
ta[\'ca-protect\'] = new Array(\'=\',\'Zaštiti ovu stranicu\');
ta[\'ca-delete\'] = new Array(\'d\',\'Obriši ovu stranicu\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Vraćati izmene koje su načinjene pre brisanja stranice\');
ta[\'ca-move\'] = new Array(\'m\',\'Premesti ovu stranicu\');
ta[\'ca-watch\'] = new Array(\'w\',\'Dodajte ovu stranicu na Vaš spisak nadgledanja\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Uklonite ovu stranicu sa Vašeg spiska nadgledanja\');
ta[\'search\'] = new Array(\'f\',\'Pretražite ovaj viki\');
ta[\'p-logo\'] = new Array(\'\',\'Glavna strana\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Posetite glavnu stranu\');
ta[\'n-portal\'] = new Array(\'\',\'O projektu, šta možete da radite i gde da pronađete stvari\');
ta[\'n-currentevents\'] = new Array(\'\',\'Saznajte više o aktuelnostima\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Spisak skorašnjih izmena na vikiju\');
ta[\'n-randompage\'] = new Array(\'x\',\'Učitavaj slučajnu stranicu\');
ta[\'n-help\'] = new Array(\'\',\'Mesto gde možete da naučite nešto\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Podržite nas\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Spisak svih stranica koje vezuju na ovu\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Skorašnje izmene na člancima povezanim sa ove stranice\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS fid za ovu stranicu\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom fid za ovu stranicu\');
ta[\'t-contributions\'] = new Array(\'\',\'Pogledaj spisak priloga ovog korisnika\');
ta[\'t-emailuser\'] = new Array(\'\',\'Pošalji elektronsku poštu ovom korisniku\');
ta[\'t-upload\'] = new Array(\'u\',\'Pošalji slike i medija fajlove\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Spisak svih posebnih stranica\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Pogledajte članak\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Pogledajte korisničku stranicu\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Pogledajte medija stranicu\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ovo je posebna stranica, ne možete je menjati\');
ta[\'ca-nstab-wp\'] = new Array(\'c\',\'Pogledajte stranicu o projektu\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Pogledajte stranicu slike\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Pogledajte sistemsku poruku\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Pogledajte šablon\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Pogledajte stranicu za pomoć\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Pogledajte stranicu kategorije\');',

# image deletion
'deletedrevision' => 'Obrisana stara revizija $1.',

# browsing diffs
'previousdiff' => '← Prethodna izmena',
'nextdiff' => 'Sledeća izmena →',

'imagemaxsize' => 'Ograniči slike na stranama za razgovor o slikama na:',
'thumbsize' => 'Veličina umanjenog prikaza :',
'showbigimage' => 'Prikaži sliku veće rezolucije ($1x$2, $3 Kb)',

'newimages' => 'Galerija novih slika',
'showhidebots' => '($1 botove)',
'noimages' => 'Nema ništa da se vidi',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',
# variants for Serbian language
'variantname-sr-ec' => 'ћирилица',
'variantname-sr-el' => 'latinica',
'variantname-sr-jc' => 'jekav',
'variantname-sr-jl' => 'jekav',
'variantname-sr' => 'disable',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Korisnik:',
'speciallogtitlelabel' => 'Naslov:',

'passwordtooshort' => 'Vaša šifra je previše kratka. Mora da ima bar $1 karaktera.',

# Media Warning
'mediawarning' => '\'\'\'Upozorenje\'\'\': Ovaj fajl sadrži loš kod, njegovim izvršavanjem možete da ugrozite vaš sistem.<hr />',

'fileinfo' => '$1KB, MIME tip: <code>$2</code>',

# Metadata
'metadata' => 'Metapodaci',
'metadata-help' => 'Ovaj fajl sadrži dodatne informacije, koje su verovatno dodali digitalni fotoaparat ili skener koji su korišćeni da bi se napravila ili digitalizovala slika. Ako je prvobitno stanje fajla promenjeno, moguće je da neki detalji ne opisuju u potpunosti izmenjenu sliku.',
'metadata-expand' => 'Pokaži detalje',
'metadata-collapse' => 'Sakrij detalje',
'metadata-fields' => 'Polja EXIF metapodataka navedena u ovoj poruci će biti ubačena na stranu o slici kada se raširi tabela za metapodatke. Ostala će biti sakrivena po podrazumevanom.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# Exif tags
'exif-imagewidth' => 'Širina',
'exif-imagelength' => 'Visina',
'exif-bitspersample' => 'Bitova po komponenti',
'exif-compression' => 'Šema kompresije',
'exif-photometricinterpretation' => 'Kompozicija piksela',
'exif-orientation' => 'Orijentacija',
'exif-samplesperpixel' => 'Broj komponenti',
'exif-planarconfiguration' => 'Princip rasporeda podataka',
'exif-ycbcrsubsampling' => 'Odnos komponente Y prema C',
'exif-ycbcrpositioning' => 'Razmeštaj komponenata Y i C',
'exif-xresolution' => 'Horizonatalna rezolucija',
'exif-yresolution' => 'Vertikalna rezolucija',
'exif-resolutionunit' => 'Jedinica rezolucije',
'exif-stripoffsets' => 'Položaj bloka podataka',
'exif-rowsperstrip' => 'Broj redova u bloku',
'exif-stripbytecounts' => 'Veličina kompresovanog bloka',
'exif-jpeginterchangeformat' => 'Udaljenost JPEG pregleda od početka fajla',
'exif-jpeginterchangeformatlength' => 'Količina bajtova JPEG pregleda',
'exif-transferfunction' => 'Funkcija preoblikovanja kolor prostora',
'exif-whitepoint' => 'Hromacitet bele tačke',
'exif-primarychromaticities' => 'Hromacitet primarnih boja',
'exif-ycbcrcoefficients' => 'Matrični koeficijenti transformacije kolor prostora',
'exif-referenceblackwhite' => 'Mesto bele i crne tačke',
'exif-datetime' => 'Datum poslednje promene fajla',
'exif-imagedescription' => 'Ime slike',
'exif-make' => 'Proizvođač kamere',
'exif-model' => 'Model kamere',
'exif-software' => 'Korišćen softver',
'exif-artist' => 'Autor',
'exif-copyright' => 'Nosilac prava',
'exif-exifversion' => 'Exif verzija',
'exif-flashpixversion' => 'Podržana verzija Flešpiksa',
'exif-colorspace' => 'Prostor boje',
'exif-componentsconfiguration' => 'Značenje svake od komponenti',
'exif-compressedbitsperpixel' => 'Mod kompresije slike',
'exif-pixelydimension' => 'Puna visina slike',
'exif-pixelxdimension' => 'Puna širina slike',
'exif-makernote' => 'Napomene proizvođača',
'exif-usercomment' => 'Korisnički komentar',
'exif-relatedsoundfile' => 'Povezani zvučni zapis',
'exif-datetimeoriginal' => 'Datum i vreme slikanja',
'exif-datetimedigitized' => 'Datum i vreme digitalizacije',
'exif-subsectime' => 'Deo sekunde u kojem je slikano',
'exif-subsectimeoriginal' => 'Deo sekunde u kojem je fotografisano',
'exif-subsectimedigitized' => 'Deo sekunde u kojem je digitalizovano',
'exif-exposuretime' => 'Ekspozicija',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-fnumber' => 'F broj otvora blende',
'exif-fnumber-format' =>'f/$1',
'exif-exposureprogram' => 'Program ekspozicije',
'exif-spectralsensitivity' => 'Spektralna osetljivost',
'exif-isospeedratings' => 'ISO vrednost',
'exif-oecf' => 'Optoelektronski faktor konverzije',
'exif-shutterspeedvalue' => 'Brzina zatvarača',
'exif-aperturevalue' => 'Otvor blende',
'exif-brightnessvalue' => 'Svetlost',
'exif-exposurebiasvalue' => 'Kompenzacija ekspozicije',
'exif-maxaperturevalue' => 'Minimalni broj otvora blende',
'exif-subjectdistance' => 'Udaljenost do objekta',
'exif-meteringmode' => 'Režim merača vremena',
'exif-lightsource' => 'Izvor svetlosti',
'exif-flash' => 'Blic',
'exif-focallength' => 'Fokusna daljina sočiva',
'exif-focallength-format' =>'$1 mm',
'exif-subjectarea' => 'Položaj i površina objekta snimka',
'exif-flashenergy' => 'Energija blica',
'exif-spatialfrequencyresponse' => 'Prostorna frekvencijska karakteristika',
'exif-focalplanexresolution' => 'Vodoravna rezolucija fokusne ravni',
'exif-focalplaneyresolution' => 'Horizonatlna rezolucija fokusne ravni',
'exif-focalplaneresolutionunit' => 'Jedinica rezolucije fokusne ravni',
'exif-subjectlocation' => 'Položaj subjekta',
'exif-exposureindex' => 'Indeks ekspozicije',
'exif-sensingmethod' => 'Tip senzora',
'exif-filesource' => 'Izvorni fajl',
'exif-scenetype' => 'Tip scene',
'exif-cfapattern' => 'CFA šablon',
'exif-customrendered' => 'Dodatna obrada slike',
'exif-exposuremode' => 'Režim izbora ekspozicije',
'exif-whitebalance' => 'Balans bele boje',
'exif-digitalzoomratio' => 'Odnos digitalnog zuma',
'exif-focallengthin35mmfilm' => 'Ekvivalent fokusne daljine za 35 mm film',
'exif-scenecapturetype' => 'Tip scene na snimku',
'exif-gaincontrol' => 'Kontrola osvetljenosti',
'exif-contrast' => 'Kontrast',
'exif-saturation' => 'Saturacija',
'exif-sharpness' => 'Oštrina',
'exif-devicesettingdescription' => 'Opis podešavanja uređaja',
'exif-subjectdistancerange' => 'Raspon udaljenosti subjekata',
'exif-imageuniqueid' => 'Jedinstveni identifikator slike',
'exif-gpsversionid' => 'Verzija bloka GPS-informacije',
'exif-gpslatituderef' => 'Severna ili južna širina',
'exif-gpslatitude' => 'Širina',
'exif-gpslongituderef' => 'Istočna ili zapadna dužina',
'exif-gpslongitude' => 'Dužina',
'exif-gpsaltituderef' => 'Visina ispod ili iznad mora',
'exif-gpsaltitude' => 'Visina',
'exif-gpstimestamp' => 'Vreme po GPS-u (atomski sat)',
'exif-gpssatellites' => 'Upotrebljeni sateliti',
'exif-gpsstatus' => 'Status prijemnika',
'exif-gpsmeasuremode' => 'Režim merenja',
'exif-gpsdop' => 'Preciznost merenja',
'exif-gpsspeedref' => 'Jedinica brzine',
'exif-gpsspeed' => 'Brzina GPS prijemnika',
'exif-gpstrackref' => 'Tip azimuta prijemnika (pravi ili magnetni)',
'exif-gpstrack' => 'Azimut prijemnika',
'exif-gpsimgdirectionref' => 'Tip azimuta slike (pravi ili magnetni)',
'exif-gpsimgdirection' => 'Azimut slike',
'exif-gpsmapdatum' => 'Korišćeni geodetski koordinatni sistem',
'exif-gpsdestlatituderef' => 'Indeks geografske širine objekta',
'exif-gpsdestlatitude' => 'Geografska širina objekta',
'exif-gpsdestlongituderef' => 'Indeks geografske dužine objekta',
'exif-gpsdestlongitude' => 'Geografska dužina objekta',
'exif-gpsdestbearingref' => 'Indeks azimuta objekta',
'exif-gpsdestbearing' => 'Azimut objekta',
'exif-gpsdestdistanceref' => 'Merne jedinice udaljenosti objekta',
'exif-gpsdestdistance' => 'Udaljenost objekta',
'exif-gpsprocessingmethod' => 'Ime metode obrade GPS podataka',
'exif-gpsareainformation' => 'Ime GPS područja',
'exif-gpsdatestamp' => 'GPS datum',
'exif-gpsdifferential' => 'GPS diferencijalna korekcija',

# Exif attributes

'exif-compression-1' => 'Nekompresovan',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normalno',
'exif-orientation-2' => 'Obrnuto po horizontali',
'exif-orientation-3' => 'Zaokrenuto 180°',
'exif-orientation-4' => 'Obrnuto po vertikali',
'exif-orientation-5' => 'Zaokrenuto 90° suprotno od smera kazaljke na satu i obrnuto po vertikali',
'exif-orientation-6' => 'Zaokrenuto 90° u smeru kazaljke na satu',
'exif-orientation-7' => 'Zaokrenuto 90° u smeru kazaljke na satu i obrnuto po vertikali',
'exif-orientation-8' => 'Zaokrenuto 90° suprotno od smera kazaljke na satu',

'exif-planarconfiguration-1' => 'delimični format',
'exif-planarconfiguration-2' => 'planarni format',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'ne postoji',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

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

'exif-meteringmode-0' => 'Nepoznato',
'exif-meteringmode-1' => 'Prosek',
'exif-meteringmode-2' => 'Prosek sa težištem na sredini',
'exif-meteringmode-3' => 'Tačka',
'exif-meteringmode-4' => 'Više tačaka',
'exif-meteringmode-5' => 'Matrični',
'exif-meteringmode-6' => 'Delimični',
'exif-meteringmode-255' => 'Drugo',

'exif-lightsource-0' => 'Nepoznato',
'exif-lightsource-1' => 'Dnevna svetlost',
'exif-lightsource-2' => 'Fluorescentno',
'exif-lightsource-3' => 'Volfram (svetlo)',
'exif-lightsource-4' => 'Blic',
'exif-lightsource-9' => 'Lepo vreme',
'exif-lightsource-10' => 'Oblačno vreme',
'exif-lightsource-11' => 'Senka',
'exif-lightsource-12' => 'Fluorescentna svetlost (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluorescentna svetlost (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluorescentna svetlost (W 3900 – 4500K)',
'exif-lightsource-15' => 'Bela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardno svetlo A',
'exif-lightsource-18' => 'Standardno svetlo B',
'exif-lightsource-19' => 'Standardno svetlo C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studijski volfram',
'exif-lightsource-255' => 'Drugi izvor svetla',

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

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Sever',
'exif-gpslatitude-s' => 'Jug',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Istok',
'exif-gpslongitude-w' => 'Zapad',

'exif-gpsstatus-a' => 'Merenje u toku',
'exif-gpsstatus-v' => 'Spreman za prenos',

'exif-gpsmeasuremode-2' => 'Dvodimenzionalno merenje',
'exif-gpsmeasuremode-3' => 'Trodimenzionalno merenje',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometri na čas',
'exif-gpsspeed-m' => 'Milje na čas',
'exif-gpsspeed-n' => 'Čvorovi',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravi pravac',
'exif-gpsdirection-m' => 'Magnetni pravac',

# external editor support
'edit-externally' => 'Izmenite ovaj fajl koristeći spoljašnju aplikaciju',
'edit-externally-help' => 'Pogledajte [http://meta.wikimedia.org/wiki/Help:External_editors uputstvo za podešavanje] za više informacija.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sve',
'imagelistall' => 'sve',
'watchlistall1' => 'sve',
'watchlistall2' => 'sve',
'namespacesall' => 'svi',

# E-mail address confirmation
'confirmemail' => 'Potvrdite adresu e-pošte',
'confirmemail_noemail' => 'Nemate potvrđenu adresu vaše e-pošte u vašim [[Special:Preferences|korisničkim podešavanjima interfejsa]].',
'confirmemail_text' => 'Ova viki zahteva da potvrdite adresu vaše e-pošte pre nego što koristite mogućnosti e-pošte. Aktivirajte dugme ispod kako biste poslali poštu za potvrdu na vašu adresu. Pošta uključuje vezu koja sadrži kod; učitajte tu vezu u vaš brauzer da biste potvrdili da je adresa vaše e-pošte validna.',
'confirmemail_send' => 'Pošalji kod za potvrdu',
'confirmemail_sent' => 'E-pošta za potvrđivanje poslata.',
'confirmemail_sendfailed' => 'Pošta za potvrđivanje nije poslata. Proverita adresu zbog nepravilnih karaktera.',
'confirmemail_invalid' => 'Netačan kod za potvrdu. Moguće je da je kod istekao.',
'confirmemail_needlogin' => 'Morate da se $1 da biste potvrdili adresu vaše e-pošte.',
'confirmemail_success' => 'Adresa vaše e-pošte je potvrđena. Možete sada da se prijavite i uživate u vikiju.',
'confirmemail_loggedin' => 'Adresa vaše e-pošte je sada potvrđena.',
'confirmemail_error' => 'Nešto je pošlo po zlu prilikom snimanja vaše potvrde.',

'confirmemail_subject' => '{{SITENAME}} adresa e-pošte za potvrđivanje',
'confirmemail_body' => 'Neko, verovatno vi, je sa IP adrese $1 registrovao nalog "$2" sa ovom adresom e-pošte na sajtu {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada vama i da aktivirate mogućnost e-pošte na sajtu {{SITENAME}}, otvorite ovu vezu u vašem brauzeru:

$3

Ako ovo *niste* vi, ne pratite vezu. Ovaj kod za potvrdu će isteći u $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Pokušaj tačno',
'searchfulltext' => 'Pretraži ceo tekst',
'createarticle' => 'Napravi članak',

# Scary transclusion
'scarytranscludedisabled' => '[Interviki uključivanje je onemogućeno]',
'scarytranscludefailed' => '[Donošenje šablona neuspešno; žao nam je]',
'scarytranscludetoolong' => '[URL je predugačak; žao nam je]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Vraćanja za ovaj članak:<br />
$1
</div>',
'trackbackremove' => '([$1 Brisanje])',
'trackbacklink' => 'Vraćanje',
'trackbackdeleteok' => 'Vraćanje je uspešno obrisano.',

# delete conflict
'deletedwhileediting' => 'Upozorenje: Ova strana je obrisana pošto ste počeli uređivanje!',
'confirmrecreate' => 'Korisnik [[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|razgovor]]) je obrisao ovaj članak pošto ste počeli uređivanje sa razlogom:
: \'\'$2\'\'
Molimo potvrdite da stvarno želite da ponovo napravite ovaj članak.',
'recreate' => 'Ponovo napravi',
'tooltip-recreate' => 'Ponovo napravite ovu stranu uprkos tome što je obrisana',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Preusmeravam na [[$1]]...',

# action=purge
'confirm_purge' => 'Da li želite očistiti keš ove stranice?

$1',
'confirm_purge_button' => 'Da',

'youhavenewmessagesmulti' => 'Imate novih poruka na $1',
'searchcontaining' => 'Pretraži članke koji sadrže \'\'$1\'\'.',
'searchnamed' => 'Pretraži članke koji se zovu \'\'$1\'\'.',
'articletitles' => 'Članci počevši od \'\'$1\'\'',
'hideresults' => 'Sakrij rezultate',

# DISPLAYTITLE
'displaytitle' => '(Veza ka ovoj strani kao [[$1]])',

'loginlanguagelabel' => 'Jezik: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; prethodna stranica',
'imgmultipagenext' => 'sledeća stranica &rarr;',
'imgmultigo' => 'Idi!',
'imgmultigotopre' => 'Idi na stranicu',

# Table pager
'ascending_abbrev' => 'rast',
'descending_abbrev' => 'opad',
'table_pager_next' => 'Sledeća stranica',
'table_pager_prev' => 'Prethodna stranica',
'table_pager_first' => 'Prva stranica',
'table_pager_last' => 'Poslednja stranica',
'table_pager_limit' => 'Prikaži $1 delova informacije po stranici',
'table_pager_limit_submit' => 'Idi',
'table_pager_empty' => 'Bez rezultata',

);

?>
