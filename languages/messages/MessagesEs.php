<?php
/** Spanish (Español)
  *
  * @addtogroup Language
  */

$skinNames = array(
	'standard' => 'Estándar',
);
$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Especial',
	NS_MAIN           => '',
	NS_TALK           => 'Discusión',
	NS_USER           => 'Usuario',
	NS_USER_TALK      => 'Usuario_Discusión',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_Discusión',
	NS_IMAGE          => 'Imagen',
	NS_IMAGE_TALK     => 'Imagen_Discusión',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_Discusión',
	NS_TEMPLATE       => 'Plantilla',
	NS_TEMPLATE_TALK  => 'Plantilla_Discusión',
	NS_HELP           => 'Ayuda',
	NS_HELP_TALK      => 'Ayuda_Discusión',
	NS_CATEGORY       => 'Categoría',
	NS_CATEGORY_TALK  => 'Categoría_Discusión',
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i j M Y',
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-záéíóúñ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Subrayar enlaces',
'tog-highlightbroken'         => 'Destacar enlaces a artículos vacíos <a href="" class="new">como este</a> (alternativa: como éste<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Ajustar párrafos',
'tog-hideminor'               => 'Ocultar ediciones menores en «cambios recientes»',
'tog-extendwatchlist'         => 'Expandir la lista de seguimiento a todos los cambios aplicables',
'tog-usenewrc'                => 'Cambios recientes realzados (no funciona en todos los navegadores)',
'tog-numberheadings'          => 'Numerar automáticamente los encabezados',
'tog-showtoolbar'             => 'Mostrar la barra de edición',
'tog-editondblclick'          => 'Editar páginas con doble click (JavaScript)',
'tog-editsection'             => 'Habilitar la edición de secciones usando el enlace [editar]',
'tog-editsectiononrightclick' => 'Habilitar la edición de secciones presionando el botón de la derecha<br /> en los títulos de secciones (JavaScript)',
'tog-showtoc'                 => 'Mostrar la tabla de contenidos (para paginas con más de 3 encabezados)',
'tog-rememberpassword'        => 'Recordar la contraseña entre sesiones',
'tog-editwidth'               => 'La caja de edición tiene el ancho máximo',
'tog-watchcreations'          => 'Vigilar las páginas que yo cree.',
'tog-watchdefault'            => 'Vigilar las páginas que yo modifique',
'tog-watchmoves'              => 'Vigilar las páginas que renombre',
'tog-watchdeletion'           => 'Vigilar las páginas que borre',
'tog-minordefault'            => 'Marcar todas las ediciones como menores por defecto',
'tog-previewontop'            => 'Mostrar la previsualización antes de la caja de edición en lugar de después',
'tog-previewonfirst'          => 'Mostrar previsualización al comenzar a editar',
'tog-nocache'                 => "Inhabilitar la ''caché'' de páginas",
'tog-enotifwatchlistpages'    => 'Enviame un correo cuando haya cambios en una página vigilada',
'tog-enotifusertalkpages'     => 'Notifícame cuando cambia mi página de discusión de usuario',
'tog-enotifminoredits'        => 'Notifícame también los cambios menores de página',
'tog-enotifrevealaddr'        => 'Revela mi dirección electrónica en los correos de notificación',
'tog-shownumberswatching'     => 'Mostrar el número de usuarios que la vigilan',
'tog-fancysig'                => 'Firma sin enlace automático',
'tog-externaleditor'          => 'Utilizar editor externo por defecto',
'tog-externaldiff'            => "Utilizar ''diff'' externo por defecto",
'tog-showjumplinks'           => 'Habilitar enlaces de accesibilidad «saltar a»',
'tog-uselivepreview'          => 'Usar live preview (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Alertar al grabar sin resumen de edición.',
'tog-watchlisthideown'        => 'Ocultar mis ediciones en la lista de seguimiento',
'tog-watchlisthidebots'       => 'Ocultar ediciones de bots en la lista de seguimiento',
'tog-watchlisthideminor'      => 'Ocultar ediciones menores en la lista de seguimiento',
'tog-nolangconversion'        => 'Deshabilitar conversión de lenguajes',
'tog-ccmeonemails'            => 'Recibir copias de los correos que envío a otros usuarios',
'tog-diffonly'                => 'No mostrar el contenido de la página bajo las diferencias',

'underline-always'  => 'Siempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Valor por defecto del navegador',

'skinpreview' => '(Ver cómo queda)',

# Dates
'sunday'        => 'Domingo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miércoles',
'thursday'      => 'Jueves',
'friday'        => 'Viernes',
'saturday'      => 'Sábado',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mie',
'thu'           => 'jue',
'fri'           => 'vie',
'sat'           => 'sab',
'january'       => 'enero',
'february'      => 'febrero',
'march'         => 'marzo',
'april'         => 'abril',
'may_long'      => 'mayo',
'june'          => 'junio',
'july'          => 'julio',
'august'        => 'agosto',
'september'     => 'septiembre',
'october'       => 'octubre',
'november'      => 'noviembre',
'december'      => 'diciembre',
'january-gen'   => 'enero',
'february-gen'  => 'febrero',
'march-gen'     => 'marzo',
'april-gen'     => 'abril',
'may-gen'       => 'mayo',
'june-gen'      => 'junio',
'july-gen'      => 'julio',
'august-gen'    => 'agosto',
'september-gen' => 'septiembre',
'october-gen'   => 'octubre',
'november-gen'  => 'noviembre',
'december-gen'  => 'diciembre',
'jan'           => 'ene',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'abr',
'may'           => 'may',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'ago',
'sep'           => 'sep',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'dic',

# Bits of text used by many pages
'categories'            => 'Categorías',
'pagecategories'        => '{{PLURAL:$1|Categoría|Categorías}}',
'category_header'       => 'Artículos en la categoría "$1"',
'subcategories'         => 'Subcategorías',
'category-media-header' => 'Archivos en la categoría "$1"',

'mainpagetext'      => 'Software wiki instalado con éxito.',
'mainpagedocfooter' => "Por favor, lee [http://meta.wikimedia.org/wiki/MediaWiki_i18n documentation on customizing the interface] y [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] para conocer su configuración y uso.",

'about'          => 'Acerca de',
'article'        => 'Artículo',
'newwindow'      => '(Se abre en una ventana nueva)',
'cancel'         => 'Cancelar',
'qbfind'         => 'Buscar',
'qbbrowse'       => 'Hojear',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Opciones de página',
'qbpageinfo'     => 'Información de página',
'qbmyoptions'    => 'Mis opciones',
'qbspecialpages' => 'Páginas especiales',
'moredotdotdot'  => 'Más...',
'mypage'         => 'Mi página',
'mytalk'         => 'Mi discusión',
'anontalk'       => 'Discusión para esta IP',
'navigation'     => 'Navegación',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Error',
'returnto'          => 'Volver a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ayuda',
'search'            => 'Buscar',
'searchbutton'      => 'Buscar',
'go'                => 'Ir',
'searcharticle'     => 'Ir',
'history'           => 'Historial',
'history_short'     => 'Historial',
'updatedmarker'     => 'actualizado desde mi última visita',
'info_short'        => 'Información',
'printableversion'  => 'Versión para imprimir',
'permalink'         => 'Enlace permanente',
'print'             => 'Imprimir',
'edit'              => 'Editar',
'editthispage'      => 'Editar esta página',
'delete'            => 'Borrar',
'deletethispage'    => 'Borrar esta página',
'undelete_short'    => 'Restaurar {{PLURAL:$1|una edición|$1 ediciones}}',
'protect'           => 'Proteger',
'protect_change'    => 'cambiar protección',
'protectthispage'   => 'Proteger esta página',
'unprotect'         => 'Desproteger',
'unprotectthispage' => 'Desproteger esta página',
'newpage'           => 'Página nueva',
'talkpage'          => 'Discutir esta página',
'talkpagelinktext'  => 'Discutir',
'specialpage'       => 'Página Especial',
'personaltools'     => 'Herramientas personales',
'postcomment'       => 'Poner un comentario',
'articlepage'       => 'Ver artículo',
'talk'              => 'Discusión',
'views'             => 'Vistas',
'toolbox'           => 'Herramientas',
'userpage'          => 'Ver página de usuario',
'projectpage'       => 'Ver página meta',
'imagepage'         => 'Ver página de imagen',
'mediawikipage'     => 'Ver página de mensaje',
'templatepage'      => 'Ver página de plantilla',
'viewhelppage'      => 'Ver página de ayuda',
'categorypage'      => 'Ver página de categoría',
'viewtalkpage'      => 'Ver discusión',
'otherlanguages'    => 'Otros idiomas',
'redirectedfrom'    => '(Redirigido desde $1)',
'redirectpagesub'   => 'Página redirigida',
'lastmodifiedat'    => 'Esta página fue modificada por última vez el $2, $1.', # $1 date, $2 time
'viewcount'         => 'Esta página ha sido visitada {{PLURAL:$1|una vez|$1 veces}}.',
'protectedpage'     => 'Página protegida',
'jumpto'            => 'Saltar a',
'jumptonavigation'  => 'navegación',
'jumptosearch'      => 'búsqueda',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Acerca de {{SITENAME}}',
'aboutpage'         => 'Project:Acerca de',
'bugreports'        => 'Informes de error de software',
'bugreportspage'    => 'Project:Informes de error',
'copyright'         => 'El contenido está disponible bajo los términos de la <i>$1</i>',
'copyrightpagename' => 'Copyright de {{SITENAME}}',
'copyrightpage'     => 'Project:Copyrights',
'currentevents'     => 'Actualidad',
'currentevents-url' => 'Actualidad',
'disclaimers'       => 'Aviso legal',
'disclaimerpage'    => 'Project:Limitación general de responsabilidad',
'edithelp'          => 'Ayuda de edición',
'edithelppage'      => 'Help:Cómo se edita una página',
'faq'               => 'FAQ',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Project:Ayuda',
'mainpage'          => 'Portada',
'policy-url'        => 'Project:Políticas',
'portal'            => 'Portal de la comunidad',
'portal-url'        => 'Project:Portal de la comunidad',
'privacy'           => 'Política de protección de datos',
'privacypage'       => 'Project:Política de protección de datos',
'sitesupport'       => 'Donaciones',
'sitesupport-url'   => 'Project:Apoyo al proyecto',

'badaccess'        => 'Error de permisos',
'badaccess-group0' => 'No está autorizado a ejecutar la acción que ha solicitado.',
'badaccess-group1' => 'La acción que ha solicitado está restringida a los usuarios de uno de estos grupos: $1.',
'badaccess-group2' => 'La acción que ha solicitado está restringida a los usuarios de uno de estos grupos: $1.',
'badaccess-groups' => 'La acción que ha solicitado está restringida a los usuarios de uno de estos grupos: $1.',

'versionrequired'     => 'La versión $1 de MediaWiki es necesaria para utilizar esta página',
'versionrequiredtext' => 'Se necesita la versión $1 de MediaWiki para utilizar esta página. Para más información, consulte [[Special:Version]]',

'ok'                  => 'OK',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => 'Obtenido de "$1"',
'youhavenewmessages'  => 'Tiene $1 ($2).',
'newmessageslink'     => 'mensajes nuevos',
'newmessagesdifflink' => 'dif. entre las dos últimas versiones',
'editsection'         => 'editar',
'editold'             => 'editar',
'editsectionhint'     => 'Editar sección: $1',
'toc'                 => 'Tabla de contenidos',
'showtoc'             => 'mostrar',
'hidetoc'             => 'ocultar',
'thisisdeleted'       => '¿Ver o restaurar $1?',
'viewdeleted'         => '¿Desea ver $1?',
'restorelink'         => '{{PLURAL:$1|una edición borrada|$1 ediciones borradas}}',
'feedlinks'           => 'Sindicación:',
'feed-invalid'        => 'Tipo de subscripción a sindicación de noticias inválida.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artículo',
'nstab-user'      => 'Usuario',
'nstab-media'     => 'Media',
'nstab-special'   => 'Especial',
'nstab-project'   => 'Página del proyecto',
'nstab-image'     => 'Imagen',
'nstab-mediawiki' => 'Mensaje',
'nstab-template'  => 'Plantilla',
'nstab-help'      => 'Ayuda',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'No existe tal acción',
'nosuchactiontext'  => 'La acción especificada en la dirección no es válida en {{SITENAME}}',
'nosuchspecialpage' => 'No existe esa página especial',
'nospecialpagetext' => 'Ha requerido una página especial que no existe en {{SITENAME}}.',

# General errors
'error'                => 'Error',
'databaseerror'        => 'Error de la base de datos',
'dberrortext'          => 'Ha ocurrido un error de sintaxis en una consulta a la base de datos.
Esto puede indicar un error en el software.
La última consulta que se intentó fue: <blockquote><tt>$1</tt></blockquote> dentro de la función "<tt>$2</tt>". El error devuelto por la base de datos fue"<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ha ocurrido un error de sintaxis en una consulta a la base de datos. La última consulta que se intentó fue:
"$1" 
desde la función "$2".
MySQL devolvió el error "$3: $4".',
'noconnect'            => 'No se pudo conectar a la base de datos en $1',
'nodb'                 => 'No se pudo seleccionar la base de datos $1',
'cachederror'          => 'Esta es una copia guardada en el caché de la página requerida, y puede no estar actualizada.',
'laggedslavemode'      => 'Aviso: puede que falten las actualizaciones mas recientes en esta página.',
'readonly'             => 'Base de datos bloqueada',
'enterlockreason'      => 'Explique el motivo del bloqueo, incluyendo una estimación de cuándo se producirá el desbloqueo',
'readonlytext'         => 'La base de datos de {{SITENAME}} no permite nuevas entradas u otras modificaciones de forma temporal, probablemente por mantenimiento rutinario, tras de lo cual volverá a la normalidad.
La explicación dada por el administrador que la bloqueó fue:
<p>$1',
'missingarticle'       => 'La base de datos no encontró el texto de una página que debería haber encontrado, llamada "$1".

Generalmente esto se debe a enlaces a diferencias entre página o historiales obsoletos de una página borrada.

Si este no es el motivo, puede que se trate de un error en el software. En tal caso, informe de ello a un administrador incluyendo la URL que provocó el error.',
'readonly_lag'         => 'La base de datos se ha bloqueado temporalmente mientras los servidores se sincronizan.',
'internalerror'        => 'Error interno',
'filecopyerror'        => 'No se pudo copiar el archivo "$1" a "$2".',
'filerenameerror'      => 'No se pudo renombrar el archivo "$1" a "$2".',
'filedeleteerror'      => 'No se pudo borrar el archivo "$1".',
'filenotfound'         => 'No se pudo encontrar el archivo "$1".',
'unexpected'           => 'Valor inesperado: "$1"="$2".',
'formerror'            => 'Error: no se pudo enviar el formulario',
'badarticleerror'      => 'Esta acción no se puede llevar a cabo en esta página.',
'cannotdelete'         => 'No se pudo borrar la página o imagen especificada. (Puede haber sido borrada por alguien antes)',
'badtitle'             => 'Título incorrecto',
'badtitletext'         => 'El título de la página solicitada esta vacío, es inválido, o es un enlace interlenguaje o interwiki incorrecto.',
'perfdisabled'         => 'Lo siento, esta función está deshabilitada temporalmente porque ralentiza la base de datos, haciendo el wiki inusable.',
'perfcached'           => 'Los siguientes datos están en caché y por tanto pueden estar desactualizados:',
'perfcachedts'         => 'Estos datos están almacenados. Su última actualización fue el $1.',
'querypage-no-updates' => 'Actualmente están deshabilitadas las actualizaciones para esta página. Sus datos no se refrescarán.',
'wrong_wfQuery_params' => 'Parámetros incorrectos para wfQuery()<br />
Funcción: $1<br />
Consulta: $2',
'viewsource'           => 'Ver código fuente',
'viewsourcefor'        => 'para $1',
'protectedpagetext'    => 'Esta página ha sido bloqueada para prevenir ediciones.',
'viewsourcetext'       => 'Puede ver y copiar el fuente de esta página:',
'protectedinterface'   => 'Esta página provee texto del interfaz del software. Está bloqueada para evitar [[{{ns:project}}:vandalismo|vandalismos]]. Si cree que debería cambiarse el texto, hable con un [[{{MediaWiki:grouppage-sysop}}|Administrador]].',
'editinginterface'     => "'''Aviso:''' Estás editando una página usada para proporcionar texto a la interfaz de {{SITENAME}}. Los cambios en esta página afectarán a la apariencia de la interfaz para los demás usuarios.",
'sqlhidden'            => '(Consulta SQL oculta)',
'cascadeprotected'     => 'Esta página está protegida contra ediciones al estar incluída en {{PLURAL:$1|la siguiente página|las siguientes páginas}} protegidas en cascada:',

# Login and logout pages
'logouttitle'                => 'Fin de sesión',
'logouttext'                 => 'Ha terminado su sesión.
Puede continuar navegando por {{SITENAME}} de forma anónima, o puede iniciar sesión otra vez con el mismo u otro usuario.',
'welcomecreation'            => '== ¡Bienvenido(a), $1! ==

Su cuenta ha sido creada. No olvide personalizar [[Special:Preferences|sus preferencias]] de {{SITENAME}}.',
'loginpagetitle'             => 'Registrarse/Entrar',
'yourname'                   => 'Su nombre de usuario',
'yourpassword'               => 'Su contraseña',
'yourpasswordagain'          => 'Repita su contraseña',
'remembermypassword'         => 'Quiero que me recuerden entre sesiones.',
'yourdomainname'             => 'Su dominio',
'externaldberror'            => 'Hubo un error de autenticación externa de la base de datos o bien no está autorizado a actualizar su cuenta externa.',
'loginproblem'               => '<b>Hubo un problema con su autenticación.</b><br />¡Inténtelo otra vez!',
'alreadyloggedin'            => '<strong>Usuario $1, ¡ya está autenticado!</strong><br />',
'login'                      => 'Registrarse/Entrar',
'loginprompt'                => 'Necesita habilitar las <i>cookies</i> en su navegador para registrarse en {{SITENAME}}.',
'userlogin'                  => 'Registrarse/Entrar',
'logout'                     => 'Salir',
'userlogout'                 => 'Salir',
'notloggedin'                => 'No ha entrado',
'nologin'                    => '¿No tiene una cuenta? $1.',
'nologinlink'                => 'Créela',
'createaccount'              => 'Cree una nueva cuenta',
'gotaccount'                 => '¿Ya tiene una cuenta? $1.',
'gotaccountlink'             => 'Autenticarse',
'createaccountmail'          => 'por correo',
'badretype'                  => 'Las contraseñas no coinciden.',
'userexists'                 => 'El nombre indicado ya está en uso. Por favor, indique un nombre diferente.',
'youremail'                  => 'Su dirección de correo electrónico',
'username'                   => 'Nombre de usuario:',
'uid'                        => 'ID de usuario:',
'yourrealname'               => 'Su nombre real *',
'yourlanguage'               => 'Idioma:',
'yourvariant'                => 'Variante lingüística',
'yournick'                   => 'Su apodo (para firmas)',
'badsig'                     => 'Firma en crudo inválida; compruebe las etiquetas HTML.',
'email'                      => 'Correo electrónico',
'prefs-help-realname'        => '* Nombre real (opcional): si opta por proporcionarlo, se usará para dar atribución a su trabajo.',
'loginerror'                 => 'Error de inicio de sesión',
'prefs-help-email'           => '* Correo (opcional): Permite a otros usuarios escribirle por correo desde su página de usuario o su página de discusión sin la necesidad de revelar su identidad.',
'nocookiesnew'               => 'La cuenta de usuario ha sido creada, pero ahora mismo no está identificado. {{SITENAME}} usa <em>cookies</em> para identificar a los usuarios registrados, pero parecen deshabilitadas. Por favor, habilítelas e identifíquese con su nombre de usuario y contraseña.',
'nocookieslogin'             => '{{SITENAME}} utiliza <em>cookies</em> para la autenticación de usuarios. Tiene las <em>cookies</em> deshabilitadas en el navegador. Por favor, actívelas e inténtelo de nuevo.',
'noname'                     => 'No ha especificado un nombre de usuario válido.',
'loginsuccesstitle'          => 'Inicio de sesión exitoso',
'loginsuccess'               => 'Ha iniciado su sesión en {{SITENAME}} como "$1".',
'nosuchuser'                 => 'No existe usuario alguno llamado "$1".
Compruebe que lo ha escrito correctamente, o use el formulario de abajo para crear una nueva cuenta de usuario.',
'nosuchusershort'            => 'No hay un usuario con el nombre "$1". Compruebe que lo ha escrito correctamente.',
'nouserspecified'            => 'Debes especificar un nombre de usuario.',
'wrongpassword'              => 'La contraseña indicada es incorrecta. Por favor, inténtelo de nuevo.',
'wrongpasswordempty'         => 'No ha escrito una contraseña, inténtelo de nuevo.',
'mailmypassword'             => 'Envíame una nueva contraseña por correo electrónico',
'passwordremindertitle'      => 'Recordatorio de contraseña de {{SITENAME}}',
'passwordremindertext'       => 'Alguien (probablemente usted, desde la dirección IP $1) solicitó que le enviáramos una nueva contraseña para iniciar sesión en {{SITENAME}} ($4). La contraseña para el usuario "$2" es ahora "$3". Ahora debería iniciar sesión y cambiar su contraseña.

Si fue otra persona quien solicitó este mensaje o ha recordado su contraseña y ya no desea cambiarla, puede ignorar este mensaje y seguir usando su contraseña original.',
'noemail'                    => 'No hay una dirección de correo electrónico registrada para "$1".',
'passwordsent'               => 'Una nueva contraseña ha sido enviada al correo electrónico de "$1".
Por favor, identifíquese de nuevo tras recibirla.',
'blocked-mailpassword'       => 'La edición está bloqueada desde su dirección IP, por lo que no se le permite utilizar la función de recuperación de contraseña para prevenir abusos.',
'eauthentsent'               => 'Un correo electrónico de confirmación ha sido enviado a la dirección especificada. Antes de que se envie algún otro correo, siga las instrucciones enviadas en el mensaje para confirmar que la dirección le pertenece.',
'throttled-mailpassword'     => 'Ya se le ha enviado un recordatorio de contraseña en las últimas $1 horas. Para prevenir abusos, sólo se enviará uno de estos recordatorios cada $1 horas.',
'mailerror'                  => 'Error al enviar correo: $1',
'acct_creation_throttle_hit' => 'Lo sentimos, ya ha creado $1 cuentas. No puede crear otra.',
'emailauthenticated'         => 'Su dirección electrónica fue verificada en $1.',
'emailnotauthenticated'      => 'Aún no ha confirmado su dirección de correo electrónico. Hasta que no lo haga, las siguientes funciones no estarán disponibles.',
'noemailprefs'               => '<strong>Especifique una dirección electrónica para habilitar estas características.</strong>',
'emailconfirmlink'           => 'Confirme su dirección de correo electrónico',
'invalidemailaddress'        => 'La dirección electrónica no se puede aceptar pues parece que tiene un formato incorrecto. Por favor, escriba una dirección bien formada o vacíe el campo.',
'accountcreated'             => 'Cuenta creada',
'accountcreatedtext'         => 'La cuenta de usuario para $1 ha sido creada.',

# Password reset dialog
'resetpass'               => 'Restaurar la contraseña',
'resetpass_announce'      => 'Se identificó con un código temporal enviado por correo. Para terminar de autenticarse, debe poner una nueva contraseña aquí:',
'resetpass_text'          => '<!-- Añada texto aquí -->',
'resetpass_header'        => 'Restaurar contraseña',
'resetpass_submit'        => 'Cambiar la contraseña e identificarse',
'resetpass_success'       => 'Se ha cambiado su contraseña. Autenticándole...',
'resetpass_bad_temporary' => 'La contraseña temporal no es válida. Quizás ya la ha cambiado o ha pedido una nueva.',
'resetpass_forbidden'     => 'En este wiki no se pueden cambiar las contraseñas',
'resetpass_missing'       => 'No hay datos en el formulario.',

# Edit page toolbar
'bold_sample'     => 'Texto en negrita',
'bold_tip'        => 'Texto en negrita',
'italic_sample'   => 'Texto en cursiva',
'italic_tip'      => 'Texto en cursiva',
'link_sample'     => 'Título del enlace',
'link_tip'        => 'Enlace interno',
'extlink_sample'  => 'http://www.ejemplo.com Título del enlace',
'extlink_tip'     => 'Enlace externo (recuerde añadir el prefijo http://)',
'headline_sample' => 'Texto de titular',
'headline_tip'    => 'Titular de nivel 2',
'math_sample'     => 'Escriba aquí una fórmula',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Inserte aquí texto sin formato',
'nowiki_tip'      => 'Pasar por alto el formato wiki',
'image_sample'    => 'Ejemplo.jpg',
'image_tip'       => 'Imagen incorporada',
'media_sample'    => 'Ejemplo.ogg',
'media_tip'       => 'Enlace a archivo multimedia',
'sig_tip'         => 'Firma, fecha y hora',
'hr_tip'          => 'Línea horizontal (utilícela con moderación)',

# Edit pages
'summary'                   => 'Resumen',
'subject'                   => 'Tema/título',
'minoredit'                 => 'Esta es una edición menor',
'watchthis'                 => 'Vigilar este artículo',
'savearticle'               => 'Grabar la página',
'preview'                   => 'Previsualizar',
'showpreview'               => 'Mostrar previsualización',
'showlivepreview'           => 'Live preview',
'showdiff'                  => 'Mostrar cambios',
'anoneditwarning'           => 'No ha introducido su nombre de usuario. Su dirección IP se guardará en el historial de edición de la página.',
'missingsummary'            => "'''Atención:''' No has escrito un resumen de edición. Si haces clic nuevamente en «{{MediaWiki:Savearticle}}» tu edición se grabará sin él.",
'missingcommenttext'        => 'Por favor introduce texto debajo.',
'missingcommentheader'      => "'''Recordatorio:''' No ha escrito un asunto o titular para este comentario. Si vuelve a pulsar Guardar, su edición se guardará sin titular.",
'summary-preview'           => 'Previsualización del resumen',
'subject-preview'           => 'Previsualización del asunto o titular',
'blockedtitle'              => 'El usuario está bloqueado',
'blockedtext'               => '<big>\'\'\'Su nombre de usuario o dirección IP ha sido bloqueada por $1.\'\'\'</big>
El bloqueo fue realizado por $1 por el siguiente motivo: $2.

Contacte con $1 u otro de los [[{{MediaWiki:grouppage-sysop}}|administradores]] si quiere discutir el bloqueo. Éste expirará en $6.

No podrá usar el enlace "enviar correo electrónico a este usuario" si no ha registrado una dirección válida de correo electrónico en sus [[Special:Preferences|preferencias]]. Su dirección IP es $3 y el identificador de bloqueo #$5. Por favor, incluya esta información en cualquier consulta que haga.',
'autoblockedtext'           => 'Su dirección IP ha sido bloqueada automáticamente porque ha sido utilizada por otro usuario, que fue bloqueado por $1.
El motivo es el siguiente:

:\'\'$2\'\'

Expiración del bloqueo: $6

Quizás quiera contactar con $1 o algún otro [[{{MediaWiki:grouppage-sysop}}|administrador]] para hablar sobre el bloqueo.

No debería usar la funcionalidad de "enviar un correo a este usuario" a no ser que tenga registrada una dirección de correo electrónico válida en sus [[Special:Preferences|preferencias]].

Your block ID is $5. Please include this ID in any queries you make.',
'blockedoriginalsource'     => "El código fuente de '''$1''' se muestra a continuación:",
'blockededitsource'         => "El texto de '''tus ediciones''' a '''$1''' se muestran a continuación:",
'whitelistedittitle'        => 'Se requiere identificación para editar.',
'whitelistedittext'         => 'Tiene que $1 para editar artículos.',
'whitelistreadtitle'        => 'Se requiere identificación para leer',
'whitelistreadtext'         => 'Tiene que [[Special:Userlogin|registrarse]] para leer artículos.',
'whitelistacctitle'         => 'No se le permite crear una cuenta',
'whitelistacctext'          => 'Para que se le permita crear cuentas en este wiki tiene que [[Special:Userlogin|iniciar sesión]] y tener los permisos apropiados.',
'confirmedittitle'          => 'Se requiere confirmación de dirección electrónica para editar',
'confirmedittext'           => 'Debes confirmar tu dirección electrónica antes de editar páginas. Por favor, establece y valida una dirección electrónica a través de tus [[Special:Preferences|preferencias de usuario]].',
'nosuchsectiontitle'        => 'No existe esa sección',
'nosuchsectiontext'         => 'Ha intentado editar una sección que no existe. Dado que no existe la sección $1, no hay dónde guardar su edición.',
'loginreqtitle'             => 'Se requiere identificación',
'loginreqlink'              => 'identificarse',
'loginreqpagetext'          => 'Debe $1 para ver otras páginas.',
'accmailtitle'              => 'La contraseña ha sido enviada.',
'accmailtext'               => 'La contraseña para «$1» se ha enviado a $2.',
'newarticle'                => '(Nuevo)',
'newarticletext'            => 'Ha seguido un enlace a una página que aún no existe. Si lo que quiere es crear esta página, escriba a continuación. Para más información consulte la [[{{MediaWiki:helppage}}|página de ayuda]]. Si llegó aquí por error, vuelva a la página anterior.',
'anontalkpagetext'          => "---- ''Esta es la página de discusión para un usuario anónimo que aún no ha creado una cuenta (o no la usa). Por lo tanto, tenemos que usar su dirección IP para identificarlo. Una dirección IP puede ser compartida por varios usuarios. Si es un usuario anónimo y cree que le han dirigido comentarios que no corresponden, por favor [[Special:Userlogin|cree una cuenta o identifíquese]] para evitar confusiones futuras con otros usuarios anónimos.''",
'noarticletext'             => '(En este momento no hay texto en esta página)',
'clearyourcache'            => "'''Nota:''' Tras guardar el archivo, debe refrescar la caché de su navegador para ver los cambios:
*'''Mozilla:'''  ''ctrl-shift-r'',
*'''Internet Explorer:''' ''ctrl-f5'',
*'''Safari:''' ''cmd-shift-r'',
*'''Konqueror''' ''f5''.",
'usercssjsyoucanpreview'    => '<strong>Consejo:</strong> Use el botón «Mostrar previsualización» para probar su nuevo css/js antes de grabarlo.',
'usercsspreview'            => "'''¡Recuerde que sólo está previsualizando su css de usuario y aún no se ha grabado!'''",
'userjspreview'             => "'''¡Recuerde que sólo está previsualizando su javascript de usuario y aún no se ha grabado!'''",
'userinvalidcssjstitle'     => "'''Aviso:''' No existe la piel \"\$1\". Recuerda que las páginas personalizadas .css y .js tienen un título en minúsculas, p.e. Usuario:Foo/monobook.css en vez de  Usuario:Foo/Monobook.css.",
'updated'                   => '(Actualizado)',
'note'                      => '<strong>Nota:</strong>',
'previewnote'               => '¡Recuerde que esto es sólo una previsualización y aún no se ha grabado!',
'previewconflict'           => 'La previsualización le muestra cómo aparecerá el texto una vez guardados los cambios.',
'session_fail_preview'      => '<strong>Lo sentimos, no pudimos efectuar su edición debido a una pérdida de los datos de sesión. Por favor, inténtelo de nuevo y si no funciona, salga de su sesión y vuelva a identificarse.</strong>',
'session_fail_preview_html' => "<strong>Lo sentimos, no hemos podido procesar tu cambio debido a una pérdida de datos de sesión.</strong>

''Puesto que este wiki tiene el HTML puro habilitado, la visión preliminar está oculta para prevenirse contra ataques en JavaScript.''

<strong>If this is a legitimate edit attempt, please try again. If it still doesn't work, try logging out and logging back in.</strong>",
'importing'                 => 'Importando $1',
'editing'                   => 'Editando $1',
'editinguser'               => 'Editando $1',
'editingsection'            => 'Editando $1 (sección)',
'editingcomment'            => 'Editando $1 (comentario)',
'editconflict'              => 'Conflicto de edición: $1',
'explainconflict'           => 'Alguien ha cambiado esta página desde que empezó a editarla.
El área de texto superior contiene el texto de la página tal cual es actualmente. Sus cambios se muestran en el área de texto inferior.
Va a tener que incorporar sus cambios en el texto existente.
<b>Sólo</b> el texto en el área superior se grabará.<br />',
'yourtext'                  => 'Su texto',
'storedversion'             => 'Versión almacenada',
'nonunicodebrowser'         => '<strong>Atención: Su navegador no cumple la norma Unicode. Se ha activado un sistema de edición alternativo que le permitirá editar artículos con seguridad: los caracteres no ASCII aparecerán en la caja de edición como códigos hexadecimales.</strong>',
'editingold'                => '<strong>Atención: Está editando una versión antigua de esta página. Si la guarda, los cambios hechos desde esa revisión se perderán.</strong>',
'yourdiff'                  => 'Diferencias',
'copyrightwarning'          => 'Por favor, tenga en cuenta que todas las contribuciones a {{SITENAME}} se consideran hechas públicas bajo la $2 (ver detalles en $1). Si no desea que la gente corrija sus escritos sin piedad y los distribuya libremente, entonces no los ponga aquí. Así mismo, usted es responsable de haber escrito este texto o haberlo copiado del dominio público u otra fuente libre. <strong>¡NO USE ESCRITOS CON COPYRIGHT SIN PERMISO!</strong>',
'copyrightwarning2'         => 'Por favor, tenga en cuenta que todas las contribuciones a {{SITENAME}} pueden ser editadas, modificadas o eliminadas por otros colaboradores. Si no desea que la gente corrija sus escritos sin piedad y los distribuya libremente, entonces no los ponga aquí. <br />Así mismo, usted es responsable de haber escrito este texto o haberlo copiado del dominio público u otra fuente libre (vea $1 para más detalles). <strong>¡NO USE ESCRITOS CON COPYRIGHT SIN PERMISO!</strong>',
'longpagewarning'           => '<strong>Atención: Esta página tiene un tamaño de $1 kilobytes; algunos navegadores pueden tener problemas editando páginas de 32kb o más.
Por favor considere la posibilidad de descomponer esta página en secciones más pequeñas.</strong>',
'longpageerror'             => '<strong>ERROR: El testo que has enviado ocupa $1 kilobytes, lo cual es mayor que $2 kilobytes. No se puede guardar.</strong>',
'readonlywarning'           => '<strong>Atención: La base de datos ha sido bloqueada por cuestiones de mantenimiento, así que no podrá guardar sus modificaciones en este momento.
Puede copiar y pegar el texto a un archivo en su ordenador y grabarlo para más tarde.</strong>',
'protectedpagewarning'      => '<strong>Atención: Esta página ha sido protegida de forma que sólo usuarios con permisos de administrador pueden editarla. Asegúrese de que está siguiendo las [[Project:Políticas de bloqueo de páginas|Políticas de bloqueo de páginas]].</strong>
__NOEDITSECTION__<h3>La edición de esta página está [[Project:Esta página está protegida|protegida]].</h3>
* Puede discutir este bloqueo en la [[{{TALKPAGENAME}}|página de discusión]] del artículo.<br />',
'semiprotectedpagewarning'  => "'''Nota:''' Esta página ha sido protegida para que sólo usuarios registrados puedan editarla.",
'cascadeprotectedwarning'   => "'''Atención:''' Esta página ha sido bloqueada de forma que sólo los administradores pueden editarla, al estar incluída en {{PLURAL:$1|la siguiente página|las siguientes páginas}} protegidas en cascada:",
'templatesused'             => 'Plantillas usadas en esta página:',
'templatesusedpreview'      => 'Plantillas usadas en esta previsualización:',
'templatesusedsection'      => 'Plantillas usadas en esta sección:',
'template-protected'        => '(protegido)',
'template-semiprotected'    => '(protegido parcialmente)',
'edittools'                 => '<!-- Este texto aparecerá bajo los formularios de edición y subida. -->',
'nocreatetitle'             => 'Creación de páginas limitada',
'nocreatetext'              => 'Este wiki ha restringido la posibilidad de crear nuevas páginas. Puede volver atrás y editar una página existente, [[Special:Userlogin|identificarse o crear una cuenta]].',
'recreate-deleted-warn'     => "'''Atención: está creando una página que ha sido borrada previamente.'''

Debería considerar si es apropiado continuar editando esta página.
Consulte a continuación el registro de borrados:",

# "Undo" feature
'undo-success' => 'La edición se puede deshacer. Por favor, compruebe las diferencias más abajo para verificar que esto es lo que quiere hacer y después guarde los cambios para terminar de deshacer la edición.',
'undo-failure' => 'La edición no se pudo deshacer debido a un conflicto con ediciones intermedias.',
'undo-summary' => 'Deshecha la revisión $1 por [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]])',

# Account creation failure
'cantcreateaccounttitle' => 'No se puede crear la cuenta',
'cantcreateaccounttext'  => 'La creación de cuentas desde esta dirección IP (<b>$1</b>) ha sido bloqueada. 
Esto se debe probablemente a vandalismos persistentes desde tu escuela o tu proveedor de servicios de Internet.',

# History pages
'revhistory'          => 'Historial de revisiones',
'viewpagelogs'        => 'Ver los registros de esta página',
'nohistory'           => 'No hay un historial de ediciones para esta página.',
'revnotfound'         => 'Revisión no encontrada',
'revnotfoundtext'     => 'No se pudo encontrar la revisión antigua de la página que ha solicitado.
Por favor, revise la dirección que usó para acceder a esta página.',
'loadhist'            => 'Recuperando el historial de la página',
'currentrev'          => 'Revisión actual',
'revisionasof'        => 'Revisión de $1',
'revision-info'       => 'Revisión a fecha de $1; $2',
'previousrevision'    => '← Revisión anterior',
'nextrevision'        => 'Revisión siguiente →',
'currentrevisionlink' => 'Ver revisión actual',
'cur'                 => 'act',
'next'                => 'sig',
'last'                => 'prev',
'orig'                => 'orig',
'page_first'          => 'primera',
'page_last'           => 'última',
'histlegend'          => 'Leyenda: (act) = diferencias con la versión actual,
(prev) = diferencias con la versión previa, M = edición menor',
'deletedrev'          => '[borrado]',
'histfirst'           => 'Primeras',
'histlast'            => 'Últimas',
'historyempty'        => '(vacío)',

# Revision feed
'history-feed-title'          => 'Historial de revisiones',
'history-feed-description'    => 'Historial de revisiones para esta página en el wiki',
'history-feed-item-nocomment' => '$1 en $2', # user at time
'history-feed-empty'          => 'La página solicitada no existe.
Puede haber sido borrada del wiki o renombrada.
Prueba a [[Special:Search|buscar en el wiki]] nuevas páginas relevantes.',

# Revision deletion
'rev-deleted-comment'         => '(comentario eliminado)',
'rev-deleted-user'            => '(nombre de usuario eliminado)',
'rev-deleted-event'           => '(entrada borrada)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Esta revisión de la página ha sido eliminada de los archivos públicos.
puede haber detalles en el [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registro de borrado].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Esta revisión de la página ha sido eliminada de los archivos públicos.
Como administrador de este wiki puedes verla;
puede haber detalles en el [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registro de borrado].
</div>',
'rev-delundel'                => 'mostrar/ocultar',
'revisiondelete'              => 'Borrar/deshacer borrado revisiones',
'revdelete-nooldid-title'     => 'No hay revisión destino',
'revdelete-nooldid-text'      => 'No se ha especificado una revisión o revisiones destino sobre las que realizar esta función.',
'revdelete-selected'          => '{{PLURAL:$2|Revisión seleccionada|Revisiones seleccionadas}} de [[:$1]]:',
'logdelete-selected'          => "{{PLURAL:$2|Seleccionado un evento|Seleccionados $2 eventos}} de registro para '''$1:'''",
'revdelete-text'              => 'Las revisiones borradas aún aparecerán en el historial de la página,
pero sus contenidos no serán accesibles al público.

Otros administradores de este wiki aún podrán acceder al contenido oculto y podrán deshacer el borrado a través de la misma interfaz, a menos los operadores del sitio establezcan una restricción adicional.',
'revdelete-legend'            => 'Establecer restricciones de revisión:',
'revdelete-hide-text'         => 'Ocultar el texto de la revisión',
'revdelete-hide-name'         => 'Ocultar acción y objetivo',
'revdelete-hide-comment'      => 'Ocultar comentario de edición',
'revdelete-hide-user'         => 'Ocultar el nombre/IP del editor',
'revdelete-hide-restricted'   => 'Aplicar estas restricciones a los administradores tal como al resto',
'revdelete-suppress'          => 'Eliminar datos de los administradores tal como al resto',
'revdelete-hide-image'        => 'Ocultar contenido del fichero',
'revdelete-unsuppress'        => 'Eliminar restricciones de revisiones restauradas',
'revdelete-log'               => 'Comentario de registro:',
'revdelete-submit'            => 'Aplicar a la revisión seleccionada',
'revdelete-logentry'          => 'cambiada la visibilidad de la revisión para [[$1]]',
'logdelete-logentry'          => 'cambiada la visibilidad de eventos de [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|revisión|revisiones}} en modo $2',
'logdelete-logaction'         => '$1 {{PLURAL:$1|evento|eventos}} a [[$3]] en modo $2',
'revdelete-success'           => 'Visibilidad de revisiones cambiada correctamente.',
'logdelete-success'           => 'Visibilidad de eventos cambiada correctamente.',

# Oversight log
'oversightlog'    => 'Registro de descuidos',
'overlogpagetext' => 'A continuación se muestra una lista de los borrados y bloqueos más recientes relacionados con contenidos ocultos de los operadores del sistema. Consulte la [[Special:Ipblocklist|lista de IPs bloqueadas]] para ver una lista de los bloqueos actuales.',

# Diffs
'difference'                => '(Diferencias entre revisiones)',
'loadingrev'                => 'recuperando revisión para diff',
'lineno'                    => 'Línea $1:',
'editcurrent'               => 'Edite la versión actual de esta página',
'selectnewerversionfordiff' => 'Seleccione una versión más reciente para comparar',
'selectolderversionfordiff' => 'Seleccione una versión más antigua para comparar',
'compareselectedversions'   => 'Comparar versiones seleccionadas',
'editundo'                  => 'deshacer',
'diff-multi'                => '(No se {{PLURAL:$1|muestra una revisión intermedia|muestran $1 revisiones intermedias}}.)',

# Search results
'searchresults'         => 'Resultados de la búsqueda',
'searchresulttext'      => 'Para más información acerca de las búsquedas en {{SITENAME}}, consulte la [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Ha consultado por '''[[:$1]]'''",
'searchsubtitleinvalid' => 'Para consulta "$1"',
'badquery'              => 'Formato incorrecto de la consulta de búsqueda',
'badquerytext'          => 'No se pudo procesar su búsqueda.
Probablemente intentó buscar una palabra de menos de tres letras, lo que todavía no es posible.
También puede ser que haya cometido un error de escritura.
Por favor, intente una nueva búsqueda.',
'matchtotals'           => 'La consulta "$1" coincidió con $2 títulos de artículos y el texto de $3 artículos.',
'noexactmatch'          => '<div style="border: 1px solid #ccc; padding: 7px;"><div style="background: #F9F9F9; padding: 7px">
<div style="font-size:115%"><b>No existe ningún artículo con el título que has escrito.</b></div>
<hr />
<ul>
<li>Posibles causas:
<ul>
<li>Puede que lo hayas <b>tecleado mal</b> o con alguna <b>falta de ortografía</b>. Comprueba el texto (recuerda que mayúsculas y acentos afectan a la búsqueda) o consulta [[{{ns:project}}:Búsqueda]]<!-- /a -->. </li>
<li>Puede que el artículo que buscas <b>tenga otro título</b>. Prueba a repetir tu búsqueda utilizando el botón "Búsqueda" de más arriba.
</li>
</ul>
</li>

<li>
Ten en cuenta que {{SITENAME}} es un wiki en desarrollo que va siendo construido poco a poco por sus visitantes. Si el artículo que buscas aún no existe, puedes crearlo siguiendo <b>[[$1|este enlace]]</b>. Puede que así otra gente vea el artículo y trate de completarlo.
</li>
</ul></div>
<div style="font-size:90%; padding-left: 7px">
<b>Muy importante:</b> en {{SITENAME}} <b>no se aceptan en ningún caso</b> textos con copyright sin el permiso explícito de sus autores. En particular, la mayoría de las páginas web (indiquen o no su autor o copyright) tienen copyright, por lo que su contenido es inadmisible aquí. Ten en cuenta que copiar este tipo de materiales <b>puede causar serios daños al proyecto</b>. Para más información, puedes leer <b>[[{{MediaWiki:Copyrightpage}}]]</b>
</div>
</div>',
'titlematches'          => 'Coincidencias de título de artículo',
'notitlematches'        => 'No hay coincidencias de título de artículo',
'textmatches'           => 'Coincidencias de texto de artículo',
'notextmatches'         => 'No hay coincidencias de texto de artículo',
'prevn'                 => '$1 previos',
'nextn'                 => '$1 siguientes',
'viewprevnext'          => 'Ver ($1) ($2) ($3).',
'showingresults'        => 'Abajo se muestran hasta <b>$1</b> resultados empezando por el número <b>$2</b>.',
'showingresultsnum'     => 'Abajo se muestran los <b>$3</b> resultados empezando por el número <b>$2</b>.',
'nonefound'             => '<strong>Nota</strong>: habitualmente las búsquedas no funcionan al preguntar por palabras comunes como "la" o "de" que no están en el índice, o al especificar más de una palabra (sólo las páginas que contengan todos los términos de una búsqueda aparecerán en el resultado).',
'powersearch'           => 'Búsqueda',
'powersearchtext'       => '
Buscar en espacio de nombres:<br />
$1<br />
$2 Listar redirecciones   Buscar $3 $9',
'searchdisabled'        => 'Las búsquedas en {{SITENAME}} está temporalmente deshabilitadas. Mientras tanto puede buscar mediante buscadores externos, pero tenga en cuenta que sus índices relativos a {{SITENAME}} pueden estar desactualizados.',
'blanknamespace'        => '(Principal)',

# Preferences page
'preferences'              => 'Preferencias',
'mypreferences'            => 'Mis preferencias',
'prefsnologin'             => 'No está identificado',
'prefsnologintext'         => 'Debe [[Special:Userlogin|identificarse]] para cambiar las preferencias de usuario.',
'prefsreset'               => 'Las preferencias han sido restauradas a los valores por defecto.',
'qbsettings'               => 'Preferencias de "Quickbar"',
'qbsettings-none'          => 'Ninguna',
'qbsettings-fixedleft'     => 'Fija a la izquierda',
'qbsettings-fixedright'    => 'Fija a la derecha',
'qbsettings-floatingleft'  => 'Flotante a la izquierda',
'qbsettings-floatingright' => 'Flotante a la derecha',
'changepassword'           => 'Cambiar la contraseña',
'skin'                     => 'Apariencia',
'math'                     => 'Fórmulas',
'dateformat'               => 'Formato de fecha',
'datedefault'              => 'Sin preferencia',
'datetime'                 => 'Fecha y hora',
'math_failure'             => 'No se pudo entender',
'math_unknown_error'       => 'error desconocido',
'math_unknown_function'    => 'función desconocida',
'math_lexing_error'        => 'error léxico',
'math_syntax_error'        => 'error de sintaxis',
'math_image_error'         => 'La conversión a PNG ha sido errónea',
'math_bad_tmpdir'          => 'No se puede escribir o crear el directorio temporal de <em>math</em>',
'math_bad_output'          => 'No se puede escribir o crear el directorio de salida de <em>math</em>',
'math_notexvc'             => 'Falta el ejecutalbe de <strong>texvc</strong>. Por favor, lea <em>math/README</em> para configurarlo.',
'prefs-personal'           => 'Datos personales',
'prefs-rc'                 => 'Cambios recientes',
'prefs-watchlist'          => 'Seguimiento',
'prefs-watchlist-days'     => 'Número de días a mostrar en la lista de seguimiento:',
'prefs-watchlist-edits'    => 'Número de ediciones a mostrar en la lista extendida:',
'prefs-misc'               => 'Miscelánea',
'saveprefs'                => 'Grabar preferencias',
'resetprefs'               => 'Restaurar preferencias por defecto',
'oldpassword'              => 'Contraseña antigua:',
'newpassword'              => 'Contraseña nueva:',
'retypenew'                => 'Confirme la nueva contraseña:',
'textboxsize'              => 'Edición',
'rows'                     => 'Filas:',
'columns'                  => 'Columnas:',
'searchresultshead'        => 'Búsquedas',
'resultsperpage'           => 'Número de resultados por página',
'contextlines'             => 'Número de líneas de contexto por resultado',
'contextchars'             => 'Caracteres de contexto por línea',
'recentchangesdays'        => 'Número de días en cambios recientes:',
'recentchangescount'       => 'Número de títulos en cambios recientes',
'savedprefs'               => 'Sus preferencias han sido grabadas.',
'timezonelegend'           => 'Huso horario',
'timezonetext'             => 'Indique el número de horas de diferencia entre su hora local y la hora del servidor (UTC).',
'localtime'                => 'Hora local',
'timezoneoffset'           => 'Diferencia',
'servertime'               => 'La hora en el servidor es',
'guesstimezone'            => 'Obtener la hora del navegador',
'allowemail'               => 'Habilitar la recepción de correo de otros usuarios',
'defaultns'                => 'Buscar en estos espacios de nombres por defecto:',
'default'                  => 'por defecto',
'files'                    => 'Archivos',

# User rights
'userrights-lookup-user'     => 'Configurar grupos de usuarios',
'userrights-user-editname'   => 'Escriba un nombre de usuario:',
'editusergroup'              => 'Modificar grupos de usuarios',
'userrights-editusergroup'   => 'Modificar grupos de usuarios',
'saveusergroups'             => 'Guardar grupos de usuarios',
'userrights-groupsmember'    => 'Miembro de:',
'userrights-groupsavailable' => 'Grupos disponibles:',
'userrights-groupshelp'      => 'Seleccione los grupos a los que quiere añadir al usuario (o de los que le quiere dar de baja).
Los grupos no seleccionados no cambiarán. Puede deseleccionar pulsando la tecla CTRL',
'userrights-reason'          => 'Motivo del cambio:',

# Groups
'group'            => 'Grupo:',
'group-bot'        => 'Bots',
'group-sysop'      => 'Administradores',
'group-bureaucrat' => 'Burócratas',
'group-all'        => '(todos)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administrador',
'group-bureaucrat-member' => 'Burócrata',

'grouppage-bot'        => 'Project:Bot',
'grouppage-sysop'      => 'Project:Administradors',
'grouppage-bureaucrat' => 'Project:Burócratas',

# User rights log
'rightslog'      => 'Cambios de perfil de usuario',
'rightslogtext'  => 'Este es un registro de cambios en los permisos de usuarios.',
'rightslogentry' => 'modificó los grupos a los que pertenece $1: de $2 a $3',
'rightsnone'     => 'ninguno',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|cambio|cambios}}',
'recentchanges'                     => 'Cambios recientes',
'recentchangestext'                 => 'Siga los cambios más recientes de esta página.',
'recentchanges-feed-description'    => 'Siga los cambios más recientes de este agregador.',
'rcnote'                            => 'A continuación se muestran los últimos <b>$1</b> cambios en los últimos <b>$2</b> días, actualizados $3',
'rcnotefrom'                        => 'A continuación se muestran los cambios desde <b>$2</b> (hasta <b>$1</b>).',
'rclistfrom'                        => 'Mostrar nuevos cambios desde $1',
'rcshowhideminor'                   => '$1 ediciones menores',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 usuarios registrados',
'rcshowhideanons'                   => '$1 usuarios anónimos',
'rcshowhidepatr'                    => '$1 ediciones patrulladas',
'rcshowhidemine'                    => '$1 mis ediciones',
'rclinks'                           => 'Ver los últimos $1 cambios de los últimos $2 días.<br />$3',
'diff'                              => 'dif',
'hist'                              => 'hist',
'hide'                              => 'ocultar',
'show'                              => 'mostrar',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 usuarios vigilando]',
'rc_categories'                     => 'Limitar a categorías (separadas por "|")',
'rc_categories_any'                 => 'Any',

# Recent changes linked
'recentchangeslinked'          => 'Seguimiento de enlaces',
'recentchangeslinked-noresult' => 'No hay cambios en las páginas enlazadas en el periodo indicado.',
'recentchangeslinked-summary'  => "Esta página especial lista los últimos cambios en las páginas enlazadas. Las páginas en su lista de seguimiento están en '''negrita'''.",

# Upload
'upload'                      => 'Subir archivo',
'uploadbtn'                   => 'Subir un archivo',
'reupload'                    => 'Subir otra vez',
'reuploaddesc'                => 'Regresar al formulario para subir.',
'uploadnologin'               => 'No ha iniciado sesión',
'uploadnologintext'           => 'Tiene que [[Special:Userlogin|iniciar sesión]] para poder subir archivos.',
'upload_directory_read_only'  => 'El servidor web no puede escribir en el directorio de subida de archivos ($1).',
'uploaderror'                 => 'Error al intentar subir archivo',
'uploadtext'                  => "Para ver o buscar imágenes que se hayan subido previamente, vaya a la [[Special:Imagelist|lista de imágenes subidas]]. Los archivos subidos y borrados son registrados en el [[Special:Log/upload|registro de subidas]]. Consulte también la [[Project:Política de uso de imágenes|política de uso de imágenes]]. Use el siguiente formulario para subir nuevos archivos de imágenes que vaya a usar para ilustrar sus artículos. En la mayoría de los navegadores, verá un botón \"Browse...\", que abrirá el diálogo de selección de archivos estándar de su sistema operativo. Cuando haya elegido un archivo, su nombre aparecerá en el campo de texto al lado del botón \"Examinar...\". También debe marcar la caja afirmando que no está violando ningún copyright al subir el archivo. Presione el botón \"Subir\" para completar la subida. Esto puede tomar algún tiempo si tiene una conexión a Internet lenta. Los formatos preferidos son JPEG para imágenes fotográficas, PNG para dibujos y diagramas, y OGG para sonidos. Por favor, dele a sus archivos nombres descriptivos para evitar confusiones. Para incluir la imagen en un artículo, use un enlace de la forma
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Archivo.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Archivo.png|alt text]]</nowiki>'''
o para sonidos
* '''<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:Archivo.ogg]]</nowiki>'''
Por favor recuerde que, al igual que con las páginas de {{SITENAME}}, otros pueden editar o borrar los archivos que ha subido si piensan que es bueno para el proyecto, y se le puede bloquear, impidiéndole subir más archivos si abusa del sistema.",
'uploadlog'                   => 'registro de subidas',
'uploadlogpage'               => 'Registro de subidas',
'uploadlogpagetext'           => 'Abajo hay una lista de los archivos que se han subido recientemente. Todas las horas son del servidor (UTC).
<ul>
</ul>',
'filename'                    => 'Nombre del archivo',
'filedesc'                    => 'Sumario',
'fileuploadsummary'           => 'Descripción:',
'filestatus'                  => 'Estado de copyright',
'filesource'                  => 'Fuente',
'uploadedfiles'               => 'Archivos subidos',
'ignorewarning'               => 'Ignorar aviso y guardar de todos modos',
'ignorewarnings'              => 'Ignorar cualquier aviso',
'minlength'                   => 'Los nombres de imágenes deben ser al menos de tres letras.',
'illegalfilename'             => 'El nombre de archivo «$1» contiene caracteres que no están permitidos en títulos de páginas. Por favor, renombra el archivo e intenta volver a subirlo.',
'badfilename'                 => 'El nombre de la imagen se ha cambiado a "$1".',
'filetype-badmime'            => 'No está permitido subir archivos del tipo MIME "$1".',
'filetype-badtype'            => "'''\".\$1\"''' es un tipo de archivo no permitido. Lista de tipos permitidos: \$2",
'filetype-missing'            => 'El archivo no tiene extensión (por ejemplo ".jpg").',
'large-file'                  => 'Se recomienda que los archivos no sean mayores de $1; el archivo tiene un tamaño de $2.',
'largefileserver'             => 'El tamaño de este archivo es mayor del que este servidor admite por configuración.',
'emptyfile'                   => 'El archivo que has intentado subir parece estar vacío; por favor, verifica que realmente se trate del archivo que intentabas subir.',
'fileexists'                  => "Ya existe un archivo con este nombre. Por favor compruebe el existente $1 si no está seguro de querer reemplazarlo.


'''Nota:''' Si finalmente sustituye el archivo, debe refrescar la caché de su navegador para ver los cambios:
*'''Mozilla''' / '''Firefox''': Pulsa el botón '''Recargar''' (o '''ctrl-r''')
*'''Internet Explorer''' / '''Opera''': '''ctrl-f5'''
*'''Safari''': '''cmd-r'''
*'''Konqueror''': '''ctrl-r''",
'fileexists-extension'        => 'Ya existe un archivo con un nombre similar:<br />
Nombre del archivo a subir: <strong><tt>$1</tt></strong><br />
Nombre del archivo existente: <strong><tt>$2</tt></strong><br />
Por favor, elija un nombre diferente.',
'fileexists-thumb'            => "'''<center>Imagen existente</center>'''",
'fileexists-thumbnail-yes'    => 'El archivo parece ser una imagen de tamaño reducido <i>(thumbnail)</i>. Por favor, compruebe el archivo <strong><tt>$1</tt></strong>.<br />
Si éste tiene el mismo tamaño que la original no es necesario subir una imagen reducida adicional.',
'file-thumbnail-no'           => 'El nombre del archivo comienza por <strong><tt>$1</tt></strong>. Parece ser una imagen de tamaño reducido <i>(thumbnail)</i>.
Si tiene esta imagen a resolución completa, por favor, súbala. En caso contrario cambie el nombre del archivo.',
'fileexists-forbidden'        => 'Ya existe un archivo con este nombre. Por favor, cambie el nombre del archivo y vuelva a subirlo. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Ya existe en ''[[Commons:Portada|Commons]]'' un archivo con el mismo nombre. Por favor cambie el nombre del archivo y vuelva a subirlo. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'Subida con éxito',
'fileuploaded'                => 'El archivo "$1" se subió con éxito.
Por favor siga este enlace: ($2) a la página de descripción y escriba
la información acerca del archivo, como por ejemplo de dónde viene, cuándo fue
creado y por quién, y cualquier otra cosa que pueda saber al respecto.',
'uploadwarning'               => 'Advertencia de subida de archivo',
'savefile'                    => 'Guardar archivo',
'uploadedimage'               => 'subió "[[$1]]".',
'uploaddisabled'              => 'Lo sentimos, la funcionalidad de subir archivos está deshabilitada.',
'uploaddisabledtext'          => 'Las subidas de archivos están deshabilitadas en este wiki',
'uploadscripted'              => 'Este archivo contiene HTML o código que puede ser interpretado erroneamente por un navegador web.',
'uploadcorrupt'               => 'Este archivo está corrupto o tiene una extensión incorrecta. Por favor, compruebe el archivo y súbalo de nuevo.',
'uploadvirus'                 => '¡El archivo contiene un virus! Detalles: $1',
'sourcefilename'              => 'Nombre original',
'destfilename'                => 'Nombre de destino',
'watchthisupload'             => 'Vigilar esta página',
'filewasdeleted'              => 'Un archivo con este nombre se subió con anterioridad y posteriormente ha sido borrado. Deberías revisar el $1 antes de subirlo de nuevo.',

'upload-proto-error'      => 'Error de protocolo',
'upload-proto-error-text' => 'Para subir archivos desde otra página la URL debe comenzar por <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Error interno',
'upload-file-error-text'  => 'Ocurrió un error al intentar crear un archivo temporal en el servidor. Por favor, contacte con el administrador.',
'upload-misc-error'       => 'Error desconocido',
'upload-misc-error-text'  => 'Ocurrió un error desconocido al subir el archivo. Por favor, verifique que la URL es válida y accesible y pruebe de nuevo. Si el problema persiste, contacte con el administrador.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'No se pudo alcanzar la URL',
'upload-curl-error6-text'  => 'La URL indicada es inalcanzable. Por favor, compruebe de nuevo que la URL es correcta y el servidor está funcionando.',
'upload-curl-error28'      => 'Tiempo de espera excedido',
'upload-curl-error28-text' => 'La página tardó demasiado en responder. Por favor, compruebe que el servidor está funcionando, espere un poco y vuelva a intentarlo. Quizás desee intentarlo en otro momento de menos carga.',

'license'            => 'Licencia',
'nolicense'          => 'Ninguna seleccionada',
'upload_source_url'  => ' (una URL válida y accesible públicamente)',
'upload_source_file' => ' (un archivo en su ordenador)',

# Image list
'imagelist'                 => 'Lista de imágenes',
'imagelisttext'             => 'Abajo hay una lista de $1 imágenes ordenadas $2.',
'imagelistforuser'          => 'Esto sólo muestra imágenes subidas por $1.',
'getimagelist'              => ' obteniendo la lista de imágenes',
'ilsubmit'                  => 'Búsqueda',
'showlast'                  => 'Mostrar las últimas $1 imágenes ordenadas  $2.',
'byname'                    => 'por nombre',
'bydate'                    => 'por fecha',
'bysize'                    => 'por tamaño',
'imgdelete'                 => 'borr',
'imgdesc'                   => 'desc',
'imgfile'                   => 'archivo',
'imglegend'                 => 'Leyenda: (desc) = mostrar/editar la descripción de la imagen.',
'imghistory'                => 'Historial de la imagen',
'revertimg'                 => 'rev',
'deleteimg'                 => 'borr',
'deleteimgcompletely'       => 'Borrar todas las revisiones',
'imghistlegend'             => 'Leyenda: (act) = la imagen actual, (borr) = borrar esta versión antigua, (rev) = volver a esta versión antigua.
<br /><i>Pulse sobre la flecha para ver las imágenes subidas en esa fecha</i>.',
'imagelinks'                => 'Enlaces a la imagen',
'linkstoimage'              => 'Las siguientes páginas enlazan a esta imagen:',
'nolinkstoimage'            => 'No hay páginas que enlacen a esta imagen.',
'sharedupload'              => 'Este archivo está compartido y puede usarse desde otros proyectos.',
'shareduploadwiki'          => 'Puede consultar $1 para más información.',
'shareduploadwiki-linktext' => 'página de descripción del archivo',
'noimage'                   => 'No existe un archivo con ese nombre, puede $1.',
'noimage-linktext'          => 'subirlo',
'uploadnewversion-linktext' => 'Subir una nueva versión de este archivo',
'imagelist_date'            => 'Fecha',
'imagelist_name'            => 'Nombre',
'imagelist_user'            => 'Usuario',
'imagelist_size'            => 'Tamaño',
'imagelist_description'     => 'Descripción',
'imagelist_search_for'      => 'Buscar por nombre de imagen:',

# MIME search
'mimesearch' => 'Búsqueda MIME',
'mimetype'   => 'Tipo MIME:',
'download'   => 'descargar',

# Unwatched pages
'unwatchedpages' => 'Páginas no vigiladas',

# List redirects
'listredirects' => 'Lista de redirecciones',

# Unused templates
'unusedtemplates'     => 'Plantillas sin uso',
'unusedtemplatestext' => 'Aquí se enumeran todas las páginas en la zona de plantillas que no están incluidas en otras páginas. Recuerda mirar lo que enlaza a las plantillas antes de borrarlas.',
'unusedtemplateswlh'  => 'otros enlaces',

# Random redirect
'randomredirect'         => 'Ir a una redirección cualquiera',
'randomredirect-nopages' => 'No hay redirecciones en este espacio de nombres.',

# Statistics
'statistics'             => 'Estadísticas',
'sitestats'              => 'Estadísticas del sitio',
'userstats'              => 'Estadísticas de usuario',
'sitestatstext'          => "Hay un total de '''$1''' páginas en la base de datos
Esto incluye páginas de discusión, páginas sobre {{SITENAME}}, borradores, redirecciones y otras que probablemente no son artículos.
Excluyéndolas, hay '''$2''' páginas que probablemente son artículos legítimos.

'''$8''' archivos fueron almacenados en el servidor.

Han habido un total de '''$3''' visitas y '''$4''' ediciones desde que el wiki fue instalado.
Esto resulta en un promedio de '''$5''' ediciones por página y '''$6''' visitas por edición.

La longitud de [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] es de '''$7'''",
'userstatstext'          => "Hay {{PLURAL:$1|'''1''' usuario registrado|'''$1''' usuarios registrados}},
de los cuales '''$2''' (o '''$4%''') son administradores ($5, ver $3).",
'statistics-mostpopular' => 'Páginas más vistas',

'disambiguations'      => 'Páginas de desambiguación',
'disambiguationspage'  => 'Template:Desambiguación',
'disambiguations-text' => "Las siguientes páginas enlazan a una '''disambiguation page'''. Deberían enlazar al tema apropiado en su lugar.<br />Una página se trata como de desambiguación si usa una plantilla enlazada desde [[MediaWiki:disambiguationspage]]",

'doubleredirects'     => 'Redirecciones dobles',
'doubleredirectstext' => '<b>Atención:</b> Esta lista puede contener falsos positivos. Eso significa usualmente que hay texto adicional con enlaces bajo el primer #REDIRECT.<br />
Cada fila contiene enlaces al segundo y tercer redirect, así como la primera línea del segundo redirect, en la que usualmente se encontrará el artículo "real" al que el primer redirect debería apuntar.',

'brokenredirects'        => 'Redirecciones incorrectas',
'brokenredirectstext'    => 'Las redirecciones siguientes enlazan a un artículo que no existe.',
'brokenredirects-edit'   => '(editar)',
'brokenredirects-delete' => '(borrar)',

'withoutinterwiki'        => 'Páginas sin enlaces de idiomas',
'withoutinterwiki-header' => 'Las siguientes páginas no enlazan a versiones en otros idiomas:',

'fewestrevisions' => 'Artículos con menos revisiones',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoría|categorías}}',
'nlinks'                  => '$1 {{PLURAL:$1|enlace|enlaces}}',
'nmembers'                => '$1 {{PLURAL:$1|artículo|artículos}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisión|revisiones}}',
'nviews'                  => '$1 {{PLURAL:$1|vista|vistas}}',
'specialpage-empty'       => 'Esta página está vacía.',
'lonelypages'             => 'Páginas huérfanas',
'lonelypagestext'         => 'Las siguientes páginas no están enlazadas desde ninguna otra página de este wiki.',
'uncategorizedpages'      => 'Páginas sin categorizar',
'uncategorizedcategories' => 'Categorías sin categorizar',
'uncategorizedimages'     => 'Imágenes sin categorizar',
'uncategorizedtemplates'  => 'Plantillas sin categorizar',
'unusedcategories'        => 'Categorías sin uso',
'unusedimages'            => 'Imágenes sin uso',
'popularpages'            => 'Páginas populares',
'wantedcategories'        => 'Categorías requeridas',
'wantedpages'             => 'Páginas requeridas',
'mostlinked'              => 'Artículos más enlazados',
'mostlinkedcategories'    => 'Categorías más enlazadas',
'mostlinkedtemplates'     => 'Plantillas más enlazadas',
'mostcategories'          => 'Páginas con más categorías',
'mostimages'              => 'Imágenes más usadas',
'mostrevisions'           => 'Artículos con más ediciones',
'allpages'                => 'Todas las páginas',
'prefixindex'             => 'Páginas por prefijo',
'randompage'              => 'Página aleatoria',
'randompage-nopages'      => 'No hay páginas en este espacio de nombres.',
'shortpages'              => 'Páginas cortas',
'longpages'               => 'Páginas largas',
'deadendpages'            => 'Páginas sin salida',
'deadendpagestext'        => 'Las páginas siguientes no enlazan a ninguna otra página en este wiki.',
'protectedpages'          => 'Páginas protegidas',
'protectedpagestext'      => 'Las siguientes páginas están protegidas contra edición o renombrado',
'protectedpagesempty'     => 'Actualmente no hay páginas protegidas con esos parámetros.',
'listusers'               => 'Lista de usuarios',
'specialpages'            => 'Páginas especiales',
'spheading'               => 'Páginas especiales',
'restrictedpheading'      => 'Páginas especiales restringidas',
'rclsub'                  => '(a páginas enlazadas desde "$1")',
'newpages'                => 'Páginas nuevas',
'newpages-username'       => 'Nombre de usuario',
'ancientpages'            => 'Artículos más antiguos',
'intl'                    => 'Enlaces interlenguaje',
'move'                    => 'Trasladar',
'movethispage'            => 'Trasladar esta página',
'unusedimagestext'        => '<p>Dese cuenta de que es posible que otras páginas web enlacen directamente a una imagen, por lo que pueden estar en uso pese a aparecer aquí.',
'unusedcategoriestext'    => 'Las siguientes categorías han sido creadas, pero ningún artículo o categoría las utiliza.',

# Book sources
'booksources'               => 'Fuentes de libros',
'booksources-search-legend' => 'Buscar fuentes de libros',
'booksources-go'            => 'Ir',
'booksources-text'          => 'A continuación se muestra una lista de enlaces a otras páginas que venden libros nuevos y usados, y que quizás tengan más información acerca de los libros que busca:',

'categoriespagetext' => 'Existen las siguientes categorías en este wiki.',
'data'               => 'Datos',
'userrights'         => 'Configuración de permisos de usuarios',
'groups'             => 'Grupos de usuarios',
'isbn'               => 'ISBN',
'alphaindexline'     => '$1 a $2',
'version'            => 'Versión',

# Special:Log
'specialloguserlabel'  => 'Usuario:',
'speciallogtitlelabel' => 'Título:',
'log'                  => 'Registros',
'log-search-legend'    => 'Buscar registros',
'log-search-submit'    => 'Ir',
'alllogstext'          => 'Presentación combinada de los registros de subidas, borrados, protecciones, bloqueos y administradores.
Puede filtrar esta vista seleccionando el tipo de registro, el nombre de usuario, o la página afectada.',
'logempty'             => 'No hay elementos en el registro con esas condiciones.',
'log-title-wildcard'   => 'Buscar títulos que empiecen por este texto',

# Special:Allpages
'nextpage'          => 'Siguiente página ($1)',
'prevpage'          => 'Página anterior ($1)',
'allpagesfrom'      => 'Mostrar páginas comenzando en:',
'allarticles'       => 'Todos los artículos',
'allinnamespace'    => 'Todas las páginas (espacio $1)',
'allnotinnamespace' => 'Todas las páginas (fuera del espacio $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Siguiente',
'allpagessubmit'    => 'Mostrar',
'allpagesprefix'    => 'Mostrar páginas con el prefijo:',
'allpagesbadtitle'  => 'El título dado era inválido o tenía un prefijo de enlace inter-idioma o inter-wiki. Puede contener uno o más caracteres que no se pueden usar en títulos.',

# Special:Listusers
'listusersfrom'      => 'Mostrar usuarios empezando por:',
'listusers-submit'   => 'Mostrar',
'listusers-noresult' => 'Ningún usuario encontrado.',

# E-mail user
'mailnologin'     => 'No enviar dirección',
'mailnologintext' => 'Debe [[Special:Userlogin|iniciar sesión]] y haber validado su dirección de correo electrónico en sus [[Special:Preferences|preferencias]] para poder enviar correo a otros usuarios.',
'emailuser'       => 'Enviar correo electrónico a este usuario',
'emailpage'       => 'Correo electrónico a usuario',
'emailpagetext'   => 'Si este usuario ha registrado una dirección electrónica válida en sus preferencias de usuario, el siguiente formulario sirve para enviarle un mensaje.
La dirección electrónica que indicó en sus preferencias de usuario aparecerá en el remitente para que el destinatario pueda responderle.',
'usermailererror' => 'El sistema de correo devolvió un error:',
'defemailsubject' => 'Correo de {{SITENAME}}',
'noemailtitle'    => 'No hay dirección de correo electrónico',
'noemailtext'     => 'Este usuario no ha especificado una dirección de correo electrónico válida, o ha elegido no recibir correo electrónico de otros usuarios.',
'emailfrom'       => 'De',
'emailto'         => 'Para',
'emailsubject'    => 'Asunto',
'emailmessage'    => 'Mensaje',
'emailsend'       => 'Enviar',
'emailccme'       => 'Envíame una copia del mensaje.',
'emailccsubject'  => 'Copia del mensaje a $1: $2',
'emailsent'       => 'Correo electrónico enviado',
'emailsenttext'   => 'Su correo electrónico ha sido enviado.',

# Watchlist
'watchlist'            => 'Lista de seguimiento',
'mywatchlist'          => 'Lista de seguimiento',
'watchlistfor'         => "(para '''$1''')",
'nowatchlist'          => 'No tiene ninguna página en su lista de seguimiento.',
'watchlistanontext'    => 'Para ver o editar las entradas de tu lista de seguimiento debes $1.',
'watchlistcount'       => "'''Tienes $1 páginas en tu lista de seguimiento, incluyendo las de discusión.'''",
'clearwatchlist'       => 'Limpiar lista de seguimiento',
'watchlistcleartext'   => '¿Estás seguro de querer borrarlos?',
'watchlistclearbutton' => 'Vaciar la lista de seguimiento',
'watchlistcleardone'   => 'Tu lista de seguimiento ha sido borrada. Se eliminaron $1 elementos.',
'watchnologin'         => 'No ha iniciado sesión',
'watchnologintext'     => 'Debe [[Special:Userlogin|iniciar sesión]] para modificar su lista de seguimiento.',
'addedwatch'           => 'Añadido a la lista de seguimiento',
'addedwatchtext'       => "La página «[[:\$1]]» ha sido añadida a su [[Special:Watchlist|lista se seguimiento]]. Cambios futuros en esta página y su página de discusión asociada se indicarán ahí, y la página aparecerá '''en negrita''' en la [[Special:Recentchanges|lista de cambios recientes]] para hacerla más visible. <p>Cuando quiera eliminar la página de su lista de seguimiento, pulse sobre \"Dejar de vigilar\" en el menú.",
'removedwatch'         => 'Eliminada de la lista de seguimiento',
'removedwatchtext'     => 'La página "[[:$1]]" ha sido eliminada de su lista de seguimiento.',
'watch'                => 'Vigilar',
'watchthispage'        => 'Vigilar esta página',
'unwatch'              => 'Dejar de vigilar',
'unwatchthispage'      => 'Dejar de vigilar',
'notanarticle'         => 'No es un artículo',
'watchnochange'        => 'Ninguno de los artículos en su lista de seguimiento fue editado en el periodo de tiempo mostrado.',
'watchdetails'         => '* $1 páginas vigiladas, sin contar las de discusión
* [[Special:Watchlist/edit|Mostrar y editar la lista de seguimiento]]',
'wlheader-enotif'      => '* La notificación por correo electrónico está habilitada',
'wlheader-showupdated' => "* Las páginas modificadas desde su última visita aparecen en '''negrita'''",
'watchmethod-recent'   => 'buscando ediciones recientes en la lista de seguimiento',
'watchmethod-list'     => 'buscando ediciones recientes en la lista de seguimiento',
'removechecked'        => 'Borrar artículos seleccionados de la lista de seguimiento',
'watchlistcontains'    => 'Su lista de seguimiento posee $1 páginas.',
'watcheditlist'        => "A continuación se muestra un listado alfabético de su lista de seguimiento.
Seleccione los artículos que desea eliminar de su lista de seguimiento y pulse el botón 'Eliminar artículos seleccionados' al final de la página.",
'removingchecked'      => 'Eliminando los artículos solicitados de la lista de seguimiento...',
'couldntremove'        => "No se pudo borrar el artículo '$1'...",
'iteminvalidname'      => "Problema con el artículo '$1', nombre inválido...",
'wlnote'               => 'A continuación se muestran los últimos $1 cambios en las últimas <b>$2</b> horas.',
'wlshowlast'           => 'Mostrar las últimas $1 horas $2 días $3',
'wlsaved'              => 'Esta es una versión guardada de su lista de seguimiento.',
'watchlist-show-bots'  => 'Mostrar ediciones de bots',
'watchlist-hide-bots'  => 'Ocultar ediciones de bots',
'watchlist-show-own'   => 'Mostrar mis ediciones',
'watchlist-hide-own'   => 'Ocultar mis ediciones',
'watchlist-show-minor' => 'Mostrar ediciones menores',
'watchlist-hide-minor' => 'Ocultar ediciones menores',
'wldone'               => 'Hecho.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Añadiendo a la lista de seguimiento...',
'unwatching' => 'Eliminando de la lista de seguimiento...',

'enotif_mailer'                => 'Notificación por correo de {{SITENAME}}',
'enotif_reset'                 => 'Marcar todas las páginas visitadas',
'enotif_newpagetext'           => 'Se trata de una nueva página.',
'enotif_impersonal_salutation' => 'usuario de {{SITENAME}}',
'changed'                      => 'modificada',
'created'                      => 'creada',
'enotif_subject'               => 'La página $PAGETITLE de {{SITENAME}} ha sido $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Vaya a $1 para ver todos los cambios desde su última visita.',
'enotif_lastdiff'              => 'Vaya a $1 para ver este cambio.',
'enotif_anon_editor'           => 'usuario anónimo $1',
'enotif_body'                  => 'Estimado/a $WATCHINGUSERNAME,

La página de {{SITENAME}} «$PAGETITLE»
ha sido $CHANGEDORCREATED por el usuario $PAGEEDITOR el $PAGEEDITDATE.
La versión actual se encuentra en {{fullurl:$PAGETITLE_RAWURL}}

$NEWPAGE

El resumen de edición es: $PAGESUMMARY $PAGEMINOREDIT

Si desea contactar con el usuario puede hacerlo por correo: {{fullurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}} o en el wiki: {{fullurl:User:$PAGEEDITOR_RAWURL}}.

Para recibir nuevas notificaciones de cambios de esta página, deberá vistarla nuevamente.
También puede, en su lista de seguimiento, modificar las opciones de notificación de sus
páginas vigiladas.

             El sistema de notificación de {{SITENAME}}.

--
Cambie las opciones de su lista de seguimiento en: {{fullurl:Special:Watchlist|edit=yes}}',

# Delete/protect/revert
'deletepage'                  => 'Borrar esta página',
'confirm'                     => 'Confirmar',
'excontent'                   => "El contenido era: '$1'",
'excontentauthor'             => "El contenido era: '$1' (y el único autor fue '$2')",
'exbeforeblank'               => "contenido antes de borrar era: '$1'",
'exblank'                     => 'página estaba vacía',
'confirmdelete'               => 'Confirme el borrado',
'deletesub'                   => '(Borrando "$1")',
'historywarning'              => 'Atención: La página que está a punto de borrar tiene un historial:',
'confirmdeletetext'           => 'Está a punto de borrar de la base de datos una página o imagen de forma permanente, así como todo su historial.
Por favor, confirme que realmente quiere hacer eso, que entiende las consecuencias, y que lo está haciendo de acuerdo con [[{{MediaWiki:policy-url}}]].',
'actioncomplete'              => 'Acción completa',
'deletedtext'                 => '"$1" ha sido borrado.
Véase $2 para un registro de los borrados recientes.',
'deletedarticle'              => 'borrado "$1"',
'dellogpage'                  => 'Registro de borrados',
'dellogpagetext'              => 'A continuación se muestra una lista de los borrados más recientes. Todos los tiempos se muestran en hora del servidor (UTC).',
'deletionlog'                 => 'registro de borrados',
'reverted'                    => 'Recuperar una revisión anterior',
'deletecomment'               => 'Motivo del borrado',
'imagereverted'               => 'Se restauró correctamente una versión anterior.',
'rollback'                    => 'Deshacer ediciones',
'rollback_short'              => 'Deshacer',
'rollbacklink'                => 'Deshacer',
'rollbackfailed'              => 'No se pudo deshacer',
'cantrollback'                => 'No se pueden deshacer las ediciones; el último colaborador es el único autor de este artículo.',
'alreadyrolled'               => 'No se puede deshacer la última edición de [[:$1]] por [[User:$2|$2]] ([[User talk:$2|discusión]]); alguien más ha editado o des hecho una edición de esta página. La última edición corresponde a [[User:$3|$3]] ([[User talk:$3|discusión]]).',
'editcomment'                 => 'El resumen de la edición es: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Se han deshecho las ediciones realizadas por [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]); hacia la última versión por [[User:$1|$1]]',
'sessionfailure'              => 'Parece que hay un problema con su sesión. Esta acción ha sido cancelada como medida de precaución contra secuestros de sesión. Por favor, vuelva a la página anterior e inténtelo de nuevo.',
'protectlogpage'              => 'Protecciones de páginas',
'protectlogtext'              => 'A continuación se muestra una lista de protección y desprotección de página. Véase [[Project:Esta página está protegida]] para más información.',
'protectedarticle'            => 'protegió [[$1]]',
'unprotectedarticle'          => 'desprotegió [[$1]]',
'protectsub'                  => '(Protegiendo "$1")',
'confirmprotecttext'          => '¿Realmente desea proteger esta página?',
'confirmprotect'              => 'Confirmar protección',
'protectmoveonly'             => 'Proteger sólo contra traslados',
'protectcomment'              => 'Motivo de la protección',
'protectexpiry'               => 'Expiración',
'protect_expiry_invalid'      => 'El tiempo de expiración no es válido.',
'protect_expiry_old'          => 'El tiempo de expiración está en el pasado.',
'unprotectsub'                => '(Desprotegiendo "$1")',
'protect-unchain'             => 'Configurar permisos para traslados',
'protect-text'                => 'Puede visualizar y modificar el nivel de protección de [[$1]]. Por favor, asegúrese de que sigue las [[Project:Políticas de protección de páginas|políticas de protección de páginas]].',
'protect-locked-blocked'      => 'No puede cambiar los niveles de protección estando bloqueado. A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-locked-dblock'       => 'Los niveles de protección no se pueden cambiar debido a un bloqueo activo de la base de datos.
A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-locked-access'       => 'Su cuenta no tiene permiso para cambiar los niveles de protección de una página.
A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-cascadeon'           => 'Esta página está actualmente protegida porque está incluida en el grupo $1 de páginas, que tiene la protección en cascada activa. Puede cambiar el nivel de protección de esta página, pero eso no afectará a la protección en cascada.',
'protect-default'             => '(por defecto)',
'protect-level-autoconfirmed' => 'Bloquear usuarios no registrados',
'protect-level-sysop'         => 'Sólo administradores',
'protect-summary-cascade'     => 'en cascada',
'protect-expiring'            => 'expira $1 (UTC)',
'protect-cascade'             => 'Protección en cascada - proteger todas las páginas incluidas en ésta.',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Nivel de restricción:',
'minimum-size'                => 'Tamaño mínimo',
'maximum-size'                => 'Tamaño máximo',

# Restrictions (nouns)
'restriction-edit' => 'Pueden editar',
'restriction-move' => 'Pueden trasladar',

# Restriction levels
'restriction-level-sysop'         => 'protegido por completo',
'restriction-level-autoconfirmed' => 'protegido parcialmente',
'restriction-level-all'           => 'cualquier nivel',

# Undelete
'undelete'                 => 'Restaurar una página borrada',
'undeletepage'             => 'Ver y restaurar páginas borradas',
'viewdeletedpage'          => 'Ver páginas borradas',
'undeletepagetext'         => 'Las siguientes páginas han sido borradas pero aún están en el archivo y pueden ser restauradas. El archivo se puede limpiar periódicamente.',
'undeleteextrahelp'        => "Para restaurar todas las revisiones, deja todas las casillas sin seleccionar y pulsa '''¡Restaurar!'''. Para restaurar sólo algunas revisiones, marca las revisiones que quieres restaurar y pulsa '''¡Restaurar!'''. Haciendo clic en al botón '''Nada''', se deseleccionarán  todas las casillas y eliminará el comentario actual.",
'undeleterevisions'        => '$1 revisiones archivadas',
'undeletehistory'          => 'Si restaura una página, todas sus revisiones serán restauradas al historial. Si una nueva página con el mismo nombre ha sido creada desde que se borró la original, las versiones restauradas aparecerán como historial anterior, y la revisión actual de la página actual no se reemplazará automáticamente.',
'undeleterevdel'           => 'No se deshará el borrado si éste resulta en el borrado parcial de la última revisión de la página. En tal caso, desmarque o muestre las revisiones borradas más recientes. Las revisiones de archivos que no tiene permitido ver no se restaurarán.',
'undeletehistorynoadmin'   => 'El artículo ha sido borrado. El motivo de su eliminación se indica abajo en el sumario, así como el detalle de las ediciones realizadas antes del borrado. El texto completo del artículo está disponible sólo para usuarios con permisos de [[{{MediaWiki:grouppage-sysop}}|administrador]].',
'undelete-revision'        => 'Borrada revisión de $1 desde $2:',
'undeleterevision-missing' => 'Revisión incorrecta o no encontrada. Puede que haya seguido un enlace erróneo, o que la revisión haya sido eliminada del archivo.',
'undeletebtn'              => '¡Restaurar!',
'undeletereset'            => 'Nada',
'undeletecomment'          => 'Razón para restaurar:',
'undeletedarticle'         => 'restaurado "$1"',
'undeletedrevisions'       => '{{PLURAL:$1|Una edición restaurada|$1 ediciones restauradas}}',
'undeletedrevisions-files' => '$1 revisions and $2 file(s) restored',
'undeletedfiles'           => '$1 archivo(s) restaurados',
'cannotundelete'           => 'Ha fallado el deshacer el borrado; alguien más puede haber deshecho el borrado antes.',
'undeletedpage'            => "<big>'''Se ha restaurado $1'''</big>

Consulta el [[Special:Log/delete|registro de borrados]] para ver una lista de los últimos borrados / restauraciones.",
'undelete-header'          => 'Consulte el [[Special:Log/delete|registro de borrados]] para ver las páginas borradas recientemente.',
'undelete-search-box'      => 'Buscar páginas borradas',
'undelete-search-prefix'   => 'Mostrar páginas que empiecen por:',
'undelete-search-submit'   => 'Buscar',
'undelete-no-results'      => 'No se encontraron páginas coincidentes en el archivo de borrados.',

# Namespace form on various pages
'namespace' => 'Espacio de nombres:',
'invert'    => 'Invertir selección',

# Contributions
'contributions' => 'Contribuciones del usuario',
'mycontris'     => 'Mis contribuciones',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'No se encontraron cambios que cumplieran estos criterios.',
'ucnote'        => 'A continuación se muestran los últimos <b>$1</b> cambios de este usuario en los últimos <b>$2</b> días.',
'uclinks'       => 'Ver los últimos $1 cambios; ver los últimos $2 días.',
'uctop'         => ' (última modificación)',

'sp-contributions-newest'      => 'Últimas',
'sp-contributions-oldest'      => 'Primeras',
'sp-contributions-newer'       => '← $1 posteriores',
'sp-contributions-older'       => '$1 previas →',
'sp-contributions-newbies'     => 'Mostrar las contribuciones de usuarios nuevos solamente',
'sp-contributions-newbies-sub' => 'Para nuevos',
'sp-contributions-blocklog'    => 'Registro de bloqueos',
'sp-contributions-search'      => 'Búsqueda de contribuciones',
'sp-contributions-username'    => 'Dirección IP o nombre de usuario:',
'sp-contributions-submit'      => 'Buscar',

'sp-newimages-showfrom' => 'Mostrar nuevas imágines empezando por $1',

# What links here
'whatlinkshere'       => 'Lo que enlaza aquí',
'notargettitle'       => 'No hay página objetivo',
'notargettext'        => 'Especifique sobre qué página desea llevar a cabo esta acción.',
'linklistsub'         => '(Lista de enlaces)',
'linkshere'           => "Las siguientes páginas enlazan a '''[[:$1]]''':",
'nolinkshere'         => "Ninguna página enlaza a '''[[:$1]]''':",
'nolinkshere-ns'      => "Ninguna página enlaza a '''[[:$1]]''' en el espacio de nombres elegido.",
'isredirect'          => 'página redirigida',
'istemplate'          => 'inclusión',
'whatlinkshere-prev'  => '{{PLURAL:$1|anterior|$1 anteriores}}',
'whatlinkshere-next'  => '{{PLURAL:$1|siguiente|$1 siguientes}}',
'whatlinkshere-links' => '← links',

# Block/unblock
'blockip'                     => 'Bloquear usuario',
'blockiptext'                 => 'Use el siguiente formulario para bloquear el acceso de escritura para una dirección IP o usuario específico. Sólo debería llegarse a este extremo para evitar vandalismos, y en cualquier caso siguiendo las  [[{{MediaWiki:policy-url}}|políticas de {{SITENAME}}]].
Recuerde explicar el motivo del bloqueo (por ejemplo, citando las páginas en particular que han sido objeto de vandalismo).',
'ipaddress'                   => 'Dirección IP',
'ipadressorusername'          => 'Dirección IP o nombre de usuario',
'ipbexpiry'                   => 'Caduca dentro de',
'ipbreason'                   => 'Razón',
'ipbreasonotherlist'          => 'Otra razón',
'ipbreason-dropdown'          => '
*Motivos comunes de bloqueo
** Añadir información falsa
** Eliminar contenido de las páginas
** Publicitar enlaces a otras páginas web
** Añadir basura a las páginas
** Comportamiento intimidatorio/acoso sexual
** Abusar de múltiples cuentas
** Nombre de usuario inaceptable',
'ipbanononly'                 => 'Bloquear usuarios anónimos solamente',
'ipbcreateaccount'            => 'Prevenir creación de cuenta de usuario.',
'ipbemailban'                 => 'Prevenir que los usuarios envien correo electrónico',
'ipbenableautoblock'          => 'Bloquear automáticamente la última dirección IP utilizada por este usuario, y cualquier IP desde la que trate de editar en adelante',
'ipbsubmit'                   => 'Bloquear esta dirección',
'ipbother'                    => 'Especificar caducidad',
'ipboptions'                  => '15 minutos:15 minutes,media hora:30 minutes,una hora:1 hour,2 horas:2 hours,un día:1 day,3 días:3 days,una semana:1 week,2 semanas:2 weeks,un mes:1 month,para siempre:infinite',
'ipbotheroption'              => 'otro',
'ipbotherreason'              => 'Otro/adicional motivo:',
'ipbhidename'                 => 'Ocultar usuario/IP en el registro de bloqueos, la lista de bloqueos activos y la lista de usuarios',
'badipaddress'                => 'La dirección IP no tiene el formato correcto.',
'blockipsuccesssub'           => 'Bloqueo realizado con éxito',
'blockipsuccesstext'          => 'La dirección IP "$1" ha sido bloqueada. <br />Ver la [[Special:Ipblocklist|lista de IP bloqueadas]] para revisar los bloqueos.',
'ipb-edit-dropdown'           => 'Editar motivo del bloqueo',
'ipb-unblock-addr'            => 'Desbloquear $1',
'ipb-unblock'                 => 'Desbloquear un usuario o dirección IP',
'ipb-blocklist-addr'          => 'Ver bloqueos existentes para $1',
'ipb-blocklist'               => 'Ver bloqueos existentes',
'unblockip'                   => 'Desbloquear usuario',
'unblockiptext'               => 'Use el formulario a continuación para devolver los permisos de escritura a una dirección IP que ha sido bloqueada.',
'ipusubmit'                   => 'Desbloquear esta dirección',
'unblocked'                   => '[[User:$1|$1]] ha sido desbloqueado',
'unblocked-id'                => 'Se ha eliminado el bloqueo $1',
'ipblocklist'                 => 'Lista de direcciones IP bloqueadas',
'ipblocklist-submit'          => 'Buscar',
'blocklistline'               => '$1, $2 bloquea $3 ($4)',
'infiniteblock'               => 'infinito',
'expiringblock'               => 'expira $1',
'anononlyblock'               => 'sólo anon.',
'noautoblockblock'            => 'bloqueo automático deshabilitado',
'createaccountblock'          => 'Creación de cuenta bloqueada.',
'emailblock'                  => 'correo electrónico bloqueado',
'ipblocklist-empty'           => 'La lista de bloqueos está vacía.',
'ipblocklist-no-results'      => 'El nombre de usuario o IP indicado no está bloqueado.',
'blocklink'                   => 'bloquear',
'unblocklink'                 => 'desbloquear',
'contribslink'                => 'contribuciones',
'autoblocker'                 => 'Ha sido bloqueado automáticamente porque su dirección IP ha sido usada recientemente por "[[User:$1|$1]]". La razón esgrimida para bloquear a "[[User:$1|$1]]" fue "$2".',
'blocklogpage'                => 'Bloqueos de usuarios',
'blocklogentry'               => 'bloqueó a "$1" durante un plazo de "$2" "$3".',
'blocklogtext'                => 'Esto es un registro de bloqueos y desbloqueos de usuarios. Las direcciones bloqueadas automáticamente no aparecen aquí. Consulte la [[Special:Ipblocklist|lista de direcciones IP bloqueadas]] para ver la lista de prohibiciones y bloqueos actualmente vigente.',
'unblocklogentry'             => 'desbloqueó a "$1"',
'block-log-flags-anononly'    => 'sólo usuarios anónimos',
'block-log-flags-nocreate'    => 'creación de cuentas deshabilitada',
'block-log-flags-noautoblock' => 'bloqueo automático deshabilitado',
'block-log-flags-noemail'     => 'correo electrónico deshabilitado',
'range_block_disabled'        => 'La facultad de administrador de crear bloqueos por rangos está deshabilitada.',
'ipb_expiry_invalid'          => 'El tiempo de caducidad no es válido.',
'ipb_already_blocked'         => '"$1" ya se encuentra bloqueado.',
'ip_range_invalid'            => 'El rango de IP no es válido.',
'proxyblocker'                => 'Bloqueador de proxies',
'ipb_cant_unblock'            => "'''Error''': Número ID $1 de bloqueo no encontrado. Pudo haber sido desbloqueado ya.",
'proxyblockreason'            => 'Su dirección IP ha sido bloqueada porque es un proxy abierto. Por favor, contacte con su proveedor de servicios de Internet o con su servicio de asistencia técnica e infórmeles de este grave problema de seguridad.',
'proxyblocksuccess'           => 'Hecho.',
'sorbs'                       => 'SORBS DNSBL',
'sorbsreason'                 => 'Su dirección IP está listada como proxy abierto en [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Su dirección IP está listada como proxy abierto en [http://www.sorbs.net SORBS] DNSBL. No puede crear una cuenta',

# Developer tools
'lockdb'              => 'Bloquear la base de datos',
'unlockdb'            => 'Desbloquear la base de datos',
'lockdbtext'          => 'El bloqueo de la base de datos impedirá a todos los usuarios editar páginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualquier otra función que requiera realizar cambios en la base de datos. Por favor, confirme que ésto es precisamente lo que quiere hacer y que desbloqueará la base de datos tan pronto haya finalizado las operaciones de mantenimiento.',
'unlockdbtext'        => 'El desbloqueo de la base de datos permitirá a todos los usuarios editar páginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualesquiera otras funciones que impliquen modificar la base de datos. Por favor, confirme que esto es precisamente lo que quiere hacer.',
'lockconfirm'         => 'Sí, realmente quiero bloquear la base de datos.',
'unlockconfirm'       => 'Sí, realmente quiero desbloquear la base de datos.',
'lockbtn'             => 'Bloquear la base de datos',
'unlockbtn'           => 'Desbloquear la base de datos',
'locknoconfirm'       => 'No ha confirmado lo que desea hacer.',
'lockdbsuccesssub'    => 'El bloqueo se ha realizado con éxito',
'unlockdbsuccesssub'  => 'El desbloqueo se ha realizado con éxito',
'lockdbsuccesstext'   => 'La base de datos de {{SITENAME}} ha sido bloqueada.
<br />Recuerde retirar el bloqueo después de completar las tareas de mantenimiento.',
'unlockdbsuccesstext' => 'La base de datos de {{SITENAME}} ha sido desbloqueada.',
'lockfilenotwritable' => 'El archivo-cerrojo de la base de datos no tiene permiso de escritura. Para bloquear o desbloquear la base de datos, este archivo tiene que ser escribible por el sesrvidor web.',
'databasenotlocked'   => 'La base de datos no está bloqueada.',

# Move page
'movepage'                => 'Renombrar página',
'movepagetext'            => "Usando el siguiente formulario podrá renombrar una página, moviendo todo su historial al nuevo nombre. El título anterior se convertirá en una redirección al nuevo título. Los enlaces al antiguo título de la página no se cambiarán. Asegúrese de no dejar redirecciones dobles o rotas. Usted es responsable de hacer que los enlaces sigan apuntando adonde se supone que lo deberían hacer.

Recuerde que la página '''no''' será renombrada si ya existe una página con el nuevo título, a no ser que sea una página vacía o un ''redirect'' sin historial.
Esto significa que podrá renombrar una página a su título original si ha cometido un error, pero que no podrá sobreescribir una página existente.

<b>¡ADVERTENCIA!</b>
Este puede ser un cambio drástico e inesperado para una página popular. Por favor, asegúrese de que comprende las consecuencias que acarreará antes de seguir adelante.",
'movepagetalktext'        => "La página de discusión asociada, si existe, será renombrada automáticamente '''a menos que:'''
*Esté moviendo la página entre espacios de nombres diferentes,
*Una página de discusión no vacía ya exista con el nombre nuevo, o
*Desactivase la opción \"Renombrar la página de discusión también\".

En estos casos, deberá trasladar manualmente el contenido de la página de discusión.",
'movearticle'             => 'Renombrar página',
'movenologin'             => 'No ha iniciado sesión',
'movenologintext'         => 'Es necesario ser un usuario registrado y [[Special:Userlogin|haber iniciado sesión]] para renombrar una página.',
'newtitle'                => 'A título nuevo',
'move-watch'              => 'Vigilar esta página',
'movepagebtn'             => 'Renombrar página',
'pagemovedsub'            => 'Página renombrada',
'pagemovedtext'           => 'Página "[[$1]]" renombrada a "[[$2]]".',
'articleexists'           => 'Ya existe una página con ese nombre o el nombre que ha elegido no es válido. Por favor, elija otro nombre.',
'talkexists'              => 'La página fue renombrada con éxito, pero la discusión no se pudo mover porque ya existe una en el título nuevo. Por favor incorpore su contenido manualmente.',
'movedto'                 => 'renombrado a',
'movetalk'                => 'Renombrar la página de discusión también, si es aplicable.',
'talkpagemoved'           => 'La página de discusión correspondiente también fue renombrada.',
'talkpagenotmoved'        => 'La página de discusión correspondiente <strong>no</strong> fue renombrada.',
'1movedto2'               => '[[$1]] trasladada a [[$2]]',
'1movedto2_redir'         => '[[$1]] trasladada a [[$2]] sobre una redirección',
'movelogpage'             => 'Registro de renombrados',
'movelogpagetext'         => 'A continuación se muestra una lista de páginas renombradas.',
'movereason'              => 'Motivo',
'revertmove'              => 'revertir',
'delete_and_move'         => 'Borrar y trasladar',
'delete_and_move_text'    => '==Se necesita borrado==

La página de destino ("[[$1]]") ya existe. ¿Quiere borrarla para permitir al traslado?',
'delete_and_move_confirm' => 'Sí, borrar la página',
'delete_and_move_reason'  => 'Borrada para permitir el traslado',
'selfmove'                => 'Los títulos de origen y destino son los mismos. No se puede trasladar un página sobre sí misma.',
'immobile_namespace'      => 'El título de destino es de un tipo especial. No se pueden trasladar páginas a ese espacio de nombres.',

# Export
'export'            => 'Exportar páginas',
'exporttext'        => 'Puede exportar el texto y el historial de edición de una página en particular o de un conjunto de páginas a un texto XML. En el futuro, este texto podría importarse posteriormente en otro wiki que ejecutase MediaWiki, sin embargo esta capacidad no está aún disponible en la versión actual.

Para exportar páginas, escriba los títulos en la caja de texto de abajo, un título por línea, y seleccione la versión actual junto a las versiones anteriores, con las líneas del historial, o sólo la versión actual con la información sobre la última edición.

En última instancia puede usar un enlace, por ejemplo [[Special:Export/{{Mediawiki:Mainpage}}]] para la página {{Mediawiki:Mainpage}}.',
'exportcuronly'     => 'Sólo incluir la revisión actual, no el historial completo',
'exportnohistory'   => "----
'''Nota:''' Exportar el historial completo de páginas a través de este formulario ha sido deshabilitado debido a problemas de rendimiento del servidor.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Añadir páginas desde categoría:',
'export-addcat'     => 'Añadir',

# Namespace 8 related
'allmessages'               => 'Todos los mensajes de MediaWiki',
'allmessagesname'           => 'Nombre',
'allmessagesdefault'        => 'Texto predeterminado',
'allmessagescurrent'        => 'Texto actual',
'allmessagestext'           => 'Esta es una lista de mensajes del sistema disponibles en el espacio de nombres MediaWiki:',
'allmessagesnotsupportedUI' => 'El idioma que está utilizando (<b>$1</b>) no está disponible en Special:AllMessages.',
'allmessagesnotsupportedDB' => 'Special:AllMessages no está disponible porque wgUseDatabaseMessages está deshabilitado.',
'allmessagesfilter'         => 'Filtrar por nombre del mensaje:',
'allmessagesmodified'       => 'Mostrar sólo los modificados',

# Thumbnails
'thumbnail-more'           => 'Aumentar',
'missingimage'             => '<b>Falta imagen</b><br /><i>$1</i>',
'filemissing'              => 'Falta archivo',
'thumbnail_error'          => 'Error al crear miniatura: $1',
'djvu_page_error'          => 'Página DjVu fuera de rango',
'djvu_no_xml'              => 'Imposible obtener XML para el archivo DjVu',
'thumbnail_invalid_params' => 'Parámetros de previsualización incorrectos',
'thumbnail_dest_directory' => 'Imposible crear el directorio de destino',

# Special:Import
'import'                     => 'Importar páginas',
'importinterwiki'            => 'Importación transwiki',
'import-interwiki-text'      => 'Selecciona un wiki y un título de página para importar.
Las fechas de revisiones y los nombres de editores se preservarán.
Todas las importaciones transwiki se registran en el [[Special:Log/import|registro de importaciones]].',
'import-interwiki-history'   => 'Copiar todas las versiones históricas para esta página',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Transferir páginas al espacio de nombres:',
'importtext'                 => 'Por favor, exporte el archivo desde el wiki de origen usando la utilidad Special:Export, guárdelo en su ordenador y súbalo aquí.',
'importstart'                => 'Importando páginas...',
'import-revision-count'      => '$1 revisión/ones',
'importnopages'              => 'No hay páginas que importar.',
'importfailed'               => 'La importación ha fallado: $1',
'importunknownsource'        => 'Tipo de fuente de importación desconocida',
'importcantopen'             => 'No se puedo importar este archivo',
'importbadinterwiki'         => 'Enlace interwiki anómalo',
'importnotext'               => 'Vacío o sin texto',
'importsuccess'              => '¡La importación tuvo éxito!',
'importhistoryconflict'      => 'Existen revisiones en conflicto en el historial (puede que se haya importado esta página antes)',
'importnosources'            => 'No hay fuentes de importación transwiki y no está permitido subir directamente el historial.',
'importnofile'               => 'No se subieron archivos de importación.',
'importuploaderror'          => 'La subida del archivo de importación ha fallado. Quizá el archivo es mayor que el tamaño máximo de subida permitido.',

# Import log
'importlogpage'                    => 'Registro de importaciones',
'importlogpagetext'                => 'Importaciones administrativas de páginas con historial desde otros wikis.',
'import-logentry-upload'           => 'importada [[$1]] por subida de archivo',
'import-logentry-upload-detail'    => '$1 revisión/ones',
'import-logentry-interwiki'        => 'transwikificada $1',
'import-logentry-interwiki-detail' => '$1 revisión/ones desde $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mi página de usuario',
'tooltip-pt-anonuserpage'         => 'La página de usuario de la IP desde la que edita',
'tooltip-pt-mytalk'               => 'Mi página de discusión',
'tooltip-pt-anontalk'             => 'Discusión sobre ediciones hechas desde esta dirección IP',
'tooltip-pt-preferences'          => 'Mis preferencias',
'tooltip-pt-watchlist'            => 'La lista de páginas para las que está vigilando los cambios',
'tooltip-pt-mycontris'            => 'Lista de mis contribuciones',
'tooltip-pt-login'                => 'Le animamos a registrarse, aunque no es obligatorio',
'tooltip-pt-anonlogin'            => 'Le animamos a registrarse, aunque no es obligatorio',
'tooltip-pt-logout'               => 'Salir de la sesión',
'tooltip-ca-talk'                 => 'Discusión acerca del artículo',
'tooltip-ca-edit'                 => 'Puede editar esta página. Por favor, use el botón de previsualización antes de grabar.',
'tooltip-ca-addsection'           => 'Añada un comentario a esta discusión',
'tooltip-ca-viewsource'           => 'Esta página está protegida, sólo puede ver su código fuente',
'tooltip-ca-history'              => 'Versiones anteriores de esta página y sus autores',
'tooltip-ca-protect'              => 'Proteger esta página',
'tooltip-ca-delete'               => 'Borrar esta página',
'tooltip-ca-undelete'             => 'Restaurar las ediciones hechas a esta página antes de que fuese borrada',
'tooltip-ca-move'                 => 'Trasladar (renombrar) esta página',
'tooltip-ca-watch'                => 'Añadir esta página a su lista de seguimiento',
'tooltip-ca-unwatch'              => 'Borrar esta página de su lista de seguimiento',
'tooltip-search'                  => 'Buscar en este wiki',
'tooltip-p-logo'                  => 'Portada',
'tooltip-n-mainpage'              => 'Visitar la Portada',
'tooltip-n-portal'                => 'Acerca del proyecto, qué puede hacer, dónde encontrar información',
'tooltip-n-currentevents'         => 'Información de contexto sobre acontecimientos actuales',
'tooltip-n-recentchanges'         => 'La lista de cambios recientes en el wiki',
'tooltip-n-randompage'            => 'Cargar una página aleatoriamente',
'tooltip-n-help'                  => 'El lugar para aprender',
'tooltip-n-sitesupport'           => 'Respáldenos',
'tooltip-t-whatlinkshere'         => 'Lista de todas las páginas del wiki que enlazan con ésta',
'tooltip-t-recentchangeslinked'   => 'Cambios recientes en las páginas que enlazan con ésta',
'tooltip-feed-rss'                => 'Sindicación RSS de esta página',
'tooltip-feed-atom'               => 'Sindicación Atom de esta página',
'tooltip-t-contributions'         => 'Ver la lista de contribuciones de este usuario',
'tooltip-t-emailuser'             => 'Enviar un mensaje de correo a este usuario',
'tooltip-t-upload'                => 'Subir imágenes o archivos multimedia',
'tooltip-t-specialpages'          => 'Lista de todas las páginas especiales',
'tooltip-t-print'                 => 'Versión para imprimir de esta página',
'tooltip-t-permalink'             => 'Enlace permanente a la versión imprimible de esta página',
'tooltip-ca-nstab-main'           => 'Ver el artículo',
'tooltip-ca-nstab-user'           => 'Ver la página de usuario',
'tooltip-ca-nstab-media'          => 'Ver la página de multimedia',
'tooltip-ca-nstab-special'        => 'Esta es una página especial, no se puede editar la página en sí',
'tooltip-ca-nstab-project'        => 'Ver la página de proyecto',
'tooltip-ca-nstab-image'          => 'Ver la página de la imagen',
'tooltip-ca-nstab-mediawiki'      => 'Ver el mensaje de sistema',
'tooltip-ca-nstab-template'       => 'Ver la plantilla',
'tooltip-ca-nstab-help'           => 'Ver la página de ayuda',
'tooltip-ca-nstab-category'       => 'Ver la página de categoría',
'tooltip-minoredit'               => 'Marcar este cambio como menor',
'tooltip-save'                    => 'Guardar los cambios',
'tooltip-preview'                 => 'Previsualice sus cambios, ¡por favor, use esto antes de grabar!',
'tooltip-diff'                    => 'Muestra los cambios que ha introducido en el texto.',
'tooltip-compareselectedversions' => 'Ver las diferencias entre las dos versiones seleccionadas de esta página.',
'tooltip-watch'                   => 'Añadir esta página a su lista de seguimiento',
'tooltip-recreate'                => 'Recupere una página que ha sido borrada',

# Stylesheets
'common.css'   => '/* Los estilos CSS definidos aquí aplicarán a todas las pieles (skins) */',
'monobook.css' => '/* cambie este archivo para personalizar la piel monobook para el sitio entero */',

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Metadatos Dublin Core RDF deshabilitados en este servidor.',
'nocreativecommons' => 'Metadatos Creative Commons RDF deshabilitados en este servidor.',
'notacceptable'     => 'El servidor wiki no puede proveer los datos en un formato que su cliente (navegador) pueda entender.',

# Attribution
'anonymous'        => 'Usuario(s) anónimo(s) de {{SITENAME}}',
'siteuser'         => 'Usuario $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Esta página fue modificada por última vez en $2, $1 por $3.', # $1 date, $2 time, $3 user
'and'              => 'y',
'othercontribs'    => 'Basado en el trabajo de $1.',
'others'           => 'otros',
'siteusers'        => 'Usuario(s) $1 de {{SITENAME}}',
'creditspage'      => 'Créditos de la página',
'nocredits'        => 'Hay información de créditos para esta página.',

# Spam protection
'spamprotectiontitle'    => 'Filtro de protección contra spam',
'spamprotectiontext'     => 'La página que intenta guardar ha sido bloqueada por el filtro de spam. Esto se debe probablemente a alguno de los enlaces externos incluidos en ella.

La siguiente expresión regular define los enlaces que se encuentran bloqueados en este momento:',
'spamprotectionmatch'    => "El siguiente texto es el que activó nuestro filtro ''anti-spam'' (contra la publicidad no solicitada): $1",
'subcategorycount'       => 'Hay {{PLURAL:$1|una subcategoría|$1 subcategorías}} en esta categoría.',
'categoryarticlecount'   => 'Hay $1 {{PLURAL:$1|artículo|artículos}} en esta categoría.',
'category-media-count'   => 'Hay $1 {{PLURAL:$1|archivo|archivos}} en esta categoría.',
'listingcontinuesabbrev' => ' cont.',
'spambot_username'       => 'Limpieza de spam de MediaWiki',
'spam_reverting'         => 'Revirtiendo a la última versión que no contenga enlaces a $1',
'spam_blanking'          => 'Todas las revisiones contienen enlaces a $1, blanqueando',

# Info page
'infosubtitle'   => 'Información de la página',
'numedits'       => 'Número de ediciones (artículo): $1',
'numtalkedits'   => 'Número de ediciones (página de discusión): $1',
'numwatchers'    => 'Número de usuarios vigilándola: $1',
'numauthors'     => 'Número de autores distintos (artículo): $1',
'numtalkauthors' => 'Número de autores distintos (página de discusión): $1',

# Math options
'mw_math_png'    => 'Producir siempre PNG',
'mw_math_simple' => 'HTML si es muy simple, si no, PNG',
'mw_math_html'   => 'HTML si es posible, si no, PNG',
'mw_math_source' => 'Dejar como TeX (para navegadores de texto)',
'mw_math_modern' => 'Recomendado para navegadores modernos',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como revisado',
'markaspatrolledtext'                 => 'Marcar este artículo como revisado',
'markedaspatrolled'                   => 'Marcar como revisado',
'markedaspatrolledtext'               => 'La versión seleccionada ha sido marcada como revisada.',
'rcpatroldisabled'                    => 'Revisión de los Cambios Recientes deshabilitada',
'rcpatroldisabledtext'                => 'La capacidad de revisar los Cambios Recientes está deshabilitada en este momento.',
'markedaspatrollederror'              => 'No se puede marcar como patrullada',
'markedaspatrollederrortext'          => 'Debes especificar una revisión para marcarla como patrullada.',
'markedaspatrollederror-noautopatrol' => 'No tiene permiso para marcar sus propios cambios como revisados.',

# Patrol log
'patrol-log-page' => 'Registro de revisionesPatrol log',
'patrol-log-line' => 'revisado $1 de $2 $3',
'patrol-log-auto' => '(automático)',

# Image deletion
'deletedrevision' => 'Borrada revisión antigua $1.',

# Browsing diffs
'previousdiff' => '← Ir a diferencias anteriores',
'nextdiff'     => 'Ir a las siguientes diferencias →',

# Media information
'mediawarning'         => "'''Aviso''': Este archivo podría contener código malicioso, ejecutándolo su sistema podría resultar comprometido.<hr />",
'imagemaxsize'         => 'Limitar imágenes en las páginas de descripción a:',
'thumbsize'            => 'Tamaño de diapositivas:',
'file-info'            => '(tamaño de archivo: $1, tipo MIME: $2)',
'file-info-size'       => '($1 × $2 pixeles, tamaño de archivo: $3, tipo MIME: $4)',
'file-nohires'         => '<small>No disponible a mayor resolución.</small>',
'file-svg'             => '<small>Esta es una imagen vectorial escalable sin pérdida. Dimensiones base: $1 × $2 pixeles.</small>',
'show-big-image'       => 'Resolución completa',
'show-big-image-thumb' => '<small>Tamaño de la previsualización: $1 × $2 pixeles</small>',

'newimages'    => 'Galería de imágenes nuevas',
'showhidebots' => '($1 bots)',
'noimages'     => 'No hay nada que ver.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh'    => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr-jc' => 'sr-jc',
'variantname-sr-jl' => 'sr-jl',
'variantname-sr'    => 'sr',

'passwordtooshort' => 'Su contraseña es muy corta. Debe tener al menos $1 caracteres.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Este archivo contiene información adicional (metadatos), probablemente añadida por la cámara digital, el escáner o el programa usado para crearlo o digitalizarlo. Si el archivo ha sido modificado desde su estado original, pueden haberse perdido algunos detalles.',
'metadata-expand'   => 'Mostrar datos detallados',
'metadata-collapse' => 'Ocultar datos detallados',
'metadata-fields'   => 'Los campos de metadatos EXIF que se listan en este mensaje se mostrarán en la página de descripción de la imagen aún cuando la tabla de metadatos esté plegada. Existen otros campos que se mantendrán ocultos por defecto y que podrán desplegarse. 
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Anchura',
'exif-imagelength'                 => 'Altura',
'exif-bitspersample'               => 'Bits por componente',
'exif-compression'                 => 'Esquema de compresión',
'exif-photometricinterpretation'   => 'Composición de pixel',
'exif-orientation'                 => 'Orientación',
'exif-samplesperpixel'             => 'Número de componentes',
'exif-planarconfiguration'         => 'Distribución de datos',
'exif-ycbcrsubsampling'            => 'Razón de submuestreo de Y a C',
'exif-ycbcrpositioning'            => 'Posicionamientos Y y C',
'exif-xresolution'                 => 'Resolución horizontal',
'exif-yresolution'                 => 'Resolución vertical',
'exif-resolutionunit'              => 'Unidad de resolución X e Y',
'exif-stripoffsets'                => 'Localización de datos de imagen',
'exif-rowsperstrip'                => 'Número de filas por banda',
'exif-stripbytecounts'             => 'Bytes por banda comprimida',
'exif-jpeginterchangeformat'       => 'Desplazamiento al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes de datos JPEG',
'exif-transferfunction'            => 'Función de transferencia',
'exif-whitepoint'                  => 'Cromacidad de punto blanco',
'exif-primarychromaticities'       => 'Cromacidades primarias',
'exif-ycbcrcoefficients'           => 'Coeficientes de la matriz de transformación de espacio de color',
'exif-referenceblackwhite'         => 'Pareja de valores blanco y negro de referencia',
'exif-datetime'                    => 'Fecha y hora de modificación del archivo',
'exif-imagedescription'            => 'Título de la imagen',
'exif-make'                        => 'Fabricante de la cámara',
'exif-model'                       => 'Modelo de cámara',
'exif-software'                    => 'Software usado',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titular de los derechos de autor',
'exif-exifversion'                 => 'Versión Exif',
'exif-flashpixversion'             => 'Versión admitida de Flashpix',
'exif-colorspace'                  => 'Espacio de color',
'exif-componentsconfiguration'     => 'Significado de cada componente',
'exif-compressedbitsperpixel'      => 'Modo de compresión de la imagen',
'exif-pixelydimension'             => 'Anchura de imagen válida',
'exif-pixelxdimension'             => 'Altura de imagen válida',
'exif-makernote'                   => 'Notas del fabricante',
'exif-usercomment'                 => 'Comentarios de usuario',
'exif-relatedsoundfile'            => 'Archivo de audio relacionado',
'exif-datetimeoriginal'            => 'Fecha y hora de la generación de los datos',
'exif-datetimedigitized'           => 'Fecha y hora de la digitalización',
'exif-subsectime'                  => 'Fecha y hora (precisión por debajo del segundo)',
'exif-subsectimeoriginal'          => 'Fecha y hora de la generación de los datos (precisión por debajo del segundo)',
'exif-subsectimedigitized'         => 'Fecha y hora de la digitalización (precisón por debajo del segundo)',
'exif-exposuretime'                => 'Tiempo de exposición',
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'Número F',
'exif-exposureprogram'             => 'Programa de exposición',
'exif-spectralsensitivity'         => 'Sensibilidad espectral',
'exif-isospeedratings'             => 'Calificación de velocidad ISO',
'exif-oecf'                        => 'Factor de conversión optoelectrónica',
'exif-shutterspeedvalue'           => 'Velocidad de obturador',
'exif-aperturevalue'               => 'Apertura',
'exif-brightnessvalue'             => 'Luminosidad',
'exif-exposurebiasvalue'           => 'Sesgo de exposición',
'exif-maxaperturevalue'            => 'Valor máximo de apertura',
'exif-subjectdistance'             => 'Distancia al sujeto',
'exif-meteringmode'                => 'Modo de medición',
'exif-lightsource'                 => 'Fuente de luz',
'exif-focallength'                 => 'Longitud de la lente focal',
'exif-subjectarea'                 => 'Área del sujeto',
'exif-flashenergy'                 => 'Energía del flash',
'exif-spatialfrequencyresponse'    => 'Respuesta de frecuencia espacial',
'exif-focalplanexresolution'       => 'Resolución X plano focal',
'exif-focalplaneyresolution'       => 'Resolución Y plano focal',
'exif-focalplaneresolutionunit'    => 'Unidad de resolución del plano focal',
'exif-subjectlocation'             => 'Localización del sujeto',
'exif-exposureindex'               => 'Índice de exposición',
'exif-sensingmethod'               => 'Método de sensor',
'exif-filesource'                  => 'Fuente de archivo',
'exif-scenetype'                   => 'Tipo de escena',
'exif-cfapattern'                  => 'Patrón CFA',
'exif-customrendered'              => 'Procesador personalizado de imagen',
'exif-exposuremode'                => 'Modo de exposición',
'exif-whitebalance'                => 'Balance de blanco',
'exif-digitalzoomratio'            => 'Razón de zoom digital',
'exif-focallengthin35mmfilm'       => 'Longitud focal en película de 35 mm',
'exif-scenecapturetype'            => 'Tipo de captura de escena',
'exif-gaincontrol'                 => 'Control de escena',
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturación',
'exif-sharpness'                   => 'Agudeza',
'exif-devicesettingdescription'    => 'Descripción de los ajustes del dispositivo',
'exif-subjectdistancerange'        => 'Rango de distancia al sujeto',
'exif-imageuniqueid'               => 'ID único de imagen',
'exif-gpsversionid'                => 'Versión de la etiqueta GPS',
'exif-gpslatituderef'              => 'Latitud norte o sur',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Longitud este u oeste',
'exif-gpslongitude'                => 'Longitud',
'exif-gpsaltituderef'              => 'Refencia de altitud',
'exif-gpsaltitude'                 => 'Altitud',
'exif-gpstimestamp'                => 'Tiempo GPS (reloj atómico)',
'exif-gpssatellites'               => 'Satélites usados para la medición',
'exif-gpsstatus'                   => 'Estado del receptor',
'exif-gpsmeasuremode'              => 'Modo de medición',
'exif-gpsdop'                      => 'Precisión de medición',
'exif-gpsspeedref'                 => 'Unidad de velocidad',
'exif-gpsspeed'                    => 'Velocidad del receptor GPS',
'exif-gpstrackref'                 => 'Referencia para la dirección del movimiento',
'exif-gpstrack'                    => 'Dirección del movimiento',
'exif-gpsimgdirectionref'          => 'Referencia de la dirección de imágen',
'exif-gpsimgdirection'             => 'Dirección de imágen',
'exif-gpsmapdatum'                 => 'Utilizados datos de medición geodésica',
'exif-gpsdestlatituderef'          => 'Referencia para la latitud del destino',
'exif-gpsdestlatitude'             => 'Destino de latitud',
'exif-gpsdestlongituderef'         => 'Referencia para la longitud del destino',
'exif-gpsdestlongitude'            => 'Longitud del destino',
'exif-gpsdestbearingref'           => 'Referencia para la orientación al destino',
'exif-gpsdestbearing'              => 'Orientación del destino',
'exif-gpsdestdistanceref'          => 'Referencia para la distancia al destino',
'exif-gpsdestdistance'             => 'Distancia al destino',
'exif-gpsprocessingmethod'         => 'Nombre del método de procesado GPS',
'exif-gpsareainformation'          => 'Nombre de la área GPS',
'exif-gpsdatestamp'                => 'Fecha GPS',
'exif-gpsdifferential'             => 'Corrección diferencial de GPS',

# EXIF attributes
'exif-compression-1' => 'No comprimida',

'exif-unknowndate' => 'Fecha desconocida',

'exif-orientation-2' => 'Volteada horizontalmente', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotada 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Volteada verticalmente', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotada 90° CCW y volteada verticalmente', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotada 90° CW', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotada 90° CW y volteada verticalmente', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotada 90° CCW', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'formato panorámico',
'exif-planarconfiguration-2' => 'formato plano',

'exif-componentsconfiguration-0' => 'no existe',

'exif-exposureprogram-0' => 'No definido',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioridad de apertura',
'exif-exposureprogram-4' => 'Prioridad de obturador',
'exif-exposureprogram-5' => 'Programa creativo (con prioridad a la profundidad de campo)',
'exif-exposureprogram-6' => 'Programa de acción (alta velocidad de obturador)',
'exif-exposureprogram-7' => 'Modo retrato (para primeros planos con el fondo desenfocado)',
'exif-exposureprogram-8' => 'Modo panorama (para fotos panorámicas con el fondo enfocado)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Desconocido',
'exif-meteringmode-1'   => 'Media',
'exif-meteringmode-2'   => 'Promedio centrado',
'exif-meteringmode-3'   => 'Puntual',
'exif-meteringmode-4'   => 'Multipunto',
'exif-meteringmode-5'   => 'Patrón',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Otro',

'exif-lightsource-0'   => 'Desconocido',
'exif-lightsource-1'   => 'Luz diurna',
'exif-lightsource-2'   => 'Fluorescente',
'exif-lightsource-3'   => 'Tungsteno (luz incandescente)',
'exif-lightsource-9'   => 'Buen tiempo',
'exif-lightsource-10'  => 'Tiempo nublado',
'exif-lightsource-11'  => 'Penumbra',
'exif-lightsource-12'  => 'Fluorescente de luz diurna (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescente de día soleado (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescente blanco frío (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluroescente blanco (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Luz estándar A',
'exif-lightsource-18'  => 'Luz estándar B',
'exif-lightsource-19'  => 'Luz estándar C',
'exif-lightsource-24'  => 'Tungsteno de estudio ISO',
'exif-lightsource-255' => 'Otra fuente de luz',

'exif-focalplaneresolutionunit-2' => 'pulgadas',

'exif-sensingmethod-1' => 'No definido',
'exif-sensingmethod-2' => 'Sensor de área de color de un chip',
'exif-sensingmethod-3' => 'Sensor de área de color de dos chips',
'exif-sensingmethod-4' => 'Sensor de área de color de tres chips',
'exif-sensingmethod-5' => 'Sensor de área secuencial de color',
'exif-sensingmethod-7' => 'Sensor trilineal',
'exif-sensingmethod-8' => 'Sensor lineal secuencial de color',

'exif-scenetype-1' => 'Una imagen directamente fotografiada',

'exif-customrendered-0' => 'Proceso normal',
'exif-customrendered-1' => 'Proceso personalizado',

'exif-exposuremode-0' => 'Exposición automática',
'exif-exposuremode-1' => 'Exposición manual',
'exif-exposuremode-2' => 'Horquillado automático',

'exif-whitebalance-0' => 'Balance de blanco automático',
'exif-whitebalance-1' => 'Balance de blanco manual',

'exif-scenecapturetype-0' => 'Estándar',
'exif-scenecapturetype-1' => 'Paisaje',
'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Escena nocturna',

'exif-gaincontrol-0' => 'Ninguna',
'exif-gaincontrol-1' => 'Bajo aumento de ganancia',
'exif-gaincontrol-2' => 'Alto aumento de ganancia',
'exif-gaincontrol-3' => 'Baja disminución de ganancia',
'exif-gaincontrol-4' => 'Alta disminución de ganancia',

'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Duro',

'exif-saturation-1' => 'Baja saturación',
'exif-saturation-2' => 'Alta saturación',

'exif-sharpness-1' => 'Suave',
'exif-sharpness-2' => 'Dura',

'exif-subjectdistancerange-0' => 'Desconocida',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Vista cercana',
'exif-subjectdistancerange-3' => 'Vista lejana',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitud norte',
'exif-gpslatitude-s' => 'Latitud sur',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitud este',
'exif-gpslongitude-w' => 'Longitud oeste',

'exif-gpsstatus-a' => 'Medida en progreso',
'exif-gpsstatus-v' => 'Interoperabilidad de medida',

'exif-gpsmeasuremode-2' => 'Medición bidimensional',
'exif-gpsmeasuremode-3' => 'Medición tridimensional',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilómetros por hora',
'exif-gpsspeed-m' => 'Millas por hora',
'exif-gpsspeed-n' => 'Nudos',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Dirección real',
'exif-gpsdirection-m' => 'Dirección magnética',

# External editor support
'edit-externally'      => 'Editar este archivo usando una aplicación externa',
'edit-externally-help' => 'Ver las [http://meta.wikimedia.org/wiki/Help:External_editors instrucciones de configuración] para más información.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todos',
'imagelistall'     => 'todos',
'watchlistall1'    => 'todos',
'watchlistall2'    => 'todos',
'namespacesall'    => 'todos',

# E-mail address confirmation
'confirmemail'            => 'Confirmar dirección de correo',
'confirmemail_noemail'    => 'No ha indicado una dirección de correo válida en sus [[Special:Preferences|preferencias de usuario]].',
'confirmemail_text'       => 'Este wiki requiere que valide su dirección de correo antes de usarlo. Pulse el botón de abajo para enviar la confirmación.
El correo incluirá un enlace con un código. Introdúzcalo para confirmar la validez de su dirección.',
'confirmemail_pending'    => '<div class="error">
Ya se le ha enviado un código de confirmación. Si ha creado su cuenta recientemente, debería esperar unos minutos a que llegue antes de pedir otro código.
</div>',
'confirmemail_send'       => 'Envíar el código de confimación.',
'confirmemail_sent'       => 'Confirmación de correo enviada.',
'confirmemail_oncreate'   => 'Se le ha enviado un código de confirmación por correo. Este código no es necesario para identificarse, pero necesitará indicarlo antes de habilitar cualquier funcionalidad del wiki relacionada con el correo.',
'confirmemail_sendfailed' => 'No fue posible enviar el correo de confirmación. Por favor, compruebe que no haya caracteres inválidos en la dirección de correo indicada.

Correo devuelto: $1',
'confirmemail_invalid'    => 'Código de confirmación incorrecto. El código debe de haber expirado.',
'confirmemail_needlogin'  => 'Necesitas $1 para confirmar tu dirección electrónica.',
'confirmemail_success'    => 'Su dirección de correo ha sido confirmada. Ahora puedes registrarse y colaborar en el wiki.',
'confirmemail_loggedin'   => 'Su dirección de correo ha sido confirmada.',
'confirmemail_error'      => 'Algo salió mal al guardar su confirmación.',
'confirmemail_subject'    => 'confirmación de la dirección de correo de {{SITENAME}}',
'confirmemail_body'       => 'Alguien, probablemente usted mismo, ha registrado una cuenta "$2" con esta dirección de correo en {{SITENAME}}, desde la dirección IP $1.

Para confirmar que esta cuenta realmente le pertenece y activar el correo en {{SITENAME}}, siga este enlace:

$3

Si la cuenta no es suya, no siga el enlace. El código de confirmación expirará en $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Probar con coincidencia exacta',
'searchfulltext' => 'Buscar por texto completo',
'createarticle'  => 'Crear artículo',

# Scary transclusion
'scarytranscludedisabled' => '[Transclusión interwiki está deshabilitada]',
'scarytranscludefailed'   => '[Obtención de plantilla falló para $1; lo sentimos]',
'scarytranscludetoolong'  => '[La URL es demasiado larga; lo sentimos]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks para este artículo:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Borrar])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'El trackback se borró correctamente.',

# Delete conflict
'deletedwhileediting' => 'Aviso: ¡Esta página ha sido borrada después de que iniciase la edición!',
'confirmrecreate'     => "El usuario [[User:$1|$1]] ([[User talk:$1|discusión]]) borró este artículo después de que usted empezase a editarlo y dio esta razón: ''$2'' Por favor, confirme que realmente desea crear de nuevo el artículo.",
'recreate'            => 'Crear de nuevo',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => 'Redirigiendo a [[$1]]...',

# action=purge
'confirm_purge'        => '¿Vaciar la caché de esta página?

$1',
'confirm_purge_button' => 'Aceptar',

'youhavenewmessagesmulti' => 'Tienes nuevos mensajes en $1',

'searchcontaining' => "Buscar artículos que contengan ''$1''.",
'searchnamed'      => "Buscar artículos con este nombre ''$1''.",
'articletitles'    => "Artículos que comienzan por ''$1''",
'hideresults'      => 'Ocultar resultados',

# DISPLAYTITLE
'displaytitle' => '(Link to this page as [[$1]])',

'loginlanguagelabel' => 'Idioma: $1',

# Multipage image navigation
'imgmultipageprev'   => '← página anterior',
'imgmultipagenext'   => 'siguiente página →',
'imgmultigo'         => '¡Ir!',
'imgmultigotopre'    => 'Ir a la página',
'imgmultiparseerror' => 'La imagen parece corrupta o incorrecta, de modo que {{SITENAME}} no puede obtener una lista de páginas.',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Siguiente página',
'table_pager_prev'         => 'Página anterior',
'table_pager_first'        => 'Primera página',
'table_pager_last'         => 'Última página',
'table_pager_limit'        => 'Mostrar {{PLURAL:$1|elemento|elementos}} por página',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty'        => 'No hay resultados',

# Auto-summaries
'autosumm-blank'   => 'Removing all content from page',
'autosumm-replace' => "Replacing page with '$1'",
'autoredircomment' => 'Redireccionando a [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Nueva página: $1',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Cargando…',
'livepreview-ready'   => 'Cargando… ¡Listo!',
'livepreview-failed'  => '¡La previsualización al vuelo falló!
Prueba la previsualización normal.',
'livepreview-error'   => 'No se pudo conectar: $1 "$2"
Prueba la previsualización normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Los cambios más recientes que $1 {{PLURAL:$1|segundo|segundos} puede que no se muestren en esta lista.',
'lag-warn-high'   => 'Debido a la alta demora de la base de datos, los cambios más recientes que $1 {{PLURAL:$1|segundo|segundos} puede que no se muestren en esta lista.',

);

?>
