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
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_sa_korisnikom',
	NS_PROJECT_TALK     => 'Razgovor_o_$1',
	NS_FILE             => 'Datoteka',
	NS_FILE_TALK        => 'Razgovor_o_datoteci',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

$linkTrail = '/^([a-zčćđžš]+)(.*)$/sDu';

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
'tog-rememberpassword'        => 'Upamti moju prijavu za ovaj preglednik (za maksimum od $1 {{PLURAL:$1|dan|dana}})',
'tog-watchcreations'          => 'Dodaj stranice koje sam napravio u moj spisak praćenja',
'tog-watchdefault'            => 'Dodaj stranice koje uređujem u moj spisak praćenja',
'tog-watchmoves'              => 'Dodaj stranice koje premještam u moj spisak praćenja',
'tog-watchdeletion'           => 'Stranice koje brišem dodaj na moj spisak praćenja',
'tog-minordefault'            => 'Označi da su sve izmjene u pravilu manje',
'tog-previewontop'            => 'Prikaži pretpregled prije kutije za uređivanje',
'tog-previewonfirst'          => 'Prikaži pretpregled na prvoj izmjeni',
'tog-nocache'                 => 'Onemogući keš (cache) stranica u pregledniku',
'tog-enotifwatchlistpages'    => 'Pošalji mi e-poštu kad se promijeni stranica na mom spisku praćenja',
'tog-enotifusertalkpages'     => 'Pošalji mi e-poštu kad se promijeni moja korisnička stranica za razgovor',
'tog-enotifminoredits'        => 'Pošalji mi e-poštu takođe za male izmjene stranica',
'tog-enotifrevealaddr'        => 'Otkrij adresu moje e-pošte u porukama obaviještenja',
'tog-shownumberswatching'     => 'Prikaži broj korisnika koji prate',
'tog-oldsig'                  => 'Pregled postojećeg potpisa:',
'tog-fancysig'                => 'Smatraj potpis kao wikitekst (bez automatskog linka)',
'tog-externaleditor'          => 'Redovno koristite eksterni editor (samo za naprednije korisnike, potrebne su posebne postavke na vašem računaru)',
'tog-externaldiff'            => 'Redovno koristi vanjski (diff) program za prikaz razlika (samo za naprednije korisnike, potrebne posebne postavke na vašem računaru [http://www.mediawiki.org/wiki/Manual:External_editors Više informacija.])',
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

# Font style option in Special:Preferences
'editfont-style'     => 'Stil slova područja uređivanja:',
'editfont-default'   => 'Po postavkama preglednika',
'editfont-monospace' => 'Slova sa jednostrukim razmakom',
'editfont-sansserif' => 'Slova bez serifa',
'editfont-serif'     => 'Slova serif',

# Dates
'sunday'        => 'nedjelja',
'monday'        => 'ponedjeljak',
'tuesday'       => 'utorak',
'wednesday'     => 'srijeda',
'thursday'      => 'četvrtak',
'friday'        => 'petak',
'saturday'      => 'subota',
'sun'           => 'Ned',
'mon'           => 'Pon',
'tue'           => 'Uto',
'wed'           => 'Sri',
'thu'           => 'Čet',
'fri'           => 'Pet',
'sat'           => 'Sub',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'mart',
'april'         => 'april',
'may_long'      => 'maj',
'june'          => 'jun',
'july'          => 'jul',
'august'        => 'august',
'september'     => 'septembar',
'october'       => 'oktobar',
'november'      => 'novembar',
'december'      => 'decembar',
'january-gen'   => 'januar',
'february-gen'  => 'februar',
'march-gen'     => 'mart',
'april-gen'     => 'april',
'may-gen'       => 'maj',
'june-gen'      => 'jun',
'july-gen'      => 'jul',
'august-gen'    => 'august',
'september-gen' => 'septembar',
'october-gen'   => 'oktobar',
'november-gen'  => 'novembar',
'december-gen'  => 'decembar',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maj',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

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
'index-category'                 => 'Indeksirane stranice',
'noindex-category'               => 'Neindeksirane stranice',

'mainpagetext'      => "'''MediaWiki softver is uspješno instaliran.'''",
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
'faq'            => 'ČPP',
'faqpage'        => 'Project:ČPP',

# Vector skin
'vector-action-addsection'       => 'Dodaj temu',
'vector-action-delete'           => 'Brisanje',
'vector-action-move'             => 'Preusmjeri',
'vector-action-protect'          => 'Zaštiti',
'vector-action-undelete'         => 'Vrati obrisano',
'vector-action-unprotect'        => 'Oslobodi od zaštite',
'vector-simplesearch-preference' => 'Omogući napredne sugestije pretrage (samo vector skin)',
'vector-view-create'             => 'Napravi',
'vector-view-edit'               => 'Uredi',
'vector-view-history'            => 'Pregled historije',
'vector-view-view'               => 'Čitaj',
'vector-view-viewsource'         => 'Vidi izvor (source)',
'actions'                        => 'Akcije',
'namespaces'                     => 'Imenski prostori',
'variants'                       => 'Varijante',

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
'view'              => 'Vidi',
'edit'              => 'Uredi',
'create'            => 'Napravi',
'editthispage'      => 'Uredite ovu stranicu',
'create-this-page'  => 'Stvori ovu stranicu',
'delete'            => 'Obriši',
'deletethispage'    => 'Obriši ovu stranicu',
'undelete_short'    => 'Vrati obrisanih {{PLURAL:$1|$1 izmjenu|$1 izmjene|$1 izmjena}}',
'viewdeleted_short' => 'Pogledaj {{PLURAL:$1|jednu obrisanu izmjenu|$1 obrisane izmjene|$1 obrisanih izmjena}}',
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
'view-pool-error'   => 'Žao nam je, serveri su trenutno preopterećeni.
Previše korisnika pokušava da pregleda ovu stranicu.
Molimo pričekajte trenutak prije nego što ponovno pokušate pristupiti ovoj stranici.

$1',
'pool-timeout'      => 'Zaustavi čekanje za zaključavanje',
'pool-queuefull'    => 'Red na pool je prenapunjen',
'pool-errorunknown' => 'nepoznata greška',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O projektu {{SITENAME}}',
'aboutpage'            => 'Project:O_projektu_{{SITENAME}}',
'copyright'            => 'Sadržaj je dostupan pod $1.',
'copyrightpage'        => '{{ns:project}}:Autorska_prava',
'currentevents'        => 'Trenutni događaji',
'currentevents-url'    => 'Project:Novosti',
'disclaimers'          => 'Odricanje odgovornosti',
'disclaimerpage'       => 'Project:Uslovi korištenja, pravne napomene i odricanje odgovornosti',
'edithelp'             => 'Pomoć pri uređivanju',
'edithelppage'         => 'Help:Uređivanje',
'helppage'             => 'Help:Sadržaj',
'mainpage'             => 'Glavna strana',
'mainpage-description' => 'Glavna strana',
'policy-url'           => 'Project:Pravila',
'portal'               => 'Portal zajednice',
'portal-url'           => 'Project:Portal_zajednice',
'privacy'              => 'Politika privatnosti',
'privacypage'          => 'Project:Pravila o anonimnosti',

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
'collapsible-collapse'    => 'Sakrij',
'collapsible-expand'      => 'Proširi',
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
'nospecialpagetext' => '<strong>Zatražili ste nevaljanu posebnu stranicu.</strong>

Lista valjanih posebnih stranica se može naći na [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Greška',
'databaseerror'        => 'Greška u bazi podataka',
'dberrortext'          => 'Desila se sintaksna greška upita baze.
Ovo se desilo zbog moguće greške u softveru.
Posljednji pokušani upit je bio:
<blockquote><tt>$1</tt></blockquote>
iz funkcije "<tt>$2</tt>".
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
'fileappenderrorread'  => 'Nije se mogao pročitati "$1" tokom dodavanja.',
'fileappenderror'      => 'Ne može se primijeniti "$1" na "$2".',
'filecopyerror'        => 'Ne može se kopirati "$1" na "$2".',
'filerenameerror'      => 'Ne može se promjeniti ime datoteke "$1" u "$2".',
'filedeleteerror'      => 'Ne može se izbrisati datoteka "$1".',
'directorycreateerror' => 'Nije moguće napraviti direktorijum "$1".',
'filenotfound'         => 'Ne može se naći datoteka "$1".',
'fileexistserror'      => 'Nemoguće je stvoriti datoteku "$1": datoteka već postoji',
'unexpected'           => 'Neočekivana vrijednost: "$1"="$2".',
'formerror'            => 'Greška: ne može se poslati formular',
'badarticleerror'      => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'cannotdelete'         => 'Ne može se obrisati stranica ili datoteka "$1".
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
'remembermypassword'         => 'Upamti moju lozinku na ovom kompjuteru (za maksimum od $1 {{PLURAL:$1|dan|dana}})',
'securelogin-stick-https'    => 'Ostanite povezani na HTTPS nakon prijave',
'yourdomainname'             => 'Vaš domen:',
'externaldberror'            => 'Došlo je do greške pri vanjskoj autorizaciji baze podataka ili vam nije dopušteno osvježavanje Vašeg vanjskog korisničkog računa.',
'login'                      => 'Prijavi se',
'nav-login-createaccount'    => 'Prijavi se / Registruj se',
'loginprompt'                => "Morate imati kolačiće ('''cookies''') omogućene da biste se prijavili na {{SITENAME}}.",
'userlogin'                  => 'Prijavi se / stvori korisnički račun',
'userloginnocreate'          => 'Prijavi se',
'logout'                     => 'Odjavi me',
'userlogout'                 => 'Odjava',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => "Nemate korisničko ime? '''$1'''.",
'nologinlink'                => 'Otvorite račun',
'createaccount'              => 'Napravi korisnički račun',
'gotaccount'                 => "Imate račun? '''$1'''.",
'gotaccountlink'             => 'Prijavi se',
'createaccountmail'          => 'e-mailom',
'createaccountreason'        => 'Razlog:',
'badretype'                  => 'Lozinke koje ste unijeli se ne poklapaju.',
'userexists'                 => 'Korisničko ime koje ste unijeli je već u upotrebi.
Molimo Vas da izaberete drugo ime.',
'loginerror'                 => 'Greška pri prijavljivanju',
'createaccounterror'         => 'Ne može se napraviti račun: $1',
'nocookiesnew'               => "Korisnički nalog je napravljen, ali niste prijavljeni.
{{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili.
Vi ste onemogućili kolačiće na Vašem računaru.
Molimo Vas da ih omogućite, a onda se prijavite sa svojim novim korisničkim imenom i šifrom.",
'nocookieslogin'             => "{{SITENAME}} koristi kolačiće (''cookies'') da bi se korisnici prijavili.
Vi ste onemogućili kolačiće na Vašem kompjuteru.
Molimo Vas da ih omogućite i da pokušate ponovo sa prijavom.",
'nocookiesfornew'            => 'Korisnički račun nije napravljen, jer nismo mogli da potvrdimo njegov izvor.
Provjerite da li su cookies omogućeni, ponovo učitajte ovu stranicu i pokušajte ponovo.',
'noname'                     => 'Niste izabrali ispravno korisničko ime.',
'loginsuccesstitle'          => 'Prijavljivanje uspješno',
'loginsuccess'               => "'''Sad ste prijavljeni na {{SITENAME}} kao \"\$1\".'''",
'nosuchuser'                 => 'Ne postoji korisnik sa imenom "$1".
Korisnička imena razlikuju velika i mala slova.
Provjerite vaše kucanje ili [[Special:UserLogin/signup|napravite novi korisnički račun]].',
'nosuchusershort'            => 'Ne postoji korisnik sa imenom "<nowiki>$1</nowiki>".
Provjerite da li ste dobro ukucali.',
'nouserspecified'            => 'Morate izabrati korisničko ime.',
'login-userblocked'          => 'Ovaj korisnik je blokiran. Prijava nije dozvoljena.',
'wrongpassword'              => 'Unijeli ste neispravnu šifru.
Molimo Vas da pokušate ponovno.',
'wrongpasswordempty'         => 'Unesena šifra je bila prazna.
Molimo Vas da pokušate ponovno.',
'passwordtooshort'           => 'Lozinka mora imati najmanje {{PLURAL:$1|1 znak|$1 znakova}}.',
'password-name-match'        => 'Vaša lozinka mora biti različita od Vašeg korisničkog imena.',
'password-login-forbidden'   => 'Korištenje ovog korisničkog imena i lozinke je zabranjeo.',
'mailmypassword'             => 'Pošalji mi novu lozinku putem E-maila',
'passwordremindertitle'      => 'Nova privremena lozinka za {{SITENAME}}',
'passwordremindertext'       => 'Neko (vjerovatno Vi, sa IP adrese $1) je zahtjevao da vam pošaljemo novu šifru za {{SITENAME}}  ($4). Privremena šifra za korisnika "$2" je napravljena i glasi "$3". Ako ste to željeli, sad treba da se prijavite i promjenite šifru.
Vaša privremena šifra će isteči za {{PLURAL:$5|$5 dan|$5 dana}}.

Ako je neko drugi napravio ovaj zahtjev ili ako ste se sjetili vaše šifre i ne želite više da je promjenite, možete da ignorišete ovu poruku i da nastavite koristeći vašu staru šifru.',
'noemail'                    => 'Ne postoji adresa e-maila za korisnika "$1".',
'noemailcreate'              => 'Morate da navedete valjanu e-mail adresu',
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
'usernamehasherror'          => 'Korisničko ime ne može sadržavati haš znakove',
'login-throttled'            => 'Previše puta ste se pokušali prijaviti.
Molimo Vas da sačekate prije nego što pokušate ponovo.',
'loginlanguagelabel'         => 'Jezik: $1',
'suspicious-userlogout'      => 'Vaš zahtjev za odjavu je odbijen jer je poslan preko pokvarenog preglednika ili keširanog proksija.',

# E-mail sending
'php-mail-error-unknown' => 'Nepoznata greška u PHP funkciji mail()',

# JavaScript password checks
'password-strength'            => 'Procijenjena snaga lozinke/zaporke: $1',
'password-strength-bad'        => 'LOŠA',
'password-strength-mediocre'   => 'osrednja',
'password-strength-acceptable' => 'prihvatljiva',
'password-strength-good'       => 'dobra',
'password-retype'              => 'Ponovite lozinku/zaporku ovdje',
'password-retype-mismatch'     => 'Lozinke/zaporke se ne slažu',

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
'resetpass-submit-cancel'   => 'Odustani',
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
'anonpreviewwarning'               => "''Niste prijavljeni. Vaša IP adresa će biti zabilježena u historiji ove stranice.''",
'missingsummary'                   => "'''Podsjećanje:''' Niste unijeli sažetak izmjene.
Ako kliknete na Sačuvaj/Snimi, Vaša izmjena će biti snimljena bez sažetka.",
'missingcommenttext'               => 'Molimo unesite komentar ispod.',
'missingcommentheader'             => "'''Podsjetnik:''' Niste napisali temu/naslov za ovaj komentar.
Ako ponovo kliknete na '''{{int:savearticle}}''', Vaše izmjene će biti snimljene bez teme/naslova.",
'summary-preview'                  => 'Pretpregled sažetka:',
'subject-preview'                  => 'Pretpregled teme/naslova:',
'blockedtitle'                     => 'Korisnik je blokiran',
'blockedtext'                      => "'''Vaše korisničko ime ili IP adresa je blokirana.'''

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
'nosuchsectiontitle'               => 'Ne mogu pronaći sekciju',
'nosuchsectiontext'                => 'Pokušali ste uređivati sekciju koja ne postoji.
Možda je premještena ili obrisana dok ste pregledavali stranicu.',
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
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretraživati srodne registre],
ili [{{fullurl:{{FULLPAGENAME}}|action=edit}} urediti ovu stranicu]</span>.',
'noarticletext-nopermission'       => 'Trenutno nema teksta na ovoj stranici.
Možete [[Special:Search/{{PAGENAME}}|tražiti ovaj naslov stranice]] na drugim stranicama ili <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti povezane registre]</span>.',
'userpage-userdoesnotexist'        => 'Korisnički račun "$1" nije registrovan.
Molimo provjerite da li želite napraviti/izmijeniti ovu stranicu.',
'userpage-userdoesnotexist-view'   => 'Korisnički račun "$1" nije registrovan.',
'blocked-notice-logextract'        => 'Ovaj korisnik je trenutno blokiran.
Posljednje stavke evidencije blokiranja možete pogledati ispod:',
'clearyourcache'                   => "'''Pažnja: Nakon što snimite izmjene, morate \"osvježiti\" keš memoriju vašeg pretraživača da bi ste vidjeli nova podešenja.'''
'''Mozilla / Firefox / Safari:''' držite ''Shift'' tipku i kliknite na ''Reload'' dugme ili ''Ctrl-R'' ili ''Ctrl-F5'' (''Command-R'' na Macintoshu);
'''Konqueror:''' klikni na ''Reload'' ili pritisnite dugme ''F5'';
'''Opera:''' očistite \"keš\" preko izbornika ''Tools → Preferences'';
'''Internet Explorer:''' držite tipku ''Ctrl'' i kliknite na ''Refresh'' ili pritisnite ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Sugestija:''' Koristite 'Prikaži izgled' dugme da testirate svoj novi CSS prije nego što ga snimite.",
'userjsyoucanpreview'              => "'''Sugestija:''' Koristite 'Prikaži izgled' dugme da testirate svoj novi JS prije nego što ga snimite.",
'usercsspreview'                   => "'''Zapamtite ovo je samo izgled Vašeg CSS-a.'''
'''Još uvijek nije snimljen!'''",
'userjspreview'                    => "'''Zapamite da je ovo samo test/pretpregled Vaše JavaScript-e.'''
'''Još uvijek nije snimljena!'''",
'sitecsspreview'                   => "'''Zapamtite ovo je samo izgled Vašeg CSS-a.'''
'''Još uvijek nije snimljen!'''",
'sitejspreview'                    => "'''Zapamtite ovo je samo izgled ovog koda JavaScripte.'''
'''Još uvijek nije snimljen!'''",
'userinvalidcssjstitle'            => "'''Upozorenje:''' Ne postoji interfejs (skin) pod imenom \"\$1\".
Ne zaboravite da imena stranica s .css i .js kodom počinju malim slovom, npr. {{ns:user}}:Foo/vector.css, a ne {{ns:user}}:Foo/Vector.css.",
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
'explainconflict'                  => "Neko drugi je promujenio ovu stranicu otkad ste Vi počeli da je mijenjate.
Gornje tekstualno polje sadrži tekst stranice koji trenutno postoji.
Vaše izmjene su prikazane u donjem tekstu.
Moraćete da unesete svoje promjene u postojeći tekst.
'''Samo''' tekst u gornjem tekstualnom polju će biti snimljen kad pritisnete \"{{int:savearticle}}\".",
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
'copyrightwarning2'                => "Zapamtite da svaki doprinos na stranici {{SITENAME}} može biti izmijenjen, promijenjen ili uklonjen od strane ostalih korisnika. Ako ne želite da ovo desi sa Vašim tekstom, onda ga nemojte slati ovdje.<br />
Također nam garantujete da ste ovo Vi napisali, ili da ste ga kopirali iz javne domene ili sličnog slobodnog izvora informacija (pogledajte $1 za više detalja).
'''NE ŠALJITE DJELA ZAŠTIĆENA AUTORSKIM PRAVOM BEZ DOZVOLE!'''",
'longpageerror'                    => "'''Greška: Tekst, koji ste poslali, je dug $1 kilobajta, što je veće od maksimuma, koji iznosi $2 kilobajta.
Stranica ne može biti spremljena.'''",
'readonlywarning'                  => "'''PAŽNJA: Baza je zaključana zbog održavanja, tako da nećete moći da snimite svoje izmjene za sada.
Možda želite da kopirate i nalijepite tekst u tekst editor i sačuvate ga za kasnije.'''

Administrator koji je zaključao bazu je naveo slijedeće objašnjenje: $1",
'protectedpagewarning'             => "'''PAŽNJA: Ova stranica je zaključana tako da samo korisnici sa administratorskim privilegijama mogu da je mijenjaju.'''
Posljednja stavka u registru je prikazana ispod kao referenca:",
'semiprotectedpagewarning'         => "'''Pažnja:''' Ova stranica je zaključana tako da je samo registrovani korisnici mogu uređivati.
Posljednja stavka registra je prikazana ispod kao referenca:",
'cascadeprotectedwarning'          => "'''Upozorenje:''' Ova stranica je zaključana tako da je samo administratori mogu mijenjati, jer je ona uključena u {{PLURAL:$1|ovu, lančanu povezanu, zaštićenu stranicu|sljedeće, lančano povezane, zaštićene stranice}}:",
'titleprotectedwarning'            => "'''UPOZORENJE: Ova stranica je zaključana tako da su potrebna [[Special:ListGroupRights|posebna prava]] da se ona napravi.'''
Posljednja stavka registra je prikazana ispod kao referenca:",
'templatesused'                    => '{{PLURAL:$1|Šablon|Šabloni}} koji su upotrebljeni na ovoj stranici:',
'templatesusedpreview'             => '{{PLURAL:$1|Šablon|Šabloni}} prikazani u ovom pregledu:',
'templatesusedsection'             => '{{PLURAL:$1|Šablon|Šabloni}} korišteni u ovoj sekciji:',
'template-protected'               => '(zaštićeno)',
'template-semiprotected'           => '(polu-zaštićeno)',
'hiddencategories'                 => 'Ova stranica pripada {{PLURAL:$1|1 skrivenoj kategoriji|$1 skrivenim kategorijama}}:',
'nocreatetitle'                    => 'Stvaranje stranica ograničeno',
'nocreatetext'                     => '{{SITENAME}} je ograničio/la postavljanje novih stranica.
Možete se vratiti i uređivati već postojeće stranice ili se [[Special:UserLogin|prijaviti ili otvoriti korisnički račun]].',
'nocreate-loggedin'                => 'Nemate dopuštenje da kreirate nove stranice.',
'sectioneditnotsupported-title'    => 'Uređivanje sekcije nije podržano',
'sectioneditnotsupported-text'     => 'Uređivanje sekcije nije podržano na ovoj stranici.',
'permissionserrors'                => 'Greške pri odobrenju',
'permissionserrorstext'            => 'Nemate dopuštenje da to uradite, iz {{PLURAL:$1|slijedećeg razloga|slijedećih razloga}}:',
'permissionserrorstext-withaction' => 'Nemate dozvolu za $2, zbog {{PLURAL:$1|sljedećeg|sljedećih}} razloga:',
'recreate-moveddeleted-warn'       => "'''Upozorenje: Postavljate stranicu koja je prethodno brisana.'''

Razmotrite da li je nastavljanje uređivanja ove stranice u skladu s pravilima.
Ovdje je naveden registar brisanja i premještanja s obrazloženjem:",
'moveddeleted-notice'              => 'Ova stranica je obrisana.
Registar brisanja i premještanja stranice je prikazan ispod kao referenca.',
'log-fulllog'                      => 'Vidi potpuni registar',
'edit-hook-aborted'                => 'Izmjena je poništena putem interfejsa.
Nije ponuđeno nikakvo objašnjenje.',
'edit-gone-missing'                => 'Stranica se nije mogla osvježiti.
Izgleda da je obrisana.',
'edit-conflict'                    => 'Sukob izmjena.',
'edit-no-change'                   => 'Vaša izmjena je ignorirana, jer nije bilo promjena teksta stranice.',
'edit-already-exists'              => 'Stranica nije mogla biti kreirana.
Izgleda da već postoji.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Upozorenje: Ova stranica sadrži previše poziva opterećujućih parserskih funkcija.

Trebalo bi imati manje od $2 {{PLURAL:$2|poziv|poziva}}, a sad ima {{PLURAL:$1|$1 poziv|$1 poziva}}.',
'expensive-parserfunction-category'       => 'Stranice sa previše poziva parserskih funkcija',
'post-expand-template-inclusion-warning'  => "'''Upozorenje:''' Šablon koji je uključen je prevelik.
Neki šabloni neće biti uključeni.",
'post-expand-template-inclusion-category' => 'Stranice gdje su uključeni šabloni preveliki',
'post-expand-template-argument-warning'   => "'''Upozorenje:''' Ova stranica sadrži najmanje jedan argument u šablonu koji ima preveliku veličinu.
Ovakvi argumenti se trebaju izbjegavati.",
'post-expand-template-argument-category'  => 'Stranice koje sadrže nedostajuće argumente u šablonu',
'parser-template-loop-warning'            => 'Otkrivena kružna greška u šablonu: [[$1]]',
'parser-template-recursion-depth-warning' => 'Dubina uključivanja šablona prekoračena ($1)',
'language-converter-depth-warning'        => 'Prekoračena granica dubine jezičkog pretvarača ($1)',

# "Undo" feature
'undo-success' => 'Izmjena se može vratiti.
Molimo da provjerite usporedbu ispod da budete sigurni da to želite učiniti, a zatim spremite promjene da bi ste završili vraćanje izmjene.',
'undo-failure' => 'Izmjene se ne mogu vratiti zbog konflikta sa izmjenama u međuvremenu.',
'undo-norev'   => 'Izmjena se ne može vratiti jer ne postoji ranija ili je obrisana.',
'undo-summary' => 'Vraćena izmjena $1 [[Special:Contributions/$2|korisnika $2]] ([[User talk:$2|razgovor]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nije moguće napraviti korisnički račun',
'cantcreateaccount-text' => "Pravljenje korisničkog računa sa ove IP adrese ('''$1''') je blokirano od strane [[User:$3|$3]].

Razlog koji je naveo $3 je ''$2''",

# History pages
'viewpagelogs'           => 'Pogledaj protokole ove stranice',
'nohistory'              => 'Ne postoji historija izmjena za ovu stranicu.',
'currentrev'             => 'Trenutna revizija',
'currentrev-asof'        => 'Trenutna revizija na dan $1',
'revisionasof'           => 'Izmjena od $1',
'revision-info'          => 'Trenutna revizija na dan $1',
'previousrevision'       => '← Starija revizija',
'nextrevision'           => 'Novija izmjena →',
'currentrevisionlink'    => 'Trenutna verzija',
'cur'                    => 'tren',
'next'                   => 'slijed',
'last'                   => 'preth',
'page_first'             => 'prva',
'page_last'              => 'zadnja',
'histlegend'             => "Odabir razlika: označite radio dugme verzija za usporedbu i pritisnite enter ili dugme na dnu.<br />
Objašnjenje: '''({{int:cur}})''' = razlika sa trenutnom verzijom,
'''({{int:last}})''' = razlika sa prethodnom verzijom, '''{{int:minoreditletter}}''' = manja izmjena.",
'history-fieldset-title' => 'Pretraga historije',
'history-show-deleted'   => 'Samo obrisane',
'histfirst'              => 'Najstarije',
'histlast'               => 'Najnovije',
'historysize'            => '({{PLURAL:$1|1 bajt|$1 bajta|$1 bajtova}})',
'historyempty'           => '(prazno)',

# Revision feed
'history-feed-title'          => 'Historija izmjena',
'history-feed-description'    => 'Historija promjena ove stranice na wikiju',
'history-feed-item-nocomment' => '$1 u $2',
'history-feed-empty'          => 'Tražena stranica ne postoji.
Moguće da je izbrisana sa wikija, ili preimenovana.
Pokušajte [[Special:Search|pretražiti wiki]] za slične stranice.',

# Revision deletion
'rev-deleted-comment'         => '(komentar uklonjen)',
'rev-deleted-user'            => '(korisničko ime uklonjeno)',
'rev-deleted-event'           => '(stavka registra obrisana)',
'rev-deleted-user-contribs'   => '[korisničko ime ili IP adresa uklonjeni - izmjena sakrivena u spisku doprinosa]',
'rev-deleted-text-permission' => "Revizija ove stranice je '''obrisana'''.
Detalje možete vidjeti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registru brisanja].",
'rev-deleted-text-unhide'     => "Revizija ove stranice je '''obrisana'''.
Detalje o tome možer vidjeti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registru brisanja].
Kao administrator još je uvijek možete [$1 vidjeti ovu reviziju] ako želite.",
'rev-suppressed-text-unhide'  => "Ova revizija stranice je '''uklonjena'''.
Možete pogledati detalje u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registru uklanjanja].
Kao administrator Vi je i dalje možete [$1 vidjeti ovu reviziju] ako želite.",
'rev-deleted-text-view'       => "Revizija ove stranice je '''obrisana'''.
Kao administrator, Vi je možete vidjeti; detalji o tome se mogu vidjeti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registru brisanja].",
'rev-suppressed-text-view'    => "Ova revizija stranice je '''uklonjena'''.
Kao administrator Vi je možete vidjeti; možete pogledati detalje u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registru uklanjanja].",
'rev-deleted-no-diff'         => "Ne možete vidjeti ove razlike jer je jedna od revizija '''obrisana'''.
Možete pregledati detalje u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registrima brisanja].",
'rev-suppressed-no-diff'      => "Ne možete vidjeti ove razlike jer je jedna od revizija '''obrisana'''.",
'rev-deleted-unhide-diff'     => "Jedna od revizija u ovom pregledu razlika je '''obrisana'''.
Možete pregledati detalje u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registru brisanja].
Kao administrator Vi još uvijek možete [$1 vidjeti ove razlike] ako želite da nastavite.",
'rev-suppressed-unhide-diff'  => "Jedna od revizija ove razlike je '''uklonjena'''.
Postoji mnogo detalja u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registru uklanjanja].
Kao administrator i dalje možete [$1 vidjeti ove razlike] ako želite da nastavite.",
'rev-deleted-diff-view'       => "Jedna od revizija u ovoj razlici je '''obrisana'''.
Kao administrator možete vidjeti ovu razliku, možda ima još detalja u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registru brisanja].",
'rev-suppressed-diff-view'    => "Jedna od revizija u ovoj razlici je '''sakrivena'''.
Kao administrator možete vidjeti ovu razliku, možda ima još detalja u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registru sakrivanja].",
'rev-delundel'                => 'pokaži/sakrij',
'rev-showdeleted'             => 'Pokaži',
'revisiondelete'              => 'Obriši/vrati revizije',
'revdelete-nooldid-title'     => 'Nije unesena tačna revizija',
'revdelete-nooldid-text'      => 'Niste precizno odredili odredišnu reviziju/revizije da se izvrši ova funkcija,
ili ta revizija ne postoji, ili pokušavate sakriti trenutnu reviziju.',
'revdelete-nologtype-title'   => 'Nije naveden tip registra',
'revdelete-nologtype-text'    => 'Niste odredili tip registra za izvršavanje ove akcije na njemu.',
'revdelete-nologid-title'     => 'Nevaljana stavka registra',
'revdelete-nologid-text'      => 'Niste odredili ciljnu stavku registra za izvršavanje ove funkcije ili navedena stavka ne postoji.',
'revdelete-no-file'           => 'Navedena datoteka ne postoji.',
'revdelete-show-file-confirm' => 'Da li ste sigurni da želite pogledati obrisanu reviziju datoteke "<nowiki>$1</nowiki>" od $2 u $3?',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Odabrana revizija|Odabrane revizije}} od [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Označena stavka registra|Označene stavke registra}}:'''",
'revdelete-text'              => "'''Obrisane revizije i događaji će i dalje biti vidljivi u historiji stranice i registrima, ali dijelovi njenog sadržaja neće biti dostupni javnosti.'''
Drugi administratori projekta {{SITENAME}} će i dalje moći pristupiti sakrivenom sadržaju i mogu ga ponovo vratiti kroz ovaj interfejs, osim ako nisu postavljena dodatna ograničenja.",
'revdelete-confirm'           => 'Molimo potvrdite da namjeravate ovo učiniti, da razumijete posljedice i da to činite u skladu s [[{{MediaWiki:Policy-url}}|pravilima]].',
'revdelete-suppress-text'     => "Ograničenja bi trebala biti korištena '''samo''' u sljedećim slučajevima:
* Osjetljive korisničke informacije
*: ''kućne adrese, brojevi telefona, brojevi bankovnih kartica itd.''",
'revdelete-legend'            => 'Postavi ograničenja vidljivosti',
'revdelete-hide-text'         => 'Sakrij tekst revizije',
'revdelete-hide-image'        => 'Sakrij sadržaj datoteke',
'revdelete-hide-name'         => 'Sakrij akciju i cilj',
'revdelete-hide-comment'      => 'Sakrij izmjene komentara',
'revdelete-hide-user'         => 'Sakrij korisničko ime urednika/IP',
'revdelete-hide-restricted'   => 'Ograniči podatke za administratore kao i za druge korisnike',
'revdelete-radio-same'        => '(ne mijenjaj)',
'revdelete-radio-set'         => 'Da',
'revdelete-radio-unset'       => 'Ne',
'revdelete-suppress'          => 'Sakrij podatke od administratora kao i od drugih',
'revdelete-unsuppress'        => 'Ukloni ograničenja na vraćenim revizijama',
'revdelete-log'               => 'Razlog:',
'revdelete-submit'            => 'Primijeni na odabrane {{PLURAL:$1|izmjena|izmjene}}',
'revdelete-logentry'          => 'promijenjena vidljivost revizije [[$1]]',
'logdelete-logentry'          => 'promijenjena vidljivost događaja [[$1]]',
'revdelete-success'           => "'''Vidljivost revizije uspješno postavljena.'''",
'revdelete-failure'           => "'''Zapisnik vidljivosti nije mogao biti postavljen:'''
$1",
'logdelete-success'           => "'''Vidljivost evidencije uspješno postavljena.'''",
'logdelete-failure'           => "'''Registar vidljivosti nije mogao biti postavljen:'''
$1",
'revdel-restore'              => 'promijeni dostupnost',
'revdel-restore-deleted'      => 'izbrisane izmjene',
'revdel-restore-visible'      => 'vidljive izmjene',
'pagehist'                    => 'Historija stranice',
'deletedhist'                 => 'Izbrisana historija',
'revdelete-content'           => 'sadržaj',
'revdelete-summary'           => 'sažetak izmjene',
'revdelete-uname'             => 'korisničko ime',
'revdelete-restricted'        => 'primijenjena ograničenja za administratore',
'revdelete-unrestricted'      => 'uklonjena ograničenja za administratore',
'revdelete-hid'               => 'sakrij $1',
'revdelete-unhid'             => 'otkrij $1',
'revdelete-log-message'       => '$1 za $2 {{PLURAL:$2|izmjenu|izmjene|izmjena}}',
'logdelete-log-message'       => '$1 za $2 {{PLURAL:$2|događaj|događaja}}',
'revdelete-hide-current'      => 'Greška pri sakrivanju stavke od $2, $1: ovo je trenutna revizija.
Ne može biti sakrivena.',
'revdelete-show-no-access'    => 'Greška pri prikazivanju stavke od $2, $1: ova stavka je označena kao "zaštićena".
Nemate pristup do ove stavke.',
'revdelete-modify-no-access'  => 'Greška pri izmjeni stavke od $2, $1: ova stavka je označena kao "zaštićena".
Nemate pristup ovoj stavci.',
'revdelete-modify-missing'    => 'Greška pri mijenjanju stavke ID $1: nedostaje u bazi podataka!',
'revdelete-no-change'         => "'''Upozorenje:''' stavka od $2, $1 već posjeduje zatražene postavke vidljivosti.",
'revdelete-concurrent-change' => 'Greška pri mijenjanju stavke od $2, $1: njen status je izmijenjen od strane nekog drugog dok ste je pokušavali mijenjati.
Molimo provjerite zapise.',
'revdelete-only-restricted'   => 'Greška pri sakrivanju stavke od dana $2, $1: ne možete ukloniti stavke od pregledavanja administratora bez da odaberete neku od drugih opcija za uklanjanje.',
'revdelete-reason-dropdown'   => '*Uobičajeni razlozi brisanja
** Kršenje autorskih prava
** Neadekvatni lični podaci
** Potencijalno klevetničke informacije',
'revdelete-otherreason'       => 'Ostali/dodatni razlog:',
'revdelete-reasonotherlist'   => 'Ostali razlozi',
'revdelete-edit-reasonlist'   => 'Uredi razloge brisanja',
'revdelete-offender'          => 'Autor revizije:',

# Suppression log
'suppressionlog'     => 'Registri sakrivanja',
'suppressionlogtext' => 'Ispod je spisak brisanja i blokiranja koja su povezana sa sadržajem koji je sakriven od administratora. Vidi [[Special:IPBlockList|spisak IP blokiranja]] za pregled trenutno važećih blokada.',

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|Premještena jedna revizija|Premještene $3 revizije|Premješteno $3 revizija}} iz $1 na $2',
'revisionmove'                 => 'Premještanje revizija sa "$1"',
'revmove-explain'              => 'Slijedeće revizije će biti premještene sa $1 na određenu ciljnu stranicu. Ako ciljna stranica ne postoji, bit će napravljenja. U suprotnom, ove revizije će biti spojene u historiji ciljne stranice.',
'revmove-legend'               => 'Postavite ciljnu stranicu i sažetak',
'revmove-submit'               => 'Premjestite revizije na odabranu stranicu',
'revisionmoveselectedversions' => 'Premjesti označene revizije',
'revmove-reasonfield'          => 'Razlog:',
'revmove-titlefield'           => 'Ciljna stranica:',
'revmove-badparam-title'       => 'Loši parametri',
'revmove-badparam'             => 'Vaš zahtjev sadrži nevaljane ili nedovoljne parametre. Molimo pritisnite "natrag" i pokušajte ponovo.',
'revmove-norevisions-title'    => 'Nevaljana ciljna revizija',
'revmove-norevisions'          => 'Niste odredili jednu ili više ciljnih revizija radi izvršenja ove funkcije ili navedena revizija ne postoji.',
'revmove-nullmove-title'       => 'Loš naslov',
'revmove-nullmove'             => 'Izvorna i ciljna stranica su iste. Molimo pritisnite "nazad" i unesite drugo ime stranice koje nije isto kao "$1".',
'revmove-success-existing'     => '{{PLURAL:$1|Jedna revizija iz [[$2]] je premještena|$1 su premještene iz [[$2]]|$1 je premješteno iz [[$2]]}} postojeće stranice [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Jedna revizija iz [[$2]] je premještena|$1 su premještene iz [[$2]]|$1 je premješteno iz [[$2]]}} na novonapravljenu stranicu [[$3]].',

# History merging
'mergehistory'                     => 'Spoji historije stranice',
'mergehistory-header'              => 'Ova stranica Vam omogućuje spajanje revizija historije neke izvorne stranice u novu stranicu. Zapamtite da će ova promjena ostaviti nepromjenjen sadržaj historije stranice.',
'mergehistory-box'                 => 'Spajanje revizija za dvije stranice:',
'mergehistory-from'                => 'Izvorna stranica:',
'mergehistory-into'                => 'Odredišna stranica:',
'mergehistory-list'                => 'Historija izmjena koja se može spojiti',
'mergehistory-merge'               => 'Slijedeće revizije stranice [[:$1]] mogu biti spojene u [[:$2]].
Koristite dugmiće u stupcu da bi ste spojili revizije koje su napravljene prije navedenog vremena.
Korištenje navigacionih linkova će resetovati ovaj stupac.',
'mergehistory-go'                  => 'Prikaži izmjene koje se mogu spojiti',
'mergehistory-submit'              => 'Spoji revizije',
'mergehistory-empty'               => 'Nema revizija za spajanje.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revizija|revizije|revizija}} stranice [[:$1]] uspješno spojeno u [[:$2]].',
'mergehistory-fail'                => 'Ne može se izvršiti spajanje historije, molimo provjerite opet stranicu i parametre vremena.',
'mergehistory-no-source'           => 'Izvorna stranica $1 ne postoji.',
'mergehistory-no-destination'      => 'Odredišna stranica $1 ne postoji.',
'mergehistory-invalid-source'      => 'Izvorna stranica mora imati valjan naslov.',
'mergehistory-invalid-destination' => 'Odredišna stranica mora imati valjan naslov.',
'mergehistory-autocomment'         => 'Spojeno [[:$1]] u [[:$2]]',
'mergehistory-comment'             => 'Spojeno [[:$1]] u [[:$2]]: $3',
'mergehistory-same-destination'    => 'Izvorne i odredišne stranice ne mogu biti iste',
'mergehistory-reason'              => 'Razlog:',

# Merge log
'mergelog'           => 'Registar spajanja',
'pagemerge-logentry' => 'spojeno [[$1]] u [[$2]] (sve do $3 revizije)',
'revertmerge'        => 'Ukini spajanje',
'mergelogpagetext'   => 'Ispod je spisak nedavnih spajanja historija stranica.',

# Diffs
'history-title'            => 'Historija izmjena stranice "$1"',
'difference'               => '(Razlika između revizija)',
'difference-multipage'     => '(Razlika između stranica)',
'lineno'                   => 'Linija $1:',
'compareselectedversions'  => 'Uporedite označene verzije',
'showhideselectedversions' => 'Pokaži/sakrij odabrane verzije',
'editundo'                 => 'ukloni ovu izmjenu',
'diff-multi'               => '({{PLURAL:$1|Nije prikazana jedna međuverzija|Nisu prikazane $1 međuverzije|Nije prikazano $1 međuverzija}}) od strane {{PLURAL:$2|korisnika|korisnika}}',
'diff-multi-manyusers'     => '({{PLURAL:$1|Nije prikazana jedna međuverzija|Nisu prikazane $1 međuverzije|Nije prikazano $1 međuverzija}}) od strane {{PLURAL:$2|korisnika|korisnika}}',

# Search results
'searchresults'                    => 'Rezultati pretrage',
'searchresults-title'              => 'Rezultati pretrage za "$1"',
'searchresulttext'                 => 'Za više informacija o pretraživanju {{SITENAME}}, v. [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Tražili ste \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|sve stranice koje počinju sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sve stranice koje vode do "$1"]])',
'searchsubtitleinvalid'            => "Tražili ste '''$1'''",
'toomanymatches'                   => 'Pronađeno je previše rezultata, molimo pokušajte unijeti konkretniji izraz',
'titlematches'                     => 'Naslov članka odgovara',
'notitlematches'                   => 'Nijedan naslov stranice ne odgovara',
'textmatches'                      => 'Tekst stranice odgovara',
'notextmatches'                    => 'Tekst stranice ne odgovara',
'prevn'                            => 'prethodna {{PLURAL:$1|$1}}',
'nextn'                            => 'sljedećih {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|Prethodni $1 rezultat|Prethodna $1 rezultata|Prethodnih $1 rezultata}}',
'nextn-title'                      => '{{PLURAL:$1|Slijedeći $1 rezultat|Slijedeća $1 rezultata|Slijedećih $1 rezultata}}',
'shown-title'                      => 'Pokaži $1 {{PLURAL:$1|rezultat|rezultata}} po stranici',
'viewprevnext'                     => 'Pogledaj ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opcije pretrage',
'searchmenu-exists'                => "'''Postoji stranica pod nazivom \"[[:\$1]]\" na ovoj wiki'''",
'searchmenu-new'                   => "'''Napravi stranicu \"[[:\$1|\$1]]\" na ovoj wiki!'''",
'searchmenu-new-nocreate'          => '"$1" je nevaljani naziv za stranicu ili je ne možete stvoriti.',
'searchhelp-url'                   => 'Help:Sadržaj',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Pregledaj stranice sa ovim prefiksom]]',
'searchprofile-articles'           => 'Stranice sadržaja',
'searchprofile-project'            => 'Stranice pomoći i projekta',
'searchprofile-images'             => 'Multimedija',
'searchprofile-everything'         => 'Sve',
'searchprofile-advanced'           => 'Napredno',
'searchprofile-articles-tooltip'   => 'Pretraga u $1',
'searchprofile-project-tooltip'    => 'Pretraga u $1',
'searchprofile-images-tooltip'     => 'Traži datoteke',
'searchprofile-everything-tooltip' => 'Pretraži sve sadržaje (ukljujući i stranice za razgovor)',
'searchprofile-advanced-tooltip'   => 'Traži u ostalim imenskim prostorima',
'search-result-size'               => '$1 ({{PLURAL:$2|1 riječ|$2 riječi}})',
'search-result-category-size'      => '{{PLURAL:$1|1 član|$1 člana|$1 članova}} ({{PLURAL:$2|1 podkategorija|$2 podkategorije|$2 podkategorija}}, {{PLURAL:$3|1 datoteka|$3 datoteke|$3 datoteka}})',
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
'searcheverything-enable'          => 'Pretraga u svim imenskim prostorima',
'searchrelated'                    => 'povezano',
'searchall'                        => 'sve',
'showingresults'                   => "Dole {{PLURAL:$1|je prikazan '''1''' rezultat|su prikazana '''$1''' rezultata|je prikazano '''$1''' rezultata}} počev od '''$2'''.",
'showingresultsnum'                => "Dolje {{PLURAL:$3|je prikazan '''1''' rezultat|su prikazana '''$3''' rezultata|je prikazano '''$3''' rezultata}} počev od #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultat '''$1''' od '''$3'''|Rezultati '''$1 - $2''' od '''$3'''}} za '''$4'''",
'nonefound'                        => "'''Napomene''': Samo neki imenski prostori se pretražuju po početnim postavkama.
Pokušajte u svoju pretragu staviti ''all:'' da se pretražuje cjelokupan sadržaj (uključujući stranice za razgovor, šablone/predloške itd.), ili koristite imenski prostor kao prefiks.",
'search-nonefound'                 => 'Nisu pronađeni rezultati koji odgovaraju upitu.',
'powersearch'                      => 'Napredna pretraga',
'powersearch-legend'               => 'Napredna pretraga',
'powersearch-ns'                   => 'Pretraga u imenskim prostorima:',
'powersearch-redir'                => 'Pokaži spisak preusmjerenja',
'powersearch-field'                => 'Traži',
'powersearch-togglelabel'          => 'Označi:',
'powersearch-toggleall'            => 'Sve',
'powersearch-togglenone'           => 'Ništa',
'search-external'                  => 'Vanjska/spoljna pretraga',
'searchdisabled'                   => 'Pretraga teksta na ovoj Wiki je trenutno onemogućena.
U međuvremenu možete pretraživati preko Googlea.
Uzmite u obzir da njegovi indeksi za ovu Wiki ne moraju biti ažurirani.',

# Quickbar
'qbsettings'               => 'Podešavanja brze palete',
'qbsettings-none'          => 'Nikakva',
'qbsettings-fixedleft'     => 'Fiksirana lijevo',
'qbsettings-fixedright'    => 'Fiksirana desno',
'qbsettings-floatingleft'  => 'Plutajuća lijevo',
'qbsettings-floatingright' => 'Plutajuća desno',

# Preferences page
'preferences'                   => 'Postavke',
'mypreferences'                 => 'Moje postavke',
'prefs-edits'                   => 'Broj izmjena:',
'prefsnologin'                  => 'Niste prijavljeni',
'prefsnologintext'              => 'Da biste mogli podešavati korisničke postavke, morate <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} biti prijavljeni]</span>.',
'changepassword'                => 'Promijeni lozinku',
'prefs-skin'                    => 'Izgled (skin)',
'skin-preview'                  => 'Pretpregled',
'prefs-math'                    => 'Prikazivanje matematike',
'datedefault'                   => 'Bez preferenci',
'prefs-datetime'                => 'Datum i vrijeme',
'prefs-personal'                => 'Korisnički profil',
'prefs-rc'                      => 'Podešavanje nedavnih izmjena',
'prefs-watchlist'               => 'Praćene stranice',
'prefs-watchlist-days'          => 'Broj dana za prikaz u spisku praćenja:',
'prefs-watchlist-days-max'      => '(najviše 7 dana)',
'prefs-watchlist-edits'         => 'Najveći broj izmjena za prikaz u proširenom spisku praćenja:',
'prefs-watchlist-edits-max'     => '(najveći broj: 1000)',
'prefs-watchlist-token'         => 'Token spiska za praćenje:',
'prefs-misc'                    => 'Ostala podešavanja',
'prefs-resetpass'               => 'Promijeni lozinku',
'prefs-email'                   => 'E-mail opcije',
'prefs-rendering'               => 'Izgled',
'saveprefs'                     => 'Snimi postavke',
'resetprefs'                    => 'Poništi nesnimljene promjene postavki',
'restoreprefs'                  => 'Vrati sve pretpostavljene postavke',
'prefs-editing'                 => 'Uređivanje',
'prefs-edit-boxsize'            => 'Veličina prozora za uređivanje.',
'rows'                          => 'Redova:',
'columns'                       => 'Kolona:',
'searchresultshead'             => 'Postavke rezultata pretrage',
'resultsperpage'                => 'Pogodaka po stranici:',
'contextlines'                  => 'Linija po pogotku:',
'contextchars'                  => 'Karaktera konteksta po liniji:',
'stub-threshold'                => 'Formatiranje <a href="#" class="stub">linkova stranica u začetku</a> (bajtova):',
'stub-threshold-disabled'       => 'Isključen/a',
'recentchangesdays'             => 'Broj dana za prikaz u nedavnim izmjenama:',
'recentchangesdays-max'         => '(najviše $1 {{PLURAL:$1|dan|dana}})',
'recentchangescount'            => 'Broj uređivanja za prikaz po pretpostavkama:',
'prefs-help-recentchangescount' => 'Ovo uključuje nedavne izmjene, historije stranice i registre.',
'prefs-help-watchlist-token'    => 'Popunjavanjem ovog polja tajnim ključem će generisati RSS feed za Vaš spisak praćenja.
Svako ko zna ključ u ovom polju će biti u mogućnosti da pročita Vaš spisak praćenja, tako da trebate izabrati sigurnu vrijednost.
Ovdje su navedene neke nasumično odabrane vrijednosti koje možete koristiti: $1',
'savedprefs'                    => 'Vaša postavke su snimljene.',
'timezonelegend'                => 'Vremenska zona:',
'localtime'                     => 'Lokalno vrijeme:',
'timezoneuseserverdefault'      => 'Koristi postavke servera',
'timezoneuseoffset'             => 'Ostalo (odredi odstupanje)',
'timezoneoffset'                => 'Odstupanje¹:',
'servertime'                    => 'Vrijeme na serveru:',
'guesstimezone'                 => 'Popuni iz preglednika',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlantski okean',
'timezoneregion-australia'      => 'Australija',
'timezoneregion-europe'         => 'Evropa',
'timezoneregion-indian'         => 'Indijski okean',
'timezoneregion-pacific'        => 'Tihi okean',
'allowemail'                    => 'Dozvoli e-mail od ostalih korisnika',
'prefs-searchoptions'           => 'Opcije pretrage',
'prefs-namespaces'              => 'Imenski prostori',
'defaultns'                     => 'Inače tražite u ovim imenskim prostorima:',
'default'                       => 'standardno',
'prefs-files'                   => 'Datoteke',
'prefs-custom-css'              => 'Prilagođeni CSS',
'prefs-custom-js'               => 'Prilagođeni JS',
'prefs-common-css-js'           => 'Zajednički CSS/JS za sve izglede (skinove):',
'prefs-reset-intro'             => 'Možete koristiti ovu stranicu da poništite Vaše postavke na ovom sajtu na pretpostavljene vrijednosti.
Ovo se ne može vratiti unazad.',
'prefs-emailconfirm-label'      => 'E-mail potvrda:',
'prefs-textboxsize'             => 'Veličina prozora za uređivanje',
'youremail'                     => 'E-mail:',
'username'                      => 'Korisničko ime:',
'uid'                           => 'Korisnički ID:',
'prefs-memberingroups'          => 'Član {{PLURAL:$1|grupe|grupa}}:',
'prefs-registration'            => 'Vrijeme registracije:',
'yourrealname'                  => 'Vaše pravo ime:',
'yourlanguage'                  => 'Jezik:',
'yournick'                      => 'Nadimak (za potpise):',
'prefs-help-signature'          => 'Komentari na stranicama za razgovor trebaju biti potpisani sa "<nowiki>~~~~</nowiki>" koje će biti pretvoreno u vaš potpis i vrijeme.',
'badsig'                        => 'Loš sirovi potpis.
Provjerite HTML tagove.',
'badsiglength'                  => 'Vaš potpis je predug.
Mora biti manji od $1 {{PLURAL:$1|znaka|znaka|znakova}}.',
'yourgender'                    => 'Spol:',
'gender-unknown'                => 'neodređen',
'gender-male'                   => 'Muški',
'gender-female'                 => 'Ženski',
'prefs-help-gender'             => 'Opcionalno: koristi se za ispravke gramatičkog roda u porukama softvera.
Ova informacija će biti javna.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'Pravo ime nije obavezno.
Ako izaberete da date ime, biće korišteno za pripisivanje Vašeg rada.',
'prefs-help-email'              => 'E-mail adresa je opcionalna, ali Vam omogućava da Vam se pošalje nova šifra u slučaju da je izgubite ili zaboravite.',
'prefs-help-email-others'       => 'Također možete da odaberete da vas drugi kontaktiraju putem vaše korisničke stranice ili stranice za razgovor bez otkrivanja vašeg identiteta.',
'prefs-help-email-required'     => 'Neophodno je navesti e-mail adresu.',
'prefs-info'                    => 'Osnovne informacije',
'prefs-i18n'                    => 'Internacionalizacije',
'prefs-signature'               => 'Potpis',
'prefs-dateformat'              => 'Format datuma',
'prefs-timeoffset'              => 'Vremenska razlika',
'prefs-advancedediting'         => 'Napredne opcije',
'prefs-advancedrc'              => 'Napredne opcije',
'prefs-advancedrendering'       => 'Napredne opcije',
'prefs-advancedsearchoptions'   => 'Napredne opcije',
'prefs-advancedwatchlist'       => 'Napredne opcije',
'prefs-displayrc'               => 'Postavke displeja',
'prefs-displaysearchoptions'    => 'Postavke displeja',
'prefs-displaywatchlist'        => 'Postavke prikaza',
'prefs-diffs'                   => 'Razlike',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-mail adresa izgleda valjano',
'email-address-validity-invalid' => 'Unesite valjanu e-mail adresu',

# User rights
'userrights'                   => 'Postavke korisničkih prava',
'userrights-lookup-user'       => 'Menadžment korisničkih prava',
'userrights-user-editname'     => 'Unesi korisničko ime:',
'editusergroup'                => 'Uredi korisničke grupe',
'editinguser'                  => "Mijenjate korisnička prava korisnika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Uredi korisničke grupe',
'saveusergroups'               => 'Snimi korisničke grupe',
'userrights-groupsmember'      => 'Član:',
'userrights-groupsmember-auto' => 'Uključeni član od:',
'userrights-groups-help'       => 'Možete promijeniti grupe kojima ovaj korisnik pripada:
* Označeni kvadratić znači da je korisnik u toj grupi.
* Neoznačen kvadratić znači da korisnik nije u toj grupi.
* Oznaka * (zvjezdica) označava da Vi ne možete izbrisati ovu grupu ako je dodate i obrnutno.',
'userrights-reason'            => 'Razlog:',
'userrights-no-interwiki'      => 'Nemate dopuštenja da uređujete korisnička prava na drugim wikijima.',
'userrights-nodatabase'        => 'Baza podataka $1 ne postoji ili nije lokalna baza.',
'userrights-nologin'           => 'Morate se [[Special:UserLogin|prijaviti]] sa administratorskim računom da bi ste mogli postavljati korisnička prava.',
'userrights-notallowed'        => 'Vaš korisnički račun nema privilegije da dodaje prava korisnika.',
'userrights-changeable-col'    => 'Grupe koje možete mijenjati',
'userrights-unchangeable-col'  => 'Grupe koje ne možete mijenjati',

# Groups
'group'               => 'Grupa:',
'group-user'          => 'Korisnici',
'group-autoconfirmed' => 'Potvrđeni korisnici',
'group-bot'           => 'Botovi',
'group-sysop'         => 'Administratori',
'group-bureaucrat'    => 'Birokrati',
'group-suppress'      => 'Nadzornici',
'group-all'           => '(sve)',

'group-user-member'          => 'Korisnik',
'group-autoconfirmed-member' => 'Potvrđeni korisnik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Birokrat',
'group-suppress-member'      => 'Nadzornik',

'grouppage-user'          => '{{ns:project}}:Korisnici',
'grouppage-autoconfirmed' => '{{ns:project}}:Potvrđeni korisnici',
'grouppage-bot'           => '{{ns:project}}:Botovi',
'grouppage-sysop'         => '{{ns:project}}:Administratori',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrati',
'grouppage-suppress'      => '{{ns:project}}:Nadzornici',

# Rights
'right-read'                  => 'Čitanje stranica',
'right-edit'                  => 'Uređivanje stranica',
'right-createpage'            => 'Pravljenje stranica (ne uključujući stranice za razgovor)',
'right-createtalk'            => 'Pravljenje stranica za razgovor',
'right-createaccount'         => 'Pravljenje korisničkog računa',
'right-minoredit'             => 'Označavanje izmjena kao malih',
'right-move'                  => 'Preusmjeravanje stranica',
'right-move-subpages'         => 'Preusmjeravanje stranica sa svim podstranicama',
'right-move-rootuserpages'    => 'Premještanje stranica osnovnih korisnika',
'right-movefile'              => 'Premještanje datoteka',
'right-suppressredirect'      => 'Ne pravi preusmjeravanje sa starog imena pri preusmjeravanju stranica',
'right-upload'                => 'Postavljanje datoteka',
'right-reupload'              => 'Postavljanje nove verzije datoteke',
'right-reupload-own'          => 'Postavljanje nove verzije datoteke koju je postavio korisnik',
'right-reupload-shared'       => 'Postavljanje novih lokalnih verzija datoteka identičnih onima u zajedničkoj ostavi',
'right-upload_by_url'         => 'Postavljanje datoteke sa URL adrese',
'right-purge'                 => 'Osvježavanje keša za stranice bez potvrde',
'right-autoconfirmed'         => 'Uređivanje poluzaštićenih stranica',
'right-bot'                   => 'Postavljen kao automatski proces',
'right-nominornewtalk'        => "Male izmjene na stranici za razgovor ne uzrokuju prikazivanje oznake ''nova poruka'' na stranici za razgovor",
'right-apihighlimits'         => 'Korištenje viših ograničenja u API upitima',
'right-writeapi'              => "Korištenje opcije ''write API''",
'right-delete'                => 'Brisanje stranica',
'right-bigdelete'             => 'Brisanje stranica sa velikom historijom',
'right-deleterevision'        => 'Brisanje i vraćanje određenih revizija stranice',
'right-deletedhistory'        => 'Pregled stavki obrisane historije, bez povezanog teksta',
'right-deletedtext'           => 'Pregled obrisanog teksta i izmjena između obrisanih revizija',
'right-browsearchive'         => 'Pretraživanje obrisanih stranica',
'right-undelete'              => 'Vraćanje obrisanih stranica',
'right-suppressrevision'      => 'Pregled i povratak revizija sakrivenih od administratora',
'right-suppressionlog'        => 'Pregled privatnih evidencija',
'right-block'                 => 'Blokiranje uređivanja drugih korisnika',
'right-blockemail'            => 'Blokiranje korisnika da šalje e-mail',
'right-hideuser'              => 'Blokiranje korisničkog imena, i njegovo sakrivanje od javnosti',
'right-ipblock-exempt'        => 'Zaobilaženje IP blokada, autoblokada i blokada IP grupe',
'right-proxyunbannable'       => 'Zaobilaženje automatskih blokada proxy-ja',
'right-unblockself'           => 'Deblokiranje samog sebe',
'right-protect'               => 'Promjena nivoa zaštite i uređivanje zaštićenih stranica',
'right-editprotected'         => 'Uređivanje zaštićenih stranica (bez povezanih zaštita)',
'right-editinterface'         => 'Uređivanje korisničkog interfejsa',
'right-editusercssjs'         => 'Uređivanje CSS i JS datoteka drugih korisnika',
'right-editusercss'           => 'Uređivanje CSS datoteka drugih korisnika',
'right-edituserjs'            => 'Uređivanje Javascript datoteka drugih korisnika',
'right-rollback'              => 'Brzo vraćanje izmjena na zadnjeg korisnika koji je uređivao određenu stranicu',
'right-markbotedits'          => 'Označavanje vraćenih izmjena kao izmjene bota',
'right-noratelimit'           => 'Izbjegavanje ograničenja uzrokovanih brzinom',
'right-import'                => 'Uvoz stranica iz drugih wikija',
'right-importupload'          => 'Uvoz stranica putem postavljanja datoteke',
'right-patrol'                => 'Označavanje izmjena drugih korisnika patroliranim',
'right-autopatrol'            => 'Vlastite izmjene se automatski označavaju kao patrolirane',
'right-patrolmarks'           => 'Pregled oznaka patroliranja u spisku nedavnih izmjena',
'right-unwatchedpages'        => 'Gledanje spiska nepraćenih stranica',
'right-trackback'             => "Slanje ''trackbacka''",
'right-mergehistory'          => 'Spajanje historije stranica',
'right-userrights'            => 'Uređivanje svih korisničkih prava',
'right-userrights-interwiki'  => 'Uređivanje korisničkih prava korisnika na drugim wikijima',
'right-siteadmin'             => 'Zaključavanje i otključavanje baze podataka',
'right-reset-passwords'       => 'Resetiranje lozinke drugih korisnika',
'right-override-export-depth' => 'Izvoz stranica uključujući povezane stranice do dubine od 5 linkova',
'right-sendemail'             => 'Slanje e-maila drugim korisnicima',
'right-revisionmove'          => 'Premještanje izmjena',
'right-disableaccount'        => 'Onemogućavanje računa',

# User rights log
'rightslog'      => 'Registar korisničkih prava',
'rightslogtext'  => 'Ovo je evidencija izmjene korisničkih prava.',
'rightslogentry' => 'promjena članstva u grupi za $1 sa $2 na $3',
'rightsnone'     => '(nema)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'čitanje ove stranice',
'action-edit'                 => 'uređujete ovu stranicu',
'action-createpage'           => 'stvaranje stranica',
'action-createtalk'           => 'stvaranje stranica za razgovor',
'action-createaccount'        => 'stvaranje ovog korisničkog računa',
'action-minoredit'            => 'označavanje ove izmjene kao manje',
'action-move'                 => 'premještanje ove stranice',
'action-move-subpages'        => 'premještanje ove stranice, i njenih podstranica',
'action-move-rootuserpages'   => 'premještanje osnovne stranice korisnika',
'action-movefile'             => 'premjesti ovu datoteku',
'action-upload'               => 'postavljate ovu datoteku',
'action-reupload'             => 'postavljanje nove verzije datoteke',
'action-reupload-shared'      => 'postavljanje ove datoteke iz zajedničke ostave',
'action-upload_by_url'        => 'postavljanje ove datoteke preko URL adrese',
'action-writeapi'             => "korištenje ''write API'' opcije",
'action-delete'               => 'brisanje ove stranice',
'action-deleterevision'       => 'brisanje ove izmjene',
'action-deletedhistory'       => 'gledanje obrisane historije ove stranice',
'action-browsearchive'        => 'pretraživanje obrisanih stranica',
'action-undelete'             => 'vraćanje ove stranice',
'action-suppressrevision'     => 'pregledavanje i vraćanje ove skrivene izmjene',
'action-suppressionlog'       => 'gledanje ove privatne evidencije',
'action-block'                => 'blokiranje uređivanja ovog korisnika',
'action-protect'              => 'promijeniti nivo zaštite ove stranice',
'action-import'               => 'uvoženje ove stranice s drugog wikija',
'action-importupload'         => 'uvoženje ove stranice postavljanjem datoteke',
'action-patrol'               => 'označavanje tuđih izmjena patroliranim',
'action-autopatrol'           => 'označavanje vlastitih izmjena kao patroliranih',
'action-unwatchedpages'       => 'pregled spiska nenadgledanih strana',
'action-trackback'            => 'slanje "trackbacka"',
'action-mergehistory'         => 'spajanje historije ove stranice',
'action-userrights'           => 'uređivanje svih korisničkih prava',
'action-userrights-interwiki' => 'uređivanje korisničkih prava na drugim wikijima',
'action-siteadmin'            => 'zaključavanje i otključavanje baze podataka',
'action-revisionmove'         => 'premještanje izmjena',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',
'recentchanges'                     => 'Nedavne izmjene',
'recentchanges-legend'              => 'Postavke za Nedavne promjene',
'recentchangestext'                 => 'Na ovoj stranici možete pratiti nedavne izmjene.',
'recentchanges-feed-description'    => 'Praćenje nedavnih izmjena na ovom wikiju u ovom feedu.',
'recentchanges-label-newpage'       => 'Ovom izmjenom je stvorena nova stranica',
'recentchanges-label-minor'         => 'Ovo je manja izmjena',
'recentchanges-label-bot'           => 'Ovu je izmjenu učinio bot',
'recentchanges-label-unpatrolled'   => 'Ova izmjena još nije patrolirana',
'rcnote'                            => "Ispod {{PLURAL:$1|je '''$1''' promjena|su '''$1''' zadnje promjene|su '''$1''' zadnjih promjena}} u {{PLURAL:$2|posljednjem '''$2''' danu|posljednja '''$2''' dana|posljednjih '''$2''' dana}}, od $4, $5.",
'rcnotefrom'                        => "Ispod {{PLURAL:$1|je '''$1''' izmjena|su '''$1''' zadnje izmjene|su '''$1''' zadnjih izmjena}} u {{PLURAL:$2|posljednjem '''$2''' danu|posljednja '''$2''' dana|posljednjih '''$2''' dana}}, od $4, $5.",
'rclistfrom'                        => 'Prikaži nove izmjene počevši od $1',
'rcshowhideminor'                   => '$1 male izmjene',
'rcshowhidebots'                    => '$1 botove',
'rcshowhideliu'                     => '$1 prijavljene korisnike',
'rcshowhideanons'                   => '$1 anonimne korisnike',
'rcshowhidepatr'                    => '$1 patrolirane izmjene',
'rcshowhidemine'                    => '$1 moje izmjene',
'rclinks'                           => 'Prikaži najskorijih $1 izmjena u posljednjih $2 dana<br />$3',
'diff'                              => 'razl',
'hist'                              => 'hist',
'hide'                              => 'Sakrij',
'show'                              => 'Prikaži',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|korisnik|korisnika}} koji pregledaju]',
'rc_categories'                     => 'Ograniči na kategorije (razdvojene sa "|")',
'rc_categories_any'                 => 'Sve',
'newsectionsummary'                 => '/* $1 */ nova sekcija',
'rc-enhanced-expand'                => 'Pokaži detalje (neophodan JavaScript)',
'rc-enhanced-hide'                  => 'Sakrij detalje',

# Recent changes linked
'recentchangeslinked'          => 'Srodne izmjene',
'recentchangeslinked-feed'     => 'Srodne izmjene',
'recentchangeslinked-toolbox'  => 'Srodne izmjene',
'recentchangeslinked-title'    => 'Srodne promjene sa "$1"',
'recentchangeslinked-noresult' => 'Nema izmjena na povezanim stranicama u zadanom periodu.',
'recentchangeslinked-summary'  => "Ova posebna stranica prikazuje promjene na povezanim stranicama.
Stranice koje su na vašem [[Special:Watchlist|spisku praćenja]] su '''podebljane'''.",
'recentchangeslinked-page'     => 'Naslov stranice:',
'recentchangeslinked-to'       => 'Pokaži promjene stranica koji su povezane sa datom stranicom',

# Upload
'upload'                      => 'Postavi datoteku',
'uploadbtn'                   => 'Postavi datoteku',
'reuploaddesc'                => 'Vratite se na upitnik za slanje',
'upload-tryagain'             => 'Pošaljite izmijenjeni opis datoteke',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Morate biti [[Special:UserLogin|prijavljeni]] da bi ste slali datoteke.',
'upload_directory_missing'    => 'Folder za postavljanje ($1) nedostaje i webserver ga ne može napraviti.',
'upload_directory_read_only'  => 'Folder za postavljanje ($1) na webserveru je postavljen samo za čitanje.',
'uploaderror'                 => 'Greška pri slanju',
'upload-recreate-warning'     => "'''Upozorenje: Datoteka s tim imenom je obrisana ili premještena.'''

Zapisnik brisanja i premještanja za ovu stranicu je dostupan ovdje na uvid:",
'uploadtext'                  => "Koristite formu ispod za postavljanje datoteka.
Da bi ste vidjeli ili pretražili ranije postavljene datoteke, pogledajte [[Special:FileList|spisak postavljenih datoteka]], ponovna postavljanja su također zapisana u [[Special:Log/upload|evidenciji postavljanja]], a brisanja u [[Special:Log/delete|evidenciji brisanja]].

Da bi ste prikazali datoteku na stranici, koristite link na jedan od slijedećih načina:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datoteka.jpg]]</nowiki></tt>''' da upotrijebite potpunu veziju datoteke
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datoteka.png|200px|thumb|lijevo|opis slike]]</nowiki></tt>''' da upotrijebite smanjeni prikaz širine 200 piksela unutar okvira, s lijevim poravnanjem i ''opisom slike''.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datoteka.ogg]]</nowiki></tt>''' za direkno povezivanje datoteke bez njenog prikazivanja",
'upload-permitted'            => 'Podržane vrste datoteka: $1.',
'upload-preferred'            => 'Preferirane vrste datoteka: $1.',
'upload-prohibited'           => 'Zabranjene vrste datoteka: $1.',
'uploadlog'                   => 'registar postavljanja',
'uploadlogpage'               => 'Registar postavljanja',
'uploadlogpagetext'           => 'Ispod je popis najnovijih postavljanja datoteka.
Vidi [[Special:NewFiles|galeriju novih datoteka]] za slikovitiji pregled.',
'filename'                    => 'Ime datoteke',
'filedesc'                    => 'Sažetak',
'fileuploadsummary'           => 'Sažetak:',
'filereuploadsummary'         => 'Izmjene datoteke:',
'filestatus'                  => 'Status autorskih prava:',
'filesource'                  => 'Izvor:',
'uploadedfiles'               => 'Postavljene datoteke',
'ignorewarning'               => 'Zanemari upozorenja i sačuvaj datoteku',
'ignorewarnings'              => 'Zanemari sva upozorenja',
'minlength1'                  => 'Ime datoteke mora imati barem jedno slovo.',
'illegalfilename'             => 'Ime datoteke "$1" sadrži simbol koji nije dozvoljen u imenu datoteke.
Molimo Vas da promijenite ime datoteke i pokušate da je ponovo postavite.',
'badfilename'                 => 'Ime datoteke je promijenjeno u "$1".',
'filetype-mime-mismatch'      => 'Proširenje datoteke ".$1" ne odgovara MIME tipu ($2).',
'filetype-badmime'            => 'Datoteke MIME vrste "$1" nije dopušteno postavljati.',
'filetype-bad-ie-mime'        => 'Ne može se postaviti ova datoteka jer je Internet Explorer prepoznaje kao "$1", što je nedozvoljena i potencijalno opasna vrsta datoteke.',
'filetype-unwanted-type'      => "'''\".\$1\"''' je nepoželjna vrsta datoteke.
{{PLURAL:\$3|Preporučena vrsta|Preporučene vrste}} datoteke su \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|nije dopušten tip datoteke|nisu dopušteni tipovi datoteka}}.
{{PLURAL:$3|Dopuštena vrsta datoteke je|Dopuštene vrste datoteka su}} $2.',
'filetype-missing'            => 'Datoteka nema ekstenziju (poput ".jpg").',
'empty-file'                  => 'Datoteka koju ste poslali je bila prazna.',
'file-too-large'              => 'Datoteka koju ste poslali je bila prevelika.',
'filename-tooshort'           => 'Ime datoteke je prekratko.',
'filetype-banned'             => 'Ova vrsta datoteke je zabranjena.',
'verification-error'          => 'Ova datoteka nije prošla provjeru.',
'hookaborted'                 => 'Izmjena koju ste pokušali napraviti prekinuta je od strane ekstenzije.',
'illegal-filename'            => 'Ime datoteke nije dopušteno.',
'overwrite'                   => 'Presnimavanje preko postojeće datoteke nije dozvoljeno.',
'unknown-error'               => 'Desila se nepoznata greška.',
'tmp-create-error'            => 'Nije moguće napraviti privremenu datoteku.',
'tmp-write-error'             => 'Greška pri pisanju privremene datoteke.',
'large-file'                  => 'Preporučeno je da datoteke nisu veće od $1;
Ova datoteka je velika $2.',
'largefileserver'             => 'Ova datoteka je veća nego što server dopušta.',
'emptyfile'                   => 'Datoteka koju ste poslali je prazna. 
Ovo je moguće zbog greške u imenu datoteke. 
Molimo Vas da provjerite da li stvarno želite da pošaljete ovu datoteku.',
'fileexists'                  => "Datoteka sa ovim imenom već postoji.
Molimo Vas da provjerite '''<tt>[[:$1]]</tt>''' ako niste sigurni da li želite da je promjenite.
[[$1|thumb]]",
'filepageexists'              => "Opis stranice za ovu datoteku je već napravljen ovdje '''<tt>[[:$1]]</tt>''', ali datoteka sa ovim nazivom trenutno ne postoji.
Sažetak koji ste naveli neće se pojaviti na stranici opisa.
Da bi se Vaš opis ovdje našao, potrebno je da ga ručno uredite.
[[$1|thumb]]",
'fileexists-extension'        => "Datoteka sa sličnim nazivom postoji: [[$2|thumb]]
* Naziv datoteke koja se postavlja: '''<tt>[[:$1]]</tt>'''
* Naziv postojeće datoteke: '''<tt>[[:$2]]</tt>'''
Molimo Vas da izaberete drugačiji naziv.",
'fileexists-thumbnail-yes'    => "Izgleda da je datoteka slika smanjene veličine ''(\"thumbnail\")''. [[\$1|thumb]]
Molimo provjerite datoteku '''<tt>[[:\$1]]</tt>'''.
Ako je provjerena datoteka ista slika originalne veličine, nije potrebno postavljati dodatnu sliku.",
'file-thumbnail-no'           => "Naziv datoteke počinje sa '''<tt>\$1</tt>'''.
Izgleda da se radi o smanjenoj slici ''(\"thumbnail\")''.
Ako imate ovu sliku u punoj rezoluciji, postavite nju; ili promijenite naslov ove datoteke.",
'fileexists-forbidden'        => 'Datoteka sa ovim imenom već postoji i ne može biti presnimljena.
Ako i dalje želite da postavite ovu datoteku, molimo Vas da se vratite i pošaljete ovu datoteku pod novim imenom. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Datoteka sa ovim imenom već postoji u zajedničkoj ostavi; molimo Vas da se vratite i pošaljete ovu datoteku pod novim imenom. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ova datoteka je dvojnik {{PLURAL:$1|slijedećoj datoteci|slijedećim datotekama}}:',
'file-deleted-duplicate'      => 'Datoteka koje je identična ovoj datoteci ([[:$1]]) je ranije bila obrisana. Trebate provjeriti historiju brisanja te datoteke prije nego što nastavite sa njenim ponovnim postavljanjem.',
'uploadwarning'               => 'Upozorenje pri slanju',
'uploadwarning-text'          => 'Molimo izmijeniti opis datoteke ispod i pokušajte kasnije.',
'savefile'                    => 'Snimi datoteku',
'uploadedimage'               => 'postavljeno "[[$1]]"',
'overwroteimage'              => 'postavljena nova verzija datoteke "[[$1]]"',
'uploaddisabled'              => 'Slanje fajlova je isključeno',
'copyuploaddisabled'          => 'Postavljanje putem URL nije omogućeno.',
'uploadfromurl-queued'        => 'Vaše postavljanje je na čekanju.',
'uploaddisabledtext'          => 'Postavljanje datoteka je onemogućeno.',
'php-uploaddisabledtext'      => 'Postavljanje datoteka preko PHP je onemogućeno. 
Molimo provjerite postavku za postavljanje datoteka.',
'uploadscripted'              => 'Ova datoteka sadrži HTML ili skriptni kod koji može izazvati grešku kod internet preglednika.',
'uploadvirus'                 => 'Fajl sadrži virus!  Detalji:  $1',
'upload-source'               => 'Izvorna datoteka',
'sourcefilename'              => 'Ime izvorišne datoteke:',
'sourceurl'                   => 'URL izvora:',
'destfilename'                => 'Ime odredišne datoteke:',
'upload-maxfilesize'          => 'Najveća veličina datoteke: $1',
'upload-description'          => 'Opis datoteke',
'upload-options'              => 'Opcije postavljanja',
'watchthisupload'             => 'Prati ovu datoteku',
'filewasdeleted'              => 'Datoteka s ovim nazivom je ranije postavljana i nakon toga obrisana.
Prije nego što nastavite da je ponovno postavite trebate provjeriti $1.',
'upload-wasdeleted'           => "''Upozorenje: Postavljate datoteku koja je ranije obrisana.'''

Potrebno je da razmotrite da li je u redu nastaviti sa postavljanjem ove datoteke.
Registar brisanja za ovu datoteku je prikazan ovdje kao referenca:",
'filename-bad-prefix'         => "Naziv datoteke koju postavljate počinje sa '''\"\$1\"''', što je naziv koji obično automatski dodjeljuju digitalni fotoaparati i kamere.
Molimo Vas da odaberete naziv datoteke koji opisuje njen sadržaj.",
'upload-success-subj'         => 'Uspješno slanje',
'upload-success-msg'          => 'Vaša datoteka iz [$2] je uspješno postavljena. Dostupna je ovdje: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problem pri postavljanju',
'upload-failure-msg'          => 'Nastao je problem s Vašim postavljanjem iz [$2]:

$1',
'upload-warning-subj'         => 'Upozorenje pri slanju',
'upload-warning-msg'          => 'Nastao je problem sa vašim postavljanjem sa [$2]. Morate se vratiti na [[Special:Upload/stash/$1|formular za postavljanje]] kako biste riješili ovaj problem.',

'upload-proto-error'        => 'Pogrešan protokol',
'upload-proto-error-text'   => 'Postavljanje sa vanjske lokacije zahtjeva URL-ove koji počinju sa <code>http://</code> ili <code>ftp://</code>.',
'upload-file-error'         => 'Interna pogreška',
'upload-file-error-text'    => 'Desila se interna greška pri pokušaju kreiranja privremene datoteke na serveru.
Molimo kontaktirajte [[Special:ListUsers/sysop|administratora]].',
'upload-misc-error'         => 'Nepoznata greška pri postavljanju',
'upload-misc-error-text'    => 'Desila se nepoznata greška pri postavljanju.
Molimo Vas provjerite da li je URL tačan i dostupan pa pokušajte ponovo.
Ako se problem ne riješi, kontaktirajte [[Special:ListUsers/sysop|administratora]].',
'upload-too-many-redirects' => 'URL sadrži previše preusmjerenja',
'upload-unknown-size'       => 'Nepoznata veličina',
'upload-http-error'         => 'Desila se HTTP greška: $1',

# Special:UploadStash
'uploadstash'          => 'Snimi niz datoteka',
'uploadstash-summary'  => 'Ova stranica daje pristup datotekama koje su postavljene (ili su u postupku postavljanja) ali još nisu objavljene na wiki. Ove datoteke nisu vidljive nikom osim korisniku koji ih je postavio.',
'uploadstash-clear'    => 'Očisti niz datoteka',
'uploadstash-nofiles'  => 'Nemate neobjavljenih datoteka',
'uploadstash-badtoken' => 'Izvršavanje ove akcije je bilo neuspješno, možda zato što su vaša uređivačka odobrenja istekla. Pokušajte ponovo.',
'uploadstash-errclear' => 'Brisanje neobjavljenih datoteka nije uspjelo.',
'uploadstash-refresh'  => 'Osvježi spisak datoteka',

# img_auth script messages
'img-auth-accessdenied' => 'Pristup onemogućen',
'img-auth-nopathinfo'   => 'Nedostaje PATH_INFO.
Vaš server nije postavljen da daje ovu informaciju.
On je zasnovan na CGI i ne može podržavati img_auth.
Pogledajte http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Zahtjevana putanja nije u direktorijumu podešenom za postavljanje.',
'img-auth-badtitle'     => 'Ne mogu napraviti valjani naslov iz "$1".',
'img-auth-nologinnWL'   => 'Niste prijavljeni i "$1" nije na spisku dozvoljenih.',
'img-auth-nofile'       => 'Datoteka "$1" ne postoji.',
'img-auth-isdir'        => 'Pokušavate pristupiti direktorijumu "$1".
Dozvoljen je samo pristup datotekama.',
'img-auth-streaming'    => 'Tok "$1".',
'img-auth-public'       => 'Funkcija img_auth.php služi za izlaz datoteka sa privatnih wikija.
Ova wiki je postavljena kao javna wiki.
Za optimalnu sigurnost, img_auth.php je onemogućena.',
'img-auth-noread'       => 'Korisnik nema pristup za čitanje "$1".',

# HTTP errors
'http-invalid-url'      => 'Nevaljan URL: $1',
'http-invalid-scheme'   => 'URLovi za koje šema "$1" nije podržana',
'http-request-error'    => 'HTTP zahtjev nije uspio zbog nepoznate pogreške.',
'http-read-error'       => 'Greška pri čitanju HTTP.',
'http-timed-out'        => 'Istekao HTTP zahtjev.',
'http-curl-error'       => 'Greška pri otvaranju URLa: $1',
'http-host-unreachable' => 'Ovaj URL nije bilo moguće otvoriti',
'http-bad-status'       => 'Nastao je problem tokom HTTP zahtjeva: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ovaj URL nije bilo moguće otvoriti',
'upload-curl-error6-text'  => 'URL koji je naveden nije dostupan.
Molimo ponovno provjerite da li je URL ispravan i da li stranica radi.',
'upload-curl-error28'      => 'Vrijeme postavljanja isteklo',
'upload-curl-error28-text' => 'Stranici je potrebno previše vremena za odgovor.
Molimo provjerite da li je stranica postavljena, malo pričekajte i pokušajte ponovno.
Možda možete pokušati kada bude manje opterećenje.',

'license'            => 'Licenca:',
'license-header'     => 'Licenciranje',
'nolicense'          => 'Ništa nije odabrano',
'license-nopreview'  => '(Pregled nije dostupan)',
'upload_source_url'  => '(valjani, javno dostupni URL)',
'upload_source_file' => '(datoteka na Vašem kompjuteru)',

# Special:ListFiles
'listfiles-summary'     => 'Ova specijalna stranica prikazuje sve postavljene datoteke.
Uobičajeno je da posljednja postavljena datoteka bude prikazana na vrhu spiska.
Klikom na zaglavlje kolone možete promjeniti način sortiranja.',
'listfiles_search_for'  => 'Traži ime medija:',
'imgfile'               => 'datoteka',
'listfiles'             => 'Spisak slika',
'listfiles_thumb'       => 'Smanjeni pregled',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Naziv',
'listfiles_user'        => 'Korisnik',
'listfiles_size'        => 'Veličina',
'listfiles_description' => 'Opis',
'listfiles_count'       => 'Verzije',

# File description page
'file-anchor-link'          => 'Datoteka',
'filehist'                  => 'Historija datoteke',
'filehist-help'             => 'Kliknite na datum/vrijeme da vidite kako je tada izgledala datoteka/fajl.',
'filehist-deleteall'        => 'izbriši sve',
'filehist-deleteone'        => 'obriši',
'filehist-revert'           => 'vrati',
'filehist-current'          => 'trenutno',
'filehist-datetime'         => 'Datum/Vrijeme',
'filehist-thumb'            => 'Smanjeni pregled',
'filehist-thumbtext'        => 'Smanjeni pregled verzije na dan $1',
'filehist-nothumb'          => 'Bez smanjenog pregleda (thumbnail)',
'filehist-user'             => 'Korisnik',
'filehist-dimensions'       => 'Dimenzije',
'filehist-filesize'         => 'Veličina datoteke',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Datoteka nedostaje',
'imagelinks'                => 'Linkovi datoteke',
'linkstoimage'              => '{{PLURAL:$1|Sljedeća stranica koristi|Sljedećih $1 stranica koriste}} ovu sliku:',
'linkstoimage-more'         => 'Više od $1 {{PLURAL:$1|stranice povezuje|stranica povezuje}} na ovu datoteku.
Slijedeći popis prikazuje {{PLURAL:$1|stranice koje|prvih $1 stranica koje}} vode na ovu datoteku.
[[Special:WhatLinksHere/$2|Ovdje se nalazi]] potpuni popis.',
'nolinkstoimage'            => 'Nema stranica koje koriste ovu datoteku.',
'morelinkstoimage'          => 'Vidi [[Special:WhatLinksHere/$1|ostale linkove]] prema ovoj datoteci.',
'redirectstofile'           => '{{PLURAL:$1|Slijedeća datoteka|Slijedeće $1 datoteke|Slijedećih $1 datoteka}} preusmjerava prema ovoj datoteci:',
'duplicatesoffile'          => '{{PLURAL:$1|Slijedeća datoteka je dvojnik|Slijedeće $1 datoteke su dvojnici}} ove datoteke ([[Special:FileDuplicateSearch/$2|detaljnije]]):',
'sharedupload'              => 'Ova datoteka/fajl je sa $1 i mogu je koristiti ostali projekti.',
'sharedupload-desc-there'   => 'Ova datoteka je sa $1 i može se koristiti i na drugim projektima.
Molimo pogledajte [$2 stranicu opisa datoteke] za ostale informacije.',
'sharedupload-desc-here'    => 'Ova datoteka je sa $1 i može se koristiti i na drugim projektima.
Opis sa njene [$2 stranice opisa datoteke] je prikazan ispod.',
'filepage-nofile'           => 'Ne postoji datoteka s ovim nazivom.',
'filepage-nofile-link'      => 'Ne postoji datoteka s ovim imenom, ali je možete [$1 postaviti].',
'uploadnewversion-linktext' => 'Postavite novu verziju ove datoteke/fajla',
'shared-repo-from'          => 'iz $1',
'shared-repo'               => 'zajednička ostava',

# File reversion
'filerevert'                => 'Vrati $1',
'filerevert-legend'         => 'Vrati datoteku/fajl',
'filerevert-intro'          => "Vraćate datoteku '''[[Media:$1|$1]]''' na [$4 verziju od $3, $2].",
'filerevert-comment'        => 'Razlog:',
'filerevert-defaultcomment' => 'Vraćeno na verziju od $2, $1',
'filerevert-submit'         => 'Vrati',
'filerevert-success'        => "'''Datoteka [[Media:$1|$1]]''' je vraćena na [$4 verziju od $3, $2].",
'filerevert-badversion'     => 'Ne postoji ranija lokalna verzija ove datoteke sa navedenim vremenskim podacima.',

# File deletion
'filedelete'                  => 'Obriši $1',
'filedelete-legend'           => 'Obriši datoteku',
'filedelete-intro'            => "Brišete datoteku '''[[Media:$1|$1]]''' zajedno sa svom njenom historijom.",
'filedelete-intro-old'        => "Brišete verziju datoteke '''[[Media:$1|$1]]''' od [$4 $3, $2].",
'filedelete-comment'          => 'Razlog:',
'filedelete-submit'           => 'Obriši',
'filedelete-success'          => "'''$1''' je obrisano.",
'filedelete-success-old'      => "Verzija datoteke '''[[Media:$1|$1]]''' od $3, $2 je obrisana.",
'filedelete-nofile'           => "'''$1''' ne postoji.",
'filedelete-nofile-old'       => "Ne postoji arhivirana verzija '''$1''' sa navedenim atributima.",
'filedelete-otherreason'      => 'Ostali/dodatni razlog/zi:',
'filedelete-reason-otherlist' => 'Ostali razlog/zi',
'filedelete-reason-dropdown'  => '*Uobičajeni razlozi brisanja
** Kršenje autorskih prava
** Datoteka dvojnik',
'filedelete-edit-reasonlist'  => 'Uredi razloge brisanja',
'filedelete-maintenance'      => 'Brisanje i povratak datoteka je privremeno onemogućen tokom održavanja.',

# MIME search
'mimesearch'         => 'MIME pretraga',
'mimesearch-summary' => 'Ova stranica omogućava filtriranje datoteka prema njihovoj MIME vrsti.
Ulazni podaci: vrstasadržaja/podvrsta, npr. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME tip:',
'download'           => 'učitaj',

# Unwatched pages
'unwatchedpages' => 'Nepraćene stranice',

# List redirects
'listredirects' => 'Spisak preusmjerenja',

# Unused templates
'unusedtemplates'     => 'Nekorišteni šabloni',
'unusedtemplatestext' => 'Ova stranica prikazuje sve stranice u imenskom prostoru {{ns:template}} koji se ne koriste.
Prije brisanja provjerite da li druge stranice vode na te šablone.',
'unusedtemplateswlh'  => 'ostali linkovi',

# Random page
'randompage'         => 'Slučajna stranica',
'randompage-nopages' => 'Nema stranica u {{PLURAL:$2|slijedećem imenskom prostoru|slijedećim imenskim prostorima}}: "$1".',

# Random redirect
'randomredirect'         => 'Slučajno preusmjerenje',
'randomredirect-nopages' => 'Nema preusmjerenja u imenskom prostoru "$1".',

# Statistics
'statistics'                   => 'Statistike',
'statistics-header-pages'      => 'Statistike stranice',
'statistics-header-edits'      => 'Statistike izmjena',
'statistics-header-views'      => 'Statistike pregleda',
'statistics-header-users'      => 'Statistike korisnika',
'statistics-header-hooks'      => 'Ostale statistike',
'statistics-articles'          => 'Stranice sadržaja',
'statistics-pages'             => 'Stranice',
'statistics-pages-desc'        => 'Sve stranice na wikiju, uključujući stranice za razgovor, preusmjerenja itd.',
'statistics-files'             => 'Broj postavljenih datoteka',
'statistics-edits'             => 'Broj izmjena od kako je instalirana {{SITENAME}}',
'statistics-edits-average'     => 'Prosječno izmjena po stranici',
'statistics-views-total'       => 'Ukupno pregleda',
'statistics-views-total-desc'  => 'Pregledi nepostojećih stranica i posebnih stranica nisu uključeni',
'statistics-views-peredit'     => 'Pregleda po izmjeni',
'statistics-users'             => 'Registrovani [[Special:ListUsers|korisnici]]',
'statistics-users-active'      => 'Aktivni korisnici',
'statistics-users-active-desc' => 'Korisnici koju su izvršili akciju u toku {{PLURAL:$1|zadnjeg dana|zadnja $1 dana|zadnjih $1 dana}}',
'statistics-mostpopular'       => 'Najviše pregledane stranice',

'disambiguations'      => 'Stranice za razvrstavanje',
'disambiguationspage'  => 'Template:Višeznačna odrednica',
'disambiguations-text' => "Slijedeće stranice su povezane sa '''stranicom za razvrstavanje'''.
Po pravilu, one se trebaju povezati sa konkretnim člankom.<br />
Stranica se smatra stranicom za razvrstavanje, ukoliko koristi šablon koji je povezan sa spiskom [[MediaWiki:Disambiguationspage|stranica za razvrstavanje]]",

'doubleredirects'                   => 'Dvostruka preusmjerenja',
'doubleredirectstext'               => 'Ova stranica prikazuje stranice koje preusmjeravaju na druga preusmjerenja.
Svaki red sadrži veze na prvo i drugo preusmjerenje, kao i na prvu liniju teksta drugog preusmjerenja, što obično daje "pravi" ciljni članak, na koji bi prvo preusmjerenje i trebalo da pokazuje.
<del>Precrtane</del> stavke su riješene.',
'double-redirect-fixed-move'        => '[[$1]] je premješten, sada je preusmjerenje na [[$2]]',
'double-redirect-fixed-maintenance' => 'Popravak dvostrukih datoteka od [[$1]] do [[$2]].',
'double-redirect-fixer'             => 'Popravljač preusmjerenja',

'brokenredirects'        => 'Pokvarena preusmjerenja',
'brokenredirectstext'    => 'Slijedeća preusmjerenja vode na nepostojeće stranice:',
'brokenredirects-edit'   => 'uredi',
'brokenredirects-delete' => 'obriši',

'withoutinterwiki'         => 'Stranice bez linkova na drugim jezicima',
'withoutinterwiki-summary' => 'Sljedeće stranice nisu povezane sa verzijama na drugim jezicima.',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Prikaži',

'fewestrevisions' => 'Stranice sa najmanje izmjena',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajtova}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategorije}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linka|linkova}}',
'nmembers'                => '$1 {{PLURAL:$1|član|članova}}',
'nrevisions'              => '$1 {{PLURAL:$1|revizija|revizije|revizija}}',
'nviews'                  => '$1 {{PLURAL:$1|pregled|pregleda}}',
'nimagelinks'             => 'Koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'ntransclusions'          => 'koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'specialpage-empty'       => 'Ne postoje rezultati za ovaj izvještaj.',
'lonelypages'             => 'Stranice - siročići',
'lonelypagestext'         => 'Slijedeće stranice nemaju linkove na ostalim stranicama na ovoj {{SITENAME}}.',
'uncategorizedpages'      => 'Nekategorisane stranice',
'uncategorizedcategories' => 'Nekategorisane kategorije',
'uncategorizedimages'     => 'Slike bez kategorije',
'uncategorizedtemplates'  => 'Šabloni bez kategorije',
'unusedcategories'        => 'Neiskorištene kategorije',
'unusedimages'            => 'Neupotrebljene datoteke',
'popularpages'            => 'Popularne stranice',
'wantedcategories'        => 'Tražene kategorije',
'wantedpages'             => 'Tražene stranice',
'wantedpages-badtitle'    => 'Nevaljan naslov u setu rezultata: $1',
'wantedfiles'             => 'Tražene datoteke',
'wantedtemplates'         => 'Potrebni šabloni',
'mostlinked'              => 'Stranice sa najviše linkova',
'mostlinkedcategories'    => 'Kategorije sa najviše linkova',
'mostlinkedtemplates'     => 'Šabloni sa najviše linkova',
'mostcategories'          => 'Stranice sa najviše kategorija',
'mostimages'              => 'Datoteke sa najviše linkova',
'mostrevisions'           => 'Stranice sa najviše izmjena',
'prefixindex'             => 'Sve stranice sa prefiksom',
'shortpages'              => 'Kratke stranice',
'longpages'               => 'Dugačke stranice',
'deadendpages'            => 'Stranice bez internih linkova',
'deadendpagestext'        => 'Slijedeće stranice nisu povezane s drugim stranicama na {{SITENAME}}.',
'protectedpages'          => 'Zaštićene stranice',
'protectedpages-indef'    => 'Samo neograničena zaštićenja',
'protectedpages-cascade'  => 'Samo prenosive zaštite',
'protectedpagestext'      => 'Slijedeće stranice su zaštićene od izmjena i premještanja',
'protectedpagesempty'     => 'Trenutno nijedna stranica nije zaštićena s ovim parametrima.',
'protectedtitles'         => 'Zaštićeni naslovi',
'protectedtitlestext'     => 'Članci sa slijedećim naslovima su zaštićeni od kreiranja.',
'protectedtitlesempty'    => 'Nema naslova zaštićenih članaka sa ovim parametrima.',
'listusers'               => 'Spisak korisnika',
'listusers-editsonly'     => 'Pokaži samo korisnike koji su uređivali',
'listusers-creationsort'  => 'Sortiraj po datumu pravljenja',
'usereditcount'           => '$1 {{PLURAL:$1|izmjena|izmjene}}',
'usercreated'             => 'Napravljeno dana $1 u $2',
'newpages'                => 'Nove stranice',
'newpages-username'       => 'Korisničko ime:',
'ancientpages'            => 'Najstarije stranice',
'move'                    => 'Premjesti',
'movethispage'            => 'Premjesti ovu stranicu',
'unusedimagestext'        => 'Slijedeće datoteke postoje ali nisu uključene ni u jednu stranicu.
Molimo obratite pažnju da druge web stranice mogu biti povezane s datotekom putem direktnog URLa, tako da i pored toga mogu biti prikazane ovdje pored aktivne upotrebe.',
'unusedcategoriestext'    => 'Slijedeće stranice kategorija postoje iako ih ni jedan drugi članak ili kategorija ne koriste.',
'notargettitle'           => 'Nema cilja',
'notargettext'            => 'Niste naveli ciljnu stranicu ili korisnika
na kome bi se izvela ova funkcija.',
'nopagetitle'             => 'Ne postoji takva stranica',
'nopagetext'              => 'Ciljna stranica koju ste naveli ne postoji.',
'pager-newer-n'           => '{{PLURAL:$1|novija 1|novije $1}}',
'pager-older-n'           => '{{PLURAL:$1|starija 1|starije $1}}',
'suppress'                => 'Nazdor',
'querypage-disabled'      => 'Ova posebna stranica je onemogućena jer smanjuje performanse.',

# Book sources
'booksources'               => 'Književni izvori',
'booksources-search-legend' => 'Traži književne izvore',
'booksources-go'            => 'Idi',
'booksources-text'          => 'Ispod se nalazi spisak vanjskih linkova na ostale stranice koje prodaju nove ili korištene knjige kao i stranice koje mogu da imaju važnije podatke o knjigama koje tražite:',
'booksources-invalid-isbn'  => 'Navedeni ISBN broj nije validan; molimo da provjerite da li je došlo do greške pri kopiranju iz prvobitnog izvora.',

# Special:Log
'specialloguserlabel'  => 'Korisnik:',
'speciallogtitlelabel' => 'Naslov:',
'log'                  => 'Registri',
'all-logs-page'        => 'Svi javni registri',
'alllogstext'          => 'Zajednički prikaz svih dostupnih evidencija sa {{SITENAME}}.
Možete specificirati prikaz izabiranjem specifičnog spiska, korisničkog imena ili promjenjenog članka (razlikovati velika slova).',
'logempty'             => 'Ne postoji takav zapis.',
'log-title-wildcard'   => 'Traži naslove koji počinju s ovim tekstom',

# Special:AllPages
'allpages'          => 'Sve stranice',
'alphaindexline'    => '$1 do $2',
'nextpage'          => 'Sljedeća strana ($1)',
'prevpage'          => 'Prethodna stranica ($1)',
'allpagesfrom'      => 'Prikaži stranice koje počinju od:',
'allpagesto'        => 'Pokaži stranice koje završavaju na:',
'allarticles'       => 'Sve stranice',
'allinnamespace'    => 'Sve stranice (imenski prostor $1)',
'allnotinnamespace' => 'Sve stranice (van imenskog prostora $1)',
'allpagesprev'      => 'Prethodna',
'allpagesnext'      => 'Slijedeće',
'allpagessubmit'    => 'Idi',
'allpagesprefix'    => 'Prikaži stranice sa prefiksom:',
'allpagesbadtitle'  => 'Dati naziv stranice je nepravilan ili ima međujezički ili interwiki prefiks.
Možda sadrži jedan ili više znakova koji se ne mogu koristiti u naslovima.',
'allpages-bad-ns'   => '{{SITENAME}} nema imenski prostor "$1".',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => '{{PLURAL:$1|Slijedeća kategorija sadrži|Slijedeće kategorije sadrže}} stranice ili multimedijalne datoteke.
[[Special:UnusedCategories|Nekorištene kategorije]] nisu prikazane ovdje.
Vidi također [[Special:WantedCategories|zatražene kategorije]].',
'categoriesfrom'                => 'Prikaži kategorije počev od:',
'special-categories-sort-count' => 'sortiranje po broju',
'special-categories-sort-abc'   => 'sortiraj po abecedi',

# Special:DeletedContributions
'deletedcontributions'             => 'Obrisani doprinosi korisnika',
'deletedcontributions-title'       => 'Obrisani doprinosi korisnika',
'sp-deletedcontributions-contribs' => 'doprinosi',

# Special:LinkSearch
'linksearch'       => 'Vanjski/spoljašni linkovi',
'linksearch-pat'   => 'Šema pretrage:',
'linksearch-ns'    => 'Imenski prostor:',
'linksearch-ok'    => 'Traži',
'linksearch-text'  => 'Općeniti izrazi poput "*.wikipedia.org" se mogu koristiti.<br />
Podržani protokoli: <tt>$1</tt>',
'linksearch-line'  => '$1 je povezan od $2',
'linksearch-error' => 'Džokeri se mogu pojavljivati samo na početku naziva servera.',

# Special:ListUsers
'listusersfrom'      => 'Prikaži korisnike počev od:',
'listusers-submit'   => 'Pokaži',
'listusers-noresult' => 'Nije pronađen korisnik.',
'listusers-blocked'  => '(blokiran)',

# Special:ActiveUsers
'activeusers'            => 'Spisak aktivnih korisnika',
'activeusers-intro'      => 'Ovo je spisak korisnika koji su napravili neku aktivnost u {{PLURAL:$1|zadnji $1 dan|zadnja $1 dana|zadnjih $1 dana}}.',
'activeusers-count'      => '{{PLURAL:$1|nedavna $1 izmjena|nedavne $1 izmjene|nedavnih $1 izmjena}} u {{PLURAL:$3|posljednji $3 dan|posljednja $3 dana|posljednjih $3 dana}}',
'activeusers-from'       => 'Prikaži korisnike koji počinju sa:',
'activeusers-hidebots'   => 'Sakrij botove',
'activeusers-hidesysops' => 'Sakrij administratore',
'activeusers-noresult'   => 'Nije pronađen korisnik.',

# Special:Log/newusers
'newuserlogpage'              => 'Registar novih korisnika',
'newuserlogpagetext'          => 'Ovo je evidencija registracije novih korisnika.',
'newuserlog-byemail'          => 'lozinka je poslana putem e-maila',
'newuserlog-create-entry'     => 'Novi korisnički račun',
'newuserlog-create2-entry'    => 'napravljen novi račun za $1',
'newuserlog-autocreate-entry' => 'Račun napravljen automatski',

# Special:ListGroupRights
'listgrouprights'                      => 'Prava korisničkih grupa',
'listgrouprights-summary'              => 'Slijedi spisak korisničkih grupa na ovoj wiki, s njihovim pravima pristupa.
O svakoj od njih postoje i [[{{MediaWiki:Listgrouprights-helppage}}|dodatne informacije]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dodano pravo</span>
* <span class="listgrouprights-revoked">Uklonjeno pravo</span>',
'listgrouprights-group'                => 'Grupa',
'listgrouprights-rights'               => 'Prava',
'listgrouprights-helppage'             => 'Help:Grupna prava',
'listgrouprights-members'              => '(lista članova)',
'listgrouprights-addgroup'             => 'Mogu dodati {{PLURAL:$2|grupu|grupe}}: $1',
'listgrouprights-removegroup'          => 'Mogu ukloniti {{PLURAL:$2|grupu|grupe}}: $1',
'listgrouprights-addgroup-all'         => 'Može dodavati sve grupe',
'listgrouprights-removegroup-all'      => 'Može ukloniti sve grupe',
'listgrouprights-addgroup-self'        => 'Može dodati {{PLURAL:$2|grupu|grupe|grupa}} na svoj račun: $1',
'listgrouprights-removegroup-self'     => 'Može ukloniti {{PLURAL:$2|grupu|grupe|grupa}} sa svog računa: $1',
'listgrouprights-addgroup-self-all'    => 'Može dodati sve grupe na svoj račun',
'listgrouprights-removegroup-self-all' => 'Može ukloniti sve grupe sa svog računa',

# E-mail user
'mailnologin'          => 'Nema adrese za slanje',
'mailnologintext'      => 'Morate biti [[Special:UserLogin|prijavljeni]] i imati ispravnu adresu e-pošte u vašim [[Special:Preferences|podešavanjima]] da biste slali e-poštu drugim korisnicima.',
'emailuser'            => 'Pošalji E-mail ovom korisniku',
'emailpage'            => 'Pošalji e-mail ovom korisniku',
'emailpagetext'        => 'Možete korisiti formu ispod za slanje e-mail poruka ovom korisniku.
E-mail adresa koju ste unijeli u [[Special:Preferences|Vašim korisničkim postavkama]] će biti prikazana kao adresa pošiljaoca, tako da će primaoc poruke moći da Vam odgovori.',
'usermailererror'      => 'Objekat maila je vratio grešku:',
'defemailsubject'      => '{{SITENAME}} e-pošta',
'usermaildisabled'     => 'Korisnički e-mail onemogućen',
'usermaildisabledtext' => 'Ne možete poslati e-mail drugim korisnicima na ovoj wiki',
'noemailtitle'         => 'Nema adrese e-pošte',
'noemailtext'          => 'Ovaj korisnik nije naveo ispravnu adresu e-pošte.',
'nowikiemailtitle'     => 'E-mail nije dozvoljen',
'nowikiemailtext'      => 'Ovaj korisnik je odabrao da ne prima e-mail poštu od drugih korisnika.',
'email-legend'         => 'Slanje e-maila drugom {{SITENAME}} korisniku',
'emailfrom'            => 'Od:',
'emailto'              => 'Za:',
'emailsubject'         => 'Tema:',
'emailmessage'         => 'Poruka:',
'emailsend'            => 'Pošalji',
'emailccme'            => 'Pošalji mi kopiju moje poruke.',
'emailccsubject'       => 'Kopiranje Vaše poruke za $1: $2',
'emailsent'            => 'E-mail poruka poslata',
'emailsenttext'        => 'Vaša poruka je poslata e-poštom.',
'emailuserfooter'      => 'Ovaj e-mail je poslao $1 korisniku $2 putem funkcije "Pošalji e-mail korisniku" sa {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Ostavljanje sistemske poruke.',
'usermessage-editor'  => 'Sistem za poruke',

# Watchlist
'watchlist'            => 'Moj spisak praćenja',
'mywatchlist'          => 'Moj spisak praćenja',
'watchlistfor2'        => 'Za $1 $2',
'nowatchlist'          => 'Nemate ništa na svom spisku praćenih članaka.',
'watchlistanontext'    => 'Molimo da $1 da možete vidjeti ili urediti stavke na Vašem spisku praćenja.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Morate biti [[Special:UserLogin|prijavljeni]] da bi ste mijenjali spisak praćenih članaka.',
'addedwatch'           => 'Dodano na listu praćenih stranica',
'addedwatchtext'       => "Stranica \"[[:\$1]]\" je dodana [[Special:Watchlist|vašoj listi praćenih stranica]].
Buduće promjene ove stranice i njoj pridružene stranice za razgovor će biti navedene ovdje, te će stranica izgledati '''podebljana''' u [[Special:RecentChanges|listi nedavnih]] izmjena kako bi se lakše uočila.",
'removedwatch'         => 'Uklonjeno s liste praćenja',
'removedwatchtext'     => 'Stranica "[[:$1]]" je uklonjena s [[Special:Watchlist|vaše liste praćenja]].',
'watch'                => 'Prati',
'watchthispage'        => 'Prati ovu stranicu',
'unwatch'              => 'Prekini praćenje',
'unwatchthispage'      => 'Ukinite praćenje',
'notanarticle'         => 'Nije članak',
'notvisiblerev'        => 'Posljednja izmjena drugog korisnika je bila izbrisana',
'watchnochange'        => 'Ništa što pratite nije promjenjeno u prikazanom vremenu.',
'watchlist-details'    => '{{PLURAL:$1|$1 stranica praćena|$1 stranice praćene|$1 stranica praćeno}} ne računajući stranice za razgovor.',
'wlheader-enotif'      => '* Obavještavanje e-poštom je omogućeno.',
'wlheader-showupdated' => "* Stranice koje su izmijenjene od kad ste ih posljednji put posjetili su prikazane '''podebljanim slovima'''",
'watchmethod-recent'   => 'provjerava se da li ima praćenih stranica u nedavnim izmjenama',
'watchmethod-list'     => 'provjerava se da li ima nedavnih izmjena u praćenim stranicama',
'watchlistcontains'    => 'Vaš spisak praćenih članaka sadrži $1 {{PLURAL:$1|stranicu|stranica}}.',
'iteminvalidname'      => "Problem sa '$1', neispravno ime...",
'wlnote'               => "Ispod je {{PLURAL:$1|najskorija izmjena|'''$1''' najskorije izmjene|'''$1''' najskorijih izmjena}} načinjenih {{PLURAL:$2|posljednjeg sata|u posljednjih '''$2''' sata|u posljednjih '''$2''' sati}}.",
'wlshowlast'           => 'Prikaži posljednjih $1 sati $2 dana $3',
'watchlist-options'    => 'Opcije liste praćenja',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Pratim…',
'unwatching' => 'Ne pratim…',

'enotif_mailer'                => '{{SITENAME}} obavještenje o pošti',
'enotif_reset'                 => 'Označi sve strane kao posjećene',
'enotif_newpagetext'           => 'Ovo je nova stranica.',
'enotif_impersonal_salutation' => '{{SITENAME}} korisnik',
'changed'                      => 'promijenjena',
'created'                      => 'napravljena',
'enotif_subject'               => '{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED od strane $PAGEEDITOR',
'enotif_lastvisited'           => 'Pogledajte $1 za sve izmjene od vaše posljednje posjete.',
'enotif_lastdiff'              => 'Vidi $1 da pregledate ovu promjenu.',
'enotif_anon_editor'           => 'anonimni korisnik $1',
'enotif_body'                  => 'Poštovani $WATCHINGUSERNAME,

{{SITENAME}} stranica $PAGETITLE je bila $CHANGEDORCREATED $PAGEEDITDATE od strane $PAGEEDITOR,
pogledajte $PAGETITLE_URL za trenutnu verziju.

$NEWPAGE

Sažetak editora: $PAGESUMMARY $PAGEMINOREDIT

Kontaktirajte editora:
pošta: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Neće biti drugih obaviještenja u slučaju daljih izmjena ukoliko ne posjetite ovu stranu.
Također možete da resetujete zastavice za obaviještenja za sve Vaše praćene stranice na vašem spisku praćenih članaka.

             Vaš prijateljski {{SITENAME}} sistem obaviještavanja

--
Da promjenite podešavanja vezana za spisak praćenih članaka posjetite
{{fullurl:{{#special:Watchlist}}/edit}}

Da obrišete stranicu iz Vašeg spiska praćenja posjetite
$UNWATCHURL

Fidbek i dalja pomoć:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Izbrišite stranicu',
'confirm'                => 'Potvrdite',
'excontent'              => "sadržaj je bio: '$1'",
'excontentauthor'        => "sadržaj je bio: '$1' (i jedini korisnik koji je mijenjao bio je '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "sadržaj prije brisanja je bio: '$1'",
'exblank'                => 'stranica je bila prazna',
'delete-confirm'         => 'Brisanje "$1"',
'delete-legend'          => 'Obriši',
'historywarning'         => "'''Upozorenje''':  Stranica koju želite da obrišete ima historiju sa otprilike $1 {{PLURAL:$1|revizijom|revizije|revizija}}:",
'confirmdeletetext'      => 'Upravo ćete obrisati stranicu sa svom njenom historijom.
Molimo da potvrdite da ćete to učiniti, da razumijete posljedice te da to činite u skladu sa [[{{MediaWiki:Policy-url}}|pravilima]].',
'actioncomplete'         => 'Akcija završena',
'actionfailed'           => 'Akcija nije uspjela',
'deletedtext'            => '"<nowiki>$1</nowiki>" je obrisan/a.
V. $2 za registar nedavnih brisanja.',
'deletedarticle'         => 'obrisan "[[$1]]"',
'suppressedarticle'      => 'promijeni vidljivost od "[[$1]]"',
'dellogpage'             => 'Registar brisanja',
'dellogpagetext'         => 'Ispod je spisak najskorijih brisanja.',
'deletionlog'            => 'registar brisanja',
'reverted'               => 'Vraćeno na prijašnju reviziju',
'deletecomment'          => 'Razlog:',
'deleteotherreason'      => 'Ostali/dodatni razlog/zi:',
'deletereasonotherlist'  => 'Ostali razlog/zi',
'deletereason-dropdown'  => '*Uobičajeni razlozi brisanja
** Zahtjev autora
** Kršenje autorskih prava
** Vandalizam',
'delete-edit-reasonlist' => 'Uredi razloge brisanja',
'delete-toobig'          => 'Ova stranica ima veliku historiju promjena, preko $1 {{PLURAL:$1|revizije|revizija}}.
Brisanje takvih stranica nije dopušteno da bi se spriječilo slučajno preopterećenje servera na kojem je {{SITENAME}}.',
'delete-warning-toobig'  => 'Ova stranica ima veliku historiju izmjena, preko $1 {{PLURAL:$1|izmjene|izmjena}}.
Njeno brisanje može dovesti do opterećenja operacione baze na {{SITENAME}};
nastavite s oprezom.',

# Rollback
'rollback'          => 'Vrati izmjene',
'rollback_short'    => 'Vrati',
'rollbacklink'      => 'vrati',
'rollbackfailed'    => 'Vraćanje nije uspjelo',
'cantrollback'      => 'Nemoguće je vratiti izmjenu;
posljednji kontributor je jedini na ovoj stranici.',
'alreadyrolled'     => 'Ne može se vratiti posljednja izmjena [[:$1]] od korisnika [[User:$2|$2]] ([[User talk:$2|razgovor]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); neko drugi je već izmjenio ili vratio članak.

Posljednja izmjena je bila od korisnika [[User:$3|$3]] ([[User talk:$3|razgovor]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Sažetak izmjene je bio: \"''\$1''\".",
'revertpage'        => 'Vraćene izmjene [[Special:Contributions/$2|$2]] ([[User talk:$2|razgovor]]) na posljednju izmjenu korisnika [[User:$1|$1]]',
'revertpage-nouser' => 'Vraćene izmjene korisnika (korisničko ime uklonjeno) na posljednju reviziju koju je načinio [[User:$1|$1]]',
'rollback-success'  => 'Poništene izmjene korisnika $1;
vraćeno na posljednju verziju koju je snimio $2.',

# Edit tokens
'sessionfailure-title' => 'Greška u sesiji',
'sessionfailure'       => "Izgleda da postoji problem sa vašom sesijom; ova akcija je otkazana kao prevencija protiv napadanja sesija. Kliknite \"back\" (''nazad'') i osvježite stranicu sa koje ste došli, i opet pokušajte.",

# Protect
'protectlogpage'              => 'Registar zaštite',
'protectlogtext'              => 'Ispod je spisak zaštićenja i od-zaštićenja stranica.
Vidi [[Special:ProtectedPages|spisak zaštićenih stranica]] za spisak trenutno operativnih zaštita stranica.',
'protectedarticle'            => '"[[$1]]" zaštićeno',
'modifiedarticleprotection'   => 'promijenjen nivo zaštite za "[[$1]]"',
'unprotectedarticle'          => 'odštićena "[[$1]]"',
'movedarticleprotection'      => 'podešavanja zaštite premještena sa "[[$2]]" na "[[$1]]"',
'protect-title'               => 'Promjena nivoa zaštite za "$1"',
'prot_1movedto2'              => '[[$1]] premješten na [[$2]]',
'protect-legend'              => 'Potvrdite zaštitu',
'protectcomment'              => 'Razlog:',
'protectexpiry'               => 'Ističe:',
'protect_expiry_invalid'      => 'Upisani vremenski rok nije valjan.',
'protect_expiry_old'          => 'Upisani vremenski rok je u prošlosti.',
'protect-unchain-permissions' => 'Otključaj daljnje opcije zaštite',
'protect-text'                => "Ovdje možete gledati i izmijeniti nivo zaštite za stranicu '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Ne možete promijeniti nivo zaštite dok ste blokirani.
Ovo su trenutne postavke za stranicu '''$1''':",
'protect-locked-dblock'       => "Nivoi zaštite se ne mogu mijenjati jer je aktivna baza podataka zaključana.
Trenutna postavka za stranicu '''$1''' je:",
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
'protect-expiry-indefinite'   => 'neograničeno',
'protect-cascade'             => 'Zaštiti sve stranice koje su uključene u ovu (kaskadna zaštita)',
'protect-cantedit'            => 'Ne možete mijenjati nivo zaštite ove stranice, jer nemate prava da je uređujete..',
'protect-othertime'           => 'Ostali period:',
'protect-othertime-op'        => 'ostali period',
'protect-existing-expiry'     => 'Postojeće vrijeme isticanja: $3, $2',
'protect-otherreason'         => 'Ostali/dodatni razlog/zi:',
'protect-otherreason-op'      => 'Ostali razlozi',
'protect-dropdown'            => '*Uobičajeni razlozi zaštite
** Prekomjerni vandalizam
** Prekomjerno spamovanje
** Kontraproduktivni uređivački rat
** Stranica velikog prometa',
'protect-edit-reasonlist'     => 'Uredi razloge zaštite',
'protect-expiry-options'      => '1 sat:1 hour,1 dan:1 day,1 sedmica:1 week,2 sedmice:2 weeks,1 mjesec:1 month,3 mjeseca:3 months,6 mjeseci:6 months,1 godine:1 year,zauvijek:infinite',
'restriction-type'            => 'Dozvola:',
'restriction-level'           => 'Nivo ograničenja:',
'minimum-size'                => 'Najmanja veličina',
'maximum-size'                => 'Najveća veličina:',
'pagesize'                    => '(bajta)',

# Restrictions (nouns)
'restriction-edit'   => 'Uredi',
'restriction-move'   => 'Premjesti',
'restriction-create' => 'Napravi',
'restriction-upload' => 'Postavljanje',

# Restriction levels
'restriction-level-sysop'         => 'potpuno zaštićeno',
'restriction-level-autoconfirmed' => 'poluzaštićeno',
'restriction-level-all'           => 'svi nivoi',

# Undelete
'undelete'                     => 'Pregledaj izbrisane stranice',
'undeletepage'                 => 'Pogledaj i vrati izbrisane stranice',
'undeletepagetitle'            => "'''Slijedeći sadržaj prikazuje obrisane revizije od [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Pregledaj izbrisane stranice',
'undeletepagetext'             => '{{PLURAL:$1|Slijedeća $1 stranica je obrisana|Slijedeće $1 stranice su obrisane|Slijedećih $1 je obrisano}} ali su još uvijek u arhivi i mogu biti vraćene.
Arhiva moše biti periodično čišćena.',
'undelete-fieldset-title'      => 'Vraćanje revizija',
'undeleteextrahelp'            => "Da vratite cijelu historijus stranice, ostavite sve kutijice neoznačene i kliknite '''''Vrati'''''.
Da bi izvršili selektivno vraćanje članaka, odaberite kutijice koje odgovaraju revizijama koje želite vratiti, i kliknite '''''Vrati'''''.
Klikom na '''''Očisti''''' ćete očistiti polje za komentar i sve kutijice.",
'undeleterevisions'            => '{{PLURAL:$1|$1 revizija arhivirana|$1 revizije arhivirane|$1 revizija arhivirano}}',
'undeletehistory'              => 'Ako vratite stranicu, sve revizije će biti vraćene njenoj historiji.
Ako je nova stranica istog imena napravljena od brisanja, vraćene revizije će se pojaviti u njenoj ranijoj historiji.',
'undeleterevdel'               => 'Vraćanje obrisanog se neće izvršiti ako bi rezultiralo da zaglavlje stranice ili revizija datoteke bude djelimično obrisano.
U takvim slučajevima, morate ukloniti označene ili otkriti sakrivene najskorije obrisane revizije.',
'undeletehistorynoadmin'       => 'Ova stranica je izbrisana.  
Razlog za brisanje se nalazi ispod u sažetku, zajedno sa detaljima korisnika koji su uređivali ovu stranicu prije brisanja.  
Tekst izbrisane stranice je vidljiv samo administratorima.',
'undelete-revision'            => 'Obrisana revizija stranice $1 (dana $4, u $5) od strane $3:',
'undeleterevision-missing'     => 'Nevaljana ili nedostajuća revizija.
Možda ste unijeli pogrešan link, ili je revizija vraćena ili uklonjena iz arhive.',
'undelete-nodiff'              => 'Nije pronađena ranija revizija.',
'undeletebtn'                  => 'Vrati',
'undeletelink'                 => 'pogledaj/vrati',
'undeleteviewlink'             => 'pogledaj',
'undeletereset'                => 'Očisti',
'undeleteinvert'               => 'Sve osim odabranog',
'undeletecomment'              => 'Razlog:',
'undeletedarticle'             => 'vraćen "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|$1 revizija vraćena|$1 revizije vraćene|$1 revizija vraćeno}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 revizija|$1 revizije|$1 revizija}} i {{PLURAL:$2|1 datoteka|$2 datoteke|$2 datoteka}} vraćeno',
'undeletedfiles'               => '{{PLURAL:$1|1 datoteka vraćena|$1 datoteke vraćene|$1 datoteka vraćeno}}',
'cannotundelete'               => 'Vraćanje nije uspjelo;
neko drugi je već vratio ovu stranicu.',
'undeletedpage'                => "'''$1 je vraćena'''

Provjerite [[Special:Log/delete|evidenciju brisanja]] za zapise najskorijih brisanja i vraćanja.",
'undelete-header'              => 'Pogledajte [[Special:Log/delete|evidenciju brisanja]] za nedavno obrisane stranice.',
'undelete-search-box'          => 'Pretraga obrisanih stranica',
'undelete-search-prefix'       => 'Prikaži stranice koje počinju sa:',
'undelete-search-submit'       => 'Traži',
'undelete-no-results'          => 'Nije pronađena odgovarajuća stranica u arhivi brisanja.',
'undelete-filename-mismatch'   => 'Ne može se vratiti revizija datoteke od $1: pogrešno ime datoteke',
'undelete-bad-store-key'       => 'Ne može se vratiti revizija datoteke sa vremenskom oznakom $1: datoteka je nestala prije brisanja.',
'undelete-cleanup-error'       => 'Greška pri brisanju nekorištene arhivske datoteke "$1".',
'undelete-missing-filearchive' => 'Ne može se vratiti arhivska datoteka sa ID oznakom $1 jer nije u bazi podataka.
Možda je već ranije vraćena.',
'undelete-error-short'         => 'Greška pri vraćanju datoteke: $1',
'undelete-error-long'          => 'Desile su se pogreške pri vraćanju datoteke:

$1',
'undelete-show-file-confirm'   => 'Da li ste sigurni da želite pogledati obrisanu reviziju datoteke "<nowiki>$1</nowiki>" od $2 u $3?',
'undelete-show-file-submit'    => 'Da',

# Namespace form on various pages
'namespace'      => 'Imenski prostor:',
'invert'         => 'Sve osim odabranog',
'blanknamespace' => '(Glavno)',

# Contributions
'contributions'       => 'Doprinosi korisnika',
'contributions-title' => 'Korisnički doprinosi od $1',
'mycontris'           => 'Moji doprinosi',
'contribsub2'         => 'Za $1 ($2)',
'nocontribs'          => 'Nisu nađene promjene koje zadovoljavaju ove uslove.',
'uctop'               => '(vrh)',
'month'               => 'Od mjeseca (i ranije):',
'year'                => 'Od godine (i ranije):',

'sp-contributions-newbies'             => 'Pokaži doprinose samo novih korisnika',
'sp-contributions-newbies-sub'         => 'Prikaži samo doprinose novih korisnika',
'sp-contributions-newbies-title'       => 'Doprinosi novih korisnika',
'sp-contributions-blocklog'            => 'registar blokiranja',
'sp-contributions-deleted'             => 'obrisani doprinosi korisnika',
'sp-contributions-uploads'             => 'postavljanja',
'sp-contributions-logs'                => 'registri',
'sp-contributions-talk'                => 'razgovor',
'sp-contributions-userrights'          => 'postavke korisničkih prava',
'sp-contributions-blocked-notice'      => 'Ovaj korisnik je trenutno blokiran. 
Posljednje stavke evidencije blokiranja možete pogledati ispod:',
'sp-contributions-blocked-notice-anon' => 'Ova IP adresa je trenutno blokirana.
Posljednje stavke zapisnika blokiranja možete pogledati ispod:',
'sp-contributions-search'              => 'Pretraga doprinosa',
'sp-contributions-username'            => 'IP adresa ili korisničko ime:',
'sp-contributions-toponly'             => 'Prikaži samo izmjene koje su posljednje revizije',
'sp-contributions-submit'              => 'Traži',

# What links here
'whatlinkshere'            => 'Šta je povezano ovdje',
'whatlinkshere-title'      => 'Stranice koje vode na "$1"',
'whatlinkshere-page'       => 'Stranica:',
'linkshere'                => "Sljedeće stranice vode na '''[[:$1]]''':",
'nolinkshere'              => "Nema linkova na '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nijedna stranica nije povezana sa '''[[:$1]]''' u odabranom imenskom prostoru.",
'isredirect'               => 'preusmjeri stranicu',
'istemplate'               => 'kao šablon',
'isimage'                  => 'link datoteke',
'whatlinkshere-prev'       => '{{PLURAL:$1|prethodni|prethodna|prethodnih}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|sljedeći|sljedeća|sljedećih}} $1',
'whatlinkshere-links'      => '← linkovi',
'whatlinkshere-hideredirs' => '$1 preusmjerenja',
'whatlinkshere-hidetrans'  => '$1 uključenja',
'whatlinkshere-hidelinks'  => '$1 linkove',
'whatlinkshere-hideimages' => '$1 linkove slika',
'whatlinkshere-filters'    => 'Filteri',

# Block/unblock
'blockip'                         => 'Blokiraj korisnika',
'blockip-title'                   => 'Blokiraj korisnika',
'blockip-legend'                  => 'Blokiranje korisnika',
'blockiptext'                     => 'Upotrebite donji upitnik da biste uklonili prava pisanja sa određene IP adrese ili korisničkog imena.  
Ovo bi trebalo da bude urađeno samo da bi se spriječio vandalizam, i u skladu sa [[{{MediaWiki:Policy-url}}|smjernicama]]. 
Unesite konkretan razlog ispod (na primjer, navodeći koje konkretne stranice su vandalizovane).',
'ipadressorusername'              => 'IP adresa ili korisničko ime:',
'ipbexpiry'                       => 'Ističe:',
'ipbreason'                       => 'Razlog:',
'ipbreasonotherlist'              => 'Ostali razlog/zi',
'ipbreason-dropdown'              => '*Najčešći razlozi blokiranja
**Unošenje netačnih informacija
**Uklanjanje sadržaja stranica
**Postavljanje spam vanjskih linkova
**Ubacivanje gluposti/grafita
**Osobni napadi (ili napadačko ponašanje)
**Čarapare (zloupotreba više korisničkih računa)
**Neprihvatljivo korisničko ime',
'ipbcreateaccount'                => 'Onemogući stvaranje računa',
'ipbemailban'                     => 'Onemogući korisnika da šalje e-mail',
'ipbenableautoblock'              => 'Automatski blokiraj zadnju IP adresu koju je koristio ovaj korisnik i sve druge IP adrese s kojih je on pokušao uređivati',
'ipbsubmit'                       => 'Blokirajte ovog korisnika',
'ipbother'                        => 'Ostali period:',
'ipboptions'                      => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 sedmica:1 week,2 sedmice:2 weeks,1 mjesec:1 month,3 mjeseca:3 months,6 mjeseci:6 months,1 godine:1 year,zauvijek:infinite',
'ipbotheroption'                  => 'ostalo',
'ipbotherreason'                  => 'Ostali/dodatni razlog/zi:',
'ipbhidename'                     => 'Sakrij korisničko ime iz uređivanja i spiskova',
'ipbwatchuser'                    => 'Prati korisničku stranicu i stranicu za razgovor ovog korisnika',
'ipb-change-block'                => 'Ponovno blokiraj korisnika sa ovim postavkama',
'badipaddress'                    => 'Nevaljana IP adresa',
'blockipsuccesssub'               => 'Blokiranje je uspjelo',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] je blokiran.
<br />Pogledajte [[Special:IPBlockList|IP spisak blokiranih korisnika]] za pregled blokiranja.',
'ipb-edit-dropdown'               => 'Uredi razloge blokiranja',
'ipb-unblock-addr'                => 'Deblokiraj $1',
'ipb-unblock'                     => 'Deblokiraj korisničko ime ili IP adresu',
'ipb-blocklist'                   => 'Vidi postojeće blokade',
'ipb-blocklist-contribs'          => 'Doprinosi za $1',
'unblockip'                       => 'Odblokiraj korisnika',
'unblockiptext'                   => 'Upotrebite donji upitnik da bi ste vratili pravo pisanja ranije blokiranoj IP adresi ili korisničkom imenu.',
'ipusubmit'                       => 'Ukloni ovu blokadu',
'unblocked'                       => '[[User:$1|$1]] je deblokiran',
'unblocked-id'                    => 'Blokada ID oznake $1 je uklonjena',
'ipblocklist'                     => 'Blokirane IP adrese i korisnička imena',
'ipblocklist-legend'              => 'Traži blokiranog korisnika',
'ipblocklist-submit'              => 'Traži',
'ipblocklist-localblock'          => 'Lokalna blokada',
'ipblocklist-otherblocks'         => 'Ostale {{PLURAL:$1|blokada|blokade}}',
'infiniteblock'                   => 'nije ograničena',
'expiringblock'                   => 'ističe dana $1 u $2',
'anononlyblock'                   => 'samo anonimni korisnici',
'noautoblockblock'                => 'automatsko blokiranje onemogućeno',
'createaccountblock'              => 'blokirano stvaranje računa',
'emailblock'                      => 'e-mail blokiran',
'blocklist-nousertalk'            => 'ne može uređivati vlastitu stranicu za razgovor',
'ipblocklist-empty'               => 'Spisak blokiranja je prazan.',
'ipblocklist-no-results'          => 'Tražena IP adresa ili korisničko ime nisu blokirani.',
'blocklink'                       => 'blokirajte',
'unblocklink'                     => 'deblokiraj',
'change-blocklink'                => 'promijeni blokadu',
'contribslink'                    => 'doprinosi',
'autoblocker'                     => 'Automatski ste blokirani jer dijelite IP adresu sa "[[User:$1|$1]]".
Razlog za blokiranje je korisnika $1 je: \'\'$2\'\'',
'blocklogpage'                    => 'Registar blokiranja',
'blocklog-showlog'                => 'Ovaj korisnik je ranije blokiran. 
Evidencija blokiranja je prikazana ispod kao referenca:',
'blocklog-showsuppresslog'        => 'Ovaj korisnik je ranije blokiran i sakriven. 
Evidencija sakrivanja je prikazana ispod kao referenca:',
'blocklogentry'                   => 'blokiran [[$1]] s rokom: $2 $3',
'reblock-logentry'                => 'promjena postavki blokiranja za [[$1]] sa vremenom isteka u $2 $3',
'blocklogtext'                    => 'Ovo je historija akcija blokiranja i deblokiranja korisnika.
Automatsko blokirane IP adrese nisu uspisane ovde.
Pogledajte [[Special:IPBlockList|blokirane IP adrese]] za spisak trenutnih zabrana i blokiranja.',
'unblocklogentry'                 => 'deblokiran $1',
'block-log-flags-anononly'        => 'samo anonimni korisnici',
'block-log-flags-nocreate'        => 'pravljenje računa onemogućeno',
'block-log-flags-noautoblock'     => 'automatsko blokiranje onemogućeno',
'block-log-flags-noemail'         => 'e-mail je blokiran',
'block-log-flags-nousertalk'      => 'ne može uređivati vlastitu stranicu za razgovor',
'block-log-flags-angry-autoblock' => 'omogućeno napredno autoblokiranje',
'block-log-flags-hiddenname'      => 'korisničko ime sakriveno',
'range_block_disabled'            => 'Administratorska mogućnost da blokira grupe je isključena.',
'ipb_expiry_invalid'              => 'Nevaljano vrijeme trajanja.',
'ipb_expiry_temp'                 => 'Sakrivene blokade korisničkih imena moraju biti stalne.',
'ipb_hide_invalid'                => 'Ne može se onemogućiti ovaj račun; možda ima isuviše izmjena.',
'ipb_already_blocked'             => '"$1" je već blokiran',
'ipb-needreblock'                 => '== Već blokirano ==
$1 je već blokiran. 
Da li želite promijeniti postavke?',
'ipb-otherblocks-header'          => 'Ostale {{PLURAL:$1|blokada|blokade}}',
'ipb_cant_unblock'                => 'Greška: Blokada sa ID oznakom $1 nije pronađena.
Možda je već deblokirana.',
'ipb_blocked_as_range'            => 'Greška: IP adresa $1 nije direktno blokirana i ne može se deblokirati.
Međutim, možda je blokirana kao dio bloka $2, koji se može deblokirati.',
'ip_range_invalid'                => 'Netačan raspon IP adresa.',
'ip_range_toolarge'               => 'Grupne blokade veće od /$1 nisu dozvoljene.',
'blockme'                         => 'Blokiraj me',
'proxyblocker'                    => 'Bloker proksija',
'proxyblocker-disabled'           => 'Ova funkcija je onemogućena.',
'proxyblockreason'                => 'Vaša IP adresa je blokirana jer je ona otvoreni proksi.  
Molimo vas da kontaktirate vašeg davatelja internetskih usluga (Internet Service Provider-a) ili tehničku podršku i obavijestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess'               => 'Proksi uspješno blokiran.',
'sorbsreason'                     => 'Vaša IP adresa je prikazana kao otvoreni proxy u DNSBL koji koristi {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Vaša IP adresa je prikazana kao otvoreni proxy u DNSBL korišten od {{SITENAME}}.
Ne možete napraviti račun',
'cant-block-while-blocked'        => 'Ne možete blokirati druge korisnike dok ste blokirani.',
'cant-see-hidden-user'            => 'Korisnik kojeg pokušavate blokirati je već blokiran i sakriven. 
Pošto nemate prava hideuser (sakrivanje korisnika), ne možete vidjeti ni urediti korisnikovu blokadu.',
'ipbblocked'                      => 'Ne možete blokirati ili deblokirati druge korisnike, jer ste i sami blokirani',
'ipbnounblockself'                => 'Nije Vam dopušteno da deblokirate samog sebe',

# Developer tools
'lockdb'              => 'Zaključajte bazu',
'unlockdb'            => 'Otključaj bazu',
'lockdbtext'          => 'Zaključavanje baze će svim korisnicima ukinuti mogućnost izmjene stranica, promjene korisničkih podešavanja, izmjene praćenih članaka, i svega ostalog što zahtjeva promjene u bazi.
Molimo Vas da potvrdite da je ovo zaista ono što namjeravate da uradite, i da ćete otključati bazu kad završite posao oko njenog održavanja.',
'unlockdbtext'        => 'Otključavanje baze će svim korisnicima vratiti mogućnost izmjene stranica, promjene korisničkih stranica, izmjene spiska praćenih članaka, i svega ostalog što zahtjeva promjene u bazi.
Molimo Vas da potvrdite da je ovo zaista ono što namijeravate da uradite.',
'lockconfirm'         => 'Da, zaista želim da zaključam bazu.',
'unlockconfirm'       => 'Da, zaista želim da otključam bazu.',
'lockbtn'             => 'Zaključajte bazu',
'unlockbtn'           => 'Otključaj bazu',
'locknoconfirm'       => 'Niste potvrdili svoju namjeru.',
'lockdbsuccesssub'    => 'Baza je uspješno zaključana',
'unlockdbsuccesssub'  => 'Baza je otključana',
'lockdbsuccesstext'   => 'Baza podataka je zaključana.<br />
Sjetite se da je [[Special:UnlockDB|otključate]] nakon što dovršite održavanje.',
'unlockdbsuccesstext' => 'Baza podataka je otključana.',
'lockfilenotwritable' => 'Datoteka zaključavanja baze je zaštićena za pisanje.
Ako želite otključati ili zaključati bazu, ova datoteka mora biti omogućena za pisanje od strane web servera.',
'databasenotlocked'   => 'Baza podataka nije zaključana.',

# Move page
'move-page'                    => 'Preusmjeravanje $1',
'move-page-legend'             => 'Premjestite stranicu',
'movepagetext'                 => "Korištenjem donjeg formulara možete preimenovati stranicu, preusmjerivši njenu historiju na novi naziv.
Stari naslov će postati stranica za preusmjerenje na novi naslov.
Možete updateirati preusmjerenja koja idu na originalni naslov automatski.
Ako to ne učinite, budite sigurni da ste provjerili [[Special:DoubleRedirects|dupla]] ili [[Special:BrokenRedirects|mrtva prusmjerenja]].
Odgovorni ste za to da poveznice nastave povezivati stranice kojima su namijenjene.

Uzmite u obzir da stranica '''neće''' biti preusmjerena ako već postoji stranica s novim naslovom, osim ako je prazna ili ako je preusmjerenje bez prethodne historije uređivanja.
To znači da stranicu možete ponovno preimenovati u stari naslov ako je u pitanju bila pogreška, te da ne možete presnimiti već postojeću stranicu.

'''UPOZORENJE!'''
Ovo može biti drastična i neočekivana promjena za popularnu stranicu;
budite sigurni da ste shvatili sve posljedice prije nego što nastavite.",
'movepagetext-noredirectfixer' => "Koristeći obrazac ispod ćete preimenovati stranicu i premjestiti cijelu njenu historiju na novi naziv.
Stari naziv će postati preusmjerenje na novi naziv.
Molimo provjerite da li postoje [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|nedovršena preusmjerenja]].
Vi ste za to odgovorni te morate provjeriti da li su linkovi ispravni i da li vode tamo gdje bi trebali.

Imajte na umu da stranica '''neće''' biti premještena ako već postoji stranica s tim imenom, osim ako je prazna ili je preusmjerenje ili nema ranije historije.
Ovo znali da možete preimenovati stranicu nazad gdje je ranije bila preimenovana ako ste pogriješili a ne možete ponovo preimenovati postojeću stranicu.

'''Pažnja!'''
Imajte na umu da preusmjeravanje popularnog članka može biti
drastična i neočekivana promjena za korisnike; molimo budite sigurni da ste shvatili posljedice prije nego što nastavite.",
'movepagetalktext'             => "Odgovarajuća stranica za razgovor, ako postoji, će automatski biti premještena istovremeno '''osim:'''
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odznačite donju kutiju.

U tim slučajevima, moraćete ručno da premjestite stranicu ukoliko to želite.",
'movearticle'                  => 'Premjestite stranicu:',
'moveuserpage-warning'         => "'''Upozorenje:''' Premještate korisničku stranicu. Molimo da zapamtite da će se samo stranica premjestiti a korisnik se ''neće'' preimenovati.",
'movenologin'                  => 'Niste prijavljeni',
'movenologintext'              => 'Morate biti registrovani korisnik i [[Special:UserLogin|prijavljeni]] da biste premjestili stranicu.',
'movenotallowed'               => 'Nemate dopuštenje za premještanje stranica.',
'movenotallowedfile'           => 'Nemate dopuštenja da premještate datoteke.',
'cant-move-user-page'          => 'Nemate dopuštenje da premještate korisničke stranice (osim podstranica).',
'cant-move-to-user-page'       => 'Nemate dopuštenje da premjestite stranicu na korisničku stranicu (osim na korisničku podstranicu).',
'newtitle'                     => 'novi naslov:',
'move-watch'                   => 'Prati ovu stranicu',
'movepagebtn'                  => 'premjestite stranicu',
'pagemovedsub'                 => 'Premještanje uspjelo',
'movepage-moved'               => '\'\'\'"$1" je premještena na "$2"\'\'\'',
'movepage-moved-redirect'      => 'Preusmjerenje je napravljeno.',
'movepage-moved-noredirect'    => 'Pravljenje preusmjerenja je onemogućeno.',
'articleexists'                => 'Stranica pod tim imenom već postoji, ili je ime koje ste izabrali neispravno.
Molimo Vas da izaberete drugo ime.',
'cantmove-titleprotected'      => 'Ne možete premjestiti stranicu na ovu lokaciju, jer je novi naslov zaštićen od pravljenja',
'talkexists'                   => "'''Sama stranica je uspješno premještena, ali stranica za razgovor nije mogla biti premještena jer takva već postoji na novom naslovu.  Molimo Vas da ih spojite ručno.'''",
'movedto'                      => 'premještena na',
'movetalk'                     => 'Premjestite pridruženu stranicu za razgovor',
'move-subpages'                => 'Premjesti sve podstranice (do $1)',
'move-talk-subpages'           => 'Premjesti podstranice stranica za razgovor (do $1)',
'movepage-page-exists'         => 'Stranica $1 već postoji i ne može biti automatski zamijenjena.',
'movepage-page-moved'          => 'Stranica $1 je premještena na $2.',
'movepage-page-unmoved'        => 'Stranica $1 ne može biti premještena na $2.',
'movepage-max-pages'           => 'Maksimum od $1 {{PLURAL:$1|stranice|stranice|stranica}} je premješteno i više nije moguće premjestiti automatski.',
'1movedto2'                    => '[[$1]] premješteno na [[$2]]',
'1movedto2_redir'              => 'premještena [[$1]] na [[$2]] putem preusmjerenja',
'move-redirect-suppressed'     => 'preusmjeravanje onemogućeno',
'movelogpage'                  => 'Registar premještanja',
'movelogpagetext'              => 'Ispod je spisak stranica koje su premještene.',
'movesubpage'                  => '{{PLURAL:$1|Podstranica|Podstranice}}',
'movesubpagetext'              => 'Ova stranica ima $1 {{PLURAL:$1|podstranicu|podstranice|podstranica}} prikazanih ispod.',
'movenosubpage'                => 'Ova stranica nema podstranica.',
'movereason'                   => 'Razlog:',
'revertmove'                   => 'vrati',
'delete_and_move'              => 'Brisanje i premještanje',
'delete_and_move_text'         => '==Brisanje neophodno==
Odredišna stranica "[[:$1]]" već postoji.
Da li je želite obrisati kako bi ste mogli izvršiti premještanje?',
'delete_and_move_confirm'      => 'Da, obriši stranicu',
'delete_and_move_reason'       => 'Obrisano da bi se napravio prostor za premještanje',
'selfmove'                     => 'Izvorni i ciljani naziv su isti; strana ne može da se premjesti preko same sebe.',
'immobile-source-namespace'    => 'Ne mogu premjestiti stranice u imenski prostor "$1"',
'immobile-target-namespace'    => 'Ne mogu se premjestiti stranice u imenski prostor "$1"',
'immobile-target-namespace-iw' => 'Međuwiki link nije valjano odredište premještanja stranice.',
'immobile-source-page'         => 'Ova stranica se ne može premještati.',
'immobile-target-page'         => 'Ne može se preusmjeriti na taj odredišni naslov.',
'imagenocrossnamespace'        => 'Ne može se premjestiti datoteka u nedatotečni imenski prostor',
'nonfile-cannot-move-to-file'  => 'Ne mogu se premjestiti podaci u datotečni imenski prostor',
'imagetypemismatch'            => 'Ekstenzija nove datoteke ne odgovara njenom tipu',
'imageinvalidfilename'         => 'Ciljno ime datoteke nije valjano',
'fix-double-redirects'         => 'Ažuriraj sva preusmjerenja koja vode ka originalnom naslovu',
'move-leave-redirect'          => 'Ostavi preusmjerenje',
'protectedpagemovewarning'     => "'''Upozorenje:''' Ova stranica je zaključana tako da je mogu premještati samo korisnici sa ovlastima administratora.
Posljednja stavka evidencije je prikazana ispod kao referenca:",
'semiprotectedpagemovewarning' => "'''Napomena:''' Ova stranica je zaključana tako da je mogu uređivati samo registrovani korisnici.
Posljednja stavka evidencije je prikazana ispod kao referenca:",
'move-over-sharedrepo'         => '== Datoteka postoji ==
[[:$1]] postoji na dijeljenom repozitorijumu. Premještanje datoteke na ovaj naslov će prepisati dijeljenu datoteku.',
'file-exists-sharedrepo'       => 'Ime datoteke koje ste odabrali je već korišteno u dijeljenom repozitorijumu.
Molimo odaberite drugo ime.',

# Export
'export'            => 'Izvezite stranice',
'exporttext'        => 'Možete izvesti tekst i historiju jedne ili više stranica uklopljene u XML kod.
Ovo se može uvesti u drugi wiki koristeći MediaWiki preko [[Special:Import|stranice uvoza]].

Za izvoz stranica unesite njihove naslove u polje ispod, jedan naslov po retku, i označite želite li trenutnu verziju zajedno sa svim ranijim, ili samo trenutnu verziju sa informacijom o zadnjoj promjeni.

U drugom slučaju možete koristiti i vezu, npr. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za stranicu [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Uključite samo trenutnu reviziju, ne cijelu historiju',
'exportnohistory'   => "----
'''Pažnja:''' Izvoz cjelokupne historije stranica preko ovog obrasca je onemogućeno iz tehničkih razloga.",
'export-submit'     => 'Izvezi',
'export-addcattext' => 'Dodaj stranice iz kategorije:',
'export-addcat'     => 'Dodaj',
'export-addnstext'  => 'Dodaj stranice iz imenskog prostora:',
'export-addns'      => 'Dodaj',
'export-download'   => 'Spremi kao datoteku',
'export-templates'  => 'Uključi šablone',
'export-pagelinks'  => 'Uključi povezane stranice do dubine od:',

# Namespace 8 related
'allmessages'                   => 'Sistemske poruke',
'allmessagesname'               => 'Naziv',
'allmessagesdefault'            => 'Uobičajeni tekst',
'allmessagescurrent'            => 'Trenutni tekst',
'allmessagestext'               => 'Ovo je spisak svih sistemskih poruka u dostupnih u MediaWiki imenskom prostoru.
Molimo posjetite [http://www.mediawiki.org/wiki/Localisation MediaWiki lokalizaciju] i [http://translatewiki.net translatewiki.net] ako želite doprinijeti općoj lokalizaciji MediaWikija.',
'allmessagesnotsupportedDB'     => "Ova stranica ne može biti korištena jer je '''\$wgUseDatabaseMessages''' isključen.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Filter po stanju podešavanja:',
'allmessages-filter-unmodified' => 'Neizmijenjeno',
'allmessages-filter-all'        => 'Sve',
'allmessages-filter-modified'   => 'Izmijenjeno',
'allmessages-prefix'            => 'Filter po prefiksu:',
'allmessages-language'          => 'Jezik:',
'allmessages-filter-submit'     => 'Idi',

# Thumbnails
'thumbnail-more'           => 'Uvećaj',
'filemissing'              => 'Nedostaje datoteka',
'thumbnail_error'          => 'Greška pri pravljenju umanjene slike: $1',
'djvu_page_error'          => 'DjVu stranica je van opsega',
'djvu_no_xml'              => 'Za XML-datoteku se ne može pozvati DjVu datoteka',
'thumbnail_invalid_params' => 'Pogrešne postavke smanjenog prikaza',
'thumbnail_dest_directory' => 'Ne može se napraviti odredišni folder',
'thumbnail_image-type'     => 'Tip slike nije podržan',
'thumbnail_gd-library'     => 'Nekompletna konfiguracija GD biblioteke: nedostaje funkcija $1',
'thumbnail_image-missing'  => 'Čini se da datoteka nedostaje: $1',

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
'imported-log-entries'       => '{{PLURAL:$1|Uvezena $1 stavka registra|Uvezene $1 stavke registra|Uvezeno $1 stavki registra}}.',
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
'tooltip-pt-anonuserpage'         => 'Korisnička stranica za ip koju Vi uređujete kao',
'tooltip-pt-mytalk'               => 'Vaša stranica za razgovor',
'tooltip-pt-anontalk'             => 'Razgovor o doprinosu sa ove IP adrese',
'tooltip-pt-preferences'          => 'Vaše postavke',
'tooltip-pt-watchlist'            => 'Spisak stranica koje pratite radi izmjena',
'tooltip-pt-mycontris'            => 'Spisak vaših doprinosa',
'tooltip-pt-login'                => 'Predlažem da se prijavite; međutim, to nije obavezno',
'tooltip-pt-anonlogin'            => 'Predlažemo da se prijavite, ali nije obavezno.',
'tooltip-pt-logout'               => 'Odjava sa projekta {{SITENAME}}',
'tooltip-ca-talk'                 => 'Razgovor o sadržaju stranice',
'tooltip-ca-edit'                 => 'Možete da uređujete ovu stranicu.
Molimo da prije snimanja koristite dugme za pretpregled',
'tooltip-ca-addsection'           => 'Započnite novu sekciju.',
'tooltip-ca-viewsource'           => 'Ova stranica je zaštićena.
Možete vidjeti njen izvor',
'tooltip-ca-history'              => 'Prethodne verzije ove stranice',
'tooltip-ca-protect'              => 'Zaštiti ovu stranicu',
'tooltip-ca-unprotect'            => 'Odštiti ovu stranicu',
'tooltip-ca-delete'               => 'Izbriši ovu stranicu',
'tooltip-ca-undelete'             => 'Vratite izmjene koje su načinjene prije brisanja stranice',
'tooltip-ca-move'                 => 'Premjesti ovu stranicu',
'tooltip-ca-watch'                => 'Dodajte ovu stranicu na Vaš spisak praćenja',
'tooltip-ca-unwatch'              => 'Izbrišite ovu stranicu sa spiska praćenja',
'tooltip-search'                  => 'Pretraži ovaj wiki',
'tooltip-search-go'               => 'Idi na stranicu s upravo ovakvim imenom ako postoji',
'tooltip-search-fulltext'         => 'Pretraga stranica sa ovim tekstom',
'tooltip-p-logo'                  => 'Posjetite glavnu stranicu',
'tooltip-n-mainpage'              => 'Posjetite glavnu stranu',
'tooltip-n-mainpage-description'  => 'Posjetite glavnu stranicu',
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
'tooltip-ca-nstab-media'          => 'Pogledajte medijski fajl',
'tooltip-ca-nstab-special'        => 'Ovo je posebna stranica, te je ne možete uređivati',
'tooltip-ca-nstab-project'        => 'Pogledajte stranicu projekta',
'tooltip-ca-nstab-image'          => 'Vidi stranicu datoteke/fajla',
'tooltip-ca-nstab-mediawiki'      => 'Pogledajte sistemsku poruku',
'tooltip-ca-nstab-template'       => 'Pogledajte šablon',
'tooltip-ca-nstab-help'           => 'Pogledajte stranicu za pomoć',
'tooltip-ca-nstab-category'       => 'Pogledajte stranicu kategorije',
'tooltip-minoredit'               => 'Označite ovo kao manju izmjenu',
'tooltip-save'                    => 'Snimite vaše izmjene',
'tooltip-preview'                 => 'Prethodni pregled stranice, molimo koristiti prije snimanja!',
'tooltip-diff'                    => 'Prikaz izmjena koje ste napravili u tekstu',
'tooltip-compareselectedversions' => 'Pogledajte pazlike između dvije selektovane verzije ove stranice.',
'tooltip-watch'                   => 'Dodajte ovu stranicu na Vaš spisak praćenja',
'tooltip-recreate'                => 'Ponovno pravljenje stranice iako je već brisana',
'tooltip-upload'                  => 'Započni postavljanje',
'tooltip-rollback'                => '"Povrat" briše izmjenu/e posljednjeg uređivača ove stranice jednim klikom',
'tooltip-undo'                    => 'Vraća ovu izmjenu i otvara formu uređivanja u modu pretpregleda.
Dozvoljava unošenje razloga za to u sažetku.',
'tooltip-preferences-save'        => 'Snimi postavke',
'tooltip-summary'                 => 'Unesite kratki sažetak',

# Metadata
'nodublincore'      => 'Dublin Core RDF metapodaci onemogućeni za ovaj server.',
'nocreativecommons' => 'Creative Commons RDF metapodaci onemogućeni za ovaj server.',
'notacceptable'     => 'Viki server ne može da pruži podatke u onom formatu koji Vaš klijent može da pročita.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonimni korisnik|$1 anonimna korisnika|$1 anonimnih korisnika}} projekta {{SITENAME}}',
'siteuser'         => '{{SITENAME}} korisnik $1',
'anonuser'         => '{{SITENAME}} anonimni korisnik $1',
'lastmodifiedatby' => 'Ovu stranicu je posljednji put promjenio $3, u $2, $1',
'othercontribs'    => 'Bazirano na radu od strane korisnika $1.',
'others'           => 'ostali',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|korisnik|korisnika}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anonimni korisnik|anonimni korisnici}} $1',
'creditspage'      => 'Autori stranice',
'nocredits'        => 'Autori ove stranice nisu navedeni.',

# Spam protection
'spamprotectiontitle' => 'Filter za zaštitu od neželjenih poruka',
'spamprotectiontext'  => 'Strana koju želite da sačuvate je blokirana od strane filtera za neželjene poruke.
Ovo je vjerovatno izazvano vezom ka vanjskoj nepoželjnoj stranici.',
'spamprotectionmatch' => 'Slijedeći tekst je izazvao naš filter za neželjene poruke: $1',
'spambot_username'    => 'MediaWiki čišćenje spama',
'spam_reverting'      => 'Vraćanje na zadnju verziju koja ne sadrži linkove ka $1',
'spam_blanking'       => 'Sve revizije koje sadrže linkove ka $1, očisti',

# Info page
'infosubtitle'   => 'Informacije za stranicu',
'numedits'       => 'Broj izmjena (stranica): $1',
'numtalkedits'   => 'Broj izmjena (stranice za razgovor): $1',
'numwatchers'    => 'Broj onih koji pregledaju: $1',
'numauthors'     => 'Broj različitih autora (stranice): $1',
'numtalkauthors' => 'Broj različitih autora (stranice za razgovor): $1',

# Math options
'mw_math_png'    => 'Uvijek prikaži kao PNG',
'mw_math_simple' => 'HTML ako je jednostavno, inače PNG',
'mw_math_html'   => 'HTML ako je moguće, inače PNG',
'mw_math_source' => 'Ostavi kao TeX (za tekstualne preglednike)',
'mw_math_modern' => 'Preporučeno za moderne preglednike',
'mw_math_mathml' => 'MathML ako je moguće (eksperimentalno)',

# Math errors
'math_failure'          => 'Neuspjeh pri parsiranju',
'math_unknown_error'    => 'nepoznata greška',
'math_unknown_function' => 'nepoznata funkcija',
'math_lexing_error'     => 'riječnička greška',
'math_syntax_error'     => 'sintaksna greška',
'math_image_error'      => 'PNG konverzija neuspješna; provjerite tačnu instalaciju latex-a i dvipng-a (ili dvips + gs + convert)',
'math_bad_tmpdir'       => 'Ne može se napisati ili napraviti privremeni matematički direktorijum',
'math_bad_output'       => 'Ne može se napisati ili napraviti direktorijum za matematički izvještaj.',
'math_notexvc'          => 'Nedostaje izvršno texvc; molimo Vas da pogledate math/README da podesite.',

# Patrolling
'markaspatrolleddiff'                 => 'Označi kao patrolirano',
'markaspatrolledtext'                 => 'Označi ovu stranicu kao patroliranu',
'markedaspatrolled'                   => 'Označeno kao patrolirano',
'markedaspatrolledtext'               => 'Izabrana revizija [[:$1]] je bila označena kao patrolirana.',
'rcpatroldisabled'                    => 'Patroliranje nedavnih izmjena onemogućeno',
'rcpatroldisabledtext'                => 'Funkcija patroliranja nedavnih izmjena je trenutno isključena.',
'markedaspatrollederror'              => 'Ne može se označiti kao patrolirano',
'markedaspatrollederrortext'          => 'Morate naglasiti reviziju koju treba označiti kao patroliranu.',
'markedaspatrollederror-noautopatrol' => 'Nije Vam dopušteno da vlastite izmjene označavate patroliranim.',

# Patrol log
'patrol-log-page'      => 'Evidencija patroliranja',
'patrol-log-header'    => 'Ovdje se nalazi evidencija patroliranih revizija.',
'patrol-log-line'      => 'označeno $1 od $2 patrolirano $3',
'patrol-log-auto'      => '(automatsko)',
'patrol-log-diff'      => 'revizija $1',
'log-show-hide-patrol' => '$1 zapis patroliranja',

# Image deletion
'deletedrevision'                 => 'Obrisana stara revizija $1',
'filedeleteerror-short'           => 'Greška pri brisanju datoteke: $1',
'filedeleteerror-long'            => 'Desile su se greške pri brisanju datoteke:

$1',
'filedelete-missing'              => 'Datoteka "$1" ne može biti obrisana, jer ne postoji.',
'filedelete-old-unregistered'     => 'Određena revizija datoteke "$1" se ne nalazi u bazi podataka.',
'filedelete-current-unregistered' => 'Određena datoteka "$1" se ne nalazi u bazi podataka.',
'filedelete-archive-read-only'    => 'Arhivski folder "$1" se postavljen samo za čitanje na serveru.',

# Browsing diffs
'previousdiff' => '← Starija izmjena',
'nextdiff'     => 'Novija izmjena →',

# Media information
'mediawarning'         => "'''Upozorenje''': Ova datoteka sadrži loš kod.
Njegovim izvršavanjem možete da ugrozite Vaš sistem.",
'imagemaxsize'         => "Ograničenje veličine slike:<br />''(za stranice opisa datoteke)''",
'thumbsize'            => 'Veličina umanjenog prikaza:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|stranica|stranice|stranica}}',
'file-info'            => 'veličina datoteke: $1, MIME tip: $2',
'file-info-size'       => '$1 × $2 piksela, veličina datoteke/fajla: $3, MIME tip: $4',
'file-nohires'         => '<small>Veća rezolucija nije dostupna.</small>',
'svg-long-desc'        => 'SVG fajl, nominalno $1 × $2 piksela, veličina fajla: $3',
'show-big-image'       => 'Puna rezolucija',
'file-info-gif-looped' => 'stalno iznova',
'file-info-gif-frames' => '$1 {{PLURAL:$1|sličica|sličice|sličica}}',
'file-info-png-looped' => 'stalno iznova',
'file-info-png-repeat' => 'pregledano $1 {{PLURAL:$1|put|puta}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|sličica|sličice|sličica}}',

# Special:NewFiles
'newimages'             => 'Galerija novih slika',
'imagelisttext'         => "Ispod je spisak od '''$1''' {{PLURAL:$1|datoteke|datoteke|datoteka}} poredanih $2.",
'newimages-summary'     => 'Ova posebna stranica prikazuje posljednje postavljene datoteke.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Ime datoteke (ili dio imena):',
'showhidebots'          => '($1 botove)',
'noimages'              => 'Ništa za prikazati.',
'ilsubmit'              => 'Traži',
'bydate'                => 'po datumu',
'sp-newimages-showfrom' => 'Prikaz novih datoteka počev od $2, $1',

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
'limitall'         => 'sve',

# E-mail address confirmation
'confirmemail'              => 'Potvrdite adresu e-pošte',
'confirmemail_noemail'      => 'Niste unijeli tačnu e-mail adresu u Vaše [[Special:Preferences|korisničke postavke]].',
'confirmemail_text'         => 'Ova viki zahtjeva da potvrdite adresu Vaše e-pošte prije nego što koristite mogućnosti e-pošte. ž
Aktivirajte dugme ispod kako bi ste poslali poštu za potvrdu na Vašu adresu.
Pošta uključuje poveznicu koja sadrži kod;
učitajte poveznicu u Vaš brauzer da bi ste potvrdili da je adresa Vaše e-pošte valjana.',
'confirmemail_pending'      => 'Kod za potvrdu Vam je već poslan putem e-maila;
ako ste nedavno otvorili Vaš račun, trebali bi pričekati par minuta da poslana pošta stigne, prije nego što ponovno zahtijevate novi kod.',
'confirmemail_send'         => 'Pošaljite kod za potvrdu',
'confirmemail_sent'         => 'E-pošta za potvrđivanje poslata.',
'confirmemail_oncreate'     => 'Kod za potvrđivanje Vam je poslat na Vašu e-mail adresu.
Taj kod nije neophodan za prijavljivanje, ali Vam je potreban kako bi ste omogućili funkcije wikija zasnovane na e-mailu.',
'confirmemail_sendfailed'   => '{{SITENAME}} Vam ne može poslati poštu za potvrđivanje.
Provjerite adresu zbog nepravilnih karaktera.

Povratna pošta: $1',
'confirmemail_invalid'      => 'Netačan kod za potvrdu.
Moguće je da je kod istekao.',
'confirmemail_needlogin'    => 'Morate $1 da bi ste potvrdili Vašu e-mail adresu.',
'confirmemail_success'      => 'Adresa vaše e-pošte je potvrđena.
Možete sad da se [[Special:UserLogin|prijavite]] i uživate u viki.',
'confirmemail_loggedin'     => 'Adresa Vaše e-pošte je potvrđena.',
'confirmemail_error'        => 'Nešto je pošlo krivo prilikom snimanja vaše potvrde.',
'confirmemail_subject'      => '{{SITENAME}} adresa e-pošte za potvrdu',
'confirmemail_body'         => 'Neko, vjerovatno Vi, je sa IP adrese $1 registrovao nalog "$2" sa ovom adresom e-pošte na {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada vama i da aktivirate mogućnost e-pošte na {{SITENAME}}, otvorite ovaj link u vašem pregledniku:

$3

Ako ovo niste vi, pratite ovaj link da prekinete prijavu:
$5

Ovaj kod za potvrdu će isteći u $4.',
'confirmemail_body_changed' => 'Neko, vjerovatno Vi, je sa IP adrese $1
je promijenio adresu e-pošte računa "$2" na ovu adresu za {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada Vama i da reaktivirate mogućnosti e-pošte na {{SITENAME}}, otvorite ovaj link u Vašem pregledniku:

$3

Ako ovaj račun *ne* pripada Vama, pratite ovaj link da prekinete odobravanje adrese e-pošte:

$5

Ovaj kod za potvrdu će isteći u $4.',
'confirmemail_body_set'     => 'Netko, vjerovatno Vi, sa IP adrese $1,
je postavio e-mail adresu za račun "$2" na ovoj adresi za {{SITENAME}}.

Da potvrdite kako ovaj račun uistinu pripada Vama i reaktivirate
e-mail postavke na {{SITENAME}}, otvoriti ovaj link u vašem pregledniku:

$3

Ako račun *ne* pripada Vama, pratite ovaj link
kako bi poništili potvrdu e-mail adrese:

$5

Ovaj kod za potvrdu će isteći u $4.',
'confirmemail_invalidated'  => 'Potvrda e-mail adrese otkazana',
'invalidateemail'           => 'Odustani od e-mail potvrde',

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
'table_pager_limit_label'  => 'Stavke po stranici:',
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
Da bi ste uklonili naslov, označite kutiju pored naslova, i kliknite "{{int:Watchlistedit-normal-submit}}".
Također možete [[Special:EditWatchlist/raw|napredno urediti spisak]].',
'watchlistedit-normal-submit'  => 'Ukloni naslove',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 naslov|$1 naslova}} je uklonjeno iz Vašeg spiska praćenja:',
'watchlistedit-raw-title'      => 'Napredno uređivanje spiska praćenja',
'watchlistedit-raw-legend'     => 'Napredno uređivanje spiska praćenja',
'watchlistedit-raw-explain'    => 'Naslovi u Vašem spisku praćenja su prikazani ispod, i mogu biti uređeni dodavanjem ili brisanjem sa spiska; jedan naslov u svakom redu.
Kada završite, kliknite "{{int:Watchlistedit-raw-submit}}".
Također možete [[Special:EditWatchlist|koristiti standardni uređivač]].',
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
'version-antispam'                 => 'Sprečavanje spama',
'version-skins'                    => 'Izgledi (skinovi)',
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
'version-poweredby-credits'        => "Ova wiki je zasnovana na '''[http://www.mediawiki.org/ MediaWiki]''', autorska prava zadržana © 2001-$1 $2.",
'version-poweredby-others'         => 'ostali',
'version-license-info'             => 'Mediawiki je slobodni softver, možete ga redistribuirati i/ili mijenjati pod uslovima GNU opće javne licence kao što je objavljeno od strane Fondacije Slobodnog Softvera, bilo u verziji 2 licence, ili (po vašoj volji) nekoj od kasniji verzija.

Mediawiki se distriburia u nadi da će biti korisna, ali BEZ IKAKVIH GARANCIJA, čak i bez ikakvih posrednih garancija o KOMERCIJALNOSTI ili DOSTUPNOSTI ZA ODREĐENU SVRHU. Pogledajte GNU opću javnu licencu za više detalja.

Trebali biste dobiti [{{SERVER}}{{SCRIPTPATH}}/KOPIJU GNU opće javne licence] zajedno s ovim programom, ako niste, pišite Fondaciji Slobodnog Softvera na adresu  Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ili je pročitajte [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html online].',
'version-software'                 => 'Instalirani softver',
'version-software-product'         => 'Proizvod',
'version-software-version'         => 'Verzija',

# Special:FilePath
'filepath'         => 'Putanja datoteke',
'filepath-page'    => 'Datoteka:',
'filepath-submit'  => 'Idi',
'filepath-summary' => 'Ova posebna stranica prikazuje potpunu putanju za datoteku.
Slike su prikazane u punoj veličini, ostale vrste datoteka su prikazane direktno sa, s njima povezanim, programom.

Unesite ime datoteke bez "{{ns:file}}:" prefiksa.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Potraga za duplim datotekama',
'fileduplicatesearch-summary'   => 'Pretraga duplih datoteka na bazi njihove haš vrijednosti.',
'fileduplicatesearch-legend'    => 'Pretraga dvojnika',
'fileduplicatesearch-filename'  => 'Ime datoteke:',
'fileduplicatesearch-submit'    => 'Traži',
'fileduplicatesearch-info'      => '$1 × $2 piksel<br />Veličina datoteke: $3<br />MIME vrsta: $4',
'fileduplicatesearch-result-1'  => 'Datoteka "$1" nema identičnih dvojnika.',
'fileduplicatesearch-result-n'  => 'Datoteka "$1" ima {{PLURAL:$2|1 identičnog|$2 identična|$2 identičnih}} dvojnika.',
'fileduplicatesearch-noresults' => 'Nije pronađena datoteka sa imenom "$1".',

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
'tags-tag'                => 'Naziv oznake',
'tags-display-header'     => 'Vidljivost na spisku izmjena',
'tags-description-header' => 'Puni opis značenja',
'tags-hitcount-header'    => 'Označene izmjene',
'tags-edit'               => 'uređivanje',
'tags-hitcount'           => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',

# Special:ComparePages
'comparepages'     => 'Usporedi stranice',
'compare-selector' => 'Usporedi revizije stranica',
'compare-page1'    => 'Stranica 1',
'compare-page2'    => 'Stranica 2',
'compare-rev1'     => 'Ispravljanje 1',
'compare-rev2'     => 'Ispravljanje 2',
'compare-submit'   => 'Usporedi',

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
'htmlform-float-invalid'       => 'Vrijednost koju ste unijeli nije broj.',
'htmlform-int-toolow'          => 'Vrijednost koju ste unijeli je ispod minimuma od $1',
'htmlform-int-toohigh'         => 'Vrijednost koju ste unijeli je iznad maksimuma od $1',
'htmlform-required'            => 'Ova vrijednost je obavezna',
'htmlform-submit'              => 'Unesi',
'htmlform-reset'               => 'Vrati izmjene',
'htmlform-selectorother-other' => 'Ostalo',

# SQLite database support
'sqlite-has-fts' => '$1 sa podrškom pretrage cijelog teksta',
'sqlite-no-fts'  => '$1 bez podrške pretrage cijelog teksta',

# Special:DisableAccount
'disableaccount'             => 'Onemogućavanje korisničkog računa',
'disableaccount-user'        => 'Korisničko ime:',
'disableaccount-reason'      => 'Razlog:',
'disableaccount-confirm'     => "Onemogući ovaj korisnički račun.
Korisnik se neće moći prijaviti, staviti novu šifru ili primati obavještenja putem e-maila.
Ako je korisnik negdje u međuvremenu prijavljen, bit će automatski odmah odjavljen.
''Zapamtite da se onemogućavanje računa ne može poništiti bez intervencije sistemskog administratora.''",
'disableaccount-mustconfirm' => 'Morate potvrditi da želite onemogućiti ovaj račun.',
'disableaccount-nosuchuser'  => 'Korisnički račun "$1" ne postoji.',
'disableaccount-success'     => 'Korisnički račun "$1" je trajno onemogućen.',
'disableaccount-logentry'    => 'trajno onesposobljen korisnički račun [[$1]]',

);
