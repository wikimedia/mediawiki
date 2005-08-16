<?php
/** Spanish (Español)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesEs = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Especial",
	NS_MAIN           => "",
	NS_TALK           => "Discusión",
	NS_USER           => "Usuario",
	NS_USER_TALK      => "Usuario_Discusión",
	NS_PROJECT	      => $wgMetNameSpace,
	NS_PROJECT_TALK   => "{$wgMetNameSpace}_Discusión",
	NS_IMAGE          => "Imagen",
	NS_IMAGE_TALK     => "Imagen_Discusión",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki_Discusión",
	NS_TEMPLATE       => "Plantilla",
	NS_TEMPLATE_TALK  => "Plantilla_Discusión",
	NS_HELP           => "Ayuda",
	NS_HELP_TALK      => "Ayuda_Discusión",
	NS_CATEGORY       => "Categoría",
	NS_CATEGORY_TALK  => "Categoría_Discusión",
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsEs = array(
	"Ninguna", "Fija a la izquierda", "Fija a la derecha", "Flotante a la izquierda"
);

/* private */ $wgSkinNamesEs = array(
	'standard' => "Estándar",
) +  $wgSkinNamesEn;

/* private */ $wgDateFormatsEs = array();

/* private */ $wgAllMessagesEs = array(
# User Toggles

"tog-underline" => "Subrayar enlaces",
"tog-highlightbroken" => "Destacar enlaces a artículos vacíos <a href=\"\" class=\"new\">como este</a> (alternativa: como éste<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "Ajustar párrafos",
"tog-hideminor" => "Esconder ediciones menores en Cambios Recientes",
"tog-usenewrc" => "Cambios recientes realzados (no para todos los navegadores)",
"tog-numberheadings" => "Auto-numerar encabezados",
"tog-showtoolbar" => "Mostrar barra de edición",
"tog-rememberpassword" => "Recordar la contraseña entre sesiones",
"tog-editwidth" => "La caja de edición tiene el ancho máximo",
"tog-editondblclick" => "Editar páginas con doble clic (JavaScript)",
"tog-editsection"=>"Habilitar la edición de secciones usando el enlace [editar]",
"tog-editsectiononrightclick"=>"Habilitar la edición de secciones presionando el botón de la derecha<br /> en los títulos de secciones (JavaScript)",
"tog-showtoc"=>"Mostrar la tabla de contenidos<br />(para paginas con más de 3 encabezados)",
"tog-watchdefault" => "Vigilar artículos nuevos y modificados",
"tog-minordefault" => "Marcar todas las ediciones como menores por defecto",
"tog-previewontop" => "Mostrar la previsualización antes de la caja de edición en lugar de después",
"tog-nocache"=> "Inhabilitar el ''cache'' de páginas",

# Dates
'sunday' => 'Domingo',
'monday' => 'Lunes',
'tuesday' => 'Martes',
'wednesday' => 'Miércoles',
'thursday' => 'Jueves',
'friday' => 'Viernes',
'saturday' => 'Sábado',
'january' => 'enero',
'february' => 'febrero',
'march' => 'marzo',
'april' => 'abril',
'may_long' => 'mayo',
'june' => 'junio',
'july' => 'julio',
'august' => 'agosto',
'september' => 'septiembre',
'october' => 'octubre',
'november' => 'noviembre',
'december' => 'diciembre',
'jan' => 'ene',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'abr',
'may' => 'may',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'ago',
'sep' => 'sep',
'oct' => 'oct',
'nov' => 'nov',
'dec' => 'dic',
# Bits of text used by many pages:
#
"linktrail"     => "/^((?:[a-z]|á|é|í|ó|ú|ñ)+)(.*)\$/sD",
"mainpage"		=> "Portada",
"mainpagetext"	=> "Software wiki instalado con éxito.",
"about"			=> "Acerca de",
"aboutsite"      => "Acerca de {{SITENAME}}",
"aboutpage"		=> "{{ns:project}}:Acerca de",
"help"			=> "Ayuda",
"helppage"		=> "{{ns:project}}:Ayuda",
"wikititlesuffix"		=>"{{SITENAME}}",
"bugreports"	=> "Informes de error de software",
"bugreportspage" => "{{ns:project}}:Informes_de_error",

"faq"			=> "FAQ",
"faqpage"		=> "{{ns:project}}:FAQ",
"edithelp"		=> "Ayuda de edición",
"edithelppage"	=> "{{ns:project}}:Cómo_se_edita_una_página",
"cancel"		=> "Cancelar",
"qbfind"		=> "Encontrar",
"qbbrowse"		=> "Hojear",
"qbedit"		=> "Editar",
"qbpageoptions" => "Opciones de página",
"qbpageinfo"	=> "Información de página",
"qbmyoptions"	=> "Mis opciones",
"mypage"		=> "Mi página",
"mytalk"        => "Mi discusión",
"currentevents" => "Actualidad",
"errorpagetitle" => "Error",
"returnto"		=> "Regresa a $1.",
"tagline"      	=> "De {{SITENAME}}, la enciclopedia libre.",
"whatlinkshere"	=> "Páginas que enlazan aquí",
"help"			=> "Ayuda",
"search"		=> "Buscar",
"go"		=> "Ir",
"history"		=> "Historial",
"printableversion" => "Versión para imprimir",
"editthispage"	=> "Editar esta página",
"deletethispage" => "Borrar esta página",
"protectthispage" => "Proteger esta página",
"unprotectthispage" => "Desproteger esta página",

"newpage" => "Página nueva",
"talkpage"		=> "Discutir esta página",
"postcomment" => "Poner un comentario",
"articlepage"   => "Ver artículo",
"subjectpage"	=> "Artículo",
"userpage" => "Ver página de usuario",
"wikipediapage" => "Ver página meta",
"imagepage" => 	"Ver página de imagen",
"viewtalkpage" => "Ver discusión",
"otherlanguages" => "Otros idiomas",
"redirectedfrom" => "(Redirigido desde $1)",
"lastmodified"	=> "Esta página fue modificada por última vez el $1.",
"viewcount"		=> "Esta página ha sido visitada $1 veces.",
"printsubtitle" => "(De {{SERVER}})",
"protectedpage" => "Página protegida",
"administrators" => "{{ns:project}}:Administradores",
"sysoptitle"	=> "Acceso de Administrador requerido",
"sysoptext"		=> "La acción que has requerido sólo puede ser llevada a cabo
 por usuarios con status de administrador.
Ver $1.",
"developertitle" => "Acceso de developer requerido",
"developertext"	=> "La acción que has requerido sólo puede ser llevada a cabo
por usuarios con status de \"developer\".
Ver $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Ir",
"ok"			=> "OK",
"sitetitle"		=> "{{SITENAME}}",
"sitesubtitle"	=> "La Enciclopedia Libre",
"retrievedfrom" => "Obtenido de \"$1\"",
"newmessages" => "Tienes $1.",
"newmessageslink" => "mensajes nuevos",
"editsection" =>"editar",
"toc" => "Tabla de contenidos",
"showtoc" => "mostrar",
"hidetoc" => "esconder",
"thisisdeleted" => "¿Ver o restaurar $1?",
"restorelink" => "$1 ediciones borradas",


# Main script and global functions
#
"nosuchaction"	=> "No existe tal acción",
"nosuchactiontext" => "La acción especificada por el URL no es
 reconocida por el software de {{SITENAME}}",
"nosuchspecialpage" => "No existe esa página especial",
"nospecialpagetext" => "Has requerido una página especial que no es
 reconocida por el software de {{SITENAME}}.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Error de la base de datos",
"dberrortext"	=> "Ha ocurrido un error de sintaxis en una consulta
a la base de datos.
La última consulta que se intentó fue:
<blockquote><tt>$1</tt></blockquote>El error de retorno de
MySQL fue\"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Ha ocurrido un error de sintaxis en una consulta
a la base de datos.
La última consulta que se intentó fue:
\"$1\"
desde la función \"$2\".
MySQL retornó el error \"$3: $4\".\n",
"noconnect"		=> "No se pudo conectar a la base de datos en $1",
"nodb"			=> "No se pudo seleccionar la base de datos $1",
"readonly"		=> "Base de datos bloqueada",
"cachederror"	=> "Esta es una copia guardada en el cache de la página requerida, y puede no estar actualizada.",
"enterlockreason" => "Explica el motivo del bloqueo, incluyendo una estimación de cuándo se producirá el desbloqueo",
"readonlytext"	=> "La base de datos de {{SITENAME}} está temporalmente
bloqueada para nuevas entradas u otras modificaciones, probablemente
para mantenimiento de rutina, después de lo cual volverá a la normalidad.
El administrador que la bloqueó ofreció esta explicación:
<p>$1",
"missingarticle" => "La base de datos no encontró el texto de una
página que debería haber encontrado, llamada \"$1\".

<p>Esto es causado usualmente por seguir un enlace a una diferencia de páginas o historial obsoleto a una página que ha sido borrada.

<p>Si esta no es la causa, puedes haber encontrado un error en el software. Por favor, informa de esto a un administrador,
incluyendo el URL.",
"internalerror" => "Error interno",
"filecopyerror" => "No se pudo copiar el archivo \"$1\" a \"$2\".",
"filerenameerror" => "No se pudo renombrar el archivo \"$1\" a \"$2\".",

"filedeleteerror" => "No se pudo borrar el archivo \"$1\".",
"filenotfound"	=> "No se pudo encontrar el archivo \"$1\".",
"unexpected"	=> "Valor no esperado: \"$1\"=\"$2\".",
"formerror"		=> "Error: no se pudo enviar el formulario",
"badarticleerror" => "Esta acción no se puede llevar a cabo en esta página.",
"cannotdelete"	=> "No se pudo borrar la página o imagen especificada. (Puede haber sido borrada por alguien antes)",
"badtitle"		=> "Título incorrecto",
"badtitletext"	=> "El título de la página requerida era incorrecto, vacío, o un enlace interleguaje o interwiki incorrecto.",

"perfdisabled" => "Lo siento, esta función está temporalmente desactivada porque enlentece la base de datos a tal punto que nadie puede usar el wiki. Será reescrita para mayor eficiencia en el futuro) probablemente por ti!",
"perfdisabledsub" => "Aquí hay una copia grabada de $1:",

# Login and logout pagesítulo
"logouttitle"	=> "Fin de sesión",
"logouttext"	=> "Has terminado tu sesión.
Puedes continuar usando {{SITENAME}} en forma anónima, o puedes
iniciar sesión otra vez como el mismo u otro usuario.\n",

"welcomecreation" => "<h2>Bienvenido(a), $1!</h2><p>Tu cuenta ha sido creada.
No olvides personalizar tus preferencia de {{SITENAME}}.",

"loginpagetitle" => "Registrarse/Entrar",
"yourname"		=> "Tu nombre de usuario",
"yourpassword"	=> "Tu contraseña",
"yourpasswordagain" => "Repite tu contraseña",
"newusersonly"	=> " (sólo usuarios nuevos)",
"remembermypassword" => "Quiero que recuerden mi contraseña entre sesiones.",
"loginproblem"	=> "<b>Hubo un problema con tu entrada.</b><br />¡Inténtalo otra vez!",
"alreadyloggedin" => "<strong>Usuario $1, ya entraste!</strong><br />\n",

"login"			=> "Registrarse/Entrar",
"userlogin"		=> "Registrarse/Entrar",
"logout"		=> "Salir",
"userlogout"	=> "Salir",
"notloggedin"	=> "No has entrado",
"createaccount"	=> "Crea una nueva cuenta",
"badretype"		=> "Las contraseñas que ingresaste no concuerdan.",
"userexists"	=> "El nombre que entraste ya está en uso. Por favor, elije un nombre diferente.",
"youremail"		=> "Tu dirección electrónica (e-mail)",
"yournick"		=> "Tu apodo (para firmas)",
"emailforlost"	=> "Ingresar una dirección electrónica es opcional, pero permite a los demás usuarios contactarse contigo a través del sitio web sin tener que revelarles tu dirección electrónica. Además, si pierdes u olvidas tu contraseña, puedes pedir que se te envíe una nueva.",
"loginerror"	=> "Error de inicio de sesión",
"noname"		=> "No has especificado un nombre de usuario válido.",
"loginsuccesstitle" => "Inicio de sesión exitoso",
"loginsuccess"	=> "Has iniciado tu sesión en {{SITENAME}} como \"$1\".",
"nosuchuser"	=> "No existe usuario alguno llamado \"$1\".
Revisa tu escritura, o usa el formulario de abajo para crear una nueva cuenta de usuario.",
"wrongpassword"	=> "La contraseña que ingresaste es incorrecta. Por favor inténtalo de nuevo.",
"mailmypassword" => "Envíame una nueva contraseña por correo electrónico",
"passwordremindertitle" => "Recordatorio de contraseña de {{SITENAME}}",
"passwordremindertext" => "Alguien (probablemente tú, desde la dirección IP $1)
solicitó que te enviáramos una nueva contraseña para iniciar sesión en {{SITENAME}}.
La contraseña para el usuario \"$2\" es ahora \"$3\".
Ahora deberías iniciar sesión y cambiar tu contraseña.",
"noemail"		=> "No hay dirección electrónica (e-mail) registrada para el(la) usuario(a) \"$1\".",
"passwordsent"	=> "Una nueva contraseña ha sido enviada a la dirección electrónica registrada para \"$1\".
Por favor entra otra vez después de que la recibas.",

# Edit pages
#
"summary"		=> "Resumen",
"subject" => "Tema/título",
"minoredit"		=> "Esta es una edición menor",
"watchthis"		=> "Vigilar este artículo",
"savearticle"	=> "Grabar la página",
"preview"		=> "Previsualizar",
"showpreview"	=> "Mostrar previsualización",
"blockedtitle"	=> "El usuario está bloqueado",
"blockedtext"	=> "Tu nombre de usuario o dirección IP ha sido bloqueada por $1.
La razón dada es la que sigue:<br />$2<p> Puedes contactar a $1 o a otro de los [[{{ns:project}}:Administradores|administradores]] para
discutir el bloqueo.",
"newarticle"	=> "(Nuevo)",
"newarticletext" => "{{SITENAME}} es una enciclopedia en desarrollo, y esta página aún no existe. Puedes pedir información en [[{{ns:project}}:Consultas]], pero no esperes una respuesta pronta. Si lo que quieres es crear esta página, empieza a escribir en la caja que sigue. Si llegaste aquí por error, presiona la tecla para volver a la página anterior de tu navegador.",
"anontalkpagetext" => "---- ''Esta es la página de discusión para un usuario anónimo que aún no ha creado una cuenta, o no la usa. Por lo tanto, tenemos que usar su [[dirección IP]] numérica para identificarlo. Una dirección IP puede ser compartida por varios usuarios. Si eres un usuario anónimo y sientes que comentarios irrelevantes han sido dirigidos a ti, por favor [[Especial:Userlogin|crea una cuenta o entra]] para evitar confusiones futuras con otros usuarios anónimos.'' ",
"noarticletext" => "(En este momento no hay texto en esta página)",

"updated"		=> "(Actualizado)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Recuerda que esto es sólo una previsualización, y no ha sido grabada todavía!",
"previewconflict" => "Esta previsualización refleja el texto en el área
de edición superior como aparecerá si eliges grabar.",
"editing"		=> "Editando $1",
"editingsection"	=> "Editando $1 (sección)",
"editingcomment"	=> "Editando $1 (comentario)",
"editconflict"	=> "Conflicto de edición: $1",
"explainconflict" => "Alguien más ha cambiado esta página desde que empezaste
a editarla.
El área de texto superior contiene el texto de la página como existe
actualmente. Tus cambios se muestran en el área de texto inferior.
Vas a tener que incorporar tus cambios en el texto existente.
<b>Sólo</b> el texto en el área de texto superior será grabado cuando presiones
 \"Grabar página\".<br />",
"yourtext"		=> "Tu texto",
"storedversion" => "Versión almacenada",
"editingold"	=> "<strong>ADVERTENCIA: Estás editando una versión antigua
 de esta página.
Si la grabas, los cambios hechos desde esa revisión se perderán.</strong>",
"yourdiff"		=> "Diferencias",
"copyrightwarning" => "Ayuda de edición, caracteres especiales: á é í ó ú Á É Í Ó Ú ü Ü ñ Ñ ¡ ¿ <br /><br />Nota por favor que todas las contribuciones a {{SITENAME}}
se consideran hechas públicas bajo la Licencia de Documentación Libre GNU
(ver detalles en $1).
 Si no deseas que la gente corrija tus escritos sin piedad
y los distribuya libremente, entonces no los pongas aquí. <br />
También tú nos aseguras que escribiste esto tú mismo y
eres dueño de los derechos de autor, o lo copiaste desde el dominio público
u otra fuente libre.
 <strong>¡NO USES ESCRITOS CON COPYRIGHT SIN PERMISO!</strong><br />",
"longpagewarning" => "<strong>ADVERTENCIA: Esta página tiene un tamaño de $1 kilobytes; algunos navegadores pueden tener problemas editando páginas de 32kb o más.
Por favor considera la posibilidad de descomponer esta página en secciones más pequeñas.</strong>",
"readonlywarning" => "<strong>ADVERTENCIA: La base de datos ha sido bloqueada para mantenimiento, así que no podrás grabar tus modificaciones en este momento.
Puedes \"cortar y pegar\" a un archivo de texto en tu computador, y grabarlo para
intentarlo después.</strong>",
"protectedpagewarning" => "<strong>ADVERTENCIA: Esta página ha sido bloqueada de manera que s&ocute;lo usuarios con privilegios de administrador pueden editarla. Asegúrate de que estás siguiendo las
[[Project:Guías_para_páginas_protegidas|guías para páginas protegidas]].</strong>",

# History pages
#
"revhistory"	=> "Historial de revisiones",
"nohistory"		=> "No hay un historial de ediciones para esta página.",
"revnotfound"	=> "Revisión no encontrada",
"revnotfoundtext" => "La revisión antigua de la página por la que preguntaste no se pudo encontrar.
Por favor revisa el URL que usaste para acceder a esta página.\n",
"loadhist"		=> "Recuperando el historial de la página",
"currentrev"	=> "Revisión actual",
"revisionasof"	=> "Revisión de $1",
"cur"			=> "act",
"next"			=> "sig",
"last"			=> "prev",
"orig"			=> "orig",
"histlegend"	=> "Simbología: (act) = diferencia con la versión actual,
(prev) = diferencia con la versión previa, M = edición menor",

# Diffs
#
"difference"	=> "(Diferencia entre revisiones)",
"loadingrev"	=> "recuperando revisión para diff",
"lineno"		=> "Línea $1:",
"editcurrent"	=> "Edita la versión actual de esta página",

# Search results
#
"searchresults" => "Resultados de búsqueda",
"searchresulttext" => "Para más información acerca de búsquedas en {{SITENAME}}, ve a [[Project:Búsqueda|Buscando en {{SITENAME}}]].",
"searchquery"	=> "Para consulta \"$1\"",

"badquery"		=> "Consulta de búsqueda formateada en forma incorrecta",
"badquerytext"	=> "No pudimos procesar tu búsqueda.
Esto es probablemente porque intentaste buscar una palabra de menos de tres letras, lo que todavía no es posible.
También puede ser que hayas cometido un error de escritura en la expresión.
Por favor, intenta una búsqueda diferente.",
"matchtotals"	=> "La consulta \"$1\" coincidió con $2 títulos de artículos
y el texto de $3 artículos.",
"nogomatch" => "No existe ninguna página con exactamente este título, estamos intentando una búsqueda en todo el texto.",
"titlematches"	=> "Coincidencias de título de artículo",
"notitlematches" => "No hay coincidencias de título de artículo",
"textmatches"	=> "Coincidencias de texto de artículo",
"notextmatches"	=> "No hay coincidencias de texto de artículo",
"prevn"			=> "$1 previos",
"nextn"			=> "$1 siguientes",
"viewprevnext"	=> "Ver ($1) ($2) ($3).",
"showingresults" => "Mostrando abajo <b>$1</b> resultados empezando con #<b>$2</b>.",
"showingresultsnum" => "Mostrando abajo  <b>$3</b> resultados comenzando con #<b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: búsquedas no exitosas son causadas a menudo
por búsquedas de palabras comunes como \"la\" o \"de\",
que no están en el índice, o por especificar más de una palabra para buscar (sólo las páginas
que contengan todos los términos de una búsqueda aparecerán en el resultado).",
"powersearch" => "Búsqueda",
"powersearchtext" => "
Buscar en espacios de nombre :<br />
$1<br />
$2 Listar redirecciones   Buscar $3 $9",
"searchdisabled" => "<p>Búsqueda en todo el texto ha sido desactivada temporalmente
debido a carga alta del servidor; esperamos tenerla otra vez en linea después de algunas actualizaciones de
soporte físico próximas.",
"blanknamespace" => "(Principal)",
# Preferences page
#
"preferences"	=> "Preferencias",
"prefsnologin" => "No has entrado",
"prefsnologintext"	=> "Debes [[Especial:Userlogin|entrar]]
para seleccionar preferencias de usuario.",
"prefslogintext" => "Has entrado con el nombre \"$1\".
Tu número de identificación interno es $2.",
"prefsreset"	=> "Las preferencias han sido repuestas a sus valores almacenados.",
"qbsettings"	=> "Preferencias de \"Quickbar\"",
"changepassword" => "Cambiar contraseña",
"skin"			=> "Piel",
"math"			=> "Cómo se muestran las fórmulas",
"dateformat"	=> "Formato de fecha",
"math_failure"		=> "No se pudo entender",
"math_unknown_error"	=> "error desconocido",
"math_unknown_function"	=> "función desconocida",
"math_lexing_error"	=> "error de léxico",
"math_syntax_error"	=> "error de sintaxis",
"saveprefs"		=> "Grabar preferencias",
"resetprefs"	=> "Volver a preferencias por defecto",
"oldpassword"	=> "Contraseña antigua",
"newpassword"	=> "Contraseña nueva",
"retypenew"		=> "Reescriba la nueva contraseña",
"textboxsize"	=> "Dimensiones del área de texto",
"rows"			=> "Filas",
"columns"		=> "Columnas",
"searchresultshead" => "Preferencias de resultado de búsqueda",
"resultsperpage" => "Resultados para mostrar por página",
"contextlines"	=> "Líneas para mostrar por resultado",
"contextchars"	=> "Caracteres de contexto por línea",
"stubthreshold" => "Umbral de artículo mínimo" ,
"recentchangescount" => "Número de títulos en cambios recientes",
"savedprefs"	=> "Tus preferencias han sido grabadas.",
'timezonelegend' => "Huso horario",
"timezonetext"	=> "Entra el número de horas de diferencia entre tu hora local
y la hora del servidor (UTC).",
"localtime"	=> "Hora local",
"timezoneoffset" => "Diferencia",
"servertime"	=> "La hora en el servidor es",
"guesstimezone" => "Obtener la hora del navegador",
"emailflag"     => "No quiero recibir correo electrónico de otros usuarios",
"defaultns"		=> "Buscar en estos espacios de nombres por defecto:",

# Recent changes
#
"changes" => "cambios",
"recentchanges" => "Cambios recientes",
"recentchangestext" => "Sigue los cambios más recientes a {{SITENAME}} en esta página.
¡[[{{ns:project}}:Bienvenidos|Bienvenidos]]!
Por favor, lee estas páginas: [[{{ns:project}}:FAQ|{{SITENAME}} FAQ]],
[[{{ns:project}}:Políticas y guías|políticas de {{SITENAME}}]]
(especialmente [[{{ns:project}}:Convenciones de nombres|las convenciones para nombrar artículos]] y
[[{{ns:project}}:Punto de vista neutral|punto de vista neutral]]).

Si quieres que {{SITENAME}} tenga éxito, es muy importante que no agregues
material restringido por [[{{ns:project}}:Copyrights|derechos de autor]]. La responsabilidad legal realmente podría dañar el proyecto, así que por favor no lo hagas.",
"rcloaderr"		=> "cargando cambios recientes",
"rcnote"		=> "Abajo están los últimos <b>$1</b> cambios en los últimos <b>$2</b> días.",
"rclistfrom"	=> "Mostrar cambios nuevos desde $1",
"rcnotefrom"	=> "Abajo están los cambios desde <b>$2</b> (se muestran hasta <b>$1</b>).",
"rclinks"		=> "Ver los últimos $1 cambios en los últimos $2 días.",
"rchide"		=> "en forma $4 ; $1 ediciones menores; $2 espacios de nombre secundarios; $3 ediciones múltiples.",
"rcliu"			=> "; $1 ediciones de usuarios en sesión",
"diff"			=> "dif",
"hist"			=> "hist",
"hide"			=> "esconder",
"show"			=> "mostrar",
"tableform"             => "tabla",
"listform"		=> "lista",
"nchanges"		=> "$1 cambios",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Subir",
"uploadbtn"		=> "Subir un archivo",
"uploadlink"	=> "Subir imágenes",
"reupload"		=> "Subir otra vez",
"reuploaddesc"	=> "Regresar al formulario para subir.",
"uploadnologin" => "No has iniciado sesión",
"uploadnologintext"	=> "Tú debes [[Especial:Userlogin|iniciar sesión]]
para subir archivos.",
"uploaderror"	=> "Error tratando de subir",
"uploadtext"	=> "Para ver o buscar imágenes que se hayan subido
previamente, ve a la [[Especial:Imagelist|lista de imágenes subidas]].
Los archivos subidos y borrados son registrados en el
[[Project:Registro de subidas|registro de subidas]].
Consulta también la [[Project:Política de uso de imágenes|política de uso de imágenes]].

Usa el formulario siguiente para subir nuevos archivos de imágenes que
vas a usar para ilustrar tus artículos.
En la mayoría de los navegadores, verás un botón \"Browse...\", que
abrirá el diálogo de selección de archivos estándar de tu sistema operativo.
Cuando hayas elegido un archivo, su nombre aparecerá en el campo de texto
al lado del botón \"Examinar...\".
También debes marcar la caja afirmando que no estás
violando ningún copyright al subir el archivo.
Presiona el botón \"Subir\" para completar la subida.
Esto puede tomar algún tiempo si tienes una conexión a Internet lenta.

Los formatos preferidos son JPEG para imágenes fotográficas, PNG
para dibujos y diagramas, y OGG para sonidos.
Por favor, dale a tus archivos nombres descriptivos para evitar confusiones.
Para incluir la imagen en un artículo, usa un enlace de la forma
'''<nowiki>[[imagen:archivo.jpg]]</nowiki>''' o
'''<nowiki>[[imagen:archivo.png|alt text]]</nowiki>''' o
'''<nowiki>[[media:archivo.ogg]]</nowiki>''' para sonidos.

Por favor recuerda que, al igual que con las páginas {{SITENAME}}, otros pueden
editar o borrar los archivos que has subido si piensan que es bueno para
la enciclopedia, y se te puede bloquear, impidiéndote subir más archivos si abusas del sistema.",
"uploadlog"		=> "registro de subidas",
"uploadlogpage" => "Registro_de_subidas",
"uploadlogpagetext" => "Abajo hay una lista de los archivos que se han
subido más recientemente. Todas las horas son del servidor (UTC).
<ul>
</ul>
",
"filename"		=> "Nombre del archivo",
"filedesc"		=> "Sumario",
"copyrightpage" => "{{ns:projec}}:Copyrights",
"copyrightpagename" => "{{SITENAME}} copyright",
"uploadedfiles"	=> "Archivos subidos",
"ignorewarning"	=> "Ignora la advertencia y graba el archivo de todos modos.",
"minlength"		=> "Los nombres de imágenes deben ser al menos de tres letras.",
"badfilename"	=> "El nombre de la imagen se ha cambiado a \"$1\".",
"badfiletype"	=> "\".$1\" no es un formato de imagen recomendado.",
#"largefile"		=> "Se recomienda que las imágenes no excedan 100k de tamaño.",
"successfulupload" => "Subida exitosa",
"fileuploaded"	=> "El archivo \"$1\" se subió en forma exitosa.
Por favor sigue este enlace: ($2) a la página de descripción y escribe
la información acerca del archivo, tal como de dónde viene, cuándo fue
creado y por quién, y cualquier otra cosa que puedas saber al respecto.",
"uploadwarning" => "Advertencia de subida de archivo",
"savefile"		=> "Grabar archivo",
"uploadedimage" => "\"[[$1]]\" subido.",
"uploaddisabled" => "Lo sentimos, subir archivos ha sido desactivado en este servidor.",
# Image list
#
"imagelist"		=> "Lista de imágenes",
"imagelisttext"	=> "Abajo hay una lista de $1 imágenes ordenadas $2.",
"getimagelist"	=> " obteniendo la lista de imágenes",

"ilsubmit"		=> "Búsqueda",
"showlast"		=> "Mostrar las últimas $1 imágenes ordenadas  $2.",
"byname"		=> "por nombre",
"bydate"		=> "por fecha",
"bysize"		=> "por tamaño",
"imgdelete"		=> "borr",
"imgdesc"		=> "desc",
"imglegend"		=> "Simbología: (desc) = mostrar/editar la descripción de la imagen.",
"imghistory"	=> "Historial de la imagen",
"revertimg"		=> "rev",
"deleteimg"		=> "borr",
"deleteimgcompletely"		=> "borr",
"imghistlegend" => "Simbología: (act) = esta es la imagen actual, (borr) = borrar
esta versión antigua, (rev) = revertir a esta versión antigua.
<br /><i>Clic en la fecha para ver imagen subida en esa fecha</i>.",
"imagelinks"	=> "Enlaces a la imagen",
"linkstoimage"	=> "Las siguientes páginas enlazan a esta imagen:",
"nolinkstoimage" => "No hay páginas que enlacen a esta imagen.",

# Statistics
#
"statistics"	=> "Estadísticas",
"sitestats"		=> "Estadísticas del sitio",
"userstats"		=> "Estadísticas de usuario",
"sitestatstext" => "Hay un total de <b>$1</b> páginas en la base de datos.
Esto incluye páginas de discusión, páginas acerca de {{SITENAME}}, páginas mínimas,
redirecciones, y otras que probablemente no puedan calificarse como artículos.
Excluyéndolas, hay <b>$2</b> páginas que probablemente son artículos legítimos.<p>
Ha habido un total de <b>$3</b> visitas a páginas, y <b>$4</b> ediciones de página
desde que el software fue actualizado (Octubre 2002).
Esto resulta en un promedio de <b>$5</b> ediciones por página,
y <b>$6</b> visitas por edición.",
"userstatstext" => "Hay <b>$1</b> usuarios registrados.
de los cuales <b>$2</b> son administradores (ver $3).",

# Maintenance Page
#
"maintenance"		=> "Página de mantenimiento",
"maintnancepagetext"	=> "Esta página incluye varias herramientas útiles para el mantenimiento diario de la enciclopedia. Algunas de estas funciones tienden a sobrecargar la base de datos, así que, por favor, no vuelvas a cargar la página después de cada ítem que arregles ;-)",
"maintenancebacklink"	=> "Volver a la Página de Mantenimiento",
"disambiguations"	=> "Páginas de desambiguación",
"disambiguationspage"	=> "{{ns:project}}:Enlaces a páginas de desambiguación",
"disambiguationstext"	=> "Los siguientes artículos enlazan a una <i>página de desambiguación</i>. Deberían enlazar al artículo apropiado.<br />Una página es considerada de desambiguación si está enlazada desde $1.<br />Enlaces desde otros espacios de nombre (Como {{ns:project}}: o usuario:) <b>no</b> son listados aquí.",
"doubleredirects"	=> "Redirecciones dobles",
"doubleredirectstext"	=> "<b>Atención:</b> Esta lista puede contener falsos positivos. Eso significa usualmente que hay texto adicional con enlaces bajo el primer #REDIRECT.<br />\nCada fila contiene enlaces al segundo y tercer redirect, así como la primera línea del segundo redirect, en la que usualmente se encontrará el artículo \"real\" al que el primer redirect debería apuntar.",
"brokenredirects"	=> "Redirecciones incorrectas",
"brokenredirectstext"	=> "Las redirecciones siguientes enlazan a un artículo que no existe.",
"selflinks"		=> "Páginas con enlaces a sí mismas",
"selflinkstext"		=> "Las siguientes páginas contienen un enlace a sí mismas, lo que no se recomienda.",
"mispeelings"       => "Páginas con faltas de ortografía",
"mispeelingstext"               => "Las siguientes páginas contienen una falta de ortografía común de las listadas en $1. La escritura correcta se indica entre paréntesis.",
"mispeelingspage"       => "Lista de faltas de ortografía comunes",
"missinglanguagelinks"  => "Enlaces Interleguaje Faltantes",
"missinglanguagelinksbutton"    => "Encontrar los enlaces interlenguaje que faltan para",
"missinglanguagelinkstext"      => "Estos artículos <b>no</b> enlazan a sus correspondientes en $1. <b>No</b> se muestran redirecciones ni subpáginas.",


# Miscellaneous special pages
#
"orphans"		=> "Páginas huérfanas",
"lonelypages"	=> "Páginas huérfanas",
"unusedimages"	=> "Imágenes sin uso",
"popularpages"	=> "Páginas populares",
"nviews"		=> "$1 visitas",
"wantedpages"	=> "Páginas requeridas",
"nlinks"		=> "$1 enlaces",
"allpages"		=> "Todas las páginas",

"randompage"	=> "Página aleatoria",
"shortpages"	=> "Páginas cortas",
"longpages"		=> "Páginas largas",
"listusers"		=> "Lista de usuarios",
"specialpages"	=> "Páginas especiales",
"spheading"		=> "Páginas especiales",
"protectpage"	=> "Páginas protegidas",
"recentchangeslinked" => "Seguimiento de enlaces",
"rclsub"		=> "(a páginas enlazadas desde \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Páginas nuevas",
"ancientpages"		=> "Artículos más antiguos",
"intl"                  => "Enlaces interlenguaje",
"movethispage"	=> "Trasladar esta página",
"unusedimagestext" => "<p>Por favor note que otros sitios web
tales como otras wikipedias pueden enlazar a una imagen
con un URL directo, y de esa manera todavía estar listada aquí
a pesar de estar en uso activo.",
"booksources"   => "Fuentes de libros",
"booksourcetext" => "A continuación hay una lista de enlaces a otros sitios que venden libros nuevos y usados, y también pueden contener información adicional acerca de los libros que estás buscando.
{{SITENAME}} no está relacionada con ninguno de estos negocios, y esta lista no debe ser considerada un patrocinio de los mismos.",
"alphaindexline" => "$1 a $2",

# Email this user
#
"mailnologin"	=> "No enviar dirección",
"mailnologintext" => "Debes [[Especial:Userlogin|iniciar sesión]]
y tener una dirección electrónica válida en tus [[Especial:Preferences|preferencias]]
para enviar un correo electrónico a otros usuarios.",
"emailuser"		=> "Enviar correo electrónico a este usuario",
"emailpage"		=> "Correo electrónico a usuario",
"emailpagetext"	=> "Si este usuario ha registrado una dirección electrónica válida en sus preferencias de usuario, el siguiente formulario sirve para enviarle un mensaje.
La dirección electrónica que indicaste en tus preferencias de usuario aparecerá en el remitente para que el destinatario te pueda responder.",
"noemailtitle"	=> "No hay dirección electrónica",
"noemailtext"	=> "Este usuario no ha especificado una dirección electrónica válida, o ha elegido no recibir correo electrónico de otros usuarios.",
"emailfrom"		=> "De",
"emailto"		=> "Para",
"emailsubject"	=> "Tema",
"emailmessage"	=> "Mensaje",
"emailsend"		=> "Enviar",
"emailsent"		=> "Correo electrónico enviado",
"emailsenttext" => "Tu correo electrónico ha sido enviado.",

# Watchlist
#
"watchlist"		=> "Lista de seguimiento",
"watchlistsub"	=> "(para el usuario \"$1\")",
"nowatchlist"	=> "No tienes ninguna página en tu lista de seguimiento.",
"watchnologin"	=> "No has iniciado sesión",
"watchnologintext"	=> "Debes [[Especial:Userlogin|iniciar sesión]]
para modificar tu lista de seguimiento.",
"addedwatch"	=> "Añadido a la lista de seguimiento",
"addedwatchtext" => "La página \"$1\" ha sido añadida a tu  <a href=\"" .
  "{{localurle:Especial:Watchlist}}\">lista se seguimiento</a>.
Cambios futuros en esta página y su página de discusión asociada se indicarán ahí, y la página aparecerá <b>en negritas</b> en la <a href=\"" .
  "{{localurle:Especial:Recentchanges}}\">lista de cambios recientes</a> para hacerla más fácil de detectar.</p>

<p>Cuando quieras eliminar la página de tu lista de seguimiento, presiona \"Dejar de vigilar\" en el menú.",
"removedwatch"	=> "Eliminada de la lista de seguimiento",
"removedwatchtext" => "La página \"$1\" ha sido eliminada de tu lista de seguimiento.",
"watchthispage"	=> "Vigilar esta página",
"unwatchthispage" => "Dejar de vigilar",
"notanarticle"	=> "No es un artículo",
"watchnochange" => "Ninguno de los artículos en tu lista de seguimiento fue editado en el periodo de tiempo mostrado.",
"watchdetails" => "($1 páginas en tu lista de seguimiento, sin contar las de discusión;
$2 páginas editadas en total desde el cutoff;
$3...
[$4 mostrar y editar la lista completa].)",
"watchmethod-recent" => "chequeando ediciones recientes en la lista de seguimiento",

"watchmethod-list" => "buscando ediciones recientes en la lista de seguimiento",
"removechecked" => "Borrar artículos seleccionados de la lista de seguimiento",
"watchlistcontains" => "Tu lista de seguimiento posee $1 páginas.",
"watcheditlist" => "Aquí está un listado alfabético de tu lista de seguimiento.
Selecciona los artículos que deseas remover de tu lista de seguimiento y
click el botón 'remover seleccionados' en el fin de la pantalla.",
"removingchecked" => "Removiendo los artículos solicitados de la lista de seguimiento...",
"couldntremove" => "No se pudo remover el artículo '$1'...",
"iteminvalidname" => "Problema con el artículo '$1', nombre inválido...",
"wlnote" => "Abajo están los últimos $1 cambios en las últimas <b>$2</b> horas.",
# Delete/protect/revert
#
"deletepage"	=> "Borrar esta página",
"confirm"		=> "Confirma",
"excontent" => "contenido era: '$1'",
"exbeforeblank" => "contenido antes de borrar era: '$1'",
"exblank" => "página estaba vacía",
"confirmdelete" => "Confirma el borrado",
"deletesub"		=> "(Borrando \"$1\")",
"historywarning" => "Atención: La página que estás por borrar tiene un historial: ",
"confirmdeletetext" => "Estás a punto de borrar una página o imagen
en forma permanente,
así como todo su historial, de la base de datos.
Por favor, confirma que realmente quieres hacer eso, que entiendes las
consecuencias, y que lo estás haciendo de acuerdo con [[{{ns:project}}:Políticas]].",
"actioncomplete" => "Acción completa",
"deletedtext"	=> "\"$1\" ha sido borrado.
Ve $2 para un registro de los borrados más recientes.",
"deletedarticle" => "borrado \"$1\"",
"dellogpage"	=> "Registro_de_borrados",
"dellogpagetext" => "Abajo hay una lista de los borrados más recientes.
Todos los tiempos se muestran en hora del servidor (UTC).
<ul>
</ul>
",
"deletionlog"	=> "registro de borrados",
"reverted"		=> "Recuperar una revisión anterior",
"deletecomment"	=> "Razón para el borrado",
"imagereverted" => "Revertido a una versión anterior tuvo éxito.",
"rollback"		=> "Revertir ediciones",
"rollbacklink"	=> "Revertir",
"rollbackfailed" => "Reversión fallida",
"cantrollback"	=> "No se pueden revertir las ediciones; el último colaborador es el único autor de este artículo.",
"alreadyrolled"	=> "No se puede revertir la última edición de [[$1]]
por [[Colaborador:$2|$2]] ([[Colaborador Discusión:$2|Discusión]]); alguien más ya ha editado o revertido esa página.

La última edición fue hecha por [[Colaborador:$3|$3]] ([[Colaborador Discusión:$3|Discusión]]). ",
#   only shown if there is an edit comment
"editcomment" => "El resumen de la edición fue: \"<i>$1</i>\".",
"revertpage"	=> "Revertida a la última edición por $1",

# Undelete
"undelete" => "Restaurar una página borrada",
"undeletepage" => "Ver y restaurar páginas borradas",
"undeletepagetext" => "Las siguientes páginas han sido borradas pero aún están en el archivo y pueden ser restauradas. El archivo puede ser limpiado periódicamente.",
"undeletearticle" => "Restaurar artículo borrado",
"undeleterevisions" => "$1 revisiones archivadas",
"undeletehistory" => "Si tú restauras una página, todas las revisiones serán restauradas al historial.
Si una nueva página con el mismo nombre ha sido creada desde el borrado, las versiones restauradas aparecerán como historial anterior, y la revisión actual de la página \"viva\" no será automáticamente reemplazada.",
"undeleterevision" => "Revisión borrada al $1",
"undeletebtn" => "Restaurar!",
"undeletedarticle" => "restaurado \"$1\"",
"undeletedtext"   => "El artículo [[$1]] ha sido restaurado con éxito.
Véase [[{{ns:project}}:Registro_de_borrados]] para una lista de borrados y restauraciones recientes.",

# Contributions
#
"contributions"	=> "Contribuciones del usuario",
"mycontris"=>"Mis contribuciones",
"contribsub"	=> "$1",
"nocontribs"	=> "No se encontraron cambios que cumplieran estos criterios.",
"ucnote"		=> "Abajo están los últimos <b>$1</b> cambios de este usuario en los últimos <b>$2</b> días.",
"uclinks"		=> "Ver los últimos $1 cambios; ver los últimos $2 días.",
"uctop"		=> " (última modificación)" ,

# What links here
#
"whatlinkshere"	=> "Lo que enlaza aquí",
"notargettitle" => "No hay página objetivo",
"notargettext"	=> "No has especificado en qué página
llevar a cabo esta función.",
"linklistsub"	=> "(Lista de enlaces)",
"linkshere"		=> "Las siguientes páginas enlazan aquí:",
"nolinkshere"	=> "Ninguna página enlaza aquí.",
"isredirect"	=> "pagina redirigida",

# Block/unblock IP
#
"blockip"		=> "Bloqueo de direcciones IP",
"blockiptext"	=> "Usa el formulario siguiente para bloquear el
acceso de escritura desde una dirección IP específica.
Esto debería hacerse sólo para prevenir vandalismo, y de
acuerdo a las [[{{ns:project}}:Políticas|políticas de {{SITENAME}}]].
Explica la razón específica del bloqueo (por ejemplo, citando
ls páginas en particular que han sido objeto de vandalismo desde la dirección IP a bloquear).",
"ipaddress"		=> "Dirección IP",
"ipbreason"		=> "Razón",
"ipbsubmit"		=> "Bloquear esta dirección",
"badipaddress"	=> "La dirección IP no tiene el formato correcto.",

"blockipsuccesssub" => "Bloqueo exitoso",
"blockipsuccesstext" => "La dirección IP  \"$1\" ha sido bloqueada.
<br />Ver [[Especial:Ipblocklist|lista de IP bloqueadas]] para revisar bloqueos.",
"unblockip"		=> "Desbloquear dirección IP",
"unblockiptext"	=> "Usa el formulario que sigue para restaurar el
acceso de escritura a una dirección IP previamente bloqueada.",
"ipusubmit"		=> "Desbloquea esta dirección",
"ipusuccess"	=> "Dirección IP \"$1\" desbloqueada",
"ipblocklist"	=> "Lista de direcciones IP bloqueadas",
"blocklistline"	=> "$1, $2 bloquea $3 ($4)",
"blocklink"		=> "bloquear",
"unblocklink"	=> "desbloquear",
"contribslink"	=> "contribuciones",
"autoblocker"	=> "Bloqueado automáticamente porque compartes una dirección IP con \"$1\". Motivo \"$2\".",

# Developer tools
#

"lockdb"		=> "Bloquear la base de datos",
"unlockdb"		=> "Desbloquear la base de datos",
"lockdbtext"	=> "El bloqueo de la base de datos impedirá a todos los usuarios editar páginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualquier otra función que requiera realizar cambios en la base de datos. Por favor, confirma que ésto es precisamente lo que quieres hacer y que desbloquearás la base de datos tan pronto hayas finalizado las operaciones de mantenimiento.",
"unlockdbtext"	=> "El desbloqueo de la base de datos permitirá a todos los usuarios editar páginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualesquiera otras funciones que impliquen modificar la base de datos. Por favor, confirma que ésto es precisamente lo que quieres hacer.",
"lockconfirm"	=> "Sí, realmente quiero bloquear la base de datos.",
"unlockconfirm"	=> "Sí, realmente quiero desbloquear la base de datos.",
"lockbtn"		=> "Bloquear la base de datos",
"unlockbtn"		=> "Desbloquear la base de datos",
"locknoconfirm" => "No has confirmado lo que deseas hacer.",
"lockdbsuccesssub" => "El bloqueo se ha realizado con éxito",
"unlockdbsuccesssub" => "El desbloqueo se ha realizado con éxito",
"lockdbsuccesstext" => "La base de datos de {{SITENAME}} ha sido bloqueada.
<br />Recuerda retirar el bloqueo después de completar las tareas de mantenimiento.",
"unlockdbsuccesstext" => "La base de datos de {{SITENAME}} ha sido desbloqueada.",

# Move page
#
"movepage"		=> "Renombrar página",
"movepagetext"	=> "Usando el formulario que sigue renombrará una página,
moviendo todo su historial al nombre nuevo.
El título anterior se convertirá en un redireccionamiento al nuevo título.
Enlaces al antiguo título de la página no se cambiarán. Asegúrate de verificar no dejar redirecciones dobles o rotas.
Tú eres responsable de hacer que los enlaces sigan apuntando adonde se supone que lo deberían hacer.

Recuerda que la página '''no''' será renombrada si ya existe una página con el nuevo título, a no ser que sea una página vacía o un ''redirect'' sin historial.
Esto significa que podrás renombrar una página a su título original si cometes un error de escritura en el nuevo título, pero que no podrás sobreescribir una página existente.

<b>ADVERTENCIA!</b>
Este puede ser un cambio drástico e inesperado para una página popular;
por favor, asegúrate de entender las consecuencias que acarreará
antes de seguir adelante.",
"movepagetalktext" => "La página de discusión asociada, si existe, será renombrada automáticamente '''a menos que:'''
*Estés moviendo la página entre espacios de nombre diferentes,
*Una página de discusión no vacía ya existe con el nombre nuevo, o
*Desactivaste la opción \"Renombrar la página de discusión también\".

En estos casos, deberás trasladar manualmente el contenido de la página de discusión.",
"movearticle"	=> "Renombrar página",
"movenologin"	=> "No has iniciado sesión",
"movenologintext" => "Es necesario ser usuario registrado y [[Especial:Userlogin|haber iniciado sesión]]
para renombrar una página.",
"newtitle"		=> "A título nuevo",
"movepagebtn"	=> "Renombrar página",
"pagemovedsub"	=> "Renombrado realizado",
"pagemovedtext" => "Página \"[[$1]]\" renombrada a \"[[$2]]\".",
"articleexists" => "Ya existe una página con ese nombre, o el nombre que has
escogido no es válido.
Por favor, elige otro nombre.",
"talkexists"	=> "La página fue renombrada con éxito, pero la página de discusión no se pudo mover porque ya existe una en el título nuevo. Por favor incorpora su contenido manualmente.",
"movedto"		=> "renombrado a",
"movetalk"	=> "Renombrar la página de discusión también, si es aplicable.",
"talkpagemoved" =>  "La página de discusión correspondiente también fue renombrada.",
"talkpagenotmoved" => "La página de discusión correspondiente <strong>no</strong> fue renombrada.",
# Math
'mw_math_png' => "Producir siempre PNG",
'mw_math_simple' => "HTML si es muy simple, si no PNG",
'mw_math_html' => "HTML si es posible, si no PNG",
'mw_math_source' => "Dejar como TeX (para navegadores de texto)",
'mw_math_modern' => "Recomendado para navegadores modernos",
'mw_math_mathml' => 'MathML',

# Bits of text used by many pages:
#
'categories' => 'Categorías',
'category' => 'categoría',
'category_header' => 'Artículos en la categoría "$1"',
'subcategories' => 'Subcategorías',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Artículo',
'nstab-user' => 'Usuario',
'nstab-media' => 'Media',
'nstab-special' => 'Especial',
'nstab-wp' => 'Acerca de',
'nstab-image' => 'Imagen',
'nstab-mediawiki' => 'Mensaje',
'nstab-template' => 'Plantilla',
'nstab-help' => 'Ayuda',
'nstab-category' => 'Categoría',


# Edit page toolbar
'bold_sample' => "Texto en negrita",
'bold_tip' => "Texto en negrita",
'italic_sample' => "Texto en cursiva",
'italic_tip' => "Texto en cursiva",
'link_sample' => "Título del enlace",
'link_tip' => "Enlace interno",
'extlink_sample' => "http://www.ejemplo.com Título del enlace",
'extlink_tip' => "Enlace externo (recuerda añadir el prefijo http://)",
'headline_sample' => "Texto de titular",
'headline_tip' => "Titular de nivel 2",
'math_sample' => "Escribe aquí una fórmula",
'math_tip' => "Fórmula matemática (LaTeX)",
'nowiki_sample' => "Aquí inserta texto sin formato",
'nowiki_tip' => "Pasar por alto el formato wiki",
'image_sample' => "Ejemplo.jpg",
'image_tip' => "Imagen incorporada",
'media_sample' => "Ejemplo.mp3",
'media_tip'=> 'Enlace a archivo multimedia',
'sig_tip' => "Firma, fecha y hora",
'hr_tip' => "Línea horizontal (utilízala con moderación)",
'infobox' => "Pulsa un botón para ver un texto de ejemplo",
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
'infobox_alert' => "Escribe el texto al que quieres dar formato.\n Se mostrará en la caja de información para poder copiar y pegar.\nEjemplo:\n$1\nse convertirá en:\n$2",

# Special:Allpages
'nextpage'          => 'Next page ($1)',
'allarticles'       => 'Todos los artículos',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Siguiente',
'allinnamespace' => 'Todas las páginas (espacio $1)',
'allpagessubmit'    => 'Mostrar',

# Patrolling
'markaspatrolleddiff'   => "Marcar como revisado",
'markaspatrolledlink'   => "[$1]",
'markaspatrolledtext'   => "Marcar este artículo como revisado",
'markedaspatrolled'     => "Marcar como revisado",
'markedaspatrolledtext' => "La versión seleccionada ha sido marcada como revisada.",
'rcpatroldisabled'      => "Revisión de los Cambios Recientes deshabilitada",
'rcpatroldisabledtext'  => "La capacidad de revisar los Cambios Recientes está deshabilitada en este momento.",

'showhideminor' => "$1 ediciones menores | $2 bots | $3 usuarios registrados | $4 ediciones revisadas ",

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Usuario: ',
'speciallogtitlelabel' => 'Título: ',

'subcategorycount' => "Hay $1 subcategorías en esta categoría.",
'subcategorycount1' => "Hay $1 subcategoría en esta categoría.",
'categoryarticlecount' => "Hay $1 artículos en esta categoría.",
'categoryarticlecount1' => "Hay $1 artículo en esta categoría.",
'categoriespagetext' => "Existen las siguientes categorías en este wiki.",

'toolbox' => "Herramientas",


'tooltip-compareselectedversions' => "Ver las diferencias entre las dos versiones seleccionadas de esta página. [alt-v]",
'tooltip-minoredit' => "Marcar este cambio como menor [alt-i]",
'tooltip-preview' => "Previsualiza tus cambios, ¡por favor, usa esto antes de grabar! [alt-p]",
'tooltip-save' => "Guardar tus cambios [alt-s]",
'tooltip-search' => "Buscar en este wiki [alt-f]",

'clearyourcache' => "'''Nota:''' Tras salvar el fichero, debes refrescar la caché de tu navegador para ver los cambios:
*'''Mozilla:'''  Pulsa el botón ''Recargar'' (o ''ctrl-r''),
*'''Internet Explorer / Opera:''' ''ctrl-f5'',
*'''Safari:''' ''cmd-r'',
*'''Konqueror''' ''ctrl-r''.",
'compareselectedversions' => "Comparar versiones seleccionadas",

'feedlinks' => "Sindicación:",

'import' => "Importar páginas",
'importfailed' => "La importación ha fallado: $1",
'importhistoryconflict' => "Existe un historial de revisiones en conflicto (puede haberse importado esta página con anterioridad)",
'importnotext' => "Vacío o sin texto",
'importsuccess' => "¡La importación tuvo éxito!",
'importtext' => "Por favor, exporta el archivo desde el wiki de origen usando la utilidad Special:Export, guardalo en tu disco y súbelo aquí.",

'thumbnail-more' => "Aumentar",

'imagemaxsize' => 'Limitar imágenes en las páginas descripción a: ',
'showbigimage' => 'Descargar versión de alta resolución ($1x$2, $3 KB)',

'newimages' => 'Galería de imágenes nuevas',
'noimages'  => 'No hay nada que ver.',

'previousdiff' => "← Ir a diferencia anterior",
'nextdiff' => "Ir a siguiente diferencia →",

'deletedrevision' => "Borrada revisión antigua $1.",

'Monobook.js' => "/* tooltips and access keys */
ta = new Object();
ta['pt-userpage'] = new Array('.','Mi página de usuario');
ta['pt-anonuserpage'] = new Array('.','La página de usuario de la IP desde la que editas');
ta['pt-mytalk'] = new Array('n','Mi página de discusión');
ta['pt-anontalk'] = new Array('n','Discusión sobre ediciones hechas desde esta dirección IP');
ta['pt-preferences'] = new Array('','Mis preferencias');
ta['pt-watchlist'] = new Array('l','La lista de páginas para las que estás vigilando los cambios');
ta['pt-mycontris'] = new Array('y','Lista de mis contribuciones');
ta['pt-login'] = new Array('o','Te animamos a registrarte, aunque no es obligatorio');
ta['pt-anonlogin'] = new Array('o','Te animamos a registrarte, aunque no es obligatorio');
ta['pt-logout'] = new Array('o','Salir de la sesión');
ta['ca-talk'] = new Array('t','Discusión acerca del artículo');
ta['ca-edit'] = new Array('e','Puedes editar esta página. Por favor, usa el botón de previsualización antes de grabar.');
ta['ca-addsection'] = new Array('+','Añade un comentario a esta discusión');
ta['ca-viewsource'] = new Array('e','Esta página está protegida, sólo puedes ver su código fuente');
ta['ca-history'] = new Array('h','Versiones anteriores de esta página');
ta['ca-protect'] = new Array('=','Proteger esta página');
ta['ca-delete'] = new Array('d','Borrar esta página');
ta['ca-undelete'] = new Array('d','Restaurar las ediciones hechas a esta página antes de que fuese borrada');
ta['ca-move'] = new Array('m','Trasladar (renombrar) esta página');
ta['ca-watch'] = new Array('w','Añadir esta página a tu lista de seguimiento');
ta['ca-unwatch'] = new Array('w','Borrar esta página de tu lista de seguimiento');
ta['search'] = new Array('f','Buscar en este wiki');
ta['p-logo'] = new Array('','Portada');
ta['n-mainpage'] = new Array('z','Visitar la Portada');
ta['n-portal'] = new Array('','Acerca del proyecto, qué puedes hacer, dónde encontrar información');
ta['n-currentevents'] = new Array('','Información de contexto sobre acontecimientos actuales');
ta['n-recentchanges'] = new Array('r','La lista de cambios recientes en el wiki');
ta['n-randompage'] = new Array('x','Cargar una página aleatoriamente');
ta['n-help'] = new Array('','El lugar para aprender');
ta['n-sitesupport'] = new Array('','Respáldanos');
ta['t-whatlinkshere'] = new Array('j','Lista de todas las páginas del wiki que enlazan con ésta');
ta['t-recentchangeslinked'] = new Array('k','Cambios recientes en las páginas que enlazan con esta otra');
ta['feed-rss'] = new Array('','Sindicación RSS de esta página');
ta['feed-atom'] = new Array('','Sindicación Atom de esta página');
ta['t-contributions'] = new Array('','Ver la lista de contribuciones de este usuario');
ta['t-emailuser'] = new Array('','Enviar un mensaje de correo a este usuario');
ta['t-upload'] = new Array('u','Subir imágenes o archivos multimedia');
ta['t-specialpages'] = new Array('q','Lista de todas las páginas especiales');
ta['ca-nstab-main'] = new Array('c','Ver el artículo');
ta['ca-nstab-user'] = new Array('c','Ver la página de usuario');
ta['ca-nstab-media'] = new Array('c','Ver la página de multimedia');
ta['ca-nstab-special'] = new Array('','Esta es una página especial, no se puede editar la página en sí');
ta['ca-nstab-wp'] = new Array('a','Ver la página de proyecto');
ta['ca-nstab-image'] = new Array('c','Ver la página de la imagen');
ta['ca-nstab-mediawiki'] = new Array('c','Ver el mensaje de sistema');
ta['ca-nstab-template'] = new Array('c','Ver la plantilla');
ta['ca-nstab-help'] = new Array('c','Ver la página de ayuda');
ta['ca-nstab-category'] = new Array('c','Ver la página de categoría');",
'navigation' => "Navegación",

'portal'		=> 'Portal de la comunidad',
'portal-url'		=> 'Project:Portal de la comunidad',

'validate'		=> 'Validar página',
'uncategorizedpages'	=> 'Páginas sin categorizar',
'uncategorizedcategories'	=> 'Categorías sin categorizar',

'movedto' => "renombrado a",
'moredotdotdot' => "Más...",
);

class LanguageEs extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesEs;
		return $wgNamespaceNamesEs;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEs;
		return $wgQuickbarSettingsEs;
	}

	function getSkinNames() {
		global $wgSkinNamesEs;
		return $wgSkinNamesEs;
	}

	function shortdate( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " de " .$this->getMonthName( substr( $ts, 4, 2 ) ) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->time( $ts, $adj ) . " " . $this->shortdate( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesEs;
		if( isset( $wgAllMessagesEs[$key] ) ) {
			return $wgAllMessagesEs[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}
}

?>
