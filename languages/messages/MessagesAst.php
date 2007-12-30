<?php
/** Asturian (Asturianu)
 *
 * @addtogroup Language
 *
 * @author Esbardu
 * @author Helix84
 * @author Mikel
 * @author SPQRobin
 * @author לערי ריינהארט
 * @author Siebrand
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_MAIN             => '',
	NS_TALK             => 'Discusión',
	NS_USER             => 'Usuariu',
	NS_USER_TALK        => 'Usuariu_discusión',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_discusión',
	NS_IMAGE            => 'Imaxen',
	NS_IMAGE_TALK       => 'Imaxen_discusión',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_discusión',
	NS_TEMPLATE         => 'Plantilla',
	NS_TEMPLATE_TALK    => 'Plantilla_discusión',
	NS_HELP             => 'Ayuda',
	NS_HELP_TALK        => 'Ayuda_discusión',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Categoría_discusión',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sorrayar enllaces:',
'tog-highlightbroken'         => 'Da-y formatu a los enllaces rotos <a href="" class="new">como esti</a> (caxella desactivada: como esti<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Xustificar parágrafos',
'tog-hideminor'               => 'Esconder ediciones menores nos cambeos recientes',
'tog-extendwatchlist'         => "Espander la llista de vixilancia p'amosar tolos cambeos aplicables",
'tog-usenewrc'                => 'Cambeos recientes ameyoraos (JavaScript)',
'tog-numberheadings'          => 'Autonumberar los encabezaos',
'tog-showtoolbar'             => "Amosar la barra de ferramientes d'edición (JavaScript)",
'tog-editondblclick'          => 'Editar páxines con doble clic (JavaScript)',
'tog-editsection'             => "Activar la edición de seiciones per aciu d'enllaces [editar]",
'tog-editsectiononrightclick' => 'Activar la edición de seiciones calcando col botón<br /> drechu enriba los títulos de seición (JavaScript)',
'tog-showtoc'                 => 'Amosar índiz (pa páxines con más de 3 encabezaos)',
'tog-rememberpassword'        => 'Recordar la clave ente sesiones',
'tog-editwidth'               => "La caxa d'edición tien el tamañu máximu",
'tog-watchcreations'          => 'Añader les páxines que creo a la mio llista de vixilancia',
'tog-watchdefault'            => "Añader les páxines qu'edito a la mio llista de vixilancia",
'tog-watchmoves'              => 'Añader les páxines que muevo a la mio llista de vixilancia',
'tog-watchdeletion'           => 'Añader les páxines que borro a la mio llista de vixilancia',
'tog-minordefault'            => 'Marcar toles ediciones como menores por defeutu',
'tog-previewontop'            => "Amosar previsualización enantes de la caxa d'edición",
'tog-previewonfirst'          => 'Amosar previsualización na primer edición',
'tog-nocache'                 => 'Desactivar la caché de les páxines',
'tog-enotifwatchlistpages'    => 'Mandame un corréu cuando cambie una páxina que toi vixilando',
'tog-enotifusertalkpages'     => 'Mandame un corréu cuando cambie la mio páxina de discusión',
'tog-enotifminoredits'        => 'Mandame tamién un corréu pa les ediciones menores',
'tog-enotifrevealaddr'        => 'Amosar el mio corréu electrónicu nos correos de notificación',
'tog-shownumberswatching'     => "Amosar el númberu d'usuarios que la tán vixilando",
'tog-fancysig'                => 'Firma ensin enllaz automáticu',
'tog-externaleditor'          => 'Usar un editor esternu por defeutu',
'tog-externaldiff'            => "Usar ''diff'' esternu por defeutu",
'tog-showjumplinks'           => 'Activar los enllaces d\'accesibilidá "saltar a"',
'tog-uselivepreview'          => 'Usar vista previa en direutu (JavaScript) (en pruebes)',
'tog-forceeditsummary'        => "Avisame cuando grabe col resume d'edición en blanco",
'tog-watchlisthideown'        => 'Esconder les mios ediciones na llista de vixilancia',
'tog-watchlisthidebots'       => 'Esconder les ediciones de bots na llista de vixilancia',
'tog-watchlisthideminor'      => 'Esconder les ediciones menores na llista de vixilancia',
'tog-nolangconversion'        => 'Deshabilitar la conversión de variantes de llingua',
'tog-ccmeonemails'            => 'Mandame copies de los correos que mando a otros usuarios',
'tog-diffonly'                => 'Nun amosar el conteníu de la páxina embaxo de les diferencies',

'underline-always'  => 'Siempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Valor por defeutu del navegador',

'skinpreview' => '(Previsualizar)',

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

# Bits of text used by many pages
'categories'            => 'Categoríes',
'pagecategories'        => '{{PLURAL:$1|Categoría|Categoríes}}',
'category_header'       => 'Páxines na categoría "$1"',
'subcategories'         => 'Subcategoríes',
'category-media-header' => 'Archivos multimedia na categoría "$1"',
'category-empty'        => "''Esta categoría nun tien anguaño nengún artículu o ficheru multimedia.''",

'mainpagetext'      => "<big>'''MediaWiki instalóse correchamente.'''</big>",
'mainpagedocfooter' => "Visita la [http://meta.wikimedia.org/wiki/Help:Contents Guía d'usuariu] pa saber cómo usar esti software wiki.

== Empecipiando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Llista de les opciones de configuración]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ de MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Llista de corréu de les ediciones de MediaWiki]",

'about'          => 'Tocante a',
'article'        => 'Conteníu de la páxina',
'newwindow'      => '(abriráse nuna ventana nueva)',
'cancel'         => 'Cancelar',
'qbfind'         => 'Alcontrar',
'qbbrowse'       => 'Escartafoyar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Esta páxina',
'qbpageinfo'     => 'Contestu',
'qbmyoptions'    => 'Les mios páxines',
'qbspecialpages' => 'Páxines especiales',
'moredotdotdot'  => 'Más...',
'mypage'         => 'La mio páxina',
'mytalk'         => 'La mio páxina de discusión',
'anontalk'       => 'Discusión pa esta IP',
'navigation'     => 'Navegación',

# Metadata in edit box
'metadata_help' => 'Metadatos:',

'errorpagetitle'    => 'Error',
'returnto'          => 'Vuelve a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ayuda',
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
'editthispage'      => 'Editar esta páxina',
'delete'            => 'Borrar',
'deletethispage'    => 'Borrar esta páxina',
'undelete_short'    => 'Restaurar {{PLURAL:$1|una edición|$1 ediciones}}',
'protect'           => 'Protexer',
'protect_change'    => 'camudar proteición',
'protectthispage'   => 'Protexer esta páxina',
'unprotect'         => 'Desprotexer',
'unprotectthispage' => 'Desprotexer esta páxina',
'newpage'           => 'Páxina nueva',
'talkpage'          => 'Discutir esta páxina',
'talkpagelinktext'  => 'discusión',
'specialpage'       => 'Páxina especial',
'personaltools'     => 'Ferramientes personales',
'postcomment'       => 'Escribir un comentariu',
'articlepage'       => 'Ver conteníu de la páxina',
'talk'              => 'Discusión',
'views'             => 'Vistes',
'toolbox'           => 'Ferramientes',
'userpage'          => "Ver páxina d'usuariu",
'projectpage'       => 'Ver la páxina de proyeutu',
'imagepage'         => "Ver la páxina d'imaxe",
'mediawikipage'     => 'Ver la páxina de mensaxe',
'templatepage'      => 'Ver la páxina de plantía',
'viewhelppage'      => "Ver la páxina d'aida",
'categorypage'      => 'Ver páxina de categoríes',
'viewtalkpage'      => 'Ver discusión',
'otherlanguages'    => 'Otres llingües',
'redirectedfrom'    => '(Redirixío dende $1)',
'redirectpagesub'   => 'Páxina de redirección',
'lastmodifiedat'    => "Esta páxina foi modificada per postrer vegada'l $1 a les $2.", # $1 date, $2 time
'viewcount'         => 'Esta páxina foi vista {{PLURAL:$1|una vegada|$1 vegaes}}.',
'protectedpage'     => 'Páxina protexida',
'jumpto'            => 'Saltar a:',
'jumptonavigation'  => 'navegación',
'jumptosearch'      => 'busca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Tocante a {{SITENAME}}',
'aboutpage'         => 'Project:Tocante a',
'bugreports'        => "Informes d'errores",
'bugreportspage'    => "Project:Informes d'errores",
'copyright'         => 'Esti conteníu ta disponible baxo los términos de la  $1.',
'copyrightpagename' => "Drechos d'autor de {{SITENAME}}",
'copyrightpage'     => "{{ns:project}}:Derechos d'autor",
'currentevents'     => 'Fechos actuales',
'currentevents-url' => 'Project:Fechos actuales',
'disclaimers'       => 'Avisu llegal',
'disclaimerpage'    => 'Project:Llimitación xeneral de responsabilidá',
'edithelp'          => "Aida d'edición",
'edithelppage'      => 'Help:Edición de páxines',
'faq'               => 'FAQ',
'faqpage'           => 'Project:Entrugues más frecuentes',
'helppage'          => 'Help:Conteníos',
'mainpage'          => 'Portada',
'policy-url'        => 'Project:Polítiques',
'portal'            => 'Portal de la comunidá',
'portal-url'        => 'Project:Portal de la comunidá',
'privacy'           => 'Politica de privacidá',
'privacypage'       => 'Project:Política de privacidá',
'sitesupport'       => 'Donativos',
'sitesupport-url'   => 'Project:Donativos',

'badaccess'        => 'Error de permisos',
'badaccess-group0' => "Nun tienes permisu pa executar l'aición solicitada.",
'badaccess-group1' => "L'aición solicitada ta llimitada a usuarios del grupu $1.",
'badaccess-group2' => "L'aición solicitada ta llimitada a usuarios d'ún de los grupos $1.",
'badaccess-groups' => "L'aición solicitada ta llimitada a usuarios d'ún de los grupos $1.",

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
'editsectionhint'         => 'Editar seición: $1',
'toc'                     => 'Tabla de conteníos',
'showtoc'                 => 'amosar',
'hidetoc'                 => 'esconder',
'thisisdeleted'           => '¿Ver o restaurar $1?',
'viewdeleted'             => '¿Ver $1?',
'restorelink'             => '{{PLURAL:$1|una edición borrada|$1 ediciones borraes}}',
'feedlinks'               => 'Canal:',
'feed-invalid'            => 'Suscripción non válida a la triba de canal.',
'site-rss-feed'           => 'Canal RSS $1',
'site-atom-feed'          => 'Canal Atom $1',
'page-rss-feed'           => 'Canal RSS "$1"',
'page-atom-feed'          => 'Canal Atom "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Páxina',
'nstab-user'      => "Páxina d'usuariu",
'nstab-media'     => "Páxina d'archivu multimedia",
'nstab-special'   => 'Especial',
'nstab-project'   => 'Páxina de proyeutu',
'nstab-image'     => 'Imaxe',
'nstab-mediawiki' => 'Mensaxe',
'nstab-template'  => 'Plantía',
'nstab-help'      => 'Aida',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'Nun esiste esa aición',
'nosuchactiontext'  => "L'aición especificada pola URL nun ye
reconocida pola wiki",
'nosuchspecialpage' => 'Nun esiste esa páxina especial',
'nospecialpagetext' => "<big>'''Pidisti una páxina especial non válida.'''</big>

Pues consultar la llista de les páxines especiales válides en [[Special:Specialpages]].",

# General errors
'error'                => 'Error',
'databaseerror'        => 'Error na base de datos',
'dberrortext'          => 'Hebo un error de sintaxis nuna consulta de la base de datos.
Esti error pue debese a un fallu del software.
La postrer consulta que s\'intentó foi:
<blockquote><tt>$1</tt></blockquote>
dende la función "<tt>$2</tt>".
MySQL retornó l\'error "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Hebo un error de sintaxis nuna consulta a la base de datos.
La postrer consulta que s\'intentó foi:
"$1"
dende la función "$2".
MySQL retornó l\'error "$3: $4"',
'noconnect'            => '¡Sentímoslo muncho! La wiki ta sufriendo delles dificultaes téuniques y nun pue contautar col servidor de la base de datos. <br />
$1',
'nodb'                 => 'Nun se pudo seleicionar la base de datos $1',
'cachederror'          => 'Esta ye una copia sacada del caché de la páxina solicitada y pue nun tar actualizada.',
'laggedslavemode'      => 'Avisu: Esta páxina pue que nun tenga actualizaciones recientes.',
'readonly'             => 'Base de datos protexida',
'enterlockreason'      => 'Introduz una razón pa la proteición, amiestando una estimación de cuándo va ser llevantada esta',
'readonlytext'         => "Nestos momentos la base de datos ta protexida pa nueves entraes y otres modificaciones, seique por un mantenimientu de rutina, depués d'él tará accesible de nuevo.

L'alministrador que la protexó conseñó esti motivu: $1",
'missingarticle'       => "La base de datos nun atopó'l testu d'una páxina qu'habría ser alcontráu, nomada \"\$1\".

Esto débese normalmente a que se calcó nuna diferencia de versiones caducada o
nun enllaz del historial a una paxína que foi borrada.

Si esti nun ye'l casu, seique atoparas un error nel software. Por favor informa a un
alministrador, indicando l'URL.",
'readonly_lag'         => 'La base de datos foi protexida automáticamente mentes los servidores de la base de datos esclava se sincronicen cola maestra',
'internalerror'        => 'Error internu',
'internalerror_info'   => 'Error internu: $1',
'filecopyerror'        => 'Nun se pudo copiar l\'archivu "$1" como "$2".',
'filerenameerror'      => 'Nun se pudo renomar l\'archivu "$1" como "$2".',
'filedeleteerror'      => 'Nun se pudo borrar l\'archivu "$1".',
'directorycreateerror' => 'Nun se pudo crear el direutoriu "$1".',
'filenotfound'         => 'Nun se pudo atopar l\'archivu "$1".',
'fileexistserror'      => 'Nun se pue escribir nel archivu "$1": yá esiste',
'unexpected'           => 'Valor inesperáu: "$1"="$2".',
'formerror'            => 'Error: nun se pudo unviar el formulariu',
'badarticleerror'      => 'Esta aición nun pue facese nesta páxina',
'cannotdelete'         => 'Nun se pudo borrar la páxina o imaxe seleicionada (seique daquién yá la borrara).',
'badtitle'             => 'Títulu incorreutu',
'badtitletext'         => 'El títulu de páxina solicitáu nun ye válidu, ta vaciu o tien enllaces inter-llingua o inter-wiki incorreutos. Pue que contenga ún o más carauteres que nun puen ser usaos nos títulos.',
'perfdisabled'         => '¡Sentímoslo muncho! Esta funcionalidá foi deshabilitada temporalmente porque allancia tanto la base de datos que naide pue usar la wiki.',
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
'protectedpagetext'    => 'Esta páxina foi protexida pa evitar la so edición.',
'viewsourcetext'       => "Pues ver y copiar el códigu fonte d'esta páxina:",
'protectedinterface'   => "Esta páxina proporciona testu d'interfaz a l'aplicación y ta protexida pa evitar el so abusu.",
'editinginterface'     => "'''Avisu:''' Tas editando una páxina usada pa proporcionar testu d'interfaz a l'aplicación. Los cambeos nesta páxina va afeuta-yos l'apariencia de la interfaz a otros usuarios.",
'sqlhidden'            => '(consulta SQL escondida)',
'cascadeprotected'     => 'Esta páxina ta protexida d\'ediciones porque ta enxerta {{PLURAL:$1|na siguiente páxina|nes siguientes páxines}}, que {{PLURAL:$1|ta protexida|tán protexíes}} cola opción "en cascada":
$2',
'namespaceprotected'   => "Nun tienes permisu pa editar páxines nel espaciu de nomes '''$1'''.",
'customcssjsprotected' => "Nun tienes permisu pa editar esta páxina porque contién preferencies personales d'otru usuariu.",
'ns-specialprotected'  => 'Les páxines del espaciu de nomes {{ns:special}} nun puen ser editaes.',
'titleprotected'       => 'Esti títulu foi protexíu de la so creación por [[User:$1|$1]]. La razón conseñada ye <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'Desconexón',
'logouttext'                 => "<strong>Yá tas desconectáu.</strong><br />
Pues siguir usando {{SITENAME}} de forma anónima, o pues volver a entrar como'l mesmu o como otru usuariu.
Ten en cuenta que dalgunes páxines van continuar saliendo como si tovía tuvieres coneutáu, hasta que llimpies la caché del navegador.",
'welcomecreation'            => "== Bienveníu, $1! ==

La to cuenta ta creada. Nun t'escaezas d'escoyer les tos preferencies de {{SITENAME}}.",
'loginpagetitle'             => "Identificación d'usuariu",
'yourname'                   => "Nome d'usuariu:",
'yourpassword'               => 'Clave:',
'yourpasswordagain'          => 'Reescribi la to clave:',
'remembermypassword'         => 'Recordar la mio identificación nesti ordenador',
'yourdomainname'             => 'El to dominiu:',
'externaldberror'            => "O hebo un error de l'autenticación esterna de la base de datos o nun tienes permisu p'actualizar la to cuenta esterna.",
'loginproblem'               => '<b>Hebo un problema cola to identificación.</b><br />¡Inténtalo otra vuelta!',
'login'                      => 'Entrar',
'loginprompt'                => "Has tener les ''cookies'' activaes pa entrar en {{SITENAME}}.",
'userlogin'                  => 'Entrar / Crear cuenta',
'logout'                     => 'Salir',
'userlogout'                 => 'Salir',
'notloggedin'                => 'Non identificáu',
'nologin'                    => '¿Nun tienes una cuenta? $1.',
'nologinlink'                => '¡Fai una!',
'createaccount'              => 'Crear una nueva cuenta',
'gotaccount'                 => '¿Ya tienes una cuenta? $1.',
'gotaccountlink'             => '¡Identifícate!',
'createaccountmail'          => 'per e-mail',
'badretype'                  => "Les claves qu'escribisti nun concuayen.",
'userexists'                 => "El nome d'usuariu conseñáu yá esiste. Por favor escueyi un nome diferente.",
'youremail'                  => 'Corréu electrónicu:',
'username'                   => "Nome d'usuariu:",
'uid'                        => "Númberu d'usuariu:",
'yourrealname'               => 'Nome real:',
'yourlanguage'               => 'Idioma de los menús:',
'yourvariant'                => 'Variante llingüística:',
'yournick'                   => 'Nomatu:',
'badsig'                     => 'Firma cruda non válida; comprueba les etiquetes HTML.',
'badsiglength'               => 'Nomatu demasiao llargu; ha tener menos de $1 carauteres.',
'email'                      => 'Corréu',
'prefs-help-realname'        => "El nome real ye opcional y si decides conseñalu va ser usáu p'atribuyite'l to trabayu.",
'loginerror'                 => "Error d'identificación",
'prefs-help-email'           => "La direición de corréu ye opcional, pero permite a los demás contautar contigo al traviés de la to páxina d'usuariu ensin necesidá de revelar la to identidá.",
'prefs-help-email-required'  => 'Necesítase una direición de corréu electrónicu.',
'nocookiesnew'               => "La cuenta d'usuariu ta creada, pero nun tas identificáu. {{SITENAME}} usa cookies pa identificar a los usuarios. Tienes les cookies deshabilitaes. Por favor actívales y depués identifícate col to nuevu nome d'usuariu y la clave.",
'nocookieslogin'             => '{{SITENAME}} usa cookies pa identificar a los usuarios. Tienes les cookies deshabilitaes. Por favor actívales y inténtalo otra vuelta.',
'noname'                     => "Nun punxisti un nome d'usuariu válidu.",
'loginsuccesstitle'          => 'Identificación correuta',
'loginsuccess'               => "'''Quedasti identificáu en {{SITENAME}} como \"\$1\".'''",
'nosuchuser'                 => 'Nun hai nengún usuariu col nome "$1". Corrixi la escritura o crea una nueva cuenta d\'usuariu.',
'nosuchusershort'            => 'Nun hai nengún usuariu col nome "$1". Mira que tea bien escritu.',
'nouserspecified'            => "Has especificar un nome d'usuariu.",
'wrongpassword'              => 'Clave errónea.  Inténtalo otra vuelta.',
'wrongpasswordempty'         => 'La clave taba en blanco. Inténtalo otra vuelta.',
'passwordtooshort'           => "La to clave nun ye válida o ye demasiao curtia. Ha tener a lo menos $1 carauteres y ser distinta del to nome d'usuariu.",
'mailmypassword'             => 'Unviame per corréu la clave',
'passwordremindertitle'      => 'Nueva clave provisional pa {{SITENAME}}',
'passwordremindertext'       => 'Daquién (seique tu, dende la direición IP $1)
solicitó que se t\'unviara una clave nueva pa {{SITENAME}} ($4).
La clave pal usuariu "$2" ye agora "$3".
Habríes identificate y camudar la to clave agora.

Si daquién más fexo esta solicitú o si recuerdes la to clave y
nun quies volver a camudala, pues inorar esti mensaxe y siguir
usando la to clave vieya.',
'noemail'                    => 'L\'usuariu "$1" nun tien puesta dirección de corréu.',
'passwordsent'               => 'Unvióse una clave nueva a la direición de corréu
rexistrada pa "$1".
Por favor identifícate de nuevo depués de recibila.',
'blocked-mailpassword'       => 'La edición ta bloquiada dende la to direición IP, y por tanto
nun se pue usar la función de recuperación de clave pa evitar abusos.',
'eauthentsent'               => "Unvióse una confirmación de corréu electrónicu a la direición indicada.
Enantes de que s'unvie nengún otru corréu a la cuenta, has siguir les instrucciones del corréu electrónicu, pa confirmar que la cuenta ye de to.",
'throttled-mailpassword'     => "Yá s'unvió un recordatoriu de la clave nes caberes $1
hores. Pa evitar l'abusu, namás sedrá unviáu un recordatoriu
cada $1 hores.",
'mailerror'                  => "Error unviando'l corréu: $1",
'acct_creation_throttle_hit' => 'Yá creasti $1 cuentes. Nun pues abrir más.',
'emailauthenticated'         => 'La to dirección de corréu confirmóse a les $1.',
'emailnotauthenticated'      => 'La to dirección de corréu nun ta comprobada. Hasta que se faiga, les siguientes funciones nun tarán disponibles.',
'noemailprefs'               => "Especifica una direición de corréu pa qu'estes funcionalidaes furrulen.",
'emailconfirmlink'           => 'Confirmar la dirección de corréu',
'invalidemailaddress'        => "La direición de corréu nun se pue aceutar yá que paez tener un formatu
non válidu. Por favor escribi una direición con formatu afayadizu o dexa vaciu'l campu.",
'accountcreated'             => 'Cuenta creada',
'accountcreatedtext'         => "La cuenta d'usuariu de $1 ta creada.",
'createaccount-title'        => 'Creación de cuenta pa {{SITENAME}}',
'createaccount-text'         => 'Daquién ($1) creó una cuenta pa $2 en {{SITENAME}}
($4). La clave de "$2" ye "$3". Habríes identificate y camudar la to clave agora.

Pues inorar esti mensaxe si la cuenta foi creada por error.',
'loginlanguagelabel'         => 'Llingua: $1',

# Password reset dialog
'resetpass'               => "Restablecer la clave d'usuariu",
'resetpass_announce'      => "Identificástiti con una clave temporal unviada per corréu. P'acabar d'identificate has escribir equí una clave nueva:",
'resetpass_header'        => 'Restablecer contraseña',
'resetpass_submit'        => 'Camudar clave y identificase',
'resetpass_success'       => '¡La to clave cambióse correutamente! Agora identificándote...',
'resetpass_bad_temporary' => 'Clave temporal non válida. Seique yá camudaras correutamente la clave o solicitaras una nueva clave temporal.',
'resetpass_forbidden'     => 'Les claves nun se puen camudar en {{SITENAME}}',
'resetpass_missing'       => 'Nun hai datos en formulariu.',

# Edit page toolbar
'bold_sample'     => 'Testu en negrina',
'bold_tip'        => 'Testu en negrina',
'italic_sample'   => 'Testu en cursiva',
'italic_tip'      => 'Testu en cursiva',
'link_sample'     => 'Títulu del enllaz',
'link_tip'        => 'Enllaz internu',
'extlink_sample'  => 'http://www.exemplu.com títulu del enllaz',
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
'media_tip'       => 'Enllaz a archivu multimedia',
'sig_tip'         => 'La to firma con fecha',
'hr_tip'          => 'Llinia horizontal (úsala con moderación)',

# Edit pages
'summary'                   => 'Resume',
'subject'                   => 'Asuntu/títulu',
'minoredit'                 => 'Esta ye una edición menor',
'watchthis'                 => 'Vixilar esta páxina',
'savearticle'               => 'Grabar páxina',
'preview'                   => 'Previsualizar',
'showpreview'               => 'Amosar previsualización',
'showlivepreview'           => 'Vista rápida',
'showdiff'                  => 'Amosar cambeos',
'anoneditwarning'           => "'''Avisu:''' Nun tas identificáu. La to IP va quedar grabada nel historial d'edición d'esta páxina.",
'missingsummary'            => "'''Recordatoriu:''' Nun escribisti un resume d'edición. Si vuelves a calcar en Guardar, la to edición sedrá guardada ensin nengún resume.",
'missingcommenttext'        => 'Por favor, escribi un comentariu embaxo.',
'missingcommentheader'      => "'''Recordatoriu:''' Nun-y punxisti tema/títulu a esti comentariu. Si vuelves a calcar en Guardar, la to edición va grabase ensin él.",
'summary-preview'           => 'Previsualización del resume',
'subject-preview'           => 'Previsualización del tema/títulu',
'blockedtitle'              => "L'usuariu ta bloquiáu",
'blockedtext'               => "<big>'''El to nome d'usuariu o la direición IP foi bloquiáu.'''</big>

El bloquéu féxolu $1. La razón declarada ye ''$2''.

* Entamu del bloquéu: $8
* Caducidá del bloquéu: $6
* Usuariu que se quier bloquiar: $7

Pues ponete en contautu con $1 o con cualesquier otru [[{{MediaWiki:Grouppage-sysop}}|alministrador]] pa discutir el bloquéu.
Nun pues usar la funcionalidá 'manda-y un email a esti usuariu' a nun ser que tea especificada una direición de corréu válida
na to [[Special:Preferences|páxina de preferencies]] y que nun te tengan bloquiao el so usu.
La to direición IP actual ye $3, y el númberu d'identificación del bloquéu ye $5. Por favor, amiesta dalgún o dambos d'estos datos nes tos consultes.",
'autoblockedtext'           => "La to direición IP foi bloquiada automáticamente porque foi usada por otru usuariu que foi bloquiáu por \$1.
El motivu conseñáu foi esti:

:''\$2''

* Entamu del bloquéu: \$8
* Caducidá del bloquéu: \$6

Pues contautar con \$1 o con otru
[[{{MediaWiki:Grouppage-sysop}}|alministrador]] p'aldericar sobre'l bloquéu.

Fíxate en que nun pues usar la funcionalidá d'\"unvia-y un corréu a esti usuariu\" a nun se que tengas una direición de corréu válida rexistrada na to [[Special:Preferences|páxina de preferencies]] y que nun teas bloquiáu pa usala.

El códigu d'identificación del bloquéu ye'l \$5. Por favor amiesta esti códigu nes consultes que faigas.",
'blockednoreason'           => 'nun se dio razón dala',
'blockedoriginalsource'     => "El códigu fonte de '''$1''' amuésase equí:",
'blockededitsource'         => "El testu de '''les tos ediciones''' en '''$1''' amuésense equí:",
'whitelistedittitle'        => 'Ye necesario tar identificáu pa poder editar',
'whitelistedittext'         => 'Tienes que $1 pa editar páxines.',
'whitelistreadtitle'        => 'Necesítase identificación pa lleer',
'whitelistreadtext'         => "Tienes que t'[[Special:Userlogin|identificar]] pa lleer páxines.",
'whitelistacctitle'         => 'Nun tienes permisu pa crear una cuenta',
'whitelistacctext'          => 'Pa poder crear cuentes en {{SITENAME}} has tar [[Special:Userlogin|identificáu]] y tener los permisos afayadizos.',
'confirmedittitle'          => 'Requerida la confirmación de corréu electrónicu pa editar',
'confirmedittext'           => "Has confirmar la to direición de corréu electrónicu enantes d'editar páxines. Por favor, configúrala y valídala nes tos [[Special:Preferences|preferencies d'usuariu]].",
'nosuchsectiontitle'        => 'Nun esiste tala seición',
'nosuchsectiontext'         => 'Intentasti editar una seición que nun esiste.  Como nun hai seición $1, nun hai sitiu pa guardar la to edición.',
'loginreqtitle'             => 'Identificación Requerida',
'loginreqlink'              => 'identificase',
'loginreqpagetext'          => 'Has $1 pa ver otres páxines.',
'accmailtitle'              => 'Clave unviada.',
'accmailtext'               => 'La clave de "$1" foi unviada a $2.',
'newarticle'                => '(Nuevu)',
'newarticletext'            => 'Siguisti un enllaz a un artículu qu\'inda nun esiste. Pa crealu, empecipia a escribir na caxa d\'equí embaxo. Si llegasti equí por enquivocu, namás tienes que calcar nel botón "atrás" del to navegador.',
'anontalkpagetext'          => "----''Esta ye la páxina de discusión pa un usuariu anónimu qu'inda nun creó una cuenta o que nun la usa. Pola mor d'ello ha usase la direición numérica IP pa identificalu/la. Tala IP pue ser compartida por varios usuarios. Si yes un usuariu anónimu y notes qu'hai comentarios irrelevantes empobinaos pa ti, por favor [[Special:Userlogin|crea una cuenta o rexístrate]] pa evitar futures confusiones con otros usuarios anónimos.''",
'noarticletext'             => "Nestos momentos nun hai testu nesta páxina. Pues [[Special:Search/{{PAGENAME}}|buscar esti títulu]] n'otres páxines, o [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar ésta equí].",
'userpage-userdoesnotexist' => 'La cuenta d\'usuariu "$1" nun ta rexistrada. Por favor asegúrate de que quies crear/editar esta páxina.',
'clearyourcache'            => "'''Nota:''' Llueu de salvar, seique tengas que llimpiar la caché del navegador pa ver los cambeos.
*'''Mozilla / Firefox / Safari:''' caltién ''Shift'' mentes calques en ''Reload'', o calca ''Ctrl-Shift-R'' (''Cmd-Shift-R'' en Apple Mac)
*'''IE:''' caltién ''Ctrl'' mentes calques ''Refresh'', o calca ''Ctrl-F5''
*'''Konqueror:''' calca nel botón ''Reload'', o calca ''F5''
*'''Opera:''' los usuarios d'Opera seique necesiten borrar dafechu'l caché en ''Tools→Preferences''",
'usercssjsyoucanpreview'    => "<strong>Conseyu:</strong> Usa'l bottón 'Amosar previsualización' pa probar el to nuevu CSS/JS enantes de guardalu.",
'usercsspreview'            => "'''¡Recuerda que namái tas previsualizando'l CSS d'usuariu, entá nun se grabó!'''",
'userjspreview'             => "'''¡Recuerda que namái tas probando/previsualizando'l to JavaScript d'usuariu, entá nun se grabó!'''",
'userinvalidcssjstitle'     => "'''Avisu:''' Nun hai piel \"\$1\". Recuerda que les páxines personalizaes .css y .js usen un títulu en minúscules, p. ex. {{ns:user}}:Foo/monobook.css en cuenta de {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(Actualizao)',
'note'                      => '<strong>Nota:</strong>',
'previewnote'               => "<strong>¡Alcuérdate de qu'esto ye sólo una previsualización y los cambeos entá nun se grabaron!</strong>",
'previewconflict'           => "Esta previsualización amuesa'l testu del área d'edición d'enriba talo y como apaecerá si guardes los cambeos.",
'session_fail_preview'      => "<strong>¡Sentímoslo muncho! Nun se pudo procesar la to edición porque hebo una perda de datos de la sesión.
Inténtalo otra vuelta. Si nun se t'arregla, intenta salir y volver a rexistrate.</strong>",
'session_fail_preview_html' => "<strong>¡Sentímoslo! Nun se pudo procesar la to edición pola mor d'una perda de datos de sesión.</strong>

''Como {{SITENAME}} tien activáu'l HTML puru, la previsualización nun s'amosará como precaución escontra ataques en JavaScript.''

<strong>Si esti ye un intentu llexítimu d'edición, por favor inténtalo otra vuelta. Si tovía asina nun furrula, intenta desconeutate y volver a identificate.</strong>",
'token_suffix_mismatch'     => "<strong>La to edición nun foi aceutada porque'l to navegador mutiló los carauteres de puntuación
nel editor. La edición nun foi aceutada pa prevenir corrupciones na páxina de testu. Esto hai vegaes
que pasa cuando tas usando un proxy anónimu basáu en web que seya problemáticu.</strong>",
'editing'                   => 'Editando $1',
'editinguser'               => "Editando l'usuariu <b>$1</b>",
'editingsection'            => 'Editando $1 (seición)',
'editingcomment'            => 'Editando $1 (comentariu)',
'editconflict'              => "Conflictu d'edición: $1",
'explainconflict'           => "Daquién más camudó esta páxina dende qu'empecipiasti a editala.
Na área de testu d'enriba ta'l testu de la páxina como ta nestos momentos.
Los tos cambeos amuésense na área de testu d'embaxo.
Vas tener que fusionar los tos cambeos dientro del testu esistente.
<b>Namái</b> va guardase'l testu de l'área d'enriba cuando calques en
\"Guardar páxina\".<br />",
'yourtext'                  => 'El to testu',
'storedversion'             => 'Versión almacenada',
'nonunicodebrowser'         => "<strong>AVISU: El to navegador nun cumple la norma unicode. Hai un sistema alternativu que te permite editar páxines de forma segura: los carauteres non-ASCII apaecerán na caxa d'edición como códigos hexadecimales.</strong>",
'editingold'                => "<strong>AVISU: Tas editando una versión vieya d'esta páxina. Si la grabes, los cambeos que se ficieron dende esa versión van perdese.</strong>",
'yourdiff'                  => 'Diferencies',
'copyrightwarning'          => "Por favor, ten en cuenta que toles contribuciones de {{SITENAME}} considérense feches públiques baxo la $2 (ver $1 pa más detalles). Si nun quies que'l to trabayu seya editáu ensin midida, nun lu pongas equí.<br />
Amás tas dexándonos afitao qu'escribisti esto tu mesmu o que lo copiasti d'una fonte llibre de dominiu públicu o asemeyao.
<strong>¡NUN PONGAS TRABAYOS CON DERECHOS D'AUTOR ENSIN PERMISU!</strong>",
'copyrightwarning2'         => "Por favor, ten en cuenta que toles contribuciones de {{SITENAME}} puen ser editaes, alteraes o eliminaes por otros usuarios. Si nun quies que'l to trabayu seya editáu ensin midida, nun lu pongas equí.<br />
Amás tas dexándonos afitao qu'escribisti esto tu mesmu o que lo copiasti d'una fonte
llibre de dominiu públicu o asemeyao (ver $1 pa más detalles).
<strong>¡NUN PONGAS TRABAYOS CON DERECHOS D'AUTOR ENSIN PERMISU!</strong>",
'longpagewarning'           => '<strong>AVISU: Esta páxina tien más de $1 quilobytes; dellos navegadores puen tener problemes editando páxines de 32 ó más kb. Habríes dixebrar la páxina en seiciones más pequeñes.</strong>',
'longpageerror'             => "<strong>ERROR: El testu qu'unviasti tien $1 quilobytes, que ye
más que'l máximu de $2 quilobytes. Nun pue ser grabáu.</strong>",
'readonlywarning'           => '<strong>AVISU: La base de datos ta protexida por mantenimientu,
polo que nun vas poder grabar les tos ediciones nestos momentos. Seique habríes copiar
el testu nun archivu de testu y grabalu pa intentalo lluéu. </strong>',
'protectedpagewarning'      => '<strong>AVISU: Esta páxina ta protexida pa que sólo los alministradores puean editala.</strong>',
'semiprotectedpagewarning'  => "'''Nota:''' Esta páxina foi protexida pa que nun puean editala namái que los usuarios rexistraos.",
'cascadeprotectedwarning'   => "'''Avisu:''' Esta páxina ta protexida pa que namái los alministradores la puean editar porque ta enxerta {{PLURAL:$1|na siguiente páxina protexida|nes siguientes páxines protexíes}} en cascada:",
'templatesused'             => 'Plantíes usaes nesta páxina:',
'templatesusedpreview'      => 'Plantíes usaes nesta previsualización:',
'templatesusedsection'      => 'Plantíes usaes nesta seición:',
'template-protected'        => '(protexida)',
'template-semiprotected'    => '(semi-protexida)',
'nocreatetitle'             => 'Creación de páxines limitada',
'nocreatetext'              => 'Esti sitiu tien restrinxida la capacidá de crear páxines nueves.
Pues volver atrás y editar una páxina esistente, o bien [[Special:Userlogin|identificate o crear una cuenta]].',
'nocreate-loggedin'         => 'Nun tienes permisu pa crear páxines nueves en {{SITENAME}}.',
'permissionserrors'         => 'Errores de Permisos',
'permissionserrorstext'     => 'Nun tienes permisu pa facer eso {{PLURAL:$1|pola siguiente razón|poles siguientes razones}}:',
'recreate-deleted-warn'     => "'''Avisu: Tas volviendo a crear una páxina que foi borrada anteriormente.'''

Habríes considerar si ye afechisco siguir editando esta páxina.
Equí tienes el rexistru de borraos d'esta páxina:",

# "Undo" feature
'undo-success' => "La edición pue esfacese. Por favor comprueba la comparanza d'embaxo pa verificar que ye eso lo que quies facer, y depués guarda los cambeos p'acabar d'esfacer la edición.",
'undo-failure' => "Nun se pudo esfacer la edición pola mor d'ediciones intermedies conflictives.",
'undo-summary' => 'Esfecha la revisión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|discusión]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nun se pue crear la cuenta',
'cantcreateaccount-text' => "La creación de cuentes dende esta direición IP (<b>$1</b>) foi bloquiada por [[Usuariu:$3|$3]].

La razón dada por $3 ye ''$2''",

# History pages
'viewpagelogs'        => "Ver rexistros d'esta páxina",
'nohistory'           => "Nun hay historial d'ediciones pa esta páxina.",
'revnotfound'         => 'Revisión non atopada',
'revnotfoundtext'     => "La revisión antigua de la páxina que solicitasti nun se pudo atopar. Por favor comprueba l'URL qu'usasti p'acceder a esta páxina.",
'loadhist'            => "Cargando l'historial de la páxina",
'currentrev'          => 'Revisión actual',
'revisionasof'        => 'Revisión de $1',
'revision-info'       => 'Revisión a fecha de $1; $2',
'previousrevision'    => '←Revisión anterior',
'nextrevision'        => 'Revisión siguiente→',
'currentrevisionlink' => 'Revisión actual',
'cur'                 => 'act',
'next'                => 'próximu',
'last'                => 'cab',
'orig'                => 'orix',
'page_first'          => 'primera',
'page_last'           => 'cabera',
'histlegend'          => "Seleición de diferencies: marca los botones de les versiones que quies comparar y da-y al <i>enter</i> o al botón d'abaxo.<br />
Lleenda: '''(act)''' = diferencies cola versión actual,
'''(cab)''' = diferencies cola versión anterior, '''m''' = edición menor.",
'deletedrev'          => '[borráu]',
'histfirst'           => 'Primera',
'histlast'            => 'Cabera',
'historysize'         => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'        => '(vaciu)',

# Revision feed
'history-feed-title'          => 'Historial de revisiones',
'history-feed-description'    => "Historial de revisiones d'esta páxina na wiki",
'history-feed-item-nocomment' => '$1 en $2', # user at time
'history-feed-empty'          => 'La páxina solicitada nun esiste.
Seique fuera borrada o renomada na wiki.
Prueba a [[Special:Search|buscar na wiki]] otres páxines nueves.',

# Revision deletion
'rev-deleted-comment'         => '(comentariu elimináu)',
'rev-deleted-user'            => "(nome d'usuariu elimináu)",
'rev-deleted-event'           => '(entrada eliminada)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Esta revisión de la páxina foi eliminada de los archivos públicos.
Pue haber detalles nel [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} rexistru de borraos].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Esta revisión de la páxina foi eliminada de los archivos públicos.
Como alministrador d\'esti sitiu pues vela; pue haber detalles nel [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} rexistru de borraos].
</div>',
'rev-delundel'                => 'amosar/esconder',
'revisiondelete'              => 'Borrar/restaurar revisiones',
'revdelete-nooldid-title'     => 'Nun hai revisión de destín',
'revdelete-nooldid-text'      => 'Nun especificasti una revisión o revisiones de destín sobre les que realizar esta función.',
'revdelete-selected'          => "{{PLURAL:$2|Revisión seleicionada|Revisiones seleicionaes}} de '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Seleicionáu un eventu de rexistru|Seleicionaos eventos de rexistru}} pa '''$1:'''",
'revdelete-text'              => "Les revisiones y eventos eliminaos van siguir apaeciendo nel historial de la páxina
y nos rexistros, pero parte del so conteníu nun va ser accesible al públicu.

Otros alministrados de {{SITENAME}} van siguir pudiendo acceder al conteníu escondíu
y puen restauralu de nuevo al traviés d'esta mesma interfaz, a nun ser que s'establezan
restricciones adicionales.",
'revdelete-legend'            => 'Establecer restricciones:',
'revdelete-hide-text'         => 'Esconder testu de revisión',
'revdelete-hide-name'         => 'Esconder aición y oxetivu',
'revdelete-hide-comment'      => "Esconder comentariu d'edición",
'revdelete-hide-user'         => "Esconder el nome d'usuariu/IP del editor",
'revdelete-hide-restricted'   => "Aplicar estes restricciones a los alministradores lo mesmo qu'a los demás",
'revdelete-suppress'          => 'Eliminar datos de los alministradores lo mesmo que los de los demás',
'revdelete-hide-image'        => 'Esconder el conteníu del archivu',
'revdelete-unsuppress'        => 'Eliminar restricciones de revisiones restauraes',
'revdelete-log'               => 'Comentariu de rexistru:',
'revdelete-submit'            => 'Aplicar a la revisión seleicionada',
'revdelete-logentry'          => 'camudada la visibilidá de revisiones de [[$1]]',
'logdelete-logentry'          => "camudada la visibilidá d'eventos de [[$1]]",
'revdelete-logaction'         => '$1 {{PLURAL:$1|revisión establecida|revisiones establecíes}} en mou $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|eventu a [[$3]] establecíu|eventos a [[$3]] establecíos}} en mou $2',
'revdelete-success'           => 'Visibilidá de revisiones establecida correutamente.',
'logdelete-success'           => "Visibilidá d'eventos establecida correutamente.",

# History merging
'mergehistory'                     => 'Fusionar historiales de páxina',
'mergehistory-box'                 => 'Fusionar les revisiones de dos páxines:',
'mergehistory-from'                => "Páxina d'orixe:",
'mergehistory-into'                => 'Páxina de destín:',
'mergehistory-go'                  => 'Amosar ediciones fusionables',
'mergehistory-submit'              => 'Fusionar revisiones',
'mergehistory-empty'               => 'Nun se pue fusionar nenguna revisión',
'mergehistory-success'             => '$3 revisiones de [[:$1]] fusionaes correutamente en [[:$2]].',
'mergehistory-no-source'           => "La páxina d'orixe $1 nun esiste.",
'mergehistory-no-destination'      => 'La páxina de destín $1 nun esiste.',
'mergehistory-invalid-source'      => "La páxina d'orixe ha tener un títulu válidu.",
'mergehistory-invalid-destination' => 'La páxina de destín ha tener un títulu válidu.',

# Merge log
'mergelog'    => 'Rexistru de fusiones',
'revertmerge' => 'Dixebrar',

# Diffs
'history-title'           => 'Historial de revisiones de "$1"',
'difference'              => '(Diferencia ente revisiones)',
'lineno'                  => 'Llinia $1:',
'compareselectedversions' => 'Comparar les versiones seleicionaes',
'editundo'                => 'esfacer',
'diff-multi'              => '(Non {{PLURAL:$1|amosada una revisión intermedia|amosaes $1 revisiones intermedies}}.)',

# Search results
'searchresults'         => 'Resultaos de la busca',
'searchsubtitle'        => "Buscasti '''[[:$1]]'''",
'searchsubtitleinvalid' => "Buscasti '''$1'''",
'noexactmatch'          => "'''Nun esiste la páxina \"\$1\".''' Pues [[:\$1|crear esta páxina]].",
'noexactmatch-nocreate' => "'''Nun hai nenguna páxina col títulu \"\$1\".'''",
'prevn'                 => 'previos $1',
'nextn'                 => 'siguientes $1',
'viewprevnext'          => 'Ver ($1) ($2) ($3)',
'showingresults'        => "Abaxo {{PLURAL:$1|amuésase '''un''' resultáu|amuésense '''$1''' resultaos}}, entamando col #'''$2'''.",
'showingresultsnum'     => "Abaxo {{PLURAL:$3|amuésase '''un''' resultáu|amuésense '''$3''' resultaos}}, entamando col #'''$2'''.",
'powersearch'           => 'Buscar',
'powersearchtext'       => 'Buscar nel espaciu de nomes:<br />$1<br />$2 Llistar redireiciones<br />Buscar $3 $9',

# Preferences page
'preferences'              => 'Preferencies',
'mypreferences'            => 'Les mios preferencies',
'prefs-edits'              => "Númberu d'ediciones:",
'prefsnologin'             => 'Non identificáu',
'prefsnologintext'         => 'Necesites tar [[Special:Userlogin|identificáu]] pa poder camudar les preferencies.',
'qbsettings-none'          => 'Nenguna',
'qbsettings-fixedleft'     => 'Fixa a manzorga',
'qbsettings-fixedright'    => 'Fixa a mandrecha',
'qbsettings-floatingleft'  => 'Flotante a manzorga',
'qbsettings-floatingright' => 'Flotante a mandrecha',
'changepassword'           => 'Camudar clave',
'skin'                     => 'Apariencia',
'math'                     => 'Fórmules matemátiques',
'dateformat'               => 'Formatu de fecha',
'datedefault'              => 'Ensin preferencia',
'datetime'                 => 'Fecha y hora',
'math_unknown_error'       => 'error desconocíu',
'math_unknown_function'    => 'función desconocida',
'math_syntax_error'        => 'error de sintaxis',
'prefs-personal'           => 'Datos personales',
'prefs-rc'                 => 'Cambeos recientes',
'prefs-watchlist'          => 'Llista de vixilancia',
'prefs-watchlist-days'     => "Númberu de díes qu'amosar na llista de vixilancia:",
'prefs-watchlist-edits'    => "Númberu d'ediciones qu'amosar na llista de vixilancia espandida:",
'prefs-misc'               => 'Varios',
'saveprefs'                => 'Guardar preferencies',
'resetprefs'               => 'Volver a les preferencies por defeutu',
'oldpassword'              => 'Clave vieya:',
'newpassword'              => 'Clave nueva:',
'retypenew'                => 'Repiti la nueva clave:',
'textboxsize'              => 'Edición',
'rows'                     => 'Files:',
'columns'                  => 'Columnes:',
'searchresultshead'        => 'Busques',
'resultsperpage'           => "Resultaos p'amosar per páxina:",
'contextlines'             => "Llinies p'amosar per resultáu:",
'contextchars'             => 'Carauteres de testu per llinia:',
'recentchangescount'       => "Númberu d'ediciones amosaes en cambeos recientes:",
'savedprefs'               => 'Les tos preferencies quedaron grabaes.',
'timezonelegend'           => 'Zona horaria',
'timezonetext'             => 'Diferencia horaria ente la UTC y la to hora llocal.',
'localtime'                => 'Hora llocal',
'timezoneoffset'           => 'Diferencia¹',
'servertime'               => 'Hora del servidor',
'guesstimezone'            => 'Obtener del navegador',
'allowemail'               => 'Dexar a los otros usuarios mandate correos',
'defaultns'                => 'Buscar por defeutu nestos espacios de nome:',
'default'                  => 'por defeutu',
'files'                    => 'Archivos',

# User rights
'userrights-user-editname'    => "Escribi un nome d'usuariu:",
'editusergroup'               => "Modificar grupos d'usuarios",
'userrights-editusergroup'    => "Editar los grupos d'usuariu",
'saveusergroups'              => "Guardar los grupos d'usuariu",
'userrights-groupsmember'     => 'Miembru de:',
'userrights-groupsavailable'  => 'Grupos disponibles:',
'userrights-reason'           => 'Motivu del cambéu:',
'userrights-available-add'    => 'Pues añader usuarios a $1.',
'userrights-available-remove' => 'Pues eliminar usuarios de $1.',

# Groups
'group'            => 'Grupu:',
'group-bot'        => 'Bots',
'group-sysop'      => 'Alministradores',
'group-bureaucrat' => 'Burócrates',
'group-all'        => '(toos)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Alministrador',
'group-bureaucrat-member' => 'Burócrata',

'grouppage-bot'        => '{{ns:project}}:Bots',
'grouppage-sysop'      => '{{ns:project}}:Alministradores',
'grouppage-bureaucrat' => '{{ns:project}}:Burócrates',

# User rights log
'rightslog'     => "Rexistru de perfil d'usuariu",
'rightslogtext' => "Esti ye un rexistru de los cambeos de los perfiles d'usuariu.",
'rightsnone'    => '(nengún)',

# Recent changes
'nchanges'                       => '{{PLURAL:$1|un cambéu|$1 cambeos}}',
'recentchanges'                  => 'Cambeos recientes',
'recentchanges-feed-description' => 'Sigue nesti canal los cambeos más recientes de la wiki.',
'rcnote'                         => "Equí embaxo {{PLURAL:$1|pue vese '''1''' cambéu|puen vese los caberos '''$1''' cambeos}} {{PLURAL:$2|nel caberu día|nos caberos '''$2''' díes}}, a fecha de $3.",
'rcnotefrom'                     => 'Abaxo tán los cambeos dende <b>$2</b> (hasta <b>$1</b>).',
'rclistfrom'                     => 'Amosar los cambeos recientes dende $1',
'rcshowhideminor'                => '$1 ediciones menores',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 usuarios rexistraos',
'rcshowhideanons'                => '$1 usuarios anónimos',
'rcshowhidepatr'                 => '$1 ediciones patrullaes',
'rcshowhidemine'                 => '$1 les mios ediciones',
'rclinks'                        => 'Amosar los caberos $1 cambeos nos caberos $2 díes <br />$3',
'diff'                           => 'dif',
'hist'                           => 'hist',
'hide'                           => 'Esconder',
'show'                           => 'Amosar',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc_categories_any'              => 'Cualesquiera',
'newsectionsummary'              => '/* $1 */ nueva seición',

# Recent changes linked
'recentchangeslinked'          => 'Cambeos rellacionaos',
'recentchangeslinked-title'    => 'Cambeos rellacionaos con $1',
'recentchangeslinked-noresult' => 'Nun hebo cambeos nes páxines enllaciaes nel periodu conseñáu.',
'recentchangeslinked-summary'  => "Esta páxina especial llista los caberos cambeos nes páxines enllacies. Les páxines de la to llista de vixilancia tán en '''negrina'''.",

# Upload
'upload'               => 'Xubir imaxe',
'uploadbtn'            => 'Xubir',
'reupload'             => 'Volver a xubir',
'uploadnologin'        => 'Nun tas identificáu',
'uploadnologintext'    => 'Tienes que tar [[Special:Userlogin|identificáu]] pa poder xubir archivos.',
'uploaderror'          => 'Error de xubida',
'upload-permitted'     => "Menes d'archivu permitíes: $1.",
'upload-preferred'     => "Menes d'archivu preferíes: $1.",
'upload-prohibited'    => "Menes d'archivu prohibíes: $1.",
'uploadlog'            => 'rexistru de xubíes',
'uploadlogpage'        => 'Rexistru de xubíes',
'filename'             => "Nome d'archivu",
'filedesc'             => 'Resume',
'fileuploadsummary'    => 'Resume:',
'filestatus'           => 'Estáu de Copyright',
'filesource'           => 'Fonte',
'uploadedfiles'        => 'Archivos xubíos',
'ignorewarning'        => "Inorar l'avisu y grabar l'archivu de toes formes.",
'ignorewarnings'       => 'Inorar tolos avisos',
'minlength1'           => "Los nomes d'archivu han tener a lo menos una lletra.",
'illegalfilename'      => 'El nome d\'archivu "$1" contién carauteres non permitíos en títulos de páxina. Por favor renoma l\'archivu y xúbilu otra vuelta.',
'badfilename'          => 'Nome de la imaxe camudáu a "$1".',
'filetype-missing'     => 'L\'archivu nun tien estensión (como ".jpg").',
'large-file'           => 'Encamiéntase a que los archivos nun pasen de $1; esti archivu tien $2.',
'largefileserver'      => 'Esti archivu ye mayor de lo que permite la configuración del servidor.',
'emptyfile'            => "L'archivu que xubisti paez tar vaciu. Esto podría ser pola mor d'un enquivocu nel nome l'archivu. Por favor, camienta si daveres quies xubir esti archivu.",
'fileexists'           => 'Yá esiste un archivu con esti nome, por favor comprueba <strong><tt>$1</tt></strong> si nun tas seguru de quere camudalu.',
'fileexists-thumb'     => "<center>'''Imaxe esistente'''</center>",
'fileexists-forbidden' => 'Yá esiste un archivu con esti nome; por favor vuelve atrás y xubi esti archivu con otru nome. [[Image:$1|thumb|center|$1]]',
'successfulupload'     => 'Xubida correuta',
'savefile'             => 'Grabar archivu',
'uploadedimage'        => 'Xubíu "[[$1]]"',
'overwroteimage'       => 'xubida una versión nueva de "[[$1]]"',
'uploaddisabled'       => 'Deshabilitaes les xubíes',
'uploadvirus'          => "¡L'archivu tien un virus! Detalles: $1",
'sourcefilename'       => "Nome d'orixe",
'destfilename'         => 'Nome de destín',
'watchthisupload'      => 'Vixilar esta páxina',

'upload-proto-error' => 'Protocolu incorreutu',
'upload-file-error'  => 'Error internu',
'upload-misc-error'  => 'Error de xubida desconocíu',

'nolicense'          => 'Nenguna seleicionada',
'license-nopreview'  => '(Previsualización non disponible)',
'upload_source_file' => ' (un archivu del to ordenador)',

# Image list
'imagelist'                 => "Llista d'imáxenes",
'imagelisttext'             => "Embaxo ta la llista {{PLURAL:$1|d'un archivu ordenáu|de '''$1''' archivos ordenaos}} $2.",
'ilsubmit'                  => 'Buscar',
'showlast'                  => 'Amosar los últimos $1 archivos ordenaos $2.',
'byname'                    => 'por nome',
'bydate'                    => 'por fecha',
'bysize'                    => 'por tamañu',
'imgdelete'                 => 'borr',
'imgdesc'                   => 'desc',
'imgfile'                   => 'archivu',
'filehist'                  => 'Historial del archivu',
'filehist-help'             => "Calca nuna fecha/hora pa ver l'archivu como taba daquélla.",
'filehist-deleteall'        => 'borrar too',
'filehist-deleteone'        => 'borrar esti',
'filehist-revert'           => 'revertir',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Fecha/Hora',
'filehist-user'             => 'Usuariu',
'filehist-dimensions'       => 'Dimensiones',
'filehist-filesize'         => 'Tamañu del archivu',
'filehist-comment'          => 'Comentariu',
'imagelinks'                => 'Enllaces a esta imaxe',
'linkstoimage'              => 'Les páxines siguientes enllacien a esti archivu:',
'nolinkstoimage'            => "Nun hai páxines qu'enllacien a esti archivu.",
'sharedupload'              => "L'archivu ye una xubida compartida y pue tar siendo usáu por otros proyeutos.",
'noimage'                   => 'Nun esiste archivu dalu con esi nome, pues $1.',
'noimage-linktext'          => 'xubilu',
'uploadnewversion-linktext' => "Xubir una nueva versión d'esta imaxe",
'imagelist_date'            => 'Fecha',
'imagelist_name'            => 'Nome',
'imagelist_user'            => 'Usuariu',
'imagelist_size'            => 'Tamañu',
'imagelist_description'     => 'Descripción',

# File reversion
'filerevert'         => 'Revertir $1',
'filerevert-legend'  => 'Revertir archivu',
'filerevert-comment' => 'Comentariu:',
'filerevert-submit'  => 'Revertir',

# File deletion
'filedelete'         => 'Borrar $1',
'filedelete-legend'  => 'borrar archivu',
'filedelete-intro'   => "Tas borrando '''[[Media:$1|$1]]'''.",
'filedelete-comment' => 'Comentariu:',
'filedelete-submit'  => 'Borrar',
'filedelete-success' => "'''$1''' foi borráu.",
'filedelete-nofile'  => "'''$1''' nun esiste nesti sitiu.",

# MIME search
'mimesearch' => 'Busca MIME',
'download'   => 'descargar',

# Unwatched pages
'unwatchedpages' => 'Páxines ensin vixilar',

# List redirects
'listredirects' => 'Llista de redireiciones',

# Unused templates
'unusedtemplates'    => 'Plantíes ensin usu',
'unusedtemplateswlh' => 'otros enllaces',

# Random page
'randompage' => 'Páxina al debalu',

# Random redirect
'randomredirect' => 'Redireición al debalu',

# Statistics
'statistics'             => 'Estadístiques',
'sitestats'              => 'Estadístiques de {{SITENAME}}',
'userstats'              => "Estadístiques d'usuariu",
'sitestatstext'          => "Hai un total {{PLURAL:\$1|d''''una''' páxina|de '''\$1''' páxines}} na base de datos.
Inclúi páxines de \"discusión\" , páxines sobre {{SITENAME}}, \"entamos\" mínimos,
redireiciones y otres que nun puen cuntar como páxines.  Ensin estes, hai {{PLURAL:\$2|'''una''' páxina|'''\$2''' páxines}} que son artículos llexítimos.

Hai {{PLURAL:\$8|xubida '''una''' imaxe|xubíes '''\$8''' imáxenes}}.

Hebo un total {{PLURAL:\$3|d''''una''' páxina visitada|de '''\$3''' páxines visitaes}}, y {{PLURAL:\$4|'''una''' edición|'''\$4''' ediciones}} dende qu'entamó {{SITENAME}}.
Esto fai una media de '''\$5''' ediciones per páxina, y '''\$6''' visites per edición.

La [http://meta.wikimedia.org/wiki/Help:Job_queue cola de xeres] ye de '''\$7'''.",
'statistics-mostpopular' => 'Páxines más vistes',

'disambiguations'      => 'Páxines de dixebra',
'disambiguationspage'  => 'Template:dixebra',
'disambiguations-text' => "Les siguientes páxines enllacien a una '''páxina de dixebra'''. En cuenta d'ello habríen enllaciar al artículu apropiáu.<br />Una páxina considérase de dixebra si usa una plantilla que tea enllaciada dende [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Redireiciones dobles',
'doubleredirectstext' => 'Esta páxina llista páxines que redireicionen a otres páxines de redireición. Cada filera contién enllaces a la primer y segunda redireición, asina como al oxetivu de la segunda redireición, que normalmente ye la páxina oxetivu "real", aonde la primer redireición habría empobinar.',

'brokenredirects'        => 'Redireiciones rotes',
'brokenredirectstext'    => 'Les siguientes redireiciones enllacien a páxines que nun esisten:',
'brokenredirects-edit'   => '(editar)',
'brokenredirects-delete' => '(borrar)',

'withoutinterwiki' => 'Páxines ensin interwikis',

'fewestrevisions' => "Páxines col menor númberu d'ediciones",

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoría|categoríes}}',
'nlinks'                  => '$1 {{PLURAL:$1|enllaz|enllaces}}',
'nmembers'                => '$1 {{PLURAL:$1|miembru|miembros}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisión|revisiones}}',
'nviews'                  => '$1 {{PLURAL:$1|vista|vistes}}',
'lonelypages'             => 'Páxines güérfanes',
'uncategorizedpages'      => 'Páxines non categorizaes',
'uncategorizedcategories' => 'Categoríes non categorizaes',
'uncategorizedimages'     => 'Imáxenes non categorizaes',
'uncategorizedtemplates'  => 'Plantíes non categorizaes',
'unusedcategories'        => 'Categoríes non usaes',
'unusedimages'            => 'Imáxenes non usaes',
'popularpages'            => 'Páxines populares',
'wantedcategories'        => 'Categoríes buscaes',
'wantedpages'             => 'Páxines buscaes',
'mostlinked'              => 'Páxines más enllaciaes',
'mostlinkedcategories'    => 'Categoríes más enllaciaes',
'mostlinkedtemplates'     => 'Plantíes más enllaciaes',
'mostcategories'          => 'Páxines con más categoríes',
'mostimages'              => 'Imáxenes más enllaciaes',
'mostrevisions'           => 'Páxines con más revisiones',
'allpages'                => 'Toles páxines',
'prefixindex'             => 'Páxines por prefixu',
'shortpages'              => 'Páxines curties',
'longpages'               => 'Páxines llargues',
'deadendpages'            => 'Páxines ensin salida',
'deadendpagestext'        => 'Les páxines siguientes nun enllacien a páxina dala de {{SITENAME}}.',
'protectedpages'          => 'Páxines protexíes',
'protectedtitles'         => 'Títulos protexíos',
'listusers'               => "Llista d'usuarios",
'specialpages'            => 'Páxines especiales',
'spheading'               => 'Páxines especiales pa tolos usuarios',
'restrictedpheading'      => 'Páxines especiales restrinxíes',
'newpages'                => 'Páxines nueves',
'newpages-username'       => "Nome d'usuariu:",
'ancientpages'            => 'Páxines más vieyes',
'intl'                    => 'Interwikis',
'move'                    => 'Treslladar',
'movethispage'            => 'Treslladar esta páxina',
'unusedcategoriestext'    => "Les siguientes categoríes esisten magar que nengún artículu o categoría faiga usu d'elles.",
'pager-newer-n'           => '{{PLURAL:$1|1 siguiente|$1 siguientes}}',
'pager-older-n'           => '{{PLURAL:$1|1 anterior|$1 anteriores}}',

# Book sources
'booksources'               => 'Fontes de llibros',
'booksources-search-legend' => 'Busca de fontes de llibros',
'booksources-go'            => 'Dir',
'booksources-text'          => "Esta ye una llista d'enllaces a otros sitios que vienden llibros nuevos y usaos, y que puen tener más información sobre llibros que pueas tar guetando:",

'categoriespagetext' => 'Les categoríes que vienen darréu esisten na wiki.',
'data'               => 'Datos',
'groups'             => "Grupos d'usuariu",
'alphaindexline'     => '$1 a $2',
'version'            => 'Versión',

# Special:Log
'specialloguserlabel'  => 'Usuariu:',
'speciallogtitlelabel' => 'Títulu:',
'log'                  => 'Rexistros',
'all-logs-page'        => 'Tolos rexistros',
'log-search-submit'    => 'Dir',
'alllogstext'          => "Visualización combinada de tolos rexistros disponibles de {{SITENAME}}. Pues filtrar la visualización seleicionando una mena de rexistru, el nome d'usuariu o la páxina afectada.",
'logempty'             => 'Nun hai coincidencies nel rexistru.',

# Special:Allpages
'nextpage'          => 'Páxina siguiente ($1)',
'prevpage'          => 'Páxina anterior ($1)',
'allpagesfrom'      => "Amosar páxines qu'entamen por:",
'allarticles'       => 'Toles páxines',
'allinnamespace'    => 'Toles páxines (espaciu de nomes $1)',
'allnotinnamespace' => 'Toles páxines (sacantes les del espaciu de nomes $1)',
'allpagesprev'      => 'Anteriores',
'allpagesnext'      => 'Siguientes',
'allpagessubmit'    => 'Dir',
'allpagesprefix'    => 'Amosar páxines col prefixu:',
'allpagesbadtitle'  => "El títulu dau a esta páxina nun yera válidu o tenía un prefixu d'enllaz interllingua o interwiki. Pue contener ún o más carauteres que nun se puen usar nos títulos.",
'allpages-bad-ns'   => '{{SITENAME}} nun tien l\'espaciu de nomes "$1".',

# Special:Listusers
'listusersfrom'      => 'Amosar usuarios emprimando dende:',
'listusers-submit'   => 'Amosar',
'listusers-noresult' => "Nun s'atoparon usuarios.",

# E-mail user
'emailuser'       => 'Manda-y un email a esti usuariu',
'emailpage'       => "Corréu d'usuariu",
'emailpagetext'   => "Si esti usuariu metió una direición de corréu electrónicu válida nes sos preferencies d'usuariu, el formulariu d'embaxo va unviar un mensaxe simple. La direición de corréu electrónicu que metisti nes tos preferencies d'usuariu va apaecer como la direición \"Dende\" del corréu, pa que'l que lo recibe seya quien a responder.",
'defemailsubject' => 'Corréu electrónicu de {{SITENAME}}',
'noemailtitle'    => 'Ensin direición de corréu',
'noemailtext'     => "Esti usuariu nun punxo una dirección de corréu válida,
o nun quier recibir correos d'otros usuarios.",
'emailfrom'       => 'De',
'emailto'         => 'A',
'emailsubject'    => 'Asuntu',
'emailmessage'    => 'Mensaxe',
'emailsend'       => 'Unviar',
'emailccme'       => 'Unviame per corréu una copia del mio mensaxe.',
'emailsent'       => 'Corréu unviáu',
'emailsenttext'   => 'El to corréu foi unviáu.',

# Watchlist
'watchlist'            => 'La mio páxina de vixilancia',
'mywatchlist'          => 'La mio páxina de vixilancia',
'watchlistfor'         => "(pa '''$1''')",
'nowatchlist'          => 'La to llista de vixilancia ta vacia.',
'watchlistanontext'    => 'Por favor $1 pa ver o editar entraes na to llista de vixilancia.',
'watchnologin'         => 'Non identificáu',
'watchnologintext'     => 'Tienes que tar [[Special:Userlogin|identificáu]] pa poder camudar la to llista de vixilancia.',
'addedwatch'           => 'Añadida a la llista de vixilancia',
'addedwatchtext'       => 'Añadióse la páxina "[[:$1]]" a la to [[Special:Watchlist|llista de vixilancia]]. Los cambeos nesta páxina y la so páxina de discusión asociada van salite en negrina na llista de [[Special:Recentchanges|cambeos recientes]] pa que seya más fácil de vela.

Si más tarde quies quitala de la llista de vixilancia calca en "Dexar de vixilar" nel menú llateral.',
'removedwatch'         => 'Eliminada de la llista de vixilancia',
'removedwatchtext'     => 'Quitóse la páxina "[[:$1]]" de la to llista de vixilancia.',
'watch'                => 'Vixilar',
'watchthispage'        => 'Vixilar esta páxina',
'unwatch'              => 'Dexar de vixilar',
'unwatchthispage'      => 'Dexar de vixilar',
'notanarticle'         => 'Nun ye un artículu',
'watchnochange'        => 'Nenguna de les tos páxines vixilaes foi editada nel periodu escoyíu.',
'watchlist-details'    => '{{PLURAL:$1|$1 páxina|$1 páxines}} vixilaes ensin cuntar les páxines de discusión.',
'wlheader-enotif'      => '* La notificación per corréu electrónicu ta activada.',
'wlheader-showupdated' => "* Les páxines camudaes dende la to última visita amuésense en '''negrina'''",
'watchmethod-recent'   => 'buscando páxines vixilaes nos cambeos recientes',
'watchmethod-list'     => 'buscando cambeos recientes nes páxines vixilaes',
'watchlistcontains'    => 'La to llista de vixilancia tien $1 {{PLURAL:$1|páxina|páxines}}.',
'wlnote'               => "Abaxo {{PLURAL:$1|ta'l caberu cambéu|tán los caberos '''$1''' cambeos}} {{PLURAL:$2|na cabera hora|nes caberes '''$2''' hores}}.",
'wlshowlast'           => 'Amosar les últimes $1 hores $2 díes $3',
'watchlist-show-bots'  => 'Amosar ediciones de bot',
'watchlist-hide-bots'  => 'Esconder ediciones de bots',
'watchlist-show-own'   => 'Amosar les mios ediciones',
'watchlist-hide-own'   => 'Esconder les mios ediciones',
'watchlist-show-minor' => 'Amosar ediciones menores',
'watchlist-hide-minor' => 'Esconder ediciones menores',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Vixilando...',
'unwatching' => 'Dexando de vixilar...',

'enotif_reset'                 => 'Marcar toles páxines visitaes',
'enotif_newpagetext'           => 'Esta ye una páxina nueva.',
'enotif_impersonal_salutation' => 'Usuariu de {{SITENAME}}',
'changed'                      => 'camudada',
'created'                      => 'creada',
'enotif_subject'               => 'La páxina de {{SITENAME}} $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Mira en $1 tolos cambeos dende la to postrer visita.',
'enotif_lastdiff'              => 'Mira en $1 pa ver esti cambéu.',
'enotif_anon_editor'           => 'usuariu anónimu $1',

# Delete/protect/revert
'deletepage'                  => 'Borrar páxina',
'confirm'                     => 'Confirmar',
'excontent'                   => "el conteníu yera: '$1'",
'excontentauthor'             => "el conteníu yera: '$1' (y l'únicu autor yera '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "el conteníu enantes de dexar en blanco yera: '$1'",
'exblank'                     => 'la páxina taba vacia',
'confirmdelete'               => 'Confirmar el borráu',
'deletesub'                   => '(Borrando "$1")',
'historywarning'              => 'Avisu: La páxina que vas borrar tien historial:',
'confirmdeletetext'           => "Tas a piques de borrar dafechu una páxina de la base de datos, arriendes del so historial.
Por favor, onfirma que ye lo que quies facer, qu'entiendes es consecuencies, y que lo tas faciendo acordies coles [[{{MediaWiki:Policy-url}}|polítiques]].",
'actioncomplete'              => 'Aición completada',
'deletedtext'                 => 'Borróse "$1".
Mira en $2 la llista de les últimes páxines borraes.',
'deletedarticle'              => 'borró "[[$1]]"',
'dellogpage'                  => 'Rexistru de borraos',
'dellogpagetext'              => 'Abaxo tán los artículos borraos más recién.',
'deletionlog'                 => 'rexistru de borraos',
'deletecomment'               => 'Razón pa borrar',
'deleteotherreason'           => 'Otra razón/razón adicional:',
'deletereasonotherlist'       => 'Otra razón',
'deletereason-dropdown'       => '*Motivos comunes de borráu
** A pidimientu del autor
** Violación de Copyright
** Vandalismu',
'rollback_short'              => 'Revertir',
'rollbacklink'                => 'revertir',
'cantrollback'                => "Nun se pue revertir la edición; el postrer collaborador ye l'únicu autor d'esta páxina.",
'alreadyrolled'               => 'Nun se pue revertir la postrer edición de [[:$1]]
fecha por [[User:$2|$2]] ([[User talk:$2|discusión]]); daquién más yá editó o revirtió la páxina.

La postrer edición foi fecha por [[User:$3|$3]] ([[User talk:$3|discusión]]).',
'editcomment'                 => 'El comentariu de la edición yera: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Revertíes les ediciones de [[Special:Contributions/$2|$2]] ([[User talk:$2|discusión]]) hasta la versión de [[User:$1|$1]]',
'sessionfailure'              => 'Paez qu\'hai un problema cola to sesión; por precaución
cancelóse l\'aición que pidisti. Da-y al botón "Atrás" del
navegador pa cargar otra vuelta la páxina y vuelve a intentalo.',
'protectlogpage'              => 'Rexistru de proteiciones',
'protectlogtext'              => 'Esti ye un rexistru de les páxines protexíes y desprotexíes. Consulta la [[Special:Protectedpages|llista de páxines protexíes]] pa ver les proteiciones actives nestos momentos.',
'protectedarticle'            => 'protexó $1',
'unprotectedarticle'          => 'desprotexó "[[$1]]"',
'protectsub'                  => '(Protexendo "$1")',
'confirmprotect'              => 'Confirmar proteición',
'protectcomment'              => 'Comentariu:',
'protectexpiry'               => 'Caduca:',
'protect_expiry_invalid'      => 'Caducidá non válida.',
'protect_expiry_old'          => 'La fecha de caducidá ta pasada.',
'unprotectsub'                => '(Desprotexendo "$1")',
'protect-unchain'             => 'Camudar los permisos pa tresllaos',
'protect-text'                => 'Equí pues ver y camudar el nivel de proteición de la páxina <strong>$1</strong>.',
'protect-locked-access'       => 'La to cuenta nun tien permisu pa camudar los niveles de proteición de páxina.
Esta ye la configuración actual pa la páxina <strong>$1</strong>:',
'protect-cascadeon'           => "Esta páxina ta protexida nestos momentos porque ta inxerida {{PLURAL:$1|na siguiente páxina, que tien|nes siguientes páxines, que tienen}} activada la proteición en cascada. Pues camudar el nivel de proteición d'esta páxina, pero nun va afeutar a la proteición en cascada.",
'protect-default'             => '(por defeutu)',
'protect-fallback'            => 'Requier el permisu "$1"',
'protect-level-autoconfirmed' => 'Bloquiar usuarios non rexistraos',
'protect-level-sysop'         => 'Namái alministradores',
'protect-summary-cascade'     => 'en cascada',
'protect-expiring'            => "caduca'l $1 (UTC)",
'protect-cascade'             => 'Páxines protexíes inxeríes nesta páxina (proteición en cascada)',
'protect-cantedit'            => "Nun pues camudar los niveles de proteición d'esta páxina porque nun tienes permisu pa editala.",
'restriction-type'            => 'Permisu:',
'restriction-level'           => 'Nivel de restricción:',
'minimum-size'                => 'Tamañu mínimu',
'maximum-size'                => 'Tamañu másimu',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit' => 'Editar',
'restriction-move' => 'Treslladar',

# Restriction levels
'restriction-level-sysop'         => 'totalmente protexida',
'restriction-level-autoconfirmed' => 'semiprotexida',
'restriction-level-all'           => 'cualesquier nivel',

# Undelete
'undelete'                 => 'Ver páxines borraes',
'undeletepage'             => 'Ver y restaurar páxines borraes',
'viewdeletedpage'          => 'Ver páxines borraes',
'undeletepagetext'         => "Les siguientes páxines foron borraes pero tovía tán nel archivu y puen
ser restauraes. L'archivu pue ser purgáu periódicamente.",
'undeleteextrahelp'        => "Pa restaurar tola páxina, deseleiciona toles caxelles y calca en
'''''Restaurar'''''. Pa realizar una restauración selectiva, seleiciona les caxelles de la revisión
que quies restaurar y calca en '''''Restaurar'''''. Calcando en '''''Llimpiar''''' quedarán vacios
el campu de comentarios y toles caxelles.",
'undeleterevisions'        => '$1 {{PLURAL:$1|revisión archivada|revisiones archivaes}}',
'undeletehistory'          => 'Si restaures la páxina, restauraránse toles revisiones al historial.
Si se creó una páxina col mesmu nome dende que foi borrada, les revisiones
restauraes van apaecer nel historial anterior. Date cuenta tamién de que les restricciones del archivu de revisiones
perderánse depués de la restauración',
'undeletehistorynoadmin'   => "Esta páxina foi borrada. La razón del borráu amuésase
nel resumen d'embaxo, amás de detalles de los usuarios qu'editaron esta páxina enantes
de ser borrada. El testu actual d'estes revisiones borraes ta disponible namái pa los alministradores.",
'undeletebtn'              => 'Restaurar',
'undeletereset'            => 'Llimpiar',
'undeletecomment'          => 'Comentariu:',
'undeletedarticle'         => 'restauróse "[[$1]]"',
'undeletedrevisions'       => '{{PLURAL:$1|1 revisión restaurada|$1 revisiones restauraes}}',
'undeletedrevisions-files' => '{{PLURAL:$1|1 revisión|$1 revisiones}} y {{PLURAL:$2|1 archivu|$2 archivos}} restauraos',
'undeletedfiles'           => '{{PLURAL:$1|1 archivu restauráu|$1 archivos restauraos}}',
'cannotundelete'           => 'Falló la restauración; seique daquién yá restaurara la páxina enantes.',
'undelete-search-box'      => 'Buscar páxines borraes',
'undelete-search-prefix'   => "Amosar páxines qu'empecipien por:",
'undelete-search-submit'   => 'Buscar',
'undelete-error-short'     => "Error al restaurar l'archivu: $1",

# Namespace form on various pages
'namespace'      => 'Espaciu de nomes:',
'invert'         => 'Invertir seleición',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribuciones del usuariu',
'mycontris'     => 'Les mios contribuciones',
'contribsub2'   => 'De $1 ($2)',
'nocontribs'    => "Nun s'atoparon cambeos que coincidan con esi criteriu.",
'uclinks'       => 'Ver los caberos $1 cambeos; ver los caberos $2 díes.',
'uctop'         => ' (últimu cambéu)',
'month'         => "Dende'l mes (y anteriores):",
'year'          => "Dende l'añu (y anteriores):",

'sp-contributions-newbies'     => 'Amosar namái les contribuciones de cuentes nueves',
'sp-contributions-newbies-sub' => 'Namái les cuentes nueves',
'sp-contributions-blocklog'    => 'Rexistru de bloqueos',
'sp-contributions-search'      => 'Buscar contribuciones',
'sp-contributions-username'    => "Direición IP o nome d'usuariu:",
'sp-contributions-submit'      => 'Buscar',

# What links here
'whatlinkshere'       => "Lo qu'enllaza equí",
'whatlinkshere-title' => "Páxines qu'enllacien a $1",
'whatlinkshere-page'  => 'Páxina:',
'linklistsub'         => "(Llista d'enllaces)",
'linkshere'           => "Les páxines siguientes enllacien a '''[[:$1]]''':",
'nolinkshere'         => "Nenguna páxina enllaza a '''[[:$1]]'''.",
'isredirect'          => 'páxina redirixida',
'istemplate'          => 'inclusión',
'whatlinkshere-prev'  => '{{PLURAL:$1|anterior|anteriores $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|siguiente|siguientes $1}}',
'whatlinkshere-links' => '← enllaces',

# Block/unblock
'blockip'                     => 'Bloquiar usuariu',
'blockiptext'                 => "Usa'l siguiente formulariu pa bloquiar el permisu d'escritura a una IP o a un usuariu concretu.
Esto debería facese sólo pa prevenir vandalismu como indiquen les [[{{MediaWiki:Policy-url}}|polítiques]]. Da una razón específica (como por exemplu citar páxines que fueron vandalizaes).",
'ipaddress'                   => 'Dirección IP:',
'ipadressorusername'          => "Dirección IP o nome d'usuariu:",
'ipbexpiry'                   => 'Caducidá:',
'ipbreason'                   => 'Razón:',
'ipbreasonotherlist'          => 'Otra razón',
'ipbreason-dropdown'          => "*Razones comunes de bloquéu
** Enxertamientu d'información falso
** Dexar les páxines en blanco
** Enllaces spam a páxines esternes
** Enxertamientu de babayaes/enguedeyos nes páxines
** Comportamientu intimidatoriu o d'acosu
** Abusu de cuentes múltiples
** Nome d'usuariu inaceutable",
'ipbanononly'                 => 'Bloquiar namái usuarios anónimos',
'ipbcreateaccount'            => 'Evitar creación de cuentes',
'ipbenableautoblock'          => "Bloquiar automáticamente la cabera direición IP usada por esti usuariu y toles IP posteriores dende les qu'intente editar",
'ipbsubmit'                   => 'Bloquiar esti usuariu',
'ipbother'                    => 'Otru periodu:',
'ipboptions'                  => '2 hores:2 hours,1 día:1 day,3 díes:3 days,1 selmana:1 week,2 selmanes:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 añu:1 year,pa siempre:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'otru',
'ipbotherreason'              => 'Otra razón/razón adicional:',
'badipaddress'                => 'IP non válida',
'blockipsuccesssub'           => 'Bloquéu fechu correctamente',
'blockipsuccesstext'          => "Bloquióse al usuariu [[Special:Contributions/$1|$1]].
<br />Mira na [[Special:Ipblocklist|llista d'IPs bloquiaes]] pa revisar los bloqueos.",
'ipb-edit-dropdown'           => 'Editar motivos de bloquéu',
'ipb-unblock-addr'            => 'Desbloquiar $1',
'ipb-unblock'                 => "Desbloquiar un nome d'usuariu o direición IP",
'ipb-blocklist-addr'          => 'Ver los bloqueos esistentes de $1',
'ipb-blocklist'               => 'Ver los bloqueos esistentes',
'unblockip'                   => 'Desbloquiar usuariu',
'ipusubmit'                   => 'Desbloquiar esta direición',
'unblocked'                   => '[[User:$1|$1]] foi desbloquiáu',
'ipblocklist'                 => "Llista de direiciones IP y nomes d'usuarios bloquiaos",
'ipblocklist-username'        => "Nome d'usuariu o direición IP:",
'ipblocklist-submit'          => 'Buscar',
'blocklistline'               => '$1, $2 bloquió a $3 ($4)',
'infiniteblock'               => 'pa siempre',
'expiringblock'               => "caduca'l $1",
'anononlyblock'               => 'namái anón.',
'noautoblockblock'            => 'bloquéu automáticu desactiváu',
'createaccountblock'          => 'bloquiada la creación de cuentes',
'emailblock'                  => 'corréu electrónicu bloquiáu',
'ipblocklist-empty'           => 'La llista de bloqueos ta vacia.',
'blocklink'                   => 'bloquiar',
'unblocklink'                 => 'desbloquiar',
'contribslink'                => 'contribuciones',
'autoblocker'                 => 'Bloquiáu automáticamente porque la to direición IP foi usada recién por "[[Usuariu:$1|$1]]". La razón del bloquéu de $1 ye: "$2"',
'blocklogpage'                => 'Rexistru de bloqueos',
'blocklogentry'               => 'bloquiáu [[$1]] con una caducidá de $2 $3',
'blocklogtext'                => "Esti ye un rexistru de los bloqueos y desbloqueos d'usuarios. Les direcciones IP
bloquiaes automáticamente nun salen equí. Pa ver los bloqueos qu'hai agora mesmo, 
mira na [[Special:Ipblocklist|llista d'IP bloquiaes]].",
'unblocklogentry'             => 'desbloquió $1',
'block-log-flags-anononly'    => 'namái usuarios anónimos',
'block-log-flags-nocreate'    => 'creación de cuentes deshabilitada',
'block-log-flags-noautoblock' => 'bloquéu automáticu deshabilitáu',
'block-log-flags-noemail'     => 'corréu electrónicu bloquiáu',
'ipb_expiry_invalid'          => 'Tiempu incorrectu.',
'ipb_already_blocked'         => '"$1" yá ta bloqueáu',
'ip_range_invalid'            => 'Rangu IP non válidu.',
'blockme'                     => 'Blóquiame',
'proxyblocker-disabled'       => 'Esta función ta deshabilitada.',
'proxyblocksuccess'           => 'Fecho.',
'sorbsreason'                 => 'La to direición IP sal na llista de proxys abiertos en DNSBL usada nesti sitiu.',
'sorbs_create_account_reason' => 'La to direición IP sal na llista de proxys abiertos en DNSBL usada nesti sitiu. Nun pues crear una cuenta',

# Developer tools
'lockdb'              => 'Protexer la base de datos',
'unlockdb'            => 'Desprotexer la base de datos',
'lockconfirm'         => 'Si, quiero protexer daveres la base de datos.',
'unlockconfirm'       => 'Sí, quiero desprotexer daveres la base de datos.',
'lockbtn'             => 'Protexer la base de datos',
'unlockbtn'           => 'Desprotexer la base de datos',
'unlockdbsuccesstext' => 'La base de datos foi desprotexida.',
'databasenotlocked'   => 'La base de datos nun ta bloquiada.',

# Move page
'movepage'                => 'Treslladar páxina',
'movepagetext'            => "Usando'l siguiente formulariu vas renomar una páxina, treslladando'l
so historial al nuevu nome. El nome vieyu va convertise nuna
redireición al nuevu. Los enllaces qu'hubiera al nome vieyu nun van
camudase; asegúrate de que nun dexes redireiciones dobles o rotes.
Tu yes el responsable de facer que los enllaces queden apuntando aonde
se supón qu'han apuntar.

Recuerda que la páxina '''nun''' va movese si yá hai una páxina col
nuevu títulu, a nun ser que tea vacia o seya una redireición que nun tenga
historial. Esto significa que pues volver a renomar una páxina col nome
orixinal si t'enquivoques, y que nun pues sobreescribir una páxina
yá esistente.

<b>¡AVISU!</b>
Esti pue ser un cambéu importante y inesperáu pa una páxina popular;
por favor, asegúrate d'entender les consecuencies de lo que vas facer
enantes de siguir.",
'movepagetalktext'        => "La páxina de discusión asociada va ser treslladada automáticamente '''a nun ser que:'''
*Yá esista una páxina de discusión non vacia col nuevu nome, o
*Desactives la caxella d'equí baxo.

Nestos casos vas tener que treslladar o fusionar la páxina manualmente.",
'movearticle'             => 'Treslladar la páxina:',
'movenologin'             => 'Non identificáu',
'movenologintext'         => 'Tienes que ser un usuariu rexistráu y tar [[Special:Userlogin|identificáu]] pa treslladar una páxina.',
'movenotallowed'          => 'Nun tienes permisu pa mover páxines en {{SITENAME}}.',
'newtitle'                => 'Al títulu nuevu:',
'move-watch'              => 'Vixilar esta páxina',
'movepagebtn'             => 'Treslladar la páxina',
'pagemovedsub'            => 'Treslláu correctu',
'movepage-moved'          => '<big>\'\'\'"$1" treslladóse a "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Yá hai una páxina con esi nome, o'l nome qu'escoyisti nun ye válidu. Por favor, escueyi otru nome.",
'talkexists'              => "'''La páxina treslladóse correutamente, pero non la so páxina de discusión porque yá esiste una col títulu nuevu. Por favor, fusiónala manualmente.'''",
'movedto'                 => 'treslladáu a',
'movetalk'                => 'Mover la páxina de discusión asociada',
'talkpagemoved'           => 'La páxina de discusión correspondiente tamién foi treslladada.',
'talkpagenotmoved'        => 'La páxina de discusión correspondiente <strong>nun</strong> foi treslladada.',
'1movedto2'               => '[[$1]] treslladáu a [[$2]]',
'1movedto2_redir'         => '[[$1]] treslladáu a [[$2]] sobre una redireición',
'movelogpage'             => 'Rexistru de tresllaos',
'movelogpagetext'         => 'Esta ye la llista de páxines treslladaes.',
'movereason'              => 'Razón:',
'revertmove'              => 'revertir',
'delete_and_move'         => 'Borrar y treslladar',
'delete_and_move_text'    => '==Necesítase borrar==

La páxina de destín "[[$1]]" yá esiste. ¿Quies borrala pa dexar sitiu pal treslláu?',
'delete_and_move_confirm' => 'Sí, borrar la páxina',
'delete_and_move_reason'  => 'Borrada pa facer sitiu pal treslláu',
'selfmove'                => "Los nomes d'orixe y destín son los mesmos, nun se pue treslladar una páxina sobre ella mesma.",
'immobile_namespace'      => "El nome d'orixe o'l de destín ye d'una triba especial; nun se puen mover páxines dende nin a esti espaciu de nomes.",

# Export
'export'        => 'Esportar páxines',
'exporttext'    => "Pues esportar el testu y l'historial d'ediciones d'una páxina en particular o d'una
riestra páxines endolcaes nun documentu XML. Esti pue ser importáu depués n'otra wiki
qu'use MediaWiki al traviés de la páxina [[Special:Importar|importar]].

Pa esportar páxines, pon los títulos na caxa de testu d'embaxo, un títulu per llinia,
y seleiciona si quies la versión actual xunto con toles versiones antigües, xunto col
so historial, o namái la versión actual cola información de la postrer edición.

Por último, tamién pues usar un enllaz: p.e. [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] pa la páxina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly' => 'Amestar namái la revisión actual, non tol historial',
'export-submit' => 'Esportar',
'export-addcat' => 'Añader',

# Namespace 8 related
'allmessages'               => 'Tolos mensaxes del sistema',
'allmessagesname'           => 'Nome',
'allmessagesdefault'        => 'Testu por defeutu',
'allmessagescurrent'        => 'Testu actual',
'allmessagestext'           => 'Esta ye una llista de tolos mensaxes disponibles nel espaciu de nomes de MediaWiki.',
'allmessagesnotsupportedDB' => "Nun pue usase '''{{ns:special}}:Allmessages''' porque '''\$wgUseDatabaseMessages''' ta deshabilitáu.",
'allmessagesfilter'         => 'Filtru pal nome del mensax:',
'allmessagesmodified'       => 'Amosar solo modificaos',

# Thumbnails
'thumbnail-more'  => 'Agrandar',
'missingimage'    => '<b>Falta la imaxe</b><br /><i>$1</i>',
'filemissing'     => 'Falta archivu',
'thumbnail_error' => 'Error al crear la miniatura: $1',
'djvu_page_error' => 'Páxina DjVu fuera de llímites',
'djvu_no_xml'     => 'Nun se pudo obtener el XML pal archivu DjVu',

# Special:Import
'import'                   => 'Importar páxines',
'import-interwiki-history' => "Copiar toles versiones d'historial d'esta páxina",
'import-interwiki-submit'  => 'Importar',
'importstart'              => 'Importando les páxines...',
'import-revision-count'    => '$1 {{PLURAL:$1|revisión|revisiones}}',
'importnotext'             => 'Vaciu o ensin testu',

# Import log
'importlogpage'                 => "Rexistru d'importaciones",
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|revisión|revisiones}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "La mio páxina d'usuariu",
'tooltip-pt-mytalk'               => 'La mio páxina de discusión',
'tooltip-pt-preferences'          => 'Les mios preferencies',
'tooltip-pt-watchlist'            => 'Llista de les páxines nes que tas vixilando los cambeos',
'tooltip-pt-mycontris'            => 'Llista de les mios contribuciones',
'tooltip-pt-login'                => 'Encamentámoste a identificate, anque nun ye obligatorio',
'tooltip-pt-logout'               => 'Salir',
'tooltip-ca-talk'                 => 'Discusión tocante al conteníu de la páxina',
'tooltip-ca-edit'                 => "Pues editar esta páxina. Por favor usa'l botón de previsualización enantes de guardar los cambeos.",
'tooltip-ca-addsection'           => 'Añade un comentariu a esta discusión.',
'tooltip-ca-viewsource'           => 'Esta páxina ta protexida. Pues ver el so códigu fonte.',
'tooltip-ca-history'              => "Versiones antigües d'esta páxina.",
'tooltip-ca-protect'              => 'Protexe esta páxina',
'tooltip-ca-delete'               => 'Borra esta páxina',
'tooltip-ca-move'                 => 'Tresllada esta páxina',
'tooltip-ca-watch'                => 'Añade esta páxina a la to llista de vixilancia',
'tooltip-ca-unwatch'              => 'Elimina esta páxina de la to llista de vixilancia',
'tooltip-search'                  => 'Busca en {{SITENAME}}',
'tooltip-p-logo'                  => 'Portada',
'tooltip-n-mainpage'              => 'Visita a la Portada',
'tooltip-n-portal'                => 'Tocante al proyeutu, qué facer, ú atopar coses',
'tooltip-n-currentevents'         => 'Información sobre los asocedíos actuales',
'tooltip-n-recentchanges'         => 'Llista de los cambeos recientes de la wiki.',
'tooltip-n-randompage'            => 'Carga una páxina al debalu',
'tooltip-n-help'                  => 'El llugar pa deprender',
'tooltip-n-sitesupport'           => 'Sofítanos',
'tooltip-t-whatlinkshere'         => "Llista de toles páxines wiki qu'enllacien equí",
'tooltip-feed-rss'                => 'Canal RSS pa esta páxina',
'tooltip-feed-atom'               => 'Canal Atom pa esta páxina',
'tooltip-t-contributions'         => "Amuesa la llista de contribuciones d'esti usuariu",
'tooltip-t-emailuser'             => 'Unvia un corréu a esti usuariu',
'tooltip-t-upload'                => 'Xube imáxenes o archivos multimedia',
'tooltip-t-specialpages'          => 'Llista de toles páxines especiales',
'tooltip-t-print'                 => "Versión imprentable d'esta páxina",
'tooltip-t-permalink'             => 'Enllaz permanente a esta versión de la páxina',
'tooltip-ca-nstab-user'           => "Amuesa la páxina d'usuariu",
'tooltip-ca-nstab-project'        => 'Amuesa la páxina de proyeutu',
'tooltip-ca-nstab-image'          => 'Amuesa la páxina de la imaxe',
'tooltip-ca-nstab-template'       => 'Amuesa la plantía',
'tooltip-ca-nstab-help'           => "Amuesa la páxina d'aida",
'tooltip-ca-nstab-category'       => 'Amuesa la páxina de categoría',
'tooltip-minoredit'               => 'Marca esti cambéu como una edición menor',
'tooltip-save'                    => 'Guarda los tos cambeos',
'tooltip-preview'                 => 'Previsualiza los tos cambeos. ¡Por favor, úsalo enantes de grabar!',
'tooltip-diff'                    => 'Amuesa los cambeos que fixisti nel testu.',
'tooltip-compareselectedversions' => "Amuesa les diferencies ente les dos versiones seleicionaes d'esta páxina.",
'tooltip-watch'                   => 'Amiesta esta páxina na to llista de vixilancia',

# Attribution
'anonymous'        => 'Usuariu/os anónimu/os de {{SITENAME}}',
'siteuser'         => '{{SITENAME}} usuariu $1',
'lastmodifiedatby' => "Esta páxina foi modificada per postrer vegada'l $1 a les $2 por $3.", # $1 date, $2 time, $3 user
'and'              => 'y',
'others'           => 'otros',
'siteusers'        => '{{SITENAME}} usuariu/os $1',
'creditspage'      => 'Páxina de creitos',
'nocredits'        => 'Nun hai disponible información de creitos pa esta páxina.',

# Spam protection
'subcategorycount'       => 'Hai {{PLURAL:$1|una subcategoría|$1 subcategoríes}} nesta categoría.',
'categoryarticlecount'   => 'Hai {{PLURAL:$1|una páxina|$1 páxines}} nesta categoría.',
'category-media-count'   => 'Hai {{PLURAL:$1|un archivu|$1 archivos}} nesta categoría.',
'listingcontinuesabbrev' => 'cont.',

# Info page
'numedits' => "Númberu d'ediciones (páxina): $1",

# Math options
'mw_math_png'    => 'Renderizar siempre PNG',
'mw_math_simple' => 'HTML si ye mui simple, o si non PNG',
'mw_math_html'   => 'HTML si ye posible, o si non PNG',
'mw_math_source' => 'Dexalo como TeX (pa navegadores de testu)',
'mw_math_modern' => 'Recomendao pa navegadores modernos',
'mw_math_mathml' => 'MathML si ye posible (esperimental)',

# Patrol log
'patrol-log-page' => 'Rexistru de supervisión',

# Image deletion
'deletedrevision'       => 'Borrada versión vieya $1',
'filedeleteerror-short' => "Error al borrar l'archivu: $1",
'filedeleteerror-long'  => "Atopáronse errores al borrar l'archivu:

$1",

# Browsing diffs
'previousdiff' => '← Diferencia anterior',
'nextdiff'     => 'Diferencia siguiente →',

# Media information
'imagemaxsize'         => 'Llendar les imáxenes nes páxines de descripción a:',
'thumbsize'            => 'Tamañu de la muestra:',
'widthheightpage'      => '$1×$2, $3 páxines',
'file-info'            => "(tamañu d'archivu: $1, triba MIME: $2)",
'file-info-size'       => "($1 × $2 píxeles, tamañu d'archivu: $3, triba MIME: $4)",
'file-nohires'         => '<small>Nun ta disponible con mayor resolución.</small>',
'svg-long-desc'        => "(archivu SVG, $1 × $2 píxeles nominales, tamañu d'archivu: $3)",
'show-big-image'       => 'Resolución completa',
'show-big-image-thumb' => "<small>Tamañu d'esta previsualización: $1 × $2 píxeles</small>",

# Special:Newimages
'newimages'    => "Galería d'imáxenes nueves",
'showhidebots' => '($1 bots)',

# Bad image list
'bad_image_list' => "El formatu ye'l que sigue:

Namái se tienen en cuenta les llinies qu'emprimen por un *. El primer enllaz d'una llinia ha ser ún qu'enllacie a una imaxe non válida.
Los demás enllaces de la mesma llinia considérense esceiciones, p.ex. páxines nes que la imaxe ha apaecer.",

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => "Esti archivu contién información adicional, probablemente añadida pola cámara dixital o l'escáner usaos pa crealu o dixitalizalu. Si l'archivu foi modificáu dende'l so estáu orixinal, seique dalgunos detalles nun tean reflexando la imaxe modificada.",
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
* focallength", # Do not translate list items

# EXIF tags
'exif-imagewidth'             => 'Anchor',
'exif-imagelength'            => 'Altor',
'exif-orientation'            => 'Orientación',
'exif-xresolution'            => 'Resolución horizontal',
'exif-yresolution'            => 'Resolución vertical',
'exif-imagedescription'       => 'Títulu de la imaxe',
'exif-make'                   => 'Fabricante de la cámara',
'exif-model'                  => 'Modelu de cámara',
'exif-software'               => 'Software usáu',
'exif-artist'                 => 'Autor',
'exif-compressedbitsperpixel' => "Mou de compresión d'imaxe",
'exif-makernote'              => 'Notes del fabricante',
'exif-usercomment'            => 'Comentarios del usuariu',
'exif-fnumber'                => 'Númberu F',
'exif-exposureprogram'        => "Programa d'esposición",
'exif-aperturevalue'          => 'Abertura',
'exif-brightnessvalue'        => 'Brillu',
'exif-lightsource'            => 'Fonte de la lluz',
'exif-flash'                  => 'Flax',
'exif-cfapattern'             => 'patrón CFA',
'exif-exposuremode'           => "Mou d'esposición",
'exif-whitebalance'           => 'Balance de blancos',
'exif-contrast'               => 'Contraste',
'exif-saturation'             => 'Saturación',
'exif-gpslatituderef'         => 'Llatitú Norte o Sur',
'exif-gpslatitude'            => 'Llatitú',
'exif-gpslongituderef'        => 'Llonxitú Este o Oeste',
'exif-gpslongitude'           => 'Llonxitú',
'exif-gpsaltitude'            => 'Altitú',
'exif-gpstimestamp'           => 'Hora GPS (reló atómicu)',
'exif-gpsmeasuremode'         => 'Mou de midida',
'exif-gpsdop'                 => 'Precisión de midida',
'exif-gpstrack'               => 'Direición de movimientu',
'exif-gpsdestlatitude'        => 'Llatitú de destín',
'exif-gpsdestlongitude'       => 'Llonxitú de destín',
'exif-gpsdestdistance'        => 'Distancia al destín',

# EXIF attributes
'exif-compression-1' => 'Non comprimida',

'exif-unknowndate' => 'Fecha desconocida',

'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Voltiada horizontalmente', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotada 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Voltiada verticalmente', # 0th row: bottom; 0th column: left

'exif-componentsconfiguration-0' => 'nun esiste',

'exif-exposureprogram-0' => 'Non definida',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',

'exif-subjectdistance-value' => '{{PLURAL:$1|$1 metru|$1 metros}}',

'exif-lightsource-0' => 'Desconocida',
'exif-lightsource-4' => 'Flax',

'exif-focalplaneresolutionunit-2' => 'pulgaes',

'exif-exposuremode-0' => 'Esposición automática',
'exif-exposuremode-1' => 'Esposición manual',

'exif-whitebalance-0' => 'Balance automáticu de blancos',
'exif-whitebalance-1' => 'Balance manual de blancos',

'exif-scenecapturetype-0' => 'Estándar',
'exif-scenecapturetype-1' => 'Paisaxe',
'exif-scenecapturetype-2' => 'Retratu',
'exif-scenecapturetype-3' => 'Escena nocherniega',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Fuerte',

# External editor support
'edit-externally'      => 'Editar esti ficheru usando una aplicación externa',
'edit-externally-help' => 'Pa más información echa un güeyu a les [http://meta.wikimedia.org/wiki/Help:External_editors instrucciones de configuración].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'toos',
'watchlistall2'    => 'too',
'namespacesall'    => 'toos',
'monthsall'        => 'toos',

# E-mail address confirmation
'confirmemail'            => 'Confirmar direición de corréu',
'confirmemail_noemail'    => "Nun tienes una direición de corréu válida nes tos [[Special:Preferences|preferencies d'usuariu]].",
'confirmemail_text'       => "{{SITENAME}} requier que valides la to direición de corréu enantes d'usar les
funcionalidaes de mensaxes. Da-y al botón que tienes equí embaxo pa unviar un avisu de
confirmación a la to direición. Esti avisu va incluyir un enllaz con un códigu; carga
l'enllaz nel to navegador pa confirmar la to direición de corréu electrónicu.",
'confirmemail_pending'    => '<div class="error">
Yá s\'unvió un códigu de confirmación a la to direición de corréu; si creasti hai poco la to cuenta, pues esperar dellos minutos a que-y de tiempu a llegar enantes de pidir otru códigu nuevu.
</div>',
'confirmemail_send'       => 'Unviar códigu de confirmación',
'confirmemail_sent'       => 'Corréu de confirmación unviáu.',
'confirmemail_oncreate'   => "Unvióse un códigu de confirmación a la to direición de corréu.
Esti códigu nun se necesita pa identificase, pero tendrás que lu conseñar enantes
d'activar cualesquier funcionalidá de la wiki que tea rellacionada col corréu.",
'confirmemail_sendfailed' => 'Nun se pudo unviar el corréu de confirmación. Revisa que nun punxeras carauteres non válidos na dirección de corréu.

El servidor de corréu devolvió: $1',
'confirmemail_invalid'    => 'Códigu de confirmación non válidu. El códigu seique tenga caducao.',
'confirmemail_needlogin'  => 'Tienes que $1 pa confirmar el to corréu.',
'confirmemail_success'    => 'El to corréu quedó confimáu. Agora yá pues identificate y collaborar na wiki.',
'confirmemail_loggedin'   => 'Quedó confirmada la to direición de corréu.',
'confirmemail_error'      => 'Hebo un problema al guardar la to confirmación.',
'confirmemail_subject'    => 'Confirmación de la dirección de corréu de {{SITENAME}}',
'confirmemail_body'       => 'Daquién, seique tu dende la IP $1, rexistró la cuenta "$2" con
esta direición de corréu en {{SITENAME}}.

Pa confirmar qu\'esta cuenta ye tuya daveres y asina activar les funcionalidaes
de corréu en {{SITENAME}}, abri esti enllaz nel to navegador:

$3

Si esti *nun* yes tú, nun abras l\'enllaz. Esti códigu de confirmación caduca en $4.',

# Delete conflict
'deletedwhileediting' => "Avisu: ¡Esta páxina foi borrada depués de qu'entamaras a editala!",
'confirmrecreate'     => "L'usuariu [[User:$1|$1]] ([[User talk:$1|discusión]]) borró esta páxina depués de qu'empecipiaras a editala pola siguiente razón:
: ''$2''
Por favor confirma que daveres quies volver a crear esta páxina.",

# HTML dump
'redirectingto' => 'Redireicionando a [[$1]]...',

# action=purge
'confirm_purge'        => "¿Llimpiar la caché d'esta páxina?

$1",
'confirm_purge_button' => 'Aceutar',

# AJAX search
'articletitles' => "Páxines qu'emprimen por ''$1''",
'useajaxsearch' => 'Usar la busca AJAX',

# Multipage image navigation
'imgmultipageprev' => '← páxina anterior',
'imgmultipagenext' => 'páxina siguiente →',

# Table pager
'ascending_abbrev'  => 'asc',
'descending_abbrev' => 'desc',
'table_pager_next'  => 'Páxina siguiente',
'table_pager_prev'  => 'Páxina anterior',
'table_pager_first' => 'Primer páxina',
'table_pager_last'  => 'Postrer páxina',

# Auto-summaries
'autosumm-blank'   => "Eliminando'l conteníu de la páxina",
'autosumm-replace' => "Sustituyendo la páxina por '$1'",
'autoredircomment' => 'Redirixendo a [[$1]]',
'autosumm-new'     => 'Páxina nueva: $1',

# Live preview
'livepreview-loading' => 'Cargando…',

# Watchlist editor
'watchlistedit-noitems'       => 'La to llista de vixilancia nun tien títulos.',
'watchlistedit-normal-submit' => 'Eliminar títulos',
'watchlistedit-normal-done'   => '{{PLURAL:$1|Eliminóse un títulu|Elimináronse $1 títulos}} de la to llista de vixilancia:',
'watchlistedit-raw-titles'    => 'Títulos:',
'watchlistedit-raw-submit'    => 'Actualizar llista de vixilancia',
'watchlistedit-raw-done'      => 'La to llista de vixilancia foi actualizada.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|Añadióse un títulu|Añadiéronse $1 títulos}}:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|Eliminóse ún títulu|Elimináronse $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver cambeos relevantes',
'watchlisttools-edit' => 'Ver y editar la llista de vixilancia',
'watchlisttools-raw'  => 'Editar la llista de vixilancia (en bruto)',

);
