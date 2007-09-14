<?php
/** Czech (česky)
 *
 * @addtogroup Language
 */
$fallback8bitEncoding = 'cp1250';

$namespaceNames = array(
	NS_MEDIA              => 'Média',
	NS_SPECIAL            => 'Speciální',
	NS_MAIN               => '',
	NS_TALK               => 'Diskuse',
	NS_USER               => 'Uživatel',
	NS_USER_TALK          => 'Uživatel_diskuse',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK	      => '$1_diskuse',
	NS_IMAGE              => 'Soubor',
	NS_IMAGE_TALK         => 'Soubor_diskuse',
	NS_MEDIAWIKI          => 'MediaWiki',
	NS_MEDIAWIKI_TALK     => 'MediaWiki_diskuse',
	NS_TEMPLATE           => 'Šablona',
	NS_TEMPLATE_TALK      => 'Šablona_diskuse',
	NS_HELP               => 'Nápověda',
	NS_HELP_TALK          => 'Nápověda_diskuse',
	NS_CATEGORY           => 'Kategorie',
	NS_CATEGORY_TALK      => 'Kategorie_diskuse',
);

/**
 * Skin names. If any key is not specified, the English one will be used.
 */
 $skinNames = array(
	'standard'            => 'Standard',
	'nostalgia'           => 'Nostalgie',
	'cologneblue'         => 'Kolínská modř',
	'chick'               => 'Kuře'
);

$datePreferences = false;

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. n. Y',
	'dmy both' => 'H:i, j. n. Y',
);

/**
 * Hledání knihy podle ISBN
 * Default list of book sources
 */
$bookstoreList = array(
    'Národní knihovna'			=> 'http://sigma.nkp.cz/F/?func=find-a&find_code=ISN&request=$1',
	'Státní technická knihovna' => 'http://www.stk.cz/cgi-bin/dflex/CZE/STK/BROWSE?A=01&V=$1',
	'inherit' => true,
);

/**
 * Magic words
 * Customisable syntax for wikitext and elsewhere
 *
 * Note to translators:
 *   Please include the English words as synonyms.  This allows people
 *   from other wikis to contribute more easily.
 *
 * This array can be modified at runtime with the LanguageGetMagic hook
 */
 $magicWords = array(
##   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT',        '#PŘESMĚRUJ'         ),
	'notoc'                  => array( 0,    '__NOTOC__',        '__BEZOBSAHU__'      ),
	'nogallery'              => array( 0,    '__NOGALLERY__',    '__BEZGALERIE__'     ),
	'forcetoc'               => array( 0,    '__FORCETOC__',     '__VŽDYOBSAH__'      ),
	'toc'                    => array( 0,    '__TOC__',          '__OBSAH__'          ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__BEZEDITOVATČÁST__' ),
	'currentmonth'           => array( 1,    'CURRENTMONTH',     'AKTUÁLNÍMĚSÍC'      ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'AKTUÁLNÍMĚSÍCJMÉNO' ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'AKTUÁLNÍMĚSÍCGEN' ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'AKTUÁLNÍMĚSÍCZKR' ),
	'currentday'             => array( 1,    'CURRENTDAY',       'AKTUÁLNÍDEN'        ),
	'currentday2'            => array( 1,    'CURRENTDAY2',      'AKTUÁLNÍDEN2'       ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME',   'AKTUÁLNÍDENJMÉNO'   ),
	'currentyear'            => array( 1,    'CURRENTYEAR',      'AKTUÁLNÍROK'        ),
	'currenttime'            => array( 1,    'CURRENTTIME',      'AKTUÁLNÍČAS'        ),
	'currenthour'            => array( 1,    'CURRENTHOUR',      'AKTUÁLNÍHODINA'     ),
	'localmonth'             => array( 1,    'LOCALMONTH',       'MÍSTNÍMĚSÍC'        ),
	'localmonthname'         => array( 1,    'LOCALMONTHNAME',   'MÍSTNÍMĚSÍCJMÉNO'   ),
	'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN','MÍSTNÍMĚSÍCGEN'     ),
	'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV', 'MÍSTNÍMĚSÍCZKR'     ),
	'localday'               => array( 1,    'LOCALDAY',         'MÍSTNÍDEN'          ),
	'localday2'              => array( 1,    'LOCALDAY2',        'MÍSTNÍDEN2'         ),
	'localdayname'           => array( 1,    'LOCALDAYNAME',     'MÍSTNÍDENJMÉNO'     ),
	'localyear'              => array( 1,    'LOCALYEAR',        'MÍSTNÍROK'          ),
	'localtime'              => array( 1,    'LOCALTIME',        'MÍSTNÍČAS'          ),
	'localhour'              => array( 1,    'LOCALHOUR',        'MÍSTNÍHODINA'       ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES',    'POČETSTRAN'         ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'POČETČLÁNKŮ'        ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES',    'POČETSOUBORŮ'       ),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS',    'POČETUŽIVATELŮ'     ),
	'pagename'               => array( 1,    'PAGENAME',         'NÁZEVSTRANY'        ),
	'pagenamee'              => array( 1,    'PAGENAMEE',        'NÁZEVSTRANYE'       ),
	'namespace'              => array( 1,    'NAMESPACE',        'JMENNÝPROSTOR'      ),
	'namespacee'             => array( 1,    'NAMESPACEE',       'JMENNÝPROSTORE'     ),
	'talkspace'              => array( 1,    'TALKSPACE',        'DISKUSNÍPROSTOR'    ),
	'talkspacee'             => array( 1,    'TALKSPACEE',       'DISKUSNÍPROSTORE'   ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ČLÁNEKPROSTOR' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ČLÁNEKPROSTORE' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME',     'PLNÝNÁZEVSTRANY'    ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE',    'PLNÝNÁZEVSTRANYE'   ),
	'subpagename'            => array( 1,    'SUBPAGENAME',      'NÁZEVPODSTRANY'     ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE',     'NÁZEVPODSTRANYE'    ),
	'basepagename'           => array( 1,    'BASEPAGENAME',     'NÁZEVNADSTRANY'     ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE',    'NÁZEVNADSTRANYE'    ),
	'talkpagename'           => array( 1,    'TALKPAGENAME',     'NÁZEVDISKUSE'       ),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE',    'NÁZEVDISKUSEE'      ),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'NÁZEVČLÁNKU' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'NÁZEVČLÁNKUE' ),
	'subst'                  => array( 0,    'SUBST:',           'VLOŽIT:'            ),
	'msgnw'                  => array( 0,    'MSGNW:',           'VLOŽITNW:'          ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'náhled'           ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'náhled=$1'  ),
	'img_right'              => array( 1,    'right',            'vpravo'             ),
	'img_left'               => array( 1,    'left',             'vlevo'              ),
	'img_none'               => array( 1,    'none',             'žádné'              ),
	'img_center'             => array( 1,    'center', 'centre', 'střed'              ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'rám'     ),
	'img_page'               => array( 1,    'page=$1', 'page $1', 'strana=$1', 'strana $1'      ),
	'sitename'               => array( 1,    'SITENAME',         'NÁZEVSERVERU'       ),
	'localurl'               => array( 0,    'LOCALURL:',        'MÍSTNÍURL:'         ),
	'localurle'              => array( 0,    'LOCALURLE:',       'MÍSTNÍURLE:'        ),
	'servername'             => array( 0,    'SERVERNAME',       'JMÉNOSERVERU'       ),
	'grammar'                => array( 0,    'GRAMMAR:',         'SKLOŇUJ:'           ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__BEZKONVERZENADPISU__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__BEZKONVERZEOBSAHU__'),
	'currentweek'            => array( 1,    'CURRENTWEEK',      'AKTUÁLNÍTÝDEN'      ),
	'currentdow'             => array( 1,    'CURRENTDOW',       'AKTUÁLNÍDENTÝDNE'   ),
	'localweek'              => array( 1,    'LOCALWEEK',        'MÍSTNÍTÝDEN'        ),
	'localdow'               => array( 1,    'LOCALDOW',         'MÍSTNÍDENTÝDNE'     ),
	'revisionid'             => array( 1,    'REVISIONID',       'IDREVIZE'           ),
	'revisionday'            => array( 1,    'REVISIONDAY',      'DENREVIZE'          ),
	'revisionday2'           => array( 1,    'REVISIONDAY2',     'DENREVIZE2'         ),
	'revisionmonth'          => array( 1,    'REVISIONMONTH',    'MĚSÍCREVIZE'        ),
	'revisionyear'           => array( 1,    'REVISIONYEAR',     'ROKREVIZE'          ),
	'revisiontimestamp'      => array( 1,    'REVISIONTIMESTAMP','KÓDČASUREVIZE'      ),
	'plural'                 => array( 0,    'PLURAL:',          'PLURÁL:'            ),
	'fullurl'                => array( 0,    'FULLURL:',         'PLNÉURL:'           ),
	'fullurle'               => array( 0,    'FULLURLE:',        'PLNÉURLE:'          ),
	'lcfirst'                => array( 0,    'LCFIRST:',         'PRVNÍMALÉ:'         ),
	'ucfirst'                => array( 0,    'UCFIRST:',         'PRVNÍVELKÉ:'        ),
	'lc'                     => array( 0,    'LC:',              'MALÁ:'              ),
	'uc'                     => array( 0,    'UC:',              'VELKÁ:'             ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE',     'ZOBRAZOVANÝNADPIS'  ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__LINKPŘIDATKOMENTÁŘ__' ),
	'currentversion'         => array( 1,    'CURRENTVERSION',   'VERZESOFTWARE'      ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP', 'AKTUÁLNÍKÓDČASU'    ),
	'localtimestamp'         => array( 1,    'LOCALTIMESTAMP',   'MÍSTNÍKÓDČASU'      ),
	'language'               => array( 0,    '#LANGUAGE:',       '#JAZYK:'            ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG', 'JAZYKOBSAHU' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:', 'STRÁNEKVEJMENNÉMPROSTORU:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS',   'POČETSPRÁVCŮ'       ),
	'formatnum'              => array( 0,    'FORMATNUM',        'FORMÁTUJČÍSLO'      ),
	'padleft'                => array( 0,    'PADLEFT',          'ZAROVNATVLEVO'      ),
	'padright'               => array( 0,    'PADRIGHT',         'ZAROVNATVPRAVO'     ),
	'special'                => array( 0,    'special', 'speciální'              ),
	'defaultsort'            => array( 1,    'DEFAULTSORT:', 'KLÍČŘAZENÍ:'            ),

);

/**
 * Regular expression matching the "link trail", e.g. "ed" in [[Toast]]ed, as 
 * the first group, and the remainder of the string as the second group.
 */
# Písmena, která se mají objevit jako část odkazu ve formě '[[jazyk]]y' atd:
$linkTrail = '/^([a-záčďéěíňóřšťúůýž]+)(.*)$/sDu';


$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Podtrhnout odkazy',
'tog-highlightbroken'         => 'Formátovat odkazy na neexistující články <a href="#" class="new">takto</a> (alternativa: takto<a href="#" class="broken">?</a>).',
'tog-justify'                 => 'Zarovnat odstavce do bloku',
'tog-hideminor'               => 'Skrýt malé editace v posledních změnách',
'tog-extendwatchlist'         => 'Rozšířený seznam sledovaných stránek',
'tog-usenewrc'                => 'Zdokonalené poslední změny (JavaScript)',
'tog-numberheadings'          => 'Automaticky číslovat nadpisy',
'tog-showtoolbar'             => 'Ukázat lištu s nástroji při editaci',
'tog-editondblclick'          => 'Editovat dvojklikem (JavaScript)',
'tog-editsection'             => 'Zapnout možnost editace části článku pomocí odkazu [editovat]',
'tog-editsectiononrightclick' => 'Zapnout možnost editace části článku pomocí kliknutí pravým tlačítkem na nadpisy v článku (JavaScript)',
'tog-showtoc'                 => 'Ukázat obsah článku (pokud má článek více než tři nadpisy)',
'tog-rememberpassword'        => 'Pamatovat si mé heslo mezi návštěvami',
'tog-editwidth'               => 'Roztáhnout editační okno na celou šířku',
'tog-watchcreations'          => 'Přidávat mnou založené stránky ke sledovaným',
'tog-watchdefault'            => 'Přidávat mnou editované stránky ke sledovaným',
'tog-watchmoves'              => 'Přidávat přesouvané stránky mezi sledované',
'tog-watchdeletion'           => 'Přidávat stránky, které smažu, mezi sledované',
'tog-minordefault'            => 'Označit editaci implicitně jako malá editace',
'tog-previewontop'            => 'Zobrazovat náhled před editačním oknem (ne za ním)',
'tog-previewonfirst'          => 'Zobrazit při první editaci náhled',
'tog-nocache'                 => 'Nepoužívat cache',
'tog-enotifwatchlistpages'    => 'Poslat e-mail při změně sledované stránky',
'tog-enotifusertalkpages'     => 'Poslat e-mail při změně mé diskusní stránky',
'tog-enotifminoredits'        => 'Poslat e-mail i pro menší editace (které jinak nezpůsobují odeslání e-mailu)',
'tog-enotifrevealaddr'        => 'Prozradit mou e-mailovou adresu v upozorňujících e-mailech',
'tog-shownumberswatching'     => 'Zobrazovat počet sledujících uživatelů',
'tog-fancysig'                => 'Neupravovat podpis (nevkládat automaticky odkaz)',
'tog-externaleditor'          => 'Implicitně používat externí editor',
'tog-externaldiff'            => 'Implicitně používat externí porovnávací program',
'tog-showjumplinks'           => 'Používat odkazy „skočit na“ pro vyšší přístupnost',
'tog-uselivepreview'          => 'Používat rychlý náhled (JavaScript) (Experimentální)',
'tog-forceeditsummary'        => 'Upozornit, když nevyplním shrnutí editace',
'tog-watchlisthideown'        => 'Na seznamu sledovaných stránek skrýt moje editace',
'tog-watchlisthidebots'       => 'Na seznamu sledovaných stránek skrýt editace botů',
'tog-watchlisthideminor'      => 'Skrýt malé editace z mých sledovaných stránek',
'tog-nolangconversion'        => 'Vypnout konverzi variant',
'tog-ccmeonemails'            => 'Zasílat mi kopie e-mailů, které pošlu jiným uživatelům',
'tog-diffonly'                => 'Nezobrazovat obsah stránky pod rozdílem verzí',

'underline-always'  => 'Vždy',
'underline-never'   => 'Nikdy',
'underline-default' => 'Podle prohlížeče',

'skinpreview' => '(Náhled)',

# Dates
'sunday'        => 'neděle',
'monday'        => 'pondělí',
'tuesday'       => 'úterý',
'wednesday'     => 'středa',
'thursday'      => 'čtvrtek',
'friday'        => 'pátek',
'saturday'      => 'sobota',
'sun'           => 'ne',
'mon'           => 'po',
'tue'           => 'út',
'wed'           => 'st',
'thu'           => 'čt',
'fri'           => 'pá',
'sat'           => 'so',
'january'       => 'leden',
'february'      => 'únor',
'march'         => 'březen',
'april'         => 'duben',
'may_long'      => 'květen',
'june'          => 'červen',
'july'          => 'červenec',
'august'        => 'srpen',
'september'     => 'září',
'october'       => 'říjen',
'november'      => 'listopad',
'december'      => 'prosinec',
'january-gen'   => 'ledna',
'february-gen'  => 'února',
'march-gen'     => 'března',
'april-gen'     => 'dubna',
'may-gen'       => 'května',
'june-gen'      => 'června',
'july-gen'      => 'července',
'august-gen'    => 'srpna',
'september-gen' => 'září',
'october-gen'   => 'října',
'november-gen'  => 'listopadu',
'december-gen'  => 'prosince',
'jan'           => '1.',
'feb'           => '2.',
'mar'           => '3.',
'apr'           => '4.',
'may'           => '5.',
'jun'           => '6.',
'jul'           => '7.',
'aug'           => '8.',
'sep'           => '9.',
'oct'           => '10.',
'nov'           => '11.',
'dec'           => '12.',

# Bits of text used by many pages
'categories'            => 'Kategorie',
'pagecategories'        => 'Kategorie',
'category_header'       => 'Články v kategorii „$1“',
'subcategories'         => 'Podkategorie',
'category-media-header' => 'Soubory v kategorii „$1“',
'category-empty'        => "''Tato kategorie neobsahuje žádné články či soubory.''",

'mainpagetext'      => "<big>'''MediaWiki bylo úspěšně nainstalováno.'''</big>",
'mainpagedocfooter' => '[http://meta.wikimedia.org/wiki/Help:Contents Průvodce uživatele] Vám napoví jak používat MediaWiki.

== Začínáme ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Nastavená konfigurace]
* [http://www.mediawiki.org/wiki/Manual:FAQ Často kladené otázky o MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce E-mailová konference oznámení MediaWiki]',

'about'          => 'Úvod',
'article'        => 'Obsahová stránka',
'newwindow'      => '(otevře se v novém okně)',
'cancel'         => 'Storno',
'qbfind'         => 'Hledání',
'qbbrowse'       => 'Listování',
'qbedit'         => 'Editování',
'qbpageoptions'  => 'Tato stránka',
'qbpageinfo'     => 'Kontext',
'qbmyoptions'    => 'Moje volby',
'qbspecialpages' => 'Speciální stránky',
'moredotdotdot'  => 'Další…',
'mypage'         => 'Moje stránka',
'mytalk'         => 'Moje diskuse',
'anontalk'       => 'Diskuse k této IP adrese',
'navigation'     => 'Navigace',

'errorpagetitle'    => 'Chyba',
'returnto'          => 'Návrat na stránku „$1“.',
'tagline'           => 'Z {{grammar:2sg|{{SITENAME}}}}',
'help'              => 'Nápověda',
'search'            => 'Hledat',
'searchbutton'      => 'Hledat',
'go'                => 'Jít na',
'searcharticle'     => 'Jít na',
'history'           => 'Historie stránky',
'history_short'     => 'Historie',
'updatedmarker'     => 'změněno od poslední návštěvy',
'info_short'        => 'Informace',
'printableversion'  => 'Verze k tisku',
'permalink'         => 'Trvalý odkaz',
'print'             => 'Vytisknout',
'edit'              => 'Editovat',
'editthispage'      => 'Editovat stránku',
'delete'            => 'Smazat',
'deletethispage'    => 'Smazat stránku',
'undelete_short'    => 'Obnovit $1 {{plural:$1|verzi|verze|verzí}}',
'protect'           => 'Zamknout',
'protect_change'    => 'změnit zámek',
'protectthispage'   => 'Zamknout stránku',
'unprotect'         => 'Odemknout',
'unprotectthispage' => 'Odemknout stránku',
'newpage'           => 'Nová stránka',
'talkpage'          => 'Diskusní stránka',
'talkpagelinktext'  => 'diskuse',
'specialpage'       => 'Speciální stránka',
'personaltools'     => 'Osobní nástroje',
'postcomment'       => 'Přidat komentář',
'articlepage'       => 'Prohlédnout si článek',
'talk'              => 'Diskuse',
'views'             => 'Zobrazení',
'toolbox'           => 'Nástroje',
'userpage'          => 'Prohlédnout si uživatelovu stránku',
'projectpage'       => 'Prohlédnout si stránku projektu',
'imagepage'         => 'Prohlédnout si stránku o obrázku',
'mediawikipage'     => 'Prohlédnout si text rozhraní',
'templatepage'      => 'Prohlédnout si šablonu',
'viewhelppage'      => 'Prohlédnout si stránku nápovědy',
'categorypage'      => 'Prohlédnout si stránku kategorie',
'viewtalkpage'      => 'Prohlédnout si diskusi',
'otherlanguages'    => 'V jiných jazycích',
'redirectedfrom'    => '(Přesměrováno z $1)',
'redirectpagesub'   => 'Přesměrování',
'lastmodifiedat'    => ' Stránka byla naposledy editována v $2, $1.', # $1 date, $2 time
'viewcount'         => 'Stránka byla zobrazena $1krát.',
'protectedpage'     => 'Zamčená stránka',
'jumpto'            => 'Přejít na:',
'jumptonavigation'  => 'navigace',
'jumptosearch'      => 'hledání',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'O&nbsp;{{grammar:6sg|{{SITENAME}}}}',
'aboutpage'         => 'Project:{{SITENAME}}',
'bugreports'        => 'Hlášení chyb',
'bugreportspage'    => 'Project:Chyby',
'copyright'         => 'Obsah je dostupný pod $1.',
'copyrightpagename' => 'podmínek {{grammar:2sg|{{SITENAME}}}}',
'copyrightpage'     => '{{ns:4}}:Autorské právo',
'currentevents'     => 'Aktuality',
'currentevents-url' => 'Aktuality',
'disclaimers'       => 'Vyloučení odpovědnosti',
'disclaimerpage'    => 'Project:Vyloučení odpovědnosti',
'edithelp'          => 'Pomoc při editování',
'edithelppage'      => 'Help:Jak editovat stránku',
'faq'               => 'Často kladené otázky',
'faqpage'           => 'Project:Často kladené otázky',
'helppage'          => 'Help:Obsah',
'mainpage'          => 'Hlavní strana',
'policy-url'        => 'Project:Pravidla',
'portal'            => 'Portál {{grammar:2sg|{{SITENAME}}}}',
'portal-url'        => 'Project:Portál {{grammar:2sg|{{SITENAME}}}}',
'privacy'           => 'Ochrana osobních údajů',
'privacypage'       => 'Project:Ochrana osobních údajů',
'sitesupport'       => 'Sponzorství',
'sitesupport-url'   => 'Project:Sponzorství',

'badaccess'        => 'Nedostatečná oprávnění',
'badaccess-group0' => 'Nemáte oprávnění k provedené požadované činnosti.',
'badaccess-group1' => 'Požadovanou činnost smějí provádět jen uživatelé ve skupině $1.',
'badaccess-group2' => 'Požadovanou činnost smějí provádět jen uživatelé ve skupinách $1.',
'badaccess-groups' => 'Požadovanou činnost smějí provádět jen uživatelé ve skupinách $1.',

'versionrequired'     => 'Vyžadováno MediaWiki verze $1',
'versionrequiredtext' => 'Pro použití této stránky je vyžadováno MediaWiki verze $1. Vizte [[{{ns:-1}}:Version]].',

'retrievedfrom'           => 'Citováno z „$1“',
'youhavenewmessages'      => 'Máte $1 ($2).',
'newmessageslink'         => 'nové zprávy',
'newmessagesdifflink'     => 'rozdíl oproti předchozí verzi',
'youhavenewmessagesmulti' => 'Na $1 máte nové zprávy',
'editsection'             => 'editovat',
'editold'                 => 'editovat',
'editsectionhint'         => 'Editace části $1',
'toc'                     => 'Obsah',
'showtoc'                 => 'zobrazit',
'hidetoc'                 => 'skrýt',
'thisisdeleted'           => 'Prohlédnout nebo obnovit $1?',
'viewdeleted'             => 'Zobrazit $1?',
'restorelink'             => '{{plural:$1|smazanou editaci|$1 smazané editace|$1 smazaných editací}}',
'feedlinks'               => 'Kanály:',
'feed-invalid'            => 'Neplatný typ kanálu.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Článek',
'nstab-user'      => 'Uživatelova stránka',
'nstab-media'     => 'Soubor',
'nstab-special'   => 'Speciální',
'nstab-project'   => 'Stránka projektu',
'nstab-image'     => 'Soubor',
'nstab-mediawiki' => 'Hlášení',
'nstab-template'  => 'Šablona',
'nstab-help'      => 'Nápověda',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Neznámý úkon',
'nosuchactiontext'  => 'Tato wiki nezná činnost (action) uvedenou v URL.',
'nosuchspecialpage' => 'Neexistující speciální stránka',
'nospecialpagetext' => 'Žádaná speciální stránka na této wiki neexistuje.',

# General errors
'error'                => 'Chyba',
'databaseerror'        => 'Databázová chyba',
'dberrortext'          => "Při dotazu do databáze došlo k syntaktické chybě.
Příčinou může být chyba v programu.
Poslední dotaz byl:
<blockquote><tt>$1</tt></blockquote>
z funkce '<tt>$2</tt>'.
MySQL vrátil chybu '<tt>$3: $4</tt>'.",
'dberrortextcl'        => 'Při dotazu do databáze došlo k syntaktické chybě.
Poslední dotaz byl:
"$1"
z funkce "$2".
MySQL vrátil chybu "$3: $4"',
'noconnect'            => 'Promiňte! Tato wiki má nějaké technické potíže a nepodařilo se připojit k databázovém serveru.<br />
$1',
'nodb'                 => 'Nebylo možné vybrat databázi $1',
'cachederror'          => 'Následuje kopie požadované stránky z cache, která nemusí být aktuální.',
'laggedslavemode'      => 'Upozornění: Stránka nemusí být zcela aktuální.',
'readonly'             => 'Databáze je uzamčena',
'enterlockreason'      => 'Udejte důvod zamčení, včetně odhadu, za jak dlouho dojde k odemčení.',
'readonlytext'         => 'Databáze je nyní uzamčena, takže nelze ukládat nové doplňky a změny. Důvodem je pravděpodobně pravidelná údržba, po které se vše vrátí do normálního stavu. 

Správce, který databázi zamkl, zanechal následující zprávu: $1',
'missingarticle'       => 'Databáze nenašla text článku, který měla najít, nazvaného „$1“.

Důvodem je obvykle zastaralý odkaz do historie smazané stránky.

V jiném případě jste možná narazil(a) na chybu v programu. Oznamte to prosím správci systému (zapamatujte si použité URL).',
'readonly_lag'         => 'Databáze byla automaticky dočasně uzamčena kvůli zpoždění ostatních databázových servery proti hlavnímu',
'internalerror'        => 'Vnitřní chyba',
'internalerror_info'   => 'Vnitřní chyba: $1',
'filecopyerror'        => 'Nebylo možné zkopírovat soubor  „$1“ na „$2“.',
'filerenameerror'      => 'Nebylo možné přejmenovat soubor „$1“ na „$2“.',
'filedeleteerror'      => 'Nebylo možné smazat soubor „$1“.',
'directorycreateerror' => 'Nelze vytvořit adresář „$1“.',
'filenotfound'         => 'Nebylo možné najít soubor „$1“.',
'fileexistserror'      => 'Nelze uložit do souboru „$1“: soubor existuje',
'unexpected'           => 'Neočekávaná hodnota: "$1"="$2".',
'formerror'            => 'Chyba: nebylo možné odeslat formulář',
'badarticleerror'      => 'Tento úkon nelze použít na tento článek.',
'cannotdelete'         => 'Nebylo možné smazat zvolenou stránku ani soubor. (Možná už byla smazána někým jiným.)',
'badtitle'             => 'Neplatný název',
'badtitletext'         => 'Požadovaný název stránky byl neplatný, prázdný nebo obsahoval nesprávnou předponu mezijazykového či interwiki odkazu. Možná obsahoval znaky, které v názvu nejsou dovoleny.',
'perfdisabled'         => 'Omlouváme se. Tato služba byla dočasně znepřístupněna, protože zpomalovala databázi natolik, že nikdo nemohl používat wiki.',
'perfcached'           => 'Následující data jsou z cache a nemusí být plně aktuální:',
'perfcachedts'         => 'Následující data jsou z cache, která byla naposledy aktualizována $1.',
'querypage-no-updates' => 'Aktualizace této stránky je vypnuta. Data teď nebudou obnoveny.',
'wrong_wfQuery_params' => 'Nesprávné parametry do wfQuery()<br />
Funkce: $1<br />
Dotaz: $2',
'viewsource'           => ' Ukázat zdroj',
'viewsourcefor'        => 'stránky $1',
'protectedpagetext'    => 'Tato stránka byla zamčena, takže ji nelze editovat.',
'viewsourcetext'       => 'Můžete si prohlédnout a zkopírovat zdrojový kód této stránky:',
'protectedinterface'   => 'Tato stránka obsahuje text softwarového rozhraní a smějí ji editovat jen správci.',
'editinginterface'     => "'''Upozornění:''' Editujete stránku, která definuje texty rozhraní. Změny této stránky ovlivní vzhled uživatelského rozhraní všem uživatelům.",
'sqlhidden'            => '(SQL dotaz skryt)',
'cascadeprotected'     => 'Tato stránka je zamčena, neboť je vložena do následujících stránek zamčených kaskádovým zámkem:',
'namespaceprotected'   => "Nemáte povoleno editovat stránky ve jmenném prostoru '''$1'''.",
'customcssjsprotected' => 'Nemáte povoleno editovat tuto stránku, protože obsahuje osobní nastavení jiného uživatele.',
'ns-specialprotected'  => 'Stránky ve jmenném prostoru {{ns:special}} nelze editovat.',

# Login and logout pages
'logouttitle'                => 'Na shledanou!',
'logouttext'                 => 'Nyní jste odhlášeni.<br />
Tento počítač může být používán k prohlížení a editaci {{grammar:2sg|{{SITENAME}}}} bez uživatelského jména, nebo pro přihlášení jiného uživatele. Upozorňujeme, že některé stránky se mohou i nadále zobrazovat, jako byste byli dosud přihlášeni. Tento jev potrvá do smazání cache vašeho prohlížeče.',
'welcomecreation'            => '== Vítejte, $1! ==
Váš účet byl vytvořen. Nezapomeňte si upravit své [[Special:Preferences|nastavení]]!',
'loginpagetitle'             => 'Přihlaste se',
'yourname'                   => 'Název vašeho účtu',
'yourpassword'               => 'Vaše heslo',
'yourpasswordagain'          => 'Napište heslo znovu',
'remembermypassword'         => 'Trvalé přihlášení',
'yourdomainname'             => 'Vaše doména',
'externaldberror'            => 'Buď nastalo chyba v databázi pro externí autentikaci, nebo nemáte dovoleno měnit svůj externí účet.',
'loginproblem'               => '<b>Nastal problém při vašem přihlášení.</b><br />Zkuste to znovu!',
'login'                      => 'Přihlaste se',
'loginprompt'                => 'K přihlášení do {{grammar:2sg|{{SITENAME}}}} musíte mít povoleny cookies.',
'userlogin'                  => 'Přihlaste se',
'logout'                     => 'Odhlásit se',
'userlogout'                 => 'Odhlášení',
'notloggedin'                => 'Nejste přihlášen(a)',
'nologin'                    => 'Dosud nemáte účet? $1.',
'nologinlink'                => 'Zaregistrujte se',
'createaccount'              => 'Vytvořit nový účet',
'gotaccount'                 => 'Už jste registrováni? $1.',
'gotaccountlink'             => 'Přihlaste se',
'createaccountmail'          => 'pomocí e-mailu',
'badretype'                  => 'Vámi napsaná hesla nesouhlasí.',
'userexists'                 => 'Uživatel se stejným jménem je už registrován. Zvolte jiné jméno.',
'youremail'                  => 'Vaše e-mailová adresa: *)',
'username'                   => 'Uživatelské jméno:',
'uid'                        => 'Uživatelské ID:',
'yourrealname'               => 'Vaše skutečné jméno: **)',
'yourlanguage'               => 'Jazyk rozhraní',
'yourvariant'                => 'Jazyková varianta',
'yournick'                   => 'Alternativní podpis',
'badsig'                     => 'Chybný podpis, zkontrolujte syntaxi HTML.',
'badsiglength'               => 'Jméno je příliš dlouhé; musí být kratší než $1 znaků.',
'prefs-help-realname'        => '**) Skutečné jméno (volitelné): pokud ho zadáte, bude použito pro označení autorství vaší práce.<br />',
'loginerror'                 => 'Chyba při přihlašování',
'prefs-help-email'           => '*) E-mail (volitelný): Umožní ostatním uživatelům vás kontaktovat, aniž by tato adresa byla zobrazena; také vám na tuto adresu může být zasláno nové heslo v případě, že své heslo zapomenete.',
'nocookiesnew'               => 'Uživatelský účet byl vytvořen, ale nejste přihlášeni. {{SITENAME}} používá cookies k přihlášení uživatelů. Vy máte cookies vypnuty. Prosím zapněte je a přihlaste se znovu s vaším novým uživatelským jménem a heslem.',
'nocookieslogin'             => '{{SITENAME}} používá cookies k přihlášení uživatelů. Vy máte cookies vypnuty. Prosím zapněte je a zkuste znovu.',
'noname'                     => 'Musíte uvést jméno svého účtu.',
'loginsuccesstitle'          => 'Přihlášení uspělo',
'loginsuccess'               => 'Nyní jste přihlášen na {{grammar:6sg|{{SITENAME}}}} jako uživatel „$1“.',
'nosuchuser'                 => 'Neexistuje uživatel se jménem „$1“. Zkontrolujte zápis, nebo si vytvořte nový účet.',
'nosuchusershort'            => 'Neexistuje uživatel se jménem „$1“. Zkontrolujte zápis.',
'nouserspecified'            => 'Musíte zadat uživatelské jméno.',
'wrongpassword'              => 'Vámi uvedené heslo nesouhlasí. Zkuste to znovu.',
'wrongpasswordempty'         => 'Zadané heslo bylo prázdné. Zkuste to znovu.',
'passwordtooshort'           => 'Vaše heslo je příliš krátké. Musí obsahovat nejméně $1 {{plural:$1|znak|znaky|znaků}} a nesmí být stejné jako uživatelské jméno.',
'mailmypassword'             => 'Poslat e-mailem dočasné heslo',
'passwordremindertitle'      => 'Připomenutí ztraceného hesla z {{grammar:2sg|{{SITENAME}}}}',
'passwordremindertext'       => 'Někdo (patrně Vy, z IP adresy $1) žádal, abychom Vám poslali nové heslo pro přihlášení do {{SITENAME}} ($4).

 Heslo pro uživatele "$2" je nyní "$3". Doporučujeme přihlásit se nyní a změnit heslo.
Pokud jste o změnu hesla nežádali nebo jste si na původní heslo již vzpomněli a už ho změnit
nechcete, můžete tuto zprávu ignorovat a používat staré heslo.',
'noemail'                    => 'Uživatel „$1“ nemá zaregistrovanou e-mailovou adresu.',
'passwordsent'               => 'Dočasné heslo bylo zasláno na e-mailovou adresu registrovanou pro „$1“. Přihlaste se, prosím, znovu, jakmile ho obdržíte.',
'blocked-mailpassword'       => 'Vaší IP adrese byla zablokována možnost editace, a současně s tím je zablokována funkce pro zaslání nového hesla.',
'eauthentsent'               => 'Potvrzovací e-mail byl zaslán na zadanou adresu.
Před tím, než vám na tuto adresu budou moci být zasílány další zprávy, následujte instrukce
v e-mailu, abyste potvrdili, že tato adresa skutečně patří vám.',
'throttled-mailpassword'     => 'Heslo již bylo jednou zasláno během uplynulých $1 hodin. 
Heslo může být zasláno jen jednou za $1 {{plural:$1|hodinu|hodiny|hodin}}.',
'mailerror'                  => 'Chyba při zasílání e-mailu: $1',
'acct_creation_throttle_hit' => 'Omlouváme se, ale už jste vyrobil(a) $1 účtů. Žádný další už nemůžete vytvořit.',
'emailauthenticated'         => 'Vaše e-mailová adresa byla ověřena $1.',
'emailnotauthenticated'      => 'Vaše e-mailová adresa dosud nebyla ověřena a e-mailové funkce do té doby nejsou dostupné.',
'noemailprefs'               => 'Pro zprovoznění následujících možností musíte zadat svou e-mailovou adresu.',
'emailconfirmlink'           => 'Podvrďte svou e-mailovou adresu',
'invalidemailaddress'        => 'Zadaná e-mailová adresa nemůže být přijata, neboť nemá správný formát. Zadejte laskavě platnou e-mailovou adresu, nebo obsah tohoto pole vymažte.',
'accountcreated'             => 'Účet vytvořen',
'accountcreatedtext'         => 'Uživatelský účet $1 byl vytvořen.',
'loginlanguagelabel'         => 'Jazyk: $1',

# Password reset dialog
'resetpass'               => 'Nové nastavení hesla účtu',
'resetpass_announce'      => 'Přihlašujete se dočasným heslem zaslaným e-mailem. Přihlášení lze dokončit po nastavení nového trvalého hesla.',
'resetpass_text'          => '<!-- Sem přidejte text -->',
'resetpass_header'        => 'Nové nastavení hesla',
'resetpass_submit'        => 'Nastavit heslo a přihlásit se',
'resetpass_success'       => 'Vaše heslo bylo úspěšně změněno. Probíhá přihlašování...',
'resetpass_bad_temporary' => 'Neplatné dočasné heslo. Možná již bylo heslo úspěšně změněno nebo někdo znovu požádal o nové dočasné heslo.',
'resetpass_forbidden'     => 'Na této wiki heslo nelze změnit.',
'resetpass_missing'       => 'Ve formuláři nejsou žádná data.',

# Edit page toolbar
'bold_sample'     => 'Tučný text',
'bold_tip'        => ' Tučný text',
'italic_sample'   => 'Kurzíva',
'italic_tip'      => 'Kurzíva',
'link_sample'     => 'Název odkazu',
'link_tip'        => 'Vnitřní odkaz',
'extlink_sample'  => 'http://www.example.com Titulek odkazu',
'extlink_tip'     => 'Externí odkaz (nezapomeňte na předponu http://)',
'headline_sample' => 'Text nadpisu',
'headline_tip'    => 'Nadpis druhé úrovně',
'math_sample'     => 'Vložit sem vzorec',
'math_tip'        => 'Matematický vzorec (LaTeX)',
'nowiki_sample'   => ' Vložit sem neformátovaný text',
'nowiki_tip'      => 'Ignorovat formátování wiki',
'image_sample'    => 'Příklad.jpg',
'image_tip'       => 'Vložený obrázek',
'media_sample'    => 'Příklad.ogg',
'media_tip'       => 'Odkaz na mediální soubor',
'sig_tip'         => 'Váš podpis s časovým údajem',
'hr_tip'          => 'Vodorovná čára (používejte střídmě)',

# Edit pages
'summary'                   => '<a href="{{LOCALURLE:Project:Shrnutí editace}}" class="internal" title="Stručně popište změny, které jste zde učinili">Shrnutí editace</a>',
'subject'                   => 'Předmět/nadpis',
'minoredit'                 => 'Tato změna je malá editace.',
'watchthis'                 => 'Sledovat tento článek',
'savearticle'               => 'Uložit změny',
'preview'                   => 'Náhled',
'showpreview'               => 'Ukázat náhled',
'showlivepreview'           => 'Rychlý náhled',
'showdiff'                  => 'Ukázat změny',
'anoneditwarning'           => "'''Varování:''' Nejste přihlášen(a). Vaše IP adresa bude zveřejněna v historii této stránky.",
'missingsummary'            => "'''Připomenutí:''' Nezadali jste shrnutí editace. Pokud ještě jednou kliknete na Uložit změny, bude vaše editace zapsána bez shrnutí.",
'missingcommenttext'        => 'Zadejte komentář',
'missingcommentheader'      => "'''Připomenutí:''' Nezadali jste předmět/nadpis pro tento komentář. Pokud ještě jednou kliknete na Uložit změny, bude vaše editace zapsána i bez toho.",
'summary-preview'           => 'Náhled shrnutí',
'subject-preview'           => 'Náhled předmětu/nadpisu',
'blockedtitle'              => 'Uživatel zablokován',
'blockedtext'               => "<big>'''Vaší IP adrese či uživatelskému jménu byla zablokována možnost editace.'''</big>

Vaše uživatelské jméno nebo IP adresa však byla zablokována správcem s uživatelským jménem „$1“. Jako důvod blokování uvedl: '''$2'''. 

* Začátek zablokování: $8 
* Zablokování vyprší: $6
* Určeno k blokování: $7 

Pokud chcete zablokování prodiskutovat, můžete kontaktovat uživatele $1 či jiného [[{{MediaWiki:grouppage-sysop}}|správce]].
Uvědomte si, že nemůžete použít nabídku „Poslat e-mail“, jestliže nemáte na {{grammar:6sg|{{SITENAME}}}} účet a ve svém [[Special:Preferences|nastavení]] uvedenu platnou e-mailovou adresu a pokud Vám byla tato možnost zakázána.
Vaše IP adresa je '''$3''' a identifikační číslo bloku je '''$5'''; tyto údaje budete muset uvést ve všech žádostech o odblokování.",
'autoblockedtext'           => 'Vaše IP adresa byla automaticky zablokována, protože byla používána jiným uživatelem, kterého zablokoval $1.

Udaný důvod:

:\'\'$2\'\'

* Zablokováno: $8
* Zablokování vyprší: $6

Můžete kontaktovat správce $1 nebo některého z dalších [[{{MediaWiki:grouppage-sysop}}|správců]] pro diskuzi o Vašem zablokování.

Mějte však na vědomí, že funkci "Poslat email tomuto uživateli" nemůžete použít, pokud nemáte zadaný platný email ve svém [[Special:Preferences|nastavení]] a nebylo vám zablokováno jeho užívání.

Číslo Vašeho zablokování je $5. Prosíme, uveďte ho při komunikaci se správci.',
'blockedoriginalsource'     => "Zdrojový text stránky '''$1''' následuje:",
'blockededitsource'         => "Text '''vašich editací''' stránky '''$1''' následuje:",
'whitelistedittitle'        => 'Pro editaci je vyžadováno přihlášení',
'whitelistedittext'         => 'Pro editaci se musíte $1.',
'whitelistreadtitle'        => 'Vyžadováno přihlášení',
'whitelistreadtext'         => 'Pro čtení článků se musíte [[Special:Userlogin|přihlásit]].',
'whitelistacctitle'         => 'Není vám dovoleno vytvářet uživatelské účty',
'whitelistacctext'          => 'Abyste na této wiki mohl(a) vytvářet uživatelské účty, musíte se [[Special:Userlogin|přihlásit]] a mít příslušná oprávnění.',
'confirmedittitle'          => 'Vyžadováno e-mailové potvrzení',
'confirmedittext'           => 'Pro editaci stránek je vyžadováno potvrzení vaší e-mailové adresy. Na stránce [[Special:Preferences|nastavení]] zadejte a nechte potvrdit svou e-mailovou adresu.',
'nosuchsectiontitle'        => 'Neexistující sekce',
'nosuchsectiontext'         => 'Pokoušíte se editovat neexistující sekci. Protože tu není žádná sekce „$1“, není kam uložit Vaši editaci.',
'loginreqtitle'             => 'Vyžadováno přihlášení',
'loginreqlink'              => 'přihlásit',
'loginreqpagetext'          => ' K prohlížení jiných stránek se musíte $1.',
'accmailtitle'              => 'Heslo odesláno.',
'accmailtext'               => 'Heslo pro „$1“ bylo odesláno na $2.',
'newarticle'                => '(Nový)',
'newarticletext'            => "Následovali jste odkaz na stránku, která dosud neexistuje.
Pokud ji chcete vytvořit, napište text do rámečku níže a stiskněte tlačítko ''Uložit změny''. Další rady najdete v [[{{MediaWiki:helppage}}|nápovědě]].
Pokud jste zde omylem, stiskněte ve svém prohlížeči tlačítko ''Zpět''.",
'anontalkpagetext'          => "---- ''Toto je diskusní stránka anonymního uživatele, který si dosud nevytvořil účet nebo ho nepoužívá. Musíme proto použít číselnou IP adresu k jeho identifikaci. Taková IP adresa může být sdílena několika uživateli. Pokud jste anonymní uživatel a cítíte, že jsou Vám adresovány irrelevantní komentáře, prosím [[Special:Userlogin|vytvořte si účet nebo se přihlaste]] a tím se vyhnete budoucí záměně s jinými anonymními uživateli.''",
'noarticletext'             => 'Tato stránka zatím neobsahuje žádný text, můžete [[Special:Search/{{PAGENAME}}|zkusit tento název vyhledat]] na jiných stránkách, nebo [{{fullurl:{{FULLPAGENAME}}|action=edit}} tuto stránku založit].',
'clearyourcache'            => "'''Poznámka:''' Po uložení musíte smazat cache vašeho prohlížeče, jinak změny neuvidíte: '''Mozilla / Firefox:''' ''Ctrl-Shift-R'', '''IE:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror''': ''F5''.",
'usercssjsyoucanpreview'    => '<strong>Tip:</strong> Použijte tlačítko „Ukázat náhled“ k testování vašeho nového css/js před uložením.',
'usercsspreview'            => "'''Pamatujte, že si prohlížíte jen náhled vašeho uživatelského css, neboť ještě nebylo uloženo!'''",
'userjspreview'             => "'''Pamatujte, že testujete a prohlížíte pouze náhled vašeho uživatelského javascriptu, dosud nebyl uložen!'''",
'userinvalidcssjstitle'     => "'''Varování:''' Vzhled „$1“ neexistuje. Nezapomeňte, že uživatelské .css a .js soubory používají malá písmena, např. {{ns:User}}:{{BASEPAGENAME}}/monobook.css, nikoli {{ns:User}}:{{BASEPAGENAME}}/Monobook.css.",
'updated'                   => '(Změna uložena)',
'note'                      => '<strong>Poznámka:</strong>&nbsp;',
'previewnote'               => 'Pamatujte, že toto je pouze náhled, ne uložení!',
'previewconflict'           => 'Tento náhled ukazuje text tak, jak bude vypadat po uložení stránky.',
'session_fail_preview'      => '<strong>Omlouváme se, ale váš požadavek se nepodařilo zpracovat. Zkuste to prosím znovu. Pokud se tento problém bude opakovat, zkuste se odhlásit a znovu přihlásit.</strong>',
'session_fail_preview_html' => "<strong>Omlouváme se, ale váš požadavek se nepodařilo zpracovat.</strong>

''Jelikož tato wiki má povoleno libovolné HTML, není zobrazen náhled jako prevence proti útokům JavaScriptem.''

Pokud jde o zamýšlenou editaci, zkuste to prosím znovu. Pokud se tento problém bude opakovat, zkuste se odhlásit a znovu přihlásit.",
'token_suffix_mismatch'     => '<strong>Vaše editace byla odmítnuta, protože Váš prohlížeč komolí některé znaky v editovaném textu. Editace byla odmítnuta, aby se zabránilo poškození textu stránky. 
Toto se může někdy stát pokud používáte chybující webový anonymizér.</strong>',
'editing'                   => 'Editace stránky $1',
'editinguser'               => 'Úprava práv uživatele $1',
'editingsection'            => 'Editace stránky $1 (část)',
'editingcomment'            => 'Editace stránky $1 (komentář)',
'editconflict'              => 'Editační konflikt: $1',
'explainconflict'           => 'Někdo změnil stránku po započetí vaší editace. Výše vidíte aktuální text článku. Vaše změny jsou uvedeny dole. Musíte sloučit své změny se stávajícím článkem. <b>Pouze</b> výše uvedený text zůstane uchován po kliknutí na „Uložit“. <br />',
'yourtext'                  => 'Váš text',
'storedversion'             => ' Uložená verze',
'nonunicodebrowser'         => '<strong>UPOZORNĚNÍ: Váš prohlížeč není schopen pracovat se znaky Unicode, pro editaci stránek prosím použijte nějaký jiný.</strong>',
'editingold'                => '<strong>VAROVÁNÍ: Nyní editujete zastaralou verzi této stránky. Když ji uložíte, všechny změny provedené mezitím se ztratí.</strong>',
'yourdiff'                  => 'Rozdíly',
'copyrightwarning'          => 'Všechny příspěvky do {{grammar:2sg|{{SITENAME}}}} jsou zveřejňovány podle $2 (podrobnosti najdete na $1). Pokud si nepřejete, aby váš text byl nemilosrdně upravován a volně šířen, pak ho do {{grammar:2sg|{{SITENAME}}}} neukládejte.<br />
Uložením příspěvku se zavazujete, že je vaším dílem nebo je zkopírován ze zdrojů, které nejsou chráněny autorským právem (tzv. <em>public domain</em>). <strong>Nekopírujte díla chráněná autorským právem bez dovolení!</strong>',
'copyrightwarning2'         => 'Uvědomte si, že všechny příspěvky do {{grammar:2sg|{{SITENAME}}}} mohou být ostatními uživateli upraveny, pozměněny či odstraněny. Pokud si nepřejete, aby váš text byl nemilosrdně upravován, pak ho do {{grammar:2sg|{{SITENAME}}}} neukládejte.<br />
Uložením příspěvku se zavazujete, že je vaším dílem nebo je zkopírován ze zdrojů, které nejsou chráněny autorským právem (tzv. <em>public domain</em>), podrobnosti najdete na $1. <strong>Nekopírujte díla chráněná autorským právem bez dovolení!</strong>',
'longpagewarning'           => 'VAROVÁNÍ: Tato stránka je $1 KB dlouhá; některé prohlížeče mohou mít problémy s editováním stran, které se blíží nebo jsou delší než 32 KB. Prosím zvažte rozdělení stránky na více částí.',
'longpageerror'             => '<strong>CHYBA: Pokoušíte se uložit text o velikosti $1 KB, přičemž dovolené maximum je $2 KB. Vaše editace nemůže být uložena.</strong>',
'readonlywarning'           => 'VAROVÁNÍ: Databáze byla uzamčena kvůli údržbě, takže nebudete moci uložit své změny. Můžete si okopírovat text do souboru a uložit ho na později.',
'protectedpagewarning'      => '<strong>Varování:</strong> Tato stránka byla zamčena, takže ji mohou editovat pouze správci.',
'semiprotectedpagewarning'  => '<strong>Poznámka:</strong> Tato stránka byla zamčena, takže ji mohou editovat pouze registrovaní uživatelé.',
'cascadeprotectedwarning'   => '<strong>Varování: Tato stránka byla zamčena, protože je vložena na stránku zamčenou kaskádním zámkem, a proto ji mohou editovat pouze správci.</strong>',
'templatesused'             => 'Šablony používané na této stránce:',
'templatesusedpreview'      => 'Šablony používané v tomto náhledu:',
'templatesusedsection'      => 'Šablony používané v této části stránky:',
'template-protected'        => '(zamčena)',
'template-semiprotected'    => '(polozamčena)',
'edittools'                 => '<!-- Tento text bude zobrazen pod formuláři pro editaci stránek a načítání souborů. -->',
'nocreatetitle'             => 'Vytváření nových stránek je omezeno',
'nocreatetext'              => 'Na tomto serveru je možnost vytváření nových stránek omezena.
Můžete se vrátit a editovat již existující stránku, nebo [[Special:Userlogin|se přihlásit či se registrovat]].',
'nocreate-loggedin'         => 'Nemáte povoleno zakládat nové stránky na této wiki.',
'permissionserrors'         => 'Chyba v povolení',
'permissionserrorstext'     => 'Nemáte povoleno toto provést z {{PLURAL:$1|následujícího důvodu|následujících důvodů|následujících důvodů}}:',
'recreate-deleted-warn'     => "'''Pozor: Pokoušíte se založit stránku, která již byla smazána.'''

Ujistěte se, že je vhodné pokračovat v editaci stránky.
Níže vidíte výpis z knihy smazaných stránek pro tuto stránku:",

# "Undo" feature
'undo-success' => 'Editace byla zrušena. Zkontrolujte a pak potvrďte změny zobrazené níže.',
'undo-failure' => 'Editace nemohla být zrušena kvůli konfliktu mezilehlých editací.',
'undo-summary' => 'Zrušena verze $1 od uživatele [[Special:Contributions/$2|$2]] ([[User talk:$2|diskuse]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nelze vytvořit uživatelský účet',
'cantcreateaccount-text' => "Zakládání nových účtů z této IP adresy (<b>$1</b>) bylo zablokováno uživatelem [[User:$3|$3]].

$3 uvádí toto zdůvodnění: ''$2''",

# History pages
'revhistory'          => 'Historie editací',
'viewpagelogs'        => 'Zobrazit protokolovací záznamy k této stránce',
'nohistory'           => 'O této stránce neexistuje historie editací.',
'revnotfound'         => 'Verze nenalezena',
'revnotfoundtext'     => 'Nelze najít starou verzi, kterou žádáte. Zkuste prosím zkontrolovat URL hledané stránky.\b',
'loadhist'            => 'Načítá se stránka historie editací',
'currentrev'          => 'Aktuální verze',
'revisionasof'        => 'Verze z $1',
'revision-info'       => 'Verze z $1; $2',
'previousrevision'    => '← Starší verze',
'nextrevision'        => 'Novější verze →',
'currentrevisionlink' => 'zobrazit aktuální verzi',
'cur'                 => 'teď',
'next'                => 'násl',
'last'                => 'předchozí',
'orig'                => 'původní',
'page_first'          => 'první',
'page_last'           => 'poslední',
'histlegend'          => '(teď) = rozdíly oproti nynější verzi, (předchozí) = rozdíly oproti předchozí verzi, <b>m</b> = malá editace',
'deletedrev'          => '[smazáno]',
'histfirst'           => 'Nejstarší',
'histlast'            => 'Nejnovější',
'historysize'         => '($1 bajtů)',
'historyempty'        => '(prázdné)',

# Revision feed
'history-feed-title'          => 'Historie editací',
'history-feed-description'    => 'Historie editací této stránky',
'history-feed-item-nocomment' => '$1 v $2', # user at time
'history-feed-empty'          => 'Požadovaná stránka neexistuje.
Mohla být smazána či přejmenována.
Zkuste [[Special:Search|hledání]].',

# Revision deletion
'rev-deleted-comment'         => '(komentář odstraněn)',
'rev-deleted-user'            => '(uživatelské jméno odstraněno)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Tato revize byla odstraněna z veřejného archivu.
Podrobnosti o smazání mohou být uvedeny v [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} knize smazaných stránek].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Tato revize byla odstraněna z veřejného archivu.
Jako správce si ji však můžete prohlédnout;
podrobnosti o smazání mohou být uvedeny v [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} knize smazaných stránek].
</div>',
'rev-delundel'                => 'skrýt/zobrazit',
'revisiondelete'              => 'Smazat/obnovit revize',
'revdelete-nooldid-title'     => 'Nezadána revize',
'revdelete-nooldid-text'      => 'Nezvolili jste revize, na které chcete tuto funkci použít.',
'revdelete-selected'          => 'Zvolené revize [[:$1]]:',
'revdelete-text'              => 'Smazané revize budou nadále zobrazeny v historii stránky, ale jejich text nebude veřejně dostupný.

Ostatní správci této wiki si budou moci skrytý obsah prohlížet a pomocí stejného rozhraní jej také obnovit,
pokud to provozovatel serveru nezakázal.',
'revdelete-legend'            => 'Nastavit omezení k revizi:',
'revdelete-hide-text'         => 'Skrýt text revize',
'revdelete-hide-comment'      => 'Skrýt editační komentář',
'revdelete-hide-user'         => 'Skrýt uživatelovu IP adresu',
'revdelete-hide-restricted'   => 'Tato omezení aplikovat i na správce',
'revdelete-hide-image'        => 'Skrýt obsah souboru',
'revdelete-unsuppress'        => 'Odstranit omezení na vrácené verze',
'revdelete-log'               => 'Komentář:',
'revdelete-submit'            => 'Aplikovat nastavení',
'revdelete-logentry'          => 'změnil viditelnost revizí u [[$1]]',
'logdelete-logentry'          => 'mění viditelnost události [[$1]]',

# Oversight log
'oversightlog'    => 'Kniha dohledu',
'overlogpagetext' => 'Níže je seznam posledních smazání a blokování s obsah skrytým pro správce. Přehled současných blokování je na [[Special:Ipblocklist|Seznamu probíhajících zablokování]].',

# Diffs
'difference'                => '(Rozdíly mezi verzemi)',
'loadingrev'                => 'načítají se verze pro zjištění rozdílů',
'lineno'                    => 'Řádka $1:',
'editcurrent'               => ' Editovat nynější verzi této stránky',
'selectnewerversionfordiff' => 'Vyberte novější verzi pro porovnání',
'selectolderversionfordiff' => 'Vyberte starší verzi pro porovnání',
'compareselectedversions'   => 'Porovnat vybrané verze',
'editundo'                  => 'zrušit editaci',
'diff-multi'                => '({{plural:$1|Není zobrazena jedna mezilehlá verze|Nejsou zobrazeny $1 mezilehlé verze|Není zobrazeno $1 mezilehlých verzí}}.)',

# Search results
'searchresults'         => 'Výsledky hledání',
'searchresulttext'      => 'Pro více informací o tom, jak hledat na {{grammar:6sg|{{SITENAME}}}}, se podívejte do [[{{MediaWiki:helppage}}|nápovědy]].',
'searchsubtitle'        => 'Hledáno „[[:$1]]“',
'searchsubtitleinvalid' => 'Hledáno „$1“',
'noexactmatch'          => "'''Neexistuje žádná stránka s názvem \"\$1\".''' Můžete ji však [[:\$1|vytvořit]].",
'titlematches'          => 'Stránky s odpovídajícím názvem',
'notitlematches'        => 'Žádné stránky názvem neodpovídají.',
'textmatches'           => 'Stránky s odpovídajícím textem',
'notextmatches'         => 'Žádné stránky textem neodpovídají.',
'prevn'                 => '$1 předchozích',
'nextn'                 => '$1 následujících',
'viewprevnext'          => 'Ukázat ($1) ($2) ($3).',
'showingresults'        => 'Zobrazuji <strong>$1</strong> {{plural:$1|výsledek|výsledky|výsledků}} počínaje od <strong>$2</strong>.',
'showingresultsnum'     => 'Zobrazuji <strong>$3</strong> {{plural:$3|výsledek|výsledky|výsledků}} počínaje od <strong>$2</strong>.',
'nonefound'             => '<strong>Poznámka</strong>: neúspěšná hledání jsou často důsledkem zadání slov, která nejsou indexována, nebo uvedením mnoha slov najednou (ve výsledku se objeví jen ty stránky, které obsahují všechna zadaná slova).',
'powersearch'           => 'Hledání',
'powersearchtext'       => '
Hledat ve jmenných prostorech:<br />
$1<br />
$2 Vypsat přesměrování &nbsp; Hledat $3 $9',
'searchdisabled'        => '<p>Omlouváme se. Plnotextové vyhledávání je dočasně nedostupné. Zatím můžete zkusit vyhledávání Googlem; je ale možné, že jeho výsledky nemusí být aktuální.</p>',

# Preferences page
'preferences'              => 'Nastavení',
'mypreferences'            => 'Nastavení',
'prefs-edits'              => 'Počet editací:',
'prefsnologin'             => 'Nejste přihlášen(a)!',
'prefsnologintext'         => 'Pro nastavení se musíte [[Special:Userlogin|přihlásit]].',
'prefsreset'               => 'Nastavení vráceno.',
'qbsettings'               => 'Nastavení lišty nástrojů',
'qbsettings-none'          => 'Žádný',
'qbsettings-fixedleft'     => 'Leží vlevo',
'qbsettings-fixedright'    => 'Leží vpravo',
'qbsettings-floatingleft'  => 'Visí vlevo',
'qbsettings-floatingright' => 'Visí vpravo',
'changepassword'           => 'Změna hesla',
'skin'                     => 'Styl',
'math'                     => 'Matematika',
'dateformat'               => 'Formát data',
'datedefault'              => 'Implicitní',
'datetime'                 => 'Datum a čas',
'math_failure'             => 'Nelze pochopit',
'math_unknown_error'       => 'neznámá chyba',
'math_unknown_function'    => 'neznámá funkce',
'math_lexing_error'        => 'chyba při lexingu',
'math_syntax_error'        => 'syntaktická chyba',
'math_image_error'         => 'Selhala konverze do PNG; zkontrolujte správnou instalaci latexu, dvips, gs a convertu',
'math_bad_tmpdir'          => 'Nelze zapsat nebo vytvořit dočasný adresář pro matematiku',
'math_bad_output'          => 'Nelze zapsat nebo vytvořit adresář pro výstup matematiky',
'math_notexvc'             => 'Chybí spustitelný texvc; podívejte se prosím do math/README na konfiguraci.',
'prefs-personal'           => 'Údaje o uživateli',
'prefs-rc'                 => ' Poslední změny',
'prefs-watchlist'          => 'Sledované stránky',
'prefs-watchlist-days'     => 'Počet dní zobrazených ve sledovaných stránkách:',
'prefs-watchlist-edits'    => 'Počet editací zobrazených na zdokonalených sledovaných stránkách:',
'prefs-misc'               => ' Různé',
'saveprefs'                => 'Uložit nastavení',
'resetprefs'               => 'Vrátit původní nastavení',
'oldpassword'              => 'Staré heslo',
'newpassword'              => 'Nové heslo',
'retypenew'                => 'Napište znovu nové heslo',
'textboxsize'              => 'Editace',
'rows'                     => 'Řádky',
'columns'                  => 'Sloupce',
'searchresultshead'        => 'Vyhledávání',
'resultsperpage'           => 'Počet nalezených článků na jednu stránku výsledků',
'contextlines'             => ' Počet řádek zobrazených z každé nalezené stránky',
'contextchars'             => ' Počet znaků kontextu na každé řádce',
'stub-threshold'           => 'Limit pro formátování odkazu jako <a href="#" class="stub">pahýl</a>:',
'recentchangesdays'        => 'Počet dní zobrazených v posledních změnách:',
'recentchangescount'       => 'Počet zobrazených záznamů v posledních změnách',
'savedprefs'               => 'Vaše nastavení bylo uloženo.',
'timezonelegend'           => 'Časové pásmo',
'timezonetext'             => 'Označte, o kolik se vaše časové pásmo liší od serveru (UTC). Například pro středoevropské časové pásmo (SEČ) vyplňte „01:00“ v zimě, „02:00“ v období platnosti letního času.',
'localtime'                => 'Místní časové pásmo',
'timezoneoffset'           => 'Posun',
'servertime'               => 'Aktuální čas na serveru',
'guesstimezone'            => 'Načíst z prohlížeče',
'allowemail'               => 'Dovolit e-mail od ostatních uživatelů',
'defaultns'                => 'Implicitně hledat v těchto jmenných prostorech:',
'default'                  => 'implicitní',
'files'                    => 'Soubory',

# User rights
'userrights-lookup-user'      => 'Spravovat uživatelské skupiny',
'userrights-user-editname'    => 'Zadejte uživatelské jméno:',
'editusergroup'               => 'Upravit uživatelskou skupinu',
'userrights-editusergroup'    => 'Upravit uživatelské skupiny',
'saveusergroups'              => 'Uložit uživatelské skupiny',
'userrights-groupsmember'     => 'Člen skupin:',
'userrights-groupsavailable'  => 'Dostupné skupiny:',
'userrights-groupshelp'       => 'Zvolte skupiny, do/ze kterých chcete uživatele přidat/odebrat.
Nezvolené skupiny nebudou změněny. Skupinu můžete vyřadit z vybraných pomocí CTRL + Levé tlačítko myši',
'userrights-reason'           => 'Zdůvodnění:',
'userrights-available-none'   => 'Nemůžete měnit zařazení do skupin.',
'userrights-available-add'    => 'Můžete uživatele přidat do skupiny $1.',
'userrights-available-remove' => 'Můžete odebrat uživatele ze skupiny $1.',

# Groups
'group'               => 'Skupina:',
'group-autoconfirmed' => 'schválení uživatelé',
'group-bot'           => 'Boti',
'group-sysop'         => 'Správci',
'group-bureaucrat'    => 'Byrokraté',
'group-all'           => '(všichni)',

'group-autoconfirmed-member' => 'schválený uživatel',
'group-sysop-member'         => 'Správce',
'group-bureaucrat-member'    => 'Byrokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Schválení uživatelé',
'grouppage-bot'           => '{{ns:Project}}:Boti',
'grouppage-sysop'         => '{{ns:Project}}:Správci',
'grouppage-bureaucrat'    => '{{ns:Project}}:Byrokraté',

# User rights log
'rightslog'      => 'Kniha práv uživatelů',
'rightslogtext'  => 'Toto je záznam změn uživatelských práv.',
'rightslogentry' => 'změnil pro $1 zařazení ve skupinách z $2 na $3',
'rightsnone'     => '(žádné)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|změna|změny|změn}}',
'recentchanges'                     => 'Poslední změny',
'recentchangestext'                 => 'Sledujte poslední změny na {{grammar:6sg|{{SITENAME}}}} na této stránce.',
'recentchanges-feed-description'    => 'Na tomto kanále sledujte poslední změny na {{grammar:6sg|{{SITENAME}}}}.',
'rcnote'                            => 'Níže {{plural:$1|je poslední|jsou poslední|je posledních}} <strong>$1</strong> {{plural:$1|změna|změny|změn}} za {{plural:$2|poslední|poslední|posledních}} <strong>$2</strong> {{plural:$2|den|dny|dnů}} před $3.',
'rcnotefrom'                        => 'Níže {{plural:$1|je|jsou|je}} nejvýše <b>$1</b> {{plural:$1|změna|změny|změn}} od <b>$2</b>.',
'rclistfrom'                        => 'Ukázat nové změny, počínaje od $1',
'rcshowhideminor'                   => '$1 malé editace',
'rcshowhidebots'                    => '$1 roboty',
'rcshowhideliu'                     => '$1 přihlášené uživatele',
'rcshowhideanons'                   => '$1 anonymní uživatele',
'rcshowhidepatr'                    => '$1 prověřené editace',
'rcshowhidemine'                    => '$1 moje editace',
'rclinks'                           => 'Ukázat $1 posledních změn během posledních $2 dnů<br />$3',
'diff'                              => 'rozdíl',
'hist'                              => 'historie',
'hide'                              => 'skrýt',
'show'                              => 'ukázat',
'number_of_watching_users_pageview' => '[$1 {{plural:$1|sledující uživatel|sledující uživatelé|sledujících uživatelů}}]',
'rc_categories'                     => 'Omezit na kategorie (oddělené „|“)',
'rc_categories_any'                 => 'Všechny',
'newsectionsummary'                 => 'Nová sekce /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Související změny',
'recentchangeslinked-title'    => 'Související změny pro stránku $1',
'recentchangeslinked-noresult' => 'V zadaném období nebyly na odkazovaných stránkách žádné změny.',
'recentchangeslinked-summary'  => "Níže jsou poslední změny na odkazovaných stránkách. Vaše sledované stránky jsou '''zvýrazněny'''.",

# Upload
'upload'                      => 'Načíst soubor',
'uploadbtn'                   => 'Načíst soubor',
'reupload'                    => 'Načíst znovu',
'reuploaddesc'                => 'Vrátit se k načtení.',
'uploadnologin'               => 'Nejste přihlášen(a)',
'uploadnologintext'           => 'Pro načtení souboru se musíte [[Special:Userlogin|přihlásit]].',
'upload_directory_read_only'  => 'Do adresáře pro načítané soubory ($1) nemá webserver právo zápisu.',
'uploaderror'                 => 'Při načítání došlo k chybě',
'uploadtext'                  => 'Pro prohlížení a hledání již dříve nahraných souborů se podívejte
na [[Special:Imagelist|seznam načtených souborů]], popř.
[[Special:Newimages|galerii nových obrázků]]. Všechna načtení
a smazání jsou zaznamenány v [[Special:Log|protokolovacích záznamech]].

Pomocí níže uvedeného formuláře můžete na wiki nahrát obrázky a jiné
soubory, které poté budete moci použít v článcích. Ve většině prohlížečů
je zobrazeno tlačítko „Procházet…“, pomocí kterého budete moci
vybrat soubor k načtení, jehož jméno se poté objeví v políčku
vedle tlačítka. Poté stiskněte tlačítko „Načíst soubor“ k
dokončení načtení. Buďte trpěliví, nahrávání může chvíli trvat.

Preferované formáty jsou JPEG pro fotografie, PNG pro schémata
a OGG pro zvuky. Používejte laskavě smysluplná jména souborů,
soubor po načtení nelze přejmenovat.

Pro vložení obrázku do stránky použijte syntaxi
<code><nowiki>[[</nowiki>{{ns:6}}<nowiki>:soubor.jpg]]</nowiki></code> nebo
<code><nowiki>[[</nowiki>{{ns:6}}<nowiki>:soubor.png|popisek]]</nowiki></code>, popř.
<code><nowiki>[[</nowiki>{{ns:-2}}<nowiki>:soubor.ogg]]</nowiki></code> pro zvuky.

Uvědomte si laskavě, že stejně jako u ostatních wikistránek mohou
ostatní uživatelé vámi nahraný soubor smazat či upravit, pokud to
uznají za vhodné; pokud budete tuto funkci zneužívat, může být
váš uživatelský účet zablokován.',
'uploadlog'                   => 'kniha nahrávek',
'uploadlogpage'               => 'Kniha nahrávek',
'uploadlogpagetext'           => 'Níže najdete seznam nejnovějších souborů.',
'filename'                    => 'Soubor',
'filedesc'                    => 'Popis',
'fileuploadsummary'           => 'Popis:',
'filestatus'                  => 'Autorská práva',
'filesource'                  => 'Zdroj',
'uploadedfiles'               => 'Načtené soubory',
'ignorewarning'               => 'Ignorovat varování a načíst soubor.',
'ignorewarnings'              => 'Ignorovat všechna varování',
'minlength1'                  => 'Jméno souboru musí mít alespoň jeden znak.',
'illegalfilename'             => 'Název souboru "$1" obsahuje znaky, které nejsou povoleny v názvech stránek. Prosím přejmenujte soubor a zkuste jej nahrát znovu.',
'badfilename'                 => 'Jméno souboru bylo změněno na „$1“.',
'filetype-badtype'            => "'''\".\$1\"''' je nedovolený typ
: Seznam povolených typů soborů: \$2",
'filetype-missing'            => 'Soubor nemá příponu (např. ".jpg").',
'large-file'                  => 'Doporučuje se, aby délka souboru nepřesahovala $1, tento soubor má $2.',
'largefileserver'             => 'Velikost tohoto souboru překračuje limit nastavený na serveru.',
'emptyfile'                   => 'Soubor, který jste vložili, se zdá být prázdný. Mohl to způsobit překlep v názvu souboru. Prosím zkontrolujte, zda jste opravdu chtěli vložit tento soubor.',
'fileexists'                  => ' Soubor s tímto jménem již existuje, prosím podívejte se na $1, pokud nevíte jistě, zda chcete tento soubor nahradit.',
'fileexists-extension'        => 'Již existuje soubor s podobným jménem:<br />
Jméno načítaného souboru: <strong><tt>$1</tt></strong><br />
Jméno existujícího souboru: <strong><tt>$2</tt></strong><br />
Vyberte jiné jméno.',
'fileexists-thumb'            => "'''<center>Existujícího soubor:</center>'''",
'fileexists-thumbnail-yes'    => 'Toto soubor je možná obrázek ve zmenšené velikosti <i>(náhled)</i>. Zkontrolujte soubor <strong><tt>$1</tt></strong>.<br />
Pokud je zmiňovaný soubor větší, ale jinak stejný, není potřeba zvlášť načítat jeho zmenšenou verzi.',
'file-thumbnail-no'           => 'Jméno souboru začíná na <strong><tt>$1</tt></strong>. Možná to je obrázek ve zmenšené velikosti <i>(náhled)</i>. 
Načtěte soubor v plném rozlišením, pokud je k dispozici, nebo změňte jméno souboru.',
'fileexists-forbidden'        => 'Soubor s tímto názvem již existuje; vraťte se a zvolte jiný název. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Soubor s tímto názvem již existuje ve sdíleném úložišti; vraťte se a zvolte jiný název. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Načtení úspěšně provedeno!',
'uploadwarning'               => 'Varování',
'savefile'                    => 'Uložit soubor',
'uploadedimage'               => 'načítá „[[$1]]“',
'overwroteimage'              => 'načtena nová verze "[[$1]]"',
'uploaddisabled'              => 'Načítání souborů vypnuto.',
'uploaddisabledtext'          => 'Promiňte, ale načítání souborů je na této wiki vypnuto.',
'uploadscripted'              => 'Tento soubor obsahuje HTML nebo kód skriptu, který by mohl být prohlížečem chybně interpretován.',
'uploadcorrupt'               => 'Soubor je poškozen nebo nemá správnou příponu. Zkontrolujte prosím soubor a zkuste ho načíst znovu.',
'uploadvirus'                 => 'Tento soubor obsahuje virus! Podrobnosti: $1',
'sourcefilename'              => 'Jméno zdrojového souboru',
'destfilename'                => 'Cílové jméno',
'watchthisupload'             => 'Sledovat tuto stránku',
'filewasdeleted'              => 'Soubor stejného jména byl již dříve načten a posléze smazán. Podrobnosti obsahuje $1.',
'filename-bad-prefix'         => 'Jméno souboru, který načítáte, začíná na <strong>"$1"</strong>, což je nevhodné jméno, obvykle automaticky přiřazované digitálním fotoaparátem. Zvolte jméno, která Váš soubor lépe popíše.',

'upload-proto-error'      => 'Neplatný protokol',
'upload-proto-error-text' => 'Nahrání vzdáleného souboru vyžaduje zadání URLs začínající na <code>http://</code> nebo <code>ftp://</code>.',
'upload-file-error'       => 'Vnitřní chyba',
'upload-file-error-text'  => 'Vnitřní chyba se vyskytla při vytváření dočasného souboru na serveru. Kontaktuje prosím administrátora systému.',
'upload-misc-error'       => 'Neznámá chyba',
'upload-misc-error-text'  => 'Neznámá chyba se vyskytla během nahrávání souboru.  Zkontrolujte zda je URL platná a přístupné a zkuste to znovu.  Pokud se chyba znovu objeví, kontaktuje administrátora systému.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Z URL nelze číst',
'upload-curl-error6-text'  => 'Ze zadané URL nelze číst.  Zkontrolujte ža URL je správně napsané a server je dostupný',
'upload-curl-error28'      => 'Čas pro nahrání vypršel',
'upload-curl-error28-text' => 'Serveru dlouho neodpovídá. Zkontrolujte, že server je dostupný, počkejte chvíli a zkuste to znovu.',

'license'            => 'Licence',
'nolicense'          => 'Bez udání licence',
'license-nopreview'  => '(Náhled není dostupný)',
'upload_source_url'  => ' (platné, veřejně přístupné URL)',
'upload_source_file' => ' (soubor na vašem počítači)',

# Image list
'imagelist'                 => 'Seznam načtených obrázků',
'imagelisttext'             => 'Níže je seznam $1 obrázků, seřazených $2.',
'getimagelist'              => 'načítám seznam obrázků',
'ilsubmit'                  => 'Hledat',
'showlast'                  => 'Ukázat posledních $1 obrázků řazených $2.',
'byname'                    => 'podle jména',
'bydate'                    => 'podle data',
'bysize'                    => 'podle velikosti',
'imgdelete'                 => 'smazat',
'imgdesc'                   => 'popis',
'imgfile'                   => 'soubor',
'filehist'                  => 'Historie souboru',
'filehist-help'             => 'Kliknutím na datum a čas se zobrazí tehdejší verze souboru.',
'filehist-deleteall'        => 'smazat vše',
'filehist-deleteone'        => 'smazat tuto',
'filehist-revert'           => 'vrátit zpět',
'filehist-current'          => 'současná',
'filehist-datetime'         => 'Datum a čas',
'filehist-user'             => 'Uživatel',
'filehist-dimensions'       => 'Rozměry',
'filehist-filesize'         => 'Velikost souboru',
'filehist-comment'          => 'Komentář',
'imagelinks'                => 'Odkazy k souboru',
'linkstoimage'              => 'Na soubor odkazují tyto stránky:',
'nolinkstoimage'            => 'Na tento soubor neodkazuje žádná stránka.',
'sharedupload'              => 'Tento soubor je sdílený a může být používán ostatními projekty.',
'shareduploadwiki'          => 'Více informací najdete na $1.',
'shareduploadwiki-linktext' => 'stránce s popisem',
'noimage'                   => 'Soubor s tímto jménem neexistuje, můžete ho $1',
'noimage-linktext'          => 'načíst',
'uploadnewversion-linktext' => 'Načíst novou verzi tohoto souboru',
'imagelist_date'            => 'Datum',
'imagelist_name'            => 'Název',
'imagelist_user'            => 'Uživatel',
'imagelist_size'            => 'Velikost (bajtů)',
'imagelist_description'     => 'Popis',
'imagelist_search_for'      => 'Hledat obrázek podle názvu:',

# File reversion
'filerevert'                => 'Vrátit zpět $1',
'filerevert-defaultcomment' => 'Navrácena verze nahraná v $2 dne $1.',

# File deletion
'filedelete-legend'    => 'Smazat soubor',
'filedelete-intro'     => "Chystáte se smazat '''[[Media:$1|$1]]'''.",
'filedelete-comment'   => 'Zdůvodnění:',
'filedelete-submit'    => 'Smazat',
'filedelete-success'   => "Soubor '''$1''' byl smazán.",
'filedelete-nofile'    => "Soubor '''$1''' neexistuje.",
'filedelete-iscurrent' => 'Pokoušíte se smazat současnou verzi souboru. Nejprve navraťte starší verzi souboru.',

# MIME search
'mimesearch' => 'Hledání podle MIME typu',
'mimetype'   => 'MIME typ:',

# Unwatched pages
'unwatchedpages' => 'Nesledované stránky',

# List redirects
'listredirects' => 'Seznam přesměrování',

# Unused templates
'unusedtemplates'     => 'Nepoužívané šablony',
'unusedtemplatestext' => 'Tato stránka obsahuje seznam všech stran ve jmenném prostoru {{ns:-1}}, které nejsou vloženy do žádné jiné strany. Před jejich smazáním nezapomeňte zkontrolovat ostatní odkazy.',
'unusedtemplateswlh'  => 'ostatní odkazy',

# Random redirect
'randomredirect'         => 'Náhodné přesměrování',
'randomredirect-nopages' => 'V tomto jmenném prostoru není žádné přesměrování.',

# Statistics
'statistics'             => 'Statistika',
'sitestats'              => 'Statistika {{grammar:2sg|{{SITENAME}}}}',
'userstats'              => 'O uživatelích',
'sitestatstext'          => "V databázi je celkem '''$1''' {{plural:$1|stránka|stránky|stránek}}. Toto číslo zahrnuje diskusní stránky, stránky o {{grammar:6sg|{{SITENAME}}}}, pahýly, přesměrování a další, které nejsou články v pravém slova smyslu. Kromě nich zbývá '''$2''' pravděpodobně {{plural:$2|skutečný článek|skutečné články|skutečných článků}}.

{{plural:$8|Byl načten|Byly načteny|Bylo načteno}} '''$8''' {{plural:$8|obrázek|obrázky|obrázků}}.

Od založení wiki bylo navštíveno celkem '''$3''' stránek a editováno '''$4'''krát. To činí v průměru '''$5''' editací na stránku a '''$6''' návštěv na editaci.

Aktuální délka fronty údržby je '''$7'''.",
'userstatstext'          => "Je zde '''$1''' registrovaných uživatelů,
z toho '''$2''' (nebo '''$4%''') správců (podívejte se na $3).",
'statistics-mostpopular' => 'Nejčtenější stránky',

'disambiguations'      => 'Stránky odkazující na rozcestníky',
'disambiguationspage'  => '{{ns:10}}:Rozcestník',
'disambiguations-text' => 'Odkazy na následujících stránkách vedou na rozcestníky (stránky obsahující některou ze šablon uvedených na [[MediaWiki:Disambiguationspage|seznamu rozcestníkových šablon]]).',

'doubleredirects'     => 'Dvojitá přesměrování',
'doubleredirectstext' => 'Každý řádek obsahuje odkaz na první a druhé přesměrování, plus první řádek textu druhého přesměrování, který obvykle ukazuje jméno „skutečného“ hlavního článku, na který by mělo první přesměrování odkazovat.',

'brokenredirects'        => 'Přerušená přesměrování',
'brokenredirectstext'    => ' Tato přesměrování vedou na neexistující články.',
'brokenredirects-edit'   => '(editovat)',
'brokenredirects-delete' => '(smazat)',

'withoutinterwiki'        => 'Stránky bez mezijazykových odkazů (interwiki)',
'withoutinterwiki-header' => 'Tyto stránky neobsahují žádný mezijazykový odkaz:',

'fewestrevisions' => 'Články s nejméně verzemi',

# Miscellaneous special pages
'nbytes'                  => '$1 {{plural:$1|bajt|bajty|bajtů}}',
'ncategories'             => '$1 {{plural:$1|kategorie|kategorie|kategorií}}',
'nlinks'                  => '$1 {{plural:$1|odkaz|odkazy|odkazů}}',
'nmembers'                => '$1 {{plural:$1|stránka|stránky|stránek}}',
'nrevisions'              => '$1 {{plural:$1|revize|revize|revizí}}',
'nviews'                  => '$1 zobrazení',
'specialpage-empty'       => 'Tomuto požadavku neodpovídají žádné záznamy.',
'lonelypages'             => 'Sirotčí články',
'lonelypagestext'         => 'Na následující stránky na této wiki neodkazuje žádná jiná stránka.',
'uncategorizedpages'      => 'Nekategorizované stránky',
'uncategorizedcategories' => 'Nekategorizované kategorie',
'uncategorizedimages'     => 'Nekategorizované soubory',
'uncategorizedtemplates'  => 'Nekategorizované šablony',
'unusedcategories'        => 'Nepoužívané kategorie',
'unusedimages'            => 'Nepoužívané obrázky a soubory',
'popularpages'            => 'Nejnavštěvovanější stránky',
'wantedcategories'        => 'Žádané kategorie',
'wantedpages'             => 'Žádoucí články',
'mostlinked'              => 'Nejodkazovanější stránky',
'mostlinkedcategories'    => 'Nejpoužívanější kategorie',
'mostlinkedtemplates'     => 'Nejvkládanější šablony',
'mostcategories'          => 'Články s nejvyšším počtem kategorií',
'mostimages'              => 'Nejpoužívanější soubory',
'mostrevisions'           => 'Stránky s nejvíce revizemi',
'allpages'                => 'Všechny stránky',
'prefixindex'             => 'Seznam stránek podle začátku názvu',
'randompage'              => 'Náhodná stránka',
'randompage-nopages'      => 'V tomto jmenném prostoru nejsou žádné stránky.',
'shortpages'              => 'Nejkratší články',
'longpages'               => 'Nejdelší články',
'deadendpages'            => 'Slepé články',
'deadendpagestext'        => 'Následující stránky neodkazují na žádnou jinou stránku této wiki.',
'protectedpages'          => 'Zamčené stránky',
'protectedpagestext'      => 'Následující stránky jsou zamčeny nebo polozamčeny pro editaci nebo přesun:',
'protectedpagesempty'     => 'Žádná stránka není zamčena s těmito parametry.',
'listusers'               => 'Uživatelé',
'specialpages'            => 'Speciální stránky',
'spheading'               => 'Speciální stránky pro všechny uživatele',
'restrictedpheading'      => 'Speciální stránky s omezeným přístupem',
'rclsub'                  => '(stránek odkazovaných z „$1“)',
'newpages'                => 'Nejnovější články',
'newpages-username'       => 'Uživatelské jméno:',
'ancientpages'            => 'Nejdéle needitované stránky',
'intl'                    => 'Mezijazykové odkazy',
'move'                    => 'Přesunout',
'movethispage'            => 'Přesunout stránku',
'unusedimagestext'        => '<p>Jiné WWW stránky mohou odkazovat přímo pomocí URL, na takové odkazy se v tomto seznamu nebere zřetel.',
'unusedcategoriestext'    => 'Následující kategorie mají své stránky, ačkoli je žádná stránka ani jiná kategorie nepoužívá.',

# Book sources
'booksources'               => 'Zdroje knih',
'booksources-search-legend' => 'Vyhledat knižní zdroje',
'booksources-go'            => 'Vyhledat',
'booksources-text'          => 'Níže je seznam odkazů na servery prodávající knihy, nebo které mohou mít další informace o knihách, které hledáte.',

'categoriespagetext' => 'Ve wiki existují následující kategorie:',
'userrights'         => 'Správa uživatelských skupin',
'groups'             => 'Uživatelské skupiny',
'alphaindexline'     => 'od $1 do $2',
'version'            => 'Verze',

# Special:Log
'specialloguserlabel'  => 'Uživatel:',
'speciallogtitlelabel' => 'Název:',
'log'                  => 'Protokolovací záznamy',
'all-logs-page'        => 'Všechny záznamy',
'alllogstext'          => 'Společné zobrazení všech dostupných protokolovacích záznamů pro {{grammar:4sg|{{SITENAME}}}}.
Zobrazení můžete zúžit výběrem typu záznamu, uživatelského jména nebo dotčené stránky.',
'logempty'             => 'Protokol neobsahuje žádný odpovídající záznam.',

# Special:Allpages
'nextpage'          => 'Další stránka ($1)',
'prevpage'          => 'Předchozí stránka ($1)',
'allpagesfrom'      => 'Všechny stránky počínaje od:',
'allarticles'       => 'Všechny články',
'allinnamespace'    => 'Všechny stránky (jmenný prostor $1)',
'allnotinnamespace' => 'Všechny stránky (mimo jmenný prostor $1)',
'allpagesprev'      => 'Předchozí',
'allpagesnext'      => 'Následující',
'allpagessubmit'    => 'Přejít',
'allpagesprefix'    => 'Zobrazit stránky začínající:',
'allpagesbadtitle'  => 'Zadaný název stránky nebyl platný nebo obsahoval předponu mezijazykového či interwiki odkazu. Možná obsahoval znaky, které v názvu nejsou dovoleny.',
'allpages-bad-ns'   => '{{SITENAME}} nemá jmenný prostor "$1".',

# Special:Listusers
'listusersfrom'      => 'Zobrazit uživatele počínaje od:',
'listusers-submit'   => 'Ukázat',
'listusers-noresult' => 'Nenalezen žádný uživatel.',

# E-mail user
'mailnologin'     => 'Bez odesílací adresy',
'mailnologintext' => 'Pokud chcete posílat e-maily jiným uživatelům, musíte se [[Special:Userlogin|přihlásit]] a mít platnou e-mailovou adresu ve svém [[Special:Preferences|nastavení]].',
'emailuser'       => 'Poslat e-mail',
'emailpage'       => 'Poslat e-mail',
'emailpagetext'   => 'Pokud tento uživatel uvedl platnou e-mailovou adresu ve svém nastavení, tímto formulářem mu lze poslat zprávu. E-mailová adresa, kterou máte uvedenu v nastavení, se objeví jako adresa odesílatele pošty, aby adresát mohl odpovědět.',
'usermailererror' => 'Chyba poštovního programu:',
'defemailsubject' => 'E-mail z {{grammar:2sg|{{SITENAME}}}}',
'noemailtitle'    => 'Bez e-mailové adresy',
'noemailtext'     => 'Tento uživatel buď nezadal platnou adresu nebo zakázal přijímat zprávy od jiných uživatelů.',
'emailfrom'       => 'Od',
'emailto'         => 'Komu',
'emailsubject'    => 'Předmět',
'emailmessage'    => 'Zpráva',
'emailsend'       => 'Odeslat',
'emailccme'       => 'Poslat kopii zprávy na můj e-mail',
'emailccsubject'  => 'Kopie Vaší zprávy pro uživatele $1: $2',
'emailsent'       => 'Zpráva odeslána',
'emailsenttext'   => 'Váš e-mail byl odeslán.',

# Watchlist
'watchlist'            => 'Sledované stránky',
'mywatchlist'          => 'Sledované stránky',
'watchlistfor'         => "(uživatele '''$1''')",
'nowatchlist'          => 'Na svém seznamu sledovaných stránek nemáte žádné položky.',
'watchlistanontext'    => 'Pro prohlížení či úpravu seznamu sledovaných stránek se musíte $1.',
'watchnologin'         => 'Nejste přihlášen(a)',
'watchnologintext'     => 'Pro sledování stránek se musíte [[Special:Userlogin|přihlásit]].',
'addedwatch'           => 'Přidáno k sledovaným',
'addedwatchtext'       => 'Stránka „[[:$1]]“ byla přidána mezi stránky, které [[Special:Watchlist|sledujete]]. Budoucí změny této stránky se objeví <b>tučně</b> v [[Special:Recentchanges|seznamu posledních změn]], aby bylo snadnější si jí povšimnout. Pokud budete později chtít stránku ze seznamu sledovaných smazat, klikněte na „Nesledovat tuto stránku“ v liště nástrojů.',
'removedwatch'         => 'Vyřazeno ze seznamu sledovaných stránek',
'removedwatchtext'     => 'Stránka „$1“ byla vyřazena z vašeho seznamu sledovaných stránek.',
'watch'                => 'Sledovat',
'watchthispage'        => 'Sledovat tuto stránku',
'unwatch'              => 'Nesledovat',
'unwatchthispage'      => 'Nesledovat tuto stránku',
'notanarticle'         => 'Toto není článek',
'watchnochange'        => 'Žádná ze sledovaných položek nebyla editována v době, která je zobrazena.',
'watchlist-details'    => 'Na svém seznamu sledovaných stránek máte $1 {{plural:$1|stránku|stránky|stránek}}',
'wlheader-enotif'      => '* Upozorňování e-mailem je zapnuto.',
'wlheader-showupdated' => "* Stránky, které se změnily od vaší poslední návštěvy, jsou zobrazeny '''tučně'''",
'watchmethod-recent'   => 'hledají se sledované stránky mezi posledními změnami',
'watchmethod-list'     => 'hledají se nejnovější editace sledovaných stránek',
'watchlistcontains'    => 'Na svém seznamu sledovaných stránek máte $1 {{plural:$1|položku|položky|položek}}.',
'iteminvalidname'      => 'Problém s položkou „$1“, neplatný název…',
'wlnote'               => 'Níže je {{plural:$1|poslední změna|poslední $1 změny|posledních $1 změn}} za {{plural:$2|poslední|poslední|posledních}} <b>$2</b> {{plural:$2|hodinu|hodiny|hodin}}.',
'wlshowlast'           => 'Ukázat posledních $1 hodin $2 dnů $3',
'watchlist-show-bots'  => 'ukázat roboty',
'watchlist-hide-bots'  => 'skrýt roboty',
'watchlist-show-own'   => 'ukázat mé editace',
'watchlist-hide-own'   => 'skrýt mé editace',
'watchlist-show-minor' => 'ukázat malé editace',
'watchlist-hide-minor' => 'skrýt malé editace',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Přidávám na seznam sledovaných stránek...',
'unwatching' => 'Odebírám ze seznamu sledovaných stránek...',

'enotif_mailer'                => 'Zasílač hlášení {{grammar:2sg|{{SITENAME}}}}',
'enotif_reset'                 => 'Vynulovat všechny příznaky (nastavit stav na „navštíveno“)',
'enotif_newpagetext'           => 'Toto je nová stránka.',
'enotif_impersonal_salutation' => 'Uživateli wiki {{SITENAME}}',
'changed'                      => 'upravil',
'created'                      => 'vytvořil',
'enotif_subject'               => '$PAGEEDITOR upravil stránku $PAGETITLE na {{grammar:6sg|{{SITENAME}}}}.',
'enotif_lastvisited'           => 'Vizte $1 pro seznam všech změn od minulé návštěvy.',
'enotif_lastdiff'              => 'Tuto změnu vizte na $1 .',
'enotif_anon_editor'           => 'anonymní uživatel $1',
'enotif_body'                  => 'Vážený uživateli $WATCHINGUSERNAME,

Ve $PAGEEDITDATE $CHANGEDORCREATED $PAGEEDITOR stránku $PAGETITLE, vizte aktuální verzi na $PAGETITLE_URL .

$NEWPAGE

Shrnutí editace: $PAGESUMMARY $PAGEMINOREDIT

Uživatele, který změnu provedl, můžete kontaktovat:
e-mailem: $PAGEEDITOR_EMAIL
na wiki: $PAGEEDITOR_WIKI

Dokud stránku nenavštívíte, nebudou vám zasílána další oznámení o změnách této stránky, případně do doby, než vynulujete příznaky ve svém seznamu sledovaných stránek.

	S pozdravem váš zasílač hlášení {{grammar:2sg|{{SITENAME}}}}

--
Pro změnu nastavení navštivte
{{fullurl:Special:Watchlist/edit}}

Rady a kontakt:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Smazat stránku',
'confirm'                     => 'Potvrdit',
'excontent'                   => 'obsah byl: „$1“',
'excontentauthor'             => 'obsah byl: „$1“ (a jediným přispěvatelem byl „[[Speciální:Contributions/$2|$2]]“)',
'exbeforeblank'               => 'obsah před vyprázdněním byl: „$1“',
'exblank'                     => 'stránka byla prázdná',
'confirmdelete'               => 'Potvrdit smazání',
'deletesub'                   => '(Maže se „$1“)',
'historywarning'              => ' Varování: Stránka, kterou chcete smazat, má historii:&nbsp;',
'confirmdeletetext'           => 'Chystáte se trvale smazat z databáze stránku nebo obrázek s celou jeho historií. Prosím potvrďte, že to opravdu chcete učinit, že si uvědomujete důsledky a že je to v souladu s [[{{MediaWiki:policy-url}}|pravidly]].',
'actioncomplete'              => 'Provedeno',
'deletedtext'                 => ' Stránka nebo soubor „$1“ byla smazána; $2 zaznamenává poslední smazání.',
'deletedarticle'              => 'maže „$1“',
'dellogpage'                  => 'Kniha smazaných stránek',
'dellogpagetext'              => 'Zde je seznam posledních smazaných stránek.',
'deletionlog'                 => 'Kniha smazaných stránek',
'reverted'                    => 'Obnovení předchozí verze',
'deletecomment'               => 'Důvod smazání',
'rollback'                    => 'Vrátit zpět editace',
'rollback_short'              => 'Vrátit zpět',
'rollbacklink'                => 'vrácení zpět',
'rollbackfailed'              => 'Nešlo vrátit zpět',
'cantrollback'                => 'Nelze vrátit zpět poslední editaci, neboť poslední přispěvatel je jediným autorem tohoto článku.',
'alreadyrolled'               => 'Nelze vrátit zpět poslední editaci [[:$1]] od [[User:$2|$2]] ([[User talk:$2|Diskuse]]), protože někdo jiný již článek editoval nebo vrátil tuto změnu zpět. Poslední editace byla od [[User:$3|$3]] ([[User talk:$3|Diskuse]]).',
'editcomment'                 => 'Shrnutí editace bylo: „<i>$1</i>“.', # only shown if there is an edit comment
'revertpage'                  => 'Editace uživatele „$2“ vrácena do předchozího stavu, jehož autorem je „$1“.',
'rollback-success'            => 'Editace uživatele $1 byla vrácena na poslední verzi od uživatele $2.',
'sessionfailure'              => 'Zřejmě je nějaký problém s vaším přihlášením;
vámi požadovaná činnost byla stornována jako prevence před neoprávněným přístupem.
Stiskněte tlačítko „zpět“, obnovte stránku, ze které jste přišli a zkuste činnost znovu.',
'protectlogpage'              => 'Kniha zamčení',
'protectlogtext'              => 'Níže je uveden seznam všech zamčení a odemčení stránek.',
'protectedarticle'            => 'zamyká „[[$1]]“',
'modifiedarticleprotection'   => 'mění zámek stránky "[[$1]]"',
'unprotectedarticle'          => 'odemyká „[[$1]]“',
'protectsub'                  => '(Zamyká se „$1“)',
'confirmprotect'              => 'Potvrdit zamčení',
'protectcomment'              => 'Důvod zamčení',
'protectexpiry'               => 'Čas vypršení',
'protect_expiry_invalid'      => 'Čas vypršení je chybný.',
'protect_expiry_old'          => 'Čas vypršení již minul.',
'unprotectsub'                => '(Odemyká se „$1“)',
'protect-unchain'             => 'Oddělené ovládání zámku přesunů',
'protect-text'                => 'Zde si můžete prohlédnout či změnit nastavení zámků stránky <strong>$1</strong>.',
'protect-locked-blocked'      => 'Nemůžete měnit nastavení zámků pokud jste zablokován. Současné nastavení pro tuto stránku je: <strong>$1</strong>:',
'protect-locked-dblock'       => 'Nastavení zámků nejde změnit kvůli zamčení databáze.
Současné nastavení pro tuto stránku je: <strong>$1</strong>:',
'protect-locked-access'       => 'Tento účet nemá povoleno měnit nastavení zámků.
Současné nastavení pro tuto stránku je: <strong>$1</strong>:',
'protect-cascadeon'           => 'Tato stránka je právě zamčena, protože je vložena do následujících stránek zamčených kaskádovým zámkem. Můžete změnit zámky pro tuto stránku, ale nebude to mít žádný vliv na kaskádové zamčení.',
'protect-default'             => '(odemčeno)',
'protect-fallback'            => 'Vyžaduje povolení "$1"',
'protect-level-autoconfirmed' => 'Pouze registrovaní',
'protect-level-sysop'         => 'Pouze správci',
'protect-summary-cascade'     => 'kaskádový',
'protect-expiring'            => 'vyprší $1 (UTC)',
'protect-cascade'             => 'Kaskádní zámek - zamkne všechny stránky vložené do této stránky.',
'restriction-type'            => 'Povolení:',
'restriction-level'           => 'Úroveň omezení:',
'pagesize'                    => '(bajtů)',

# Restrictions (nouns)
'restriction-edit' => 'editace',
'restriction-move' => 'přesun',

# Restriction levels
'restriction-level-sysop'         => 'zamčeno',
'restriction-level-autoconfirmed' => 'polozamčeno',
'restriction-level-all'           => 'kterákoliv úroveň',

# Undelete
'undelete'                 => 'Smazané stránky',
'undeletepage'             => 'Prohlédnout si a obnovit smazanou stránku',
'viewdeletedpage'          => 'Zobrazení smazané stránky',
'undeletepagetext'         => 'Tyto stránky jsou smazány, avšak dosud archivovány, a proto je možno je obnovit. Archiv může být pravidelně vyprazdňován.',
'undeleteextrahelp'        => "Pro úplné obnovení stránky s kompletní historií ponechte všechny čtverečky nezaškrtnuté a klikněte na '''''Obnovit'''''. Pro částečné obnovení zašrktněte čtverečky u obnovovaných revizí a klikněte na '''''Obnovit'''''. Kliknutí na '''''Reset''''' vyprázdní komentář a zruší všechna zaškrtnutí.",
'undeleterevisions'        => '$1 {{plural:$1|verze je archivována|verze jsou archivovány|verzí je archivováno}}',
'undeletehistory'          => 'Pokud stránku obnovíte, všechny revize budou v historii obnoveny. Pokud byla vytvořena nová stránka se stejným jménem jako smazaná, obnovené revize se zapíší na starší místo v historii a nová stránka nebude nahrazena.',
'undeletehistorynoadmin'   => 'Tato stránka byla smazána. Důvod smazání je uveden níže, spolu s informacemi o uživatelích, kteří tuto stránku před smazáním editovali. Samotný text stránky je dostupný pouze správcům.',
'undelete-revision'        => 'Smazaná verze článku $1 z $2:',
'undeleterevision-missing' => 'Nesprávná nebo chybějící revize. Možná máte špatný odkaz, nebo revize byla obnovena či odstraněna z archivu.',
'undeletebtn'              => 'Obnovit',
'undeletecomment'          => 'Komentář:',
'undeletedarticle'         => 'obnovuje „[[$1]]“',
'undeletedrevisions'       => '{{plural:$1|Obnovena $1 verze|Obnoveny $1 verze|Obnoveno $1 verzí}}',
'undeletedrevisions-files' => '{{plural:$1|Obnovena jedna verze|Obnoveny $1 verze|Obnoveno $1 verzí}} a $2 {{plural:$2|soubor|soubory|souborů}}.',
'undeletedfiles'           => '{{plural:$1|obnoven $1 soubor|obnoveny $1 soubory|obnoveno $1 souborů}}',
'cannotundelete'           => 'Obnovení se nepovedlo; někdo jiný pravděpodobně obnovil stránku dřív než Vy.',
'undeletedpage'            => "<big>'''$1 byla obnovena'''</big>

Záznam o posledních mazáních a obnoveních najdete v [[Special:Log/delete|knize smazaných stránek]].",
'undelete-header'          => 'Vizte nedávno smazané stránky v [[Special:Log/delete|knize smazaných stránek]].',
'undelete-search-box'      => 'Hledání smazaných stránek',
'undelete-search-prefix'   => 'Zobraz stránky začínající na:',
'undelete-search-submit'   => 'Hledat',
'undelete-no-results'      => 'Dotazu žádné smazané stránky neodpovídají.',

# Namespace form on various pages
'namespace'      => 'Jmenný prostor:',
'invert'         => 'Obrátit výběr',
'blanknamespace' => '(Hlavní)',

# Contributions
'contributions' => 'Příspěvky uživatele',
'mycontris'     => 'Mé příspěvky',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'Nenalezeny žádné změny vyhovující kritériím.',
'ucnote'        => 'Níže jsou uživatelovy poslední <strong>$1</strong> změny během {{plural:$2|posledního|posledních|posledních}} <strong>$2</strong> {{plural:$2|dne|dnů|dnů}}.',
'uclinks'       => 'Ukaž posledních $1 změn; ukaž posledních $2 dnů.',
'uctop'         => ' (aktuální)',
'month'         => 'Do měsíce:',
'year'          => 'Do roku:',

'sp-contributions-newest'      => 'Nejnovější',
'sp-contributions-oldest'      => 'Nejstarší',
'sp-contributions-newer'       => '$1 novějších',
'sp-contributions-older'       => '$1 starších',
'sp-contributions-newbies'     => 'Zobrazit příspěvky nově založených účtů',
'sp-contributions-newbies-sub' => 'Noví uživatelé',
'sp-contributions-blocklog'    => 'Kniha zablokování',
'sp-contributions-search'      => 'Zobrazení příspěvků',
'sp-contributions-username'    => 'IP adresa nebo uživatelské jméno:',
'sp-contributions-submit'      => 'Zobrazit',

'sp-newimages-showfrom' => 'Zobrazit nové obrázky počínaje od $1',

# What links here
'whatlinkshere'       => 'Odkazuje sem',
'whatlinkshere-title' => 'Stránky, které odkazují na $1',
'notargettitle'       => 'Bez cílové stránky',
'notargettext'        => 'Této funkci musíte určit cílovou stránku nebo uživatele.',
'linklistsub'         => '(Seznam odkazů)',
'linkshere'           => "Na '''[[:$1]]''' odkazují tyto stránky:",
'nolinkshere'         => "Žádná stránka na '''[[:$1]]''' neodkazuje.",
'nolinkshere-ns'      => "Ve zvoleném jmenném prostoru na '''[[:$1]]''' neodkazuje žádná stránka.",
'isredirect'          => 'přesměrování',
'istemplate'          => 'vložení',
'whatlinkshere-prev'  => '{{PLURAL:$1|předchozí|předchozí $1|předchozích $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|následující|následující $1|následujících $1}}',
'whatlinkshere-links' => '← odkazy',

# Block/unblock
'blockip'                     => 'Zablokovat uživatele',
'blockiptext'                 => 'Tento formulář slouží k zablokování editací z konkrétní IP adresy nebo uživatelského jména. Toto by mělo být používáno jen v souladu s [[{{MediaWiki:policy-url}}|pravidly]]. Udejte přesný důvod níže (například ocitujte, které stránky byly poškozeny). Pro odblokování se podívejte na [[Special:Ipblocklist|seznam blokovaných IP adres]].',
'ipaddress'                   => 'IP adresa',
'ipadressorusername'          => 'IP adresa nebo uživatelské jméno',
'ipbexpiry'                   => 'Čas vypršení',
'ipbreason'                   => 'Důvod',
'ipbreasonotherlist'          => 'Jiný důvod',
'ipbreason-dropdown'          => '*Časté důvody blokování
** Vkládání klamných informací
** Odstraňování obsahu stránek
** Vkládání reklamních externích odkazů
** Vkládání nesmyslů
** Zastrašování nebo vyhrožování
** Zneužívání více účtů
** Nevhodné uživatelské jméno',
'ipbanononly'                 => 'Zablokovat pouze anonymní uživatele',
'ipbcreateaccount'            => 'Nedovolit registraci nových uživatelů',
'ipbemailban'                 => 'Bránit uživateli v posílání e-mailů',
'ipbenableautoblock'          => 'Automaticky blokovat IP adresy používané tímto uživatelem',
'ipbsubmit'                   => 'Zablokovat',
'ipbother'                    => 'Jiný čas vypršení',
'ipboptions'                  => '2 hodiny:2 hours,1 den:1 day,3 dny:3 days,1 týden:1 week,2 týdny:2 weeks,1 měsíc:1 month,3 měsíce:3 months,6 měsíců:6 months,1 rok:1 year,do odvolání:infinite',
'ipbotheroption'              => 'jiný',
'ipbotherreason'              => 'Jiný nebo další důvod:',
'ipbhidename'                 => 'Skrýt uživatelské jméno nebo IP v knize zablokování a seznamu probíhajících bloků',
'badipaddress'                => 'Neplatná IP adresa',
'blockipsuccesssub'           => 'Zablokování uspělo',
'blockipsuccesstext'          => 'Uživatel „[[Special:Contributions/$1|$1]]“ je zablokován. <br />Podívejte se na [[Special:Ipblocklist|seznam zablokovaných]], [[Special:Log/block|kniha zablokování]] zaznamenává všechny podobné úkony.',
'ipb-edit-dropdown'           => 'Editace seznamu důvodů zablokování',
'ipb-unblock-addr'            => 'Odblokovat uživatele nebo IP $1',
'ipb-unblock'                 => 'Odblokovat uživatele nebo IP adresu',
'ipb-blocklist-addr'          => 'Zobrazit probíhající bloky pro $1',
'ipb-blocklist'               => 'Zobrazit probíhající bloky',
'unblockip'                   => 'Odblokovat uživatele',
'unblockiptext'               => 'Tímto formulářem je možno obnovit právo blokované IP adresy či uživatele opět přispívat do {{grammar:2sg|{{SITENAME}}}}.',
'ipusubmit'                   => 'Odblokovat',
'unblocked'                   => '[[User:$1|$1]] byl odblokován',
'unblocked-id'                => 'Blok $1 byl zrušen',
'ipblocklist'                 => 'Seznam blokovaných IP adres',
'ipblocklist-legend'          => 'Hledat zablokovaného uživatele',
'ipblocklist-username'        => 'Jméno nebo IP adresa:',
'ipblocklist-submit'          => 'Hledat',
'blocklistline'               => '$1 $2 zablokoval $3 ($4)',
'infiniteblock'               => 'do odvolání',
'expiringblock'               => 'čas vypršení: $1',
'anononlyblock'               => 'jen anonymové',
'noautoblockblock'            => 'bez autoblokování',
'createaccountblock'          => 'tvorba účtů zakázána',
'emailblock'                  => 'e-mail blokován',
'ipblocklist-empty'           => 'Seznam probíhajících bloků je prázdný.',
'ipblocklist-no-results'      => 'Požadovaná IP adresa nebo uživatelské jméno není blokováno.',
'blocklink'                   => 'zablokovat',
'unblocklink'                 => 'uvolnit',
'contribslink'                => 'příspěvky',
'autoblocker'                 => 'Automaticky zablokováno, protože sdílíte IP adresu s „$1“. Důvod zablokování tohoto uživatele: „$2“.',
'blocklogpage'                => 'Kniha zablokování',
'blocklogentry'               => 'blokuje „[[$1]]“ s časem vypršení $2 $3',
'blocklogtext'                => 'Toto je kniha úkonů blokování a odblokování uživatelů. Automaticky blokované IP adresy nejsou vypsány. Podívejte se na [[Special:Ipblocklist|seznam blokování IP]] s výčtem aktuálních zákazů a blokování.',
'unblocklogentry'             => 'odblokovává „$1“',
'block-log-flags-anononly'    => 'jen nepřihl.',
'block-log-flags-nocreate'    => 'vytváření účtů zablokováno',
'block-log-flags-noautoblock' => 'autoblokování vypnuto',
'block-log-flags-noemail'     => 'e-maily zablokovány',
'range_block_disabled'        => 'Blokování rozsahů IP adres je zakázáno.',
'ipb_expiry_invalid'          => 'Neplatný čas vypršení.',
'ipb_already_blocked'         => '„$1“ již je zablokován.',
'ip_range_invalid'            => 'Neplatný IP rozsah.',
'proxyblocker'                => 'Blokování proxy serverů',
'ipb_cant_unblock'            => 'Chyba: Blokování s ID $1 nebylo nalezeno. Uživatel již možná byl odblokován.',
'proxyblockreason'            => 'Vaše IP adresa byla zablokována, protože funguje jako otevřený proxy server. Kontaktujte svého poskytovatele internetového připojení nebo technickou podporu a informujte je o tomto vážném bezpečnostním problému.',
'proxyblocksuccess'           => 'Hotovo.',
'sorbsreason'                 => 'Vaše IP adresa je uvedena na seznamu DNSBL jako otevřený proxy server.',
'sorbs_create_account_reason' => 'Vaše IP adresa je uvedena na seznamu DNSBL jako otevřený proxy server. Z této adresy si nemůžete založit účet',

# Developer tools
'lockdb'              => 'Zamknout databázi',
'unlockdb'            => 'Odemknout databázi',
'lockdbtext'          => 'Pokud zamknete databázi, znemožníte ostatním editovat, upravovat nastavení, sledované stránky apod. Potvrďte, že to opravdu chcete udělat a že odemknete databázi hned po opravách.',
'unlockdbtext'        => ' Pokud odemknete databázi, umožníte ostatním editovat, upravovat nastavení, sledované stránky apod. Potvrďte, že to opravdu chcete udělat.',
'lockconfirm'         => 'Ano, opravdu chci zamknout databázi.',
'unlockconfirm'       => 'Ano, opravdu chci odemknout databázi.',
'lockbtn'             => 'Zamknout databázi',
'unlockbtn'           => 'Odemknout databázi',
'locknoconfirm'       => 'Nebylo zaškrtnuto políčko potvrzení.',
'lockdbsuccesssub'    => 'Databáze uzamčena',
'unlockdbsuccesssub'  => 'Databáze odemčena',
'lockdbsuccesstext'   => 'Databáze {{grammar:2sg|{{SITENAME}}}} byla úspěšně uzamčena.
<br />Nezapomeňte ji po dokončení údržby [[Special:Unlockdb|odemknout]].',
'unlockdbsuccesstext' => 'Databáze {{grammar:2sg|{{SITENAME}}}} je odemčena.',
'lockfilenotwritable' => 'Do souboru zámku databáze nelze zapisovat. Pro zamčení či odemčení databáze musí mít webový server právo zápisu do tohoto souboru.',
'databasenotlocked'   => 'Databáze není uzamčena.',

# Move page
'movepage'                => 'Přesunout stránku',
'movepagetext'            => 'Pomocí tohoto formuláře změníte název stránky a přesunete i celou její historii pod nový název. Původní název se stane přesměrováním na nový název. Odkazy na předchozí název <i>nebudou</i> změněny. <b>VAROVÁNÍ!</b> Toto může drastická a nečekaná změna pro oblíbené stránky. Ujistěte se, prosím, že chápete důsledky vašeho kroku před tím, než změnu provedete.',
'movepagetalktext'        => "Přidružená diskusní stránka, pokud existuje, bude automaticky přesunuta společně se stránkou, '''pokud:'''
* Dosud neexistuje neprázdná diskusní stránka pod novým jménem a
* nezrušíte křížek ve formuláři.

V těchto případech musíte přesunout nebo sloučit stránky manuálně, jestliže si to přejete.",
'movearticle'             => 'Přesunout stránku',
'movenologin'             => 'Nejste přihlášen(a)!',
'movenologintext'         => 'Pro přesouvání stránek se musíte [[Special:Userlogin|přihlásit]].',
'movenotallowed'          => 'Nemáte oprávnění k přesunu stránek na této wiki.',
'newtitle'                => 'Na nový název',
'move-watch'              => 'Sledovat tuto stránku',
'movepagebtn'             => 'Přesunout stránku',
'pagemovedsub'            => 'Úspěšně přesunuto',
'movepage-moved'          => "<big>'''Stránka „$1“ byla přesunuta na „$2“.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Takto nazvaná stránka již existuje, nebo Vámi zvolený název je neplatný. Zvolte jiný název.',
'talkexists'              => 'Stránka byla přesunuta úspěšně, ale diskusní stránka přesunuta být nemohla, neboť pod novým názvem již nějaká stránka existuje. Proveďte prosím ruční sloučení.',
'movedto'                 => 'přesunuto na',
'movetalk'                => 'Přesunout také diskusní stránku, existuje-li.',
'talkpagemoved'           => 'Diskusní stránka byla také přesunuta.',
'talkpagenotmoved'        => 'Diskusní stránka <strong>nebyla</strong> přesunuta.',
'1movedto2'               => 'Stránka [[$1]] přemístěna na stránku [[$2]]',
'1movedto2_redir'         => 'Stránka [[$1]] přemístěna na stránku [[$2]] s výměnou přesměrování',
'movelogpage'             => 'Kniha přesunů',
'movelogpagetext'         => 'Toto je záznam všech přesunů stránek.',
'movereason'              => 'Důvod',
'revertmove'              => 'vrátit',
'delete_and_move'         => 'Smazat a přesunout',
'delete_and_move_text'    => '==Je potřeba smazání==

Cílová stránka „[[$1]]“ již existuje. Přejete si ji smazat pro uvolnění místa pro přesun?',
'delete_and_move_confirm' => 'Ano, smazat cílovou stránku',
'delete_and_move_reason'  => 'Smazáno pro umožnění přesunu',
'selfmove'                => 'Původní a nový název jsou stejné; nelze stránku přesunout na sebe samu.',
'immobile_namespace'      => 'Nový název je speciálního druhu; z a do tohoto jmenného prostoru nelze stránky přesouvat.',

# Export
'export'            => 'Exportovat stránky',
'exporttext'        => 'Můžete exportovat text a historii editací některé stránky nebo sady stránek zabalené v XML. Výsledný soubor lze naimportovat do jiné wiki, která běží na software MediaWiki, pomocí stránky [[{{ns:Special}}:Import]].

Do níže uvedeného editačního pole zadejte názvy stránek, které chcete exportovat; každý řádek jeden název. Zvolte také, zda se mají exportovat i starší verze stránky včetně informací v historii editací, nebo jen aktuální verze s informací o poslední editaci.',
'exportcuronly'     => 'Zahrnout jen současnou verzi, ne plnou historii',
'exportnohistory'   => "----
'''Poznámka:''' export plných historií prostřednictvím tohoto formuláře byl z výkonnostních důvodů zakázán.",
'export-submit'     => 'Exportovat',
'export-addcattext' => 'Přidat stránky z kategorie:',
'export-addcat'     => 'Přidat',
'export-download'   => 'Nabídnout uložení jako soubor',

# Namespace 8 related
'allmessages'               => 'Všechna systémová hlášení',
'allmessagesname'           => 'Označení hlášení',
'allmessagesdefault'        => 'Původní text',
'allmessagescurrent'        => 'Aktuální text',
'allmessagestext'           => 'Toto je seznam všech hlášení dostupných ve jmenném prostoru MediaWiki.',
'allmessagesnotsupportedDB' => '{{ns:-1}}:AllMessages není podporováno, neboť wgUseDatabaseMessages je vypnuto.',
'allmessagesfilter'         => 'Filtr názvů hlášení:',
'allmessagesmodified'       => 'Zobrazit pouze upravená hlášení',

# Thumbnails
'thumbnail-more'           => 'Zvětšit',
'missingimage'             => '<b>Chybějící obrázek</b><br /><i>$1</i>',
'filemissing'              => 'Chybějící soubor',
'thumbnail_error'          => 'Chyba při vytváření náhledu: $1',
'thumbnail_invalid_params' => 'Neplatný parametr náhledu',
'thumbnail_dest_directory' => 'Nelze vytvořit cílový adresář',

# Special:Import
'import'                     => 'Importovat stránky',
'importinterwiki'            => 'Import mezi wiki',
'import-interwiki-text'      => 'Pro import zvolte zdrojovou wiki a název stránky. Data revizí a jména autorů budou zachována. Všechny importy se zaznamenávají do [[Special:Log/import|knihy importů]].',
'import-interwiki-history'   => 'Zkopírovat všechny historické verze této stránky',
'import-interwiki-submit'    => 'Importovat',
'import-interwiki-namespace' => 'Stránky přenést do jmenného prostoru:',
'importtext'                 => 'Prosím exportujte soubor ze zdrojové wiki pomocí nástroje {{ns:-1}}:Export, uložte ji na svůj disk a nahrajte ji sem.',
'importstart'                => 'Stránky se importují…',
'import-revision-count'      => '$1 {{PLURAL:$1|revize|revize|revizí}}',
'importnopages'              => 'Není co importovat.',
'importfailed'               => 'Import selhal: $1',
'importunknownsource'        => 'Neznámý typ zdroje importu',
'importcantopen'             => 'Nepodařilo se otevřít importní soubor',
'importbadinterwiki'         => 'Neplatný interwiki odkaz',
'importnotext'               => 'Prázdný nebo žádný text',
'importsuccess'              => 'Import byl úspěšný!',
'importhistoryconflict'      => 'Existuje konflikt mezi historiemi verzí. Možná, že tato stránka byla již importována dříve.',
'importnosources'            => 'Nebyly definovány žádné zdroje pro import mezi wiki a přímé načítání historie je vypnuto.',
'importnofile'               => 'Nebyl načten importní soubor.',
'importuploaderror'          => 'Nepodařilo se načíst importní soubor; možná jeho velikost přesahuje dovolenou mez.',

# Import log
'importlogpage'                    => 'Kniha importů',
'importlogpagetext'                => 'Na této stránce se zobrazují správcovské importy stránek včetně historie editací z jiných wiki.',
'import-logentry-upload'           => 'naimportoval [[$1]] načtením souboru',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revizi|revize|revizí}}',
'import-logentry-interwiki'        => 'přenesl $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revizi|revize|revizí}} z $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Moje uživatelská stránka',
'tooltip-pt-anonuserpage'         => 'Uživatelská stránka pro IP adresu, ze které editujete',
'tooltip-pt-mytalk'               => 'Moje diskusní stránka',
'tooltip-pt-anontalk'             => 'Diskuse o editacích provedených z této IP adresy',
'tooltip-pt-preferences'          => 'Moje preference',
'tooltip-pt-watchlist'            => 'Seznam stránek, jejichž změny sleduji',
'tooltip-pt-mycontris'            => 'Seznam mých příspěvků',
'tooltip-pt-login'                => 'Doporučujeme vám přihlásit se, ovšem není to povinné.',
'tooltip-pt-anonlogin'            => 'Doporučujeme vám přihlásit se, ovšem není to povinné.',
'tooltip-pt-logout'               => 'Odhlásit se',
'tooltip-ca-talk'                 => 'Diskuse ke stránce',
'tooltip-ca-edit'                 => 'Tuto stránku můžete editovat. Prosíme použijte tlačítko Ukázat náhled před uložením.',
'tooltip-ca-addsection'           => 'Přidat k této diskusi svůj komentář.',
'tooltip-ca-viewsource'           => 'Tato stránka je zamčena. Můžete si prohlédnout její zdrojový kód.',
'tooltip-ca-history'              => 'Starší verze této stránky.',
'tooltip-ca-protect'              => 'Zamknout tuto stránku.',
'tooltip-ca-delete'               => 'Smazat tuto stránku.',
'tooltip-ca-undelete'             => 'Obnovit editace této stránky provedené před jejím smazáním.',
'tooltip-ca-move'                 => 'Přesunout tuto stránku',
'tooltip-ca-watch'                => 'Přidat tuto stránku mezi sledované',
'tooltip-ca-unwatch'              => 'Vyjmout tuto stránku ze sledovaných',
'tooltip-search'                  => 'Prohledat tuto wiki',
'tooltip-p-logo'                  => 'Hlavní strana',
'tooltip-n-mainpage'              => 'Navštívit Hlavní stranu',
'tooltip-n-portal'                => 'O projektu, jak můžete pomoci, kde hledat',
'tooltip-n-currentevents'         => 'Informace o aktuálních událostech',
'tooltip-n-recentchanges'         => 'Seznam posledních změn na této wiki',
'tooltip-n-randompage'            => 'Přejít na náhodně vybranou stránku',
'tooltip-n-help'                  => 'Místo, kde najdete pomoc',
'tooltip-n-sitesupport'           => 'Podpořte nás',
'tooltip-t-whatlinkshere'         => 'Seznam všech wikistránek, které sem odkazují',
'tooltip-t-recentchangeslinked'   => 'Nedávné změny stránek, na které je odkazováno',
'tooltip-feed-rss'                => 'RSS kanál pro tuto stránku',
'tooltip-feed-atom'               => 'Atom kanál pro tuto stránku',
'tooltip-t-contributions'         => 'Prohlédnout si seznam příspěvků tohoto uživatele',
'tooltip-t-emailuser'             => 'Poslat e-mail tomuto uživateli',
'tooltip-t-upload'                => 'Nahrát obrázky či jiná multimédia',
'tooltip-t-specialpages'          => 'Seznam všech speciálních stránek',
'tooltip-t-print'                 => 'Tato stránka v podobě vhodné k tisku',
'tooltip-t-permalink'             => 'Trvalý odkaz na tuto verzi této stránky',
'tooltip-ca-nstab-main'           => 'Zobrazit článek',
'tooltip-ca-nstab-user'           => 'Zobrazit uživatelovu stránku',
'tooltip-ca-nstab-media'          => 'Zobrazit stránku souboru',
'tooltip-ca-nstab-special'        => 'Toto je speciální stránka, kterou nelze editovat.',
'tooltip-ca-nstab-project'        => 'Zobrazit stránku o wiki.',
'tooltip-ca-nstab-image'          => 'Zobrazit stránku obrázku.',
'tooltip-ca-nstab-mediawiki'      => 'Zobrazit systémovou zprávu.',
'tooltip-ca-nstab-template'       => 'Zobrazit šablonu.',
'tooltip-ca-nstab-help'           => 'Zobrazit stránku nápovědy.',
'tooltip-ca-nstab-category'       => 'Zobrazit kategorii.',
'tooltip-minoredit'               => 'Označit změnu jako malou editaci',
'tooltip-save'                    => 'Kliknutím na toto tlačítko uložíte na trvalo své změny',
'tooltip-preview'                 => 'Kliknutím na toto tlačítko zobrazíte náhled svých změn; používejte ho, prosíme, před uložením!',
'tooltip-diff'                    => 'Zobrazit, jaké změny jste v textu provedli.',
'tooltip-compareselectedversions' => 'Podívat se na rozdíly mezi dvěma vybranými verzemi této stránky.',
'tooltip-watch'                   => 'Přidat stránku do seznamu sledovaných',
'tooltip-recreate'                => 'Obnovit stánku i když byla smazána',
'tooltip-upload'                  => 'Odesílání souboru',

# Stylesheets
'common.css'   => '/* Zde uvedené CSS bude ovlivňovat všechny styly */',
'monobook.css' => '/* Zde uvedené CSS bude ovlivňovat pouze styl „monobook“ */',

# Scripts
'common.js'   => '/* Zde uvedený JavaScript bude použit pro všechny uživatele při načtení každé stránky.  */',
'monobook.js' => '/* Zastaralé; použijte [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Na tomto serveru je vypnuto generování metadat Dublin Core RDF.',
'nocreativecommons' => 'Na tomto server je vypnuto generování metadat Creative Commons RDF.',
'notacceptable'     => 'Tento wiki server není schopen poskytnout data ve formátu, který by váš klient byl schopen přečíst.',

# Attribution
'anonymous'        => 'Anonymní uživatel(é) {{GRAMMAR:2sg|{{SITENAME}}}}',
'siteuser'         => 'uživatel {{grammar:2sg|{{SITENAME}}}} $1',
'lastmodifiedatby' => 'Tuto stránku naposledy měnil $3 v $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'a',
'othercontribs'    => 'Do textu přispěli $1.',
'others'           => 'ostatní',
'siteusers'        => 'uživatel(é) {{grammar:2sg|{{SITENAME}}}} $1',
'creditspage'      => 'Zásluhy za stránku',
'nocredits'        => 'K této stránce neexistuje informace o zásluhách.',

# Spam protection
'spamprotectiontitle'    => 'Protispamový filtr',
'spamprotectiontext'     => 'Stránka, kterou jste se pokusil(a) uložit, byla zablokována protispamovým filtrem. Pravděpodobnou příčinou je odkaz na externí stránky. Může vás zajímat následující regulární výraz, který označuje v současné době blokované stránky:',
'spamprotectionmatch'    => 'Následující text spustil náš filtr proti spamu: $1',
'subcategorycount'       => 'Tato kategorie obsahuje $1 {{plural:$1|podkategorii|podkategorie|podkategorií}}.',
'categoryarticlecount'   => 'Tato kategorie obsahuje $1 {{plural:$1|článek|články|článků}}.',
'category-media-count'   => 'Tato kategorie obsahuje {{plural:$1|jeden soubor|$1 soubory|$1 souborů}}.',
'listingcontinuesabbrev' => 'pokrač.',
'spambot_username'       => 'Systémový čistič spamu',
'spam_reverting'         => 'Revert na poslední verzi neobsahující odkazy na $1',
'spam_blanking'          => 'Všechny revize obsahovaly odkazy na $1, vyprázdněno',

# Info page
'infosubtitle'   => 'Informace o stránce',
'numedits'       => 'Počet editací (článek): $1',
'numtalkedits'   => 'Počet editací (diskusní stránka): $1',
'numwatchers'    => 'Počet sledujících uživatelů: $1',
'numauthors'     => 'Počet různých autorů (článek): $1',
'numtalkauthors' => 'Počet různých autorů (diskusní stránka): $1',

# Math options
'mw_math_png'    => 'Vždy jako PNG',
'mw_math_simple' => 'Jednoduché jako HTML, jinak PNG',
'mw_math_html'   => 'HTML pokud je to možné, jinak PNG',
'mw_math_source' => 'Ponechat jako TeX (pro textové prohlížeče)',
'mw_math_modern' => 'Doporučené nastavení pro moderní prohlížeče',
'mw_math_mathml' => 'MathML pokud je podporováno (experimentální)',

# Patrolling
'markaspatrolleddiff'                 => 'Označit jako prověřené',
'markaspatrolledtext'                 => 'Označit tento článek jako prověřený',
'markedaspatrolled'                   => 'Označeno jako prověřené',
'markedaspatrolledtext'               => 'Vybraná verze byla označena jako prověřená.',
'rcpatroldisabled'                    => 'Hlídka posledních změn vypnuta',
'rcpatroldisabledtext'                => 'Hlídka posledních změn je momentálně vypnuta.',
'markedaspatrollederror'              => 'Nelze označit za prověřené',
'markedaspatrollederrortext'          => 'Musíte zvolit revizi, která má být označena jako prověřená.',
'markedaspatrollederror-noautopatrol' => 'Nemáte dovoleno označovat vlastní editace jako prověřené.',

# Patrol log
'patrol-log-page' => 'Kniha prověřených editací',
'patrol-log-line' => 'označit revizi $1 článku $2 za prověřenou $3',
'patrol-log-auto' => '(automaticky)',

# Image deletion
'deletedrevision' => 'Smazána stará revize $1',

# Browsing diffs
'previousdiff' => '← Předchozí porovnání',
'nextdiff'     => 'Následující porovnání →',

# Media information
'mediawarning'         => "'''Upozornění''': Tento soubor může obsahovat škodlivý kód, spuštěním můžete ohrozit svůj počítač.<hr />",
'imagemaxsize'         => 'Omezit obrázky na stránkách s popiskem na:',
'thumbsize'            => 'Velikost náhledu:',
'widthheightpage'      => '$1×$2, $3 {{plural:$3|stránka|stránky|stránek}}',
'file-info'            => '(velikost souboru: $1, MIME typ: $2)',
'file-info-size'       => '(rozměr: $1 × $2 pixelů, velikost souboru: $3, MIME typ: $4)',
'file-nohires'         => '<small>Větší rozlišení není k dispozici.</small>',
'svg-long-desc'        => '(soubor SVG, nominální rozměr: $1 × $2 pixelů, velikost souboru: $3)',
'show-big-image'       => 'Obrázek ve vyšším rozlišení',
'show-big-image-thumb' => '<small>Velikost tohoto náhledu je: $1 × $2 pixelů</small>',

# Special:Newimages
'newimages'    => 'Galerie nových obrázků',
'showhidebots' => '($1 roboty)',
'noimages'     => 'Není co zobrazit.',

# Bad image list
'bad_image_list' => 'Tato stránka má následující formát:

Obsahuje pouze seznam s odrážkami (řádka začíná s *). První odkaz na řádce musí být odkaz na soubor, kterému se zakazuje zobrazování v článcích. Všechny další odkazy na řádce jsou považovány za výjimky, tedy články ve kterých se obrázek může zobrazit.',

# Metadata
'metadata-help'     => 'Tento soubor obsahuje dodatečné informace, poskytnuté zřejmě digitálním fotoaparátem nebo scannerem, kterým byl pořízen. Pokud byl soubor od té doby změněn, některé údaje mohou být neplatné.',
'metadata-expand'   => 'Zobrazit podrobnosti',
'metadata-collapse' => 'Skrýt podrobnosti',
'metadata-fields'   => 'Pole EXIF uvedená v této zprávě budou na stránce s popisem vypsána vždy. Pro zobrazení ostatních bude třeba kliknout na „zobrazit podrobnosti“.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Šířka',
'exif-imagelength'                 => 'Výška',
'exif-bitspersample'               => 'Bitů na složku',
'exif-compression'                 => 'Druh komprese',
'exif-photometricinterpretation'   => 'Barevný prostor',
'exif-orientation'                 => 'Orientace',
'exif-samplesperpixel'             => 'Počet složek',
'exif-planarconfiguration'         => 'Uspořádání dat',
'exif-ycbcrsubsampling'            => 'Poměr podvzorkování Y ku C',
'exif-ycbcrpositioning'            => 'Umístění Y a C',
'exif-xresolution'                 => 'Rozlišení obrázku na šířku',
'exif-yresolution'                 => 'Rozlišení obrázku na výšku',
'exif-resolutionunit'              => 'Jednotky rozlišení',
'exif-stripoffsets'                => 'Umístění dat obrázku',
'exif-rowsperstrip'                => 'Počet řádků na pás',
'exif-stripbytecounts'             => 'Počet bajtů na komprimovaný pás',
'exif-jpeginterchangeformat'       => 'Ofset k JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Počet bajtů JPEG dat',
'exif-transferfunction'            => 'Přenosová funkce',
'exif-whitepoint'                  => 'Chroma bílého bodu',
'exif-primarychromaticities'       => 'Chroma primárních barev',
'exif-ycbcrcoefficients'           => 'Koeficienty matice pro transformaci barevných prostorů',
'exif-referenceblackwhite'         => 'Světlost referenčního černého a bílého bodu',
'exif-datetime'                    => 'Datum a čas vytvoření obrázku',
'exif-imagedescription'            => 'Název obrázku',
'exif-make'                        => 'Značka fotoaparátu',
'exif-model'                       => 'Model fotoaparátu',
'exif-software'                    => 'Použitý software',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Držitel autorských práv',
'exif-exifversion'                 => 'Verze Exif',
'exif-flashpixversion'             => 'Podporovaná verze Flashpix',
'exif-colorspace'                  => 'Barevný prostor',
'exif-componentsconfiguration'     => 'Význam jednotlivých složek',
'exif-compressedbitsperpixel'      => 'Komprimační režim',
'exif-pixelydimension'             => 'Platná šířka obrazu',
'exif-pixelxdimension'             => 'Platná výška obrazu',
'exif-makernote'                   => 'Poznámky výrobce',
'exif-usercomment'                 => 'Uživatelské poznámky',
'exif-relatedsoundfile'            => 'Související zvukový soubor',
'exif-datetimeoriginal'            => 'Datum a čas pořízení obrázku',
'exif-datetimedigitized'           => 'Datum a čas digitalizace',
'exif-subsectime'                  => 'zlomky sekundy pro DateTime',
'exif-subsectimeoriginal'          => 'zlomky sekundy pro DateTimeOriginal',
'exif-subsectimedigitized'         => 'zlomky sekundy pro DateTimeDigitized',
'exif-exposuretime'                => 'Expozice',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Clona',
'exif-exposureprogram'             => 'Expoziční program',
'exif-spectralsensitivity'         => 'Spektrální citlivost',
'exif-isospeedratings'             => 'Nastavení ISO citlivosti',
'exif-oecf'                        => 'Optoelectronická převodní funkce (OECF)',
'exif-shutterspeedvalue'           => 'Rychlost závěrky',
'exif-aperturevalue'               => 'Clona',
'exif-brightnessvalue'             => 'Světlost',
'exif-exposurebiasvalue'           => 'Změna expozice',
'exif-maxaperturevalue'            => 'Nejmenší clona',
'exif-subjectdistance'             => 'Vzdálenost k předmětu',
'exif-meteringmode'                => 'Způsob měření',
'exif-lightsource'                 => 'Světelný zdroj',
'exif-flash'                       => 'Blesk',
'exif-focallength'                 => 'Ohnisková vzdálenost',
'exif-subjectarea'                 => 'Umístění předmětu',
'exif-flashenergy'                 => 'Výkon blesku',
'exif-spatialfrequencyresponse'    => 'Odezva prostorové frekvence',
'exif-focalplanexresolution'       => 'X rozlišení ohniskové roviny',
'exif-focalplaneyresolution'       => 'Y rozlišení ohniskové roviny',
'exif-focalplaneresolutionunit'    => 'Jednotka rozlišení ohniskové roviny',
'exif-subjectlocation'             => 'Umístění předmětu',
'exif-exposureindex'               => 'Expoziční index',
'exif-sensingmethod'               => 'Senzor',
'exif-filesource'                  => 'Zdroj souboru',
'exif-scenetype'                   => 'Druh scény',
'exif-cfapattern'                  => 'Geometrické uspořádání senzoru',
'exif-customrendered'              => 'Uživatelské zpracování',
'exif-exposuremode'                => 'Expoziční režim',
'exif-whitebalance'                => 'Vyvážení bílé',
'exif-digitalzoomratio'            => 'Digitální zoom',
'exif-focallengthin35mmfilm'       => 'Ekvivalent ohniskové délky pro 35mm film',
'exif-scenecapturetype'            => 'Druh scény',
'exif-gaincontrol'                 => 'Úprava světlosti',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Sytost',
'exif-sharpness'                   => 'Ostrost',
'exif-devicesettingdescription'    => 'Popis nastavení zařízení',
'exif-subjectdistancerange'        => 'Vzdálenost k předmětu',
'exif-imageuniqueid'               => 'Unikátní ID obrázku',
'exif-gpsversionid'                => 'Verze GPS tagu',
'exif-gpslatituderef'              => 'Severní/jižní zeměpisná šířka',
'exif-gpslatitude'                 => 'Zeměpisná šířka',
'exif-gpslongituderef'             => 'Východní/západní zeměpisná délka',
'exif-gpslongitude'                => 'Zeměpisná délka',
'exif-gpsaltituderef'              => 'Nad/podmořská výška/hloubka',
'exif-gpsaltitude'                 => 'Nadmořská výška',
'exif-gpstimestamp'                => 'GPS čas (podle atomových hodin)',
'exif-gpssatellites'               => 'Satelity použité pro měření',
'exif-gpsstatus'                   => 'Stav přijímače',
'exif-gpsmeasuremode'              => 'Režim měření',
'exif-gpsdop'                      => 'Přesnost měření',
'exif-gpsspeedref'                 => 'Jednotka rychlosti',
'exif-gpsspeed'                    => 'Rychlost GPS přijímače',
'exif-gpstrackref'                 => 'Reference pro směr pohybu',
'exif-gpstrack'                    => 'Směr pohybu',
'exif-gpsimgdirectionref'          => 'Reference pro orientaci obrázku',
'exif-gpsimgdirection'             => 'Orientace obrázku',
'exif-gpsmapdatum'                 => 'Použitý geodetický systém',
'exif-gpsdestlatituderef'          => 'Severní/jižní zeměpisná šířka předmětu',
'exif-gpsdestlatitude'             => 'Zeměpisná šířka předmětu',
'exif-gpsdestlongituderef'         => 'Východní/západní zeměpisná délka předmětu',
'exif-gpsdestlongitude'            => 'Zeměpisná délka předmětu',
'exif-gpsdestbearingref'           => 'Reference pro směr k předmětu',
'exif-gpsdestbearing'              => 'Směr k předmětu',
'exif-gpsdestdistanceref'          => 'Jednotka vzdálenosti k předmětu',
'exif-gpsdestdistance'             => 'Vzdálenost k předmětu',
'exif-gpsprocessingmethod'         => 'Označení metody zpracování GPS dat',
'exif-gpsareainformation'          => 'Označení GPS oblasti',
'exif-gpsdatestamp'                => 'Datum podle GPS',
'exif-gpsdifferential'             => 'Diferenciální korekce GPS',

# EXIF attributes
'exif-compression-1' => 'Nekomprimovaný',

'exif-unknowndate' => 'neznámé datum',

'exif-orientation-1' => 'Normální', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Vodorovně převráceno', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Otočeno o 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Svisle převráceno', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Otočeno o 90° proti směru hodinových ručiček a svisle převráceno', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Otočeno o 90° ve směru hodinových ručiček', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Otočeno o 90° ve směru hodinových ručiček a svisle převráceno', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Otočeno o 90° proti směru hodinových ručiček', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky',
'exif-planarconfiguration-2' => 'planar',

'exif-componentsconfiguration-0' => 'neexistuje',

'exif-exposureprogram-0' => 'Neuvedeno',
'exif-exposureprogram-1' => 'Ruční',
'exif-exposureprogram-2' => 'Normální',
'exif-exposureprogram-3' => 'Priorita clony',
'exif-exposureprogram-4' => 'Priorita závěrky',
'exif-exposureprogram-5' => 'Kreativní (lepší hloubka ostrosti)',
'exif-exposureprogram-6' => 'Akce (rychlejší závěrka)',
'exif-exposureprogram-7' => 'Portrét (detailní fotografie s neostrým pozadím)',
'exif-exposureprogram-8' => 'Krajina (fotografie krajiny s ostrým pozadím)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'Není známo',
'exif-meteringmode-1'   => 'Průměrové',
'exif-meteringmode-2'   => 'Vážený průměr',
'exif-meteringmode-3'   => 'Bodové',
'exif-meteringmode-4'   => 'Zónové',
'exif-meteringmode-5'   => 'Vzorové',
'exif-meteringmode-6'   => 'Částečné',
'exif-meteringmode-255' => 'Jiné',

'exif-lightsource-0'   => 'Není známo',
'exif-lightsource-1'   => 'Denní světlo',
'exif-lightsource-2'   => 'Fluorescentní',
'exif-lightsource-3'   => 'Žárovka',
'exif-lightsource-4'   => 'Blesk',
'exif-lightsource-9'   => 'Jasno',
'exif-lightsource-10'  => 'Zamračeno',
'exif-lightsource-11'  => 'Stín',
'exif-lightsource-12'  => 'Zářivka denní světlo (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Zářivka bílé denní světlo (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Zářivka studená bílá (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Bílá zářivka (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardní osvětlení A',
'exif-lightsource-18'  => 'Standardní osvětlení B',
'exif-lightsource-19'  => 'Standardní osvětlení C',
'exif-lightsource-24'  => 'ISO studiová žárovka',
'exif-lightsource-255' => 'Jiný světelný zdroj',

'exif-focalplaneresolutionunit-2' => 'in',

'exif-sensingmethod-1' => 'Není známo',
'exif-sensingmethod-2' => 'Jednočipový plošný senzor',
'exif-sensingmethod-3' => 'Dvoučipový plošný senzor',
'exif-sensingmethod-4' => 'Tříčipový plošný senzor',
'exif-sensingmethod-5' => 'Sekvenční plošný senzor',
'exif-sensingmethod-7' => 'Trilineární senzor',
'exif-sensingmethod-8' => 'Sekvenční lineární senzor',

'exif-filesource-3' => 'Digitální fotoaparát',

'exif-scenetype-1' => 'Přímo fotografováno',

'exif-customrendered-0' => 'Běžné zpracování',
'exif-customrendered-1' => 'Uživatelské zpracování',

'exif-exposuremode-0' => 'Automatická expozice',
'exif-exposuremode-1' => 'Ruční expozice',
'exif-exposuremode-2' => 'Bracketing',

'exif-whitebalance-0' => 'Automatické vyvážení bílé',
'exif-whitebalance-1' => 'Ruční vyvážení bílé',

'exif-scenecapturetype-0' => 'Standardní',
'exif-scenecapturetype-1' => 'Na šířku',
'exif-scenecapturetype-2' => 'Na výšku',
'exif-scenecapturetype-3' => 'Noční scéna',

'exif-gaincontrol-0' => 'Žádná',
'exif-gaincontrol-1' => 'Mírné zvýšení jasu',
'exif-gaincontrol-2' => 'Výrazné zvýšení jasu',
'exif-gaincontrol-3' => 'Mírné snížení jasu',
'exif-gaincontrol-4' => 'Výrazné snížení jasu',

'exif-contrast-0' => 'Normální',
'exif-contrast-1' => 'Měkký',
'exif-contrast-2' => 'Tvrdý',

'exif-saturation-0' => 'Normální',
'exif-saturation-1' => 'Nízká sytost',
'exif-saturation-2' => 'Vysoká sytost',

'exif-sharpness-0' => 'Normální',
'exif-sharpness-1' => 'Měkká',
'exif-sharpness-2' => 'Tvrdá',

'exif-subjectdistancerange-0' => 'Není známo',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Detail',
'exif-subjectdistancerange-3' => 'Pohled zdálky',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Severní šířka',
'exif-gpslatitude-s' => 'Jižní šířka',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Východní délka',
'exif-gpslongitude-w' => 'Západní délka',

'exif-gpsstatus-a' => 'Probíhá měření',
'exif-gpsstatus-v' => 'Měření mimo provoz',

'exif-gpsmeasuremode-2' => 'Dvourozměrné měření',
'exif-gpsmeasuremode-3' => 'Trojrozměrné měření',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'kt',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravý kurs',
'exif-gpsdirection-m' => 'Magnetický kurs',

# External editor support
'edit-externally'      => 'Editovat tento soubor v externím programu',
'edit-externally-help' => 'Více informací najdete v [http://meta.wikimedia.org/wiki/Help:External_editors nápovědě pro nastavení].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'všechny',
'imagelistall'     => 'všechny',
'watchlistall2'    => 'všechny',
'namespacesall'    => 'všechny',
'monthsall'        => 'všechny',

# E-mail address confirmation
'confirmemail'            => 'Potvrzení e-mailové adresy',
'confirmemail_noemail'    => 'Ve svém [[Special:Preferences|uživatelském nastavení]] jste nezadali platnou e-mailovou adresu.',
'confirmemail_text'       => 'Tato wiki vyžaduje, abyste potvrdili svou e-mailovou adresu
před využíváním některých funkcí. Kliknutím na níže umístěné tlačítko dojde k odeslání
potvrzovacího e-mailu na vámi uvedeno adresu. Tento mail obsahuje odkaz a potvrzovací kód;
přejděte na odkazovanou stránku svým internetovým prohlížečem, tím potvrdíte, že
zadaná adresa je platná.',
'confirmemail_pending'    => '<div class="error">
Potvrzovací kód vám byl již zaslán na váš e-mail.
Pokud jste si založili účet před chvílí, vyčkejte, prosíme, několik minut na doručení kódu dříve, než požádáte o nový.
</div>',
'confirmemail_send'       => 'Odeslat potvrzovací kód',
'confirmemail_sent'       => 'Potvrzovací e-mail byl odeslán',
'confirmemail_oncreate'   => 'Na vaši e-mailovou adresu byl zaslán potvrzovací kód.
Tento kód není zapotřebí k přihlášení, ale budete ho potřebovat k aktivaci některých funkcí založených na využití e-mailu.',
'confirmemail_sendfailed' => 'Nepodařilo se odeslat potvrzovací e-mail. Zkontrolujte, zda adresa neobsahuje chybné znaky.

Poštovní program hlásí: $1',
'confirmemail_invalid'    => 'Neplatný potvrzovací kód. Možná již vypršela platnost kódu.',
'confirmemail_needlogin'  => 'Pro potvrzení své e-mailové adresy se musíte $1.',
'confirmemail_success'    => 'Vaše e-mailová adresa byla potvrzena. Nyní se můžete přihlásit a používat wiki.',
'confirmemail_loggedin'   => 'Vaše e-mailová adresa byla potvrzena.',
'confirmemail_error'      => 'Nepodařilo se uložit vaše potvrzení.',
'confirmemail_subject'    => 'Potvrzení e-mailové adresy pro {{grammar:4sg|{{SITENAME}}}}',
'confirmemail_body'       => 'Někdo (patrně vy, z IP adresy $1) si registroval účet se jménem "$2" a touto e-mailovou adresou na {{grammar:6sg|{{SITENAME}}}}.

Pokud si přejete aktivovat e-mailové funkce na {{grammar:6sg|{{SITENAME}}}}, tak pro potvrzení,
že tato adresa opravdu patří vám, přejděte svým internetovým prohlížečem na následující adresu:

$3

Pokud jste o toto potvrzení *nežádali*, neklikejte na předchozí odkaz. Platnost tohoto potvrzovacího
kódu vyprší $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Vkládání šablon mezi wiki je vypnuto]',
'scarytranscludefailed'   => '[Nepodařilo se načíst šablonu pro $1; je mi líto]',
'scarytranscludetoolong'  => '[Příliš dlouhé URL; je mi líto]',

# Trackbacks
'trackbackbox'      => "<div id='mw_trackbacks'>
Zpětné odkazy k tomuto článku:<br />
$1
</div>",
'trackbackremove'   => ' ([$1 Smazat])',
'trackbacklink'     => 'Zpětný odkaz',
'trackbackdeleteok' => 'Zpětný odkaz byl úspěšně smazán.',

# Delete conflict
'deletedwhileediting' => 'Upozornění: V průběhu vaší editace byla tato stránka smazána!',
'confirmrecreate'     => 'Uživatel [[User:$1|$1]] ([[User talk:$1|diskuse]]) tuto stránku smazal poté, co jste začali editovat, s odůvodněním:
: „$2“
Opravdu si přejete znovu tuto stránku založit?',
'recreate'            => 'Znovu založit',

# HTML dump
'redirectingto' => 'Přesměrovává se na [[$1]]…',

# action=purge
'confirm_purge' => 'Aktualizovat cachovanou verzi této stránky?

$1',

# AJAX search
'searchcontaining' => "Hledat články obsahující ''$1''.",
'searchnamed'      => "Hledat články pojmenované ''$1''.",
'articletitles'    => "Články začínající ''$1''",
'hideresults'      => 'Skrýt výsledky',

# Multipage image navigation
'imgmultipageprev'   => '&larr; předchozí stránka',
'imgmultipagenext'   => 'následující stránka &rarr;',
'imgmultigo'         => 'Jít!',
'imgmultigotopre'    => 'Přejít na stránku',
'imgmultiparseerror' => 'Soubor je zřejmě chybný nebo poškozený, nelze vyhledat seznam stránek.',

# Table pager
'ascending_abbrev'         => 'vzestupně',
'descending_abbrev'        => 'sestupně',
'table_pager_next'         => 'Následující stránka',
'table_pager_prev'         => 'Předchozí stránka',
'table_pager_first'        => 'První stránka',
'table_pager_last'         => 'Poslední stránka',
'table_pager_limit'        => 'Zobrazit $1 položek na stránku',
'table_pager_limit_submit' => 'Zobrazit',
'table_pager_empty'        => 'Nic nebylo nalezeno',

# Auto-summaries
'autosumm-blank'   => 'Vyprázdnění stránky',
'autosumm-replace' => 'Obsah stránky nahrazen textem „$1“',
'autoredircomment' => 'Přesměrováno na [[$1]]',
'autosumm-new'     => 'Nová stránka: $1',

# Size units
'size-kilobytes' => '$1 kB',

# Live preview
'livepreview-loading' => 'Nahrávám…',
'livepreview-ready'   => 'Nahrávám… Připraven!',
'livepreview-failed'  => 'Rychlý náhled není dostupný! Použijte normální náhled.',
'livepreview-error'   => 'Neúspěšné spojení: $1 "$2". Použijte normální náhled.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Změny za posledních $1 sekund nemusí být v tomto seznamu zobrazeny.',
'lag-warn-high'   => 'Protože je databázový server právě mimořádně vytížen, nemusí být změny za posledních $1 sekund v tomto seznamu zobrazeny.',

# Watchlist editor
'watchlistedit-numitems'       => 'Váš seznam sledovaných stránek obsahuje (mimo diskusních stránek) {{PLURAL:$1|1 položku|$1 položky|$1 položek}}.',
'watchlistedit-noitems'        => 'Váš seznam sledovaných stránek neobsahuje žádnou položku.',
'watchlistedit-clear-title'    => 'Vyprázdnění seznamu sledovaných stránek',
'watchlistedit-clear-legend'   => 'Vyprázdnění seznamu sledovaných stránek',
'watchlistedit-clear-confirm'  => 'Tato akce smaže všechny položky vašeho seznamu sledovaných stránek. Je to vaším úmyslem? Jednotlivé položky můžete odstranit v [[Special:Watchlist/edit|editoru sledovaných stránek]].',
'watchlistedit-clear-submit'   => 'Vyprázdnit',
'watchlistedit-clear-done'     => 'Váš seznam sledovaných stránek byl vyprázdněn. Všechny jeho položky byly odstraněny.',
'watchlistedit-normal-title'   => 'Editace seznamu sledovaných stránek',
'watchlistedit-normal-legend'  => 'Odstranění položek ze seznamu sledovaných stránek',
'watchlistedit-normal-explain' => 'Položky vašeho seznamu sledovaných stránek jsou zobrazeny níže. Ty, které chcete ze seznamu odstranit, označte zaškrtnutím a poté stiskněte tlačítko Odstranit položky. Editovat seznam sledovaných stránek můžete také [[Special:Watchlist/raw|v textové podobě]], případně můžete [[Special:Watchlist/clear|odstranit všechny položky seznamu]].',
'watchlistedit-normal-submit'  => 'Odstranit položky',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Byla odstraněna 1 položka|Byly odstraněny $1 položky|Bylo odstraněno $1 položek}} z Vašeho seznamu sledovaných stránek:',
'watchlistedit-raw-title'      => 'Textový režim editace seznamu',
'watchlistedit-raw-legend'     => 'Editace textového souboru sledovaných stránek',
'watchlistedit-raw-explain'    => 'Položky na vašem seznamu sledovaných stránek jsou zobrazeny dole. Můžete je přidávat, odstraňovat či měnit v zobrazeném seznamu – jedna řádka je jedna položka. Pro uložení změn stiskněte tlačítko Aktualizovat seznam. Seznam editovaných stránek můžete také [[Special:Watchlist/edit|editovat ve standardním editoru]].',
'watchlistedit-raw-titles'     => 'Položky:',
'watchlistedit-raw-submit'     => 'Aktualizovat seznam',
'watchlistedit-raw-done'       => 'Seznam vašich sledovaných stránek byl aktualizován.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Byla přidána 1 položka|Byly přidány $1 položky|Bylo přidáno $1 položek}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Odstraněna položka|Ostraněny $1 položky|Odstraněno $1 položek}}:',

# Watchlist editing tools
'watchlisttools-view'  => 'Zobrazit změny sledovaných stránek',
'watchlisttools-edit'  => 'Zobrazit a editovat seznam sledovaných stránek',
'watchlisttools-raw'   => 'Editovat textový soubor sledovaných stránek',
'watchlisttools-clear' => 'Vyprázdnit seznam sledovaných stránek',

);
