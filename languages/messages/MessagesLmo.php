<?php
/** lumbaart (lumbaart)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amgine
 * @author Clamengh
 * @author Dakrismeno
 * @author DracoRoboter
 * @author Flavio05
 * @author GatoSelvadego
 * @author Insübrich
 * @author Kemmótar
 * @author Malafaya
 * @author Reedy
 * @author Remulazz
 * @author SabineCretella
 * @author Snowdog
 * @author Sprüngli
 */

$fallback = 'it';

$namespaceNames = array(
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Ciciarada',
	NS_USER             => 'Druvadur',
	NS_USER_TALK        => 'Ciciarada_Druvadur',
	NS_PROJECT_TALK     => '$1_Ciciarada',
	NS_FILE             => 'Archivi',
	NS_FILE_TALK        => 'Ciciarada_Archivi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Ciciarada_MediaWiki',
	NS_TEMPLATE         => 'Mudel',
	NS_TEMPLATE_TALK    => 'Ciciarada_Mudel',
	NS_HELP             => 'Jüt',
	NS_HELP_TALK        => 'Ciciarada_Jüt',
	NS_CATEGORY         => 'Categuria',
	NS_CATEGORY_TALK    => 'Ciciarada_Categuria',
);

$namespaceAliases = array(
	'Speciale'              => NS_SPECIAL,
	'Discussione'           => NS_TALK,
	'Utente'                => NS_USER,
	'Druvat'                => NS_USER,
	'Dovrat'                => NS_USER,
	'Discussioni_utente'    => NS_USER_TALK,
	'Ciciarada_Druvat'      => NS_USER_TALK,
	'Ciciarada_Dovrat'      => NS_USER_TALK,
	'Discussioni_$1'        => NS_PROJECT_TALK,
	'Immagine'              => NS_FILE,
	'Discussioni_file'      => NS_FILE_TALK,
	'Discussioni_immagine'  => NS_FILE_TALK,
	'Discussioni_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Model'                 => NS_TEMPLATE,
	'Discussioni_template'  => NS_TEMPLATE_TALK,
	'Ciciarada_Model'       => NS_TEMPLATE_TALK,
	'Aiuto'                 => NS_HELP,
	'Aida'                  => NS_HELP,
	'Discussioni_aiuto'     => NS_HELP_TALK,
	'Ciciarada_Aida'        => NS_HELP_TALK,
	'Categoria'             => NS_CATEGORY,
	'Discussioni_categoria' => NS_CATEGORY_TALK,
	'Ciciarada_Categoria'   => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Messagg' ),
	'BrokenRedirects'           => array( 'RedirezionS-cepada' ),
	'Categories'                => array( 'Categurij' ),
	'CreateAccount'             => array( 'CreaCünt' ),
	'Disambiguations'           => array( 'Desambiguazion' ),
	'DoubleRedirects'           => array( 'RedirezionDubia' ),
	'Listadmins'                => array( 'ListaAministradur' ),
	'Listfiles'                 => array( 'Imagin' ),
	'Listgrouprights'           => array( 'Lista_di_dirit_di_grüp' ),
	'Listusers'                 => array( 'Dupradur' ),
	'Lonelypages'               => array( 'PaginnDaPerLur' ),
	'Newimages'                 => array( 'ImaginNöv' ),
	'Preferences'               => array( 'Preferenz' ),
	'Randompage'                => array( 'PaginaAzardada' ),
	'Recentchanges'             => array( 'CambiamentRecent' ),
	'Recentchangeslinked'       => array( 'MudifeghCulegaa' ),
	'Specialpages'              => array( 'PaginnSpecial' ),
	'Statistics'                => array( 'Statìstegh' ),
	'Uncategorizedpages'        => array( 'PaginnMingaCategurizaa' ),
	'Upload'                    => array( 'CaregaSü' ),
	'Userlogin'                 => array( 'VenaDenter' ),
	'Userlogout'                => array( 'VaFö' ),
	'Watchlist'                 => array( 'SutOeugg' ),
);

$magicWords = array(
	'img_right'                 => array( '1', 'drita', 'destra', 'right' ),
	'img_left'                  => array( '1', 'manzína', 'sinistra', 'left' ),
	'img_none'                  => array( '1', 'nissön', 'nessuno', 'none' ),
	'sitename'                  => array( '1', 'NUMSIT', 'NOMESITO', 'SITENAME' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Sutulinia i ligam',
'tog-justify' => 'Paràgraf: giüstifigaa',
'tog-hideminor' => 'Scund i mudifegh men impurtant in di "cambiament recent"',
'tog-hidepatrolled' => 'Scund i mudifegh verifegaa intra i ültem mudifegh',
'tog-newpageshidepatrolled' => 'Scund i paginn verifegaa de la lista di paginn növ',
'tog-extendwatchlist' => 'Slarga la funziun "tegn sot ögg" in manera che la fà vidè tüt i mudifegh, minga dumà l\'ültema',
'tog-usenewrc' => 'Dupra i ültem mudifegh avanzaa (ghe vör el JavaScript)',
'tog-numberheadings' => 'Utu-nümerazión di paragraf',
'tog-showtoolbar' => 'Fá vidé ai butún da redataziún (JavaScript)',
'tog-editondblclick' => 'Redatá i pagin cun al dópi clich (JavaScript)',
'tog-editsection' => 'Abilità edizion di seczion par ligam',
'tog-editsectiononrightclick' => 'Abilitá redatazziún dai sezziún cun al clic<br />
süi titul dai sezziún (JavaScript)',
'tog-showtoc' => "Fà vidè l'indes per i paginn cun püssee de 3 sezión",
'tog-rememberpassword' => "Regòrdass la mè paròla d'urdin (for a maximum of $1 {{PLURAL:$1|day|days}})",
'tog-watchcreations' => "Giunta i paginn ch'hoo creaa mì a la lista di paginn che tegni sot ögg",
'tog-watchdefault' => "Gjüntá i pagin redataa in dala lista dii pagin tegnüü d'öcc",
'tog-watchmoves' => "Giunta i paginn ch'hoo muvüü a la lista di paginn che tegni sot ögg",
'tog-watchdeletion' => "Giunta i paginn ch'hoo scancelaa a la lista di paginn che tegni sot ögg",
'tog-minordefault' => 'Marca tücc i mudifegh cume piscinìn',
'tog-previewontop' => "Fá vidé un'anteprima anaanz dala finèstra da redatazziún",
'tog-previewonfirst' => "Fá vidé l'anteprima ala prima redatazziún",
'tog-nocache' => 'DIsativa la "cache" per i paginn',
'tog-enotifusertalkpages' => "Mandem un messagg e-mail quand che gh'è di mudifegh a la mè pàgina di ciaciarad",
'tog-enotifminoredits' => 'Màndem un messagg e-mail anca per i mudifegh piscinín',
'tog-enotifrevealaddr' => "Lassa vedè 'l mè indirizz e-mail int i messagg d'avis",
'tog-oldsig' => 'Anteprima de la firma esistenta:',
'tog-fancysig' => 'Trata la firma cume test wiki (senza nissön ligam utumatich)',
'tog-externaleditor' => "Dröva semper un prugrama da redatazión estern (dumà per espert, 'l gh'ha de besogn d'impustazión speciaj ins 'l to computer)",
'tog-externaldiff' => 'Druvá sempar un "diff" estèrnu',
'tog-watchlisthideown' => "Sconda i me mudifich dai pagin che a ten d'ögg",
'tog-watchlisthidebots' => "Sconda i mudifich di bot da i pagin che a ten d'ögg",
'tog-ccmeonemails' => 'Spedissem una copia di messagg spedii a i alter druvadur',
'tog-diffonly' => "Mustra mía el cuntegnüü de la pagina apress ai ''diffs''",
'tog-showhiddencats' => 'Fà vidè i categurij scundüü',
'tog-norollbackdiff' => "Mustra mía i ''diffs'' dop che i henn staa ripristinaa cun un rollback",

'underline-always' => 'Semper',
'underline-never' => 'Mai',
'underline-default' => 'Mantegn i impustazión standard del browser',

# Font style option in Special:Preferences
'editfont-style' => "Stil del font de l'area de mudifega:",
'editfont-default' => 'Browser de default',
'editfont-monospace' => 'Font mono-spaziaa',
'editfont-sansserif' => 'Font sans-serif',
'editfont-serif' => 'Font serif',

# Dates
'sunday' => 'Dumeniga',
'monday' => 'Lündesdí',
'tuesday' => 'Martedì',
'wednesday' => 'Merculdí',
'thursday' => 'Giuedí',
'friday' => 'Venerdí',
'saturday' => 'Sábat',
'sun' => 'Dom:',
'mon' => 'Lün',
'tue' => 'Mar',
'wed' => 'Ven',
'thu' => 'Giu',
'fri' => 'Ven',
'sat' => 'Sab',
'january' => 'Genar',
'february' => 'Febrar',
'march' => 'Marz',
'april' => 'Avril',
'may_long' => 'Magg',
'june' => 'Giügn',
'july' => 'Lüi',
'august' => 'Agust',
'september' => 'Setember',
'october' => 'Utuber',
'november' => 'Nuvember',
'december' => 'Dicember',
'january-gen' => 'Genar',
'february-gen' => 'Febrar',
'march-gen' => 'Marz',
'april-gen' => 'Avril',
'may-gen' => 'Magg',
'june-gen' => 'Giugn',
'july-gen' => 'Luj',
'august-gen' => 'Aoust',
'september-gen' => 'Setember',
'october-gen' => 'Otober',
'november-gen' => 'November',
'december-gen' => 'Dizember',
'jan' => 'Gen',
'feb' => 'Feb',
'mar' => 'Mrz',
'apr' => 'Avr',
'may' => 'Mag',
'jun' => 'Giü',
'jul' => 'Lüi',
'aug' => 'Agu',
'sep' => 'Set',
'oct' => 'Utu',
'nov' => 'Nuv',
'dec' => 'Dic',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categuria|Categurij}}',
'category_header' => 'Artìcuj int la categuria "$1"',
'subcategories' => 'Suta-categurij',
'category-media-header' => 'File int la categuria "$1"',
'category-empty' => "''Per 'l mument quela categuria chì la gh'ha denter né de paginn ne d'archivi mültimedia''",
'hidden-categories' => '{{PLURAL:$1|Categuria scundüda|Categurij scundüü}}',
'hidden-category-category' => 'Categurij scundüü',
'category-subcat-count' => "{{PLURAL:$2|Quela categuria chì la gh'ha dumà una sota-categuria, missa chì de sota.|Quela categuria chì la gh'ha {{PLURAL:$1|una sota-categuria|$1 sota-categurij}} chì de sota, sü un tutal de $2.}}",
'category-subcat-count-limited' => "Quela categuria chì la gh'ha denter {{PLURAL:$1|la sut-categuria| i $1 sut-categurij}} chì abass.",
'category-article-count' => "{{PLURAL:$2|Quela categuria chì la gh'ha dumà quela pagina chì.|In quela categuria chì gh'è {{PLURAL:$1|la pagina indicada|i $1 paginn indicaa}} de $2 che gh'hinn in tutal.}}",
'category-file-count' => "{{PLURAL:$2|Quela categuria chì la gh'ha denter dumà el file chì suta|Quela categuria chì la gh'ha denter {{PLURAL:$1|'l file|$1 i file}} ripurtaa chì suta, sü un tutal de $2.}}",
'listingcontinuesabbrev' => 'cont.',
'index-category' => 'Paginn indicizaa',

'about' => 'A pruposit də',
'article' => 'Pagina de cuntegnüü',
'newwindow' => "(sa derviss int un'óltra finèstra)",
'cancel' => 'Lassa perd',
'moredotdotdot' => 'Püssee',
'mypage' => 'La mè pagina',
'mytalk' => 'i mè discüssiun',
'anontalk' => 'Ciciarad per quel adress IP chì',
'navigation' => 'Navegazión',
'and' => '&#32;e',

# Cologne Blue skin
'qbfind' => 'Tröva',
'qbbrowse' => 'Sföja',
'qbedit' => 'Mudifega',
'qbpageoptions' => 'Opzión de la pagina',
'qbpageinfo' => 'Infurmazión revard a la pagina',
'qbmyoptions' => 'I mè paginn',
'qbspecialpages' => 'Paginn special',
'faq' => 'FAQ',
'faqpage' => 'Project:Dumand frequent',

# Vector skin
'vector-action-addsection' => 'Giunta argument',
'vector-action-delete' => 'Scancela',
'vector-action-move' => 'Sposta',
'vector-action-protect' => 'Prutegg',
'vector-action-undelete' => 'Recüpera',
'vector-action-unprotect' => 'Desbloca',
'vector-view-create' => 'Crea',
'vector-view-edit' => 'Mudifega',
'vector-view-history' => 'Varda la storia',
'vector-view-view' => 'Legg',
'vector-view-viewsource' => 'Varda el codes',
'actions' => 'Azión',
'namespaces' => 'Namespace',
'variants' => 'Variant',

'errorpagetitle' => 'Erur',
'returnto' => 'Turna indré a $1.',
'tagline' => 'De {{SITENAME}}',
'help' => 'Paginn de jüt',
'search' => 'Cerca',
'searchbutton' => 'Cerca',
'go' => 'Inanz',
'searcharticle' => 'Và',
'history' => 'Crunulugia de la pagina',
'history_short' => 'Crunulugìa',
'printableversion' => 'Versión stampàbil',
'permalink' => 'Culegament permanent',
'print' => 'Stampa',
'edit' => 'Mudifega',
'create' => 'Crea',
'editthispage' => 'Mudifega quela pagina chi',
'create-this-page' => 'Crea quela pagina chi',
'delete' => 'Scancela',
'deletethispage' => 'Scancela quela pagina chì',
'undelete_short' => 'Rimet a post {{PLURAL:$1|1 mudifica|$1 mudifigh}}',
'protect' => 'Bloca',
'protect_change' => 'cambia',
'protectthispage' => 'Prutegg quela pagina chì',
'unprotect' => 'Desbloca',
'unprotectthispage' => 'Tö via la pruteziun',
'newpage' => 'Pagina növa',
'talkpage' => 'Discüssión',
'talkpagelinktext' => 'Ciciarada',
'specialpage' => 'Pagina speciala',
'personaltools' => 'Istrüment persunaj',
'postcomment' => 'Sezión növa',
'articlepage' => "Varda l'articul",
'talk' => 'Discüssión',
'views' => 'Visid',
'toolbox' => 'Arnes',
'userpage' => 'Vidè la pàgina del dovrat',
'projectpage' => 'Varda la pagina de servizzi',
'imagepage' => 'Varda la pagina del file',
'mediawikipage' => 'Mustra el messagg',
'templatepage' => 'Mustra la bueta',
'viewhelppage' => 'Fà vidè la pagina de jüt',
'categorypage' => 'Fà vidè la categuria',
'viewtalkpage' => 'Varda i discüssiun',
'otherlanguages' => 'Alter lenguv',
'redirectedfrom' => '(Rimandaa da $1)',
'redirectpagesub' => 'Pagina de redirezión',
'lastmodifiedat' => "Quela pagina chì l'è stada mudifegada l'ültima völta del $1, a $2.",
'viewcount' => "Quela pagina chì a l'è stada legiüda {{PLURAL:$1|una völta|$1 völta}}.",
'protectedpage' => 'Pagina prutegiüda',
'jumpto' => 'Va a:',
'jumptonavigation' => 'Navigazión',
'jumptosearch' => 'cerca',
'view-pool-error' => "Ne rincress, ma i server a hinn bej caregaa al mument.
Trop drovat a hinn 'dree pruvà a vardà quela pagina chì.
Per piasè, specia un mument prima de pruà a vardà anmò quela pagina chì.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'A prupòsit de {{SITENAME}}',
'aboutpage' => 'Project:A pruposit',
'copyright' => "El cuntegnüü a l'è dispunibil sota a una licenza $1.",
'copyrightpage' => "{{ns:project}}:Dirit d'autur",
'currentevents' => 'Atüalitaa',
'currentevents-url' => 'Project:Aveniment Recent',
'disclaimers' => 'Disclaimers',
'disclaimerpage' => 'Project:Avertenz generaj',
'edithelp' => 'Manual de spiegazión',
'edithelppage' => 'Help:Scriv un articul',
'helppage' => 'Help:Contegnüü',
'mainpage' => 'Pagina principala',
'mainpage-description' => 'Pagina principala',
'policy-url' => 'Project:Policy',
'portal' => 'Purtal de la cumünità',
'portal-url' => 'Project:Purtal de la cumünità',
'privacy' => "Pulitega de la ''privacy''",
'privacypage' => 'Project:Infurmazión ins la privacy',

'badaccess' => 'Permiss sbajaa',
'badaccess-group0' => "Te gh'è mía 'l permiss per tirà inanz cun 'sta uperazión chì.",
'badaccess-groups' => "Quela funzión chì l'è reservada ai druvat che i henn in {{PLURAL:$2|del grüp|vün di grüp chì suta}}: $1.",

'versionrequired' => 'Al ghe va per forza la versión $1 de MediaWiki',
'versionrequiredtext' => 'Per duprà quela pagina chì la ghe va la versión $1 del prugrama MediaWiki. Varda [[Special:Version]]',

'ok' => 'Va ben',
'pagetitle' => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom' => 'Utegnüü de "$1"',
'youhavenewmessages' => "A gh'hii di $1 ($2).",
'newmessageslink' => 'messagg növ',
'newmessagesdifflink' => 'diferenza cun la versión de prima',
'youhavenewmessagesmulti' => "Te gh'hee di messagg növ ins'el $1",
'editsection' => 'mudifega',
'editold' => 'mudifega',
'viewsourceold' => 'fà vidè el codes surgent',
'editlink' => 'mudifega',
'viewsourcelink' => 'fà vidè el codes surgent',
'editsectionhint' => 'Mudifega la sezión $1',
'toc' => 'Cuntegnüü',
'showtoc' => 'fà vidè',
'hidetoc' => 'scund',
'thisisdeleted' => 'Varda o rimet a post $1?',
'viewdeleted' => 'Te vöret vidè $1?',
'restorelink' => '{{PLURAL:$1|1 mudifega scancelada|$1 mudifegh scancelaa}}',
'feedlinks' => 'Feed:',
'feed-invalid' => 'Mudalità de sotascrizión del feed minga valida',
'feed-unavailable' => "Gh'en è minga de feed",
'site-rss-feed' => 'Feed RSS de $1',
'site-atom-feed' => 'Feed Atom de $1',
'page-rss-feed' => 'Feed RSS per "$1"',
'page-atom-feed' => 'Feed Atom per "$1"',
'red-link-title' => "$1 (la pagina la gh'è minga)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Articul',
'nstab-user' => 'Pagina persunala',
'nstab-media' => 'Pagina multimediala',
'nstab-special' => 'Pagina speciala',
'nstab-project' => 'Pagina de servizi',
'nstab-image' => 'Figüra',
'nstab-mediawiki' => 'Messagg',
'nstab-template' => 'Bueta',
'nstab-help' => 'Ajüt',
'nstab-category' => 'Categuria',

# Main script and global functions
'nosuchaction' => 'Uperaziun minga recugnussüda',
'nosuchactiontext' => "L'uperaziun che t'hee ciamaa in del ligam URL a l'è minga recugnussüda.<br />
Pö vess che t'hee batüü mal l'URL, o che seet andaa adree a un ligam minga bun.<br />
Quest chì al pudaria anca indicà un bug dent in del software dupraa de {{SITENAME}}.",
'nosuchspecialpage' => "La gh'è minga una pagina pagina special tan 'me quela che t'hee ciamaa",
'nospecialpagetext' => "<strong>T'hee ciamaa una pagina speciala minga valida.</strong>

Una lista di paginn special la se pö truà in de la [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Erur',
'databaseerror' => 'Erur in del database',
'readonly' => 'Database blucaa',
'missing-article' => "El database l'ha minga truaa el test d'una pagina che l'avaria duvüü truà, ciamaa \"\$1\" \$2.

Quest chì a l'è de solet causaa perchè al s'è riciamaa un paragón intra revisión vegg de la pagina o un ligam a una versión vegia d'una pagina che l'è stada scancelada

Se l'è minga inscì, te pudariet vegh truà un bug in del software.

Per piasè, fa raport a 'n'[[Special:ListUsers/sysop|aministradur]], cun la nota de l'URL.",
'missingarticle-rev' => '(revision#: $1)',
'missingarticle-diff' => '(Diff: $1, $2)',
'internalerror' => 'Erur in del sistema',
'internalerror_info' => 'Erur intern: $1',
'filecopyerror' => 'L\'è mía staa pussibel cubià l\'archivi "$1" in "$2"',
'badtitle' => 'Títul mía bun',
'badtitletext' => "El titul de la pagina ciamada a l'è vöj, sbajaa o cun carater minga acetaa, opüra al vegn d'un erur in di ligam intra sit wiki diferent o versión in lenguv diferent de l'istess sit.",
'viewsource' => 'Còdas surgent',
'protectedpagetext' => "Cula pagina chi l'è stata blucà per impedinn la mudifica.",
'viewsourcetext' => "L'è pussibil vèd e cupià el codes surgent de cula pagina chí:",
'editinginterface' => "'''Ocio''': Te see adree a mudifegà una pàgina che la se dröva per generà 'l test de l'interfacia del prugrama. Qualsìa mudìfega fada la cambierà l'interfacia de tüt i druvadur. Se te gh'hee intenzión de fà una tradüzión, per piasì cunsiderà la pussibilità de druvà [//translatewiki.net/wiki/Main_Page?setlang=lmo translatewiki.net], 'l pruget de lucalizazión de MediaWiki.",
'ns-specialprotected' => 'I paginn special i pören mía vess mudifegaa',

# Login and logout pages
'logouttext' => "'''Adess a sii descuness.'''

A pudé andà inanz a druvà la {{SITENAME}} in manera anònima, o a pudé [[Special:UserLogin|cunètev anmò]] cun l'istess suranomm o cun un suranomm diferent.
Tegné cünt che certi paginn pödass che i seguiten a vedess tant 'me se a füdìssuv anmò cuness, fin quand che hii nò vudaa 'l ''cache'' del voster browser.",
'welcomecreation' => "== Benvegnüü, $1! ==
'L to cünt l'è staa pruntaa. Desmenteghet mía de mudifegà i to [[Special:Preferences|preferenz de {{SITENAME}}]].",
'yourname' => 'El to suranóm:',
'yourpassword' => "Parola d'urdin",
'yourpasswordagain' => "Mett dent ammò la parola d'urdin",
'remembermypassword' => "Regordass la mè parola d'urdin (for a maximum of $1 {{PLURAL:$1|day|days}})",
'login' => 'Va dent',
'nav-login-createaccount' => 'Vena denter / Crea un cünt',
'loginprompt' => 'Par cunett a {{SITENAME}}, a duvii abilitá i galet.',
'userlogin' => 'Vegní denter - Creè un cünt',
'userloginnocreate' => 'Vegn denter',
'logout' => 'Va fö',
'userlogout' => 'Và fö',
'notloggedin' => 'Te seet minga dent in del sistema',
'nologin' => "Gh'avii anmò da registrav? '''$1'''.",
'nologinlink' => 'Creé un cünt!',
'createaccount' => 'Creá un cünt',
'gotaccount' => "Gh'hee-t giamò un cünt? '''$1'''.",
'gotaccountlink' => 'Va dent in del sistema',
'createaccountmail' => 'per indirizz e-mail',
'badretype' => "I password che t'hee miss a hinn diferent.",
'userexists' => "El nom de duvrat che t'hee miss dent a l'è giamò dupraa.
Per piasè, scerniss un alter suranom.",
'loginerror' => "Erur in de l'andà dent in del sistema.",
'createaccounterror' => 'Se pö minga creà el cünt: $1',
'nocookiesnew' => "El cünt a l'è staa creaa, ma t'hee minga pudüü andà dent in del sistema.
{{SITENAME}} al dupra i cookies per fà andà i duvrat in del sistema.
Tì te gh'hee i cookies disabilitaa.
Per piasè, abilita i cookies e pröa anmò a andà dent cunt el tò nom e la password.",
'noname' => "Vüü avii mía specificaa un nomm d'üsüari valévul.",
'loginsuccesstitle' => "La cunessiun l'è scumenzada cun sücess.",
'loginsuccess' => 'Al é connectaa a {{SITENAME}} compagn "$1".',
'nosuchuser' => "A gh'è nissün druvat cun 'l nom ''$1''. <br />
I suranomm i henn sensibil a i leter majùscul.<br />
Cuntrola 'l nom che t'hee metüü denter o [[Special:UserLogin/signup|crea un cünt növ]].",
'nosuchusershort' => "Ghe n'è mia d'ütent cun el nom de \"\$1\". Ch'el cuntrola se l'ha scrivüü giüst.",
'nouserspecified' => "Te gh'heet da specificà un nom del druvatt.",
'wrongpassword' => "La ciav che t'hee metüü denter l'è nò giüsta. Pröva turna per piasè.",
'wrongpasswordempty' => "T'hee no metüü denter la parola ciav. Pröva turna per piasè.",
'mailmypassword' => 'Spedissem una password növa per e-mail',
'passwordremindertext' => "Un quajdün (prubabilment ti, cun l'indiriz IP \$1) l'ha ciamaa da mandagh 'na ciav növa per andà denter int 'l sistema de {{SITENAME}} (\$4).
La ciav per 'l druvadur \"\$2\" adess l'è \"\$3\".
Sariss mej andà denter int 'l sit almanch una völta prima de cambià la ciav.
La to ciav tempuranea la scaderà da chì a {{PLURAL:\$5|un dì|\$5 dì}}.

Se te nò staa ti a ciamà 'sta ciav chì, o magara t'hee truaa la ciav vegia e te vör pü cambiala, te pör ignurà 'stu messagg chì e 'ndà inanz a druà la ciav vegia.",
'passwordsent' => "Una parola ciav bele növa l'è staa spedii a l'indiriz e-mail registra da l'ütent \"\$1\".
Per piasè, ve drent anmò dop che te l'ricevüü.",
'blocked-mailpassword' => "'L to indirizz IP l'è blucaa, e per quela resón lì te pö mía druvà la funzion de recüper de la password.",
'emailauthenticated' => "'L tò indirizz e-mail l'è staa verificaa 'l $2 ai $3.",
'emailnotauthenticated' => 'Ul tò adrèss da pòsta letronica l è mia staa gnamò verificaa. Nissün mesacc al saraa mandaa par i servizzi che segütan.',
'emailconfirmlink' => "Cunferma 'l to indirizz e-mail",
'accountcreated' => 'Cunt bell-e-cread',
'accountcreatedtext' => "'L cünt del druvat $1 l'è bele pruntaa.",
'loginlanguagelabel' => 'Lengua: $1',

# Change password dialog
'oldpassword' => "Paròla d'urdin végja:",
'newpassword' => "Paròla d'urdin növa:",
'retypenew' => "Scriv ancamò la paròla d'urdin növa:",

# Edit page toolbar
'bold_sample' => 'Test in grasset',
'bold_tip' => 'Test in grasset',
'italic_sample' => 'Test in cursiv',
'italic_tip' => 'Test in cursiv',
'link_sample' => 'Titul del ligam',
'link_tip' => 'Ligam de dent',
'extlink_sample' => 'http://www.example.com titul del ligam',
'extlink_tip' => 'Ligam de föra (regordess el prefiss http:// )',
'headline_sample' => "Intestazión de l'articul",
'headline_tip' => 'Intestazión de 2° nivel',
'nowiki_sample' => 'Met dent chì el test minga furmataa',
'nowiki_tip' => 'Ignora la furmatazión wiki',
'image_tip' => 'File inglubaa in del test',
'media_tip' => 'Ligam a un file multimedial',
'sig_tip' => 'Firma cun data e ura',
'hr_tip' => 'Riga urizuntala (duprala cun giüdizi)',

# Edit pages
'summary' => 'Mutiv per la mudifega:',
'subject' => 'Suget (intestazión)',
'minoredit' => "Questa chì l'è una mudifega piscinina",
'watchthis' => "Tegn d'ögg quela pagina chì",
'savearticle' => 'Salva',
'preview' => 'Varda prima de salvà la pagina',
'showpreview' => 'Famm vedè prima',
'showdiff' => 'Famm vedè i cambiament',
'anoneditwarning' => 'Tì te set minga entraa. In de la crunulugia de la pagina se vedarà el tò IP.',
'summary-preview' => "Pröva de l'uget:",
'blockedtext' => "'''El to nom del druvadur o el to indirizz IP l'è stat blucaa.'''

El bloch l'è stat fat da $1.
El mutiv per el bloch l'è: ''$2''

* Principi del bloch: $8
* Scadenza del bloch: $6
* Blucaa: $7

Se a vurii, a pudii cuntatà $1 o un olter [[{{MediaWiki:Grouppage-sysop}}|aministradur]] per discüt el bloch.

Feegh a ment che la funzion 'Manda un email a quel druvadur chì' l'è mia ativa se avii mia registraa un indirizz e-mail valid ind i voster [[Special:Preferences|preferenz]] o se l'üsagg de 'sta funzion l' è stat blucaa.

L'indirizz IP curent l'è $3, el nümer ID del bloch l'è #$5.
Fee el piasè d'inclüd tüt i detaj chì de sura in qualsessìa dumanda che a decidii de fà.",
'accmailtext' => 'La parola d\'urdin per "$1" l\'è stada mandada a $2.',
'newarticle' => '(Növ)',
'newarticletext' => 'Te seet andaa adree a un ligam a una pagina che la esista gnamò.
Per creà la pagina, a l\'è assee che te tachet a scriv in del box desota (varda la [[{{MediaWiki:Helppage}}|pagina de vüt]] per savèn püssee).
Se te seet chì per erur, schiscia "indree" in sül tò browser.',
'anontalkpagetext' => "''Questa chí a l'é la pagina da ciciarada d'un druvadur che l'ha nonanmò registraa un cünt, o che 'l le dröva mia.
Per 'sta reson chí, el pò vess identificaa dumà cunt el sò indirizz nümereg de IP.
'Stu indirizz IP el pö vess druvaa da püssee d'un druvadur. Se te seet un druvadur anònim e ve someja che un quaj messagg ch'al ga par ch'al gh'a nagòt à vidé con lu, ch'al prœuva a [[Special:UserLogin|creà el sò cunt]].''",
'noarticletext' => "Per 'l mument quela pagina chì l'è vöja. Te pòdet [[Special:Search/{{PAGENAME}}|cercà quel articul chì]] int i alter paginn, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cercà int i register imparentaa], o sedenò [{{fullurl:{{FULLPAGENAME}}|action=edit}} mudifichè 'sta pagina chì adess-adess]</span>.",
'clearyourcache' => "'''Nòta:''' dòpu che avii salvaa, pudaría véss neçessari de scancelá la memòria \"cache\" dal vòst prugráma də navigazziún in reet par vidé i mudifich faa. '''Mozilla / Firefox / Safari:''' tegní schiscjaa al butún ''Shift'' intaant che sə clica ''Reload'', upüür schiscjá ''Ctrl-Shift-R'' (''Cmd-Shift-R'' sül Apple Mac); '''IE:''' schiscjá ''Ctrl'' intaant che sə clica ''Refresh'', upüür schiscjá ''Ctrl-F5''; '''Konqueror:''': semplicemeent clicá al butún ''Reload'', upüür schiscjá ''F5''; '''Opera''' i üteent pudarían vech büsögn da scancelá cumpletameent la memòria \"cache\" in ''Tools&rarr;Preferences''.",
'previewnote' => "''''''Atenziun'''! Questa pagina la serviss dumà de vardà. I cambiament hinn minga staa salvaa.'''",
'editing' => 'Mudifega de $1',
'editingsection' => 'Mudifega de $1 (sezión)',
'editingcomment' => 'Adree a mudifegà $1 (sezión növa)',
'yourtext' => 'El tò test',
'yourdiff' => 'Diferenz',
'copyrightwarning' => "Ten per piasè present che tüt i cuntribüzión a {{SITENAME}} se cunsideren daa sota una licenza $2 (varda $1 per savèn püssee).
Se te vöret minga che i tò test i poden vess mudifegaa e redistribüii d'una persona qualsessia senza nissüna limitazión, mandei minga a {{SITENAME}}<br />
Cunt el test che te mandet tì te deciaret anca che, sota la tò respunsabilità, che el test te l'hee scrivüü depertì 'me uriginal, o pür che l'è una cobia d'una funt de dumini pübligh o un'altra funt libera in manera cumpagna.<br />
'''MANDA MINGA DEL MATERIAL CHE L'E' CUERT D'UN DIRIT D'AUTUR SENZA UTURIZAZIUN'''",
'protectedpagewarning' => "'''Ocio: quela pagina chì l'è stada blucaa in manéra che dumá i dupradur cunt i privilegg de sysop i pören mudificàla.'''",
'semiprotectedpagewarning' => "'''Nota:''' Quela pagina chì l'è stada blucada in manera che dumà i druvadur registraa i pören mudifegàla.
L'ültima vus del register l'è mustrada chì de suta per riferiment:",
'templatesused' => '{{PLURAL:$1|Mudel|Mudej}} dopraa in quela pagina chì:',
'templatesusedpreview' => '{{PLURAL:$1|Mudel|Mudej}} dopraa in quela pröva chì:',
'template-protected' => '(prutegiüü)',
'template-semiprotected' => '(semi-prutegiüü)',
'hiddencategories' => 'Quela pagina chì la fa part de {{PLURAL:$1|una categuria|$1 categurij}} scundüü:',
'permissionserrorstext-withaction' => "Te gh'hee minga i permiss per $2, per {{PLURAL:$1|quela resón chì |quij resón chì}}:",
'recreate-moveddeleted-warn' => "'''Ocio: te see adree a creà turna una pagina che l'eva giamò stada scancelada.'''

Cuntrulee se l'è propi el cas de cuntinuà a mudifegà 'sta pagina chì.
Per cumudità, la lista di scancelament e di San Martìn l'è ripurtada chi de suta:",

# "Undo" feature
'undo-summary' => 'Scancelada la mudifega $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Ciciarada]])',

# History pages
'viewpagelogs' => 'Varda i register de quela pagina chì',
'currentrev-asof' => 'Versión curenta di $1',
'revisionasof' => 'Revisión $1',
'previousrevision' => '←Versión püssee vegia',
'nextrevision' => 'Revisión püssee növ →',
'currentrevisionlink' => 'Varda la revisión curenta',
'cur' => 'Cur',
'next' => 'pròssim',
'last' => 'ültima',
'histlegend' => "Selezion di diferenz: seleziuná i balitt di version de cumpará e pö schisciá ''enter'' upüra al buton in scima ala tabèlina.<br />
Spiegazzion di símbul: (cur) = diferenza cun la version curenta, (ültima) = diferenza cun l'ültima version, M = mudifega piscinína.",
'history-fieldset-title' => 'Varda la cronolugia',
'histfirst' => 'Püssee vegg',
'histlast' => 'Püssee növ',

# Revision deletion
'rev-deleted-text-permission' => "Questa version de la pagina l'è stada '''scancelada'''.
Per infurmazion, varda ind el [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} register di scancelament].",
'rev-delundel' => 'fa vidè/scund',
'revdel-restore' => 'Cambia la visibilità',
'revdelete-edit-reasonlist' => 'Mudifega i mutiv del scancelament',

# Merge log
'revertmerge' => 'Scancela i ünión',

# Diffs
'history-title' => 'Cronolugia di mudifegh de "$1"',
'lineno' => 'Riga $1:',
'compareselectedversions' => 'Compara i versión seleziunaa',
'editundo' => "turna a 'me che l'era",

# Search results
'searchresults' => 'Risültaa de la recerca.',
'searchresults-title' => 'Resültaa de la ricerca de "$1"',
'searchresulttext' => 'Per vegh püssee infurmazión in de la ricerca interna de {{SITENAME}}, varda [[{{MediaWiki:Helppage}}|Ricerca in {{SITENAME}}]].',
'searchsubtitle' => 'Tì t\'hee cercaa \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tüt i paginn che scumincen per "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tüt i paginn che porten a "$1"]])',
'searchsubtitleinvalid' => 'T\'hee cercaa "$1"',
'toomanymatches' => "Gh'è tropi curispundens. Mudifichè la richiesta.",
'notitlematches' => "La vus che t'hee ciamaa la se tröa minga intra i tituj di articuj",
'textmatches' => "Truvaa int 'l test di paginn",
'notextmatches' => "La vus che t'hee ciamaa la gh'ha minga una curispundenza in del test di paginn.",
'prevn' => 'precedent {{PLURAL:$1|$1}}',
'nextn' => 'pròssim {{PLURAL:$1|$1}}',
'viewprevnext' => 'Vidé ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Upzión de ricerca',
'searchmenu-new' => "'''Trà in pee la pagina \"[[:\$1]]\" ins quel sit chì!'''",
'searchhelp-url' => 'Help:Contegnüü',
'searchprofile-articles' => 'Paginn de cuntegnüü',
'searchprofile-project' => 'Paginn de jüt e de pruget',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Tüt',
'searchprofile-advanced' => 'Avanzaa',
'searchprofile-articles-tooltip' => 'Cerca in $1',
'searchprofile-project-tooltip' => 'Cerca in $1',
'searchprofile-everything-tooltip' => 'Cerca depertüt (anca int i paginn de discüssion)',
'searchprofile-advanced-tooltip' => 'Cerca int i namespace persunalizaa',
'search-result-size' => '$1 ({{PLURAL:$2|1 parola|$2 paroll}})',
'search-redirect' => '(redirezión $1)',
'search-section' => '(sessión $1)',
'search-suggest' => 'Vurivet dì: $1',
'search-interwiki-caption' => 'Pruget fredej',
'search-interwiki-default' => '$1 resültaa',
'search-interwiki-more' => '(püssee)',
'nonefound' => "''''Tenzión''': la ricerca la vegn fada in utumategh dumà per un quaj namespace.
Pröa a giuntagh denanz a la tò ricerca ''all:'' per cercà in tücc i namespace (cumpres i discüssión, i mudel, etc...) o dupra el namespace vursüü 'me prefiss.",
'powersearch' => 'Truvá',
'powersearch-legend' => 'Recerca avanzada',
'powersearch-ns' => 'Cerca in di namespace:',
'powersearch-redir' => 'Lista i redirezión',
'powersearch-field' => 'Cerca',

# Preferences page
'preferences' => 'Preferenz',
'mypreferences' => 'i mè preferenz',
'prefs-edits' => 'Quantità de mudifegh faa:',
'changepassword' => "Mudifega la paròla d'urdin",
'prefs-skin' => "Aspett de l'interfacia",
'datedefault' => 'Nissüna preferenza',
'prefs-datetime' => 'Data e urari',
'prefs-personal' => 'Carateristich dal dupradur',
'prefs-rc' => 'Cambiament recent',
'prefs-watchlist' => "Paginn tegnüü d'ögg",
'prefs-watchlist-days' => "Nümer de dì da mustrà ind i paginn da tegn d'ögg:",
'prefs-watchlist-edits' => 'Nümer de mudifegh da mustrà cunt i fünzión avanzaa:',
'prefs-misc' => 'Ólter',
'prefs-rendering' => 'Aparenza',
'saveprefs' => 'Tegn i mudifech',
'resetprefs' => 'Trá via i mudifech',
'restoreprefs' => 'Ristabiliss i impustazión de default',
'prefs-editing' => 'Mudifich',
'rows' => 'Riich:',
'columns' => 'Culònn:',
'searchresultshead' => 'Cerca',
'resultsperpage' => 'Resültaa pər pagina:',
'recentchangescount' => "Nümer de mudifegh da mustrà per ''default'':",
'savedprefs' => 'I preferenz hinn stai salvaa.',
'timezonelegend' => 'Lucalitaa',
'localtime' => 'Urari lucaal',
'timezoneoffset' => 'Diferenza¹',
'servertime' => 'Urari dal sèrver',
'guesstimezone' => 'Catá l urari dal sèrver',
'allowemail' => 'Permètt ai altar üteent də cuntatamm par email',
'prefs-searchoptions' => 'Upzión de ricerca',
'defaultns' => 'Tröva sempar in di caamp:',
'prefs-files' => 'Archivi',
'prefs-emailconfirm-label' => "Cunferma de l'e-mail:",
'youremail' => 'E-mail',
'username' => 'Nom dal dovrée',
'uid' => 'ID del druvadur:',
'prefs-memberingroups' => 'Mémber {{PLURAL:$1|del grüp|di grüp}}:',
'prefs-registration' => 'Registraa dal:',
'yourrealname' => 'Nomm:',
'yourlanguage' => 'Lengua:',
'yournick' => 'Suranomm:',
'prefs-help-signature' => "I cument ind i paginn de discüssion i gh'han de vess firmaa cun \"<nowiki>~~~~</nowiki>\" che 'l sarà pö cunvertì int la tua firma cun tacada la data e l'ura.",
'yourgender' => 'Géner:',
'gender-unknown' => 'Mía specifegaa',
'gender-male' => "Mas'c",
'gender-female' => 'Femena',
'prefs-help-gender' => 'Upziunal: druvaa per adatà i messagg del software a segónda del gener del druvadur. Questa infurmazion chì la sarà püblica.',
'email' => 'Indirizz de pòsta elettrònica.',
'prefs-help-email' => "L'e-mail a l'è mia obligatòri, però al permet da mandàv una ciav noeva in cas che ve la desmenteghé. A podé apó scernì da lassà entrà i alter dovrat in contat con violter senza da busogn da svelà la vosta identità.",
'prefs-info' => 'Infurmazion de bas',
'prefs-i18n' => 'Internaziunalizazión',
'prefs-advancedrendering' => 'Fünzión avanzaa',

# User rights
'userrights' => 'Gestión di dirit di druvadur',
'userrights-lookup-user' => 'Gestion di grüp di druvaduu',
'userrights-user-editname' => 'Butée dent un nom da dovrat',
'editusergroup' => 'Mudifega i grüp del druvadur',
'editinguser' => "Mudifega di dirit del druvadur '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Mudifega i grüp del druvadur',
'saveusergroups' => 'Salva i grüp di druvadur',
'userrights-groupsmember' => 'El fà part di grüp:',
'userrights-groups-help' => "Se pö cambià i grüp ai qual l'è assegnaa quel druvadur chì.
* Un quader marcaa 'l vör dì che 'l druvadur al fà part de quel grüp lì.
* Un quader mia marcaa 'l vör dì che 'l druvadur el fà mia part de quel grüp lì.
* L'asterisch (*) el vör dì che se pö mia tö via un druvadur dal grüp dop d'avèghel giuntaa, o vice versa.",
'userrights-reason' => 'Reson:',
'userrights-no-interwiki' => "Te gh'hee mía i permiss necessari per pudè mudifegà i dirit di druvadur di olter wiki.",
'userrights-nodatabase' => "La base dat $1 a gh'é mia, o pura a l'é mia locala.",
'userrights-nologin' => "Al gh'a da [[Special:UserLogin|rintrà ent el sistema]] con un cunt d'administrator par podé dà di drecc ai dovracc.",
'userrights-notallowed' => "A l'ha mia li permission par podé dà di drecc ai dovracc.",
'userrights-changeable-col' => 'Grüp che te pö mudifegà',
'userrights-unchangeable-col' => 'Grüp che te pö mia mudifegà',

# Groups
'group-user' => 'Druvadur',
'group-autoconfirmed' => "Druvadur che i s'henn cunvalidaa deperlur",
'group-sysop' => 'Aministradur',

'group-user-member' => 'Dovratt',

'grouppage-user' => '{{ns:project}}:Druvadur',
'grouppage-sysop' => '{{ns:project}}:Aministradur',

# Rights
'right-edit' => 'Edita pàgini',
'right-createaccount' => 'Crea cünt de dovratt bej-e növ',

# User rights log
'rightslog' => 'Dirit di druvat',
'rightslogentry' => "l'ha mudifegaa $1 dal grüp $2 al grüp $3",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'mudifega quela pagina chì',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|mudifega|mudifegh}}',
'recentchanges' => 'Cambiament recent',
'recentchanges-legend' => 'Upzión ültem mudifegh',
'recentchanges-summary' => "In quela pagina chì a gh'è i cambiament püssee recent al cuntegnüü del sit.",
'recentchanges-feed-description' => "Quel feed chì 'l mustra i mudifegh püssee recent ai cuntegnüü de la wiki.",
'recentchanges-label-newpage' => "Quela mudifega chì l'ha creaa una pagina növa",
'recentchanges-label-minor' => "Quela chì l'è una mudifega piscinina.",
'recentchanges-label-bot' => "Quela mudifega chì l'ha fada un bot",
'recentchanges-label-unpatrolled' => "Quela mudifega chì a l'è stada mimga anmò verificada.",
'rcnote' => "Chì de sota {{PLURAL:$1|gh'è '''1''' mudifega|a hinn i ültim '''$1''' mudifegh}} in di ültim {{PLURAL:$2|dì|'''$2''' dì}}, a partì dai $5 del $4.",
'rcnotefrom' => "Chì de sota gh'è la lista di mudifegh de <b>$2</b> (fina a <b>$1</b>).",
'rclistfrom' => 'Fà vidè i cambiament növ a partì de $1',
'rcshowhideminor' => '$1 i mudifegh piscinín',
'rcshowhidebots' => '$1 i bot',
'rcshowhideliu' => '$1 i dupradur cunetüü',
'rcshowhideanons' => '$1 i dupradur anònim',
'rcshowhidemine' => '$1 i mè mudifich',
'rclinks' => 'Fà vedé i ültim $1 cambiament in di ültim $2 dì<br />$3',
'diff' => 'dif',
'hist' => 'stòria',
'hide' => 'Scund',
'show' => 'Famm vedè',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'b',
'newsectionsummary' => '/* $1 */ sezión növa',
'rc-enhanced-expand' => 'Varda i detali (al vör el JavaScript)',
'rc-enhanced-hide' => 'Scund i detali',

# Recent changes linked
'recentchangeslinked' => 'Cambiament culegaa',
'recentchangeslinked-feed' => 'Cambiament culegaa',
'recentchangeslinked-toolbox' => 'Cambiament culegaa',
'recentchangeslinked-title' => 'Mudifegh ligaa a "$1"',
'recentchangeslinked-summary' => "Questa chì a l'è una lista di paginn faa de poch temp ai paginn culigaa a quela specifegada (o a member d'una categuria specifegada).
I paginn dent in [[Special:Watchlist|la lista ch'it ten-e sot euj]] i resten marcaa in \"grasset\"",
'recentchangeslinked-page' => 'Nom de la pagina:',
'recentchangeslinked-to' => 'Fà vidè dumà i mudifegh ai paginn culigaa a quela dada',

# Upload
'upload' => 'Carga sü un file',
'uploadbtn' => 'Carga sü',
'uploadnologin' => 'Minga cuness',
'uploadlogpage' => 'Log di file caregaa',
'filedesc' => 'Sumari',
'fileuploadsummary' => 'Sumari:',
'ignorewarnings' => 'Ignora tücc i avertimeent',
'largefileserver' => 'Chel archivi-chí al è püssee graant che ul serviduur al sía cunfigüraa da permett.',
'uploadedimage' => 'l\'ha cargaa "[[$1]]"',
'sourcefilename' => "Nomm da l'archivi surgeent:",
'destfilename' => "Nomm da l'archivi da destinazziun:",

# Special:ListFiles
'imgfile' => 'archivi',
'listfiles' => 'Listá i imàgin',
'listfiles_date' => 'Dada',
'listfiles_name' => 'Nomm',
'listfiles_user' => 'Dovratt',

# File description page
'filehist' => "Storia de l'archivi",
'filehist-help' => "Schiscia in sü un grüp data/ura per vidè el file cumè che'l se presentava in quel mument là",
'filehist-deleteall' => 'scancela tüt',
'filehist-deleteone' => 'Scancèla',
'filehist-revert' => "Butar torna 'me ch'al era",
'filehist-current' => 'curent',
'filehist-datetime' => 'Data/Ura',
'filehist-thumb' => 'Miniadüra',
'filehist-thumbtext' => 'Miniadüra de la versión di $1',
'filehist-user' => 'Dovrat',
'filehist-dimensions' => 'Dimensión',
'filehist-comment' => 'Uget',
'imagelinks' => 'Ligamm al file',
'linkstoimage' => "{{PLURAL:$1|Quela pagina chì la gh'ha |$1 Quij paginn chì i gh'hann}} ligam al file:",
'sharedupload' => "Quel archivi chì al vegn de $1 e'l pö vess dupraa da alter pruget",
'sharedupload-desc-here' => "Quel ''file'' chì al vegn de $1 e 'l pö vess druvaa da alter pruget.
La descrizión sura la sua [$2 pagina de descrizión del file] l'è mustrada chì suta.",
'uploadnewversion-linktext' => 'Carga una versión növa de quel file chì',

# File reversion
'filerevert-intro' => "Te seet adree a bütà turna el file '''[[Media:$1|$1]]''' a la [$4 version del $2, $3].",
'filerevert-comment' => 'Uget:',
'filerevert-defaultcomment' => 'Bütada turna la versión di $2, $1',

# File deletion
'filedelete' => 'Scancela $1',
'filedelete-legend' => "Scancela 'l file",
'filedelete-intro-old' => "Te seet adree a scancelà la versión de '''[[Media:$1|$1]]''' del [$4 $2, $3].",
'filedelete-comment' => 'Reson:',
'filedelete-otherreason' => 'Alter resón/spiegazión:',
'filedelete-reason-otherlist' => 'Óltra resón',
'filedelete-reason-dropdown' => '*I sòlit resón per i scancelament
** Viulazión de copyright
** File dubi',
'filedelete-edit-reasonlist' => 'Mudifega i mutiv del scancelament',

# MIME search
'mimesearch' => 'cérca MIME',

# Unwatched pages
'unwatchedpages' => "Paginn mía tegnüü d'ögg",

# List redirects
'listredirects' => 'Listá i pagin re-indirizzaa',

# Unused templates
'unusedtemplates' => 'Templat mia druvaa',
'unusedtemplateswlh' => 'alter culegament',

# Random page
'randompage' => 'Una pagina a cas',

# Random redirect
'randomredirect' => 'Un redirect a cas',

# Statistics
'statistics' => 'Statistich',
'statistics-header-pages' => 'Statistegh di paginn',
'statistics-header-edits' => 'Statistegh di mudifegh',
'statistics-header-views' => 'Statistegh di visüalizazión',
'statistics-header-users' => 'Statistegh di druvadur',
'statistics-header-hooks' => 'Alter statistegh',
'statistics-articles' => 'Paginn de cuntegnüü',
'statistics-pages' => 'Paginn',
'statistics-pages-desc' => 'Tüt i paginn del sit, cumpres i paginn de discüssion, i redirect, e.i.v.',
'statistics-files' => 'File caregaa sü',
'statistics-edits' => "Paginn mudifegaa dal dì che l'è nassüü 'l sit de {{SITENAME}}",
'statistics-edits-average' => 'Mudifegh in média per pagina',
'statistics-users' => '[[Special:ListUsers|Druvadur]] registraa',
'statistics-users-active' => 'Druvadur ativ',
'statistics-users-active-desc' => "Druvadur che i hann faa un'azión int {{PLURAL:$1|l'ültem dì|i ültem $1 dì}}",

'disambiguations' => 'Pagin da disambiguazziún',

'doubleredirects' => 'Redirezziún dópi',

'brokenredirects' => 'Redirezziún interótt',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'nmembers' => '$1 {{PLURAL:$1|element|element}}',
'uncategorizedpages' => "Paginn ch'i gh'hann mía de categuria",
'uncategorizedcategories' => 'Categurij mía categurizaa',
'uncategorizedimages' => "''File'' ch'i gh'hann mía de categuria.",
'uncategorizedtemplates' => "Mudel ch'i gh'hann mía de categuria.",
'unusedcategories' => 'Categurij mía druvaa',
'unusedimages' => 'Imagin mia druvaa',
'wantedcategories' => 'Categurij ricercaa',
'wantedpages' => 'Pagin ricercaa',
'mostlinked' => 'Püssè ligaa a pagin',
'mostlinkedcategories' => 'Categurij cun püssee ligamm',
'mostcategories' => 'Articui cun püssee categurij',
'mostimages' => 'Püssè ligaa a imagin',
'mostrevisions' => 'Articui cun püssè revisiún',
'prefixindex' => 'Tüt i paginn cun prefiss',
'shortpages' => 'Paginn püssee cürt',
'longpages' => 'Paginn püssee lungh',
'deadendpages' => 'Pagin senza surtida',
'listusers' => 'Lista di dupradur registraa',
'listusers-editsonly' => 'Mustra dumà i dupradur cun di mudifegh',
'listusers-creationsort' => 'Cavèzza per data de creazión',
'usercreated' => 'Creaa el $1 a $2',
'newpages' => 'Paginn növ',
'ancientpages' => 'Paginn püssee vegg',
'move' => 'Sposta',
'movethispage' => 'Sposta quela pagina chì',
'pager-newer-n' => '{{PLURAL:$1|1|$1}} püssee növ',
'pager-older-n' => '{{PLURAL:$1|1|$1}} püssee vegg',

# Book sources
'booksources' => 'Surgent per i lìber',
'booksources-search-legend' => 'Cerca i fónt di liber',
'booksources-go' => 'Va',

# Special:Log
'specialloguserlabel' => 'Üteent:',
'speciallogtitlelabel' => 'Titul:',
'log' => 'Register',
'logempty' => "El log l'è vöj.",

# Special:AllPages
'allpages' => 'Tücc i pagin',
'alphaindexline' => 'de $1 a $2',
'prevpage' => 'Pagina prima ($1)',
'allpagesfrom' => 'Fàm vedè i paginn a partì da:',
'allpagesto' => 'Fàm ved i paginn fín a:',
'allarticles' => 'Tucc i artícoj',
'allpagesprev' => 'Precedent',
'allpagesnext' => 'Pròssim',
'allpagessubmit' => 'Inanz',
'allpagesprefix' => "Varda i pagin ch'i scumenza per:",

# Special:Categories
'categories' => 'Categurij',

# Special:DeletedContributions
'deletedcontributions' => 'Cuntribüziun scancelaa',
'deletedcontributions-title' => 'Cuntribüziun scancelaa',

# Special:LinkSearch
'linksearch' => 'Ligam de föra',

# Special:ListUsers
'listusersfrom' => 'Fàm vedè i dupradur a partì da:',

# Special:ActiveUsers
'activeusers-from' => 'Fàm vedè i dupradur a partì da:',

# Special:Log/newusers
'newuserlogpage' => 'Rrgister di druvat növ',

# Special:ListGroupRights
'listgrouprights' => 'Dirit del grüp di druvat',
'listgrouprights-members' => '(Lista di member)',

# E-mail user
'emailuser' => 'Manda un email a quel druvadur chì',
'emailsent' => 'Messagg spedii',
'emailsenttext' => "El messagg e-mail l'è staa spedii.",

# Watchlist
'watchlist' => "Paginn ch'a tegni d'ögg",
'mywatchlist' => "Paginn che a tegni d'ögg",
'addedwatchtext' => "La pagina \"[[:\$1]]\" l'è stada giuntada a la lista di [[Special:Watchlist|paginn da tegn d'ögg]].
I cambiament che vegnarà fai a 'sta pagina chì e a la sóa pagina de discüssion
i vegnarann segnalaa chichinscì e la pagina la se vedarà cun caràter '''grev''' ins la
[[Special:RecentChanges|lista dij cambiament recent]], giüst per metela in evidenza.
<p>Se te vörat tö via quela pagina chì dala lista dij paginn da tegn d'ögg te pòdat schiscià 'l butón \"tegn pü d'ögg\".",
'removedwatchtext' => 'La pagina "[[:$1]]" l\'è stada scancelada de la tò lista di [[Special:Watchlist|paginn sot ögg]].',
'watch' => "Tegn d'öcc",
'watchthispage' => "Tegn d'ögg quela pagina chì",
'unwatch' => "Tegn pü d'ögg",
'watchnochange' => "Nissün cambiament l'è stai faa ins i pàginn che te tegn d'ögg ind 'l períud de temp indicaa.",
'watchlist-details' => '{{PLURAL:$1|$1 pagina|$1 paginn}} tegnüü sot ögg, fö che i paginn de discüssión.',
'wlshowlast' => 'Fa vidé i ültim $1 ur $2 dì $3',
'watchlist-options' => "Upzión lista d'ussevazión",

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => "Giuntà ai pagin da ten d'ögg...",
'unwatching' => "Eliminà dai pagin da ten d'ögg...",

'enotif_newpagetext' => "Chesta-chí l'è una pàgina növa.",
'changed' => 'cambiaa',
'enotif_subject' => 'La pagina $PAGETITLE de {{SITENAME}} l\'è stada $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited' => 'Varda $1 per vedè tüt i mudifegh da la tua ültema vìsita.',
'enotif_body' => 'Cara $WATCHINGUSERNAME,

La pàgina $PAGETITLE del sit {{SITENAME}} a l\'è stada $CHANGEDORCREATED del $PAGEEDITDATE da $PAGEEDITOR, varda $PAGETITLE_URL per la version curenta.

$NEWPAGE

Sumari de la mudifega, metüü denter da l\'autur: $PAGESUMMARY $PAGEMINOREDIT

Per cuntatà l\'autur:
per e-mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ghe sarà pü mía de nutificazion in cas d\'una quaj oltra mudifega, a manch che te veet nò a visità la pàgina in questión.
De surapü, te pö mudifegà l\'impustazion de l\'avis de nutifega per quij paginn che i henn ins la lista di paginn che te tegn d\'ögg.

             \'L to sistema de nutifega da {{SITENAME}}

--
Per mudifegà l\'impustazión de la lista di paginn che te tegn d\'ögg, varda
{{canonicalurl:Special:Watchlist/edit}}

Per fà di cumünicazion de servizzi e per cercà jüt:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Scancela la pagina',
'excontent' => "'l cuntegnüü l'eva: '$1'",
'excontentauthor' => "'l cuntegnüü l'eva: '$1' (e l'ünich cuntribüdur l'eva staa '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "Inanz da vess svujaa 'l cuntegnüü l'eva: '$1'",
'historywarning' => "'''Ocio!''' La pagina che a sii adree a scancelà la gh'a una stòria cun $1 {{PLURAL:$1|revisión|revisionn}}:",
'confirmdeletetext' => "A te see lì per scancelà una pagina cun tüta la sua crunulugìa.
Per piasè, dà la cunferma che te gh'hee intenzión de andà inanz cun 'l scancelament, che te see al curent di cunseguenz, e che te see adree a fàl segónd i regulament de [[{{MediaWiki:Policy-url}}]].",
'actioncomplete' => 'Aziun cumpletada',
'deletedtext' => 'La pagina "$1" l\'è stada scancelada. Varda el $2 per una lista di ültim scancelaziun.',
'dellogpage' => 'Register di scancelament',
'deletionlog' => 'log di scancelament',
'reverted' => 'Bütada sü turna la versión de prima.',
'deletecomment' => 'Reson:',
'deleteotherreason' => 'Alter mutiv:',
'deletereasonotherlist' => 'Altra resón',
'deletereason-dropdown' => "*Mutiv cumün de scancelaziun
** Richiesta de l'aütur
** Viulaziun del copyright
** Vandalism",
'delete-edit-reasonlist' => 'Mudifega i mutiv del scancelament',

# Rollback
'rollback' => 'Rollback',
'rollbacklink' => 'Rollback',
'rollbackfailed' => 'L è mia staa pussibil purtá indré',
'alreadyrolled' => "L è mia pussibil turná indré al'ültima versiún da [[:$1]] dal [[User:$2|$2]] ([[User talk:$2|Discüssiún]]); un quaivün l á gjamò redataa o giraa indré la pagina.
L'ültima redatazziún l eva da [[User:$3|$3]] ([[User talk:$3|Discüssiún]]).",
'rollback-success' => "Nülaa i mudifegh de $1; pagina purtada indree a l'ültima versión de $2.",

# Protect
'protectlogpage' => 'Register di prutezión',
'protectedarticle' => 'l\'ha prutegiüü "[[$1]]"',
'modifiedarticleprotection' => 'A l\'è müdaa el nivel de prutezión per "[[$1]]"',
'unprotectedarticle' => 'l\'ha sblucaa "[[$1]]"',
'protect-title' => 'Prutezziún da "$1"',
'prot_1movedto2' => '[[$1]] spustaa in [[$2]]',
'protect-legend' => 'Cunferma de blocch',
'protectcomment' => 'Reson:',
'protectexpiry' => 'Scadenza:',
'protect_expiry_invalid' => 'Scadenza pü bona',
'protect_expiry_old' => 'Scadenza giamò passada',
'protect-text' => "Chì se pö vardà e müdà el nivel de prutezión de la pagina '''$1'''.",
'protect-locked-access' => "El tò cünt a l'ha minga la qualifega per pudè müdà el nivel de prutezión.
Quest chì a hinn i regulazión curent per la pagina '''$1''':",
'protect-cascadeon' => "Al mument, quela pagina chì l'è prutegiüda perchè l'è inclüsa int {{PLURAL:$1|la pagina chì suta, che la gh'ha|i paginn chì suta, ch'i gh'hann}} la prutezion a cascada. Se pö mudifegà 'l nivel de prutezion de quela pagina chì, ma una mudifega del gener la gh'avarà mia d'efet ins i impustazión ch'i deriven da la prutezión a cascada.",
'protect-default' => 'Uturiza tücc i druvat',
'protect-fallback' => 'Al ghe vör el permiss "$1"',
'protect-level-autoconfirmed' => 'Bloca i druvat növ e quij minga registraa',
'protect-level-sysop' => 'dumà per i aministradur',
'protect-summary-cascade' => 'recursiva',
'protect-expiring' => 'scadenza: $1 (UTC)',
'protect-cascade' => "Prutegg i paginn ch'i fan part de questa (prutezión recursiva)",
'protect-cantedit' => "Te pödet minga mudifegà i nivel de prutezión a quela pagina chì, per via che t'hee minga el permiss de mudifegala.",
'protect-dropdown' => '*Mutiv cumün per la prutezion
** Tròp vandalism
** Tròp spam
** Edit war
** Pagina cun parecc tràfich',
'protect-edit-reasonlist' => 'Mudifega i mutiv per la prutezion',
'protect-expiry-options' => '1 ura:1 hour,1 dì:1 day,1 semana:1 week,2 semann:2 weeks,1 mes:1 month,3 mes:3 months,6 mes:6 months,1 ann:1 year,per sémper:infinite',
'restriction-type' => 'Permiss',
'restriction-level' => 'Nivel de restrizión',
'minimum-size' => 'Misüra mìnima',
'maximum-size' => 'Misüra màssima:',
'pagesize' => '(byte)',

# Restrictions (nouns)
'restriction-edit' => 'Mudifega',

# Undelete
'undelete' => 'Varda i pagin scancelaa',
'undelete-nodiff' => "Per questa pagina gh'è nanca una revisiun precedenta.",
'undeletebtn' => 'Rimett a post',
'undeletelink' => 'Varda/büta indree',
'undeletedrevisions' => '{{PLURAL:$1|1 revision|$1 versiun}} rimetüü a post',

# Namespace form on various pages
'namespace' => 'Namespace:',
'invert' => 'Invertì la seleziòn',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribüzión del druvadur',
'contributions-title' => 'Cuntribüzión de $1',
'mycontris' => 'I mè intervent',
'contribsub2' => 'Per $1 ($2)',
'uctop' => '(ültima per la pagina)',
'month' => 'A partì del mes (e quij inanz)',
'year' => "A partì de l'ann (e quij inanz)",

'sp-contributions-newbies' => 'Fà vidè dumà i cuntribüzión di dvurat növ',
'sp-contributions-blocklog' => 'Register di bloch',
'sp-contributions-deleted' => 'Cuntribüziun scancelaa',
'sp-contributions-talk' => 'ciciarada',
'sp-contributions-userrights' => 'Gestión di dirit di druvadur',
'sp-contributions-blocked-notice' => "Per el mument quel druvadur chì l'è blucaa. L'ültima entrada int el register di bloch l'è repurtada chì de suta per riferiment:",
'sp-contributions-search' => 'Cerca i cuntribüzión',
'sp-contributions-username' => 'Adress IP o nom druvat:',
'sp-contributions-submit' => 'Ricerca',

# What links here
'whatlinkshere' => 'Pagin che se culeghen chì',
'whatlinkshere-title' => 'Paginn che menen a "$1"',
'whatlinkshere-page' => 'Pagina:',
'linkshere' => "I paginn chì de sota gh'hann di ligam che porten a '''[[:$1]]''':",
'isredirect' => 'redirezión',
'istemplate' => 'inclüsión',
'isimage' => 'ligam a una figüra',
'whatlinkshere-prev' => '{{PLURAL:$1|quel prima|$1 prima}}',
'whatlinkshere-next' => '{{PLURAL:$1|dopu|$1 dopu}}',
'whatlinkshere-links' => '← ligam',
'whatlinkshere-hideredirs' => '$1 redirezión',
'whatlinkshere-hidetrans' => '$1 inclüsión',
'whatlinkshere-hidelinks' => '$1 ligam',
'whatlinkshere-filters' => 'Filter:',

# Block/unblock
'blockip' => 'Bloca el dovrat',
'blockip-title' => "Bloca 'l druvadur",
'blockip-legend' => "Bloca 'l druvadur",
'blockiptext' => "Druvee 'l mòdul chì de suta per blucà l'acess cun dirit de scritüra a un indirizz IP specifegh o a un druvadur registraa.
El bloch gh'è de druvàl dumà per evità el vandalism e in acord cun i [[{{MediaWiki:Policy-url}}|regulament de {{SITENAME}}]].
Scrivee chì de suta 'l mutiv specifegh per el bloch (presempi, a pudii scriv i titul di paginn che i henn stat suget a vandalism).",
'ipadressorusername' => 'Indirizz IP o nom del druvdur:',
'ipbexpiry' => 'Fina al:',
'ipbreason' => 'Reson:',
'ipbreasonotherlist' => 'Alter mutiv',
'ipbreason-dropdown' => "*Mutiv püssee cumün per i blòch
** Avè caregaa di infurmazión fals
** Avè töt via del cuntegnüü dai paginn
** Avè giuntaa di ereclam a di sit da föra
** Avè giuntaa de la ratatuja int i paginn
** Cumpurtament intimidatori
** Avè druvaa püssee dun cünt in manera abüsiva
** El nom del druvàt l'è inacetabil",
'ipbcreateaccount' => 'Lassegh mia creà di alter cünt',
'ipbemailban' => "Fà in manera che quel druvàt chì 'l poda mia spedì di messagg e-mail",
'ipbenableautoblock' => "Bloca in manera utumatega l'ültim indirizz IP druvaa da 'stu druvadur chì, e qualsessìa olter indirizz IP cun al qual el cerca de fà di mudifegh.",
'ipbsubmit' => 'Blòca quel druvàt chì',
'ipbother' => 'Altra dürada:',
'ipboptions' => '2 ur:2 hours,1 dì:1 day,3 dì:3 days,1 semana:1 week,2 semann:2 weeks,1 mes:1 month,3 mes:3 months,6 mes:6 months,1 ann:1 year,infinii:infinite',
'ipbotheroption' => 'Alter',
'ipbotherreason' => 'Alter resón/spiegazión',
'ipbhidename' => "Scund 'l nom del druvat dai mudifegh e da i list.",
'ipbwatchuser' => "Tegn d'ögg i paginn duvrat e de discüssión de quel duvrat chì",
'ipb-change-block' => 'Blocà ancamò el duvrat cun quij impustazión chì',
'badipaddress' => 'Adrèss IP mia valid',
'blockipsuccesssub' => 'Blucagg bel-e faa',
'blockipsuccesstext' => "[[Special:Contributions/$1|$1]] a l'è staa blucaa.<br />
Varda [[Special:BlockList|lista di IP blucaa]] per vidè anmò i bloch.",
'ipb-edit-dropdown' => 'Resón del bloch',
'ipb-unblock-addr' => 'Desblòca $1',
'ipb-unblock' => 'Desbloca un duvrat o un adress IP',
'ipb-blocklist' => 'Vardee i blòch ativ',
'ipb-blocklist-contribs' => 'Cuntribüzión de $1',
'unblockip' => 'Desblòca quel druvàt chì',
'ipusubmit' => "Tö via 'stu bloch chì",
'unblocked' => "[[User:$1|$1]] l'è staa desblucaa",
'ipblocklist' => 'Adrèss IP e druvàt blucaa',
'infiniteblock' => 'per semper',
'expiringblock' => 'el finiss el $1 a $2',
'anononlyblock' => 'dumà i anònim',
'noautoblockblock' => 'bloch utumàtich mía ativ',
'createaccountblock' => 'creazión di cünt blucada',
'emailblock' => 'e-mail blucaa',
'blocklist-nousertalk' => 'el pö mía mudifegà la soa pagina de discüssión',
'ipblocklist-empty' => "El register di bloch l'è vöj.",
'blocklink' => 'bloca',
'unblocklink' => 'desbloca',
'change-blocklink' => 'cambia bloch',
'contribslink' => 'cuntribüzión',
'blocklogpage' => 'Log di blocch',
'blocklogentry' => "l'ha blucaa [[$1]] per un temp de $2 $3",
'blocklogtext' => "Quel chì l'è el register di bloch e desbloch di druvadur.
I indirizz IP che i henn staa blucaa utumaticament i henn mía cumpres int la lista.
Varda el [[Special:BlockList|register di IP blucaa]] per la lista de tüt i bloch uperaziunaj ativ.",
'unblocklogentry' => "l'ha desblucaa $1",
'block-log-flags-anononly' => 'dumà druvadur anònim',
'block-log-flags-nocreate' => 'blucada la creazión de cünt növ',

# Move page
'movepagetext' => "Duvraant la büeta chí-da-sota al re-numinerà una pàgina, muveent tüta la suva stòria al nomm nööf. Ul vecc títul al deventarà una pàgina da redirezziun al nööf títul. I liamm a la vegja pàgina i sarà mia cambiaa: assürévas da cuntrulá par redirezziun dopi u rumpüüt.
A sii respunsàbil da assüráss che i liamm i sigüta a puntá intúe i è süpunüü da ná.
Nutii che la pàgina la sarà '''mia''' muvüda se a gh'è gjamò una pàgina al nööf títul, a maanch che la sía vöja, una redirezziun cun nissüna stòtia d'esizziun passada. Cheest-chí al signífega ch'a pudii renuminá indrée
una pàgina intúe l'évuf renuminada via par eruur, e che vüü pudii mia surascriif una pàgina esisteent.


<b>ATENZIUN!</b>
Cheest-chí al pöö vess un canbi dràstegh e inaspetaa par una pàgina pupülara: par piasée assürévas ch'a ii capii i cunsegueenz da cheest-chí prima da ná inaanz.",
'movepagetalktext' => "La pagina de discüssión tacada a quel articul chì, la sarà spustada in manera utumatega insema a l'articul, '''asca in quij cas chì:'''
* quand che la pagina a l'è spustada intra namespace diferent
* se in del növ titul al gh'è giamò una pagina de discüssiun (minga vöja)
* el quadret de cunferma chì de sota a l'è staa deseleziónaa.
In quij cas chì, se'l var la pena, ghe sarà de spustà a man i infurmazión de la pagina de discüssión.",
'movearticle' => "Möva l'articul",
'newtitle' => 'Titul növ:',
'move-watch' => "Gionta chela pagina chí ai pàgin à tegní d'œucc.",
'movepagebtn' => 'Sposta quela pagina chì',
'pagemovedsub' => "San Martin l'è bele fat!",
'movepage-moved' => "'''\"\$1\" l'è staa muvüü a \"\$2\"'''",
'movepage-moved-redirect' => "L'è staa creaa un redirect.",
'articleexists' => "Una pagina che la se ciama cumpagn la gh'è giamò, opüra el nom che hii scernüü al va minga ben. <br />
Che 'l scerna, per piasè, un nom diferent per quel articul chì.",
'talkexists' => "'''La pagina a l'è stada spustada ben, ma'l s'è pudüü minga spustà la pagina de discüssión perchè gh'en è giamò un altra cun l'istess nom. Per piasè met insema i cuntegnüü di dò paginn a man'''",
'movedto' => 'spustaa vers:',
'movetalk' => 'Sposta anca la pagina de discüssión',
'movelogpage' => 'Register di San Martin',
'movereason' => 'Resón:',
'revertmove' => "büta indree a 'mè che l'era",
'delete_and_move' => 'Scancelá e mööf',

# Export
'export' => 'Espurtá pagin',

# Namespace 8 related
'allmessages' => 'Tücc i messacc dal sistéma',
'allmessagesdefault' => 'Test standard',
'allmessagescurrent' => 'Test curent',
'allmessagestext' => 'Chesta chí l è una lista də messácc də sistema dispunibil indal MediaWiki: namespace.',

# Thumbnails
'thumbnail-more' => 'Ingrandí',

# Special:Import
'import' => 'Impurtá di pagin',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'La tò pagina druvat',
'tooltip-pt-mytalk' => 'La tua pagina de discüssión',
'tooltip-pt-preferences' => 'I to preferenz',
'tooltip-pt-watchlist' => 'Lista di paginn che te tegnet sot ögg',
'tooltip-pt-mycontris' => 'Lista di tò cuntribüzión',
'tooltip-pt-login' => "Nünch cunsejum la registraziun, anca se l'è minga ubligatoria.",
'tooltip-pt-logout' => 'Va fö (logout)',
'tooltip-ca-talk' => 'Discüssiun revard el cuntegnüü de la pagina.',
'tooltip-ca-edit' => "Te pör mudifegà quela pagina chì. Per piasè dröva 'l butón per ved i cambiament prima de salvà.",
'tooltip-ca-addsection' => 'Scumencia una sezión növa',
'tooltip-ca-viewsource' => "Quela pagina chì a l'è pruteta, ma te pödet vidè el sò codes surgent",
'tooltip-ca-history' => 'Versión vegg de quela pagina chì',
'tooltip-ca-protect' => 'Prutegg quela pagina chì',
'tooltip-ca-unprotect' => 'Tö via la prutezión a questa pagina',
'tooltip-ca-delete' => 'Scancela questa pagina',
'tooltip-ca-move' => "Sposta 'sta pagina chì (cambiagh 'l titul)",
'tooltip-ca-watch' => 'Giunta quela pagina chì a la tò lista di rop che te tegnet sot ögg',
'tooltip-ca-unwatch' => 'Tö via quela pagina chì de la lista di paginn sot ögg',
'tooltip-search' => 'Cerca in {{SITENAME}}',
'tooltip-search-go' => "Va a una pagina che la se ciama cumpagn, semper che la gh'è",
'tooltip-search-fulltext' => 'Cerca quel test chì intra i paginn del sit',
'tooltip-p-logo' => 'Pagina principala',
'tooltip-n-mainpage' => 'Visité la pàgina principala',
'tooltip-n-mainpage-description' => 'Visita la pagina principala',
'tooltip-n-portal' => "Descrizión del pruget, 'sè ch'a pudé fà, indè che se pö truvà i rob.",
'tooltip-n-currentevents' => "Infurmazión sura a vergòt d'atüalità.",
'tooltip-n-recentchanges' => 'Lista di ültim mudifegh a la wiki',
'tooltip-n-randompage' => 'Carega una pagina a cas',
'tooltip-n-help' => 'Paginn de jüt',
'tooltip-t-whatlinkshere' => "Lista de tuti li pàgini wiki ch'i liga scià",
'tooltip-t-recentchangeslinked' => 'Lista di ültim cambiament ai paginn culegaa a questa',
'tooltip-feed-rss' => 'Feed RSS per chesta pàgina',
'tooltip-feed-atom' => 'Atom feed per quela pagina chì',
'tooltip-t-contributions' => 'Varda la lista di cuntribüzión de quel duvrat chì',
'tooltip-t-emailuser' => 'Manda una mail a quel druvat chì',
'tooltip-t-upload' => 'Carga file multimediaj',
'tooltip-t-specialpages' => 'Lista de tütt i pagin speciaal',
'tooltip-t-print' => 'Versión bona de stampà de quela pagina chì',
'tooltip-t-permalink' => 'Ligam permanent a quela versión chì de la pagina',
'tooltip-ca-nstab-main' => 'Vardà la pagina de cuntegnüü',
'tooltip-ca-nstab-user' => 'Varda la pagina del druvat',
'tooltip-ca-nstab-special' => "Questa chì a l'è una pagina speciala, se pö minga mudifegala",
'tooltip-ca-nstab-project' => 'Varda la pagina del pruget',
'tooltip-ca-nstab-image' => 'Varda la pagina del file',
'tooltip-ca-nstab-template' => 'Varda el mudel',
'tooltip-ca-nstab-category' => 'Varda la pagina de la categuria',
'tooltip-minoredit' => "Marca questa chì 'mè una mudifega piscinina",
'tooltip-save' => 'Salva i tò mudifegh',
'tooltip-preview' => 'Varda i mudifegh (semper mej fàl prima de salvà)',
'tooltip-diff' => 'Fam vidè i mudifegh che hoo faa al test.',
'tooltip-compareselectedversions' => 'Far vider li diferenzi entra li doi version selezionadi da chesta pàgina',
'tooltip-watch' => 'Giunta quela pagina chì a la lista di rop che te tegnen sot ögg',
'tooltip-rollback' => 'El "Rollback" al scancela cunt un clich i mudifigh faa a quela pagina chì de l\'ültem cuntribüdur',
'tooltip-undo' => '"Undo" al scancela questa mudifega chì e la derv la finestra de mudifega in manera de vardà prima. La te lassa giuntàgh una spiegazión de la mudifega.',

# Attribution
'siteuser' => '{{SITENAME}} ütent $1',

# Image deletion
'deletedrevision' => 'Scancelada la revision vegia de $1.',

# Browsing diffs
'previousdiff' => '← Diferenza püssee vegia',
'nextdiff' => 'Mudifega püssee növa →',

# Media information
'imagemaxsize' => 'Limitá i imagin süi pagin da descrizziún dii imagin a:',
'thumbsize' => 'Dimensiún diapusitiif:',
'file-info-size' => '$1 × $2 pixel, dimensión : $3, sort MIME: $4',
'file-nohires' => 'Nissüna resulüzión püssee granda dispunibila.',
'svg-long-desc' => "archivi in furmaa SVG, dimensión nominaj  $1 × $2 pixel, dimensión de l'archivi: $3",
'show-big-image' => 'Versión a resolüzión volta',

# Special:NewFiles
'newimages' => 'Espusizión di imàgin növ',
'ilsubmit' => 'Truvá',

# Bad image list
'bad_image_list' => "El furmaa a l'è quest chì:

Se tegnen bón dumà i list póntaa (i righ che scumincen per *).
El prim ligam de ogni riga la gh'ha de vess un ligam a un file minga desideraa.
I ligam che i vegnen dopu, in sü l'istessa riga, i vegnen cónsideraa di ecezión (che'l vör dì paginn induè che 'l file se'l pö riciamà in manera nurmala).",

# Metadata
'metadata' => 'Metadat',
'metadata-help' => "Quel file chì al gh'ha dent di infurmazión adiziunaj, che l'è prubabil che j'ha giuntaa la fotocamera o 'l scanner dupraa per fàl o digitalizàl. Se el file a l'è staa mudifegaa, un quajvün di detali i pudarien curespund pü ai mudifegh faa.",
'metadata-expand' => 'Fà vidè i detali',
'metadata-collapse' => 'Scund i detali',
'metadata-fields' => 'I camp di metadat EXIF listaa in quel messagg chì i saran mustraa in de la pagina de la figüra quand che la tabela di metadat la sarà presentada furma cürta. Per impustazión i alter camp i saran scundüü.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# External editor support
'edit-externally' => 'Redatá chest archivi cunt un prugramari da fö',
'edit-externally-help' => 'Varda [//www.mediawiki.org/wiki/Manual:External_editors i istrüzión] per avègh püssee infurmazión (in ingles).',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tücc',
'namespacesall' => 'tücc',
'monthsall' => 'tücc',

# E-mail address confirmation
'confirmemail' => "Cunferma l<nowiki>'</nowiki>''e-mail''",
'confirmemail_text' => "Prima da pudé riçeef mesacc sül tò adrèss da pòsta letrònica l è neçessari verificál.
Schiscjá ul butún che gh'è chi da sót par curfermá al tò adrèss.
Te riçevaree un mesacc cun deent un ligamm specjal; ti duvaree clicaa sül ligamm par cunfermá che l tò adrèss l è válit.",
'confirmemail_send' => 'Mandum un mesacc da cunfermazziún',
'confirmemail_sent' => 'Ul mesacc da cunfermazziún l è staa mandaa.',
'confirmemail_success' => "'L voster indirizz e-mail l'è staa cunfermaa: adess a pudii druvà la wiki.",
'confirmemail_loggedin' => "Adess 'l voster indirizz e-mail l'è staa cunfermaa",

# Auto-summaries
'autosumm-blank' => 'Pagina svujada',

# Watchlist editing tools
'watchlisttools-view' => 'Varda i mudifegh impurtant',
'watchlisttools-edit' => 'Varda e mudifega la lista di paginn che te tegnet sut ögg',
'watchlisttools-raw' => 'Mudifega la lista in furmaa test',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|ciciarade]])',

# Special:Version
'version' => 'Versiun',

# Special:FilePath
'filepath' => 'Percuurz daj archivi',

# Special:SpecialPages
'specialpages' => 'Paginn special',

);
