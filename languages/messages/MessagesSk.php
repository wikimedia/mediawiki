<?php
/**
 * Slovak (Slovenčina)
 *
 * @addtogroup Language
 *
 * Translators: Valasek, helix84, Palica, Liso, Maros
 */


$quickbarSettings = array(
	'Žiadne', 'Ukotvené vľavo', 'Ukotvené vpravo', 'Plávajúce vľavo'
);

$datePreferences = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short dmyt',
	'ISO 8601',
);

$datePreferenceMigrationMap = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
);

$dateFormats = array(
	/*
	'Default',
	'15. január 2001 16:12',
	'15. jan. 2001 16:12',
	'16:12, 15. január 2001',
	'16:12, 15. jan. 2001',
	'ISO 8601' => '2001-01-15 16:12:34'*/

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'H:i, j. F Y',

	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. F Y H:i',

	'short dmyt time' => 'H:i',
	'short dmyt date' => 'j. M. Y',
	'short dmyt both' => 'j. M. Y H:i',

	'tdmy time' => 'H:i',
	'tdmy date' => 'j. F Y',
	'tdmy both' => 'H:i, j. F Y',

	'short tdmy time' => 'H:i',
	'short tdmy date' => 'j. M. Y',
	'short tdmy both' => 'H:i, j. M. Y',
	
);

$bookstoreList = array(
	'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1',
	'BokBerit' => 'http://www.bokberit.no/annet_sted/bocker/$1.html',
	'Bokkilden' => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
	'Haugenbok' => 'http://www.haugenbok.no/searchresults.cfm?searchtype=simple&isbn=$1',
	'Akademika' => 'http://www.akademika.no/sok.php?isbn=$1',
	'Gnist' => 'http://www.gnist.no/sok.php?isbn=$1',
	'Amazon.co.uk' => 'http://www.amazon.co.uk/exec/obidos/ISBN=$1',
	'Amazon.de' => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

# Note to translators:
# Please include the English words as synonyms. This allows people
# from other wikis to contribute more easily.
#
$magicWords = array(
	# ID CASE SYNONYMS
	'redirect'   => array( 0, '#redirect', '#presmeruj' ),
	'notoc'   => array( 0, '__NOTOC__', '__BEZOBSAHU__' ),
	'forcetoc'   => array( 0, '__FORCETOC__', '__VYNÚŤOBSAH__' ),
	'toc'   => array( 0, '__TOC__', '__OBSAH__' ),
	'noeditsection'   => array( 0, '__NOEDITSECTION__', '__NEUPRAVUJSEKCIE__' ),
	'start'   => array( 0, '__START__', '__ŠTART__' ),
	'currentmonth'   => array( 1, 'CURRENTMONTH', 'MESIAC' ),
	'currentmonthname'   => array( 1, 'CURRENTMONTHNAME', 'MENOMESIACA' ),
	'currentmonthnamegen'   => array( 1, 'CURRENTMONTHNAMEGEN', 'MENOAKTUÁLNEHOMESIACAGEN' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'MENOAKTUÁLNEHOMESIACASKRATKA' ),
	'currentday'   => array( 1, 'CURRENTDAY', 'AKTUÁLNYDEŇ' ),
	'currentdayname'   => array( 1, 'CURRENTDAYNAME', 'MENOAKTUÁLNEHODŇA' ),
	'currentyear'   => array( 1, 'CURRENTYEAR', 'AKTUÁLNYROK' ),
	'currenttime'   => array( 1, 'CURRENTTIME', 'AKTUÁLNYČAS' ),
	'numberofarticles'   => array( 1, 'NUMBEROFARTICLES', 'POČETČLÁNKOV' ),
	'pagename'   => array( 1, 'PAGENAME', 'MENOSTRÁNKY' ),
	'pagenamee'   => array( 1, 'PAGENAMEE' ),
	'namespace'   => array( 1, 'NAMESPACE', 'MENNÝPRIESTOR' ),
	'msg'   => array( 0, 'MSG:', 'SPRÁVA:' ),
	'subst'   => array( 0, 'SUBST:' ),
	'msgnw'   => array( 0, 'MSGNW:' ),
	'img_thumbnail'   => array( 1, 'thumbnail', 'thumb', 'náhľad', 'náhľadobrázka' ),
	'img_right'   => array( 1, 'right', 'vpravo' ),
	'img_left'   => array( 1, 'left', 'vľavo' ),
	'img_none'   => array( 1, 'none', 'žiadny' ),
	'img_width'   => array( 1, '$1px', '$1bod' ),
	'img_center'   => array( 1, 'center', 'centre', 'stred' ),
	'img_framed'   => array( 1, 'framed', 'enframed', 'frame', 'rám' ),
	'int'   => array( 0, 'INT:' ),
	'sitename'   => array( 1, 'SITENAME', 'MENOLOKALITY' ),
	'ns'   => array( 0, 'NS:', 'MP:' ),
	'localurl'   => array( 0, 'LOCALURL:' ),
	'localurle'   => array( 0, 'LOCALURLE:' ),
	'server'   => array( 0, 'SERVER' ),
	'grammar'   => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	'notitleconvert'   => array( 0, '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'   => array( 0, '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'   => array( 1, 'CURRENTWEEK', 'AKTUÁLNYTÝŽDEŇ' ),
	'currentdow'   => array( 1, 'CURRENTDOW' ),
	'revisionid'   => array( 1, 'REVISIONID' ),
);

$namespaceNames = array(
	NS_MEDIA          => 'Médiá',
	NS_SPECIAL        => 'Špeciálne',
	NS_MAIN           => '',
	NS_TALK           => 'Diskusia',
	NS_USER           => 'Redaktor',
	NS_USER_TALK      => 'Diskusia_s_redaktorom',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Diskusia_k_{{grammar:datív|$1}}',
	NS_IMAGE          => 'Obrázok',
	NS_IMAGE_TALK     => 'Diskusia_k_obrázku',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Diskusia_k_MediaWiki',
	NS_TEMPLATE       => 'Šablóna',
	NS_TEMPLATE_TALK  => 'Diskusia_k_šablóne',
	NS_HELP           => 'Pomoc',
	NS_HELP_TALK      => 'Diskusia_k_pomoci',
	NS_CATEGORY       => 'Kategória',
	NS_CATEGORY_TALK  => 'Diskusia_ku_kategórii'
);

# Compatbility with old names
$namespaceAliases = array(
	"Komentár"               => NS_TALK,
	"Komentár_k_redaktorovi" => NS_USER_TALK,
	"Komentár_k_Wikipédii"   => NS_PROJECT_TALK,
	"Komentár_k_obrázku"     => NS_IMAGE_TALK,
	"Komentár_k_MediaWiki"   => NS_MEDIAWIKI_TALK,
);

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ','
);

$linkTrail = '/^([a-záäčďéíľĺňóôŕšťúýž]+)(.*)$/sDu';


$messages = array(
# User preference toggles
'tog-underline'               => 'Podčiarkuj odkazy',
'tog-highlightbroken'         => 'Neexistujúce odkazy zobrazuj červenou',
'tog-justify'                 => 'Zarovnávaj odstavce',
'tog-hideminor'               => 'V posledných úpravách neukazuj drobné úpravy',
'tog-extendwatchlist'         => 'Rozšír zoznam sledovaných, aby ukazoval všetky súvisiace zmeny',
'tog-usenewrc'                => 'Špeciálne zobrazenie posledných úprav (vyžaduje JavaScript)',
'tog-numberheadings'          => 'Automaticky čísluj odstavce',
'tog-showtoolbar'             => 'Zobrazuj upravovací panel nástrojov',
'tog-editondblclick'          => 'Upravuj stránky po dvojitom kliknutí (JavaScript)',
'tog-editsection'             => 'Umožni upravovať sekcie cez odkazy [úprava]',
'tog-editsectiononrightclick' => 'Umožni upravovať sekcie po kliknutí pravým tlačidlom na nadpisy sekcií (JavaScript)',
'tog-showtoc'                 => 'Zobraz obsah (pre stránky s viac ako 3 nadpismi)',
'tog-rememberpassword'        => 'Pamätaj si heslo aj nabudúce',
'tog-editwidth'               => 'Maximálna šírka okna na úpravy',
'tog-watchcreations'          => 'Pridaj stránky, ktoré vytvorím do môjho zoznamu sledovaných stránok',
'tog-watchdefault'            => 'Upozorňuj na nové a novo upravené stránky',
'tog-minordefault'            => 'Označ všetky zmeny štandardne ako drobné',
'tog-previewontop'            => 'Zobrazuj ukážku pred oknom na úpravy, a nie až za ním',
'tog-previewonfirst'          => 'Zobraz náhľad pri prvom upravovaní',
'tog-nocache'                 => 'Vypni ukladanie stránok do vyrovnávacej pamäte',
'tog-enotifwatchlistpages'    => 'Pošli mi email keď sa stránka zmení',
'tog-enotifusertalkpages'     => 'Pošli mi email po zmene mojej redaktorskej diskusnej stránky',
'tog-enotifminoredits'        => 'Pošli mi email aj o drobných úpravách stránok',
'tog-enotifrevealaddr'        => 'Zobraz moju emailovú adresu v notifikačných emailoch',
'tog-shownumberswatching'     => 'Zobraz počet sledujúcich používateľov',
'tog-fancysig'                => 'Nespracovávať podpisy (bez automatických odkazov)',
'tog-externaleditor'          => 'Používaj štandardne externý editor',
'tog-externaldiff'            => 'Používaj štandardne externý diff',
'tog-showjumplinks'           => 'Používaj odkazy „skočiť na“ pre lepšiu dostupnosť',
'tog-uselivepreview'          => 'Použitie živého náhľadu (JavaScript) (experimentálna funkcia)',
'tog-forceeditsummary'        => 'Upozorni ma, keď neuvádzam zhrnutie úprav',
'tog-watchlisthideown'        => 'Skry moje úpravy zo zoznamu sledovaných',
'tog-watchlisthidebots'       => 'Skry úpravy botov zo zoznamu sledovaných',
'tog-nolangconversion'        => 'Vypni konverziu variantov',
'tog-ccmeonemails'            => 'Pošli mi kópie mojich emailov, ktoré pošlem ostatným používateľom',

'underline-always'  => 'Vždy',
'underline-never'   => 'Nikdy',
'underline-default' => 'Štandardné nastavenie prehliadača',

'skinpreview' => '(Náhľad)',

# Dates
'sunday'        => 'nedeľa',
'monday'        => 'pondelok',
'tuesday'       => 'utorok',
'wednesday'     => 'streda',
'thursday'      => 'štvrtok',
'friday'        => 'piatok',
'saturday'      => 'sobota',
'sun'           => 'Ned',
'mon'           => 'Pon',
'tue'           => 'Uto',
'wed'           => 'Str',
'thu'           => 'Štv',
'fri'           => 'Pia',
'sat'           => 'Sob',
'january'       => 'január',
'february'      => 'február',
'march'         => 'marec',
'april'         => 'apríl',
'may_long'      => 'máj',
'june'          => 'jún',
'july'          => 'júl',
'august'        => 'august',
'september'     => 'september',
'october'       => 'október',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'januára',
'february-gen'  => 'februára',
'march-gen'     => 'marca',
'april-gen'     => 'apríla',
'may-gen'       => 'mája',
'june-gen'      => 'júna',
'july-gen'      => 'júla',
'august-gen'    => 'augusta',
'september-gen' => 'septembra',
'october-gen'   => 'októbra',
'november-gen'  => 'novembra',
'december-gen'  => 'decembra',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'máj',
'jun'           => 'jún',
'jul'           => 'júl',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategória|Kategórie|Kategórie}}',
'pagecategories'        => '{{PLURAL:$1|Kategória|Kategórie|Kategórie}}',
'category_header'       => 'stránky v kategórii „$1“',
'subcategories'         => 'Podkategórie',
'category-media-header' => 'Multimediálne súbory v kategórii "$1"',

'mainpage'          => 'Hlavná stránka',
'mainpagetext'      => 'Wiki softvér bol úspešne nainštalovaný.',
'mainpagedocfooter' => "Pre pomoc a konfiguračné nastavenia prosím pozrite [http://meta.wikimedia.org/wiki/MediaWiki_i18n documentation on customizing the interface] a [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User's Guide].",

'portal'          => 'Portál komunity',
'portal-url'      => 'Project:Portál komunity',
'about'           => 'Projekt',
'aboutsite'       => 'O {{GRAMMAR:lokál|{{SITENAME}}}}',
'aboutpage'       => 'Project:Úvod',
'article'         => 'Stránka s obsahom',
'help'            => 'Pomoc',
'helppage'        => 'Pomoc:Obsah',
'bugreports'      => 'Oznámenia o chybách',
'bugreportspage'  => 'Project:Oznámenia o chybách',
'sitesupport'     => 'Donácie',
'sitesupport-url' => 'Project:Dotácie',
'faq'             => 'FAQ',
'faqpage'         => 'Project:FAQ',
'edithelp'        => 'Ako upravovať stránku',
'newwindow'       => '(otvorí v novom okne)',
'edithelppage'    => 'Pomoc:Ako upravovať stránku',
'cancel'          => 'Zrušiť',
'qbfind'          => 'Nájdi',
'qbbrowse'        => 'Listuj',
'qbedit'          => 'Upravuj',
'qbpageoptions'   => 'Možnosti stránky',
'qbpageinfo'      => 'Informácie o stránke',
'qbmyoptions'     => 'Moje nastavenia',
'qbspecialpages'  => 'Špeciálne stránky',
'moredotdotdot'   => 'Viac...',
'mypage'          => 'Moja stránka',
'mytalk'          => 'Moja diskusia',
'anontalk'        => 'Diskusia k tejto IP adrese',
'navigation'      => 'Navigácia',

# Metadata in edit box
'metadata_help' => 'Metadáta (vysvetlenie pozri na [[Project:Metadata]]):',

'currentevents'     => 'Aktuality',
'currentevents-url' => 'Aktuality',

'disclaimers'       => 'Vylúčenie zodpovednosti',
'disclaimerpage'    => 'Project:Vylúčenie zodpovednosti',
'privacy'           => 'Ochrana osobných údajov',
'privacypage'       => 'Project:Ochrana osobných údajov',
'errorpagetitle'    => 'Chyba',
'returnto'          => 'Späť na $1.',
'tagline'           => 'Z {{GRAMMAR:genitív|{{SITENAME}}}}',
'search'            => 'Hľadaj',
'searchbutton'      => 'Hľadaj',
'go'                => 'Choď',
'searcharticle'     => 'Choď',
'history'           => 'história stránky',
'history_short'     => 'História',
'updatedmarker'     => 'aktualizované od mojej poslednej návštevy',
'info_short'        => 'Informácie',
'printableversion'  => 'Verzia na tlač',
'permalink'         => 'Trvalý odkaz',
'print'             => 'Tlač',
'edit'              => 'úprava',
'editthispage'      => 'Upravuj túto stránku',
'delete'            => 'Vymaž',
'deletethispage'    => 'Vymaž túto stránku',
'undelete_short'    => 'Obnov $1 úprav',
'protect'           => 'Zamkni',
'protectthispage'   => 'Zamkni túto stránku',
'unprotect'         => 'Odomkni',
'unprotectthispage' => 'Odomkni túto stránku',
'newpage'           => 'Nová stránka',
'talkpage'          => 'Diskusia k stránke',
'specialpage'       => 'Špeciálna stránka',
'personaltools'     => 'Osobné nástroje',
'postcomment'       => 'Pridaj komentár',
'articlepage'       => 'Zobraz stránku',
'talk'              => 'Diskusia',
'views'             => 'Zobrazení',
'toolbox'           => 'Nástroje',
'userpage'          => 'Zobraz stránku redaktora',
'projectpage'       => 'Zobraz projektovú stránku',
'imagepage'         => 'Zobraz popisnú stránku obrázka',
'mediawikipage'     => 'Zobraz stránku so správou',
'templatepage'      => 'Zobraziť stránku šablóny',
'viewhelppage'      => 'Zobraziť stránku Pomocníka',
'categorypage'      => 'Zobraz stránku kategórie',
'viewtalkpage'      => 'Zobraz diskusiu k stránke',
'otherlanguages'    => 'Iné jazyky',
'redirectedfrom'    => '(Presmerované z $1)',
'redirectpagesub'   => 'Presmerovacia stránka',
'lastmodifiedat'    => 'Čas poslednej úpravy tejto stránky je $2, $1.', # $1 date, $2 time
'viewcount'         => 'Táto stránka bola navštívená $1-krát.',
'copyright'         => 'Obsah je dostupný pod $1.',
'protectedpage'     => 'Zamknutá stránka',
'jumpto'            => 'Skoč na:',
'jumptonavigation'  => 'navigácia',
'jumptosearch'      => 'hľadanie',

'badaccess'        => 'Chyba povolenia',
'badaccess-group0' => 'Nemáte povolenie na vykonanie požadovanej akcie.',
'badaccess-group1' => 'Akciu, ktorú požadujete môže vykonať iba člen skupiny $1.',
'badaccess-group2' => 'Akciu, ktorú požadujete môže vykonať iba člen jednej zo skupín $1.',
'badaccess-groups' => 'Akciu, ktorú požadujete môže vykonať iba člen jednej zo skupín $1.',

'versionrequired'     => 'Požadovaná verzia MediaWiki $1',
'versionrequiredtext' => 'Na použitie tejto stránky je požadovaná verzia MediaWiki $1. Pozri [[Special:Version]]',

'ok'                  => 'OK',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => 'Zdroj: "$1"',
'youhavenewmessages'  => 'Máte $1 ($2).',
'newmessageslink'     => 'nové správy',
'newmessagesdifflink' => 'diff s predposlednou revíziou',
'editsection'         => 'úprava',
'editold'             => 'upraviť',
'editsectionhint'     => 'Upravuj sekciu: $1',
'toc'                 => 'Obsah',
'showtoc'             => 'zobraz',
'hidetoc'             => 'schovaj',
'thisisdeleted'       => 'Zobraziť alebo obnoviť $1?',
'viewdeleted'         => 'Zobraziť $1?',
'restorelink'         => '{{PLURAL:$1|jedna zmazaná úprava|$1 zmazané úpravy|$1 zmazaných úprav}}',
'feedlinks'           => 'Kanál:',
'feed-invalid'        => 'Neplatný typ feedu.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Stránka',
'nstab-user'      => 'Stránka redaktora',
'nstab-media'     => 'Médiá',
'nstab-special'   => 'Špeciálne',
'nstab-project'   => 'Projektová stránka',
'nstab-image'     => 'Súbor',
'nstab-mediawiki' => 'Správa',
'nstab-template'  => 'Šablóna',
'nstab-help'      => 'Pomoc',
'nstab-category'  => 'Kategória',

# Main script and global functions
'nosuchaction'      => 'Takáto akcia neexistuje',
'nosuchactiontext'  => 'Softvér MediaWiki nepozná akciu,
ktorú vyžadujete pomocou URL.',
'nosuchspecialpage' => 'Takáto špeciálna stránka neexistuje',
'nospecialpagetext' => 'Softvér MediaWiki nepozná takúto špeciálnu stránku, zoznam špeciálnych stránok nájdete na [[Special:Specialpages]].',

# General errors
'error'                => 'Chyba',
'databaseerror'        => 'Chyba v databáze',
'dberrortext'          => 'Nastala syntaktická chyba v príkaze na prehľadávanie databázy.
Posledný pokus o prehľadávanie bol:
<blockquote><tt>$1</tt></blockquote>
z funkcie "<tt>$2</tt>".
MySQL vrátil chybu "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Nastala syntaktická chyba pri dotaze do databázy.
Posledný pokus o dotaz do databázy znel:
"$1"
z funkcie "$2".
MySQL vrátil chybu "$3: $4".',
'noconnect'            => 'Prepáčte! Wiki má technické problémy a nemôže kontaktovať databázový server. <br />
$1',
'nodb'                 => 'Neviem vybrať databázu $1',
'cachederror'          => 'Nasledujúca stránka je odložená kópia vyžiadanej stránky a nemusí byť aktuálna.',
'laggedslavemode'      => 'Varovanie: Je možné, že stránka neobsahuje posledné aktualizácie.',
'readonly'             => 'Databáza je zamknutá',
'enterlockreason'      => 'Zadajte dôvod požadovaného zamknutia vrátane odhadu, kedy očakávate odomknutie',
'readonlytext'         => 'Databáza je momentálne zamknutá, nové stránky a úpravy sú zablokované, pravdepodobne z dôvodu údržby databázy. Po skončení tejto údržby bude {{SITENAME}} opäť fungovať normálne.

Správca, ktorý nariadil uzamknutie, uvádza tento dôvod: $1',
'missingarticle'       => 'Databáza nenašla text stránky, ktorý by mala nájsť, menovite "$1".

Toto je zvyčajne zapríčinené odkazovaním na staršie verzie alebo odkazom na stránku, ktorý bol zmazaný.

Ak to nie je ten prípad, možno ste našli chybu s softvéri. Prosím ohláste túto chybu správcovi, uveďte aj názov stránky - odkaz (URL).',
'readonly_lag'         => 'The databáza bola automaticky zamknutá pokým záložné databázové servery nedoženú hlavný server',
'internalerror'        => 'Vnútorná chyba',
'filecopyerror'        => 'Neviem skopírovať súbor "$1" na "$2".',
'filerenameerror'      => 'Neviem premenovať súbor "$1" na "$2".',
'filedeleteerror'      => 'Neviem vymazať súbor "$1".',
'filenotfound'         => 'Neviem nájsť súbor "$1".',
'unexpected'           => 'Nečakaná hodnota: "$1"="$2".',
'formerror'            => 'Chyba: neviem spracovať formulár',
'badarticleerror'      => 'Na tejto stránke túto akciu nemožno vykonať.',
'cannotdelete'         => 'Neviem zmazať danú stránku alebo súbor. (Možno už bol zmazaný niekým iným.)',
'badtitle'             => 'Neplatný nadpis',
'badtitletext'         => 'Požadovaný nadpis bol neplatný, nezadaný, alebo nesprávne odkazovaný z inej jazykovej verzie {{GRAMMAR:genitív|{{SITENAME}}}}. Mohol tiež obsahovať jeden alebo viac znakov, ktoré nie je možné použiť v nadpisoch.',
'perfdisabled'         => 'Prepáčte! Táto funkcia bola dočasne vypnutá,
pretože tak spomaľuje databázu, že nikto nemôže používať
wiki.',
'perfdisabledsub'      => 'Tu je uložená kópia z $1:', # obsolete?
'perfcached'           => '<span style="color:#ff0000"><strong>Nasledujúce dáta sú z dočasnej pamäte a nemusia byť úplne aktuálne:</strong></span>',
'perfcachedts'         => 'Nasledujúce údaje pochádzajú z cache a naposledy boli aktualizované $1.',
'wrong_wfQuery_params' => 'Nesprávny parameter v wfQuery()<br />
Funkcia: $1<br />
Dotaz: $2',
'viewsource'           => 'Zobraz zdroj',
'viewsourcefor'        => '$1',
'protectedinterface'   => 'Táto stránka poskytuje text používateľského rozhrania a aby sa predišlo zneužitiam, upravovať ju môžu iba [[Project:Správcovia|správcovia]].',
'editinginterface'     => "'''Varovanie:''' Upravujete stránku, ktorá poskytuje text používateľského rozhrania. Zmeny tejto stránky ovplyvnia vzhľad používateľského rozhrania ostatných používateľov.",
'sqlhidden'            => '(SQL príkaz na prehľadávanie je skrytý)',

# Login and logout pages
'logouttitle'                => 'Odhlásiť redaktora',
'logouttext'                 => 'Práve ste sa odhlásili.
Odteraz môžete používať {{GRAMMAR:akuzatív|{{SITENAME}}}} ako anonymný redaktor alebo sa môžete
opäť prihlásiť pod rovnakým alebo odlišným redaktorským menom. Uvedomte si, že niektoré stránky sa môžu
naďalej zobrazovať ako keby ste boli prihlásený, až kým nevymažete
vyrovnávaciu pamäť vášho prehliadača.',
'welcomecreation'            => '== Vitaj, $1! ==

Vaše konto je vytvorené. Nezabudnite si nastaviť vaše redaktorské nastavenia.',
'loginpagetitle'             => 'Prihlásenie redaktora',
'yourname'                   => 'Vaše redaktorské meno',
'yourpassword'               => 'Vaše heslo',
'yourpasswordagain'          => 'Zopakujte heslo',
'remembermypassword'         => 'Pamätať si heslo aj po vypnutí počítača.',
'yourdomainname'             => 'Vaša doména',
'externaldberror'            => 'Buď nastala chyba externej autentifikačnej databázy alebo Vám nie je povolené aktualizovať Váš externý účet.',
'loginproblem'               => '<b>Nastal problém pri vašom prihlasovaní.</b><br />Skúste znova!',
'alreadyloggedin'            => "'''Užívateľ $1, vy už ste prihlásený!'''<br />",
'login'                      => 'Prihlásenie',
'loginprompt'                => 'Na prihlásenie do {{GRAMMAR:genitív|{{SITENAME}}}} musíte mať zapnuté koláčiky (cookies).',
'userlogin'                  => 'Vytvorte si konto alebo sa prihláste',
'logout'                     => 'Odhlásenie',
'userlogout'                 => 'Odhlásenie',
'notloggedin'                => 'Neprihlásený/á',
'nologin'                    => 'Nemáte ešte účet? $1.',
'nologinlink'                => 'Vytvoriť nový účet',
'createaccount'              => 'Vytvoriť nový účet',
'gotaccount'                 => 'Máte už vytvorený účet? $1.',
'gotaccountlink'             => 'Prihlásenie',
'createaccountmail'          => 'e-mailom',
'badretype'                  => 'Zadané heslá nie sú rovnaké.',
'userexists'                 => 'Zadané redaktorské meno už používa niekto iný. Zadajte iné meno.',
'youremail'                  => 'Váš e-mail²',
'username'                   => 'Používateľské meno:',
'uid'                        => 'ID užívateľa:',
'yourrealname'               => 'Skutočné meno *:',
'yourlanguage'               => 'Jazyk:',
'yourvariant'                => 'Variant',
'yournick'                   => 'Prezývka:',
'badsig'                     => 'Neplatný podpis v pôvodnom tvare; skontrolujte HTML tagy.',
'email'                      => 'E-mail',
'prefs-help-email-enotif'    => 'Táto adresa sa používa aj na posielanie e-mailových upozornení, ak ste túto možnosť povolili.',
'prefs-help-realname'        => '¹ Skutočné meno (nepovinné): ak sa rozhodnete ho poskytnúť, bude použité na označenie Vašej práce.',
'loginerror'                 => 'Chyba pri prihlasovaní',
'prefs-help-email'           => '² E-mail (nepovinné): Umožní iným ľuďom kontaktovať Vás z Vašej užívateľskej a diskusnej, bez potreby uverejňovania Vašej e-mailovej adresy a môže byť použité na poslanie nového hesla, ak zabudnete pôvodné.',
'nocookiesnew'               => 'Redaktorské konto bolo vytvorené, ale nie ste prihlásený. {{SITENAME}} používa koláčiky (cookies) na prihlásenie. Vy máte koláčiky (cookies) vypnuté. Zapnite ich a potom sa prihláste s vaším novým redaktorským menom a heslom.',
'nocookieslogin'             => '{{SITENAME}} používa koláčiky (cookies) na prihlásenie. Vy máte koláčiky vypnuté. Prosíme, zapnite ich a skúste znovu.',
'noname'                     => 'Nezadali ste platné redaktorské meno.',
'loginsuccesstitle'          => 'Prihlásenie úspešné',
'loginsuccess'               => 'Teraz ste prihlásený do {{GRAMMAR:genitív|{{SITENAME}}}} ako "$1".',
'nosuchuser'                 => 'Redaktorské meno "$1" neexistuje. Skontrolujte preklepy alebo sa prihláste ako nový redaktor pomocou dolu uvedeného formulára.',
'nosuchusershort'            => 'V súčasnosti neexistuje redaktor s menom "$1". Skontrolujte preklepy.',
'nouserspecified'            => 'Musíte uviesť meno používateľa.',
'wrongpassword'              => 'Zadané heslo je nesprávne. Skúste  znovu.',
'wrongpasswordempty'         => 'Zadané heslo bolo prázdne. Skúste prosím znova.',
'mailmypassword'             => 'Pošlite mi e-mailom dočasné heslo',
'passwordremindertitle'      => 'Oznámenie o hesle z {{GRAMMAR:genitív|{{SITENAME}}}}',
'passwordremindertext'       => 'Niekto (pravdepodobne vy, z IP adresy $1)
požiadal, aby sme vám zaslali nové prihlasovacie heslo do {{GRAMMAR:genitív|{{SITENAME}}}} ($4).
Heslo pre redaktora "$2" je teraz "$3".
Teraz by ste sa mali prihlásiť a zmeniť vaše heslo.

Ak túto požiadavku poslal niekto iný alebo ste si spomenuli svoje heslo a neželáte
si ho zmeniť, môžete túto správu ignorovať a naďalej používať svoje staré heslo.',
'noemail'                    => 'Redaktor "$1" nezadal e-mailovú adresu.',
'passwordsent'               => 'Nové heslo bolo zaslané na e-mailovú adresu
redaktora "$1".
Prosím, prihláste sa znovu, keď ho obdržíte.',
'blocked-mailpassword'       => 'Boli zablokované úpravy z vašej IP adresy, a tak nie je dovolené použiť funkciu znovuvyžiadania hesla, aby sa zabránilo zneužitiu.',
'eauthentsent'               => 'Email s potvrdením bol zaslaný na uvedenú emailovú adresu.
Predtým ako sa na účet pošle akákoľvek ďalšia pošta, musíte splniť inštrukcie v emaili, aby sa potvrdilo, že účet je skutočne Váš.',
'throttled-mailpassword'     => 'V priebehu posledných $1 hodín už došlo k vyžiadaniu hesla.
Aby sa zabránilo zneužitiu, vyžiadanie hesla je možné vykonať iba raz za $1 hodín.',
'mailerror'                  => 'Chyba pri posielaní e-mailu: $1',
'acct_creation_throttle_hit' => 'Prepáčte, už máte vytvorených $1 účtov. Nemôžete ich z tejto IP adresy vytvoriť za 24 hodín viac. Toto je opatrenie proti vandalizmu.',
'emailauthenticated'         => 'Vaša e-mailová adresa bola overená na $1.',
'emailnotauthenticated'      => 'Vaša e-mailová adresa ešte nebola overená. Preto nemôžete prijať emaily pre žiadnu z nasledovných funkcií.',
'noemailprefs'               => '<strong>Nezadali ste žiadnu e-mailovú adresu</strong>, nasledujúce
nástroje nebudú prístupné.',
'emailconfirmlink'           => 'Potvrďte vašu e-mailovú adresu',
'invalidemailaddress'        => 'E-mailovú adresu nemožno akceptovať, pretože sa zdá, že má neplatný formát. Zadajte dobre naformátovanú adresu alebo nechajte príslušné políčko prázdne.',
'accountcreated'             => 'Účet vytvorený',
'accountcreatedtext'         => 'Používateľský účet pre $1 bol vytvorený.',

# Edit page toolbar
'bold_sample'     => 'Tučný text',
'bold_tip'        => 'Tučný text',
'italic_sample'   => 'Kurzíva',
'italic_tip'      => 'Kurzíva',
'link_sample'     => 'Názov odkazu',
'link_tip'        => 'Interný odkaz',
'extlink_sample'  => 'http://www.example.com názov odkazu',
'extlink_tip'     => 'Externý odkaz (nezabudnite prefix http://)',
'headline_sample' => 'Text nadpisu',
'headline_tip'    => 'Text nadpisu úrovne 2',
'math_sample'     => 'Sem vložte vzorec',
'math_tip'        => 'Matematický vzorec (LaTeX)',
'nowiki_sample'   => 'Sem vložte neformátovaný text',
'nowiki_tip'      => 'Ignoruj wiki formátovanie',
'image_sample'    => 'Príklad.jpg',
'image_tip'       => 'Vložený obrázok',
'media_sample'    => 'Príklad.ogg',
'media_tip'       => 'Odkaz na media súbor',
'sig_tip'         => 'Váš podpis s dátumom a časom',
'hr_tip'          => 'Horizontálna čiara (používajte zriedka)',

# Edit pages
'summary'                   => 'Zhrnutie úprav',
'subject'                   => 'Téma/nadpis',
'minoredit'                 => 'Toto je drobná úprava',
'watchthis'                 => 'Sleduj úpravy tejto stránky',
'savearticle'               => 'Ulož stránku',
'preview'                   => 'Náhľad',
'showpreview'               => 'Zobraz náhľad',
'showlivepreview'           => 'Živý náhľad',
'showdiff'                  => 'Zobraz rozdiely',
'anoneditwarning'           => 'Nie ste [[Special:Userlogin|prihlásený]]. Vaša [[IP adresa]] bude zaznamenaná v <span class="plainlinks"> [{{fullurl:{{FULLPAGENAME}}|action=history}} histórii úprav]</span> tejto stránky.',
'missingsummary'            => "'''Upozornenie:''' Neposkytli ste zhrnutie úprav. Ak kliknete znova na Uložiť, Vaše úpravy sa uložia bez zhrnutia úprav.",
'missingcommenttext'        => 'Prosím, dolu napíšte komentár.',
'missingcommentheader'      => "'''Pripomienka:''' Neposkutli ste predmet/hlavičku tohto komentára. Ak znova kliknete na tlačidlo Uložiť, vaša úprava sa uloží bez nej.",
'summary-preview'           => 'Náhľad zhrnutia',
'subject-preview'           => 'Náhľad predmetu/hlavičky',
'blockedtitle'              => 'Redaktor je zablokovaný',
'blockedtext'               => 'Vaše redaktorské meno alebo IP adresu zablokoval $1.
Udáva tento dôvod:<br />\'\'$2\'\'

Môžete kontaktovať $1 alebo s jedného z ďalších 
[[{{ns:project}}:Správcovia|správcov]] a prediskutovať blokovanie.

Uvedomte si, že nemôžete použiť funkciu "Pošli e-mail redaktorovi", pokiaľ nemáte registrovanú platnú e-mailovú adresu vo vašich [[Special:Preferences|nastaveniach]].

Vaša IP adresa je $3. Prosíme, zahrňte túto adresu do každého dotazu, ktorý posielate.',
'blockedoriginalsource'     => "Zdroj '''$1''' je zobrazený nižšie:",
'blockededitsource'         => "Text '''Vašich úprav''' stránky '''$1''' je zobrazený nižšie:",
'whitelistedittitle'        => 'Na úpravu je nutné prihlásenie',
'whitelistedittext'         => 'Na úpravu stránok sa musíte najskôr $1.',
'whitelistreadtitle'        => 'Je potrebné sa prihlásiť, aby ste mohli čítať',
'whitelistreadtext'         => 'Na čítanie stránok musíte byť [[Special:Userlogin|prihlásený/á]]',
'whitelistacctitle'         => 'Nemáte dovolené vytvorenie konta',
'whitelistacctext'          => 'Na umožnenie vytvorenia konta v tomto Wiki musíte byť [[Special:Userlogin|prihlásený/á]] a mať primerané práva.',
'confirmedittitle'          => 'Aby ste mohli upravovať je potrebné potvrdenie e-mailu',
'confirmedittext'           => 'Pred úpravami stránok musíte potvrdiť vašu emailovú adresu. Prosím, nastavte a overte svoju emailovú adresu v [[Special:Preferences|používateľských nastaveniach]].',
'loginreqtitle'             => 'Nutné prihlásenie',
'loginreqlink'              => 'prihlásiť',
'loginreqpagetext'          => 'Na prezeranie ďalších stránok sa musíte $1.',
'accmailtitle'              => 'Heslo odoslané.',
'accmailtext'               => "Heslo pre '$1' bolo poslané na $2.",
'newarticle'                => '(Nový)',
'newarticletext'            => "Sledovali ste odkaz na stránku, ktorá zatiaľ neexistuje.
Stránku vytvoríte tak, že začnete písať do dolného poľa a potom stlačíte tlačidlo \"Ulož stránku\".
(Viac informácií nájdete na stránkach [[{{ns:help}}:Obsah|Pomocníka]]).
Ak ste sa sem dostali nechtiac, iba kliknite na tlačidlo '''späť''' vo svojom prehliadači.",
'anontalkpagetext'          => "<br />
----
''Toto je diskusná stránka anonymného redaktora, ktorý nemá vytvorené svoje konto alebo ho nepoužíva. Preto musíme na jeho identifikáciu použiť numerickú IP adresu. Je možné, že takúto IP adresu používajú viacerí redaktori. Ak ste anonymný redaktor a máte pocit, že vám boli adresované irelevantné diskusné príspevky, zriaďte si konto alebo sa prihláste ([[Special:Userlogin|Zriadenie konta alebo prihlásenie]]), aby sa zamedzilo budúcim zámenám s inými anonymnými redaktormi''",
'noarticletext'             => '{{MediaWiki Noarticletext NS {{NAMESPACE}}}}',
'clearyourcache'            => "'''Poznámka:''' Aby sa zmeny prejavili, po uložení musíte vymazať vyrovnávaciu pamäť vášho prehliadača: '''Mozilla:''' ''Ctrl-Shift-R'', '''IE:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview'    => "<strong>Tip:</strong> Použite tlačítko 'Zobraz náhľad' na otestovanie Vášho nového CSS/JS pred uložením.",
'usercsspreview'            => "'''Nezabudnite, že toto je iba náhľad Vášho užívateľského CSS, ešte nebolo uložené!'''",
'userjspreview'             => "'''Nezabudnite, že iba testujete/náhľad vášho užívateľského JavaScriptu, ešte nebol uložený!'''",
'userinvalidcssjstitle'     => "'''Varovanie:''' Neexistuje skin \"\$1\". Pamätajte, že vlastné .css a .js stránky používajú názov s malými písmenami, napr. Redaktor:Foo/monobook.css na rozdiel od Redaktor:Foo/Monobook.css.",
'updated'                   => '(Aktualizovaný)',
'note'                      => '<strong>Poznámka: </strong>',
'previewnote'               => 'Nezabudnite, toto je len náhľad vami upravovanej stránky. Zmeny ešte nie sú uložené!',
'previewconflict'           => 'Tento náhľad upravenej stránky zobrazuje text z horného poľa s textom tak, ako sa zobrazí potom, keď ju uložíte.',
'session_fail_preview'      => '<strong>Prepáčte, nemohli sme spracovať Váš príspevok kvôli strate údajov relácie (session). Skúste to prosím ešte raz. Ak to nebude fungovať, skúste sa odhlásiť a znovu prihlásiť.</strong>',
'session_fail_preview_html' => "<strong>Prepáčte! Nemohli sme spracovať Vašu úpravu kvôli strate údajov relácie.</strong>

''Pretože táto wiki má použitie HTML umožnené, náhľad sa nezobrazí (prevencia pred JavaScript útokmi).''

<strong>Ak je toto legitímny pokus o úpravu, skúste prosím znova. Ak to stále nefunguje, skúste sa odhlásiť a znovu prihlásiť.</strong>",
'importing'                 => 'Importuje sa $1',
'editing'                   => 'Úprava stránky $1',
'editinguser'               => 'Úprava stránky $1',
'editingsection'            => 'Úprava stránky $1 (sekcia)',
'editingcomment'            => 'Úprava stránky $1 (komentár)',
'editconflict'              => 'Konflikt pri úprave: $1',
'explainconflict'           => 'Niekto iný zmenil túto stránku, zatiaľ čo
ste ju upravovali vy.
Horné okno na úpravy obsahuje text stránky tak, ako je momentálne platný.
Vaše úpravy sú uvedené v dolnom okne na úpravy.
Budete musieť zlúčiť vaše zmeny s existujúcim textom.
<b>Iba</b> obsah horného okna sa uloží, keď
stlačíte "Ulož stránku".<br />',
'yourtext'                  => 'Váš text',
'storedversion'             => 'Uložená verzia',
'nonunicodebrowser'         => '<strong>UPOZORNENIE: Váš prehliadač nepodporuje unicode. Dočasným riešením ako bezpečne upravovať stránky je, že ne-ASCII znaky sa v upravovacom textovom poli zobrazia ako zodpovedajúce hexadecimálne hodnoty.</strong>',
'editingold'                => '<div style="background: #FFBDBD; border: 1px solid #BB7979; color: #000000; font-weight: bold; margin: 2em 0 1em; padding: .5em 1em; vertical-align: middle; clear: both;">POZOR: Upravujete starú
verziu tejto stránky. Ak vašu úpravu uložíte, prepíšete tým všetky úpravy, ktoré nasledovali po tejto starej verzii.</div>',
'yourdiff'                  => 'Rozdiely',
'copyrightwarning'          => 'Nezabudnite, že všetky príspevky do {{GRAMMAR:genitív|{{SITENAME}}}} sa považujú za príspevky pod licenciou $2 (podrobnosti pozri pod $1). Ak nechcete, aby bolo to, čo ste napísali, neúprosne upravované a ďalej ľubovoľne rozširované, tak sem váš text neumiestňujte.<br />

Týmto sa právne zaväzujete, že ste tento text buď napísali sám, alebo že je skopírovaný
z voľného diela (public domain) alebo podobného zdroja neobmedzeného autorskými právami.
<strong>NEUMIESTŇUJTE TU BEZ POVOLENIA DIELA CHRÁNENÉ AUTORSKÝM PRÁVOM!</strong>',
'copyrightwarning2'         => 'Prosím uvedomte si, že všetky príspevky do {{GRAMMAR:genitív|{{SITENAME}}}} môžu byť upravované, skracované alebo odstránené inými príspievateľmi. Ak nechcete, aby Vaše texty boli menené, tak ich tu neuverejňujte.<br />

Týmto sa právne zaväzujete, že ste tento text buď napísali sám, alebo že je skopírovaný
z voľného diela (public domain) alebo podobného zdroja neobmedzeného autorskými právami (podrobnosti: $1).
<strong>NEUMIESTŇUJTE SEM BEZ POVOLENIA DIELA CHRÁNENÉ AUTORSKÝM PRÁVOM!</strong>',
'longpagewarning'           => '<strong>POZOR: Táto stránka má $1 kilobajtov; niektoré
prehliadače by mohli mať problémy s úpravou stránok, ktorých veľkosť sa blíži k alebo presahuje 32kb.
Zvážte, či by nebolo možné rozdeliť stránku na menšie sekcie.</strong>',
'longpageerror'             => '<strong>CHYBA: Text, ktorý ste poslali má $1 kilobajtov, čo je viac ako maximum $2 kilobajtov. Nie je možné ho uložiť.</strong>',
'readonlywarning'           => '<strong>POZOR: Databáza bola počas upravovania stránky zamknutá z dôvodu údržby,
takže stránku momentálne nemôžete uložiť. Môžete skopírovať a vložiť
text do textového súboru a uložiť si ho na neskôr.</strong>',
'protectedpagewarning'      => '<strong>POZOR: Táto stránka bola zamknutá, takže ju môžu upravovať iba redaktori s oprávnením správcu. Uistite sa, že rozumiete [[Project:Pravidlá zamykania stránok|pravidlám zamykania stránok]].</strong>',
'semiprotectedpagewarning'  => "'''Poznámka:''' Táto stránka bola zamknutá tak, aby ju mohli upravovať iba registrovaní používatelia.",
'templatesused'             => 'Šablóny použité na tejto stránke:',
'templatesusedpreview'      => 'Šablóny použité v tomto náhľade:',
'templatesusedsection'      => 'Šablóny použité v tejto sekcii:',
'edittools'                 => '<!-- Tento text sa zobrazí pod upravovacím a nahrávacím formulárom. -->',
'nocreatetitle'             => 'Tvorba nových stránok bola obmedzená',
'nocreatetext'              => 'Na tejto stránke je tvorba nových stránok obmedzená.
Teraz sa môžete vrátiť späť a upravovať existujúcu stránku alebo [[Special:Userlogin|sa prihlásiť alebo vytvoriť účet]].',

# "Undo" feature
'undo-summary' => 'Používateľ [[Special:Contributions/$2]] ([[User talk:$2]]) vrátil revíziu $1',

# Account creation failure
'cantcreateaccounttitle' => 'Nedá sa vytvoriť účet',
'cantcreateaccounttext'  => 'Vytvorenie účtu z tejto IP adresy (<b>$1</b>) bolo zablokované. Pravdepodobne je to kvôli sústavnému vandalizmu z adresy vašej školy či poskytovateľa internetového poskytovateľa.',

# History pages
'revhistory'                  => 'Predošlé verzie',
'viewpagelogs'                => 'Zobraziť záznamy pre túto stránku',
'nohistory'                   => 'Pre túto stránku neexistuje história.',
'revnotfound'                 => 'Predošlá verzia nebola nájdená',
'revnotfoundtext'             => 'Požadovaná staršia verzia stránky nebola nájdená.
Prosím skontrolujte URL adresu, ktorú ste použili na prístup k tejto stránke.',
'loadhist'                    => 'Sťahovanie histórie stránky',
'currentrev'                  => 'Aktuálna verzia',
'revisionasof'                => 'Verzia zo dňa a času $1',
'revision-info'               => 'Revízia z $1; $2',
'previousrevision'            => '← Staršia verzia',
'nextrevision'                => 'Novšia verzia →',
'currentrevisionlink'         => 'Zobrazenie aktuálnej úpravy',
'cur'                         => 'aktuálna',
'next'                        => 'ďalšia',
'last'                        => 'posledná',
'orig'                        => 'pôvodná',
'histlegend'                  => 'Legenda: (aktuálna) = rozdiel oproti aktuálnej verzii,
(posledná) = rozdiel oproti predchádzajúcej verzii, D = drobná úprava',
'deletedrev'                  => '[zmazané]',
'histfirst'                   => 'Najskorší',
'histlast'                    => 'Posledný',
'rev-deleted-comment'         => '(komentár odstránený)',
'rev-deleted-user'            => '(používateľské meno odstránené)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Táto revízia stránky bola odstránená z verejných archívov.
Podrobnosti nájdete v [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} zázname mazaní].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Táto revízia stránky bola odstránená z verejných archívov.
Ako správca tohto projektu si ju môžete prezrieť;
podrobnosti môžu byť v [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} zázname mazaní].
</div>',
'rev-delundel'                => 'ukáž/skry',

'history-feed-title'          => 'História úprav',
'history-feed-description'    => 'História úprav pre túto stránku na wiki',
'history-feed-item-nocomment' => '$1 na $2', # user at time
'history-feed-empty'          => 'Požadovaná stránka neexistuje.
Možno bola zmazaná z wiki alebo premenovaná.
Skúste [[Special:Search|vyhľadávať na wiki]] relevantné nové stránky.',

# Revision deletion
'revisiondelete'            => 'Zmazať/obnoviť revízie',
'revdelete-nooldid-title'   => 'Chýba cieľová revízia',
'revdelete-nooldid-text'    => 'Nešpecifikovali ste cieľovú revíziu alebo revízie, na ktorých sa má táto funkcia vykonať.',
'revdelete-selected'        => 'Vyber revíziu [[:$1]]:',
'revdelete-text'            => 'Zmazané revízie sú stále viditeľné v histórii úprav stránky,
ale ich obsah nebude prístupný verejnosti.

Iní správcovia tejto wiki budú stále môcť pristupovať k skrytému obsahu a môžu
ho znova obnoviť použitím tohto rozhrania v prípade, že operátormi projektu
nie sú stanovené ďakšie obmedzenia.',
'revdelete-legend'          => 'Nastav obmedzenia revízie:',
'revdelete-hide-text'       => 'Skry text revízie',
'revdelete-hide-comment'    => 'Skry zhrnutie úprav',
'revdelete-hide-user'       => 'Skry používateľské meno/IP redaktora',
'revdelete-hide-restricted' => 'Použi tieto obmedzenia na správcov ako aj na ostatných',
'revdelete-log'             => 'Komentár záznamu:',
'revdelete-submit'          => 'Použi na zvolenú revíziu',
'revdelete-logentry'        => 'viditeľnosť revízie bola zmenená pre [[$1]]',

# Diffs
'difference'                => '(Rozdiel medzi revíziami)',
'loadingrev'                => 'Sťahujem verzie, na zobrazenie rozdielov',
'lineno'                    => 'Riadok $1:',
'editcurrent'               => 'Upraviť aktuálnu verziu tejto stránky',
'selectnewerversionfordiff' => 'Vybrať na porovnanie novšiu verziu',
'selectolderversionfordiff' => 'Vybrať na porovnanie staršiu verziu',
'compareselectedversions'   => 'Porovnaj označené verzie',
'editundo'                  => 'Vrátiť',

# Search results
'searchresults'         => 'Výsledky vyhľadávania',
'searchresulttext'      => 'Viac informácií o vyhľadávaní vo {{GRAMMAR:lokál|{{SITENAME}}}} je uvedených na $1.',
'searchsubtitle'        => 'Na vyhľadávací dotaz "[[:$1]]"',
'searchsubtitleinvalid' => 'Na vyhľadávací dotaz "$1"',
'badquery'              => 'Nesprávne formulovaná požiadavka na vyhľadávanie',
'badquerytext'          => 'Váš text na prehľadávanie sme nemohli spracovať. Dôvodom je pravdepodobne to, že ste hľadali slovo kratšie ako tri písmená, čo zatiaľ {{SITENAME}} neumožňuje. Alebo ste možno výraz zle napísali, napríklad „dom a a záhrada“. Skúste iný text na prehľadávanie.',
'matchtotals'           => 'Výsledkom dotazu "$1" je {{plural:$2|jeden názov stránky|$3 názvy stránok|$3 názvov stránok}}
a text {{plural:$3|jednej stránky|$3 názvy stránok|$3 názvov stránok}}.',
'noexactmatch'          => "'''Neexistuje stránka nazvaná \"\$1\"'''. Chcete '''[[:\$1|vytvoriť novú stránku]]''' s týmto názvom?",
'titlematches'          => 'Vyhovujúce názvy stránok',
'notitlematches'        => 'V názvoch stránok nebola nájdená zhoda',
'textmatches'           => 'Zhody v textoch stránok',
'notextmatches'         => 'V textoch stránok nebola nájdená zhoda',
'prevn'                 => 'predošlá $1',
'nextn'                 => 'ďalšia $1',
'viewprevnext'          => 'Zobraz ($1) ($2) ($3).',
'showingresults'        => 'Nižšie je zobrazených <b>$1</b> výsledkov, počnúc od  #<b>$2</b>.',
'showingresultsnum'     => 'Nižšie je zobrazených <b>$3</b> výsledkov, počnúc od  #<b>$2</b>.',
'nonefound'             => "<strong>Poznámka</strong>: bezvýsledné vyhľadávania sú často spôsobené buď snahou hľadať príliš bežné, obyčajné slová (napríklad slovo ''je''), pretože tieto sa nezaraďujú do indexu vyhľadávača, alebo uvedením viac ako jedného vyhľadávaného výrazu, pretože výsledky uvádzajú len stránky obsahujúce všetky vyhľadávané výrazy.",
'powersearch'           => 'Vyhľadávanie',
'powersearchtext'       => 'Vyhľadávania v menných priestoroch :<br />
$1<br />
$2 Zoznam presmerovaní &nbsp; Hľadanie pre $3 $9',
'searchdisabled'        => 'Prepáčte! Fulltextové vyhľadávanie bolo dočasne vypnuté z dôvodu preťaženia. Zatiaľ môžete použiť hľadanie pomocou Google, ktoré však nemusí byť aktuálne.',
'blanknamespace'        => '(Hlavný)',

# Preferences page
'preferences'           => 'Nastavenia',
'mypreferences'         => 'nastavenia',
'prefsnologin'          => 'Nie ste prihlásený/á',
'prefsnologintext'      => 'Musíte byť [[Special:Userlogin|prihlásený/á]], aby ste mohli zmeniť vaše nastavenia.',
'prefsreset'            => 'Boli obnovené pôvodné nastavenia.',
'qbsettings'            => 'Bočný panel',
'changepassword'        => 'Zmeniť heslo',
'skin'                  => 'Vzhľad',
'math'                  => 'Vykreslenie matematiky',
'dateformat'            => 'Formát dátumu',
'datedefault'           => 'Predvolený',
'datetime'              => 'Dátum a čas',
'math_failure'          => 'Syntaktická analýza (parsing) neúspešná',
'math_unknown_error'    => 'neznáma chyba',
'math_unknown_function' => 'neznáma funkcia',
'math_lexing_error'     => 'lexikálna chyba',
'math_syntax_error'     => 'syntaktická chyba',
'math_image_error'      => 'PNG konverzia neúspešná; skontrolujte správnosť inštalácie programov: latex, dvips, gs a convert',
'math_bad_tmpdir'       => 'Nemôžem zapisovať alebo vytvoriť dočasný matematický adresár',
'math_bad_output'       => 'Nemôžem zapisovať alebo vytvoriť výstupný matematický adresár',
'math_notexvc'          => 'Chýbajúci program texvc; konfigurácia je popísaná v math/README.',
'prefs-personal'        => 'Profil',
'prefs-rc'              => 'Posledné úpravy',
'prefs-watchlist'       => 'Sledované stránky',
'prefs-watchlist-days'  => 'Koľko dní zobrazovať v sledovaných stránkach:',
'prefs-watchlist-edits' => 'Počet úprav, ktorý sa zobrazí v rozšírenom zozname sledovaných:',
'prefs-misc'            => 'Rôzne',
'saveprefs'             => 'Ulož nastavenia',
'resetprefs'            => 'Obnoviť pôvodné nastavenia',
'oldpassword'           => 'Staré heslo:',
'newpassword'           => 'Nové heslo:',
'retypenew'             => 'Nové heslo (ešte raz):',
'textboxsize'           => 'Úpravy',
'rows'                  => 'Riadky',
'columns'               => 'Stĺpce',
'searchresultshead'     => 'Vyhľadávanie',
'resultsperpage'        => 'Počet vyhovujúcich výsledkov zobrazených na strane',
'contextlines'          => 'Počet zobrazených riadkov z kažnej nájdenej stránky',
'contextchars'          => 'Počet kontextových znakov v riadku',
'stubthreshold'         => 'Hranica pre zobrazenie nedokončených stránok (výhonkov):',
'recentchangescount'    => 'Počet nadpisov uvedených v posledných úpravách',
'savedprefs'            => 'Vaše nastavenia boli uložené.',
'timezonelegend'        => 'Časové pásmo',
'timezonetext'          => 'Počet hodín, o ktorý sa váš miestny čas odlišuje od času na serveri (UTC).',
'localtime'             => 'Miestny čas',
'timezoneoffset'        => 'Rozdiel¹',
'servertime'            => 'Aktuálny čas na serveri',
'guesstimezone'         => 'Prevziať z prehliadača',
'allowemail'            => 'Povoľ prijímanie e-mailov od iných redaktorov',
'defaultns'             => 'Štandardne vyhľadávaj v týchto menných priestoroch:',
'default'               => 'predvolený',
'files'                 => 'Súbory',

# User rights
'userrights-lookup-user'     => 'Spravuj skupiny redaktorov',
'userrights-user-editname'   => 'Napíš meno redaktora:',
'editusergroup'              => 'Uprav skupinu Redaktora',
'userrights-editusergroup'   => 'Uprav skupinu',
'saveusergroups'             => 'Ulož skupinu',
'userrights-groupsmember'    => 'Člen skupiny:',
'userrights-groupsavailable' => 'Dostupné skupiny:',
'userrights-groupshelp'      => 'Označte skupiny, do ktorých chcete pridať alebo z ktorých chcete
odobrať redaktora. Neoznačené skupiny nebudú zmenené. Odobrať skupinu možete pomocou CTRL + kliknutie ľavým tlačidlom',

# Groups
'group'            => 'Skupina:',
'group-bot'        => 'Boti',
'group-sysop'      => 'Správcovia',
'group-bureaucrat' => 'Byrokrati',
'group-all'        => '(všetci)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Správca',
'group-bureaucrat-member' => 'Byrokrat',

'grouppage-bot'        => 'Project:Boti',
'grouppage-sysop'      => 'Project:Správcovia',
'grouppage-bureaucrat' => 'Project:Byrokrati',

# User rights log
'rightslog'      => 'Záznam užívateľských práv',
'rightslogtext'  => 'Toto je záznam zmien redaktorových práv.',
'rightslogentry' => 'členstvo v skupine zmenené pre $1 z $2 na $3',
'rightsnone'     => '(žiadne)',

# Recent changes
'changes'                           => 'úpravy',
'recentchanges'                     => 'Posledné úpravy',
'recentchangestext'                 => 'Pomocou tejto stránky sledujete posledné úpravy stránok {{GRAMMAR:genitív|{{SITENAME}}}}.
Pozrite si stránky [[Project:Vitajte|Vitajte!]], [[Project:FAQ|{{SITENAME}} FAQ]].

Ak chcete, aby {{SITENAME}} uspela, je veľmi dôležité, aby ste nepridávali
materiál obmedzený inými [[Project:Autorské právo|autorskými právami]].
Právne záväzky môžu projekt vážne poškodiť, takže Vás prosíme, aby ste to nerobili.',
'rcnote'                            => 'Tu je posledných <strong>$1</strong> úprav počas posledných <strong>$2</strong> dní ($3).',
'rcnotefrom'                        => 'Nižšie sú zobrazené úpravy od <b>$2</b> (do <b>$1</b>).',
'rclistfrom'                        => 'Zobraz nové úpravy počnúc od $1',
'rcshowhideminor'                   => '$1 drobné úpravy',
'rcshowhidebots'                    => '$1 botov',
'rcshowhideliu'                     => '$1 prihlásených používateľov',
'rcshowhideanons'                   => '$1 anonymných používateľov',
'rcshowhidepatr'                    => '$1 úpravy strážených stránok',
'rcshowhidemine'                    => '$1 moje úpravy',
'rclinks'                           => 'Zobraz posledných $1 úprav v posledných $2 dňoch<br />$3',
'diff'                              => 'rozdiel',
'hist'                              => 'história',
'hide'                              => 'skryť',
'show'                              => 'zobraz',
'minoreditletter'                   => 'D',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[sledujúcich redaktorov: $1]',
'rc_categories'                     => 'Obmedziť na kategórie (oddeľte "|")',
'rc_categories_any'                 => 'akékoľvek',

# Upload
'upload'                      => 'Nahranie súboru',
'uploadbtn'                   => 'Nahrať súbor',
'reupload'                    => 'Zopakovať nahranie',
'reuploaddesc'                => 'Späť k formuláru na nahranie.',
'uploadnologin'               => 'Nie ste prihlásený',
'uploadnologintext'           => 'Musíte byť [[Special:Userlogin|prihlásený/á]], aby ste mohli nahrávať súbory.',
'upload_directory_read_only'  => 'Nie je možné zapisovať webovým servrom do adresára pre nahrávanie ($1).',
'uploaderror'                 => 'Chyba pri nahrávaní',
'uploadtext'                  => "Tento formulár použite na nahrávanie súborov, na zobrazenie alebo hľadanie už nahraných súborov choďte na [[Special:Imagelist|zoznam nahraných súborov]], nahrávania a mazania sa tiež zaznamenávajú v [[Special:Log/upload|zázname nahrávaní]].

Na začlenenie obrázku do stránky použite odkaz v tvare

* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Súbor.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Súbor.png|alternatívny text]]</nowiki>'''
alebo pre priamy odkaz na súbor
* '''<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:Súbor.ogg]]</nowiki>'''",
'uploadlog'                   => 'Záznam nahrávaní',
'uploadlogpage'               => 'Záznam nahrávaní',
'uploadlogpagetext'           => 'Nižšie je zoznam nedávno nahraných súborov.
Všetky uvedené časy sú časy na serveri (UTC).',
'filename'                    => 'Meno súboru',
'filedesc'                    => 'Opis súboru',
'fileuploadsummary'           => 'Zhrnutie:',
'filestatus'                  => 'Stav autorských práv',
'filesource'                  => 'Zdroj',
'copyrightpage'               => 'Project:Autorské práva',
'copyrightpagename'           => 'autorské práva {{GRAMMAR:genitív|{{SITENAME}}}}',
'uploadedfiles'               => 'Nahrané súbory',
'ignorewarning'               => 'Ignorovať varovanie a súbor napriek tomu uložiť.',
'ignorewarnings'              => 'Ignorovať všetky varovania',
'minlength'                   => 'Názvy obrázkov musia obsahovať najmenej tri písmená.',
'illegalfilename'             => 'Názov súboru "$1" obsahuje znaky, ktoré nie sú povolené v názvoch stránok. Prosím premenujte súbor a skúste ho nahrať znovu.',
'badfilename'                 => 'Meno obrázka bolo zmenené na "$1".',
'badfiletype'                 => '".$1" nie je odporúčaný formát obrázkového súboru.',
'largefileserver'             => 'Tento súbor je väčší ako je možné nahrať na server (z dôvodu obmedzenia veľkosti súboru v konfigurácii servera).',
'emptyfile'                   => 'Zdá sa, že súbor, ktorý ste nahrali je prázdny. Mohlo sa stať, že ste urobili v názve súboru preklep. Prosím, skontrolujte, či skutočne chcete nahrať tento súbor.',
'fileexists'                  => 'Súbor s týmto názvom už existuje, prosím skontrolujte $1 ak nie ste si istý, či ho chcete zmeniť.',
'fileexists-forbidden'        => 'Súbor s týmto názvom už existuje; choďte prosím späť a nahrajte tento súbor pod iným názvom. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Súbor s týmto názvom už existuje v zdieľanom úložisku súborov; choďte prosím späť a nahrajte tento súbor pod iným názvom. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Nahranie bolo úspešné',
'fileuploaded'                => 'Súbor "$1" bol úspešne nahraný.
Nasledujte tento odkaz ($2) na stránku, na ktorej zadáte informácie na opis súboru, napríklad odkiaľ pochádza, kedy a kým bol vytvorený a všetko ostatné, čo o ňom prípadne viete. Ak je nahraný súbor obrázok, možno ho takto vložiť do stránky: <tt><nowiki>[[{{ns:Image}}:$1|thumb|Opis]]</nowiki></tt>',
'uploadwarning'               => 'Varovanie pri nahrávaní',
'savefile'                    => 'Ulož súbor',
'uploadedimage'               => 'nahraný „[[$1]]“',
'uploaddisabled'              => 'Prepáčte, nahrávanie je vypnuté.',
'uploaddisabledtext'          => 'Nahrávanie súborov na túto wiki je vypnuté.',
'uploadscripted'              => 'Tento súbor obsahuje kód HTML alebo skript, ktorý može byť chybne interpretovaný prehliadačom.',
'uploadcorrupt'               => 'Tento súbor je závadný alebo má nesprávnu príponu. Skontrolujte súbor a nahrajte ho znova.',
'uploadvirus'                 => 'Súbor obsahuje vírus! Detaily: $1',
'sourcefilename'              => 'Názov zdrojového súboru',
'destfilename'                => 'Názov cieľového súboru',
'watchthisupload'             => 'Sleduj túto stránku',
'filewasdeleted'              => 'Súbor s týmto názvom bol už nahraný a následne zmazaný. Mali by ste skontrolovať $1 predtým, ako budete pokračovať na opätovné nahranie.',

'upload-proto-error'      => 'Nesprávny protokol',
'upload-proto-error-text' => 'Vzdialené nahrávanie vyžaduje, aby URL začínali <code>http://</code> alebo <code>ftp://</code>.',
'upload-file-error'       => 'Vnútorná chyba',
'upload-file-error-text'  => 'Vyskytla sa vnútorná chyba pri pokuse vytvoriť dočasný súbor na serveri. Prosím, kontaktujte správcu systému.',
'upload-misc-error'       => 'Neznáma chyba pri nahrávaní',
'upload-misc-error-text'  => 'Počas nahrávania sa vyskytla neznáma chyba. Prosím, overte, že URL je platný a dostupný a skúste znova. Ak problém pretrváva, kontaktujte správcu systému.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nedostupný URL',
'upload-curl-error6-text'  => 'Poskytnutý URL nebol dostupný. Prosím, skontrolujte znova, že URL je správny a lokalita je dostupná.',
'upload-curl-error28'      => 'Vypršal čas vyhradený pre nahrávanie',
'upload-curl-error28-text' => 'Lokalite trvala odpoveď príliš dlho. Prosím, skontrolujte, či je lokalita dopstupná, chvíľu počkajte a skúste znova. Možno je potrebné skúsiť nahrávanie v čase, kedy je lokalita menej zaťažená.',

'license'            => 'Licencovanie',
'nolicense'          => 'Nič nebolo vybrané',
'upload_source_url'  => ' (platný, verejne prístupný URL)',
'upload_source_file' => ' (súbor na Vašom počítači)',

# Image list
'imagelist'                 => 'Zoznam nahraných obrázkov',
'imagelisttext'             => 'Tu je zoznam $1 obrázkov zoradený $2.',
'imagelistforuser'          => 'Zobrazuje iba obrázky nahrané redaktorom $1.',
'getimagelist'              => 'sťahujem zoznam nahraných obrázkov',
'ilsubmit'                  => 'Vyhľadávanie',
'showlast'                  => 'Zobraz posledných $1 obrázkov zoradených $2.',
'byname'                    => 'podľa mena',
'bydate'                    => 'podľa dátumu',
'bysize'                    => 'podľa veľkosti',
'imgdelete'                 => 'zmazať',
'imgdesc'                   => 'opis',
'imgfile'                   => 'súbor',
'imglegend'                 => 'Vysvetlivky: (opis) = zobraz/uprav opis obrázku.',
'imghistory'                => 'História súboru',
'revertimg'                 => 'obnov',
'deleteimg'                 => 'zmazať',
'deleteimgcompletely'       => 'Vymaž všetky verzie',
'imghistlegend'             => 'Vysvetlivky: (aktuálna) = toto je aktuálny obrázok, (zmazať) = zmaž
túto starú verziu, (pôvodná) = vráť sa k tejto starej verzii.
<br /><i>Kliknite na dátum, aby sa zobrazil obrázok nahraný v ten deň</i>.',
'imagelinks'                => 'Odkazy na obrázok',
'linkstoimage'              => 'Na tento obrázok odkazujú nasledujúce stránky:',
'nolinkstoimage'            => 'Žiadne stránky neobsahujú odkazy na tento obrázok.',
'sharedupload'              => 'Toto je zdieľaný súbor a je možné ho používať na iných projektoch.',
'shareduploadwiki'          => 'Ďalšie informácie pozrite na $1.',
'shareduploadwiki-linktext' => 'stránka opisu súboru',
'noimage'                   => 'Súbor s takým menom neexistuje, môžete ho $1',
'noimage-linktext'          => 'nahrať',
'uploadnewversion-linktext' => 'Nahrajte novú verziu tohto súboru.',
'imagelist_date'            => 'Dátum',
'imagelist_name'            => 'Názov',
'imagelist_user'            => 'Užívateľ',
'imagelist_size'            => 'Veľkosť (v bajtoch)',
'imagelist_description'     => 'Popis',
'imagelist_search_for'      => 'Hľadať názov obrázka:',

# MIME search
'mimesearch' => 'MIME vyhľadávanie',
'mimetype'   => 'MIME typ:',
'download'   => 'download',

# Unwatched pages
'unwatchedpages' => 'Nesledované stránky',

# List redirects
'listredirects' => 'Zoznam presmerovaní',

# Unused templates
'unusedtemplates'     => 'Nepoužité šablóny',
'unusedtemplatestext' => 'Táto stránka obsahuje zoznam všetkých stránok v mennom prisetore Šablóna:, ktoré nie sú vložené v žiadnej inej stránke. Pred zmazaním nezabudnite skontrolovať ostatné odkazy!',
'unusedtemplateswlh'  => 'iné odkazy',

# Random redirect
'randomredirect' => 'Náhodná presmerovacia stránka',

# Statistics
'statistics'             => 'Štatistiky',
'sitestats'              => 'Štatistika webu',
'userstats'              => 'Štatistika k redaktorom',
'sitestatstext'          => "{{SITENAME}} momentálne má '''$2''' stránok.
Do toho sa nezapočítavajú presmerovania, diskusné stránky, popisné stránky obrázkov, stránky používateľských profilov, šablóny, stránky Pomocníka, portály, stránky bez odkazov na iné stránky a stránky o {{GRAMMAR:lokál|{{SITENAME}}}}.
Vrátane týchto máme spolu '''$1''' stránok.

Celkovo bolo nahraných '''$8''' súborov.

Celkovo boli stránky navštívené '''$3'''-krát a upravené '''$4'''-krát. To znamená, že pripadá priemerne '''$5''' úprav na každú stránku a '''$6''' návštev na každú úpravu (od posledného vylepšenia (upgrade) softvéru 20. júla 2002).

[http://meta.wikimedia.org/wiki/Help:Job_queue Dĺžka frontu úloh] je momentálne '''$7'''.",
'userstatstext'          => "Celkovo je '''$1''' zaregistrovaných redaktorov,
z čoho '''$2''' (alebo '''$4%''') sú administrátormi (pozri $5).",
'statistics-mostpopular' => 'Najčastejšie prezerané stránky',

'disambiguations'     => 'Stránky na rozlíšenie viacerých významov',
'disambiguationspage' => 'Šablóna:Rozlišovacia stránka',

'doubleredirects'     => 'Dvojité presmerovania',
'doubleredirectstext' => 'Každý riadok obsahuje odkaz na prvé a druhé presmerovanie a tiež prvý riadok z textu na ktorý odkazuje druhé presmerovanie, ktoré zvyčajne odkazuje na "skutočný" cieľ, na ktorý má odkazovať prvé presmerovanie.',

'brokenredirects'     => 'Pokazené presmerovania',
'brokenredirectstext' => 'Tieto presmerovania odkazujú na neexistujúcu stránku.',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajty|bajtov}}',
'ncategories'             => '$1 {{PLURAL:$1|kategória|kategórie|kategórií}}',
'nlinks'                  => '$1 {{PLURAL:$1|odkaz|odkazy|odkazov}}',
'nmembers'                => '$1 {{PLURAL:$1|člen|členovia|členov}}',
'nrevisions'              => '$1 {{PLURAL:$1|revízia|revízie|revízií}}',
'nviews'                  => '$1 {{PLURAL:$1|návšteva|návštevy|návštev}}',
'lonelypages'             => 'Opustené stránky',
'lonelypagestext'         => 'Na nasledujúce stránky neodkazujú žiadne iné stránky z tejto wiki.',
'uncategorizedpages'      => 'Nekategorizované stránky',
'uncategorizedcategories' => 'Nekategorizované kategórie',
'uncategorizedimages'     => 'Nekategorizované obrázky',
'unusedcategories'        => 'Nepoužité kategórie',
'unusedimages'            => 'Opustené obrázky',
'popularpages'            => 'Populárne stránky',
'wantedcategories'        => 'Žiadané kategórie',
'wantedpages'             => 'Žiadané stránky',
'mostlinked'              => 'Najčastejšie odkazované stránky',
'mostlinkedcategories'    => 'Najčastejšie odkazované kategórie',
'mostcategories'          => 'Stránky s najväčším počtom kategórií',
'mostimages'              => 'Najčastejšie odkazované obrázky',
'mostrevisions'           => 'Stránky s najväčším počtom revízií',
'allpages'                => 'Všetky stránky',
'prefixindex'             => 'Index prefixu',
'randompage'              => 'Náhodná stránka',
'shortpages'              => 'Krátke stránky',
'longpages'               => 'Dlhé stránky',
'deadendpages'            => 'Slepé stránky',
'deadendpagestext'        => 'Nasledujúce stránky neodkazujú na žiadne iné stránky z tejto wiki.',
'listusers'               => 'Zoznam redaktorov',
'specialpages'            => 'Špeciálne stránky',
'spheading'               => 'Špeciálne stránky pre všetkých redaktorov',
'restrictedpheading'      => 'Obmedzené špeciálne stránky',
'recentchangeslinked'     => 'Súvisiace úpravy',
'rclsub'                  => '(na stránky, na ktoré odkazuje "$1")',
'newpages'                => 'Nové stránky',
'newpages-username'       => 'Meno používateľa:',
'ancientpages'            => 'Najdávnejšie upravované stránky',
'intl'                    => 'Mezijazykové odkazy',
'move'                    => 'Presuň',
'movethispage'            => 'Presuň túto stránku',
'unusedimagestext'        => '<p>Prosím, uvedomte si, že iné web stránky môžu odkazovať na tento obrázok priamo URL adresou a tak tu môžu byť uvedené napriek tomu, že ich externé stránky používajú.</p>',
'unusedcategoriestext'    => 'Nasledovné stránky kategórií existujú napriek tomu, že ich nepoužíva žiadna iná stránka ani kategória.',

# Book sources
'booksources' => 'Knižné zdroje',

'categoriespagetext' => 'Nasledujúce kategórie existujú vo wiki.',
'data'               => 'Dáta',
'userrights'         => 'Spravovanie redaktorských práv',
'groups'             => 'Skupiny redaktorov',
'isbn'               => 'ISBN',
'alphaindexline'     => '$1 do $2',
'version'            => 'Zobraz verziu MediaWiki',
'log'                => 'Záznamy',
'alllogstext'        => 'Kombinované zobrazenie nahrávaní, mazaní, zamknutí, blokovaní a akcií sysopa.
Môžete zmenšiť rozsah, ak zvolíte typ záznamu, meno redaktora alebo dotyčnú stránku.',
'logempty'           => 'V zázname neboli nájdené zodpovedajúce položky.',

# Special:Allpages
'nextpage'          => 'Ďalšia stránka ($1)',
'allpagesfrom'      => 'Zobraz stránky od:',
'allarticles'       => 'Všetky stránky',
'allinnamespace'    => 'Všetky stránky (menný priestor $1)',
'allnotinnamespace' => 'Všetky stránky (nie z menného priestoru $1)',
'allpagesprev'      => 'Predchádzajúci',
'allpagesnext'      => 'Ďalší',
'allpagessubmit'    => 'Choď',
'allpagesprefix'    => 'Zobraz stránky s predponou:',
'allpagesbadtitle'  => 'Zadaný názov stránky je neplatný alebo mal medzijazykový alebo interwiki prefix. Môže obsahovať jeden alebo viac znakov, ktoré nie je možné použiť v názve stránky.',

# Special:Listusers
'listusersfrom' => 'Zobraziť používateľov počnúc:',

# E-mail user
'mailnologin'     => 'Žiadna adresa na zaslanie',
'mailnologintext' => 'Musíte byť [[Special:Userlogin|prihlásený]] a mať platnú e-mailovú adresu vo vašich [[Special:Preferences|nastaveniach]], aby ste mohli iným redaktorom posielať e-maily.',
'emailuser'       => 'E-mail tomuto redaktorovi',
'emailpage'       => 'E-mail redaktorovi',
'emailpagetext'   => 'Ak tento redaktor zadal platnú e-mailovú adresu vo svojich nastaveniach,
môžete mu pomocou dole uvedeného formulára poslať e-mail.
E-mailová adresa, ktorú ste zadali vo vašich nastaveniach sa zobrazí
ako adresa odosielateľa e-mailu, aby bol príjemca schopný vám
odpovedať.',
'usermailererror' => 'Emailový program vrátil chybu:',
'defemailsubject' => 'email {{GRAMMAR:genitív|{{SITENAME}}}}',
'noemailtitle'    => 'Chýba e-mailová adresa',
'noemailtext'     => 'Tento redaktor nešpecifikoval platnú e-mailovú adresu
alebo sa rozhodol, že nebude prijímať e-maily od druhých redaktorov.',
'emailfrom'       => 'Odosielateľ',
'emailto'         => 'Príjemca',
'emailsubject'    => 'Vec',
'emailmessage'    => 'Správa',
'emailsend'       => 'Odoslať',
'emailccme'       => 'Pošli mi emailom kópiu mojej správy.',
'emailccsubject'  => 'Kópia správy pre $1: $2',
'emailsent'       => 'E-mail bol odoslaný',
'emailsenttext'   => 'Vaša e-mailová správa bola odoslaná.',

# Watchlist
'watchlist'            => 'Sledované stránky',
'watchlistfor'         => "(používateľa '''$1''')",
'nowatchlist'          => 'V zozname sledovaných stránok nemáte žiadne položky.',
'watchlistanontext'    => 'Prosím $1 pre prezeranie alebo úpravu Vášho zoznamu sledovaných stránok.',
'watchlistcount'       => "'''Na zozname sledovaných máte $1 položiek (vrátane diskusných stránok).'''",
'clearwatchlist'       => 'Vyčistiť zoznam sledovaných',
'watchlistcleartext'   => 'Určite ich chcete odstrániť?',
'watchlistclearbutton' => 'Vyčistiť zoznam sledovaných',
'watchlistcleardone'   => 'Váš zoznam sledovaných bol vyčistený. $1 položiek bolo odstránených.',
'watchnologin'         => 'Nie ste prihlásený/á',
'watchnologintext'     => 'Musíte byť [[Special:Userlogin|prihlásený/á]], aby ste mohli modifikovať vaše sledované stránky.',
'addedwatch'           => 'Pridaná do zoznamu sledovaných stránok',
'addedwatchtext'       => "Stránka [[\$1]] bola pridaná do [[Special:Watchlist|sledovaných stránok]]. Budú tam uvedené ďalšie úpravy tejto stránky a jej diskusie a stránka bude zobrazená '''tučne''' v [[Special:Recentchanges|zozname posledných úprav]], aby ste ju ľahšie našli. 

Ak budete chcieť neskôr stránku odstrániť zo sledovaných stránok, kliknite na \"nesleduj\" v horných záložkách.",
'removedwatch'         => 'Odstránená zo zoznamu sledovaných stránok',
'removedwatchtext'     => 'Stránka "$1" bol odstránená z vášho zoznamu sledovaných stránok.',
'watch'                => 'Sleduj',
'watchthispage'        => 'Sleduj túto stránku',
'unwatch'              => 'Nesleduj',
'unwatchthispage'      => 'Nesleduj túto stránku',
'notanarticle'         => 'Toto nie je stránka',
'watchnochange'        => 'V rámci zobrazeného času nebola upravená žiadna z Vašich sledovaných stránok.',
'watchdetails'         => '($1 sledovaných stránok, nepočítajúc stránky diskusie;
$2 úprav stránok spolu od ukončenia;
$3...
[[Special:Watchlist/edit|zobraz a upravuj úplný zoznam]].)',
'wlheader-enotif'      => '* Upozorňovanie e-mailom je zapnuté.',
'wlheader-showupdated' => "* Stránky, ktoré boli zmené od vašej poslednej návštevy sú zobrazené '''tučne'''.",
'watchmethod-recent'   => 'kontrolujem posledné úpravy sledovaných stránok',
'watchmethod-list'     => 'kontrolujem sledované stránky na posledné úpravy',
'removechecked'        => 'Odstráň vybrané položky zo zoznamu sledovaných stránok',
'watchlistcontains'    => 'Váš zoznam sledovaných stránok obsahuje $1 položiek.',
'watcheditlist'        => "Tu je abecedný zoznam vašich
sledovaných stránok. Označte stránky, ktoré chcete odstrániť a kliknite na tlačidlo
'Odstráň vybrané'
na spodnej časti obrazovky (odstránie stránky v hlavnom mennom priestore tiež odstráni príslušnú diskusnú stránku a naopak).",
'removingchecked'      => 'Odstraňujem požadované položky zo zoznamu sledovaných stránok...',
'couldntremove'        => "Nemôžem odstrániť položku '$1'...",
'iteminvalidname'      => "Problém s položkou '$1', neplatné meno...",
'wlnote'               => 'Nižšie je posledných $1 zmien v posledných <b>$2</b> hodinách.',
'wlshowlast'           => 'Zobraz posledných $1 hodín $2 dní $3',
'wlsaved'              => 'Toto je uložená verzia zoznamu vašich sledovaných stránok.',
'wldone'               => 'Hotovo.',

'enotif_mailer'      => 'Upozorňovač {{GRAMMAR:genitív|{{SITENAME}}}}',
'enotif_reset'       => 'Vynulovať upozornenia (nastav ich status na "navštívené")',
'enotif_newpagetext' => 'Toto je nová stránka.',
'changed'            => 'zmene',
'created'            => 'vytvorení',
'enotif_subject'     => '{{SITENAME}} - stránka $PAGETITLE bola $CHANGEDORCREATED $PAGEEDITOR',
'enotif_lastvisited' => 'Pozrite $1 pre všetky zmeny od vašej poslednej návštevy.',
'enotif_body'        => 'Drahý $WATCHINGUSERNAME,

na {{GRAMMAR:lokál|{{SITENAME}}}} došlo $PAGEEDITDATE k $CHANGEDORCREATED stránky $PAGETITLE redaktorom $PAGEEDITOR, pozrite si aktuálnu verziu $PAGETITLE_URL .

$NEWPAGE

Zhrnutie: $PAGESUMMARY $PAGEMINOREDIT
Kontaktujte redaktora:
mail $PAGEEDITOR_EMAIL
wiki $PAGEEDITOR_WIKI

Nedostanete ďalšie upozornenia, aj ak bude stránka znovu upravovaná, kým nenavštívíte túto stránku. Možete tiež vynulovať upozornenia pre všetky vaše sledované stránky.

 Váš upozorňovací systém {{GRAMMAR:genitív|{{SITENAME}}}}

--
Pre zmenu nastavenia vašich sledovaných stránok navštívte
{{fullurl:Special:Watchlist/edit}}

Návrhy a ďalšia pomoc:
{{fullurl:Pomoc:Obsah}}',

# Delete/protect/revert
'deletepage'                  => 'Zmazať stránku',
'confirm'                     => 'Potvrdiť',
'excontent'                   => "obsah bol: '$1'",
'excontentauthor'             => "obsah bol: '$1' (a jediný autor bol '[[Special:Contributions/$2]]')",
'exbeforeblank'               => "obsah pred vyčistením stránky bol: '$1'",
'exblank'                     => 'stránka bola prázdna',
'confirmdelete'               => 'Potvrdiť zmazanie',
'deletesub'                   => '(Mažem "$1")',
'historywarning'              => 'POZOR: Stránka, ktorú chcete zmazať má históriu:',
'confirmdeletetext'           => 'Idete trvalo zmazať z databázy stránku alebo obrázok spolu so všetkými jeho/jej predošlými verziami. Potvrďte, že máte v úmysle tak urobiť, že ste si vedomý následkov, a že to robíte v súlade so [[Project:Zásady a smernice|zásadami a smernicami {{GRAMMAR:genitív|{{SITENAME}}}}]].',
'actioncomplete'              => 'Akcia ukončená',
'deletedtext'                 => '"$1" bol zmazaný.
Na $2 nájdete zoznam posledných zmazaní.',
'deletedarticle'              => '„[[$1]]“ zmazaný',
'dellogpage'                  => 'Záznam zmazaní',
'dellogpagetext'              => 'Tu je zoznam posledných zmazaní.
Všetky zobrazené časy sú časy na serveri (UTC).
<ul>
</ul>',
'deletionlog'                 => 'záznam zmazaní',
'reverted'                    => 'Obnovené na skoršiu verziu',
'deletecomment'               => 'Dôvod na zmazanie',
'imagereverted'               => 'Obnovenie skoršej verzie bolo úspešné.',
'rollback'                    => 'Rollback úprav',
'rollback_short'              => 'Rollback',
'rollbacklink'                => 'rollback',
'rollbackfailed'              => 'Rollback neúspešný',
'cantrollback'                => 'Nemôžem úpravu vrátiť späť, posledný autor je jediný autor tejto stránky.',
'alreadyrolled'               => 'Nemôžem vrátiť späť poslednú úpravu [[$1]] od [[User:$2|$2]] ([[User talk:$2|Diskusia]]); niekto iný buď upravoval stránku, alebo už vrátil späť.

Autorom poslednej úpravy je [[User:$3|$3]] ([[User talk:$3|Diskusia]]).',
'editcomment'                 => 'Komentár k úprave bol: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Posledné úpravy používateľa [[Special:Contributions/$2|$2]] ([[User_talk:$2|diskusia]]) vrátené; bola obnovená posledná úprava $1',
'sessionfailure'              => 'Zdá sa, že je problém s vašou prihlasovacou reláciou;
táto akcia bola zrušená ako prevencia proti zneužitiu relácie (session).
Prosím, stlačte "naspäť", obnovte stránku, z ktorej ste sa sem dostali, a skúste to znova.',
'protectlogpage'              => 'Záznam_zamknutí',
'protectlogtext'              => 'Nižšie je zoznam zamknutí/odomknutí stránok.
Pre dodatočné informácie pozrite [[Project:Zamknutá stránka]].',
'protectedarticle'            => 'zamyká "[[$1]]"',
'unprotectedarticle'          => 'odomyká "[[$1]]"',
'protectsub'                  => '(Zamykám "$1")',
'confirmprotecttext'          => 'Skutočne chcete zamknúť túto stránku?',
'confirmprotect'              => 'Potvrďte zamknutie',
'protectmoveonly'             => 'Zamkni iba presuny stránky',
'protectcomment'              => 'Dôvod zamknutia',
'unprotectsub'                => '(Odomykám "$1")',
'confirmunprotecttext'        => 'Skutočne chcete odomknúť túto stránku?',
'confirmunprotect'            => 'Potvrďte odomknutie',
'unprotectcomment'            => 'Dôvod odomknutia',
'protect-unchain'             => 'Odomknúť povolenia pre presun',
'protect-text'                => 'Úroveň ochrany stránky [[$1]] si môžete pozrieť tu.
Uistite sa prosím, že dodržiavate [[Project:Chránená stránka|zásady projektu]].',
'protect-viewtext'            => 'Váš účet nemá povolenie meniť úrovne ochrany stránky. Tu sú aktuálne nastavenia stránky [[$1]]:',
'protect-default'             => '(predvolené)',
'protect-level-autoconfirmed' => 'Zablokuj neregistrovaných používateľov',
'protect-level-sysop'         => 'Len pre správcov',

# Restrictions (nouns)
'restriction-edit' => 'Úprava',
'restriction-move' => 'Presun',

# Undelete
'undelete'                 => 'Obnov zmazanú stránku',
'undeletepage'             => 'Zobraz a obnov zmazané stránky',
'viewdeletedpage'          => 'Zobraz zmazané stránky',
'undeletepagetext'         => 'Tieto stránky boli zmazané, ale sú stále v archíve a
môžu byť obnovené. Archív môže byť pravidelne vyprázdnený.',
'undeleteextrahelp'        => "Ak chcete obnoviť celú stránku, nechajte všetky zaškrtávacie polia nezaškrtnuté a kliknite na '''''Obnov!'''''.
Ak chcete vykonať selektívnu obnovu, zašktrnite polia zodpovedajúce revíziám, ktoré sa majú obnoviť a kliknite na '''''Obnov'''''.
Kliknutie na '''''Reset''''' vyčistí pole s komentárom a všetky zaškrtávacie polia.",
'undeletearticle'          => 'Obnov zmazanú stránku',
'undeleterevisions'        => '$1 {{PLURAL:verzia je archivovaná|verzie sú archivované|verzií je archivovaných}}',
'undeletehistory'          => 'Ak obnovíte túto stránku, obnovia sa aj všetky predchádzajúce verzie do zoznamu predchádzajúcich verzií.
Ak bola od zmazania vytvorená nová stránka s rovnakým názvom, zobrazia sa
obnovené verzie ako posledné úpravy novej stránky a aktuálna verzia novej stránky
nebude automaticky nahradená.',
'undeletehistorynoadmin'   => 'Táto stránka bola zmazaná. Dôvod zmazania je zobrazený dolu v zhrnutí spolu s podrobnosťami o používateľoch, ktorí túto stránku upravovali pred zmazaním. Samotný text týchto zmazaných revízií je prístupný iba správcom.',
'undeleterevision-missing' => 'Neplatná alebo chýbajúca revízia. Zrejme ste použili zlý odkaz alebo revízia bola obnovená alebo odstránená z histórie.',
'undeletebtn'              => 'Obnov!',
'undeletereset'            => 'Reset',
'undeletecomment'          => 'Komentár:',
'undeletedarticle'         => 'obnovený „[[$1]]“',
'undeletedrevisions'       => '$1 verzií obnovených',
'undeletedrevisions-files' => '$1 revízií a $2 súbor(ov) obnovených',
'undeletedfiles'           => '$1 súbor(ov) obnovený(ch)',
'cannotundelete'           => 'Obnovenie sa nepodarilo; pravdepodobne niekto iný obnovil stránku skôr ako Vy.',
'undeletedpage'            => "<big>'''$1 bol obnovený'''</big>

Zoznam posledných mazaní a obnovení nájdete v [[Special:Log/delete|Zázname mazaní]].",

# Namespace form on various pages
'namespace' => 'Menný priestor:',
'invert'    => 'Invertovať výber',

# Contributions
'contributions' => 'Príspevky redaktora',
'mycontris'     => 'Moje príspevky',
'contribsub'    => 'Pre $1',
'nocontribs'    => 'Neboli nájdené úpravy, ktoré by zodpovedali týmto kritériám.',
'ucnote'        => 'Nižšie je posledných <b>$1</b> úprav od tohto redaktora uskutočnených počas posledných <b>$2</b> dní.',
'uclinks'       => 'Zobraz posledných $1 úprav; zobraz posledných $2 dní.',
'uctop'         => '(posledná úprava)',
'newbies'       => 'začiatočníci',

'sp-contributions-newest'      => 'Najnovšie',
'sp-contributions-oldest'      => 'Najstaršie',
'sp-contributions-newer'       => 'Novších $1',
'sp-contributions-older'       => 'Starších $1',
'sp-contributions-newbies-sub' => 'Pre nováčikov',

'sp-newimages-showfrom' => 'Zobraz nové obrázky počínajúc $1',

# What links here
'whatlinkshere'        => 'Odkazy na túto stránku',
'whatlinkshere-barrow' => '&lt;',
'notargettitle'        => 'Nebol zadaný cieľ',
'notargettext'         => 'Nezadali ste cieľovú stránku alebo redaktora,
na ktorý/-ého chcete aplikovať túto funkciu.',
'linklistsub'          => '(Zoznam odkazov)',
'linkshere'            => "Nasledujúce stránky odkazujú na '''[[:$1]]''':",
'nolinkshere'          => "Žiadne stránky neodkazujú na '''[[:$1]]'''.",
'isredirect'           => 'presmerovacia stránka',
'istemplate'           => 'použitá',

# Block/unblock
'blockip'                     => 'Zablokovať redaktora',
'blockiptext'                 => 'Použite dolu uvedený formulár na zablokovanie možnosti zápisov uskutočnených z IP adresy alebo od redaktora.
Mali by ste to urobiť len na zabránenie vandalizmu a v súlade so [[Project:Zásady a smernice|zásadami a smernicami {{GRAMMAR:genitív|{{SITENAME}}}}]].
Nižšie uveďte konkrétny dôvod (napríklad uveďte konkrétne stránky, ktoré padli za obeť vandalizmu).',
'ipaddress'                   => 'IP adresa/meno redaktora',
'ipadressorusername'          => 'IP adresa/meno redaktora',
'ipbexpiry'                   => 'Ukončenie',
'ipbreason'                   => 'Dôvod',
'ipbanononly'                 => 'Blokovať iba anonymných používateľov.',
'ipbcreateaccount'            => 'Zabráň vytváraniu účtov',
'ipbenableautoblock'          => 'Automaticky blokovať poslednú IP adresu, ktorú tento používateľ použil, a všetky ďalšie adresy, z ktorých sa pokúsi upravovať.',
'ipbsubmit'                   => 'Zablokovať tohto redaktora',
'ipbother'                    => 'Iný čas',
'ipboptions'                  => '2 hodiny:2 hours,1 deň:1 day,3 dni:3 days,1 týždeň:1 week,2 týždne:2 weeks,1 mesiac:1 month,3 mesiace:3 months,6 mesiacov:6 months,1 rok:1 year,na neurčito:infinite',
'ipbotheroption'              => 'iný čas',
'badipaddress'                => 'IP adresa má nesprávny formát.',
'blockipsuccesssub'           => 'Zablokovanie bolo úspešné',
'blockipsuccesstext'          => '"$1" bol/a zablokovaný/á.<br />
[[Special:Ipblocklist|IP block list]] obsahuje zoznam blokovaní.',
'unblockip'                   => 'Odblokovať redaktora',
'unblockiptext'               => 'Použite nižšie uvedený formulár na obnovenie možnosti zápisov
z doteraz zablokovanej IP adresy alebo od redaktora.',
'ipusubmit'                   => 'Odblokovať túto adresu',
'unblocked'                   => '[[User:$1|$1]] bol odblokovaný',
'ipblocklist'                 => 'Zablokovaní/é redaktori/IP adresy',
'blocklistline'               => '$1, $2 zablokoval $3 (ukončenie $4)',
'infiniteblock'               => 'na neurčito',
'expiringblock'               => 'ukončenie $1',
'anononlyblock'               => 'iba anon.',
'noautoblockblock'            => 'automatické blokovanie vypnuté',
'createaccountblock'          => 'tvorba účtov bola zablokovaná',
'ipblocklistempty'            => 'Zoznam blokovaných je prázdny.',
'blocklink'                   => 'zablokovať',
'unblocklink'                 => 'odblokuj',
'contribslink'                => 'príspevky',
'autoblocker'                 => 'Ste zablokovaný, pretože zdieľate IP adresu s "$1". Dôvod "$2".',
'blocklogpage'                => 'Záznam_blokovaní',
'blocklogentry'               => 'zablokoval/a "[[$1]]" s časom ukončenia $2',
'blocklogtext'                => 'Toto je zoznam blokovaní a odblokovaní redaktorov. Automaticky
blokované IP adresy nie sú zahrnuté. Viď zoznam
[[Special:Ipblocklist|aktuálnych zákazov a blokovaní]].',
'unblocklogentry'             => 'odblokoval/a "$1"',
'range_block_disabled'        => 'Možnosť správcov vytvárať rozsah zablokovaní je vypnutá.',
'ipb_expiry_invalid'          => 'Neplatný čas ukončenia.',
'ipb_already_blocked'         => '"$1" je už zablokovaný',
'ip_range_invalid'            => 'Neplatný IP rozsah.',
'proxyblocker'                => 'Blokovač proxy',
'ipb_cant_unblock'            => 'Chyba: ID bloku $1 nenájdený. Možno už bol odblokovaný.',
'proxyblockreason'            => 'Vaša IP adresa bola zablokovaná, pretože je otvorená proxy. Prosím kontaktujte vášho internetového poskytovateľa alebo technickú podporu a informujte ich o tomto vážnom bezpečnostnom probléme.',
'proxyblocksuccess'           => 'Hotovo.',
'sorbs'                       => 'SORBS DNSBL',
'sorbsreason'                 => 'Vaša IP adresa je vedená ako nezabezpečený proxy server v [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Vaša IP adresa je vedená ako nezabezpečený proxy server v [http://www.sorbs.net SORBS] DNSBL. Nemôžete si vytvoriť účet.',

# Developer tools
'lockdb'              => 'Zamknúť databázu',
'unlockdb'            => 'Odomknúť databázu',
'lockdbtext'          => 'Zamknutím databázy sa preruší možnosť všetkých
redaktorov upravovať stránky, meniť svoje nastavenia, upravovať sledované stránky a
iné veci vyžadujúce zmeny v databáze.
Potvrďte, že to naozaj chcete urobiť, a že
odomknete databázu po ukončení údržby.',
'unlockdbtext'        => 'Odomknutie databázy obnoví schopnosť všetkých
redaktorov upravovať stránky, meniť svoje nastavenia, upravovať svoj zoznam sledovaných stránok a
iné veci vyžadujúce zmeny v databáze.
Potvrďte, že to naozaj chcete urobiť.',
'lockconfirm'         => 'Áno, naozaj chcem zamknúť databázu.',
'unlockconfirm'       => 'Áno, naozaj chcem odomknúť databázu.',
'lockbtn'             => 'Zamknúť databázu',
'unlockbtn'           => 'Odomknúť databázu',
'locknoconfirm'       => 'Neoznačili ste potvrdzovacie pole.',
'lockdbsuccesssub'    => 'Zamknutie databázy úspešné',
'unlockdbsuccesssub'  => 'Databáza bola úspešne odomknutá',
'lockdbsuccesstext'   => 'Databáza bola dočasne zamknutá.',
'unlockdbsuccesstext' => 'Databáza {{GRAMMAR:genitív|{{SITENAME}}}} bola odomknutá.',
'lockfilenotwritable' => 'Súbor, ktorý zamyká databázu nie je zapisovateľný. Aby bolo možné zamknúť či odomknúť databázu, je potrebné, aby doňho mohol web server zapisovať.',
'databasenotlocked'   => 'Databáza nie je zamknutá.',

# Move page
'movepage'                => 'Presunúť stránku',
'movepagetext'            => "Pomocou tohto formulára premenujete stránku a premiestnite všetky jej predchádzajúce verzie pod zadané nové meno.
Starý názov sa stane presmerovacou stránkou na nový názov.
Odkazy na starú stránku sa však nezmenia, ubezpečte sa, že ste skontrolovali
výskyt dvojitých alebo pokazených presmerovaní.
Vy ste zodpovedný za to, aby odkazy naďalej ukazovali
tam, kam majú.

Uvedomte si, že stránka sa '''nepremiestni''', ak pod novým názvom
už stránka existuje. Toto neplatí iba ak je stránka prázdna alebo presmerovacia a nemá
žiadne predchádzajúce verzie. To znamená, že môžete premenovať stránku späť na názov,
ktorý mala pred premenovaním, ak ste sa pomýlili, a že nemôžete prepísať
existujúcu stránku.

<b>POZOR!</b>
Toto môže byť drastická a nečakaná zmena pre populárnu stránku;
ubezpečte sa preto, skôr ako budete pokračovať, že chápete
dôsledky svojho činu.",
'movepagetalktext'        => "Príslušná diskusná stránka (ak existuje) bude premiestnená spolu so samotnou stránkou; '''nestane sa tak, iba ak:'''
*už existuje Diskusná stránka pod týmto novým menom, alebo
*nezaškrtnete nižšie sa nachádzajúci textový rámček.

V takých prípadoch budete musieť, ak si to želáte, premiestniť alebo zlúčiť stránku ručne.",
'movearticle'             => 'Presuň stránku',
'movenologin'             => 'Nie ste prihlásený',
'movenologintext'         => 'Musíte byť registrovaný redaktor a [[Special:Userlogin|prihlásený]], aby ste mohli presunúť stránku.',
'newtitle'                => 'Na nový názov',
'movepagebtn'             => 'Presunúť stránku',
'pagemovedsub'            => 'Presun bol úspešný',
'pagemovedtext'           => 'Stránka "[[$1]]" bola presunutá na "[[$2]]".',
'articleexists'           => 'Stránka s týmto názvom už existuje alebo
vami zadaný názov je neplatný.
Prosím vyberte si iný názov.',
'talkexists'              => "'''Samotná stránka bola úspešne premiestnená,
ale diskusná stránka sa nedala premiestniť,
pretože už jedna existuje pod zadaným novým názvom.
Prosím, zlúčte ich ručne.'''",
'movedto'                 => 'presunutý na',
'movetalk'                => 'Premiestniť aj "diskusnú" stránku, ak je to možné.',
'talkpagemoved'           => 'Príslušná diskusná stránka bola tiež premiestnená.',
'talkpagenotmoved'        => 'Príslušná diskusná stránka <strong>nebola</strong> premiestnená.',
'1movedto2'               => '[[$1]] premiestnená na [[$2]]',
'1movedto2_redir'         => '[[$1]] premiestnená na [[$2]] výmenou presmerovania',
'movelogpage'             => 'Záznam presunov',
'movelogpagetext'         => 'Tu je zoznam posledných presunutí.',
'movereason'              => 'Dôvod',
'revertmove'              => 'obnova',
'delete_and_move'         => 'Vymaž a presuň',
'delete_and_move_text'    => '==Je potrebné zmazať stránku==

Cieľová stránka "[[$1]]" už existuje. Chcete ho vymazať a vytvoriť tak priestor pre presun?',
'delete_and_move_confirm' => 'Áno, zmaž stránku',
'delete_and_move_reason'  => 'Vymaž, aby sa umožnil presun',
'selfmove'                => 'Zdrojový a cieľový názov sú rovnaké; nemôžem presunúť stránku na seba samú.',
'immobile_namespace'      => 'Cieľový názov je špeciálneho typu; nemôžem presunúť stránku do tohto menného priestoru.',

# Export
'export'          => 'Export stránok',
'exporttext'      => 'Môžete exportovať text a históriu úprav konkrétnej
stránky alebo množiny stránok do XML; tieto môžu byť potom importované do iného
wiki používajúceho MediaWiki softvér pomocou stránky Special:Import.

Pre export stránok zadajte názvy do tohto poľa, jeden názov na riadok, a zvoľte, či chcete iba súčasnú verziu s informáciou o poslednej úprave alebo aj všetky staršie verzie s históriou úprav.

V druhom prípade môžete tiež použiť odkaz, napr. [[Special:Export/{{Mediawiki:Mainpage}}]] pre stránku {{Mediawiki:Mainpage}}.',
'exportcuronly'   => 'Zahrň iba aktuálnu verziu, nie kompletnú históriu',
'exportnohistory' => '----',
'export-submit'   => 'Export',

# Namespace 8 related
'allmessages'               => 'Všetky systémové správy',
'allmessagesname'           => 'Názov',
'allmessagesdefault'        => 'štandardný text',
'allmessagescurrent'        => 'aktuálny text',
'allmessagestext'           => 'Toto je zoznam všetkých správ dostupných v mennom priestore MediaWiki.',
'allmessagesnotsupportedUI' => "Special:AllMessages na tejto lokalite (site) nepodporuje jazyk pre vaše rozhranie ('''$1''').",
'allmessagesnotsupportedDB' => 'Special:AllMessages nie je podporované, pretože je vypnuté wgUseDatabaseMessages.',
'allmessagesfilter'         => 'Filter názvov správ:',
'allmessagesmodified'       => 'Zobraz iba zmenené',

# Thumbnails
'thumbnail-more'  => 'Zväčšiť',
'missingimage'    => '<b>Chýbajúci obrázok</b><br /><i>$1</i>\n',
'filemissing'     => 'Chýbajúci súbor',
'thumbnail_error' => 'Chyba pri vytváraní náhľadu: $1',

# Special:Import
'import'                     => 'Import stránok',
'importinterwiki'            => 'Transwiki import',
'import-interwiki-text'      => 'Zvoľte wiki a názov stránky, ktorá sa má importovať.
Dátumy revízií a mná redaktorov budú zachované.
Všetky transwiki importy sa zaznamenávajú v [[Special:Log/import|Zázname importov]].',
'import-interwiki-history'   => 'Skopírovať všetky historické revízie tejto stránky',
'import-interwiki-submit'    => 'Importovať',
'import-interwiki-namespace' => 'Presunúť stránky do menného priestoru:',
'importtext'                 => 'Prosím exportujte súbor zo zdrojov wiki použitím nástroja Special:Export, uložte na váš disk a nahrajte tu.',
'importstart'                => 'Importujú sa stránky...',
'import-revision-count'      => '$1 {{PLURAL:$1|revízia|revízie|revízií}}',
'importnopages'              => 'Žiadne stránky pre import.',
'importfailed'               => 'Chyba pri importe: $1',
'importunknownsource'        => 'Neznámy typ zdroja pre import',
'importcantopen'             => 'Nedal sa otvoriť súbor importu',
'importbadinterwiki'         => 'Zlý interwiki odkaz',
'importnotext'               => 'Prázdny alebo žiadny text',
'importsuccess'              => 'Import úspešný!',
'importhistoryconflict'      => 'Existujú konfliktné histórie revízií (možno už bola táto stránka importovaná)',
'importnosources'            => 'Neboli definované žiadne zdroje pre transwiki import a priame nahranie histórie je vypnuté.',
'importnofile'               => 'Nebol nahraný import súbor.',
'importuploaderror'          => 'Nahrávanie importovaného súboru sa nepodarilo; možno súbor presahuje najväčšiu povolenú veľkosť.',

# Import log
'importlogpage'                    => 'Záznam importov',
'importlogpagetext'                => 'Administratívny import stránok vrátane histórie úprav z iných wiki.',
'import-logentry-upload'           => 'importovaný $1 pomocou nahrania súboru',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revízia|revízie|revízií}}',
'import-logentry-interwiki'        => 'Transwiki import $1 úspešný',
'import-logentry-interwiki-detail' => '$1 revízií z $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Moja redaktorská stránka',
'tooltip-pt-anonuserpage'         => 'Stránka redaktora pre ip adresu, ktorú upravujete ako',
'tooltip-pt-mytalk'               => 'Moja diskusná stránka',
'tooltip-pt-anontalk'             => 'Diskusia o úpravách z tejto ip adresy',
'tooltip-pt-preferences'          => 'Moje nastavenia',
'tooltip-pt-watchlist'            => 'Zoznam stránok, na ktorých sledujete zmeny.',
'tooltip-pt-mycontris'            => 'Zoznam mojich príspevkov',
'tooltip-pt-login'                => 'Odporúčame Vám prihlásiť sa, nie je to však povinné.',
'tooltip-pt-anonlogin'            => 'Odporúčame Vám prihlásiť sa, nie je to však povinné.',
'tooltip-pt-logout'               => 'Odhlásenie',
'tooltip-ca-talk'                 => 'Diskusia o obsahu stránky',
'tooltip-ca-edit'                 => 'Môžete upravovať túto stránku. Prosíme, pred uložením použite tlačidlo Zobraziť náhľad.',
'tooltip-ca-addsection'           => 'Pridaj komentár k tejto diskusii.',
'tooltip-ca-viewsource'           => 'Táto stránka je zamknutá. Môžete však vidieť jej zdrojový text.',
'tooltip-ca-history'              => 'Minulé verzie tejto stránky.',
'tooltip-ca-protect'              => 'Zamkni túto stránku',
'tooltip-ca-delete'               => 'Vymaž túto stránku',
'tooltip-ca-undelete'             => 'Obnov úpravy tejtoto stránky až po dobu jeho vymazania',
'tooltip-ca-move'                 => 'Presuň túto stránku',
'tooltip-ca-watch'                => 'Pridať túto stránku do zoznamu sledovaných stránok',
'tooltip-ca-unwatch'              => 'Odstrániť túto stránku zo sledovaných stránok',
'tooltip-search'                  => 'Prehľadávanie tejto wiki',
'tooltip-p-logo'                  => 'Hlavná stránka',
'tooltip-n-mainpage'              => 'Navštíviť Hlavnú stránku',
'tooltip-n-portal'                => 'O projekte, ako môžete prispieť, kde čo nájsť',
'tooltip-n-currentevents'         => 'Aktuálne udalosti a ich pozadie',
'tooltip-n-recentchanges'         => 'Zoznam posledných úprav vo wiki.',
'tooltip-n-randompage'            => 'Zobrazenie náhodnej stránky',
'tooltip-n-help'                  => 'Pozrieť si pomoc.',
'tooltip-n-sitesupport'           => 'Podporte nás',
'tooltip-t-whatlinkshere'         => 'Zoznam všetkých wiki stránok, ktoré sem odkazujú',
'tooltip-t-recentchangeslinked'   => 'Posledné úpravy v stránkach, ktoré odkazujú na túto stránku',
'tooltip-feed-rss'                => 'RSS feed pre túto stránku',
'tooltip-feed-atom'               => 'Atom feed pre túto stránku',
'tooltip-t-contributions'         => 'Pozrieť si zoznam príspevkov od tohto redaktora',
'tooltip-t-emailuser'             => 'Poslať e-mail tomuto redaktorovi',
'tooltip-t-upload'                => 'Nahranie obrázkových alebo multimediálnych súborov',
'tooltip-t-specialpages'          => 'Zoznam všetkých špeciálnych stránok',
'tooltip-ca-nstab-main'           => 'Pozrieť si obsah stránky',
'tooltip-ca-nstab-user'           => 'Pozrieť si stránku redaktora',
'tooltip-ca-nstab-media'          => 'Pozrieť si stránku médií',
'tooltip-ca-nstab-special'        => 'Toto je špeciálna stránka, nemôžete ju upravovať.',
'tooltip-ca-nstab-project'        => 'Pozrieť si stránku projektu',
'tooltip-ca-nstab-image'          => 'Pozrieť si stránku obrázku',
'tooltip-ca-nstab-mediawiki'      => 'Pozrieť si systémovú stránku',
'tooltip-ca-nstab-template'       => 'Pozrieť si šablónu',
'tooltip-ca-nstab-help'           => 'Pozrieť si stránku Pomocníka',
'tooltip-ca-nstab-category'       => 'Pozrieť si stránku s kategóriami',
'tooltip-minoredit'               => 'Označ toto ako drobnú úpravu',
'tooltip-save'                    => 'Uloží vaše úpravy',
'tooltip-preview'                 => 'Náhľad úprav, prosím použite pred uložením!',
'tooltip-diff'                    => 'Ukáž, aké zmeny ste urobili v texte.',
'tooltip-compareselectedversions' => 'Zobraz rozdiely medzi dvoma vybranými verziami tejto stránky.',
'tooltip-watch'                   => 'Pridaj túto stránku k sledovaným.',
'tooltip-recreate'                => 'Znovu vytvoriť stránku napriek tomu, že bola zmazaná',

# Stylesheets
'monobook.css' => '/* úpravou tohto súboru si prispôsobíte skin monobook pre celú wiki */',

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata sú pre tento server vypnuté.',
'nocreativecommons' => 'Creative Commons RDF metadata sú pre tento server vypnuté.',
'notacceptable'     => 'Wiki server nedokáže poskytovať dáta vo formáte, v akom ich váš klient vie čítať.',

# Attribution
'anonymous'        => 'Anonymný redaktor/i {{GRAMMAR:genitív|{{SITENAME}}}}',
'siteuser'         => 'Redaktor {{GRAMMAR:genitív|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Táto stránka bola naposledy upravovaná $2, $1 redaktorom $3.', # $1 date, $2 time, $3 user
'and'              => 'a',
'othercontribs'    => 'Založené na práci redaktora $1.',
'others'           => 'iné',
'siteusers'        => 'Redaktori {{GRAMMAR:genitív|{{SITENAME}}}} $1',
'creditspage'      => 'Autori stránky',
'nocredits'        => 'Pre túto stránku neexistujú žiadne dostupné ocenenia.',

# Spam protection
'spamprotectiontitle'    => 'Filter na ochranu pred spamom',
'spamprotectiontext'     => 'Stránka, ktorú ste chceli uložiť, bola blokovaná filtrom na spam. Pravdepodobne to spôsobil link na externú internetovú lokalitu (site).',
'spamprotectionmatch'    => 'Nasledujúci text aktivoval náš spam filter: $1',
'subcategorycount'       => 'V tejto kategórii {{PLURAL:$1|je jedna podkategória|sú $1 podkategórie|je $1 podkategórií}}.',
'categoryarticlecount'   => 'V tejto kategórii {{PLURAL:$1|je jedna stránka|sú $1 stránky|je $1 stránok}}.',
'category-media-count'   => 'V tejto kategórii {{PLURAL:$1|je jeden súbor|sú $1 súbory|je $1 súborov}}.',
'listingcontinuesabbrev' => ' pokrač.',
'spambot_username'       => 'MediaWiki čistenie spamu',
'spam_reverting'         => 'Revertujem na poslednú verziu, ktorá neobsahuje odkazy na $1',
'spam_blanking'          => 'Všetky revízie obsahovali odkaz na $1, odstraňujem obsah',

# Info page
'infosubtitle'   => 'Informácie o stránke',
'numedits'       => 'Počet úprav (stránka): $1',
'numtalkedits'   => 'Počet úprav (diskusná stránka): $1',
'numwatchers'    => 'Počet zobrazení: $1',
'numauthors'     => 'Počet odlišných autorov (stránka): $1',
'numtalkauthors' => 'Počet odlišných autorov (diskusná stránka): $1',

# Math options
'mw_math_png'    => 'Vždy vykresľuj PNG',
'mw_math_simple' => 'Na jednoduché použi HTML, inak PNG',
'mw_math_html'   => 'Ak sa dá, použi HTML, inak PNG',
'mw_math_source' => 'Ponechaj TeX (pre textové prehliadače)',
'mw_math_modern' => 'Odporúčané pre moderné prehliadače',
'mw_math_mathml' => 'MathML (experimentálne)',

# Patrolling
'markaspatrolleddiff'        => 'Označ ako strážený',
'markaspatrolledtext'        => 'Označ túto stránku ako stráženú',
'markedaspatrolled'          => 'Označené ako strážené',
'markedaspatrolledtext'      => 'Vybraná verzia bola označená na stráženie.',
'rcpatroldisabled'           => 'Stráženie posledných zmien bolo vypnuté',
'rcpatroldisabledtext'       => 'Funkcia stráženia posledných zmien je momentálne vypnutá.',
'markedaspatrollederror'     => 'Nie je možné označiť ako strážený',
'markedaspatrollederrortext' => 'Pre označenie ako strážený je potrebné uviesť revíziu, ktorá sa má označiť ako strážená.',

# Image deletion
'deletedrevision' => 'Zmazať staré verzie $1.',

# Browsing diffs
'previousdiff' => '← Choď na predchádzajúcu verziu',
'nextdiff'     => 'Choď na ďalšiu verziu →',

'imagemaxsize' => 'Obmedz obrázky na popisnej stránke obrázka na:',
'thumbsize'    => 'Veľkosť náhľadu:',
'showbigimage' => 'Stiahnuť tento obrázok vo väčšom rozlíšení ($1x$2, $3 KB)',

'newimages'    => 'Galéria nových obrázkov',
'showhidebots' => '($1 botov)',
'noimages'     => 'Nič na zobrazenie.',

# Labels for User: and Title: on Special:Log pages
'specialloguserlabel'  => 'Redaktor:',
'speciallogtitlelabel' => 'Názov:',

'passwordtooshort' => 'Vaše heslo je príliš krátke. Musí mať dĺžku aspoň $1 znakov.',

# Media Warning
'mediawarning' => "'''Upozornenie''': Tento súbor môže obsahovať nebezpečný programový kód, po spustení ktorého by bol váš systém kompromitovaný.
<hr />",

'fileinfo' => '$1KB, MIME : <code>$2</code>',

# Metadata
'metadata'          => 'Metadáta',
'metadata-help'     => 'Tento súbor obsahuje ďalšie informácie, pravdepodobne pochádzajúce z digitálneho fotoaparátu či skenera ktorý ho vytvoril alebo digitalizoval. Ak bol súbor zmenený, niektoré podrobnosti sa nemusia plne zhodovať so zmeneným obrázkom.',
'metadata-expand'   => 'Zobraz detaily EXIF',
'metadata-collapse' => 'Skry detaily EXIF',
'metadata-fields'   => 'Polia EXIF metadát uvedených v tejto správe sa zobrazia na stránke obrázka vtedy, keď je tabuľka metadát zbalená. Ostatné sa štandardne nezobrazia.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Šírka',
'exif-imagelength'                 => 'Výška',
'exif-bitspersample'               => 'Bitov na zložku',
'exif-compression'                 => 'Kompresná schéma',
'exif-photometricinterpretation'   => 'Pixelové zloženie',
'exif-orientation'                 => 'Orientácia',
'exif-samplesperpixel'             => 'Počet zložiek',
'exif-planarconfiguration'         => 'Rozloženie dát',
'exif-ycbcrsubsampling'            => 'Pomer podvzorkovania Y ku C',
'exif-ycbcrpositioning'            => 'Poloha Y a C',
'exif-xresolution'                 => 'Horizontálne rozlíšenie',
'exif-yresolution'                 => 'Vertikálne rozlíšenie',
'exif-resolutionunit'              => 'Jednotky horizontálneho a verikálneho rozlíšenia',
'exif-stripoffsets'                => 'Umiestnenie obrazových dát',
'exif-rowsperstrip'                => 'Počet riadkov na pás',
'exif-stripbytecounts'             => 'Bajtov na komprimovaný prúžok',
'exif-jpeginterchangeformat'       => 'Offset k JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytov JPEG dát',
'exif-transferfunction'            => 'Prenosová funkcia',
'exif-whitepoint'                  => 'Chromaticita bieleho bodu',
'exif-primarychromaticities'       => 'Chromaticity primárností',
'exif-ycbcrcoefficients'           => 'Koeficienty transformačnej matice farebného priestoru',
'exif-referenceblackwhite'         => 'Dvojica bielych a čiernych referenčných hodnôt',
'exif-datetime'                    => 'Dátum a čas zmeny súboru',
'exif-imagedescription'            => 'Názov obrázka',
'exif-make'                        => 'Výrobca aparátu',
'exif-model'                       => 'Model aparátu',
'exif-software'                    => 'Použitý softvér',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Držiteľ autorských práv',
'exif-exifversion'                 => 'Verzia exif tagu',
'exif-flashpixversion'             => 'Podporovaná verzia Flashpix',
'exif-colorspace'                  => 'Farebný priestor',
'exif-componentsconfiguration'     => 'Význam jednotlivých zložiek',
'exif-compressedbitsperpixel'      => 'Kompresný režim obrázka',
'exif-pixelydimension'             => 'platná šírka obrázka',
'exif-pixelxdimension'             => 'Platná vyška obrázka',
'exif-makernote'                   => 'Poznámky výrobcu',
'exif-usercomment'                 => 'Komentáre používateľa',
'exif-relatedsoundfile'            => 'Súvisiaci zvukový súbor',
'exif-datetimeoriginal'            => 'Dátum a čas vytvorenia dát',
'exif-datetimedigitized'           => 'Dátum a čas digitalizácie',
'exif-subsectime'                  => 'Subsekundy DateTime',
'exif-subsectimeoriginal'          => 'Zlomky sekundy DateTimeOriginal',
'exif-subsectimedigitized'         => 'Zlomky sekundy DateTimeDigitized',
'exif-exposuretime'                => 'Expozičný čas',
'exif-exposuretime-format'         => '$1 sekundy ($2)',
'exif-fnumber'                     => 'Číslo F',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Expozičný program',
'exif-spectralsensitivity'         => 'Spektrálna citlivosť',
'exif-isospeedratings'             => 'Rýchlostné ohodnotenie ISO',
'exif-oecf'                        => 'Optoelektronický konverzný činiteľ',
'exif-shutterspeedvalue'           => 'Rýchlosť uzávierky',
'exif-aperturevalue'               => 'Clona',
'exif-brightnessvalue'             => 'Jas',
'exif-exposurebiasvalue'           => 'Expozičné skreslenie',
'exif-maxaperturevalue'            => 'Maximálna krajinná clona',
'exif-subjectdistance'             => 'Vzdialenosť subjektu',
'exif-meteringmode'                => 'Merací režim',
'exif-lightsource'                 => 'Svetelný zdroj',
'exif-flash'                       => 'Blesk',
'exif-focallength'                 => 'Ohnisková vzdialenosť šošoviek',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Oblasť subjektu',
'exif-flashenergy'                 => 'Energia blesku',
'exif-spatialfrequencyresponse'    => 'Priestorová frekvenčná odozva',
'exif-focalplanexresolution'       => 'Horizontálne rozlíšenie ohniskovej roviny',
'exif-focalplaneyresolution'       => 'Vertikálne rozlíšenie ohniskovej roviny',
'exif-focalplaneresolutionunit'    => 'Jednotka rozlíšenia v ohniskovej rovine',
'exif-subjectlocation'             => 'Umiestnenie subjektu',
'exif-exposureindex'               => 'Expozičný index',
'exif-sensingmethod'               => 'Snímacia metóda',
'exif-filesource'                  => 'Zdroj súboru',
'exif-scenetype'                   => 'Typ scény',
'exif-cfapattern'                  => 'Vzor CFA',
'exif-customrendered'              => 'Ručné spracovanie obrazu',
'exif-exposuremode'                => 'Expozičný režim',
'exif-whitebalance'                => 'Vyváženie bielej',
'exif-digitalzoomratio'            => 'Rozsah digitálneho zoomu',
'exif-focallengthin35mmfilm'       => 'Ohnisková vzdialenosť 35 mm filmu',
'exif-scenecapturetype'            => 'Typ zachytenia scény',
'exif-gaincontrol'                 => 'Riadenie scény',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Sýtosť',
'exif-sharpness'                   => 'Ostrosť',
'exif-devicesettingdescription'    => 'Opis nastavení zariadenia',
'exif-subjectdistancerange'        => 'Rozsah vzdialenosti subjektu',
'exif-imageuniqueid'               => 'Jedinečný ID obrázka',
'exif-gpsversionid'                => 'Verzia GPS tagu',
'exif-gpslatituderef'              => 'Severná alebo južná šírka',
'exif-gpslatitude'                 => 'Zemepisná šírka',
'exif-gpslongituderef'             => 'Západná alebo východná dĺžka',
'exif-gpslongitude'                => 'Zemepisná dĺžka',
'exif-gpsaltituderef'              => 'Referencia výšky',
'exif-gpsaltitude'                 => 'Výška',
'exif-gpstimestamp'                => 'Čas GPS (atómové hodiny)',
'exif-gpssatellites'               => 'Satelity použité pri meraní',
'exif-gpsstatus'                   => 'Stav prijímača',
'exif-gpsmeasuremode'              => 'Režim merania',
'exif-gpsdop'                      => 'Presnosť merania',
'exif-gpsspeedref'                 => 'Rýchlostná jednotka',
'exif-gpsspeed'                    => 'Rýchlosť prijímača GPS',
'exif-gpstrackref'                 => 'Referencia pre smer pohybu',
'exif-gpstrack'                    => 'Smer pohybu',
'exif-gpsimgdirectionref'          => 'Referencia pre smer obrázka',
'exif-gpsimgdirection'             => 'Smer obrázka',
'exif-gpsmapdatum'                 => 'Použité údaje geodetického prieskumu',
'exif-gpsdestlatituderef'          => 'Referencia zemepisnej šírky cieľa',
'exif-gpsdestlatitude'             => 'Zemepisná šírka cieľa',
'exif-gpsdestlongituderef'         => 'Referencia zemepisnej dĺžky cieľa',
'exif-gpsdestlongitude'            => 'Zemepisná dĺžka cieľa',
'exif-gpsdestbearingref'           => 'Referencia polohy cieľa',
'exif-gpsdestbearing'              => 'Smer k cieľu',
'exif-gpsdestdistanceref'          => 'Referencia vzdialenosti cieľa',
'exif-gpsdestdistance'             => 'Vzdialenosť k cieľu',
'exif-gpsprocessingmethod'         => 'Názov GPS metódy spracovania',
'exif-gpsareainformation'          => 'Názov GPS oblasti',
'exif-gpsdatestamp'                => 'Dátum GPS',
'exif-gpsdifferential'             => 'Diferenciálna korekcia GPS',

# EXIF attributes
'exif-compression-1' => 'Bez kompresie',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normálna', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Horizontálne prevrátená', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Otočená o 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikálne prevrátená', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Otočená o 90° proti smeru hodinových ručičiek a vertikálne prevrátená', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Otočená o 90° v smere hodinových ručičiek', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Otočená o 90° v smere hodinových ručičiek a vertikálne prevrátená', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Otočená o 90° proti smeru hodinových ručičiek', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'masívny formát',
'exif-planarconfiguration-2' => 'rovinný formát',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'neexistuje',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Nedefinovaný',
'exif-exposureprogram-1' => 'Ručný',
'exif-exposureprogram-2' => 'Normálny program',
'exif-exposureprogram-3' => 'Priorita clony',
'exif-exposureprogram-4' => 'Priorita uzávierky',
'exif-exposureprogram-5' => 'Tvorivý program (skreslený smerom k hĺbke poľa)',
'exif-exposureprogram-6' => 'Akčný program (skreslený smerom k rýchlosti uzávierky)',
'exif-exposureprogram-7' => 'Režim portrét (pre detailné zábery s nezaostreným pozadím)',
'exif-exposureprogram-8' => 'Režim krajinka (pre fotografie krajiny so zaostreným pozadím)',

'exif-subjectdistance-value' => '$1 metrov',

'exif-meteringmode-0'   => 'Neznámy',
'exif-meteringmode-1'   => 'Priemer',
'exif-meteringmode-2'   => 'Vážený priemer',
'exif-meteringmode-3'   => 'Bod',
'exif-meteringmode-4'   => 'Viacero bodov',
'exif-meteringmode-5'   => 'Vzor',
'exif-meteringmode-6'   => 'Čiastočný',
'exif-meteringmode-255' => 'Iný',

'exif-lightsource-0'   => 'Neznámy',
'exif-lightsource-1'   => 'Denné svetlo',
'exif-lightsource-2'   => 'Fluorescenčný',
'exif-lightsource-3'   => 'Volfrám (inkandescentné svetlo)',
'exif-lightsource-4'   => 'Blesk',
'exif-lightsource-9'   => 'Dobré počasie',
'exif-lightsource-10'  => 'Hmlisté počasie',
'exif-lightsource-11'  => 'Tieň',
'exif-lightsource-12'  => 'Fluorescenčné denné svetlo (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Flourescenčná denná biela (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fuorescenčná chladná biela (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescenčná biela (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Štandardné svetlo A',
'exif-lightsource-18'  => 'Štandardné svetlo B',
'exif-lightsource-19'  => 'Štandardné svetlo C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'ISO štúdiový volfrám',
'exif-lightsource-255' => 'Iný svetelný zdroj',

'exif-focalplaneresolutionunit-2' => 'palcov',

'exif-sensingmethod-1' => 'Nedefinovaná',
'exif-sensingmethod-2' => 'Jednočipový farebný snímač oblasti',
'exif-sensingmethod-3' => 'Dvojčipový farebný snímač oblasti',
'exif-sensingmethod-4' => 'Trojčipový farebný snímač oblasti',
'exif-sensingmethod-5' => 'Sekvenčný farebný snímač oblasti',
'exif-sensingmethod-7' => 'Trilineárny snímač',
'exif-sensingmethod-8' => 'Sekvenčný farebný lineárny snímač',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'Priamo odfotený obrázok',

'exif-customrendered-0' => 'Normálne spracovanie',
'exif-customrendered-1' => 'Ručné spracovanie',

'exif-exposuremode-0' => 'Automatická expozícia',
'exif-exposuremode-1' => 'Ručná expozícia',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'Automatické vyváženie bielej',
'exif-whitebalance-1' => 'Ručné vyváženie bielej',

'exif-scenecapturetype-0' => 'Štandardný',
'exif-scenecapturetype-1' => 'Krajinka',
'exif-scenecapturetype-2' => 'Portrét',
'exif-scenecapturetype-3' => 'Nočná scéna',

'exif-gaincontrol-0' => 'Žiadne',
'exif-gaincontrol-1' => 'Slabé zosilnenie nahor',
'exif-gaincontrol-2' => 'Silné zosilnenie nahor',
'exif-gaincontrol-3' => 'Slabé zosilnenie nadol',
'exif-gaincontrol-4' => 'Silné zosilnenie nadol',

'exif-contrast-0' => 'Normálny',
'exif-contrast-1' => 'Mäkký',
'exif-contrast-2' => 'Tvrdý',

'exif-saturation-0' => 'Normálna',
'exif-saturation-1' => 'Nízka sýtosť',
'exif-saturation-2' => 'Výsoká sýtosť',

'exif-sharpness-0' => 'Normálna',
'exif-sharpness-1' => 'Mäkká',
'exif-sharpness-2' => 'Tvrdá',

'exif-subjectdistancerange-0' => 'Neznámy',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Blízky pohľad',
'exif-subjectdistancerange-3' => 'Ďaleký pohľad',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Severná šírka',
'exif-gpslatitude-s' => 'Južná šírka',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Východná dĺžka',
'exif-gpslongitude-w' => 'Západná dĺžka',

'exif-gpsstatus-a' => 'Prebieha meranie',
'exif-gpsstatus-v' => 'Interoperabilita merania',

'exif-gpsmeasuremode-2' => '2-rozmerné meranie',
'exif-gpsmeasuremode-3' => '3-rozmerné meranie',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometrov za hodinu',
'exif-gpsspeed-m' => 'Míľ za hodinu',
'exif-gpsspeed-n' => 'Uzlov',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Skutočný smer',
'exif-gpsdirection-m' => 'Magnetický smer',

# External editor support
'edit-externally'      => 'Uprav tento súbor pomocou externého programu',
'edit-externally-help' => 'Viac informácií poskytnú inštrukcie pre nastavenie [http://meta.wikimedia.org/wiki/Help:External_editors externého editora].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'všetky',
'imagelistall'     => 'všetky',
'watchlistall1'    => 'všetky',
'watchlistall2'    => 'všetky',
'namespacesall'    => 'všetky',

# E-mail address confirmation
'confirmemail'            => 'Potvrdiť e-mailovú adresu',
'confirmemail_noemail'    => 'Nenastavili ste platnú emailovú adresu vo svojich [[Special:Preferences|Nastaveniach]].',
'confirmemail_text'       => 'Táto wiki vyžaduje, aby ste potvrdili platnosť Vašej e-mailovej adresy
pred používaním e-mailových funkcií. Kliknite na tlačidlo dole, aby sa na Vašu adresu odoslal potvrdzovací
e-mail. V e-maili bude aj odkaz obsahujúci kód; načítajte odkaz
do Vášho prehliadača pre potvrdenie, že Vaša e-mailová adresa je platná.',
'confirmemail_send'       => 'Odoslať potvrdzovací kód',
'confirmemail_sent'       => 'Potvrdzovací e-mail odoslaný.',
'confirmemail_sendfailed' => 'Nebolo možné odoslať potvrdzovací e-mail. Skontrolujte neplatné znaky v adrese.

Program, ktorý odosielal poštu vrátil: $1',
'confirmemail_invalid'    => 'Neplatný potvrdzovací kód. Kód možno vypršal.',
'confirmemail_needlogin'  => 'Musíte sa $1 na potvrdenie Vašej emailovaj adresy.',
'confirmemail_success'    => 'Vaša e-mailová adresa bola potvrdená. Môžete sa prihlásiť a využívať wiki.',
'confirmemail_loggedin'   => 'Vaša e-mailová adresa bola potvrdená.',
'confirmemail_error'      => 'Niečo sa pokazilo pri ukladaní vášho potvrdenia.',
'confirmemail_subject'    => '{{SITENAME}} - potvrdenie e-mailovej adresy',
'confirmemail_body'       => 'Niekto, pravdepodobne vy z IP adresy $1, zaregistroval účet
"$2" s touto e-mailovou adresou na {{GRAMMAR:lokál|{{SITENAME}}}}.

Pre potvrdenie, že tento účet skutočne patrí Vám a pre aktivovanie
e-mailových funkcií na {{GRAMMAR:lokál|{{SITENAME}}}}, otvorte tento odkaz vo vašom prehliadači:

$3

Ak ste to *neboli* Vy, neotvárajte odkaz. Tento potvrdzovací kód
vyprší o $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Skúste presné vyhľadávanie',
'searchfulltext' => 'Fulltextové vyhľadávanie',
'createarticle'  => 'Vytvoriť stránku',

# Scary transclusion
'scarytranscludedisabled' => '[Transklúzia interwiki je vypnutá]',
'scarytranscludefailed'   => '[Nepodarilo sa priniesť šablónu pre $1; prepáčte]',
'scarytranscludetoolong'  => '[URL je príliš dlhé; prepáčte]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackback pre túto stránku:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Zmazať])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback úspešne zmazaný.',

# Delete conflict
'deletedwhileediting' => 'Varovanie: Táto stránka bola zmazaná potom, ako ste začali s úpravami!',
'confirmrecreate'     => "Redaktor [[User:$1|$1]] ([[User talk:$1|diskusia]]) zmazal túto stránku potom, ako ste ho začal upravovať s odôvodnením:
: ''$2''
Prosím potvrďte, že ho chcete skutočne znovu vytvoriť.",
'recreate'            => 'Znova vytvoriť',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Presmerovanie na [[$1]]...',

# action=purge
'confirm_purge'        => 'Vyčistiť cache pamäť tejto stránky?

$1',
'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => 'Máte nové správy na $1',

'searchcontaining' => "Hľadaj stránky obsahujúce ''$1''.",
'searchnamed'      => "Hľadaj stránky s názvom ''$1''.",
'articletitles'    => "Stránky začínajúce na ''$1''",
'hideresults'      => 'Skry výsledky',

# DISPLAYTITLE
'displaytitle' => '(Odkazujte na túto stránku ako [[$1]])',

'loginlanguagelabel' => 'Jazyk: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; predošlá stránka',
'imgmultipagenext' => 'ďalšia stránka &rarr;',
'imgmultigo'       => 'Spustiť',
'imgmultigotopre'  => 'Choď na stránku',

# Table pager
'ascending_abbrev'         => 'vzostupne',
'descending_abbrev'        => 'zostupne',
'table_pager_next'         => 'Nasledujúca stránka',
'table_pager_prev'         => 'Predošlá stránka',
'table_pager_first'        => 'Prvá stránka',
'table_pager_last'         => 'Posledná stránka',
'table_pager_limit'        => 'Zobraz $1 položiek na stránku',
'table_pager_limit_submit' => 'Spusti',
'table_pager_empty'        => 'Bez výsledkov',

# Auto-summaries
'autosumm-blank'   => 'Odstraňujem obsah stránky',
'autosumm-replace' => "Nahrádzam stránku textom '$1'",
'autoredircomment' => 'Presmerovanie na [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Nová stránka: $1',

);

?>
