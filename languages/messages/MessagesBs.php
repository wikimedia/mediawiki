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

$skinNames = array(
	'Obična', 'Nostalgija', 'Kelnsko plavo'
);

$magicWords = array(
	# ID                              CASE SYNONYMS
	'redirect'               => array( 0, '#Preusmjeri', '#redirect', '#preusmjeri', '#PREUSMJERI' ),
	'notoc'                  => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	'toc'                    => array( 0, '__TOC__', '__SADRŽAJ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
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
	'sitename'               => array( 1, 'SITENAME', 'IMESAJTA' ),
	'ns'                     => array( 0, 'NS:', 'IP:' ),
	'localurl'               => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
	'localurle'              => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
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
);

$fallback8bitEncoding = "iso-8859-2";
$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zćčžšđž]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'            => 'Podvuci veze:',
'tog-highlightbroken'      => 'Formatiraj pokvarene veze <a href="" class="new">ovako</a> (alternativa: ovako<a href="" class="internal">?</a>).',
'tog-justify'              => 'Uravnjaj pasuse',
'tog-hideminor'            => 'Sakrij male izmjene u spisku nedavnih izmjena',
'tog-usenewrc'             => 'Poboljšan spisak nedavnih izmjena (JavaScript)',
'tog-numberheadings'       => 'Automatski numeriši podnaslove',
'tog-showtoolbar'          => 'Prikaži dugmiće za izmjene (JavaScript)',
'tog-showtoc'              => 'Prikaži sadržaj<br />(u svim stranicama sa više od tri podnaslova)',
'tog-rememberpassword'     => 'Zapamti lozinku kroz više seansi',
'tog-watchcreations'       => 'Dodaj stranice koje ja napravim u moj spisak praćenih članaka',
'tog-watchdefault'         => 'Dodaj stranice koje uređujem u moj spisak praćenih članaka',
'tog-minordefault'         => 'Označi sve izmjene malim isprva',
'tog-previewontop'         => 'Prikaži pretpregled prije polja za izmjenu a ne posle',
'tog-previewonfirst'       => 'Prikaži izgled pri prvoj izmjeni',
'tog-nocache'              => 'Onemogući keširanje stranica',
'tog-enotifwatchlistpages' => 'Pošalji mi e-poštu kad se promijene stranice',
'tog-enotifusertalkpages'  => 'Pošalji mi e-poštu kad se promijeni moja korisnička stranica za razgovor',
'tog-enotifminoredits'     => 'Pošalji mi e-poštu takođe za male izmjene stranica',
'tog-enotifrevealaddr'     => 'Otkrij adresu moje e-pošte u porukama obaviještenja',
'tog-shownumberswatching'  => 'Prikaži broj korisnika koji prate',
'tog-showjumplinks'        => 'Omogući "skoči na" poveznice',

'underline-always'  => 'Uvijek',
'underline-never'   => 'Nikad',
'underline-default' => 'Po podešavanjima brauzera',

'skinpreview' => '(Pregled)',

# Dates
'sunday'    => 'nedelja',
'monday'    => 'ponedeljak',
'tuesday'   => 'utorak',
'wednesday' => 'srijeda',
'thursday'  => 'četvrtak',
'friday'    => 'petak',
'saturday'  => 'subota',
'january'   => 'januar',
'february'  => 'februar',
'march'     => 'mart',
'april'     => 'april',
'june'      => 'jun',
'july'      => 'jul',
'august'    => 'avgust',
'september' => 'septembar',
'october'   => 'oktobar',
'november'  => 'novembar',
'december'  => 'decembar',
'jan'       => 'jan',
'feb'       => 'feb',
'mar'       => 'mar',
'apr'       => 'apr',
'may'       => 'maj',
'jun'       => 'jun',
'jul'       => 'jul',
'aug'       => 'avg',
'sep'       => 'sep',
'oct'       => 'okt',
'nov'       => 'nov',
'dec'       => 'dec',

# Bits of text used by many pages
'categories'      => 'Kategorije',
'pagecategories'  => 'Kategorije',
'category_header' => 'Članaka u kategoriji "$1"',
'subcategories'   => 'Potkategorije',

'mainpagetext' => 'Viki softver is uspješno instaliran.',

'about'          => 'O...',
'article'        => 'Članak',
'cancel'         => 'Poništite',
'qbfind'         => 'Pronađite',
'qbbrowse'       => 'Prelistajte',
'qbedit'         => 'Izmjenite',
'qbpageoptions'  => 'Opcije stranice',
'qbpageinfo'     => 'Informacije o stranici',
'qbmyoptions'    => 'Moje opcije',
'qbspecialpages' => 'Posebne stranice',
'moredotdotdot'  => 'Još...',
'mypage'         => 'Moja stranica',
'mytalk'         => 'Moj razgovor',
'anontalk'       => 'Razgovor za ovu IP adresu',
'navigation'     => 'Navigacija',

'errorpagetitle'    => 'Greška',
'returnto'          => 'Povratak na $1.',
'help'              => 'Pomoć',
'search'            => 'Pretraži',
'searchbutton'      => 'Pretraži',
'go'                => 'Idi',
'searcharticle'     => 'Idi',
'history'           => 'Istorija stranice',
'history_short'     => 'Istorija',
'printableversion'  => 'Verzija sa štampanje',
'permalink'         => 'Trajna veza',
'print'             => 'Štampa',
'edit'              => 'Uredite',
'editthispage'      => 'Uredite ovu stranicu',
'delete'            => 'Obrišite',
'deletethispage'    => 'Obriši ovu stranicu',
'undelete_short'    => 'Vrati $1 obrisanih izmjena',
'protect'           => 'Zaštitite',
'protectthispage'   => 'Zaštitite ovu stranicu',
'unprotect'         => 'Odštiti',
'unprotectthispage' => 'Odštiti ovu stranicu',
'newpage'           => 'Nova stranica',
'talkpage'          => 'Razgovor o stranici',
'specialpage'       => 'Posebna Stranica',
'personaltools'     => 'Lični alati',
'postcomment'       => 'Pošaljite komentar',
'articlepage'       => 'Pogledaj članak',
'talk'              => 'Razgovor',
'views'             => 'Pregledi',
'toolbox'           => 'Posebne funkcije',
'userpage'          => 'Pogledaj korisničku stranicu',
'projectpage'       => 'Pogledaj stranu o ovoj strani',
'imagepage'         => 'Pogjedajte stranicu slike',
'viewtalkpage'      => 'Pogledaj raspravu',
'otherlanguages'    => 'Ostali jezici',
'redirectedfrom'    => '(Preusmjereno sa $1)',
'lastmodifiedat'    => 'Ova stranica je poslednji put izmijenjena $2, $1', # $1 date, $2 time
'viewcount'         => 'Ovoj stranici je pristupljeno $1 puta.',
'protectedpage'     => 'Zaštićena stranica',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'O projektu {{SITENAME}}',
'aboutpage'         => 'Project:O',
'bugreports'        => 'Prijavite grešku',
'bugreportspage'    => 'Project:Prijave_grešaka',
'copyright'         => 'Svi sadržaji podliježu "$1" licenci.',
'copyrightpagename' => '{{SITENAME}} autorska prava',
'copyrightpage'     => '{{ns:project}}:Autorska_prava',
'currentevents'     => 'Trenutni događaji',
'disclaimers'       => 'Odricanje odgovornosti',
'disclaimerpage'    => 'Project:Uslovi korišćenja, pravne napomene i odricanje odgovornosti',
'edithelp'          => 'Pomoć pri uređivanju stranice',
'edithelppage'      => 'Help:Uređivanje',
'faqpage'           => 'Project:NPP',
'helppage'          => 'Help:Sadržaj',
'mainpage'          => 'Glavna stranica',
'portal'            => 'Portal zajednice',
'portal-url'        => 'Project:Portal_zajednice',
'sitesupport'       => 'Donacije',
'sitesupport-url'   => 'Project:Donacije',

'versionrequired'     => 'Verzija $1 MedijaVikija je potrebna',
'versionrequiredtext' => 'Verzija $1 MedijaVikija je potrebna da bi se koristila ova strana. Pogledajte [[Special:Version|verziju]]',

'ok'                 => 'da',
'pagetitle'          => '$1 - {{SITENAME}}',
'retrievedfrom'      => 'Dobavljeno iz "$1"',
'youhavenewmessages' => 'Imate $1 ($2).',
'newmessageslink'    => 'novih poruka',
'editsection'        => 'uredite',
'editold'            => 'uredite',
'toc'                => 'Sadržaj',
'showtoc'            => 'prikaži',
'hidetoc'            => 'sakrij',
'thisisdeleted'      => 'Pogledaj ili vrati $1?',
'viewdeleted'        => 'Pogledaj $1?',
'restorelink'        => '$1 izbrisanih izmjena',
'feedlinks'          => 'Fid:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Članak',
'nstab-user'      => 'Korisnička stranica',
'nstab-media'     => 'Medija',
'nstab-special'   => 'Posebna',
'nstab-project'   => 'Članak',
'nstab-image'     => 'Slika',
'nstab-mediawiki' => 'Poruka',
'nstab-template'  => 'Šablon',
'nstab-help'      => 'Pomoć',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Nema takve akcije',
'nosuchactiontext'  => 'Akcija navedena u URL-u nije
prepoznata od strane {{SITENAME}} softvera.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nospecialpagetext' => 'Tražili ste posebnu stranicu, koju {{SITENAME}} softver nije prepoznao.',

# General errors
'error'                => 'Greška',
'databaseerror'        => 'Greška u bazi',
'dberrortext'          => 'Desila se sintaksna greška upita baze.
Ovo je moguće zbog ilegalnog upita, ili moguće greške u softveru.
Poslednji pokušani upit je bio: <blockquote><tt>$1</tt></blockquote>
iz funkcije "<tt>$2</tt>".
MySQL je vratio grešku "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Desila se sintaksna greška upita baze.
Poslednji pokušani upit je bio:
"$1"
iz funkcije "$2".
MySQL je vratio grešku "$3: $4".',
'noconnect'            => 'Žao nam je! Viki ima neke tehničke poteškoće, i ne može da se poveže sa serverom baze. <br />',
'nodb'                 => 'Ne mogu da izaberem bazu $1',
'cachederror'          => 'Ovo je keširana kopija zahtjevane stranice, i možda nije najnovija.',
'readonly'             => 'Baza je zaključana',
'enterlockreason'      => 'Unesite razlog za zaključavanje, uključujući procijenu
vremena otključavanja',
'readonlytext'         => 'Baza je trenutno zaključana za nove unose i ostale izmjene, vjerovatno zbog rutinskog održavanja, posle čega će biti vraćena u uobičajeno stanje.

Administrator koji ju je zaključao je ponudio ovo objašnjenje: $1',
'missingarticle'       => 'Baza nije mogla naći tekst stranice koji je trebala da nađe, nazvan "$1".

Ovo je obično izazvano praćenjem zastarijelog "razl" ili veze ka istoriji
stranice koja je obrisana.

Ako ovo nije slučaj, možda ste pronašli grešku u softveru.
Molimo Vas da prijaviti ovo jednom od [[{{MediaWiki:Grouppage-sysop}}|administratora]], zajedno sa URL-om.',
'internalerror'        => 'Interna greška',
'filecopyerror'        => 'Ne može se kopirati "$1" na "$2".',
'filerenameerror'      => 'Ne može se promjeniti ime fajla "$1" to "$2".',
'filedeleteerror'      => 'Ne može se izbrisati fajl "$1".',
'filenotfound'         => 'Ne može se naći fajl "$1".',
'unexpected'           => 'Neočekivana vrijednost: "$1"="$2".',
'formerror'            => 'Greška:  ne može se poslati upitnik',
'badarticleerror'      => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'cannotdelete'         => 'Ne može se obrisati navedena stranica ili slika.  (Moguće je da ju je neko drugi već obrisao.)',
'badtitle'             => 'Loš naslov',
'badtitletext'         => 'Zahtjevani naslov stranice je bio neispravan, prazan ili neispravno povezan međujezički ili interviki naslov.',
'perfdisabled'         => 'Žao nam je!  Ova mogućnost je privremeno onemogućena jer usporava bazu do te mjere da više niko ne može da koristi viki.',
'perfcached'           => 'Sledeći podaci su keširani i možda neće biti u potpunosti ažurirani:',
'wrong_wfQuery_params' => 'Netačni parametri za wfQuery()<br />
Funkcija: $1<br />
Pretraga: $2',
'viewsource'           => 'pogledaj kod',
'sqlhidden'            => '(SQL pretraga sakrivena)',

# Login and logout pages
'logouttitle'           => 'Odjavite se',
'logouttext'            => '<strong>Sad ste odjavljeni.</strong><br />
Možete nastaviti da koristite {{SITENAME}} anonimno, ili se ponovo prijaviti
kao isti ili kao drugi korisnik.  Obratite pažnju da neke stranice mogu nastaviti da se prikazuju kao da ste još uvijek prijavljeni, dok ne očistite keš svog brauzera.',
'welcomecreation'       => '<h2>Dobro došli, $1!</h2><p>Vaš nalog je napravljen.
Ne zaboravite da prilagodite sebi svoja podešavanja.',
'loginpagetitle'        => 'Prijavljivanje',
'yourname'              => 'Korisničko ime',
'yourpassword'          => 'Lozinka',
'yourpasswordagain'     => 'Ponovite lozinku',
'remembermypassword'    => 'Zapamti me',
'yourdomainname'        => 'Vaš domen',
'loginproblem'          => '<b>Bilo je problema sa vašim prijavljivanjem.</b><br />Probajte ponovo!',
'login'                 => 'Prijavi se',
'loginprompt'           => "Morate imati kolačiće ('''cookies''') omogućene da biste se prijavili na {{SITENAME}}.",
'userlogin'             => 'Prijavite se / Registrujte se',
'logout'                => 'Odjavite se',
'userlogout'            => 'Odjavite se',
'notloggedin'           => 'Niste prijavljeni',
'nologinlink'           => 'Napravite nalog',
'createaccount'         => 'Napravi nalog',
'gotaccount'            => 'Imate nalog? $1.',
'gotaccountlink'        => 'Prijavi se',
'createaccountmail'     => 'e-poštom',
'badretype'             => 'Lozinke koje ste unijeli se ne poklapaju.',
'userexists'            => 'Korisničko ime koje ste unijeli je već u upotrebi.  Molimo Vas da izaberete drugo ime.',
'youremail'             => 'E-pošta *',
'username'              => 'Korisničko ime:',
'uid'                   => 'Korisnički ID:',
'yourrealname'          => 'Vaše pravo ime *',
'yournick'              => 'Nadimak (za potpise):',
'loginerror'            => 'Greška pri prijavljivanju',
'prefs-help-email'      => '* E-mail (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
'nocookiesnew'          => "Korisnički nalog je napravljen, ali niste prijavljeni.  {{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili.  Vi ste onemogućili kolačiće na Vašem kompjuteru.  molimo Vas da ih omogućite, a onda se prijavite sa svojim novim korisničkim imenom i lozinkom.",
'nocookieslogin'        => "{{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili.  Vi ste onemogućili kolačiće na Vašem kompjuteru.  Molimo Vas da ih omogućite i da pokušate ponovo sa prijavom.",
'noname'                => 'Niste izabrali ispravno korisničko ime.',
'loginsuccesstitle'     => 'Prijavljivanje uspješno',
'loginsuccess'          => "'''Sad ste prijavljeni na {{SITENAME}} kao \"\$1\".'''",
'nosuchuser'            => 'Ne postoji korisnik sa imenom "$1". Provjerite Vaše kucanje, ili upotrebite donji upitnik da napravite novi korisnički nalog.',
'wrongpassword'         => 'Unijeli ste neispravnu lozinku.  Molimo Vas da pokušate ponovo.',
'wrongpasswordempty'    => 'Lozinka je bila prazna.  Molimo Vas da pokušate ponovo.',
'mailmypassword'        => 'Pošalji mi moju lozinku',
'passwordremindertitle' => '{{SITENAME}} podsjetnik za lozinku',
'passwordremindertext'  => 'Neko (vjerovatno Vi, sa IP adrese $1)
je zahtjevao da vam pošaljemo novu {{SITENAME}} lozinku za prijavljivanje na {{SERVERNAME}}.
Lozinka za korisnika "$2" je sad "$3".
Sad treba da se prijavite i promjenite lozinku.

Ako je neko drugi napravio ovaj zahtjev ili ako ste se sjetili vaše lozinke i
ne želite više da je promjenite, možete da ignorišete ovu poruku i da nastavite koristeći
vašu staru lozinku.',
'noemail'               => 'Ne postoji adresa e-pošte za korisnika "$1".',
'passwordsent'          => 'Nova lozinka je poslata na adresu e-pošte
korisnika "$1".
Molimo Vas da se prijavite pošto je primite.',
'mailerror'             => 'Greška pri slanju e-pošte: $1',

# Edit page toolbar
'bold_sample'     => 'Podebljan tekst',
'bold_tip'        => 'Podebljan tekst',
'italic_sample'   => 'Kurzivan tekst',
'italic_tip'      => 'Kurzivan tekst',
'link_sample'     => 'Naslov poveznice',
'link_tip'        => 'Unutrašnja poveznica',
'extlink_sample'  => 'http://www.adresa.com opis adrese',
'extlink_tip'     => 'Spoljašnja poveznica (zapamti prefiks http://)',
'headline_sample' => 'Naslov',
'headline_tip'    => 'Podnaslov',
'math_sample'     => 'Unesite formulu ovdje',
'math_tip'        => 'Matematička formula (LaTeX)',
'nowiki_sample'   => 'Dodaj neformatirani tekst ovdje',
'nowiki_tip'      => 'Ignoriši viki formatiranje teksta',
'image_sample'    => 'ime_slike.jpg',
'image_tip'       => 'Uklopljena slika',
'media_sample'    => 'ime_medija_fajla.ogg',
'media_tip'       => 'Putanja ka multimedijalnom fajlu',
'sig_tip'         => 'Vaš potpis sa trenutnim vremenom',
'hr_tip'          => 'Horizontalna linija (koristite oskudno)',

# Edit pages
'summary'                  => 'Sažetak',
'subject'                  => 'Tema/naslov',
'minoredit'                => 'Ovo je mala izmjena',
'watchthis'                => 'Prati ovaj članak',
'savearticle'              => 'Sačuvaj',
'preview'                  => 'Pregled stranice',
'showpreview'              => 'Prikaži izgled',
'showdiff'                 => 'Prikaži izmjene',
'anoneditwarning'          => 'Niste prijavljeni. Vaša IP adresa će biti zapisana.',
'blockedtitle'             => 'Korisnik je blokiran',
'blockedtext'              => "Vaše korisničko ime ili IP adresa je blokirana od strane $1.
Dati razlog je sledeći:<br />''$2''<p>Možete kontaktirati $1 ili nekog drugog [[{{MediaWiki:Grouppage-sysop}}|administratora]] da biste razgovarili o blokadi.",
'whitelistedittitle'       => 'Obavezno je prijavljivanje za uređivanje',
'whitelistedittext'        => 'Morate da se [[Special:Userlogin|prijavite]] da bi ste uređivali stranice.',
'whitelistreadtitle'       => 'Obavezno je prijavljivanje za čitanje',
'whitelistreadtext'        => 'Morate da se [[Special:Userlogin|prijavite]] da bi ste čitali članke.',
'whitelistacctitle'        => 'Nije vam dozvoljeno da napravite nalog',
'whitelistacctext'         => 'Da bi vam bilo dozvoljeno da napravite naloge na ovom Vikiju, morate da se [[Special:Userlogin|prijavite]] i imate odgovarajuća ovlašćenja.',
'loginreqtitle'            => 'Potrebno je [[Special:Userlogin|prijavljivanje]]',
'accmailtitle'             => 'Lozinka poslata.',
'accmailtext'              => "Lozinka za nalog '$1' je poslata na adresu $2.",
'newarticle'               => '(Novi)',
/*'newarticletext'           => "<div style=\"border: 1px solid #ccc; padding: 7px;\">'''{{SITENAME}} nema stranicu {{PAGENAME}}.'''
* Da započnete stranicu, koristite prostor ispod i kad završite, pritisnite \"Sačuvaj\".  Vaše izmjene će odmah biti vidljive.
* Ako ste novi na prjektu {{SITENAME}}, molimo Vas da pogledate [[{{MediaWiki:Helppage}}|pomoćnu stranicu]], ili koristite [[Project:Igralište|igralište]] za eksperimentaciju.
</div>",*/
'anontalkpagetext'         => "----''Ovo je stranica za razgovor za anonimnog korisnika koji još nije napravio nalog ili ga ne koristi.  Zbog toga moramo da koristimo brojčanu IP adresu kako bismo odentifikovali njega ili nju.  Takvu adresu može dijeliti više korisnika.  Ako ste anonimni korisnik i mislite da su vam upućene nebitne primjedbe, molimo Vas da [[Special:Userlogin|napravite nalog ili se prijavite]] da biste izbjegli buduću zabunu sa ostalim anonimnim korisnicima.''",
'noarticletext'            => "<div style=\"border: 1px solid #ccc; padding: 7px;\">'''{{SITENAME}} još nema ovaj članak.'''
* Da započnete članak, kliknite '''[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} uredite ovu stranicu]'''.
* [[Special:Search/{{PAGENAME}}|Pretraži {{PAGENAME}}]] u ostalim člancima
* [[Special:Whatlinkshere/{{NAMESPACE}}:{{PAGENAME}}|Stranice koje su povezane za]] {{PAGENAME}} članak
----
* '''Ukoliko ste napravili ovaj članak u poslednjih nekoliko minuta i još se nije pojavio, postoji mogućnost da je server u zastoju zbog osvježavanja baze podataka.''' Molimo Vas da probate sa <span class=\"plainlinks\">[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=purge}} osvježavanjem]<span> ili sačekajte i provjerite kasnije ponovo prije ponovnog pravljenja članka.
* Ako ste napravili članak pod ovim imenom ranije, moguće je da je bio izbrisan.  Potražite '''{{FULLPAGENAME}}''' [{{fullurl:Special:Log|type=delete&page={{FULLPAGENAMEE}}}} u spisku brisanja].
</div>",
'usercssjsyoucanpreview'   => "<strong>Pažnja:</strong> Koristite 'Prikaži izgled' dugme da testirate svoj novi CSS/JS prije nego što sačuvate.",
'usercsspreview'           => "'''Zapamtite ovo je samo izgled vašeg CSS-a, još uvijek nije sačuvan!'''",
'userjspreview'            => "'''Zapamtite ovo je samo izgled vaše JavaScript-e, još uvijek nije sačuvan!'''",
'updated'                  => '(Osvježeno)',
'note'                     => '<strong>Pažnja:</strong>',
'previewnote'              => '<strong>Ovo je samo pregled; izmjene stranice nisu još sačuvane!</strong>',
'previewconflict'          => 'Ovaj pregled reflektuje tekst u gornjem polju
kako će izgledati ako pritisnete "Sačuvaj članak".',
'editing'                  => 'Uređujete $1',
'editinguser'              => 'Uređujete $1',
'editconflict'             => 'Sukobljenje izmjene: $1',
'explainconflict'          => 'Neko drugi je promjenio ovu stranicu otkad ste Vi počeli da je mjenjate.
Gornje tekstualno polje sadrži tekst stranice koji trenutno postoji.
Vaše izmjene su prikazane u donjem tekstu.
Moraćete da unesete svoje promjene u postojeći tekst.
<b>Samo</b> tekst u gornjem tekstualnom polju će biti snimljen kad
pritisnete "Sačuvaj".<br />',
'yourtext'                 => 'Vaš tekst',
'storedversion'            => 'Uskladištena verzija',
'editingold'               => '<strong>PAŽNJA:  Vi mijenjate stariju
reviziju ove stranice.
Ako je snimite, sve promjene učinjene od ove revizije će biti izgubljene.</strong>',
'yourdiff'                 => 'Razlike',
'copyrightwarning'         => 'Za sve priloge poslate na projekat {{SITENAME}} smatramo da su objavljeni pod $2 (konsultujte $1 za detalje).
Ukoliko ne želite da vaši članci budu podložni izmjenama i slobodnom rasturanju i objavljivanju, 
nemojte ih slati ovdje. Takođe, slanje članka podrazumijeva i vašu izjavu da ste ga napisali sami, ili da ste ga kopirali iz izvora u javnom domenu ili sličnog slobodnog izvora.

<strong>NEMOJTE SLATI RAD ZAŠTIĆEN AUTORSKIM PRAVIMA BEZ DOZVOLE AUTORA!</strong>',
'longpagewarning'          => '<strong>PAŽNJA:  Ova stranica ima $1 kilobajta; niki
brauzeri mogu imati problema kad uređujete stranice skoro ili veće od 32 kilobajta.
Molimo Vas da razmotrite razbijanje stranice na manje dijelove.</strong>',
'readonlywarning'          => '<strong>PAŽNJA:  Baza je zaključana zbog održavanja,
tako da nećete moći da sačuvate svoje izmjene za sada.  Možda želite da kopirate
i nalijepite tekst u tekst editor i sačuvate ga za kasnije.</strong>',
'protectedpagewarning'     => '<strong>PAŽNJA: Ova stranica je zaključana tako da samo korisnici sa
administratorkim privilegijama mogu da je mijenjaju. Uvjerite se da pratite [[Special:Protectedpages|pravila o zaštiti stranica]]</strong>.',
'semiprotectedpagewarning' => "'''Pažnja:''' Ova stranica je zaključana tako da je samo registrovani korisnici mogu uređivati.",
'templatesused'            => 'Šabloni koji su upotrebljeni na ovoj stranici:',

# History pages
'revhistory'      => 'Istorija izmjena',
'nohistory'       => 'Ne postoji istorija izmjena za ovu stranicu.',
'revnotfound'     => 'Revizija nije pronađena',
'revnotfoundtext' => 'Starija revizija ove stranice koju ste zatražili nije nađena.
Molimo Vas da provjerite URL pomoću kojeg ste pristupili ovoj stranici.',
'loadhist'        => 'Učitaje se istorija stranice',
'currentrev'      => 'Trenutna revizija',
'revisionasof'    => 'Revizija od $1',
'cur'             => 'tren',
'next'            => 'sled',
'last'            => 'posl',
'orig'            => 'orig',
'histlegend'      => 'Objašnjenje: (tren) = razlika sa trenutnom verziom,
(posl) = razlika sa prethodnom verziom, M = mala izmjena',

# Diffs
'difference'                => '(Razlika između revizija)',
'loadingrev'                => 'učitava se revizija za razliku',
'lineno'                    => 'Linija $1:',
'editcurrent'               => 'Izmijenite trenutnu verziju ove stranice',
'selectnewerversionfordiff' => 'Izaberite noviju verziju za upoređivanje',
'selectolderversionfordiff' => 'Izaberite stariju verziju za upoređivanje',
'compareselectedversions'   => 'Uporedite označene verzije',

# Search results
'searchresults'         => 'Rezultati pretrage',
'searchresulttext'      => 'Za više informacija o pretraživanju {{SITENAME}}, pogledajte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Tražili ste [[:$1]] [[Special:Allpages/$1|&#x5B;Sadržaj&#x5D;]]',
'searchsubtitleinvalid' => 'Tražili ste $1',
'badquery'              => 'Loše oblikovan upit za pretragu',
'badquerytext'          => 'Nismo mogli da obradimo vaš upit.  Ovo je vjerovatno zbog toga što ste pokušali da tražite riječ kraću od tri slova, što trenutno nije podržano.  Takođe je moguće da ste pogrešno ukucali izraz, na primjer "riba ii krljušti".  Molimo vas da pokušate nekim drugim upitom.',
'matchtotals'           => 'Upit "$1" je nađen u "$2" naslova članaka
i tekst $3 članaka.',
'noexactmatch'          => "Nema stranice sa takvim imenom.

Možete '''[[:$1|da napravite članak sa tim naslovom]]''' ili [[{{MediaWiki:Helppage}}|da stavite zahtjev za ovaj članak]] ili [[Special:Allpages/$1|potražite na drugim stranicama]].

::*'''''<u>Opomena: Nemojte da kopirate materijale za koje nemate dozvolu!</u>'''''",
'titlematches'          => 'Naslov članka odgovara',
'notitlematches'        => 'Naslov članka ne odgovara.',
'textmatches'           => 'Tekst stranice odgovara',
'notextmatches'         => 'Tekst članka ne odgovara',
'prevn'                 => 'prethodnih $1',
'nextn'                 => 'sledećih $1',
'viewprevnext'          => 'Pogledaj ($1) ($2) ($3).',
'showingresults'        => 'Prikazani su <b>$1</b> rezultata počev od <b>$2</b>.',
'showingresultsnum'     => 'Prikazani su <b>$3</b> rezultati počev od <b>$2</b>.',
'nonefound'             => "'''Pažnja''': neuspješne pretrage su
često izazvane traženjem čestih riječi kao \"je\" ili \"od\",
koje nisu indeksirane, ili navođenjem više od jednog izraza za traženje (samo stranice
koje sadrže sve izraze koji se traže će se pojaviti u rezultatima).",
'powersearch'           => 'Traži',
'powersearchtext'       => 'Pretraga u imenskim prostorima :<br />
$1<br />
$2 Izlistajte preusmjerenja &nbsp; Tražite $3 $9',
'searchdisabled'        => '<p>Izvinjavamo se!  Puno pretraga teksta je privremeno onemogućena.  U međuvremenu, možete koristiti Google za pretragu.  Indeks može biti stariji.',
'blanknamespace'        => '(Glavno)',

# Preferences page
'preferences'             => 'Podešavanja',
'prefsnologin'            => 'Niste prijavljeni',
'prefsnologintext'        => 'Morate biti [[Special:Userlogin|prijavljeni]] da biste podešavali korisnička podešavanja.',
'prefsreset'              => 'Podešavanja su vraćena na prvotne vrijednosti.',
'qbsettings'              => 'Podešavanja brze palete',
'qbsettings-none'         => 'Nikakva',
'qbsettings-fixedleft'    => 'Pričvršćena lijevo',
'qbsettings-fixedright'   => 'Pričvršćena desno',
'qbsettings-floatingleft' => 'Plutajuća lijevo',
'changepassword'          => 'Promjeni lozinku',
'skin'                    => 'Koža',
'math'                    => 'Prikazivanje matematike',
'dateformat'              => 'Format datuma',
'datedefault'             => 'Nije bitno',
'math_failure'            => 'Neuspjeh pri parsiranju',
'math_unknown_error'      => 'nepoznata greška',
'math_unknown_function'   => 'nepoznata funkcija',
'math_lexing_error'       => 'riječnička greška',
'math_syntax_error'       => 'sintaksna greška',
'math_image_error'        => 'PNG konverzija neuspješna; provjerite tačnu instalaciju latex-a, dvips-a, gs-a i convert-a',
'math_bad_tmpdir'         => 'Ne može se napisati ili napraviti privremeni matematični direktorijum',
'math_bad_output'         => 'Ne može se napisati ili napraviti direktorijum za matematični izvještaj.',
'math_notexvc'            => 'Nedostaje izvršno texvc; molimo Vas da pogledate math/README da podesite.',
'prefs-personal'          => 'Korisnički podaci',
'prefs-rc'                => 'Podešavanja nedavnih izmjena',
'prefs-misc'              => 'Ostala podešavanja',
'saveprefs'               => 'Sačuvajte podešavanja',
'resetprefs'              => 'Vrati podešavanja',
'oldpassword'             => 'Stara lozinka:',
'newpassword'             => 'Nova lozinka:',
'retypenew'               => 'Ukucajte ponovo novu lozinku:',
'textboxsize'             => 'Veličine tekstualnog polja',
'rows'                    => 'Redova',
'columns'                 => 'Kolona',
'searchresultshead'       => 'Podešavanja rezultata pretrage',
'resultsperpage'          => 'Pogodaka po stranici:',
'contextlines'            => 'Linija po pogotku:',
'contextchars'            => 'Karaktera konteksta po liniji:',
'recentchangescount'      => 'Broj naslova u nedavnim izmjenama:',
'savedprefs'              => 'Vaša podešavanja su sačuvana.',
'timezonelegend'          => 'Vremenska zona',
'timezonetext'            => 'Unesite broj sati za koji se Vaše lokalno vrijeme razlikuje od serverskog vremena (UTC).',
'localtime'               => 'Lokalno vrijeme',
'timezoneoffset'          => 'Odstupanje',
'servertime'              => 'Vrijeme na serveru',
'guesstimezone'           => 'Popuni iz brauzera',
'defaultns'               => 'Uobičajeno tražite u ovim imenskim prostorima:',

# Recent changes
'recentchanges'     => 'Nedavne izmjene',
'recentchangestext' => 'Na ovoj stranici možete pratiti nedavne izmjene.',
'rcnote'            => 'Ispod je najskorijih <strong>$1</strong> izmjena u poslednjih <strong>$2</strong> dana.',
'rcnotefrom'        => 'Ispod su izmjene od <b>$2</b> (do <b>$1</b> prikazano).',
'rclistfrom'        => 'Prikaži nove izmjene počev od $1',
'rcshowhideminor'   => '$1 male izmjene',
'rcshowhidebots'    => '$1 botove',
'rcshowhideliu'     => '$1 prijavljene korisnike',
'rcshowhideanons'   => '$1 anonimne korisnike',
'rcshowhidepatr'    => '$1 patrolirane izmjene',
'rcshowhidemine'    => '$1 moje izmjene',
'rclinks'           => 'Prikaži najskorijih $1 izmjena u poslednjih $2 dana; $3',
'diff'              => 'razl',
'hist'              => 'ist',
'hide'              => 'sakrij',
'show'              => 'pokaži',

# Recent changes linked
'recentchangeslinked' => 'Srodne izmjene',

# Upload
'upload'                      => 'Postavi datoteku',
'uploadbtn'                   => 'Postavi datoteku',
'reupload'                    => 'Ponovo pošaljite',
'reuploaddesc'                => 'Vratite se na upitnik za slanje.',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Morate biti [[Special:Userlogin|prijavljeni]]
da bi ste slali fajlove.',
'uploaderror'                 => 'Greška pri slanju',
'uploadlog'                   => 'log slanja',
'uploadlogpage'               => 'istorija slanja',
'uploadlogpagetext'           => 'Ispod je spisak najskorijih slanja.',
'filename'                    => 'Ime fajla',
'filedesc'                    => 'Opis',
'filestatus'                  => 'Status autorskih prava',
'filesource'                  => 'Izvor',
'uploadedfiles'               => 'Poslati fajlovi',
'badfilename'                 => 'Ime slike je promjenjeno u "$1".',
'emptyfile'                   => 'Fajl koji ste poslali je prazan. Ovo je moguće zbog greške u imenu fajla. Molimo Vas da provjerite da li stvarno želite da pošaljete ovaj fajl.',
'fileexists'                  => 'Fajl sa ovim imenom već postoji.  Molimo Vas da provjerite $1 ako niste sigurni da li želite da ga promjenite.',
'fileexists-forbidden'        => 'Fajl sa ovim imenom već postoji; molimo Vas da se vratite i pošaljete ovaj fajl pod novim imenom. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fajl sa ovim imenom već postoji u zajedničkoj ostavi; molimo Vas da se vratite i pošaljete ovaj fajl pod novim imenom. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Uspješno slanje',
'uploadwarning'               => 'Upozorenje pri slanju',
'savefile'                    => 'Sačuvaj fajl',
'uploadedimage'               => 'poslato "[[$1]]"',
'uploaddisabled'              => 'Slanje fajlova je isključeno',
'uploadvirus'                 => 'Fajl sadrži virus!  Detalji:  $1',

# Image list
'imagelist'      => 'Spisak slika',
'imagelisttext'  => 'Ispod je spisak $1 slika poređanih $2.',
'getimagelist'   => 'pribavljam spisak slika',
'ilsubmit'       => 'Traži',
'showlast'       => 'Prikaži poslednjih $1 slika sortiranih po $2.',
'byname'         => 'po imenu',
'bydate'         => 'po datumu',
'bysize'         => 'po veličini',
'imgdelete'      => 'obr',
'imgdesc'        => 'opis',
'imagelinks'     => 'Upotreba slike',
'linkstoimage'   => 'Sledeće stranice koriste ovu sliku:',
'nolinkstoimage' => 'Nema stranica koje koriste ovu sliku.',

# MIME search
'mimesearch' => 'MIME pretraga',
'mimetype'   => 'MIME tip:',

# Statistics
'statistics'    => 'Statistike',
'sitestats'     => 'Statistika sajta',
'userstats'     => 'Statistike korisnika',
'sitestatstext' => "{{SITENAME}} trenutno ima '''$2''' članaka.

Ovaj broj isključuje preusmjerenja, stranice za razgovor, stranice sa opisom slike, korisničke stranice, šablone, stranice za pomoć, članke bez poveznica, i stranice o projektu {{SITENAME}}.

Totalni broj stranica u bazi:  '''$1'''.

Bilo je '''$3''' pogleda stranica, i '''$4''' izmjena otkad je viki bio instaliran.
To izađe u prosjeku oko '''$5''' izmjena po stranici, i '''$6''' pogleda po izmjeni.",
'userstatstext' => "Postoji '''$1''' registrovanih korisnika, od kojih
su '''$2''' (ili '''$4%''') administratori.",

'disambiguations'     => 'Stranice za višeznačne odrednice',
'disambiguationspage' => '{{ns:template}}:Višeznačna odrednica',

'doubleredirects'     => 'Dvostruka preusmjerenja',
'doubleredirectstext' => 'Svaki red sadrži veze na prvo i drugo preusmjerenje, kao i na prvu liniju teksta drugog preusmjerenja, što obično daje "pravi" ciljni članak, na koji bi prvo preusmjerenje i trebalo da pokazuje.',

'brokenredirects'     => 'Pokvarena preusmjerenja',
'brokenredirectstext' => 'Sledeća preusmjerenja su povezana na nepostojeći članak:',

# Miscellaneous special pages
'nbytes'                  => '$1 bajtova',
'nlinks'                  => '$1 veza',
'nviews'                  => '$1 puta pogledano',
'lonelypages'             => 'Siročići',
'uncategorizedpages'      => 'Nekategorisane stranice',
'uncategorizedcategories' => 'Nekategorisane kategorije',
'unusedcategories'        => 'Nekorišćene kategorije',
'unusedimages'            => 'Neupotrebljene slike',
'popularpages'            => 'Popularne stranice',
'wantedcategories'        => 'Tražene kategorije',
'wantedpages'             => 'Tražene stranice',
'allpages'                => 'Sve stranice',
'randompage'              => 'Slučajna stranica',
'shortpages'              => 'Kratke stranice',
'longpages'               => 'Dugačke stranice',
'deadendpages'            => 'Stranice bez internih veza',
'listusers'               => 'Spisak korisnika',
'specialpages'            => 'Posebne stranice',
'spheading'               => 'Posebne stranice za sve korisnike',
'rclsub'                  => '(na stranice povezane sa "$1")',
'newpages'                => 'Nove stranice',
'ancientpages'            => 'Najstarije stranice',
'intl'                    => 'Međujezičke veze',
'move'                    => 'Premjestite',
'movethispage'            => 'Premjesti ovu stranicu',
'unusedimagestext'        => '<p>Obratite pažnju da se drugi veb sajtovi, kao što su drugi
međunarodni Vikiji, mogu povezati na sliku direktnom
URL-om, i tako mogu još uvijek biti prikazani ovdje uprkos
aktivnoj upotrebi.</p>',
'unusedcategoriestext'    => 'Sledeće strane kategorija postoje iako ih ni jedan drugi članak ili kategorija ne koriste.',

# Book sources
'booksources' => 'Štampani izvori',

'categoriespagetext' => 'Sledeće kategorije već postoje u {{SITENAME}}',
'alphaindexline'     => '$1 u $2',
'version'            => 'Verzija',

# Special:Log
'specialloguserlabel'  => 'Korisnik:',
'speciallogtitlelabel' => 'Naslov:',

# E-mail user
'mailnologin'     => 'Nema adrese za slanje',
'mailnologintext' => 'Morate biti [[Special:Userlogin|prijavljeni]]
i imati ispravnu adresu e-pošte u vašim [[Special:Preferences|podešavanjima]]
da biste slali e-poštu drugim korisnicima.',
'emailuser'       => 'Pošalji e-poštu ovom korisniku',
'emailpage'       => 'Pošalji e-pismo korisniku',
'emailpagetext'   => 'Ako je ovaj korisnik unio ispravnu adresu e-pošte u
cvoja korisnička podešavanja, upitnik ispod će poslati jednu poruku.
Adresa e-pošte koju ste vi uneli u svoja korisnička podešavanja će se pojaviti
kao "Od" adresa poruke, tako da će primalac moći da odgovori.',
'usermailererror' => 'Objekat pošte je vratio grešku:',
'defemailsubject' => '{{SITENAME}} e-pošta',
'noemailtitle'    => 'Nema adrese e-pošte',
'noemailtext'     => 'Ovaj korisnik nije naveo ispravnu adresu e-pošte,
ili je izabrao da ne prima e-poštu od drugih korisnika.',
'emailfrom'       => 'Od',
'emailto'         => 'Za',
'emailsubject'    => 'Tema',
'emailmessage'    => 'Poruka',
'emailsend'       => 'Pošalji',
'emailsent'       => 'Poruka poslata',
'emailsenttext'   => 'Vaša poruka je poslata e-poštom.',

# Watchlist
'watchlist'            => 'Praćeni članci',
'mywatchlist'          => 'Praćeni članci',
'nowatchlist'          => 'Nemate ništa na svom spisku praćenih članaka.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Morate biti [[Special:Userlogin|prijavljeni]] da bi ste mijenjali spisak praćenih članaka.',
'addedwatch'           => 'Dodato u spisak praćenih članaka',
'addedwatchtext'       => 'Stranica "[[:$1]]" je dodata vašem [[Special:Watchlist|spisku praćenih članaka]]. Buduće promjene ove stranice i njoj pridružene stranice za razgovor će biti navedene ovde, i stranica će biti <b>podebljana</b> u [[Special:Recentchanges|spisku]] nedavnih izmjena da bi se lakše uočila.

Ako kasnije želite da uklonite stranicu sa vašeg spiska praćenih članaka, kliknite na "prekini praćenje" na paleti.',
'removedwatch'         => 'Uklonjeno iz spiska praćenih članaka',
'removedwatchtext'     => 'Stranica "$1" je uklonjena iz vašeg spiska praćenih članaka.',
'watch'                => 'Prati',
'watchthispage'        => 'Prati ovu stranicu',
'unwatch'              => 'Ukinite praćenje',
'unwatchthispage'      => 'Ukinite praćenje',
'notanarticle'         => 'Nije članak',
'watchnochange'        => 'Ništa što pratite nije promjenjeno u prikazanom vremenu.',
'watchlist-details'    => '$1 stranica praćeno ne računajući stranice za razgovor',
'wlheader-enotif'      => '* Obavještavanje e-poštom je omogućeno.',
'wlheader-showupdated' => "* Stranice koje su izmjenjene od kad ste ih poslednji put posjetili su prikazane '''podebljanim slovima'''",
'watchmethod-recent'   => 'provjerava se da li ima praćenih stranica u nedavnim izmjenama',
'watchmethod-list'     => 'provjerava se da li ima nedavnih izmjena u praćenim stranicama',
'watchlistcontains'    => 'Vaš spisak praćenih članaka sadrži $1 stranica.',
'iteminvalidname'      => "Problem sa '$1', neispravno ime...",
'wlnote'               => 'Ispod je najskorijih $1 izmjena, načinjenih u posljednjih <b>$2</b> sati.',
'wlshowlast'           => 'Prikaži poslednjih $1 sati $2 dana $3',
'wlsaved'              => 'Ovo je sačuvana verzija vašeg spiska praćenih članaka.',

'enotif_mailer'      => '{{SITENAME}} obaviještenje o pošti',
'enotif_reset'       => 'Označi sve strane kao posjećene',
'enotif_newpagetext' => 'Ovo je novi članak.',
'enotif_subject'     => '{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED od strane $PAGEEDITOR',
'enotif_lastvisited' => 'Pogledajte {{fullurl:$PAGETITLE_RAWURL|diff=0&oldid=$OLDID}} za sve izmjene od vaše poslednje posjete.',
'enotif_body'        => 'Dragi $WATCHINGUSERNAME,

{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED $PAGEEDITDATE od strane $PAGEEDITOR,
pogledajte {{fullurl:$PAGETITLE_RAWURL}} za trenutnu verziju.

$NEWPAGE

Rezime editora: $PAGESUMMARY $PAGEMINOREDIT

Kontaktirajte editora:
pošta {{fullurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}}
viki {{fullurl:User:$PAGEEDITOR_RAWURL}}

Neće biti drugih obaviještenja u slučaju daljih izmjena ukoliko ne posjetite ovu stranu.
Takođe možete da resetujete zastavice za obaviještenja za sve Vaše praćene stranice na vašem spisku praćenenih članaka.

             Vaš prijateljski {{SITENAME}} sistem obaviještavanja

--
Da promjenite podešavanja vezana za spisak praćenenih članaka posjetite
{{fullurl:Special:Watchlist|edit=yes}}

Fidbek i dalja pomoć:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Obrišite stranicu',
'confirm'                     => 'Potvrdite',
'excontent'                   => "sadržaj je bio: '$1'",
'exbeforeblank'               => "sadržaj prije brisanja je bio: '$1'",
'exblank'                     => 'stranica je bila prazna',
'confirmdelete'               => 'Potvrdi brisanje',
'deletesub'                   => '(Briše se "$1")',
'historywarning'              => 'Pažnja:  stranica koju želite da obrišete ima istoriju:',
'confirmdeletetext'           => 'Na putu ste da trajno obrišete stranicu
ili sliku zajedno sa svom njenom istorijom iz baze.
Molimo Vas da potvrdite da namjeravate da uradite ovo, da razumijete
poslijedice, i da ovo radite u skladu sa
[[{{MediaWiki:Policy-url}}|pravilima]] {{SITENAME}}.',
'actioncomplete'              => 'Akcija završena',
'deletedtext'                 => 'Članak "$1" je obrisan.
Pogledajte $2 za zapis o skorašnjim brisanjima.',
'deletedarticle'              => 'obrisan "[[$1]]"',
'dellogpage'                  => 'istorija brisanja',
'dellogpagetext'              => 'Ispod je spisak najskorijih brisanja.',
'deletionlog'                 => 'istorija brisanja',
'reverted'                    => 'Vraćeno na prijašnju reviziju',
'deletecomment'               => 'Razlog za brisanje',
'rollback'                    => 'Vrati izmjene',
'rollback_short'              => 'Vrati',
'rollbacklink'                => 'vrati',
'rollbackfailed'              => 'Vraćanje nije uspjelo',
'cantrollback'                => 'Ne može se vratiti izmjena; poslednji autor je ujedno i jedini.',
'alreadyrolled'               => 'Ne može se vratiti poslednja izmjena [[:$1]] od korisnika [[User:$2|$2]] ([[User talk:$2|razgovor]]); neko drugi je već izmjenio ili vratio članak.  Poslednja izmjena od korisnika [[User:$3|$3]] ([[User talk:$3|razgovor]]).',
'editcomment'                 => 'Komentar izmjene je: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Vraćene izmjene $2 na poslednju izmjenu korisnika $1',
'protectlogpage'              => 'Istorija zaključavanja',
'protectlogtext'              => 'Ispod je spisak zaštićenja stranice.',
'protectedarticle'            => 'stranica "[[$1]]" je zaštićena',
'unprotectedarticle'          => 'odštićena "$1"',
'protectsub'                  => '(Zaštićuje se "$1")',
'confirmprotect'              => 'Potvrdite zaštitu',
'protectcomment'              => 'Razlog za zaštitu',
'unprotectsub'                => '(Skidanje zaštite "$1")',
'protect-unchain'             => 'Deblokirajte dozvole premještanja',
'protect-text'                => 'Ovdje možete gledati i izmjeniti level zaštite za stranicu <strong>$1</strong>.',
'protect-default'             => '(standardno)',
'protect-level-autoconfirmed' => 'Blokiraj neregistrovane korisnike',
'protect-level-sysop'         => 'Samo administratori',

# Undelete
'undelete'               => 'Pogledaj izbrisane stranice',
'undeletepage'           => 'Pogledaj i vrati izbrisane stranice',
'viewdeletedpage'        => 'Pogledaj izbrisane stranice',
'undeletepagetext'       => 'Sledeće stranice su izbrisane ali su još uvijek u arhivi i
mogu biti vraćene.  Arhiva moše biti periodično čišćena.',
'undeleterevisions'      => '$1 revizija arhivirano',
'undeletehistory'        => 'Ako vratite stranicu, sve revizije će biti vraćene njenoj istoriji.
Ako je nova stranica istog imena napravljena od brisanja, vraćene
revizije će se pojaviti u ranijoj istoriji, a trenutna revizija sadašnje stranice
neće biti automatski zamijenjena.',
'undeletehistorynoadmin' => 'Ova stranica je izbrisana.  Ispod se nalazi dio istorije brisanja i istorija revizija izbrisane stranice.  Tekst izbrisane stranice je vidljiv samo korisnicima koji su administratori.',
'undeletebtn'            => 'Vrati!',
'undeletedarticle'       => 'vraćeno "$1"',
'undeletedrevisions'     => '$1 revizija vraćeno',

# Contributions
'contributions' => 'Doprinos korisnika',
'mycontris'     => 'Moj doprinos',
'contribsub2'   => 'Za $1 ($2)',
'nocontribs'    => 'Nisu nađene promjene koje zadovoljavaju ove uslove.',
'ucnote'        => 'Ispod je poslednjih <b>$1</b> izmjena u poslednjih <b>$2</b> dana.',
'uclinks'       => 'Gledaj poslednjih $1 izmjena; gledaj poslednjih $2 dana.',
'uctop'         => ' (vrh)',

# What links here
'whatlinkshere' => 'Šta je povezano ovdje',
'notargettitle' => 'Nema cilja',
'notargettext'  => 'Niste naveli ciljnu stranicu ili korisnika
na kome bi se izvela ova funkcija.',
'linklistsub'   => '(Spisak veza)',
'linkshere'     => 'Sledeće stranice su povezane ovdje:',
'nolinkshere'   => 'Ništa nije povezano ovdje.',
'isredirect'    => 'preusmjerivač',

# Block/unblock
'blockip'              => 'Blokiraj korisnika',
'blockiptext'          => 'Upotrebite donji upitnik da biste uklonili prava pisanja sa određene IP adrese ili korisničkog imena.  Ovo bi trebalo da bude urađeno samo da bi se spriječio vandalizam, i u skladu sa [[{{MediaWiki:Policy-url}}|smjernicama]]. Unesite konkretan razlog ispod (na primjer, navodeći koje stranice su vandalizovane).',
'ipaddress'            => 'IP adresa/korisničko ime',
'ipbexpiry'            => 'Trajanje',
'ipbreason'            => 'Razlog',
'ipbsubmit'            => 'Blokirajte ovog korisnika',
'badipaddress'         => 'Pogrešna IP adresa',
'blockipsuccesssub'    => 'Blokiranje je uspjelo',
'blockipsuccesstext'   => '[[Special:Contributions/$1|$1]] je blokiran.
<br />Pogledajte [[Special:Ipblocklist|IP spisak blokiranih korisnika]] za pregled blokiranja.',
'unblockip'            => 'Odblokiraj korisnika',
'unblockiptext'        => 'Upotrebite donji upitnik da bi ste vratili
pravo pisanja ranije blokiranoj IP adresi
ili korisničkom imenu.',
'ipusubmit'            => 'Deblokirajte ovog korisnika',
'ipblocklist'          => 'Spisak blokiranih IP adresa i korisničkih imena',
'blocklistline'        => '$1, $2 blokirao korisnika $3 ($4)',
'blocklink'            => 'blokirajte',
'unblocklink'          => 'deblokiraj',
'contribslink'         => 'doprinos',
'autoblocker'          => 'Automatski ste blokirani jer dijelite IP adresu sa "$1".  Razlog za blokiranje je: "\'\'\'$2\'\'\'"',
'blocklogentry'        => 'je blokirao "$1" sa vremenom isticanja blokade od $2',
'blocklogtext'         => 'Ovo je istorija blokiranja i deblokiranja korisnika.  Automatsko blokirane IP adrese nisu uspisane ovde.  Pogledajte [[Special:Ipblocklist|blokirane IP adrese]] za spisak trenutnih zabrana i blokiranja.',
'unblocklogentry'      => 'deblokiran $1',
'range_block_disabled' => 'Administratorska mogućnost da blokira grupe je isključena.',
'ipb_expiry_invalid'   => 'Pogrešno vrijeme trajanja.',
'ip_range_invalid'     => 'Netačan raspon IP adresa.',
'proxyblocker'         => 'Bloker proksija',
'proxyblockreason'     => 'Vaša IP adresa je blokirana jer je ona otvoreni proksi.  Molimo vas da kontaktirate vašeg davatelja internetskih usluga (Internet Service Provider-a) ili tehničku podršku i obavijestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess'    => 'Proksi uspješno blokiran.',

# Developer tools
'lockdb'              => 'Zaključajte bazu',
'unlockdb'            => 'Otključaj bazu',
'lockdbtext'          => 'Zaključavanje baze će svim korisnicima ukinuti mogućnost izmjene stranica,
promjene korisničkih podešavanja, izmjene praćenih članaka, i svega ostalog
što zahtjeva promjene u bazi.
Molimo Vas da potvrdite da je ovo zaista ono što namjeravate da uradite, i da ćete
otkučati bazu kad završite posao oko njenog održavanja.',
'unlockdbtext'        => 'Otključavanje baze će svim korisnicima vratiti mogućnost
izmjene stranica, promjene korisničkih stranica, izmjene spiska praćenih članaka,
i svega ostalog što zahtjeva promjene u bazi.
Molimo Vas da potvrdite da je ovo zaista ono što namijeravate da uradite.',
'lockconfirm'         => 'Da, zaista želim da zaključam bazu.',
'unlockconfirm'       => 'Da, zaista želim da otključam bazu.',
'lockbtn'             => 'Zaključajte bazu',
'unlockbtn'           => 'Otključaj bazu',
'locknoconfirm'       => 'Niste potvrdili svoju namjeru.',
'lockdbsuccesssub'    => 'Baza je zaključana',
'unlockdbsuccesssub'  => 'Baza je otključana',
'lockdbsuccesstext'   => '{{SITENAME}} baza podataka je zaključana. <br /> Sjetite se da je otključate kad završite sa održavanjem.',
'unlockdbsuccesstext' => '{{SITENAME}} baza podataka je otključana.',

# Move page
'movepage'         => 'Premjestite stranicu',
'movepagetext'     => "Donji upitnik će preimenovati stranicu, premještajući svu
njenu istoriju na novo ime.
Stari naslov će postati preusmjerenje na novi naslov.
Poveznice prema starom naslovu neće biti promijenjene; obavezno
provjerite da li ima [[Special:DoubleRedirects|dvostrukih]] ili [[Special:BrokenRedirects|pokvarenih preusmjerenja]].
Na vama je odgovornost da veze i dalje idu tamo gdje trebaju da idu.

Obratite pažnju da stranica '''neće''' biti pomjerena ako već postoji
stranica sa novim naslovom, osim ako je ona prazna ili preusmjerenje i nema
istoriju promjena.   Ovo znači da ne možete preimenovati stranicu na ono ime
sa koga ste je preimenovali ako pogriješite, i ne možete prepisati
postojeću stranicu.

<b>PAŽNJA!</b>
Ovo može biti drastična i neočekivana promjena za popularnu stranicu;
molimo Vas da budete sigurni da razumijete poslijedice ovoga prije što
nastavite.",
'movepagetalktext' => "Odgovarajuća stranica za razgovor, ako postoji, će automatski biti premještena istovremeno '''osim:'''
*Ako premještate stranicu preko imenskih prostora,
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odčekirajte donju kutiju.

U tim slučajevima, moraćete ručno da premjestite stranicu ukoliko to želite.",
'movearticle'      => 'Premjestite stranicu',
'movenologin'      => 'Niste prijavljeni',
'movenologintext'  => 'Morate biti registrovani korisnik i [[Special:Userlogin|prijavljeni]]
da biste premjestili stranicu.',
'newtitle'         => 'Novi naslov',
'movepagebtn'      => 'premjestite stranicu',
'pagemovedsub'     => 'Premještanje uspjelo',
'articleexists'    => 'Stranica pod tim imenom već postoji, ili je ime koje ste izabrali neispravno.  Molimo Vas da izaberete drugo ime.',
'talkexists'       => 'Sama stranica je uspješno premještena, ali
stranica za razgovor nije mogla biti premještena jer takva već postoji na novom naslovu.  Molimo Vas da ih spojite ručno.',
'movedto'          => 'premještena na',
'movetalk'         => 'Premjestite "stranicu za razgovor" takođe, ako je moguće.',
'talkpagemoved'    => 'Odgovarajuća stranica za razgovor je takođe premještena.',
'talkpagenotmoved' => 'Odgovarajuća stranica za razgovor <strong>nije</strong> premještena.',
'1movedto2'        => 'stranica [[$1]] premještena u stranicu [[$2]]',
'1movedto2_redir'  => 'stranica [[$1]] premještena u stranicu [[$2]] putem preusmjerenja',
'revertmove'       => 'vrati',
'selfmove'         => 'Izvorni i ciljani naziv su isti; strana ne može da se premjesti preko same sebe.',

# Export
'export'        => 'Izvezite stranice',
'exporttext'    => 'Možete izvesti tekst i istoriju promjena određene stranice
ili grupe stranice u XML formatu.  Ovo onda može biti uvezeno u drugi viki koji koristi MedijaViki softver, transformisano, ili korišćeno za Vaše lične potrebe.',
'exportcuronly' => 'Uključite samo trenutnu reviziju, ne cijelu istoriju',

# Namespace 8 related
'allmessages'               => 'Sistemske poruke',
'allmessagestext'           => 'Ovo je spisak svih sistemskih poruka u {{ns:8}} imenskom prostoru.',
'allmessagesnotsupportedDB' => '[[Special:Allmessages|sistemske poruke]] nisu podržane zato što je <i>wgUseDatabaseMessages</i> isključen.',

# Thumbnails
'thumbnail-more' => 'uvećajte',
'missingimage'   => '<b>Ovdje nedostaje slika</b><br /><i>$1</i>',
'filemissing'    => 'Nedostaje fajl',

# Special:Import
'import'                => 'Ivoz stranica',
'importtext'            => 'Molimo Vas da izvezete fajl iz izvornog vikija koristeći [[Special:Export|izvoz]], sačuvajte ga kod sebe i pošaljite ovde.',
'importfailed'          => 'Uvoz nije uspjeo: $1',
'importnotext'          => 'Stranica je prazna, ili bez teksta',
'importsuccess'         => 'Uspješno ste uvezli stranicu!',
'importhistoryconflict' => 'Postoji konfliktna istorija revizija',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Moja korisnička stranica',
'tooltip-pt-anonuserpage'         => 'Korisnička stranica za ip koju Vi uređujete kao',
'tooltip-pt-mytalk'               => 'Moja stranica za razgovor',
'tooltip-pt-anontalk'             => 'Razgovor o doprinosu sa ove IP adrese',
'tooltip-pt-preferences'          => 'Moja podešavanja',
'tooltip-pt-watchlist'            => 'Spisak članaka koje pratite.',
'tooltip-pt-mycontris'            => 'Spisak mog doprinosa',
'tooltip-pt-login'                => 'Prijava nije obavezna, ali donosi mnogo koristi.',
'tooltip-pt-anonlogin'            => 'Prijava nije obavezna, ali donosi mnogo koristi.',
'tooltip-pt-logout'               => 'Odjava sa projekta {{SITENAME}}',
'tooltip-ca-talk'                 => 'Razgovor o sadržaju',
'tooltip-ca-edit'                 => 'Možete da uređujete ovaj članak. Molimo Vas, koristite dugme "Prikaži izgled',
'tooltip-ca-addsection'           => 'Dodajte svoj komentar.',
'tooltip-ca-viewsource'           => 'Ovaj članak je zaključan. Možete ga samo vidjeti ili kopirati kod.',
'tooltip-ca-history'              => 'Prethodne verzije ove stranice.',
'tooltip-ca-protect'              => 'Zaštitite stranicu od budućih izmjena',
'tooltip-ca-delete'               => 'Izbrišite ovu stranicu',
'tooltip-ca-undelete'             => 'Vratite izmjene koje su načinjene prije brisanja stranice',
'tooltip-ca-move'                 => 'Pomjerite stranicu',
'tooltip-ca-watch'                => 'Dodajte stranicu u listu praćnih članaka',
'tooltip-ca-unwatch'              => 'Izbrišite stranicu sa liste praćnih članaka',
'tooltip-search'                  => 'Pretražite projekat {{SITENAME}}',
'tooltip-p-logo'                  => 'Glavna stranica',
'tooltip-n-mainpage'              => 'Posjetite glavnu stranicu',
'tooltip-n-portal'                => 'O projektu, kako Vi možete pomoći, i gdje da nađete potrebne stvari o projektu {{SITENAME}}',
'tooltip-n-currentevents'         => 'Podaci o onome na čemu se trenutno radi',
'tooltip-n-recentchanges'         => 'Spisak nedavnih izmjena na projektu {{SITENAME}}.',
'tooltip-n-randompage'            => 'Otvorite slučajan članak',
'tooltip-n-help'                  => 'Naučite da uređujete projekat {{SITENAME}}.',
'tooltip-n-sitesupport'           => 'Podržite nas',
'tooltip-t-whatlinkshere'         => 'Spisak svih članaka koji su povezani sa ovim',
'tooltip-t-recentchangeslinked'   => 'Nedavne izmjene na stranicama koje su povezane sa ovom',
'tooltip-feed-rss'                => 'RSS za ovu stranicu',
'tooltip-feed-atom'               => 'Atom za ovu stranicu',
'tooltip-t-contributions'         => 'Pogledajte spisak doprinosa ovog korisnika',
'tooltip-t-emailuser'             => 'Pošaljite pismo ovom korisniku',
'tooltip-t-upload'                => 'Pošaljite slike i medija fajlove',
'tooltip-t-specialpages'          => 'Spisak svih posebih stranica',
'tooltip-ca-nstab-main'           => 'Pogledajte sadržaj članka',
'tooltip-ca-nstab-user'           => 'Pogledajte korisničku stranicu',
'tooltip-ca-nstab-media'          => 'Pogledajte medija fajl',
'tooltip-ca-nstab-special'        => 'Ovo je specijalna stranica i zato je ne možete uređivati',
'tooltip-ca-nstab-project'        => 'Pogledajte projekat stranicu',
'tooltip-ca-nstab-image'          => 'Pogledajte stranicu slike',
'tooltip-ca-nstab-mediawiki'      => 'Pogledajte sistemsku poruku',
'tooltip-ca-nstab-template'       => 'Pogledajte šablon',
'tooltip-ca-nstab-help'           => 'Pogledajte stranicu za pomoć',
'tooltip-ca-nstab-category'       => 'Pogledajte stranicu kategorije',
'tooltip-minoredit'               => 'Naznačite da se radi o maloj izmjeni',
'tooltip-save'                    => 'Sačuvajte Vaše izmjene',
'tooltip-preview'                 => 'Pregledajte Vaše izmjene; molimo Vas da koristite ovo prije nego što sačuvate stranicu!',
'tooltip-compareselectedversions' => 'Pogledajte pazlike između dvije selektovane verzije ove stranice.',
'tooltip-watch'                   => 'Dodajte ovu stranicu na Vaš spisak praćenih članaka',

# Metadata
'nodublincore'      => 'Dublin Core RDF metapodaci onemogućeni za ovaj server.',
'nocreativecommons' => 'Creative Commons RDF metapodaci onemogućeni za ovaj server.',
'notacceptable'     => 'Viki server ne može da pruži podatke u onom formatu koji Vaš klijent može da pročita.',

# Attribution
'anonymous'        => 'Anonimni korisnik od {{SITENAME}}',
'siteuser'         => '{{SITENAME}} korisnik $1',
'lastmodifiedatby' => 'Ovu stranicu je poslednji put promjenio $3, dana $3.', # $1 date, $2 time, $3 user
'and'              => 'i',
'othercontribs'    => 'Bazirano na radu od strane korisnika $1.',
'siteusers'        => '{{SITENAME}} korisnik (korisnici) $1',

# Spam protection
'spamprotectiontitle' => 'Filter za zaštitu od neželjenih poruka',
'spamprotectiontext'  => 'Strana koju želite da sačuvate je blokirana od strane filtera za neželjene poruke.  Ovo je vjerovatno izazvao vezom ka spoljašnjem sajtu.',
'spamprotectionmatch' => 'Sledeći tekst je izazvao naš filter za neželjene poruke: $1',
'subcategorycount'    => '$1 potkategorija su u ovoj kategoriji.',

# Patrolling
'markaspatrolleddiff'        => 'Označi kao patrolirano',
'markaspatrolledtext'        => 'Označi ovaj članak kao patroliran',
'markedaspatrolled'          => 'Označeno kao patrolirano',
'markedaspatrolledtext'      => 'Izabrana revizija je označena kao patrolirana.',
'markedaspatrollederror'     => 'Ne može se označiti kao patrolirano',
'markedaspatrollederrortext' => 'Morate naglasiti reviziju koju treba označiti kao patroliranu.',

# Media information
'mediawarning' => "'''Upozorenje''': Ovaj fajl sadrži loš kod, njegovim izvršavanjem možete da ugrozite Vaš sistem.
<hr />",
'thumbsize'    => 'Veličina umanjenog prikaza:',

# Special:Newimages
'showhidebots' => '($1 botove)',

# Metadata
'metadata' => 'Metapodaci',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sve',

# E-mail address confirmation
'confirmemail'            => 'Potvrdite adresu e-pošte',
'confirmemail_text'       => 'Ova viki zahtjeva da potvrdite adresu Vaše e-pošte prije nego što koristite mogućnosti e-pošte. Aktivirajte dugme ispod kako bi ste poslali poštu za potvrdu na Vašu adresu. Pošta uključuje poveznicu koja sadrži kod; učitajte poveznicu u Vaš brauzer da bi ste potvrdili da je adresa Vaše e-pošte validna.',
'confirmemail_send'       => 'Pošaljite kod za potvrdu',
'confirmemail_sent'       => 'E-pošta za potvrđivanje poslata.',
'confirmemail_sendfailed' => 'Pošta za potvrđivanje nije poslata. Provjerite adresu zbog nepravilnih karaktera.',
'confirmemail_invalid'    => 'Netačan kod za potvrdu. Moguće je da je kod istekao.',
'confirmemail_success'    => 'Adresa vaše e-pošte je potvrđena. Možete sad da se prijavite i uživate u viki.',
'confirmemail_loggedin'   => 'Adresa Vaše e-pošte je potvrđena.',
'confirmemail_error'      => 'Nešto je pošlo po zlu prilikom sačuvavanja vaše potvrde.',
'confirmemail_subject'    => '{{SITENAME}} adresa e-pošte za potvrđivanje',
'confirmemail_body'       => 'Neko, vjerovatno Vi, je sa IP adrese $1 registrovao nalog "$2" sa ovom adresom e-pošte na {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada vama i da aktivirate mogućnost e-pošte na {{SITENAME}}, otvorite ovu poveznicu u vašem brauzeru:

$3

Ako ovo niste vi, ne pratite poveznicu. Ovaj kod za potvrdu će isteći u $4.',

# Delete conflict
'confirmrecreate' => "Korisnik [[User:$1|$1]] ([[User talk:$1|razgovor]]) je obrisao ovaj članak pošto ste počeli uređivanje sa razlogom:
: ''$2''

Molimo Vas da potvrdite da stvarno želite da ponovo napravite ovaj članak.",

);
