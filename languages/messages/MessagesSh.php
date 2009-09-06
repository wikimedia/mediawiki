<?php
/** Serbo-Croatian (Srpskohrvatski / Српскохрватски)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author OC Ripper
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_sa_korisnikom',
	NS_FILE             => 'Datoteka',
	NS_FILE_TALK        => 'Razgovor_o_datoteci',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Podvuci linkove:',
'tog-highlightbroken'         => 'Formatiraj pokvarene linkove <a href="" class="new">ovako</a> (alternativa: ovako<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Uravnaj pasuse',
'tog-hideminor'               => 'Sakrij manje izmjene u spisku nedavnih izmjena',
'tog-hidepatrolled'           => 'Sakrij patrolirane izmjene u nedavnim promjenama',
'tog-newpageshidepatrolled'   => 'Sakrij patrolirane stranice sa spiska novih stranica',
'tog-extendwatchlist'         => 'Proširi spisak praćenja za pogled svih izmjena, ne samo nedavnih',
'tog-usenewrc'                => 'Korištenje poboljšanog spiska nedavnih izmjena (zahtijeva JavaScript)',
'tog-numberheadings'          => 'Automatski numeriši podnaslove',
'tog-showtoolbar'             => 'Pokaži alatnu traku (potreban JavaScript)',
'tog-editondblclick'          => 'Izmijeni stranice dvostrukim klikom (potreban JavaScript)',
'tog-editsection'             => 'Omogući uređivanje sekcija preko [uredi] linkova',
'tog-editsectiononrightclick' => 'Uključite uređivanje odjeljka sa pritiskom na desno dugme miša u naslovu odjeljka (JavaScript)',
'tog-showtoc'                 => 'Prikaži sadržaj (u svim stranicama sa više od tri podnaslova)',
'tog-rememberpassword'        => 'Upamti moju lozinku na ovom kompjuteru za buduće posjete',
'tog-editwidth'               => 'Proširi kutiju za uređivanje da ispuni cijeli ekran',
'tog-watchcreations'          => 'Dodaj stranice koje sam napravio u moj spisak praćenja',
'tog-watchdefault'            => 'Dodaj stranice koje uređujem u moj spisak praćenja',
'tog-watchmoves'              => 'Dodaj stranice koje premještam u moj spisak praćenja',
'tog-watchdeletion'           => 'Stranice koje brišem dodaj na moj spisak praćenja',
'tog-minordefault'            => 'Označi da su sve izmjene u pravilu manje',
'tog-previewontop'            => 'Prikaži pretpregled prije kutije za uređivanje',
'tog-previewonfirst'          => 'Prikaži pretpregled na prvoj izmjeni',
'tog-nocache'                 => 'Onemogući keširanje stranica',
'tog-enotifwatchlistpages'    => 'Pošalji mi e-poštu kad se promijeni stranica na mom spisku praćenja',
'tog-enotifusertalkpages'     => 'Pošalji mi e-poštu kad se promijeni moja korisnička stranica za razgovor',
'tog-enotifminoredits'        => 'Pošalji mi e-poštu takođe za male izmjene stranica',
'tog-enotifrevealaddr'        => 'Otkrij adresu moje e-pošte u porukama obaviještenja',
'tog-shownumberswatching'     => 'Prikaži broj korisnika koji prate',
'tog-fancysig'                => 'Smatraj potpis kao wikitekst (bez automatskog linka)',
'tog-externaleditor'          => 'Po defaultu koristite eksterni editor (samo za naprednije korisnike, potrebne su posebne postavke na vašem računaru)',
'tog-externaldiff'            => 'Koristi vanjski (diff) program za prikaz razlika (samo za naprednije korisnike, potrebne posebne postavke na vašem računaru)',
'tog-showjumplinks'           => 'Omogući opciju "skoči na" linkove',
'tog-uselivepreview'          => 'Koristite pretpregled uživo (potreban JavaScript) (eksperimentalno)',
'tog-forceeditsummary'        => 'Opomeni me pri unosu praznog sažetka',
'tog-watchlisthideown'        => 'Sakrij moje izmjene sa spiska praćenih članaka',
'tog-watchlisthidebots'       => 'Sakrij izmjene botova sa spiska praćenih članaka',
'tog-watchlisthideminor'      => 'Sakrij manje izmjene sa spiska praćenja',
'tog-watchlisthideliu'        => 'Sakrij izmjene prijavljenih korisnika sa liste praćenja',
'tog-watchlisthideanons'      => 'Sakrij izmjene anonimnih korisnika sa liste praćenja',
'tog-watchlisthidepatrolled'  => 'Sakrij patrolirane izmjene sa spiska praćenja',
'tog-ccmeonemails'            => 'Pošalji mi kopije emailova koje šaljem drugim korisnicima',
'tog-diffonly'                => 'Ne prikazuj sadržaj stranice ispod prikaza razlika',
'tog-showhiddencats'          => 'Prikaži skrivene kategorije',
'tog-norollbackdiff'          => 'Nakon povrata zanemari prikaz razlika',

'underline-always'  => 'Uvijek',
'underline-never'   => 'Nikad',
'underline-default' => 'Po postavkama preglednika',

# Dates
'january'   => 'januar',
'february'  => 'februar',
'march'     => 'mart',
'april'     => 'april',
'may_long'  => 'maj',
'june'      => 'jun',
'july'      => 'jul',
'august'    => 'august',
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
'aug'       => 'aug',
'sep'       => 'sep',
'oct'       => 'okt',
'nov'       => 'nov',
'dec'       => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategorije}}',
'category_header'                => 'Stranice u kategoriji "$1"',
'subcategories'                  => 'Potkategorije',
'category-media-header'          => 'Medijske datoteke u kategoriji "$1"',
'category-empty'                 => "''Ova kategorija trenutno ne sadrži članke ni medijske datoteke.''",
'hidden-categories'              => '{{PLURAL:$1|Sakrivena kategorija|Sakrivene kategorije}}',
'hidden-category-category'       => 'Sakrivene kategorije',
'category-subcat-count'          => '{{PLURAL:$2|Ova kategorija ima sljedeću $1 potkategoriju.|Ova kategorija ima {{PLURAL:$1|sljedeće potkategorije|sljedećih $1 potkategorija}}, od $2 ukupno.}}',
'category-subcat-count-limited'  => 'Ova kategorija sadrži {{PLURAL:$1|slijedeću $1 podkategoriju|slijedeće $1 podkategorije|slijedećih $1 podkategorija}}.',
'category-article-count'         => '{{PLURAL:$2|U ovoj kategoriji se nalazi $1 stranica.|{{PLURAL:$1|Prikazana je $1 stranica|Prikazano je $1 stranice|Prikazano je $1 stranica}} od ukupno $2 u ovoj kategoriji.}}',
'category-article-count-limited' => '{{PLURAL:$1|Slijedeća $1 stranica je|Slijedeće $1 stranice su|Slijedećih $1 stranica je}} u ovoj kategoriji.',
'category-file-count'            => '{{PLURAL:$2|Ova kategorija ima slijedeću $1 datoteku.|{{PLURAL:$1|Prikazana je $1 datoteka|Prikazane su $1 datoteke|Prikazano je $1 datoteka}} u ovoj kategoriji, od ukupno $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Slijedeća $1 datoteka je|Slijedeće $1 datoteke su|Slijedećih $1 datoteka je}} u ovoj kategoriji.',
'listingcontinuesabbrev'         => 'nast.',

'mainpagetext'      => "<big>'''MediaWiki softver is uspješno instaliran.'''</big>",
'mainpagedocfooter' => 'Kontaktirajte [http://meta.wikimedia.org/wiki/Help:Contents uputstva za korisnike] za informacije o upotrebi wiki programa.

== Početak ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista postavki]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki najčešće postavljana pitanja]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista E-Mail adresa MediaWiki]',

'about'         => 'O...',
'article'       => 'Stranica sadržaja (članak)',
'newwindow'     => '(otvara se u novom prozoru)',
'cancel'        => 'Poništi',
'moredotdotdot' => 'Još...',
'mypage'        => 'Moja stranica',
'mytalk'        => 'Moj razgovor',
'anontalk'      => 'Razgovor za ovu IP adresu',
'navigation'    => 'Navigacija',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Pronađite',
'qbbrowse'       => 'Prelistajte',
'qbedit'         => 'Uredi',
'qbpageoptions'  => 'Opcije stranice',
'qbpageinfo'     => 'Informacije o stranici',
'qbmyoptions'    => 'Moje opcije',
'qbspecialpages' => 'Posebne stranice',

# Metadata in edit box
'metadata_help' => 'Metapodaci:',

'errorpagetitle'    => 'Greška',
'returnto'          => 'Povratak na $1.',
'tagline'           => 'Izvor: {{SITENAME}}',
'help'              => 'Pomoć',
'search'            => 'Pretraga',
'searchbutton'      => 'Traži',
'go'                => 'Idi',
'searcharticle'     => 'Idi',
'history'           => 'Historija stranice',
'history_short'     => 'Historija',
'updatedmarker'     => 'promjene od moje zadnje posjete',
'info_short'        => 'Informacija',
'printableversion'  => 'Verzija za ispis',
'permalink'         => 'Trajni link',
'print'             => 'Štampa',
'edit'              => 'Uredi',
'create'            => 'Napravi',
'editthispage'      => 'Uredite ovu stranicu',
'create-this-page'  => 'Stvori ovu stranicu',
'delete'            => 'Obriši',
'deletethispage'    => 'Obriši ovu stranicu',
'undelete_short'    => 'Vrati obrisanih {{PLURAL:$1|$1 izmjenu|$1 izmjene|$1 izmjena}}',
'protect'           => 'Zaštiti',
'protect_change'    => 'promijeni',
'protectthispage'   => 'Zaštiti ovu stranicu',
'unprotect'         => 'Odštiti',
'unprotectthispage' => 'Odštiti ovu stranicu',
'newpage'           => 'Nova stranica',
'talkpage'          => 'Razgovaraj o ovoj stranici',
'talkpagelinktext'  => 'Razgovor',
'specialpage'       => 'Posebna stranica',
'personaltools'     => 'Lični alati',
'postcomment'       => 'Nova sekcija',
'articlepage'       => 'Pogledaj stranicu sa sadržajem (članak)',
'talk'              => 'Razgovor',
'views'             => 'Pregledi',
'toolbox'           => 'Traka sa alatima',
'userpage'          => 'Pogledajte korisničku stranicu',
'projectpage'       => 'Pogledajte stranicu projekta',
'imagepage'         => 'Vidi stranicu datoteke/fajla',
'mediawikipage'     => 'Pogledaj stranicu s porukom',
'templatepage'      => 'Pogledajte stranicu sa šablonom',
'viewhelppage'      => 'Pogledajte stranicu za pomoć',
'categorypage'      => 'Pogledajte stranicu kategorije',
'viewtalkpage'      => 'Pogledajte raspravu',
'otherlanguages'    => 'Na drugim jezicima',
'redirectedfrom'    => '(Preusmjereno sa $1)',
'redirectpagesub'   => 'Preusmjeri stranicu',
'lastmodifiedat'    => 'Ova stranica je posljednji put izmijenjena $1, $2.',
'viewcount'         => 'Ovoj stranici je pristupljeno {{PLURAL:$1|$1 put|$1 puta}}.',
'protectedpage'     => 'Zaštićena stranica',
'jumpto'            => 'Skoči na:',
'jumptonavigation'  => 'navigacija',
'jumptosearch'      => 'pretraga',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O projektu {{SITENAME}}',
'copyright'            => 'Sadržaj je dostupan pod $1.',
'disclaimers'          => 'Odricanje odgovornosti',
'edithelp'             => 'Pomoć pri uređivanju',
'helppage'             => 'Help:Sadržaj',
'mainpage'             => 'Glavna strana',
'mainpage-description' => 'Glavna strana',
'privacy'              => 'Politika privatnosti',

'badaccess'        => 'Greška pri odobrenju',
'badaccess-group0' => 'Nije vam dozvoljeno izvršiti akciju koju ste zahtjevali.',
'badaccess-groups' => 'Akcija koju ste zahtjevali je ograničena na korisnike iz {{PLURAL:$2|ove grupe|jedne od grupa}}: $1.',

'versionrequired'     => 'Potrebna je verzija $1 MediaWikija',
'versionrequiredtext' => 'Potrebna je verzija $1 MediaWikija da bi se koristila ova stranica. Pogledaj [[Special:Version|verziju]].',

'ok'                      => 'da',
'retrievedfrom'           => 'Dobavljeno iz "$1"',
'youhavenewmessages'      => 'Imate $1 ($2).',
'newmessageslink'         => 'novih promjena',
'newmessagesdifflink'     => 'posljednja promjena',
'youhavenewmessagesmulti' => 'Imate nove poruke na $1',
'editsection'             => 'uredi',
'editold'                 => 'uredi',
'viewsourceold'           => 'pogledaj izvor',
'editlink'                => 'uredi',
'viewsourcelink'          => 'pogledaj kod',
'editsectionhint'         => 'Uredi sekciju: $1',
'toc'                     => 'Sadržaj',
'showtoc'                 => 'prikaži',
'hidetoc'                 => 'sakrij',
'thisisdeleted'           => 'Pogledaj ili vrati $1?',
'viewdeleted'             => 'Pogledaj $1?',
'restorelink'             => '{{PLURAL:$1|$1 izbrisana izmjena|$1 izbrisanih izmjena}}',
'feedlinks'               => 'Fid:',
'feed-invalid'            => 'Loš tip prijave na fid.',
'feed-unavailable'        => 'Fidovi (izvori) nisu dostupni',
'site-rss-feed'           => '$1 RSS fid',
'site-atom-feed'          => '$1 Atom fid',
'page-rss-feed'           => '"$1" RSS fid',
'page-atom-feed'          => '"$1" Atom fid',
'red-link-title'          => '$1 (stranica ne postoji)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Stranica',
'nstab-user'      => 'Korisnička stranica',
'nstab-media'     => 'Mediji',
'nstab-special'   => 'Posebna stranica',
'nstab-project'   => 'Stranica projekta',
'nstab-image'     => 'Datoteka',
'nstab-mediawiki' => 'Poruka',
'nstab-template'  => 'Šablon',
'nstab-help'      => 'Stranica pomoći',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Nema takve akcije',
'nosuchactiontext'  => 'Akcija navedena u URL-u nije valjana.
Možda ste pogriješili pri unosu URL-a ili ste slijedili pokvaren link.
Moguće je i da je ovo greška u softveru koji koristi {{SITENAME}}.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nospecialpagetext' => "<big>'''Zatražili ste nevaljanu posebnu stranicu.'''</big>

Lista valjanih posebnih stranica se može naći na [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Greška',
'databaseerror'        => 'Greška u bazi podataka',
'dberrortext'          => 'Desila se sintaksna greška upita baze.
Ovo se desilo zbog moguće greške u softveru.
Posljednji pokušani upit je bio: <blockquote><tt>$1</tt></blockquote> iz funkcije "<tt>$2</tt>".
MySQL je vratio grešku "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Desila se sintaksna greška upita baze.
Posljednji pokušani upit je bio:
"$1"
iz funkcije "$2".
MySQL je vratio grešku "$3: $4".',
'laggedslavemode'      => "'''Upozorenje''': Stranica ne mora sadržavati posljednja ažuriranja.",
'readonly'             => 'Baza podataka je zaključana',
'enterlockreason'      => 'Unesite razlog za zaključavanje, uključujući procjenu vremena otključavanja',
'readonlytext'         => 'Baza je trenutno zaključana za nove unose i ostale izmjene, vjerovatno zbog rutinskog održavanja, posle čega će biti vraćena u uobičajeno stanje.

Administrator koji ju je zaključao je ponudio ovo objašnjenje: $1',
'missing-article'      => 'U bazi podataka nije pronađen tekst stranice tražen pod nazivom "$1" $2.

Do ovoga dolazi kada se prati premještaj ili historija linka za stranicu koja je pobrisana.

U slučaju da se ne radi o gore navedenom, moguće je da ste pronašli grešku u programu.
Molimo Vas da ovo prijavite [[Special:ListUsers/sysop|administratoru]] sa navođenjem tačne adrese stranice',
'missingarticle-rev'   => '(izmjena#: $1)',
'missingarticle-diff'  => '(Razl: $1, $2)',
'readonly_lag'         => 'Baza podataka je zaključana dok se sekundarne baze podataka na serveru ne sastave sa glavnom.',
'internalerror'        => 'Interna pogreška',
'internalerror_info'   => 'Interna greška: $1',
'filecopyerror'        => 'Ne može se kopirati "$1" na "$2".',
'filerenameerror'      => 'Ne može se promjeniti ime datoteke "$1" u "$2".',
'filedeleteerror'      => 'Ne može se izbrisati datoteka "$1".',
'directorycreateerror' => 'Nije moguće napraviti direktorijum "$1".',
'filenotfound'         => 'Ne može se naći datoteka "$1".',
'fileexistserror'      => 'Nemoguće je stvoriti datoteku "$1": datoteka već postoji',
'unexpected'           => 'Neočekivana vrijednost: "$1"="$2".',
'formerror'            => 'Greška: ne može se poslati formular',
'badarticleerror'      => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'cannotdelete'         => 'Ne može se obrisati navedena stranica ili datoteka.
Moguće je da ju je neko drugi već obrisao.',
'badtitle'             => 'Loš naslov',
'badtitletext'         => 'Zatražena stranica je bila nevaljana, prazna ili neispravno povezana s među-jezičkim ili inter-wiki naslovom.
Može sadržavati jedno ili više slova koja se ne mogu koristiti u naslovima.',
'perfcached'           => 'Slijedeći podaci su keširani i možda neće biti u potpunosti ažurirani.',
'perfcachedts'         => 'Slijedeći podaci se nalaze u memoriji i zadnji put su ažurirani $1.',
'querypage-no-updates' => 'Ažuriranje ove stranice je isključeno.
Podaci koji se ovdje nalaze neće biti biti ažurirani.',
'wrong_wfQuery_params' => 'Netačni parametri za wfQuery()<br />
Funkcija: $1<br />
Pretraga: $2',
'viewsource'           => 'Pogledaj kod',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Akcija je usporena',
'actionthrottledtext'  => 'Kao anti-spam mjera, ograničene su vam izmjene u određenom vremenu, i trenutačno ste dostigli to ograničenje. Pokušajte ponovo poslije nekoliko minuta.',
'protectedpagetext'    => 'Ova stranica je zaključana da bi se spriječilo uređivanje.',
'viewsourcetext'       => 'Možete vidjeti i kopirati izvorni tekst ove stranice:',
'protectedinterface'   => 'Ova stranica sadrži tekst interfejsa za softver, pa je zaključana kako bi se spriječile zloupotrebe.',
'editinginterface'     => "'''Upozorenje:''' Mijenjate stranicu koja se koristi za tekst interfejsa za softver.
Promjene na ovoj stranici dovode i do promjena interfejsa za druge korisnike.
Za prijevode, molimo Vas koristite [http://translatewiki.net/wiki/Main_Page?setlang=bs translatewiki.net], projekt prijevoda za MediaWiki.",
'sqlhidden'            => '(SQL pretraga sakrivena)',
'cascadeprotected'     => 'Ova stranica je zaštićena od uređivanja, jer je uključena u {{PLURAL:$1|stranicu zaštićenu|stranice zaštićene}} od uređivanja sa uključenom kaskadnom opcijom:
$2',
'namespaceprotected'   => "Nemate dozvolu uređivati stranice imenskog prostora '''$1'''.",
'customcssjsprotected' => 'Nemate dozvolu za mijenjanje ove stranice jer sadrži osobne postavke nekog drugog korisnika.',
'ns-specialprotected'  => 'Posebne stranice se ne mogu uređivati.',
'titleprotected'       => 'Naslov stranice je zaštićen od postavljanja od strane korisnika [[User:$1|$1]].
Kao razlog je naveden "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Loša konfiguracija: nepoznati anti-virus program: ''$1''",
'virus-scanfailed'     => 'skeniranje nije uspjelo (code $1)',
'virus-unknownscanner' => 'nepoznati anti-virus program:',

# Login and logout pages
'logouttext'                 => "'''Sad ste odjavljeni.'''

Možete nastaviti da koristite {{SITENAME}} anonimno, ili se ponovo [[Special:UserLogin|prijaviti]] kao isti ili kao drugi korisnik.
Obratite pažnju da neke stranice mogu nastaviti da se prikazuju kao da ste još uvijek prijavljeni, dok ne očistite keš svog preglednika.",
'welcomecreation'            => '== Dobro došli, $1! ==
Vaš korisnički račun je napravljen.
Ne zaboravite izmijeniti vlastite [[Special:Preferences|{{SITENAME}} postavke]].',
'yourname'                   => 'Korisničko ime:',
'yourpassword'               => 'Lozinka/zaporka:',
'yourpasswordagain'          => 'Ponovno utipkajte lozinku/zaporku:',
'remembermypassword'         => 'Upamti moju lozinku na ovom kompjuteru za buduće posjete',
'yourdomainname'             => 'Vaš domen:',
'externaldberror'            => 'Došlo je do greške pri vanjskoj autorizaciji baze podataka ili vam nije dopušteno osvježavanje Vašeg vanjskog korisničkog računa.',
'login'                      => 'Prijavi se',
'nav-login-createaccount'    => 'Prijavi se / Registruj se',
'loginprompt'                => "Morate imati kolačiće ('''cookies''') omogućene da biste se prijavili na {{SITENAME}}.",
'userlogin'                  => 'Prijavi se / stvori korisnički račun',
'logout'                     => 'Odjavi me',
'userlogout'                 => 'Odjava',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => 'Nemate korisničko ime? $1.',
'nologinlink'                => 'Otvorite račun',
'createaccount'              => 'Napravi korisnički račun',
'gotaccount'                 => 'Imate račun? $1.',
'gotaccountlink'             => 'Prijavi se',
'createaccountmail'          => 'e-mailom',
'badretype'                  => 'Lozinke koje ste unijeli se ne poklapaju.',
'userexists'                 => 'Korisničko ime koje ste unijeli je već u upotrebi.
Molimo Vas da izaberete drugo ime.',
'loginerror'                 => 'Greška pri prijavljivanju',
'nocookiesnew'               => "Korisnički nalog je napravljen, ali niste prijavljeni.  
{{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili.  
Vi ste onemogućili kolačiće na Vašem računaru.  
Molimo Vas da ih omogućite, a onda se prijavite sa svojim novim korisničkim imenom i šifrom.",
'nocookieslogin'             => "{{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili.  
Vi ste onemogućili kolačiće na Vašem kompjuteru.  
Molimo Vas da ih omogućite i da pokušate ponovo sa prijavom.",
'noname'                     => 'Niste izabrali ispravno korisničko ime.',
'loginsuccesstitle'          => 'Prijavljivanje uspješno',
'loginsuccess'               => "'''Sad ste prijavljeni na {{SITENAME}} kao \"\$1\".'''",
'nosuchuser'                 => 'Ne postoji korisnik sa imenom "$1".
Korisnička imena razlikuju velika i mala slova.
Provjerite vaše kucanje ili [[Special:UserLogin/signup|napravite novi korisnički račun]].',
'nosuchusershort'            => 'Ne postoji korisnik sa imenom "<nowiki>$1</nowiki>".
Provjerite da li ste dobro ukucali.',
'nouserspecified'            => 'Morate izabrati korisničko ime.',
'wrongpassword'              => 'Unijeli ste neispravnu šifru.
Molimo Vas da pokušate ponovno.',
'wrongpasswordempty'         => 'Unesena šifra je bila prazna.
Molimo Vas da pokušate ponovno.',
'passwordtooshort'           => 'Vaša lozinka je prekratka.
Lozinka mora imati najmanje {{PLURAL:$1|1 znak|$1 znakova}}.',
'mailmypassword'             => 'Pošalji mi novu lozinku putem E-maila',
'passwordremindertitle'      => 'Nova privremena lozinka za {{SITENAME}}',
'passwordremindertext'       => 'Neko (vjerovatno Vi, sa IP adrese $1) je zahtjevao da vam pošaljemo novu šifru za {{SITENAME}}  ($4). Privremena šifra za korisnika "$2" je napravljena i glasi "$3". Ako ste to željeli, sad treba da se prijavite i promjenite šifru.
Vaša privremena šifra će isteči za {{PLURAL:$5|$5 dan|$5 dana}}.

Ako je neko drugi napravio ovaj zahtjev ili ako ste se sjetili vaše šifre i ne želite više da je promjenite, možete da ignorišete ovu poruku i da nastavite koristeći vašu staru šifru.',
'noemail'                    => 'Ne postoji adresa e-maila za korisnika "$1".',
'passwordsent'               => 'Nova šifra je poslata na e-mail adresu korisnika "$1".
Molimo Vas da se prijavite pošto je primite.',
'blocked-mailpassword'       => 'Da bi se spriječila nedozvoljena akcija, Vašoj IP adresi je onemogućeno uređivanje stranica kao i mogućnost zahtijevanje nove šifre.',
'eauthentsent'               => 'Na navedenu adresu poslan je e-mail s potvrdom.  
Prije nego što pošaljemo daljnje poruke, molimo vas da otvorite e-mail i slijedite u njemu sadržana uputstva da potvrdite da ste upravo vi kreirali korisnički račun.',
'throttled-mailpassword'     => 'Već Vam je poslan e-mail za promjenu šifre u {{PLURAL:$1|zadnjih sat vremena|zadnja $1 sata|zadnjih $1 sati}}.
Da bi se spriječila zloupotreba, može se poslati samo jedan e-mail za promjenu šifre {{PLURAL:$1|svakih sat vremena|svaka $1 sata|svakih $1 sati}}.',
'mailerror'                  => 'Greška pri slanju e-pošte: $1',
'acct_creation_throttle_hit' => 'Posjetioci na ovoj wiki koji koriste Vašu IP adresu su već napravili {{PLURAL:$1|$1 račun|$1 računa}} u zadnjih nekoliko dana, što je najveći broj dopuštenih napravljenih računa za ovaj period.
Kao rezultat, posjetioci koji koriste ovu IP adresu ne mogu trenutno praviti više računa.',
'emailauthenticated'         => 'Vaša e-mail adresa je autentificirana na $2 u $3.',
'emailnotauthenticated'      => 'Vaša e-mail adresa još nije autentificirana.
Nijedan e-mail neće biti poslan za bilo koju uslugu od slijedećih.',
'noemailprefs'               => 'Unesite e-mail adresu za osposobljavanje slijedećih usluga.',
'emailconfirmlink'           => 'Potvrdite Vašu e-mail adresu',
'invalidemailaddress'        => 'Ova e-mail adresa ne može biti prihvaćena jer je u neodgovarajućem obliku.
Molimo vas da unesete ispravnu adresu ili ostavite prazno polje.',
'accountcreated'             => 'Korisnički račun je napravljen',
'accountcreatedtext'         => 'Korisnički račun za $1 je napravljen.',
'createaccount-title'        => 'Pravljenje korisničkog računa za {{SITENAME}}',
'createaccount-text'         => 'Neko je napravio korisnički račun za vašu e-mail adresu na {{SITENAME}} ($4) sa imenom "$2", i sa šifrom "$3".
Trebali biste se prijaviti i promjeniti šifru.

Možete ignorisati ovu poruku, ako je korisnički račun napravljen greškom.',
'login-throttled'            => 'Previše puta ste pokušali unijeti šifru za korisnički račun.
Molimo vas da sačekate prije nego što pokušate ponovo.',
'loginlanguagelabel'         => 'Jezik: $1',

# Password reset dialog
'resetpass'                 => 'Promijeni korisničku šifru',
'resetpass_announce'        => 'Prijavili ste se sa privremenim kodom koji ste dobili na e-mail.
Da biste završili prijavu, morate unijeti novu šifru ovdje:',
'resetpass_header'          => 'Obnovi lozinku za račun',
'oldpassword'               => 'Stara šifra:',
'newpassword'               => 'Nova šifra:',
'retypenew'                 => 'Ukucajte ponovo novu šifru:',
'resetpass_submit'          => 'Odredi lozinku i prijavi se',
'resetpass_success'         => 'Vaša šifra je uspiješno promjenjena! Prijava u toku...',
'resetpass_forbidden'       => 'Šifre ne mogu biti promjenjene',
'resetpass-no-info'         => 'Morate biti prijavljeni da bi ste pristupili ovoj stranici direktno.',
'resetpass-submit-loggedin' => 'Promijeni lozinku',
'resetpass-wrong-oldpass'   => 'Privremena ili trenutna lozinka nije valjana.  
Možda ste već uspješno promijenili Vašu lozinku ili ste tražili novu privremenu lozinku.',
'resetpass-temp-password'   => 'Privremena lozinka:',

# Edit page toolbar
'bold_sample'     => 'Podebljan tekst',
'bold_tip'        => 'Podebljan tekst',
'italic_sample'   => 'Kurzivan tekst',
'italic_tip'      => 'Kurzivan tekst',
'link_sample'     => 'Naslov linka',
'link_tip'        => 'Interni link',
'extlink_sample'  => 'http://www.example.com naslov linka',
'extlink_tip'     => 'Eksterni link (zapamti prefiks http:// )',
'headline_sample' => 'Tekst naslova',
'headline_tip'    => 'Podnaslov',
'math_sample'     => 'Unesite formulu ovdje',
'math_tip'        => 'Matematička formula (LaTeX)',
'nowiki_sample'   => 'Dodaj neformatirani tekst ovdje',
'nowiki_tip'      => 'Ignoriraj wiki formatiranje',
'image_tip'       => 'Uklopljena datoteka/fajl',
'media_tip'       => 'Putanja ka multimedijalnoj datoteci/fajlu',
'sig_tip'         => 'Vaš potpis sa trenutnim vremenom',
'hr_tip'          => 'Horizontalna linija (koristite rijetko)',

# Edit pages
'summary'                          => 'Sažetak:',
'subject'                          => 'Tema/naslov:',
'minoredit'                        => 'Ovo je manja izmjena',
'watchthis'                        => 'Prati ovu stranicu',
'savearticle'                      => 'Snimi stranicu',
'preview'                          => 'Pretpregled',
'showpreview'                      => 'Prikaži izgled',
'showlivepreview'                  => 'Pretpregled uživo',
'showdiff'                         => 'Prikaži izmjene',
'anoneditwarning'                  => "'''Upozorenje:''' Niste prijavljeni.
Vaša IP adresa će biti zabilježena u historiji ove stranice.",
'missingsummary'                   => "'''Podsjećanje:''' Niste unijeli sažetak izmjene.
Ako kliknete na Sačuvaj/Snimi, Vaša izmjena će biti snimljena bez sažetka.",
'missingcommenttext'               => 'Molimo unesite komentar ispod.',
'missingcommentheader'             => "'''Podsjetnik:''' Niste napisali temu/naslov za ovaj komentar.
Ako ponovo kliknete na '''Snimi stranicu''', Vaše izmjene će biti snimljene bez teme/naslova.",
'summary-preview'                  => 'Pretpregled sažetka:',
'subject-preview'                  => 'Pretpregled teme/naslova:',
'blockedtitle'                     => 'Korisnik je blokiran',
'blockedtext'                      => "<big>'''Vaše korisničko ime ili IP adresa je blokirana.'''</big>

Blokada izvršena od strane $1.
Dati razlog je slijedeći: ''$2''.

*Početak blokade: $8
*Kraj perioda blokade: $6
*Ime blokiranog korisnika: $7

Možete kontaktirati $1 ili nekog drugog [[{{MediaWiki:Grouppage-sysop}}|administratora]] da biste razgovarali o blokadi.

Ne možete koristiti opciju ''Pošalji e-mail korisniku'' osim ako niste unijeli e-mail adresu u [[Special:Preferences|Vaše postavke]].
Vaša trenutna IP adresa je $3, a oznaka blokade je #$5.
Molimo Vas da navedete gornje podatke u zahtjevu za deblokadu.",
'autoblockedtext'                  => 'Vaša IP adresa je automatski blokirana jer je korištena od strane drugog korisnika, a blokirao ju je $1.
Naveden je slijedeći razlog:

:\'\'$2\'\'

* Početak blokade: $8
* Kraj blokade: $6
* Blokirani korisnik: $7

Možete kontaktirati $1 ili nekog drugog iz grupe [[{{MediaWiki:Grouppage-sysop}}|administratora]] i zahtijevati da Vas deblokira.

Zapamtite da ne možete koristiti opciju "pošalji e-mail ovom korisniku" sve dok ne unesete validnu e-mail adresu pri registraciji u Vašim [[Special:Preferences|korisničkim postavkama]] te Vas ne spriječava ga je koristite.

Vaša trenutna IP adresa je $3, a ID blokade je $5.
Molimo da navedete sve gore navedene detalje u zahtjevu za deblokadu.',
'blockednoreason'                  => 'razlog nije naveden',
'blockedoriginalsource'            => "Izvor '''$1''' je prikazan ispod:",
'blockededitsource'                => "Sadržaj '''vaših izmjena''' na '''$1''' je prikazan ispod:",
'whitelistedittitle'               => 'Za uređivanje je obavezna prijava',
'whitelistedittext'                => 'Da bi ste uređivali stranice, morate se $1.',
'confirmedittext'                  => 'Morate potvrditi Vašu e-mail adresu prije nego počnete mijenjati stranice.
Molimo da postavite i verifikujete Vašu e-mail adresu putem Vaših [[Special:Preferences|korisničkih opcija]].',
'nosuchsectiontitle'               => 'Nema takve sekcije',
'nosuchsectiontext'                => 'Pokušali ste uređivati sekciju koja ne postoji.
Pošto sekcija $1 ne postoji, nije moguće spremiti Vaše promjene.',
'loginreqtitle'                    => 'Potrebno je prijavljivanje',
'loginreqlink'                     => 'prijavi se',
'loginreqpagetext'                 => 'Morate $1 da bi ste vidjeli druge stranice.',
'accmailtitle'                     => 'Lozinka poslana.',
'accmailtext'                      => "Nasumično odabrana lozinka za nalog [[User talk:$1|$1]] je poslata na adresu $2.

Lozinka za ovaj novi račun može biti promijenjena na stranici ''[[Special:ChangePassword|izmjene šifre]]'' nakon prijave.",
'newarticle'                       => '(Novi)',
'newarticletext'                   => "Preko linka ste došli na stranicu koja još uvijek ne postoji.
* Ako želite stvoriti stranicu, počnite tipkati u okviru dolje (v. [[{{MediaWiki:Helppage}}|stranicu za pomoć]] za više informacija).
* Ukoliko ste došli greškom, pritisnike dugme '''Nazad''' ('''back''') na vašem pregledniku.",
'anontalkpagetext'                 => "----''Ovo je stranica za razgovor za anonimnog korisnika koji još nije napravio račun ili ga ne koristi.
Zbog toga moramo da koristimo brojčanu IP adresu kako bismo identifikovali njega ili nju.
Takvu adresu može dijeliti više korisnika.
Ako ste anonimni korisnik i mislite da su vam upućene nebitne primjedbe, molimo Vas da [[Special:UserLogin/signup|napravite račun]] ili se [[Special:UserLogin|prijavite]] da biste izbjegli buduću zabunu sa ostalim anonimnim korisnicima.''",
'noarticletext'                    => 'Na ovoj stranici trenutno nema teksta.
Možete [[Special:Search/{{PAGENAME}}|tražiti naslov ove stranice]] u drugim stranicama,
<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} pretraživati srodne registre],
ili [{{fullurl:{{FULLPAGENAME}}|action=edit}} urediti ovu stranicu]</span>.',
'userpage-userdoesnotexist'        => 'Korisnički račun "$1" nije registrovan.
Molimo provjerite da li želite napraviti/izmijeniti ovu stranicu.',
'clearyourcache'                   => "'''Pažnja: Nakon što snimite izmjene, morate \"osvježiti\" keš memoriju vašeg pretraživača da bi ste vidjeli nova podešenja.'''
'''Mozilla / Firefox / Safari:''' držite ''Shift'' tipku i kliknite na ''Reload'' dugme ili ''Ctrl-R'' ili ''Ctrl-F5'' (''Command-R'' na Macintoshu);
'''Konqueror:''' klikni na ''Reload'' ili pritisnite dugme ''F5'';
'''Opera:''' očistite \"keš\" preko izbornika ''Tools → Preferences'';
'''Internet Explorer:''' držite tipku ''Ctrl'' i kliknite na ''Refresh'' ili pritisnite ''Ctrl-F5''.",
'usercsspreview'                   => "'''Zapamtite ovo je samo izgled Vašeg CSS-a.'''
'''Još uvijek nije snimljen!'''",
'userjspreview'                    => "'''Zapamite da je ovo samo test/pretpregled Vaše JavaScript-e.'''
'''Još uvijek nije snimljena!'''",
'userinvalidcssjstitle'            => "'''Upozorenje:''' Ne postoji interfejs (skin) pod imenom \"\$1\".
Ne zaboravite da imena stranica s .css i .js kodom počinju malim slovom, npr. {{ns:user}}:Foo/monobook.css, a ne {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Osvježeno)',
'note'                             => "'''Napomena:'''",
'previewnote'                      => "'''Upamtite da je ovo samo pretpregled.'''
Vaše izmjene još uvijek nisu snimljene!",
'previewconflict'                  => 'Ovaj pretpregled reflektuje tekst u gornjem polju
kako će izgledati ako pritisnete "Snimi stranicu".',
'session_fail_preview'             => "'''Izvinjavamo se! Nismo mogli obraditi vašu izmjenu zbog gubitka podataka o prijavi. Molimo pokušajte ponovno. Ako i dalje ne bude radilo, pokušajte se [[Special:UserLogout|odjaviti]] i ponovno prijaviti.'''",
'session_fail_preview_html'        => "'''Žao nam je! Nismo mogli da obradimo vašu izmjenu zbog gubitka podataka.'''

''Zbog toga što {{SITENAME}} ima omogućen izvorni HTML, predpregled je sakriven kao predostrožnost protiv JavaScript napada.''

'''Ako ste pokušali da napravite pravu izmjenu, molimo pokušajte ponovo. Ako i dalje ne radi, pokušajte da se [[Special:UserLogout|odjavite]] i ponovo prijavite.'''",
'token_suffix_mismatch'            => "'''Vaša izmjena nije prihvaćena jer je Vaš web preglednik ubacio znakove interpunkcije u token uređivanja.'''
Izmjena je odbačena da bi se spriječilo uništavanje teksta stranice.
To se događa ponekad kad korisite problematični anonimni proxy koji je baziran na web-u.",
'editing'                          => 'Uređujete $1',
'editingsection'                   => 'Uređujete $1 (sekciju)',
'editingcomment'                   => 'Uređujete $1 (nova sekcija)',
'editconflict'                     => 'Sukobljenje izmjene: $1',
'explainconflict'                  => 'Neko drugi je promujenio ovu stranicu otkad ste Vi počeli da je mijenjate.
Gornje tekstualno polje sadrži tekst stranice koji trenutno postoji.
Vaše izmjene su prikazane u donjem tekstu.
Moraćete da unesete svoje promjene u postojeći tekst.
<b>Samo</b> tekst u gornjem tekstualnom polju će biti snimljen kad
pritisnete "Snimi stranicu".<br />',
'yourtext'                         => 'Vaš tekst',
'storedversion'                    => 'Uskladištena verzija',
'nonunicodebrowser'                => "'''UPOZORENJE: Vaš preglednik ne podržava Unicode zapis znakova.
Molimo Vas promijenite ga prije sljedećeg uređivanja članaka. Znakovi koji nisu po ASCII standardu će se u prozoru za izmjene pojaviti kao heksadecimalni kodovi.'''",
'editingold'                       => "'''PAŽNJA:  Vi mijenjate stariju reviziju ove stranice.
Ako je snimite, sve promjene učinjene od ove revizije će biti izgubljene.'''",
'yourdiff'                         => 'Razlike',
'copyrightwarning'                 => "Molimo da uzmete u obzir kako se smatra da su svi doprinosi u {{SITENAME}} izdani pod $2 (v. $1 za detalje).
Ukoliko ne želite da vaše pisanje bude nemilosrdno uređivano i redistribuirano po tuđoj volji, onda ga nemojte ovdje objavljivati.<br />
Također obećavate kako ste ga napisali sami ili kopirali iz izvora u javnoj domeni ili sličnog slobodnog izvora.
'''NEMOJTE SLATI RAD ZAŠTIĆEN AUTORSKIM PRAVIMA BEZ DOZVOLE!'''",
'templatesused'                    => 'Šabloni korišteni na ovoj stranici:',
'templatesusedpreview'             => 'Šabloni korišteni u ovom pretpregledu:',
'template-protected'               => '(zaštićeno)',
'template-semiprotected'           => '(polu-zaštićeno)',
'hiddencategories'                 => 'Ova stranica pripada {{PLURAL:$1|1 skrivenoj kategoriji|$1 skrivenim kategorijama}}:',
'permissionserrorstext-withaction' => 'Nemate dozvolu za $2, zbog {{PLURAL:$1|sljedećeg|sljedećih}} razloga:',
'moveddeleted-notice'              => 'Ova stranica je obrisana.
Registar brisanja za stranicu je dolje naveden radi referenci.',

# History pages
'viewpagelogs'           => 'Pogledaj protokole ove stranice',
'currentrev-asof'        => 'Trenutna revizija na dan $1',
'revisionasof'           => 'Izmjena od $1',
'previousrevision'       => '← Starija revizija',
'nextrevision'           => 'Novija izmjena →',
'currentrevisionlink'    => 'Trenutna verzija',
'cur'                    => 'tren',
'last'                   => 'preth',
'histlegend'             => "Odabir razlika: označite radio dugme verzija za usporedbu i pritisnite enter ili dugme na dnu.<br />
Objašnjenje: '''({{int:cur}})''' = razlika sa trenutnom verzijom,
'''({{int:last}})''' = razlika sa prethodnom verzijom, '''{{int:minoreditletter}}''' = manja izmjena.",
'history-fieldset-title' => 'Pretraga historije',
'histfirst'              => 'Najstarije',
'histlast'               => 'Najnovije',

# Revision deletion
'rev-delundel'   => 'pokaži/sakrij',
'revdel-restore' => 'promijeni dostupnost',

# Merge log
'revertmerge' => 'Ukini spajanje',

# Diffs
'history-title'           => 'Historija izmjena stranice "$1"',
'difference'              => '(Razlika između revizija)',
'lineno'                  => 'Linija $1:',
'compareselectedversions' => 'Uporedite označene verzije',
'editundo'                => 'ukloni ovu izmjenu',

# Search results
'searchresults'                    => 'Rezultati pretrage',
'searchresults-title'              => 'Rezultati pretrage za "$1"',
'searchresulttext'                 => 'Za više informacija o pretraživanju {{SITENAME}}, v. [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Tražili ste \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|sve stranice koje počinju sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sve stranice koje vode do "$1"]])',
'searchsubtitleinvalid'            => "Tražili ste '''$1'''",
'noexactmatch'                     => "'''Ne postoji stranica sa naslovom \"\$1\".'''
Možete [[:\$1|stvoriti ovu stranicu]].",
'noexactmatch-nocreate'            => "'''Ne postoji stranica sa naslovom \"\$1\".'''",
'notitlematches'                   => 'Nijedan naslov stranice ne odgovara',
'notextmatches'                    => 'Tekst stranice ne odgovara',
'prevn'                            => 'prethodna {{PLURAL:$1|$1}}',
'nextn'                            => 'sljedećih {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Pogledaj ($1) ($2) ($3)',
'searchmenu-legend'                => 'Opcije pretrage',
'searchmenu-exists'                => "'''Postoji stranica pod nazivom \"[[\$1]]\" na ovoj wiki'''",
'searchmenu-new'                   => "'''Napravi stranicu \"[[:\$1|\$1]]\" na ovoj wiki!'''",
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Pregledaj stranice sa ovim prefiksom]]',
'searchprofile-everything'         => 'Sve',
'searchprofile-advanced'           => 'Napredno',
'searchprofile-articles-tooltip'   => 'Pretraga u $1',
'searchprofile-project-tooltip'    => 'Pretraga u $1',
'searchprofile-images-tooltip'     => 'Traži datoteke',
'searchprofile-everything-tooltip' => 'Pretraži sve sadržaje (ukljujući i stranice za razgovor)',
'searchprofile-advanced-tooltip'   => 'Traži u ostalim imenskim prostorima',
'search-result-size'               => '$1 ({{PLURAL:$2|1 riječ|$2 riječi}})',
'search-result-score'              => 'Relevantnost: $1%',
'search-redirect'                  => '(preusmjeravanje $1)',
'search-section'                   => '(sekcija $1)',
'search-suggest'                   => 'Da li ste mislili: $1',
'search-interwiki-caption'         => 'Srodni projekti',
'search-interwiki-default'         => '$1 rezultati:',
'search-interwiki-more'            => '(više)',
'search-mwsuggest-enabled'         => 'sa sugestijama',
'search-mwsuggest-disabled'        => 'bez sugestija',
'search-relatedarticle'            => 'Povezano',
'mwsuggest-disable'                => 'Onemogući AJAX prijedloge',
'showingresults'                   => "Dole {{PLURAL:$1|je prikazan '''1''' rezultat|su prikazana '''$1''' rezultata|je prikazano '''$1''' rezultata}} počev od '''$2'''.",
'showingresultsnum'                => "Dolje {{PLURAL:$3|je prikazan '''1''' rezultat|su prikazana '''$3''' rezultata|je prikazano '''$3''' rezultata}} počev od #'''$2'''.",
'nonefound'                        => "'''Napomene''': Samo neki imenski prostori se pretražuju po početnim postavkama.
Pokušajte u svoju pretragu staviti ''all:'' da se pretražuje cjelokupan sadržaj (uključujući stranice za razgovor, šablone/predloške itd.), ili koristite imenski prostor kao prefiks.",
'search-nonefound'                 => 'Nisu pronađeni rezultati koji odgovaraju upitu.',
'powersearch'                      => 'Napredna pretraga',
'powersearch-legend'               => 'Napredna pretraga',
'powersearch-ns'                   => 'Pretraga u imenskim prostorima:',
'powersearch-redir'                => 'Pokaži spisak preusmjerenja',
'powersearch-field'                => 'Traži',
'search-external'                  => 'Vanjska/spoljna pretraga',
'searchdisabled'                   => 'Pretraga teksta na ovoj Wiki je trenutno onemogućena. 
U međuvremenu možete pretraživati preko Googlea.
Uzmite u obzir da njegovi indeksi za ovu Wiki ne moraju biti ažurirani.',

# Preferences page
'preferences'               => 'Postavke',
'mypreferences'             => 'Moje postavke',
'youremail'                 => 'E-mail:',
'username'                  => 'Korisničko ime:',
'uid'                       => 'Korisnički ID:',
'prefs-memberingroups'      => 'Član {{PLURAL:$1|grupe|grupa}}:',
'yourrealname'              => 'Vaše pravo ime:',
'yourlanguage'              => 'Jezik:',
'yournick'                  => 'Nadimak (za potpise):',
'badsig'                    => 'Loš sirovi potpis.
Provjerite HTML tagove.',
'badsiglength'              => 'Vaš potpis je predug.
Mora biti manji od $1 {{PLURAL:$1|znaka|znaka|znakova}}.',
'yourgender'                => 'Spol:',
'gender-unknown'            => 'neodređen',
'gender-male'               => 'Muški',
'gender-female'             => 'Ženski',
'prefs-help-gender'         => 'Opcionalno: koristi se za ispravke gramatičkog roda u porukama softvera. 
Ova informacija će biti javna.',
'email'                     => 'E-mail',
'prefs-help-realname'       => 'Pravo ime nije obavezno.
Ako izaberete da date ime, biće korišteno za pripisivanje Vašeg rada.',
'prefs-help-email'          => 'E-mail adresa je opcionalna, unesena adresa Vam omogućava da Vam se pošalje nova šifra u slučaju da je izgubite ili zaboravite.
Također omogućuje drugim korisnicima da vas kontaktiraju preko Vaše korisničke stranice ili stranice za razgovor bez otkrivanja Vašeg identiteta.',
'prefs-help-email-required' => 'Neophodno je navesti e-mail adresu.',

# Groups
'group-sysop' => 'Administratori',

# User rights log
'rightslog' => 'Registar korisničkih prava',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'uređujete ovu stranicu',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',
'recentchanges'                  => 'Nedavne izmjene',
'recentchanges-legend'           => 'Postavke za Nedavne promjene',
'recentchanges-feed-description' => 'Praćenje nedavnih izmjena na ovom wikiju u ovom feedu.',
'rcnote'                         => "Ispod {{PLURAL:$1|je '''$1''' promjena|su '''$1''' zadnje promjene|su '''$1''' zadnjih promjena}} u {{PLURAL:$2|posljednjem '''$2''' danu|posljednja '''$2''' dana|posljednjih '''$2''' dana}}, od $4, $5.",
'rclistfrom'                     => 'Prikaži nove izmjene počevši od $1',
'rcshowhideminor'                => '$1 male izmjene',
'rcshowhidebots'                 => '$1 botove',
'rcshowhideliu'                  => '$1 prijavljene korisnike',
'rcshowhideanons'                => '$1 anonimne korisnike',
'rcshowhidemine'                 => '$1 moje izmjene',
'rclinks'                        => 'Prikaži najskorijih $1 izmjena u posljednjih $2 dana<br />$3',
'diff'                           => 'razl',
'hist'                           => 'hist',
'hide'                           => 'Sakrij',
'show'                           => 'Prikaži',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Pokaži detalje (neophodan JavaScript)',
'rc-enhanced-hide'               => 'Sakrij detalje',

# Recent changes linked
'recentchangeslinked'         => 'Srodne izmjene',
'recentchangeslinked-feed'    => 'Srodne izmjene',
'recentchangeslinked-toolbox' => 'Srodne izmjene',
'recentchangeslinked-title'   => 'Srodne promjene sa "$1"',
'recentchangeslinked-summary' => "Ova posebna stranica prikazuje promjene na povezanim stranicama. 
Stranice koje su na vašem [[Special:Watchlist|spisku praćenja]] su '''podebljane'''.",
'recentchangeslinked-page'    => 'Naslov stranice:',
'recentchangeslinked-to'      => 'Pokaži promjene stranica koji su povezane sa datom stranicom',

# Upload
'upload'          => 'Postavi datoteku',
'uploadbtn'       => 'Postavi datoteku',
'uploadlogpage'   => 'Registar postavljanja',
'uploadedimage'   => 'postavljeno "[[$1]]"',
'watchthisupload' => 'Prati ovu stranicu',

# File description page
'file-anchor-link'          => 'Datoteka',
'filehist'                  => 'Historija datoteke',
'filehist-help'             => 'Kliknite na datum/vrijeme da vidite kako je tada izgledala datoteka/fajl.',
'filehist-revert'           => 'vrati',
'filehist-current'          => 'trenutno',
'filehist-datetime'         => 'Datum/Vrijeme',
'filehist-thumb'            => 'Smanjeni pregled',
'filehist-thumbtext'        => 'Smanjeni pregled verzije na dan $1',
'filehist-user'             => 'Korisnik',
'filehist-dimensions'       => 'Dimenzije',
'filehist-comment'          => 'Komentar',
'imagelinks'                => 'Linkovi datoteke',
'linkstoimage'              => '{{PLURAL:$1|Sljedeća stranica koristi|Sljedećih $1 stranica koriste}} ovu sliku:',
'sharedupload'              => 'Ova datoteka/fajl je sa $1 i mogu je koristiti ostali projekti.',
'uploadnewversion-linktext' => 'Postavite novu verziju ove datoteke/fajla',

# File reversion
'filerevert'        => 'Vrati $1',
'filerevert-legend' => 'Vrati datoteku/fajl',
'filerevert-submit' => 'Vrati',

# File deletion
'filedelete-otherreason'      => 'Ostali/dodatni razlog/zi:',
'filedelete-reason-otherlist' => 'Ostali razlog/zi',

# Random page
'randompage'         => 'Slučajna stranica',
'randompage-nopages' => 'Nema stranica u imenskom prostoru "$1".',

# Random redirect
'randomredirect'         => 'Slučajno preusmjerenje',
'randomredirect-nopages' => 'Nema preusmjerenja u imenskom prostoru "$1".',

# Statistics
'statistics' => 'Statistike',

'withoutinterwiki'         => 'Stranice bez linkova na drugim jezicima',
'withoutinterwiki-summary' => 'Sljedeće stranice nisu povezane sa verzijama na drugim jezicima.',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Prikaži',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|bajt|bajtova}}',
'nmembers'             => '$1 {{PLURAL:$1|član|članova}}',
'mostlinked'           => 'Stranice sa najviše linkova',
'mostlinkedcategories' => 'Kategorije sa najviše linkova',
'mostlinkedtemplates'  => 'Šabloni sa najviše linkova',
'mostcategories'       => 'Stranice sa najviše kategorija',
'mostimages'           => 'Datoteke sa najviše linkova',
'mostrevisions'        => 'Stranice sa najviše izmjena',
'prefixindex'          => 'Sve stranice sa prefiksom',
'newpages'             => 'Nove stranice',
'move'                 => 'Premjesti',
'movethispage'         => 'Premjesti ovu stranicu',
'pager-newer-n'        => '{{PLURAL:$1|novija 1|novije $1}}',
'pager-older-n'        => '{{PLURAL:$1|starija 1|starije $1}}',

# Book sources
'booksources'               => 'Književni izvori',
'booksources-search-legend' => 'Traži književne izvore',
'booksources-go'            => 'Idi',

# Special:Log
'log' => 'Registri',

# Special:AllPages
'allpages'       => 'Sve stranice',
'alphaindexline' => '$1 do $2',
'prevpage'       => 'Prethodna stranica ($1)',
'allpagesfrom'   => 'Prikaži stranice koje počinju od:',
'allpagesto'     => 'Pokaži stranice koje završavaju na:',
'allarticles'    => 'Sve stranice',
'allpagesprev'   => 'Prethodna',
'allpagessubmit' => 'Idi',

# Special:LinkSearch
'linksearch' => 'Vanjski/spoljašni linkovi',

# Special:ListUsers
'listusers-submit' => 'Pokaži',

# Special:Log/newusers
'newuserlogpage'          => 'Registar novih korisnika',
'newuserlog-create-entry' => 'Novi korisnički račun',

# Special:ListGroupRights
'listgrouprights-members' => '(lista članova)',

# E-mail user
'emailuser' => 'Pošalji E-mail ovom korisniku',

# Watchlist
'watchlist'         => 'Moj spisak praćenja',
'mywatchlist'       => 'Moj spisak praćenja',
'watchlistfor'      => "(za '''$1''')",
'addedwatch'        => 'Dodano na listu praćenih stranica',
'addedwatchtext'    => "Stranica \"[[:\$1]]\" je dodana [[Special:Watchlist|vašoj listi praćenih stranica]].
Buduće promjene ove stranice i njoj pridružene stranice za razgovor će biti navedene ovdje, te će stranica izgledati '''podebljana''' u [[Special:RecentChanges|listi nedavnih]] izmjena kako bi se lakše uočila.",
'removedwatch'      => 'Uklonjeno s liste praćenja',
'removedwatchtext'  => 'Stranica "[[:$1]]" je uklonjena s [[Special:Watchlist|vaše liste praćenja]].',
'watch'             => 'Prati',
'watchthispage'     => 'Prati ovu stranicu',
'unwatch'           => 'Prekini praćenje',
'watchlist-details' => '{{PLURAL:$1|$1 stranica praćena|$1 stranice praćene|$1 stranica praćeno}} ne računajući stranice za razgovor.',
'wlshowlast'        => 'Prikaži posljednjih $1 sati $2 dana $3',
'watchlist-options' => 'Opcije liste praćenja',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Pratim…',
'unwatching' => 'Ne pratim…',

# Delete
'deletepage'            => 'Izbrišite stranicu',
'confirmdeletetext'     => 'Upravo ćete obrisati stranicu sa svom njenom historijom.
Molimo da potvrdite da ćete to učiniti, da razumijete posljedice te da to činite u skladu sa [[{{MediaWiki:Policy-url}}|pravilima]].',
'actioncomplete'        => 'Akcija završena',
'deletedtext'           => '"<nowiki>$1</nowiki>" je obrisan/a.
V. $2 za registar nedavnih brisanja.',
'deletedarticle'        => 'obrisan "[[$1]]"',
'dellogpage'            => 'Registar brisanja',
'deletionlog'           => 'registar brisanja',
'deletecomment'         => 'Razlog za brisanje:',
'deleteotherreason'     => 'Ostali/dodatni razlog/zi:',
'deletereasonotherlist' => 'Ostali razlog/zi',

# Rollback
'rollbacklink' => 'vrati',
'cantrollback' => 'Nemoguće je vratiti izmjenu;
posljednji kontributor je jedini na ovoj stranici.',

# Protect
'protectlogpage'              => 'Registar zaštite',
'protectedarticle'            => '"[[$1]]" zaštićeno',
'modifiedarticleprotection'   => 'promijenjen nivo zaštite za "[[$1]]"',
'protectcomment'              => 'Komentar:',
'protectexpiry'               => 'Ističe:',
'protect_expiry_invalid'      => 'Upisani vremenski rok nije valjan.',
'protect_expiry_old'          => 'Upisani vremenski rok je u prošlosti.',
'protect-unchain'             => 'Deblokirajte dozvole premještanja',
'protect-text'                => "Ovdje možete gledati i izmijeniti nivo zaštite za stranicu '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Nemate ovlasti za mijenjanje stepena zaštite.
Slijede trenutne postavke stranice '''$1''':",
'protect-cascadeon'           => 'Ova stranica je trenutno zaštićena jer je uključena u {{PLURAL:$1|stranicu, koja ima|stranice, koje imaju|stranice, koje imaju}} uključenu prenosnu (kaskadnu) zaštitu.  
Možete promijeniti stepen zaštite ove stranice, ali to neće uticati na prenosnu zaštitu.',
'protect-default'             => 'Dozvoli svim korisnicima',
'protect-fallback'            => 'Potrebno je imati "$1" ovlasti',
'protect-level-autoconfirmed' => 'Blokiraj nove i neregistrovane korisnike',
'protect-level-sysop'         => 'Samo administratori',
'protect-summary-cascade'     => 'prenosna (kaskadna) zaštita',
'protect-expiring'            => 'ističe $1 (UTC)',
'protect-cascade'             => 'Zaštiti sve stranice koje su uključene u ovu (kaskadna zaštita)',
'protect-cantedit'            => 'Ne možete mijenjati nivo zaštite ove stranice, jer nemate prava da je uređujete..',
'protect-otherreason'         => 'Ostali/dodatni razlog/zi:',
'protect-otherreason-op'      => 'Ostali/dodatni razlog/zi:',
'restriction-type'            => 'Dozvola:',
'restriction-level'           => 'Nivo ograničenja:',

# Restrictions (nouns)
'restriction-move' => 'Premjesti',

# Undelete
'undelete'         => 'Pregledaj izbrisane stranice',
'viewdeletedpage'  => 'Pregledaj izbrisane stranice',
'undeletelink'     => 'pogledaj/vrati',
'undeleteinvert'   => 'Sve osim odabranog',
'undeletedarticle' => 'vraćen "[[$1]]"',

# Namespace form on various pages
'invert'         => 'Sve osim odabranog',
'blanknamespace' => '(Glavno)',

# Contributions
'contributions'       => 'Doprinosi korisnika',
'contributions-title' => 'Korisnički doprinosi od $1',
'mycontris'           => 'Moji doprinosi',
'contribsub2'         => 'Za $1 ($2)',
'uctop'               => '(vrh)',
'month'               => 'Od mjeseca (i ranije):',
'year'                => 'Od godine (i ranije):',

'sp-contributions-newbies'  => 'Pokaži doprinose samo novih korisnika',
'sp-contributions-blocklog' => 'registar blokiranja',
'sp-contributions-talk'     => 'Razgovor',
'sp-contributions-search'   => 'Pretraga doprinosa',
'sp-contributions-username' => 'IP adresa ili korisničko ime:',
'sp-contributions-submit'   => 'Traži',

# What links here
'whatlinkshere'            => 'Šta je povezano ovdje',
'whatlinkshere-title'      => 'Stranice koje vode na "$1"',
'whatlinkshere-page'       => 'Stranica:',
'linkshere'                => "Sljedeće stranice vode na '''[[:$1]]''':",
'isredirect'               => 'preusmjeri stranicu',
'isimage'                  => 'link datoteke',
'whatlinkshere-prev'       => '{{PLURAL:$1|prethodni|prethodna|prethodnih}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|sljedeći|sljedeća|sljedećih}} $1',
'whatlinkshere-links'      => '← linkovi',
'whatlinkshere-hideredirs' => '$1 preusmjerenja',
'whatlinkshere-hidelinks'  => '$1 linkove',
'whatlinkshere-filters'    => 'Filteri',

# Block/unblock
'blockip'                  => 'Blokiraj korisnika',
'ipbreasonotherlist'       => 'Ostali razlog/zi',
'ipboptions'               => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 sedmica:1 week,2 sedmice:2 weeks,1 mjesec:1 month,3 mjeseca:3 months,6 mjeseci:6 months,1 godine:1 year,zauvijek:infinite',
'ipbotherreason'           => 'Ostali/dodatni razlog/zi:',
'ipblocklist'              => 'Blokirane IP adrese i korisnička imena',
'blocklink'                => 'blokirajte',
'unblocklink'              => 'deblokiraj',
'change-blocklink'         => 'promijeni blokadu',
'contribslink'             => 'doprinosi',
'blocklogpage'             => 'Registar blokiranja',
'blocklogentry'            => 'blokiran [[$1]] s rokom: $2 $3',
'unblocklogentry'          => 'deblokiran $1',
'block-log-flags-nocreate' => 'pravljenje računa onemogućeno',

# Move page
'movepagetext'     => "Korištenjem donjeg formulara možete preimenovati stranicu, preusmjerivši njenu historiju na novi naziv.
Stari naslov će postati stranica za preusmjerenje na novi naslov.
Možete updateirati preusmjerenja koja idu na originalni naslov automatski.
Ako to ne učinite, budite sigurni da ste provjerili [[Special:DoubleRedirects|dupla]] ili [[Special:BrokenRedirects|mrtva prusmjerenja]].
Odgovorni ste za to da poveznice nastave povezivati stranice kojima su namijenjene.

Uzmite u obzir da stranica '''neće''' biti preusmjerena ako već postoji stranica s novim naslovom, osim ako je prazna ili ako je preusmjerenje bez prethodne historije uređivanja.
To znači da stranicu možete ponovno preimenovati u stari naslov ako je u pitanju bila pogreška, te da ne možete presnimiti već postojeću stranicu.

'''UPOZORENJE!'''
Ovo može biti drastična i neočekivana promjena za popularnu stranicu;
budite sigurni da ste shvatili sve posljedice prije nego što nastavite.",
'movepagetalktext' => "Odgovarajuća stranica za razgovor, ako postoji, će automatski biti premještena istovremeno '''osim:'''
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odznačite donju kutiju.

U tim slučajevima, moraćete ručno da premjestite stranicu ukoliko to želite.",
'movearticle'      => 'Premjestite stranicu:',
'newtitle'         => 'novi naslov:',
'move-watch'       => 'Prati ovu stranicu',
'movepagebtn'      => 'premjestite stranicu',
'pagemovedsub'     => 'Premještanje uspjelo',
'movepage-moved'   => '<big>\'\'\'"$1" je premještena na "$2"\'\'\'</big>',
'articleexists'    => 'Stranica pod tim imenom već postoji, ili je ime koje ste izabrali neispravno.  
Molimo Vas da izaberete drugo ime.',
'talkexists'       => "'''Sama stranica je uspješno premještena, ali stranica za razgovor nije mogla biti premještena jer takva već postoji na novom naslovu.  Molimo Vas da ih spojite ručno.'''",
'movedto'          => 'premještena na',
'movetalk'         => 'Premjestite pridruženu stranicu za razgovor',
'1movedto2'        => '[[$1]] premješteno na [[$2]]',
'1movedto2_redir'  => 'premještena [[$1]] na [[$2]] putem preusmjerenja',
'movelogpage'      => 'Registar premještanja',
'movereason'       => 'Razlog:',
'revertmove'       => 'vrati',

# Export
'export' => 'Izvezite stranice',

# Namespace 8 related
'allmessages' => 'Sistemske poruke',

# Thumbnails
'thumbnail-more' => 'Uvećaj',

# Special:Import
'import'                     => 'Uvoz stranica',
'importinterwiki'            => 'Transwiki uvoz',
'import-interwiki-text'      => 'Izaberi wiki i naslov stranice za uvoz.
Datumi revizije i imena urednika će biti sačuvana.
Sve akcije vezane uz transwiki uvoz su zabilježene u [[Special:Log/import|registru uvoza]].',
'import-interwiki-source'    => 'Izvorna wiki/stranica:',
'import-interwiki-history'   => 'Kopiraj sve verzije historije za ovu stranicu',
'import-interwiki-templates' => 'Uključi sve šablone',
'import-interwiki-submit'    => 'Uvoz',
'import-interwiki-namespace' => 'Odredišni imenski prostor:',
'import-upload-filename'     => 'Naziv datoteke:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Molimo vas da izvezete datoteku iz izvornog wikija koristeći [[Special:Export|utility za izvoz]].
Snimite je na vašem kompjuteru i pošaljite ovdje.',
'importstart'                => 'Uvoženje stranica…',
'import-revision-count'      => '$1 {{PLURAL:$1|revizija|revizije|revizija}}',
'importnopages'              => 'Nema stranica za uvoz.',
'importfailed'               => 'Uvoz nije uspio: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Nepoznat izvorni tip uvoza',
'importcantopen'             => 'Ne može se otvoriti uvozna datoteka',
'importbadinterwiki'         => 'Loš interwiki link',
'importnotext'               => 'Prazan ili nepostojeći tekst',
'importsuccess'              => 'Uvoz dovršen!',
'importhistoryconflict'      => 'Postoji konfliktna historija revizija (moguće je da je ova stranica ranije uvezena)',
'importnosources'            => 'Nisu definirani izvori za transwiki uvoz i neposredna postavljanja historije su onemogućena.',
'importnofile'               => 'Uvozna datoteka nije postavljena.',
'importuploaderrorsize'      => 'Postavljanje uvozne datoteke nije uspjelo.
Datoteka je veća od dozvoljene veličine za postavljanje.',
'importuploaderrorpartial'   => 'Postavljanje uvozne datoteke nije uspjelo.
Datoteka je samo djelomično postavljena.',
'importuploaderrortemp'      => 'Postavljanje uvozne datoteke nije uspjelo.
Nedostaje privremeni folder.',
'import-parse-failure'       => 'Greška pri parsiranju XML uvoza',
'import-noarticle'           => 'Nema stranice za uvoz!',
'import-nonewrevisions'      => 'Sve revizije su prethodno uvežene.',
'xml-error-string'           => '$1 na liniji $2, kolona $3 (bajt $4): $5',
'import-upload'              => 'Postavljanje XML podataka',
'import-token-mismatch'      => 'Izgubljeni podaci sesije.
Molimo pokušajte ponovno.',
'import-invalid-interwiki'   => 'Ne može se uvesti iz navedenog wikija.',

# Import log
'importlogpage'                    => 'Registar uvoza',
'importlogpagetext'                => 'Administrativni uvozi stranica s historijom izmjena sa drugih wikija.',
'import-logentry-upload'           => 'uvezen/a [[$1]] postavljanjem datoteke',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revizija|revizije|revizija}}',
'import-logentry-interwiki'        => 'uveženo ("transwikied") $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revizija|revizije|revizija}} sa $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Vaša korisnička stranica',
'tooltip-pt-mytalk'               => 'Vaša stranica za razgovor',
'tooltip-pt-preferences'          => 'Vaše postavke',
'tooltip-pt-watchlist'            => 'Spisak stranica koje pratite radi izmjena',
'tooltip-pt-mycontris'            => 'Spisak vaših doprinosa',
'tooltip-pt-login'                => 'Predlažem da se prijavite; međutim, to nije obavezno',
'tooltip-pt-logout'               => 'Odjava sa projekta {{SITENAME}}',
'tooltip-ca-talk'                 => 'Razgovor o sadržaju stranice',
'tooltip-ca-edit'                 => 'Možete da uređujete ovu stranicu.
Molimo da prije snimanja koristite dugme za pretpregled',
'tooltip-ca-addsection'           => 'Započnite novu sekciju.',
'tooltip-ca-viewsource'           => 'Ova stranica je zaštićena.
Možete vidjeti njen izvor',
'tooltip-ca-history'              => 'Prethodne verzije ove stranice',
'tooltip-ca-protect'              => 'Zaštiti ovu stranicu',
'tooltip-ca-delete'               => 'Izbriši ovu stranicu',
'tooltip-ca-move'                 => 'Premjesti ovu stranicu',
'tooltip-ca-watch'                => 'Dodajte ovu stranicu na Vaš spisak praćenja',
'tooltip-ca-unwatch'              => 'Izbrišite ovu stranicu sa spiska praćenja',
'tooltip-search'                  => 'Pretraži ovaj wiki',
'tooltip-search-go'               => 'Idi na stranicu s upravo ovakvim imenom ako postoji',
'tooltip-search-fulltext'         => 'Pretraga stranica sa ovim tekstom',
'tooltip-n-mainpage'              => 'Posjetite glavnu stranu',
'tooltip-n-portal'                => 'O projektu, što možete učiniti, gdje možete naći stvari',
'tooltip-n-currentevents'         => 'Pronađi dodatne informacije o trenutnim događajima',
'tooltip-n-recentchanges'         => 'Spisak nedavnih izmjena na wikiju.',
'tooltip-n-randompage'            => 'Otvorite slučajnu stranicu',
'tooltip-n-help'                  => 'Mjesto gdje možete nešto da naučite',
'tooltip-t-whatlinkshere'         => 'Spisak svih stranica povezanih sa ovim',
'tooltip-t-recentchangeslinked'   => 'Nedavne izmjene ovdje povezanih stranica',
'tooltip-feed-rss'                => 'RSS feed za ovu stranicu',
'tooltip-feed-atom'               => 'Atom feed za ovu stranicu',
'tooltip-t-contributions'         => 'Pogledajte listu doprinosa ovog korisnika',
'tooltip-t-emailuser'             => 'Pošaljite e-mail ovom korisniku',
'tooltip-t-upload'                => 'Postavi datoteke',
'tooltip-t-specialpages'          => 'Popis svih posebnih stranica',
'tooltip-t-print'                 => 'Verzija ove stranice za štampanje',
'tooltip-t-permalink'             => 'Stalni link ove verzije stranice',
'tooltip-ca-nstab-main'           => 'Pogledajte sadržaj stranice',
'tooltip-ca-nstab-user'           => 'Pogledajte korisničku stranicu',
'tooltip-ca-nstab-special'        => 'Ovo je posebna stranica, te je ne možete uređivati',
'tooltip-ca-nstab-project'        => 'Pogledajte stranicu projekta',
'tooltip-ca-nstab-image'          => 'Vidi stranicu datoteke/fajla',
'tooltip-ca-nstab-category'       => 'Pogledajte stranicu kategorije',
'tooltip-minoredit'               => 'Označite ovo kao manju izmjenu',
'tooltip-save'                    => 'Snimite vaše izmjene',
'tooltip-preview'                 => 'Prethodni pregled stranice, molimo koristiti prije snimanja!',
'tooltip-diff'                    => 'Prikaz izmjena koje ste napravili u tekstu',
'tooltip-compareselectedversions' => 'Pogledajte pazlike između dvije selektovane verzije ove stranice.',
'tooltip-watch'                   => 'Dodajte ovu stranicu na Vaš spisak praćenja',
'tooltip-rollback'                => '"Povrat" briše izmjenu/e posljednjeg uređivača ove stranice jednim klikom',
'tooltip-undo'                    => 'Vraća ovu izmjenu i otvara formu uređivanja u modu pretpregleda. 
Dozvoljava unošenje razloga za to u sažetku.',

# Browsing diffs
'previousdiff' => '← Starija izmjena',
'nextdiff'     => 'Novija izmjena →',

# Media information
'file-info-size'       => '($1 × $2 piksela, veličina datoteke/fajla: $3, MIME tip: $4)',
'file-nohires'         => '<small>Veća rezolucija nije dostupna.</small>',
'svg-long-desc'        => '(SVG fajl, nominalno $1 × $2 piksela, veličina fajla: $3)',
'show-big-image'       => 'Puna rezolucija',
'show-big-image-thumb' => '<small>Veličina ovog prikaza: $1 × $2 piksela</small>',

# Special:NewFiles
'newimages'    => 'Galerija novih slika',
'showhidebots' => '($1 botove)',

# Bad image list
'bad_image_list' => "Koristi se sljedeći format:

Razmatraju se samo stavke u spisku (linije koje počinju sa *).  
Prvi link u liniji mora biti povezan sa lošom datotekom/fajlom.
Svi drugi linkovi u istoj liniji se smatraju izuzecima, npr. kod stranica gdje se datoteka/fajl pojavljuje ''inline''.",

# Metadata
'metadata'          => 'Metapodaci',
'metadata-help'     => 'Ova datoteka/fajl sadržava dodatne podatke koje je vjerojatno dodala digitalna kamera ili skener u procesu snimanja odnosno digitalizacije. Ako je datoteka/fajl mijenjana u odnosu na prvotno stanje, neki detalji možda nisu u skladu s trenutnim stanjem.',
'metadata-expand'   => 'Pokaži dodatne detalje',
'metadata-collapse' => 'Sakrij dodatne detalje',
'metadata-fields'   => "Sljedeći EXIF metapodaci će biti prikazani ispod slike u tablici s metapodacima. Ostali će biti sakriveni (možete ih vidjeti ako kliknete na link ''Pokaži sve detalje'').
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Širina',
'exif-imagelength'                 => 'Visina',
'exif-bitspersample'               => 'Bita po komponenti',
'exif-compression'                 => 'Šema kompresije',
'exif-photometricinterpretation'   => 'Sastav piksela',
'exif-orientation'                 => 'Orijentacija',
'exif-samplesperpixel'             => 'Broj komponenti',
'exif-planarconfiguration'         => 'Aranžiranje podataka',
'exif-ycbcrsubsampling'            => 'Odnos subsampling od Y do C',
'exif-ycbcrpositioning'            => 'Pozicioniranje Y i C',
'exif-xresolution'                 => 'Horizontalna rezolucija',
'exif-yresolution'                 => 'Vertikalna rezolucija',
'exif-resolutionunit'              => 'Jedinice X i Y rezolucije',
'exif-stripoffsets'                => 'Lokacija podataka slike',
'exif-rowsperstrip'                => 'Broj redaka po liniji',
'exif-stripbytecounts'             => 'Bita po kompresovanoj liniji',
'exif-jpeginterchangeformat'       => 'Presijek do JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bita JPEG podataka',
'exif-transferfunction'            => 'Transferna funkcija',
'exif-whitepoint'                  => 'Hromiranost bijele tačke',
'exif-primarychromaticities'       => 'Hromaticitet primarnih boja',
'exif-ycbcrcoefficients'           => 'Koeficijenti transformacije matrice prostora boja',
'exif-referenceblackwhite'         => 'Par crnih i bijelih referentnih vrijednosti',
'exif-datetime'                    => 'Vrijeme i datum promjene datoteke',
'exif-imagedescription'            => 'Naslov slike',
'exif-make'                        => 'Proizvođač kamere',
'exif-model'                       => 'Model kamere',
'exif-software'                    => 'Korišteni softver',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Vlasnik autorskih prava',
'exif-exifversion'                 => 'Exif verzija',
'exif-flashpixversion'             => 'Podržana verzija Flashpix',
'exif-colorspace'                  => 'Prostor boje',
'exif-componentsconfiguration'     => 'Značenje svake komponente',
'exif-compressedbitsperpixel'      => 'Način kompresije slike',
'exif-pixelydimension'             => 'Određena širina slike',
'exif-pixelxdimension'             => 'Određena visina slike',
'exif-makernote'                   => 'Bilješke proizvođača',
'exif-usercomment'                 => 'Korisnički komentari',
'exif-relatedsoundfile'            => 'Povezana zvučna datoteka',
'exif-datetimeoriginal'            => 'Datum i vrijeme generisanja podataka',
'exif-datetimedigitized'           => 'Datum i vrijeme digitalizacije',
'exif-subsectime'                  => 'Datum i vrijeme u dijelovima sekunde',
'exif-subsectimeoriginal'          => 'Originalno vrijeme i datum u dijelovima sekunde',
'exif-subsectimedigitized'         => 'Datum i vrijeme digitalizacije u dijelovima sekunde',
'exif-exposuretime'                => 'Vrijeme izlaganja (ekspozicije)',
'exif-exposuretime-format'         => '$1 sekundi ($2)',
'exif-fnumber'                     => 'F broj',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Program ekspozicije',
'exif-spectralsensitivity'         => 'Spektralna osjetljivost',
'exif-isospeedratings'             => 'Rejting ISO brzine',
'exif-oecf'                        => 'Optoelektronski faktor konvezije',
'exif-shutterspeedvalue'           => 'Brzina okidača',
'exif-aperturevalue'               => 'Otvor blende',
'exif-brightnessvalue'             => 'Osvijetljenost',
'exif-exposurebiasvalue'           => 'Kompozicija ekspozicije',
'exif-maxaperturevalue'            => 'Najveći broj otvora blende',
'exif-subjectdistance'             => 'Udaljenost objekta',
'exif-meteringmode'                => 'Način mjerenja',
'exif-lightsource'                 => 'Izvor svjetlosti',
'exif-flash'                       => 'Bljesak',
'exif-focallength'                 => 'Fokusna dužina objektiva',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Površina objekta',
'exif-flashenergy'                 => 'Energija bljeska',
'exif-spatialfrequencyresponse'    => 'Prostorna frekvencija odgovora',
'exif-focalplanexresolution'       => 'Rezolucija fokusne ravni X',
'exif-focalplaneyresolution'       => 'Rezolucija fokusne ravni Y',
'exif-focalplaneresolutionunit'    => 'Jedinica rezolucije fokusne ravni',
'exif-subjectlocation'             => 'Lokacija objekta',
'exif-exposureindex'               => 'Indeks ekspozicije',
'exif-sensingmethod'               => 'Vrsta senzora',
'exif-filesource'                  => 'Izvor datoteke',
'exif-scenetype'                   => 'Vrsta scene',
'exif-cfapattern'                  => 'CFA šema',
'exif-customrendered'              => 'Podešeno uređivanje slike',
'exif-exposuremode'                => 'Vrsta ekspozicije',
'exif-whitebalance'                => 'Bijeli balans',
'exif-digitalzoomratio'            => 'Odnos digitalnog zuma',
'exif-focallengthin35mmfilm'       => 'Fokusna dužina kod 35 mm filma',
'exif-scenecapturetype'            => 'Vrsta scene snimanja',
'exif-gaincontrol'                 => 'Kontrola scene',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Saturacija',
'exif-sharpness'                   => 'Izoštrenost',
'exif-devicesettingdescription'    => 'Opis postavki uređaja',
'exif-subjectdistancerange'        => 'Udaljenost od objekta',
'exif-imageuniqueid'               => 'Jedinstveni ID slike',
'exif-gpsversionid'                => 'Verzija GPS bloka informacija',
'exif-gpslatituderef'              => 'Sjeverna ili južna širina',
'exif-gpslatitude'                 => 'Širina',
'exif-gpslongituderef'             => 'Istočna ili zapadna dužina',
'exif-gpslongitude'                => 'Dužina',
'exif-gpsaltituderef'              => 'Referenca visine',
'exif-gpsaltitude'                 => 'Nadmorska visina',
'exif-gpstimestamp'                => 'GPS vrijeme (atomski sat)',
'exif-gpssatellites'               => 'Sateliti korišteni pri mjerenju',
'exif-gpsstatus'                   => 'Status prijemnika',
'exif-gpsmeasuremode'              => 'Način mjerenja',
'exif-gpsdop'                      => 'Preciznost mjerenja',
'exif-gpsspeedref'                 => 'Jedinica brzine',
'exif-gpsspeed'                    => 'Brzina GPS prijemnika',
'exif-gpstrackref'                 => 'Referenca za smjer kretanja',
'exif-gpstrack'                    => 'Smjer kretanja',
'exif-gpsimgdirectionref'          => 'Referenca za smjer slike',
'exif-gpsimgdirection'             => 'Smjer slike',
'exif-gpsmapdatum'                 => 'Upotrijebljeni podaci geodetskih mjerenja',
'exif-gpsdestlatituderef'          => 'Referenca za geografsku širinu odredišta',
'exif-gpsdestlatitude'             => 'Širina odredišta',
'exif-gpsdestlongituderef'         => 'Referenca za geografsku dužinu odredišta',
'exif-gpsdestlongitude'            => 'Dužina odredišta',
'exif-gpsdestbearingref'           => 'Indeks azimuta odredišta',
'exif-gpsdestbearing'              => 'Azimut odredišta',
'exif-gpsdestdistanceref'          => 'Referenca za udaljenost od odredišta',
'exif-gpsdestdistance'             => 'Udaljenost do odredišta',
'exif-gpsprocessingmethod'         => 'Naziv GPS metoda procesiranja',
'exif-gpsareainformation'          => 'Naziv GPS područja',
'exif-gpsdatestamp'                => 'GPS datum',
'exif-gpsdifferential'             => 'GPS diferencijalna korekcija',

# EXIF attributes
'exif-compression-1' => 'Nekompresovano',

'exif-unknowndate' => 'Nepoznat datum',

'exif-orientation-1' => 'Normalna',
'exif-orientation-2' => 'Horizontalno preokrenuto',
'exif-orientation-3' => 'Rotirano 180°',
'exif-orientation-4' => 'Vertikalno preokrenuto',
'exif-orientation-5' => 'Rotirano 90° suprotno kazaljke i vertikalno obrnuto',
'exif-orientation-6' => 'Rotirano 90° u smjeru kazaljke',
'exif-orientation-7' => 'Rotirano 90° u smjeru kazaljke i preokrenuto vertikalno',
'exif-orientation-8' => 'Rotirano 90° suprotno od kazaljke',

'exif-planarconfiguration-1' => 'grubi format',
'exif-planarconfiguration-2' => 'format u ravni',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'ne postoji',

'exif-exposureprogram-0' => 'Nije određen',
'exif-exposureprogram-1' => 'Ručno',
'exif-exposureprogram-2' => 'Normalni program',
'exif-exposureprogram-3' => 'Prioritet otvora blende',
'exif-exposureprogram-4' => 'Prioritet okidača',
'exif-exposureprogram-5' => 'Kreativni program (usmjeren ka dubini polja)',
'exif-exposureprogram-6' => 'Program akcije (usmjereno na veću brzinu okidača)',
'exif-exposureprogram-7' => 'Način portreta (za fotografije iz blizine sa pozadinom van fokusa)',
'exif-exposureprogram-8' => 'Način pejzaža (za pejzažne fotografije sa pozadinom u fokusu)',

'exif-subjectdistance-value' => '$1 metara',

'exif-meteringmode-0'   => 'Nepoznat',
'exif-meteringmode-1'   => 'Prosječan',
'exif-meteringmode-2'   => 'Srednji prosjek težišta',
'exif-meteringmode-3'   => 'Tačka',
'exif-meteringmode-4'   => 'Višestruka tačka',
'exif-meteringmode-5'   => 'Šema',
'exif-meteringmode-6'   => 'Djelimični',
'exif-meteringmode-255' => 'Ostalo',

'exif-lightsource-0'   => 'Nepoznat',
'exif-lightsource-1'   => 'Dnevno svjetlo',
'exif-lightsource-2'   => 'Fluorescentni',
'exif-lightsource-3'   => 'Volfram (svjetlo)',
'exif-lightsource-4'   => 'Bljesak (blic)',
'exif-lightsource-9'   => 'Lijepo vrijeme',
'exif-lightsource-10'  => 'Oblačno vrijeme',
'exif-lightsource-11'  => 'Osjenčeno',
'exif-lightsource-12'  => 'Dnevna fluorescencija (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dnevna bijela fluorescencija (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Hladno bijela fluorescencija (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Bijela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardno svjetlo A',
'exif-lightsource-18'  => 'Standardno svjetlo B',
'exif-lightsource-19'  => 'Standardno svjetlo C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-24'  => 'ISO studio volfram',
'exif-lightsource-255' => 'Ostali izvori svjetlosti',

# Flash modes
'exif-flash-fired-0'    => 'Bljesak (blic) nije radio',
'exif-flash-fired-1'    => 'Blic radio',
'exif-flash-return-0'   => 'bljesak (blic) nije poslao nikakav odziv',
'exif-flash-return-2'   => 'nije otkriven bljesak (blic)',
'exif-flash-return-3'   => 'otkriven bljesak',
'exif-flash-mode-1'     => 'obavezan rad bljeska',
'exif-flash-mode-2'     => 'obavezno izbjegavanje bljeska',
'exif-flash-mode-3'     => 'automatski način',
'exif-flash-function-1' => 'Bez funkcije bljeska',
'exif-flash-redeye-1'   => 'način redukcije "crvenila očiju"',

'exif-focalplaneresolutionunit-2' => 'inči',

'exif-sensingmethod-1' => 'Nedefinisan',
'exif-sensingmethod-2' => 'Senzor boje površine sa jednim čipom',
'exif-sensingmethod-3' => 'Senzor boje površine sa dva čipa',
'exif-sensingmethod-4' => 'Senzor boje površine sa tri čipa',
'exif-sensingmethod-5' => 'Senzor boje površine sa tri čipa',
'exif-sensingmethod-7' => 'Trilinearni senzor',
'exif-sensingmethod-8' => 'Sekvencijalni senzor boje linija',

'exif-scenetype-1' => 'Direktno fotografisana slika',

'exif-customrendered-0' => 'Normalni proces',
'exif-customrendered-1' => 'Podešeni proces',

'exif-exposuremode-0' => 'Automatska ekpozicija',
'exif-exposuremode-1' => 'Ručna ekspozicija',
'exif-exposuremode-2' => 'Automatski određen raspon',

'exif-whitebalance-0' => 'Automatski bijeli balans',
'exif-whitebalance-1' => 'Ručno podešeni bijeli balans',

'exif-scenecapturetype-0' => 'Standardna',
'exif-scenecapturetype-1' => 'Pejzaž',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Noćna scena',

'exif-gaincontrol-0' => 'Ništa',
'exif-gaincontrol-1' => 'Malo povećanje',
'exif-gaincontrol-2' => 'Veće povećanje',
'exif-gaincontrol-3' => 'Manje smanjenje',
'exif-gaincontrol-4' => 'Veće smanjenje',

'exif-contrast-0' => 'Normalni',
'exif-contrast-1' => 'Meki',
'exif-contrast-2' => 'Snažni',

'exif-saturation-0' => 'Normalna',
'exif-saturation-1' => 'Niska zasićenost',
'exif-saturation-2' => 'Jako zasićenje',

'exif-sharpness-0' => 'Normalna',
'exif-sharpness-1' => 'Blago',
'exif-sharpness-2' => 'Oštro',

'exif-subjectdistancerange-0' => 'Nepoznat',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Pogled izbliza',
'exif-subjectdistancerange-3' => 'Pogled iz daljine',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Sjeverna širina',
'exif-gpslatitude-s' => 'Južna širina',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Istočna dužina',
'exif-gpslongitude-w' => 'Zapadna dužina',

'exif-gpsstatus-a' => 'Mjerenje u toku',
'exif-gpsstatus-v' => 'Mjerenje van funkcije',

'exif-gpsmeasuremode-2' => 'dvodimenzionalno mjerenje',
'exif-gpsmeasuremode-3' => 'trodimenzionalno mjerenje',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometara na sat',
'exif-gpsspeed-m' => 'Milja na sat',
'exif-gpsspeed-n' => 'Čvorova',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Stvarni pravac',
'exif-gpsdirection-m' => 'Magnetski smjer',

# External editor support
'edit-externally'      => 'Izmijeni ovu datoteku/fajl koristeći eksternu aplikaciju',
'edit-externally-help' => '(Pogledajte [http://www.mediawiki.org/wiki/Manual:External_editors instrukcije za podešavanje] za više informacija)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sve',
'imagelistall'     => 'sve',
'watchlistall2'    => 'sve',
'namespacesall'    => 'sve',
'monthsall'        => 'sve',

# E-mail address confirmation
'confirmemail'             => 'Potvrdite adresu e-pošte',
'confirmemail_noemail'     => 'Niste unijeli tačnu e-mail adresu u Vaše [[Special:Preferences|korisničke postavke]].',
'confirmemail_text'        => 'Ova viki zahtjeva da potvrdite adresu Vaše e-pošte prije nego što koristite mogućnosti e-pošte. ž
Aktivirajte dugme ispod kako bi ste poslali poštu za potvrdu na Vašu adresu. 
Pošta uključuje poveznicu koja sadrži kod; 
učitajte poveznicu u Vaš brauzer da bi ste potvrdili da je adresa Vaše e-pošte valjana.',
'confirmemail_pending'     => 'Kod za potvrdu Vam je već poslan putem e-maila;
ako ste nedavno otvorili Vaš račun, trebali bi pričekati par minuta da poslana pošta stigne, prije nego što ponovno zahtijevate novi kod.',
'confirmemail_send'        => 'Pošaljite kod za potvrdu',
'confirmemail_sent'        => 'E-pošta za potvrđivanje poslata.',
'confirmemail_oncreate'    => 'Kod za potvrđivanje Vam je poslat na Vašu e-mail adresu.
Taj kod nije neophodan za prijavljivanje, ali Vam je potreban kako bi ste omogućili funkcije wikija zasnovane na e-mailu.',
'confirmemail_sendfailed'  => '{{SITENAME}} Vam ne može poslati poštu za potvrđivanje. 
Provjerite adresu zbog nepravilnih karaktera.

Povratna pošta: $1',
'confirmemail_invalid'     => 'Netačan kod za potvrdu. 
Moguće je da je kod istekao.',
'confirmemail_needlogin'   => 'Morate $1 da bi ste potvrdili Vašu e-mail adresu.',
'confirmemail_success'     => 'Adresa vaše e-pošte je potvrđena. 
Možete sad da se [[Special:UserLogin|prijavite]] i uživate u viki.',
'confirmemail_loggedin'    => 'Adresa Vaše e-pošte je potvrđena.',
'confirmemail_error'       => 'Nešto je pošlo krivo prilikom snimanja vaše potvrde.',
'confirmemail_subject'     => '{{SITENAME}} adresa e-pošte za potvrdu',
'confirmemail_invalidated' => 'Potvrda e-mail adrese otkazana',
'invalidateemail'          => 'Odustani od e-mail potvrde',

# Scary transclusion
'scarytranscludedisabled' => '[Međuwiki umetanje je isključeno]',
'scarytranscludefailed'   => '[Neuspješno preusmjerenje šablona na $1]',
'scarytranscludetoolong'  => '[URL je predugačak]',

# Trackbacks
'trackbackbox'      => 'Trackbacks za ovu stranicu:<br />
$1',
'trackbackremove'   => '([$1 Brisanje])',
'trackbacklink'     => 'Vraćanje',
'trackbackdeleteok' => 'Trackback je uspješno obrisan.',

# Delete conflict
'deletedwhileediting' => "'''Upozorenje''': Ova stranica je obrisana prije nego što ste počeli uređivati!",
'confirmrecreate'     => "Korisnik [[User:$1|$1]] ([[User talk:$1|razgovor]]) je obrisao ovaj članak pošto ste počeli uređivanje sa razlogom:
: ''$2''

Molimo Vas da potvrdite da stvarno želite da ponovo napravite ovaj članak.",
'recreate'            => 'Ponovno napravi',

# action=purge
'confirm_purge_button' => 'U redu',
'confirm-purge-top'    => "Da li želite obrisati keš (''cache'') ove stranice?",
'confirm-purge-bottom' => 'Ispražnjava keš stranice i prikazuje najsvježiju verziju.',

# Multipage image navigation
'imgmultipageprev' => '← prethodna stranica',
'imgmultipagenext' => 'sljedeća stranica →',
'imgmultigo'       => 'Idi!',
'imgmultigoto'     => 'Idi na stranicu $1',

# Table pager
'ascending_abbrev'         => 'rast',
'descending_abbrev'        => 'opad',
'table_pager_next'         => 'Sljedeća stranica',
'table_pager_prev'         => 'Prethodna stranica',
'table_pager_first'        => 'Prva stranica',
'table_pager_last'         => 'Zadnja stranica',
'table_pager_limit'        => 'Pokaži $1 stavki po stranici',
'table_pager_limit_submit' => 'Idi',
'table_pager_empty'        => 'Bez rezultata',

# Auto-summaries
'autosumm-blank'   => 'Uklanjanje sadržaja stranice',
'autosumm-replace' => "Zamjena stranice sa '$1'",
'autoredircomment' => 'Preusmjereno na [[$1]]',
'autosumm-new'     => "Napravljena stranica sa '$1'",

# Live preview
'livepreview-loading' => 'Učitavanje...',
'livepreview-ready'   => 'Učitavanje... Spreman!',
'livepreview-failed'  => 'Pregled uživo nije uspio! Pokušajte normalni pregled.',
'livepreview-error'   => 'Spajanje nije uspjelo: $1 "$2".
Pokušajte normalni pregled.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Promjene načinjene prije manje od $1 {{PLURAL:$1|sekunde|sekunde|sekundi}} možda neće biti prikazane na ovom spisku.',
'lag-warn-high'   => 'Zbog dužeg zastoja baze podataka na serveru, izmjene novije od $1 {{PLURAL:$1|sekunde|sekunde|sekundi}} možda neće biti prikazane na ovom spisku.',

# Watchlist editor
'watchlistedit-numitems'       => 'Vaš spisak praćenja sadrži {{PLURAL:$1|1 naslov|$1 naslova}}, izuzimajući stranice za razgovor.',
'watchlistedit-noitems'        => 'Vaš spisak praćenja ne sadrži naslove.',
'watchlistedit-normal-title'   => 'Uredi spisak praćenja',
'watchlistedit-normal-legend'  => 'Ukloni naslove iz spiska praćenja',
'watchlistedit-normal-explain' => 'Naslovi na Vašem spisku praćenja su prikazani ispod.
Da bi ste uklonili naslov, označite kutiju pored naslova, i kliknite Ukloni naslove.
Također možete [[Special:Watchlist/raw|napredno urediti spisak]].',
'watchlistedit-normal-submit'  => 'Ukloni naslove',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 naslov|$1 naslova}} je uklonjeno iz Vašeg spiska praćenja:',
'watchlistedit-raw-title'      => 'Napredno uređivanje spiska praćenja',
'watchlistedit-raw-legend'     => 'Napredno uređivanje spiska praćenja',
'watchlistedit-raw-explain'    => 'Naslovi u Vašem spisku praćenja su prikazani ispod, i mogu biti uređeni dodavanje ili brisanjem sa spiska; jedan naslov u svakom redu.
Kada završite, kliknite Ažuriraj spisak praćenja.
Također možete [[Special:Watchlist/edit|koristiti standardni uređivač]].',
'watchlistedit-raw-titles'     => 'Naslovi:',
'watchlistedit-raw-submit'     => 'Ažuriraj spisak praćenja',
'watchlistedit-raw-done'       => 'Vaš spisak praćenja je ažuriran.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 naslov je dodan|$1 naslova su dodana|$1 naslova je dodano}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 naslov je uklonjen|$1 naslova je uklonjeno}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Vidi relevantne promjene',
'watchlisttools-edit' => 'Vidi i uredi listu praćenja',
'watchlisttools-raw'  => 'Uredi grubu listu praćenja',

# Core parser functions
'unknown_extension_tag' => 'Nepoznata oznaka ekstenzije "$1"',
'duplicate-defaultsort' => '\'\'\'Upozorenje\'\'\': Postavljeni ključ sortiranja "$2" zamjenjuje raniji ključ "$1".',

# Special:Version
'version'                          => 'Verzija',
'version-extensions'               => 'Instalirana proširenja (ekstenzije)',
'version-specialpages'             => 'Posebne stranice',
'version-parserhooks'              => 'Kuke parsera',
'version-variables'                => 'Promjenjive',
'version-other'                    => 'Ostalo',
'version-mediahandlers'            => 'Upravljači medije',
'version-hooks'                    => 'Kuke',
'version-extension-functions'      => 'Funkcije proširenja (ekstenzije)',
'version-parser-extensiontags'     => "Parser proširenja (''tagovi'')",
'version-parser-function-hooks'    => 'Kuke parserske funkcije',
'version-skin-extension-functions' => 'Funkcije proširenja skina',
'version-hook-name'                => 'Naziv kuke',
'version-hook-subscribedby'        => 'Pretplaćeno od',
'version-version'                  => '(Verzija $1)',
'version-license'                  => 'Licenca',
'version-software'                 => 'Instalirani softver',
'version-software-product'         => 'Proizvod',
'version-software-version'         => 'Verzija',

# Special:FilePath
'filepath'         => 'Putanja datoteke',
'filepath-page'    => 'Datoteka:',
'filepath-submit'  => 'Putanja',
'filepath-summary' => 'Ova posebna stranica prikazuje potpunu putanju za datoteku.
Slike su prikazane u punoj veličini, ostale vrste datoteka su prikazane direktno sa, s njima povezanim, programom.

Unesite ime datoteke bez "{{ns:file}}:" prefiksa.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Potraga za duplim datotekama',
'fileduplicatesearch-summary'  => 'Pretraga duplih datoteka na bazi njihove haš vrijednosti.

Unesite ime datoteke bez "{{ns:file}}:" prefiksa.',
'fileduplicatesearch-legend'   => 'Pretraga dvojnika',
'fileduplicatesearch-filename' => 'Ime datoteke:',
'fileduplicatesearch-submit'   => 'Traži',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Veličina datoteke: $3<br />MIME vrsta: $4',
'fileduplicatesearch-result-1' => 'Datoteka "$1" nema identičnih dvojnika.',
'fileduplicatesearch-result-n' => 'Datoteka "$1" ima {{PLURAL:$2|1 identičnog|$2 identična|$2 identičnih}} dvojnika.',

# Special:SpecialPages
'specialpages'                   => 'Posebne stranice',
'specialpages-note'              => '----
* Normalne posebne stranice.
* <strong class="mw-specialpagerestricted">Zaštićene posebne stranice.</strong>',
'specialpages-group-maintenance' => 'Izvještaji za održavanje',
'specialpages-group-other'       => 'Ostale posebne stranice',
'specialpages-group-login'       => 'Prijava / Otvaranje računa',
'specialpages-group-changes'     => 'Nedavne izmjene i registri',
'specialpages-group-media'       => 'Mediji i postavljanje datoteka',
'specialpages-group-users'       => 'Korisnici i korisnička prava',
'specialpages-group-highuse'     => 'Često korištene stranice',
'specialpages-group-pages'       => 'Spiskovi stranica',
'specialpages-group-pagetools'   => 'Alati za stranice',
'specialpages-group-wiki'        => 'Wiki podaci i alati',
'specialpages-group-redirects'   => 'Preusmjeravanje posebnih stranica',
'specialpages-group-spam'        => 'Spam alati',

# Special:BlankPage
'blankpage'              => 'Prazna stranica',
'intentionallyblankpage' => 'Ova je stranica namjerno ostavljena praznom.',

# External image whitelist
'external_image_whitelist' => ' #Ostavite ovu liniju onakva kakva je<pre>
#Stavite obične fragmente opisa (samo dio koji ide između //) ispod
#Ovi će biti spojeni sa URLovima sa vanjskih (eksternih) slika
#One koji se spoje biće prikazane kao slike, u suprotnom će se prikazati samo link
#Linije koje počinju sa # se tretiraju kao komentari
#Ovo ne razlikuje velika i mala slova

#Stavite sve regex fragmente iznad ove linije. Ostavite ovu liniju onakvu kakva je</pre>',

# Special:Tags
'tags'                    => 'Oznake valjane izmjene',
'tag-filter'              => 'Filter [[Special:Tags|oznaka]]:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Oznake',
'tags-intro'              => 'Ova stranica prikazuje spisak oznaka (tagova) koje softver može staviti na svaku izmjenu i njihovo značenje.',
'tags-tag'                => 'Unutrašnji naziv oznake',
'tags-display-header'     => 'Vidljivost na spisku izmjena',
'tags-description-header' => 'Puni opis značenja',
'tags-hitcount-header'    => 'Označene izmjene',
'tags-edit'               => 'uređivanje',
'tags-hitcount'           => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',

# Database error messages
'dberr-header'      => 'Ovaj wiki ima problem',
'dberr-problems'    => 'Žao nam je! Ova stranica ima tehničke poteškoće.',
'dberr-again'       => 'Pokušajte pričekati nekoliko minuta i ponovno učitati.',
'dberr-info'        => '(Ne može se spojiti server baze podataka: $1)',
'dberr-usegoogle'   => 'U međuvremenu pokušajte pretraživati preko Googlea.',
'dberr-outofdate'   => 'Uzmite u obzir da njihovi indeksi našeg sadržaja ne moraju uvijek biti ažurni.',
'dberr-cachederror' => 'Sljedeći tekst je keširana kopija tražene stranice i možda nije potpuno ažurirana.',

# HTML forms
'htmlform-invalid-input'       => 'Postoje određeni problemi sa Vašim unosom',
'htmlform-select-badoption'    => 'Vrijednost koju ste unijeli nije valjana opcija.',
'htmlform-int-invalid'         => 'Vrijednost koju ste unijeli nije cijeli broj.',
'htmlform-int-toolow'          => 'Vrijednost koju ste unijeli je ispod minimuma od $1',
'htmlform-int-toohigh'         => 'Vrijednost koju ste unijeli je iznad maksimuma od $1',
'htmlform-submit'              => 'Unesi',
'htmlform-reset'               => 'Vrati izmjene',
'htmlform-selectorother-other' => 'Ostalo',

);
