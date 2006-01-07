<?php
/** Croatian (hrvatski)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** */
require_once( 'LanguageUtf8.php' );

# See Language.php for notes.

if($wgMetaNamespace === FALSE)
	$wgMetaNamespace = str_replace( ' ', '_', $wgSitename );

/* private */ $wgNamespaceNamesHr = array(
 NS_MEDIA           => "Mediji",
 NS_SPECIAL         => "Posebno",
 NS_MAIN            => "",
 NS_TALK            => "Razgovor",
 NS_USER            => "Suradnik",
 NS_USER_TALK       => "Razgovor_sa_suradnikom",
 NS_PROJECT         => $wgMetaNamespace,
 NS_PROJECT_TALK    => "Razgovor_" . $wgMetaNamespace,
 NS_IMAGE           => "Slika",
 NS_IMAGE_TALK      => "Razgovor_o_slici",
 NS_MEDIAWIKI       => "MediaWiki",
 NS_MEDIAWIKI_TALK  => "MediaWiki_razgovor",
 NS_TEMPLATE        => "Predložak",
 NS_TEMPLATE_TALK   => "Razgovor_o_predlošku",
 NS_HELP            => "Pomoć",
 NS_HELP_TALK       => "Razgovor_o_pomoći",
 NS_CATEGORY        => "Kategorija",
 NS_CATEGORY_TALK   => "Razgovor_o_kategoriji"
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsHr = array(
 "Bez", "Lijevo nepomično", "Desno nepomično", "Lijevo leteće"
);

/* private */ $wgSkinNamesHr = array(
 'standard'  => "Standardna",
 'nostalgia'  => "Nostalgija",
 'cologneblue'  => "Kölnska plava",
 'smarty'  => "Paddington",
 'montparnasse'  => "Montparnasse",
 'davinci'  => "DaVinci",
 'mono'   => "Mono",
 'monobook'  => "MonoBook",
 "myskin"  => "MySkin",
 "chick"  => "Chick"
);

/* private */ $wgAllMessagesHr = array(

'Monobook.css' =>
'/** Ovdje idu izmjene monobook stylesheeta */ ',

# User preference toggles
"tog-underline"            => "Podcrtane poveznice",
"tog-highlightbroken"      => "Istakni prazne poveznice drugom bojom (inače, upitnikom na kraju).",
"tog-justify"              => "Poravnaj odlomke i zdesna",
"tog-hideminor"            => "Sakrij manje izmjene na stranici \"Nedavne promjene\"",
"tog-usenewrc"             => "Poboljšan izgled Nedavnih promjena (nije za sve preglednike)",
"tog-numberheadings"       => "Automatski označi naslove brojevima",
"tog-showtoolbar"          => "Prikaži traku s alatima za uređivanje",
"tog-editondblclick"       => "Dvoklik otvara uređivanje stranice (JavaScript)",
"tog-editsection"             => "Prikaži poveznice za uređivanje pojedinih odlomaka",
"tog-editsectiononrightclick" => "Pritiskom na desnu tipku miša otvori uređivanje pojedinih odlomaka (JavaScript) ",
"tog-showtoc"                 => "U člancima s više od tri odlomka prikaži tablicu sadržaja.",
"tog-rememberpassword"     => "Zapamti lozinku između prijava",
"tog-editwidth"            => "Okvir za uređivanje zauzima cijelu širinu",
"tog-watchdefault"         => "Postavi sve nove i izmijenjene stranice u popis praćenja",
"tog-minordefault"         => "Normalno označavaj sve moje izmjene kao manje",
"tog-previewontop"         => "Prikaži kako će stranica izgledati iznad okvira za uređivanje",
"tog-previewonfirst"       => "Prikaži kako će stranica izgledati čim otvorim uređivanje",
"tog-nocache"                 => "Isključi međuspremnik (cache) stranica.",
"tog-enotifwatchlistpages" => "Pošalji mi e-mail kod izmjene stranice u popisu praćenja",
"tog-enotifusertalkpages"  => "Pošalji mi e-mail kod izmjene moje stranice za razgovor",
"tog-enotifminoredits"     => "Pošalji mi e-mail i kod manjih izmjena",
"tog-enotifrevealaddr"     => "Prikaži moju e-mail adresu u obavijestima o izmjeni",
"tog-shownumberswatching"  => "Prikaži broj suradnika koji prate stranicu (u nedavnim izmjenama, popisu praćenja i samim člancima)",
"tog-fancysig"             => "Običan potpis (bez automatske poveznice)",
'tog-externaleditor' => 'Uvijek koristi vanjski editor',
'tog-externaldiff' => 'Uvijek koristi vanjski program za usporedbu',

'underline-always' => 'Uvijek',
'underline-never' => 'Nikad',
'underline-default' => 'Prema postavkama preglednika',

'skinpreview' => '(Pregled)',

# Dates
'sunday' => 'nedjelja',
'monday' => 'ponedjeljak',
'tuesday' => 'utorak',
'wednesday' => 'srijeda',
'thursday' => 'četvrtak',
'friday' => 'petak',
'saturday' => 'subota',
'january' => 'siječnja',
'february' => 'veljače',
'march' => 'ožujka',
'april' => 'travnja',
'may_long' => 'svibnja',
'june' => 'lipnja',
'july' => 'srpnja',
'august' => 'kolovoza',
'september' => 'rujna',
'october' => 'listopada',
'november' => 'studenog',
'december' => 'prosinca',
'jan' => 'sij',
'feb' => 'velj',
'mar' => 'ožu',
'apr' => 'tra',
'may' => 'svi',
'jun' => 'lip',
'jul' => 'srp',
'aug' => 'kol',
'sep' => 'ruj',
'oct' => 'lis',
'nov' => 'stu',
'dec' => 'pro',

# Bits of text used by many pages:
#
"categories"            => "Kategorije stranica",
"category"              => "Kategorija",
"category_header"       => "Članci u kategoriji \"$1\"",
"subcategories"         => "Potkategorije",
"linktrail"             => "/^([č|š|ž|ć|đ|ß|a-z]+)(.*)\$/sD",
"mainpage"              => "Glavna stranica",
"mainpagetext"          => "Softver Wiki je uspješno instaliran.",
"mainpagedocfooter" => "Pogledajte [http://meta.wikimedia.org/wiki/MediaWiki_i18n dokumentaciju o prilagodbi sučelja]
i [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Vodič za suradnike] za pomoć pri uporabi i podešavanju.",
"portal"		=> "Portal zajednice",
"portalurl"		=> "{{ns:4}}:Portal zajednice",
"about"			=> "O",
"aboutsite"             => "O projektu {{SITENAME}}",
"aboutpage"		=> "Project:O_projektu_{{SITENAME}}",
"article" => "Članak",
"help"			=> "Pomoć",
"helppage"		=> "Project:Pomoć",
"bugreports"	        => "Poruke o programskim greškama",
"bugreportspage"        => "Project:Poruke_o_programskim_greškama",
"sitesupport"   => "Novčani prilozi",
'sitesupport-url' => 'Project:Site support',
"faq"			=> "Najčešća pitanja",
"faqpage"		=> "{{ns:project}}:FAQ",
"edithelp"		=> "Kako uređivati stranicu",
'newwindow'		=> '(otvara se u novom prozoru)',
"edithelppage"	        => "{{ns:project}}:Kako_uređivati_stranicu",
"cancel"		=> "Odustani",
"qbfind"		=> "Nađi",
"qbbrowse"		=> "Pregledaj",
"qbedit"		=> "Uredi",
"qbpageoptions"         => "Postavke stranice",
"qbpageinfo"	        => "O stranici",
"qbmyoptions"	        => "Moje stranice",
"qbspecialpages"	=> "Posebne stranice",
"moredotdotdot"	=> "Više...",
"mypage"		=> "Moja stranica",
"mytalk"		=> "Moj razgovor",
"anontalk"		=> "Razgovor za ovu IP adresu",
"navigation" => "Orijentacija",
"currentevents"         => "Aktualno",
"disclaimers" => "Uvjeti korištenja | Pravne napomene | Odricanje od odgovornosti",
"disclaimerpage"		=> "{{ns:4}}:General_disclaimer",
"errorpagetitle"        => "Greška",
"returnto"		=> "Vrati se na $1.",
"tagline"      	        => "Izvor: {{SITENAME}}",
"whatlinkshere"	        => "Što vodi ovamo",
"help"			=> "Pomoć",
"search"		=> "Traži",
'go'		=> 'Kreni',
"history"		        => "Stare izmjene",
"history_short"		=> "Stare izmjene",
'updatedmarker' => 'obnovljeno od zadnjeg posjeta',
'info_short'	=> 'Informacija',
"printableversion"      => "Verzija za ispis",
'permalink'     => 'Trajna poveznica',
'print' => 'Ispiši',
'edit' => 'Uredi',
"editthispage"	        => "Uredi ovu stranicu",
"delete"                => "Izbriši",
"deletethispage"        => "Izbriši ovu stranicu",
"undelete_short1"       => "Vrati jedno uređivanje",
"undelete_short"        => "Vrati $1 uređivanja",
"protect" => "Zaštiti",
"protectthispage"       => "Zaštiti ovu stranicu",
"unprotect" => "Ukloni zaštitu",
"unprotectthispage"     => "Ukloni zaštitu s ove stranice",
"newpage"               => "Nova stranica",
"talkpage"		=> "Razgovor o ovoj stranici",
"specialpage" => "Posebna stranica",
"personaltools" => "Osobni alati",
"postcomment" => "Napiši komentar",
"addsection"   => "+",
"articlepage"	        => "Vidi članak",
'subjectpage'	=> 'Vidi predmet', # For compatibility
'talk' => 'Razgovor',
'views' => 'Pogledi',
"toolbox" => "Traka s alatima",
"wikipediapage" => "Vidi stranicu o projektu",
"userpage"              => "Vidi suradnikovu stranicu",
"imagepage"             => "Vidi stranicu slike",
"viewtalkpage"          => "Vidi razgovor",
"otherlanguages"        => "Drugi jezici",
"redirectedfrom"        => "(Preusmjereno s $1)",
"lastmodified"	        => "Datum zadnje promjene na ovoj stranici: $1",
"viewcount"		=> "Ova stranica je pogledana $1 puta.",
"copyright"	=> "Sadržaji se koriste u skladu s $1.",
"poweredby"	=> "{{SITENAME}} koristi [http://www.mediawiki.org/ MediaWiki], program za wiki sustave s otvorenim izvornim kodom.",
"printsubtitle"         => "(Izvor: {{SERVER}})",
"protectedpage"         => "Zaštićena stranica",
"administrators"        => "Project:Administratori",

"sysoptitle"	        => "Nužne administrativne ovlasti",
"sysoptext"		=> "Željenu radnju mogu obaviti samo
suradnici sa statusom \"administrator\".
Vidi i $1.",
"developertitle" => "Nužne programerske ovlasti",
"developertext"	=> "Željenu radnju mogu obaviti samo suradnici
sa statusom \"programer\".
Vidi $1.",

'badaccess' => 'Greška u ovlaštenjima',
'badaccesstext' => 'Radnju koju ste započeli
može obaviti samo korisnik s ovlaštenjem "$2".
Pogledajte $1.',

'versionrequired' => 'Potrebna inačica $1 MediaWikija',
'versionrequiredtext' => 'Za korištenje ove stranice potrebna je inačica $1 MediaWiki softvera. Pogledaj [[Special:Version]]',

"nbytes"		=> "$1 bajtova",
"ok"			=> "U redu",
"sitetitle"		=> "{{SITENAME}}",
"sitesubtitle"	        => "",
"pagetitle"		=> "$1 - {{SITENAME}}",
"retrievedfrom"         => "Dobavljeno iz \"$1\"",
"newmessages"           => "Imaš $1.",
"newmessageslink"       => "novih poruka",
"editsection"		=>"uredi",
"toc" => "Sadržaj",
"showtoc" => "prikaži",
"hidetoc" => "sakrij",
"thisisdeleted" => "Vidi ili vrati $1?",
'viewdeleted' => 'Vidi $1?',
'restorelink1' => 'jedna pobrisana izmjena',
"restorelink" => "$1 pobrisanih izmjena",
"feedlinks" => "Feed:",
'sitenotice'	=> '-', # the equivalent to wgSiteNotice

# Kurzworte für jeden Namespace, u.a. von MonoBook verwendet
'nstab-main' => 'Članak',
'nstab-user' => 'Stranica suradnika',
'nstab-media' => 'Mediji',
'nstab-special' => 'Posebno',
'nstab-image' => 'Slika',
'nstab-help' => 'Pomoć',
'nstab-category' => 'Kategorija',
'nstab-wp' => 'Stranica o projektu',
'nstab-mediawiki' => 'Poruka',
'nstab-template' => 'Predložak',

# Editier-Werkzeugleiste
"bold_sample"=>"Podebljani tekst",
"bold_tip"=>"Podebljani tekst",
"italic_sample"=>"Kurzivni tekst",
"italic_tip"=>"Kurzivni tekst",
"link_sample"=>"Tekst poveznice",
"link_tip"=>"Unutarnja poveznica",
"extlink_sample"=>"http://www.primjer.hr Tekst poveznice",
"extlink_tip"=>"Vanjska poveznica (pazi, nužan je prefiks http://)",
"headline_sample"=>"Tekst naslova",
"headline_tip"=>"Podnaslov",
"math_sample"=>"Ovdje unesi formulu",
"math_tip"=>"Matematička formula (LaTeX)",
"nowiki_sample"=>"Ovdje unesite neoblikovani tekst",
"nowiki_tip"=>"Neoblikovani tekst",
"image_sample"=>"Primjer.jpg",
"image_tip"=>"Uložena slika",
"media_sample"=>"Primjer.ogg",
"media_tip"=>"Uloženi medij",
"sig_tip"=>"Vaš potpis s datumom",
"hr_tip"=>"Vodoravna crta (koristiti rijetko)",

# Main script and global functions
#
"nosuchaction"                => "Nema takve naredbe",
"nosuchactiontext"      => "Navedeni URL označava
nepostojeću naredbu",
"nosuchspecialpage"     => "Posebna stranica ne postoji",
"nospecialpagetext"     => "Takva posebna stranica ne postoji.",

# General errors
#
"error"                               => "Greška",
"databaseerror"         => "Greška baze podataka",
"internalerror"         => "Greška sustava",
"filecopyerror"         => "Ne mogu kopirati datoteku \"$1\" u \"$2\".",
"filerenameerror"       => "Ne mogu preimenovati datoteku \"$1\" u \"$2\".",
"filedeleteerror"       => "Ne mogu obrisati datoteku \"$1\".",
"filenotfound"                => "Datoteka \"$1\" nije nađena.",
"unexpected"          => "Neočekivana vrijednost: \"$1\"=\"$2\".",
"formerror"                   => "Greška: Ne mogu poslati podatke",
"badarticleerror"       => "Nemoguće naći stranicu tog imena.",
"cannotdelete"                => "Ne mogu obrisati navedenu stranicu ili sliku. (Moguće da je već obrisana.)",
"badtitle"                    => "Loš naslov",
"badtitletext"                => "Navedeni naslov stranice nepravilan ili loše formirana interwiki poveznica.",
"perfdisabled"          => "Privremeno onemogućeno. Koristite kopiju snimljenu $1:",
"dberrortext"	        => "Došlo je do sintaksne pogreške s upitom bazi.
Možda se radi o bugu u softveru.
Posljednji pokušaj upita je glasio:
<blockquote><tt>$1</tt></blockquote>
iz funkcije \"<tt>$2</tt>\".
MySQL je vratio pogrešku \"<tt>$3: $4</tt>\".",
"dberrortextcl"	        => "Došlo je do sintaksne pogreške s upitom bazi.
Možda se radi o bugu u softveru.
Posljednji pokušaj upita je glasio:
\"$1\"
iz funkcije \"<tt>$2</tt>\".
MySQL je vratio pogrešku \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Oprostite! Wiki trenutno ima tehničkih problema i ne može se povezati s bazom podataka. $1",
"nodb"			=> "Nije bilo moguće odabrati bazu podataka $1",
'laggedslavemode' => 'Upozorenje: na stranici se možda ne nalaze najnovije promjene.',
"cachederror" => "Ova je verzija stranice iz međuspremnika i možda ne sadrži sve promjene.",
"readonly"		=> "Baza podataka je zaključana",
"enterlockreason" => "Upiši razlog zaključavanja i procjenu vremena otključavanja",
"readonlytext"	=> "Baza podataka je trenutno zaključana i nije moguće mijenjati je,
vjerojatno zbog redovnog održavanja. Nakon što ono završi baza će se vratiti u normalno stanje.",
"missingarticle" => "U bazi podataka nije pronađena stranica \"$1\" koja je trebala biti pronađena.

Ovo se najčešće događa zbog poveznice na zastarjelu usporedbu ili staru promjenu stranice koja je u međuvremenu izbrisana.

Ako to nije slučaj, možda se radi o softverskoj grešci. Molimo da u tom slučaju pošaljete poruku administratoru navodeći URL.",
'readonly_lag' => 'Baza podataka je automatski zaključana dok se sekundarni bazni poslužitelji ne usklade s glavnim',
"internalerror"         => "Greška sustava",
"filecopyerror"         => "Ne mogu kopirati datoteku \"$1\" u \"$2\".",
"filerenameerror"       => "Ne mogu preimenovati datoteku \"$1\" u \"$2\".",
"filedeleteerror"       => "Ne mogu obrisati datoteku \"$1\".",
"filenotfound"	        => "Datoteka \"$1\" nije nađena.",
"unexpected"	        => "Neočekivana vrijednost: \"$1\"=\"$2\".",
"formerror"		=> "Greška: Ne mogu poslati podatke",
"badarticleerror"       => "Ovu radnju nije moguće izvesti s tom stranicom.",
"cannotdelete"	        => "Ne mogu obrisati navedenu stranicu ili sliku. (Moguće da je već obrisana.)",
"badtitle"		=> "Loš naslov",
"badtitletext"	        => "Navedeni naslov stranice nepravilan ili loše formirana interwiki poveznica.",
"perfdisabled"          => "Privremeno onemogućeno. Koristite kopiju snimljenu $1:",
"perfdisabledsub" => "Ovo je snimljena kopija $1:",
"perfcached" => "Sljedeći podaci su iz međuspremnika i možda nisu najsvježiji:",
"wrong_wfQuery_params" => "Neispravni parametri poslani u wfQuery()<br />
Funkcija: $1<br />
Upit: $2",
"viewsource" => "Vidi izvornik",
"protectedtext" => "Ova je stranica zaključana čime je onemogućeno njeno uređivanje; za ovo postoji nekoliko mogućih razloga, molim pogledajte članak [[{{ns:4}}:Protected page|Zaštićena stranica]].

Možete pogledati izvorni kod ove stranice i kopirati ga:",
'sqlhidden' => '(SQL upit sakriven)',

# Login and logout pages
#
"logouttitle"	        => "Odjava suradnika",
"logouttext"	        => "Odjavili ste se.<br />
Možete nastaviti s korištenjem projekta {{SITENAME}} anonimno,
ili se možete ponovo prijaviti pod istim ili drugim imenom. Neke se stranice mogu
prikazivati kao da ste još uvijek prijavljeni, sve dok ne očistite međuspremnik svog preglednika.",
"welcomecreation"       => "== Dobrodošli, $1! ==

Vaš je suradnički račun otvoren. Ne zaboravite podesiti korisničke postavke.",

"loginpagetitle"        => "Prijava suradnika",
"yourname"		=> "Suradničko ime",
"yourrealname"		=> "Pravo ime<sup>1</sup>",
"yourlanguage"		=> "Jezik",
'yourvariant'  => 'Varijanta',
"yourpassword"	        => "Upišite lozinku",
"yourpasswordagain"     => "Ponovno upišite lozinku",
"newusersonly"	        => " (samo novi suradnici)",
"remembermypassword"    => "Trajno zapamti moju lozinku.",
'yourdomainname'       => 'Vaša domena',
'externaldberror'      => 'Došlo je do greške s vanjskom autorizacijom ili vam nije dozvoljeno osvježavanje vanjskog suradničkog računa.',
"loginproblem"	        => "<b>Došlo je do greške s vašom prijavom.</b><br />Pokušajte iznova!",
"alreadyloggedin"       => "<strong>Suradniče $1, već ste prijavljeni!</strong><br />",
"login"			=> "Prijavi se",
"loginprompt"           => "Za prijavu na sustav {{SITENAME}} morate u pregledniku uključiti kolačiće (cookies).",
"userlogin"		=> "Prijavi se",
"logout"		=> "Odjavi se",
"userlogout"	        => "Odjavi se",
"notloggedin" => "Niste prijavljeni",
"createaccount"	        => "Otvori novi suradnički račun",
'createaccountmail'	=> 'poštom',
"badretype"		=> "Unesene lozinke nisu istovjetne.",
"userexists"	        => "Uneseno suradničko ime već je u upotrebi. Unesite neko drugo ime.",
"youremail"		=> "Vaša elektronska pošta",
"yournick"		=> "Vaš nadimak (za potpisivanje)",
'email'			=> 'E-mail',
"yourrealname"		=> "Pravo ime (nije obvezno)*",
"emailforlost"	        => "* Polja označena zvjezdicom ili superskriptom nisu obvezna.
Unošenje e-mail adrese omogućava drugima da vam se jave kroz wiki bez da znaju vašu adresu,
a ako zaboravite lozinku, možemo vam na ovu adresu poslati novu.",
'prefs-help-email' 	=> '* E-mail (nije obvezno): Omogućuje drugima da vas kontaktiraju na suradničkoj stranici ili stranici za razgovor bez javnog otkrivanja vaše e-mail adrese.
Također, ako zaboravite lozinku možemo vam na ovu adresu poslati novu, privremenu.',
'prefs-help-email-enotif' => 'Ova će se adresa koristiti i za slanje izvješća o promjenama u wikiju, ako ih uključite.',
'prefs-help-realname' 	=> '* Pravo ime (nije obvezno): za pravnu atribuciju vaših doprinosa.',
"loginerror"	        => "Greška u prijavi",
'nocookiesnew' => 'Suradnički račun je otvoren, ali niste uspješno prijavljeni. Naime, {{SITENAME}} koristi kolačiće (\'\'cookies\'\') u procesu prijave. Isključili ste kolačiće. Molim uključite ih i pokušajte ponovo s vašim novim imenom i lozinkom.',
'nocookieslogin' => '{{SITELOGIN}} koristi kolačiće (\'\'cookies\'\') u procesu prijave. Isključili ste kolačiće. Molim uključite ih i pokušajte ponovo.',
"noname"		=> "Niste unijeli valjano suradničko ime.",
"loginsuccesstitle"     => "Prijava uspješna",
"loginsuccess"	        => "Prijavili ste se na wiki kao \"$1\".",
"nosuchuser"	        => "Ne postoji suradnik s imenom \"$1\". Provjerite jeste li točno utipkali, ili otvorite novi suradnički račun koristeći donji obrazac.",
"wrongpassword"	        => "Lozinka koju ste unijeli nije ispravna ili nedostaje. Pokušajte opet.",
"mailmypassword"        => "Pošalji mi novu lozinku",
"passwordremindertitle" => "{{SITENAME}}: nova lozinka.",
"passwordremindertext"  => "Netko je (vjerojatno vi, s IP adrese $1)
zatražio da pošaljemo novu lozinku za sustav {{SITENAME}}.
Lozinka za suradnika \"$2\" je postavljena na \"$3\".
Molimo vas da se odmah prijavite i promijenite lozinku.",
"noemail"		=> "Suradnik \"$1\" nema zapisanu e-mail adresu.",
"passwordsent"	        => "Nova je lozinka poslana na e-mail adresu suradnika \"$1\"",
'eauthentsent'             =>  "Na navedenu adresu poslan je e-mail s potvrdom. Prije nego što pošaljemo daljnje poruke,
molimo vas da otvorite e-mail i slijedite u njemu sadržana uputstva.",
'loginend'		            => '&nbsp;',
'mailerror'                 => "Greška pri slanju e-maila: $1",
'acct_creation_throttle_hit' => 'Nažalost, ne možete otvoriti nove suradničke račune. Već ste otvorili $1.',
'emailauthenticated'        => 'Vaša e-mail adresa je ovjerena $1.',
'emailnotauthenticated'     => 'Vaša e-mail adresa <strong>još nije ovjerena</strong>.
Ne možemo poslati e-mail ni u jednoj od sljedećih naredbi.',
'noemailprefs'              => '<strong>Nije navedena e-mail adresa</strong>, stoga sljedeće naredbe neće raditi.',
'emailconfirmlink' => 'Potvrdite svoju e-mail adresu',
'invalidemailaddress'	=> 'Ne mogu prihvatiti e-mail adresu jer nije valjano oblikovana.
Molim unesite ispravno oblikovanu adresu ili ostavite polje praznim.',

# Edit pages
#
"summary"		=> "Sažetak",
"subject"       => "Predmet",
"minoredit"		=> "Ovo je manja promjena",
"watchthis"		=> "Prati ovaj članak",
"savearticle"	        => "Sačuvaj stranicu",
"preview"		=> "Pregled kako će stranica izgledati",
"showpreview"	        => "Prikaži kako će izgledati",
'showdiff'	=> 'Prikaži promjene',
"blockedtitle"	        => "Suradnik je blokiran",
"blockedtext"	        => "Vaše suradničko ime ili IP adresu blokirao je administrator $1.
Razlog je:<br />''$2''

Ako želite raspraviti blokiranje
javite se administratoru $1 ili nekom drugom [[Project:Administrators|administratoru]].

Ne možete se koristiti naredbom \"piši suradniku\" ako niste
registrirali valjanu e-mail adresu u svojim [[Special:Preferences|postavkama]].

Vaša IP adresa je $3. Molimo vas da je spomenete u porukama o ovom predmetu.",
'whitelistedittitle' => 'Za uređivanje stranice morate se prijaviti',
'whitelistedittext' => 'Za uređivanje stranice morate se [[Special:Userlogin|prijaviti]].',
'whitelistreadtitle' => 'Za čitanje stranice morate se prijaviti',
'whitelistreadtext' => 'Za čitanje stranice morate se [[Special:Userlogin|prijaviti]].',
'whitelistacctitle' => 'Ne možete otvoriti suradnički račun',
'whitelistacctext' => 'Da biste otvarali suradničke račune na ovom wikiju morate se [[Special:Userlogin|prijaviti]] i posjedovati odgovarajuća ovlaštenja.',
'loginreqtitle'	=> 'Nužna prijava',
'loginreqlink' => 'prijava',
'loginreqpagetext'	=> 'Morate se $1 da biste vidjeli ostale stranice.',
'accmailtitle' => 'Lozinka poslana.',
'accmailtext' => "Lozinka za suradnika '$1' poslana je na adresu $2.",
"newarticle"	        => "(Novo)",
"newarticletext" =>
"Došli ste na stranicu koja još nema sadržaja.<br />
*Ako želite unijeti sadržaj, počnite tipkati u prozor ispod ovog teksta.
*Ako vam treba pomoć, idite na [[Project:Pomoć|stranicu za pomoć]].
*Ako ste ovamo dospjeli slučajno, kliknite \"Natrag\" (Back) u svom programu.",
"anontalkpagetext"      => "----''Ovo je stranica za razgovor s anonimnim suradnikom koji nije otvorio suradnički račun ili se njime ne koristi. Zbog toga se moramo služiti brojčanom [[IP adresa|IP adresom]] kako bismo ga identificirali. Takvu adresu često koristi više ljudi. Ako ste anonimni suradnik i smatrate da su vam upućeni irelevantni komentari, molimo vas da [[Special:Userlogin|otvorite suradnički račun ili se prijavite]] te tako u budućnosti izbjegnete zamjenu s drugim anonimnim suradnicima.''",
"noarticletext" => "(Trenutno na ovoj stranici nema teksta)",
'clearyourcache' => "'''Napomena:''' Nakon snimanja trebate očistiti međuspremnik svog preglednika kako biste vidjeli promjene.
'''Mozilla / Firefox / Safari:''' držite ''Shift'' i pritisnite ''Reload'', ili pritisnite ''Ctrl-Shift-R'' (''Cmd-Shift-R'' na Apple Macu);
'''IE:''' držite ''Ctrl'' i pritisnite ''Refresh'', ili pritisnite ''Ctrl-F5''; '''Konqueror:''': samo pritisnite dugme ''Reload'' ili pritisnite ''F5''; korsnici '''Opere''' možda će morati u potpunosti isprazniti međuspremnik u ''Tools&rarr;Preferences''.",
'usercssjsyoucanpreview' => "<strong>Savjet:</strong> Koristite dugme 'Pokaži kako će izgledati' za testiranje svog CSS/JS prije snimanja.",
'usercsspreview' => "'''Ne zaboravite: samo isprobavate/pregledavate svoj suradnički CSS, i da još nije snimljen!'''",
'userjspreview' => "'''Ne zaboravite: samo isprobavate/pregledavate svoj suradnički JavaScript, i da još nije snimljen!'''",
"updated"		=> "(Ažurirano)",
"note"			=> "<strong>Napomena:</strong> ",
"previewnote"	        => "Ne zaboravite da je ovo samo pregled kako će stranica izgledati i da
stranica još nije snimljena!",
"previewconflict"       => "Ovaj pregled odražava stanje u gornjem polju za unos koje će biti sačuvano
ako pritisnete \"Sačuvaj stranicu\".",
"editing"		=> "Uređujete $1",
'editingsection'		=> "Uređujete $1 (odlomak)",
'editingcomment'		=> "Uređujete $1 (komentar)",
"editconflict"	        => "Istovremeno uređivanje: $1",
"explainconflict"       => "Netko je u međuvremenu promijenio stranicu. Gornje polje sadrži sadašnji tekst stranice.
U donjem polju prikazane su vaše promjene. Morat ćete unijeti vaše promjene u sadašnji tekst. <b>Samo</b> će tekst
u u gornjem polju biti sačuvan kad pritisnete \"Snimi stranicu\".",
"yourtext"		=> "Vaš tekst",
"storedversion"         => "Pohranjena inačica",
'nonunicodebrowser' => '<strong>UPOZORENJE: Vaš preglednik ne podržava Unicode zapis znakova, molim promijenite ga prije sljedećeg uređivanja članaka.</strong>',
"editingold"	        => "<strong>UPOZORENJE: Uređujete stariju inačicu
ove stranice. Ako je sačuvate, sve će promjene učinjene nakon ove inačice biti izgubljene.</strong>",
"yourdiff"		=> "Razlike",
"copyrightwarning"      => "Molimo uočite da se svi doprinosi projektu {{SITENAME}} smatraju objavljenima pod uvjetima GNU Free Documentation licence (vidi $1 za detalje).
Ako ne želite da se vaše pisanje nemilosrdno uređuje i slobodno raspačava, nemojte ga slati.<br />
Također nam obećavate da ste ovo sami napisali, ili da ste to prepisali iz nečeg što je u javnom vlasništvu ili pod sličnom slobodnom licencom.
<strong>NE STAVLJAJTE ZAŠTIĆENE RADOVE BEZ DOZVOLE!</strong>",
'copyrightwarning2' => 'Molimo uočite da se svi doprinosi projektu {{SITENAME}} smatraju objavljenima pod uvjetima GNU Free Documentation License (vidi $1 za detalje).
Ako ne želite da se vaše pisanje nemilosrdno uređuje, nemojte ga slati ovdje.<br />
Također nam obećavate da ste ovo sami napisali, ili da ste to prepisali iz nečeg što je u javnom vlasništvu ili pod sličnom slobodnom licencom.
<strong>NE STAVLJAJTE ZAŠTIĆENE RADOVE BEZ DOZVOLE!</strong>',
"longpagewarning" => "PAŽNJA: Ova stranica je dugačka $1 kilobajta; neki preglednici bi mogli imati problema pri uređivanju stranica koje se približavaju ili su duže od 32 kb.
Molimo razmislite o rastavljanju stranice na manje odjeljke.",
"readonlywarning" => "<strong>UPOZORENJE: Baza podataka je zaključana zbog održavanja, pa trenutno ne možete sačuvati svoje
promjene. Najbolje je da kopirate i zaljepite tekst u tekstualnu datoteku te je snimite za kasnije.</strong>",
"protectedpagewarning" => "<strong>UPOZORENJE: ova stranica je zaključana i mogu je uređivati samo suradnici s administratorskim pravima. Molimo pogledajte [[Project:Protected_page_guidelines|smjernice o zaključavanju]].</strong>",
'templatesused'	=> 'Predlošci korišteni na ovoj stranici:',

# History pages
#
"revhistory"	        => "Stare izmjene",
"nohistory"		=> "Ova stranica nema starijih izmjena.",
"revnotfound"	        => "Stara izmjena nije nađena.",
"revnotfoundtext"       => "Ne mogu pronaći staru izmjenu stranice koju ste zatražili.
Molimo provjerite URL koji vas je doveo ovamo.",
"loadhist"		=> "Učitavam stare izmjene",
"currentrev"	        => "Trenutna inačica",
"revisionasof"	        => "Inačica od $1",
'revisionasofwithlink'  => 'Inačica od $1; $2<br />$3 | $4',
'previousrevision'	=> '←Starija inačica',
'nextrevision'		=> 'Novija inačica→',
'currentrevisionlink'   => 'vidi trenutnu inačicu',
"cur"			=> "sad",
"next"			=> "sljed",
"last"			=> "pret",
"orig"			=> "izvo",
"histlegend"	        => "Uputa: (sad) = razlika od trenutne inačice,
(pret) = razlika od prethodne inačice, m = manja promjena",
'history_copyright'    => '-',
'deletedrev' => '[izbrisano]',
'histfirst' => 'Najstarije',
'histlast' => 'Najnovije',

# Diffs
#
"difference"	        => "(Usporedba među inačicama)",
"loadingrev"	        => "učitavam inačicu za usporedbu",
"lineno"		=> "Redak $1:",
"editcurrent"	        => "Uredi trenutnu inačicu ove stranice",
'selectnewerversionfordiff' => 'Izaberi noviju inačicu za usporedbu',
'selectolderversionfordiff' => 'Izaberi stariju inačicu za usporedbu',
'compareselectedversions' => 'Usporedi odabrane inačice',

# Search results
#
"searchresults"         => "Rezultati pretrage",
"searchresulttext"      => "Za više obavijesti o pretraživanju projekta {{SITENAME}} vidi [[Project:Tražilica]].",
"searchquery"	        => "Za upit \"$1\"",
"badquery"		=> "Loše oblikovan upit",
"badquerytext"	        => "Nismo mogli provesti vašu pretragu.
Razlog je vjerojatno u tome što ste pokušali tražiti riječ kraću od tri
slova, što još nije moguće.
Možda ste pogriješili pri upisu pretrage. Pokušajte ponovo.",
"matchtotals"	        => "Upitu \"$1\" odgovara $2 naslova stranica i $3 tekstova stranica.",
"nogomatch"             => "Ne postoji stranica s točno takvim naslovom, pokušava se pretraga cijelog sadržaja.",
"titlematches"	        => "Pronađene stranice prema naslovu",
"notitlematches"        => "Nema pronađenih stranica prema naslovu",
"textmatches"	        => "Pronađene stranice prema tekstu članka",
"notextmatches"	        => "Nema pronađenih stranica prema tekstu članka",
"prevn"			=> "prethodnih $1",
"nextn"			=> "sljedećih $1",
"viewprevnext"	        => "Vidi ($1) ($2) ($3).",
"showingresults"        => "Dolje je prikazano <b>$1</b> rezultata, počevši od <b>$2.</b>.",
"nonefound"		=> "<b>Napomena</b>: pretrage su neuspješne ako tražite česte riječi koje ne indeksiramo, ili u upitu navedete previše pojmova (u rezultatu se pojavlju samo stranice koje sadrže sve tražene pojmove).",
"powersearch"           => "Traženje",
"powersearchtext" => "
Traženje u prostoru :<br />
$1<br />
$2 Popis se preusmjerava   Traženje za $3 $9",
"searchdisabled" => "<p>Oprostite! Pretraga po cjelokupnoj bazi je zbog bržeg rada projekta {{SITENAME}} trenutno onomogućena. Možete se poslužiti tražilicom Google.</p>",
'googlesearch' => '
<form method="get" action="http://www.google.com/search" id="googlesearch">
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />

    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="$3" />
  <div>
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>',
"blanknamespace" => "(Glavni)",

# Preferences page
#
"preferences"	        => "Postavke",
"prefsnologin"          => "Niste prijavljeni",
"prefsnologintext"	=> "Morate biti [[Special:Userlogin|prijavljeni]]
za podešavanje korisničkih postavki.",
"prefslogintext"        => "Prijavljeni ste kao \"$1\".
Interni identifikacijski broj je $2.",
"prefsreset"	        => "Postavke su vraćene na prvotne vrijednosti.",
"qbsettings"	        => "Traka",
"changepassword"        => "Promjena lozinke",
"skin"			=> "Izgled",
"math"			=> "Prikaz matematičkih formula",
'dateformat'		=> 'Format datuma',
"math_failure"		=> "Obrada nije uspjela.",
"math_unknown_error"	=> "nepoznata greška",
"math_unknown_function"	=> "nepoznata funkcija ",
"math_lexing_error"	=> "rječnička greška (lexing error)",
"math_syntax_error"	=> "sintaksna greška",
'math_image_error' => 'Konverzija u PNG nije uspjela; provjerite jesu li dobro instalirani latex, dvips, gs, i convert',
'math_bad_tmpdir' => 'Ne mogu otvoriti ili pisati u privremeni direktorij za matematiku',
'math_bad_output' => 'Ne mogu otvoriti ili pisati u odredišni direktorij za matematiku',
'math_notexvc' => 'Nedostaje izvršna datoteka texvc-a; pogledajte math/README za postavke.',
'prefs-misc' => 'Razno',
'prefs-personal' => 'Podaci o suradniku',
'prefs-rc' => 'Nedavne promjene i kratki članci',
"saveprefs"		=> "Snimi postavke",
"resetprefs"	        => "Vrati na prvotne postavke",
"oldpassword"	        => "Stara lozinka",
"newpassword"	        => "Nova lozinka",
"retypenew"		=> "Ponovno unesite lozinku",
"textboxsize"	        => "Širina okvira za uređivanje",
"rows"			=> "Redova",
"columns"		=> "Stupaca",
"searchresultshead"     => "Prikaz rezultata pretrage",
"resultsperpage"        => "Koliko pogodaka na jednoj stranici",
"contextlines"	        => "Koliko redova teksta po pogotku",
"contextchars"	        => "Koliko znakova po retku",
"stubthreshold"         => "Prag za prikaz članaka u nastajanju (stubova)",
"recentchangescount"    => "Broj naslova u nedavnim izmjenama",
"savedprefs"	        => "Vaše postavke su sačuvane.",
'timezonelegend' => 'Vremenska zona',
"timezonetext"	        => "Unesite razliku između vašeg lokalnog vremena i vremena na poslužitelju (UTC).",
"localtime"	        => "Lokalno vrijeme",
"timezoneoffset"        => "Razlika",
'servertime'	=> 'Vrijeme na poslužitelju',
'guesstimezone' => 'Vrijeme dobiveno od preglednika',
"emailflag"		=> "Nemoj drugim suradnicima prikazati moju e-mail adresu",
"defaultns"  => "Ako ne navedem drugačije, traži u ovim prostorima:",
'default'		=> 'prvotno',
'files'			=> 'Datoteke',

# User levels special page
#

# switching pan
'groups-lookup-group' => 'Upravljaj skupnim pravima',
'groups-group-edit' => 'Postojeće skupine:',
'editgroup' => 'Uredi skupinu',
'addgroup' => 'Dodaj skupinu',

'userrights-lookup-user' => 'Upravljaj skupinama suradnika',
'userrights-user-editname' => 'Unesite suradničko ime:',
'editusergroup' => 'Uredi suradničke skupine',

# group editing
'groups-editgroup' => 'Uredi skupinu',
'groups-addgroup' => 'Dodaj skupinu',
'groups-editgroup-preamble' => 'Ako ime ili opis počinje s dvotočkom,
ostatak će se smatrati imenom sistemske poruke pa će biti lokalizirano
u MediaWiki prostoru',
'groups-editgroup-name' => 'Ime skupine:',
'groups-editgroup-description' => 'Opis skupine (najviše 255 znakova):<br />',
'savegroup'                 => 'Sačuvaj skupinu',
'groups-tableheader' => 'ID || Ime || Opis || Prava',
'groups-existing' => 'Postojeće skupine',
'groups-noname' => 'Molim unesite valjano ime skupine',
'groups-already-exists' => 'Skupina pod tim imenom već postoji',
'addgrouplogentry' => 'Dodana skupina $2',
'changegrouplogentry' => 'Promijenjena skupina $2',
'renamegrouplogentry' => 'Skupina $2 preimenovana u $3',

# user groups editing
#
'userrights-editusergroup' => 'Uredi skupine suradnika',
'saveusergroups' => 'Snimi skupine suradnika',
'userrights-groupsmember' => 'Član:',
'userrights-groupsavailable' => 'Dostupne skupine:',
'userrights-groupshelp' => 'Izaberite skupine u koje želite dodati ili iz njih ukloniti suradnika.
Neoznačene skupine neće se promijeniti. Skupinu možete deselektirati istovremenim pritiskom CTRL + lijeva tipka miša',
'userrights-logcomment' => 'Članstvo u skupini $1 zamijenjeno članstvom u skupini $2',

# Default group names and descriptions
#
'group-anon-name' => 'Anonimni',
'group-anon-desc' => 'Anonimni suradnici',
'group-loggedin-name' => 'Suradnik',
'group-loggedin-desc' => 'Prijavljeni suradnici',
'group-admin-name' => 'Administrator',
'group-admin-desc' => 'Suradnici od povjerenja koji mogu blokirati druge suradnike i brisati članke',
'group-bureaucrat-name' => 'Birokrat',
'group-bureaucrat-desc' => 'Članovi skupine birokrata mogu davati suradnicima adminstratorska prava',
'group-steward-name' => 'Upravitelj',
'group-steward-desc' => 'Upravitelji imaju potpuni pristup',

# Recent changes
#
"changes"               => "promjene",
"recentchanges"         => "Nedavne promjene",
"recentchangestext"     => "Na ovoj stranici možete pratiti nedavne promjene u wikiju.",
"rcloaderr"		=> "Učitavam nedavne promjene",
"rcnote"		=> "Slijedi zadnjih <strong>$1</strong> promjena u zadnjih <strong>$2</strong> dana.",
"rcnotefrom"		=> "Slijede promjene od <b>$2</b> (prikazano ih je do <b>$1</b>).",
"rclistfrom"		=> "Prikaži nove promjene počevši od $1",
'showhideminor' => "<br />$1 manje promjene | $2 botove | $3 prijavljene suradnike | $4 nadzirana uređivanja ",
"rclinks"		=> "Prikaži zadnjih $1 promjena u zadnjih $2 dana; $3",
"rchide"		=> "u $4 obliku; $1 manjih promjena; $2 promjena u sekundarnim prostorima; $3 višekratnih uređivanja.",
'rcliu'			=> "; $1 uređivanja prijavljenih suradnika",
"diff"			=> "razl",
"hist"			=> "pov",
"hide"			=> "sakrij",
"show"			=> "prikaži",
"tableform"		=> "tablica",
"listform"		=> "popis",
"nchanges"		=> "$1 promjena",
"minoreditletter"       => "m",
"newpageletter"         => "N",
'sectionlink' => '→',
'number_of_watching_users_RCview' 	=> '[$1]',
'number_of_watching_users_pageview' 	=> '[$1 suradnika prati ovu stranicu]',

# Upload
#
"upload"		=> "Postavi datoteku",
"uploadbtn"		=> "Postavi datoteku",
"uploadlink"	        => "Postavi sliku",
"reupload"		=> "Ponovno postavi",
"reuploaddesc"	        => "Vratite se u obrazac za postavljanje.",
"uploadnologin"         => "Niste prijavljeni",
"uploadnologintext"	=> "Za postavljanje datoteka morate biti  [[Special:Userlogin|prijavljeni].",
"uploadfile"	        => "Postavi datoteku",
'upload_directory_read_only' => 'Server ne može pisati u direktorij za postavljanje ($1).',
"uploaderror"	        => "Greška kod postavljanja",
"uploadtext"	=> "'''STANITE!''' Prije nego što postavite sliku pročitajte i slijedite upute
o [[Project:Slike|upotrebi slika]].

Ovaj obrazac služi za postavljanje novih slika. Za pregledavanje i pretraživanje već postavljenih slika
vidi [[Special:Imagelist|popis postavljenih datoteka]]. Postavljanja i brisanja bilježe se i u [[Special:Log|evidenciji]].

Stavljanjem oznake u odgovarajući kvadratić morate potvrditi da postavljanjem slike ne kršite ničija autorska prava.
Na kraju pritisnite dugme \"Postavi datoteku\".

Da biste na stranicu stavili sliku, koristite poveznice tipa
'''<nowiki>[[{{ns:6}}:datoteka.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:6}}:datoteka.png|popratni tekst]]</nowiki>''' ili
'''<nowiki>[[{{ns:-2}}:datoteka.ogg]]</nowiki>''' za izravnu poveznicu na datoteku.",
'uploadlog' => 'evidencija postavljanja',
'uploadlogpage' => 'Evidencija_postavljanja',
'uploadlogpagetext' => 'Dolje je popis nedavno postavljenih slika.',
"filename"		=> "Ime datoteke",
"filedesc"		=> "Opis",
'fileuploadsummary' => 'Opis:',
'filestatus' => 'Status autorskih prava',
'filesource' => 'Izvor',
"copyrightpage"         => "Project:Autorska prava",
"copyrightpagename"     => "Autorska prava na projektu {{SITENAME}}",
"uploadedfiles"	        => "Postavljene datoteke",
"minlength"		=> "Imena slika moraju imati najmanje tri slova.",
'illegalfilename' => 'Ime datoteke "$1" sadrži znakove koji nisu dozvoljeni u imenima stranica. Preimenujte datoteku i ponovno je postavite.',
"badfilename"	        => "Ime slike automatski je promijenjeno u \"$1\".",
"badfiletype"	        => "\".$1\" nije preporučljiv format za slike.",
"largefile"		=> "Preporučljivo je da veličina slika ne prelazi 100 kb",
'emptyfile' => 'Datoteka koju ste postavili je prazna. Možda se radi o krivo utipkanom imenu datoteke. Provjerite želite li zaista postaviti ovu datoteku.',
'fileexists' => 'Datoteka s ovim imenom već postoji, pogledajte $1 ako niste sigurni želite li je uistinu promijeniti.',
"successfulupload"      => "Postavljanje uspješno.",
"fileuploaded"	        => "Datoteka \"$1\" je uspješno postavljena.
Slijedite ovu poveznicu: ($2) na stranicu s opisom i unesite
podatke o datoteci: opis, izvor i licencu.

Ako je ovo slika, možete je unijeti u stranicu ovako: <tt><nowiki>[[Image:$1|thumb|Opis]]</nowiki></tt>.",
"uploadwarning"         => "Upozorenje kod postavljanja",
"savefile"		=> "Sačuvaj datoteku",
"uploadedimage"         => "postavljeno \"$1\"",
'uploaddisabled' => 'Oprostite, postavljanje je onemogućeno',
'uploadscripted' => 'Ova datoteka sadrži HTML ili skriptu, što može dovesti do grešaka u web pregledniku.',
'uploadcorrupt' => 'Ova je datoteka oštećena ili ima nepravilan nastavak. Provjerite i pokušajte ponovo.',
'uploadvirus' => 'Datoteka sadrži virus! Podrobnije: $1',
'sourcefilename' => 'Ime datoteke na vašem računalu',
'destfilename' => 'Ime datoteke na wikiju',

# Image list
#
"imagelist"		=> "Popis slika",
"imagelisttext"	        => "Ispod je popis $1 slika složen $2.",
"getimagelist"	        => "dobavljam popis slika",
"ilsubmit"		=> "Traži",
"showlast"		=> "Prikaži $1 slika složenih $2.",
"byname"		=> "po imenu",
"bydate"		=> "po datumu",
"bysize"		=> "po veličini",
"imgdelete"		=> "bris",
"imgdesc"		=> "opis",
"imglegend"		=> "Uputa: (opis) = prikaži/uredi opis slike.",
"imghistory"	        => "Povijest slike",
"revertimg"		=> "vra",
"deleteimg"		=> "bri",
"deleteimgcompletely"		=> "Izbriši sve inačice datoteke",
"imghistlegend"         => "Uputa: (tre) = trenutna slika, (bri) = briši
zadnju inačicu, (vra) = vrati sliku na prethodnu inačicu.
<br /><i>Klikni na datum, da vidiš inačicu koja je tada postavljena</i>.",
"imagelinks"	=> "Poveznice slike",
"linkstoimage"	=> "Sljedeće stranice povezuju na ovu sliku:",
"nolinkstoimage" => "Nijedna stranica ne povezuje na ovu sliku.",
'sharedupload' => 'Ova je datoteka postavljena na zajedničkom poslužitelju i mogu je koristiti i ostali wikiji',
'shareduploadwiki' => 'Za podrobnije informacije vidi $1.',
'shareduploadwiki-linktext' => 'stranica s opisom datoteke',
'shareddescriptionfollows' => '-',
'noimage'       => 'Ne postoji datoteka s ovim imenom. Možete ju $1.',
'noimage-linktext'       => 'postaviti',
'uploadnewversion' => '[$1 Postavi novu inačicu datoteke]',

# Statistics
#
"statistics"	=> "Statistika",
"sitestats"		=> "Statistika ovog wikija",
"userstats"		=> "Statistika suradnika",
"sitestatstext" => "U bazi podataka ukupno je <b>$1</b> članaka.
Ovaj broj uključuje stranice za raspravu, stranice o projektu u prostoru {{SITENAME}}, kratke članke,
preusmjerene stranice, i sve ostale članke koje najvjerojatnije ne možemo računati kao sadržaj.

Trenutno je <b>$2</b> članaka koji predstavljaju valjan sadržaj (nalaze se u glavnom prostoru i sadrže
barem jednu unutarnju poveznicu).

Ukupno je <b>$3</b> pregleda stranica, i <b>$4</b> uređivanja članaka od pokretanja projekta {{SITENAME}} na hrvatskom.
U prosjeku to iznosi <b>$5</b> uređivanja po stranici, i <b>$6</b> pregleda po uređivanju.
<br />",
"userstatstext" => "Broj registriranih suradnika je <b>$1</b>. Od toga je <b>$2</b> administratora (vidi $3).",

# Maintenance page
#
"maintenance"		=> "Stranica za održavanje",
"maintnancepagetext"	=> "Na ovoj se stranici nalazi nekoliko alata za svakodnevno održavanje.
Neke od ovih funkcija opterećuju bazu podataka pa vas zato molimo da ne učitavate iznova nakon svakog popravka ;-)",
"maintenancebacklink"	=> "Natrag na stranicu za održavanje",
"disambiguations"	=> "Razdvojbene stranice",
"disambiguationspage"	=> "Template:disambig",
"disambiguationstext"	=> "Sljedeći su članci povezani na <i>razdvojbenu stranicu</i>. Morali bi biti povezani
na odgovarajući sadržaj.<br />Stranica je razdvojbena ako je povezana iz $1.<br />Poveznice
iz sekundarnih prostora ovdje <i>nisu</i> prikazane.",
"doubleredirects"	=> "Dvostruko preusmjeravanje",
"doubleredirectstext"	=> "<b>Pozor:</b>ovaj popis može sadržavati nepravilne članove. To obično znači
da postoji dodatan tekst u poveznici prve naredbe \#REDIRECT.<br />
Svaki red sadrži poveznice na prvo i drugo preusmjeravanje, te te prvu liniju teksta drugog preusmjeravanja
koja obično ukazuje na \"pravu\" odredišnu stranicu, na koju bi trebalo pokazivati prvo preusmjeravanje.",
"brokenredirects"	=> "Kriva preusmjeravanja",
"brokenredirectstext"	=> "Sljedeća preusmjeravanja pokazuju na nepostojeće članke.",
"selflinks"		=> "Stranice s poveznicama na same sebe",
"selflinkstext"		=> "Sljedeće stranice povezuju na same sebe, što ne bi trebalo postojati.",
"mispeelings"           => "Stranice s pogrešno napisanim riječima",
"mispeelingstext"       => "Sljedeće stranice sadrže česte pravopisne pogreške, prikazane na $1.
Ponegdje je upisan i ispravni oblik te pogreške (ovako).",
"mispeelingspage"       => "Popis čestih pravopisnih pogrešaka",
"missinglanguagelinks"  => "Jezične poveznice koje nedostaju",
"missinglanguagelinksbutton"    => "Nađi jezične poveznice koje nedostaju za",
"missinglanguagelinkstext"      => "Ovi članci <i>nisu</i> povezani s njihovim ekvivalentima u $1. Preusmjeravanja i
podstranice <i>nisu</i> prikazane.",


# Miscellaneous special pages
#
"orphans"	=> "Stranice siročad",
'geo'		=> 'GEO koordinate',
"lonelypages"	=> "Stranice siročad",
'uncategorizedpages'	=> 'Nekategorizirane stranice',
'uncategorizedcategories'	=> 'Nekategorizirane kategorije',
'unusedcategories' => 'Nekorištene kategorije',
"unusedimages"	=> "Nekorištene slike",
"popularpages"	=> "Popularne stranice",
"nviews"	=> "$1 puta pogledano",
"wantedpages"	=> "Tražene stranice",
'mostlinked'	=> 'Stranice na koje vodi najviše poveznica',
"nlinks"	=> "$1 poveznica",
"allpages"	=> "Sve stranice",
'prefixindex'   => 'Indeks prema početku naslova',
"randompage"	=> "Slučajna stranica",
"shortpages"	=> "Kratke stranice",
"longpages"	=> "Duge stranice",
'deadendpages'  => 'Slijepe ulice',
"listusers"	=> "Popis suradnika",
"specialpages"	=> "Posebne stranice",
"spheading"	=> "Posebne stranice za sve suradnike",
'restrictedpheading' => 'Posebne stranice s ograničenim pristupom',
"protectpage"	=> "Zaštiti stranicu",
"recentchangeslinked" => "Povezane stranice",
"rclsub"	=> "(na stranice povezane iz \"$1\")",
"debug"		=> "Uklanjaj programske greške",
"newpages"	=> "Nove stranice",
'ancientpages'		=> 'Najstarije stranice',
'intl'		=> 'Interwiki poveznice',
"move"		=> "Premjesti",
"movethispage"	=> "Premjesti ovu stranicu",
"unusedimagestext" => "<p>Moguće je da su druge mrežne stranice izvan ovog
wikija povezane na sliku neposrednim URLom, a nisu ovdje navedene unatoč aktivnoj uporabi.</p>",
"booksources"	=> "Pretraživanje po ISBN-u",
'categoriespagetext' => 'Na ovom wikiju postoje sljedeće kategorije.',
'data'	=> 'Podaci',
'userrights' => 'Upravljanje suradničkim pravima',
'groups' => 'Suradničke skupine',
"booksourcetext" => "Dolje je popis poveznica prema stranicama koje prodaju nove ili rabljene knjige i
gdje su možda na raspolaganju dodatne informacije o knjigama koje tražite.
{{SITENAME}} ne posluje ni s jednim od ovih siteova i ovaj popis nije pokazatelj njihovog uspjeha.",
'rfcurl' =>  'http://www.ietf.org/rfc/rfc$1.txt',
'pubmedurl' =>  'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'alphaindexline' => "$1 do $2",
'version'		=> 'Verzija softvera',
'log'		=> 'Evidencije',
'alllogstext' => 'Skupni prikaz evidencija postavljenih datoteka, brisanja, zaštite, blokiranja, i administratorskih prava.
Možete suziti prikaz odabirući tip evidencije, suradničko ime ili stranicu u pitanju.',

# Special:Allpages
'nextpage' => 'Sljedeća stranica ($1)',
'allpagesfrom' => 'Pokaži stranice počevši od:',
'allarticles' => 'Svi članci',
'allnonarticles' => 'Svi ne-članci',
'allinnamespace' => 'Svi članci (prostor $1)',
'allnotinnamespace' => 'Sve stranice koje nisu u prostoru $1 ',
'allpagesprev' => 'Prijašnje',
'allpagesnext' => 'Sljedeće',
'allpagessubmit' => 'Kreni',

# Email this user
#
"mailnologin"	=> "Nema adrese pošiljaoca",
"mailnologintext" => "Morate biti [[Special:Userlogin|prijavljeni]]
i imati valjanu adresu e-pošte u svojim [[Special:Preferences|postavkama]]
da bi mogli slati poštu drugim suradnicima.",
"emailuser"	=> "Pošalji e-poštu ovom suradniku",
"emailpage"	=> "Pošalji e-poštu suradniku",
"emailpagetext"	=> "Ako je suradnik unio valjanu e-mail adresu u svojim postavkama,
bit će mu poslana poruka s tekstom iz donjeg obrasca.
E-mail adresa iz vaših postavki nalazit će se u \"From\" polju poruke i primatelj će vam moći odgovoriti.",
'usermailererror' => 'Sustav pošte se vratio s greškom: ',
'defemailsubject'  => "{{SITENAME}} e-mail",
"noemailtitle"	=> "Nema adrese primaoca",
"noemailtext"	=> "Ovaj suradnik nije unio valjanu e-mail adresu ili se odlučio na neće primati poštu od drugih suradnika.",
"emailfrom"	=> "Od",
"emailto"	=> "Za",
"emailsubject"	=> "Tema",
"emailmessage"	=> "Poruka",
"emailsend"	=> "Pošalji",
"emailsent"	=> "E-mail poslan",
"emailsenttext" => "Vaša poruka je poslana.",

# Watchlist
#
"watchlist"	=> "Moj popis praćenja",
"watchlistsub"	=> "(za suradnika \"$1\")",
"nowatchlist"	=> "Na vašem popisu praćenja nema nijednog članka.",
"watchnologin"	=> "Niste prijavljeni",
"watchnologintext" => "Morate biti [[Special:Userlogin|prijavljeni]]
za promjene u popisu praćenja.",
"addedwatch"	=> "Dodano u popis praćenja",
"addedwatchtext" => "Stranica \"$1\" je dodana na vaš [[Special:Watchlist|popis praćenja]].
Promjene na ovoj stranici i njenoj stranici za razgovor bit će tamo prikazani, a stranica će biti ispisana
<b>podebljano</b> u [[Special:Recentchanges|popisu nedavnih promjena]], da biste je lakše primijetili.
<p>Ako poželite ukloniti stranicu s popisa praćenja, pritisnite \"Prekini praćenje\" u traci s naredbama.</p>",
"removedwatch"	=> "Odstranjena s popisa praćenja",
"removedwatchtext" => "Stranica \"$1\" je odstranjena s vašeg popisa praćenja.",
"watchthispage"	=> "Prati ovu stranicu",
"unwatchthispage" => "Prekini praćenje",
"notanarticle"	=> "Nije članak",
"watch" 	=> "Prati",
'unwatch' => 'Prekini praćenje',

'notanarticle' => 'Nije članak',
'watchnochange' => 'Niti jedna od praćenih stranica nije promijenjena od vašeg zadnjeg posjeta.',
'watchdetails' => '* broj stranica koje se prate (ne brojeći stranice za razgovor): $1
* [[Special:Watchlist/edit|prikaži i uredi popis praćenja]]',
'wlheader-enotif' => '* Uključeno je izvješćivanje e-mailom.',
'wlheader-showupdated' => '* Stranice koje su promijenjene od vašeg zadnjeg posjeta prikazane su \'\'\'podebljano\'\'\'',
'watchmethod-recent' => 'provjera nedavnih promjena praćenih stranica',
'watchmethod-list' => 'provjera praćanih stranica za nedavne promjene',
'removechecked' => 'Ukloni označene članke s popisa praćenja',
'watchlistcontains' => 'Broj stranica na vašem popisu praćenja je $1.',
'watcheditlist' => 'Ovdje je abecedni popis stranica koje pratite. Označite stranice koje želite ukloniti
s popisa i pritisnite dugme \'ukloni označeno\' na dnu ekrana (uklanjanjem stranice sa sadržajem uklanja se
i stranica za razgovor i obrnuto).',
'removingchecked' => 'Uklanjam ove članke s popisa praćenja...',
'couldntremove' => 'Nisam mogao ukloniti \'$1\'...',
'iteminvalidname' => 'Problem s izborom \'$1\', ime nije valjano...',
'wlnote' => 'Ovdje je posljednjih $1 promjena u posljednjih <b>$2</b> sati.',
'wlshowlast' => 'Pokaži zadnjih $1 sati $2 dana $3',
'wlsaved' => 'Ovo je snimljena inačica vašeg popisa praćenja.',
'wlhideshowown' => '$1 moja uređivanja.',
'wlshow'		=> 'Pokaži',
'wlhide'		=> 'Sakrij',

'enotif_mailer' => '{{SITENAME}} - izvješća o promjenama',
'enotif_reset' => 'Označi sve stranice kao već posjećene',
'enotif_newpagetext' => 'Ovo je nova stranica.',
'changed' => 'promijenio',
'created' => 'stvorio',
'enotif_subject' => '{{SITENAME}}: Stranicu $PAGETITLE je $CHANGEDORCREATED suradnik $PAGEEDITOR',
'enotif_lastvisited' => 'Pogledaj $1 za promjene od zadnjeg posjeta.',
'enotif_body' => '$WATCHINGUSERNAME,

stranicu na projektu {{SITENAME}} s naslovom $PAGETITLE je dana $PAGEEDITDATE $CHANGEDORCREATED suradnik $PAGEEDITOR,
pogledajte $PAGETITLE_URL za trenutnu inačicu.

$NEWPAGE

Sažetak urednika: $PAGESUMMARY $PAGEMINOREDIT

Možete se javiti uredniku:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Do vašeg ponovnog posjeta stranici nećete dobivati daljnja izviješća.
Postavke za izvješćivanje možete resetirati na svom popisu praćenja.

            Vaš sustav izvješćivanja - hrvatska {{SITENAME}}.

--
Za promjene svog popisa praćenja posjetite
{{SERVER}}{{localurl:Special:Watchlist|edit=yes}}

Za pomoć posjetite:
{{SERVER}}{{localurl:Help:Contents}}',


# Delete/protect/revert
#
"deletepage"	=> "Izbriši stranicu",
"confirm"	=> "Potvrdi",
"excontent"     => "sadržaj je bio:",
'excontentauthor' => "sadržaj je bio: '$1' (a jedini urednik '$2')",
"exbeforeblank" => "sadržaj prije brisanja je bio: '$1'",
"exblank"       => "stranica je bila prazna",
"confirmdelete" => "Potvrdi brisanje",
"deletesub"	=> "(Brišem \"$1\")",
"historywarning" => "UPOZORENJE: Stranica koju želite obrisati ima prijašnje inačice: ",
"confirmdeletetext" => "Zauvijek ćete izbrisati stranicu ili sliku zajedno s prijašnjim inačicama.
Molim potvrdite svoju namjeru, da razumijete posljedice i da ovo radite u skladu s [[Project:Pravila|pravilima]].",
"confirmcheck"	 => "Da, sigurno želim izbrisati stranicu.",
"actioncomplete" => "Zahvat završen",
"deletedtext"	 => "\"$1\" je izbrisana.
Vidi $2 za evidenciju nedavnih brisanja.",
"deletedarticle" => "izbrisano \"$1\"",
"dellogpage"	 => "Evidencija_brisanja",
"dellogpagetext" => "Dolje je popis nedavnih brisanja.
Sva vremena su prema poslužiteljevom vremenu (UTC).
<ul>
</ul>",
"deletionlog"	=> "evidencija brisanja",
"reverted"	=> "Vraćeno na prijašnju inačicu",
"deletecomment"	=> "Razlog za brisanje",
"imagereverted" => "Uspješno vraćeno na prijašnju inačicu.",
"rollback"	=> "Ukloni posljednju promjenu",
'rollback_short' => 'Ukloni',
"rollbacklink"	=> "ukloni",
'rollbackfailed' => 'Uklanjanje neuspješno',
"cantrollback"	=> "Ne mogu ukloniti posljednju promjenu, postoji samo jedna promjena.",
"alreadyrolled" => "Ne mogu ukloniti posljednju promjenu članka [[$1]] koju je napravio suradnik [[User:$2|$2]]
([[User talk:$2|Talk]]); netko je već promijenio stranicu ili uklonio promjenu.

Posljednju promjenu napravio je suradnik [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
"editcomment"  => "Komentar promjene je: \"<i>$1</i>\".",
"revertpage"   => "Uklonjena promjena suradnika $2, vraćeno na zadnju inačicu suradnika $1",
'sessionfailure' => 'Uočili smo problem s vašom prijavom. Zadnja naredba nije izvršena
kako bi izbjegla zloupotreba. Molimo vas da u pregledniku pritisnete "Natrag" (Back) i ponovno učitate stranicu
s koje ste stigli.',
'protectlogpage' => 'Evidencija zaštićivanja',
'protectlogtext' => 'Ispod je popis zaštićivanja i uklanjanja zaštite pojedinih stranica.
Pogledajte članak [[Project:Protected page|Zaštićena stranica]] za više obavijesti na ovu temu.',
'protectedarticle' => 'članak "[[$1]]" je zaštićen',
'unprotectedarticle' => 'uklonjena zaštita članka "[[$1]]"',
'protectsub' => '(Zaštićujem "$1")',
'confirmprotecttext' => 'Želite li doista zaštititi ovu stranicu?',
'confirmprotect' => 'Potvrda zaštite',
'protectmoveonly' => 'Zaštiti samo od premještanja',
'protectcomment' => 'Razlog za zaštitu',
'unprotectsub' => '(Uklanjam zaštitu stranice "$1")',
'confirmunprotecttext' => 'Želite li doista ukloniti zaštitu?',
'confirmunprotect' => 'Potvrda uklanjanja zaštite',
'unprotectcomment' => 'Razlog za uklanjanje zaštite',

# Undelete
"undelete" => "Vrati izbrisanu stranicu",
"undeletepage" => "Vidi i/ili vrati izbrisane stranice",
'viewdeletedpage' => 'Pogledaj izbrisanu stranicu',
"undeletepagetext" => "Sljedeće su stranice izbrisane, ali se još uvijek nalaze u bazi i mogu se obnoviti. Baza se povremeno čisti od ovakvih stranica.",
"undeletearticle" => "Vrati izbrisanu stranicu",
"undeleterevisions" => "$1 inačica je arhivirano",
"undeletehistory" => "Ako vratite izbrisanu stranicu, bit će vraćene i sve prijašnje promjene. Ako je u međuvremenu stvorena nova stranica s istim imenom, vraćena stranica bit će upisana kao prijašnja promjena sadašnje. Sadašnja stranica neće biti zamijenjena.",
'undeletehistorynoadmin' => 'Ovaj je članak izbrisan. Razlog za brisanje prikazan je u donjem sažetku, zajedno s
detaljima o suradnicima koji su uređivali ovu stranicu prije brisanja.
Tekst izbrisanih inačica dostupan je samo administratorima.',
"undeleterevision" => "Izbrisana inačica od $1",
"undeletebtn" => "Vrati!",
"undeletedarticle" => "vraćen \"$1\"",
"undeletedtext"   => "Članak [[:$1|$1]] je uspješno vraćen.
Vidi [[Project:Evidencija_brisanja]] za popis nedavnih brisanja i vraćanja.",

# Namespace form on various pages
'namespace' => 'Prostor:',
'invert' => 'Sve osim odabranog',

# Contributions
#
"contributions"	=> "Doprinosi suradnika",
"mycontris"     => "Moji doprinosi",
"contribsub"	=> "Za $1",
"nocontribs"	=> "Nema promjena koje udovoljavaju ovim kriterijima.",
"ucnote"	=> "Ovdje je zadnjih <b>$1</b> promjena ovog suradnika u zadnjih <b>$2</b> dana.",
"uclinks"	=> "Pogledaj zadnjih $1 promjena; pogledaj zadnjih $2 dana.",
"uctop"		=> " (vrh)" ,
'newbies'       => 'novaci',
'contribs-showhideminor' => '$1 manjih izmjena',

# What links here
#
"whatlinkshere"	=> "Što vodi ovamo",
"notargettitle" => "Nema odredišta",
"notargettext"	=> "Niste naveli ciljnu stranicu ili suradnika za izvršavanje ove funkcije.",
"linklistsub"	=> "(Popis poveznica)",
"linkshere"	=> "Sljedeće stranice povezuju ovamo:",
"nolinkshere"	=> "Nijedna stranica ne povezuje ovamo.",
"isredirect"	=> "stranica za preusmjeravanje",

# Block/unblock IP
#
"blockip"	=> "Blokiraj suradnika",
"blockiptext"	=> "Koristite donji obrazac za blokiranje pisanja pojedinih suradnika ili IP adresa .
To biste trebali raditi samo zbog sprječavanja vandalizma i u skladu
sa [[Project:Policy|smjernicama]].
Upišite i razlog za ovo blokiranje (npr. stranice koje su
vandalizirane).",
"ipaddress"	=> "IP adresa",
'ipadressorusername' => 'IP adresa ili suradničko ime',
'ipbexpiry' => 'Rok (na engleskom)',
"ipbreason"	=> "Razlog",
"ipbsubmit"	=> "Blokiraj ovog suradnika",
'ipbother' => 'Neki drugi rok (na engleskom, npr. 6 days',
'ipboptions'		=> '2 hours:2 hours,1 day:1 day,3 days:3 days,1 week:1 week,2 weeks:2 weeks,1 month:1 month,3 months:3 months,6 months:6 months,1 year:1 year,infinite:infinite',
'ipbotheroption'	=> 'drugo',
"badipaddress"	=> "Nevaljana IP adresa.",
"blockipsuccesssub" => "Uspješno blokirano",
"blockipsuccesstext" => "Suradnik [[{{ns:Special}}:Contributions/$1|$1]] je blokiran.
<br />Pogledaj [[{{ns:Special}}:Ipblocklist|IP block list]] za pregled blokiranja.",
"unblockip"	=> "Deblokiraj suradnika",
"unblockiptext"	=> "Ovaj se obrazac koristi za vraćanje prava na pisanje prethodno blokiranoj IP adresi.",
"ipusubmit"	=> "Deblokiraj ovu adresu",
"ipusuccess"	=> "Suradnik \"$1\" deblokiran",
"ipblocklist"	=> "Popis blokiranih IP adresa",
"blocklistline"	=> "$1, $2 je blokirao $3",
'infiniteblock' => 'neograničeno',
'expiringblock' => 'istječe $1',
'ipblocklistempty'	=> 'Popis blokiranja je prazan.',
"blocklink"	=> "blokiraj",
"unblocklink"	=> "deblokiraj",
"contribslink"	=> "doprinosi",
"autoblocker" => "Automatski ste blokirani jer je vašu IP adresu nedavno koristio \"[[User:$1|$1]]\" koji je blokiran zbog: \"$2\".",
'blocklogpage' => 'Evidencija_blokiranja',
'blocklogentry' => 'Blokiran je "[[$1]]" na rok $2',
'blocklogtext' => 'Ovo je evidencija blokiranja i deblokiranja. Na popisu
nema automatski blokiranih IP adresa. Za popis trenutnih zabrana i
blokiranja vidi [[Special:Ipblocklist|listu IP blokiranja]].',
'unblocklogentry' => 'Deblokiran "$1"',
'range_block_disabled' => 'Isključena je administratorska naredba za blokiranje raspona IP adresa.',
'ipb_expiry_invalid' => 'Vremenski rok nije valjan.',
'ip_range_invalid' => 'Raspon IP adresa nije valjan.',
'proxyblocker' => 'Zaštita od otvorenih posrednika (proxyja)',
'proxyblockreason' => 'Vaša je IP adresa blokirana jer se radi o otvorenom posredniku (proxyju). Molim stupite u vezu s vašim davateljem internetskih usluga (ISP-om) ili službom tehničke podrške i obavijestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess'	=> "Napravljeno.",
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'Vaša IP adresa je na popisu otvorenih posrednika na poslužitelju [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Vaša IP adresa je na popisu otvorenih posrednika na poslužitelju [http://www.sorbs.net SORBS] DNSBL. Ne možete otvoriti račun.',

# Developer tools
#
"lockdb"	=> "Zaključaj bazu podataka",
"unlockdb"	=> "Otključaj bazu podataka",
"lockdbtext"	=> "Zaključavanjem baze će se suradnicima onemogućiti uređivanje stranica, mijenjanje postavki i popisa praćenja, i sve drugo što zahtijeva promjene u bazi podataka.
Molim potvrdite svoju namjeru zaključavanja, te da ćete otključati bazu čim završite s održavanjem.",
"unlockdbtext"	=> "Otključavanjem baze omogućit ćete suradnicima uređivanje stranica,
mijenjanje postavki, uređivanje popisa praćenja i druge stvari koje zahtijevaju promjene u bazi. Molim potvrdite svoju namjeru.",
"lockconfirm"	=> "Da, sigurno želim zaključati bazu.",
"unlockconfirm"	=> "Da, sigurno želim otključati bazu.",
"lockbtn"	=> "Zaključaj bazu podataka",
"unlockbtn"	=> "Otključaj bazu podataka",
"locknoconfirm" => "Niste potvrdili svoje namjere.",
"lockdbsuccesssub" => "Zaključavanje baze podataka uspjelo",
"unlockdbsuccesssub" => "Otključavanje baze podataka uspjelo",
"lockdbsuccesstext" => "Baza podataka je zaključana.
<br />Ne zaboravite otključati po završetku održavanja.",
"unlockdbsuccesstext" => "Baza podataka je otključana.",

# Make sysop
'makesysop' => 'Daj suradniku administratorska prava',
'makesysoptext' => 'Ovaj obrazac služi birokratima za dodjeljivanje administratorskih prava pojedinom suradniku. Utipkajte ime suradnika u kućicu i pritisnite dugme kako biste suradniku dali administratorska prava.',
'makesysopname' => 'Ime suradnika:',
'makesysopsubmit' => 'Učini ovog suradnika administratorom',
'makesysopok' => '<b>Suradnik "$1" je postao administrator</b>',
'makesysopfail' => '<b>Suradnika "$1" nije se moglo učiniti administratorom. (Jeste li pravilno upisali ime?)</b>',
'setbureaucratflag' => 'Postavi oznaku birokrata',
'setstewardflag'    => 'Postavi oznaku upravitelja',
'bureaucratlog'		=> 'Evidencija_birokrata',
'rightslogtext'		=> 'Ovo je evidencija promjena suradničkih prava.',
'bureaucratlogentry' => 'Suradnik $1 premješten iz skupine $2 u skupinu $3',
'rights'			=> 'Prava:',
'set_user_rights'	=> 'Postavi suradnička prava',
'user_rights_set' => '<b>Prava za suradnika "$1" postavljena</b>',
'set_rights_fail'	=> "<b>Prava za suradnika \"$1\" nisu postavljena. (Jeste li pravilno upisali ime?)</b>",
'makesysop' => 'Učini suradnika administratorom',
'already_sysop' => 'Ovaj je suradnik već administrator',
'already_bureaucrat' => 'Ovaj je suradnik već birokrat',
'already_steward' => 'Ovaj je suradnik već upravitelj',

# Move page
#
"movepage"	=> "Premjesti stranicu",
'movepagetext' => 'Korištenjem ovog obrasca ćete preimenovati stranicu i premjestiti sve stare izmjene
na novo ime.
Stari će se naslov pretvoriti u stranicu koja automatski preusmjerava na novi naslov.
Poveznice na stari naslov ostat će iste; bilo bi dobro da
[[Special:Maintenance|provjerite]] je li preusmjeravanje ispravno.
Na vama je da se pobrinete da poveznice i dalje vode tamo
gdje bi trebale.

Stranica se \'\'\'neće\'\'\' premjestiti ako već postoji stranica s novim naslovom,
osim u slučaju prazne stranice ili stranice za preusmjeravanje koja nema
nikakvih starih izmjena. To znači: 1. ako pogriješite, možete opet preimenovati
stranicu na stari naslov, 2. ne može vam se dogoditi da izbrišete neku postojeću stranicu.

<b>OPREZ!</b>
Ovo može biti drastična i neočekivana promjena kad su u pitanju popularne stranice,
i zato dobro razmislite prije nego što preimenujete stranicu.',
'movepagetalktext' => 'Stranica za razgovor, ako postoji, automatski će se premjestiti zajedno sa stranicom koju premještate. \'\'\'Stranica za razgovor neće se premjestiti ako:\'\'\'
*premještate stranicu iz jednog prostora u drugi,
*pod novim imenom već postoji stranica za razgovor s nekim sadržajem, ili
*maknete kvačicu u kućici na dnu ove stranice.

U tim slučajevima ćete morati sami premjestiti ili iskopirati stranicu za razgovor,
ako to želite.',
'movearticle' => 'Premjesti stranicu',
"movenologin"	=> "Niste prijavljeni",
'movenologintext' => 'Ako želite premjestiti stranicu morate biti [[Special:Userlogin|prijavljeni]].',
'newtitle' => 'Na novi naslov',
"movepagebtn"	=> "Premjesti stranicu",
"pagemovedsub"	=> "Premještanje uspjelo",
'pagemovedtext' => 'Stranica "[[$1]]" premještena je na "[[$2]]".',
'articleexists' => 'Stranica pod tim imenom već postoji ili ime koje ste odabrali nije u skladu s pravilima.
Molimo odaberite drugo ime.',
'talkexists' => '\'\'\'Sama stranica je uspješno prenesena, ali stranicu za razgovor nije bilo moguće prenijeti jer na odredištu već postoji stranica za razgovor. Molimo da ih ručno spojite.\'\'\'',
'movedto' => 'premješteno na',
'movetalk' => 'Premjesti i njezinu stranicu za razgovor ako je moguće.',
'talkpagemoved' => 'Pripadajuća stranica za razgovor također je premještena.',
'talkpagenotmoved' => 'Pripadajuća stranica za razgovor <strong>nije</strong> premještena.',
'1movedto2'		=> '$1 premješteno na $2',
'1movedto2_redir' => '$1 premješteno na $2 preko postojećeg preusmjeravanja',
'movelogpage' => 'Evidencija premještanja',
'movelogpagetext' => 'Ispod je popis premještenih stranica.',
'movereason' => 'Razlog',
'revertmove' => 'vrati',
'delete_and_move' => 'Izbriši i premjesti',
'delete_and_move_text' => '==Nužno brisanje==

Odredišni članak "[[$1]]" već postoji. Želite li ga obrisati da biste napravili mjesto za premještaj?',
'delete_and_move_reason' => 'Obrisano kako bi se napravilo mjesta za premještaj.',
'selfmove' => "Izvorni i odredišni naslov su isti; ne mogu premjestiti stranicu na nju samu.",
'immobile_namespace' => 'Odredišni naslov pripada posebnom tipu; u taj prostor ne mogu pomicati stranice.',

# Export

'export' => 'Izvezi stranice',
'exporttext' => 'Možete izvesti tekst i prijašnje promjene jedne ili više stranica uklopljene u XML kod. U budućim verzijama MediaWiki softvera bit će moguće uvesti ovakvu stranicu u neki drugi wiki. Trenutna verzija to još ne podržava.

Za izvoz stranica unesite njihove naslove u polje ispod, jedan naslov po retku, i označite želite li trenutnu inačicu zajedno sa svim prijašnjima, ili samo trenutnu inačicu s informacijom o zadnjoj promjeni.

U potonjem slučaju možete koristiti i poveznicu, npr. [[{{ns:Special}}:Export/Hrvatska]] za članak [[Hrvatska]].',
'exportcuronly' => 'Uključi samo trenutnu inačicu, ne i sve prijašnje',

# Namespace 8 related

'allmessages' => 'Sve sistemske poruke',
'allmessagesname' => 'Ime',
'allmessagesdefault' => 'Prvotni tekst',
'allmessagescurrent' => 'Trenutni tekst',
'allmessagestext' => 'Ovo je popis svih sistemskih poruka u prostoru MediaWiki: .',
'allmessagesnotsupportedUI' => 'Trenutno odabrani jezik, <b>$1</b>, nije podržan u popisu Special:AllMessages na ovom mjestu. ',
'allmessagesnotsupportedDB' => 'Uređivanje Special:AllMessages trenutno nije podržano jer je isključen parametar wgUseDatabaseMessages.',

# Thumbnails

'thumbnail-more' => 'Povećaj',
'missingimage' => '<b>Nedostaje slika</b><br /><i>$1</i>',
'filemissing' => 'Nedostaje datoteka',

# Special:Import
'import' => 'Uvezi stranice',
'importinterwiki' => 'Transwiki uvoz',
'importtext' => 'Molim da izvezete ovu datoteku iz izvorišnog wikija koristeći pomagalo Special:Export, snimite je na svoj disk i postavite je ovdje.',
'importfailed' => 'Uvoz nije uspio: $1',
'importnotext' => 'Prazno ili bez teksta',
'importsuccess' => 'Uvoz je uspio!',
'importhistoryconflict' => 'Došlo je do konflikta među prijašnjim inačicama (ova je stranica možda već uvezena)',
'importnosources' => 'Nije unesen nijedan izvor za transwiki uvoz i neposredno postavljanje povijesti je onemogućeno.',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Pretraži projekt {{SITENAME}} [alt-f]',
'tooltip-minoredit' => 'Označi kao manju promjenu [alt-i]',
'tooltip-save' => 'Sačuvaj promjene [alt-s]',
'tooltip-preview' => 'Prikaži kako će izgledati, molimo koristite prije snimanja! [alt-p]',
'tooltip-diff' => 'Prikaži promjene učinjene u tekstu. [alt-d]',
'tooltip-compareselectedversions' => 'Prikaži usporedbu izabranih inačica ove stranice. [alt-v]',
'tooltip-watch' => 'Dodaj na popis praćenja [alt-w]',

# Metadata
'nodublincore' => 'Dublin Core RDF metapodaci su isključeni na ovom serveru.',
'nocreativecommons' => 'Creative Commons RDF metapodaci su isključeni na ovom serveru.',
'notacceptable' => 'Wiki server ne može dobaviti podatke u obliku kojega vaš klijent može pročitati.',

# Attribution

'anonymous' => 'Anonimni suradnik projekta {{SITENAME}}',
'siteuser' => 'Suradnik $1 na projektu {{SITENAME}}',
'lastmodifiedby' => 'Ovu je stranicu zadnji put mijenjao dana $1 suradnik $2.',
'and' => 'i',
'othercontribs' => 'Temelji se na doprinosu suradnika $1.',
'others' => 'drugih',
'siteusers' => '{{SITENAME}} suradnik(ci) $1',
'creditspage' => 'Autori stranice',
'nocredits' => 'Za ovu stranicu nema podataka o autorima.',

# Spam protection

'spamprotectiontitle' => 'Zaštita od spama',
'spamprotectiontext' => 'Stranicu koju ste željeli snimiti blokirao je filter spama. Razlog je vjerojatno vanjska poveznica.',
'spamprotectionmatch' => 'Naš filter spama reagirao je na sljedeći tekst: $1',
'subcategorycount' => 'Broj potkategorija u ovoj kategoriji: $1.',
'subcategorycount1' => 'U ovoj je kategoriji jedna potkategorija.',
'categoryarticlecount' => 'Broj članaka u ovoj kategoriji: $1.',
'categoryarticlecount1' => 'U ovoj je kategoriji jedan članak.',
'usenewcategorypage' => "1\n\nAko želiš isključiti novi izgled stranice kategorija neka prvi znak bude \"0\".",
'listingcontinuesabbrev' => " nast.",

# Info page
'infosubtitle' => 'Podaci o stranici',
'numedits' => 'Broj promjena (članak): $1',
'numtalkedits' => 'Broj promjena (stranica za razgovor): $1',
'numwatchers' => 'Broj pratitelja: $1',
'numauthors' => 'Broj autora (članak): $1',
'numtalkauthors' => 'Broj autora (stranica za razgovor): $1',

# Math options
'mw_math_png' => 'Uvijek kao PNG',
'mw_math_simple' => 'Ako je vrlo jednostavno HTML, inače PNG',
'mw_math_html' => 'Ako je moguće HTML, inače PNG',
'mw_math_source' => 'Ostavi u formatu TeX (za tekstualne preglednike)',
'mw_math_modern' => 'Preporučeno za današnje preglednike',
'mw_math_mathml' => 'Ako je moguće MathML (u pokusnoj fazi)',

# Patrolling
'markaspatrolleddiff' => 'Postavi nadzor',
'markaspatrolledlink'   => "[$1]",
'markaspatrolledtext' => 'Postavi nadzor na ovom članku',
'markedaspatrolled' => 'Postavljen nadzor',
'markedaspatrolledtext' => 'Na navedenu promjenu postavljen je nadzor.',
'rcpatroldisabled' => 'Nadzor nedavnih promjena isključen',
'rcpatroldisabledtext' => 'Naredba "Nadziri nedavne promjene" trenutno je isključena.',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Moja suradnička stranica\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Suradnička stranica za IP adresu pod kojom uređujete\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Moja stranica za razgovor\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Razgovor o suradnicima s ove IP adrese\');
ta[\'pt-preferences\'] = new Array(\'\',\'Moje postavke\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Popis stranica koje pratite.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Popis mojih doprinosa\');
ta[\'pt-login\'] = new Array(\'o\',\'Predlažemo vam da se prijavite, ali nije obvezno.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Predlažemo vam da se prijavite, ali nije obvezno.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Odjavi se\');
ta[\'ca-talk\'] = new Array(\'t\',\'Razgovor o stranici\');
ta[\'ca-edit\'] = new Array(\'e\',\'Možete uređivati ovu stranicu. Koristite Pregled kako će izgledati prije nego što snimite.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Dodaj komentar ovom razgovoru.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Ova stranica je zaštićena. Možete pogledati izvorni kod.\');
ta[\'ca-history\'] = new Array(\'h\',\'Ranije izmjene na ovoj stranici.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Zaštiti ovu stranicu\');
ta[\'ca-delete\'] = new Array(\'d\',\'Izbriši ovu stranicu\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Vrati uređivanja na ovoj stranici prije nego što je izbrisana\');
ta[\'ca-move\'] = new Array(\'m\',\'Premjesti ovu stranicu\');
ta[\'ca-watch\'] = new Array(\'w\',\'Dodaj ovu stranicu na svoj popis praćenja\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Ukloni ovu stranicu s popisa praćenja\');
ta[\'search\'] = new Array(\'f\',\'Pretraži ovaj wiki\');
ta[\'p-logo\'] = new Array(\'\',\'Glavna stranica\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Posjeti glavnu stranicu\');
ta[\'n-portal\'] = new Array(\'\',\'O projektu, što možete učiniti, gdje je što\');
ta[\'n-currentevents\'] = new Array(\'\',\'O trenutnim događajima\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Popis nedavnih promjena u wikiju.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Učitaj slučajnu stranicu\');
ta[\'n-help\'] = new Array(\'\',\'Mjesto za pomoć suradnicima.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Podržite nas materijalno\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Popis svih stranica koje sadrže poveznice ovamo\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Nedavne promjene na stranicama na koje vode ovdašnje poveznice\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS feed za ovu stranicu\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom feed za ovu stranicu\');
ta[\'t-contributions\'] = new Array(\'\',\'Pogledaj popis suradnikovih doprinosa\');
ta[\'t-emailuser\'] = new Array(\'\',\'Pošalji suradniku e-mail\');
ta[\'t-upload\'] = new Array(\'u\',\'Postavi slike i druge medije\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Popis posebnih stranica\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Pogledaj sadržaj\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Pogledaj suradničku stranicu\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Pogledaj stranicu s opisom medija\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ovo je posebna stranica koju nije moguće izravno uređivati.\');
ta[\'ca-nstab-wp\'] = new Array(\'a\',\'Pogledaj stranicu o projektu\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Pogledaj stranicu o slici\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Pogledaj sistemske poruke\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Pogledaj predložak\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Pogledaj stranicu za pomoć\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Pogledaj stranicu kategorije\');',

# image deletion
'deletedrevision' => 'Izbrisana stara inačica $1.',

# browsing diffs
'previousdiff' => '← Usporedba s prethodnom',
'nextdiff' => 'Usporedba sa sljedećom →',

'imagemaxsize' => 'Ograniči veličinu slike na stranici s opisom: ',
'thumbsize'	=> 'Veličina sličice (umanjene inačice slike): ',
'showbigimage' => 'Učitaj u punoj veličini ($1x$2, $3 KB)',

'newimages' => 'Galerija novih datoteka',
'showhidebots' => '($1 botova)',
'noimages'  => 'Nema slika.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Suradnik: ',
'speciallogtitlelabel' => 'Naslov: ',

'passwordtooshort' => 'Vaša je lozinka prekratka. Lozinke moraju sadržavati najmanje $1 znakova.',

# Media Warning
'mediawarning' => '\'\'\'Upozorenje\'\'\': Ova datoteka možda sadrži zlonamjerni program čije bi izvršavanje moglo ugroziti vaš računalni sustav.
<hr>',

'fileinfo' => '$1KB, MIME tip: <code>$2</code>',

# Metadata
'metadata' => 'Metapodaci',

# Exif tags
'exif-imagewidth' =>'Širina',
'exif-imagelength' =>'Visina',
'exif-bitspersample' =>'Dubina boje',
'exif-compression' =>'Način sažimanja',
'exif-photometricinterpretation' =>'Kolor model',
'exif-orientation' =>'Orijentacija kadra',
'exif-samplesperpixel' =>'Broj kolor komponenata',
'exif-planarconfiguration' =>'Princip rasporeda podataka',
'exif-ycbcrsubsampling' =>'Omjer kompnente Y prema C',
'exif-ycbcrpositioning' =>'Razmještaj komponenata Y i C',
'exif-xresolution' =>'Vodoravna razlučivost',
'exif-yresolution' =>'Okomita razlučivost',
'exif-resolutionunit' =>'Jedinica razlučivosti',
'exif-stripoffsets' =>'Položaj bloka podataka',
'exif-rowsperstrip' =>'Broj redova u bloku',
'exif-stripbytecounts' =>'Veličina komprimiranog bloka',
'exif-jpeginterchangeformat' =>'Udaljenost JPEG previewa od početka datoteke',
'exif-jpeginterchangeformatlength' =>'Količina bajtova JPEG previewa',
'exif-transferfunction' =>'Funkcija preoblikovanja kolor prostora',
'exif-whitepoint' =>'Kromaticitet bijele točke',
'exif-primarychromaticities' =>'Kromaticitet primarnih boja',
'exif-ycbcrcoefficients' =>'Matrični koeficijenti preobrazbe kolor prostora',
'exif-referenceblackwhite' =>'Mjesto bijele i crne točke',
'exif-datetime' =>'Datum zadnje promjene datoteke',
'exif-imagedescription' =>'Ime slike',
'exif-make' =>'Proizvođač kamere',
'exif-model' =>'Model kamere',
'exif-software' =>'Korišteni softver',
'exif-artist' =>'Autor',
'exif-copyright' =>'Nositelj prava',
'exif-exifversion' =>'Exif verzija',
'exif-flashpixversion' =>'Podržana verzija Flashpixa',
'exif-colorspace' =>'Kolor prostor',
'exif-componentsconfiguration' =>'Značenje pojedinih komponenti',
'exif-compressedbitsperpixel' =>'Dubina boje poslije sažimanja',
'exif-pixelydimension' =>'Puna visina slike',
'exif-pixelxdimension' =>'Puna širina slike',
'exif-makernote' =>'Napomene proizvođača',
'exif-usercomment' =>'Suradnički komentar',
'exif-relatedsoundfile' =>'Povezani zvučni zapis',
'exif-datetimeoriginal' =>'Datum i vrijeme slikanja',
'exif-datetimedigitized' =>'Datum i vrijeme digitalizacije',
'exif-subsectime' =>'Dio sekunde u kojem je slikano',
'exif-subsectimeoriginal' =>'Dio sekunde u kojem je fotografirano',
'exif-subsectimedigitized' =>'Dio sekunde u kojem je digitalizirano',
'exif-exposuretime' =>'Ekspozicija',
'exif-fnumber' =>'F broj dijafragme',
'exif-exposureprogram' =>'Program ekspozicije',
'exif-spectralsensitivity' =>'Spektralna osjetljivost',
'exif-isospeedratings' =>'ISO vrijednost',
'exif-oecf' =>'Optoelektronski faktor konverzije',
'exif-shutterspeedvalue' =>'Brzina zatvarača',
'exif-aperturevalue' =>'Dijafragma',
'exif-brightnessvalue' =>'Osvijetljenost',
'exif-exposurebiasvalue' =>'Kompenzacija ekspozicije',
'exif-maxaperturevalue' =>'Minimalni broj dijafragme',
'exif-subjectdistance' =>'Udaljenost do objekta',
'exif-meteringmode' =>'Režim mjerača vremena',
'exif-lightsource' =>'Izvor svjetlosti',
'exif-flash' =>'Bljeskalica',
'exif-focallength' =>'Žarišna duljina leće',
'exif-subjectarea' =>'Položaj i površina objekta snimke',
'exif-flashenergy' =>'Energija bljeskalice',
'exif-spatialfrequencyresponse' =>'Prostorna frekvencijska karakteristika',
'exif-focalplanexresolution' =>'Vodoravna razlučivost žarišne ravnine',
'exif-focalplaneyresolution' =>'Okomita razlučivost žarišne ravnine',
'exif-focalplaneresolutionunit' =>'Jedinica razlučivosti žarišne ravnine',
'exif-subjectlocation' =>'Položaj subjekta',
'exif-exposureindex' =>'Indeks ekspozicije',
'exif-sensingmethod' =>'Tip senzora',
'exif-filesource' =>'Izvorna datoteka',
'exif-scenetype' =>'Tip scene',
'exif-cfapattern' =>'Tip kolor filtera',
'exif-customrendered' =>'Dodatna obrada slike',
'exif-exposuremode' =>'Režim izbora ekspozicije',
'exif-whitebalance' =>'Balans bijele',
'exif-digitalzoomratio' =>'Razmjer digitalnog zooma',
'exif-focallengthin35mmfilm' =>'Ekvivalent žarišne daljine za 35 mm film',
'exif-scenecapturetype' =>'Tip scene na snimci',
'exif-gaincontrol' =>'Kontrola osvijetljenosti',
'exif-contrast' =>'Kontrast',
'exif-saturation' =>'Zasićenje',
'exif-sharpness' =>'Oštrina',
'exif-devicesettingdescription' =>'Opis postavki uređaja',
'exif-subjectdistancerange' =>'Raspon udaljenosti subjekata',
'exif-imageuniqueid' =>'Jedinstveni identifikator slike',
'exif-gpsversionid' =>'Verzija bloka GPS-informacije',
'exif-gpslatituderef' =>'Sjeverna ili južna širina',
'exif-gpslatitude' =>'Širina',
'exif-gpslongituderef' =>'Istočna ili zapadna dužina',
'exif-gpslongitude' =>'Dužina',
'exif-gpsaltituderef' =>'Visina ispod ili iznad mora',
'exif-gpsaltitude' =>'Visina',
'exif-gpstimestamp' =>'Vrijeme po GPS-u (atomski sat)',
'exif-gpssatellites' =>'Korišteni sateliti',
'exif-gpsstatus' =>'Status prijemnika',
'exif-gpsmeasuremode' =>'Režim mjerenja',
'exif-gpsdop' =>'Preciznost mjerenja',
'exif-gpsspeedref' =>'Jedinica brzine',
'exif-gpsspeed' =>'Brzina GPS prijemnika',
'exif-gpstrackref' =>'Tip azimuta prijemnika (pravi ili magnetni)',
'exif-gpstrack' =>'Azimut prijemnika',
'exif-gpsimgdirectionref' =>'Tip azimuta slike (pravi ili magnetni)',
'exif-gpsimgdirection' =>'Azimut slike',
'exif-gpsmapdatum' =>'Korišteni geodetski koordinatni sustav',
'exif-gpsdestlatituderef' =>'Indeks zemlj. širine objekta',
'exif-gpsdestlatitude' =>'Zemlj. širina objekta',
'exif-gpsdestlongituderef' =>'Indeks zemlj. dužine objekta',
'exif-gpsdestlongitude' =>'Zemljopisna dužina objekta',
'exif-gpsdestbearingref' =>'Indeks pelenga objekta',
'exif-gpsdestbearing' =>'Peleng objekta',
'exif-gpsdestdistanceref' =>'Mjerne jedinice udaljenosti objekta',
'exif-gpsdestdistance' =>'Udaljenost objekta',
'exif-gpsprocessingmethod' =>'Ime metode obrade GPS podataka',
'exif-gpsareainformation' =>'Ime GPS područja',
'exif-gpsdatestamp' =>'GPS datum',
'exif-gpsdifferential' =>'GPS diferencijalna korekcija',

# Make & model, can be wikified in order to link to the camera and model name

'exif-make-value' => '$1',
'exif-model-value' =>'$1',
'exif-software-value' => '$1',

# Exif attributes

'exif-compression-1' => 'Nesažeto',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-1' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normalno', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Zrcaljeno po horizontali', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Zaokrenuto 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Zrcaljeno po vertikali', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Zaokrenuto 90° suprotno od sata i zrcaljeno po vertikali', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Zaokrenuto 90° u smjeru sata', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Zaokrenuto 90° u smjeru sata i zrcaljeno po vertikali', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Zaokrenuto 90° suprotno od sata', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

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
'exif-exposureprogram-3' => 'Prioritet dijafragme',
'exif-exposureprogram-4' => 'Prioritet zatvarača',
'exif-exposureprogram-5' => 'Umjetnički program (na temelju nužne dubine polja)',
'exif-exposureprogram-6' => 'Sportski program (na temelju što bržeg zatvarača)',
'exif-exposureprogram-7' => 'Portretni režim (za krupne planove s neoštrom pozadinom)',
'exif-exposureprogram-8' => 'Režim krajolika (za slike krajolika s oštrom pozadinom)',

'exif-subjectdistance-value' => '$1 metara',

'exif-meteringmode-0' => 'Nepoznato',
'exif-meteringmode-1' => 'Prosjek',
'exif-meteringmode-2' => 'Prosjek s težištem na sredini',
'exif-meteringmode-3' => 'Točka',
'exif-meteringmode-4' => 'Više točaka',
'exif-meteringmode-5' => 'Matrični',
'exif-meteringmode-6' => 'Djelomični',
'exif-meteringmode-255' => 'Drugo',

'exif-lightsource-0' => 'Nepoznato',
'exif-lightsource-1' => 'Dnevna svjetlost',
'exif-lightsource-2' => 'Fluorescentno',
'exif-lightsource-3' => 'Volframska žarulja',
'exif-lightsource-4' => 'Bljeskalica',
'exif-lightsource-9' => 'Lijepo vrijeme',
'exif-lightsource-10' => 'Oblačno vrijeme',
'exif-lightsource-11' => 'Sjena',
'exif-lightsource-12' => 'Fluorescentna svjetlost (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluorescentna svjetlost (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluorescentna svjetlost (W 3900 – 4500K)',
'exif-lightsource-15' => 'Bijela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardno svjetlo A',
'exif-lightsource-18' => 'Standardno svjetlo B',
'exif-lightsource-19' => 'Standardno svjetlo C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studijska svjetiljka',
'exif-lightsource-255' => 'Drugi izvor svjetla',

'exif-focalplaneresolutionunit-2' => 'inči',

'exif-sensingmethod-1' => 'Nedefinirano',
'exif-sensingmethod-2' => 'Jednokristalni matrični senzor',
'exif-sensingmethod-3' => 'Dvokristalni matrični senzor',
'exif-sensingmethod-4' => 'Trokristalni matrični senzor',
'exif-sensingmethod-5' => 'Sekvencijalni matrični senzor',
'exif-sensingmethod-7' => 'Trobojni linearni senzor',
'exif-sensingmethod-8' => 'Sekvencijalni linearni senzor',

'exif-filesource-3' => 'Digitalni fotoaparat',

'exif-scenetype-1' => 'Izravno fotografirana slika',

'exif-customrendered-0' => 'Normalni proces',
'exif-customrendered-1' => 'Nestadardni proces',

'exif-exposuremode-0' => 'Automatski',
'exif-exposuremode-1' => 'Ručno',
'exif-exposuremode-2' => 'Automatski sa zadanim rasponom',

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
'exif-subjectdistancerange-1' => 'Krupni plan',
'exif-subjectdistancerange-2' => 'Bliski plan',
'exif-subjectdistancerange-3' => 'Udaljeno',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Sjever',
'exif-gpslatitude-s' => 'Jug',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Istok',
'exif-gpslongitude-w' => 'Zapad',

'exif-gpsstatus-a' => 'Mjerenje u tijeku',
'exif-gpsstatus-v' => 'Spreman za prijenos',

'exif-gpsmeasuremode-2' => 'Dvodimenzionalno mjerenje',
'exif-gpsmeasuremode-3' => 'Trodimenzionalno mjerenje',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'kmh',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'čv',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravi sjever',
'exif-gpsdirection-m' => 'Magnetni sjever',

# external editor support
'edit-externally' => 'Uredi koristeći se vanjskom aplikacijom',
'edit-externally-help' => 'Vidi [http://meta.wikimedia.org/wiki/Help:External_editors setup upute] za više informacija.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sve',
'imagelistall' => 'sve',
'watchlistall1' => 'sve',
'watchlistall2' => 'sve',
'namespacesall' => 'sve',

# E-mail address confirmation
'confirmemail' => 'Potvrda e-mail adrese',
'confirmemail_text' => 'U ovom wikiju morate prije korištenja e-mail naredbi verificirati svoju e-mail adresu. Kliknite na dugme ispod kako biste
poslali poruku s potvrdom na vašu adresu. U poruci će biti poveznica koju morate otvoriti u
svom web pregledniku da biste verificirali adresu.',
'confirmemail_send' => 'Pošalji kôd za potvrdu e-mail adrese',
'confirmemail_sent' => 'Poruka s potvrdom je poslana.',
'confirmemail_sendfailed' => 'Poruka s potvrdom nije se mogla poslati. Provjerite pravilnost adrese.',
'confirmemail_invalid' => 'Pogrešna potvrda. Kod je možda istekao.',
'confirmemail_success' => 'Vaša je e-mail adresa potvrđena. Možete se prijaviti i uživati u wikiju.',
'confirmemail_loggedin' => 'Vaša je e-mail adresa potvrđena.',
'confirmemail_error' => 'Došlo je do greške kod snimanja vaše potvrde.',

'confirmemail_subject' => '{{SITENAME}}: potvrda e-mail adrese',
'confirmemail_body' => 'Vi ili netko drugi s IP adrese $1 ste otvorili
suradnički račun pod imenom "$2" s ovom e-mail adresom na Wikipediji.

Kako biste potvrdili da je ovaj suradnički račun uistinu vaš i
uključili e-mail naredbe na Wikipediji, otvorite u vašem
pregledniku sljedeću poveznicu:

$3

Ako ovo *niste* vi, nemojte otvarati poveznicu.

Valjanost ovog potvrdnog koda istječe $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Pokušaj naći točan pogodak',
'searchfulltext' => 'Traži po cjelokupnom tekstu',
'createarticle' => 'Stvori članak',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transkluzija isključena]',
'scarytranscludefailed' => '[Dobava predloška nije uspjela; $1; ispričavam se]',
'scarytranscludetoolong' => '[URL je predug; ispričavam se]',

# Trackbacks
'trackbackbox' => "<div id='mw_trackbacks'>
''Trackbackovi'' za ovaj članak:<br />
$1
</div>",
'trackback' => "; $4$5 : [$2 $1]",
'trackbackexcerpt' => "; $4$5 : [$2 $1]: <nowiki>$3</nowiki>",
'trackbackremove' => ' ([$1 izbrisati])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'Trackback izbrisan.',


# delete conflict

'deletedwhileediting' => 'Upozorenje: dok ste uređivali stranicu netko ju je izbrisao!',
'confirmrecreate' => 'Suradnik [[User:$1|$1]] ([[User talk:$1|talk]]) izbrisao je ovaj članak nakon što ste ga počeli uređivati. Razlog brisanja
: \'\'$2\'\'
Potvrdite namjeru vraćanja ovog članka.',
'recreate' => 'Vrati',
'tooltip-recreate' => '',

'unusedcategoriestext' => 'Na navedenim stranicama kategorija nema ni jednog članka ili potkategorije.',
'unit-pixel' => 'px',
'sitematrix' => 'Tablica Wikimedijinih projekata',
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageHr extends LanguageUtf8 {
	
	function getNamespaces() {
		global $wgNamespaceNamesHr;
		return $wgNamespaceNamesHr;
	}

	function getDateFormats() {
		return false;
	}

	function getQuickbarSettings() {
	 	global $wgQuickbarSettingsHr;
		return $wgQuickbarSettingsHr;
 	}

	function getSkinNames() {
		global $wgSkinNamesHr;
		return $wgSkinNamesHr;
 	}
	
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		$this->getMonthName( substr( $ts, 4, 2 ) ) .
		  " " .
		  substr( $ts, 0, 4 ) . "." ;
		return $d;
	}

	function getMessage( $key ) {
		global $wgAllMessagesHr;
		if( isset( $wgAllMessagesHr[$key] ) ) {
			return $wgAllMessagesHr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

 	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
 	}

 	function fallback8bitEncoding() {
		return "iso-8859-2";
 	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace ('.', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

}

?>
