<?

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesEs = array(
	-1	=> "Especial",
	0	=> "",
	1	=> "Discusión",
	2	=> "Usuario",
	3	=> "Usuario_Discusión",
	4	=> "Wikipedia",
	5	=> "Wikipedia_Discusión",
	6	=> "Imagen",
	7	=> "Imagen_Discusión"
);

/* Note that some default options can be customized -- see
   '$wgDefaultUserOptionsEn' in Language.php */

/* private */ $wgQuickbarSettingsEs = array(
	"Ninguna", "Fija a la izquierda", "Fija a la derecha", "Flotante a la izquierda"
);

/* private */ $wgSkinNamesEs = array(
	"Standard", "Nostalgia", "Cologne Blue"
);

/* private */ $wgMathNamesEs = array(
	"Producir siempre PNG",
	"HTML si es muy simple, si no PNG",
	"HTML si es posible,si no PNG",
	"Dejar como TeX (para navegadores de texto)",
        "Recomendado para navegadores modernos"
);

/* private */ $wgUserTogglesEs = array(
	"hover"		=> "Mostrar caja flotante sobre los enlaces wiki",
	"underline" => "Subrayar enlaces",
	"highlightbroken" => "Destacar enlaces a tópicos vacíos<a href=\"\" class=\"new\">como este</a> (alternativa: como este<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Ajustar párrafos",
	"hideminor" => "Esconder ediciones menores en cambios recientes",
	"usenewrc" => "Cambios recientes realzados (no para todos los navegadores)",
	"numberheadings" => "Auto-numerar encabezados",
	"rememberpassword" => "Recordar la contraseña entre sesiones",
	"editwidth" => "La caja de edición tiene el ancho máximo",
	"editondblclick" => "Edit pages on double click (JavaScript)",
	"watchdefault" => "Vigilar artículos nuevos y modificados",
	"minordefault" => "Marcar todas las ediciones como menores por defecto",
	"previewontop" => "Mostrar la previsualización antes de la caja de edición en lugar de después"
);

/* Please customize this with some Spanish-language bookshops
   and/or reference sites that can look up by ISBN number */
/* private */ $wgBookstoreListEs = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* Where known, these should be native names and spellings of
   languages, so the speakers can recognize them. */
/* private */ $wgLanguageNamesEs = array(
	"aa"	=> "Afar",
	"ab"	=> "Abkhaziano",
	"af"	=> "Afrikaans",
	"sq"	=> "Albanés",
	"am"	=> "Amharico",
	"ar"	=> "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236; (Araby,Árabe)",
	"hy"	=> "Armeno",
	"as"	=> "Assamese",
	"ay"	=> "Aymara",
	"az"	=> "Azerbaijani",
	"ba"	=> "Bashkir",
	"eu"	=> "Vasco",
	"be"	=> "Bieloruso",
	"bn"	=> "Bengalí",
	"dz"	=> "Bhutaní",
	"bh"	=> "Bihara",
	"bi"	=> "Bislama",
	"my"	=> "Burmese",
	"km"	=> "Camboyano",
	"ca"	=> "Català(Catalán)",
	"co"	=> "Corso",
	"hr"	=> "Croata",
	"cs"	=> "&#268;eská(Checo)",
	"da"	=> "Dansk(Danés)", # Note two different subdomains. 
	"dk"	=> "Dansk(Danés)", # 'da' is correct for the language.
	"nl"	=> "Nederlands (Holandés)",
	"en"	=> "English (Inglés)",
	"simple" => "Inglés Simple",
	"eo"	=> "Esperanto",
	"et"	=> "Eesti (Estoniano)",
	"fo"	=> "Faeroese",
	"fj"	=> "Fijiés",
	"fi"	=> "Suomi (Finlandés)",
	"fr"	=> "Fran&#231;ais (Francés)",
	"fy"	=> "Frisio",
	"gl"	=> "Gallego",
	"ka"	=> "Georgiano",
	"de"	=> "Deutsch(Alemán)",
	"el"	=> "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940; (Ellenika,Griego)",
	"kl"	=> "Groenlandés",
	"gn"	=> "Guarani",
	"gu"	=> "Gujarati",
	"ha"	=> "Hausa",
	"he"	=> "Hebreo",
	"hi"	=> "Hindi",
	"hu"	=> "Húngaro",
	"is"	=> "Islandés",
	"id"	=> "Indoneso",
	"ia"	=> "Interlingua",
	"iu"	=> "Inuktitut",
	"ik"	=> "Inupiak",
	"ga"	=> "Irlandés",
	"it"	=> "Italiano",
	"ja"	=> "&#26085;&#26412;&#35486; (Nihongo,Japonés)",
	"jv"	=> "Javanés",
	"kn"	=> "Kannada",
	"ks"	=> "Kashmiri",
	"kk"	=> "Kazakh",
	"rw"	=> "Kinyarwanda",
	"ky"	=> "Kirghiz",
	"rn"	=> "Kirundi",
	"ko"	=> "&#54620;&#44397;&#50612; (Hangukeo,Coreano)",
	"lo"	=> "Laotiano",
	"la"	=> "Latina",
	"lv"	=> "Letoniano",
	"ln"	=> "Lingala",
	"lt"	=> "Lituaniano",
	"mk"	=> "Macedoniano",
	"mg"	=> "Malagasy",
	"ms"	=> "Malay",
	"ml"	=> "Malayalam",
	"mi"	=> "Maori",
	"mr"	=> "Marathi",
	"mo"	=> "Moldavian",
	"mn"	=> "Mongolian",
	"na"	=> "Nauru",

	"ne"	=> "Nepali",
	"no"	=> "Noruego",
	"oc"	=> "Occitano",
	"or"	=> "Oriya",
	"om"	=> "Oromo",
	"ps"	=> "Pashto",
	"fa"	=> "Persa",
	"pl"	=> "Polski (Polaco)",
	"pt"	=> "Portugu&#234;s (Portugués)",
	"pa"	=> "Punjabi",
	"qu"	=> "Quechua",
	"rm"	=> "Rhaeto-Romance",
	"ro"	=> "Romaniano",
	"ru"	=> "Ruso",
	"sm"	=> "Samoano",
	"sg"	=> "Sangro",
	"sa"	=> "Sánscrito",
	"sr"	=> "Serbo",
	"sh"	=> "Serbocroata",
	"st"	=> "Sesotho",
	"tn"	=> "Setswana",
	"sn"	=> "Shona",
	"sd"	=> "Sindhi",
	"si"	=> "Sinhalés",
	"ss"	=> "Siswati",
	"sk"	=> "Eslovaco",
	"sl"	=> "Esloveno",
	"so"	=> "Somali",
	"es"	=> "Castellano",
	"su"	=> "Sudanés",
	"sw"	=> "Swahili",
	"sv"	=> "Sueco (Svensk)",
	"tl"	=> "Tagalog",
	"tg"	=> "Tajik",
	"ta"	=> "Tamil",
	"tt"	=> "Tatar",
	"te"	=> "Telugu",
	"th"	=> "Tailandés",
	"bo"	=> "Tibetano",
	"ti"	=> "Tigrinya",
	"to"	=> "Tonga",
	"ts"	=> "Tsonga",
	"tr"	=> "Turco",
	"tk"	=> "Turcomano",
	"tw"	=> "Twi",
	"ug"	=> "Uighur",
	"uk"	=> "Ucraniano",
	"ur"	=> "Urdu",
	"uz"	=> "Uzbek",
	"vi"	=> "Vietnamés",
	"vo"	=> "Volapuk",
	"cy"	=> "Galés",
	"wo"	=> "Wolof",
	"xh"	=> "Xhosa",
	"yi"	=> "Yiddish",
	"yo"	=> "Yoruba",
	"za"	=> "Zhuang",
	"zh" => "&#20013;&#25991; (Zhongwen,Chino)",
	"zu"	=> "Zulu"
);

/* private */ $wgWeekdayNamesEs = array(
	"Domingo", "Lunes", "Martes", "Miércoles", "Jueves",
	"Viernes", "Sábado"
);

/* private */ $wgMonthNamesEs = array(
	"enero", "febrero", "marzo", "abril", "mayo", "junio",
	"julio", "agosto", "septiembre", "octubre", "noviembre",
	"diciembre"
);

/* private */ $wgMonthAbbreviationsEs = array(
	"ene", "feb", "mar", "abr", "may", "jun", "jul", "ago",
	"sep", "oct", "nov", "dic"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesEs = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Preferencias de usuario",
	"Watchlist"		=> "Mi lista de seguimiento",
	"Recentchanges" => "Cambio Recientes",
	"Upload"		=> "Subir una imagen",
	"Imagelist"		=> "Lista de imágenes",
	"Listusers"		=> "Usuarios registrados",
	"Statistics"	=> "Estadísticas del sitio",
	"Randompage"	=> "Artículo aleatorio",

	"Lonelypages"	=> "Artículos huérfanos",
	"Unusedimages"	=> "Imágenes huérfanas",
	"Popularpages"	=> "Artículos populares",
	"Wantedpages"	=> "Artículos más solicitados",
	"Shortpages"	=> "Artículos cortos",
	"Longpages"		=> "Artículos largos",
	"Newpages"		=> "Artículos nuevos",
	"Intl"		=> "Enlaces Interlenguaje",
	"Allpages"		=> "Todas las páginas (alfabético)",

	"Ipblocklist"	=> "Direcciones IP bloqueadas",
	"Maintenance"   => "Página de mantención",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"   => "Fuentes externas de libros"
);

/* private */ $wgSysopSpecialPagesEs = array(
	"Blockip"		=> "Bloquear una dirección IP",
	"Asksql"		=> "Búsqueda en la base de datos",
	"Undelete"      => "Ver y restaurar páginas borradas"
);

/* private */ $wgDeveloperSpecialPagesEs = array(
	"Lockdb"		=> "Cerrar acceso de escritura a la base de datos",
	"Unlockdb"		=> "Restaurar acceso de escritura a la base de datos",
	"Debug"			=> "Debugging information"
);

/* private */ $wgAllMessagesEs = array(

# Bits of text used by many pages:
#
"linktrail"     => "/^([a-záéíóúñ]+)(.*)\$/sD",
"mainpage"		=> "Portada",
"mainpagetext"	=> "Software wiki instalado exitosamente.",
"about"			=> "Acerca de",
"aboutwikipedia" => "Acerca de Wikipedia",
"aboutpage"		=> "Wikipedia:Acerca de",
"help"			=> "Ayuda",
"helppage"		=> "Wikipedia:Ayuda",
"wikititlesuffix"		=>"Wikipedia",
"bugreports"	=> "Reportes de error de software",
"bugreportspage" => "Wikipedia:Reportes_de_error",
"faq"			=> "FAQ",
"faqpage"		=> "Wikipedia:FAQ",
"edithelp"		=> "Ayuda de edición",
"edithelppage"	=> "Wikipedia:Cómo_se_edita_una_página",
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
"fromwikipedia"	=> "De Wikipedia, la enciclopedia libre.",
"whatlinkshere"	=> "Páginas que enlazan aquí",
"help"			=> "Ayuda",
"search"		=> "Buscar",
"go"		=> "Ir",
"history"		=> "Historia",
"printableversion" => "Versión para imprimir",
"editthispage"	=> "Edita esta página",
"deletethispage" => "Borra esta página",
"protectthispage" => "Proteje esta página",
"unprotectthispage" => "Desproteje esta página",
"newpage" => "Página nueva",
"talkpage"		=> "Discute esta página",
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
"gnunote" => "Todo el texto se hace disponible bajo los términos de la <a class=internal href='/wiki/GNU_FDL'>Licencia de Documentación Libre GNU (GNU FDL)",
"printsubtitle" => "(De http://es.wikipedia.org)",
"protectedpage" => "Página protegida",
"administrators" => "Wikipedia:Administradores",
"sysoptitle"	=> "Acceso de Sysop requerido",
"sysoptext"		=> "La acción que has requerido sólo puede ser llevada a cabo
 por usuarios con status de \"sysop\".
Ver $1.",
"developertitle" => "Acceso de developer requerido",
"developertext"	=> "La acción que has requerido sólo puede ser llevada a cabo 
por usuarios con status de \"developer\".
Ver $1.",
"nbytes"		=> "$1 bytes",
"go"			=> "Ir",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "La Enciclopedia Libre",
"retrievedfrom" => "Obtenido de \"$1\"",
"newmessages" => "Tienes $1.",
"newmessageslink" => "mensajes nuevos",

# Main script and global functions
#
"nosuchaction"	=> "No existe tal acción",
"nosuchactiontext" => "La acción especificada por el URL no es
 reconocida por el software de Wikipedia",
"nosuchspecialpage" => "No existe esa página especial",
"nospecialpagetext" => "Has requerido una página especial que no es
 reconocida por el software de Wikipedia.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Error de la base de datos",
"dberrortext"	=> "Ha ocurrido un error de sintaxis en una consulta
a la base de datos. 
Esto puede ser debido a una búsqueda ilegal (ver $5),
o puede indicar un error en el software.
La última consulta que se intentó fue:
<blockquote><tt>$1</tt></blockquote>El error de retorno de 
MySQL fue\"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Ha ocurrido un error de sintaxis en una consulta
a la base de datos.
La última consulta que se intentó fue:
\"$1\"
desde la funci&oacute;n \"$2\".
MySQL retorn&oacute; el error \"$3: $4\".\n",
"noconnect"		=> "No se pudo conectar a la base de datos en $1",
"nodb"			=> "No se pudo seleccionar la base de datos$1",
"readonly"		=> "Base de datos bloqueada",
"enterlockreason" => "Enter a reason for the lock, including an estimate
of when the lock will be released",
"readonlytext"	=> "La base de datos de Wikipedia está temporalmente
bloqueda para nuevas entradas u otras modificaciones, probablemenete
pare mantención de rutina, después de lo cual volverá a la normalidad.
El administrador que la bloqueó ofreció esta explicación:
<p>$1",
"missingarticle" => "La base de datos no encontró el texto de una
página que debería haber encontrado, llamada \"$1\".

<p>Esto es causado usualmente por seguir un enlace a una diferencia de páginas o historia obsoleta a una página que ha sido borrada.

<p>Si esta no es la causa, puedes haber encontrado un error en el software. Por favor, reporte esto a un administrador,
notando el URL.",
"internalerror" => "Error interno",
"filecopyerror" => "No se pudo copiar el archivo \"$1\" a \"$2\".",
"filerenameerror" => "No se pudo renombrar el archivo \"$1\" a \"$2\".",
"filedeleteerror" => "No se pudo borrar el archivo \"$1\".",
"filenotfound"	=> "No se pudo encontrar el archivo \"$1\".",
"unexpected"	=> "Valor no esperado: \"$1\"=\"$2\".",
"formerror"		=> "Error: no se pudo submitir la forma",	
"badarticleerror" => "Esta acción no se puede llevar a cabo en esta página.",
"cannotdelete"	=> "No se pudo borrar la página o imagen especificada.(Puede haber sido borrada por alguien antes)",
"badtitle"		=> "T&iacute;tulo incorrecto",
"badtitletext"	=> "El t&iacute;tulo de la página requerida era inválido, vac&iacute;o, o un enlace interleguaje o interwiki incorrecto.",

"perfdisabled" => "Lo siento, esta función está temporalmente desactivada porque enlentece la base de datos a tal punto que nadie puede usar el wiki. Será reescrita para mayor eficiencia en el futuro )probablemente por ti!=",

# Login and logout pages&iacute;tulo
"logouttitle"	=> "Fin de sesión",
"logouttext"	=> "Has terminado tu sesión.
Puedes continuar usando Wikipedia en forma anónima, o puedes
iniciar sesión otra vez  como el mismo u otro usuario.\n",

"welcomecreation" => "<h2>Bienvenido(a), $1!</h2><p>Tu cuenta ha sido creada.
No olvides perzonalizar tus preferencia de Wikipedia.",

"loginpagetitle" => "Registrarse/Entrar",
"yourname"		=> "Tu nombre de usuario",
"yourpassword"	=> "Tu contraseña",
"yourpasswordagain" => "Repite tu contraseña",
"newusersonly"	=> " (sólo usuarios nuevos)",
"remembermypassword" => "Quiero que recuerden mi contraseña entre sesiones.",
"loginproblem"	=> "<b>Hubo un problema con tu entrada.</b><br>¡Trata otra vez!",
"alreadyloggedin" => "<font color=red><b>Usuario $1, ya entraste!</b></font><br>\n",

"areyounew"		=> "Si eres nuevo en Wikipedia en Español, y
quieres tener una cuenta de usuario, ingresa un nombre de usuario,
y tipea y repite una contraseña.
Tu dirección electrónica es opcional: si pierdes u olvidas tu
contraseña, puedes pedir que se envíe a la dirección que des.  <br>\n",
"login"			=> "Registrarse/Entrar",
"userlogin"		=> "Registrarse/Entrar",
"logout"		=> "Salir",
"userlogout"	=> "Salir",
"createaccount"	=> "Crea una nueva cuenta",
"badretype"		=> "Las contraseñas que ingresaste no concuerdan.",
"userexists"	=> "El nombre que entraste ya está en uso. Por favor, elije un nombre diferente.",
"youremail"		=> "Tu dirección electrónica (e-mail)",
"yournick"		=> "Tu apodo (para firmas)",
"emailforlost"	=> "Ingresar una dirección electrónica es opcional. Pero permite a los demás usuarios contactarte a trav&ecute;s del sitio web sin tener que revelarles tu dirección electrónica. Además, si pierdes u olvidas tu contraseña, puedes pedir que se envíe una nueva a tu dirección electrónica.", 
"loginerror"	=> "Error de inicio de sesión",
"noname"		=> "No has especificado un nombre de usuario válido.",
"loginsuccesstitle" => "Inicio de sesión exitoso",
"loginsuccess"	=> "Has iniciado tu sesión en Wikipedia como \"$1\".",
"nosuchuser"	=> "No existe usuario alguno llamado \"$1\".
Revisa tu deletreo, o usa la forma abajo para crear una nueva cuenta de usuario.",
"wrongpassword"	=> "La contraseña que ingresaste es incorrecta. Por favor trata otra vez.",
"mailmypassword" => "Envíame una nueva contraseña por correo electrónico",
"passwordremindertitle" => "Recordatorio de contraseña de Wikipedia",
"passwordremindertext" => "Alguien (probablemente tú , desde la direccion IP $1)
solicitó que te enviaramos una nueva contraseña para iniciar sesión en Wikipedia.
La contraseña para el usuario \"$2\" es ahora \"$3\".
Ahora deberías iniciar sesion y cambiar tu contraseña.",
"noemail"		=> "No hay dirección electrónica (e-mail) registrada para el(la) usuario(a) \"$1\".",
"passwordsent"	=> "Una nueva contraseña ha sido enviada a la dirección electrónica
registrada para \"$1\".
Por favor entra otra vez después de que la recibas.",

# Edit pages
#
"summary"		=> "Resumen",
"minoredit"		=> "Esta es una edición menor.",
"watchthis"		=> "Vigila este artículo.",
"savearticle"	=> "Grabar la página",
"preview"		=> "Previsualizar",
"showpreview"	=> "Mostrar previsualización",
"blockedtitle"	=> "El usuario está bloqueado",
"blockedtext"	=> "Tu nombre de usuario o dirección IP ha sido bloqueada por $1.
La razón dada es la que sigue:<br>$2<p> Puedes contactar a $1 o a otro de los Wikipedia:Administradores|administradores]] para
discutir el bloqueo.",
"newarticle"	=> "(Nuevo)",
"newarticletext" => "Wikipedia es una enciclopedia en desarrollo, y esta página a&uacute;n no existe. Puedes pedir información en [[Wikipedia:Consultas]], pero no esperes una respuesta pronta.Si quieres crear esta página, empieza a escribir en la caja que sigue. Si llegaste aquí por error, presiona la tecla para volver a la página anterior de tu navegador.",
"anontalkpagetext" => "---- ''Esta es la página de discusión para un usuario anónimo que a&uacute;n no ha creado una cuenta, o no la usa. Por lo tanto, tenemos que usar su [[dirección IP]] num&eacute;rica para adentificarlo. Una dirección IP puede ser compartida por varios usuarios. Si eres un usuario anónimo y sientes que comentarios irreleventes han sido dirigidoa a ti, por favor [[Especial:Userlogin|crea una cuenta o entra]] para evitar confusiones futuras con otros usuarios anónimos.'' ",
"noarticletext" => "(En este momento no hay texto en esta página)",
"updated"		=> "(Actualizado)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Recuerda que esto es sólo una previsualización, y no ha sido grabada todavía!",
"previewconflict" => "Esta previsualización refleja el texto en el área
de edición superior como aparecerá si eliges grabar.",
"editing"		=> "Editando $1",
"editconflict"	=> "Conflicto de edición: $1",
"explainconflict" => "Alguien más ha cambiado esta página desde que empezaste
a editarla. 
El área de texto superior contiene el texto de la página como existe
actualmente. Tus cambios se muestran en el área de texto inferior.
Vas a tener que incorporar tus cambio en el texto existente.
<b>Sólo</b> el texto en el área de texto superior será grabado cuando presiones
 \"Grabar pagina\".\n<p>",
"yourtext"		=> "Tu texto",
"storedversion" => "Versión almacenada",
"editingold"	=> "<strong>ADVERTENCIA:Estás editando una versión antigua
 de esta página.
Si la grabas, los cambios hechos desde esa revisión se perderán.</strong>\n",
"yourdiff"		=> "Diferencias",
"copyrightwarning" => "Nota por favor que todas las contribuciones a Wikipedia 
se consideran hechas públicas bajo la Licencia de Documentación Libre GNU 
(ver detalles en $1). 
 Si no deseas que la gente corrija tus escritos sin piedad 
y los distribuya libremente, entonces no los pongas aquí. <br>
También tú nos aseguras que escribiste esto tú mismo y 
eres dueño de los derechos de autor, o lo copiaste desde el dominio público 
u otra fuente libre.
 <strong>NO USES ESCRITOS CON COPYRIGHT SIN PERMISO!</strong><br>
áéíóúÁÉÍÓÚüÜñÑ¡¿",
"longpagewarning" => "ADVERTENCIA: Esta página tiene un tama&ntilde;o de $1 kilobytes ; algunos navegadores pueden tener problemas editando página
cerca de o más grandes que 32kb.
Por favor considera descomponer esta página en secciones más peque&ntilde;as.",
"readonlywarning" => "ADVERTENCIA:La base de datos ha sido bloqueada por mantenimiento, así que no podrás grabar tus modificaciones en este momemnto. 
Puede \"cortar y pegar\" a un archivo de texto en tu computador, y grabarlo para
tratar despu&eacute;s.",
"protectedpagewarning" => "ADVERTENCIA: Esta página ha sido bloqueda de manera que s&ocute;lo usuarios con privilegios de administrador pueden editarla. Asegúrate de que estás siguiendo las
<a href='/wiki/Wikipedia:Guías_para_páginas_protegidas'>guías para páginas protegidas</a>.",
# History pages
#
"revhistory"	=> "Historia de revisiones",
"nohistory"		=> "No hay una historia de ediciones para esta página.",
"revnotfound"	=> "Revisión no encontrada",
"revnotfoundtext" => "La revisión antigua de la página por la que preguntaste no se pudo encontrar.
Por favor revisa el URL que usaste para acceder a esta página.\n",
"loadhist"		=> "Recuperando la historia de la página",
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
"searchhelppage" => "Wikipedia:Búsqueda",
"searchingwikipedia" => "Buscando en Wikipedia",
"searchresulttext" => "Para más información acerca de búsquedas en Wikipedia, ve $1.",
"searchquery"	=> "Para consulta \"$1\"",
"badquery"		=> "Consulta de búsqueda formateada en forma incorrecta",
"badquerytext"	=> "No pudimos procesar tu búsqueda.
Esto es probablemente porque intentaste buscar una palabra de menos de tres letras, lo que todavía no es posible.
También puede ser que hayas cometido un error de tipeo en la expresión.
Por favor, trata una búsqueda diferente.",
"matchtotals"	=> "La consulta \"$1\" coincidió con $2  títulos de artículos
y el texto de $3 artículos.",
"nogomatch" => "No existe ninguna página con este exactamente este título, intentando una búsqueda en todo el texto.",
"titlematches"	=> "Coincidencias de título de artículo",
"notitlematches" => "No hay coincidencias de título de artículo",
"textmatches"	=> "Coincidencias de texto de artículo",
"notextmatches"	=> "No hay coincidencias de texto de artículo",
"prevn"			=> "$1 previos",
"nextn"			=> "$1 siguientes",
"viewprevnext"	=> "Ve ($1) ($2) ($3).",
"showingresults" => "Mostrando abajo <b>$1</b> resultados empezando con #<b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: búsquedas no exitosas son causadas a menudo
por búsquedas de palabras comunes como \"la\" o \"de\",
que no están en el índice, o por especificar más de una palabra para buscar( sólo páginas
que contienen todos los términos de una búsqueda aparecerán en el resultado).",
"powersearch" => "Búsqueda",
"powersearchtext" => "
Buscar en espacios de nombre :<br>
$1<br>
$2 Listar redirecciones &nbsp; Buscar $3 $9",

# Preferences page
#
"preferences"	=> "Preferencias",
"prefsnologin" => "No has entrado",
"prefsnologintext"	=> "Debes haber <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrado</a>
para seleccionar preferencias de usuario.",
"prefslogintext" => "Has entrado con el nombre \"$1\".
Tu número de identificación interno es $2.",
"prefsreset"	=> "Las preferencias han sido repuestas desde almacenaje.",
"qbsettings"	=> "Preferencias de \"Quickbar\"", 
"changepassword" => "Cambia contraseña",
"skin"			=> "Piel",
"math"			=> "Cómo se muestran las fórmulas",
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
"textboxsize"	=> "Dimensiones de la caja de texto",
"rows"			=> "Filas",
"columns"		=> "Columnas",
"searchresultshead" => "Preferencias de resultado de búsqueda",
"resultsperpage" => "Resultados para mostrar por página",
"contextlines"	=> "Líneas para mostrar por resultado",
"contextchars"	=> "Caracteres de contexto por línea",
"stubthreshold" => "Umbral de artículo mínimo" ,
"recentchangescount" => "Número de títulos en cambios recientes",
"savedprefs"	=> "Tus preferencias han sido grabadas.",
"timezonetext"	=> "Entra el número de horas de diferencia entre tu hora local
y la hora del servidor (UTC).",
"localtime"	=> "Hora local",
"timezoneoffset" => "Diferencia",
"emailflag"     => "No quiero recibir correo electrónico de otros usuarios",

# Recent changes
#
"changes" => "cambios",
"recentchanges" => "Cambios Recientes",
"recentchangestext" => "Sigue los cambios más recientes a Wikipedia en esta página.
[[Wikipedia:Bienvenidos|Bienvenidos]]!
Por favor, mira estas páginas: [[wikipedia:FAQ|Wikipedia FAQ]],
[[Wikipedia:Políticas y guías|políticas de Wikipedia]]
(especialmente [[wikipedia:Convenciones de nombres|las convenciones para nombrar artículos]]y
[[wikipedia:Punto de vista neutral|punto de vista neutral]]).

Si quieres que Wikipedia tenga éxito, es muy importante que no agregues
material restringido por [[wikipedia:Copyrights|derechos de autor]].
La responsabilidad legal realmente podría dañar  el proyecto, así que por favor no lo hagas.

Ve también [http://meta.wikipedia.org/wiki/Special:Recentchanges discusión reciente en Meta (multilingüe)].",
"rcloaderr"		=> "cargando cambios recientes",
"rcnote"		=> "Abajo están los últimos <b>$1</b> cambios en los últimos <b>$2</b> días.",
"rclistfrom"	=> "Mostrar cambios nuevos desde $1",
"rcnotefrom"	=> "Abajo están los cambios desde <b>$2</b> (se muestran hasta <b>$1</b>).",
"rclinks"		=> "Ver los últimos $1 cambios en los últimos $2 días.",
"rchide"		=> "en forma $4 ; $1 ediciones menores; $2 espacios de nombre secundarios; $3 ediciones múltiples.",
"diff"			=> "diferencias",
"hist"			=> "historia",
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
"uploadnologintext"	=> "Tú debes haber <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrado</a>
para subir archivos.",
"uploadfile"	=> "Subir archivo",
"uploaderror"	=> "Error tratando de subir",
"uploadtext"	=> "Para ver o buscar imágenes que se hayan subido
previamente, ve a la <a href=\"" . wfLocalUrlE( "Especial:Imagelist" ) .
"\">lista de imágenes subidas</a>.
Subidas y borrados son registrados en el <a href=\"" .
wfLocalUrlE( "Wikipedia:Registro de subidas" ) . "\">registro de subidas</a>.
Ve también la <a href=\"" . wfLocalUrlE( "Wikipedia:Política de uso de imágenes" ) .
"\">política de uso de imágenes</a>.
<p>Usa la forma abajo para subir nuevos archivos de imágenes que
vas a usar para ilustrar tus artículos.
En la mayoría de los navegadores, verás un botón \"Browse...\", que
traerá el diálogo de selección de archivos estándar en tu sistema operativo.
Cuando elijas un archivo el nombre de ese archivo aparecerá en el campo de texto
al lado del botón.
También debes marcar la caja afirmando que no estás
violando ningún copyright al subir el archivo.
Presiona el boton \"Subir\" para completar la subida.
Esto puede tomar algún tiempo si tienes una conección a internet lenta.
<p>Los formatos preferidos son JPEG para imágenes fotográficas, PNG
para dibujos y otras imágenes icónicas, y OGG para sonidos.
Por favor, dale a tus archivos nombres descriptivos para evitar confusión.
Para incluir la imagen en un artículo, usa un enlace de la forma
<b>[[imagen:archivo.jpg]]</b> o <b>[[imagen:archivo.png|alt text]]</b>
o <b>[[media:archivo.ogg]]</b> para sonidos.
<p>Por favor nota que, al igual que con páginas Wikipedia, otros pueden
editar o borrar los archivos que has subido si piensan que es bueno para
la enciclopedia, y se te puede bloquear, impidiéndote subir archivos si abusas del sistema.",
"uploadlog"		=> "registro de subidas",
"uploadlogpage" => "Registro_de_subidas",
"uploadlogpagetext" => "Abajo hay una lista de los archivos que se han
subido más recientemente. Todas las horas son del servidor (UTC).
<ul>
</ul>
",
"filename"		=> "Nombre del archivo",
"filedesc"		=> "Sumario",
"affirmation"	=> "Afirmo que el dueño del copyright de este archivo
está de acuerdo en licenciarlo bajo los términos de $1.",
"copyrightpage" => "Wikipedia:Copyrights",
"copyrightpagename" => "Wikipedia copyright",
"uploadedfiles"	=> "Archivos subidos",
"noaffirmation" => "Tú debes afirmar que tus subidas de archivos no violan ningún copyright.",
"ignorewarning"	=> "Ignora la advertencia y graba el archivo de todos modos.",
"minlength"		=> "Los nombres de imágenes deben ser al menos tres letras.",
"badfilename"	=> "El nombre de la imagen se ha cambiado a \"$1\".",
"badfiletype"	=> "\".$1\" no es un formato de imagen recomendado.",
"largefile"		=> "Se recomienda que las imágenes no excedan 100k de tamaño.",
"successfulupload" => "Subida exitosa",
"fileuploaded"	=> "El archivo \"$1\" se subió en forma exitosa.
Por favor sigue este enlace: ($2) a la página de descripción y llena
la información acerca del archivo, tal como de dónde viene, cuándo fue
creado y por quién, y cualquier otra cosa que puedas saber al respecto.",
"uploadwarning" => "Advertencia de subida de archivo",
"savefile"		=> "Grabar archivo",
"uploadedimage" => "\"$1\" subido.",

# Image list
#
"imagelist"		=> "Lista de imágenes",
"imagelisttext"	=> "Abajo hay una lista de $1 imágenes ordenadas $2.",
"getimagelist"	=> " obteniendo la lista de imágenes",
"ilshowmatch"	=> "Muestra todas las imágenes con nombres que coincidan con",
"ilsubmit"		=> "Búsqueda",
"showlast"		=> "Mostrar las últimas $1 imágenes ordenadas  $2.",
"all"			=> "todas",
"byname"		=> "por nombre",
"bydate"		=> "por fecha",
"bysize"		=> "por tamaño",
"imgdelete"		=> "borr",
"imgdesc"		=> "desc",
"imglegend"		=> "Simbología: (desc) = mostrar/editar la descripción de la imagen.",
"imghistory"	=> "Historia de la imagen",
"revertimg"		=> "rev",
"deleteimg"		=> "borr",
"imghistlegend" => "Simbología: (act) = esta es la imagen actual, (borr) = borrar
esta versión antigua, (rev) = revertir a esta versión antigua.
<br><i>Click en la fecha para ver imagen subida en esa fecha</i>.",
"imagelinks"	=> "Enlaces a la imagen",
"linkstoimage"	=> "Las siguientes páginas enlazan a esta imagen:",
"nolinkstoimage" => "No hay páginas que enlacen a esta imagen.",

# Statistics
#
"statistics"	=> "Estadísticas",
"sitestats"		=> "Estadísticas del sitio",
"userstats"		=> "Estadísticas de usuario",
"sitestatstext" => "Hay un total de <b>$1</b> páginas en la base de datos.
Esto incluye páginas de discusión, páginas acerca de Wikipedia, páginas mínimas,
redirecciones, y otras que probablemente no califican como artículos.
Excluyendo aquellas, hay <b>$2</b> páginas que probablemente son artículos legítimos.<p>
Ha habido un total de <b>$3</b> visitas a páginas, y <b>$4</b> ediciones de página
desde que el software fue actualizado (Octubre 2002).
Esto resulta en un promedio de <b>$5</b> ediciones por página, 
y <b>$6</b> visitas por edición.",
"userstatstext" => "Hay <b>$1</b> usuarios registrados.
de los cuales <b>$2</b> son administradores (ver $3).",

# Maintenance Page
#
"maintenance"		=> "Página de mantenimiento",
"maintnancepagetext"	=> "Esta página incluye varias herramientas utiles para mantenimiento diaria. Algunas de estas funciones tienden a sobrecargar la base de datos, asi que, por favor, no vuelvas a cargar la página despues de cada item que arregles ;-)",
"maintenancebacklink"	=> "De vuelta a la Página de Mantenimiento",
"disambiguations"	=> "Páginas de desambiguación",
"disambiguationspage"	=> "Wikipedia:Enlaces a páginas de desambiguación",
"disambiguationstext"	=> "Los siguientes articulos enlazan a una<i>página de desambiguación</i>. Deberían enlazar al tópico apropiado.<br>Una página es considerada una página de desambiguación si es enlazada desde $1.<br>Enlaces desde otros espacios de nombre (Como Wikipedia: o usuario:) <i>no</i> son  listados aquí.",
"doubleredirects"	=> "Redirecciones Dobles",
"doubleredirectstext"	=> "<b>Atención:</b> Esta lista puede contener falsos positivos. Eso significa usualmente que hay texto adicional con enlaces bajo el primer #REDIRECT.<br>\nCada fila continen enlaces al segundo y tercer redirect, así como la primera línea del segundo redirect, lo que usualmente da el artículo \"real\", al que el primer redirect debería apuntar.",
"brokenredirects"	=> "Redirecciones incorrectas",
"brokenredirectstext"	=> "Las redirecciones siguientes enlazan a un artículo que no existe.",
"selflinks"		=> "Páginas con autoenlaces",
"selflinkstext"		=> "Las siguientes páginas contienen un enlace a sí mismas, lo que no es recomendado.",
"mispeelings"       => "Páginas con faltas de ortografía",
"mispeelingstext"               => "Las siguientes páginas contienen una falta de ortografía común, las cuales están listadas en $1. La escritura correcta puede estar dada (como esto).",
"mispeelingspage"       => "Lista de faltas de ortografía comunes",           
"missinglanguagelinks"  => "Enlaces Interleguaje Faltantes",
"missinglanguagelinksbutton"    => "Encontrar los enlaces interlenguaje que faltan para",
"missinglanguagelinkstext"      => "Estos artículos <i>no</i> enlazan a sus contrapartes en $1. <i>No</i> se muestran redirecciones y subpáginas.",


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
"sysopspheading" => "Páginas especiales para uso de sysops",
"developerspheading" => "Páginas especiales para uso de developers",
"protectpage"	=> "Páginas protegidas",
"recentchangeslinked" => "Seguimiento de enlaces",
"rclsub"		=> "(a páginas enlazadas desde \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Páginas nuevas",
"intl"                  => "Enlaces interlenguaje",
"movethispage"	=> "Trasladar esta página",
"unusedimagestext" => "<p>Por favor note que otros sitios web
tales como otras wikipedias pueden enlazar a una imagen
con un URL directo, y de esa manera todavía estar listada aquí
a pesar de estar en uso activo.",
"booksources"   => "Fuentes de libros",
"booksourcetext" => "A continuacion hay una lista de enlaces a otros sitios que venden libros nuevos y usados, y también pueden contener información adicional acerca de los libros que estás buscando.
Wikipedia no está afiliada con ninguno de estos negocios, y esta lista no puede ser considerada un patrocinio.",
"alphaindexline" => "$1 a $2",

# Email this user
#
"mailnologin"	=> "No enviar dirección",
"mailnologintext" => "Debes haber <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrado</a>
y tener una direccion electrónica válida en tus <a href=\"" .
  wfLocalUrl( "Especial:Preferences" ) . "\">preferencias</a>
para enviar correo electrónico a otros usuarios.",
"emailuser"		=> "Envía correo electrónico a este usuario",
"emailpage"		=> "Correo electrónico a usuario",
"emailpagetext"	=> "Si este usuario ha entrado una dirección electrónica válida en sus preferencias de usuario, la forma que sigue sirve para enviarle un mensaje.
La dirección electrónica que entraste en tus preferencias de usuario aparecerá en el remitente, de manera que el destinatario pueda responder.",
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
"nowatchlist"	=> "No tienes ningún ítem en tu lista de seguimiento.",
"watchnologin"	=> "No has iniciado sesión",
"watchnologintext"	=> "Debes haber <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrado</a>
para modificar tu lista de seguimiento.",
"addedwatch"	=> "Añadido a lista de seguimiento",
"addedwatchtext" => "La página \"$1\" ha sido añadida a tu  <a href=\"" .
  wfLocalUrl( "Especial:Watchlist" ) . "\">lista se seguimiento</a>.
Cambios futuros a esta página y su página de discusión asociada será listada ahí,  y la página aparecerá <b>en negritas</b> en la <a href=\"" .
  wfLocalUrl( "Especial:Recentchanges" ) . "\">lista de cambios recientes</a> para hacerla más facil de notar.</p>

<p>Cuando quieras remover la página de tu lista de seguimiento, presiona \"Dejar de vigilar\" en la barra del costado.",
"removedwatch"	=> "Removida de lista de seguimiento",
"removedwatchtext" => "La página \"$1\" ha sido removida de tu lista de seguimiento.",
"watchthispage"	=> "Vigilar esta página",
"unwatchthispage" => "Dejar de vigilar",
"notanarticle"	=> "No es un artículo",

# Delete/protect/revert
#
"deletepage"	=> "Borrar esta página",
"confirm"		=> "Confirma",
"confirmdelete" => "Confirma el borrado",
"deletesub"		=> "(Borrando \"$1\")",
"confirmdeletetext" => "Estás a punto de borrar una página o imagen 
en forma permanente,
así como toda su historia, de la base de datos.
Por favor, confirma que realmente quieres hacer eso, que entiendes las
consecuencias, y que lo estás haciendo de acuerdo con [[Wikipedia:Políticas]].",
"confirmcheck"	=> "Sí, realmente quiero borrar esto.",
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
"reverted"		=> "Revertido a una revisión anterior",
"deletecomment"	=> "Razon para el borrado",
"imagereverted" => "Revertido a una versión anterior fue exitoso.",
"rollback"		=> "Revertir ediciones",
"rollbacklink"	=> "Revertiy",
"cantrollback"	=> "No se pueden revertir las ediciones; el último colaborador es el único autor de este artículo.",
"revertpage"	=> "Revertida a la última edición por $1",

# Undelete
"undelete" => "Restaura una página borrada",
"undeletepage" => "Ve y restora páginas borradas",
"undeletepagetext" => "Las siguientes páginas han sido borradas pero aún están en el archivo y pueden ser restauradas. El archivo puede ser limpiado periódicamente.",
"undeletearticle" => "Restaurar artículo borrado",
"undeleterevisions" => "$1 revisiones archivadas",
"undeletehistory" => "Si tu restauras una página, todas las revisiones serán restauradas a la historia.
Si una nueva página con el mismo nombre ha sido creada desde el borrado, las versiones restauradas aparecerán como historia anterior, y la revisión actual del la página \"viva\" no sera automáticamente reemplazada.",
"undeleterevision" => "Revisión borrada al $1",
"undeletebtn" => "Restaurar!",
"undeletedarticle" => "restaurado \"$1\"",
"undeletedtext"   => "El artículo [[$1]] ha sido restaurado exitosamente.
Ve [[Wikipedia:Registro_de_borrados]] para una lista de borrados y restauraciones recientes.",

# Contributions
#
"contributions"	=> "Contribuciones del usuario",
"mycontris"=>"Mis contribuciones",
"contribsub"	=> "Para $1",
"nocontribs"	=> "No se encontraron cambios que calzaran estos criterios.",
"ucnote"		=> "Abajo están los últimos <b>$1</b> cambios de este usuario en los últimos <b>$2</b> días.",
"uclinks"		=> "Ver los últimos $1 cambios; ver los últimos $2 días.",
"uctop"		=> " (última modificaci&oacute;n)" ,

# What links here
#
"whatlinkshere"	=> "Lo que enlaza aquí",
"notargettitle" => "No hay página blanco",
"notargettext"	=> "No has especificado en qué página
llevar a cabo esta función.",
"linklistsub"	=> "(Lista de enlaces)",
"linkshere"		=> "Las siguientes páginas enlazan aquí:",
"nolinkshere"	=> "Ninguna página enlaza aquí.",
"isredirect"	=> "pagina redirigida",

# Block/unblock IP
#
"blockip"		=> "Bloqueo de direcciones IP",
"blockiptext"	=> "Usa la forma que sigue para bloquear el
acceso de escritura desde una dirección IP específica.
Esto debería ser hecho sólo para prevenir vandalismo, y de
acuerdo a las [[Wikipedia:Políticas| políticas de Wikipedia]].
Llena con una razón específica más abajo (por ejemplo, citando
que páginas en particular estan siendo vadalizadas).",
"ipaddress"		=> "Dirección IP",
"ipbreason"		=> "Razón",
"ipbsubmit"		=> "Bloquear esta dirección",
"badipaddress"	=> "La dirección IP no tiene el formato correcto.",
"noblockreason" => "Debes dar una razón para el bloqueo.",
"blockipsuccesssub" => "Bloqueo exitoso",
"blockipsuccesstext" => "La direccion IP  \"$1\" ha sido bloqueada.
<br>Ve [[Especial:Ipblocklist|lista de IP bloqueadas]] para revisar bloqueos.",
"unblockip"		=> "Desbloquear dirección IP",
"unblockiptext"	=> "Usa la forma que sigue para restaurar 
acceso de escritura a una dirección IP previamente bloqueada.",
"ipusubmit"		=> "Desbloquea esta dirección",
"ipusuccess"	=> "Dirección IP \"$1\" desbloqueada",
"ipblocklist"	=> "Lista de direcciones IP bloqueadas",
"blocklistline"	=> "$1, $2 bloquea $3",
"blocklink"		=> "bloquear",
"unblocklink"	=> "desbloquear",
"contribslink"	=> "contribuciones",

# Developer tools
#
"lockdb"		=> "Lock database",
"unlockdb"		=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"		=> "Lock database",
"unlockbtn"		=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The Wikipedia database has been locked.
<br>Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The Wikipedia database has been unlocked.",

# SQL query
#
"asksql"		=> "Consulta SQL",
"asksqltext"	=> "Usa la forma que sigue para hacer una consulta directa
a la base de datos de Wikipedia. Usa comillas simples ('como estas') para delimitar
cadenas de caracteres literales.
Esto puede añadir una carga considerable al servidor, así que
por favor usa esta función lo menos possible.",
"sqlquery"		=> "Entra la consulta",
"querybtn"		=> "Envía la consulta",
"selectonly"	=> "Consultas diferentes a \"SELECT\" están restringidas sólo
a Wikipedia developers.",
"querysuccessful" => "Consulta exitosa",

# Move page
#
"movepage"		=> "Renombrar página",
"movepagetext"	=> "Usando el formulario que sigue renombrará una página,
moviendo toda su historia al nombre nuevo.
El título anterior se convertirá en un redireccionamiento al nuevo título.
Enlaces al antiguo título de la página no se cambiarán. Asegúrate de [[Especial:Maintenance|verificar]] no dejar redirecciones dobles o rotas.
Tú eres responsable de hacer que los enlaces sigan apuntando adonde se supone que lo hagan. 

Nota que la página '''no''' será trasladada si ya existe una página con el nuevo título, a no ser que sea una página vacía o un ''redirect'' sin historia. 
Esto significa que tu puedes renombrar de vuelta una página a su título original si cometes un error, y que no puedes sobreescribir una página existente.

<b>ADVERTENCIA!</b>
Este puede ser un cambio drástico e inesperado para una página popular;
por favor, asegurate de entender las consecuencias que acarreara
antes de seguir adelante.",
"movepagetalktext" => "La página de discusión asociada, si existe, será trasladad automáticamente '''a menos que:'''
*Estés moviendo la página entre espacios de nombre diferentes,
*Una página de discusión no vacía ya existe con el nombre nuevo, o
*Deseleccionaste la caja abajo.

En esos casos, deberás trasladar o mezclar la página manualmente si lo deseas.",
"movearticle"	=> "Renombrar página",
"movenologin"	=> "No estás dentro de una sesion",
"movenologintext" => "Tu debes ser un usuario registrado y <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">dentro de una sesión</a>
para renombrar una página.",
"newtitle"		=> "A título nuevo",
"movepagebtn"	=> "Renombrar página",
"pagemovedsub"	=> "Renombramiento exitoso",
"pagemovedtext" => "Página \"[[$1]]\" renombrada a \"[[$2]]\".",
"articleexists" => "Ya existe una página con ese nombre, o el nombre que has
escogido no es válido.
Por favor, elije otro nombre.",
"talkexists"	=> "La página misma fue renombrada exitosamente, pero la página de discusión no se pudo mover porque una ya existe en el título nuevo. Por favor incorporarlas manualmente.",
"movedto"		=> "renombrado a",
"movetalk"	=> "Renombrar la página de discusión también, si es aplicable.",
"talkpagemoved" =>  "La página de discusión correspondiente también fue renombrada.",
"talkpagenotmoved" => "La página de discusión correspondiente <strong>no</strong> fue renombrada.",

);

class LanguageEs extends Language {

	# Inherent default user options unless customization is desired

    function getBookstoreList () {
		global $wgBookstoreListEn ;
		return $wgBookstoreListEn ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesEs;
		return $wgNamespaceNamesEs;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesEs;
		return $wgNamespaceNamesEs[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesEs;

		foreach ( $wgNamespaceNamesEs as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}
	#function specialPage( $name ) {
	#	return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	#}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsEs;
		return $wgQuickbarSettingsEs;
	}

	function getSkinNames() {
		global $wgSkinNamesEs;
		return $wgSkinNamesEs;
	}

	function getMathNames() {
		global $wgMathNamesEn;
		return $wgMathNamesEn;
	}


	function getUserToggles() {
		global $wgUserTogglesEs;
		return $wgUserTogglesEs;
	}



	function getLanguageNames() {
		global $wgLanguageNamesEn;
		return $wgLanguageNamesEn;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesEs;
		if ( ! array_key_exists( $code, $wgLanguageNamesEs ) ) {
			return "";
		}
		return $wgLanguageNamesEs[$code];
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesEs;
		return $wgMonthNamesEs[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsEs;
		return $wgMonthAbbreviationsEs[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesEs;
		return $wgWeekdayNamesEs[$key-1];
	}

	# Inherit userAdjust()
        
	function shortdate( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " de " .$this->getMonthName( substr( $ts, 4, 2 ) ) . ", " .
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . " " . $this->shortdate( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesEs;
		return $wgValidSpecialPagesEs;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesEs;
		return $wgSysopSpecialPagesEs;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesEs;
		return $wgDeveloperSpecialPagesEs;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesEs, $wgAllMessagesEn;
		$m = $wgAllMessagesEs[$key];

		if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
		else return $m;
	}
}

?>