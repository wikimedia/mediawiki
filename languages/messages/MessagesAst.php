<?php
/** Asturian (asturianu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Esbardu
 * @author Kaganer
 * @author Mikel
 * @author Remember the dot
 * @author Savh
 * @author Xuacu
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Alderique',
	NS_USER             => 'Usuariu',
	NS_USER_TALK        => 'Usuariu_alderique',
	NS_PROJECT_TALK     => '$1_alderique',
	NS_FILE             => 'Archivu',
	NS_FILE_TALK        => 'Archivu_alderique',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_alderique',
	NS_TEMPLATE         => 'Plantía',
	NS_TEMPLATE_TALK    => 'Plantía_alderique',
	NS_HELP             => 'Aida',
	NS_HELP_TALK        => 'Aida_alderique',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Categoría_alderique',
);

$namespaceAliases = array(
	'Imaxe' => NS_FILE,
	'Imaxe alderique' => NS_FILE_TALK,
	'Discusión'           => NS_TALK,
	'Usuariu_discusión'   => NS_USER_TALK,
	'$1_discusión'        => NS_PROJECT_TALK,
	'Imaxen'              => NS_FILE,
	'Imaxen_discusión'    => NS_FILE_TALK,
	'MediaWiki_discusión' => NS_MEDIAWIKI_TALK,
	'Plantilla'           => NS_TEMPLATE,
	'Plantilla_discusión' => NS_TEMPLATE_TALK,
	'Ayuda'               => NS_HELP,
	'Ayuda_discusión'     => NS_HELP_TALK,
	'Categoría_discusión' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Block'                     => array( 'Bloquiar', 'BloquiarIP', 'BloquiarUsuariu' ),
	'Log'                       => array( 'Rexistru' ),
	'Statistics'                => array( 'Estadístiques' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Sorrayar enllaces:',
'tog-justify' => 'Xustificar parágrafos',
'tog-hideminor' => 'Anubrir les ediciones menores nos cambeos recientes',
'tog-hidepatrolled' => 'Anubrir les ediciones vixilaes nos cambeos recientes',
'tog-newpageshidepatrolled' => 'Anubrir les páxines vixilaes na llista de páxines nueves',
'tog-extendwatchlist' => "Espander la llista de vixilancia p'amosar tolos cambeos, non solo los más recientes.",
'tog-usenewrc' => 'Agrupar los cambeos por páxina nos cambeos recientes y na llista de vixilancia (necesita JavaScript)',
'tog-numberheadings' => 'Autonumberar los encabezaos',
'tog-showtoolbar' => "Amosar la barra de ferramientes d'edición (JavaScript)",
'tog-editondblclick' => 'Editar páxines con doble clic (necesita JavaScript)',
'tog-editsection' => "Activar la edición de seiciones per aciu d'enllaces [editar]",
'tog-editsectiononrightclick' => 'Activar la edición de seiciones calcando col botón drechu nos títulos de seición (necesita JavaScript)',
'tog-showtoc' => 'Amosar índiz (pa páxines con más de 3 encabezaos)',
'tog-rememberpassword' => 'Recordar la mio identificación nesti ordenador (hasta un máximu de $1 {{PLURAL:$1|día|díes}})',
'tog-watchcreations' => 'Amestar les páxines que creo y los ficheros que cargo a la mio llista de vixilancia',
'tog-watchdefault' => "Amestar les páxines y ficheros qu'edito a la mio llista de vixilancia",
'tog-watchmoves' => 'Amestar les páxines y ficheros que muevo a la mio llista de vixilancia',
'tog-watchdeletion' => 'Amestar les páxines y ficheros que desanicio a la mio llista de vixilancia',
'tog-minordefault' => 'Marcar toles ediciones como menores por defeutu',
'tog-previewontop' => "Amosar previsualización enantes de la caxa d'edición",
'tog-previewonfirst' => 'Amosar previsualización na primer edición',
'tog-nocache' => 'Desactivar la caché de páxines del restolador',
'tog-enotifwatchlistpages' => 'Mandame un corréu cuando camude una páxina o ficheru de la mio llista de vixilancia',
'tog-enotifusertalkpages' => "Mandame un corréu cuando camude la mio páxina d'alderique",
'tog-enotifminoredits' => 'Mandame tamién un corréu cuando heba ediciones menores de les páxines y ficheros',
'tog-enotifrevealaddr' => 'Amosar la mio direición de corréu nos correos de notificación',
'tog-shownumberswatching' => "Amosar el númberu d'usuarios que tán vixilando la páxina",
'tog-oldsig' => 'Firma esistente:',
'tog-fancysig' => 'Tratar la firma como testu wiki (ensin enllaz automáticu)',
'tog-externaleditor' => 'Usar un editor esternu por defeutu (namái pa espertos, necesita configuraciones especiales nel to ordenador. [//www.mediawiki.org/wiki/Manual:External_editors Más información.])',
'tog-externaldiff' => 'Usar un diff esternu por defutu (namái pa espertos, necesita configuraciones especiales nel to ordenador. [//www.mediawiki.org/wiki/Manual:External_editors Más información.])',
'tog-showjumplinks' => 'Activar los enllaces d\'accesibilidá "saltar a"',
'tog-uselivepreview' => 'Usar vista previa en tiempu real (necesita JavaScript) (en pruebes)',
'tog-forceeditsummary' => "Avisame cuando grabe col resume d'edición en blanco",
'tog-watchlisthideown' => 'Anubrir les mios ediciones na llista de vixilancia',
'tog-watchlisthidebots' => 'Anubrir les ediciones de bots na llista de vixilancia',
'tog-watchlisthideminor' => 'Anubrir les ediciones menores na llista de vixilancia',
'tog-watchlisthideliu' => "Anubrir les ediciones d'usuarios identificaos na llista de vixilancia",
'tog-watchlisthideanons' => "Anubrir les ediciones d'usuarios anónimos na llista de vixilancia",
'tog-watchlisthidepatrolled' => 'Anubrir les ediciones vixilaes de la llista de vixilancia',
'tog-ccmeonemails' => "Mandame copies de los correos qu'unvio a otros usuarios",
'tog-diffonly' => 'Nun amosar el conteníu de la páxina embaxo de les diferencies',
'tog-showhiddencats' => 'Amosar categoríes anubríes',
'tog-noconvertlink' => 'Desactivar la conversión del títulu del enllaz',
'tog-norollbackdiff' => 'Desaniciar les diferencies depués de facer una restauración',

'underline-always' => 'Siempre',
'underline-never' => 'Nunca',
'underline-default' => 'Predeterminao del aspeutu o del restolador',

# Font style option in Special:Preferences
'editfont-style' => "Estilu de fonte de l'área d'edición:",
'editfont-default' => 'Restolador por defeutu',
'editfont-monospace' => 'Tipu de lletra monoespaciada',
'editfont-sansserif' => 'Tipu de lletra Sans-serif',
'editfont-serif' => 'TIpu de lletra Serif',

# Dates
'sunday' => 'domingu',
'monday' => 'llunes',
'tuesday' => 'martes',
'wednesday' => 'miércoles',
'thursday' => 'xueves',
'friday' => 'vienres',
'saturday' => 'sábadu',
'sun' => 'dom',
'mon' => 'llu',
'tue' => 'mar',
'wed' => 'mié',
'thu' => 'xue',
'fri' => 'vie',
'sat' => 'sáb',
'january' => 'xineru',
'february' => 'febreru',
'march' => 'marzu',
'april' => 'abril',
'may_long' => 'mayu',
'june' => 'xunu',
'july' => 'xunetu',
'august' => 'agostu',
'september' => 'setiembre',
'october' => 'ochobre',
'november' => 'payares',
'december' => 'avientu',
'january-gen' => 'de xineru',
'february-gen' => 'de febreru',
'march-gen' => 'de marzu',
'april-gen' => "d'abril",
'may-gen' => 'de mayu',
'june-gen' => 'de xunu',
'july-gen' => 'de xunetu',
'august-gen' => "d'agostu",
'september-gen' => 'de setiembre',
'october-gen' => "d'ochobre",
'november-gen' => 'de payares',
'december-gen' => "d'avientu",
'jan' => 'xin',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'abr',
'may' => 'may',
'jun' => 'xun',
'jul' => 'xnt',
'aug' => 'ago',
'sep' => 'set',
'oct' => 'och',
'nov' => 'pay',
'dec' => 'avi',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categoría|Categoríes}}',
'category_header' => 'Páxines na categoría «$1»',
'subcategories' => 'Subcategoríes',
'category-media-header' => 'Ficheros multimedia na categoría «$1»',
'category-empty' => "''Anguaño esta categoría nun tien nengún artículu nin ficheru multimedia.''",
'hidden-categories' => '{{PLURAL:$1|Categoría anubrida|Categoríes anubríes}}',
'hidden-category-category' => 'Categoríes anubríes',
'category-subcat-count' => "{{PLURAL:$2|Esta categoría tien namái la subcategoría siguiente.|Esta categoría tien {{PLURAL:$1|la siguiente subcategoría|les siguientes $1 subcategoríes}}, d'un total de $2.}}",
'category-subcat-count-limited' => 'Esta categoría tien {{PLURAL:$1|la subcategoría siguiente|les $1 subcategoríes siguientes}}.',
'category-article-count' => "{{PLURAL:$2|Esta categoría contién namái la páxina siguiente.|{{PLURAL:$1|La páxina siguiente ta|Les $1 páxines siguientes tán}} nesta categoría, d'un total de $2.}}",
'category-article-count-limited' => '{{PLURAL:$1|La páxina siguiente ta|Les $1 páxines siguientes tán}} na categoría actual.',
'category-file-count' => "{{PLURAL:$2|Esta categoría contién namái'l ficheru siguiente.|{{PLURAL:$1|El ficheru siguiente ta|Los $1 ficheros siguientes tán}} nesta categoría, d'un total de $2.}}",
'category-file-count-limited' => '{{PLURAL:$1El ficheru siguiente ta|Los $1 ficheeros siguientes tán}} na categoría actual.',
'listingcontinuesabbrev' => 'cont.',
'index-category' => 'Páxines indexaes',
'noindex-category' => 'Páxines non indexaes',
'broken-file-category' => 'Páxines con enllaces frañíos a ficheros',

'about' => 'Tocante a',
'article' => 'Páxina de conteníu',
'newwindow' => "(s'abre nuna ventana nueva)",
'cancel' => 'Encaboxar',
'moredotdotdot' => 'Más...',
'mypage' => 'Páxina',
'mytalk' => 'Alderique',
'anontalk' => 'Alderique pa esta IP',
'navigation' => 'Navegación',
'and' => '&#32;y',

# Cologne Blue skin
'qbfind' => 'Guetar',
'qbbrowse' => 'Restolar',
'qbedit' => 'Editar',
'qbpageoptions' => 'Esta páxina',
'qbpageinfo' => 'Contestu',
'qbmyoptions' => 'Les mios páxines',
'qbspecialpages' => 'Páxines especiales',
'faq' => 'EMF (entrugues más frecuentes)',
'faqpage' => 'Project:EMF',

# Vector skin
'vector-action-addsection' => 'Amestar seición',
'vector-action-delete' => 'Desaniciar',
'vector-action-move' => 'Treslladar',
'vector-action-protect' => 'Protexer',
'vector-action-undelete' => 'Restaurar',
'vector-action-unprotect' => 'Camudar la proteición',
'vector-simplesearch-preference' => 'Activar la barra de gueta simplificada (namái apariencia Vector)',
'vector-view-create' => 'Crear',
'vector-view-edit' => 'Editar',
'vector-view-history' => 'Ver historial',
'vector-view-view' => 'Lleer',
'vector-view-viewsource' => 'Ver fonte',
'actions' => 'Aiciones',
'namespaces' => 'Espacios de nome',
'variants' => 'Variantes',

'errorpagetitle' => 'Error',
'returnto' => 'Tornar a $1.',
'tagline' => 'De {{SITENAME}}',
'help' => 'Ayuda',
'search' => 'Guetar',
'searchbutton' => 'Guetar',
'go' => 'Dir',
'searcharticle' => 'Dir',
'history' => 'Historial de la páxina',
'history_short' => 'Historial',
'updatedmarker' => 'anovada dende la mio visita cabera',
'printableversion' => 'Versión pa imprentar',
'permalink' => 'Enllaz permanente',
'print' => 'Imprentar',
'view' => 'Ver',
'edit' => 'Editar',
'create' => 'Crear',
'editthispage' => 'Editar esta páxina',
'create-this-page' => 'Crear esta páxina',
'delete' => 'Desaniciar',
'deletethispage' => 'Desaniciar esta páxina',
'undelete_short' => 'Restaurar {{PLURAL:$1|una edición|$1 ediciones}}',
'viewdeleted_short' => 'Ver {{PLURAL:$1|una edición desaniciada|$1 ediciones desaniciaes}}',
'protect' => 'Protexer',
'protect_change' => 'camudar',
'protectthispage' => 'Protexer esta páxina',
'unprotect' => 'Camudar la proteición',
'unprotectthispage' => "Camudar la proteición d'esta páxina",
'newpage' => 'Páxina nueva',
'talkpage' => 'Aldericar sobre esta páxina',
'talkpagelinktext' => 'Alderique',
'specialpage' => 'Páxina especial',
'personaltools' => 'Ferramientes personales',
'postcomment' => 'Seición nueva',
'articlepage' => 'Ver la páxina de conteníu',
'talk' => 'Alderique',
'views' => 'Vistes',
'toolbox' => 'Ferramientes',
'userpage' => "Ver la páxina d'usuariu",
'projectpage' => 'Ver la páxina del proyeutu',
'imagepage' => 'Ver la páxina del ficheru',
'mediawikipage' => 'Ver la páxina de mensaxes',
'templatepage' => 'Ver la páxina de plantía',
'viewhelppage' => "Ver la páxina d'ayuda",
'categorypage' => 'Ver la páxina de la categoría',
'viewtalkpage' => "Ver la páxina d'alderique",
'otherlanguages' => "N'otres llingües",
'redirectedfrom' => '(Redirixío dende $1)',
'redirectpagesub' => 'Páxina de redireición',
'lastmodifiedat' => "Esta páxina camudóse la postrera vegada'l $1 a les $2.",
'viewcount' => 'Esta páxina visitóse {{PLURAL:$1|una vegada|$1 vegaes}}.',
'protectedpage' => 'Páxina protexida',
'jumpto' => 'Saltar a:',
'jumptonavigation' => 'navegación',
'jumptosearch' => 'guetar',
'view-pool-error' => 'Sentímoslo, los sirvidores tán sobrecargaos nestos momentos.
Hai demasiaos usuarios faciendo por ver esta páxina.
Por favor espera un cachín enantes de volver intentar acceder a esta páxina.

$1',
'pool-timeout' => 'Tiempu escosáu esperando pal bloquéu',
'pool-queuefull' => 'La cola de trabayu ta enllena',
'pool-errorunknown' => 'Fallu desconocíu',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Tocante a {{SITENAME}}',
'aboutpage' => 'Project:Tocante a',
'copyright' => 'El conteníu ta disponible baxo los términos de llicencia  $1.',
'copyrightpage' => "{{ns:project}}:Drechos d'autor",
'currentevents' => 'Actualidá',
'currentevents-url' => 'Project:Actualidá',
'disclaimers' => 'Avisu llegal',
'disclaimerpage' => 'Project:Avisu xeneral',
'edithelp' => "Ayuda d'edición",
'edithelppage' => 'Help:Cómo editar una páxina',
'helppage' => 'Help:Conteníu',
'mainpage' => 'Portada',
'mainpage-description' => 'Portada',
'policy-url' => 'Project:Polítiques',
'portal' => 'Portal de la comunidá',
'portal-url' => 'Project:Portal de la comunidá',
'privacy' => 'Política de proteición de datos',
'privacypage' => 'Project:Política de proteición de datos',

'badaccess' => 'Fallu de permisos',
'badaccess-group0' => "Nun tienes permisu pa executar l'aición solicitada.",
'badaccess-groups' => "L'aición solicitada ta acutada pa usuarios {{PLURAL:$2|del grupu|d'ún d'estos grupos}}: $1.",

'versionrequired' => 'Necesítase la versión $1 de MediaWiki',
'versionrequiredtext' => 'Necesítase la versión $1 de MediaWiki pa usar esta páxina. Ver la [[Special:Version|páxina de versión]].',

'ok' => 'Aceutar',
'retrievedfrom' => 'Sacáu de «$1»',
'youhavenewmessages' => 'Tienes $1 ($2).',
'newmessageslink' => 'mensaxes nuevos',
'newmessagesdifflink' => 'cambéu postreru',
'youhavenewmessagesfromusers' => "Tienes $1 {{PLURAL:$3|d'otru usuariu|de $3 usuarios}} ($2).",
'youhavenewmessagesmanyusers' => 'Tienes $1 de munchos usuarios ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|un mensaxe nuevu|$1 mensaxes nuevos}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|cambéu postreru|cambeos postreros}}',
'youhavenewmessagesmulti' => 'Tienes mensaxes nuevos en $1',
'editsection' => 'editar',
'editold' => 'editar',
'viewsourceold' => 'ver fonte',
'editlink' => 'editar',
'viewsourcelink' => 'ver fonte',
'editsectionhint' => 'Editar seición: $1',
'toc' => 'Índiz',
'showtoc' => 'amosar',
'hidetoc' => 'anubrir',
'collapsible-collapse' => 'Plegar',
'collapsible-expand' => 'Espander',
'thisisdeleted' => '¿Ver o restaurar $1?',
'viewdeleted' => '¿Ver $1?',
'restorelink' => '{{PLURAL:$1|una edición desaniciada|$1 ediciones desaniciaes}}',
'feedlinks' => 'Canal:',
'feed-invalid' => 'Suscripción non válida a la triba de canal.',
'feed-unavailable' => 'Les canales de sindicación nun tán disponibles',
'site-rss-feed' => 'Canal RSS $1',
'site-atom-feed' => 'Canal Atom $1',
'page-rss-feed' => 'Canal RSS «$1»',
'page-atom-feed' => 'Canal Atom «$1»',
'red-link-title' => '$1 (la páxina nun esiste)',
'sort-descending' => 'Orde descendente',
'sort-ascending' => 'Orde ascendente',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Páxina',
'nstab-user' => "Páxina d'usuariu",
'nstab-media' => 'Páxina de ficheru multimedia',
'nstab-special' => 'Páxina especial',
'nstab-project' => 'Páxina del proyeutu',
'nstab-image' => 'Ficheru',
'nstab-mediawiki' => 'Mensaxe',
'nstab-template' => 'Plantía',
'nstab-help' => 'Ayuda',
'nstab-category' => 'Categoría',

# Main script and global functions
'nosuchaction' => 'Nun esiste esa aición',
'nosuchactiontext' => "L'aición especificada pola URL nun ye válida.
Seique escribieras mal la URL o siguieras un enllaz incorreutu.
Tamién podría ser un bug nel software usáu por {{SITENAME}}.",
'nosuchspecialpage' => 'Nun esiste esa páxina especial',
'nospecialpagetext' => '<strong>Pidisti una páxina especial non válida.</strong>

Pues atopar una llista de les páxines especiales válides en [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Error',
'databaseerror' => 'Fallu na base de datos',
'dberrortext' => "Hebo un fallu de sintaxis nuna consulta a la base de datos.
Esti fallu pue ser por un problema del software.
La postrer consulta a la base de datos que s'intentó foi:
<blockquote><code>$1</code></blockquote>
dende la función «<code>$2</code>».
La base datos dio'l fallu «<samp>$3: $4</samp>».",
'dberrortextcl' => "Hebo un fallu de sintaxis nuna consulta a la base de datos.
La postrer consulta de la base de datos que s'intentó foi:
«$1»
dende la función «$2».
La base de datos dio'l fallu «$3: $4».",
'laggedslavemode' => "'''Avisu:''' Esta páxina pue que nun tenga actualizaciones recientes.",
'readonly' => 'Base de datos candada',
'enterlockreason' => 'Introduz un motivu pal candáu, amestando una estimación de cuándo va tener llugar el descandáu',
'readonlytext' => "Nestos momentos la base de datos ta candada pa nueves entraes y otres modificaciones, seique por un mantenimientu de rutina, volviendo tar accesible cuando esti dea fin.

L'alministrador que la candó conseñó esti motivu: $1",
'missing-article' => "La base de datos nun atopó'l testu d'una páxina qu'hubiera tener alcontrao, nomada «\$1» \$2.

Esto débese davezu a siguir una \"dif\" caducada o un enllaz d'historial a una páxina que se desanició.

Si esti nun ye'l casu, seique tengas atopao un bug nel software.
Por favor informa d'esto a un [[Special:ListUsers/sysop|alministrador]], anotando la URL.",
'missingarticle-rev' => '(núm. revisión: $1)',
'missingarticle-diff' => '(dif: $1, $2)',
'readonly_lag' => 'La base de datos candóse automáticamente mentes los sirvidores de la base de datos esclava se sincronicen cola maestra',
'internalerror' => 'Fallu internu',
'internalerror_info' => 'Fallu internu: $1',
'fileappenderrorread' => "Nun se pudo lleer «$1» mentanto l'amestadura.",
'fileappenderror' => 'Nun se pudo amestar «$1» a «$2».',
'filecopyerror' => 'Nun se pudo copiar el ficheru «$1» como «$2».',
'filerenameerror' => 'Nun se pudo renomar el ficheru «$1» como «$2».',
'filedeleteerror' => 'Nun se pudo desaniciar el ficheru «$1».',
'directorycreateerror' => 'Nun se pudo crear el direutoriu «$1».',
'filenotfound' => 'Nun se pudo atopar el ficheru «$1».',
'fileexistserror' => 'Nun se pue escribir nel archivu «$1»: yá esiste',
'unexpected' => 'Valor inesperáu: «$1»=«$2».',
'formerror' => 'Fallu: Nun se pudo unviar el formulariu.',
'badarticleerror' => 'Esta aición nun pue facese nesta páxina.',
'cannotdelete' => "Nun pudo desaniciase la páxina o'l ficheru «$1».
Seique daquién yá lo desaniciara.",
'cannotdelete-title' => 'Nun se pue desaniciar la páxina «$1»',
'delete-hook-aborted' => 'Desaniciu albortáu pol hook.
Nun conseñó esplicación.',
'badtitle' => 'Títulu incorreutu',
'badtitletext' => 'El títulu de páxina solicitáu nun ye válidu, ta baleru o tien enllaces interllingua o interwiki incorreutos.
Pue contener un caráuter o más que nun se puen usar nos títulos.',
'perfcached' => "Los datos siguientes tán na caché y seique nun tean anovaos. Hai un máximu {{PLURAL:$1|d'un resultáu disponible|de $1 resultaos disponibles}} na caché.",
'perfcachedts' => "Los datos siguientes tán na caché y anováronse per postrer vegada'l $1. Hai un máximu {{PLURAL:$4|d'un resultáu disponible|de $4 resultaos disponibles}} na caché.",
'querypage-no-updates' => "Anguaño los anovamientos d'esta páxina tán desactivaos.
Estos datos nun van refrescase nestos momentos.",
'wrong_wfQuery_params' => 'Parámetros incorreutos pa wfQuery()<br />
Función: $1<br />
Consulta: $2',
'viewsource' => 'Ver fonte',
'viewsource-title' => 'Ver la fonte de «$1»',
'actionthrottled' => 'Aición llendada',
'actionthrottledtext' => 'Como midida anti-spam, nun se pue repetir esta aición munches vegaes en pocu tiempu, y trespasasti esi llímite.
Por favor vuelvi intentalo nunos minutos.',
'protectedpagetext' => 'Esta páxina ta candada pa torgar ediciones y otres aiciones.',
'viewsourcetext' => "Pues ver y copiar la fonte d'esta páxina:",
'viewyourtext' => "Pues ver y copiar la fonte de '''les tos ediciones''' d'esta páxina:",
'protectedinterface' => "Esta páxina proporciona'l testu de la interfaz del software d'esta wiki, y ta candada pa torgar abusos.
P'amestar o cambiar les traducciones de toles wikis, por favor usa [//translatewiki.net/translatewiki.net], el proyeutu de llocalización de MediaWiki.",
'editinginterface' => "'''Avisu:''' Tas editando una páxina que s'usa pa proporcionar el testu d'interfaz del programa.
Los cambeos nesta páxina van afeutar l'apariencia de la interfaz pa otros usuarios d'esta wiki.
P'amestar o camudar traducciones pa toles wikis, por favor, usa [//translatewiki.net/ translatewiki.net], el proyeutu de traducción de MediaWiki.",
'sqlhidden' => '(consulta SQL anubrida)',
'cascadeprotected' => "Esta páxina ta protexida d'ediciones porque ta enxerta {{PLURAL:$1|na siguiente páxina|nes siguientes páxines}}, que {{PLURAL:$1|ta protexida|tán protexíes}} cola opción «en cascada» activada:
$2",
'namespaceprotected' => "Nun tienes permisu pa editar páxines nel espaciu de nomes '''$1'''.",
'customcssprotected' => "Nun tienes permisu pa editar esta páxina CSS porque contién preferencies personales d'otru usuariu.",
'customjsprotected' => "Nun tienes permisu pa editar esta páxina de JavaScript porque contién preferencies personales d'otru usuariu.",
'ns-specialprotected' => 'Les páxines especiales nun se puen editar.',
'titleprotected' => "Esti títulu foi protexíu de la so creación por [[User:$1|$1]].
El motivu conseñáu ye «''$2''».",
'filereadonlyerror' => "Nun se pudo camudar el ficheru «$1» porque'l repositoriu de ficheros «$2» ta en mou de namái llectura.

L'alministrador que lu bloquió dio esti motivu: «$3».",
'invalidtitle-knownnamespace' => 'Títulu non válidu col espaciu de nomes «$2» y el testu «$3»',
'invalidtitle-unknownnamespace' => "Titulu non válidu col númberu $1 d'espaciu de nomes desconocíu y el testu «$2»",
'exception-nologin' => 'Non identificáu',
'exception-nologin-text' => "Esta páxina o aición necesita qu'anicies sesión nesta wiki.",

# Virus scanner
'virus-badscanner' => "Fallu de configuración: Escáner de virus desconocíu: ''$1''",
'virus-scanfailed' => "fallu d'escanéu (códigu $1)",
'virus-unknownscanner' => 'antivirus desconocíu:',

# Login and logout pages
'logouttext' => "'''Agora tas desconeutáu.'''

Pues siguir usando {{SITENAME}} de forma anónima, o pues [[Special:UserLogin|volver entrar]] como'l mesmu o como otru usuariu.
Ten en cuenta que dalgunes páxines puen siguir apaeciendo como si tovía tuvieres coneutáu, hasta que llimpies la caché del restolador.",
'welcomecreation' => "== ¡Bienllegáu, $1! ==
Creóse la to cuenta.
Nun t'escaezas d'escoyer les tos [[Special:Preferences|preferencies de {{SITENAME}}]].",
'yourname' => "Nome d'usuariu:",
'yourpassword' => 'Contraseña:',
'yourpasswordagain' => 'Escribi otra vuelta la contraseña:',
'remembermypassword' => "Recordar la mio identificación nesti restolador (un máximu {{PLURAL:$1|d'un día|de $1 díes}})",
'securelogin-stick-https' => "Siguir coneutáu al HTTPS dempués d'identificase",
'yourdomainname' => 'El to dominiu:',
'password-change-forbidden' => 'Nun se pueden camudar les contraseñes nesta wiki.',
'externaldberror' => "O hebo un fallu d'autenticación de la base de datos o nun tienes permisu p'anovar la to cuenta esterna.",
'login' => 'Identificase',
'nav-login-createaccount' => 'Identificase / crear una cuenta',
'loginprompt' => 'Has tener les «cookies» activaes pa identificate en {{SITENAME}}.',
'userlogin' => 'Identificase / crear una cuenta',
'userloginnocreate' => 'Identificase',
'logout' => 'Dexar de tar identificáu',
'userlogout' => 'Colar',
'notloggedin' => 'Non identificáu',
'nologin' => '¿Nun tienes una cuenta? $1.',
'nologinlink' => 'Crear una cuenta',
'createaccount' => 'Crear una cuenta',
'gotaccount' => '¿Ya tienes una cuenta? $1.',
'gotaccountlink' => 'Identificase',
'userlogin-resetlink' => "¿Escaecisti los datos d'identificación?",
'createaccountmail' => 'Per corréu electrónicu',
'createaccountreason' => 'Motivu:',
'badretype' => "Les claves qu'escribisti nun concuayen.",
'userexists' => "El nome d'usuariu conseñáu yá ta usándose.
Por favor escueyi un nome diferente.",
'loginerror' => "Fallu d'identificación",
'createaccounterror' => 'Nun se pudo crear la cuenta: $1',
'nocookiesnew' => "La cuenta d'usuariu ta creada, pero nun tas identificáu.
{{SITENAME}} usa «cookies» pa identificar a los usuarios.
Tienes les «cookies» desactivaes.
Por favor activales y depués entra col to nome d'usuariu y clave nuevos.",
'nocookieslogin' => '{{SITENAME}} usa «cookies» pa identificar a los usuarios.
Tienes les «cookies» desactivaes.
Por favor actívales y vuelvi intentalo.',
'nocookiesfornew' => "La cuenta nun se creó porque nun pudimos confirmar l'orixe.
Asegúrate de tener activaes les «cookies», recarga esta páxina y vuelvi intentalo.",
'noname' => "Nun conseñasti un nome d'usuariu válidu.",
'loginsuccesstitle' => 'Identificación correuta',
'loginsuccess' => "'''Quedasti identificáu en {{SITENAME}} como «$1».'''",
'nosuchuser' => "Nun hai nengún usuariu col nome «$1».
Los nomes d'usuariu distinguen mayúscules y minúscules.
Mira que tea bien escritu o [[Special:UserLogin/signup|crea una cuenta nueva]].",
'nosuchusershort' => 'Nun hai nengún usuariu col nome «$1».
Mira que tea bien escritu.',
'nouserspecified' => "Has especificar un nome d'usuariu.",
'login-userblocked' => 'Esti usuariu ta bloquiáu. Nun se permite la so identificación.',
'wrongpassword' => 'La clave escrita ye incorreuta.
Vuelvi intentalo.',
'wrongpasswordempty' => 'La clave taba en blanco.
Vuelvi intentalo.',
'passwordtooshort' => 'Les claves han tener a lo menos {{PLURAL:$1|1 caráuter|$1 carauteres}}.',
'password-name-match' => "La clave ha ser distinta del nome d'usuariu.",
'password-login-forbidden' => "Ta torgao usar esti nome d'usuariu y clave.",
'mailmypassword' => 'Unviar la clave nueva per corréu',
'passwordremindertitle' => 'Clave nueva provisional pa {{SITENAME}}',
'passwordremindertext' => "Daquién (seique tu, dende la direición IP $1) solicitó una clave
nueva pa {{SITENAME}} ($4). Creóse una clave provisional pal usuariu
«$2» que ye «$3». Si fuisti tu, necesites identificate y escoyer
una clave nueva agora. La clave provisional caduca {{PLURAL:$5|nun día|en $5 díes}}.

Si esta solicitú la fizo otra persona, o si recuerdes la clave y
nun quies volver camudala, pues escaecete d'esti mensaxe y siguir
usando la clave antigua.",
'noemail' => "L'usuariu «$1» nun tien rexistrada nenguna direición de corréu.",
'noemailcreate' => 'Has conseñar una direición de corréu válida',
'passwordsent' => 'Univóse una clave nueva a la direición de corréu rexistrada pa «$1».
Por favor vuelvi identificate depués de recibila.',
'blocked-mailpassword' => 'Ta bloquiada la edición dende la to direición IP, polo que pa evitar abusos nun se pue usar la función de recuperación de clave.',
'eauthentsent' => "Unvióse un corréu electrónicu de confirmación a la direición indicada.
Enantes de que s'unvie nengún otru corréu a la cuenta, has siguir les instrucciones del corréu electrónicu pa confirmar que la cuenta ye de to.",
'throttled-mailpassword' => "Yá s'unvió un recordatoriu de la clave {{PLURAL:$1|na postrer hora|nes postreres $1 hores}}.
Pa evitar abusos, namái s'unviará un recordatoriu cada {{PLURAL:$1|hora|$1 hores}}.",
'mailerror' => 'Fallu al unviar el corréu: $1',
'acct_creation_throttle_hit' => "Los visitantes d'esta wiki qu'usen la to direición IP yá crearon güei {{PLURAL:$1|1 cuenta|$1 cuentes}}, que ye'l máximu almitíu nesti periodu de tiempu.
Poro, los visitantes qu'usen esta direición IP nun puen crear más cuentes de momentu.",
'emailauthenticated' => "La to direición de corréu electrónicu confirmóse'l $2 a les $3.",
'emailnotauthenticated' => 'La to direición de corréu electrónicu nun se comprobó entá.
Nun va unviase nengún corréu pa nenguna de les funciones siguientes.',
'noemailprefs' => 'Conseña una direición de corréu electrónicu nes tos preferencies pa que funcionen eses carauterístiques.',
'emailconfirmlink' => 'Confirmar la direición de corréu electrónicu',
'invalidemailaddress' => "La direición de corréu electrónicu nun se pue aceutar yá que paez tener un formatu non válidu.
Por favor conseña una direición con formatu afayadizu o dexa baleru'l campu.",
'cannotchangeemail' => 'Les direiciones de corréu electrónicu de la cuenta nun puen camudase nesta wiki.',
'emaildisabled' => 'Esti sitiu nun pue unviar correos electrónicos.',
'accountcreated' => 'Cuenta creada',
'accountcreatedtext' => "Creóse la cuenta d'usuariu de $1.",
'createaccount-title' => 'Creación de cuenta pa {{SITENAME}}',
'createaccount-text' => 'Daquién creó una cuenta cola to direición de corréu electrónicu en {{SITENAME}} ($4) col nome «$2», asociada a la clave «$3».
Habríes identificte y camudar la clave agora.

Pues escaecer esti mensaxe si esta cuenta se creó por error.',
'usernamehasherror' => "El nome d'usuariu nun pue contener carauteres «hash»",
'login-throttled' => "Ficisti demasiaos intentos recientes d'identificación.
Por favor espera enantes d'intentalo otra vuelta.",
'login-abort-generic' => 'Falló la to identificación - Encaboxao',
'loginlanguagelabel' => 'Llingua: $1',
'suspicious-userlogout' => "Negósete'l pidimientu de desconexón porque paez que vien d'un restolador frañíu o d'un proxy de caché.",

# E-mail sending
'php-mail-error-unknown' => 'Fallu desconocíu na función mail() de PHP.',
'user-mail-no-addy' => 'Intentasti unviar un corréu electrónicu ensin direición.',

# Change password dialog
'resetpass' => 'Camudar la clave',
'resetpass_announce' => "Identificástite con una clave provisional unviada per corréu electrónicu.
P'acabar d'identificate, has conseñar equí una clave nueva:",
'resetpass_text' => '<!-- Amestar testu equí -->',
'resetpass_header' => 'Camudar la clave de la cuenta',
'oldpassword' => 'Clave antigua:',
'newpassword' => 'Clave nueva:',
'retypenew' => 'Escribi otra vuelta la clave nueva:',
'resetpass_submit' => 'Conseñar una clave y identificase',
'resetpass_success' => '¡Camudóse correutamente la to clave!
Pasando a identificate...',
'resetpass_forbidden' => 'Les claves nun se puen camudar',
'resetpass-no-info' => "Has tar identificáu p'acceder direutamente a esta páxina.",
'resetpass-submit-loggedin' => 'Camudar la clave',
'resetpass-submit-cancel' => 'Encaboxar',
'resetpass-wrong-oldpass' => 'Clave provisional o actual non válida.
Seique yá camudaras correutamente la clave o que pidieras una nueva clave provisional.',
'resetpass-temp-password' => 'Clave provisional:',

# Special:PasswordReset
'passwordreset' => 'Reaniciar clave',
'passwordreset-text' => 'Completa esti formulariu pa recibir un corréu electrónicu que te recuerde los detalles de la to cuenta.',
'passwordreset-legend' => 'Reaniciar clave',
'passwordreset-disabled' => 'Los reanicios de clave tán desactivaos nesta wiki.',
'passwordreset-pretext' => "{{PLURAL:$1||Escribi ún de los elementos de los datos d'abaxo}}",
'passwordreset-username' => "Nome d'usuariu:",
'passwordreset-domain' => 'Dominiu:',
'passwordreset-capture' => '¿Ver el corréu electrónicu resultante?',
'passwordreset-capture-help' => "Si marques esta caxella, podrás ver el corréu (cola clave provisional) amás d'unvialu al usuariu.",
'passwordreset-email' => 'Direición de corréu electrónicu:',
'passwordreset-emailtitle' => 'Detalles de la cuenta en {{SITENAME}}',
'passwordreset-emailtext-ip' => "Daquién (seique tu, dende la direición IP $1)solicitó un recordatoriu de los
detalles de la to cuenta de {{SITENAME}} ($4).
{{PLURAL:$3|La cuenta d'usuariu siguiente ta asociada|Les cuentes d'usuariu siguientes tán asociaes}}
a esta direición de corréu electrónicu:

$2

{{PLURAL:$3|Esta clave provisional caduca|Estes claves provisionales caduquen}} {{PLURAL:$5|nún día|en $5 díes}}.
Habríes identificate y escoyer una clave nueva agora. Si esta solicitú la fizo otra persona,
o si recordasti la clave orixinal y yá nun quies camudala, pues escaecer esti mensaxe y siguir
usando la to clave antigua.",
'passwordreset-emailtext-user' => "L'usuariu $1 de {{SITENAME}} solicitó un recordatoriu de los detalles de la to cuenta de {{SITENAME}} ($4).
{{PLURAL:$3|La cuenta d'usuariu siguiente ta asociada|Les cuentes d'usuariu siguientes tán asociaes}}
a esta direición de corréu electrónicu:

$2

{{PLURAL:$3|Esta clave provisional caduca|Estes claves provisionales caduquen}} {{PLURAL:$5|nun día|en $5 díes}}.
Habríes identificate y escoyer una clave nueva agora. Si esta solicitú la fizo otra persona, o si
recordasti la clave orixinal y yá nun quies camudala, pues escaecer esti mensaxe y siguir usando
la to clave antigua.",
'passwordreset-emailelement' => "Nome d'usuariu: $1
Clave provisional: $2",
'passwordreset-emailsent' => 'Unvióse un corréu electrónicu de recordatoriu.',
'passwordreset-emailsent-capture' => "Unvióse un corréu electrónicu de recordatoriu, que s'amuesa abaxo.",
'passwordreset-emailerror-capture' => "Xeneróse un corréu electrónicu de recordatoriu, que s'amuesa abaxo, pero falló'l so unviu al usuariu: $1",

# Special:ChangeEmail
'changeemail' => 'Camudar la direición de corréu electrónicu',
'changeemail-header' => 'Camudar la direición de corréu electrónicu de la cuenta',
'changeemail-text' => 'Rellena esti formulariu pa camudar la to direición de corréu electrónicu. Habrás escribir la to clave pa confirmar esti cambéu.',
'changeemail-no-info' => "Has tar identificáu p'acceder direutamente a esta páxina.",
'changeemail-oldemail' => 'Direición de corréu electrónicu actual:',
'changeemail-newemail' => 'Direición de corréu electrónicu nueva:',
'changeemail-none' => '(nengún)',
'changeemail-submit' => 'Camudar el corréu electrónicu',
'changeemail-cancel' => 'Encaboxar',

# Edit page toolbar
'bold_sample' => 'Testu en negrina',
'bold_tip' => 'Testu en negrina',
'italic_sample' => 'Testu en cursiva',
'italic_tip' => 'Testu en cursiva',
'link_sample' => 'Títulu del enllaz',
'link_tip' => 'Enllaz internu',
'extlink_sample' => 'http://www.example.com títulu del enllaz',
'extlink_tip' => 'Enllaz esternu (alcuérdate del prefixu http://)',
'headline_sample' => 'Testu de cabecera',
'headline_tip' => 'Cabecera de nivel 2',
'nowiki_sample' => 'Pon equí testu ensin formatu',
'nowiki_tip' => 'Inorar el formatu wiki',
'image_sample' => 'Exemplu.jpg',
'image_tip' => 'Ficheru incrustáu',
'media_sample' => 'Exemplu.ogg',
'media_tip' => 'Enllaz del ficheru',
'sig_tip' => 'La to robla con data y hora',
'hr_tip' => 'Llinia horizontal (úsala con moderación)',

# Edit pages
'summary' => 'Resume:',
'subject' => 'Asuntu/cabecera:',
'minoredit' => 'Esta ye una edición menor',
'watchthis' => 'Vixilar esta páxina',
'savearticle' => 'Guardar la páxina',
'preview' => 'Previsualización',
'showpreview' => 'Amosar previsualización',
'showlivepreview' => 'Vista rápida',
'showdiff' => 'Amosar cambeos',
'anoneditwarning' => "'''Avisu:''' Nun tas identificáu.
La to direición IP va quedar grabada nel historial d'edición d'esta páxina.",
'anonpreviewwarning' => "''Nun tas identificáu. Al guardar va queda rexistrada la to direición IP nel historial d'edición d'esta páxina.''",
'missingsummary' => "'''Recordatoriu:''' Nun conseñasti un resume d'edición.
Si vuelves calcar en \"{{int:savearticle}}\", la to edición va guardase ensin nengún resume.",
'missingcommenttext' => 'Por favor, escribi un comentariu embaxo.',
'missingcommentheader' => "'''Recordatoriu:''' Nun-y conseñasti asuntu/cabecera a esti comentariu.
Si vuelves calcar en \"{{int:savearticle}}\", la to edición va guardase ensin nengún comentariu.",
'summary-preview' => 'Previsualización del resume:',
'subject-preview' => 'Previsualización del asuntu/cabecera:',
'blockedtitle' => "L'usuariu ta bloquiáu",
'blockedtext' => "'''Bloquióse'l to nome d'usuariu o la to direición IP.'''

El bloquéu féxolu $1.
El motivu conseñáu ye ''$2''.

* Entamu del bloquéu: $8
* Caducidá del bloquéu: $6
* Usuariu a bloquiar: $7

Pues ponete en contautu con $1 o con cualesquier otru [[{{MediaWiki:Grouppage-sysop}}|alministrador]] p'aldericar sobre'l bloquéu.
Nun pues usar la funcionalidá 'manda-y un corréu electrónicu a esti usuariu' a nun ser que tea especificada una direición de
corréu electrónicu válida na to [[Special:Preferences|páxina de preferencies]] y que nun te tengan bloquiao'l so usu.
La to direición IP actual ye $3, y el númberu d'identificación del bloquéu ye $5.
Por favor, amiesta tolos detalles nes consultes que faigas.",
'autoblockedtext' => 'Bloquióse automáticamente la to direición IP porque la usó otru usuariu que foi bloquiáu por $1.
El motivu conseñáu ye:

:\'\'$2\'\'

* Entamu del bloquéu: $8
* Caducidá del bloquéu: $6
* Usuariu a bloquiar: $7

Pues ponete en contautu con $1 o con cualesquier otru [[{{MediaWiki:Grouppage-sysop}}|alministrador]] p\'aldericar sobre\'l bloquéu.

Fíxate en que nun pues usar la funcionalidá "manda-y un corréu electrónicu a esti usuariu" a nun se que tea rexistrada una direición de corréu electrónicu válida na to [[Special:Preferences|páxina de preferencies]] y que nun te tengan bloquiao\'l so usu.

La to direición IP actual ye $3, y el númberu d\'identificación del bloquéu ye $5.
Por favor, amiesta tolos detalles nes consultes que faigas.

La to direición IP actual ye $3, y el númberu d\'identificación del bloquéu ye $5.
Por favor, amiesta tolos detalles nes consultes que faigas.',
'blockednoreason' => 'nun se dio nengún motivu',
'whitelistedittext' => 'Has $1 pa editar páxines.',
'confirmedittext' => "Has confirmar la to direición de corréu electrónicu enantes d'editar páxines.
Por favor, defínila y valídala nes tos [[Special:Preferences|preferencies d'usuariu]].",
'nosuchsectiontitle' => 'Nun se pue atopar la seición',
'nosuchsectiontext' => 'Intentasti editar una seición que nun esiste.
Seique se treslladara o desaniciara mientres víes la páxina.',
'loginreqtitle' => 'Necesítase identificación',
'loginreqlink' => 'identificate',
'loginreqpagetext' => 'Has $1 pa ver otres páxines.',
'accmailtitle' => 'Clave unviada.',
'accmailtext' => "Unvióse a $2 una clave xenerada al debalu pal usuariu [[User talk:$1|$1]].

La clave d'esta cuenta nueva pue camudase na páxina ''[[Special:ChangePassword|camudar clave]]'' depués d'identificate.",
'newarticle' => '(Nuevu)',
'newarticletext' => "Siguisti un enllaz a un artículu qu'inda nun esiste.
Pa crear la páxina, empecipia a escribir nel cuadru d'embaxo (mira la [[{{MediaWiki:Helppage}}|páxina d'ayuda]] pa más información).
Si llegasti equí por enquivocu, calca nel botón '''atrás''' del to restolador.",
'anontalkpagetext' => "----''Esta ye la páxina de'alderique pa un usuariu anónimu qu'inda nun creó una cuenta o que nun la usa.
Pola mor d'ello ha usase la direición numbérica IP pa identificalu/la.
Tala IP pue ser compartida por varios usuarios.
Si yes un usuariu anónimu y notes qu'hai comentarios irrelevantes empobinaos pa ti, por favor [[Special:UserLogin/signup|crea una cuenta]] o [[Special:UserLogin/signup|identifícate]] pa torgar futures confusiones con otros usuarios anónimos.''",
'noarticletext' => 'Nestos momentos nun hai testu nesta páxina.
Pues [[Special:Search/{{PAGENAME}}|guetar esti títulu de páxina]] n\'otres páxines,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} guetar los rexistros rellacionaos],
o [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar esta páxina]</span>.',
'noarticletext-nopermission' => 'Nestos momentos nun hai testu nesta páxina.
Pues [[Special:Search/{{PAGENAME}}|guetar esti títulu de páxina]] n\'otres páxines o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} guetar los rexistros rellacionaos]</span>, pero nun tienes permisu pa crear esta páxina.',
'missing-revision' => 'La revisión #$1 de la páxina llamada "{{PAGENAME}}" nun esiste.

De vezu la causa d\'esto ye siguir un enllaz antiguu del historial a una páxina que se desanició.
Se puen alcontrar más detalles nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].',
'userpage-userdoesnotexist' => "La cuenta d'usuariu «$1» nun ta rexistrada.
Por favor comprueba si quies crear/editar esta páxina.",
'userpage-userdoesnotexist-view' => "La cuenta d'usuariu «$1» nun ta rexistrada.",
'blocked-notice-logextract' => 'Nestos momentos esti usuariu ta bloquiáu.
La postrer entrada del rexistru de bloqueos úfrese darréu pa referencia:',
'clearyourcache' => "'''Nota:''' Llueu de guardar, seique tengas que llimpiar la caché del restolador pa ver los cambeos.
*'''Firefox / Safari:''' Caltén ''Mayús'' mentes calques en ''Recargar'', o calca ''Ctrl-F5'' o ''Ctrl-R'' (''⌘-R'' nun Mac)
* '''Google Chrome:''' Calca ''Ctrl-Mayús-R'' (''⌘-Mayús-R'' nun Mac)
* '''Internet Explorer:''' Caltén ''Ctrl'' mentes calques ''Refrescar'', o calca ''Ctrl-F5''
* '''Opera:''' llimpia la caché en ''Ferramientes → Preferencies''",
'usercssyoucanpreview' => "'''Conseyu:''' Usa'l botón \"{{int:showpreview}}\" pa probar el to CSS nuevu enantes de guardalu.",
'userjsyoucanpreview' => "'''Conseyu:''' Usa'l botón \"{{int:showpreview}}\" pa probar el to JavaScript nuevu enantes de guardalu.",
'usercsspreview' => "'''Recuerda que namái tas previsualizando'l to CSS d'usuariu.'''
'''¡Tovía nun ta guardáu!'''",
'userjspreview' => "'''¡Recuerda que namái tas probando/previsualizando'l to JavaScript d'usuariu.'''
'''¡Tovía nun ta guardáu!'''",
'sitecsspreview' => "'''Recuerda que namái tas previsualizando esti CSS.'''
'''¡Tovía nun ta guardáu!'''",
'sitejspreview' => "'''¡Recuerda que namái tas probando esti códigu JavaScript.'''
'''¡Tovía nun tá guardáu!'''",
'userinvalidcssjstitle' => "'''Avisu:''' Nun hai apariencia «$1».
Les páxines personalizaes .css y .js usen un títulu en minúscules, p. ex. {{ns:user}}:Foo/vector.css en cuenta de {{ns:user}}:Foo/Vector.css.",
'updated' => '(Actualizao)',
'note' => "'''Nota:'''",
'previewnote' => "'''Alcuerdate de qu'esto ye sólo una vista previa.'''
¡Los cambios entá nun se guardaron!",
'continue-editing' => "Dir al área d'edición",
'previewconflict' => "Esta vista previa amuesa'l testu del área d'edición d'arriba tal como apaecerá si escueyes guardar.",
'session_fail_preview' => "'''¡Sentímoslo muncho! Nun se pudo procesar la to edición porque hebo una perda de datos de la sesión.
Inténtalo otra vuelta. Si nun se t'arregla, intenta salir y volver a rexistrate.'''",
'session_fail_preview_html' => "'''¡Sentímoslo! Nun se pudo procesar la to edición pola mor d'una perda de datos de sesión.'''

''Como {{SITENAME}} tien activáu'l HTML puru, la previsualización nun s'amosará como precaución escontra ataques en JavaScript.''

'''Si esti ye un intentu llexítimu d'edición, por favor inténtalo otra vuelta. Si tovía asina nun furrula, intenta [[Special:UserLogout|desconeutate]] y volver a identificate.'''",
'token_suffix_mismatch' => "'''La to edición nun s'aceutó porque'l to navegador mutiló los caráuteres de puntuación nel editor.'''
La edición nun foi aceutada pa prevenir corrupciones na páxina de testu.
Dacuando esto pasa por usar un serviciu proxy anónimu basáu en web que tenga fallos.",
'edit_form_incomplete' => "'''Delles partes del formulariu d'edición nun llegaron al sirvidor; comprueba que les ediciones tean intactes y vuelve a tentalo.'''",
'editing' => 'Editando $1',
'creating' => 'Creando $1',
'editingsection' => 'Editando $1 (seición)',
'editingcomment' => 'Editando $1 (seición nueva)',
'editconflict' => "Conflictu d'edición: $1",
'explainconflict' => "Daquién más camudó esta páxina dende qu'empecipiasti a editala.
L'área de testu d'arriba contién el testu de la páxina como ta nestos momentos.
Los tos cambios s'amuesen nel área de testu d'abaxo.
Vas tener que fusionar los tos cambios dientro del testu esistente.
'''Namái''' va guardase'l testu del área d'arriba cuando calques \"{{int:savearticle}}\".",
'yourtext' => 'El to testu',
'storedversion' => 'Versión almacenada',
'nonunicodebrowser' => "'''AVISU: El to navegador nun cumple la norma unicode. Hai un sistema alternativu que te permite editar páxines de forma segura: los carauteres non-ASCII apaecerán na caxa d'edición como códigos hexadecimales.'''",
'editingold' => "'''AVISU: Tas editando una revisión vieya d'esta páxina. Si la grabes, los cambios que se ficieron dende esta revisión van perdese.'''",
'yourdiff' => 'Diferencies',
'copyrightwarning' => "Por favor, ten en cuenta que toles contribuciones de {{SITENAME}} se consideren espublizaes baxo la $2 (ver $1 pa más detalles). Si nun quies que'l to trabayu s'edite ensin midida y se distribuya al debalu, nun lu pongas equí.<br />
Amás tas dexándonos afitao qu'escribisti esto tu mesmu o que lo copiasti d'una fonte llibre de dominiu públicu o asemeyao.
'''¡NUN PONGAS TRABAYOS CON DERECHOS D'AUTOR ENSIN PERMISU!'''",
'copyrightwarning2' => "Por favor, ten en cuenta que toles contribuciones de {{SITENAME}} se puen editar, alterar o desaniciar por otros usuarios. Si nun quies que'l to trabayu s'edite ensin midida, nun lu pongas equí.<br />
Amás tas dexándonos afitao qu'escribisti esto tu mesmu, o que lo copiasti d'una fonte llibre de dominiu públicu o asemeyao (ver $1 pa más detalles).
'''¡Nun pongas trabayos con drechos d'autor ensin permisu!'''",
'longpageerror' => "'''ERROR: El testu qu'unviasti tien {{PLURAL:$1|un quilobyte|$1 quilobytes}}, que pasa del máximu de {{PLURAL:$2|un quilobyte|$2 quilobytes}}.'''
Nun se pue grabar.",
'readonlywarning' => "'''Avisu: La base de datos ta candada por mantenimientu, polo que nun vas poder guardar les tos ediciones nestos momentos.'''
Seique habríes copiar y apegar el testu nun ficheru de testu y guardalu pa intentalo más sero.

L'alministrador que la candó dio esta esplicación: $1",
'protectedpagewarning' => "'''Avisu: Esta páxina ta candada pa que sólo los alministradores puean editala.'''
La cabera entrada del rexistru s'ufre darréu pa referencia:",
'semiprotectedpagewarning' => "'''Nota:''' Esta páxina ta candada pa que nun puean editala namái que los usuarios rexistraos.
La cabera entrada del rexistru s'ufre darréu pa referencia:",
'cascadeprotectedwarning' => "'''Avisu:''' Esta páxina ta candada pa que namái los alministradores la puean editar porque ta enxerta {{PLURAL:$1|na siguiente páxina protexida|nes siguientes páxines protexíes}} en cascada:",
'titleprotectedwarning' => "'''Avisu: Esta páxina ta candada pa que necesite [[Special:ListGroupRights|permisos especiales]] pa creala.'''
La cabera entrada del rexistru s'ufre darréu pa referencia:",
'templatesused' => '{{PLURAL:$1|Plantía usada|Plantíes usaes}} nesta páxina:',
'templatesusedpreview' => '{{PLURAL:$1|Plantía usada|Plantíes usaes}} nesta vista previa:',
'templatesusedsection' => '{{PLURAL:$1|Plantía usada|Plantíes usaes}} nesta seición:',
'template-protected' => '(protexía)',
'template-semiprotected' => '(semi-protexida)',
'hiddencategories' => 'Esta páxina pertenez a {{PLURAL:$1|una categoría anubrida|$1 categoríes anubríes}}:',
'edittools' => "<!-- Esti testu apaecerá baxo los formularios d'edición y xuba. -->",
'nocreatetitle' => 'Creación de páxines limitada',
'nocreatetext' => '{{SITENAME}} tien restrinxida la capacidá de crear páxines nueves.
Pues volver atrás y editar una páxina esistente, o bien [[Special:UserLogin|identificate o crear una cuenta]].',
'nocreate-loggedin' => 'Nun tienes permisu pa crear páxines nueves.',
'sectioneditnotsupported-title' => 'Nun hai sofitu pa editar seición',
'sectioneditnotsupported-text' => 'La edición de seición nun tien sofitu nesta páxina.',
'permissionserrors' => 'Errores de Permisos',
'permissionserrorstext' => 'Nun tienes permisu pa facer eso {{PLURAL:$1|pol siguiente motivu|polos siguientes motivos}}:',
'permissionserrorstext-withaction' => 'Nun tienes permisu pa $2 {{PLURAL:$1|pol siguiente motivu|polos siguientes motivos}}:',
'recreate-moveddeleted-warn' => "'''Avisu: Tas volviendo a crear una páxina que se desanició anteriormente.'''

Habríes considerar si ye afechisco siguir editando esta páxina.
Equí tienes el rexistru de desanicios y tresllaos d'esta páxina:",
'moveddeleted-notice' => "Esta páxina se desanició.
Como referencia, embaxo s'ufre'l rexistru de desanicios y tresllaos de la páxina.",
'log-fulllog' => 'Ver el rexistru ensembre',
'edit-hook-aborted' => 'Edición albortada pol hook.
Nun conseñó esplicación.',
'edit-gone-missing' => 'Nun se pudo actualizar la páxina.
Paez que se desanició.',
'edit-conflict' => "Conflictu d'edición.",
'edit-no-change' => "S'inoró la to edición, porque nun se fizo nengún cambéu nel testu.",
'edit-already-exists' => 'Nun se pudo crear una páxina nueva.
Yá esiste.',
'defaultmessagetext' => 'Testu predetermináu',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Avisu:''' Esta páxina contién demasiaes llamaes costoses a funciones d'análisis sintáuticu.

Habría tener menos de $2 {{PLURAL:$2|llamada|llamaes}}, y agora tien $1 {{PLURAL:$1|llamada|llamaes}}.",
'expensive-parserfunction-category' => "Páxines con demasiaes llamaes costoses a funciones d'análisis sintáuticu",
'post-expand-template-inclusion-warning' => "'''Avisu:''' El tamañu de les plantíes incluyíes ye demasiao grande.
Delles plantíes nun se van incluir.",
'post-expand-template-inclusion-category' => "Páxines con escesu d'inclusiones de plantíes",
'post-expand-template-argument-warning' => "'''Avisu:''' Esta páxina contién polo menos un parámetru de plantía que tien un tamañu d'espansión demasiao llargu.
Estos parámetros s'omitieron.",
'post-expand-template-argument-category' => 'Páxines con parámetros de plantía omitíos',
'parser-template-loop-warning' => 'Hai una rueda de plantíes: [[$1]]',
'parser-template-recursion-depth-warning' => 'Se pasó la llende de fondura recursiva de les plantíes ($1)',
'language-converter-depth-warning' => 'Se pasó la llende de fondura del convertidor de llingües ($1)',
'node-count-exceeded-category' => 'Páxines onde se pasó la cuenta de nodios',
'node-count-exceeded-warning' => 'La páxina pasó la cuenta de nodios',
'expansion-depth-exceeded-category' => "Páxines onde se pasó la fondura d'espansión",
'expansion-depth-exceeded-warning' => "La páxina pasó la fondura d'espansión",
'parser-unstrip-loop-warning' => 'Deteutóse un bucle "unstrip"',
'parser-unstrip-recursion-limit' => 'Superóse\'l llímite de recursión d\'"unstrip" ($1)',
'converter-manual-rule-error' => 'Detectóse un error na regla de conversión manual de llingua',

# "Undo" feature
'undo-success' => "La edición se pue esfacer.
Por favor comprueba la comparanza d'abaxo pa confirmar que ye eso lo que quies facer, y depués guarda los cambios p'acabar d'esfacer la edición.",
'undo-failure' => "Nun se pudo esfacer la edición pola mor d'ediciones intermedies conflictives.",
'undo-norev' => 'Nun se pudo esfacer la edición porque nun esiste o se desanició.',
'undo-summary' => 'Esfacer la revisión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|alderique]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nun se pue crear la cuenta',
'cantcreateaccount-text' => "La creación de cuentes dende esta direición IP ('''$1''') foi bloquiada por [[User:$3|$3]].

El motivu dau por $3 ye ''$2''",

# History pages
'viewpagelogs' => "Ver los rexistros d'esta páxina",
'nohistory' => "Nun hay historial d'ediciones pa esta páxina.",
'currentrev' => 'Revisión actual',
'currentrev-asof' => 'Revisión actual a fecha de $1',
'revisionasof' => 'Revisión a fecha de $1',
'revision-info' => 'Revisión a fecha de $1; $2',
'previousrevision' => '←Revisión anterior',
'nextrevision' => 'Revisión siguiente→',
'currentrevisionlink' => 'Revisión actual',
'cur' => 'act',
'next' => 'próximu',
'last' => 'cab',
'page_first' => 'primera',
'page_last' => 'cabera',
'histlegend' => "Seleición de diferencies: marca los botones de les versiones que quies comparar y calca <i>enter</i> o al botón d'abaxo.<br />
Lleenda: '''({{int:cur}})''' = diferencies cola versión actual, '''({{int:last}})''' = diferencies cola versión anterior, '''{{int:minoreditletter}}''' = edición menor.",
'history-fieldset-title' => 'Navegar pel historial',
'history-show-deleted' => 'Sólo desaniciaes',
'histfirst' => 'Primera',
'histlast' => 'Cabera',
'historysize' => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty' => '(balero)',

# Revision feed
'history-feed-title' => 'Historial de revisiones',
'history-feed-description' => "Historial de revisiones d'esta páxina na wiki",
'history-feed-item-nocomment' => '$1 en $2',
'history-feed-empty' => 'La páxina solicitada nun esiste.
Seique fuera desaniciada de la wiki, o renomada.
Prueba a [[Special:Search|buscar na wiki]] otres páxines nueves.',

# Revision deletion
'rev-deleted-comment' => "(resume d'edición desaniciáu)",
'rev-deleted-user' => "(nome d'usuariu desaniciáu)",
'rev-deleted-event' => '(aición del rexistru desaniciada)',
'rev-deleted-user-contribs' => "[nome d'usuariu o direición IP desaniciáu - ediciones anubríes en contribuciones]",
'rev-deleted-text-permission' => "Esta revisión de la páxina se '''desanició'''.
Los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].",
'rev-deleted-text-unhide' => "Esta revisión de la páxina se '''desanició'''.
Los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].
Entá pues [$1 ver esta revisión] si quies siguir.",
'rev-suppressed-text-unhide' => "Esta revisión de la páxina se '''suprimió'''.
Los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/supress|page={{FULLPAGENAMEE}}}} rexistru de supresiones].
Entá pues [$1 ver esta revisión] si quies siguir.",
'rev-deleted-text-view' => "Esta revisión de la páxina se '''desanició'''.
Pues vela; los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].",
'rev-suppressed-text-view' => "Esta revisión de la páxina se '''suprimió'''.
Pues vela; los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/supress|page={{FULLPAGENAMEE}}}} rexistru de supresiones].",
'rev-deleted-no-diff' => "Nun pues ver esti diff porque una de les revisiones se '''desanició'''.
Los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].",
'rev-suppressed-no-diff' => "Nun pues ver esti diff porque una de les revisiones se '''desanició'''.",
'rev-deleted-unhide-diff' => "Una de les revisiones d'esti diff se '''desanició'''.
Los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].
Entá pues [$1 ver esti diff] si quies siguir.",
'rev-suppressed-unhide-diff' => "Una de les revisiones d'esti diff se '''suprimió'''.
Los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/supress|page={{FULLPAGENAMEE}}}} rexistru de supresiones].
Entá pues [$1 ver esti diff] si quies siguir.",
'rev-deleted-diff-view' => "Una de les revisiones d'esti diff se '''desanició'''.
Pues ver esti diff; los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].",
'rev-suppressed-diff-view' => "Una de les revisiones d'esti diff se '''suprimió'''.
Pues ver el diff; los detalles s'alcuentren nel [{{fullurl:{{#Special:Log}}/supress|page={{FULLPAGENAMEE}}}} rexistru de supresiones].",
'rev-delundel' => 'amosar/anubrir',
'rev-showdeleted' => 'amosar',
'revisiondelete' => 'Desaniciar/restaurar revisiones',
'revdelete-nooldid-title' => 'Revisión de destín non válida',
'revdelete-nooldid-text' => 'Nun especificasti una revisión o revisiones destín sobre les que realizar esta función, la revisión especificada nun esiste, o tas intentando anubrir la revisión actual.',
'revdelete-nologtype-title' => 'Nun se dio la triba de rexistru',
'revdelete-nologtype-text' => 'Nun conseñasti una triba de rexistru nel que facer esta aición.',
'revdelete-nologid-title' => 'Entrada del rexistru inválida',
'revdelete-nologid-text' => 'O nun conseñasti un socesu específicu del rexistru pa facer esta función o la entrada conseñada nun esiste.',
'revdelete-no-file' => 'El ficheru conseñáu nun esiste.',
'revdelete-show-file-confirm' => '¿Tas seguru de que quies ver una versión desaniciada del ficheru "<nowiki>$1</nowiki>" del $2 a les $3?',
'revdelete-show-file-submit' => 'Sí',
'revdelete-selected' => "'''{{PLURAL:$2|Revisión seleicionada|Revisiones seleicionaes}} de [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Seleicionáu un eventu de rexistru|Seleicionaos eventos de rexistru}}:'''",
'revdelete-text' => "'''Les revisiones y socesos desaniciaos van siguir apaeciendo nel historial de la páxina y nos rexistros, pero parte del so conteníu nun va ser accesible pal públicu.'''
Otros alministradores de {{SITENAME}} van siguir pudiendo acceder al conteníu anubríu y puen restauralu de nuevo al traviés d'esta mesma interfaz, a nun ser que s'establezan otres restricciones.",
'revdelete-confirm' => "Confirma que quies facer esto, qu'entiendes les consecuencies, y que vas facer esto d'alcuerdo [[{{MediaWiki:Policy-url}}|cola política]].",
'revdelete-suppress-text' => "La supresión '''namái''' tendría d'usase nos casos darréu:
* Información que pudiere ser bilordiosa
* Información personal non apropiada
*: ''direiciones de llares y númberos de teléfonu, númberos de seguridá social, etc.''",
'revdelete-legend' => 'Establecer restricciones de visibilidá',
'revdelete-hide-text' => 'Esconder testu de revisión',
'revdelete-hide-image' => 'Esconder el conteníu del archivu',
'revdelete-hide-name' => 'Esconder aición y oxetivu',
'revdelete-hide-comment' => "Esconder comentariu d'edición",
'revdelete-hide-user' => "Esconder el nome d'usuariu/IP del editor",
'revdelete-hide-restricted' => "Desaniciar datos de los alministradores y d'otros",
'revdelete-radio-same' => '(ensin cambeos)',
'revdelete-radio-set' => 'Sí',
'revdelete-radio-unset' => 'Non',
'revdelete-suppress' => 'Eliminar datos de los alministradores lo mesmo que los de los demás',
'revdelete-unsuppress' => 'Eliminar restricciones de revisiones restauraes',
'revdelete-log' => 'Motivu:',
'revdelete-submit' => 'Aplicar a {{PLURAL:$1|la revisión seleicionada|les revisiones seleicionaes}}',
'revdelete-success' => "'''Visibilidá de revisiones anovada correutamente.'''",
'revdelete-failure' => "'''La visibilida de revisiones nun se pudo anovar:'''
$1",
'logdelete-success' => "'''Visibilidá d'eventos establecida correutamente.'''",
'logdelete-failure' => "'''Nun se pudo configurar la visibilidá del rexistru:'''
$1",
'revdel-restore' => 'camudar visibilidá',
'revdel-restore-deleted' => 'revisiones desaniciaes',
'revdel-restore-visible' => 'revisiones visibles',
'pagehist' => 'Historial de la páxina',
'deletedhist' => 'Historial elimináu',
'revdelete-hide-current' => "Error al anubrir l'elementu con data $1, $2: esta ye la revisión actual.
Nun se pue anubrir.",
'revdelete-show-no-access' => 'Error al amosar l\'elementu con data $2, $1: esti elementu se marcó como "llendáu".
Nun tienes accesu al mesmu.',
'revdelete-modify-no-access' => 'Error al camudar l\'elementu con data $2, $1: esti elementu se marcó como "llendáu".
Nun tienes accesu al mesmu.',
'revdelete-modify-missing' => "Error al camudar l'elementu con ID $1: ¡falta na base de datos!",
'revdelete-no-change' => "'''Avisu:''' l'elementu con data $2, $1 yá tien los axustes de visibilidá pidíos.",
'revdelete-concurrent-change' => "Error al camudar l'elementu con data $2, $1: paez que'l so estáu camudólu otra persona mientres tentabes camudalu tu.
Comprueba los rexistros, por favor.",
'revdelete-only-restricted' => "Fallu al anubrir l'elementu con data $1, $2: nun se puen quitar elementos de la vista de los alministradores ensin escoyer tamién una de les otres opciones de visibilidá.",
'revdelete-reason-dropdown' => "*Razones comúnes de desaniciu 
** Violación del Copyright
** Información personal o comentariu non apropiáu
** Nome d'usuariu non apropiáu
** Información potencialmente bilordiosa",
'revdelete-otherreason' => 'Motivu distintu/adicional:',
'revdelete-reasonotherlist' => 'Otru motivu',
'revdelete-edit-reasonlist' => 'Editar motivos del desaniciu',
'revdelete-offender' => 'Autor de la revisión:',

# Suppression log
'suppressionlog' => 'Rexistru de supresiones',
'suppressionlogtext' => 'Más abaxo hai una llista de desanicios y bloqueos rellacionaos con conteníu anubríu a los alministradores.
Mira la [[Special:BlockList|llista de bloqueos]] pa ver la llista de los bloqueos y torgues activos anguaño.',

# History merging
'mergehistory' => 'Fusionar historiales de páxina',
'mergehistory-header' => "Esta páxina permítete fusionar revisiones del historial d'una páxina orixe nuna páxina nueva.
Asegúrate de qu'esti cambéu caltenga la continuidá del históricu de la páxina.",
'mergehistory-box' => 'Fusionar les revisiones de dos páxines:',
'mergehistory-from' => "Páxina d'orixe:",
'mergehistory-into' => 'Páxina de destín:',
'mergehistory-list' => "Historial d'ediciones fusionable",
'mergehistory-merge' => "Les siguientes revisiones de [[:$1]] puen fusionase en [[:$2]]. Usa la columna de botones d'opción pa fusionar namaí les revisiones creaes na y enantes de la hora especificada. has fixate en que si uses los enllaces de navegación esborraránse les seleiciones feches nesta columna.",
'mergehistory-go' => 'Amosar ediciones fusionables',
'mergehistory-submit' => 'Fusionar revisiones',
'mergehistory-empty' => 'Nun se pue fusionar nenguna revisión.',
'mergehistory-success' => '$3 {{PLURAL:$3|revisión|revisiones}} de [[:$1]] fusionaes correutamente en [[:$2]].',
'mergehistory-fail' => "Nun se pudo facer la fusión d'historiales, por favor verifica la páxina y los parámetros temporales.",
'mergehistory-no-source' => "La páxina d'orixe $1 nun esiste.",
'mergehistory-no-destination' => 'La páxina de destín $1 nun esiste.',
'mergehistory-invalid-source' => "La páxina d'orixe ha tener un títulu válidu.",
'mergehistory-invalid-destination' => 'La páxina de destín ha tener un títulu válidu.',
'mergehistory-autocomment' => '[[:$1]] fusionada con [[:$2]]',
'mergehistory-comment' => '[[:$1]] fusionada con [[:$2]]: $3',
'mergehistory-same-destination' => "Les páxines d'orixe y destín nun puen ser la mesma",
'mergehistory-reason' => 'Motivu:',

# Merge log
'mergelog' => 'Rexistru de fusiones',
'pagemerge-logentry' => '[[$1]] foi fusionada en [[$2]] (hasta la revisión $3)',
'revertmerge' => 'Dixebrar',
'mergelogpagetext' => "Abaxo amuésase una llista de les fusiones más recientes d'un historial de páxina con otru.",

# Diffs
'history-title' => 'Historial de revisiones de «$1»',
'difference-title' => 'Diferencies ente revisiones de «$1»',
'difference-title-multipage' => 'Diferencies ente les páxines «$1» y «$2»',
'difference-multipage' => '(Diferencia ente páxines)',
'lineno' => 'Llinia $1:',
'compareselectedversions' => 'Comparar les revisiones seleicionaes',
'showhideselectedversions' => 'Amosar/anubrir les versiones seleicionaes',
'editundo' => 'desfacer',
'diff-multi' => "({{PLURAL:$1|Nun s'amuesa 1 revisión intermedia|Nun s'amuesen $1 revisiones intermedies}} {{PLURAL:$2|d'un usuariu|de $2 usuarios}} )",
'diff-multi-manyusers' => "({{PLURAL:$1|Nun s'amuesa una revisión intermedia|Nun s'amuesen $1 revisiones intermedies}} de más de $2 {{PLURAL:$2|usuariu|usuarios}})",
'difference-missing-revision' => "{{PLURAL:$2|Nun s'alcontró|Nun s'alcontraron}} {{PLURAL:$2|una revisión|$2 revisiones}} d'esta diferencia ($1).

De vezu la causa d'esto ye siguir un enllaz de diferencia antiguu a una páxina que se desanició.
Se puen alcontrar más detalles nel [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistru de desanicios].",

# Search results
'searchresults' => 'Resultaos de la gueta',
'searchresults-title' => 'Resultaos de guetar "$1"',
'searchresulttext' => 'Pa más información tocante a busques en {{SITENAME}}, vete a [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Buscasti \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|toles páxines qu\'emprimen con "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toles páxines qu\'enllacien a "$1"]])',
'searchsubtitleinvalid' => "Buscasti '''$1'''",
'toomanymatches' => 'Atopáronse demasiaes coincidencies, por favor fai una consulta diferente',
'titlematches' => 'Coincidencies de los títulos de la páxina',
'notitlematches' => 'Nun hai coincidencies nel títulu de la páxina',
'textmatches' => 'Coincidencies del testu de la páxina',
'notextmatches' => 'Nun hai coincidencies nel testu de la páxina',
'prevn' => '{{PLURAL:$1|anterior|$1 anteriores}}',
'nextn' => '{{PLURAL:$1|siguiente|$1 siguientes}}',
'prevn-title' => '$1 {{PLURAL:$1|resultáu anterior|resultaos anteriores}}',
'nextn-title' => '{{PLURAL:$1|Siguiente resultáu|Siguientes $1 resultaos}}',
'shown-title' => 'Amosar $1 {{PLURAL:$1|resultáu|resultaos}} por páxina',
'viewprevnext' => 'Ver ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Opciones de busca',
'searchmenu-exists' => "'''Hai una páxina nomada \"[[\$1]]\" nesta wiki'''",
'searchmenu-new' => "'''¡Crear la páxina \"[[:\$1]]\" nesta wiki!'''",
'searchhelp-url' => 'Help:Conteníos',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Restolar páxines con esti prefixu]]',
'searchprofile-articles' => 'Páxines de conteníu',
'searchprofile-project' => 'Páxines de proyeutu y ayuda',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Too',
'searchprofile-advanced' => 'Avanzao',
'searchprofile-articles-tooltip' => 'Buscar en $1',
'searchprofile-project-tooltip' => 'Buscar en $1',
'searchprofile-images-tooltip' => 'Buscar ficheros',
'searchprofile-everything-tooltip' => "Buscar tol conteníu (incluyendo páxines d'alderique)",
'searchprofile-advanced-tooltip' => 'Guetar nos espacios de nomes personalizaos',
'search-result-size' => '$1 ({{PLURAL:$2|1 pallabra|$2 pallabres}})',
'search-result-category-size' => '{{PLURAL:$1|1 miembru|$1 miembros}} ({{PLURAL:$2|1 subcategoría|$2 subcategories}}, {{PLURAL:$3|1 ficheru|$3 ficheros}})',
'search-result-score' => 'Relevancia: $1%',
'search-redirect' => '(redireición de $1)',
'search-section' => '(seición $1)',
'search-suggest' => 'Quixisti dicir: $1',
'search-interwiki-caption' => 'Proyeutos hermanos',
'search-interwiki-default' => '$1 resultaos:',
'search-interwiki-more' => '(más)',
'search-relatedarticle' => 'Rellacionáu',
'mwsuggest-disable' => 'Desactivar les suxerencies AJAX',
'searcheverything-enable' => 'Buscar en tolos espacios de nome',
'searchrelated' => 'rellacionáu',
'searchall' => 'toos',
'showingresults' => "Abaxo {{PLURAL:$1|amuésase '''un''' resultáu|amuésense '''$1''' resultaos}}, entamando col #'''$2'''.",
'showingresultsnum' => "Abaxo {{PLURAL:$3|amuésase '''un''' resultáu|amuésense '''$3''' resultaos}}, entamando col #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Resultáu '''$1''' de '''$3'''|Resultaos '''$1 - $2''' de '''$3'''}} pa '''$4'''",
'nonefound' => "'''Nota''': De mou predetermináu namái se busca en dellos espacios de nomes. Prueba a poner delantre de la to consulta ''all:'' pa buscar en tol conteníu (inxiriendo páxines d'alderique, plantíes, etc.), o usa como prefixu l'espaciu de nome deseáu.",
'search-nonefound' => 'Nun hebo resultaos que casaren cola consulta.',
'powersearch' => 'Gueta avanzada',
'powersearch-legend' => 'Gueta avanzada',
'powersearch-ns' => 'Buscar nos espacios de nome:',
'powersearch-redir' => 'Llistar redireiciones',
'powersearch-field' => 'Buscar',
'powersearch-togglelabel' => 'Comprobar:',
'powersearch-toggleall' => 'Toos',
'powersearch-togglenone' => 'Dengún',
'search-external' => 'Busca esterna',
'searchdisabled' => "La busca en {{SITENAME}} ta desactivada. Mentanto, pues buscar en Google. Has fixate en que'l conteníu de los sos índices de {{SITENAME}} pue tar desfasáu.",

# Quickbar
'qbsettings' => 'Barra rápida',
'qbsettings-none' => 'Nenguna',
'qbsettings-fixedleft' => 'Fixa a manzorga',
'qbsettings-fixedright' => 'Fixa a mandrecha',
'qbsettings-floatingleft' => 'Flotante a manzorga',
'qbsettings-floatingright' => 'Flotante a mandrecha',
'qbsettings-directionality' => "Fixa, según la direición d'escritura de la to llingua",

# Preferences page
'preferences' => 'Preferencies',
'mypreferences' => 'Preferencies',
'prefs-edits' => "Númberu d'ediciones:",
'prefsnologin' => 'Non identificáu',
'prefsnologintext' => 'Necesites tar <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} identificáu]</span> pa camudar les preferencies d\'usuariu.',
'changepassword' => 'Camudar la clave',
'prefs-skin' => 'Apariencia',
'skin-preview' => 'Vista previa',
'datedefault' => 'Ensin preferencia',
'prefs-beta' => 'Carauterístiques beta',
'prefs-datetime' => 'Fecha y hora',
'prefs-labs' => 'Carauterístiques esperimentales',
'prefs-user-pages' => "Páxines d'usuariu",
'prefs-personal' => 'Perfil del usuariu',
'prefs-rc' => 'Cambios recientes',
'prefs-watchlist' => 'Llista de vixilancia',
'prefs-watchlist-days' => "Númberu de díes qu'amosar na llista de vixilancia:",
'prefs-watchlist-days-max' => 'Máximo $1 {{PLURAL:$1|día|díes}}',
'prefs-watchlist-edits' => "Númberu d'ediciones qu'amosar na llista de vixilancia espandida:",
'prefs-watchlist-edits-max' => 'Númberu máximu: 1000',
'prefs-watchlist-token' => 'Marca de la llista de vixilancia:',
'prefs-misc' => 'Varios',
'prefs-resetpass' => 'Camudar la conseña',
'prefs-changeemail' => 'Camudar el corréu electrónicu',
'prefs-setemail' => 'Conseñar una direición de corréu electrónicu',
'prefs-email' => 'Opciones de corréu',
'prefs-rendering' => 'Aspeutu',
'saveprefs' => 'Guardar',
'resetprefs' => 'Llimpiar los cambios ensin guardar',
'restoreprefs' => 'Restaurar tolos axustes predeterminaos',
'prefs-editing' => 'Edición',
'prefs-edit-boxsize' => "Tamañu de la ventana d'edición.",
'rows' => 'Fileres:',
'columns' => 'Columnes:',
'searchresultshead' => 'Guetar',
'resultsperpage' => 'Resultaos por páxina:',
'stub-threshold' => 'Llímite superior pa considerar como <a href="#" class="stub">enllaz a entamu</a> (bytes):',
'stub-threshold-disabled' => 'Desactivao',
'recentchangesdays' => "Díes que s'amuesen nos cambios recientes:",
'recentchangesdays-max' => '(máximo $1 {{PLURAL:$1|día|díes}})',
'recentchangescount' => "Númberu d'ediciones p'amosar de mou predetermináu:",
'prefs-help-recentchangescount' => 'Incluye los cambios recientes, los historiales de páxines y los rexistros.',
'prefs-help-watchlist-token' => "Rellenando esti campu con una clave secreta se xenerará una canal RSS pa la to llista de vixilancia.
Quien sepa la clave d'esti campu podrá lleer la to llista de vixilancia, poro, escueyi un valor seguru.
Equí tienes un valor al debalu que pues usar: $1",
'savedprefs' => 'Les tos preferencies quedaron grabaes.',
'timezonelegend' => 'Estaya horaria:',
'localtime' => 'Hora llocal:',
'timezoneuseserverdefault' => 'Usar la predeterminada de la wiki ($1)',
'timezoneuseoffset' => 'Otru (especificar diferencia)',
'timezoneoffset' => 'Diferencia¹:',
'servertime' => 'Hora del sirvidor:',
'guesstimezone' => 'Obtener del navegador',
'timezoneregion-africa' => 'África',
'timezoneregion-america' => 'América',
'timezoneregion-antarctica' => 'Antártida',
'timezoneregion-arctic' => 'Árticu',
'timezoneregion-asia' => 'Asia',
'timezoneregion-atlantic' => 'Océanu Atlánticu',
'timezoneregion-australia' => 'Australia',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Océanu Índicu',
'timezoneregion-pacific' => 'Océanu Pacíficu',
'allowemail' => 'Dexar a los otros usuarios mandate correos',
'prefs-searchoptions' => 'Guetar',
'prefs-namespaces' => 'Espacios de nome',
'defaultns' => "D'otra miente, guetar nestos espacios de nome:",
'default' => 'predetermináu',
'prefs-files' => 'Ficheros',
'prefs-custom-css' => 'CSS personalizada',
'prefs-custom-js' => 'JS personalizada',
'prefs-common-css-js' => 'CSS/JavaScript compartíu pa toles pieles:',
'prefs-reset-intro' => 'Pues usar esta páxina pa reaniciar les preferencies a los valores predeterminaos del sitiu.
Esto nun se pue desfacer.',
'prefs-emailconfirm-label' => 'Confirmación del corréu:',
'prefs-textboxsize' => "Tamañu de la ventana d'edición",
'youremail' => 'Corréu electrónicu:',
'username' => "Nome d'usuariu:",
'uid' => "Númberu d'usuariu:",
'prefs-memberingroups' => 'Miembru {{PLURAL:$1|del grupu|de los grupos}}:',
'prefs-registration' => 'Hora del rexistru:',
'yourrealname' => 'Nome real:',
'yourlanguage' => 'Llingua:',
'yourvariant' => 'Variante llingüística del conteníu:',
'prefs-help-variant' => "La ortografía o variante que prefieres p'amosar el conteníu de les páxines d'esta wiki.",
'yournick' => 'Firma:',
'prefs-help-signature' => 'Los comentarios nes páxines d\'alderique habría que roblales con "<nowiki>~~~~</nowiki>" que se convertirán na to robla y una marca de tiempu.',
'badsig' => 'Firma cruda non válida; comprueba les etiquetes HTML.',
'badsiglength' => 'La to robla ye demasiao llarga.
Ha tener menos de $1 {{PLURAL:$1|caráuter|carauteres}}.',
'yourgender' => 'Xéneru:',
'gender-unknown' => 'Non especificáu',
'gender-male' => 'Masculín',
'gender-female' => 'Femenín',
'prefs-help-gender' => "Opcional: s'usa pol software pa crear diálogos col xéneru correchu.
Esta información sedrá pública.",
'email' => 'Corréu',
'prefs-help-realname' => "El nome real ye opcional y si decides conseñalu va ser usáu p'atribuyite'l to trabayu.",
'prefs-help-email' => 'La direición de corréu ye opcional, pero ye necesaria pa unviate una conseña nueva si escaeces la tuya.',
'prefs-help-email-others' => "Tamién pues escoyer permitir qu'otres persones contauten contigo pela to páxina d'usuariu o la d'alderique ensin necesidá de revelar la to identidá.",
'prefs-help-email-required' => 'Necesítase una direición de corréu electrónicu.',
'prefs-info' => 'Información básica',
'prefs-i18n' => 'Internacionalización',
'prefs-signature' => 'Robla',
'prefs-dateformat' => 'Formatu de data',
'prefs-timeoffset' => 'Diferencia horaria',
'prefs-advancedediting' => 'Opciones avanzaes',
'prefs-advancedrc' => 'Opciones avanzaes',
'prefs-advancedrendering' => 'Opciones avanzaes',
'prefs-advancedsearchoptions' => 'Opciones avanzaes',
'prefs-advancedwatchlist' => 'Opciones avanzaes',
'prefs-displayrc' => 'Opciones de vista',
'prefs-displaysearchoptions' => 'Opciones de vista',
'prefs-displaywatchlist' => 'Opciones de vista',
'prefs-diffs' => 'Diferencies',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'La direición de corréu paez válida',
'email-address-validity-invalid' => 'Escribi una direición de corréu válida',

# User rights
'userrights' => "Xestión de permisos d'usuariu",
'userrights-lookup-user' => 'Xestión de grupos del usuariu',
'userrights-user-editname' => "Escribi un nome d'usuariu:",
'editusergroup' => "Modificar grupos d'usuariu",
'editinguser' => "Camudando los permisos del usuariu '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => "Editar los grupos d'usuariu",
'saveusergroups' => "Guardar los grupos d'usuariu",
'userrights-groupsmember' => 'Miembru de:',
'userrights-groupsmember-auto' => 'Miembru implícitu de:',
'userrights-groups-help' => "Pues camudar los grupos a los que pertenez esti usuariu.
* Un caxellu marcáu significa que l'usuariu ta nesi grupu.
* Un caxellu non marcáu significa que l'usuariu nun ta nesi grupu.
* Un * indica que nun pues eliminalu del grupu una vegada tea inxeríu, o viceversa.",
'userrights-reason' => 'Motivu:',
'userrights-no-interwiki' => "Nun tienes permisu pa editar los derechos d'usuariu n'otres wikis.",
'userrights-nodatabase' => 'La base de datos $1 nun esiste o nun ye llocal.',
'userrights-nologin' => "Has tar [[Special:UserLogin|identificáu]] con una cuenta d'alministrador p'asignar derechos d'usuariu.",
'userrights-notallowed' => "La to cuenta nun tien permisu p'amestar o desaniciar permisos d'usuariu.",
'userrights-changeable-col' => 'Grupos que pues camudar',
'userrights-unchangeable-col' => 'Grupos que nun pues camudar',

# Groups
'group' => 'Grupu:',
'group-user' => 'Usuarios',
'group-autoconfirmed' => 'Usuarios autoconfirmaos',
'group-bot' => 'Bots',
'group-sysop' => 'Alministradores',
'group-bureaucrat' => 'Burócrates',
'group-suppress' => 'Güeyadores',
'group-all' => '(toos)',

'group-user-member' => '{{GENDER:$1|usuariu|usuaria}}',
'group-autoconfirmed-member' => '{{GENDER:$1|usuariu auto-confirmáu|usuaria auto-confirmada}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|alministrador|alministradora}}',
'group-bureaucrat-member' => '{{GENDER:$1|burócrata}}',
'group-suppress-member' => '{{GENDER:$1|supervisor|supervisora}}',

'grouppage-user' => '{{ns:project}}:Usuarios',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuarios autoconfirmaos',
'grouppage-bot' => '{{ns:project}}:Bots',
'grouppage-sysop' => '{{ns:project}}:Alministradores',
'grouppage-bureaucrat' => '{{ns:project}}:Burócrates',
'grouppage-suppress' => '{{ns:project}}:Güeyadores',

# Rights
'right-read' => 'Lleer páxines',
'right-edit' => 'Editar páxines',
'right-createpage' => "Crear páxines (que nun seyan páxines d'alderique)",
'right-createtalk' => "Crear páxines d'alderique",
'right-createaccount' => "Crear cuentes nueves d'usuariu",
'right-minoredit' => 'Marcar ediciones como menores',
'right-move' => 'Treslladar páxines',
'right-move-subpages' => 'Treslladar les páxines coles sos subpáxines',
'right-move-rootuserpages' => "Treslladar páxines d'un usuariu root",
'right-movefile' => 'Treslladar archivos',
'right-suppressredirect' => "Nun crear una redireición dende'l nome antiguu cuando se tresllada una páxina",
'right-upload' => 'Xubir ficheros',
'right-reupload' => 'Sobreescribir un archivu esistente',
'right-reupload-own' => 'Sobreescribir un archivu esistente xubíu pol mesmu usuariu',
'right-reupload-shared' => 'Anular llocalmente archivos del depósitu compartíu multimedia',
'right-upload_by_url' => 'Xubir un archivu dende una direición URL',
'right-purge' => 'Purgar la caché del sitiu pa una páxina que nun tenga páxina de confirmación',
'right-autoconfirmed' => 'Editar páxines semi-protexíes',
'right-bot' => 'Tratar como un procesu automatizáu',
'right-nominornewtalk' => "Nun amosar l'avisu de nuevos mensaxes cuando se faen ediciones menores en páxines d'alderique",
'right-apihighlimits' => 'Usar los llímites superiores nes consultes API',
'right-writeapi' => "Usar l'API d'escritura",
'right-delete' => 'Esborrar páxines',
'right-bigdelete' => 'Esborrar páxines con historiales grandes',
'right-deletelogentry' => 'Desaniciar y restaurar entraes del rexistru determinaes',
'right-deleterevision' => 'Eliminar y restaurar revisiones específiques de les páxines',
'right-deletedhistory' => 'Ver entraes eliminaes del historial ensin testu asociáu',
'right-deletedtext' => 'Ver el testu desaniciáu y los cambeos ente versiones desaniciaes',
'right-browsearchive' => 'Buscar páxines desaniciaes',
'right-undelete' => 'Restaurar una páxina',
'right-suppressrevision' => 'Revisar y restaurar revisiones ocultes a los alministradores',
'right-suppressionlog' => 'Ver rexistros privaos',
'right-block' => "Bloquiar la edición d'otros usuarios",
'right-blockemail' => "Bloquia-y l'unviu de corréu electrónicu a un usuariu",
'right-hideuser' => "Bloquiar un nome d'usuariu ocultándolu al públicu",
'right-ipblock-exempt' => "Saltar los bloqueos d'IP, los autobloqueos y los bloqueos d'intervalu",
'right-proxyunbannable' => 'Saltar los bloqueos automáticos de los proxys',
'right-unblockself' => 'Desbloquiase ellos mesmos',
'right-protect' => 'Camudar los niveles de proteición y editar páxines protexíes',
'right-editprotected' => 'Editar les páxines protexíes (ensin proteición en cascada)',
'right-editinterface' => "Editar la interfaz d'usuariu",
'right-editusercssjs' => "Editar los archivos CSS y JS d'otros usuarios",
'right-editusercss' => "Editar los archivos CSS d'otros usuarios",
'right-edituserjs' => "Editar los archivos JS d'otros usuarios",
'right-rollback' => "Revertir rápido a un usuariu qu'editó una páxina determinada",
'right-markbotedits' => 'Marcar les ediciones revertíes como ediciones de bot',
'right-noratelimit' => 'Nun tar afeutáu polos llímites de tasa',
'right-import' => 'Importar páxines dende otres wikis',
'right-importupload' => 'Importar páxines dende un archivu',
'right-patrol' => 'Marcar les ediciones como supervisaes',
'right-autopatrol' => 'Marcar automáticamente les ediciones como supervisaes',
'right-patrolmarks' => 'Ver les marques de supervisión de los cambeos recientes',
'right-unwatchedpages' => 'Ver una llista de páxines non vixilaes',
'right-mergehistory' => 'Fusionar historiales de páxines',
'right-userrights' => "Editar tolos drechos d'usuariu",
'right-userrights-interwiki' => "Editar los drechos d'usuariu d'usuarios d'otros sitios wiki",
'right-siteadmin' => 'Candar y descandar la base de datos',
'right-override-export-depth' => 'Esportar páxines, incluyendo páxines enllazaes fasta una fondura de 5',
'right-sendemail' => 'Unviar corréu a otros usuarios',
'right-passwordreset' => 'Ver los correos de reestablecimientu de conseña',

# User rights log
'rightslog' => "Rexistru de perfil d'usuariu",
'rightslogtext' => "Esti ye un rexistru de los cambeos de los perfiles d'usuariu.",
'rightslogentry' => 'camudó la pertenencia de grupu del usuariu $1 dende $2 a $3',
'rightslogentry-autopromote' => 'promocionó automáticamente de $2 a $3',
'rightsnone' => '(nengún)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lleer esta páxina',
'action-edit' => 'editar esta páxina',
'action-createpage' => 'crear páxines',
'action-createtalk' => "crear páxines d'alderique",
'action-createaccount' => "crear esta cuenta d'usuariu",
'action-minoredit' => 'marcar esta edición como menor',
'action-move' => 'treslladar esta páxina',
'action-move-subpages' => 'treslladar esta páxina y les sos subpáxines',
'action-move-rootuserpages' => "treslladar páxines d'un usuariu root",
'action-movefile' => 'treslladar esti archivu',
'action-upload' => 'xubir esti archivu',
'action-reupload' => 'sobreescribir esti archivu esistente',
'action-reupload-shared' => 'sustituyir esti archivu nun direutoriu compartíu',
'action-upload_by_url' => 'xubir esti archivu dende una direición URL',
'action-writeapi' => "usar l'API d'escritura",
'action-delete' => 'desaniciar esta páxina',
'action-deleterevision' => 'eliminar esta revisión',
'action-deletedhistory' => "ver l'historial elimináu d'esta páxina",
'action-browsearchive' => 'buscar páxines desaniciaes',
'action-undelete' => 'restaurar esta páxina',
'action-suppressrevision' => 'revisar y restaurar esta revisión oculta',
'action-suppressionlog' => 'ver esti rexistru priváu',
'action-block' => "bloquiar qu'esti usuariu edite",
'action-protect' => 'camudar los niveles de proteición pa esta páxina',
'action-rollback' => "Revertir rápido les ediciones del caberu usuariu qu'editó una páxina determinada",
'action-import' => 'importar esta páxina dende otra wiki',
'action-importupload' => "importar esta páxina dende una xubida d'archivu",
'action-patrol' => "marcar les ediciones d'otros como supervisaes",
'action-autopatrol' => 'marcar la to edición como supervisada',
'action-unwatchedpages' => 'ver la llista de páxines non vixilaes',
'action-mergehistory' => "fusionar l'historial d'esta páxina",
'action-userrights' => "editar tolos drechos d'usuariu",
'action-userrights-interwiki' => "editar los drechos d'usuariu d'usuarios d'otres wikis",
'action-siteadmin' => 'candar o descandar la base de datos',
'action-sendemail' => 'unviar correos',

# Recent changes
'nchanges' => '{{PLURAL:$1|un cambiu|$1 cambios}}',
'recentchanges' => 'Cambios recientes',
'recentchanges-legend' => 'Opciones de cambios recientes',
'recentchanges-summary' => 'Sigui los cambios más recientes na wiki nesta páxina.',
'recentchanges-feed-description' => 'Sigui nesta canal los últimos cambios de la wiki.',
'recentchanges-label-newpage' => 'Esta edición creó una páxina nueva',
'recentchanges-label-minor' => 'Esta ye una edición menor',
'recentchanges-label-bot' => 'Esta edición ta fecha por un bot',
'recentchanges-label-unpatrolled' => 'Esta edición ta ensin patrullar entá',
'rcnote' => "Equí embaxo {{PLURAL:$1|pue vese '''1''' cambiu|puen vese los caberos '''$1''' cambios}} {{PLURAL:$2|del caberu día|de los caberos '''$2''' díes}}, a fecha de $5, $4.",
'rcnotefrom' => "Abaxo tan los cambeos dende '''$2''' (s'amuesen fasta '''$1''').",
'rclistfrom' => 'Amosar los nuevos cambios dende $1',
'rcshowhideminor' => '$1 ediciones menores',
'rcshowhidebots' => '$1 bots',
'rcshowhideliu' => '$1 usuarios rexistraos',
'rcshowhideanons' => '$1 usuarios anónimos',
'rcshowhidepatr' => '$1 ediciones supervisaes',
'rcshowhidemine' => '$1 les mios ediciones',
'rclinks' => 'Amosar los caberos $1 cambios de los caberos $2 díes <br />$3',
'diff' => 'dif',
'hist' => 'hist',
'hide' => 'Anubrir',
'show' => 'Amosar',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usuariu|ususarios}} vixilando]',
'rc_categories' => 'Llímite pa les categoríes (dixebrar con "|")',
'rc_categories_any' => 'Cualesquiera',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|bytes}} dempués del cambiu',
'newsectionsummary' => '/* $1 */ nueva seición',
'rc-enhanced-expand' => 'Amosar detalles (requier JavaScript)',
'rc-enhanced-hide' => 'Anubrir los detalles',
'rc-old-title' => 'creada orixinalmente como «$1»',

# Recent changes linked
'recentchangeslinked' => 'Cambios rellacionaos',
'recentchangeslinked-feed' => 'Cambios rellacionaos',
'recentchangeslinked-toolbox' => 'Cambios rellacionaos',
'recentchangeslinked-title' => 'Cambios rellacionaos con "$1"',
'recentchangeslinked-noresult' => 'Nun hebo cambios nes páxines enllaciaes nel periodu conseñáu.',
'recentchangeslinked-summary' => "Esta ye una llista de los caberos cambios fechos nes páxines enllaciaes dende una páxina determinada (o nos miembros d'una categoría determinada). Les páxines de [[Special:Watchlist|la to llista de vixilancia]] tán en '''negrina'''.",
'recentchangeslinked-page' => 'Nome de la páxina:',
'recentchangeslinked-to' => "Amosar los cambios de les páxines qu'enllacen en cuenta de los de la páxina dada",

# Upload
'upload' => 'Xubir ficheru',
'uploadbtn' => 'Xubir ficheru',
'reuploaddesc' => 'Cancelar la xubida y tornar al formulariu de xubíes',
'upload-tryagain' => 'Unviar descripción camudada del ficheru',
'uploadnologin' => 'Non identificáu',
'uploadnologintext' => 'Tienes que tar [[Special:UserLogin|identificáu]] pa poder xubir archivos.',
'upload_directory_missing' => 'El direutoriu de xubida ($1) nun esiste y nun pudo ser creáu pol sirvidor de web.',
'upload_directory_read_only' => "El sirvidor nun pue modificar el direutoriu de xubida d'archivos ($1).",
'uploaderror' => 'Error de xubida',
'upload-recreate-warning' => "'''Avisu: Se desanició o treslladó un ficheru con esi nome.'''

Equí s'ufre'l rexistru de desaniciu y treslláu d'esta páxina por comodidá:",
'uploadtext' => "Usa'l formulariu d'abaxo pa xubir archivos.
Pa ver o buscar archivos xubíos previamente, vete a la [[Special:FileList|llista d'archivos xubíos]]. Les xubíes tamién queden conseñaos nel [[Special:Log/upload|rexistru de xubíes]], y los esborraos nel [[Special:Log/delete|rexistru d'esborraos]].

P'amiestar un archivu nuna páxina, usa un enllaz con ún de los siguientes formatos:
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Archivu.jpg]]</nowiki></code>''' pa usar la versión completa del archivu
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Archivu.png|200px|thumb|left|testu alternativu]]</nowiki></code>''' pa usar un renderizáu de 200 píxeles d'anchu nun caxellu al marxe esquierdu con 'testu alternativu' como la so descripción
*'''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Archivu.ogg]]</nowiki></code>''' pa enllazar direutamente al archivu ensin amosar l'archivu",
'upload-permitted' => "Menes d'archivu permitíes: $1.",
'upload-preferred' => "Menes d'archivu preferíes: $1.",
'upload-prohibited' => "Menes d'archivu prohibíes: $1.",
'uploadlog' => 'rexistru de xubíes',
'uploadlogpage' => 'Rexistru de xubíes',
'uploadlogpagetext' => "Abaxo amuésase una llista de les xubíes d'archivos más recientes.
Mira la [[Special:NewFiles|galería d'archivos nuevos]] pa una güeyada más visual.",
'filename' => 'Nome del ficheru',
'filedesc' => 'Resume',
'fileuploadsummary' => 'Resume:',
'filereuploadsummary' => 'Cambios del ficheru:',
'filestatus' => 'Estáu de Copyright:',
'filesource' => 'Fonte:',
'uploadedfiles' => 'Archivos xubíos',
'ignorewarning' => "Inorar l'avisu y grabar l'archivu de toes formes",
'ignorewarnings' => 'Inorar tolos avisos',
'minlength1' => "Los nomes d'archivu han tener a lo menos una lletra.",
'illegalfilename' => 'El nome d\'archivu "$1" contién carauteres non permitíos en títulos de páxina. Por favor renoma l\'archivu y xúbilu otra vuelta.',
'filename-toolong' => 'Los nomes de ficheru nun puen tener más de 240 bytes.',
'badfilename' => 'Nome de la imaxe camudáu a "$1".',
'filetype-mime-mismatch' => 'La estensión de ficheru ".$1" nun casa cola triba MIME detectada del ficheru ($2).',
'filetype-badmime' => 'Los ficheros de la triba MIME "$1" nun tienen permitida la xubida.',
'filetype-bad-ie-mime' => 'Nun se pue xubir esti ficheru porque Internet Explorer detectalu como "$1", que nun ta permitíu y pue ser una triba de ficheru peligrosa.',
'filetype-unwanted-type' => "'''\".\$1\"''' ye una triba de ficheru non recomendáu.
{{PLURAL:\$3|La triba de ficheru preferida ye|Les tribes de ficheru preferíes son}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|nun ye una triba de ficheru permitida|nun son tribes de ficheru permitíes}}.
{{PLURAL:$3|La triba de ficheru permitida ye|Les tribes de ficheru permitíes son}} $2.',
'filetype-missing' => 'El ficheru nun tien estensión (como ".jpg").',
'empty-file' => "El ficheru qu'unviasti taba baleru.",
'file-too-large' => "El ficheru qu'unviasti yera demasiao grande.",
'filename-tooshort' => 'El nome de ficheru ye demasiao curtiu.',
'filetype-banned' => 'Esta triba de ficheru ta torgada.',
'verification-error' => 'Esti ficheru nun pasó la comprobación de ficheros.',
'hookaborted' => 'La conexón con una estensión encaboxó el cambéu que tentasti facer.',
'illegal-filename' => 'El nome de ficheru nun ta permitíu.',
'overwrite' => 'Nun ta permitío sobroscribir un ficheru esistente.',
'unknown-error' => 'Hebo un error desconocíu.',
'tmp-create-error' => 'Nun se pudo crear el ficheru temporal.',
'tmp-write-error' => 'Error al escribir nel ficheru temporal.',
'large-file' => 'Encamiéntase a que los ficheros nun pasen de $1;
esti ficheru tien $2.',
'largefileserver' => 'Esti ficheru ye mayor de lo que permite la configuración del sirvidor.',
'emptyfile' => "El ficheru que xubisti paez tar vaciu.
Esto podría ser pola mor d'un enquivocu nel nome del ficheru.
Por favor, camienta si daveres quies xubir esti archivu.",
'windows-nonascii-filename' => 'Esta wiki nun permite nomes de ficheru con caráuteres especiales.',
'fileexists' => 'Yá esiste un ficheru con esti nome, por favor comprueba <strong>[[:$1]]</strong> si nun tas seguru de querer camudalu.
[[$1|thumb]]',
'filepageexists' => "La páxina de descripción d'esti ficheru yá se creó en <strong>[[:$1]]</strong>, pero anguaño nun esiste nengún ficheru con esti nome.
El resume que pongas nun va apaecer na páxina de descripción.
Pa facer que'l to resume apaeza, vas tener que lu editar manualmente.
[[$1|thumb]]",
'fileexists-extension' => 'Yá esiste un ficheru con un nome asemeyáu: [[$2|thumb]]
* Nome del ficheru que se quier xubir: <strong>[[:$1]]</strong>
* Nome del ficheru esistente: <strong>[[:$2]]</strong>
Por favor escueyi un nome diferente.',
'fileexists-thumbnail-yes' => "El ficheru paez ser una imaxe de tamañu menguáu ''(miniatura)''.
 [[$1|thumb]]
Por favor comprueba el ficheru <strong>[[:$1]]</strong>.
Si'l ficheru comprobáu tien el mesmu tamañu que la imaxe orixinal, nun ye necesario xubir una miniatura estra.",
'file-thumbnail-no' => "El ficheru entama con <strong>$1</strong>.
Paez ser una imaxe de tamañu menguáu ''(miniatura)''.
Si tienes esta imaxe a resolución completa xúbila; si non, por favor camuda'l nome del ficheru.",
'fileexists-forbidden' => 'Yá esiste un ficheru con esti nome, y nun se pue renomar.
Si tovía asina quies xubir el ficheru, por favor vuelvi atrás y usa otru nome.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Yá esiste un ficheru con esti nome nel direutoriu de ficheros compartíos.
Si tovía asina quies xubir el ficheru, por favor vuelvi atrás y usa otru nome.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Esti ficheru ye un duplicáu {{PLURAL:$1|del siguiente ficheru|de los siguientes ficheros}}:',
'file-deleted-duplicate' => 'Yá se desanició enantes un ficheru idénticu a esti ([[:$1]]).
Deberíes revisar el historial de desaniciu del ficheru enantes de xubilu otra vuelta.',
'uploadwarning' => 'Avisu de xubíes de ficheros',
'uploadwarning-text' => 'Por favor, camuda más abaxo la descripción del ficheru y vuelve a tentalo.',
'savefile' => 'Guardar ficheru',
'uploadedimage' => 'xubió "[[$1]]"',
'overwroteimage' => 'xubió una versión nueva de "[[$1]]"',
'uploaddisabled' => 'Deshabilitaes les xubíes',
'copyuploaddisabled' => 'Xubir por URL ta desactivao.',
'uploadfromurl-queued' => 'La to xubía ta na cola.',
'uploaddisabledtext' => 'Les xubíes de ficheros tán desactivaes.',
'php-uploaddisabledtext' => 'Les xubíes de ficheros tan desactivaes en PHP.
Por favor, comprueba la configuración de file_uploads.',
'uploadscripted' => 'Esti ficheru contién códigu HTML o scripts que se puen interpretar equivocadamente por un navegador.',
'uploadvirus' => '¡El ficheru tien un virus!
Detalles: $1',
'uploadjava' => 'El ficheru ye un archivu ZIP que contién un ficheru .class de Java.
Xubir ficheros Java nun ta permitío, porque puen facer que se salten les torgues de seguridá.',
'upload-source' => 'Ficheru orixe',
'sourcefilename' => "Nome d'orixe:",
'sourceurl' => "URL d'orixe:",
'destfilename' => 'Nome de destín:',
'upload-maxfilesize' => 'Tamañu máximu del ficheru: $1',
'upload-description' => 'Descripción del ficheru',
'upload-options' => 'Opciones de xubía',
'watchthisupload' => 'Vixilar esti ficheru',
'filewasdeleted' => 'Yá se xubió y se desanició depués un ficheru con esti nome.
Habríes comprobar el $1 enantes de volver a xubilu.',
'filename-bad-prefix' => "El nome del ficheru que tas xubiendo entama con '''\"\$1\"''', que ye un nome non descriptivu que de vezu conseñen automáticamente les cámares dixitales.
Por favor escueyi un nome más descriptivu pal to ficheru.",
'filename-prefix-blacklist' => ' #<!-- dexa esta llinia exactamente como ta --> <pre>
# La sintaxis ye la siguiente:
#   * Lo que va del caráuter "#" al fin de llinia ye un comentariu
#   * Toa llinia non-balera ye un prefixu pa los nomes de ficheru típicos que ponen les cámares dixitales
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # dellos teléfonos móviles
IMG # xenéricu
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- dexa esta llinia exactamente como ta -->',
'upload-success-subj' => 'Xubida correuta',
'upload-success-msg' => 'La xubía de [$2] foi correuta. Ta disponible equí: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problema na xubía',
'upload-failure-msg' => 'Hebo un problema cola to xubía de [$2]:

$1',
'upload-warning-subj' => 'Avisu de xubía',
'upload-warning-msg' => 'Hebo un problema cola to xubía de [$2]. Pues volver al [[Special:Upload/stash/$1|formulariu de xubía]] pa iguar esti problema.',

'upload-proto-error' => 'Protocolu incorreutu',
'upload-proto-error-text' => "La xubida remota requier que l'URL entame por <code>http://</code> o <code>ftp://</code>.",
'upload-file-error' => 'Error internu',
'upload-file-error-text' => 'Hebo un error al intentar crear un ficheru temporal nel sirvidor.
Por favor contauta con un [[Special:ListUsers/sysop|alministrador]] del sistema.',
'upload-misc-error' => 'Error de xubida desconocíu',
'upload-misc-error-text' => "Hebo un error desconocíu na xubida del ficheru.
Por favor comprueba que l'URL ye válidu y accesible, y inténtalo otra vuelta.
Si'l problema persiste, contauta con un [[Special:ListUsers/sysop|alministrador]] del sistema.",
'upload-too-many-redirects' => 'La URL contenía demasiaes redireiciones',
'upload-unknown-size' => 'Tamañu desconocíu',
'upload-http-error' => 'Hebo un error HTTP: $1',
'upload-copy-upload-invalid-domain' => 'La xubida de copies nun ta disponible dende esti dominiu.',

# File backend
'backend-fail-stream' => 'Nun se pudo tresmitir el ficheru $1.',
'backend-fail-backup' => 'Nun se pudo facer copia de seguridá del ficheru $1.',
'backend-fail-notexists' => 'El ficheru $1 nun esiste.',
'backend-fail-hashes' => 'Nun se pudieron alcontrar los hashes del ficheru pa comparar.',
'backend-fail-notsame' => 'Yá esiste un ficheru non idénticu en $1.',
'backend-fail-invalidpath' => "$1 nun ye una ruta d'almacenamientu válida.",
'backend-fail-delete' => 'Nun se pudo desaniciar el ficheru $1.',
'backend-fail-alreadyexists' => 'El ficheru $1 yá esiste.',
'backend-fail-store' => 'Nun se pudo guardar el ficheru $1 en $2.',
'backend-fail-copy' => 'Nun se pudo copiar el ficheru $1 como $2.',
'backend-fail-move' => 'Nun se pudo mover el ficheru $1 a $2.',
'backend-fail-opentemp' => 'Nun se pudo abrir el ficheru temporal.',
'backend-fail-writetemp' => 'Nun se pudo escribir nel ficheru temporal.',
'backend-fail-closetemp' => 'Nun se pudo zarrar el ficheru temporal.',
'backend-fail-read' => 'Nun se pudo lleer el ficheru $1.',
'backend-fail-create' => 'Nun se pudo escribir el ficheru $1.',
'backend-fail-maxsize' => 'Nun se pudo escribir el ficheru  $1  porque ye mayor de {{PLURAL:$2|un byte|$2 bytes}}.',
'backend-fail-readonly' => 'Nesti momentu el motor d\'almacenamientu "$1" ta en mou de sólo llectura. El motivu dau foi: "$2"',
'backend-fail-synced' => 'El ficheru "$1" ta nún estáu inconsistente colos motores d\'almacenamientu internos',
'backend-fail-connect' => 'Nun se pudo coneutar col motor d\'almacenamientu "$1".',
'backend-fail-internal' => 'Hebo un fallu desconocíu nel motor d\'almacenamientu "$1".',
'backend-fail-contenttype' => 'Non se pudo determinar la triba de conteníu de ficheru a guardar en "$1".',
'backend-fail-batchsize' => "El motor d'almacenamientu dio un llote de $1 {{PLURAL:$1|operación|operaciones}} en ficheros; el llímite ye de $2 {{PLURAL:$2|operación|operaciones}}.",
'backend-fail-usable' => 'Nun se pudo llee o escribir el ficheru «$1» porque nun hai permisos bastantes o falten los direutorios/contenedores.',

# File journal errors
'filejournal-fail-dbconnect' => 'Nun se pudo coneutar cola base de datos del diariu pal sofitu d\'almacenamientu "$1".',
'filejournal-fail-dbquery' => 'Nun se pudo anovar la base de datos del diariu pal sofitu d\'almacenamientu "$1".',

# Lock manager
'lockmanager-notlocked' => 'Nun se pudo desbloquiar "$1"; nun ta bloquiáu.',
'lockmanager-fail-closelock' => 'Nun se pudo zarrar el ficheru de bloquéu pa "$1".',
'lockmanager-fail-deletelock' => 'Non se pudo desaniciar el ficheru de bloquéu pa "$1".',
'lockmanager-fail-acquirelock' => 'Nun se pudo adquirir el bloquéu pa "$1".',
'lockmanager-fail-openlock' => 'Nun se pudo abrir el ficheru de bloquéu pa "$1".',
'lockmanager-fail-releaselock' => 'Nun se pudo lliberar el bloquéu pa "$1".',
'lockmanager-fail-db-bucket' => 'Nun se pudo contautar con bloqueos de bases de datos bastantes nel conxuntu $1.',
'lockmanager-fail-db-release' => 'Nun se pudieron lliberar los bloqueos na base de datos $1.',
'lockmanager-fail-svr-acquire' => 'Nun se pudieron afitar los bloqueos nel sirvidor $1.',
'lockmanager-fail-svr-release' => 'Nun se pudieron lliberar los bloqueos nel sirvidor $1.',

# ZipDirectoryReader
'zip-file-open-error' => "S'alcontró un fallu al abrir el ficheru pa les comprobaciones del ZIP.",
'zip-wrong-format' => 'El ficheru especificáu nun yera un ficheru ZIP.',
'zip-bad' => 'El ficheru ZIP ta corrompíu o ye imposible lleelu.
Nun se pudo comprobar la seguridá de mou afayadizu.',
'zip-unsupported' => "El ficheru ye un archivu ZIP qu'usa carauterístiques ensin encontu en MediaWiki.
Nun se pue comprobar la seguridá de mou afayadizu.",

# Special:UploadStash
'uploadstash' => 'Ficheros na reserva',
'uploadstash-summary' => "Esta páxina ufre accesu a ficheros que tan xubíos (o en procesu de xubía), pero qu'entá nun s'espublizaron na wiki. Estos ficheros nun son visibles pa naide, sacante l'usuariu que los xubió.",
'uploadstash-clear' => 'Desaniciar los ficheros de la reserva',
'uploadstash-nofiles' => 'Nun tienes ficheros na reserva.',
'uploadstash-badtoken' => 'Nun se pudo facer esa operación, seique porque caducó la identificación pa editar. Vuelvi a intentalo.',
'uploadstash-errclear' => 'Nun se pudieron desaniciar los ficheros.',
'uploadstash-refresh' => 'Anovar la llista de ficheros',
'invalid-chunk-offset' => 'Allugamientu inválidu del fragmentu',

# img_auth script messages
'img-auth-accessdenied' => 'Accesu denegáu',
'img-auth-nopathinfo' => 'Falta PATH_INFO.
El to sirvidor nun ta configuráu pa pasar esta información.
Pue tar basáu en CGI y nun tener sofitu pa img_auth.
Ver https://www.mediawiki.org/wiki/Manual:Image_Authorization',
'img-auth-notindir' => 'El camín solicitáu nun ta nel direutoriu de xubíes configuráu.',
'img-auth-badtitle' => 'Nun se pue construir un títulu validu dende "$1".',
'img-auth-nologinnWL' => 'Nun tas coneutáu y "$1" nun ta na llista blanca.',
'img-auth-nofile' => 'El ficheru "$1" nun esiste.',
'img-auth-isdir' => 'Tas tentando acceder al direutoriu "$1".
Namái se permite l\'accesu a ficheros.',
'img-auth-streaming' => 'Unviando "$1".',
'img-auth-public' => "La función de img_auth.php ye sacar ficheros d'una wiki privada.
Esta wiki ta configurada como wiki pública.
Pa una meyor seguridá, img_auth.php ta desactiváu.",
'img-auth-noread' => 'L\'usuariu nun tien accesu pa lleer "$1".',
'img-auth-bad-query-string' => 'La URL tien una cadena de consulta inválida.',

# HTTP errors
'http-invalid-url' => 'URL inválida: $1',
'http-invalid-scheme' => 'Les URLs col esquema "$1" nun tienen sofitu.',
'http-request-error' => 'La llamada HTTP fallló por un error desconocíu.',
'http-read-error' => 'Error de llectura HTTP.',
'http-timed-out' => "La llamada HTTP escosó'l tiempu.",
'http-curl-error' => 'Error al baxar la URL: $1',
'http-host-unreachable' => 'Nun se pudo acceder a la URL.',
'http-bad-status' => 'Hebo un problema demientres la llamada HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Nun se pudo acceder a la URL',
'upload-curl-error6-text' => "Nun se pudo acceder a la URL introducida. Por favor comprueba que la URL ye correuta y que'l sitiu ta activu.",
'upload-curl-error28' => "Fin del tiempu d'espera de la xubida",
'upload-curl-error28-text' => "El sitiu tardó demasiáu tiempu en responder. Por favor comprueba que'l sitiu ta activu, espera unos momentos y vuelve a intentalo. Igual ye meyor que lo intentes nun momentu en que tea menos sobrecargáu.",

'license' => 'Llicencia:',
'license-header' => 'Llicencia',
'nolicense' => 'Nenguna seleicionada',
'license-nopreview' => '(Previsualización non disponible)',
'upload_source_url' => ' (una URL válida y accesible públicamente)',
'upload_source_file' => ' (un archivu del to ordenador)',

# Special:ListFiles
'listfiles-summary' => "Esta páxina especial amuesa tolos ficheros xubíos.
Al peñerar por usuariu, s'amuesa namái la cabera versión de los ficheros que xubió esi usuariu.",
'listfiles_search_for' => "Buscar por nome d'archivu multimedia:",
'imgfile' => 'archivu',
'listfiles' => "Llista d'imáxenes",
'listfiles_thumb' => 'Miniatura',
'listfiles_date' => 'Data',
'listfiles_name' => 'Nome',
'listfiles_user' => 'Usuariu',
'listfiles_size' => 'Tamañu',
'listfiles_description' => 'Descripción',
'listfiles_count' => 'Versiones',

# File description page
'file-anchor-link' => 'Ficheru',
'filehist' => 'Historial del ficheru',
'filehist-help' => 'Calca nuna fecha/hora pa ver el ficheru como taba daquella.',
'filehist-deleteall' => 'esborrar too',
'filehist-deleteone' => 'desaniciar',
'filehist-revert' => 'revertir',
'filehist-current' => 'actual',
'filehist-datetime' => 'Data/Hora',
'filehist-thumb' => 'Miniatura',
'filehist-thumbtext' => 'Miniatura de la versión a fecha de $1',
'filehist-nothumb' => 'Ensin miniatura',
'filehist-user' => 'Usuariu',
'filehist-dimensions' => 'Dimensiones',
'filehist-filesize' => 'Tamañu del ficheru',
'filehist-comment' => 'Comentariu',
'filehist-missing' => 'Falta ficheru',
'imagelinks' => 'Usu del ficheru',
'linkstoimage' => '{{PLURAL:$1|La páxina siguiente enllacia|Les páxines siguientes enllacien}} a esti ficheru:',
'linkstoimage-more' => "Más de $1 {{PLURAL:$1|páxina enllacia|páxines enllacien}} a esti archivu.
La llista siguiente amuesa{{PLURAL:$1|'l primer enllaz de páxina| los primeros $1 enllaces de páxina}} a esti archivu namái.
Hai disponible una [[Special:WhatLinksHere/$2|llista completa]].",
'nolinkstoimage' => "Nun hai páxines qu'enllacien a esti ficheru.",
'morelinkstoimage' => 'Ver [[Special:WhatLinksHere/$1|más enllaces]] a esti archivu.',
'linkstoimage-redirect' => '$1 (redireición de ficheru) $2',
'duplicatesoffile' => "{{PLURAL:$1|El siguiente archivu ye un duplicáu|Los siguientes $1 archivos son duplicaos}} d'esti archivu ([[Special:FileDuplicateSearch/$2|más detalles]]):",
'sharedupload' => "Esti ficheru ye de $1 y se pue usar n'otros proyeutos.",
'sharedupload-desc-there' => 'Esti ficheru ye de $1 y puen usalu otros proyeutos.
Llee la [páxina de descripción del ficheru $2] pa más información.',
'sharedupload-desc-here' => "Esti ficheru ye de $1 y puen usalu otros proyeutos.
La descripción de la [$2 páxina de descripción del ficheru] s'amuesa darréu.",
'sharedupload-desc-edit' => "Esti ficheru ye de $1 y se pue usar n'otros proyeutos.
Seique quieras camudar la descripción de la so [páxina de descripción de ficheru $2] allí.",
'sharedupload-desc-create' => "Esti ficheru ye de $1 y se pue usar n'otros proyeutos.
Seique quieras camudar la descripción de la so [páxina de descripción de ficheru $2] allí.",
'filepage-nofile' => 'Nun esiste dengún ficheru con esti nome.',
'filepage-nofile-link' => 'Nun esiste ficheru dalu con esti nome, pero pues [$1 xubilu].',
'uploadnewversion-linktext' => "Xubir una nueva versión d'esta imaxe",
'shared-repo-from' => 'de $1',
'shared-repo' => 'un repositoriu compartíu',
'filepage.css' => "/* El CSS allugáu equí s'incluye na páxina de descripción del ficheru, que tamién s'incluye nes wikis clientes foriates */",
'upload-disallowed-here' => 'Nun pues sobrescribir esti ficheru.',

# File reversion
'filerevert' => 'Revertir $1',
'filerevert-legend' => 'Revertir archivu',
'filerevert-intro' => "Tas revirtiendo '''[[Media:$1|$1]]''' a la [$4 versión del $3 a les $2].",
'filerevert-comment' => 'Motivu:',
'filerevert-defaultcomment' => 'Revertida a la versión del $2 a les $1',
'filerevert-submit' => 'Revertir',
'filerevert-success' => "'''[[Media:$1|$1]]''' foi revertida a la [$4 versión del $3 a les $2].",
'filerevert-badversion' => "Nun hai nenguna versión llocal previa d'esti archivu cola fecha conseñada.",

# File deletion
'filedelete' => 'Desaniciar $1',
'filedelete-legend' => 'Esborrar archivu',
'filedelete-intro' => "Tas a piques d'esborrar el ficheru '''[[Media:$1|$1]]''' xunto con tol so historial.",
'filedelete-intro-old' => "Tas esborrando la versión de '''[[Media:$1|$1]]''' del [$4 $3 a les $2].",
'filedelete-comment' => 'Motivu:',
'filedelete-submit' => 'Desaniciar',
'filedelete-success' => "'''$1''' se desanició.",
'filedelete-success-old' => "Eliminóse la versión de '''[[Media:$1|$1]]''' del $2 a les $3.",
'filedelete-nofile' => "'''$1''' nun esiste.",
'filedelete-nofile-old' => "Nun hai nenguna versión archivada de  '''$1''' colos atributos especificaos.",
'filedelete-otherreason' => 'Motivu distintu/adicional:',
'filedelete-reason-otherlist' => 'Otru motivu',
'filedelete-reason-dropdown' => "*Motivos comunes d'esborráu
** Violación de Copyright
** Archivu duplicáu",
'filedelete-edit-reasonlist' => "Editar los motivos d'esborráu",
'filedelete-maintenance' => 'El desaniciu y restauración de ficheros ta desactivao temporalmente mientres ta en mantenimientu.',
'filedelete-maintenance-title' => 'Nun se pue desaniciar el ficheru',

# MIME search
'mimesearch' => 'Busca MIME',
'mimesearch-summary' => "Esta páxina activa'l filtráu d'archivos en función de la so triba MIME. Entrada: contenttype/subtype, p.ex. <code>image/jpeg</code>.",
'mimetype' => 'Triba MIME:',
'download' => 'descargar',

# Unwatched pages
'unwatchedpages' => 'Páxines ensin vixilar',

# List redirects
'listredirects' => 'Llista de redireiciones',

# Unused templates
'unusedtemplates' => 'Plantíes ensin usu',
'unusedtemplatestext' => "Esta páxina llista toles páxines del espaciu de nomes {{ns:template}} que nun tán inxeríes n'otres páxines.
Alcuérdate de comprobar otros enllaces a les plantíes enantes d'esborrales.",
'unusedtemplateswlh' => 'otros enllaces',

# Random page
'randompage' => 'Páxina al debalu',
'randompage-nopages' => 'Nun hai páxines {{PLURAL:$2|nel espaciu|nos espacios}} de nomes darréu: "$1".',

# Random redirect
'randomredirect' => 'Redireición al debalu',
'randomredirect-nopages' => 'Nun hai redireiciones nel espaciu de nomes "$1".',

# Statistics
'statistics' => 'Estadístiques',
'statistics-header-pages' => 'Estadístiques de páxines',
'statistics-header-edits' => "Estadístiques d'ediciones",
'statistics-header-views' => 'Estadístiques de visites',
'statistics-header-users' => "Estadístiques d'usuariu",
'statistics-header-hooks' => 'Otres estadístiques',
'statistics-articles' => 'Páxines de conteníu',
'statistics-pages' => 'Páxines',
'statistics-pages-desc' => "Toles páxines de la wiki, incluyendo páxines d'alderique, redireiciones, etc.",
'statistics-files' => 'Archivos xubíos',
'statistics-edits' => "Ediciones de páxines dende qu'entamó {{SITENAME}}",
'statistics-edits-average' => "Media d'ediciones por páxina",
'statistics-views-total' => 'Visites totales',
'statistics-views-total-desc' => "Les vistes de páxines non-esistentes y especiales nun s'incluyen",
'statistics-views-peredit' => 'Visites por edición',
'statistics-users' => '[[Special:ListUsers|Usuarios]] rexistraos',
'statistics-users-active' => 'Usuarios activos',
'statistics-users-active-desc' => 'Usuarios que realizaron una aición {{PLURAL:$1|nel caberu día|nos caberos $1 díes}}',
'statistics-mostpopular' => 'Páxines más vistes',

'disambiguations' => "Páxines qu'enllacen con páxines de dixebra",
'disambiguationspage' => 'Template:dixebra',
'disambiguations-text' => "Les siguientes páxines contienen polo menos un enllaz a una '''páxina de dixebra'''. En cuenta d'ello habríen enllaciar a una páxina más apropiada.<br />
Una páxina tratase como una páxina de dixebra si usa una plantía que tea enllaciada dende [[MediaWiki:Disambiguationspage]]",

'doubleredirects' => 'Redireiciones dobles',
'doubleredirectstext' => 'Esta páxina llista páxines que redireicionen a otres páxines de redireición.
Cada filera contién enllaces a la primer y segunda redireición, asina como al oxetivu de la segunda redireición, que de vezu ye la páxina oxetivu "real", onde tendría d\'empobinar la primer redireición.
Les entraes <del>tachaes</del> tan resueltes.',
'double-redirect-fixed-move' => '[[$1]] foi treslladáu, agora ye una redireición haza [[$2]]',
'double-redirect-fixed-maintenance' => 'Iguando la doble redireición de [[$1]] a [[$2]].',
'double-redirect-fixer' => 'Iguador de redireiciones',

'brokenredirects' => 'Redireiciones rotes',
'brokenredirectstext' => 'Les siguientes redireiciones enllacien a páxines non esistentes:',
'brokenredirects-edit' => 'editar',
'brokenredirects-delete' => 'desaniciar',

'withoutinterwiki' => 'Páxines ensin interwikis',
'withoutinterwiki-summary' => "Les páxines siguientes nun enllacien a versiones n'otres llingües:",
'withoutinterwiki-legend' => 'Prefixu',
'withoutinterwiki-submit' => 'Amosar',

'fewestrevisions' => "Páxines col menor númberu d'ediciones",

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories' => '$1 {{PLURAL:$1|categoría|categoríes}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwiki|interwikis}}',
'nlinks' => '$1 {{PLURAL:$1|enllaz|enllaces}}',
'nmembers' => '$1 {{PLURAL:$1|miembru|miembros}}',
'nrevisions' => '$1 {{PLURAL:$1|revisión|revisiones}}',
'nviews' => '$1 {{PLURAL:$1|vista|vistes}}',
'nimagelinks' => 'Usao en $1 {{PLURAL:$1|páxina|páxines}}',
'ntransclusions' => 'usao en $1 {{PLURAL:$1|páxina|páxines}}',
'specialpage-empty' => 'Nun hai resultaos nestos momentos.',
'lonelypages' => 'Páxines güérfanes',
'lonelypagestext' => 'Les páxines siguientes nun tán enllaciaes nin trescluyíes dende otres páxines de {{SITENAME}}.',
'uncategorizedpages' => 'Páxines non categorizaes',
'uncategorizedcategories' => 'Categoríes non categorizaes',
'uncategorizedimages' => 'Archivos non categorizaos',
'uncategorizedtemplates' => 'Plantíes non categorizaes',
'unusedcategories' => 'Categoríes non usaes',
'unusedimages' => 'Imáxenes non usaes',
'popularpages' => 'Páxines populares',
'wantedcategories' => 'Categoríes buscaes',
'wantedpages' => 'Páxines buscaes',
'wantedpages-badtitle' => 'Títulu inválidu nel conxuntu de resultaos: $1',
'wantedfiles' => 'Archivos buscaos',
'wantedfiletext-cat' => "Los ficheros siguientes tan usándose, pero nun esisten. Ye posible qu'apaezan ficheros de repositorios esternos ensin qu'esistan. Cualesquier falsu positivu tará <del>tacháu</del>. Amás, les páxines qu'inxerten ficheros que nun esisten apaecen na llista de [[:$1]].",
'wantedfiletext-nocat' => "Los ficheros siguientes tan usándose, pero nun esisten. Ye posible qu'apaezan ficheros de repositorios esternos ensin qu'esistan. Cualesquier falsu positivu tará <del>tacháu</del>.",
'wantedtemplates' => 'Plantíes más buscaes',
'mostlinked' => 'Páxines más enllaciaes',
'mostlinkedcategories' => 'Categoríes más enllaciaes',
'mostlinkedtemplates' => 'Plantíes más enllaciaes',
'mostcategories' => 'Páxines con más categoríes',
'mostimages' => 'Archivos más enllaciaos',
'mostinterwikis' => 'Páxines con más interwikis',
'mostrevisions' => 'Páxines con más revisiones',
'prefixindex' => 'Toles páxines col prefixu',
'prefixindex-namespace' => 'Toles páxines col prefixu (espaciu de nomes $1)',
'shortpages' => 'Páxines curties',
'longpages' => 'Páxines llargues',
'deadendpages' => 'Páxines ensin salida',
'deadendpagestext' => 'Les páxines siguientes nun enllacien a páxina dala de {{SITENAME}}.',
'protectedpages' => 'Páxines protexíes',
'protectedpages-indef' => 'Namái les proteiciones permanentes',
'protectedpages-cascade' => 'Namái proteiciones en cascada',
'protectedpagestext' => "Les páxines siguientes tán protexíes escontra'l treslláu y la edición",
'protectedpagesempty' => 'Nun hai páxines protexíes anguaño con estos parámetros.',
'protectedtitles' => 'Títulos protexíos',
'protectedtitlestext' => 'Los siguiente títulos tán protexíos de la so creación',
'protectedtitlesempty' => 'Nun hai títulos protexíos anguaño con estos parámetros.',
'listusers' => "Llista d'usuarios",
'listusers-editsonly' => 'Amosar namái usuarios con ediciones',
'listusers-creationsort' => 'Ordenar por data de creación',
'usereditcount' => '$1 {{PLURAL:$1|edición|ediciones}}',
'usercreated' => "{{GENDER:$3|Creáu el|Creada'l}} $1 a les $2",
'newpages' => 'Páxines nueves',
'newpages-username' => "Nome d'usuariu:",
'ancientpages' => 'Páxines más vieyes',
'move' => 'Treslladar',
'movethispage' => 'Treslladar esta páxina',
'unusedimagestext' => "Los ficheros darréu esisten pero nun tan inxertaos en páxina dala.
Date cuenta de qu'otros sitios web puen enllazar a un ficheru con una URL direuta, polo que seique tean tovía llistaos equí, magar que tean n'usu activu.",
'unusedcategoriestext' => "Les siguientes categoríes esisten magar que nengún artículu o categoría faiga usu d'elles.",
'notargettitle' => 'Nun hai oxetivu',
'notargettext' => 'Nun especificasti una páxina oxetivu o un usuariu sobre los que realizar esta función.',
'nopagetitle' => 'Nun esiste la páxina oxetivu',
'nopagetext' => "La páxina oxetivu qu'especificasti nun esiste.",
'pager-newer-n' => '{{PLURAL:$1|1 siguiente|$1 siguientes}}',
'pager-older-n' => '{{PLURAL:$1|1 anterior|$1 anteriores}}',
'suppress' => 'Güeyador',
'querypage-disabled' => 'Esta páxina especial ta desactivada por razones de rindimientu.',

# Book sources
'booksources' => 'Fontes de llibros',
'booksources-search-legend' => 'Busca de fontes de llibros',
'booksources-go' => 'Dir',
'booksources-text' => "Esta ye una llista d'enllaces a otros sitios que vienden llibros nuevos y usaos, y que puen tener más información sobre llibros que pueas tar guetando:",
'booksources-invalid-isbn' => 'El códigu ISBN que puxisti nun paez que valga; mira que te vien copiáu de la fonte orixinal.',

# Special:Log
'specialloguserlabel' => 'Pol usuariu:',
'speciallogtitlelabel' => 'Col títulu:',
'log' => 'Rexistros',
'all-logs-page' => 'Tolos rexistros públicos',
'alllogstext' => "Visualización combinada de tolos rexistros disponibles de {{SITENAME}}.
Pues filtrar la visualización seleicionando una mena de rexistru, el nome d'usuariu (teniendo en cuenta les mayúscules y minúscules) o la páxina afectada (teniendo en cuenta tamién les mayúscules y minúscules).",
'logempty' => 'Nun hai coincidencies nel rexistru.',
'log-title-wildcard' => "Buscar títulos qu'emprimen con esti testu",
'showhideselectedlogentries' => 'Amosar/anubrir les entraes del rexistru seleicionaes',

# Special:AllPages
'allpages' => 'Toles páxines',
'alphaindexline' => '$1 a $2',
'nextpage' => 'Páxina siguiente ($1)',
'prevpage' => 'Páxina anterior ($1)',
'allpagesfrom' => "Amosar páxines qu'entamen por:",
'allpagesto' => 'Amosar páxines que finen por:',
'allarticles' => 'Toles páxines',
'allinnamespace' => 'Toles páxines (espaciu de nomes $1)',
'allnotinnamespace' => 'Toles páxines (sacantes les del espaciu de nomes $1)',
'allpagesprev' => 'Anteriores',
'allpagesnext' => 'Siguientes',
'allpagessubmit' => 'Dir',
'allpagesprefix' => 'Amosar páxines col prefixu:',
'allpagesbadtitle' => "El títulu dau a esta páxina nun yera válidu o tenía un prefixu d'enllaz interllingua o interwiki. Pue contener ún o más carauteres que nun se puen usar nos títulos.",
'allpages-bad-ns' => '{{SITENAME}} nun tien l\'espaciu de nomes "$1".',
'allpages-hide-redirects' => 'Anubrir redireiciones',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => "Tas viendo una versión en caché d'esta páxina, que pue llegar a tener $1 d'antigüedá.",
'cachedspecial-viewing-cached-ts' => "Tas viendo una versión en caché d'esta páxina, que pue nun tar anovada dafechu.",
'cachedspecial-refresh-now' => 'Ver la más nueva.',

# Special:Categories
'categories' => 'Categoríes',
'categoriespagetext' => "{{PLURAL:$1|La categoría darréu contién|Les categoríes darréu contienen}} páxines o ficheros multimedia.
Les [[Special:UnusedCategories|categoríes non usaes]] nun s'amuesen equí.
Ver tamién les [[Special:WantedCategories|categoríes más buscaes]].",
'categoriesfrom' => "Amosar categoríes qu'emprimen por:",
'special-categories-sort-count' => 'ordenar por tamañu',
'special-categories-sort-abc' => 'ordenar alfabéticamente',

# Special:DeletedContributions
'deletedcontributions' => "Contribuciones d'usuariu esborraes",
'deletedcontributions-title' => "Contribuciones d'usuariu desaniciaes",
'sp-deletedcontributions-contribs' => 'collaboraciones',

# Special:LinkSearch
'linksearch' => "Gueta d'enllaces esternos",
'linksearch-pat' => 'Patrón de busca:',
'linksearch-ns' => 'Espaciu de nomes:',
'linksearch-ok' => 'Guetar',
'linksearch-text' => 'Se puen usar comodinos como "*.wikipedia.org".
Necesita polo menos un dominiu de primer nivel, como "*.org".<br />
Protocolos almitíos: <code>$1</code> (el predetermináu ye http:// si nun se conseña dengún protocolu).',
'linksearch-line' => '$1 enllaciáu dende $2',
'linksearch-error' => 'Los comodinos namái puen apaecer al entamu del nome del güéspede.',

# Special:ListUsers
'listusersfrom' => 'Amosar usuarios emprimando dende:',
'listusers-submit' => 'Amosar',
'listusers-noresult' => "Nun s'atoparon usuarios.",
'listusers-blocked' => '(bloquiau)',

# Special:ActiveUsers
'activeusers' => "Llista d'usuarios activos",
'activeusers-intro' => "Esta ye una llista d'usuarios que tuvieron alguna mena d'actividá hai menos de $1 {{PLURAL:$1|día|díes}}.",
'activeusers-count' => '$1 {{PLURAL:$1|edición|ediciones}} {{PLURAL:$3|nel caberu día|nos caberos $3 díes}}',
'activeusers-from' => 'Amosar usuarios principiando dende:',
'activeusers-hidebots' => 'Anubrir bots',
'activeusers-hidesysops' => 'Anubrir alministradores',
'activeusers-noresult' => "Nun s'alcontraron usuarios.",

# Special:Log/newusers
'newuserlogpage' => "Rexistru de creación d'usuarios",
'newuserlogpagetext' => "Esti ye un rexistru de creación d'usuarios.",

# Special:ListGroupRights
'listgrouprights' => "Drechos de los grupos d'usuariu",
'listgrouprights-summary' => "La siguiente ye una llista de grupos d'usuariu definíos nesta wiki, colos sos drechos d'accesu asociaos.
Pue haber [[{{MediaWiki:Listgrouprights-helppage}}|información adicional]] tocante a drechos individuales.",
'listgrouprights-key' => '* <span class="listgrouprights-granted">Permisu concedíu</span>
* <span class="listgrouprights-revoked">Permisu retiráu</span>',
'listgrouprights-group' => 'Grupu',
'listgrouprights-rights' => 'Drechos',
'listgrouprights-helppage' => 'Help:Drechos de grupu',
'listgrouprights-members' => '(llista de miembros)',
'listgrouprights-addgroup' => 'Pue añader {{PLURAL:$2|grupu|grupos}}: $1',
'listgrouprights-removegroup' => 'Pue quitar {{PLURAL:$2|grupu|grupos}}: $1',
'listgrouprights-addgroup-all' => 'Pue añader tolos grupos',
'listgrouprights-removegroup-all' => 'Pue quitar tolos grupos',
'listgrouprights-addgroup-self' => 'Aamestar {{PLURAL:$2|grupu|grupos}} a la cuenta propia: $1',
'listgrouprights-removegroup-self' => 'Desaniciar {{PLURAL:$2|grupu|grupos}} de la cuenta propia: $1',
'listgrouprights-addgroup-self-all' => 'Amestar tolos grupos a la cuenta propia',
'listgrouprights-removegroup-self-all' => 'Desaniciar tolos grupos de la cuenta propia',

# E-mail user
'mailnologin' => "Ensin direición d'unviu",
'mailnologintext' => 'Has tar [[Special:UserLogin|identificáu]]
y tener una direición de corréu válida nes tos [[Special:Preferences|preferencies]]
pa poder unviar correos a otros usuarios.',
'emailuser' => 'Manda-y un corréu a esti usuariu',
'emailuser-title-target' => 'Unviar un corréu electrónicu a {{GENDER:$1|esti usuariu|esta usuaria}}',
'emailuser-title-notarget' => 'Unviar un corréu electrónicu a un usuariu',
'emailpage' => 'Envigar un corréu electrónicu a un usuariu',
'emailpagetext' => 'Pues usar el formulariu de más abaxo pa unviar un corréu electrónicu a {{GENDER:$1|esti usuariu|esta usuaria}}.
La direición de corréu electrónicu qu\'especificasti nes [[Special:Preferences|tos preferencies d\'usuariu]] va apaecer como la direición "Dende" del corréu, pa que\'l que lo recibe seya quien a respondete direutamente a ti.',
'usermailererror' => "L'operador de corréu devolvió un error:",
'defemailsubject' => 'Corréu electrónicu del usuariu «$1» de {{SITENAME}}',
'usermaildisabled' => 'Corréu del usuariu desactiváu',
'usermaildisabledtext' => "Nun pues unviar corréu a otros usuarios d'esta wiki",
'noemailtitle' => 'Ensin direición de corréu',
'noemailtext' => 'Esti usuariu nun especificó una direición de corréu válida.',
'nowikiemailtitle' => "Nun se permite'l corréu electrónicu",
'nowikiemailtext' => "Esti usuariu nun quier recibir correos d'otros usuarios.",
'emailnotarget' => "Nome d'usuariu del destinatariu non esistente o inválidu.",
'emailtarget' => "Escribi'l nome d'usuariu del destinatariu",
'emailusername' => "Nome d'usuariu:",
'emailusernamesubmit' => 'Unviar',
'email-legend' => 'Unviar un corréu electrónicu a otru usuariu de {{SITENAME}}',
'emailfrom' => 'De:',
'emailto' => 'A:',
'emailsubject' => 'Asuntu:',
'emailmessage' => 'Mensaxe:',
'emailsend' => 'Unviar',
'emailccme' => 'Unviame per corréu una copia del mio mensaxe.',
'emailccsubject' => 'Copia del to mensaxe a $1: $2',
'emailsent' => 'Corréu unviáu',
'emailsenttext' => 'El to corréu foi unviáu.',
'emailuserfooter' => 'Esti corréu electrónicu unviolu $1 a $2 per aciu de la función "Manda-y un corréu a un usuariu" de {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Dexar un mensaxe del sistema.',
'usermessage-editor' => 'Mensaxería del sistema',

# Watchlist
'watchlist' => 'La mio llista de vixilancia',
'mywatchlist' => 'Llista de vixilancia',
'watchlistfor2' => 'Pa $1 $2',
'nowatchlist' => 'La to llista de vixilancia ta vacia.',
'watchlistanontext' => 'Por favor $1 pa ver o editar entraes na to llista de vixilancia.',
'watchnologin' => 'Non identificáu',
'watchnologintext' => 'Tienes que tar [[Special:UserLogin|identificáu]] pa poder camudar la to llista de vixilancia.',
'addwatch' => 'Amestar a la llista de vixilancia',
'addedwatchtext' => 'La páxina "[[:$1]]" s\'amestó a la to [[Special:Watchlist|llista de vixilancia]].
Los futuro cambeos nesta páxina y na so páxina d\'alderique asociada apaecerán allí.',
'removewatch' => 'Desaniciar de la llista de vixilancia',
'removedwatchtext' => 'Desapuntóse la páxina "[[:$1]]" de la [[Special:Watchlist|to llista de vixilancia]].',
'watch' => 'Vixilar',
'watchthispage' => 'Vixilar esta páxina',
'unwatch' => 'Dexar de vixilar',
'unwatchthispage' => 'Dexar de vixilar',
'notanarticle' => 'Nun ye un artículu',
'notvisiblerev' => 'Esborróse la revisión',
'watchnochange' => 'Nenguna de les tos páxines vixilaes foi editada nel periodu escoyíu.',
'watchlist-details' => "{{PLURAL:$1|$1 páxina|$1 páxines}} na to llista de vixilancia ensin cuntar les páxines d'alderique.",
'wlheader-enotif' => '* La notificación per corréu electrónicu ta activada.',
'wlheader-showupdated' => "* Les páxines camudaes dende la to última visita amuésense en '''negrina'''",
'watchmethod-recent' => 'comprobando páxines vixilaes nos cambios recientes',
'watchmethod-list' => 'comprobando ediciones recientes nes páxines vixilaes',
'watchlistcontains' => 'La to llista de vixilancia tien $1 {{PLURAL:$1|páxina|páxines}}.',
'iteminvalidname' => "Problema col elementu '$1', nome non válidu...",
'wlnote' => "Abaxo {{PLURAL:$1|ta'l caberu cambiu|tán los caberos '''$1''' cambios}} {{PLURAL:$2|na cabera hora|nes caberes '''$2''' hores}}, el día $3 a les $4.",
'wlshowlast' => 'Amosar les últimes $1 hores $2 díes $3',
'watchlist-options' => 'Opciones de la llista de vixilancia',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Vixilando...',
'unwatching' => 'Dexando de vixilar...',
'watcherrortext' => 'Hebo un fallu al camudar la configuración de la to llista de vixilancia pa «$1».',

'enotif_mailer' => 'Notificación de corréu de {{SITENAME}}',
'enotif_reset' => 'Marcar toles páxines visitaes',
'enotif_newpagetext' => 'Esta ye una páxina nueva.',
'enotif_impersonal_salutation' => 'Usuariu de {{SITENAME}}',
'changed' => 'camudada',
'created' => 'creada',
'enotif_subject' => 'La páxina de {{SITENAME}} $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited' => 'Mira en $1 pa ver tolos cambios dende la cabera visita.',
'enotif_lastdiff' => 'Mira en $1 pa ver esti cambéu.',
'enotif_anon_editor' => 'usuariu anónimu $1',
'enotif_body' => 'Estimáu $WATCHINGUSERNAME,


La páxina de {{SITENAME}} $PAGETITLE foi $CHANGEDORCREATED el $PAGEEDITDATE por $PAGEEDITOR, mira $PAGETITLE_URL pa ver la versión actual.

$NEWPAGE

Resume del editor: $PAGESUMMARY $PAGEMINOREDIT

Ponte\'n contautu col editor:
corréu: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

En casu de producise más cambios, nun habrá más notificaciones a nun ser que visites esta páxina. Tamién podríes reaniciar na to llista de vixilancia los marcadores de notificación de toles páxines que tengas vixilaes.

El to abertable sistema de notificación de {{SITENAME}}

--
Pa camudar les preferencies d\'avisos per corréu, visita
{{canonicalurl:{{#special:Preferences}}}}

Pa camudar la configuración de la to llista de vixilancia, visita
{{canonicalurl:{{#special:EditWatchlist}}}}

Pa desaniciar la páxina de la to llista de vixilancia, visita
$UNWATCHURL

Más ayuda y sofitu:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Esborrar páxina',
'confirm' => 'Confirmar',
'excontent' => "el conteníu yera: '$1'",
'excontentauthor' => "el conteníu yera: '$1' (y l'únicu autor yera '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "el conteníu enantes de dexar en blanco yera: '$1'",
'exblank' => 'la páxina taba vacia',
'delete-confirm' => 'Desaniciar «$1»',
'delete-legend' => 'Desaniciar',
'historywarning' => "'''Avisu:'''' La páxina que vas desaniciar tien un historial con aproximadamente $1 {{PLURAL:$1|revisión|revisiones}}:",
'confirmdeletetext' => "Tas a piques d'esborrar una páxina xunto con tol so historial.
Por favor confirma que ye lo que quies facer, qu'entiendes les consecuencies, y que lo tas faciendo acordies coles [[{{MediaWiki:Policy-url}}|polítiques]].",
'actioncomplete' => 'Aición completada',
'actionfailed' => "Falló l'aición",
'deletedtext' => 'Esborróse "$1".
Mira en $2 la llista de les últimes páxines esborraes.',
'dellogpage' => 'Rexistru de desanicios',
'dellogpagetext' => 'Abaxo amuésase una llista de los artículos esborraos más recién.',
'deletionlog' => 'rexistru de desanicios',
'reverted' => 'Revertida a una revisión anterior',
'deletecomment' => 'Motivu:',
'deleteotherreason' => 'Motivu distintu/adicional:',
'deletereasonotherlist' => 'Otru motivu',
'deletereason-dropdown' => "*Motivos comunes d'esborráu
** A pidimientu del autor
** Violación de Copyright
** Vandalismu",
'delete-edit-reasonlist' => "Editar los motivos d'esborráu",
'delete-toobig' => "Esta páxina tien un historial d'ediciones grande, más de $1 {{PLURAL:$1|revisión|revisiones}}.
Restrinxóse l'esborráu d'estes páxines pa evitar perturbaciones accidentales de {{SITENAME}}.",
'delete-warning-toobig' => "Esta páxina tien un historial d'ediciones grande, más de $1 {{PLURAL:$1|revisión|revisiones}}.
Esborralu pue perturbar les operaciones de la base de datos de {{SITENAME}};
obra con precaución.",

# Rollback
'rollback' => 'Revertir ediciones',
'rollback_short' => 'Revertir',
'rollbacklink' => 'revertir',
'rollbacklinkcount' => 'revertir $1 {{PLURAL:$1|edición|ediciones}}',
'rollbacklinkcount-morethan' => 'revertir más de $1 {{PLURAL:$1|edición|ediciones}}',
'rollbackfailed' => 'Falló la reversión',
'cantrollback' => "Nun se pue revertir la edición; el postrer collaborador ye l'únicu autor d'esta páxina.",
'alreadyrolled' => 'Nun se pue revertir la postrer edición de [[:$1]] fecha por [[User:$2|$2]] ([[User talk:$2|alderique]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
daquién más yá editó o revirtió la páxina.

La postrer edición foi fecha por [[User:$3|$3]] ([[User talk:$3|alderique]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "El resume de la edición yera: \"''\$1''\".",
'revertpage' => 'Revertíes les ediciones de [[Special:Contributions/$2|$2]] ([[User talk:$2|alderique]]) hasta la cabera versión de [[User:$1|$1]]',
'revertpage-nouser' => "Revertíes les ediciones de (nome d'usuariu desaniciáu) a la cabera revisión de [[User:$1|$1]]",
'rollback-success' => 'Revertíes les ediciones de $1; camudáu a la última versión de $2.',

# Edit tokens
'sessionfailure-title' => 'Fallu de sesión',
'sessionfailure' => 'Paez qu\'hai un problema cola to sesión; por precaución
cancelóse l\'aición que pidisti. Da-y al botón "Atrás" del
navegador pa cargar otra vuelta la páxina y vuelve a intentalo.',

# Protect
'protectlogpage' => 'Rexistru de proteiciones',
'protectlogtext' => 'Darréu ta un rexistru de les protecciones de páxines.
Consulta la [[Special:ProtectedPages|llista de páxines protexíes]] pa ver les proteiciones actives nestos momentos.',
'protectedarticle' => 'protexó «[[$1]]»',
'modifiedarticleprotection' => 'camudó\'l nivel de proteición de "[[$1]]"',
'unprotectedarticle' => 'quitó-y la protección a "[[$1]]"',
'movedarticleprotection' => 'treslladó los parámetros de proteición dende "[[$2]]" a "[[$1]]"',
'protect-title' => 'Protexendo "$1"',
'protect-title-notallowed' => 'Ver el nivel de proteición de "$1"',
'prot_1movedto2' => '[[$1]] treslladáu a [[$2]]',
'protect-badnamespace-title' => 'Espaciu de nomes non protexible',
'protect-badnamespace-text' => "Les páxines d'esti espaciu de nomes nun se puen protexer.",
'protect-legend' => 'Confirmar proteición',
'protectcomment' => 'Motivu:',
'protectexpiry' => 'Caduca:',
'protect_expiry_invalid' => 'Caducidá non válida.',
'protect_expiry_old' => 'La fecha de caducidá ta pasada.',
'protect-unchain-permissions' => 'Desbloquiar les demás opciones de protección',
'protect-text' => "Equí pues ver y camudar el nivel de proteición de la páxina '''$1'''.",
'protect-locked-blocked' => "Nun pues camudar los niveles de proteición mentes teas bloquiáu. Esta
ye la configuración actual de la páxina '''$1''':",
'protect-locked-dblock' => "Los niveles de proteición nun puen ser camudaos pol mor d'un candáu activu de
la base de datos. Esta ye la configuración actual de la páxina '''$1''':",
'protect-locked-access' => "La to cuenta nun tien permisu pa camudar los niveles de proteición de páxina.
Esta ye la configuración actual pa la páxina '''$1''':",
'protect-cascadeon' => "Esta páxina ta protexida nestos momentos porque ta inxerida {{PLURAL:$1|na siguiente páxina, que tien|nes siguientes páxines, que tienen}} activada la proteición en cascada. Pues camudar el nivel de proteición d'esta páxina, pero nun va afeutar a la proteición en cascada.",
'protect-default' => 'Permitir tolos usuarios',
'protect-fallback' => 'Permitir namái usuarios con permisu "$1"',
'protect-level-autoconfirmed' => 'Permitir namái usuarios autoconfirmaos',
'protect-level-sysop' => 'Permitir namái alministradores',
'protect-summary-cascade' => 'en cascada',
'protect-expiring' => "caduca'l $1 (UTC)",
'protect-expiring-local' => 'caduca el $1',
'protect-expiry-indefinite' => 'indefiníu',
'protect-cascade' => 'Páxines protexíes inxeríes nesta páxina (proteición en cascada)',
'protect-cantedit' => "Nun pues camudar los niveles de proteición d'esta páxina porque nun tienes permisu pa editala.",
'protect-othertime' => 'Otru periodu:',
'protect-othertime-op' => 'otru periodu',
'protect-existing-expiry' => 'Caducidá actual: $2, $3',
'protect-otherreason' => 'Motivu distintu/adicional:',
'protect-otherreason-op' => 'Otru motivu',
'protect-dropdown' => "*Motivos comunes de proteición
** Vandalismu escomanáu
** Spamming escesivu
** Guerra d'ediciones contraproducente
** Páxina de tráficu altu",
'protect-edit-reasonlist' => 'Editar los motivos de proteición',
'protect-expiry-options' => '1 hora:1 hour,1 día:1 day,1 selmana:1 week,2 selmanes:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 añu:1 year,pa siempre:infinite',
'restriction-type' => 'Permisu:',
'restriction-level' => 'Nivel de restricción:',
'minimum-size' => 'Tamañu mínimu',
'maximum-size' => 'Tamañu máximu:',
'pagesize' => '(bytes)',

# Restrictions (nouns)
'restriction-edit' => 'Editar',
'restriction-move' => 'Treslladar',
'restriction-create' => 'Crear',
'restriction-upload' => 'Xubir',

# Restriction levels
'restriction-level-sysop' => 'totalmente protexida',
'restriction-level-autoconfirmed' => 'semiprotexida',
'restriction-level-all' => 'cualesquier nivel',

# Undelete
'undelete' => 'Ver páxines esborraes',
'undeletepage' => 'Ver y restaurar páxines esborraes',
'undeletepagetitle' => "'''Les siguientes son les revisiones esborraes de [[:$1]]'''.",
'viewdeletedpage' => 'Ver páxines esborraes',
'undeletepagetext' => "{{PLURAL:$1|La siguiente páxina foi esborrada pero tovía ta nel archivu y pue ser restauráu|Les $1 páxines siguientes foron esborraes pero tovía tán nel archivu y puen ser restauraes}}. L'archivu pue ser purgáu periódicamente.",
'undelete-fieldset-title' => 'Restaurar revisiones',
'undeleteextrahelp' => "Pa restaurar l'historial ensembre de la páxina, desmarca toles caxelles y calca en '''''{{int:undeletebtn}}'''''.
Pa realizar una restauración selectiva, seleiciona les caxelles de la revisión que quies restaurar y calca en '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|revisión archivada|revisiones archivaes}}',
'undeletehistory' => 'Si restaures la páxina, restauraránse toles revisiones al historial.
Si se creó una páxina col mesmu nome dende que fuera esborrada, les revisiones restauraes van apaecer nel historial anterior.',
'undeleterevdel' => 'Nun se fadrá la restauración si ésta provoca un esborráu parcial de la páxina cimera o de la revisión
del archivu. Nestos casos, tienes que desmarcar o amosar les revisiones esborraes más recién.',
'undeletehistorynoadmin' => "Esta páxina foi esborrada. El motivu del esborráu amuésase
nel resume d'embaxo, amás de detalles de los usuarios qu'editaron esta páxina enantes
de ser esborrada. El testu actual d'estes revisiones esborraes ta disponible namái pa los alministradores.",
'undelete-revision' => 'Revisión esborrada de $1 ($4, a les $5) fecha por $3:',
'undeleterevision-missing' => "Falta la revisión o nun ye válida. Sieque l'enllaz nun seya correutu, o que la
revisión fuera restaurada o eliminada del archivu.",
'undelete-nodiff' => "Nun s'atopó revisión previa.",
'undeletebtn' => 'Restaurar',
'undeletelink' => 'ver/restaurar',
'undeleteviewlink' => 'ver',
'undeletereset' => 'Reaniciar',
'undeleteinvert' => 'Invertir seleición',
'undeletecomment' => 'Motivu:',
'undeletedrevisions' => '{{PLURAL:$1|1 revisión restaurada|$1 revisiones restauraes}}',
'undeletedrevisions-files' => '{{PLURAL:$1|1 revisión|$1 revisiones}} y {{PLURAL:$2|1 archivu|$2 archivos}} restauraos',
'undeletedfiles' => '{{PLURAL:$1|1 archivu restauráu|$1 archivos restauraos}}',
'cannotundelete' => 'Falló la restauración; seique daquién yá restaurara la páxina enantes.',
'undeletedpage' => "'''Restauróse $1'''

Consulta'l [[Special:Log/delete|rexistru d'esborraos]] pa ver los esborraos y restauraciones de recién.",
'undelete-header' => "Mira nel [[Special:Log/delete|rexistru d'esborraos]] les páxines esborraes recién.",
'undelete-search-title' => 'Buscar páxines desaniciaes',
'undelete-search-box' => 'Buscar páxines desaniciaes',
'undelete-search-prefix' => "Amosar páxines qu'empecipien por:",
'undelete-search-submit' => 'Guetar',
'undelete-no-results' => "Nun s'atoparon páxines afechisques a la busca nel archivu d'esborraos.",
'undelete-filename-mismatch' => "Nun se pue restaurar la revisión del archivu con fecha $1: el nome d'archivu nun concuaya",
'undelete-bad-store-key' => "Nun se pue restaurar la revisión del archivu con fecha $1: yá nun esistía l'archivu nel momentu d'esborralu.",
'undelete-cleanup-error' => 'Error al esborrar l\'archivu non usáu "$1".',
'undelete-missing-filearchive' => "Nun se pue restaurar l'archivu col númberu d'identificación $1 porque nun ta na base de datos. Seique yá fuera restauráu.",
'undelete-error' => 'Error al restaurar la páxina',
'undelete-error-short' => "Error al restaurar l'archivu: $1",
'undelete-error-long' => "Atopáronse errores al restaurar l'archivu:

$1",
'undelete-show-file-confirm' => '¿Tas seguru de que quies ver una versión desaniciada del ficheru "<nowiki>$1</nowiki>" del $2 a les $3?',
'undelete-show-file-submit' => 'Sí',

# Namespace form on various pages
'namespace' => 'Espaciu de nomes:',
'invert' => 'Invertir seleición',
'tooltip-invert' => "Marca esta caxella p'anubrir los cambios de les páxines pertenecientes al espaciu de nomes seleicionáu (y al espaciu de nomes asociáu si se marcó)",
'namespace_association' => 'Espaciu de nome asociáu',
'tooltip-namespace_association' => "Marca esta caxella p'amestar tamién l'espaciu de nombres d'alderique o asuntu asociaos col espaciu de nomes seleicionáu",
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Collaboraciones del usuariu',
'contributions-title' => "Contribuciones d'usuariu pa $1",
'mycontris' => 'Collaboraciones',
'contribsub2' => 'Pa $1 ($2)',
'nocontribs' => "Nun s'atoparon cambeos que coincidan con esi criteriu.",
'uctop' => '(actual)',
'month' => "Dende'l mes (y anteriores):",
'year' => "Dende l'añu (y anteriores):",

'sp-contributions-newbies' => 'Amosar namái les contribuciones de cuentes nueves',
'sp-contributions-newbies-sub' => 'Namái les cuentes nueves',
'sp-contributions-newbies-title' => "Contribuciones d'usuariu pa cuentes nueves",
'sp-contributions-blocklog' => 'rexistru de bloqueos',
'sp-contributions-deleted' => "Contribuciones d'usuariu desaniciaes",
'sp-contributions-uploads' => 'xubes',
'sp-contributions-logs' => 'rexistros',
'sp-contributions-talk' => 'alderique',
'sp-contributions-userrights' => "xestión de permisos d'usuariu",
'sp-contributions-blocked-notice' => "Esti usuariu anguaño ta bloquiáu.
La cabera entrada del rexistru de bloqueos s'ufre darréu pa referencia:",
'sp-contributions-blocked-notice-anon' => "Esta IP anguaño ta bloquiada.
La cabera entrada del rexistru de bloqueos s'ufre darréu pa referencia:",
'sp-contributions-search' => 'Buscar contribuciones',
'sp-contributions-username' => "Direición IP o nome d'usuariu:",
'sp-contributions-toponly' => 'Amosar namái les ediciones que son les caberes revisiones',
'sp-contributions-submit' => 'Guetar',

# What links here
'whatlinkshere' => "Lo qu'enllaza equí",
'whatlinkshere-title' => 'Páxines qu\'enllacien a "$1"',
'whatlinkshere-page' => 'Páxina:',
'linkshere' => "Les páxines siguientes enllacien a '''[[:$1]]''':",
'nolinkshere' => "Nenguna páxina enllaza a '''[[:$1]]'''.",
'nolinkshere-ns' => "Nenguna páxina enllaza a '''[[:$1]]''' nel espaciu de nome conseñáu.",
'isredirect' => 'páxina de redireición',
'istemplate' => 'tresclusión',
'isimage' => 'enllaz al ficheru',
'whatlinkshere-prev' => '{{PLURAL:$1|anterior|anteriores $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|siguiente|siguientes $1}}',
'whatlinkshere-links' => '← enllaces',
'whatlinkshere-hideredirs' => '$1 redireiciones',
'whatlinkshere-hidetrans' => '$1 tresclusiones',
'whatlinkshere-hidelinks' => '$1 enllaces',
'whatlinkshere-hideimages' => '$1 los enllaces al ficheru',
'whatlinkshere-filters' => 'Peñeres',

# Block/unblock
'autoblockid' => 'Autobloquiar #$1',
'block' => 'Bloquiar usuariu',
'unblock' => 'Desbloquiar usuariu',
'blockip' => 'Bloquiar usuariu',
'blockip-title' => 'Bloquiar usuariu',
'blockip-legend' => 'Bloquiar usuariu',
'blockiptext' => "Usa'l siguiente formulariu pa bloquiar el permisu d'escritura a una IP o a un usuariu concretu.
Esto debería facese sólo pa prevenir vandalismu como indiquen les [[{{MediaWiki:Policy-url}}|polítiques]]. Da un motivu específicu (como por exemplu citar páxines que fueron vandalizaes).",
'ipadressorusername' => "Direición IP o nome d'usuariu:",
'ipbexpiry' => 'Caducidá:',
'ipbreason' => 'Motivu:',
'ipbreasonotherlist' => 'Otru motivu',
'ipbreason-dropdown' => "*Motivos comunes de bloquéu
** Enxertamientu d'información falso
** Dexar les páxines en blanco
** Enllaces spam a páxines esternes
** Enxertamientu de babayaes/enguedeyos nes páxines
** Comportamientu intimidatoriu o d'acosu
** Abusu de cuentes múltiples
** Nome d'usuariu inaceutable",
'ipb-hardblock' => 'Torgar que los usuarios coneutaos editen dende esta direición IP',
'ipbcreateaccount' => 'Evitar creación de cuentes',
'ipbemailban' => "Torgar al usuariu l'unviu de corréu electrónicu",
'ipbenableautoblock' => "Bloquiar automáticamente la cabera direición IP usada por esti usuariu y toles IP posteriores dende les qu'intente editar",
'ipbsubmit' => 'Bloquiar esti usuariu',
'ipbother' => 'Otru periodu:',
'ipboptions' => '2 hores:2 hours,1 día:1 day,3 díes:3 days,1 selmana:1 week,2 selmanes:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 añu:1 year,indefiníu:infinite',
'ipbotheroption' => 'otru',
'ipbotherreason' => 'Motivu distintu/adicional:',
'ipbhidename' => "Anubrir el nome d'usuariu d'ediciones y llistes",
'ipbwatchuser' => "Vixilar les páxines d'usuariu y d'alderique d'esti usuariu",
'ipb-disableusertalk' => "Torgar qu'esti usuariu edite la so páxina d'alderique mentanto tea bloquiáu",
'ipb-change-block' => "Volver a bloquiar l'usuariu con estos parámetros",
'ipb-confirm' => 'Confirmar bloquéu',
'badipaddress' => 'IP non válida',
'blockipsuccesssub' => 'Bloquéu fechu correctamente',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] ta {{GENDER:$1|bloquiáu|bloquiada}}.<br />
Mira na [[Special:BlockList|llista de bloqueos]] pa revisar los bloqueos.',
'ipb-blockingself' => '¡Tas a piques de bloquiate tú mesmu! ¿Tas seguru de que quies facer eso?',
'ipb-confirmhideuser' => 'Tas a piques de bloquiar un usuariu con "anubrir usuariu" activao. Esto desaniciará el nome del usuariu de tolos llistaos y entraes de los rexistros. ¿De xuro quies facer eso?',
'ipb-edit-dropdown' => 'Editar motivos de bloquéu',
'ipb-unblock-addr' => 'Desbloquiar $1',
'ipb-unblock' => "Desbloquiar un nome d'usuariu o direición IP",
'ipb-blocklist' => 'Ver los bloqueos esistentes',
'ipb-blocklist-contribs' => 'Contribuciones de $1',
'unblockip' => 'Desbloquiar usuariu',
'unblockiptext' => "Usa'l formulariu d'abaxo pa restablecer l'accesu d'escritura a una direicion IP o a un nome d'usuariu previamente bloquiáu.",
'ipusubmit' => 'Desaniciar esti bloquéu',
'unblocked' => '[[User:$1|$1]] foi desbloquiáu',
'unblocked-range' => '$1 se desbloquió',
'unblocked-id' => 'El bloquéu $1 foi elimináu',
'blocklist' => 'Usuarios bloquiaos',
'ipblocklist' => 'Usuarios bloquiaos',
'ipblocklist-legend' => 'Atopar un usuariu bloquiáu',
'blocklist-userblocks' => 'Anubrir los bloqueos de cuenta',
'blocklist-tempblocks' => 'Anubrir los bloqueos temporales',
'blocklist-addressblocks' => "Anubrir los bloqueos d'IP simples",
'blocklist-rangeblocks' => 'Anubrir los bloqueos de rangu',
'blocklist-timestamp' => "Marca d'hora",
'blocklist-target' => 'Oxetivu',
'blocklist-expiry' => 'Caduca',
'blocklist-by' => 'Alministración de bloqueos',
'blocklist-params' => 'Parámetros de bloquéu',
'blocklist-reason' => 'Motivu',
'ipblocklist-submit' => 'Guetar',
'ipblocklist-localblock' => 'Bloquéu llocal',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Otru bloquéu|Otros bloqueos}}',
'infiniteblock' => 'pa siempre',
'expiringblock' => "caduca'l $1 a les $2",
'anononlyblock' => 'namái anón.',
'noautoblockblock' => 'bloquéu automáticu desactiváu',
'createaccountblock' => 'bloquiada la creación de cuentes',
'emailblock' => 'corréu electrónicu bloquiáu',
'blocklist-nousertalk' => "nun pue editar la so páxina d'alderique",
'ipblocklist-empty' => 'La llista de bloqueos ta vacia.',
'ipblocklist-no-results' => "La direición IP o nome d'usuariu solicitáu nun ta bloquiáu.",
'blocklink' => 'bloquiar',
'unblocklink' => 'desbloquiar',
'change-blocklink' => 'camudar el bloquéu',
'contribslink' => 'contribuciones',
'emaillink' => 'unviar corréu',
'autoblocker' => 'Bloquiáu automáticamente porque la to direición IP foi usada recién por "[[User:$1|$1]]". El motivu del bloquéu de $1 ye: "$2"',
'blocklogpage' => 'Rexistru de bloqueos',
'blocklog-showlog' => "Esti usuariu recibió un bloquéu previamente.
El rexistru de bloqueos s'ufre darréu pa referencia:",
'blocklog-showsuppresslog' => "Esti usuariu recibió un bloquéu y s'anubrió previamente.
El rexistru de desanicios s'ufre darréu pa referencia:",
'blocklogentry' => 'bloquió [[$1]] con una caducidá de $2 $3',
'reblock-logentry' => 'camudó los parámetros de bloquéu de [[$1]] con una caducidá de $2 $3',
'blocklogtext' => "Esti ye un rexistru de los bloqueos y desbloqueos d'usuarios.
Les direcciones IP bloquiaes automáticamente nun salen equí.
Pa ver los bloqueos qu'hai agora mesmo, mira na [[Special:BlockList|llista de bloqueos]].",
'unblocklogentry' => 'desbloquió $1',
'block-log-flags-anononly' => 'namái usuarios anónimos',
'block-log-flags-nocreate' => 'creación de cuentes desactivada',
'block-log-flags-noautoblock' => 'bloquéu automáticu deshabilitáu',
'block-log-flags-noemail' => 'corréu electrónicu bloquiáu',
'block-log-flags-nousertalk' => "nun pue editar la páxina d'alderique propia",
'block-log-flags-angry-autoblock' => 'autobloquéu ameyoráu activáu',
'block-log-flags-hiddenname' => "nome d'usuariu anubríu",
'range_block_disabled' => "La capacidá d'alministrador pa crear bloqueos d'intervalos ta desactivada.",
'ipb_expiry_invalid' => 'Tiempu incorrectu.',
'ipb_expiry_temp' => "Los bloqueos de nome d'usuariu escondíos han ser permanentes.",
'ipb_hide_invalid' => 'Nun se pue desaniciar esta cuenta; seique tenga demasiaes ediciones.',
'ipb_already_blocked' => '"$1" yá ta bloquiáu',
'ipb-needreblock' => '$1 yá ta bloquiáu. ¿Quies camudar los parámetros?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Otru bloquéu|Otros bloqueos}}',
'unblock-hideuser' => "Nun pues desbloquiar esti usuariu, porque tien el nome d'usuariu anubríu.",
'ipb_cant_unblock' => "Error: Nun s'atopó'l bloquéu númberu $1. Seique yá fuera desbloquiáu.",
'ipb_blocked_as_range' => 'Error: La IP $1 nun ta bloquiada direutamente, polo que nun pue ser desloquiada. Sicasí, foi bloquiada como parte del intervalu $2, que pue ser desbloquiáu.',
'ip_range_invalid' => 'Rangu IP non válidu.',
'ip_range_toolarge' => 'Nun se permiten bloqueos mayores de /$1.',
'blockme' => 'Blóquiame',
'proxyblocker' => 'Bloquiador de proxys',
'proxyblocker-disabled' => 'Esta función ta desactivada.',
'proxyblockreason' => "La to direición IP foi bloquiada porque ye un proxy abiertu. Por favor contauta col to proveedor de serviciones d'Internet o col to servicio d'asistencia téunica y infórmalos d'esti seriu problema de seguridá.",
'proxyblocksuccess' => 'Fecho.',
'sorbsreason' => 'La to direición IP sal na llista de proxys abiertos en DNSBL usada por {{SITENAME}}.',
'sorbs_create_account_reason' => 'La to direición IP sal na llista de proxys abiertos en DNSBL usada por {{SITENAME}}. Nun pues crear una cuenta',
'cant-block-while-blocked' => 'Nun pues bloquiar a otros usuarios mentes tu teas bloquiáu.',
'cant-see-hidden-user' => "L'usuariu que tentes bloquiar yá ta bloquiáu y anubríu.
Como nun tienes permisos p'anubrir usuarios, nun pues ver o editar el bloquéu del usuariu.",
'ipbblocked' => 'Nun pues bloquiar o desbloquiar a otros usuarios, porque tas bloquiáu tu mesmu',
'ipbnounblockself' => 'Nun tienes permisu pa desbloquiate tu mesmu',

# Developer tools
'lockdb' => 'Protexer la base de datos',
'unlockdb' => 'Desprotexer la base de datos',
'lockdbtext' => 'Al bloquiar la base de datos suspenderáse la capacidá de tolos usuarios pa editar páxines, camudar les sos preferencies, editar les llistes de vixilancia y otres aiciones que requieran cambios na base de datos. Por favor confirma que ye lo que quies facer, y que vas desbloquiar la base de datos cuando fines col mantenimientu.',
'unlockdbtext' => 'Al desbloquiar la base de datos restauraráse la capacidá de tolos usuarios pa editar páxines, camudar les sos preferencies, editar les sos llistes de vixilancia y otres aiciones que requieren cambios na base de datos. Por favor confirma que ye lo quies facer.',
'lockconfirm' => 'Si, quiero candar daveres la base de datos.',
'unlockconfirm' => 'Sí, quiero descandar daveres la base de datos.',
'lockbtn' => 'Protexer la base de datos',
'unlockbtn' => 'Desprotexer la base de datos',
'locknoconfirm' => 'Nun activasti la caxa de confirmación.',
'lockdbsuccesssub' => 'Candáu de la base de datos efeutuáu correutamente',
'unlockdbsuccesssub' => 'Candáu de la base de datos elimináu',
'lockdbsuccesstext' => "Candóse la base de datos.
<br />Alcuérdate de [[Special:UnlockDB|descandala]] depués d'acabar el so mantenimientu.",
'unlockdbsuccesstext' => 'La base de datos foi descandada.',
'lockfilenotwritable' => "L'archivu de candáu de la base de datos nun ye escribible. Pa candar o descandar la base de datos esti tien que poder ser modificáu pol sirvidor.",
'databasenotlocked' => 'La base de datos nun ta candada.',
'lockedbyandtime' => '(por $1 el $2 a les $3)',

# Move page
'move-page' => 'Treslladar $1',
'move-page-legend' => 'Treslladar páxina',
'movepagetext' => "Usando'l siguiente formulariu vas renomar una páxina, treslladando'l so historial al nuevu nome.
El nome vieyu va convertise nuna páxina de redireición al títulu nuevu.
Pues actualizar les redireiciones qu'enllacien al títulu orixinal automáticamente.
Si prefieres nun lo facer, asegúrate de que nun dexes [[Special:DoubleRedirects|redireiciones dobles]] o [[Special:BrokenRedirects|rotes]].
Tu yes el responsable de facer que los enllaces queden apuntando au se supón que tienen d'apuntar.

Recuerda que la páxina '''nun''' va movese si yá hai una páxina col nuevu títulu, a nun ser que seya una redireición y nun tenga historial.
Esto significa que pues volver a renomar una páxina col nome orixinal si t'enquivoques, y nun pues sobreescribir una páxina yá esistente.

¡AVISU!'''
Esti pue ser un cambéu importante y inesperáu pa una páxina popular;
por favor, asegúrate d'entender les consecuencies de lo que vas facer enantes de siguir.",
'movepagetext-noredirectfixer' => "Usando'l siguiente formulariu vas renomar una páxina, treslladando'l so historial al nuevu nome.
El nome vieyu va convertise nuna redireición al nuevu.
Asegúrate de que nun dexes [[Special:DoubleRedirects|redireiciones dobles]] o [[Special:BrokenRedirects|rotes]].
Tu yes el responsable de facer que los enllaces queden apuntando au se supón qu'han apuntar.

Recuerda que la páxina '''nun''' va movese si yá hai una páxina col nuevu títulu, a nun ser que tea balera o seya una redireición que nun tenga historial.
Esto significa que pues volver a renomar una páxina col nome orixinal si t'enquivoques, y que nun pues sobreescribir una páxina yá esistente.

¡AVISU!'''
Esti pue ser un cambéu importante y inesperáu pa una páxina popular;
por favor, asegúrate d'entender les consecuencies de lo que vas facer enantes de siguir.",
'movepagetalktext' => "La páxina d'alderique asociada va ser treslladada automáticamente '''a nun ser que:'''
*Yá esista una páxina d'alderique non vacia col nuevu nome, o
*Desactives la caxella d'equí baxo.

Nestos casos vas tener que treslladar o fusionar la páxina manualmente.",
'movearticle' => 'Treslladar la páxina:',
'moveuserpage-warning' => "'''Atención:''' Tas a piques de mover una páxina d'usuariu. Atalanta que namái se va mover la páxina y que ''nun'' se va renomar l'usuariu.",
'movenologin' => 'Non identificáu',
'movenologintext' => 'Tienes que ser un usuariu rexistráu y tar [[Special:UserLogin|identificáu]] pa treslladar una páxina.',
'movenotallowed' => 'Nun tienes permisu pa mover páxines.',
'movenotallowedfile' => 'Nun tienes permisu pa mover ficheros.',
'cant-move-user-page' => "Nun tienes permisu pa treslladar páxines d'usuariu (independientemente de les subpáxines).",
'cant-move-to-user-page' => "Nun tienes permisu pa treslladar una páxina a una páxina d'usuariu (sacante a una subpáxina d'usuariu).",
'newtitle' => 'Al títulu nuevu:',
'move-watch' => 'Vixilar esta páxina',
'movepagebtn' => 'Treslladar la páxina',
'pagemovedsub' => 'Treslláu correctu',
'movepage-moved' => '\'\'\'"$1" treslladóse a "$2"\'\'\'',
'movepage-moved-redirect' => 'Creóse una redireición.',
'movepage-moved-noredirect' => "Desaniciose la creación d'una redireición.",
'articleexists' => "Yá hai una páxina con esi nome, o'l nome qu'escoyisti nun ye válidu. Por favor, escueyi otru nome.",
'cantmove-titleprotected' => "Nun pues mover una páxina a esti llugar porque'l nuevu títulu foi protexíu de la so creación",
'talkexists' => "'''La páxina treslladóse correutamente, pero non la so páxina d'alderique porque yá esiste una col títulu nuevu. Por favor, fusiónala manualmente.'''",
'movedto' => 'treslladáu a',
'movetalk' => "Mover la páxina d'alderique asociada",
'move-subpages' => 'Treslladar les subpáxines (hasta $1)',
'move-talk-subpages' => "Treslladar les subpáxines de la páxina d'alderique (hasta $1)",
'movepage-page-exists' => 'La páxina $1 yá esiste y nun se pue sobreescribir automáticamente.',
'movepage-page-moved' => 'Treslladóse la páxina $1 a $2.',
'movepage-page-unmoved' => 'Nun se pudo treslladar la páxina $1 a $2.',
'movepage-max-pages' => "Treslladóse'l máximu de $1 {{PLURAL:$1|páxina|páxinees}} y nun van treslladase más automáticamente.",
'movelogpage' => 'Rexistru de tresllaos',
'movelogpagetext' => 'Esta ye la llista de páxines treslladaes.',
'movesubpage' => '{{PLURAL:$1|Subpáxina|Subpáxines}}',
'movesubpagetext' => "Esta páxina tien $1 {{PLURAL:$1|subpáxina|subpáxines}} que s'amuesen darréu.",
'movenosubpage' => 'Esta páxina nun tien subpáxines.',
'movereason' => 'Motivu:',
'revertmove' => 'revertir',
'delete_and_move' => 'Esborrar y treslladar',
'delete_and_move_text' => '==Necesítase esborrar==

La páxina de destín "[[:$1]]" yá esiste. ¿Quies esborrala pa dexar sitiu pal treslláu?',
'delete_and_move_confirm' => 'Sí, esborrar la páxina',
'delete_and_move_reason' => 'Desaniciada pa facer sitiu pa treslladar dende «[[$1]]»',
'selfmove' => "Los nomes d'orixe y destín son los mesmos, nun se pue treslladar una páxina sobre ella mesma.",
'immobile-source-namespace' => 'Nun se puen treslladar páxines nel espaciu de nomes "$1"',
'immobile-target-namespace' => 'Nun se puen treslladar páxines al espaciu de nomes "$1"',
'immobile-target-namespace-iw' => "Nun puedes mover una páxina a un enllaz d'Interwiki.",
'immobile-source-page' => 'Esta páxina nun ye treslladable.',
'immobile-target-page' => 'Nun se pue treslladar a esi títulu de destín.',
'imagenocrossnamespace' => "Nun se pue treslladar una imaxe a nun espaciu de nomes que nun ye d'imáxenes",
'nonfile-cannot-move-to-file' => 'Nun se pue treslladar más que ficheros al espaciu de nomes de ficheros',
'imagetypemismatch' => 'La estensión nueva del archivu nun concueya cola so mena',
'imageinvalidfilename' => 'El nome del archivu oxetivu nun ye válidu',
'fix-double-redirects' => 'Actualizar cualesquier redireición que señale al títulu orixinal',
'move-leave-redirect' => 'Dexar una redireición detrás',
'protectedpagemovewarning' => "'''Avisu: Esta páxina ta candada pa que sólo los alministradores puedan treslladala.'''
La cabera entrada del rexistru s'ufre darréu pa referencia:",
'semiprotectedpagemovewarning' => "'''Nota:''' Esta páxina ta candada pa que namái los usuarios rexistraos puedan treslladala.
La cabera entrada del rexistru s'ufre darréu pa referencia:",
'move-over-sharedrepo' => '== Ficheru esistente ==
[[:$1]] esiste nun repositoriu compartíu. Si mueves un ficheru a esti títulu se saltará el ficheru compartíu.',
'file-exists-sharedrepo' => "El nome de ficheru qu'escoyisti yá ta n'usu nun repositoriu compartíu.
Escueyi otru nome, por favor.",

# Export
'export' => 'Esportar páxines',
'exporttext' => "Pues esportar el testu y l'historial d'ediciones d'una páxina en particular o d'una
riestra páxines endolcaes nun documentu XML. Esti se pue importar depués n'otra wiki
qu'use MediaWiki al traviés de la páxina [[Special:Import|importar]].

Pa esportar páxines, pon los títulos na caxa de testu d'embaxo, un títulu por llinia,
y seleiciona si quies la versión actual xunto con toles versiones antigües, xunto col
so historial, o namái la versión actual cola información de la postrer edición.

Por último, tamién pues usar un enllaz: p.e. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pa la páxina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportall' => 'Esportar toles páxines',
'exportcuronly' => 'Amestar namái la revisión actual, non tol historial',
'exportnohistory' => "----
'''Nota:''' Desactivóse la esportación del historial completu de páxines al traviés d'esti formulariu por motivos de rendimientu.",
'exportlistauthors' => 'Incluir una llista completa de collaboradores pa cada páxina',
'export-submit' => 'Esportar',
'export-addcattext' => 'Añader páxines dende la categoría:',
'export-addcat' => 'Amestar',
'export-addnstext' => 'Amestar páxines del espaciu de nomes:',
'export-addns' => 'Amestar',
'export-download' => 'Guardar como archivu',
'export-templates' => 'Inxerir plantíes',
'export-pagelinks' => 'Incluyir páxines enllazaes fasta una profundidá de:',

# Namespace 8 related
'allmessages' => 'Tolos mensaxes del sistema',
'allmessagesname' => 'Nome',
'allmessagesdefault' => 'Testu predetermináu',
'allmessagescurrent' => 'Testu actual',
'allmessagestext' => 'Esta ye una llista de los mensaxes de sistema disponibles nel espaciu de nomes de MediaWiki.
Por favor visita [//www.mediawiki.org/wiki/Localisation Llocalización de MediaWiki] y [//translatewiki.net translatewiki.net] si quies contribuyer a la llocalización xenérica de MediaWiki.',
'allmessagesnotsupportedDB' => "Nun pue usase '''{{ns:special}}:Allmessages''' porque '''\$wgUseDatabaseMessages''' ta deshabilitáu.",
'allmessages-filter-legend' => 'Peñerar',
'allmessages-filter' => 'Peñerar por estáu de personalización:',
'allmessages-filter-unmodified' => 'Ensin cambéos',
'allmessages-filter-all' => 'Toos',
'allmessages-filter-modified' => 'Camudaos',
'allmessages-prefix' => 'Peñerar pol prefixu:',
'allmessages-language' => 'Llingua:',
'allmessages-filter-submit' => 'Dir',

# Thumbnails
'thumbnail-more' => 'Agrandar',
'filemissing' => 'Falta archivu',
'thumbnail_error' => 'Error al crear la miniatura: $1',
'djvu_page_error' => 'Páxina DjVu fuera de llímites',
'djvu_no_xml' => 'Nun se pudo obtener el XML pal archivu DjVu',
'thumbnail-temp-create' => 'Nun se pudo crear el ficheru temporal de miniatura',
'thumbnail-dest-create' => 'Nun se pudo guardar la miniatura nel destín',
'thumbnail_invalid_params' => 'Parámetros de miniatura non válidos',
'thumbnail_dest_directory' => 'Nun se pue crear el direutoriu de destín',
'thumbnail_image-type' => "Triba d'imaxe ensin sofitu",
'thumbnail_gd-library' => 'Configuración incompleta de la biblioteca GD: falta la función $1',
'thumbnail_image-missing' => "Paez que falta'l ficheru: $1",

# Special:Import
'import' => 'Importar páxines',
'importinterwiki' => 'Importación treswiki',
'import-interwiki-text' => "Seleiciona una wiki y un títulu de páxina pa importar.
Les feches de revisión y los nomes de los editores caltendránse.
Toles aiciones d'importación treswiki queden rexistraes nel [[Special:Log/import|rexistru d'importaciones]].",
'import-interwiki-source' => 'Códigu wiki/páxina:',
'import-interwiki-history' => "Copiar toles versiones d'historial d'esta páxina",
'import-interwiki-templates' => 'Incluyir toles plantíes',
'import-interwiki-submit' => 'Importar',
'import-interwiki-namespace' => 'Espaciu de nomes de destín:',
'import-interwiki-rootpage' => 'Páxina raíz de destín (opcional):',
'import-upload-filename' => 'Nome del ficheru:',
'import-comment' => 'Comentariu:',
'importtext' => "Por favor, esporta'l ficheru dende la wiki d'orixe usando la [[Special:Export|ferramienta d'esportación]].
Guárdalu nel ordenador y xúbilu equí.",
'importstart' => 'Importando les páxines...',
'import-revision-count' => '$1 {{PLURAL:$1|revisión|revisiones}}',
'importnopages' => 'Nun hai páxines pa importar.',
'imported-log-entries' => 'Importao $1 {{PLURAL:$1|entrada del rexistru|entraes del rexistru}}.',
'importfailed' => 'Falló la importación: $1',
'importunknownsource' => "Triba d'orixe d'importación desconocida",
'importcantopen' => "Nun se pudo abrir el ficheru d'importación",
'importbadinterwiki' => 'Enllaz interwiki incorreutu',
'importnotext' => 'Vaciu o ensin testu',
'importsuccess' => '¡Importación finalizada!',
'importhistoryconflict' => 'Existe un conflictu na revisión del historial (seique esta páxina fuera importada previamente)',
'importnosources' => "Nun se definió l'orixe de la importación treswiki y les xubíes direutes del historial tán deshabilitaes.",
'importnofile' => "Nun se xubió nengún archivu d'importación.",
'importuploaderrorsize' => "Falló la xubida del archivu d'importación. L'archivu ye más grande que'l tamañu permitíu de xubida.",
'importuploaderrorpartial' => "Falló la xubida del archivu d'importación. L'archivu xubióse solo parcialmente.",
'importuploaderrortemp' => "Falló la xubida del archivu d'importación. Falta una carpeta temporal.",
'import-parse-failure' => "Fallu nel análisis d'importación XML",
'import-noarticle' => '¡Nun hai páxina pa importar!',
'import-nonewrevisions' => 'Toles revisiones fueran importaes previamente.',
'xml-error-string' => '$1 na llinia $2, col $3 (byte $4): $5',
'import-upload' => 'Xubir datos XML',
'import-token-mismatch' => 'Perdiéronse los datos de la sesión. Intentalo otra vuelta.',
'import-invalid-interwiki' => "Nun se puede importar d'esi wiki.",
'import-error-edit' => "La páxina $1 nun s'importó porque nun tienes permisu pa editala.",
'import-error-create' => "La páxina $1 nun s'importó porque nun tienes permisu pa creala.",
'import-error-interwiki' => "La páxina «$1» nun s'importó porque'l so nome ta acutáu pa enllaces esternos (interwiki).",
'import-error-special' => "La páxina «$1» nun s'importó porque pertenez a un espaciu de nomes especial que nun permite les páxines.",
'import-error-invalid' => "La páxina «$1» nun s'importó porque tien un nome inválidu.",
'import-options-wrong' => '{{PLURAL:$2|Opción enquivocada|Opciones enquivocaes}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'La páxina raíz dada ye un títulu inválidu.',
'import-rootpage-nosubpage' => 'L\'espaciu de nomes "$1" de la páxina raíz nun permite subpáxines.',

# Import log
'importlogpage' => "Rexistru d'importaciones",
'importlogpagetext' => "Importaciones alministrativas de páxines con historial d'ediciones d'otres wikis.",
'import-logentry-upload' => "importada [[$1]] per aciu d'una xuba d'archivu",
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|revisión|revisiones}}',
'import-logentry-interwiki' => 'treswikificada $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisión|revisiones}} dende $2',

# JavaScriptTest
'javascripttest' => 'Prueba de JavaScript',
'javascripttest-disabled' => 'Esta función ta desactivada.',
'javascripttest-title' => 'Executando pruebes de $1',
'javascripttest-pagetext-noframework' => 'Esta páxina ta acutada pa executar pruebes de javascript.',
'javascripttest-pagetext-unknownframework' => "L'entornu de pruebes «$1» ye desconocíu.",
'javascripttest-pagetext-frameworks' => 'Escueyi un de los siguientes entornos de pruebes: $1',
'javascripttest-pagetext-skins' => 'Escueyi una apariencia pa executar les pruebes:',
'javascripttest-qunit-intro' => 'Ver la [$1 documentación de les pruebes] en mediawiki.org.',
'javascripttest-qunit-heading' => 'Conxuntu de pruebes JavaScript QUnit de MediaWiki',

# Tooltip help for the actions
'tooltip-pt-userpage' => "La to páxina d'usuariu",
'tooltip-pt-anonuserpage' => "La páxina d'usuariu de la IP cola que tas editando",
'tooltip-pt-mytalk' => "La to páxina d'alderique",
'tooltip-pt-anontalk' => 'Alderique de les ediciones feches con esta direición IP',
'tooltip-pt-preferences' => 'Les tos preferencies',
'tooltip-pt-watchlist' => 'Llista de les páxines nes que tas vixilando los cambios',
'tooltip-pt-mycontris' => 'Llista de les tos collaboraciones',
'tooltip-pt-login' => "T'encamentamos que t'identifiques, anque nun ye obligatorio",
'tooltip-pt-anonlogin' => "T'encamentamos que t'identifiques, anque nun ye obligatorio.",
'tooltip-pt-logout' => 'Colar',
'tooltip-ca-talk' => 'Alderique tocante al conteníu de la páxina',
'tooltip-ca-edit' => "Pues editar esta páxina. Por favor usa'l botón de vista previa enantes de guardar los cambios.",
'tooltip-ca-addsection' => 'Emprima una seición nueva',
'tooltip-ca-viewsource' => 'Esta páxina ta protexida.
Pues ver el so códigu fonte.',
'tooltip-ca-history' => "Versiones antigües d'esta páxina",
'tooltip-ca-protect' => 'Protexer esta páxina',
'tooltip-ca-unprotect' => 'Camudar la proteición desta páxina',
'tooltip-ca-delete' => 'Desaniciar esta páxina',
'tooltip-ca-undelete' => 'Restaura les ediciones feches nesta páxina enantes de que fuera esborrada',
'tooltip-ca-move' => 'Tresllada esta páxina',
'tooltip-ca-watch' => 'Amiesta esta páxina na to llista de vixilancia',
'tooltip-ca-unwatch' => 'Desaniciar esta páxina de la to llista de vixilancia',
'tooltip-search' => 'Busca en {{SITENAME}}',
'tooltip-search-go' => 'Dir a una páxina con esti nome exautu si esiste',
'tooltip-search-fulltext' => 'Busca páxines con esti testu',
'tooltip-p-logo' => 'Visita la portada',
'tooltip-n-mainpage' => 'Visita la portada',
'tooltip-n-mainpage-description' => 'Visita la portada',
'tooltip-n-portal' => "Tocante al proyeutu, lo qué pues facer, ú s'alcuentren les coses",
'tooltip-n-currentevents' => "Alcontrar información de contestu sobro asocedíos d'actualidá",
'tooltip-n-recentchanges' => 'La llista de cambios recientes de la wiki.',
'tooltip-n-randompage' => 'Carga una páxina al debalu',
'tooltip-n-help' => 'El llugar pa deprender',
'tooltip-t-whatlinkshere' => "Llista de toles páxines wiki qu'enllacien equí",
'tooltip-t-recentchangeslinked' => 'Cambios recientes nes páxines enllazaes dende esta',
'tooltip-feed-rss' => 'Canal RSS pa esta páxina',
'tooltip-feed-atom' => 'Canal Atom pa esta páxina',
'tooltip-t-contributions' => "Llista de collaboraciones d'esti usuariu",
'tooltip-t-emailuser' => 'Unvia un corréu a esti usuariu',
'tooltip-t-upload' => 'Xubir ficheros',
'tooltip-t-specialpages' => 'Llista de toles páxines especiales',
'tooltip-t-print' => "Versión imprentable d'esta páxina",
'tooltip-t-permalink' => 'Enllaz permanente a esta revisión de la páxina',
'tooltip-ca-nstab-main' => 'Ver la páxina de conteníu',
'tooltip-ca-nstab-user' => "Ver la páxina d'usuariu",
'tooltip-ca-nstab-media' => 'Amuesa la páxina de multimedia',
'tooltip-ca-nstab-special' => 'Esta ye una páxina especial, nun pues editar la propia páxina',
'tooltip-ca-nstab-project' => 'Vera la páxina de proyeutu',
'tooltip-ca-nstab-image' => 'Ver la páxina del ficheru',
'tooltip-ca-nstab-mediawiki' => "Amuesa'l mensaxe de sistema",
'tooltip-ca-nstab-template' => 'Amuesa la plantía',
'tooltip-ca-nstab-help' => "Amuesa la páxina d'ayuda",
'tooltip-ca-nstab-category' => 'Ver la páxina de categoría',
'tooltip-minoredit' => 'Marcar como una edición menor',
'tooltip-save' => 'Guardar los cambios',
'tooltip-preview' => 'Vista previa de los cambios, ¡usa esto enantes de guardar!',
'tooltip-diff' => 'Amuesa los cambios que fixisti nel testu.',
'tooltip-compareselectedversions' => "Ver les diferencies ente les dos revisiones seleicionaes d'esta páxina.",
'tooltip-watch' => 'Amiesta esta páxina a la to llista de vixilancia',
'tooltip-watchlistedit-normal-submit' => 'Desaniciar títulos',
'tooltip-watchlistedit-raw-submit' => 'Anovar llista de vixilancia',
'tooltip-recreate' => 'Vuelve a crear la páxina magar que se tenga esborrao',
'tooltip-upload' => 'Empecipiar la xubida',
'tooltip-rollback' => '"Revertir" desfái nún clic la edición(es) d\'esta páxina del postrer collaborador.',
'tooltip-undo' => '"Esfacer" revierte esta edición y abre\'l formulariu d\'edición en mou de vista previa. Permite añader un motivu nel resume.',
'tooltip-preferences-save' => 'Guardar les preferencies',
'tooltip-summary' => 'Escribi un resume curtiu',

# Stylesheets
'common.css' => "/* Los CSS allugaos equí s'aplicarán a tolos aspeutos */",
'standard.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Standard */',
'nostalgia.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Nostalgia */',
'cologneblue.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Cologne Blue */',
'monobook.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Monobook */',
'myskin.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu MySkin */',
'chick.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Chick */',
'simple.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Simple */',
'modern.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Modern */',
'vector.css' => '/* Los CSS allugaos equí afeutarán a los usuarios del aspeutu Vector */',
'print.css' => '/* Los CSS allugaos equí afeutarán a la salida pola imprentadora */',
'handheld.css' => '/* Los CSS allugaos equí afeutarán a los preseos portátiles basaos nel aspeutu configuráu en $wgHandheldStyle */',
'noscript.css' => '/* Los CSS allugaos equí afeutarán a los usuarios col JavaScript desactiváu */',
'group-autoconfirmed.css' => '/* Los CSS allugaos equí afeutarán a los usuarios autoconfirmaos namái */',
'group-bot.css' => '/* Los CSS allugaos equí afeutarán a los bots namái */',
'group-sysop.css' => '/* Los CSS allugaos equí afeutarán a los sysops namái */',
'group-bureaucrat.css' => '/* Los CSS allugaos equí afeutarán a los burócrates namái */',

# Scripts
'common.js' => '/* Cualesquier JavaScript que tea equí se cargará pa tolos usuarios en cada carga de páxina. */',
'standard.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Standard */',
'nostalgia.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Nostalgia */',
'cologneblue.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Cologne Blue */',
'monobook.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu MonoBook */',
'myskin.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu MySkin */',
'chick.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Chick */',
'simple.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Simple */',
'modern.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Modern */',
'vector.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios del aspeutu Vector */',
'group-autoconfirmed.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los usuarios autoconfirmaos namái */',
'group-bot.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los bots namái */',
'group-sysop.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los sysops namái */',
'group-bureaucrat.js' => '/* Cualesquier JavaScript que tea equí se cargará pa los burócrates namái */',

# Metadata
'notacceptable' => 'El sirvidor de la wiki nun pue suplir los datos nun formatu llexible pol to navegador.',

# Attribution
'anonymous' => '{{PLURAL:$1|Usuariu anónimu|Usuarios anónimos}} de {{SITENAME}}',
'siteuser' => '{{SITENAME}} usuariu $1',
'anonuser' => 'usuariu anónimu de {{SITENAME}} $1',
'lastmodifiedatby' => "Esta páxina se camudó por cabera vegada'l $1 a les $2 por $3.",
'othercontribs' => 'Basao nel trabayu fechu por $1.',
'others' => 'otros',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|usuariu|usuarios}} $1',
'anonusers' => '{{PLURAL:$2|Usuariu anónimu|usuarios anónimos}} de {{SITENAME}} $1',
'creditspage' => 'Páxina de creitos',
'nocredits' => 'Nun hai disponible información de creitos pa esta páxina.',

# Spam protection
'spamprotectiontitle' => 'Filtru de proteición de spam',
'spamprotectiontext' => 'La páxina que queríes guardar foi bloquiada pol filtru de spam.
Probablemente tea causao por un enllaz a un sitiu esternu de la llista prieta.',
'spamprotectionmatch' => "El testu siguiente foi'l qu'activó'l nuesu filtru de spam: $1",
'spambot_username' => 'Llimpieza de spam de MediaWiki',
'spam_reverting' => 'Revirtiendo a la cabera versión que nun contién enllaces a $1',
'spam_blanking' => 'Toles revisiones teníen enllaces a $1; dexando en blanco',
'spam_deleting' => 'Toles revisiones teníen enllaces a $1, desaniciando',

# Info page
'pageinfo-title' => 'Información sobro "$1"',
'pageinfo-not-current' => 'Sentimoslo, ye imposible dar esta información de les revisiones antigües.',
'pageinfo-header-basic' => 'Información básica',
'pageinfo-header-edits' => "Historial d'ediciones",
'pageinfo-header-restrictions' => 'Proteición de páxina',
'pageinfo-header-properties' => 'Propiedaes de la páxina',
'pageinfo-display-title' => 'Amosar el títulu',
'pageinfo-default-sort' => "Clave d'ordenación predeterminada",
'pageinfo-length' => 'Llonxitú de la páxina (en bytes)',
'pageinfo-article-id' => 'ID de la páxina',
'pageinfo-robot-policy' => 'Estáu del motor de gueta',
'pageinfo-robot-index' => 'Pue ser índiz',
'pageinfo-robot-noindex' => 'Nun pue ser índiz',
'pageinfo-views' => 'Númberu de visites',
'pageinfo-watchers' => 'Númberu de vixilantes de la páxina',
'pageinfo-redirects-name' => 'Redireiciones a esta páxina',
'pageinfo-subpages-name' => "Subpáxines d'esta páxina",
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|redireición|redireiciones}}; $3 {{PLURAL:$3|non-redireición|non-redireiciones}})',
'pageinfo-firstuser' => 'Creador de la páxina',
'pageinfo-firsttime' => 'Data de creación de la páxina',
'pageinfo-lastuser' => 'Caberu editor',
'pageinfo-lasttime' => 'Data de la cabera edición',
'pageinfo-edits' => "Númberu total d'ediciones",
'pageinfo-authors' => "Númberu total d'autores distintos",
'pageinfo-recent-edits' => "Númberu d'ediciones recientes (nos caberos $1)",
'pageinfo-recent-authors' => "Númberu d'autores distintos recientes",
'pageinfo-magic-words' => '{{PLURAL:$1|Pallabra máxica|Pallabres máxiques}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Categoría anubrida|Categoríes anubríes}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Plantía incluída|Plantíes incluíes}} ($1)',

# Skin names
'skinname-standard' => 'Clásicu',
'skinname-nostalgia' => 'Señardá',
'skinname-cologneblue' => 'Azul Colonia',
'skinname-myskin' => 'MySkin',
'skinname-modern' => 'Modernu',

# Patrolling
'markaspatrolleddiff' => 'Marcar como supervisada',
'markaspatrolledtext' => 'Marcar esta páxina como supervisada',
'markedaspatrolled' => 'Marcar como supervisada',
'markedaspatrolledtext' => 'La revisión seleicionada de [[:$1]] se marcó como supervisada.',
'rcpatroldisabled' => 'Supervisión de cambios recientes desactivada',
'rcpatroldisabledtext' => 'La función de supervisión de cambios recientes ta desactivada nestos momentos.',
'markedaspatrollederror' => 'Nun se pue marcar como supervisada',
'markedaspatrollederrortext' => 'Necesites conseñar una revisión pa marcala como supervisada.',
'markedaspatrollederror-noautopatrol' => 'Nun tienes permisu pa marcar los cambios propios como supervisaos.',

# Patrol log
'patrol-log-page' => 'Rexistru de supervisión',
'patrol-log-header' => 'Esti ye un rexistru de les revisiones supervisaes.',
'log-show-hide-patrol' => '$1 rexistru de supervisión',

# Image deletion
'deletedrevision' => 'Esborrada la reversión vieya $1',
'filedeleteerror-short' => "Error al esborrar l'archivu: $1",
'filedeleteerror-long' => "Atopáronse errores al esborrar l'archivu:

$1",
'filedelete-missing' => 'L\'archivu "$1" nun pue esborrase porque nun esiste.',
'filedelete-old-unregistered' => 'La revisión d\'archivu "$1" nun ta na base de datos.',
'filedelete-current-unregistered' => 'L\'archivu especificáu "$1" nun ta na base de datos.',
'filedelete-archive-read-only' => 'El direutoriu d\'archivos "$1" nun pue ser modificáu pol sirvidor.',

# Browsing diffs
'previousdiff' => '← Edición más antigua',
'nextdiff' => 'Edición más nueva →',

# Media information
'mediawarning' => "'''Avisu''': Esta triba de ficheru pue contener códigu maliciosu.
Al executalu pues comprometer el to sistema.",
'imagemaxsize' => "Llende del tamañu d'imaxe: <br />''(pa les páxines de descripción de ficheru)''",
'thumbsize' => 'Tamañu de la muestra:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|páxina|páxines}}',
'file-info' => "tamañu d'archivu: $1, triba MIME: $2",
'file-info-size' => '$1 × $2 píxels, tamañu de ficheru: $3, triba MIME: $4',
'file-info-size-pages' => '$1 × $2 pixels, tamañu del ficheru: $3, triba MIME: $4, $5 {{PLURAL:$5|páxina|páxines}}',
'file-nohires' => 'Nun ta disponible con mayor resolución.',
'svg-long-desc' => 'ficheru SVG, $1 × $2 píxels nominales, tamañu de ficheru: $3',
'svg-long-desc-animated' => 'Ficheru SVG animáu; nominalmente de $1 × $2 pixels; tamañu del ficheru: $3',
'show-big-image' => 'Resolución completa',
'show-big-image-preview' => "Tamañu d'esta previsualización: $1.",
'show-big-image-other' => '{{PLURAL:$2|Otra resolución|Otres resoluciones}}: $1.',
'show-big-image-size' => '$1 × $2 pixels',
'file-info-gif-looped' => 'animáu',
'file-info-gif-frames' => '$1 {{PLURAL:$1|cuadru|cuadros}}',
'file-info-png-looped' => 'animáu',
'file-info-png-repeat' => 'reproducíu $1 {{PLURAL:$1|vez|veces}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|cuadru|cuadros}}',
'file-no-thumb-animation' => "'''Nota: pola mor de llímites téunicos, les miniatures d'esti ficheru nun tan animaes.'''",
'file-no-thumb-animation-gif' => "'''Nota: pola mor de llímites téunicos, les miniatures d'imaxes GIF d'alta resolución como esta nun tan animaes.'''",

# Special:NewFiles
'newimages' => "Galería d'imáxenes nueves",
'imagelisttext' => "Embaxo ta la llista {{PLURAL:$1|d'un archivu ordenáu|de '''$1''' archivos ordenaos}} $2.",
'newimages-summary' => 'Esta páxina especial amuesa los caberos archivos xubíos.',
'newimages-legend' => 'Peñera',
'newimages-label' => "Nome d'archivu (o una parte d'él):",
'showhidebots' => '($1 bots)',
'noimages' => 'Nun hai nada que ver.',
'ilsubmit' => 'Guetar',
'bydate' => 'por fecha',
'sp-newimages-showfrom' => "Amosar los archivos nuevos emprimando dende'l $1 a les $2",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 segundu|$1 segundos}}',
'minutes' => '{{PLURAL:$1|$1 minutu|$1 minutos}}',
'hours' => '{{PLURAL:$1|$1 hora|$1 hores}}',
'days' => '{{PLURAL:$1|$1 día|$1 díes}}',
'ago' => 'hai $1',

# Bad image list
'bad_image_list' => "El formatu ye'l que sigue:

Namái se consideren los elementos de llista (llinies qu'emprimen con *).
El primer enllaz d'una llinia tien de ser ún qu'enllacie a un archivu non válidu.
Los demás enllaces de la mesma llinia considérense esceiciones, p.ex. páxines nes que'l ficheru pue apaecer en llinia.",

# Metadata
'metadata' => 'Metadatos',
'metadata-help' => "Esti ficheru contién otra información, probablemente añadida pola cámara dixital o l'escáner usaos pa crealu o dixitalizalu.
Si'l ficheru se camudó dende'l so estáu orixinal, seique dalgunos detalles nun se reflexen completamente nel ficheru camudáu.",
'metadata-expand' => 'Amosar detalles estendíos',
'metadata-collapse' => 'Esconder detalles estendíos',
'metadata-fields' => 'Los campos de metadatos de la imaxe llistaos nesti mensaxe van ser inxeríos na vista de la páxina de la imaxe cuando la tabla de metadatos tea recoyida.
Los demás tarán anubríos de mou predetermináu.
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

# EXIF tags
'exif-imagewidth' => 'Anchor',
'exif-imagelength' => 'Altor',
'exif-bitspersample' => 'Bits por componente',
'exif-compression' => 'Esquema de compresión',
'exif-photometricinterpretation' => 'Composición del píxel',
'exif-orientation' => 'Orientación',
'exif-samplesperpixel' => 'Númberu de componentes',
'exif-planarconfiguration' => 'Distribución de los datos',
'exif-ycbcrsubsampling' => "Razón de somuestréu d'Y a C",
'exif-ycbcrpositioning' => 'Allugamientu Y y C',
'exif-xresolution' => 'Resolución horizontal',
'exif-yresolution' => 'Resolución vertical',
'exif-stripoffsets' => 'Allugamientu de los datos de la imaxe',
'exif-rowsperstrip' => 'Númberu de fileres por banda',
'exif-stripbytecounts' => 'Bytes por banda comprimida',
'exif-jpeginterchangeformat' => 'Desplazamientu al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes de datos JPEG',
'exif-whitepoint' => 'Cromacidá de puntu blancu',
'exif-primarychromaticities' => 'Cromacidá de los primarios',
'exif-ycbcrcoefficients' => 'Coeficientes de la matriz de tresformación del espaciu de color',
'exif-referenceblackwhite' => 'Pareya de valores blancu y negru de referencia',
'exif-datetime' => 'Fecha y hora de modificación del archivu',
'exif-imagedescription' => 'Títulu de la imaxe',
'exif-make' => 'Fabricante de la cámara',
'exif-model' => 'Modelu de cámara',
'exif-software' => 'Software usáu',
'exif-artist' => 'Autor',
'exif-copyright' => 'Titular del Copyright',
'exif-exifversion' => 'Versión Exif',
'exif-flashpixversion' => 'Versión almitida de Flashpix',
'exif-colorspace' => 'Espaciu de color',
'exif-componentsconfiguration' => 'Significáu de cada componente',
'exif-compressedbitsperpixel' => "Mou de compresión d'imaxe",
'exif-pixelydimension' => "Anchor d'imaxe",
'exif-pixelxdimension' => "Altor d'imaxe",
'exif-usercomment' => 'Comentarios del usuariu',
'exif-relatedsoundfile' => "Archivu d'audiu rellacionáu",
'exif-datetimeoriginal' => 'Fecha y hora de la xeneración de datos',
'exif-datetimedigitized' => 'Fecha y hora de la dixitalización',
'exif-subsectime' => 'Fecha y hora (precisión infrasegundu)',
'exif-subsectimeoriginal' => 'Fecha y hora del orixinal (precisión infrasegundu)',
'exif-subsectimedigitized' => 'Fecha y hora de la dixitalización (precisión infrasegundu)',
'exif-exposuretime' => "Tiempu d'esposición",
'exif-exposuretime-format' => '$1 seg ($2)',
'exif-fnumber' => 'Númberu F',
'exif-exposureprogram' => "Programa d'esposición",
'exif-spectralsensitivity' => 'Sensitividá espeutral',
'exif-isospeedratings' => 'Sensibilidá ISO',
'exif-shutterspeedvalue' => 'Velocidá APEX del obturador',
'exif-aperturevalue' => 'Abertura APEX',
'exif-brightnessvalue' => 'Brillu APEX',
'exif-exposurebiasvalue' => "Correición d'esposición",
'exif-maxaperturevalue' => "Valor máximu d'apertura",
'exif-subjectdistance' => 'Distancia al suxetu',
'exif-meteringmode' => 'Mou de midición',
'exif-lightsource' => 'Fonte de la lluz',
'exif-flash' => 'Flax',
'exif-focallength' => 'Llonxitú focal de la lente',
'exif-subjectarea' => 'Área del suxetu',
'exif-flashenergy' => 'Enerxía del flax',
'exif-focalplanexresolution' => 'Resolución X del planu focal',
'exif-focalplaneyresolution' => 'Resolución Y del planu focal',
'exif-focalplaneresolutionunit' => 'Unidá de resolución del planu focal',
'exif-subjectlocation' => 'Allugamientu del suxetu',
'exif-exposureindex' => "Índiz d'esposición",
'exif-sensingmethod' => 'Métodu de sensor',
'exif-filesource' => 'Orixe del archivu',
'exif-scenetype' => "Triba d'escena",
'exif-customrendered' => "Procesamientu d'imaxe personalizáu",
'exif-exposuremode' => "Mou d'esposición",
'exif-whitebalance' => 'Balance de blancos',
'exif-digitalzoomratio' => 'Razón de zoom dixital',
'exif-focallengthin35mmfilm' => 'Llonxitú focal en película de 35 mm',
'exif-scenecapturetype' => "Triba de captura d'escena",
'exif-gaincontrol' => "Control d'escena",
'exif-contrast' => 'Contraste',
'exif-saturation' => 'Saturación',
'exif-sharpness' => 'Nitidez',
'exif-devicesettingdescription' => 'Descripción de la configuración del dispositivu',
'exif-subjectdistancerange' => 'Intervalu de distacia al suxetu',
'exif-imageuniqueid' => "Identificación única d'imaxe",
'exif-gpsversionid' => 'Versión de la etiqueta GPS',
'exif-gpslatituderef' => 'Llatitú Norte o Sur',
'exif-gpslatitude' => 'Llatitú',
'exif-gpslongituderef' => 'Llonxitú Este o Oeste',
'exif-gpslongitude' => 'Llonxitú',
'exif-gpsaltituderef' => "Referencia d'altitú",
'exif-gpsaltitude' => 'Altitú',
'exif-gpstimestamp' => 'Hora GPS (reló atómicu)',
'exif-gpssatellites' => 'Satélites usaos pa la midida',
'exif-gpsstatus' => 'Estáu del receptor',
'exif-gpsmeasuremode' => 'Mou de midida',
'exif-gpsdop' => 'Precisión de midida',
'exif-gpsspeedref' => 'Unidá de velocidá',
'exif-gpsspeed' => 'Velocidá del receutor GPS',
'exif-gpstrackref' => 'Referencia de la direición de movimientu',
'exif-gpstrack' => 'Direición de movimientu',
'exif-gpsimgdirectionref' => 'Referencia de la direición de la imaxe',
'exif-gpsimgdirection' => 'Direición de la imaxe',
'exif-gpsmapdatum' => 'Usaos datos del estudiu xeodésicu',
'exif-gpsdestlatituderef' => 'Referencia de la llatitú de destín',
'exif-gpsdestlatitude' => 'Llatitú de destín',
'exif-gpsdestlongituderef' => 'Referencia de la llonxitú de destín',
'exif-gpsdestlongitude' => 'Llonxitú de destín',
'exif-gpsdestbearingref' => 'Referencia de la orientación de destín',
'exif-gpsdestbearing' => 'Orientación del destín',
'exif-gpsdestdistanceref' => 'Referencia de la distancia al destín',
'exif-gpsdestdistance' => 'Distancia al destín',
'exif-gpsprocessingmethod' => 'Nome del métodu de procesamientu de GPS',
'exif-gpsareainformation' => "Nome de l'área GPS",
'exif-gpsdatestamp' => 'Fecha GPS',
'exif-gpsdifferential' => 'Correición diferencial de GPS',
'exif-jpegfilecomment' => 'Comentariu del ficheru JPEG',
'exif-keywords' => 'Pallabres clave',
'exif-worldregioncreated' => 'Parte del mundu aú se sacó la semeya',
'exif-countrycreated' => 'País nel que se sacó la semeya',
'exif-countrycodecreated' => 'Códigu del país nel que se sacó la semeya',
'exif-provinceorstatecreated' => 'Provincia o estáu nel que se sacó la semeya',
'exif-citycreated' => 'Ciudá na que se sacó la semeya',
'exif-sublocationcreated' => 'Subdivisión de la ciudá onde se sacó esta semeya',
'exif-worldregiondest' => 'Rexón del mundu amosada',
'exif-countrydest' => 'País amosáu',
'exif-countrycodedest' => 'Códigu del país amosáu',
'exif-provinceorstatedest' => 'Provincia o estáu amosáu',
'exif-citydest' => 'Ciudá amosada',
'exif-sublocationdest' => 'Subdivisión de la ciudá amosada',
'exif-objectname' => 'Títulu curtiu',
'exif-specialinstructions' => 'Instrucciones especiales',
'exif-headline' => 'Titular',
'exif-credit' => 'Créitu/Fornidor',
'exif-source' => 'Fonte',
'exif-editstatus' => 'Estáu editorial de la imaxe',
'exif-urgency' => 'Urxencia',
'exif-fixtureidentifier' => 'Nome del elementu',
'exif-locationdest' => 'Llugar representáu',
'exif-locationdestcode' => 'Códigu del llugar representáu',
'exif-objectcycle' => 'Hora del día a la que se destina esti mediu',
'exif-contact' => 'Información de contautu',
'exif-writer' => 'Redactor',
'exif-languagecode' => 'Llingua',
'exif-iimversion' => "Versión d'IIM",
'exif-iimcategory' => 'Categoría',
'exif-iimsupplementalcategory' => 'Categoríes suplementaries',
'exif-datetimeexpires' => 'Nun usar dempués de',
'exif-datetimereleased' => 'Asoleyada el',
'exif-originaltransmissionref' => 'Códigu orixinal de llocalización de tresmisión',
'exif-identifier' => 'Identificador',
'exif-lens' => 'Lente usada',
'exif-serialnumber' => 'Númberu de serie de la cámara',
'exif-cameraownername' => 'Dueñu de la cámara',
'exif-label' => 'Etiqueta',
'exif-datetimemetadata' => 'Hora que camudaron los metadatos la cabera vegada',
'exif-nickname' => 'Nome informal de la imaxe',
'exif-rating' => 'Puntos (sobre 5)',
'exif-rightscertificate' => 'Certificáu de xestión de drechos',
'exif-copyrighted' => 'Estáu del copyright',
'exif-copyrightowner' => 'Titular del copyright',
'exif-usageterms' => "Términos d'usu",
'exif-webstatement' => "Declaración en llinia de drechos d'autor",
'exif-originaldocumentid' => 'ID única del documentu orixinal',
'exif-licenseurl' => "URL de la llicencia de drechos d'autor",
'exif-morepermissionsurl' => 'Información de llicencia alternativa',
'exif-attributionurl' => 'Al re-usar esta obra, por favor enllazar a',
'exif-preferredattributionname' => 'Al re-usar esta obra, por favor atribuir a',
'exif-pngfilecomment' => 'Comentariu del ficheru PNG',
'exif-disclaimer' => 'Avisu xeneral',
'exif-contentwarning' => "Avisu sobro'l conteníu",
'exif-giffilecomment' => 'Comentariu del ficheru GIF',
'exif-intellectualgenre' => "Triba d'elementu",
'exif-subjectnewscode' => 'Códigu del tema',
'exif-scenecode' => "Códigu d'escena IPTC",
'exif-event' => 'Acontecimientu representáu',
'exif-organisationinimage' => 'Organización representada',
'exif-personinimage' => 'Persona representada',
'exif-originalimageheight' => 'Altor de la imaxe enantes de cortase',
'exif-originalimagewidth' => 'Anchor de la imaxe enantes de cortase',

# EXIF attributes
'exif-compression-1' => 'Non comprimida',
'exif-compression-2' => 'CCITT Grupu 3 codificación Huffman RLE con Modificación 1-Dimensional',
'exif-compression-3' => 'Codificación de fax CCITT Grupu 3',
'exif-compression-4' => 'Codificación de fax CCITT Grupu 4',
'exif-compression-6' => 'JPEG (antiguu)',

'exif-copyrighted-true' => "Con drechos d'autor",
'exif-copyrighted-false' => 'Dominiu públicu',

'exif-unknowndate' => 'Fecha desconocida',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Voltiada horizontalmente',
'exif-orientation-3' => 'Rotada 180°',
'exif-orientation-4' => 'Voltiada verticalmente',
'exif-orientation-5' => 'Rotada 90° a manzorga y voltiada verticalmente',
'exif-orientation-6' => 'Xirada 90° en sentíu antihorariu',
'exif-orientation-7' => 'Rotada 90° a mandrecha y voltiada verticalmente',
'exif-orientation-8' => 'Xirada 90° en sentíu horariu',

'exif-planarconfiguration-1' => 'formatu irregular',
'exif-planarconfiguration-2' => 'formatu planu',

'exif-xyresolution-i' => '$1 ppp',
'exif-xyresolution-c' => '$1 ppc',

'exif-colorspace-65535' => 'Ensin calibrar',

'exif-componentsconfiguration-0' => 'nun esiste',

'exif-exposureprogram-0' => 'Non definida',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => "Prioridá d'apertura",
'exif-exposureprogram-4' => "Prioridá d'obturador",
'exif-exposureprogram-5' => 'Programa creativu (con prioridá de profundidá de campu)',
'exif-exposureprogram-6' => "Programa d'aición (prioridá d'alta velocidá del obturador)",
'exif-exposureprogram-7' => 'Mou retratu (pa semeyes cercanes col fondu desenfocáu)',
'exif-exposureprogram-8' => 'Mou paisaxe (pa semeyes amplies col fondu enfocáu)',

'exif-subjectdistance-value' => '{{PLURAL:$1|$1 metru|$1 metros}}',

'exif-meteringmode-0' => 'Desconocíu',
'exif-meteringmode-1' => 'Media',
'exif-meteringmode-2' => 'Media ponderada centrada',
'exif-meteringmode-3' => 'Puntual',
'exif-meteringmode-4' => 'Multipuntu',
'exif-meteringmode-5' => 'Patrón',
'exif-meteringmode-6' => 'Parcial',
'exif-meteringmode-255' => 'Otru',

'exif-lightsource-0' => 'Desconocida',
'exif-lightsource-1' => 'Lluz diurna',
'exif-lightsource-2' => 'Fluorescente',
'exif-lightsource-3' => 'Tungstenu (lluz incandescente)',
'exif-lightsource-4' => 'Flax',
'exif-lightsource-9' => 'Tiempu despexáu',
'exif-lightsource-10' => 'Tiempu ñubláu',
'exif-lightsource-11' => 'Solombra',
'exif-lightsource-12' => 'Fluorescente lluz de día (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluorescente blancu día (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluorescente blancu fríu (W 3900 – 4500K)',
'exif-lightsource-15' => 'Fluorescente blancu (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Lluz estándar A',
'exif-lightsource-18' => 'Lluz estándar B',
'exif-lightsource-19' => 'Lluz estándar C',
'exif-lightsource-24' => "Tungstenu ISO d'estudio",
'exif-lightsource-255' => 'Otra fonte de lluz',

# Flash modes
'exif-flash-fired-0' => 'Flax non disparáu',
'exif-flash-fired-1' => 'Flax disparáu',
'exif-flash-return-0' => 'ensin función de deteición de retornu estroboscópicu',
'exif-flash-return-2' => 'lluz de retornu estroboscópicu non detectada',
'exif-flash-return-3' => 'lluz de retornu estroboscópicu detectada',
'exif-flash-mode-1' => 'disparu de flax forciáu',
'exif-flash-mode-2' => 'supresión de flax forciáu',
'exif-flash-mode-3' => 'mou automáticu',
'exif-flash-function-1' => 'Ensin función de flax',
'exif-flash-redeye-1' => "mou d'amenorgamientu de güeyos encarnaos",

'exif-focalplaneresolutionunit-2' => 'pulgaes',

'exif-sensingmethod-1' => 'Non definíu',
'exif-sensingmethod-2' => "Sensor d'área de color d'un chip",
'exif-sensingmethod-3' => "Sensor d'área de color de dos chips",
'exif-sensingmethod-4' => "Sensor d'área de color de tres chips",
'exif-sensingmethod-5' => "Sensor d'área secuencial de color",
'exif-sensingmethod-7' => 'Sensor Trillinial',
'exif-sensingmethod-8' => 'Sensor llinial secuencial de color',

'exif-filesource-3' => 'Cámara fotográfica dixital',

'exif-scenetype-1' => 'Una imaxe fotografiada direutamente',

'exif-customrendered-0' => 'Procesu normal',
'exif-customrendered-1' => 'Procesu personalizáu',

'exif-exposuremode-0' => 'Esposición automática',
'exif-exposuremode-1' => 'Esposición manual',
'exif-exposuremode-2' => 'Puesta ente paréntesis automática',

'exif-whitebalance-0' => 'Balance automáticu de blancos',
'exif-whitebalance-1' => 'Balance manual de blancos',

'exif-scenecapturetype-0' => 'Estándar',
'exif-scenecapturetype-1' => 'Paisaxe',
'exif-scenecapturetype-2' => 'Retratu',
'exif-scenecapturetype-3' => 'Escena nocherniega',

'exif-gaincontrol-0' => 'Nenguna',
'exif-gaincontrol-1' => 'Aumentu de ganancia baxu',
'exif-gaincontrol-2' => 'Aumentu de ganancia altu',
'exif-gaincontrol-3' => 'Mengua de ganancia baxa',
'exif-gaincontrol-4' => 'Mengua de ganancia alta',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Fuerte',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturación baxa',
'exif-saturation-2' => 'Saturación alta',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Suave',
'exif-sharpness-2' => 'Fuerte',

'exif-subjectdistancerange-0' => 'Desconocíu',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Vista averada',
'exif-subjectdistancerange-3' => 'Vista alloñada',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Llatitú Norte',
'exif-gpslatitude-s' => 'Llatitú Sur',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Lloxitú Este',
'exif-gpslongitude-w' => 'Lloxitú Oeste',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => "$1 {{PLURAL:$1|metru|metros}} sobro'l nivel del mar",
'exif-gpsaltitude-below-sealevel' => "$1 {{PLURAL:$1|metru|metros}} baxo'l nivel del mar",

'exif-gpsstatus-a' => 'Midición en progresu',
'exif-gpsstatus-v' => 'Interoperabilidá de la midición',

'exif-gpsmeasuremode-2' => 'Midición bidimensional',
'exif-gpsmeasuremode-3' => 'Midición tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Quilómetros por hora',
'exif-gpsspeed-m' => 'Milles por hora',
'exif-gpsspeed-n' => 'Nueyos',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Quilómetros',
'exif-gpsdestdistance-m' => 'Milles',
'exif-gpsdestdistance-n' => 'Milles náutiques',

'exif-gpsdop-excellent' => 'Escelente ($1)',
'exif-gpsdop-good' => 'Bona ($1)',
'exif-gpsdop-moderate' => 'Moderada ($1)',
'exif-gpsdop-fair' => 'Xusta ($1)',
'exif-gpsdop-poor' => 'Probe ($1)',

'exif-objectcycle-a' => 'Sólo pola mañana',
'exif-objectcycle-p' => 'Sólo pola tarde',
'exif-objectcycle-b' => 'Pola mañana y pola tarde',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direición real',
'exif-gpsdirection-m' => 'Direición magnética',

'exif-ycbcrpositioning-1' => 'Centráu',
'exif-ycbcrpositioning-2' => 'Co-llocalizaos',

'exif-dc-contributor' => 'Collaboradores',
'exif-dc-coverage' => 'Estensión espacial o temporal del conteníu',
'exif-dc-date' => 'Data(es)',
'exif-dc-publisher' => 'Editor',
'exif-dc-relation' => 'Otros medios rellacionaos',
'exif-dc-rights' => 'Drechos',
'exif-dc-source' => 'Mediu orixinal',
'exif-dc-type' => 'Triba de mediu',

'exif-rating-rejected' => 'Refugáu',

'exif-isospeedratings-overflow' => 'Mayor que 65535',

'exif-iimcategory-ace' => 'Arte, cultura y entretenimientu',
'exif-iimcategory-clj' => 'Crime y drechu',
'exif-iimcategory-dis' => 'Desastres y accidentes',
'exif-iimcategory-fin' => 'Economía y negocios',
'exif-iimcategory-edu' => 'Educación',
'exif-iimcategory-evn' => 'Mediu ambiente',
'exif-iimcategory-hth' => 'Salú',
'exif-iimcategory-hum' => 'Interés humanu',
'exif-iimcategory-lab' => 'Trabayu',
'exif-iimcategory-lif' => 'Estilu de vida y recréu',
'exif-iimcategory-pol' => 'Política',
'exif-iimcategory-rel' => 'Relixón y creencies',
'exif-iimcategory-sci' => 'Ciencia y teunoloxía',
'exif-iimcategory-soi' => 'Cuestiones sociales',
'exif-iimcategory-spo' => 'Deportes',
'exif-iimcategory-war' => 'Guerra, conflictos y protestes',
'exif-iimcategory-wea' => 'Meteoroloxía',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => 'Baxa ($1)',
'exif-urgency-high' => 'Alta ($1)',
'exif-urgency-other' => 'Prioridá definía pol usuariu ($1)',

# External editor support
'edit-externally' => 'Editar esti ficheru usando una aplicación esterna',
'edit-externally-help' => '(Pa más información ver les [//www.mediawiki.org/wiki/Manual:External_editors instrucciones de configuración])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'too',
'namespacesall' => 'toos',
'monthsall' => 'toos',
'limitall' => 'toos',

# E-mail address confirmation
'confirmemail' => 'Confirmar direición de corréu',
'confirmemail_noemail' => "Nun tienes una direición de corréu válida nes tos [[Special:Preferences|preferencies d'usuariu]].",
'confirmemail_text' => "{{SITENAME}} requier que valides la to direición de corréu enantes d'usar les
funcionalidaes de mensaxes. Da-y al botón que tienes equí embaxo pa unviar un avisu de
confirmación a la to direición. Esti avisu va incluyir un enllaz con un códigu; carga
l'enllaz nel to navegador pa confirmar la to direición de corréu electrónicu.",
'confirmemail_pending' => "Yá s'unvió un códigu de confirmación a la to direición de corréu; si creasti hai poco la to cuenta, pues esperar dellos minutos a que-y de tiempu a llegar enantes de pidir otru códigu nuevu.",
'confirmemail_send' => 'Unviar códigu de confirmación',
'confirmemail_sent' => 'Corréu de confirmación unviáu.',
'confirmemail_oncreate' => "Unvióse un códigu de confirmación a la to direición de corréu.
Esti códigu nun se necesita pa identificase, pero tendrás que lu conseñar enantes
d'activar cualesquier funcionalidá de la wiki que tea rellacionada col corréu.",
'confirmemail_sendfailed' => '{{SITENAME}} nun pudo unviar el to corréu de confirmación.
Por favor comprueba que nun punxeras carauteres non válidos na to direición de corréu.

El sirvidor de corréu devolvió: $1',
'confirmemail_invalid' => 'Códigu de confirmación non válidu. El códigu seique tenga caducao.',
'confirmemail_needlogin' => 'Has $1 pa confirmar la to direición de corréu electrónicu.',
'confirmemail_success' => 'El to corréu quedó confimáu.
Agora yá pues [[Special:UserLogin|coneutate]] y esfrutar de la wiki.',
'confirmemail_loggedin' => 'Quedó confirmada la to direición de corréu.',
'confirmemail_error' => 'Hebo un problema al guardar la to confirmación.',
'confirmemail_subject' => 'Confirmación de la direición de corréu de {{SITENAME}}',
'confirmemail_body' => 'Daquién, seique tu dende la IP $1, rexistró la cuenta "$2" con
esta direición de corréu en {{SITENAME}}.

Pa confirmar qu\'esta cuenta ye tuya daveres y asina activar les funcionalidaes
de corréu en {{SITENAME}}, abri esti enllaz nel to navegador:

$3

Si *nun* rexistrasti tu la cuenta, da-y a esti enllaz pa cancelar
la confirmación de la direición de corréu electrónicu:

$5

Esti códigu de confirmación caduca\'l $4.',
'confirmemail_body_changed' => 'Daquién, seique tu dende la IP $1, camudó les señes de corréu de
la cuenta "$2" a esta direición de corréu en {{SITENAME}}.

Pa confirmar qu\'esta cuenta ye tuya daveres y reactivar les funciones
de corréu en {{SITENAME}}, abri esti enllaz nel to navegador:

$3

Si la cuenta *nun* ye de to, calca nesti enllaz pa encaboxar
la confirmación de les señes de corréu electrónicu:

$5

Esti códigu de confirmación caduca\'l $4.',
'confirmemail_body_set' => 'Daquién, seique tu dende la IP $1, camudó les señes de corréu de
la cuenta "$2" a esta direición de corréu en {{SITENAME}}.

Pa confirmar qu\'esta cuenta ye tuya daveres y reactivar les funciones
de corréu en {{SITENAME}}, abri esti enllaz nel to navegador:

$3

Si la cuenta *nun* ye de to, calca nesti enllaz pa encaboxar
la confirmación de les señes de corréu electrónicu:

$5

Esti códigu de confirmación caduca\'l $4.',
'confirmemail_invalidated' => 'Confirmación de direición de corréu electrónicu cancelada',
'invalidateemail' => 'Cancelar confirmación de corréu electrónicu',

# Scary transclusion
'scarytranscludedisabled' => '[La tresclusión interwiki ta desactivada]',
'scarytranscludefailed' => '[Falló la recuperación de la plantía pa $1]',
'scarytranscludetoolong' => '[La URL ye demasiao llarga]',

# Delete conflict
'deletedwhileediting' => "'''Avisu''': ¡Esta páxina foi esborrada depués de qu'entamaras a editala!",
'confirmrecreate' => "L'usuariu [[User:$1|$1]] ([[User talk:$1|alderique]]) esborró esta páxina depués de qu'empecipiaras a editala pol siguiente motivu:
: ''$2''
Por favor confirma que daveres quies volver a crear esta páxina.",
'confirmrecreate-noreason' => "L'usuariu [[User:$1|$1]] ([[User talk:$1|talk]]) desanició esta páxina depués de que principiaras a editala.  Por favor confirma que daveres quies volver a crear esta páxina.",
'recreate' => 'Volver a crear',

# action=purge
'confirm_purge_button' => 'Aceutar',
'confirm-purge-top' => "¿Llimpiar la caché d'esta páxina?",
'confirm-purge-bottom' => 'Purgar una páxina esborra la caché y fuercia a apaecer la versión actual más recién.',

# action=watch/unwatch
'confirm-watch-button' => 'Aceutar',
'confirm-watch-top' => '¿Amestar esta páxina a la to llista de vixilancia?',
'confirm-unwatch-button' => 'Aceutar',
'confirm-unwatch-top' => '¿Desaniciar esta páxina de la to llista de vixilancia?',

# Multipage image navigation
'imgmultipageprev' => '← páxina anterior',
'imgmultipagenext' => 'páxina siguiente →',
'imgmultigo' => '¡Dir!',
'imgmultigoto' => 'Dir a la páxina $1',

# Table pager
'ascending_abbrev' => 'asc',
'descending_abbrev' => 'desc',
'table_pager_next' => 'Páxina siguiente',
'table_pager_prev' => 'Páxina anterior',
'table_pager_first' => 'Primer páxina',
'table_pager_last' => 'Postrer páxina',
'table_pager_limit' => 'Amosar $1 elementos por páxina',
'table_pager_limit_label' => 'Elementos por páxina:',
'table_pager_limit_submit' => 'Dir',
'table_pager_empty' => 'Nun hai resultaos',

# Auto-summaries
'autosumm-blank' => 'Páxina dexada en blanco',
'autosumm-replace' => "Sustituyendo la páxina por '$1'",
'autoredircomment' => 'Redirixendo a [[$1]]',
'autosumm-new' => "Páxina creada con '$1'",

# Size units
'size-gigabytes' => '$1 XB',

# Live preview
'livepreview-loading' => 'Cargando...',
'livepreview-ready' => 'Cargando… ¡Llisto!',
'livepreview-failed' => '¡La previsualización rápida falló! Intenta la previsualización normal.',
'livepreview-error' => 'Nun se pudo coneutar: $1 "$2". Intenta la previsualización normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Los cambios más nuevos que $1 {{PLURAL:$|segundu|segundos}} seique nun s\'amuesen nesta llista.',
'lag-warn-high' => "Pola mor d'un importante retrasu nel sirvidor de la base de datos, los cambios más nuevos que $1 {{PLURAL:$1|segundu|segundos}} seique nun s'amuesen nesta llista.",

# Watchlist editor
'watchlistedit-numitems' => "La to llista de vixilancia tien {{PLURAL:$1|1 títulu|$1 títulos}}, escluyendo les páxines d'alderique.",
'watchlistedit-noitems' => 'La to llista de vixilancia nun tien títulos.',
'watchlistedit-normal-title' => 'Editar la llista de vixilancia',
'watchlistedit-normal-legend' => 'Eliminar títulos de la llista de vixilancia',
'watchlistedit-normal-explain' => "Abaxo s'amuesen los títulos de la to llista de vixilancia.
Pa desaniciar un títulu, marca la caxella d'al llau d'él, y calca \"{{int:Watchlistedit-normal-submit}}\".
Tamién pues [[Special:EditWatchlist/raw|editar la llista en bruto]].",
'watchlistedit-normal-submit' => 'Desaniciar títulos',
'watchlistedit-normal-done' => '{{PLURAL:$1|Eliminóse un títulu|Elimináronse $1 títulos}} de la to llista de vixilancia:',
'watchlistedit-raw-title' => 'Editar la llista de vixilancia en bruto',
'watchlistedit-raw-legend' => 'Editar la llista de vixilancia en bruto',
'watchlistedit-raw-explain' => "Abaxo s'amuesen los títulos de la to llista de vixilancia, y puen editase amestándolos o desaniciándolos de la llista; un títulu per llinia.
N'acabando, calca \"{{int:Watchlistedit-raw-submit}}\".
Tamién pues [[Special:EditWatchlist|usar l'editor estándar]].",
'watchlistedit-raw-titles' => 'Títulos:',
'watchlistedit-raw-submit' => 'Anovar llista de vixilancia',
'watchlistedit-raw-done' => 'La to llista de vixilancia foi actualizada.',
'watchlistedit-raw-added' => '{{PLURAL:$1|Añadióse un títulu|Añadiéronse $1 títulos}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|Eliminóse ún títulu|Elimináronse $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver cambios relevantes',
'watchlisttools-edit' => 'Ver y editar la llista de vixilancia',
'watchlisttools-raw' => 'Editar la llista de vixilancia (en bruto)',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|alderique]])',

# Core parser functions
'unknown_extension_tag' => 'Etiqueta d\'estensión "$1" desconocida',
'duplicate-defaultsort' => 'Avisu: La clave d\'ordenación predeterminada "$2" anula la clave d\'ordenación anterior "$1".',

# Special:Version
'version' => 'Versión',
'version-extensions' => 'Estensiones instalaes',
'version-specialpages' => 'Páxines especiales',
'version-parserhooks' => "Hooks d'análisis sintáuticu",
'version-variables' => 'Variables',
'version-antispam' => 'Prevención del corréu puxarra',
'version-skins' => 'Apariencia',
'version-other' => 'Otros',
'version-mediahandlers' => "Remanadores d'archivos multimedia",
'version-hooks' => 'Hooks',
'version-extension-functions' => "Funciones d'estensiones",
'version-parser-extensiontags' => "Etiquetes d'estensiones d'análisis",
'version-parser-function-hooks' => "Hooks de les funciones d'análisis sintáuticu",
'version-hook-name' => 'Nome del hook',
'version-hook-subscribedby' => 'Suscritu por',
'version-version' => '(Versión $1)',
'version-license' => 'Llicencia',
'version-poweredby-credits' => "Esta wiki funciona con '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'otros',
'version-license-info' => "MediaWiki ye software llibre; pues redistribuilu y/o camudalu baxo los términos de la Llicencia Pública Xeneral GNU tal como ta asoleyada pola Free Software Foundation; o la versión 2 de la Llicencia, o (como prefieras) cualesquier versión posterior.

MediaWiki se distribúi col envís de que seya afayadiza, pero ENSIN GARANTÍA DALA; ensin siquiera garantía implícita de COMERCIALIDÁ o ADAUTACIÓN A UN DETERMINÁU PROPÓSITU. Llee la Llicencia Pública Xeneral GNU pa más detalles.

Tendríes d'haber recibío [{{SERVER}}{{SCRIPTPATH}}/COPYING una copia de la Llicencia Pública Xeneral GNU] xunto con esti programa; sinón, escribi a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA o [//www.gnu.org/licenses/old-licenses/gpl-2.0.html lleela en llinia].",
'version-software' => 'Software instaláu',
'version-software-product' => 'Productu',
'version-software-version' => 'Versión',
'version-entrypoints' => "URLs del puntu d'entrada",
'version-entrypoints-header-entrypoint' => "Puntu d'entrada",
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => "Ruta d'archivu",
'filepath-page' => 'Ficheru:',
'filepath-submit' => 'Dir',
'filepath-summary' => "Esta páxina especial devuelve la ruta completa d'un archivu.
Les imáxenes amuésense a resolución completa; les demás tribes d'archivu execútense direutamente col so programa asociáu.",

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Buscar archivos duplicaos',
'fileduplicatesearch-summary' => 'Busca archivos duplicaos basándose nos sos valores fragmentarios.',
'fileduplicatesearch-legend' => 'Buscar duplicaos',
'fileduplicatesearch-filename' => 'Nome del ficheru:',
'fileduplicatesearch-submit' => 'Guetar',
'fileduplicatesearch-info' => '$1 × $2 píxeles<br />Tamañu del archivu: $3<br />Triba MIME: $4',
'fileduplicatesearch-result-1' => 'L\'archivu "$1" nun tien duplicáu idénticu.',
'fileduplicatesearch-result-n' => 'L\'archivu "$1" tien {{PLURAL:$2|un duplicáu idénticu|$2 duplicaos idénticos}}.',
'fileduplicatesearch-noresults' => "Nun s'alcontró dengún ficheru nomáu «$1».",

# Special:SpecialPages
'specialpages' => 'Páxines especiales',
'specialpages-note' => '----
* Páxines especiales normales.
* <span class="mw-specialpagerestricted">Páxines especiales restrinxíes.</span>
* <span class="mw-specialpagecached">Páxines especiales en caché (seique nun tean actualizaes).</span>',
'specialpages-group-maintenance' => 'Informes de mantenimientu',
'specialpages-group-other' => 'Otres páxines especiales',
'specialpages-group-login' => 'Identificase / crear cuenta',
'specialpages-group-changes' => 'Cambeos recientes y rexistros',
'specialpages-group-media' => 'Informes multimedia y xubíes',
'specialpages-group-users' => 'Usuarios y drechos',
'specialpages-group-highuse' => 'Páxines mui usaes',
'specialpages-group-pages' => 'Llistes de páxines',
'specialpages-group-pagetools' => 'Ferramientes de páxina',
'specialpages-group-wiki' => 'Datos y ferramientes',
'specialpages-group-redirects' => 'Páxines especiales de redireición',
'specialpages-group-spam' => 'Ferramientes pa spam',

# Special:BlankPage
'blankpage' => 'Páxina en blanco',
'intentionallyblankpage' => 'Esta páxina ta en blanco arrémente',

# External image whitelist
'external_image_whitelist' => "#Dexa esta llinia exautamente como ta<pre>
#Pon los fragmentos d'espresiones regulares (namái la parte que va ente les //) debaxo
#Esto va ser comprobao coles URLs d'imáxenes esternes (hotlinked)
#Les que concuayen se van amosar como imáxenes; si nun concuayen, namái se va amosar un enllaz a la imaxe
#Les llinies qu'emprimen con # se traten como comentarios
#Esto nun ye sensible a la capitalización

#Pon tolos fragmentos regex enantes d'esta llinia. Dexa esta llinia exautamente como ta</pre>",

# Special:Tags
'tags' => 'Etiquetes de cambiu válides',
'tag-filter' => "Filtru d'[[Special:Tags|etiquetes]]:",
'tag-filter-submit' => 'Peñera',
'tags-title' => 'Etiquetes',
'tags-intro' => "Esta páxina llista les etiquetes coles que'l software pue marcar una edición, y el so significáu.",
'tags-tag' => "Nome d'etiqueta",
'tags-display-header' => 'Aspeutu nes llistes de cambios',
'tags-description-header' => 'Descripción completa del significáu',
'tags-hitcount-header' => 'Cambios etiquetaos',
'tags-edit' => 'editar',
'tags-hitcount' => '$1 {{PLURAL:$1|cambiu|cambios}}',

# Special:ComparePages
'comparepages' => 'Comparar páxines',
'compare-selector' => 'Comparar revisiones de páxina',
'compare-page1' => 'Páxina 1',
'compare-page2' => 'Páxina 2',
'compare-rev1' => 'Revisión 1',
'compare-rev2' => 'Revisión 2',
'compare-submit' => 'Comparar',
'compare-invalid-title' => 'El títulu que conseñasti nun ye válidu.',
'compare-title-not-exists' => 'El títulu que conseñasti nun esiste.',
'compare-revision-not-exists' => 'La revisión que conseñasti nun esiste.',

# Database error messages
'dberr-header' => 'Esta wiki tien un problema',
'dberr-problems' => '¡Sentímoslo! Esti sitiu ta esperimentando dificultaes téuniques.',
'dberr-again' => 'Tenta esperar dellos minutos y recargar.',
'dberr-info' => '(Nun se pue contautar cola base de datos del sirvidor: $1)',
'dberr-usegoogle' => 'Pues probar a guetar con Google mentanto.',
'dberr-outofdate' => 'Atalanta que los sos índices del nuesu conteníu seique nun tean actualizaos.',
'dberr-cachederror' => 'Esta ye una copia na caché de la páxina que se pidiera, y pue que nun tea actualizada.',

# HTML forms
'htmlform-invalid-input' => 'Hai problemes con parte de la to entrada',
'htmlform-select-badoption' => 'El valor que conseñasti nun ye una opción válida.',
'htmlform-int-invalid' => 'El valor que conseñasti nun ye un númberu enteru.',
'htmlform-float-invalid' => 'El valor que conseñasti nun ye un númberu.',
'htmlform-int-toolow' => "El valor que conseñasti ye menor que'l mínimu de $1",
'htmlform-int-toohigh' => "El valor que conseñasti ye mayor que'l máximu de $1",
'htmlform-required' => 'Se requier esti valor',
'htmlform-submit' => 'Unviar',
'htmlform-reset' => 'Desfacer los cambios',
'htmlform-selectorother-other' => 'Otros',

# SQLite database support
'sqlite-has-fts' => '$1 con sofitu pa gueta en testu completu',
'sqlite-no-fts' => '$1 ensin sofitu pa gueta en testu completu',

# New logging system
'logentry-delete-delete' => '$1 desanició la páxina $3',
'logentry-delete-restore' => '$1 restauró la páxina $3',
'logentry-delete-event' => "$1 camudó la visibilidá {{PLURAL:$5|d'un socesu del rexistru|de $5 socesos del rexistru}} en $3: $4",
'logentry-delete-revision' => "$1 camudó la visibilidá {{PLURAL:$5|d'una revisión|de $5 revisiones}} na páxina $3: $4",
'logentry-delete-event-legacy' => '$1 camudó la visibilidá de los socesos del rexistru en $3',
'logentry-delete-revision-legacy' => '$1 camudó la visibilidá de les revisiones na páxina $3',
'logentry-suppress-delete' => '$1 suprimió la páxina $3',
'logentry-suppress-event' => "$1 camudó en secretu la visibilidá {{PLURAL:$5|d'un socesu del rexistru|de $5 socesos del rexistru}} en $3: $4",
'logentry-suppress-revision' => "$1 camudó en secretu la visibilidá {{PLURAL:$5|d'una revisión|de $5 revisiones}} na páxina $3: $4",
'logentry-suppress-event-legacy' => '$1 camudó en secretu la visibilidá de los socesos del rexistru en $3',
'logentry-suppress-revision-legacy' => '$1 camudó en secretu la visibilidá de les revisiones na páxina $3',
'revdelete-content-hid' => 'conteníu tapecíu',
'revdelete-summary-hid' => "resume d'edición tapecíu",
'revdelete-uname-hid' => "nome d'usuariu anubríu",
'revdelete-content-unhid' => 'conteníu non tapecíu',
'revdelete-summary-unhid' => "resume d'edición non tapecíu",
'revdelete-uname-unhid' => "nome d'usuariu non anubríu",
'revdelete-restricted' => 'aplicaes les restricciones a los alministradores',
'revdelete-unrestricted' => 'eliminaes les restricciones a los alministradores',
'logentry-move-move' => '$1 treslladó la páxina "$3" a "$4"',
'logentry-move-move-noredirect' => '$1 treslladó la páxina "$3" a "$4" ensin dexar una redireición',
'logentry-move-move_redir' => '$1 treslladó la páxina "$3" a "$4" sobre una redireición',
'logentry-move-move_redir-noredirect' => '$1 treslladó la páxina "$3" a "$4" sobre una redireición ensin dexar una redireición',
'logentry-patrol-patrol' => '$1 marcó la revisión $4 de la páxina "$3" como patrullada',
'logentry-patrol-patrol-auto' => '$1 marcó automaticamente la revisión $4 de la páxina "$3" como patrullada',
'logentry-newusers-newusers' => "Se creó la cuenta d'usuariu $1",
'logentry-newusers-create' => "Se creó la cuenta d'usuariu $1",
'logentry-newusers-create2' => "$1 creó la cuenta d'usuariu $3",
'logentry-newusers-autocreate' => 'La cuenta $1 se creó automáticamente',
'newuserlog-byemail' => 'conseña unviada per corréu electrónicu',

# Feedback
'feedback-bugornote' => 'Si tas preparáu pa describir un problema técnicu en detalle, [$1 informa del fallu].
D\'otra miente, pues usar el formulariu cenciellu d\'abaxo. El to comentariu apaecerá na páxina "[$3 $2]" xunto col to nome d\'usuariu y el restolador qu\'uses.',
'feedback-subject' => 'Asuntu:',
'feedback-message' => 'Mensaxe:',
'feedback-cancel' => 'Encaboxar',
'feedback-submit' => 'Unviar comentarios',
'feedback-adding' => 'Amestando el comentariu a la páxina...',
'feedback-error1' => 'Fallu: Resultáu de la API non reconocíu',
'feedback-error2' => 'Fallu: Falló la edición',
'feedback-error3' => 'Fallu: Ensin respuesta de la API',
'feedback-thanks' => "¡Gracies! La to opinión s'espublizó na páxina «[$2  $1]».",
'feedback-close' => 'Fecho',
'feedback-bugcheck' => '¡Perfeuto! Comprueba que nun tea yá ente los [$1 fallos conocíos].',
'feedback-bugnew' => "Yá lo comprobé. Informar d'esti fallu nuevu",

# Search suggestions
'searchsuggest-search' => 'Guetar',
'searchsuggest-containing' => 'que contién...',

# API errors
'api-error-badaccess-groups' => 'Nun tienes permisu pa xubir ficheros a esta wiki.',
'api-error-badtoken' => 'Fallu internu: token incorreutu.',
'api-error-copyuploaddisabled' => "Xubir d'una URL ta desactivao nesti sirvidor.",
'api-error-duplicate' => 'Yá hai {{PLURAL:$1|[$2 otru ficheru]|[$2 otros ficheros]}} nesti sitiu col mesmu conteníu.',
'api-error-duplicate-archive' => 'Había {{PLURAL:$1|[$2 otru ficheru]|[$2 otros ficheros]}} nesti sitiu col mesmu conteníu, pero se {{PLURAL:$1|desanició|desaniciaron}}.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Ficheru duplicáu|Ficheros duplicaos}} que yá se {{PLURAL:$1|desanició|desaniciaron}}',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Ficheru duplicáu|Ficheros duplicaos}}',
'api-error-empty-file' => "El ficheru qu'unviasti taba baleru.",
'api-error-emptypage' => 'Nun se permite la creación de páxines nueves baleres.',
'api-error-fetchfileerror' => 'Fallu internu: daqué nun funcionó al buscar el ficheru.',
'api-error-fileexists-forbidden' => 'Yá esiste un ficheru col nome «$1» y nun se pue sobrescribir.',
'api-error-fileexists-shared-forbidden' => 'Yá esiste un ficheru col nome «$1» nel repositoriu de ficheros compartíos, y nun se pue sobrescribir.',
'api-error-file-too-large' => "El ficheru qu'unviasti yera demasiao grande.",
'api-error-filename-tooshort' => 'El nome de ficheru ye demasiao curtiu.',
'api-error-filetype-banned' => 'Esta triba de ficheru ta torgada.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|nun ye una triba de ficheru permitida|nun son tribes de ficheru permitíes}}. {{PLURAL:$3|La triba de ficheru permitida ye|Les tribes de ficheru permitíes son}} $2.',
'api-error-filetype-missing' => 'Al ficheru falta-y una estensión.',
'api-error-hookaborted' => 'La conexón con una estensión encaboxó el cambéu que tentasti facer.',
'api-error-http' => 'Fallu internu: Nun se pudo coneutar col sirvidor.',
'api-error-illegal-filename' => 'El nome de ficheru nun ta permitíu.',
'api-error-internal-error' => 'Fallu internu: daqué nun funcionó al procesar la xubida na wiki.',
'api-error-invalid-file-key' => "Fallu internu: nun s'atopó el ficheru nel depósitu temporal.",
'api-error-missingparam' => 'Fallu internu: falten parámetros na solicitú.',
'api-error-missingresult' => 'Fallu internu: nun se pudo determinar si la copia foi bien.',
'api-error-mustbeloggedin' => 'Tienes de tar autenticáu pa xubir ficheros.',
'api-error-mustbeposted' => 'Fallu internu: la solicitú necesita HTTP POST.',
'api-error-noimageinfo' => 'La xubida foi correuta, pero el sirvidor nun dio información dala del ficheru.',
'api-error-nomodule' => 'Fallu internu: nun se configuró dengún módulu de xubíes.',
'api-error-ok-but-empty' => 'Fallu internu: nun hai respuesta del sirvidor.',
'api-error-overwrite' => 'Nun ta permitío sobroscribir un ficheru esistente.',
'api-error-stashfailed' => 'Fallu internu: el sirvidor nun pudo guardar el ficheru temporal.',
'api-error-timeout' => 'El sirvidor nun respondió nel tiempu esperáu.',
'api-error-unclassified' => 'Hebo un fallu desconocíu',
'api-error-unknown-code' => 'Fallu desconocíu: «$1»',
'api-error-unknown-error' => 'Fallu internu: daqué nun funcionó al tentar xubir el ficheru.',
'api-error-unknown-warning' => 'Avisu desconocíu: $1',
'api-error-unknownerror' => 'Fallu desconocíu: «$1».',
'api-error-uploaddisabled' => 'Les xubíes tan desactivaes nesta wiki.',
'api-error-verification-error' => 'Esti ficheru pudiera tar corrompíu, o tien una estensión incorreuta.',

# Durations
'duration-seconds' => '$1 {{PLURAL: $1|segundu|segundos}}',
'duration-minutes' => '$1 {{PLURAL: $1|minutu|minutos}}',
'duration-hours' => '$1 {{PLURAL: $1|hora|hores}}',
'duration-days' => '$1 {{PLURAL: $1|día|díes}}',
'duration-weeks' => '$1 {{PLURAL: $1|selmana|selmanes}}',
'duration-years' => '$1 {{PLURAL: $1|añu|años}}',
'duration-decades' => '$1 {{PLURAL:$1|década|décades}}',
'duration-centuries' => '$1 {{PLURAL:$1|sieglu|sieglos}}',
'duration-millennia' => '$1 {{PLURAL:$1|mileniu|milenios}}',

);
