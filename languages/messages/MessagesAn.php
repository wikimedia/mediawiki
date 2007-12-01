<?php
/** Aragonese (Aragonés)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Juanpabl
 * @author G - ג
 * @author Willtron
 * @author Nike
 * @author Siebrand
 */

$fallback = 'es';

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Espezial',
	NS_MAIN           => '',
	NS_TALK           => 'Descusión',
	NS_USER           => 'Usuario',
	NS_USER_TALK      => 'Descusión_usuario',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Descusión_$1',
	NS_IMAGE          => 'Imachen',
	NS_IMAGE_TALK     => 'Descusión_imachen',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Descusión_MediaWiki',
	NS_TEMPLATE       => 'Plantilla',
	NS_TEMPLATE_TALK  => 'Descusión_plantilla',
	NS_HELP           => 'Aduya',
	NS_HELP_TALK      => 'Descusión_aduya',
	NS_CATEGORY       => 'Categoría',
	NS_CATEGORY_TALK  => 'Descusión_categoría',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Subrayar os binclos:',
'tog-highlightbroken'         => 'Formatiar os binclos trencatos <a href="" class="new"> d\'ista traza </a> (y si no, asinas <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Achustar parrafos',
'tog-hideminor'               => 'Amagar edizions menors en a pachina de "zaguers cambeos"',
'tog-extendwatchlist'         => 'Enamplar a lista de seguimiento ta amostrar toz os cambeos afeutatos.',
'tog-usenewrc'                => 'Presentazión amillorada de "zaguers cambeos" (cal JavaScript)',
'tog-numberheadings'          => 'Numerar automaticament os encabezaus',
'tog-showtoolbar'             => "Amostrar a barra d'ainas d'edizión (cal JavaScript)",
'tog-editondblclick'          => 'Autibar edizión de pachinas fendo-ie doble click (cal JavaScript)',
'tog-editsection'             => 'Autibar a edizión por seczions usando binclos [editar]',
'tog-editsectiononrightclick' => "Autibar a edizión de sezions punchando con o botón dreito d'a rateta <br /> en os títols de sezions (cal JavaScript)",
'tog-showtoc'                 => 'Amostrar o endize de contenius (ta pachinas con más de 3 encabezaus)',
'tog-rememberpassword'        => 'Remerar a parabra de paso entre sesions',
'tog-editwidth'               => "O cuatrón d'edizión tien l'amplaria masima",
'tog-watchcreations'          => 'Bexilar as pachinas que creye',
'tog-watchdefault'            => 'Bexilar as pachinas que edite',
'tog-watchmoves'              => 'Bexilar as pachinas que treslade',
'tog-watchdeletion'           => 'Bexilar as pachinas que borre',
'tog-minordefault'            => 'Marcar por defeuto todas as edizions como menors',
'tog-previewontop'            => "Fer beyer l'ambiesta prebia antes d'o cuatrón d'edizión (en cuenta de dimpués)",
'tog-previewonfirst'          => "Amostrar l'ambiesta prebia de l'articlo en a primera edizión",
'tog-nocache'                 => "Desautibar a ''caché'' de pachinas",
'tog-enotifwatchlistpages'    => 'Nimbiar-me un correu cuan bi aiga cambeos en una pachina bexilada por yo',
'tog-enotifusertalkpages'     => 'Nimbiar-me un correu cuan cambee a mía pachina de descusión',
'tog-enotifminoredits'        => 'Nimbiar-me un correu tamién cuan bi aiga edizions menors de pachinas',
'tog-enotifrevealaddr'        => 'Fer beyer a mía adreza electronica en os correus de notificazión',
'tog-shownumberswatching'     => "Amostrar o numero d'usuarios que bexilan l'articlo",
'tog-fancysig'                => 'Siñaduras simplas (sin de binclo automatico)',
'tog-externaleditor'          => 'Emplegar por defeuto á un editor esterno',
'tog-externaldiff'            => 'Emplegar por defeuto un bisualizador de cambeos esterno',
'tog-showjumplinks'           => 'Autibar binclos d\'azesibilidat "saltar ta"',
'tog-uselivepreview'          => 'Autibar prebisualizazión automatica (cal JavaScript) (Esperimental)',
'tog-forceeditsummary'        => 'Abisar-me cuan o campo de resumen siga buedo.',
'tog-watchlisthideown'        => 'Amagar as mías edizions en a lista de seguimiento',
'tog-watchlisthidebots'       => 'Amagar edizions de bots en a lista de seguimiento',
'tog-watchlisthideminor'      => 'Amagar edizions menors en a lista de seguimiento',
'tog-nolangconversion'        => 'Desautibar conversión de bariants',
'tog-ccmeonemails'            => 'Rezibir copias de os correus que nimbío ta atros usuarios',
'tog-diffonly'                => "No fer beyer o conteniu d'a pachina debaxo d'as esferenzias",

'underline-always'  => 'Siempre',
'underline-never'   => 'Nunca',
'underline-default' => "Confegurazión por defeuto d'o nabegador",

'skinpreview' => '(Fer una prebatina)',

# Dates
'sunday'        => 'Domingo',
'monday'        => 'luns',
'tuesday'       => 'martes',
'wednesday'     => 'miércols',
'thursday'      => 'chuebes',
'friday'        => 'biernes',
'saturday'      => 'sabado',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mie',
'thu'           => 'chu',
'fri'           => 'bie',
'sat'           => 'sab',
'january'       => 'chinero',
'february'      => 'febrero',
'march'         => 'marzo',
'april'         => 'abril',
'may_long'      => 'mayo',
'june'          => 'chunio',
'july'          => 'chulio',
'august'        => 'agosto',
'september'     => 'setiembre',
'october'       => 'otubre',
'november'      => 'nobiembre',
'december'      => 'abiento',
'january-gen'   => 'de chinero',
'february-gen'  => 'de febrero',
'march-gen'     => 'de marzo',
'april-gen'     => "d'abril",
'may-gen'       => 'de mayo',
'june-gen'      => 'de chunio',
'july-gen'      => 'de chulio',
'august-gen'    => "d'agosto",
'september-gen' => 'de setiembre',
'october-gen'   => "d'otubre",
'november-gen'  => 'de nobiembre',
'december-gen'  => "d'abiento",
'jan'           => 'chi',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'abr',
'may'           => 'may',
'jun'           => 'chn',
'jul'           => 'chl',
'aug'           => 'ago',
'sep'           => 'set',
'oct'           => 'otu',
'nov'           => 'nob',
'dec'           => 'abi',

# Bits of text used by many pages
'categories'            => 'Categorías',
'pagecategories'        => '{{PLURAL:$1|Categoría|Categorías}}',
'category_header'       => 'Articlos en a categoría "$1"',
'subcategories'         => 'Subcategorías',
'category-media-header' => 'Contenius multimedia en a categoría "$1"',
'category-empty'        => "''Ista categoría no tiene por agora garra articlo ni conteniu multimedia''",

'mainpagetext'      => "O programa MediaWiki s'ha instalato correutament.",
'mainpagedocfooter' => "Consulta a [http://meta.wikimedia.org/wiki/Help:Contents Guía d'usuario] ta mirar informazión sobre cómo usar o software wiki.

== Ta prenzipiar ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de carauteristicas confegurables]
* [http://www.mediawiki.org/wiki/Manual:FAQ Preguntas cutianas sobre MediaWiki (FAQ)]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de correu sobre ta anunzios de MediaWiki]",

'about'          => 'Informazión sobre',
'article'        => 'Articlo',
'newwindow'      => "(s'ubre en una nueba finestra)",
'cancel'         => 'Anular',
'qbfind'         => 'Mirar',
'qbbrowse'       => 'Nabegar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Ista pachina',
'qbpageinfo'     => "Informazión d'a pachina",
'qbmyoptions'    => 'Pachinas propias',
'qbspecialpages' => 'Pachinas espezials',
'moredotdotdot'  => 'Más...',
'mypage'         => 'A mía pachina',
'mytalk'         => 'A mía descusión',
'anontalk'       => "Descusión d'ista IP",
'navigation'     => 'Nabego',

# Metadata in edit box
'metadata_help' => 'Metadatos:',

'errorpagetitle'    => 'Error',
'returnto'          => 'Tornar ta $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Aduya',
'search'            => 'Mirar',
'searchbutton'      => 'Mirar-lo',
'go'                => 'Ir-ie',
'searcharticle'     => 'Ir-ie',
'history'           => 'Istorial de cambeos',
'history_short'     => 'Istorial',
'updatedmarker'     => 'esbiellato dende a zaguera besita',
'info_short'        => 'Informazión',
'printableversion'  => 'Bersión ta imprentar',
'permalink'         => 'Binclo permanent',
'print'             => 'Imprentar',
'edit'              => 'Editar',
'editthispage'      => 'Editar ista pachina',
'delete'            => 'Borrar',
'deletethispage'    => 'Borrar ista pachina',
'undelete_short'    => 'Restaurar {{PLURAL:$1|una edizión|$1 edizions}}',
'protect'           => 'Protexer',
'protect_change'    => 'cambiar a protezión',
'protectthispage'   => 'Protexer ista pachina',
'unprotect'         => 'esprotexer',
'unprotectthispage' => 'Esprotexer ista pachina',
'newpage'           => 'Pachina nueba',
'talkpage'          => "Descusión d'ista pachina",
'talkpagelinktext'  => 'Descutir',
'specialpage'       => 'Pachina Espezial',
'personaltools'     => 'Ainas presonals',
'postcomment'       => 'Adibir un comentario',
'articlepage'       => "Beyer l'articlo",
'talk'              => 'Descusión',
'views'             => 'Bisualizazions',
'toolbox'           => 'Ainas',
'userpage'          => "Beyer a pachina d'usuario",
'projectpage'       => "Beyer a pachina d'o procheuto",
'imagepage'         => "Beyer a pachina d'a imachen",
'mediawikipage'     => "Beyer a pachina d'o mensache",
'templatepage'      => "Beyer a pachina d'a plantilla",
'viewhelppage'      => "Beyer a pachina d'aduya",
'categorypage'      => 'Beyer a pachina de categoría',
'viewtalkpage'      => 'Beyer a pachina de descusión',
'otherlanguages'    => 'Atras luengas',
'redirectedfrom'    => '(Reendrezato dende $1)',
'redirectpagesub'   => 'Pachina reendrezata',
'lastmodifiedat'    => "Zaguera edizión d'ista pachina: $2, $1.", # $1 date, $2 time
'viewcount'         => 'Ista pachina ha tenito {{PLURAL:$1|una besita|$1 besitas}}.',
'protectedpage'     => 'Pachina protechita',
'jumpto'            => 'Ir ta:',
'jumptonavigation'  => 'nabego',
'jumptosearch'      => 'busca',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Informazión sobre {{SITENAME}}',
'aboutpage'         => 'Project:Informazión sobre',
'bugreports'        => "Informes d'error d'o programa",
'bugreportspage'    => "Project:Informe d'errors",
'copyright'         => 'O conteniu ye disponible baxo a lizenzia $1.',
'copyrightpagename' => "Dreitos d'autor de {{SITENAME}}",
'currentevents'     => 'Autualidat',
'currentevents-url' => 'Project:Autualidat',
'disclaimers'       => 'Abiso legal',
'disclaimerpage'    => 'Project:Abiso legal',
'edithelp'          => 'Aduya ta editar pachinas',
'edithelppage'      => 'Help:Cómo se fa ta editar una pachina',
'faq'               => 'Preguntas cutianas',
'faqpage'           => 'Project:Preguntas cutianas',
'helppage'          => 'Help:Aduya',
'mainpage'          => 'Portalada',
'policy-url'        => 'Project:Politicas y normas',
'portal'            => "Portal d'a comunidat",
'portal-url'        => "Project:Portal d'a comunidat",
'privacy'           => 'Politica de pribazidat',
'privacypage'       => 'Project:Politica de pribazidat',
'sitesupport'       => 'Donazions',
'sitesupport-url'   => 'Project:Donazions',

'badaccess'        => 'Error de premisos',
'badaccess-group0' => 'No tiene premiso ta fer serbir ista aizión.',
'badaccess-group1' => "Ista aizión nomás ye premitita ta os usuarios d'a colla $1.",
'badaccess-group2' => "Ista aizión nomás ye premitita ta usuarios de beluna d'istas collas: $1.",
'badaccess-groups' => "Ista aizión nomás ye premitita ta os usuarios de beluna d'as collas: $1.",

'versionrequired'     => 'Cal a bersión $1 de MediaWiki ta fer serbir ista pachina',
'versionrequiredtext' => 'Cal a bersión $1 de MediaWiki ta fer serbir ista pachina. Ta más informazión, consulte [[Special:Version]]',

'ok'                      => "D'alcuerdo",
'retrievedfrom'           => 'Otenito de "$1"',
'youhavenewmessages'      => 'Tiene $1 ($2).',
'newmessageslink'         => 'mensaches nuebos',
'newmessagesdifflink'     => 'Esferenzias con a zaguera bersión',
'youhavenewmessagesmulti' => 'Tiene nuebos mensaches en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'editsectionhint'         => 'Editar a sezión: $1',
'toc'                     => 'Contenius',
'showtoc'                 => 'fer beyer',
'hidetoc'                 => 'amagar',
'thisisdeleted'           => '¿Quiere fer beyer u restaurar $1?',
'viewdeleted'             => '¿Quiere fer beyer $1?',
'restorelink'             => '{{PLURAL:$1|una edizión borrata|$1 edizions borratas}}',
'feedlinks'               => 'Sendicazión como fuent de notizias:',
'feed-invalid'            => 'No se premite ixe tipo de sendicazión como fuent de notizias.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Articlo',
'nstab-user'      => "Pachina d'usuario",
'nstab-media'     => 'Pachina multimedia',
'nstab-special'   => 'Espezial',
'nstab-project'   => "Pachina d'o proyeuto",
'nstab-image'     => 'Imachen',
'nstab-mediawiki' => 'Mensache',
'nstab-template'  => 'Plantilla',
'nstab-help'      => 'Aduya',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'No se reconoxe ista aizión',
'nosuchactiontext'  => "{{SITENAME}} no reconoxe l'aizión espezificata en l'adreza URL",
'nosuchspecialpage' => 'No esiste ixa pachina espezial',
'nospecialpagetext' => "<big>'''A pachina espezial que ha demandato no esiste.'''</big>

Puede trobar una lista de pachinas espezials en [[Special:Specialpages]].",

# General errors
'databaseerror'        => "Error d'a base de datos",
'dberrortext'          => 'Ha escaizito una error de sintacsis en una consulta á la base de datos.
Isto podría endicar una error en o programa.
A zaguera consulta que se miró de fer estió: <blockquote><tt>$1</tt></blockquote> aintro d\'a funzión "<tt>$2</tt>". A error tornata por a base de datos estió "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ha escaizito una error de sintacsis en una consulta á la base de datos. A zaguera consulta que se miró de fer estió: 
"$1" 
aintro d\'a funzión "$2". 
A base de datos retornó a error "<tt>$3: $4</tt>".',
'noconnect'            => "A wiki tiene agora bellas dificultaz tecnicas, y no se podió contautar con o serbidor d'a base de datos. <br />
$1",
'nodb'                 => 'No se podió trigar a base de datos $1',
'cachederror'          => "Ista ye una copia en caché d'a pachina demandata, y puestar que no siga autualizata.",
'laggedslavemode'      => "Pare cuenta: podrían faltar as zagueras autualizazions d'ista pachina.",
'readonly'             => 'Base de datos bloquiata',
'enterlockreason'      => "Esplique a causa d'o bloqueyo, encluyendo una estimazión de cuán se produzirá o desbloqueyo",
'readonlytext'         => "A base de datos de {{SITENAME}} ye bloquiata temporalment, probablement por mantenimiento rutinario, dimpués d'ixo tornará á la normalidat.
L'almenistrador que la bloquió dió ista esplicazión:
<p>$1",
'missingarticle'       => "A base de datos no trobó o testo d'a pachina \"\$1\", que abría d'aber trobato.

Isto gosa pasar si se sigue un binclo enta una esferenzia de bersions zircunduzita, u enta un istorial d'una pachina que ha estato borrata.

Si ista no ye a causa, podría aber trobato una error en o programa. Por fabor, informe d'isto á un almenistrador, endicando-le l'adreza URL.",
'readonly_lag'         => 'A base de datos ye bloquiata temporalment entre que os serbidors se sincronizan.',
'internalerror'        => 'Error interna',
'internalerror_info'   => 'Error interna: $1',
'filecopyerror'        => 'No s\'ha puesto copiar l\'archibo "$1" ta "$2".',
'filerenameerror'      => 'No s\'ha puesto cambiar o nombre de l\'archibo "$1" á "$2".',
'filedeleteerror'      => 'No s\'ha puesto borrar l\'archibo "$1".',
'directorycreateerror' => 'No s\'ha puesto crear o direutorio "$1".',
'filenotfound'         => 'No s\'ha puesto trobar l\'archibo "$1".',
'fileexistserror'      => 'No s\'ha puesto escribir en l\'archibo "$1": l\'archibo ya esiste',
'unexpected'           => 'Balura no prebista: "$1"="$2".',
'formerror'            => 'Error: no se podió nimbiar o formulario',
'badarticleerror'      => 'Ista aizión no se puede no se puede reyalizar en ista pachina.',
'cannotdelete'         => "No se podió borrar a pachina u l'archibo espezificato. (Puestar que belatro usuario l'aiga borrato dinantes)",
'badtitle'             => 'Títol incorreuto',
'badtitletext'         => "O títol d'a pachina demandata ye buedo, incorreuto, u tiene un binclo interwiki mal feito. Puede contener uno u más carauters que no se pueden fer serbir en títols.",
'perfdisabled'         => "S'ha desautibato temporalment ista opzión porque fa lenta a base de datos de traza que dengún no puede usar o wiki.",
'perfcached'           => 'Os datos que siguen son en caché, y podrían no estar esbiellatos:',
'perfcachedts'         => 'Istos datos se troban en a caché, que estió esbiellata por zaguer begada o $1.',
'querypage-no-updates' => "S'han desautibato as autualizazions d'ista pachina. Por ixo, no s'esta esbiellando os datos.",
'wrong_wfQuery_params' => 'Parametros incorreutos ta wfQuery()<br />
Funzión: $1<br />
Consulta: $2',
'viewsource'           => 'Beyer codigo fuen',
'viewsourcefor'        => 'ta $1',
'actionthrottled'      => 'Aizión afogada',
'actionthrottledtext'  => 'Como mida anti-spam, bi ha un limite en o numero de begadas que puede fer ista aizión en un curto espazio de tiempo, y ha blincato iste limite. Aspere bels menutos y prebe de fer-lo nuebament.',
'protectedpagetext'    => 'Ista pachina ha estato bloquiata y no se puede editar.',
'viewsourcetext'       => "Puede beyer y copiar o codigo fuent d'ista pachina:",
'protectedinterface'   => "Ista pachina furne o testo d'a interfaz ta o software. Ye protexita ta pribar o bandalismo. Si creye que bi ha bella error, contaute con un [[{{MediaWiki:Grouppage-sysop}}|Almenistrador]].",
'editinginterface'     => "'''Pare cuenta:''' Ye editando una pachina emplegata ta furnir o testo d'a interfaz de {{SITENAME}}. Os cambeos en ista pachina tendrán efeuto en l'aparenzia d'a interfaz ta os atros usuarios.",
'sqlhidden'            => '(Consulta SQL amagata)',
'cascadeprotected'     => 'Ista pachina ye protexita y no se puede editar porque ye encluyita en {{PLURAL:$1|a siguient pachina|as siguients pachinas}}, que son protexitas con a opzión de "cascada": $2',
'namespaceprotected'   => "No tiene premiso ta editar as pachinas d'o espazio de nombres '''$1'''.",
'customcssjsprotected' => "No tiene premiso ta editar ista pachina porque contiene a confegurazión presonal d'atro usuario.",
'ns-specialprotected'  => "No ye posible editar as pachinas d'o espazio de nombres {{ns:special}}.",

# Login and logout pages
'logouttitle'                => "Fin d'a sesión",
'logouttext'                 => "Ha rematato a sesión.
Puede continar nabegando por {{SITENAME}} anonimament, u puede enzetar unatra sesión atra begada con o mesmo u atro usuario. Pare cuenta que bellas pachinas se pueden amostrar encara como si continase en a sesión anterior, dica que se limpie a caché d'o nabegador.",
'welcomecreation'            => "== ¡Bienbeniu(da), $1! ==

S'ha creyato a suya cuenta. No xublide presonalizar as [[Special:Preferences|preferenzias]].",
'loginpagetitle'             => 'Enzetar a sesión',
'yourname'                   => "Nombre d'usuario:",
'yourpassword'               => 'Parabra de paso:',
'yourpasswordagain'          => 'Torne á escribir a parabra de paso:',
'remembermypassword'         => "Remerar datos d'usuario entre sesions.",
'yourdomainname'             => 'Dominio:',
'externaldberror'            => "Bi abió una error d'autenticazión esterna d'a base de datos u bien no tiene premisos ta autualizar a suya cuenta esterna.",
'loginproblem'               => '<b>Escaizió un problema con a suya autenticazión.</b><br />¡Prebe unatra begada!',
'login'                      => 'Enzetar sesión',
'loginprompt'                => 'Ha de autibar as <i>cookies</i> en o nabegador ta rechistrar-se en {{SITENAME}}.',
'userlogin'                  => 'Enzetar una sesión / creyar cuenta',
'logout'                     => "Salir d'a sesión",
'userlogout'                 => 'Salir',
'notloggedin'                => 'No ha dentrato en o sistema',
'nologin'                    => 'No tiene cuenta? $1.',
'nologinlink'                => 'Creyar una nueba cuenta',
'createaccount'              => 'Creyar una nueba cuenta',
'gotaccount'                 => 'Tiene ya una cuenta? $1.',
'gotaccountlink'             => 'Identificar-se y enzetar sesión',
'createaccountmail'          => 'por correu electronico',
'badretype'                  => 'As parabras de paso que ha escrito no son iguals.',
'userexists'                 => 'Ixe nombre ya ye en uso. Por fabor, meta un nombre diferent.',
'youremail'                  => 'Adreza de correu electronico:',
'username'                   => "Nombre d'usuario:",
'uid'                        => "ID d'usuario:",
'yourrealname'               => 'O suyo nombre reyal:',
'yourlanguage'               => 'Luenga:',
'yourvariant'                => 'Modalidat linguistica',
'yournick'                   => 'A suya embotada (ta siñar):',
'badsig'                     => 'A suya siñadura no ye premitida; comprebe as etiquetas HTML emplegadas.',
'badsiglength'               => 'Embotada masiau larga; no abría de tener más de $1 caráuters.',
'email'                      => 'Adreza electronica',
'prefs-help-realname'        => "* Nombre reyal (opzional): si eslixe escribir-lo, se ferá serbir ta l'atribuzión d'a suya faina.",
'loginerror'                 => 'Error en enzetar a sesión',
'prefs-help-email'           => "Adreza electronica (opzional): Premite á atros usuarios nimbiar-le correus electronicos por meyo de a suya pachina d'usuario u de descusión d'usuario sin d'aber de rebelar a suya identidá.",
'prefs-help-email-required'  => 'Cal una adreza electronica.',
'nocookiesnew'               => "A cuenta d'usuario s'ha creyata, pero agora no ye encara indentificato. {{SITENAME}} fa serbir <em>cookies</em> ta identificar á os usuario rechistratos, pero pareix que las tiene desautibatas. Por fabor, autibe-las e identifique-se con o suyo nombre d'usuario y parabra de paso.",
'nocookieslogin'             => "{{SITENAME}} fa serbir <em>cookies</em> ta la identificazión d'usuarios. Tiene as <em>cookies</em> desautibatas en o nabegador. Por fabor, autibe-las y prebe á identificar-se de nuebas.",
'noname'                     => "No ha escrito un nombre d'usuario correuto.",
'loginsuccesstitle'          => "S'ha identificato correutament",
'loginsuccess'               => 'Ha enzetato una sesión en {{SITENAME}} como "$1".',
'nosuchuser'                 => 'No bi ha garra usuario clamato "$1".
Comprebe si ha escrito bien o nombre u creye una nueba cuenta d\'usuario.',
'nosuchusershort'            => 'No bi ha garra usuario con o nombre "$1". Comprebe si o nombre ye bien escrito.',
'nouserspecified'            => "Ha d'escribir un nombre d'usuario.",
'wrongpassword'              => 'A parabra de paso endicata no ye correuta. Prebe unatra begada.',
'wrongpasswordempty'         => 'No ha escrito garra parabra de paso. Prebe unatra begada.',
'passwordtooshort'           => "A parabra de paso no ye apropiata u ye masiau curta. Ha de tener como menimo $1 caráuters y no ha d'estar a mesma que o nombre d'usuario.",
'mailmypassword'             => 'Nimbia-me una nueba parabra de paso por correu electronico',
'passwordremindertitle'      => 'Nueba parabra de paso temporal de {{SITENAME}}',
'passwordremindertext'       => 'Belún (probablement busté, dende l\'adreza IP $1) demandó que li nimbiásenos una nueba parabra de paso ta la suya cuenta en  {{SITENAME}} ($4). 
A parabra de paso ta l\'usuario "$2" ye agora "$3".
Li consellamos que enzete agora una sesión y cambee a suya parabra de paso.

Si iste mensache fue demandato por otri, u si ya se\'n ha alcordato d\'a parabra de paso y ya no deseya cambiar-la, puede innorar iste mensache y continar fendo serbir l\'antiga parabra de paso.',
'noemail'                    => 'No bi ha garra adreza de correu electronico rechistrada ta "$1".',
'passwordsent'               => 'Una nueba parabra de paso plega de nimbiar-se ta o correu electronico de "$1". 
Por fabor, identifique-se unatra bez malas que la reculla.',
'blocked-mailpassword'       => "A suya adreza IP ye bloquiata y, ta pribar abusos, no se li premite emplegar d'a funzión de recuperazión de parabras de paso.",
'eauthentsent'               => "S'ha nimbiato un correu electronico de confirmazión ta l'adreza espezificata. Antes que no se nimbíe dengún atro correu ta ixa cuenta, has de confirmar que ixa adreza te pertenexe. Ta ixo, cal que sigas as instruzions que trobarás en o mensache.",
'throttled-mailpassword'     => "Ya s'ha nimbiato un correu recordatorio con a suya parabra de paso fa menos de $1 oras. Ta pribar abusos, sólo se nimbia un recordatorio cada $1 oras.",
'mailerror'                  => 'Error en nimbiar o correu: $1',
'acct_creation_throttle_hit' => 'Lo sentimos, ya ha creyato $1 cuentas. No puede creyar más cuentas.',
'emailauthenticated'         => 'A suya adreza electronica estió confirmata o $1.',
'emailnotauthenticated'      => "A suya adreza eleutronica <strong> no ye encara confirmata </strong>. No podrá recullir garra correu t'as siguients funzions.",
'noemailprefs'               => '<strong>Escriba una adreza electronica ta autibar istas carauteristicas.</strong>',
'emailconfirmlink'           => 'Confirme a suya adreza electronica',
'invalidemailaddress'        => "L'adreza electronica no puede estar azeutata pues tiene un formato incorreuto. Por favor, escriba una adreza bien formatiata, u dixe buedo ixe campo.",
'accountcreated'             => 'Cuenta creyata',
'accountcreatedtext'         => "S'ha creyato a cuenta d'usuario de $1.",
'createaccount-title'        => 'Creyar una cuenta en {{SITENAME}}',
'createaccount-text'         => 'Belún ($1) ha creyato una cuenta ta $2 en {{SITENAME}}
($4). A parabra de paso ta "$2" ye "$3". Debería dentrar-ie y cambiar a suya parabra de paso.

Si ista cuenta s\'ha creyato por error, simplament innore iste mensache.',
'loginlanguagelabel'         => 'Idioma: $1',

# Password reset dialog
'resetpass'               => "Restablir a parabra de paso d'a cuenta d'usuario",
'resetpass_announce'      => 'Has enzetato una sesión con una parabra de paso temporal que fue nimbiata por correu electronico. Por fabor, escribe aquí una nueba parabra de paso:',
'resetpass_text'          => '<!-- Adiba aquí o testo -->',
'resetpass_header'        => 'Restablir parabra de paso',
'resetpass_submit'        => 'Cambiar a parabra de paso e identificar-se',
'resetpass_success'       => 'A suya parabra de paso ya ye cambiata. Agora ya puede dentrar-ie...',
'resetpass_bad_temporary' => "Parabra de paso temporal incorreuta. Puede estar que ya aiga cambiato a suya parabra de paso u que aiga demadato o nimbío d'una atra.",
'resetpass_forbidden'     => 'En ista wiki no se pueden cambiar as parabras de paso',
'resetpass_missing'       => 'No ha escrito datos en o formulario.',

# Edit page toolbar
'bold_sample'     => 'Testo en negreta',
'bold_tip'        => 'Testo en negreta',
'italic_sample'   => 'Testo en cursiba',
'italic_tip'      => 'Testo en cursiba',
'link_sample'     => "Títol d'o binclo",
'link_tip'        => 'Binclo interno',
'extlink_sample'  => "http://www.exemplo.com Títol d'o binclo",
'extlink_tip'     => "Binclo esterno (alcuerde-se d'adibir o prefixo http://)",
'headline_sample' => 'Testo de tetular',
'headline_tip'    => 'Tetular de libel 2',
'math_sample'     => 'Escriba aquí a formula',
'math_tip'        => 'Formula matematica (LaTeX)',
'nowiki_sample'   => 'Escriba aquí testo sin de formato',
'nowiki_tip'      => 'Inorar o formato wiki',
'image_sample'    => 'Exemplo.jpg',
'image_tip'       => 'Imachen enserita',
'media_sample'    => 'Exemplo.ogg',
'media_tip'       => 'Binclo con un archibo multimedia',
'sig_tip'         => 'Siñadura, calendata y ora',
'hr_tip'          => 'Linia orizontal (en faiga un emplego amoderau)',

# Edit pages
'summary'                   => 'Resumen',
'subject'                   => 'Tema/títol',
'minoredit'                 => 'He feito una edizión menor',
'watchthis'                 => 'Bexilar ista pachina',
'savearticle'               => 'Alzar pachina',
'preview'                   => 'Bisualizazión prebia',
'showpreview'               => 'Bisualizazión prebia',
'showlivepreview'           => 'Ambiesta prebia rapeda',
'showdiff'                  => 'Mostrar cambeos',
'anoneditwarning'           => "''Pare cuenta:'' No s'ha identificato con un nombre d'usuario. A suya adreza IP s'alzará en o istorial d'a pachina.",
'missingsummary'            => "'''Pare cuenta:''' No ha escrito garra resumen d'edizión. Si fa clic nuebament en «{{MediaWiki:Savearticle}}» a suya edizión se grabará sin resumen.",
'missingcommenttext'        => 'Por fabor, escriba o testo astí baxo.',
'missingcommentheader'      => "'''Pare cuenta:''' No ha escrito garra títol ta iste comentario. Si puncha un atra bez en con a rateta en \"Alzar\", a suya edizión se grabará sin títol.",
'summary-preview'           => "Beyer ambiesta prebia d'o resumen",
'subject-preview'           => "Ambiesta prebia d'o tema/títol",
'blockedtitle'              => "L'usuario ye bloquiato",
'blockedtext'               => "<big>'''O suyo nombre d'usuario u adreza IP ha estato bloquiato.'''</big>

O bloqueyo fue feito por \$1. A razón data ye ''\$2''.

* Prenzipio d'o bloqueyo: \$8
* Fin d'o bloqueyo: \$6
* Indentificazión bloquiata: \$7

Puede contautar con \$1 u con atro [[{{MediaWiki:Grouppage-sysop}}|almenistrador]] ta letigar sobre o bloqueyo.

No puede fer serbir o binclo \"nimbiar correu electronico ta iste usuario\" si no ha rechistrato una adreza apropiata de correu electronico en as suyas [[Special:Preferences|preferenzias]]. A suya adreza IP autual ye \$3, y o identificador d'o bloqueyo ye #\$5. Por fabor encluiga belún u os dos datos cuan faga cualsiquier consulta.",
'autoblockedtext'           => "A suya adreza IP fue bloquiata automaticament porque l'eba feito serbir un atro usuario bloquiato por \$1.

A razón d'o bloqueyo ye ista:

:''\$2''


* Prenzipio d'o bloqueyo: \$8
* Fin d'o bloqueyo: \$6


Puede contautar con \$1 u con atro d'os [[{{MediaWiki:Grouppage-sysop}}|almenistradors]] ta litigar sobre o bloqueyo.

Pare cuenta que no puede emplegar a funzión \"Nimbiar correu electronico ta iste usuario\" si no tiene una adreza de correu electronico lechitima rechistrada en as suyas [[Special:Preferences|preferenzias d'usuario]] u si li ha estato biedata ista funzión.

O suyo identificador de bloqueyo ye \$5. Por fabor encluiga belún u os dos datos cuan faga cualsiquier consulta.",
'blockednoreason'           => "No s'ha dato garra causa",
'blockedoriginalsource'     => "Contino s'amuestra o codigo fuent de  '''$1''':",
'blockededitsource'         => "Contino s'amuestra o testo d'as suyas '''edizions''' á '''$1''':",
'whitelistedittitle'        => 'Cal enzetar una sesión ta ta fer edizions.',
'whitelistedittext'         => 'Ha de $1 ta poder editar pachinas.',
'whitelistreadtitle'        => "Cal que s'identifique y que enzete una sesión ta poder leyer",
'whitelistreadtext'         => 'Ha de [[Special:Userlogin|identificar-se]] ta leyer as pachinas.',
'whitelistacctitle'         => 'No tiene premiso ta creyar una cuenta',
'whitelistacctext'          => 'Ta que pueda creyar cuentas en iste wiki li cal [[Special:Userlogin|enzetar una sesión]] y tener os premisos apropiatos.',
'confirmedittitle'          => 'Cal que confirme a suya adreza electronica ta poder editar',
'confirmedittext'           => "Ha de confirmar a suya adreza electronica antis de poder editar pachinas. Por fabor, establa y confirme una adreza  electronica a trabiés d'as suyas [[Special:Preferences|preferenzias d'usuario]].",
'nosuchsectiontitle'        => 'No esiste ixa sezión',
'nosuchsectiontext'         => "Has prebato d'editar una sezión que no existe. Como no bi ha sezión $1, as suyas edizions no se pueden alzar en garra puesto.",
'loginreqtitle'             => 'Cal que enzete una sesión',
'loginreqlink'              => 'enzetar una sesión',
'loginreqpagetext'          => 'Ha de $1 ta beyer atras pachinas.',
'accmailtitle'              => 'A parabra de paso ha estato nimbiata.',
'accmailtext'               => "A parabra de paso de '$1' s'ha nimbiato á $2.",
'newarticle'                => '(Nuebo)',
'newarticletext'            => "Ha siguito un binclo á una pachina que encara no esiste.
Ta creyar a pachina, prenzipie á escribir en a caxa d'abaxo
(mire-se l'[[{{MediaWiki:Helppage}}|aduya]] ta más informazión).
Si bi ha plegau por error, punche o botón d'o suyo nabegador ta tornar entazaga.",
'anontalkpagetext'          => "---- ''Ista ye a pachina de descusión d'un usuario anonimo que encara no ha creyato una cuenta, u no l'ha feito serbir. Por ixo, hemos d'emplegar a suya adreza IP ta identificar-lo/a. Una adreza IP puede estar compartita entre diferens usuarios. Si busté ye un usuario anonimo y creye que s'han endrezato á busté con comentarios no relebants, [[Special:Userlogin|creye una cuenta u identifique-se]] ta pribar esdebenideras confusions con atros usuarios anonimos.''",
'noarticletext'             => 'Por agora no bi ha testo en ista pachina. Puede [[Special:Search/{{PAGENAME}}|mirar o títol]] en atras pachinas u [{{fullurl:{{FULLPAGENAME}}|action=edit}} prenzipiar á escribir en ista pachina].',
'userpage-userdoesnotexist' => 'A cuenta d\'usuario "$1" no ye rechistrada. Piense si quiere creyar u editar ista pachina.',
'clearyourcache'            => "'''Nota:''' Si quiere beyer os cambeos dimpués d'alzar l'archibo, puede estar que tienga que refrescar a caché d'o suyo nabegador ta beyer os cambeos:
*'''Mozilla:'''  ''ctrl-shift-r'',
*'''Internet Explorer:''' ''ctrl-f5'',
*'''Safari:''' ''cmd-shift-r'',
*'''Konqueror''' ''f5''.",
'usercssjsyoucanpreview'    => '<strong>Consello:</strong> Faga serbir o botón «Amostrar prebisualizazión» ta prebar o nuebo css/js antes de grabar-lo.',
'usercsspreview'            => "'''Remere que sólo ye prebisualizando o suyo css d'usuario y encara no ye grabato!'''",
'userjspreview'             => "'''Remere que sólo ye prebisualizando o suyo javascript d'usuario y encara no ye grabato!'''",
'userinvalidcssjstitle'     => "'''Pare cuenta:''' No bi ha garra aparenzia clamata \"\$1\". Remere que as pachinas presonalizatas .css y .js tienen un títol en minusclas, p.e. Usuario:Foo/monobook.css en cuenta de Usuario:Foo/Monobook.css.",
'updated'                   => '(Autualizato)',
'note'                      => '<strong>Nota:</strong>',
'previewnote'               => "<strong>Pare cuenta que isto sólo ye que l'ambiesta prebia d'a pachina; os cambeos encara no han estato alzatos!</strong>",
'previewconflict'           => "L'ambiesta prebia li amostrará l'aparenzia d'o testo dimpués d'alzar os cambeos.",
'session_fail_preview'      => "<strong>Ya lo sentimos, pero no podiemos alzar a suya edizión por una perda d'os datos de sesion. Por fabor, prebe de fer-lo una atra bez, y si encara no funziona, salga d'a sesión y torne á identificar-se.</strong>",
'session_fail_preview_html' => "<strong>Ya lo sentimos, pero no emos puesto prozesar a suya edizión porque os datos de sesión s'han acazegatos.</strong>

''Como iste wiki tiene l'HTML puro autibato, s'ha amagato l'ambiesta prebia ta aprebenir ataques en JavaScript.''

<strong>Si ista ye una prebatina lechitima d'edizión, por fabor, prebe una atra bez. Si encara no funzionase alabez, prebe-se de zarrar a sesión y i dentre identificando-se de nuebas.</strong>",
'token_suffix_mismatch'     => "<strong>S'ha refusato a suya edizión porque o suyo client ha esbarafundiato os caráuters de puntuazión en o editor. A edizión s'ha refusata ta pribar a corrompizión d'a pachina de testo. Isto gosa escaizer cuan se fa serbir un serbizio de proxy defeutuoso alazetato en a web.</strong>",
'editing'                   => 'Editando $1',
'editinguser'               => 'Editando $1',
'editingsection'            => 'Editando $1 (sezión)',
'editingcomment'            => 'Editando $1 (comentario)',
'editconflict'              => "Conflito d'edizión: $1",
'explainconflict'           => "Bel atro usuario ha cambiato ista pachina dende que bustet prenzipió á editar-la. O cuatrón de testo superior contiene o testo d'a pachina como ye autualment. Os suyos cambeos s'amuestran en o cuatrón de testo inferior. Abrá d'encorporar os suyos cambeos en o testo esistent. <b>Nomás</b> o testo en o cuatrón superior s'alzará cuan prete o botón \"Alzar a pachina\". <br />",
'yourtext'                  => 'O testo suyo',
'storedversion'             => 'Bersión almadazenata',
'nonunicodebrowser'         => "<strong>Pare cuenta: O suyo nabegador no cumple a norma Unicode. S'ha autibato un sistema d'edizión alternatibo que li premitirá d'editar articlos con seguridat: os caráuters no ASCII aparixerán en a caxa d'edizión como codigos exadezimals.</strong>",
'editingold'                => "<strong>PARE CUENTA: Ye editando una bersión antiga d'ista pachina. Si alza a pachina, toz os cambeos feitos dende ixa rebisión se tresbatirán.</strong>",
'yourdiff'                  => 'Esferenzias',
'copyrightwarning'          => "Por fabor, pare cuenta que todas as contrebuzions á {{SITENAME}} se consideran feitas publicas baxo a lizenzia $2 (beyer detalles en $1). Si no deseya que a chen corrixa os suyos escritos sin piedat y los destribuiga librement, alabez, no debería meter-los aquí. En publicar aquí, tamién ye declarando que busté mesmo escribió iste testo y ye dueño d'os dreitos d'autor, u bien lo copió dende o dominio publico u cualsiquier atra fuent libre.
<strong>NO COPIE SIN PREMISO ESCRITOS CON DREITOS D'AUTOR!</strong><br />",
'copyrightwarning2'         => "Por fabor, pare cuenta que todas as contrebuzions á {{SITENAME}} puede estar editatas, cambiatas u borratas por atros colaboradors. Si no deseya que a chen corrixa os suyos escritos sin piedat y los destribuiga librement, alabez, no debería meter-los aquí. <br /> En publicar aquí, tamién ye declarando que busté mesmo escribió iste testo y ye o dueño d'os dreitos d'autor, u bien lo copió dende o dominio publico u cualsiquier atra fuent libre (beyer $1 ta más informazión). <br />
<strong>NO COPIE SIN PREMISO ESCRITOS CON DREITOS D'AUTOR!</strong>",
'longpagewarning'           => '<strong>Pare cuenta: Ista pachina tiene ya $1 kilobytes; bels nabegadors pueden tener problemas en editar pachinas de 32KB o más.
Considere, por fabor, a posibilidat de troxar ista pachina en trestallos más chicoz.</strong>',
'longpageerror'             => '<strong>ERROR: O testo que ha escrito ye de $1 kilobytes, que ye mayor que a grandaria maisima de $2 kilobytes. No se puede alzar.</strong>',
'readonlywarning'           => '<strong>Pare cuenta: A base de datos ha estato bloquiata por custions de mantenimiento. Por ixo, en iste inte ye imposible alzar as suyas edizions. Puede copiar y apegar o testo en un archibo y alzar-lo ta dimpués.</strong>',
'protectedpagewarning'      => '<strong>ADVERTENCIA: Esta página ha sido protegida de manera que sólo usuarios con permisos de administrador pueden editarla.</strong>',
'semiprotectedpagewarning'  => "'''Nota:''' Ista página ha estato protexita ta que nomás usuarios rechistratos puedan editar-la.",
'cascadeprotectedwarning'   => "'''Pare cuenta:''' Ista pachina ye protexita ta que nomás os almenistrador puedan editar-la, porque ye encluyita en {{PLURAL:$1|a siguient pachina, protexita|as siguients pachinas, protexitas}} con a opzión de ''cascada'' :",
'templatesused'             => 'Plantillas emplegatas en ista pachina:',
'templatesusedpreview'      => 'Plantillas emplegatas en ista ambiesta prebia:',
'templatesusedsection'      => 'Plantillas usatas en ista sezión:',
'template-protected'        => '(protexito)',
'template-semiprotected'    => '(semiprotexito)',
'edittools'                 => "<!-- Iste testo amanixerá baxo os formularios d'edizión y carga. -->",
'nocreatetitle'             => "S'ha restrinxito a creyazión de pachinas",
'nocreatetext'              => 'Iste wiki ha limitato a creyazión de nuebas pachinas. Puede tornar entazaga y editar una pachina ya esistent, [[Special:Userlogin|identificarse u creyar una cuenta]].',
'nocreate-loggedin'         => 'No tiene premisos ta creyar nuebas pachinas en ista wiki.',
'permissionserrorstext'     => 'No tiene premisos ta fer-lo, por {{PLURAL:$1|ista razón|istas razons}}:',
'recreate-deleted-warn'     => "'''Pare cuenta: ye creyando una pachina que ya ha estato borrata denantes.'''

Abría de considerar si ye reyalment nezesario continar editando ista pachina.
Puede consultar o rechistro de borraus que s'amuestra a continuazión:",

# "Undo" feature
'undo-success' => "A edizión puede esfer-se. Antis d'esfer a edizión, mire-se a siguient comparanza ta comprebar que ye ixo o que quiere fer reyalment. Alabez, puede alzar os cambeos ta esfer a edizión.",
'undo-failure' => 'No se puede esfer a edizión pues un atro usuario ha feito una edizión intermeya.',
'undo-summary' => 'Esfeita la edizión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|desc.]])',

# Account creation failure
'cantcreateaccounttitle' => 'No se puede creyar a cuenta',
'cantcreateaccount-text' => "A creyazión de cuentas dende ixa adreza IP (<b>$1</b>) estió bloquiata por [[User:$3|$3]].

A razón endicata por $3 ye ''$2''",

# History pages
'revhistory'          => 'Istorial de bersions',
'viewpagelogs'        => "Beyer os rechistros d'ista pachina",
'nohistory'           => "Ista pachina no tiene un istorial d'edizions.",
'revnotfound'         => 'Bersión no trobata',
'revnotfoundtext'     => "No se pudo trobar a bersión antiga d'a pachina demandata.
Por fabor, rebise l'adreza que fazió serbir t'aczeder á ista pachina.",
'currentrev'          => 'Bersión autual',
'revisionasof'        => "Bersión d'o $1",
'revision-info'       => "Bersión d'o $1 feita por $2",
'previousrevision'    => '← Bersión anterior',
'nextrevision'        => 'Bersión siguient →',
'currentrevisionlink' => 'Beyer bersión autual',
'cur'                 => 'aut',
'next'                => 'siguient',
'last'                => 'zag',
'page_first'          => 'primeras',
'page_last'           => 'zagueras',
'histlegend'          => 'Leyenda: (aut) = esferenzias con a bersión autual,
(ant) = diferenzias con a bersión anterior, m = edizión menor',
'histfirst'           => 'Primeras contrebuzions',
'histlast'            => 'Zagueras',

# Revision feed
'history-feed-item-nocomment' => '$1 en $2', # user at time

# Diffs
'history-title'           => 'Istorial de bersions de "$1"',
'difference'              => '(Esferenzias entre bersions)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'Confrontar as bersions trigatas',
'editundo'                => 'esfer',
'diff-multi'              => "(S'ha amagato {{plural:$1|una edizión entremeya|$1 edizions entremeyas}}.)",

# Search results
'searchresults' => 'Resultaus de mirar',
'noexactmatch'  => "'''No esiste garra pachina tetulata \"\$1\".''' Puede aduyar [[:\$1|creyando-la]].",
'prevn'         => 'anteriors $1',
'nextn'         => 'siguiens $1',
'viewprevnext'  => 'Beyer ($1) ($2) ($3)',
'powersearch'   => 'Mirar-lo',

# Preferences page
'preferences'       => 'Preferenzias',
'mypreferences'     => 'As mías preferenzias',
'prefsnologintext'  => "Has d'estar [[{{ns:special}}:Userlogin|rechistrau]] y aber enzetau una sesión ta cambear as preferenzias d'usuario.",
'prefs-rc'          => 'Zaguers cambeos',
'prefs-watchlist'   => 'Lista de seguimiento',
'saveprefs'         => 'Alzar preferenzias',
'retypenew'         => 'Torne á escribir a nueba parabra de paso:',
'searchresultshead' => 'Mirar',

'grouppage-sysop' => '{{ns:project}}:Almenistradors',

# User rights log
'rightslog' => "Rechistro de premisos d'os usuarios",

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|cambeo|cambeos}}',
'recentchanges'                  => 'Zaguers cambeos',
'recentchanges-feed-description' => "Seguir en ista canal de notizias os cambeos más rezients d'o wiki.",
'rcnote'                         => "Más t'abaxo {{PLURAL:$1|s'amuestra o unico cambeo feito|s'amuestran os '''$1''' zaguers cambeos feitos}} en {{PLURAL:$2|o zaguer día|os zaguers '''$2''' días}}, {{PLURAL:$1|autualizato|autualizatos}} a $3.",
'rcnotefrom'                     => "Contino s'amuestran os cambeos dende <b>$2</b> (dica <b>$1</b>).",
'rclistfrom'                     => 'Mostrar nuebos cambeos dende $1',
'rcshowhideminor'                => '$1 edizions menors',
'rcshowhideliu'                  => '$1 usuarios rechistraus',
'rcshowhideanons'                => '$1 usuarios anonimos',
'rcshowhidepatr'                 => '$1 edizions controlatas',
'rcshowhidemine'                 => '$1 as mías edizions',
'rclinks'                        => 'Amostrar os zaguers $1 cambeos en os zaguers $2 días.<br />$3',
'diff'                           => 'esf',
'hist'                           => 'ist',
'hide'                           => 'amagar',
'show'                           => 'Mostrar',

# Recent changes linked
'recentchangeslinked'          => 'Cambeos en pachinas relazionadas',
'recentchangeslinked-title'    => 'Cambeos relazionatos con $1',
'recentchangeslinked-noresult' => 'No bi abió cambeos en as pachinas binculatas en o entrebalo de tiempo endicato.',
'recentchangeslinked-summary'  => "Ista pachina espezial amuestra os zaguers cambeos en as pachinas binculatas. As pachinas d'a suya lista de seguimiento son en  '''negreta'''.",

# Upload
'upload'                    => 'Cargar archibo',
'uploadbtn'                 => 'Cargar un archibo',
'uploadnologintext'         => "Has d'estar [[{{ns:special}}:Userlogin|rechistrau]] ta cargar archibos.",
'uploadlogpage'             => "Rechistro de cargas d'archibos",
'uploadedimage'             => '«[[$1]]» cargato.',
'filename-prefix-blacklist' => ' #<!-- dixe ista linia esautament igual como ye --> <pre>
# A sintacsis ye asinas: 
#   * Tot o que prenzipia por un caráuter "#" dica la fin d\'a linia ye un comentario
#   * As atras linias tienen os prefixos que claban automaticament as camaras dichitals
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # bels telefonos móbils
IMG # chenerica
JD # Jenoptik
MGP # Pentax
PICT # misz.
 #</pre> <!-- dixe ista linia esautament igual como ye -->',

# Image list
'imagelist'                 => 'Lista de imachens',
'ilsubmit'                  => 'Uscar',
'filehist'                  => "Istorial de l'archibo",
'filehist-help'             => "Punche en una calendata/ora ta beyer l'archibo como amanixeba por ixas engüeltas.",
'filehist-current'          => 'autual',
'filehist-datetime'         => 'Calendata/Ora',
'filehist-user'             => 'Usuario',
'filehist-filesize'         => "Grandaria d'o fichero",
'filehist-comment'          => 'Comentario',
'imagelinks'                => 'Binclos ta la imachen',
'linkstoimage'              => 'Istas son as pachinas que tienen binclos con ista imachen:',
'nolinkstoimage'            => 'Denguna pachina tiene un binclo con ista imachen.',
'sharedupload'              => 'Iste archibo ye compartito y puede estar que siga emplegato en atros procheutos.',
'noimage'                   => 'No bi ha garra archibo con ixe nombre, pero puede $1.',
'noimage-linktext'          => 'cargar-lo',
'uploadnewversion-linktext' => "Cargar una nueba bersión d'ista archibo",

# MIME search
'mimesearch' => 'Mirar por tipo MIME',

# Unwatched pages
'unwatchedpages' => 'Pachinas sin bexilar',

# List redirects
'listredirects' => 'Lista de reendrezeras',

# Unused templates
'unusedtemplates' => 'Plantillas sin de uso',

# Random page
'randompage' => "Una pachina á l'azar",

# Random redirect
'randomredirect' => 'Ir-ie á una adreza cualsiquiera',

# Statistics
'statistics' => 'Estadisticas',
'sitestats'  => "Estadisticas d'a {{SITENAME}}",
'userstats'  => "Estadisticas d'usuario",

'disambiguations' => 'Pachinas de desambigazión',

'doubleredirects' => 'Reendrezeras dobles',

'brokenredirects' => 'Reendrezeras crebatas',

'withoutinterwiki' => "Pachinas sin d'interwikis",

'fewestrevisions' => 'Articlos con menos edizions',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'                  => '$1 {{PLURAL:$1|binclo|binclos}}',
'nmembers'                => '$1 {{PLURAL:$1|miembro|miembros}}',
'lonelypages'             => 'Pachinas popiellas',
'uncategorizedpages'      => 'Pachinas sin categorizar',
'uncategorizedcategories' => 'Categorías sin categorizar',
'uncategorizedimages'     => 'Imachens sin categorizar',
'uncategorizedtemplates'  => 'Plantillas sin categorizar',
'unusedcategories'        => 'Categorías sin emplegar',
'unusedimages'            => 'Imachens sin uso',
'wantedcategories'        => 'Categorías requiestas',
'wantedpages'             => 'Pachinas requiestas',
'mostlinked'              => 'Pachinas más enlazadas',
'mostlinkedcategories'    => 'Categorías más enlazadas',
'mostlinkedtemplates'     => 'Plantillas más binculatas',
'mostcategories'          => 'Pachinas con más categorías',
'mostimages'              => 'Imachens más emplegatas',
'mostrevisions'           => 'Pachinas con más edizions',
'allpages'                => 'Todas as pachinas',
'prefixindex'             => 'Pachinas por prefixo',
'shortpages'              => 'Pachinas más curtas',
'longpages'               => 'Pachinas más largas',
'deadendpages'            => 'Pachinas sin salida',
'protectedpages'          => 'Pachinas protexitas',
'listusers'               => "Lista d'usuarios",
'specialpages'            => 'Pachinas espezials',
'restrictedpheading'      => 'Pachinas espezials restrinxitas',
'rclsub'                  => '(enta pachinas enlazadas dende "$1")',
'newpages'                => 'Pachinas nuebas',
'ancientpages'            => 'Pachinas más biellas',
'move'                    => 'Tresladar',
'movethispage'            => 'Tresladar ista pachina',

# Book sources
'booksources' => 'Fuents de libros',

'alphaindexline' => '$1 á $2',
'version'        => 'Bersión',

# Special:Log
'specialloguserlabel'  => 'Usuario:',
'speciallogtitlelabel' => 'Títol:',
'log'                  => 'Rechistros',
'all-logs-page'        => 'Toz os rechistros',
'log-search-submit'    => 'Ir-ie',

# Special:Allpages
'nextpage'       => 'Siguient pachina ($1)',
'prevpage'       => 'Pachina anterior ($1)',
'allpagesfrom'   => 'Amostrar pachinas que prenzipien por:',
'allarticles'    => 'Toz os articlos',
'allinnamespace' => 'Todas as pachinas (espazio $1)',
'allpagessubmit' => 'Amostrar',
'allpagesprefix' => 'Amostrar pachinas con o prefixo:',

# E-mail user
'emailuser' => 'Nimbiar un correu electronico ta iste usuario',

# Watchlist
'watchlist'            => 'Lista de seguimiento',
'mywatchlist'          => 'A mía lista de seguimiento',
'watchlistfor'         => "(ta '''$1''')",
'nowatchlist'          => 'No tiens denguna pachina en a lista de seguimiento.',
'addedwatch'           => 'Adibiu á la suya lista de seguimiento',
'watch'                => 'Bexilar',
'watchthispage'        => 'Bexilar ista pachina',
'unwatch'              => 'Dixar de bexilar',
'unwatchthispage'      => 'Dixar de bexilar',
'watchlist-details'    => '{{PLURAL:$1|$1 pachina|$1 pachinas}} bexiladas (sin contar-ie as pachinas de descusión).',
'wlshowlast'           => 'Amostrar as zagueras $1 horas, $2 días u $3',
'watchlist-hide-bots'  => 'Amagar as edizions de bots',
'watchlist-hide-own'   => 'Amagar as mías edizions',
'watchlist-hide-minor' => 'Amagar edizions menors',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Bexilando...',
'unwatching' => 'Deixar de bexilar...',

# Delete/protect/revert
'historywarning' => 'Pare cuenta: A pachina que ba a borrar tiene un istorial de cambeos:',
'actioncomplete' => 'Aizión rematada',
'deletedarticle' => 'borrato "$1"',
'dellogpage'     => 'Rechistro de borraus',
'rollbacklink'   => 'Esfer',
'protectlogpage' => 'Protezions de pachinas',
'protectcomment' => 'Razón ta protexer:',

# Undelete
'undeletepagetext' => "As pachinas siguiens han siu borradas, pero encara son en l'archibo y podría estar restauradas. El archibo se borra periodicamén.",
'undeletebtn'      => 'Restaurar!',

# Namespace form on various pages
'namespace'      => 'Espazio de nombres:',
'invert'         => 'Contornar selezión',
'blanknamespace' => '(Prenzipal)',

# Contributions
'contributions' => "Contrebuzions de l'usuario",
'mycontris'     => 'As mías contrebuzions',
'contribsub2'   => 'De $1 ($2)',
'uctop'         => ' (zaguer cambeo)',
'month'         => 'Dende o mes (y anteriors):',
'year'          => "Dende l'año (y anteriors):",

'sp-contributions-newest'      => 'Zagueras',
'sp-contributions-oldest'      => 'Primeras',
'sp-contributions-newer'       => '← $1 posteriors

== Testo de tetular ==',
'sp-contributions-older'       => '$1 anteriors →',
'sp-contributions-newbies'     => "Amostrar nomás as contrebuzions d'os usuarios nuebos",
'sp-contributions-newbies-sub' => 'Por usuarios nuebos',
'sp-contributions-blocklog'    => 'Rechistro de bloqueyos',
'sp-contributions-search'      => 'Mirar contrebuzions',
'sp-contributions-username'    => "Adreza IP u nombre d'usuario:",
'sp-contributions-submit'      => 'Mirar',

# What links here
'whatlinkshere'       => 'Pachinas que enlazan con ista',
'whatlinkshere-title' => 'Pachinas que tienen binclos con $1',
'linklistsub'         => '(Lista de binclos)',
'linkshere'           => "As siguients pachinas tienen binclos enta '''[[:$1]]''':",
'nolinkshere'         => "Denguna pachina tiene binclos ta '''[[:$1]]'''.",
'isredirect'          => 'pachina reendrezata',
'istemplate'          => 'enclusión',
'whatlinkshere-prev'  => '{{PLURAL:$1|anterior|anteriors $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|siguient|siguients $1}}',
'whatlinkshere-links' => '← binclos',

# Block/unblock
'blockip'       => 'Bloquiar usuario',
'ipboptions'    => '2 oras:2 hours,1 día:1 day,3 días:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 año:1 year,ta cutio:infinite',
'ipblocklist'   => "Lista d'as adrezas IP bloquiatas",
'blocklink'     => 'bloquiar',
'unblocklink'   => 'esbloquiar',
'contribslink'  => 'contrebuzions',
'blocklogpage'  => 'Rechistro de bloqueyos',
'blocklogentry' => "S'ha bloquiato á [[$1]] con una durada de $2 $3",

# Move page
'movearticle'     => 'Tresladar pachina:',
'movepagebtn'     => 'Tresladar pachina',
'movepage-moved'  => '<big>\'\'\'"$1" ha estato tresladato á "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'1movedto2'       => '[[$1]] tresladada á [[$2]]',
'1movedto2_redir' => '[[$1]] tresladada á [[$2]] sobre una reendrezera',
'movelogpage'     => 'Rechistro de treslatos',
'revertmove'      => 'esfer',

# Export
'export' => 'Esportar as pachinas',

# Namespace 8 related
'allmessages'        => "Toz os mensaches d'o sistema",
'allmessagesname'    => 'Nombre',
'allmessagesdefault' => 'Testo por defeuto',
'allmessagescurrent' => 'Testo autual',
'allmessagestext'    => 'Ista ye una lista de toz os mensaches disposables en o espazio de nombres MediaWiki.',

# Thumbnails
'thumbnail-more'  => 'Fer más gran',
'thumbnail_error' => "S'ha produzito una error en creyar a miniatura: $1",

# Import log
'importlogpage' => "Rechistro d'importazions",

# Tooltip help for the actions
'tooltip-pt-userpage'             => "A mía pachina d'usuario",
'tooltip-pt-mytalk'               => 'A mía pachina de descusión',
'tooltip-pt-preferences'          => 'As mías preferenzias',
'tooltip-pt-watchlist'            => 'A lista de pachinas en que ha estato bexilando os cambeos',
'tooltip-pt-mycontris'            => "Lista d'as mías contribuzions",
'tooltip-pt-login'                => 'Li recomendamos rechistrar-se, encara que no ye obligatorio',
'tooltip-pt-logout'               => 'Rematar a sesión',
'tooltip-ca-talk'                 => "Descusión sobre l'articlo",
'tooltip-ca-edit'                 => 'Puede editar ista pachina. Por fabor, faga serbir o botón de bisualizazión prebia antes de grabar.',
'tooltip-ca-addsection'           => 'Adibir un comentario ta ista descusión',
'tooltip-ca-viewsource'           => 'Ista pachina ye protexita, nomás puede beyer o codigo fuent',
'tooltip-ca-protect'              => 'Protexer ista pachina',
'tooltip-ca-delete'               => 'Borrar ista pachina',
'tooltip-ca-move'                 => 'Tresladar (renombrar) ista pachina',
'tooltip-ca-watch'                => 'Adibir ista pachina á la suya lista de seguimiento',
'tooltip-ca-unwatch'              => "Borrar ista pachina d'a suya lista de seguimiento",
'tooltip-search'                  => 'Mirar en {{SITENAME}}',
'tooltip-n-mainpage'              => 'Besitar a Portalada',
'tooltip-n-portal'                => 'Sobre o procheuto, que puede fer, án trobar as cosas',
'tooltip-n-currentevents'         => 'Trobar informazión cheneral sobre escaizimientos autuals',
'tooltip-n-recentchanges'         => "A lista d'os zaguers cambeos en o wiki",
'tooltip-n-randompage'            => 'Cargar una pachina aleatoriament',
'tooltip-n-help'                  => 'O puesto ta saber más.',
'tooltip-n-sitesupport'           => 'Refirme o procheuto',
'tooltip-t-whatlinkshere'         => "Lista de todas as pachinas d'o wiki binculatas con ista",
'tooltip-t-contributions'         => "Beyer a lista de contrebuzions d'iste usuario",
'tooltip-t-emailuser'             => 'Nimbiar un correu electronico ta iste usuario',
'tooltip-t-upload'                => 'Puyar imáchens u archibos multimedia ta o serbidor',
'tooltip-t-specialpages'          => 'Lista de todas as pachinas espezials',
'tooltip-ca-nstab-user'           => "Beyer a pachina d'usuario",
'tooltip-ca-nstab-project'        => "Beyer a pachina d'o procheuto",
'tooltip-ca-nstab-image'          => "Beyer a pachina d'a imachen",
'tooltip-ca-nstab-template'       => 'Beyer a plantilla',
'tooltip-ca-nstab-help'           => "Beyer a pachina d'aduya",
'tooltip-ca-nstab-category'       => "Beyer a pachina d'a categoría",
'tooltip-minoredit'               => 'Siñalar ista edizión como cambeo menor',
'tooltip-save'                    => 'Alzar os cambeos',
'tooltip-preview'                 => 'Rebise os suyos cambeos, por fabor, faga serbir isto antes de grabar!',
'tooltip-diff'                    => 'Amuestra os cambeos que ha feito en o testo.',
'tooltip-compareselectedversions' => "Beyer as esferenzias entre as dos bersions trigatas d'ista pachina.",
'tooltip-watch'                   => 'Adibir ista pachina á la suya lista de seguimiento',

# Attribution
'and'    => 'y',
'others' => 'atros',

# Spam protection
'subcategorycount'     => 'Bi ha {{PLURAL:$1|una subcategoría|$1 subcategorías}} en ista categoría.',
'categoryarticlecount' => 'Bi ha $1 {{PLURAL:$1|articlo|articlos}} en ista categoría.',
'category-media-count' => 'Bi ha {{PLURAL:$1|&nbsp;un archibo|$1 archibos}} en ista categoría.',

# Browsing diffs
'previousdiff' => '← Ir ta esferenzias anteriors',
'nextdiff'     => "Ir t'as siguients esferenzias →",

# Media information
'file-info-size'       => "($1 × $2 píxels; grandaria de l'archivo: $3; tipo MIME: $4)",
'file-nohires'         => '<small>No bi ha garra bersión con mayor resoluzión.</small>',
'svg-long-desc'        => '(archibo SVG, nominalment $1 × $2 píxels, grandaria: $3)',
'show-big-image'       => 'Imachen en a maisima resoluzión',
'show-big-image-thumb' => "<small>Grandaria d'ista ambiesta prebia: $1 × $2 píxels</small>",

# Special:Newimages
'newimages' => 'Galería de nuebas imachens',

# Bad image list
'bad_image_list' => "O formato ye asinas:

Se consideran nomás os elementos d'una lista (linias que escomienzan por *). O primer binclo de cada linia ha d'estar un binclo ta una imachen mala. Cualsiquiers atros binclos en a mesma linia se consideran eszepzions, i.e. pachinas an que a imachen puede amanexer enserita en a línia.",

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => "Iste archibo contiene informazión adizional (metadatos), probablement adibida por a camara dichital, o escáner u o programa emplegato ta creyar-lo u dichitalizar-lo.  Si l'archibo ha estato modificato dende o suyo estau orichinal, bels detalles podrían aber-se tresbatitos.",
'metadata-expand'   => 'Amostrar informazión detallata',
'metadata-collapse' => 'Amagar a informazión detallata',
'metadata-fields'   => "Os campos de metadatos EXIF que amanixen en iste mensache s'amuestrarán en a pachina de descripzión d'a imachen, mesmo si a tabla ye plegata. Bi ha atros campos que remanirán amagatos por defeuto.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength",

# EXIF tags
'exif-gpstimestamp' => 'Tiempo GPS (reloch atomico)',

# External editor support
'edit-externally'      => 'Editar iste archibo fendo serbir una aplicazión esterna',
'edit-externally-help' => 'Leiga as [http://meta.wikimedia.org/wiki/Help:External_editors instruzions de confegurazión] (en anglés) ta más informazión.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'toz',
'namespacesall' => 'todo',
'monthsall'     => '(toz)',

# Watchlist editing tools
'watchlisttools-view' => 'Amostrar cambeos',
'watchlisttools-edit' => 'Beyer y editar a lista de seguimiento',
'watchlisttools-raw'  => 'Editar a lista de seguimiento en formato testo',

);
