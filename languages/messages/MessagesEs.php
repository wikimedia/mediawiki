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
'categories'            => '{{PLURAL:$1|Categoría|Categorías}}',
'pagecategories'        => '{{PLURAL:$1|Categoría|Categorías}}',
'category_header'       => 'Artículos en la categoría "$1"',
'subcategories'         => 'Subcategorías',
'category-media-header' => 'Archivos multimedia en la categoría "$1"',
'category-empty'        => "''La categoría no contiene actualmente ningún artículo o archivo multimedia''",

'mainpagetext'      => 'Software wiki instalado con éxito.',
'mainpagedocfooter' => "Consulta la [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] para obtener información sobre el uso del software wiki.

== Empezando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

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

'retrievedfrom'           => 'Obtenido de "$1"',
'youhavenewmessages'      => 'Tiene $1 ($2).',
'newmessageslink'         => 'mensajes nuevos',
'newmessagesdifflink'     => 'dif. entre las dos últimas versiones',
'youhavenewmessagesmulti' => 'Tienes nuevos mensajes en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'editsectionhint'         => 'Editar sección: $1',
'toc'                     => 'Tabla de contenidos',
'showtoc'                 => 'mostrar',
'hidetoc'                 => 'ocultar',
'thisisdeleted'           => '¿Ver o restaurar $1?',
'viewdeleted'             => '¿Desea ver $1?',
'restorelink'             => '{{PLURAL:$1|una edición borrada|$1 ediciones borradas}}',
'feedlinks'               => 'Sindicación:',
'feed-invalid'            => 'Tipo de subscripción a sindicación de noticias inválida.',

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
'laggedslavemode'      => 'Aviso: puede que falten las actualizaciones más recientes en esta página.',
'readonly'             => 'Base de datos bloqueada',
'enterlockreason'      => 'Explique el motivo del bloqueo, incluyendo una estimación de cuándo se producirá el desbloqueo',
'readonlytext'         => 'La base de datos de {{SITENAME}} no permite nuevas entradas u otras modificaciones de forma temporal, probablemente por mantenimiento rutinario, tras de lo cual volverá a la normalidad.
La explicación dada por el administrador que la bloqueó fue:
<p>$1',
'missingarticle'       => 'La base de datos no encontró el texto de una página que debería haber encontrado, llamada "$1".

Normalmente esto se debe a que se ha seguido un enlace a una diferencia de versiones, o historial obsoletos de una página que ha sido borrada.

Si esta no es la causa, puedes haber encontrado un error en el software. Por favor, informa de esto a un administrador,
incluyendo el URL.',
'readonly_lag'         => 'La base de datos se ha bloqueado temporalmente mientras los servidores se sincronizan.',
'internalerror'        => 'Error interno',
'filecopyerror'        => 'No se pudo copiar el archivo "$1" a "$2".',
'filerenameerror'      => 'No se pudo renombrar el archivo "$1" a "$2".',
'filedeleteerror'      => 'No se pudo borrar el archivo "$1".',
'directorycreateerror' => 'No se pudo crear el directorio "$1".',
'filenotfound'         => 'No se pudo encontrar el archivo "$1".',
'unexpected'           => 'Valor inesperado: "$1"="$2".',
'formerror'            => 'Error: no se pudo enviar el formulario',
'badarticleerror'      => 'Esta acción no se puede llevar a cabo en esta página.',
'cannotdelete'         => 'No se pudo borrar la página o archivo especificada. (alguien puede haberla borrado antes)',
'badtitle'             => 'Título incorrecto',
'badtitletext'         => 'El título de la página solicitada esta vacío, no es válido, o es un enlace interlenguaje o interwiki incorrecto.',
'perfdisabled'         => 'Lo sentimos, esta función está temporalmente desactivada porque enlentece la base de datos a tal punto que nadie puede usar el wiki.',
'perfcached'           => 'Los siguientes datos están en caché y por tanto pueden estar desactualizados:',
'perfcachedts'         => 'Estos datos están almacenados. Su última actualización fue el $1.',
'querypage-no-updates' => 'Actualmente las actualizaciones de esta página están desactivadas. Estos datos no serán actualizados a corto plazo.',
'wrong_wfQuery_params' => 'Parámetros incorrectos para wfQuery()<br />
Función: $1<br />
Consulta: $2',
'viewsource'           => 'Ver código fuente',
'viewsourcefor'        => 'para $1',
'protectedpagetext'    => 'Esta página ha sido bloqueada para evitar su edición.',
'viewsourcetext'       => 'Puedes ver y copiar el código fuente de esta página:',
'protectedinterface'   => 'Esta página provee texto del interfaz del software. Está protegida para evitar vandalismos. Si cree que debería cambiarse el texto, hable con un [[{{MediaWiki:grouppage-sysop}}|Administrador]].',
'editinginterface'     => "'''Aviso:''' Estás editando una página usada para proporcionar texto a la interfaz de {{SITENAME}}. Los cambios en esta página afectarán a la apariencia de la interfaz para los demás usuarios.",
'sqlhidden'            => '(Consulta SQL oculta)',
'cascadeprotected'     => 'Esta página ha sido protegida para su edición, porque está incluida en {{PLURAL:$1|la siguiente página|las siguientes páginas}}, que están protegidas con las opción de "cascada":',
'namespaceprotected'   => "No tienes permiso para editar las páginas del espacio de nombres '''$1'''.",
'customcssjsprotected' => 'No tienes permiso para editar esta página porque contiene elementos de la configuración personal de otro usuario.',

# Login and logout pages
'logouttitle'                => 'Fin de sesión',
'logouttext'                 => 'Ha terminado su sesión.
Puede continuar navegando por {{SITENAME}} de forma anónima, o puede iniciar sesión otra vez con el mismo u otro usuario.',
'welcomecreation'            => '== ¡Bienvenido(a), $1! ==

Tu cuenta ha sido creada. No olvides personalizar [[Special:Preferences|tus preferencias]].',
'loginpagetitle'             => 'Registrarse/Entrar',
'yourname'                   => 'Su nombre de usuario',
'yourpassword'               => 'Su contraseña',
'yourpasswordagain'          => 'Repita su contraseña',
'remembermypassword'         => 'Quiero que me recuerden entre sesiones.',
'yourdomainname'             => 'Su dominio',
'externaldberror'            => 'Hubo un error de autenticación externa de la base de datos o bien no está autorizado a actualizar su cuenta externa.',
'loginproblem'               => '<b>Hubo un problema con su autenticación.</b><br />¡Inténtelo otra vez!',
'login'                      => 'Registrarse/Entrar',
'loginprompt'                => 'Necesita habilitar las <i>cookies</i> en su navegador para registrarse en {{SITENAME}}.',
'userlogin'                  => 'Registrarse/Entrar',
'logout'                     => 'Salir',
'userlogout'                 => 'Salir',
'notloggedin'                => 'No ha entrado',
'nologin'                    => '¿No tiene una cuenta? $1.',
'nologinlink'                => 'Crear una cuenta',
'createaccount'              => 'Cree una nueva cuenta',
'gotaccount'                 => '¿Ya tiene una cuenta? $1.',
'gotaccountlink'             => 'Autenticarse',
'createaccountmail'          => 'por correo electrónico',
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
'passwordtooshort'           => 'Su contraseña es muy corta. Debe tener al menos $1 caracteres.',
'mailmypassword'             => 'Envíame una nueva contraseña por correo electrónico',
'passwordremindertitle'      => 'Recordatorio de contraseña de {{SITENAME}}',
'passwordremindertext'       => 'Alguien (probablemente tú, desde la dirección IP $1) solicitó que te enviáramos una nueva contraseña para su cuenta en {{SITENAME}} ($4). 
La contraseña para el usuario "$2" es ahora "$3".
Ahora deberías iniciar sesión y cambiar tu contraseña.

Si fue otro quien solicitó este mensaje o has recordado tu contraseña y ya no deseas cambiarla, puedes ignorar este mensaje y seguir usando tu contraseña original.',
'noemail'                    => 'No hay una dirección de correo electrónico registrada para "$1".',
'passwordsent'               => 'Una nueva contraseña ha sido enviada al correo electrónico de "$1".
Por favor, identifíquese de nuevo tras recibirla.',
'blocked-mailpassword'       => 'Tu dirección IP está bloqueada, y no se te permite el uso de la función de recuperación de contraseñas para prevenir abusos.',
'eauthentsent'               => 'Un correo electrónico de confirmación ha sido enviado a la dirección especificada. Antes de que se envíe cualquier otro correo a la cuenta tienes que seguir las instrucciones enviadas en el mensaje,  para así confirmar que la dirección te pertenece.',
'throttled-mailpassword'     => 'Ya se ha enviado un recordatorio de password en las últimas $1 horas. Para evitar los abusos, solo se enviará un recordatorio de password cada $1 horas.',
'mailerror'                  => 'Error al enviar correo: $1',
'acct_creation_throttle_hit' => 'Lo sentimos, ya ha creado $1 cuentas. No puede crear otra.',
'emailauthenticated'         => 'Su dirección electrónica fue verificada en $1.',
'emailnotauthenticated'      => 'Aún no has confirmado tu dirección de correo electrónico.
Hasta que lo hagas, las siguientes funciones no estarán disponibles.',
'noemailprefs'               => '<strong>Especifique una dirección electrónica para habilitar estas características.</strong>',
'emailconfirmlink'           => 'Confirme su dirección de correo electrónico',
'invalidemailaddress'        => 'La dirección electrónica no puede ser aceptada, pues parece que tiene un formato no válido. Por favor, escribe una dirección bien formada, o vacía el campo.',
'accountcreated'             => 'Cuenta creada',
'accountcreatedtext'         => 'La cuenta de usuario para $1 ha sido creada.',
'loginlanguagelabel'         => 'Idioma: $1',

# Password reset dialog
'resetpass'               => 'Restablecer la contraseña de usuario',
'resetpass_announce'      => 'Has iniciado sesión con una contraseña temporal que fue enviada por correo electrónico. Por favor, ingresa una nueva contraseña aquí:',
'resetpass_text'          => '<!-- Añada texto aquí -->',
'resetpass_header'        => 'Restablecer contraseña',
'resetpass_submit'        => 'Cambiar la contraseña e identificarse',
'resetpass_success'       => 'Se ha cambiado su contraseña. Autenticándole...',
'resetpass_bad_temporary' => 'Contraseña temporal no válida. Puede que ya hayas cambiado tu contraseña o que hayas solicitado el envío de otra.',
'resetpass_forbidden'     => 'Imposible cambiar contraseñas en esta wiki',
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
'showdiff'                  => 'Mostrar cambios',
'anoneditwarning'           => "''Aviso:'' No has introducido tu nombre de usuario. Tu dirección IP se guardará en el historial de edición de la página.",
'missingsummary'            => "'''Atención:''' No has escrito un resumen de edición. Si haces clic nuevamente en «{{MediaWiki:Savearticle}}» tu edición se grabará sin él.",
'missingcommenttext'        => 'Por favor introduce texto debajo.',
'missingcommentheader'      => "'''Atención:''' No has escrito un título para este comentario. Si haces clic nuevamente en Grabar tu edición se grabará sin él.",
'summary-preview'           => 'Previsualización del resumen',
'subject-preview'           => 'Previsualización del tema/título',
'blockedtitle'              => 'El usuario está bloqueado',
'blockedtext'               => "<big>'''Tu nombre de usuario o dirección IP ha sido bloqueada.'''</big>

El bloqueo fue hecho por \$1. La razón dada es ''\$2''.

Puedes contactar con \$1 o con otro [[{{MediaWiki:grouppage-sysop}}|administrador]] para discutir el bloqueo.

No puedes usar el enlace \"enviar correo electrónico a este usuario\" si no has registrado una dirección válida de correo electrónico en tus [[Special:Preferences|preferencias]]. Tu dirección IP actual es \$3, y el identificador del bloqueo es #\$5. Por favor incluye uno o ambos datos en cualquier consulta que hagas.",
'autoblockedtext'           => 'Tu dirección IP ha sido bloqueada automáticamente porque era utilizada por otro ususario que fue bloqueado por $1.

La razón dada es esta:

:\'\'$2\'\'

Caducidad del bloqueo: $6


Puedes contactar con $1 o con otro de los [[{{MediaWiki:grouppage-sysop}}|administradores]] para discutir el bloqueo.

Nota que no puedes utilizar la función "Enviar correo electrónico a este usuario" a menos que tengas una dirección de correo electrónico válida registrada en tus [[Special:Preferences|preferencias de usuario]].

Tu identificador de bloqueo es $5. Por favor, incluye este identificador en cualquier petición que hagas.',
'blockedoriginalsource'     => "El código fuente de '''$1''' se muestra a continuación:",
'blockededitsource'         => "El texto de '''tus ediciones''' a '''$1''' se muestran a continuación:",
'whitelistedittitle'        => 'Se requiere identificación para editar.',
'whitelistedittext'         => 'Tienes que $1 para editar artículos.',
'whitelistreadtitle'        => 'Se requiere identificación para leer',
'whitelistreadtext'         => 'Tienes que [[Special:Userlogin|registrarte]] para leer artículos.',
'whitelistacctitle'         => 'No se le permite crear una cuenta',
'whitelistacctext'          => 'Para que se te permita crear cuentas en este wiki tienes que [[Special:Userlogin|iniciar sesión]] y tener los permisos apropiados.',
'confirmedittitle'          => 'Se requiere confirmación de dirección electrónica para editar',
'confirmedittext'           => 'Debes confirmar tu dirección electrónica antes de editar páginas. Por favor, establece y valida una dirección electrónica a través de tus [[Special:Preferences|preferencias de usuario]].',
'nosuchsectiontitle'        => 'No existe tal sección',
'nosuchsectiontext'         => 'Has intentado editar una sección que no existe. Como no hay sección $1, no hay ningún lugar donde salvar tu edición.',
'loginreqtitle'             => 'Se requiere identificación',
'loginreqlink'              => 'identificarse',
'loginreqpagetext'          => 'Debe $1 para ver otras páginas.',
'accmailtitle'              => 'La contraseña ha sido enviada.',
'accmailtext'               => "La contraseña para '$1' se ha enviado a $2.",
'newarticle'                => '(Nuevo)',
'newarticletext'            => 'Ha seguido un enlace a una página que aún no existe. Si lo que quiere es crear esta página, escriba a continuación. Para más información consulte la [[{{MediaWiki:helppage}}|página de ayuda]]. Si llegó aquí por error, vuelva a la página anterior.',
'anontalkpagetext'          => "---- ''Esta es la página de discusión de un usuario anónimo que aún no ha creado una cuenta, o no la usa. Por lo tanto, tenemos que usar su dirección IP para identificarlo. Una dirección IP puede ser compartida por varios usuarios. Si eres un usuario anónimo y crees que se han dirigido a ti con comentarios improcedentes, por favor [[Special:Userlogin|crea una cuenta o entra]] para evitar confusiones futuras con otros usuarios anónimos.''",
'noarticletext'             => 'En este momento no hay texto en esta página, puedes [[Special:Search/{{PAGENAME}}|buscar por el título de esta página]] en otras páginas o [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar esta página].',
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

<strong>Si éste es un intento legítimo de modificación, por favor, inténtelo de nuevo. Si aún entonces no funcionase, pruebe a cerrar la sesión y a ingresar de nuevo.</strong>",
'editing'                   => 'Editando $1',
'editinguser'               => 'Editando $1',
'editingsection'            => 'Editando $1 (sección)',
'editingcomment'            => 'Editando $1 (comentario)',
'editconflict'              => 'Conflicto de edición: $1',
'explainconflict'           => 'Alguien más ha cambiado esta página desde que empezaste a editarla. El área de texto superior contiene el texto de la página como existe actualmente. Tus cambios se muestran en el área de texto inferior. Si quieres grabar tus cambios, has de trasladarlos al área superior. <b>Sólo</b> el texto en el área de texto superior será grabado cuando pulses «Grabar página».<br />',
'yourtext'                  => 'Su texto',
'storedversion'             => 'Versión almacenada',
'nonunicodebrowser'         => '<strong>Atención: Su navegador no cumple la norma Unicode. Se ha activado un sistema de edición alternativo que le permitirá editar artículos con seguridad: los caracteres no ASCII aparecerán en la caja de edición como códigos hexadecimales.</strong>',
'editingold'                => '<strong>ADVERTENCIA: Estás editando una versión antigua de esta página.
Si la grabas, los cambios hechos desde esa revisión se perderán.</strong>',
'yourdiff'                  => 'Diferencias',
'copyrightwarning'          => 'Por favor observa que todas las contribuciones a {{SITENAME}} se consideran hechas públicas bajo la $2 (ver detalles en $1).Si no deseas que la gente corrija tus escritos sin piedad y los distribuya libremente, entonces no los pongas aquí. También tú nos aseguras que escribiste esto texto tú mismo y eres dueño de los derechos de autor, o lo copiaste desde el dominio público u otra fuente libre.<strong>¡NO USES ESCRITOS CON COPYRIGHT SIN PERMISO!</strong><br />',
'copyrightwarning2'         => 'Por favor, ten en cuenta que todas las contribuciones a {{SITENAME}} pueden ser editadas, modificadas o eliminadas por otros colaboradores. Si no deseas que la gente corrija tus escritos sin piedad y los distribuya libremente, entonces no los pongas aquí. <br />También tú nos aseguras que escribiste esto tú mismo y eres dueño de los derechos de autor, o lo copiaste desde el dominio público u otra fuente libre. (véase $1 para detalles). <br /><strong>¡NO USES ESCRITOS CON COPYRIGHT SIN PERMISO!</strong>',
'longpagewarning'           => '<strong>Atención: Esta página tiene un tamaño de $1 kilobytes; algunos navegadores pueden tener problemas editando páginas de 32KB o más.
Por favor considere la posibilidad de dividir esta página en secciones más pequeñas.</strong>',
'longpageerror'             => '<strong>ERROR: El texto que has enviado ocupa $1 kilobytes, lo cual es mayor que $2 kilobytes. No se puede guardar.</strong>',
'readonlywarning'           => '<strong>Atención: La base de datos ha sido bloqueada por cuestiones de mantenimiento, así que no podrá guardar sus modificaciones en este momento.
Puede copiar y pegar el texto a un archivo en su ordenador y grabarlo para más tarde.</strong>',
'protectedpagewarning'      => '<strong>ADVERTENCIA: Esta página ha sido protegida de manera que sólo usuarios con permisos de administrador pueden editarla. Asegúrate de que estás siguiendo las [[Project:Políticas de bloqueo de páginas|Políticas de bloqueo de páginas]].</strong>
__NOEDITSECTION__<h3>La edición de esta página está [[Project:Esta página está protegida|protegida]].</h3>
* Puedes opinar sobre este bloqueo en la [[{{TALKPAGENAME}}|página de discusión]] del artículo.<br />',
'semiprotectedpagewarning'  => "'''Nota:''' Esta página ha sido protegida para que sólo usuarios registrados puedan editarla.",
'cascadeprotectedwarning'   => "'''Aviso:''' Esta página está protegida, sólo los administradores pueden editarla porque está transcluida en las siguientes páginas protegidas con la opción de ''cascada'':",
'templatesused'             => 'Plantillas usadas en esta página:',
'templatesusedpreview'      => 'Plantillas usadas en esta previsualización:',
'templatesusedsection'      => 'Plantillas usadas en esta sección:',
'template-protected'        => '(protegida)',
'template-semiprotected'    => '(semiprotegida)',
'edittools'                 => '<!-- Este texto aparecerá bajo los formularios de edición y subida. -->',
'nocreatetitle'             => 'Creación de páginas limitada',
'nocreatetext'              => 'Este wiki ha restringido la posibilidad de crear nuevas páginas. Puede volver atrás y editar una página existente, [[Special:Userlogin|identificarse o crear una cuenta]].',
'recreate-deleted-warn'     => "'''Atención: está creando una página que ha sido borrada previamente.'''

Debería considerar si es apropiado continuar editando esta página.
Consulte a continuación el registro de borrados:",

# "Undo" feature
'undo-success' => 'La edición puede deshacerse. Antes de deshacer la edición, comprueba la siguiente comparación para verificar que realmente es lo que quiere hacer, y entonces guarde los cambios para así deshacer la edición.',
'undo-failure' => 'No se puede deshacer la edición ya que otro usuario ha realizado una edición intermedia.',
'undo-summary' => 'Deshecha la edición $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|disc.]])',

# Account creation failure
'cantcreateaccounttitle' => 'No se puede crear la cuenta',

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
'page_first'          => 'primeras',
'page_last'           => 'últimas',
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
'revdelete-hide-image'        => 'Ocultar el contenido del archivo',
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
'history-title'             => 'Historial de revisiones para "$1"',
'difference'                => '(Diferencias entre revisiones)',
'loadingrev'                => 'recuperando revisión para diff',
'lineno'                    => 'Línea $1:',
'editcurrent'               => 'Edite la versión actual de esta página',
'selectnewerversionfordiff' => 'Seleccione una versión más reciente para comparar',
'selectolderversionfordiff' => 'Seleccione una versión más antigua para comparar',
'compareselectedversions'   => 'Comparar versiones seleccionadas',
'editundo'                  => 'deshacer',
'diff-multi'                => '({{plural:$1|Una edición intermedia no se muestra|$1 ediciones intermedias no se muestran}}.)',

# Search results
'searchresults'         => 'Resultados de la búsqueda',
'searchresulttext'      => 'Para más información acerca de las búsquedas en {{SITENAME}}, consulte la [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'        => "Has consultado por '''[[:$1]]'''",
'searchsubtitleinvalid' => 'Para consulta "$1"',
'noexactmatch'          => "'''No existe una página llamada \"\$1\".''' Puedes [[:\$1|crearla]].",
'titlematches'          => 'Coincidencias de título de artículo',
'notitlematches'        => 'No hay coincidencias de título de artículo',
'textmatches'           => 'Coincidencias de texto de artículo',
'notextmatches'         => 'No hay coincidencias de texto de artículo',
'prevn'                 => '$1 previas',
'nextn'                 => '$1 siguientes',
'viewprevnext'          => 'Ver ($1) ($2) ($3).',
'showingresults'        => "Abajo se {{PLURAL:$1|muestra '''1''' resultado|muestran hasta '''$1''' resultados}} empezando por el nº '''$2'''.",
'showingresultsnum'     => "Abajo se {{PLURAL:$3|muestra '''1''' resultado|muestran los '''$3''' resultados}} empezando por el nº '''$2'''.",
'nonefound'             => '<strong>Nota</strong>: las búsquedas fallidas suelen producirse al buscar palabras comunes como "la" o "de", que no están en el índice, o por especificar más de una palabra a buscar (sólo las páginas
que contengan todos los términos de búsqueda aparecerán en el resultado).',
'powersearch'           => 'Búsqueda',
'powersearchtext'       => '
Buscar en espacio de nombres:<br />
$1<br />
$2 Listar redirecciones   Buscar $3 $9',
'searchdisabled'        => 'Las búsquedas en {{SITENAME}} está temporalmente deshabilitadas. Mientras tanto puede buscar mediante buscadores externos, pero tenga en cuenta que sus índices relativos a {{SITENAME}} pueden estar desactualizados.',

# Preferences page
'preferences'              => 'Preferencias',
'mypreferences'            => 'Mis preferencias',
'prefsnologin'             => 'No está identificado',
'prefsnologintext'         => 'Debes [[Special:Userlogin|entrar]] para cambiar las preferencias de usuario.',
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
'math_image_error'         => 'La conversión a PNG ha fallado; comprueba que latex, dvips, gs, y convert estén instalados correctamente',
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
'resultsperpage'           => 'Resultados por página:',
'contextlines'             => 'Número de líneas de contexto por resultado',
'contextchars'             => 'Caracteres de contexto por línea',
'recentchangesdays'        => 'Días a mostrar en cambios recientes:',
'recentchangescount'       => 'Número de títulos en cambios recientes',
'savedprefs'               => 'Sus preferencias han sido grabadas.',
'timezonelegend'           => 'Huso horario',
'timezonetext'             => 'Indique el número de horas de diferencia entre su hora local y la hora del servidor (UTC).',
'localtime'                => 'Hora local',
'timezoneoffset'           => 'Diferencia¹',
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
'userrights-reason'          => 'Motivo para el cambio:',

# Groups
'group'            => 'Grupo:',
'group-sysop'      => 'Administradores',
'group-bureaucrat' => 'Burócratas',
'group-all'        => '(todos)',

'group-sysop-member'      => 'Administrador',
'group-bureaucrat-member' => 'Burócrata',

'grouppage-bot'        => 'Project:Bot',
'grouppage-sysop'      => '{{ns:project}}:Administradores',
'grouppage-bureaucrat' => 'Project:Burócratas',

# User rights log
'rightslog'      => 'Cambios de perfil de usuario',
'rightslogtext'  => 'Este es un registro de cambios en los permisos de usuarios.',
'rightslogentry' => 'modificó los grupos a los que pertenece $1: de $2 a $3',
'rightsnone'     => 'ninguno',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|cambio|cambios}}',
'recentchanges'                     => 'Cambios recientes',
'recentchangestext'                 => 'Sigue los cambios más recientes de la wiki en esta página.',
'recentchanges-feed-description'    => 'Seguir los cambios más recientes en el wiki en este feed.',
'rcnote'                            => 'Abajo están los últimos <b>$1</b> cambios en los últimos <b>$2</b> días, actualizados $3',
'rcnotefrom'                        => 'A continuación se muestran los cambios desde <b>$2</b> (hasta <b>$1</b>).',
'rclistfrom'                        => 'Mostrar nuevos cambios desde $1',
'rcshowhideminor'                   => '$1 ediciones menores',
'rcshowhideliu'                     => '$1 usuarios registrados',
'rcshowhideanons'                   => '$1 usuarios anónimos',
'rcshowhidepatr'                    => '$1 ediciones patrulladas',
'rcshowhidemine'                    => '$1 mis ediciones',
'rclinks'                           => 'Ver los últimos $1 cambios en los últimos $2 días.<br />$3',
'diff'                              => 'dif',
'hide'                              => 'ocultar',
'show'                              => 'mostrar',
'number_of_watching_users_pageview' => '[$1 usuarios vigilando]',
'rc_categories'                     => 'Limitar a categorías (separadas por "|")',
'rc_categories_any'                 => 'Cualquiera',
'newsectionsummary'                 => 'Nueva sección: /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Cambios en enlazadas',
'recentchangeslinked-noresult' => 'No hubo cambios en las páginas enlazadas durante el periodo indicado.',
'recentchangeslinked-summary'  => "Esta página especial lista los últimos cambios en las páginas enlazadas. Las páginas en su lista de seguimiento están en '''negrita'''.",

# Upload
'upload'                      => 'Subir archivo',
'uploadbtn'                   => 'Subir un archivo',
'reupload'                    => 'Subir otra vez',
'reuploaddesc'                => 'Regresar al formulario para subir.',
'uploadnologin'               => 'No ha iniciado sesión',
'uploadnologintext'           => 'Tienes que [[Special:Userlogin|iniciar sesión]] para poder subir archivos.',
'upload_directory_read_only'  => 'El servidor web no puede escribir en el directorio de subida de archivos ($1).',
'uploaderror'                 => 'Error al intentar subir archivo',
'uploadtext'                  => "Utiliza el formulario de abajo para subir archivos, para ver o buscar imágenes subidas previamente vete a la [[Special:Imagelist|lista de archivos subidos]], las subidas y los borrados también están registrados en el [[Special:Log/upload|registro de subidas]].

Para incluir la imágen en una página, usa un enlace en el formulario '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Archivo.jpg]]</nowiki>''', '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Archivo.png|texto alternativo]]</nowiki>''' o
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Archivo.ogg]]</nowiki>''' para enlazar directamente al archivo.",
'uploadlog'                   => 'registro de subidas',
'uploadlogpage'               => 'Subidas de archivos',
'uploadlogpagetext'           => 'Abajo hay una lista de los últimos archivos subidos. Todas las horas son del servidor.',
'filename'                    => 'Nombre del archivo',
'filedesc'                    => 'Sumario',
'fileuploadsummary'           => 'Descripción:',
'filestatus'                  => 'Estado de copyright',
'filesource'                  => 'Fuente',
'uploadedfiles'               => 'Archivos subidos',
'ignorewarning'               => 'Ignorar aviso y guardar de todos modos.',
'ignorewarnings'              => 'Ignorar cualquier aviso',
'illegalfilename'             => 'El nombre de archivo «$1» contiene caracteres que no están permitidos en títulos de páginas. Por favor, renombra el archivo e intenta volver a subirlo.',
'badfilename'                 => 'El nombre de la imagen se ha cambiado a "$1".',
'filetype-badmime'            => 'No se permite subir archivos de tipo MIME "$1".',
'filetype-badtype'            => "'''\".\$1\"''' es un tipo de archivo no permitido. Lista de tipos permitidos: \$2",
'filetype-missing'            => 'El archivo no tiene extensión (como ".jpg").',
'large-file'                  => 'Se recomienda que los archivos no sean mayores de de $1; este archivo ocupa $2.',
'largefileserver'             => 'El tamaño de este archivo es mayor del que este servidor admite por configuración.',
'emptyfile'                   => 'El archivo que has intentado subir parece estar vacío; por favor, verifica que realmente se trate del archivo que intentabas subir.',
'fileexists'                  => "Ya existe un archivo con este nombre. Por favor compruebe el existente $1 si no está seguro de querer reemplazarlo.


'''Nota:''' Si finalmente sustituye el archivo, debe refrescar la caché de su navegador para ver los cambios:
*'''Mozilla''' / '''Firefox''': Pulsa el botón '''Recargar''' (o '''ctrl-r''')
*'''Internet Explorer''' / '''Opera''': '''ctrl-f5'''
*'''Safari''': '''cmd-r'''
*'''Konqueror''': '''ctrl-r''",
'fileexists-extension'        => 'Existe un archivo con un nombre similar:<br />
Nombre del archivo que se está subiendo: <strong><tt>$1</tt></strong><br />
Nombre del archivo ya existente: <strong><tt>$2</tt></strong><br />
Por favor, elige un nombre diferente.',
'fileexists-thumb'            => "'''<center>Imagen existente</center>'''",
'fileexists-thumbnail-yes'    => 'El archivo parece ser una imagen de tamaño reducido <i>(thumbnail)</i>. Por favor comprueba el archivo <strong><tt>$1</tt></strong>.<br />
Si el archivo comprobado es la misma imagen a tamaño original no es necesario subir un thumbnail más.',
'file-thumbnail-no'           => 'El nombre del archivo comienza con <strong><tt>$1</tt></strong>. Parece ser una imagen de tamaño reducido <i>(thumbnail)</i>.
Si tienes esta imagen a toda resolución súbela, si no, por favor cambia el nombre del archivo.',
'fileexists-forbidden'        => 'Ya existe un archivo con este nombre. Por favor, cambie el nombre del archivo y vuelva a subirlo. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ya existe un archivo con este nombre en el repositorio compartido; por favor, regresa a la página anterior y sube tu archivo con otro nombre. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Subida con éxito',
'uploadwarning'               => 'Advertencia de subida de archivo',
'savefile'                    => 'Guardar archivo',
'uploadedimage'               => '«[[$1]]» subido.',
'uploaddisabled'              => 'Subida de archivos deshabilitada',
'uploaddisabledtext'          => 'No es posible subir archivos en esta wiki.',
'uploadscripted'              => 'Este archivo contiene script o código HTML que puede ser interpretado erróneamente por un navegador.',
'uploadcorrupt'               => 'Este archivo está corrupto o la extensión indicada no se corresponde con el tipo de archivo. Por favor, comprueba el archivo y vuelve a subirlo.',
'uploadvirus'                 => '¡El archivo contiene un virus! Detalles: $1',
'sourcefilename'              => 'Nombre del archivo origen',
'destfilename'                => 'Nombre del archivo de destino',
'watchthisupload'             => 'Vigilar esta página',
'filewasdeleted'              => 'Un archivo con este nombre se subió con anterioridad y posteriormente ha sido borrado. Deberías revisar el $1 antes de subirlo de nuevo.',

'upload-proto-error'      => 'Protocolo incorrecto',
'upload-proto-error-text' => 'Para subir archivos desde otra página la URL debe comenzar por <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Error interno',
'upload-file-error-text'  => 'Ha ocurrido un error interno mientras se intentaba crear un fichero temporal en el servidor. Por favor, contacta con un administrador del sistema.',
'upload-misc-error'       => 'Error desconocido en la subida',
'upload-misc-error-text'  => 'Ha ocurrido un error durante la subida. Por favor verifica que la URL es válida y accesible e inténtalo de nuevo. Si el problema persiste, contacta con un administrador del sistema.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'No se pudo alcanzar la URL',
'upload-curl-error6-text'  => 'La URL no pudo ser alcanzada. Por favor comprueba que la URL es correcta y el sitio web está funcionando.',
'upload-curl-error28'      => 'Tiempo de espera excedido',
'upload-curl-error28-text' => 'La página tardó demasiado en responder. Por favor, compruebe que el servidor está funcionando, espere un poco y vuelva a intentarlo. Quizás desee intentarlo en otro momento de menos carga.',

'license'            => 'Licencia',
'nolicense'          => 'Ninguna seleccionada',
'upload_source_url'  => ' (una URL válida y accesible públicamente)',
'upload_source_file' => ' (un archivo en su ordenador)',

# Image list
'imagelist'                 => 'Lista de imágenes',
'imagelisttext'             => 'Abajo hay una lista de $1 imágenes ordenadas $2.',
'getimagelist'              => ' obteniendo la lista de imágenes',
'ilsubmit'                  => 'Búsqueda',
'showlast'                  => 'Mostrar las últimas $1 imágenes ordenadas  $2.',
'byname'                    => 'por nombre',
'bydate'                    => 'por fecha',
'bysize'                    => 'por tamaño',
'imgdelete'                 => 'borr',
'imgfile'                   => 'archivo',
'filehist-user'             => 'Usuario',
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
'imagelist_size'            => 'Tamaño (bytes)',
'imagelist_description'     => 'Descripción',
'imagelist_search_for'      => 'Buscar por nombre de imagen:',

# File reversion
'filerevert' => 'Revertir $1',

# File deletion
'filedelete'            => 'Borrar $1',
'filedelete-nofile-old' => "No existe una versión guardada de '''$1''' con los atributos especificados.",

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
'sitestatstext'          => "Hay un total de {{PLURAL:$1|'''1''' página|'''$1''' páginas}} en la base de datos.
Esto incluye páginas de discusión, páginas sobre {{SITENAME}}, esbozos mínimos, redirecciones y otras que probablemente no puedan ser consideradas páginas de contenidos.
Excluyéndolas, hay {{PLURAL:$2|1 página que, probablemente sea una página|'''$2''' páginas que, probablemente, sean páginas}} de contenido legítimo.

Hay '''$8''' {{PLURAL:$8|archivo almacenado|archivos almacenados}} en el servidor.

Desde la instalación del wiki ha habido un total de '''$3''' {{PLURAL:$3|visita|visitas}} y '''$4''' {{PLURAL:$4|edición de página|ediciones de páginas}}.
Esto resulta en un promedio de '''$5''' {{PLURAL:$5|edición|ediciones}} por página y '''$6''' {{PLURAL:$6|visita|visitas}} por edición.

La longitud de la [http://meta.wikimedia.org/wiki/Help:Job_queue cola de tareas] es de '''$7'''",
'userstatstext'          => "Hay {{PLURAL:$1|'''1''' usuario registrado|'''$1''' usuarios registrados}},
de los cuales '''$2''' (el '''$4%''') tienen privilegios de $5.",
'statistics-mostpopular' => 'Páginas más vistas',

'disambiguations'      => 'Páginas de desambiguación',
'disambiguationspage'  => 'Template:Desambiguación',
'disambiguations-text' => "Las siguientes páginas enlazan con una '''página de desambiguación'''. En lugar de ello deberían enlazar con  el tema apropiado.<br />Una página es considerada página de desambiguación si utiliza la plantilla que está enlazada desde [[MediaWiki:disambiguationspage]].",

'doubleredirects'     => 'Redirecciones dobles',
'doubleredirectstext' => '<b>Atención:</b> Esta lista puede contener falsos positivos. Eso significa usualmente que hay texto adicional con enlaces bajo el primer #REDIRECT.<br />
Cada fila contiene enlaces al segundo y tercer redirect, así como la primera línea del segundo redirect, en la que usualmente se encontrará el artículo "real" al que el primer redirect debería apuntar.',

'brokenredirects'        => 'Redirecciones incorrectas',
'brokenredirectstext'    => 'Las redirecciones siguientes enlazan a un artículo que no existe.',
'brokenredirects-edit'   => '(editar)',
'brokenredirects-delete' => '(borrar)',

'withoutinterwiki'        => 'Páginas sin interwikis',
'withoutinterwiki-header' => 'Las siguientes páginas no enlazan a versiones en otros idiomas:',

'fewestrevisions' => 'Artículos con menos ediciones',

# Miscellaneous special pages
'ncategories'             => '$1 {{PLURAL:$1|categoría|categorías}}',
'nlinks'                  => '$1 {{PLURAL:$1|enlace|enlaces}}',
'nmembers'                => '$1 {{PLURAL:$1|artículo|artículos}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisión|revisiones}}',
'nviews'                  => '$1 {{PLURAL:$1|vista|vistas}}',
'specialpage-empty'       => 'Esta página está vacía.',
'lonelypages'             => 'Páginas huérfanas',
'lonelypagestext'         => 'Ninguna página de este wiki enlaza a las listadas aquí.',
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
'deadendpagestext'        => 'Las siguientes páginas no enlazan a otras páginas de este wiki.',
'protectedpages'          => 'Páginas protegidas',
'protectedpagestext'      => 'Las siguientes páginas están protegidas para su edición o traslado',
'protectedpagesempty'     => 'Actualmente no hay ninguna página protegida con esos parámetros.',
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
'unusedimagestext'        => '<p>Por favor, ten en cuenta que otros sitios web pueden enlazar a una imagen directamente con su URL, y de esa manera no aparecer listados aquí pese a estar en uso.</p>',
'unusedcategoriestext'    => 'Las siguientes categorías han sido creadas, pero ningún artículo o categoría las utiliza.',

# Book sources
'booksources'               => 'Fuentes de libros',
'booksources-search-legend' => 'Buscar fuentes de libros',
'booksources-go'            => 'Ir',
'booksources-text'          => 'Abajo hay una lista de enlaces a otros sitios que venden libros nuevos y usados, puede que contengan más información sobre los libros que estás buscando.',

'categoriespagetext' => 'Existen las siguientes categorías en este wiki.',
'data'               => 'Datos',
'userrights'         => 'Configuración de permisos de usuarios',
'groups'             => 'Grupos de usuarios',
'alphaindexline'     => '$1 a $2',
'version'            => 'Versión',

# Special:Log
'specialloguserlabel'  => 'Usuario:',
'speciallogtitlelabel' => 'Título:',
'log'                  => 'Registros',
'all-logs-page'        => 'Todos los registros',
'log-search-legend'    => 'Buscar registros',
'log-search-submit'    => 'Ir',
'alllogstext'          => 'Vista combinada de todos los registros de {{SITENAME}}.
Puedes filtrar la vista seleccionando un tipo de registro, el nombre del usuario o la página afectada.',
'logempty'             => 'No hay elementos en el registro con esas condiciones.',
'log-title-wildcard'   => 'Buscar títulos que empiecen con este texto',

# Special:Allpages
'nextpage'          => 'Siguiente página ($1)',
'prevpage'          => 'Página anterior ($1)',
'allpagesfrom'      => 'Mostrar páginas que empiecen por:',
'allarticles'       => 'Todos los artículos',
'allinnamespace'    => 'Todas las páginas (espacio $1)',
'allnotinnamespace' => 'Todas las páginas (fuera del espacio $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Siguiente',
'allpagessubmit'    => 'Mostrar',
'allpagesprefix'    => 'Mostrar páginas con el prefijo:',
'allpagesbadtitle'  => 'El título dado era inválido o tenía un prefijo de enlace inter-idioma o inter-wiki. Puede contener uno o más caracteres que no se pueden usar en títulos.',

# Special:Listusers
'listusersfrom'      => 'Mostrar usuarios que empiecen por:',
'listusers-submit'   => 'Mostrar',
'listusers-noresult' => 'No se encontró al usuario.',

# E-mail user
'mailnologin'     => 'No enviar dirección',
'mailnologintext' => 'Debes [[Special:Userlogin|iniciar sesión]] y tener una dirección electrónica válida en tus [[Special:Preferences|preferencias]] para enviar un correo electrónico a otros usuarios.',
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
'emailccme'       => 'Enviarme una copia de mi mensaje.',
'emailccsubject'  => 'Copia de tu mensaje a $1: $2',
'emailsent'       => 'Correo electrónico enviado',
'emailsenttext'   => 'Su correo electrónico ha sido enviado.',

# Watchlist
'watchlist'            => 'Lista de seguimiento',
'mywatchlist'          => 'Lista de seguimiento',
'watchlistfor'         => "(para '''$1''')",
'nowatchlist'          => 'No tiene ninguna página en su lista de seguimiento.',
'watchlistanontext'    => 'Para ver o editar las entradas de tu lista de seguimiento es necesario $1.',
'watchnologin'         => 'No ha iniciado sesión',
'watchnologintext'     => 'Debes [[Special:Userlogin|iniciar sesión]] para modificar tu lista de seguimiento.',
'addedwatch'           => 'Añadido a la lista de seguimiento',
'addedwatchtext'       => "La página «[[:\$1]]» ha sido añadida a tu [[Special:Watchlist|lista se seguimiento]]. Cambios futuros en esta página y su página de discusión asociada se indicarán ahí, y la página aparecerá '''en negritas''' en la [[Special:Recentchanges|lista de cambios recientes]] para hacerla más fácil de detectar. <p>Cuando quieras eliminar la página de tu lista de seguimiento, presiona \"Dejar de vigilar\" en el menú.",
'removedwatch'         => 'Eliminada de la lista de seguimiento',
'removedwatchtext'     => 'La página "[[:$1]]" ha sido eliminada de su lista de seguimiento.',
'watch'                => 'Vigilar',
'watchthispage'        => 'Vigilar esta página',
'unwatch'              => 'Dejar de vigilar',
'unwatchthispage'      => 'Dejar de vigilar',
'notanarticle'         => 'No es un artículo',
'watchnochange'        => 'Ninguno de los artículos de tu lista de seguimiento fue editado en el periodo de tiempo mostrado.',
'watchlist-details'    => '$1 páginas vigiladas, sin contar las de discusión.',
'wlheader-enotif'      => '* La notificación por correo electrónico está habilitada',
'wlheader-showupdated' => "* Las páginas modificadas desde su última visita aparecen en '''negrita'''",
'watchmethod-recent'   => 'Revisando cambios recientes en busca de páginas vigiladas',
'watchmethod-list'     => 'Revisando las páginas vigiladas en busca de cambios recientes',
'watchlistcontains'    => 'Su lista de seguimiento posee $1 páginas.',
'iteminvalidname'      => "Problema con el artículo '$1', nombre inválido...",
'wlnote'               => 'A continuación se muestran los últimos $1 cambios en las últimas <b>$2</b> horas.',
'wlshowlast'           => 'Ver los cambios de las últimas $1 horas, $2 días  $3',
'watchlist-show-bots'  => 'Mostrar ediciones de bots',
'watchlist-hide-bots'  => 'Ocultar ediciones de bots',
'watchlist-show-own'   => 'Mostrar mis ediciones',
'watchlist-hide-own'   => 'Ocultar mis ediciones',
'watchlist-show-minor' => 'Mostrar ediciones menores',
'watchlist-hide-minor' => 'Esconder ediciones menores',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Vigilando...',
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

Para comunicarse con el usuario:
por correo electrónico: {{fullurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}}
en el wiki: {{fullurl:User:$PAGEEDITOR_RAWURL}}

Para recibir nuevas notificaciones de cambios de esta página, deberá visitarla nuevamente.
También puede, en su lista de seguimiento, modificar las opciones de notificación de sus
páginas vigiladas.

             El sistema de notificación de {{SITENAME}}.

--
Cambie las opciones de su lista de seguimiento en:
{{fullurl:Special:Watchlist|edit=yes}}',

# Delete/protect/revert
'deletepage'                  => 'Borrar esta página',
'confirm'                     => 'Confirmar',
'excontent'                   => "El contenido era: '$1'",
'excontentauthor'             => "El contenido era: '$1' (y el único autor fue '$2')",
'exbeforeblank'               => "El contenido antes de blanquear era: '$1'",
'exblank'                     => 'página estaba vacía',
'confirmdelete'               => 'Confirme el borrado',
'deletesub'                   => '(Borrando "$1")',
'historywarning'              => 'Atención: La página que está a punto de borrar tiene un historial:',
'confirmdeletetext'           => 'Estás a punto de borrar una página o imagen
en forma permanente,
así como todo su historial, de la base de datos.
Por favor, confirma que realmente quieres hacer eso, que entiendes las
consecuencias, y que lo estás haciendo de acuerdo con [[Project:Políticas]].',
'actioncomplete'              => 'Acción completa',
'deletedtext'                 => '"$1" ha sido borrado.
Véase $2 para un registro de los borrados recientes.',
'deletedarticle'              => 'borrado "$1"',
'dellogpage'                  => 'Registro de borrados',
'dellogpagetext'              => 'A continuación se muestra una lista de los borrados más recientes. Todos los tiempos se muestran en hora del servidor (UTC).',
'deletionlog'                 => 'registro de borrados',
'reverted'                    => 'Recuperar una revisión anterior',
'deletecomment'               => 'Motivo del borrado',
'rollback'                    => 'Revertir ediciones',
'rollback_short'              => 'Revertir',
'rollbacklink'                => 'Revertir',
'rollbackfailed'              => 'No se pudo revertir',
'cantrollback'                => 'No se pueden revertir las ediciones; el último colaborador es el único autor de este artículo.',
'alreadyrolled'               => 'No se puede revertir la última edición de [[$1]] por [[User:$2|$2]] ([[User talk:$2|discusión]]); alguien más ya ha editado o revertido esa página. La última edición fue hecha por [[User:$3|$3]] ([[User talk:$3|discusión]]).',
'editcomment'                 => 'El resumen de la edición es: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Revertidas las ediciones realizadas por [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]); a la última edición de [[User:$1|$1]]',
'rollback-success'            => 'Revertidas las ediciones de $1; recuperada la última versión de $2.',
'sessionfailure'              => 'Parece que hay un problema con tu sesión;
esta acción ha sido cancelada como medida de precaución contra secuestros de sesión.
Por favor, pulsa "Atrás", recarga la página de la que viniste e inténtalo de nuevo.',
'protectlogpage'              => 'Protecciones de páginas',
'protectlogtext'              => 'Abajo se presenta una lista de protección y desprotección de página.
Véase [[Project:Esta página está protegida]] para más información.',
'protectedarticle'            => 'protegió [[$1]]',
'modifiedarticleprotection'   => 'Cambiado el nivel de protección de "[[$1]]"',
'unprotectedarticle'          => 'desprotegió [[$1]]',
'protectsub'                  => '(Protegiendo "$1")',
'confirmprotect'              => 'Confirmar protección',
'protectcomment'              => 'Motivo de la protección',
'protectexpiry'               => 'Caducidad:',
'protect_expiry_invalid'      => 'Tiempo de caducidad incorrecto.',
'protect_expiry_old'          => 'El tiempo de expiración está en el pasado.',
'unprotectsub'                => '(Desprotegiendo "$1")',
'protect-unchain'             => 'Configurar permisos para traslados',
'protect-text'                => 'Puedes ver y modificar el nivel de protección de la página <strong>$1</strong>.',
'protect-locked-blocked'      => 'No puede cambiar los niveles de protección estando bloqueado. A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-locked-dblock'       => 'Los niveles de protección no se pueden cambiar debido a un bloqueo activo de la base de datos.
A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-locked-access'       => 'Su cuenta no tiene permiso para cambiar los niveles de protección de una página.
A continuación se muestran las opciones actuales de la página <strong>$1</strong>:',
'protect-cascadeon'           => 'Actualmente esta página está protegida porque está incluida en {{PLURAL:$1|la siguiente página|las siguientes páginas}}, que tienen activada la opción de protección en cascada. Puedes cambiar el nivel de protección de esta página, pero no afectará a la protección en cascada.',
'protect-default'             => '(por defecto)',
'protect-level-autoconfirmed' => 'Bloquear usuarios no registrados',
'protect-level-sysop'         => 'Sólo administradores',
'protect-summary-cascade'     => 'en cascada',
'protect-expiring'            => 'caduca el $1 (UTC)',
'protect-cascade'             => 'Protección en cascada - proteger todas las páginas incluidas en ésta.',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Nivel de restricción:',
'minimum-size'                => 'Tamaño mínimo',
'maximum-size'                => 'Tamaño máximo',

# Restrictions (nouns)
'restriction-edit' => 'Pueden editar',
'restriction-move' => 'Pueden trasladar',

# Restriction levels
'restriction-level-sysop'         => 'completamente protegida',
'restriction-level-autoconfirmed' => 'semiprotegida',
'restriction-level-all'           => 'cualquier nivel',

# Undelete
'undelete'                 => 'Restaurar una página borrada',
'undeletepage'             => 'Ver y restaurar páginas borradas',
'viewdeletedpage'          => 'Ver páginas borradas',
'undeletepagetext'         => 'Las siguientes páginas han sido borradas pero aún están en el archivo y pueden ser restauradas. El archivo se puede limpiar periódicamente.',
'undeleteextrahelp'        => "Para restaurar todas las revisiones, deja todas las casillas sin seleccionar y pulsa '''¡Restaurar!'''. Para restaurar sólo algunas revisiones, marca las revisiones que quieres restaurar y pulsa '''¡Restaurar!'''. Haciendo clic en al botón '''Nada''', se deseleccionarán todas las casillas y eliminará el comentario actual.",
'undeleterevisions'        => '$1 revisiones archivadas',
'undeletehistory'          => 'Si restaura una página, todas sus revisiones serán restauradas al historial. Si una nueva página con el mismo nombre ha sido creada desde que se borró la original, las versiones restauradas aparecerán como historial anterior, y la revisión actual de la página actual no se reemplazará automáticamente.',
'undeleterevdel'           => 'No se deshará el borrado si éste resulta en el borrado parcial de la última revisión de la página. En tal caso, desmarque o muestre las revisiones borradas más recientes. Las revisiones de archivos que no tiene permitido ver no se restaurarán.',
'undeletehistorynoadmin'   => 'El artículo ha sido borrado. La razón de su eliminación se indica abajo en el resumen, así como los detalles de las ediciones realizadas antes del borrado. El texto completo del artículo está disponible sólo para usuarios con permisos de administrador.',
'undelete-revision'        => 'Edición borrada de $1 de $2:',
'undeleterevision-missing' => 'Revisión no válida o perdida. Puede deberse a un enlace incorrecto,
o a que la revisión haya sido restaurada o eliminada del archivo.',
'undeletebtn'              => '¡Restaurar!',
'undeletereset'            => 'Nada',
'undeletecomment'          => 'Razón para restaurar:',
'undeletedarticle'         => 'restauró "$1"',
'undeletedrevisions'       => '{{PLURAL:$1|Una edición restaurada|$1 ediciones restauradas}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|ediciones restauradas y $2 archivo restaurado|ediciones y $2 archivos restaurados}}',
'undeletedfiles'           => '$1 {{plural:$1|archivo restaurado|archivos restaurados}}',
'cannotundelete'           => 'Ha fallado el deshacer el borrado; alguien más puede haber deshecho el borrado antes.',
'undeletedpage'            => "<big>'''Se ha restaurado $1'''</big>

Consulta el [[Special:Log/delete|registro de borrados]] para ver una lista de los últimos borrados y restauraciones.",
'undelete-header'          => 'En el [[Special:Log/delete|registro de borrados]] se listan las páginas eliminadas.',
'undelete-search-box'      => 'Buscar páginas borradas',
'undelete-search-prefix'   => 'Mostrar páginas que empiecen por:',
'undelete-search-submit'   => 'Buscar',
'undelete-no-results'      => 'No se encontraron páginas borradas para ese criterio de búsqueda.',
'undelete-error-short'     => 'Error restaurando archivo: $1',

# Namespace form on various pages
'namespace'      => 'Espacio de nombres:',
'invert'         => 'Invertir selección',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribuciones del usuario',
'mycontris'     => 'Mis contribuciones',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'No se encontraron cambios que cumplieran estos criterios.',
'ucnote'        => 'A continuación se muestran los últimos <b>$1</b> cambios de este usuario en los últimos <b>$2</b> días.',
'uclinks'       => 'Ver los últimos $1 cambios; ver los últimos $2 días.',
'uctop'         => ' (última modificación)',
'month'         => 'Desde el mes (y anterior):',
'year'          => 'Desde el año (y anterior):',

'sp-contributions-newest'      => 'Últimas',
'sp-contributions-oldest'      => 'Primeras',
'sp-contributions-newer'       => '← $1 posteriores',
'sp-contributions-older'       => '$1 previas →',
'sp-contributions-newbies'     => 'Mostrar solo las contribuciones de usuarios nuevos',
'sp-contributions-newbies-sub' => 'Para nuevos',
'sp-contributions-blocklog'    => 'Registro de bloqueos',
'sp-contributions-search'      => 'Buscar contribuciones',
'sp-contributions-username'    => 'Dirección IP o nombre de usuario:',
'sp-contributions-submit'      => 'Buscar',

'sp-newimages-showfrom' => 'Mostrar nuevas imágenes empezando por $1',

# What links here
'whatlinkshere'       => 'Lo que enlaza aquí',
'whatlinkshere-title' => 'Páginas que enlazan a $1',
'notargettitle'       => 'No hay página objetivo',
'notargettext'        => 'Especifique sobre qué página desea llevar a cabo esta acción.',
'linklistsub'         => '(Lista de enlaces)',
'linkshere'           => "Las siguientes páginas enlazan a '''[[:$1]]''':",
'nolinkshere'         => "Ninguna página enlaza con '''[[:$1]]'''.",
'nolinkshere-ns'      => "Ninguna página enlaza con '''[[:$1]]''' en el espacio de nombres elegido.",
'isredirect'          => 'página redirigida',
'istemplate'          => 'inclusión',
'whatlinkshere-prev'  => '{{PLURAL:$1|previa|previas $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|siguiente|siguientes $1}}',
'whatlinkshere-links' => '← enlaces',

# Block/unblock
'blockip'                     => 'Bloquear usuario',
'blockiptext'                 => 'Usa el formulario siguiente para bloquear el
acceso de escritura desde una dirección IP específica o un nombre de usuario.
Esto debería hacerse sólo para prevenir vandalismos, y de
acuerdo a las [[Project:Políticas|políticas de {{SITENAME}}]].
Explica la razón específica del bloqueo (por ejemplo, citando
las páginas en particular que han sido objeto de vandalismo).',
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
'ipbenableautoblock'          => 'Bloquear automáticamente la dirección IP usada por este usuario, y cualquier IP posterior desde la cual intente editar',
'ipbsubmit'                   => 'Bloquear a este usuario',
'ipbother'                    => 'Especificar caducidad',
'ipboptions'                  => '2 horas:2 hours,1 día:1 day,3 días:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 año:1 year,Para siempre:infinite',
'ipbotheroption'              => 'otro',
'ipbotherreason'              => 'Otro/adicional motivo:',
'ipbhidename'                 => 'Ocultar usuario/IP en el registro de bloqueos, la lista de bloqueos activos y la lista de usuarios',
'badipaddress'                => 'La dirección IP no tiene el formato correcto.',
'blockipsuccesssub'           => 'Bloqueo realizado con éxito',
'blockipsuccesstext'          => 'La dirección IP "$1" ha sido bloqueada. <br />Ver [[Special:Ipblocklist|lista de IP bloqueadas]] para revisar bloqueos.',
'ipb-edit-dropdown'           => 'Editar motivo del bloqueo',
'ipb-unblock-addr'            => 'Desbloquear $1',
'ipb-unblock'                 => 'Desbloquear un usuario o una IP',
'ipb-blocklist-addr'          => 'Muestra bloqueos vigentes de $1',
'ipb-blocklist'               => 'Ver bloqueos vigentes',
'unblockip'                   => 'Desbloquear usuario',
'unblockiptext'               => 'Use el formulario a continuación para devolver los permisos de escritura a una dirección IP que ha sido bloqueada.',
'ipusubmit'                   => 'Desbloquear esta dirección',
'unblocked'                   => '[[User:$1|$1]] ha sido desbloqueado',
'unblocked-id'                => 'Se ha eliminado el bloqueo $1',
'ipblocklist'                 => 'Lista de direcciones IP bloqueadas',
'ipblocklist-legend'          => 'Encontrar a un usuario bloqueado',
'ipblocklist-username'        => 'Nombre de usuario o dirección IP:',
'ipblocklist-submit'          => 'Buscar',
'blocklistline'               => '$1, $2 bloquea a $3 ($4)',
'infiniteblock'               => 'infinito',
'expiringblock'               => 'expira $1',
'anononlyblock'               => 'sólo anón.',
'noautoblockblock'            => 'Bloqueo automático deshabilitado',
'createaccountblock'          => 'Creación de cuenta bloqueada.',
'emailblock'                  => 'correo electrónico bloqueado',
'ipblocklist-empty'           => 'La lista de bloqueos está vacía.',
'ipblocklist-no-results'      => 'El nombre de usuario o IP indicado no está bloqueado.',
'blocklink'                   => 'bloquear',
'unblocklink'                 => 'desbloquear',
'contribslink'                => 'contribuciones',
'autoblocker'                 => 'Has sido bloqueado automáticamente porque tu dirección IP ha sido usada recientemente por "[[User:$1|$1]]". La razón esgrimida para bloquear a "[[User:$1|$1]]" fue "$2".',
'blocklogpage'                => 'Bloqueos de usuarios',
'blocklogentry'               => 'bloqueó a "$1" $3 durante un plazo de "$2".',
'blocklogtext'                => 'Esto es un registro de bloqueos y desbloqueos de usuarios. Las direcciones bloqueadas automáticamente no aparecen aquí. Consulte la [[Special:Ipblocklist|lista de direcciones IP bloqueadas]] para ver la lista de prohibiciones y bloqueos actualmente vigente.',
'unblocklogentry'             => 'desbloqueó a "$1"',
'block-log-flags-anononly'    => 'sólo anónimos',
'block-log-flags-nocreate'    => 'desactivada la creación de cuentas',
'block-log-flags-noautoblock' => 'bloqueo automático desactivado',
'block-log-flags-noemail'     => 'correo electrónico deshabilitado',
'range_block_disabled'        => 'La facultad de administrador de crear bloqueos por rangos está deshabilitada.',
'ipb_expiry_invalid'          => 'El tiempo de caducidad no es válido.',
'ipb_already_blocked'         => '"$1" ya se encuentra bloqueado.',
'ip_range_invalid'            => 'El rango de IP no es válido.',
'proxyblocker'                => 'Bloqueador de proxies',
'ipb_cant_unblock'            => "'''Error''': Número ID $1 de bloqueo no encontrado. Pudo haber sido desbloqueado ya.",
'proxyblockreason'            => 'Su dirección IP ha sido bloqueada porque es un proxy abierto. Por favor, contacte con su proveedor de servicios de Internet o con su servicio de asistencia técnica e infórmeles de este grave problema de seguridad.',
'proxyblocksuccess'           => 'Hecho.',
'sorbsreason'                 => 'Su dirección IP está listada como proxy abierto en DNSBL.',
'sorbs_create_account_reason' => 'Su dirección IP está listada como proxy abierto en DNSBL. No puede crear una cuenta',

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
'movepagetext'            => "Usando el siguiente formulario se renombrará una página, moviendo todo su historial al nuevo nombre.
El título anterior se convertirá en una redirección al nuevo título.
Los enlaces al antiguo título de la página no se cambiarán.
Asegúrate de no dejar redirecciones dobles o rotas.
Tú eres responsable de hacer que los enlaces sigan apuntando adonde se supone que deberían hacerlo.


Recuerda que la página '''no''' será renombrada si ya existe una página con el nuevo título, a no ser que sea una página vacía o un ''redirect'' sin historial.

Esto significa que podrás renombrar una página a su título original si has cometido un error, pero que no podrás sobreescribir una página existente.

<b>¡ADVERTENCIA!</b>
Este puede ser un cambio drástico e inesperado para una página popular;
por favor, asegúrate de entender las consecuencias que acarreará
antes de seguir adelante.",
'movepagetalktext'        => "La página de discusión asociada, si existe, será renombrada automáticamente '''a menos que:'''
*Esté moviendo la página entre espacios de nombres diferentes,
*Una página de discusión no vacía ya exista con el nombre nuevo, o
*Desactivase la opción \"Renombrar la página de discusión también\".

En estos casos, deberá trasladar manualmente el contenido de la página de discusión.",
'movearticle'             => 'Renombrar página',
'movenologin'             => 'No ha iniciado sesión',
'movenologintext'         => 'Es necesario ser usuario registrado y [[Special:Userlogin|haber iniciado sesión]] para renombrar una página.',
'newtitle'                => 'A título nuevo',
'move-watch'              => 'Vigilar este artículo',
'movepagebtn'             => 'Renombrar página',
'pagemovedsub'            => 'Renombrado realizado con éxito',
'movepage-moved'          => "<big>'''«$1» ha sido trasladado a «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Ya existe una página con ese nombre o el nombre que ha elegido no es válido. Por favor, elija otro nombre.',
'talkexists'              => 'La página fue renombrada con éxito, pero la discusión no se pudo mover porque ya existe una en el título nuevo. Por favor incorpore su contenido manualmente.',
'movedto'                 => 'renombrado a',
'movetalk'                => 'Renombrar la página de discusión también, si es aplicable.',
'talkpagemoved'           => 'La página de discusión correspondiente también fue renombrada.',
'talkpagenotmoved'        => 'La página de discusión correspondiente <strong>no</strong> fue renombrada.',
'1movedto2'               => '[[$1]] trasladada a [[$2]]',
'1movedto2_redir'         => '[[$1]] trasladada a [[$2]] sobre una redirección',
'movelogpage'             => 'Registro de traslados',
'movelogpagetext'         => 'Abajo se encuentra una lista de páginas trasladadas.',
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
'exporttext'        => 'Puedes exportar el texto y el historial de ediciones de una página en particular o de un conjunto de páginas a un texto XML. En el futuro, este texto podría importarse en otro wiki que ejecutase MediaWiki a través de [[Special:Import|importar página]].

Para exportar páginas, escribe los títulos en la caja de texto de abajo, un título por línea, y selecciona si quieres la versión actual junto a las versiones anteriores, con las líneas del historial, o sólo la versión actual con la información sobre la última edición.

En el último caso también puedes usar un enlace, por ejemplo [[Special:Export/{{Mediawiki:Mainpage}}]] para la página {{Mediawiki:Mainpage}}.',
'exportcuronly'     => 'Incluye sólo la revisión actual, no el historial de revisiones al completo.',
'exportnohistory'   => "----
'''Nota:''' Exportar el historial completo de páginas a través de este formulario ha sido deshabilitado debido a problemas de rendimiento del servidor.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Añadir páginas desde la categoría:',
'export-addcat'     => 'Añadir',

# Namespace 8 related
'allmessages'               => 'Todos los mensajes de MediaWiki',
'allmessagesname'           => 'Nombre',
'allmessagesdefault'        => 'Texto predeterminado',
'allmessagescurrent'        => 'Texto actual',
'allmessagestext'           => 'Esta es una lista de mensajes del sistema disponibles en el espacio de nombres MediaWiki:',
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
'thumbnail_invalid_params' => 'Parámetros del thumbnail no válidos',
'thumbnail_dest_directory' => 'Incapaz de crear el directorio de destino',

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
'import-revision-count'      => '$1 {{PLURAL:$1|revisión|revisiones}}',
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
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revisión|revisiones}}',
'import-logentry-interwiki'        => 'transwikificada $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisión|revisiones}} desde $2',

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
'tooltip-t-print'                 => 'Versión imprimible de la página',
'tooltip-t-permalink'             => 'Enlace permanente a esta versión de la página',
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
'tooltip-recreate'                => 'Recupera una página que ha sido borrada',

# Stylesheets
'common.css'   => '/* Los estilos CSS definidos aquí aplicarán a todas las pieles (skins) */',
'monobook.css' => '/* cambie este archivo para personalizar la piel monobook para el sitio entero */',

# Scripts
'common.js'   => '/* Cualquier código JavaScript escrito aquí se cargará para todos los usuarios en cada carga de página. */',
'monobook.js' => '/* Obsoleto y desaconsejado; usa [[MediaWiki:common.js]] */',

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
'spamprotectiontitle'  => 'Filtro de protección contra spam',
'spamprotectiontext'   => 'La página que intentas guardar ha sido bloqueada por el filtro de spam. Esto se debe probablemente a alguno de los un enlaces externos incluidos en ella.',
'spamprotectionmatch'  => "El siguiente texto es el que activó nuestro filtro ''anti-spam'' (contra la publicidad no solicitada): $1",
'subcategorycount'     => 'Hay {{PLURAL:$1|una subcategoría|$1 subcategorías}} en esta categoría.',
'categoryarticlecount' => 'Se {{PLURAL:$1|lista|listan}} $1 {{PLURAL:$1|artículo|artículos}} de esta categoría.',
'category-media-count' => 'Existe{{PLURAL:$1|&nbsp;un archivo|n $1 archivos}} en esta categoría.',
'spambot_username'     => 'Limpieza de spam de MediaWiki',
'spam_reverting'       => 'Revirtiendo a la última versión que no contenga enlaces a $1',
'spam_blanking'        => 'Todas las revisiones contienen enlaces a $1, blanqueando',

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
'markedaspatrolled'                   => 'Marcado como revisado',
'markedaspatrolledtext'               => 'La versión seleccionada ha sido marcada como revisada.',
'rcpatroldisabled'                    => 'Revisión de los Cambios Recientes deshabilitada',
'rcpatroldisabledtext'                => 'La capacidad de revisar los Cambios Recientes está deshabilitada en este momento.',
'markedaspatrollederror'              => 'No se puede marcar como patrullada',
'markedaspatrollederrortext'          => 'Debes especificar una revisión para marcarla como patrullada.',
'markedaspatrollederror-noautopatrol' => 'No tienes permisos para marcar tus propios cambios como revisados.',

# Patrol log
'patrol-log-page' => 'Registro de revisiones',
'patrol-log-line' => 'revisado $1 de $2 $3',
'patrol-log-auto' => '(automático)',

# Image deletion
'deletedrevision' => 'Borrada revisión antigua $1',

# Browsing diffs
'previousdiff' => '← Ir a diferencias anteriores',
'nextdiff'     => 'Ir a las siguientes diferencias →',

# Media information
'mediawarning'         => "'''Atención''': Este fichero puede contener código malicioso, ejecutarlo podría comprometer la seguridad de tu equipo.<hr />",
'imagemaxsize'         => 'Limitar imágenes en las páginas de descripción a:',
'thumbsize'            => 'Tamaño de las vistas en miniatura:',
'file-info'            => '(tamaño de archivo: $1; tipo MIME: $2)',
'file-info-size'       => '($1 × $2 píxeles; tamaño de archivo: $3; tipo MIME: $4)',
'file-nohires'         => '<small>No disponible a mayor resolución.</small>',
'show-big-image'       => 'Resolución original',
'show-big-image-thumb' => '<small>Tamaño de esta vista previa: $1 × $2 píxeles</small>',

# Special:Newimages
'newimages' => 'Galería de imágenes nuevas',
'noimages'  => 'No hay nada que ver.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Este archivo contiene información adicional (metadatos), probablemente añadida por la cámara digital, el escáner o el programa usado para crearlo o digitalizarlo. Si el archivo ha sido modificado desde su estado original, pueden haberse perdido algunos detalles.',
'metadata-expand'   => 'Mostrar datos detallados',
'metadata-collapse' => 'Ocultar datos detallados',
'metadata-fields'   => 'Los campos de metadatos EXIF que se listan en este mensaje se mostrarán en la página de descripción de la imagen aún cuando la tabla de metadatos esté plegada. Existen otros campos que se mantendrán ocultos por defecto. 
* Fabricante
* Modelo
* Fecha y hora de creación
* Tiempo de exposición
* Número f
* Distancia focal',

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
'edit-externally-help' => 'Lee las [http://meta.wikimedia.org/wiki/Help:External_editors instrucciones de configuración] (en inglés) para más información.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todos',
'imagelistall'     => 'todas',
'watchlistall2'    => 'todos',
'namespacesall'    => 'todos',
'monthsall'        => '(todos)',

# E-mail address confirmation
'confirmemail'            => 'Confirmar dirección E-mail',
'confirmemail_noemail'    => 'No tienes una dirección de correo electrónico válida en tus [[Special:Preferences|preferencias de usuario]].',
'confirmemail_text'       => 'Este wiki requiere que valide su dirección de correo antes de usarlo. Pulse el botón de abajo para enviar la confirmación.
El correo incluirá un enlace con un código. Introdúzcalo para confirmar la validez de su dirección.',
'confirmemail_pending'    => '<div class="error">
Ya se te ha enviado un código de confirmación; si creaste una cuenta recientemente, puede que tengas que esperar unos minutos para que te llegue antes de intentar pedir un nuevo código.
</div>',
'confirmemail_send'       => 'Envíar el código de confimación.',
'confirmemail_sent'       => 'Confirmación de correo enviada.',
'confirmemail_oncreate'   => 'Se ha enviado un código de confirmación a tu dirección de correo electrónico.
Este código no es necesario para entrar, pero necesitarás darlo antes de activar cualquier función basada en correo electrónico en el wiki.',
'confirmemail_sendfailed' => 'No fue posible enviar el correo de confirmación. Por favor, compruebe que no haya caracteres inválidos en la dirección de correo indicada.

Correo devuelto: $1',
'confirmemail_invalid'    => 'Código de confirmación incorrecto. El código debe haber expirado.',
'confirmemail_needlogin'  => 'Necesitas $1 para confirmar tu dirección electrónica.',
'confirmemail_success'    => 'Su dirección de correo ha sido confirmada. Ahora puedes registrarse y colaborar en el wiki.',
'confirmemail_loggedin'   => 'Tu dirección e-mail ha sido confirmada.',
'confirmemail_error'      => 'Algo salió mal al guardar su confirmación.',
'confirmemail_subject'    => 'confirmación de la dirección de correo de {{SITENAME}}',
'confirmemail_body'       => 'Alguien, probablemente usted mismo, ha registrado una cuenta "$2" con esta dirección de correo en {{SITENAME}}, desde la dirección IP $1.

Para confirmar que esta cuenta realmente le pertenece y activar el correo en {{SITENAME}}, siga este enlace:

$3

Si la cuenta no es suya, no siga el enlace. El código de confirmación expirará en $4.',

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
'trackbackdeleteok' => 'El trackback se borró correctamente.',

# Delete conflict
'deletedwhileediting' => 'Aviso: ¡Esta página ha sido borrada después de que iniciase la edición!',
'confirmrecreate'     => "El usuario [[User:$1|$1]] ([[User talk:$1|discusión]]) borró este artículo después de que tú empezaces a editarlo y dio esta razón: ''$2'' Por favor, confirma que realmente deseas crear de nuevo el artículo.",
'recreate'            => 'Crear de nuevo',

# HTML dump
'redirectingto' => 'Redirigiendo a [[$1]]...',

# action=purge
'confirm_purge'        => '¿Limpiar la caché de esta página?

$1',
'confirm_purge_button' => 'Aceptar',

# AJAX search
'searchcontaining' => "Buscar artículos que contengan ''$1''.",
'searchnamed'      => "Buscar artículos con este nombre ''$1''.",
'articletitles'    => "Artículos que comienzan por ''$1''",
'hideresults'      => 'Ocultar resultados',

# Multipage image navigation
'imgmultipageprev'   => '← página anterior',
'imgmultipagenext'   => 'siguiente página →',
'imgmultigo'         => '¡Ir!',
'imgmultigotopre'    => 'Ir a la página',
'imgmultiparseerror' => 'La imagen parece corrupta o incorrecta, de modo que {{SITENAME}} no puede obtener una lista de páginas.',

# Table pager
'table_pager_next'         => 'Página siguiente',
'table_pager_prev'         => 'Página anterior',
'table_pager_first'        => 'Primera página',
'table_pager_last'         => 'Última página',
'table_pager_limit'        => 'Mostrar $1 elementos por página',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty'        => 'No hay resultados',

# Auto-summaries
'autosumm-blank'   => 'Página blanqueada',
'autosumm-replace' => 'Página reemplazada por "$1"',
'autoredircomment' => 'Redireccionado a [[$1]]',
'autosumm-new'     => 'Página nueva: $1',

# Live preview
'livepreview-loading' => 'Cargando…',
'livepreview-ready'   => 'Cargando… ¡Listo!',
'livepreview-failed'  => '¡La previsualización al vuelo falló!
Prueba la previsualización normal.',
'livepreview-error'   => 'La conexión no ha sido posible: $1 "$2"
Intenta la previsualización normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Los cambios realizados en los últimos $1 segundos pueden no ser mostrados en esta lista.',
'lag-warn-high'   => 'Debido a una alta latencia el servidor de base de datos, los cambios realizados en los últimos $1 segundos pueden no ser mostrados en esta lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'Tu lista de seguimiento tiene {{PLURAL:$1|una página |$1 páginas}}, excluyendo las páginas de discusión.',
'watchlistedit-noitems'        => 'Tu lista de seguimiento está vacía.',
'watchlistedit-clear-title'    => 'Borrar lista de seguimiento',
'watchlistedit-clear-legend'   => 'Borrar lista de seguimiento',
'watchlistedit-clear-confirm'  => 'Esto eliminará todos los títulos de tu lista de seguimiento. ¿Estás seguro de que quieres hacerlo? También puedes [[Special:Watchlist/edit|eliminar los títulos individualmente]].',
'watchlistedit-clear-submit'   => 'Borrar',
'watchlistedit-clear-done'     => 'Tu lista de seguimiento ha sido borrada completamente.',
'watchlistedit-normal-title'   => 'Editar lista de seguimiento',
'watchlistedit-normal-legend'  => 'Borrar títulos de la lista de seguimiento',
'watchlistedit-normal-explain' => "Las páginas de tu lista de seguimiento se muestran debajo. Para eliminar una página, marca la casilla junto a la página, y haz clic en ''Borrar páginas''. También puedes [[Special:Watchlist/raw|editar la lista en crudo]] o [[Special:Watchlist/clear|borrarlo todo]].",
'watchlistedit-normal-submit'  => 'Borrar páginas',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 página ha sido borrada|$1 páginas han sido borradas}} de tu lista de seguimiento:',
'watchlistedit-raw-title'      => 'Editar lista de seguimiento en crudo',
'watchlistedit-raw-legend'     => 'Editar tu lista de seguimiento en modo texto',
'watchlistedit-raw-explain'    => 'Las páginas de tu lista de seguimiento se muestran debajo. Esta lista puede ser editada añadiendo o eliminando líneas de la lista; una página por línea. Cuando acabes, haz clic en Actualizar lista de seguimiento. También puedes utilizar el [[Especial:Watchlist/edit|editor estándar]].',
'watchlistedit-raw-titles'     => 'Páginas:',
'watchlistedit-raw-submit'     => 'Actualizar lista de seguimiento',
'watchlistedit-raw-done'       => 'Tu lista de seguimiento se ha actualizado.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Se ha añadido una página|Se han añadido $1 páginas}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Una página ha sido borrada|$1 páginas han sido borradas}}:',

# Watchlist editing tools
'watchlisttools-view'  => 'Ver cambios',
'watchlisttools-edit'  => 'Ver y editar tu lista de seguimiento',
'watchlisttools-raw'   => 'Editar lista de seguimiento en crudo',
'watchlisttools-clear' => 'Borrar lista de seguimiento',

);
