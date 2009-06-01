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
'tog-hidepatrolled'           => 'Peida viimaste muudatuste loetelus jälgimisloendis esitatavad muudatused',
'tog-extendwatchlist'         => 'Laienda jälgimisloendit, et näha kõiki muudatusi, mitte vaid kõige värskemaid',
'tog-usenewrc'                => 'Laiendatud viimased muudatused (nõutav JavaScripti olemasolu)',
'tog-numberheadings'          => 'Pealkirjade automaatnummerdus',
'tog-showtoolbar'             => 'Redigeerimise tööriistariba näitamine',
'tog-editondblclick'          => 'Artiklite redigeerimine topeltklõpsu peale (JavaScript)',
'tog-editsection'             => '[redigeeri] lingid peatükkide muutmiseks',
'tog-editsectiononrightclick' => 'Peatükkide redigeerimine paremklõpsuga alampealkirjadel (JavaScript)',
'tog-showtoc'                 => 'Näita sisukorda (lehtedel, millel on rohkem kui 3 pealkirja)',
'tog-rememberpassword'        => 'Parooli meeldejätmine tulevasteks seanssideks',
'tog-editwidth'               => 'Redaktoriaknal on täislaius',
'tog-watchcreations'          => 'Lisa minu loodud lehed jälgimisloendisse',
'tog-watchdefault'            => 'Jälgi uusi ja muudetud artikleid',
'tog-watchmoves'              => 'Lisa minu teisaldatud artiklid jälgimisloendisse',
'tog-watchdeletion'           => 'Lisa minu kustutatud leheküljed jälgimisloendisse',
'tog-minordefault'            => 'Märgi kõik parandused vaikimisi pisiparandusteks',
'tog-previewontop'            => 'Näita eelvaadet redaktoriakna ees, mitte järel',
'tog-previewonfirst'          => 'Näita eelvaadet esimesel redigeerimisel',
'tog-nocache'                 => 'Keela lehekülgede puhverdamine',
'tog-enotifwatchlistpages'    => 'Teata meili teel, kui minu jälgitavat artiklit muudetakse',
'tog-enotifusertalkpages'     => 'Teata meili teel, kui minu arutelu lehte muudetakse',
'tog-enotifminoredits'        => 'Teata meili teel ka pisiparandustest',
'tog-enotifrevealaddr'        => 'Näita minu e-posti aadressi teatavakstegemiste e-kirjades.',
'tog-shownumberswatching'     => 'Näita jälgivate kasutajate hulka',
'tog-fancysig'                => 'Kasuta vikiteksti vormingus allkirja (ilma automaatse lingita kasutajalehele)',
'tog-externaleditor'          => 'Kasuta vaikimisi välist redaktorit',
'tog-externaldiff'            => 'Kasuta vaikimisi välist võrdlusvahendit (ainult ekspertidele, tarvilikud on kasutaja arvuti eriseadistused)',
'tog-forceeditsummary'        => 'Nõua redigeerimisel resümee välja täitmist',
'tog-watchlisthideown'        => 'Peida minu redaktsioonid jälgimisloendist',
'tog-watchlisthidebots'       => 'Peida robotid jälgimisloendist',
'tog-watchlisthideminor'      => 'Peida pisiparandused jälgimisloendist',
'tog-watchlisthideliu'        => 'Peida sisselogitud kasutajate muudatused jälgimisloendist',
'tog-watchlisthideanons'      => 'Peida anonüümsete kasutajate muudatused jälgimisloendist',
'tog-ccmeonemails'            => 'Saada mulle koopiad e-mailidest, mida ma teistele kasutajatele saadan',
'tog-diffonly'                => 'Ära näita erinevuste vaate all lehe sisu',
'tog-showhiddencats'          => 'Näita peidetud kategooriaid',

'underline-always'  => 'Alati',
'underline-never'   => 'Mitte kunagi',
'underline-default' => 'Brauseri vaikeväärtus',

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
'category_header'                => 'Artiklid kategooriast "$1"',
'subcategories'                  => 'Allkategooriad',
'category-media-header'          => 'Meediafailid kategooriast "$1"',
'category-empty'                 => "''Selles kategoorias pole ühtegi artiklit ega meediafaili.''",
'hidden-categories'              => '{{PLURAL:$1|Peidetud kategooria|Peidetud kategooriad}}',
'hidden-category-category'       => 'Peidetud kategooriad', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Sellel kategoorial on ainult järgmine allkategooria.|Sellel kategoorial on {{PLURAL:$1|järgmine allkategooria|järgmised $1 allkategooriat}}, (kokku $2).}}',
'category-subcat-count-limited'  => 'Sellel kategoorial on {{PLURAL:$1|järgmine allkategooria|järgmised $1 allkategooriat}}.',
'category-article-count'         => '{{PLURAL:$2|Antud kategoorias on ainult järgmine lehekülg.|Antud kategoorias on {{PLURAL:$1|järgmine lehekülg|järgmised $1 lehekülge}} (kokku $2).}}',
'category-article-count-limited' => 'Antud kategoorias on {{PLURAL:$1|järgmine lehekülg|järgmised $1 lehekülge}}.',
'category-file-count'            => '{{PLURAL:$2|Selles kategoorias on ainult järgmine fail.|{{PLURAL:$1|Järgmine fail |Järgmised $1 faili}} on selles kategoorias (kokku $2).}}',
'category-file-count-limited'    => '{{PLURAL:$1|Järgmine fail|Järgmised $1 faili}} on selles kategoorias.',
'listingcontinuesabbrev'         => 'jätk',

'mainpagetext'      => "<big>'''MediaWiki tarkvara on edukalt paigaldatud.'''</big>",
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
'mytalk'         => 'Arutelu',
'anontalk'       => 'Arutelu selle IP jaoks',
'navigation'     => 'Navigeerimine',
'and'            => '&#32;ja',

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
'projectpage'       => 'Metalehekülg',
'imagepage'         => 'Vaata faililehekülge',
'mediawikipage'     => 'Vaata sõnumite lehekülge',
'templatepage'      => 'Mallilehekülg',
'viewhelppage'      => 'Vaata abilehekülge',
'categorypage'      => 'Kategoorialehekülg',
'viewtalkpage'      => 'Arutelulehekülg',
'otherlanguages'    => 'Teistes keeltes',
'redirectedfrom'    => '(Ümber suunatud artiklist $1)',
'redirectpagesub'   => 'Ümbersuunamisleht',
'lastmodifiedat'    => 'Viimane muutmine: $2, $1', # $1 date, $2 time
'viewcount'         => 'Seda lehekülge on külastatud {{PLURAL:$1|üks kord|$1 korda}}.',
'protectedpage'     => 'Kaitstud lehekülg',
'jumpto'            => 'Mine:',
'jumptonavigation'  => 'navigeerimiskast',
'jumptosearch'      => 'otsi',

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
'faq'                  => 'KKK',
'faqpage'              => 'Project:KKK',
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
'badaccess-groups' => 'Tegevus, mida üritasid, on piiratud kasutajatele {{PLURAL:$2|grupis|ühes neist gruppidest}}: $1.',

'versionrequired'     => 'Nõutav MediaWiki versioon $1',
'versionrequiredtext' => 'Selle lehe kasutamiseks on nõutav MediaWiki versioon $1.
Vaata [[Special:Version|versiooni lehekülge]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Välja otsitud andmebaasist "$1"',
'youhavenewmessages'      => 'Teile on $1 ($2).',
'newmessageslink'         => 'uusi sõnumeid',
'newmessagesdifflink'     => 'erinevus eelviimasest redaktsioonist',
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
'nosuchactiontext'  => 'Wiki ei tunne antud URLile vastavat tegevust.
Sa nähtavasti trükkisid URLi valesti või kasutasid vigast linki.
Võimalik aga, et see osutab veale portaali {{SITENAME}} poolt kasutatavas tarkvaras.',
'nosuchspecialpage' => 'Sellist erilehekülge pole.',
'nospecialpagetext' => 'Viki ei tunne sellist erilehekülge.',

# General errors
'error'                => 'Viga',
'databaseerror'        => 'Andmebaasi viga',
'dberrortext'          => 'Andmebaasipäringus oli õigekirjaviga.
Otsingupäring oli ebakorrektne või on tarkvaras viga.
Viimane andmebaasipäring oli:
<blockquote><tt>$1</tt></blockquote>
ja see kutsuti funktsioonist "<tt>$2</tt>".
MySQL tagastas veateate "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Andmebaasipäringus oli õigekirjaviga.
Viimane andmebaasipäring oli:
"$1"
ja see kutsuti funktsioonist "$2".
MySQL tagastas veateate "$3: $4".',
'noconnect'            => 'Vabandame! Vikil on tehnilisi probleeme ning ei saa andmebaasiserveriga $1 ühendust.',
'nodb'                 => 'Andmebaasi $1 ei õnnestunud kätte saada',
'cachederror'          => 'Järgnev tekst pärineb serveri vahemälust ega pruugi olla lehekülje viimane versioon.',
'laggedslavemode'      => 'Hoiatus: Leheküljel võivad puududa viimased uuendused.',
'readonly'             => 'Andmebaas on hetkel kirjutuskaitse all',
'enterlockreason'      => 'Sisesta lukustamise põhjus ning juurdepääsu taastamise ligikaudne aeg',
'readonlytext'         => 'Andmebaas on praegu kirjutuskaitse all, tõenäoliselt andmebaasi rutiinseks hoolduseks, mille lõppedes normaalne olukord taastub.
Administraator, kes selle kaitse alla võttis, andis järgmise selgituse:
<p>$1',
'missing-article'      => 'Andmebaas ei leidnud küsitud lehekülje "$1" $2 teksti.

Põhjuseks võib olla võrdlus- või ajaloolink kustutatud leheküljele.

Kui tegemist ei ole nimetatud olukorraga on põhjust kahtlustada süsteemi viga.
Sellisel juhul tuleks informeerida [[Special:ListUsers/sysop|mõnda administratooritest]], edastades talle ka käesoleva lehe URLi.',
'missingarticle-rev'   => '(redaktsioon: $1)',
'missingarticle-diff'  => '(redaktsioonid: $1, $2)',
'internalerror'        => 'Sisemine viga',
'internalerror_info'   => 'Sisemine viga: $1',
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
'badtitletext'         => 'Küsitud artiklipealkiri oli kas vigane, tühi või siis
valesti viidatud keelte- või wikidevaheline pealkiri.',
'perfcached'           => 'Järgnevad andmed on puhverdatud ja ei pruugi olla kõige värskemad:',
'perfcachedts'         => 'Järgmised andmed on vahemälus. Viimase uuendamise daatum on $1.',
'wrong_wfQuery_params' => 'Valed parameeterid funktsioonile wfQuery()<br />
Funktsioon: $1<br />
Päring: $2',
'viewsource'           => 'Vaata lähteteksti',
'viewsourcefor'        => '$1',
'protectedpagetext'    => 'See lehekülg on lukustatud, et muudatusi ei tehtaks.',
'viewsourcetext'       => 'Võite vaadata ja kopeerida lehekülje algteksti:',
'protectedinterface'   => 'Sellel leheküljel on tarkvara kasutajaliidese tekst. Kuritahtliku muutmise vältimiseks on lehekülg lukustatud.',
'editinginterface'     => "'''Hoiatus:''' Te redigeerite tarkvara kasutajaliidese tekstiga lehekülge. Muudatused siin mõjutavad kõikide kasutajate kasutajaliidest. Tõlkijad, palun kaaluge MediaWiki tõlkimisprojekti – [http://translatewiki.net/wiki/Main_Page?setlang=et translatewiki.net] kasutamist.",
'sqlhidden'            => '(SQL päring peidetud)',
'namespaceprotected'   => "Teil ei ole õigusi redigeerida lehekülgi '''$1''' nimeruumis.",
'customcssjsprotected' => 'Sul pole õigust antud lehte muuta, kuna see sisaldab teise kasutaja isiklikke seadeid.',
'ns-specialprotected'  => 'Erilehekülgi ei saa redigeerida.',

# Virus scanner
'virus-badscanner'     => "Viga konfiguratsioonis: tundmatu viirusetõrje: ''$1''",
'virus-scanfailed'     => 'skaneerimine ebaõnnestus (veakood $1)',
'virus-unknownscanner' => 'tundmatu viirusetõrje:',

# Login and logout pages
'logouttitle'                => 'Väljalogimine',
'logouttext'                 => "'''Te olete nüüd välja loginud.'''

Te võite jätkata {{SITENAME}} kasutamist anonüümselt, aga ka sama või mõne teise kasutajana uuesti [[Special:UserLogin|sisse logida]].",
'welcomecreation'            => '<h2>Tere tulemast, $1!</h2><p>Teie konto on loodud. Ärge unustage seada oma eelistusi.',
'loginpagetitle'             => 'Sisselogimine',
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
'youremail'                  => 'Teie e-posti aadress*',
'username'                   => 'Kasutajanimi:',
'uid'                        => 'Kasutaja ID:',
'prefs-memberingroups'       => 'Kuulub {{PLURAL:$1|gruppi|gruppidesse}}:',
'yourrealname'               => 'Teie tegelik nimi*',
'yourlanguage'               => 'Keel:',
'yournick'                   => 'Teie hüüdnimi (allakirjutamiseks)',
'badsig'                     => 'Sobimatu allkiri.
Palun kontrolli HTML koodi.',
'badsiglength'               => 'Sinu signatuur on liiga pikk.
See ei tohi olla pikem kui $1 {{PLURAL:$1|sümbol|sümbolit}}.',
'yourgender'                 => 'Sugu:',
'gender-unknown'             => 'Määratlemata',
'gender-male'                => 'Mees',
'gender-female'              => 'Naine',
'email'                      => 'E-post',
'prefs-help-realname'        => '* <strong>Tegelik nimi</strong> (pole kohustuslik): kui otsustate selle avaldada, kasutatakse seda Teie kaastöö seostamiseks Teiega.<br />',
'loginerror'                 => 'Viga sisselogimisel',
'prefs-help-email'           => 'Elektronpostiaadressi sisestamine ei ole kohustuslik, kuid võimaldab sul tellida parooli meeldetuletuse, kui peaksid oma parooli unustama. Samuti saad aadressi märkides anda oma identiteeti avaldamata teistele kasutajatele võimaluse enesele sõnumeid saata.',
'prefs-help-email-required'  => 'E-posti aadress on vajalik.',
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
'passwordtooshort'           => 'Sisestatud parool on vigane või liiga lühike. See peab koosnema vähemalt {{PLURAL:$1|ühest|$1}} tähemärgist ning peab erinema kasutajanimest.',
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
'mailerror'                  => 'Viga kirja saatmisel: $1',
'acct_creation_throttle_hit' => 'Wiki külastajad, kes lähtuvad sinu IP-lt on viimase ööpäeva jooksul loonud {{PLURAL:$1|ühe konto|$1 kontot}} ja suuremat arvu kasutajakontosid ei ole sellise perioodi jooksul luua lubatud.
Seega, hetkel ei saa antud IP kasutajad uusi kontosid avada.',
'emailauthenticated'         => 'Sinu e-posti aadress kinnitati: $2 kell $3.',
'emailnotauthenticated'      => 'Sinu e-posti aadress <strong>pole veel kinnitatud</strong>. E-posti kinnitamata aadressile ei saadeta.',
'noemailprefs'               => 'Järgnevate võimaluste toimimiseks on vaja sisestada e-posti aadress.',
'emailconfirmlink'           => 'Kinnita oma e-posti aadress',
'accountcreated'             => 'Konto loodud',
'createaccount-title'        => 'Konto loomine portaali {{SITENAME}}',
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
'resetpass_bad_temporary'   => 'Vale ajutine parool.

Sa võid olla juba edukalt muutnud oma parooli või küsinud uue ajutise parooli.',
'resetpass_forbidden'       => 'Paroole ei saa muuta',
'resetpass-no-info'         => 'Pead olema sisselogitud, et sellele lehele pääseda.',
'resetpass-submit-loggedin' => 'Muuda parool',
'resetpass-temp-password'   => 'Ajutine parool:',
'resetpass-logtext'         => 'Järgneb nimekiri kasutajatest, kes on lasknud oma salasõna administraatoril ümber muuta.',

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
'nowiki_sample'   => 'Sisesta formaatimata tekst',
'nowiki_tip'      => 'Ignoreeri viki vormindust',
'image_sample'    => 'Näidis.jpg',
'image_tip'       => 'Pilt',
'media_sample'    => 'Näidis.ogg',
'media_tip'       => 'Link failile',
'sig_tip'         => 'Sinu signatuur kuupäeva ja kellaajaga',
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
'missingcommenttext'               => 'Palun sisesta siit allapoole kommentaar.',
'summary-preview'                  => 'Resümee eelvaade:',
'blockedtitle'                     => 'Kasutaja on blokeeritud',
'blockedtext'                      => "<big>'''Teie kasutajanime või IP-aadressi blokeeris $1.'''</big>

Tema põhjendus on järgmine: ''$2''.

* Blokeeringu algus: $8
* Blokeeringu lõpp: $6
* Sooviti blokeerida: $7

Küsimuse arutamiseks võite pöörduda $1 või mõne teise [[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pange tähele, et Te ei saa sellele kasutajale teadet saata, kui Te pole registreerinud oma [[Special:Preferences|eelistuste lehel]] kehtivat e-posti aadressi.

Teie praegune IP on $3 ning blokeeringu number on #$5. Lisage need andmed kõigile järelepärimistele, mida kavatsete teha.",
'autoblockedtext'                  => "Teie IP-aadress blokeeriti automaatselt, sest seda kasutas teine kasutaja, kes oli blokeeritud $1 poolt.
Põhjendus on järgmine:

:''$2''

* Blokeeringu algus: $8
* Blokeeringu lõpp: $6
* Sooviti blokeerida: $7

Küsimuse arutamiseks võite pöörduda $1 või mõne teise [[{{MediaWiki:Grouppage-sysop}}|administraatori]] poole.

Pange tähele, et Te ei saa sellele kasutajale teadet saata, kui Te pole registreerinud oma [[Special:Preferences|eelistuste lehel]] kehtivat e-posti aadressi ega ole selle kasutamisest blokeeritud.

Teie praegune IP on $3 ning blokeeringu number on #$5. Lisage need andmed kõigile järelpärimistele, mida kavatsete teha.",
'blockednoreason'                  => 'põhjendust ei ole kirja pandud',
'blockedoriginalsource'            => "'''$1''' allikas on näidatud allpool:",
'whitelistedittitle'               => 'Redigeerimiseks tuleb sisse logida',
'whitelistedittext'                => 'Lehekülgede toimetamiseks peate $1.',
'nosuchsectiontitle'               => 'Sellist rubriiki ei ole',
'loginreqtitle'                    => 'Vajalik on sisselogimine',
'loginreqlink'                     => 'sisse logima',
'loginreqpagetext'                 => 'Lehekülgede vaatamiseks peate $1.',
'accmailtitle'                     => 'Parool saadetud.',
'accmailtext'                      => "Kasutajale '$1' genereeritud juhuslik parool saadeti aadressile $2.

Seda parooli on võimalik muuta ''[[Special:ChangePassword|parooli muutmise lehel]]'' peale uuele kontole sisse logimist.",
'newarticle'                       => '(Uus)',
'newarticletext'                   => "Sellist lehekülge ei ole veel loodud. Lehekülje loomiseks hakake kirjutama all olevasse tekstikasti
(lisainfo saamiseks vaadake [[{{MediaWiki:Helppage}}|juhendit]]).

Kui sattusite siia kogemata, klõpsake lihtsalt brauseri ''tagasi''-nupule või lingile ''tühista''.",
'anontalkpagetext'                 => "---- ''See on arutelulehekülg anonüümse kasutaja jaoks, kes ei ole loonud kontot või ei kasuta seda. Sellepärast tuleb meil kasutaja identifitseerimiseks kasutada tema IP-aadressi.
Sellisel IP-aadressilt võib portaali kasutada mitu inimest.
Kui oled osutatud IP kasutaja ning leiad, et siinsed kommentaarid ei puutu kuidagi sinusse, siis palun [[Special:UserLogin|loo konto või logi sisse]], et sind edaspidi teiste anonüümsete kasutajatega segi ei aetaks.''",
'noarticletext'                    => 'Käesoleval leheküljel hetkel teksti ei ole.
Võid [[Special:Search/{{PAGENAME}}|otsida pealkirjaks olevat fraasi]] teistelt lehtedelt,
<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} uurida asjassepuutuvaid logisid] või [{{fullurl:{{FULLPAGENAME}}|action=edit}} puuduva lehekülje ise luua]</span>.',
'userpage-userdoesnotexist'        => 'Kasutajakontot "$1" pole olemas.
Palun mõtle järele, kas soovid seda lehte luua või muuta.',
'clearyourcache'                   => "'''Märkus:''' Pärast salvestamist pead sa muudatuste nägemiseks oma brauseri puhvri tühjendama: '''Mozilla:''' ''ctrl-shift-r'', '''IE:''' ''ctrl-f5'', '''Safari:''' ''cmd-shift-r'', '''Konqueror''' ''f5''.",
'usercssjsyoucanpreview'           => "'''Vihje:''' Kasuta nuppu 'Näita eelvaadet' oma uue css/js testimiseks enne salvestamist.",
'usercsspreview'                   => "'''Ärge unustage, et seda versiooni teie isiklikust stiililehest pole veel salvestatud!'''",
'userjspreview'                    => "'''Ärge unustage, et see versioon teie isiklikust javascriptist on alles salvestamata!'''",
'updated'                          => '(Värskendatud)',
'note'                             => "'''Meeldetuletus:'''",
'previewnote'                      => "'''Ärge unustage, et see versioon ei ole veel salvestatud!'''",
'previewconflict'                  => 'See eelvaade näitab, kuidas ülemises toimetuskastis olev tekst hakkab välja nägema, kui otsustate salvestada.',
'editing'                          => 'Redigeerimisel on $1',
'editingsection'                   => 'Redigeerimisel on osa leheküljest $1',
'editingcomment'                   => 'Muutmisel on $1 (uus alalõik)',
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
'copyrightwarning'                 => "Pidage silmas, et kõik {{SITENAME}}'le tehtud kaastööd loetakse avaldatuks vastavalt $2 (vaata ka $1). Kui Te ei soovi, et Teie poolt kirjutatut halastamatult redigeeritakse ja omal äranägemisel kasutatakse, siis ärge seda siia salvestage.<br />
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast.<br />
'''ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!'''",
'copyrightwarning2'                => "Pidage silmas, et kõiki {{SITENAME}}'le tehtud kaastöid võidakse muuta või kustutada teiste kaastööliste poolt. Kui Te ei soovi, et Teie poolt kirjutatut halastamatult redigeeritakse, siis ärge seda siia salvestage.<br />
Te kinnitate ka, et kirjutasite selle ise või võtsite selle kopeerimiskitsenduseta allikast (vaata ka $1).<br />
'''ÄRGE SAATKE AUTORIÕIGUSEGA KAITSTUD MATERJALI ILMA LOATA!'''",
'longpagewarning'                  => "'''HOIATUS: Selle lehekülje pikkus ületab $1 kilobaiti. Mõne brauseri puhul valmistab raskusi juba 32-le kilobaidile läheneva pikkusega lehekülgede redigeerimine. Palun kaaluge selle lehekülje sisu jaotamist lühemate lehekülgede vahel.'''",
'readonlywarning'                  => "'''HOIATUS: Andmebaas on lukustatud hooldustöödeks, nii et praegu ei saa parandusi salvestada. Võite teksti hilisemaks kasutamiseks alles hoida tekstifailina.'''

Administraator, kes andmebaasi lukustas, andis järgmise selgituse: $1",
'protectedpagewarning'             => "'''HOIATUS: See lehekülg on lukustatud, nii et seda saavad redigeerida ainult administraatori õigustega kasutajad.'''",
'semiprotectedpagewarning'         => "'''Märkus:''' See lehekülg on lukustatud nii, et üksnes registreeritud kasutajad saavad seda muuta.",
'templatesused'                    => 'Sellel lehel on kasutusel järgnevad mallid:',
'templatesusedpreview'             => 'Selles eelvaates kasutatakse järgmisi malle:',
'templatesusedsection'             => 'Siin rubriigis kasutatud mallid:',
'template-protected'               => '(kaitstud)',
'template-semiprotected'           => '(osaliselt kaitstud)',
'hiddencategories'                 => 'See lehekülg kuulub {{PLURAL:$1|1 peidetud kategooriasse|$1 peidetud kategooriasse}}:',
'nocreatetitle'                    => 'Lehekülje loomine piiratud',
'nocreatetext'                     => '{{SITENAME}}l on piirangud uue lehekülje loomisel.
Te võite pöörduda tagasi ja toimetada olemasolevat lehekülge või [[Special:UserLogin|logida süsteemi või luua uus konto]].',
'nocreate-loggedin'                => 'Sul ei ole luba luua uusi lehekülgi.',
'permissionserrors'                => 'Viga õigustes',
'permissionserrorstext'            => 'Teil ei ole õigust seda teha {{PLURAL:$1|järgmisel põhjusel|järgmistel põhjustel}}:',
'permissionserrorstext-withaction' => 'Sul pole piisavalt õigusi selleks, et $2, {{PLURAL:$1|järgneval põhjusel|järgnevatel põhjustel}}:',
'recreate-deleted-warn'            => "'''Hoiatus: Te loote uuesti lehte, mis on varem kustutatud.'''

Kaaluge, kas lehe uuesti loomine on kohane.
Lehe eelnevad kustutamised:",
'deleted-notice'                   => 'See lehekülg on kustutatud.
Allpool on esitatud lehekülje kustutamislogi.',
'deletelog-fulllog'                => 'Vaata täielikku logi',
'edit-gone-missing'                => 'Polnud võimalik lehekülge uuendada.
Tundub, et see on kustutatud.',
'edit-conflict'                    => 'Redigeerimiskonflikt.',
'edit-no-change'                   => 'Sinu redigeerimist ignoreeriti, sest tekstile ei olnud tehtud muudatusi.',
'edit-already-exists'              => 'Ei saanud alustada uut lehekülge.
See on juba olemas.',

# Parser/template warnings
'parser-template-loop-warning' => 'Mallid moodustavad tsükli: [[$1]]',

# "Undo" feature
'undo-success' => 'Selle redaktsiooni käigus tehtud muudatusi saab eemaldada. Palun kontrolli allolevat võrdlust veendumaks, et tahad need muudatused tõepoolest eemaldada. Seejärel saad lehekülje salvestada.',
'undo-summary' => 'Tühistati muudatus $1, mille tegi [[Special:Contributions/$2|$2]] ([[User talk:$2|arutelu]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ei saa kontot luua',

# History pages
'viewpagelogs'           => 'Vaata selle lehe logisid',
'nohistory'              => 'Sellel leheküljel ei ole eelmisi redaktsioone.',
'currentrev'             => 'Viimane redaktsioon',
'currentrev-asof'        => 'Viimane redaktsioon ($1)',
'revisionasof'           => 'Redaktsioon: $1',
'revision-info'          => 'Redaktsioon seisuga $1 kasutajalt $2', # Additionally available: $3: revision id
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
'deletedrev'             => '[kustutatud]',
'histfirst'              => 'Esimesed',
'histlast'               => 'Viimased',
'historysize'            => '({{PLURAL:$1|1 bait|$1 baiti}})',
'historyempty'           => '(tühi)',

# Revision feed
'history-feed-title'          => 'Redigeerimiste ajalugu',
'history-feed-item-nocomment' => '$1 - $2', # user at time

# Revision deletion
'rev-deleted-comment'    => '(kommentaar eemaldatud)',
'rev-deleted-user'       => '(kasutajanimi eemaldatud)',
'rev-delundel'           => 'näita/peida',
'revisiondelete'         => 'Kustuta/taasta redaktsioone',
'revdelete-legend'       => 'Sea nähtavusele piirangud',
'revdelete-hide-text'    => 'Peida redigeerimise tekst',
'revdelete-hide-comment' => 'Peida muudatuse kommentaar',
'revdelete-hide-user'    => 'Peida toimetaja kasutajanimi/IP',
'revdelete-hide-image'   => 'Peida faili sisu',
'revdelete-log'          => 'Logi kommentaar:',
'revdelete-submit'       => 'Pöördu valitud redigeerimise juurde',
'revdel-restore'         => 'Muuda nähtavust',
'pagehist'               => 'Lehekülje ajalugu',
'deletedhist'            => 'Kustutatud ajalugu',
'revdelete-content'      => 'sisu',
'revdelete-summary'      => 'toimeta kokkuvõtet',
'revdelete-uname'        => 'kasutajanimi',
'revdelete-hid'          => 'peitsin: $1',
'revdelete-unhid'        => 'tegin nähtavaks: $1',

# History merging
'mergehistory'                     => 'Ühenda lehtede ajalood',
'mergehistory-from'                => 'Lehekülje allikas:',
'mergehistory-into'                => 'Lehekülje sihtpunkt:',
'mergehistory-go'                  => 'Näita ühendatavaid muudatusi',
'mergehistory-submit'              => 'Ühenda redaktsioonid',
'mergehistory-empty'               => 'Ühendatavaid redaktsioone ei ole.',
'mergehistory-no-source'           => 'Lehekülje allikat $1 ei ole.',
'mergehistory-no-destination'      => 'Lehekülje sihtpunkti $1 ei ole.',
'mergehistory-invalid-source'      => 'Allikaleheküljel peab olema lubatav pealkiri.',
'mergehistory-invalid-destination' => 'Sihtkoha leheküljel peab olema lubatav pealkiri.',
'mergehistory-reason'              => 'Põhjus:',

# Merge log
'revertmerge' => 'Tühista ühendamine',

# Diffs
'history-title'           => '"$1" muudatuste ajalugu',
'difference'              => '(Erinevused redaktsioonide vahel)',
'lineno'                  => 'Rida $1:',
'compareselectedversions' => 'Võrdle valitud redaktsioone',
'visualcomparison'        => 'Visuaalne võrdlus',
'wikicodecomparison'      => 'Wikitekstide võrdlus',
'editundo'                => 'eemalda',
'diff-multi'              => '({{PLURAL:$1|Ühte vahepealset muudatust|$1 vahepealset muudatust}} ei näidata.)',
'diff-removed'            => '$1 eemaldatud',
'diff-src'                => 'allikas',
'diff-width'              => 'laius',
'diff-height'             => 'kõrgus',
'diff-p'                  => "'''paragrahv'''",
'diff-blockquote'         => "'''tsitaat'''",
'diff-h1'                 => "'''pealkiri (tase 1)'''",
'diff-h2'                 => "'''pealkiri (tase 2)'''",
'diff-h3'                 => "'''pealkiri (tase 3)'''",
'diff-h4'                 => "'''pealkiri (tase 4)'''",
'diff-h5'                 => "'''pealkiri (tase 5)'''",
'diff-table'              => "'''tabel'''",
'diff-tbody'              => "'''tabeli sisu'''",
'diff-tr'                 => "'''rida'''",
'diff-th'                 => "'''päis'''",
'diff-br'                 => "'''tühik'''",
'diff-dd'                 => "'''definitsioon'''",
'diff-img'                => "'''pilt'''",
'diff-span'               => "'''ulatus'''",
'diff-a'                  => "'''link'''",
'diff-i'                  => "'''kaldkiri'''",
'diff-b'                  => "'''paks kiri'''",
'diff-strong'             => "'''tugev'''",
'diff-em'                 => "'''rõhk'''",
'diff-font'               => "'''kirjatüüp'''",
'diff-big'                => "'''suur'''",
'diff-del'                => "'''kustutatud'''",
'diff-tt'                 => "'''fikseeritud laius'''",
'diff-sub'                => "'''alaindeks'''",
'diff-sup'                => "'''ülaindeks'''",
'diff-strike'             => "'''läbi joonitud'''",

# Search results
'searchresults'                   => 'Otsingu tulemused',
'searchresults-title'             => 'Otsingu "$1" tulemused',
'searchresulttext'                => 'Lisainfot otsimise kohta vaata [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                  => 'Otsisid fraasi "[[:$1]]" ([[Special:Prefixindex/$1|kõik sõnega "$1" algavad lehed]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kõik lehed, mis sisaldavad linke artiklile "$1"]])',
'searchsubtitleinvalid'           => 'Päring "$1"',
'noexactmatch'                    => "'''Artiklit pealkirjaga \"\$1\" ei leitud.''' Võite [[:\$1|selle artikli luua]].",
'noexactmatch-nocreate'           => "'''Lehekülge pealkirjaga \"\$1\" ei eksisteeri.'''",
'titlematches'                    => 'Vasted artikli pealkirjades',
'notitlematches'                  => 'Artikli pealkirjades otsitavat ei leitud',
'textmatches'                     => 'Vasted artikli tekstides',
'notextmatches'                   => 'Artikli tekstides otsitavat ei leitud',
'prevn'                           => 'eelmised $1',
'nextn'                           => 'järgmised $1',
'viewprevnext'                    => 'Näita ($1) ($2) ($3).',
'searchmenu-legend'               => 'Otsingu sätted',
'searchmenu-new'                  => "'''Loo siia wikisse lehekülg pealkirjaga \"[[:\$1]]\"!'''",
'searchhelp-url'                  => 'Help:Juhend',
'searchprofile-articles'          => 'Sisuleheküljed',
'searchprofile-articles-and-proj' => 'Sisu- & projektileheküljed',
'searchprofile-project'           => 'Projektilehed',
'searchprofile-images'            => 'Failid',
'searchprofile-everything'        => 'Kõik',
'searchprofile-images-tooltip'    => 'Failiotsing',
'search-result-size'              => '$1 ({{PLURAL:$2|1 sõna|$2 sõna}})',
'search-redirect'                 => '(ümbersuunamine $1)',
'search-section'                  => '(alaosa $1)',
'search-suggest'                  => 'Kas Sa mõtlesid: $1',
'search-interwiki-caption'        => 'Sõsarprojektid',
'search-interwiki-default'        => '$1 tulemused:',
'search-interwiki-more'           => '(veel)',
'search-mwsuggest-enabled'        => 'ettepanekutega',
'search-mwsuggest-disabled'       => 'ettepanekuid ei ole',
'search-relatedarticle'           => 'Seotud',
'searchrelated'                   => 'seotud',
'searchall'                       => 'kõik',
'showingresults'                  => "Allpool näitame {{PLURAL:$1|'''ühte''' tulemit|'''$1''' tulemit}} alates tulemist #'''$2'''.",
'showingresultstotal'             => "Allpool näidatakse {{PLURAL:$4|'''$1'''. tulemust (otsingutulemuste koguarv '''$3''')|'''$1. - $2.''' tulemust (otsingutulemuste koguarv '''$3''')}}",
'nonefound'                       => "'''Märkus''': Otsing hõlmab vaikimisi vaid osasid nimeruume.
Kui soovid otsida ühekorraga kõigist nimeruumidest (kaasa arvatud arutelulehed, mallid, jne) kasuta
päringu ees prefiksit ''all:''. Konkreetsest nimeruumist otsimiseks kasuta prefiksina sele nimeruumi nime.",
'powersearch'                     => 'Otsi',
'powersearch-legend'              => 'Detailne otsing',
'powersearch-ns'                  => 'Otsing nimeruumidest:',
'powersearch-redir'               => 'Loetle ümbersuunamised',
'powersearch-field'               => 'Otsi fraasi',
'searchdisabled'                  => "<p>Vabandage! Otsing vikist on ajutiselt peatatud, et säilitada muude teenuste normaalne töökiirus. Otsimiseks võite kasutada allpool olevat Google'i otsinguvormi, kuid sellelt saadavad tulemused võivad olla vananenud.</p>",

# Preferences page
'preferences'               => 'Eelistused',
'mypreferences'             => 'eelistused',
'prefs-edits'               => 'Redigeerimiste arv:',
'prefsnologin'              => 'Te ei ole sisse loginud',
'prefsnologintext'          => 'Et oma eelistusi seada, peate olema <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} sisse logitud]</span>.',
'prefsreset'                => 'Teie eelistused on arvutimälu järgi taastatud.',
'qbsettings'                => 'Kiirriba sätted',
'qbsettings-none'           => 'Ei_ole',
'qbsettings-fixedleft'      => 'Püsivalt_vasakul',
'qbsettings-fixedright'     => 'Püsivalt paremal',
'qbsettings-floatingleft'   => 'Ujuvalt vasakul',
'qbsettings-floatingright'  => 'Ujuvalt paremal',
'changepassword'            => 'Muuda parool',
'skin'                      => 'Kujundus',
'skin-preview'              => 'Eelvaade',
'math'                      => 'Valemite näitamine',
'dateformat'                => 'Kuupäeva formaat',
'datedefault'               => 'Eelistus puudub',
'datetime'                  => 'Kuupäev ja kellaaeg',
'math_failure'              => 'Arusaamatu süntaks',
'math_unknown_error'        => 'Tundmatu viga',
'math_unknown_function'     => 'Tundmatu funktsioon',
'math_lexing_error'         => 'Väljalugemisviga',
'math_syntax_error'         => 'Süntaksiviga',
'prefs-personal'            => 'Kasutaja andmed',
'prefs-rc'                  => 'Viimaste muudatuste kuvamine',
'prefs-watchlist'           => 'Jälgimisloend',
'prefs-watchlist-days'      => 'Mitme päeva muudatusi näidata loendis:',
'prefs-watchlist-days-max'  => '(maksimaalne päevade arv on 7)',
'prefs-watchlist-edits'     => 'Mitu muudatust näidatakse laiendatud jälgimisloendis:',
'prefs-watchlist-edits-max' => '(maksimaalne väärtus: 1000)',
'prefs-misc'                => 'Muud seaded',
'prefs-resetpass'           => 'Muuda parooli',
'saveprefs'                 => 'Salvesta eelistused',
'resetprefs'                => 'Lähtesta eelistused',
'restoreprefs'              => 'Taasta kõikjal vaikesätted',
'textboxsize'               => 'Redigeerimisseaded',
'prefs-edit-boxsize'        => 'Toimetamise akna suurus.',
'rows'                      => 'Redaktoriakna ridade arv:',
'columns'                   => 'Veergude arv',
'searchresultshead'         => 'Otsingutulemite sätted',
'resultsperpage'            => 'Tulemeid leheküljel',
'contextlines'              => 'Ridu tulemis',
'contextchars'              => 'Konteksti pikkus real',
'recentchangesdays'         => 'Mitu päeva näidata viimastes muudatustes:',
'recentchangescount'        => 'Mitut pealkirja näidata vaikimisi viimaste muudatuste lehel, artiklite ajaloolehtedel ja logides:',
'savedprefs'                => 'Teie eelistused on salvestatud.',
'timezonelegend'            => 'Ajavöönd:',
'timezonetext'              => 'Kohaliku aja ja serveri aja (maailmaaja) vahe tundides.',
'localtime'                 => 'Kohalik aeg:',
'timezoneselect'            => 'Ajavöönd:',
'timezoneoffset'            => 'Ajavahe¹:',
'servertime'                => 'Serveri aeg:',
'guesstimezone'             => 'Loe aeg brauserist',
'timezoneregion-africa'     => 'Aafrika',
'timezoneregion-america'    => 'Ameerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic'     => 'Arktika',
'timezoneregion-asia'       => 'Aasia',
'timezoneregion-atlantic'   => 'Atlandi ookean',
'timezoneregion-australia'  => 'Austraalia',
'timezoneregion-europe'     => 'Euroopa',
'timezoneregion-indian'     => 'India ookean',
'timezoneregion-pacific'    => 'Vaikne ookean',
'allowemail'                => 'Luba teistel kasutajatel mulle e-posti saata',
'prefs-searchoptions'       => 'Otsingu valikud',
'prefs-namespaces'          => 'Nimeruumid',
'defaultns'                 => 'Vaikimisi otsi järgmistest nimeruumidest:',
'default'                   => 'vaikeväärtus',
'files'                     => 'Failid',

# User rights
'userrights'                  => 'Kasutaja õiguste muutmine', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Muuda kasutajagruppi',
'userrights-user-editname'    => 'Sisesta kasutajatunnus:',
'editusergroup'               => 'Muuda kasutajagruppi',
'editinguser'                 => "Muudan kasutaja '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) õigusi",
'userrights-editusergroup'    => 'Kasutajagrupi valik',
'saveusergroups'              => 'Salvesta grupi muudatused',
'userrights-groupsmember'     => 'Kuulub gruppi:',
'userrights-groups-help'      => 'Sa võid muuta selle kasutaja kuuluvust eri kasutajagruppidesse:
* Märgitud kast tähendab, et kasutaja kuulub sellesse gruppi.
* Mitte märgitud kast tähendab, et kasutaja sellesse gruppi ei kuulu
* Aga * kasutajagrupi juures tähistab õigust, mida sa peale lisamist enam eemaldada ei saa, või siis ka vastupidi.',
'userrights-reason'           => 'Muutmise põhjus:',
'userrights-no-interwiki'     => 'Sul ei ole luba muuta kasutajaõigusi teistes vikides.',
'userrights-nodatabase'       => 'Andmebaasi $1 ei ole olemas või pole see kohalik.',
'userrights-nologin'          => 'Kasutaja õiguste muutmiseks, pead sa administreerimis õigustega kontole [[Special:UserLogin|sisse logima]].',
'userrights-notallowed'       => 'Sulle pole antud luba jagada kasutajatele õigusi.',
'userrights-changeable-col'   => 'Grupid, mida sa saad muuta',
'userrights-unchangeable-col' => 'Grupid, mida sa muuta ei saa',

# Groups
'group'               => 'Grupp:',
'group-user'          => 'Kasutajad',
'group-autoconfirmed' => 'Automaatselt kinnitatud kasutajad',
'group-bot'           => 'Robotid',
'group-sysop'         => 'Administraatorid',
'group-bureaucrat'    => 'Bürokraadid',
'group-all'           => '(kõik)',

'group-user-member'          => 'Kasutaja',
'group-autoconfirmed-member' => 'Automaatselt kinnitatud kasutaja',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administraator',
'group-bureaucrat-member'    => 'Bürokraat',

'grouppage-user'          => '{{ns:project}}:Kasutajad',
'grouppage-autoconfirmed' => '{{ns:project}}:Automaatselt kinnitatud kasutajad',
'grouppage-bot'           => '{{ns:project}}:Robotid',
'grouppage-sysop'         => '{{ns:project}}:Administraatorid',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraadid',

# Rights
'right-read'            => 'Lugeda lehekülgi',
'right-edit'            => 'Redigeerida lehekülje sisu',
'right-createpage'      => 'Luua lehekülgi (mis pole arutelu leheküljed)',
'right-createtalk'      => 'Luua arutelu lehekülgi',
'right-createaccount'   => 'Luua uusi kasutaja kontosid',
'right-minoredit'       => 'Märkida muudatusi pisimuudatustena',
'right-move'            => 'Teisaldada lehekülgi',
'right-move-subpages'   => 'Teisaldada lehekülgi koos nende alam-lehtedega',
'right-movefile'        => 'Teisaldada faile',
'right-upload'          => 'Lae faile üles',
'right-reupload'        => 'Kirjutada olemasolevaid faile üle',
'right-writeapi'        => 'Kasutada {{SITENAME}} kirjutamise liidest',
'right-delete'          => 'Kustuta lehekülgi',
'right-bigdelete'       => 'Kustutada pikka ajalooga lehekülgi',
'right-browsearchive'   => 'Otsida kustutatud lehekülgi',
'right-undelete'        => 'Taasta lehekülg',
'right-suppressionlog'  => 'Vaata privaatlogisid',
'right-block'           => 'Keelata lehekülgede muutmist mõnel kasutajal',
'right-blockemail'      => 'Keelata kasutajal e-kirjade saatmine',
'right-hideuser'        => 'Blokeeri kasutajanimi, peites see avalikkuse eest',
'right-editinterface'   => 'Muuta kasutaja liidest',
'right-editusercssjs'   => 'Redigeerida teiste kasutajate CSS ja JS faile',
'right-import'          => 'Impordi lehekülgi teistest vikidest',
'right-importupload'    => 'Impordi lehekülgi faili üleslaadimisest',
'right-patrol'          => 'Märgista teiste redigeerimised kontrollituks',
'right-patrolmarks'     => 'Vaadata viimaste muudatuste kontrollimise märkeid',
'right-unwatchedpages'  => 'Vaadata jälgimata lehekülgede nimekirja',
'right-siteadmin'       => 'Panna lukku ja lukust lahti teha andmebaasi',
'right-reset-passwords' => 'Määrata teistele kasutajatele paroole',

# User rights log
'rightslog'      => 'Kasutaja õiguste logi',
'rightslogtext'  => 'See on logi kasutajate õiguste muutuste kohta.',
'rightslogentry' => 'muutis kasutaja $1 õigusi, õigused varem $2 ning õigused nüüd $3',
'rightsnone'     => '(puuduvad)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'             => 'loe seda lehekülge',
'action-edit'             => 'muuda seda lehekülge',
'action-createpage'       => 'alusta lehekülgi',
'action-createtalk'       => 'alusta arutelulehti',
'action-createaccount'    => 'loo see kasutajakonto',
'action-minoredit'        => 'märgista see muudatus kui pisimuudatus',
'action-move'             => 'teisalda see lehekülg',
'action-movefile'         => 'teisalda see fail',
'action-delete'           => 'kustuta see lehekülg',
'action-deleterevision'   => 'kustuta see redigeerimine',
'action-deletedhistory'   => 'vaata selle lehekülje kustutatud ajalugu',
'action-browsearchive'    => 'otsi kustutatud lehekülgi',
'action-undelete'         => 'taasta see lehekülg',
'action-suppressrevision' => 'vaata üle ja taasta see peidetud redigeerimine',
'action-suppressionlog'   => 'vaata seda privaatlogi',
'action-block'            => 'blokeeri see kasutaja toimetamisest',
'action-protect'          => 'muuda selle lehekülje kaitsetasemeid',
'action-import'           => 'impordi see lehekülg teisest wikist',
'action-importupload'     => 'impordi see lehekülg faili üleslaadimisest',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|muudatus|muudatust}}',
'recentchanges'                     => 'Viimased muudatused',
'recentchanges-legend'              => 'Viimaste muudatuste seaded',
'recentchangestext'                 => 'Jälgige sellel leheküljel viimaseid muudatusi.',
'recentchanges-feed-description'    => 'Jälgi vikisse tehtud viimaseid muudatusi.',
'rcnote'                            => "Allpool on esitatud {{PLURAL:$1|'''1''' muudatus|viimased '''$1''' muudatust}} viimase {{PLURAL:$2|päeva|'''$2''' päeva}} jooksul, seisuga $4, kell $5.",
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
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|jälgiv kasutaja|jälgivat kasutajat}}]',
'newsectionsummary'                 => '/* $1 */ uus alajaotus',
'rc-enhanced-expand'                => 'Näita üksikasju (nõuab JavaScripti)',
'rc-enhanced-hide'                  => 'Peida detailid',

# Recent changes linked
'recentchangeslinked'          => 'Seotud muudatused',
'recentchangeslinked-title'    => 'Muudatused, mis on seotud "$1"-ga.',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Antud ajavahemiku jooksul ei ole lingitud lehekülgedel muudatusi tehtud.',
'recentchangeslinked-summary'  => "See on viimaste muudatuste nimekiri lehekülgedel, kuhu lähevad lingid antud leheküljelt (või antud kategooria liikmetele).
Leheküljed, mis lähevad [[Special:Watchlist|Jälgimisloendi]] koosseisu, on esiletoodud '''rasvasena'''.",
'recentchangeslinked-page'     => 'Lehekülje nimi:',
'recentchangeslinked-to'       => 'Näita hoopis muudatusi lehekülgedel, mis sellele lehele lingivad',

# Upload
'upload'               => 'Faili üleslaadimine',
'uploadbtn'            => 'Lae fail',
'reupload'             => 'Uuesti üleslaadimine',
'reuploaddesc'         => 'Tagasi üleslaadimise vormi juurde.',
'uploadnologin'        => 'sisse logimata',
'uploadnologintext'    => 'Kui Te soovite faile üles laadida, peate [[Special:UserLogin|sisse logima]].',
'uploaderror'          => 'Faili laadimine ebaõnnestus',
'uploadtext'           => "Järgnevat vormi võid kasutada failide üles laadimiseks.

Et näha või leida eelnevalt üles laetud pilte mine vaata [[Special:FileList|piltide nimekirja]].
Üleslaadimiste ajalugu saab uurida [[Special:Log/upload|üleslaadimise logist]], kustutamiste oma [[Special:Log/delete|kustutamiste logist]].

Faili lisamiseks artiklile kasuta linki ühel kujul järgnevatest:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:fail.jpg]]</nowiki></tt>''' pildi täisversiooni lisamiseks;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:fail.png|200px|thumb|left|kirjeldus]]</nowiki></tt>''' 200-pikselilise esituse loomiseks lehekülje vasakule äärele lisatavas kastis, kus 'kirjeldus' lisatakse pildiallkirjana;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:fail.ogg]]</nowiki></tt>''' helifaili-lingi loomiseks.",
'upload-permitted'     => 'Lubatud failitüübid: $1.',
'upload-preferred'     => 'Eelistatud failitüübid: $1.',
'upload-prohibited'    => 'Keelatud failitüübid: $1.',
'uploadlog'            => 'üleslaadimise logi',
'uploadlogpage'        => 'Üleslaadimise logi',
'uploadlogpagetext'    => 'Allpool on loend viimastest failide üleslaadimistest. Visuaalsema esituse nägemiseks vaata [[Special:NewFiles|uute failide galeriid]].',
'filename'             => 'Faili nimi',
'filedesc'             => 'Lühikirjeldus',
'fileuploadsummary'    => 'Info faili kohta:',
'filesource'           => 'Allikas:',
'uploadedfiles'        => 'Üleslaaditud failid',
'ignorewarning'        => 'Ignoreeri hoiatust ja salvesta fail hoiatusest hoolimata',
'ignorewarnings'       => 'Ignoreeri hoiatusi',
'illegalfilename'      => 'Faili "$1" nimi sisaldab sümboleid, mis pole pealkirjades lubatud. Palun nimetage fail ümber ja proovige uuesti.',
'badfilename'          => 'Pildi nimi on muudetud. Uus nimi on "$1".',
'filetype-banned-type' => "'''\".\$1\"''' ei ole lubatud failitüüp.  Lubatud {{PLURAL:\$3|failitüüp|failitüübid}} on  \$2.",
'filetype-missing'     => 'Failil puudub laiend (nagu näiteks ".jpg").',
'large-file'           => 'On soovitatav, et üleslaetavad failid ei oleks suuremad kui $1; selle faili suurus on $2.',
'largefileserver'      => 'Antud fail on suurem serverikonfiguratsiooni poolt lubatavast failisuurusest.',
'emptyfile'            => 'Fail, mille Te üles laadisite, paistab olevat tühi.
See võib olla tingitud vigasest failinimest.
Palun kaalutlege, kas Te tõesti soovite seda faili üles laadida.',
'fileexists'           => "Sellise nimega fail on juba olemas. Palun kontrollige '''<tt>$1</tt>''', kui te ei ole kindel, kas tahate seda muuta.",
'fileexists-thumb'     => "<center>'''Fail on olemas'''</center>",
'fileexists-forbidden' => 'Sellise nimega fail on juba olemas, seda ei saa üle kirjutada.
Palun pöörduge tagasi ja laadige fail üles mõne teise nime all. [[File:$1|thumb|center|$1]]',
'successfulupload'     => 'Üleslaadimine õnnestus',
'uploadwarning'        => 'Üleslaadimise hoiatus',
'savefile'             => 'Salvesta fail',
'uploadedimage'        => 'Fail "[[$1]]" on üles laaditud',
'overwroteimage'       => 'üles laaditud uus variant "[[$1]]"',
'uploaddisabled'       => 'Üleslaadimine hetkel keelatud',
'uploaddisabledtext'   => 'Faili üleslaadimine on keelatud.',
'uploadcorrupt'        => 'Fail on vigane või vale laiendiga. Palun kontrolli faili ja proovi seda uuesti üles laadida.',
'uploadvirus'          => 'Fail sisaldab viirust! Täpsemalt: $1',
'sourcefilename'       => 'Lähtefail:',
'destfilename'         => 'Failinimi vikis:',
'upload-maxfilesize'   => 'Maksimaalne failisuurus: $1',
'watchthisupload'      => 'Jälgi seda lehekülge',

'upload-misc-error' => 'Tundmatu viga üleslaadimisel',

'license'   => 'Litsents:',
'nolicense' => 'pole valitud',

# Special:ListFiles
'imgfile'               => 'fail',
'listfiles'             => 'Piltide loend',
'listfiles_date'        => 'Kuupäev',
'listfiles_name'        => 'Nimi',
'listfiles_user'        => 'Kasutaja',
'listfiles_size'        => 'Suurus',
'listfiles_description' => 'Kirjeldus',

# File description page
'filehist'                  => 'Faili ajalugu',
'filehist-help'             => 'Klõpsa Kuupäev/kellaaeg, et näha faili sel ajahetkel.',
'filehist-deleteall'        => 'kustuta kõik',
'filehist-deleteone'        => 'kustuta see',
'filehist-revert'           => 'taasta',
'filehist-current'          => 'viimane',
'filehist-datetime'         => 'Kuupäev/kellaaeg',
'filehist-thumb'            => 'Pöialpilt',
'filehist-thumbtext'        => 'Pöialpilt $1 versioonile',
'filehist-user'             => 'Kasutaja',
'filehist-dimensions'       => 'Mõõtmed',
'filehist-filesize'         => 'Faili suurus',
'filehist-comment'          => 'Kommentaar',
'imagelinks'                => 'Viited failile',
'linkstoimage'              => 'Sellele pildile {{PLURAL:$1|viitab järgmine lehekülg|viitavad järgmised leheküljed}}:',
'nolinkstoimage'            => 'Sellele pildile ei viita ükski lehekülg.',
'sharedupload'              => 'See fail pärineb allikast $1 ning võib olla kasutusel ka teistes projektides.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki-desc'     => 'Sealne $1 on toodud allpool.',
'shareduploadwiki-linktext' => 'faili kirjelduse lehekülg',
'noimage'                   => 'Sellise nimega faili pole, võite selle $1.',
'noimage-linktext'          => 'üles laadida',
'uploadnewversion-linktext' => 'Lae üles selle faili uus versioon',

# File deletion
'filedelete'                  => 'Kustuta $1',
'filedelete-legend'           => 'Kustuta fail',
'filedelete-comment'          => 'Kustutamise põhjus:',
'filedelete-submit'           => 'Kustuta',
'filedelete-success'          => "'''$1''' on kustutatud.",
'filedelete-otherreason'      => 'Muu/täiendav põhjus',
'filedelete-reason-otherlist' => 'Muu põhjus',
'filedelete-reason-dropdown'  => '*Harilikud kustutamise põhjused
** Autoriõiguste rikkumine
** Duplikaat',
'filedelete-edit-reasonlist'  => 'Redigeeri kustutamise põhjuseid',

# MIME search
'mimesearch' => 'MIME otsing',
'mimetype'   => 'MIME tüüp:',

# Unwatched pages
'unwatchedpages' => 'Jälgimata lehed',

# List redirects
'listredirects' => 'Ümbersuunamised',

# Unused templates
'unusedtemplates'     => 'Kasutamata mallid',
'unusedtemplatestext' => 'See lehekülg loetleb kõik leheküljed nimeruumis {{ns:template}}, mida teistel lehekülgedel ei kasutata. Enne kustutamist palun kontrollige, kas siia pole muid linke.',
'unusedtemplateswlh'  => 'teised lingid',

# Random page
'randompage' => 'Juhuslik artikkel',

# Random redirect
'randomredirect' => 'Juhuslik ümbersuunamine',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Lehekülgede statistika',
'statistics-header-edits'      => 'Redigeerimise statistika',
'statistics-header-users'      => 'Kasutajate statistika',
'statistics-articles'          => 'Sisulehekülgi',
'statistics-pages'             => 'Lehekülgi',
'statistics-pages-desc'        => 'Kõik lehed wikis, kaasa arvatud arutelulehed, ümbersuunamised jne.',
'statistics-files'             => 'Üleslaaditud faile',
'statistics-edits'             => 'Redigeerimisi alates {{SITENAME}} loomisest',
'statistics-edits-average'     => 'Keskmiselt redigeerimisi lehekülje kohta',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue Tööjärje] pikkus',
'statistics-users'             => 'Registreeritud [[Special:ListUsers|kasutajaid]]',
'statistics-users-active'      => 'Aktiivseid kasutajaid',
'statistics-users-active-desc' => 'Kasutajad, kes on viimase {{PLURAL:$1|päeva|$1 päeva}} jooksul tegutsenud',

'disambiguations' => 'Täpsustusleheküljed',

'doubleredirects'     => 'Kahekordsed ümbersuunamised',
'doubleredirectstext' => 'Igal real on ära toodud esimene ja teine ümbersuunamisleht ning samuti teise ümbersuunamislehe viide, mis tavaliselt on viiteks, kuhu esimene ümbersuunamisleht peaks otse suunama.',

'brokenredirects'        => 'Vigased ümbersuunamised',
'brokenredirectstext'    => 'Järgmised leheküljed on ümber suunatud olematutele lehekülgedele:',
'brokenredirects-edit'   => '(redigeeri)',
'brokenredirects-delete' => '(kustuta)',

'withoutinterwiki' => 'Keelelinkideta leheküljed',

'fewestrevisions' => 'Leheküljed, kus on kõige vähem muudatusi tehtud',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bait|baiti}}',
'ncategories'             => '$1 {{PLURAL:$1|kategooria|kategooriat}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|linki}}',
'nmembers'                => '$1 {{PLURAL:$1|liige|liiget}}',
'nrevisions'              => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}}',
'nviews'                  => '$1 {{PLURAL:$1|külastus|külastust}}',
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
'wantedfiles'             => 'Kõige oodatumad failid',
'mostlinked'              => 'Kõige viidatumad leheküljed',
'mostlinkedcategories'    => 'Kõige viidatumad kategooriad',
'mostlinkedtemplates'     => 'Kõige viidatumad mallid',
'mostcategories'          => 'Enim kategoriseeritud artiklid',
'mostimages'              => 'Kõige kasutatumad failid',
'mostrevisions'           => 'Kõige pikema redigeerimislooga artiklid',
'prefixindex'             => 'Kõik pealkirjad prefiksiga',
'shortpages'              => 'Lühikesed artiklid',
'longpages'               => 'Pikad artiklid',
'deadendpages'            => 'Edasipääsuta artiklid',
'deadendpagestext'        => 'Järgmised leheküljed ei viita ühelegi teisele Viki leheküljele.',
'protectedpages'          => 'Kaitstud leheküljed',
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
'pager-newer-n'           => '{{PLURAL:$1|uuem 1|uuemad $1}}',
'pager-older-n'           => '{{PLURAL:$1|vanem 1|vanemad $1}}',

# Book sources
'booksources'               => 'Otsi raamatut',
'booksources-search-legend' => 'Otsi raamatut',
'booksources-go'            => 'Mine',

# Special:Log
'specialloguserlabel'  => 'Kasutaja:',
'speciallogtitlelabel' => 'Pealkiri:',
'log'                  => 'Logid',
'all-logs-page'        => 'Kõik logid',
'alllogstext'          => 'See on kombineeritud vaade üleslaadimise, kustutamise, kaitsmise, blokeerimise ja administraatorilogist. Valiku kitsendamiseks vali soovitav logitüüp, sisesta kasutajanimi (tõstutundlik) või huvi pakkuva lehekülge pealkiri (tõstutundlik).',
'logempty'             => 'Logides vastavad kirjed puuduvad.',

# Special:AllPages
'allpages'          => 'Kõik artiklid',
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
'categories'         => 'Kategooriad',
'categoriespagetext' => 'Vikis on järgmised kategooriad.
Siin ei näidata [[Special:UnusedCategories|Unused categories]].
Vaata ka [[Special:WantedCategories|wanted categories]].',
'categoriesfrom'     => 'Näita kategooriaid alates:',

# Special:DeletedContributions
'deletedcontributions' => 'Kasutaja kustutatud kaastööd',

# Special:LinkSearch
'linksearch'    => 'Välislingid',
'linksearch-ns' => 'Nimeruum:',
'linksearch-ok' => 'Otsi',

# Special:ListUsers
'listusersfrom'      => 'Näita kasutajaid alustades:',
'listusers-submit'   => 'Näita',
'listusers-noresult' => 'Kasutajat ei leitud.',

# Special:Log/newusers
'newuserlogpage'              => 'Kasutaja loomise logi',
'newuserlogpagetext'          => 'See logi sisaldab infot äsja loodud uute kasutajate kohta.',
'newuserlog-byemail'          => 'parool saadetud e-postiga',
'newuserlog-create-entry'     => 'Uus kasutaja',
'newuserlog-create2-entry'    => 'loodud uus konto $1',
'newuserlog-autocreate-entry' => 'Konto loodud automaatselt',

# Special:ListGroupRights
'listgrouprights'               => 'Kasutajagrupi õigused',
'listgrouprights-group'         => 'Grupp',
'listgrouprights-rights'        => 'Õigused',
'listgrouprights-helppage'      => 'Help:Grupi õigused',
'listgrouprights-members'       => '(liikmete loend)',
'listgrouprights-right-display' => '$1 ($2)',

# E-mail user
'mailnologintext' => 'Te peate olema [[Special:UserLogin|sisse logitud]] ja teil peab [[Special:Preferences|eelistustes]] olema kehtiv e-posti aadress, et saata teistele kasutajatele e-kirju.',
'emailuser'       => 'Saada sellele kasutajale e-kiri',
'emailpage'       => 'Saada kasutajale e-kiri',
'emailpagetext'   => 'Kui see kasutaja on oma eelistuste lehel sisestanud e-posti aadressi, siis saate alloleva vormi kaudu talle kirja saata. Et kasutaja saaks vastata, täidetakse kirja saatja väli "kellelt" e-posti aadressiga, mille olete sisestanud [[Special:Preferences|oma eelistuste lehel]].',
'emailfrom'       => 'Kellelt:',
'emailto'         => 'Kellele:',
'emailsubject'    => 'Pealkiri:',
'emailmessage'    => 'Sõnum:',
'emailsend'       => 'Saada',
'emailccme'       => 'Saada mulle koopia.',
'emailsent'       => 'E-post saadetud',
'emailsenttext'   => 'Teie sõnum on saadetud.',

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
'watchnochange'        => 'Valitud perioodi jooksul ei ole üheski jälgitavas artiklis muudatusi tehtud.',
'watchlist-details'    => 'Jälgimisloendis on {{PLURAL:$1|$1 lehekülg|$1 lehekülge}} (ei arvestata arutelulehekülgi).',
'wlheader-showupdated' => "* Leheküljed, mida on muudetud peale sinu viimast külastust, on '''rasvases kirjas'''",
'watchmethod-list'     => 'jälgitavate lehekülgede viimased muudatused',
'watchlistcontains'    => 'Sinu jälgimisloendis on $1 {{PLURAL:$1|artikkel|artiklit}}.',
'wlnote'               => "Allpool on {{PLURAL:$1|viimane muudatus|viimased '''$1''' muudatust}} viimase {{PLURAL:$2|tunni|'''$2''' tunni}} jooksul.",
'wlshowlast'           => 'Näita viimast $1 tundi $2 päeva. $3',
'watchlist-options'    => 'Jälgimisloendi võimalused',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'jälgin...',
'unwatching' => 'Jälgimise lõpetamine...',

'enotif_reset'       => 'Märgi kõik lehed loetuks',
'enotif_newpagetext' => 'See on uus lehekülg.',
'changed'            => 'muudetud',

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
'deletedtext'            => '"<nowiki>$1</nowiki>" on kustutatud. $2 lehel on nimekiri viimastest kustutatud lehekülgedest.',
'deletedarticle'         => '"$1" kustutatud',
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
'rollback'       => 'Tühista muudatused',
'rollback_short' => 'Tühista',
'rollbacklink'   => 'tühista',
'rollbackfailed' => 'Muudatuste tühistamine ebaõnnestus',
'cantrollback'   => 'Ei saa muudatusi tagasi pöörata; viimane kaastööline on artikli ainus autor.',
'editcomment'    => "Redaktsiooni kokkuvõte: \"''\$1''\".", # only shown if there is an edit comment
'revertpage'     => 'Tühistati kasutaja [[Special:Contributions/$2|$2]] ([[User talk:$2|arutelu]]) tehtud muudatused ning pöörduti tagasi viimasele muudatusele, mille tegi [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectlogpage'              => 'Kaitsmise logi',
'protectlogtext'              => 'Allpool on loetletud lehekülgede kaitsmised ja kaitsete eemaldamised. Praegu kaitstud lehekülgi vaata [[Special:ProtectedPages|kaitstud lehtede loetelust]].',
'protectedarticle'            => 'kaitses lehekülje "[[$1]]"',
'modifiedarticleprotection'   => 'lehe "[[$1]]" kaitsmismäära muudeti',
'unprotectedarticle'          => 'eemaldas lehekülje "[[$1]]" kaitse',
'protect-title'               => '"$1" kaitsmine',
'prot_1movedto2'              => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla',
'protect-legend'              => 'Kinnita kaitsmine',
'protectcomment'              => 'Põhjus',
'protectexpiry'               => 'Aegub:',
'protect_expiry_invalid'      => 'Sobimatu aegumise tähtaeg.',
'protect_expiry_old'          => 'Aegumise tähtaeg on minevikus.',
'protect-unchain'             => 'Võimalda lehekülje teisaldamist.',
'protect-text'                => "Siin võite vaadata ja muuta lehekülje '''<nowiki>$1</nowiki>''' kaitsesätteid.",
'protect-locked-access'       => "Teie konto ei oma õiguseid muuta lehekülje kaitstuse taset.
Allpool on toodud lehekülje '''$1''' hetkel kehtivad seaded:",
'protect-cascadeon'           => 'See lehekülg on kaitstud, kuna ta on kasutusel {{PLURAL:$1|järgmisel leheküljel|järgmistel lehekülgedel}}, mis on omakorda kaskaadkaitse all.
Sa saad muuta selle lehekülje kaitse staatust, kuid see ei mõjuta kaskaadkaitset.',
'protect-default'             => 'Luba kõigile kasutajatele',
'protect-fallback'            => 'Require "$1" permission
Nõuab "$1" õiguseid',
'protect-level-autoconfirmed' => 'Blokeeri uued ja registreerimata kasutajad',
'protect-level-sysop'         => 'Ainult administraatorid',
'protect-summary-cascade'     => 'kaskaad',
'protect-expiring'            => 'aegub $1 (UTC)',
'protect-expiry-indefinite'   => 'määramatu',
'protect-cascade'             => 'Kaitse lehekülgi, mis on lülitatud käesoleva lehekülje koosseisu (kaskaadkaitse)',
'protect-cantedit'            => 'Te ei saa muuta selle lehekülje kaitstuse taset, sest Teile pole selleks luba antud.',
'protect-dropdown'            => '*Tavalised kaitsmise põhjused
** Liigne vandalism
** Liigne spämmimine
** Counter-productive edit warring
** Kõrge liiklusega lehekülg',
'protect-edit-reasonlist'     => 'Muudatuste eest kaitsmise põhjused',
'protect-expiry-options'      => '1 tund:1 hour,1 päev:1 day,1 nädal:1 week,2 nädalat: 2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Lubatud:',
'restriction-level'           => 'Kaitsmise tase:',
'minimum-size'                => 'Min suurus',
'maximum-size'                => 'Max suurus:',
'pagesize'                    => '(baiti)',

# Restrictions (nouns)
'restriction-edit'   => 'Redigeerimine',
'restriction-move'   => 'Teisaldamine',
'restriction-create' => 'Loomine',
'restriction-upload' => 'Lae üles',

# Restriction levels
'restriction-level-sysop'         => 'täielikult kaitstud',
'restriction-level-autoconfirmed' => 'poolkaitstud',
'restriction-level-all'           => 'kõik tasemed',

# Undelete
'undelete'                  => 'Taasta kustutatud lehekülg',
'undeletepage'              => 'Kuva ja taasta kustutatud lehekülgi',
'viewdeletedpage'           => 'Vaata kustutatud lehekülgi',
'undeletepagetext'          => '{{PLURAL:$1|Järgnev lehekülg on kustutatud|Järgnevad leheküljed on kustutatud}}, kuid arhiivis veel olemas ja taastatavad. Arhiivi sisu kustutatakse perioodiliselt.',
'undelete-fieldset-title'   => 'Taasta redigeerimised',
'undeleteextrahelp'         => "Kogu lehe ja selle ajaloo taastamiseks jätke kõik linnukesed tühjaks ja vajutage '''''Taasta'''''.
Et taastada valikuliselt, tehke linnukesed kastidesse, mida soovite taastada ja vajutage '''''Taasta'''''.
Nupu '''''Tühjenda''''' vajutamine tühjendab põhjusevälja ja eemaldab kõik linnukesed.",
'undeleterevisions'         => '$1 arhiveeritud {{PLURAL:$1|redaktsioon|redaktsiooni}}.',
'undeletehistory'           => 'Kui taastate lehekülje, taastuvad kõik versioonid artikli ajaloona. 
Kui vahepeal on loodud uus samanimeline lehekülg, ilmuvad taastatud versioonid varasema ajaloona.',
'undeletehistorynoadmin'    => 'See artikkel on kustutatud. Kustutamise põhjus ning selle lehekülje redigeerimislugu enne kustutamist on näha allolevas kokkuvõttes. Artikli kustutamiseelsete redaktsioonide tekst on kättesaadav ainult administraatoritele.',
'undeletebtn'               => 'Taasta',
'undeletelink'              => 'vaata/taasta',
'undeletereset'             => 'Tühjenda',
'undeletecomment'           => 'Põhjus:',
'undeletedarticle'          => '"$1" taastatud',
'undeletedrevisions'        => '$1 {{PLURAL:$1|redaktsioon|redaktsiooni}} taastatud',
'cannotundelete'            => 'Taastamine ebaõnnestus; keegi teine võis lehe juba taastada.',
'undelete-search-box'       => 'Otsi kustutatud lehekülgi',
'undelete-search-prefix'    => 'Näita lehekülgi, mille pealkiri algab nii:',
'undelete-search-submit'    => 'Otsi',
'undelete-show-file-submit' => 'Jah',

# Namespace form on various pages
'namespace'      => 'Nimeruum:',
'invert'         => 'Näita kõiki peale valitud nimeruumi',
'blanknamespace' => '(Artiklid)',

# Contributions
'contributions'       => 'Kasutaja kaastööd',
'contributions-title' => 'Kasutaja $1 kaastööd',
'mycontris'           => 'Kaastöö',
'contribsub2'         => 'Kasutaja "$1 ($2)" jaoks',
'nocontribs'          => 'Antud kriteeriumile vastavaid muudatusi ei leidnud.', # Optional parameter: $1 is the user name
'uctop'               => ' (üles)',
'month'               => 'Alates kuust (ja varasemad):',
'year'                => 'Alates aastast (ja varasemad):',

'sp-contributions-newbies'     => 'Näita ainult uute kasutajate kaastööd.',
'sp-contributions-newbies-sub' => 'Uued kasutajad',
'sp-contributions-blocklog'    => 'Blokeerimise logi',
'sp-contributions-logs'        => 'logid',
'sp-contributions-search'      => 'Otsi kaastöid',
'sp-contributions-username'    => 'IP aadress või kasutajanimi:',
'sp-contributions-submit'      => 'Otsi',

# What links here
'whatlinkshere'            => 'Lingid siia',
'whatlinkshere-title'      => 'Leheküljed, mis viitavad lehele "$1"',
'whatlinkshere-page'       => 'Lehekülg:',
'linkshere'                => "Lehele '''[[:$1]]''' viitavad järgmised leheküljed:",
'nolinkshere'              => "Lehele '''[[:$1]]''' ei viita ükski lehekülg.",
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
'blockip'                    => 'Blokeeri IP-aadress',
'blockip-legend'             => 'Blokeeri kasutaja',
'blockiptext'                => "See vorm on kirjutamisõiguste blokeerimiseks konkreetselt IP-aadressilt.
'''Seda tohib teha ainult vandalismi vältimiseks ning kooskõlas [[{{MediaWiki:Policy-url}}|{{SITENAME}} sisekorraga]]'''.
Kindlasti tuleb täita ka väli \"põhjus\", paigutades sinna näiteks viited konkreetsetele lehekülgedele, mida rikuti.",
'ipaddress'                  => 'IP-aadress',
'ipadressorusername'         => 'IP-aadress või kasutajanimi',
'ipbexpiry'                  => 'Kehtivus',
'ipbreason'                  => 'Põhjus',
'ipbreasonotherlist'         => 'Muul põhjusel',
'ipbreason-dropdown'         => '*Tavalised blokeerimise põhjused
** Lehtedelt sisu kustutamine
** Sodimine
** Taunitav käitumine, isiklikud rünnakud
** Mittesobiv kasutajanimi
** Spämmi levitamine
** Vale info levitamine',
'ipbanononly'                => 'Blokeeri ainult anonüümsed kasutajad',
'ipbcreateaccount'           => 'Takista konto loomist',
'ipbemailban'                => 'Takista kasutaja poolt ka e-maili saatmine',
'ipbenableautoblock'         => "Blokeeri automaatselt ka selle kasutaja poolt kasutatud IP aadress, ning ka kõik sarnased IP'd millelt võidakse proovida sodida",
'ipbsubmit'                  => 'Blokeeri see aadress',
'ipbother'                   => 'Muu tähtaeg',
'ipboptions'                 => '2 tundi:2 hours,1 päev:1 day,3 päeva:3 days,1 nädal:1 week,2 nädalat:2 weeks,1 kuu:1 month,3 kuud:3 months,6 kuud:6 months,1 aasta:1 year,igavene:infinite', # display1:time1,display2:time2,...
'ipbotheroption'             => 'muu tähtaeg',
'ipbotherreason'             => 'Muu/täiendav põhjus:',
'ipbwatchuser'               => 'Jälgi selle kasutaja lehekülge ja arutelu',
'ipballowusertalk'           => 'Luba kasutajal vaatamata blokeeringule, siiski muuta enese arutelu lehekülge',
'badipaddress'               => 'The IP address is badly formed.',
'blockipsuccesssub'          => 'Blokeerimine õnnestus',
'blockipsuccesstext'         => '[[Special:Contributions/$1|$1]] on blokeeritud.<br />
Kehtivaid blokeeringuid vaata [[Special:IPBlockList|blokeeringute loendist]].',
'ipb-blocklist'              => 'Vaata kehtivaid keelde',
'unblockip'                  => 'Lõpeta IP aadressi blokeerimine',
'unblockiptext'              => 'Kasutage allpool olevat vormi redigeerimisõiguste taastamiseks varem blokeeritud IP aadressile.',
'unblocked'                  => '[[User:$1|$1]] blokeering võeti maha.',
'unblocked-id'               => 'Blokeerimine $1 on lõpetatud',
'ipblocklist'                => 'Blokeeritud IP-aadresside ja kasutajakontode loend',
'blocklistline'              => '$1, $2 blokeeris $3 ($4)',
'infiniteblock'              => 'igavene',
'expiringblock'              => 'aegub $1',
'ipblocklist-empty'          => 'Blokeerimiste loend on tühi.',
'blocklink'                  => 'blokeeri',
'unblocklink'                => 'lõpeta blokeerimine',
'change-blocklink'           => 'muuda blokeeringut',
'contribslink'               => 'kaastöö',
'autoblocker'                => 'Autoblokeeritud kuna teie IP aadress on hiljut kasutatud "[[User:$1|$1]]" poolt. $1-le antud bloki põhjus on "\'\'\'$2\'\'\'"',
'blocklogpage'               => 'Blokeerimise logi',
'blocklogentry'              => 'blokeeris "[[$1]]". Blokeeringu aegumistähtaeg on $2 $3',
'blocklogtext'               => 'See on kasutajate blokeerimiste ja blokeeringute eemaldamiste nimekiri. Automaatselt blokeeritud IP aadresse siin ei näidata. Hetkel aktiivsete blokeeringute ja redigeerimiskeeldude nimekirja vaata [[Special:IPBlockList|IP blokeeringute nimekirja]] leheküljelt.',
'unblocklogentry'            => '"$1" blokeerimine lõpetatud',
'block-log-flags-nocreate'   => 'kontode loomine on blokeeritud',
'block-log-flags-noemail'    => 'e-mail blokeeritud',
'block-log-flags-nousertalk' => 'ei saa muuta enda arutelulehte',
'proxyblockreason'           => 'Teie IP aadress on blokeeritud, sest see on anonüümne proxy server. Palun kontakteeruga oma internetiteenuse pakkujaga või tehnilise toega ning informeerige neid sellest probleemist.',
'proxyblocksuccess'          => 'Tehtud.',
'cant-block-while-blocked'   => 'Teisi kasutajaid ei saa blokeerida, kui oled ise blokeeritud.',

# Developer tools
'lockdb'              => 'Lukusta andmebaas',
'unlockdb'            => 'Tee andmebaas lukust lahti',
'lockconfirm'         => 'Jah, ma soovin andmebaasi lukustada.',
'unlockconfirm'       => 'Jah, ma tõesti soovin andmebaasi lukust avada.',
'lockbtn'             => 'Võta andmebaas kirjutuskaitse alla',
'unlockbtn'           => 'Taasta andmebaasi kirjutuspääs',
'lockdbsuccesssub'    => 'Andmebaas kirjutuskaitse all',
'unlockdbsuccesssub'  => 'Kirjutuspääs taastatud',
'lockdbsuccesstext'   => 'Andmebaas on nüüd kirjutuskaitse all.
<br />Kui Teie hooldustöö on läbi, ärge unustage kirjutuspääsu taastada!',
'unlockdbsuccesstext' => 'Andmebaasi kirjutuspääs on taastatud.',

# Move page
'move-page-legend'        => 'Teisalda artikkel',
'movepagetext'            => "Allolevat vormi kasutades saate lehekülje ümber nimetada.
Lehekülje ajalugu tõstetakse uue pealkirja alla automaatselt.
Praeguse pealkirjaga leheküljest saab ümbersuunamisleht uuele leheküljele.
Teistes artiklites olevaid linke praeguse nimega leheküljele automaatselt ei muudeta.
Teie kohuseks on hoolitseda, et ei tekiks topeltümbersuunamisi ning et kõik jääks toimima nagu enne ümbernimetamist.

Lehekülge '''ei nimetata ümber''' juhul, kui uue nimega lehekülg on juba olemas. Erandiks on juhud, kui olemasolev lehekülg on tühi või ümbersuunamislehekülg ja sellel pole redigeerimisajalugu.
See tähendab, et te ei saa kogemata üle kirjutada juba olemasolevat lehekülge, kuid saate ebaõnnestunud ümbernimetamise tagasi pöörata.

'''ETTEVAATUST!'''
Võimalik, et kavatsete teha ootamatut ning drastilist muudatust väga loetavasse artiklisse;
enne muudatuse tegemist mõelge palun järele, mis võib olla selle tagajärjeks.",
'movepagetalktext'        => "Koos artiklileheküljega teisaldatakse automaatselt ka arutelulehekülg, '''välja arvatud juhtudel, kui:'''
*liigutate lehekülge ühest nimeruumist teise,
*uue nime all on juba olemas mittetühi arutelulehekülg või
*jätate alumise kastikese märgistamata.

Neil juhtudel teisaldage arutelulehekülg soovi korral eraldi või ühendage ta omal käel uue aruteluleheküljega.",
'movearticle'             => 'Teisalda artiklilehekülg',
'movenologin'             => 'Te ei ole sisse loginud',
'movenologintext'         => 'Et lehekülge teisaldada, peate registreeruma
kasutajaks ja [[Special:UserLogin|sisse logima]]',
'newtitle'                => 'Uue pealkirja alla',
'move-watch'              => 'Jälgi seda lehekülge',
'movepagebtn'             => 'Teisalda artikkel',
'pagemovedsub'            => 'Artikkel on teisaldatud',
'movepage-moved'          => '<big>\'\'\'"$1" teisaldatud pealkirja "$2" alla\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Selle nimega artikkel on juba olemas või pole valitud nimi lubatav. Palun valige uus nimi.',
'cantmove-titleprotected' => 'Lehte ei saa sinna teisaldada, sest uus pealkiri on artikli loomise eest kaitstud',
'talkexists'              => 'Artikkel on teisaldatud, kuid arutelulehekülge ei saanud teisaldada, sest uue nime all on arutelulehekülg juba olemas. Palun ühendage aruteluleheküljed ise.',
'movedto'                 => 'Teisaldatud pealkirja alla:',
'movetalk'                => 'Teisalda ka "arutelu", kui saab.',
'1movedto2'               => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla',
'1movedto2_redir'         => 'Lehekülg "[[$1]]" teisaldatud pealkirja "[[$2]]" alla ümbersuunamisega',
'movelogpage'             => 'Teisaldamise logi',
'movelogpagetext'         => 'See logi sisaldab infot lehekülgede teisaldamistest.',
'movereason'              => 'Põhjus',
'revertmove'              => 'taasta',
'delete_and_move'         => 'Kustuta ja teisalda',
'delete_and_move_confirm' => 'Jah, kustuta lehekülg',
'delete_and_move_reason'  => 'Kustutatud, et asemele tõsta teine lehekülg',

# Export
'export'        => 'Lehekülgede eksport',
'exporttext'    => 'Sa saad siin eksportida kindla lehekülje või nende kogumi, tekstid, koos kogu nende muudatuste ajalooga, XML kujule viiduna. Seda saad sa vajadusel kasutada teksti ülekandmiseks teise vikisse, kasutades selleks MediaWiki [[Special:Import|impordi lehekülge]].

Et eksportida lehekülgi, sisesta nende pealkirjad all olevasse teksti kasti, iga pealkiri ise reale, ning vali kas sa soovid saada leheküljest kõiki selle vanemaid versioone (muudatusi) või soovid sa saada leheküljest vaid hetke versiooni.

Viimasel juhul võid sa näiteks "[[{{MediaWiki:Mainpage}}]]" lehekülje, jaoks kasutada samuti linki kujul:  [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportcuronly' => 'Lisa vaid viimane versioon lehest, ning mitte kogu ajalugu',

# Namespace 8 related
'allmessages'        => 'Kõik süsteemi sõnumid',
'allmessagesname'    => 'Nimi',
'allmessagesdefault' => 'Vaikimisi tekst',
'allmessagescurrent' => 'Praegune tekst',
'allmessagestext'    => 'See on loend kõikidest kättesaadavatest süsteemi sõnumitest MediaWiki: nimeruumis.
Kui soovid MediaWiki tarkvara tõlkimises osaleda siis vaata lehti [http://www.mediawiki.org/wiki/Localisation MediaWiki Lokaliseerimine] ja [http://translatewiki.net translatewiki.net].',

# Thumbnails
'thumbnail-more'  => 'Suurenda',
'filemissing'     => 'Fail puudub',
'thumbnail_error' => 'Viga pisipildi loomisel: $1',

# Special:Import
'import'                   => 'Lehekülgede import',
'importinterwiki'          => 'Vikidevaheline import',
'import-upload-filename'   => 'Failinimi:',
'import-comment'           => 'Kommentaar:',
'importtext'               => 'Palun ekspordi fail allikaks olevast vikist kasutades [[Special:Export|lehekülgede ekspordi vahendit]].
Salvesta see oma arvutisse ning lae see siia ülesse.',
'importstart'              => 'Impordin lehekülgi...',
'import-revision-count'    => '$1 {{PLURAL:$1|versioon|versiooni}}',
'importnopages'            => 'Ei olnud imporditavaid lehekülgi.',
'importfailed'             => 'Importimine ebaõnnestus: <nowiki>$1</nowiki>',
'importunknownsource'      => 'Unknown import source type
Tundmatu tüüpi algallikas',
'importcantopen'           => 'Ei saa imporditavat faili avada',
'importbadinterwiki'       => 'Vigane interwiki link',
'importnotext'             => 'Tühi või ilma tekstita',
'importsuccess'            => 'Importimine edukalt lõpetatud!',
'importhistoryconflict'    => 'Konfliktne muudatuste ajalugu (võimalik, et seda lehekülge juba varem imporditud)',
'importnosources'          => 'Ühtegi transwiki impordiallikat ei ole defineeritud ning ajaloo otseimpordi funktsioon on välja lülitatud.',
'importnofile'             => 'Faili importimiseks, ei laetud ühtki faili ülesse.',
'importuploaderrorsize'    => 'Üleslaaditava faili import ebaõnnestus.
Fail on lubatust suurem.',
'importuploaderrorpartial' => 'Üleslaaditava faili import ebaõnnestus.
Fail oli vaid osaliselt üleslaetud.',
'importuploaderrortemp'    => 'Üleslaaditava faili import ebaõnnestus.
Puudub ajutine kataloog.',
'import-noarticle'         => 'Ühtki lehekülge polnud importida!',

# Import log
'importlogpage'          => 'Impordi logi',
'import-logentry-upload' => 'faili impordi abil imporditud [[$1]] lehekülg',

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
'tooltip-ca-addsection'           => 'Algata uus alajaotis',
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
'tooltip-t-whatlinkshere'         => 'Kõik Viki leheküljed, mis siia viitavad',
'tooltip-t-recentchangeslinked'   => 'Viimased muudatused lehekülgedel, milledele on siit viidatud',
'tooltip-feed-rss'                => 'Selle lehekülje RSS sööt',
'tooltip-feed-atom'               => 'Selle lehekülje Atom sööt',
'tooltip-t-contributions'         => 'Kuva selle kasutaja kaastööd',
'tooltip-t-emailuser'             => 'Saada sellele kasutajale e-kiri',
'tooltip-t-upload'                => 'Lae üles faile',
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
'tooltip-rollback'                => '"Tühista" tühistab ühe klikiga viimase kaastöölise poolt tehtud muudatuse(d)',
'tooltip-undo'                    => '"Eemalda" tühistab selle muudatuse ja avab teksti eelvaatega redigeerimisakna. 
Samuti võimaldab see resümee reale põhjenduse lisamist.',

# Attribution
'anonymous' => '{{SITENAME}} {{PLURAL:$1|anonüümne kasutaja|anonüümsed kasutajad}}',
'siteuser'  => 'Viki kasutaja $1',
'others'    => 'teised',
'siteusers' => 'Portaali {{SITENAME}} {{PLURAL:$2|kasutaja|kasutajad}} $1',

# Skin names
'skinname-standard'    => 'Standard',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Kölni sinine',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Mu oma nahk',

# Math options
'mw_math_png'    => 'Alati PNG',
'mw_math_simple' => 'Kui väga lihtne, siis HTML, muidu PNG',
'mw_math_html'   => 'Võimaluse korral HTML, muidu PNG',
'mw_math_source' => 'Säilitada TeX (tekstibrauserite puhul)',
'mw_math_modern' => 'Soovitatav moodsate brauserite puhul',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff' => 'Märgi kui kontrollitud',
'markaspatrolledtext' => 'Märgi see leht kontrollituks',
'markedaspatrolled'   => 'Kontrollituks märgitud',

# Image deletion
'deletedrevision' => 'Kustutatud vanem variant $1',

# Browsing diffs
'previousdiff' => '← Eelmised erinevused',
'nextdiff'     => 'Järgmised erinevused →',

# Media information
'mediawarning'         => "'''Hoiatus''': See fail võib sisaldada pahatahtlikku koodi, mille käivitamime võib kahjustada teie arvutisüsteemi.<hr />",
'imagemaxsize'         => "Maksimaalne pildi suurus:<br />''kirjelduslehekülgedel''",
'thumbsize'            => 'Pisipildi suurus:',
'file-info-size'       => '($1 × $2 pikslit, faili suurus: $3, MIME tüüp: $4)',
'file-nohires'         => '<small>Sellest suuremat pilti pole.</small>',
'svg-long-desc'        => '(SVG fail, algsuurus $1 × $2 pikslit, faili suurus: $3)',
'show-big-image'       => 'Originaalsuurus',
'show-big-image-thumb' => '<small>Selle eelvaate suurus on: $1 × $2 pikselit</small>',

# Special:NewFiles
'newimages'             => 'Uute meediafailide galerii',
'imagelisttext'         => "
Järgnevas loendis, mis on sorteeritud $2, on '''$1''' {{PLURAL:$1|fail|faili}}.",
'showhidebots'          => '($1 robotite kaastööd)',
'ilsubmit'              => 'Otsi',
'bydate'                => 'kuupäeva järgi',
'sp-newimages-showfrom' => 'Näita uusi faile alates $2 $1',

# Bad image list
'bad_image_list' => 'Arvesse võetakse ainult nimekirja ühikud (read, mis algavad sümboliga *).
Esimene link real peab olema link kõlbmatule failile.
Samal real olevaid järgmiseid linke vaadeldakse kui erandeid, see tähendab artikleid, mille koosseisu kujutise võib lülitada.',

# Metadata
'metadata'          => 'Metaandmed',
'metadata-help'     => 'See fail sisaldab lisateavet, mis on tõenäoliselt lisatud digitaalkaamera või skänneri poolt.
Kui faili on muudetud mõne tarkvara programmiga, siis võivad osad andmed olla muutunud või täielikult eemaldatud.',
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
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'               => 'Laius',
'exif-imagelength'              => 'Kõrgus',
'exif-make'                     => 'Kaamera tootja',
'exif-model'                    => 'Kaamera mudel',
'exif-software'                 => 'Kasutatud tarkvara',
'exif-artist'                   => 'Autor',
'exif-copyright'                => 'Autoriõiguste omanik',
'exif-exifversion'              => 'Exif versioon',
'exif-makernote'                => 'Tootja märkmed',
'exif-usercomment'              => 'Kasutaja kommentaarid',
'exif-exposuretime'             => 'Säriaeg',
'exif-aperturevalue'            => 'Avaarv',
'exif-brightnessvalue'          => 'Heledus',
'exif-flash'                    => 'Välk',
'exif-focallength'              => 'Fookuskaugus',
'exif-whitebalance'             => 'Valge tasakaal',
'exif-contrast'                 => 'Kontrastsus',
'exif-saturation'               => 'Küllastus',
'exif-sharpness'                => 'Teravus',
'exif-devicesettingdescription' => 'Seadme seadistuste kirjeldus',
'exif-gpslatitude'              => 'Laius',
'exif-gpslongitude'             => 'Laiuskraad',
'exif-gpsaltituderef'           => 'Viide kõrgusele merepinnast',
'exif-gpsaltitude'              => 'Kõrgus merepinnast',
'exif-gpstimestamp'             => 'GPS aeg (aatomikell)',

'exif-subjectdistance-value' => '$1 meetrit',

'exif-lightsource-10' => 'Pilvine ilm',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilomeetrit tunnis',
'exif-gpsspeed-m' => 'Miili tunnis',
'exif-gpsspeed-n' => 'Sõlme',

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
'confirmemail'            => 'Kinnita e-posti aadress',
'confirmemail_text'       => 'Enne kui saad e-postiga seotud teenuseid kasutada, pead sa oma e-posti aadressi õigsust kinnitama. Allpool olevale nupule klikkides meilitakse sulle kinnituskood, koodi kinnitamiseks kliki meilis oleval lingil.',
'confirmemail_send'       => 'Meili kinnituskood',
'confirmemail_sent'       => 'Kinnitusmeil saadetud.',
'confirmemail_sendfailed' => 'Kinnitusmeili ei õnnestunud saata. 
Kontrolli aadressi õigsust.

Veateade meili saatmisel: $1',
'confirmemail_invalid'    => 'Vigane kinnituskood, kinnituskood võib olla aegunud.',
'confirmemail_needlogin'  => 'Oma e-posti aadressi kinnitamiseks pead sa $1.',
'confirmemail_success'    => 'Sinu e-posti aadress on nüüd kinnitatud. Sa võid sisse logida ning viki imelisest maailma nautida.',
'confirmemail_loggedin'   => 'Sinu e-posti aadress on nüüd kinnitatud.',
'confirmemail_error'      => 'Viga kinnituskoodi salvestamisel.',
'confirmemail_subject'    => '{{SITENAME}}: e-posti aadressi kinnitamine',
'confirmemail_body'       => 'Keegi, ilmselt sa ise, registreeris IP aadressilt $1 saidil {{SITENAME}} kasutajakonto "$2".

Kinnitamaks, et see kasutajakonto tõepoolest kuulub sulle ning aktiveerimaks e-posti teenuseid, ava oma brauseris järgnev link:

$3

Kui see *ei* ole sinu loodud konto, siis ava järgnev link $5 kinnituse tühistamiseks. 

Kinnituskood aegub $4.',

# Scary transclusion
'scarytranscludetoolong' => '[URL on liiga pikk]',

# Delete conflict
'deletedwhileediting' => 'Hoiatus: Sel ajal, kui sina artiklit redigeerisid, kustutas keegi selle ära!',

# Multipage image navigation
'imgmultipageprev' => '← eelmine lehekülg',
'imgmultipagenext' => 'järgmine lehekülg →',

# Table pager
'table_pager_prev'  => 'Eelmine lehekülg',
'table_pager_first' => 'Esimene lehekülg',
'table_pager_last'  => 'Viimane lehekülg',

# Auto-summaries
'autosumm-blank'   => 'Kustutatud kogu lehekülje sisu',
'autosumm-replace' => "Lehekülg asendatud tekstiga '$1'",
'autoredircomment' => 'Ümbersuunamine lehele [[$1]]',
'autosumm-new'     => "Uus lehekülg: '$1'",

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
'watchlistedit-raw-submit'     => 'Uuenda jälgimisloendit',
'watchlistedit-raw-done'       => 'Teie jälgimisloend on uuendatud.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 lehekülg|$1 lehekülge}} lisatud:',

# Watchlist editing tools
'watchlisttools-view' => 'Näita vastavaid muudatusi',
'watchlisttools-edit' => 'Vaata ja redigeeri jälgimisloendit',
'watchlisttools-raw'  => 'Muuda lähteteksti',

# Special:Version
'version'                  => 'Versioon', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Erileheküljed',
'version-parserhooks'      => 'Süntaksianalüsaatori lisad (Parser hooks)',
'version-software'         => 'Installeeritud tarkvara',
'version-software-product' => 'Toode',
'version-software-version' => 'Versioon',

# Special:FilePath
'filepath'      => 'Failitee',
'filepath-page' => 'Fail:',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Otsi faili duplikaate',
'fileduplicatesearch-legend'   => 'Otsi faili duplikaati',
'fileduplicatesearch-filename' => 'Faili nimi:',
'fileduplicatesearch-submit'   => 'Otsi',

# Special:SpecialPages
'specialpages'                   => 'Erileheküljed',
'specialpages-group-maintenance' => 'Hooldusraportid',
'specialpages-group-other'       => 'Teised erileheküljed',
'specialpages-group-login'       => 'Sisselogimine / registreerumine',
'specialpages-group-changes'     => 'Viimased muudatused ja logid',
'specialpages-group-media'       => 'Failidega seonduv',
'specialpages-group-users'       => 'Kasutajad ja õigused',
'specialpages-group-highuse'     => 'Tihti kasutatud leheküljed',
'specialpages-group-pagetools'   => 'Töö lehekülgedega',
'specialpages-group-wiki'        => 'Viki andmed ja tööriistad',
'specialpages-group-redirects'   => 'Ümbersuunavad erilehed',
'specialpages-group-spam'        => 'Töö spämmiga',

# Special:BlankPage
'blankpage' => 'Tühi leht',

# Special:Tags
'tags-hitcount' => '$1 {{PLURAL:$1|muudatus|muudatust}}',

);
