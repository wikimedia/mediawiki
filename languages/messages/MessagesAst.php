<?php
/** Asturian (Asturianu)
 *
 * @addtogroup Language
 *
 * @author Esbardu
 * @author G - ג
 * @author Helix84
 * @author Mikel
 * @author SPQRobin
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
'tog-highlightbroken'         => 'Dái formatu a los enllaces rotos <a href="" class="new">como esti</a> (alternativu: como esti<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Xustificar parágrafos',
'tog-hideminor'               => 'Esconder ediciones menores nos cambeos recientes',
'tog-extendwatchlist'         => "Espander la llista de vixilancia p'amosar tolos cambeos aplicables",
'tog-usenewrc'                => 'Cambeos recientes ameyoraos (JavaScript)',
'tog-numberheadings'          => 'Autonumberar los apartaos',
'tog-showtoolbar'             => "Amosar barra de ferramientes d'edición (JavaScript)",
'tog-editondblclick'          => 'Editar páxines calcando dos vegaes (JavaScript)',
'tog-editsection'             => "Activar la edición de seiciones per aciu d'enllaces [editar]",
'tog-editsectiononrightclick' => 'Activar la edición de seiciones calcando col botón<br /> drechu enriba los títulos de seición (JavaScript)',
'tog-showtoc'                 => 'Amosar índiz (pa páxines con más de 3 apartaos)',
'tog-rememberpassword'        => 'Recordar clave ente sesiones',
'tog-editwidth'               => "La caxa d'edición tién el tamañu máximu",
'tog-watchcreations'          => 'Añader les páxines que creo a la mio llista de vixilancia',
'tog-watchdefault'            => "Añader les páxines qu'edito a la mio llista de vixilancia",
'tog-watchmoves'              => 'Añader les páxines que muevo a la mio llista de vixilancia',
'tog-watchdeletion'           => 'Añader les páxines que borro a la mio llista de vixilancia',
'tog-minordefault'            => 'Marcar toles ediciones como menores por defeutu',
'tog-previewontop'            => "Amosar previsualización enantes de la caxa d'edición",
'tog-previewonfirst'          => 'Amosar previsualización na primer edición',
'tog-nocache'                 => 'Desactivar la caché de les páxines',
'tog-enotifwatchlistpages'    => 'Mandáime un corréu cuando cambie una páxina que toi vixilando',
'tog-enotifusertalkpages'     => 'Mandáime un corréu cundo cambie la mio páxina de discusión',
'tog-enotifminoredits'        => 'Mandáime tamién un corréu pa les ediciones menores',
'tog-enotifrevealaddr'        => 'Amosar el mio corréu electrónicu nos correos de notificación',
'tog-shownumberswatching'     => "Amosar el númberu d'usuarios que tan vixilando",
'tog-fancysig'                => 'Firma ensin enllaz automáticu',
'tog-externaleditor'          => 'Usar un editor esternu por defeutu',
'tog-externaldiff'            => "Usar ''diff'' esternu por defeutu",
'tog-showjumplinks'           => 'Activar los enllaces d\'accesibilidá "saltar a"',
'tog-uselivepreview'          => 'Usar vista previa en direutu (JavaScript) (En prebes)',
'tog-watchlisthideown'        => 'Esconder les mios ediciones na llista de vixilancia',
'tog-watchlisthidebots'       => 'Esconder les ediciones de bots na llista de vixilancia',
'tog-watchlisthideminor'      => 'Esconder les ediciones menores na llista de vixilancia',
'tog-ccmeonemails'            => 'Mandáime copies de los correos que mando a otros usuarios',

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
'category-media-header' => 'Multimedios na categoría "$1"',
'category-empty'        => "''Esta categoría nun tien anguaño nengún artículu o ficheru multimedia.''",

'mainpagetext'      => "<big>'''MediaWiki instalóse corechamente.'''</big>",
'mainpagedocfooter' => "Visita la [http://meta.wikimedia.org/wiki/Help:Contents Guía d'usuariu] pa saber como usar esti software wiki.

== Entamando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Llista de les opciones de configuración]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ de MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce llista de corréu de lliberaciones de MediaWiki]",

'about'          => 'Tocante a',
'article'        => 'Conteníu de la páxina',
'newwindow'      => '(abriráse nuna ventana nueva)',
'cancel'         => 'Cancelar',
'qbfind'         => 'Alcontrar',
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

'errorpagetitle'    => 'Error',
'returnto'          => 'Vuelve a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ayuda',
'search'            => 'Buscar',
'searchbutton'      => 'Buscar',
'go'                => 'Dir',
'searcharticle'     => 'Dir',
'history'           => 'Historial de la páxina',
'history_short'     => 'Historia',
'updatedmarker'     => 'actualizáu dende la mio última visita',
'info_short'        => 'Información',
'printableversion'  => 'Versión pa imprentar',
'permalink'         => 'Enllaz permanente',
'print'             => 'Imprentar',
'edit'              => 'Editar',
'editthispage'      => 'Editar esta páxina',
'delete'            => 'Borrar',
'deletethispage'    => 'Borrar esta páxina',
'protect'           => 'Protexer',
'protectthispage'   => 'Protexer esta páxina',
'unprotect'         => 'Desprotexer',
'unprotectthispage' => 'Desprotexer esta páxina',
'newpage'           => 'Páxina nueva',
'talkpage'          => 'Discute esta páxina',
'talkpagelinktext'  => 'discusión',
'specialpage'       => 'Páxina especial',
'personaltools'     => 'Ferramientes personales',
'postcomment'       => 'Escribir un comentariu',
'articlepage'       => 'Ver conteníu de la páxina',
'talk'              => 'Discusión',
'views'             => 'Vistes',
'toolbox'           => 'Ferramientes',
'userpage'          => "Ver páxina d'usuariu",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Tocante a {{SITENAME}}',

# Login and logout pages
'acct_creation_throttle_hit' => 'Yá creasti $1 cuentes. Nun pues abrir más.',
'accountcreated'             => 'Cuenta creada',
'accountcreatedtext'         => "La cuenta d'usuariu de $1 ta creada.",

# Edit pages
'accmailtitle' => 'Clave unviada.',
'accmailtext'  => 'La clave de "$1" foi unviada a $2.',

# Preferences page
'allowemail' => 'Dexar a los otros usuarios mandate correos',

# Statistics
'sitestatstext' => "Hai un total de '''\$1''' páxines na base de datos.  Incluye páxines de \"discusión\" , páxines sobre {{SITENAME}}, \"entamos\" mínimos, redireiciones y otres que nun puen contar como páxines.  Ensin estes, hai '''\$2''' páxines que son artículos llexítimos.

'''\$8''' files have been uploaded.

Hubo un total de '''\$3''' páxines visitaes y '''\$4''' ediciones dende que la Uiqui entamó.  Esto fai una media de '''\$5''' ediciones por páxina y '''\$6''' visites por edición.

The [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] length is '''\$7'''.",

# Miscellaneous special pages
'allpages' => 'Toles páxines',

# Special:Log
'all-logs-page' => 'Tolos rexistros',
'alllogstext'   => "Visualización combinada de tolos rexistros disponibles de {{SITENAME}}. Pues filtrar la visualización seleicionando una mena de rexistru, el nome d'usuariu o la páxina afectada.",

# Special:Allpages
'allpagesfrom'      => "Amosar páxines qu'entamen por:",
'allarticles'       => 'Toles páxines',
'allinnamespace'    => 'Toles páxines (espaciu de nome $1)',
'allnotinnamespace' => 'Toles páxines (sacantes les del espaciu de nomes $1)',
'allpagesnext'      => 'Siguientes',
'allpagesbadtitle'  => "El títulu dau a esta páxina nun yera válidu o tenía un prefixu d'enlla inter-llingua o inter-wiki. Pue contener ún o más carauteres que nun se puen usar nos títulos.",
'allpages-bad-ns'   => '{{SITENAME}} nun tien l\'espaciu de nomes "$1".',

# Watchlist
'addedwatch'     => 'Añadío a la llista de vixilancia',
'addedwatchtext' => 'Añadióse la páxina "[[:$1]]" a la to [[Special:Watchlist|llista de vixilancia]]. Los cambeos nesta páxina y la so páxina de discusión asociada van salite en negrina na llista de [[Special:Recentchanges|cambeos recientes]] pa que seya más fácil de vela.

Si más tarde quies quitala de la llista de vixilancia calca en "Dexar de vixilar" nel menú llateral.',

# Delete/protect/revert
'actioncomplete' => 'Aición completada',

# Move page
'1movedto2'       => '[[$1]] treslladáu a [[$2]]',
'1movedto2_redir' => '[[$1]] treslladáu a [[$2]] sobre una redireición',

# Namespace 8 related
'allmessages'               => 'Tolos mensaxes del sistema',
'allmessagesname'           => 'Nome',
'allmessagesdefault'        => 'Testu por defeutu',
'allmessagescurrent'        => 'Testu actual',
'allmessagestext'           => 'Esta ye una llista de tolos mensaxes disponibles nel sistema de MediaWiki.',
'allmessagesnotsupportedDB' => "Nun pue usase '''{{ns:special}}:Allmessages''' porque '''\$wgUseDatabaseMessages''' ta deshabilitáu.",
'allmessagesfilter'         => 'Filtru pal nome del mensax:',
'allmessagesmodified'       => 'Amosar solo modificaos',

);
