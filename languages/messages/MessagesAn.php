<?php
/** Aragonese (Aragonés)
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 * @author Juanpabl
 * @author G - ג
 * @author Willtron
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
'tog-highlightbroken'         => 'Formatiar os binclos trencaus <a href="" class="new"> d\'ista traza </a> (y si no, asinas <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Achustar parrafos',
'tog-hideminor'               => 'Amagar edizions menors en a pachina de "zaguers cambeos"',
'tog-extendwatchlist'         => 'Enamplar a lista de seguimiento ta amostrar toz os cambeos afeutatos.',
'tog-usenewrc'                => 'Presentazión amillorada de "zaguers cambeos" (cal JavaScript)',
'tog-numberheadings'          => 'Numerar automaticament os encabezaus',
'tog-showtoolbar'             => "Amostrar a barra d'ainas d'edizión (cal JavaScript)",
'tog-editondblclick'          => 'Autibar edizión de pachinas fendo-ie doble click (cal JavaScript)',
'tog-editsection'             => 'Autibar a edizión por seczions usando binclos [editar]',
'tog-editsectiononrightclick' => "Autibar a edizión de sezions con o botón dreito d'o ratet <br /> en os titols de seczions (cal JavaScript)",
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

'skinpreview' => '(Prebar cómo queda)',

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
'copyrightpage'     => 'Project:Copyrights',
'currentevents'     => 'Autualidat',
'currentevents-url' => 'Autualidat',
'disclaimers'       => 'Abiso legal',
'disclaimerpage'    => 'Project:Abiso legal',
'edithelp'          => 'Aduya ta editar pachinas',
'edithelppage'      => 'Help:Cómo se fa ta editar una pachina',
'faq'               => 'Preguntas cutianas',
'faqpage'           => 'Project:Preguntas cutianas',
'helppage'          => 'Project:Aduya',
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
'feed-invalid'            => 'Tipo imbalido de sendicazión como fuent de notizias.',

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
'nospecialpagetext' => "'''<big> A pachina espezial que ha demandato no esiste.</big>'''

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
'enterlockreason'      => "Esplique o motibo d'o bloqueyo, encluyendo una estimazión de cuán se produzirá o desbloqueyo",
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
'perfdisabled'         => "S'ha desautibato temporalment ista opzión porque fa lenta a base de datos de traza que denguno no puede usar o wiki.",
'perfcached'           => 'Os datos que siguen son en caché, y podrían no estar esbiellatos:',
'perfcachedts'         => 'Istos datos se troban en a caché, que estió esbiellata por zaguer begada o $1.',
'querypage-no-updates' => "S'han desautibato as autualizazions d'ista pachina. Por ixo, no s'esta esbiellando os datos.",
'wrong_wfQuery_params' => 'Parametros incorreutos ta wfQuery()<br />
Funzión: $1<br />
Consulta: $2',
'viewsource'           => 'Beyer codigo fuen',
'viewsourcefor'        => 'ta $1',
'protectedpagetext'    => 'Ista pachina ha estato bloquiata y no se puede editar.',
'viewsourcetext'       => "Puez beyer y copiar o codigo fuent d'ista pachina:",
'protectedinterface'   => "Ista pachina furne o testo d'a interfaz ta o software. Ye protexita ta pribar o bandalismo. Si creye que bi ha bella error, contaute con un [[{{MediaWiki:grouppage-sysop}}|Almenistrador]].",
'editinginterface'     => "'''Pare cuenta:''' Ye editando una pachina emplegata ta furnir o testo d'a interfaz de {{SITENAME}}. Os cambeos en ista pachina tendrán efeuto en l'aparenzia d'a interfaz ta os atros usuarios.",
'sqlhidden'            => '(Consulta SQL amagata)',
'cascadeprotected'     => 'Ista pachina ye protexita y no se puede editar porque ye encluyita en {{PLURAL:$1|a siguient pachina|as siguients pachinas}}, que son protexitas con a opzión de "cascada": $2',
'namespaceprotected'   => "No tiens premiso ta editar as pachinas d'o espazio de nombres '''$1'''.",
'customcssjsprotected' => "No tiens premiso ta editar ista pachina porque contiene para editar esta página porque contiene a confegurazión presonal d'atro usuario.",
'ns-specialprotected'  => "No ye posible editar as pachinas d'o espazio de nombres {{ns:special}}.",

# Login and logout pages
'logouttitle'        => 'Fin de sesión',
'logouttext'         => "Ha rematato a suya sesión.
Puede continar nabegando por {{SITENAME}} anonimament, u puede enzetar unatra sesión atra begada con o mesmo u atro usuario. Pare cuenta que bellas pachinas se pueden amostrar encara como si continase en a sesión anterior, dica que se limpie a caché d'o nabegador.",
'welcomecreation'    => "== ¡Bienbeniu(da), $1! ==

S'ha creyato a suya cuenta. No xublide presonalizar as [[Special:Preferences|preferenzias]].",
'loginpagetitle'     => 'Enzetar sesión',
'yourname'           => "Nombre d'usuario:",
'yourpassword'       => 'Parabra de paso:',
'yourpasswordagain'  => 'Torne á escribir a parabra de paso:',
'remembermypassword' => "Remerar datos d'usuario entre sesions.",
'yourdomainname'     => 'Su dominio:',
'externaldberror'    => "Bi abió una error autenticazión esterna d'a base de datos u bien no tiene premisos ta autualizar a suya cuenta esterna.",
'loginproblem'       => '<b>Escaizió un problema con a suya autenticazión.</b><br />¡Prebe unatra begada!',
'login'              => 'Enzetar sesión',
'loginprompt'        => 'Ha de autibar as <i>cookies</i> en o nabegador ta rechistrar-se en {{SITENAME}}.',
'userlogin'          => 'Enzetar sesión / creyar cuenta',
'userlogout'         => 'Salir',
'notloggedin'        => 'No ha enzetato sesión',
'nologin'            => 'No tiene cuenta? $1.',
'nologinlink'        => 'Creyar una nueba cuenta',
'createaccount'      => 'Creyar una nueba cuenta',
'gotaccount'         => 'Tiene ya una cuenta? $1.',
'username'           => "Nombre d'usuario:",
'yourrealname'       => 'O tuyo nombre reyal:',
'yourlanguage'       => 'Lenguache:',
'yournick'           => 'A tuya embotada (ta siñar):',
'prefs-help-email'   => "Correu-e (ozional): Premite á atros usuarios contautar con tu por meyo de a tuya pachina d'usuario u a tuya pachina de descusión sin de aber menester de rebelar a tuya identidá.",
'noname'             => "No has introduziu un nombre d'usuario correuto.",

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
'nowiki_tip'      => 'Innorar o formato wiki',
'image_sample'    => 'Exemplo.jpg',
'image_tip'       => 'Imachen enserita',
'media_sample'    => 'Exemplo.ogg',
'media_tip'       => 'Binclo con un archibo multimedia',
'sig_tip'         => 'Siñadura, calendata y ora',
'hr_tip'          => 'Línia orizontal (en faiga un emplego amoderau)',

# Edit pages
'summary'                  => 'Resumen',
'subject'                  => 'Tema/títol',
'minoredit'                => 'He feito una edizión menor',
'watchthis'                => 'Bexilar ista pachina',
'savearticle'              => 'Alzar pachina',
'preview'                  => 'Bisualizazión prebia',
'showpreview'              => 'Bisualizazión prebia',
'showdiff'                 => 'Mostrar cambeos',
'anoneditwarning'          => "''Pare cuenta:'' No s'ha identificato con un nombre d'usuario. A suya adreza IP s'alzará en o istorial d'a pachina.",
'newarticle'               => '(Nuebo)',
'newarticletext'           => "Has siguito un binclo á una pachina que encara no esiste.
Ta creyar a pachina, prenzipia á escribir en a caxa d'abaxo
(se beiga l'[[{{MediaWiki:helppage}}|aduya]] ta más informazión).
Si bi has plegau por error, puncha o botón d'o tuyo nabegador ta ir entazaga.",
'noarticletext'            => 'Por agora no bi ha testo en ista pachina. Puede [[Special:Search/{{PAGENAME}}|mirar o títol]] en atras pachinas u [{{fullurl:{{FULLPAGENAME}}|action=edit}} prenzipiar á escribir en ista pachina].',
'editing'                  => 'Editando $1',
'editingsection'           => 'Editando $1 (sezión)',
'copyrightwarning'         => "Por fabor, pare cuenta que todas as contrebuzions á {{SITENAME}} se consideran feitas publicas baxo a lizenzia $2 (beyer detalles en $1). Si no deseya que a chen corrixa os suyos escritos sin piedat y los destribuiga librement, alabez, no debería meter-los aquí. En publicar aquí, tamién ye declarando que busté mesmo escribió iste testo y ye dueño d'os dreitos d'autor, u bien lo copió dende o dominio publico u cualsiquier atra fuent libre.
<strong>NO COPIE SIN PREMISO ESCRITOS CON DREITOS D'AUTOR!</strong><br />",
'semiprotectedpagewarning' => "'''Nota:''' Ista página ha estato protexita ta que nomás usuarios rechistratos puedan editar-la.",
'templatesused'            => 'Plantillas emplegatas en ista pachina:',
'template-protected'       => '(protexito)',
'template-semiprotected'   => '(semiprotexito)',
'nocreatetext'             => 'Iste wiki ha limitato a creyazión de nuebas pachinas. Puede tornar entazaga y editar una pachina ya esistent, [[Special:Userlogin|identificarse u creyar una cuenta]].',

# "Undo" feature
'undo-summary' => 'Esfeita la edizión $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|desc.]])',

# History pages
'revhistory'          => 'Istorial de bersions',
'viewpagelogs'        => "Beyer os rechistros d'ista pachina",
'nohistory'           => "Ista pachina no tiene un istorial d'edizions.",
'currentrev'          => 'Bersión autual',
'revisionasof'        => "Bersión d'o $1",
'revision-info'       => "Bersión d'o $1 feita por $2",
'previousrevision'    => '← Bersión anterior',
'nextrevision'        => 'Bersión siguient →',
'currentrevisionlink' => 'Beyer bersión autual',
'cur'                 => 'aut',
'next'                => 'siguién',
'last'                => 'zag',
'histlegend'          => 'Leyenda: (aut) = esferenzias con a bersión autual,
(ant) = diferenzias con a bersión anterior, m = edizión menor',
'histlast'            => 'Zagueras',

# Diffs
'history-title'           => 'Istorial de bersions de "$1"',
'difference'              => '(Esferenzias entre bersions)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'Confrontar as bersions trigatas',
'editundo'                => 'esfer',

# Search results
'searchresults' => "Resultaus d'a rechira",
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
'retypenew'         => 'Torna á escribir a tuya nueba parabra de paso:',
'searchresultshead' => "Confegurar resultaus d'a rechira",

# Recent changes
'recentchanges'                  => 'Zaguers cambeos',
'recentchanges-feed-description' => "Seguir en ista canal de notizias os cambeos más rezients d'o wiki.",
'rcnote'                         => "Más t'abaxo bi son os zaguers <b>$1</b> cambeos en os zaguers <b>$2</b> días, autualizatos o $3",
'rclistfrom'                     => 'Mostrar nuebos cambeos dende $1',
'rcshowhideminor'                => '$1 edizions menors',
'rcshowhideliu'                  => '$1 usuarios rechistraus',
'rcshowhideanons'                => '$1 usuarios anonimos',
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
'upload'            => 'Cargar archibo',
'uploadnologintext' => "Has d'estar [[{{ns:special}}:Userlogin|rechistrau]] ta cargar archibos.",

# Image list
'ilsubmit'          => 'Uscar',
'filehist'          => "Istorial d'o archibo",
'filehist-help'     => "Punche en una calendata/ora ta beyer l'archibo como amanixeba por ixas engüeltas.",
'filehist-current'  => 'autual',
'filehist-datetime' => 'Calendata/Ora',
'filehist-user'     => 'Usuario',
'filehist-filesize' => "Grandaria d'o fichero",
'filehist-comment'  => 'Comentario',
'imagelinks'        => 'Binclos ta la imachen',
'linkstoimage'      => 'Istas son as pachinas que tienen binclos con ista imachen:',
'nolinkstoimage'    => 'Denguna pachina tiene un binclo con ista imachen.',
'sharedupload'      => 'Iste archibo ye compartito y puede archivo está compartido y puede que siga emplegato en atros procheutos.',

# Unwatched pages
'unwatchedpages' => 'Pachinas sin bexilar',

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

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'nmembers'                => '$1 {{PLURAL:$1|miembro|miembros}}',
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
'mostcategories'          => 'Pachinas con más categorías',
'mostimages'              => 'Imachens más emplegatas',
'mostrevisions'           => 'Pachinas con más edizions',
'allpages'                => 'Todas as pachinas',
'prefixindex'             => 'Pachinas por prefixo',
'shortpages'              => 'Pachinas más curtas',
'longpages'               => 'Pachinas más largas',
'deadendpages'            => 'Pachinas sin salida',
'specialpages'            => 'Pachinas espezials',
'restrictedpheading'      => 'Pachinas espezials restrinxitas',
'rclsub'                  => '(enta pachinas enlazadas dende "$1")',
'newpages'                => 'Pachinas nuebas',
'ancientpages'            => 'Pachinas más biellas',
'move'                    => 'Tresladar',

'alphaindexline' => '$1 á $2',
'version'        => 'Bersión',

# Special:Log
'specialloguserlabel' => 'Usuario:',
'log'                 => 'Rechistros',
'all-logs-page'       => 'Toz os rechistros',
'log-search-submit'   => 'Ir-ie',

# Special:Allpages
'nextpage'       => 'Siguién pachina ($1)',
'allarticles'    => 'Toz os articlos',
'allinnamespace' => 'Todas as pachinas (espazio $1)',
'allpagessubmit' => 'Amostrar',

# Watchlist
'watchlist'       => 'Lista de seguimiento',
'mywatchlist'     => 'A mía lista de seguimiento',
'nowatchlist'     => 'No tiens denguna pachina en a lista de seguimiento.',
'addedwatch'      => 'Adibiu ta la tuya lista de seguimiento',
'watch'           => 'Bexilar',
'watchthispage'   => 'Bexilar ista pachina',
'unwatch'         => 'Dixar de bexilar',
'unwatchthispage' => 'Dixar de bexilar',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Bexilando...',
'unwatching' => 'Deixar de bexilar...',

# Delete/protect/revert
'historywarning' => 'Pare cuenta: A pachina que ba a borrar tiene un istorial de cambeos:',
'actioncomplete' => 'Aizión rematada',
'deletedarticle' => 'borrato "$1"',
'dellogpage'     => 'Rechistro de borraus',
'rollbacklink'   => 'Esfer',
'protectcomment' => 'Razón ta protexer:',

# Undelete
'undeletepagetext' => "As pachinas siguiens han siu borradas, pero encara son en l'archibo y podría estar restauradas. El archibo se borra periodicamén.",

# Namespace form on various pages
'namespace'      => 'Espazio de nombres:',
'blanknamespace' => '(Prenzipal)',

# Contributions
'contributions' => "Contrebuzions de l'usuario",
'mycontris'     => 'As mías contrebuzions',

'sp-contributions-blocklog' => 'Rechistro de bloqueyos',
'sp-contributions-username' => "Adreza IP u nombre d'usuario:",

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
'ipboptions'   => '2 oras:2 hours,1 día:1 day,3 días:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 año:1 year,ta cutio:infinite',
'blocklink'    => 'bloquiar',
'contribslink' => 'contrebuzions',
'blocklogpage' => 'Rechistro de bloqueyos',

# Move page
'movearticle'     => 'Tresladar pachina:',
'movepagebtn'     => 'Tresladar pachina',
'movepage-moved'  => '<big>\'\'\'"$1" ha estato tresladato á "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'1movedto2'       => '[[$1]] tresladada á [[$2]]',
'1movedto2_redir' => '[[$1]] tresladada á [[$2]] sobre una reendrezera',
'movelogpage'     => 'Rechistro de treslatos',

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
'tooltip-t-upload'                => 'Puyar imáchens u archibos multimedia ta o serbidor',
'tooltip-t-specialpages'          => 'Lista de todas as pachinas espezials',
'tooltip-ca-nstab-user'           => "Beyer a pachina d'usuario",
'tooltip-ca-nstab-project'        => "Beyer a pachina d'o procheuto",
'tooltip-ca-nstab-image'          => "Beyer a pachina d'a imachen",
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

# Browsing diffs
'previousdiff' => '← Ir ta esferenzias anteriors',

# Media information
'file-info-size'       => "($1 × $2 píxels; grandaria de l'archivo: $3; tipo MIME: $4)",
'file-nohires'         => '<small>No bi ha garra bersión con mayor resoluzión.</small>',
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
* make (fabricador)
* model (modelo)
* datetimeoriginal (calendata y ora de creyazión)
* exposuretime (tiempo d'esposizión)
* fnumber (Numero f)
* focallength (longaria focal)",

# EXIF tags
'exif-gpstimestamp' => 'Tiempo GPS (reloch atomico)',

# External editor support
'edit-externally'      => 'Editar iste archibo fendo serbir una aplicazión esterna',
'edit-externally-help' => 'Leiga as [http://meta.wikimedia.org/wiki/Help:External_editors instruzions de confegurazión] (en anglés) ta más informazión.',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'todo',

);
