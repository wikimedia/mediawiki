<?php
/**
 * Slovak (Slovenčina)
 *
 * @package MediaWiki
 * @subpackage Language
 */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesSk = array(
	NS_MEDIA		=> 'Médiá',
	NS_SPECIAL		=> 'Špeciálne',
	NS_MAIN			=> '',
	NS_TALK			=> 'Diskusia',
	NS_USER			=> 'Redaktor',
	NS_USER_TALK		=> 'Diskusia_s_redaktorom',
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> FALSE, # Nadefinované vo funkcii dole 'Diskusia_k_' . $wgMetaNamespace,
	NS_IMAGE		=> 'Obrázok',
	NS_IMAGE_TALK		=> 'Diskusia_k_obrázku',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Diskusia_k_MediaWiki',
	NS_TEMPLATE		=> 'Šablóna',
	NS_TEMPLATE_TALK	=> 'Diskusia_k_šablóne',
	NS_HELP			=> 'Pomoc',
	NS_HELP_TALK		=> 'Diskusia_k_pomoci',
	NS_CATEGORY		=> 'Kategória',
	NS_CATEGORY_TALK	=> 'Diskusia_ku_kategórii'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSk = array(
	'Žiadne', 'Ukotvené vľavo', 'Ukotvené vpravo', 'Plávajúce vľavo'
);

/* private */ $wgDateFormatsSk = array(
	'Default',
	'15. január 2001 16:12',
	'15. jan. 2001 16:12',
	'16:12, 15. január 2001',
	'16:12, 15. jan. 2001',
	'ISO 8601' => '2001-01-15 16:12:34'
);

/* private */ $wgBookstoreListSk = array(
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
/* private */ $wgMagicWordsSk = array(
# ID CASE SYNONYMS
	MAG_REDIRECT => array( 0, '#redirect', '#presmeruj' ),
	MAG_NOTOC => array( 0, '__NOTOC__', '__BEZOBSAHU__' ),
	MAG_FORCETOC => array( 0, '__FORCETOC__', '__VYNÚŤOBSAH__' ),
	MAG_TOC => array( 0, '__TOC__', '__OBSAH__' ),
	MAG_NOEDITSECTION => array( 0, '__NOEDITSECTION__', '__NEUPRAVUJSEKCIE__' ),
	MAG_START => array( 0, '__START__', '__ŠTART__' ),
	MAG_CURRENTMONTH => array( 1, 'CURRENTMONTH', 'MESIAC' ),
	MAG_CURRENTMONTHNAME => array( 1, 'CURRENTMONTHNAME', 'MENOMESIACA' ),
	MAG_CURRENTMONTHNAMEGEN => array( 1, 'CURRENTMONTHNAMEGEN', 'MENOAKTUÁLNEHOMESIACAGEN' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'MENOAKTUÁLNEHOMESIACASKRATKA' ),
	MAG_CURRENTDAY => array( 1, 'CURRENTDAY', 'AKTUÁLNYDEŇ' ),
	MAG_CURRENTDAYNAME => array( 1, 'CURRENTDAYNAME', 'MENOAKTUÁLNEHODŇA' ),
	MAG_CURRENTYEAR => array( 1, 'CURRENTYEAR', 'AKTUÁLNYROK' ),
	MAG_CURRENTTIME => array( 1, 'CURRENTTIME', 'AKTUÁLNYČAS' ),
	MAG_NUMBEROFARTICLES => array( 1, 'NUMBEROFARTICLES', 'POČETČLÁNKOV' ),
	MAG_PAGENAME => array( 1, 'PAGENAME', 'MENOSTRÁNKY' ),
	MAG_PAGENAMEE => array( 1, 'PAGENAMEE' ),
	MAG_NAMESPACE => array( 1, 'NAMESPACE', 'MENNÝPRIESTOR' ),
	MAG_MSG => array( 0, 'MSG:', 'SPRÁVA:' ),
	MAG_SUBST => array( 0, 'SUBST:' ),
	MAG_MSGNW => array( 0, 'MSGNW:' ),
	MAG_END => array( 0, '__END__', '__KONIEC__' ),
	MAG_IMG_THUMBNAIL => array( 1, 'thumbnail', 'thumb', 'náhľad', 'náhľadobrázka' ),
	MAG_IMG_RIGHT => array( 1, 'right', 'vpravo' ),
	MAG_IMG_LEFT => array( 1, 'left', 'vľavo' ),
	MAG_IMG_NONE => array( 1, 'none', 'žiadny' ),
	MAG_IMG_WIDTH => array( 1, '$1px', '$1bod' ),
	MAG_IMG_CENTER => array( 1, 'center', 'centre', 'stred' ),
	MAG_IMG_FRAMED => array( 1, 'framed', 'enframed', 'frame', 'rám' ),
	MAG_INT => array( 0, 'INT:' ),
	MAG_SITENAME => array( 1, 'SITENAME', 'MENOLOKALITY' ),
	MAG_NS => array( 0, 'NS:', 'MP:' ),
	MAG_LOCALURL => array( 0, 'LOCALURL:' ),
	MAG_LOCALURLE => array( 0, 'LOCALURLE:' ),
	MAG_SERVER => array( 0, 'SERVER' ),
	MAG_GRAMMAR => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	MAG_NOTITLECONVERT => array( 0, '__NOTITLECONVERT__', '__NOTC__' ),
	MAG_NOCONTENTCONVERT => array( 0, '__NOCONTENTCONVERT__', '__NOCC__' ),
	MAG_CURRENTWEEK => array( 1, 'CURRENTWEEK', 'AKTUÁLNYTÝŽDEŇ' ),
	MAG_CURRENTDOW => array( 1, 'CURRENTDOW' ),
	MAG_REVISIONID => array( 1, 'REVISIONID' ),
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

/* private */ $wgAllMessagesSk = array(

# User Toggles

'tog-editondblclick' => "Upravuj stránky po dvojitom kliknutí (JavaScript)",
'tog-editsection' => "Umožni upravovať sekcie cez [uprav] odkazy",
'tog-editsectiononrightclick' => "Umožni upravovať sekcie po kliknutí pravým tlačidlom na nadpisy sekcií (JavaScript)",
'tog-editwidth' => "Maximálna šírka okna na úpravy",
'tog-fancysig' => "Nespracovávať podpisy (bez automatických odkazov)",
'tog-hideminor' => "V posledných úpravách neukazuj drobné úpravy",
'tog-highlightbroken' => "Neexistujúce odkazy zobrazuj červenou",
'tog-justify' => "Zarovnávaj odstavce",
'tog-minordefault' => "Označ všetky zmeny ako drobné",
'tog-nocache' => "Vypni ukladanie stránok do vyrovnávacej pamäte",
'tog-numberheadings' => "Automaticky čísluj odstavce",
'tog-previewonfirst' => "Zobraz náhľad pri prvom upravovaní",
'tog-previewontop' => "Zobrazuj ukážku pred oknom na úpravy, a nie až za ním",
'tog-rememberpassword' => "Pamätaj si heslo aj nabudúce",
'tog-showtoc' => "Zobraz obsah (pre stránky s viac ako 3 nadpismi)",
'tog-showtoolbar' => "Zobrazuj upravovací panel nástrojov",
'tog-underline' => "Podčiarkuj odkazy",
'tog-usenewrc' => "Špeciálne zobrazenie posledných úprav (vyžaduje JavaScript)",
'tog-watchdefault' => "Upozorňuj na nové a novo upravené stránky",
#'tog-externaleditor' => 'Use external editor by default',
#'tog-externaldiff' => 'Use external diff by default',

# Dates
#

'sunday' => 'nedeľa',
'monday' => 'pondelok',
'tuesday' => 'utorok',
'wednesday' => 'streda',
'thursday' => 'štvrtok',
'friday' => 'piatok',
'saturday' => 'sobota',
'january' => 'január',
'february' => 'február',
'march' => 'marec',
'april' => 'apríl',
'may_long' => 'máj',
'june' => 'jún',
'july' => 'júl',
'august' => 'august',
'september' => 'september',
'october' => 'október',
'november' => 'november',
'december' => 'december',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'máj',
'jun' => 'jún',
'jul' => 'júl',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',

# Bits of text used by many pages:
#
'categories' => 'Kategórie',
'category' => 'kategória',
'category_header' => 'články v kategórii "$1"',
'subcategories' => 'podkategórie',

"linktrail" => "/^((?:[a-z]|á|ä|č|ď|é|í|ľ|ĺ|ň|ó|ô|ŕ|š|ť|ú|ý|ž)+)(.*)$/sD",
"mainpage" => "Hlavná stránka",
'mainpagetext' => 'Wiki sofvér úspešne nainštalovaný.',
"mainpagedocfooter" => "Prosím prečítajte si [http://meta.wikipedia.org/wiki/MediaWiki_i18n dokumentáciu ako upraviť rozhranie]
a [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide Používateľskú príručku], ktorá Vám pomôže pri nastavení a používaní.",

'portal' => 'Portál komunity',
'portal-url' => 'Project:Portál_komunity',
'about' => 'Projekt',
'aboutsite' => 'O {{GRAMMAR:lokál|{{SITENAME}}}}',
'aboutpage' => 'Project:Úvod',
'article' => 'Stránka s obsahom',
'help' => 'Pomoc',
'helppage' => 'Pomoc:Obsah',
'bugreports' => 'Oznámenia o chybách',
'bugreportspage' => 'Project:Oznámenia_o_chybách',
'sitesupport' => 'Dotácie', # To enable, something like 'Donations',
'sitesupport-url' => 'Project:Dotácie',
'faq' => 'FAQ',
'faqpage' => 'Project:FAQ',
'edithelp' => 'Ako upravovať stránku',
'newwindow' => '(otvorí nové okno)',
'edithelppage' => 'Project:Ako_upravovať_stránku',
'cancel' => 'Zrušiť',
'qbfind' => 'Nájdi',
'qbbrowse' => 'Listuj',
'qbedit' => 'Upravuj',
'qbpageoptions' => 'Možnosti stránky',
'qbpageinfo' => 'Informácie o stránke',
'qbmyoptions' => 'Moje nastavenia',
'qbspecialpages' => 'Špeciálne stránky',
'moredotdotdot' => 'Viac...',
'mypage' => 'Moja stránka',
'mytalk' => 'Moja diskusia',
'anontalk' => 'Diskusia k tejto IP adrese',
'navigation' => 'Navigácia',

# Metadata in edit box
'metadata' => "'''Metadáta''' (for an explanation see <a href=\"$1\">here</a>)",
'metadata_page' => 'Project:Metadáta',

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

'currentevents' => 'Aktuality',
'currentevents-url' => 'Aktuality',

# NOTE: To turn off "Disclaimers" in the title links,
# set "disclaimers" => "-"

'disclaimers' => 'Vylúčenie zodpovednosti',
'disclaimerpage' => "Project:Vylúčenie zodpovednosti",
'errorpagetitle' => "Chyba",
'returnto' => "Späť na $1.",
'tagline' => "Z {{GRAMMAR:genitív|{{SITENAME}}}}",
'whatlinkshere' => 'Odkazy na tento článok',
'help' => 'Pomoc',
'search' => 'Hľadaj',
'go' => 'Choď',
"history" => 'História článku',
'history_short' => 'História',
'info_short' => 'Informácie',
'printableversion' => 'Verzia na tlač',
'edit' => 'Uprav',
'editthispage' => 'Upravuj túto stránku',
'delete' => 'Vymaž',
'deletethispage' => 'Vymaž tento článok',
'undelete_short1' => 'Obnov jednu úpravu',
'undelete_short' => 'Obnov $1 úprav',
'protect' => 'Zamkni',
'protectthispage' => 'Zamkni tento článok',
'unprotect' => 'Odomkni',
'unprotectthispage' => 'Odomkni tento článok',
'newpage' => 'Nový článok',
'talkpage' => 'Diskusia k článku',
'specialpage' => 'Špeciálna stránka',
'personaltools' => 'Osobné nástroje',
'postcomment' => 'Pridaj komentár',
'addsection' => '+',
'articlepage' => 'Zobraz článok',
'subjectpage' => 'Zobraz tému', # For compatibility
'talk' => 'Diskusia',
'views' => 'Zobrazení',
'toolbox' => 'Nástroje',
'userpage' => 'Zobraz stránku redaktora',
'wikipediapage' => 'Zobraz stránku projektu',
'imagepage' => 'Zobraz stránku s obrázkom',
'viewtalkpage' => 'Zobraz diskusiu k článku',
'otherlanguages' => 'Iné jazyky',
'redirectedfrom' => '(Presmerované z $1)',
'lastmodified' => 'K posledným úpravám tejto stránky došlo $1.',
'viewcount' => 'Táto stránka bola navštívená $1-krát.',
'copyright' => 'Obsah je dostupný $1.',
'poweredby' => "{{SITENAME}} používa [http://www.mediawiki.org/ MediaWiki], open source wiki nástroj.",
'printsubtitle' => "(Zdroj: {{SERVER}})",
'protectedpage' => 'Zamknutá stránka',
'administrators' => "Project:Administrátori",
'sysoptitle' => 'Je potrebné oprávnenie typu administrátor',
'sysoptext' => "Požadovanú akciu môžu vykonať iba redaktori s oprávnením administrátor. Pozri $1.",
'developertitle' => 'Je potrebné oprávnenie typu vývojár',
'developertext' => "Požadovanú akciu môžu vykonať iba redaktori s oprávnením \"vývojár\".
Pozri $1.",
'nbytes' => '$1 bajtov',
'ok' => 'OK',
'sitetitle' => "{{SITENAME}}",
'pagetitle' => "$1 - {{SITENAME}}",
'sitesubtitle' => 'Slobodná encyklopédia', # FIXME
'retrievedfrom' => "Zdroj: \"$1\"",
'newmessages' => "Máte $1.",
'newmessageslink' => 'nové správy',
'editsection' => 'upraviť',
'toc' => 'Obsah',
'showtoc' => 'zobraz',
'hidetoc' => 'schovaj',
'thisisdeleted' => "Zobraziť alebo obnoviť $1?",
'restorelink' => "$1 zmazaných úprav",
'feedlinks' => 'Kanál:',
'sitenotice' => '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Článok',
'nstab-user' => 'Stránka redaktora',
'nstab-media' => 'Médiá',
'nstab-special' => 'Špeciálne',
'nstab-wp' => 'Projekt',
'nstab-image' => 'Súbor',
'nstab-mediawiki' => 'Správa',
'nstab-template' => 'Šablóna',
'nstab-help' => 'Pomoc',
'nstab-category' => 'Kategória',

# Main script and global functions
#
'nosuchaction' => 'Takáto akcia neexistuje',
'nosuchactiontext' => 'Softvér MediaWiki nepozná akciu,
ktorú vyžadujete pomocou URL.',
'nosuchspecialpage' => 'Takáto špeciálna stránka neexistuje',
'nospecialpagetext' => 'Softvér MediaWiki nepozná takúto špeciálnu stránku,
zoznam špeciálnych stránok nájdete na [[{{ns:special}}:Specialpages]].',

# General errors
#
'error' => 'Chyba',
'databaseerror' => 'Chyba v databáze',
'dberrortext' => "Nastala syntaktická chyba v príkaze na prehľadávanie databázy.
Posledný pokus o prehľadávanie bol:
<blockquote><tt>$1</tt></blockquote>
z funkcie \"<tt>$2</tt>\".
MySQL vrátil chybu \"<tt>$3: $4</tt>\".",
'dberrortextcl' => "Nastala syntaktická chyba v príkaze na prehľadávanie databázy.
Posledný pokus o prehľadávanie bol:
\"$1\"
z funkcie \"$2\".
MySQL vrátil chybu \"$3: $4\".\n",
'noconnect' => 'Prepáčte! Wiki má technické problémy a nemôže kontaktovať databázový server. <br />
$1',
'nodb' => "Neviem vybrať databázu $1",
'cachederror' => 'Nasledujúca stránka je odložená kópia vyžiadanej stránky a nemusí byť aktuálna.',
'laggedslavemode' => 'Varovanie: Je možné, že stránka neobsahuje posledné aktualizácie.',
'readonly' => 'Databáza je zamknutá',
'enterlockreason' => 'Zadajte dôvod požadovaného zamknutia vrátane odhadu,
kedy očakávate odomknutie',
'readonlytext' => "Databáza je momentálne zamknutá,
nové články a úpravy sú zablokované, pravdepodobne z dôvodu údržby databázy.
Po skončení tejto údržby bude {{SITENAME}} opäť fungovať normálne.
Administrátor, ktorý nariadil uzamknutie, uvádza tento dôvod: $1",
'missingarticle' => "Databáza nenašla text článku, ktorý by mala nájsť,
konkrétne \"$1\".

Toto je obyčajne zapríčinené odkazovaním na staršie verzie alebo odkazom na článok, ktorý bol zmazaný.

Ak toto neplatí, pravdepodobne ste našli chybu s softvéri.
Prosím ohláste túto chybu administrátorovi, uveďte aj meno článku - odkaz (URL).",
#'readonly_lag' => "The database has been automatically locked while the slave database servers catch up to the master",
'internalerror' => 'Vnútorná chyba',
'filecopyerror' => "Neviem skopírovať súbor \"$1\" na \"$2\".",
'filerenameerror' => "Neviem premenovať súbor \"$1\" na \"$2\".",
'filedeleteerror' => "Neviem vymazať súbor \"$1\".",
'filenotfound' => "Neviem nájsť súbor \"$1\".",
'unexpected' => "Nečakaná hodnota: \"$1\"=\"$2\".",
'formerror' => 'Chyba: neviem spracovať formulár',
'badarticleerror' => 'Na tejto stránke túto akciu nemožno vykonať.',
'cannotdelete' => 'Neviem zmazať danú stránku alebo súbor. (Možno už bol zmazaný niekým iným.)',
'badtitle' => 'Neplatný nadpis',
'badtitletext' => "Požadovaný nadpis bol neplatný, nezadaný,
alebo nesprávne odkazovaný z inej jazykovej verzie {{GRAMMAR:genitív|{{SITENAME}}}}.",
'perfdisabled' => 'Prepáčte! Táto funkcia bola dočasne vypnutá,
pretože tak spomaľuje databázu, že nikto nemôže používať
wiki.',
'perfdisabledsub' => "Tu je uložená kópia z $1:", # obsolete?
'perfcached' => '<strong>Nasledujúce dáta sú z dočasnej pamäte a nemusia byť úplne aktuálne:</strong>',
'wrong_wfQuery_params' => "Nesprávny parameter v wfQuery()<br />
Funkcia: $1<br />
Dotaz: $2",
'viewsource' => 'Zobraz zdroj',
'protectedtext' => "Táto stránka bola zamknutá na zabránenie úprav; pravdepodobne existuje
veľa dôvodov prečo je to tak, prosíme pozrite
[[Project:Zamknutá stránka]].

Môžete si pozrieť a skopírovať zdroj tejto stránky:",
'sqlhidden' => '(SQL príkaz na prehľadávanie je skrytý)',

# Login and logout pages
#
'logouttitle' => 'Odhlásiť redaktora',
'logouttext' => "Práve ste sa odhlásili.
Odteraz môžete používať {{GRAMMAR:akuzatív|{{SITENAME}}}} ako anonymný redaktor alebo sa môžete
opäť prihlásiť pod rovnakým alebo odlišným redaktorským menom. Uvedomte si, že niektoré stránky sa môžu
naďalej zobrazovať ako keby ste boli prihlásený, až kým nevymažete
vyrovnávaciu pamäť vášho prehliadača.\n",

'welcomecreation' => "== Vitajte, $1! ==
Vaše konto je vytvorené. Nezabudnite si nastaviť vaše redaktorské nastavenia.",

'loginpagetitle' => 'Prihlásenie redaktora',
'yourname' => 'Vaše redaktorské meno',
'yourpassword' => 'Vaše heslo',
'yourpasswordagain' => 'Zopakujte heslo',
'newusersonly' => ' (iba noví redaktori)',
'remembermypassword' => 'Pamätať si heslo aj po vypnutí počítača.',
'loginproblem' => "'''Nastal problém pri vašom prihlasovaní.'''<br />Skúste to znova!",
'alreadyloggedin' => "'''Užívateľ $1, vy už ste prihlásený!'''<br />\n",

'login' => 'Prihlásenie',
'loginprompt' => "Na prihlásenie do {{GRAMMAR:genitív|{{SITENAME}}}} musíte mať zapnuté koláčiky (cookies).",
'userlogin' => 'Vytvorte si konto alebo sa prihláste',
'logout' => 'Odhlásenie',
'userlogout' => 'Odhlásenie',
'notloggedin' => 'Neprihlásený/á',
'createaccount' => 'Zriadiť nové konto',
'createaccountmail' => 'e-mailom',
'badretype' => 'Zadané heslá nie sú rovnaké.',
'userexists' => 'Zadané redaktorské meno už používa niekto iný. Zadajte iné meno.',
'youremail' => 'Váš e-mail²',
'yourrealname' => 'Vaše skutočné meno¹',
'yourlanguage' => 'Jazyk',
'yourvariant' => 'Variant',
'yournick' => 'Vaša prezývka',
'email' => 'e-mail',
'emailforlost' => "Polia označené horným indexom sú nepovinné. Uvedenie e-mailovej adresy umožňuje
ľuďom vás kontaktovať cez rozhranie prehliadača bez toho, aby ste týmto ľuďom museli prezradiť vašu
e-mailovú adresu. Navyše možno e-mailovú adresu použiť na zaslanie nového hesla, ak ho náhodou zabudnete.
<br /><br />
Vaše skutočné meno, ak sa rozhodnete ho uverejniť, bude priradené k vašej práci.",
'prefs-help-email-enotif' => 'Táto adresa sa používa aj na posielanie e-mailových upozornení, ak ste túto možnosť povolili.',
'prefs-help-realname' => '¹ Skutočné meno (nepovinné): ak sa rozhodnete ho poskytnúť, bude použité na označenie Vašej práce.',
'loginerror' => 'Chyba pri prihlasovaní',
'prefs-help-email' => '² E-mail (nepovinné): Uloženie e-mailovej adresy umožní iným ľuďom kontaktovať Vás priamo pomocou web stránky, bez uverejňovania Vašej e-mailovej adresy a môže byť použité na poslanie nového hesla, ak zabudnete pôvodné.',
'nocookiesnew' => "Redaktorské konto bolo vytvorené, ale nie ste prihlásený. {{SITENAME}} používa koláčiky (cookies) na prihlásenie. Vy máte koláčiky (cookies) vypnuté. Zapnite ich a potom sa prihláste s vaším novým redaktorským menom a heslom.",
'nocookieslogin' => "{{SITENAME}} používa koláčiky (cookies) na prihlásenie. Vy máte koláčiky (cookies) vypnuté. Zapnite ich a skúste znovu.",
'noname' => 'Nezadali ste platné redaktorské meno.',
'loginsuccesstitle' => 'Prihlásenie úspešné',
'loginsuccess' => "Teraz ste prihlásený do {{GRAMMAR:genitív|{{SITENAME}}}} ako \"$1\".",
'nosuchuser' => "Redaktorské meno \"$1\" neexistuje. Skontrolujte preklepy alebo sa prihláste ako nový redaktor pomocou dolu uvedeného formulára.",
'nosuchusershort' => "V súčasnosti neexistuje redaktor s menom \"$1\". Skontrolujte preklepy.",
'wrongpassword' => 'Zadané heslo je nesprávne. Skúste to znovu.',
'mailmypassword' => 'Pošlite mi e-mailom dočasné heslo',
'noemail' => "Redaktor \"$1\" nezadal e-mailovú adresu.",
'passwordsent' => "Nové heslo bolo zaslané na e-mailovú adresu
redaktora \"$1\".
Prihláste sa znovu po jeho obdržaní.",
#'eauthentsent' => "A confirmation email has been sent to the nominated email address.
#Before any other mail is sent to the account, you will have to follow the instructions in the email,
#to confirm that the account is actually yours.",
'loginend' => '&nbsp;',
'mailerror' => "Chyba pri posielaní e-mailu: $1",
'acct_creation_throttle_hit' => 'Prepáčte, už máte vytvorených $1 kont. Nemôžete ich vytvoriť viac.',
'emailauthenticated' => 'Vaša e-mailová adresa bola overená na $1.',
'emailnotauthenticated' => 'Vaša e-mailová adresa <strong>ešte nebola overená</strong> a pokročilé funkcie e-mailu sú "deaktivované až do overenia"<strong>(d.a.d.o)</strong>.',
'noemailprefs' => '<strong>Nezadali ste žiadnu e-mailovú adresu</strong>, nasledujúce
nástroje nebudú prístupné.',
'emailconfirmlink' => 'Potvrďte vašu e-mailovú adresu',
'invalidemailaddress' => 'E-mailovú adresu nemožno akceptovať, pretože sa zdá, že má neplatný formát. Zadajte dobre naformátovanú adresu alebo nechajte príslušné políčko prázdne.',

# Edit page toolbar
'bold_sample' => 'Tučný text',
'bold_tip' => 'Tučný text',
'italic_sample' => 'Kurzíva',
'italic_tip' => 'Kurzíva',
'link_sample' => 'Názov odkazu',
'link_tip' => 'Interný odkaz',
'extlink_sample' => 'http://www.príklad.sk názov odkazu',
'extlink_tip' => 'Externý odkaz (nezabudnite prefix http://)',
'headline_sample' => 'Nadpis',
'headline_tip' => 'Nadpis 2. úrovne',
'math_sample' => 'Sem vložte vzorec',
'math_tip' => 'Matematický vzorec (LaTeX)',
'nowiki_sample' => 'Sem vložte neformátovaný text',
'nowiki_tip' => 'Ignoruj wiki formátovanie',
'image_sample' => 'Príklad.jpg',
'image_tip' => 'Vložený obrázok',
'media_sample' => 'Príklad.ogg',
'media_tip' => 'Odkaz na media súbor',
'sig_tip' => 'Váš podpis s dátumom a časom',
'hr_tip' => 'Horizontálna čiara (používajte zriedka)',
'infobox' => 'Na získanie textu príkladu kliknite na tlačidlo',
'infobox_alert' => "Prosím zadajte text, ktorý chcete, aby bol naformátovaný.\n Zobrazí sa ako text príkladu na kopírovanie a vkladanie.\nPríklad:\n$1\nsa stane:\n$2",

# Edit pages
#
'summary' => 'Zhrnutie úprav',
'subject' => 'Téma/nadpis',
'minoredit' => 'Toto je drobná úprava.',
'watchthis' => 'Sleduj úpravy tohto článku',
'savearticle' => 'Ulož článok',
'preview' => 'Náhľad',
'showpreview' => 'Zobraz náhľad',
'showdiff' => 'Zobraz rozdiely',
'blockedtitle' => 'Redaktor je zablokovaný',
'blockedtext' => "Vaše redaktorské meno alebo IP adresu zablokoval $1.
Udáva tento dôvod:<br />''$2''

Môžete sa skontaktovať s $1 alebo s iným [[Project:Administrátori|administrátorom]] a prediskutovať blokovanie.\",

Uvedomte si, že nemôžete použiť funkciu \"Pošli e-mail redaktorovi\", ak nemáte zaregistrovanú platnú e-mailovú adresu vo vašich [[Špeciálne:Nastavenia|nastaveniach]].

Vaša IP adresa je $3. Prosíme zahrňte túto adresu do každého vášho príkazu na prehľadávanie.
",
'whitelistedittitle' => 'Na úpravu je nutné prihlásenie',
'whitelistedittext' => 'Na úpravu článkov musíte byť [[Special:Userlogin|prihlásený/á]].',
'whitelistreadtitle' => 'Na čítanie článkov je nutné prihlásenie',
'whitelistreadtext' => 'Na čítanie článkov musíte byť [[Special:Userlogin|prihlásený/á]]',
'whitelistacctitle' => 'Nemáte dovolené vytvorenie konta',
'whitelistacctext' => 'Na umožnenie vytvorenia účtu na tejto Wiki musíte byť [[Special:Userlogin|prihlásený/á]] a mať primerané práva.',
'loginreqtitle' => 'Nutné prihlásenie',
'loginreqtext' => 'Na prezeranie ďalších článkov sa musíte [[Special:Userlogin|prihlásiť]].',
'accmailtitle' => 'Heslo odoslané.',
'accmailtext' => "Heslo pre '$1' bolo poslané $2.",
'newarticle' => '(Nový)',
'newarticletext' =>
"<div style=\"border: 1px solid #ccc; padding: 7px;\">'''{{SITENAME}} ešte neobsahuje článok s názvom {{PAGENAME}}.'''
* Na vytvorenie nového článku začnite písať do dolného okna a potom kliknite \"Ulož článok\". Vaše zmeny budú ihneď viditeľné.
* Prosíme Vás, aby ste nevytvárali článok na prezentáciu samého seba, web stránky, produktu alebo podniku (pozri [[Project:Zásady a pravidlá]]).
* Ak ste vo {{GRAMMAR:lokál|{{SITENAME}}}} nový/á, prečítajte si pred tvorbou [[Project:Váš prvý článok|vášho prvého článku]] [[Project:Príručka|Príručku]] alebo použite na experimenty [[Project:Pieskovisko|pieskovisko]].
</div>",
'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext' => "---- ''Toto je diskusná stránka anonymného redaktora, ktorý nemá vytvorené svoje konto alebo ho nepoužíva. Preto musíme na jeho identifikáciu použiť numerickú [[IP adresa|IP adresu]]. Je možné, že takúto IP adresu používajú viacerí redaktori. Ak ste anonymný redaktor a máte pocit, že vám boli adresované irelevantné diskusné príspevky, zriaďte si vlastný účet alebo sa prihláste ([[Special:Userlogin|Zriadenie konta alebo prihlásenie]]), aby sa zamedzilo budúcim zámenám s inými anonymnými redaktormi''",
'noarticletext' => '(Tento článok momentálne neobsahuje žiaden text)',
'clearyourcache' => "'''Poznámka:''' Aby sa zmeny prejavili, po uložení musíte vymazať vyrovnávaciu pamäť vášho prehliadača: '''Mozilla:''' ''Ctrl-Shift-R'', '''IE:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'usercssjsyoucanpreview' => "<strong>Tip:</strong> Použite tlačidlo 'Zobraz náhľad', aby sa otestovalo Vaše nové CSS/JS pred uložením.",
'usercsspreview' => "'''Nezabudnite, že toto je iba náhľad Vášho užívateľského CSS, ešte nebolo uložené!'''",
'userjspreview' => "'''Nezabudnite, že iba testujete/náhľad vášho užívateľského JavaScriptu, ešte nebol uložený!'''",
'updated' => '(Aktualizované)',
'note' => '<strong>Poznámka: </strong> ',
'previewnote' => 'Nezabudnite, toto je len náhľad vami upravovaného článku. Článok ešte nie je uložený!',
'previewconflict' => 'Tento náhľad upraveného článku zobrazuje
text z horného okna na úpravy tak, ako sa zobrazí potom, keď ho uložíte.',
'editing' => "Úprava stránky $1",
'editingsection' => "Úprava stránky $1 (sekcia)",
'editingcomment' => "Úprava stránky $1 (diskusia)",
'editconflict' => 'Konflikt pri úprave: $1',
'explainconflict' => "Niekto iný zmenil túto stránku, medzi tým čo ste ju upravovali vy.
Horné okno na úpravy obsahuje text stránky tak, ako je momentálne platný.
Vaše úpravy sú uvedené v dolnom okne na úpravy.
Budete musieť zlúčiť vaše zmeny s existujúcim textom.
'''Iba''' obsah horného okna sa uloží, keď stlačíte \"Ulož článok\".",
'yourtext' => 'Váš text',
'storedversion' => 'Uložená verzia',
'nonunicodebrowser' => "<strong>UPOZORNENIE: Váš prehliadač nepodporuje unicode, prosím pred úpravou článku použite iný.</strong>",
'editingold' => "<strong>POZOR: Upravujete starú
verziu tejto stránky.
Ak vašu úpravu uložíte, prepíšete tým všetky úpravy, ktoré nasledovali po tejto starej verzii.</strong>",
'yourdiff' => 'Rozdiely',
'copyrightwarning' => "<div id=\"specChar\" style=\"margin-top:5px;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:3px;text-align:center;\">
<small>[[metawikipedia:Help:Special_characters|Zvláštne znaky]]
</small></div>

Nezabudnite, že všetky príspevky do {{GRAMMAR:genitív|{{SITENAME}}}}
sa považujú za príspevky uskutočnené podľa GNU Free Documentation License
(podrobnosti pozri pod $1).
Ak nechcete, aby bolo to, čo ste napísali, neúprosne upravované a ďalej
ľubovoľne rozširované, tak sem váš text neumiestňujte.<br />
Týmto nám tiež sľubujete, že ste tento text buď napísali sám, alebo že je skopírovaný
zo spoločného vlastníctva (public domain) alebo podobného voľne prístupného zdroja.
<strong>NEUMIESTŇUJTE TU BEZ POVOLENIA DIELA CHRÁNENÉ AUTORSKÝM PRÁVOM!</strong>",
'copyrightwarning2' => "Prosím uvedomte si, že všetky príspevky do {{GRAMMAR:genitív|{{SITENAME}}}}
môžu byť upravované, skracované alebo odstránené inými príspevkami.
Ak nechcete, aby Vaše texty boli menené, tak ich tu neuverejňujte.<br />
Týmto nám tiež sľubujete, že ste tento text buď napísali sám, alebo že je skopírovaný
zo spoločného vlastníctva (public domain) alebo podobného voľne prístupného zdroja.
<strong>NEUMIESTŇUJTE TU BEZ POVOLENIA DIELA CHRÁNENÉ AUTORSKÝM PRÁVOM!</strong>",
'longpagewarning' => "<strong>POZOR: Táto stránka má $1 kilobajtov; niektoré
prehliadače by mohli mať problémy s úpravou stránok, ktorých veľkosť sa blíži k alebo presahuje 32kb.
Zvážte, či by nebolo možné rozdeliť stránku na menšie sekcie.</strong>",
'readonlywarning' => '<strong>POZOR: Databáza bola počas upravovania stránky zamknutá z dôvodu údržby,
takže stránku momentálne nemôžete uložiť. Môžete skopírovať a vložiť
text do textového súboru a uložiť si ho na neskôr.</strong>',
'protectedpagewarning' => "<strong>POZOR: Táto stránka bola zamknutá, takže ju môžu upravovať iba redaktori s oprávnením administrátor. Uistite sa, že rozumiete [[Project:Pravidlá zamykania stránok|pravidlám zamykania stránok]].</strong>",
'templatesused' => 'Šablóny použité v tomto článku:',

# History pages
#
'revhistory' => 'Predchádzajúce verzie',
'nohistory' => 'Pre tento článok neexistuje história.',
'revnotfound' => 'Predchádzajúca verzia nebola nájdená',
'revnotfoundtext' => "Požadovaná staršia verzia článku nebola nájdená.
Prosím skontrolujte URL adresu, ktorú ste použili na prístup k tejto stránke.\n",
'loadhist' => 'Otváram históriu stránky',
'currentrev' => 'Aktuálna verzia',
'revisionasof' => 'Verzia zo dňa a času $1',
'revisionasofwithlink' => 'Verzia zo dňa $1; $2<br />$3 | $4',
'previousrevision' => '← Staršia verzia',
'nextrevision' => 'Novšia verzia →',
'currentrevisionlink' => 'Zobrazenie aktuálnej úpravy',
'cur' => 'aktuálna',
'next' => 'ďalšia',
'last' => 'posledná',
'orig' => 'pôvodná',
'histlegend' => 'Legenda: (aktuálna) = rozdiel oproti aktuálnej verzii,
(posledná) = rozdiel oproti predchádzajúcej verzii, D = drobná úprava',
'history_copyright' => '-',
'deletedrev' => '[zmazané]',

# Diffs
#
'difference' => '(Rozdiel medzi verziami)',
'loadingrev' => 'Sťahujem verzie na zobrazenie rozdielov',
'lineno' => "Riadok $1:",
'editcurrent' => 'Upraviť aktuálnu verziu tejto stránky',
'selectnewerversionfordiff' => 'Vybrať na porovnanie novšiu verziu',
'selectolderversionfordiff' => 'Vybrať na porovnanie staršiu verziu',
'compareselectedversions' => 'Porovnaj označené verzie',

# Search results
#
'searchresults' => 'Výsledky vyhľadávania',
'searchresulttext' => "Viac informácií o vyhľadávaní vo {{GRAMMAR:lokál|{{SITENAME}}}} je uvedených na $1.",
'searchquery' => "Na vyhľadávací dotaz \"$1\"",
'badquery' => 'Nesprávne formulovaný text na prehľadávanie',
'badquerytext' => 'Váš text na prehľadávanie sme nemohli spracovať.
Dôvodom je pravdepodobne to, že ste hľadali slovo
kratšie ako tri písmená, čo zatiaľ {{SITENAME}} neumožňuje.
Alebo ste možno výraz zle napísali, napríklad "dom a a záhrada". Skúste iný text na prehľadávanie.',
'matchtotals' => 'Výsledkom textu na prehľadávanie "$1" je $2 nadpisov článkov a text $3 článkov.',
'nogomatch' => "Neexistuje článok s presne takýmto nadpisom; skúšam nájsť podobné nadpisy.

<br /><br />Chcete '''<a href=\"$1\" class=\"new\">vytvoriť nový článok s týmto nadpisom</a>'''? Alebo [[Project:Žiadané články|dať žiadosť na jeho tvorbu]]?.",
'titlematches' => 'Vyhovujúce nadpisy článkov',
'notitlematches' => 'Niet vyhovujúcich nadpisov článkov',
'textmatches' => 'Vyhovujúce texty článkov',
'notextmatches' => 'Niet vyhovujúcich textov článkov',
'prevn' => "predchádzajúca $1",
'nextn' => "ďalšia $1",
'viewprevnext' => "Zobraz ($1) ($2) ($3).",
'showingresults' => "Nižšie je zobrazených '''$1''' výsledkov, počnúc od #'''$2'''.",
'showingresultsnum' => "Nižšie je zobrazených '''$3''' výsledkov, počnúc od #'''$2'''.",
'nonefound' => "<strong>Poznámka</strong>: bezvýsledné vyhľadávania sú
často spôsobené buď snahou hľadať príliš bežné, obyčajné slová (napríklad mať),
pretože tieto sa neregistrujú, alebo uvedením viac ako jedného vyhľadávaného výrazu,
pretože výsledky uvádzajú len stránky obsahujúce všetky vyhľadávané výrazy.",
'powersearch' => 'Vyhľadávanie',
'powersearchtext' => "Vyhľadávania v menných priestoroch :<br />
$1<br />
$2 Zoznam presmerovaní &nbsp; Hľadanie pre $3 $9",
'searchdisabled' => 'Prepáčte! Fulltextové vyhľadávanie bolo dočasne vypnuté z dôvodu preťaženia. Zatiaľ môžete použiť hľadanie pomocou Google, ktoré však nemusí byť aktuálne.',
'blanknamespace' => '(Hlavný)',

# Preferences page
#
'preferences' => 'Nastavenia',
'prefsnologin' => 'Nie ste prihlásený/á',
'prefsnologintext' => "Musíte byť [[Special:Userlogin|prihlásený/á]],
aby ste mohli zmeniť vaše nastavenia.",
'prefslogintext' => "Ste prihlásený ako \"[[Redaktor:$1|$1]]\" ([[Komentár k redaktorovi:$1|Diskusia]], [[Špeciálne:Contributions/$1|príspevky]]). Vaše interné číslo ID je $2.

Pozri [[m:Help:Preferences|Nastavenia]] na vysvetlenie volieb.",
'prefsreset' => 'Boli obnovené pôvodné nastavenia.',
'qbsettings' => 'Nastavenia pre bočné menu',
'changepassword' => 'Zmeniť heslo',
'skin' => 'Vzhľad',
'math' => 'Matematika',
'dateformat' => 'Formát dátumu',
'math_failure' => 'Syntaktická analýza (parsing) neúspešná',
'math_unknown_error' => 'neznáma chyba',
'math_unknown_function' => 'neznáma funkcia ',
'math_lexing_error' => 'lexingová chyba',
'math_syntax_error' => 'syntaktická chyba',
'math_image_error' => 'PNG konverzia neúspešná; skontrolujte správnosť inštalácie programov: latex, dvips, gs a convert',
'math_bad_tmpdir' => 'Nemôžem zapisovať alebo vytvoriť dočasný matematický adresár',
'math_bad_output' => 'Nemôžem zapisovať alebo vytvoriť výstupný matematický adresár',
'math_notexvc' => 'Chýbajúci texvc program; prosím pozrite math/README na konfiguráciu.',
'prefs-personal' => 'Redaktorské nastavenia',
'prefs-rc' => 'Zobrazenie posledných úprav a nedokončených článkov',
'prefs-misc' => 'Rôzne nastavenia',
'saveprefs' => 'Ulož nastavenia',
'resetprefs' => 'Obnoviť pôvodné nastavenia',
'oldpassword' => 'Staré heslo',
'newpassword' => 'Nové heslo',
'retypenew' => 'Nové heslo (ešte raz)',
'textboxsize' => 'Veľkosť okna na úpravy',
'rows' => 'Riadky',
'columns' => 'Stĺpce',
'searchresultshead' => 'Nastavenia výsledkov vyhľadávania',
'resultsperpage' => 'Počet vyhovujúcich výsledkov zobrazených na strane',
'contextlines' => 'Počet zobrazených riadkov z kažnej nájdenej stránky',
'contextchars' => 'Počet kontextových znakov v riadku',
'stubthreshold' => 'Hranica pre zobrazenie nedokončených článkov',
'recentchangescount' => 'Počet nadpisov uvedených v posledných úpravách',
'savedprefs' => 'Vaše nastavenia boli uložené.',
'timezonelegend' => 'Časové pásmo',
'timezonetext' => 'Zadajte počet hodín, o ktorý sa váš miestny čas odlišuje
od času na serveri (UTC).',
'localtime' => 'Miestny čas',
'timezoneoffset' => 'Rozdiel¹',
'servertime' => 'Aktuálny čas na serveri',
'guesstimezone' => 'Prevziať z prehliadača',
'emailflag' => 'Zakázať prijímanie e-mailov od druhých redaktorov',
'defaultns' => 'Štandardne vyhľadávaj v týchto menných priestoroch:',
'default' => 'štandardne',
'files' => 'Súbory',

# User levels special page
#

# switching pan
'groups-lookup-group' => 'Spravuj práva skupiny',
'groups-group-edit' => 'Existujúce skupiny: ',
'editgroup' => 'Uprav skupinu',
'addgroup' => 'Pridaj skupinu',

'userrights-lookup-user' => 'Spravuj skupiny redaktorov',
'userrights-user-editname' => 'Napíš meno redaktora: ',
'editusergroup' => 'Uprav redaktorské skupiny',

# group editing
'groups-editgroup' => 'Uprav skupinu',
'groups-addgroup' => 'Pridaj skupinu',
'groups-editgroup-preamble' => 'Ak opis začína čiarkou, zvyšok sa bude považovať za
názov systémovej správy a text bude lokalizovateľný pomocou menného priestoru MediaWiki',
'groups-editgroup-name' => 'Meno skupiny: ',
'groups-editgroup-description' => 'Opis skupiny (max. 255 znakov):<br />',
'savegroup' => 'Ulož skupinu',
'groups-tableheader' => 'ID || Meno || Opis || Práva',
'groups-existing' => 'Existujúce skupiny',
'groups-noname' => 'Zvoľte platné meno skupiny',
'groups-already-exists' => 'Skupina s týmto názvom už existuje',
'addgrouplogentry' => 'Skupina $2 pridaná',
'changegrouplogentry' => 'Skupina $2 zmenená',
'renamegrouplogentry' => 'Skupina $2 premenovaná na $3',

# user groups editing
#
'userrights-editusergroup' => 'Uprav skupinu',
'saveusergroups' => 'Ulož skupinu',
'userrights-groupsmember' => 'Člen skupiny:',
'userrights-groupsavailable' => 'Dostupné skupiny:',
'userrights-groupshelp' => 'Označte skupiny, do ktorých chcete pridať alebo z ktorých chcete
odobrať redaktora. Neoznačené skupiny nebudú zmenené. Odobrať skupinu možete pomocou CTRL + kliknutie ľavým tlačidlom',
'userrights-logcomment' => 'Zmenená príslušnosť zo skupiny $1 na skupinu $2',

# Default group names and descriptions
#
'group-anon-name' => 'Anonym',
'group-anon-desc' => 'Anonymný redaktor',
'group-loggedin-name' => 'Redaktor',
'group-loggedin-desc' => 'Bežní prihlásení redaktori',
'group-admin-name' => 'Administrátor',
'group-admin-desc' => 'Dôveryhodní redaktori, ktorí môžu blokovať redaktorov a mazať články',
'group-bureaucrat-name' => 'Byrokrat',
'group-bureaucrat-desc' => 'Skupina byrokratov vytvára sysopov',
'group-steward-name' => 'Steward',
'group-steward-desc' => 'Plný prístup',

# Recent changes
#
'changes' => 'úpravy',
'recentchanges' => 'Posledné úpravy',
'recentchanges-url' => 'Special:Recentchanges',
'recentchangestext' => 'Pomocou tejto stránky sledujete posledné úpravy vykonané vo {{GRAMMAR:lokál|{{SITENAME}}}}.
[[Project:Vitajte vo {{GRAMMAR:lokál|{{SITENAME}}}}|]]!
Pozrite si nasledujúce články: [[Project:FAQ|{{SITENAME}} FAQ]],
[[Project:Zásady a pravidlá|Zásady a pravidlá]]
(špeciálne [[Project:Konvencie pre názvoslovie|konvencie pre názvoslovie]],
[[Project:Neutrálny uhol pohľadu|neutrálny uhol pohľadu]]),
a [[Project:Bežné chyby|bežné chyby]].

Ak chcete, aby {{SITENAME}} uspela, je veľmi dôležité, aby ste nepridávali
materiál obmedzený inými [[Project:Autorské právo|autorskými právami]].
Právne záväzky môžu projekt vážne poškodiť, takže Vás prosíme, aby ste to nerobili.
Pozrite aj [http://meta.wikipedia.org/wiki/Special:Recentchanges recent meta discussion].',
'rcloaderr' => 'Nahrávam posledné úpravy',
'rcnote' => "Tu je posledných <strong>$1</strong> úprav počas posledných <strong>$2</strong> dní.",
'rcnotefrom' => "Tu sú posledné zmeny od '''$2''' (zobrazených '''$1''' záznamov).",
'rclistfrom' => "Zobraz nové úpravy počnúc od $1",
'showhideminor' => "$1 drobné úpravy | $2 robotov | $3 prihlásených redaktorov ",
'rclinks' => "Zobraz posledných $1 úprav v posledných $2 dňoch<br />$3",
'rchide' => "vo forme $4; $1 drobné úpravy; $2 druhotné menné priestory; $3 viacnásobné úpravy.",
'rcliu' => "; $1 úprav od prihlásených redaktorov",
'diff' => 'rozdiel',
'hist' => 'história',
'hide' => 'skryť',
'show' => 'zobraz',
'tableform' => 'tabuľka',
'listform' => 'zoznam',
'nchanges' => "$1 úprav",
'minoreditletter' => 'D',
'newpageletter' => 'N',
'sectionlink' => '→',
'number_of_watching_users_RCview' => '[$1]',
'number_of_watching_users_pageview' => '[sledujúcich redaktorov: $1]',

# Upload
#
'upload' => 'Nahranie súboru',
'uploadbtn' => 'Nahrať súbor',
'uploadlink' => 'Nahranie súborov',
'reupload' => 'Zopakovať nahranie',
'reuploaddesc' => 'Späť k formuláru na nahranie.',
'uploadnologin' => 'Nie ste prihlásený',
'uploadnologintext' => "Musíte byť [[Special:Userlogin|prihlásený]],
aby ste mohli nahrávať súbory.",
'upload_directory_read_only' => 'Nie je možné zapisovať webovým servrom do adresára pre nahrávanie ($1).',
'uploaderror' => 'Chyba pri nahrávaní',
'uploadtext' =>
"<strong>STOP!</strong> Skôr ako sem začnete nahrávať,
ubezpečte sa, že ste si prečítali, a že dodržujete [[Project:Zásady používania obrázkov|Zásady používania obrázkov]].

Na zobrazenie alebo vyhľadávanie už nahraných obrázkov,
choďte na [[Special:Imagelist|zoznam nahraných obrázkov]].
Nahrania a zmazania sú zaznamenané na [[Project:Záznam nahrávaní|zázname nahrávaní]].

Použite dole uvedený formulár na nahranie nových obrázkov,
ktoré budú slúžiť ako ilustrácie do vášho článku.
Na väčšine prehliadačov uvidíte tlačidlo \"Prehľadať...\", ktoré
spôsobí zobrazenie štandardného dialógu vášho operačného systému
Otvoriť súbor. Výberom súboru sa automaticky
vyplní meno súboru v textovom rámčeku vedľa tlačidla.

Musíte tiež zaškrtnutím potvrdiť, že nahraním súboru
neporušujete žiadne autorské práva.
Stlačením tlačidla \"Nahrať\" potom ukončíte nahranie.
Ak máte pomalé internetové pripojenie, môže to trvať istý čas.

Uprednostňované formáty sú JPEG pre fotografické obrázky , PNG
pre kresby a iné symboly a OGG pre zvuky.
Prosíme Vás, aby ste svojim súborom dali opisný názov, aby sa zamedzilo zámenám.
Na začlenenie obrázku v článku použite odkaz v tvare '''<nowiki>[[{{ns:image}}:súbor.jpg]]</nowiki>'''
alebo '''<nowiki>[[{{ns:image}}:súbor.png|alt text]]</nowiki>'''
alebo ''<nowiki>'[[{{ns:media}}:súbor.ogg]]</nowiki>''' pre zvuky.

Nezabudnite, že tak ako pri stránkach {{GRAMMAR:genitív|{{SITENAME}}}}, môžu iní upravovať alebo zmazať vaše nahrané súbory, ak si myslia, že to je prospešné encyklopédii, a
nahrávanie vám môže byť znemožnené (zablokované), ak budete zneužívať systém.",

'uploadlog' => 'Záznam nahrávaní',
'uploadlogpage' => 'Záznam nahrávaní',
'uploadlogpagetext' => 'Nižšie je zoznam nedávno nahraných súborov.
Všetky uvedené časy sú časy na servri (UTC).
<ul>
</ul>',
'filename' => 'Názov súboru',
'filedesc' => 'Opis súboru',
'filestatus' => 'Stav autorských práv',
'filesource' => 'Zdroj',
'copyrightpage' => "Project:Autorské práva",
'copyrightpagename' => "{{SITENAME}} copyright",
'uploadedfiles' => 'Nahrané súbory',
'ignorewarning' => 'Ignorovať varovanie a súbor napriek tomu uložiť.',
'minlength' => 'Názvy obrázkov musia obsahovať najmenej tri písmená.',
'illegalfilename' => 'Názov súboru "$1" obsahuje znaky, ktoré nie sú povolené v názvoch článkov. Prosím premenujte súbor a skúste ho nahrať znovu.',
'badfilename' => 'Meno obrázka bolo zmenené na "$1".',
'badfiletype' => "\".$1\" nie je odporúčaný formát obrázkového súboru.",
'largefile' => 'Odporúčame, aby obrázky neprekročili veľkosť $1 bajtov, veľkosť tohto súboru je $2 bajtov',
'emptyfile' => 'Súbor, ktorý ste nahrali sa zdá byť prázdny. Toto mohlo vzniknúť preklepom v názve súboru. Skontrolujte, či skutočne chcete nahrať tento súbor.',
'fileexists' => 'Súbor s týmto názvom už existuje. Skontrolujte $1, ak si nie ste istý, či ho chcete zmeniť.',
'successfulupload' => 'Nahranie bolo úspešné',
'fileuploaded' => "Súbor \"$1\" bol úspešne nahraný.
Nasledujte tento odkaz ($2) na stránku, na ktorej zadáte
informácie na opis súboru, napríklad odkiaľ pochádza, kedy a kým bol
vytvorený a všetko ostatné, čo o ňom prípadne viete.
Ak je nahraný súbor obrázok, možno ho takto vložiť do článku: <tt><nowiki>[[Obrázok:$1|thumb|Opis]]</nowiki></tt>",
'uploadwarning' => 'Varovanie pre nahrávanie',
'savefile' => 'Ulož súbor',
'uploadedimage' => "nahraný \"$1\"",
'uploaddisabled' => 'Prepáčte, nahrávanie je vypnuté.',
'uploadscripted' => 'Tento súbor obsahuje kód HTML alebo skript, ktorý može byť chybne interpretovaný prehliadačom.',
'uploadcorrupt' => 'Tento súbor je závadný alebo má nesprávnu príponu. Skontrolujte súbor a nahrajte ho znova.',
'uploadvirus' => 'Súbor obsahuje virus! Detaily: $1',
'sourcefilename' => 'Názov zdrojového súboru',
'destfilename' => 'Názov cieľového súboru',

# Image list
#
'imagelist' => 'Zoznam nahraných obrázkov',
'imagelisttext' => "Tu je zoznam $1 obrázkov zoradený $2.",
'getimagelist' => 'Sťahujem zoznam nahraných obrázkov',
'ilsubmit' => 'Vyhľadávanie',
'showlast' => "Zobraz posledných $1 obrázkov zoradených $2.",
'byname' => 'podľa mena',
'bydate' => 'podľa dátumu',
'bysize' => 'podľa veľkosti',
'imgdelete' => 'zmazať',
'imgdesc' => 'popis',
'imglegend' => 'Vysvetlivky: (popis) = zobraz/uprav popis obrázku.',
'imghistory' => 'História súboru',
'revertimg' => 'obnov',
'deleteimg' => 'zmazať',
'deleteimgcompletely' => 'Vymaž všetky verzie',
'imghistlegend' => "Vysvetlivky: (aktuálna) = toto je aktuálny obrázok, (zmazať) = zmaž
túto starú verziu, (pôvodná) = vráť sa k tejto starej verzii.
<br />''Kliknite na dátum, aby sa zobrazil obrázok nahraný v ten deň''.",
'imagelinks' => 'Odkazy na obrázok',
'linkstoimage' => 'Na tento obrázok odkazujú nasledujúce články:',
'nolinkstoimage' => 'Žiadne články neobsahujú odkazy na tento obrázok.',
'sharedupload' => "Tento súbor je [[:commons:Main_Page|zdieľaný zdroj]] a môže byť použitý v iných wiki.

<br clear=both>
{| align=center border=0 cellpadding=3 cellspacing=3 style=\"border: solid #aaa 1px; background: #f9f9f9; font-size: 100%;\"
|-
| [[Image:Commons without text.png|20px|Wikimedia Commons Logo]]
|Toto je súbor z [[Commons:Main Page|Wikimedia Commons]]. Prosíme pozrite si jeho '''[[Commons:Image:{{PAGENAME}}|popisnú stránku ]]''' <!--on the Commons-->.
|}",
'shareduploadwiki' => "Ďalšie informácie pozrite na [stránka opisu súboru $1].",
'noimage' => 'Súbor s takým menom neexistuje, môžete ho [$1 nahrať]',
'uploadnewversion' => '[$1 Nahrajte novú verziu tohto súboru.]',

# Statistics
#
'statistics' => 'Štatistiky',
'sitestats' => 'Štatistika webu',
'userstats' => 'Štatistika k redaktorom',
'sitestatstext' => "V databáze je celkovo '''$1''' článkov.
Vrátane \"diskusných\" stránok, článkov o {{GRAMMAR:lokál|{{SITENAME}}}}, extrémne krátkych článkov,
presmerovaní a iných, ktoré asi nemožno považovať za články.
Okrem uvedených, existuje '''$2''' článkov, ktoré možno považovať za právoplatné články.

Celkovo boli stránky navštívené '''$3'''-krát a upravené '''$4'''-krát,
od posledného vylepšenia (upgrade) softvéru (20. júla 2002).
To znamená, že pripadá priemerne '''$5''' úprav na každý článok a '''$6''' návštev na každú úpravu.",
'userstatstext' => "Celkovo je '''$1''' zaregistrovaných redaktorov,
z čoho '''$2''' (alebo '''$4%''') sú administrátormi (pozri $3).",

# Maintenance Page
#
'maintenance' => 'Stránka údržby',
'maintnancepagetext' => 'Táto stránka obsahuje niekoľko praktických funkcií na každodennú údržbu {{GRAMMAR:genitív|{{SITENAME}}}}. Niektoré z týchto funkcií môžu predstavovať veľkú záťaž pre databázu, preto podľa možnosti nerobte reload po každej zmene ;-)',
'maintenancebacklink' => 'Späť na Stránku údržby',
'disambiguations' => 'Stránky na rozlíšenie viacerých významov',
'disambiguationspage' => "Project:Odkazy na stránky na rozlíšenie viacerých významov",
'disambiguationstext' => "Tieto články obsahujú odkazy na ''rozlišovaciu stránku''. Namiesto toho by mali obsahovať odkazy na stránku s príslušnou témou. <br />Stránka sa považuje za rozlišovaciu stránku, ak $1 na ňu obsahuje odkaz.<br />Odkazy z iných menných priestorov tu ''nie'' sú uvedené.",
'doubleredirects' => 'Dvojité presmerovanie',
'doubleredirectstext' => "Každý riadok obsahuje odkaz na prvé a druhé presmerovanie, ako aj prvý riadok z textu, na ktorý odkazuje druhé presmerovanie, ktoré zvyčajne odkazuje na \"skutočný\" cieľ, na ktorý má odkazovať prvé presmerovanie.",
'brokenredirects' => 'Pokazené presmerovania',
'brokenredirectstext' => 'Tieto presmerovania odkazujú na neexistujúci článok.',
'selflinks' => 'Články s odkazmi na seba',
'selflinkstext' => 'Tieto články obsahujú odkazy na seba, čo by nemali.',
'mispeelings' => 'Stránky s nesprávnym pravopisom',
'mispeelingstext' => "Tieto stránky obsahujú bežné pravopisné chyby, ktoré sú uvedené na $1. Slová uvedené v zátvorkách ukazujú správny pravopis.",
'mispeelingspage' => 'Zoznam bežných pravopisných chýb',
'missinglanguagelinks' => 'Chýbajúce jazykové odkazy',
'missinglanguagelinksbutton' => 'Nájdi chýbajúce jazykové odkazy pre',
'missinglanguagelinkstext' => "Tieto články ''neobsahujú'' odkazy k ich náprotivku v $1. Presmerovania a podstránky ''nie'' sú zobrazené.",


# Miscellaneous special pages
#
'orphans' => 'Opustené články',
'geo' => 'GEO súradnice',
'validate' => 'Schváliť článok',
'lonelypages' => 'Opustené články',
'uncategorizedpages' => 'Nekategorizované články',
'uncategorizedcategories' => 'Nekategorizované kategórie',
'unusedimages' => 'Nevyužité súbory',
'popularpages' => 'Populárne články',
'nviews' => '$1 návštev',
'wantedpages' => 'Žiadané články',
'nlinks' => 'počet odkazov: $1',
'allpages' => 'Všetky stránky',
'randompage' => 'Náhodný článok',
'randompage-url'=> 'Special:Randompage',
'shortpages' => 'Krátke články',
'longpages' => 'Dlhé články',
'deadendpages' => 'Slepé stránky',
'listusers' => 'Zoznam redaktorov',
'specialpages' => 'Špeciálne stránky',
'spheading' => 'Špeciálne stránky pre všetkých redaktorov',
'restrictedpheading' => 'Obmedzené špeciálne stránky',
'protectpage' => 'Zamknúť stránku',
'recentchangeslinked' => 'Súvisiace úpravy',
'rclsub' => "(na články, na ktoré odkazuje \"$1\")",
'debug' => 'Ladenie',
'newpages' => 'Nové články',
'ancientpages' => 'Najdávnejšie upravované články',
'intl' => 'Medzijazykové odkazy',
'move' => 'Presuň',
'movethispage' => 'Presuň túto stránku',
'unusedimagestext' => 'Uvedomte si, že niektoré web stránky, môžu odkazovať na tento obrázok
priamo cez URL adresu, preto tu môže byť stále uvedený, napriek tomu, že
ho aktívne používajú.',
'booksources' => 'Knižné zdroje',
'categoriespagetext' => 'Nasledujúce kategórie existujú vo wiki.',
'data' => 'Dáta',
'userrights' => 'Spravovanie redaktorských práv',
'groups' => 'Skupiny redaktorov',

# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
'booksourcetext' => "Nižšie je uvedený zoznam odkazov k iným web stránkam, ktoré
predávajú nové alebo použité knihy a prípadne majú ďalšie informácie
o knihách, ktoré hľadáte.
{{SITENAME}} nie je so žiadnym z týchto predajcov v obchodnom spojení a
tento zoznam nemožno chápať ako ich podporu.",
'isbn' => 'ISBN',
'rfcurl' => 'http://www.faqs.org/rfcs/rfc$1.html',
'pubmedurl' => 'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'alphaindexline' => "$1 do $2",
'version' => 'Zobraz verziu MediaWiki',
'log' => 'Záznamy',
'alllogstext' => 'Kombinované zobrazenie nahrávaní, mazaní, zamknutí, blokovaní a akcií sysopa.
Môžete zmenšiť rozsah, ak zvolíte typ záznamu, meno redaktora alebo dotyčnú stránku.',

# Special:Allpages
'nextpage' => 'Ďalšia stránka ($1)',
'allpagesfrom' => 'Zobraz články od článku:',
'allarticles' => 'Všetky články',
'allnonarticles' => 'Všetky ne-články',
'allinnamespace' => 'Všetky stránky (menný priestor $1)',
'allnotinnamespace' => 'Všetky stránky (nie z menného priestoru $1)',
'allpagesprev' => 'Predchádzajúci',
'allpagesnext' => 'Ďalší',
'allpagessubmit' => 'Choď',

# E this user
#
'mailnologin' => 'Žiadna adresa na zaslanie',
'mailnologintext' => "Musíte byť [[Special:Userlogin|prihlásený]]
a mať platnú e-mailovú adresu vo vašich [[Special:Preferences|nastaveniach]],
aby ste mohli iným redaktorom posielať e-maily.",
'emailuser' => 'Pošli e-mail tomuto redaktorovi',
'emailpage' => 'E-mail redaktorovi',
'emailpagetext' => 'Ak tento redaktor zadal platnú e-mailovú adresu vo svojich nastaveniach,
môžete mu pomocou dole uvedeného formulára poslať e-mail.
E-mailová adresa, ktorú ste zadali vo vašich nastaveniach sa zobrazí
ako adresa odosielateľa e-mailu, aby bol príjemca schopný vám
odpovedať.',
'usermailererror' => 'Mail objekt vrátil chybu: ',
'defemailsubject' => "{{SITENAME}} e-mail",
'noemailtitle' => 'Chýba e-mailová adresa',
'noemailtext' => 'Tento redaktor nezadal platnú e-mailovú adresu,
alebo sa rozhodol, že nebude prijímať e-maily od druhých redaktorov.',
'emailfrom' => 'Odosielateľ',
'emailto' => 'Príjemca',
'emailsubject' => 'Vec',
'emailmessage' => 'Správa',
'emailsend' => 'Odoslať',
'emailsent' => 'E-mail bol odoslaný',
'emailsenttext' => 'Vaša e-mailová správa bola odoslaná.',

# Watchlist
#
'watchlist' => 'Sledované články',
'watchlistsub' => "(pre redaktora \"$1\")",
'nowatchlist' => 'V sledovaných článkoch nemáte žiadne položky.',
'watchnologin' => 'Nie ste prihlásený/á',
'watchnologintext' => "Musíte byť [[Special:Userlogin|prihlásený/á]],
aby ste mohli modifikovať vaše sledované články.",
'addedwatch' => 'Pridaný do sledovaných článkov',
'addedwatchtext' => "Stránka [[$1]] bola pridaná do [[Špeciálne:Watchlist|sledovaných článkov]].
Budú tam uvedené ďalšie úpravy tejto stránky a jej diskusie
a stránka bude zobrazená '''tučne''' v [[Špeciálne:Recentchanges|zozname posledných úprav]], aby ste ju ľahšie našli.

Ak budete chcieť neskôr stránku odstrániť zo sledovaných stránok, kliknite na \"nesleduj\" v horných záložkách.",
'removedwatch' => 'Odstránený zo sledovaných článkov',
'removedwatchtext' => "Článok \"$1\" bol odstránený z vašich sledovaných článkov.",
'watch' => 'Sleduj',
'watchthispage' => 'Sleduj tento článok',
'unwatch' => 'Nesleduj',
'unwatchthispage' => 'Nesleduj tento článok',
'notanarticle' => 'Toto nie je článok',
'watchnochange' => 'V rámci zobrazeného času nebola upravená žiadna z vašich sledovaných stránok.',
'watchdetails' => "* $1 sledovaných stránok, nepočítajúc stránky diskusie, $2 úprav stránok spolu za sledované obdobie;
* Typ dotazu: $3
* [[Special:Watchlist/edit|zobraz a upravuj úplný zoznam.",
'wlheader-enotif' => "* Upozorňovanie e-mailom je zapnuté.",
'wlheader-showupdated' => "* Články, ktoré boli zmené od vašej poslednej návštevy sú zobrazené '''tučne'''.",
'watchmethod-recent'=> 'kontrolujem posledné úpravy sledovaných článkov',
'watchmethod-list' => 'kontrolujem sledované články na posledné úpravy',
'removechecked' => 'Odstráň vybrané položky zo sledovaných článkov',
'watchlistcontains' => "Vaše sledované články obsahujú $1 článkov.",
'watcheditlist' => "Tu je abecedný zoznam vašich
sledovaných článkov. Označte články, ktoré chcete odstrániť
a kliknite na tlačidlo 'Odstráň vybrané'
na spodnej časti obrazovky.",
'removingchecked' => 'Odstraňujem požadované položky zo sledovaných článkov ...',
'couldntremove' => "Nemôžem odstrániť položku '$1'...",
'iteminvalidname' => "Problém s položkou '$1', neplatné meno...",
'wlnote' => "Nižšie je posledných $1 zmien za posledných '''$2''' hodín.",
'wlshowlast' => 'Zobraz posledných $1 hodín $2 dní $3',
'wlsaved' => 'Toto je uložená verzia vašich sledovaných článkov.',
'wlhideshowown' => '$1 moje úpravy.',
'wlshow' => 'Zobraz',
'wlhide' => 'Skry',

'enotif_mailer' => 'Upozorňovač {{GRAMMAR:genitív|{{SITENAME}}}}',
'enotif_reset' => 'Vynulovať upozornenia (nastav ich status na "navštívené")',
'enotif_newpagetext'=> 'Toto je nový článok.',
'changed' => 'zmene',
'created' => 'vytvorení',
'enotif_subject' => '{{SITENAME}} - stránka $PAGETITLE bola $CHANGEDORCREATED $PAGEEDITOR',
'enotif_lastvisited' => 'Pozrite $1 pre všetky zmeny od vašej poslednej návštevy.',
'enotif_body' => 'Drahý $WATCHINGUSERNAME,

na {{GRAMMAR:lokál|{{SITENAME}}}} došlo $PAGEEDITDATE k $CHANGEDORCREATED stránky $PAGETITLE redaktorom $PAGEEDITOR, pozrite si aktuálnu verziu $PAGETITLE_URL .

$NEWPAGE

Zhrnutie: $PAGESUMMARY $PAGEMINOREDIT
Kontaktujte redaktora:
mail $PAGEEDITOR_EMAIL
wiki $PAGEEDITOR_WIKI

Nedostanete ďalšie upozornenia, aj ak bude článok znovu upravovaný, kým nenavštivíte tento článok. Možete tiež vynulovať upozornenia pre všetky vaše sledované stránky.

 Váš upozorňovací systém {{GRAMMAR:genitív|{{SITENAME}}}}

--
Pre zmenu nastavenia vašich sledovaných článkov navštívte
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Návrhy a ďalšia pomoc:
{{SERVER}}{{localurl:Pomoc:Obsah}}',

# Delete/protect/revert
#
'deletepage' => 'Zmazať stránku',
'confirm' => 'Potvrdiť',
'excontent' => "obsah bol: '$1'",
'excontentauthor' => "obsah bol: '$1' (a jediný autor bol '$2')",
'exbeforeblank' => "obsah pred vyčistením stránky bol: '$1'",
'exblank' => 'stránka bola prázdna',
'confirmdelete' => 'Potvrdiť zmazanie',
'deletesub' => "(Mažem \"$1\")",
'historywarning' => 'POZOR: Stránka, ktorú chcete zmazať má históriu: ',
'confirmdeletetext' => "Idete trvalo zmazať z databázy stránku alebo obrázok
spolu so všetkými jeho/jej predchádzajúcimi verziami.
Potvrďte, že máte v úmysle tak urobiť, že ste si vedomý
následkov, a že to robíte
v súlade so [[Project:Zásady a pravidlá]].",
'actioncomplete' => 'Akcia ukončená',
'deletedtext' => "\"$1\" bol zmazaný.
Na $2 nájdete zoznam posledných zmazaní.",
'deletedarticle' => "\"[[$1]]\" zmazaný",
'dellogpage' => 'Záznam zmazaní',
'dellogpagetext' => 'Tu je zoznam posledných zmazaní.',
'deletionlog' => 'záznam zmazaní',
'reverted' => 'Vrátené na skoršiu verziu',
'deletecomment' => 'Dôvod na zmazanie',
'imagereverted' => 'Vrátenie skoršej verzie bolo úspešné.',
'rollback' => 'Rollback úprav',
'rollback_short' => 'Rollback',
'rollbacklink' => 'rollback',
'rollbackfailed' => 'Rollback neúspešný',
'cantrollback' => 'Nemôžem úpravu vrátiť späť, posledný autor je jediný autor tohto článku.',
'alreadyrolled' => "Nemôžem vrátiť späť poslednú úpravu [[$1]]
od [[Redaktor:$2|$2]] ([[Diskusia_s_redaktorom:$2|Diskusia]]); niekto iný buď upravoval stránku, alebo už vrátil späť.

Autorom poslednej úpravy je [[Redaktor:$3|$3]] ([[Diskusia_s_redaktorom:$3|Diskusia]]).",
'editcomment' => "Komentár k úprave bol: \"''$1''\".",
'revertpage' => "Bola obnovená posledná úprava $2 od $1.",
'sessionfailure' => 'Zdá sa, že je problém s vašou prihlasovacou reláciou;
táto akcia bola zrušená ako prevencia proti zneužitiu relácie (session).
Prosím, stlačte "naspäť", obnovte stránku, z ktorej ste sa sem dostali, a skúste to znova.',
'protectlogpage' => 'Záznam_zamknutí',
'protectlogtext' => "Nižšie je zoznam zamknutí/odomknutí stránok.
Pre dodatočné informácie pozrite [[Project:Zamknutá stránka]].",
'protectedarticle' => "zamyká \"[[$1]]\"",
'unprotectedarticle' => "odomyká \"[[$1]]\"",
'protectsub' =>"(Zamykám \"$1\")",
'confirmprotecttext' => 'Skutočne chcete zamknúť túto stránku?',
'confirmprotect' => 'Potvrďte zamknutie',
'protectmoveonly' => 'Zamkni iba presuny stránky',
'protectcomment' => 'Dôvod zamknutia',
'unprotectsub' =>"(Odomykám \"$1\")",
'confirmunprotecttext' => 'Skutočne chcete odomknúť túto stránku?',
'confirmunprotect' => 'Potvrďte odomknutie',
'unprotectcomment' => 'Dôvod odomknutia',

# Undelete
'undelete' => 'Obnov zmazaný článok',
'undeletepage' => 'Zobraz a obnov zmazané články',
'undeletepagetext' => 'Tieto články boli zmazané, ale sú stále v archíve a
môžu byť obnovené. Archív môže byť pravidelne vyprázdnený.',
'undeletearticle' => 'Obnov zmazaný článok',
'undeleterevisions' => "$1 verzií je archivovaných",
'undeletehistory' => 'Ak obnovíte tento článok, obnovia sa aj všetky predchádzajúce verzie do zoznamu predchádzajúcich verzií.
Ak bol od zmazania vytvorený nový článok s tým istým menom, zobrazia sa
obnovené verzie ako posledné úpravy nového článku a aktuálna verzia nového článku
nebude automaticky nahradená.',
'undeleterevision' => "Zmazaná verzia zo dňa a času $1",
'undeletebtn' => 'Obnov!',
'undeletedarticle' => "obnovený \"[[$1]]\"",
'undeletedrevisions' => "$1 verzií obnovených",
'undeletedtext' => "Článok [[$1]] bol úspešne obnovený.
Pozri [[Special:Log/delete]] - zoznam posledných zmazaní a obnovení.",

# Namespace form on various pages
'namespace' => 'Menný priestor:',
'invert' => 'Invertovať výber',

# Contributions
#
'contributions' => 'Príspevky redaktora',
'mycontris' => 'Moje príspevky',
'contribsub' => "Pre $1",
'nocontribs' => 'Neboli nájdené úpravy, ktoré by zodpovedali týmto kritériám.',
'ucnote' => "Nižšie je posledných '''$1''' úprav od tohto redaktora uskutočnených počas posledných '''$2''' dní.",
'uclinks' => "Zobraz posledných $1 úprav; zobraz posledných $2 dní.",
'uctop' => ' (posledná úprava)' ,
'newbies' => 'začiatočníci',

# What links here
#
'whatlinkshere' => 'Odkazy na tento článok',
'notargettitle' => 'Nebol zadaný cieľ',
'notargettext' => 'Nezadali ste cieľový článok alebo redaktora,
na ktorý/-ého chcete aplikovať túto funkciu.',
'linklistsub' => '(Zoznam odkazov)',
'linkshere' => 'Sem odkazujú nasledujúce články::',
'nolinkshere' => 'Sem neodkazujú žiadne články.',
'isredirect' => 'presmerovacia stránka',

# Block/unblock IP
#
'blockip' => 'Zablokovať redaktora',
'blockiptext' => "Použite dolu uvedený formulár na zablokovanie možnosti zápisov uskutočnených
z IP adresy alebo od redaktora.
Mali by ste to urobiť len na zabránenie vandalizmu a
v súlade s [[Project:Zásady a pravidlá]].
Nižšie uveďte konkrétny dôvod (napríklad uveďte konkrétne stránky,
ktoré sa stali obeťou vandalizmu).",
'ipaddress' => 'IP adresa',
'ipadressorusername' => 'IP adresa/meno redaktora',
'ipbexpiry' => 'Ukončenie',
'ipbreason' => 'Dôvod',
'ipbsubmit' => 'Zablokovať tohto redaktora',
'badipaddress' => 'IP adresa má nesprávny formát',
'blockipsuccesssub' => 'Zablokovanie bolo úspešné',
'blockipsuccesstext' => "\"$1\" bol/a zablokovaný/á.
<br /> [[Špeciálne:Ipblocklist|IP block list]] obsahuje zoznam blokovaní.",
'unblockip' => 'Odblokovať redaktora',
'unblockiptext' => 'Použite nižšie uvedený formulár na obnovenie možnosti zápisov
z doteraz zablokovanej IP adresy alebo od redaktora.',
'ipusubmit' => 'Odblokovať túto adresu',
'ipusuccess' => "\"[[$1]]\" odblokovaný/á",
'ipblocklist' => 'Zablokovaní/é redaktori/IP adresy',
'blocklistline' => "$1, $2 zablokoval $3 ($4)",
'infiniteblock' => 'ukončenie infinite', //fixme
'expiringblock' => 'ukončenie $1',
'blocklink' => 'zablokovať',
'unblocklink' => 'odblokuj',
'contribslink' => 'príspevky',
'autoblocker' => "Ste zablokovaný, pretože zdieľate IP adresu s \"$1\". Dôvod \"$2\".",
'blocklogpage' => 'Záznam_blokovaní',
'blocklogentry' => 'zablokovaný "[[$1]]" s časom ukončenia $2',
'blocklogtext' => 'Toto je zoznam blokovaní a odblokovaní redaktorov. Automaticky
blokované IP adresy nie sú zahrnuté. Pozri zoznam
[[Špeciálne:Ipblocklist|IP block]] aktuálnych zákazov a blokovaní.',
'unblocklogentry' => 'odblokovaný/á "$1"',
'range_block_disabled' => 'Schopnosť sysopa vytvárať bloky rozpätí je deaktivovaná.',
'ipb_expiry_invalid' => 'Neplatný čas ukončenia.',
'ip_range_invalid' => "Neplatné rozpätie IP.\n",
'proxyblocker' => 'Blokovač proxy',
'proxyblockreason' => 'Vaša IP adresa bola zablokovaná, pretože je otvorená proxy. Prosím kontaktujte vášho internetového poskytovateľa alebo technickú podporu a informujte ich o tomto vážnom bezpečnostnom probléme.',
'proxyblocksuccess' => "Hotovo.\n",
'sorbs' => 'SORBS DNSBL',
'sorbsreason' => 'Vaša IP adresa je vedená ako nezabezpečený proxy server v [http://www.sorbs.net SORBS] DNSBL.',

# Developer tools
#
'lockdb' => 'Zamknúť databázu',
'unlockdb' => 'Odomknúť databázu',
'lockdbtext' => 'Zamknutím databázy sa preruší možnosť všetkých
redaktorov upravovať stránky, meniť svoje nastavenia, upravovať sledované stránky a
iné veci vyžadujúce zmeny v databáze.
Potvrďte, že to naozaj chcete urobiť, a že
odomknete databázu po ukončení údržby.',
'unlockdbtext' => 'Odomknutie databázy obnoví možnosť všetkých
redaktorov upravovať články, meniť svoje nastavenia, upravovať svoje sledovaných články a
iné veci vyžadujúce zmeny v databáze.
Potvrďte, že to naozaj chcete urobiť.',
'lockconfirm' => 'Áno, naozaj chcem zamknúť databázu.',
'unlockconfirm' => 'Áno, naozaj chcem odomknúť databázu.',
'lockbtn' => 'Zamknúť databázu',
'unlockbtn' => 'Odomknúť databázu',
'locknoconfirm' => 'Neoznačili ste potvrdzovacie pole.',
'lockdbsuccesssub' => 'Zamknutie databázy úspešné',
'unlockdbsuccesssub' => 'Databáza bola úspešne odomknutá',
'lockdbsuccesstext' => 'Databáza bola zamknutá.
<br />Nezabudnite po dokončení údržby odstrániť zámok.',
'unlockdbsuccesstext' => 'Databáza bola odomknutá.',

# Make sysop
'makesysoptitle' => 'Urob z redaktora administrátora',
'makesysoptext' => 'Tento formulár používajú byrokrati na zmenu redaktorov na administrátorov.
Do poľa napíšte meno redaktora a potvrďte zmenu redaktora na administrátora',
'makesysopname' => 'Meno redaktora:',
'makesysopsubmit' => 'Urob z tohto redaktora administrátora',
'makesysopok' => "'''Redaktor \"$1\" je teraz administrátorom (sysop)'''",
'makesysopfail' => "'''Redaktor \"$1\" nemôže byť administrátorom. (Zadali ste meno správne?)'''",
'setbureaucratflag' => 'Nastav byrokratický príznak',
'setstewardflag' => 'Nastav príznak stewarda',
'bureaucratlog' => 'Záznam byrokratov',
'rightslogtext' => 'Toto je záznam zmien redaktorových práv.',
'bureaucratlogentry' => "Zmené členstvo v skupine pre $1 z $2 na $3",
'rights' => 'Práva:',
'set_user_rights' => 'Nastav redaktorove práva',
'user_rights_set' => "'''Práva pre redaktora \"$1\" zmenené'''",
'set_rights_fail' => "'''Redaktorove práva pre \"$1\" nemohli byť nastavené. (zadali ste meno správne?)'''",
'makesysop' => 'Urob z redaktora administrátora (sysop)',
'already_sysop' => 'Tento redaktor už je administrátor',
'already_bureaucrat' => 'Tento redaktor už je byrokrat',
'already_steward' => 'Tento redaktor už je steward',

# Validation
#
'val_yes' => 'áno',
'val_no' => 'nie',
'val_of' => '$1 of $2',
'val_revision' => 'verzie',
'val_time' => 'čas',
'val_user_stats_title' => 'Prehľad overení redaktora $1',
'val_my_stats_title' => 'Môj prehľad overení',
'val_list_header' => '<th>#</th><th>Téma</th><th>Rozsah</th><th>Akcia</th>',
'val_add' => 'Pridaj',
'val_del' => 'Vymaž',
'val_show_my_ratings' => 'Ukáž moje overenia',
'val_revision_number' => 'Verzie #$1',
'val_warning' => "'''Už ''nikdy'' tu nič nemeň bez ''explicitného'' konsenzu komunity!'''",
'val_rev_for' => 'verzie pre ',
'val_details_th_user' => 'Redaktor $1',
'val_validation_of' => 'Overenie "$1"',
'val_revision_of' => 'Verzie $1',
'val_revision_changes_ok' => 'Vaše hodnotenie bolo zaznamenané!',
'val_revision_stats_link' => '(<a href="$1">detaily</a>)',
'val_iamsure' => 'Zaškrtnite políčko, ak to myslíte vážne!',
'val_clear_old' => 'Vymaž moje staršie záznamy overení',
'val_merge_old' => 'Použi moje predchádzajúce hodnotenia tam, kde je zvolené \'Nemám názor\'',
'val_form_note' => "'''Tip:''' Zlúčiť vaše údaje znamená, že pre verziu
článku, ktorý zvolíte, všetky možnosti, kde ste označili ''nemám názor'',
budú nastavené na hodnotu a komentár najčerstvejšej verzie, pre ktorú
ste vyjadrili názor. Napríklad, ak chcete zmeniť jedinú nezávislú možnosť
pre novšiu verziu, ale tiež ponechať vaše ostatné nastavenia pre tento článok
v tejto verzii, zvoľte iba, ktorú možnosť máte v úmysle ''zmeniť'',
a zlúčením sa doplnia ostatné možnosti z vašich predchádzajúcich nastavení",
'val_noop' => 'Nemám názor',
'val_percent' => "'''$1%'''<br />($2 z $3 bodov<br />od $4 redaktorov)",
'val_percent_single' => "'''$1%'''<br />($2 z $3 bodov<br />od jedného redaktora)",
'val_total' => 'Celkovo',
'val_version' => 'Verzie',
'val_tab' => 'Overenie',
'val_this_is_current_version' => 'toto je aktuálna verzia',
'val_version_of' => "Verzie $1" ,
'val_table_header' => "<tr><th>Trieda</th>$1<th colspan=4>Názor</th>$1<th>Poznámka</th></tr>\n",
'val_stat_link_text' => 'Štatistiky overení pre tento článok',
'val_view_version' => 'Zobraz túto verziu',
'val_validate_version' => 'Over túto verziu',
'val_user_validations' => 'Tento redaktor overil $1 článkov.',
'val_no_anon_validation' => 'Musíte byť prihlásený, ak chcete overovať články.',
'val_validate_article_namespace_only' => "Overovať možno iba články. Táto stránka ''nie je'' v mennom priestore pre články.",
'val_validated' => 'Overené.',
'val_article_lists' => 'Zoznam overených článkov',
'val_page_validation_statistics' => 'Štatistika overení pre stránku $1',

# Move page
#
'movepage' => 'Presunúť článok',
'movepagetext' => "Pomocou tohto formulára premenujete článok a premiestnite všetky
jeho predchádzajúce verzie pod zadané nové meno.
Starý názov sa stane presmerovacím článkom na nový názov.
Odkazy na starý článok sa však nezmenia, ubezpečte sa, že ste skontrolovali
výskyt dvojitých alebo pokazených presmerovaní.
Vy ste zodpovedný za to, aby odkazy naďalej ukazovali
tam, kam majú.

Uvedomte si, že článok sa nepremiestni, ak pod novým názvom
už článok existuje. Toto neplatí iba ak je článok prázdny alebo presmerovací a nemá
žiadne predchádzajúce verzie. To znamená, že môžete premenovať článok späť na meno,
ktoré mal pred premenovaním, ak ste sa pomýlili, a že nemôžete prepísať
existujúcí článok.

'''POZOR!'''
Toto môže byť drastická a nečakaná zmena pre populárny článok;
ubezpečte sa preto, skôr ako budete pokračovať, že chápete
dôsledky svojho činu.',
'movepagetalktext' => 'Príslušná Diskusná stránka (ak vôbec existuje) bude premiestnená spolu so samotným článkom; '''nestane sa tak, iba ak:'''
*premiestňujete článok do iného menného priestoru,
*už existuje Diskusná stránka pod týmto novým menom, alebo
*nezaškrtnete nižšie sa nachádzajúci textový rámček.

V takých prípadoch budete musieť, ak si to želáte, premiestniť alebo zlúčiť článok ručne.",
'movearticle' => 'Presuň článok',
'movenologin' => 'Neprihlásený/á',
'movenologintext' => "Musíte byť registrovaný redaktor a [[Special:Userlogin|prihlásený]] aby ste mohli presunúť článok.",
'newtitle' => 'Na nový názov',
'movepagebtn' => 'Presunúť článok',
'pagemovedsub' => 'Presun bol úspešný',
'pagemovedtext' => "Článok \"[[$1]]\" bol presunutý na \"[[$2]]\".",
'articleexists' => 'Stránka s týmto názvom už existuje alebo
vami zadaný názov je neplatný.
Prosím vyberte si iný názov.',
'talkexists' => 'Samotný článok bol úspešne premiestnený,
ale Diskusná stránka sa nedala premiestniť,
pretože už jedna existuje pod zadaným novým názvom. Zlúčte ich manuálne.',
'movedto' => 'presunutý na',
'movetalk' => 'Premiestniť aj "diskusnú" stránku, ak je to možné.',
'talkpagemoved' => 'Príslušná diskusná stránka bola tiež premiestnená.',
'talkpagenotmoved' => 'Príslušná diskusná stránka <strong>nebola</strong> premiestnená.',
'1movedto2' => "[[$1]] premiestnená na [[$2]]",
'1movedto2_redir' => '[[$1]] premiestnená na [[$2]] výmenou presmerovania',
'movelogpage' => 'Záznam presunov',
'movelogpagetext' => 'Tu je zoznam posledných presunutí.',
'movereason' => 'Dôvod',
'revertmove' => 'obnova',
'delete_and_move' => 'Vymaž a presuň',
'delete_and_move_text' =>
'==Potreba zmazať článok==

Cieľový článok "[[$1]]" už existuje. Chcete ho vymazať a vytvoriť tak priestor pre presun?',
'delete_and_move_reason' => 'Vymaž, aby sa umožnil presun',
'selfmove' => "Zdrojový a cieľový názov sú rovnaké; nemôžem presunúť článok na seba samého.",
'immobile_namespace' => "Cieľový názov je špeciálneho typu; nemôžem presunúť článok do tohto menného priestoru.",

# Export

'export' => 'Export stránok',
'exporttext' => 'Môžete exportovať text a históriu úprav konkrétnej
stránky alebo množiny stránok do XML; tieto môžu byť potom importované do iného
wiki používajúceho MediaWiki softvér, zmenené alebo iba ponechané pre
vaše potešenie. (Súčasná verzia MediaWiki nepodporuje import).

Zadajte názvy článkov, každý názov na nový riadok a zvoľte, či chcete všetky alebo iba poslednú verziu.

Ak chcete iba posledné úpravy, ako napr. [[Special:Export/Tank]] pre článok [[Tank]].
',
'exportcuronly' => 'Zahrň iba aktuálnu verziu, nie kompletnú históriu',

# Namespace 8 related

'allmessages' => 'Všetky systémové správy',
'allmessagesname' => 'Názov',
'allmessagesdefault' => 'štandardný text',
'allmessagescurrent' => 'aktuálny text',
'allmessagestext' => 'Toto je zoznam všetkých správ dostupných v mennom priestore MediaWiki.',
'allmessagesnotsupportedUI' => "Special:AllMessages na tejto lokalite (site) nepodporuje jazyk pre vaše rozhranie ('''$1'''). ",
'allmessagesnotsupportedDB' => 'Special:AllMessages nie je podporované, pretože je vypnuté wgUseDatabaseMessages.',

# Thumbnails

'thumbnail-more' => 'Zväčšiť',
'missingimage' => "'''Chýbajúci obrázok'''<br />''$1''\n",
'filemissing' => 'Chýbajúci súbor',

# Special:Import
'import' => 'Import stránok',
'importtext' => 'Prosím exportujte súbor zo zdrojovej wiki pomocou nástroja Špeciálne:Export, uložte na váš disk a nahrajte tu.',
'importfailed' => "Chyba pri importe: $1",
'importnotext' => 'Prázdny alebo žiadny text',
'importsuccess' => 'Import úspešný!',
'importhistoryconflict' => 'Existujú konfliktné histórie verzií (možno ste už tento článok importovali)',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'd',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Hľadaj v tejto wiki. [alt-f]',
'tooltip-minoredit' => 'Označ toto ako drobnú úpravu. [alt-i]',
'tooltip-save' => 'Ulož úpravy. [alt-s]',
'tooltip-preview' => 'Náhľad úprav, prosím použite pred uložením! [alt-p]',
'tooltip-diff' => 'Ukáž, aké zmeny ste urobili v texte. [alt-d]',
'tooltip-compareselectedversions' => 'Zobraz rozdiely medzi dvoma vybranými verziami tohto článku. [alt-v]',
'tooltip-watch' => 'Pridaj túto stránku k sledovaným. [alt-w]',

# stylesheets
#'Monobook.css' => '/* edit this file to customize the monobook skin for the entire site */',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Dublin Core RDF metadata sú pre tento server vypnuté.',
'nocreativecommons' => 'Creative Commons RDF metadata sú pre tento server vypnuté.',
'notacceptable' => 'Wiki server nedokáže poskytovať dáta vo formáte, v akom ich váš klient vie čítať.',

# Attribution

'anonymous' => "Anonymný redaktor/i {{GRAMMAR:genitív{{SITENAME}}}}",
'siteuser' => "Redaktor {{GRAMMAR:genitív{{SITENAME}}}} $1",
'lastmodifiedby' => "Táto stránka bola naposledy upravovaná $1 redaktorom $2.",
'and' => 'a',
'othercontribs' => "Založené na práci redaktora $1.",
'others' => 'iné',
'siteusers' => "Redaktori {{GRAMMAR:genitív{{SITENAME}}}} $1",
'creditspage' => 'Ocenenia za stránku', #LEPSI PREKLAD?
'nocredits' => 'Pre tento článok neexistujú žiadne dostupné ocenenia.', #LEPSI PREKLAD?

# Spam protection

'spamprotectiontitle' => 'Filter na ochranu pred spamom',
'spamprotectiontext' => 'Článok, ktorý ste chceli uložiť, bol blokovaný filtrom na spam. Pravdepodobne to spôsobil link na externú internetovú lokalitu (site).',
'spamprotectionmatch' => 'Nasledujúci text aktivoval náš spam filter: $1',
'subcategorycount' => "V tejto kategórii je $1 podkategórii.",
'subcategorycount1' => "V tejto kategórii je $1 podkategória.",
'categoryarticlecount' => "V tejto kategórii je $1 článkov.",
'categoryarticlecount1' => "V tejto kategórii je $1 článok.",
'usenewcategorypage' => "1\n\nNastavte prvý znak na \"0\" , aby ste vypli nový layout stránky kategórie.",
'listingcontinuesabbrev' => " pokrač.",

# Info page
'infosubtitle' => 'Informácie o stránke',
'numedits' => 'Počet úprav (článok): $1',
'numtalkedits' => 'Počet úprav (diskusia k článku): $1',
'numwatchers' => 'Počet zobrazení: $1',
'numauthors' => 'Počet odlišných autorov (článok): $1',
'numtalkauthors' => 'Počet odlišných autorov (diskusia k článku): $1',

# Math options
'mw_math_png' => 'Vždy vytvor PNG',
'mw_math_simple' => 'Na jednoduché použi HTML, inak PNG',
'mw_math_html' => 'Ak sa dá, použi HTML, inak PNG',
'mw_math_source' => 'Ponechaj TeX (pre textové prehliadače)',
'mw_math_modern' => 'Odporúčame pre moderné prehliadače',
'mw_math_mathml' => 'MathML (experimentálne)',

# Patrolling
'markaspatrolleddiff' => "Označ ako strážené",
'markaspatrolledlink' => "[$1]",
'markaspatrolledtext' => "Označ tento článok ako strážený",
'markedaspatrolled' => "Označené ako strážené",
'markedaspatrolledtext' => "Vybrané verzie boli označené ako strážené.",
'rcpatroldisabled' => "Stráženie posledných zmien bolo vypnut",
'rcpatroldisabledtext' => "Funkcia stráženia posledných zmien je momentálne vypnutá.",

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* tooltips and access keys */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Moja redaktorská stránka\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Stránka redaktora pre ip adresu, ktorú upravujete ako\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Moja diskusná stránka\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Diskusia o úpravách z tejto ip adresy\');
ta[\'pt-preferences\'] = new Array(\'\',\'Moje nastavenia\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Zoznam článkov, na ktorých sledujete zmeny.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Zoznam mojich príspevkov\');
ta[\'pt-login\'] = new Array(\'o\',\'Odporúčame Vám prihlásiť sa, nie je to však povinné.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Odporúčame Vám prihlásiť sa, nie je to však povinné.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Odhlásenie\');
ta[\'ca-talk\'] = new Array(\'t\',\'Diskusia o obsahu článku\');
ta[\'ca-edit\'] = new Array(\'e\',\'Môžete upravovať tento článok. Prosíme, použite tlačidlo náhľad pre uložením.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Pridaj komentár k tejto diskusii.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Tento článok je zamknutý. Môžete však vidieť jeho zdrojový text.\');
ta[\'ca-history\'] = new Array(\'h\',\'Minulé verzie tohto článku.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Zamkni tento článok\');
ta[\'ca-delete\'] = new Array(\'d\',\'Vymaž tento článok\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Obnov úpravy tohto článku až po dobu jeho vymazania\');
ta[\'ca-move\'] = new Array(\'m\',\'Presuň tento článok\');
ta[\'ca-watch\'] = new Array(\'w\',\'Pridať tento článok do sledovaných článkov\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Odstrániť tento článok zo sledovaných článkov\');
ta[\'search\'] = new Array(\'f\',\'Prehľadávanie tejto wiki\');
ta[\'p-logo\'] = new Array(\'\',\'Hlavná stránka\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Navštíviť Hlavnú stránku\');
ta[\'n-portal\'] = new Array(\'\',\'O projekte, ako môžete prispieť, kde čo nájsť\');
ta[\'n-currentevents\'] = new Array(\'\',\'Aktuálne udalosti a ich pozadie\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Zoznam posledných úprav vo wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Zobrazenie náhodného článku\');
ta[\'n-help\'] = new Array(\'\',\'Pozrieť si pomoc.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Podporte nás\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Zoznam všetkých wiki článkov, ktoré sem odkazujú\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Posledné úpravy v článkoch, ktoré odkazujú na túto stránku\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS feed pre túto stránku\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom feed pre túto stránku\');
ta[\'t-contributions\'] = new Array(\'\',\'Pozrieť si zoznam príspevkov od tohto redaktora\');
ta[\'t-emailuser\'] = new Array(\'\',\'Poslať e-mail tomuto redaktorovi\');
ta[\'t-upload\'] = new Array(\'u\',\'Nahranie obrázkových alebo mediálnych súborov\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Zoznam všetkých špeciálnych stránok\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Pozrieť si obsah článku\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Pozrieť si stránku redaktora\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Pozrieť si stránku médii\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Toto je špeciálna stránka, nemôžete ju upravovať.\');
ta[\'ca-nstab-wp\'] = new Array(\'c\',\'Pozrieť si stránku projektu\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Pozrieť si stránku obrázku\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Pozrieť si systémovú stránku\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Pozrieť si šablónu\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Pozrieť si stránku s Pomocou\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Pozrieť si stránku s kategóriami\');
',

# image deletion
'deletedrevision' => 'Zmazať staré verzie $1.',

# browsing diffs
'previousdiff' => '← Choď na predchádzajúcu verziu',
'nextdiff' => 'Choď na ďalšiu verziu →',

'imagemaxsize' => 'Obmedzte obrázky na stránke opisu s obrázkami na: ',
'thumbsize' => 'Veľkosť náhľadu: ',
'showbigimage' => 'Stiahnuť tento obrázok vo väčšom rozlíšení ($1x$2, $3 KB)',

'newimages' => 'Galéria nových obrázkov',
'noimages' => 'Nič na zobrazenie.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Redaktor: ',
'speciallogtitlelabel' => 'Názov: ',

'passwordtooshort' => 'Vaše heslo je príliš krátke. Musí mať dĺžku aspoň $1 znakov.',

# Media Warning
'mediawarning' => '\'\'\'Upozornenie\'\'\': Tento súbor môže obsahovať nebezpečný programový kód, po spustení ktorého by bol váš systém kompromitovaný.
<hr>',
'fileinfo' => '$1KB, MIME : <code>$2</code>',

# Metadata
'metadata' => 'Metadáta',

# external editor support
'edit-externally' => 'Uprav tento súbor pomocou externého programu',
'edit-externally-help' => 'Viac informácii je na [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions].',

# 'all' in various places, this might be different for inflicted languages
'recentchangesall' => 'všetky',
'imagelistall' => 'všetky',
'watchlistall1' => 'všetky',
'watchlistall2' => 'všetky',
'namespacesall' => 'všetky',

# E-mail address confirmation
'confirmemail' => 'Potvrdiť e-mailovú adresu',
'confirmemail_text' => "Táto wiki vyžaduje, aby ste potvrdili platnosť Vašej e-mailovej adresy
pred používaním e-mailových funkcií. Kliknite na tlačidlo dole, aby sa na Vašu adresu odoslal potvrdzovací
e-mail. V e-maili bude aj odkaz obsahujúci kód; načítajte odkaz
do Vášho prehliadača pre potvrdenie, že Vaša e-mailová adresa je platná.",
'confirmemail_send' => 'Odoslať potvrdzovací kód',
'confirmemail_sent' => 'Potvrdzovací e-mail odoslaný.',
'confirmemail_sendfailed' => 'Nebolo možné odoslať potvrdzovací e-mail. Skontrolujte neplatné znaky v adrese.',
'confirmemail_invalid' => 'Neplatný potvrdzovací kód. Kód možno vypršal.',
'confirmemail_success' => 'Vaša e-mailová adresa bola potvrdená. Môžete sa prihlásiť a využívať wiki.',
'confirmemail_loggedin' => 'Vaša e-mailová adresa bola potvrdená.',
'confirmemail_error' => 'Niečo sa pokazilo pri ukladaní vášho potvrdenia.',

'confirmemail_subject' => '{{SITENAME}} - potvrdenie e-mailovej adresy',
'confirmemail_body' => "Niekto, pravdepodobne vy z IP adresy $1, zaregistroval účet
\"$2\" s touto e-mailovou adresou na {{GRAMMAR:lokál|{{SITENAME}}}}.

Pre potvrdenie, že tento účet skutočne patrí Vám a pre aktivovanie
e-mailových funkcií na {{GRAMMAR:lokál|{{SITENAME}}}}, otvorte tento odkaz vo vašom prehliadači:

$3

Ak ste to *neboli* Vy, neotvárajte odkaz. Tento potvrdzovací kód
vyprší o $4.
",


);

class LanguageSk extends LanguageUtf8 {

	function LanguageSk() {
		global $wgNamespaceNamesSk, $wgMetaNamespace;
		LanguageUtf8::LanguageUtf8();
		$wgNamespaceNamesSk[NS_PROJECT_TALK] = 'Diskusia_k_' . $this->convertGrammar( $wgMetaNamespace, 'datív' );
	}

	function getNamespaces() {
		global $wgNamespaceNamesSk;
		return $wgNamespaceNamesSk;
	}


	function getNsIndex( $text ) {
		global $wgNamespaceNamesSk;

		foreach ( $wgNamespaceNamesSk as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with old names:
		if( 0 == strcasecmp( "Komentár", $text ) ) { return NS_TALK; } # 1
		if( 0 == strcasecmp( "Komentár_k_redaktorovi", $text ) ) { return NS_USER_TALK; } # 3
		if( 0 == strcasecmp( "Komentár_k_Wikipédii", $text ) ) { return NS_PROJECT_TALK; }
		if( 0 == strcasecmp( "Komentár_k_obrázku", $text ) ) { return NS_IMAGE_TALK; } # 7
		if( 0 == strcasecmp( "Komentár_k_MediaWiki", $text ) ) { return NS_MEDIAWIKI_TALK; } # 9
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSk;
		return $wgQuickbarSettingsSk;
	}

	function getDateFormats() {
		global $wgDateFormatsSk;
		return $wgDateFormatsSk;
	}

	/**
	 * Exports $wgBookstoreListSk
	 * @return array
	 */
	function getBookstoreList() {
		global $wgBookstoreListSk ;
		return $wgBookstoreListSk ;
	}

	function &getMagicWords() {
		global $wgMagicWordsSk;
		return $wgMagicWordsSk;
	}

	function getMessage( $key ) {
		global $wgAllMessagesSk;
		if($wgAllMessagesSk[$key])
			return $wgAllMessagesSk[$key];
		return parent::getMessage( $key );
	}

	var $digitTransTable = array(
		',' => "\xc2\xa0",
		'.' => ','
	);

	function formatNum( $number ) {
		return strtr($number, $this->digitTransTable );
	}

	# Convert from the nominative form of a noun to some other case
	# Invoked with {{GRAMMAR:case|word}}
	function convertGrammar( $word, $case ) {
		switch ( $case ) {
			case 'genitív':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédie';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníku';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátov';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikikníh';
				}
			break;
			case 'datív':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédii';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníku';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátom';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihám';
				}
			break;
			case 'akuzatív':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédiu';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovník';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitáty';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihy';
				}
			break;
			case 'lokál':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédii';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníku';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátoch';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihách';
				}
			break;
			case 'inštrumentál':
				if ( $word == 'Wikipédia' ) {
					$word = 'Wikipédiou';
				} elseif ( $word == 'Wikislovník' ) {
					$word = 'Wikislovníkom';
				} elseif ( $word == 'Wikicitáty' ) {
					$word = 'Wikicitátmi';
				} elseif ( $word == 'Wikiknihy' ) {
					$word = 'Wikiknihami';
				}
			break;
		}
	return $word;
	}

}
?>
