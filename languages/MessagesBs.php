<?php

$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Posebno',
	NS_MAIN             => '',
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_sa_korisnikom',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Razgovor_{{grammar:instrumental|$1}}',
	NS_IMAGE            => 'Slika',
	NS_IMAGE_TALK       => 'Razgovor_o_slici',
	NS_MEDIAWIKI        => 'MedijaViki',
	NS_MEDIAWIKI_TALK   => 'Razgovor_o_MedijaVikiju',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

$quickbarSettings = array(
	'Nikakva', 'Pričvršćena lijevo', 'Pričvršćena desno', 'Plutajuća lijevo'
);

$skinNames = array(
	'Obična', 'Nostalgija', 'Kelnsko plavo', 'Pedington', 'Monparnas'
);

$magicWords = array(
	# ID                              CASE SYNONYMS
	'redirect'               => array( 0, '#Preusmjeri', '#redirect', '#preusmjeri', '#PREUSMJERI' ),
	'notoc'                  => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	'toc'                    => array( 0, '__TOC__', '__SADRŽAJ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
	'start'                  => array( 0, '__START__', '__POČETAK__' ),
	'end'                    => array( 0, '__END__', '__KRAJ__' ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'TRENUTNIMJESEC' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMJESECIME' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMJESECROD' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMJESECSKR' ),
	'currentday'             => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'TRENUTNOVRIJEME' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
	'pagename'               => array( 1, 'PAGENAME', 'STRANICA' ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'STRANICE' ),
	'namespace'              => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
	'namespacee'             => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
	'msg'                    => array( 0, 'MSG:', 'POR:' ),
	'subst'                  => array( 0, 'SUBST:', 'ZAMJENI:' ),
	'msgnw'                  => array( 0, 'MSGNW:', 'NVPOR:' ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'mini' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
	'img_right'              => array( 1, 'right', 'desno', 'd' ),
	'img_left'               => array( 1, 'left', 'lijevo', 'l' ),
	'img_none'               => array( 1, 'none', 'n', 'bez' ),
	'img_width'              => array( 1, '$1px', '$1piksel' , '$1p' ),
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
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__BEZTC__' ),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'TRENUTNASEDMICA' ),
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

$fallback8bitEncoding = "iso-8859-2";
$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zćčžšđž]+)(.*)$/sDu';

$messages = array(
'1movedto2' => 'stranica [[$1]] premještena u stranicu [[$2]]',
'1movedto2_redir' => 'stranica [[$1]] premještena u stranicu [[$2]] putem preusmjerenja',
'Monobook.css' => '/*
*/',
'Monobook.js' => '
/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Moja korisnička stranica\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Korisnička stranica za ip koju Vi uređujete kao\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Moja stranica za razgovor\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Razgovor o doprinosu sa ove IP adrese\');
ta[\'pt-preferences\'] = new Array(\'\',\'Moja podešavanja\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Spisak članaka koje pratite.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Spisak mog doprinosa\');
ta[\'pt-login\'] = new Array(\'o\',\'Prijava nije obavezna, ali donosi mnogo koristi.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Prijava nije obavezna, ali donosi mnogo koristi.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Odjava sa projekta {{SITENAME}}\');
ta[\'ca-talk\'] = new Array(\'t\',\'Razgovor o sadržaju\');
ta[\'ca-edit\'] = new Array(\'e\',\'Možete da uređujete ovaj članak. Molimo Vas, koristite dugme "Prikaži izgled" prije konačnog sačuvavanja vaših imjena.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Dodajte svoj komentar.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Ovaj članak je zaključan. Možete ga samo vidjeti ili kopirati kod.\');
ta[\'ca-history\'] = new Array(\'h\',\'Prethodne verzije ove stranice.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Zaštitite stranicu od budućih izmjena\');
ta[\'ca-delete\'] = new Array(\'d\',\'Izbrišite ovu stranicu\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Vratite izmjene koje su načinjene prije brisanja stranice\');
ta[\'ca-move\'] = new Array(\'m\',\'Pomjerite stranicu\');
ta[\'ca-nomove\'] = new Array(\'\',\'Nemate dozvolu za pomjeranje ove stranice\');
ta[\'ca-watch\'] = new Array(\'w\',\'Dodajte stranicu u listu praćnih članaka\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Izbrišite stranicu sa liste praćnih članaka\');
ta[\'search\'] = new Array(\'f\',\'Pretražite projekat {{SITENAME}}\');
ta[\'p-logo\'] = new Array(\'\',\'Glavna stranica\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Posjetite glavnu stranicu\');
ta[\'n-portal\'] = new Array(\'\',\'O projektu, kako Vi možete pomoći, i gdje da nađete potrebne stvari o projektu {{SITENAME}}\');
ta[\'n-currentevents\'] = new Array(\'\',\'Podaci o onome na čemu se trenutno radi\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Spisak nedavnih izmjena na projektu {{SITENAME}}.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Otvorite slučajan članak\');
ta[\'n-help\'] = new Array(\'\',\'Naučite da uređujete projekat {{SITENAME}}.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Podržite nas\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Spisak svih članaka koji su povezani sa ovim\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Nedavne izmjene na stranicama koje su povezane sa ovom\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS za ovu stranicu\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom za ovu stranicu\');
ta[\'t-contributions\'] = new Array(\'\',\'Pogledajte spisak doprinosa ovog korisnika\');
ta[\'t-emailuser\'] = new Array(\'\',\'Pošaljite pismo ovom korisniku\');
ta[\'t-upload\'] = new Array(\'u\',\'Pošaljite slike i medija fajlove\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Spisak svih posebih stranica\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Pogledajte sadržaj članka\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Pogledajte korisničku stranicu\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Pogledajte medija fajl\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ovo je specijalna stranica i zato je ne možete uređivati\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Pogledajte projekat stranicu\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Pogledajte stranicu slike\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Pogledajte sistemsku poruku\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Pogledajte šablon\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Pogledajte stranicu za pomoć\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Pogledajte stranicu kategorije\');',
'about' => 'O...',
'aboutpage' => '{{ns:4}}:O',
'aboutsite' => 'O projektu {{SITENAME}}',
'accmailtext' => 'Lozinka za nalog \'$1\' je poslata na adresu $2.',
'accmailtitle' => 'Lozinka poslata.',
'actioncomplete' => 'Akcija završena',
'addedwatch' => 'Dodato u spisak praćenih članaka',
'addedwatchtext' => 'Stranica "[[:$1]]" je dodata vašem [[{{ns:-1}}:Watchlist|spisku praćenih članaka]]. Buduće promjene ove stranice i njoj pridružene stranice za razgovor će biti navedene ovde, i stranica će biti <b>podebljana</b> u [[{{ns:-1}}:Recentchanges|spisku]] nedavnih izmjena da bi se lakše uočila.</p>
<p>Ako kasnije želite da uklonite stranicu sa vašeg spiska praćenih članaka, kliknite na "prekini praćenje" na paleti.',
'allmessages' => 'Sistemske poruke',
'allmessagesnotsupportedDB' => '[[{{ns:-1}}:Allmessages|sistemske poruke]] nisu podržane zato što je <i>wgUseDatabaseMessages</i> isključen.',
'allmessagesnotsupportedUI' => 'Vaš trenutni jezik interfejsa <b>$1</b> nije podržan u [[{{ns:-1}}:Allmessages|sistemskim porukama]] na ovoj viki.',
'allmessagestext' => 'Ovo je spisak svih sistemskih poruka u {{ns:8}} imenskom prostoru.',
'allpages' => 'Sve stranice',
'alphaindexline' => '$1 u $2',
'alreadyloggedin' => '<strong>Korisniče $1, već ste prijavljeni!</strong><br />',
'alreadyrolled' => 'Ne može se vratiti poslednja izmjena [[$1]] od korisnika [[{{ns:2}}:$2|$2]] ([[{{ns:3}}:$2|razgovor]]); neko drugi je već izmjenio ili vratio članak.  Poslednja izmjena od korisnika [[{{ns:2}}:$3|$3]] ([[{{ns:3}}:$3|razgovor]]).',
'ancientpages' => 'Najstarije stranice',
'and' => 'i',
'anoneditwarning' => 'Niste prijavljeni. Vaša IP adresa će biti zapisana.',
'anontalk' => 'Razgovor za ovu IP adresu',
'anontalkpagetext' => '----\'\'Ovo je stranica za razgovor za anonimnog korisnika koji još nije napravio nalog ili ga ne koristi.  Zbog toga moramo da koristimo brojčanu [[IP adresa|IP adresu]] kako bismo odentifikovali njega ili nju.  Takvu adresu može dijeliti više korisnika.  Ako ste anonimni korisnik i mislite da su vam upućene nebitne primjedbe, molimo Vas da [[{{ns:-1}}:Userlogin|napravite nalog ili se prijavite]] da biste izbjegli buduću zabunu sa ostalim anonimnim korisnicima.\'\'',
'anonymous' => 'Anonimni korisnik od {{SITENAME}}',
'apr' => 'apr',
'april' => 'april',
'article' => 'Članak',
'articleexists' => 'Stranica pod tim imenom već postoji, ili je ime koje ste izabrali neispravno.  Molimo Vas da izaberete drugo ime.',
'articlepage' => 'Pogledaj članak',
'aug' => 'avg',
'august' => 'avgust',
'autoblocker' => 'Automatski ste blokirani jer dijelite IP adresu sa "$1".  Razlog za blokiranje je: "\'\'\'$2\'\'\'"',
'badarticleerror' => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'badfilename' => 'Ime slike je promjenjeno u "$1".',
'badfiletype' => '".$1" nije preporučeni format slike.',
'badipaddress' => 'Pogrešna IP adresa',
'badquery' => 'Loše oblikovan upit za pretragu',
'badquerytext' => 'Nismo mogli da obradimo vaš upit.  Ovo je vjerovatno zbog toga što ste pokušali da tražite riječ kraću od tri slova, što trenutno nije podržano.  Takođe je moguće da ste pogrešno ukucali izraz, na primjer "riba ii krljušti".  Molimo vas da pokušate nekim drugim upitom.',
'badretype' => 'Lozinke koje ste unijeli se ne poklapaju.',
'badtitle' => 'Loš naslov',
'badtitletext' => 'Zahtjevani naslov stranice je bio neispravan, prazan ili neispravno povezan međujezički ili interviki naslov.',
'blanknamespace' => '(Glavno)',
'blockedtext' => 'Vaše korisničko ime ili IP adresa je blokirana od strane $1.
Dati razlog je sledeći:<br />\'\'$2\'\'<p>Možete kontaktirati $1 ili nekog drugog [[{{ns:4}}:Administratori|administratora]] da biste razgovarili o blokadi.',
'blockedtitle' => 'Korisnik je blokiran',
'blockip' => 'Blokiraj korisnika',
'blockipsuccesssub' => 'Blokiranje je uspjelo',
'blockipsuccesstext' => '[[{{ns:-1}}:Contributions/$1|$1]] je blokiran.
<br />Pogledajte [[{{ns:-1}}:Ipblocklist|IP spisak blokiranih korisnika]] za pregled blokiranja.',
'blockiptext' => 'Upotrebite donji upitnik da biste uklonili prava pisanja sa određene IP adrese ili korisničkog imena.  Ovo bi trebalo da bude urađeno samo da bi se spriječio vandalizam, i u skladu sa [[{{ns:4}}:Smjernice|smjernicama]].  Unesite konkretan razlog ispod (na primjer, navodeći koje stranice su vandalizovane).',
'blocklink' => 'blokirajte',
'blocklistline' => '$1, $2 blokirao korisnika $3 ($4)',
'blocklogentry' => 'je blokirao "$1" sa vremenom isticanja blokade od $2',
'blocklogtext' => 'Ovo je istorija blokiranja i deblokiranja korisnika.  Automatsko blokirane IP adrese nisu uspisane ovde.  Pogledajte [[{{ns:-1}}:Ipblocklist|blokirane IP adrese]] za spisak trenutnih zabrana i blokiranja.',
'bold_sample' => 'Podebljan tekst',
'bold_tip' => 'Podebljan tekst',
'booksources' => 'Štampani izvori',
'booksourcetext' => 'Ispod je spisak veza na druge sajtove koji
prodaju nove i korišćene knjige, i takođe mogu imati daljnje informacije
o knjigama koje tražite.
{{SITENAME}} ne sarađuje ni se jednim od ovih preduzeća, i
ovaj spisak ne treba da se shvati kao potvrda njihovog kvaliteta.',
'brokenredirects' => 'Pokvarena preusmjerenja',
'brokenredirectstext' => 'Sledeća preusmjerenja su povezana na nepostojeći članak:',
'bugreports' => 'Prijavite grešku',
'bugreportspage' => '{{ns:4}}:Prijave_grešaka',
'bydate' => 'po datumu',
'byname' => 'po imenu',
'bysize' => 'po veličini',
'cachederror' => 'Ovo je keširana kopija zahtjevane stranice, i možda nije najnovija.',
'cancel' => 'Poništite',
'cannotdelete' => 'Ne može se obrisati navedena stranica ili slika.  (Moguće je da ju je neko drugi već obrisao.)',
'cantrollback' => 'Ne može se vratiti izmjena; poslednji autor je ujedno i jedini.',
'categories' => 'Kategorije',
'categoriespagetext' => 'Sledeće kategorije već postoje u {{SITENAME}}',
'category_header' => 'Članaka u kategoriji "$1"',
'changepassword' => 'Promjeni lozinku',
'changes' => 'izmjene',
'columns' => 'Kolona',
'compareselectedversions' => 'Uporedite označene verzije',
'confirm' => 'Potvrdite',
'confirmdelete' => 'Potvrdi brisanje',
'confirmdeletetext' => 'Na putu ste da trajno obrišete stranicu
ili sliku zajedno sa svom njenom istorijom iz baze.
Molimo Vas da potvrdite da namjeravate da uradite ovo, da razumijete
poslijedice, i da ovo radite u skladu sa
[[{{ns:4}}:Pravila|pravilima]] {{SITENAME}}.',
'confirmemail' => 'Potvrdite adresu e-pošte',
'confirmemail_body' => 'Neko, vjerovatno Vi, je sa IP adrese $1 registrovao nalog "$2" sa ovom adresom e-pošte na {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada vama i da aktivirate mogućnost e-pošte na {{SITENAME}}, otvorite ovu poveznicu u vašem brauzeru:

$3

Ako ovo niste vi, ne pratite poveznicu. Ovaj kod za potvrdu će isteći u $4.',
'confirmemail_error' => 'Nešto je pošlo po zlu prilikom sačuvavanja vaše potvrde.',
'confirmemail_invalid' => 'Netačan kod za potvrdu. Moguće je da je kod istekao.',
'confirmemail_loggedin' => 'Adresa Vaše e-pošte je potvrđena.',
'confirmemail_send' => 'Pošaljite kod za potvrdu',
'confirmemail_sendfailed' => 'Pošta za potvrđivanje nije poslata. Provjerite adresu zbog nepravilnih karaktera.',
'confirmemail_sent' => 'E-pošta za potvrđivanje poslata.',
'confirmemail_subject' => '{{SITENAME}} adresa e-pošte za potvrđivanje',
'confirmemail_success' => 'Adresa vaše e-pošte je potvrđena. Možete sad da se prijavite i uživate u viki.',
'confirmemail_text' => 'Ova viki zahtjeva da potvrdite adresu Vaše e-pošte prije nego što koristite mogućnosti e-pošte. Aktivirajte dugme ispod kako bi ste poslali poštu za potvrdu na Vašu adresu. Pošta uključuje poveznicu koja sadrži kod; učitajte poveznicu u Vaš brauzer da bi ste potvrdili da je adresa Vaše e-pošte validna.',
'confirmprotect' => 'Potvrdite zaštitu',
'confirmprotecttext' => 'Da li zaista želite da zaštitite ovu stranicu?',
'confirmrecreate' => 'Korisnik [[{{ns:2}}:$1|$1]] ([[{{ns:3}}:$1|razgovor]]) je obrisao ovaj članak pošto ste počeli uređivanje sa razlogom:
: \'\'$2\'\'

Molimo Vas da potvrdite da stvarno želite da ponovo napravite ovaj članak.',
'confirmunprotect' => 'Potvrdite skidanje zaštite',
'confirmunprotecttext' => 'Da li zaista želite da skinete zaštitu sa ove stranice?',
'contextchars' => 'Karaktera konteksta po liniji:',
'contextlines' => 'Linija po pogotku:',
'contribslink' => 'doprinos',
'contribsub' => 'Za $1',
'contributions' => 'Doprinos korisnika',
'copyright' => 'Svi sadržaji podliježu "$1" licenci.',
'copyrightpage' => '{{ns:4}}:Autorska_prava',
'copyrightpagename' => '{{SITENAME}} autorska prava',
'copyrightwarning' => 'Za sve priloge poslate na projekat {{SITENAME}} smatramo da su objavljeni pod $2 (konsultujte $1 za detalje).
Ukoliko ne želite da vaši članci budu podložni izmjenama i slobodnom rasturanju i objavljivanju, 
nemojte ih slati ovdje. Takođe, slanje članka podrazumijeva i vašu izjavu da ste ga napisali sami, ili da ste ga kopirali iz izvora u javnom domenu ili sličnog slobodnog izvora.

<strong>NEMOJTE SLATI RAD ZAŠTIĆEN AUTORSKIM PRAVIMA BEZ DOZVOLE AUTORA!</strong>',
'couldntremove' => 'Ne može se ukloniti \'$1\'...',
'createaccount' => 'Napravi nalog',
'createaccountmail' => 'e-poštom',
'cur' => 'tren',
'currentevents' => 'Trenutni događaji',
'currentrev' => 'Trenutna revizija',
'databaseerror' => 'Greška u bazi',
'dateformat' => 'Format datuma',
'datedefault' => 'Nije bitno',
'dberrortext' => 'Desila se sintaksna greška upita baze.
Ovo je moguće zbog ilegalnog upita, ili moguće greške u softveru.
Poslednji pokušani upit je bio: <blockquote><tt>$1</tt></blockquote>
iz funkcije "<tt>$2</tt>".
MySQL je vratio grešku "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Desila se sintaksna greška upita baze.
Poslednji pokušani upit je bio:
"$1"
iz funkcije "$2".
MySQL je vratio grešku "$3: $4".',
'deadendpages' => 'Stranice bez internih veza',
'dec' => 'dec',
'december' => 'decembar',
'defaultns' => 'Uobičajeno tražite u ovim imenskim prostorima:',
'defemailsubject' => '{{SITENAME}} e-pošta',
'delete' => 'Obrišite',
'deletecomment' => 'Razlog za brisanje',
'deletedarticle' => 'obrisan "[[$1]]"',
'deletedtext' => 'Članak "$1" je obrisan.
Pogledajte $2 za zapis o skorašnjim brisanjima.',
'deleteimg' => 'obr',
'deletepage' => 'Obrišite stranicu',
'deletesub' => '(Briše se "$1")',
'deletethispage' => 'Obriši ovu stranicu',
'deletionlog' => 'istorija brisanja',
'dellogpage' => 'istorija brisanja',
'dellogpagetext' => 'Ispod je spisak najskorijih brisanja.',
'diff' => 'razl',
'difference' => '(Razlika između revizija)',
'disambiguations' => 'Stranice za višeznačne odrednice',
'disambiguationspage' => '{{ns:10}}:Višeznačna odrednica',
'disambiguationstext' => 'Sledeći članci se povezuju sa <i>višeznačnom odrednicom</i>.  Umjesto toga, oni bi trebali da se povezuju sa odgovarajućom temom.<br />Stranica se tretira kao višeznačna odrednica ako je povezana sa $1.<br />Poveznice iz ostalih imenskih prostora <i>nisu</i> navedene ovdje.',
'disclaimerpage' => '{{ns:4}}:Uslovi korišćenja, pravne napomene i odricanje odgovornosti',
'disclaimers' => 'Odricanje odgovornosti',
'doubleredirects' => 'Dvostruka preusmjerenja',
'doubleredirectstext' => 'Svaki red sadrži veze na prvo i drugo preusmjerenje, kao i na prvu liniju teksta drugog preusmjerenja, što obično daje "pravi" ciljni članak, na koji bi prvo preusmjerenje i trebalo da pokazuje.',
'edit' => 'Uredite',
'editcomment' => 'Komentar izmjene je: "<i>$1</i>".',
'editconflict' => 'Sukobljenje izmjene: $1',
'editcurrent' => 'Izmijenite trenutnu verziju ove stranice',
'edithelp' => 'Pomoć pri uređivanju stranice',
'edithelppage' => '{{ns:4}}:Uređivanje',
'editing' => 'Uređujete $1',
'editingold' => '<strong>PAŽNJA:  Vi mijenjate stariju
reviziju ove stranice.
Ako je snimite, sve promjene učinjene od ove revizije će biti izgubljene.</strong>',
'editsection' => 'uredite',
'editold' => 'uredite',
'editthispage' => 'Uredite ovu stranicu',
'emailfrom' => 'Od',
'emailmessage' => 'Poruka',
'emailpage' => 'Pošalji e-pismo korisniku',
'emailpagetext' => 'Ako je ovaj korisnik unio ispravnu adresu e-pošte u
cvoja korisnička podešavanja, upitnik ispod će poslati jednu poruku.
Adresa e-pošte koju ste vi uneli u svoja korisnička podešavanja će se pojaviti
kao "Od" adresa poruke, tako da će primalac moći da odgovori.',
'emailsend' => 'Pošalji',
'emailsent' => 'Poruka poslata',
'emailsenttext' => 'Vaša poruka je poslata e-poštom.',
'emailsubject' => 'Tema',
'emailto' => 'Za',
'emailuser' => 'Pošalji e-poštu ovom korisniku',
'emptyfile' => 'Fajl koji ste poslali je prazan. Ovo je moguće zbog greške u imenu fajla. Molimo Vas da provjerite da li stvarno želite da pošaljete ovaj fajl.',
'enotif_body' => 'Dragi $WATCHINGUSERNAME,

{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED $PAGEEDITDATE od strane $PAGEEDITOR,
pogledajte {{SERVER}}{{localurl:$PAGETITLE_RAWURL}} za trenutnu verziju.

$NEWPAGE

Rezime editora: $PAGESUMMARY $PAGEMINOREDIT

Kontaktirajte editora:
pošta {{SERVER}}{{localurl:{{ns:-1}}:Emailuser|target=$PAGEEDITOR_RAWURL}}
viki {{SERVER}}{{localurl:User:$PAGEEDITOR_RAWURL}}

Neće biti drugih obaviještenja u slučaju daljih izmjena ukoliko ne posjetite ovu stranu.
Takođe možete da resetujete zastavice za obaviještenja za sve Vaše praćene stranice na vašem spisku praćenenih članaka.

             Vaš prijateljski {{SITENAME}} sistem obaviještavanja

--
Da promjenite podešavanja vezana za spisak praćenenih članaka posjetite
{{SERVER}}{{localurl:{{ns:-1}}:Watchlist|edit=yes}}

Fidbek i dalja pomoć:
{{SERVER}}{{localurl:{{ns:12}}:Sadržaj}}',
'enotif_lastvisited' => 'Pogledajte {{SERVER}}{{localurl:$PAGETITLE_RAWURL|diff=0&oldid=$OLDID}} za sve izmjene od vaše poslednje posjete.',
'enotif_mailer' => '{{SITENAME}} obaviještenje o pošti',
'enotif_newpagetext' => 'Ovo je novi članak.',
'enotif_reset' => 'Označi sve strane kao posjećene',
'enotif_subject' => '{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED od strane $PAGEEDITOR',
'enterlockreason' => 'Unesite razlog za zaključavanje, uključujući procijenu
vremena otključavanja',
'error' => 'Greška',
'errorpagetitle' => 'Greška',
'exbeforeblank' => 'sadržaj prije brisanja je bio: \'$1\'',
'exblank' => 'stranica je bila prazna',
'excontent' => 'sadržaj je bio: \'$1\'',
'explainconflict' => 'Neko drugi je promjenio ovu stranicu otkad ste Vi počeli da je mjenjate.
Gornje tekstualno polje sadrži tekst stranice koji trenutno postoji.
Vaše izmjene su prikazane u donjem tekstu.
Moraćete da unesete svoje promjene u postojeći tekst.
<b>Samo</b> tekst u gornjem tekstualnom polju će biti snimljen kad
pritisnete "Sačuvaj".<br />',
'export' => 'Izvezite stranice',
'exportcuronly' => 'Uključite samo trenutnu reviziju, ne cijelu istoriju',
'exporttext' => 'Možete izvesti tekst i istoriju promjena određene stranice
ili grupe stranice u XML formatu.  Ovo onda može biti uvezeno u drugi viki koji koristi MedijaViki softver, transformisano, ili korišćeno za Vaše lične potrebe.',
'extlink_sample' => 'http://www.adresa.com opis adrese',
'extlink_tip' => 'Spoljašnja poveznica (zapamti prefiks http://)',
'faqpage' => '{{ns:4}}:NPP',
'feb' => 'feb',
'february' => 'februar',
'feedlinks' => 'Fid:',
'filecopyerror' => 'Ne može se kopirati "$1" na "$2".',
'filedeleteerror' => 'Ne može se izbrisati fajl "$1".',
'filedesc' => 'Opis',
'fileexists' => 'Fajl sa ovim imenom već postoji.  Molimo Vas da provjerite $1 ako niste sigurni da li želite da ga promjenite.',
'fileexists-forbidden' => 'Fajl sa ovim imenom već postoji; molimo Vas da se vratite i pošaljete ovaj fajl pod novim imenom. [[{{ns:6}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fajl sa ovim imenom već postoji u zajedničkoj ostavi; molimo Vas da se vratite i pošaljete ovaj fajl pod novim imenom. [[{{ns:6}}:$1|thumb|center|$1]]',
'fileinfo' => '$1KB, MIME tip: <code>$2</code>',
'filemissing' => 'Nedostaje fajl',
'filename' => 'Ime fajla',
'filenotfound' => 'Ne može se naći fajl "$1".',
'filerenameerror' => 'Ne može se promjeniti ime fajla "$1" to "$2".',
'filesource' => 'Izvor',
'filestatus' => 'Status autorskih prava',
'fileuploaded' => 'Fajl "$1" je uspješno poslat.
Molimo Vas da pratite ovu vezu: ($2) do stranice za opisivanje i unesite
informacije o fajlu, kao odakle je, kad i ko ga
je napravio, i bio šta drugo što znate o njemu.',
'formerror' => 'Greška:  ne može se poslati upitnik',
'friday' => 'petak',
'getimagelist' => 'pribavljam spisak slika',
'go' => 'Idi',
'gotaccount' => 'Imate nalog? $1.',
'gotaccountlink' => 'Prijavi se',
'guesstimezone' => 'Popuni iz brauzera',
'headline_sample' => 'Naslov',
'headline_tip' => 'Podnaslov',
'help' => 'Pomoć',
'helppage' => '{{ns:12}}:Sadržaj',
'hide' => 'sakrij',
'hidetoc' => 'sakrij',
'hist' => 'ist',
'histlegend' => 'Objašnjenje: (tren) = razlika sa trenutnom verziom,
(posl) = razlika sa prethodnom verziom, M = mala izmjena',
'history' => 'Istorija stranice',
'history_short' => 'Istorija',
'historywarning' => 'Pažnja:  stranica koju želite da obrišete ima istoriju:',
'hr_tip' => 'Horizontalna linija (koristite oskudno)',
'ilsubmit' => 'Traži',
'image_sample' => 'ime_slike.jpg',
'image_tip' => 'Uklopljena slika',
'imagelinks' => 'Upotreba slike',
'imagelist' => 'Spisak slika',
'imagelisttext' => 'Ispod je spisak $1 slika poređanih $2.',
'imagepage' => 'Pogjedajte stranicu slike',
'imagereverted' => 'Vraćanje na raniju verziju je uspješno.',
'imgdelete' => 'obr',
'imgdesc' => 'opis',
'imghistlegend' => 'Objašnjenje:  (tren) = ovo je trenutna slika, (obr) = obrišite
ovu staru verziju, (vrt) = vrati na ovu staru verziju.
<br /><i>Kliknite na datum da vidite sliku poslatu tog dana</i>.',
'imghistory' => 'Istorija slike',
'imglegend' => 'Objašnjenje:  (opis) = prikaži/izmjeni opis slike.',
'import' => 'Ivoz stranica',
'importfailed' => 'Uvoz nije uspjeo: $1',
'importhistoryconflict' => 'Postoji konfliktna istorija revizija',
'importnotext' => 'Stranica je prazna, ili bez teksta',
'importsuccess' => 'Uspješno ste uvezli stranicu!',
'importtext' => 'Molimo Vas da izvezete fajl iz izvornog vikija koristeći [[{{ns:-1}}:Export|izvoz]], sačuvajte ga kod sebe i pošaljite ovde.',
'internalerror' => 'Interna greška',
'intl' => 'Međujezičke veze',
'ip_range_invalid' => 'Netačan raspon IP adresa.',
'ipaddress' => 'IP adresa/korisničko ime',
'ipb_expiry_invalid' => 'Pogrešno vrijeme trajanja.',
'ipbexpiry' => 'Trajanje',
'ipblocklist' => 'Spisak blokiranih IP adresa i korisničkih imena',
'ipbreason' => 'Razlog',
'ipbsubmit' => 'Blokirajte ovog korisnika',
'ipusubmit' => 'Deblokirajte ovog korisnika',
'isredirect' => 'preusmjerivač',
'italic_sample' => 'Kurzivan tekst',
'italic_tip' => 'Kurzivan tekst',
'iteminvalidname' => 'Problem sa \'$1\', neispravno ime...',
'jan' => 'jan',
'january' => 'januar',
'jul' => 'jul',
'july' => 'jul',
'jun' => 'jun',
'june' => 'jun',
'largefile' => 'Preporučuje se da slike ne pređu veličinu od 100K.',
'last' => 'posl',
'lastmodifiedat' => 'Ova stranica je poslednji put izmijenjena $2, $1',
'lastmodifiedby' => 'Ovu stranicu je poslednji put promjenio $2, dana $2.',
'lineno' => 'Linija $1:',
'link_sample' => 'Naslov poveznice',
'link_tip' => 'Unutrašnja poveznica',
'linklistsub' => '(Spisak veza)',
'linkshere' => 'Sledeće stranice su povezane ovdje:',
'linkstoimage' => 'Sledeće stranice koriste ovu sliku:',
'listusers' => 'Spisak korisnika',
'loadhist' => 'Učitaje se istorija stranice',
'loadingrev' => 'učitava se revizija za razliku',
'localtime' => 'Lokalno vrijeme',
'lockbtn' => 'Zaključajte bazu',
'lockconfirm' => 'Da, zaista želim da zaključam bazu.',
'lockdb' => 'Zaključajte bazu',
'lockdbsuccesssub' => 'Baza je zaključana',
'lockdbsuccesstext' => '{{SITENAME}} baza podataka je zaključana. <br /> Sjetite se da je otključate kad završite sa održavanjem.',
'lockdbtext' => 'Zaključavanje baze će svim korisnicima ukinuti mogućnost izmjene stranica,
promjene korisničkih podešavanja, izmjene praćenih članaka, i svega ostalog
što zahtjeva promjene u bazi.
Molimo Vas da potvrdite da je ovo zaista ono što namjeravate da uradite, i da ćete
otkučati bazu kad završite posao oko njenog održavanja.',
'locknoconfirm' => 'Niste potvrdili svoju namjeru.',
'login' => 'Prijavi se',
'loginerror' => 'Greška pri prijavljivanju',
'loginpagetitle' => 'Prijavljivanje',
'loginproblem' => '<b>Bilo je problema sa vašim prijavljivanjem.</b><br />Probajte ponovo!',
'loginprompt' => 'Morate imati kolačiće (\'\'\'cookies\'\'\') omogućene da biste se prijavili na {{SITENAME}}.',
'loginreqtitle' => 'Potrebno je [[{{ns:-1}}:Userlogin|prijavljivanje]]',
'loginsuccess' => '\'\'\'Sad ste prijavljeni na {{SITENAME}} kao "$1".\'\'\'',
'loginsuccesstitle' => 'Prijavljivanje uspješno',
'logout' => 'Odjavite se',
'logouttext' => '<strong>Sad ste odjavljeni.</strong><br />
Možete nastaviti da koristite {{SITENAME}} anonimno, ili se ponovo prijaviti
kao isti ili kao drugi korisnik.  Obratite pažnju da neke stranice mogu nastaviti da se prikazuju kao da ste još uvijek prijavljeni, dok ne očistite keš svog brauzera.',
'logouttitle' => 'Odjavite se',
'lonelypages' => 'Siročići',
'longpages' => 'Dugačke stranice',
'longpagewarning' => '<strong>PAŽNJA:  Ova stranica ima $1 kilobajta; niki
brauzeri mogu imati problema kad uređujete stranice skoro ili veće od 32 kilobajta.
Molimo Vas da razmotrite razbijanje stranice na manje dijelove.</strong>',
'mailerror' => 'Greška pri slanju e-pošte: $1',
'mailmypassword' => 'Pošalji mi moju lozinku',
'mailnologin' => 'Nema adrese za slanje',
'mailnologintext' => 'Morate biti [[Special:Userlogin|prijavljeni]]
i imati ispravnu adresu e-pošte u vašim [[Special:Preferences|podešavanjima]]
da biste slali e-poštu drugim korisnicima.',
'mainpage' => 'Glavna stranica',
'mainpagetext' => 'Viki softver is uspješno instaliran.',
'makesysop' => 'Dodijeli administratorska prava korisniku',
'makesysopfail' => '<b>Korisnik "$1" nije mogao dobiti administratorska prava. (Da li ste pravo unijeli ime?)</b>',
'makesysopname' => 'Ime korisnika:',
'makesysopok' => '<b>Korisnik "$1" je sad administrator</b>',
'makesysopsubmit' => 'Dodajte ovom korisniku administratorska prava',
'makesysoptext' => 'Ovaj formular se koristi sa strane birokrata da se obični korisnici pretvore u administratore.  Unesite ime korisnika u kutiju i pritisnite dugme da bi korisnik postao administrator.',
'makesysoptitle' => 'Pretvorite korisnika u administratora',
'mar' => 'mar',
'march' => 'mart',
'markaspatrolleddiff' => 'Označi kao patrolirano',
'markaspatrolledtext' => 'Označi ovaj članak kao patroliran',
'markedaspatrolled' => 'Označeno kao patrolirano',
'markedaspatrollederror' => 'Ne može se označiti kao patrolirano',
'markedaspatrollederrortext' => 'Morate naglasiti reviziju koju treba označiti kao patroliranu.',
'markedaspatrolledtext' => 'Izabrana revizija je označena kao patrolirana.',
'matchtotals' => 'Upit "$1" je nađen u "$2" naslova članaka
i tekst $3 članaka.',
'math' => 'Prikazivanje matematike',
'math_bad_output' => 'Ne može se napisati ili napraviti direktorijum za matematični izvještaj.',
'math_bad_tmpdir' => 'Ne može se napisati ili napraviti privremeni matematični direktorijum',
'math_failure' => 'Neuspjeh pri parsiranju',
'math_image_error' => 'PNG konverzija neuspješna; provjerite tačnu instalaciju latex-a, dvips-a, gs-a i convert-a',
'math_lexing_error' => 'riječnička greška',
'math_notexvc' => 'Nedostaje izvršno texvc; molimo Vas da pogledate math/README da podesite.',
'math_sample' => 'Unesite formulu ovdje',
'math_syntax_error' => 'sintaksna greška',
'math_tip' => 'Matematička formula (LaTeX)',
'math_unknown_error' => 'nepoznata greška',
'math_unknown_function' => 'nepoznata funkcija',
'may' => 'maj',
'media_sample' => 'ime_medija_fajla.ogg',
'media_tip' => 'Putanja ka multimedijalnom fajlu',
'mediawarning' => '\'\'\'Upozorenje\'\'\': Ovaj fajl sadrži loš kod, njegovim izvršavanjem možete da ugrozite Vaš sistem.
<hr />',
'metadata' => 'Metapodaci',
'mimesearch' => 'MIME pretraga',
'mimetype' => 'MIME tip:',
'minlength' => 'Imena fajlova moraju imati bar tri slova.',
'minoredit' => 'Ovo je mala izmjena',
'missingarticle' => 'Baza nije mogla naći tekst stranice koji je trebala da nađe, nazvan "$1".

Ovo je obično izazvano praćenjem zastarijelog "razl" ili veze ka istoriji
stranice koja je obrisana.

Ako ovo nije slučaj, možda ste pronašli grešku u softveru.
Molimo Vas da prijaviti ovo jednom od [[{{ns:4}}:Administratori|administratora]], zajedno sa URL-om.',
'missingimage' => '<b>Ovdje nedostaje slika</b><br /><i>$1</i>',
'monday' => 'ponedeljak',
'moredotdotdot' => 'Još...',
'move' => 'Premjestite',
'movearticle' => 'Premjestite stranicu',
'movedto' => 'premještena na',
'movenologin' => 'Niste prijavljeni',
'movenologintext' => 'Morate biti registrovani korisnik i [[Special:Userlogin|prijavljeni]]
da biste premjestili stranicu.',
'movepage' => 'Premjestite stranicu',
'movepagebtn' => 'premjestite stranicu',
'movepagetalktext' => 'Odgovarajuća stranica za razgovor, ako postoji, će automatski biti premještena istovremeno \'\'\'osim:\'\'\'
*Ako premještate stranicu preko imenskih prostora,
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odčekirajte donju kutiju.

U tim slučajevima, moraćete ručno da premjestite stranicu ukoliko to želite.',
'movepagetext' => 'Donji upitnik će preimenovati stranicu, premještajući svu
njenu istoriju na novo ime.
Stari naslov će postati preusmjerenje na novi naslov.
Poveznice prema starom naslovu neće biti promijenjene; obavezno
provjerite da li ima [[{{ns:-1}}:DoubleRedirects|dvostrukih]] ili [[{{ns:-1}}:BrokenRedirects|pokvarenih preusmjerenja]].
Na vama je odgovornost da veze i dalje idu tamo gdje trebaju da idu.

Obratite pažnju da stranica \'\'\'neće\'\'\' biti pomjerena ako već postoji
stranica sa novim naslovom, osim ako je ona prazna ili preusmjerenje i nema
istoriju promjena.   Ovo znači da ne možete preimenovati stranicu na ono ime
sa koga ste je preimenovali ako pogriješite, i ne možete prepisati
postojeću stranicu.

<b>PAŽNJA!</b>
Ovo može biti drastična i neočekivana promjena za popularnu stranicu;
molimo Vas da budete sigurni da razumijete poslijedice ovoga prije što
nastavite.',
'movetalk' => 'Premjestite "stranicu za razgovor" takođe, ako je moguće.',
'movethispage' => 'Premjesti ovu stranicu',
'mycontris' => 'Moj doprinos',
'mypage' => 'Moja stranica',
'mytalk' => 'Moj razgovor',
'navigation' => 'Navigacija',
'nbytes' => '$1 bajtova',
'newarticle' => '(Novi)',
'newarticletext' => '<div style="border: 1px solid #ccc; padding: 7px;">\'\'\'{{SITENAME}} nema stranicu {{PAGENAME}}.\'\'\'
* Da započnete stranicu, koristite prostor ispod i kad završite, pritisnite "Sačuvaj".  Vaše izmjene će odmah biti vidljive.
* Ako ste novi na prjektu {{SITENAME}}, molimo Vas da pogledate [[{{ns:4}}:Pomoć|pomoćnu stranicu]], ili koristite [[{{ns:4}}:Igralište|igralište]] za eksperimentaciju.
</div>',
'newmessageslink' => 'novih poruka',
'newpage' => 'Nova stranica',
'newpages' => 'Nove stranice',
'newpassword' => 'Nova lozinka:',
'newtitle' => 'Novi naslov',
'next' => 'sled',
'nextn' => 'sledećih $1',
'nlinks' => '$1 veza',
'noarticletext' => '<div style="border: 1px solid #ccc; padding: 7px;">\'\'\'{{SITENAME}} još nema ovaj članak.\'\'\'
* Da započnete članak, kliknite \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} uredite ovu stranicu]\'\'\'.
* [[{{ns:-1}}:Search/{{PAGENAME}}|Pretraži {{PAGENAME}}]] u ostalim člancima
* [[{{ns:-1}}:Whatlinkshere/{{NAMESPACE}}:{{PAGENAME}}|Stranice koje su povezane za]] {{PAGENAME}} članak
----
* \'\'\'Ukoliko ste napravili ovaj članak u poslednjih nekoliko minuta i još se nije pojavio, postoji mogućnost da je server u zastoju zbog osvježavanja baze podataka.\'\'\' Molimo Vas da probate sa <span class="plainlinks">[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=purge}} osvježavanjem]<span> ili sačekajte i provjerite kasnije ponovo prije ponovnog pravljenja članka.
* Ako ste napravili članak pod ovim imenom ranije, moguće je da je bio izbrisan.  Potražite \'\'\'{{FULLPAGENAME}}\'\'\' [{{fullurl:Special:Log|type=delete&page={{FULLPAGENAMEE}}}} u spisku brisanja].  Alternativno, provjerite [[{{ns:4}}:Zahtjevi za brisanje#{{PAGENAME}}|ovdje]].
</div>',
'noconnect' => 'Žao nam je! Viki ima neke tehničke poteškoće, i ne može da se poveže sa serverom baze. <br />',
'nocontribs' => 'Nisu nađene promjene koje zadovoljavaju ove uslove.',
'nocookieslogin' => '{{SITENAME}} koristi kolačiće (\'\'cookies\'\') da bi se korisnici prijavili.  Vi ste onemogućili kolačiće na Vašem kompjuteru.  Molimo Vas da ih omogućite i da pokušate ponovo sa prijavom.',
'nocookiesnew' => 'Korisnički nalog je napravljen, ali niste prijavljeni.  {{SITENAME}} koristi kolačiće (\'\'cookies\'\') da bi se korisnici prijavili.  Vi ste onemogućili kolačiće na Vašem kompjuteru.  molimo Vas da ih omogućite, a onda se prijavite sa svojim novim korisničkim imenom i lozinkom.',
'nocreativecommons' => 'Creative Commons RDF metapodaci onemogućeni za ovaj server.',
'nodb' => 'Ne mogu da izaberem bazu $1',
'nodublincore' => 'Dublin Core RDF metapodaci onemogućeni za ovaj server.',
'noemail' => 'Ne postoji adresa e-pošte za korisnika "$1".',
'noemailtext' => 'Ovaj korisnik nije naveo ispravnu adresu e-pošte,
ili je izabrao da ne prima e-poštu od drugih korisnika.',
'noemailtitle' => 'Nema adrese e-pošte',
'noexactmatch' => 'Nema stranice sa takvim imenom.

Možete \'\'\'[[:$1|da napravite članak sa tim naslovom]]\'\'\' ili [[{{ns:4}}:Zahtjevani članci|da stavite zahtjev za ovaj članak]] ili [[{{ns:-1}}:Allpages/$1|potražite na drugim stranicama]].

::*\'\'\'\'\'<u>Opomena: Nemojte da kopirate materijale za koje nemate dozvolu!</u>\'\'\'\'\'',
'nohistory' => 'Ne postoji istorija izmjena za ovu stranicu.',
'nolinkshere' => 'Ništa nije povezano ovdje.',
'nolinkstoimage' => 'Nema stranica koje koriste ovu sliku.',
'nologinlink' => 'Napravite nalog',
'noname' => 'Niste izabrali ispravno korisničko ime.',
'nonefound' => '\'\'\'Pažnja\'\'\': neuspješne pretrage su
često izazvane traženjem čestih riječi kao "je" ili "od",
koje nisu indeksirane, ili navođenjem više od jednog izraza za traženje (samo stranice
koje sadrže sve izraze koji se traže će se pojaviti u rezultatima).',
'nospecialpagetext' => 'Tražili ste posebnu stranicu, koju {{SITENAME}} softver nije prepoznao.',
'nosuchaction' => 'Nema takve akcije',
'nosuchactiontext' => 'Akcija navedena u URL-u nije
prepoznata od strane {{SITENAME}} softvera.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nosuchuser' => 'Ne postoji korisnik sa imenom "$1". Provjerite Vaše kucanje, ili upotrebite donji upitnik da napravite novi korisnički nalog.',
'notacceptable' => 'Viki server ne može da pruži podatke u onom formatu koji Vaš klijent može da pročita.',
'notanarticle' => 'Nije članak',
'notargettext' => 'Niste naveli ciljnu stranicu ili korisnika
na kome bi se izvela ova funkcija.',
'notargettitle' => 'Nema cilja',
'note' => '<strong>Pažnja:</strong>',
'notextmatches' => 'Tekst članka ne odgovara',
'notitlematches' => 'Naslov članka ne odgovara.',
'notloggedin' => 'Niste prijavljeni',
'nov' => 'nov',
'november' => 'novembar',
'nowatchlist' => 'Nemate ništa na svom spisku praćenih članaka.',
'nowiki_sample' => 'Dodaj neformatirani tekst ovdje',
'nowiki_tip' => 'Ignoriši viki formatiranje teksta',
'nstab-category' => 'Kategorija',
'nstab-help' => 'Pomoć',
'nstab-image' => 'Slika',
'nstab-main' => 'Članak',
'nstab-media' => 'Medija',
'nstab-mediawiki' => 'Poruka',
'nstab-special' => 'Posebna',
'nstab-template' => 'Šablon',
'nstab-user' => 'Korisnička stranica',
'nstab-project' => 'Članak',
'nviews' => '$1 puta pogledano',
'oct' => 'okt',
'october' => 'oktobar',
'ok' => 'da',
'oldpassword' => 'Stara lozinka:',
'orig' => 'orig',
'othercontribs' => 'Bazirano na radu od strane korisnika $1.',
'otherlanguages' => 'Ostali jezici',
'pagemovedsub' => 'Premještanje uspjelo',
'pagemovedtext' => 'Stranica "[[$1]]" premještena je na "[[$2]]".',
'pagetitle' => '$1 - {{SITENAME}}',
'passwordremindertext' => 'Neko (vjerovatno Vi, sa IP adrese $1)
je zahtjevao da vam pošaljemo novu {{SITENAME}} lozinku za prijavljivanje na {{SERVERNAME}}.
Lozinka za korisnika "$2" je sad "$3".
Sad treba da se prijavite i promjenite lozinku.

Ako je neko drugi napravio ovaj zahtjev ili ako ste se sjetili vaše lozinke i
ne želite više da je promjenite, možete da ignorišete ovu poruku i da nastavite koristeći
vašu staru lozinku.',
'passwordremindertitle' => '{{SITENAME}} podsjetnik za lozinku',
'passwordsent' => 'Nova lozinka je poslata na adresu e-pošte
korisnika "$1".
Molimo Vas da se prijavite pošto je primite.',
'perfcached' => 'Sledeći podaci su keširani i možda neće biti u potpunosti ažurirani:',
'perfdisabled' => 'Žao nam je!  Ova mogućnost je privremeno onemogućena jer usporava bazu do te mjere da više niko ne može da koristi viki.',
'perfdisabledsub' => 'Ovdje je sačuvana kopija $1:',
'permalink' => 'Trajna veza',
'personaltools' => 'Lični alati',
'popularpages' => 'Popularne stranice',
'portal' => 'Portal zajednice',
'portal-url' => '{{ns:4}}:Portal_zajednice',
'postcomment' => 'Pošaljite komentar',
'powersearch' => 'Traži',
'powersearchtext' => 'Pretraga u imenskim prostorima :<br />
$1<br />
$2 Izlistajte preusmjerenja &nbsp; Tražite $3 $9',
'preferences' => 'Podešavanja',
'prefs-help-email' => '* E-mail (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
'prefs-misc' => 'Ostala podešavanja',
'prefs-personal' => 'Korisnički podaci',
'prefs-rc' => 'Podešavanja nedavnih izmjena',
'prefsnologin' => 'Niste prijavljeni',
'prefsnologintext' => 'Morate biti [[{{ns:-1}}:Userlogin|prijavljeni]] da biste podešavali korisnička podešavanja.',
'prefsreset' => 'Podešavanja su vraćena na prvotne vrijednosti.',
'preview' => 'Pregled stranice',
'previewconflict' => 'Ovaj pregled reflektuje tekst u gornjem polju
kako će izgledati ako pritisnete "Sačuvaj članak".',
'previewnote' => '<strong>Ovo je samo pregled; izmjene stranice nisu još sačuvane!</strong>',
'prevn' => 'prethodnih $1',
'print' => 'Štampa',
'printableversion' => 'Verzija sa štampanje',
'protect' => 'Zaštitite',
'protect-default' => '(standardno)',
'protect-level-autoconfirmed' => 'Blokiraj neregistrovane korisnike',
'protect-level-sysop' => 'Samo administratori',
'protect-text' => 'Ovdje možete gledati i izmjeniti level zaštite za stranicu <strong>$1</strong>.
Budite sigurni da pratite [[{{ns:4}}:Zaštićena stranica|uputstva projekta]].',
'protect-unchain' => 'Deblokirajte dozvole premještanja',
'protect-viewtext' => 'Vaš nalog nema dozvolu da promjeni level zaštite stranica.
Ovo su trenutna podešavanja za stranicu <strong>$1</strong>:',
'protectcomment' => 'Razlog za zaštitu',
'protectedarticle' => 'stranica "[[$1]]" je zaštićena',
'protectedpage' => 'Zaštićena stranica',
'protectedpagewarning' => '<strong>PAŽNJA:  Ova stranica je zaključana tako da samo korisnici sa
administratorkim privilegijama mogu da je mijenjaju.  Uvjerite se da pratite [[{{ns:4}}:Pravila o zaštiti stranica|pravila o zaštiti stranica]]</strong>.',
'protectedtext' => 'Ova stranica je zaključana i ne može se uređivati; moguće je da ima
mnogo razloga za ovo, molimo Vas da pogledate [[{{ns:4}}:Zaštićena stranica]].

Možete gledati i kopirati sadržaj ove stranice:',
'protectlogpage' => 'Istorija zaključavanja',
'protectlogtext' => 'Ispod je spisak zaštićenja stranice.
Pogledajte [[{{ns:4}}:Zaštićena stranica]] za više informacija.',
'protectsub' => '(Zaštićuje se "$1")',
'protectthispage' => 'Zaštitite ovu stranicu',
'proxyblocker' => 'Bloker proksija',
'proxyblockreason' => 'Vaša IP adresa je blokirana jer je ona otvoreni proksi.  Molimo vas da kontaktirate vašeg davatelja internetskih usluga (Internet Service Provider-a) ili tehničku podršku i obavijestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess' => 'Proksi uspješno blokiran.',
'qbbrowse' => 'Prelistajte',
'qbedit' => 'Izmjenite',
'qbfind' => 'Pronađite',
'qbmyoptions' => 'Moje opcije',
'qbpageinfo' => 'Informacije o stranici',
'qbpageoptions' => 'Opcije stranice',
'qbsettings' => 'Podešavanja brze palete',
'qbspecialpages' => 'Posebne stranice',
'randompage' => 'Slučajna stranica',
'range_block_disabled' => 'Administratorska mogućnost da blokira grupe je isključena.',
'rclinks' => 'Prikaži najskorijih $1 izmjena u poslednjih $2 dana; $3',
'rclistfrom' => 'Prikaži nove izmjene počev od $1',
'rclsub' => '(na stranice povezane sa "$1")',
'rcnote' => 'Ispod je najskorijih <strong>$1</strong> izmjena u poslednjih <strong>$2</strong> dana.',
'rcnotefrom' => 'Ispod su izmjene od <b>$2</b> (do <b>$1</b> prikazano).',
'rcshowhideanons' => '$1 anonimne korisnike',
'rcshowhidebots' => '$1 botove',
'rcshowhideliu' => '$1 prijavljene korisnike',
'rcshowhidemine' => '$1 moje izmjene',
'rcshowhideminor' => '$1 male izmjene',
'rcshowhidepatr' => '$1 patrolirane izmjene',
'readonly' => 'Baza je zaključana',
'readonlytext' => 'Baza je trenutno zaključana za nove unose i ostale izmjene, vjerovatno zbog rutinskog održavanja, posle čega će biti vraćena u uobičajeno stanje.

Administrator koji ju je zaključao je ponudio ovo objašnjenje: $1',
'readonlywarning' => '<strong>PAŽNJA:  Baza je zaključana zbog održavanja,
tako da nećete moći da sačuvate svoje izmjene za sada.  Možda želite da kopirate
i nalijepite tekst u tekst editor i sačuvate ga za kasnije.</strong>',
'recentchanges' => 'Nedavne izmjene',
'recentchangesall' => 'sve',
'recentchangescount' => 'Broj naslova u nedavnim izmjenama:',
'recentchangeslinked' => 'Srodne izmjene',
'recentchangestext' => 'Na ovoj stranici možete pratiti nedavne izmjene.',
'redirectedfrom' => '(Preusmjereno sa $1)',
'remembermypassword' => 'Zapamti me',
'removechecked' => 'Uklonite označene unose iz spiska praćenih članaka',
'removedwatch' => 'Uklonjeno iz spiska praćenih članaka',
'removedwatchtext' => 'Stranica "$1" je uklonjena iz vašeg spiska praćenih članaka.',
'removingchecked' => 'Uklanjaju se ove stranice sa spiska praćenih članaka...',
'resetprefs' => 'Vrati podešavanja',
'restorelink' => '$1 izbrisanih izmjena',
'resultsperpage' => 'Pogodaka po stranici:',
'retrievedfrom' => 'Dobavljeno iz "$1"',
'returnto' => 'Povratak na $1.',
'retypenew' => 'Ukucajte ponovo novu lozinku:',
'reupload' => 'Ponovo pošaljite',
'reuploaddesc' => 'Vratite se na upitnik za slanje.',
'reverted' => 'Vraćeno na prijašnju reviziju',
'revertimg' => 'vrt',
'revertmove' => 'vrati',
'revertpage' => 'Vraćene izmjene $2 na poslednju izmjenu korisnika $1',
'revhistory' => 'Istorija izmjena',
'revisionasof' => 'Revizija od $1',
'revnotfound' => 'Revizija nije pronađena',
'revnotfoundtext' => 'Starija revizija ove stranice koju ste zatražili nije nađena.
Molimo Vas da provjerite URL pomoću kojeg ste pristupili ovoj stranici.',
'rights' => 'Prava:',
'rollback' => 'Vrati izmjene',
'rollback_short' => 'Vrati',
'rollbackfailed' => 'Vraćanje nije uspjelo',
'rollbacklink' => 'vrati',
'rows' => 'Redova',
'saturday' => 'subota',
'savearticle' => 'Sačuvaj',
'savedprefs' => 'Vaša podešavanja su sačuvana.',
'savefile' => 'Sačuvaj fajl',
'saveprefs' => 'Sačuvajte podešavanja',
'search' => 'Pretraži',
'searchbutton' => 'Pretraži',
'searchdisabled' => '<p>Izvinjavamo se!  Puno pretraga teksta je privremeno onemogućena.  U međuvremenu, možete koristiti Google za pretragu.  Indeks može biti stariji.',
'searchsubtitle' => 'Tražili ste [[:$1]] [[Special:Allpages/$1|&#x5B;Sadržaj&#x5D;]]',
'searchsubtitleinvalid' => 'Tražili ste $1 ',
'searchresults' => 'Rezultati pretrage',
'searchresultshead' => 'Podešavanja rezultata pretrage',
'searchresulttext' => 'Za više informacija o pretraživanju {{SITENAME}}, pogledajte [[{{ns:4}}:Pretraga|Pretraga]].',
'selectnewerversionfordiff' => 'Izaberite noviju verziju za upoređivanje',
'selectolderversionfordiff' => 'Izaberite stariju verziju za upoređivanje',
'selfmove' => 'Izvorni i ciljani naziv su isti; strana ne može da se premjesti preko same sebe.',
'semiprotectedpagewarning' => '\'\'\'Pažnja:\'\'\' Ova stranica je zaključana tako da je samo registrovani korisnici mogu uređivati.',
'sep' => 'sep',
'september' => 'septembar',
'servertime' => 'Vrijeme na serveru',
'set_rights_fail' => '<b>Korisnička prava za $"1" nisu mogla da se podese.  (Da li ste pravilno unijeli ime?)</b>',
'set_user_rights' => 'Postavi prava korisnika',
'setbureaucratflag' => 'Postavi prava birokrate',
'shortpages' => 'Kratke stranice',
'show' => 'pokaži',
'showbigimage' => 'Prikaži sliku veće rezolucije ($1x$2, $3 Kb)',
'showdiff' => 'Prikaži izmjene',
'showhidebots' => '($1 botove)',
'showingresults' => 'Prikazani su <b>$1</b> rezultata počev od <b>$2</b>.',
'showingresultsnum' => 'Prikazani su <b>$3</b> rezultati počev od <b>$2</b>.',
'showlast' => 'Prikaži poslednjih $1 slika sortiranih po $2.',
'showpreview' => 'Prikaži izgled',
'showtoc' => 'prikaži',
'sig_tip' => 'Vaš potpis sa trenutnim vremenom',
'sitestats' => 'Statistika sajta',
'sitestatstext' => '<p style="font-size:125%;margin-bottom:0">{{SITENAME}} trenutno ima \'\'\'$2\'\'\' članaka.</p>
<p style="margin-top:0">Ovaj broj isključuje preusmjerenja, stranice za razgovor, stranice sa opisom slike, korisničke stranice, šablone, stranice za pomoć, članke bez poveznica, i stranice o projektu {{SITENAME}}.</p>
<p>
Totalni broj stranica u bazi:  \'\'\'$1\'\'\'.</p>
<p>
Bilo je \'\'\'$3\'\'\' pogleda stranica, i \'\'\'$4\'\'\' izmjena otkad je viki bio instaliran.
To izađe u prosjeku oko \'\'\'$5\'\'\' izmjena po stranici, i \'\'\'$6\'\'\' pogleda po izmjeni.
</p>',
'sitesupport' => 'Donacije',
'sitesupport-url' => '{{ns:4}}:Donacije',
'siteuser' => '{{SITENAME}} korisnik $1',
'siteusers' => '{{SITENAME}} korisnik (korisnici) $1',
'skin' => 'Koža',
'skinpreview' => '(Pregled)',
'spamprotectionmatch' => 'Sledeći tekst je izazvao naš filter za neželjene poruke: $1',
'spamprotectiontext' => 'Strana koju želite da sačuvate je blokirana od strane filtera za neželjene poruke.  Ovo je vjerovatno izazvao vezom ka spoljašnjem sajtu.',
'spamprotectiontitle' => 'Filter za zaštitu od neželjenih poruka',
'speciallogtitlelabel' => 'Naslov:',
'specialloguserlabel' => 'Korisnik:',
'specialpage' => 'Posebna Stranica',
'specialpages' => 'Posebne stranice',
'spheading' => 'Posebne stranice za sve korisnike',
'sqlhidden' => '(SQL pretraga sakrivena)',
'statistics' => 'Statistike',
'storedversion' => 'Uskladištena verzija',
'stubthreshold' => 'Granica za prikazivanje klica',
'subcategories' => 'Potkategorije',
'subcategorycount' => '$1 potkategorija su u ovoj kategoriji.',
'subject' => 'Tema/naslov',
'successfulupload' => 'Uspješno slanje',
'summary' => 'Sažetak',
'sunday' => 'nedelja',
'talk' => 'Razgovor',
'talkexists' => 'Sama stranica je uspješno premještena, ali
stranica za razgovor nije mogla biti premještena jer takva već postoji na novom naslovu.  Molimo Vas da ih spojite ručno.',
'talkpage' => 'Razgovor o stranici',
'talkpagemoved' => 'Odgovarajuća stranica za razgovor je takođe premještena.',
'talkpagenotmoved' => 'Odgovarajuća stranica za razgovor <strong>nije</strong> premještena.',
'templatesused' => 'Šabloni koji su upotrebljeni na ovoj stranici:',
'textboxsize' => 'Veličine tekstualnog polja',
'textmatches' => 'Tekst stranice odgovara',
'thisisdeleted' => 'Pogledaj ili vrati $1?',
'thumbnail-more' => 'uvećajte',
'thumbsize' => 'Veličina umanjenog prikaza:',
'thursday' => 'četvrtak',
'timezonelegend' => 'Vremenska zona',
'timezoneoffset' => 'Odstupanje',
'timezonetext' => 'Unesite broj sati za koji se Vaše lokalno vrijeme razlikuje od serverskog vremena (UTC).',
'titlematches' => 'Naslov članka odgovara',
'toc' => 'Sadržaj',
'tog-autopatrol' => 'Označi moje izmjene kao patrolirane',
'tog-enotifminoredits' => 'Pošalji mi e-poštu takođe za male izmjene stranica',
'tog-enotifrevealaddr' => 'Otkrij adresu moje e-pošte u porukama obaviještenja',
'tog-enotifusertalkpages' => 'Pošalji mi e-poštu kad se promijeni moja korisnička stranica za razgovor',
'tog-enotifwatchlistpages' => 'Pošalji mi e-poštu kad se promijene stranice',
'tog-hideminor' => 'Sakrij male izmjene u spisku nedavnih izmjena',
'tog-highlightbroken' => 'Formatiraj pokvarene veze <a href="" class="new">ovako</a> (alternativa: ovako<a href="" class="internal">?</a>).',
'tog-justify' => 'Uravnjaj pasuse',
'tog-minordefault' => 'Označi sve izmjene malim isprva',
'tog-nocache' => 'Onemogući keširanje stranica',
'tog-numberheadings' => 'Automatski numeriši podnaslove',
'tog-previewonfirst' => 'Prikaži izgled pri prvoj izmjeni',
'tog-previewontop' => 'Prikaži pretpregled prije polja za izmjenu a ne posle',
'tog-rememberpassword' => 'Zapamti lozinku kroz više seansi',
'tog-showjumplinks' => 'Omogući "skoči na" poveznice',
'tog-shownumberswatching' => 'Prikaži broj korisnika koji prate',
'tog-showtoc' => 'Prikaži sadržaj<br />(u svim stranicama sa više od tri podnaslova)',
'tog-showtoolbar' => 'Prikaži dugmiće za izmjene (JavaScript)',
'tog-underline' => 'Podvuci veze:',
'tog-usenewrc' => 'Poboljšan spisak nedavnih izmjena (JavaScript)',
'tog-watchcreations' => 'Dodaj stranice koje ja napravim u moj spisak praćenih članaka',
'tog-watchdefault' => 'Dodaj stranice koje uređujem u moj spisak praćenih članaka',
'toolbox' => 'Posebne funkcije',
'tooltip-compareselectedversions' => 'Pogledajte pazlike između dvije selektovane verzije ove stranice. [alt-v]',
'tooltip-minoredit' => 'Naznačite da se radi o maloj izmjeni [alt-i]',
'tooltip-preview' => 'Pregledajte Vaše izmjene; molimo Vas da koristite ovo prije nego što sačuvate stranicu! [alt-p]',
'tooltip-save' => 'Sačuvajte Vaše izmjene [alt-s]',
'tooltip-search' => 'Pretražite projekat {{SITENAME}} [alt-f]',
'tooltip-watch' => 'Dodajte ovu stranicu na Vaš spisak praćenih članaka [alt-w]',
'tuesday' => 'utorak',
'uclinks' => 'Gledaj poslednjih $1 izmjena; gledaj poslednjih $2 dana.',
'ucnote' => 'Ispod je poslednjih <b>$1</b> izmjena u poslednjih <b>$2</b> dana.',
'uctop' => ' (vrh)',
'uid' => 'Korisnički ID:',
'unblockip' => 'Odblokiraj korisnika',
'unblockiptext' => 'Upotrebite donji upitnik da bi ste vratili
pravo pisanja ranije blokiranoj IP adresi
ili korisničkom imenu.',
'unblocklink' => 'deblokiraj',
'unblocklogentry' => 'deblokiran $1',
'uncategorizedcategories' => 'Nekategorisane kategorije',
'uncategorizedpages' => 'Nekategorisane stranice',
'undelete' => 'Pogledaj izbrisane stranice',
'undelete_short' => 'Vrati $1 obrisanih izmjena',
'undeletearticle' => 'Vrati izbrisani članak',
'undeletebtn' => 'Vrati!',
'undeletedarticle' => 'vraćeno "$1"',
'undeletedrevisions' => '$1 revizija vraćeno',
'undeletehistory' => 'Ako vratite stranicu, sve revizije će biti vraćene njenoj istoriji.
Ako je nova stranica istog imena napravljena od brisanja, vraćene
revizije će se pojaviti u ranijoj istoriji, a trenutna revizija sadašnje stranice
neće biti automatski zamijenjena.',
'undeletehistorynoadmin' => 'Ova stranica je izbrisana.  Ispod se nalazi dio istorije brisanja i istorija revizija izbrisane stranice.  Tekst izbrisane stranice je vidljiv samo korisnicima koji su administratori.',
'undeletepage' => 'Pogledaj i vrati izbrisane stranice',
'undeletepagetext' => 'Sledeće stranice su izbrisane ali su još uvijek u arhivi i
mogu biti vraćene.  Arhiva moše biti periodično čišćena.',
'undeleterevision' => 'Izbrisana revizija od $1',
'undeleterevisions' => '$1 revizija arhivirano',
'underline-always' => 'Uvijek',
'underline-default' => 'Po podešavanjima brauzera',
'underline-never' => 'Nikad',
'unexpected' => 'Neočekivana vrijednost: "$1"="$2".',
'unlockbtn' => 'Otključaj bazu',
'unlockconfirm' => 'Da, zaista želim da otključam bazu.',
'unlockdb' => 'Otključaj bazu',
'unlockdbsuccesssub' => 'Baza je otključana',
'unlockdbsuccesstext' => '{{SITENAME}} baza podataka je otključana.',
'unlockdbtext' => 'Otključavanje baze će svim korisnicima vratiti mogućnost
izmjene stranica, promjene korisničkih stranica, izmjene spiska praćenih članaka,
i svega ostalog što zahtjeva promjene u bazi.
Molimo Vas da potvrdite da je ovo zaista ono što namijeravate da uradite.',
'unprotect' => 'odštiti',
'unprotectcomment' => 'Razlog za skidanje zaštite',
'unprotectedarticle' => 'odštićena "$1"',
'unprotectsub' => '(Skidanje zaštite "$1")',
'unprotectthispage' => 'Odštiti ovu stranicu',
'unusedcategories' => 'Nekorišćene kategorije',
'unusedcategoriestext' => 'Sledeće strane kategorija postoje iako ih ni jedan drugi članak ili kategorija ne koriste.',
'unusedimages' => 'Neupotrebljene slike',
'unusedimagestext' => '<p>Obratite pažnju da se drugi veb sajtovi, kao što su drugi
međunarodni Vikiji, mogu povezati na sliku direktnom
URL-om, i tako mogu još uvijek biti prikazani ovdje uprkos
aktivnoj upotrebi.</p>',
'unwatch' => 'Ukinite praćenje',
'unwatchthispage' => 'Ukinite praćenje',
'updated' => '(Osvježeno)',
'upload' => 'Postavi datoteku',
'uploadbtn' => 'Postavi datoteku',
'uploaddisabled' => 'Slanje fajlova je isključeno',
'uploadedfiles' => 'Poslati fajlovi',
'uploadedimage' => 'poslato "[[$1]]"',
'uploaderror' => 'Greška pri slanju',
'uploadlog' => 'log slanja',
'uploadlogpage' => 'istorija slanja',
'uploadlogpagetext' => 'Ispod je spisak najskorijih slanja.',
'uploadnologin' => 'Niste prijavljeni',
'uploadnologintext' => 'Morate biti [[{{ns:-1}}:Userlogin|prijavljeni]]
da bi ste slali fajlove.',
'uploadvirus' => 'Fajl sadrži virus!  Detalji:  $1',
'uploadwarning' => 'Upozorenje pri slanju',
'user_rights_set' => '<b>Prava za korisnika "$1" promjenjena</b>',
'usercssjsyoucanpreview' => '<strong>Pažnja:</strong> Koristite \'Prikaži izgled\' dugme da testirate svoj novi CSS/JS prije nego što sačuvate.',
'usercsspreview' => '\'\'\'Zapamtite ovo je samo izgled vašeg CSS-a, još uvijek nije sačuvan!\'\'\'',
'userexists' => 'Korisničko ime koje ste unijeli je već u upotrebi.  Molimo Vas da izaberete drugo ime.',
'userjspreview' => '\'\'\'Zapamtite ovo je samo izgled vaše JavaScript-e, još uvijek nije sačuvan!\'\'\'',
'userlogin' => 'Prijavite se / Registrujte se',
'userlogout' => 'Odjavite se',
'usermailererror' => 'Objekat pošte je vratio grešku:',
'username' => 'Korisničko ime:',
'userpage' => 'Pogledaj korisničku stranicu',
'userstats' => 'Statistike korisnika',
'userstatstext' => 'Postoji \'\'\'$1\'\'\' registrovanih korisnika, od kojih
su \'\'\'$2\'\'\' (ili \'\'\'$4%\'\'\') administratori.',
'version' => 'Verzija',
'versionrequired' => 'Verzija $1 MedijaVikija je potrebna',
'versionrequiredtext' => 'Verzija $1 MedijaVikija je potrebna da bi se koristila ova strana. Pogledajte [[{{ns:-1}}:Version|verziju]]',
'viewcount' => 'Ovoj stranici je pristupljeno $1 puta.',
'viewdeleted' => 'Pogledaj $1?',
'viewdeletedpage' => 'Pogledaj izbrisane stranice',
'viewprevnext' => 'Pogledaj ($1) ($2) ($3).',
'views' => 'Pregledi',
'viewsource' => 'pogledaj kod',
'viewtalkpage' => 'Pogledaj raspravu',
'wantedcategories' => 'Tražene kategorije',
'wantedpages' => 'Tražene stranice',
'watch' => 'Prati',
'watchdetails' => '* $1 stranica praćeno ne računajući stranice za razgovor
* [$4 prikaži i mijenjaj potpuni spisak]',
'watcheditlist' => 'Ovdje je abecedni spisak stranica koje
pratite.  Označite stranice koje želite da uklonite sa svog spiska i kliknite na dugme \'ukloni izabrane\' na dnu ekrana.',
'watchlist' => 'Praćeni članci',
'watchlistcontains' => 'Vaš spisak praćenih članaka sadrži $1 stranica.',
'watchmethod-list' => 'provjerava se da li ima nedavnih izmjena u praćenim stranicama',
'watchmethod-recent' => 'provjerava se da li ima praćenih stranica u nedavnim izmjenama',
'watchnochange' => 'Ništa što pratite nije promjenjeno u prikazanom vremenu.',
'watchnologin' => 'Niste prijavljeni',
'watchnologintext' => 'Morate biti [[{{ns:-1}}:Userlogin|prijavljeni]] da bi ste mijenjali spisak praćenih članaka.',
'watchthis' => 'Prati ovaj članak',
'watchthispage' => 'Prati ovu stranicu',
'wednesday' => 'srijeda',
'welcomecreation' => '<h2>Dobro došli, $1!</h2><p>Vaš nalog je napravljen.
Ne zaboravite da prilagodite sebi svoja podešavanja.',
'whatlinkshere' => 'Šta je povezano ovdje',
'whitelistacctext' => 'Da bi vam bilo dozvoljeno da napravite naloge na ovom Vikiju, morate da se [[{{ns:-1}}:Userlogin|prijavite]] i imate odgovarajuća ovlašćenja.',
'whitelistacctitle' => 'Nije vam dozvoljeno da napravite nalog',
'whitelistedittext' => 'Morate da se [[{{ns:-1}}:Userlogin|prijavite]] da bi ste uređivali stranice.',
'whitelistedittitle' => 'Obavezno je prijavljivanje za uređivanje',
'whitelistreadtext' => 'Morate da se [[{{ns:-1}}:Userlogin|prijavite]] da bi ste čitali članke.',
'whitelistreadtitle' => 'Obavezno je prijavljivanje za čitanje',
'projectpage' => 'Pogledaj stranu o ovoj strani',
'wlheader-enotif' => '* Obavještavanje e-poštom je omogućeno.',
'wlheader-showupdated' => '* Stranice koje su izmjenjene od kad ste ih poslednji put posjetili su prikazane \'\'\'podebljanim slovima\'\'\'',
'wlhideshowbots' => '$1 izmjena botova.',
'wlhideshowown' => '$1 moje izmjene.',
'wlnote' => 'Ispod je najskorijih $1 izmjena, načinjenih u posljednjih <b>$2</b> sati.',
'wlsaved' => 'Ovo je sačuvana verzija vašeg spiska praćenih članaka.',
'wlshowlast' => 'Prikaži poslednjih $1 sati $2 dana $3',
'wrong_wfQuery_params' => 'Netačni parametri za wfQuery()<br />
Funkcija: $1<br />
Pretraga: $2',
'wrongpassword' => 'Unijeli ste neispravnu lozinku.  Molimo Vas da pokušate ponovo.',
'wrongpasswordempty' => 'Lozinka je bila prazna.  Molimo Vas da pokušate ponovo.',
'youhavenewmessages' => 'Imate $1 ($2).',
'yourdiff' => 'Razlike',
'yourdomainname' => 'Vaš domen',
'youremail' => 'E-pošta *',
'yourname' => 'Korisničko ime',
'yournick' => 'Nadimak (za potpise):',
'yourpassword' => 'Lozinka',
'yourpasswordagain' => 'Ponovite lozinku',
'yourrealname' => 'Vaše pravo ime *',
'yourtext' => 'Vaš tekst',
);


?>
