<?

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesEs = array(
	-1	=> "Especial",
	0	=> "",
	1	=> "Discusi�n",
	2	=> "Usuario",
	3	=> "Usuario_Discusi�n",
	4	=> "Wikipedia",
	5	=> "Wikipedia_Discusi�n",
	6	=> "Imagen",
	7	=> "Imagen_Discusi�n"
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
	"HTML si es posible, si no PNG",
	"Dejar como TeX (para navegadores de texto)",
        "Recomendado para navegadores modernos"
);

/* private */ $wgUserTogglesEs = array(
	"hover"		=> "Mostrar caja flotante sobre los enlaces wiki",
	"underline" => "Subrayar enlaces",
	"highlightbroken" => "Destacar enlaces a art�culos vac�os <a href=\"\" class=\"new\">como este</a> (alternativa: como �ste<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Ajustar p�rrafos",
	"hideminor" => "Esconder ediciones menores en Cambios Recientes",
	"usenewrc" => "Cambios Recientes realzados (no para todos los navegadores)",
	"numberheadings" => "Auto-numerar encabezados",
	"rememberpassword" => "Recordar la contrase�a entre sesiones",
	"editwidth" => "La caja de edici�n tiene el ancho m�ximo",
	"editondblclick" => "Editar p�ginas con doble click (JavaScript)",
	"watchdefault" => "Vigilar art�culos nuevos y modificados",
	"minordefault" => "Marcar todas las ediciones como menores por defecto",
	"previewontop" => "Mostrar la previsualizaci�n antes de la caja de edici�n en lugar de despu�s"
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
	"sq"	=> "Shqip (Alban�s)",
	"am"	=> "Amh�rico",
	"ar"	=> "&#8238;&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;&#8236; (Araby,�rabe)",
	"hy"	=> "Armenio",
	"as"	=> "Assamese",
	"ay"	=> "Aymara",
	"az"	=> "Azerbaijani",
	"ba"	=> "Bashkir",
	"eu"	=> "Euskara (Vasco)",
	"be"	=> "Bielorruso",
	"bn"	=> "Bengal�",
        "bs"    => "Bosnio (Bosanski)",
	"dz"	=> "Bhutan�",
	"bh"	=> "Bihara",
	"bi"	=> "Bislama",
	"my"	=> "Birmano",
	"km"	=> "Camboyano",
	"ca"	=> "Catal�(Catal�n)",
	"co"	=> "Corso",
	"hr"	=> "Croata",
	"cs"	=> "&#268;esk�(Checo)",
	"da"	=> "Dansk(Dan�s)", # Note two different subdomains. 
	"dk"	=> "Dansk(Dan�s)", # 'da' is correct for the language.
	"nl"	=> "Nederlands (Holand�s)",
	"en"	=> "English (Ingl�s)",
	"simple" => "Ingl�s Simple",
	"eo"	=> "Esperanto",
	"et"	=> "Eesti (Estoniano)",
	"fo"	=> "Fero�s",
	"fj"	=> "Fiji�s",
	"fi"	=> "Suomi (Finland�s)",
	"fr"	=> "Fran&#231;ais (Franc�s)",
	"fy"	=> "Frisio",
	"gl"	=> "Gallego",
	"ka"	=> "Georgiano",
	"de"	=> "Deutsch(Alem�n)",
	"el"	=> "&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940; (Ellenika,Griego)",
        "cy"    => "Cymraeg (Gal�s)",
	"kl"	=> "Groenland�s",
	"gn"	=> "Guaran�",
	"gu"	=> "Gujarat�",
	"ha"	=> "Hausa",
	"he"	=> "????? (Ivrit, Hebreo)",
	"hi"	=> "Hindi",
	"hu"	=> "Magyar (H�ngaro)",
	"is"	=> "Island�s",
	"id"	=> "Bahasa Indonesia (Indonesio)",
	"ia"	=> "Interlingua",
	"iu"	=> "Inuktitut (Esquimal)",
	"ik"	=> "Inupiak",
	"ga"	=> "Irland�s",
	"it"	=> "Italiano",
	"ja"	=> "&#26085;&#26412;&#35486; (Nihongo,Japon�s)",
	"jv"	=> "Javan�s",
	"kn"	=> "Kannada",
	"ks"	=> "Kashmir (Cachemiro)",
	"kk"	=> "Kazakh (Cazaco)",
	"rw"	=> "Kinyarwanda",
	"ky"	=> "Kirghiz",
	"rn"	=> "Kirundi",
	"ko"	=> "&#54620;&#44397;&#50612; (Hangukeo,Coreano)",
	"lo"	=> "Laotiano",
	"la"	=> "Latina",
	"lv"	=> "Let�n",
	"ln"	=> "Lingala",
	"lt"	=> "Lituano",
	"mk"	=> "Macedonio",
	"mg"	=> "Malagasy (Malgache)",
	"ms"	=> "Bahasa Melayu (Malayo)",
	"ml"	=> "Malayalam",
	"mi"	=> "Maor�",
	"mr"	=> "Marathi",
	"mo"	=> "Moldavo",
	"mn"	=> "Mongol",
	"na"	=> "Nauru",
	"ne"	=> "Nepal�",
	"no"	=> "Noruego",
	"oc"	=> "Occitano",
	"or"	=> "Oriya",
	"om"	=> "Oromo",
	"ps"	=> "Pashto",
	"fa"	=> "Persa",
	"pl"	=> "Polski (Polaco)",
	"pt"	=> "Portugu&#234;s (Portugu�s)",
	"pa"	=> "Punjab�",
	"qu"	=> "Quechua",
	"rm"	=> "Rhaeto-Romance (Retorrom�nico)",
	"ro"	=> "Rumano",
	"ru"	=> "??????? (Russkiy, Ruso)",
	"sm"	=> "Samoano",
	"sg"	=> "Sangro",
	"sa"	=> "S�nscrito",
	"sr"	=> "?????? (Srpski, Serbio)",
	"sh"	=> "Serbocroata",
	"st"	=> "Sesotho",
	"tn"	=> "Setswana",
	"sn"	=> "Shona",
	"sd"	=> "Sindhi",
	"si"	=> "Cingal�s",
	"ss"	=> "Siswati",
	"sk"	=> "Eslovaco",
	"sl"	=> "Esloveno",
	"so"	=> "Somal�",
	"es"	=> "Castellano",
	"su"	=> "Sudan�s",
	"sw"	=> "Swahili (Suahel�)",
	"sv"	=> "Svensk (Sueco)",
	"tl"	=> "Tagalog (T�galo)",
	"tg"	=> "Tajik (Tayico)",
	"ta"	=> "Tamil",
	"tt"	=> "Tatar",
	"te"	=> "Telugu",
	"th"	=> "Tailand�s",
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
	"uz"	=> "Uzbek (Uzbeco)",
	"vi"	=> "Vietnamita",
	"vo"	=> "Volap�k",
	"cy"	=> "Gal�s",
	"wo"	=> "Wolof",
	"xh"	=> "Xhosa",
	"yi"	=> "Yiddish",
	"yo"	=> "Yoruba",
	"za"	=> "Zhuang",
	"zh" => "&#20013;&#25991; (Zhongwen,Chino)",
	"zu"	=> "Zul�"
);

/* private */ $wgWeekdayNamesEs = array(
	"Domingo", "Lunes", "Martes", "Mi�rcoles", "Jueves",
	"Viernes", "S�bado"
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
	"Recentchanges" => "Cambios Recientes",
	"Upload"		=> "Subir una imagen",
	"Imagelist"		=> "Lista de im�genes",
	"Listusers"		=> "Usuarios registrados",
	"Statistics"	=> "Estad�sticas del sitio",
	"Randompage"	=> "Art�culo aleatorio",

	"Lonelypages"	=> "Art�culos hu�rfanos",
	"Unusedimages"	=> "Im�genes hu�rfanas",
	"Popularpages"	=> "Art�culos populares",
	"Wantedpages"	=> "Art�culos m�s solicitados",
	"Shortpages"	=> "Art�culos cortos",
	"Longpages"		=> "Art�culos largos",
	"Newpages"		=> "Art�culos nuevos",
	"Intl"		=> "Enlaces Interlenguaje",
	"Allpages"		=> "Todas las p�ginas (alfab�tico)",

	"Ipblocklist"	=> "Direcciones IP bloqueadas",
	"Maintenance"   => "P�gina de mantenimiento",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"   => "Fuentes externas de libros"
);

/* private */ $wgSysopSpecialPagesEs = array(
	"Blockip"		=> "Bloquear una direcci�n IP",
	"Asksql"		=> "B�squeda en la base de datos",
	"Undelete"      => "Ver y restaurar p�ginas borradas"
);

/* private */ $wgDeveloperSpecialPagesEs = array(
	"Lockdb"		=> "Cerrar acceso de escritura a la base de datos",
	"Unlockdb"		=> "Restaurar acceso de escritura a la base de datos",
	"Debug"			=> "Debugging information"
);

/* private */ $wgAllMessagesEs = array(

# Bits of text used by many pages:
#
"linktrail"     => "/^([a-z������]+)(.*)\$/sD",
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
"edithelp"		=> "Ayuda de edici�n",
"edithelppage"	=> "Wikipedia:C�mo_se_edita_una_p�gina",
"cancel"		=> "Cancelar",
"qbfind"		=> "Encontrar",
"qbbrowse"		=> "Hojear",
"qbedit"		=> "Editar",
"qbpageoptions" => "Opciones de p�gina",
"qbpageinfo"	=> "Informaci�n de p�gina",
"qbmyoptions"	=> "Mis opciones",
"mypage"		=> "Mi p�gina",
"mytalk"        => "Mi discusi�n",
"currentevents" => "Actualidad",
"errorpagetitle" => "Error",
"returnto"		=> "Regresa a $1.",
"fromwikipedia"	=> "De Wikipedia, la enciclopedia libre.",
"whatlinkshere"	=> "P�ginas que enlazan aqu�",
"help"			=> "Ayuda",
"search"		=> "Buscar",
"go"		=> "Ir",
"history"		=> "Historia",
"printableversion" => "Versi�n para imprimir",
"editthispage"	=> "Edita esta p�gina",
"deletethispage" => "Borra esta p�gina",
"protectthispage" => "Proteje esta p�gina",
"unprotectthispage" => "Desproteje esta p�gina",
"newpage" => "P�gina nueva",
"talkpage"		=> "Discute esta p�gina",
"articlepage"   => "Ver art�culo",
"subjectpage"	=> "Art�culo",
"userpage" => "Ver p�gina de usuario",
"wikipediapage" => "Ver p�gina meta",
"imagepage" => 	"Ver p�gina de imagen",
"viewtalkpage" => "Ver discusi�n",
"otherlanguages" => "Otros idiomas",
"redirectedfrom" => "(Redirigido desde $1)",
"lastmodified"	=> "Esta p�gina fue modificada por �ltima vez el $1.",
"viewcount"		=> "Esta p�gina ha sido visitada $1 veces.",
"gnunote" => "Todo el texto se hace disponible bajo los t�rminos de la <a class=internal href='/wiki/GNU_FDL'>Licencia de Documentaci�n Libre GNU (GNU FDL)",
"printsubtitle" => "(De http://es.wikipedia.org)",
"protectedpage" => "P�gina protegida",
"administrators" => "Wikipedia:Administradores",
"sysoptitle"	=> "Acceso de Sysop requerido",
"sysoptext"		=> "La acci�n que has requerido s�lo puede ser llevada a cabo
 por usuarios con status de \"sysop\".
Ver $1.",
"developertitle" => "Acceso de developer requerido",
"developertext"	=> "La acci�n que has requerido s�lo puede ser llevada a cabo 
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
"nosuchaction"	=> "No existe tal acci�n",
"nosuchactiontext" => "La acci�n especificada por el URL no es
 reconocida por el software de Wikipedia",
"nosuchspecialpage" => "No existe esa p�gina especial",
"nospecialpagetext" => "Has requerido una p�gina especial que no es
 reconocida por el software de Wikipedia.",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Error de la base de datos",
"dberrortext"	=> "Ha ocurrido un error de sintaxis en una consulta
a la base de datos. 
Esto puede ser debido a una b�squeda ilegal (ver $5),
o puede indicar un error en el software.
La �ltima consulta que se intent� fue:
<blockquote><tt>$1</tt></blockquote>El error de retorno de 
MySQL fue\"<tt>$3: $4</tt>\".",
"dberrortextcl" => "Ha ocurrido un error de sintaxis en una consulta
a la base de datos.
La �ltima consulta que se intent� fue:
\"$1\"
desde la funci&oacute;n \"$2\".
MySQL retorn&oacute; el error \"$3: $4\".\n",
"noconnect"		=> "No se pudo conectar a la base de datos en $1",
"nodb"			=> "No se pudo seleccionar la base de datos $1",
"readonly"		=> "Base de datos bloqueada",
"enterlockreason" => "Explica el motivo del bloqueo, incluyendo una estimaci�n de cu�ndo se producir� el desbloqueo",
"readonlytext"	=> "La base de datos de Wikipedia est� temporalmente
bloqueada para nuevas entradas u otras modificaciones, probablemente
para mantenimiento de rutina, despu�s de lo cual volver� a la normalidad.
El administrador que la bloque� ofreci� esta explicaci�n:
<p>$1",
"missingarticle" => "La base de datos no encontr� el texto de una
p�gina que deber�a haber encontrado, llamada \"$1\".

<p>Esto es causado usualmente por seguir un enlace a una diferencia de p�ginas o historia obsoleta a una p�gina que ha sido borrada.

<p>Si esta no es la causa, puedes haber encontrado un error en el software. Por favor, reporta esto a un administrador,
notando el URL.",
"internalerror" => "Error interno",
"filecopyerror" => "No se pudo copiar el archivo \"$1\" a \"$2\".",
"filerenameerror" => "No se pudo renombrar el archivo \"$1\" a \"$2\".",

"filedeleteerror" => "No se pudo borrar el archivo \"$1\".",
"filenotfound"	=> "No se pudo encontrar el archivo \"$1\".",
"unexpected"	=> "Valor no esperado: \"$1\"=\"$2\".",
"formerror"		=> "Error: no se pudo submitir la forma",	
"badarticleerror" => "Esta acci�n no se puede llevar a cabo en esta p�gina.",
"cannotdelete"	=> "No se pudo borrar la p�gina o imagen especificada. (Puede haber sido borrada por alguien antes)",
"badtitle"		=> "T&iacute;tulo incorrecto",
"badtitletext"	=> "El t&iacute;tulo de la p�gina requerida era inv�lido, vac&iacute;o, o un enlace interleguaje o interwiki incorrecto.",

"perfdisabled" => "Lo siento, esta funci�n est� temporalmente desactivada porque ralentiza la base de datos a tal punto que nadie puede usar el wiki. Ser� reescrita para mayor eficiencia en el futuro) probablemente por ti!=",

# Login and logout pages&iacute;tulo
"logouttitle"	=> "Fin de sesi�n",
"logouttext"	=> "Has terminado tu sesi�n.
Puedes continuar usando Wikipedia en forma an�nima, o puedes
iniciar sesi�n otra vez como el mismo u otro usuario.\n",

"welcomecreation" => "<h2>Bienvenido(a), $1!</h2><p>Tu cuenta ha sido creada. 
No olvides perzonalizar tus preferencia de Wikipedia.",

"loginpagetitle" => "Registrarse/Entrar",
"yourname"		=> "Tu nombre de usuario",
"yourpassword"	=> "Tu contrase�a",
"yourpasswordagain" => "Repite tu contrase�a",
"newusersonly"	=> " (s�lo usuarios nuevos)",
"remembermypassword" => "Quiero que recuerden mi contrase�a entre sesiones.",
"loginproblem"	=> "<b>Hubo un problema con tu entrada.</b><br>�Int�ntalo otra vez!",
"alreadyloggedin" => "<font color=red><b>Usuario $1, ya entraste!</b></font><br>\n",

"areyounew"		=> "Si eres nuevo en Wikipedia en Espa�ol y
quieres tener una cuenta de usuario, ingresa un nombre de usuario,
y escribe y repite una contrase�a.
Tu direcci�n electr�nica es opcional: si pierdes u olvidas tu
contrase�a, puedes pedir que se env�e a la direcci�n que des.  <br>\n",
"login"			=> "Registrarse/Entrar",
"userlogin"		=> "Registrarse/Entrar",
"logout"		=> "Salir",
"userlogout"	=> "Salir",
"createaccount"	=> "Crea una nueva cuenta",
"badretype"		=> "Las contrase�as que ingresaste no concuerdan.",
"userexists"	=> "El nombre que entraste ya est� en uso. Por favor, elije un nombre diferente.",
"youremail"		=> "Tu direcci�n electr�nica (e-mail)",
"yournick"		=> "Tu apodo (para firmas)",
"emailforlost"	=> "Ingresar una direcci�n electr�nica es opcional, pero permite a los dem�s usuarios contactar contigo a trav&ecute;s del sitio web sin tener que revelarles tu direcci�n electr�nica. Adem�s, si pierdes u olvidas tu contrase�a, puedes pedir que se env�e una nueva a tu direcci�n electr�nica.", 
"loginerror"	=> "Error de inicio de sesi�n",
"noname"		=> "No has especificado un nombre de usuario v�lido.",
"loginsuccesstitle" => "Inicio de sesi�n exitoso",
"loginsuccess"	=> "Has iniciado tu sesi�n en Wikipedia como \"$1\".",
"nosuchuser"	=> "No existe usuario alguno llamado \"$1\".
Revisa tu deletreo, o usa la forma abajo para crear una nueva cuenta de usuario.",
"wrongpassword"	=> "La contrase�a que ingresaste es incorrecta. Por favor int�ntalo de nuevo.",
"mailmypassword" => "Env�ame una nueva contrase�a por correo electr�nico",
"passwordremindertitle" => "Recordatorio de contrase�a de Wikipedia",
"passwordremindertext" => "Alguien (probablemente t�, desde la direccion IP $1)
solicit� que te enviaramos una nueva contrase�a para iniciar sesi�n en Wikipedia.
La contrase�a para el usuario \"$2\" es ahora \"$3\".
Ahora deber�as iniciar sesion y cambiar tu contrase�a.",
"noemail"		=> "No hay direcci�n electr�nica (e-mail) registrada para el(la) usuario(a) \"$1\".",
"passwordsent"	=> "Una nueva contrase�a ha sido enviada a la direcci�n electr�nica registrada para \"$1\".
Por favor entra otra vez despu�s de que la recibas.",

# Edit pages
#
"summary"		=> "Resumen",
"minoredit"		=> "Esta es una edici�n menor.",
"watchthis"		=> "Vigila este art�culo.",
"savearticle"	=> "Grabar la p�gina",
"preview"		=> "Previsualizar",
"showpreview"	=> "Mostrar previsualizaci�n",
"blockedtitle"	=> "El usuario est� bloqueado",
"blockedtext"	=> "Tu nombre de usuario o direcci�n IP ha sido bloqueada por $1.
La raz�n dada es la que sigue:<br>$2<p> Puedes contactar a $1 o a otro de los Wikipedia:Administradores|administradores]] para
discutir el bloqueo.",
"newarticle"	=> "(Nuevo)",
"newarticletext" => "Wikipedia es una enciclopedia en desarrollo, y esta p�gina a&uacute;n no existe. Puedes pedir informaci�n en [[Wikipedia:Consultas]], pero no esperes una respuesta pronta. Si quieres crear esta p�gina, empieza a escribir en la caja que sigue. Si llegaste aqu� por error, presiona la tecla para volver a la p�gina anterior de tu navegador.",
"anontalkpagetext" => "---- ''Esta es la p�gina de discusi�n para un usuario an�nimo que a&uacute;n no ha creado una cuenta, o no la usa. Por lo tanto, tenemos que usar su [[direcci�n IP]] num&eacute;rica para adentificarlo. Una direcci�n IP puede ser compartida por varios usuarios. Si eres un usuario an�nimo y sientes que comentarios irreleventes han sido dirigidos a ti, por favor [[Especial:Userlogin|crea una cuenta o entra]] para evitar confusiones futuras con otros usuarios an�nimos.'' ",
"noarticletext" => "(En este momento no hay texto en esta p�gina)",
"updated"		=> "(Actualizado)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Recuerda que esto es s�lo una previsualizaci�n, y no ha sido grabada todav�a!",
"previewconflict" => "Esta previsualizaci�n refleja el texto en el �rea
de edici�n superior como aparecer� si eliges grabar.",
"editing"		=> "Editando $1",
"editconflict"	=> "Conflicto de edici�n: $1",
"explainconflict" => "Alguien m�s ha cambiado esta p�gina desde que empezaste
a editarla. 
El �rea de texto superior contiene el texto de la p�gina como existe
actualmente. Tus cambios se muestran en el �rea de texto inferior.
Vas a tener que incorporar tus cambios en el texto existente.
<b>S�lo</b> el texto en el �rea de texto superior ser� grabado cuando presiones
 \"Grabar pagina\".\n<p>",
"yourtext"		=> "Tu texto",
"storedversion" => "Versi�n almacenada",
"editingold"	=> "<strong>ADVERTENCIA: Est�s editando una versi�n antigua
 de esta p�gina.
Si la grabas, los cambios hechos desde esa revisi�n se perder�n.</strong>\n",
"yourdiff"		=> "Diferencias",
"copyrightwarning" => "Nota por favor que todas las contribuciones a Wikipedia 
se consideran hechas p�blicas bajo la Licencia de Documentaci�n Libre GNU 
(ver detalles en $1). 
 Si no deseas que la gente corrija tus escritos sin piedad 
y los distribuya libremente, entonces no los pongas aqu�. <br>
Tambi�n t� nos aseguras que escribiste esto t� mismo y 
eres due�o de los derechos de autor, o lo copiaste desde el dominio p�blico 
u otra fuente libre.
 <strong>NO USES ESCRITOS CON COPYRIGHT SIN PERMISO!</strong><br>",
"longpagewarning" => "ADVERTENCIA: Esta p�gina tiene un tama&ntilde;o de $1 kilobytes; algunos navegadores pueden tener problemas editando p�ginas de 32kb o m�s.
Por favor considera la posibilidad de descomponer esta p�gina en secciones m�s peque&ntilde;as.",
"readonlywarning" => "ADVERTENCIA: La base de datos ha sido bloqueada para mantenimiento, as� que no podr�s grabar tus modificaciones en este momento. 
Puedes \"cortar y pegar\" a un archivo de texto en tu computador, y grabarlo para
intentarlo despu&eacute;s.",
"protectedpagewarning" => "ADVERTENCIA: Esta p�gina ha sido bloqueda de manera que s&ocute;lo usuarios con privilegios de administrador pueden editarla. Aseg�rate de que est�s siguiendo las 
<a href='/wiki/Wikipedia:Gu�as_para_p�ginas_protegidas'>gu�as para p�ginas protegidas</a>.",
# History pages
#
"revhistory"	=> "Historia de revisiones",
"nohistory"		=> "No hay una historia de ediciones para esta p�gina.",
"revnotfound"	=> "Revisi�n no encontrada",
"revnotfoundtext" => "La revisi�n antigua de la p�gina por la que preguntaste no se pudo encontrar.
Por favor revisa el URL que usaste para acceder a esta p�gina.\n",
"loadhist"		=> "Recuperando la historia de la p�gina",
"currentrev"	=> "Revisi�n actual",
"revisionasof"	=> "Revisi�n de $1",
"cur"			=> "act",
"next"			=> "sig",
"last"			=> "prev",
"orig"			=> "orig",
"histlegend"	=> "Simbolog�a: (act) = diferencia con la versi�n actual,
(prev) = diferencia con la versi�n previa, M = edici�n menor",

# Diffs
#
"difference"	=> "(Diferencia entre revisiones)",
"loadingrev"	=> "recuperando revisi�n para diff",
"lineno"		=> "L�nea $1:",
"editcurrent"	=> "Edita la versi�n actual de esta p�gina",

# Search results
#
"searchresults" => "Resultados de b�squeda",
"searchhelppage" => "Wikipedia:B�squeda",
"searchingwikipedia" => "Buscando en Wikipedia",
"searchresulttext" => "Para m�s informaci�n acerca de b�squedas en Wikipedia, ve a $1.",
"searchquery"	=> "Para consulta \"$1\"",
"badquery"		=> "Consulta de b�squeda formateada en forma incorrecta",
"badquerytext"	=> "No pudimos procesar tu b�squeda.
Esto es probablemente porque intentaste buscar una palabra de menos de tres letras, lo que todav�a no es posible.
Tambi�n puede ser que hayas cometido un error de escritura en la expresi�n.
Por favor, intenta una b�squeda diferente.",
"matchtotals"	=> "La consulta \"$1\" coincidi� con $2  t�tulos de art�culos
y el texto de $3 art�culos.",
"nogomatch" => "No existe ninguna p�gina con este exactamente este t�tulo, intentando una b�squeda en todo el texto.",
"titlematches"	=> "Coincidencias de t�tulo de art�culo",
"notitlematches" => "No hay coincidencias de t�tulo de art�culo",
"textmatches"	=> "Coincidencias de texto de art�culo",
"notextmatches"	=> "No hay coincidencias de texto de art�culo",
"prevn"			=> "$1 previos",
"nextn"			=> "$1 siguientes",
"viewprevnext"	=> "Ver ($1) ($2) ($3).",
"showingresults" => "Mostrando abajo <b>$1</b> resultados empezando con #<b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: b�squedas no exitosas son causadas a menudo
por b�squedas de palabras comunes como \"la\" o \"de\",
que no est�n en el �ndice, o por especificar m�s de una palabra para buscar (s�lo las p�ginas
que contengan todos los t�rminos de una b�squeda aparecer�n en el resultado).",
"powersearch" => "B�squeda",
"powersearchtext" => "
Buscar en espacios de nombre :<br>
$1<br>
$2 Listar redirecciones &nbsp; Buscar $3 $9",

# Preferences page
#
"preferences"	=> "Preferencias",
"prefsnologin" => "No has entrado",
"prefsnologintext"	=> "Debes <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">entrar</a>
para seleccionar preferencias de usuario.",
"prefslogintext" => "Has entrado con el nombre \"$1\".
Tu n�mero de identificaci�n interno es $2.",
"prefsreset"	=> "Las preferencias han sido repuestas desde almacenaje.",
"qbsettings"	=> "Preferencias de \"Quickbar\"", 
"changepassword" => "Cambia contrase�a",
"skin"			=> "Piel",
"math"			=> "C�mo se muestran las f�rmulas",
"math_failure"		=> "No se pudo entender",
"math_unknown_error"	=> "error desconocido",
"math_unknown_function"	=> "funci�n desconocida",
"math_lexing_error"	=> "error de l�xico",
"math_syntax_error"	=> "error de sintaxis",
"saveprefs"		=> "Grabar preferencias",
"resetprefs"	=> "Volver a preferencias por defecto",
"oldpassword"	=> "Contrase�a antigua",
"newpassword"	=> "Contrase�a nueva",
"retypenew"		=> "Reescriba la nueva contrase�a",
"textboxsize"	=> "Dimensiones de la caja de texto",
"rows"			=> "Filas",
"columns"		=> "Columnas",
"searchresultshead" => "Preferencias de resultado de b�squeda",
"resultsperpage" => "Resultados para mostrar por p�gina",
"contextlines"	=> "L�neas para mostrar por resultado",
"contextchars"	=> "Caracteres de contexto por l�nea",
"stubthreshold" => "Umbral de art�culo m�nimo" ,
"recentchangescount" => "N�mero de t�tulos en cambios recientes",
"savedprefs"	=> "Tus preferencias han sido grabadas.",
"timezonetext"	=> "Entra el n�mero de horas de diferencia entre tu hora local
y la hora del servidor (UTC).",
"localtime"	=> "Hora local",
"timezoneoffset" => "Diferencia",
"emailflag"     => "No quiero recibir correo electr�nico de otros usuarios",

# Recent changes
#
"changes" => "cambios",
"recentchanges" => "Cambios Recientes",
"recentchangestext" => "Sigue los cambios m�s recientes a Wikipedia en esta p�gina.
[[Wikipedia:Bienvenidos|Bienvenidos]]!
Por favor, mira estas p�ginas: [[wikipedia:FAQ|Wikipedia FAQ]],
[[Wikipedia:Pol�ticas y gu�as|pol�ticas de Wikipedia]]
(especialmente [[wikipedia:Convenciones de nombres|las convenciones para nombrar art�culos]] y
[[wikipedia:Punto de vista neutral|punto de vista neutral]]).

Si quieres que Wikipedia tenga �xito, es muy importante que no agregues
material restringido por [[wikipedia:Copyrights|derechos de autor]].
La responsabilidad legal realmente podr�a da�ar el proyecto, as� que por favor no lo hagas.

Ve tambi�n [http://meta.wikipedia.org/wiki/Special:Recentchanges discusi�n reciente en Meta (multiling�e)].",
"rcloaderr"		=> "cargando cambios recientes",
"rcnote"		=> "Abajo est�n los �ltimos <b>$1</b> cambios en los �ltimos <b>$2</b> d�as.",
"rclistfrom"	=> "Mostrar cambios nuevos desde $1",
"rcnotefrom"	=> "Abajo est�n los cambios desde <b>$2</b> (se muestran hasta <b>$1</b>).",
"rclinks"		=> "Ver los �ltimos $1 cambios en los �ltimos $2 d�as.",
"rchide"		=> "en forma $4 ; $1 ediciones menores; $2 espacios de nombre secundarios; $3 ediciones m�ltiples.",
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
"uploadlink"	=> "Subir im�genes",
"reupload"		=> "Subir otra vez",
"reuploaddesc"	=> "Regresar al formulario para subir.",
"uploadnologin" => "No has iniciado sesi�n",
"uploadnologintext"	=> "T� debes <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">iniciar sesi�n</a>
para subir archivos.",
"uploadfile"	=> "Subir archivo",
"uploaderror"	=> "Error tratando de subir",
"uploadtext"	=> "Para ver o buscar im�genes que se hayan subido
previamente, ve a la <a href=\"" . wfLocalUrlE( "Especial:Imagelist" ) .
"\">lista de im�genes subidas</a>.
Los archivos subidos y borrados son registrados en el <a href=\"" .
wfLocalUrlE( "Wikipedia:Registro de subidas" ) . "\">registro de subidas</a>.
Consulta tambi�n la <a href=\"" . wfLocalUrlE( "Wikipedia:Pol�tica de uso de im�genes" ) .
"\">pol�tica de uso de im�genes</a>.
<p>Usa el formulario siguiente para subir nuevos archivos de im�genes que
vas a usar para ilustrar tus art�culos.
En la mayor�a de los navegadores, ver�s un bot�n \"Browse...\", que
abrir� el di�logo de selecci�n de archivos est�ndar de tu sistema operativo.
Cuando hayas elegido un archivo, su nombre aparecer� en el campo de texto
al lado del bot�n \"Examinar...\".
Tambi�n debes marcar la caja afirmando que no est�s
violando ning�n copyright al subir el archivo.
Presiona el boton \"Subir\" para completar la subida.
Esto puede tomar alg�n tiempo si tienes una conecci�n a internet lenta.
<p>Los formatos preferidos son JPEG para im�genes fotogr�ficas, PNG
para dibujos y diagramas, y OGG para sonidos.
Por favor, dale a tus archivos nombres descriptivos para evitar confusiones.
Para incluir la imagen en un art�culo, usa un enlace de la forma
<b>[[imagen:archivo.jpg]]</b> o <b>[[imagen:archivo.png|alt text]]</b>
o <b>[[media:archivo.ogg]]</b> para sonidos.
<p>Por favor recuerda que, al igual que con las p�ginas Wikipedia, otros pueden
editar o borrar los archivos que has subido si piensan que es bueno para
la enciclopedia, y se te puede bloquear, impidi�ndote subir m�s archivos si abusas del sistema.",
"uploadlog"		=> "registro de subidas",
"uploadlogpage" => "Registro_de_subidas",
"uploadlogpagetext" => "Abajo hay una lista de los archivos que se han
subido m�s recientemente. Todas las horas son del servidor (UTC).
<ul>
</ul>
",
"filename"		=> "Nombre del archivo",
"filedesc"		=> "Sumario",
"affirmation"	=> "Afirmo que el due�o del copyright de este archivo
est� de acuerdo en licenciarlo bajo los t�rminos de $1.",
"copyrightpage" => "Wikipedia:Copyrights",
"copyrightpagename" => "Wikipedia copyright",
"uploadedfiles"	=> "Archivos subidos",
"noaffirmation" => "T� debes afirmar que tus subidas de archivos no violan ning�n copyright.",
"ignorewarning"	=> "Ignora la advertencia y graba el archivo de todos modos.",
"minlength"		=> "Los nombres de im�genes deben ser al menos de tres letras.",
"badfilename"	=> "El nombre de la imagen se ha cambiado a \"$1\".",
"badfiletype"	=> "\".$1\" no es un formato de imagen recomendado.",
"largefile"		=> "Se recomienda que las im�genes no excedan 100k de tama�o.",
"successfulupload" => "Subida exitosa",
"fileuploaded"	=> "El archivo \"$1\" se subi� en forma exitosa.
Por favor sigue este enlace: ($2) a la p�gina de descripci�n y escribe
la informaci�n acerca del archivo, tal como de d�nde viene, cu�ndo fue
creado y por qui�n, y cualquier otra cosa que puedas saber al respecto.",
"uploadwarning" => "Advertencia de subida de archivo",
"savefile"		=> "Grabar archivo",
"uploadedimage" => "\"$1\" subido.",

# Image list
#
"imagelist"		=> "Lista de im�genes",
"imagelisttext"	=> "Abajo hay una lista de $1 im�genes ordenadas $2.",
"getimagelist"	=> " obteniendo la lista de im�genes",
"ilshowmatch"	=> "Muestra todas las im�genes con nombres que coincidan con",

"ilsubmit"		=> "B�squeda",
"showlast"		=> "Mostrar las �ltimas $1 im�genes ordenadas  $2.",
"all"			=> "todas",
"byname"		=> "por nombre",
"bydate"		=> "por fecha",
"bysize"		=> "por tama�o",
"imgdelete"		=> "borr",
"imgdesc"		=> "desc",
"imglegend"		=> "Simbolog�a: (desc) = mostrar/editar la descripci�n de la imagen.",
"imghistory"	=> "Historia de la imagen",
"revertimg"		=> "rev",
"deleteimg"		=> "borr",
"imghistlegend" => "Simbolog�a: (act) = esta es la imagen actual, (borr) = borrar
esta versi�n antigua, (rev) = revertir a esta versi�n antigua.
<br><i>Click en la fecha para ver imagen subida en esa fecha</i>.",
"imagelinks"	=> "Enlaces a la imagen",
"linkstoimage"	=> "Las siguientes p�ginas enlazan a esta imagen:",
"nolinkstoimage" => "No hay p�ginas que enlacen a esta imagen.",

# Statistics
#
"statistics"	=> "Estad�sticas",
"sitestats"		=> "Estad�sticas del sitio",
"userstats"		=> "Estad�sticas de usuario",
"sitestatstext" => "Hay un total de <b>$1</b> p�ginas en la base de datos.
Esto incluye p�ginas de discusi�n, p�ginas acerca de Wikipedia, p�ginas m�nimas,
redirecciones, y otras que probablemente no puedan calificarse como art�culos.
Excluyendolas, hay <b>$2</b> p�ginas que probablemente son art�culos leg�timos.<p>
Ha habido un total de <b>$3</b> visitas a p�ginas, y <b>$4</b> ediciones de p�gina
desde que el software fue actualizado (Octubre 2002). 
Esto resulta en un promedio de <b>$5</b> ediciones por p�gina, 
y <b>$6</b> visitas por edici�n.",
"userstatstext" => "Hay <b>$1</b> usuarios registrados.
de los cuales <b>$2</b> son administradores (ver $3).",

# Maintenance Page
#
"maintenance"		=> "P�gina de mantenimiento",
"maintnancepagetext"	=> "Esta p�gina incluye varias herramientas �tiles para el mantenimiento diario de la enciclopedia. Algunas de estas funciones tienden a sobrecargar la base de datos, asi que, por favor, no vuelvas a cargar la p�gina despues de cada item que arregles ;-)",
"maintenancebacklink"	=> "Volver a la P�gina de Mantenimiento",
"disambiguations"	=> "P�ginas de desambiguaci�n",
"disambiguationspage"	=> "Wikipedia:Enlaces a p�ginas de desambiguaci�n",
"disambiguationstext"	=> "Los siguientes art�culos enlazan a una <i>p�gina de desambiguaci�n</i>. Deber�an enlazar al art�culo apropiado.<br>Una p�gina es considerada de desambiguaci�n si est� enlazada desde $1.<br>Enlaces desde otros espacios de nombre (Como Wikipedia: o usuario:) <b>no</b> son  listados aqu�.",
"doubleredirects"	=> "Redirecciones Dobles",
"doubleredirectstext"	=> "<b>Atenci�n:</b> Esta lista puede contener falsos positivos. Eso significa usualmente que hay texto adicional con enlaces bajo el primer #REDIRECT.<br>\nCada fila contiene enlaces al segundo y tercer redirect, as� como la primera l�nea del segundo redirect, en la que usualmente se encontrar� el art�culo \"real\" al que el primer redirect deber�a apuntar.",
"brokenredirects"	=> "Redirecciones incorrectas",
"brokenredirectstext"	=> "Las redirecciones siguientes enlazan a un art�culo que no existe.",
"selflinks"		=> "P�ginas con autoenlaces",
"selflinkstext"		=> "Las siguientes p�ginas contienen un enlace a s� mismas, lo que no se recomienda.",
"mispeelings"       => "P�ginas con faltas de ortograf�a",
"mispeelingstext"               => "Las siguientes p�ginas contienen una falta de ortograf�a com�n de las listadas en $1. La escritura correcta se indica entre par�ntesis.",
"mispeelingspage"       => "Lista de faltas de ortograf�a comunes",           
"missinglanguagelinks"  => "Enlaces Interleguaje Faltantes",
"missinglanguagelinksbutton"    => "Encontrar los enlaces interlenguaje que faltan para",
"missinglanguagelinkstext"      => "Estos art�culos <b>no</b> enlazan a sus correspondientes en $1. <b>No</b> se muestran redirecciones ni subp�ginas.",


# Miscellaneous special pages
#
"orphans"		=> "P�ginas hu�rfanas",
"lonelypages"	=> "P�ginas hu�rfanas",
"unusedimages"	=> "Im�genes sin uso",
"popularpages"	=> "P�ginas populares",
"nviews"		=> "$1 visitas",
"wantedpages"	=> "P�ginas requeridas",
"nlinks"		=> "$1 enlaces",
"allpages"		=> "Todas las p�ginas",
"randompage"	=> "P�gina aleatoria",
"shortpages"	=> "P�ginas cortas",
"longpages"		=> "P�ginas largas",
"listusers"		=> "Lista de usuarios",
"specialpages"	=> "P�ginas especiales",
"spheading"		=> "P�ginas especiales",
"sysopspheading" => "P�ginas especiales para uso de sysops",
"developerspheading" => "P�ginas especiales para uso de developers",
"protectpage"	=> "P�ginas protegidas",
"recentchangeslinked" => "Seguimiento de enlaces",
"rclsub"		=> "(a p�ginas enlazadas desde \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "P�ginas nuevas",
"intl"                  => "Enlaces interlenguaje",
"movethispage"	=> "Trasladar esta p�gina",
"unusedimagestext" => "<p>Por favor note que otros sitios web
tales como otras wikipedias pueden enlazar a una imagen
con un URL directo, y de esa manera todav�a estar listada aqu�
a pesar de estar en uso activo.",
"booksources"   => "Fuentes de libros",
"booksourcetext" => "A continuacion hay una lista de enlaces a otros sitios que venden libros nuevos y usados, y tambi�n pueden contener informaci�n adicional acerca de los libros que est�s buscando.
Wikipedia no est� relacionada con ninguno de estos negocios, y esta lista no debe ser considerada un patrocinio de los mismos.",
"alphaindexline" => "$1 a $2",

# Email this user
#
"mailnologin"	=> "No enviar direcci�n",
"mailnologintext" => "Debes <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">inciar sesi�n</a>
y tener una direccion electr�nica v�lida en tus <a href=\"" .
  wfLocalUrl( "Especial:Preferences" ) . "\">preferencias</a>
para enviar un correo electr�nico a otros usuarios.",
"emailuser"		=> "Env�a correo electr�nico a este usuario",
"emailpage"		=> "Correo electr�nico a usuario",
"emailpagetext"	=> "Si este usuario ha registrado una direcci�n electr�nica v�lida en sus preferencias de usuario, el siguiente formulario sirve para enviarle un mensaje.
La direcci�n electr�nica que indicaste en tus preferencias de usuario aparecer� en el remitente para que el destinatario te pueda responder.",
"noemailtitle"	=> "No hay direcci�n electr�nica",
"noemailtext"	=> "Este usuario no ha especificado una direcci�n electr�nica v�lida, o ha elegido no recibir correo electr�nico de otros usuarios.",
"emailfrom"		=> "De",
"emailto"		=> "Para",
"emailsubject"	=> "Tema",
"emailmessage"	=> "Mensaje",
"emailsend"		=> "Enviar",
"emailsent"		=> "Correo electr�nico enviado",
"emailsenttext" => "Tu correo electr�nico ha sido enviado.",

# Watchlist
#
"watchlist"		=> "Lista de seguimiento",
"watchlistsub"	=> "(para el usuario \"$1\")",
"nowatchlist"	=> "No tienes ninguna p�gina en tu lista de seguimiento.",
"watchnologin"	=> "No has iniciado sesi�n",
"watchnologintext"	=> "Debes <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">iniciar sesi�n</a>
para modificar tu lista de seguimiento.",
"addedwatch"	=> "A�adido a la lista de seguimiento",
"addedwatchtext" => "La p�gina \"$1\" ha sido a�adida a tu  <a href=\"" .
  wfLocalUrl( "Especial:Watchlist" ) . "\">lista se seguimiento</a>.
Cambios futuros en esta p�gina y su p�gina de discusi�n asociada se indicar�n ah�, y la p�gina aparecer� <b>en negritas</b> en la <a href=\"" .
  wfLocalUrl( "Especial:Recentchanges" ) . "\">lista de cambios recientes</a> para hacerla m�s facil de detectar.</p>

<p>Cuando quieras eliminar la p�gina de tu lista de seguimiento, presiona \"Dejar de vigilar\" en el men�.",
"removedwatch"	=> "Eliminada de la lista de seguimiento",
"removedwatchtext" => "La p�gina \"$1\" ha sido eliminada de tu lista de seguimiento.",
"watchthispage"	=> "Vigilar esta p�gina",
"unwatchthispage" => "Dejar de vigilar",
"notanarticle"	=> "No es un art�culo",

# Delete/protect/revert
#
"deletepage"	=> "Borrar esta p�gina",
"confirm"		=> "Confirma",
"confirmdelete" => "Confirma el borrado",
"deletesub"		=> "(Borrando \"$1\")",
"confirmdeletetext" => "Est�s a punto de borrar una p�gina o imagen 
en forma permanente,
as� como toda su historia, de la base de datos.
Por favor, confirma que realmente quieres hacer eso, que entiendes las
consecuencias, y que lo est�s haciendo de acuerdo con [[Wikipedia:Pol�ticas]].",
"confirmcheck"	=> "S�, realmente quiero borrar esto.",
"actioncomplete" => "Acci�n completa",
"deletedtext"	=> "\"$1\" ha sido borrado.
Ve $2 para un registro de los borrados m�s recientes.",
"deletedarticle" => "borrado \"$1\"",
"dellogpage"	=> "Registro_de_borrados",
"dellogpagetext" => "Abajo hay una lista de los borrados m�s recientes.
Todos los tiempos se muestran en hora del servidor (UTC).
<ul>
</ul>
",
"deletionlog"	=> "registro de borrados",
"reverted"		=> "Revertido a una revisi�n anterior",
"deletecomment"	=> "Razon para el borrado",
"imagereverted" => "Revertido a una versi�n anterior fue exitoso.",
"rollback"		=> "Revertir ediciones",
"rollbacklink"	=> "Revertiy",
"cantrollback"	=> "No se pueden revertir las ediciones; el �ltimo colaborador es el �nico autor de este art�culo.",
"revertpage"	=> "Revertida a la �ltima edici�n por $1",

# Undelete
"undelete" => "Restaura una p�gina borrada",
"undeletepage" => "Ve y restaura p�ginas borradas",
"undeletepagetext" => "Las siguientes p�ginas han sido borradas pero a�n est�n en el archivo y pueden ser restauradas. El archivo puede ser limpiado peri�dicamente.",
"undeletearticle" => "Restaurar art�culo borrado",
"undeleterevisions" => "$1 revisiones archivadas",
"undeletehistory" => "Si t� restauras una p�gina, todas las revisiones ser�n restauradas a la historia.
Si una nueva p�gina con el mismo nombre ha sido creada desde el borrado, las versiones restauradas aparecer�n como historia anterior, y la revisi�n actual de la p�gina \"viva\" no ser� autom�ticamente reemplazada.",
"undeleterevision" => "Revisi�n borrada al $1",
"undeletebtn" => "Restaurar!",
"undeletedarticle" => "restaurado \"$1\"",
"undeletedtext"   => "El art�culo [[$1]] ha sido restaurado exitosamente.
Ve [[Wikipedia:Registro_de_borrados]] para una lista de borrados y restauraciones recientes.",

# Contributions
#
"contributions"	=> "Contribuciones del usuario",
"mycontris"=>"Mis contribuciones",
"contribsub"	=> "$1",
"nocontribs"	=> "No se encontraron cambios que cumplieran estos criterios.",
"ucnote"		=> "Abajo est�n los �ltimos <b>$1</b> cambios de este usuario en los �ltimos <b>$2</b> d�as.",
"uclinks"		=> "Ver los �ltimos $1 cambios; ver los �ltimos $2 d�as.",
"uctop"		=> " (�ltima modificaci&oacute;n)" ,

# What links here
#
"whatlinkshere"	=> "Lo que enlaza aqu�",
"notargettitle" => "No hay p�gina blanco",
"notargettext"	=> "No has especificado en qu� p�gina
llevar a cabo esta funci�n.",
"linklistsub"	=> "(Lista de enlaces)",
"linkshere"		=> "Las siguientes p�ginas enlazan aqu�:",
"nolinkshere"	=> "Ninguna p�gina enlaza aqu�.",
"isredirect"	=> "pagina redirigida",

# Block/unblock IP
#
"blockip"		=> "Bloqueo de direcciones IP",
"blockiptext"	=> "Usa el formulario siguiente para bloquear el
acceso de escritura desde una direcci�n IP espec�fica.
Esto deber�a hacerse s�lo para prevenir vandalismo, y de
acuerdo a las [[Wikipedia:Pol�ticas| pol�ticas de Wikipedia]].
Explica la raz�n espec�fica del bloqueo (por ejemplo, citando
ls p�ginas en particular que han sido objeto de vandalismo desde la direcci�n IP a bloquear).",
"ipaddress"		=> "Direcci�n IP",
"ipbreason"		=> "Raz�n",
"ipbsubmit"		=> "Bloquear esta direcci�n",
"badipaddress"	=> "La direcci�n IP no tiene el formato correcto.",
"noblockreason" => "Debes dar una raz�n para el bloqueo.",
"blockipsuccesssub" => "Bloqueo exitoso",
"blockipsuccesstext" => "La direccion IP  \"$1\" ha sido bloqueada.
<br>Ver [[Especial:Ipblocklist|lista de IP bloqueadas]] para revisar bloqueos.",
"unblockip"		=> "Desbloquear direcci�n IP",
"unblockiptext"	=> "Usa el formulario que sigue para restaurar el 
acceso de escritura a una direcci�n IP previamente bloqueada.",
"ipusubmit"		=> "Desbloquea esta direcci�n",
"ipusuccess"	=> "Direcci�n IP \"$1\" desbloqueada",
"ipblocklist"	=> "Lista de direcciones IP bloqueadas",
"blocklistline"	=> "$1, $2 bloquea $3",
"blocklink"		=> "bloquear",
"unblocklink"	=> "desbloquear",
"contribslink"	=> "contribuciones",

# Developer tools
#
"lockdb"		=> "Bloquear la base de datos",
"unlockdb"		=> "Desbloquear la base de datos",
"lockdbtext"	=> "El bloqueo de la base de datos impedir� a todos los usuarios editar p�ginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualquier otra funci�n que requiera realizar cambios en la base de datos. Por favor, confirma que �sto es precisamente lo que quieres hacer y que desbloquear�s la base de datos tan pronto hayas finalizado las operaciones de mantenimiento.",
"unlockdbtext"	=> "El desbloqueo de la base de datos permitir� a todos los usuarios editar p�ginas, cambiar sus preferencias, modificar sus listas de seguimiento y cualesquiera otras funciones que impliquen modificar la base de datos. Por favor, confirma que �sto es precisamente lo que quieres hacer.",
"lockconfirm"	=> "S�, realmente quiero bloquear la base de datos.",
"unlockconfirm"	=> "S�, realmente quiero desbloquear la base de datos.",
"lockbtn"		=> "Bloquear la base de datos",
"unlockbtn"		=> "Desbloquear la base de datos",
"locknoconfirm" => "No has confirmado lo que deseas hacer.",
"lockdbsuccesssub" => "El bloqueo se ha realizado con �xito",
"unlockdbsuccesssub" => "El desbloqueo se ha realizado con �xito",
"lockdbsuccesstext" => "La base de datos de Wikipedia ha sido bloqueada.
<br>Recuerda retirar el bloqueo despu�s de completar las tareas de mantenimiento.",
"unlockdbsuccesstext" => "La base de datos de Wikipedia ha sido desbloqueada.",

# SQL query
#
"asksql"		=> "Consulta SQL",
"asksqltext"	=> "Usa el formulario que sigue para hacer una consulta directa
a la base de datos de Wikipedia. Usa comillas simples ('como estas') para delimitar
cadenas de caracteres literales.
Esto puede a�adir una carga considerable al servidor, as� que
por favor usa esta funci�n lo menos possible.",
"sqlquery"		=> "Entra la consulta",
"querybtn"		=> "Env�a la consulta",
"selectonly"	=> "Consultas diferentes a \"SELECT\" est�n restringidas s�lo
a Wikipedia developers.",
"querysuccessful" => "Consulta exitosa",

# Move page
#
"movepage"		=> "Renombrar p�gina",
"movepagetext"	=> "Usando el formulario que sigue renombrar� una p�gina,
moviendo toda su historia al nombre nuevo.
El t�tulo anterior se convertir� en un redireccionamiento al nuevo t�tulo.
Enlaces al antiguo t�tulo de la p�gina no se cambiar�n. Aseg�rate de [[Especial:Maintenance|verificar]] no dejar redirecciones dobles o rotas.
T� eres responsable de hacer que los enlaces sigan apuntando adonde se supone que lo deber�an hacer. 

Recuerda que la p�gina '''no''' ser� trasladada si ya existe una p�gina con el nuevo t�tulo, a no ser que sea una p�gina vac�a o un ''redirect'' sin historia. 
Esto significa que podr�s renombrar una p�gina a su t�tulo original si cometes un error de escritura en el nuevo t�tulo, pero que no podr�s sobreescribir una p�gina existente.

<b>ADVERTENCIA!</b>
Este puede ser un cambio dr�stico e inesperado para una p�gina popular;
por favor, asegurate de entender las consecuencias que acarrear�
antes de seguir adelante.",
"movepagetalktext" => "La p�gina de discusi�n asociada, si existe, ser� trasladada autom�ticamente '''a menos que:'''
*Est�s moviendo la p�gina entre espacios de nombre diferentes,
*Una p�gina de discusi�n no vac�a ya existe con el nombre nuevo, o
*Desactivaste la opci�n \"Renombrar la p�gina de discusi�n tambi�n\".

En estos casos, deber�s trasladar manualmente el contenido de la p�gina de discusi�n.",
"movearticle"	=> "Renombrar p�gina",
"movenologin"	=> "No has iniciado sesion",
"movenologintext" => "Es necesario ser usuario registrado y <a href=\"" .
  wfLocalUrl( "Especial:Userlogin" ) . "\">haber iniciado sesi�n</a>
para renombrar una p�gina.",
"newtitle"		=> "A t�tulo nuevo",
"movepagebtn"	=> "Renombrar p�gina",
"pagemovedsub"	=> "Renombramiento exitoso",
"pagemovedtext" => "P�gina \"[[$1]]\" renombrada a \"[[$2]]\".",
"articleexists" => "Ya existe una p�gina con ese nombre, o el nombre que has
escogido no es v�lido.
Por favor, elije otro nombre.",
"talkexists"	=> "La p�gina fue renombrada exitosamente, pero la p�gina de discusi�n no se pudo mover porque ya existe una en el t�tulo nuevo. Por favor incorpora su contenido manualmente.",
"movedto"		=> "renombrado a",
"movetalk"	=> "Renombrar la p�gina de discusi�n tambi�n, si es aplicable.",
"talkpagemoved" =>  "La p�gina de discusi�n correspondiente tambi�n fue renombrada.",
"talkpagenotmoved" => "La p�gina de discusi�n correspondiente <strong>no</strong> fue renombrada.",

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
