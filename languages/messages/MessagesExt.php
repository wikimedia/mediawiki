<?php
/** Extremaduran (estremeñu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Better
 * @author Kaganer
 * @author The Evil IP address
 * @author Urhixidur
 * @author Xuacu
 */

$namespaceNames = array(
	NS_TEMPLATE         => 'Prantilla',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Surrayal atihus:',
'tog-justify' => 'Encahal párrafus',
'tog-hideminor' => 'Açonchal eicionis chiqueninas en "úrtimus chambus"',
'tog-hidepatrolled' => 'Açonchal eicionis vegilás en úrtimus chambus',
'tog-newpageshidepatrolled' => 'Açonchal páginas vegilás ena nueva lista',
'tog-extendwatchlist' => 'Aumental la lista de seguimientu pa muestral tolos chambus apricabris, nu solu los úrtimus',
'tog-usenewrc' => 'Resartal úrtimus chambus (es mestel JavaScript)',
'tog-numberheadings' => 'Autu-numeral entítulus',
'tog-showtoolbar' => "Muestral la barra d'eición (JavaScript)",
'tog-editondblclick' => 'Eital páhinas haziendu dobri click (JavaScript)',
'tog-editsection' => 'Premitil eital mensahis gastandu el atihu [eital]',
'tog-editsectiononrightclick' => 'Premitil eital secionis pulsandu el botón de la derecha<br /> enus entítulus de secionis (JavaScript)',
'tog-showtoc' => 'Muestral cuairu e continius (pa páhinas con mas de 3 entítulus)',
'tog-rememberpassword' => 'Recordal la mi cuenta nesti ordinaol (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations' => 'Vehilal las páhinas que yo crei',
'tog-watchdefault' => "Vehilal las páhinas qu'eiti",
'tog-watchmoves' => 'Vehilal las páhinas que rellami',
'tog-watchdeletion' => "Vehilal las páhinas qu'esborri",
'tog-minordefault' => 'Aseñalal tolas eicionis cumu chiqueninas pol defeutu',
'tog-previewontop' => "Previsoreal sobri la caha d'eición, i nu embahu",
'tog-previewonfirst' => 'Previsoreal ena primera eición',
'tog-nocache' => 'Desatival "caché" enas páhinas',
'tog-enotifwatchlistpages' => 'Envialmi un correu cuandu aiga chambus nuna páhina vehilá',
'tog-enotifusertalkpages' => 'Envialmi un correu cuandu alguien escreba ena mi caraba',
'tog-enotifminoredits' => 'Envialmi un correu cuandu se haga una eición chiquenina duna páhina',
'tog-enotifrevealaddr' => "Muestral la mi direción d'email enus correus",
'tog-shownumberswatching' => "Muestral el númeru d'usuárius que la vehilan",
'tog-oldsig' => 'Firma dessistenti:',
'tog-fancysig' => 'Tratal la firma cumu testu wiki (sin atiju automáticu)',
'tog-uselivepreview' => 'Gastal "live preview" (JavaScript) (en prebas)',
'tog-forceeditsummary' => 'Avisalmi cuandu nu escreba una síntesis dun chambu',
'tog-watchlisthideown' => 'Açonchal las mis eicionis ena lista e seguimientu',
'tog-watchlisthidebots' => 'Açonchal las eicionis de bots ena lista e seguimientu',
'tog-watchlisthideminor' => 'Açonchal las eicionis chiqueninas ena lista e seguimientu',
'tog-watchlisthideliu' => "Açonchal eicionis d'usuárius rustrius ena lista de seguimientu",
'tog-watchlisthideanons' => "Açonchal eicionis d'usuárius anónimus ena lista de seguimientu",
'tog-watchlisthidepatrolled' => 'Açonchal eicionis vegilás ena lista de seguimientu',
'tog-ccmeonemails' => 'Envialmi copias de los emails que enviu a otrus usuárius',
'tog-diffonly' => 'Nu muestral el continiu la páhina embahu las defs',
'tog-showhiddencats' => 'Muestral categorias açonchás',
'tog-norollbackdiff' => 'Omitil diff aluspués de reveltil una página',

'underline-always' => 'Sempri',
'underline-never' => 'Nunca',
'underline-default' => 'Sigún esté nel esproraol',

# Dates
'sunday' => 'Domingu',
'monday' => 'Lunis',
'tuesday' => 'Martis',
'wednesday' => 'Miércuris',
'thursday' => 'Huevis',
'friday' => 'Vienris',
'saturday' => 'Sábau',
'sun' => 'Dom',
'mon' => 'Lun',
'tue' => 'Mar',
'wed' => 'Mié',
'thu' => 'Hue',
'fri' => 'Vie',
'sat' => 'Sáb',
'january' => 'Eneru',
'february' => 'Hebreru',
'march' => 'Marçu',
'april' => 'Abril',
'may_long' => 'Mayu',
'june' => 'Húniu',
'july' => 'Húliu',
'august' => 'Agostu',
'september' => 'Setiembri',
'october' => 'Otubri',
'november' => 'Noviembri',
'december' => 'Diciembri',
'january-gen' => 'Eneru',
'february-gen' => 'Hebreru',
'march-gen' => 'Marçu',
'april-gen' => 'Abril',
'may-gen' => 'Mayu',
'june-gen' => 'Húniu',
'july-gen' => 'Húliu',
'august-gen' => 'Agostu',
'september-gen' => 'Setiembri',
'october-gen' => 'Otubri',
'november-gen' => 'Noviembri',
'december-gen' => 'Diciembri',
'jan' => 'Ene',
'feb' => 'Heb',
'mar' => 'Mar',
'apr' => 'Abr',
'may' => 'May',
'jun' => 'Hún',
'jul' => 'Húl',
'aug' => 'Ago',
'sep' => 'Set',
'oct' => 'Otu',
'nov' => 'Nov',
'dec' => 'Dic',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header' => 'Artículus ena categoria "$1"',
'subcategories' => 'Sucategorias',
'category-media-header' => 'Meya ena categoria "$1"',
'category-empty' => "''Atualmenti nu desistin ni artículus ni archivus murtimeya nesta categoria.''",
'hidden-categories' => '{{PLURAL:$1|categoria açonchá|categorias açonchás}}',
'hidden-category-category' => 'Categorias açonchás',
'category-subcat-count' => '{{PLURAL:$2|Esta categoria solu tiini la siguienti sucategoria.|Esta categoria tiini {{PLURAL:$1|la siguienti sucategoria|las siguientis $1 sucategorias}}, dun total de $2.}}',
'category-subcat-count-limited' => 'Esta categoria tiini {{PLURAL:$1|la siguienti sucategoria|las siguientis $1 sucategorias}}.',
'category-article-count' => '{{PLURAL:$2|Esta categoria solu contiini la siguienti páhina.|{{PLURAL:$1|La siguienti páhina está|Las siguientis $1 páhinas están}} nesta categoria, dun total de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|La siguienti páhina está|Las siguientis $1 páhinas están}} nesta categoria.',
'category-file-count' => '{{PLURAL:$2|Esta categoria solu contiini el siguienti archivu.|{{PLURAL:$1|El siguienti archivu está|Los siguientis $1 archivus están}} nesta categoria, dun total de $2.}}',
'category-file-count-limited' => '{{PLURAL:$1|El siguienti archivu está|Los siguientis $1 archivus están}} nesta categoria.',
'listingcontinuesabbrev' => 'acont.',

'about' => 'Al tentu',
'article' => 'Artículu',
'newwindow' => "(s'abrirá nuna nueva ventana)",
'cancel' => 'Cancelal',
'moredotdotdot' => 'Mas...',
'mypage' => 'La mi páhina',
'mytalk' => 'La mi caraba',
'anontalk' => 'Caraba pa esta IP',
'navigation' => 'Güiquipeandu',
'and' => '&#32;i',

# Cologne Blue skin
'qbfind' => 'Alcuentral',
'qbbrowse' => 'Escrucal',
'qbedit' => 'Eital',
'qbpageoptions' => 'Esta páhina',
'qbmyoptions' => 'Las mis páhinas',
'qbspecialpages' => 'Páhinas especialis',
'faq' => 'FAQ',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Añiil tema',
'vector-action-delete' => 'Esborral',
'vector-action-move' => 'Mual',
'vector-action-protect' => 'Protegel',
'vector-action-undelete' => 'Esborral',
'vector-action-unprotect' => 'Esprotegel',
'vector-view-create' => 'Crial',
'vector-view-edit' => 'Eital',
'vector-view-history' => 'Guipal estorial',
'vector-view-view' => 'Leyel',
'vector-view-viewsource' => 'Guipal cóigu',
'actions' => 'Acionis',
'namespaces' => 'Espáciu nombris',
'variants' => 'Variantis',

'errorpagetitle' => 'Marru',
'returnto' => 'Gorvel a $1.',
'tagline' => 'Dendi {{SITENAME}}',
'help' => 'Ayua',
'search' => 'Landeal',
'searchbutton' => 'Landeal',
'go' => 'Dil',
'searcharticle' => 'Dil',
'history' => 'Estorial',
'history_short' => 'Estorial',
'updatedmarker' => 'atualizau dendi la mi úrtima vesita',
'printableversion' => 'Velsión pa imprental',
'permalink' => 'Atiju remanenti',
'print' => 'Imprental',
'edit' => 'Eital',
'create' => 'Crial',
'editthispage' => 'Eital esta páhina',
'create-this-page' => 'Crial esta páhina',
'delete' => 'Esborral',
'deletethispage' => 'Esborral esta páhina',
'undelete_short' => 'Arrecuperal {{PLURAL:$1|una eición|$1 eicionis}}',
'protect' => 'Protegel',
'protect_change' => 'escambial',
'protectthispage' => 'Protegel esta página',
'unprotect' => 'esprotegel',
'unprotectthispage' => 'Esprotegel esta página',
'newpage' => 'Páhina nueva',
'talkpage' => 'Palral sobri esta páhina',
'talkpagelinktext' => 'Caraba',
'specialpage' => 'Página Especial',
'personaltools' => 'Herramientas presonalis',
'postcomment' => 'Nueva seción',
'articlepage' => 'Vel artículu',
'talk' => 'Caraba',
'views' => 'Guipás',
'toolbox' => 'Herramientas',
'userpage' => "Vel página d'ussuáriu",
'projectpage' => 'Vel página el proyeutu',
'imagepage' => 'Vel páhina la imahin',
'mediawikipage' => 'Vel páhina el mensahi',
'templatepage' => 'Vel la prantilla',
'viewhelppage' => "Vel páhina d'ayua",
'categorypage' => 'Vel páhina e categorias',
'viewtalkpage' => 'Vel caraba',
'otherlanguages' => 'En otras palras',
'redirectedfrom' => '(Rederihiu dendi $1)',
'redirectpagesub' => 'Rederihil páhina',
'lastmodifiedat' => 'Los úrtimus chambus desta páhina huerun a las $2 el dia $1.',
'viewcount' => 'Esta páhina á siu visoreá {{PLURAL:$1|una vezi|$1 vezis}}.',
'protectedpage' => 'Página protegia',
'jumpto' => 'Sartal a:',
'jumptonavigation' => 'Güiquipeandu',
'jumptosearch' => 'Landeal',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Al tentu {{SITENAME}}',
'aboutpage' => 'Project:Enjolmación',
'copyright' => 'Continiu disponibri bahu $1.',
'copyrightpage' => '{{ns:project}}:Copyright',
'currentevents' => 'La trohi las notícias',
'currentevents-url' => 'Project:La trohi las notícias',
'disclaimers' => 'Avissu legal',
'disclaimerpage' => 'Project:Arrayu heneral de responsabiliá',
'edithelp' => "Ayua d'eición",
'helppage' => 'Help:Continius',
'mainpage' => 'Página prencipal',
'mainpage-description' => 'Páhina prencipal',
'policy-url' => 'Project:Pulítica',
'portal' => 'Puertal la comuniá',
'portal-url' => 'Project:Puertal la Comuniá',
'privacy' => 'Pulítica',
'privacypage' => 'Project:Pulítica e privaciá',

'badaccess' => 'Marru colos tus premisus',
'badaccess-group0' => 'Nu se te premiti hazel esa ación.',
'badaccess-groups' => 'Solu los usuárius {{PLURAL:$2|del grupu|de los grupus}} $1 puein hazel esa ación.',

'versionrequired' => 'Es mestel tenel la velsión $1 de MeyaGüiqui',
'versionrequiredtext' => 'Es mestel tenel la velsión $1 de MeyaGüiqui pa usal esta páhina. Vai a la  [[Special:Version|páhina e velsión]].',

'ok' => 'Dalcuerdu',
'retrievedfrom' => 'Arrecuperau dendi "$1"',
'youhavenewmessages' => 'Tiinis $1 ($2).',
'newmessageslink' => 'nuevus mensahis',
'newmessagesdifflink' => 'úrtimu chambu',
'youhavenewmessagesmulti' => 'Tiinis nuevus mensahis en $1',
'editsection' => 'eital',
'editold' => 'eital',
'viewsourceold' => 'Visoreal coigu huenti',
'editlink' => 'eital',
'viewsourcelink' => 'vel coigu',
'editsectionhint' => 'Eital seción: $1',
'toc' => 'Continius',
'showtoc' => 'muestral',
'hidetoc' => 'açonchal',
'thisisdeleted' => 'Guipal u restaural $1?',
'viewdeleted' => 'Visoreal $1?',
'restorelink' => '{{PLURAL:$1|una eición esborrá|$1 eicionis esborrás}}',
'feedlinks' => 'Sindicación:',
'feed-invalid' => 'Suscrición nu vália.',
'feed-unavailable' => 'Los canalis de sindicación nu están disponibris',
'site-rss-feed' => 'Canal RSS $1',
'site-atom-feed' => 'Canal Atom $1',
'page-rss-feed' => 'Canal RSS "$1"',
'page-atom-feed' => 'Canal Atom "$1"',
'red-link-title' => '$1 (la página nu dessisti)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Artículu',
'nstab-user' => "Páhina d'usuáriu",
'nstab-media' => 'Páhina "Meya"',
'nstab-special' => 'Artículu especial',
'nstab-project' => 'Página el proyeutu',
'nstab-image' => 'Archivu',
'nstab-mediawiki' => 'Mensahi',
'nstab-template' => 'Prantilla',
'nstab-help' => "Páhina d'ayua",
'nstab-category' => 'Categoria',

# Main script and global functions
'nosuchaction' => 'Nu desisti tal ación',
'nosuchactiontext' => 'La URL nu es vália.
Es possibri que aigas marrau escribiendu la direción, u aigas siguiu un atiju encorretu.
Tamién es possibri que se trati dun marru entelnu de {{SITENAME}}.
especificá ena URL',
'nosuchspecialpage' => 'Nu desisti tal páhina especial',
'nospecialpagetext' => '<strong>Nu desisti esa páhina especial.</strong>

Pueis alcuentral una lista colas páhinas especialis desistentis en [[Special:SpecialPages]].',

# General errors
'error' => 'Marru',
'databaseerror' => 'Marru ena basi e datus',
'laggedslavemode' => 'Avisu: Es posibri que la páhina nu esté atualizá.',
'readonly' => 'Basi e datus atarugá',
'enterlockreason' => 'Escrebi una razón pal tarugu, i cuandu esti
sedrá esborrau',
'readonlytext' => 'La basi e datus está atualmenti atarugá, siguramenti pol mantenimientu rutinariu. Cuandu s´acabihin los chambus, la güiqui gorverá a la normaliá.

La razón dá pol el alministraol que pusu el tarugu es: $1',
'missing-article' => 'Nu s\'á alcuentrau el testu anombrau "$1" $2 ena bassi de datus.

Estu sueli acontecel pol seguil un atiju antigu referenti a una página que á siu esborrá.

Si ésti nu es el chascu, es possibri que aigas alcuentrau un marru nel software.
Pol favol, contauta con un [[Special:ListUsers/sysop|çajoril]], mentandu la URL.',
'missingarticle-rev' => '(revisión#: $1)',
'missingarticle-diff' => '(Def: $1, $2)',
'readonly_lag' => "S'á atarugau la basi e datus temporalmenti mentris los sirvioris se sincroniçan.",
'internalerror' => 'Marru entelnu',
'internalerror_info' => 'Marru entelnu: $1',
'filecopyerror' => 'Nu se puei copial el archivu "$1" a "$2".',
'filerenameerror' => 'Nu se puei renombral el archivu "$1" a "$2".',
'filedeleteerror' => 'Nu se puei esborral el archivu "$1".',
'directorycreateerror' => 'Nu se puei crial el diretoriu "$1".',
'filenotfound' => 'Nu se puei alcuentral el archivu "$1".',
'fileexistserror' => 'Nu es posibri escrebil el archivu "$1": el archivu ya desisti',
'unexpected' => 'Valol nu asperau: "$1"="$2".',
'formerror' => 'Marru: nu se puei envial el hormulariu',
'badarticleerror' => 'Nu se puei realizal esta ación nesta páhina.',
'cannotdelete' => 'Nu es possibri esborral "$1". Puei que ya lo aiga esborrau otra presona.',
'badtitle' => 'Mal entitulau',
'badtitletext' => 'El entítulu la páhina está vaciu, nu es váliu, u es un atihu entelluenga u entelgüiqui encorretu.',
'perfcached' => "Los siguientis datus s'alcuentran nel caché i es posibri que nu estén atualizaus. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.",
'perfcachedts' => 'Estus datus están emburacaus. La su úrtima atualización hue el $1. A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.',
'querypage-no-updates' => "Las atualiçacionis desta páhina s'alcuentran atualmenti desativás. Los datus nu sedrán atualizaus a cortu praçu.",
'wrong_wfQuery_params' => 'Parametrus a wfQuery()<br /> Hunción: $1<br /> Pregunta: $2 encorretus',
'viewsource' => 'Vel coigu huenti',
'actionthrottled' => 'Ación ilimitá',
'actionthrottledtext' => 'Cumu miia pa prevenil el spam, solu pueis hazel esta ación un limitau númeru e vezis nun cortu praçu e tiempu, i as pasau esti límiti. Pol favol, enténtalu otra vezi endrentu angunus minutus.',
'protectedpagetext' => "Esta página s'alcuentra atarugá a nuevas eicionis.",
'viewsourcetext' => 'Pueis vel i copial el cóigu huenti desta páhina:',
'protectedinterface' => "Esta páhina proporciona el testu la entrihazi el software, razón pola que s'alcuentra atarugá.",
'editinginterface' => "'''Cudiau:''' Estás eitandu una página que propolciona el testu la entrijazi el software. Los chambus hechus aquina afeutarán a la entrijazi d'otrus ussuárius.
Pa traucil, consiera gastal [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], el proyeutu de traución de MediaWiki.",
'cascadeprotected' => 'Esta páhina s\'alcuentra protehia ebiu a que horma parti e {{PLURAL:$1|la siguienti páhina|las siguientis páhinas}}, qu\'están protehias cola oción "proteción en cascá" ativá:
$2',
'namespaceprotected' => "Nu tiinis premisu pa eital páhinas nel \"espaciu e nombris\" '''\$1'''.",
'ns-specialprotected' => 'Nu se puein eital las páhinas el {{ns:special}} "espaciu e nombris".',
'titleprotected' => "Esti entítulu á siu atarugau pol [[User:$1|$1]].
La razón es la siguienti: ''$2''.",

# Virus scanner
'virus-badscanner' => "Mala confeguración: escrucaol de virus andarríu: ''$1''",
'virus-scanfailed' => 'marru al escrucal virus (cóigu $1)',
'virus-unknownscanner' => 'Antivirus andarriu:',

# Login and logout pages
'logouttext' => "'''Cuenta afechá corretamenti.'''<br />
Pueis acontinal gastandu {{SITENAME}} de holma anónima, u <span class='plainlinks'>[$1 entral ena tu cuenta]</span> con el mesmu ussuáriu, u con otru.
Dati cuenta que hata que nu esborris el caché del tu escrucaol pué paecel que la tu cuenta acontina abierta n'angunas páginas.",
'yourname' => "Nombri d'usuáriu:",
'yourpassword' => 'Consínia:',
'yourpasswordagain' => 'Escrebi e nuevu la consínia:',
'remembermypassword' => 'Recordal la mi cuenta nesti ordinaol (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname' => 'El tu domiñu:',
'externaldberror' => "Marru d'autentificación esterna e la basi e datus, u bien nu t'alcuentras autorizau p'atualizal la tu cuenta esterna.",
'login' => 'Entral',
'nav-login-createaccount' => 'Entral/Crial cuenta',
'loginprompt' => 'Ebis atival las cookies pa entral en {{SITENAME}}.',
'userlogin' => 'Entral/Crial cuenta',
'userloginnocreate' => 'Entral',
'logout' => 'Salil',
'userlogout' => 'Salil',
'notloggedin' => "Nu t'alcuentras rustriu",
'nologin' => 'Entovia nu gastas una cuenta? $1.',
'nologinlink' => 'Cria una',
'createaccount' => 'Crial cuenta',
'gotaccount' => "Ya tiinis una cuenta? '''$1'''.",
'gotaccountlink' => 'Entral',
'createaccountmail' => 'pol e-mail',
'badretype' => 'Las consínias nu conciin.',
'userexists' => "El nombri d'usuáriu ya s'alcuentra rustriu, pol favol, escrebi otru nombri.",
'loginerror' => "Marru d'ativación",
'createaccounterror' => "Nu es possibri crial la cuenta d'usuáriu: $1",
'nocookiesnew' => "S'á criau la tu cuenta d'usuáriu, inque nu la tinis abierta. {{SITENAME}} gasta \"cookies\" pa premitil el acesu a los usuárius, i tú las tinis desativás. Pol favol, atívalas i entra ena tu cuenta con el tu nombri d'usuáriu i consínia.",
'nocookieslogin' => '{{SITENAME}} gasta cookies pa entifical a los usuárius, i tú las tiinis esativás. Pol favol, atívalas i preba otra vezi.',
'noname' => "Nu as escrebiu un nombri d'usuáriu corretu.",
'loginsuccesstitle' => 'Yeu, lo cúmu va esu?',
'loginsuccess' => "'''Acabihas d'entral en {{SITENAME}} con el nombri \"\$1\".'''",
'nosuchuser' => 'Nu dessisti dengún usuáriu anombrau "$1".
Compreba que lo aigas escritu bien, u [[Special:UserLogin/signup|cria una cuenta nueva]].',
'nosuchusershort' => 'Nu ai dengún usuáriu llamau "$1". Compreba qu\'esté bien escritu.',
'nouserspecified' => "Ebis escribil un nombri d'usuáriu.",
'wrongpassword' => 'La consínia escrebia nu es correta. Pol favol, preba otra vezi.',
'wrongpasswordempty' => 'As ehau en brancu la consínia. Pol favol, preba otra vezi.',
'passwordtooshort' => 'Las consínias ebin tenel cumu ménimu {{PLURAL:$1|1 caratel|$1 carateris}}.',
'password-name-match' => 'La consínia ebi sel deferenti del tu nombri dusuáriu.',
'mailmypassword' => 'Envialmi pol correu una nueva consínia',
'passwordremindertitle' => 'Alcuerda-consínias de {{SITENAME}}',
'passwordremindertext' => 'Alguien (siguramenti tú, dendi la direción IP $1)
mos á solicitau una nueva consínia pa {{SITENAME}} ($4).
La consínia temporal del ussuáriu "$2" es "$3".
Eberias entral ena tu cuenta i chambal la consínia lo antis possibri, la consínia caducará {{PLURAL:$5|nun dia|en $5 dias}}.

Si nu ás solicitau tú el chambu, u ya t\'as alcuerdau \'e la tu consínia i nu quieis chambala, pueis acontinal gastandu la consínia antígua.',
'noemail' => 'Nu ai emburacau dengún e-mail el usuáriu "$1".',
'passwordsent' => 'S\'á enviau una nueva consínia a la direción d\'email
rustria pol "$1".
Pol favol, abri la tu cuenta d\'usuáriu cuandu la recibas.',
'blocked-mailpassword' => "La tu direción d'IP está atarugá, polo que nu se te premiti
gastal la hunción p'arrecuperal consínias pa previnil abusionis.",
'eauthentsent' => "S'á enviau un email de confirmación a la direción especificá. Enantis de que se envii cualisquiel otru correu a la cuenta tienis que seguil las istrucionis enviás nel mensahi, pa d'esta horma, confirmal que la direción te preteneci.",
'throttled-mailpassword' => "S'á gastau un alcuerda-consínias jadi
menus {{PLURAL:$1|duna ora|de $1 oras}}. Cumu miía de seguráncia, solu es pssobri gastal el alcuerda-consínias ca {{PLURAL:$1|ora|$1 oras}}.",
'mailerror' => 'Marru enviandu el mensahi: $1',
'acct_creation_throttle_hit' => "Usuárius d'esta wiki que gastan la mesma IP que tú án criau {{PLURAL:$1|1 cuenta|$1 cuentas}} nel úrtimu dia, lo cual es el máissimu premitiu.
Ebiu a estu, entovia nu se premiti rustrilsi a los vessitantis que gastin la mesma IP.",
'emailauthenticated' => 'La direción de correu eletrónicu hue comprebá el dia $2 a las $3.',
'emailnotauthenticated' => 'Entovia nu as confirmau la tu direción email. Hata que lo hagas, nu estarán disponibris las siguientis huncionis.',
'noemailprefs' => "Escreba la su direción de correu p'atival estas caraterísticas.",
'emailconfirmlink' => 'Confirma el tu e-mail',
'invalidemailaddress' => "Nu es possibri acetal la tu direción d'email ebiu a que paci tenel un jolmatu nu premitiu. Pol favol, escrebi una direción con algotru jolmatu, u quea en brancu el cuairu.",
'accountcreated' => 'Cuenta criá',
'accountcreatedtext' => "La cuenta d'usuáriu pa $1 á siu criá.",
'createaccount-title' => 'Criaeru e cuentas de {{SITENAME}}',
'createaccount-text' => 'Alguien á criau una cuenta pa $2 en {{SITENAME}} ($4). La consínia pa "$2" es "$3".
Eberias entral ena tu cuenta i chambal la tu consínia.

Si s\'á criau la cuenta ebiu a angún marru, inora esti mensahi.',
'loginlanguagelabel' => 'Palra: $1',

# Change password dialog
'resetpass' => 'Escambial la consínia',
'resetpass_announce' => 'As entrau ena tu cuenta con una consínia temporal. Pol favol, escrebi una nueva consínia aquí:',
'resetpass_text' => '<!-- Aquí s´escrebi el testu -->',
'resetpass_header' => "Escambial la consínia la tu cuenta d'usuáriu",
'oldpassword' => 'Consínia antigua:',
'newpassword' => 'Consínia nueva:',
'retypenew' => 'Güervi a escrebil la nueva consínia:',
'resetpass_submit' => 'Escrebi la consínia i entra',
'changepassword-success' => 'La tu consínia á siu chambá! Ya pueis entral otra vezi ena tu cuenta...',
'resetpass_forbidden' => 'Nu es possibri escambial las consínias',
'resetpass-submit-loggedin' => 'Escambial consínia',
'resetpass-submit-cancel' => 'Cancelal',
'resetpass-temp-password' => 'Consínia temporal:',

# Edit page toolbar
'bold_sample' => 'Testu en letra "Bold"',
'bold_tip' => 'Testu en letra "Bold"',
'italic_sample' => 'Testu en letra "Itálica"',
'italic_tip' => 'Testu en letra "Itálica"',
'link_sample' => 'Atihal entítulu',
'link_tip' => 'Atihu entelnu',
'extlink_sample' => 'http://www.example.com Entítulu el atihu',
'extlink_tip' => 'Atihu esternu (alcuerdati el prefihu http://)',
'headline_sample' => 'Entítulu',
'headline_tip' => 'Entítulu e nivel 2',
'nowiki_sample' => 'Añiil testu sin hormatu aquí',
'nowiki_tip' => 'Inoral hormatu güiqui',
'image_sample' => 'Sabulugal.jpg',
'image_tip' => 'Imahin encuairá',
'media_sample' => 'Sabulugal.ogg',
'media_tip' => "Atihu d'archivu",
'sig_tip' => 'Firma, fecha i ora',
'hr_tip' => 'Línia orizontal (deseparaol)',

# Edit pages
'summary' => 'Síntesis:',
'subject' => 'Tema/entítulu:',
'minoredit' => 'Esta es una eición chiquenina',
'watchthis' => 'Vehilal esta páhina',
'savearticle' => 'Emburacal páhina',
'preview' => 'Previsoreal',
'showpreview' => 'Previsoreal',
'showlivepreview' => '"Live Preview"',
'showdiff' => 'Muestral chambus',
'anoneditwarning' => "'''Avisu:''' Nu t'alcuentras rustriu, razón pola que s'emburacará la tu IP nel estorial d'esta páhina.",
'missingsummary' => "'''Atención:''' Nu as escrebiu una síntesis al tentu la tu eición. Si pursas otra vezi sobri «{{int:savearticle}}» la tu eición s´emburacará sin él.",
'missingcommenttext' => 'Pol favol, escrebi un testu embahu.',
'missingcommentheader' => "'''Atención:''' Nu as escrebiu un entítulu pal tu comentáriu. Si güervis a pursal sobri \"Emburacal\", s'emburacará sin él.",
'summary-preview' => 'Previsoreal síntesis:',
'subject-preview' => 'Previsoreal tema/entítulu:',
'blockedtitle' => 'Esti usuáriu está atarugau',
'blockedtext' => "'''El tu nombri d'usuáriu/direción IP está atarugau/á.'''

\$1 jue quien jidu el tarugu, pola siguienti razón: ''\$2''.

* Fecha en qu'el tarugu prencipió: \$8
* Fecha en qu'el tarugu acabijará: \$6
* Tarugu: \$7

Pueis contatal con \$1 u con otru [[{{MediaWiki:Grouppage-sysop}}|çahoril]] pa chalral al tentu el tarugu.
Si nu as escrebiu enas tus [[Special:Preferences|preferéncias]] una direción d'email, u si t'á siu atarugau el correu, nu te sedrá possibri gastal el botón \"Envial un email a esti ussuáriu\".
\$3 es la tu direción IP atual, i el ID del tarugu es #\$5. Pol favol, escrebi dambus los dos datus en cualisquiel consurta que hagas.",
'autoblockedtext' => 'La tu direción IP á siu atarugá automáticamenti ebiu a qu\'estaba siendu gastá pol otru ussuáriu, quién á siu atarugau pol $1 cola siguienti razón:

:\'\'$2\'\'

* Fecha en qu\'el tarugu prencipió: $8
* Fecha en qu\'el tarugu acabijará: $6
# Tarugu: $7

Pueis contautal con $1 u con otru [[{{MediaWiki:Grouppage-sysop}}|çahoril]] pa chalral al tentu el tarugu.
Si nu as escrebiu enas tus [[Special:Preferences|preferéncias]] una direción d\'email, u si t\'á siu atarugau el correu, nu te sedrá possibri gastal el botón "Envial un email a esti ussuáriu".
$3 es la tu direción IP atual, i el ID del tarugu es #$5. Pol favol, escrebi dambus los dos datus en cualisquiel consurta que hagas.',
'blockednoreason' => "nu s'an dau razonis",
'whitelistedittext' => 'Tiinis que $1 pa eital páhinas.',
'confirmedittext' => 'Ebis confirmal la tu direción d´email enantis d´eital páhinas. Pol favol, escrebi i compreba el tu email pol meyu las tus [[Special:Preferences|preferéncias d´usuáriu]].',
'nosuchsectiontitle' => 'Nu es posibri alcuentral el apaltiju',
'nosuchsectiontext' => "Estás ententandu eital un apaltiju que nu desisti.
Es posibri qu'aiga siu muau u esborrau mentris visoreabas la página.",
'loginreqtitle' => 'Es mestel rustrilsi',
'loginreqlink' => 'entral',
'loginreqpagetext' => 'Ebis $1 pa vel otras páhinas.',
'accmailtitle' => 'Consínia enviá.',
'accmailtext' => 'Se t\'á enviau una consínia aleatória pa [[User talk:$1|$1]] a La consínia pa "$1" a $2.

Es possibri escambial la consínia de la cuenta entrandu ena tu cuenta, ena página d\'\'\'[[Special:ChangePassword|escambial consínia]]\'\'.',
'newarticle' => '(Nuevu)',
'newarticletext' => "Esti artículu entovia nu desisti.
Si quieis crial esti artículu, escribi nel cuairu d'embahu
(si t'es mestel, mira la [[{{MediaWiki:Helppage}}|páhina d'ayua]]).
Si nu quieis crial esti artículu, solu tiinis que pursal nel botón \"'''atrás'''\" del tu escrucaol.",
'anontalkpagetext' => "----''Esta es la caraba dun usuáriu anónimu qu'entovia nu á criau una cuenta, u nu la gasta, asínque tenemus que usal la su direción IP pa ientificalu. Una mesma direción IP puei sel gastá pol varius usuárius, polo que si creis que s'án derihiu a tí con cosas que nu vinin a cuentu, pol favol [[Special:UserLogin|cria una cuenta]] pa evital huturus pobremas con otrus usuárius anónimus.''",
'noarticletext' => 'Entovia nu ai dengún testu escrebiu nesta páhina.
Pueis [[Special:Search/{{PAGENAME}}|landeal el entítulu del artículu]] en otras páhinas,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} search the related logs],
u [{{fullurl:{{FULLPAGENAME}}|action=edit}} eital ésta]</span>.',
'userpage-userdoesnotexist' => 'La cuenta d\'usuáriu "<nowiki>$1</nowiki>" nu está rustria. Pol favol, compreba que rialmenti quieis crial/eital esta páhina.',
'clearyourcache' => "'''Nota:''' Aluspués d'emburacal el archivu, ebi gorvel a cargal la página pa vel los chambus. '''Mozilla / Firefox / Safari:''' Pursa la tecra ''Shift'' mentris das a ''Recargal'', u pursa ''Ctrl-F5'' u ''Ctrl-R'' (''Command-R'' en Mac);
'''Konqueror:''' Pursa ''F5'' u ''Recargal'';
'''Opera:''' Esborra el caché en ''Herramientas→Preferéncias''.
'''Internet Explorer:''' Mantén ''Ctrl'' mentris pursas ''Atualizal'', u pursa ''Ctrl-F5''.",
'usercssyoucanpreview' => "'''Consehu:''' Gasta el botón 'Previsoreal' pa prebal el tu nuevu CSS enantis d´emburacal.",
'userjsyoucanpreview' => "'''Consehu:''' Gasta el botón 'Previsoreal' pa prebal el tu nuevu JS enantis d´emburacal.",
'usercsspreview' => "'''Alcuerdati que solu estás previsoreandu el tu CSS d'usuáriu.'''
'''Entovia nu está emburacau!'''",
'userjspreview' => "'''Recuerda que solu estás prebandu/previsoreandu el tu JavaScript d´usuáriu, entovia nu está emburacau!'''",
'userinvalidcssjstitle' => "'''Avisu:''' Nu desisti el skin \"\$1\". Alcuerdati que las páhinas presonalizás .css i .js tienin el su entítulu en menúsculas, p.s. {{ns:user}}:Foo/vector.css en lugal de {{ns:user}}:Foo/Vector.css.",
'updated' => '(Atualizau)',
'note' => "'''Nota:'''",
'previewnote' => "'''Agora solu estás previsoreandu; entovia nu están emburacaus los chambus!'''",
'previewconflict' => 'Al previsoreal se muestra cúmu queará el testu una vezi emburacaus los chambus.',
'session_fail_preview' => "'''Marru al empuntal la eición.
Pol favol, ententa empuntala otra vezi, i si acontina marrandu, preba a afechal i abril de nuevu la tu cuenta.'''",
'session_fail_preview_html' => "'''Lo sentimus, nu á siu posibri procesal la tu eición ebiu a una perda e datus de sesión.'''

''Puestu que {{SITENAME}} tieni ativau el HTML puru, la previsorealización nu se muestrará cumu precaución anti los ataquis en JavaScript.''

'''Si esti es un ententu lehítimu d'eición, pol favol, ententalu otra vezi. Si acontina sin furrulal, preba a desconetalti i gorvel a entral ena tu cuenta.'''",
'token_suffix_mismatch' => "'''La tu eición nu á siu acetá ebiu a qu'el tu escrucaol mutiló los caráteris de puntuación nel eitol. La eición nu á siu acetá pa prevenil pobremas nel artículu.
Esti pobrema se dá angunas vezis si estás gastandu un proxy anónimu basau en web que seya pobremáticu.'''",
'editing' => 'Eitandu $1',
'editingsection' => 'Eitandu $1 (seción)',
'editingcomment' => 'Eitandu $1 (nueva seción)',
'editconflict' => "Conflitu d'eición: $1",
'explainconflict' => "Alguien á hechu chambus nesta páhina dendi que prencipiasti a eitala.
El cuairu e testu superiol endica el testu que desisti atualmenti ena páhina.
Los tus chambus se muestran nel cuairu e testu inferiol.
Pa emburacal los tus chambus, ebis tresladalus al cuairu superiol.
'''Solu''' s'emburacará el testu el cuairu superiol cuandu pursis \"{{int:savearticle}}\".",
'yourtext' => 'El tu testu',
'storedversion' => 'Velsión emburacá',
'nonunicodebrowser' => "'''Atención: El tu escrucaol nu cumpri la norma Unicode. S'á ativau un sistema d'eición alternativu que te premitirá eital artículus con seguráncia, inque los carateris que nu seyan ASCII apaicirán nel cuairu d'eición cumu cóigus esadecimalis.'''",
'editingold' => "'''Avisu: Estás eitandu una velsión antigua
desta páhina.
Si la emburacas, tolos chambus hechus dendi esa revisión se perderán.'''",
'yourdiff' => 'Deferéncias',
'copyrightwarning' => "Pol favol, dati cuenta e que tolos endirguis en {{SITENAME}} s'arreparan hechus púbricus bahu \$2 (vel detallis en \$1). Si nu quieis qu'otras presonas hagan chambus enos tus escritus i los destribuya librienti, altonci nu los escrebas aquina.<br />
Pol otra parti, al pursal el botón \"emburacal\" mos estás asigurandu que lo escrebiu á siu hechu pol tí, u lo as copiau dun domiñu púbricu u recursu semilal.
'''Nu emburaquis labutus con Copyright sin premisu!'''",
'copyrightwarning2' => "Tolas contribucionis a {{SITENAME}} puein sel eitás, chambás, u esborrás pol otrus colabutaoris. Si nu estás dalcuerdu, altonci nu emburaquis ná.<br />
Pol otra parti, al pursal el botón \"emburacal\" mos estás asigurandu que lo escrebiu á siu hechu pol tí, u copiau dun domiñu púbricu u recursu semilal (lei \$1 pa mas detallis).
'''Cudia: Nu emburaquis labutus con Copyright sin premisu!'''",
'longpageerror' => "'''Marru: El testu qu'as empuntau ocupa $1 kbs (siendu polo tantu mayol de $2 kbs). Nu es posibri emburacal.'''",
'readonlywarning' => "'''Alverténcia: La bassi datus s'alcuentra cerrá pol mantenimientu nesti momentu,
razón pola que nu pueis emburacal los tus chambus agora.'''
Pa nu perdel los chambus, pueis copialus i pegalus nel tu ordinaol, i nun ratinu, emburacalus ena wiki.

El alministraol que á cerrau la bassi datus á dau la siguienti razón: $1",
'protectedpagewarning' => "'''Alverténcia: Esta página s'alcuentra atarugá, asínque sólu los çahorilis puein eitala.'''
Embaju se muestra el rustriju d'acessu cumu referéncia:",
'semiprotectedpagewarning' => "'''Nota:''' S'á atarugau esta página, asínque solu los ussuárius rustrius puein eitala.
Embaju se muestra el rustriju d'acessu cumu referéncia:",
'cascadeprotectedwarning' => "'''Avisu:''' Esta páhina está protehia, asínque solu los çahorilis puein eitala. La razón de qu'esté protehia es que s'alcuentra encluia {{PLURAL:$1|ena siguienti páhina|enas siguientis páhinas}} cola oción ''cascá'' ativá:",
'titleprotectedwarning' => "'''Alverténcia: Esta página á siu atarugá, razón pola que son mestel [[Special:ListGroupRights|ciertus derechus]] pa criala.'''",
'templatesused' => '{{PLURAL:$1|Prantilla|Prantillas}} gastás nesta página:',
'templatesusedpreview' => '{{PLURAL:$1|Prantilla|Prantillas}} gastás al previsoreal:',
'templatesusedsection' => '{{PLURAL:$1|Prantilla gastá|Prantillas gastás}} en esti apaltiju:',
'template-protected' => '(protehiu)',
'template-semiprotected' => '(abati-protehiu)',
'hiddencategories' => 'Esta páhina preteneci a {{PLURAL:$1|1 categoria açonchá|$1 categorias açonchás}}:',
'edittools' => '<!-- Esti testu apaicirá embahu los hormulárius d´eición i empuntu. -->',
'nocreatetext' => 'Nu se premiti crial páhinas nuevas a usuárius anónimus.
Pueis gorvel i eital anguna páhina ya desistenti, u [[Special:UserLogin|rustrilti]].',
'nocreate-loggedin' => 'Nu tiés premissu pa crial nuevas páginas.',
'permissionserrors' => 'Marrus colos premisus',
'permissionserrorstext' => 'Nu t´está premitiu hazel esu, {{PLURAL:$1|pola siguienti razón|polas siguientis razonis}}:',
'permissionserrorstext-withaction' => 'Nu tiinis premisu pa $2, {{PLURAL:$1|pola siguienti razón|polas siguientis razonis}}:',
'recreate-moveddeleted-warn' => "'''Avissu: Vas a gorvel a crial una páhina que ya jue esborrá.'''

Eberias consideral acontinal eitandu esta página.
Embaju se muestra el rustriju d'esborrau:",
'log-fulllog' => 'Visoreal el rustriju compretu',
'edit-gone-missing' => 'Nu es possibri atualizal la página.
Paci bel siu esborrá.',
'edit-conflict' => "Marru d'eición.",
'edit-no-change' => "La eición nu á siu emburacá, ebiu a que nu s'á hechu dengún chambu nel testu.",
'edit-already-exists' => 'Marru al crial la nueva página.
Ya dessisti.',

# "Undo" feature
'undo-success' => 'Se puei eshazel la eición. Enantis d´eshazel la eición, compreba la siguienti comparáncia pa verifical que realmenti es lo que quieis hazel, i altonci, emburaqui los chambus pa, d´esta horma, eshazel la eición.',
'undo-failure' => 'Nu es posibri eshazel la eición ebiu a que otru usuáriu á realizau una eición entelmeya.',
'undo-norev' => 'La eición nu pué sel eshecha ebiu a que nu dessisti, u hue esborrá',
'undo-summary' => 'Eshazel revisión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Caraba]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nu es posibri crial la cuenta',
'cantcreateaccount-text' => "La criación de cuentas pol parti e la IP ('''$1''') á siu pará pol el usuáriu [[User:$3|$3]].

La razón dá pol $3 es ''$2''",

# History pages
'viewpagelogs' => 'Vel los rustrihus d´esta páhina',
'nohistory' => 'Nu ai dengún estorial d´eicionis pa esta páhina.',
'currentrev' => 'Revisión atual',
'currentrev-asof' => 'Úrtima revisión: $1',
'revisionasof' => 'Revisión de $1',
'revision-info' => 'Revisión de $1 hecha pol $2',
'previousrevision' => '←Revisión mas antigua',
'nextrevision' => 'Revisión mas recienti→',
'currentrevisionlink' => 'Revisión atual',
'cur' => 'atu',
'next' => 'siguienti',
'last' => 'úrtimu',
'page_first' => 'primel',
'page_last' => 'úrtimu',
'histlegend' => 'Leyenda: (ati) = deferéncias cola velsión atual, (anter) = deferéncias cola velsión anteriol, C = eición chiquenina',
'history-fieldset-title' => 'Escrucal estorial',
'history-show-deleted' => 'Solu esborraus',
'histfirst' => 'Mas recienti',
'histlast' => 'Mas antigu',
'historysize' => '({{PLURAL:$1|1 byte|$1 byts}})',
'historyempty' => '(vaciu)',

# Revision feed
'history-feed-title' => 'Estorial de revisionis',
'history-feed-description' => 'Estorial de revisionis pa esta páhina nel güiqui',
'history-feed-item-nocomment' => '$1 en $2',
'history-feed-empty' => 'Esa páhina nu desisti.
Es posibri qu´aiga siu esborrá e la güiqui, u que s´aiga chambau el su nombri.
Preba [[Special:Search|landeandu]] entri las nuevas páhinas de la güiqui.',

# Revision deletion
'rev-deleted-comment' => '(comentáriu esborrau)',
'rev-deleted-user' => '(nombri d´usuáriu esborrau)',
'rev-deleted-event' => '(entrá esborrá)',
'rev-deleted-text-permission' => "La revisión desta página á siu '''esborrá'''.
Es possibri que aiga detallis nel [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rustriju d'esborrau].",
'rev-deleted-text-view' => "Esta revisión de la páhina á siu '''esborrá'''.
Cumu alministraol pueis echali una guipaina;
puei bel detallis nel [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rustriju d'esborrau].",
'rev-delundel' => 'muestral/açonchal',
'rev-showdeleted' => 'muestral',
'revisiondelete' => 'Esborral/arrecuperal revisionis',
'revdelete-nooldid-title' => 'Nu ai una revisión destinu',
'revdelete-nooldid-text' => 'Nu as prehisau denguna revisión destinu ondi realizal esta hunción.',
'revdelete-show-file-submit' => 'Sí',
'revdelete-selected' => "'''{{PLURAL:$2|Revisión aseñalá e|Revisionis aseñalás de}} '''[[:$1]]''':'''",
'logdelete-selected' => "'''{{PLURAL:$1|Eventu el rustrihu aseñalau|Eventus del rustrihu aseñalaus}}:'''",
'revdelete-text' => "'''Las revisionis esborrás entovia apaicirán nel estorial la páhina, peru el su continiu nu sedrá acesibri pal púbricu.'''

El restu e çahorilis desti güiqui sí tendrán premisu pa visoreal el continiu açonchau, i revertil el esborrau si es mestel, a nu sel que los alministraoris del güiqui crein una restrición aicional.",
'revdelete-legend' => 'Establecel restricionis de visibiliá',
'revdelete-hide-text' => 'Açonchal el testu la revisión',
'revdelete-hide-image' => 'Açonchal el continiu el archivu',
'revdelete-hide-name' => 'Açonchal ación i ohetivu',
'revdelete-hide-comment' => 'Açonchal síntesis la eición',
'revdelete-hide-user' => 'Açonchal nombri d´usuáriu/IP el eitol',
'revdelete-hide-restricted' => 'Tamién aprical estus tarugus a los çahorilis i atarugal esta entrihazi',
'revdelete-radio-set' => 'Sí',
'revdelete-radio-unset' => 'Nu',
'revdelete-suppress' => 'Esborral tamién los datus los çahorilis',
'revdelete-unsuppress' => 'Esborral restricionis enas revisionis arrecuperás',
'revdelete-log' => 'Razón:',
'revdelete-submit' => 'Aprical a {{PLURAL:$1|la revisión aseñalá|las revisionis aseñalás}}',
'revdelete-success' => "'''Visibiliá revisionis chambá.'''",
'logdelete-success' => "'''Visibiliá d'eventus chambá.'''",
'revdel-restore' => 'Chambal visibiliá',
'pagehist' => 'Estorial la páhina',
'deletedhist' => 'Estorial esborrau',
'revdelete-edit-reasonlist' => 'Eital razonis del esborrau',

# Suppression log
'suppressionlogtext' => 'Embahu se muestra una lista colos esborraus i tarugus mas nuevus, encruyendu conteniu açonchau polos çahorilis. Guipai la [[Special:IPBlockList|lista e tarugus a IP]] pa visoreal una lista colos tarugus ativus atualmenti.',

# History merging
'mergehistory' => 'Uñifical el estorial las páhinas',
'mergehistory-header' => "Esta páhina te premiti mestural las revisionis el estorial duna páhina huenti nuna nueva páhina.
Asigurati e qu'esti chambu mantenga la continuiá el estorial la páhina.",
'mergehistory-box' => 'Uñifical las revisionis las dos páhinas:',
'mergehistory-from' => 'Páhina e cóigu huenti:',
'mergehistory-into' => 'Páhina e destinu:',
'mergehistory-list' => "Estorial d'eicionis uñificabri",
'mergehistory-merge' => "Las siguientis revisionis de [[:$1]] puein mesturalsi en [[:$2]]. Gasta la coluna e botonis d'ocionis pa mestural las revisionis criás en i hata la ora especificá. Dati cuenta e que gastandu los atihus de navegación s'esborran las fechas aseñalás nesta coluna.",
'mergehistory-go' => 'Muestral eicionis uñificabris',
'mergehistory-submit' => 'Uñifical revisionis',
'mergehistory-empty' => 'Nu es posibri uñifical denguna revisión.',
'mergehistory-success' => "S'án mesturau $3 {{PLURAL:$3|revisión|revisionis}} de [[:$1]] en [[:$2]].",
'mergehistory-fail' => 'Nu es posibri uñifical los estorialis. Pol favol, compreba la páhina i los parámetrus de tiempu.',
'mergehistory-no-source' => 'La páhina huenti $1 nu desisti.',
'mergehistory-no-destination' => 'La páhina e destinu $1 nu desisti.',
'mergehistory-invalid-source' => 'La páhina huenti ebi tenel un entítulu premitiu.',
'mergehistory-invalid-destination' => 'La páhina e destinu ebi tenel un entítulu premitiu.',
'mergehistory-autocomment' => 'Mesturau [[:$1]] en [[:$2]]',
'mergehistory-comment' => 'Mesturau [[:$1]] en [[:$2]]: $3',
'mergehistory-reason' => 'Razón:',

# Merge log
'mergelog' => 'Rustrihu e fusionis',
'pagemerge-logentry' => "S'á uñificau [[$1]] en [[$2]] (hata la revisión $3)",
'revertmerge' => 'Desuñifical',
'mergelogpagetext' => "Embahu se muestra una lista colas úrtimas uñificacionis d'estorialis.",

# Diffs
'history-title' => 'Estorial de revisionis de "$1"',
'lineno' => 'Línia $1:',
'compareselectedversions' => 'Comparal velsionis aseñalás',
'editundo' => 'esjazel',
'diff-multi' => '(Nu se {{PLURAL:$1|muestra una revisión entelmeya|muestran $1 revisionis entelmeyas}}.)',

# Search results
'searchresults' => 'Landeal resurtaus',
'searchresults-title' => 'Landeal resurtaus pa "$1"',
'searchresulttext' => 'Pa mas enhormación al tentu landeal en {{SITENAME}}, vaiti a [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'As landeau \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tolos artículus que prencipian pol "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tolos artículus que atihan a "$1"]])',
'searchsubtitleinvalid' => "Landeasti '''$1'''",
'titlematches' => 'Conciéncias con el entítulu el artículu',
'notitlematches' => 'Nu ai artículus llamaus asina',
'textmatches' => 'Conciéncias con el testu el artículu',
'notextmatches' => 'Nu desistin conciéncias con el testu el artículu',
'prevn' => '{{PLURAL:$1|$1 anterioris}}',
'nextn' => '{{PLURAL:$1|$1 siguientis}}',
'prevn-title' => 'Anterioris $1 {{PLURAL:$1|resurtau|resurtaus}}',
'nextn-title' => 'Siguientis $1 {{PLURAL:$1|resurtau|resurtaus}}',
'shown-title' => 'Muestral $1 {{PLURAL:$1|resurtau|resurtaus}} pol página',
'viewprevnext' => 'Vel ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Ocionis de landeu',
'searchmenu-new' => "'''Crial el artículu \"[[:\$1]]\"'''",
'searchprofile-images' => 'Murtimeya',
'searchprofile-everything' => 'Tó',
'searchprofile-advanced' => 'Avançau',
'searchprofile-articles-tooltip' => 'Landeal en $1',
'searchprofile-project-tooltip' => 'Landeal en $1',
'searchprofile-images-tooltip' => 'Landeal archivus',
'searchprofile-everything-tooltip' => 'Landeal tol conteniu (encruyendu carabas)',
'search-result-size' => '$1 ({{PLURAL:$2|1 letra|$2 letras}})',
'search-result-score' => 'Emportáncia: $1%',
'search-redirect' => '(rederihil $1)',
'search-section' => '(seción $1)',
'search-suggest' => 'Quieis izil: $1',
'search-interwiki-caption' => 'Proyeutus helmanus',
'search-interwiki-default' => '$1 resurtaus:',
'search-interwiki-more' => '(más)',
'searchrelated' => 'relacionau',
'searchall' => 'tó',
'showingresults' => "Embahu se {{PLURAL:$1|muestra '''1''' resurtau qu'esmiença|muestran hata '''$1''' resurtaus qu'esmiençan}} pol #'''$2'''.",
'showingresultsnum' => "Embahu se {{PLURAL:$3|muestra '''1''' resurtau qu'esmiença|muestran'''$3''' resurtaus qu'esmiençan}} pol #'''$2'''.",
'nonefound' => "'''Nota''': Solu se busca en angunus espacius de nombris pol defetu. Preba a escrebil el prefihu ''all:'' nel tu landeu pa landeal tol conteniu (encruyendu carabas, prantillas...), u gasta el espaciu de nombri deseau cumu prefihu.",
'powersearch' => 'Landeal',
'powersearch-legend' => 'Landeu avançau',
'powersearch-ns' => 'Landeal en espaciu e nombris:',
'powersearch-redir' => 'Listal redirecionis',
'powersearch-field' => 'Landeal',
'search-external' => 'Landeu estelnu',
'searchdisabled' => 'Los landeus en {{SITENAME}} están temporalmenti desativaus. Mentris tantu, pueis landeal meyanti landerus esternus, inque ten en cuenta que los sus éndicis concernientis a {{SITENAME}} puein nu estal atualizaus.',

# Preferences page
'preferences' => 'Preferéncias',
'mypreferences' => 'Las mis preferéncias',
'prefs-edits' => "Númiru d'eicionis:",
'prefsnologin' => "Nu t'alcuentras rustriu",
'prefsnologintext' => 'Ebis estal [[Special:UserLogin|rustriu]] pa chambal las tus preferéncias.',
'changepassword' => 'Chambal consínia',
'prefs-skin' => 'Aparéncia',
'skin-preview' => 'Previsoreal',
'datedefault' => 'Sin preferéncias',
'prefs-datetime' => 'Fecha i ora',
'prefs-personal' => 'Datus el usuáriu',
'prefs-rc' => 'Úrtimus chambus',
'prefs-watchlist' => 'Lista e seguimientu',
'prefs-watchlist-days' => 'Máisimu númeru e dias a muestral ena lista e seguimientu:',
'prefs-watchlist-days-max' => 'Maximum $1 {{PLURAL:$1|day|days}}',
'prefs-watchlist-edits' => 'Númeru máisimu e chambus a muestral ena lista e seguimientu umentá:',
'prefs-misc' => 'Bandallu (una mihina e tó)',
'prefs-resetpass' => 'Escambial consínia',
'saveprefs' => 'Emburacal',
'resetprefs' => 'Esborral los chambus nu emburacaus',
'prefs-editing' => 'Eitandu',
'rows' => 'Filas:',
'columns' => 'Colunas:',
'searchresultshead' => 'Landeal',
'resultsperpage' => 'Resurtaus pol páhina:',
'stub-threshold' => 'Arrayu superiol pa consieral cumu <a href="#" class="stub">atihu a prencipiu</a> (bytes):',
'recentchangesdays' => 'Númeru e dias a muestral en "úrtimus chambus":',
'recentchangesdays-max' => 'Máissimu $1 {{PLURAL:$1|dia|dias}}',
'recentchangescount' => 'Númeru d´eicionis a muestral en "úrtimus chambus":',
'savedprefs' => 'S´an emburacau las tus preferéncias.',
'timezonelegend' => 'Zona orária',
'localtime' => 'Ora local',
'timezoneoffset' => 'Deferéncia oraria¹:',
'servertime' => 'Ora del sirviol:',
'guesstimezone' => 'Estrael la ora el escrucaol',
'timezoneregion-africa' => 'África',
'timezoneregion-america' => 'América',
'timezoneregion-antarctica' => 'Antáltia',
'timezoneregion-arctic' => 'Álticu',
'timezoneregion-asia' => 'Ásia',
'timezoneregion-atlantic' => 'Océanu Alánticu',
'timezoneregion-australia' => 'Austrália',
'timezoneregion-europe' => 'Uropa',
'timezoneregion-indian' => 'Océanu Índicu',
'timezoneregion-pacific' => 'Océanu Pacíficu',
'allowemail' => 'Premitil que m´envíin emails otrus usuárius',
'prefs-searchoptions' => 'Ocionis de landeu',
'prefs-namespaces' => 'Espáciu nombris',
'defaultns' => 'Landeal nestus "espacius de nombris" pol defeutu:',
'default' => 'defeutu',
'prefs-files' => 'Archivus',
'prefs-custom-css' => 'CSS pressonalizau',
'prefs-custom-js' => 'JS pressonalizau',
'youremail' => 'Email:',
'username' => "Nombri d'usuáriu:",
'uid' => "ID d'usuáriu:",
'prefs-memberingroups' => 'Miembru de {{PLURAL:$1|grupu|groupus}}:',
'yourrealname' => 'Nombri verdaeru:',
'yourlanguage' => 'Palra:',
'yourvariant' => 'Varianti:',
'yournick' => 'Moti:',
'badsig' => 'Nu se premiti esa firma; compreba las etiquetas HTML.',
'badsiglength' => 'El tu moti es mu largu.
Ebi tenel menus de $1 {{PLURAL:$1|caratel|carateris}}.',
'yourgender' => 'Héneru:',
'gender-unknown' => 'Nu especificau',
'gender-male' => 'Ombri',
'gender-female' => 'Mujel',
'email' => 'Email',
'prefs-help-realname' => "El nombri rial es ocional, peru nel chascu en que lo escribas, se gastará p'atribuilti el tu labutu.",
'prefs-help-email' => "Es ocional escrebil el tu email, peru es mestel pa crial una nueva consínia si t'orvias de l'antigua.
Amás premiti qu'otrus ussuárius contatin contigu pol mé la tu página d'ussuáriu u caraba, sin sel mestel muestral la tu entiá.",
'prefs-help-email-required' => 'Es mestel la direción email.',

# User rights
'userrights' => "Alministral premisus d'usuárius",
'userrights-lookup-user' => "Alministral grupus d'usuárius",
'userrights-user-editname' => 'Escrebi un nombri d´usuáriu:',
'editusergroup' => "Eital grupus d'usuárius",
'editinguser' => "Chambandu los derechus del usuáriu '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => "Eital grupus d'usuárius",
'saveusergroups' => "Emburacal grupus d'usuárius",
'userrights-groupsmember' => 'Miembru e:',
'userrights-groups-help' => "Pueis chambal los grupus enos qu'está esti usuáriu.
* Un cuairu aseñalau endica qu'el usuáriu está nesi grupu.
* Un cuairu sin aseñalal endica qu'el usuáriu nu está nesi grupu.
* Una * endica que nu pudrás esborralu del grupu una vezi lo aigas añiiu, u vice versa.",
'userrights-reason' => 'Razón:',
'userrights-no-interwiki' => 'Nu tienis premisu pa eital los derechus los usuárius en otras güiquis.',
'userrights-nodatabase' => 'La basi e datus $1 nu desisti, u nu es local.',
'userrights-nologin' => "Ebis [[Special:UserLogin|rustrilti]] con una cuenta d'alministraol pa puel asinal derechus a los usuárius.",
'userrights-notallowed' => "Nu tienis los permisus nesezárius p'asinal derechus a los usuárius.",
'userrights-changeable-col' => 'Grupus que pueis chambal',
'userrights-unchangeable-col' => 'Grupus que nu pueis chambal',

# Groups
'group' => 'Grupu:',
'group-user' => 'Usuárius',
'group-autoconfirmed' => 'Usuárius autuconfirmaus',
'group-bot' => 'Bots',
'group-sysop' => 'Çahorilis',
'group-bureaucrat' => 'Alministraoris',
'group-all' => '(tó)',

'group-user-member' => '{{GENDER:$1|Usuáriu}}',
'group-autoconfirmed-member' => 'Usuáriu autuconfirmau',
'group-bot-member' => 'Bot',
'group-sysop-member' => 'Çahoril',
'group-bureaucrat-member' => 'Alministraol',

'grouppage-user' => '{{ns:project}}:Usuárius',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuárius autuconfirmaus',
'grouppage-bot' => '{{ns:project}}:Bots',
'grouppage-sysop' => '{{ns:project}}:Çahorilis',
'grouppage-bureaucrat' => '{{ns:project}}:Alministraoris',

# Rights
'right-read' => 'Leyel páhinas',
'right-edit' => 'Eital páhinas',
'right-createpage' => 'Crial páhinas (que nu seyan carabas)',
'right-createtalk' => 'Crial carabas',
'right-createaccount' => "Crial cuentas d'usuáriu nuevas",
'right-minoredit' => 'Aseñalal eicionis cumu chiqueninas',
'right-move' => 'Mual páhinas',
'right-movefile' => 'Mual archivus',
'right-suppressredirect' => 'Nu crial una redireción nel antigu asiahamientu la páhina cuandu se mui.',
'right-upload' => 'Empuntal archivus',
'right-reupload' => 'Sobriescrebil un archivu ya desistenti',
'right-reupload-own' => 'Sobriescrebil un archivu empuntau pol mí mesmu',
'right-upload_by_url' => 'Empuntal un archivu dendi una direción URL',
'right-delete' => 'Esborral páhinas',
'right-bigdelete' => 'Esborral páhinas con grandis estorialis',
'right-browsearchive' => 'Landeal páhinas esborrás',
'right-undelete' => 'Arrecuperal una páhina',
'right-suppressionlog' => 'Guipal rustrijus privaus',
'right-import' => 'Emporteal páginas dendi otras wikis',
'right-importupload' => 'Emporteal páginas dendi un archivu empuntau',
'right-mergehistory' => 'Mestural el estorial de dambas las dos páginas',
'right-userrights' => 'Eital los derechus de tolos usuárius',
'right-siteadmin' => 'Atarugal i desatarugal la basi e datus',

# Special:Log/newusers
'newuserlogpage' => 'Rustrihu de nuevus usuárius',

# User rights log
'rightslog' => 'Rustrihu e derechus de los usuárius',
'rightslogtext' => 'Esti es un rustrihu e chambus enus derechus los usuárius.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'leyel esta página',
'action-edit' => 'eital esta páhina',
'action-createpage' => 'crial páginas',
'action-createtalk' => 'crial carabas',
'action-createaccount' => "crial esta cuenta d'usuáriu",
'action-minoredit' => 'asseñalal esta eición cumu chiquenina',
'action-move' => 'mual esta página',
'action-move-rootuserpages' => "mual páginas d'ussuáriu raís",
'action-movefile' => 'mual archivu',
'action-upload' => 'empuntal archivu',
'action-upload_by_url' => 'empuntal archivu dendi una URL',
'action-delete' => 'esborral esta página',
'action-deleterevision' => 'esborral esta revisión',
'action-browsearchive' => 'landeal páginas esborrás',
'action-undelete' => 'arrecuperal esta página',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|chambu|chambus}}',
'recentchanges' => 'Úrtimus chambus',
'recentchanges-legend' => 'Ocionis enos úrtimus chambus',
'recentchanges-summary' => 'Sigui los úrtimus chambus d´esti güiqui nesta páhina.',
'recentchanges-feed-description' => 'Sigui los úrtimus chambus nel güiqui nesti feed.',
'rcnote' => "Embahu se {{PLURAL:$1|muestra '''1''' chambu|muestran los úrtimus '''$1''' chambus}} {{PLURAL:$2|dendi ayel|enus úrtimus '''$2''' dias}}, de $4 a las $5.",
'rcnotefrom' => "Embahu se muestran los chambus hechus dendi el '''$2''' (hata el '''$1''').",
'rclistfrom' => 'Muestral los chambus hechus dendi el $1',
'rcshowhideminor' => '$1 eicionis chiqueninas',
'rcshowhidebots' => '$1 bots',
'rcshowhideliu' => '$1 usuárius rustrius',
'rcshowhideanons' => '$1 usuárius anónimus',
'rcshowhidepatr' => '$1 eicionis patrullás',
'rcshowhidemine' => '$1 las mis eicionis',
'rclinks' => 'Muestral los $1 úrtimus chambus enus $2 úrtimus dias<br />$3',
'diff' => 'def',
'hist' => 'estor',
'hide' => 'Açonchal',
'show' => 'Muestral',
'minoreditletter' => 'c',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usuáriu está|usuárius están}} vehilandu]',
'rc_categories' => 'Arrayal a categorias (separás pol "|")',
'rc_categories_any' => 'Cualisquiá',
'newsectionsummary' => '/* $1 */ seción nueva',
'rc-enhanced-expand' => 'muestral detallis (es mestel JavaScript)',
'rc-enhanced-hide' => 'Açonchal detallis',

# Recent changes linked
'recentchangeslinked' => 'Chambus relacionaus',
'recentchangeslinked-feed' => 'Chambus relacionaus',
'recentchangeslinked-toolbox' => 'Chambus relacionaus',
'recentchangeslinked-title' => 'Chambus relacionaus con "$1"',
'recentchangeslinked-summary' => "Nesta páhina especial ai una lista colos úrtimus chambus en páhinas qu'están atihás dendi una páhina concreta (u en miembrus de una detelminá categoria).
Las páhinas de la tu [[Special:Watchlist|lista e seguimientu]] están en '''negrina'''.",
'recentchangeslinked-page' => 'Nombri la páhina:',
'recentchangeslinked-to' => 'Muestral chambus a páginas que atigin Show changes to pages linked to the given page instead',

# Upload
'upload' => 'Empuntal archivu',
'uploadbtn' => 'Empuntal archivu',
'reuploaddesc' => 'Cancelal el empuntu i gorvel al hormuláriu.',
'uploadnologin' => 'Nu estás rustriu',
'uploadnologintext' => 'Ebis estal [[Special:UserLogin|rustriu]]
pa empuntal archivus.',
'upload_directory_read_only' => "Nu puei escrebilsi nel diretoriu d'empuntu ($1) el sirviol.",
'uploaderror' => 'Marru d´empuntu',
'uploadtext' => "Gasta el hormuláriu d'embahu pa empuntal archivus, pa vel u landeal imahin ya empuntás vaiti pala [[Special:FileList|lista d'archivus empuntaus]]. Tantu los archivus empuntaus cumu los esborraus se rustrin nel [[Special:Log/upload|rustrihu d'empuntu]].

P'añiil la imahin nuna páhina, gasta el atihu el hormuláriu
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki>''' u
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' p'atihal diretamenti al archivu.",
'upload-permitted' => "Crasis d'archivus premitias: $1.",
'upload-preferred' => "Crasis d'archivus preferias: $1.",
'upload-prohibited' => "Crasis d'archivus atarugás: $1.",
'uploadlog' => "rustrihu d'empuntu",
'uploadlogpage' => "Rustrihu d'empuntu",
'uploadlogpagetext' => 'Embaju se muestra una lista colos úrtimus archivus empuntaus.
Vai al [[Special:NewFiles|correol de nuevus archivus]] pa echali una guipaina de holma mas estética.',
'filename' => 'Nombri el archivu',
'filedesc' => 'Síntesis',
'fileuploadsummary' => 'Síntesis:',
'filereuploadsummary' => 'Chambus del archivu:',
'filestatus' => 'Estau el Copyright:',
'filesource' => 'Coigu huenti:',
'uploadedfiles' => 'Archivus empuntaus',
'ignorewarning' => 'Inoral el avisihu i emburacal el achivu',
'ignorewarnings' => 'Inoral tolos avisihus',
'minlength1' => 'Los nombris d´archivus ebin tenel al menus una letra.',
'illegalfilename' => 'El nombri "$1" tiini carateris que nu están premitius enus entítulus de páhinas. Pol favol, ponli otru nombri al archivu i preba a empuntalu e nuevu.',
'badfilename' => 'S´á chambau el nombri el archivu a "$1".',
'filetype-badmime' => 'Nu está premitiu empuntal los archivus MIME type "$1".',
'filetype-unwanted-type' => "'''\".\$1\"''' es una crassi d'archivu nu deseá. {{PLURAL:\$3|La crassi d'archivu preferia es|Las crassis d'archivus preferias son}} \$2.",
'filetype-banned-type' => "'''\".\$1\"''' nu es una crassi d'archivu premitia.
{{PLURAL:\$3|La crassi d'archivu premitia es|Las crassis d'archivus premitias son}} \$2.",
'filetype-missing' => 'El archivu nu tiini estensión (cumu ".jpg").',
'large-file' => 'Es recomendabri que los archivus nu seyan mayoris de $1; esti archivu ocupa $2.',
'largefileserver' => 'Esti archivu es mas grandi que lo premitiu pol el sirviol.',
'emptyfile' => "El archivu qu'as ententau empuntal paci estal vaciu; pol favol, compreba que realmenti se trata el archivu que querias empuntal.",
'fileexists' => 'Ya desisti un archivu con esi nombri.
Pol favol, compreba que realmenti quieis chambal el archivu <strong>[[:$1]]</strong>.
[[$1|thumb]]',
'filepageexists' => 'Ya desisti un artículu con esi nombri, pol favol, compreba <strong>[[:$1]]</strong> si nu estás siguru e querel chambalu.',
'fileexists-extension' => 'Ya desisti un archivu con un nombri paiciu: [[$2|thumb]]
* Nombri el archivu empuntau: <strong>[[:$1]]</strong>
* Nombri el archivu ya desistenti: <strong>[[:$2]]</strong>
Pol favol, lihi un nombri deferenti.',
'fileexists-thumbnail-yes' => "El archivu paci sel una imahin chiquenina ''(cuairu)''. [[$1|thumb]]
Pol favol, compreba qu'el archivu <strong>[[:$1]]</strong> nu es la mesma imahin.
Nel chascu en que huera la mesm imahin (inque seya en grandi) nu es mestel qu'empuntis el tu archivu.",
'file-thumbnail-no' => "El nombri el archivu esmiença pol <strong>$1</strong>. Paci sel una imahin pequeña ''(cuairu)''.
Si tiinis la imahin cola resolución orihinal, empúntala, si nu, pol favol, chamba el nombri del archivu.",
'fileexists-forbidden' => 'Ya dessisti un archivu con esti nombri, i nu es possibri sobriescribilu.
Si entovia quieris empuntal el archivu, pol favol, güervi atrás i empuntalu con otru nombri. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Ya dessisti un archivu con esti nombri nel repossitóriu d'archivus compartius;
Si acontinas queriendu empuntal el archivu, vai alatrás i escambia el nombri el archivu.
 [[File:$1|thumb|center|$1]]",
'file-exists-duplicate' => 'Esti archivu es un dupricau {{PLURAL:$1|el siguienti archivu|los siguientis archivus}}:',
'uploadwarning' => 'Avisu d´empuntu',
'savefile' => 'Emburacal archivu',
'uploadedimage' => 'emputau "[[$1]]"',
'overwroteimage' => 'empuntá una nueva velsión de "[[$1]]"',
'uploaddisabled' => 'Empuntus desativaus',
'uploaddisabledtext' => "El empuntu d'archivus está desativau.",
'uploadscripted' => 'Esti archivu contieni script u cóigu HTML que puei sel mal entelpretau pol un escrucaol.',
'uploadvirus' => 'El archivu tiini un virus! Detallis: $1',
'sourcefilename' => 'Nombri orihinal:',
'destfilename' => 'Nombri e destinu:',
'upload-maxfilesize' => 'Grandol máisimu el archivu: $1',
'watchthisupload' => 'Vegilal esti archivu',
'filewasdeleted' => 'Un archivu con el mesmu nombri ya hue empuntau i alogu esborrau. Eberias comprebal el $1 enantis de gorvel a empuntalu.',
'filename-bad-prefix' => "El nombri del archivu qu'estás empuntandu esmiença pol '''\"\$1\"''', es izil, es un nombri nu descritivu (típicu nombri dau autumaticamenti pol cámaras dehitalis). Pol favol, chamba el nombri del tu archivu.",
'upload-success-subj' => 'Empuntu satisfatoriu',

'upload-proto-error' => 'Protocolu encorretu',
'upload-proto-error-text' => 'El empuntu remotu prehisa e "URLs" qu´esmiencin pol <code>http://</code> u <code>ftp://</code>.',
'upload-file-error' => 'Marru entelnu',
'upload-file-error-text' => "Á aconteciu un marru entelnu cuandu s'ententaba crial un ficheru temporal nel sirviol.
Pol favol, contauta con angún [[Special:ListUsers/sysop|çajoril]].",
'upload-misc-error' => "Marru d'empuntu andarriu",
'upload-misc-error-text' => 'Marru aconteciu al empuntal el archivu.
Pol favol, compreba que la URL es vália i acesibri i enténtalu otra vedi.
Si el pobrema acontina, contauta con un [[Special:ListUsers/sysop|çahoril]].',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Nu se puei dil a la URL',
'upload-curl-error6-text' => 'Nu á siu posibri dil a la URL.',
'upload-curl-error28' => "Tiempu d'empuntu esceiu",
'upload-curl-error28-text' => "La páhina está tardandu abondu en contestal. Pol favol, compreba qu'el sirviol hunciona, aspera un pocu i güervi a ententalu. Quiciás prefieras ententalu n'otru momentu con menus carga.",

'license' => 'Licéncia:',
'license-header' => 'Licéncia:',
'nolicense' => 'Dengunu selecionau',
'license-nopreview' => '(Nu se puei previsoreal)',
'upload_source_url' => ' (una URL vália i acesibri)',
'upload_source_file' => ' (un archivu nel tu ordinaol)',

# Special:ListFiles
'listfiles-summary' => 'Esta páhina especial muestra tolos archivus empuntaus.
Pol defetu los úrtimus archivus empuntaus se muestran ena parti arta la lista.
Pursa nel entítulu la coluna pa chambal el ordin.',
'listfiles_search_for' => 'Landeal pol nombri la imahin:',
'imgfile' => 'archivu',
'listfiles' => 'Lista d´archivus',
'listfiles_date' => 'Fecha',
'listfiles_name' => 'Nombri',
'listfiles_user' => 'Usuáriu',
'listfiles_size' => 'Grandol',
'listfiles_description' => 'Descrición',
'listfiles_count' => 'Velsionis',

# File description page
'file-anchor-link' => 'Archivu',
'filehist' => 'Estorial el archivu',
'filehist-help' => 'Pursa nuna fecha/ora pa vel cumu era el archivu nesi momentu.',
'filehist-deleteall' => 'esborral tós',
'filehist-deleteone' => 'esborral',
'filehist-revert' => 'revertil',
'filehist-current' => 'atual',
'filehist-datetime' => 'Fecha/Ora',
'filehist-thumb' => 'Cuairu',
'filehist-thumbtext' => 'cuairu pala velsión $1',
'filehist-nothumb' => 'Sin cuairu',
'filehist-user' => 'Usuáriu',
'filehist-dimensions' => 'Miias',
'filehist-filesize' => 'Grandol el archivu',
'filehist-comment' => 'Comentáriu',
'imagelinks' => 'Atihus',
'linkstoimage' => '{{PLURAL:$1|El siguienti artículu atiha|Los siguientis $1 artículus atihan}} a esti archivu:',
'nolinkstoimage' => 'Nu ai denguna páhina qu´atihi a esti archivu.',
'morelinkstoimage' => 'Guipal [[Special:WhatLinksHere/$1|mas atijus]] a esti archivu.',
'sharedupload' => 'Esti archivu procei de $1 i puei gastalsi dendi otrus proyeutus.',
'uploadnewversion-linktext' => 'Empuntal una nueva velsión d´esti archivu',
'shared-repo-from' => 'dendi $1',
'shared-repo' => 'un repossitóriu compartiu',

# File reversion
'filerevert' => 'Revertil $1',
'filerevert-legend' => 'Revertil archivu',
'filerevert-intro' => "Estás revirtiendu '''[[Media:$1|$1]]''' a la [$4 velsión del $3 a las $2].",
'filerevert-comment' => 'Comentáriu:',
'filerevert-defaultcomment' => 'Revertiu a la velsión de $2, $1',
'filerevert-submit' => 'Revertil',
'filerevert-success' => "S'á revertiu '''[[Media:$1|$1]]''' a [$4 velsión de $3, $2].",
'filerevert-badversion' => "Nu desisti denguna velsión local prévia d'esti archivu cola fecha aseñalá.",

# File deletion
'filedelete' => 'Esborral $1',
'filedelete-legend' => 'Esborral archivu',
'filedelete-intro' => "Estás esborrandu '''[[Media:$1|$1]]''' untu al su estorial.",
'filedelete-intro-old' => "Estás esborrandu la velsón de '''[[Media:$1|$1]]''' del [$4 $3 a las $2].",
'filedelete-comment' => 'Razón:',
'filedelete-submit' => 'Esborral',
'filedelete-success' => "S´á esborrau '''$1'''.",
'filedelete-success-old' => "Á siu esborrá la velsión de '''[[Media:$1|$1]]''' del $2 a las $3.</span>",
'filedelete-nofile' => "'''$1''' nu dessisti.",
'filedelete-nofile-old' => "Nu desisti una velsión archivá e '''$1''' con esas caraterísticas.",
'filedelete-otherreason' => 'Razón adicional:',
'filedelete-reason-otherlist' => 'Otra razón',
'filedelete-reason-dropdown' => "*Razonis frecuentis d'esborrau
** Violación del Copyright
** Archivu dupricau",
'filedelete-edit-reasonlist' => 'Eital razonis del esborrau',

# MIME search
'mimesearch' => 'Landeu MIME',
'mimesearch-summary' => "Esta páhina ativa el filtrau d'archivus en hunción la su crasi MIME. Entrá: contenttype/subtype, p.sab. <code>image/jpeg</code>.",
'mimetype' => 'Tipu MIME:',
'download' => 'descargal',

# Unwatched pages
'unwatchedpages' => 'Páhinas sin vehilal',

# List redirects
'listredirects' => 'Lista e redirecionis',

# Unused templates
'unusedtemplates' => 'Prantillas abaldonás',
'unusedtemplatestext' => "Esta páhina lista tolas páhinas del espaciu e nombris de prantillas que nu están incluias n'otras páhinas. Alcuérdati e comprebal otrus atihus a las prantillas enantis d'esborralas.",
'unusedtemplateswlh' => 'otrus atihus',

# Random page
'randompage' => 'Cualisquiel páhina',
'randompage-nopages' => 'Nu ai páhinas nesti "espaciu e nombris".',

# Random redirect
'randomredirect' => 'Cualisquiel redireción',
'randomredirect-nopages' => 'Nu dessistin redirecionis nel espáciu nombris "$1".',

# Statistics
'statistics' => 'Estaísticas',
'statistics-header-pages' => 'Estaísticas de la página',
'statistics-header-edits' => "Estaísticas d'eición",
'statistics-header-views' => 'Guipal estaísticas',
'statistics-header-users' => 'Estaísticas d´usuáriu',
'statistics-pages' => 'Páginas',
'statistics-pages-desc' => 'Tolas páginas nel wiki, encruyendu carabas, redirecionis...',
'statistics-files' => 'Archivus empuntaus',
'statistics-users' => '[[Special:ListUsers|Usuárius rustrius]]',
'statistics-users-active' => 'Ussuárius ativus',
'statistics-users-active-desc' => 'Ussuárius que aigan hechu anguna ación {{PLURAL:$1|nel úrtimu dia|enus úrtimus $1 dias}}',
'statistics-mostpopular' => 'Páhinas mas visoreás',

'doubleredirects' => 'Redirecionis dobris',

'brokenredirects' => 'Redirecionis eschangás',
'brokenredirectstext' => 'Las siguientis redirecionis atijan a páginas que nu dessistin:',
'brokenredirects-edit' => 'eital',
'brokenredirects-delete' => 'esborral',

'withoutinterwiki' => 'Páhinas sin atihus "EntelGüiqui"',
'withoutinterwiki-summary' => 'Las siguientis páhinas nu atihan a velsionis en otras palras:',
'withoutinterwiki-legend' => 'Prefihu',
'withoutinterwiki-submit' => 'Muestral',

'fewestrevisions' => 'Páhinas con menus revisionis',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories' => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks' => '$1 {{PLURAL:$1|atihu|atihus}}',
'nmembers' => '$1 {{PLURAL:$1|miembru|miembrus}}',
'nrevisions' => '$1 {{PLURAL:$1|revisión|revisionis}}',
'nviews' => '$1 {{PLURAL:$1|vesita|vesitas}}',
'specialpage-empty' => 'Esta páhina está vacia.',
'lonelypages' => 'Páhinas güérfanas',
'lonelypagestext' => 'Las siguientis páginas nu están atijás (dendi otras páginas) ena {{SITENAME}}.',
'uncategorizedpages' => 'Páhinas sin categorizal',
'uncategorizedcategories' => 'Categorias sin categorizal',
'uncategorizedimages' => 'Imahin sin categoriçal',
'uncategorizedtemplates' => 'Prantillas sin categoria',
'unusedcategories' => 'Categorias abaldonás',
'unusedimages' => 'Archivus abaldonaus',
'popularpages' => 'Páhinas polularis',
'wantedcategories' => 'Categorias deseás',
'wantedpages' => 'Páhinas deseás',
'wantedfiles' => 'Archivus nedessitaus',
'mostlinked' => 'Páhinas mas atihás',
'mostlinkedcategories' => 'Categorias mas atihás',
'mostlinkedtemplates' => 'Prantillas mas atihás',
'mostcategories' => 'Páhinas con mas categorias',
'mostimages' => 'Imahin mas atihás',
'mostrevisions' => 'Artículus con mas revisionis',
'prefixindex' => 'Páhinas pol prefihu',
'shortpages' => 'Páhinas cortas',
'longpages' => 'Páhinas largas',
'deadendpages' => 'Callehonis',
'deadendpagestext' => 'Las siguientis páhinas nu atihan a otras páhinas desti güiqui.',
'protectedpages' => 'Páhinas protehias',
'protectedpagestext' => 'Las siguientis páhinas nu se puein ni movel ni eital (están protehias)',
'protectedpagesempty' => 'Nu desisti denguna páhina protehia con estus parámetrus.',
'protectedtitles' => 'Entítulus protehius',
'protectedtitlestext' => "Los siguientis entítulus s'alcuentran atarugaus",
'protectedtitlesempty' => 'Ogañu nu desistin entítulus protehius con estus parámetrus.',
'listusers' => 'Lista d´usuárius',
'usercreated' => 'Criá el $1 a las $2',
'newpages' => 'Nuevas páhinas',
'newpages-username' => 'Nombri d´usuáriu:',
'ancientpages' => 'Páhinas mas antiguas',
'move' => 'Movel',
'movethispage' => 'Movel esta páhina',
'unusedimagestext' => "Pol favol, fíhati en qu'otras páhinas web puein atihal a una imahin con una URL direta, polo qu'están listás aquí, inque tengan un usu ativu.",
'unusedcategoriestext' => "Las siguientis categorias desistin, inque nu s'alcuentra denguna páhina/categoria en ellas.",
'notargettitle' => 'Dengún ohetivu',
'notargettext' => 'Nu as especificau una páhina ohetivu u un usuáriu sobri los que hazel esta hunción.',
'pager-newer-n' => '{{PLURAL:$1|1 mas recienti|$1 mas recientis}}',
'pager-older-n' => '{{PLURAL:$1|1 mas antigu|$1 mas antigus}}',

# Book sources
'booksources' => 'Huentis de librus',
'booksources-search-legend' => 'Landeal huentis de librus',
'booksources-go' => 'Dil',
'booksources-text' => "Embahu se muestra una lista d'atihus a páhinas que vendin librus usaus i nuevus, i ondi pueis alcuentral enhormación al tentu los librus qu'estás landeandu:",

# Special:Log
'specialloguserlabel' => 'Usuáriu:',
'speciallogtitlelabel' => 'Entítulu:',
'log' => 'Rustrihus',
'all-logs-page' => 'Tolos rustrijus púbricus',
'logempty' => 'Nu desistin elementus con esas condicionis nel rustrihu.',
'log-title-wildcard' => 'Landeal entítulus qu´esmiencin con esti testu',

# Special:AllPages
'allpages' => 'Tolas páhinas',
'alphaindexline' => '$1 a $2',
'nextpage' => 'Siguienti páhina ($1)',
'prevpage' => 'Páhina anteriol ($1)',
'allpagesfrom' => "Muestral páhinas qu'esmiencin pol:",
'allpagesto' => 'Muestral artículus que acabihin en:',
'allarticles' => 'Tolos artículus',
'allinnamespace' => 'Tolas páhinas (qu´estén en $1)',
'allnotinnamespace' => 'Tolas páhinas (que nu estén en $1)',
'allpagesprev' => 'Anterioris',
'allpagesnext' => 'Siguientis',
'allpagessubmit' => 'Dil',
'allpagesprefix' => 'Muestral páhinas con el prefihu:',
'allpages-bad-ns' => '{{SITENAME}} nu tieni el espaciu e nombris "$1".',

# Special:Categories
'categories' => 'Categorias',
'categoriespagetext' => 'Las siguientis categorias contienin artículus u archivus murtimeya.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',
'special-categories-sort-count' => 'ordenal pol cuenta',
'special-categories-sort-abc' => 'ordenal alfabeticamenti',

# Special:DeletedContributions
'deletedcontributions' => 'Contribucionis el usuáriu esborrás',
'deletedcontributions-title' => 'Contribucionis el usuáriu esborrás',

# Special:LinkSearch
'linksearch' => 'Atihus estelnus',
'linksearch-ns' => 'Espáciu nombris:',
'linksearch-ok' => 'Landeal',

# Special:ListUsers
'listusersfrom' => "Muestral usuárius qu'esmiencin pol:",
'listusers-submit' => 'Muestral',
'listusers-noresult' => 'Nu s´alcuentró dengún usuáriu.',

# Special:ListGroupRights
'listgrouprights' => "Derechus del grupu d'usuárius",
'listgrouprights-group' => 'Grupu',
'listgrouprights-rights' => 'Derechus',
'listgrouprights-helppage' => 'Help:Derechus del grupu',
'listgrouprights-members' => '(lista e miembrus)',
'listgrouprights-addgroup' => 'Añiil {{PLURAL:$2|grupu|grupus}}: $1',
'listgrouprights-removegroup' => 'Esborral {{PLURAL:$2|grupu|grupus}}: $1',
'listgrouprights-addgroup-all' => 'Añiil tolos grupus',
'listgrouprights-removegroup-all' => 'Esborral tolos grupus',

# Email user
'mailnologin' => 'Nu envial direción',
'mailnologintext' => 'Ebis estal [[Special:UserLogin|rutrau]]
i tenel una direción d´email correta enas tus [[Special:Preferences|preferéncias]]
pa envial correus a otrus usuárius.',
'emailuser' => 'Envial un email a esti usuáriu',
'emailpage' => 'E-mail el usuáriu',
'emailpagetext' => "Si esti usuáriu á escrebiu una direción email enas sus preferéncias, con el hormulariu d'embahu se l'enviará un mensahi.
La direción email qu'aigas escrebiu enas tus preferéncias apaicirá cumu remitenti el mensahi, d'esta horma, el destinatariu pudrá contestalti.",
'usermailererror' => 'El sistema e correu degorvió un marru:',
'defemailsubject' => 'E-mail de {{SITENAME}}',
'noemailtitle' => 'Nu ai direción d´e-mail',
'noemailtext' => "Esti usuáriu nu á escrebiu una direción email enas sus preferéncias, u tieni ativá la oción de nu recibil mensahis d'otrus usuárius.",
'nowikiemailtitle' => 'Emails nu premitius',
'nowikiemailtext' => 'Esti ussuáriu a ligiu nu recibil emails de otrus ussuárius.',
'email-legend' => 'Envial un email a otru ussuáriu de {{SITENAME}}',
'emailfrom' => 'De:',
'emailto' => 'A:',
'emailsubject' => 'Tema:',
'emailmessage' => 'Mensaji:',
'emailsend' => 'Envial',
'emailccme' => 'Envialmi una copia el mensahi.',
'emailccsubject' => 'Copia el tu mensahi a $1: $2',
'emailsent' => 'E-mail enviau',
'emailsenttext' => 'Se á enviau el tu mensahi pol e-mail.',

# Watchlist
'watchlist' => 'La mi lista e seguimientu',
'mywatchlist' => 'La mi lista e seguimientu',
'nowatchlist' => 'La tu lista e seguimientu está vacia.',
'watchlistanontext' => 'Pa vel u eital las entrás ena tu lista e seguimientu es mestel $1.',
'watchnologin' => 'Nu estás rustriu',
'watchnologintext' => 'Ebis [[Special:UserLogin|abril la tu cuenta]] pa puel hazel chambus ena tu lista e seguimientu.',
'addedwatchtext' => "S´á añiiu la páhina \"[[:\$1]]\" a la tu [[Special:Watchlist|lista e seguimientu]].
Los huturus chambus de la páhina i ena su caraba se muestrarán aquí,
i el su entítulu apaicirá en '''negrina''' ena [[Special:RecentChanges|lista d´úrtimus chambus]].

Si quieis ehal de vehilal la páhina, pursa sobri \"Ehal de vehilal\".",
'removedwatchtext' => 'La página "[[:$1]]" á siu esborrá de la tu [[Special:Watchlist|lista de seguimientu]].',
'watch' => 'Vegilal',
'watchthispage' => 'Vehilal esta páhina',
'unwatch' => 'Ehal de vehilal',
'unwatchthispage' => 'Ehal de vehilal',
'notanarticle' => 'Nu es un artículu',
'notvisiblerev' => 'La revisión á siu esborrá',
'watchlist-details' => '{{PLURAL:$1|$1 artículu|$1 artículus}} vehilaus (sin contal las carabas).',
'wlheader-enotif' => 'Se premitin notificacionis pol email.',
'wlheader-showupdated' => "Las páhinas que s'án emburacau dendi la úrtima vezi que las visoreasti son muestrás en '''negrina'''",
'watchmethod-recent' => 'comprebandu las úrtimas eicionis en páhinas vehilás',
'watchmethod-list' => 'Revisandu las páhinas vehilás en cata los úrtimus chambus',
'watchlistcontains' => 'Ai $1 {{PLURAL:$1|páhina|páhinas}} ena tu lista e seguimientu.',
'iteminvalidname' => "Pobrema con el artículu '$1', nombri nu premitiu...",
'wlnote' => "Embahu {{PLURAL:$1|es el úrtimu chambu|son los úrtimus '''$1''' chambus}} enas úrtimas {{PLURAL:$2|oras|'''$2''' oras}}.",
'wlshowlast' => 'Muestral úrtimus $1 oras $2 dias $3',
'watchlist-options' => 'Ocionis de la mi lista e seguimientu',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Vehilandu...',
'unwatching' => 'Abaldonandu la vehiláncia en...',

'enotif_mailer' => 'Notificaeru pol correu e {{SITENAME}}',
'enotif_reset' => 'Aseñalal tolas páhinas vesitás',
'enotif_impersonal_salutation' => 'usuáriu e {{SITENAME}}',
'enotif_lastvisited' => 'Vai pa $1 pa visoreal tolos chambus hechus dendi la tu úrtima vesita.',
'enotif_lastdiff' => 'Vai pa $1 pa visoreal esti chambu.',
'enotif_anon_editor' => 'usuáriu anónimu $1',
'enotif_body' => 'Estimau $WATCHINGUSERNAME,


S\'á $CHANGEDORCREATED el artículu $PAGETITLE (de {{SITENAME}}) el $PAGEEDITDATE, siendu el su autol  $PAGEEDITOR. Consurta la $PAGETITLE_URL pa leyel la nueva velsión.

$NEWPAGE

Síntesis el eitol: $PAGESUMMARY $PAGEMINOREDIT

Contatal con el eitol:
Email: $PAGEEDITOR_EMAIL
Güiqui: $PAGEEDITOR_WIKI

Nel chascu en que nu vesitis el artículu, nu se te hazrán mas notificacionis. Amas, pueis cancelal tolas notificacionis ena tu lista e seguimientu.

             Salús dendi {{SITENAME}}!!

--
Pa hazel chambus ena tu lista e seguimientu, vesita
{{canonicalurl:{{#special:EditWatchlist}}}}

Ayua la Güiquipeya:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'criá',
'changed' => 'chambau',

# Delete
'deletepage' => 'Esborral páhina',
'confirm' => 'Confirmal',
'excontent' => "el continiu era: '$1'",
'excontentauthor' => "el continiu era: '$1' (i el únicu contribuyenti hue '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "el continiu enantis de branquealu era: '$1'",
'exblank' => 'la páhina estaba vacia',
'delete-confirm' => 'Esborral "$1"',
'delete-legend' => 'Esborral',
'historywarning' => 'Avisu: La páhina que vas a esborral tieni un estorial:',
'confirmdeletetext' => "Vas a esborral una páhina/imahin i el su estorial de horma permanenti.
Pol favol, confirma que realmenti quieis hazel esu, qu'entiendis las consecuencias, i que lo hazis dalcuerdu cola
[[{{MediaWiki:Policy-url}}]].",
'actioncomplete' => 'Ación acabihá',
'deletedtext' => 'S\'á esborrau "$1" corretamenti.
Consurta $2 pa vel los úrtimus esborraus.',
'dellogpage' => 'Rustrihu d´esborrau',
'dellogpagetext' => 'Embahu se muestra una lista colos úrtimus esborraus.',
'deletionlog' => 'rustrihu d´esborrau',
'reverted' => 'Revertiu a la úrtima revisión',
'deletecomment' => 'Razón:',
'deleteotherreason' => 'Otras razonis:',
'deletereasonotherlist' => 'Otra razón',
'deletereason-dropdown' => "*Motivus mas frecuentis d'esborrau
** Pol solicitú el autol
** Violación el Copyright
** Vandalismu",
'delete-edit-reasonlist' => 'Eital razonis del esborrau',
'delete-warning-toobig' => "Esta páhina tieni un estorial d'eicionis grandi, mas de $1 revisionis. Esborralu puei causal pobremas enas operacionis la basi e datus de {{SITENAME}}; atua con cudiau.",

# Rollback
'rollback' => 'Revertil eicionis',
'rollback_short' => 'Revertil',
'rollbacklink' => 'revertil',
'rollbackfailed' => 'Marru revirtiendu',
'cantrollback' => 'Nu se puei eshazel la eición; el úrtimu colabutaol es el únicu autol d´esta páhina.',
'alreadyrolled' => 'Nu es posibri revertil la úrtima eición de [[:$1]], hecha pol [[User:$2|$2]] ([[User talk:$2|Caraba]]); alguien ya á eitau u revertiu la páhina.

La úrtima eición á siu hecha pol [[User:$3|$3]] ([[User talk:$3|Caraba]]).',
'editcomment' => "La síntesis la eición hue: \"''\$1''\".",
'revertpage' => 'Án siu revertias las eicionis de [[Special:Contributions/$2|$2]] ([[User talk:$2|Caraba]]); chambau a la úrtima velsión de [[User:$1|$1]]',
'rollback-success' => 'Revertias las eicionis de $1; chambau a la úrtima velsión de $2.',

# Edit tokens
'sessionfailure' => "Paci qu'ai un pobrema cola tu sesión; pol precaución
s'á cancelau l'ación solicitá. Pursa nel botón \"Atrás\" del
tu escrucaol pa cargal otra vezi la páhina i güervi a ententalu.",

# Protect
'protectlogpage' => 'Rustrihu e proteción',
'protectlogtext' => 'Embahu se muestra una lista cola proteción i desproteción la páhina. Pa mas enhormación, lei "[[Special:ProtectedPages|Esta páhina está protehia]]".',
'protectedarticle' => '"[[$1]]" protehiu',
'modifiedarticleprotection' => 'chambau el nivel de proteción a "[[$1]]"',
'unprotectedarticle' => '"[[$1]]" esprotehiu',
'protect-title' => 'Estableciendu nivel de proteción pa "$1"',
'prot_1movedto2' => '[[$1]] s´á moviu a [[$2]]',
'protect-legend' => 'Confirmal proteción',
'protectcomment' => 'Razón:',
'protectexpiry' => 'Acabiha:',
'protect_expiry_invalid' => 'La fecha e cauciá nu es correta.',
'protect_expiry_old' => 'La fecha e cauciá está nel pasau.',
'protect-text' => "Aquí pueis vel i chambal el nivel de proteción la páhina '''$1'''.",
'protect-locked-blocked' => "Nu pueis chambal los nivelis de proteción mentris estés atarugau. Velaquí las ocionis atulais la páhina '''$1''':",
'protect-locked-dblock' => "Nu se puein chambal los nivelis de proteción ebiu a un tarugu ativu ena basi e datus.
Velaquí las ocionis atualis la páhina '''$1''':",
'protect-locked-access' => "Nu tiinis los premisus nesezarius pa chambal los nivelis de proteción duna páhina.
Velaquí las ocionis atualis la páhina '''$1''':",
'protect-cascadeon' => "Esta páhina s'alcuetra atualmenti protehia polque está incluia {{PLURAL:$1|ena siguienti páhina, que tieni|enas siguientis páhinas, que tienin}} la proteción en cascá ativá. Pueis chambal el nivel de proteción desta páhina, peru ellu tendrá consecuencias en tola proteción en cascá.",
'protect-default' => 'Premitil a tolos usuárius',
'protect-fallback' => 'Es mestel el premisu "$1"',
'protect-level-autoconfirmed' => 'Atarugal a los nuevus usuárius anónimus',
'protect-level-sysop' => 'Solu çahorilis',
'protect-summary-cascade' => 'proteción en "cascá"',
'protect-expiring' => 'acabiha el $1 (UTC)',
'protect-expiry-indefinite' => 'endefiniu',
'protect-cascade' => 'Protehel las páhinas encluias nesta páhina (proteción en "cascá")',
'protect-cantedit' => "Nu t'es posibri chambal el nivel de proteción desta páhina ebiu a que nu tienis los premisus nesezárius pa eitala.",
'protect-edit-reasonlist' => 'Eital radonis de proteción',
'protect-expiry-options' => '1 ora:1 hour,1 dia:1 day,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 mesis:3 months,6 mesis:6 months,1 añu:1 year,enfinitu:infinite',
'restriction-type' => 'Premisus:',
'restriction-level' => 'Nivel de restrición:',
'minimum-size' => 'Grandol mén',
'maximum-size' => 'Grandol mái:',
'pagesize' => '(bytes)',

# Restrictions (nouns)
'restriction-edit' => 'Eital',
'restriction-move' => 'Movel',
'restriction-create' => 'Crial',
'restriction-upload' => 'Empuntal',

# Restriction levels
'restriction-level-sysop' => 'totalmenti protehia',
'restriction-level-autoconfirmed' => 'abati protehia',
'restriction-level-all' => 'cualisquiel nivel',

# Undelete
'undelete' => 'Vel páhinas esborrás',
'undeletepage' => 'Vel i restaural páhinas esborrás',
'viewdeletedpage' => 'Vel páhinas esborrás',
'undeletepagetext' => "Las siguientis páhinas án siu esborrás, peru acontinan ena trohi i puein sel arrecuperás. Nu ostanti, la trohi s'esborra ca ciertu tiempu.",
'undelete-fieldset-title' => 'Arrecuperal revissionis',
'undeleterevisions' => '$1 {{PLURAL:$1|revisión emburacá|revisionis emburacás}}',
'undeletehistory' => "Si arrecuperas la página, s'arrecuperaran tolas revisionis del estorial.
Si s'á criau una página con el mesmu nombri dendi que hue esborrá, las revisionis
arrecuperás apaicerán nel estorial anteriol.",
'undeletehistorynoadmin' => "Esta páhina á siu esborrá. La razón el esborrau se muestra embahu, unta los detallis al tentu los usuárius qu'eitarun esta páhina enantis de que huera esborrá. El testu las revisionis esborrás solu está disponibri pa los çahorilis.",
'undelete-revision' => 'Esborrá la revisión de $1 (del dia $4 a las $5), hecha pol $3:',
'undelete-nodiff' => "Nu s'á alcuentrau denguna revisión previa.",
'undeletebtn' => 'Restaural',
'undeletelink' => 'Guipal/arrecuperal',
'undeletereset' => 'Reahustal',
'undeletecomment' => 'Comentáriu:',
'undeletedrevisions' => '{{PLURAL:$1|1 revisión|$1 revisionis}} restaurás',
'undeletedrevisions-files' => '{{PLURAL:$1|1 revisión|$1 revisionis}} i {{PLURAL:$2|1 archivu|$2 archivus}} restauraus',
'undeletedfiles' => '{{PLURAL:$1|1 archivu|$1 archivus}} restauraus',
'cannotundelete' => 'Marru arrecuperandu; es posibri qu´alguien ya aiga arrecuperau la páhina.',
'undeletedpage' => "'''S'á restaurau $1'''

Consurta el [[Special:Log/delete|rustrihu d'esborrau]] pa visoreal los úrtimus esborraus i arrecuperacionis.",
'undelete-header' => 'Vaiti pal [[Special:Log/delete|rustrihu d´esborrau]] pa vel las úrtimas páhinas esborrás.',
'undelete-search-box' => 'Landeal páhinas esborrás',
'undelete-search-prefix' => 'Muestral páhinas qu´esmiencin pol:',
'undelete-search-submit' => 'Landeal',
'undelete-no-results' => "Nu s'alcuentrarun páhinas con esas caraterísticas nel rustrihu d'esborrau.",
'undelete-filename-mismatch' => 'Nu se puei arrecuperal la revisión del archivu con fecha $1: el nombri el archivu nu concuerda',
'undelete-bad-store-key' => 'Nu se puei arrecuperal la revisión del archivu con fecha $1: ya nu desistia el archivu nel momentu el esborrau.',
'undelete-cleanup-error' => 'Marru esborrandu el archivu "$1".',
'undelete-missing-filearchive' => "Nu se puei arrecuperal el archivu con ID $1 ebiu a que nu s'alcuentra ena basi e datus. Es posibri que ya aiga siu arrecuperau.",
'undelete-error-short' => 'Marru arrecuperandu archivu: $1',
'undelete-error-long' => 'Marrus alcuentraus al arrecuperal el archivu:

$1',
'undelete-show-file-submit' => 'Sí',

# Namespace form on various pages
'namespace' => 'Espáciu de nombris:',
'invert' => 'Invertil seleción',
'blanknamespace' => '(Prencipal)',

# Contributions
'contributions' => 'Endirguis el usuáriu',
'contributions-title' => 'Contribucionis del usuáriu a $1',
'mycontris' => 'Los mis endirguis',
'contribsub2' => 'Pa $1 ($2)',
'nocontribs' => "Nu s'alcuentrun chambus con esus criterius.",
'uctop' => '(úrtimu chambu)',
'month' => 'Mes:',
'year' => 'Añu:',

'sp-contributions-newbies' => 'Solu muestral los endirguis de cuentas nuevas',
'sp-contributions-newbies-sub' => 'Pa nuevas cuentas',
'sp-contributions-blocklog' => 'Rustrihu e tarugus',
'sp-contributions-deleted' => 'Contribucionis el usuáriu esborrás',
'sp-contributions-logs' => 'rustrijus',
'sp-contributions-talk' => 'Caraba',
'sp-contributions-search' => 'Landeal pol endirguis',
'sp-contributions-username' => 'IP u nombri d´usuáriu:',
'sp-contributions-submit' => 'Landeal',

# What links here
'whatlinkshere' => 'Lo que atija aquina',
'whatlinkshere-title' => 'Páhinas que atihan a $1',
'whatlinkshere-page' => 'Páhina:',
'linkshere' => "Las siguientis páhinas atihan a '''[[:$1]]''':",
'nolinkshere' => "Denguna páhina atiha a '''[[:$1]]'''.",
'nolinkshere-ns' => "Nu ai denguna páhina qu´atihi a '''[[:$1]]''' nel espaciu e nombris lihiu.",
'isredirect' => 'Rederihil páhina',
'istemplate' => 'inclusión',
'isimage' => 'atihu la imahin',
'whatlinkshere-prev' => '{{PLURAL:$1|anteriol|$1 anteriol}}',
'whatlinkshere-next' => '{{PLURAL:$1|siguienti|$1 siguienti}}',
'whatlinkshere-links' => '← atihus',
'whatlinkshere-hideredirs' => '$1 redirecionis',
'whatlinkshere-hidetrans' => '$1 trasclusionis',
'whatlinkshere-hidelinks' => '$1 atihus',
'whatlinkshere-hideimages' => '$1 atihus a la imahin',
'whatlinkshere-filters' => 'Filtrus',

# Block/unblock
'blockip' => 'Atarugal usuáriu',
'blockip-legend' => 'Atarugal usuáriu',
'blockiptext' => "Gasta el hormuláriu d'embahu p'atarugal el acesu duna IP u dun usuáriu.
Estu solu ebi hazelsi pa evital el vandalismu, i dalcuerdu cola [[{{MediaWiki:Policy-url}}|póliça]].
Escrebi una razón concreta embahu (pol sabulugal, almientandu páhinas qu'aigan siu vandalizás pol esti usuáriu).",
'ipadressorusername' => 'IP u nombri d´usuáriu:',
'ipbexpiry' => 'Acabiha:',
'ipbreason' => 'Razón:',
'ipbreasonotherlist' => 'Otra razón',
'ipbreason-dropdown' => '*Motivus frecuentis de tarugus
** Escrebil enhormación farsa
** Esborral el continiu las páhinas
** Añiil publiciá d´otras páhinas...
** Añiil basura enas páhinas
** Comportamientu encévicu
** Abusal con varias cuentas
** Nombris d´usuárius enacetabris',
'ipbcreateaccount' => 'Atarugal el criaeru e cuentas',
'ipbemailban' => 'Atarugal al usuáriu envial emails',
'ipbenableautoblock' => "Atarugal autumáticamenti la direción IP gastá pol esti usuáriu, i cualisquiel IP posteriol endi la cual trati d'eital",
'ipbsubmit' => 'Atarugal a esti usuáriu',
'ipbother' => 'Otra ora:',
'ipboptions' => '2 oras:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 mesis:3 months,6 mesis:6 months,1 añu:1 year,enfinitu:infinite',
'ipbotheroption' => 'otru',
'ipbotherreason' => 'Anguna otra razón?:',
'ipbhidename' => 'Açonchal nombri d\'usuáriu nel "rustrihu e tarugus", "lista e tarugus ativus" i "lista d\'usuárius"',
'ipbwatchuser' => "Visoreal la páhina d'usuáriu i la caraba d'esti usuáriu.",
'badipaddress' => 'Direción IP nu premitia',
'blockipsuccesssub' => 'Usuáriu atarugau!',
'blockipsuccesstext' => "La direción IP [[Special:Contributions/$1|$1]] á siu atarugá.
<br />Consurta la [[Special:BlockList|lista d'IP atarugás]] pa visoreal los tarugus.",
'ipb-edit-dropdown' => 'Eital las razonis el tarugu',
'ipb-unblock-addr' => 'Desatarugal $1',
'ipb-unblock' => 'Desatarugal un nombri d´usuáriu u direción IP',
'ipb-blocklist' => 'Vel tarugus desistentis',
'ipb-blocklist-contribs' => 'Contribucionis de $1',
'unblockip' => 'Desatarugal usuáriu',
'unblockiptext' => "Gasta el hormulariu d'embahu pa restablecel el acesu d'escritura a una direción IP u a un nombri d'usuáriu previamenti atarugau.",
'ipusubmit' => 'Esborral esti tarugu',
'unblocked' => 'El usuáriu [[User:$1|$1]] á siu desatarugau',
'unblocked-id' => 'S´á esborrau el tarugu $1',
'ipblocklist' => "Lista de IP i nombris d'usuárius atarugaus",
'ipblocklist-legend' => 'Landeal a un usuáriu atarugau',
'ipblocklist-submit' => 'Landeal',
'infiniteblock' => 'enfinitu',
'expiringblock' => 'acabiha $1 $2',
'anononlyblock' => 'solu anón.',
'noautoblockblock' => 'autu-tarugu esativau',
'createaccountblock' => 'criaeru e páhinas atarugau',
'emailblock' => 'email atarugau',
'ipblocklist-empty' => 'La lista e tarugus está vacia.',
'ipblocklist-no-results' => 'Esta direción IP/nombri d´usuáriu nu está atarugau.',
'blocklink' => 'atarugal',
'unblocklink' => 'desatarugal',
'change-blocklink' => 'chambal tarugu',
'contribslink' => 'endirguis',
'autoblocker' => 'Autu-atarugau ebiu a que la tu IP á siu gastá hazi pocu pol "[[User:$1|$1]]". La razón el tarugu de $1 es: "$2"',
'blocklogpage' => 'Rustrihu e tarugus',
'blocklogentry' => 'atarugó a "[[$1]]" $3 duranti un praçu e "$2"',
'unblocklogentry' => '$1 desatarugau',
'block-log-flags-anononly' => 'sólu usuárius anónimus',
'block-log-flags-nocreate' => 'Desativau el criaeru e cuentas',
'block-log-flags-noautoblock' => 'autu-tarugu esativau',
'block-log-flags-noemail' => 'email atarugau',
'block-log-flags-hiddenname' => "nombri d'ussuáriu açonchau.",
'range_block_disabled' => 'Nu se premiti a los çahorilis crial tarugus pol rangus.',
'ipb_expiry_invalid' => 'Tiempu encorretu.',
'ipb_already_blocked' => '"$1" ya está atarugau',
'ipb_cant_unblock' => "Marru: Nu s'á alcuentrau el tarugu con ID $1. Es posibri que ya aiga siu desatarugau.",
'ipb_blocked_as_range' => "Marru: La IP $1 nu s'alcuentra atarugá diretamenti, polo que nu puei sel desatarugá. Nu ostanti, hue atarugá cumu parti el intervalu $2, que puei sel desatarugau.",
'ip_range_invalid' => "Rangu d'IP nu premitiu.",
'proxyblocker' => 'Tarugaol de proxys',
'proxyblockreason' => "La tu direción IP á siu atarugá polque es un proxy abiertu. Pol favol, contauta con el tu proveol de sirvicius d'Internet u con el tu sirviciu d'asisténcia télefónica i enhórmalus desti gravi pobrema e seguráncia.",
'sorbsreason' => 'La tu direción IP apaici ena lista e proxys abiertus en DNSBL gastá pol {{SITENAME}}.',
'sorbs_create_account_reason' => 'La tu direción IP apaici ena lista e proxys abiertus en DNSBL gastá pol {{SITENAME}}. Nu se te premiti crial una cuenta',

# Developer tools
'lockdb' => 'Atarugal la basi e datus',
'unlockdb' => 'Desatarugal la basi e datus',
'lockdbtext' => 'Al atarugal la basi e datus el restu d´usuárius nu pudrán
eital páhinas, chambal las sus preferéncias, eital las sus listas de seguimientu,
i algotras cosas que requieran chambus ena basi e datus.
Pol favol, confirma que realmenti quieis atarugal la basi e datus, i qu´esborrarás el tarugu
cuandu aigas acabihau.',
'unlockdbtext' => "Al desatarugal la basi e datus se premitirá a tolos
usuárius eital páhinas, chambal las sus preferéncias, eital
las sus páhinas vehilás i algotras acionis que nesezitan hazel chambus
ena basi e datus. Pol favol, confirma qu'es lo que quieis hazel.",
'lockconfirm' => 'Sí, realmenti quieu atarugal la basi e datus.',
'unlockconfirm' => 'Sí, realmenti quieu desatarugal la basi e datus.',
'lockbtn' => 'Atarugal basi e datus',
'unlockbtn' => 'Desatarugal la basi e datus',
'locknoconfirm' => 'Nu as confirmau lo que te petaria hazel.',
'lockdbsuccesssub' => 'Tarugu la basi e datus ativu',
'unlockdbsuccesssub' => 'Esborrau el tarugu la basi e datus',
'lockdbsuccesstext' => 'La basi e datus á siu atarugá.
<br />Alcuerdati d´[[Special:UnlockDB|esborral el tarugu]] cuandu aigas acabihau.',
'unlockdbsuccesstext' => "S'á desatarugau la basi e datus.",
'lockfilenotwritable' => "El tarugu la basi e datus nu se puei sobriescribil. P'atarugual u desatarugal la basi e datus, esta ebi puel sel escrita pol sirviol web.",
'databasenotlocked' => 'La basi e datus nu está atarugá.',

# Move page
'move-page' => 'Mual $1',
'move-page-legend' => 'Movel páhina',
'movepagetext' => "Gastandu el hormuláriu d'embahu se chambará el nombri la páhina, moviendu el su estorial al nuevu nombri, i rederihiendu el entítulu antigu al nuevu.
Los atihus al entítulu antigu nu chambarán; cúdia colas dobris redirecionis i los atihus eschangaus.
Eris responsabri e que los atihus acontinin llevandu andi se suponi que tienin que lleval.

Pol otra parti, la páhina '''nu''' se moverá si ya desisti una páhina con el nombri nuevu, a nu sel que seya una páhina vacia u una redireción. Estu senifica que pueis gorvel a poneli el nombri antigu en chascu e marru, peru nu t'es posibri sobriescrebil una páhina ya desistenti.

'''Avisu!'''
En páhinas popularis, esta ación puei arrepresental un chambu emportanti;
pol favol, asigurati e qu'entiendis las consecuéncias enantis d'acontinal.",
'movepagetalktext' => "La caraba asociá se moverá con el artículu, '''a nus sel que:'''
*Ya desista otra caraba con el mesmu nombri, u
*Nu comprebis la caha d'embahu.

En dambus los dos chascus, si lo deseas, tendrás que movel u mestural la páhina manualmenti.",
'movearticle' => 'Movel páhina:',
'movenologin' => "Nu t'alcuentras rustriu",
'movenologintext' => 'Ebis estal rustriu i [[Special:UserLogin|entral ena tu cuenta]] pa movel una páhina.',
'movenotallowed' => 'Nu tinis premissu pa mual páginas.',
'movenotallowedfile' => 'Nu tinis premissus pa mual archivus.',
'newtitle' => 'Nuevu entítulu:',
'move-watch' => 'Vehilal esta páhina',
'movepagebtn' => 'Movel páhina',
'pagemovedsub' => 'S´á moviu la páhina',
'movepage-moved' => "S'á muau '''\"\$1\" a \"\$2\"'''",
'movepage-moved-redirect' => 'Á siu criá una redireción.',
'articleexists' => 'Ya desisti una páhina con esi nombri u nu se premiti el nombri qu´as lihiu.
Pol favol, escrebi otru entítulu.',
'cantmove-titleprotected' => "Nu t'es posibri movel la páhina ebiu a qu'el nuevu entítulu s'alcuentra atarugau",
'talkexists' => "'''S'á moviu la páhina, peru la su caraba nu puei sel movia polque ya desisti otra caraba con el nuevu entítulu. Pol favol, mesturalas manualmenti.'''",
'movedto' => 's´á moviu a',
'movetalk' => 'Tamién movel la su caraba',
'movelogpage' => 'Rustrihu e movimientus',
'movelogpagetext' => 'Embahu ai una lista colas páhinas movias.',
'movereason' => 'Razón:',
'revertmove' => 'revertil',
'delete_and_move' => 'Esborral i movel',
'delete_and_move_text' => '==Es mestel esborral==

Ya desisti la páhina "[[:$1]]". Te petaria esborrala pa premitil el treslau?',
'delete_and_move_confirm' => 'Sí, esborral la páhina',
'delete_and_move_reason' => 'Esborrá pa premitil el treslau',
'selfmove' => "Los entítulus d'orihin i destinu son los mesmus. Nu es posibri movel una páhina sobri sí mesma..",
'immobile-source-page' => 'Nu es possibri mual esta página.',

# Export
'export' => 'Esporteal páhinas',
'exportcuronly' => 'Incluyi solu la revisión atual, nu el estorial de revisionis al completu',
'exportnohistory' => "----
'''Nota:''' Nu es posibri esporteal el estorial completu las páhinas a través d'esti hormulariu ebiu a tareas de mantenimientu.",
'export-submit' => 'Esporteal',
'export-addcattext' => 'Añiil páhinas dendi anguna categoria:',
'export-addcat' => 'Añiil',
'export-addns' => 'Añiil',
'export-download' => 'Ofrecel emburacal cumu un archivu',
'export-templates' => 'Incluil prantillas',

# Namespace 8 related
'allmessages' => 'Mensahis el sistema',
'allmessagesname' => 'Nombri',
'allmessagesdefault' => 'Testu pol defeutu',
'allmessagescurrent' => 'Testu atual',
'allmessagestext' => 'Esta es una lista e mensahis del sistema disponibris nel espaciu e nombris MediaWiki:
Pol favol, vesita [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] i [//translatewiki.net translatewiki.net] si quieis colabutal.',
'allmessagesnotsupportedDB' => "Nu se puei gastal esta páhina polque '''\$wgUseDatabaseMessages''' está desativau.",

# Thumbnails
'thumbnail-more' => 'Agrandal',
'filemissing' => 'Archivu escambulliu',
'thumbnail_error' => 'Marru criandu cuairu: $1',
'djvu_page_error' => 'Páhina DjVu huera el rangu',
'djvu_no_xml' => 'Nu á siu posibri otenel el XML pal archivu DjVu',
'thumbnail_invalid_params' => 'Nu se premitin esus parámetrus pal cuairu',
'thumbnail_dest_directory' => 'Nu es posibri crial el diretoriu e destinu',

# Special:Import
'import' => 'Emporteal páhinas',
'importinterwiki' => 'Emporteaeru trasgüiqui',
'import-interwiki-text' => "Aseñala un güiqui i el entítulu la páhina que quieas emporteal.
Las fechas las revisionis i los nombris los eitoris se mantendrán.
Tolas acionis d'emporteau transwiki se rustrin nel [[Special:Log/import|rustrihu d'emporteau]].",
'import-interwiki-history' => "Copial tolas velsionis estóricas d'esta páhina",
'import-interwiki-templates' => 'Encruil tolos cuairus',
'import-interwiki-submit' => 'Emporteal',
'import-interwiki-namespace' => 'Movel páginas al espáciu nombris:',
'import-upload-filename' => 'Nombri del archivu:',
'import-comment' => 'Comentáriu:',
'importstart' => 'Emporteandu páhinas...',
'import-revision-count' => '$1 {{PLURAL:$1|revisión|revisionis}}',
'importnopages' => 'Nu ai páhinas pa emporteal.',
'importfailed' => 'Marru al emporteal: $1',
'importcantopen' => 'Nu se puei abril el archivu emporteau',
'importbadinterwiki' => 'Marru nel atihu d´EntelGüiqui',
'importnotext' => 'Vaciu u sin testu',
'importsuccess' => 'Archivu emporteau!',
'importnofile' => 'Dengún archivu emporteau hue empuntau.',
'import-parse-failure' => "Marru nel análisis d'emporteación XML",
'import-noarticle' => 'Nu ai páhinas pa emporteal!',
'import-nonewrevisions' => 'Ya án siu emporteás tolas revisionis.',
'xml-error-string' => '$1 ena línia $2, col $3 (byte $4): $5',
'import-upload' => 'Empuntal datus XML',

# Import log
'importlogpage' => 'Emporteal rustrihu',
'importlogpagetext' => "Emporteacionis alministrativas de páhinas con estorial d'edicionis d'otras güiquis.",
'import-logentry-upload' => "á emporteau [[$1]] pol empuntu d'archivu",
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|revisión|revisionis}}',
'import-logentry-interwiki' => 'trasgüiquipeau $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisión|revisionis}} dendi $2',

# Tooltip help for the actions
'tooltip-pt-userpage' => "La mi páhina d'usuáriu",
'tooltip-pt-anonuserpage' => "La páhina d'usuáriu la IP dendi la qu'eita",
'tooltip-pt-mytalk' => 'La mi caraba',
'tooltip-pt-anontalk' => 'Caraba sobri las eicionis hechas con esta IP',
'tooltip-pt-preferences' => 'Las mis preferéncias',
'tooltip-pt-watchlist' => 'Lista e páhinas enas que vehilas los chambus',
'tooltip-pt-mycontris' => 'Los mis endirguis',
'tooltip-pt-login' => 'Te recomendamus que te rustris, inque nu es mestel.',
'tooltip-pt-anonlogin' => 'Te recomendamus que te rustris, inque nu es mestel.',
'tooltip-pt-logout' => 'Salil',
'tooltip-ca-talk' => 'Caraba al tentu el artículu',
'tooltip-ca-edit' => 'Pueis eital esta página.
Pol favol, gasta el botón "previsoreal" enantis d\'emburacal.',
'tooltip-ca-addsection' => 'Prencipial una nueva seción',
'tooltip-ca-viewsource' => 'Esta páhina está protehia (nu pueis hazel chambus).',
'tooltip-ca-history' => "Velsionis anterioris d'esta página.",
'tooltip-ca-protect' => 'Protehel esta páhina',
'tooltip-ca-delete' => 'Esborral esta páhina',
'tooltip-ca-undelete' => 'Arrecuperal las eicionis hechas nesta páhina enantis de que huera esborrá',
'tooltip-ca-move' => 'Movel esta páhina',
'tooltip-ca-watch' => 'Añiil esta páhina a la tu lista e seguimientu',
'tooltip-ca-unwatch' => 'Esborral esta páhina e la tu lista e seguimientu',
'tooltip-search' => 'Landeal {{SITENAME}}',
'tooltip-search-go' => 'Dil pa una página con el nombri dessautu si dessisti',
'tooltip-search-fulltext' => 'Landeal páginas con esti testu',
'tooltip-p-logo' => 'Páhina prencipal',
'tooltip-n-mainpage' => 'Vesital la Página Prencipal',
'tooltip-n-mainpage-description' => 'Vessital la página prencipal',
'tooltip-n-portal' => 'Al tentu el proyeutu, lo que pueis hazel, ondi alcuentral cosas',
'tooltip-n-currentevents' => 'Enhormación de contestu al tentu acontecimientus atualis',
'tooltip-n-recentchanges' => 'La lista e los úrtimus chambus nesti güiqui.',
'tooltip-n-randompage' => 'Cargal cualisquiel páhina',
'tooltip-n-help' => 'El lugal pa deprendel.',
'tooltip-t-whatlinkshere' => "Lista con tolas páginas wiki qu'atijan aquina",
'tooltip-t-recentchangeslinked' => 'Úrtimus chambus en páhinas atihás dendi esta páhina',
'tooltip-feed-rss' => 'RSS feed pa esta páhina',
'tooltip-feed-atom' => 'Atom feed pa esta páhina',
'tooltip-t-contributions' => 'Visoreal los endirguis desti usuáriu',
'tooltip-t-emailuser' => 'Envial un email a esti usuáriu',
'tooltip-t-upload' => 'Empuntal archivus',
'tooltip-t-specialpages' => 'Lista con tolas páginas especialis',
'tooltip-t-print' => 'Velsión pa imprental desta páhina',
'tooltip-t-permalink' => 'Atihu remanenti a esta velsión de la páhina',
'tooltip-ca-nstab-main' => 'Vel el artículu',
'tooltip-ca-nstab-user' => 'Vel la páhina d´usuáriu',
'tooltip-ca-nstab-media' => 'Vel la páhina e "meya"',
'tooltip-ca-nstab-special' => 'Esta es una páhina especial, razón pola que nu pueis eitala',
'tooltip-ca-nstab-project' => 'Vel la páhina el proyeutu',
'tooltip-ca-nstab-image' => 'Vel la páhina el archivu',
'tooltip-ca-nstab-mediawiki' => 'Vel el mensahi el sistema',
'tooltip-ca-nstab-template' => 'Vel la prantilla',
'tooltip-ca-nstab-help' => 'Vel la páhina d´ayua',
'tooltip-ca-nstab-category' => 'Vel la categoria',
'tooltip-minoredit' => 'Aseñalal cumu eición chiquenina',
'tooltip-save' => 'Emburacal los tus chambus',
'tooltip-preview' => 'Pol favol, previsorea el artículu enantis d´emburacalu!',
'tooltip-diff' => 'Muestral los chambus qu´as hechu nel testu.',
'tooltip-compareselectedversions' => "Visoreal las deferéncias entri las dos velsionis aseñalás d'esta páhina.",
'tooltip-watch' => 'Añiil esta páhina a la tu lista e seguimientu',
'tooltip-recreate' => 'Gorvel a crial la páhina inque aiga siu esborrá',
'tooltip-upload' => 'Prencipial a empuntal',
'tooltip-rollback' => '"Reveltil" esborra las eicionis hechas a esta página pol úrtimu usuáriu con un click',
'tooltip-undo' => '"Esjadel" revierti ésta eición i abri el mó eición en mó previsoreal.
Éstu premiti añiil una radón al estorial.',

# Scripts
'monobook.js' => '/* Antigu; gasta [[MediaWiki:common.js]] */',

# Metadata
'notacceptable' => 'El sirviol de la güiqui nu puei chambal los datus a un hormatu leibri pol tu escrucaol.',

# Attribution
'anonymous' => '{{PLURAL:$1|Ussuáriu anónimu|Ussuárius anónimus}} en {{SITENAME}}',
'siteuser' => '{{SITENAME}} usuáriu $1',
'lastmodifiedatby' => 'Esta páhina se chambó pol úrtima vezi a las $2, el dia $1 pol $3.',
'othercontribs' => 'Basau nun labutu e $1.',
'others' => 'otrus',
'siteusers' => '{{SITENAME}} usuáriu/s $1',
'creditspage' => 'Créitus la páhina',
'nocredits' => 'Nu ai créitus disponibris pa esta páhina.',

# Spam protection
'spamprotectiontitle' => 'Filtru e proteción anti-Spam',
'spamprotectiontext' => 'La página que quieis emburacal á siu atarugá pol filtru anti-spam. Estu puei sel ebiu a angún atiju a una página esteriol.',
'spamprotectionmatch' => 'El testu siguiente á ativau el muestru filtru antispam: $1',
'spambot_username' => 'MediaWiki limpia-spam',
'spam_reverting' => 'Revirtiendu a la úrtima velsión que nu contenga atihus a $1',
'spam_blanking' => 'Tolas revisionis tienin atihus a $1, branqueandu',

# Patrolling
'markaspatrolleddiff' => 'Aseñalal cumu patrullau',
'markaspatrolledtext' => 'Aseñalal esti artículu cumu patrullau',
'markedaspatrolled' => 'Aseñalal cumu patrullau',
'markedaspatrolledtext' => 'La revisión asseñalá á siu marcá cumu patrullá.',
'rcpatroldisabled' => "Patrulla d'Úrtimus Chambus desativá",
'rcpatroldisabledtext' => "La capaciá pa patrullal los Úrtimus Chambus está desativá n'esti momentu.",
'markedaspatrollederror' => 'Nu se puei aseñalal cumu patrullá',
'markedaspatrollederrortext' => "Ebis especifical una revisión p'aseñalala cumu patrullá.",
'markedaspatrollederror-noautopatrol' => 'Nu tienis premisu p\'aseñalal los tus propius chambus cumu "revisaus".',

# Patrol log
'patrol-log-page' => 'Rustrihu e revisionis',
'patrol-log-header' => 'Esti es un rustriju e revissionis patrullás.',

# Image deletion
'deletedrevision' => 'Esborrá la revisión antigua $1',
'filedeleteerror-short' => 'Marru esborrandu archivu: $1',
'filedeleteerror-long' => 'Marrus alcuentraus al esborral el archivu:

$1',
'filedelete-missing' => 'El archivu "$1" nu puei sel esborrau ebiu a que nu desisti.',
'filedelete-old-unregistered' => 'La velsión especificá la revisión "$1" nu s\'alcuentra ena basi e datus.',
'filedelete-current-unregistered' => 'El archivu "$1" nu está ena basi e datus.',
'filedelete-archive-read-only' => 'El diretóriu d\'archivus "$1" nu puei sel moificau pol sirviol.',

# Browsing diffs
'previousdiff' => '← Def anteriol',
'nextdiff' => 'Siguienti def →',

# Media information
'thumbsize' => 'Grandol el cuairu:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|página|páginas}}',
'file-info' => 'grandol el archivu: $1, MIME type: $2',
'file-info-size' => '$1 × $2 pixel, grandol el archivu: $3, MIME type: $4',
'file-nohires' => 'Nu disponibri a mayol resolución.',
'svg-long-desc' => 'archivu SVG, $1 × $2 pixelis, grandol: $3',
'show-big-image' => 'Resolución máisima',

# Special:NewFiles
'newimages' => 'Correol d´archivus nuevus',
'imagelisttext' => "Embahu ai una lista con '''$1''' {{PLURAL:$1|archivu|archivus}} ordenaus $2.",
'newimages-legend' => 'Filtru',
'newimages-label' => 'Nombri el archivu (u parti):',
'showhidebots' => '($1 bots)',
'noimages' => 'Nu ai ná pa vel.',
'ilsubmit' => 'Landeal',
'bydate' => 'pol fecha',
'sp-newimages-showfrom' => 'Muestral nuevas imahin empuntás a partil de $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => '$1o',

# Bad image list
'bad_image_list' => 'El hormatu ebi sel asina:

Solu las frasis en hormatu lista (cuandu se prencipia la frasi con *) son consierás. El primel atihu nuna frasi ebi sel a una mala imahin.
Cualisquiel otru atihu ena mesma línia se consierará ececión, p.s. páhinas ondi la imahin puei ocurril ena línia.',

# Metadata
'metadata' => 'Metadatus',
'metadata-help' => "Esti archivu contieni enhormación aicional (metadatus), probabrienti añiia pola cámara dehital, el escánel u el pograma gastau pa crialu u dehitaliçalu. Si s'án hechu chambus nel archivu, es posibri que s'aigan perdiu detallis.",
'metadata-expand' => 'Muestral detallis',
'metadata-collapse' => 'Açonchal detallis',
'metadata-fields' => 'Los datus de metadatus EXIF que se listan nesti mensahi se muestrarán ena páhina e descrición la imahin aún cuandu la tabra e metadatus esté açonchá. Desistin algotrus campus que se mantendrán açonchaus pol defetu.
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

# Exif tags
'exif-imagewidth' => 'Anchón',
'exif-imagelength' => 'Artura',
'exif-bitspersample' => 'Bits pol componenti',
'exif-compression' => 'Esquema e compresión',
'exif-photometricinterpretation' => 'Composición del pixel',
'exif-orientation' => 'Orientación',
'exif-samplesperpixel' => 'Númeru e componentis',
'exif-planarconfiguration' => 'Distribuieru e los datus',
'exif-ycbcrpositioning' => 'Posicionamientus Y i C',
'exif-xresolution' => 'Resolución orizontal',
'exif-yresolution' => 'Resolución vertical',
'exif-jpeginterchangeformatlength' => 'Bytes de datus JPEG',
'exif-whitepoint' => 'Cromaciá e puntu brancu',
'exif-primarychromaticities' => 'Cromaciais primarias',
'exif-imagedescription' => 'Entítulu la imáhin',
'exif-make' => 'Fabricanti e la cámara',
'exif-model' => 'Moelu la cámara',
'exif-software' => 'Software gastau',
'exif-artist' => 'Autol',
'exif-copyright' => 'Entitulal el Copyright',
'exif-exifversion' => 'Velsión Exif',
'exif-flashpixversion' => 'Velsión Flashpix soportá',
'exif-colorspace' => 'Espaciu e colol',
'exif-componentsconfiguration' => 'Senificau e ca componenti',
'exif-compressedbitsperpixel' => 'Mó de compresión la imahin',
'exif-pixelydimension' => 'Anchón la imahin premitiu',
'exif-pixelxdimension' => 'Artu la imahin premitiu',
'exif-usercomment' => 'Comentárius del usuáriu',
'exif-relatedsoundfile' => "Archivu d'audiu relacionau",
'exif-datetimeoriginal' => 'Fecha i ora la heneración los datus',
'exif-exposuretime' => "Tiempu d'esposición",
'exif-exposuretime-format' => '$1 seg ($2)',
'exif-fnumber' => 'Númeru F',
'exif-exposureprogram' => "Pograma d'esposición",
'exif-isospeedratings' => 'Calificación de velociá ISO',
'exif-aperturevalue' => 'Apertura',
'exif-brightnessvalue' => 'Brillu',
'exif-maxaperturevalue' => 'Máisima apertura',
'exif-lightsource' => 'Huenti e lús',
'exif-subjectarea' => 'Ária',
'exif-flashenergy' => 'Poténcia el Flash',
'exif-subjectlocation' => 'Asiahamientu',
'exif-sensingmethod' => 'Métu e sensol',
'exif-filesource' => 'Coigu el archivu',
'exif-scenetype' => "Crasi d'escena",
'exif-customrendered' => "Procesamientu d'imahin presonalizau",
'exif-exposuremode' => "Mó d'esposición",
'exif-whitebalance' => 'Balanci e brancu',
'exif-digitalzoomratio' => 'Ratiu el zoom dehital',
'exif-focallengthin35mmfilm' => 'Longol focal en carreti e 35 mm',
'exif-gaincontrol' => 'Control la escena',
'exif-contrast' => 'Contrasti',
'exif-saturation' => 'Saturáncia',
'exif-imageuniqueid' => "Ientificaeru d'imahin",
'exif-gpsversionid' => 'Velsión la etiqueta GPS',
'exif-gpslatituderef' => 'Latitú Norti u Sul',
'exif-gpslatitude' => 'Latitú',
'exif-gpslongituderef' => 'Lonhitú Esti u Oesti',
'exif-gpslongitude' => 'Lonhitú',
'exif-gpsaltituderef' => 'Artitú e referéncia',
'exif-gpsaltitude' => 'Artitú',
'exif-gpstimestamp' => 'Ora el GPS (Reló atómicu)',
'exif-gpssatellites' => 'Satélitis gastaus pala miia',
'exif-gpsstatus' => 'Estau el recetol',
'exif-gpsmeasuremode' => 'Mó e miia',
'exif-gpsdop' => 'Precisión de miia',
'exif-gpsspeedref' => 'Uniá e velociá',
'exif-gpsspeed' => 'Velociá el recetol GPS',
'exif-gpstrack' => 'Direción el movimientu',
'exif-gpsimgdirection' => 'Direción la imahin',
'exif-gpsdestlatituderef' => 'Referéncia pala latitú el destinu',
'exif-gpsdestlatitude' => 'Latitú el destinu',
'exif-gpsdestlongituderef' => 'Referéncia pala lonhitú el destinu',
'exif-gpsdestlongitude' => 'Lonhitú el destinu',
'exif-gpsdestbearingref' => 'Referéncia la orientación de destinu',
'exif-gpsdestbearing' => 'Orientación de destinu',
'exif-gpsdestdistanceref' => 'Longol al destinu',
'exif-gpsdestdistance' => 'Longol al destinu',
'exif-gpsprocessingmethod' => 'Nombri el métu e procesamientu e GPS',
'exif-gpsareainformation' => 'Nombri el ária GPS',
'exif-gpsdatestamp' => 'Fecha el GPS',
'exif-gpsdifferential' => 'Correción diferencial de GPS',

# Exif attributes
'exif-compression-1' => 'Descomprimiu',

'exif-unknowndate' => 'Fecha andarria',

'exif-orientation-2' => 'Gorteau orizontalmenti',
'exif-orientation-3' => 'Repiau 180°',
'exif-orientation-4' => 'Gorteau verticalmenti',
'exif-orientation-5' => 'Repiau 90° CCW i gorteau verticalmenti',
'exif-orientation-6' => 'Repiau 90° CW',
'exif-orientation-7' => 'Repiau 90° CW i gorteau verticalmenti',
'exif-orientation-8' => 'Repiau 90° CCW',

'exif-planarconfiguration-1' => 'hormatu gruesu',
'exif-planarconfiguration-2' => 'hormatu pranu',

'exif-componentsconfiguration-0' => 'nu desisti',

'exif-exposureprogram-0' => 'Sin definil',
'exif-exposureprogram-2' => 'Pograma normal',
'exif-exposureprogram-3' => "Prioriá d'apertura",
'exif-exposureprogram-4' => "Prioriá d'oturaol",
'exif-exposureprogram-5' => 'Pograma criativu (con prioriá e prohundiá e campu)',
'exif-exposureprogram-6' => "Pograma d'ación (prioridá d'arta velociá el oturaol)",
'exif-exposureprogram-7' => "Mó retrataura (p'afotus cercanas con el hondu desenfocau)",
'exif-exposureprogram-8' => "Mó paisahi (p'afotus amprias con el hondu enfocau)",

'exif-subjectdistance-value' => '$1 metrus',

'exif-meteringmode-0' => 'Andarriu',
'exif-meteringmode-1' => 'Promeyu',
'exif-meteringmode-2' => 'Promeyu centrau',
'exif-meteringmode-4' => 'MurtiSpot',
'exif-meteringmode-5' => 'Patrón',
'exif-meteringmode-6' => 'Parcial',
'exif-meteringmode-255' => 'Otru',

'exif-lightsource-0' => 'Andarriu',
'exif-lightsource-1' => 'Lus el dia',
'exif-lightsource-2' => 'Fluorescenti',
'exif-lightsource-3' => 'Tungstenu (lús encandescenti)',
'exif-lightsource-9' => 'Güen tiempu',
'exif-lightsource-10' => 'Tiempu nubrau',
'exif-lightsource-12' => 'Fluorescenti lús diulna (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluorescenti Brancu-Dia (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluorescenti Brancu-Friu (W 3900 – 4500K)',
'exif-lightsource-15' => 'Fluorescenti brancu (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Lús estándal A',
'exif-lightsource-18' => 'Lús estándal B',
'exif-lightsource-19' => 'Lús estándal C',
'exif-lightsource-24' => "Tungstenu d'estuyu ISO",
'exif-lightsource-255' => 'Otra huenti e lús',

'exif-focalplaneresolutionunit-2' => 'purgás',

'exif-sensingmethod-1' => 'Nu definiu',
'exif-sensingmethod-7' => 'Sensol trilinial',

'exif-scenetype-1' => 'Una imahin diretamenti afotugrafiá',

'exif-customrendered-0' => 'Procesu normal',
'exif-customrendered-1' => 'Procesu presonalizau',

'exif-exposuremode-0' => 'Esposición autumática',
'exif-exposuremode-1' => 'Esposición manual',

'exif-whitebalance-0' => 'Balanci e brancu autumáticu',
'exif-whitebalance-1' => 'Balanci e brancu manual',

'exif-scenecapturetype-0' => 'Estándal',
'exif-scenecapturetype-1' => 'Paisahi',
'exif-scenecapturetype-2' => 'Retratu',
'exif-scenecapturetype-3' => 'Escena notúlnia',

'exif-gaincontrol-0' => 'Dengunu',
'exif-gaincontrol-1' => 'Umentu bahu e ganáncia',
'exif-gaincontrol-2' => 'Umentu artu e ganáncia',
'exif-gaincontrol-3' => 'Deminución baha e ganáncia',
'exif-gaincontrol-4' => 'Deminución arta e ganáncia',

'exif-contrast-0' => 'Nolmal',
'exif-contrast-1' => 'Suavi',
'exif-contrast-2' => 'Duru',

'exif-saturation-0' => 'Nolmal',
'exif-saturation-1' => 'Poca saturación',
'exif-saturation-2' => 'Mucha saturación',

'exif-sharpness-0' => 'Nolmal',
'exif-sharpness-1' => 'Suavi',
'exif-sharpness-2' => 'Dura',

'exif-subjectdistancerange-0' => 'Andarriu',
'exif-subjectdistancerange-1' => 'Macru',
'exif-subjectdistancerange-2' => 'Afechal vista',
'exif-subjectdistancerange-3' => 'Vista dendi largu',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitú norti',
'exif-gpslatitude-s' => 'Latitú sul',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Lonhitú esti',
'exif-gpslongitude-w' => 'Lonhitú oesti',

'exif-gpsstatus-a' => 'Miia en pogresu',
'exif-gpsstatus-v' => 'Enteloperabiliá e miia',

'exif-gpsmeasuremode-2' => 'Miia bidimensional',
'exif-gpsmeasuremode-3' => 'Miia tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Quilómetrus pol ora',
'exif-gpsspeed-m' => 'Millas pol ora',
'exif-gpsspeed-n' => 'Ñus',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direción verdaera',
'exif-gpsdirection-m' => 'Direción manética',

# External editor support
'edit-externally' => 'Eital esti archivu gastandu una apricación esterna',
'edit-externally-help' => 'Pa mas enholmación, lei las [//www.mediawiki.org/wiki/Manual:External_editors istrucionis de configuración] (en ingrés).',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tó',
'namespacesall' => 'tó',
'monthsall' => 'tó',

# Email address confirmation
'confirmemail' => 'Confirmal email',
'confirmemail_noemail' => 'Nu as escrebiu una direción d´email correta enas tus [[Special:Preferences|preferéncias]].',
'confirmemail_text' => "{{SITENAME}} requieri que confirmis la tu direción d'email enantis de gastal las huncionis de correu. Ativa el botón d'embahu pa envial un correu e confirmación a la tu direción. El correu incluirá un atihu con un cóigu; sigui el atihu pa confirmal la tu direción d'email.",
'confirmemail_pending' => "Un coigu e confirmación s'á enviau a la tu direción d'email; si acabihas de
crial la tu cuenta, aspera duranti angunus minutus a que te chegui el
correu enantis de solicital otru coigu.",
'confirmemail_send' => 'Envial un coigu e confirmación pol email',
'confirmemail_sent' => 'Email de confirmación enviau.',
'confirmemail_oncreate' => "S'á enviau un cóigu e confirmación a la tu direción de correu eletrónicu.
Esti cóigu nu es mestel pa entral ena tu cuenta, peru tendrás que dalu enantis d'atival cualisquiel hunción basá en correu eletrónicu nel güiqui.",
'confirmemail_sendfailed' => 'Nu es posibri envial el email de confirmación. Compreba que la direción esté bien escrita.

El correu degorvió: $1',
'confirmemail_invalid' => "Coigu de confirmación envaliu. Es posibri qu'aiga caucau.",
'confirmemail_needlogin' => "Es mestel $1 pa confirmal la tu direción d'email.",
'confirmemail_success' => "S'á confirmau la tu direción d'email. Ya pueis entral ena tu cuenta i embailti cola Güiqui.",
'confirmemail_loggedin' => "S'á confirmau la tu direción d'email.",
'confirmemail_error' => 'Marru al emburacal la tu confirmación.',
'confirmemail_subject' => 'Confirmaeru de direción de correu de {{SITENAME}}',
'confirmemail_body' => 'Yeu!

Alguien, siguramenti tú, á rustriu la cuenta "$2" dendi la direción
IP $1 con esta direción d\'email en {{SITENAME}}.

Pa confirmal qu\'esta cuenta es tuya i atival
las caraterísticas del email en {{SITENAME}}, abri esti atihu nel tu escrucaol:

$3

Si nu as siu tú quien á rustriu la cuenta, pursa nel siguienti atihu
pa cancelal la confirmación del email:

$5

El coigu de confirmación caucará a las $4.',
'confirmemail_invalidated' => "Confirmaeru d'email cancelau",
'invalidateemail' => "Cancelal el confirmaeru d'email",

# Scary transclusion
'scarytranscludedisabled' => '[El EntriGüiqui está desativau]',
'scarytranscludefailed' => '[Marru al cargal la prantilla pa $1]',
'scarytranscludetoolong' => '[La URL es mu larga]',

# Delete conflict
'deletedwhileediting' => 'Avisu: esta página á siu esborrá endispués de tu encetal a eitala!',
'confirmrecreate' => "El usuáriu [[User:$1|$1]] ([[User talk:$1|caraba]]) á esborrau esta páhina aluspués de que prencipiaras a eitala, pola siguienti razón:
: ''$2''
Pol favol, confirma si rialmenti quieis gorvel a crial la páhina.",
'recreate' => 'Gorvel a crial',

# action=purge
'confirm_purge_button' => 'Dalcuerdu',
'confirm-purge-top' => 'Esborral el caché desta páhina?',

# Multipage image navigation
'imgmultipageprev' => '← páhina anteriol',
'imgmultipagenext' => 'páhina siguienti →',
'imgmultigo' => 'Dil!',
'imgmultigoto' => 'Dil a la página $1',

# Table pager
'table_pager_next' => 'Páhina siguienti',
'table_pager_prev' => 'Páhina anteriol',
'table_pager_first' => 'Primel páhina',
'table_pager_last' => 'Úrtima páhina',
'table_pager_limit' => 'Muestral $1 artículus pol páhina',
'table_pager_limit_submit' => 'Dil',
'table_pager_empty' => 'Nu s´alcuentrun resurtaus',

# Auto-summaries
'autosumm-blank' => 'Esborrau el continiu la página',
'autosumm-replace' => "Páhina escambiá pol '$1'",
'autoredircomment' => 'Rederihiendu a [[$1]]',
'autosumm-new' => "Criá página con '$1'",

# Live preview
'livepreview-loading' => 'Cargandu…',
'livepreview-ready' => 'Cargandu… Listu!',
'livepreview-failed' => 'Marru cola "Live Preview"! Preba a previsoreal normalmenti.',
'livepreview-error' => 'Marru al conetal: $1 "$2". Preba a previsoreal normalmenti.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Es possibri que nu se muestrin los chambus hechus hadi menus de $1 {{PLURAL:$1|segundu|segundus}}.',
'lag-warn-high' => 'Ebiu a una arta laténcia el sirviol la basi e datus, los chambus hechus enos úrtimus $1 segundus puein nu sel muestraus nesta lista.',

# Watchlist editor
'watchlistedit-numitems' => 'Ena tu lista e seguimientu ai {{PLURAL:$1|1 entítulu|$1 entítulus}}, sin contal las carabas.',
'watchlistedit-noitems' => 'Nu ai entítulus ena tu lista e seguimientu.',
'watchlistedit-normal-title' => 'Eital la lista e seguimientu',
'watchlistedit-normal-legend' => 'Esborral entítulus de la lista e seguimientu',
'watchlistedit-normal-explain' => 'Los entítulus de la tu lista e seguimientu se muestran embahu. Pa esborral un entítulu, seleciona el cuairu d´al lau i pursa sobri "Esborral entítulus". Tamién pueis [[Special:EditWatchlist/raw|eital la lista]].',
'watchlistedit-normal-submit' => 'Esborral entítulus',
'watchlistedit-normal-done' => 'As esborrau {{PLURAL:$1|1 entítulu e|$1 entítulus de}} la tu lista e seguimientu:',
'watchlistedit-raw-title' => 'Eital lista e seguimientu',
'watchlistedit-raw-legend' => 'Eital lista e seguimientu',
'watchlistedit-raw-explain' => 'Se muestran embahu los entítulus de la tu lista e seguimientu, que puein sel eitaus
	añiendulus i esborrándulus de la lista; un entítulu pol línia. Cuandu acabihis, pursa sobri "Atualizal lista e seguimientu".
	Tamién pueis [[Special:EditWatchlist|gastal el eitol estándal]].',
'watchlistedit-raw-titles' => 'Entítulus:',
'watchlistedit-raw-submit' => 'Atualizal la lista e seguimientu',
'watchlistedit-raw-done' => 'La tu lista e seguimientu s´acabiha d´atualizal!',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 entítulu hue añiiu|$1 entítulus huerun añiius}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 entítulu hue esborrau|$1 entítulus huerun esborraus}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Vel chambus emportantis',
'watchlisttools-edit' => 'Vel i eital la lista e seguimientu',
'watchlisttools-raw' => 'Eital lista e seguimientu',

# Iranian month names
'iranian-calendar-m1' => '1 mes Jalāli',
'iranian-calendar-m2' => '2 mes Jalāli',
'iranian-calendar-m3' => '3 mes Jalāli',
'iranian-calendar-m4' => '4 mes Jalāli',
'iranian-calendar-m5' => '5 mes Jalāli',
'iranian-calendar-m6' => '6 mes Jalāli',
'iranian-calendar-m7' => '7 mes Jalāli',
'iranian-calendar-m8' => '8 mes Jalāli',
'iranian-calendar-m9' => '9 mes Jalāli',
'iranian-calendar-m10' => '10 mes Jalāli',
'iranian-calendar-m11' => '11 mes Jalāli',
'iranian-calendar-m12' => '12 mes Jalāli',

# Core parser functions
'unknown_extension_tag' => 'estensión andarria: "$1"',

# Special:Version
'version' => 'Velsión',
'version-extensions' => 'Estensionis istalás',
'version-specialpages' => 'Páhinas especialis',
'version-variables' => 'Variabris',
'version-other' => 'Otru',
'version-hook-name' => 'Nombri el Hook',
'version-hook-subscribedby' => 'Suscritu pol',
'version-version' => '(Velsión $1)',
'version-license' => 'Licéncia',
'version-software' => 'Software istalau',
'version-software-product' => 'Proutu',
'version-software-version' => 'Velsión',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Landeal archivus dupricaus',
'fileduplicatesearch-legend' => 'Landeal dupricaus',
'fileduplicatesearch-filename' => 'Nombri el archivu:',
'fileduplicatesearch-submit' => 'Landeal',
'fileduplicatesearch-info' => '$1 × $2 pixel<br />Grandol del archivu: $3<br />Crasi MIME: $4',
'fileduplicatesearch-result-1' => 'El archivu "$1" nu tiini dupricaus.',
'fileduplicatesearch-result-n' => 'El archivu "$1" tiini {{PLURAL:$2|1 dupricau igual|$2 dupricaus igualis}}.',

# Special:SpecialPages
'specialpages' => 'Páhinas especialis',
'specialpages-group-other' => 'Otras páhinas especialis',
'specialpages-group-login' => 'Entral / Crial cuenta',
'specialpages-group-changes' => 'Úrtimus chambus i rustrihus',
'specialpages-group-users' => 'Usuárius i derechus',
'specialpages-group-highuse' => 'Páginas mas visoreás',
'specialpages-group-pages' => 'Lista de páginas',
'specialpages-group-pagetools' => 'Herramientas de página',
'specialpages-group-wiki' => 'Datus Wiki i herramientas',
'specialpages-group-spam' => 'Herramientas de Spam',

# Special:BlankPage
'blankpage' => 'Branqueal página',

# Special:Tags
'tags-edit' => 'eital',

# Database error messages
'dberr-header' => 'Marru ena wiki',

# New logging system
'revdelete-restricted' => 'las restricionis a los çahorilis án siu apricás',
'revdelete-unrestricted' => 'las restricionis a los çahorilis án siu esborrás',
'rightsnone' => '(dengunu)',

);
