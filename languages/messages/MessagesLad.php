<?php
/** Ladino (Ladino)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author ILVI
 * @author Remember the dot
 * @author Runningfridgesrule
 * @author Taichi
 * @author Universal Life
 * @author לערי ריינהארט
 */

$fallback = 'es';

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Diskusyón',
	NS_USER             => 'Usador',
	NS_USER_TALK        => 'Messaje_de_Usador',
	NS_PROJECT_TALK     => 'Diskusyón_de_$1',
	NS_FILE             => 'Dosya',
	NS_FILE_TALK        => 'Diskusyón_de_Dosya',
	NS_MEDIAWIKI        => 'MedyaViki',
	NS_MEDIAWIKI_TALK   => 'Diskusyón_de_MedyaViki',
	NS_TEMPLATE         => 'Xabblón',
	NS_TEMPLATE_TALK    => 'Diskusyón_de_Xabblón',
	NS_HELP             => 'Ayudo',
	NS_HELP_TALK        => 'Diskusyón_de_Ayudo',
	NS_CATEGORY         => 'Katēggoría',
	NS_CATEGORY_TALK    => 'Diskusyón_de_Katēggoría',
);

$namespaceAliases = array(
	// Backward compat. Fallbacks from 'es'.
	'Especial'            => NS_SPECIAL,
	'Discusión'           => NS_TALK,
	'Usuario'             => NS_USER,
	'Usuario_Discusión'   => NS_USER_TALK,
	'$1_Discusión'        => NS_PROJECT_TALK,
	'Archivo'             => NS_FILE,
	'Archivo_Discusión'   => NS_FILE_TALK,
	'MediaWiki_Discusión' => NS_MEDIAWIKI_TALK,
	'Plantilla'           => NS_TEMPLATE,
	'Plantilla_Discusión' => NS_TEMPLATE_TALK,
	'Ayuda'               => NS_HELP,
	'Ayuda_Discusión'     => NS_HELP_TALK,
	'Categoría'           => NS_CATEGORY,
	'Categoría_Discusión' => NS_CATEGORY_TALK,

	'Meddia'                   => NS_MEDIA,
	'Diskussión'               => NS_TALK,
	'Empleador'                => NS_USER,
	'Message_de_Empleador'     => NS_USER_TALK,
	'Diskussión_de_$1'         => NS_PROJECT_TALK,
	'Dossia'                   => NS_FILE,
	'Diskussión_de_Dossia'     => NS_FILE_TALK,
	'Diskussión_de_Xabblón'    => NS_MEDIAWIKI_TALK,
	'Plantilla_Discusión'      => NS_TEMPLATE_TALK,
	'Diskussión_de_Ayudo'      => NS_HELP_TALK,
	'Kateggoría'               => NS_CATEGORY,
	'Diskussión_de_Kateggoría' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'DireksyonesDobles' ),
	'BrokenRedirects'           => array( 'DireksyonesBozeadas' ),
	'Disambiguations'           => array( 'Apartamiento_de_senso' ),
	'Userlogin'                 => array( 'Entrada_del_usador' ),
	'Userlogout'                => array( 'Salida_del_usador' ),
	'CreateAccount'             => array( 'CriarCuento' ),
	'Preferences'               => array( 'Preferencias' ),
	'Watchlist'                 => array( 'Lista_de_escogidos' ),
	'Recentchanges'             => array( 'TrocamientosFreskos' ),
	'Upload'                    => array( 'CargarDosya' ),
	'Listfiles'                 => array( 'ListaDosyas' ),
	'Newimages'                 => array( 'NuevasDosyas' ),
	'Listusers'                 => array( 'ListaUsadores' ),
	'Listgrouprights'           => array( 'DerechosGruposUsadores' ),
	'Statistics'                => array( 'Estatistika' ),
	'Randompage'                => array( 'KualunkeHoja' ),
	'Lonelypages'               => array( 'HojasHuérfanas' ),
	'Uncategorizedpages'        => array( 'HojasNoKateggorizadas' ),
	'Uncategorizedcategories'   => array( 'KateggoríasNoKateggorizadas' ),
	'Uncategorizedimages'       => array( 'DosyasNoKateggorizadas' ),
	'Uncategorizedtemplates'    => array( 'XabblonesNoKateggorizados' ),
	'Unusedcategories'          => array( 'KateggoríasSinUso' ),
	'Unusedimages'              => array( 'DosyasSinUso' ),
	'Wantedpages'               => array( 'HojasDemandadas' ),
	'Wantedcategories'          => array( 'KateggoríasDemandadas' ),
	'Wantedfiles'               => array( 'DosyasDemandadas' ),
	'Wantedtemplates'           => array( 'XabblonesDemandados' ),
	'Mostlinked'                => array( 'HojasLoMásMunchoLinkeadas' ),
	'Mostlinkedcategories'      => array( 'KateggoríasMásUsadas' ),
	'Mostlinkedtemplates'       => array( 'XabblonesMásUsados' ),
	'Mostimages'                => array( 'DosyasLoMásMunchoLinkeadas' ),
	'Mostcategories'            => array( 'MásKateggorizadas' ),
	'Mostrevisions'             => array( 'MásEddisyones' ),
	'Fewestrevisions'           => array( 'MancoEddisyones' ),
	'Shortpages'                => array( 'HojasCurtas' ),
	'Longpages'                 => array( 'HojasLargas' ),
	'Newpages'                  => array( 'HojasNuevas' ),
	'Ancientpages'              => array( 'HojasViejas' ),
	'Deadendpages'              => array( 'HojasSinLinkes' ),
	'Protectedpages'            => array( 'HojasGuardadas' ),
	'Protectedtitles'           => array( 'TítůlosGuardados' ),
	'Allpages'                  => array( 'TodasLasHojas' ),
	'Prefixindex'               => array( 'Fijhrist_de_prefiksos' ),
	'Ipblocklist'               => array( 'UsadoresBloqueados' ),
	'Unblock'                   => array( 'Desbloquea' ),
	'Specialpages'              => array( 'HojasEspeciales' ),
	'Contributions'             => array( 'Ajustamientos' ),
	'Emailuser'                 => array( 'MandarEmailUsador' ),
	'Confirmemail'              => array( 'AverdadearEmail' ),
	'Whatlinkshere'             => array( 'LoQueLinkeaAquí' ),
	'Recentchangeslinked'       => array( 'TrocamientosEnterassados' ),
	'Movepage'                  => array( 'TashirearHoja' ),
	'Blockme'                   => array( 'Bloquearme' ),
	'Booksources'               => array( 'FuentesDeLibros' ),
	'Categories'                => array( 'Kateggorías' ),
	'Export'                    => array( 'AktarearAfuera' ),
	'Version'                   => array( 'Versión' ),
	'Allmessages'               => array( 'TodosLosMessajes' ),
	'Log'                       => array( 'Rējistro' ),
	'Blockip'                   => array( 'Bloquear' ),
	'Undelete'                  => array( 'TraerAtrás' ),
	'Import'                    => array( 'AktarearAriento' ),
	'Lockdb'                    => array( 'BloquearBasa_de_dados' ),
	'Unlockdb'                  => array( 'DesbloquearBasa_de_dados' ),
	'Userrights'                => array( 'DerechosUsadores' ),
	'MIMEsearch'                => array( 'BuscarPorMIME' ),
	'FileDuplicateSearch'       => array( 'BuscarDosyasDobles' ),
	'Unwatchedpages'            => array( 'HojasSinCudiadas' ),
	'Listredirects'             => array( 'TodasLasDireksyones' ),
	'Revisiondelete'            => array( 'EfassarRēvizyón' ),
	'Unusedtemplates'           => array( 'XabblonesSinUso' ),
	'Randomredirect'            => array( 'KualunkeDireksyón' ),
	'Mypage'                    => array( 'MiHoja' ),
	'Mytalk'                    => array( 'MiDiskusyón' ),
	'Mycontributions'           => array( 'MisAjustamientos' ),
	'Listadmins'                => array( 'ListaDeAdministradores' ),
	'Listbots'                  => array( 'ListaDeBots' ),
	'Popularpages'              => array( 'HojasMásVisitadas' ),
	'Search'                    => array( 'Buscar' ),
	'Resetpass'                 => array( 'TrocarKóddiche' ),
	'Withoutinterwiki'          => array( 'SinIntervikis' ),
	'MergeHistory'              => array( 'AjuntarIstoria' ),
	'Filepath'                  => array( 'Pozisyón_de_dosya' ),
	'Invalidateemail'           => array( 'DesverdadearEmail' ),
	'Blankpage'                 => array( 'VaziarHoja' ),
	'LinkSearch'                => array( 'Busqueda_de_linkes' ),
	'DeletedContributions'      => array( 'AjustamientosEfassados' ),
	'Tags'                      => array( 'Etiquetas' ),
	'Activeusers'               => array( 'UsadoresActivos' ),
	'RevisionMove'              => array( 'TashireaRēvizyón' ),
	'ComparePages'              => array( 'ApariguarHojas' ),
	'Selenium'                  => array( 'Selenyum' ),
	'Badtitle'                  => array( 'TítůloNegro' ),
);

$messages = array(
# User preference toggles
'tog-underline'             => 'Subrayar linkes',
'tog-highlightbroken'       => 'Mostrar los artículos vazíos <a href="" class="new">ansina</a> (alternativa: ansina<a href="" class="internal">?</a>).',
'tog-justify'               => 'Atacanar paragrafos',
'tog-hideminor'             => 'Esconde ediciones chiquiticas de los «trocamientos frescos»',
'tog-hidepatrolled'         => 'Esconde los trocamientos "de guardia" en la hoja de los trocamientos frescos',
'tog-newpageshidepatrolled' => 'Esconde las hojas cudiadas de la lista de hojas nuevas.',
'tog-showtoolbar'           => 'Amostrár la barra de edision',
'tog-editondblclick'        => 'Troca las hojas en klikando dos vezes. (JavaScript es menesteroso)',
'tog-rememberpassword'      => 'Akodrár mis informasiones sobre ésta komputadóra',
'tog-watchcreations'        => 'Ajusta las hojas que estó creando, a mi lista de escogidas',
'tog-watchdefault'          => 'Ajusta las hojas que troco, a mi lista de escogidas',
'tog-watchmoves'            => 'Ajusta las hojas que renombro, a mi lista de escogidas',
'tog-watchdeletion'         => 'Ajusta las hojas que efasso, a mi lista de escogidas',
'tog-previewontop'          => 'Mostra la prevista arriba de la caxa del trocamiento.',
'tog-previewonfirst'        => 'Mostra la prevista al primer trocamiento.',
'tog-enotifwatchlistpages'  => 'Émbiame una letra electrónica cuando y ay un trocamiento en una hoja que estó cudiando',
'tog-enotifusertalkpages'   => 'Embiame una pósta kuando troka mi pajina de diskusion de uzuario',
'tog-enotifminoredits'      => 'Mándame también una letra electrónica por los trocamientos chiquiticos de las hojas.',
'tog-shownumberswatching'   => 'Amostrár el número de uzuarios ke la vijilan',
'tog-ccmeonemails'          => 'Las copias de las letras electrónicas que mandí a otros usuarios, ¡Mándamelas!',
'tog-diffonly'              => 'No mostres el contenido de las hojas debaxo de las diferencias.',
'tog-showhiddencats'        => 'Amostrár kategorías eskondidas',

'underline-always' => 'Siempre',
'underline-never'  => 'Nunka',

# Dates
'sunday'        => 'Aljhad',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miércoles',
'thursday'      => 'Jugüeves',
'friday'        => 'Viernes',
'saturday'      => 'Xabbat',
'sun'           => 'Alj',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mie',
'thu'           => 'Jug',
'fri'           => 'Vie',
'sat'           => 'Xab',
'january'       => 'Enero',
'february'      => 'Febrero',
'march'         => 'Março',
'april'         => 'Abril',
'may_long'      => 'Mayo',
'june'          => 'Junio',
'july'          => 'Julio',
'august'        => 'Agosto',
'september'     => 'Setiembre',
'october'       => 'Octubre',
'november'      => 'Noviembre',
'december'      => 'Deziembre',
'january-gen'   => 'de Januario',
'february-gen'  => 'de Februario',
'march-gen'     => 'de Março',
'april-gen'     => 'de Avril',
'may-gen'       => 'de Mayo',
'june-gen'      => 'de Junio',
'july-gen'      => 'de Julyo',
'august-gen'    => 'de Agosto',
'september-gen' => 'de Septiembre',
'october-gen'   => 'de Octubre',
'november-gen'  => 'de Noviembre',
'december-gen'  => 'de Diziembre',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'May',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Oct',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Categoría|Categorías}}',
'category_header'          => 'Artícůlos en la kateggoría "$1"',
'subcategories'            => 'Subcategorías',
'category-media-header'    => 'Archivos multimedia en la kategoría "$1"',
'category-empty'           => "''La kategoría no kontiene aktualmente ningún artikolo o archivo multimedia''",
'hidden-categories'        => '{{PLURAL:$1|Categoría escondida|Categorías escondidas}}',
'hidden-category-category' => 'Categorías escondidas',
'category-subcat-count'    => '{{PLURAL:$2|Esta categoría contiene solamente la categoría venidera.|Esta categoría contiene {{PLURAL:$1|las categorías venideras|$1 subcategorías venideras}}, de un total de $2 subcategorías.}}',
'category-article-count'   => '{{PLURAL:$2|Esta categoría contiene solamente la hoja venidera.|{{PLURAL:$1|La hoja venidera apartiene|Las $1 hojas venideras apartienen}} a esta categoría, de un total de $2.}}',
'listingcontinuesabbrev'   => 'cont.',

'about'         => 'Encima de',
'article'       => 'Artícůlo',
'newwindow'     => '(Se avre en una ventana nueva)',
'cancel'        => 'Suprimir',
'moredotdotdot' => 'Mas...',
'mypage'        => 'Mi hoja',
'mytalk'        => 'Mi diskusyón',
'anontalk'      => 'Diskusion para esta IP',
'navigation'    => 'Passeo',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Topa',
'qbbrowse'       => 'Navêga',
'qbedit'         => 'Troca',
'qbpageoptions'  => 'Opciones de la hoja',
'qbmyoptions'    => 'Mis opsiones',
'qbspecialpages' => 'Pajinas espesiales',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-delete'       => 'Efassa',
'vector-action-move'         => 'Taxirea',
'vector-action-protect'      => 'Guarda',
'vector-namespace-category'  => 'Kateggoría',
'vector-namespace-help'      => 'Hoja de ayudo',
'vector-namespace-image'     => 'Dosya',
'vector-namespace-main'      => 'Hoja',
'vector-namespace-mediawiki' => 'Noticia',
'vector-namespace-project'   => 'Hoja de proyecto',
'vector-namespace-special'   => 'Hoja especial',
'vector-namespace-talk'      => 'Diskussión',
'vector-namespace-template'  => 'Xabblón',
'vector-namespace-user'      => 'Hoja de empleador',
'vector-view-create'         => 'Cria',
'vector-view-edit'           => 'Troca',
'vector-view-history'        => 'Ve la istoria',
'vector-view-view'           => 'Melda',
'vector-view-viewsource'     => 'Ve su orijjín',
'actions'                    => 'Acciones',
'namespaces'                 => 'Espacios de nombres',
'variants'                   => 'Variantes',

'errorpagetitle'    => 'Yerro',
'returnto'          => 'Tornar a $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ayudo',
'search'            => 'Busca',
'searchbutton'      => 'Busca',
'go'                => 'Vate',
'searcharticle'     => 'Vate',
'history'           => 'La istoria de la hoja',
'history_short'     => 'Istoria',
'info_short'        => 'Enformación',
'printableversion'  => 'Vista apropiada para imprimir',
'permalink'         => 'Link mantenido',
'print'             => 'Imprimír',
'edit'              => 'Troca',
'create'            => 'Cria',
'editthispage'      => 'Troca esta hoja',
'create-this-page'  => 'Cria esta hoja',
'delete'            => 'Efassa',
'deletethispage'    => 'Efassa esta hoja',
'undelete_short'    => 'Abolta $1 trocamientos',
'protect'           => 'Abriga',
'protect_change'    => 'Troca el abrigamiento',
'protectthispage'   => 'Abriga esta hoja',
'unprotect'         => 'Desabriga',
'unprotectthispage' => 'Desabriga esta hoja',
'newpage'           => 'Hoja nueva',
'talkpage'          => 'Diskute la hoja',
'talkpagelinktext'  => 'Messaje',
'specialpage'       => 'Hoja Especial',
'personaltools'     => 'Aparatos personales',
'postcomment'       => 'Ajusta un comentario',
'articlepage'       => 'Ve el artícůlo',
'talk'              => 'Diskusyón',
'views'             => 'Vista',
'toolbox'           => 'Caxa de Aparatos',
'userpage'          => 'Ve la hoja del empleador',
'projectpage'       => 'Mira la hoja del prodjekto',
'imagepage'         => 'Mira la hoja de la dosya',
'mediawikipage'     => 'Mostra la hoja de message',
'templatepage'      => 'Ver la hoja del xabblón',
'viewhelppage'      => 'Ver la hoja de ayudo',
'categorypage'      => 'Mostra la hoja de kateggoría',
'viewtalkpage'      => 'Ver diskusion',
'otherlanguages'    => 'En otras linguas',
'redirectedfrom'    => '(Redirigido desde $1)',
'redirectpagesub'   => 'Hoja redirigida',
'lastmodifiedat'    => 'Esta hoja fue trocada por la última vez en $2, a las $1.',
'protectedpage'     => 'Hoja guardada',
'jumpto'            => 'Salta a:',
'jumptonavigation'  => 'passeo',
'jumptosearch'      => 'búsqueda',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Encima de la {{SITENAME}}',
'aboutpage'            => 'Project:Encima de',
'copyright'            => 'El contenido se puede topar debaxo de la <i>$1</i>',
'copyrightpage'        => '{{ns:project}}:Derechos de autor',
'currentevents'        => 'Aktualidad',
'currentevents-url'    => 'Project:Aktualidad',
'disclaimers'          => 'Rēfusamiento de responsabbilitá',
'disclaimerpage'       => 'Project:Rēfusamiento de responsabbilitá general',
'edithelp'             => '¿Cómodo se la troca?',
'edithelppage'         => 'Help:Una hoja, ¿cómodo se la troca?',
'helppage'             => 'Help:Contenidos',
'mainpage'             => 'La Primera Hoja',
'mainpage-description' => 'La Hoja Primera',
'policy-url'           => 'Project:Politikas',
'portal'               => 'Portal de la comunidad',
'portal-url'           => 'Project:Portal de la comunidad',
'privacy'              => 'Prencipio de particůlaridad',
'privacypage'          => 'Project:Prencipio de particůlaridad',

'badaccess' => 'Yerro de permissión',

'ok'                      => 'DE ACORDDO',
'retrievedfrom'           => 'Tomado del adresso "$1"',
'youhavenewmessages'      => 'Tienes $1 ($2).',
'newmessageslink'         => 'mesajes nuevos',
'newmessagesdifflink'     => 'el trocamiento de alcabo',
'youhavenewmessagesmulti' => 'Tienes messajes nuevos en $1',
'editsection'             => 'troca',
'editold'                 => 'trocar',
'editlink'                => 'trocar',
'viewsourcelink'          => 'Ve el origín',
'editsectionhint'         => 'Troca la parte: $1',
'toc'                     => 'Contenidos',
'showtoc'                 => 'Amostrar',
'hidetoc'                 => 'esconder',
'thisisdeleted'           => 'Ver o restorar $1?',
'viewdeleted'             => 'Desea ver $1?',
'site-rss-feed'           => 'Fuente de RSS de $1',
'site-atom-feed'          => 'Fuente de Atom de $1',
'page-rss-feed'           => '"$1" Fuente RSS',
'page-atom-feed'          => '"$1" Subscripción Atom',
'red-link-title'          => '$1 (esta hoja no egziste)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Hoja',
'nstab-user'      => 'Hoja de empleador',
'nstab-media'     => 'Hoja de Meddia',
'nstab-special'   => 'Hoja Especial',
'nstab-project'   => 'Hoja del proyecto',
'nstab-image'     => 'Arxivo',
'nstab-mediawiki' => 'Messaj',
'nstab-template'  => 'Xabblón',
'nstab-help'      => 'Ayudo',
'nstab-category'  => 'Kateggoría',

# Main script and global functions
'nosuchspecialpage' => 'No ay tala hoja especial',

# General errors
'error'               => 'Yerro',
'databaseerror'       => 'Yerro de la Databasa',
'missing-article'     => 'La databasa no topó el teksto de una hoja que debería topar, llamada "$1" $2.

Esto es generalmente cabsado por un "diff" anacrónico o un link a la istoria de una hoja que era efassado.

Si esto no es el cabso, puede ser que topates un escarabajo en el software.
Si puede ser, enfórmaselo a un [[Special:ListUsers/sysop|administrator]], anotando la URL.',
'missingarticle-rev'  => '(nº. de revisión: $1)',
'missingarticle-diff' => '(Dif.: $1, $2)',
'filecopyerror'       => 'No se pudo copiar el arxiv "$1" a "$2".',
'badtitletext'        => 'El título de la hoja demandada está vazío, no es valible, o es un link interlingua o interwiki incorrecto.
Puede ser que contiene uno o más caracteres que no se pueden usar en los títulos.',
'viewsource'          => 'Ver el codd fuente',
'viewsourcefor'       => 'para $1',

# Login and logout pages
'yourname'                => 'Su nombre de usuario',
'yourpassword'            => 'Parola',
'remembermypassword'      => 'Acórdate de mi entre sessiones (por un maksimum de {{PLURAL:$1|día|días}}',
'login'                   => 'Entrar',
'nav-login-createaccount' => 'Entrar / Enrejjistrar',
'userlogin'               => 'Entrar / Registrarse',
'logout'                  => 'Salir',
'userlogout'              => 'Salir',
'nologin'                 => "¿No tienes un cuento? '''$1'''.",
'nologinlink'             => 'Crea un cuento',
'createaccount'           => 'Crea un nuevo cuento',
'gotaccount'              => "¿Ya tienes un cuento? '''$1'''.",
'createaccountmail'       => 'por una letra electrónica',
'userexists'              => 'El nombre que entrates ya se usa.
Si puede ser, escoge un otro nombre.',
'createaccounterror'      => 'No se pudo crear el cuento: $1',
'mailmypassword'          => 'Embiar una nueva koddiche por e-mail',
'emailconfirmlink'        => 'Confirma su adderesso de letra electrónica',
'accountcreated'          => 'Cuento creado',
'accountcreatedtext'      => 'El cuento del usuario para $1 fue creado.',
'loginlanguagelabel'      => 'Lingua: $1',

# Edit page toolbar
'bold_sample'     => 'Teksto gordo',
'bold_tip'        => 'Teksto gordo',
'italic_sample'   => 'Teksto cursivo',
'italic_tip'      => 'Teksto en cursiva',
'link_sample'     => 'Título del link',
'link_tip'        => 'Link interno',
'extlink_sample'  => 'http://www.example.com Título del link',
'extlink_tip'     => 'Link eksterno (acόrdate de ajustar el prefiks http://)',
'headline_sample' => 'Escrituria de títůlo',
'headline_tip'    => 'Titular de nivel 2',
'math_sample'     => 'Escribe aquí una formula',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Escribid aquí texto sin formato',
'nowiki_tip'      => 'Iñorar el formato wiki',
'image_tip'       => 'Imagen incorporada',
'media_tip'       => 'Link al arxivo multimedia',
'sig_tip'         => 'Firma, data i ora',
'hr_tip'          => 'Liña orizontala (úsala de vez en cuando)',

# Edit pages
'summary'                          => 'Resumido:',
'subject'                          => 'Tema/título:',
'minoredit'                        => 'Esta es una edición chiquitica',
'watchthis'                        => 'Cudia esta hoja',
'savearticle'                      => 'Enrejjistra la hoja',
'preview'                          => 'Previsualizar',
'showpreview'                      => 'Mostrar la previsualización',
'showdiff'                         => 'Amostrar los trocamientos',
'anoneditwarning'                  => "'''Noticia:''' La sesyón no empeçó con un cuento de usuario.
Tu adresso de IP va ser enrejjistrado en la istoria de la hoja.",
'summary-preview'                  => 'Previsualización del resumen:',
'blockednoreason'                  => 'La razόn no se diό',
'whitelistedittext'                => 'Tienes que $1 para pueder trocar artículos.',
'loginreqpagetext'                 => 'Tienes que $1 para pueder ver otras hojas.',
'accmailtitle'                     => 'La kontrasenya ha sido embiada.',
'accmailtext'                      => 'La kontrasenya para "$1" se ha embiado a $2.',
'newarticle'                       => '(Nuevo)',
'newarticletext'                   => 'Allegates a una hoja que daínda no egziste.
Para crear esta hoja, empeça a escribir en la caxa de abaxo. Mira [[{{MediaWiki:Helppage}}|la hoja de ayudo]] para saber más.
Si venites aquí por yerro, torna a la hoja de antes.',
'noarticletext'                    => 'En este momento no ay teksto en esta hoja.
Puedes [[Special:Search/{{PAGENAME}}|buscar el título de esta hoja]] en otras hojas,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} buscar en los registros],
o [{{fullurl:{{FULLPAGENAME}}|action=edit}} trocar esta hoja]</span>.',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'¡Acórdate que esto es sólo una previsualización y daínda no se registró!'''",
'editing'                          => 'Trocando $1',
'editingsection'                   => 'Trocando $1 (sección)',
'yourtext'                         => 'Tu teksto',
'yourdiff'                         => 'Diferencias',
'copyrightwarning'                 => "Si puede ser, observa que todas las contribuciones a {{SITENAME}} se consideran hechas públicas abaxo la $2 (ver detalyos en $1). Si no queres que la gente endereche tus tekstos escritos sin piadad i los esparta libberamente, alora no los metas aquí. También nos estás asegurando ansí que escribites este teksto tu mismo i sos el dueño de los derechos de autor, o lo copiates desde el dominio público u otra fuente libbero.'''¡QUE N0 USES TEKSTOS ESCRITOS CON COPYRIGHT SIN PERMISSIÓN!'''<br />",
'templatesused'                    => '{{PLURAL:$1|El xabblón usado|Los xabblones usados}} en esta hoja:',
'templatesusedpreview'             => '↓ {{PLURAL:$1|El xabblón usado|Los xabblones usados}} en esta vista:',
'template-protected'               => '(guardada)',
'template-semiprotected'           => '(media guardada)',
'hiddencategories'                 => 'Esta hoja es un miembro de {{PLURAL:$1|1 kateggoría escondida|$1 kateggorías escondidas}}:',
'nocreate-loggedin'                => 'No tienes el permisso de creas hojas nuevas.',
'permissionserrorstext-withaction' => 'No tienes el permiso para $2, por las {{PLURAL:$1|razón|razones}} venideras:',

# History pages
'viewpagelogs'           => 'Ver los registros de esta hoja',
'currentrev-asof'        => 'Versión de alcabo de $1',
'revisionasof'           => 'Rēvisión de $1',
'previousrevision'       => '← Rēvisión de antes',
'nextrevision'           => 'Rêvisión venidera →',
'currentrevisionlink'    => 'Revisión actual',
'cur'                    => 'act',
'last'                   => 'de alcabo',
'page_first'             => 'primeras',
'page_last'              => 'de alcabo',
'histlegend'             => "Selección de diferencias: marca los selectores de las versiones a comparar y pulsa ''enter'' o el botón de abajo.<br />
Leyenda: (act) = diferencias con la versión actual,
(prev) = diferencias con la versión previa, M = edición menor",
'history-fieldset-title' => 'Buscar en la istoria',
'history-show-deleted'   => 'Sólamente efassado',
'histfirst'              => 'Primeras',
'histlast'               => 'De alcabo',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vazío)',

# Revision deletion
'rev-delundel'               => 'mostra/esconde',
'rev-showdeleted'            => 'mostra',
'revdelete-show-file-submit' => 'Sí',
'revdelete-radio-same'       => '(no troques)',
'revdelete-radio-set'        => 'Sí',
'revdelete-radio-unset'      => 'No',
'revdelete-log'              => 'Razón:',
'revdel-restore'             => 'Troca la viźibbilidad',
'revdelete-content'          => 'contenido',
'revdelete-reasonotherlist'  => 'Otra razón',

# History merging
'mergehistory-reason' => 'Razón:',

# Merge log
'revertmerge' => 'Deshazer fusión',

# Diffs
'history-title'           => 'Istoria de revisiones para «$1»',
'difference'              => '(Diferencias entre rêvisiones)',
'lineno'                  => 'Liña $1:',
'compareselectedversions' => 'Comparar versiones escogidas',
'editundo'                => 'deshazer',

# Search results
'searchresults'             => 'Resultados de la búsqueda',
'searchresults-title'       => 'Resultados de la búsqueda de «$1»',
'searchresulttext'          => 'Para saber más encima de buscar en {{SITENAME}}, mira la [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Buscates \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|todas las hojas que empeçan con "$1"]] {{int:pipe-separator}} [[Special:WhatLinksHere/$1|todas las hojas que dan link a «$1»]])',
'searchsubtitleinvalid'     => "Buscates '''$1'''",
'notitlematches'            => 'No se pudo topar en dingún título.',
'notextmatches'             => 'No se pudo topar en dinguna hoja.',
'prevn'                     => '{{PLURAL:$1|$1}} de antes',
'nextn'                     => '{{PLURAL:$1|$1}} venideras',
'prevn-title'               => '$1 {{PLURAL:$1|resultado|resultados}} de antes',
'nextn-title'               => 'Venideros $1 {{PLURAL:$1|resultado|resultados}}',
'shown-title'               => 'Mostra $1 {{PLURAL:$1|resultado|resultados}} por hoja',
'viewprevnext'              => 'Ver ($1 {{int:pipe-separator}} $2) ($3).',
'searchhelp-url'            => 'Help:Ayudo',
'searchprofile-everything'  => 'Todo',
'searchprofile-advanced'    => 'Adelantado',
'search-result-size'        => '$1 ({{PLURAL:$2|1 biervo|$2 biervos}})',
'search-redirect'           => '(rēdirijjado desde $1)',
'search-section'            => '(seksyón $1)',
'search-suggest'            => 'Quisites dezir: $1',
'search-interwiki-caption'  => 'Proyectos hermanos',
'search-interwiki-default'  => 'Los resultados de $1:',
'search-interwiki-more'     => '(más)',
'search-mwsuggest-enabled'  => 'con consejos',
'search-mwsuggest-disabled' => 'no ay consejos',
'searchall'                 => 'todos',
'nonefound'                 => "'''Nota''': Por defecto sólo se busca en algunos espacios de nombre.
Proba a usar el prefixo ''all:'' para buscar en todo el contenido (incluyendo las hojas de diskussión, xabblones, etc.) o usa el espacio de nombre que queres como prefixo. También puedes usar el formulario de búsqueda adelantada que aparece abaxo.

Las búsquedas producen más o munco a buscar biervos comunes como «la» o «de», que no están en el índice, o por especificar más de una palabra a buscar (sólo las hojas que contienen todos los términos de búsqueda van aparecer en el resultado).",
'powersearch'               => 'Búsqueda adelantada',
'powersearch-legend'        => 'Búsqueda adelantada',
'powersearch-ns'            => 'Busca en los espacios de nombres:',
'powersearch-redir'         => 'Mostra las redirecciones',
'powersearch-field'         => 'Busca por',
'powersearch-toggleall'     => 'Todos',
'search-external'           => 'Búsqueda eksterna',

# Preferences page
'preferences'               => 'Preferencias',
'mypreferences'             => 'Mis preferencias',
'prefs-skin'                => 'Vista',
'prefs-math'                => 'Fórmulas',
'prefs-rc'                  => 'Los Trocamientos de Alcabo',
'prefs-watchlist'           => 'Lista de los Trocamientos Preferidos',
'prefs-watchlist-days'      => 'El número de los días a mostrar en la lista de los trocamientos preferidos:',
'prefs-watchlist-days-max'  => '7 días a lo más muncho',
'prefs-resetpass'           => 'Trocar la parola',
'prefs-rendering'           => 'Vista',
'timezoneregion-africa'     => 'África',
'timezoneregion-america'    => 'América',
'timezoneregion-antarctica' => 'Antárctica',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-australia'  => 'Ostralia',
'timezoneregion-europe'     => 'Europa',
'youremail'                 => 'El adderesso de tu letra electrόnica:',
'username'                  => 'Nombre de usuario:',
'yourlanguage'              => 'Lingua:',
'email'                     => 'Letra electrónica',

# Groups
'group-sysop' => 'Administradores',

'grouppage-sysop' => '{{ns:project}}:Administradores',

# Rights
'right-edit' => 'Trocar las hojas',

# User rights log
'rightslog' => 'Trocamientos de profil de usuario',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'meldar esta hoja',
'action-edit'       => 'trocar esta hoja',
'action-createpage' => 'crear hojas',
'action-delete'     => 'efassar esta hoja',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|trocamiento|trocamientos}}',
'recentchanges'                  => 'Trocamientos frescos',
'recentchanges-legend'           => 'Opciones encima de los trocamientos frescos',
'recentchangestext'              => 'Perseguid en esta hoja, los trocamientos de alcabo realizados en la Viki.',
'recentchanges-feed-description' => 'Perseguir los trocamientos más nuevos en el viki en este feed.',
'rcnote'                         => "Debaxo {{PLURAL:$1|ay '''1''' trocamiento realizado|están los dal cabo '''$1''' trocamientos realizados}} en  {{PLURAL:$2|el dal cabo día|los dal cabo '''$2''' días}}, hasta el $4, $5.",
'rclistfrom'                     => 'Mostra los trocamientos nuevos empeçando desde $1',
'rcshowhideminor'                => '$1 trocamientos chiquiticos',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 empleadores enrējjistrados',
'rcshowhideanons'                => '$1 empleadores anonimes',
'rcshowhidemine'                 => '$1 mis ediciones',
'rclinks'                        => 'Ver los dal cabo $1 trocamientos en los dal cabo $2 días.<br />$3',
'diff'                           => 'dif',
'hist'                           => 'ist',
'hide'                           => 'Esconder',
'show'                           => 'Amostrar',
'minoreditletter'                => 'ch',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Mostra los detalyos (cale JavaScript)',
'rc-enhanced-hide'               => 'Guarda los detalyos',

# Recent changes linked
'recentchangeslinked'         => 'Trocamientos conectados',
'recentchangeslinked-feed'    => 'Trocamientos conectados',
'recentchangeslinked-toolbox' => 'Trocamientos conectados',
'recentchangeslinked-title'   => 'Los trocamientos relacionados con "$1"',
'recentchangeslinked-summary' => "La lista dêbbaxo, es la lista de los trocamientos de alcabo, por las hojas que dan link a la hoja siñalada (o por los miembros de la kateggoría siñalada).
Las hojas en tu [[Special:Watchlist|lista de escogidas]] son escritas '''gordas'''.",
'recentchangeslinked-page'    => 'Nombre de la hoja',
'recentchangeslinked-to'      => 'Mostra los trocamientos freskos en lugar de la hoja indicada',

# Upload
'upload'        => 'Cargar una dosya',
'uploadlogpage' => 'Subidas de arxivos',
'uploadedimage' => 'subió «[[$1]]»',

# Special:ListFiles
'listfiles_name' => 'Nombre',
'listfiles_user' => 'Usuario',

# File description page
'file-anchor-link'          => 'Archivo',
'filehist'                  => 'Istoria del dosyé',
'filehist-help'             => 'Klika encima de una data/ora para vel el arxivo de esta data.',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura de la versión de $1',
'filehist-user'             => 'Empleador',
'filehist-dimensions'       => 'Dimensiones',
'filehist-comment'          => 'Comentario',
'imagelinks'                => 'Linkes al arxivo multimedia',
'linkstoimage'              => '{{PLURAL:$1|La hoja venidera da link|Las hojas venideras dan link}} a este arxivo:',
'sharedupload'              => 'Este arxivo es de $1 i puede ser usado por otros proyectos.',
'uploadnewversion-linktext' => 'Subir una nueva versión de este arxivo',

# Random page
'randompage' => 'Página por ventura',

# Statistics
'statistics' => 'Estatísticas',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|bayt|baytes}}',
'nmembers'      => '$1 {{PLURAL:$1|miembro|miembros}}',
'prefixindex'   => 'Todas las hojas con prefixo',
'newpages'      => 'Hojas nuevas',
'ancientpages'  => 'Artikolos mas viejos',
'move'          => 'taxirea',
'movethispage'  => 'Tashirea esta hoja',
'pager-newer-n' => '{{PLURAL:$1|1 venidero|$1 venideros}}',
'pager-older-n' => '{{PLURAL:$1|1 de antes|$1 de antes}}',

# Book sources
'booksources'               => 'Fuentes de libros',
'booksources-search-legend' => 'Buscar fuentes de libros',
'booksources-go'            => 'Yir',

# Special:Log
'log' => 'Registros',

# Special:AllPages
'allpages'       => 'Todas las hojas',
'alphaindexline' => '$1 a $2',
'prevpage'       => 'Hoja de antés ($1)',
'allpagesfrom'   => 'Mostrar hojas que empecen por:',
'allpagesto'     => 'Mostrar hojas escapadas con:',
'allarticles'    => 'Todos los artikolos',
'allinnamespace' => 'Todas las pajinas (espasio $1)',
'allpagesnext'   => 'Siguiente',
'allpagessubmit' => 'Amostrar la lista',

# Special:Categories
'categories'                    => 'Kategorías',
'special-categories-sort-count' => 'ordenar por número',
'special-categories-sort-abc'   => 'ordenar alefbeticamente',

# Special:LinkSearch
'linksearch' => '↓ Linkes eksternos',

# Special:Log/newusers
'newuserlogpage'          => 'Registro de creación de usuarios',
'newuserlog-create-entry' => 'Usuario nuevo',

# Special:ListGroupRights
'listgrouprights-members' => '(ver los miembros de este grupo)',

# E-mail user
'emailuser' => 'Embia e-mail a este usuario',

# Watchlist
'watchlist'         => 'Mi lista de escogidas',
'mywatchlist'       => 'Mi lista de escogidas',
'watchlistfor'      => "(para '''$1''')",
'addedwatch'        => 'Ajustado a la lista de escogidas',
'addedwatchtext'    => "La hoja «[[:$1]]» fue ajustada a tu [[Special:Watchlist|lista de escogidas]]. Los trocamientos venideros en esta hoja i en tu hoja de diskussión associada se van indicar aí, i la hoja va aparecer '''gordo''' en la hoja de [[Special:RecentChanges|trocamientos freskos]] para hazerla más kolay de detektar.

Cuando queres eliminar la hoja de tu lista de escogidas, piza «Dexar de cudiar» en el menú.",
'removedwatch'      => 'Quitado de la lista de escogidas',
'removedwatchtext'  => 'La hoja «[[:$1]]» fue eliminada de tu [[Special:Watchlist|lista de escogidas]].',
'watch'             => 'cudia',
'watchthispage'     => 'Cudia esta hoja',
'unwatch'           => 'dexa de cudiar',
'watchlist-details' => '{{PLURAL:$1|$1 hoja|$1 hojas}} en tu lista de escogidas, sin contar las de la diskussión.',
'wlshowlast'        => 'Ver los trocamientos de las últimas $1 oras, $2 días  $3',
'watchlist-options' => 'Opciones de la lista de escogidas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Cudiando...',
'unwatching' => 'Dexando de cudiar...',

# Delete
'deletepage'            => 'Efassar esta hoja',
'confirmdeletetext'     => 'Estás al punto de efassar una hoja
en forma turable, ansí como todo su istoria.
Si puede ser, confirma que de verdad queres hazer esto, que estás entendiendo las
resultados, i que lo estás haziendo de acorddo con las [[{{MediaWiki:Policy-url}}|Políticas]].',
'actioncomplete'        => 'Aksion kompleta',
'deletedtext'           => '"<nowiki>$1</nowiki>" fue efassado.
Mira $2 para un registro de los efassados nuevos.',
'deletedarticle'        => 'efassó «[[$1]]»',
'dellogpage'            => 'Registro de efassados',
'deletecomment'         => 'Razón:',
'deleteotherreason'     => 'Otra razón:',
'deletereasonotherlist' => 'Otra razón',
'deletereason-dropdown' => '* Motivos generales de efassamientos
** La demanda del criador de la hoja
** Violación de copyright
** Vandalismo',

# Rollback
'rollbacklink' => 'Aboltar',

# Protect
'protectlogpage'              => 'Protecciones de las hojas',
'protectedarticle'            => 'guardó «[[$1]]»',
'modifiedarticleprotection'   => 'trocó el nivel de protección de «[[$1]]»',
'prot_1movedto2'              => '[[$1]] trasladado a [[$2]]',
'protectcomment'              => 'Razón:',
'protectexpiry'               => 'Escapa:',
'protect_expiry_invalid'      => 'Tiempo de escapación yerrado.',
'protect_expiry_old'          => 'El tiempo de escapación está en el passado.',
'protect-text'                => "Puedes ver i trocar el nivel de protección de la hoja '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Tu cuento no tiene permissión para trocar los niveles de protección de una hoja.
A continuación se mostran las opciones actuales de la hoja '''$1''':",
'protect-cascadeon'           => 'Esta hoja está guardada en momento porque está incluida en {{PLURAL:$1|la hoja venidera|las hojas venideras}}, que tienen activada la opción de protección en grados. Puedes trocar el nivel de protección de esta hoja, ma no va afectar a la protección en grados.',
'protect-default'             => 'Permitir todos los usuarios',
'protect-fallback'            => 'Tiene menester del permiso «$1»',
'protect-level-autoconfirmed' => 'Bloquear usuarios nuevos y no registrados',
'protect-level-sysop'         => 'Sólo administradores',
'protect-summary-cascade'     => 'con grados',
'protect-expiring'            => 'caduca el $1 (UTC)',
'protect-cascade'             => 'Protección en cascada - guardar todas las hojas incluidas en ésta.',
'protect-cantedit'            => 'No puedes trocar el nivel de protección porque no tienes permissión para hazer ediciones.',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Nivel de restricción:',

# Undelete
'undeletelink'     => 've/restora',
'undeletedarticle' => 'restoró «[[$1]]»',

# Namespace form on various pages
'namespace'      => 'Espacio de nombres:',
'invert'         => 'Invertir selección',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Ajustamientos del empleador',
'contributions-title' => 'Ajustamientos {{GENDER:$1|del usuario|de la usuaria}} $1',
'mycontris'           => 'Mis ajustamientos',
'contribsub2'         => '$1 ($2)',
'uctop'               => '(última modificación)',
'month'               => 'Desde el mes (i antes):',
'year'                => 'Desde el año (i antes):',

'sp-contributions-newbies'  => 'Mostrar solo las ajustamientos de los usuarios nuevos',
'sp-contributions-blocklog' => 'registro de bloqueos',
'sp-contributions-talk'     => 'Diścutir',
'sp-contributions-search'   => 'Buscar ajustamientos',
'sp-contributions-username' => 'Dirección IP o nombre de usuario:',
'sp-contributions-submit'   => 'Buscar',

# What links here
'whatlinkshere'            => 'Lo que se ata con aquí',
'whatlinkshere-title'      => 'Hojas que dan link a "$1"',
'whatlinkshere-page'       => 'Hoja:',
'linkshere'                => "Las hojas venideras dan link a '''[[:$1]]''':",
'isredirect'               => 'Hoja redirigida',
'istemplate'               => 'inclusión',
'isimage'                  => 'Link del image',
'whatlinkshere-prev'       => '{{PLURAL:$1|de antes|de antes $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|venidera|venideras $1}}',
'whatlinkshere-links'      => '← linkes',
'whatlinkshere-hideredirs' => '$1 redirecciones',
'whatlinkshere-hidetrans'  => '$1 inclusiones',
'whatlinkshere-hidelinks'  => '$1 linkes',
'whatlinkshere-filters'    => 'Filtres',

# Block/unblock
'blockip'                  => 'Bloquear usuario',
'ipboptions'               => '2 oras:2 hours,1 día:1 day,3 días:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 año:1 year,para siempre:infinite',
'ipblocklist'              => 'Lista de direcciones IP y nombres de usuario bloqueadas',
'blocklink'                => 'Bloquea',
'unblocklink'              => 'quita el bloqueo',
'change-blocklink'         => 'troca el bloqueo',
'contribslink'             => 'Ajustamientos',
'blocklogpage'             => 'Bloqueos de usuarios',
'blocklogentry'            => 'bloqueó a [[$1]] $3 durante un tiempo de $2',
'unblocklogentry'          => 'desbloqueó a "$1"',
'block-log-flags-nocreate' => 'desactivada la creación de cuentos',

# Move page
'movepagetext'     => "Usando el formulario venidero se va renombrar una hoja, quitando todo su istoria a su nuevo nombre.
El título de antes se va convertir en una redirección al nuevo título.
Los linkes al antiguo título de la hoja no se van trocar.
Asegúrate de no dexar [[Special:DoubleRedirects|redirecciones dobles]] o [[Special:BrokenRedirects|rotas]].
Tú sos responsable de aranjjar los linkes de manera menesterosa.

Acórdate que la hoja '''no''' va ser renombrada si ya egziste una hoja con esta nuevo título, a no ser que sea una hoja vazía o una redirección sin istoria.
Esto siñifica que vas pueder renombrar una hoja a su título original si hazes un yerro, ma que no vas pueder sobrescribir una hoja que ya existe.

'''¡Atansión!'''
Este puede ser un trocamiento muy muy emportante e inesperado para una hoja popular;
si puede ser, asegúrate de entender las resultados del lo que hazes antes de yir endelantre.",
'movepagetalktext' => "La hoja de diskussión associada, si egziste, va ser renombrada otomáticamente '''a menos que:'''
*Esté renombrando la hoja entre espacios de nombres diferentes,
*Una hoja de diskussión no vazía ya egziste con el nombre nuevo, o
*Desactivara la opción \"Renombrar la hoja de diskussión también\".

En estos casos, va deber trasladar manualmente el contenido de la hoja de diskussión.",
'movearticle'      => 'Renombra la hoja',
'newtitle'         => 'A título nuevo',
'move-watch'       => 'Cudiar este artículo',
'movepagebtn'      => 'Renombra la hoja',
'pagemovedsub'     => 'Renombrado realizado con reuxitá',
'movepage-moved'   => '\'\'\'"$1" fue renombrado a "$2".\'\'\'',
'articleexists'    => 'Una hoja con este nombre ya egziste, o el nombre que escogites no es valable.
Si puede ser, escoge otro nombre.',
'talkexists'       => 'La hoja fue renombrada con reuxito, ma la diskussión no se pudo renombrar porque ya egziste una en el título nuevo. Si puede ser, házelo manualmente.',
'movedto'          => 'renombrado a',
'movetalk'         => 'Renombrar la hoja de diskussión también, si es possible.',
'1movedto2'        => 'El nuevo nombre de la hoja [[$1]]; agora es [[$2]]',
'1movedto2_redir'  => 'El títůlo [[$1]] fue reddireksyonado a la hoja [[$2]]',
'movelogpage'      => 'Registro de traslados',
'movereason'       => 'Razón:',
'revertmove'       => 'abolta',

# Export
'export' => 'Exporta la hoja',

# Namespace 8 related
'allmessages'        => 'Mesajes del sistema',
'allmessagesname'    => 'Nombre',
'allmessagesdefault' => 'Teksto por defekto',
'allmessagescurrent' => 'Teksto aktual',

# Thumbnails
'thumbnail-more' => 'Engrandecer',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tu hoja de empleador',
'tooltip-pt-mytalk'               => 'Tu hoja de diskussión',
'tooltip-pt-preferences'          => 'Mis preferencias',
'tooltip-pt-watchlist'            => 'La lista de los trocamientos acontecidos en las hojas cudiadas.',
'tooltip-pt-mycontris'            => 'La lista de tus ajustamientos',
'tooltip-pt-login'                => 'Se le aconseja a entrar, ma no es obligado.',
'tooltip-pt-logout'               => 'Salir de la sessión',
'tooltip-ca-talk'                 => 'Diskusyón encima del artícůlo',
'tooltip-ca-edit'                 => 'Puedes trocar esta hoja 
Si puede ser, usa el botón de prēviźualiźasyón antes de enrejjistrarla.',
'tooltip-ca-addsection'           => 'Empeça una nueva sección',
'tooltip-ca-viewsource'           => 'Esta hoja está guardada; sólo puedes ver su codd fuente',
'tooltip-ca-history'              => 'Versiones viejos de esta hoja',
'tooltip-ca-protect'              => 'Guardar esta hoja',
'tooltip-ca-delete'               => 'Efassar esta hoja',
'tooltip-ca-move'                 => 'Taxirea (renombra) esta hoja',
'tooltip-ca-watch'                => 'Ajusta esta hoja a tu lista de escogidas',
'tooltip-ca-unwatch'              => 'Quita esta hoja de tu lista de escogidos',
'tooltip-search'                  => 'Busca en {{SITENAME}}',
'tooltip-search-go'               => 'Si ay una hoja con este nombre egzakto, vate allá.',
'tooltip-search-fulltext'         => 'Busca este teksto en las hojas',
'tooltip-n-mainpage'              => 'Torna a la Hoja Primera',
'tooltip-n-mainpage-description'  => 'Visita la Primera Hoja',
'tooltip-n-portal'                => 'Encima del prodjekto, ¿qué se puede hazer i ánde topar todo?',
'tooltip-n-currentevents'         => 'Topar informaciones encima de los acontecimientos actuales',
'tooltip-n-recentchanges'         => 'La lista de los trocamientos frescos en el viki',
'tooltip-n-randompage'            => 'Carga una hoja por ventura',
'tooltip-n-help'                  => 'El lugar para ambezarse',
'tooltip-t-whatlinkshere'         => 'La lista de todas las hojas del viki que se atan con ésta',
'tooltip-t-recentchangeslinked'   => 'Los trocamientos de alcabo de las hojas linkeados desde aquí',
'tooltip-feed-rss'                => 'Sindicación RSS de esta hoja',
'tooltip-feed-atom'               => 'Sindicación Atom de esta hoja',
'tooltip-t-contributions'         => 'Ver la lista de ajustamientos de este usuario',
'tooltip-t-emailuser'             => 'A este usuario, mándale una letra electrόnica (ímey)',
'tooltip-t-upload'                => 'Manda imajjes o dosyas de multimedia al servidor',
'tooltip-t-specialpages'          => 'La lista de todas las hojas especiales',
'tooltip-t-print'                 => 'Versión imprimible de la hoja',
'tooltip-t-permalink'             => 'Link permanente a esta versión de la hoja',
'tooltip-ca-nstab-main'           => 'Ve el artículo',
'tooltip-ca-nstab-user'           => 'Ve la hoja de usuario',
'tooltip-ca-nstab-special'        => 'Esta es una hoja especial, la hoja ya no se puede trocar',
'tooltip-ca-nstab-project'        => 'Ver la hoja del prodjekto',
'tooltip-ca-nstab-image'          => 'Ver la hoja de la image.',
'tooltip-ca-nstab-template'       => 'Ver el xabblón',
'tooltip-ca-nstab-category'       => 'Ve la hoja de categoría',
'tooltip-minoredit'               => 'Márcalo como un trocamiento chiquitico',
'tooltip-save'                    => 'Guardar los trocamientos',
'tooltip-preview'                 => 'Que previzualize sus trocamientos, ¡si puede ser, que use esto antes de enregistrar!',
'tooltip-diff'                    => 'Mostra los trocamientos que él/ella hizo en el texhto.',
'tooltip-compareselectedversions' => 'Ve las diferencias entre las dos versiones escogidas de esta hoja.',
'tooltip-watch'                   => 'Ajusta esta hoja a tu lista de escogidas',
'tooltip-rollback'                => '«Aboltar» abolta todas los trocamientos del empleador de alcabo solo klikando una vez.',
'tooltip-undo'                    => '«Deshazer» revierte la edición seleccionada y avre la hoja de edición en el modo de previsualización.
Permite ajustar una razón al resumen de edición.',

# Attribution
'anonymous' => '{{PLURAL:$1|Uzuario anonimo|Uzuarios anonimos}} de {{SITENAME}}',

# Browsing diffs
'previousdiff' => '← Trocamiento más antiguo',
'nextdiff'     => 'Edición más nueva →',

# Media information
'file-info-size'       => '($1 × $2 píkseles; boy del arxivo: $3; tipo MIME: $4)',
'file-nohires'         => '<small>No disponible a mayor resolución.</small>',
'svg-long-desc'        => '(arxivo SVG, nominalmente $1 × $2 píkseles, boy del arxivo: $3)',
'show-big-image'       => 'Resolución original',
'show-big-image-thumb' => '<small>Boy de esta vista previa: $1 × $2 píkseles</small>',

# Bad image list
'bad_image_list' => 'El formato es ansina:

Sólo elementos listados (satires que empeçan con *) se aprecian.
El primer link del satir debe de ser un link al foto negro (al que se quere bloquear).
El resto de los linkes del mismo satir se juzgan como eksepsyones (por enxemplo, artícůlos encima de los cualos la foto puede aparecer).',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Este arxivo contiene enformación adicional (metadatos), probablemente ajustada por la cámara digital, el escáner o el programa usado para crearlo o digitalizarlo. Si el arxivo fue modificado desde su estado original, puede aver perdido algunos detalyos.',
'metadata-expand'   => 'Mostra los detalyos ekstendidos',
'metadata-collapse' => 'Esconder los detalyos ekstendidos',
'metadata-fields'   => 'Los campos de metadatos EXIF que se listan en este messaj se van a mostrar en la hoja de la descripción de la foto daínda cuando la tabla de metadatos está cerrada.
Los otros campos se van a guardar por defecto.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-filesource'   => 'Manadéro de archivo',
'exif-gpstimestamp' => 'Tiémpo GPS (óra atómica)',
'exif-gpsdatestamp' => 'Dáta GPS',

'exif-meteringmode-255' => 'Otro',

'exif-lightsource-9'  => 'Bueno tiémpo',
'exif-lightsource-10' => 'Tiémpo nuvlozo',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometros por óra',

# External editor support
'edit-externally'      => 'Trocar esto arxivo usando una aplicación eksterna',
'edit-externally-help' => '(Melda las [http://www.mediawiki.org/wiki/Manual:External_editors enstruksiones de configuración] -en inglés- para saber más)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todos',
'imagelistall'     => 'todas',
'watchlistall2'    => 'todos',
'namespacesall'    => 'todos',
'monthsall'        => '(todos)',

# E-mail address confirmation
'confirmemail'         => 'Konfirmar direksion e-pósta',
'confirmemail_send'    => 'Embiar el kodigo de konfirmasion.',
'confirmemail_sent'    => 'Konfirmasion de pósta embiada.',
'confirmemail_success' => 'Su direksion de pósta a sido konfirmada. Agóra puedes registrarse e kolaborar en el wiki.',

# Trackbacks
'trackbackremove' => '([$1 Efasár])',

# Delete conflict
'recreate' => 'Krear de muevo',

# action=purge
'confirm_purge_button' => 'Akseptár',

# Multipage image navigation
'imgmultipageprev' => '← pajina anterior',
'imgmultipagenext' => 'siguiente pajina →',
'imgmultigo'       => 'Ir!',

# Table pager
'table_pager_next'         => 'Pajina siguiente',
'table_pager_prev'         => 'Pajina anterior',
'table_pager_first'        => 'Primera pajina',
'table_pager_last'         => 'Ultima pajina',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty'        => 'No hay resultados',

# Auto-summaries
'autoredircomment' => 'Redireksionado a [[$1]]',
'autosumm-new'     => 'Pajina mueva: $1',

# Watchlist editing tools
'watchlisttools-view' => 'Ver los trocamientos',
'watchlisttools-edit' => 'Ver i trocar tu lista de escogidas',
'watchlisttools-raw'  => 'Troca tu lista de escogidas en crudo',

# Special:Version
'version'                  => 'Versión',
'version-specialpages'     => 'Pajinas espesiales',
'version-other'            => 'Otros',
'version-version'          => '(Versión $1)',
'version-software-version' => 'Versión',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Buscar',

# Special:SpecialPages
'specialpages' => 'Hojas especiales',

);
