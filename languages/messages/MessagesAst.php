<?php
/** Asturian (Asturianu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Esbardu
 * @author Mikel
 * @author Remember the dot
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

$messages = array(
# User preference toggles
'tog-underline'               => 'Sorrayar enllaces:',
'tog-highlightbroken'         => 'Da-y formatu a los enllaces rotos <a href="" class="new">como esti</a> (caxella desactivada: como esti<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Xustificar parágrafos',
'tog-hideminor'               => 'Esconder ediciones menores nos cambeos recientes',
'tog-hidepatrolled'           => 'Atapecer les ediciones vixilaes nos cambeos recientes',
'tog-newpageshidepatrolled'   => 'Atapecer les páxines vixilaes na llista de páxines nueves',
'tog-extendwatchlist'         => "Espander la llista de vixilancia p'amosar tolos cambeos aplicables, nun sólo los recientes.",
'tog-usenewrc'                => 'Cambeos recientes ameyoraos (necesita JavaScript)',
'tog-numberheadings'          => 'Autonumberar los encabezaos',
'tog-showtoolbar'             => "Amosar la barra de ferramientes d'edición (JavaScript)",
'tog-editondblclick'          => 'Editar páxines con doble clic (JavaScript)',
'tog-editsection'             => "Activar la edición de seiciones per aciu d'enllaces [editar]",
'tog-editsectiononrightclick' => 'Activar la edición de seiciones calcando col botón<br /> drechu enriba los títulos de seición (JavaScript)',
'tog-showtoc'                 => 'Amosar índiz (pa páxines con más de 3 encabezaos)',
'tog-rememberpassword'        => 'Recordar la clave ente sesiones (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Añader les páxines que creo a la mio llista de vixilancia',
'tog-watchdefault'            => "Añader les páxines qu'edito a la mio llista de vixilancia",
'tog-watchmoves'              => 'Añader les páxines que muevo a la mio llista de vixilancia',
'tog-watchdeletion'           => "Añader les páxines qu'esborro a la mio llista de vixilancia",
'tog-minordefault'            => 'Marcar toles ediciones como menores por defeutu',
'tog-previewontop'            => "Amosar previsualización enantes de la caxa d'edición",
'tog-previewonfirst'          => 'Amosar previsualización na primer edición',
'tog-nocache'                 => 'Desactivar la caché de les páxines',
'tog-enotifwatchlistpages'    => 'Mandame un corréu cuando cambie una páxina de la mio llista de vixilancia',
'tog-enotifusertalkpages'     => "Mandame un corréu cuando camude la mio páxina d'alderique",
'tog-enotifminoredits'        => 'Mandame tamién un corréu pa les ediciones menores',
'tog-enotifrevealaddr'        => 'Amosar el mio corréu electrónicu nos correos de notificación',
'tog-shownumberswatching'     => "Amosar el númberu d'usuarios que la tán vixilando",
'tog-fancysig'                => 'Usar la firma como "testu wiki" (ensin enllaz automáticu)',
'tog-externaleditor'          => 'Usar un editor esternu por defeutu (namái pa espertos, necesita configuraciones especiales nel to ordenador)',
'tog-externaldiff'            => "Usar ''diff'' esternu por defeutu (namái pa espertos, necesita configuraciones especiales nel to ordenador)",
'tog-showjumplinks'           => 'Activar los enllaces d\'accesibilidá "saltar a"',
'tog-uselivepreview'          => 'Usar vista previa en direutu (JavaScript) (en pruebes)',
'tog-forceeditsummary'        => "Avisame cuando grabe col resume d'edición en blanco",
'tog-watchlisthideown'        => 'Esconder les mios ediciones na llista de vixilancia',
'tog-watchlisthidebots'       => 'Esconder les ediciones de bots na llista de vixilancia',
'tog-watchlisthideminor'      => 'Esconder les ediciones menores na llista de vixilancia',
'tog-watchlisthideliu'        => "Ocultar ediciones d'usuarios rexistraos na llista de vixilancia",
'tog-watchlisthideanons'      => "Ocultar ediciones d'usuarios anónimos na llista de vixilancia",
'tog-watchlisthidepatrolled'  => 'Anubrir les ediciones patrullaes de la llista de vixilancia',
'tog-nolangconversion'        => 'Deshabilitar la conversión de variantes de llingua',
'tog-ccmeonemails'            => 'Mandame copies de los correos que mando a otros usuarios',
'tog-diffonly'                => 'Nun amosar el conteníu de la páxina embaxo de les diferencies',
'tog-showhiddencats'          => 'Amosar categoríes ocultes',
'tog-norollbackdiff'          => 'Desanicier les diferencies depués de restaurar',

'underline-always'  => 'Siempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Valor por defeutu del navegador',

# Dates
'sunday'        => 'domingu',
'monday'        => 'llunes',
'tuesday'       => 'martes',
'wednesday'     => 'miércoles',
'thursday'      => 'xueves',
'friday'        => 'vienres',
'saturday'      => 'sábadu',
'sun'           => 'dom',
'mon'           => 'llu',
'tue'           => 'mar',
'wed'           => 'mié',
'thu'           => 'xue',
'fri'           => 'vie',
'sat'           => 'sáb',
'january'       => 'xineru',
'february'      => 'febreru',
'march'         => 'marzu',
'april'         => 'abril',
'may_long'      => 'mayu',
'june'          => 'xunu',
'july'          => 'xunetu',
'august'        => 'agostu',
'september'     => 'setiembre',
'october'       => 'ochobre',
'november'      => 'payares',
'december'      => 'avientu',
'january-gen'   => 'xineru',
'february-gen'  => 'febreru',
'march-gen'     => 'marzu',
'april-gen'     => 'abril',
'may-gen'       => 'mayu',
'june-gen'      => 'xunu',
'july-gen'      => 'xunetu',
'august-gen'    => 'agostu',
'september-gen' => 'setiembre',
'october-gen'   => 'ochobre',
'november-gen'  => 'payares',
'december-gen'  => 'avientu',
'jan'           => 'xin',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'abr',
'may'           => 'may',
'jun'           => 'xun',
'jul'           => 'xnt',
'aug'           => 'ago',
'sep'           => 'set',
'oct'           => 'och',
'nov'           => 'pay',
'dec'           => 'avi',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoría|Categoríes}}',
'category_header'                => 'Páxines na categoría "$1"',
'subcategories'                  => 'Subcategoríes',
'category-media-header'          => 'Archivos multimedia na categoría "$1"',
'category-empty'                 => "''Esta categoría nun tien anguaño nengún artículu o ficheru multimedia.''",
'hidden-categories'              => '{{PLURAL:$1|Categoría oculta|Categoríes ocultes}}',
'hidden-category-category'       => 'Categoríes ocultes',
'category-subcat-count'          => "{{PLURAL:$2|Esta categoría namái tien la subcategoría siguiente.|Esta categoría tien {{PLURAL:$1|la siguiente subcategoría|les siguientes $1 subcategoríes}}, d'un total de $2.}}",
'category-subcat-count-limited'  => 'Esta categoría tien {{PLURAL:$1|la siguiente subcategoría|les siguientes $1 subcategoríes}}.',
'category-article-count'         => "{{PLURAL:$2|Esta categoría contién namái la páxina siguiente.|{{PLURAL:$1|La siguiente páxina ta|Les $1 páxines siguientes tán}} nesta categoría, d'un total de $2.}}",
'category-article-count-limited' => '{{PLURAL:$1|La siguiente páxina ta|Les siguientes $1 páxines tán}} na categoría actual.',
'category-file-count'            => "{{PLURAL:$2|Esta categoría contién namái l'archivu siguiente.|{{PLURAL:$1|L'archivu siguiente ta|Los $1 archivos siguientes tán}} nesta categoría, d'un total de $2.}}",
'category-file-count-limited'    => '{{PLURAL:$1|El siguiente archivu ta|Los siguientes $1 archivos tán}} na categoría actual.',
'listingcontinuesabbrev'         => 'cont.',

'mainpagetext'      => "'''MediaWiki instalóse correchamente.'''",
'mainpagedocfooter' => "Visita la [http://meta.wikimedia.org/wiki/Help:Contents Guía d'usuariu] pa saber cómo usar esti software wiki.

== Empecipiando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Llista de les opciones de configuración]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ de MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Llista de corréu de les ediciones de MediaWiki]",

'about'         => 'Tocante a',
'article'       => 'Conteníu de la páxina',
'newwindow'     => '(abriráse nuna ventana nueva)',
'cancel'        => 'Cancelar',
'moredotdotdot' => 'Más...',
'mypage'        => 'La mio páxina',
'mytalk'        => "La mio páxina d'alderique",
'anontalk'      => 'Alderique pa esta IP',
'navigation'    => 'Navegación',
'and'           => '&#32;y',

# Cologne Blue skin
'qbfind'         => 'Alcontrar',
'qbbrowse'       => 'Escartafoyar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Esta páxina',
'qbpageinfo'     => 'Contestu',
'qbmyoptions'    => 'Les mios páxines',
'qbspecialpages' => 'Páxines especiales',
'faq'            => 'FAQ',
'faqpage'        => 'Project:Entrugues más frecuentes',

'errorpagetitle'    => 'Error',
'returnto'          => 'Vuelve a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Aida',
'search'            => 'Buscar',
'searchbutton'      => 'Buscar',
'go'                => 'Dir',
'searcharticle'     => 'Dir',
'history'           => 'Historial de la páxina',
'history_short'     => 'Historial',
'updatedmarker'     => 'actualizáu dende la mio última visita',
'info_short'        => 'Información',
'printableversion'  => 'Versión pa imprentar',
'permalink'         => 'Enllaz permanente',
'print'             => 'Imprentar',
'edit'              => 'Editar',
'create'            => 'Crear',
'editthispage'      => 'Editar esta páxina',
'create-this-page'  => 'Crear esta páxina',
'delete'            => 'Esborrar',
'deletethispage'    => 'Esborrar esta páxina',
'undelete_short'    => 'Restaurar {{PLURAL:$1|una edición|$1 ediciones}}',
'protect'           => 'Protexer',
'protect_change'    => 'camudar',
'protectthispage'   => 'Protexer esta páxina',
'unprotect'         => 'Desprotexer',
'unprotectthispage' => 'Desprotexer esta páxina',
'newpage'           => 'Páxina nueva',
'talkpage'          => 'Aldericar sobre esta páxina',
'talkpagelinktext'  => 'Alderique',
'specialpage'       => 'Páxina especial',
'personaltools'     => 'Ferramientes personales',
'postcomment'       => 'Seición nueva',
'articlepage'       => 'Ver conteníu de la páxina',
'talk'              => 'Alderique',
'views'             => 'Vistes',
'toolbox'           => 'Ferramientes',
'userpage'          => "Ver páxina d'usuariu",
'projectpage'       => 'Ver la páxina de proyeutu',
'imagepage'         => 'Ver la páxina de ficheros',
'mediawikipage'     => 'Ver la páxina de mensaxe',
'templatepage'      => 'Ver la páxina de plantía',
'viewhelppage'      => "Ver la páxina d'aida",
'categorypage'      => 'Ver páxina de categoríes',
'viewtalkpage'      => 'Ver alderique',
'otherlanguages'    => 'Otres llingües',
'redirectedfrom'    => '(Redirixío dende $1)',
'redirectpagesub'   => 'Páxina de redireición',
'lastmodifiedat'    => "Esta páxina foi modificada per postrer vegada'l $1 a les $2.",
'viewcount'         => 'Esta páxina foi vista {{PLURAL:$1|una vegada|$1 vegaes}}.',
'protectedpage'     => 'Páxina protexida',
'jumpto'            => 'Saltar a:',
'jumptonavigation'  => 'navegación',
'jumptosearch'      => 'busca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tocante a {{SITENAME}}',
'aboutpage'            => 'Project:Tocante a',
'copyright'            => 'Esti conteníu ta disponible baxo los términos de la  $1.',
'copyrightpage'        => "{{ns:project}}:Derechos d'autor",
'currentevents'        => 'Fechos actuales',
'currentevents-url'    => 'Project:Fechos actuales',
'disclaimers'          => 'Avisu llegal',
'disclaimerpage'       => 'Project:Llimitación xeneral de responsabilidá',
'edithelp'             => "Aida d'edición",
'edithelppage'         => 'Help:Edición de páxines',
'helppage'             => 'Help:Conteníos',
'mainpage'             => 'Portada',
'mainpage-description' => 'Portada',
'policy-url'           => 'Project:Polítiques',
'portal'               => 'Portal de la comunidá',
'portal-url'           => 'Project:Portal de la comunidá',
'privacy'              => 'Politica de privacidá',
'privacypage'          => 'Project:Política de privacidá',

'badaccess'        => 'Error de permisos',
'badaccess-group0' => "Nun tienes permisu pa executar l'aición solicitada.",
'badaccess-groups' => "L'aición solicitada ta llimitada a usuarios {{PLURAL:$2|del grupu|d'ún de los grupos}}: $1.",

'versionrequired'     => 'Necesítase la versión $1 de MediaWiki',
'versionrequiredtext' => 'Necesítase la versión $1 de MediaWiki pa usar esta páxina. Ver la [[Special:Version|páxina de versión]].',

'ok'                      => 'Aceutar',
'retrievedfrom'           => 'Obtenío de "$1"',
'youhavenewmessages'      => 'Tienes $1 ($2).',
'newmessageslink'         => 'mensaxes nuevos',
'newmessagesdifflink'     => 'últimu cambéu',
'youhavenewmessagesmulti' => 'Tienes mensaxes nuevos en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'viewsourceold'           => 'ver fonte',
'editlink'                => 'editar',
'viewsourcelink'          => 'amosar la fonte',
'editsectionhint'         => 'Editar seición: $1',
'toc'                     => 'Tabla de conteníos',
'showtoc'                 => 'amosar',
'hidetoc'                 => 'esconder',
'thisisdeleted'           => '¿Ver o restaurar $1?',
'viewdeleted'             => '¿Ver $1?',
'restorelink'             => '{{PLURAL:$1|una edición esborrada|$1 ediciones esborraes}}',
'feedlinks'               => 'Canal:',
'feed-invalid'            => 'Suscripción non válida a la triba de canal.',
'feed-unavailable'        => 'Les canales de sindicación nun tán disponibles',
'site-rss-feed'           => 'Canal RSS $1',
'site-atom-feed'          => 'Canal Atom $1',
'page-rss-feed'           => 'Canal RSS "$1"',
'page-atom-feed'          => 'Canal Atom "$1"',
'red-link-title'          => '$1 (la páxina nun esiste)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Páxina',
'nstab-user'      => "Páxina d'usuariu",
'nstab-media'     => "Páxina d'archivu multimedia",
'nstab-special'   => 'Páxina especial',
'nstab-project'   => 'Páxina de proyeutu',
'nstab-image'     => 'Archivu',
'nstab-mediawiki' => 'Mensaxe',
'nstab-template'  => 'Plantía',
'nstab-help'      => 'Aida',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'Nun esiste esa aición',
'nosuchactiontext'  => "L'aición especificada pola URL nun ye válida.
Seique escribieras mal la URL o siguieras un enllaz incorreutu.
Tamién podría ser un bug nel software usáu por {{SITENAME}}.",
'nosuchspecialpage' => 'Nun esiste esa páxina especial',
'nospecialpagetext' => '<strong>Pidisti una páxina especial non válida.</strong>

Pues consultar la llista de les páxines especiales válides en [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Error',
'databaseerror'        => 'Error na base de datos',
'dberrortext'          => 'Hebo un fallu de sintaxis nuna consulta de la base de datos.
Esti fallu puede ser por un problema del software.
La postrer consulta que s\'intentó foi:
<blockquote><tt>$1</tt></blockquote>
dende la función "<tt>$2</tt>".
La base datos dió el fallu "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Hebo un fallu de sintaxis nuna consulta a la base de datos.
La postrer consulta que s\'intentó foi:
"$1"
dende la función "$2".
La base de datos dió\'l fallu "$3: $4"',
'laggedslavemode'      => 'Avisu: Esta páxina pue que nun tenga actualizaciones recientes.',
'readonly'             => 'Base de datos candada',
'enterlockreason'      => 'Introduz un motivu pal candáu, amiestando una estimación de cuándo va ser llevantáu esti',
'readonlytext'         => "Nestos momentos la base de datos ta candada pa nueves entraes y otres modificaciones, seique por un mantenimientu de rutina, depués d'él tará accesible de nuevo.

L'alministrador que la candó conseñó esti motivu: $1",
'missing-article'      => "La base de datos nun atopó'l testu d'una páxina qu'habría tener atopao, nomada \"\$1\" \$2.

Esto débese davezu a siguir una dif caducada o un enllaz d'historial a una páxina que foi esborrada.

Si esti nun ye'l casu, seique tengas atopao un bug nel software.
Por favor informa d'esto a un [[Special:ListUsers/sysop|alministrador]], anotando la URL.",
'missingarticle-rev'   => '(revisión: $1)',
'missingarticle-diff'  => '(dif: $1, $2)',
'readonly_lag'         => 'La base de datos foi candada automáticamente mentes los sirvidores de la base de datos esclava se sincronicen cola maestra',
'internalerror'        => 'Error internu',
'internalerror_info'   => 'Error internu: $1',
'filecopyerror'        => 'Nun se pudo copiar l\'archivu "$1" como "$2".',
'filerenameerror'      => 'Nun se pudo renomar l\'archivu "$1" como "$2".',
'filedeleteerror'      => 'Nun se pudo esborrar l\'archivu "$1".',
'directorycreateerror' => 'Nun se pudo crear el direutoriu "$1".',
'filenotfound'         => 'Nun se pudo atopar l\'archivu "$1".',
'fileexistserror'      => 'Nun se pue escribir nel archivu "$1": yá esiste',
'unexpected'           => 'Valor inesperáu: "$1"="$2".',
'formerror'            => 'Error: nun se pudo unviar el formulariu',
'badarticleerror'      => 'Esta aición nun pue facese nesta páxina',
'cannotdelete'         => 'Nun pudo desaniciase la páxina o el ficheru "$1".
Seique daquién yá la desaniciara.',
'badtitle'             => 'Títulu incorreutu',
'badtitletext'         => 'El títulu de páxina solicitáu nun ye válidu, ta vaciu o tien enllaces inter-llingua o inter-wiki incorreutos. Pue que contenga ún o más carauteres que nun puen ser usaos nos títulos.',
'perfcached'           => 'Los siguientes datos tán na caché y pue que nun tean completamente actualizaos.',
'perfcachedts'         => "Los siguientes datos tán na caché y actualizáronse la última vegada'l $1.",
'querypage-no-updates' => "Les actualizaciones d'esta páxina tán actualmente deshabilitaes. Los datos qu'hai equí nun sedrán refrescaos nestos momentos.",
'wrong_wfQuery_params' => 'Parámetros incorreutos pa wfQuery()<br />
Función: $1<br />
Consulta: $2',
'viewsource'           => 'Ver códigu fonte',
'viewsourcefor'        => 'pa $1',
'actionthrottled'      => 'Aición llimitada',
'actionthrottledtext'  => "Como midida anti-spam, nun pues facer esta aición munches vegaes en pocu tiempu, y trespasasti esi llímite. Por favor inténtalo de nuevo dientro d'unos minutos.",
'protectedpagetext'    => 'Esta páxina foi candada pa evitar la so edición.',
'viewsourcetext'       => "Pues ver y copiar el códigu fonte d'esta páxina:",
'protectedinterface'   => "Esta páxina proporciona testu d'interfaz a l'aplicación y ta candada pa evitar el so abusu.",
'editinginterface'     => "'''Avisu:''' Tas editando una páxina usada pa proporcionar testu d'interfaz a l'aplicación. Los cambeos nesta páxina va afeuta-yos l'apariencia de la interfaz a otros usuarios. Si quies facer traducciones, por favor participa en [http://translatewiki.net/wiki/Main_Page?setlang=ast translatewiki.net], el proyeutu de traducción de MediaWiki.",
'sqlhidden'            => '(consulta SQL escondida)',
'cascadeprotected'     => 'Esta páxina ta protexida d\'ediciones porque ta enxerta {{PLURAL:$1|na siguiente páxina|nes siguientes páxines}}, que {{PLURAL:$1|ta protexida|tán protexíes}} cola opción "en cascada":
$2',
'namespaceprotected'   => "Nun tienes permisu pa editar páxines nel espaciu de nomes '''$1'''.",
'customcssjsprotected' => "Nun tienes permisu pa editar esta páxina porque contién preferencies personales d'otru usuariu.",
'ns-specialprotected'  => 'Les páxines especiales nun puen ser editaes.',
'titleprotected'       => "Esti títulu foi protexíu de la so creación por [[User:$1|$1]]. El motivu conseñáu ye ''$2''.",

# Virus scanner
'virus-badscanner'     => "Configuración errónea: escáner de virus desconocíu: ''$1''",
'virus-scanfailed'     => "fallu d'escaniáu (códigu $1)",
'virus-unknownscanner' => 'antivirus desconocíu:',

# Login and logout pages
'logouttext'                 => "'''Yá tas desconectáu.'''

Pues siguir usando {{SITENAME}} de forma anónima, o pues [[Special:UserLogin|volver a entrar]] como'l mesmu o como otru usuariu.
Ten en cuenta que dalgunes páxines van continuar saliendo como si tovía tuvieres coneutáu, hasta que llimpies la caché del navegador.",
'welcomecreation'            => "== Bienveníu, $1! ==
La to cuenta ta creada.
Nun t'escaezas d'escoyer les tos [[Special:Preferences|preferencies de {{SITENAME}}]].",
'yourname'                   => "Nome d'usuariu:",
'yourpassword'               => 'Clave:',
'yourpasswordagain'          => 'Reescribi la to clave:',
'remembermypassword'         => 'Recordar la mio identificación nesti ordenador (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'             => 'El to dominiu:',
'externaldberror'            => "O hebo un error de l'autenticación esterna de la base de datos o nun tienes permisu p'actualizar la to cuenta esterna.",
'login'                      => 'Entrar',
'nav-login-createaccount'    => 'Entrar / Crear cuenta',
'loginprompt'                => "Has tener les ''cookies'' activaes pa entrar en {{SITENAME}}.",
'userlogin'                  => 'Entrar / Crear cuenta',
'userloginnocreate'          => 'Entrar',
'logout'                     => 'Salir',
'userlogout'                 => 'Salir',
'notloggedin'                => 'Non identificáu',
'nologin'                    => "¿Nun tienes una cuenta? '''$1'''.",
'nologinlink'                => '¡Fai una!',
'createaccount'              => 'Crear una nueva cuenta',
'gotaccount'                 => "¿Ya tienes una cuenta? '''$1'''.",
'gotaccountlink'             => '¡Identifícate!',
'createaccountmail'          => 'per e-mail',
'badretype'                  => "Les claves qu'escribisti nun concuayen.",
'userexists'                 => "El nome d'usuariu conseñáu yá esiste. Por favor escueyi un nome diferente.",
'loginerror'                 => "Error d'identificación",
'nocookiesnew'               => "La cuenta d'usuariu ta creada, pero nun tas identificáu. {{SITENAME}} usa cookies pa identificar a los usuarios. Tienes les cookies deshabilitaes. Por favor actívales y depués identifícate col to nuevu nome d'usuariu y la clave.",
'nocookieslogin'             => '{{SITENAME}} usa cookies pa identificar a los usuarios. Tienes les cookies deshabilitaes. Por favor actívales y inténtalo otra vuelta.',
'noname'                     => "Nun punxisti un nome d'usuariu válidu.",
'loginsuccesstitle'          => 'Identificación correuta',
'loginsuccess'               => "'''Quedasti identificáu en {{SITENAME}} como \"\$1\".'''",
'nosuchuser'                 => 'Nun hai usuariu dalu col nome "$1".
Los nomes d\'usuariu distinguen mayúscules y minúscules.
Comprueba la ortografía o [[Special:UserLogin/signup|crea una cuenta d\'usuariu nueva]].',
'nosuchusershort'            => 'Nun hai nengún usuariu col nome "<nowiki>$1</nowiki>". Mira que tea bien escritu.',
'nouserspecified'            => "Has especificar un nome d'usuariu.",
'wrongpassword'              => 'Clave errónea.  Inténtalo otra vuelta.',
'wrongpasswordempty'         => 'La clave taba en blanco. Inténtalo otra vuelta.',
'passwordtooshort'           => "La to clave nun ye válida o ye demasiao curtia.
Ha tener a lo menos {{PLURAL:$1|1 caráuter|$1 carauteres}} y ser distinta del to nome d'usuariu.",
'mailmypassword'             => 'Unviar la clave nueva',
'passwordremindertitle'      => 'Nueva clave provisional pa {{SITENAME}}',
'passwordremindertext'       => 'Daquién (seique tu, dende la direición IP $1) solicitó una clave
nueva pa {{SITENAME}} ($4). Creóse una clave temporal pal usuariu
"$2" que ye "$3". Si fuisti tu, necesites identificate y escoyer una
clave nueva agora. La clave temporal caduca {{PLURAL:$5|nun día|en $5 díes}}.

Si daquién más fizo esta solicitú, o si recuerdes la to clave y
nun quies volver a camudala, pues inorar esti mensaxe y siguir
usando la to clave vieya.',
'noemail'                    => 'L\'usuariu "$1" nun tien puesta direición de corréu.',
'passwordsent'               => 'Unvióse una clave nueva a la direición de corréu
rexistrada pa "$1".
Por favor identifícate de nuevo depués de recibila.',
'blocked-mailpassword'       => 'La edición ta bloquiada dende la to direición IP, y por tanto
nun se pue usar la función de recuperación de clave pa evitar abusos.',
'eauthentsent'               => "Unvióse una confirmación de corréu electrónicu a la direición indicada.
Enantes de que s'unvie nengún otru corréu a la cuenta, has siguir les instrucciones del corréu electrónicu, pa confirmar que la cuenta ye de to.",
'throttled-mailpassword'     => "Yá s'unvió un recordatoriu de la clave {{PLURAL:$1|na cabera hora|nes caberes $1 hores}}.
Pa evitar l'abusu, namái sedrá unviáu un recordatoriu cada {{PLURAL:$1|hora|$1 hores}}.",
'mailerror'                  => "Error unviando'l corréu: $1",
'acct_creation_throttle_hit' => "Los visitantes d'esta wiki qu'usen la to direición IP yá crearon güei {{PLURAL:$1|1 cuenta|$1 cuentes}}, que ye'l máximu almitíu nesti periodu de tiempu.
Poro, los visitantes qu'usen esta direición IP nun pues crear más cuentes de momentu.",
'emailauthenticated'         => "La to direición de corréu confirmóse'l $2 a les $3.",
'emailnotauthenticated'      => 'La to direición de corréu nun ta comprobada. Hasta que se faiga, les siguientes funciones nun tarán disponibles.',
'noemailprefs'               => "Especifica una direición de corréu pa qu'estes funcionalidaes furrulen.",
'emailconfirmlink'           => 'Confirmar la direición de corréu',
'invalidemailaddress'        => "La direición de corréu nun se pue aceutar yá que paez tener un formatu non válidu.
Por favor escribi una direición con formatu afayadizu o dexa vaciu'l campu.",
'accountcreated'             => 'Cuenta creada',
'accountcreatedtext'         => "La cuenta d'usuariu de $1 ta creada.",
'createaccount-title'        => 'Creación de cuenta pa {{SITENAME}}',
'createaccount-text'         => 'Daquién creó una cuenta pa la to direición de corréu electrónicu en {{SITENAME}} ($4) nomada "$2", asociada a la clave "$3". Habríes identificate y camudar la to clave agora.

Pues inorar esti mensaxe si la cuenta foi creada por error.',
'login-throttled'            => "Ficisti demasiaos intentos recientes cola clave d'esta cuenta. Por favor espera enantes d'intentalo otra vuelta.",
'loginlanguagelabel'         => 'Llingua: $1',

# Password reset dialog
'resetpass'                 => 'Camudar la clave',
'resetpass_announce'        => "Identificástiti con una clave temporal unviada per corréu. P'acabar d'identificate has escribir equí una clave nueva:",
'resetpass_header'          => 'Camudar la clave de la cuenta',
'oldpassword'               => 'Clave vieya:',
'newpassword'               => 'Clave nueva:',
'retypenew'                 => 'Repiti la nueva clave:',
'resetpass_submit'          => 'Camudar clave y identificase',
'resetpass_success'         => '¡La to clave cambióse correutamente! Agora identificándote...',
'resetpass_forbidden'       => 'Les claves nun se puen camudar',
'resetpass-no-info'         => "Has tar identificáu p'acceder direutamente a esta páxina.",
'resetpass-submit-loggedin' => 'Camudar clave',
'resetpass-wrong-oldpass'   => "La clave d'anguaño o temporal nun casa.
Puede ser que yá la cambiaras o que solicitaste otra.",
'resetpass-temp-password'   => 'Clave temporal:',

# Edit page toolbar
'bold_sample'     => 'Testu en negrina',
'bold_tip'        => 'Testu en negrina',
'italic_sample'   => 'Testu en cursiva',
'italic_tip'      => 'Testu en cursiva',
'link_sample'     => 'Títulu del enllaz',
'link_tip'        => 'Enllaz internu',
'extlink_sample'  => 'http://www.example.com títulu del enllaz',
'extlink_tip'     => "Enllaz esternu (recuerda'l prefixu http://)",
'headline_sample' => 'Testu de cabecera',
'headline_tip'    => 'Testu cabecera nivel 2',
'math_sample'     => 'Inxertar fórmula equí',
'math_tip'        => 'Fórmula matemática',
'nowiki_sample'   => 'Pon equí testu ensin formatu',
'nowiki_tip'      => "Inora'l formatu wiki",
'image_sample'    => 'Exemplu.jpg',
'image_tip'       => 'Inxertar imaxe',
'media_sample'    => 'Exemplu.ogg',
'media_tip'       => 'Enllaz a archivu',
'sig_tip'         => 'La to firma con fecha',
'hr_tip'          => 'Llinia horizontal (úsala con moderación)',

# Edit pages
'summary'                          => 'Resume:',
'subject'                          => 'Asuntu/títulu:',
'minoredit'                        => 'Esta ye una edición menor',
'watchthis'                        => 'Vixilar esta páxina',
'savearticle'                      => 'Grabar páxina',
'preview'                          => 'Previsualizar',
'showpreview'                      => 'Amosar previsualización',
'showlivepreview'                  => 'Vista rápida',
'showdiff'                         => 'Amosar cambeos',
'anoneditwarning'                  => "'''Avisu:''' Nun tas identificáu. La to IP va quedar grabada nel historial d'edición d'esta páxina.",
'missingsummary'                   => "'''Recordatoriu:''' Nun escribisti un resume d'edición. Si vuelves a calcar en Guardar, la to edición sedrá guardada ensin nengún resume.",
'missingcommenttext'               => 'Por favor, escribi un comentariu embaxo.',
'missingcommentheader'             => "'''Recordatoriu:''' Nun-y punxisti tema/títulu a esti comentariu. Si vuelves a calcar en Guardar, la to edición va grabase ensin él.",
'summary-preview'                  => 'Previsualización del resume:',
'subject-preview'                  => 'Previsualización del tema/títulu:',
'blockedtitle'                     => "L'usuariu ta bloquiáu",
'blockedtext'                      => "'''El to nome d'usuariu o la to direición IP foi bloquiáu.'''

El bloquéu féxolu $1.
El motivu conseñáu ye ''$2''.

* Entamu del bloquéu: $8
* Caducidá del bloquéu: $6
* Usuariu que se quier bloquiar: $7

Pues ponete en contautu con $1 o con cualesquier otru [[{{MediaWiki:Grouppage-sysop}}|alministrador]] pa discutir el bloquéu.
Nun pues usar la funcionalidá 'manda-y un email a esti usuariu' a nun ser que tea especificada una direición de corréu válida
na to [[Special:Preferences|páxina de preferencies]] y que nun te tengan bloquiao el so usu.
La to direición IP actual ye $3, y el númberu d'identificación del bloquéu ye $5.
Por favor, amiesta dalgún o dambos d'estos datos nes tos consultes.",
'autoblockedtext'                  => 'La to direición IP foi bloquiada automáticamente porque foi usada por otru usuariu que foi bloquiáu por $1.
El motivu conseñáu foi esti:

:\'\'$2\'\'

* Entamu del bloquéu: $8
* Caducidá del bloquéu: $6
* Usuariu que se quier bloquiar: $7

Pues ponete en contautu con $1 o con cualesquier otru [[{{MediaWiki:Grouppage-sysop}}|alministrador]] p\'aldericar sobre\'l bloquéu.

Fíxate en que nun pues usar la funcionalidá d\'"unvia-y un corréu a esti usuariu" a nun se que tengas una direición de corréu válida rexistrada na to [[Special:Preferences|páxina de preferencies]] y que nun teas bloquiáu pa usala.

La to direición IP actual ye $3, y el númberu d\'identificación del bloquéu ye $5.
Por favor, amiesta toos estos detalles nes consultes que faigas.',
'blockednoreason'                  => 'nun se dio nengún motivu',
'blockedoriginalsource'            => "El códigu fonte de '''$1''' amuésase equí:",
'blockededitsource'                => "El testu de '''les tos ediciones''' en '''$1''' amuésase equí:",
'whitelistedittitle'               => 'Ye necesario tar identificáu pa poder editar',
'whitelistedittext'                => 'Tienes que $1 pa editar páxines.',
'confirmedittext'                  => "Has confirmar la to direición de corréu electrónicu enantes d'editar páxines. Por favor, configúrala y valídala nes tos [[Special:Preferences|preferencies d'usuariu]].",
'nosuchsectiontitle'               => 'Nun esiste tala seición',
'nosuchsectiontext'                => 'Intentasti editar una seición que nun esiste.',
'loginreqtitle'                    => 'Identificación Requerida',
'loginreqlink'                     => 'identificase',
'loginreqpagetext'                 => 'Has $1 pa ver otres páxines.',
'accmailtitle'                     => 'Clave unviada.',
'accmailtext'                      => "Unvióse a $2 una clave xenerada al debalu pa [[User talk:$1|$1]].

La clave pa esta cuenta nueva pue camudase na páxina ''[[Special:ChangePassword|camudar clave]]'' depués d'identificate.",
'newarticle'                       => '(Nuevu)',
'newarticletext'                   => 'Siguisti un enllaz a un artículu qu\'inda nun esiste. Pa crealu, empecipia a escribir na caxa que vien darréu (mira la [[{{MediaWiki:Helppage}}|páxina d\'aida]] pa más información). Si llegasti equí por enquivocu, namás tienes que calcar nel botón "Dir atrás" del to navegador.',
'anontalkpagetext'                 => "----''Esta ye la páxina de'alderique pa un usuariu anónimu qu'inda nun creó una cuenta o que nun la usa. Pola mor d'ello ha usase la direición numérica IP pa identificalu/la. Tala IP pue ser compartida por varios usuarios. Si yes un usuariu anónimu y notes qu'hai comentarios irrelevantes empobinaos pa ti, por favor [[Special:UserLogin/signup|crea una cuenta]] o [[Special:UserLogin/signup|rexístrate]] pa evitar futures confusiones con otros usuarios anónimos.''",
'noarticletext'                    => 'Anguaño nun hai testu nesta páxina.
Pues [[Special:Search/{{PAGENAME}}|buscar esti títulu de páxina]] n\'otres páxines,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} buscar los rexistros rellacionaos],
o [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar ésta equí]</span>.',
'userpage-userdoesnotexist'        => 'La cuenta d\'usuariu "$1" nun ta rexistrada. Por favor asegúrate de que quies crear/editar esta páxina.',
'clearyourcache'                   => "'''Nota:''' Llueu de salvar, seique tengas que llimpiar la caché del navegador pa ver los cambeos.
*'''Mozilla / Firefox / Safari:''' caltién ''Shift'' mentes calques en ''Reload'', o calca ''Ctrl-Shift-R'' (''Cmd-Shift-R'' en Apple Mac)
*'''IE:''' caltién ''Ctrl'' mentes calques ''Refresh'', o calca ''Ctrl-F5''
*'''Konqueror:''' calca nel botón ''Reload'', o calca ''F5''
*'''Opera:''' los usuarios d'Opera seique necesiten esborrar dafechu'l caché en ''Tools→Preferences''",
'usercssyoucanpreview'             => "'''Conseyu:''' Usa'l bottón 'Amosar previsualización' pa probar el to nuevu CSS enantes de guardalu.",
'userjsyoucanpreview'              => "'''Conseyu:''' Usa'l bottón 'Amosar previsualización' pa probar el to nuevu JS enantes de guardalu.",
'usercsspreview'                   => "'''Recuerda que namái tas previsualizando'l to CSS d'usuariu.'''
'''¡Tovía nun ta guardáu!'''",
'userjspreview'                    => "'''¡Recuerda que namái tas probando/previsualizando'l to JavaScript d'usuariu, entá nun se grabó!'''",
'userinvalidcssjstitle'            => "'''Avisu:''' Nun hai piel \"\$1\". Recuerda que les páxines personalizaes .css y .js usen un títulu en minúscules, p. ex. {{ns:user}}:Foo/monobook.css en cuenta de {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Actualizao)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''¡Alcuérdate de qu'esto ye sólo una previsualización y los cambeos entá nun se grabaron!'''",
'previewconflict'                  => "Esta previsualización amuesa'l testu del área d'edición d'enriba talo y como apaecerá si guardes los cambeos.",
'session_fail_preview'             => "'''¡Sentímoslo muncho! Nun se pudo procesar la to edición porque hebo una perda de datos de la sesión.
Inténtalo otra vuelta. Si nun se t'arregla, intenta salir y volver a rexistrate.'''",
'session_fail_preview_html'        => "'''¡Sentímoslo! Nun se pudo procesar la to edición pola mor d'una perda de datos de sesión.'''

''Como {{SITENAME}} tien activáu'l HTML puru, la previsualización nun s'amosará como precaución escontra ataques en JavaScript.''

'''Si esti ye un intentu llexítimu d'edición, por favor inténtalo otra vuelta. Si tovía asina nun furrula, intenta [[Special:UserLogout|desconeutate]] y volver a identificate.'''",
'token_suffix_mismatch'            => "'''La to edición nun foi aceutada porque'l to navegador mutiló los carauteres de puntuación
nel editor. La edición nun foi aceutada pa prevenir corrupciones na páxina de testu. Esto hai vegaes
que pasa cuando tas usando un proxy anónimu basáu en web que seya problemáticu.'''",
'editing'                          => 'Editando $1',
'editingsection'                   => 'Editando $1 (seición)',
'editingcomment'                   => 'Editando $1 (seición nueva)',
'editconflict'                     => "Conflictu d'edición: $1",
'explainconflict'                  => "Daquién más camudó esta páxina dende qu'empecipiasti a editala.
Na área de testu d'enriba ta'l testu de la páxina como ta nestos momentos.
Los tos cambeos amuésense na área de testu d'embaxo.
Vas tener que fusionar los tos cambeos dientro del testu esistente.
'''Namái''' va guardase'l testu de l'área d'enriba cuando calques en \"Guardar páxina\".",
'yourtext'                         => 'El to testu',
'storedversion'                    => 'Versión almacenada',
'nonunicodebrowser'                => "'''AVISU: El to navegador nun cumple la norma unicode. Hai un sistema alternativu que te permite editar páxines de forma segura: los carauteres non-ASCII apaecerán na caxa d'edición como códigos hexadecimales.'''",
'editingold'                       => "'''AVISU: Tas editando una revisión vieya d'esta páxina. Si la grabes, los cambeos que se ficieron dende esa revisión van perdese.'''",
'yourdiff'                         => 'Diferencies',
'copyrightwarning'                 => "Por favor, ten en cuenta que toles contribuciones de {{SITENAME}} considérense feches públiques baxo la $2 (ver $1 pa más detalles). Si nun quies que'l to trabayu seya editáu ensin midida, nun lu pongas equí.<br />
Amás tas dexándonos afitao qu'escribisti esto tu mesmu o que lo copiasti d'una fonte llibre de dominiu públicu o asemeyao.
'''¡NUN PONGAS TRABAYOS CON DERECHOS D'AUTOR ENSIN PERMISU!'''",
'copyrightwarning2'                => "Por favor, ten en cuenta que toles contribuciones de {{SITENAME}} puen ser editaes, alteraes o eliminaes por otros usuarios. Si nun quies que'l to trabayu seya editáu ensin midida, nun lu pongas equí.<br />
Amás tas dexándonos afitao qu'escribisti esto tu mesmu o que lo copiasti d'una fonte
llibre de dominiu públicu o asemeyao (ver $1 pa más detalles).
'''¡NUN PONGAS TRABAYOS CON DERECHOS D'AUTOR ENSIN PERMISU!'''",
'longpagewarning'                  => "'''AVISU: Esta páxina tien más de $1 quilobytes; dellos navegadores puen tener problemes editando páxines de 32 ó más kb. Habríes dixebrar la páxina en seiciones más pequeñes.'''",
'longpageerror'                    => "'''ERROR: El testu qu'unviasti tien $1 quilobytes, que ye
más que'l máximu de $2 quilobytes. Nun pue ser grabáu.'''",
'readonlywarning'                  => "'''AVISU: La base de datos ta candada por mantenimientu, polo que nun vas poder guardar les tos ediciones nestos momentos.
Seique habríes copiar el testu nun archivu de testu y guardalu pa intentalo lluéu. '''

L'alministrador que la candó ufrió una esplicación: $1",
'protectedpagewarning'             => "'''AVISU: Esta páxina ta candada pa que sólo los alministradores puean editala.'''",
'semiprotectedpagewarning'         => "'''Nota:''' Esta páxina foi candada pa que nun puean editala namái que los usuarios rexistraos.",
'cascadeprotectedwarning'          => "'''Avisu:''' Esta páxina ta candada pa que namái los alministradores la puean editar porque ta enxerta {{PLURAL:$1|na siguiente páxina protexida|nes siguientes páxines protexíes}} en cascada:",
'titleprotectedwarning'            => "'''AVISU: Esta páxina torgose pa que namái [[Special:ListGroupRights|dalgunos usuarios especiales]] puean creala.'''",
'templatesused'                    => '{{PLURAL:$1|plantía usada|plantíes usaes}} nesta páxina:',
'templatesusedpreview'             => '{{PLURAL:$1|plantía usada|plantíes usaes}} nesta previsualización:',
'templatesusedsection'             => 'Plantíes usaes nesta seición:',
'template-protected'               => '(protexida)',
'template-semiprotected'           => '(semi-protexida)',
'hiddencategories'                 => 'Esta páxina pertenez a {{PLURAL:$1|una categoría oculta|$1 categoríes ocultes}}:',
'nocreatetitle'                    => 'Creación de páxines limitada',
'nocreatetext'                     => '{{SITENAME}} tien restrinxida la capacidá de crear páxines nueves.
Pues volver atrás y editar una páxina esistente, o bien [[Special:UserLogin|identificate o crear una cuenta]].',
'nocreate-loggedin'                => 'Nun tienes permisu pa crear páxines nueves.',
'permissionserrors'                => 'Errores de Permisos',
'permissionserrorstext'            => 'Nun tienes permisu pa facer eso {{PLURAL:$1|pol siguiente motivu|polos siguientes motivos}}:',
'permissionserrorstext-withaction' => 'Nun tienes permisu pa $2 {{PLURAL:$1|pol siguiente motivu|polos siguientes motivos}}:',
'recreate-moveddeleted-warn'       => "'''Avisu: Tas volviendo a crear una páxina que foi esborrada anteriormente.'''

Habríes considerar si ye afechisco siguir editando esta páxina.
Equí tienes el rexistru d'esborraos d'esta páxina:",
'moveddeleted-notice'              => "Esta páxina foi eliminada.
Como referencia, embaxo amuésase'l rexistru d'esborráu de la páxina.",
'edit-hook-aborted'                => 'Edición albortada pol hook.
Nun conseñó esplicación.',
'edit-gone-missing'                => 'Nun se pudo actualizar la páxina.
Paez que foi eliminada.',
'edit-conflict'                    => "Conflictu d'edición.",
'edit-no-change'                   => 'Inoróse la to edición porque nun produxo nengún cambéu nel testu.',
'edit-already-exists'              => 'Nun se pudo crear una páxina nueva.
Yá esiste.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "Avisu: Esta páxina contién demasiaes llamaes costoses a funciones d'análisis sintáuticu.

Habría tener menos de $2 {{PLURAL:$2|llamada|llamaes}}, y agora tien $1 {{PLURAL:$1|llamada|llamaes}}.",
'expensive-parserfunction-category'       => "Páxines con demasiaes llamaes costoses a funciones d'análisis sintáuticu",
'post-expand-template-inclusion-warning'  => 'Avisu: Esta páxina tien demasiaes inclusiones de plantíes.
Dalgunes plantíes nun van ser incluyíes.',
'post-expand-template-inclusion-category' => "Páxines con escesu d'inclusiones de plantíes",
'post-expand-template-argument-warning'   => "Avisu: Esta páxina contién a lo menos un parámetru de plantía que tien un tamañu d'espansión demasiao llargu.
Estos parámetros van ser omitíos.",
'post-expand-template-argument-category'  => 'Páxines con parámetros de plantía omitíos',
'parser-template-loop-warning'            => 'Hai una rueda de plantíes: [[$1]]',
'parser-template-recursion-depth-warning' => "Superóse'l llímite de profundidá de les plantíes ($1)",

# "Undo" feature
'undo-success' => "La edición pue esfacese. Por favor comprueba la comparanza d'embaxo pa verificar que ye eso lo que quies facer, y depués guarda los cambeos p'acabar d'esfacer la edición.",
'undo-failure' => "Nun se pudo esfacer la edición pola mor d'ediciones intermedies conflictives.",
'undo-norev'   => 'Nun se pudo esfacer la edición porque nun esiste o foi eliminada.',
'undo-summary' => 'Esfecha la revisión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|alderique]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nun se pue crear la cuenta',
'cantcreateaccount-text' => "La creación de cuentes dende esta direición IP ('''$1''') foi bloquiada por [[User:$3|$3]].

El motivu dau por $3 ye ''$2''",

# History pages
'viewpagelogs'           => "Ver rexistros d'esta páxina",
'nohistory'              => "Nun hay historial d'ediciones pa esta páxina.",
'currentrev'             => 'Revisión actual',
'currentrev-asof'        => 'Revisión actual a fecha de $1',
'revisionasof'           => 'Revisión a fecha de $1',
'revision-info'          => 'Revisión a fecha de $1; $2',
'previousrevision'       => '←Revisión anterior',
'nextrevision'           => 'Revisión siguiente→',
'currentrevisionlink'    => 'Revisión actual',
'cur'                    => 'act',
'next'                   => 'próximu',
'last'                   => 'cab',
'page_first'             => 'primera',
'page_last'              => 'cabera',
'histlegend'             => "Seleición de diferencies: marca los botones de les versiones que quies comparar y da-y al <i>enter</i> o al botón d'abaxo.<br />
Lleenda: '''(act)''' = diferencies cola versión actual,
'''(cab)''' = diferencies cola versión anterior, '''m''' = edición menor.",
'history-fieldset-title' => 'Navegar pel historial',
'histfirst'              => 'Primera',
'histlast'               => 'Cabera',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vaciu)',

# Revision feed
'history-feed-title'          => 'Historial de revisiones',
'history-feed-description'    => "Historial de revisiones d'esta páxina na wiki",
'history-feed-item-nocomment' => '$1 en $2',
'history-feed-empty'          => 'La páxina solicitada nun esiste.
Seique fuera esborrada o renomada na wiki.
Prueba a [[Special:Search|buscar na wiki]] otres páxines nueves.',

# Revision deletion
'rev-deleted-comment'         => '(comentariu elimináu)',
'rev-deleted-user'            => "(nome d'usuariu elimináu)",
'rev-deleted-event'           => '(aición de rexistru eliminada)',
'rev-deleted-text-permission' => "Esta revisión de la páxina foi eliminada de los archivos públicos.
Pue haber detalles nel [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rexistru d'esborraos].",
'rev-deleted-text-view'       => "Esta revisión de la páxina foi eliminada de los archivos públicos.
Como alministrador d'esti sitiu pues vela; pue haber detalles nel [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rexistru d'esborraos].",
'rev-delundel'                => 'amosar/esconder',
'revisiondelete'              => 'Esborrar/restaurar revisiones',
'revdelete-nooldid-title'     => 'Revisión de destín non válida',
'revdelete-nooldid-text'      => 'Nun especificasti una revisión o revisiones de destín sobre les que realizar esta función, la revisión especificada nun esiste, o tas intentando ocultar la revisión actual.',
'revdelete-selected'          => "'''{{PLURAL:$2|Revisión seleicionada|Revisiones seleicionaes}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Seleicionáu un eventu de rexistru|Seleicionaos eventos de rexistru}}:'''",
'revdelete-text'              => "'''Les revisiones y eventos eliminaos van siguir apaeciendo nel historial de la páxina
y nos rexistros, pero parte del so conteníu nun va ser accesible al públicu.'''

Otros alministrados de {{SITENAME}} van siguir pudiendo acceder al conteníu escondíu
y puen restauralu de nuevo al traviés d'esta mesma interfaz, a nun ser que s'establezan
restricciones adicionales.",
'revdelete-legend'            => 'Establecer restricciones de visibilidá',
'revdelete-hide-text'         => 'Esconder testu de revisión',
'revdelete-hide-image'        => 'Esconder el conteníu del archivu',
'revdelete-hide-name'         => 'Esconder aición y oxetivu',
'revdelete-hide-comment'      => "Esconder comentariu d'edición",
'revdelete-hide-user'         => "Esconder el nome d'usuariu/IP del editor",
'revdelete-hide-restricted'   => 'Aplicar estes restricciones a los alministradores y candar la so interfaz',
'revdelete-suppress'          => 'Eliminar datos de los alministradores lo mesmo que los de los demás',
'revdelete-unsuppress'        => 'Eliminar restricciones de revisiones restauraes',
'revdelete-log'               => 'Motivu:',
'revdelete-submit'            => 'Aplicar a la revisión seleicionada',
'revdelete-logentry'          => 'camudada la visibilidá de revisiones de [[$1]]',
'logdelete-logentry'          => "camudada la visibilidá d'eventos de [[$1]]",
'revdelete-success'           => "'''Visibilidá de revisiones establecida correutamente.'''",
'logdelete-success'           => "'''Visibilidá d'eventos establecida correutamente.'''",
'revdel-restore'              => 'Camudar visibilidá',
'pagehist'                    => 'Historial de la páxina',
'deletedhist'                 => 'Historial elimináu',
'revdelete-content'           => 'conteníu',
'revdelete-summary'           => 'editar resume',
'revdelete-uname'             => "nome d'usuariu",
'revdelete-restricted'        => 'aplicaes les restricciones a los alministradores',
'revdelete-unrestricted'      => 'eliminaes les restricciones a los alministradores',
'revdelete-hid'               => "ocultáu'l $1",
'revdelete-unhid'             => "amosáu'l $1",
'revdelete-log-message'       => '$1 pa {{PLURAL:$2|una revisión|$2 revisiones}}',
'logdelete-log-message'       => '$1 pa {{PLURAL:$2|un eventu|$2 eventos}}',

# Suppression log
'suppressionlog'     => 'Rexistru de supresiones',
'suppressionlogtext' => "Embaxo amuésase una llista de los esborraos y bloqueos rellacionaos con conteníu ocultu a los alministradores.
Mira'l [[Special:IPBlockList|rexistru de bloqueos d'IP]] pa ver una llista de los bloqueos activos anguaño.",

# History merging
'mergehistory'                     => 'Fusionar historiales de páxina',
'mergehistory-header'              => "Esta páxina permítete fusionar revisiones del historial d'una páxina orixe nuna páxina nueva.
Asegúrate de qu'esti cambéu caltenga la continuidá del históricu de la páxina.",
'mergehistory-box'                 => 'Fusionar les revisiones de dos páxines:',
'mergehistory-from'                => "Páxina d'orixe:",
'mergehistory-into'                => 'Páxina de destín:',
'mergehistory-list'                => "Historial d'ediciones fusionable",
'mergehistory-merge'               => "Les siguientes revisiones de [[:$1]] puen fusionase en [[:$2]]. Usa la columna de botones d'opción pa fusionar namaí les revisiones creaes na y enantes de la hora especificada. has fixate en que si uses los enllaces de navegación esborraránse les seleiciones feches nesta columna.",
'mergehistory-go'                  => 'Amosar ediciones fusionables',
'mergehistory-submit'              => 'Fusionar revisiones',
'mergehistory-empty'               => 'Nun se pue fusionar nenguna revisión.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revisión|revisiones}} de [[:$1]] fusionaes correutamente en [[:$2]].',
'mergehistory-fail'                => "Nun se pudo facer la fusión d'historiales, por favor verifica la páxina y los parámetros temporales.",
'mergehistory-no-source'           => "La páxina d'orixe $1 nun esiste.",
'mergehistory-no-destination'      => 'La páxina de destín $1 nun esiste.',
'mergehistory-invalid-source'      => "La páxina d'orixe ha tener un títulu válidu.",
'mergehistory-invalid-destination' => 'La páxina de destín ha tener un títulu válidu.',
'mergehistory-autocomment'         => '[[:$1]] fusionada con [[:$2]]',
'mergehistory-comment'             => '[[:$1]] fusionada con [[:$2]]: $3',
'mergehistory-same-destination'    => "Les páxines d'orixe y destín nun puen ser la mesma",

# Merge log
'mergelog'           => 'Rexistru de fusiones',
'pagemerge-logentry' => '[[$1]] foi fusionada en [[$2]] (hasta la revisión $3)',
'revertmerge'        => 'Dixebrar',
'mergelogpagetext'   => "Abaxo amuésase una llista de les fusiones más recientes d'un historial de páxina con otru.",

# Diffs
'history-title'           => 'Historial de revisiones de "$1"',
'difference'              => '(Diferencia ente revisiones)',
'lineno'                  => 'Llinia $1:',
'compareselectedversions' => 'Comparar les versiones seleicionaes',
'editundo'                => 'esfacer',
'diff-multi'              => '({{PLURAL:$1|1 revisión intermedia non amosada|$1 revisiones intermedies non amosaes}})',

# Search results
'searchresults'                    => 'Resultaos de la busca',
'searchresults-title'              => 'Resultaos de la busca pa "$1"',
'searchresulttext'                 => 'Pa más información tocante a busques en {{SITENAME}}, vete a [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Buscasti \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|toles páxines qu\'emprimen con "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toles páxines qu\'enllacien a "$1"]])',
'searchsubtitleinvalid'            => "Buscasti '''$1'''",
'toomanymatches'                   => 'Atopáronse demasiaes coincidencies, por favor fai una consulta diferente',
'titlematches'                     => 'Coincidencies de los títulos de la páxina',
'notitlematches'                   => 'Nun hai coincidencies nel títulu de la páxina',
'textmatches'                      => 'Coincidencies del testu de la páxina',
'notextmatches'                    => 'Nun hai coincidencies nel testu de la páxina',
'prevn'                            => 'previos {{PLURAL:$1|$1}}',
'nextn'                            => 'siguientes {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Ver ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opciones de busca',
'searchmenu-exists'                => "'''Hai una páxina nomada \"[[\$1]]\" nesta wiki'''",
'searchmenu-new'                   => "'''¡Crear la páxina \"[[:\$1]]\" nesta wiki!'''",
'searchhelp-url'                   => 'Help:Conteníos',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Restolar páxines con esti prefixu]]',
'searchprofile-articles'           => 'Páxines de conteníu',
'searchprofile-project'            => 'Páxines de proyeutu',
'searchprofile-images'             => 'Archivos',
'searchprofile-everything'         => 'Too',
'searchprofile-advanced'           => 'Avanzao',
'searchprofile-articles-tooltip'   => 'Buscar en $1',
'searchprofile-project-tooltip'    => 'Buscar en $1',
'searchprofile-images-tooltip'     => 'Buscar archivos',
'searchprofile-everything-tooltip' => "Buscar tol conteníu (incluyendo páxines d'alderique)",
'searchprofile-advanced-tooltip'   => 'Buscar en namespaces personalizaos',
'search-result-size'               => '$1 ({{PLURAL:$2|1 pallabra|$2 pallabres}})',
'search-result-score'              => 'Relevancia: $1%',
'search-redirect'                  => '(redireición a $1)',
'search-section'                   => '(seición $1)',
'search-suggest'                   => 'Quixisti dicir: $1',
'search-interwiki-caption'         => 'Proyeutos hermanos',
'search-interwiki-default'         => '$1 resultaos:',
'search-interwiki-more'            => '(más)',
'search-mwsuggest-enabled'         => 'con suxerencies',
'search-mwsuggest-disabled'        => 'ensin suxerencies',
'search-relatedarticle'            => 'Rellacionáu',
'mwsuggest-disable'                => 'Desactivar les suxerencies AJAX',
'searchrelated'                    => 'rellacionáu',
'searchall'                        => 'toos',
'showingresults'                   => "Abaxo {{PLURAL:$1|amuésase '''un''' resultáu|amuésense '''$1''' resultaos}}, entamando col #'''$2'''.",
'showingresultsnum'                => "Abaxo {{PLURAL:$3|amuésase '''un''' resultáu|amuésense '''$3''' resultaos}}, entamando col #'''$2'''.",
'nonefound'                        => "'''Nota''': Por defeutu namái se busca en dalgunos de los espacios de nome. Prueba a poner delantre de la to consulta ''all:'' pa buscar en tol conteníu (inxiriendo páxines d'alderique, plantíes, etc.), o usa como prefixu l'espaciu de nome deseáu.",
'search-nonefound'                 => 'Nun hebo resultaos que concueyen cola consulta.',
'powersearch'                      => 'Buscar',
'powersearch-legend'               => 'Busca avanzada',
'powersearch-ns'                   => 'Buscar nos espacios de nome:',
'powersearch-redir'                => 'Llistar redireiciones',
'powersearch-field'                => 'Buscar',
'search-external'                  => 'Busca esterna',
'searchdisabled'                   => "La busca en {{SITENAME}} ta desactivada. Mentanto, pues buscar en Google. Has fixate en que'l conteníu de los sos índices de {{SITENAME}} pue tar desfasáu.",

# Quickbar
'qbsettings'               => 'Barra rápida',
'qbsettings-none'          => 'Nenguna',
'qbsettings-fixedleft'     => 'Fixa a manzorga',
'qbsettings-fixedright'    => 'Fixa a mandrecha',
'qbsettings-floatingleft'  => 'Flotante a manzorga',
'qbsettings-floatingright' => 'Flotante a mandrecha',

# Preferences page
'preferences'               => 'Preferencies',
'mypreferences'             => 'Les mios preferencies',
'prefs-edits'               => "Númberu d'ediciones:",
'prefsnologin'              => 'Non identificáu',
'prefsnologintext'          => 'Necesites tar <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} identificáu]</span> pa camudar les preferencies d\'usuariu.',
'changepassword'            => 'Camudar clave',
'prefs-skin'                => 'Apariencia',
'skin-preview'              => 'Previsualizar',
'prefs-math'                => 'Fórmules matemátiques',
'datedefault'               => 'Ensin preferencia',
'prefs-datetime'            => 'Fecha y hora',
'prefs-personal'            => 'Datos personales',
'prefs-rc'                  => 'Cambeos recientes',
'prefs-watchlist'           => 'Llista de vixilancia',
'prefs-watchlist-days'      => "Númberu de díes qu'amosar na llista de vixilancia:",
'prefs-watchlist-days-max'  => '(7 díes máximo)',
'prefs-watchlist-edits'     => "Númberu d'ediciones qu'amosar na llista de vixilancia espandida:",
'prefs-watchlist-edits-max' => '(númberu máximu: 1000)',
'prefs-misc'                => 'Varios',
'prefs-resetpass'           => 'Camudar clave',
'saveprefs'                 => 'Guardar preferencies',
'resetprefs'                => 'Volver a les preferencies por defeutu',
'prefs-editing'             => 'Edición',
'prefs-edit-boxsize'        => "Tamañu de la ventana d'edición.",
'rows'                      => 'Files:',
'columns'                   => 'Columnes:',
'searchresultshead'         => 'Busques',
'resultsperpage'            => "Resultaos p'amosar per páxina:",
'contextlines'              => "Llinies p'amosar per resultáu:",
'contextchars'              => 'Carauteres de testu per llinia:',
'stub-threshold'            => 'Llímite superior pa considerar como <a href="#" class="stub">enllaz a entamu</a> (bytes):',
'recentchangesdays'         => "Díes qu'amosar nos cambeos recientes:",
'recentchangesdays-max'     => '(máximo $1 {{PLURAL:$1|día|díes}})',
'recentchangescount'        => "Númberu d'ediciones amosaes nes páxines de cambeos recientes, historiales de páxines y rexistros, por defeutu:",
'savedprefs'                => 'Les tos preferencies quedaron grabaes.',
'timezonelegend'            => 'Zona horaria:',
'localtime'                 => 'Hora llocal:',
'timezoneuseserverdefault'  => 'Usar el sirvidor preferíu',
'timezoneuseoffset'         => 'Otru (especificar diferencia)',
'timezoneoffset'            => 'Diferencia¹:',
'servertime'                => 'Hora del sirvidor:',
'guesstimezone'             => 'Obtener del navegador',
'timezoneregion-africa'     => 'África',
'timezoneregion-america'    => 'América',
'timezoneregion-antarctica' => 'Antártida',
'timezoneregion-arctic'     => 'Árticu',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Océanu Atlánticu',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Océanu Índicu',
'timezoneregion-pacific'    => 'Océanu Pacíficu',
'allowemail'                => 'Dexar a los otros usuarios mandate correos',
'prefs-searchoptions'       => 'Opciones de busca',
'prefs-namespaces'          => 'Espacios de nome',
'defaultns'                 => 'Buscar por defeutu nestos espacios de nome:',
'default'                   => 'por defeutu',
'prefs-files'               => 'Archivos',
'prefs-custom-css'          => 'CSS personalizada',
'prefs-custom-js'           => 'JS personalizada',
'youremail'                 => 'Corréu electrónicu:',
'username'                  => "Nome d'usuariu:",
'uid'                       => "Númberu d'usuariu:",
'prefs-memberingroups'      => 'Miembru {{PLURAL:$1|del grupu|de los grupos}}:',
'yourrealname'              => 'Nome real:',
'yourlanguage'              => 'Idioma de los menús:',
'yourvariant'               => 'Variante llingüística:',
'yournick'                  => 'Firma:',
'badsig'                    => 'Firma cruda non válida; comprueba les etiquetes HTML.',
'badsiglength'              => 'La to robla ye demasiao llarga.
Ha tener menos de $1 {{PLURAL:$1|caráuter|carauteres}}.',
'yourgender'                => 'Xéneru:',
'gender-unknown'            => 'Non especificáu',
'gender-male'               => 'Masculín',
'gender-female'             => 'Femenín',
'email'                     => 'Corréu',
'prefs-help-realname'       => "El nome real ye opcional y si decides conseñalu va ser usáu p'atribuyite'l to trabayu.",
'prefs-help-email'          => "La direición de corréu ye opcional, pero permite unviate una clave nueva si escaeces la to clave.
Tamién pues escoyer permitir a los demás contautar contigo al traviés de la to páxina d'usuariu o d'alderique ensin necesidá de revelar la to identidá.",
'prefs-help-email-required' => 'Necesítase una direición de corréu electrónicu.',

# User rights
'userrights'                  => "Remanamientu de derechos d'usuariu",
'userrights-lookup-user'      => "Remanamientu de grupos d'usuariu",
'userrights-user-editname'    => "Escribi un nome d'usuariu:",
'editusergroup'               => "Modificar grupos d'usuariu",
'editinguser'                 => "Camudando los drechos del usuariu '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => "Editar los grupos d'usuariu",
'saveusergroups'              => "Guardar los grupos d'usuariu",
'userrights-groupsmember'     => 'Miembru de:',
'userrights-groups-help'      => "Pues camudar los grupos a los que pertenez esti usuariu.
* Un caxellu marcáu significa que l'usuariu ta nesi grupu.
* Un caxellu non marcáu significa que l'usuariu nun ta nesi grupu.
* Un * indica que nun pues eliminalu del grupu una vegada tea inxeríu, o viceversa.",
'userrights-reason'           => 'Motivu:',
'userrights-no-interwiki'     => "Nun tienes permisu pa editar los derechos d'usuariu n'otres wikis.",
'userrights-nodatabase'       => 'La base de datos $1 nun esiste o nun ye llocal.',
'userrights-nologin'          => "Has tar [[Special:UserLogin|identificáu]] con una cuenta d'alministrador p'asignar derechos d'usuariu.",
'userrights-notallowed'       => "La to cuenta nun tien permisos p'asignar derechos d'usuariu.",
'userrights-changeable-col'   => 'Grupos que pues camudar',
'userrights-unchangeable-col' => 'Grupos que nun pues camudar',

# Groups
'group'               => 'Grupu:',
'group-user'          => 'Usuarios',
'group-autoconfirmed' => 'Usuarios autoconfirmaos',
'group-bot'           => 'Bots',
'group-sysop'         => 'Alministradores',
'group-bureaucrat'    => 'Burócrates',
'group-suppress'      => 'Güeyadores',
'group-all'           => '(toos)',

'group-user-member'          => 'usuariu',
'group-autoconfirmed-member' => 'usuariu autoconfirmáu',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'alministrador',
'group-bureaucrat-member'    => 'burócrata',
'group-suppress-member'      => 'güeyador',

'grouppage-user'          => '{{ns:project}}:Usuarios',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuarios autoconfirmaos',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Alministradores',
'grouppage-bureaucrat'    => '{{ns:project}}:Burócrates',
'grouppage-suppress'      => '{{ns:project}}:Güeyadores',

# Rights
'right-read'                 => 'Lleer páxines',
'right-edit'                 => 'Editar páxines',
'right-createpage'           => "Crear páxines (que nun seyan páxines d'alderique)",
'right-createtalk'           => "Crear páxines d'alderique",
'right-createaccount'        => "Crear cuentes nueves d'usuariu",
'right-minoredit'            => 'Marcar ediciones como menores',
'right-move'                 => 'Treslladar páxines',
'right-move-subpages'        => 'Treslladar les páxines coles sos subpáxines',
'right-move-rootuserpages'   => "Treslladar páxines d'un usuariu root",
'right-movefile'             => 'Treslladar archivos',
'right-suppressredirect'     => "Nun crear una redireición dende'l nome antiguu cuando se tresllada una páxina",
'right-upload'               => 'Xubir archivos',
'right-reupload'             => 'Sobreescribir un archivu esistente',
'right-reupload-own'         => 'Sobreescribir un archivu esistente xubíu pol mesmu usuariu',
'right-reupload-shared'      => 'Anular llocalmente archivos del depósitu compartíu multimedia',
'right-upload_by_url'        => 'Xubir un archivu dende una direición URL',
'right-purge'                => 'Purgar la caché del sitiu pa una páxina que nun tenga páxina de confirmación',
'right-autoconfirmed'        => 'Editar páxines semi-protexíes',
'right-bot'                  => 'Tratar como un procesu automatizáu',
'right-nominornewtalk'       => "Nun amosar l'avisu de nuevos mensaxes cuando se faen ediciones menores en páxines d'alderique",
'right-apihighlimits'        => 'Usar los llímites superiores nes consultes API',
'right-writeapi'             => "Usar l'API d'escritura",
'right-delete'               => 'Esborrar páxines',
'right-bigdelete'            => 'Esborrar páxines con historiales grandes',
'right-deleterevision'       => 'Eliminar y restaurar revisiones específiques de les páxines',
'right-deletedhistory'       => 'Ver entraes eliminaes del historial ensin testu asociáu',
'right-browsearchive'        => 'Buscar páxines esborraes',
'right-undelete'             => 'Restaurar una páxina',
'right-suppressrevision'     => 'Revisar y restaurar revisiones ocultes a los alministradores',
'right-suppressionlog'       => 'Ver rexistros privaos',
'right-block'                => "Bloquiar la edición d'otros usuarios",
'right-blockemail'           => "Bloquia-y l'unviu de corréu electrónicu a un usuariu",
'right-hideuser'             => "Bloquiar un nome d'usuariu ocultándolu al públicu",
'right-ipblock-exempt'       => "Saltar los bloqueos d'IP, los autobloqueos y los bloqueos d'intervalu",
'right-proxyunbannable'      => 'Saltar los bloqueos automáticos de los proxys',
'right-protect'              => 'Camudar los niveles de proteición y editar páxines protexíes',
'right-editprotected'        => 'Editar les páxines protexíes (ensin proteición en cascada)',
'right-editinterface'        => "Editar la interfaz d'usuariu",
'right-editusercssjs'        => "Editar los archivos CSS y JS d'otros usuarios",
'right-editusercss'          => "Editar los archivos CSS d'otros usuarios",
'right-edituserjs'           => "Editar los archivos JS d'otros usuarios",
'right-rollback'             => "Revertir rápido a un usuariu qu'editó una páxina determinada",
'right-markbotedits'         => 'Marcar les ediciones revertíes como ediciones de bot',
'right-noratelimit'          => 'Nun tar afeutáu polos llímites de tasa',
'right-import'               => 'Importar páxines dende otres wikis',
'right-importupload'         => 'Importar páxines dende un archivu',
'right-patrol'               => 'Marcar les ediciones como supervisaes',
'right-autopatrol'           => 'Marcar automáticamente les ediciones como supervisaes',
'right-patrolmarks'          => 'Ver les marques de supervisión de los cambeos recientes',
'right-unwatchedpages'       => 'Ver una llista de páxines non vixilaes',
'right-trackback'            => 'Añader un retroenllaz',
'right-mergehistory'         => 'Fusionar historiales de páxines',
'right-userrights'           => "Editar tolos drechos d'usuariu",
'right-userrights-interwiki' => "Editar los drechos d'usuariu d'usuarios d'otros sitios wiki",
'right-siteadmin'            => 'Candar y descandar la base de datos',

# User rights log
'rightslog'      => "Rexistru de perfil d'usuariu",
'rightslogtext'  => "Esti ye un rexistru de los cambeos de los perfiles d'usuariu.",
'rightslogentry' => 'camudó la pertenencia de grupu del usuariu $1 dende $2 a $3',
'rightsnone'     => '(nengún)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lleer esta páxina',
'action-edit'                 => 'editar esta páxina',
'action-createpage'           => 'crear páxines',
'action-createtalk'           => "crear páxines d'alderique",
'action-createaccount'        => "crear esta cuenta d'usuariu",
'action-minoredit'            => 'marcar esta edición como menor',
'action-move'                 => 'treslladar esta páxina',
'action-move-subpages'        => 'treslladar esta páxina y les sos subpáxines',
'action-move-rootuserpages'   => "treslladar páxines d'un usuariu root",
'action-movefile'             => 'treslladar esti archivu',
'action-upload'               => 'xubir esti archivu',
'action-reupload'             => 'sobreescribir esti archivu esistente',
'action-reupload-shared'      => 'sustituyir esti archivu nun direutoriu compartíu',
'action-upload_by_url'        => 'xubir esti archivu dende una direición URL',
'action-writeapi'             => "usar l'API d'escritura",
'action-delete'               => 'eliminar esta páxina',
'action-deleterevision'       => 'eliminar esta revisión',
'action-deletedhistory'       => "ver l'historial elimináu d'esta páxina",
'action-browsearchive'        => 'buscar páxines esborraes',
'action-undelete'             => 'restaurar esta páxina',
'action-suppressrevision'     => 'revisar y restaurar esta revisión oculta',
'action-suppressionlog'       => 'ver esti rexistru priváu',
'action-block'                => "bloquiar qu'esti usuariu edite",
'action-protect'              => 'camudar los niveles de proteición pa esta páxina',
'action-import'               => 'importar esta páxina dende otra wiki',
'action-importupload'         => "importar esta páxina dende una xubida d'archivu",
'action-patrol'               => "marcar les ediciones d'otros como supervisaes",
'action-autopatrol'           => 'marcar la to edición como supervisada',
'action-unwatchedpages'       => 'ver la llista de páxines non vixilaes',
'action-trackback'            => 'añader un retroenllaz',
'action-mergehistory'         => "fusionar l'historial d'esta páxina",
'action-userrights'           => "editar tolos drechos d'usuariu",
'action-userrights-interwiki' => "editar los drechos d'usuariu d'usuarios d'otres wikis",
'action-siteadmin'            => 'candar o descandar la base de datos',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|un cambéu|$1 cambeos}}',
'recentchanges'                     => 'Cambeos recientes',
'recentchanges-legend'              => 'Opciones de cambeos recientes',
'recentchangestext'                 => 'Sigui los cambeos más recientes na wiki nesta páxina.',
'recentchanges-feed-description'    => 'Sigue nesti canal los cambeos más recientes de la wiki.',
'rcnote'                            => "Equí embaxo {{PLURAL:$1|pue vese '''1''' cambéu|puen vese los caberos '''$1''' cambeos}} {{PLURAL:$2|nel caberu día|nos caberos '''$2''' díes}}, a fecha de $5, $4.",
'rcnotefrom'                        => 'Abaxo tán los cambeos dende <b>$2</b> (hasta <b>$1</b>).',
'rclistfrom'                        => 'Amosar los cambeos recientes dende $1',
'rcshowhideminor'                   => '$1 ediciones menores',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 usuarios rexistraos',
'rcshowhideanons'                   => '$1 usuarios anónimos',
'rcshowhidepatr'                    => '$1 ediciones supervisaes',
'rcshowhidemine'                    => '$1 les mios ediciones',
'rclinks'                           => 'Amosar los caberos $1 cambeos nos caberos $2 díes <br />$3',
'diff'                              => 'dif',
'hist'                              => 'hist',
'hide'                              => 'Esconder',
'show'                              => 'Amosar',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usuariu|ususarios}} vixilando]',
'rc_categories'                     => 'Llímite pa les categoríes (dixebrar con "|")',
'rc_categories_any'                 => 'Cualesquiera',
'newsectionsummary'                 => '/* $1 */ nueva seición',
'rc-enhanced-expand'                => 'Amosar detalles (requier JavaScript)',
'rc-enhanced-hide'                  => 'Ocultar detalles',

# Recent changes linked
'recentchangeslinked'          => 'Cambeos rellacionaos',
'recentchangeslinked-feed'     => 'Cambeos rellacionaos',
'recentchangeslinked-toolbox'  => 'Cambeos rellacionaos',
'recentchangeslinked-title'    => 'Cambeos rellacionaos con "$1"',
'recentchangeslinked-noresult' => 'Nun hebo cambeos nes páxines enllaciaes nel periodu conseñáu.',
'recentchangeslinked-summary'  => "Esta ye una llista de los caberos cambeos fechos nes páxines enllaciaes dende una páxina determinada (o nos miembros d'una categoría determinada). Les páxines de [[Special:Watchlist|la to llista de vixilancia]] tán en '''negrina'''.",
'recentchangeslinked-page'     => 'Nome de la páxina:',
'recentchangeslinked-to'       => "Amosar los cambeos a les páxines enllaciaes en cuenta d'a la páxina dada",

# Upload
'upload'                      => 'Xubir imaxe',
'uploadbtn'                   => 'Xubir',
'reuploaddesc'                => 'Cancelar la xubida y tornar al formulariu de xubíes',
'uploadnologin'               => 'Nun tas identificáu',
'uploadnologintext'           => 'Tienes que tar [[Special:UserLogin|identificáu]] pa poder xubir archivos.',
'upload_directory_missing'    => 'El direutoriu de xubida ($1) nun esiste y nun pudo ser creáu pol sirvidor de web.',
'upload_directory_read_only'  => "El sirvidor nun pue modificar el direutoriu de xubida d'archivos ($1).",
'uploaderror'                 => 'Error de xubida',
'uploadtext'                  => "Usa'l formulariu d'abaxo pa xubir archivos.
Pa ver o buscar archivos xubíos previamente, vete a la [[Special:FileList|llista d'archivos xubíos]]. Les xubíes tamién queden conseñaos nel [[Special:Log/upload|rexistru de xubíes]], y los esborraos nel [[Special:Log/delete|rexistru d'esborraos]].

P'amiestar un archivu nuna páxina, usa un enllaz con ún de los siguientes formatos:
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Archivu.jpg]]</nowiki></tt>''' pa usar la versión completa del archivu
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Archivu.png|200px|thumb|left|testu alternativu]]</nowiki></tt>''' pa usar un renderizáu de 200 píxeles d'anchu nun caxellu al marxe esquierdu con 'testu alternativu' como la so descripción
*'''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Archivu.ogg]]</nowiki></tt>''' pa enllazar direutamente al archivu ensin amosar l'archivu",
'upload-permitted'            => "Menes d'archivu permitíes: $1.",
'upload-preferred'            => "Menes d'archivu preferíes: $1.",
'upload-prohibited'           => "Menes d'archivu prohibíes: $1.",
'uploadlog'                   => 'rexistru de xubíes',
'uploadlogpage'               => 'Rexistru de xubíes',
'uploadlogpagetext'           => "Abaxo amuésase una llista de les xubíes d'archivos más recientes.
Mira la [[Special:NewFiles|galería d'archivos nuevos]] pa una güeyada más visual.",
'filename'                    => "Nome d'archivu",
'filedesc'                    => 'Resume',
'fileuploadsummary'           => 'Resume:',
'filestatus'                  => 'Estáu de Copyright:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Archivos xubíos',
'ignorewarning'               => "Inorar l'avisu y grabar l'archivu de toes formes",
'ignorewarnings'              => 'Inorar tolos avisos',
'minlength1'                  => "Los nomes d'archivu han tener a lo menos una lletra.",
'illegalfilename'             => 'El nome d\'archivu "$1" contién carauteres non permitíos en títulos de páxina. Por favor renoma l\'archivu y xúbilu otra vuelta.',
'badfilename'                 => 'Nome de la imaxe camudáu a "$1".',
'filetype-badmime'            => 'Los archivos de la triba MIME "$1" nun tienen permitida la xubida.',
'filetype-bad-ie-mime'        => 'Nun puede xubise esti ficheru porque Internet Explorer deteutalu como "$1", nun ta permitíu y paez que pueda ser un ficheru peligrosu.',
'filetype-unwanted-type'      => "'''\".\$1\"''' ye una mena d'archivu non recomendáu.
{{PLURAL:\$3|La mena d'archivu preferida ye|Les menes d'archivu preferíes son}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' nun ye una mena d'archivu permitida.
{{PLURAL:\$3|La mena d'archivu permitida ye|Les menes d'archivu permitíes son}} \$2.",
'filetype-missing'            => 'L\'archivu nun tien estensión (como ".jpg").',
'large-file'                  => 'Encamiéntase a que los archivos nun pasen de $1; esti archivu tien $2.',
'largefileserver'             => 'Esti archivu ye mayor de lo que permite la configuración del sirvidor.',
'emptyfile'                   => "L'archivu que xubisti paez tar vaciu. Esto podría ser pola mor d'un enquivocu nel nome l'archivu. Por favor, camienta si daveres quies xubir esti archivu.",
'fileexists'                  => "Yá esiste un archivu con esti nome, por favor comprueba '''<tt>[[:$1]]</tt>''' si nun tas seguru de quere camudalu.
[[$1|thumb]]",
'filepageexists'              => "La páxina de descripción d'esti archivu yá foi creada en '''<tt>[[:$1]]</tt>''', pero nestos momentos nun esiste nengún archivu con esti nome. El resume que pongas nun va apaecer na páxina de descripción. Pa facer que'l to resume apaeza vas tener que lu editar manualmente.",
'fileexists-extension'        => "Yá esiste un archivu con un nome asemeyáu: [[$2|thumb]]
* Nome del archivu que se quier xubir: '''<tt>[[:$1]]</tt>'''
* Nome del archivu esistente: '''<tt>[[:$2]]</tt>'''
Por favor escueyi un nome diferente.",
'fileexists-thumbnail-yes'    => "L'archivu paez ser una imaxe de tamañu menguáu ''(miniatura)''. [[$1|thumb]]
Por favor comprueba l'archivu '''<tt>[[:$1]]</tt>'''.
Si l'archivu comprobáu tien el mesmu tamañu que la imaxe orixinal, nun ye necesario xubir una miniatura extra.",
'file-thumbnail-no'           => "L'archivu entama con '''<tt>$1</tt>'''.
Paez ser una imaxe de tamañu menguáu ''(miniatura)''.
Si tienes esta imaxe a resolución completa xúbila; si non, por favor camuda'l nome del archivu.",
'fileexists-forbidden'        => 'Yá esiste un ficheru con esti nome, y nun pue ser renomáu.
Si tovía asina quies xubir el ficheru, por favor vuelvi atrás y usa otru nome. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Yá esiste un archivu con esti nome nel direutoriu d'archivos compartíos.
Si tovía asina quies xubir l'archivu, por favor vuelvi atrás y usa otru nome. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => 'Esti archivu ye un duplicáu {{PLURAL:$1|del siguiente archivu|de los siguientes archivos}}:',
'file-deleted-duplicate'      => 'Yá se desanició enantes un ficheru idénticu a esti ([[$1]]). Deberíes revisar el historial del ficheru enantes de xubilu otra vuelta.',
'uploadwarning'               => "Avisu de xubíes d'archivos",
'savefile'                    => 'Grabar archivu',
'uploadedimage'               => 'xubió "[[$1]]"',
'overwroteimage'              => 'xubió una versión nueva de "[[$1]]"',
'uploaddisabled'              => 'Deshabilitaes les xubíes',
'uploaddisabledtext'          => "Les xubíes d'archivos tán desactivaes.",
'uploadscripted'              => 'Esti archivu contién códigu HTML o scripts que puen ser interpretaos erróneamente por un navegador.',
'uploadvirus'                 => "¡L'archivu tien un virus! Detalles: $1",
'sourcefilename'              => "Nome d'orixe:",
'destfilename'                => 'Nome de destín:',
'upload-maxfilesize'          => "Máximu tamañu d'archivu: $1",
'watchthisupload'             => 'Vixilar esti ficheru',
'filewasdeleted'              => 'Yá foi xubíu y depués esborráu un archivu con esti nome. Habríes comprobar el $1 enantes de volver a xubilu.',
'upload-wasdeleted'           => "'''Avisu: Tas xubiendo un archivu que yá foi esborráu anteriormente.'''

Habríes considerar si ye afechisco continuar xubiendo esti archivu.
Amuésase equí'l rexistru d'esborraos pa esti archivu a los efeutos oportunos:",
'filename-bad-prefix'         => "El nome del archivu que tas xubiendo entama con '''\"\$1\"''', que ye un nome non descriptivu típicamente asignáu automáticamente poles cámares dixitales. Por favor escueyi un nome más descriptivu pal to archivu.",
'upload-success-subj'         => 'Xubida correuta',

'upload-proto-error'      => 'Protocolu incorreutu',
'upload-proto-error-text' => "La xubida remota requier que l'URL entame por <code>http://</code> o <code>ftp://</code>.",
'upload-file-error'       => 'Error internu',
'upload-file-error-text'  => 'Hebo un error al intentar crear un archivu temporal nel sirvidor.
Por favor contauta con un [[Special:ListUsers/sysop|alministrador]] del sistema.',
'upload-misc-error'       => 'Error de xubida desconocíu',
'upload-misc-error-text'  => "Hebo un error desconocíu na xubida del archivu.
Por favor verifica que l'URL ye válidu y accesible, y inténtalo otra vuelta.
Si'l problema persiste, contauta con un [[Special:ListUsers/sysop|alministrador]] del sistema.",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nun se pudo acceder a la URL',
'upload-curl-error6-text'  => "Nun se pudo acceder a la URL introducida. Por favor comprueba que la URL ye correuta y que'l sitiu ta activu.",
'upload-curl-error28'      => "Fin del tiempu d'espera de la xubida",
'upload-curl-error28-text' => "El sitiu tardó demasiáu tiempu en responder. Por favor comprueba que'l sitiu ta activu, espera unos momentos y vuelve a intentalo. Igual ye meyor que lo intentes nun momentu en que tea menos sobrecargáu.",

'license'            => 'Llicencia:',
'license-header'     => 'Llicencia:',
'nolicense'          => 'Nenguna seleicionada',
'license-nopreview'  => '(Previsualización non disponible)',
'upload_source_url'  => ' (una URL válida y accesible públicamente)',
'upload_source_file' => ' (un archivu del to ordenador)',

# Special:ListFiles
'listfiles-summary'     => "Esta páxina especial amuesa tolos archivos xubíos.
Por defeutu los caberos archivos xubíos amuésense a lo cimero de la llista.
Calcando na cabecera d'una columna camúdase l'orde acordies con ella.",
'listfiles_search_for'  => "Buscar por nome d'archivu multimedia:",
'imgfile'               => 'archivu',
'listfiles'             => "Llista d'imáxenes",
'listfiles_date'        => 'Fecha',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Usuariu',
'listfiles_size'        => 'Tamañu',
'listfiles_description' => 'Descripción',
'listfiles_count'       => 'Versiones',

# File description page
'file-anchor-link'          => 'Archivu',
'filehist'                  => 'Historial del archivu',
'filehist-help'             => "Calca nuna fecha/hora pa ver l'archivu como taba daquélla.",
'filehist-deleteall'        => 'esborrar too',
'filehist-deleteone'        => 'esborrar',
'filehist-revert'           => 'revertir',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Fecha/Hora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura de la versión a fecha de $1',
'filehist-nothumb'          => 'Ensin miniatura',
'filehist-user'             => 'Usuariu',
'filehist-dimensions'       => 'Dimensiones',
'filehist-filesize'         => 'Tamañu del archivu',
'filehist-comment'          => 'Comentariu',
'imagelinks'                => 'Enllaces del ficheru',
'linkstoimage'              => '{{PLURAL:$1|La páxina siguiente enllacia|Les páxines siguientes enllacien}} a esti archivu:',
'linkstoimage-more'         => "Más de $1 {{PLURAL:$1|páxina enllacia|páxines enllacien}} a esti archivu.
La llista siguiente amuesa{{PLURAL:$1|'l primer enllaz de páxina| los primeros $1 enllaces de páxina}} a esti archivu namái.
Hai disponible una [[Special:WhatLinksHere/$2|llista completa]].",
'nolinkstoimage'            => "Nun hai páxines qu'enllacien a esti archivu.",
'morelinkstoimage'          => 'Ver [[Special:WhatLinksHere/$1|más enllaces]] a esti archivu.',
'redirectstofile'           => '{{PLURAL:$1|El siguiente archivu redirixe|Los siguientes $1 archivos redirixen}} a esti archivu:',
'duplicatesoffile'          => "{{PLURAL:$1|El siguiente archivu ye un duplicáu|Los siguientes $1 archivos son duplicaos}} d'esti archivu ([[Special:FileDuplicateSearch/$2|más detalles]]):",
'sharedupload'              => 'El ficheru ye de $1 y pueden que tean usandolu otros proyeutos.',
'uploadnewversion-linktext' => "Xubir una nueva versión d'esta imaxe",

# File reversion
'filerevert'                => 'Revertir $1',
'filerevert-legend'         => 'Revertir archivu',
'filerevert-intro'          => "Tas revirtiendo '''[[Media:$1|$1]]''' a la [$4 versión del $3 a les $2].",
'filerevert-comment'        => 'Comentariu:',
'filerevert-defaultcomment' => 'Revertida a la versión del $2 a les $1',
'filerevert-submit'         => 'Revertir',
'filerevert-success'        => "'''[[Media:$1|$1]]''' foi revertida a la [$4 versión del $3 a les $2].",
'filerevert-badversion'     => "Nun hai nenguna versión llocal previa d'esti archivu cola fecha conseñada.",

# File deletion
'filedelete'                  => 'Esborrar $1',
'filedelete-legend'           => 'Esborrar archivu',
'filedelete-intro'            => "Tas a piques d'esborrar el ficheru '''[[Media:$1|$1]]''' xunto con tol so historial.",
'filedelete-intro-old'        => "Tas esborrando la versión de '''[[Media:$1|$1]]''' del [$4 $3 a les $2].",
'filedelete-comment'          => 'Motivu:',
'filedelete-submit'           => 'Esborrar',
'filedelete-success'          => "'''$1''' foi esborráu.",
'filedelete-success-old'      => "Eliminóse la versión de '''[[Media:$1|$1]]''' del $2 a les $3.",
'filedelete-nofile'           => "'''$1''' nun esiste.",
'filedelete-nofile-old'       => "Nun hai nenguna versión archivada de  '''$1''' colos atributos especificaos.",
'filedelete-otherreason'      => 'Otru motivu/motivu adicional:',
'filedelete-reason-otherlist' => 'Otru motivu',
'filedelete-reason-dropdown'  => "*Motivos comunes d'esborráu
** Violación de Copyright
** Archivu duplicáu",
'filedelete-edit-reasonlist'  => "Editar los motivos d'esborráu",

# MIME search
'mimesearch'         => 'Busca MIME',
'mimesearch-summary' => "Esta páxina activa'l filtráu d'archivos en función de la so triba MIME. Entrada: contenttype/subtype, p.ex. <tt>image/jpeg</tt>.",
'mimetype'           => 'Triba MIME:',
'download'           => 'descargar',

# Unwatched pages
'unwatchedpages' => 'Páxines ensin vixilar',

# List redirects
'listredirects' => 'Llista de redireiciones',

# Unused templates
'unusedtemplates'     => 'Plantíes ensin usu',
'unusedtemplatestext' => "Esta páxina llista toles páxines del espaciu de nomes {{ns:template}} que nun tán inxeríes n'otres páxines.
Alcuérdate de comprobar otros enllaces a les plantíes enantes d'esborrales.",
'unusedtemplateswlh'  => 'otros enllaces',

# Random page
'randompage'         => 'Páxina al debalu',
'randompage-nopages' => 'Nun hai páxines nel espaciu de nomes "$1".',

# Random redirect
'randomredirect'         => 'Redireición al debalu',
'randomredirect-nopages' => 'Nun hai redireiciones nel espaciu de nomes "$1".',

# Statistics
'statistics'                   => 'Estadístiques',
'statistics-header-pages'      => 'Estadístiques de páxines',
'statistics-header-edits'      => "Estadístiques d'ediciones",
'statistics-header-views'      => 'Estadístiques de visites',
'statistics-header-users'      => "Estadístiques d'usuariu",
'statistics-articles'          => 'Páxines de conteníu',
'statistics-pages'             => 'Páxines',
'statistics-pages-desc'        => "Toles páxines de la wiki, incluyendo páxines d'alderique, redireiciones, etc.",
'statistics-files'             => 'Archivos xubíos',
'statistics-edits'             => "Ediciones de páxines dende qu'entamó {{SITENAME}}",
'statistics-edits-average'     => "Media d'ediciones per páxina",
'statistics-views-total'       => 'Visites totales',
'statistics-views-peredit'     => 'Visites por edición',
'statistics-users'             => '[[Special:ListUsers|Usuarios]] rexistraos',
'statistics-users-active'      => 'Usuarios activos',
'statistics-users-active-desc' => 'Usuarios que realizaron una aición {{PLURAL:$1|nel caberu día|nos caberos $1 díes}}',
'statistics-mostpopular'       => 'Páxines más vistes',

'disambiguations'      => 'Páxines de dixebra',
'disambiguationspage'  => 'Template:dixebra',
'disambiguations-text' => "Les siguientes páxines enllacien a una '''páxina de dixebra'''. En cuenta d'ello habríen enllaciar al artículu apropiáu.<br />Una páxina considérase de dixebra si usa una plantía que tea enllaciada dende [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Redireiciones dobles',
'doubleredirectstext'        => 'Esta páxina llista páxines que redireicionen a otres páxines de redireición. Cada filera contién enllaces a la primer y segunda redireición, asina como al oxetivu de la segunda redireición, que normalmente ye la páxina oxetivu "real", aonde la primer redireición habría empobinar.',
'double-redirect-fixed-move' => '[[$1]] foi treslladáu, agora ye una redireición haza [[$2]]',
'double-redirect-fixer'      => 'Iguador de redireiciones',

'brokenredirects'        => 'Redireiciones rotes',
'brokenredirectstext'    => 'Les siguientes redireiciones enllacien a páxines non esistentes:',
'brokenredirects-edit'   => 'editar',
'brokenredirects-delete' => 'esborrar',

'withoutinterwiki'         => 'Páxines ensin interwikis',
'withoutinterwiki-summary' => "Les páxines siguientes nun enllacien a versiones n'otres llingües:",
'withoutinterwiki-legend'  => 'Prefixu',
'withoutinterwiki-submit'  => 'Amosar',

'fewestrevisions' => "Páxines col menor númberu d'ediciones",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoría|categoríes}}',
'nlinks'                  => '$1 {{PLURAL:$1|enllaz|enllaces}}',
'nmembers'                => '$1 {{PLURAL:$1|miembru|miembros}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisión|revisiones}}',
'nviews'                  => '$1 {{PLURAL:$1|vista|vistes}}',
'specialpage-empty'       => 'Nun hai resultaos nestos momentos.',
'lonelypages'             => 'Páxines güérfanes',
'lonelypagestext'         => 'Les páxines siguientes nun tán enllaciaes nin trescluyíes dende otres páxines de {{SITENAME}}.',
'uncategorizedpages'      => 'Páxines non categorizaes',
'uncategorizedcategories' => 'Categoríes non categorizaes',
'uncategorizedimages'     => 'Archivos non categorizaos',
'uncategorizedtemplates'  => 'Plantíes non categorizaes',
'unusedcategories'        => 'Categoríes non usaes',
'unusedimages'            => 'Imáxenes non usaes',
'popularpages'            => 'Páxines populares',
'wantedcategories'        => 'Categoríes buscaes',
'wantedpages'             => 'Páxines buscaes',
'wantedfiles'             => 'Archivos buscaos',
'wantedtemplates'         => 'Plantíes más buscaes',
'mostlinked'              => 'Páxines más enllaciaes',
'mostlinkedcategories'    => 'Categoríes más enllaciaes',
'mostlinkedtemplates'     => 'Plantíes más enllaciaes',
'mostcategories'          => 'Páxines con más categoríes',
'mostimages'              => 'Archivos más enllaciaos',
'mostrevisions'           => 'Páxines con más revisiones',
'prefixindex'             => 'Toles páxines col prefixu',
'shortpages'              => 'Páxines curties',
'longpages'               => 'Páxines llargues',
'deadendpages'            => 'Páxines ensin salida',
'deadendpagestext'        => 'Les páxines siguientes nun enllacien a páxina dala de {{SITENAME}}.',
'protectedpages'          => 'Páxines protexíes',
'protectedpages-indef'    => 'Namái les proteiciones permanentes',
'protectedpages-cascade'  => 'Namái proteiciones en cascada',
'protectedpagestext'      => "Les páxines siguientes tán protexíes escontra'l treslláu y la edición",
'protectedpagesempty'     => 'Nun hai páxines protexíes anguaño con estos parámetros.',
'protectedtitles'         => 'Títulos protexíos',
'protectedtitlestext'     => 'Los siguiente títulos tán protexíos de la so creación',
'protectedtitlesempty'    => 'Nun hai títulos protexíos anguaño con estos parámetros.',
'listusers'               => "Llista d'usuarios",
'listusers-editsonly'     => 'Amosar namái usuarios con ediciones',
'listusers-creationsort'  => 'Ordenar por data de creación',
'usereditcount'           => '$1 {{PLURAL:$1|edición|ediciones}}',
'newpages'                => 'Páxines nueves',
'newpages-username'       => "Nome d'usuariu:",
'ancientpages'            => 'Páxines más vieyes',
'move'                    => 'Treslladar',
'movethispage'            => 'Treslladar esta páxina',
'unusedimagestext'        => "Por favor, fíxate qu'otros sitios web puen enllazar a una imaxe con una URL direuta, polo que seique tean tovía llistaos equí, magar que tean n'usu activu.",
'unusedcategoriestext'    => "Les siguientes categoríes esisten magar que nengún artículu o categoría faiga usu d'elles.",
'notargettitle'           => 'Nun hai oxetivu',
'notargettext'            => 'Nun especificasti una páxina oxetivu o un usuariu sobre los que realizar esta función.',
'nopagetitle'             => 'Nun esiste la páxina oxetivu',
'nopagetext'              => "La páxina oxetivu qu'especificasti nun esiste.",
'pager-newer-n'           => '{{PLURAL:$1|1 siguiente|$1 siguientes}}',
'pager-older-n'           => '{{PLURAL:$1|1 anterior|$1 anteriores}}',
'suppress'                => 'Güeyador',

# Book sources
'booksources'               => 'Fontes de llibros',
'booksources-search-legend' => 'Busca de fontes de llibros',
'booksources-go'            => 'Dir',
'booksources-text'          => "Esta ye una llista d'enllaces a otros sitios que vienden llibros nuevos y usaos, y que puen tener más información sobre llibros que pueas tar guetando:",
'booksources-invalid-isbn'  => 'El códigu ISBN que puxisti nun paez que valga; mira que te vien copiáu de la fonte orixinal.',

# Special:Log
'specialloguserlabel'  => 'Usuariu:',
'speciallogtitlelabel' => 'Títulu:',
'log'                  => 'Rexistros',
'all-logs-page'        => 'Tolos rexistros',
'alllogstext'          => "Visualización combinada de tolos rexistros disponibles de {{SITENAME}}.
Pues filtrar la visualización seleicionando una mena de rexistru, el nome d'usuariu (teniendo en cuenta les mayúscules y minúscules) o la páxina afectada (teniendo en cuenta tamién les mayúscules y minúscules).",
'logempty'             => 'Nun hai coincidencies nel rexistru.',
'log-title-wildcard'   => "Buscar títulos qu'emprimen con esti testu",

# Special:AllPages
'allpages'          => 'Toles páxines',
'alphaindexline'    => '$1 a $2',
'nextpage'          => 'Páxina siguiente ($1)',
'prevpage'          => 'Páxina anterior ($1)',
'allpagesfrom'      => "Amosar páxines qu'entamen por:",
'allpagesto'        => 'Amosar páxines que finen por:',
'allarticles'       => 'Toles páxines',
'allinnamespace'    => 'Toles páxines (espaciu de nomes $1)',
'allnotinnamespace' => 'Toles páxines (sacantes les del espaciu de nomes $1)',
'allpagesprev'      => 'Anteriores',
'allpagesnext'      => 'Siguientes',
'allpagessubmit'    => 'Dir',
'allpagesprefix'    => 'Amosar páxines col prefixu:',
'allpagesbadtitle'  => "El títulu dau a esta páxina nun yera válidu o tenía un prefixu d'enllaz interllingua o interwiki. Pue contener ún o más carauteres que nun se puen usar nos títulos.",
'allpages-bad-ns'   => '{{SITENAME}} nun tien l\'espaciu de nomes "$1".',

# Special:Categories
'categories'                    => 'Categoríes',
'categoriespagetext'            => "Les categoríes que vienen darréu contienen páxines o archivos multimedia.
Les [[Special:UnusedCategories|categoríes non usaes]] nun s'amuesen equí.
Ver tamién les [[Special:WantedCategories|categoríes más buscaes]].",
'categoriesfrom'                => "Amosar categoríes qu'emprimen por:",
'special-categories-sort-count' => 'ordenar por tamañu',
'special-categories-sort-abc'   => 'ordenar alfabéticamente',

# Special:DeletedContributions
'deletedcontributions' => "Contribuciones d'usuariu esborraes",

# Special:LinkSearch
'linksearch'       => 'Enllaces esternos',
'linksearch-pat'   => 'Patrón de busca:',
'linksearch-ns'    => 'Espaciu de nomes:',
'linksearch-ok'    => 'Buscar',
'linksearch-text'  => 'Puen usase comodinos como "*.wikipedia.org".<br />Protocolos almitíos: <tt>$1</tt>',
'linksearch-line'  => '$1 enllaciáu dende $2',
'linksearch-error' => 'Los comodinos namái puen apaecer al entamu del nome del güéspede.',

# Special:ListUsers
'listusersfrom'      => 'Amosar usuarios emprimando dende:',
'listusers-submit'   => 'Amosar',
'listusers-noresult' => "Nun s'atoparon usuarios.",

# Special:Log/newusers
'newuserlogpage'              => "Rexistru de creación d'usuarios",
'newuserlogpagetext'          => "Esti ye un rexistru de creación d'usuarios.",
'newuserlog-byemail'          => 'clave unviada per corréu electrónicu',
'newuserlog-create-entry'     => 'Usuariu nuevu',
'newuserlog-create2-entry'    => 'creó una cuenta nueva pa $1',
'newuserlog-autocreate-entry' => 'Cuenta creada automáticamente',

# Special:ListGroupRights
'listgrouprights'                 => "Drechos de los grupos d'usuariu",
'listgrouprights-summary'         => "La siguiente ye una llista de grupos d'usuariu definíos nesta wiki, colos sos drechos d'accesu asociaos.
Pue haber [[{{MediaWiki:Listgrouprights-helppage}}|información adicional]] tocante a drechos individuales.",
'listgrouprights-group'           => 'Grupu',
'listgrouprights-rights'          => 'Drechos',
'listgrouprights-helppage'        => 'Help:Drechos de grupu',
'listgrouprights-members'         => '(llista de miembros)',
'listgrouprights-addgroup'        => 'Pue añader {{PLURAL:$2|grupu|grupos}}: $1',
'listgrouprights-removegroup'     => 'Pue quitar {{PLURAL:$2|grupu|grupos}}: $1',
'listgrouprights-addgroup-all'    => 'Pue añader tolos grupos',
'listgrouprights-removegroup-all' => 'Pue quitar tolos grupos',

# E-mail user
'mailnologin'      => "Ensin direición d'unviu",
'mailnologintext'  => 'Has tar [[Special:UserLogin|identificáu]]
y tener una direición de corréu válida nes tos [[Special:Preferences|preferencies]]
pa poder unviar correos a otros usuarios.',
'emailuser'        => 'Manda-y un email a esti usuariu',
'emailpage'        => "Corréu d'usuariu",
'emailpagetext'    => "Pues usar el formulariu d'embaxo pa unviar un corréu electrónicu a esti usuariu.
La direición de corréu electrónicu qu'especificasti nes [[Special:Preferences|tos preferencies d'usuariu]] va apaecer como la direición \"Dende\" del corréu, pa que'l que lo recibe seya quien a respondete direutamente a ti.",
'usermailererror'  => "L'operador de corréu devolvió un error:",
'defemailsubject'  => 'Corréu electrónicu de {{SITENAME}}',
'noemailtitle'     => 'Ensin direición de corréu',
'noemailtext'      => 'Esti usuariu nun especificó una direición de corréu válida.',
'nowikiemailtitle' => "Nun se permite'l corréu electrónicu",
'nowikiemailtext'  => "Esti usuariu nun quier recibir correos d'otros usuarios.",
'email-legend'     => 'Unviar un corréu electrónicu a otru usuariu de {{SITENAME}}',
'emailfrom'        => 'De:',
'emailto'          => 'A:',
'emailsubject'     => 'Asuntu:',
'emailmessage'     => 'Mensaxe:',
'emailsend'        => 'Unviar',
'emailccme'        => 'Unviame per corréu una copia del mio mensaxe.',
'emailccsubject'   => 'Copia del to mensaxe a $1: $2',
'emailsent'        => 'Corréu unviáu',
'emailsenttext'    => 'El to corréu foi unviáu.',
'emailuserfooter'  => 'Esti corréu electrónicu foi unviáu por $1 a $2 per acidu de la funxión "Manda-y un corréu a un usuariu" de {{SITENAME}}.',

# Watchlist
'watchlist'            => 'La mio páxina de vixilancia',
'mywatchlist'          => 'La mio páxina de vixilancia',
'nowatchlist'          => 'La to llista de vixilancia ta vacia.',
'watchlistanontext'    => 'Por favor $1 pa ver o editar entraes na to llista de vixilancia.',
'watchnologin'         => 'Non identificáu',
'watchnologintext'     => 'Tienes que tar [[Special:UserLogin|identificáu]] pa poder camudar la to llista de vixilancia.',
'addedwatch'           => 'Añadida a la llista de vixilancia',
'addedwatchtext'       => 'Añadióse la páxina "[[:$1]]" a la to [[Special:Watchlist|llista de vixilancia]]. Los cambeos nesta páxina y la so páxina d\'alderique asociada van salite en negrina na llista de [[Special:RecentChanges|cambeos recientes]] pa que seya más fácil de vela.

Si más tarde quies quitala de la llista de vixilancia calca en "Dexar de vixilar" nel menú llateral.',
'removedwatch'         => 'Eliminada de la llista de vixilancia',
'removedwatchtext'     => 'Desapuntóse la páxina "[[:$1]]" de la [[Special:Watchlist|to llista de vixilancia]].',
'watch'                => 'Vixilar',
'watchthispage'        => 'Vixilar esta páxina',
'unwatch'              => 'Dexar de vixilar',
'unwatchthispage'      => 'Dexar de vixilar',
'notanarticle'         => 'Nun ye un artículu',
'notvisiblerev'        => 'Esborróse la revisión',
'watchnochange'        => 'Nenguna de les tos páxines vixilaes foi editada nel periodu escoyíu.',
'watchlist-details'    => "{{PLURAL:$1|$1 páxina|$1 páxines}} na to llista de vixilancia ensin cuntar les páxines d'alderique.",
'wlheader-enotif'      => '* La notificación per corréu electrónicu ta activada.',
'wlheader-showupdated' => "* Les páxines camudaes dende la to última visita amuésense en '''negrina'''",
'watchmethod-recent'   => 'buscando páxines vixilaes nos cambeos recientes',
'watchmethod-list'     => 'buscando cambeos recientes nes páxines vixilaes',
'watchlistcontains'    => 'La to llista de vixilancia tien $1 {{PLURAL:$1|páxina|páxines}}.',
'iteminvalidname'      => "Problema col elementu '$1', nome non válidu...",
'wlnote'               => "Abaxo {{PLURAL:$1|ta'l caberu cambéu|tán los caberos '''$1''' cambeos}} {{PLURAL:$2|na cabera hora|nes caberes '''$2''' hores}}.",
'wlshowlast'           => 'Amosar les últimes $1 hores $2 díes $3',
'watchlist-options'    => 'Opciones de la llista de vixilancia',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vixilando...',
'unwatching' => 'Dexando de vixilar...',

'enotif_mailer'                => 'Notificación de corréu de {{SITENAME}}',
'enotif_reset'                 => 'Marcar toles páxines visitaes',
'enotif_newpagetext'           => 'Esta ye una páxina nueva.',
'enotif_impersonal_salutation' => 'Usuariu de {{SITENAME}}',
'changed'                      => 'camudada',
'created'                      => 'creada',
'enotif_subject'               => 'La páxina de {{SITENAME}} $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Mira en $1 tolos cambeos dende la to postrer visita.',
'enotif_lastdiff'              => 'Mira en $1 pa ver esti cambéu.',
'enotif_anon_editor'           => 'usuariu anónimu $1',
'enotif_body'                  => 'Estimáu $WATCHINGUSERNAME,


La páxina de {{SITENAME}} $PAGETITLE foi $CHANGEDORCREATED el $PAGEEDITDATE por $PAGEEDITOR, vete $PAGETITLE_URL pa ver la versión actual.

$NEWPAGE

Resume del editor: $PAGESUMMARY $PAGEMINOREDIT

Contautos del editor:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

En casu de producise más cambeos, nun habrá más notificaciones a nun ser que visites esta páxina. Tamién podríes restablecer na to llista de vixilancia los marcadores de notificación de toles páxines que tengas vixilaes.

             El to abertable sistema de notificación de {{SITENAME}}

--
Pa camudar la configuración de la to llista de vixilancia, visita
{{fullurl:{{#special:Watchlist}}/edit}}

Más aida y sofitu:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Esborrar páxina',
'confirm'                => 'Confirmar',
'excontent'              => "el conteníu yera: '$1'",
'excontentauthor'        => "el conteníu yera: '$1' (y l'únicu autor yera '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "el conteníu enantes de dexar en blanco yera: '$1'",
'exblank'                => 'la páxina taba vacia',
'delete-confirm'         => 'Esborrar "$1"',
'delete-legend'          => 'Esborrar',
'historywarning'         => 'Avisu: La páxina que vas esborrar tien historial:',
'confirmdeletetext'      => "Tas a piques d'esborrar una páxina xunto con tol so historial.
Por favor confirma que ye lo que quies facer, qu'entiendes les consecuencies, y que lo tas faciendo acordies coles [[{{MediaWiki:Policy-url}}|polítiques]].",
'actioncomplete'         => 'Aición completada',
'deletedtext'            => 'Esborróse "<nowiki>$1</nowiki>".
Mira en $2 la llista de les últimes páxines esborraes.',
'deletedarticle'         => 'esborró "[[$1]]"',
'suppressedarticle'      => 'suprimió "[[$1]]"',
'dellogpage'             => "Rexistru d'esborraos",
'dellogpagetext'         => 'Abaxo amuésase una llista de los artículos esborraos más recién.',
'deletionlog'            => 'rexistru de-esborraos',
'reverted'               => 'Revertida a una revisión anterior',
'deletecomment'          => 'Motivu:',
'deleteotherreason'      => 'Otru motivu/motivu adicional:',
'deletereasonotherlist'  => 'Otru motivu',
'deletereason-dropdown'  => "*Motivos comunes d'esborráu
** A pidimientu del autor
** Violación de Copyright
** Vandalismu",
'delete-edit-reasonlist' => "Editar los motivos d'esborráu",
'delete-toobig'          => "Esta páxina tien un historial d'ediciones grande, más de $1 {{PLURAL:$1|revisión|revisiones}}.
Restrinxóse l'esborráu d'estes páxines pa evitar perturbaciones accidentales de {{SITENAME}}.",
'delete-warning-toobig'  => "Esta páxina tien un historial d'ediciones grande, más de $1 {{PLURAL:$1|revisión|revisiones}}.
Esborralu pue perturbar les operaciones de la base de datos de {{SITENAME}};
obra con precaución.",

# Rollback
'rollback'         => 'Revertir ediciones',
'rollback_short'   => 'Revertir',
'rollbacklink'     => 'revertir',
'rollbackfailed'   => 'Falló la reversión',
'cantrollback'     => "Nun se pue revertir la edición; el postrer collaborador ye l'únicu autor d'esta páxina.",
'alreadyrolled'    => 'Nun se pue revertir la postrer edición de [[:$1]] fecha por [[User:$2|$2]] ([[User talk:$2|alderique]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
daquién más yá editó o revirtió la páxina.

La postrer edición foi fecha por [[User:$3|$3]] ([[User talk:$3|alderique]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "El resume de la edición yera: \"''\$1''\".",
'revertpage'       => 'Revertíes les ediciones de [[Special:Contributions/$2|$2]] ([[User talk:$2|alderique]]) hasta la cabera versión de [[User:$1|$1]]',
'rollback-success' => 'Revertíes les ediciones de $1; camudáu a la última versión de $2.',

# Edit tokens
'sessionfailure' => 'Paez qu\'hai un problema cola to sesión; por precaución
cancelóse l\'aición que pidisti. Da-y al botón "Atrás" del
navegador pa cargar otra vuelta la páxina y vuelve a intentalo.',

# Protect
'protectlogpage'              => 'Rexistru de proteiciones',
'protectlogtext'              => 'Esti ye un rexistru de les páxines candaes y descandaes. Consulta la [[Special:ProtectedPages|llista de páxines protexíes]] pa ver les proteiciones actives nestos momentos.',
'protectedarticle'            => 'protexó $1',
'modifiedarticleprotection'   => 'camudó\'l nivel de proteición de "[[$1]]"',
'unprotectedarticle'          => 'desprotexó "[[$1]]"',
'movedarticleprotection'      => 'treslladó los parámetros de proteición dende "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Protexendo "$1"',
'prot_1movedto2'              => '[[$1]] treslladáu a [[$2]]',
'protect-legend'              => 'Confirmar proteición',
'protectcomment'              => 'Motivu:',
'protectexpiry'               => 'Caduca:',
'protect_expiry_invalid'      => 'Caducidá non válida.',
'protect_expiry_old'          => 'La fecha de caducidá ta pasada.',
'protect-text'                => "Equí pues ver y camudar el nivel de proteición de la páxina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Nun pues camudar los niveles de proteición mentes teas bloquiáu. Esta
ye la configuración actual de la páxina '''$1''':",
'protect-locked-dblock'       => "Los niveles de proteición nun puen ser camudaos pol mor d'un candáu activu de
la base de datos. Esta ye la configuración actual de la páxina '''$1''':",
'protect-locked-access'       => "La to cuenta nun tien permisu pa camudar los niveles de proteición de páxina.
Esta ye la configuración actual pa la páxina '''$1''':",
'protect-cascadeon'           => "Esta páxina ta protexida nestos momentos porque ta inxerida {{PLURAL:$1|na siguiente páxina, que tien|nes siguientes páxines, que tienen}} activada la proteición en cascada. Pues camudar el nivel de proteición d'esta páxina, pero nun va afeutar a la proteición en cascada.",
'protect-default'             => 'Permitir tolos usuarios',
'protect-fallback'            => 'Requier el permisu "$1"',
'protect-level-autoconfirmed' => 'Bloquiar usuarios nuevos y non rexistraos',
'protect-level-sysop'         => 'Namái alministradores',
'protect-summary-cascade'     => 'en cascada',
'protect-expiring'            => "caduca'l $1 (UTC)",
'protect-expiry-indefinite'   => 'indefiníu',
'protect-cascade'             => 'Páxines protexíes inxeríes nesta páxina (proteición en cascada)',
'protect-cantedit'            => "Nun pues camudar los niveles de proteición d'esta páxina porque nun tienes permisu pa editala.",
'protect-othertime'           => 'Otru periodu:',
'protect-othertime-op'        => 'otru periodu',
'protect-existing-expiry'     => 'Caducidá actual: $2, $3',
'protect-otherreason'         => 'Otru motivu/motivu adicional:',
'protect-otherreason-op'      => 'otru motivu/motivu adicional',
'protect-dropdown'            => "*Motivos comunes de proteición
** Vandalismu escomanáu
** Spamming escesivu
** Guerra d'ediciones contraproducente
** Páxina de tráficu altu",
'protect-edit-reasonlist'     => 'Editar los motivos de proteición',
'protect-expiry-options'      => '1 hora:1 hour,1 día:1 day,1 selmana:1 week,2 selmanes:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 añu:1 year,pa siempre:infinite',
'restriction-type'            => 'Permisu:',
'restriction-level'           => 'Nivel de restricción:',
'minimum-size'                => 'Tamañu mínimu',
'maximum-size'                => 'Tamañu máximu:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Editar',
'restriction-move'   => 'Treslladar',
'restriction-create' => 'Crear',
'restriction-upload' => 'Xubir',

# Restriction levels
'restriction-level-sysop'         => 'totalmente protexida',
'restriction-level-autoconfirmed' => 'semiprotexida',
'restriction-level-all'           => 'cualesquier nivel',

# Undelete
'undelete'                     => 'Ver páxines esborraes',
'undeletepage'                 => 'Ver y restaurar páxines esborraes',
'undeletepagetitle'            => "'''Les siguientes son les revisiones esborraes de [[:$1]]'''.",
'viewdeletedpage'              => 'Ver páxines esborraes',
'undeletepagetext'             => "{{PLURAL:$1|La siguiente páxina foi esborrada pero tovía ta nel archivu y pue ser restauráu|Les $1 páxines siguientes foron esborraes pero tovía tán nel archivu y puen ser restauraes}}. L'archivu pue ser purgáu periódicamente.",
'undelete-fieldset-title'      => 'Restaurar revisiones',
'undeleteextrahelp'            => "Pa restaurar tol historial de la páxina, deseleiciona toles caxelles y calca en '''''Restaurar'''''.
Pa realizar una restauración selectiva, seleiciona les caxelles de la revisión que quies restaurar y calca en '''''Restaurar'''''.
Calcando en '''''Llimpiar''''' quedarán vacios el campu de comentarios y toles caxelles.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revisión archivada|revisiones archivaes}}',
'undeletehistory'              => 'Si restaures la páxina, restauraránse toles revisiones al historial.
Si se creó una páxina col mesmu nome dende que fuera esborrada, les revisiones restauraes van apaecer nel historial anterior.',
'undeleterevdel'               => 'Nun se fadrá la restauración si ésta provoca un esborráu parcial de la páxina cimera o de la revisión
del archivu. Nestos casos, tienes que desmarcar o amosar les revisiones esborraes más recién.',
'undeletehistorynoadmin'       => "Esta páxina foi esborrada. El motivu del esborráu amuésase
nel resume d'embaxo, amás de detalles de los usuarios qu'editaron esta páxina enantes
de ser esborrada. El testu actual d'estes revisiones esborraes ta disponible namái pa los alministradores.",
'undelete-revision'            => 'Revisión esborrada de $1 ($4, a les $5) fecha por $3:',
'undeleterevision-missing'     => "Falta la revisión o nun ye válida. Sieque l'enllaz nun seya correutu, o que la
revisión fuera restaurada o eliminada del archivu.",
'undelete-nodiff'              => "Nun s'atopó revisión previa.",
'undeletebtn'                  => 'Restaurar',
'undeletelink'                 => 'amosar/restaurar',
'undeletereset'                => 'Llimpiar',
'undeleteinvert'               => 'Invertir seleición',
'undeletecomment'              => 'Comentariu:',
'undeletedarticle'             => 'restauró "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|1 revisión restaurada|$1 revisiones restauraes}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 revisión|$1 revisiones}} y {{PLURAL:$2|1 archivu|$2 archivos}} restauraos',
'undeletedfiles'               => '{{PLURAL:$1|1 archivu restauráu|$1 archivos restauraos}}',
'cannotundelete'               => 'Falló la restauración; seique daquién yá restaurara la páxina enantes.',
'undeletedpage'                => "'''Restauróse $1'''

Consulta'l [[Special:Log/delete|rexistru d'esborraos]] pa ver los esborraos y restauraciones de recién.",
'undelete-header'              => "Mira nel [[Special:Log/delete|rexistru d'esborraos]] les páxines esborraes recién.",
'undelete-search-box'          => 'Buscar páxines esborraes',
'undelete-search-prefix'       => "Amosar páxines qu'empecipien por:",
'undelete-search-submit'       => 'Buscar',
'undelete-no-results'          => "Nun s'atoparon páxines afechisques a la busca nel archivu d'esborraos.",
'undelete-filename-mismatch'   => "Nun se pue restaurar la revisión del archivu con fecha $1: el nome d'archivu nun concuaya",
'undelete-bad-store-key'       => "Nun se pue restaurar la revisión del archivu con fecha $1: yá nun esistía l'archivu nel momentu d'esborralu.",
'undelete-cleanup-error'       => 'Error al esborrar l\'archivu non usáu "$1".',
'undelete-missing-filearchive' => "Nun se pue restaurar l'archivu col númberu d'identificación $1 porque nun ta na base de datos. Seique yá fuera restauráu.",
'undelete-error-short'         => "Error al restaurar l'archivu: $1",
'undelete-error-long'          => "Atopáronse errores al restaurar l'archivu:

$1",
'undelete-show-file-confirm'   => '¿Tas seguru de que quies ver una versión desaniciada del ficheru "<nowiki>$1</nowiki>" del $2 a les $3?',
'undelete-show-file-submit'    => 'Sí',

# Namespace form on various pages
'namespace'      => 'Espaciu de nomes:',
'invert'         => 'Invertir seleición',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Contribuciones del usuariu',
'contributions-title' => "Contribuciones d'usuariu pa $1",
'mycontris'           => 'Les mios contribuciones',
'contribsub2'         => 'De $1 ($2)',
'nocontribs'          => "Nun s'atoparon cambeos que coincidan con esi criteriu.",
'uctop'               => '(últimu cambéu)',
'month'               => "Dende'l mes (y anteriores):",
'year'                => "Dende l'añu (y anteriores):",

'sp-contributions-newbies'       => 'Amosar namái les contribuciones de cuentes nueves',
'sp-contributions-newbies-sub'   => 'Namái les cuentes nueves',
'sp-contributions-newbies-title' => "Contribuciones d'usuariu pa cuentes nueves",
'sp-contributions-blocklog'      => 'Rexistru de bloqueos',
'sp-contributions-talk'          => 'Alderique',
'sp-contributions-search'        => 'Buscar contribuciones',
'sp-contributions-username'      => "Direición IP o nome d'usuariu:",
'sp-contributions-submit'        => 'Buscar',

# What links here
'whatlinkshere'            => "Lo qu'enllaza equí",
'whatlinkshere-title'      => 'Páxines qu\'enllacien a "$1"',
'whatlinkshere-page'       => 'Páxina:',
'linkshere'                => "Les páxines siguientes enllacien a '''[[:$1]]''':",
'nolinkshere'              => "Nenguna páxina enllaza a '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nenguna páxina enllaza a '''[[:$1]]''' nel espaciu de nome conseñáu.",
'isredirect'               => 'páxina redirixida',
'istemplate'               => 'inclusión',
'isimage'                  => "enllaz d'imaxe",
'whatlinkshere-prev'       => '{{PLURAL:$1|anterior|anteriores $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|siguiente|siguientes $1}}',
'whatlinkshere-links'      => '← enllaces',
'whatlinkshere-hideredirs' => '$1 redireiciones',
'whatlinkshere-hidetrans'  => '$1 tresclusiones',
'whatlinkshere-hidelinks'  => '$1 enllaces',
'whatlinkshere-hideimages' => "$1 enllaces d'imaxe",
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                         => 'Bloquiar usuariu',
'blockip-legend'                  => 'Bloquiar usuariu',
'blockiptext'                     => "Usa'l siguiente formulariu pa bloquiar el permisu d'escritura a una IP o a un usuariu concretu.
Esto debería facese sólo pa prevenir vandalismu como indiquen les [[{{MediaWiki:Policy-url}}|polítiques]]. Da un motivu específicu (como por exemplu citar páxines que fueron vandalizaes).",
'ipaddress'                       => 'Direición IP:',
'ipadressorusername'              => "Direición IP o nome d'usuariu:",
'ipbexpiry'                       => 'Caducidá:',
'ipbreason'                       => 'Motivu:',
'ipbreasonotherlist'              => 'Otru motivu',
'ipbreason-dropdown'              => "*Motivos comunes de bloquéu
** Enxertamientu d'información falso
** Dexar les páxines en blanco
** Enllaces spam a páxines esternes
** Enxertamientu de babayaes/enguedeyos nes páxines
** Comportamientu intimidatoriu o d'acosu
** Abusu de cuentes múltiples
** Nome d'usuariu inaceutable",
'ipbanononly'                     => 'Bloquiar namái usuarios anónimos',
'ipbcreateaccount'                => 'Evitar creación de cuentes',
'ipbemailban'                     => "Torgar al usuariu l'unviu de corréu electrónicu",
'ipbenableautoblock'              => "Bloquiar automáticamente la cabera direición IP usada por esti usuariu y toles IP posteriores dende les qu'intente editar",
'ipbsubmit'                       => 'Bloquiar esti usuariu',
'ipbother'                        => 'Otru periodu:',
'ipboptions'                      => '2 hores:2 hours,1 día:1 day,3 díes:3 days,1 selmana:1 week,2 selmanes:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 añu:1 year,pa siempre:infinite',
'ipbotheroption'                  => 'otru',
'ipbotherreason'                  => 'Otru motivu/motivu adicional:',
'ipbhidename'                     => "Ocultar el nome d'usuariu del rexistru de bloqueos, de la llista de bloqueos activos y de la llista d'usuarios",
'ipbwatchuser'                    => "Vixilar les páxines d'usuariu y d'alderique d'esti usuariu",
'ipballowusertalk'                => "Permite a esti usuariu editar la páxina d'alderique propia mentes ta bloquiáu",
'ipb-change-block'                => "Volver a bloquiar l'usuariu con estos parámetros",
'badipaddress'                    => 'IP non válida',
'blockipsuccesssub'               => 'Bloquéu fechu correctamente',
'blockipsuccesstext'              => "Bloquióse al usuariu [[Special:Contributions/$1|$1]].
<br />Mira na [[Special:IPBlockList|llista d'IPs bloquiaes]] pa revisar los bloqueos.",
'ipb-edit-dropdown'               => 'Editar motivos de bloquéu',
'ipb-unblock-addr'                => 'Desbloquiar $1',
'ipb-unblock'                     => "Desbloquiar un nome d'usuariu o direición IP",
'ipb-blocklist-addr'              => 'Bloqueos esistentes de $1',
'ipb-blocklist'                   => 'Ver los bloqueos esistentes',
'ipb-blocklist-contribs'          => 'Contribuciones de $1',
'unblockip'                       => 'Desbloquiar usuariu',
'unblockiptext'                   => "Usa'l formulariu d'abaxo pa restablecer l'accesu d'escritura a una direicion IP o a un nome d'usuariu previamente bloquiáu.",
'ipusubmit'                       => 'Desaniciar esti bloquéu',
'unblocked'                       => '[[User:$1|$1]] foi desbloquiáu',
'unblocked-id'                    => 'El bloquéu $1 foi elimináu',
'ipblocklist'                     => "Direiciones IP y nomes d'usuarios bloquiaos",
'ipblocklist-legend'              => 'Atopar un usuariu bloquiáu',
'ipblocklist-username'            => "Nome d'usuariu o direición IP:",
'ipblocklist-sh-userblocks'       => '$1 los bloqueos de cuenta',
'ipblocklist-sh-tempblocks'       => '$1 los bloqueos temporales',
'ipblocklist-sh-addressblocks'    => "$1 los bloqueos d'IP simples",
'ipblocklist-submit'              => 'Buscar',
'blocklistline'                   => '$1, $2 bloquió a $3 ($4)',
'infiniteblock'                   => 'pa siempre',
'expiringblock'                   => "caduca'l $1 $2",
'anononlyblock'                   => 'namái anón.',
'noautoblockblock'                => 'bloquéu automáticu desactiváu',
'createaccountblock'              => 'bloquiada la creación de cuentes',
'emailblock'                      => 'corréu electrónicu bloquiáu',
'blocklist-nousertalk'            => "nun pue editar la so páxina d'alderique",
'ipblocklist-empty'               => 'La llista de bloqueos ta vacia.',
'ipblocklist-no-results'          => "La direición IP o nome d'usuariu solicitáu nun ta bloquiáu.",
'blocklink'                       => 'bloquiar',
'unblocklink'                     => 'desbloquiar',
'change-blocklink'                => 'camudar el bloquéu',
'contribslink'                    => 'contribuciones',
'autoblocker'                     => 'Bloquiáu automáticamente porque la to direición IP foi usada recién por "[[User:$1|$1]]". El motivu del bloquéu de $1 ye: "$2"',
'blocklogpage'                    => 'Rexistru de bloqueos',
'blocklogentry'                   => 'bloquió [[$1]] con una caducidá de $2 $3',
'reblock-logentry'                => 'camudó los parámetros de bloquéu de [[$1]] con una caducidá de $2 $3',
'blocklogtext'                    => "Esti ye un rexistru de los bloqueos y desbloqueos d'usuarios.
Les direcciones IP bloquiaes automáticamente nun salen equí.
Pa ver los bloqueos qu'hai agora mesmo, mira na [[Special:IPBlockList|llista d'IP bloquiaes]].",
'unblocklogentry'                 => 'desbloquió $1',
'block-log-flags-anononly'        => 'namái usuarios anónimos',
'block-log-flags-nocreate'        => 'creación de cuentes deshabilitada',
'block-log-flags-noautoblock'     => 'bloquéu automáticu deshabilitáu',
'block-log-flags-noemail'         => 'corréu electrónicu bloquiáu',
'block-log-flags-nousertalk'      => "nun pue editar la páxina d'alderique propia",
'block-log-flags-angry-autoblock' => 'autobloquéu ameyoráu activáu',
'range_block_disabled'            => "La capacidá d'alministrador pa crear bloqueos d'intervalos ta desactivada.",
'ipb_expiry_invalid'              => 'Tiempu incorrectu.',
'ipb_expiry_temp'                 => "Los bloqueos de nome d'usuariu escondíos han ser permanentes.",
'ipb_already_blocked'             => '"$1" yá ta bloqueáu',
'ipb-needreblock'                 => '== Yá bloquiáu ==
$1 yá ta bloquiáu. ¿Quies camudar los parámetros?',
'ipb_cant_unblock'                => "Error: Nun s'atopó'l bloquéu númberu $1. Seique yá fuera desbloquiáu.",
'ipb_blocked_as_range'            => 'Error: La IP $1 nun ta bloquiada direutamente, polo que nun pue ser desloquiada. Sicasí, foi bloquiada como parte del intervalu $2, que pue ser desbloquiáu.',
'ip_range_invalid'                => 'Rangu IP non válidu.',
'blockme'                         => 'Blóquiame',
'proxyblocker'                    => 'Bloquiador de proxys',
'proxyblocker-disabled'           => 'Esta función ta deshabilitada.',
'proxyblockreason'                => "La to direición IP foi bloquiada porque ye un proxy abiertu. Por favor contauta col to proveedor de serviciones d'Internet o col to servicio d'asistencia téunica y infórmalos d'esti seriu problema de seguridá.",
'proxyblocksuccess'               => 'Fecho.',
'sorbsreason'                     => 'La to direición IP sal na llista de proxys abiertos en DNSBL usada por {{SITENAME}}.',
'sorbs_create_account_reason'     => 'La to direición IP sal na llista de proxys abiertos en DNSBL usada por {{SITENAME}}. Nun pues crear una cuenta',
'cant-block-while-blocked'        => 'Nun pues bloquiar a otros usuarios mentes tu teas bloquiáu.',

# Developer tools
'lockdb'              => 'Protexer la base de datos',
'unlockdb'            => 'Desprotexer la base de datos',
'lockdbtext'          => "Al protexer la base de datos suspenderáse la capacidá de tolos
usuarios pa editar páxines, camudar les sos preferencies, editar
les sos llistes de vixilancia y otres aiciones que requieran
cambeos na base de datos. Por favor confirma que ye lo que quies facer,
y que vas descandar la base de datos cuando fine'l so mantenimientu.",
'unlockdbtext'        => 'Al desprotexer la base de datos restauraráse la capacida de tolos
usuarios pa editar páxines, camudar les sos preferencies, editar
les sos llistes de vixilancia y otres aiciones que requieren cambeos
na base de datos. Por favor confirma que ye lo quies facer.',
'lockconfirm'         => 'Si, quiero candar daveres la base de datos.',
'unlockconfirm'       => 'Sí, quiero descandar daveres la base de datos.',
'lockbtn'             => 'Protexer la base de datos',
'unlockbtn'           => 'Desprotexer la base de datos',
'locknoconfirm'       => 'Nun activasti la caxa de confirmación.',
'lockdbsuccesssub'    => 'Candáu de la base de datos efeutuáu correutamente',
'unlockdbsuccesssub'  => 'Candáu de la base de datos elimináu',
'lockdbsuccesstext'   => "Candóse la base de datos.
<br />Alcuérdate de [[Special:UnlockDB|descandala]] depués d'acabar el so mantenimientu.",
'unlockdbsuccesstext' => 'La base de datos foi descandada.',
'lockfilenotwritable' => "L'archivu de candáu de la base de datos nun ye escribible. Pa candar o descandar la base de datos esti tien que poder ser modificáu pol sirvidor.",
'databasenotlocked'   => 'La base de datos nun ta candada.',

# Move page
'move-page'                    => 'Treslladar $1',
'move-page-legend'             => 'Treslladar páxina',
'movepagetext'                 => "Usando'l siguiente formulariu vas renomar una páxina, treslladando'l so historial al nuevu nome.
El nome vieyu va convertise nuna redireición al nuevu.
Pues actualizar redireiciones qu'enllacien al títulu orixinal automáticamente.
Si prefieres nun lo facer, asegúrate de que nun dexes [[Special:DoubleRedirects|redireiciones dobles]] o [[Special:BrokenRedirects|rotes]].
Tu yes el responsable de facer que los enllaces queden apuntando aonde se supón qu'han apuntar.

Recuerda que la páxina '''nun''' va movese si yá hai una páxina col nuevu títulu, a nun ser que tea vacia o seya una redireición que nun tenga historial.
Esto significa que pues volver a renomar una páxina col nome orixinal si t'enquivoques, y que nun pues sobreescribir una páxina yá esistente.

¡AVISU!'''
Esti pue ser un cambéu importante y inesperáu pa una páxina popular;
por favor, asegúrate d'entender les consecuencies de lo que vas facer enantes de siguir.",
'movepagetalktext'             => "La páxina d'alderique asociada va ser treslladada automáticamente '''a nun ser que:'''
*Yá esista una páxina d'alderique non vacia col nuevu nome, o
*Desactives la caxella d'equí baxo.

Nestos casos vas tener que treslladar o fusionar la páxina manualmente.",
'movearticle'                  => 'Treslladar la páxina:',
'movenologin'                  => 'Non identificáu',
'movenologintext'              => 'Tienes que ser un usuariu rexistráu y tar [[Special:UserLogin|identificáu]] pa treslladar una páxina.',
'movenotallowed'               => 'Nun tienes permisu pa mover páxines.',
'movenotallowedfile'           => 'Nun tienes permisu pa mover ficheros.',
'cant-move-user-page'          => "Nun tienes permisu pa treslladar páxines d'usuariu (independientemente de les subpáxines).",
'cant-move-to-user-page'       => "Nun tienes permisu pa treslladar una páxina a una páxina d'usuariu (sacante a una subpáxina d'usuariu).",
'newtitle'                     => 'Al títulu nuevu:',
'move-watch'                   => 'Vixilar esta páxina',
'movepagebtn'                  => 'Treslladar la páxina',
'pagemovedsub'                 => 'Treslláu correctu',
'movepage-moved'               => '\'\'\'"$1" treslladóse a "$2"\'\'\'',
'movepage-moved-redirect'      => 'Creóse una redireición.',
'movepage-moved-noredirect'    => "Desaniciose la creación d'una redireición.",
'articleexists'                => "Yá hai una páxina con esi nome, o'l nome qu'escoyisti nun ye válidu. Por favor, escueyi otru nome.",
'cantmove-titleprotected'      => "Nun pues mover una páxina a esti llugar porque'l nuevu títulu foi protexíu de la so creación",
'talkexists'                   => "'''La páxina treslladóse correutamente, pero non la so páxina d'alderique porque yá esiste una col títulu nuevu. Por favor, fusiónala manualmente.'''",
'movedto'                      => 'treslladáu a',
'movetalk'                     => "Mover la páxina d'alderique asociada",
'move-subpages'                => 'Treslladar les subpáxines (hasta $1)',
'move-talk-subpages'           => "Treslladar les subpáxines de la páxina d'alderique (hasta $1)",
'movepage-page-exists'         => 'La páxina $1 yá esiste y nun se pue sobreescribir automáticamente.',
'movepage-page-moved'          => 'Treslladóse la páxina $1 a $2.',
'movepage-page-unmoved'        => 'Nun se pudo treslladar la páxina $1 a $2.',
'movepage-max-pages'           => "Treslladóse'l máximu de $1 {{PLURAL:$1|páxina|páxinees}} y nun van treslladase más automáticamente.",
'1movedto2'                    => '[[$1]] treslladáu a [[$2]]',
'1movedto2_redir'              => '[[$1]] treslladáu a [[$2]] sobre una redireición',
'move-redirect-suppressed'     => 'redireición desaniciada',
'movelogpage'                  => 'Rexistru de tresllaos',
'movelogpagetext'              => 'Esta ye la llista de páxines treslladaes.',
'movereason'                   => 'Motivu:',
'revertmove'                   => 'revertir',
'delete_and_move'              => 'Esborrar y treslladar',
'delete_and_move_text'         => '==Necesítase esborrar==

La páxina de destín "[[:$1]]" yá esiste. ¿Quies esborrala pa dexar sitiu pal treslláu?',
'delete_and_move_confirm'      => 'Sí, esborrar la páxina',
'delete_and_move_reason'       => 'Esborrada pa facer sitiu pal treslláu',
'selfmove'                     => "Los nomes d'orixe y destín son los mesmos, nun se pue treslladar una páxina sobre ella mesma.",
'immobile-source-namespace'    => 'Nun se puen treslladar páxines nel espaciu de nomes "$1"',
'immobile-target-namespace'    => 'Nun se puen treslladar páxines al espaciu de nomes "$1"',
'immobile-target-namespace-iw' => "Nun puedes mover una páxina a un enllaz d'Interwiki.",
'immobile-source-page'         => 'Esta páxina nun ye treslladable.',
'immobile-target-page'         => 'Nun se pue treslladar a esi títulu de destín.',
'imagenocrossnamespace'        => "Nun se pue treslladar una imaxe a nun espaciu de nomes que nun ye d'imáxenes",
'imagetypemismatch'            => 'La estensión nueva del archivu nun concueya cola so mena',
'imageinvalidfilename'         => 'El nome del archivu oxetivu nun ye válidu',
'fix-double-redirects'         => 'Actualizar cualesquier redireición que señale al títulu orixinal',
'move-leave-redirect'          => 'Dexar una redireición detrás',

# Export
'export'            => 'Esportar páxines',
'exporttext'        => "Pues esportar el testu y l'historial d'ediciones d'una páxina en particular o d'una
riestra páxines endolcaes nun documentu XML. Esti pue ser importáu depués n'otra wiki
qu'use MediaWiki al traviés de la páxina [[Special:Import|importar]].

Pa esportar páxines, pon los títulos na caxa de testu d'embaxo, un títulu per llinia,
y seleiciona si quies la versión actual xunto con toles versiones antigües, xunto col
so historial, o namái la versión actual cola información de la postrer edición.

Por último, tamién pues usar un enllaz: p.e. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pa la páxina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Amestar namái la revisión actual, non tol historial',
'exportnohistory'   => "----
'''Nota:''' Desactivóse la esportación del historial completu de páxines al traviés d'esti formulariu por motivos de rendimientu.",
'export-submit'     => 'Esportar',
'export-addcattext' => 'Añader páxines dende la categoría:',
'export-addcat'     => 'Añader',
'export-download'   => 'Guardar como archivu',
'export-templates'  => 'Inxerir plantíes',

# Namespace 8 related
'allmessages'               => 'Tolos mensaxes del sistema',
'allmessagesname'           => 'Nome',
'allmessagesdefault'        => 'Testu por defeutu',
'allmessagescurrent'        => 'Testu actual',
'allmessagestext'           => 'Esta ye una llista de los mensaxes de sistema disponibles nel espaciu de nomes de MediaWiki.
Por favor visita [http://www.mediawiki.org/wiki/Localisation Llocalización de MediaWiki] y [http://translatewiki.net translatewiki.net] si quies contribuyer a la llocalización xenérica de MediaWiki.',
'allmessagesnotsupportedDB' => "Nun pue usase '''{{ns:special}}:Allmessages''' porque '''\$wgUseDatabaseMessages''' ta deshabilitáu.",

# Thumbnails
'thumbnail-more'           => 'Agrandar',
'filemissing'              => 'Falta archivu',
'thumbnail_error'          => 'Error al crear la miniatura: $1',
'djvu_page_error'          => 'Páxina DjVu fuera de llímites',
'djvu_no_xml'              => 'Nun se pudo obtener el XML pal archivu DjVu',
'thumbnail_invalid_params' => 'Parámetros de miniatura non válidos',
'thumbnail_dest_directory' => 'Nun se pue crear el direutoriu de destín',

# Special:Import
'import'                     => 'Importar páxines',
'importinterwiki'            => 'Importación treswiki',
'import-interwiki-text'      => "Seleiciona una wiki y un títulu de páxina pa importar.
Les feches de revisión y los nomes de los editores caltendránse.
Toles aiciones d'importación treswiki queden rexistraes nel [[Special:Log/import|rexistru d'importaciones]].",
'import-interwiki-source'    => 'Códigu wiki/páxina:',
'import-interwiki-history'   => "Copiar toles versiones d'historial d'esta páxina",
'import-interwiki-templates' => 'Incluyir toles plantíes',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Espaciu de nomes de destín:',
'import-upload-filename'     => 'Nome del ficheru:',
'import-comment'             => 'Comentariu:',
'importtext'                 => "Por favor, esporta l'archivu dende la wiki d'orixe usando la [[Special:Export|utilidá d'esportación]].
Guárdalu nel to ordenador y xúbilu equí.",
'importstart'                => 'Importando les páxines...',
'import-revision-count'      => '$1 {{PLURAL:$1|revisión|revisiones}}',
'importnopages'              => 'Nun hai páxines pa importar.',
'importfailed'               => 'Falló la importación: $1',
'importunknownsource'        => "Triba d'orixe d'importación desconocida",
'importcantopen'             => "Nun se pudo abrir l'archivu d'importación",
'importbadinterwiki'         => 'Enllaz interwiki incorreutu',
'importnotext'               => 'Vaciu o ensin testu',
'importsuccess'              => '¡Importación finalizada!',
'importhistoryconflict'      => 'Existe un conflictu na revisión del historial (seique esta páxina fuera importada previamente)',
'importnosources'            => "Nun se definió l'orixe de la importación treswiki y les xubíes direutes del historial tán deshabilitaes.",
'importnofile'               => "Nun se xubió nengún archivu d'importación.",
'importuploaderrorsize'      => "Falló la xubida del archivu d'importación. L'archivu ye más grande que'l tamañu permitíu de xubida.",
'importuploaderrorpartial'   => "Falló la xubida del archivu d'importación. L'archivu xubióse solo parcialmente.",
'importuploaderrortemp'      => "Falló la xubida del archivu d'importación. Falta una carpeta temporal.",
'import-parse-failure'       => "Fallu nel análisis d'importación XML",
'import-noarticle'           => '¡Nun hai páxina pa importar!',
'import-nonewrevisions'      => 'Toles revisiones fueran importaes previamente.',
'xml-error-string'           => '$1 na llinia $2, col $3 (byte $4): $5',
'import-upload'              => 'Xubir datos XML',
'import-token-mismatch'      => 'Perdiéronse los datos de la sesión. Intentalo otra vuelta.',
'import-invalid-interwiki'   => "Nun se puede importar d'esi wiki.",

# Import log
'importlogpage'                    => "Rexistru d'importaciones",
'importlogpagetext'                => "Importaciones alministrativas de páxines con historial d'ediciones d'otres wikis.",
'import-logentry-upload'           => "importada [[$1]] per aciud d'una xuba d'archivu",
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revisión|revisiones}}',
'import-logentry-interwiki'        => 'treswikificada $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisión|revisiones}} dende $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "La to páxina d'usuariu",
'tooltip-pt-anonuserpage'         => "La páxina d'usuariu de la IP cola que tas editando",
'tooltip-pt-mytalk'               => "La to páxina d'alderique",
'tooltip-pt-anontalk'             => 'Alderique de les ediciones feches con esta direición IP',
'tooltip-pt-preferences'          => 'Les mios preferencies',
'tooltip-pt-watchlist'            => 'Llista de les páxines nes que tas vixilando los cambeos',
'tooltip-pt-mycontris'            => 'Llista de les tos contribuciones',
'tooltip-pt-login'                => 'Encamentámoste a identificate, anque nun ye obligatorio',
'tooltip-pt-anonlogin'            => "Encamiéntasete que t'identifiques, anque nun ye obligatorio.",
'tooltip-pt-logout'               => 'Salir',
'tooltip-ca-talk'                 => 'Alderique tocante al conteníu de la páxina',
'tooltip-ca-edit'                 => "Pues editar esta páxina. Por favor usa'l botón de previsualización enantes de guardar los cambeos.",
'tooltip-ca-addsection'           => 'Emprima una seición nueva',
'tooltip-ca-viewsource'           => 'Esta páxina ta protexida. Pues ver el so códigu fonte.',
'tooltip-ca-history'              => "Versiones antigües d'esta páxina.",
'tooltip-ca-protect'              => 'Protexe esta páxina',
'tooltip-ca-delete'               => 'Esborra esta páxina',
'tooltip-ca-undelete'             => 'Restaura les ediciones feches nesta páxina enantes de que fuera esborrada',
'tooltip-ca-move'                 => 'Tresllada esta páxina',
'tooltip-ca-watch'                => 'Añade esta páxina a la to llista de vixilancia',
'tooltip-ca-unwatch'              => 'Elimina esta páxina de la to llista de vixilancia',
'tooltip-search'                  => 'Busca en {{SITENAME}}',
'tooltip-search-go'               => 'Llévate a una páxina con esti nome exautu si esiste',
'tooltip-search-fulltext'         => 'Busca páxines con esti testu',
'tooltip-p-logo'                  => 'Portada',
'tooltip-n-mainpage'              => 'Visita a la Portada',
'tooltip-n-mainpage-description'  => "Dir a la páxina d'aniciu",
'tooltip-n-portal'                => 'Tocante al proyeutu, qué facer, ú atopar coses',
'tooltip-n-currentevents'         => 'Información sobre los asocedíos actuales',
'tooltip-n-recentchanges'         => 'Llista de los cambeos recientes de la wiki.',
'tooltip-n-randompage'            => 'Carga una páxina al debalu',
'tooltip-n-help'                  => 'El llugar pa deprender',
'tooltip-t-whatlinkshere'         => "Llista de toles páxines wiki qu'enllacien equí",
'tooltip-t-recentchangeslinked'   => "Cambeos recientes en páxines qu'enllacien dende esta páxina",
'tooltip-feed-rss'                => 'Canal RSS pa esta páxina',
'tooltip-feed-atom'               => 'Canal Atom pa esta páxina',
'tooltip-t-contributions'         => "Amuesa la llista de contribuciones d'esti usuariu",
'tooltip-t-emailuser'             => 'Unvia un corréu a esti usuariu',
'tooltip-t-upload'                => 'Xube archivos',
'tooltip-t-specialpages'          => 'Llista de toles páxines especiales',
'tooltip-t-print'                 => "Versión imprentable d'esta páxina",
'tooltip-t-permalink'             => 'Enllaz permanente a esta versión de la páxina',
'tooltip-ca-nstab-main'           => "Amuesa l'artículu",
'tooltip-ca-nstab-user'           => "Amuesa la páxina d'usuariu",
'tooltip-ca-nstab-media'          => 'Amuesa la páxina de multimedia',
'tooltip-ca-nstab-special'        => 'Esta ye una páxina especial, nun puedes editar la páxina',
'tooltip-ca-nstab-project'        => 'Amuesa la páxina de proyeutu',
'tooltip-ca-nstab-image'          => 'Amuesa la páxina del archivu',
'tooltip-ca-nstab-mediawiki'      => "Amuesa'l mensaxe de sistema",
'tooltip-ca-nstab-template'       => 'Amuesa la plantía',
'tooltip-ca-nstab-help'           => "Amuesa la páxina d'aida",
'tooltip-ca-nstab-category'       => 'Amuesa la páxina de categoría',
'tooltip-minoredit'               => 'Marca esti cambéu como una edición menor',
'tooltip-save'                    => 'Guarda los tos cambeos',
'tooltip-preview'                 => 'Previsualiza los tos cambeos. ¡Por favor, úsalo enantes de grabar!',
'tooltip-diff'                    => 'Amuesa los cambeos que fixisti nel testu.',
'tooltip-compareselectedversions' => "Amuesa les diferencies ente les dos versiones seleicionaes d'esta páxina.",
'tooltip-watch'                   => 'Amiesta esta páxina na to llista de vixilancia',
'tooltip-recreate'                => 'Vuelve a crear la páxina magar que se tenga esborrao',
'tooltip-upload'                  => 'Empecipiar la xubida',
'tooltip-rollback'                => '"Revertir" elimina la edición/es d\'esta páxina fecha/es pol postrer collaborador con un solu clic.',
'tooltip-undo'                    => '"Esfacer" revierte esta edición y abre\'l formulariu d\'edición en mou de previsualización. Permite añader un motivu nel resume.',

# Metadata
'nodublincore'      => 'Metadatos RDF Dublin Core desactivaos pa esti sirvidor.',
'nocreativecommons' => 'Metadatos RDF Creative Commons desactivaos pa esti sirvidor.',
'notacceptable'     => 'El sirvidor de la wiki nun pue suplir los datos nun formatu llexible pol to navegador.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Usuariu anónimu|Usuarios anónimos}} de {{SITENAME}}',
'siteuser'         => '{{SITENAME}} usuariu $1',
'lastmodifiedatby' => "Esta páxina foi modificada per postrer vegada'l $1 a les $2 por $3.",
'othercontribs'    => 'Basao nel trabayu fechu por $1.',
'others'           => 'otros',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|usuariu|usuarios}} $1',
'creditspage'      => 'Páxina de creitos',
'nocredits'        => 'Nun hai disponible información de creitos pa esta páxina.',

# Spam protection
'spamprotectiontitle' => 'Filtru de proteición de spam',
'spamprotectiontext'  => 'La páxina que queríes guardar foi bloquiada pol filtru de spam.
Probablemente tea causao por un enllaz a un sitiu esternu de la llista prieta.',
'spamprotectionmatch' => "El testu siguiente foi'l qu'activó'l nuesu filtru de spam: $1",
'spambot_username'    => 'Llimpieza de spam de MediaWiki',
'spam_reverting'      => 'Revirtiendo a la cabera versión que nun contién enllaces a $1',
'spam_blanking'       => 'Toles revisiones teníen enllaces a $1; dexando en blanco',

# Info page
'infosubtitle'   => 'Información de la páxina',
'numedits'       => "Númberu d'ediciones (páxina): $1",
'numtalkedits'   => "Númberu d'ediciones (páxina d'alderique): $1",
'numwatchers'    => "Númberu d'usuarios vixilando: $1",
'numauthors'     => "Númberu d'autores distintos (páxina): $1",
'numtalkauthors' => "Númberu d'autores distintos (páxina d'alderique): $1",

# Math options
'mw_math_png'    => 'Renderizar siempre PNG',
'mw_math_simple' => 'HTML si ye mui simple, o si non PNG',
'mw_math_html'   => 'HTML si ye posible, o si non PNG',
'mw_math_source' => 'Dexalo como TeX (pa navegadores de testu)',
'mw_math_modern' => 'Recomendao pa navegadores modernos',
'mw_math_mathml' => 'MathML si ye posible (esperimental)',

# Math errors
'math_failure'          => 'Fallu al revisar la fórmula',
'math_unknown_error'    => 'error desconocíu',
'math_unknown_function' => 'función desconocida',
'math_lexing_error'     => 'Error lléxicu',
'math_syntax_error'     => 'error de sintaxis',
'math_image_error'      => 'Falló la convesión PNG; comprueba que tea bien la instalación de latex, dvips, gs y convert',
'math_bad_tmpdir'       => "Nun se pue escribir o crear el direutoriu temporal 'math'",
'math_bad_output'       => "Nun se pue escribir o crear el direutoriu de salida 'math'",
'math_notexvc'          => "Falta l'executable 'texvc'; por favor mira 'math/README' pa configuralo.",

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como supervisada',
'markaspatrolledtext'                 => 'Marcar esta páxina como supervisada',
'markedaspatrolled'                   => 'Marcar como supervisada',
'markedaspatrolledtext'               => 'La revisión seleicionada marcóse como supervisada.',
'rcpatroldisabled'                    => 'Supervisión de Cambeos Recientes desactivada',
'rcpatroldisabledtext'                => 'La funcionalidá de Supervisión de Cambeos Recientes ta desactivada nestos momentos.',
'markedaspatrollederror'              => 'Nun se pue marcar como supervisada',
'markedaspatrollederrortext'          => 'Necesites conseñar una revisión pa marcala como supervisada.',
'markedaspatrollederror-noautopatrol' => 'Nun pues marcar los tos propios cambeos como supervisaos.',

# Patrol log
'patrol-log-page'      => 'Rexistru de supervisión',
'patrol-log-header'    => 'Esti ye un rexistru de les revisiones supervisaes.',
'patrol-log-line'      => 'marcó la versión $1 de $2 como supervisada $3',
'patrol-log-auto'      => '(automática)',
'patrol-log-diff'      => 'revisión $1',
'log-show-hide-patrol' => '$1 rexistru de supervisión',

# Image deletion
'deletedrevision'                 => 'Esborrada la reversión vieya $1',
'filedeleteerror-short'           => "Error al esborrar l'archivu: $1",
'filedeleteerror-long'            => "Atopáronse errores al esborrar l'archivu:

$1",
'filedelete-missing'              => 'L\'archivu "$1" nun pue esborrase porque nun esiste.',
'filedelete-old-unregistered'     => 'La revisión d\'archivu "$1" nun ta na base de datos.',
'filedelete-current-unregistered' => 'L\'archivu especificáu "$1" nun ta na base de datos.',
'filedelete-archive-read-only'    => 'El direutoriu d\'archivos "$1" nun pue ser modificáu pol sirvidor.',

# Browsing diffs
'previousdiff' => '← Edición más antigua',
'nextdiff'     => 'Diferencia más recién →',

# Media information
'mediawarning'         => "'''Avisu''': Esti archivu pue contener códigu maliciosu, pue ser comprometío executalu nel to sistema.",
'imagemaxsize'         => 'Llendar les imáxenes nes páxines de descripción a:',
'thumbsize'            => 'Tamañu de la muestra:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|páxina|páxines}}',
'file-info'            => "(tamañu d'archivu: $1, triba MIME: $2)",
'file-info-size'       => "($1 × $2 píxeles, tamañu d'archivu: $3, triba MIME: $4)",
'file-nohires'         => '<small>Nun ta disponible con mayor resolución.</small>',
'svg-long-desc'        => "(archivu SVG, $1 × $2 píxeles nominales, tamañu d'archivu: $3)",
'show-big-image'       => 'Resolución completa',
'show-big-image-thumb' => "<small>Tamañu d'esta previsualización: $1 × $2 píxeles</small>",

# Special:NewFiles
'newimages'             => "Galería d'imáxenes nueves",
'imagelisttext'         => "Embaxo ta la llista {{PLURAL:$1|d'un archivu ordenáu|de '''$1''' archivos ordenaos}} $2.",
'newimages-summary'     => 'Esta páxina especial amuesa los caberos archivos xubíos.',
'newimages-legend'      => "Nome d'archivu",
'newimages-label'       => "Nome d'archivu (o una parte d'él):",
'showhidebots'          => '($1 bots)',
'noimages'              => 'Nun hai nada que ver.',
'ilsubmit'              => 'Buscar',
'bydate'                => 'por fecha',
'sp-newimages-showfrom' => "Amosar los archivos nuevos emprimando dende'l $1 a les $2",

# Bad image list
'bad_image_list' => "El formatu ye'l que sigue:

Namái tienen en cuenta los elementos de llista (llinies qu'emprimen por un *). El primer enllaz d'una llinia ha ser ún qu'enllacie a un archivu non válidu. Los demás enllaces de la mesma llinia considérense esceiciones, p.ex. páxines nes que l'archivu ha apaecer.",

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => "Esti archivu contién información adicional, probablemente añadida pola cámara dixital o l'escáner usaos pa crealu o dixitalizalu. Si l'archivu foi modificáu dende'l so estáu orixinal, seique dalgunos detalles nun tean reflexando l'archivu modificáu.",
'metadata-expand'   => 'Amosar detalles estendíos',
'metadata-collapse' => 'Esconder detalles estendíos',
'metadata-fields'   => "Los campos de metadatos EXIF llistaos nesti mensaxe van ser
inxeríos na visualización de la páxina d'imaxe inda cuando la
tabla de metadatos tea recoyida. Los demás tarán escondíos por defeutu.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Anchor',
'exif-imagelength'                 => 'Altor',
'exif-bitspersample'               => 'Bits por componente',
'exif-compression'                 => 'Esquema de compresión',
'exif-photometricinterpretation'   => 'Composición del píxel',
'exif-orientation'                 => 'Orientación',
'exif-samplesperpixel'             => 'Númberu de componentes',
'exif-planarconfiguration'         => 'Distribución de los datos',
'exif-ycbcrsubsampling'            => "Razón de somuestréu d'Y a C",
'exif-ycbcrpositioning'            => 'Allugamientu Y y C',
'exif-xresolution'                 => 'Resolución horizontal',
'exif-yresolution'                 => 'Resolución vertical',
'exif-resolutionunit'              => 'Unidá de resolución X y Y',
'exif-stripoffsets'                => 'Allugamientu de los datos de la imaxe',
'exif-rowsperstrip'                => 'Númberu de files per banda',
'exif-stripbytecounts'             => 'Bytes per banda comprimida',
'exif-jpeginterchangeformat'       => 'Desplazamientu al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes de datos JPEG',
'exif-transferfunction'            => 'Función de tresferencia',
'exif-whitepoint'                  => 'Cromacidá de puntu blancu',
'exif-primarychromaticities'       => 'Cromacidá de los primarios',
'exif-ycbcrcoefficients'           => 'Coeficientes de la matriz de tresformación del espaciu de color',
'exif-referenceblackwhite'         => 'Pareya de valores blancu y negru de referencia',
'exif-datetime'                    => 'Fecha y hora de modificación del archivu',
'exif-imagedescription'            => 'Títulu de la imaxe',
'exif-make'                        => 'Fabricante de la cámara',
'exif-model'                       => 'Modelu de cámara',
'exif-software'                    => 'Software usáu',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titular del Copyright',
'exif-exifversion'                 => 'Versión Exif',
'exif-flashpixversion'             => 'Versión almitida de Flashpix',
'exif-colorspace'                  => 'Espaciu de color',
'exif-componentsconfiguration'     => 'Significáu de cada componente',
'exif-compressedbitsperpixel'      => "Mou de compresión d'imaxe",
'exif-pixelydimension'             => "Anchor d'imaxe válidu",
'exif-pixelxdimension'             => "Altor d'imaxe válidu",
'exif-makernote'                   => 'Notes del fabricante',
'exif-usercomment'                 => 'Comentarios del usuariu',
'exif-relatedsoundfile'            => "Archivu d'audiu rellacionáu",
'exif-datetimeoriginal'            => 'Fecha y hora de la xeneración de datos',
'exif-datetimedigitized'           => 'Fecha y hora de la dixitalización',
'exif-subsectime'                  => 'Fecha y hora (precisión infrasegundu)',
'exif-subsectimeoriginal'          => 'Fecha y hora del orixinal (precisión infrasegundu)',
'exif-subsectimedigitized'         => 'Fecha y hora de la dixitalización (precisión infrasegundu)',
'exif-exposuretime'                => "Tiempu d'esposición",
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'Númberu F',
'exif-exposureprogram'             => "Programa d'esposición",
'exif-spectralsensitivity'         => 'Sensitividá espeutral',
'exif-isospeedratings'             => 'Sensibilidá ISO',
'exif-oecf'                        => 'Factor de conversión optoelectrónicu',
'exif-shutterspeedvalue'           => 'Velocidá del obturador',
'exif-aperturevalue'               => 'Abertura',
'exif-brightnessvalue'             => 'Brillu',
'exif-exposurebiasvalue'           => "Correición d'esposición",
'exif-maxaperturevalue'            => "Valor máximu d'apertura",
'exif-subjectdistance'             => 'Distancia al suxetu',
'exif-meteringmode'                => 'Mou de midición',
'exif-lightsource'                 => 'Fonte de la lluz',
'exif-flash'                       => 'Flax',
'exif-focallength'                 => 'Llonxitú focal de la lente',
'exif-subjectarea'                 => 'Área del suxetu',
'exif-flashenergy'                 => 'Enerxía del flax',
'exif-spatialfrequencyresponse'    => 'Rempuesta de frecuencia espacial',
'exif-focalplanexresolution'       => 'Resolución X del planu focal',
'exif-focalplaneyresolution'       => 'Resolución Y del planu focal',
'exif-focalplaneresolutionunit'    => 'Unidá de resolución del planu focal',
'exif-subjectlocation'             => 'Allugamientu del suxetu',
'exif-exposureindex'               => "Índiz d'esposición",
'exif-sensingmethod'               => 'Métodu de sensor',
'exif-filesource'                  => 'Orixe del archivu',
'exif-scenetype'                   => "Triba d'escena",
'exif-cfapattern'                  => 'patrón CFA',
'exif-customrendered'              => "Procesamientu d'imaxe personalizáu",
'exif-exposuremode'                => "Mou d'esposición",
'exif-whitebalance'                => 'Balance de blancos',
'exif-digitalzoomratio'            => 'Razón de zoom dixital',
'exif-focallengthin35mmfilm'       => 'Llonxitú focal en película de 35 mm',
'exif-scenecapturetype'            => "Triba de captura d'escena",
'exif-gaincontrol'                 => "Control d'escena",
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturación',
'exif-sharpness'                   => 'Nitidez',
'exif-devicesettingdescription'    => 'Descripción de la configuración del dispositivu',
'exif-subjectdistancerange'        => 'Intervalu de distacia al suxetu',
'exif-imageuniqueid'               => "Identificación única d'imaxe",
'exif-gpsversionid'                => 'Versión de la etiqueta GPS',
'exif-gpslatituderef'              => 'Llatitú Norte o Sur',
'exif-gpslatitude'                 => 'Llatitú',
'exif-gpslongituderef'             => 'Llonxitú Este o Oeste',
'exif-gpslongitude'                => 'Llonxitú',
'exif-gpsaltituderef'              => "Referencia d'altitú",
'exif-gpsaltitude'                 => 'Altitú',
'exif-gpstimestamp'                => 'Hora GPS (reló atómicu)',
'exif-gpssatellites'               => 'Satélites usaos pa la midida',
'exif-gpsstatus'                   => 'Estáu del receptor',
'exif-gpsmeasuremode'              => 'Mou de midida',
'exif-gpsdop'                      => 'Precisión de midida',
'exif-gpsspeedref'                 => 'Unidá de velocidá',
'exif-gpsspeed'                    => 'Velocidá del receutor GPS',
'exif-gpstrackref'                 => 'Referencia de la direición de movimientu',
'exif-gpstrack'                    => 'Direición de movimientu',
'exif-gpsimgdirectionref'          => 'Referencia de la direición de la imaxe',
'exif-gpsimgdirection'             => 'Direición de la imaxe',
'exif-gpsmapdatum'                 => 'Usaos datos del estudiu xeodésicu',
'exif-gpsdestlatituderef'          => 'Referencia de la llatitú de destín',
'exif-gpsdestlatitude'             => 'Llatitú de destín',
'exif-gpsdestlongituderef'         => 'Referencia de la llonxitú de destín',
'exif-gpsdestlongitude'            => 'Llonxitú de destín',
'exif-gpsdestbearingref'           => 'Referencia de la orientación de destín',
'exif-gpsdestbearing'              => 'Orientación del destín',
'exif-gpsdestdistanceref'          => 'Referencia de la distancia al destín',
'exif-gpsdestdistance'             => 'Distancia al destín',
'exif-gpsprocessingmethod'         => 'Nome del métodu de procesamientu de GPS',
'exif-gpsareainformation'          => "Nome de l'área GPS",
'exif-gpsdatestamp'                => 'Fecha GPS',
'exif-gpsdifferential'             => 'Correición diferencial de GPS',

# EXIF attributes
'exif-compression-1' => 'Non comprimida',

'exif-unknowndate' => 'Fecha desconocida',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Voltiada horizontalmente',
'exif-orientation-3' => 'Rotada 180°',
'exif-orientation-4' => 'Voltiada verticalmente',
'exif-orientation-5' => 'Rotada 90° a manzorga y voltiada verticalmente',
'exif-orientation-6' => 'Rotada 90° a mandrecha',
'exif-orientation-7' => 'Rotada 90° a mandrecha y voltiada verticalmente',
'exif-orientation-8' => 'Rotada 90° a manzorga',

'exif-planarconfiguration-1' => 'formatu irregular',
'exif-planarconfiguration-2' => 'formatu planu',

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

'exif-meteringmode-0'   => 'Desconocíu',
'exif-meteringmode-1'   => 'Promediáu',
'exif-meteringmode-2'   => 'Media ponderada centrada',
'exif-meteringmode-3'   => 'Puntual',
'exif-meteringmode-4'   => 'Multipuntu',
'exif-meteringmode-5'   => 'Patrón',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Otru',

'exif-lightsource-0'   => 'Desconocida',
'exif-lightsource-1'   => 'Lluz diurna',
'exif-lightsource-2'   => 'Fluorescente',
'exif-lightsource-3'   => 'Tungstenu (lluz incandescente)',
'exif-lightsource-4'   => 'Flax',
'exif-lightsource-9'   => 'Tiempu despexáu',
'exif-lightsource-10'  => 'Tiempu ñubláu',
'exif-lightsource-11'  => 'Solombra',
'exif-lightsource-12'  => 'Fluorescente lluz de día (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescente blancu día (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescente blancu fríu (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescente blancu (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lluz estándar A',
'exif-lightsource-18'  => 'Lluz estándar B',
'exif-lightsource-19'  => 'Lluz estándar C',
'exif-lightsource-24'  => "Tungstenu ISO d'estudio",
'exif-lightsource-255' => 'Otra fonte de lluz',

# Flash modes
'exif-flash-fired-0'    => 'Flax non disparáu',
'exif-flash-fired-1'    => 'Flax disparáu',
'exif-flash-return-0'   => 'ensin función de deteición de retornu estroboscópicu',
'exif-flash-return-2'   => 'lluz de retornu estroboscópicu non detectada',
'exif-flash-return-3'   => 'lluz de retornu estroboscópicu detectada',
'exif-flash-mode-1'     => 'disparu de flax forciáu',
'exif-flash-mode-2'     => 'supresión de flax forciáu',
'exif-flash-mode-3'     => 'mou automáticu',
'exif-flash-function-1' => 'Ensin función de flax',
'exif-flash-redeye-1'   => "mou d'amenorgamientu de güeyos encarnaos",

'exif-focalplaneresolutionunit-2' => 'pulgaes',

'exif-sensingmethod-1' => 'Non definíu',
'exif-sensingmethod-2' => "Sensor d'área de color d'un chip",
'exif-sensingmethod-3' => "Sensor d'área de color de dos chips",
'exif-sensingmethod-4' => "Sensor d'área de color de tres chips",
'exif-sensingmethod-5' => "Sensor d'área secuencial de color",
'exif-sensingmethod-7' => 'Sensor Trillinial',
'exif-sensingmethod-8' => 'Sensor llinial secuencial de color',

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

'exif-gpsstatus-a' => 'Midición en progresu',
'exif-gpsstatus-v' => 'Interoperabilidá de la midición',

'exif-gpsmeasuremode-2' => 'Midición bidimensional',
'exif-gpsmeasuremode-3' => 'Midición tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Quilómetros per hora',
'exif-gpsspeed-m' => 'Milles per hora',
'exif-gpsspeed-n' => 'Nueyos',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direición real',
'exif-gpsdirection-m' => 'Direición magnética',

# External editor support
'edit-externally'      => 'Editar esti ficheru usando una aplicación esterna',
'edit-externally-help' => '(Pa más información echa un güeyu a les [http://www.mediawiki.org/wiki/Manual:External_editors instrucciones de configuración])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'toos',
'imagelistall'     => 'toes',
'watchlistall2'    => 'too',
'namespacesall'    => 'toos',
'monthsall'        => 'toos',

# E-mail address confirmation
'confirmemail'             => 'Confirmar direición de corréu',
'confirmemail_noemail'     => "Nun tienes una direición de corréu válida nes tos [[Special:Preferences|preferencies d'usuariu]].",
'confirmemail_text'        => "{{SITENAME}} requier que valides la to direición de corréu enantes d'usar les
funcionalidaes de mensaxes. Da-y al botón que tienes equí embaxo pa unviar un avisu de
confirmación a la to direición. Esti avisu va incluyir un enllaz con un códigu; carga
l'enllaz nel to navegador pa confirmar la to direición de corréu electrónicu.",
'confirmemail_pending'     => "Yá s'unvió un códigu de confirmación a la to direición de corréu; si creasti hai poco la to cuenta, pues esperar dellos minutos a que-y de tiempu a llegar enantes de pidir otru códigu nuevu.",
'confirmemail_send'        => 'Unviar códigu de confirmación',
'confirmemail_sent'        => 'Corréu de confirmación unviáu.',
'confirmemail_oncreate'    => "Unvióse un códigu de confirmación a la to direición de corréu.
Esti códigu nun se necesita pa identificase, pero tendrás que lu conseñar enantes
d'activar cualesquier funcionalidá de la wiki que tea rellacionada col corréu.",
'confirmemail_sendfailed'  => '{{SITENAME}} nun pudo unviar el to corréu de confirmación.
Por favor comprueba que nun punxeras carauteres non válidos na to direición de corréu.

El sirvidor de corréu devolvió: $1',
'confirmemail_invalid'     => 'Códigu de confirmación non válidu. El códigu seique tenga caducao.',
'confirmemail_needlogin'   => 'Tienes que $1 pa confirmar el to corréu.',
'confirmemail_success'     => 'El to corréu quedó confimáu. Agora yá pues identificate y collaborar na wiki.',
'confirmemail_loggedin'    => 'Quedó confirmada la to direición de corréu.',
'confirmemail_error'       => 'Hebo un problema al guardar la to confirmación.',
'confirmemail_subject'     => 'Confirmación de la direición de corréu de {{SITENAME}}',
'confirmemail_body'        => 'Daquién, seique tu dende la IP $1, rexistró la cuenta "$2" con
esta direición de corréu en {{SITENAME}}.

Pa confirmar qu\'esta cuenta ye tuya daveres y asina activar les funcionalidaes
de corréu en {{SITENAME}}, abri esti enllaz nel to navegador:

$3

Si *nun* rexistrasti tu la cuenta, da-y a esti enllaz pa cancelar
la confirmación de la direición de corréu electrónicu:

$5

Esti códigu de confirmación caduca\'l $4.',
'confirmemail_invalidated' => 'Confirmación de direición de corréu electrónicu cancelada',
'invalidateemail'          => 'Cancelar confirmación de corréu electrónicu',

# Scary transclusion
'scarytranscludedisabled' => '[La tresclusión interwiki ta desactivada]',
'scarytranscludefailed'   => '[La obtención de la plantía falló pa $1]',
'scarytranscludetoolong'  => '[La URL ye demasiao llarga]',

# Trackbacks
'trackbackbox'      => 'Retroenllaces pa esta páxina:<br />
$1',
'trackbackremove'   => '([$1 Esborrar])',
'trackbacklink'     => 'Retroenllaz',
'trackbackdeleteok' => 'El retroenllaz esborróse correutamente.',

# Delete conflict
'deletedwhileediting' => "'''Avisu''': ¡Esta páxina foi esborrada depués de qu'entamaras a editala!",
'confirmrecreate'     => "L'usuariu [[User:$1|$1]] ([[User talk:$1|alderique]]) esborró esta páxina depués de qu'empecipiaras a editala pol siguiente motivu:
: ''$2''
Por favor confirma que daveres quies volver a crear esta páxina.",
'recreate'            => 'Volver a crear',

# action=purge
'confirm_purge_button' => 'Aceutar',
'confirm-purge-top'    => "¿Llimpiar la caché d'esta páxina?",
'confirm-purge-bottom' => 'Purgar una páxina esborra la caché y fuercia a apaecer la versión actual más recién.',

# Multipage image navigation
'imgmultipageprev' => '← páxina anterior',
'imgmultipagenext' => 'páxina siguiente →',
'imgmultigo'       => '¡Dir!',
'imgmultigoto'     => 'Dir a la páxina $1',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Páxina siguiente',
'table_pager_prev'         => 'Páxina anterior',
'table_pager_first'        => 'Primer páxina',
'table_pager_last'         => 'Postrer páxina',
'table_pager_limit'        => 'Amosar $1 elementos per páxina',
'table_pager_limit_submit' => 'Dir',
'table_pager_empty'        => 'Nun hai resultaos',

# Auto-summaries
'autosumm-blank'   => 'Páxina dexada en blanco',
'autosumm-replace' => "Sustituyendo la páxina por '$1'",
'autoredircomment' => 'Redirixendo a [[$1]]',
'autosumm-new'     => "Páxina creada con '$1'",

# Live preview
'livepreview-loading' => 'Cargando…',
'livepreview-ready'   => 'Cargando… ¡Llisto!',
'livepreview-failed'  => '¡La previsualización rápida falló! Intenta la previsualización normal.',
'livepreview-error'   => 'Nun se pudo coneutar: $1 "$2". Intenta la previsualización normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Los cambeos más recién de $1 {{PLURAL:$|segundu|segundos}} pue que nun s'amuesen nesta llista.",
'lag-warn-high'   => "Pol mor d'un importante retrasu del sirvidor de la base de datos, los cambeos más recién de $1 {{PLURAL:$1|segundu|segundos}} pue que nun s'amuesen nesta llista.",

# Watchlist editor
'watchlistedit-numitems'       => "La to llista de vixilancia tien {{PLURAL:$1|1 títulu|$1 títulos}}, escluyendo les páxines d'alderique.",
'watchlistedit-noitems'        => 'La to llista de vixilancia nun tien títulos.',
'watchlistedit-normal-title'   => 'Editar la llista de vixilancia',
'watchlistedit-normal-legend'  => 'Eliminar títulos de la llista de vixilancia',
'watchlistedit-normal-explain' => "Abaxo amuésense los títulos de la to llista de vixilancia. Pa eliminar un títulu,
activa la caxa d'al llau d'él, y calca n'Eliminar Títulos. Tamién pues [[Special:Watchlist/raw|editar la llista en bruto]].",
'watchlistedit-normal-submit'  => 'Eliminar títulos',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Eliminóse un títulu|Elimináronse $1 títulos}} de la to llista de vixilancia:',
'watchlistedit-raw-title'      => 'Editar la llista de vixilancia en bruto',
'watchlistedit-raw-legend'     => 'Editar la llista de vixilancia en bruto',
'watchlistedit-raw-explain'    => "Abaxo amuésense los títulos de la to llista de vixilancia, y puen ser
editaos añadiéndolos o eliminandolos de la llista; un títulu per llinia. N'acabando, calca n'Actualizar Llista de Vixilancia.
Tamién pues [[Special:Watchlist/edit|usar l'editor estándar]].",
'watchlistedit-raw-titles'     => 'Títulos:',
'watchlistedit-raw-submit'     => 'Actualizar llista de vixilancia',
'watchlistedit-raw-done'       => 'La to llista de vixilancia foi actualizada.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Añadióse un títulu|Añadiéronse $1 títulos}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Eliminóse ún títulu|Elimináronse $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver cambeos relevantes',
'watchlisttools-edit' => 'Ver y editar la llista de vixilancia',
'watchlisttools-raw'  => 'Editar la llista de vixilancia (en bruto)',

# Core parser functions
'unknown_extension_tag' => 'Etiqueta d\'estensión "$1" desconocida',
'duplicate-defaultsort' => 'Avisu: L\'atayu "$2" va trocar l\'atayu "$1".',

# Special:Version
'version'                          => 'Versión',
'version-extensions'               => 'Estensiones instalaes',
'version-specialpages'             => 'Páxines especiales',
'version-parserhooks'              => "Hooks d'análisis sintáuticu",
'version-variables'                => 'Variables',
'version-other'                    => 'Otros',
'version-mediahandlers'            => "Remanadores d'archivos multimedia",
'version-hooks'                    => 'Hooks',
'version-extension-functions'      => "Funciones d'estensiones",
'version-parser-extensiontags'     => "Etiquetes d'estensiones d'análisis",
'version-parser-function-hooks'    => "Hooks de les funciones d'análisis sintáuticu",
'version-skin-extension-functions' => "Funciones d'estensiones de pieles",
'version-hook-name'                => 'Nome del hook',
'version-hook-subscribedby'        => 'Suscritu por',
'version-version'                  => '(Versión $1)',
'version-license'                  => 'Llicencia',
'version-software'                 => 'Software instaláu',
'version-software-product'         => 'Productu',
'version-software-version'         => 'Versión',

# Special:FilePath
'filepath'         => "Ruta d'archivu",
'filepath-page'    => 'Archivu:',
'filepath-submit'  => 'Ruta',
'filepath-summary' => "Esta páxina especial devuelve la ruta completa d'un archivu. Les imáxenes amuésense a resolución completa; les demás tribes d'archivu execútense direutamente col so programa asociáu.

Escribi'l nome d'archivu ensin el prefixu \"{{ns:file}}:\".",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Buscar archivos duplicaos',
'fileduplicatesearch-summary'  => 'Busca archivos duplicaos basándose nos sos valores fragmentarios.

Escribi\'l nome del archivu ensin el prefixu "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Buscar duplicaos',
'fileduplicatesearch-filename' => "Nome d'archivu:",
'fileduplicatesearch-submit'   => 'Buscar',
'fileduplicatesearch-info'     => '$1 × $2 píxeles<br />Tamañu del archivu: $3<br />Triba MIME: $4',
'fileduplicatesearch-result-1' => 'L\'archivu "$1" nun tien duplicáu idénticu.',
'fileduplicatesearch-result-n' => 'L\'archivu "$1" tien {{PLURAL:$2|un duplicáu idénticu|$2 duplicaos idénticos}}.',

# Special:SpecialPages
'specialpages'                   => 'Páxines especiales',
'specialpages-note'              => '----
* Páxines especiales normales.
* <strong class="mw-specialpagerestricted">Páxines especiales restrinxíes.</strong>',
'specialpages-group-maintenance' => 'Informes de mantenimientu',
'specialpages-group-other'       => 'Otres páxines especiales',
'specialpages-group-login'       => 'Entrar / Crear cuenta',
'specialpages-group-changes'     => 'Cambeos recientes y rexistros',
'specialpages-group-media'       => 'Informes multimedia y xubíes',
'specialpages-group-users'       => 'Usuarios y drechos',
'specialpages-group-highuse'     => 'Páxines mui usaes',
'specialpages-group-pages'       => 'Llista de páxines',
'specialpages-group-pagetools'   => 'Ferramientes de páxina',
'specialpages-group-wiki'        => 'Datos wiki y ferramientes',
'specialpages-group-redirects'   => 'Páxines especiales de redireición',
'specialpages-group-spam'        => 'Ferramientes pa spam',

# Special:BlankPage
'blankpage'              => 'Páxina en blanco',
'intentionallyblankpage' => 'Esta páxina ta en blanco arrémente',

# External image whitelist
'external_image_whitelist' => " #Dexa esta llinia exautamente como ta<pre>
#Pon los fragmentos d'espresiones regulares (namái la parte que va ente les //) debaxo
#Esto va ser comprobao coles URLs d'imáxenes esternes (hotlinked)
#Les que concuayen van ser visualizaes como imáxenes; si nun concuayen, namái se va amosar un enllaz a la imaxe
#Les llinies qu'emprimen con # son trataes como comentarios

#Pon tolos fragmentos regex enantes d'esta llinia. Dexa esta llinia exautamente como ta</pre>",

# Special:Tags
'tag-filter'           => "Filtru d'[[Special:Tags|etiquetes]]:",
'tag-filter-submit'    => 'Filtrar',
'tags-title'           => 'Etiquetes',
'tags-hitcount-header' => 'Cambeos etiquetaos',
'tags-edit'            => 'editar',
'tags-hitcount'        => '$1 {{PLURAL:$1|cambéu|cambeos}}',

# Database error messages
'dberr-header'   => 'Esta wiki tien un problema',
'dberr-problems' => '¡Sentímoslo! Esti sitiu ta esperimentando dificultaes téuniques.',
'dberr-info'     => '(Nun se pue contautar cola base de datos del sirvidor: $1)',

);
