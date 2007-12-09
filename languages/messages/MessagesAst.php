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
 * @author Mikel
 * @author SPQRobin
 * @author Esbardu
 * @author לערי ריינהארט
 * @author Helix84
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
'categorypage'      => 'Ver páxina de categoríes',
'viewtalkpage'      => 'Ver discusión',
'otherlanguages'    => 'Otres llingües',
'redirectedfrom'    => '(Redirixío dende $1)',
'redirectpagesub'   => 'Páxina de redirección',
'lastmodifiedat'    => "Esta páxina foi modificada per postrer vegada'l $1 a les $2.", # $1 date, $2 time
'viewcount'         => 'Esta páxina foi vista {{PLURAL:$1|1 vegaes|$1 vegaes}}.',
'protectedpage'     => 'Páxina protexida',

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
'disclaimerpage'    => 'Project:Limitación xeneral de responsabilidá',
'edithelp'          => "Ayuda d'edición",
'edithelppage'      => 'Help:Ayuda',
'faqpage'           => 'Project:Entrugues más frecuentes',
'helppage'          => 'Help:Conteníu',
'mainpage'          => 'Portada',
'portal'            => 'Portal de la comunidá',
'portal-url'        => 'Project:Portal de la comunidá',
'privacy'           => 'Politica de privacidá',
'privacypage'       => 'Project:Política_de_privacidá',
'sitesupport'       => 'Donativos',
'sitesupport-url'   => 'Project:Donativos',

'badaccess'        => 'Error de permisos',
'badaccess-group0' => "Nun tienes permisu pa executar l'aición solicitada.",
'badaccess-group1' => "L'aición solicitada ta llimitada a usuarios del grupu $1.",
'badaccess-group2' => "L'aición solicitada ta llimitada a usuarios d'ún de los grupos $1.",
'badaccess-groups' => "L'aición solicitada ta llimitada a usuarios d'ún de los grupos $1.",

'youhavenewmessages'      => 'Tienes $1 ($2).',
'newmessageslink'         => 'mensaxes nuevos',
'newmessagesdifflink'     => 'Dif. ente les dos últimes versiones',
'youhavenewmessagesmulti' => 'Tienes mensaxes nuevos en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'editsectionhint'         => 'Editar seición: $1',
'toc'                     => 'Tabla de conteníos',
'showtoc'                 => 'Ver',
'hidetoc'                 => 'esconder',
'thisisdeleted'           => '¿Ver o restaurar $1?',
'viewdeleted'             => '¿Ver $1?',
'restorelink'             => '{{PLURAL:$1|1 edicion|$1 ediciones}} borraes',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artículu',
'nstab-user'      => 'Páxina del usuariu',
'nstab-special'   => 'Especial',
'nstab-project'   => 'Páxina de proyeutu',
'nstab-image'     => 'semeya',
'nstab-mediawiki' => 'mensaxe',
'nstab-template'  => 'Plantilla',
'nstab-help'      => 'Ayuda',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'Nun esiste esa aición',
'nosuchspecialpage' => 'Nun esiste esa páxina especial',
'nospecialpagetext' => "<big>'''Pidisti una páxina especial non válida.'''</big>

Pues consultar la llista de les páxines especiales válides en [[Special:Specialpages]].",

# General errors
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
'noconnect'            => '¡Sentímoslo muncho! La wiki ta sufriendo dalles dificultaes téuniques y nun pue contautar col servidor de la base de datos. <br />
$1',
'cachederror'          => 'Esta ye una copia sacada del caché de la páxina solicitada y pue nun tar actualizada.',
'laggedslavemode'      => 'Avisu: Esta páxina pue que nun tenga actualizaciones recientes.',
'enterlockreason'      => 'Introduz una razón pa la protección, inxerida una estimación de cuándo la protección va ser llevantada',
'internalerror'        => 'Erru internu',
'internalerror_info'   => 'Error internu: $1',
'filecopyerror'        => 'Nun se pudo copiar l\'archivu "$1" como "$2".',
'filerenameerror'      => 'Nun se pudo renomar l\'archivu "$1" como "$2".',
'filedeleteerror'      => 'Nun se pudo borrar l\'archivu "$1".',
'directorycreateerror' => 'Nun se pudo crear el direutoriu "$1".',
'filenotfound'         => 'Nun se pudo atopar l\'archivu "$1".',
'badarticleerror'      => 'Esta aición nun pue facese nesta páxina',
'cannotdelete'         => 'Nun se fue pa borrar la páxina o imaxe seleicionada (seique daquién yá la borrara).',
'badtitle'             => 'Títulu incorreutu',
'badtitletext'         => 'El títulu de páxina solicitáu nun ye válidu, ta vaciu o tien enllaces inter-llingua o inter-wiki incorreutos. Pue que contenga ún o más carauteres que nun puen ser usaos nos títulos.',
'perfcached'           => 'Los siguientes datos tán na caché y pue que nun tean actualizaos del todo:',
'perfcachedts'         => "Los siguientes datos tán na caché y actualizáronse por última vegada'l $1.",
'viewsource'           => 'Ver fonte',
'protectedinterface'   => "Esta páxina proporciona testu d'interfaz a l'aplicación y ta protexida pa evitar el so abusu.",
'editinginterface'     => "'''Avisu:''' Tas editando una páxina usada pa proporcionar testu d'interfaz a l'aplicación. Los cambeos nesta páxina va afeuta-yos l'apariencia de la interfaz a otros usuarios.",
'cascadeprotected'     => 'Esta páxina ta protexida d\'ediciones porque ta enxerta {{PLURAL:$1|na siguiente páxina|nes siguientes páxines}}, que {{PLURAL:$1|ta protexida|tán protexíes}} cola opción "en cascada":',
'customcssjsprotected' => "Nun tienes permisu pa editar esta páxina porque contién preferencies personales d'otru usuariu.",

# Login and logout pages
'logouttext'                 => "<strong>Yá tas desconectáu.</strong><br />
Pues siguir usando {{SITENAME}} de forma anónima, o pues volver a entrar como'l mesmu o como otru usuariu.
Ten en cuenta que dalgunes páxines van continuar saliendo como si tovía tuvieres coneutáu, hasta que llimpies la caché del navegador.",
'welcomecreation'            => '== Bienveníu, $1! ==

La to cuenta ta creada.  Nun olvides escoyer les tos  {{SITENAME}} preferencies.',
'loginpagetitle'             => "Clave d'usuariu",
'yourname'                   => "Nome d'usuariu:",
'yourpassword'               => 'Clave:',
'yourpasswordagain'          => 'La to clave, otra vegada',
'remembermypassword'         => 'Recordar la mio clave nesti ordenador',
'yourdomainname'             => 'El to dominiu',
'login'                      => 'Entrar',
'loginprompt'                => "Has tener les ''cookies'' activaes pa entrar na {{SITENAME}}.",
'userlogin'                  => 'Entrar / Crear cuenta',
'logout'                     => 'Salir',
'userlogout'                 => 'Salir',
'notloggedin'                => 'Ensin entrar',
'nologin'                    => '¿Nun tienes una cuenta? $1.',
'nologinlink'                => '¡Fai una!',
'createaccount'              => 'Crea una nueva cuenta',
'gotaccount'                 => '¿Ya ties una cuenta? $1.',
'gotaccountlink'             => '¡Identifícate!',
'createaccountmail'          => 'per e-mail',
'badretype'                  => "Les claves qu'escribisti nun concuayen.",
'youremail'                  => 'Corréu electrónicu:',
'username'                   => "Nome d'usuariu:",
'uid'                        => "Númberu d'usuariu:",
'yourrealname'               => 'Nome real:',
'yourlanguage'               => 'Idioma de los menús',
'yournick'                   => 'El to nome (pa les firmes)',
'badsig'                     => 'Firma cruda non válida; comprueba les etiquetes HTML.',
'badsiglength'               => 'Nomatu demasiao llargu; ha tener menos de $1 carauteres.',
'email'                      => 'Corréu',
'prefs-help-email'           => "La direición de corréu ye opcional, pero permite a los demás contautar contigo al traviés de la to páxina d'usuariu ensin necesidá de revelar la to identidá.",
'noname'                     => "Nun punxisti un nome d'usuariu válidu.",
'loginsuccesstitle'          => 'Entrada con ésitu',
'loginsuccess'               => "'''Entrasti na {{SITENAME}} como \"\$1\".'''",
'nosuchuser'                 => 'Nun hai nengún usuariu col nome "$1".
Corrixe l\'erru o creya una nueva cuenta d\'usuariu abaxo.',
'nosuchusershort'            => 'Nun hai usuariu dalu col nome "$1". Mira que tea bien escritu.',
'nouserspecified'            => "Has especificar un nome d'usuariu.",
'wrongpassword'              => 'Clave errónea.  Inténtalo otra vegada',
'wrongpasswordempty'         => 'La clave taba en blanco. Inténtalo otra vegada.',
'passwordtooshort'           => 'La to contraseña ye demasiao curtia. Ha tener a lo menos $1 carauteres.',
'mailmypassword'             => 'Unviame per corréu la clave',
'noemail'                    => 'L\'usuariu "$1" nun tien puesta dirección de corréu.',
'blocked-mailpassword'       => 'La edición ta bloquiada dende la to direición IP, y por tanto
nun se pue usar la función de recuperación de clave pa evitar abusos.',
'eauthentsent'               => "Unvióse una confirmación de corréu electrónicu a la direición indicada.
Enantes de que s'unvie nengún otru corréu a la cuenta, has siguir les instrucciones del corréu electrónicu, pa confirmar que la cuenta ye de to.",
'acct_creation_throttle_hit' => 'Yá creasti $1 cuentes. Nun pues abrir más.',
'emailauthenticated'         => 'La to dirección de corréu confirmóse a les $1.',
'emailnotauthenticated'      => 'La to dirección de corréu nun ta comprobada. Hasta que se faiga les siguientes funciones nun tarán disponibles:',
'emailconfirmlink'           => 'Confirmar la dirección de corréu',
'accountcreated'             => 'Cuenta creada',
'accountcreatedtext'         => "La cuenta d'usuariu de $1 ta creada.",
'loginlanguagelabel'         => 'Llingua: $1',

# Edit page toolbar
'bold_sample'     => 'Testu en negrina',
'bold_tip'        => 'Testu en negrina',
'italic_sample'   => 'Testu en cursiva',
'italic_tip'      => 'Testu en cursiva',
'link_tip'        => 'Enllaz internu',
'extlink_sample'  => 'http://www.exemplu.com títulu del enllaz',
'extlink_tip'     => "Enllaz esternu (recuerda'l prefixu http://)",
'headline_sample' => 'Testu de cabecera',
'headline_tip'    => 'Testu cabecera nivel 2',
'math_sample'     => 'Inxertar fórmula equí',
'math_tip'        => 'Fórmula matemática',
'image_sample'    => 'Exemplu.jpg',
'image_tip'       => 'Inxertar imaxe',
'media_sample'    => 'Exemplu.ogg',
'hr_tip'          => 'Llinia horizontal (úsala con moderación)',

# Edit pages
'summary'                  => 'Resume',
'subject'                  => 'Asuntu/títulu',
'minoredit'                => 'Ésta ye una edición menor',
'watchthis'                => 'Vixilar esta páxina',
'savearticle'              => 'Grabar páxina',
'preview'                  => 'Previsualizar',
'showpreview'              => 'Previsualizar',
'showdiff'                 => 'Ver les diferencies',
'anoneditwarning'          => "'''Avisu:''' Nun tas identificáu. La to IP va quedar grabada nel historial d'edición d'esta páxina.",
'blockedtitle'             => "L'usuariu ta bloquiáu",
'blockednoreason'          => 'nun se dio razón dala',
'blockedoriginalsource'    => "El códigu fonte de '''$1''' amuésase equí:",
'blockededitsource'        => "El testu de '''les tos ediciones''' en '''$1''' amuésense equí:",
'whitelistedittitle'       => 'Ye necesariu tar identificáu pa poder editar.',
'whitelistedittext'        => 'Tienes que $1 pa editar páxines.',
'whitelistacctitle'        => 'Nun tienes permisu pa facer una cuenta.',
'confirmedittitle'         => 'Requerida la confirmación de corréu electrónicu pa editar',
'confirmedittext'          => "Has confirmar la to direición de corréu electrónicu enantes d'editar páxines. Por favor, configúrala y valídala nes tos [[Special:Preferences|preferencies d'usuariu]].",
'accmailtitle'             => 'Clave unviada.',
'accmailtext'              => 'La clave de "$1" foi unviada a $2.',
'newarticle'               => '(Nuevu)',
'newarticletext'           => 'Siguisti un enllaz a un artículu qu\'inda nun esiste. Pa crealu, empecipia a escribir na caxa d\'equí embaxo. Si llegasti equí por enquivocu, namás tienes que calcar nel botón "atrás" del to navegador.',
'anontalkpagetext'         => "----''Esta ye la páxina de discusión pa un usuariu anónimu qu'inda nun creó una cuenta o que nun la usa. Pola mor d'ello ha usase la direición numérica IP pa identificalu/la. Tala IP pue ser compartida por varios usuarios. Si yes un usuariu anónimu y notes qu'hai comentarios irrelevantes empobinaos pa ti, por favor [[Special:Userlogin|crea una cuenta o rexístrate]] pa evitar futures confusiones con otros usuarios anónimos.''",
'noarticletext'            => "Agora nun hai testu nesta páxina. Pa entamar l'artículu, calca en '''[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} equí]'''.)",
'clearyourcache'           => "'''Nota:''' Llueu de salvar, seique tengas que llimpiar la caché del navegador pa ver los cambeos.
*'''Mozilla / Firefox / Safari:''' caltién ''Shift'' mentes calques en ''Reload'', o calca ''Ctrl-Shift-R'' (''Cmd-Shift-R'' en Apple Mac)
*'''IE:''' caltién ''Ctrl'' mentes calques ''Refresh'', o calca ''Ctrl-F5''
*'''Konqueror:''' calca nel botón ''Reload'', o calca ''F5''
*'''Opera:''' los usuarios d'Opera seique necesiten borrar dafechu'l caché en ''Tools→Preferences''",
'note'                     => '<strong>Nota:</strong>',
'previewnote'              => "<strong>¡Alcuérdate de qu'esto ye sólo una previsualización y entovía nun foi grabada!</strong>",
'session_fail_preview'     => "<strong>¡Sentímoslo muncho! Nun se pudo procesar la to edición porque hebo una perda de datos de la sesión.
Inténtalo otra vuelta. Si nun se t'arregla, intenta salir y volver a rexistrate.</strong>",
'editing'                  => 'Editando $1',
'editinguser'              => "Editando l'usuariu <b>$1</b>",
'editingsection'           => 'Editando $1 (seición)',
'editingcomment'           => 'Editando $1 (comentariu)',
'editconflict'             => "Conflictu d'edición: $1",
'yourtext'                 => 'El to testu',
'editingold'               => "<strong>AVISU: Tas editando una versión vieya d'esta páxina. Si la grabes, los cambios fechos dende esa versión van perdese.</strong>",
'yourdiff'                 => 'Diferencia',
'longpagewarning'          => '<strong>AVISU: Esta páxina tien más de $1 quilobytes; dellos navegadores puen tener problemes editando páxines de 32 ó más kb. Habríes dixebrar la páxina en seiciones más pequeñes.</strong>',
'readonlywarning'          => '<strong>AVISU: La base de datos ta pesllada por mantenimientu,
polo que nun vas poder grabar les tos ediciones agora. Quizá deberías copiar el testu
nun ficheru esternu y volver a intentalu lluéu. </strong>',
'protectedpagewarning'     => '<strong>AVISU: Esta páxina ta protexida pa que sólo los alministradores pueaneditala.</strong>',
'semiprotectedpagewarning' => "'''Nota:''' Esta páxina foi bloqueada pa que nun puean editala namái que los usuarios rexistraos.",
'cascadeprotectedwarning'  => "'''Avisu:''' Esta páxina ta protexida pa que namái los alministradores la puean editar porque ta enxerta {{PLURAL:$1|na siguiente páxina protexida|nes siguientes páxines protexíes}} en cascada:",
'templatesused'            => 'Plantilles usaes nesta páxina:',
'nocreatetitle'            => 'Creación de páxines limitada',
'recreate-deleted-warn'    => "'''Avisu: Tas volviendo a crear una páxina que foi borrada anteriormente.'''

Habríes considerar si ye afechisco siguir editando esta páxina.
Equí tienes el rexistru de borraos d'esta páxina:",

# Account creation failure
'cantcreateaccounttitle' => 'Nun se pue crear la cuenta',
'cantcreateaccount-text' => "La creación de cuentes dende esta direición IP (<b>$1</b>) foi bloquiada por [[Usuariu:$3|$3]].

La razón dada por $3 ye ''$2''",

# History pages
'viewpagelogs'        => "Ver rexistros d'esta páxina",
'nohistory'           => "Nun hay historial d'ediciones pa esta páxina.",
'currentrev'          => 'Revisión actual',
'revisionasof'        => 'Versión de $1',
'previousrevision'    => '←Versión anterior',
'nextrevision'        => 'Siguiente versión→',
'currentrevisionlink' => 'ver la versión actual',
'cur'                 => 'act',
'next'                => 'próximu',
'last'                => 'cab',
'histlegend'          => "Seleción de diferencies: marca los botones de les versiones pa comparar y da-y al <i>enter</i> o al botón d'abaxo.<br />
Lleenda: '''(act)''' = diferencies cola versión actual,
'''(cab)''' = diferencies cola versión anterior, '''m''' = edición menor.",
'deletedrev'          => '[borráu]',
'histfirst'           => 'Primera',
'histlast'            => 'Cabera',

# Diffs
'difference'              => '(Diferencia ente revisiones)',
'lineno'                  => 'Llinia $1:',
'compareselectedversions' => 'Comparar les versiones seleicionaes',
'editundo'                => 'esfacer',
'diff-multi'              => '({{PLURAL:$1|Non amosada una revisión intermedia|Non amosaes $1 revisiones intermedies}}.)',

# Search results
'searchresults'         => 'Resultaos de la busca',
'searchsubtitle'        => "Buscasti '''[[:$1]]'''",
'searchsubtitleinvalid' => "Buscasti '''$1'''",
'noexactmatch'          => "'''Nun esiste l'artículu \"\$1\".''' Pues [[:\$1|crear esti artículu]].",
'prevn'                 => 'previos $1',
'nextn'                 => 'siguientes $1',
'viewprevnext'          => 'Ver ($1) ($2) ($3).',
'showingresults'        => "Ver abaxo {{PLURAL:$3|'''1''' resultau|'''$3''' resultaos}} entamando col #'''$2'''.",
'showingresultsnum'     => "Amosando abaxo los {{PLURAL:$3|'''1''' resultau|'''$3''' resultaos}} entamando con #'''$2'''.",
'powersearch'           => 'Buscar',
'powersearchtext'       => 'Buscar nel espaciu de nomes:<br />
$1<br />
$2 List redirects &nbsp; Buscar $3 $9',

# Preferences page
'preferences'           => 'Preferencies',
'mypreferences'         => 'Les mios preferencies',
'prefsnologintext'      => 'Ties que tar [[Special:Userlogin|identificáu]] pa poder camudar les preferencies.',
'changepassword'        => 'Camudar clave',
'skin'                  => 'Apariencia',
'math'                  => 'Cómo amosar les fórmules',
'dateformat'            => 'Formatu de fecha',
'datedefault'           => 'Ensin preferencia',
'datetime'              => 'Fecha y hora',
'math_unknown_error'    => 'error desconocíu',
'math_unknown_function' => 'función desconocida',
'math_syntax_error'     => 'error de sintaxis',
'prefs-personal'        => 'Datos personales',
'prefs-rc'              => 'Cambeos recientes y entamos',
'prefs-watchlist'       => 'Llista de vixilancia',
'prefs-watchlist-days'  => "Númberu de díes qu'amosar na llista de vixilancia:",
'prefs-watchlist-edits' => "Númberu d'ediciones qu'amosar na llista de vixilancia espandida:",
'prefs-misc'            => 'Varios',
'saveprefs'             => 'Guardar preferencies',
'resetprefs'            => 'Volver a les preferencies por defeutu',
'oldpassword'           => 'Clave vieya',
'newpassword'           => 'Clave nueva',
'retypenew'             => 'Repite nueva clave',
'textboxsize'           => 'Editar',
'rows'                  => 'Files',
'columns'               => 'Columnes:',
'searchresultshead'     => 'Busques',
'resultsperpage'        => "Resultaos p'amosar por páxina",
'contextlines'          => "Llinies p'amosar por resultáu",
'contextchars'          => 'Carauteres de testu per llinia',
'recentchangescount'    => 'Númberu de títulos nos cambeos recientes',
'savedprefs'            => 'Les tos preferencies quedaron grabaes.',
'timezonelegend'        => 'Zona horaria',
'timezonetext'          => 'Introduz la diferencia horaria ente la UTC y la to hora llocal.',
'localtime'             => 'Hora llocal',
'timezoneoffset'        => 'Diferencia¹',
'servertime'            => 'La hora del servidor ye',
'guesstimezone'         => 'Obtener del navegador',
'allowemail'            => 'Dexar a los otros usuarios mandate correos',
'defaultns'             => 'Buscar por defeutu nestos espacios de nome:',
'default'               => 'por defeutu',
'files'                 => 'Archivos',

# User rights
'editusergroup' => "Modificar grupos d'usuarios",

# Groups
'group'            => "Tipu d'usuariu:",
'group-sysop'      => 'Alministradores',
'group-bureaucrat' => 'Burócrates',
'group-all'        => '(toos)',

'group-sysop-member'      => 'Alministrador',
'group-bureaucrat-member' => 'Burócrata',

'grouppage-bot'        => '{{ns:project}}:Bots',
'grouppage-sysop'      => '{{ns:project}}:Alministradores',
'grouppage-bureaucrat' => '{{ns:project}}:Burócrates',

# User rights log
'rightslog'     => "Rexistru de perfil d'usuariu",
'rightslogtext' => "Esti ye un rexistru de los cambeos de los perfiles d'usuariu.",

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|camudamientu|camudamientos}}',
'recentchanges'     => 'Cambeos recientes',
'rcnotefrom'        => 'Abaxo tán los cambeos dende <b>$2</b> (hasta <b>$1</b>).',
'rclistfrom'        => 'Ver los cambeos recientes dende $1',
'rcshowhideminor'   => '$1 ediciones menores',
'rcshowhideliu'     => '$1 usuarios rexistraos',
'rcshowhideanons'   => '$1 usuarios anónimos',
'rcshowhidemine'    => '$1 les mios ediciones',
'rclinks'           => 'Amosar los caberos $1 cambeos nos caberos $2 díes <br />$3',
'diff'              => 'dif',
'hide'              => 'Esconder',
'show'              => 'Amosar',

# Recent changes linked
'recentchangeslinked' => 'Cambeos rellacionaos',

# Upload
'upload'            => 'Xubir imaxe',
'uploadbtn'         => 'Xubir',
'uploadnologin'     => 'Ensin entrar',
'uploadnologintext' => 'Tienes que tar [[Special:Userlogin|identificáu]] pa poder xubir archivos.',
'uploaderror'       => 'Error de xubida',
'uploadlog'         => 'rexistru de xubíes',
'uploadlogpage'     => 'Rexistru de xubíes',
'filedesc'          => 'Comentarios',
'fileuploadsummary' => 'Comentarios:',
'filesource'        => 'Fonte',
'uploadedfiles'     => 'Xubir',
'badfilename'       => 'Nome de la imaxe camudáu a "$1".',
'largefileserver'   => 'Esti archivu ye mayor de lo que permite la configuración del servidor.',
'emptyfile'         => "L'archivu que xubisti paez tar vaciu. Esto podría ser pola mor d'un enquivocu nel nome l'archivu. Por favor, camienta si daveres quies xubir esti archivu.",
'successfulupload'  => 'Xubida correuta',
'uploadedimage'     => 'Xubíu "[[$1]]"',
'sourcefilename'    => 'Nome orixinal',
'destfilename'      => 'Nome final',
'watchthisupload'   => 'Vixilar esta páxina',

'nolicense' => 'Nenguna seleicionada',

# Image list
'imagelist'                 => 'Llista semeyes',
'imagelisttext'             => "Embaxu hai una llista de '''$1''' {{PLURAL:$1|archivu|archivos}} ordenaos $2.",
'ilsubmit'                  => 'Buscar',
'showlast'                  => 'Amosar los últimos $1 archivos ordenaos $2.',
'byname'                    => 'por nome',
'bydate'                    => 'por fecha',
'bysize'                    => 'por tamañu',
'imagelinks'                => 'Enllaces a esta semeya',
'linkstoimage'              => 'Les páxines siguientes enllacien a esti archivu:',
'nolinkstoimage'            => "Nun hai páxines qu'enllacen a esti archivu.",
'noimage'                   => 'Nun tenemos nengún archivu con esi nome, puedes $1.',
'noimage-linktext'          => 'xubilu',
'uploadnewversion-linktext' => "Xubir una nueva versión d'esta imaxe",
'imagelist_date'            => 'Data',
'imagelist_name'            => 'Nome',
'imagelist_description'     => 'Descripción',

# MIME search
'mimesearch' => 'Busca MIME',
'download'   => 'descargar',

# Unwatched pages
'unwatchedpages' => 'Páxines ensin vixilar',

# List redirects
'listredirects' => 'Llista de redireiciones',

# Unused templates
'unusedtemplates' => 'Plantilles ensin usu',

# Random page
'randompage' => 'Páxina al debalu',

# Random redirect
'randomredirect' => 'Redireición aleatoria',

# Statistics
'statistics'    => 'Estadístiques',
'sitestats'     => "Páxina d'estadístiques",
'userstats'     => "Estadístiques d'usuariu",

'disambiguations'      => 'Páxines de dixebra',
'disambiguationspage'  => 'Template:dixebra',
'disambiguations-text' => "Les siguientes p'axines enllacien a una '''páxina de dixebra'''. En cuenta d'ello habríen enllaciar al artículu apropiáu.<br />Una páxina considérase de dixebra si usa una plantilla que tea enllaciada dende [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Redireiciones dobles',
'doubleredirectstext' => 'Esta páxina llista páxines que redireiciones a otres páxines de redireición. Cada filera contién enllaces a la primer y segunda redireición, asina como al oxetivu de la segunda redireición, que normalmente ye la páxina oxetivu "real", aonde la primer redireición habría empobinar.',

'brokenredirects'        => 'Redireiciones rotes',
'brokenredirectstext'    => 'Les siguientes redireiciones enllacen a páxines que nun esisten:',
'brokenredirects-edit'   => '(editar)',
'brokenredirects-delete' => '(borrar)',

'withoutinterwiki' => 'Artículos ensin enllaces interwiki',

'fewestrevisions' => "Artículos col menor númberu d'ediciones",

# Miscellaneous special pages
'ncategories'             => '$1 {{PLURAL:$1|categoría|categoríes}}',
'nlinks'                  => '$1 {{PLURAL:$1|enllaz|enllaces}}',
'nrevisions'              => '$1 {{PLURAL:$1|edición|ediciones}}',
'lonelypages'             => 'Páxines güérfanes',
'uncategorizedpages'      => 'Páxines ensin categorizar',
'uncategorizedcategories' => 'Categoríes güérfanes',
'uncategorizedimages'     => 'Semeyes ensin categorizar',
'uncategorizedtemplates'  => 'Plantilles non categorizaes',
'unusedcategories'        => 'Categoríes ensin usu',
'unusedimages'            => 'Semeyes ensin usar',
'wantedcategories'        => 'Categoríes buscaes',
'wantedpages'             => 'Páxines buscaes',
'mostlinked'              => 'Páxines más enllazaes',
'mostlinkedcategories'    => 'Categoríes más usaes',
'mostcategories'          => 'Páxines con más categoríes',
'mostimages'              => 'Semeyes más usaes',
'mostrevisions'           => 'Páxines con más ediciones',
'allpages'                => 'Toles páxines',
'prefixindex'             => 'Páxines por prefixu',
'shortpages'              => 'Páxines curties',
'longpages'               => 'Páxines llargues',
'deadendpages'            => 'Páxines ensin salida',
'deadendpagestext'        => "Los artículos siguientes nun enllacien a nenguna páxina d'esta uiqui.",
'protectedpages'          => 'Páxines protexíes',
'listusers'               => "Llista d'usuarios",
'specialpages'            => 'Páxines especiales',
'spheading'               => 'Páxines especiales pa tolos usuarios',
'restrictedpheading'      => 'Páxines especiales restrinxíes',
'rclsub'                  => '(a páxines enllazaes dende "$1")',
'newpages'                => 'Páxines nueves',
'newpages-username'       => "Nome d'usuariu:",
'ancientpages'            => 'Páxines más vieyes',
'intl'                    => 'Interuiquis',
'move'                    => 'Treslladar',
'movethispage'            => 'Tresllada esta páxina',
'unusedcategoriestext'    => "Les siguientes categoríes esisten magar que nengún artículu o categoría faiga usu d'elles.",

# Book sources
'booksources'               => 'Fontes de llibros',
'booksources-search-legend' => 'Busca de fontes de llibros',
'booksources-go'            => 'Dir',
'booksources-text'          => "Esta ye una llista d'enllaces a otros sitios que vienden llibros nuevos y usaos, y que puen tener más información sobre llibros que pueas tar guetando:",

'categoriespagetext' => 'Les categoríes que vienen darréu esisten na Uiqui.',
'data'               => 'Datos',
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
'nextpage'          => 'Siguiente páxina ($1)',
'allpagesfrom'      => "Amosar páxines qu'entamen por:",
'allarticles'       => 'Toles páxines',
'allinnamespace'    => 'Toles páxines (espaciu de nome $1)',
'allnotinnamespace' => 'Toles páxines (sacantes les del espaciu de nomes $1)',
'allpagesprev'      => 'Anteriores',
'allpagesnext'      => 'Siguientes',
'allpagessubmit'    => 'Dir',
'allpagesprefix'    => 'Amosar páxines col prefixu:',
'allpagesbadtitle'  => "El títulu dau a esta páxina nun yera válidu o tenía un prefixu d'enlla inter-llingua o inter-wiki. Pue contener ún o más carauteres que nun se puen usar nos títulos.",
'allpages-bad-ns'   => '{{SITENAME}} nun tien l\'espaciu de nomes "$1".',

# Special:Listusers
'listusersfrom' => 'Amosar usuarios emprimando dende:',

# E-mail user
'emailuser'       => 'Manda-y un email a esti usuariu',
'emailpage'       => "Corréu d'usuariu",
'emailpagetext'   => "Si esti usuariu metió una direición de corréu electrónicu válida nes sos preferencies d'usuariu, el formulariu d'embaxu va unviar un mensaxe simple. La direición de corréu electrónicu que metisti nes tos preferencies d'usuariu va apaecer como la direición \"Dende\" del corréu, pa que'l que lo recibe seya quien a responder.",
'defemailsubject' => 'Corréu electrónicu de {{SITENAME}}',
'noemailtitle'    => 'Ensin direición de corréu',
'noemailtext'     => "Esti usuariu nun puso una dirección de corréu válida o nun quier recibir correos d'otros usuarios.",
'emailfrom'       => 'De',
'emailto'         => 'A',
'emailsubject'    => 'Asuntu',
'emailmessage'    => 'Mensaxe',
'emailsend'       => 'Unviar',
'emailsent'       => 'Corréu unviáu',
'emailsenttext'   => 'El to corréu foi unviáu',

# Watchlist
'watchlist'            => 'Llista de vixilancia',
'mywatchlist'          => 'La mio páxina de vixilancia',
'watchlistfor'         => "(pa '''$1''')",
'nowatchlist'          => 'La tu llista de vixilancia ta vacía.',
'watchnologin'         => 'Ensin entrar',
'watchnologintext'     => 'Tienes que tar [[Special:Userlogin|identificáu]] pa poder camudar la to llista de vixilancia.',
'addedwatch'           => 'Añadío a la llista de vixilancia',
'addedwatchtext'       => 'Añadióse la páxina "[[:$1]]" a la to [[Special:Watchlist|llista de vixilancia]]. Los cambeos nesta páxina y la so páxina de discusión asociada van salite en negrina na llista de [[Special:Recentchanges|cambeos recientes]] pa que seya más fácil de vela.

Si más tarde quies quitala de la llista de vixilancia calca en "Dexar de vixilar" nel menú llateral.',
'removedwatch'         => 'Quitar de la llista de vixilancia',
'removedwatchtext'     => 'Quitose la páxina "[[:$1]]" de la to llista de vixilancia.',
'watch'                => 'Vixilar',
'watchthispage'        => 'Vixilar esta páxina',
'unwatch'              => 'dexar de vixilar',
'notanarticle'         => 'Nun ye un artículu',
'watchnochange'        => 'Nenguna de les tus páxines vixilaes foi editada nel periodu elexíu.',
'watchlistcontains'    => 'La to llista de vixilancia tien $1 {{PLURAL:$1|páxina|páxines}}.',
'wlshowlast'           => 'Asoleyar les últimes $1 hores $2 díes $3',
'watchlist-hide-bots'  => 'Esconder ediciones de bots',
'watchlist-hide-own'   => 'Esconder les mis ediciones',
'watchlist-hide-minor' => 'Esconder ediciones menores',

'enotif_reset'       => 'Marcar toles páxines visitaes',
'enotif_newpagetext' => 'Esta ye una páxina nueva.',
'changed'            => 'camudada',
'created'            => 'creada',

# Delete/protect/revert
'deletepage'                  => 'Borrar páxina',
'confirm'                     => 'Confirmar',
'excontent'                   => "el conteníu yera: '$1'",
'excontentauthor'             => "el conteníu yera: '$1' (y l'únicu autor yera '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "el conteníu enantes de dexar en blanco yera: '$1'",
'exblank'                     => 'la páxina taba vacía',
'confirmdelete'               => 'Confirmar el borráu',
'deletesub'                   => '(Borrando "$1")',
'historywarning'              => 'AVISU: La páxina que vas borrar tien un historial:',
'confirmdeletetext'           => "Tas a puntu de borrar pa siempre de la base de datos una páxina o imaxe arriendes del so historial.
Por favor, confirma que ye lo que quies facer, qu'entiendes les consecuencies, y que lo tas faciendo acordies coles [[{{MediaWiki:Policy-url}}|polítiques]].",
'actioncomplete'              => 'Aición completada',
'deletedtext'                 => '"$1" foi borráu.
Mira\'l $2 pa una llista de les últimes coses borraes.',
'deletedarticle'              => 'Borró "[[$1]]"',
'dellogpage'                  => 'Rexistru de borraos',
'dellogpagetext'              => 'Abaxo tán los artículos borraos más recientemente.',
'deletionlog'                 => 'rexistru de borraos',
'deletecomment'               => 'Razón pa borrar',
'rollback_short'              => 'Desaniciar',
'rollbacklink'                => 'Desaniciar',
'cantrollback'                => "Nun se pue revertir la edición; el postrer collaborador ye l'únicu autor d'esta páxina.",
'alreadyrolled'               => 'Nun se pue desaniciar la postrer edición de [[:$1]]
fecha por [[User:$2|$2]] ([[User talk:$2|discusión]]); dalguién más yá editó o revirtió la páxina.

La postrer edición foi fecha por [[User:$3|$3]] ([[User talk:$3|discusión]]).',
'editcomment'                 => 'El resume de la edición yera: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Revertíes les ediciones de [[Special:Contributions/$2|$2]] ([[User talk:$2|discusión]]) hasta la versión de [[User:$1|$1]]',
'sessionfailure'              => 'Paez qu\'hai un problema cola tu sesión; por precaución cancelose l\'acción que pidiste. Da-y al botón de "Atrás" nel navegador y vuelve a intentalu.',
'protectlogpage'              => 'Rexistru de protecciones',
'protectlogtext'              => 'Esti ye un rexistru de les páxines protexíes y desprotexíes. Consulta la [[Special:Protectedpages|llista de páxines protexíes]] pa ver les proteiciones actives actualmente.',
'protectedarticle'            => 'protexó $1',
'unprotectedarticle'          => 'desprotexió "[[$1]]"',
'protectsub'                  => '(Protexendo "$1")',
'confirmprotect'              => 'Confirma proteición',
'protectcomment'              => 'Razón pa protexer',
'unprotectsub'                => '(Desprotexiendo "$1")',
'protect-unchain'             => 'Camudar los permisos pa tresllados',
'protect-text'                => 'Equí pues ver y camudar el nivel de protección de <strong>$1</strong>.',
'protect-default'             => '(por defeutu)',
'protect-level-autoconfirmed' => 'Bloquear usuarios ensin rexistrar',
'protect-level-sysop'         => 'Sólu alministradores',

# Restrictions (nouns)
'restriction-edit' => 'Editar',
'restriction-move' => 'Treslladar',

# Undelete
'undelete'                 => 'Ver páxines borraes',
'undeletepage'             => 'Ver y restaurar páxines borraes',
'undeletepagetext'         => "Les siguientes páxines foron borraes pero inda tan nel archivu y puen ser restauraes. L'archivu pue ser purgáu periódicamente.",
'undeleteextrahelp'        => "Pa restaurar tola páxina, deseleiciona toles casielles y calca en '''''Restaurar'''''. Pa realizar una restauración selectiva, seleiciona les casielles de la revisión que se quier restaura y calca en '''''Restaurar'''''. Calcando en '''''Llimpiar''''' quedarán vacios el campu de comentarios y toles casielles.",
'undeleterevisions'        => '$1 {{PLURAL:$1|revision|revisiones}} archivaes',
'undeletehistory'          => 'Si restaures la páxina, restauraránse toles revisiones del historial. Si foi creada una páxina col mesmu nome dende que fuera borrada, les revisiones restauraes van apaecer nel historial anterior, y la revisión actual de la páxina activa nun sedrá sustituyida automáticamente.',
'undeletehistorynoadmin'   => "Esti artículu foi borráu. La razón de borralu amuésase nel resumen d'embaxo, amás de detalles de los usuarios qu'editaron esta páxina enantes de ser borrada. El testu actual d'estes revisiones borraes ta disponible namái pa los alministradores.",
'undeletebtn'              => 'Restaurar',
'undeletereset'            => 'Llimpiar',
'undeletecomment'          => 'Comentariu:',
'undeletedarticle'         => 'restauróse "[[$1]]"',
'undeletedrevisions'       => '{{PLURAL:$1|1 revision|$1 revisiones}} restauraes',
'undeletedrevisions-files' => '{{PLURAL:$1|1 revision|$1 revisiones}} y {{PLURAL:$2|1 archivu|$2 archivos}} restauraos',
'undeletedfiles'           => '{{PLURAL:$1|1 archivu|$1 archivos}} restauraos',
'cannotundelete'           => 'Falló la restauración; seique daquién yá restaurara la páxina enantes.',

# Namespace form on various pages
'namespace'      => 'Espaciu de nomes:',
'invert'         => 'Invertir seleción',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribuciones del usuariu',
'mycontris'     => 'Les mios contribuciones',
'contribsub2'   => 'De $1 ($2)',
'nocontribs'    => "Nun s'atoparon cambeos que coincidan con esi criteriu.",
'uclinks'       => 'Ver los caberos $1 cambeos; ver los caberos $2 díes.',
'uctop'         => ' (últimu cambeu)',

'sp-contributions-blocklog' => 'Rexistru de bloqueos',

# What links here
'whatlinkshere' => "Lo qu'enllaza equí",
'linklistsub'   => "(Llista d'enllaces)",
'linkshere'     => "Les páxines siguientes enllacien en '''[[:$1]]''':",
'nolinkshere'   => "Nenguna páxina enllaza en '''[[:$1]]'''.",
'isredirect'    => 'Redireicionar páxina',

# Block/unblock
'blockip'                     => 'Bloquiar usuariu',
'blockiptext'                 => "Usa'l siguiente formulariu pa bloquear el permisu d'escritura a una IP o a un usuariu concretu.
Esto debería facese sólo pa prevenir vandalismu como indiquen les [[{{MediaWiki:Policy-url}}|polítiques]]. Da una razón específica (como por exemplu citar páxines que fueron vandalizaes).",
'ipaddress'                   => 'Dirección IP',
'ipadressorusername'          => "Dirección IP o nome d'usuariu",
'ipbexpiry'                   => 'Tiempu',
'ipbreason'                   => 'Razón',
'ipbreasonotherlist'          => 'Otra razón',
'ipbreason-dropdown'          => "*Razones comunes de bloquéu
** Enxertamientu d'información falso
** Dexar les páxines en blanco
** Enllaces spam a páxines esternes
** Enxertamientu de babayaes/enguedeyos nes páxines
** Comportamientu intimidatoriu o d'acosu
** Abusu de cuentes múltiples
** Nome d'usuariu inaceutable",
'ipbanononly'                 => 'Bloquear namái usuarios anónimos',
'ipbcreateaccount'            => 'Evitar creación de cuentes',
'ipbenableautoblock'          => "Bloquiar automáticamente la cabera direición IP usada por esti usuariu y toles IP posteriores dende les que s'intente editar",
'ipbsubmit'                   => 'Bloquear esti usuariu',
'ipbother'                    => 'Otru periodu',
'ipboptions'                  => '15 minutos:15 minutes,1 hora:1 hour,2 hores:2 hours,1 día:1 day,2 díes:2 days,1 selmana:1 week,1 mes:1 month,pa siempre:infinite',
'ipbotheroption'              => 'otru',
'ipbotherreason'              => 'Otra razón/razón adicional',
'badipaddress'                => 'IP non válida',
'blockipsuccesssub'           => 'Bloquéu fechu correctamente',
'blockipsuccesstext'          => "Bloquióse al usuariu [[Special:Contributions/$1|$1]].
<br />Mira na [[Special:Ipblocklist|llista d'IPs bloquiaes]] pa revisar los bloqueos.",
'unblockip'                   => 'Desbloquear usuariu',
'ipusubmit'                   => 'Desbloquear esta dirección',
'unblocked'                   => '[[User:$1|$1]] foi desbloqueáu',
'ipblocklist'                 => "Llista de direcciones IP y nomes d'usuarios bloqueaos",
'blocklistline'               => '$1, $2 bloquió a $3 ($4)',
'infiniteblock'               => 'pa siempre',
'expiringblock'               => 'hasta $1',
'anononlyblock'               => 'namái anón.',
'noautoblockblock'            => 'bloquéu automáticu desactiváu',
'createaccountblock'          => 'bloqueada creación de cuentes',
'emailblock'                  => 'corréu electrónicu bloquiáu',
'blocklink'                   => 'bloquiar',
'unblocklink'                 => 'desbloquear',
'contribslink'                => 'contribuciones',
'autoblocker'                 => 'Bloquiáu automáticamente porque la to direición IP foi usada recién por "[[Usuariu:$1|$1]]". La razón del bloquéu de $1 ye: "$2"',
'blocklogpage'                => 'Rexistru de bloqueos',
'blocklogentry'               => '"[[$1]]" $3 foi bloquiáu $2',
'blocklogtext'                => "Esti ye un rexistru de los bloqueos y desbloqueos d'usuarios. Les direcciones IP
bloquiaes automáticamente nun salen equí. Pa ver los bloqueos qu'hai agora mesmo, 
mira na [[Special:Ipblocklist|llista d'IP bloquiaes]].",
'unblocklogentry'             => '$1 desbloqueáu',
'block-log-flags-anononly'    => 'namái usuarios anónimos',
'block-log-flags-nocreate'    => 'creación de cuentes deshabilitada',
'block-log-flags-noautoblock' => 'bloquéu automáticu deshabilitáu',
'block-log-flags-noemail'     => 'corréu electrónicu bloquiáu',
'ipb_expiry_invalid'          => 'Tiempu incorrectu.',
'ipb_already_blocked'         => '"$1" yá ta bloqueáu',
'ip_range_invalid'            => 'Rangu IP non válidu.',
'blockme'                     => 'Blóquiame',
'sorbsreason'                 => 'La to dirección IP sal na llista de proxys abiertos en DNSBL.',
'sorbs_create_account_reason' => 'La to dirección IP sal na llista de proxys abiertos en DNSBL. Nun pues crear una cuenta.',

# Developer tools
'lockconfirm'       => 'Si, daveres quiero protexer la base de datos.',
'lockbtn'           => 'Protexer base de datos',
'databasenotlocked' => 'La base de datos nun ta bloquiada.',

# Move page
'movepage'                => 'Treslladar páxina',
'movepagetext'            => "Usando'l siguiente formulariu vas renomar una páxina, treslladando'l so historial al nuevu nome. El nome vieyu va convertise nuna redireición al nuevu. Los enllaces qu'hubiere al nome vieyu nun van camudase, asegúrate de que nun dexes redireiciones dobles o rotes. Tu yes el responsable de facer que los enllaces queden apuntando aonde se supón que han apuntar. Recuerda que la páxina '''nun''' se va mover si yá hai una páxina col nuevu títulu, a nun ser que seya una páxina vacia o una redireición que nun tenga historial. Esto significa que pues volver a renomar una páxina col nome orixinal si t'enquivoques, y que nun pues sobreescribir una páxina yá esistente. <b>¡AVISU!</b> Esti pue ser un cambéu importante y inesperáu pa una páxina popular; por favor, asegúrate d'entender les consecuencies de lo que vas facer.",
'movepagetalktext'        => "La so páxina de discusión asociada va ser treslladada automáticamente '''a nun ser que:'''
*Yá esista una páxina de discusión non vacia col nuevu nome, o
*Desactives el caxellu d'equí baxo.

Nestos casos vas tener que treslladar o fusionar la páxina manualmente.",
'movearticle'             => 'Treslladar la páxina',
'movenologin'             => 'Ensin entrar',
'movenologintext'         => 'Ties que ser un usuariu rexistráu y tar [[Special:Userlogin|identificáu]] pa poder treslladar una páxina.',
'newtitle'                => 'Al nuevu nome:',
'move-watch'              => 'Vixilar esta páxina',
'movepagebtn'             => 'Treslladar páxina',
'pagemovedsub'            => 'Treslláu correctu',
'movepage-moved'          => '<big>\'\'\'"$1" treslladáu a "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Yá hai una páxina con esi nome, o'l nome qu'escoyisti nun ye válidu. Por favor, elixi otru nome.",
'movedto'                 => 'treslladáu a',
'movetalk'                => 'Mover tamién la páxina de discusión si ye posible.',
'talkpagemoved'           => 'La páxina de discusión tamíen fue treslladá.',
'talkpagenotmoved'        => 'La páxina de discusión <strong>nun</strong> fue treslladada.',
'1movedto2'               => '[[$1]] treslladáu a [[$2]]',
'1movedto2_redir'         => '[[$1]] treslladáu a [[$2]] sobre una redireición',
'movelogpage'             => 'Rexistru de tresllaos',
'movelogpagetext'         => 'Esta ye la llista de páxines treslladaes.',
'movereason'              => 'Razón',
'revertmove'              => 'Desfacer',
'delete_and_move'         => 'Borrar y mover',
'delete_and_move_text'    => "==Necesítase borrar==

L'artículu de destín «[[$1]]» yá esiste. ¿Quies borralu pa dexar sitiu pal treslláu?",
'delete_and_move_confirm' => 'Sí, borrar la páxina',
'delete_and_move_reason'  => 'Borrada pa facer sitiu pal treslláu',
'selfmove'                => "Los nomes d'orixen y destinu son los mesmos, nun se pue treslladar una páxina sobre ella mesma.",
'immobile_namespace'      => "El títulu de la fonte o'l destín ye d'una triba especial; nun se puen mover páxines dende nin a este nome d'espaciu.",

# Export
'export'        => 'Esportar páxines',
'exporttext'    => "Pues esportar el testu y l'historial d'ediciones una páxina en particular o d'una riestra páxines nun documentu XML. Ésti pue ser importáu depués n'otra wiki qu'use MediaWiki al traviés de la páxina [[Special:Importar]] páxina.

Pa esportar páxines, pon los títulos na caxa de testu d'embaxo, un títulu per llinia, y seleiciona si quies la versión actual xunto con toles versiones antigües col so historial, o namái la versión actual cola información de la última edición.

Por último, tamién pues usar un enllaz: p.e. [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] pa la páxina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly' => 'Amestar namái la revisión actual, non tol historial',
'export-submit' => 'Esportar',

# Namespace 8 related
'allmessages'               => 'Tolos mensaxes del sistema',
'allmessagesname'           => 'Nome',
'allmessagesdefault'        => 'Testu por defeutu',
'allmessagescurrent'        => 'Testu actual',
'allmessagestext'           => 'Esta ye una llista de tolos mensaxes disponibles nel sistema de MediaWiki.',
'allmessagesnotsupportedDB' => "Nun pue usase '''{{ns:special}}:Allmessages''' porque '''\$wgUseDatabaseMessages''' ta deshabilitáu.",
'allmessagesfilter'         => 'Filtru pal nome del mensax:',
'allmessagesmodified'       => 'Amosar solo modificaos',

# Thumbnails
'thumbnail-more'  => 'Enllargar',
'missingimage'    => '<b>Falta la imaxe</b><br /><i>$1</i>',
'djvu_page_error' => 'Páxina DjVu fuera de llímites',
'djvu_no_xml'     => 'Nun se pudo obtener el XML pal archivu DjVu',

# Special:Import
'import' => 'Importar páxines',

# Import log
'importlogpage' => "Rexistru d'importaciones",

# Tooltip help for the actions
'tooltip-minoredit' => 'Marca los cambeos como una edición menor',
'tooltip-save'      => 'Guarda los tos cambeos',
'tooltip-preview'   => 'Previsualiza los tos cambeos. ¡Por favor, úsalo enantes de grabar!',
'tooltip-diff'      => 'Amuesa los cambeos que fixisti nel testu.',
'tooltip-watch'     => 'Amiesta esta páxina na to llista de vixilancia',

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
'subcategorycount'     => 'Hai {{PLURAL:$1|una subcategoría|$1 subcategoríes}} nesta categoría.',
'categoryarticlecount' => 'Hai {{PLURAL:$1|un artículu|$1 artículos}} nesta categoría.',
'category-media-count' => 'Hai {{PLURAL:$1|un archivu|$1 archivos}} nesta categoría.',

# Info page
'numedits' => "Númberu d'ediciones (artículu): $1",

# Math options
'mw_math_png'    => 'Facer siempre PNG',
'mw_math_simple' => 'HTML si ye mui simple, si non, PNG',
'mw_math_html'   => 'HTML si ye posible, si non PNG',
'mw_math_source' => 'Dexalo como TeX',
'mw_math_modern' => 'Recomendao pa navegadores modernos',
'mw_math_mathml' => 'MathML si ye posible (esperimental)',

# Patrol log
'patrol-log-page' => 'Rexistru de supervisión',

# Image deletion
'deletedrevision' => 'Borrada versión vieya $1.',

# Browsing diffs
'previousdiff' => '← Dir a la diferencia anterior',
'nextdiff'     => 'Dir a la siguiente diferencia →',

# Media information
'imagemaxsize'         => 'Llendar les imáxenes nes páxines de descripción a:',
'thumbsize'            => 'Tamañu de la muestra:',
'file-info'            => "(tamañu d'archivu: $1, triba MIME: $2)",
'file-info-size'       => "($1 × $2 píxeles, tamañu d'archivu: $3, triba MIME: $4)",
'file-nohires'         => '<small>Nun ta disponible con mayor resolución.</small>',
'show-big-image'       => 'Resolución completa',
'show-big-image-thumb' => "<small>Tamañu d'esta previsualización: $1 × $2 píxeles</small>",

# Special:Newimages
'newimages' => "Galería d'archivos nuevos",

# Bad image list
'bad_image_list' => "El formatu ye'l que sigue:

Namái se tienen en cuenta les llinies qu'emprimen por un *. El primer enllaz d'una llinia ha ser ún qu'enllace a una imaxe mala.
Los demás enllaces de la mesma llinia considérense esceiciones, p.ex. artículos nos que la imaxe ha apaecer.",

# EXIF tags
'exif-artist'                 => 'Autor',
'exif-compressedbitsperpixel' => "Mou de compresión d'imaxe",
'exif-brightnessvalue'        => 'Brillu',
'exif-cfapattern'             => 'patrón CFA',
'exif-contrast'               => 'Contraste',

# EXIF attributes
'exif-compression-1' => 'Non comprimida',

'exif-componentsconfiguration-0' => 'nun esiste',

# External editor support
'edit-externally'      => 'Editar esti ficheru usando una aplicación externa',
'edit-externally-help' => 'Pa más información echa un güeyu a les [http://meta.wikimedia.org/wiki/Help:External_editors instrucciones de configuración].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Toos',
'namespacesall'    => 'toos',

# E-mail address confirmation
'confirmemail'            => 'Confirmar direición de corréu',
'confirmemail_noemail'    => "Nun tienes una direición de corréu válida nes tos [[Special:Preferences|preferencies d'usuariu]].",
'confirmemail_text'       => 'Nesta wiki ye necesariu que valides la to dirección de corréu pa poder usala. Da-y al botón que tienes equí abaxo pa unviar la confirmación. 

Va mandásete un corréu con un códigu de confirmación. Tienes que da-y al enllaz pa confirmalu.',
'confirmemail_pending'    => '<div class="error">
Yá s\'unvió un códigu de confirmación a la to direición de corréu; si creasti hai poco la to cuenta, pues esperar dellos minutos a que-y de tiempu a llegar enantes de pidir otru códigu nuevu.
</div>',
'confirmemail_send'       => 'Unviar códigu de confirmación',
'confirmemail_sent'       => 'Corréu de confirmación unviáu.',
'confirmemail_oncreate'   => "Unvióse un códigu de confirmación a la to direición de corréu.
Esti códigu nun se necesita pa identificase, pero tendrás que lu conseñar enantes
d'activar cualesquier funcionalidá de la wiki que tea rellacionada col corréu.",
'confirmemail_sendfailed' => 'Nun se pudo unviar el corréu de comprobación. Revisa que nun pusieras caracteres inválidos na dirección de corréu.

Mailer returned: $1',
'confirmemail_invalid'    => 'Códigu de confirmación non válidu. El códigu seique tenga caducao.',
'confirmemail_needlogin'  => 'Tienes que $1 pa confirmar el to corréu.',
'confirmemail_success'    => 'El to corréu ya ta confimáu. Agora pues rexistrate y collaborar.',
'confirmemail_loggedin'   => 'Confirmóse la to direición de corréu.',
'confirmemail_error'      => 'Hebo un problema al guardar la to confirmación.',
'confirmemail_subject'    => 'Confirmación de la dirección de corréu de {{SITENAME}}',
'confirmemail_body'       => 'Daquién, seique tu dende la IP $1, rexistró la cuenta "$2" con esta direición de corréu en {{SITENAME}}.

Pa confirmar qu\'esta cuenta ye tuya daveres y asina activar les opciones de corréu en {{SITENAME}}, abri esti enllaz nel to navegador:

$3

Si esti nun yes tú, nun abras l\'enllaz. Esti códigu de confirmación caduca en $4.',

# Delete conflict
'deletedwhileediting' => "¡Avisu: Esta páxina foi borrada depués de qu'entamaras a editala!",
'confirmrecreate'     => "L'usuariu [[User:$1|$1]] ([[User talk:$1|discusión]]) borró esta páxina depués de qu'empecipiaras a editale pola siguiente razón:
: ''$2''
Por favor confirma que daveres quies volver a crear esta páxina.",

# action=purge
'confirm_purge'        => "¿Llimpiar la caché d'esta páxina?

$1",
'confirm_purge_button' => 'Aceutar',

# AJAX search
'articletitles' => "Artículos entamando por ''$1''",

# Table pager
'table_pager_first' => 'Primer páxina',
'table_pager_last'  => 'Postrer páxina',

# Auto-summaries
'autosumm-blank'   => "Eliminando'l conteníu de la páxina",
'autosumm-replace' => "Sustituyendo la páxina por '$1'",
'autoredircomment' => 'Redirixendo a [[$1]]',
'autosumm-new'     => 'Páxina nueva: $1',

# Watchlist editing tools
'watchlisttools-edit' => 'Ver y editar la llista de vixilancia',

);
