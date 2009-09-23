<?php
/** Lumbaart (Lumbaart)
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
 * @author Insübrich
 * @author Kemmótar
 * @author Malafaya
 * @author Remulazz
 * @author SabineCretella
 * @author Snowdog
 * @author Sprüngli
 */

$fallback = 'it';

$namespaceNames = array(
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Ciciarada',
	NS_USER             => 'Druvat',
	NS_USER_TALK        => 'Ciciarada_Druvat',
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
	'Speciale' => NS_SPECIAL,
	'Discussione' => NS_TALK,
	'Utente' => NS_USER,
	'Discussioni_utente' => NS_USER_TALK,
	'Dovrat' => NS_USER,
	'Ciciarada_Dovrat' => NS_USER_TALK,
	'Discussioni_$1' => NS_PROJECT_TALK,
	'Discussioni_file' => NS_FILE_TALK,
	'Immagine' => NS_FILE,
	'Discussioni_immagine' => NS_FILE_TALK,
	'Discussioni_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Discussioni_template' => NS_TEMPLATE_TALK,
	'Model' => NS_TEMPLATE,
	'Ciciarada_Model' => NS_TEMPLATE_TALK,
	'Aiuto' => NS_HELP,
	'Discussioni_aiuto' => NS_HELP_TALK,
	'Aida' => NS_HELP,
	'Ciciarada_Aida' => NS_HELP_TALK,
	'Categoria' => NS_CATEGORY,
	'Discussioni_categoria' => NS_CATEGORY_TALK,
	'Ciciarada_Categoria' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'RedirezionDubia' ),
	'BrokenRedirects'           => array( 'RedirezionS-cepada' ),
	'Disambiguations'           => array( 'Desambiguazion' ),
	'Userlogin'                 => array( 'VenaDenter' ),
	'Userlogout'                => array( 'VaFö' ),
	'CreateAccount'             => array( 'CreaCünt' ),
	'Preferences'               => array( 'Preferenz' ),
	'Watchlist'                 => array( 'SutOeugg' ),
	'Recentchanges'             => array( 'CambiamentRecent' ),
	'Upload'                    => array( 'CaregaSü' ),
	'Listfiles'                 => array( 'Imagin' ),
	'Newimages'                 => array( 'ImaginNöv' ),
	'Listusers'                 => array( 'Druvatt' ),
	'Listgrouprights'           => array( 'Lista da drecc di group' ),
	'Statistics'                => array( 'Statìstegh' ),
	'Randompage'                => array( 'PaginaAzardada' ),
	'Lonelypages'               => array( 'PaginnDaPerLur' ),
	'Uncategorizedpages'        => array( 'PaginnMingaCategurizaa' ),
	'Specialpages'              => array( 'PaginnSpecial' ),
	'Recentchangeslinked'       => array( 'MudifeghCulegaa' ),
	'Categories'                => array( 'Categurij' ),
	'Allmessages'               => array( 'Messagg' ),
	'Listadmins'                => array( 'ListaAministradur' ),
);

$magicWords = array(
	'img_right'             => array( '1', 'drita', 'destra', 'right' ),
	'img_left'              => array( '1', 'manzína', 'sinistra', 'left' ),
	'img_none'              => array( '1', 'nissön', 'nessuno', 'none' ),
	'sitename'              => array( '1', 'NUMSIT', 'NOMESITO', 'SITENAME' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sutulinia i ligam',
'tog-highlightbroken'         => 'Evidenzia <a href="" class="new">così</a> i ligam a paginn che esisten minga (se l\'è disativaa al vegn föra <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paràgraf: giüstifigaa',
'tog-hideminor'               => 'Scund i mudifegh men impurtant in di "cambiament recent"',
'tog-hidepatrolled'           => 'Scund i mudifegh verifegaa intra i ültem mudifegh',
'tog-newpageshidepatrolled'   => 'Scund i paginn verifegaa de la lista di paginn növ',
'tog-usenewrc'                => '"cambiament recent" migliuraa (JavaScript)',
'tog-numberheadings'          => 'Utu-nümerazión di paragraf',
'tog-showtoolbar'             => 'Fá vidé ai butún da redataziún (JavaScript)',
'tog-editondblclick'          => 'Redatá i pagin cun al dópi clich (JavaScript)',
'tog-editsection'             => 'Abilità edizion di seczion par ligam',
'tog-editsectiononrightclick' => 'Abilitá redatazziún dai sezziún cun al clic<br />
süi titul dai sezziún (JavaScript)',
'tog-rememberpassword'        => "Regòrdass la mè paròla d'urdin",
'tog-editwidth'               => "Slarga la finèstra di mudifegh fin che la impiniss tüt 'l scherm",
'tog-watchcreations'          => "Giunta i paginn ch'hoo creaa mì a la lista di paginn che tegni sot ögg",
'tog-watchdefault'            => "Gjüntá i pagin redataa in dala lista dii pagin tegnüü d'öcc",
'tog-watchmoves'              => "Giunta i paginn ch'hoo muvüü a la lista di paginn che tegni sot ögg",
'tog-watchdeletion'           => "Giunta i paginn ch'hoo scancelaa a la lista di paginn che tegni sot ögg",
'tog-minordefault'            => 'Marcá sempar tücc i redatazziún cuma "da minuur impurtanza"',
'tog-previewontop'            => "Fá vidé un'anteprima anaanz dala finèstra da redatazziún",
'tog-previewonfirst'          => "Fá vidé l'anteprima ala prima redatazziún",
'tog-nocache'                 => 'DIsativa la "cache" per i paginn',
'tog-oldsig'                  => 'Anteprima de la firma esistenta:',
'tog-fancysig'                => 'Firma semplificava (senza al ligamm utumatich)',
'tog-externaleditor'          => "Dröva semper un prugrama da redatazión estern (dumà per espert, 'l gh'ha de besogn d'impustazión speciaj ins 'l to computer)",
'tog-externaldiff'            => 'Druvá sempar un "diff" estèrnu',
'tog-watchlisthideown'        => "Sconda i me mudifich dai pagin che a ten d'ögg",
'tog-watchlisthidebots'       => "Sconda i mudifich di bot da i pagin che a ten d'ögg",
'tog-showhiddencats'          => 'Fà vidè i categurij scundüü',

'underline-always'  => 'Semper',
'underline-never'   => 'Mai',
'underline-default' => 'Mantegn i impustazión standard del browser',

# Font style option in Special:Preferences
'editfont-style'     => "Stil del font de l'area de mudifega:",
'editfont-default'   => 'Browser de default',
'editfont-monospace' => 'Font mono-spaziaa',
'editfont-sansserif' => 'Font sans-serif',

# Dates
'sunday'        => 'dumeniga',
'monday'        => 'lündesdí',
'wednesday'     => 'mercurdí',
'thursday'      => 'giuedí',
'friday'        => 'venerdí',
'saturday'      => 'sábat',
'sun'           => 'Dom:',
'mon'           => 'Lün',
'tue'           => 'Mar',
'wed'           => 'Ven',
'thu'           => 'Giu',
'fri'           => 'Ven',
'sat'           => 'Sab',
'january'       => 'ginee',
'february'      => 'febraar',
'march'         => 'maarz',
'april'         => 'avriil',
'may_long'      => 'macc',
'june'          => 'gjügn',
'july'          => 'lüi',
'august'        => 'avóst',
'september'     => 'setembər',
'october'       => 'Utuber',
'november'      => 'nuvembər',
'december'      => 'dicember',
'january-gen'   => 'Giner',
'february-gen'  => 'Fevrer',
'march-gen'     => 'Marz',
'april-gen'     => 'Avril',
'may-gen'       => 'Mag',
'june-gen'      => 'Giugn',
'july-gen'      => 'Luj',
'august-gen'    => 'Aoust',
'september-gen' => 'Setember',
'october-gen'   => 'Otober',
'november-gen'  => 'November',
'december-gen'  => 'Dizember',
'jan'           => 'Gen',
'feb'           => 'Feb',
'mar'           => 'mrz',
'apr'           => 'avr',
'may'           => 'Mag',
'jun'           => 'Giü',
'jul'           => 'Lüi',
'aug'           => 'Agu',
'sep'           => 'Set',
'oct'           => 'utu',
'nov'           => 'nuv',
'dec'           => 'Dic',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Categuria|Categurij}}',
'category_header'          => 'Artìcuj int la categuria "$1"',
'subcategories'            => 'Suta-categurij',
'category-media-header'    => 'File int la categuria "$1"',
'category-empty'           => "''Per 'l mument quela categuria chì la gh'ha denter né de paginn ne d'archivi mültimedia''",
'hidden-categories'        => '{{PLURAL:$1|Categuria scundüda|Categurij scundüü}}',
'hidden-category-category' => 'Categurij scundüü',

'about'         => 'A pruposit də',
'newwindow'     => "(sa derviss in un'óltra finèstra)",
'cancel'        => 'Lassa perd',
'moredotdotdot' => 'Püssee',
'mypage'        => 'La mè pagina',
'mytalk'        => 'i mè discüssiun',
'anontalk'      => 'Ciciarad per quel adress IP chì',
'navigation'    => 'Navegazión',

# Cologne Blue skin
'qbfind'         => 'Tröa',
'qbbrowse'       => 'Sföja',
'qbedit'         => 'Redatá',
'qbpageoptions'  => 'Opzión de la pagina',
'qbpageinfo'     => 'Infurmazión revard a la pagina',
'qbspecialpages' => 'Paginn special',
'faq'            => 'FAQ - Fera Ai Question',

# Vector skin
'vector-action-delete'      => 'Scancela',
'vector-action-move'        => 'Sposta',
'vector-action-protect'     => 'Prutegg',
'vector-action-undelete'    => 'Recüpera',
'vector-namespace-category' => 'Categuria',
'vector-namespace-media'    => 'File mültimedial',
'vector-namespace-project'  => 'Pagina de servizi',
'vector-namespace-user'     => 'Pagina da dovrée',
'vector-view-create'        => 'Crea',
'vector-view-edit'          => 'Mudifega',
'vector-view-view'          => 'Legg',
'vector-view-viewsource'    => 'Varda el codes',
'actions'                   => 'Azión',
'namespaces'                => 'Namespace',
'variants'                  => 'Variant',

# Metadata in edit box
'metadata_help' => 'Metadat:',

'errorpagetitle'    => 'Erur',
'returnto'          => 'Turna indré a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Pàgin da jütt',
'search'            => 'Cerca',
'searchbutton'      => 'Cerca',
'go'                => 'Innanz',
'searcharticle'     => 'Và',
'history'           => 'Crunulugia de la pagina',
'history_short'     => 'Crunulugìa',
'info_short'        => 'Infurmazión',
'printableversion'  => 'Versiun də stampà',
'permalink'         => 'Culegament permanent',
'print'             => 'Stampa',
'edit'              => 'Mudifega',
'create'            => 'Crea',
'editthispage'      => 'Mudifega quela pagina chi',
'create-this-page'  => 'Crea quela pagina chi',
'delete'            => 'Scancela',
'deletethispage'    => 'Scancela quela pagina chì',
'undelete_short'    => 'Rimet a post {{PLURAL:$1|1 mudifica|$1 mudifigh}}',
'protect'           => 'Bloca',
'protect_change'    => 'cambia',
'protectthispage'   => 'Prutegg quela pagina chì',
'unprotect'         => 'Desbloca',
'unprotectthispage' => 'Tö via la pruteziun',
'newpage'           => 'Pagina növa',
'talkpage'          => 'Discüssión',
'talkpagelinktext'  => 'Ciciarada',
'specialpage'       => 'Pagina speciala',
'personaltools'     => 'Istrüment persunaj',
'postcomment'       => 'Sezión növa',
'articlepage'       => "Varda l'articul",
'talk'              => 'Discüssión',
'views'             => 'Visid',
'toolbox'           => 'Arnes',
'userpage'          => 'Vidè la pàgina del dovrat',
'projectpage'       => 'Varda la pagina de servizzi',
'imagepage'         => 'Varda la pagina del file',
'mediawikipage'     => 'Mustra el messagg',
'templatepage'      => 'Mustra la bueta',
'viewhelppage'      => 'Fà vidè la pagina de jüt',
'categorypage'      => 'Fà vidè la categuria',
'viewtalkpage'      => 'Varda i discüssiun',
'otherlanguages'    => 'Alter lenguv',
'redirectedfrom'    => '(Redirezión de $1)',
'redirectpagesub'   => 'Pagina de redirezión',
'lastmodifiedat'    => "Quela pagina chì l'è stada mudifegada l'ültima völta del $1, a $2.",
'viewcount'         => "Quela pagina chì a l'è stada legiüda {{PLURAL:$1|una völta|$1 völta}}.",
'protectedpage'     => 'Pagina prutegiüda',
'jumpto'            => 'Va a:',
'jumptonavigation'  => 'Navigazión',
'jumptosearch'      => 'cerca',
'view-pool-error'   => "Ne rincress, ma i server a hinn bej caregaa al mument.
Trop drovat a hinn 'dree pruvà a vardà quela pagina chì.
Per piasè, specia un mument prima de pruà a vardà anmò quela pagina chì.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A prupòsit de {{SITENAME}}',
'aboutpage'            => 'Pruget:A pruposit',
'copyright'            => "El cuntegnüü a l'è dispunibil sota a una licenza $1.",
'copyrightpage'        => "{{ns:project}}:Dirit d'autur",
'currentevents'        => 'Atüalitaa',
'currentevents-url'    => 'Project:Aveniment Recent',
'disclaimers'          => 'Disclaimers',
'disclaimerpage'       => 'Project:Avertenz generaj',
'edithelp'             => 'Manual de spiegazión',
'edithelppage'         => 'Help:Scriv un articul',
'helppage'             => 'Help:Contegnüü',
'mainpage'             => 'Pagina principala',
'mainpage-description' => 'Pagina principala',
'policy-url'           => 'Project:Policy',
'portal'               => 'Purtal de la cumünità',
'portal-url'           => 'Project:Purtal de la cumünità',
'privacy'              => "Pulitega de la ''privacy''",
'privacypage'          => 'Project:Infurmazión ins la privacy',

'badaccess'        => 'Permiss sbajaa',
'badaccess-group0' => "Te gh'è mía 'l permiss per tirà inanz cun 'sta uperazión chì.",
'badaccess-groups' => "Quela funzión chì l'è reservada ai druvat che i henn in {{PLURAL:$2|del grüp|vün di grüp chì suta}}: $1.",

'versionrequired'     => 'Al ghe va per forza la versión $1 de MediaWiki',
'versionrequiredtext' => 'Per duprà quela pagina chì la ghe va la versión $1 del prugrama MediaWiki. Varda [[Special:Version]]',

'ok'                      => 'Va ben',
'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Utegnüü de "$1"',
'youhavenewmessages'      => "A gh'hii di $1 ($2).",
'newmessageslink'         => 'messacc nöf',
'newmessagesdifflink'     => 'diferenza cun la versión de prima',
'youhavenewmessagesmulti' => "Te gh'hee di messagg növ ins'el $1",
'editsection'             => 'mudifega',
'editold'                 => 'mudifega',
'viewsourceold'           => 'fà vidè el codes surgent',
'editlink'                => 'mudifega',
'viewsourcelink'          => 'fà vidè el codes surgent',
'editsectionhint'         => 'Mudifega la sezión $1',
'toc'                     => 'Cuntegnüü',
'showtoc'                 => 'fà vidè',
'hidetoc'                 => 'scund',
'thisisdeleted'           => 'Varda o rimet a post $1?',
'viewdeleted'             => 'Te vöret vidè $1?',
'restorelink'             => '{{PLURAL:$1|1 mudifega scancelada|$1 mudifegh scancelaa}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Mudalità de sotascrizión del feed minga valida',
'feed-unavailable'        => "Gh'en è minga de feed",
'site-rss-feed'           => 'Feed RSS de $1',
'site-atom-feed'          => 'Feed Atom de $1',
'page-rss-feed'           => 'Feed RSS per "$1"',
'page-atom-feed'          => 'Feed Atom per "$1"',
'red-link-title'          => "$1 (la pagina la gh'è minga)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Articul',
'nstab-user'      => 'Pagina persunala',
'nstab-media'     => 'Pagina multimediala',
'nstab-special'   => 'Pagina speciala',
'nstab-project'   => 'Pagina de servizi',
'nstab-image'     => 'Figüra',
'nstab-mediawiki' => 'Messagg',
'nstab-template'  => 'Bueta',
'nstab-help'      => 'Ajüt',
'nstab-category'  => 'Categuria',

# Main script and global functions
'nosuchaction'      => 'Uperaziun minga recugnussüda',
'nosuchactiontext'  => "L'uperaziun che t'hee ciamaa in del ligam URL a l'è minga recugnussüda.<br />
Pö vess che t'hee batüü mal l'URL, o che seet andaa adree a un ligam minga bun.<br />
Quest chì al pudaria anca indicà un bug dent in del software dupraa de {{SITENAME}}.",
'nosuchspecialpage' => "La gh'è minga una pagina pagina special tan 'me quela che t'hee ciamaa",
'nospecialpagetext' => "<span style="font-size:larger">'''T'hee ciamaa una pagina speciala minga valida.'''</span>

Una lista di paginn special la se pö truà in de la [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'               => 'Erur',
'databaseerror'       => 'Erur in del database',
'readonly'            => 'Database blucaa',
'missingarticle-rev'  => '(revision#: $1)',
'missingarticle-diff' => '(Diff: $1, $2)',
'internalerror'       => 'Erur in del sistema',
'internalerror_info'  => 'Erur intern: $1',
'badtitle'            => 'Títul mía bun',
'viewsource'          => 'Còdas surgent',
'viewsourcefor'       => 'de $1',
'protectedpagetext'   => "Cula pagina chi l'è stata blucà per impedinn la mudifica.",
'viewsourcetext'      => "L'è pussibil vèd e cupià el codes surgent de cula pagina chí:",
'editinginterface'    => "'''Attenzion''': el testo de quella pagina chì el fà part de l'interfacia utent del sitt. Tutt i modifigh che te fet se vedaran subit su i messagg visualizzaa per tutt i utent.",

# Login and logout pages
'logouttext'              => "'''Adess a seis descunetacc.'''<br />
A podé tirar innanz a dovrar la {{SITENAME}} in manera anònima, a podé
sa cunèta amò cont l'istess o un olt nomm. Tegné cunt che di
pagini i podressa vess fadi vider compagn che a saressov amò conetacc, fin coura che
a scancelé mia la memòria cava dal vost bigat.",
'welcomecreation'         => "== Benvegnüü, $1! ==
'L to cünt l'è staa pruntaa. Desmenteghet mía de mudifegà i to [[Special:Preferences|preferenz de {{SITENAME}}]].",
'yourname'                => 'El to suranóm:',
'yourpassword'            => "Parola d'urdin",
'yourpasswordagain'       => "Mett dent ammò la parola d'urdin",
'remembermypassword'      => "Regordass la mè parola d'urdin",
'nav-login-createaccount' => 'Vena denter / Crea un cünt',
'loginprompt'             => 'Par cunett a {{SITENAME}}, a duvii abilitá i galet.',
'userlogin'               => 'Vegní denter',
'logout'                  => 'Va fö',
'userlogout'              => 'Và fö',
'notloggedin'             => 'Te seet minga dent in del sistema',
'nologin'                 => "A gh'hiiv anmò da registrav? $1.",
'nologinlink'             => 'Creé un cünt!',
'createaccount'           => 'Creá un cünt',
'gotaccount'              => "Gh'hee-t giamò un cünt? $1.",
'gotaccountlink'          => 'Va dent in del sistema',
'createaccountmail'       => 'per indirizz e-mail',
'badretype'               => "I password che t'hee miss a hinn diferent.",
'userexists'              => "El nom de duvrat che t'hee miss dent a l'è giamò dupraa.
Per piasè, scerniss un alter suranom.",
'loginerror'              => "Erur in de l'andà dent in del sistema.",
'nocookiesnew'            => "El cünt a l'è staa creaa, ma t'hee minga pudüü andà dent in del sistema.
{{SITENAME}} al dupra i cookies per fà andà i duvrat in del sistema.
Tì te gh'hee i cookies disabilitaa.
Per piasè, abilita i cookies e pröa anmò a andà dent cunt el tò nom e la password.",
'noname'                  => "Vüü avii mía specificaa un nomm d'üsüari valévul.",
'loginsuccesstitle'       => "La cunessiun l'è scumenzada cun sücess.",
'loginsuccess'            => 'Al é connectaa a {{SITENAME}} compagn "$1".',
'nosuchusershort'         => "Ghe n'è mia d'ütent cun el nom de \"<nowiki>\$1</nowiki>\". Ch'el cuntrola se l'ha scrivüü giüst.",
'nouserspecified'         => "Te gh'heet da specificà un nom del druvatt.",
'wrongpassword'           => "La ciav che t'hee metüü dreent l'è no giüsta. Pröva turna per piasè.",
'wrongpasswordempty'      => "T'hee no metüü drent la parola ciav. Pröva turna per piasè.",
'mailmypassword'          => 'Spedissem una password növa per e-mail',
'passwordremindertext'    => "Un Quajdün (prubabilment ti, cun l'indiriz IP \$1) l'ha ciamaa da mandagh 'na ciav növa per andà dreent int el sistema de {{SITENAME}} (\$4).
La ciav per l'ütent \"\$2\" adess l'è \"\$3\".
Sariss mej andà drent int el sit almanch una völta prima de cambià la ciav.

Se te no staa ti a ciamà 'sta ciav chì, o magara t'hee truaa la ciav vegia e te vör pü cambiala, te pör ignurà 'stu messag chì e 'ndà inanz a druà la ciav vegia.",
'passwordsent'            => "Una parola ciav bele növa l'è staa spedii a l'indiriz e-mail registra da l'ütent \"\$1\".
Per piasè, ve drent anmò dop che te l'ricevüü.",
'emailauthenticated'      => 'Ul tò adrèss e-mail l è staa verificaa: $1.',
'emailnotauthenticated'   => 'Ul tò adrèss da pòsta letronica l è mia staa gnamò verificaa. Nissün mesacc al saraa mandaa par i servizzi che segütan.',
'accountcreated'          => 'Cunt bell-e-cread',

# Password reset dialog
'oldpassword' => "Paròla d'urdin végja:",
'newpassword' => "Paròla d'urdin növa:",
'retypenew'   => "Scriv ancamò la paròla d'urdin növa:",

# Edit pages
'summary'              => 'Argument de la mudifica:',
'minoredit'            => "Chesta chi l'è una mudifica da impurtanza minuur",
'watchthis'            => "Tegn d'öcc quela pagina chì",
'savearticle'          => 'Salva',
'preview'              => 'Varda prima de salvà la pagina',
'showpreview'          => 'Famm vedè prima',
'showdiff'             => 'Famm vedè i cambiament',
'anoneditwarning'      => 'Tì te set minga entraa. In de la crunulugia de la pagina se vedarà el tò IP.',
'accmailtext'          => 'La parola d\'urdin per "$1" l\'è stada mandada a $2.',
'anontalkpagetext'     => "----''Chesta chí a l'é la pagina da ciciarada d'un usuari che l'ha ammò minga registraa un cunt, o ascí ch'al vœur minga dovràl; donca, el pò vess identificaa domà cont el sò IP, ch'el pœul vess compartii con fiss dovrat diferent. Se al é un dovrat anònim e a l'ha vist un quai messagg ch'al ga par ch'al gh'a nagòt à vidé con lu, ch'al prœuva a [[Special:UserLogin|creà el sò cunt]].''",
'noarticletext'        => "Per 'l mument quela pagina chì l'è vöja. Te pòdet [[Special:Search/{{PAGENAME}}|cercà quel articul chì]] int i alter paginn, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} cercà int i register imparentaa], o sedenò [{{fullurl:{{FULLPAGENAME}}|action=edit}} mudifichè 'sta pagina chì adess-adess].",
'clearyourcache'       => "'''Nòta:''' dòpu che avii salvaa, pudaría véss neçessari de scancelá la memòria \"cache\" dal vòst prugráma də navigazziún in reet par vidé i mudifich faa. '''Mozilla / Firefox / Safari:''' tegní schiscjaa al butún ''Shift'' intaant che sə clica ''Reload'', upüür schiscjá ''Ctrl-Shift-R'' (''Cmd-Shift-R'' sül Apple Mac); '''IE:''' schiscjá ''Ctrl'' intaant che sə clica ''Refresh'', upüür schiscjá ''Ctrl-F5''; '''Konqueror:''': semplicemeent clicá al butún ''Reload'', upüür schiscjá ''F5''; '''Opera''' i üteent pudarían vech büsögn da scancelá cumpletameent la memòria \"cache\" in ''Tools&rarr;Preferences''.",
'previewnote'          => "''''''Atenziun'''! Questa pagina la serviss dumà de vardà. I cambiament hinn minga staa salvaa.'''",
'editing'              => 'Mudifega de $1',
'editingcomment'       => 'Redataant $1 (cumentari)',
'yourtext'             => 'El tò test',
'yourdiff'             => 'Diferenz',
'longpagewarning'      => "'''Feegh da ment''': Quela pagina chì l'è longa $1 kilobyte; gh'è di browser ch'i pudarissen vegh di fastidi a mudifegà paginn ch'i riven arent o ch'i gh'hann püssee de 32kb. Per piasè vardee se l'è pussibil fà giò la pagina in tuchet püssee piscinin.",
'protectedpagewarning' => "'''ATENZIÚN: chésta pagina l è staja blucava in manéra che dumá i üteent cunt i privilegi də sysop a pòdan mudificala.'''",
'templatesused'        => 'Buete duvrade in chesta pàgina - Buett duvraat in chesta pàgina:',
'template-protected'   => '(prutegiüü)',

# History pages
'next'       => 'pròssim',
'last'       => 'ültima',
'histlegend' => "Selezion di diferenz: seleziuná i balitt di version de cumpará e pö schisciá ''enter'' upüra al buton in scima ala tabèlina.<br />
Spiegazzion di símbul: (cur) = diferenza cun la version de adess, (ültima) = diferenza cun l'ültima version, M = cambiament d'impurtanza minur.",
'histfirst'  => 'Püssee vecc',
'histlast'   => 'Püssee receent',

# Diffs
'compareselectedversions' => 'Compara i version catad fœu',

# Search results
'searchresults'                    => 'Risültaa de la recerca.',
'noexactmatch'                     => "'''La pagina \"\$1\" la esista no.''' L'è pussibil [[:\$1|creala adèss]].",
'noexactmatch-nocreate'            => "'''La pagina cun el titul \"\$1\" la esista no.'''",
'toomanymatches'                   => "Gh'è tropi curispundens. Mudifichè la richiesta.",
'prevn'                            => 'precedent {{PLURAL:$1|$1}}',
'nextn'                            => 'pròssim {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Vidé ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-new'                   => "'''Trà in pee la pagina \"[[:\$1]]\" ins quel sit chì!'''",
'searchhelp-url'                   => 'Help:Contegnüü',
'searchprofile-articles'           => 'Paginn de cuntegnüü',
'searchprofile-project'            => 'Paginn de jüt e de pruget',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Tüt',
'searchprofile-advanced'           => 'Avanzaa',
'searchprofile-articles-tooltip'   => 'Cerca in $1',
'searchprofile-project-tooltip'    => 'Cerca in $1',
'searchprofile-everything-tooltip' => 'Cerca depertüt (anca int i paginn de discüssion)',
'searchprofile-advanced-tooltip'   => 'Cerca int i namespace persunalizaa',
'search-result-size'               => '$1 ({{PLURAL:$2|1 parola|$2 paroll}})',
'search-suggest'                   => 'Vurivet dì: $1',
'powersearch'                      => 'Truvá',
'powersearch-legend'               => 'Recerca avanzada',

# Preferences page
'preferences'        => 'Preferenz',
'mypreferences'      => 'i mè preferenz',
'changepassword'     => "Mudifega la paròla d'urdin",
'prefs-skin'         => "Aspett de l'interfacia",
'prefs-math'         => 'Matem',
'datedefault'        => 'Nissüna preferenza',
'prefs-datetime'     => 'Data e urari',
'prefs-personal'     => 'Carateristich dal druvat',
'prefs-rc'           => 'Cambiament recent',
'prefs-misc'         => 'Vari',
'saveprefs'          => 'Tegn i mudifech',
'resetprefs'         => 'Trá via i mudifech',
'prefs-editing'      => 'Mudifich',
'rows'               => 'Riich:',
'columns'            => 'Culònn:',
'searchresultshead'  => 'Cerca',
'resultsperpage'     => 'Resültaa pər pagina:',
'contextlines'       => 'Riich pər resültaa:',
'contextchars'       => 'Cuntèst pər riga:',
'recentchangescount' => 'Titui in di "cambiameent reçeent":',
'savedprefs'         => 'I preferenz hinn stai salvaa.',
'timezonelegend'     => 'Lucalitaa',
'localtime'          => 'Urari lucaal',
'timezoneoffset'     => 'Diferenza¹',
'servertime'         => 'Urari dal sèrver',
'guesstimezone'      => 'Catá l urari dal sèrver',
'allowemail'         => 'Permètt ai altar üteent də cuntatamm par email',
'defaultns'          => 'Tröva sempar in di caamp:',
'prefs-files'        => 'Archivi',
'youremail'          => 'E-mail',
'username'           => 'Nom dal dovrée',
'yourrealname'       => 'Nomm:',
'yourlanguage'       => 'Lengua:',
'yournick'           => 'Suranomm:',
'email'              => 'Indirizz de pòsta elettrònica.',
'prefs-help-email'   => "L'e-mail a l'è mia obligatòri, però al permet da mandàv una ciav noeva in cas che ve la desmenteghé. A podé apó scernì da lassà entrà i alter dovrat in contat con violter senza da busogn da svelà la vosta identità.",

# User rights
'userrights-lookup-user'   => 'Gestion di group da dovracc',
'userrights-user-editname' => 'Butée dent un nom da dovrat',
'editusergroup'            => 'Edita i group da dovrée',
'userrights-editusergroup' => 'Edita i group da dovrat',
'saveusergroups'           => 'Salvaguarda i group da dovracc',
'userrights-groupsmember'  => 'Mémber da:',
'userrights-reason'        => 'Reson da la modifiazion:',
'userrights-no-interwiki'  => "A l'ha mia la permession par canvià i dercc à di dovracc d'oltre wiki.",
'userrights-nodatabase'    => "La base dat $1 a gh'é mia, o pura a l'é mia locala.",
'userrights-nologin'       => "Al gh'a da [[Special:UserLogin|rintrà ent el sistema]] con un cunt d'administrator par podé dà di drecc ai dovracc.",
'userrights-notallowed'    => "A l'ha mia li permission par podé dà di drecc ai dovracc.",

# Groups
'group-user' => 'Dovracc',

'group-user-member' => 'Dovratt',

'grouppage-user' => '{{ns:project}}:Dovracc',

# Rights
'right-edit'          => 'Edita pàgini',
'right-createaccount' => 'Crea cünt de dovratt bej-e növ',

# Recent changes
'recentchanges'                    => 'Cambiament recent',
'recentchangestext'                => "In quela pagina chì a gh'è i cambiament püssee recent al cuntegnüü del sit.",
'recentchanges-feed-description'   => "Quel feed chì 'l mustra i mudifegh püssee recent ai cuntegnüü de la wiki.",
'recentchanges-label-legend'       => 'Legenda: $1.',
'recentchanges-legend-newpage'     => '$1 - pagina növa',
'recentchanges-label-newpage'      => "Quela mudifega chì l'ha creaa una pagina növa",
'recentchanges-legend-minor'       => '$1 - mudifega minur',
'recentchanges-label-minor'        => "Quela chì l'è una mudifega piscinina.",
'recentchanges-legend-bot'         => "$1 - mudifega d'un bot",
'recentchanges-label-bot'          => "Quela mudifega chì l'ha fada un bot",
'recentchanges-legend-unpatrolled' => '$1 - mudifega mia verificada',
'recentchanges-label-unpatrolled'  => "Quela mudifega chì a l'è stada mimga anmò verificada.",
'rcnote'                           => "De sota gh'è {{PLURAL:$1|è '''1''' mudifega|a hinn i ültim '''$1''' mudifegh}} in di ültim {{PLURAL:$2|dì|'''$2''' dì}}, a partì de ur $5 del $4.",
'rcnotefrom'                       => "Chì de sota gh'è la lista di mudifegh de <b>$2</b> (fina a <b>$1</b>).",
'rclistfrom'                       => 'Fà vidè i növ cambiament a partì de $1',
'rcshowhideminor'                  => '$1 mudifegh minur',
'rcshowhidebots'                   => '$1 i bot',
'rcshowhideliu'                    => '$1 üteent cunèss',
'rcshowhideanons'                  => '$1 dovrat anònim',
'rcshowhidemine'                   => '$1 i mè mudifich',
'rclinks'                          => 'Fá vidé i ültim $1 cambiameent indi ültim $2 dí<br />$3',
'diff'                             => 'dif',
'hist'                             => 'stòria',
'hide'                             => 'Scund',
'show'                             => 'Famm vedè',

# Recent changes linked
'recentchangeslinked'         => 'Cambiament culegaa',
'recentchangeslinked-feed'    => 'Cambiament culegaa',
'recentchangeslinked-toolbox' => 'Cambiament culegaa',
'recentchangeslinked-title'   => 'Mudifegh ligaa a "$1"',

# Upload
'upload'            => 'Carga sü un file',
'uploadbtn'         => 'Carga sü',
'uploadnologin'     => 'Minga cuness',
'uploadlogpage'     => 'Log di file caregaa',
'filedesc'          => 'Sumari',
'fileuploadsummary' => 'Sumari:',
'ignorewarnings'    => 'Ignora tücc i avertimeent',
'largefileserver'   => 'Chel archivi-chí al è püssee graant che ul serviduur al sía cunfigüraa da permett.',
'sourcefilename'    => "Nomm da l'archivi surgeent:",
'destfilename'      => "Nomm da l'archivi da destinazziun:",

# Special:ListFiles
'imgfile'        => 'archivi',
'listfiles'      => 'Listá i imàgin',
'listfiles_date' => 'Dada',
'listfiles_name' => 'Nomm',
'listfiles_user' => 'Dovratt',

# File description page
'filehist-revert' => "Butar torna 'me ch'al era",
'filehist-user'   => 'Dovrat',
'imagelinks'      => 'Ligamm al file',

# MIME search
'mimesearch' => 'cérca MIME',

# Unwatched pages
'unwatchedpages' => "Pagin mia tegnüü d'öcc",

# List redirects
'listredirects' => 'Listá i pagin re-indirizzaa',

# Unused templates
'unusedtemplates' => 'Templat mia druvaa',

# Random page
'randompage' => 'Página a caas',

# Statistics
'statistics'              => 'Statistich',
'statistics-header-users' => 'Statistich di utent',
'statistics-files'        => 'File caregaa sü',

'disambiguations' => 'Pagin da disambiguazziún',

'doubleredirects' => 'Redirezziún dópi',

'brokenredirects' => 'Redirezziún interótt',

# Miscellaneous special pages
'uncategorizedpages'      => 'Pagin mia categurizzaa',
'uncategorizedcategories' => 'Categurii mia categurizzaa',
'unusedcategories'        => 'Categurii mia druvaa',
'unusedimages'            => 'Imagin mia druvaa',
'wantedcategories'        => 'Categurii ricercaa',
'wantedpages'             => 'Pagin ricercaa',
'mostlinked'              => 'Püssè ligaa a pagin',
'mostlinkedcategories'    => 'Püssè ligaa ai categurii',
'mostcategories'          => 'Articui cun püssè categurii',
'mostimages'              => 'Püssè ligaa a imagin',
'mostrevisions'           => 'Articui cun püssè revisiún',
'prefixindex'             => 'Tüt i paginn cun prefiss',
'shortpages'              => 'Pagin püssee curt',
'longpages'               => 'Pagin püssè luunch',
'deadendpages'            => 'Pagin senza surtida',
'listusers'               => 'Listá i üteent registraa',
'newpages'                => 'Pagin nööf',
'ancientpages'            => 'Pagin püssee vecc',

# Book sources
'booksources' => 'Surgeent librari',

# Special:Log
'specialloguserlabel'  => 'Üteent:',
'speciallogtitlelabel' => 'Titul:',
'logempty'             => "El log l'è vöj.",

# Special:AllPages
'allpages'       => 'Tücc i pagin',
'allpagesfrom'   => 'Famm vedè i pagin a partì de:',
'allpagesto'     => 'Fàm ved i paginn fín a:',
'allarticles'    => 'Tucc i artícoj',
'allpagesprev'   => 'Precedent',
'allpagesnext'   => 'Pròssim',
'allpagessubmit' => 'Innanz',
'allpagesprefix' => "Varda i pagin ch'i scumenza per:",

# Special:Categories
'categories' => 'Categurii',

# Special:DeletedContributions
'deletedcontributions'       => 'Cuntribüziun scancelaa',
'deletedcontributions-title' => 'Cuntribüziun scancelaa',

# E-mail user
'emailuser' => 'Manda un email al duvrátt',

# Watchlist
'watchlist'        => 'In usservazziun',
'mywatchlist'      => "Paginn che a tegni d'ögg",
'addedwatch'       => 'Pagina giuntada a la lista di paginn sot ögg',
'addedwatchtext'   => "La pagina \"[[:\$1]]\" l'è stada giuntada a la lista di [[Special:Watchlist|paginn da tegn d'ögg]].
I cambiament che vegnarà fai a 'sta pagina chì e a la sóa pagina de discüssion
i vegnarann segnalaa chichinscì e la pagina la se vedarà cun caràter '''grev''' ins la
[[Special:RecentChanges|lista dij cambiament recent]], giüst per metela in evidenza.
<p>Se te vörat tö via quela pagina chì dala lista dij paginn da tegn d'ögg te pòdat schiscià 'l butón \"tegn pü d'ögg\".",
'removedwatch'     => 'Scancelaa dala lista di usservazziún.',
'removedwatchtext' => 'La pagina "[[:$1]]" l\'è staja scancelava dala tóa lista da usservazziún.',
'watch'            => "Tegn d'öcc",
'watchthispage'    => "Tegn d'öcc questa pagina",
'unwatch'          => "Tegn pü d'öcc",
'watchnochange'    => "Nissün cambiameent l è stai faa süi articui/págin che ti tegnat d'öcc indal períut da teemp selezziunaa.",
'wlshowlast'       => 'Fa vidé i ültim $1 uur $2 dí $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => "Giuntà ai pagin da ten d'ögg...",
'unwatching' => "Eliminà dai pagin da ten d'ögg...",

'enotif_newpagetext' => "Chesta-chí l'è una pàgina növa.",
'changed'            => 'cambiaa',

# Delete
'deletepage'            => 'Scancela la pagina',
'historywarning'        => "Atenziún: La pagina che a sii dré a scancelá la gh'a una stòria:",
'actioncomplete'        => 'Aziun cumpletada',
'deletedtext'           => 'La pagina "<nowiki>$1</nowiki>" l\'è stada scancelada. Varda el $2 per una lista di ültim scancelaziun.',
'deletedarticle'        => 'l\'ha scancelaa "[[$1]]"',
'dellogpage'            => 'Register di scancelament',
'deletionlog'           => 'log di scancelament',
'deletecomment'         => 'Mutiif dala scancelazziun',
'deleteotherreason'     => 'Alter mutiv:',
'deletereason-dropdown' => "*Mutiv cumün de scancelaziun
** Richiesta de l'aütur
** Viulaziun del copyright
** Vandalism",

# Rollback
'rollback'         => 'Rollback',
'rollbacklink'     => 'Rollback',
'rollbackfailed'   => 'L è mia staa pussibil purtá indré',
'alreadyrolled'    => "L è mia pussibil turná indré al'ültima versiún da [[:$1]] dal [[User:$2|$2]] ([[User talk:$2|Discüssiún]]); un quaivün l á gjamò redataa o giraa indré la pagina.
L'ültima redatazziún l eva da [[User:$3|$3]] ([[User talk:$3|Discüssiún]]).",
'rollback-success' => "Nülaa i mudifegh de $1; pagina purtada indree a l'ültima versión de $2.",

# Protect
'unprotectedarticle' => 'l\'ha sblucaa "[[$1]]"',
'protect-title'      => 'Prutezziún da "$1"',
'prot_1movedto2'     => '[[$1]] spustaa in [[$2]]',
'protect-legend'     => 'Cunferma de blocch',
'protectcomment'     => 'Spiega parchè ti vörat blucá la pagina',

# Undelete
'undelete'           => 'Varda i pagin scancelaa',
'undelete-nodiff'    => "Per questa pagina gh'è nanca una revisiun precedenta.",
'undeletebtn'        => 'Rimett a post',
'undeletedarticle'   => 'rimetüü a post "[[$1]]"',
'undeletedrevisions' => '{{PLURAL:$1|1 revision|$1 versiun}} rimetüü a post',

# Namespace form on various pages
'invert'         => 'Invertí la selezziún',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribuzion dal dovrat',
'mycontris'     => 'I mè interveent',
'uctop'         => '(ültima per la pagina)',

'sp-contributions-deleted' => 'Cuntribüziun scancelaa',
'sp-contributions-talk'    => 'ciciarada',

# What links here
'whatlinkshere' => 'Pagin che se culeghen chì',

# Block/unblock
'blockip'                => 'Bloca el dovrat',
'ipadressorusername'     => 'Adrèss IP o nom del druvàt:',
'ipbexpiry'              => 'Fina al:',
'ipbreason'              => 'Reson:',
'ipbreasonotherlist'     => 'Alter mutiv',
'ipbreason-dropdown'     => "*Mutiv püssee cumün per i blòch
** Avè caregaa di infurmazión fals
** Avè töt via del cuntegnüü dai paginn
** Avè giuntaa di ereclam a di sit da föra
** Avè giuntaa de la ratatuja int i paginn
** Cumpurtament intimidatori
** Avè druvaa püssee dun cünt in manera abüsiva
** El nom del druvàt l'è inacetabil",
'ipbanononly'            => 'Blòca dumà i druvàt anonim',
'ipbcreateaccount'       => 'Lassegh mia creà di alter cünt',
'ipbemailban'            => "Fà in manera che quel druvàt chì 'l poda mia spedì di messagg e-mail",
'ipbsubmit'              => 'Blòca quel druvàt chì',
'ipbother'               => 'Altra dürada:',
'ipboptions'             => '2 ur:2 hours,1 dì:1 day,3 dì:3 days,1 semana:1 week,2 semann:2 weeks,1 mes:1 month,3 mes:3 months,6 mes:6 months,1 ann:1 year,infinii:infinite',
'ipbotheroption'         => 'Alter',
'ipbotherreason'         => 'Alter resón/spiegazión',
'ipbhidename'            => "Scund 'l nom del druvat dai mudifegh e da i list.",
'ipbwatchuser'           => "Tegn d'ögg i paginn duvrat e de discüssión de quel duvrat chì",
'ipballowusertalk'       => "Permet a quel duvrat chì de mudifegà la sò pagina de discüssión intanta che l'è blucaa",
'ipb-change-block'       => 'Blocà ancamò el duvrat cun quij impustazión chì',
'badipaddress'           => 'Adrèss IP mia valid',
'blockipsuccesssub'      => 'Blucagg bel-e faa',
'blockipsuccesstext'     => "[[Special:Contributions/$1|$1]] a l'è staa blucaa.<br />
Varda [[Special:IPBlockList|lista di IP blucaa]] per vidè anmò i bloch.",
'ipb-edit-dropdown'      => 'Resón del bloch',
'ipb-unblock-addr'       => 'Desblòca $1',
'ipb-unblock'            => 'Desbloca un duvrat o un adress IP',
'ipb-blocklist-addr'     => 'Bloch esistent per $1',
'ipb-blocklist'          => 'Vardee i blòch ativ',
'ipb-blocklist-contribs' => 'Cuntribüzión de $1',
'unblockip'              => 'Desblòca quel druvàt chì',
'ipusubmit'              => "Tö via 'stu bloch chì",
'unblocked'              => "[[User:$1|$1]] l'è staa desblucaa",
'ipblocklist'            => 'Adrèss IP e druvàt blucaa',
'blocklistline'          => "$1, $2 l'ha blucaa $3 ($4)",
'blocklink'              => 'bloca',
'contribslink'           => 'cuntribüzziún',
'blocklogpage'           => 'Log di blocch',
'blocklogentry'          => "l'ha blucaa [[$1]] per un temp de $2 $3",

# Move page
'movepagetext'    => "Duvraant la büeta chí-da-sota al re-numinerà una pàgina, muveent tüta la suva stòria al nomm nööf. Ul vecc títul al deventarà una pàgina da redirezziun al nööf títul. I liamm a la vegja pàgina i sarà mia cambiaa: assürévas da cuntrulá par redirezziun dopi u rumpüüt.
A sii respunsàbil da assüráss che i liamm i sigüta a puntá intúe i è süpunüü da ná.
Nutii che la pàgina la sarà '''mia''' muvüda se a gh'è gjamò una pàgina al nööf títul, a maanch che la sía vöja, una redirezziun cun nissüna stòtia d'esizziun passada. Cheest-chí al signífega ch'a pudii renuminá indrée
una pàgina intúe l'évuf renuminada via par eruur, e che vüü pudii mia surascriif una pàgina esisteent.


<b>ATENZIUN!</b>
Cheest-chí al pöö vess un canbi dràstegh e inaspetaa par una pàgina pupülara: par piasée assürévas ch'a ii capii i cunsegueenz da cheest-chí prima da ná inaanz.",
'movearticle'     => "Möva l'articul",
'newtitle'        => 'Titul növ:',
'move-watch'      => "Gionta chela pagina chí ai pàgin à tegní d'œucc.",
'pagemovedsub'    => "San Martin l'è bele fat!",
'movepage-moved'  => "<span style="font-size:larger">'''\"\$1\" l'è staa muvüü a \"\$2\"'''</span>",
'movedto'         => 'spustaa vers:',
'1movedto2'       => '[[$1]] spustaa in [[$2]]',
'1movedto2_redir' => '[[$1]] movuu in [[$2]] par redirezion',
'delete_and_move' => 'Scancelá e mööf',

# Export
'export' => 'Espurtá pagin',

# Namespace 8 related
'allmessages'        => 'Tücc i messacc dal sistéma',
'allmessagesdefault' => 'Test standard',
'allmessagescurrent' => 'Test curent',
'allmessagestext'    => 'Chesta chí l è una lista də messácc də sistema dispunibil indal MediaWiki: namespace.',

# Thumbnails
'thumbnail-more' => 'Ingrandí',

# Special:Import
'import' => 'Impurtá di pagin',

# Tooltip help for the actions
'tooltip-pt-mytalk'               => 'La tua pagina de discüssión',
'tooltip-pt-preferences'          => 'I to preferenz',
'tooltip-pt-logout'               => 'Va fö (logout)',
'tooltip-ca-edit'                 => "Te pör mudifegà quela pagina chì. Per piasè dröva 'l butón per ved i cambiament prima de salvà.",
'tooltip-ca-addsection'           => 'Scumencia una sezión növa',
'tooltip-ca-delete'               => 'Scancela questa pagina',
'tooltip-ca-move'                 => "Sposta 'sta pagina chì (cambiagh 'l titul)",
'tooltip-n-mainpage'              => 'Visité la pàgina principala',
'tooltip-n-portal'                => "Descripzion del proget, cossa ch'a podé far, dond trovar vergòt",
'tooltip-n-currentevents'         => "Informazion ansima a vergòt ch'al riva.",
'tooltip-n-recentchanges'         => 'Lista de canviamenc recenc del wiki',
'tooltip-n-randompage'            => "Càrrega una pàgina a l'azard",
'tooltip-n-help'                  => "Pàgini d'aida",
'tooltip-t-whatlinkshere'         => "Lista de tuti li pàgini wiki ch'i liga scià",
'tooltip-t-recentchangeslinked'   => 'Canviamenc recenc en li pàgini ligadi a chesta',
'tooltip-feed-rss'                => 'Feed RSS per chesta pàgina',
'tooltip-t-specialpages'          => 'Lista de tütt i pagin speciaal',
'tooltip-ca-nstab-project'        => 'Varda la pagina del pruget',
'tooltip-preview'                 => 'Varda i mudifegh (semper mej fàl prima de salvà)',
'tooltip-compareselectedversions' => 'Far vider li diferenzi entra li doi version selezionadi da chesta pàgina',

# Attribution
'siteuser' => '{{SITENAME}} ütent $1',

# Math options
'mw_math_png'    => 'Trasfurmá sempər in PNG',
'mw_math_simple' => 'HTML se mia cumplicaa altrimeent PNG',
'mw_math_html'   => 'HTML se l è pussíbil altrimeent PNG',
'mw_math_source' => 'Lassá in furmaa TeX (pər i prugráma də navigazziún dumá in furmaa da testu)',
'mw_math_modern' => 'Racumandaa pər i bigatt püssè reçeent',
'mw_math_mathml' => 'MathML se l è pussíbil (sperimentaal)',

# Media information
'imagemaxsize' => 'Limitá i imagin süi pagin da descrizziún dii imagin a:',
'thumbsize'    => 'Dimensiún diapusitiif:',

# Special:NewFiles
'newimages' => 'Espusizziun di imàgin nööf',
'ilsubmit'  => 'Truvá',

# External editor support
'edit-externally'      => 'Redatá chest archivi cunt un prugramari da fö',
'edit-externally-help' => 'Varda [http://www.mediawiki.org/wiki/Manual:External_editors i istrüzión] per avègh püssee infurmazión (in ingles).',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tücc',
'imagelistall'     => 'tücc',
'watchlistall2'    => 'tücc',
'namespacesall'    => 'tücc',

# E-mail address confirmation
'confirmemail'          => "Cunferma l<nowiki>'</nowiki>''e-mail''",
'confirmemail_text'     => "Prima da pudé riçeef mesacc sül tò adrèss da pòsta letrònica l è neçessari verificál.
Schiscjá ul butún che gh'è chi da sót par curfermá al tò adrèss.
Te riçevaree un mesacc cun deent un ligamm specjal; ti duvaree clicaa sül ligamm par cunfermá che l tò adrèss l è válit.",
'confirmemail_send'     => 'Mandum un mesacc da cunfermazziún',
'confirmemail_sent'     => 'Ul mesacc da cunfermazziún l è staa mandaa.',
'confirmemail_success'  => "'L voster indirizz e-mail l'è staa cunfermaa: adess a pudii druvà la wiki.",
'confirmemail_loggedin' => "Adess 'l voster indirizz e-mail l'è staa cunfermaa",

# Auto-summaries
'autosumm-blank' => 'Pagina svujada',

# Special:Version
'version' => 'Versiun',

# Special:FilePath
'filepath' => 'Percuurz daj archivi',

# Special:SpecialPages
'specialpages' => 'Paginn special',

);
