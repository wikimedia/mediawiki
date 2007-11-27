<?php
/** Estonian (Eesti)
 *
 * @addtogroup Language
 *
 * @author WikedKentaur
 * @author SPQRobin
 * @author Nike
 * @author G - ג
 * @author Võrok
 * @author Siebrand
 */

$namespaceNames = array(
	NS_MEDIA            => 'Meedia',
	NS_SPECIAL          => 'Eri',
	NS_MAIN             => '',
	NS_TALK             => 'Arutelu',
	NS_USER             => 'Kasutaja',
	NS_USER_TALK        => 'Kasutaja_arutelu',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_arutelu',
	NS_IMAGE            => 'Pilt',
	NS_IMAGE_TALK       => 'Pildi_arutelu',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_arutelu',
	NS_TEMPLATE         => 'Mall',
	NS_TEMPLATE_TALK    => 'Malli_arutelu',
	NS_HELP             => 'Juhend',
	NS_HELP_TALK        => 'Juhendi_arutelu',
	NS_CATEGORY         => 'Kategooria',
	NS_CATEGORY_TALK    => 'Kategooria_arutelu'
);

$skinNames = array(
	'standard' => 'Standard',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Kölni sinine',
	'monobook' => 'MonoBook',
	'myskin' => 'Mu oma nahk'
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
	#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#redirect', "#suuna"    ),
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );
$linkTrail = "/^([a-z]+)(.*)\$/sD";

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
'tog-extendwatchlist'         => 'Laienda jälgimisloendit, et näha kõiki muudatusi',
'tog-usenewrc'                => 'Laiendatud viimased muudatused (mitte kõikide brauserite puhul)',
'tog-numberheadings'          => 'Pealkirjade automaatnummerdus',
'tog-showtoolbar'             => 'Redigeerimise tööriistariba näitamine',
'tog-editondblclick'          => 'Artiklite redigeerimine topeltklõpsu peale (JavaScript)',
'tog-editsection'             => '[redigeeri] lingid peatükkide muutmiseks',
'tog-editsectiononrightclick' => 'Peatükkide redigeerimine paremklõpsuga alampealkirjadel (JavaScript)',
'tog-showtoc'                 => 'Näita sisukorda (lehtedel, millel on rohkem kui 3 pealkirja)',
'tog-rememberpassword'        => 'Parooli meeldejätmine tulevasteks seanssideks',
'tog-editwidth'               => 'Redaktoriaknal on täislaius',
'tog-watchcreations'          => 'Lisa minu loodud lehed minu jälgimisloendisse',
'tog-watchdefault'            => 'Jälgi uusi ja muudetud artikleid',
'tog-minordefault'            => 'Märgi kõik parandused vaikimisi pisiparandusteks',
'tog-previewontop'            => 'Näita eelvaadet redaktoriakna ees, mitte järel',
'tog-previewonfirst'          => 'Näita eelvaadet esimesel redigeerimisel',
'tog-nocache'                 => 'Keela lehekülgede puhverdamine',
'tog-enotifwatchlistpages'    => 'Teata meili teel, kui minu jälgitavat artiklit muudetakse',
'tog-enotifusertalkpages'     => 'Teata meili teel, kui minu arutelu lehte muudetakse',
'tog-enotifminoredits'        => 'Teata meili teel ka pisiparandustest',
'tog-fancysig'                => 'Kasuta lihtsaid allkirju (ilma linkideta kasutajalehele)',
'tog-externaleditor'          => 'Kasuta vaikimisi välist redaktorit',
'tog-externaldiff'            => 'Kasuta vaikimisi välist võrdlusvahendit (diff)',
'tog-forceeditsummary'        => 'Nõua redigeerimisel resümee välja täitmist',

'underline-always'  => 'Alati',
'underline-never'   => 'Mitte kunagi',
'underline-default' => 'Brauseri vaikeväärtus',

'skinpreview' => '(Eelvaade)',

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

# Bits of text used by many pages
'categories'            => 'Kategooriad',
'pagecategories'        => '{{PLURAL:$1|Kategooria|Kategooriad}}',
'category_header'       => 'Artiklid kategooriast "$1"',
'subcategories'         => 'Allkategooriad',
'category-media-header' => 'Meediafailid kategooriast "$1"',
'category-empty'        => "''Selles kategoorias pole ühtegi artiklit ega meediafaili.''",

'mainpagetext'      => "<big>'''Wiki tarkvara installeeritud.'''</big>",
'mainpagedocfooter' => 'Juhiste saamiseks kasutamise ning konfigureerimise kohta vaata palun inglisekeelset [http://meta.wikimedia.org/wiki/MediaWiki_localisation dokumentatsiooni liidese kohaldamisest]
ning [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide kasutusjuhendit].',

'about'          => 'Tiitelandmed',
'article'        => 'artikkel',
'newwindow'      => '(avaneb uues aknas)',
'cancel'         => 'Tühista',
'qbfind'         => 'Otsi',
'qbbrowse'       => 'Sirvi',
'qbedit'         => 'Redigeeri',
'qbpageoptions'  => 'Lehekülje suvandid',
'qbpageinfo'     => 'Lehekülje andmed',
'qbmyoptions'    => 'Minu suvandid',
'qbspecialpages' => 'Erileheküljed',
'moredotdotdot'  => 'Veel...',
'mypage'         => 'Minu lehekülg',
'mytalk'         => 'Minu arutelu',
'anontalk'       => 'Arutelu selle IP jaoks',
'navigation'     => 'Navigeerimine',

'errorpagetitle'    => 'Viga',
'returnto'          => 'Naase $1 juurde',
'tagline'           => 'Allikas: {{SITENAME}}',
'help'              => 'Juhend',
'search'            => 'Otsi',
'searchbutton'      => 'Otsi',
'go'                => 'Mine',
'searcharticle'     => 'Mine',
'history'           => 'Artikli ajalugu',
'history_short'     => 'Ajalugu',
'info_short'        => 'Info',
'printableversion'  => 'Prinditav versioon',
'permalink'         => 'Püsilink',
'print'             => 'Prindi',
'edit'              => 'redigeeri',
'editthispage'      => 'Redigeeri seda artiklit',
'delete'            => 'kustuta',
'deletethispage'    => 'Kustuta see artikkel',
'undelete_short'    => 'Taasta {{PLURAL:$1|üks muudatus|$1 muudatust}}',
'protect'           => 'Kaitse',
'protectthispage'   => 'Kaitse seda artiklit',
'unprotect'         => 'Ära kaitse',
'unprotectthispage' => 'Ära kaitse seda artiklit',
'newpage'           => 'Uus artikkel',
'talkpage'          => 'Selle artikli arutelu',
'talkpagelinktext'  => 'Arutelu',
'specialpage'       => 'Erilehekülg',
'personaltools'     => 'Personaalsed tööriistad',
'postcomment'       => 'Postita kommentaar',
'articlepage'       => 'Artiklilehekülg',
'talk'              => 'Arutelu',
'views'             => 'vaatamisi',
'toolbox'           => 'Tööriistakast',
'userpage'          => 'Kasutajalehekülg',
'projectpage'       => 'Metalehekülg',
'imagepage'         => 'Pildilehekülg',
'templatepage'      => 'Mallilehekülg',
'categorypage'      => 'Kategoorialehekülg',
'viewtalkpage'      => 'Arutelulehekülg',
'otherlanguages'    => 'Teised keeled',
'redirectedfrom'    => '(Ümber suunatud artiklist $1)',
'redirectpagesub'   => 'Ümbersuunamisleht',
'lastmodifiedat'    => 'Viimane muutmine: $2, $1', # $1 date, $2 time
'viewcount'         => 'Seda lehekülge on külastatud {{plural:$1|üks kord|$1 korda}}.',
'protectedpage'     => 'Kaitstud artikkel',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} tiitelandmed',
'aboutpage'         => '{{ns:project}}:Tiitelandmed',
'bugreports'        => 'Teated programmivigadest',
'bugreportspage'    => '{{ns:project}}:Teated_programmivigadest',
'copyright'         => 'Kogu tekst on kasutatav litsentsi $1 tingimustel.',
'copyrightpagename' => '{{SITENAME}} ja autoriõigused',
'copyrightpage'     => '{{ns:project}}:Autoriõigused',
'currentevents'     => 'Sündmused maailmas',
'currentevents-url' => 'Sündmused maailmas',
'disclaimers'       => 'Hoiatused',
'disclaimerpage'    => 'Project:Hoiatused',
'edithelp'          => 'Redigeerimisjuhend',
'edithelppage'      => '{{ns:Project}}:Kuidas_lehte_redigeerida',
'faq'               => 'KKK',
'faqpage'           => '{{ns:project}}:KKK',
'helppage'          => '{{ns:12}}:Juhend',
'mainpage'          => 'Esileht',
'policy-url'        => 'Project:policy',
'portal'            => 'Kogukonnavärav',
'portal-url'        => '{{ns:project}}:Kogukonnavärav',
'privacy'           => 'Privaatsus',
'privacypage'       => 'Project:Privaatsus',
'sitesupport'       => 'Annetused',

'badaccess' => 'Õigus puudub',

'retrievedfrom'       => 'Välja otsitud andmebaasist "$1"',
'youhavenewmessages'  => 'Teile on $1 ($2).',
'newmessageslink'     => 'uusi sõnumeid',
'newmessagesdifflink' => 'erinevus eelviimasest redaktsioonist',
'editsection'         => 'redigeeri',
'editold'             => 'redigeeri',
'editsectionhint'     => 'Redigeeri alaosa $1',
'toc'                 => 'Sisukord',
'showtoc'             => 'näita',
'hidetoc'             => 'peida',
'thisisdeleted'       => 'Vaata või taasta $1?',
'viewdeleted'         => 'Vaata lehekülge $1?',
'restorelink'         => '{{PLURAL:$1|üks kustutatud versioon|$1 kustutatud versiooni}}',
'feedlinks'           => 'Sööde:',

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
'nosuchactiontext'  => 'Wiki ei tunne sellele aadressile vastavat toimingut.',
'nosuchspecialpage' => 'Sellist erilehekülge pole.',
'nospecialpagetext' => 'Viki ei tunne sellist erilehekülge.',

# General errors
'error'                => 'Viga',
'databaseerror'        => 'Andmebaasi viga',
'dberrortext'          => 'Andmebaasipäringus oli süntaksiviga.
Otsingupäring oli ebakorrektne (vaata $5) või on tarkvaras viga.
Viimane andmebaasipäring oli:
<blockquote><tt>$1</tt></blockquote>
ja see kutsuti funktsioonist "<tt>$2</tt>".
MySQL andis vea "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Andmebaasipäringus oli süntaksiviga.
Viimane andmebaasipäring oli:
"$1"
ja see kutsuti funktsioonist "$2".
MySQL andis vea "$3: $4".',
'noconnect'            => 'Vabandame! Vikil on tehnilisi probleeme ning ta ei saa andmebaasiserveriga $1 ühendust.',
'nodb'                 => 'Andmebaasi $1 ei õnnestunud kätte saada',
'cachederror'          => 'Järgnev tekst pärineb serveri vahemälust ega pruugi olla lehekülje viimane versioon.',
'readonly'             => 'Andmebaas on hetkel kirjutuskaitse all',
'enterlockreason'      => 'Sisesta lukustamise põhjus ning juurdepääsu taastamise ligikaudne aeg',
'readonlytext'         => 'Andmebaas on praegu kirjutuskaitse all, tõenäoliselt andmebaasi rutiinseks hoolduseks, mille lõppedes normaalne olukord taastub.
Administraator, kes selle kaitse alla võttis, andis järgmise selgituse:
<p>$1',
'missingarticle'       => 'Andmebaas ei leidnud lehekülje "$1" teksti, kuigi see oleks pidanud olema leitav. 

<p>Tavaliselt on selle põhjuseks vananenud erinevuste- või ajaloolink leheküljele, mis on kustutatud. 

<p>Kui ei ole tegemist sellise juhtumiga, siis võib olla tegemist tarkvaraveaga. Palun teatage sellest administraatorile, märkides ära aadressi.',
'internalerror'        => 'Sisemine viga',
'filecopyerror'        => 'Ei saanud faili "$1" kopeerida nimega "$2".',
'filerenameerror'      => 'Ei saanud faili "$1" failiks "$2" ümber nimetada.',
'filedeleteerror'      => 'Faili nimega "$1" ei ole võimalik kustutada.',
'filenotfound'         => 'Faili nimega "$1" ei leitud.',
'unexpected'           => 'Ootamatu väärtus: "$1"="$2".',
'formerror'            => 'Viga: vormi ei saanud salvestada',
'badarticleerror'      => 'Seda toimingut ei saa sellel leheküljel sooritada.',
'cannotdelete'         => 'Seda lehekülge või pilti ei ole võimalik kustutada. (Võib-olla keegi teine juba kustutas selle.)',
'badtitle'             => 'Vigane pealkiri',
'badtitletext'         => 'Küsitud artiklipealkiri oli kas vigane, tühi või siis
valesti viidatud keelte- või wikidevaheline pealkiri.',
'perfdisabled'         => 'Vabandage! See funktsioon ajutiselt ei tööta, sest ta aeglustab andmebaasi kasutamist võimatuseni. Sellepärast täiustatakse vastavat programmi lähitulevikus. Võib-olla teete seda Teie!',
'perfcached'           => 'Järgnevad andmed on puhverdatud ja ei pruugi olla kõige värskemad:',
'perfcachedts'         => 'Järgmised andmed on vahemälus. Viimase uuendamise daatum on $1.',
'wrong_wfQuery_params' => 'Valed parameeterid funktsioonile wfQuery()<br />
Funktsioon: $1<br />
Päring: $2',
'viewsource'           => 'Vaata lähteteksti',
'viewsourcefor'        => '$1',
'protectedinterface'   => 'Sellel leheküljel on tarkvara kasutajaliidese tekst. Kuritahtliku muutmise vältimiseks on lehekülg lukustatud.',
'editinginterface'     => "'''Hoiatus:''' Te redigeerite tarkvara kasutajaliidese tekstiga lehekülge. Muudatused siin mõjutavad kõikide kasutajate kasutajaliidest.",

# Login and logout pages
'logouttitle'                => 'Väljalogimine',
'logouttext'                 => 'Te olete välja loginud.
Võite kasutada süsteemi anonüümselt, aga ka sama või mõne teise kasutajana uuesti sisse logida.',
'welcomecreation'            => '<h2>Tere tulemast, $1!</h2><p>Teie konto on loodud. Ärge unustage seada oma eelistusi.',
'loginpagetitle'             => 'Sisselogimine',
'yourname'                   => 'Teie kasutajanimi',
'yourpassword'               => 'Teie parool',
'yourpasswordagain'          => 'Sisestage parool uuesti',
'remembermypassword'         => 'Parooli meeldejätmine tulevasteks seanssideks.',
'loginproblem'               => '<b>Sisselogimine ei õnnestunud.</b><br />Proovige uuesti!',
'login'                      => 'Logi sisse',
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
'userexists'                 => 'Sisestatud kasutajanimi on juba kasutusel. Valige uus nimi.',
'youremail'                  => 'Teie e-posti aadress*',
'username'                   => 'Kasutajanimi:',
'uid'                        => 'Kasutaja ID:',
'yourrealname'               => 'Teie tegelik nimi*',
'yourlanguage'               => 'Keel:',
'yournick'                   => 'Teie hüüdnimi (allakirjutamiseks)',
'email'                      => 'E-post',
'prefs-help-realname'        => '* <strong>Tegelik nimi</strong> (pole kohustuslik): kui otsustate selle avaldada, kasutatakse seda Teie kaastöö seostamiseks Teiega.<br />',
'loginerror'                 => 'Viga sisselogimisel',
'prefs-help-email'           => '* <strong>E-post</strong> (pole kohustuslik): Võimaldab inimestel Teiega veebisaidi kaudu ühendust võtta, ilma et Te peaksite neile oma meiliaadressi avaldama, samuti on sellest kasu, kui unustate parooli.',
'nocookiesnew'               => 'Kasutajakonto loodi, aga sa ei ole sisse logitud, sest {{SITENAME}} kasutab kasutajate tuvastamisel küpsiseid. Sinu brauseris on küpsised keelatud. Palun sea küpsised lubatuks ja logi siis oma vastse kasutajanime ning parooliga sisse.',
'nocookieslogin'             => '{{SITENAME}} kasutab kasutajate tuvastamisel küpsiseid. Sinu brauseris on küpsised keelatud. Palun sea küpsised lubatuks ja proovi siis uuesti.',
'noname'                     => 'Sa ei sisestanud kasutajanime lubataval kujul.',
'loginsuccesstitle'          => 'Sisselogimine õnnestus',
'loginsuccess'               => 'Te olete sisse loginud. Teie kasutajanimi on "$1".',
'nosuchuser'                 => 'Kasutajat nimega "$1" ei ole olemas. Kontrollige kirjapilti või kasutage alljärgnevat vormi uue kasutajakonto loomiseks.',
'nosuchusershort'            => 'Kasutajat nimega "$1" ei ole olemas. Kontrollige kirjapilti.',
'wrongpassword'              => 'Vale parool. Proovige uuesti.',
'passwordtooshort'           => 'Sisestatud parool on vigane või liiga lühike. See peab koosnema vähemalt $1 tähemärgist ning peab erinema kasutajanimest.',
'mailmypassword'             => 'Saada mulle meili teel uus parool',
'passwordremindertitle'      => '{{SITENAME}} - unustatud salasõna',
'passwordremindertext'       => 'Keegi (tõenäoliselt Teie, IP-aadressilt $1),
palus, et me saadaksime Teile uue parooli süsteemi sisselogimiseks ($4).
Kasutaja "$2" parool on nüüd "$3".
Võiksid sisse logida ja selle ajutise parooli ära muuta.

Sinu {{SITENAME}}.',
'noemail'                    => 'Kasutaja "$1" meiliaadressi meil kahjuks pole.',
'passwordsent'               => 'Uus parool on saadetud kasutaja "$1" registreeritud meiliaadressil.
Pärast parooli saamist logige palun sisse.',
'mailerror'                  => 'Viga kirja saatmisel: $1',
'acct_creation_throttle_hit' => 'Vabandame, aga te olete loonud juba $1 kontot. Rohkem te ei saa.',
'emailauthenticated'         => 'Sinu e-posti aadress kinnitati $1.',
'emailnotauthenticated'      => 'Sinu e-posti aadress <strong>pole veel kinnitatud</strong>. E-posti kinnitamata aadressile ei saadeta.',
'noemailprefs'               => 'Järgnevate võimaluste toimimiseks on vaja sisestada e-posti aadress.',
'emailconfirmlink'           => 'Kinnita oma e-posti aadress',

# Edit page toolbar
'bold_sample'     => 'Rasvane kiri',
'bold_tip'        => 'Rasvane kiri',
'italic_sample'   => 'Kaldkiri',
'italic_tip'      => 'Kaldkiri',
'link_sample'     => 'Lingitav pealkiri',
'link_tip'        => 'Siselink',
'extlink_sample'  => 'http://www.välislink.ee Lingi nimi',
'extlink_tip'     => 'Välislink (ärge unustage kasutada http:// eesliidet)',
'headline_sample' => 'Pealkiri',
'headline_tip'    => '2. taseme pealkiri',
'math_sample'     => 'Sisesta valem siia',
'math_tip'        => 'Matemaatiline valem (LaTeX)',
'nowiki_sample'   => 'Sisesta formaatimata tekst',
'nowiki_tip'      => 'Ignoreeri viki vormindust',
'image_sample'    => 'Näidis.jpg',
'image_tip'       => 'Pilt',
'media_sample'    => 'Näidis.mp3',
'media_tip'       => 'Link meediafailile',
'sig_tip'         => 'Sinu signatuur kuupäeva ja kellaajaga',
'hr_tip'          => 'Horisontaalkriips (kasuta säästlikult)',

# Edit pages
'summary'                  => 'Resümee',
'subject'                  => 'Kommentaari pealkiri',
'minoredit'                => 'See on pisiparandus',
'watchthis'                => 'Jälgi seda artiklit',
'savearticle'              => 'Salvesta',
'preview'                  => 'Eelvaade',
'showpreview'              => 'Näita eelvaadet',
'showlivepreview'          => 'Näita eelvaadet',
'showdiff'                 => 'Näita muudatusi',
'anoneditwarning'          => 'Te ei ole sisse logitud. Selle lehe redigeerimislogisse salvestatakse Teie IP-aadress.',
'summary-preview'          => 'Resümee eelvaade',
'blockedtitle'             => 'Kasutaja on blokeeritud',
'blockedtext'              => "Teie kasutajanime või IP-aadressi blokeeris $1.
Tema põhjendus on järgmine:<br />''$2''<p>Küsimuse arutamiseks võite pöörduda $1 või mõne teise
[[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pange tähele, et Te ei saa sellele kasutajale teadet saata, kui Te pole registreerinud oma [[Special:Eelistused|eelistuste lehel]] kehtivat e-posti aadressi.

Teie IP on $3. Lisage see aadress kõigile järelpärimistele, mida kavatsete teha.",
'whitelistedittitle'       => 'Redigeerimiseks tuleb sisse logida',
'whitelistedittext'        => 'Lehekülgede toimetamiseks peate [[Special:Userlogin|sisse logima]].',
'whitelistreadtitle'       => 'Lugemiseks peate olema sisse logitud',
'whitelistreadtext'        => 'Lehekülgede lugemiseks peate [[Special:Userlogin|sisse logima]].',
'whitelistacctitle'        => 'Teil pole õigust kasutajakontot luua',
'whitelistacctext'         => 'Et selles Vikis kontosid luua, peate olema [[Special:Userlogin|sisse logitud]] ja omama vastavaid õigusi.',
'loginreqtitle'            => 'Vajalik on sisselogimine',
'loginreqlink'             => 'sisse logima',
'loginreqpagetext'         => 'Lehekülgede vaatamiseks peate $1.',
'accmailtitle'             => 'Parool saadetud.',
'accmailtext'              => "Kasutaja '$1' parool saadeti aadressile $2.",
'newarticle'               => '(Uus)',
'newarticletext'           => "Seda lehekülge veel ei ole.
Lehekülje loomiseks hakake kirjutama all olevasse tekstikasti
(lisainfo saamiseks vaadake [[{{MediaWiki:Helppage}}|juhendit]]).
Kui sattusite siia kogemata, klõpsake lihtsalt brauseri ''back''-nupule või lingile ''tühista''.",
'anontalkpagetext'         => "---- ''See on arutelulehekülg anonüümse kasutaja kohta, kes ei ole loonud kontot või ei kasuta seda. Sellepärast tuleb meil kasutaja identifitseerimiseks kasutada tema IP-aadressi. See IP-aadress võib olla mitmele kasutajale ühine. Kui olete anonüümne kasutaja ning leiate, et kommentaarid sellel leheküljel ei ole mõeldud Teile, siis palun [[Special:Userlogin|looge konto või logige sisse]], et edaspidi arusaamatusi vältida.''",
'noarticletext'            => "<div style=\"border: 1px solid #ccc; padding: 7px; background-color: #fff; color: #000\">
'''Sellise pealkirjaga lehekülge ei ole.'''
* <span class=\"plainlinks\">'''[{{fullurl:{{FULLPAGENAME}}|action=edit}} Alusta seda lehekülge]''' või</span>
* <span class=\"plainlinks\">[[{{ns:special}}:Search/{{PAGENAMEE}}|Otsi väljendit \"{{PAGENAME}}]]\" teistest artiklitest või</span>
* [[Special:Whatlinkshere/{{NAMESPACE}}:{{PAGENAMEE}}|Vaata lehekülgi, mis siia viitavad]].
</div>",
'clearyourcache'           => "'''Märkus:''' Pärast salvestamist pead sa muudatuste nägemiseks oma brauseri puhvri tühjendama: '''Mozilla:''' ''ctrl-shift-r'', '''IE:''' ''ctrl-f5'', '''Safari:''' ''cmd-shift-r'', '''Konqueror''' ''f5''.",
'usercssjsyoucanpreview'   => "<strong>Vihje:</strong> Kasuta nuppu 'Näita eelvaadet' oma uue css/js testimiseks enne salvestamist.",
'usercsspreview'           => "'''Ärge unustage, et seda versiooni teie isiklikust stiililehest pole veel salvestatud!'''",
'userjspreview'            => "'''Ärge unustage, et see versioon teie isiklikust javascriptist on alles salvestamata!'''",
'updated'                  => '(Värskendatud)',
'note'                     => '<strong>Meeldetuletus:</strong>',
'previewnote'              => '<strong>Ärge unustage, et see versioon ei ole veel salvestatud!</strong>',
'previewconflict'          => 'See eelvaade näitab, kuidas ülemises toimetuskastis olev tekst hakkab välja nägema, kui otsustate salvestada.',
'editing'                  => 'Redigeerimisel on $1',
'editinguser'              => 'Redigeerimisel on $1',
'editingsection'           => 'Redigeerimisel on osa leheküljest $1',
'editingcomment'           => 'Lisamisel on $1 kommentaar',
'editconflict'             => 'Redigeerimiskonflikt: $1',
'explainconflict'          => 'Keegi teine on muutnud seda lehekülge pärast seda, kui Teie seda redigeerima hakkasite.
Ülemine toimetuskast sisaldab teksti viimast versiooni.
Teie muudatused on alumises kastis.
Teil tuleb need viimasesse versiooni üle viia.
Kui Te klõpsate nupule
 "Salvesta", siis salvestub <b>ainult</b> ülemises toimetuskastis olev tekst.<br />',
'yourtext'                 => 'Teie tekst',
'storedversion'            => 'Salvestatud redaktsioon',
'editingold'               => '<strong>ETTEVAATUST! Te redigeerite praegu selle lehekülje vana redaktsiooni.
Kui Te selle salvestate, siis lähevad kõik vahepealsed muudatused kaduma.</strong>',
'yourdiff'                 => 'Erinevused',
'copyrightwarning'         => "Pidage silmas, et kõik {{SITENAME}}'le tehtud kaastööd loetakse avaldatuks vastavalt $2 (vaata ka $1). Kui Te ei soovi, et Teie poolt kirjutatut halastamatult redigeeritakse ja omal äranägemisel kasutatakse, siis ärge seda siia salvestage.<br />
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast.<br />
<strong>ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!</strong>",
'copyrightwarning2'        => "Pidage silmas, et kõiki {{SITENAME}}'le tehtud kaastöid võidakse muuta või kustutada teiste kaastööliste poolt. Kui Te ei soovi, et Teie poolt kirjutatut halastamatult redigeeritakse, siis ärge seda siia salvestage.<br />
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast (vaata ka $1).<br />
<strong>ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!</strong>",
'longpagewarning'          => '<strong>HOIATUS: Selle lehekülje pikkus ületab $1 kilobaiti. Mõne brauseri puhul valmistab raskusi juba 32-le kilobaidile läheneva pikkusega lehekülgede redigeerimine. Palun kaaluge selle lehekülje sisu jaotamist lühemate lehekülgede vahel.</strong>',
'readonlywarning'          => '<strong>HOIATUS: Andmebaas on lukustatud hooldustöödeks, nii et praegu ei saa parandusi salvestada. Võite teksti alal hoida tekstifailina ning salvestada hiljem.</strong>',
'protectedpagewarning'     => '<strong>HOIATUS: See lehekülg on lukustatud, nii et seda saavad redigeerida ainult administraatori õigustega kasutajad.</strong>',
'semiprotectedpagewarning' => "'''Märkus:''' See lehekülg on lukustatud nii, et üksnes registreeritud kasutajad saavad seda muuta.",
'templatesused'            => 'Sellel lehel on kasutusel järgnevad mallid:',
'recreate-deleted-warn'    => "'''Hoiatus: Te loote uuesti lehte, mis on varem kustutatud.'''

Kaaluge, kas lehe uuesti loomine on kohane.
Lehe eelnevad kustutamised:",

# History pages
'revhistory'          => 'Redigeerimislugu',
'viewpagelogs'        => 'Vaata selle lehe logisid',
'nohistory'           => 'Sellel leheküljel ei ole eelmisi redaktsioone.',
'revnotfound'         => 'Redaktsiooni ei leitud',
'revnotfoundtext'     => 'Teie poolt päritud vana redaktsiooni ei leitud.
Palun kontrollige aadressi, millel Te seda lehekülge leida püüdsite.',
'loadhist'            => 'Lehekülje ajaloo laadimine',
'currentrev'          => 'Viimane redaktsioon',
'revisionasof'        => 'Redaktsioon: $1',
'previousrevision'    => '←Vanem redaktsioon',
'nextrevision'        => 'Uuem redaktsioon→',
'currentrevisionlink' => 'vaata viimast redaktsiooni',
'cur'                 => 'viim',
'next'                => 'järg',
'last'                => 'eel',
'page_first'          => 'esimene',
'page_last'           => 'viimane',
'histlegend'          => 'Märgi versioonid, mida tahad võrrelda ja vajuta võrdlemisnupule.
Legend: (viim) = erinevused võrreldes viimase redaktsiooniga,
(eel) = erinevused võrreldes eelmise redaktsiooniga, P = pisimuudatus',
'deletedrev'          => '[kustutatud]',
'histfirst'           => 'Esimesed',
'histlast'            => 'Viimased',
'historysize'         => '($1 baiti)',
'historyempty'        => '(tühi)',

# Diffs
'difference'                => '(Erinevused redaktsioonide vahel)',
'loadingrev'                => 'Redaktsiooni laadimine erinevustelehekülje jaoks',
'lineno'                    => 'Rida $1:',
'editcurrent'               => 'Redigeeri selle lehekülje viimast redaktsiooni',
'selectnewerversionfordiff' => 'Vali uuem versioon, et võrrelda',
'selectolderversionfordiff' => 'Vali vanem versioon, et võrrelda',
'compareselectedversions'   => 'Võrdle valitud redaktsioone',

# Search results
'searchresults'         => 'Otsingu tulemused',
'searchresulttext'      => 'Lisainfot otsimise kohta vaata $1.',
'searchsubtitle'        => 'Päring "[[:$1]]"',
'searchsubtitleinvalid' => 'Päring "$1"',
'noexactmatch'          => "'''Artiklit pealkirjaga \"\$1\" ei leitud.''' Võite [[:\$1|selle artikli luua]].",
'titlematches'          => 'Vasted artikli pealkirjades',
'notitlematches'        => 'Artikli pealkirjades otsitavat ei leitud',
'textmatches'           => 'Vasted artikli tekstides',
'notextmatches'         => 'Artikli tekstides otsitavat ei leitud',
'prevn'                 => 'eelmised $1',
'nextn'                 => 'järgmised $1',
'viewprevnext'          => 'Näita ($1) ($2) ($3).',
'showingresults'        => 'Allpool näitame <b>$1</b> tulemit alates tulemist #<b>$2</b>.',
'nonefound'             => '<strong>Märkus</strong>: otsingute ebaõnnestumise sagedaseks põhjuseks on asjaolu,
et väga sageli esinevaid sõnu ei võta süsteem otsimisel arvesse. Teine põhjus võib olla
mitme otsingusõna kasutamine (tulemusena ilmuvad ainult leheküljed, mis sisaldavad kõiki otsingusõnu).',
'powersearch'           => 'Otsi',
'powersearchtext'       => '
Otsing nimeruumidest :<br />
$1<br />
$2 Loetle ümbersuunamisi &nbsp; Otsi $3 $9',
'searchdisabled'        => "<p>Vabandage! Otsing vikist on ajutiselt peatatud, et säilitada muude teenuste normaalne töökiirus. Otsimiseks võite kasutada allpool olevat Google'i otsinguvormi, kuid sellelt saadavad tulemused võivad olla vananenud.</p>",

# Preferences page
'preferences'             => 'Minu eelistused',
'mypreferences'           => 'minu eelistused',
'prefsnologin'            => 'Te ei ole sisse loginud',
'prefsnologintext'        => 'Et oma eelistusi seada, [[Special:Userlogin|tuleb Teil]]
sisse logida.',
'prefsreset'              => 'Teie eelistused on arvutimälu järgi taastatud.',
'qbsettings'              => 'Kiirriba sätted',
'qbsettings-none'         => 'Ei_ole',
'qbsettings-fixedleft'    => 'Püsivalt_vasakul',
'qbsettings-fixedright'   => 'Püsivalt paremal',
'qbsettings-floatingleft' => 'Ujuvalt vasakul',
'changepassword'          => 'Muuda parool',
'skin'                    => 'Kujundus',
'math'                    => 'Valemite näitamine',
'dateformat'              => 'Kuupäeva formaat',
'datedefault'             => 'Eelistus puudub',
'datetime'                => 'Kuupäev ja kellaaeg',
'math_failure'            => 'Arusaamatu süntaks',
'math_unknown_error'      => 'Tundmatu viga',
'math_unknown_function'   => 'Tundmatu funktsioon',
'math_lexing_error'       => 'Väljalugemisviga',
'math_syntax_error'       => 'Süntaksiviga',
'prefs-personal'          => 'Kasutaja andmed',
'prefs-rc'                => 'Viimaste muudatuste kuvamine',
'prefs-watchlist'         => 'Jälgimisloend',
'prefs-watchlist-days'    => 'Mitme päeva muudatusi näidata loendis:',
'prefs-watchlist-edits'   => 'Mitu muudatust näidatakse laiendatud jälgimisloendis:',
'prefs-misc'              => 'Muud seaded',
'saveprefs'               => 'Salvesta eelistused',
'resetprefs'              => 'Lähtesta eelistused',
'oldpassword'             => 'Vana parool',
'newpassword'             => 'Uus parool',
'retypenew'               => 'Sisestage uus parool uuesti',
'textboxsize'             => 'Redigeerimisseaded',
'rows'                    => 'Redaktoriakna ridade arv:',
'columns'                 => 'Veergude arv',
'searchresultshead'       => 'Otsingutulemite sätted',
'resultsperpage'          => 'Tulemeid leheküljel',
'contextlines'            => 'Ridu tulemis',
'contextchars'            => 'Konteksti pikkus real',
'recentchangescount'      => 'Pealkirjade arv viimastes muudatustes',
'savedprefs'              => 'Teie eelistused on salvestatud.',
'timezonelegend'          => 'Ajavöönd',
'timezonetext'            => 'Kohaliku aja ja serveri aja (maailmaaja) vahe tundides.',
'localtime'               => 'Kohalik aeg',
'timezoneoffset'          => 'Ajavahe',
'servertime'              => 'Serveri aeg',
'guesstimezone'           => 'Loe aeg brauserist',
'allowemail'              => 'Luba teistel kasutajatel mulle e-posti saata',
'defaultns'               => 'Vaikimisi otsi järgmistest nimeruumidest:',
'default'                 => 'vaikeväärtus',
'files'                   => 'Failid',

# User rights
'userrights-lookup-user'     => 'Muuda kasutajagruppi',
'userrights-user-editname'   => 'Sisesta kasutajatunnus:',
'editusergroup'              => 'Muuda kasutajagruppi',
'userrights-editusergroup'   => 'Kasutajagrupi valik',
'saveusergroups'             => 'Salvesta grupi muudatused',
'userrights-groupsmember'    => 'Kuulub gruppi:',
'userrights-groupsavailable' => 'Võimalik lisada gruppidesse:',
'userrights-groupshelp'      => 'Vali grupid, millest sa tahad kasutajat eemaldada või millesse kasutajat lisada.
Valimata jäetud gruppe ei muudeta. Grupi valikut saab tühistada CTRL + parem kliki abil.',

# Groups
'group'            => 'Grupp:',
'group-bot'        => 'Botid',
'group-sysop'      => 'Administraatorid',
'group-bureaucrat' => 'Bürokraadid',
'group-all'        => '(kõik)',

'group-sysop-member'      => 'Administraator',
'group-bureaucrat-member' => 'Bürokraat',

'grouppage-sysop' => '{{ns:project}}:administraatorid',

# User rights log
'rightslogtext' => 'See on logi kasutajate õiguste muutuste kohta.',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|muudatus|muudatust}}',
'recentchanges'                     => 'Viimased muudatused',
'recentchangestext'                 => 'Jälgige sellel leheküljel viimaseid muudatusi.',
'rcnote'                            => "Allpool on esitatud viimased '''$1''' muudatust viimase '''$2''' päeva jooksul.",
'rcnotefrom'                        => 'Allpool on esitatud muudatused alates <b>$2</b> (näidatakse kuni <b>$1</b> muudatust).',
'rclistfrom'                        => 'Näita muudatusi alates $1',
'rcshowhideminor'                   => '$1 pisiparandused',
'rcshowhidebots'                    => '$1 robotid',
'rcshowhideliu'                     => '$1 sisseloginud kasutajad',
'rcshowhideanons'                   => '$1 anonüümsed kasutajad',
'rcshowhidemine'                    => '$1 minu parandused',
'rclinks'                           => 'Näita viimast $1 muudatust viimase $2 päeva jooksul<br />$3',
'diff'                              => 'erin',
'hist'                              => 'ajal',
'hide'                              => 'peida',
'show'                              => 'näita',
'minoreditletter'                   => 'P',
'newpageletter'                     => 'U',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|jälgiv kasutaja|jälgivat kasutajat}}]',

# Recent changes linked
'recentchangeslinked' => 'Seotud muudatused',

# Upload
'upload'               => 'Faili üleslaadimine',
'uploadbtn'            => 'Lae fail',
'reupload'             => 'Uuesti üleslaadimine',
'reuploaddesc'         => 'Tagasi üleslaadimise vormi juurde.',
'uploadnologin'        => 'sisse logimata',
'uploadnologintext'    => 'Kui Te soovite faile üles laadida, peate [[Special:Userlogin|sisse logima]].',
'uploaderror'          => 'Faili laadimine ebaõnnestus',
'uploadtext'           => '<strong>STOPP!</strong> Enne kui sooritad üleslaadimise,
peaksid tagama, et see järgib siinset [[{{MediaWiki:Policy-url}}|piltide kasutamise korda]].

Et näha või leida eelnevalt üleslaetud pilte,
mine vaata [[Special:Imagelist|piltide nimekirja]].
Üleslaadimised ning kustutamised logitakse [[Special:Log/upload|üleslaadimise logis]].

Järgneva vormi abil saad laadida üles uusi pilte
oma artiklite illustreerimiseks.
Enamikul brauseritest, näed nuppu "Browse...", mis viib sind
sinu operatsioonisüsteemi standardsesse failiavamisaknasse.
Faili valimisel sisestatakse selle faili nimi tekstiväljale
nupu kõrval.
Samuti pead märgistama kastikese, kinnitades sellega,
et sa ei riku seda faili üleslaadides kellegi autoriõigusi.
Üleslaadimise lõpuleviimiseks vajuta nupule "Üleslaadimine".
See võib võtta pisut aega, eriti kui teil on aeglane internetiühendus.

Eelistatud formaatideks on fotode puhul JPEG , joonistuste
ja ikoonilaadsete piltide puhul PNG, helide jaoks aga OGG.
Nimeta oma failid palun nõnda, et nad kirjeldaksid arusaadaval moel faili sisu, see aitab segadusi vältida.
Pildi lisamiseks artiklile, kasuta linki kujul:
<b><nowiki>[[</nowiki>{{ns:image}}<nowiki>:pilt.jpg]]</nowiki></b> või <b><nowiki>[[</nowiki>{{ns:image}}<nowiki>:pilt.png|alt. tekst]]</nowiki></b>.
Helifaili puhul: <b><nowiki>[[</nowiki>{{ns:media}}<nowiki>:fail.ogg]]</nowiki></b>.

Pane tähele, et nagu ka ülejäänud siinsete lehekülgede puhul,
võivad teised sinu poolt laetud faile saidi huvides
muuta või kustutada ning juhul kui sa süsteemi kuritarvitad
võidakse sinu ligipääs sulgeda.',
'uploadlog'            => 'üleslaadimise logi',
'uploadlogpage'        => 'Üleslaadimise logi',
'uploadlogpagetext'    => 'Allpool on loend viimastest failide üleslaadimistest. Kõik ajad näidatakse serveri aja järgi.',
'filename'             => 'Faili nimi',
'filedesc'             => 'Lühikirjeldus',
'fileuploadsummary'    => 'Info faili kohta:',
'uploadedfiles'        => 'Üleslaaditud failid',
'ignorewarning'        => 'Ignoreeri hoiatust ja salvesta fail hoiatusest hoolimata.',
'ignorewarnings'       => 'Ignoreeri hoiatusi',
'illegalfilename'      => 'Faili "$1" nimi sisaldab sümboleid, mis pole pealkirjades lubatud. Palun nimetage fail ümber ja proovige uuesti.',
'badfilename'          => 'Pildi nimi on muudetud. Uus nimi on "$1".',
'large-file'           => 'On soovitatav, et üleslaetavad failid ei oleks suuremad kui $1; selle faili suurus on $2.',
'largefileserver'      => 'Antud fail on suurem serverikonfiguratsiooni poolt lubatavast failisuurusest.',
'fileexists'           => 'Sellise nimega fail on juba olemas. Palun kontrollige $1, kui te ei ole kindel, kas tahate seda muuta.',
'fileexists-forbidden' => 'Sellise nimega fail on juba olemas, palun pöörduge tagasi ja laadige fail üles mõne teise nime all. [[Image:$1|thumb|center|$1]]',
'successfulupload'     => 'Üleslaadimine õnnestus',
'uploadwarning'        => 'Üleslaadimise hoiatus',
'savefile'             => 'Salvesta fail',
'uploadedimage'        => 'Fail "[[$1]]" on üles laaditud',
'uploaddisabled'       => 'Üleslaadimine hetkel keelatud',
'uploaddisabledtext'   => 'Vabandage, faili laadimine pole hetkel võimalik.',
'uploadcorrupt'        => 'Fail on vigane või vale laiendiga. Palun kontrolli faili ja proovi seda uuesti üles laadida.',
'uploadvirus'          => 'Fail sisaldab viirust! Täpsemalt: $1',
'sourcefilename'       => 'Lähtefail',
'destfilename'         => 'Failinimi vikis',

'license'   => 'Litsents',
'nolicense' => 'pole valitud',

# Image list
'imagelist'        => 'Piltide loend',
'imagelisttext'    => 'Piltide arv järgnevas loendis: $1. Sorteeritud $2.',
'getimagelist'     => 'hangin piltide nimekirja',
'ilsubmit'         => 'Otsi',
'showlast'         => 'Näita viimast $1 pilti sorteerituna $2.',
'byname'           => 'nime järgi',
'bydate'           => 'kuupäeva järgi',
'bysize'           => 'suuruse järgi',
'imgdelete'        => 'kust',
'imgdesc'          => 'kirj',
'filehist-user'    => 'Kasutaja',
'imagelinks'       => 'Viited pildile',
'linkstoimage'     => 'Sellele pildile viitavad järgmised leheküljed:',
'nolinkstoimage'   => 'Selle pildile ei viita ükski lehekülg.',
'noimage'          => 'Sellise nimega faili pole, võite selle $1.',
'noimage-linktext' => 'üles laadida',

# MIME search
'mimesearch' => 'MIME otsing',
'mimetype'   => 'MIME tüüp:',

# Unwatched pages
'unwatchedpages' => 'Jälgimata lehed',

# List redirects
'listredirects' => 'Ümbersuunamised',

# Unused templates
'unusedtemplates'     => 'Kasutamata mallid',
'unusedtemplatestext' => 'See lehekülg loetleb kõik mallinimeruumi leheküljed, millele teistelt lehekülgedelt ei viidata. Enne kustutamist palun kontrollige, kas siia pole muid linke.',
'unusedtemplateswlh'  => 'teised lingid',

# Random page
'randompage' => 'Juhuslik artikkel',

# Random redirect
'randomredirect' => 'Juhuslik ümbersuunamine',

# Statistics
'statistics'    => 'Statistika',
'sitestats'     => 'Saidi statistika',
'userstats'     => 'Kasutaja statistika',
'sitestatstext' => "Andmebaas sisaldab kokku <b>$1</b> lehekülge.
See arv hõlmab ka arutelulehekülgi, abilehekülgi, väga lühikesi lehekülgi (nuppe), ümbersuunamislehekülgi ning muid lehekülgi. Ilma neid arvestamata on vikis praegu <b>$2</b> lehekülge, mida võib pidada artikliteks.

Üles on laetud '''$8''' faili.

There have been a total of '''$3''' page views, and '''$4''' page edits
since the wiki was setup.
That comes to '''$5''' average edits per page, and '''$6''' views per edit.",
'userstatstext' => 'Registreeritud kasutajate arv: <b>$1</b>.
Administraatori staatuses kasutajaid: <b>$2</b> (vt $3).',

'disambiguations' => 'Täpsustusleheküljed',

'doubleredirects'     => 'Kahekordsed ümbersuunamised',
'doubleredirectstext' => 'Igal real on ära toodud esimene ja teine ümbersuunamisleht ning samuti teise ümbersuunamislehe viide, mis tavaliselt on viiteks, kuhu esimene ümbersuunamisleht peaks otse suunama.',

'brokenredirects'     => 'Vigased ümbersuunamised',
'brokenredirectstext' => 'Järgmised leheküljed on ümber suunatud olematutele lehekülgedele.',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bait|baiti}}',
'ncategories'             => '$1 {{PLURAL:$1|kategooria|kategooriat}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linki}}',
'nmembers'                => '$1 {{PLURAL:$1|liige|liiget}}',
'nrevisions'              => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'nviews'                  => 'Külastuste arv: $1',
'lonelypages'             => 'Viitamata artiklid',
'uncategorizedpages'      => 'Kategoriseerimata leheküljed',
'uncategorizedcategories' => 'Kategoriseerimata kategooriad',
'uncategorizedimages'     => 'Kategoriseerimata pildid',
'unusedcategories'        => 'Kasutamata kategooriad',
'unusedimages'            => 'Kasutamata pildid',
'popularpages'            => 'Loetumad artiklid',
'wantedcategories'        => 'Kõige oodatumad kategooriad',
'wantedpages'             => 'Kõige oodatumad artiklid',
'mostlinked'              => 'Kõige viidatumad leheküljed',
'mostlinkedcategories'    => 'Kõige viidatumad kategooriad',
'mostcategories'          => 'Enim kategoriseeritud artiklid',
'mostimages'              => 'Kõige kasutatumad pildid',
'mostrevisions'           => 'Kõige pikema redigeerimislooga artiklid',
'allpages'                => 'Kõik artiklid',
'shortpages'              => 'Lühikesed artiklid',
'longpages'               => 'Pikad artiklid',
'deadendpages'            => 'Edasipääsuta artiklid',
'listusers'               => 'Kasutajad',
'specialpages'            => 'Erileheküljed',
'spheading'               => 'Erileheküljed',
'restrictedpheading'      => 'Piirangutega erileheküljed',
'rclsub'                  => '(lehekülgedel, millele "$1" viitab)',
'newpages'                => 'Uued leheküljed',
'ancientpages'            => 'Kõige vanemad artiklid',
'intl'                    => 'Keeltevahelised lingid',
'move'                    => 'Teisalda',
'movethispage'            => 'Muuda pealkirja',
'unusedimagestext'        => '<p>Pange palun tähele, et teised
veebisaidid võivad linkida pildile otselingiga ja seega võivad
siin toodud pildid olla ikkagi aktiivses kasutuses.',
'unusedcategoriestext'    => 'Need kategooriad pole ühesgi artiklis või teises kategoorias kasutuses.',
'notargettitle'           => 'Puudub sihtlehekülg',
'notargettext'            => 'Sa ei ole esitanud sihtlehekülge ega kasutajat, kelle kallal seda operatsiooni toime panna.',

# Book sources
'booksources' => 'Otsi raamatut',

'categoriespagetext' => 'Vikis on järgmised kategooriad.',
'userrights'         => 'Kasutaja õiguste muutmine',
'groups'             => 'Kasutaja grupid',
'alphaindexline'     => '$1 kuni $2',
'version'            => 'Versioon',

# Special:Log
'specialloguserlabel'  => 'Kasutaja:',
'speciallogtitlelabel' => 'Pealkiri:',
'log'                  => 'Logid',
'alllogstext'          => 'See on kombineeritud vaade üleslaadimise, kustutamise, kaitsmise, blokeerimise ja administraatorilogist. Valiku kitsendamiseks vali soovitav logitüüp, sisesta kasutajanimi või huvi pakkuva lehekülge pealkiri.',
'logempty'             => 'Logides vastavad kirjed puuduvad.',

# Special:Allpages
'nextpage'          => 'Järgmine lehekülg ($1)',
'allpagesfrom'      => 'Näita alates:',
'allarticles'       => 'Kõik artiklid',
'allinnamespace'    => 'Kõik artiklid ($1 nimeruum)',
'allnotinnamespace' => 'Kõik artiklid (mis ei kuulu $1 nimeruumi)',
'allpagesprev'      => 'Eelmised',
'allpagesnext'      => 'Järgmised',
'allpagessubmit'    => 'Näita',

# E-mail user
'emailuser'     => 'Saada sellele kasutajale e-kiri',
'emailpage'     => 'Saada kasutajale e-kiri',
'emailpagetext' => 'Kui see kasutaja on oma eelistuste lehel sisestanud e-posti aadressi, siis saate alloleva vormi kaudu talle kirja saata. Et kasutaja saaks vastata, täidetakse kirja saatja väli "kellelt" e-posti aadressiga, mille olete sisestanud oma eelistuste lehel.',
'emailfrom'     => 'Kellelt',
'emailto'       => 'Kellele',
'emailsubject'  => 'Pealkiri',
'emailmessage'  => 'Sõnum',
'emailsend'     => 'Saada',
'emailsent'     => 'E-post saadetud',
'emailsenttext' => 'Teie sõnum on saadetud.',

# Watchlist
'watchlist'            => 'Minu jälgimisloend',
'mywatchlist'          => 'Minu jälgimisloend',
'watchlistfor'         => "('''$1''' jaoks)",
'nowatchlist'          => 'Teie jälgimisloend on tühi.',
'watchlistanontext'    => 'Et näha ja muuta oma jälgimisloendit, peate $1.',
'watchnologin'         => 'Ei ole sisse logitud',
'watchnologintext'     => 'Jälgimisloendi muutmiseks peate [[Special:Userlogin|sisse logima]].',
'addedwatch'           => 'Lisatud jälgimisloendile',
'addedwatchtext'       => 'Lehekülg "$1" on lisatud Teie [[Special:Watchlist|jälgimisloendile]].

Edasised muudatused käesoleval lehel ja sellega seotud aruteluküljel reastatakse jälgimisloendis ning [[Special:Recentchanges|viimaste muudatuste lehel]] tuuakse jälgitava lehe pealkiri esile <b>rasvase</b> kirja abil.

Kui tahad seda lehte hiljem jälgimisloendist eemaldada, klõpsa päisenupule "Lõpeta jälgimine".',
'removedwatch'         => 'Jälgimisloendist kustutatud',
'removedwatchtext'     => 'Artikkel "[[:$1]]" on jälgimisloendist kustutatud.',
'watch'                => 'Jälgi',
'watchthispage'        => 'Jälgi seda artiklit',
'unwatch'              => 'Lõpeta jälgimine',
'unwatchthispage'      => 'Ära jälgi',
'notanarticle'         => 'Pole artikkel',
'watchnochange'        => 'Valitud perioodi jooksul ei ole üheski jälgitavas artiklis muudatusi tehtud.',
'wlheader-showupdated' => "* Leheküljed, mida on muudetud peale sinu viimast külastust, on '''rasvases kirjas'''",
'watchmethod-list'     => 'jälgitavate lehekülgede viimased muudatused',
'watchlistcontains'    => 'Sinu jälgimisloendis on $1 artiklit.',
'wlnote'               => 'Allpool on viimased $1 muudatust viimase <b>$2</b> tunni jooksul.',
'wlshowlast'           => 'Näita viimast $1 tundi $2 päeva. $3',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'jälgin...',
'unwatching' => 'Jälgimise lõpetamine...',

'enotif_reset' => 'Märgi kõik lehed loetuks',
'changed'      => 'muudetud',

# Delete/protect/revert
'deletepage'                  => 'Kustuta lehekülg',
'confirm'                     => 'Kinnita',
'excontent'                   => "sisu oli: '$1'",
'excontentauthor'             => "sisu oli: '$1' (ja ainuke kirjutaja oli '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "sisu enne lehekülje tühjendamist: '$1'",
'exblank'                     => 'lehekülg oli tühi',
'confirmdelete'               => 'Kinnita kustutamine',
'deletesub'                   => '(Kustutan "$1")',
'historywarning'              => 'Hoiatus: leheküljel, mida tahate kustutada, on ajalugu:&nbsp;',
'confirmdeletetext'           => 'Sa oled andmebaasist jäädavalt kustutamas lehte või pilti koos kogu tema ajalooga. Palun kinnita, et sa tahad seda tõepoolest teha, et sa mõistad tagajärgi ja et sinu tegevus on kooskõlas siinse [[{{MediaWiki:Policy-url}}|sisekorraga]].',
'actioncomplete'              => 'Toiming sooritatud',
'deletedtext'                 => '"$1" on kustutatud. $2 lehel on nimekiri viimastest kustutatud lehekülgedest.',
'deletedarticle'              => '"$1" kustutatud',
'dellogpage'                  => 'Kustutatud_leheküljed',
'dellogpagetext'              => 'Allpool on esitatud nimekiri viimastest kustutamistest.
Kõik toodud kellaajad järgivad serveriaega (UTC).',
'deletionlog'                 => 'Kustutatud leheküljed',
'reverted'                    => 'Pöörduti tagasi varasemale versioonile',
'deletecomment'               => 'Kustutamise põhjus',
'rollback'                    => 'Tühista muudatused',
'rollback_short'              => 'Tühista',
'rollbacklink'                => 'tühista',
'rollbackfailed'              => 'Muudatuste tühistamine ebaõnnestus',
'cantrollback'                => 'Ei saa muudatusi tagasi pöörata; viimane kaastööline on artikli ainus autor.',
'editcomment'                 => 'Artikli sisu oli: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Pöörduti tagasi viimasele muudatusele, mille tegi $1',
'protectlogpage'              => 'Kaitsmise logi',
'protectlogtext'              => 'Allpool on loetletud lehekülgede kaitsmised ja kaitsete eemaldamised. Praegu kaitstud lehekülgi vaata [[Special:Protectedpages|kaitstud lehtede loetelust]].',
'protectedarticle'            => 'kaitses lehekülje "[[$1]]"',
'unprotectedarticle'          => 'eemaldas lehekülje "[[$1]]" kaitse',
'protectsub'                  => '("$1" kaitsmine)',
'confirmprotect'              => 'Kinnita kaitsmine',
'protectcomment'              => 'Põhjus',
'unprotectsub'                => '(Lehekülje "$1" kaitse alt võtmine)',
'protect-text'                => 'Siin võite vaadata ja muuta lehekülje <strong>$1</strong> kaitsesätteid.',
'protect-default'             => '(tavaline)',
'protect-level-autoconfirmed' => 'Ainult registreeritud kasutajad',
'protect-level-sysop'         => 'Ainult administraatorid',
'pagesize'                    => '(baiti)',

# Restrictions (nouns)
'restriction-edit' => 'Redigeerimine',
'restriction-move' => 'Teisaldamine',

# Undelete
'undelete'               => 'Taasta kustutatud lehekülg',
'undeletepage'           => 'Kuva ja taasta kustutatud lehekülgi',
'viewdeletedpage'        => 'Vaata kustutatud lehekülgi',
'undeletepagetext'       => 'Järgnevad leheküljed on kustutatud, kuis arhiivis
veel olemas, neid saab taastada. Arhiivi sisu vistatakse aegajalt üle parda.',
'undeleteextrahelp'      => "Kogu lehe ja selle ajaloo taastamiseks jätke kõik linnukesed tühjaks ja vajutage '''''Taasta'''''.
Et taastada valikuliselt, tehke linnukesed kastidesse, mida soovite taastada ja vajutage '''''Taasta'''''.
Nupu '''''Tühjenda''''' vajutamine tühjendab põhjusevälja ja eemaldab kõik linnukesed.",
'undeleterevisions'      => 'Arhiveeritud versioone on $1.',
'undeletehistory'        => 'Kui taastate lehekülje, taastuvad kõik versioonid artikli
ajaloona. Kui vahepeal on loodud uus samanimeline lehekülg, ilmuvad taastatud
versioonid varasema ajaloona. Kehtivat versiooni automaatselt välja ei vahetata.',
'undeletehistorynoadmin' => 'See artikkel on kustutatud. Kustutamise põhjus ning selle lehekülje redigeerimislugu enne kustutamist on näha allolevas kokkuvõttes. Artikli kustutamiseelsete redaktsioonide tekst on kättesaadav ainult administraatoritele.',
'undeletebtn'            => 'Taasta',
'undeletereset'          => 'Tühjenda',
'undeletecomment'        => 'Põhjus:',
'undeletedarticle'       => '"$1" taastatud',
'undeletedrevisions'     => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}} taastatud',
'cannotundelete'         => 'Taastamine ebaõnnestus; keegi teine võis lehe juba taastada.',

# Namespace form on various pages
'namespace'      => 'Nimeruum:',
'invert'         => 'Näita kõiki peale valitud nimeruumi',
'blanknamespace' => '(Artiklid)',

# Contributions
'contributions' => 'Kasutaja kaastööd',
'mycontris'     => 'Minu kaastöö',
'contribsub2'   => 'Kasutaja "$1 ($2)" jaoks',
'nocontribs'    => 'Antud kriteeriumile vastavaid muudatusi ei leidnud.',
'ucnote'        => 'Esitatakse selle kasutaja tehtud viimased <b>$1</b> muudatust viimase <b>$2</b> päeva jooksul.',
'uclinks'       => 'Näita viimast $1 muudatust; viimase $2 päeva jooksul.',
'uctop'         => ' (üles)',

'sp-contributions-newest' => 'Uusimad',
'sp-contributions-oldest' => 'Vanimad',
'sp-contributions-newer'  => '$1 uuemat',
'sp-contributions-older'  => '$1 vanemat',

'sp-newimages-showfrom' => 'Näita uusi pilte alates $1',

# What links here
'whatlinkshere' => 'Viidad siia',
'linklistsub'   => '(Linkide loend)',
'linkshere'     => 'Siia viitavad järgmised leheküljed:',
'nolinkshere'   => 'Siia ei viita ükski lehekülg.',
'isredirect'    => 'ümbersuunamislehekülg',
'istemplate'    => 'kasutamine',

# Block/unblock
'blockip'            => 'Blokeeri IP-aadress',
'blockiptext'        => "See vorm on kirjutamisõiguste blokeerimiseks konkreetselt IP-aadressilt.
'''Seda tohib teha ainult vandalismi vältimiseks ning kooskõlas [[{{MediaWiki:Policy-url}}|{{SITENAME}} sisekorraga]]'''.
Kindlasti tuleb täita ka väli \"põhjus\", paigutades sinna näiteks viited konkreetsetele lehekülgedele, mida rikuti.",
'ipaddress'          => 'IP-aadress',
'ipadressorusername' => 'IP-aadress või kasutajanimi',
'ipbexpiry'          => 'Kehtivus',
'ipbreason'          => 'Põhjus',
'ipbreasonotherlist' => 'Muul põhjusel',
'ipbsubmit'          => 'Blokeeri see aadress',
'ipbother'           => 'Muu tähtaeg',
'ipboptions'         => '2 tundi:2 hours,1 päev:1 day,3 päeva:3 days,1 nädal:1 week,2 nädalat:2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite',
'ipbotheroption'     => 'muu tähtaeg',
'ipbotherreason'     => 'Muu/täiendav põhjus:',
'badipaddress'       => 'The IP address is badly formed.',
'blockipsuccesssub'  => 'Blokeerimine õnnestus',
'blockipsuccesstext' => 'IP-aadress "$1" on blokeeritud.
<br />Kehtivaid blokeeringuid vaata [[Special:Ipblocklist|blokeeringute nimekirjast]].',
'unblockip'          => 'Lõpeta IP aadressi blokeerimine',
'unblockiptext'      => 'Kasutage allpool olevat vormi redigeerimisõiguste taastamiseks varem blokeeritud IP aadressile.',
'ipblocklist'        => 'Blokeeritud IP-aadresside loend',
'blocklistline'      => '$1, $2 blokeeris $3 ($4)',
'expiringblock'      => 'aegub $1',
'ipblocklist-empty'  => 'Blokeerimiste loend on tühi.',
'blocklink'          => 'blokeeri',
'unblocklink'        => 'lõpeta blokeerimine',
'contribslink'       => 'kaastöö',
'autoblocker'        => 'Autoblokeeritud kuna teie IP aadress on hiljut kasutatud "[[User:$1|$1]]" poolt. $1-le antud bloki põhjus on "\'\'\'$2\'\'\'"',
'blocklogpage'       => 'Blokeerimise logi',
'blocklogentry'      => 'blokeeris "[[$1]]". Blokeeringu aegumistähtaeg on $2 $3',
'blocklogtext'       => 'See on kasutajate blokeerimiste ja blokeeringute eemaldamiste nimekiri. Automaatselt blokeeritud IP aadresse siin ei näidata. Hetkel aktiivsete blokeeringute ja redigeerimiskeeldude nimekirja vaata [[Special:Ipblocklist|IP blokeeringute nimekirja]] leheküljelt.',
'unblocklogentry'    => '"$1" blokeerimine lõpetatud',
'proxyblockreason'   => 'Teie IP aadress on blokeeritud, sest see on anonüümne proxy server. Palun kontakteeruga oma internetiteenuse pakkujaga või tehnilise toega ning informeerige neid sellest probleemist.',
'proxyblocksuccess'  => 'Tehtud.',

# Developer tools
'lockdb'              => 'Lukusta andmebaas',
'unlockdb'            => 'Tee andmebaas lukust lahti',
'lockconfirm'         => 'Jah, ma soovin andmebaasi lukustada.',
'lockbtn'             => 'Võta andmebaas kirjutuskaitse alla',
'unlockbtn'           => 'Taasta andmebaasi kirjutuspääs',
'lockdbsuccesssub'    => 'Andmebaas kirjutuskaitse all',
'unlockdbsuccesssub'  => 'Kirjutuspääs taastatud',
'lockdbsuccesstext'   => 'Andmebaas on nüüd kirjutuskaitse all.
<br />Kui Teie hooldustöö on läbi, ärge unustage kirjutuspääsu taastada!',
'unlockdbsuccesstext' => 'Andmebaasi kirjutuspääs on taastatud.',

# Move page
'movepage'         => 'Teisalda artikkel',
'movepagetext'     => "Allolevat vormi kasutades saate lehekülje ümber nimetada. Lehekülje ajalugu tõstetakse uue pealkirja alla automaatselt. Praeguse pealkirjaga leheküljest saab ümbersuunamisleht uuele leheküljele. Teistes artiklites olevaid linke praeguse nimega leheküljele automaatselt ei muudeta. Teie kohuseks on hoolitseda, et ei tekiks topeltümbersuunamisi ning et kõik jääks toimima nagu enne ümbernimetamist.

Lehekülge '''ei nimetata ümber''' juhul, kui uue nimega lehekülg on juba olemas. Erandiks on juhud, kui olemasolev lehekülg on tühi või ümbersuunamislehekülg ja sellel pole redigeerimisajalugu. See tähendab, et te ei saa kogemata üle kirjutada juba olemasolevat lehekülge, kuid saate ebaõnnestunud ümbernimetamise tagasi pöörata.

<strong>ETTEVAATUST!</strong> Võimalik, et kavatsete  teha ootamatut ning drastilist muudatust väga loetavasse artiklisse; enne muudatuse tegemist mõelge palun järele, mis võib olla selle tagajärjeks.",
'movepagetalktext' => "Koos artiklileheküljega teisaldatakse automaatselt ka arutelulehekülg, '''välja arvatud juhtudel, kui:'''
*liigutate lehekülge ühest nimeruumist teise,
*uue nime all on juba olemas mittetühi arutelulehekülg või
*jätate alumise kastikese märgistamata.

Neil juhtudel teisaldage arutelulehekülg soovi korral eraldi või ühendage ta omal käel uue aruteluleheküljega.",
'movearticle'      => 'Teisalda artiklilehekülg',
'movenologin'      => 'Te ei ole sisse loginud',
'movenologintext'  => 'Et lehekülge teisaldada, peate registreeruma
kasutajaks ja [[Special:Userlogin|sisse logima]]',
'newtitle'         => 'Uue pealkirja alla',
'movepagebtn'      => 'Teisalda artikkel',
'pagemovedsub'     => 'Artikkel on teisaldatud',
'articleexists'    => 'Selle nimega artikkel on juba olemas või pole valitud nimi lubatav. Palun valige uus nimi.',
'talkexists'       => 'Artikkel on teisaldatud, kuid arutelulehekülge ei saanud teisaldada, sest uue nime all on arutelulehekülg juba olemas. Palun ühendage aruteluleheküljed ise.',
'movedto'          => 'Teisaldatud pealkirja alla:',
'movetalk'         => 'Teisalda ka "arutelu", kui saab.',
'talkpagemoved'    => 'Ka vastav arutelulehekülg on teisaldatud.',
'talkpagenotmoved' => 'Vastav arutelulehekülg jäi teisaldamata.',
'1movedto2'        => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla',
'1movedto2_redir'  => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla ümbersuunamisega',
'movelogpage'      => 'Teisaldamise logi',
'movelogpagetext'  => 'See logi sisaldab infot lehekülgede teisaldamistest.',
'movereason'       => 'Põhjus',
'revertmove'       => 'taasta',

# Export
'export' => 'Lehekülgede eksport',

# Namespace 8 related
'allmessages'        => 'Kõik süsteemi sõnumid',
'allmessagesname'    => 'Nimi',
'allmessagesdefault' => 'Vaikimisi tekst',
'allmessagescurrent' => 'Praegune tekst',
'allmessagestext'    => 'See on loend kõikidest kättesaadavatest süsteemi sõnumitest MediaWiki: nimeruumis.',

# Thumbnails
'thumbnail-more' => 'Suurenda',
'missingimage'   => '<b>Puudub pildifail</b><br /><i>$1</i>',

# Special:Import
'import'          => 'Lehekülgede import',
'importfailed'    => 'Importimine ebaõnnestus: $1',
'importnosources' => 'Ühtegi transwiki impordiallikat ei ole defineeritud ning ajaloo otseimpordi funktsioon on välja lülitatud.',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Minu kasutaja leht',
'tooltip-pt-anonuserpage'         => 'Selle IP aadressi kasutajaleht',
'tooltip-pt-mytalk'               => 'Minu arutelu leht',
'tooltip-pt-anontalk'             => 'Arutelu sellelt IP aadressilt tehtud muudatuste kohta',
'tooltip-pt-preferences'          => 'Minu eelistused',
'tooltip-pt-watchlist'            => 'Lehekülgede loend, mida jälgid muudatuste osas',
'tooltip-pt-mycontris'            => 'Loend minu kaastöö kohta',
'tooltip-pt-login'                => 'Me julgustame teid sisse logima, kuid see pole kohustuslik.',
'tooltip-pt-anonlogin'            => 'Me julgustame teid sisse logima, kuid see pole kohustuslik.',
'tooltip-pt-logout'               => 'Logi välja',
'tooltip-ca-talk'                 => 'Selle artikli arutelu',
'tooltip-ca-edit'                 => 'Te võite seda lehekülge redigeerida. Palun kasutage enne salvestamist eelvaadet.',
'tooltip-ca-addsection'           => 'Lisa kommentaar arutellu.',
'tooltip-ca-viewsource'           => 'See lehekülg on kaitstud. Te võite kuvada selle koodi.',
'tooltip-ca-history'              => 'Selle lehekülje varasemad versioonid.',
'tooltip-ca-protect'              => 'Kaitse seda lehekülge',
'tooltip-ca-delete'               => 'Kustuta see lehekülg',
'tooltip-ca-undelete'             => 'Taasta tehtud muudatused enne kui see lehekülg kustutati',
'tooltip-ca-move'                 => 'Teisalda see lehekülg teise nime alla.',
'tooltip-ca-watch'                => 'Lisa see lehekülg oma jälgimisloendile',
'tooltip-ca-unwatch'              => 'Eemalda see lehekülg oma jälgimisloendist',
'tooltip-search'                  => 'Otsi vikist',
'tooltip-p-logo'                  => 'Esileht',
'tooltip-n-mainpage'              => 'Mine esilehele',
'tooltip-n-portal'                => 'Projekti kohta, mida te saate teha, kuidas leida informatsiooni jne',
'tooltip-n-currentevents'         => 'Leia informatsiooni sündmuste kohta maailmas',
'tooltip-n-recentchanges'         => 'Vikis tehtud viimaste muudatuste loend.',
'tooltip-n-randompage'            => 'Mine juhuslikule leheküljele',
'tooltip-n-help'                  => 'Kuidas redigeerida.',
'tooltip-n-sitesupport'           => 'Toeta meid',
'tooltip-t-whatlinkshere'         => 'Kõik Viki leheküljed, mis siia viitavad',
'tooltip-t-recentchangeslinked'   => 'Viimased muudatused lehekülgedel, milledele on siit viidatud',
'tooltip-feed-rss'                => 'Selle lehekülje RSS sööt',
'tooltip-feed-atom'               => 'Selle lehekülje Atom sööt',
'tooltip-t-contributions'         => 'Kuva selle kasutaja kaastööd',
'tooltip-t-emailuser'             => 'Saada sellele kasutajale e-kiri',
'tooltip-t-upload'                => 'Lae üles pilte ja muid meediafaile',
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

# Attribution
'anonymous' => '{{SITENAME}} anonüümsed kasutajad',
'siteuser'  => 'Viki kasutaja $1',
'and'       => 'ja',
'others'    => 'teised',
'siteusers' => 'Viki kasutaja(d) $1',

# Spam protection
'subcategorycount'       => 'Sellel kategoorial on {{PLURAL:$1|üks allkategooria|$1 allkategooriat}}.',
'categoryarticlecount'   => 'Lehel kuvatakse $1 kategooriasse {{PLURAL:$1|kuuluv artikkel|kuuluvat artiklit}}.',
'category-media-count'   => 'Lehel kuvatakse $1 kategooriasse {{PLURAL:$1|kuuluv fail|kuuluvat faili}}.',
'listingcontinuesabbrev' => 'jätk',

# Math options
'mw_math_png'    => 'Alati PNG',
'mw_math_simple' => 'Kui väga lihtne, siis HTML, muidu PNG',
'mw_math_html'   => 'Võimaluse korral HTML, muidu PNG',
'mw_math_source' => 'Säilitada TeX (tekstibrauserite puhul)',
'mw_math_modern' => 'Soovitatav moodsate brauserite puhul',
'mw_math_mathml' => 'MathML',

# Browsing diffs
'previousdiff' => '← Eelmised erinevused',
'nextdiff'     => 'Järgmised erinevused →',

# Media information
'imagemaxsize' => 'Maksimaalne pildi suurus kirjelduslehekülgedel:',
'thumbsize'    => 'Pisipildi suurus:',

# Special:Newimages
'newimages'    => 'Uute meediafailide galerii',
'showhidebots' => '($1 bottide kaastööd)',

# EXIF tags
'exif-artist'          => 'Autor',
'exif-exposuretime'    => 'Säriaeg',
'exif-aperturevalue'   => 'Ava',
'exif-brightnessvalue' => 'Heledus',
'exif-focallength'     => 'Fookuskaugus',
'exif-contrast'        => 'Kontrastsus',

'exif-lightsource-10' => 'Pilvine ilm',

# External editor support
'edit-externally'      => 'Töötle faili välise programmiga',
'edit-externally-help' => 'Lisainfot loe leheküljelt [http://meta.wikimedia.org/wiki/Help:External_editors meta:väliste redaktorite kasutamine]',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'kõik',
'imagelistall'     => 'kõik pildid',
'watchlistall2'    => 'Näita kõiki',
'namespacesall'    => 'kõik',
'monthsall'        => 'kõik',

# E-mail address confirmation
'confirmemail'            => 'Kinnita e-posti aadress',
'confirmemail_text'       => 'Enne kui saad e-postiga seotud teenuseid kasutada, pead sa oma e-posti aadressi õigsust kinnitama. Allpool olevale nupule klikkides meilitakse sulle kinnituskood, koodi kinnitamiseks kliki meilis oleval lingil.',
'confirmemail_send'       => 'Meili kinnituskood',
'confirmemail_sent'       => 'Kinnitusmeil saadetud.',
'confirmemail_sendfailed' => 'Kinnitusmeili ei õnnestunud saata. Kontrolli aadressi õigsust.

Mailer returned: $1',
'confirmemail_invalid'    => 'Vigane kinnituskood, kinnituskood võib olla aegunud.',
'confirmemail_needlogin'  => 'Oma e-posti aadressi kinnitamiseks pead sa $1.',
'confirmemail_success'    => 'Sinu e-posti aadress on nüüd kinnitatud. Sa võid sisse logida ning viki imelisest maailma nautida.',
'confirmemail_loggedin'   => 'Sinu e-posti aadress on nüüd kinnitatud.',
'confirmemail_error'      => 'Viga kinnituskoodi salvestamisel.',
'confirmemail_subject'    => '{{SITENAME}}: e-posti aadressi kinnitamine',
'confirmemail_body'       => 'Keegi, ilmselt sa ise, registreeris IP aadressilt $1 saidil {{SITENAME}} kasutajakonto "$2".

Kinnitamaks, et see kasutajakonto tõepoolest kuulub sulle ning aktiveerimaks e-posti teenuseid, ava oma brauseris järgnev link:

$3

Kui see *ei* ole sinu loodud konto, siis ära kliki lingil. Kinnituskood aegub $4.',

# Delete conflict
'deletedwhileediting' => 'Hoiatus: Sel ajal, kui Te artiklit redigeerisite, on keegi selle kustutanud!',

# HTML dump
'redirectingto' => 'Ümbersuunamine lehele [[$1]]...',

# Auto-summaries
'autoredircomment' => 'Ümbersuunamine lehele [[$1]]',
'autosumm-new'     => 'Uus lehekülg: $1',

# Watchlist editor
'watchlistedit-numitems'       => 'Teie jälgimisloendis on {{PLURAL:$1|1 leht|$1 lehte}}, ilma arutelulehtedeta.',
'watchlistedit-normal-title'   => 'Jälgimisloendi redigeerimine',
'watchlistedit-normal-legend'  => 'Jälgimisloendist lehtede eemaldamine',
'watchlistedit-normal-explain' => "Siin on lehed, mis on teie jälgimisloendis.Et lehti eemaldada, tehke vastavatesse kastidesse linnukesed ja vajutage nuppu '''Eemalda valitud lehed'''. Te võite ka [[Special:Watchlist/raw|redigeerida lähtefaili]] või [[Special:Watchlist/clear|eemaldada kõik lehed]].",
'watchlistedit-normal-submit'  => 'Eemalda valitud lehed',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 leht|Järgmised $1 lehte}} on Teie jälgimisloendist eemaldatud:',
'watchlistedit-raw-done'       => 'Teie jälgimisloend on uuendatud.',

# Watchlist editing tools
'watchlisttools-view' => 'Näita vastavaid muudatusi',
'watchlisttools-edit' => 'Vaata ja redigeeri jälgimisloendit',
'watchlisttools-raw'  => 'Redigeeri lähtefaili',

);
