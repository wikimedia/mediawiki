<?php
/** Czech (česky)
 *
 * @package MediaWiki
 * @subpackage Language
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

$quickbarSettings = array(
	'Žádný', 'Leží vlevo', 'Leží vpravo', 'Visí vlevo'
);

$skinNames = array(
	'standard'            => 'Standard',
	'nostalgia'           => 'Nostalgie',
	'cologneblue'         => 'Kolínská modř',
	'chick'               => 'Kuře'
);

# Hledání knihy podle ISBN
# $wgBookstoreListCs = ..
$bookstoreList = array(
    'Národní knihovna'			=> 'http://sigma.nkp.cz/F/?func=find-a&find_code=ISN&request=$1',
	'Státní technická knihovna' => 'http://www.stk.cz/cgi-bin/dflex/CZE/STK/BROWSE?A=01&V=$1',
	'inherit' => true,
);

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
# Nepoužívá se, pro používání je třeba povolit getMagicWords dole v LanguageCs.
$magicWords = array(
##   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT',        '#PŘESMĚRUJ'     ),
	'notoc'                  => array( 0,    '__NOTOC__',        '__BEZOBSAHU__'  ),
	'forcetoc'               => array( 0,    '__FORCETOC__',     '__VŽDYOBSAH__'  ),
	'toc'                    => array( 0,    '__TOC__',          '__OBSAH__'      ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__BEZEDITOVATČÁST__' ),
	'start'                  => array( 0,    '__START__',        '__ZAČÁTEK__'        ),
	'currentmonth'           => array( 1,    'CURRENTMONTH',     'AKTUÁLNÍMĚSÍC'      ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'AKTUÁLNÍMĚSÍCJMÉNO' ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'AKTUÁLNÍMĚSÍCGEN' ),
#	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV' 'AKTUÁLNÍMĚSÍCZKR'  ),
	'currentday'             => array( 1,    'CURRENTDAY',       'AKTUÁLNÍDEN' ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME',   'AKTUÁLNÍDENJMÉNO'   ),
	'currentyear'            => array( 1,    'CURRENTYEAR',      'AKTUÁLNÍROK'        ),
	'currenttime'            => array( 1,    'CURRENTTIME',      'AKTUÁLNÍČAS'        ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'POČETČLÁNKŮ'        ),
	'pagename'               => array( 1,    'PAGENAME',         'NÁZEVSTRANY'        ),
	'pagenamee'  			 => array( 1,    'PAGENAMEE',        'NÁZEVSTRANYE'       ),
	'namespace'              => array( 1,    'NAMESPACE',        'JMENNÝPROSTOR'      ),
	'msg'                    => array( 0,    'MSG:'                   ),
	'subst'                  => array( 0,    'SUBST:',           'VLOŽIT:'            ),
	'msgnw'                  => array( 0,    'MSGNW:',           'VLOŽITNW:'          ),
	'end'                    => array( 0,    '__END__',          '__KONEC__'          ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'náhled'           ),
	'img_right'              => array( 1,    'right',            'vpravo'             ),
	'img_left'               => array( 1,    'left',             'vlevo'              ),
	'img_none'               => array( 1,    'none',             'žádné'              ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre', 'střed'              ),
	'img_framed'  	         => array( 1,    'framed', 'enframed', 'frame', 'rám'     ),
	'int'                    => array( 0,    'INT:'                   ),
	'sitename'               => array( 1,    'SITENAME',         'NÁZEVSERVERU'       ),
	'ns'                     => array( 0,    'NS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:',        'MÍSTNÍURL:'         ),
	'localurle'              => array( 0,    'LOCALURLE:',       'MÍSTNÍURLE:'        ),
	'server'                 => array( 0,    'SERVER'                 ),
	'revisionid'             => array( 1,    'REVISIONID',       'IDREVIZE'           )
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([a-záčďéěíňóřšťúůýž]+)(.*)$/sDu';

$datePreferences = false;
$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. n. Y',
	'dmy both' => 'H:i, j. n. Y',
);

$messages = array(

# Části textu používané různými stránkami:
'categories' => 'Kategorie',
'category_header' => 'Články v kategorii „$1“',
'subcategories' => 'Podkategorie',

# Dates
'sunday' => 'neděle',
'monday' => 'pondělí',
'tuesday' => 'úterý',
'wednesday' => 'středa',
'thursday' => 'čtvrtek',
'friday' => 'pátek',
'saturday' => 'sobota',

'january' => 'leden',
'february' => 'únor',
'march' => 'březen',
'april' => 'duben',
'may_long' => 'květen',
'june' => 'červen',
'july' => 'červenec',
'august' => 'srpen',
'september' => 'září',
'october' => 'říjen',
'november' => 'listopad',
'december' => 'prosinec',

# genitive month names (see LanguageCs::getMonthNameGen)
'january-gen' => 'ledna',
'february-gen' => 'února',
'march-gen' => 'března',
'april-gen' => 'dubna',
'may-gen' => 'května',
'june-gen' => 'června',
'july-gen' => 'července',
'august-gen' => 'srpna',
'september-gen' => 'září',
'october-gen' => 'října',
'november-gen' => 'listopadu',
'december-gen' => 'prosince',

'jan' => '1.',
'feb' => '2.',
'mar' => '3.',
'apr' => '4.',
'may' => '5.',
'jun' => '6.',
'jul' => '7.',
'aug' => '8.',
'sep' => '9.',
'oct' => '10.',
'nov' => '11.',
'dec' => '12.',

# Písmena, která se mají objevit jako část odkazu ve formě '[[jazyk]]y' atd:
'mainpage'              => 'Hlavní strana',
'mainpagetext'  => 'Wiki software úspěšně nainstalován.',
'mainpagedocfooter' => 'Podívejte se prosím do [http://meta.wikimedia.org/wiki/MediaWiki_i18n dokumentace k nastavení rozhraní] a [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide uživatelské příručky] pro nápovědu k použití a nastavení.',
'portal'                => 'Portál {{grammar:2sg|{{SITENAME}}}}',
'portal-url'            => 'Project:Portál {{grammar:2sg|{{SITENAME}}}}',
'about'                 => 'Úvod',
'aboutsite' => 'O&nbsp;{{grammar:6sg|{{SITENAME}}}}',
'aboutpage'             => 'Project:{{SITENAME}}',
'article' => 'Obsahová stránka',
'help'                  => 'Nápověda',
'helppage'              => 'Help:Obsah',
'bugreports'    => 'Hlášení chyb',
'bugreportspage' => 'Project:Chyby',
'sitesupport'   => 'Sponzorství',
'sitesupport-url' => 'Project:Sponzorství',
'faq'                   => 'Často kladené otázky',
'faqpage'               => 'Project:Často kladené otázky',
'edithelp'              => 'Pomoc při editování',
'newwindow'             => '(otevře se v novém okně)',
'edithelppage'  => 'Help:Jak editovat stránku',
'cancel'                => 'Storno',
'qbfind'                => 'Hledání',
'qbbrowse'              => 'Listování',
'qbedit'                => 'Editování',
'qbpageoptions' => 'Tato stránka',
'qbpageinfo'    => 'Kontext',
'qbmyoptions'   => 'Moje volby',
'qbspecialpages'        => 'Speciální stránky',
'moredotdotdot'	=> 'Další…',
'mypage'                => 'Moje stránka',
'mytalk'        => 'Moje diskuse',
'anontalk'              => 'Diskuse k této IP adrese',

'navigation' => 'Navigace',

'currentevents' => 'Aktuality',
'currentevents-url' => 'Aktuality',

'disclaimers' => 'Vyloučení odpovědnosti',
'disclaimerpage' => 'Project:Vyloučení odpovědnosti',
'privacy' => 'Ochrana osobních údajů',
'privacypage' => 'Project:Ochrana osobních údajů',
'errorpagetitle' => 'Chyba',
'returnto'		=> 'Návrat na stránku „$1“.',
'tagline' => 'Z {{grammar:2sg|{{SITENAME}}}}',
'help'                  => 'Nápověda',
'search'                => 'Hledat',
'searchbutton'          => 'Hledat',
'go'            => 'Jít na', #FIXME
'history'               => 'Historie stránky',
'history_short' => 'Historie',
'updatedmarker' => 'změněno od poslední návštěvy',
'info_short'    => 'Informace',
'printableversion' => 'Verze k tisku',
'permalink'     => 'Trvalý odkaz',
'print' => 'Vytisknout',
'edit' => 'Editovat',
'editthispage'  => 'Editovat stránku',
'delete' => 'Smazat',
'deletethispage' => 'Smazat stránku',
'undelete_short' => 'Obnovit $1 {{plural:$1|verzi|verze|verzí}}',
'protect' => 'Zamknout',
'protectthispage' => 'Zamknout stránku',
'unprotect' => 'Odemknout',
'unprotectthispage' => 'Odemknout stránku',
'newpage' => 'Nová stránka',
'talkpage'              => 'Diskusní stránka',
'specialpage' => 'Speciální stránka',
'personaltools' => 'Osobní nástroje',
'postcomment'   => 'Přidat komentář',
'articlepage'   => 'Prohlédnout si článek',
'talk' => 'Diskuse',
'views' => 'Zobrazení',
'toolbox' => 'Nástroje',
'userpage' => 'Prohlédnout si uživatelovu stránku',
'projectpage' => 'Prohlédnout si stránku o projektu',
'imagepage' =>       'Prohlédnout si stránku o obrázku',
'viewtalkpage' => 'Ukázat diskusi',
'otherlanguages' => 'V jiných jazycích',
'redirectedfrom' => '(Přesměrováno z $1)',
'redirectpagesub' => 'Přesměrování',
'autoredircomment' => 'Přesměrováno na [[$1]]',
'lastmodifiedat'  => ' Stránka byla naposledy editována v $2, $1.',
'viewcount'             => 'Stránka byla zobrazena $1krát.',
'copyright'     => 'Obsah je dostupný pod $1.',
'protectedpage' => 'Zamčená stránka',
'jumpto' => 'Přejít na:',
'jumptonavigation' => 'navigace',
'jumptosearch' => 'hledání',

'badaccess'     => 'Nedostatečná oprávnění',

'versionrequired' => 'Vyžadováno MediaWiki verze $1',
'versionrequiredtext' => 'Pro použití této stránky je vyžadováno MediaWiki verze $1. Vizte [[{{ns:-1}}:Version]].',

'nbytes'        => '$1 {{plural:$1|bajt|bajty|bajtů}}',
'ncategories'		=> '$1 {{plural:$1|kategorie|kategorie|kategorií}}',
'nlinks'        => '$1 {{plural:$1|odkaz|odkazy|odkazů}}',
'nmembers'		  => '$1 {{plural:$1|stránka|stránky|stránek}}',
'nrevisions'		=> '$1 {{plural:$1|revize|revize|revizí}}',
'nviews'        => '$1 zobrazení',

'ok'              => 'OK',
'pagetitle'		=> '$1 - {{SITENAME}}',
'retrievedfrom' => 'Citováno z „$1“', #FIXME: Ukazuje se po tisku strany
'youhavenewmessages' => 'Máte $1 ($2).',
'newmessageslink' => 'nové zprávy',
'newmessagesdifflink' => 'rozdíl oproti předchozí verzi',
'editsection'=>'editovat',
'editold'=>'editovat',
'editsectionhint' => 'Editace části $1',
'toc' => 'Obsah',
'showtoc' => 'zobrazit',
'hidetoc' => 'skrýt',
'thisisdeleted' => 'Prohlédnout nebo obnovit $1?',
'viewdeleted' => 'Zobrazit $1?',
'restorelink' => '{{plural:$1|smazanou editaci|smazané editace|smazaných editací}}',
'feedlinks' => 'Kanály:',
'feed-invalid' => 'Neplatný typ kanálu.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Článek',
'nstab-user' => 'Uživatelova stránka',
'nstab-media' => 'Soubor',
'nstab-special' => 'Speciální',
'nstab-project' => '{{SITENAME}}',
'nstab-image' => 'Soubor',
'nstab-mediawiki' => 'Hlášení',
'nstab-template' => 'Šablona',
'nstab-help' => 'Nápověda',
'nstab-category' => 'Kategorie',

# Main script and global functions
#
'nosuchaction'  => 'Neznámý úkon',
'nosuchactiontext' => 'Tato wiki nezná činnost (action) uvedenou v URL.',
'nosuchspecialpage' => 'Neexistující speciální stránka',
'nospecialpagetext' => 'Žádaná speciální stránka na této wiki neexistuje.',

# General errors
#
'error'         => 'Chyba',
'databaseerror' => 'Databázová chyba',
'dberrortext'   => 'Při dotazu do databáze došlo k syntaktické chybě.
Příčinou může být chyba v programu.
Poslední dotaz byl:
<blockquote><tt>$1</tt></blockquote>
z funkce \'<tt>$2</tt>\'.
MySQL vrátil chybu \'<tt>$3: $4</tt>\'.',
'dberrortextcl' => 'Při dotazu do databáze došlo k syntaktické chybě.
Poslední dotaz byl:
<blockquote><tt>$1</tt></blockquote>
z funkce \'<tt>$2</tt>\'.
MySQL vrátil chybu \'<tt>$3: $4</tt>\'.',
'noconnect'             => 'Promiňte! Tato wiki má nějaké technické potíže a nepodařilo se připojit k databázovém serveru.<br />
$1',
'nodb'                  => 'Nebylo možné vybrat databázi $1',
'cachederror'           => 'Následuje kopie požadované stránky z cache, která nemusí být aktuální.',
'laggedslavemode'       => 'Upozornění: Stránka nemusí být zcela aktuální.',
'readonly'              => 'Databáze je uzamčena',
'enterlockreason' => 'Udejte důvod zamčení, včetně odhadu, za jak dlouho dojde k odemčení.',
'readonlytext'  => 'Databáze je nyní uzamčena, takže nelze ukládat nové doplňky a změny.

Důvodem je pravděpodobně pravidelná údržba, po které se vše vrátí do normálního stavu.
Správce, který databázi zamkl, zanechal následující zprávu: $1',
'missingarticle' => 'Databáze nenašla text článku, který měla najít, nazvaného „$1“.

Důvodem je obvykle zastaralý odkaz do historie smazané stránky.

V jiném případě jste možná narazil(a) na chybu v programu. Oznamte to prosím správci systému (zapamatujte si použité URL).',
'readonly_lag' => 'Databáze byla automaticky dočasně uzamčena kvůli zpoždění ostatních databázových servery proti hlavnímu',
'internalerror' => 'Vnitřní chyba',
'filecopyerror' => 'Nebylo možné zkopírovat soubor  „$1“ na „$2“.',
'filerenameerror' => 'Nebylo možné přejmenovat soubor „$1“ na „$2“.',
'filedeleteerror' => 'Nebylo možné smazat soubor „$1“.',
'filenotfound'  => 'Nebylo možné najít soubor „$1“.',
'unexpected'    => 'Neočekávaná hodnota: "$1"="$2".',
'formerror'             => 'Chyba: nebylo možné odeslat formulář',
'badarticleerror' => 'Tento úkon nelze použít na tento článek.',
'cannotdelete'  => 'Nebylo možné smazat zvolenou stránku ani soubor. (Možná už byla smazána někým jiným.)',
'badtitle'              => 'Neplatný název',
'badtitletext'  => 'Požadovaný název stránky byl neplatný, prázdný nebo nesprávně adresovaný na jinojazyčný název nebo jiný článek {{grammar:2sg|{{SITENAME}}}}.',
'perfdisabled' => 'Omlouváme se. Tato služba byla dočasně znepřístupněna, protože zpomalovala databázi natolik, že nikdo nemohl používat wiki.',
'perfdisabledsub' => 'Tady je uložená kopie z $1:', # obsolete?
'perfcached' => 'Následující data jsou z cache a nemusí být plně aktuální:',
'perfcachedts' => 'Následující data jsou z cache, která byla naposledy aktualizována $1.',
'wrong_wfQuery_params' => 'Nesprávné parametry do wfQuery()<br />
Funkce: $1<br />
Dotaz: $2',
'viewsource' => ' Ukázat zdroj',
'viewsourcefor' => 'stránky $1',
'protectedtext' => 'Tato stránka byla zamčena, takže ji nelze editovat; je mnoho důvodů, proč se tak mohlo stát. Vizte prosím [[{{ns:4}}:Stránka je zamčena]]. Můžete si prohlédnout a okopírovat zdrojový text této stránky:',
'protectedinterface' => 'Tato stránka obsahuje text softwarového rozhraní a smějí ji editovat jen správci.',
'editinginterface' => "'''Upozornění:''' Editujete stránku, která definuje texty rozhraní. Změny této stránky ovlivní vzhled uživatelského rozhraní všem uživatelům.",
'sqlhidden' => '(SQL dotaz skryt)',

# Login and logout pages
#
'logouttitle'   => 'Na shledanou!',
'logouttext'    => 'Nyní jste odhlášeni.<br />
Tento počítač může být používán k prohlížení a editaci {{grammar:2sg|{{SITENAME}}}} bez uživatelského jména, nebo pro přihlášení jiného uživatele. Upozorňujeme, že některé stránky se mohou i nadále zobrazovat, jako byste byli dosud přihlášeni. Tento jev potrvá do smazání cache vašeho prohlížeče.',

'welcomecreation' => '== Vítejte, $1! ==
Váš účet byl vytvořen. Nezapomeňte si upravit své [[Special:Preferences|nastavení]]!',

'loginpagetitle' => 'Přihlaste se', #FIXME
'yourname'              => 'Název vašeho účtu', #FIXME buď heslo nebo jméno uživatele nebo název účtu atd.?
'yourpassword'  => 'Vaše heslo',
'yourpasswordagain' => 'Napište heslo znovu',
'remembermypassword' => 'Trvalé přihlášení',
'yourdomainname'       => 'Vaše doména',
'externaldberror'      => 'Buď nastalo chyba v databázi pro externí autentikaci, nebo nemáte dovoleno měnit svůj externí účet.',
'loginproblem'  => '<b>Nastal problém při vašem přihlášení.</b><br />Zkuste to znovu!',
'alreadyloggedin' => "<strong>Uživateli $1, již jste přihlášen!</strong><br />",

'login'                 => 'Přihlaste se', #FIXME, what exactly do the following go to?
'loginprompt'   => 'K přihlášení do {{grammar:2sg|{{SITENAME}}}} musíte mít povoleny cookies.',
'userlogin'             => 'Přihlaste se',
'logout'                => 'Odhlásit se',
'userlogout'    => 'Na shledanou',
'notloggedin'   => 'Nejste přihlášen(a)',
'nologin'	=> 'Dosud nemáte účet? $1.',
'nologinlink'	=> 'Zaregistrujte se',
'createaccount' => 'Vytvořit nový účet',
'gotaccount'	=> 'Už jste registrováni? $1.',
'gotaccountlink'	=> 'Přihlaste se',
'createaccountmail'     => 'pomocí e-mailu',
'badretype'             => 'Vámi napsaná hesla nesouhlasí.',
'userexists'    => 'Uživatel se stejným jménem je už registrován. Zvolte jiné jméno.',
'youremail'             => 'Vaše e-mailová adresa *)',
'username'		=> 'Uživatelské jméno:',
'uid'			=> 'Uživatelské ID:',
'yourrealname'          => 'Vaše skutečné jméno **)',
'yourlanguage'	=> 'Jazyk rozhraní',
'yourvariant'  => 'Jazyková varianta',
'yournick'              => 'Alternativní podpis',
'badsig'		=> 'Chybný podpis, zkontrolujte syntaxi HTML.',
'email'			=> 'E-mail',
'prefs-help-email-enotif' => 'Na tuto adresu vám budou zasílány informace o změně stránek, pokud o ně požádáte.',
'prefs-help-realname' 	=> '**) Skutečné jméno (volitelné): pokud ho zadáte, bude použito pro označení autorství vaší práce.<br />',
'loginerror'    => 'Chyba při přihlašování',
'prefs-help-email'      => '*) E-mail (volitelný): Umožní ostatním uživatelům vás kontaktovat, aniž by tato adresa byla zobrazena; také vám na tuto adresu může být zasláno nové heslo v případě, že své heslo zapomenete.',
'nocookiesnew'  => 'Uživatelský účet byl vytvřen, ale nejste přihlášeni. {{SITENAME}} používá cookies k přihlášení uživatelů. Vy máte cookies vypnuty. Prosím zapněte je a přihlaste se znovu s vaším novým uživatelským jménem a heslem.',
'nocookieslogin'      => '{{SITENAME}} používá cookies k přihlášení uživatelů. Vy máte cookies vypnuty. Prosím zapněte je a zkuste znovu.',
'noname'                => 'Musíte uvést jméno svého účtu.',
'loginsuccesstitle' => 'Přihlášení uspělo',
'loginsuccess'	=> 'Nyní jste přihlášen na {{grammar:6sg|{{SITENAME}}}} jako uživatel „$1“.',
'nosuchuser'	=> 'Neexistuje uživatel se jménem „$1“. Zkontrolujte zápis, nebo si vytvořte nový účet.',
'nosuchusershort'	=> 'Neexistuje uživatel se jménem „$1“. Zkontrolujte zápis.',
'nouserspecified'	=> 'Musíte zadat uživatelské jméno.',
'wrongpassword' => 'Vámi uvedené heslo nesouhlasí. Zkuste to znovu.',
'wrongpasswordempty'		=> 'Zadané heslo bylo prázdné. Zkuste to znovu.',
'mailmypassword' => 'Poslat e-mailem dočasné heslo',
'passwordremindertitle' => 'Připomenutí ztraceného hesla z {{grammar:2sg|{{SITENAME}}}}',
'passwordremindertext' => 'Někdo (patrně Vy, z IP adresy $1) žádal, abychom Vám poslali nové heslo pro přihlášení do {{SITENAME}} ($4). Heslo pro uživatele "$2" je nyní "$3". Doporučujeme přihlásit se nyní a změnit heslo.

Pokud jste o změnu hesla nežádali nebo jste si na původní heslo již vzpomněli a už ho změnit
nechcete, můžete tuto zprávu ignorovat a používat staré heslo.',
'noemail'		=> 'Uživatel „$1“ nemá zaregistrovanou e-mailovou adresu.',
'passwordsent'	=> 'Dočasné heslo bylo zasláno na e-mailovou adresu registrovanou pro „$1“. Přihlaste se, prosím, znovu, jakmile ho obdržíte.',
'eauthentsent'             =>  'Potvrzovací e-mail byl zaslán na zadanou adresu.
Před tím, než vám na tuto adresu budou moci být zasílány další zprávy, následujte instrukce
v e-mailu, abyste potvrdili, že tato adresa skutečně patří vám.',

'mailerror' => 'Chyba při zasílání e-mailu: $1',
'acct_creation_throttle_hit' => 'Omlouváme se, ale už jste vyrobil(a) $1 účtů. Žádný další už nemůžete vytvořit.',
'emailauthenticated' 	=> 'Vaše e-mailová adresa byla ověřena $1.',
'emailnotauthenticated'	=> 'Vaše e-mailová adresa <strong>dosud nebyla ověřena</strong> a e-mailové funkce do té doby nejsou dostupné.',
'noemailprefs'              => '<strong>Nebyla zadána e-mailová adresa</strong>, následující možnosti jsou nefunkční.',
'emailconfirmlink' => 'Podvrďte svou e-mailovou adresu',
'invalidemailaddress'	=> 'Zadaná e-mailová adresa nemůže být přijata, neboť nemá správný formát. Zadejte laskavě platnou e-mailovou adresu, nebo obsah tohoto pole vymažte.',
'accountcreated' => 'Účet vytvořen',
'accountcreatedtext' => 'Uživatelský účet $1 byl vytvořen.',

# Edit page toolbar
'bold_sample'=>'Tučný text',
'bold_tip'=>' Tučný text',
'italic_sample'=>'Kurzíva',
'italic_tip'=>'Kurzíva',
'link_sample'=>'Název odkazu',
'link_tip'=>'Vnitřní odkaz',
'extlink_sample'=>'http://www.example.com Titulek odkazu',
'extlink_tip'=>'Externí odkaz (nezapomeňte na předponu http://)',
'headline_sample'=>'Text nadpisu',
'headline_tip'=>'Nadpis druhé úrovně',
'math_sample'=>'Vložit sem vzorec',
'math_tip'=>'Matematický vzorec (LaTeX)',
'nowiki_sample'=>' Vložit sem neformátovaný text',
'nowiki_tip'=>'Ignorovat formátování wiki',
'image_sample'=>'Příklad.jpg',
'image_tip'=>'Vložený obrázek',
'media_sample'=>'Příklad.ogg',
'media_tip'=>'Odkaz na mediální soubor',
'sig_tip'=>'Váš podpis s časovým údajem',
'hr_tip'=>'Vodorovná čára (používejte střídmě)',

# Edit pages
#
'summary'               => '<a href="{{LOCALURLE:Project:Shrnutí editace}}" class="internal" title="Stručně popište změny, které jste zde učinili">Shrnutí editace</a>',
'subject'               => 'Předmět/nadpis',
'minoredit'		=> 'Tato změna je malá editace.',
'watchthis'             => 'Sledovat tento článek',
'savearticle'   => 'Uložit změny',
'preview'               => 'Náhled',
'showlivepreview'	=> 'Rychlý náhled',
'showpreview'   => 'Ukázat náhled',
'showdiff'		=> 'Ukázat změny',
'anoneditwarning' => "'''Varování:''' Nejste přihlášen(a). Vaše IP adresa bude zveřejněna v historii této stránky.",
'missingsummary' => "'''Připomenutí:''' Nezadali jste shrnutí editace. Pokud ještě jednou kliknete na Uložit změny, bude vaše editace zapsána bez shrnutí.",
'missingcommenttext' => 'Zadejte komentář',
'blockedtitle'  => 'Uživatel zablokován',
'blockedtext'   => "Pokoušíte se editovat stránku, ať už kliknutím na tlačítko ''Editovat stránku'', nebo na červený odkaz.

Vaše uživatelské jméno nebo IP adresa však byla [[Project:Blokování|zablokována]]
správcem s uživatelským jménem „$1“. Byl uveden následující důvod:&nbsp;'''$2'''.

Můžete [[Special:Emailuser/$4|poslat e-mail uživateli $4]] nebo jinému
[[Special:Listadmins|správci]] k prodiskutování zablokování. Uvědomte si, že nemůžete použít
nabídku „Poslat e-mail“, jestliže nemáte na {{grammar:6sg|{{SITENAME}}}} účet a uvedenu platnou e-mailovou adresu
ve svém [[Special:Preferences|nastavení]].

Vaše IP adresa je '''$3'''. Prosím vložte tuto adresu do všech žádostí, které pošlete.

Pokud chcete vědět, kdy zablokování vyprší, podívejte se prosím na [[Special:Ipblocklist|seznam blokovaných adres IP]].

Pokud se potřebujete podívat na wiki text článku, můžete použít nabídku [[Special:Export|exportovat stránky]].

== Jen číst? ==
Blokování nebrání čtení stránek, jen jejich editaci. Pokud jste si chtěli jen
přečíst stránku a vidíte tuto zprávu, pravděpodobně jste klikli na červený odkaz.
To je odkaz na stránku, která zatím neexistuje, takže se uživateli otevře editační
okénko. Tento problém mít nebudete, pokud budete klikat jen na modré odkazy.",
'whitelistedittitle' => 'Pro editaci je vyžadováno přihlášení',
'whitelistedittext' => 'Pro editaci se musíte $1.',
'whitelistreadtitle' => 'Vyžadováno přihlášení',
'whitelistreadtext' => 'Pro čtení článků se musíte [[Special:Userlogin|přihlásit]].',
'whitelistacctitle' => 'Není vám dovoleno vytvářet uživatelské účty',
'whitelistacctext' => 'Abyste na této wiki mohl(a) vytvářet uživatelské účty, musíte se [[Special:Userlogin|přihlásit]] a mít příslušná oprávnění.',
'confirmedittitle' => 'Vyžadováno e-mailové potvrzení',
'confirmedittext' => 'Pro editaci stránek je vyžadováno potvrzení vaší e-mailové adresy. Na stránce [[Special:Preferences|nastavení]] zadejte a nechte potvrdit svou e-mailovou adresu.',
'loginreqtitle' => 'Vyžadováno přihlášení',
'loginreqlink' => 'přihlásit',
'loginreqpagetext'  => ' K prohlížení jiných stránek se musíte $1.',
'accmailtitle' => 'Heslo odesláno.',
'accmailtext' => 'Heslo pro „$1“ bylo odesláno na $2.',
'newarticle'    => '(Nový)',
'newarticletext' => "Následovali jste odkaz na stránku, která dosud neexistuje.
Pokud ji chcete vytvořit, napište text do rámečku níže a stiskněte tlačítko ''Uložit změny''. Další rady najdete v [[Nápověda:Obsah|nápovědě]].
Pokud jste zde omylem, stiskněte ve svém prohlížeči tlačítko ''Zpět''.",
'anontalkpagetext' => "---- ''Toto je diskusní stránka anonymního uživatele, který si dosud nevytvořil účet nebo ho nepoužívá. Musíme proto použít číselnou [[w:cs:IP adresa|IP adresu]] k jeho identifikaci. Taková IP adresa může být sdílena několika uživateli. Pokud jste anonymní uživatel a cítíte, že jsou Vám adresovány irrelevantní komentáře, prosím [[Special:Userlogin|vytvořte si účet nebo se přihlaste]] a tím se vyhnete budoucí záměně s jinými anonymními uživateli.''",
'noarticletext' => 'Tato stránka zatím neobsahuje žádný text, můžete [[Special:Search/{{PAGENAME}}|zkusit tento název vyhledat]] na jiných stránkách, nebo [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} tuto stránku založit].',
'clearyourcache' => "'''Poznámka:''' Po uložení musíte smazat cache vašeho prohlížeče, jinak změny neuvidíte: '''Mozilla / Firefox:''' ''Ctrl-Shift-R'', '''IE:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror''': ''F5''.",
'usercssjsyoucanpreview' => '<strong>Tip:</strong> Použijte tlačítko „Ukázat náhled“ k testování vašeho nového css/js před uložením.',
'usercsspreview' => "'''Pamatujte, že si prohlížíte jen náhled vašeho uživatelského css, neboť ještě nebylo uloženo!'''",
'userjspreview' => "'''Pamatujte, že testujete a prohlížíte pouze náhled vašeho uživatelského javascriptu, dosud nebyl uložen!'''",
'userinvalidcssjstitle' => "'''Varování:''' Vzhled „$1“ neexistuje. Nezapomeňte, že uživatelské .css a .js soubory používají malá písmena, např. {{ns:User}}:{{BASEPAGENAME}}/monobook.css, nikoli {{ns:User}}:{{BASEPAGENAME}}/Monobook.css.",
'updated'               => '(Změna uložena)', #FIXME: ?
'note'			=> '<strong>Poznámka:</strong>&nbsp;', #FIXME: Where does this come from?
'previewnote'   => 'Pamatujte, že toto je pouze náhled, ne uložení!',
'session_fail_preview' => '<strong>Omlouváme se, ale váš požadavek se nepodařilo zpracovat. Zkuste to prosím znovu. Pokud se tento problém bude opakovat, zkuste se odhlásit a znovu přihlásit.</strong>',
'session_fail_preview_html' => '<strong>Omlouváme se, ale váš požadavek se nepodařilo zpracovat.</strong>

\'\'Jelikož tato wiki má povoleno libovolné HTML, není zobrazen náhled jako prevence proti útokům JavaScriptem.\'\'

Pokud jde o zamýšlenou editaci, zkuste to prosím znovu. Pokud se tento problém bude opakovat, zkuste se odhlásit a znovu přihlásit.</strong>',
'previewconflict' => 'Tento náhled ukazuje text tak, jak bude vypadat po uložení stránky.', #FIXME
'importing' => 'Import stránky $1',
'editing'               => 'Editace stránky $1',
'editinguser'               => 'Editace stránky $1',
'editingsection'		=> 'Editace stránky $1 (část)',
'editingcomment'		=> 'Editace stránky $1 (komentář)',
'editconflict'  => 'Editační konflikt: $1',
'explainconflict' => 'Někdo změnil stránku po započetí vaší editace. Výše vidíte aktuální text článku. Vaše změny jsou uvedeny dole. Musíte sloučit své změny se stávajícím článkem. <b>Pouze</b> výše uvedený text zůstane uchován po kliknutí na „Uložit“. <br />',
'yourtext'              => 'Váš text',
'storedversion' => ' Uložená verze',
'nonunicodebrowser' => '<strong>UPOZORNĚNÍ: Váš prohlížeč není schopen pracovat se znaky [[w:cs:Unicode|Unicode]], pro editaci stránek prosím použijte nějaký jiný.</strong>',
'editingold'    => '<strong>VAROVÁNÍ: Nyní editujete zastaralou verzi této stránky. Když ji uložíte, všechny změny provedené mezitím se ztratí.</strong>',
'yourdiff'              => 'Rozdíly',
'copyrightwarning' => 'Všechny příspěvky do {{grammar:2sg|{{SITENAME}}}} jsou zveřejňovány podle $2 (podrobnosti najdete na $1). Pokud si nepřejete, aby váš text byl nemilosrdně upravován a volně šířen, pak ho do {{grammar:2sg|{{SITENAME}}}} neukládejte.<br />
Uložením příspěvku se zavazujete, že je vaším dílem nebo je zkopírován ze zdrojů, které [[w:cs:volné dílo|nejsou chráněny]] autorským právem (tzv. <em>public domain</em>). <strong>Nekopírujte díla chráněná autorským právem bez dovolení!</strong>',
'copyrightwarning2' => 'Uvědomte si, že všechny příspěvky do {{grammar:2sg|{{SITENAME}}}} mohou být ostatními uživateli upraveny, pozměněny či odstraněny. Pokud si nepřejete, aby váš text byl nemilosrdně upravován, pak ho do {{grammar:2sg|{{SITENAME}}}} neukládejte.<br />
Uložením příspěvku se zavazujete, že je vaším dílem nebo je zkopírován ze zdrojů, které [[w:cs:volné dílo|nejsou chráněny]] autorským právem (tzv. <em>public domain</em>), podrobnosti najdete na $1. <strong>Nekopírujte díla chráněná autorským právem bez dovolení!</strong>',
'longpagewarning' => 'VAROVÁNÍ: Tato stránka je $1 KB dlouhá; některé prohlížeče mohou mít problémy s editováním stran, které se blíží nebo jsou delší než 32 KB. Prosím zvažte rozdělení stránky na více částí.',
'longpageerror' => '<strong>CHYBA: Pokoušíte se uložit text o velikosti $1 KB, přičemž dovolené maximum je $2 KB. Vaše editace nemůže být uložena.</strong>',
'readonlywarning' => 'VAROVÁNÍ: Databáze byla uzamčena kvůli údržbě, takže nebudete moci uložit své změny. Můžete si okopírovat text do souboru a uložit ho na později.',
'protectedpagewarning' => '<strong>Varování:</strong> Tato stránka byla zamčena, takže ji mohou editovat pouze správci. Ujistěte se, že dodržujete [[Project:Pravidla pro zamčené stránky|pravidla pro zamčené stránky]].',
'semiprotectedpagewarning' => '<strong>Poznámka:</strong> Tato stránka byla zamčena, takže ji mohou editovat pouze registrovaní uživatelé.',
'templatesused'	=> 'Šablony používané na této stránce:',
'edittools' => '<!-- Tento text bude zobrazen pod formuláři pro editaci stránek a načítání souborů. -->',
'nocreatetitle' => 'Vytváření nových stránek je omezeno',
'nocreatetext' => 'Na tomto serveru je možnost vytváření nových stránek omezena.
Můžete se vrátit a editovat již existující stránku, nebo [[Special:Userlogin|se přihlásit či se registrovat]].',

# History pages
#
'revhistory'    => 'Historie editací',
'nohistory'             => 'O této stránce neexistuje historie editací.',
'revnotfound'   => 'Verze nenalezena',
'revnotfoundtext' => 'Nelze najít starou verzi, kterou žádáte. Zkuste prosím zkontrolovat URL hledané stránky.\b',
'loadhist'              => 'Načítá se stránka historie editací', #FIXME Apparently not used
'currentrev'    => 'Aktuální verze',
'revisionasof'          => 'Verze z $1',
'previousrevision' => '← Starší verze',
'nextrevision'		=> 'Novější verze →',
'currentrevisionlink'   => 'zobrazit aktuální verzi',
'cur'                   => 'teď',
'next'                  => 'násl',
'last'                  => 'předchozí',
'orig'                  => 'původní',
'histlegend'    => '(teď) = rozdíly oproti nynější verzi, (předchozí) = rozdíly oproti předchozí verzi, <b>m</b> = malá editace',
'deletedrev'			=> '[smazáno]',
'histfirst' => 'Nejstarší',
'histlast' => 'Nejnovější',
'rev-deleted-comment' => '(komentář odstraněn)',
'rev-deleted-user' => '(uživatelské jméno odstraněno)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Tato revize byla odstraněna z veřejného archivu.
Podrobnosti o smazání mohou být uvedeny v [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} knize smazaných stránek].
</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">
Tato revize byla odstraněna z veřejného archivu.
Jako správce si ji však můžete prohlédnout;
podrobnosti o smazání mohou být uvedeny v [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} knize smazaných stránek].
</div>',
'rev-delundel' => 'skrýt/zobrazit',

'history-feed-title' => 'Historie editací',
'history-feed-description'	=> 'Historie editací této stránky',
'history-feed-item-nocomment' => '$1 v $2',
'history-feed-empty' => 'Požadovaná stránka neexistuje.
Mohla být smazána či přejmenována.
Zkuste [[Special:Search|hledání]].',

# Revision deletion
#
'revisiondelete' => 'Smazat/obnovit revize',
'revdelete-selected' => 'Zvolené revize [[:$1]]:',
'revdelete-text' => 'Smazané revize budou nadále zobrazeny v historii stránky, ale jejich text nebude veřejně dostupný.

Ostatní správci této wiki si budou moci skrytý obsah prohlížet a pomocí stejného rozhraní jej také obnovit,
pokud to provozovatel serveru nezakázal.',
'revdelete-legend' => 'Nastavit omezení k revizi:',
'revdelete-hide-text' => 'Skrýt text revize',
'revdelete-hide-comment' => 'Skrýt editační komentář',
'revdelete-hide-user' => 'Skrýt uživatelovu IP adresu',
'revdelete-hide-restricted' => 'Tato omezení aplikovat i na správce',
'revdelete-log' => 'Komentář:',
'revdelete-submit' => 'Aplikovat nastavení',
'revdelete-logentry' => 'změnil viditelnost revizí u [[$1]]',

# Diffs
#
'difference'    => '(Rozdíly mezi verzemi)',
'loadingrev'    => 'načítají se verze pro zjištění rozdílů', #FIXME Apparently not used
'lineno'                => 'Řádka $1:',
'editcurrent'   => ' Editovat nynější verzi této stránky',
'selectnewerversionfordiff' => 'Vyberte novější verzi pro porovnání',
'selectolderversionfordiff' => 'Vyberte starší verzi pro porovnání',
'compareselectedversions' => 'Porovnat vybrané verze',

# Search results
#
'searchresults' => 'Výsledky hledání',
'searchresulttext' => 'Pro více informací o tom, jak hledat na {{grammar:6sg|{{SITENAME}}}}, se podívejte na [[Nápověda:Hledání]].',
'searchsubtitle'	=> 'Hledáno „[[:$1]]“',
'searchsubtitleinvalid'	=> 'Hledáno „$1“',
'badquery'              => 'Špatně vytvořený vyhledávací dotaz',
'badquerytext'  => 'Nemůžeme zpracovat vaše zadání. Je to pravděpodobně tím, že hledáte slovo kratší než tři písmena, což zatím není podporováno. Může to být také tím, že zadání bylo napsáno nesprávně. Prosím zkuste jiné zadání.',
'matchtotals'	=> 'Zadanému „$1“ odpovídá $2 názvů stran a text $3 stran.',
'noexactmatch' => '      Neexistuje žádná stránka, která by měla přesně tento název, zkouším plnotextové vyhledávání.',
'titlematches'  => 'Stránky s odpovídajícím názvem',
'notitlematches' => 'Žádné stránky názvem neodpovídají.',
'textmatches'   => 'Stránky s odpovídajícím textem',
'notextmatches' => 'Žádné stránky textem neodpovídají.',
'prevn'                 => '$1 předchozích',
'nextn'                 => '$1 následujících',
'viewprevnext'  => 'Ukázat ($1) ($2) ($3).',
'showingresults' => 'Zobrazuji <strong>$1</strong> {{plural:$1|výsledek|výsledky|výsledků}} počínaje od <strong>$2</strong>.',
'showingresultsnum' => 'Zobrazuji <strong>$3</strong> {{plural:$3|výsledek|výsledky|výsledků}} počínaje od <strong>$2</strong>.',
'nonefound'             => '<strong>Poznámka</strong>: neúspěšná hledání jsou často důsledkem zadání slov, která nejsou indexována, nebo uvedením mnoha slov najednou (ve výsledku se objeví jen ty stránky, které obsahují všechna zadaná slova).',
'powersearch' => 'Hledání',
'powersearchtext' => 'Hledat',
'powersearchtext' => '
Hledat ve jmenných prostorech:<br />
$1<br />
$2 Vypsat přesměrování &nbsp; Hledat $3 $9',
'searchdisabled' => '<p>Omlouváme se. Plnotextové vyhledávání je dočasně nedostupné. Zatím můžete zkusit vyhledávání Googlem; je ale možné, že jeho výsledky nemusí být aktuální.</p>',
'blanknamespace' => '(Hlavní)',

# Preferences page
#
'preferences'   => 'Nastavení',
'prefsnologin' => 'Nejste přihlášen(a)!',
'prefsnologintext'      => 'Pro nastavení se musíte [[Special:Userlogin|přihlásit]].',
'prefsreset'    => 'Nastavení vráceno.', #FIXME: Hmm...
'qbsettings'    => 'Nastavení lišty nástrojů',
'changepassword' => 'Změna hesla',
'skin'                  => 'Styl',
'math'                  => 'Matematika',
'dateformat'    => 'Formát data',
'datedefault'		=> 'Implicitní',
'datetime'		=> 'Datum a čas',
'math_failure'          => 'Nelze pochopit',
'math_unknown_error'    => 'neznámá chyba',
'math_unknown_function' => 'neznámá funkce',
'math_lexing_error'     => 'chyba při lexingu',   #FIXME
'math_syntax_error'     => 'syntaktická chyba',
'math_image_error'      => 'Selhala konverze do PNG; zkontrolujte správnou instalaci latexu, dvips, gs a convertu',
'math_bad_tmpdir'       => 'Nelze zapsat nebo vytvořit dočasný adresář pro matematiku',
'math_bad_output'       => 'Nelze zapsat nebo vytvořit adresář pro výstup matematiky',
'math_notexvc'  => 'Chybí spustitelný texvc; podívejte se prosím do math/README na konfiguraci.',
'prefs-personal' => 'Údaje o uživateli',
'prefs-rc' => ' Poslední změny',
'prefs-watchlist' => 'Sledované stránky',
'prefs-watchlist-days' => 'Počet dní zobrazených ve sledovaných stránkách:',
'prefs-watchlist-edits' => 'Počet editací zobrazených na zdokonalených sledovaných stránkách:',
'prefs-misc' => ' Různé',
'saveprefs'             => 'Uložit nastavení',
'resetprefs'    => 'Vrátit původní nastavení',
'oldpassword'   => 'Staré heslo',
'newpassword'   => 'Nové heslo',
'retypenew'             => 'Napište znovu nové heslo',
'textboxsize'   => 'Editace',
'rows'                  => 'Řádky',
'columns'               => 'Sloupce',
'searchresultshead' => 'Vyhledávání',
'resultsperpage' => 'Počet nalezených článků na jednu stránku výsledků',
'contextlines'  => ' Počet řádek zobrazených z každé nalezené stránky',
'contextchars'  => ' Počet znaků kontextu na každé řádce',
'stubthreshold' => 'Hranice pro zobrazení pahýlu',
'recentchangescount' => 'Počet zobrazených záznamů v posledních změnách',
'savedprefs'    => 'Vaše nastavení bylo uloženo.',
'timezonelegend' => 'Časové pásmo',
'timezonetext'	=> 'Označte, o kolik se vaše časové pásmo liší od serveru (UTC). Například pro středoevropské časové pásmo (SEČ) vyplňte „01:00“ v zimě, „02:00“ v období platnosti letního času.',
'localtime'     => 'Místní časové pásmo',
'timezoneoffset' => 'Posun',
'servertime'	=> 'Aktuální čas na serveru',
'guesstimezone' => 'Načíst z prohlížeče',
'allowemail'	=> 'Dovolit e-mail od ostatních uživatelů',
'defaultns'     => 'Implicitně hledat v těchto jmenných prostorech:',
'default'		=> 'implicitní',
'files'			=> 'Soubory',

# User levels special page
#
'userrights-lookup-user' => 'Spravovat uživatelské skupiny',
'userrights-user-editname' => 'Zadejte uživatelské jméno:',
'editusergroup' => 'Upravit uživatelskou skupinu',

# user groups editing
'userrights-editusergroup' => 'Upravit uživatelské skupiny',
'saveusergroups' => 'Uložit uživatelské skupiny',
'userrights-groupsmember' => 'Člen skupin:',
'userrights-groupsavailable' => 'Dostupné skupiny:',
'userrights-groupshelp' => 'Zvolte skupiny, do/ze kterých chcete uživatele přidat/odebrat.
Nezvolené skupiny nebudou změněny. Skupinu můžete vyřadit z vybraných pomocí CTRL + Levé tlačítko myši',

# Default group names and descriptions
#
'group'                   => 'Skupina:',
'group-bot'               => 'Boti',
'group-sysop'             => 'Správci',
'group-bureaucrat'        => 'Byrokraté',
'group-all'               => '(všichni)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Správce',
'group-bureaucrat-member' => 'Byrokrat',

'grouppage-bot' => '{{ns:Project}}:Boti',
'grouppage-sysop' => '{{ns:Project}}:Správci',
'grouppage-bureaucrat' => '{{ns:Project}}:Byrokraté',

# Recent changes
#
'changes' => 'změny',
'recentchanges' => 'Poslední změny',
'recentchangestext' => 'Sledujte poslední změny na {{grammar:6sg|{{SITENAME}}}} na této stránce.',
'rcnote'            => 'Níže {{plural:$1|je poslední|jsou poslední|je posledních}} <strong>$1</strong> {{plural:$1|změna|změny|změn}} za {{plural:$2|poslední|poslední|posledních}} <strong>$2</strong> {{plural:$2|den|dny|dnů}} před $3.',
'rcnotefrom'        => 'Níže {{plural:$1|je|jsou|je}} nejvýše <b>$1</b> {{plural:$1|změna|změny|změn}} od <b>$2</b>.',
'rclistfrom'    => 'Ukázat nové změny, počínaje od $1',
'rcshowhideminor' => '$1 malé editace',
'rcshowhidebots' => '$1 roboty',
'rcshowhideliu' => '$1 přihlášené uživatele',
'rcshowhideanons' => '$1 anonymní uživatele',
'rcshowhidepatr' => '$1 prověřené editace',
'rcshowhidemine' => '$1 moje editace',
'rclinks'               => 'Ukázat $1 posledních změn během posledních $2 dnů; $3.',
'diff'                  => 'rozdíl',
'hist'                  => 'historie',
'hide'                  => 'skrýt',
'show'                  => 'ukázat',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'sectionlink' => '→',
'number_of_watching_users_pageview' 	=> '[$1 {{plural:$1|sledující uživatel|sledující uživatelé|sledujících uživatelů}}]',
'rc_categories'	=> 'Omezit na kategorie (oddělené „|“)',
'rc_categories_any'	=> 'Všechny',

# Upload
#
'upload'                => 'Načíst soubor',
'uploadbtn'             => 'Načíst soubor',
'reupload'              => 'Načíst znovu',
'reuploaddesc'  => 'Vrátit se k načtení.',
'uploadnologin' => 'Nejste přihlášen(a)',
'uploadnologintext'     => 'Pro načtení souboru se musíte [[Special:Userlogin|přihlásit]].',
'upload_directory_read_only' => 'Do adresáře pro načítané soubory ($1) nemá webserver právo zápisu.',
'uploaderror'   => 'Při načítání došlo k chybě',
'uploadtext'	=> "
'''POZOR!''' Před nahráváním souborů si zcela určitě přečtěte
[[Project:Pravidla použití obrázků|pravidla použití obrázků]]
a dodržujte je.

Pro prohlížení a hledání již dříve nahraných souborů se podívejte
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
<code><nowiki>[[{{ns:6}}:soubor.jpg]]</nowiki></code> nebo
<code><nowiki>[[{{ns:6}}:soubor.png|popisek]]</nowiki></code>, popř.
<code><nowiki>[[{{ns:-2}}:soubor.ogg]]</nowiki></code> pro zvuky.

Uvědomte si laskavě, že stejně jako u ostatních wikistránek mohou
ostatní uživatelé vámi nahraný soubor smazat či upravit, pokud to
uznají za vhodné; pokud budete tuto funkci zneužívat, může být
váš uživatelský účet zablokován.",
'uploadlog'		=> 'kniha nahrávek',
'uploadlogpage' => 'Kniha_nahrávek',
'uploadlogpagetext' => 'Níže najdete seznam nejnovějších souborů.',
'filename' => 'Soubor',
'filedesc' => 'Popis',
'fileuploadsummary' => 'Popis:',
'filestatus' => 'Autorská práva',
'filesource' => 'Zdroj',
'copyrightpage' => '{{ns:4}}:Autorské právo',
'copyrightpagename' => 'podmínek {{grammar:2sg|{{SITENAME}}}}',
'uploadedfiles' => 'Načtené soubory',
'ignorewarning'        => 'Ignorovat varování a načíst soubor.',
'ignorewarnings'	=> 'Ignorovat všechna varování',
'minlength'             => 'Jméno souboru se musí skládat nejméně ze tří písmen.',
'illegalfilename'       => 'Název souboru "$1" obsahuje znaky, které nejsou povoleny v názvech stránek. Prosím přejmenujte soubor a zkuste jej nahrát znovu.',
'badfilename'	=> 'Jméno souboru bylo změněno na „$1“.',
'badfiletype'	=> '„.$1“ není jeden z dovolených typů souborů.',
'largefile'             => 'Doporučuje se, aby délka souboru nepřesahovala $1&nbsp;B, tento soubor má $2&nbsp;B.',
'largefileserver' => 'Velikost tohoto souboru překračuje limit nastavený na serveru.',
'emptyfile'             => 'Soubor, který jste vložili, se zdá být prázdný. Mohl to způsobit překlep v názvu souboru. Prosím zkontrolujte, zda jste opravdu chtěli vložit tento soubor.',
'fileexists'    => ' Soubor s tímto jménem již existuje, prosím podívejte se na $1, pokud nevíte jistě, zda chcete tento soubor nahradit.',
'fileexists-forbidden' => 'Soubor s tímto názvem již existuje; vraťte se a zvolte jiný název. [[Soubor:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Soubor s tímto názvem již existuje ve sdíleném úložišti; vraťte se a zvolte jiný název. [[Soubor:$1|thumb|center|$1]]',
'successfulupload' => 'Načtení úspěšně provedeno!',
'fileuploaded'	=> 'Soubor „$1“ byl úspěšně načten. Prosím klikněte na tento odkaz: ($2), který vede na stránku popisu a napište tam informace o souboru: odkud pochází, kdy byl vytvořen a kým; a cokoliv dalšího, co o něm můžete vědět. Pokud je to obrázek, můžete ho do stránek vložit takto: <tt><nowiki>[[</nowiki>{{ns:6}}:$1|thumb|Nadpis]]</tt>',
'uploadwarning' => 'Varování',
'savefile'              => 'Uložit soubor',
'uploadedimage' => 'načítá „[[$1]]“',
'uploaddisabled' => 'Načítání souborů vypnuto.',
'uploaddisabledtext' => 'Promiňte, ale načítání souborů je na této wiki vypnuto.',
'uploadscripted' => 'Tento soubor obsahuje HTML nebo kód skriptu, který by mohl být prohlížečem chybně interpretován.',
'uploadcorrupt' => 'Soubor je poškozen nebo nemá správnou příponu. Zkontrolujte prosím soubor a zkuste ho načíst znovu.',
'uploadvirus' => 'Tento soubor obsahuje virus! Podrobnosti: $1',
'sourcefilename' => 'Jméno zdrojového souboru',
'destfilename' => 'Cílové jméno',
'filewasdeleted' => 'Soubor stejného jména byl již dříve načten a posléze smazán. Podrobnosti obsahuje $1.', #TODO: grammar...??

'license' => 'Licence',
'nolicense' => 'Bez udání licence',

# Image list
#
'imagelist'             => 'Seznam načtených obrázků',
'imagelisttext' => 'Níže je seznam $1 obrázků, setříděných $2.',
'imagelistforuser' => 'Tento seznam obsahuje jen soubory načtené uživatelem $1.',
'getimagelist'  => 'načítám seznam obrázků',
'ilsubmit'              => 'Hledat',
'showlast'              => 'Ukázat posledních $1 obrázků tříděných $2.',
'byname'                => 'podle jména',
'bydate'                => 'podle data',
'bysize'                => 'podle velikosti',
'imgdelete'             => 'smazat',
'imgdesc'               => 'popis',
'imglegend'             => '(popis) = ukázat / editovat popis souboru.',
'imghistory'    => 'Historie načtených souborů',
'revertimg'             => 'vrátit',
'deleteimg'             => 'smazat',
'deleteimgcompletely'           => 'smazat úplně',
'imghistlegend' => '(teď) = toto je současná verze souboru, (smazat úplně) = smazat všechny verze tohoto souboru, (smazat) = smazat jen tuto verzi, (vrátit) = obnovit starou verzi. <br /> <i>Klikněte na datum pro zobrazení obrázku, který byl uložen v ten den.</i>',
'imagelinks'    => 'Odkazy k souboru',
'linkstoimage'  => 'Na soubor odkazují tyto stránky:',
'nolinkstoimage' => 'Na tento soubor neodkazuje žádná stránka.',
'sharedupload' => 'Tento soubor je sdílený a může být používán ostatními projekty.',
'shareduploadwiki' => 'Více informací najdete na $1.',
'shareduploadwiki-linktext' => 'stránce s popisem',
'noimage'       => 'Soubor s tímto jménem neexistuje, můžete ho $1',
'noimage-linktext' => 'načíst',
'uploadnewversion-linktext' => 'Načíst novou verzi tohoto souboru',

# Mime search
#
'mimesearch' => 'Hledání podle MIME typu',
'mimetype' => 'MIME typ:',
'download' => 'download',

# Unwatchedpages
#
'unwatchedpages' => 'Nesledované stránky',

# List redirects
'listredirects' => 'Seznam přesměrování',

# Unused templates
'unusedtemplates' => 'Nepoužívané šablony',
'unusedtemplatestext' => 'Tato stránka obsahuje seznam všech stran ve jmenném prostoru {{ns:-1}}, které nejsou vloženy do žádné jiné strany. Před jejich smazáním nezapomeňte zkontrolovat ostatní odkazy.',
'unusedtemplateswlh' => 'ostatní odkazy',

# Random redirect
'randomredirect' => 'Náhodné přesměrování',

# Statistics
#
'statistics'    => 'Statistika',
'sitestats'             => 'O serveru',
'userstats'             => 'O uživatelích',
'sitestatstext' => "V databázi je celkem '''$1''' {{plural:$1|stránka|stránky|stránek}}. Toto číslo zahrnuje diskusní stránky, stránky o {{grammar:6sg|{{SITENAME}}}}, pahýly, přesměrování a další, které nejsou články v pravém slova smyslu. Kromě nich zbývá '''$2''' pravděpodobně {{plural:$2|skutečný článek|skutečné články|skutečných článků}}.

{{plural:$8|Byl načten|Byly načteny|Bylo načteno}} '''$8''' {{plural:$8|obrázek|obrázky|obrázků}}.

Od založení wiki bylo navštíveno celkem '''$3''' stránek a editováno '''$4'''krát. To činí v průměru '''$5''' editací na stránku a '''$6''' návštěv na editaci.

Aktuální délka fronty údržby je '''$7'''.",
'userstatstext' => "Je zde '''$1''' {{plural:$1|registrovaný uživatel|registrovaní uživatelé|registrovaných uživatelů}}, z toho '''$2''' {{plural:$2|správce|správci|správců}} (podívejte se na $3).",

# Maintenance Page
#
'disambiguations'       => 'Stránky odkazující na rozcestníky',
'disambiguationspage'   => '{{ns:10}}:Rozcestník',
'disambiguationstext'   => 'Následující články odkazují na rozcestník. Místo toho by měly by odkazovat na příslušný článek s konkrétním významem.<br /> Článek je považován za rozcestník v případě, že odkazuje na $1. Stránky z jiných jmenných prostorů zde <em>nejsou</em> uvedeny.',
'doubleredirects'       => 'Dvojitá přesměrování',
'doubleredirectstext'	=> 'Každý řádek obsahuje odkaz na první a druhé přesměrování, plus první řádek textu druhého přesměrování, který obvykle ukazuje jméno „skutečného“ hlavního článku, na který by mělo první přesměrování odkazovat.',
'brokenredirects'       => 'Přerušená přesměrování',
'brokenredirectstext'   => ' Tato přesměrování vedou na neexistující články.',

# Miscellaneous special pages
#
'lonelypages'   => 'Sirotčí články',
'uncategorizedpages'	=> 'Nekategorizované stránky',
'uncategorizedcategories'	=> 'Nekategorizované kategorie',
'unusedimages'  => 'Nepoužívané obrázky a soubory',
'unusedcategories' => 'Nepoužívané kategorie',
'popularpages'  => 'Nejnavštěvovanější stránky',
'wantedcategories' => 'Žádané kategorie',
'wantedpages'   => 'Žádoucí články',
'mostlinked'	=> 'Nejodkazovanější stránky',
'mostlinkedcategories' => 'Nejpoužívanější kategorie',
'mostcategories' => 'Články s nejvyšším počtem kategorií',
'mostimages'	=> 'Nejpoužívanější soubory',
'mostrevisions' => 'Stránky s nejvíce revizemi',
'allpages'              => 'Všechny stránky',
'prefixindex'   => 'Seznam stránek podle začátku názvu',
'randompage'    => 'Náhodná stránka',
'shortpages'    => 'Nejkratší články',
'longpages'             => 'Nejdelší články',
'deadendpages'  => 'Slepé články',
'listusers'             => 'Uživatelé',
'specialpages'  => 'Speciální stránky',
'spheading'             => 'Speciální stránky pro všechny uživatele',
'restrictedpheading'	=> 'Speciální stránky s omezeným přístupem',
'recentchangeslinked' => 'Související změny',
'rclsub'                => '(stránek odkazovaných z „$1“)',
'newpages'              => 'Nejnovější články',
'ancientpages'		=> 'Nejdéle needitované stránky',
'intl'		=> 'Mezijazykové odkazy',
'move' => 'Přesunout',
'movethispage'  => 'Přesunout stránku',
'unusedimagestext' => '<p>Jiné WWW stránky mohou odkazovat přímo pomocí URL, na takové odkazy se v tomto seznamu nebere zřetel.',
'unusedcategoriestext' => 'Následující kategorie mají své stránky, ačkoli je žádná stránka ani jiná kategorie nepoužívá.',
'booksources'   => 'Zdroje knih',
'categoriespagetext' => 'Ve wiki existují následující kategorie:',
'data'	=> 'Data',
'userrights' => 'Správa uživatelských skupin',
'groups' => 'Uživatelské skupiny',
'booksourcetext' => 'Následují odkazy na jiné WWW stránky, na kterých se prodávají knihy, nebo které mohou obsahovat další informace o knize, kterou hledáte. {{SITENAME}} nemá s těmito prodejnami žádný vztah, tyto odkazy nelze chápat jako doporučení.',
'isbn'  => 'ISBN',
'alphaindexline' => 'od $1 do $2',
'version'               => 'Verze',
'log'		=> 'Protokolovací záznamy',
'alllogstext'	=> 'Společné zobrazení knihy nahrávek, smazání, zamčení, zablokování a uživatelských práv.
Zobrazení můžete zůžit výběrem typu záznamu, uživatelského jména nebo dotčené stránky.',
'logempty' => 'Protokol neobsahuje žádný odpovídající záznam.',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Uživatel:',
'speciallogtitlelabel' => 'Název:',

# Special:Allpages
'nextpage'          => 'Další stránka ($1)',
'allpagesfrom'		=> 'Všechny stránky počínaje od:',
'allarticles'		=> 'Všechny články',
'allinnamespace'	=> 'Všechny stránky (jmenný prostor $1)',
'allnotinnamespace'	=> 'Všechny stránky (mimo jmenný prostor $1)',
'allpagesprev'		=> 'Předchozí',
'allpagesnext'		=> 'Následující',
'allpagessubmit'	=> 'Přejít',
'allpagesprefix'	=> 'Zobrazit stránky začínající:',

# Email this user
#
'mailnologin'   => 'Bez odesílací adresy',
'mailnologintext' => 'Pokud chcete posílat e-maily jiným uživatelům, musíte se [[Special:Userlogin|přihlásit]] a mít platnou e-mailovou adresu ve svém [[Special:Preferences|nastavení]].',
'emailuser'             => 'Poslat e-mail',
'emailpage'             => 'Poslat e-mail',
'emailpagetext' => 'Pokud tento uživatel uvedl platnou e-mailovou adresu ve svém nastavení, tímto formulářem mu lze poslat zprávu. E-mailová adresa, kterou máte uvedenu v nastavení, se objeví jako adresa odesílatele pošty, aby adresát mohl odpovědět.',
'usermailererror' => 'Chyba poštovního programu:',
'defemailsubject'  => 'E-mail z {{grammar:2sg|{{SITENAME}}}}',
'noemailtitle'  => 'Bez e-mailové adresy',
'noemailtext'   => 'Tento uživatel buď nezadal platnou adresu nebo zakázal přijímat zprávy od jiných uživatelů.',
'emailfrom'             => 'Od',
'emailto'               => 'Komu',
'emailsubject'  => 'Předmět',
'emailmessage'  => 'Zpráva',
'emailsend'             => 'Odeslat',
'emailsent'             => 'Zpráva odeslána',
'emailsenttext' => 'Váš e-mail byl odeslán.',

# Watchlist
#
'watchlist'             => 'Sledované stránky',
'nowatchlist'   => 'Na svém seznamu sledovaných stránek nemáte žádné položky.',
'watchlistcount' 	=> "'''Na svém seznamu sledovaných stránek máte $1 {{plural:$1|položku|položky|položek}} včetně diskusí.'''",
'clearwatchlist' 	=> 'Smazat seznam sledovaných stránek',
'watchlistcleartext' => 'Jste si jisti, že chcete odstranit tyto položky?',
'watchlistclearbutton' => 'Smazat',
'watchlistcleardone' => 'Váš seznam sledovaných stránek byl smazán, {{plural:$1|byla odstraněna jedna položka|byly odstraněny $1 položky|bylo odstraněno $1 položek}}.',
'watchnologin'  => 'Nejste přihlášen(a)',
'watchnologintext'      => 'Pro sledování oblíbených stránek se musíte [[Special:Userlogin|přihlásit]].',
'addedwatch'    => 'Přidáno k oblíbeným',
'addedwatchtext' => 'Stránka „[[:$1]]“ byla přidána mezi stránky, které [[Special:Watchlist|sledujete]]. Budoucí změny této stránky se objeví <b>tučně</b> v [[Special:Recentchanges|seznamu posledních změn]], aby bylo snadnější si jí povšimnout. Pokud budete později chtít stránku ze seznamu sledovaných smazat, klikněte na „Nesledovat tuto stránku“ v liště nástrojů.',
'removedwatch'  => 'Vyřazeno ze seznamu sledovaných stránek',
'removedwatchtext' => 'Stránka „$1“ byla vyřazena z vašeho seznamu sledovaných stránek.',
'watch' => 'Sledovat',
'watchthispage' => 'Sledovat tuto stránku',
'unwatch' => 'Nesledovat',
'unwatchthispage' => 'Nesledovat tuto stránku',
'notanarticle'  => 'Toto není článek',
'watchnochange'         => 'Žádná ze sledovaných položek nebyla editována v době, která je zobrazena.',
'watchdetails'          => '* Na svém seznamu sledovaných stránek máte $1 {{plural:$1|stránku|stránky|stránek}}
* [[Special:Watchlist/edit|Ukázat a editovat kompletní seznam]].
* [[Special:Watchlist/clear|Odstranit všechny položky]]',
'wlheader-enotif' 		=> '* Upozorňování e-mailem je zapnuto.',
'wlheader-showupdated'   => "* Stránky, které se změnily od vaší poslední návštěvy, jsou zobrazeny '''tučně'''",
'watchmethod-recent'=> 'hledají se sledované stránky mezi posledními změnami',
'watchmethod-list'      => 'hledají se nejnovější editace sledovaných stránek',
'removechecked'         => 'Vyřadit označené položky ze seznamu sledovaných',
'watchlistcontains' => 'Na svém seznamu sledovaných stránek máte $1 {{plural:$1|položku|položky|položek}}.',
'watcheditlist'		=> 'Tady je abecední seznam vašich sledovaných stránek. Zaškrtněte stránky, které chcete smazat z vašeho seznamu a klikněte na tlačítko „vyřadit označené“ na konci obrazovky. S každou stránkou je vždy sledována i její diskusní stránka a naopak.',
'removingchecked' 	=> 'Požadované položky se odstraňují ze seznamu sledovaných…',
'couldntremove' 	=> 'Nepodařilo se odstranit položku „$1“…',
'iteminvalidname' 	=> 'Problém s položkou „$1“, neplatný název…',
'wlnote'                        => 'Níže je {{plural:$1|poslední změna|poslední $1 změny|posledních $1 změn}} za {{plural:$2|poslední|poslední|posledních}} <b>$2</b> {{plural:$2|hodinu|hodiny|hodin}}.',
'wlshowlast'            => 'Ukázat posledních $1 hodin $2 dnů $3',
'wlsaved'                       => 'Toto je uložená verze vašeho seznamu sledovaných stránek.',
'wlhideshowown'   	=> '$1 moje editace.',
'wlhideshowbots'   	=> '$1 roboty.',
'wldone'            => 'Hotovo.',

'enotif_mailer' 		=> 'Zasílač hlášení {{grammar:2sg|{{SITENAME}}}}',
'enotif_reset'			=> 'Vynulovat všechny příznaky (nastavit stav na „navštíveno“)',
'enotif_newpagetext'=> 'Toto je nová stránka.',
'changed' 	=> 'změněno',
'created' 	=> 'vytvořeno',
'enotif_subject' 	=> '$PAGEEDITOR upravil stránku $PAGETITLE na {{grammar:6sg|{{SITENAME}}}}.',
'enotif_lastvisited' => 'Vizte $1 pro seznam všech změn od minulé návštěvy.',
'enotif_body' => 'Vážený uživateli $WATCHINGUSERNAME,

$PAGEEDITDATE upravil $PAGEEDITOR stránku $PAGETITLE, vizte $PAGETITLE_URL pro aktuální verzi.

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
{{fullurl:Project:Potřebuji pomoc}}',

# Delete/protect/revert
#
'deletepage'    => 'Smazat stránku',
'confirm'               => 'Potvrdit',
'excontent' => 'obsah byl: „$1“',
'excontentauthor' => "obsah byl: „$1“ (a jediným přispěvatelem byl „$2“)",
'exbeforeblank' => 'obsah před vyprázdněním byl: „$1“',
'exblank' => 'stránka byla prázdná',
'confirmdelete' => 'Potvrdit smazání',
'deletesub'		=> '(Maže se „$1“)',
'historywarning' => ' Varování: Stránka, kterou chcete smazat, má historii:&nbsp;',
'confirmdeletetext' => 'Chystáte se trvale smazat z databáze stránku nebo obrázek s celou jeho historií. Prosím potvrďte, že to opravdu chcete učinit, že si uvědomujete důsledky a že je to v souladu s [[Project:Pravidla|pravidly]].',
'actioncomplete' => 'Provedeno',
'deletedtext'	=> ' Stránka nebo soubor „$1“ byla smazána; $2 zaznamenává poslední smazání.',
'deletedarticle' => 'maže „$1“',
'dellogpage'    => 'Kniha_smazaných_stránek',
'dellogpagetext' => 'Zde je seznam posledních smazaných z databáze. Všechny časové údaje uvedeny podle časového pásma serveru (UTC).
<ul>
</ul>',
'deletionlog'   => 'kniha smazaných stránek',
'reverted'              => 'Obnovení předchozí verze',
'deletecomment' => 'Důvod smazání',
'imagereverted' => 'Obnovení předchozí verze úspěšně provedeno.',
'rollback'              => 'Vrátit zpět editace',
'rollback_short' => 'Vrátit zpět',
'rollbacklink'  => 'vrácení zpět',
'rollbackfailed' => 'Nešlo vrátit zpět',
'cantrollback'  => 'Nelze vrátit zpět poslední editaci, neboť poslední přispěvatel je jediným autorem tohoto článku.',
'alreadyrolled' => 'Nelze vrátit zpět poslední editaci [[$1]] od [[User:$2|$2]] ([[User talk:$2|Diskuse]]), protože někdo jiný již článek editoval nebo vrátil tuto změnu zpět. Poslední editace byla od [[User:$3|$3]] ([[User talk:$3|Diskuse]]).',
#   only shown if there is an edit comment
'editcomment' => 'Shrnutí editace bylo: „<i>$1</i>“.',
'revertpage'	=> 'Editace uživatele „$2“ vrácena do předchozího stavu, jehož autorem je „$1“.',
'sessionfailure' => 'Zřejmě je nějaký problém s vaším přihlášením;
vámi požadovaná činnost byla stornována jako prevence před neoprávněným přístupem.
Stiskněte tlačítko „zpět“, obnovte stránku, ze které jste přišli a zkuste činnost znovu.',
'protectlogpage' => 'Kniha_zamčení',
'protectlogtext' => 'Zde je seznam zamčení/odemčení stránek. Viz [[{{ns:4}}:Zamčená stránka]] pro další informace.',
'protectedarticle' => 'zamyká „[[$1]]“',
'unprotectedarticle' => 'odemyká „[[$1]]“',
'protectsub' =>'(Zamyká se „$1“)',
'confirmprotecttext' => 'Opravdu chcete zamknout tuto stránku?',
'confirmprotect' => 'Potvrdit zamčení',
'protectmoveonly' => 'Bránit pouze proti přesunutí',
'protectcomment' => 'Důvod zamčení',
'unprotectsub' => '(Odemyká se „$1“)',
'confirmunprotecttext' => 'Opravdu chcete odemknout tuto stránku?',
'confirmunprotect' => 'Potvrdit odemčení',
'unprotectcomment' => 'Důvod odemčení',
'protect-unchain' => 'Oddělené ovládání zámku přesunů',
'protect-text' => 'Zde si můžete prohlédnout či změnit nastavení zámků stránky <strong>$1</strong>. Ujistěte se, že dodržujete [[Project:Zamčené stránky|pravidla projektu]].',
'protect-viewtext' => 'Nemáte dostatečná oprávnění ke změně zámků stran. Zde si můžete prohlédnout aktuální nastavení stránky [[$1]]:',
'protect-default' => '(odemčeno)',
'protect-level-autoconfirmed' => 'Pouze registrovaní',
'protect-level-sysop' => 'Pouze správci',

# restrictions (nouns)
'restriction-edit' => 'Editace',
'restriction-move' => 'Přesun',

# Undelete
'undelete' => 'Smazané stránky',
'undeletepage' => 'Prohlédnout si a obnovit smazanou stránku',
'viewdeletedpage' => 'Zobrazení smazané stránky',
'undeletepagetext' => 'Tyto stránky jsou smazány, avšak dosud archivovány, a proto je možno je obnovit. Archiv může být pravidelně vyprazdňován.',
'undeleteextrahelp' => "Pro úplné obnovení stránky s kompletní historií ponechte všechny čtverečky nezaškrtnuté a klikněte na '''''Obnovit'''''. Pro částečné obnovení zašrktněte čtverečky u obnovovaných revizí a klikněte na '''''Obnovit'''''. Kliknutí na '''''Reset''''' vyprázdní komentář a zruší všechna zaškrtnutí.",
'undeletearticle' => 'Obnovit smazaný článek',
'undeleterevisions' => '$1 {{plural:$1|verze|verze|verzí}} je archivováno',
'undeletehistory' => 'Pokud stránku obnovíte, všechny revize budou v historii obnoveny. Pokud byla vytvořena nová stránka se stejným jménem jako smazaná, obnovené revize se zapíší na starší místo v historii a nová stránka nebude nahrazena.',
'undeletehistorynoadmin' => 'Tato stránka byla smazána. Důvod smazání je uveden níže, spolu s informacemi o uživatelích, kteří tuto stránku před smazáním editovali. Samotný text stránky je dostupný pouze správcům.',
'undeleterevision' => 'Smazaná verze z $1',
'undeletebtn' => 'Obnovit',
'undeletereset' => 'Reset',
'undeletecomment' => 'Komentář:',
'undeletedarticle' => 'obnovuje „[[$1]]“',
'undeletedrevisions' => '{{plural:$1|Obnovena jedna verze|Obnoveny $1 verze|Obnoveno $1 verzí}}',
'undeletedpage'   => '<big>Stránka $1 byla obnovena</big>

Záznam o posledních mazáních a obnoveních najdete v [[Special:Log/delete|knize smazaných stránek]].',

# Namespace form on various pages
'namespace' => 'Jmenný prostor:',
'invert' => 'Obrátit výběr',

# Contributions
#
'contributions' => 'Příspěvky uživatele',
'mycontris'             => 'Mé příspěvky',
'contribsub'    => '$1',
'nocontribs'    => 'Nenalezeny žádné změny vyhovující kritériím.',
'ucnote'                => 'Níže jsou uživatelovy poslední <strong>$1</strong> změny během {{plural:$1|posledního|posledních|posledních}} <strong>$2</strong> {{plural:$1|dne|dnů|dnů}}.',
'uclinks'               => 'Ukaž posledních $1 změn; ukaž posledních $2 dnů.',
'uctop'                 => ' (aktuální)',
'newbies'       => 'nováčci',

'sp-newimages-showfrom' => 'Zobrazit nové obrázky počínaje od $1',

'sp-contributions-newest' => 'Nejnovější',
'sp-contributions-oldest' => 'Nejstarší',
'sp-contributions-newer'  => '$1 novějších',
'sp-contributions-older'  => '$1 starších',
'sp-contributions-newbies-sub' => 'Noví uživatelé',

# What links here
#
'whatlinkshere' => 'Odkazuje sem',
'notargettitle' => 'Bez cílové stránky',
'notargettext'  => 'Této funkci musíte určit cílovou stránku nebo uživatele.',
'linklistsub'   => '(Seznam odkazů)',
'linkshere'     => 'Odkazují sem tyto stránky:',
'nolinkshere'   => 'Žádná stránka sem neodkazuje.',
'isredirect'    => 'přesměrování',
'istemplate'	=> 'vložení',

# Block/unblock IP
#
'blockip'               => 'Zablokovat uživatele',
'blockiptext'   => 'Tento formulář slouží k zablokování editací z konkrétní IP adresy nebo uživatelského jména. Toto by mělo být používáno jen v souladu s [[{{ns:4}}:blokování|pravidly blokování]]. Udejte přesný důvod níže (například ocitujte, které stránky byly poškozeny). Pro odblokování se podívejte na [[Special:Ipblocklist|seznam blokovaných IP adres]].',
'ipaddress'			  => 'IP adresa',
'ipadressorusername' => 'IP adresa nebo uživatelské jméno',
'ipbexpiry'             => 'Čas vypršení',
'ipbreason'             => 'Důvod',
'ipbsubmit'             => 'Zablokovat',
'ipbother'		=> 'Jiný čas vypršení',
'ipboptions'		=> '2 hodiny:2 hours,1 den:1 day,3 dny:3 days,1 týden:1 week,2 týdny:2 weeks,1 měsíc:1 month,3 měsíce:3 months,6 měsíců:6 months,1 rok:1 year,do odvolání:infinite',
'ipbotheroption'	=> 'jiný',
'badipaddress'  => 'Neplatná IP adresa',
'blockipsuccesssub' => 'Zablokování uspělo',
'blockipsuccesstext' => 'Uživatel „[[Special:Contributions/$1|$1]]“ je zablokován. <br />Podívejte se na [[{{ns-1}}:Ipblocklist|seznam zablokovaných]], [[{{ns-1}}:Log/block|kniha zablokování]] zaznamenává všechny podobné úkony.',
'unblockip'             => 'Odblokovat IP adresu',
'unblockiptext' => 'Tímto formulářem je možno obnovit právo blokované IP adresy či uživatele opět přispívat do {{grammar:2sg|{{SITENAME}}}}.',
'ipusubmit'             => 'Odblokovat',
'ipblocklist'   => 'Seznam blokovaných IP adres',
'blocklistline' => '$1 $2 zablokoval $3 ($4)',
'infiniteblock' => 'do odvolání',
'expiringblock' => 'čas vypršení: $1',
'ipblocklistempty'	=> 'Seznam blokování je momentálně prázdný.',
'blocklink'             => 'zablokovat',
'unblocklink'   => 'uvolnit',
'contribslink'  => 'příspěvky',
'autoblocker'	=> 'Automaticky zablokováno, protože sdílíte IP adresu s „$1“. Důvod: „$2“.',
'blocklogpage'	=> 'Kniha_zablokování',
'blocklogentry'	=> 'zablokovává „[[$1]]“ s časem vypršení $2',
'blocklogtext'	=> 'Toto je kniha úkonů blokování a odblokování uživatelů. Automaticky blokované IP adresy nejsou vypsány. Podívejte se na [[Special:Ipblocklist|seznam blokování IP]] s výčtem aktuálních zákazů a blokování.',
'unblocklogentry'	=> 'odblokovává „$1“',
'range_block_disabled'  => 'Blokování rozsahů IP adres je zakázáno.',
'ipb_expiry_invalid'    => 'Neplatný čas vypršení.',
'ip_range_invalid'      => 'Neplatný IP rozsah.',
'proxyblocker'  => 'Blokování proxy serverů',
'proxyblockreason'      => 'Vaše IP adresa byla zablokována, protože funguje jako otevřený proxy server. Kontaktujte prosím vašeho poskytovatele Internetového připojení nebo technickou podporu a informujte je o tomto vážném bezpečnostním problému.',
'proxyblocksuccess'     => 'Hotovo.',
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'Vaše IP adresa je uvedena na seznamu [http://www.sorbs.net SORBS] DNSBL jako [[w:cs:otevřená proxy|otevřená proxy]].',
'sorbs_create_account_reason' => 'Vaše IP adresa je uvedena na seznamu [http://www.sorbs.net SORBS] DNSBL jako [[w:cs:otevřená proxy|otevřená proxy]]. Z této adresy si nemůžete založit účet',

# Developer tools
#
'lockdb'                => 'Zamknout databázi',
'unlockdb'              => 'Odemknout databázi',
'lockdbtext'    => 'Pokud zamknete databázi, znemožníte ostatním editovat, upravovat nastavení, sledované stránky apod. Potvrďte, že to opravdu chcete udělat a že odemknete databázi hned po opravách.',
'unlockdbtext'  => ' Pokud odemknete databázi, umožníte ostatním editovat, upravovat nastavení, sledované stránky apod. Potvrďte, že to opravdu chcete udělat.',
'lockconfirm'   => 'Ano, opravdu chci zamknout databázi.',
'unlockconfirm' => 'Ano, opravdu chci odemknout databázi.',
'lockbtn'               => 'Zamknout databázi',
'unlockbtn'             => 'Odemknout databázi',
'locknoconfirm' => 'Nebylo zaškrtnuto políčko potvrzení.',
'lockdbsuccesssub' => 'Databáze uzamčena',
'unlockdbsuccesssub' => 'Databáze odemčena',
'lockdbsuccesstext' => 'Databáze {{grammar:2sg|{{SITENAME}}}} byla úspěšně uzamčena.
<br />Nezapomeňte ji po opravách odemknout.',
'unlockdbsuccesstext' => 'Databáze {{grammar:2sg|{{SITENAME}}}} je odemčena.',

# Make sysop
'makesysoptitle'        => 'Učinit uživatele správcem',
'makesysoptext'         => 'Tento formulář je používán byrokraty pro změnu obyčejného uživatele na správce. Vepište jméno uživatele do políčka a stiskněte tlačítko.',
'makesysopname'         => 'Jméno uživatele:',
'makesysopsubmit'       => 'Učinit tohoto uživatele správcem',
'makesysopok'		=> '<b>Uživatel „$1“ nyní patří mezi správce</b>',
'makesysopfail'		=> '<b>Uživatel „$1“ nemůže být učiněn správcem. (Vložili jste jeho jméno správně?)</b>',
'setbureaucratflag' => 'Nastavit příznak byrokrata',
'rightslogtext'		=> 'Toto je záznam změn uživatelských oprávnění.',
'rightslog'		=> 'Kniha práv uživatelů',
'rightslogtext'		=> 'Toto je záznam změn uživatelských práv.',
'rightslogentry'	=> 'změnil pro $1 členství ve skupinách z $2 na $3',
'rights'			=> 'Oprávnění:',
'set_user_rights'	=> 'Nastavit uživatelova oprávnění',
'user_rights_set'	=> '<b>Uživatelova práva k „$1“ aktualizována</b>',
'set_rights_fail'	=> '<b>Uživatelova práva k „$1“ nemohla být nastavena. (Vložili jste jeho jméno správně?)</b>',
'makesysop'         => 'Učinit uživatele správcem',
'already_sysop'     => 'Tento uživatel už je správce.',
'already_bureaucrat' => 'Tento uživatel už je byrokrat.',
'rightsnone' => '(žádné)',

# Move page
#
'movepage'              => 'Přesunout stránku',
'movepagetext'  => 'Pomocí tohoto formuláře změníte název stránky a přesunete i celou její historii pod nový název. Původní název se stane přesměrováním na nový název. Odkazy na předchozí název <i>nebudou</i> změněny. <b>VAROVÁNÍ!</b> Toto může drastická a nečekaná změna pro oblíbené stránky. Ujistěte se, prosím, že chápete důsledky vašeho kroku před tím, než změnu provedete.',
'movepagetalktext' => "Přidružená diskusní stránka, pokud existuje, bude automaticky přesunuta společně se stránkou, '''pokud:'''
* Nepřesouváte stránku napříč jmennými prostory,
* Již neexistuje neprázdná diskusní stránka pod novým jménem, nebo
* Nezrušíte křížek ve formuláři.

V těchto případech musíte přesunout nebo sloučit stránky manuálně, jestliže si to přejete.",
'movearticle'   => 'Přesunout stránku',
'movenologin' => 'Nejste přihlášen(a)!',
'movenologintext'       => 'Pro přesouvání stránek se musíte [[Special:Userlogin|přihlásit]].',
'newtitle'              => 'Na nový název',
'movepagebtn'   => 'Přesunout stránku',
'pagemovedsub'  => 'Úspěšně přesunuto',
'pagemovedtext' => "Stránka „[[$1]]“ přesunuta na „[[$2]]“.

'''Nyní''' následujte odkaz [[{{ns-1}}:Whatlinkshere/$1]]: pokud se v seznamu vyskytnou nějaké přesměrovače, je třeba je upravit tak, aby ukazovaly na nový název ($2), jinak nebudou fungovat.",
'articleexists' => 'Takto nazvaná stránka již existuje, nebo Vámi zvolený název je neplatný. Zvolte jiný název.',
'talkexists'	=> 'Stránka byla přesunuta úspěšně, ale diskusní stránka přesunuta být nemohla, neboť pod novým názvem již nějaká stránka existuje. Proveďte prosím ruční sloučení.',
'movedto'               => 'přesunuto na',
'movetalk'              => 'Přesunout také diskusní stránku, existuje-li.',
'talkpagemoved' => 'Diskusní stránka byla také přesunuta.',
'talkpagenotmoved' => 'Diskusní stránka <strong>nebyla</strong> přesunuta.',
'1movedto2'             => 'Stránka [[$1]] přemístěna na stránku [[$2]]',
'1movedto2_redir' => 'Stránka [[$1]] přemístěna na stránku [[$2]] s výměnou přesměrování',
'movelogpage' => 'Kniha přesunů',
'movelogpagetext' => 'Toto je záznam všech přesunů stránek.',
'movereason'	=> 'Důvod',
'revertmove'	=> 'vrátit',
'delete_and_move' => 'Smazat a přesunout',
'delete_and_move_text'	=>
'==Je potřeba smazání==

Cílová stránka „[[$1]]“ již existuje. Přejete si ji smazat pro uvolnění místa pro přesun?',
'delete_and_move_confirm' => 'Ano, smazat cílovou stránku',
'delete_and_move_reason' => 'Smazáno pro umožnění přesunu',
'selfmove' => 'Původní a nový název jsou stejné; nelze stránku přesunout na sebe samu.',
'immobile_namespace' => 'Nový název je speciálního druhu; do tohoto jmenného prostoru nelze stránky přesouvat.',

# Export

'export'                => 'Exportovat stránky',
'exporttext'    => 'Můžete exportovat text a historii editací některé stránky nebo sady stránek zabalené v XML; to může být importováno do jiné wiki, která běží na software MediaWiki, transformováno nebo jen uschováno pro vaši soukromou potřebu.',
'exportcuronly' => 'Zahrnout jen současnou verzi, ne plnou historii',
'exportnohistory' => "----
'''Poznámka:''' export plných historií protřednictvím tohoto formuláře byl z výkonnostních důvodů zakázán.",

# Namespace 8 related

'allmessages'   => 'Všechna systémová hlášení',
'allmessagesname' => 'Označení hlášení',
'allmessagesdefault' => 'Původní text',
'allmessagescurrent' => 'Aktuální text',
'allmessagestext'       => 'Toto je seznam všech hlášení dostupných ve jmenném prostoru MediaWiki.',
'allmessagesnotsupportedUI' => 'Váš aktuální jazyk rozhraní <b>$1</b> není na tomto serveru pro {{ns:-1}}:AllMessages podporován.',
'allmessagesnotsupportedDB' => '{{ns:-1}}:AllMessages není podporováno, neboť wgUseDatabaseMessages je vypnuto.',
'allmessagesfilter' => 'Filtr názvů hlášení:',
'allmessagesmodified' => 'Zobrazit pouze upravená hlášení',

# Thumbnails

'thumbnail-more'        => 'Zvětšit',
'missingimage'          => "<b>Chybějící obrázek</b><br /><i>$1</i>",
'filemissing'		=> 'Chybějící soubor',
'thumbnail_error'   => 'Chyba při vytváření náhledu: $1',

# Special:Import
'import'        => 'Importovat stránky',
'importinterwiki' => 'Import mezi wiki',
'importtext'    => 'Prosím exportujte soubor ze zdrojové wiki pomocí nástroje {{ns:-1}}:Export, uložte ji na svůj disk a nahrajte ji sem.',
'importfailed'  => 'Import selhal: $1',
'importnotext'  => 'Prázdný nebo žádný text',
'importsuccess' => 'Import byl úspěšný!',
'importhistoryconflict' => 'Existuje konflikt mezi historiemi verzí. Možná, že tato stránka byla již importována dříve.',
'importnosources' => 'Nebyly definovány žádné zdroje pro import mezi wiki a přímé načítání historie je vypnuto.',
'importnofile' => 'Nebyl načten importní soubor.',
'importuploaderror' => 'Nepodařilo se načíst importní soubor; možná jeho velikost přesahuje dovolenou mez.',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-watch' => 'w',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'd',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'Hledat na této wiki [alt-f]',
'tooltip-minoredit' => 'Označit jako malou editaci [alt-i]',
'tooltip-save' => 'Uložit vaše úpravy [alt-s]',
'tooltip-preview' => 'Prohlédnout vaše úpravy, prosíme použijte tuto funkci před uložením! [alt-p]',
'tooltip-diff' => 'Zobrazit, jaké změny jste v textu provedli. [alt-d]',
'tooltip-compareselectedversions' => 'Porovnat rozdíly mezi zvolenými verzemi této stránky. [alt-v]',
'tooltip-watch' => 'Přidat stránku do seznamu sledovaných [alt-w]',

# stylesheets

'Monobook.css' => '/* editací tohoto souboru upravíte styl "monobook" pro celou {{grammar:4sg|{{SITENAME}}}} */',

# Metadata
'nodublincore' => 'Na tomto serveru je vypnuto generování metadat Dublin Core RDF.',
'nocreativecommons' => 'Na tomto server je vypnuto generování metadat Creative Commons RDF.',
'notacceptable' => 'Tento wiki server není schopen poskytnout data ve formátu, který by váš klient byl schopen přečíst.',

# Attribution

'anonymous' => "Anonymní uživatel(é) {{SITENAME}}",
'siteuser' => "Uživatel {{SITENAME}} $1",
'lastmodifiedatby' => 'Tuto stránku naposledy měnil $3 v $2, $1.',
'and' => 'a',
'othercontribs' => 'Založeno na textu od uživatele $1.',
'others' => 'další',
'siteusers' => "Uživatel(é) {{SITENAME}} $1",
'creditspage' => 'Zásluhy za stránku',
'nocredits' => 'K této stránce neexistuje informace o zásluhách.',

# Spam protection

'spamprotectiontitle' => 'Spam protection filter',
'spamprotectiontext' => 'Stránka, kterou jste se pokusil(a) uložit, byla zablokována protispamovým filtrem. Pravděpodobnou příčinou je odkaz na externí stránky. Může vás zajímat následující regulární výraz, který označuje v současné době blokované stránky:',
'spamprotectionmatch' => 'Následující text spustil náš filtr proti spamu: $1',
'spambot_username' => 'Systémový čistič spamu',
'spam_reverting' => 'Revert na poslední verzi neobsahující odkazy na $1',
'spam_blanking' => 'Všechny revize obsahovaly odkazy na $1, vyprázdněno',

# Categories

'subcategorycount' => 'Tato kategorie obsahuje $1 {{plural:$1|podkategorii|podkategorie|podkategorií}}.',
'categoryarticlecount' => 'Tato kategorie obsahuje $1 {{plural:$1|článek|články|článků}}.',
'listingcontinuesabbrev' => 'pokrač.',

# Info page
'infosubtitle' => 'Informace o stránce',
'numedits' => 'Počet editací (článek): $1',
'numtalkedits' => 'Počet editací (diskusní stránka): $1',
'numwatchers' => 'Počet sledujících uživatelů: $1',
'numauthors' => 'Počet různých autorů (článek): $1',
'numtalkauthors' => 'Počet různých autorů (diskusní stránka): $1',

# Math options
'mw_math_png' => 'Vždy jako PNG',
'mw_math_simple' => 'Jednoduché jako HTML, jinak PNG',
'mw_math_html' => 'HTML pokud je to možné, jinak PNG',
'mw_math_source' => 'Ponechat jako TeX (pro textové prohlížeče)',
'mw_math_modern' => 'Doporučené nastavení pro moderní prohlížeče',
'mw_math_mathml' => 'MathML pokud je podporováno (experimentální)',

# Patrolling
'markaspatrolleddiff'   => 'Označit jako prověřené',
'markaspatrolledtext'   => 'Označit tento článek jako prověřený',
'markedaspatrolled'     => 'Označeno jako prověřené',
'markedaspatrolledtext' => 'Vybraná verze byla označena jako prověřená.',
'rcpatroldisabled'      => 'Hlídka posledních změn vypnuta',
'rcpatroldisabledtext'  => 'Hlídka posledních změn je momentálně vypnuta.',
'markedaspatrollederror'  => 'Nelze označit za prověřené',
'markedaspatrollederrortext' => 'Musíte zvolit revizi, která má být označena jako prověřená.',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => "/* tooltips and access keys */
var ta = new Object();
ta['pt-userpage'] = new Array('.','Moje uživatelská stránka');
ta['pt-anonuserpage'] = new Array('.','Uživatelská stránka pro IP adresu, ze které editujete');
ta['pt-mytalk'] = new Array('n','Moje diskusní stránka');
ta['pt-anontalk'] = new Array('n','Diskuse o editacích provedených z této IP adresy');
ta['pt-preferences'] = new Array('','Moje preference');
ta['pt-watchlist'] = new Array('l','Seznam stránek, jejichž změny sleduji');
ta['pt-mycontris'] = new Array('y','Seznam mých příspěvků');
ta['pt-login'] = new Array('o','Doporučujeme vám přihlásit se, ovšem není to povinné.');
ta['pt-anonlogin'] = new Array('o','Doporučujeme vám přihlásit se, ovšem není to povinné.');
ta['pt-logout'] = new Array('o','Odhlásit se');
ta['ca-talk'] = new Array('t','Diskuse ke stránce');
ta['ca-edit'] = new Array('e','Tuto stránku můžete editovat. Prosíme použijte tlačítko Ukázat náhled před uložením.');
ta['ca-addsection'] = new Array('+','Přidat k této diskusi svůj komentář.');
ta['ca-viewsource'] = new Array('e','Tato stránka je zamčena. Můžete si prohlédnout její zdrojový kód.');
ta['ca-history'] = new Array('h','Starší verze této stránky.');
ta['ca-protect'] = new Array('=','Zamknout tuto stránku.');
ta['ca-delete'] = new Array('d','Smazat tuto stránku.');
ta['ca-undelete'] = new Array('d','Obnovit editace této stránky provedené před jejím smazáním.');
ta['ca-move'] = new Array('m','Přesunout tuto stránku');
ta['ca-watch'] = new Array('w','Přidat tuto stránku mezi sledované');
ta['ca-unwatch'] = new Array('w','Vyjmout tuto stránku ze sledovaných');
ta['search'] = new Array('f','Hledat na této wiki');
ta['p-logo'] = new Array('','Hlavní strana');
ta['n-mainpage'] = new Array('z','Navštívit Hlavní stranu');
ta['n-portal'] = new Array('','O projektu, jak můžete pomoci, kde hledat');
ta['n-currentevents'] = new Array('','Informace o aktuálních událostech');
ta['n-recentchanges'] = new Array('r','Seznam posledních změn na této wiki');
ta['n-randompage'] = new Array('x','Přejít na náhodně vybranou stránku');
ta['n-help'] = new Array('','Místo, kde najdete pomoc');
ta['n-sitesupport'] = new Array('','Podpořte nás');
ta['t-whatlinkshere'] = new Array('j','Seznam všech wikistránek, které sem odkazují');
ta['t-recentchangeslinked'] = new Array('k','Nedávné změny stránek, které sem odkazují');
ta['feed-rss'] = new Array('','RSS kanál pro tuto stránku');
ta['feed-atom'] = new Array('','Atom kanál pro tuto stránku');
ta['t-contributions'] = new Array('','Prohlédnout si seznam příspěvků tohoto uživatele');
ta['t-emailuser'] = new Array('','Poslat e-mail tomuto uživateli');
ta['t-upload'] = new Array('u','Nahrát obrázky či jiná multimédia');
ta['t-specialpages'] = new Array('q','Seznam všech speciálních stránek');
ta['ca-nstab-main'] = new Array('c','Zobrazit článek');
ta['ca-nstab-user'] = new Array('c','Zobrazit uživatelovu stránku');
ta['ca-nstab-media'] = new Array('c','Zobrazit stránku souboru');
ta['ca-nstab-special'] = new Array('','Toto je speciální stránka, kterou nelze editovat.');
ta['ca-nstab-project'] = new Array('a','Zobrazit stránku o wiki.');
ta['ca-nstab-image'] = new Array('c','Zobrazit stránku obrázku.');
ta['ca-nstab-mediawiki'] = new Array('c','Zobrazit systémovou zprávu.');
ta['ca-nstab-template'] = new Array('c','Zobrazit šablonu.');
ta['ca-nstab-help'] = new Array('c','Zobrazit stránku nápovědy.');
ta['ca-nstab-category'] = new Array('c','Zobrazit kategorii.');",

# preferences
'tog-underline'                 => 'Podtrhnout odkazy',
'tog-highlightbroken'           => 'Formátovat odkazy na neexistující články <a href="#" class="new">takto</a> (alternativa: takto<a href="#" class="broken">?</a>).',
'tog-justify'                   => 'Zarovnat odstavce do bloku',
'tog-hideminor'                 => 'Skrýt malé editace v posledních změnách',
'tog-extendwatchlist'           => 'Rozšířený seznam sledovaných stránek',
'tog-usenewrc'                  => 'Zdokonalené poslední změny (JavaScript)',
'tog-numberheadings'            => 'Automaticky číslovat nadpisy',
'tog-showtoolbar'               => 'Ukázat lištu s nástroji při editaci',
'tog-editondblclick'            => 'Editovat dvojklikem (JavaScript)',
'tog-editsection'               => 'Zapnout možnost editace části článku pomocí odkazu [editovat]',
'tog-editsectiononrightclick'   => 'Zapnout možnost editace části článku pomocí kliknutí pravým tlačítkem na nadpisy v článku (JavaScript)',
'tog-showtoc'                   => 'Ukázat obsah článku (pokud má článek více než tři nadpisy)',
'tog-rememberpassword'          => 'Pamatovat si mé heslo mezi návštěvami',
'tog-editwidth'                 => 'Roztáhnout editační okno na celou šířku',
'tog-watchcreations'            => 'Přidávat mnou založené stránky ke sledovaným',
'tog-watchdefault'              => 'Přidávat mnou editované stránky ke sledovaným',
'tog-minordefault'              => 'Označit editaci implicitně jako malá editace',
'tog-previewontop'              => 'Zobrazovat náhled před editačním oknem (ne za ním)',
'tog-previewonfirst'			=> 'Zobrazit při první editaci náhled',
'tog-nocache'                   => 'Nepoužívat cache',
'tog-enotifwatchlistpages' 		=> 'Poslat e-mail při změnách stránky (poznámka: existující příznaky musí být v seznamu sledovaných odstraněny ručně)',
'tog-enotifusertalkpages' 		=> 'Poslat e-mail při změnách mé diskusní stránky (poznámka: existující příznaky musí být v seznamu sledovaných odstraněny ručně)',
'tog-enotifminoredits' 			=> 'Poslat e-mail i pro menší editace (které jinak nezpůsobují odeslání e-mailu)',
'tog-enotifrevealaddr' 			=> 'Prozradit mou e-mailovou adresu v upozorňujících e-mailech (při mých změnách to umožňuje sledujícím uživatelům rychle mi odpovědět)',
'tog-shownumberswatching' 		=> 'Zobrazovat počet sledujících uživatelů (v posledních změnách, sledovaných stránkách a patičkách stránek',
'tog-fancysig'	                => 'Neupravovat podpis (nevkládat automaticky odkaz)',
'tog-externaleditor'			=> 'Implicitně používat externí editor',
'tog-externaldiff'				=> 'Implicitně používat externí porovnávací program',
'tog-showjumplinks'				=> 'Používat odkazy „skočit na“ pro vyšší přístupnost',
'tog-uselivepreview' => 'Používat rychlý náhled (JavaScript) (Experimentální)',
'tog-autopatrol' => 'Označovat moje editace jako prověřené',
'tog-forceeditsummary' => 'Upozornit, když nevyplním shrnutí editace',
'tog-watchlisthideown' => 'Na seznamu sledovaných stránek skrýt moje editace',
'tog-watchlisthidebots' => 'Na seznamu sledovaných stránek skrýt editace botů',

'underline-always' => 'Vždy',
'underline-never' => 'Nikdy',
'underline-default' => 'Podle prohlížeče',

'skinpreview' => '(Náhled)',

# image deletion
'deletedrevision' => 'Smazána stará verze $1.',

# browsing diffs
'previousdiff' => '← Předchozí porovnání',
'nextdiff' => 'Následující porovnání →',

'imagemaxsize' => 'Omezit obrázky na stránkách s popiskem na:',
'thumbsize'	=> 'Velikost náhledu:',
'showbigimage' => 'Stáhnout verzi s vysokým rozlišením ($1&times;$2, $3 KB)',

'newimages' => 'Galerie nových obrázků',
'showhidebots' => '($1 roboty)',
'noimages'  => 'Není co zobrazit.',

'passwordtooshort' => 'Vaše heslo je příliš krátké. Musí obsahovat nejméně $1 {{plural:$1|znak|znaky|znaků}}.',

# Media Warning
'mediawarning' => '\'\'\'Upozornění\'\'\': Tento soubor může obsahovat škodlivý kód, spuštěním můžete ohrozit svůj počítač.<hr />',

'fileinfo' => '$1 KB, MIME typ: <code>$2</code>',

# Metadata
'metadata' => 'Metadata',
'metadata_help' => 'Metadata (vysvětlení najdete na [[{{ns:project}}:Metadata]]):',
'metadata-help' => 'Tento soubor obsahuje dodatečné informace, poskytnuté zřejmě digitálním fotoaparátem nebo scannerem, kterým byl pořízen. Pokud byl soubor od té doby změněn, některé údaje mohou být neplatné.',
'metadata-expand' => 'Zobrazit podrobnosti',
'metadata-collapse' => 'Skrýt podrobnosti',
'metadata-fields' => 'Pole EXIF uvedené v této zprávě budou na stránce s popisem vypsány vždy. Pro zobrazení ostatních bude třeba kliknout na „zobrazit podrobnosti“.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# Exif tags
# TODO: zkontrolovat překlad, profesionální fotograf/grafik by bodnul
'exif-imagewidth' =>'Šířka',
'exif-imagelength' =>'Výška',
'exif-bitspersample' =>'Bitů na složku',
'exif-compression' =>'Druh komprese',
'exif-photometricinterpretation' =>'Barevný prostor',
'exif-orientation' =>'Orientace',
'exif-samplesperpixel' =>'Počet složek',
'exif-planarconfiguration' =>'Uspořádání dat',
'exif-ycbcrsubsampling' =>'Poměr podvzorkování Y ku C',
'exif-ycbcrpositioning' =>'Umístění Y a C',
'exif-xresolution' =>'Rozlišení obrázku na šířku',
'exif-yresolution' =>'Rozlišení obrázku na výšku',
'exif-resolutionunit' =>'Jednotky rozlišení',
'exif-stripoffsets' =>'Umístění dat obrázku',
'exif-rowsperstrip' =>'Počet řádků na pás',
'exif-stripbytecounts' =>'Počet bajtů na komprimovaný pás',
'exif-jpeginterchangeformat' =>'Ofset k JPEG SOI',
'exif-jpeginterchangeformatlength' =>'Počet bajtů JPEG dat',
'exif-transferfunction' =>'Přenosová funkce',
'exif-whitepoint' =>'Chroma bílého bodu',
'exif-primarychromaticities' =>'Chroma primárních barev',
'exif-ycbcrcoefficients' =>'Koeficienty matice pro transformaci barevných prostorů',
'exif-referenceblackwhite' =>'Světlost referenčního černého a bílého bodu',
'exif-datetime' =>'Datum a čas vytvoření obrázku',
'exif-imagedescription' =>'Název obrázku',
'exif-make' =>'Značka fotoaparátu',
'exif-model' =>'Model fotoaparátu',
'exif-software' =>'Použitý software',
'exif-artist' =>'Autor',
'exif-copyright' =>'Držitel autorských práv',
'exif-exifversion' =>'Verze Exif',
'exif-flashpixversion' =>'Podporovaná verze Flashpix',
'exif-colorspace' =>'Barevný prostor',
'exif-componentsconfiguration' =>'Význam jednotlivých složek',
'exif-compressedbitsperpixel' =>'Komprimační režim',
'exif-pixelydimension' =>'Platná šířka obrazu',
'exif-pixelxdimension' =>'Platná výška obrazu',
'exif-makernote' =>'Poznámky výrobce',
'exif-usercomment' =>'Uživatelské poznámky',
'exif-relatedsoundfile' =>'Související zvukový soubor',
'exif-datetimeoriginal' =>'Datum a čas pořízení obrázku',
'exif-datetimedigitized' =>'Datum a čas digitalizace',
'exif-subsectime' =>'zlomky sekundy pro DateTime',
'exif-subsectimeoriginal' =>'zlomky sekundy pro DateTimeOriginal',
'exif-subsectimedigitized' =>'zlomky sekundy pro DateTimeDigitized',
'exif-exposuretime' =>'Expozice',
'exif-exposuretime-format' => '$1 s ($2)',
'exif-fnumber' =>'Clona',
'exif-fnumber-format' =>'f/$1',
'exif-exposureprogram' =>'Expoziční program',
'exif-spectralsensitivity' =>'Spektrální citlivost',
'exif-isospeedratings' =>'Nastavení ISO citlivosti',
'exif-oecf' =>'Optoelectronická převodní funkce (OECF)',
'exif-shutterspeedvalue' =>'Rychlost závěrky',
'exif-aperturevalue' =>'Clona',
'exif-brightnessvalue' =>'Světlost',
'exif-exposurebiasvalue' =>'Změna expozice',
'exif-maxaperturevalue' =>'Nejmenší clona',
'exif-subjectdistance' =>'Vzdálenost k předmětu',
'exif-meteringmode' =>'Způsob měření',
'exif-lightsource' =>'Světelný zdroj',
'exif-flash' =>'Blesk',
'exif-focallength' =>'Ohnisková vzdálenost',
'exif-focallength-format' =>'$1 mm',
'exif-subjectarea' =>'Umístění předmětu',
'exif-flashenergy' =>'Výkon blesku',
'exif-spatialfrequencyresponse' =>'Odezva prostorové frekvence',
'exif-focalplanexresolution' =>'X rozlišení ohniskové roviny',
'exif-focalplaneyresolution' =>'Y rozlišení ohniskové roviny',
'exif-focalplaneresolutionunit' =>'Jednotka rozlišení ohniskové roviny',
'exif-subjectlocation' =>'Umístění předmětu',
'exif-exposureindex' =>'Expoziční index',
'exif-sensingmethod' =>'Senzor',
'exif-filesource' =>'Zdroj souboru',
'exif-scenetype' =>'Druh scény',
'exif-cfapattern' =>'Geometrické uspořádání senzoru',
'exif-customrendered' =>'Uživatelské zpracování',
'exif-exposuremode' =>'Expoziční režim',
'exif-whitebalance' =>'Vyvážení bílé',
'exif-digitalzoomratio' =>'Digitální zoom',
'exif-focallengthin35mmfilm' =>'Ekvivalent ohniskové délky pro 35mm film',
'exif-scenecapturetype' =>'Druh scény',
'exif-gaincontrol' =>'Úprava světlosti',
'exif-contrast' =>'Kontrast',
'exif-saturation' =>'Sytost',
'exif-sharpness' =>'Ostrost',
'exif-devicesettingdescription' =>'Popis nastavení zařízení',
'exif-subjectdistancerange' =>'Vzdálenost k předmětu',
'exif-imageuniqueid' =>'Unikátní ID obrázku',
'exif-gpsversionid' =>'Verze GPS tagu',
'exif-gpslatituderef' =>'Severní/jižní zeměpisná šířka',
'exif-gpslatitude' =>'Zeměpisná šířka',
'exif-gpslongituderef' =>'Východní/západní zeměpisná délka',
'exif-gpslongitude' =>'Zeměpisná délka',
'exif-gpsaltituderef' =>'Nad/podmořská výška/hloubka',
'exif-gpsaltitude' =>'Nadmořská výška',
'exif-gpstimestamp' =>'GPS čas (podle atomových hodin)',
'exif-gpssatellites' =>'Satelity použité pro měření',
'exif-gpsstatus' =>'Stav přijímače',
'exif-gpsmeasuremode' =>'Režim měření',
'exif-gpsdop' =>'Přesnost měření',
'exif-gpsspeedref' =>'Jednotka rychlosti',
'exif-gpsspeed' =>'Rychlost GPS přijímače',
'exif-gpstrackref' =>'Reference pro směr pohybu',
'exif-gpstrack' =>'Směr pohybu',
'exif-gpsimgdirectionref' =>'Reference pro orientaci obrázku',
'exif-gpsimgdirection' =>'Orientace obrázku',
'exif-gpsmapdatum' =>'Použitý geodetický systém',
'exif-gpsdestlatituderef' =>'Severní/jižní zeměpisná šířka předmětu',
'exif-gpsdestlatitude' =>'Zeměpisná šířka předmětu',
'exif-gpsdestlongituderef' =>'Východní/západní zeměpisná délka předmětu',
'exif-gpsdestlongitude' =>'Zeměpisná délka předmětu',
'exif-gpsdestbearingref' =>'Reference pro směr k předmětu',
'exif-gpsdestbearing' =>'Směr k předmětu',
'exif-gpsdestdistanceref' =>'Jednotka vzdálenosti k předmětu',
'exif-gpsdestdistance' =>'Vzdálenost k předmětu',
'exif-gpsprocessingmethod' =>'Označení metody zpracování GPS dat',
'exif-gpsareainformation' =>'Označení GPS oblasti',
'exif-gpsdatestamp' =>'Datum podle GPS',
'exif-gpsdifferential' =>'Diferenciální korekce GPS',

# Exif attributes

'exif-compression-1' => 'Nekomprimovaný',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normální', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Vodorovně převráceno', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Otočeno o 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Svisle převráceno', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Otočeno o 90° proti směru hodinových ručiček a svisle převráceno', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Otočeno o 90° ve směru hodinových ručiček', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Otočeno o 90° ve směru hodinových ručiček a svisle převráceno', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Otočeno o 90° proti směru hodinových ručiček', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky',
'exif-planarconfiguration-2' => 'planar',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'neexistuje',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

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

'exif-meteringmode-0' => 'Není známo',
'exif-meteringmode-1' => 'Průměrové',
'exif-meteringmode-2' => 'Vážený průměr',
'exif-meteringmode-3' => 'Bodové',
'exif-meteringmode-4' => 'Zónové',
'exif-meteringmode-5' => 'Vzorové',
'exif-meteringmode-6' => 'Částečné',
'exif-meteringmode-255' => 'Jiné',

'exif-lightsource-0' => 'Není známo',
'exif-lightsource-1' => 'Denní světlo',
'exif-lightsource-2' => 'Fluorescentní',
'exif-lightsource-3' => 'Žárovka',
'exif-lightsource-4' => 'Blesk',
'exif-lightsource-9' => 'Jasno',
'exif-lightsource-10' => 'Zamračeno',
'exif-lightsource-11' => 'Stín',
'exif-lightsource-12' => 'Zářivka denní světlo (D 5700 – 7100K)',
'exif-lightsource-13' => 'Zářivka bílé denní světlo (N 4600 – 5400K)',
'exif-lightsource-14' => 'Zářivka studená bílá (W 3900 – 4500K)',
'exif-lightsource-15' => 'Bílá zářivka (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardní osvětlení A',
'exif-lightsource-18' => 'Standardní osvětlení B',
'exif-lightsource-19' => 'Standardní osvětlení C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studiová žárovka',
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
'exif-scenecapturetype-1' => 'Na šířku',		# TODO: ?? portrét/krajina, nebo na šířku/na výšku -- co ta noční scéna?
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

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Severní šířka',
'exif-gpslatitude-s' => 'Jižní šířka',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Východní délka',
'exif-gpslongitude-w' => 'Západní délka',

'exif-gpsstatus-a' => 'Probíhá měření',
'exif-gpsstatus-v' => 'Měření mimo provoz',

'exif-gpsmeasuremode-2' => 'Dvourozměrné měření',
'exif-gpsmeasuremode-3' => 'Trojrozměrné měření',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/h',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'kt',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravý kurs',
'exif-gpsdirection-m' => 'Magnetický kurs',

# external editor support
'edit-externally' => 'Editovat tento soubor v externím programu',
'edit-externally-help' => 'Více informací najdete v [http://meta.wikimedia.org/wiki/Help:External_editors nápovědě pro nastavení].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'všechny',
'imagelistall' => 'všechny',
'watchlistall1' => 'všechny',
'watchlistall2' => 'všechny',
'namespacesall' => 'všechny',

# E-mail address confirmation
'confirmemail' => 'Potvrzení e-mailové adresy',
'confirmemail_text' => 'Tato wiki vyžaduje, abyste potvrdili svou e-mailovou adresu
před využíváním některých funkcí. Kliknutím na níže umístěné tlačítko dojde k odeslání
potvrzovacího e-mailu na vámi uvedeno adresu. Tento mail obsahuje odkaz a potvrzovací kód;
přejděte na odkazovanou stránku svým internetovým prohlížečem, tím potvrdíte, že
zadaná adresa je platná.',
'confirmemail_send' => 'Odeslat potvrzovací kód',
'confirmemail_sent' => 'Potvrzovací e-mail byl odeslán',
'confirmemail_sendfailed' => 'Nepodařilo se odeslat potvrzovací e-mail. Zkontrolujte, zda adresa neobsahuje chybné znaky.',
'confirmemail_invalid' => 'Neplatný potvrzovací kód. Možná již vypršela platnost kódu.',
'confirmemail_success' => 'Vaše e-mailová adresa byla potvrzena. Nyní se můžete přihlásit a používat wiki.',
'confirmemail_loggedin' => 'Vaše e-mailová adresa byla potvrzena.',
'confirmemail_error' => 'Nepodařilo se uložit vaše potvrzení.',
'confirmemail_needlogin' => 'Pro potvrzení své e-mailové adresy se musíte $1.',

'confirmemail_subject'  => 'Potvrzení e-mailové adresy pro {{grammar:4sg|{{SITENAME}}}}',
'confirmemail_body' => 'Někdo (patrně vy, z IP adresy $1) si registroval účet se jménem "$2" a touto e-mailovou adresou na {{grammar:6sg|{{SITENAME}}}}.

Pokud si přejete aktivovat e-mailové funkce na {{grammar:6sg|{{SITENAME}}}}, tak pro potvrzení,
že tato adresa opravdu patří vám, přejděte svým internetovým prohlížečem na následující adresu:

$3

Pokud jste o toto potvrzení *nežádali*, neklikejte na předchozí odkaz. Platnost tohoto potvrzovacího
kódu vyprší $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Vyzkoušet přesné hledání',
'searchfulltext' => 'Plnotextové hledání',
'createarticle' => 'Vytvořit článek',

# Scary transclusion
'scarytranscludedisabled' => '[Vkládání šablon mezi wiki je vypnuto]',
'scarytranscludefailed' => '[Nepodařilo se načíst šablonu pro $1; je mi líto]',
'scarytranscludetoolong' => '[Příliš dlouhé URL; je mi líto]',

# Trackbacks
'trackbackbox' => "<div id='mw_trackbacks'>
Zpětné odkazy k tomuto článku:<br />
$1
</div>",
'trackbackremove' => ' ([$1 Smazat])',
'trackbacklink' => 'Zpětný odkaz',
'trackbackdeleteok' => 'Zpětný odkaz byl úspěšně smazán.',

# delete conflict
'deletedwhileediting' => 'Upozornění: V průběhu vaší editace byla tato stránka smazána!',
'confirmrecreate' => 'Uživatel [[{{ns:2}}:$1|$1]] ([[{{ns:3}}:$1|diskuse]]) tento článek smazal poté, co jste začali editovat, s odůvodněním:
: „$2“
Opravdu si přejete znovu tento článek založit?',
'recreate' => 'Znovu založit',
'tooltip-recreate' => '',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Přesměrovává se na [[$1]]…',

# action=purge
'confirm_purge' => "Aktualizovat cachovanou verzi této stránky?\n\n$1",
'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => 'Na $1 máte nové zprávy',
'newtalkseperator' => ',_',
'searchcontaining' => "Hledat články obsahující ''$1''.",
'searchnamed' => "Hledat články pojmenované ''$1''.",
'articletitles' => "Články začínající ''$1''",
'hideresults' => 'Skrýt výsledky',

# DISPLAYTITLE
'displaytitle' => '(Na tuto stránku odkazujte pomocí [[$1]])',
);


?>
